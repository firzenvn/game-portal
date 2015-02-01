<?php

namespace Services;



use EModel\Category;
use EModel\Countries;


interface CatalogService {


    /**
     * @param $id
     * @return Countries
     */
    public function requireCountryById($id);


    /**
     * @param $id
     * @return Category
     */
    public function requireCategoryById($id);



    public function getCategoriesTree($parentCatId);



    public function getAllCategory();


    public function removeCategory($id);



    public function addCategory($data);


    public function updateCategory($data);

    public function getAllCategoryGroup();
    /**
     * @param $groupCode
     * @return Category
     */
    public function getRootCategory($groupCode);
    /**
     * @param $code
     * @return mixed
     * @throws \Util\Exceptions\BusinessException
     */
    public function getAllCategoriesByGroupCode($code);

    /**
     * @param $catId
     * @return \Illuminate\Database\Eloquent\Collection|static
     */
    public function getAllParent($catId);

    public function getAllChildren($catId);

    public function getAllCategoriesByParentCode($code);
    public function getCategoryAsKendoDataSource($groupCode);



    public function getCategoryByCodeInGroup($groupCode, $code);
}