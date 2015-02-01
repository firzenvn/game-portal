<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => false,

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => 'http://localhost',

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Ho_Chi_Minh',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => 'TbaUQmsnKC8NvacYEIGE',
    'base_domain' =>'maxgate.vn',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => array(

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        'Illuminate\Cache\CacheServiceProvider',
        'Illuminate\Session\CommandsServiceProvider',
        'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
        'Illuminate\Routing\ControllerServiceProvider',
        'Illuminate\Cookie\CookieServiceProvider',
        'Illuminate\Database\DatabaseServiceProvider',
        'Illuminate\Encryption\EncryptionServiceProvider',
        'Illuminate\Filesystem\FilesystemServiceProvider',
        'Illuminate\Hashing\HashServiceProvider',
        'Illuminate\Html\HtmlServiceProvider',
        'Illuminate\Log\LogServiceProvider',
        'Illuminate\Mail\MailServiceProvider',
        'Illuminate\Database\MigrationServiceProvider',
        'Illuminate\Pagination\PaginationServiceProvider',
        'Illuminate\Queue\QueueServiceProvider',
        'Illuminate\Redis\RedisServiceProvider',
        'Illuminate\Remote\RemoteServiceProvider',
        'Illuminate\Auth\Reminders\ReminderServiceProvider',
        'Illuminate\Database\SeedServiceProvider',
        'Illuminate\Session\SessionServiceProvider',
        'Illuminate\Translation\TranslationServiceProvider',
        'Illuminate\Validation\ValidationServiceProvider',
        'Illuminate\View\ViewServiceProvider',
        'Illuminate\Workbench\WorkbenchServiceProvider',
//        'Davzie\LaravelBootstrap\LaravelBootstrapServiceProvider',
        'Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider',
        'Barryvdh\Debugbar\ServiceProvider',
        'Way\Generators\GeneratorsServiceProvider',
        'mnshankar\RoleBasedAuthority\RoleBasedAuthorityServiceProvider',
        'Frozennode\Administrator\AdministratorServiceProvider',
        'Regulus\ActivityLog\ActivityLogServiceProvider',
        'Pingpong\Widget\WidgetServiceProvider',
		'Barryvdh\Elfinder\ElfinderServiceProvider',
        'Yangqi\Htmldom\HtmldomServiceProvider',
    ),

    /*
    |--------------------------------------------------------------------------
    | Service Provider Manifest
    |--------------------------------------------------------------------------
    |
    | The service provider manifest is used by Laravel to lazy load service
    | providers which are not needed for each request, as well to keep a
    | list of all of the services. Here, you may set its storage spot.
    |
    */

    'manifest' => storage_path().'/meta',

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => array(

        'App'             => 'Illuminate\Support\Facades\App',
        'Artisan'         => 'Illuminate\Support\Facades\Artisan',
        'Auth'            => 'Illuminate\Support\Facades\Auth',
        'Blade'           => 'Illuminate\Support\Facades\Blade',
        'Cache'           => 'Illuminate\Support\Facades\Cache',
        'ClassLoader'     => 'Illuminate\Support\ClassLoader',
        'Config'          => 'Illuminate\Support\Facades\Config',
        'Controller'      => 'Illuminate\Routing\Controller',
        'Cookie'          => 'Illuminate\Support\Facades\Cookie',
        'Crypt'           => 'Illuminate\Support\Facades\Crypt',
        'DB'              => 'Illuminate\Support\Facades\DB',
        'Eloquent'        => 'Illuminate\Database\Eloquent\Model',
        'Event'           => 'Illuminate\Support\Facades\Event',
        'File'            => 'Illuminate\Support\Facades\File',
        'Form'            => 'Illuminate\Support\Facades\Form',
        'Hash'            => 'Illuminate\Support\Facades\Hash',
        'HTML'            => 'Illuminate\Support\Facades\HTML',
        'Input'           => 'Illuminate\Support\Facades\Input',
        'Lang'            => 'Illuminate\Support\Facades\Lang',
        'Log'             => 'Illuminate\Support\Facades\Log',
        'Mail'            => 'Illuminate\Support\Facades\Mail',
        'Paginator'       => 'Illuminate\Support\Facades\Paginator',
        'Password'        => 'Illuminate\Support\Facades\Password',
        'Queue'           => 'Illuminate\Support\Facades\Queue',
        'Redirect'        => 'Illuminate\Support\Facades\Redirect',
        'Redis'           => 'Illuminate\Support\Facades\Redis',
        'Request'         => 'Illuminate\Support\Facades\Request',
        'Response'        => 'Illuminate\Support\Facades\Response',
        'Route'           => 'Illuminate\Support\Facades\Route',
        'Schema'          => 'Illuminate\Support\Facades\Schema',
        'Seeder'          => 'Illuminate\Database\Seeder',
        'Session'         => 'Illuminate\Support\Facades\Session',
        'SSH'             => 'Illuminate\Support\Facades\SSH',
        'Str'             => 'Illuminate\Support\Str',
        'URL'             => 'Illuminate\Support\Facades\URL',
        'Validator'       => 'Illuminate\Support\Facades\Validator',
        'View'            => 'Illuminate\Support\Facades\View',
        'Debugbar'        => 'Barryvdh\Debugbar\Facade',
        'Authority'        => 'mnshankar\RoleBasedAuthority\Facades\Authority',
        'Activity' => 'Regulus\ActivityLog\Activity',
        'Htmldom' => 'Yangqi\Htmldom\Htmldom',

    ),

