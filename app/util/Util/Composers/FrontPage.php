<?php
namespace Util\Composers;
use Illuminate\Support\MessageBag;
use Auth, Session, Config, App;

class FrontPage{

    /**
     * Compose the view with the following variables bound do it
     * @param  View $view The View
     * @return null
     */
    public function compose($view)
    {

        /*$view->with('vendor', Config::get('::app.custom_config.vendor'))
            ->with('menu_items', Config::get('::app.custom_config.menu_items') )
            ->with('success', Session::get('success' , new MessageBag ) )
            ->with('urlSegment', Config::get('::app.custom_config.admin_url_segment'));*/

    }

}