<?php
/**
 * 轮播图
 * $Author: dingchaoyang $
 * 2014-11-12 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/cycleNews/CycleNews.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');
class CycleNews{
	private $response;
	function __construct(){
		$this->response = new CycleNewsSuccess();
	}
	
	function responseResult(){
		return $this->response->responseResult();
	}
}

class CycleNewsSuccess extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_CYCLENEWSSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_CYCLENEWS;
// 		echo 'CycleNewsSuccess';
	}
	
	function responseResult(){
		$base = parent::responseResult();
		
		$a[0]['imageUrl']=/*SERVERURL.*/'/mobile_page/images/banner.png';
		$a[0]['navUrl'] = '/mobile/spring_activity.php';
		$a[0]['type'] = 0;//是webview
		
		$a[1]['imageUrl']=/*SERVERURL.*/'/mobile_page/images/a2.png';
		$a[1]['catgoryID'] = '18';//分类id
		$a[1]['navUrl'] = '40';//险种id
		$a[1]['type'] = 1;//产品
		
		$a[2]['imageUrl']=/*SERVERURL.*/'/mobile_page/images/a3.png';
		$a[2]['catgoryID'] = '18';//分类id
		$a[2]['navUrl'] = '39';//险种id
		$a[2]['type'] = 1;//产品
		$cres = array('news'=>$a);

		$result = array_merge($base,$cres);
		return $result;
	}
}

class CycleNewsFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_CYCLENEWSFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_CYCLENEWS;
	}

	function responseResult(){
		$result = parent::responseResult();
		return $result;
	}
}
?>