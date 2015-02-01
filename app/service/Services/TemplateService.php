<?php

namespace Services;

use EModel\Templates;

interface TemplateService {


    /**
     * @param $id
     * @return Templates
     */
    public function requireById($id);
}