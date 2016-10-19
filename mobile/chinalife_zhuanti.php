<?php

/**
 * ECSHOP 商品页
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: testyang $
 * $Id: goods.php 15013 2008-10-23 09:31:42Z testyang $
*/

define('IN_ECS', true);
define('ECS_ADMIN', true);

require(dirname(__FILE__) . '/includes/init.php');
//require(dirname(__FILE__) .'/baoxian/common.php');
include_once(dirname(__FILE__) .'./../baoxian/common.php');
include_once(ROOT_PATH . 'includes/hongdong_function.php');
require(dirname(__FILE__) . './../includes/lib_code.php');


if(!isset($_REQUEST['act']))
{
	$_REQUEST['act']='';
}


//add yes123 2015-02-05 uid参数转换
if($_REQUEST['act'] == 'uid_transform')
{
	$uid = $_REQUEST['uid'];
	$encrypted_data = ebaoins_encrypt($uid,"syden1981");
	$encrypted_data = array('parameter'=>"act=getc&uid=$encrypted_data");
	exit;
}
//通过代理人ID和产品ID获取剩余赠送数量
//comment by zhangxi, 20150313, 这里也要进行通用性的修改
else if($_REQUEST['act'] == 'get_give_count')
{
	$msg = "";
	$code=0;
	$uid = isset ($_REQUEST['uid']) ? $_REQUEST['uid'] : 0;
	$gid = isset ($_REQUEST['gid']) ? $_REQUEST['gid'] : 0;
	$activity_id = isset ($_REQUEST['activity_id']) ? $_REQUEST['activity_id'] : 0;
	if($activity_id){
		$give_product_cfg = get_give_product_cfg($uid,$gid,$activity_id);
		$activity = get_current_activity_by_id($activity_id);
		if(empty($activity)){
			$msg="不是活动期间";
			$code=10;//两款产品活动都已经结束
		}
		die(json_encode(array (
			'code' => $code,
			'surplus_num' =>$give_product_cfg['give_count']-$give_product_cfg['order_num'],
			'msg'=>$msg
		)));
	}
	
	
	$jn_gid = isset ($_REQUEST['jn_gid']) ? $_REQUEST['jn_gid'] : 0;
	$jw_gid = isset ($_REQUEST['jw_gid']) ? $_REQUEST['jw_gid'] : 0;
	$jn_activity_id = isset ($_REQUEST['jn_activity_id']) ? $_REQUEST['jn_activity_id'] : 0;
	$jw_activity_id = isset ($_REQUEST['jw_activity_id']) ? $_REQUEST['jw_activity_id'] : 0;

	$give_product_cfg_jn = get_give_product_cfg($uid,$jn_gid,$jn_activity_id);
	$give_product_cfg_jw = get_give_product_cfg($uid,$jw_gid,$jw_activity_id);
	
	//start add yes123 2015-02-06 判断活动是否结束
	$jn_activity = get_current_activity_by_id($jn_activity_id);
	$jw_activity = get_current_activity_by_id($jw_activity_id);

	if(empty($jn_activity) && empty($jw_activity)){
		$msg="不是活动期间";
		$code=10;
	}
	//end add yes123 2015-02-06 判断活动是否结束
	
	die(json_encode(array (
				'code' => $code,
				'give_count'=>$give_product_cfg_jn['give_count'],
				'jn_surplus_num' =>$give_product_cfg_jn['give_count']-$give_product_cfg_jn['order_num'],
				'jw_surplus_num' =>$give_product_cfg_jw['give_count']-$give_product_cfg_jw['order_num'],
				'msg'=>$msg
	)));

}else if($_REQUEST['act'] == 'go_insure'){
	$user_id = isset ($_REQUEST['uid']) ? $_REQUEST['uid'] : 0;
	$url = isset ($_REQUEST['url']) ? $_REQUEST['url'] : "";
	$goods_id = isset ($_REQUEST['gid']) ? $_REQUEST['gid'] : 0;	
	if(strstr($user_id,","))
	{
		$arr = explode(",",$user_id);
		$user_id = $arr[0];
		ss_log("go_insure user_id:".$user_id);
	}
	$data = check_legal($user_id,$goods_id);
	$data['url'] = $url;
	die(json_encode($data));
	
}
/*获取验证码*/
elseif($_REQUEST['act'] == 'send_phonecode'){
	
	include_once(ROOT_PATH.'sdk_sms.php');
	$type = isset ($_REQUEST['type']) ? trim($_REQUEST['type']) : "";
	$mobile_phone = isset ($_REQUEST['mobile_phone']) ? trim($_REQUEST['mobile_phone']) : "";
	$activity_id = isset ($_REQUEST['activity_id']) ? trim($_REQUEST['activity_id']) : 0;
	$goods_id = isset ($_REQUEST['goods_id']) ? trim($_REQUEST['goods_id']) : 0;
	
	//验证手机号码后，再验证openid
	$c_openid = isset($_SESSION['c_openid'])?$_SESSION['c_openid']:"no";
	$sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users_c') ." WHERE c_openid='$c_openid'";
	$user_id =  $GLOBALS['db']->getOne($sql);
	ss_log('send_phonecode, get activity_id='.$activity_id.',mobile_phone='.$mobile_phone);
//	if($user_id){
//		$sql = "SELECT order_id FROM " . $GLOBALS['ecs']->table('order_info') ." WHERE activity_id='$activity_id' AND client_id='$user_id' AND pay_status=2 ";
//		ss_log("通过openid判断用户是否投保过sql:".$sql);
//		$order_id =  $GLOBALS['db']->getOne($sql);
//		if($order_id){
//			ss_log('很抱歉，每个微信号只能投保一次！'.$sql);
//			$datas = json_encode(array('code'=>11,'msg'=>'很抱歉，每人只能投保一次！'));
//			die($datas);
//		}
//		
//	}
	
	
	
	
	$datas = send_phonecode_zhuanti($mobile_phone,$type,0,$goods_id);
	die($datas);
}

