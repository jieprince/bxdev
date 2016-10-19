<?php
/**
 * 提交订单
 * $Author: dingchaoyang $
 * 2014-11-24 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/submitOrder/orderSubmit.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_ . 'includes/init.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class OrderSubmitSuccess extends BaseResponse implements IResponse{
	private $order;
	private $policy;
	private $balance;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_ORDERSUBMITSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_ORDERSUBMIT;
	}
	
	function setData($data,$payUrl){
		$this->order = array('orderId'=>$data,'payUrl'=> $payUrl );
		$this->getPolicyNo($data);
		$this->getBalance();
	}
	
	//获取账户余额
	private function getBalance(){
		$sql = "SELECT user_money FROM bx_users WHERE user_id ='" . $_SESSION['user_id'] . "'";
		
		$resSet = $GLOBALS ['db']->getRow ( $sql );
		if (!empty($resSet)){
			$this->balance =array('balance'=>$resSet['user_money'])  ;
		}
	}
	
	//根据ordersn查询保单号，如果有保单号，则提交订单的同时，也投保成功
	private function getPolicyNo($data)
	{
		$policy_no=0;
		if (!empty($data)){
			global $GLOBALS;
			
			$sql = "SELECT policy_no FROM t_insurance_policy WHERE order_sn ='" . $data . "'";

			$resSet = $GLOBALS ['db']->getRow ( $sql );
			if (!empty($resSet)){
				$policy_no = $resSet['policy_no'];
			}
		}
		$this->policy = array('policy_no'=>$policy_no);
	}
	
	function responseResult(){
		$base = parent::responseResult();
		$cur = array_merge($this->order,$this->policy);
		$cur = array_merge($cur,$this->balance);
		return array_merge($base,$cur);
	}
}

class OrderSubmitFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_ORDERSUBMITFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_ORDERSUBMIT;
	}

	function responseResult(){
		$base = parent::responseResult();
		return $base;
	}
}

class OrderSubmitExist extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_ORDERSUBMITEXIST_CONTENT;
		$this->status = new ResStatus('2', $message);
		$this->command = APP_COMMAND_ORDERSUBMIT;
	}

	function responseResult(){
		$base = parent::responseResult();
		return $base;
	}
}
?>