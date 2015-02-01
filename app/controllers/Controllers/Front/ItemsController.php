<?php


use Util\Oauth2\PlaygateIDOauth2;
use Util\Oauth2\PlaygateIDRestClient;
use Util\Oauth2Helper;

class ItemsController extends FrontBaseController {

    public function sendGift(){
        $server = Input::get('server');
        $user = User::findOrFail(Input::get('user_id'));
        $params = Input::get('params');
        $result = \Util\GameHelper::send($user, $server, $params);

        return $result;
    }


}