/*C端用户帐号注册*/
else if($_REQUEST['act'] == 'client_reg')
{	
	$client = isset($_SESSION['client'])?$_SESSION['client']:"";
	if(!$client){
		$datas = json_encode(array('code'=>10,'msg'=>'请先获取验证码！'));
		die($datas);
	}
	$code = trim($_REQUEST['code']);
	$mobile_phone = trim($_REQUEST['mobile_phone']);
	$datas = client_reg($client,$mobile_phone,$code);
	die($datas);
	
}

/*判断是否登录*/
else if($_REQUEST['act'] == 'is_login')
{	
	//获取openid
	$user_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
	if($user_id){
		$uid = ebaoins_encrypt($user_id,"syden1981");
		//add yes123 2015-05-04 判断是不是渠道
		$sql = "SELECT * FROM bx_users  WHERE user_id = '$user_id' ";
	    $user =  $GLOBALS['db']->getRow($sql);
		$data = array (
				'code' => 0,
				'uid'=>$uid,
				'check_status'=>$user['check_status'],
				'institution_id'=>$user['institution_id'],
				'msg' => 'error'
		);
		
		die(json_encode($data));
			
	}else{
		die(json_encode(array (
				'code' => 1,
				'msg' => 'not login'
		)));
	}
	
}
//清空用户信息  
else if($_REQUEST['act'] == 'clear_session')
{	
	ss_log("start clear_session");
    $_SESSION['user_id']   = 0;
    $_SESSION['user_name'] = "";
    $_SESSION['real_name'] = "";
    $_SESSION['email']     = "";
    $_SESSION['check_status']     = "";//add 缓存是否审核信息 2014-10-05 yes123
    //modify yes123 2015-02-09 查询所有，判断如果openid和c_openid 有值，就设置到session
    $_SESSION['b_openid'] = "";
    $_SESSION['c_openid'] = "";
    echo "清除session完毕！";
    exit;
	
}
else if($_REQUEST['act'] == 'share_active')
{	
	$login_type = isset($_SESSION['login_type'])?$_SESSION['login_type']:"";
	$smarty->assign('login_type', $login_type);
	$activity_id = 0;
	if(isset($_GET['active_id']))
	{
		$activity_id = $_GET['active_id'];
	}
	$smarty->assign('activity_id', $activity_id);
	$smarty->display('share_reg.dwt');//邀请注册页面链接
	exit;
}
else if($_REQUEST['act'] == 'getc')//分享给C看到的链接
{
	$uid=$_REQUEST['uid'];
	
	$c_openid = isset($_SESSION['c_openid']) ? $_SESSION['c_openid'] : '';
	if ($c_openid=='') {
		 if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) 
		 {  
		 	include_once(ROOT_PATH . 'mobile/getcopenid.php');
		 	$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?uid=".$uid;
		 	ss_log('getc.url:'.$url);
			get_c_openid($url);
			exit;
		 }
	
	}
	
	
	//
	$b_openid = isset($_SESSION['b_openid']) ? $_SESSION['b_openid'] : '';
	if ($b_openid=='') {
		 if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) 
		 {  
		 	include_once(ROOT_PATH . 'mobile/getbopenid.php');
		 	$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?uid=".$uid;
		 	ss_log('activity_b_openid getc.url:'.$url);
			get_activity_b_openid($url);
			exit;
		 }
	}else{
		ss_log("走getc 已经有b_openid:".$b_openid);
		
	}
	//////////////////////////////////////////////////////////////////////////
	$activity_product = "guoshou_xueping";
	
	if($activity_product == 'guoshou_xueping')
	{
		assign_xuepingxian($smarty,$db, $uid);
	}
	
	
	//added by zhangxi, 20150210, 代理人手机，姓名等信息获取，用于c端页面保险顾问信息显示
	$curr_uid = isset($_REQUEST['uid'])?$_REQUEST['uid']:'';
	if($curr_uid != '')
	{
		$row = explode(',', $curr_uid);
		$real_uid = intval($row[0]);
		if($real_uid>0)
		{
			$sql = "SELECT * from bx_users where user_id='$real_uid'";
			$insurance_agent_info = $GLOBALS['db']->getRow($sql);
			//判断，如果审核通过，显示$real_uid的手机号码，否则显示jilin代理的
/*			if(!$insurance_agent_info['is_cheack'])
			{ 
				$institution_id = 0;
				if($insurance_agent_info['institution_id'])
				{
					$institution_id = $insurance_agent_info['institution_id'];
				}
				else
				{
					include_once(ROOT_PATH . 'baoxian/source/my_const.php');
					$institution_id = JILIN_INSTITUTION_ID;
					
				}
				
				$insurance_agent_info = get_institution_byid($institution_id);
			}*/
			$smarty->assign('insurance_agent_info', $insurance_agent_info);
			ss_log("get agent info success, uid = $real_uid");
		}
		else
		{
			ss_log("get agent info error, uid = 0");
		}
	}
	//用来区分当前是哪款产品做活动
	$smarty->assign('activity_product', $activity_product);
	$smarty->assign('uid', $uid);
	$smarty->display('share_product_huodong_client_'.$activity_product.'.dwt');//C看到的
}
else
{
	
	//$goods_id = !empty($_GET['id']) ? intval($_GET['id']) : '';
	//$act = !empty($_GET['act']) ? $_GET['act'] : '';
	$encrypted_data="";
	if ($_SESSION['user_id'] > 0)
	{
		$smarty->assign('user_id', $_SESSION['user_id']);
		$encrypted_data = ebaoins_encrypt($_SESSION['user_id'],"syden1981");
	}
	$uid=$encrypted_data;//用户登录后，uid加密返回，就可以分享给c端，让c端进行购买了，b端自己也可以进行购买了。
	
	$activity_product = "guoshou_xueping";
	if($activity_product == 'guoshou_xueping')
	{
		$uid=0;
		assign_xuepingxian($smarty,$db, $uid);
	}
	
	
	$smarty->assign('activity_product', $activity_product);
	$smarty->assign('uid', $uid);
	
	$smarty->display('share_product_huodong_'.$activity_product.'.dwt');//B看到的
}

