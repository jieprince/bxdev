<?php
define('IN_ECS', true);
/* 取得当前ecshop所在的根目录 */
require(dirname(dirname(__FILE__)) . '/includes/init.php');
require_once(ROOT_PATH . 'baoxian/source/function_debug.php');
require_once(ROOT_PATH . 'Partner/partner_common.php');

$cooperator = isset($_REQUEST['cooperator'])?$_REQUEST['cooperator']:'';
$buyerId = isset($_REQUEST['buyerId'])?$_REQUEST['buyerId']:'';
$type = isset($_REQUEST['type'])?trim($_REQUEST['type']):'order_list';//目前可用值 order_list,warranty_list
ss_log(__FILE__.",解密前 cooperator:".$cooperator);
ss_log(__FILE__.",解密前 buyerId:".$buyerId);


//解密
$data = partner_decrypt(array('cooperator'=>$cooperator,'buyerId'=>$buyerId));
$cooperator = $data['cooperator'];
$buyerId = $data['buyerId'];
ss_log(__FILE__.",解密后 cooperator:".$cooperator);
ss_log(__FILE__.",解密后 buyerId:".$buyerId);


if(!$cooperator ||!$buyerId)
{
	die('<script>alert("非法访问");</script>');
}

$url_arr = array(
	'order_list'=>"../user.php?act=order_list",
	'policy_list'=>"../user.php?act=warranty_list",
);

$res = partner_login($cooperator,$buyerId);

if(!$res)
{
	ss_log(__FILE__.",非法访问，保单或者订单列表,".$type);
	die('<script>alert("非法访问");</script>');
	
}
else
{
	if($url_arr[$type])
	{
		safari_handle();
		$url = $url_arr[$type];
		
		$url.="&cooperator=$cooperator&buyerId=$buyerId";
		ss_log("go url:".$url);
		header("location: $url");
	}
	else
	{
		die('<script>alert("没有可用资源");</script>');
	}
	
}
	


	
?>
