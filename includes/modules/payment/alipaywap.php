<?php
/**
 * 支付宝手机网页支付的类，直接调用alipaywap/alipayapi.php进行签名，生成支付的页面
 * $Author: dingchaoyang $
 * 2014-11-22 $
 */
class alipaywap{
	public $eba_wap_order;
	public function get_code($order,$other){

// 		$this->eba_wap_order = $order;
		//调用支付宝手机网页支付的签名并生成支付页面
		include_once('includes/modules/payment/alipaywap/alipayapi.php');
		$payObj = new AlipayAPI();
// 		print_r($order);
		$payObj->setParams($order);
		if ($payObj->getPayUrl()){
			return $payObj->wayPayUrl;
		}else {
			return '';
		}
	}
}
?>