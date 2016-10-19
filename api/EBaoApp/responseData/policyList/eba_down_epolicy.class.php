<?php
/**
 * 下载电子保单
 * $Author: dingchaoyang $
 * 2014-12-3 $
 */
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/policyList/eba_down_epolicy.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
// require($ROOT_PATH_ . 'includes/init.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_EPolicySuccess extends BaseResponse implements IResponse{
	private $pdfFile;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_DOWNEPOLICYSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_DOWNEPOLICY;
	}

	function setData($data){
		//http://182.92.156.137:82/baoxian/cp.php?ac=product_buy&policy_id=4210&op=getpolicyfile&uid=297&EBA_Android=1
// 		$this->pdfFile = array('pdfUrl'=>/*SERVERURL.*/'/baoxian/'.$data);
		$this->pdfFile = array('pdfUrl'=>'baoxian/cp.php?ac=product_buy&policy_id='.$data.'&op=getpolicyfile&uid='.$_SESSION['user_id'].'&platformId=103');
	}
	
	function responseResult(){
		return array_merge(parent::responseResult(),$this->pdfFile);
	}
}

class Eba_EPolicyFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_DOWNEPOLICYFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_DOWNEPOLICY;
	}
	
	function responseResult(){
		return parent::responseResult();
	}
}
?>