<?php
/**
 * 订单列表
 * $Author: dingchaoyang $
 * 2014-11-28 $
 */
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/orderList/orderList.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
// require($ROOT_PATH_ . 'includes/init.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_OrderList extends BaseResponse implements IResponse{
	private $list;
	function __construct(){
		parent::__construct();
		$message =  new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_ORDERLISTSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_ORDERLIST;
	}
	
	function setData($data){
		if (!empty($data)){
			foreach ($data as $key=>$value){
				$this->list[$key]['orderId'] = $value['order_id'];//订单id
				$this->list[$key]['orderSN'] = $value['order_sn'];//订单号
				$this->list[$key]['outTradeNo'] = $value['out_trade_no'];//支付时的商户订单号
				$this->list[$key]['orderTime'] = $value['order_time_all'];//订单时间
				$this->list[$key]['amount'] = $value['amount'];//订单金额
				$this->list[$key]['useBalance'] = $value['useBalance'];//使用余额
				
				$this->list[$key]['policyHolderID'] = $value['applicant_uid'];//投保人id
				$this->list[$key]['policyHolder'] = $value['applicant_username'];//投保人名称
				$this->list[$key]['insurerCode'] = $value['insurer_code'];//保险公司编码
				$this->list[$key]['insurerCompany'] = $value['insurer_name'];//保险公司
				
				$this->list[$key]['serviceFee'] = $value['serviceFee'];//服务费
				$this->list[$key]['goodName'] = $value['goods_list'][0]['goods_name'];//险种名称
				$this->list[$key]['policyStatus'] = $value['policy_status'];//保单状态
				$this->list[$key]['orderStatus'] = $value['orderStatus'];//订单状态
				$this->list[$key]['payStatus'] = $value['payStatus'];//付款状态
				$this->list[$key]['policyNO'] = $value['policyNO'];//保单号
				$this->list[$key]['policyID'] = $value['policy_id'];//投保单号
				$this->list[$key]['policyNum'] = $value['policyNum'];//投保份数
				$this->list[$key]['payType'] = $value['payType'];//支付方式
			}
		}
	}
	
	function responseResult(){
		$curr = array('orderList'=>$this->list);
		return array_merge(parent::responseResult(),$curr);
	}
}

class Eba_OrderNoList extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message =  new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_ORDERLISTNODATA_CONTENT;
		$this->status = new ResStatus('2', $message);
		$this->command = APP_COMMAND_ORDERLIST;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

class Eba_OrderFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message =  new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_ORDERLISTFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_ORDERLIST;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

?>