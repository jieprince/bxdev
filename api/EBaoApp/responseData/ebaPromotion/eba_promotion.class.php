<?php
/**
 * 活动报文
 * $Author: dingchaoyang $
 * 2015-2-5 $
 */
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/ebaPromotion/eba_promotion.class.php', '', str_replace ( '\\', '/', __FILE__ ) );

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');
include_once ($ROOT_PATH_ . 'includes/lib_code.php');

class EbaPromotion extends BaseResponse implements IResponse {
	private $promotions = array ();
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_PROMOTIONSSUCCESS_CONTENT;
		$this->status = new ResStatus ( '0', $message );
		$this->command = APP_COMMAND_PROMOTION;
		
		$this->setData();
	}

 	private function setData() {
		$uid = $_REQUEST['uid'];
		$encrypted_data = ebaoins_encrypt($uid,"syden1981");
		$params = "act=getc&uid=".$encrypted_data;
		$this->promotions [0] = array (
				'title' => '我分享我获客',
				'url' => '/mobile/spring_activity.php?' . $params,
				'content' => '"1分钱抢"平安燃气用户意外伤害保险' 
		);
	}
	
	function responseResult(){
		$base = parent::responseResult();
		$curr = array('promotions'=>$this->promotions);
		return array_merge($base,$curr);
	}
}

?>