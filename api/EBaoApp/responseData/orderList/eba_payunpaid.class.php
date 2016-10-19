<?php
/**
 * 不管选择哪种支付方式，如果使用余额，须要请求该接口，服务器根据上传的使用余额来进行修改订单使用余额或修改订单状态或进行投保。
 * $Author: dingchaoyang $
 * 2014-12-31 $
 */
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/orderList/eba_payunpaid.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
// require($ROOT_PATH_ . 'includes/init.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');
include_once ($ROOT_PATH_ . 'includes/modules/payment/alipaywap/alipayapi.php');
class Eba_PayUnpaidOrder extends BaseResponse implements IResponse {
	private $policyNo = array (
			'policy_no' => "" 
	);
	private $payUrl = array (
			'payUrl' => '' 
	);
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_PAYUNPAIDORDERSUCCESS_CONTENT;
		$this->status = new ResStatus ( '0', $message );
		$this->command = APP_COMMAND_PAYUNPAIDORDER;
	}
	function setData($order) {
		global $GLOBALS;
		if ($order ['order_status'] == 1 && $order ['pay_status'] == 2) { // 订单为已付款，查看是否投保成功
			$sql = "select policy_no from t_insurance_policy where order_id ='" . $order ['order_id'] . "'";
			$policy_no = $GLOBALS ['db']->getOne ( $sql );
// 			if ($policy_no) {
// 				$this->status->message->content = APP_PAYUNPAIDORDERINSUREDSUCCESS_CONTENT;
// 			} else {
// 				$this->status->message->content = APP_PAYUNPAIDORDERINSUREDFAIL_CONTENT;
// 			}
			
			$this->policyNo = array (
					'policy_no' => $policy_no ? $policy_no : "" 
			);
		} else {
			if ($order ['pay_id'] == 20) { // 支付宝网页支付，返回payUrl
				$sql = "select * from bx_order_info where order_sn='" . $order ['order_sn'] . "'";
				$set = $GLOBALS ['db']->getRow ( $sql );
				if ($set) {
					
					$order ['order_amount'] = $set ['goods_amount'] - $set ['surplus'];
					$payObj = new AlipayAPI ();
					$payObj->setParams ( $order );
					$payUrl = $payObj->getPayUrl ();
					$this->payUrl = array (
							'payUrl' => $payUrl ? $payUrl : "" 
					);
				}
			}
		}
	}
	function responseResult() {
		$base = parent::responseResult ();
		if ($this->payUrl) {
			$base = array_merge ( $base, $this->payUrl );
		}
		if ($this->policyNo) {
			$base = array_merge ( $base, $this->policyNo );
		}
		return $base;
	}
}
class Eba_PayUnpaidOrderFail extends BaseResponse implements IResponse {
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_PAYUNPAIDORDERFAIL_CONTENT;
		$this->status = new ResStatus ( '1', $message );
		$this->command = APP_COMMAND_PAYUNPAIDORDER;
	}
	function responseResult() {
		return parent::responseResult ();
	}
}

?>