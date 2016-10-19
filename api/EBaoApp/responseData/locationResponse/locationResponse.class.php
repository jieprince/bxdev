<?php
/**
 * 收集用户位置信息
 * $Author: dingchaoyang $
 * 2014-11-12 $
 */
// define ( 'IN_ECS', true );
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/locationResponse/locationResponse.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

// include_once ($ROOT_PATH_ . 'includes/init.php');

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class LocationCollect extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_LOCATIONSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_LOCATION;
		
		$this->updateLocation();
	}
	
	//将位置信息插入到数据表中
	private function updateLocation(){
		global $GLOBALS;
		$uid = isset ( $_REQUEST ['uid'] ) ? isset ( $_REQUEST ['uid'] ) : '';
		$longitude = isset ( $_REQUEST ['longitude'] ) ? $_REQUEST ['longitude'] : '';
		$latitude = isset ( $_REQUEST ['latitude'] ) ? $_REQUEST ['latitude'] : '';
		
		$sql = "INSERT INTO " . $GLOBALS ['ecs']->table ( 'app_location_info' ) . "(uid," . "Longitude," . "Latitude)" . "VALUES('" . $uid . "','" . $longitude . "','" . $latitude . "')";
		
		$GLOBALS ['db']->query ( $sql );
	}
	
	function responseResult(){
		return parent::responseResult();
	}
}

?>