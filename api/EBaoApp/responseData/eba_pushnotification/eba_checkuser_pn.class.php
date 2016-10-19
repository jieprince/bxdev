<?php
/**
 * 会员审核
 * $Author: dingchaoyang $
 * 2014-12-15 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/eba_pushnotification/eba_checkuser_pn.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'includes/modules/pushnotification/XingeApp.php');
// include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
// include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
// include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
// include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
// include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_CheckUserPush{
	private $uid;
	private $msg;
	function __construct($uid,$success){
		$this->uid = $uid;
		if ($success == '0'){//审核通过
			$this->msg = 	XG_PN_CHECKUSER_SUCCESSMSG_CONTENT;
		}
		if($success == '1'){//审核不通过
			$this->msg = 	XG_PN_CHECKUSER_FAILMSG_CONTENT;
		}
	}
	
	function pushNotification(){
		$searchSql = "select * from bx_app_client_info where uid=" . $this->uid ;
		 echo $searchSql;
		
		$sSet = $GLOBALS ['db']->getAll ( $searchSql );
		foreach ($sSet as $key=>$value){
			if ($value['token']){
				if ($value['platformId'] == ANDROIDPLATFORM){
					$push = new XingeApp(XG_PN_ANDROID_ACCESSID, XG_PN_ANDROID_ACCESSKEY);
					$mess = new Message();
					$mess->setExpireTime(86400);
					$mess->setType(Message::TYPE_NOTIFICATION);
					$mess->setTitle(XG_PN_MSG_TITLE);
					$mess->setContent($this->msg);
					// 					$style = new Style(0);
					$style = new Style(0,1,1,1);//0，1响铃， 1震动，1 可清除
					$action = new ClickAction();
					$action->setActionType(ClickAction::TYPE_INTENT);
					$action->setIntent('intent:10086#Intent;scheme=tel;action=android.intent.action.DIAL;S.key=value;end');
					$mess->setStyle($style);
					$mess->setAction($action);
					$ret = $push->PushSingleDevice($value['token'], $mess);
// 					echo 'android';
// 					print_r($ret) ;
// 					exit;
					return($ret);
				}elseif ($value['platformId'] == IPHONEPLATFORM){
					$push = new XingeApp(XG_PN_IPHONE_ACCESSID, XG_PN_IPHONE_ACCESSKEY);
					$mess = new MessageIOS();
					$mess->setExpireTime(86400);
					//$mess->setSendTime("2014-03-13 16:00:00");
					$mess->setAlert($this->msg);
					//$mess->setAlert(array('key1'=>'value1'));
					// 					$mess->setBadge(1);
					// 					$mess->setSound("beep.wav");
					$custom = array('type'=>'0');
					$mess->setCustom($custom);
					$acceptTime = new TimeInterval(0, 0, 23, 59);
					$mess->addAcceptTime($acceptTime);
					$ret = $push->PushSingleDevice($value['token'], $mess, XingeApp::IOSENV_DEV);
// 					echo 'iphone';
// 					print_r($ret) ;
// 					exit;
					return $ret;
				}
			}
		}
	}
} 

?>