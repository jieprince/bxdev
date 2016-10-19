<?php
/**
 * 提现
 * $Author: dingchaoyang $
 * 2014-12-19 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/financeManage/eba_withdraw_cash.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_WithdrawCashSuccess extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message ->title = APP_ALERT_TITLE;
		$message ->content = APP_WITHDRAWCASHSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_WITHDRAWCASH;
	}
	
	function responseResult(){
		$base = parent::responseResult();
		return $base;
	}
}

class Eba_WithdrawCashFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message ->title = APP_ALERT_TITLE;
		$message ->content = APP_WITHDRAWCASHFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_WITHDRAWCASH;
	}

	function responseResult(){
		$base = parent::responseResult();
		return $base;
	}
}
?>