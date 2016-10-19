<?php
/**
 * 投保单
 * $Author: dingchaoyang $
 * 2014-11-20 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/tempPolicy/tempPolicy.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class TempPolicySuccess extends BaseResponse implements IResponse{
	private $tempPolicyId;
	
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_TEMPPOLICYSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_TEMPPOLICY;
	}
	
	function setData($infos){
		$this->tempPolicyId = $infos;
	}
	
	function responseResult(){
		$res = array('policyId'=>$this->tempPolicyId);
		return array_merge(parent::responseResult(),$res);
	}
	
}

class TempPolicyFail extends BaseResponse implements IResponse{
	private $tempPolicyId;

	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_TEMPPOLICYFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_TEMPPOLICY;
	}

	function responseResult(){
		return parent::responseResult();
	}

}
?>