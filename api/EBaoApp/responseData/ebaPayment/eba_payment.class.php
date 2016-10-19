<?php
/**
 * 在订单列表对未支付的订单 进行支付，如果选择支付宝网页支付，返回payUrl
 * $Author: dingchaoyang $
 * 2014-12-27 $
 */

$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/ebaPayment/eba_payment.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_PaymentSuccess extends BaseResponse implements IResponse{
	private $payUrl;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_ALIPAYURLSUCCESSCONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_ALIPAYURL;
	}
	
	function setData($data){
		if ($data){
			$this->payUrl = $data;
		}
	}
	
	function responseResult(){
		$base = parent::responseResult();
		$curr = array('payUrl'=>$this->payUrl);
		return array_merge($base,$curr);
	}
}

class Eba_PaymentFail extends BaseResponse implements IResponse{
	private $payUrl;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_ALIPAYURLFAILCONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_ALIPAYURL;
	}

	function responseResult(){
		$base = parent::responseResult();
		return $base;
	}
}

?>