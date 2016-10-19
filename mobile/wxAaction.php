<?php
define('IN_ECS', true);
require(str_replace('\\','/',dirname(__FILE__)) . '/includes/init.php');
include_once (ROOT_PATH . 'mobile/includes/class/wxUtils.class.php');
$act = isset($_REQUEST['act'])?trim($_REQUEST['act']):'';

if($act=="downimg")
{
	ss_log("开始下载图片。。");
	
	$res = array('code'=>0);
	$media_id = isset($_REQUEST['media_id'])?trim($_REQUEST['media_id']):'';
		
	if($media_id)
	{
		$target = "attachment/insurance_policy_attachment_$_SESSION[user_id]";
		$wxUtils = new wxUtils(APPID, APPSECRET);
		$filename = $wxUtils->getMediaById($media_id,ROOT_PATH.$target);
		$res['url']=$target."/".$filename;
	
	}
	else
	{
		$res['code']=1;
		$res['msg'] = '资源ID不能为空';
		
	}
	
	ss_log("下载文件结果：".print_r($res,true));
	
	die(json_encode($res));
}





?>
