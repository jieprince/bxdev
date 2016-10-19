<?php
/**
 * 获取短息验证码
 * $Author: dingchaoyang $
 * 2014-11-07 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/smsCheck/smsCheckCode.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class SmsCheckCode extends BaseResponse implements IResponse{
	public $checkCode;
// 	function __construct(){}
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_SMSSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_SMS;
		
	}
	
	function responseResult(){
		$code = array('checkCode'=>$this->checkCode);
		$result = array_merge (parent::responseResult(),$code);
		
		return $result;
	}
}
?>