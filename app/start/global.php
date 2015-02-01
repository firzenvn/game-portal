<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/controllers/Controllers/Front',
	app_path().'/models',
    app_path().'/service',
	app_path().'/database/seeds',
    app_path().'/external/soha',
    app_path().'/external/zing',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception)
{
	Log::error($exception);
    return View::make('error')->with('ex','Xin lỗi bạn vì sự bất tiện này.');
});

App::error(function(Util\Exceptions\SystemException $e)
{
    Log::error($e);
    return View::make('error')->with('ex',$e->getMessage());

});

App::error(function(Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e)
{
    return View::make('error')->with('ex',"Url không tồn tại");

});



/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
 * Proxy ips
 */
Request::setTrustedProxies(array(
	'10.0.0.166','10.0.0.167'
));


/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/
require app_path().'/event-listeners.php';
require app_path().'/filters.php';
