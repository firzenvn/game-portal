<?php

namespace Services;



use EModel\Pages;

interface PageService {


    /**
     * @param $id
     * @return Pages
     */
    public function requireById($id);

    public function getAllPageWithTemplate();
}