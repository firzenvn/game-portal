<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

use EModel\Games;
use Illuminate\Support\MessageBag;
use Util\Oauth2\PlaygateIDOauth2;
use Util\Oauth2\PlaygateIDRestClient;
use Util\Oauth2Helper;
use Yangqi\Htmldom\Htmldom;

App::before(function($request)
{


    // Singleton (global) object
    App::singleton('myApp', function(){
        $app = new stdClass;
        $domain=Request::server('HTTP_HOST');
        $baseDomain = Config::get('::app.base_domain');
        $domainParts=  explode('.',substr($domain, 0, strlen($domain) - strlen($baseDomain)) );

        $subdomain='';
        if(count($domainParts)>=2){
            $subdomain=$domainParts[count($domainParts)-2];
        }
        $externalPortal='';
        if(count($domainParts)>=3){
            $externalPortal=$domainParts[count($domainParts)-3];
        }
        if($subdomain == 'news'){
            $app->isOnSubdomain= false;
            $app->isOnNews= true;
        }else{
            $game=Games::where('subdomain','=',$subdomain)->first();
            $app->game=$game;
            $app->isOnSubdomain=$subdomain?true:false;
            $app->isOnNews= false;
        }

        $app->externalPortal=$externalPortal;
        if($app->isOnSubdomain){
            if($app->externalPortal == SohaHelper::EXTERNAL_PORTAL_CODE){
                View::addLocation(public_path().'/media/game-tpl/'.$subdomain.'/soha_layouts');
            }elseif($app->externalPortal == ZingHelper::EXTERNAL_PORTAL_CODE){
                View::addLocation(public_path().'/media/game-tpl/'.$subdomain.'/zing_layouts');
            }
            else{
                if($subdomain == 'vqmm')
                    View::addLocation(public_path().'/media/game-tpl/'.$subdomain);
                else{
                    View::addLocation(public_path().'/media/game-tpl/'.$subdomain.'/default_layouts');
                    View::addLocation(public_path().'/media/game-tpl/'.$subdomain.'/lien_chien');
                }


            }
        }else{
            if($app->isOnNews)
                View::addLocation(public_path().'/media/news-tpl/default_layouts');
            else
                return App::abort(503, "System is under construction");
        }
        return $app;
    });
    $app = App::make('myApp');
    View::share('myApp', $app);
    if($app->isOnSubdomain){
        $maintain_url=array();
        $whitelist_ips=array();
        @include_once(public_path('media/game-tpl/'.$app->game->subdomain.'/maintain_url.php'));
        if(!empty($whitelist_ips) && !in_array(Request::getClientIp(),$whitelist_ips)) {
            foreach ($maintain_url as $aUrl) {
                if (Request::is($aUrl)) {
                    return Redirect::to('/maintain');
                }
            }
        }
    }

});


App::after(function($request, $response)
{
    $app = App::make('myApp');
    if($app->externalPortal && $app->isOnSubdomain){
        if($response instanceof Illuminate\Http\Response){
            $content = $response->getOriginalContent();
            if($app->externalPortal == SohaHelper::EXTERNAL_PORTAL_CODE){
                $html = new Htmldom($content);

                foreach($html->find('a') as $element){
                    if (preg_match("/^\//i",$element->href))
                    {
                        if(preg_match("/^\/media/i",$element->href)){

                        }else{
                            if(!in_array($element->href,array('/nap-soha', '/vip' , '/nap-tien', '/gift-code')))
                            {
                                if(!@include_once(public_path('media/game-tpl/'.$app->game->subdomain.'/extenal_config.php')) ){
                                    $soha_excluded_url=array();
                                }

                                $linkParent = $element->parent();
                                if($linkParent->tag == 'li' && in_array($element->href, $soha_excluded_url)){
                                    $linkParent->outertext = '';
                                }
                                $element->target = '_top';
                                $element->href = 'http://'.$app->game->subdomain.'.sohagame.vn'.$element->href;
                            }
                        }
                    }

                    if (preg_match("/^http:\/\/".$app->game->subdomain.".maxgate.vn/i",$element->href))
                    {
                        $replace = 'http://'.$app->game->subdomain.'.sohagame.vn';
                        $element->href = str_replace('http://'.$app->game->subdomain.'.maxgate.vn', $replace, $element->href);
                        $element->target = '_top';
                    }
                }



                $doc = $html;
                $response->setContent($doc);
            }
        }

    }



});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
    if (Auth::guest()) return Redirect::guest('login');
});

