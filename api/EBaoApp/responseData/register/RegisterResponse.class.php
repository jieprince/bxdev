<?php
/**
 * 注册
 * $Author: dingchaoyang $
 * 2014-11-07 $
 */
$ROOT_PATH_=str_replace ( 'api/EBaoApp/responseData/register/RegisterResponse.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class RegisterResponseSuccess extends BaseResponse implements IResponse {
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_REGISTERSUCCESS_CONTENT;
		$this->status = new ResStatus('0',$message);
		$this->command = APP_COMMAND_REGISTER;
		// 		echo $this->command;
	}

	function responseResult(){
		$result = parent::responseResult();

		return $result;
	}
}

class RegisterResponseFail extends BaseResponse implements IResponse {
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_REGISTERFAIL_CONTENT;
		$this->status = new ResStatus('1',$message);
		$this->command = APP_COMMAND_REGISTER;
		// 		echo $this->command;
	}

	function responseResult(){
		$result = parent::responseResult();

		return $result;
	}
}

class RegisterResponseExist extends BaseResponse implements IResponse {
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_REGISTERUSEREXIST;
		$this->status = new ResStatus('5',$message);
		$this->command = APP_COMMAND_REGISTER;
		// 		echo $this->command;
	}

	function responseResult(){
		$result = parent::responseResult();

		return $result;
	}
}

?>