function assign_xuepingxian($smarty,$db, $uid)
{
	$wheresql=" ipb.product_code ='CNL1001'";
		$sql = "SELECT * from t_insurance_product_base AS ipb" .
					" INNER JOIN t_insurance_product_attribute AS ipa" .
					" ON ipb.attribute_id=ipa.attribute_id" .
					" INNER JOIN bx_goods AS g" .
					" ON g.tid=ipa.attribute_id " .
					" WHERE".$wheresql;
		$product_info = $GLOBALS['db']->getRow($sql);
		$product_id = $product_info['product_id'];
		$gid = $product_info['goods_id'];
		$smarty->assign('youer_gid', $gid);
		$favourable_activity = get_current_activity_by_goods_id($gid);
		$smarty->assign('youer_msg', "产品正式销售时间为：5月4日到6月30日");
		if($favourable_activity){
			$smarty->assign('youer_activity_id', $favourable_activity['act_id']);
		}else{
			$smarty->assign('youer_activity_id', 0);
			
		}
		
		$guoshou_activity_id =  $favourable_activity['act_id'];
		
		//baoxian/cp.php?ac=product_buy&product_id=5&gid=40
		$url="baoxian/cp.php?ac=product_buy&product_id=$product_id&gid=$gid&uid=$uid&activity_id=".$favourable_activity['act_id'];
		//$url="baoxian/cp.php?ac=product_buy&product_id=$product_id&gid=$gid";
		ss_log(__FUNCTION__.", url_youer=".$url);
		$smarty->assign('url_youer', $url);//国寿儿童保险保障计划
		$smarty->assign('guoshou_activity_id', $guoshou_activity_id);
		//$smarty->assign('buy_detail', get_buy_detail($ranqi_activity_id)); //购买明细	
		
		$wheresql=" ipb.product_code ='CNL1002'";
		$sql = "SELECT * from t_insurance_product_base AS ipb" .
					" INNER JOIN t_insurance_product_attribute AS ipa" .
					" ON ipb.attribute_id=ipa.attribute_id" .
					" INNER JOIN bx_goods AS g" .
					" ON g.tid=ipa.attribute_id " .
					" WHERE".$wheresql;
		$product_info = $GLOBALS['db']->getRow($sql);
		$product_id = $product_info['product_id'];
		$gid = $product_info['goods_id'];
		$smarty->assign('gid_xuesheng1', $gid);
		$favourable_activity = get_current_activity_by_goods_id($gid);
		$smarty->assign('xuesheng_msg', "产品正式销售时间为：5月4日到6月30日");
		if($favourable_activity){
			$smarty->assign('xuesheng_activity_id', $favourable_activity['act_id']);
		}else{
			$smarty->assign('xuesheng_activity_id', 0);
			
		}
		
		//$ranqi_activity_id =  $favourable_activity['act_id'];
		$url_xuesheng_fangan1="baoxian/cp.php?ac=product_buy&product_id=$product_id&gid=$gid&uid=$uid&activity_id=".$favourable_activity['act_id'];
		ss_log(__FUNCTION__.", url_xuesheng_fangan1=".$url_xuesheng_fangan1);
		$smarty->assign('url_xuesheng_fangan1', $url_xuesheng_fangan1);
		
		$wheresql=" ipb.product_code ='CNL1003'";
		$sql = "SELECT * from t_insurance_product_base AS ipb" .
					" INNER JOIN t_insurance_product_attribute AS ipa" .
					" ON ipb.attribute_id=ipa.attribute_id" .
					" INNER JOIN bx_goods AS g" .
					" ON g.tid=ipa.attribute_id " .
					" WHERE".$wheresql;
		$product_info = $GLOBALS['db']->getRow($sql);
		$product_id = $product_info['product_id'];
		$gid = $product_info['goods_id'];
		$smarty->assign('gid_xuesheng2', $gid);
		$favourable_activity = get_current_activity_by_goods_id($gid);
		//$ranqi_activity_id =  $favourable_activity['act_id'];
		$url="baoxian/cp.php?ac=product_buy&product_id=$product_id&gid=$gid&uid=$uid&activity_id=".$favourable_activity['act_id'];
		ss_log(__FUNCTION__.", url_xuesheng_fangan2=".$url);
		$smarty->assign('url_xuesheng_fangan2', $url);
}


