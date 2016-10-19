<?php

/**
 * ECSHOP 用户中心
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: user.php 16643 2009-09-08 07:02:13Z liubo $
*/

define('IN_ECS', true);
define('ECS_ADMIN', true);

require(dirname(__FILE__) . '/includes/init.php');
/* 载入语言文件 */
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/user.php');
include_once(ROOT_PATH . 'baoxian/source/function_debug.php');
include_once(ROOT_PATH . 'includes/lib_clips.php');
if ($_SESSION['user_id'] > 0)
{
	$smarty->assign('user_name', $_SESSION['user_name']);
}
$act = isset($_REQUEST['act']) ? $_REQUEST['act'] : '';
$user_id = $_SESSION['user_id'];

/* 用户登陆 */
if ($act == 'login')
{
	$smarty->display('login.dwt');exit;
	
}


/* 用户登陆 */
if ($act == 'do_login')
{
	$c_open_id = !empty($_SESSION['c_openid']) ? $_SESSION['c_openid'] : '';
	ss_log('do_login  open_id:'.$c_open_id);
	$user_name = !empty($_POST['username']) ? $_POST['username'] : '';
	$pwd = !empty($_POST['pwd']) ? $_POST['pwd'] : '';
	$gourl = !empty($_POST['gourl']) ? $_POST['gourl'] : '';
	
	$remember = isset($_POST['remember']) ? $_POST['remember'] : 0;
	//记住用户名字
	if(!empty($remember)){
		setcookie("ECS[reuser_name]", $user_name, time() + 31536000, '/');
	}
	$reuser_name= isset($_COOKIE['ECS']['reuser_name']) ? $_COOKIE['ECS']['reuser_name'] : '';
	if(!empty($reuser_name)){
		$smarty->assign('reuser_name', $reuser_name);
	}
	
	if (empty($user_name) || empty($pwd))
	{
		ecs_header("Location:user.php\n");
		$login_faild = 1;
	}
	else
	{ 
		
		//切换校验的表名
		$user->user_table='users_c';
		
		if ($user->check_user($user_name, $pwd) > 0)
		{	
			$user->set_session($user_name);
			$user->set_cookie($user_name);
			ss_log('client check user_id:'.$_SESSION['user_id'].',open_id:'.$c_open_id.",login_type:".$_SESSION['login_type']);
			updateOPenId($_SESSION['user_id'],$c_open_id);
			
			update_user_info();
           	recalculate_price();
                        
                
			//优化登陆跳转
			if($gourl){
				ss_log('into check_user111..');
				ecs_header("Location:$gourl\n");
				exit;
			}else{
				ecs_header("Location:user.php\n");
				//$smarty->display('user_menu.dwt');
				exit;
			}
		}
		else
		{
			ss_log('into check_user555..');
			$login_faild = 1;
		}
	}

	$smarty->assign('login_faild', $login_faild);
	$smarty->display('login.dwt');
	
	exit;
}

/* 订单列表 */
elseif ($act == 'order_list')
{ 
	$c_openid = isset($_SESSION['c_openid'])?$_SESSION['c_openid']:"";
	if(!$_SESSION['user_id'] && !$c_openid ){
		ss_log("client go order_list user_id and  c_penid is null  go login");
        $smarty->assign('footer', get_footer());
        $smarty->display('login.dwt');
        exit;
    }
    
    if(!$_SESSION['user_id'] && $c_openid){
    	getDbUserInfo($c_openid);
    }
    
    
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
    include_once(ROOT_PATH . 'includes/class/User.class.php');
    $_REQUEST['client_id'] = $_SESSION['user_id'];
    $user_obj = new User;
    
    if(isset($_SESSION['user_id'])){
    	
    	ss_log("C端获取订单列表，client_id:".$_REQUEST['client_id']);
    	$res = $user_obj->getOrderList();
		$payment = $res['payment'];
		$orders = $res['orders'];
		$condition = $res['condition'];
		$record_count = $res['record_count'];
		
		$pagebar = mobilePagebar($record_count,'user.php?act=order_list',$condition);
		$smarty->assign('pagebar' , $pagebar);
		
	    $smarty->assign('payment',  $payment);
		$smarty->assign('orders', $orders);
		$smarty->assign('condition', $condition);
		$smarty->display('order_list.dwt');
		exit;
    	
    }else{
    	$smarty->assign('footer', get_footer());
        $smarty->display('login.dwt');
    }

}

