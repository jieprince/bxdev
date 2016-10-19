<?php
/**
 * 会员动态
 * $Author: dingchaoyang $
 * 2014-11-11 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/memberNews/memberNews.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class MemberNewsSuccess extends BaseResponse implements IResponse{
	private $infos;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_MEMBERNEWSSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_MEMBERNEWS;
	}
	
	function setNews($infos){
		$this->infos = $infos;	
	}
	
	function responseResult(){
		$ar = array('news'=>$this->infos);
		$result = array_merge(parent::responseResult(),$ar);
		return $result;
	}
}

class MemberNewsFail extends BaseResponse implements IResponse{
	private $infos;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_MEMBERNEWSFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_MEMBERNEWS;
	}

	function responseResult(){
		
		$result = parent::responseResult();
		return $result;
	}
}
?>