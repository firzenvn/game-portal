<?php
namespace Util;


use DOMDocument;
use Illuminate\Support\Facades\Cache;
use Log;
use Whoops\Example\Exception;

class CommonHelper{

    public static  function ckEditorPreparer($vTexte)
    {
        $aTexte = explode("\n",$vTexte);
        for ($i=0;$i<count($aTexte)-1;$i++)
        {$aTexte[$i] .= '\\';}
        return implode("\n",$aTexte);
    }

    public static function rand_string( $length ) {
        $str ='';
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        $size = strlen( $chars );
        for( $i = 0; $i < $length; $i++ ) {

            $str .= $chars[ rand( 0, $size - 1 ) ];
        }

        return $str;
    }

    public static function vietnameseToASCII($sample){
        $marTViet=array("à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
            "ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề"
        ,"ế","ệ","ể","ễ",
            "ì","í","ị","ỉ","ĩ",
            "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ"
        ,"ờ","ớ","ợ","ở","ỡ",
            "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
            "ỳ","ý","ỵ","ỷ","ỹ",
            "đ",
            "À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă"
        ,"Ằ","Ắ","Ặ","Ẳ","Ẵ",
            "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
            "Ì","Í","Ị","Ỉ","Ĩ",
            "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ"
        ,"Ờ","Ớ","Ợ","Ở","Ỡ",
            "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
            "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
            "Đ", " ", "\\" ,"/", "!","@","#","$","%","^","&","*","(",")","_","+","~","`",":",";","'",'"',"<",">",",",".","?","|");
        $marKoDau=array("a","a","a","a","a","a","a","a","a","a","a"
        ,"a","a","a","a","a","a",
            "e","e","e","e","e","e","e","e","e","e","e",
            "i","i","i","i","i",
            "o","o","o","o","o","o","o","o","o","o","o","o"
        ,"o","o","o","o","o",
            "u","u","u","u","u","u","u","u","u","u","u",
            "y","y","y","y","y",
            "d",
            "A","A","A","A","A","A","A","A","A","A","A","A"
        ,"A","A","A","A","A",
            "E","E","E","E","E","E","E","E","E","E","E",
            "I","I","I","I","I",
            "O","O","O","O","O","O","O","O","O","O","O","O"
        ,"O","O","O","O","O",
            "U","U","U","U","U","U","U","U","U","U","U",
            "Y","Y","Y","Y","Y",
            "D", "-", "-" ,"-", "-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-","-");
        return str_replace($marTViet,$marKoDau,$sample);
    }


    public static function objectToArray($d) {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $d);
        }
        else {
            // Return array
            return $d;
        }
    }
    public static  function getCategoryForCombo($rows){

        $right = array();
        $result = array();

        foreach ($rows as $item) {

            if(count($right)>0){
                while ($right[count($right)-1]->rgt<$item->rgt) {
                    array_pop($right);
                }
            }
            foreach ($right as $rightItem) {
                $item->name = "-".$item->name;
            }
            array_push($result, $item);

            array_push($right,$item);
        }
        return $result;
    }

    public static function stripExtensions( $filename ){
        return preg_replace("/\\.[^.\\s]{3,4}$/", "", $filename);
    }

    public static function getExtensions($filename)
    {
        return $ext = pathinfo($filename, PATHINFO_EXTENSION);
    }

    public static function keyValArrToJsonString($sampleArr, $delimiter = '$$$'){
        if(!$sampleArr) return null;
        $resultArr = array();
        foreach ($sampleArr as $aKeyValPair) {
            $tmpArr = explode($delimiter, $aKeyValPair);
            $resultArr[$tmpArr[0]] = $tmpArr[1];
        }
        return json_encode($resultArr);

    }

    public static function numberFormat($sample)
    {
        return  money_format("%!n", $sample);
    }

    public static function rss_to_array($tag, $array, $url, $forum_name='') {
        if(Cache::has($forum_name.'_forum_rss_array')){
            return json_decode(Cache::get($forum_name.'_forum_rss_array'),true);
        }
        $doc = new DOMdocument();
        if(@$doc->load($url) === false){
            return array();
        }
        $rss_array = array();
        $items = array();
        foreach($doc-> getElementsByTagName($tag) AS $node) {
            foreach($array AS $key => $value) {
                $items[$value] = $node->getElementsByTagName($value)->item(0)->nodeValue;
            }
            array_push($rss_array, $items);
        }

        Cache::put($forum_name.'_forum_rss_array',json_encode($rss_array), 60);
        return $rss_array;
    }

    public static function stringGetLeft($sample, $length, $posfix = '...'){
        if(strlen($sample) <= $length)
            return $sample;
        else
            return substr($sample, 0, $length).$posfix;
    }

    /*
     * $fb[0]->share_count
     * $fb[0]->like_count
     * $fb[0]->comment_count
     */
    public static function getFacebookInfo($url){
        if(Cache::has(urlencode($url).'_fb_json')){
            return json_decode(Cache::get(urlencode($url).'_fb_json'));
        }
        $fql  = "SELECT share_count, like_count, comment_count, click_count FROM link_stat WHERE url = '".$url."'";

        $apifql="https://api.facebook.com/method/fql.query?format=json&query=".urlencode($fql);
        $fb_json=file_get_contents($apifql);
        Cache::add(urlencode($url).'_fb_json',$fb_json,10);
        return json_decode($fb_json);
    }

}

