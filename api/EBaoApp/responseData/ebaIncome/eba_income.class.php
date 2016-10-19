<?php
/**
 * 收益列表
 * $Author: dingchaoyang $
 * 2014-12-6 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/ebaIncome/eba_income.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_IncomeListSuccess extends BaseResponse implements IResponse{
	private $total;
	private $count;
	private $list;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_IMCOMELISTSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_IMCOMELIST;
	}
	
	function setData($data,$count,$totalAmount){
		$this->total = array('totalAmount'=>$totalAmount);
		$this->count = array('recCount'=>$count);
		foreach ($data as $key=>$value){
			$this->list[$key]['orderSN'] = $value['order_sn'];//订单号
			$this->list[$key]['type'] = $value['incoming_type'];//类型
			$this->list[$key]['from'] = $value['cname'];//收入来源
			$this->list[$key]['date'] = $value['change_time'];//操作时间
			$this->list[$key]['amount'] = $value['user_money'];//金额
		}
	}
	
	function responseResult(){
		$base = parent::responseResult();
		$curr = array_merge($base,$this->total);
		$curr = array_merge($curr,$this->count);
		$list = array('income'=>$this->list);
		$curr = array_merge($curr,$list);
		return $curr;
		
	}
}

class Eba_IncomeListNoData extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_IMCOMELISTNODATA_CONTENT;
		$this->status = new ResStatus('2', $message);
		$this->command = APP_COMMAND_IMCOMELIST;
	}

	function responseResult(){
		return parent::responseResult();

	}
}

class Eba_IncomeListFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_IMCOMELISTFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_IMCOMELIST;
	}

	function responseResult(){
		return parent::responseResult();

	}
}

?>