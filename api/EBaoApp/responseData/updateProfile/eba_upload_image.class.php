<?php
/**
 * 上传图片
 * $Author: dingchaoyang $
 * 2014-12-08 $
 */
$ROOT_PATH_=str_replace ( 'api/EBaoApp/responseData/updateProfile/eba_upload_image.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_UploadImageSuccess extends BaseResponse implements IResponse{
	private $path;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_UPLOADIMAGESUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_UPLOADIMAGE;
	}
	
	function setData($path){
		$this->path = $path;
	}
	
	function responseResult(){
		$base = parent::responseResult();
		$curr = array('imageUrl'=>$this->path);
		return array_merge($base,$curr);
	}
}

class Eba_UploadImageNoMatchType extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_UPLOADIMAGENOMATCHTYPE_CONTENT;
		$this->status = new ResStatus('3', $message);
		$this->command = APP_COMMAND_UPLOADIMAGE;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

class Eba_UploadImageOverMax extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_UPLOADIMAGEOVERMAX_CONTENT;
		$this->status = new ResStatus('4', $message);
		$this->command = APP_COMMAND_UPLOADIMAGE;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

class Eba_UploadImageFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_UPLOADIMAGEFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_UPLOADIMAGE;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

?>