<?php
/**
 * 申请开发票
 * $Author: dingchaoyang $
 * 2014-12-8 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/ebaInvoice/eba_applyInvoice.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_ApplyInvoiceSuccess extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_APPLYINVOICESUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_APPLYINVOICE;
	}
	
	function responseResult(){
		return parent::responseResult();
	}
}

class Eba_ApplyInvoiceFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_APPLYINVOICEFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_APPLYINVOICE;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

?>