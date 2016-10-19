<?php
include_once("WxPayHelper.php");


$commonUtil = new CommonUtil();
$wxPayHelper = new WxPayHelper();

$wxPayHelper->setParameter("bank_type", "WX");
$wxPayHelper->setParameter("body", "天地飘香商城订单");
$wxPayHelper->setParameter("partner", "1218613201");
$wxPayHelper->setParameter("out_trade_no", '1218613201aaa');
$wxPayHelper->setParameter("total_fee", "1");
$wxPayHelper->setParameter("fee_type", "1");
$wxPayHelper->setParameter("notify_url", "htttp://wx.lzljtdpx.com/mobile/respond.php?code=weixinpay");
$wxPayHelper->setParameter("spbill_create_ip", "127.0.0.1");
$wxPayHelper->setParameter("input_charset", "GBK");
$wxPayHelper->create_native_package();
 echo $PayUrl= $wxPayHelper->create_native_url("1218613201aaa");

echo '<div style="text-align:center"><input type="button" onclick="window.open(\''.$PayUrl.'\')" value="weixinpay" /></div>';
?>

<a href="<?php echo $PayUrl;?>"> <?php echo $PayUrl;?></a>
<input onclick="javascript:location.href='<?=$PayUrl?>'" type="button" name="zhifu" value=" 微信支付 " />

<a href="weixin://wxpay/bizpayurl?timestamp=1413281547&appid=wxfc78b36ff9a8ff95&productid=1218613201aaa&noncestr=AYCNMuLDZtou6RQZ&sign=9d332199ea73dc9b7cbffe6e4f7e9453e5c6afa8">微信支付</a>