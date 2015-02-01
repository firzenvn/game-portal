<?php


use Util\Exceptions\BusinessException;
use Util\Oauth2\PlaygateIDOauth2;
use Util\Oauth2Helper;

class UsersController extends BaseController {

    public function login(){
        $username = Input::get('username');
        $password = Input::get('password');
        $url = Input::get('url');

        $playgateIDOauth2 = new PlaygateIDOauth2();
        $signinByTicketUrl = $playgateIDOauth2->buildSigninByTicketUrl($username, $password, $url);

        return Response::json(array('success'=>true, 'url'=>$signinByTicketUrl));



	}

    private function validateRegisterInput($username, $password, $retypePassword, $retUrl){
        $validator = Validator::make(
            array(
                'name' => $username,
                'password' => $password,
            ),
            array(
                'name' => 'required',
                'password' => 'required',
            )
        );

        if ($validator->fails())
        {
            throw new BusinessException("Chưa nhập username hoặc password");
        }

        if($password != $retypePassword)
            throw new BusinessException("Password không trùng khớp");

        return true;
    }

    public function registerSso(){
        session_start();
        $username = Input::get('username');
        $password = Input::get('password');
        $retypePassword = Input::get('retypePassword');
        $retUrl = Input::get('retUrl');

        try{
            $this->validateRegisterInput($username,$password,$retypePassword,$retUrl);
        }catch(Exception $e){
            return Response::json(array('success'=>false,'message'=>$e->getMessage()));
        }

        $trialSession = PlayTrialSession::where('session_id', '=', session_id())
            ->first();
        $source = null;
        if($trialSession)
            $source = $trialSession->source;
        $playgateIDOauth2 = new PlaygateIDOauth2();
        $userInfoArr = array('username'=>$username, 'password'=>$password, 'source'=>$source);
        $url =  $playgateIDOauth2->buildRegisterUrl($userInfoArr ,$retUrl);
        return Response::json(array('success'=>true,'url'=>$url));
    }


    public function ssoLoginCallback(){
        session_start();
        $playgateIDOauth2 = new PlaygateIDOauth2();
        list($accessToken,$userInfo)=$playgateIDOauth2->loginCallback();

        //Neu user chua co tren portal => them moi
        $checkingUser = User::find($userInfo->id);
        if(!$checkingUser){
            $checkingUser = new User(array('id'=>$userInfo->id, 'username'=>$userInfo->username, 'first_name'=>$userInfo->first_name,
                'last_name'=>$userInfo->last_name,'email'=>$userInfo->email,
                'phone'=>$userInfo->phone));
            $trialSession = PlayTrialSession::where('session_id', '=', session_id())->first();
            if($trialSession){
                $checkingUser->session_id = session_id();
                $checkingUser->session_subdomain = App::make('myApp')->game->subdomain;

            }

            $checkingUser->save();
            if($trialSession){
                $model = new PlayHistory(array('game_id'=>$trialSession->game_id,
                    'server_id'=>$trialSession->server_id,'user_id'=>$checkingUser->id, 'is_first'=>1));
                $model->save();
            }


        }        //Neu co roi => co the update thong tin user
        else{
            $checkingUser->fill(array('username'=>$userInfo->username, 'first_name'=>$userInfo->first_name,
                'last_name'=>$userInfo->last_name,'email'=>$userInfo->email,
                'phone'=>$userInfo->phone));
            $trialSession = PlayTrialSession::where('session_id', '=', session_id())->first();
            if($trialSession){
                if(strtotime($checkingUser->created_at) > (time() - 5*60)){
                    $checkingUser->session_id = session_id();
                    $checkingUser->session_subdomain = App::make('myApp')->game->subdomain;
                }
                $model = new PlayHistory(array('game_id'=>$trialSession->game_id,
                    'server_id'=>$trialSession->server_id,'user_id'=>$checkingUser->id, 'is_first'=>1));
                $model->save();
            }

            $checkingUser->save();
        }

        //set trang thai dang nhap
		Oauth2Helper::storeToken($accessToken);
        Auth::loginUsingId($userInfo->id);

        return View::make('front.sso-image');
    }

    public function ssoLogoutCallback(){
        session_start();
        $playgateIDOauth2 = new PlaygateIDOauth2();
        Auth::logout();
        Oauth2Helper::forgetToken();
    //sinh lai PHPSESSID trong truong hop play-trial
        session_regenerate_id(true);
        $playgateIDOauth2->returnImage();

    }
}
