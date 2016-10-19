<?php
/**
 * 勿动
 * app微信sdk支付后，调用notify_url:respond_wx.php,回调地址调用该类的repond方法获取参数和进行订单状态修改、及投保过程
 * @author Ding chaoyang
 * date 2014-12-23
 * 
 */

class wxpayapp{
	function get_code($order, $payment){
		return '';
	}
	
	function respond(){
		//add log for app dingchaoyang 2014-12-23
		$ROOT_PATH__= str_replace ( 'includes/modules/payment/wxpayapp.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
		include_once ($ROOT_PATH__ . 'api/EBaoApp/eba_logManager.class.php');
		Eba_LogManager::logPayResonse('postdata from weixin for wxpayapp  ');
		//end
		if (intval($_GET['trade_state'])  == 0)
		{
			$order_sn = trim($_GET['out_trade_no']);
			$log_id = explode('-',$order_sn);
// 			/* 检查支付的金额是否相符 */
// 			if (!check_money($log_id[1], intval($_GET['total_fee'])/100 ))
// 			{
// 				Eba_LogManager::log('postdata from weixin for wxpayapp total_fee is checked fail '.intval($_GET['total_fee'])/100 .' order_sn'.$order_sn);
// 				return false;
// 			}
			ss_log("weixinpay into order_paid ,trade_state=0 , order_sn: ".$order_sn);
			/* 改变订单状态 */
			order_paid($log_id[1], 2);
			echo "success";		//请不要修改或删除
			return true;
		}
		else
		{
			echo "fail";
			return false;
		}
	}
}

?>