<?php namespace Util\Filters;
use Auth, Redirect, Config;

class Admin {


    public function filter()
    {

        if ( Auth::guest() )
            return Redirect::guest( Config::get('::app.custom_config.admin_url_segment').'/login');
//        $_SESSION['isLoggedIn'] = Auth::check() ? true : false;
        if (session_id() == '') {
            @session_start();
            /* or Session:start(); */
        }
        $_SESSION['isLoggedIn'] = Auth::check() ? true : false;
    }

}