<?php

namespace Util\Oauth2;
use Util\GameHelper;

/**
 * Class PlaygateIDOauth2
 * - Tạo link đăng nhập sử dụng Oauth2 của hệ thông PhucThanhID
 * - Xử lý login callback khi user đăng nhập và PhucThanhID trả về authorization_code
 *
 * @author Firzen Le <dangleduc@gmail.com>
 */
class PlaygateIDOauth2{

	public $scopes = array('id', 'phone','username','email','first_name','last_name');

	const PLAYGATE_ID_BASE_URL='http://dev.id.maxgate.vn';
	protected $_app_id=null;
	protected $_app_secret=null;
	protected $_login_redirect_uri=null;
	protected $_base_url=null;

	function __construct(){
		$this->_app_id=GameHelper::getOauth2_APP_ID();
		$this->_app_secret=GameHelper::getOauth2_APP_SECRET();
		$this->_login_redirect_uri=GameHelper::getOauth2_LOGIN_REDIRECT_URI();
		$this->_base_url=GameHelper::getOauth2_BASE_URL();
	}

    /**
     * Tạo link đăng nhập qua Oauth2
     */
    public function generateLoginUrl(){
        session_start();
        $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection

        return $this->_base_url."/oauth/authorize?client_id="
        . $this->_app_id. "&response_type=code" . "&redirect_uri=" . urlencode($this->_login_redirect_uri) . "&state="
        . $_SESSION['state'] . "&scope=".implode(",",$this->scopes);
    }

    /**
     * Sử dụng trong trường hợp tích hợp oauth2 theo chuẩn PASSWORD FLOW
     * Post/Get thông tin đăng nhập của user về id.playagate.vn để lấy ra access_token
     */
    public function loginPasswordFlowCredentials($username,$password){
        session_start();
        $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection

        $args=array(
            'grant_type'=>'password',
            'client_id'=>$this->_app_id,
            'client_secret'=>$this->_app_secret,
            'username'=>$username,
            'password'=>$password,
            'scope'=>implode(",",$this->scopes),
            'state'=>$_SESSION['state']
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_base_url.'/oauth/access_token');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($args));
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/ca.crt");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $output = curl_exec($ch);
        if($output===false){
            curl_close($ch);
            return array(
                array('error'=>'curl_error', 'error_description'=>curl_error($ch)),
                null
            );
        }

        curl_close($ch);

        $accessTokenInfo = json_decode($output,true);
        //If json format error?
        if($accessTokenInfo===false){
            return array(
                array('error'=>'json_format_error','error_description'=>$output),
                null
            );
        }
        if(isset($accessTokenInfo['error']))
            return $accessTokenInfo;

        $userInfo=$this->getUserDetails($accessTokenInfo['access_token']);

