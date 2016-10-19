<?php
/**
 * 银行卡
 * $Author: dingchaoyang $
 * 2014-12-6 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/ebaUnionPay/eba_unionpay.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_UnionPaySuccess extends BaseResponse implements IResponse{
	private $list;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_UNIONPAYSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_UNIONPAY;
	}
	
	function setData($data){
		foreach ($data as $key=>$value){
			$this->list[$key]['accountID'] = $value['bid'];//账户id
			$this->list[$key]['accountName'] = $value['bank_name'];//账户名称
			$this->list[$key]['bank'] = $value['b_account'];//开户行
			$this->list[$key]['subBank'] = $value['sub_branch'];//支行
			$this->list[$key]['accountCode'] = $value['bank_code'];//账号
		}
	}
	
	function responseResult(){
		$base = parent::responseResult();
		$cur = array('unionPay'=>$this->list);
		return array_merge($base,$cur);
	}
}

class Eba_UnionPayNoData extends BaseResponse implements IResponse{
	private $list;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_UNIONPAYNODATA_CONTENT;
		$this->status = new ResStatus('2', $message);
		$this->command = APP_COMMAND_UNIONPAY;
	}

	function responseResult(){
		$base = parent::responseResult();
		return $base;
	}
}

class Eba_UnionPayFail extends BaseResponse implements IResponse{
	private $list;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_UNIONPAYFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_UNIONPAY;
	}

	function responseResult(){
		$base = parent::responseResult();
		return $base;
	}
}
?>