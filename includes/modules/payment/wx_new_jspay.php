<?php
/**
 * ECSHOP微信新版JSAPI支付插件
 */
if (!defined('IN_ECS')) {
    die('Hacking attempt');
}
$payment_lang = ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/payment/wx_new_jspay.php';

if (file_exists($payment_lang))
{
    global $_LANG;

    include_once($payment_lang);
}
else
{
	$payment_lang = ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/payment/wx_new_jspay.php';
	global $_LANG;
    include_once($payment_lang);
}
/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = 'wx_new_jspay_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = '黔驴 QQ:290605830';

    /* 网址 */
    $modules[$i]['website'] = 'http://wx.qq.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.0';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'appid',           'type' => 'text',   'value' => ''),
        array('name' => 'mchid',               'type' => 'text',   'value' => ''),
        array('name' => 'key',           'type' => 'text',   'value' => ''),
        array('name' => 'appsecret',           'type' => 'text',   'value' => ''),
       // array('name' => 'logs',           'type' => 'text',   'value' => ''),
    );

    return;
}


class wx_new_jspay
{
    function __construct()
    {
    	global $log;
        $payment = get_payment('wx_new_jspay');
    
        if(!defined('WXAPPID'))
        {
            define("WXAPPID", $payment['appid']);
            define("WXMCHID", $payment['mchid']);
            define("WXKEY", $payment['key']);
            define("WXAPPSECRET", $payment['appsecret']);
            define("WXCURL_TIMEOUT", 30);
        }
        
        $ecs_url = $GLOBALS['ecs']->url();
		////$log->info("ecs_url:".$ecs_url);
        if(isMobile()){
            define('WXNOTIFY_URL',$ecs_url.'mobile/wxpay_notify_url.php');

        }else{
            define('WXNOTIFY_URL',$ecs_url.'notify/wxpay_notify_url.php');

        }

        
        require_once(dirname(__FILE__)."/WxPayPubHelper/WxPayPubHelper.php");

    }


    function get_code($order, $payment)
    {
	   
        $jsApi = new JsApi_pub($payment);
		
        //判断是否来自于微信浏览器，否则显示扫码支付
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($user_agent, 'MicroMessenger')){
		 
            $html = $this->get_scancodepay($order);
            return $html;
        }
  

        $openid=$payment['openid'];
        if($openid)
        {
        	$log_id = insert_pay_log ( $order['order_id'], $order['order_amount'], PAY_ORDER );
        	
            $unifiedOrder = new UnifiedOrder_pub($payment);

            $unifiedOrder->setParameter("openid","$openid");//商品描述
            $unifiedOrder->setParameter("body",$order['order_sn']);//商品描述
            
            $out_trade_no = $order['order_sn'];
            
            $unifiedOrder->setParameter("out_trade_no",$out_trade_no."_".$log_id);//商户订单号 
            $unifiedOrder->setParameter("attach",strval($log_id));//商户支付日志
            $unifiedOrder->setParameter("total_fee",strval(intval($order['order_amount']*100)));//总金额
            $unifiedOrder->setParameter("notify_url",WXNOTIFY_URL);//通知地址 
            $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型


            $prepay_id = $unifiedOrder->getPrepayId();
			
			ss_log("prepay_id：".$prepay_id);
			
            $jsApi->setPrepayId($prepay_id);

            $jsApiParameters = $jsApi->getParameters();


            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $allow_use_wxPay = true;

            if(strpos($user_agent, 'MicroMessenger') === false)
            {
                $allow_use_wxPay = false;
            }
            else
            {
                preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $user_agent, $matches);
                if($matches[2] < 5.0)
                {
                    $allow_use_wxPay = false;
                }
            }

            $html='';

            $html .= '<script language="javascript">';
            if($allow_use_wxPay)
            {
                $html .= "function jsApiCall(){";
                $html .= "WeixinJSBridge.invoke(";
                $html .= "'getBrandWCPayRequest',";
                $html .= $jsApiParameters.",";
                $html .= "function(res){";
                $html .= "if(res.err_msg == 'get_brand_wcpay_request:ok'){window.location.href='".$GLOBALS['ecs']->url()."respond.php?code=wx_new_jspay&order_id=$order[order_id]'}";
                //$html .= "WeixinJSBridge.log(res.err_msg);";
                $html .= "}";
                $html .= ");";
                $html .= "}";
                $html .= "function callpay(){";
                $html .= 'if (typeof WeixinJSBridge == "undefined"){';
                $html .= "if( document.addEventListener ){";
                $html .= "document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);";
                $html .= "}else if (document.attachEvent){";
                $html .= "document.attachEvent('WeixinJSBridgeReady', jsApiCall); ";
                $html .= "document.attachEvent('onWeixinJSBridgeReady', jsApiCall);";
                $html .= "}";
                $html .= "}else{";
                $html .= "jsApiCall();";
                $html .= "}}";
            }
            else
            {
                $html .= 'function callpay(){';
                $html .= 'alert("您的微信不支持支付功能,请更新您的微信版本")';
                $html .= "}";

            }

            $html .= '</script>';
            $html .= '<a href="javascript:callpay();" class="c-btn3">微信支付</a>';



			ss_log("html：".$html);

