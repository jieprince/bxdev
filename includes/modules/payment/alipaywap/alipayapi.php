<?php
/* *
 * 功能：即时到账交易接口接入页
 * 版本：3.3
 * 修改日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************注意*************************
 * 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 * 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
 * 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
 * 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
 * 如果不想使用扩展功能请把扩展功能参数赋空值。
 */




/**************************调用授权接口alipay.wap.trade.create.direct获取授权码token**************************/
class AlipayAPI{
	public $wayPayUrl;
	
	function getPayUrl(){
		return $this->wayPayUrl;
	}
	
	function setParams($eba_wap_order){
		require_once("alipay.config.php");
		require_once("lib/alipay_submit.class.php");
		require_once ("lib/alipay_core.function.php");
		//返回格式
		$format = "xml";
		//必填，不需要修改
		
		//返回格式
		$v = "2.0";
		//必填，不需要修改
		
		//请求号
		$req_id = date('Ymdhis');
		//必填，须保证每次请求都是唯一
		
		//**req_data详细信息**
		
		//服务器异步通知页面路径
		// print_r($order);
		// exit();

		$ROOT_PATH__= str_replace ( 'includes/modules/payment/alipaywap/alipayapi.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
		include_once ($ROOT_PATH__ . 'includes/cls_ecshop.php');
		$ebaecs = new ECS(null,null);
		
		$notify_url = $ebaecs->url()."respond_alipay_wap.php";
		//需http://格式的完整路径，不允许加?id=123这类自定义参数
		
		//页面跳转同步通知页面路径
		// $call_back_url = "http://127.0.0.1:8800/WS_WAP_PAYWAP-PHP-UTF-8/call_back_url.php";
		//需http://格式的完整路径，不允许加?id=123这类自定义参数
		
		//操作中断返回地址
		// $merchant_url = "http://127.0.0.1:8800/WS_WAP_PAYWAP-PHP-UTF-8/xxxx.php";
		//用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数
		
		//卖家支付宝帐户
		$seller_email = 'ztxhbxzb@163.com';//$_POST['WIDseller_email'];
		//必填

		//商户订单号
		$out_trade_no = $eba_wap_order['order_sn'].'-'.$eba_wap_order['log_id'];//'201412221000';//$_POST['WIDout_trade_no'];
		//商户网站订单系统中唯一订单号，必填
		
		//订单名称
		$subject = $eba_wap_order['trade_subject'];//$_POST['WIDsubject'];
		//必填
		
		//付款金额
		$total_fee = $eba_wap_order['order_amount'];
		//必填
		
		//请求业务参数详细
		$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
//  		$req_data = urlencode($req_data);
		//必填
// 		echo $req_data;
		/************************************************************/
		
		//构造要请求的参数数组，无需改动
		$para_token = array(
				"service" => "alipay.wap.trade.create.direct",
				"partner" => trim($alipay_config['partner']),
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
//  		 print_r($para_token );
		// exit();
		// //add by dingchaoyang 2014-12-22
		// //对该数组做参数名首字母升序的排序动作
		// $para_token = argSort($para_token);
		// //end
		
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestHttp($para_token);

		//URLDECODE返回的信息
		$html_text = urldecode($html_text);
// echo $html_text. 'requesttoken';
		//解析远程模拟提交后返回的信息
		$para_html_text = $alipaySubmit->parseResponse($html_text);
		
		//获取request_token
		$request_token = $para_html_text['request_token'];
// 		echo '--'.$request_token. 'requesttoken';
		/**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/
		
		//业务详细
		$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
//  		$req_data = urlencode($req_data);
		//必填
		
		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "alipay.wap.auth.authAndExecute",
				"partner" => trim($alipay_config['partner']),
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		
		//构造get参数
		$parameter = $alipaySubmit->buildRequestPara($parameter);
		foreach ($parameter as $key=>$value){
			$getParam .= $key.'='.urlencode($value).'&';
		}
		$getParam = substr($getParam, 0,-1);
		$this->wayPayUrl = 'http://wappaygw.alipay.com/service/rest.htm?' . $getParam ;
// 		return $wapPayUrl;
		// $html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');
		// echo $html_text;
	}
}	

?>