Route::filter('maintain', function()
{
    $maintain_url=array();

    require_once public_path('media/config/maintain_url.php');
    foreach ($maintain_url as $aUrl) {
        if (Request::is($aUrl))
        {
            Redirect::to('/maintain');
        }
    }
});



Route::filter('auth-play-trial', function()
{
    $app = App::make('myApp');

    if($app->externalPortal){
        throw new \Util\Exceptions\SystemException('Url không tồn tại');

    }
    else{
        if(Auth::user()){
            return Redirect::to('/play');
        }
    }
});


Route::filter('auth-play', function()
{
    $app = App::make('myApp');

    if($app->externalPortal == SohaHelper::EXTERNAL_PORTAL_CODE ){
        $result = SohaHelper::processAuthSso();
        if($result !== null)
            return $result;
    }
    elseif($app->externalPortal == ZingHelper::EXTERNAL_PORTAL_CODE){
        if(Auth::guest())
            return Redirect::to('http://login.me.zing.vn/?url=http://me.zing.vn/apps/'.ZingHelper::getAppId());
    }
    else{

        if(Auth::user()){
            $user = Auth::user();
            if($user->source != 'maxgate')
                return Redirect::to(\Util\GameHelper::getOauth2_BASE_URL().'/users/login?return_url='.Request::url());
        }
        if (Auth::guest()){
            session_start();
            $trialSession = PlayTrialSession::where('session_id', '=', session_id())->first();
            if($trialSession)
                return Redirect::to('/play-trial');
            else
                return Redirect::to(\Util\GameHelper::getOauth2_BASE_URL().'/users/login?return_url='.Request::url());
        }


    }


});


Route::filter('auth-sso', function()
{
    $app = App::make('myApp');

    if($app->externalPortal == SohaHelper::EXTERNAL_PORTAL_CODE ){
        $result = SohaHelper::processAuthSso();
        if($result !== null)
            return $result;
    }
    elseif($app->externalPortal == ZingHelper::EXTERNAL_PORTAL_CODE){
        if(Auth::guest())
            return Redirect::to('http://login.me.zing.vn/?url=http://me.zing.vn/apps/'.ZingHelper::getAppId());
    }
    else{
        if(Auth::user()){
            $user = Auth::user();
            if($user->source != 'maxgate')
                return Redirect::to(\Util\GameHelper::getOauth2_BASE_URL().'/users/login?return_url='.Request::url());
        }

        if (Auth::guest()
            || !Oauth2Helper::validateToken()
            || !Oauth2Helper::loadAccounts()->loaded)
            return Redirect::to(\Util\GameHelper::getOauth2_BASE_URL().'/users/login?return_url='.Request::url());

    }


});

/**
 * Filter kiểm tra quyền truy cập vào các tính năng dành cho quản trị
 */
Route::filter('admin', function(){
    if (!Auth::user()->hasRole('admin'))
    {
        return App::abort('403', 'You are not authorized.');
    }
});

/**
 * Kiểm tra quyền truy cập
 */
Route::filter('permission', function(){
    if (!Authority::can('access',Request::path()) && !Auth::user()->hasRole('admin') && !Auth::user()->hasRole('super-admin'))
    {
        return Redirect::to('users/dashboard')->with('error','Bạn không có quyền truy cập tính năng này');
    }
});


Route::filter('logAction', function($route, $request, $response){
    Activity::log(array(
        'contentId'   => '$user->id',
        'contentType' => 'User',
        'description' => 'Created a User',
        'details'     => 'Username: $user->username',
        'updated'     => '$id ? true : false',
    ));

});

Route::filter('adminFilter', 'Util\Filters\Admin');

Route::filter('injectPageId', function($route,$request){

    Input::merge(array('pageId'=>$route->getAction()['pageId'] ));

});



Route::filter('auth.basic', function()
{
    return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
    if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
    if (Session::token() != Input::get('_token'))
    {
        throw new Illuminate\Session\TokenMismatchException;
    }
});

/**
 * Kiểm tra quyền truy cập
 */
Route::filter('permission_check', function(){
    if (!Authority::can('access',Request::path()) && !Auth::user()->hasRole('admin') && !Auth::user()->hasRole('super-admin'))
    {
        return Redirect::to('admin/login')->with('errors',new MessageBag( array( 'Bạn không có quyền truy cập!' ) ));
    }
});
