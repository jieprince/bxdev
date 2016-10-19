<?php
/**
 * 添加银行卡
 * $Author: dingchaoyang $
 * 2014-12-15 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/ebaUnionPay/eba_add_unionpay.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_UnionPayAddSuccess extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_ADDUNIONPAYSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_ADDUNIONPAY;
	}
	
	function responseResult(){
		return parent::responseResult();
	}
}

class Eba_UnionPayAddExist extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_ADDUNIONPAYEXIST_CONTENT;
		$this->status = new ResStatus('2', $message);
		$this->command = APP_COMMAND_ADDUNIONPAY;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

class Eba_UnionPayAddFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_ADDUNIONPAYFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_ADDUNIONPAY;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

?>