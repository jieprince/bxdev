<?php
/**
 * 在web端的showmessage提示
* $Author: dingchaoyang $
* 2014-12-29 $
*/

$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/ebaOtherError/eba_other_error.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_OtherError extends BaseResponse implements IResponse{
	
	function __construct($content,$command){
		parent::__construct();
		
		if (!$content){
			$content = APP_OTHERERRORCONTENT;
		}
		if (!$command){
			$command = APP_COMMAND_OTHERERROR;
		}
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = '.'.$content;
		$this->status = new ResStatus('1', $message);
		$this->command = $command; 	
	}
	
	function responseResult(){
		return parent::responseResult();
	}
}

?>