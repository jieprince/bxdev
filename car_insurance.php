<?php

/**
 * 阳光车险
 *
 **/


define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_order.php');
//require(ROOT_PATH . 'baoxian/source/function_debug.php');
require(ROOT_PATH . 'baoxian/source/function_third_order.php');
include_once(ROOT_PATH.'baoxian/source/my_const.php');

$act = isset($_REQUEST['act'])?$_REQUEST['act']:'default';

$smarty->assign('act', $act);

//add yes123 2015-04-09 未登录处理
if(!$_SESSION['user_id'])
{
	header ( "Location: user.php" );
	exit ();
}


if($act=='go_insure_sunshine')
{
	//TR3268677163736,BU3268678163736
	//BU为商业险订单前缀，TR为交强险订单前缀
	$TR = get_order_sn();
	$BU = get_order_sn();
	$tbsn = "TR".$TR.",BU".$BU;

	ss_log("go_insure_sunshine");
	
	ss_log("TR: ".$TR);
	ss_log("BU: ".$BU);
	ss_log("tbsn: ".$tbsn);
	
	//////////////////////////////////////////////////////////////////////////////////////
	
	$goods_id = $_GET['goods_id'];
	
	/*
	$sql = "SELECT tid FROM bx_goods WHERE goods_id='$goods_id'";
	$attribute_id = $db->getOne($sql);
	ss_log("go_insure_sunshine , attribute_id: ".$attribute_id);
	*/
	
	$uid = $_SESSION['user_id'];
	
	ss_log("go_insure_sunshine , uid: ".$uid);
		
	/*
	//////////在我们系统中生成新的订单，然后把这里的两个订单保存到我们系统的订单上
	$obj_ThirdOrder = new ThirdOrder();
	$obj_ThirdOrder->gen_pre_order($uid,$goods_id,$attribute_id, $TR, $BU);//首先生成预先存放的本地订单
	*/
		
	$smarty->assign('tbsn', $tbsn);
	$smarty->assign('citySelected', '110115');//120000将来这里要选择北京
	$smarty->assign('cooperator', 'W02410792');//这是分配给我们的
	
	$smarty->assign('requestUrl', URL_SINOSIG_CAR);
	/////////////////////////////////////////////////////
	$buyerId = $uid;
	$auctionId = $goods_id;
	//$buyerNick = $_SESSION['user_name'];
	//$auctionTitle = 
	//$promotionInfo
	
	$smarty->assign('buyerId', $buyerId);//渠道登录客户Id
	//$smarty->assign('buyerNick', $buyerNick);//渠道商客户登录账号
	$smarty->assign('auctionId', $auctionId);//商品id
	//$smarty->assign('auctionTitle', $auctionTitle);//商品名称
	//$smarty->assign('promotionInfo', $promotionInfo);//优惠信息
}	


$smarty->display('car_insurance.dwt');
/**
 * 2014-10-04
 * yes123
 * end
 */
  


?>