<?php
/**
 * app基础支付宝sdk,支付成功后的业务数据处理
 * $Author: dingchaoyang $
 * 2014-11-24 $
 */

class alipayapp{
	
	function get_code($order,$other){
		return '';
	}
	
	function respond()
	{
		//add log for app dingchaoyang 2014-12-23
		$ROOT_PATH__= str_replace ( 'includes/modules/payment/alipayapp.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
		include_once ($ROOT_PATH__ . 'api/EBaoApp/eba_logManager.class.php');
		Eba_LogManager::logPayResonse('postdata from alipay for alipay sdk ');
		//end
		//订单号
		$out_trade_no = $_REQUEST['out_trade_no'];
		//订单金额
		$total_fee = $_REQUEST['total_fee'];
		//订单状态
		$trade_status = $_REQUEST['trade_status'];
		
		//日志号
		$log_id = explode('-',$out_trade_no);
		$log_id = $log_id[1];
// 		/* 检查支付的金额是否相符 */
// 		if (!check_money($log_id, $total_fee))
// 		{
// 			Eba_LogManager::log('postdata from alipaysdk for alipayapp total_fee is checked fail '.$total_fee .' order_sn'.$order_sn);
// 			return false;
// 		}
	
		if ($trade_status == 'WAIT_SELLER_SEND_GOODS')
		{
			ss_log("alipay into order_paid ,WAIT_SELLER_SEND_GOODS, order_sn: ".$out_trade_no);
			/* 改变订单状态 */
			order_paid($log_id, 2);
			echo "success";		//请不要修改或删除
	
			return true;
		}
		elseif ($trade_status == 'TRADE_FINISHED')
		{
			ss_log("alipay into order_paid ,TRADE_FINISHED, order_sn: ".$out_trade_no);
			/* 改变订单状态 */
			order_paid($log_id);
			echo "success";		//请不要修改或删除
	
			return true;
		}
		elseif ($trade_status == 'TRADE_SUCCESS')
		{
			ss_log("alipay into order_paid ,TRADE_SUCCESS, order_sn: ".$out_trade_no);
			 
			/* 改变订单状态 */
			order_paid($log_id, 2);
			echo "success";		//请不要修改或删除
	
			return true;
		}
		else
		{
			return false;
		}
	}
}