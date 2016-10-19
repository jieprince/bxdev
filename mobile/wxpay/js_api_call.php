<?php
/**  点击支付按钮的时候跳转到这里
 * JS_API支付demo
 * ====================================================
 * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
 * 成功调起支付需要三个步骤：
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
*/
	include_once("WxPayPubHelper.php");
	include_once($_SERVER['DOCUMENT_ROOT'] . '/baoxian/source/function_debug.php');
	//使用jsapi接口
	$jsApi = new JsApi_pub();
	$b_openid  = isset($_SESSION['b_openid'])?$_SESSION['b_openid']:"";
	
	$order_sn = $_REQUEST['order_sn']; //这里把log_id 也拼写上，修改订单状态的时候用到
	$log_id = $_REQUEST['log_id'];
	$out_trade_no = $_REQUEST['order_sn']."-".$log_id; //这里把log_id 也拼写上，修改订单状态的时候用到
	$order_amount = $_REQUEST['order_amount'];
	$total_fee = $order_amount*100;//金额以分为单位
	$return_url = $_REQUEST['return_url'];
	ss_log('b_openid:'.$b_openid.",order_sn:".$order_sn.",order_amount:".$order_amount.",total_fee:".$total_fee);
	ss_log('WxPayConf_pub::NOTIFY_URL-->'.WxPayConf_pub::NOTIFY_URL);
	
	
	
	if(!$b_openid){
		
		require(dirname(__FILE__) . 'mobile/includes/lib_common.php');
		$client_ip = get_client_ip();
		ss_log("client_ip:".$client_ip);
			//=========步骤1：网页授权获取用户openid============
		//通过code获得openid
		if (!isset($_GET['code']))
		{
			//触发微信返回code码
			$redirectUrl = $_SERVER['DOCUMENT_ROOT'] . "/mobile/wxpay/js_api_call.php?order_sn=$order_sn&log_id=$log_id&order_amount=$order_amount&return_url=$return_url";
			ss_log("支付，拿code redirectUrl： $redirectUrl");
			$url = $jsApi->createOauthUrlForCode($redirectUrl);
			Header("Location: $url"); //重定手机浏览器向去拿code
			exit;
		}else
		{
			//获取code码，以获取openid
		    $code = $_GET['code'];
			$jsApi->setCode($code);
			$openid = $jsApi->getOpenId();
			$_SESSION['b_openid'] = $openid;
			ss_log("js_api_call:".$openid);
		}
		
	}
	else
	{
		ss_log("js_api_call b_openid is: ".$b_openid);
		
	}

	//=========步骤2：使用统一支付接口，获取prepay_id============
	//使用统一支付接口
	$unifiedOrder = new UnifiedOrder_pub();
	
	//设置统一支付接口参数
	//设置必填参数
	//appid已填,商户无需重复填写
	//mch_id已填,商户无需重复填写
	//noncestr已填,商户无需重复填写
	//spbill_create_ip已填,商户无需重复填写
	//sign已填,商户无需重复填写
	$unifiedOrder->setParameter("openid","$b_openid");//商品描述
	$unifiedOrder->setParameter("body","e保险订单");//商品描述
	//自定义订单号，此处仅作举例
	//$timeStamp = time();
	//$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
	$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号 
	$unifiedOrder->setParameter("total_fee",$total_fee);//金额以分为单位
	$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址 
	$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
	//非必填参数，商户可根据实际情况选填
	//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
	//$unifiedOrder->setParameter("device_info","XXXX");//设备号 
	//$unifiedOrder->setParameter("attach","XXXX");//附加数据 
	//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
	//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
	//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记 
	//$unifiedOrder->setParameter("openid","XXXX");//用户标识
	//$unifiedOrder->setParameter("product_id","XXXX");//商品ID

	$prepay_id = $unifiedOrder->getPrepayId();
	ss_log("prepay_id:".$prepay_id);
	//=========步骤3：使用jsapi调起支付============
	$jsApi->setPrepayId($prepay_id);
	
	$jsApiParameters = $jsApi->getParameters();
	//echo $jsApiParameters;
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>微信安全支付</title>
	<script type="text/javascript">

		//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					WeixinJSBridge.log(res.err_msg);
					if(res.err_msg=="get_brand_wcpay_request:ok"){
						window.location.href="<?php echo $return_url; ?>"; 
					}else{
						alert("支付失败！");
						//window.location.href="../user.php"; 
					}
				}
			);
		}

		function callpay()
		{
			if (typeof WeixinJSBridge == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else{
			    jsApiCall();
			}
		}
	</script>
<style>
.weixin {
	padding-top:5%;
	text-align:center;
	}
.weixin img{
	width:50%;
	}
.radius{
	margin: 10px 0px;
	padding: 10px 0;
}
.mc {
	padding: 6px;
}
.pay_online_bot input {
	display: block;
	background: -webkit-gradient(linear, left top, left bottom, from(#FF7D00), to(#FF7D00));
	background: -moz-linear-gradient(top, #FF7D00, #FF7D00);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FF7D00', endColorstr='#FF7D00');
	background-color: #FF7D00;
	border: none;
	width: 90%;
	margin: 10px auto 0;
	height: 2.5em;
	line-height: 2.5em;
	color: #fff;
	font-size: 1em;
	cursor: pointer;
	text-align: center;
	border-radius: 5px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-bottom: 1px solid #FF7D00;
	
}
</style>
</head>
<body>
<div>
	<div class="weixin">
	<img src="../images/zhifu.png">
	</div>
	<div>
	<p style="text-align:center;padding:0px;margin:0px;font-size:20px;margin-top:40px;">
	订单号：<?php echo $order_sn;?>
	</p>
	<p style="text-align:center;padding:0px;margin:0px;font-size:36px;margin-top:20px;">
	￥<?php echo $order_amount;?>
	</p>
	</div>
	<div class="mc radius">
		<div class="pay_online_bot">
		<input type="button" style="height:45px;"onclick="callpay()" value="立即支付"/>
		</div>
	</div>
</div>
</body>
</html>