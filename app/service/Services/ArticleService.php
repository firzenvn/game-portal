<?php

namespace Services;



use EModel\Article\Articles;
use EModel\Page\Pages;

interface ArticleService {


    /**
     * @param $id
     * @return Articles
     */
    public function requireById($id);

    public function delete($id);


}