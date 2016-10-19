<?php
/**
 * 险种大类
 * $Author: dingchaoyang $
 * 2014-11-14 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/insuranceKind/insuranceKind.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
// include_once ($ROOT_PATH_ . 'includes/init.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');
class InsuranceKind extends BaseResponse implements IResponse {
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_INSURANCEKINDSUCCESS_CONTENT;
		$this->status = new ResStatus ( '0', $message );
		$this->command = APP_COMMAND_INSURANCEKIND;
	}
	
	function responseResult() {
		$base = parent::responseResult ();
		$curr = array('insKind'=>$this->getInsKinds());
		return array_merge($base,$curr);
	}
	
	private function getInsKinds() {
		global $GLOBALS;
// 		$filter = "in ('12','6','18')";
		$set = $GLOBALS ['db']->getAll ( "SELECT cat_id,cat_name,cat_desc,icon_show_in_app FROM " . $GLOBALS ['ecs']->table ( 'category' ) . " WHERE is_show_in_app = 1 and is_show = 1"  );
		
		foreach ($set as $key=>$record){
			$result[$key]['catId'] = $record['cat_id'];
			$result[$key]['catName'] = $record['cat_name']; 
			$result[$key]['desc'] = $record['cat_desc'];
			$result[$key]['icon'] = "/mobile_page/images/".$record['icon_show_in_app'];
		}
		
		return $result;
	}
}
?>