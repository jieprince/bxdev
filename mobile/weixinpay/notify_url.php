<?php
/* *
 * 功能：微信服务器异步通知页面
 * 14-9-28 下午1:28
 */
define('IN_ECS', true);

/* 取得当前ecshop所在的根目录 */
define('ROOT_PATH', str_replace('mobile/weixinpay/notify_url.php', '', str_replace('\\', '/', __FILE__)));

// 初始数据库
require(ROOT_PATH . 'includes/init_mysql.php');

// 订单
require(ROOT_PATH . 'includes/lib_order.php');
// 支付
require(ROOT_PATH . 'includes/lib_payment.php');

$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

$msg=simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

include_once("WxPayHelper.php");

$wxPayHelper = new WxPayHelper();

$wxPayHelper->setParameter("bank_type", "WX");
$wxPayHelper->setParameter("body", "天地飘香商城订单");
$wxPayHelper->setParameter("partner", "1218613201");
$wxPayHelper->setParameter("out_trade_no", '1218613201');
$wxPayHelper->setParameter("total_fee", "1");
$wxPayHelper->setParameter("fee_type", "1");
$wxPayHelper->setParameter("notify_url", "htttp://wx.lzljtdpx.com/mobile/respond.php?code=weixinpay");
$wxPayHelper->setParameter("spbill_create_ip", "127.0.0.1");
$wxPayHelper->setParameter("input_charset", "GBK");

echo $package = $wxPayHelper->create_native_package();


//$fd = sprintf($return_data,"wxfc78b36ff9a8ff95", $package, time(), "1218613201aaa");
//exit($fd);