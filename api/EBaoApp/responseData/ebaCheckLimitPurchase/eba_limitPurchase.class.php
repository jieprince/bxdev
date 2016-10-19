<?php
/**
 * 限制购买
 * $Author: dingchaoyang $
 * 2014-12-18 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/ebaCheckLimitPurchase/eba_limitPurchase.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_LimitPurchase_Pingan extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_LIMITPURCHASE_PINGAN_CONTENT;
		$this->status = new ResStatus('11', $message);
		$this->command = APP_COMMAND_LIMITPURCHASE;
	}
	
	function responseResult(){
		return parent::responseResult();
	}
}

class Eba_Purchaseble_Pingan extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_PURCHASEBLE_PINGAN_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_LIMITPURCHASE;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

?>