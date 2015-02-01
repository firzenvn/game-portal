<?php
namespace Util;

use Illuminate\Support\Facades\Config;

class AppDirectoryHelper{

    public static  function getAllTemplateFile()
    {

        $result = array();
        $dir = app_path().'/views/layout';
        $tmpArr = glob($dir .'/'. "ftpl*");
        foreach ($tmpArr as $name) {
            $result[basename($name, ".php")] = basename($name, ".php");
        }
        return $result;
    }

    public static function getAllFrontController()
    {
        $result = array();
        $dir = app_path().'/controllers/Controllers/Front';
        $tmpArr = glob($dir .'/'. "*");
        foreach ($tmpArr as $name) {
            $result[basename($name, ".php")] = basename($name, ".php");
        }
        return $result;
    }

    public static  function getAllBlockFile()
    {

        $result = array();
        $dir = app_path().'/views/includes/blocks';
        $tmpArr = glob($dir .'/'. "*");
        foreach ($tmpArr as $name) {
            $result[basename($name, ".php")] = basename($name, ".php");
        }
        return $result;
    }


}

