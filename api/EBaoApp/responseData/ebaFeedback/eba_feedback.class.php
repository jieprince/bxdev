<?php
/**
 * 在web端的showmessage提示
 * $Author: dingchaoyang $
 * 2014-12-29 $
 */

$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/ebaFeedback/eba_feedback.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_ . 'api/EBaoApp/platformEnvironment.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Feedback extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_FEEDBACKCONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_FEEDBACK;
		$this->feedback();
	}
	
	private function feedback(){
		global $GLOBALS;
		$uid = $_REQUEST['uid'];
		$username = $_REQUEST['username'];
		$feedback = $_REQUEST['feedback'];
		$platformId=PlatformEnvironment::getPlatformID();
		$sql ="INSERT INTO bx_app_feedback (uid,username,feedback,platformId) VALUES ('".$uid."','".$username."','".$feedback."','".$platformId."')";
		$GLOBALS ['db']->query($sql);
		
	}
	
	function responseResult(){
		return parent::responseResult();
	}
}

?>