<?php
/**
 * 更新用户资料信息
 * $Author: dingchaoyang $
 * 2014-11-07 $
 */
$ROOT_PATH_=str_replace ( 'api/EBaoApp/responseData/updateProfile/updateProfile.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class UpdateProfileSuccess extends BaseResponse implements IResponse{
	
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_UPDATEPROFILESUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_UPDATEPROFILE;
	}
	
	function responseResult(){
		return parent::responseResult();
	}
}

class UpdateProfileFail extends BaseResponse implements IResponse{

	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_UPDATEPROFILEFAIL_CONTENT;
		$this->status = new ResStatus('10', $message);
		$this->command = APP_COMMAND_UPDATEPROFILE;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

class UpdateProfileEmailExist extends BaseResponse implements IResponse{

	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_UPDATEPROFILEFAIL_EAMILEXIST_CONTENT;
		$this->status = new ResStatus('3', $message);
		$this->command = APP_COMMAND_UPDATEPROFILE;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

class UpdateProfileIDExist extends BaseResponse implements IResponse{

	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_UPDATEPROFILEFAIL_IDEXIST_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_UPDATEPROFILE;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

class UpdateProfileNumExist extends BaseResponse implements IResponse{

	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_UPDATEPROFILEFAIL_NUMEXIST_CONTENT;
		$this->status = new ResStatus('2', $message);
		$this->command = APP_COMMAND_UPDATEPROFILE;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

?>