/* 订单详情 */
elseif($act=='order_detail'){
	if(!$_SESSION['user_id']){
		$smarty->display('login.dwt');
		exit;
	}
	$id= isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
	if(!$id){
		  $order_sn= isset($_GET['order_sn']) ? intval($_GET['order_sn']) : 0;
		  if($order_sn){
		  		$sql = "SELECT order_id FROM ". $ecs->table('order_info') . " WHERE order_sn='$order_sn'";
		  		$id = $db->getOne($sql);	
		  }
	}
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/lib_payment.php');
	include_once(ROOT_PATH . 'includes/lib_order.php');
	include_once(ROOT_PATH . 'includes/lib_clips.php');
	/* 订单详情 */
	$order = get_order_detail($id, $_SESSION['user_id']);
	
        //print_a($order);
	if ($order === false)
	{
		exit("对不起，该订单不存在");
	}
	require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/user.php');
	/* 订单商品 */
	$goods_list = order_goods2($id);
	if (empty($goods_list))
	{
	$tips = '<br><br>无效错误订单<br><br><a href=user.php?act=order_list class=red>返回我的订单</a>';
	$smarty->assign('tips', $tips);
	$smarty->display('order_done.dwt');
	exit();
	}
	foreach ($goods_list AS $key => $value)
	{
		$goods_list[$key]['market_price'] = price_format($value['market_price'], false);
		$goods_list[$key]['goods_price']  = price_format($value['goods_price'], false);
		$goods_list[$key]['subtotal']	 = price_format($value['subtotal'], false);
	}

	/* 订单 支付 配送 状态语言项 */
	$order['order_status'] = $_LANG['os'][$order['order_status']];
	$order['pay_status'] = $_LANG['ps'][$order['pay_status']];
	$order['shipping_status'] = $_LANG['ss'][$order['shipping_status']];
	$smarty->assign('order',	  $order); 
	$smarty->assign('goods_list', $goods_list);
	$smarty->assign('lang',	   $_LANG);
	$smarty->assign('footer', get_footer());
	ss_log("pay_online:". $order['pay_online']);
	$smarty->display('order_info.dwt');
	exit();
}
/* 取消订单 */
elseif ($act == 'cancel_order')
{
	if(!$_SESSION['user_id']){
		$smarty->assign('footer', get_footer());
		$smarty->display('login.dwt');
		exit;
	}
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/lib_order.php');

	$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
	if (cancel_order($order_id, $_SESSION['user_id']))
	{
		ecs_header("Location: user.php?act=order_list\n");
		exit;
	}
}
/* 保单列表 */
elseif($act == 'policy_list'){
  if(!$_SESSION['user_id']){
		$smarty->assign('footer', get_footer());
		$smarty->display('login.dwt');
		exit;
	}
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/lib_policy.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$_REQUEST['client_id'] = $_SESSION['user_id'];
	ss_log("REQUEST['client_id']:".$_REQUEST['client_id']);
	$user_obj = new User;
	$res = $user_obj->getPolicyList();
	$warranty = $res['policy_list'];
	$record_count = $res['record_count'];
	$condition = $res['condition'];
	
	if ($record_count > 0){
		$pagebar = mobilePagebar($record_count,'user.php?act=policy_list',$condition);
		$smarty->assign('pagebar' , $pagebar);
	}

	$smarty->assign('policy_list', $warranty);
    $smarty->assign('condition', $condition);
    $smarty->display('policy_list.dwt');
}
/* 保单详情 */
elseif($act == 'policy_detail'){
	global $attr_status;
	if(!$_SESSION['user_id']){
		$smarty->assign('footer', get_footer());
		$smarty->display('login.dwt');
		exit;
	}
	
   $policy_id = isset($_REQUEST['policy_id']) ? intval($_REQUEST['policy_id']) : 1;
  
   require_once(ROOT_PATH . 'baoxian/common.php');
   require_once(ROOT_PATH . 'baoxian/source/function_baoxian.php');
   if($policy_id)
	{
		$ret = get_policy_info_view($policy_id, $smarty);
		$policy = $ret['policy_arr'];
		if($policy['policy_status'])
		{
			$policy['policy_status_str'] = $attr_status[$policy['policy_status']];
		}
		
		$user_info_applicant = $ret['user_info_applicant'];
		$list_subject = $ret['list_subject'];//多层级一个链表
		$smarty->assign('policy', $policy);
		$smarty->assign('user_info_applicant', $user_info_applicant);
		$smarty->assign('list_subject', $list_subject);
		//$download_token = time();
		//$sql = " UPDATE t_insurance_policy SET download_token=".$download_token." WHERE policy_id=".$policy_id;   
		//$db->query($sql);
		//$smarty->assign('download_token', $download_token);
		
		//查询保险起期和止期
		$sql = "SELECT start_date,end_date FROM t_insurance_policy WHERE policy_id = '$policy_id' ";
		$start_and_end_date = $GLOBALS['db']->getRow($sql);
		
		$start_date = strtotime($start_and_end_date['start_date']);	
		$end_date = strtotime($start_and_end_date['end_date']);	
		$start_and_end_date['start_date'] = date("Y-m-d",$start_date);
		$start_and_end_date['end_date'] = date("Y-m-d",$end_date);
		$smarty->assign('start_and_end_date', $start_and_end_date);
		
		
		//获取商品信息
		$attribute_id = $policy['attribute_id'];
		$sql = " SELECT * FROM bx_goods WHERE  tid='$attribute_id'";   
		$goods = $db->getRow($sql);
		$smarty->assign('goods', $goods);
		
	}       
   $smarty->display('policy_info.dwt');
}
/*add yes123 2015-01-23 下载电子保单*/
elseif($act == 'download_policy_doc')
{
	$user_agent = $_SERVER["HTTP_USER_AGENT"];
	$policy_id = $_GET['policy_id'];
	if(strstr($user_agent,'MicroMessenger')){//来自微信浏览器
		$smarty->assign('footer', get_footer());
		$smarty->display('downpdf.dwt');
	}else{
		$r_download_token = isset ($_REQUEST['download_token']) ?trim($_REQUEST['download_token']): '';
		//校验token
		$download_token = $db->getOne("SELECT download_token FROM t_insurance_policy WHERE policy_id=".$policy_id);
		ss_log("r_download_token:".$r_download_token);
		ss_log("download_token:".$download_token);
		if($r_download_token == $download_token){
			$agent_uid = $_REQUEST['uid'];
			$_SESSION['user_id']=$agent_uid;
			//清空token
			$sql = " UPDATE t_insurance_policy SET download_token=0 WHERE policy_id=".$policy_id;   
			$db->query($sql);
			$url="../baoxian/cp.php?ac=product_buy&policy_id=$policy_id&op=getpolicyfile";
			header("Location: $url");  
		}else{
			echo "<script type=text/javascript>alert('链接过期，请重下！');location.href='user.php?act=policy_detail&policy_id=$policy_id';</script>";
			exit;
		}
	}
}

