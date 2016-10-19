<?php
/*路由功能*/
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH . 'baoxian/source/function_debug.php');
include_once(ROOT_PATH . 'includes/hongdong_function.php');

$act = isset($_REQUEST['act']) ? $_REQUEST['act'] : '';



/*校验校验赠送剩余数量*/
if ($act == 'check_agent_num')
{
	$uid = isset ($_REQUEST['uid']) ? trim($_REQUEST['uid']) : 0;
	$gid = isset ($_REQUEST['gid']) ? trim($_REQUEST['gid']) : 0;
	if($uid && $gid){
		$res = check_agent_num($uid,$gid);
	}else{
		$res = array ('code' => 2,'msg' => 'error');
	}
	ss_log('校验赠送剩余数量 uid:'.$uid.",gid:".$gid);
	die($res);
}

/*获取验证码*/
if($act == 'send_phonecode'){
	include_once(ROOT_PATH.'sdk_sms.php');
	$type = isset ($_REQUEST['type']) ? trim($_REQUEST['type']) : "";
	$mobile_phone = isset ($_REQUEST['mobile_phone']) ? trim($_REQUEST['mobile_phone']) : "";
	$activity_id = isset ($_REQUEST['activity_id']) ? trim($_REQUEST['activity_id']) : 0;
	$goods_id = isset ($_REQUEST['goods_id']) ? trim($_REQUEST['goods_id']) : 0;
	
	$datas = send_phonecode($mobile_phone,$type,$activity_id,$goods_id);
	die($datas);
}

/*C端用户帐号注册*/
if($act == 'client_reg')
{	
	$client = $_SESSION['client'];
	$code = trim($_REQUEST['code']);
	$mobile_phone = trim($_REQUEST['mobile_phone']);
	$datas = client_reg($client,$mobile_phone,$code);
	die($datas);
	
}

/*获取openid*/
if($act == 'send_policy_by_openid')
{	
	include_once(ROOT_PATH.'client/includes/class/WeiXin.class.php');
	$weixin =  new WeiXin;
	$weixin->send_policy_by_openid();
}



/*C端用户帐号注册*/
if($act == 'upload_img')
{	
	include_once(ROOT_PATH.'client/includes/class/WeiXin.class.php');
	$weixin =  new WeiXin;
	$weixin->upload_img();
}

if($act == 'test_upload_img')
{	
	include_once(ROOT_PATH.'client/includes/class/WeiXin.class.php');
	$weixin =  new WeiXin;
	$img_path = ROOT_PATH."images/201411/goods_img/44_G_1416809539848.jpg";
	$weixin->upload_img($img_path);
}

if($act == 'test_upload_text_img')
{	
	include_once(ROOT_PATH.'client/includes/class/WeiXin.class.php');
	$weixin =  new WeiXin;
	$new[] = array(
		'thumb_media_id'=>'Os2S5xWRo4t8uM4ng65XOSvhFXz9TbvfchgXfcYMH-4OIhkq5-d0BcnD1ru_awS5',
		'author'=>'E保险',
		'title'=>'E保险保单',
		'content_source_url'=>'http://www.ebaoins.cn/client/user.php?act=policy_list',
		'content'=>'欢迎关注E保险，订单查询请点击',
		'digest'=>'E保险保单查询和下载'
	);
	$data = array('articles'=>$new);
	$weixin->update_text_img($data);
}
?>
