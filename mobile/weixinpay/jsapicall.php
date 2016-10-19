<?php
include_once("WxPayHelper.php");

$commonUtil = new CommonUtil();
$wxPayHelper = new WxPayHelper();

$total_fee          = isset($_POST['order_amount']) ? $_POST['order_amount'] :0;//商品费用
$order_sn           = isset($_POST['order_sn']) ? $_POST['order_sn'] : '';//商品订单号

$notify_url         = $_POST['notify_url'];//支付成功回调地址
$return_url         = $_POST['return_url'];//页面跳转同步通知页面路径
$spbill_create_ip   = $_POST['spbill_create_ip'];//服务器ip 114.215.103.231

$wxPayHelper->setParameter("bank_type", "WX");
$wxPayHelper->setParameter("body", $_POST['body']);
$wxPayHelper->setParameter("partner", $_POST['partner']);
$wxPayHelper->setParameter("out_trade_no", $order_sn);
$wxPayHelper->setParameter("total_fee", $total_fee*100);
$wxPayHelper->setParameter("fee_type", "1");
$wxPayHelper->setParameter("notify_url", $notify_url);
$wxPayHelper->setParameter("spbill_create_ip", $spbill_create_ip);
$wxPayHelper->setParameter("input_charset", "UTF-8");

?>
<html>
<head>
<title>提交订单-微信支付</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="ecdaddy.com">
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=0">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">

<script language="javascript">
function callpay()
{
	WeixinJSBridge.invoke('getBrandWCPayRequest',<?php echo $wxPayHelper->create_biz_package(); ?>,function(res){
            alert(res.err_msg);
	WeixinJSBridge.log(res.err_msg);        
            if(res.err_msg=='get_brand_wcpay_request:ok')
            {                
               window.location.href = '<?php echo $return_url;?>';
            }else
            {                         
                window.location.href = '<?php echo $return_url;?>';
            }
	
	});
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
	height: 40px;
	line-height: 40px;
	color: #fff;
	font-size: 14px;
	cursor: pointer;
	text-align: center;
	border-radius: 2px;
	-webkit-border-radius: 2px;
	-moz-border-radius: 2px;
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
	￥<?php echo $total_fee;?>
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
