<?php
/**
 * 找回密码
 * $Author: dingchaoyang $
 * 2014-11-07 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/retrievePassword/retrievePassword.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');
class RetrievePasswordSuccess extends BaseResponse implements IResponse{
	private $mobile;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_RETRIEVEPWSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_RETRIEVEPW;
	}
	
	function setData($data){
		$this->mobile = $data;
		$this->status->message->content = str_replace('%s%',$this->mobile,APP_RETRIEVEPWSUCCESS_CONTENT) ;
	}
	
	function responseResult(){
		$result = parent::responseResult();
		
		return $result;
	}
}

class RetrievePasswordFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_RETRIEVEPWFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_RETRIEVEPW;
	}

	function responseResult(){
		$result = parent::responseResult();

		return $result;
	}
}

class RetrievePasswordMatch extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_RETRIEVEPWNOMATCH_CONTENT;
		$this->status = new ResStatus('2', $message);
		$this->command = APP_COMMAND_RETRIEVEPW;
	}

	function responseResult(){
		$result = parent::responseResult();

		return $result;
	}
}
?>