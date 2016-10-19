<?php
/**
 * 注销保单
* $Author: dingchaoyang $
* 2014-12-2 $
*/
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/policyList/eba_cancel_policy.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
// require($ROOT_PATH_ . 'includes/init.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_CancelPolicySuccess extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_CANCELPOLICYSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_CANCELPOLICY;
	}
	
	public function setData($data){
		$this->status->message->content = $data;
	}
	
	function responseResult(){
		return parent::responseResult();
	}
}

class Eba_CancelPolicyFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_CANCELPOLICYFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_CANCELPOLICY;
	}

	public function setData($data){
		$this->status->message->content = $data;
	}
	
	function responseResult(){
		return parent::responseResult();
	}
}

?>