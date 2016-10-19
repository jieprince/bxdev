<?php
/**
 * 会话失效后返回
 * $Author: dingchaoyang $
 * 2014-12-12 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/ebaSession/eba_session_invalid.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_SessionInvalid extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_SESSIONINVALID_CONTENT;
		$this->status = new ResStatus('101', $message);
		$this->command = APP_COMMAND_SESSIONINVALID;
	}
	
	function responseResult(){
		return parent::responseResult();
	}
}

?>