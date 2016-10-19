<?php
define('IN_ECS', true);
require (dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . 'includes/class/RSA.php');
require_once(ROOT_PATH . 'baoxian/source/function_debug.php');

$pubfile = ROOT_PATH."includes/rsa_key/rsa_public_key.pem"; 
$prifile = ROOT_PATH."includes/rsa_key/rsa_private_key.pem"; 
$m = new RSA($pubfile, $prifile); 

$cooperator = trim($_REQUEST['cooperator']);
$buyerId = trim($_REQUEST['buyerId']);
$cooperator =  "1398243505";
$buyerId = "123";

//加密
$cooperator = $m->encrypt($cooperator);
$buyerId = $m->encrypt($buyerId);
 $smarty->assign('cooperator',       $cooperator);
 $smarty->assign('buyerId',       $buyerId);
$smarty->display('test.dwt');
?>
