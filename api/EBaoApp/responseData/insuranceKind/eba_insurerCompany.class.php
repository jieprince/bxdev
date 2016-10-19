<?php
/**
 * 保险公司
 * $Author: dingchaoyang $
 * 2014-12-10 $
 */
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/insuranceKind/eba_insurerCompany.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
// include_once ($ROOT_PATH_ . 'includes/init.php');
include_once ($ROOT_PATH_ . 'includes/class/commonUtils.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class InsurerCompany extends BaseResponse implements IResponse{
	private $list;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_INSURERCOMPANYSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_INSURERCOMPANY;
	}
	
	private function setData(){
		global $GLOBALS;
		$set = $GLOBALS ['db']->getAll ( "SELECT brand_id,brand_name FROM bx_brand"  );
		foreach ($set as $key=>$value){
			$this->list[$key]['insurerID'] = $value['brand_id'];//保险公司id
			$this->list[$key]['insurerName'] = $value['brand_name'];//保险公司名称
		}
	}
	
	function responseResult(){
		$this->setData();
		
		$base = parent::responseResult();
		$curr = array('insurer'=>$this->list);
		return array_merge($base,$curr);
	}
}

?>