        return array($accessTokenInfo,$userInfo);
    }

    /**
     * Hàm xử lý lấy ra thông tin user đã đăng nhập qua oauth2 và trả về authrization_code
     * 1. từ authorization_code gọi sang ID để lấy access_token
     * 2. dùng access_token để lấy về thông tin user đang đăng nhập
     *
     * @return array(access_token, user)
     */
    public function loginCallback(){
        //get authorization_code
        $code = $_REQUEST["code"];

        //check CSRF
//        session_start();
        /*if($_SESSION['state'] == null || ($_SESSION['state'] != $_REQUEST['state']))
        {
            die("May be CSRF attack");
        }*/

        if(empty($code))
        {
            exit('Login with ID failed');
        }

        //get access_token từ authorization_code
        $accessTokenInfo = $this->getAccessTokenDetails($code);
        if($accessTokenInfo == null)
        {
            echo "Unable to get Access Token";
            exit(0);
        }

        $userInfo = $this->getUserDetails($accessTokenInfo['access_token']);

        return array($accessTokenInfo,$userInfo);
    }


    /**
     * Get access_token from authorization_code
     * @param $app_id
     * @param $app_secret
     * @param $redirect_url
     * @param $code
     * @return null
     */
    function getAccessTokenDetails($code)
    {
        $token_url = $this->_base_url."/oauth/access_token?"
            . "client_id=" . $this->_app_id . "&grant_type=authorization_code" . "&redirect_uri=" . urlencode($this->_login_redirect_uri)
            . "&client_secret=" . $this->_app_secret . "&code=" . $code;
        $response = @file_get_contents($token_url);
        return json_decode($response,true);
    }

    /**
     * Get user detail from access_token
     * @param $access_token
     * @return mixed|null
     */
    function getUserDetails($access_token)
    {
        $graph_url = $this->_base_url."/graph-api/me?access_token=". $access_token;
        $user = json_decode(@file_get_contents($graph_url));
        if(!empty($user))
            return $user;

        return null;
    }

    /**
     * Build url để thực hiện đăng nhập qua sso
     * @param $username
     * @param $password
     * @param $return_url
     */
    function buildSigninByTicketUrl($username,$password,$return_url){
        $signinTicket=$this->encryptTicket(array('username'=>$username,'password'=>$password));
        return $this->_base_url.'/users/signin-by-ticket?_cid='.$this->_app_id.'&signin-ticket='.urlencode($signinTicket).'&return_url='.urlencode($return_url);
    }

    /**
     * Hàm mã hóa thông tin đăng nhập username/password tạo ra signinTicket
     * @param $args array, vd array('username'=>'un', 'password'=>'pwd', 'email'=>'eml',...)
     * Returns an encrypted & utf8-encoded
     */
    function encryptTicket($args=array()) {
        $pure_string=json_encode($args);
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $this->_app_secret, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
        return base64_encode($encrypted_string);
    }

    /**
     * Hàm giải mã signinTicket thành username/password để kiểm tra đăng nhập
     * Returns decrypted original string
     */
    function decryptTicket($ticket) {
        $ticket=base64_decode($ticket);
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $this->_app_secret, $ticket, MCRYPT_MODE_ECB, $iv);
        $decrypted_string = trim($decrypted_string, "\0\4");
        return json_decode($decrypted_string,true);
    }

    /**
     * Return 1x1 pixel image while signing in by sso
     */
    function returnImage()
    {
        header('Content-Type: image/gif');

        # this is an image with 1pixel x 1pixel
        $img = base64_decode('R0lGODdhAQABAPAAAL6+vgAAACwAAAAAAQABAAACAkQBADs=');
        echo $img;
    }

    /**
     * Tạo url thực hiện register-by-ticket
     * @param $userData array('username'=>'', 'password'=>'','source'=>...)
     * @param $return_url url return sau khi dang ky thanh cong
     * @return string
     */

    function buildRegisterUrl($userData=array(),$return_url){
        $encryptTicket = $this->encryptTicket($userData);
        $returnUrl = GameHelper::getOauth2_BASE_URL().'/users/register-by-ticket?_cid='.
            GameHelper::getOauth2_APP_ID().'&register-ticket='.urlencode($encryptTicket).'&return_url='.urlencode($return_url);
        return $returnUrl;
    }

    /*function buildRegisterUrl($username, $password, $url){
        $encryptTicket = $this->encryptTicket(array('username' => $username, 'password' => $password));
        $returnUrl = $this->_base_url.'/users/register-by-ticket?_cid='.
            $this->_app_id.'&register-ticket='.urlencode($encryptTicket).'&return_url='.urlencode($url);
        return $returnUrl;
    }*/

    function buildLogOut( $url){
        $returnUrl = $this->_base_url.'/users/logout?return_url='.$url;

        return $returnUrl;
    }

	/**
	 * Hàm refresh_token
	 * Thực hiện sử dụng refresh_token để lấy access_token mới trong trường hợp access_token cũ bị hết hạn mà không cần user đăng nhập lại
	 * @param $refresh_token
	 * @return mixed
	 */
	function refreshToken($refresh_token){
		$token_url = $this->_base_url."/oauth/access_token?"
			. "client_id=" . $this->_app_id . "&grant_type=refresh_token" . "&refresh_token=" . urlencode($refresh_token)
			. "&client_secret=" . $this->_app_secret . "&state=" . time();
		$response = @file_get_contents($token_url);
		return json_decode($response,true);
	}
}

 ?>