/* 个人资料页面 */
elseif ($act == 'profile')
{
    if(!$_SESSION['user_id']){
            $smarty->assign('footer', get_footer());
            $smarty->display('login.dwt');
            exit;
    }
    include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
    $user_obj = new User;
	$r = $user_obj->getProfile();
	$user_info = $r['user_info'];
	$extend_info_list = $r['extend_info_list'];

	if($user_info['check_status']==CHECKED_CHECK_STATUS)
	{
		$smarty->assign('province_name_my', $r['province_name_my']);
		$smarty->assign('city_name_my', $r['city_name_my']);
	}
	else
	{
		if(isset($r['provinces'])){
			$smarty->assign('provinces', $r['provinces']);
		}
		if(isset($r['citys'])){
			$smarty->assign('citys', $r['citys']);
		}
		
	}
	
	//echo "<pre>";print_r($user_info);
    $smarty->assign('extend_info_list', $extend_info_list);
    $smarty->assign('profile', $user_info);
    $smarty->display('profile.dwt');
}

/* 修改个人资料的处理 */
elseif ($act == 'act_edit_profile')
{
	
    if(!$_SESSION['user_id']){
            $smarty->assign('footer', get_footer());
            $smarty->display('login.dwt');
            exit;
    }
  	$is_weixin = isset($_POST['is_weixin']) ? trim($_POST['is_weixin']) : '';
  	$_POST['is_weixin']=1;
    include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
    $user_obj = new User;
	$r = $user_obj->editProfile();

	if($r)
	{
		if($_POST['apply_check']==1)
		{
			die(json_encode(array('code'=>0,'msg'=>$_LANG['submit_check_success'])));
		}else{
			die(json_encode(array('code'=>0,'msg'=>$_LANG['edit_profile_success'])));
		}
	}
	else
	{
		$msg = $_LANG['edit_profile_failed'];
		die(json_encode(array('code'=>0,'msg'=>$_LANG['edit_profile_failed'])));
	}
}

