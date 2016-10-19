<?php
define('IN_ECS', true);
define('ECS_ADMIN', true);
require(dirname(__FILE__) . '/includes/init.php'); 
$act = empty($_REQUEST['act'])?'':$_REQUEST['act'];
if($act == 'share_active')
{	
	$login_type = isset($_SESSION['login_type'])?$_SESSION['login_type']:"";
	$smarty->assign('login_type', $login_type);
	$activity_id = 0;
	if(isset($_GET['active_id']))
	{
		$activity_id = $_GET['active_id'];
	}
	$smarty->assign('activity_id', $activity_id);
	$smarty->display('share_reg.dwt');//邀请注册页面链接
	exit;
	 
}elseif($act == 'share_C'){
	$SESSIONuser_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
	
	$user_id = $_GET['uid'];
	$smarty->assign('SESSIONuser_id', $SESSIONuser_id);
	$smarty->assign('user_id', $user_id);
	
	$smarty->display('share_product_huodong_client_pingan_y502_C.dwt');
}else{
	$user_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
	$smarty->assign('user_id', $user_id);
	$smarty->display('share_product_huodong_client_pingan_y502_B.dwt');
}


?>