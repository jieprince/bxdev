<?php

/**
 * app集成微信支付成功后对服务器的通知页面
 * $Author: 丁朝阳 $
 * 2014-12-22  $
 */
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_payment.php');
require(ROOT_PATH . 'includes/lib_order.php');
include_once(ROOT_PATH . 'baoxian/source/function_debug.php');

// /* 参数是否为空 */
// if (empty($trade_state))
// {
// 	$msg = $_LANG['weixin pay_not_exist'];
// 	ss_log($msg);
// }
// else

$plugin_file = 'includes/modules/payment/wxpayapp.php';

/* 检查插件文件是否存在，如果存在则验证支付是否成功，否则则返回失败信息 */
if (file_exists($plugin_file))
{
	/* 根据支付方式代码创建支付类的对象并调用其响应操作方法 */
	include_once($plugin_file);

	$payment = new wxpayapp();
	$msg     = (@$payment->respond()) ? $_LANG['pay_success'] : $_LANG['pay_fail'];
	ss_log($msg);
}

?>