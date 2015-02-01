<?php

namespace Controllers\Admin;

use EModel\Category;
use EModel\Country\Countries;
use EModel\Country\CountriesInterface;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Services\CatalogService;
use Util\Exceptions\BusinessException;
use Util\Exceptions\EntityNotFoundException;


class CatalogsController extends AdminBaseController {


    /**
     * @param CountriesInterface $countryRepoInterface
     */
    function __construct(CatalogService $catalogService)
    {
        parent::__construct();
        $this->catalogService = $catalogService;


    }


    public function getCountries()
    {

        $this->layout->content = View::make('admin.country_index')
            ->with( 'items' , Countries::all());

    }


    public function getDeleteCountry( $id ){

        $country = $this->catalogService->requireCountryById($id);
        $country->delete();
        $message = 'The item was successfully removed.';
        return Redirect::to( '/admin/catalogs/countries' )
            ->with('success', new MessageBag( array( $message ) ) );

    }


    public function getCountryNew(  ){

        $this->layout->content = View::make('admin.country_new');

    }

    public function getCountryEdit( $id ){

        try{
            $item = $this->catalogService->requireCountryById($id);
        } catch( EntityNotFoundException $e ){
            return Redirect::to( '/admin/catalogs/countries')->
                with('errors', new MessageBag( array("An item with the ID:$id could not be found.") ) );
        }

        $this->layout->content = View::make('admin.country_edit')
            ->with( 'item' , $item );

    }


    public function postCountryNew()
    {
        $newRecord = new Countries(Input::all());
        $valid = $newRecord->isValid();
        if( !$valid )
            return Redirect::to( '/admin/catalogs/country-new')->
                with( 'errors' , $newRecord->getErrors() )->withInput();
        $newRecord->save();
        return Redirect::to( '/admin/catalogs/countries' )->
            with( 'success' , new MessageBag( array( 'Thêm mới thành công' ) ) );
    }

    public function postCountryEdit($id){

        $record = $this->catalogService->requireCountryById( $id );
        $record->fill( Input::all() );

        $valid =  $record->isValid();

        if( !$valid )
            return Redirect::to( '/admin/catalogs/country-edit/'.$id)->
                with( 'errors' , $record->getErrors() )->withInput();

        // Run the hydration method that populates anything else that is required / runs any other
        // model interactions and save it.
        $record->save();

        // Redirect that shit man! You did good! Validated and saved, man mum would be proud!
        return Redirect::to( '/admin/catalogs/countries' )->
            with( 'success' , new MessageBag( array( 'Sửa thành công' ) ) );
    }

    public function getCategories($code)
    {
        if(!$code) throw new BusinessException("mã nhóm không tồn tại");
        $kendoDataSource = $this->catalogService->getCategoryAsKendoDataSource($code);
        $this->layout->content = View::make('admin.category_index')
        ->with('kendoDataSource', json_encode($kendoDataSource));
    }

    public function postGetCategoryParent(){
        $id = Input::get('id');

        $categoryItem = $this->catalogService->requireCategoryById($id);
        $directParent = $categoryItem->getDirectParent();
        if(!$directParent)
            return Response::json(array('success'=>true, "upable"=>false, "downable"=>false));
        else{
            $upable = true;
            $downable = true;

            if($directParent->lft == $categoryItem->lft - 1 )
                $upable = false;
            if($directParent->rgt == $categoryItem->rgt + 1 )
                $downable = false;
            return Response::json(array('success'=>true, "upable"=>$upable, "downable"=>$downable));
        }
    }

    public function postSaveCategory(){

        $code = Input::get('code');
        $name = Input::get('name');
        $description = Input::get('description');
        $parentId = Input::get('parentId');
        $id = Input::get('id');


        if(!$id){
            $newRecord = new Category();
            $newRecord->code = $code;
            $newRecord->name = $name;
            $parentCat = $this->catalogService->requireCategoryById($parentId);
            if($parentCat)
                Response::json(array('success'=>false,'msg'=>'Nhóm cha không tồn tại'));
            $newRecord->group_code = $parentCat->group_code;
        }else{
            $newRecord = $this->catalogService->requireCategoryById($id);
            $newRecord->code = $code;
            $newRecord->name = $name;
        }

        $valid = $newRecord->isValid();
        if(!$valid)
            return Response::json(array('success'=>false,'msg'=>$this->errorToString($newRecord->getErrors())));

        if(!$id){
            $id = $this->catalogService->addCategory(
                array("code"=>$code,"name"=>$name,"description"=>$description,"parentId"=>$parentId));
        }else{
            $data = array("id"=>$id, "code"=>$code,"name"=>$name,"description"=>$description);
            $this->catalogService->updateCategory($data);
        }

        return Response::json(array('success'=>true,'id'=>$id));

    }

    public function postRemoveCategory(){
        $id = Input::get('id');
        $category = $this->catalogService->requireCategoryById($id);
        if(!$category)
            throw new BusinessException('id '.$id.' không tồn tai!');
        $this->catalogService->removeCategory($id);
        return Response::json(array('success'=>true));
    }


    public function postUpCategory(){
        $id = Input::get('id');
        $category = $this->catalogService->requireCategoryById($id);
        $category->upMyPosition();
//        return Redirect::to( '/admin/catalogs/categories/'.$category->group_code);
        return Response::json(array('success'=>true));
    }


    public function postDownCategory(){
        $id = Input::get('id');
        $category = $this->catalogService->requireCategoryById($id);
        $category->downMyPosition();
        return Response::json(array('success'=>true));
    }

}
