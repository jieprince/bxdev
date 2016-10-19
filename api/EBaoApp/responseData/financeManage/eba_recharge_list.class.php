<?php
/**
 * 充值/提现记录
 * $Author: dingchaoyang $
 * 2014-12-3 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/financeManage/eba_recharge_list.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_RechargeListSuccess extends BaseResponse implements IResponse{
	private $list;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_RECHARGELISTSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command= APP_COMMAND_RECHARGELIST;
	}
	
	function setData($data){
		foreach ($data as $key=>$value){
			$this->list[$key]['orderID'] = $value['id'];//金额
			$this->list[$key]['amount'] = $value['amount'];//金额
			$this->list[$key]['payType'] = $value['payment'];//充值方式
			$this->list[$key]['memo'] = str_replace('N/A','',$value['short_user_note']) ;//用户备注
			$this->list[$key]['memo1'] = str_replace('N/A','',$value['short_admin_note']) ;//管理员备注
			$this->list[$key]['applyDate'] = $value['add_time'];//申请时间
			$this->list[$key]['statusStr'] = $value['pay_status'];//状态文字
			$this->list[$key]['statusID'] = $value['statusID'];//状态id 0未付款，1已付款
		}
	}
	
	function responseResult(){
		$base = parent::responseResult();
		$curr = array('recharge'=>$this->list);
		return array_merge($base,$curr);
	}
}

class Eba_RechargeListNoData extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_RECHARGELISTNODATA_CONTENT;
		$this->status = new ResStatus('2', $message);
		$this->command= APP_COMMAND_RECHARGELIST;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

class Eba_RechargeListFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_RECHARGELISTFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command= APP_COMMAND_RECHARGELIST;
	}

	function responseResult(){
		return parent::responseResult();
	}
}
?>