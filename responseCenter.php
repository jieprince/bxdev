<?php
/**
 * 对于在目前web系统上咱是提供不了的数据，在这里统一处理
 * 根据客户端上传过来的command名字，通过工厂类直接生成相应的对象并作响应
 * $Author: dingchaoyang $
 * 2014-11-12 $
 */
define ( 'IN_ECS', true );
$ROOT_PATH_= str_replace ( 'responseCenter.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_ . 'api/EBaoApp/eba_adapter.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');

$command = $_REQUEST['command'];
$needSessionArray = array(APP_COMMAND_PROMOTION,APP_COMMAND_LOCATION,APP_COMMAND_SHORTSHOW,APP_COMMAND_POLICYRESULT,APP_COMMAND_INSURERCOMPANY);
if (in_array($command, $needSessionArray)){
	//add sessionmanager for app dingchaoyang 2014-12-21
	include_once ($ROOT_PATH_ . 'api/EBaoApp/eba_sessionManager.class.php');
	Eba_SessionManager::setSession();
	//end by dingchaoyang 2014-12-21
}
//add log for app dingchaoyang 2014-12-23
include_once ($ROOT_PATH_ . 'api/EBaoApp/eba_logManager.class.php');
Eba_LogManager::log('data from responsecenter.php,uid='.$_SESSION['user_id'].',command='.$command.'。');
//end
// echo 'a'.$command;
//根据command和不同的参数 调用相应的接口
if (isset($command) && !empty($command))
{
	if ($command == APP_COMMAND_POLICYRESULT){
		EbaAdapter::responsePolicyResult($_REQUEST);
	}
}

//没有其他参数，只有command，根据command直接生成对应的类对象
if (isset($command) && !empty($command)){
// 	echo $command;
	EbaAdapter::responseData($command);
}

// include_once ($ROOT_PATH_ . 'includes/modules/payment/wxpayapp.php');
// $pay = new wxpayapp;
// $pay->respond();
// echo 'fasfa';

// $i=1;
// echo intval($i/100);

// $order_sn='200-110';
// $log_id = explode('-',$order_sn);
// echo $log_id[1];
// //测试alipay 的手机网页支付

// include_once ($ROOT_PATH_ . 'includes/modules/payment/alipaywap/alipayapi.php');
// $alipayapi = new AlipayAPI();
// $alipayapi->setParams(null);
// include_once ($ROOT_PATH_ . 'includes/modules/payment/alipaywap.php');
// $pay = new alipaywap();
// $order = array('order_sn'=>'2011000011112222','order_amount'=>0.01);
// echo $pay->get_code($order, '');
// // $payHtml = $pay->get_code($order, $other);

// echo $pay->get_code($order, $other);

// //start 测试推送通知
// include_once ($ROOT_PATH_ . 'includes/modules/pushnotification/XingeApp.php');

// //单个设备下发通知Intent
// //setIntent()的内容需要使用intent.toUri(Intent.URI_INTENT_SCHEME)方法来得到序列化后的Intent(自定义参数也包含在Intent内）
// //终端收到后通过intent.parseUri()来反序列化得到Intent
// function DemoPushSingleDeviceNotificationIntent()
// {
// 	$push = new XingeApp(App_PN_XG_ACCESSID, App_PN_XG_ACCESSKEY);
// 	$mess = new Message();
// 	$mess->setExpireTime(86400);
// 	$mess->setType(Message::TYPE_NOTIFICATION);
// 	$mess->setTitle("title");
// 	$mess->setContent("通知点击执行Intent测试");
// 	$style = new Style(0);
// 	$style = new Style(0,1,1,0);
// 	$action = new ClickAction();
// 	$action->setActionType(ClickAction::TYPE_INTENT);
// 	$action->setIntent('intent:10086#Intent;scheme=tel;action=android.intent.action.DIAL;S.key=value;end');
// 	$mess->setStyle($style);
// 	$mess->setAction($action);
// 	$ret = $push->PushSingleDevice('token', $mess);
// 	return($ret);
// }

// //end 测试通知

?>