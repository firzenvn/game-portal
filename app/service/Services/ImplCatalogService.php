<?php
namespace Services;


use EModel\Category;
use EModel\Countries;
use Illuminate\Support\Facades\DB;
use Util\CommonHelper;
use Util\Exceptions\BusinessException;
use Util\Exceptions\EntityNotFoundException;

class ImplCatalogService extends BaseService implements CatalogService
{


    /**
     * @param $id
     * @return Countries
     */
    public function requireCountryById($id)
    {
        $model = Countries::find($id);

        if ( ! $model) {
            throw new EntityNotFoundException;
        }
        return $model;
    }

    /**
     * @param $id
     * @return Category
     */
    public function requireCategoryById($id)
    {
        $model = Category::find($id);

        if ( ! $model) {
            throw new EntityNotFoundException;
        }
        return $model;
    }


    //@todo: more work
    public function getCategoriesTree($parentCatId){

        if(!$parentCatId) throw new BusinessException("groupCode null!");
        $category_Item = $this->requireCategoryById($parentCatId);
        $group_code = $category_Item->group_code;

        return DB::select("select category.* from category  ,category as parent
        where category.lft between parent.lft and parent.rgt
        and parent.id = ? and category.group_code = ? order by category.lft asc", array($parentCatId, $group_code));
    }



    public function getAllCategory()
    {
        return Category::orderBy('group_code')
            ->orderBy('lft');
    }


    public function removeCategory($id)
    {
        $category_Item = $this->requireCategoryById($id);
        //if iam a child
        if($category_Item->lft == $category_Item->rgt - 1){
            $this->reorderTreeOnDelete($category_Item);
        }else{
            throw new BusinessException("Không được xóa nhóm có nhóm con!");
        }
        $category_Item->delete();
    }

    private function reorderTreeOnDelete($categoryItem){
        $group_code = $categoryItem->group_code;
        Category::where('rgt', '>',$categoryItem->rgt )
            ->where('group_code', '=',$group_code )
            ->decrement('rgt',2);

        Category::where('lft', '>',$categoryItem->rgt )
            ->where('group_code', '=',$group_code )
            ->decrement('lft',2);
    }

    public function addCategory($data)
    {
        $parentId = $data["parentId"];
        $parentItem = $this->requireCategoryById($parentId);
        $tmpData = $data;

        if($data["name"]){
            $tmpData["alias"] = CommonHelper::vietnameseToASCII($data["name"]);
        }

        $tmpData["group_code"] = $parentItem->group_code;
        $tmpData["lft"] = $parentItem->rgt;
        $tmpData["rgt"] = $parentItem->rgt + 1;
        $this->reorderTree(  $parentItem->group_code, $parentItem->rgt - 1);
        unset($tmpData["parentId"]);
        return  Category::insertGetId($tmpData);
    }

    private function reorderTree($group_code, $lastRight){

        Category::where('rgt', '>',$lastRight )
            ->where('group_code', '=',$group_code )
            ->increment('rgt', 2);

        Category::where('lft', '>',$lastRight )
            ->where('group_code', '=',$group_code )
            ->increment('lft', 2);

    }

    public function updateCategory($data)
    {
        $tmp = $data;
        if($data["name"]){
            $tmp["alias"] = CommonHelper::vietnameseToASCII($data["name"]);
        }

        $categoryItem = $this->requireCategoryById($data['id']);
        if($categoryItem->modifiable == 0)
            throw new BusinessException("Không thể sửa category này. Vui lòng liên hệ admin.");
        Category::where('id','=',$data['id'])->update($tmp);
    }

    public function getAllCategoryGroup()
    {
        return Category::where('lft', '=',1)->get();
    }

    /**
     * @param $groupCode
     * @return Category
     */
    public function getRootCategory($groupCode){
        return Category::where('group_code','=',$groupCode)
            ->where('lft','=',1)
            ->first();
    }

    /**
     * @param $code
     * @return mixed
     * @throws \Util\Exceptions\BusinessException
     */
    public function getAllCategoriesByGroupCode($code)
    {
        if(!$code) throw new BusinessException("groupCode null!");
        $category_Item = $this->getRootCategory($code);
        $parentCatId = $category_Item->id;

        return DB::select("select category.* from category  ,category as parent
        where category.lft between parent.lft and parent.rgt
        and parent.id = ? and category.group_code = ? order by category.lft asc", array($parentCatId, $code));
    }

    /**
     * @param $catId
     * @return \Illuminate\Database\Eloquent\Collection|static
     */
    public function getAllParent($catId)
    {
        $category_Item = $this->requireCategoryById($catId);
        return Category::where('group_code','=',$category_Item->group_code)
            ->where('lft','<',$category_Item->lft)
            ->where('rgt','>',$category_Item->rgt)
            ->get();
    }

    public function getAllChildren($catId)
    {
        $category_Item = $this->requireCategoryById($catId);
        return Category::where('group_code','=',$category_Item->group_code)
            ->where('lft','>',$category_Item->lft)
            ->where('rgt','<',$category_Item->rgt)
            ->get();
    }

    public function getAllCategoriesByParentCode($code)
    {

        if(!$code) throw new BusinessException("parentCode null!");
        $category_Item = Category::where('code','=', $code)->get();
        $parentCatId = $category_Item->id;
        $groupCode = $category_Item->group_code;

        return DB::select("select category.* from category  ,category as parent
        where category.lft between parent.lft and parent.rgt
        and parent.id = ? and category.group_code = ? order by category.lft asc", array($parentCatId, $groupCode));

    }

    public function getCategoryAsKendoDataSource($groupCode)
    {
        $rows = $this->getAllCategoriesByGroupCode($groupCode);
        return $this->getTreeKendoDatasource($rows);
    }

    private function getTreeKendoDatasource($rows){
        $right = array();
        $result = array();

        foreach ($rows as $anItem) {
            $item = get_object_vars($anItem);
            $item["text"] = $item["name"];

            if(count($right)>0){
                while ($right[count($right)-1]['rgt']<$item['rgt']) {
                    array_pop($right);
                }
            }
            if($item['lft'] < $item['rgt'] -1){

                $item["items"] = array();
            }
            if(count($right)>0){
                $latestHasChildren = &$this->findLatestHasChildren($result[(count($result)-1)], $right[count($right)-1]);

                $latestHasChildren["items"][] =  $item;

            }else{
                call_user_func_array("array_push", array(&$result,&$item));
            }

            call_user_func_array("array_push", array(&$right,&$item));

        }
        return $result;
    }
    private function &findLatestHasChildren(&$arr, $sample){

        if(array_key_exists("items",$arr)){
            if(count($arr["items"]) == 0 || !array_key_exists("items",$arr["items"][count($arr["items"]) -1]) ){
                return $arr;
            }elseif(array_key_exists("items",$arr["items"][count($arr["items"]) -1])
                && $arr["items"][count($arr["items"]) -1]["rgt"] < $sample["rgt"]){
                return $arr;
            }
            else {
                return $this->findLatestHasChildren($arr["items"][count($arr["items"]) -1],$sample);
            }
        }
        else{
            return $arr;
        }
    }

    public function getCategoryByCodeInGroup($groupCode, $code)
    {
        return Category::where('code','=', $code)
            ->where('group_code','=', $groupCode)
            ->first();
    }
}