/* 修改会员密码 */
elseif ($act == 'act_edit_password')
{
    if(!$_SESSION['user_id']){
            $smarty->assign('footer', get_footer());
            $smarty->display('login.dwt');
            exit;
    }
    include_once(ROOT_PATH . 'includes/lib_passport.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
    $user_obj = new User;
	$res = $user_obj->editPassword();	
	exit;
}

/* 退出会员中心 */
elseif ($act == 'logout')
{
	if (!isset($back_act) && isset($GLOBALS['_SERVER']['HTTP_REFERER']))
	{
		$back_act = strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'user.php') ? './index.php' : $GLOBALS['_SERVER']['HTTP_REFERER'];
	}
	
	//add yes123 2015-01-13退出登录，清空users表中的open_id
	$sql = 'UPDATE ' . $ecs->table('users_c') . ' SET openid=0 WHERE user_id='.$_SESSION['user_id'];   
	$db->query($sql);
	
	$user->logout();
	$Loaction = 'index.php';
	ecs_header("Location: $Loaction\n");

}
/* 显示会员注册界面 */
elseif ($act == 'register')
{
	if (!isset($back_act) && isset($GLOBALS['_SERVER']['HTTP_REFERER']))
	{
		$back_act = strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'user.php') ? './index.php' : $GLOBALS['_SERVER']['HTTP_REFERER'];
	}
	//
	if($_SESSION['user_id'] > 0){
		echo '<meta http-equiv="refresh" content="0;URL='.$back_act.'" />';
		exit;
	}
	/* 取出注册扩展字段 */
	$sql = 'SELECT * FROM ' . $ecs->table('reg_fields') . ' WHERE type < 2 AND display = 1 ORDER BY dis_order, id';
	$extend_info_list = $db->getAll($sql);
	$smarty->assign('extend_info_list', $extend_info_list);
	/* 密码找回问题 */
	$_LANG['passwd_questions']['friend_birthday'] = '我最好朋友的生日？';
	$_LANG['passwd_questions']['old_address']	 = '我儿时居住地的地址？';
	$_LANG['passwd_questions']['motto']		   = '我的座右铭是？';
	$_LANG['passwd_questions']['favorite_movie']  = '我最喜爱的电影？';
	$_LANG['passwd_questions']['favorite_song']   = '我最喜爱的歌曲？';
	$_LANG['passwd_questions']['favorite_food']   = '我最喜爱的食物？';
	$_LANG['passwd_questions']['interest']		= '我最大的爱好？';
	$_LANG['passwd_questions']['favorite_novel']  = '我最喜欢的小说？';
	$_LANG['passwd_questions']['favorite_equipe'] = '我最喜欢的运动队？';
	/* 密码提示问题 */
	$smarty->assign('passwd_questions', $_LANG['passwd_questions']);
	$smarty->assign('footer', get_footer());
	$smarty->display('user_passport.dwt');
}
/* 注册会员的处理 */
elseif ($act == 'act_register')
{
		include_once(ROOT_PATH . 'includes/lib_passport.php');

		$username = isset($_POST['username']) ? trim($_POST['username']) : '';
		$password = isset($_POST['password']) ? trim($_POST['password']) : '';
		$email	= isset($_POST['email']) ? trim($_POST['email']) : '';
		$other['msn'] = isset($_POST['extend_field1']) ? $_POST['extend_field1'] : '';
		$other['qq'] = isset($_POST['extend_field2']) ? $_POST['extend_field2'] : '';
		$other['office_phone'] = isset($_POST['extend_field3']) ? $_POST['extend_field3'] : '';
		$other['home_phone'] = isset($_POST['extend_field4']) ? $_POST['extend_field4'] : '';
		$other['mobile_phone'] = isset($_POST['extend_field5']) ? $_POST['extend_field5'] : '';
		$sel_question = empty($_POST['sel_question']) ? '' : compile_str($_POST['sel_question']);
		$passwd_answer = isset($_POST['passwd_answer']) ? compile_str(trim($_POST['passwd_answer'])) : '';

		$back_act = isset($_POST['back_act']) ? trim($_POST['back_act']) : '';

		if (m_register($username, $password, $email, $other) !== false)
		{
			/*把新注册用户的扩展信息插入数据库*/
			$sql = 'SELECT id FROM ' . $ecs->table('reg_fields') . ' WHERE type = 0 AND display = 1 ORDER BY dis_order, id';   //读出所有自定义扩展字段的id
			$fields_arr = $db->getAll($sql);

			$extend_field_str = '';	//生成扩展字段的内容字符串
			foreach ($fields_arr AS $val)
			{
				$extend_field_index = 'extend_field' . $val['id'];
				if(!empty($_POST[$extend_field_index]))
				{
					$temp_field_content = strlen($_POST[$extend_field_index]) > 100 ? mb_substr($_POST[$extend_field_index], 0, 99) : $_POST[$extend_field_index];
					$extend_field_str .= " ('" . $_SESSION['user_id'] . "', '" . $val['id'] . "', '" . compile_str($temp_field_content) . "'),";
				}
			}
			$extend_field_str = substr($extend_field_str, 0, -1);

			if ($extend_field_str)	  //插入注册扩展数据
			{
				$sql = 'INSERT INTO '. $ecs->table('reg_extend_info') . ' (`user_id`, `reg_field_id`, `content`) VALUES' . $extend_field_str;
				$db->query($sql);
			}

			/* 写入密码提示问题和答案 */
			if (!empty($passwd_answer) && !empty($sel_question))
			{
				$sql = 'UPDATE ' . $ecs->table('users') . " SET `passwd_question`='$sel_question', `passwd_answer`='$passwd_answer'  WHERE `user_id`='" . $_SESSION['user_id'] . "'";
				$db->query($sql);
			}

			$ucdata = empty($user->ucdata)? "" : $user->ucdata;
			$Loaction = 'index.php';
			ecs_header("Location: $Loaction\n");
		}
}
/* add by yes123 2014-11-28 通过短信验证码修改修改会员密码 */
elseif ($act == 'reset_password')
{
	 $smarty->display('reset_password.dwt');
}
/* add by yes123 2014-11-28 通过短信验证码修改修改会员密码 */
elseif ($act == 'act_reset_password')
{
	
    include_once(ROOT_PATH . 'includes/lib_passport.php');
    $new_password = trim($_POST['new_password']);
    $mobile_phone      = trim($_POST['mobile_phone']);
    $checkCode      = trim($_POST['checkCode']);
   	$client =  isset($_SESSION['client'])?$_SESSION['client']:null;
	$msg="";
	if($client)
	{
		//验证用户填写的验证码和服务端发送的是否一致
		if($client['code']!=$checkCode)
		{
			$msg="短信动态验证码有误！";
		}
		
		//验证用户填写的手机号码和服务端发送的是否一致
		if($client['mobile_phone']!=$mobile_phone)
		{
			$msg="手机号码有误！";
		}
		
		
	}
	else
	{
		ss_log("error:update password  SESSION[phone_code] is null ");
		$msg="抱歉,出现未知错误,请重新获取验证码!";
	}


    if (strlen($new_password) < 6)
    {
        $msg="密码长度不能小于6位！";
    }
    
    if($msg){
    	die(json_encode(array('code'=>1,'msg'=>$msg)));
    }
    
    $sql="SELECT user_id FROM ".$ecs->table('users_c'). " WHERE mobile_phone= '".$mobile_phone."'";
    ss_log('通过手机号码获取user_id sql:'.$sql);
	$user_id = $db->getOne($sql);
    if($user_id)
    {
		$sql="UPDATE ".$ecs->table('users_c'). "SET `ec_salt`='0',password = '".md5($new_password)."' WHERE user_id=".$user_id;
		ss_log('修改密码sql:'.$sql);
		$db->query($sql);
		$_SESSION['client']=null;
	    die(json_encode(array('code'=>0,'msg'=>'更改成功')));	
    }

}
elseif($act == 'download_policy_doc')
{
	$user_agent = $_SERVER["HTTP_USER_AGENT"];
	$policy_id = $_GET['policy_id'];
	if(strstr($user_agent,'MicroMessenger')){//来自微信浏览器
		$smarty->assign('footer', get_footer());
		$smarty->display('downpdf.dwt');
	}else{
		$r_download_token = isset ($_REQUEST['download_token']) ?trim($_REQUEST['download_token']): '';
		//校验token
		$download_token = $db->getOne("SELECT download_token FROM t_insurance_policy WHERE policy_id=".$policy_id);
		ss_log("r_download_token:".$r_download_token);
		ss_log("download_token:".$download_token);
		if($r_download_token == $download_token){
			$agent_uid = $_REQUEST['uid'];
			$_SESSION['user_id']=$agent_uid;
			//清空token
			$sql = " UPDATE t_insurance_policy SET download_token=0 WHERE policy_id=".$policy_id;   
			$db->query($sql);
			$url="../baoxian/cp.php?ac=product_buy&policy_id=$policy_id&op=getpolicyfile";
			header("Location: $url");  
		}else{
			echo "<script type=text/javascript>alert('链接过期，请重下！');location.href='user.php?act=policy_detail&policy_id=$policy_id';</script>";
			exit;
		}
	}
}
else
{
	$c_openid = isset($_SESSION['c_openid'])?$_SESSION['c_openid']:"";
	if(!$_SESSION['user_id'] && !$c_openid ){
		ss_log("client go login.dwt:c_openid:".$c_openid);
            $smarty->assign('footer', get_footer());
            $smarty->display('login.dwt');
            exit;
    }
    
    if(!$_SESSION['user_id'] && $c_openid){
    	getDbUserInfo($c_openid);
    }
    
	
	$user_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
	$login_type = isset($_SESSION['login_type'])?$_SESSION['login_type']:"";
	
	if($user_id && $login_type=='c')
	{
		$sql = "SELECT order_id,user_id FROM " . $GLOBALS['ecs']->table('order_info') . " WHERE client_id = '$user_id' ORDER BY order_id desc limit 1";
		
		$order_info = $GLOBALS['db']->getRow($sql);
		$id = isset($order_info['order_id'])?$order_info['order_id']:0;
		if($id){
			include_once(ROOT_PATH . 'includes/lib_transaction.php');
			include_once(ROOT_PATH . 'includes/lib_payment.php');
			include_once(ROOT_PATH . 'includes/lib_order.php');
			include_once(ROOT_PATH . 'includes/lib_clips.php');
			/* 订单详情 */
			$order = get_order_detail($id, $_SESSION['user_id']);
			
			//echo "<pre>";print_r($order);
		        //print_a($order);
			if ($order === false)
			{
				exit("对不起，该订单不存在");
			}
			require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/user.php');
			/* 订单商品 */
			$goods_list = order_goods2($id);
			if (empty($goods_list))
			{
				$tips = '<br><br>无效错误订单<br><br><a href=user.php?act=order_list class=red>返回我的订单</a>';
				$smarty->assign('tips', $tips);
				$smarty->display('order_done.dwt');
				exit();
			}
			foreach ($goods_list AS $key => $value)
			{
				$goods_list[$key]['market_price'] = price_format($value['market_price'], false);
				$goods_list[$key]['goods_price']  = price_format($value['goods_price'], false);
				$goods_list[$key]['subtotal']	 = price_format($value['subtotal'], false);
			}
		
			/* 订单 支付 配送 状态语言项 */
			$order['order_status'] = $_LANG['os'][$order['order_status']];
			$order['pay_status'] = $_LANG['ps'][$order['pay_status']];
			$order['shipping_status'] = $_LANG['ss'][$order['shipping_status']];
			$smarty->assign('order',	  $order); 
			$smarty->assign('goods_list', $goods_list);
			$smarty->assign('lang',	   $_LANG);
			$smarty->assign('footer', get_footer());
			
			
			//获取保单
			$sql = "SELECT * FROM t_insurance_policy WHERE order_id = '$id'";
			$policy = $GLOBALS['db']->getRow($sql);
			$smarty->assign('policy',	   $policy);
			
			$download_token = time();
			$sql = " UPDATE t_insurance_policy SET download_token=".$download_token." WHERE policy_id='$policy[policy_id]'";   
			ss_log("update download_token:".$sql);
			$db->query($sql);
			$smarty->assign('download_token', $download_token);
			
			
			//获取保险顾问联系方式
			$sql = "SELECT * FROM bx_users WHERE user_id = '$order_info[user_id]'";
			$agent = $db->getRow($sql);
			
			$agent['real_name_str']="工号：000".$order_info['user_id'];
			$smarty->assign('agent', $agent);
		}
		
		 $smarty->display('user_menu.dwt');
	}
	else
	{
         $smarty->display('login.dwt');
	}
   
}


