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
include_once(ROOT_PATH . 'includes/lib_user.php');


if ($_SESSION['user_id'] > 0)
{
	$smarty->assign('user_name', $_SESSION['user_name']);
}
//start add by wangcya, 20150130
else
{
	$user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
	$user_name = isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : '';
	if($user_id)
	{
	   $_SESSION['user_id'] = $user_id;
	   $_SESSION['user_name'] = $user_name;
	   
	}
	
	//add app's session by dingchaoyang
	include_once (ROOT_PATH . 'api/EBaoApp/eba_sessionManager.class.php');
	Eba_SessionManager::setSession();
	//end
}
//end add by wangcya, 20150130

$act = isset($_REQUEST['act']) ? $_REQUEST['act'] : '';
$user_id = $_SESSION['user_id'];

$act_name = array('order_list'=>"订单列表","policy_list"=>"保单列表","phone_code"=>"获取验证码"); 
if(isset($act_name[$act]))
{
	$smarty->assign('module_title', $act_name[$act]);
}

/* 用户登陆 */
if ($act == 'do_login')
{
	$b_openid  = isset($_SESSION['b_openid'])?$_SESSION['b_openid']:"";
	ss_log('do_login  open_id:'.$b_openid);
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
		/* 只让wcyht234 18330297153 登陆 start*/
	/*		$sql = "select user_name from bx_users where user_name = '$user_name'";
                $user_id = $GLOBALS['db']->getOne($sql);
			
                $array = array('wcyht234','18330297153','15901587269','13699207982','test123');
				
                if(!in_array($user_id,$array)){
                    ecs_header("Location:index.php\n");exit;
                }*/
                /* baohongzhou 14-12-8 下午2:26 end */
		if ($user->check_user($user_name, $pwd) > 0)
		{	
			$user->set_session($user_name);
			$user->set_cookie($user_name);
			ss_log('mobile check user_id:'.$_SESSION['user_id'].',b_openid:'.$b_openid.",login_type:".$_SESSION['login_type']);
			updateOPenId($_SESSION['user_id'],$b_openid);
			
			update_user_info();
           	recalculate_price();
           	
            set_config();         
           	
           	//start add by wangcya, 2015-01-31 , for bug[238],增加会议记录
           	$activity_id = intval($_POST['activity_id']);//add by wangcya, 2015-01-31 , for bug[238]
           	if($activity_id)
           	{
           		$_SESSION['activity_id'] = $activity_id;
           		$uid = $_SESSION['user_id'];
           	
           		include_once(ROOT_PATH . 'includes/class/User.class.php');
           		$user_obj = new User;
           		$user_obj->add_user_activity($activity_id,$uid);
           			
           	}
           	//end add by wangcya, 2015-01-31 , for bug[238],增加会议记录
                
			//优化登陆跳转
			if($gourl){
				ecs_header("Location:$gourl\n");
				exit;
			}else{
				$sql = "SELECT COUNT(*) FROM " . $ecs->table('cart') . " WHERE session_id = '" . SESS_ID . "' " . "AND parent_id = 0 AND is_gift = 0 AND rec_type = 0";
				if ($db->getOne($sql) > 0){
					ecs_header("Location:index.php\n");
					exit;
				}			
				else
				{
					ecs_header("Location:index.php\n");
					exit;
				}
			}
		}
		else
		{
			$login_faild = 1;
		}
	}

	$smarty->assign('login_faild', $login_faild);
	$smarty->display('login.dwt');
	exit;
}

/* 微信用户自动登陆 */
elseif ($act == 'weixin_login')
{
	$user_name = !empty($_REQUEST['username']) ? $_GET['username'] : '';
	$pwd = !empty($_GET['pwd']) ? $_GET['pwd'] : '';
	$gourl = !empty($_GET['gourl']) ? $_GET['gourl'] : '';
	
	$remember = isset($_GET['remember']) ? $_GET['remember'] : 0;
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
		if ($user->check_user($user_name, $pwd) > 0)
		{
			$user->set_session($user_name);
			$user->set_cookie($user_name);
			update_user_info();
			//优化登陆跳转
			if($gourl){
				ecs_header("Location:$gourl\n");
				exit;
			}else{
				$sql = "SELECT COUNT(*) FROM " . $ecs->table('cart') . " WHERE session_id = '" . SESS_ID . "' " . "AND parent_id = 0 AND is_gift = 0 AND rec_type = 0";
				if ($db->getOne($sql) > 0){
					ecs_header("Location:cart.php\n");
					exit;
				}else{
					ecs_header("Location:user.php\n");
					exit;
				}
			}
		}
		else
		{
			$login_faild = 1;
		}
	}
        
	$smarty->assign('login_faild', $login_faild);
	$smarty->display('login.dwt');
	exit;
}

