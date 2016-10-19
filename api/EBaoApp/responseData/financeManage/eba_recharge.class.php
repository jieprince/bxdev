<?php
/**
 * 充值
 * $Author: dingchaoyang $
 * 2014-12-3 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/financeManage/eba_recharge.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_RechargeSuccess extends BaseResponse implements IResponse{
	private $info;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_RECHARGESUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_RECHARGE;
	}
	
	function setData($data,$payUrl){
		$this->info['orderSN'] = $data['order_sn'].'-'.$data['log_id'];//订单号
		$this->info['rechargeAmount'] = $data['surplus_amount'];//充值金额
		$this->info['fee'] = $data['order_amount'] - $data['surplus_amount'];//手续费
		$this->info['amount'] = $data['order_amount'];//应付金额
		$this->info['payUrl'] = $payUrl;//支付宝手机网页支付的url
	}
	
	function responseResult(){
		$base = parent::responseResult();
		$cur = array('order'=>$this->info);
		return array_merge($base,$cur);
	}
}
?>