<?php
/**
 * 财务往来，账单明细列表
 * $Author: dingchaoyang $
 * 2014-12-3 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/financeManage/eba_bill.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_BillSuccess extends BaseResponse implements IResponse{
	private $list;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_BILLLISTSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_BILLLIST;
	}
	
	function setData($data){
		foreach ($data as $key=>$value){
			$this->list[$key]['desc'] = $value['short_change_desc'];//账单描述
			$this->list[$key]['datetime'] = $value['change_time'];//操作时间
			$this->list[$key]['type'] = $value['type'];//增加或减少
			$this->list[$key]['orderSN'] = $value['order_sn'];//订单号
			$this->list[$key]['amount'] = $value['amount'];//金额 
		}
	}
	
	function responseResult(){
		$base = parent::responseResult();
		$curr = array('bill'=>$this->list);
		return array_merge($base,$curr);
	}
}

class Eba_BillNoData extends BaseResponse implements IResponse{
	private $list;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_BILLLISTNODATA_CONTENT;
		$this->status = new ResStatus('2', $message);
		$this->command = APP_COMMAND_BILLLIST;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

class Eba_BillFail extends BaseResponse implements IResponse{
	private $list;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_BILLLISTFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_BILLLIST;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

?>