/* add yes123 2015-01-24 发送短信验证码 */
elseif($act == 'send_phonecode')
{	
	include_once(ROOT_PATH.'sdk_sms.php');
	
}
/* 订单列表 */
elseif ($act == 'order_list')
{ 
	if(!$_SESSION['user_id']){
		$smarty->assign('footer', get_footer());
		$smarty->display('login.dwt');
		exit;
	} 
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
    include_once(ROOT_PATH . 'includes/class/User.class.php');
    $user_obj = new User;
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
}
/* 购物车列表  add by yes123, 2015-05-19*/
elseif ($act == 'cart_list')
{	
	if(!$_SESSION['user_id']){
		$smarty->assign('footer', get_footer());
		$smarty->display('login.dwt');
		exit;
	} 
	
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$_REQUEST['list_type']='cart_list';
	$user_obj = new User;
	$res = $user_obj->getPolicyList();
	$condition = $res['condition'];
	$policy_list = $res['policy_list'];
	$record_count = $res['record_count'];
	$total_premium = $res['total_premium'];
	
	$pagebar = mobilePagebar($record_count,'user.php?act=cart_list',$condition);
	$smarty->assign('pagebar' , $pagebar);
	$total_premium = price_format($total_premium, $change_price = true);
	$smarty->assign('total_premium',  $total_premium);
	$smarty->assign('policy_list', $policy_list);

	$smarty->display('cart_list.dwt');
	
}
/* 移除保单  add by yes123, 2015-05-20*/
elseif ($act == 'remove_policy')
{	
	if(!$_SESSION['user_id']){
		$smarty->assign('footer', get_footer());
		$smarty->display('login.dwt');
		exit;
	} 
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
	$user_obj = new User;
	$policy_ids = isset($_GET['policy_ids'])?$_GET['policy_ids']:0;
	$res = $user_obj->removePolicy($policy_ids,$user_id);
	die(json_encode($res));
}
/* 会员通过账目明细列表进行再付款的操作 */
elseif ($act == 'pay')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');
    include_once(ROOT_PATH . 'includes/lib_payment.php');
    include_once(ROOT_PATH . 'includes/lib_order.php');

    //变量初始化
    $surplus_id = isset($_GET['id'])  ? intval($_GET['id'])  : 0;
    $payment_id = isset($_GET['pid']) ? intval($_GET['pid']) : 0;

    if ($surplus_id == 0)
    {
        ecs_header("Location: user.php?act=account_log\n");
        exit;
    }

    //如果原来的支付方式已禁用或者已删除, 重新选择支付方式
    if ($payment_id == 0)
    {
        ecs_header("Location: user.php?act=account_deposit&id=".$surplus_id."\n");
        exit;
    }

    //获取单条会员账目信息
    $order = array();
    $order = get_surplus_info($surplus_id);
	
    //支付方式的信息
    $payment_info = array();
    $payment_info = payment_info($payment_id);

    /* 如果当前支付方式没有被禁用，进行支付的操作 */
    if (!empty($payment_info))
    {
        //取得支付信息，生成支付代码
        $payment = unserialize_config($payment_info['pay_config']);

        //生成伪订单号
        $order['order_sn'] = $surplus_id;

        //获取需要支付的log_id
        $order['log_id'] = get_paylog_id($surplus_id, $pay_type = PAY_SURPLUS);

        $order['user_name']      = $_SESSION['user_name'];
        $order['surplus_amount'] = $order['amount'];

        //计算支付手续费用
        $payment_info['pay_fee'] = pay_fee($payment_id, $order['surplus_amount'], 0);

        //计算此次预付款需要支付的总金额
        $order['order_amount']   = $order['surplus_amount'] + $payment_info['pay_fee'];

        //如果支付费用改变了，也要相应的更改pay_log表的order_amount
        $order_amount = $db->getOne("SELECT order_amount FROM " .$ecs->table('pay_log')." WHERE log_id = '$order[log_id]'");
        if ($order_amount <> $order['order_amount'])
        {
            $db->query("UPDATE " .$ecs->table('pay_log').
                       " SET order_amount = '$order[order_amount]' WHERE log_id = '$order[log_id]'");
        }

        /* 调用相应的支付方式文件 */
        include_once(ROOT_PATH . 'includes/modules/payment/' . $payment_info['pay_code'] . '.php');

        /* 取得在线支付方式的支付按钮 */
        $pay_obj = new $payment_info['pay_code'];
        $pay_button = $pay_obj->get_code($order, $payment);
		$payment_info['pay_button'] =str_replace('20px','40px',$pay_button);
        /* 模板赋值 */
        $smarty->assign('payment', $payment_info);
        $smarty->assign('order',   $order);
        $smarty->assign('pay_fee', price_format($payment_info['pay_fee'], false));
        $smarty->assign('amount',  price_format($order['surplus_amount'], false));
        $smarty->assign('act',  'act_account');
        $smarty->display('account.dwt');
    }
    /* 重新选择支付方式 */
    else
    {
        include_once(ROOT_PATH . 'includes/lib_clips.php');

        $smarty->assign('payment', get_online_payment_list());
        $smarty->assign('order',   $order);
        $smarty->assign('action',  'account_deposit');
        $smarty->display('account.dwt');
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

	//如果未支付，并且支付方式不是微信支付，获取微信支付ID，给用户修改支付方式
	if(!$order['pay_status']) 
	{
		$sql = "SELECT pay_id FROM ".$ecs->table('payment')." WHERE pay_code='weixinpay'";
		$smarty->assign('pay_id',	   $db->getOne($sql));
	}
	
	
	$_REQUEST['from'] = "order_info";
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
	$res = $user_obj->getPolicyList();
	//echo "<pre>";print_r($res);exit;

	$warranty = $res['policy_list'];
	
	foreach ( $warranty as $pkey => $policy ) {
       if($policy['insurer_code']=='SSBQ')
       {
       		$order['type'] = 'third';
       		$order['insurer_code'] = 'SSBQ';
       		break;
       }
	}
	$smarty->assign('warranty', $warranty);
	
	
	if(count($warranty)==1)
	{
		$policy_id = $warranty[0]['policy_id'];
	   	require_once(ROOT_PATH . 'baoxian/common.php');
	   	require_once(ROOT_PATH . 'baoxian/source/function_baoxian.php');
	   	global $attr_status;
	   	if($policy_id)
		{
			$ret = get_policy_info_view($policy_id,$smarty);
			//echo "<pre>";print_r($ret);exit;
			$policy = $ret['policy_arr'];
			if($policy['policy_status'])
			{
				$policy['policy_status_str'] = $attr_status[$policy['policy_status']];
			}
			
			$user_info_applicant = $ret['user_info_applicant'];
			$list_subject = $ret['list_subject'];//多层级一个链表
			$smarty->assign('policy_info', $policy);
			$smarty->assign('user_info_applicant', $user_info_applicant);
			$smarty->assign('list_subject', $list_subject);
			//add by dingchaoyang 2015-3-31
			$smarty->assign('platformId',isset($_REQUEST['platformId'])?$_REQUEST['platformId']:'');
			$smarty->assign('uid',isset($_REQUEST['uid'])?$_REQUEST['uid']:'');
			//end
			
			//获取商品信息
			$attribute_id = $policy['attribute_id'];
			$sql = " SELECT * FROM bx_goods WHERE tid='$attribute_id'";   
			$goods = $db->getRow($sql);
			$smarty->assign('goods', $goods);
			
		}       

		
	}
	
	
	//获取上传文件需要用到的参数
	require_once ROOT_PATH."mobile/includes/class/JSSDK.php";
	$jssdk = new JSSDK(APPID, APPSECRET);
	$signPackage = $jssdk->GetSignPackage();
	ss_log("signPackage:".print_r($signPackage,true));
	$smarty->assign('signPackage', $signPackage);
	
	
	/* 订单 支付 配送 状态语言项 */
	$order['order_status'] = $_LANG['os'][$order['order_status']];
	$order['pay_status'] = $_LANG['ps'][$order['pay_status']];
	$order['shipping_status'] = $_LANG['ss'][$order['shipping_status']];
	$smarty->assign('order',	  $order); 
	$smarty->assign('goods_list', $goods_list);
	$smarty->assign('lang',	   $_LANG);
	$smarty->assign('footer', get_footer());
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
	if(!$_SESSION['user_id']){
		$smarty->assign('footer', get_footer());
		$smarty->display('login.dwt');
		exit;
	}
	
   $policy_id = isset($_REQUEST['policy_id']) ? intval($_REQUEST['policy_id']) : 1;
  
   require_once(ROOT_PATH . 'baoxian/common.php');
   require_once(ROOT_PATH . 'baoxian/source/function_baoxian.php');
   global $attr_status;
   if($policy_id)
	{
		$ret = get_policy_info_view($policy_id,$smarty);
		$policy = $ret['policy_arr'];
		if($policy['policy_status'])
		{
			$policy['policy_status_str'] = $attr_status[$policy['policy_status']];
		}
		
		$user_info_applicant = $ret['user_info_applicant'];
		$list_subject = $ret['list_subject'];//多层级一个链表
		$smarty->assign('order', $policy);
		$smarty->assign('user_info_applicant', $user_info_applicant);
		$smarty->assign('list_subject', $list_subject);
		//add by dingchaoyang 2015-3-31
		$smarty->assign('platformId',isset($_REQUEST['platformId'])?$_REQUEST['platformId']:'');
		$smarty->assign('uid',isset($_REQUEST['uid'])?$_REQUEST['uid']:'');
		//end
		
		//获取商品信息
		$attribute_id = $policy['attribute_id'];
		$sql = " SELECT * FROM bx_goods WHERE tid='$attribute_id'";   
		$goods = $db->getRow($sql);
		$smarty->assign('goods', $goods);
		
	}       
   $smarty->display('policy_info.dwt');
}
/* 生成下载电子保单token */
elseif($act == 'get_download_policy_token'){
	if(!$_SESSION['user_id']){
		die(json_encode(array('code'=>1,'msg'=>'请先登录')));
	}
	
	$policy_id = $_GET['policy_id'];
	$download_token = time();
	$sql = " UPDATE t_insurance_policy SET download_token=".$download_token." WHERE policy_id=".$policy_id;   
	if($db->query($sql))
	{
		die(json_encode(array('code'=>0,'msg'=>'ok','token'=>$download_token)));
	}
	
	exit;
	
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

/* 补充资料 */
else if($act=='upload_policy_attachment')
{
	
	
	include_once(ROOT_PATH . 'baoxian/source/function_common.php');
	include_once(ROOT_PATH . 'baoxian/common.php');
	include_once(ROOT_PATH . 'baoxian/source/function_baoxian_ssbq.php');
	$policy_id = isset($_REQUEST['policy_id'])?intval($_REQUEST['policy_id']):0;
	$order_id = isset($_REQUEST['order_id'])?intval($_REQUEST['order_id']):0;
	
	if(!$policy_id)
	{
		 show_message("错误，请重试！");
	}
	process_upload_file(array('policy_id'=>$policy_id),'insurance_policy');

	$url = "user.php?act=order_detail&order_id=$order_id";
	header("location: $url");
	
	exit;
}

/* 个人资料页面 */
elseif ($act == 'profile')
{
	ss_log("into weixin profie");
	
    if(!$_SESSION['user_id'])
    {
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
		if(isset($r['province_name_my']))
		{
			$smarty->assign('province_name_my', $r['province_name_my']);
		}
		
		if(isset($r['city_name_my']))
		{
			$smarty->assign('city_name_my', $r['city_name_my']);
		}
		
		if(isset($r['district_name_my']))
		{
			$smarty->assign('district_name_my', $r['district_name_my']);
		}
		
	}
	else
	{
		if(isset($r['province_list'])){
			$smarty->assign('province_list', $r['province_list']);
		}
		if(isset($r['city_list'])){
			$smarty->assign('city_list', $r['city_list']);
		}
		if(isset($r['district_list'])){
			$smarty->assign('district_list', $r['district_list']);
		}
		
	}

    $smarty->assign('extend_info_list', $extend_info_list);
    $smarty->assign('profile', $user_info);
    
    ss_log("进入普通的个人资料页面");
    $smarty->display('profile.dwt');
    
}
/* 修改个人资料的处理 */
elseif ($act == 'act_edit_profile')
{//可能不用了。
	
    if(!$_SESSION['user_id'])
    {
            $smarty->assign('footer', get_footer());
            $smarty->display('login.dwt');
            exit;
    }
    
  	$is_weixin = isset($_POST['is_weixin']) ? trim($_POST['is_weixin']) : '';
  	$_POST['is_weixin']=1;
    include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
   
    $user_obj = new User;
    
   	ss_log("进入活动普通用户资料编辑");
    	
	$r = $user_obj->editProfile();
  	
	if($r)
	{
		
		if($_POST['check_status']==PENDING_CHECK_STATUS)
		{
			ss_log('微信端提交审核成功');
			die(json_encode(array('code'=>0,'msg'=>$_LANG['submit_check_success'])));
		}
		else
		{
			ss_log('微信端保存个人信息成功');
			die(json_encode(array('code'=>0,'msg'=>$_LANG['edit_profile_success'])));
		}
		
	}
	else
	{
		ss_log('微信端'.$msg);
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
	$sql = 'UPDATE ' . $ecs->table('users') . ' SET openid=0 WHERE user_id='.$_SESSION['user_id'];   
	//add yes123 2015-02-07退出登录后，清空openid
	ss_log('退出登录后，清空openid：'.$sql);
	$_SESSION['openid']=0;
	$db->query($sql);
	
	$user->logout();
	$Loaction = 'index.php';
	ecs_header("Location: $Loaction\n");

}
/* 显示会员注册界面 */
elseif ($act == 'register')
{
	global $user_type_list;
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
/* 注册会员的注册动作处理 */
elseif ($act == 'act_register')
{
	include_once(ROOT_PATH . 'includes/lib_passport.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
    $user_obj = new User;
	$res = $user_obj->userRegister();	
	exit;
		
}
//start add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能,可以在这个地方做跳转
elseif ($act == 'act_register_meeting')
{
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
	$res = $user_obj->registerMeeting();
	exit;

}
//added by zhangxi,20150417, 增加专题的注册通道处理

//end add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能,可以在这个地方做跳转
/* 增加收货地址 */
elseif ($act == 'add_address')
{
	include_once('includes/lib_transaction.php');        
	 /* 取得国家列表、商店所在国家、商店所在国家的省列表 */
	$smarty->assign('country_list',       get_regions());
	$smarty->assign('shop_country',       $_CFG['shop_country']);
	$smarty->assign('shop_province_list', get_regions(1, $_CFG['shop_country']));
    $consignee_list = get_consignee_list($_SESSION['user_id']);
	/* 取得每个收货地址的省市区列表 */
	$province_list = array();
	$city_list = array();
	$district_list = array();
	foreach ($consignee_list as $region_id => $consignee)
	{
		$consignee['country']  = isset($consignee['country'])  ? intval($consignee['country'])  : 0;
		$consignee['province'] = isset($consignee['province']) ? intval($consignee['province']) : 0;
		$consignee['city']     = isset($consignee['city'])     ? intval($consignee['city'])     : 0;

		$province_list = get_regions(1, $consignee['country']);
		$city_list     = get_regions(2, $consignee['province']);
		$district_list = get_regions(3, $consignee['city']);
	}
	$smarty->assign('province_list', $province_list);
	$smarty->assign('city_list',     $city_list);
	$smarty->assign('district_list', $district_list);	
	$smarty->assign('action', "user.php?act=add_edit_address");
	$smarty->assign('fun', 'add');
	$smarty->display('address_list.dwt');
}
/* 收货地址列表 */
elseif ($act == 'address_list')
{
	include_once('includes/lib_transaction.php');        
	 /* 取得国家列表、商店所在国家、商店所在国家的省列表 */
	$smarty->assign('country_list',       get_regions());
	$smarty->assign('shop_country',       $_CFG['shop_country']);
	$smarty->assign('shop_province_list', get_regions(1, $_CFG['shop_country']));
    $consignee_list = get_consignee_list($_SESSION['user_id']);
	/* 取得每个收货地址的省市区列表 */
	$province_list = array();
	$city_list = array();
	$district_list = array();
	foreach ($consignee_list as $region_id => $consignee)
	{
		$consignee['country']  = isset($consignee['country'])  ? intval($consignee['country'])  : 0;
		$consignee['province'] = isset($consignee['province']) ? intval($consignee['province']) : 0;
		$consignee['city']     = isset($consignee['city'])     ? intval($consignee['city'])     : 0;

		$province_list = get_regions(1, $consignee['country']);
		$city_list     = get_regions(2, $consignee['province']);
		$district_list = get_regions(3, $consignee['city']);
	}
	$smarty->assign('province_list', $province_list);
	$smarty->assign('city_list',     $city_list);
	$smarty->assign('district_list', $district_list);	
	$smarty->assign('consignee', $consignee_list);
        $smarty->assign('action', "user.php?act=act_edit_address");
        $smarty->assign('subval', '修改送货地址');
        $smarty->assign('fun', 'list');
        $smarty->display('address_list.dwt');
}
/*更新收获地址*/
elseif ($act == 'act_edit_address'){
	
	global $db;
	include_once('includes/lib_transaction.php');
	
	if(empty($_POST['country']) || empty($_POST['province']) || empty($_POST['city']) || empty($_POST['district']))
    {
        echo '<script language=javascript>alert("配送区域不可为空！");history.go(-1);</script>';
        exit;
    }
    if(empty($_POST['address']))
    {
        echo '<script language=javascript>alert("收货地址不可为空！");history.go(-1);</script>';
        exit;
    }
	if(empty($_POST['consignee']))
    {
        echo '<script language=javascript>alert("收货人姓名不可为空！");history.go(-1);</script>';
        exit;
    }
    if(empty($_POST['tel']))
    {
        echo '<script language=javascript>alert("联系电话不可为空！");history.go(-1);</script>';
        exit;
    }
    if(empty($_POST['mobile']))
    {
        echo '<script language=javascript>alert("联系手机不可为空！");history.go(-1);</script>';
        exit;
    }
    if(empty($_POST['email']))
    {
        echo '<script language=javascript>alert("电子邮箱不可为空！");history.go(-1);</script>';
        exit;
    }
	/*
	 * 保存收货人信息
	 */
	$consignee = array(
		'user_id'		=> $_SESSION['user_id'],
		'address_id'    => empty($_POST['address_id']) ? 0  : intval($_POST['address_id']),
		'consignee'     => empty($_POST['consignee'])  ? '' : trim($_POST['consignee']),
		'country'       => empty($_POST['country'])    ? '' : $_POST['country'],
		'province'      => empty($_POST['province'])   ? '' : $_POST['province'],
		'city'          => empty($_POST['city'])       ? '' : $_POST['city'],
		'district'      => empty($_POST['district'])   ? '' : $_POST['district'],
		'email'         => empty($_POST['email'])      ? '' : $_POST['email'],
		'address'       => empty($_POST['address'])    ? '' : $_POST['address'],
		'zipcode'       => empty($_POST['zipcode'])    ? '' : make_semiangle(trim($_POST['zipcode'])),
		'tel'           => empty($_POST['tel'])        ? '' : make_semiangle(trim($_POST['tel'])),
		'mobile'        => empty($_POST['mobile'])     ? '' : make_semiangle(trim($_POST['mobile'])),
		'sign_building' => empty($_POST['sign_building']) ? '' : $_POST['sign_building'],
		'best_time'     => empty($_POST['best_time'])  ? '' : $_POST['best_time'],
		'default_id'    => empty($_POST['default_id'])  ? '0' : $_POST['default_id'],
	);
	
	$result = update_address($consignee);
	
	$GLOBALS['db']->query("UPDATE " . $GLOBALS['ecs']->table('user_address') . " SET default_id = 0 WHERE default_id != 1 ");
	
	if($result){
        echo '<script language=javascript>alert("修改收货地址成功");location.href="user.php?act=address_list";</script>';
	}
	else{
        echo '<script language=javascript>alert("修改失败");history.go(-1);</script>';
	}
	if ($_SESSION['user_id'] > 0)
    {
        $smarty->assign('user_name', $_SESSION['user_name']);
    }
}
/*增加收获地址*/
elseif ($act == 'add_edit_address'){
	
	global $db;
	include_once('includes/lib_transaction.php');
	
	if(empty($_POST['country']) || empty($_POST['province']) || empty($_POST['city']) || empty($_POST['district']))
    {
        echo '<script language=javascript>alert("配送区域不可为空！");history.go(-1);</script>';
        exit;
    }
    if(empty($_POST['address']))
    {
        echo '<script language=javascript>alert("收货地址不可为空！");history.go(-1);</script>';
        exit;
    }
	if(empty($_POST['consignee']))
    {
        echo '<script language=javascript>alert("收货人姓名不可为空！");history.go(-1);</script>';
        exit;
    }
    if(empty($_POST['tel']))
    {
        echo '<script language=javascript>alert("联系电话不可为空！");history.go(-1);</script>';
        exit;
    }
    if(empty($_POST['mobile']))
    {
        echo '<script language=javascript>alert("联系手机不可为空！");history.go(-1);</script>';
        exit;
    }
    if(empty($_POST['email']))
    {
        echo '<script language=javascript>alert("电子邮箱不可为空！");history.go(-1);</script>';
        exit;
    }
	/*
	 * 保存收货人信息
	 */
	$consignee = array(
		'user_id'		=> $_SESSION['user_id'],
		'address_id'    => empty($_POST['address_id']) ? 0  : intval($_POST['address_id']),
		'consignee'     => empty($_POST['consignee'])  ? '' : trim($_POST['consignee']),
		'country'       => empty($_POST['country'])    ? '' : $_POST['country'],
		'province'      => empty($_POST['province'])   ? '' : $_POST['province'],
		'city'          => empty($_POST['city'])       ? '' : $_POST['city'],
		'district'      => empty($_POST['district'])   ? '' : $_POST['district'],
		'email'         => empty($_POST['email'])      ? '' : $_POST['email'],
		'address'       => empty($_POST['address'])    ? '' : $_POST['address'],
		'zipcode'       => empty($_POST['zipcode'])    ? '' : make_semiangle(trim($_POST['zipcode'])),
		'tel'           => empty($_POST['tel'])        ? '' : make_semiangle(trim($_POST['tel'])),
		'mobile'        => empty($_POST['mobile'])     ? '' : make_semiangle(trim($_POST['mobile'])),
		'sign_building' => empty($_POST['sign_building']) ? '' : $_POST['sign_building'],
		'best_time'     => empty($_POST['best_time'])  ? '' : $_POST['best_time'],
	);
	
	$result = update_address($consignee);
	if($result){
        echo '<script language=javascript>alert("增加收货地址成功");location.href="user.php?act=address_list";</script>';
	}
	else{
        echo '<script language=javascript>alert("增加收货地址失败");history.go(-1);</script>';
	}
	if ($_SESSION['user_id'] > 0)
    {
        $smarty->assign('user_name', $_SESSION['user_name']);
    }
}
/* 删除收货人信息*/
elseif ($act == 'drop_address')
{
	include_once('includes/lib_transaction.php');

	$consignee_id = intval($_GET['id']);

	if (drop_consignee($consignee_id))
	{
		ecs_header("Location: user.php?act=address_list\n");
		exit;
	}
}
/* 添加收藏商品(ajax) */
elseif ($act == 'collect')
{
	include_once(ROOT_PATH .'includes/cls_json.php');
	$json = new JSON();
	$result = array('error' => 0, 'message' => '');
	$goods_id = $_GET['id'];

	if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == 0)
	{
		$result['error'] = 1;
		$result['message'] = "由于您还没有登录，因此您还不能使用该功能。";
		die($json->encode($result));
	}
	else
	{
		/* 检查是否已经存在于用户的收藏夹 */
		$sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('collect_goods') .
			" WHERE user_id='$_SESSION[user_id]' AND goods_id = '$goods_id'";
		if ($GLOBALS['db']->GetOne($sql) > 0)
		{
			$result['error'] = 1;
			$result['message'] = "该商品已经存在于您的收藏夹中。";
			die($json->encode($result));
		}
		else
		{
			$time = time();
			$sql = "INSERT INTO " .$GLOBALS['ecs']->table('collect_goods'). " (user_id, goods_id, add_time)" .
					"VALUES ('$_SESSION[user_id]', '$goods_id', '$time')";

			if ($GLOBALS['db']->query($sql) === false)
			{
				$result['error'] = 1;
				$result['message'] = $GLOBALS['db']->errorMsg();
				die($json->encode($result));
			}
			else
			{
				$result['error'] = 0;
				$result['message'] = "该商品已经成功地加入了您的收藏夹。";
				die($json->encode($result));
			}
		}
	}
}
/* 已购买 */
elseif($act == 'just_print'){

  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
  $sql = "SELECT order_id FROM " . $ecs->table('order_info'). " WHERE user_id = $user_id";
  $order_id_array = $db->getAll($sql);
  $order_id_str = '';
  for($i=0;$i<count($order_id_array);$i++){
     $order_id_str .= $order_id_array[$i]['order_id'].",";
  }
  $order_id_str = rtrim($order_id_str,',');
  $sql = "SELECT group_concat(DISTINCT og.goods_name) FROM ". $ecs->table('order_goods') . " AS og LEFT JOIN " . $ecs->table('order_info') . " AS oi ON og.order_id = oi.order_id WHERE og.order_id in($order_id_str) GROUP BY og.goods_name";
  $record_count = count($db->getAll($sql));

  if ($record_count > 0){
        $page_num = '5';
        $page = !empty($_GET['page']) ? intval($_GET['page']) : 1;
        $pages = ceil($record_count / $page_num);
        if ($page <= 0)
        {
                $page = 1;
        }
        if ($pages == 0)
        {
                $pages = 1;
        }
        if ($page > $pages)
        {
                $page = $pages;
        }
        $pagebar = get_wap_pager($record_count, $page_num, $page, 'user.php?act=just_print', 'page');
        $smarty->assign('pagebar' , $pagebar);
    }
  if(!empty($order_id_str)){	
	$sql = "SELECT group_concat(DISTINCT og.goods_name), og.goods_name, og.goods_id, oi.order_sn, oi.pay_time, og.product_id FROM ". $ecs->table('order_goods') . " AS og LEFT JOIN " . $ecs->table('order_info') . " AS oi ON og.order_id = oi.order_id WHERE og.order_id in($order_id_str) GROUP BY og.goods_name ORDER BY og.rec_id DESC";
	//$just_print = $db->getAll($sql);
        $res = $GLOBALS['db'] -> selectLimit($sql, $page_num, ($page-1)*$page_num);
        while ($row = $GLOBALS['db']->fetchRow($res)){
          if($row['pay_time'])
          {
	          $row['pay_time'] = date('Y-m-d',$row['pay_time']);   
          }
          
          //add yes123 2015-04-28 获取品牌
          $sql= "SELECT brand_id FROM bx_goods WHERE goods_id='$row[goods_id]'";
          $brand_id = $GLOBALS['db']->getOne($sql);
          if($brand_id)
          {
	          $sql = "SELECT brand_name FROM bx_brand WHERE brand_id=$brand_id ";
          	  $brand_name = $GLOBALS['db']->getOne($sql);
          	  $row['brand_name'] =$brand_name;
          }
          
          
          $just_print[] = $row;
        }
//	foreach($just_print as $key=>$value){
//		$just_print[$key]['pay_time'] = date('Y-m-d', $value['pay_time']);
//	}
  }
  $smarty->assign('just_print_array', $just_print);
  $smarty->display("just_print.dwt");
}
/* 银行账户 */
elseif($act == 'bank_account'){
    
    if (empty($_SESSION['user_id']))
    {
      header("Location:user.php?act=login");
    }
    $smarty->assign('user_id',$_SESSION['user_id']);
    $sql = "SELECT * FROM ". $ecs->table('bank') ."WHERE uid = $_SESSION[user_id]";
    $bankAll=$db->getAll($sql);
    $smarty->assign('bankAll', $bankAll);
    $smarty->display("bank_account.dwt");
}
/* 银行账户修改 */
elseif($act == 'edit_bank'){
     if (empty($_SESSION['user_id']))
    {
      header("Location:user.php?act=login");
    }
    $bid = $_REQUEST['bid'];
    $sql = "SELECT * FROM " . $ecs->table('bank'). " WHERE bid = '$bid'";
    $bank_array = $db->getRow($sql);
    $smarty->assign('banks', $bank_array); 
    $smarty->assign('act','act_edit_bank');
    $smarty->display("edit_bank.dwt");
}
/* 修改账户处理 */
elseif($act == 'act_edit_bank')
{
    $bid = $_POST['bid'];
    $bank_name = isset($_POST['bank_name']) ? $_POST['bank_name'] : '';
    $b_account = isset($_POST['b_account']) ? $_POST['b_account'] : '';
    $sub_branch = isset($_POST['sub_branch']) ? $_POST['sub_branch'] : '';
    $bank_code = isset($_POST['bank_code']) ? $_POST['bank_code'] : '';
    $sql = "UPDATE " . $GLOBALS['ecs']->table('bank') . " SET bank_name = '$bank_name', b_account = '$b_account', sub_branch = '$sub_branch', bank_code = '$bank_code' WHERE bid = '$bid'";
    if($db->query($sql))
    {
      echo '<script language=javascript>alert("账户修改成功！");location.href="user.php?act=bank_account";</script>';
      //show_message('修改成功！','','user.php?act=bank_account');	
    }else{
      echo '<script language=javascript>alert("修改失败！请重新修改...");location.href="user.php?act=edit_bank&bid=$bid";</script>';
      //show_message('修改失败！请重新修改...','',"user.php?act=edit_bank&bid=$bid");
    }
}
/* 删除银行账户 */
elseif($act == 'delete_account')
{
    
   $bid = $_REQUEST['bid']; 
   if($bid){
      $sql = "DELETE FROM " . $GLOBALS['ecs']->table('bank') . " WHERE bid = '$bid'";
	  if($db->query($sql))
	  {
            echo '<script language=javascript>alert("账户删除成功!");location.href="user.php?act=bank_account";</script>';
            //show_message('账户删除成功！', '', 'user.php?act=bank_account');	    
	  }
	  else{
            echo '<script language=javascript>alert("删除账户失败！");location.href="user.php?act=bank_account";</script>';
	    //show_message('删除账户失败！' , '', 'user.php?act=bank_account');
	  }
   }
}
/* 添加账户界面 */
elseif($act == 'add_bank')
{
    
    if (empty($_SESSION['user_id']))
    {
      header("Location:user.php?act=login");
    }
    $smarty->assign('act','add_account');
    $smarty->display("add_account.dwt");
}
/* 账户添加处理 */
elseif($act == 'add_account')
{
    
    if (empty($_SESSION['user_id']))
    {
        header("Location:user.php?act=login");
    }
   
    $bank_code  = $_POST['bank_code'];
    $sql = "SELECT * FROM " . $ecs->table('bank'). " WHERE bank_code = '$bank_code'";
    
    if(count($db->getAll($sql)) > 0){
        echo '<script language=javascript>alert("账号已存在,请检测...");location.href="user.php?act=add_bank";</script>';  
        //show_message('账号已存在,请检测...', '', 'user.php?act=add_bank');
    }else{
            $sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('bank') ." WHERE uid = '$_SESSION[user_id]' ";
            $count = $GLOBALS['db']->getOne($sql);
            if($count >= 3){
                echo '<script language=javascript>alert("最多可添加三个银行账号！");location.href="user.php?act=bank_account";</script>';
            }else{
                /* 获得数据 */
                $bank_name = empty($_POST['bank_name']) ? '' : $_POST['bank_name'];
                if(empty($_POST['rests']))
                {
                   $b_account = $_POST['b_account'];
                }
                else
                {
                   $b_account = $_POST['rests'];
                }
                $sub_branch = $_POST['sub_branch'];
                $userID = $_SESSION['user_id'];
                if($bank_name != null && $b_account != null && $bank_code != null && $sub_branch != null){
                  $sql="INSERT INTO ".$GLOBALS['ecs']->table('bank')." (uid, bank_name, b_account, sub_branch, bank_code) VALUES ($userID, '$bank_name', '$b_account', '$sub_branch', '$bank_code')";
                  $db->query($sql);
                  header("Location:user.php?act=bank_account");
                }else{
                  echo '<script language=javascript>alert("以上信息均不可为空！");location.href="user.php?act=add_bank";</script>';
                  //show_message('以上信息均不可为空！', '', 'user.php?act=add_bank');
                } 
            }
    }
}
/* 推荐界面 */
elseif($act == 'affiliate'){
	if (empty($_SESSION['user_id']))
    {
    	$gourl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    	ss_log("gourl:".$gourl);
    	$smarty->assign('gourl', $gourl);//
    	$smarty->display('login.dwt');
    	exit;
      
    }
	
    require(ROOT_PATH . 'languages/zh_cn/admin/account_log.php');
	//start modify yes123 2014-12-31获取我的推荐数据列表 抽取，，微信端要用
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user = new User;
	$res = $user->getAffiliate();
	$user_list = $res['user_list'];
	$condition = $res['condition'];
	$earnings_total = $res['earnings_total'];
	
	foreach ( $user_list as $key => $value ) {
    	$user_list[$key]['check_status'] = $check_status_list[$value['check_status']];
    	//通过用户名查询此用户带来的服务费
    	$sql = "SELECT sum(user_money) as total FROM " . $ecs->table('account_log') . " WHERE user_id= '".$_SESSION['user_id']."' AND cname= '". $value['user_id'] .
		"'  AND incoming_type='".$_LANG['tui_jian']."'";
    	 $total=$GLOBALS['db']->getOne($sql);
    	 $user_list[$key]['income_total']=$total['total'];
	}
	
    $pager = $res['pager'];
	$record_count = $pager['record_count'];
	//分页函数
	if($record_count){
		$pagebar = mobilePagebar($record_count,'user.php?act=affiliate',$condition);
		$smarty->assign('pagebar' , $pagebar);
	}

    //end modify yes123 2014-12-31获取我的推荐数据列表 抽取，，微信端要用
    $smarty->assign('record_count', $record_count);//
    $smarty->assign('condition', $condition);//
    $smarty->assign('earnings_total', $earnings_total);//收益总计
    $smarty->assign('user_list', $user_list);
    $smarty->assign('shopname', $_CFG['shop_name']);
    $smarty->assign('userid',$_SESSION['user_id']);
    $smarty->assign('shopurl', $ecs->url());
    
    $smarty->display('affiliate.dwt');
}
/* 会议付费的处理 */
//start add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能
/* 个人资料页面 */
elseif ($act == 'profile_meeting')
{
	ss_log("into weixin profie");

	if(!$_SESSION['user_id'])
	{
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

	//start add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能
	$user_id = $_SESSION['user_id'];
	$sql = "SELECT type FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_id = '$user_id'";
	ss_log("$sql");
	$user_type = $GLOBALS['db']->getOne($sql);
	//$user_type = $user_info['type'];
	if($user_type=="meeting")
	{
		ss_log("进入活动的个人资料页面");
		$smarty->display('profile_meeting.dwt');
	}//end add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能
}
elseif ($act == 'act_edit_profile_meeting')
{//可能不用了。
	
	if(!$_SESSION['user_id'])
	{
		$smarty->assign('footer', get_footer());
		$smarty->display('login.dwt');
		exit;
	}
	
	$is_weixin = isset($_POST['is_weixin']) ? trim($_POST['is_weixin']) : '';
	$_POST['is_weixin']=1;
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	
	
	//start add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能,可以在这个地方做跳转
	$user_id = $_SESSION['user_id'];
	$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_id = '$user_id'";
	ss_log("$sql");
	$user_info = $GLOBALS['db']->getRow($sql);
	//end add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能,可以在这个地方做跳转
	$user_type = $user_info['type'];
	$check_status = $user_info['check_status'];
	ss_log("act_edit_profile user_type: ".$user_type);
	
	if($check_status!=CHECKED_CHECK_STATUS &&$is_weixin)//$user_type=="meeting"&&$is_weixin)
	{	
		include_once(ROOT_PATH . 'includes/class/MeetingActive.class.php');
		$ObjMeeting = new MeetingActive;
		
		ss_log("进入活动注册用户资料编辑");
		$r = $ObjMeeting->editProfile_meeting();
		if($r)
		{
			//start add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能,可以在这个地方做跳转
			//////////////////注册成功，则要给该用户成成一个订单支付订单//////////////////////////////////
			$url = "user.php";
			die(json_encode(array('code'=>"meeting",'msg'=>$_LANG['submit_check_success'],'url'=>$url)));
		}
		else
		{
			$msg = $_LANG['edit_profile_failed'];
			die(json_encode(array('code'=>0,'msg'=>$_LANG['edit_profile_failed'])));
		}
	}
	else
	{
		ss_log("正式用户不能修改资料");
	}
}
elseif ($act == 'pay_meeting')
{
	ss_log("into pay_meeting");
	
	/*
	if(!$_SESSION['user_id'])
	{
		$smarty->assign('footer', get_footer());
		$smarty->display('login.dwt');
		exit;
	}
	
	$is_weixin = isset($_POST['is_weixin']) ? trim($_POST['is_weixin']) : '';
	$_POST['is_weixin']=1;
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	
	
	//start add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能,可以在这个地方做跳转
	$user_id = $_SESSION['user_id'];
	$sql = "SELECT type FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_id = '$user_id'";
	ss_log("$sql");
	$user_type = $GLOBALS['db']->getOne($sql);
	//end add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能,可以在这个地方做跳转
	ss_log("act_edit_profile user_type: ".$user_type);
	
	if(1)//&&$is_weixin)//$user_type=="meeting"
	{
		include_once(ROOT_PATH . 'includes/class/MeetingActive.class.php');
		$ObjMeeting = new MeetingActive;
	
		ss_log("进入活动注册用户资料编辑");
		$r = $ObjMeeting->editProfile_meeting();
		
		if($r)
		{
			//start add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能,可以在这个地方做跳转
			//////////////////注册成功，则要给该用户成成一个订单支付订单//////////////////////////////////
			$url = "user.php";
			die(json_encode(array('code'=>"meeting",'msg'=>$_LANG['submit_check_success'],'url'=>$url)));
		}
		else
		{
			$msg = $_LANG['edit_profile_failed'];
			die(json_encode(array('code'=>0,'msg'=>$_LANG['edit_profile_failed'])));
		}
		
	}
	*/
	//////////////////////////////////////////////////////////////////////////
	include_once(ROOT_PATH . 'includes/class/MeetingActive.class.php');
	$ObjMeeting = new MeetingActive;
	
	$user_id = $_SESSION['user_id'];
	$mr_id = isset($_REQUEST['mr_id']) ? $_REQUEST['mr_id'] : '';
	
	ss_log("我的会议的，mr_id: ".$mr_id);
	
	$url = $ObjMeeting->pay_meeting($user_id,$mr_id);

	ss_log($url);
	ecs_header("Location: $url\n");
	exit(0);

}
//end add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能
/* 短信动态验证码 注册 */
elseif($act == 'phone_code')
{
	get_check_code();
}

/* 编辑支付方式理 */
elseif ($act  == 'act_edit_payment')
{
	
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
	$res = $user_obj->editPayment();
	
	$order_id = intval($_POST['order_id']);
	if($res){
		$datas = json_encode(array('code'=>0,'msg'=>'已更换成功！'));
	}else{
		$datas = json_encode(array('code'=>1,'msg'=>'已更换失败！'));
	}
    /* 跳转 */
    die($datas);
}

/* 用户中心 */
else
{        
	if ($_SESSION['user_id'] > 0)
	{            
            show_user_center();
	}
	else//登录页面输出
	{            
		//响应json数据到客户端
		$ROOT_PATH_= str_replace ( 'mobile/user.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
		//end add by dingchaoyang 2014-12-18
		$reuser_name= isset($_COOKIE['ECS']['reuser_name']) ? $_COOKIE['ECS']['reuser_name'] : '';
		if(!empty($reuser_name)){
			$smarty->assign('reuser_name', $reuser_name);
		}
        //start 增加会议id  会议登录之前的赋值会议id @add liuhui 2015-01-31
        $activity_id = isset ($_REQUEST['activity_id']) ? $_REQUEST['activity_id'] : "";
        if($activity_id){
            $smarty->assign('activity_id', $activity_id);
        }else{
            $smarty->assign('activity_id',"");
        }
        //end 增加会议id  会议登录之前的赋值会议id @add liuhui 2015-01-31
        
		//add by zhangxi, 20150203, 活动中，登陆后，能够重定向到活动前的页面, start
		if(isset($GLOBALS['_SERVER']['HTTP_REFERER'])
				&& strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'spring_activity.php'))
		{
			$gourl = $GLOBALS['_SERVER']['HTTP_REFERER'];
		//		            $gourl = strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'user.php') ? './user.php' : $GLOBALS['_SERVER']['HTTP_REFERER'];
		}
		//comment by zhangxi, 20150417, 专题页面也可以这样处理
		else if(isset($GLOBALS['_SERVER']['HTTP_REFERER'])
				&& strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'chinalife_zhuanti.php'))
		{
			$gourl = $GLOBALS['_SERVER']['HTTP_REFERER'];
		//		            $gourl = strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'user.php') ? './user.php' : $GLOBALS['_SERVER']['HTTP_REFERER'];
		}
		else
		{
			$gourl='';
		}
		
		
		$smarty->assign('gourl', $gourl);
		//add by zhangxi, 20150203, 活动中，登陆后，能够重定向到活动前的页面, end

		$smarty->assign('footer', get_footer());
		$smarty->display('login.dwt');
		exit;
	}
}

/**
 * 用户中心显示
 */
function show_user_center()
{
	ss_log("into ".__FUNCTION__);
	
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
        
	$user_info = get_user_info();
	$GLOBALS['smarty']->assign('user_info', $user_info );
	$GLOBALS['smarty']->assign('best_goods' , $best_goods);
	$GLOBALS['smarty']->assign('footer', get_footer());
	
	$GLOBALS['smarty']->assign('profile', $user_info );
	
	//add yes123  2015-05-04获取最新公告
	include_once(ROOT_PATH . 'includes/lib_user.php');
	$affiche = get_new_affiche();
	$GLOBALS['smarty']->assign('affiche', $affiche );
	
	
	//start add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能
	if(isset($_SESSION['activity_id']))
	{
		$activity_id = $_SESSION['activity_id'];//add by wangcya, 2015-01-31 , for bug[238]
		ss_log("activity_id: ".$activity_id);	
	}
	else
	{
		ss_log("not find activity_id in session");
		$activity_id = 0;
	}
	
	if($user_info['type'] =="meeting" || $activity_id>0 )
	{//会议注册用户进入不同的主页
		$uid = $_SESSION['user_id'];
		$sql = "SELECT * from bx_meeting_register WHERE uid='$uid'";
		$meeting_list = $GLOBALS['db']->getAll($sql);
		
		$attr_registered = array(0=>"未报名",
			  					 1=>"已报名");
		
		$attr_need_pay = array(0=>"免费",
							   1=>"收费");
		
		foreach ($meeting_list AS $key => $value)
		{
			$meeting_list[$key]['dateline_reg'] = date('Y-m-d H:i', $value['dateline_reg']);
			$meeting_list[$key]['dateline_pay'] = date('Y-m-d H:i', $value['dateline_pay']);
			$meeting_list[$key]['registered_str'] = $attr_registered[$value['registered']];
			$meeting_list[$key]['need_pay_str'] = $attr_need_pay[$value['need_pay']];
			
			if($value['need_pay']&&!$value['registered'])
			{
				$meeting_list[$key]['show_pay'] = 1;
			}
		}
		
		$GLOBALS['smarty']->assign('meeting_list', $meeting_list );
		$GLOBALS['smarty']->display('user_meeting.dwt');
	}
	//end add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能
	else
	{
		$GLOBALS['smarty']->display('user.dwt');
	}
	
}

/**
 * 手机注册
 */
function m_register($username, $password, $email, $other = array())
{
	/* 检查username */
	if (empty($username))
	{
		echo '<script>alert("用户名必须填写！");window.location.href="user.php?act=register"; </script>';
		return false;
	}
	if (preg_match('/\'\/^\\s*$|^c:\\\\con\\\\con$|[%,\\*\\"\\s\\t\\<\\>\\&\'\\\\]/', $username))
	{
		echo '<script>alert("用户名错误！");window.location.href="user.php?act=register"; </script>';
		return false;
	}

	/* 检查是否和管理员重名 */
	if (admin_registered($username))
	{
		echo '<script>alert("此用户已存在！");window.location.href="user.php?act=register"; </script>';
		return false;
	}

	if (!$GLOBALS['user']->add_user($username, $password, $email))
	{
		echo '<script>alert("注册失败！");window.location.href="user.php?act=register"; </script>';
		//注册失败
		return false;
	}
	else
	{
		//注册成功

		/* 设置成登录状态 */
		$GLOBALS['user']->set_session($username);
		$GLOBALS['user']->set_cookie($username);
	}

		//定义other合法的变量数组
		$other_key_array = array('msn', 'qq', 'office_phone', 'home_phone', 'mobile_phone');
		$update_data['reg_time'] = local_strtotime(local_date('Y-m-d H:i:s'));
		if ($other)
		{
			foreach ($other as $key=>$val)
			{
				//删除非法key值
				if (!in_array($key, $other_key_array))
				{
					unset($other[$key]);
				}
				else
				{
					$other[$key] =  htmlspecialchars(trim($val)); //防止用户输入javascript代码
				}
			}
			$update_data = array_merge($update_data, $other);
		}
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('users'), $update_data, 'UPDATE', 'user_id = ' . $_SESSION['user_id']);

		update_user_info();	  // 更新用户信息
        $Loaction = 'user.php?act=user_center';
        ecs_header("Location: $Loaction\n");
		return true;

}

//add yes123 2014-16 更新openid
function updateOPenId($user_id,$b_openid) {
	ss_log('updata user openid ..  user_id:'.$user_id.',openid:'.$b_openid);
	if($b_openid)
	{
		//先根据此openid 清空
		$sql = "UPDATE " . $GLOBALS['ecs']->table('users') ." SET openid=''  WHERE openid='$b_openid'";
     	$GLOBALS['db']->query($sql);
		
		$sql = "UPDATE " . $GLOBALS['ecs']->table('users') ." SET openid='$b_openid'   WHERE user_id = '$user_id'";
     	return $GLOBALS['db']->query($sql);
	}
}


?>