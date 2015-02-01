<?php

namespace Controllers\Admin;
use BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\MessageBag;
use Log;
use User;
use Util\Oauth2\PlaygateIDOauth2;
use Util\Validators\Login;
use View, Auth, Redirect, Validator, Session, Input;

class AdminBaseController extends BaseController {

    /**
     * Let's whitelist all the methods we want to allow guests to visit!
     *
     * @access   protected
     * @var      array
     */

    protected $layout = 'layout.layout_admin';

    protected $whitelist = array(
        'getLogin',
        'getLogout',
        'postLogin'
    );

    /**
     * Initializer.
     *
     * @access   public
     * @return   void
     */
    public function __construct()
    {
        // Achieve that segment
        $this->urlSegment = Config::get('::app.custom_config.admin_url_segment');
        // Setup composed views and the variables that they require
        $this->beforeFilter( 'adminFilter' , array('except' => $this->whitelist) );
        $this->beforeFilter('permission_check', array('except'=> $this->whitelist));
        $composed_views = array( '*');
        View::composer($composed_views, 'Util\Composers\AdminPage');

//        $this->beforeFilter('admin');
    }

    /**
     * Main users page.
     *
     * @access   public
     * @return   View
     */
    public function getIndex()
    {
        $this->layout->content = View::make( 'admin.dashboard' );
    }

    /**
     * Log the user out
     * @return Redirect
     */
    public function getLogout()
    {
        Auth::logout();
        Session::flush();
        return Redirect::to($this->urlSegment.'/login')
                ->with('success', new MessageBag(array('Succesfully logged out.')));
    }

    /**
     * Login form page.
     *
     * @access   public
     * @return   View
     */
    public function getLogin()
    {

        // If logged in, redirect to admin area
//        if (Auth::check())
//        {
//            return Redirect::to( $this->urlSegment );
//        }

        return View::make('admin.login')->with('urlSegment', $this->urlSegment);
    }

    /**
     * Login form processing.
     *
     * @access   public
     * @return   Redirect
     */

    public function postLogin(){
        $loginValidator = new Login( Input::all() );
        if ( !$loginValidator->passes() )
        {
            return Redirect::to($this->urlSegment.'/login')
                ->with('errors', new MessageBag( array( 'Username &amp; Password sai' ) ) );
        }

        $username = Input::get('username');
        $password = Input::get('password');

        $playgateIDOauth2 = new PlaygateIDOauth2();

        $result = $playgateIDOauth2->loginPasswordFlowCredentials($username, $password);
        if(isset($result['error'])){
            return Redirect::back()->with('errors', new MessageBag( array( 'Thông tin đăng nhập không chính xác' ) ) )->withInput();
        }
        list($accessTokenInfo, $userInfo) = $result;
        Log::debug('$userInfo->id'.$userInfo->id);
        $checkingUser = User::find($userInfo->id);
        if(!$checkingUser){
            return Redirect::to($this->urlSegment.'/login')
                ->with('errors', new MessageBag( array( 'User không tồn tại' ) ) );
        }
        Auth::login($checkingUser);
        return Redirect::to( $this->urlSegment )
            ->with('success', new MessageBag( array('Đăng nhập thành công') ) );
    }

}