            return $html;

        }
        else
        {
            $html='';
            $html .= '<script language="javascript">';
            $html .= 'function callpay(){';
            $html .= 'alert("请在微信中使用微信支付")';
            $html .= "}";
            $html .= '</script>';
            $html .= '<button type="button" onclick="callpay()">微信支付</button>';

            return $html;
        }

        
    }
    function respond()
    {


        ss_log("into ".__FILE__);
        $payment  = get_payment('wx_new_jspay');

        $notify = new Notify_pub();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		
		ss_log($xml);
		
        $notify->saveData($xml);
        if($payment['logs'])
        {
            $this->log(ROOT_PATH.'/data/wx_new_log.txt',"传递过来的XML\r\n".var_export($xml,true));
        }
        if($notify->checkSign() == TRUE)
        {
            ss_log("本地签名正确");
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                ss_log("return_code失败");
                if($payment['logs']){
                    $this->log(ROOT_PATH.'/data/wx_new_log.txt',"return_code失败\r\n");
                }
            }
            elseif($notify->data["result_code"] == "FAIL"){
                ss_log("result_code失败");
                //此处应该更新一下订单状态，商户自行增删操作
                if($payment['logs']){
                    $this->log(ROOT_PATH.'/data/wx_new_log.txt',"result_code失败\r\n");
                }
            }
            else{
                //此处应该更新一下订单状态，商户自行增删操作
                ss_log("支付成功");
                if($payment['logs']){
                    $this->log(ROOT_PATH.'/data/wx_new_log.txt',"支付成功\r\n");
                }
                $total_fee = $notify->data["total_fee"];
				
				$log_id_arr = explode('-',$notify->data['out_trade_no']);
				ss_log('data[out_trade_no]:'.$notify->data['out_trade_no'].",log_id:".$log_id_arr[1]);
				$log_id = $log_id_arr[1];
                $sql = 'SELECT order_amount FROM ' . $GLOBALS['ecs']->table('pay_log') ." WHERE log_id = '$log_id'";
                $amount = $GLOBALS['db']->getOne($sql);
                
                if($payment['logs'])
                {
                    $this->log(ROOT_PATH.'/data/wx_new_log.txt','订单金额'.$amount."\r\n");
                }
                
                if(intval($amount*100) != $total_fee)
                {
                    ss_log("金额不相等total_fee：".$total_fee.",amount:".$amount*100);
                    if($payment['logs'])
                    {   
                        $this->log(ROOT_PATH.'/data/wx_new_log.txt','订单金额不符'."\r\n");
                    }
                    
                    echo 'fail';
                    return;
                }

                order_paid($log_id, 2);
                return true;
            }

        }
        else
        {
            ss_log("本地签名失败");
            $this->log(ROOT_PATH.'/data/wx_new_log.txt',"签名失败\r\n");
        }
        return false;
       
    }

    function get_scancodepay($order){
		global $_CFG;
        require_once "WxPayPubHelper/WxPay.Api.php";
        require_once "WxPayPubHelper/WxPay.NativePay.php";

        $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('pay_log') .
            " WHERE log_id = '$order[log_id]'";

        $pay_log = $GLOBALS['db']->getRow($sql);
        
        
        $back_url = '';
        if($pay_log['order_type']==PAY_ORDER){
            $log_id = insert_pay_log ( $order['order_id'], $order['order_amount'], PAY_ORDER );
            $back_url='user.php?act=order_detail&order_id='.$order['order_id'].'';
            
        }else if($pay_log['order_type']==PAY_SURPLUS){
            $log_id = insert_pay_log ( $order['order_sn'], $order['order_amount'], PAY_SURPLUS );
            $back_url='user.php?act=account_log&process_type=0';
        }

		
		if(!$log_id){
			
			die('<script>alert("抱歉，内部错误，请联系客服！");history.go(-1);</script>');
		}
		
        $notify = new NativePay();

        $notify = new NativePay();
        $input = new WxPayUnifiedOrder();
        $input->SetBody($order['order_sn']);
        $input->SetAttach(strval($log_id));

        $input->SetOut_trade_no($order['order_sn'].'-'.$log_id);


        $input->SetTotal_fee(strval(intval($order['order_amount']*100)));
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");


        $input->SetNotify_url(WXNOTIFY_URL);
        ss_log('WXNOTIFY_URL:'.WXNOTIFY_URL);
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id($order['order_sn']);
        $result = $notify->GetPayUrl($input);
        ss_log(print_r($result,true));
        $url2 = $result["code_url"];
        $url2 =urlencode($url2);
        $html='<div align="center">';
        $html.="<img alt=\"模式二扫码支付\" src=\"http://paysdk.weixin.qq.com/example/qrcode.php?data=$url2\" style=\"width:150px;height:150px;\"/>
               <br> <img src='themes/".$_CFG['template']."/images/scan_code_desc.png' /></div>";
        
        $html.='<script type="text/javascript">
        		 	$(function(){setInterval("is_pay_ok();",2000);});
        			function is_pay_ok(){
						$.ajax({
						   type: "GET",
						   url: "user.php",
						   data: "act=search_pay_status&log_id='.$log_id.'",
						   success: function(res){
						      if(res=="1"){
						    	  alert("支付成功！");
						    	  window.location="'.$back_url.'";
						      }
						   }
					});
				}</script>';
				
        return $html;

    }


    function log($file,$txt)
    {
       $fp =  fopen($file,'ab+');
       fwrite($fp,'-----------'.local_date('Y-m-d H:i:s').'-----------------');
       fwrite($fp,$txt);
       fwrite($fp,"\r\n\r\n\r\n");
       fclose($fp);
    }
    
}
?>