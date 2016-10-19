<?php
/**
 * 针对支付宝手机网页支付的notify_url
 * 因为wap的回调地址 不允许添加get参数，且notify_url接收到的数据格式也不一样。所以独立出来
 * $Author: dingchaoyang $
 * 2014-11-22 $
 */
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_payment.php');
require(ROOT_PATH . 'includes/lib_order.php');
include_once(ROOT_PATH . 'baoxian/source/function_debug.php');

$ROOT_PATH_= str_replace ( 'respond_alipay_wap.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'includes/modules/payment/alipaywap/notify_url.php');
?>