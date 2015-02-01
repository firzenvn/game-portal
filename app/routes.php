<?php

use EModel\GameServer;
use EModel\Pages;

$adminUrlSegment = Config::get('::app.custom_config.admin_url_segment');

// Filter all requests ensuring a user is logged in when this filter is called

Route::controller( $adminUrlSegment.'/giftcodes'  , 'Controllers\Admin\GiftCodesController' );
Route::controller( $adminUrlSegment.'/galleries' , 'Controllers\Admin\GalleriesController' );
Route::controller( $adminUrlSegment.'/users'     , 'Controllers\Admin\UsersController' );
Route::controller( $adminUrlSegment.'/settings'  , 'Controllers\Admin\SettingsController' );
Route::controller( $adminUrlSegment.'/blocks'    , 'Controllers\Admin\BlocksController' );
Route::controller( $adminUrlSegment.'/catalogs'  , 'Controllers\Admin\CatalogsController' );
Route::controller( $adminUrlSegment.'/templates' , 'Controllers\Admin\TemplatesController' );
Route::controller( $adminUrlSegment.'/pages'     , 'Controllers\Admin\PagesController' );
Route::controller( $adminUrlSegment.'/articles'  , 'Controllers\Admin\ArticlesController' );
Route::controller( $adminUrlSegment.'/games'  , 'Controllers\Admin\GamesController' );
Route::controller( $adminUrlSegment.'/reports' , 'Controllers\Admin\ReportsController'  );
Route::controller( $adminUrlSegment.'/league' , 'Controllers\Admin\LeaguesController'  );
Route::controller( $adminUrlSegment              , 'Controllers\Admin\DashController'  );


//---------------Front Module-----------------------

//Route::get('login-callback','UsersController@loginCallback');
Route::get('sso-login-callback','UsersController@ssoLoginCallback');

Route::get('sso-logout-callback','UsersController@ssoLogoutCallback');

Route::group(array('domain' => 'vqmm.dev.maxgate.vn'), function()
{
    Route::get('/', 'SpinController@home');
    Route::post('/check', 'SpinController@check');
    Route::post('/result', 'SpinController@result');
});


//--------For game detail----------------------------

Route::get('/tin-tuc/{catSlug}/{slug}', 'NewsController@getDetail');
Route::get('/', 'HomeController@index');
Route::get('/tin-tuc/{catSlug?}', 'NewsController@news');
Route::get('/huong-dan/{slug}', 'NewsController@guideDetail');
Route::post('/login','UsersController@login');
Route::post('/register-sso','UsersController@registerSso');
Route::get('/server', 'GameController@servers');
Route::get('/danh-sach/{catSlug?}', 'NewsController@getList');
Route::post('/send-gift', 'ItemsController@sendGift');
Route::get('/testtime', function(){
    return time();
});


Route::pattern('prefix', '[zs]{1}');
Route::get('/play/{prefix}{order_number}',array('before' => 'auth-sso','uses' => 'GameController@playByOrderNumber'))
    ->where('order_number', '[0-9]+');

Route::get('/play/{server_id?}',array('before' => 'auth-play','uses' => 'GameController@play'));
Route::get('/play-trial',array('before' => 'auth-play-trial','uses' => 'GameController@playTrial'));

Route::get('/nap-tien',array('before' => 'auth-sso','uses' => 'TxnsController@getCharge'));
Route::post('/nap-tien',array('before' => 'auth-sso','uses' => 'TxnsController@doCharge'));
Route::post('/nap-the',array('before' => 'auth-sso','uses' => 'TxnsController@doChargeCard'));
Route::get('/gift-code', 'GameController@getGiftCode');
Route::post('/gift-code', 'GameController@postGiftCode');
Route::get('/thu-vien/{type?}', 'GalleriesController@getIndex');
Route::get('/maintain','HomeController@maintain');



Route::get('/nap-soha','TxnsController@getChargeSoha');
Route::post('/do-nap-soha','TxnsController@doChargeSoha');
Route::get('/pay-redirect','TxnsController@payRedirectSoha');
Route::get('/pre-charge','GameController@preCharge');

//for soha portal
Route::post('/payment-callback','TxnsController@sohaPaymentCallback');

//for news

Route::group(array('domain' => 'news.maxgate.vn'), function()
{
    Route::get('/{catSlug}', 'NewsController@newsList');
    Route::get('/{catSlug}/{slug}', 'NewsController@newsDetail');
});




//for zing
Route::get('/users/zing-signin-request', 'ZingController@home');
Route::get('/nap-zing', 'ZingController@charge');
Route::post('/charge-zing', 'ZingController@doCharge');
Route::get('/txns/zing-payment-callback', 'ZingController@paymentCallback');

//--lien chien---
Route::get('/lien-chien/{league_id?}', 'LienChienController@home');
Route::post('/lien-chien/load-result', 'LienChienController@loadResult');
Route::post('/lien-chien/load-list', 'LienChienController@loadList');
Route::post('/lien-chien/load-winner', 'LienChienController@loadWinner');




$allPages = Pages::all();

foreach ($allPages as $aPage) {
    Route::get($aPage->route,
        array('before'=>'injectPageId', 'uses'=>$aPage->controller.'@'.$aPage->action, 'pageId'=>$aPage->id)
        );
}

Route::group(array('before' => array('auth','permission_check')), function()
{
	\Route::get('elfinder', 'Barryvdh\Elfinder\ElfinderController@showIndex');
	\Route::any('elfinder/connector', 'Barryvdh\Elfinder\ElfinderController@showConnector');
	\Route::get('elfinder/tinymce', 'Barryvdh\Elfinder\ElfinderController@showTinyMCE4');
	\Route::get('elfinder/ckeditor4', 'Barryvdh\Elfinder\ElfinderController@showCKeditor4');
});


/** Include IOC Bindings **/
include __DIR__.'/bindings.php';

//------------ For package -------------------
Route::get('/captcha', 'CaptchaController@getBuild');
Route::controller('/chat','ChatController');
