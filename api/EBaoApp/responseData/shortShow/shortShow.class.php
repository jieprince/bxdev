<?php
/**
 * 快捷功能数据：我的收藏，我的推荐，我的收入，账户余额
 * $Author: dingchaoyang $
 * 2014-11-13 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/shortShow/shortShow.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
// include_once ($ROOT_PATH_ . 'includes/init.php');

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');
class ShortShow extends BaseResponse implements IResponse {
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_SHORTSHOWSUCCESS_CONTENT;
		$this->status = new ResStatus ( '0', $message );
		$this->command = APP_COMMAND_SHORTSHOW;
	}
	
	// 我的收藏
	private function getFavorite() {
		global $GLOBALS;
		$record_count = $GLOBALS ['db']->getOne ( "SELECT COUNT(*) FROM " . $GLOBALS ['ecs']->table ( 'collect_goods' ) . " WHERE user_id='" . $_SESSION ['user_id'] . "'" );
		return $this->nullObjToInt($record_count);
	}
	
	// 我的推荐
	private function getRecommend() {
		$record_count = $GLOBALS ['db']->getOne ( "SELECT COUNT(*) FROM " . $GLOBALS ['ecs']->table ( 'users' ) . " WHERE parent_id= '" . $_SESSION ['user_id'] . "' " );
		return $this->nullObjToInt($record_count);
	}
	
	// 我的收益
	private function getIncome() {
		$shouru_money_total = $GLOBALS ['db']->getOne ( "SELECT sum(user_money) FROM " . $GLOBALS ['ecs']->table ( 'account_log' ) . " WHERE user_id = '" . $_SESSION ['user_id'] . "'" . " AND incoming_type IS NOT NULL AND incoming_type <>'' " . " AND user_money <> 0  " );
		return $this->nullObjToInt($shouru_money_total);
	}
	
	// 账户余额
	private function getBalance() {
		$balance = $GLOBALS ['db']->getOne ( "SELECT user_money FROM " . $GLOBALS ['ecs']->table ( 'users' ) . " WHERE user_id = '" . $_SESSION ['user_id'] . "'" );
		return $this->nullObjToInt($balance);
	}
	function responseResult() {
		$base = parent::responseResult ();
		$curr = array (
				'data' => array (
						'favorite' => $this->getFavorite (),
						'recommend' => $this->getRecommend (),
						'income' => $this->getIncome (),
						'balance' => $this->getBalance () 
				) 
		);
		return array_merge ( $base, $curr );
	}
}

?>