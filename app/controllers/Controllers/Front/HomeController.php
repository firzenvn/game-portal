<?php


use Illuminate\Support\Facades\View;
use Util\Oauth2\PlaygateIDOauth2;

class HomeController extends FrontBaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/



    public function test()
    {
        $this->initRoutablePage();
        $this->layout->content = View::make('front.test');
    }

    public function index()
    {
        $myApp=App::make('myApp');
        //        Auth::loginUsingId(68);
//     Session::set('access_token','3mZyGhGGa9PP1AMslMk58mm6E8ZU57Bb7keSmLpY');
        //------for game page----------
        if($myApp->isOnSubdomain){
            $this->loadLayout('home');
        }else //------for portal page----------
        {
//            $this->initRoutablePage();
            if($myApp->isOnNews){
                $this->layout = 'home';
                $this->setupLayout();
//                $this->layout->content = View::make('abc');
            }

            else
                $this->layout->content = View::make('front.home')->with('pageItem',$this->page);
        }

    }

    public function loginCallback(){
        $playgateIDOauth2 = new PlaygateIDOauth2();
        list($accessToken,$userInfo)=$playgateIDOauth2->loginCallback();
        //Neu user chua co tren portal => them moi
        $checkingUser = User::find($userInfo->id);
        if(!$checkingUser){
            $checkingUser = new User(array('id'=>$userInfo->id, 'username'=>$userInfo->username, 'first_name'=>$userInfo->first_name,
                'last_name'=>$userInfo->last_name,'email'=>$userInfo->email,
                'phone'=>$userInfo->phone));
            $checkingUser->save();
        }        //Neu co roi => co the update thong tin user
        else{
            $checkingUser->fill(array('username'=>$userInfo->username, 'first_name'=>$userInfo->first_name,
                'last_name'=>$userInfo->last_name,'email'=>$userInfo->email,
                'phone'=>$userInfo->phone));
            $checkingUser->save();
        }

        //set trang thai dang nhap
        Auth::login($checkingUser);
//        return Redirect::intended('/');
        $playgateIDOauth2->returnImage();
    }


    public function maintain()
    {
        $this->loadLayout( 'maintain');
    }



}
