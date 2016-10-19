<?php
define('IN_ECS', true);
$ROOT_PATH_= str_replace ( 'notify/wxpay_notify_url.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

require($ROOT_PATH_ . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_payment.php');
require_once(ROOT_PATH .'includes/modules/payment/wx_new_jspay.php');
$payment = new wx_new_jspay();
$payment->respond();



?>
