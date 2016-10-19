<?php
define('IN_ECS', true);
/* 取得当前ecshop所在的根目录 */
require(dirname(dirname(__FILE__)) . '/includes/init.php');
require_once(ROOT_PATH . 'includes/class/RSA.php');
require_once(ROOT_PATH . 'baoxian/source/function_debug.php');
require_once(ROOT_PATH . 'Partner/partner_common.php');


$url = "../category.php?act=hot_goods_list";

if($_SESSION['user_id']>0)
{
	$_SESSION['user_id'] = 0;
	$_SESSION['user_name'] = '';    
	$_SESSION['real_name'] = ''; 

}


//合作伙伴渠道代码：就是我们分配给商旅通的渠道ID，表示是来自商旅通的。
$cooperator = isset($_REQUEST['cooperator'])?$_REQUEST['cooperator']:'';

//渠道登录客户Id，也就是购买者的uid
$buyerId = isset($_REQUEST['buyerId'])?$_REQUEST['buyerId']:'';

//城市
$citySelected = isset($_REQUEST['citySelected'])?$_REQUEST['citySelected']:'';

ss_log("解密前 cooperator:".$cooperator);
ss_log("解密前 buyerId:".$buyerId);


//数字签名串，用来作为安全认证可信性。用第三方的私人秘钥进行数字签名，然后我们用公钥解密，以检验数据来源的可信性。sig=RSA(channalid+ thirduid),就是把渠道id和uid串起来，用RSA数字签名。
$sig = isset($_REQUEST['sig'])?$_REQUEST['sig']:''; 	


//校验签名
/*$pubfile = ROOT_PATH."includes/rsa_key/springpass_publicKey.keystore"; 
$m->setKeyfile($pubfile);
$flag = $m->verify($cooperator.$buyerId, $sig);
if(!$flag ||!$cooperator ||!$buyerId)
{
	
	
}*/

//解密
$data = partner_decrypt(array('cooperator'=>$cooperator,'buyerId'=>$buyerId));
$cooperator = $data['cooperator'];
$buyerId = $data['buyerId'];


ss_log("解密后 cooperator:".$cooperator);
ss_log("解密后 buyerId:".$buyerId);


if(!$cooperator ||!$buyerId)
{
	ss_log("cooperator或者buyerId为空");
	die('<script>alert("非法访问");</script>');
}


$res = partner_login($cooperator,$buyerId);

if(!$res)
{
	die('<script>alert("非法访问");</script>');
	
}
else
{
	
	$url.="&cooperator=$cooperator&buyerId=$buyerId";
	ss_log("location:".$url);
	header("location: $url");
}
	



?>