//-----------------Custom config-----------------

    'custom_config'=>array(
        'admin_url_segment'=>'admin',
        'vendor'=>'Play gate',

//--------admin menu item, should be replaced by data retrieved from database?---
        'menu_items'=>array(
            'articles'=>array(
                'name'=>'Tin Bài',
                'icon'=>'list',
                'top'=>true,
                'url'=>'/admin/articles',
                'items'=>array(
                    'news'=>array(
                        'name'=>'Tin Tức',
                        'url'=>'/admin/articles',
                    ),
                    'add-news'=>array(
                        'name'=>'Thêm mới',
                        'url'=>'/admin/articles/new',
                    ),

                )
            ),
            'catalogs'=>array(
                'name'=>'Danh mục',
                'icon'=>'book',
                'top'=>true,
                'url'=>'/admin/catalogs/categories/ARTICLE',
                'items'=>array(

                    'article_group'=>array(
                        'name'=>'Nhóm chính bài viết(news)',
                        'url'=>'/admin/catalogs/categories/ARTICLE',
                    ),
                    'sub_article_group'=>array(
                        'name'=>'Nhóm bài viết trong game',
                        'url'=>'/admin/catalogs/categories/SUB_ARTICLE',
                    ),
                    'pos_article_group'=>array(
                        'name'=>'Nhóm vị trí bài viết(news)',
                        'url'=>'/admin/catalogs/categories/POS_ARTICLE',
                    ),
                    'game_group'=>array(
                        'name'=>'Nhóm chính game',
                        'url'=>'/admin/catalogs/categories/GAME',
                    ),
                    'sub_game_group'=>array(
                        'name'=>'Nhóm phụ game',
                        'url'=>'/admin/catalogs/categories/SUB_GAME',
                    ),
                    'sub_gallery_group'=>array(
                        'name'=>'Nhóm phụ gallery',
                        'url'=>'/admin/catalogs/categories/SUB_GALLERY',
                    ),

                )
            ),
            'game'=>array(
                'name'=>'Game',
                'icon'=>'book',
                'top'=>true,
                'url'=>'/admin/games',
                'items'=>array(
                    'index'=>array(
                        'name'=>'Danh sách',
                        'url'=>'/admin/games',
                    ),
                    'add'=>array(
                        'name'=>'Thêm mới',
                        'url'=>'/admin/games/new',
                    ),
                )
            ),
            'cms'=>array(
                'name'=>'CMS',
                'icon'=>'book',
                'top'=>true,
                'url'=>'/admin/pages',
                'items'=>array(
                    'template'=>array(
                        'name'=>'Template',
                        'url'=>'/admin/templates',
                    ),
                    'page'=>array(
                        'name'=>'Page',
                        'url'=>'/admin/pages',
                    ),
                )
            ),
            'blocks'=>array(
                'name'=>'Content Blocks',
                'icon'=>'th-large',
                'top'=>true,
                'url'=>'/admin/blocks',
            ),
            'galleries'=>array(
                'name'=>'Galleries',
                'icon'=>'picture',
                'top'=>true,
                'url'=>'/admin/galleries',
            ),
            'giftcodes'=>array(
                'name'=>'Giftcode',
                'icon'=>'book',
                'top'=>true,
                'url'=>'/admin/giftcodes',
                'items'=>array(
                    'index'=>array(
                        'name'=>'Danh sách',
                        'url'=>'/admin/giftcodes',
                    ),
                    'add'=>array(
                        'name'=>'Thêm mới',
                        'url'=>'/admin/giftcodes/new',
                    ),
                )
            ),
            'league'=>array(
                'name'=>'Liên chiến',
                'icon'=>'book',
                'top'=>true,
                'url'=>'/admin/league',
                'items'=>array(
                    'index'=>array(
                        'name'=>'Danh sách',
                        'url'=>'/admin/league',
                    ),
                    'add'=>array(
                        'name'=>'Thêm mới',
                        'url'=>'/admin/league/new',
                    ),

                )
            ),
            'report'=>array(
                'name'=>'Báo cáo',
                'icon'=>'book',
                'top'=>true,
                'url'=>'/admin/reports',
                'items'=>array(
                    'sum_by_game'=>array(
                        'name'=>'Tổng doanh thu',
                        'url'=>'/admin/reports/sum-by-game',
                    ),
                    /*'page'=>array(
                        'name'=>'Page',
                        'url'=>'/admin/pages',
                    ),*/
                )
            ),
        )
    ),

);
