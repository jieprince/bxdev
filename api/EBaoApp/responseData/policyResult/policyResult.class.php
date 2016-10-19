<?php
/**
 * 查询投保 结果
 * $Author: dingchaoyang $
 * 2014-11-26 $
 */
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/policyResult/policyResult.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
// require($ROOT_PATH_ . 'includes/init.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');
class PolicyResult extends BaseResponse implements IResponse {
	private $ordersn;
	private $orderType;
	private $result;
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_POLICYRESULTSUCCESS_CONTENT;
		$this->status = new ResStatus ( '0', $message );
		$this->command = APP_COMMAND_POLICYRESULT;
	}
	function setData($data) {
		if ( $data) {
			$this->ordersn = trim ( $data['orderSN'] );//$_REQUEST['orderSN']
			$this->orderType = $data['orderType'];
		}
	}
	function responseResult() {
		$this->searchResult();
		return array_merge(parent::responseResult(),$this->result);
	}
	private function searchResult() {
		global $GLOBALS;
		$orderid = explode('-',$this->ordersn);
		if ($this->orderType == 0){//投保订单
			
			$sql = "SELECT * FROM t_insurance_policy WHERE policy_status='insured' and order_sn ='" . $orderid[0] . "'";
		}elseif ($this->orderType == 1){//充值订单
			$sql = "SELECT * FROM bx_user_account WHERE is_paid = 1 and id='".$orderid[0]."'";
		}
		
		$res = "1";
		$resSet = $GLOBALS ['db']->getRow ( $sql );
		if ($resSet) {
			$res = "0";
		}
		$this->result = array (
				'result' => $res 
		);
	}
}
?>