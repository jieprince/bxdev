<?php

/**
 * ECSHOP 支付宝插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: cuikai 14-8-19 上午11:13
 * 手机支付插件
 */
if (!defined('IN_ECS')) {
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/payment/weixinpay.php';

if (file_exists($payment_lang)) {
    global $_LANG;

    include_once($payment_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE) {
    $i = isset($modules) ? count($modules) : 0;

    /* 是否是mobile端支付 cuikai 14-8-18 下午5:00 */
    $modules[$i]['is_mobile'] = '1';

    /* 代码 */
    $modules[$i]['code'] = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc'] = 'weixinpay_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod'] = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online'] = '1';

    /* 作者 */
    $modules[$i]['author'] = 'cuikai';

    /* 网址 */
    $modules[$i]['website'] = 'http://user.qzone.qq.com/1459970418/infocenter?ptsig=R2pGufaRj7lnQ37RgJFMl5-N5-RiIF*AvQkmZkL*p7E_';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.2';

    /* 配置信息 */
    $modules[$i]['config'] = array(
        array('name' => 'PARTNERKEY', 'type' => 'text', 'value' => ''),
        array('name' => 'APPKEY', 'type' => 'text', 'value' => ''),
        array('name' => 'SIGNTYPE', 'type' => 'text', 'value' => ''),
        array('name' => 'partner', 'type' => 'text', 'value' => ''),
        array('name' => 'body', 'type' => 'text', 'value' => '')
    );

    return;
}

/**
 * 类
 */
class weixinpay {

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function weixinpay() {
        
    }

    function __construct() {
        $this->weixinpay();
    }

    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment) {
       // echo "<pre>";print_r($order);
        //获取微信开发者配置缓存信息
        //$weixinsetting = read_static_cache('shop_config-weixin');
        $weixinsetting = $GLOBALS['db']->getRow("SELECT appid,appsecret from bx_weixinsetting order by add_time desc limit 1");
        //服务器异步通知页面路径
        $notify_url     = "http://" . $_SERVER['HTTP_HOST'] . "/mobile/respond.php?code=weixinpay";
        //页面跳转同步通知页面路径
       	//add yes123 2015-01-20  支付订单和充值跳转的页面不一样 
        $order_id = isset ($order['order_id']) ? intval($order['order_id']) : 0;
       	if($order_id){
       		$return_url     = "http://" . $_SERVER['HTTP_HOST'] . "/mobile/user.php?act=order_detail&order_id=".$order['order_id'];
       	} else{
	        $return_url     = "http://" . $_SERVER['HTTP_HOST'] . "/mobile/account.php?process_type=0";
       	}
       	
       	$js_api_call="./wxpay/js_api_call.php";
       	//add yes123 2015-01-29 活动重定向URL
       //	if($order['activity_id']){  必须是模拟b端登录的支付完成后才引导用户去关注E保险公众号
        $client_id = isset($order['client_id'])?$order['client_id']:0;
        $activity_id = isset($order['activity_id'])?$order['activity_id']:0;
      	if($activity_id && $client_id){
       		$return_url = "http://mp.weixin.qq.com/s?__biz=MzAxNTI2OTk5MA==&mid=203156096&idx=1&sn=da71c9995a77cb7fa14be7a2cc15c205#rd";
       		$js_api_call="../mobile/wxpay/js_api_call.php";
       	}
       	
        //服务器ip地址
        $spbill_create_ip = real_ip();
        
        $button = "<form name=\"\" action=\"$js_api_call\" method=\"post\">
	<input type=\"hidden\" name=\"order_sn\" value=\"" . $order['order_sn'] . "\"/>
	<input type=\"hidden\" name=\"order_amount\" value=\"" . $order['order_amount'] . "\"/>
	<input type=\"hidden\" name=\"log_id\" value=\"" . $order['log_id'] . "\"/>
        <input type='hidden' name='appid' value='$weixinsetting[appid]' />
        <input type='hidden' name='appsecret' value='$weixinsetting[appsecret]' />
        <input type='hidden' name='PARTNERKEY' value='$payment[PARTNERKEY]' />
        <input type='hidden' name='APPKEY' value='$payment[APPKEY]' />
        <input type='hidden' name='SIGNTYPE' value='$payment[SIGNTYPE]' />
        <input type='hidden' name='partner' value='$payment[partner]' />
        <input type='hidden' name='body' value='$payment[body]' />
        <input type='hidden' name='notify_url' value='$notify_url' />
        <input type='hidden' name='return_url' value='$return_url' />
        <input type='hidden' name='spbill_create_ip' value='$spbill_create_ip' />
	<input type=\"submit\"  value=\"微信支付\" style=\"width:80px; line-height:20px;background-color:#F7762E;border-radius:3px;color:#fff;padding:3px 5px\"/>
        </form>";
 
        return $button;
    }       

    /**
     * 响应操作
     */
    function respond() {
        /* 获取微信推送的订单信息 */
        $order_sn       = $_REQUEST['out_trade_no'];//商城订单号
        $transaction_id = $_REQUEST['transaction_id'];//微信订单号
        $total_fee      = $_REQUEST['total_fee']/100;//订单总金额

        // 日志记录
        //Log::write("[order_sn:".$order_sn." ] [transaction_id:".$transaction_id ."] [total_fee:".$total_fee ."] ", "PAY_ERROR", "/home/wwwlogs/weixinpay.log");

        if(!check_money_reset_with_weixin($order_sn,$total_fee))
        {
            // 日志记录
            Log::write("支付金额与订单金额不符[order_sn:{$out_trade_no}][支付金额:{$total_fee}]", 'PAY_ERROR', "/home/wwwlogs/weixinpay.log");
        }else{
            //Log::write("支付金额与订单金额相符[order_sn:{$order_sn}][支付金额:{$total_fee}]", 'PAY_ERROR', "/home/wwwlogs/weixinpay.log");
        }

        // 更新支付状态为支付成功
        order_paid_reset_with_weixin($order_sn,$transaction_id); 

        exit("success");//向微信回复成功状态
    }

}

?>