<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ChatController extends \BaseController {

    function postState(){
        $log = array();
        $file = public_path().'/media/chat/'.Input::get('file').'.txt';
        if(!file_exists($file)){
            File::put($file,'');
        }
        $lines = file($file);
        $log['state'] = count($lines);


        return Response::json($log);
    }

    function postFirst(){
        $log = array();
        $state = Input::get('state');
        $file = public_path().'/media/chat/'.Input::get('file').'.txt';
        $lines = file($file);
        $text= array();
        $log['state'] = $state + count($lines) - $state;
        foreach ($lines as $line_num => $line)
        {
            if($line_num >= $state-15){
                $text[] =  $line = str_replace("\n", "", $line);
            }

        }
        $log['text'] = $text;

        return Response::json($log);
    }

    function postUpdate(){
        $log = array();
        $state = Input::get('state');
        $file = public_path().'/media/chat/'.Input::get('file').'.txt';
        $lines = file($file);
        $count =  count($lines);
        if($state == $count){
            $log['state'] = $state;
            $log['text'] = false;
        }
        else{
            $text= array();
            $log['state'] = $state + count($lines) - $state;
            foreach ($lines as $line_num => $line)
            {
                if($line_num >= $state){
                    $text[] =  $line = str_replace("\n", "", $line);
                }

            }
            $log['text'] = $text;
        }

        return Response::json($log);
    }

    public function postSend()
    {
        $file = public_path().'/media/chat/'.Input::get('file').'.txt';
        $nickname = htmlentities(strip_tags(Input::get('nickname')));
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        $message = htmlentities(strip_tags(Input::get('message')));
        $time = date('H:i d/m/y',time());
        if (($message) != "\n") {

            if (preg_match($reg_exUrl, $message, $url)) {
                $message = preg_replace($reg_exUrl, '<a href="' . $url[0] . '" target="_blank">' . $url[0] . '</a>', $message);
            }


            fwrite(fopen($file, 'a'), "<time>".$time."</time><span class='username'>" . $nickname . "</span><span class='message'>" . $message = str_replace("\n", " ", $message). "</span>" . "\n" );
        }
    }

}