/**
 * 用户中心显示
 */
function show_user_center()
{
    include_once(ROOT_PATH .'includes/lib_clips.php');
    
	$best_goods = get_recommend_goods('best');
	if (count($best_goods) > 0)
	{
		foreach  ($best_goods as $key => $best_data)
		{
			$best_goods[$key]['shop_price'] = encode_output($best_data['shop_price']);
			$best_goods[$key]['name'] = encode_output($best_data['name']);
		}
	}
        
	//22:18 2013-7-16
	$rank_name = $GLOBALS['db']->getOne('SELECT rank_name FROM ' . $GLOBALS['ecs']->table('user_rank') . ' WHERE rank_id = '.$_SESSION['user_rank']);
	$GLOBALS['smarty']->assign('info', get_user_default($_SESSION['user_id']));
	$GLOBALS['smarty']->assign('rank_name', $rank_name);
        
	$GLOBALS['smarty']->assign('user_info', get_user_info());
	$GLOBALS['smarty']->assign('best_goods' , $best_goods);
	$GLOBALS['smarty']->assign('footer', get_footer());
	$GLOBALS['smarty']->display('user.dwt');
}

//add yes123 2014-16 更新openid
function updateOPenId($user_id,$openid) {
	ss_log('updata user openid ..  user_id:'.$user_id.',openid:'.$openid);
	if($openid)
	{
		$sql = "UPDATE " . $GLOBALS['ecs']->table('users') ." SET openid='$openid'   WHERE user_id = '$user_id'";
     	return $GLOBALS['db']->query($sql);
	}
}

function getDbUserInfo($openid) {
	ss_log("client login by openid:".$openid);
    $sql = "SELECT * FROM" . $GLOBALS['ecs']->table('users_c') .
            " WHERE c_openid = '" . $openid . "'";
    $db_user =  $GLOBALS['db']->getRow($sql);
	$_SESSION['user_id'] = $db_user['user_id'];
	$_SESSION['user_name'] = $db_user['user_name'];    
	$_SESSION['real_name'] = $db_user['real_name']; 
	$_SESSION['b_openid'] = $db_user['openid']; 
	$_SESSION['c_openid'] = $db_user['c_openid']; 
	$_SESSION['login_type'] = "c"; 
	return $db_user;
}
?>