function get_buy_detail($activity_ids)
{
	include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
	
	$sql = "SELECT ip.attribute_name,u.user_name FROM t_insurance_policy ip,bx_users_c u WHERE ip.attribute_name NOT LIKE '%测试%' AND  u.user_id=ip.client_id AND ip.activity_id IN('$activity_ids') ORDER BY policy_id DESC LIMIT 0,10";
    $policy_list =  $GLOBALS['db']->getAll($sql);
    if($policy_list){
    	foreach ( $policy_list as $key => $policy ) {
	       $user_name_str = hidtel($policy['user_name']);
	       //刚刚159****1521领取了40万航空意外保险
	       $policy_list[$key]['text']="刚刚".$user_name_str."领取了".$policy['attribute_name'];
		}
	   // echo "<pre>";print_r($policy_list);
	    $sql = "SELECT count(ip.policy_id) FROM t_insurance_policy ip,bx_users_c u WHERE ip.attribute_name NOT LIKE '%测试%' AND  u.user_id=ip.client_id AND ip.activity_id IN('$activity_ids')";
	    $policy_count =  $GLOBALS['db']->getOne($sql);
	    return array("policy_list"=>$policy_list,'policy_count'=>$policy_count+5000);
    }
}

function hidtel($phone){
	$string = $phone;
	$pattern = "/(1\d{1,2})\d\d(\d{0,3})/";
	$replacement = "\$1****\$3";
	$phone =  preg_replace($pattern, $replacement, $string);
	return $phone;
}

function get_institution_byid($id)
{
	$sql = "SELECT * from bx_distributor where d_uid='$id'";
	ss_log(__FUNCTION__.":".$sql);
	$insurance_agent_info = $GLOBALS['db']->getRow($sql);	
	$insurance_agent_info['user_id']=$insurance_agent_info['d_uid'];
	$insurance_agent_info['real_name']=$insurance_agent_info['d_contacts'];
	$insurance_agent_info['mobile_phone']=$insurance_agent_info['d_mobilePhone'];
	
	return $insurance_agent_info;
}
?>