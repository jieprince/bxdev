<?php
/**
 * 登录
 * $Author: dingchaoyang $
 * 2014-11-11 $
 */
//
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/login/login.php', '', str_replace ( '\\', '/', __FILE__ ) );
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/platformEnvironment.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/eba_sessionManager.class.php');

class LoginSuccess extends BaseResponse implements IResponse {
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_LOGINSUCCESS_CONTENT;
		$this->status = new ResStatus ( '0', $message );
		$this->command = APP_COMMAND_LOGIN;
		// echo $this->command;
	}
	function responseResult() {
		// 获取session token
		$accessToken = Eba_SessionManager::getSessionToken($this->user->uid);
		$base = parent::responseResult ();
		$curr = array('accessToken'=>$accessToken);
		return array_merge($base,$curr);
	}

}

class LoginNoUser extends BaseResponse implements IResponse {
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_LOGINNOUSER_CONTENT;
		$this->status = new ResStatus ( '1', $message );
		$this->command = APP_COMMAND_LOGIN;
		// echo $this->command;
	}
	function responseResult() {
		// 获取session token
		$base = parent::responseResult ();
		return $base;
	}

}

class LoginFail extends BaseResponse implements IResponse {
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_LOGINFAIL_CONTENT;
		$this->status = new ResStatus ( '1', $message );
		$this->command = APP_COMMAND_LOGIN;
		// print_r("loginfail construct " . $this->status->responseResult());
	}
	function responseResult() {
		$result = parent::responseResult ();
		
		return $result;
	}
}
?>