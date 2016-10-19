<?php

/**
 * ECSHOP 会员中心
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: user.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/* 载入语言文件 */
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/user.php');

require_once(ROOT_PATH . 'baoxian/common.php');
require_once(ROOT_PATH . 'baoxian/source/function_debug.php');
include_once(ROOT_PATH . 'includes/lib_clips.php');
include_once(ROOT_PATH . 'includes/class/distrbutor.class.php');
/////////////////////////////////////////////////////////////////////////////

require(ROOT_PATH .'languages/zh_cn/user.php');
///////////////////////////////////////////////////////////////////////////
///phpinfo();

$user_id = $_SESSION['user_id'];
$action  = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'profile';

$affiliate = unserialize($GLOBALS['_CFG']['affiliate']);
$smarty->assign('affiliate', $affiliate);

$back_act='';
	//如果用户已经登录，则获取用户基本信息
if($user_id)
{
	include_once(ROOT_PATH . 'includes/lib_user.php');
	$user_base_info = user_base_info($user_id);
	$smarty->assign('user_base_info', $user_base_info);
	
	//获取渠道是私有还是公有
	if($_SESSION['rank_code']==ORGANIZATION_USER && empty($_SESSION['d_institution_type']))
	{
		ss_log("session d_institution_type:".$_SESSION['d_institution_type']);
			$sql = "SELECT d_institution_type FROM bx_distributor WHERE  d_uid='$user_id'";
			$d_institution_type = $GLOBALS['db']->getOne($sql);
			$_SESSION['d_institution_type'] = $d_institution_type;

	}
	
}

/**
 * 2014/7/29
 * bhz
 * 数组$not_login_arr，$ui_arr中添加 bank_account、group_buy
 */

// 不需要登录的操作或自己验证是否登录（如ajax处理）的act
$not_login_arr =
array('act_cus_cer_type','login','act_login','register','act_register','get_password','send_pwd_email','password', 'signin', 'add_tag',  'return_to_cart', 'logout', 'email_list', 'validate_email', 'send_hash_mail', 'order_query', 'is_registered', 'check_email','clear_history','qpassword_name', 'get_passwd_question', 'check_answer', 'bank_account', 'group_buy', 'add_bank', 'policy_edit', 'edit_bank', 'act_edit_bank', 'just_print', 'phone_code','update_password_verification_code','change_version','is_exist_by_value','check_captcha','collect');
//array('act_cus_cer_type','login','act_login','register','act_register','get_password','send_pwd_email','password', 'signin', 'add_tag',  'return_to_cart', 'logout', 'email_list', 'validate_email', 'send_hash_mail', 'order_query', 'is_registered', 'check_email','clear_history','qpassword_name', 'get_passwd_question', 'check_answer', 'bank_account', 'group_buy', 'add_bank', 'edit_pass', 'policy_edit', 'edit_bank', 'act_edit_bank', 'just_print', 'organ_info', 'salesman_list', 'edit_organ', 'control_add', 'salesman', 'phone_code','update_password_verification_code');
/* 显示页面的action列表 */
$ui_arr = array('save_fp','act_edit_password','cancel','delete_account',
'add_account','withdrawals','delete_collection','act_edit_surplus','act_edit_service_money',
'collect','cancel_order','act_pay_wap','act_edit_profile','del_customer','add_customer',
'upload_img','upload_file','register','warranty_list', 'login', 'profile', 'order_list', 'order_detail',
'policy_detail', 'address_list', 'collection_list','message_list', 'tag_list', 'get_password',
'reset_password', 'booking_list', 'add_booking', 'account_raply','account_deposit', 'account_log',
'account_detail','account_income','transfer_accounts', 'act_account', 'pay', 'default', 'bonus', 
'group_buy', 'group_buy_detail', 'affiliate', 'comment_list','validate_email','track_packages', 
'transform_points','qpassword_name', 'get_passwd_question', 'check_answer','bank_account', 'group_buy', 
'add_bank', 'edit_pass', 'policy_edit','edit_bank', 'act_edit_bank', 'just_print', 'organ_info',
'salesman_list','child_organ_list','child_organ_info','edit_organ', 'control_add', 'salesman', 
'invoice_list','customer_list','change_phone','salesman_detail','remove_salesman','order_manage_list',
'policy_manage_list','edit_pass','user_center_affiche','cart_list','remove_policy','act_account_show',
'withdraw_confirm_info','child_organ_add','department_list','save_organ_department','del_department',
'organ_goods_list','waiting_processing','settlement_search','change_userbg_img','upload_policy_attachment','go_pay_form','organ_manage','search_pay_status'
);
// add comment 删除'phone_code'在$ui_arr by dingchaoyang 2014-12-27

///////////////start add by wangcya , 20141030,第三方渠道的方式///////////////////////
if ($action == 'channel_login')
{
	ss_log("into user.php, act channel_login");
	////////////////////////////////////////////

	//$peer_uid = 11;
	//$peer_username = "wcyht234";

	/////////////////////////////////////////////////////////
	if($_GET['op'] =="test")
	{
		$_POST['username'] = "wcyht234";
		$_POST['password'] = "123456";
		$_POST['remember'] = 0;
	}
	///////////////////////////////////////
	//$en_str = "fei93-tgie13jwi.6s78wwmerti";

	//$str_sig_remote = md5($peer_uid.$en_str);
	//$_POST['channel_sig'] = $str_sig_remote;
	//$str_sig_local  = md5($peer_uid.$en_str);
	//首先应该到数据库中查询一下，是否有这个用户。

	$allow_direct_visit = 0;
	
	//include_once('../includes/init.php');
	include_once('../languages/'.'zh_cn'. '/user.php');
	include_once('../includes/lib_passport.php');
	
	if(empty($_SESSION['user_id']))
	{
		$ret_login = $user->login($_POST['username'], $_POST['password'],isset($_POST['remember']));
	}
	else
	{
		$ret_login = true;
	}
	
	if($ret_login)
	{


		if($_GET['op'] =="test")
		{
			
			$_POST['applyNum'] = 1;
			$_POST['startDate'] = "2014-11-12 00:00:00";
			$_POST['endDate'] = "2015-11-03 23:59:59";
			$_POST['relationshipWithInsured'] = 3;//子女
			$_POST['totalModalPremium'] = 20;
			$_POST['beneficiary'] = 1;
			
			////////////////////////////////////////////////////////////
			$_POST['applicant_certificates_type'] = 1;//身份证
			$_POST['applicant_certificates_code'] = "11010519810716681X";
			$_POST['applicant_fullname']= "老王";
			$_POST['applicant_sex']= 1;//男
			$_POST['applicant_birthday']= "1981-07-16";
			$_POST['applicant_mobilephone']= "13611260420";
			$_POST['applicant_email']= "13611260420@sohu.com";
			$_POST['applicant_province_code']= "110000";
			$_POST['applicant_province']= "北京";
			$_POST['applicant_city_code']= "110100";//北京
			$_POST['applicant_city']= "北京";
			$_POST['applicant_zipcode']= "100103";
			$_POST['applicant_address']= "北苑";
			$_POST['applicant_occupationClassCode']= "A0004";
				
			////////////////////////////////////////////////////////////
			//	$POST[assured[0][assured_certificates_type]];
			$assured_post = array();
			$assured_post['assured_certificates_type']= 1;
			$assured_post['assured_certificates_code']= "11022619871204472X";
			$assured_post['assured_birthday']= "1987-12-04";
			$assured_post['assured_fullname']= "小王-测试";
			$assured_post['assured_sex']= 2;//1男，2女
			$assured_post['assured_mobilephone']= "13611255332";
			$assured_post['assured_email']= "13611255332@sina.com";
			$assured_post['insured_province_code']= "110000";
			$assured_post['insured_province']= "北京";
			$assured_post['insured_city_code']= "110100";
			$assured_post['insured_city']= "北京";
			$assured_post['insured_zipcode']= "100105";
			$assured_post['insured_address']= "天通苑";
			$assured_post['insured_occupationClassCode']= "A0004";
			
			
			$_POST['assured'][0] = $assured_post;
				
			//////////////////////////////////////////////////////////
			
			$_POST['orderDate']= "2014-11-13 02:00:00";
			$_POST['flightNo']= "MU123";
			$_POST['flightFrom']= "北京";
			$_POST['flightTo']= "伤害";
			$_POST['takeoffDate']= "2014-11-12 02:00:00";
			$_POST['landDate']   = "2014-11-12 03:00:00";
			$_POST['eTicketNo']= "ABC";
			$_POST['pnrCode']= "BBB";
			$_POST['ticketAmount']= "2000";
			$_POST['travelAgency']= "春秋";
			
			/////////////////////////////////////////////////////////
			$_POST['product_code'] = "2101700000000009";
			$_POST['peer_order_id'] = rand(1,1000);
			
			$_POST['channel'] = "datang";
			$_POST['type'] = "policy";
			
			$_POST['service_fee'] = 0;
			$_POST['hardwareprice'] = 0;
			///////////////////////////////////////////////////////////////////
		}
		//////////////////////////////////////////////////
		$product_code = $_POST['product_code'];
		$peer_order_id = $_POST['peer_order_id'];
		$channel = $_POST['channel'];
		$type  = $_POST['type'];
		$service_fee = $_POST['service_fee'];//1;//$hardwareprice*0.20; 每个订单为1元
		$hardwareprice = $_POST['hardwareprice'];
		
		
		$agent_uid = $_SESSION['user_id'];
		$agent_username = $_SESSION['user_name'];
		
		////////////////////////////////////////////////////////////
		$lock = new CacheLock('key_name');
		$lock->lock();
		///////////////////////////////////////////////////////////////
		
		$ret_attr = array();
		if($peer_order_id)//  就是说可能出现刷新导致重复提交。
		{
			$wheresql = "peer_order_id='$peer_order_id'";
			$sql = "SELECT * FROM t_order_device WHERE $wheresql LIMIT 1";
			//ss_log($sql);
			$attr_order_other_device = $db->getRow($sql);
			if($attr_order_other_device['peer_order_id'])
			{//已经存在了对方的这个订单号
				$ret_attr['status']= "113";
				$ret_attr['info']= "your order id is already exisit! your_order_id: ".$attr_order_other_device['peer_order_id'];
					
				ss_log($ret_attr['info']);
				//my_order_id
				//
				$ret_attr['data']= array();
				$ret_attr['data']['user_id'] = $attr_order_other_device['agent_uid'];
				$ret_attr['data']['order_id'] = $attr_order_other_device['my_order_id'];
				echo json_encode($ret_attr);
					
				$lock->unlock();
				exit(0);
			}
			else//没有，则插入一条
			{
				$order_status ="";
				
			
				
				/*
				$order_arr = array(
						'agent_uid'=> $agent_uid,
						'agent_username'=> $agent_username,
						//'my_order_id'=> $order_id,
						'channel' => $channel,
						'type'=>  $type,
						'peer_order_id' =>  $peer_order_id,
						'hardware_price' => $hardwareprice,
						'service_fee' => $service_fee,
						'dateline' => $_SGLOBAL['timestamp']
				);
				*/
					
				ss_log("--will before insert order_device, peer_order_id: ".$peer_order_id);
				$dateline = time();
				$sql = "INSERT INTO t_order_device(agent_uid, agent_username, channel, type,peer_order_id,hardware_price, service_fee,dateline) 
				        VALUES ('$agent_uid','$agent_username','$channel','$type','$peer_order_id','$hardwareprice','$service_fee','$dateline')";
				$GLOBALS['db']->query($sql);
				$order_device_id = $db->insert_id();
				
				//$order_device_id = inserttable('order_device', $order_arr,true);
				ss_log("--after insert order_device, id is :".$order_device_id);
				if($order_device_id<=0)
				{
					$msg = "your order id is alread exist! order_id: ".$peer_order_id;
					ss_log($msg);
		
					$ret_attr['status']= "115";
					$ret_attr['info']= $msg;
					echo json_encode($ret_attr);
		
					$lock->unlock();
					exit(0);
				}
					
			}
		
		}
		else
		{//对方的订单不存在，也不行。
			$ret_attr['status']= "114";
			$ret_attr['info']= "your order id is null!";
			
			ss_log($ret_attr['info']);
			
			echo json_encode($ret_attr);
			
			$lock->unlock();
			exit(0);
		
		}

		//logic here
		$lock->unlock();
		//使用过程中需要注意下文件锁所在路径需要有写权限.
		//////////////////////////////////////////////////////
		
		
		$sql = "SELECT attribute_id,product_id FROM t_insurance_product_base WHERE product_code='$product_code'";
		$product_attr = $db->getRow($sql);
		if($product_attr['product_id'])
		{
			$product_id = $product_attr['product_id'];
			$attribute_id = $product_attr['attribute_id'];
			
			$sql = "SELECT goods_id FROM bx_goods WHERE tid='$attribute_id'";
			$goods_id = $db->getOne($sql);
		}
		else
		{
			//$ret_attr = array();
			$ret_attr['status']= 120;//"0"; modify by wangcya, 20141010
			$ret_attr['info']= "没有对应的产品!";
			
			$str_json = json_encode($ret_attr);
			//ss_log("--will return json is : ".$str_json);
				
			echo $str_json;
			exit(0);
		}
			
		////////////////////////////////////////////////////////////
		ss_log("login success! peer username: ".$_POST['username']);
		//ss_log("user.php, allow_direct_visit, peer uid: ".$peer_uid);
			
			
		$allow_direct_visit = 1;
		
		//////////////////////////////////////////////////////////
		$_GET['ac'] = "product_buy";
		$_GET['product_id'] = $product_id;
		$_POST['product_id'] = $product_id;//一定要增加
		$_GET['gid'] = $goods_id;//goods_id
		$_GET['allow_direct_visit'] = $allow_direct_visit;//为了传递参数
		$_GET['order_device_id'] = $order_device_id;//对应双方的订单号。
		

		ss_log("flow, will redirect to cp.php");
		ss_log("product_id: ".$product_id." gid: ".$goods_id);
		
		include_once(ROOT_PATH.'baoxian/cp.php');
	}
	else
	{
		//$ret_attr = array();
		$ret_attr['status']= 120;//"0"; modify by wangcya, 20141010
		$ret_attr['info']= "login fail!";
			
		$ret_attr['data']= array();
		//$ret_attr['data']['user_id'] = $uid;
		//$ret_attr['data']['order_id'] = $order['order_id'];
		//$ret_attr['data']['order_sn'] = $order['order_sn'];
		//$ret_attr['data']['policy_id'] = $policy_id;
			
			
		$v = var_export($ret_attr, TRUE);
		ss_log("--will before return json is : ". $v);
			
		$str_json = json_encode($ret_attr);
		ss_log("--will return json is : ".$str_json);
			
		echo $str_json;
		exit(0);
	}

} 	
////////////////end add by wangcya , 20141030,第三方渠道的方式///////////////////////

//add sessionmanager for app dingchaoyang 2014-12-21
if (in_array($action, $ui_arr)){
	include_once (ROOT_PATH . 'api/EBaoApp/eba_sessionManager.class.php');
	Eba_SessionManager::setSession();
	$user_id = $_SESSION['user_id'];
}
//end by dingchaoyang 2014-12-21

//add log for app dingchaoyang 2014-12-23
if($action != 'act_login' && $action != 'act_edit_password'){
	include_once (ROOT_PATH . 'api/EBaoApp/eba_logManager.class.php');
	Eba_LogManager::log('data from user.php,uid='.$user_id.',act='.$action.'。');
}


//end
/* 未登录处理 */
if (empty($_SESSION['user_id']))
{
    if (!in_array($action, $not_login_arr))
    {
        if (in_array($action, $ui_arr))
        {
            /* 如果需要登录,并是显示页面的操作，记录当前操作，用于登录后跳转到相应操作
            if ($action == 'login')
            {
                if (isset($_REQUEST['back_act']))
                {
                    $back_act = trim($_REQUEST['back_act']);
                }
            }
            else
            {}*/
            if (!empty($_SERVER['QUERY_STRING']))
            {
                $back_act = 'user.php?' . strip_tags($_SERVER['QUERY_STRING']);
            }
            $action = 'login';
        }
        else
        {
            //未登录提交数据。非正常途径提交数据！
            die($_LANG['require_login']);
        }
    }
}

/* 如果是显示页面，对页面进行相应赋值 */
if (in_array($action, $ui_arr))
{
    assign_template();
    $position = assign_ur_here(0, $_LANG['user_center']);
    $smarty->assign('page_title', $position['title']); // 页面标题
    $smarty->assign('ur_here',    $position['ur_here']);
    $sql = "SELECT value FROM " . $ecs->table('shop_config') . " WHERE id = 419";
    $row = $db->getRow($sql);
    $car_off = $row['value'];
    $smarty->assign('car_off',       $car_off);
    /* 是否显示积分兑换 */
    if (!empty($_CFG['points_rule']) && unserialize($_CFG['points_rule']))
    {
        $smarty->assign('show_transform_points',     1);
    }
    $smarty->assign('helps',      get_shop_help());        // 网店帮助
    $smarty->assign('data_dir',   DATA_DIR);   // 数据目录
    $smarty->assign('action',     $action);
    $smarty->assign('lang',       $_LANG);
}


/*

//start add by yes123, 20140929,修改金额和删除日志的功能代码，以后要删除这段代码。
if ($action == 'updatejine')
{
	$sql="select user_id,user_money,change_desc from bx_account_log where change_desc like '支付宝付款%'";
	$row = $db->getAll($sql);
	echo $sql;echo "<br/>";
	echo "<pre>";print_r($row);

	foreach ($row as $key => $value ) 
	{
		//查询出问题的订单
		$user_money = "-".$value['user_money'];
		$sql_update = "UPDATE " . $GLOBALS['ecs']->table('users') .
		" SET user_money = user_money + (".$user_money .")" .
		" WHERE user_id =".$value['user_id'];
		echo $sql_update;
		ss_log($sql_update);
		echo "<br/>";
		$GLOBALS['db']->query($sql_update);
		 
		$sql_delete ="delete from bx_account_log where change_desc ='".$value['change_desc']."'";
		echo $sql_delete;
		ss_log($sql_delete);
		
		$GLOBALS['db']->query($sql_delete);
		 
	}
	
	exit;
}
//end add by yes123, 20140929,修改金额和删除日志的功能代码，以后要删除这段代码。

*/

//用户中心欢迎页
if ($action == 'default')
{
    include_once(ROOT_PATH .'includes/lib_clips.php');
	
    if ($rank = get_rank_info())
    {
        $smarty->assign('rank_name', sprintf($_LANG['your_level'], $rank['rank_name']));
        if (!empty($rank['next_rank_name']))
        {
            $smarty->assign('next_rank_name', sprintf($_LANG['next_level'], $rank['next_rank'] ,$rank['next_rank_name']));
        }
    }
    
    //如果此用户属于渠道下的成员，那么也显示只能购买的产品
    $user_id = $_SESSION['user_id'];
    $user_info = get_user_info($user_id);
		
    $smarty->assign('institution_id',        $institution_id);
    
    $info = get_user_default($user_id);
 
    $smarty->assign('info',        $info);
    $smarty->assign('user_notice', $_CFG['user_notice']);
    $smarty->assign('prompt',      get_user_prompt($user_id));
	/***
	 * 修改时间:2014/7/29
	 * 修改者：鲍洪州
	 * 功能：分配产品分类数据
	 */
	$smarty->assign('categories', $categories);
	include_once(ROOT_PATH . 'includes/lib_user.php');
	$user_base_info = user_base_info($_SESSION['user_id']);
	$smarty->assign('user_base_info', $user_base_info);
	
    $smarty->display('user_clips_default.dwt');
}


/* 显示会员注册界面 */
if ($action == 'register')
{
    if ((!isset($back_act)||empty($back_act)) && isset($GLOBALS['_SERVER']['HTTP_REFERER']))
    {
        $back_act = strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'user.php') ? './index.php' : $GLOBALS['_SERVER']['HTTP_REFERER'];
    }

    /* 取出注册扩展字段 */
    $sql = 'SELECT * FROM ' . $ecs->table('reg_fields') . ' WHERE type < 2 AND display = 1 ORDER BY dis_order, id';
    $extend_info_list = $db->getAll($sql);
    $smarty->assign('extend_info_list', $extend_info_list);

    /* 验证码相关设置 */
    if ((intval($_CFG['captcha']) & CAPTCHA_REGISTER) && gd_version() > 0)
    {
        $smarty->assign('enabled_captcha', 1);
        $smarty->assign('rand',            mt_rand());
    }
		
	//add yes123 2015-03-05 个人注册或者渠道注册的标志
	$is_institution = isset($_REQUEST['is_institution'])?$_REQUEST['is_institution']:1;
	$smarty->assign('is_institution', $is_institution);
    /* 密码提示问题 */
    $smarty->assign('passwd_questions', $_LANG['passwd_questions']);

	 	
	$smarty->assign('user_type_list', $user_type_list);
    /* 增加是否关闭注册 */
    $smarty->assign('shop_reg_closed', $_CFG['shop_reg_closed']);
    $smarty->assign('back_act', $back_act);
    $smarty->display('reg.dwt');
}
/* 保单处理   add by wangcya, 20140731*/
elseif ($action == 'warranty_list')
{	
	
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
	$res = $user_obj->getPolicyList();
	$warranty = $res['policy_list'];
	$record_count = $res['record_count'];
	$pager = $res['pager'];
	$condition = $res['condition'];
    $page = $pager['page'];
    
    //add by dingchaoyang 2014-12-1
    //响应json数据到客户端
//  print_r(iconv('utf-8', 'gb2312//ignore', json_encode($warranty)));
    include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    $more = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
    if ($more > intval($pager['page_count'])){
    	EbaAdapter::responsePolicyList('2');
    }else{
    	EbaAdapter::responsePolicyList($warranty);
    } 
    //end add by dingchaoyang 2014-12-1
	
	$smarty->assign('pager',  $pager);

	$smarty->assign('warranty', $warranty);
	/***
	 * 2014/7/29
	 * bhz
	 * 分配产品分类数据
	 */
      
	$smarty->assign('brand_list', get_brand_list());
	$smarty->assign('recent', $_REQUEST['recent']); //2014/11/19 add @ liuhui
	$smarty->assign('condition', $condition);  		
    $smarty->display('user_transaction.dwt');
	
}

/*理赔查询*/
elseif ($action == 'settlement_search')
{	
	
	$smarty->display('user_transaction.dwt');
}

/*理赔查询*/
elseif ($action == 'waiting_processing')
{	
	$smarty->display('user_transaction.dwt');
}

/* 购物车列表  add by yes123, 2015-05-19*/
elseif ($action == 'cart_list')
{	
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
	$_REQUEST['list_type']='cart_list';
	$user_obj = new User;
	
	//start 清空昨天之前的购物车投保单
	$dateline=date('Y-m-d H:i:s',strtotime(date('Y-m-d')));
	$dateline = strtotime($dateline);
	$sql = "SELECT policy_id FROM t_insurance_policy WHERE policy_status='cart' AND order_id=0 AND dateline<'$dateline' AND agent_uid=$user_id ";
	ss_log("获取可以清空的投保单:".$sql);
	$policy_id_list = $db->getAll($sql);
	if(!empty($policy_id_list))
	{
		$policy_ids = CommonUtils :: arrToStr($policy_id_list, "policy_id");
		$res = $user_obj->removePolicy($policy_ids,$user_id);
	}

	//end 清空昨天之前的购物车投保单
	
	
	
	$res = $user_obj->getPolicyList();
	$warranty = $res['policy_list'];
	$total_premium = $res['total_premium'];
	$record_count = $res['record_count'];
	$pager = $res['pager'];
	$condition = $res['condition'];
    $page = $pager['page'];
    
    //add by dingchaoyang 2014-12-1
    //响应json数据到客户端
//  print_r(iconv('utf-8', 'gb2312//ignore', json_encode($warranty)));
    include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    $more = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
    if ($more > intval($pager['page_count'])){
    	EbaAdapter::responsePolicyList('2');
    }else{
    	EbaAdapter::responsePolicyList($warranty);
    } 
    //end add by dingchaoyang 2014-12-1
	
	$smarty->assign('pager',  $pager);

	$smarty->assign('warranty', $warranty);
	$smarty->assign('total_premium', price_format($total_premium, $change_price = true) );

	/***
	 * 2014/7/29
	 * bhz
	 * 分配产品分类数据
	 */
      
	//$smarty->assign('categories', $categories);
	$smarty->assign('recent', $_REQUEST['recent']); //2014/11/19 add @ liuhui
	$smarty->assign('condition', $condition);  		
    $smarty->display('user_transaction.dwt');
	
}
/* 移除保单  add by yes123, 2015-05-20*/
elseif ($action == 'remove_policy')
{	
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
	$user_obj = new User;
	$policy_ids = isset($_GET['policy_ids'])?$_GET['policy_ids']:0;
	$res = $user_obj->removePolicy($policy_ids,$user_id);
	die(json_encode($res));
}

/* 保单处理   add by wangcya, 20140731*/
elseif ($action == 'policy_detail')
{      
	$policy_id = isset($_REQUEST['policy_id']) ? intval($_REQUEST['policy_id']) : 1;
     
    //判断是否是本人
    $sql = "SELECT agent_uid FROM t_insurance_policy WHERE policy_id='$policy_id'";
    $agent_uid = $GLOBALS['db']->getOne($sql);
    
    if($agent_uid!=$_SESSION['user_id'])
    {
    	
 		show_message("无权查看他人保单！");
    	
    }
        

	if($policy_id)
	{
		$ret = get_policy_info_view($policy_id, $smarty);
		
		//echo "<pre>";print_r($ret);exit;
		//add by dingchaoyang 2014-12-1
// 		print_r(iconv('utf-8', 'gb2312//ignore', json_encode($ret)));
		//响应json数据到客户端
		include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
		EbaAdapter::responsePolicyDetail($ret);
		//end add by dingchaoyang 2014-12-1

		$policy = $ret['policy_arr'];
		//add yes123 2015-04-14 如果是阳光的产品，数据另外获取
		if($policy['insurer_code']=='sinosig')
		{
			//1.获取车辆信息
			include_once(ROOT_PATH . 'includes/lib_policy.php');
			$car = get_policy_car_info($policy_id);
			
			//2.投保险种及费用
			if($policy['attribute_type']=='compulsory') //交强险
			{ 
				$policy_car_sun_compulsory = get_compulsory_policy_info($policy_id);
				$smarty->assign('policy_car_sun_compulsory', $policy_car_sun_compulsory);
			}
			else if($policy['attribute_type']=='commercial')
			{
				$policy_car_sun_commercial = get_commercial_policy_info($policy_id);
				$smarty->assign('policy_car_sun_commercial', $policy_car_sun_commercial);
			}
			
			$policy['user_info_applicant'] = $ret['user_info_applicant'];
			$policy['user_info_applicant']['gender']=substr($policy['user_info_applicant']['certificates_code'], (strlen($policy['user_info_applicant']['certificates_code'])==15 ? -2 : -1), 1) % 2 ? '男' : '女'; 
			$smarty->assign('policy', $policy);
			$smarty->assign('car', $car);
			$smarty->display('user_transaction_policy_detail.dwt');
			exit;
		}
		
		
		$user_info_applicant = $ret['user_info_applicant'];
		$list_subject = $ret['list_subject'];//多层级一个链表
		
		$product_info = $list_subject[0]['list_subject_product'][0];
		
		//start by zhangxi, 2014119, start
    	$list_subject = y502_assign_user_policy_info($list_subject, $smarty, $product_info['product_id'],$policy_id, $policy);
	    //end by zhangxi, 20141219
	    //echo $policy['insurer_code'];
	    if($policy['insurer_code'] == 'SINO')
	    {
	    	//added by zhangxi, 20150107,  华安工程信息获取
	    	huaan_assign_project_info($smarty, $product_info['product_id'],$policy_id);
	    	
	    }
	
	    //added by zhangxi, 20150408, 新华人寿的处理
//	    if($policy['insurer_code'] == 'NCI')
//	    {
//	    	global $attr_xinhua_nation;
//			global $attr_xinhua_applicant_gender;
//			global $attr_xinhua_certificates_type;
//			global $attr_xinhua_relationship_with_applicant;
//	    	//policy中包含了附加信息
//	    	
//	    	//投保人信息$user_info_applicant
//	    	
//	    	//被保险人信息 list_subject
//	    	
//	    	//投保人国籍，被保险人国籍
//	    	require_once(ROOT_PATH . 'oop/classes/NCI_Insurance.php');
//			$nciobj = new NCI_Insurance();
//			//更新相关信息显示：
//			
//    		//通过省代码获取省份名称
//			$user_info_applicant['province_code'] = $nciobj->get_province_name_by_code($user_info_applicant['province_code']);
//    		//通过市代码获取市名称
//			$user_info_applicant['city_code'] = $nciobj->get_city_name_by_code($user_info_applicant['city_code']);		
//    		//通过县代码获取县名称
//			$user_info_applicant['county_code'] = $nciobj->get_county_name_by_code($user_info_applicant['county_code']);		
//			$user_info_applicant['business_type'] = $nciobj->get_industry_name_by_code($user_info_applicant['business_type']);		
//    		//通过职业代码获取职业名称
//			$user_info_applicant['occupationClassCode'] = $nciobj->get_career_name_by_code($user_info_applicant['occupationClassCode']);
//			
//			$policy["serviceOrgCode"] = $nciobj->get_service_name_by_code($policy["serviceOrgCode"]);
//			$policy["partPayBankCode"] = $nciobj->get_bank_name_by_code($policy["partPayBankCode"]);
//			//国籍
//			$user_info_applicant['nation_code'] = $attr_xinhua_nation[$user_info_applicant['nation_code']];
//			
//			
//			$list_insurant = $list_subject[0]['list_subject_insurant'];
//			$list_beneficiary_arr = array();
//			foreach($list_insurant as $key=>$value)
//			{
//				$list_subject[0]['list_subject_insurant'][$key]['province_code'] = $nciobj->get_province_name_by_code($value['province_code']);
//				$list_subject[0]['list_subject_insurant'][$key]['city_code'] = $nciobj->get_city_name_by_code($value['city_code']);
//				$list_subject[0]['list_subject_insurant'][$key]['county_code'] = $nciobj->get_county_name_by_code($value['county_code']);
//				$list_subject[0]['list_subject_insurant'][$key]['nation_code'] = $attr_xinhua_nation[$value['nation_code']];
//				$list_subject[0]['list_subject_insurant'][$key]['occupationClassCode'] = $nciobj->get_career_name_by_code($value['occupationClassCode']);
//				$list_subject[0]['list_subject_insurant'][$key]['business_type'] = $nciobj->get_industry_name_by_code($value['business_type']);
//				//每个被保险人下都有，
//				$list_beneficiary_arr[] = $value['list_beneficiary'];
//			}
//			
//			//暂时只用一个呗保险人的受益人
//			$list_beneficiary = $list_beneficiary_arr[0];
//			foreach($list_beneficiary as $key=>$val)
//				{
//					$list_beneficiary[$key]['nativePlace'] = $attr_xinhua_nation[$val['nativePlace']];
//					$list_beneficiary[$key]['sex'] = $attr_xinhua_applicant_gender[$val['sex']];
//					$list_beneficiary[$key]['cardType'] = $attr_xinhua_certificates_type[$val['cardType']];
//					//$list_beneficiary[$key]['cardNo'] = $val['cardNo'];
//					$list_beneficiary[$key]['benefitRelation'] = $attr_xinhua_relationship_with_applicant[$val['benefitRelation']];
//				}
//			$smarty->assign('list_beneficiary', $list_beneficiary );
//			//$insurant_info['province_code'] = $nciobj->get_province_name_by_code($insurant_info['province_code']);
//			//$insurant_info['city_code'] = $nciobj->get_city_name_by_code($insurant_info['city_code']);
//			//$insurant_info['county_code'] = $nciobj->get_county_name_by_code($insurant_info['county_code']);
//			//$insurant_info['nation_code'] = $attr_xinhua_nation[$insurant_info['nation_code']];
//			//$insurant_info['occupationClassCode'] = $nciobj->get_career_name_by_code($insurant_info['occupationClassCode']);
//			//$insurant_info['business_type'] = $nciobj->get_industry_name_by_code($insurant_info['business_type']);
//			
//			
//			//新华人寿增加受益人信息获取变量  $list_beneficiary
//			
//	    }
	    
	    
		$smarty->assign('product_info', $product_info );
		//ss_log("product_code: ".$product_info['product_code']);
		
		$policy['total_premium'] = floatval($policy['total_premium']);
		$policy['rate_myself'] = floatval($policy['rate_myself']);
		 
		$smarty->assign('policy', $policy);
		$smarty->assign('user_info_applicant', $user_info_applicant);
		$smarty->assign('list_subject', $list_subject);
		
		//attribute_type 
		$smarty->assign('product_code',$product_info['product_code']);
		$smarty->assign('insurer_code',$policy['insurer_code']);
		 
	}        

	
    $smarty->display('user_transaction.dwt');
}

else if($action=='upload_policy_attachment')
{
	include_once(ROOT_PATH . 'baoxian/source/function_baoxian_ssbq.php');
	$policy_id = isset($_REQUEST['policy_id'])?intval($_REQUEST['policy_id']):0;
	
	if(!$policy_id)
	{
		 show_message("错误，请重试！");
	}
	process_upload_file(array('policy_id'=>$policy_id),'insurance_policy');
	
	header("location: user.php?act=policy_detail&policy_id=$policy_id");
	
	exit;
}
/* *
 * 2014/8/6
 * bhz
 * 保单修改界面 
 */
elseif($action == 'policy_edit')
{
    include_once(ROOT_PATH . 'includes/lib_transaction.php');
    $policy_id = isset($_GET['policy_id']) ? intval($_GET['policy_id']) : 0;

    $sql="select * from t_insurance_policy where policy_id=$policy_id  ";

    $policy = $db->getRow($sql);
   
    $smarty->assign('policy', $policy);
    /***
     * 2014/7/29
     * bhz
     * 分配产品分类数据
     */

    $smarty->assign('categories', $categories);
    $smarty->display('user_transaction_policy_edit.dwt');
}
//add yes123 2015-01-08 导出保单
elseif ($action == 'export_policy_list')
{
	//标题
	$title = array ('保单号','出单员','保险公司','险种','保费','出单日期','保险起期','保险止期','投保人','被保险人');
	export_policy_list($title,"#ff6600","#000000");
	exit;
}

//add yes123 2015-04-02 通过保单号列表查询保单
elseif ($action == 'checkByPolicyIds')
{
	$ids = $_REQUEST['ids'];	
	//判断是否申请过
	$sql="select policy_id from bx_invoice_order_policy where policy_id IN($ids) ";
	$policy_ids = $db->getAll($sql);
	if(!empty($policy_ids))
	{
		$msg="保单ID为".$policy_ids[0]['policy_id']."的保单已申请过发票，不能重复申请！";
		$datas = json_encode(array('code'=>2,'msg'=>$msg));
    	die($datas);
	}
	
	
	
	//判断是否属于同一个保险公司
	$sql="select DISTINCT insurer_code from t_insurance_policy where policy_id IN($ids) ";
    $insurer_code = $db->getAll($sql);
    if(count($insurer_code)>1)
    {
    	$datas = json_encode(array('code'=>2,'msg'=>'您选择的保单所属保险公司不一致，请重新选择'));
    	die($datas);
    }
    
	//判断是否已投保
	$no_insure_ids = "";
	$sql="select policy_id,policy_status from t_insurance_policy where policy_id IN($ids) ";
    $policy_status_list = $db->getAll($sql);
	foreach ($policy_status_list as $key => $value ) {
       if($value['policy_status']!='insured')
       {
       		$no_insure_ids.=$value['policy_id'].",";
       }
	}
	if($no_insure_ids)
	{
		$datas = json_encode(array('code'=>2,'msg'=>'保单号为：'.$no_insure_ids."没有投保，无法申请发票"));
    	die($datas);
	}
	
	//判断是否属于同一个代理人
/*	$sql="select DISTINCT applicant_uid from t_insurance_policy where policy_id IN($ids) ";
    $applicant_uid = $db->getAll($sql);
    if(count($applicant_uid)>1)
    {
    	$datas = json_encode(array('code'=>2,'msg'=>'您选择的保单所属投保人不一致，请重新选择'));
    	die($datas);
    }*/
    
    $datas = json_encode(array('code'=>0,'msg'=>'success'));
 	die($datas);	
}


/* 注册会员的处理 ,B端注册或者活动注册*/
elseif ($action == 'act_register')
{

	include_once(ROOT_PATH . 'includes/class/User.class.php');
    $user_obj = new User;
	$res = $user_obj->userRegister();
	exit;
	
}
/* 验证用户注册邮件 */
elseif ($action == 'validate_email')
{
    $hash = empty($_GET['hash']) ? '' : trim($_GET['hash']);
    if ($hash)
    {
        include_once(ROOT_PATH . 'includes/lib_passport.php');
        $id = register_hash('decode', $hash);
        if ($id > 0)
        {
            $sql = "UPDATE " . $ecs->table('users') . " SET is_validated = 1 WHERE user_id='$id'";
            $db->query($sql);
            $sql = 'SELECT user_name, email FROM ' . $ecs->table('users') . " WHERE user_id = '$id'";
            $row = $db->getRow($sql);
            show_message(sprintf($_LANG['validate_ok'], $row['user_name'], $row['email']),$_LANG['profile_lnk'], 'user.php');
        }
    }
    show_message($_LANG['validate_fail']);
}

/* add yes123 2014-12-29 更换手机号码 */
elseif ($action == 'change_phone')
{
	
	
	$username = isset($_REQUEST['username'])?trim($_REQUEST['username']):'';
	$checkCode = isset($_REQUEST['checkCode'])?trim($_REQUEST['checkCode']):'';
	
	//查询保存的验证码
	$check_res = check_code($username,$checkCode,CHANGE_PHONE);
	if($check_res['code']!=CHECK_CODE_OK)
	{
		$datas = json_encode(array('code'=>1,'msg'=>$check_res['msg']));
	 	die($datas);
	}
	
    $sql = "UPDATE bx_users SET user_name='$username',mobile_phone='$username' WHERE user_id = ".$_SESSION['user_id'];
    $res= $GLOBALS['db']->query($sql);	
    
    if($res){
    	$datas = json_encode(array('code'=>0,'msg'=>'更新成功！'));
	 	die($datas);
    }else{
        $datas = json_encode(array('code'=>1,'msg'=>'更新失败！'));
	 	die($datas);
    }
	
}
/* add yes123 2014-12-29 更换手机号码 */
elseif ($action == 'binding_phone')
{
	
	$username = isset($_REQUEST['username'])?trim($_REQUEST['username']):'';
	$checkCode = isset($_REQUEST['checkCode'])?trim($_REQUEST['checkCode']):'';
	
	//查询保存的验证码
	$check_res = check_code($username,$checkCode,BINDING_PHONE_CODE_SMS);
	if($check_res['code']!=CHECK_CODE_OK)
	{
		$datas = json_encode(array('code'=>1,'msg'=>$check_res['msg']));
	 	die($datas);
	}
	
	
	$sql="SELECT user_id FROM ".$GLOBALS['ecs']->table('users'). " WHERE mobile_phone= '".$username."'"; 
	$res = $GLOBALS['db']->getOne($sql);
	if($res)
	{
		$datas = json_encode(array('code'=>1,'msg'=>'手机号码已存在'));
	 	die($datas);
		
	}
	
    $sql = "UPDATE bx_users SET mobile_phone='$username' WHERE user_id = ".$_SESSION['user_id'];
    $res= $GLOBALS['db']->query($sql);	
    
    if($res){
    	$datas = json_encode(array('code'=>0,'msg'=>'绑定成功！'));
	 	die($datas);
    }else{
        $datas = json_encode(array('code'=>1,'msg'=>'绑定失败！'));
	 	die($datas);
    }
	
}
/* 验证用户邮箱地址是否被注册 */
elseif($action == 'check_email')
{
    $email = trim($_GET['email']);
    if ($user->check_email($email))
    {
        echo 'false';
    }
    else
    {
        echo 'ok';
    }
}
/* 用户登录界面获取，不是传值，而是根据本文件开头对session的判断来决定的 */
elseif ($action == 'login')
{
	
    if (empty($back_act))
    {
        if (empty($back_act) && isset($GLOBALS['_SERVER']['HTTP_REFERER']))
        {
            $back_act = strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'user.php') ? './index.php' : $GLOBALS['_SERVER']['HTTP_REFERER'];
        }
        else
        {
            $back_act = 'user.php';
        }

    }


    $captcha = intval($_CFG['captcha']);
    if (($captcha & CAPTCHA_LOGIN) && (!($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && $_SESSION['login_fail'] > 2)) && gd_version() > 0)
    {
        $GLOBALS['smarty']->assign('enabled_captcha', 1);
        $GLOBALS['smarty']->assign('rand', mt_rand());
    }
	$smarty->assign('categories',   $categories); // 总分类树
    $smarty->assign('back_act', $back_act);
    
    //add by dingchaoyang 2014-12-12
    //响应json数据到客户端
    include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    EbaAdapter::responseData('Eba_SessionInvalid');
    //end add by dingchaoyang 2014-12-12
    
    $smarty->display('login.dwt');//登录页面的模板
    
    
}

/* 处理会员的登录 动作*/
elseif ($action == 'act_login')
{
	$username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $back_act = isset($_POST['back_act']) ? trim($_POST['back_act']) : '';
    $is_ajax =  isset($_POST['is_ajax'])  ? intval($_POST['is_ajax']) : 0;
    
    $res = array();
    $res['code'] = 1;
    //add by dingchaoyang 2014-11-26
    //如果来自移动端，不判断。移动端登录 没有验证码
    include_once (ROOT_PATH . 'api/EBaoApp/platformEnvironment.class.php');
    if (!(PlatformEnvironment::isMobilePlatform())){
        $captcha = intval($_CFG['captcha']);
	    if (($captcha & CAPTCHA_LOGIN) && (!($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && $_SESSION['login_fail'] > 2)) && gd_version() > 0)
	    {
	        if (empty($_POST['captcha']))
	        {
	        	if($is_ajax)
	        	{
	        		$res['code'] = 2;
	        		$res['msg'] = $_LANG['invalid_captcha'];
	        		die(json_encode($res));
	        	}
	        	else
	        	{
		            show_message($_LANG['invalid_captcha'], $_LANG['relogin_lnk'], 'user.php', 'error');
	        	}
	        }
	
	        /* 检查验证码 */
	        include_once('includes/cls_captcha.php');
	
	        $validator = new captcha();
	        $validator->session_word = 'captcha_login';
	        if (!$validator->check_word($_POST['captcha']))
	        {
	        	if($is_ajax)
	        	{
	        		$res['code'] = 2;
	        		$res['msg'] = $_LANG['invalid_captcha'];
	        		die(json_encode($res));
	        	}
	        	else
	        	{
		            show_message($_LANG['invalid_captcha'], $_LANG['relogin_lnk'], 'user.php', 'error');
	        	}
	        }
	    }
    }

    if (PlatformEnvironment::isMobilePlatform()){
    	$sql = "SELECT user_id, password, salt,ec_salt " .
    			" FROM bx_users" .
    			" WHERE user_name='$username' OR mobile_phone='$username' OR email='$username'";
    	 
    	$row = $db->getRow($sql);
    	if (!($row['user_id']))
    	{
	        include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
	        EbaAdapter::responseData(LoginNoUser);
    	}
    }
    
    if ($user->login($username, $password,isset($_POST['remember'])))
    {
        update_user_info();
        recalculate_price();
        
        //add yes123 2015-06-23渠道个性化设置 
       	set_config();
        
        //add by dingchaoyang 2014-11-4
        //响应json数据到客户端
        include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
        EbaAdapter::responseData(LoginSuccess);
		//end add by dingchaoyang 2014-11-4
        $ucdata = isset($user->ucdata)? $user->ucdata : '';
        
        include_once(ROOT_PATH . 'includes/lib_user.php');
        $smarty->assign('new_affiche',   get_new_affiche());
        
        //判断是注册还是添加用户
        $sql = "SELECT user_source FROM bx_users  WHERE user_id=$_SESSION[user_id] ";
        $user_source = $db->getOne($sql);
        

        if($user_source)
        {
        	$msg = "登录成功，您的账户有安全隐患，强烈建议修改密码";
        	if($is_ajax)
	    	{
	    		$res['msg'] = $msg;
	    		die(json_encode($res));
	    	}
        	else
        	{
	        	show_message($msg,'', 'default.php', 'info');
        		
        	}
        }
		else
		{
			if($is_ajax)
	    	{
	    		$res['msg'] = $_LANG['login_success'];
	    		die(json_encode($res));
	    	}
	    	else
	    	{
				$ucdata = isset($user->ucdata)? $user->ucdata : '';
				
				header("location: default.php");
				exit;
	  			//show_message($_LANG['login_success'] . $ucdata , array($_LANG['back_up_page'], $_LANG['profile_lnk']), array($back_act,'index.php'), 'info');
	    	}
		}
		
		        
    }
    else
    {
        //add by dingchaoyang 2014-11-4
        //响应json数据到客户端
        include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
        EbaAdapter::responseData(LoginFail);
		//end add by dingchaoyang 2014-11-4
		
		if($is_ajax)
    	{
    		$res['code'] = 2;
    		$res['msg'] = $_LANG['login_failure'];
    		die(json_encode($res));
    	}
		else
		{
    		$_SESSION['login_fail'] ++ ;
        	show_message($_LANG['login_failure'], $_LANG['relogin_lnk'], 'user.php', 'error');
		}
    }
}

/* 处理 ajax 的登录请求 */
elseif ($action == 'signin')
{
    include_once('includes/cls_json.php');
    $json = new JSON;

    $username = !empty($_POST['username']) ? json_str_iconv(trim($_POST['username'])) : '';
    $password = !empty($_POST['password']) ? trim($_POST['password']) : '';
    $captcha = !empty($_POST['captcha']) ? json_str_iconv(trim($_POST['captcha'])) : '';
    $result   = array('error' => 0, 'content' => '');

    $captcha = intval($_CFG['captcha']);
    if (($captcha & CAPTCHA_LOGIN) && (!($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && $_SESSION['login_fail'] > 2)) && gd_version() > 0)
    {
        if (empty($captcha))
        {
            $result['error']   = 1;
            $result['content'] = $_LANG['invalid_captcha'];
            die($json->encode($result));
        }

        /* 检查验证码 */
        include_once('includes/cls_captcha.php');

        $validator = new captcha();
        $validator->session_word = 'captcha_login';
        if (!$validator->check_word($_POST['captcha']))
        {

            $result['error']   = 1;
            $result['content'] = $_LANG['invalid_captcha'];
            die($json->encode($result));
        }
    }

    if ($user->login($username, $password))
    {
        update_user_info();  //更新用户信息
        recalculate_price(); // 重新计算购物车中的商品价格
        $smarty->assign('user_info', get_user_info());
        $ucdata = empty($user->ucdata)? "" : $user->ucdata;
        $result['ucdata'] = $ucdata;
        $result['content'] = $smarty->fetch('library/member_info.lbi');
    }
    else
    {
        $_SESSION['login_fail']++;
        if ($_SESSION['login_fail'] > 2)
        {
            $smarty->assign('enabled_captcha', 1);
            $result['html'] = $smarty->fetch('library/member_info.lbi');
        }
        $result['error']   = 1;
        $result['content'] = $_LANG['login_failure'];
    }
    die($json->encode($result));
}

/* 退出会员中心 */
elseif ($action == 'logout')
{
    if ((!isset($back_act)|| empty($back_act)) && isset($GLOBALS['_SERVER']['HTTP_REFERER']))
    {
        $back_act = strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'user.php') ? './index.php' : $GLOBALS['_SERVER']['HTTP_REFERER'];
    }

    $user->logout();
    //add by dingchaoyang 2014-11-27
    //响应json数据到客户端
    include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    EbaAdapter::responseData(EbaLogout);
    //end add by dingchaoyang 2014-11-27
    $ucdata = empty($user->ucdata)? "" : $user->ucdata;
    show_message($_LANG['logout'] . $ucdata, array($_LANG['back_up_page'], $_LANG['back_home_lnk']), array($back_act, 'index.php'), 'info');
}

/* 个人资料页面 */
elseif ($action == 'profile')
{
	
	ss_log("will into pc profile");
	
    include_once(ROOT_PATH . 'includes/lib_transaction.php');
    include_once(ROOT_PATH . 'includes/class/User.class.php');
    $user_obj = new User;
	$res = $user_obj->getProfile();
	$user_info = $res['user_info'];
	
	$extend_info_list = $res['extend_info_list'];
    
    //add by dingchaoyang 2014-11-7
    //响应json数据到客户端
    include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    EbaAdapter::responseProfile($user_info);
	//end add by dingchaoyang 2014-11-7
	
	if($user_info['check_status']==CHECKED_CHECK_STATUS)
	{
		$smarty->assign('province_name_my', $res['province_name_my']);
		$smarty->assign('city_name_my', $res['city_name_my']);
		$smarty->assign('district_name_my', $res['district_name_my']);
	}

	$smarty->assign('province_list', $res['province_list']);
	$smarty->assign('city_list', $res['city_list']);
	$smarty->assign('district_list', $res['district_list']);
   	
   	//获取封面列表
   	$sql = "SELECT * FROM ".$ecs->table('userbg_img')." ORDER BY show_order DESC,id DESC";
   	$userbg_img_list = $db->getAll($sql);
    $smarty->assign('userbg_img_list', $userbg_img_list);
    
    $smarty->assign('extend_info_list', $extend_info_list);
    
    $smarty->assign('profile', $user_info);
    $smarty->assign('zhengjian_type_list', $zhengjian_type_list);
	
	$smarty->assign('request_check', $_REQUEST['check']);
	
	
	//绑定手机验证码
    $smarty->assign('enabled_captcha', 1);
    $smarty->assign('rand',            mt_rand());
	
		
    $smarty->display('user_transaction.dwt');//个人中心
}
/* 帮助中心 */
elseif($action == 'option_help'){
	
	global $_LANG; 
	$smarty->assign('lang', $_LANG);  
	$smarty->assign('action', $action);  
	$smarty->display('user_transaction_option_help.dwt');
}
/* 修改个人资料的处理 */
elseif ($action == 'act_edit_profile')
{
	//modify  yes123 2015-01-06 编辑个人资料 微信和PC端代码抽取合并
	//$is_weixin = isset($_POST['is_weixin']) ? trim($_POST['is_weixin']) : 1;
	ss_log('is_weixin:'.$is_weixin);
    include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	//add yes123 2015-02-03 pc端也用ajax提交。和微信一样
    $user_obj = new User;
	$r = $user_obj->editProfile();
	if($r)
	{
		//add by dingchaoyang 2014-11-10
    	//响应json数据到客户端
    	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    	EbaAdapter::responseData('UpdateProfileSuccess');
    	//end add by dingchaoyang 2014-11-10
		
		
		if($is_weixin)
		{
			if($_POST['check_status']==PENDING_CHECK_STATUS)
			{
				die(json_encode(array('code'=>0,'msg'=>$_LANG['submit_check_success'])));
				
			}
			else
			{
				die(json_encode(array('code'=>0,'msg'=>$_LANG['edit_profile_success'])));
			}
			
		}//add yes123 2015-01-06 微信端提示

    	    	
    
    		//modify 提交审核和保存用户资料的提示不一致
    	if($_POST['check_status']==PENDING_CHECK_STATUS)
    	{
    		show_message($_LANG['submit_check_success'], $_LANG['profile_lnk'], 'user.php?act=profile', 'info');
    	}
    	else
    	{
    		show_message($_LANG['edit_profile_success'], $_LANG['profile_lnk'], 'user.php?act=profile', 'info');
    	}
    	
	}
	else
	{
		 //add by dingchaoyang 2014-11-10
        //响应json数据到客户端
        include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
        EbaAdapter::responseData('UpdateProfileFail');
        //end add by dingchaoyang 2014-11-10
        if($is_weixin)
		{
			die(json_encode(array('code'=>1,'msg'=>$_LANG['edit_profile_failed'])));
		}//add yes123 2015-01-06 微信端提示
        
        
        $msg = $_LANG['edit_profile_failed'];
        show_message($msg, '', '', 'info');
	}

}
elseif($action == 'change_userbg_img')
{
	$id = intval($_REQUEST['id']);
	if($id)
	{
		$sql = "SELECT id FROM " . $GLOBALS['ecs']->table('userbg_img') . "WHERE id='$id'";
		$id = $GLOBALS['db']->getOne($sql);
		if($id)
		{
			$data = array('bg_img'=>$id);
			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('users'), $data, "UPDATE", "user_id = $user_id");  
		}
		
	}
	
	header("Location: user.php");
	
	
}
elseif ($action == 'del_user_img')
{   
	include_once(ROOT_PATH . 'includes/class/imageUtils.class.php');
	$arr = ImageUtils::delImg(ROOT_PATH.$filename);
	echo json_encode($arr);
} 
//add yes123 201-12-02 处理图片的上传和删除  end

//add yes123 201-12-11 上传我的客户的图片
elseif ($action == 'upload_img')
{   
	$img_type = isset($_REQUEST['img_type'])?$_REQUEST['img_type']:1; //0头像,1为身份证正面,2为身份证反面  3为证书正面
	$max_size = $img_type==0?2048000:5120000;
	$path_type = $_REQUEST['path_type'];
	
	$path_arr = array(
		'1'=>'images/private/user_img',
		'2'=>'images/private/customers_img',
		'3'=>'images/private/payment_img',
	);
	
	$save_path = $path_arr[$path_type];
	
	
	include_once (ROOT_PATH . 'api/EBaoApp/platformEnvironment.class.php');
	
	$arr = upload_img($save_path,$max_size);

	//add by dingchaoyang 2014-11-8
	//响应json数据到客户端
	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
	EbaAdapter::responseUplaodImage($arr);
	//end add by dingchaoyang 2014-11-8

	die( json_encode($arr));
} 
//add yes123 201-12-11 上传文件
elseif ($action == 'upload_file')
{   
	include_once (ROOT_PATH . 'includes/class/FileUpload.class.php');
	$target = "attachment/insurance_policy_attachment_$_SESSION[user_id]";
	
	$upload_obj = new FileUpload;
	$res = $upload_obj->upload('attachment',$target);
	
	//$res = my_upload_file($upload,$target);
	$res['file'] = $target."/".$res['file'];

	die( json_encode($res));
} 


/* 密码找回-->修改密码界面 */
elseif ($action == 'get_password')
{
    include_once(ROOT_PATH . 'includes/lib_passport.php');
	
    $smarty->assign('enabled_captcha', 1);
    $smarty->assign('rand',            mt_rand());
	
    if (isset($_GET['code']) && isset($_GET['uid'])) //从邮件处获得的act
    {
        $code = trim($_GET['code']);
        $uid  = intval($_GET['uid']);

        /* 判断链接的合法性 */
        $user_info = $user->get_profile_by_id($uid);
        if (empty($user_info) || ($user_info && md5($user_info['user_id'] . $_CFG['hash_code'] . $user_info['reg_time']) != $code))
        {
            show_message($_LANG['parm_error'], $_LANG['back_home_lnk'], './', 'info');
        }

        $smarty->assign('uid',    $uid);
        $smarty->assign('code',   $code);
        $smarty->assign('action', 'reset_password');
        $smarty->display('get_password.dwt');
    }
    else
    {
        //显示用户名和email表单
        $smarty->display('get_password.dwt');
    }
}
/* 密码找回-->输入用户名界面 */
elseif ($action == 'qpassword_name')
{
    //显示输入要找回密码的账号表单
    $smarty->display('user_passport.dwt');
}
/* 密码找回-->根据注册用户名取得密码提示问题界面 */
elseif ($action == 'get_passwd_question')
{
    if (empty($_POST['user_name']))
    {
        show_message($_LANG['no_passwd_question'], $_LANG['back_home_lnk'], './', 'info');
    }
    else
    {
        $user_name = trim($_POST['user_name']);
    }

    //取出会员密码问题和答案
    $sql = 'SELECT user_id, user_name, passwd_question, passwd_answer FROM ' . $ecs->table('users') . " WHERE user_name = '" . $user_name . "'";
    $user_question_arr = $db->getRow($sql);

    //如果没有设置密码问题，给出错误提示
    if (empty($user_question_arr['passwd_answer']))
    {
        show_message($_LANG['no_passwd_question'], $_LANG['back_home_lnk'], './', 'info');
    }

    $_SESSION['temp_user'] = $user_question_arr['user_id'];  //设置临时用户，不具有有效身份
    $_SESSION['temp_user_name'] = $user_question_arr['user_name'];  //设置临时用户，不具有有效身份
    $_SESSION['passwd_answer'] = $user_question_arr['passwd_answer'];   //存储密码问题答案，减少一次数据库访问

    $captcha = intval($_CFG['captcha']);
    if (($captcha & CAPTCHA_LOGIN) && (!($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && $_SESSION['login_fail'] > 2)) && gd_version() > 0)
    {
        $GLOBALS['smarty']->assign('enabled_captcha', 1);
        $GLOBALS['smarty']->assign('rand', mt_rand());
    }

    $smarty->assign('passwd_question', $_LANG['passwd_questions'][$user_question_arr['passwd_question']]);
    $smarty->display('user_passport.dwt');
}
/* 密码找回-->根据提交的密码答案进行相应处理 */
elseif ($action == 'check_answer')
{
    $captcha = intval($_CFG['captcha']);
    if (($captcha & CAPTCHA_LOGIN) && (!($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && $_SESSION['login_fail'] > 2)) && gd_version() > 0)
    {
        if (empty($_POST['captcha']))
        {
            show_message($_LANG['invalid_captcha'], $_LANG['back_retry_answer'], 'user.php?act=qpassword_name', 'error');
        }

        /* 检查验证码 */
        include_once('includes/cls_captcha.php');

        $validator = new captcha();
        $validator->session_word = 'captcha_login';
        if (!$validator->check_word($_POST['captcha']))
        {
            show_message($_LANG['invalid_captcha'], $_LANG['back_retry_answer'], 'user.php?act=qpassword_name', 'error');
        }
    }

    if (empty($_POST['passwd_answer']) || $_POST['passwd_answer'] != $_SESSION['passwd_answer'])
    {
        show_message($_LANG['wrong_passwd_answer'], $_LANG['back_retry_answer'], 'user.php?act=qpassword_name', 'info');
    }
    else
    {
        $_SESSION['user_id'] = $_SESSION['temp_user'];
        $_SESSION['user_name'] = $_SESSION['temp_user_name'];
        unset($_SESSION['temp_user']);
        unset($_SESSION['temp_user_name']);
        $smarty->assign('uid',    $_SESSION['user_id']);
        $smarty->assign('action', 'reset_password');
        $smarty->display('user_passport.dwt');
    }
}
/* 发送密码修改确认邮件 */
elseif ($action == 'send_pwd_email')
{
    include_once(ROOT_PATH . 'includes/lib_passport.php');

    /* 初始化会员用户名和邮件地址 */
    $user_name = !empty($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $email     = !empty($_POST['email'])     ? trim($_POST['email'])     : '';

    //用户名和邮件地址是否匹配
    $user_info = $user->get_user_info($user_name);

    if ($user_info && $user_info['email'] == $email)
    {
        //生成code
         //$code = md5($user_info[0] . $user_info[1]);

        $code = md5($user_info['user_id'] . $_CFG['hash_code'] . $user_info['reg_time']);
        //发送邮件的函数
        if (send_pwd_email($user_info['user_id'], $user_name, $email, $code))
        {
        	//add by dingchaoyang 2014-11-7
        	//响应json数据到客户端
        	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
        	EbaAdapter::responseData('RetrievePasswordSuccess');
        	//end add by dingchaoyang 2014-11-7
        	show_message($_LANG['send_success'] . $email, $_LANG['back_home_lnk'], './', 'info');
        }
        else
        {
            //发送邮件出错
        	//add by dingchaoyang 2014-11-7
        	//响应json数据到客户端
        	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
        	EbaAdapter::responseData('RetrievePasswordFail');
        	//end add by dingchaoyang 2014-11-7
            show_message($_LANG['fail_send_password'], $_LANG['back_page_up'], './', 'info');
        }
    }
    else
    {
        //用户名与邮件地址不匹配
    	//add by dingchaoyang 2014-11-7
    	//响应json数据到客户端
    	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    	EbaAdapter::responseData('RetrievePasswordMatch');
    	//end add by dingchaoyang 2014-11-7
        show_message($_LANG['username_no_email'], $_LANG['back_page_up'], '', 'info');
    }
}

/* 重置新密码 */
elseif ($action == 'reset_password')
{
    //显示重置密码的表单
    $smarty->display('user_passport.dwt');
}

/* 修改会员密码 */
elseif ($action == 'act_edit_password')
{
    include_once(ROOT_PATH . 'includes/lib_passport.php');
    //modify yes123 2015-01-21 修改密码代码抽取，微信端用
	include_once(ROOT_PATH . 'includes/class/User.class.php');
    $user_obj = new User;
	$res = $user_obj->editPassword();	
	exit;
}


/* add by yes123 2014-11-28 通过短信验证码修改修改会员密码 */
elseif ($action == 'update_password_verification_code')
{
	
    include_once(ROOT_PATH . 'includes/lib_passport.php');
    $new_password = trim($_POST['password']);
    $username      = trim($_POST['username']);
    $checkCode      = trim($_POST['checkCode']);
	
	$check_res = check_code($username,$checkCode,RESET_PASSWORD);
	
	if($check_res['code']!=CHECK_CODE_OK)
	{
		show_message($check_res['msg'], '', 'user.php?act=get_password', 'error');
		
	}
    if (strlen($new_password) < 6)
    {
        //show_message($_LANG['passport_js']['password_shorter']);
        $check_res['msg']=$_LANG['passport_js']['password_shorter'];
        $check_res['code']=4;
        show_message($check_res['msg'], '', 'user.php?act=get_password', 'error');
        
    }
    
    
    $sql="SELECT user_id FROM ".$ecs->table('users'). " WHERE mobile_phone= '".$username."' OR user_name='$username'"; 
    ss_log('通过手机号码获取user_id sql:'.$sql);
	$user_id = $db->getOne($sql);
    if($user_id)
    {
		$sql="UPDATE ".$ecs->table('users'). "SET `ec_salt`='0',user_source=0,password = '".md5($new_password)."' WHERE user_id=".$user_id;
		ss_log('修改密码sql:'.$sql);
		$db->query($sql);
		$user->logout();
	    //show_message($_LANG['edit_password_success'], $_LANG['relogin_lnk'], 'user.php?act=login', 'info');	
	    $check_res['msg']=$_LANG['edit_password_success'];
        $check_res['code']=CHECK_CODE_OK;
        show_message($check_res['msg'], '', 'user.php?act=login', 'info');
    }
    else
    {
    	  $check_res['msg']='未绑定手机号码，请联系管理员！';
          $check_res['code']=204;
    	  show_message($check_res['msg'], '', 'user.php?act=get_password', 'error');
    }
    
}

/* 添加一个代金券 */
elseif ($action == 'act_add_bonus')
{
    include_once(ROOT_PATH . 'includes/lib_transaction.php');

    $bouns_sn = isset($_POST['bonus_sn']) ? intval($_POST['bonus_sn']) : '';

    if (add_bonus($user_id, $bouns_sn))
    {
        show_message($_LANG['add_bonus_sucess'], $_LANG['back_up_page'], 'user.php?act=bonus', 'info');
    }
    else
    {
        $err->show($_LANG['back_up_page'], 'user.php?act=bonus');
    }
}
/* 查看订单列表 */
elseif ($action == 'order_list')
{	
	//订单条件 br:yes123,20140906 

    include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
    $user_obj = new User;
	$res = $user_obj->getOrderList();
	$payment = $res['payment'];
	$orders = $res['orders'];
	$condition = $res['condition'];
	$record_count = $res['record_count'];
	$pager = $res['pager'];
	$order_tatol_amount = $res['order_tatol_amount'];
	
    $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
    //add by dingchaoyang 2014-11-27
    //响应json数据到客户端
//     print_r(iconv('utf-8', 'gb2312//ignore', json_encode($orders)));
    include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    if ($page > intval($pager['page_count'])){
    	EbaAdapter::responseOrderList('2');
    }else{
    	EbaAdapter::responseOrderList($orders);
    } 
    //end add by dingchaoyang 2014-11-27
    foreach ($orders as $key => $order )
    {
        $orders[$key]['warranty_no'] =!empty($order['policy_id'])?get_warranty_no($order['policy_id']):"";
     
    }
    
    $smarty->assign('order_tatol_amount',  $order_tatol_amount);

    $smarty->assign('payment',  $payment);
    $smarty->assign('pager',  $pager);
    $smarty->assign('orders', $orders);
	/***
	 * 2014/7/29
	 * bhz
	 * 分配产品分类数据
	 */
	//echo "<pre>";print_r($condition);
	$smarty->assign('categories', $categories);
	$smarty->assign('recent', $_REQUEST['recent']); //2014/11/19 add @ liuhui
	$smarty->assign('condition', $condition);
    $smarty->display('user_transaction.dwt');
}

//add 获取支付宝网页支付的payurl by dingchaoyang 2014-12-27
elseif ($action == 'act_pay_wap'){
	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
	$out_trade_no = trim($_REQUEST['out_trade_no']);
	$orderid = explode('-',$out_trade_no);
	$order['order_sn'] = $orderid[0];
	$log_id = $orderid[1];
	if (empty($log_id)){
		//告知app 不能支付
		EbaAdapter::responsePaymentUrl('');
	}else {
		//获取订单金额
		$sql = "select * from bx_order_info where order_sn='".$order['order_sn']."'";
		$set = $GLOBALS['db']->getRow($sql);
		if ($set){
			$order['log_id'] = $log_id;
			$order['order_amount'] = $set['goods_amount'] - $set['surplus'];
			include_once (ROOT_PATH . 'includes/modules/payment/alipaywap/alipayapi.php');
			$payObj = new AlipayAPI();
			$payObj->setParams($order);
			$payUrl = $payObj->getPayUrl();
			//add by dingchaoyang 2014-12-27
			//响应json数据到客户端
			EbaAdapter::responsePaymentUrl($payUrl);
			//end add by dingchaoyang 2014-12-27
		}else{
			//告知app，不能支付
			EbaAdapter::responsePaymentUrl('');
		}

	}
	
}
//end

/* 查看订单详情 */
elseif ($action == 'order_detail')
{
    include_once(ROOT_PATH . 'includes/lib_transaction.php');
    include_once(ROOT_PATH . 'includes/lib_payment.php');
    include_once(ROOT_PATH . 'includes/lib_order.php');
    include_once(ROOT_PATH . 'includes/lib_clips.php');

    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

    /* 订单详情 */
    $order = get_order_detail($order_id, $user_id);
    if ($order === false)
    {
        $err->show($_LANG['back_home_lnk'], './');

        exit;
    }

    /* 是否显示添加到购物车 */
    if ($order['extension_code'] != 'group_buy' && $order['extension_code'] != 'exchange_goods')
    {
        $smarty->assign('allow_to_cart', 1);
    }

    /* 订单商品 */
    $goods_list = order_goods($order_id);
    foreach ($goods_list AS $key => $value)
    {
        $goods_list[$key]['market_price'] = price_format($value['market_price'], false);
        $goods_list[$key]['goods_price']  = price_format($value['goods_price'], false);
        $goods_list[$key]['subtotal']     = price_format($value['subtotal'], false);
    }

     /* 设置能否修改使用余额数 */
    if ($order['order_amount'] > 0)
    {
        if ($order['order_status'] == OS_UNCONFIRMED || $order['order_status'] == OS_CONFIRMED)
        {
            $user = user_info($order['user_id']);
         
            if ($user['user_money'] + $user['credit_line'] > 0)
            {
                $smarty->assign('allow_edit_surplus', 1);
                $smarty->assign('max_surplus', sprintf($_LANG['max_surplus'], $user['user_money']));
            }
            
            /* add yes123 2015-07-07 设置能否修改使用金币数 */
            if ($user['service_money']> 0)
            {
                $smarty->assign('allow_edit_service_money', 1);
                $smarty->assign('max_service_money', sprintf($_LANG['max_surplus'], $user['service_money']));
            }
            
        }
    }


   


    /* 未发货，未付款时允许更换支付方式 */
    if ($order['order_amount'] > 0 && $order['pay_status'] == PS_UNPAYED && $order['shipping_status'] == SS_UNSHIPPED)
    {
        $payment_list = available_payment_list(false, 0);//modify yes123 2014-12 30获取其他支付方式

        /* 过滤掉当前支付方式和余额支付方式 */
        if(is_array($payment_list))
        {
            foreach ($payment_list as $key => $payment)
            {
                if ($payment['pay_id'] == $order['pay_id'] || $payment['pay_code'] == 'balance')
                {
                    unset($payment_list[$key]);
                }
            }
        }
        $smarty->assign('payment_list', $payment_list);
    }

    /* 订单 支付 配送 状态语言项 */
    $order['order_status'] = $_LANG['os'][$order['order_status']];
    $order['pay_status'] = $_LANG['ps'][$order['pay_status']];
    $order['shipping_status'] = $_LANG['ss'][$order['shipping_status']];
    $smarty->assign('categories', $categories);
    $smarty->assign('goods_list', $goods_list);

    
    //start by wangcya, for bug[193],能够支持多人批量投保
    $where_sql=" WHERE p.order_id=".$order_id;//add by wangcya, for bug[193],能够支持多人批量投保，
    ss_log($where_sql);
  //  $warranty = get_user_policy($user_id,50, 0,$where_sql);
  
  	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
	$_REQUEST['from']='order_info';
	$res = $user_obj->getPolicyList();
	$warranty = $res['policy_list'];
	
	foreach ( $warranty as $pkey => $policy ) {
       if($policy['insurer_code']=='SSBQ')
       {
       		$order['type'] = 'third';
       		$order['insurer_code'] = 'SSBQ';
       		break;
       }
	}
	
	
   	$record_count = $res['record_count'];
	$pager = $res['pager'];

    //重新建立pager  订单下的保单分页用的
    $temp_condition = array();
    $temp_condition['act'] = 'order_detail';
    $temp_condition['order_id'] = $order_id;
    $pager  = get_pager('user.php', $temp_condition, $pager['record_count'], $pager['page'],$pager['size']);
     $smarty->assign('order',      $order);
	$smarty->assign('pager',  $pager);

    
   
    //////////////////////////////////////////////////////
    $smarty->assign('warranty', $warranty);
    $smarty->assign('nosearch', 1);//不显示搜索
  	
  	
    
    $smarty->display('user_transaction.dwt');
}//order_detail

/* 取消订单 */
elseif ($action == 'cancel_order')
{			
    include_once(ROOT_PATH . 'includes/lib_transaction.php');
    include_once(ROOT_PATH . 'includes/lib_order.php');

    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

    if (cancel_order($order_id, $user_id))
    {
    	//add by dingchaoyang 2014-12-2
    	//响应json数据到客户端
    	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    	EbaAdapter::responseCancelOrder('0');
    	//end add by dingchaoyang 2014-12-2
    	ecs_header("Location: user.php?act=order_list\n");
        exit;
    }
    else
    {
    	//add by dingchaoyang 2014-12-2
    	//响应json数据到客户端
    	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    	EbaAdapter::responseCancelOrder('1');
    	//end add by dingchaoyang 2014-12-2
        $err->show($_LANG['order_list_lnk'], 'user.php?act=order_list');
    }
}


/**2014-9-18 yes123
 * 保存发票信息
 */
elseif ($action == 'save_fp')
{
	include_once (ROOT_PATH . 'includes/class/invoice.class.php');
	include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
	    $invoice=new Invoice;
		$invoice->fp_title = trim($_REQUEST['fp_title']);
		$invoice->username = trim($_REQUEST['username']);
		$invoice->tel = trim($_REQUEST['tel']);
		$invoice->phone = trim($_REQUEST['phone']);
		$invoice->address = trim($_REQUEST['address']);
		$invoice->zonecode = trim($_REQUEST['zonecode']); 
		$invoice->postscript = trim($_REQUEST['postscript']);
		$invoice->policy_ids = CommonUtils::trimAll($_REQUEST['policy_ids']);
		$invoice->invoice_id = trim($_REQUEST['invoice_id']);
		if($invoice->saveOrEditInvoice())
		{
			//add by dingchaoyang 2014-12-8
			//响应json数据到客户端
			include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
			EbaAdapter::responseData('Eba_ApplyInvoiceSuccess');
			//end add by dingchaoyang 2014-12-8
			ecs_header("Location:user.php?act=invoice_list\n");
		}else{
			//add by dingchaoyang 2014-12-8
			//响应json数据到客户端
			include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
			EbaAdapter::responseData('Eba_ApplyInvoiceFail');
			//end add by dingchaoyang 2014-12-8
			ecs_header("Location:user.php?act=invoice_list\n");
		}
		
		
}
/* 发票列表*/
elseif ($action == 'invoice_list')
{
	include_once (ROOT_PATH . 'includes/class/invoice.class.php');
	$invoice=new Invoice();
	$invoice->fp_title = trim($_REQUEST['fp_title']);
	$invoice->username = trim($_REQUEST['recipient']);
	$invoice->tel = trim($_REQUEST['tel']);
	$invoice->phone = trim($_REQUEST['phone']);
	$invoice->address = trim($_REQUEST['address']);
	$invoice->zonecode = trim($_REQUEST['zonecode']); 
	$invoice->postscript = trim($_REQUEST['postscript']);
	$invoice->start_time = trim($_REQUEST['start_time']);
	$invoice->end_time = trim($_REQUEST['end_time']);
	$invoice->order_sn = trim($_REQUEST['order_sn']);
	$invoice->user_id = $_SESSION['user_id'];
	
	//拼接条件
	$condition = $invoice->whereCondition();
	$condition['act']=$action; //add yes123 2014-12-08 分页时用到
	
	$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
	$invoice->start = ($page - 1) * 10;  //modify yes123 2014-12-08 分页修正
	$invoice->page_size = 10;

	
	$total_count =$invoice->getInvoiceCount();
    $pager  = get_pager('user.php', $condition, $total_count, $page);
    
	$invoice_list = $invoice->getInvoiceList();

	//add by dingchaoyang 2014-12-3
	//响应json数据到客户端
//  	print_r(iconv('utf-8', 'gb2312//ignore', json_encode($invoice_list) ));
	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
	if ($page > intval($pager['page_count'])){
		EbaAdapter::responseInvoiceList('2');
	}else{
		EbaAdapter::responseInvoiceList($invoice_list);
	}
	//end add by dingchaoyang 2014-12-3
	
	$smarty->assign('pager',  $pager);
	$smarty->assign('condition', $condition);
	$smarty->assign('invoice_list', $invoice_list);
	$smarty->display('user_transaction_invoice_list.dwt');
}//invoice_list

/**2014-11-20 yes123
 * 通过发票ID查询发票
 */
elseif ($action == 'findReceiptById')
{	
	include_once (ROOT_PATH . 'includes/class/invoice.class.php');
	$invoice = Invoice::getInvoiceById($_REQUEST['receipt_id']);
	
	die(json_encode($invoice));
}
/* 收货地址列表界面*/
elseif ($action == 'address_list')
{
    include_once(ROOT_PATH . 'includes/lib_transaction.php');
    include_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/shopping_flow.php');
    $smarty->assign('lang',  $_LANG);

    /* 取得国家列表、商店所在国家、商店所在国家的省列表 */
    $smarty->assign('country_list',       get_regions());
    $smarty->assign('shop_province_list', get_regions(1, $_CFG['shop_country']));

    /* 获得用户所有的收货人信息 */
    $consignee_list = get_consignee_list($_SESSION['user_id']);

    if (count($consignee_list) < 5 && $_SESSION['user_id'] > 0)
    {
        /* 如果用户收货人信息的总数小于5 则增加一个新的收货人信息 */
        $consignee_list[] = array('country' => $_CFG['shop_country'], 'email' => isset($_SESSION['email']) ? $_SESSION['email'] : '');
    }

    $smarty->assign('consignee_list', $consignee_list);

    //取得国家列表，如果有收货人列表，取得省市区列表
    foreach ($consignee_list AS $region_id => $consignee)
    {
        $consignee['country']  = isset($consignee['country'])  ? intval($consignee['country'])  : 0;
        $consignee['province'] = isset($consignee['province']) ? intval($consignee['province']) : 0;
        $consignee['city']     = isset($consignee['city'])     ? intval($consignee['city'])     : 0;

        $province_list[$region_id] = get_regions(1, $consignee['country']);
        $city_list[$region_id]     = get_regions(2, $consignee['province']);
        $district_list[$region_id] = get_regions(3, $consignee['city']);
    }

    /* 获取默认收货ID */
    $address_id  = $db->getOne("SELECT address_id FROM " .$ecs->table('users'). " WHERE user_id='$user_id'");

    //赋值于模板
    $smarty->assign('real_goods_count', 1);
    $smarty->assign('shop_country',     $_CFG['shop_country']);
    $smarty->assign('shop_province',    get_regions(1, $_CFG['shop_country']));
    $smarty->assign('province_list',    $province_list);
    $smarty->assign('address',          $address_id);
    $smarty->assign('city_list',        $city_list);
    $smarty->assign('district_list',    $district_list);
    $smarty->assign('currency_format',  $_CFG['currency_format']);
    $smarty->assign('integral_scale',   $_CFG['integral_scale']);
    $smarty->assign('name_of_region',   array($_CFG['name_of_region_1'], $_CFG['name_of_region_2'], $_CFG['name_of_region_3'], $_CFG['name_of_region_4']));
    /***
	 * 2014/7/29
	 * bhz
	 * 分配产品分类数据
	 */
	$smarty->assign('categories', $categories);
	
    $smarty->display('user_transaction_address_list.dwt');
}//address_list

/* 添加/编辑收货地址的处理 */
elseif ($action == 'act_edit_address')
{
    include_once(ROOT_PATH . 'includes/lib_transaction.php');
    include_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/shopping_flow.php');
    $smarty->assign('lang', $_LANG);

    $address = array(
        'user_id'    => $user_id,
        'address_id' => intval($_POST['address_id']),
        'country'    => isset($_POST['country'])   ? intval($_POST['country'])  : 0,
        'province'   => isset($_POST['province'])  ? intval($_POST['province']) : 0,
        'city'       => isset($_POST['city'])      ? intval($_POST['city'])     : 0,
        'district'   => isset($_POST['district'])  ? intval($_POST['district']) : 0,
        'address'    => isset($_POST['address'])   ? compile_str(trim($_POST['address']))    : '',
        'consignee'  => isset($_POST['consignee']) ? compile_str(trim($_POST['consignee']))  : '',
        'email'      => isset($_POST['email'])     ? compile_str(trim($_POST['email']))      : '',
        'tel'        => isset($_POST['tel'])       ? compile_str(make_semiangle(trim($_POST['tel']))) : '',
        'mobile'     => isset($_POST['mobile'])    ? compile_str(make_semiangle(trim($_POST['mobile']))) : '',
        'best_time'  => isset($_POST['best_time']) ? compile_str(trim($_POST['best_time']))  : '',
        'sign_building' => isset($_POST['sign_building']) ? compile_str(trim($_POST['sign_building'])) : '',
        'zipcode'       => isset($_POST['zipcode'])       ? compile_str(make_semiangle(trim($_POST['zipcode']))) : '',
        );

    if (update_address($address))
    {
        show_message($_LANG['edit_address_success'], $_LANG['address_list_lnk'], 'user.php?act=address_list');
    }
}

/**
 * add yes123 2014-12-08  我的客户列表
 * 
 * */
elseif ($action == 'customer_list')
{			
	include_once (ROOT_PATH . 'includes/class/Customer.class.php');
	$customer=new Customer();
	$customer->agent_uid = $_SESSION['user_id'];
	$condition['type']=$_REQUEST['type'];
	//拼接条件
	$condition = $customer->whereCondition($condition['type']);
	$condition['act']=$action;
	
	
	$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
	$page = $page==0?1:$page; //add yes123 2014-12-09防止用户输入0报错
	$customer->start = ($page - 1) * 10;
	$customer->page_size = 10;
	
	//modify yes123 2014-12-16
	if($condition['type']==1){ //渠道
		$total_count =$customer->getOrganizationCustomerCount();
	 	$customer_list = $customer->getOrganizationCustomerList();
	}
	else if($condition['type']==0) //个人
	{
		$total_count =$customer->getCustomerCount();
	 	$customer_list = $customer->getCustomerList();
	}
	
	$pager  = get_pager('user.php', $condition, $total_count, $page);
	$c_type = $customer->getCertificatesType();
	//add by dingchaoyang 2014-12-13
	//响应json数据到客户端
// print_r(iconv('utf-8', 'gb2312//ignore', json_encode($customer_list)));
	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
	if ($page > intval($pager['page_count'])){
		EbaAdapter::responseCustomerList('2');
	}else{
		EbaAdapter::responseCustomerList($customer_list);
	}
	//end add by dingchaoyang 2014-12-13
	
	
	//获取省份列表，添加我的客户时用到
	$sql = "SELECT region_id, region_name FROM " . $GLOBALS['ecs']->table('region') . " WHERE parent_id = 1"; 
	$province_list = $GLOBALS['db']->getAll($sql);
	$smarty->assign('province_list',  $province_list);
	
	//echo "<pre>";print_r($province_list);
	
	$smarty->assign('pager',  $pager);
	$smarty->assign('condition', $condition);
	$smarty->assign('c_type_list', $c_type);
	$smarty->assign('customer_list', $customer_list);
	$smarty->display('user_transaction_customer_list.dwt');
	
}//customer_list
/**
 * add yes123 2014-12-09 获取客户信息
 * 
 * */
elseif ($action == 'getCusById')
{			
	include_once (ROOT_PATH . 'includes/class/Customer.class.php');
	$customer=new Customer();
	$customer->agent_uid = $_SESSION['user_id'];
	$type=$_REQUEST['type']; //渠道1  个人0
	$cus_id = $_REQUEST['cus_id'];
	if($cus_id)
	{
		if($type==1) //渠道
		{
			$cus_info = $customer->getOrganizationCusById($cus_id);
			$c_type = $customer->getOrgCertificatesType(); //add yes123 2014-12-15 证件类型
		}
		else if($type==0) //个人
		{
			$cus_info = $customer->getCusById($cus_id);
			$city = $customer->getCityListByPcode($cus_info['province_code']);
			$c_type = $customer->getCertificatesType(); //add yes123 2014-12-15 证件类型
		}
		
		$data=array('code'=>2,'city'=>$city,'cus_info'=>$cus_info,'c_type'=>$c_type);
		die(json_encode($data));
	}

}
// add yes123 2014-12-19 新增客户机获取证件类型
elseif ($action == 'get_certificates_type')
{	
	include_once (ROOT_PATH . 'includes/class/Customer.class.php');
	$customer=new Customer();
	ss_log("waiian:");
	$type=$_REQUEST['type']; //渠道1  个人0
	if($type==1) //渠道
	{
		$c_type = $customer->getOrgCertificatesType();
		ss_log("渠道");
	}
	else if($type==0)
	{
		ss_log("个人");
		$c_type = $customer->getCertificatesType();
	}
			
			
	$data=array('code'=>0,'c_type'=>$c_type);
	die(json_encode($data));
	
}

// add yes123 2014-12-19 获取地区
elseif ($action == 'get_region')
{	
	include_once (ROOT_PATH . 'includes/class/Customer.class.php');
	$customer=new Customer();
	$p_code = trim($_REQUEST['p_code']);
	$region = $customer->getCityListByPcode($p_code);
	$data=array('code'=>1,'region'=>$region);
	die(json_encode($data));
	
}

/**
 * add yes123 2014-12-09 获取客户信息
 * 
 * */
elseif ($action == 'add_customer')
{	
	$type=$_REQUEST['type']; //渠道1  个人0		
	include_once (ROOT_PATH . 'includes/class/Customer.class.php');
	$customer=new Customer();
	
	if($type==1) //渠道
	{
		$uid = $customer->saveOrganizationCustomer($_SESSION['user_id']);
	}
	else if($type==0)
	{
		$uid = $customer->saveCustomer($_SESSION['user_id']);
	}
	
	//add by dingchaoyang 2014-12-13
	//响应json数据到客户端
	// print_r(iconv('utf-8', 'gb2312//ignore', json_encode($customer_list)));
	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
	if ($uid){
		EbaAdapter::responseData('Eba_CustomerAddSuccess');
	}else{
		EbaAdapter::responseData('Eba_CustomerAddFail');
	}
	//end add by dingchaoyang 2014-12-13
	if($type==1) //渠道
	{
		header("Location: user.php?act=customer_list&type=1"); 
	}
	else if($type==0)
	{
		header("Location: user.php?act=customer_list"); 
		
	}
	
	exit;
}

/**
 * add yes123 2014-12-12 删除客户
 * 
 * */
elseif ($action == 'del_customer')
{	
	$type=$_REQUEST['type']; //渠道1  个人0				
	include_once (ROOT_PATH . 'includes/class/Customer.class.php');
	$customer=new Customer();
	
	if($type==1) //渠道
	{
		$r = $customer->delOrganizationCustomer($_REQUEST['cus_id']);
	}
	else if($type==0)
	{
		$r = $customer->delCustomer($_REQUEST['cus_id']);
	}
	
	//add by dingchaoyang 2014-12-13
	//响应json数据到客户端
	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
	if (intval($r['code']) == 0){
		EbaAdapter::responseData('Eba_CustomerDelSuccess');
	}else {
		EbaAdapter::responseData('Eba_CustomerDelFail');
	}
	//end add by dingchaoyang 2014-12-13
	die(json_encode($r));
}

/**
 * add dingchaoyang 2014-12-15 客户证件类型
 *
 * */
elseif ($action == 'act_cus_cer_type')
{
	include_once (ROOT_PATH . 'includes/class/Customer.class.php');
	$customer=new Customer();
	$r = $customer->getCertificatesType();
	$ogn = $customer->getOrgCertificatesType();

	//add by dingchaoyang 2014-12-15
	//响应json数据到客户端
	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
	EbaAdapter::responseCustomerCerList($r,$ogn);
	//end add by dingchaoyang 2014-12-15
}

/* 删除收货地址 */
elseif ($action == 'drop_consignee')
{
    include_once('includes/lib_transaction.php');

    $consignee_id = intval($_GET['id']);

    if (drop_consignee($consignee_id))
    {
        ecs_header("Location: user.php?act=address_list\n");
        exit;
    }
    else
    {
        show_message($_LANG['del_address_false']);
    }
}

/* 显示收藏商品列表 */
elseif ($action == 'collection_list')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');

    $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

    $record_count = $db->getOne("SELECT COUNT(*) FROM " .$ecs->table('collect_goods').
                                " WHERE user_id='$user_id' ORDER BY add_time DESC");

    $pager = get_pager('user.php', array('act' => $action), $record_count, $page);
    $smarty->assign('pager', $pager);
	$collect_array = get_collection_goods($user_id, $pager['size'], $pager['start']);
	foreach($collect_array as $key=>$val){
	  $collect_array[$key]['add_time'] = date('Y-m-d H:i:s', $val['add_time']);
	}
    $smarty->assign('goods_list', $collect_array);
    $smarty->assign('url',        $ecs->url());
    $lang_list = array(
        'UTF8'   => $_LANG['charset']['utf8'],
        'GB2312' => $_LANG['charset']['zh_cn'],
        'BIG5'   => $_LANG['charset']['zh_tw'],
    );
    $smarty->assign('lang_list',  $lang_list);
    $smarty->assign('user_id',  $user_id);
	/***
	 * 2014/7/29
	 * bhz
	 * 分配产品分类数据
	 */
	$smarty->assign('categories', $categories);
	
    $smarty->display('user_clips_collection_list.dwt');
}

/* 删除收藏的商品 */
elseif ($action == 'delete_collection')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');

    $collection_id = isset($_GET['collection_id']) ? intval($_GET['collection_id']) : 0;
    $gid = trim($_REQUEST['id']);

    if (($collection_id > 0)|| $gid)
    {
        $db->query('DELETE FROM ' .$ecs->table('collect_goods'). " WHERE (rec_id='$collection_id' or goods_id='$gid') AND user_id ='$user_id'" );
    }
    //add by dingchaoyang 2014-12-31
    include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    EbaAdapter::responseFavoriteIns(3);
    //end add by dingchaoyang 2014-12-31
    ecs_header("Location: user.php?act=collection_list\n");
    exit;
}

/* 添加关注商品 */
elseif ($action == 'add_to_attention')
{
    $rec_id = (int)$_GET['rec_id'];
    if ($rec_id)
    {
        $db->query('UPDATE ' .$ecs->table('collect_goods'). "SET is_attention = 1 WHERE rec_id='$rec_id' AND user_id ='$user_id'" );
    }
    ecs_header("Location: user.php?act=collection_list\n");
    exit;
}
/* 取消关注商品 */
elseif ($action == 'del_attention')
{
    $rec_id = (int)$_GET['rec_id'];
    if ($rec_id)
    {
        $db->query('UPDATE ' .$ecs->table('collect_goods'). "SET is_attention = 0 WHERE rec_id='$rec_id' AND user_id ='$user_id'" );
    }
    ecs_header("Location: user.php?act=collection_list\n");
    exit;
}
/* 显示留言列表 */
elseif ($action == 'message_list')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');

    $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

    $order_id = empty($_GET['order_id']) ? 0 : intval($_GET['order_id']);
    $order_info = array();

    /* 获取用户留言的数量 */
    if ($order_id)
    {
        $sql = "SELECT COUNT(*) FROM " .$ecs->table('feedback').
                " WHERE parent_id = 0 AND order_id = '$order_id' AND user_id = '$user_id'";
        $order_info = $db->getRow("SELECT * FROM " . $ecs->table('order_info') . " WHERE order_id = '$order_id' AND user_id = '$user_id'");
        $order_info['url'] = 'user.php?act=order_detail&order_id=' . $order_id;
    }
    else
    {
        $sql = "SELECT COUNT(*) FROM " .$ecs->table('feedback').
           " WHERE parent_id = 0 AND user_id = '$user_id' AND user_name = '" . $_SESSION['user_name'] . "' AND order_id=0";
    }

    $record_count = $db->getOne($sql);
    $act = array('act' => $action);

    if ($order_id != '')
    {
        $act['order_id'] = $order_id;
    }

    $pager = get_pager('user.php', $act, $record_count, $page, 5);

    $smarty->assign('message_list', get_message_list($user_id, $_SESSION['user_name'], $pager['size'], $pager['start'], $order_id));
    $smarty->assign('pager',        $pager);
    $smarty->assign('order_info',   $order_info);
    $smarty->display('user_clips_message_list.dwt');
}

/* 显示评论列表 */
elseif ($action == 'comment_list')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');

    $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

    /* 获取用户留言的数量 */
    $sql = "SELECT COUNT(*) FROM " .$ecs->table('comment').
           " WHERE parent_id = 0 AND user_id = '$user_id'";
    $record_count = $db->getOne($sql);
    $pager = get_pager('user.php', array('act' => $action), $record_count, $page, 5);

    $smarty->assign('comment_list', get_comment_list($user_id, $pager['size'], $pager['start']));
    $smarty->assign('pager',        $pager);
	/***
	 * 2014/7/29
	 * bhz
	 * 分配产品分类数据
	 */
	$smarty->assign('categories', $categories);
	
    $smarty->display('user_clips_comment_list.dwt');
}

/* 添加我的留言 */
elseif ($action == 'act_add_message')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');

    $message = array(
        'user_id'     => $user_id,
        'user_name'   => $_SESSION['user_name'],
        'user_email'  => $_SESSION['email'],
        'msg_type'    => isset($_POST['msg_type']) ? intval($_POST['msg_type'])     : 0,
        'msg_title'   => isset($_POST['msg_title']) ? trim($_POST['msg_title'])     : '',
        'msg_content' => isset($_POST['msg_content']) ? trim($_POST['msg_content']) : '',
        'order_id'=>empty($_POST['order_id']) ? 0 : intval($_POST['order_id']),
        'upload'      => (isset($_FILES['message_img']['error']) && $_FILES['message_img']['error'] == 0) || (!isset($_FILES['message_img']['error']) && isset($_FILES['message_img']['tmp_name']) && $_FILES['message_img']['tmp_name'] != 'none')
         ? $_FILES['message_img'] : array()
     );

    if (add_message($message))
    {
        show_message($_LANG['add_message_success'], $_LANG['message_list_lnk'], 'user.php?act=message_list&order_id=' . $message['order_id'],'info');
    }
    else
    {
        $err->show($_LANG['message_list_lnk'], 'user.php?act=message_list');
    }
}

/* 标签云列表 */
elseif ($action == 'tag_list')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');

    $good_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $smarty->assign('tags',      get_user_tags($user_id));
    $smarty->assign('tags_from', 'user');
    $smarty->display('user_clips_tag_list.dwt');
}

/* 删除标签云的处理 */
elseif ($action == 'act_del_tag')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');

    $tag_words = isset($_GET['tag_words']) ? trim($_GET['tag_words']) : '';
    delete_tag($tag_words, $user_id);

    ecs_header("Location: user.php?act=tag_list\n");
    exit;

}

/* 显示缺货登记列表 */
elseif ($action == 'booking_list')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');

    $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

    /* 获取缺货登记的数量 */
    $sql = "SELECT COUNT(*) " .
            "FROM " .$ecs->table('booking_goods'). " AS bg, " .
                     $ecs->table('goods') . " AS g " .
            "WHERE bg.goods_id = g.goods_id AND user_id = '$user_id'";
    $record_count = $db->getOne($sql);
    $pager = get_pager('user.php', array('act' => $action), $record_count, $page);

    $smarty->assign('booking_list', get_booking_list($user_id, $pager['size'], $pager['start']));
    $smarty->assign('pager',        $pager);
    $smarty->display('user_clips_booking_list.dwt');
}
/* 添加缺货登记页面 */
elseif ($action == 'add_booking')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');

    $goods_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($goods_id == 0)
    {
        show_message($_LANG['no_goods_id'], $_LANG['back_page_up'], '', 'error');
    }

    /* 根据规格属性获取货品规格信息 */
    $goods_attr = '';
    if ($_GET['spec'] != '')
    {
        $goods_attr_id = $_GET['spec'];

        $attr_list = array();
        $sql = "SELECT a.attr_name, g.attr_value " .
                "FROM " . $ecs->table('goods_attr') . " AS g, " .
                    $ecs->table('attribute') . " AS a " .
                "WHERE g.attr_id = a.attr_id " .
                "AND g.goods_attr_id " . db_create_in($goods_attr_id);
        $res = $db->query($sql);
        while ($row = $db->fetchRow($res))
        {
            $attr_list[] = $row['attr_name'] . ': ' . $row['attr_value'];
        }
        $goods_attr = join(chr(13) . chr(10), $attr_list);
    }
    $smarty->assign('goods_attr', $goods_attr);

    $smarty->assign('info', get_goodsinfo($goods_id));
    $smarty->display('user_clips_add_booking.dwt');

}

/* 添加缺货登记的处理 */
elseif ($action == 'act_add_booking')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');

    $booking = array(
        'goods_id'     => isset($_POST['id'])      ? intval($_POST['id'])     : 0,
        'goods_amount' => isset($_POST['number'])  ? intval($_POST['number']) : 0,
        'desc'         => isset($_POST['desc'])    ? trim($_POST['desc'])     : '',
        'linkman'      => isset($_POST['linkman']) ? trim($_POST['linkman'])  : '',
        'email'        => isset($_POST['email'])   ? trim($_POST['email'])    : '',
        'tel'          => isset($_POST['tel'])     ? trim($_POST['tel'])      : '',
        'booking_id'   => isset($_POST['rec_id'])  ? intval($_POST['rec_id']) : 0
    );

    // 查看此商品是否已经登记过
    $rec_id = get_booking_rec($user_id, $booking['goods_id']);
    if ($rec_id > 0)
    {
        show_message($_LANG['booking_rec_exist'], $_LANG['back_page_up'], '', 'error');
    }

    if (add_booking($booking))
    {
        show_message($_LANG['booking_success'], $_LANG['back_booking_list'], 'user.php?act=booking_list',
        'info');
    }
    else
    {
        $err->show($_LANG['booking_list_lnk'], 'user.php?act=booking_list');
    }
}

/* 删除缺货登记 */
elseif ($action == 'act_del_booking')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id == 0 || $user_id == 0)
    {
        ecs_header("Location: user.php?act=booking_list\n");
        exit;
    }

    $result = delete_booking($id, $user_id);
    if ($result)
    {
        ecs_header("Location: user.php?act=booking_list\n");
        exit;
    }
}

/* 确认收货 */
elseif ($action == 'affirm_received')
{
    include_once(ROOT_PATH . 'includes/lib_transaction.php');

    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

    if (affirm_received($order_id, $user_id))
    {
        ecs_header("Location: user.php?act=order_list\n");
        exit;
    }
    else
    {
        $err->show($_LANG['order_list_lnk'], 'user.php?act=order_list');
    }
}

/* 会员退款申请界面 */
elseif ($action == 'account_raply')
{   
/*	//判断是否审核通过
	$sql = "SELECT check_status FROM " . $GLOBALS['ecs']->table('users')." WHERE user_id='$user_id'";
	$check_status = $GLOBALS['db']->getOne($sql);
	if($check_status!=CHECKED_CHECK_STATUS)
	{
		show_message("当前用户正在审核，不能提现！");
	}*/
	
	$user_id = $_SESSION['user_id'];
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
	$res = $user_obj->checkAccountWithdraw($user_id);
	
	/*只有两个账户都有钱时，才允许显示提现*/
	$is_show_account=0;
	foreach ( $res['withdraw_rule_cfg'] as $key => $value ) {
		
		if($value['usable_money']>0)
		{
			$is_show_account=1;
		}
	}
	
	
	if($res['code']==1)
	{
		show_message($res['msg']);
	}

	$smarty->assign('str_name', $_LANG['surplus_type_1']);

	//提现配置列表
	$smarty->assign('withdraw_rule_cfg_list',$res['withdraw_rule_cfg']);
	
	    //启用验证码
    $smarty->assign('enabled_captcha', 1);
	
	//手机号码
	$smarty->assign('mobile_phone',$res['user']['mobile_phone']);
	//银行列表
	$smarty->assign('banks',$res['bank_list']);
	$smarty->assign('current_position','提现');
	$smarty->assign('is_show_account',$is_show_account);

    $smarty->display('user_transaction.dwt');
}

/* 会员预付款界面 */
elseif ($action == 'account_deposit')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');

    $surplus_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $account    = get_surplus_info($surplus_id);
	if($_SESSION['user_id']){
	$sql = "SELECT * FROM " . $ecs->table('users') ." WHERE user_id = '$_SESSION[user_id]'";
	$user_info = $db->getRow($sql);
	/* 渠道用户 */
	if($user_info['parent_id'] == 0 && $user_info['is_institution'] == 2){
		$sql = "SELECT d_mobilePhone FROM " . $ecs->table('distributor') ." WHERE d_uid = '$_SESSION[user_id]'";
		$username = $db->getOne($sql);
		/* 手机号码判断 */
		if(preg_match("/^1[1-9][0-9]\d{8}$/",$user_info['user_name'])){
			$smarty->assign('username', $user_info['user_name']);
		}else{
		  $smarty->assign('username', $username);
		}
	/* 个人会员 */
	}else{
		$sql = "SELECT user_name, mobile_phone FROM " . $ecs->table('users') ." WHERE user_id = '$_SESSION[user_id]'";
		$username = $db->getRow($sql);
		/* 手机号码判断 */
		if(preg_match("/^1[1-9][0-9]\d{8}$/",$username['user_name'])){
			 $smarty->assign('username', $username['user_name']);
		 }else{
			$smarty->assign('username', $username['mobile_phone']);
		 }
	  }
	}

	$payment = get_mypayment(WEBSITE_ACCOUNT);
	$smarty->assign('payment', $payment);
	//$smarty->assign('payment', get_online_payment_list(false));
	//echo "<pre>";print_r($payment);
	    //获取剩余余额
    $smarty->assign('user_money',get_user_surplus($user_id));
    
    $smarty->assign('order',   $account);
    $smarty->assign('str_name',   $_LANG['surplus_type_0']);
	/***
	 * 2014/7/29
	 * bhz
	 * 分配产品分类数据
	 */
	$smarty->assign('categories', $categories);
	$smarty->assign('current_position', '充值');
    $smarty->display('user_transaction.dwt');
}//account_deposit
elseif ($action == 'transfer_accounts')
{
	//获取当前用户可用余额
	$sql = "SELECT user_money FROM " .$ecs->table('users')." WHERE user_id=".$_SESSION['user_id'];
	$user_money = $db->getOne($sql);
	/*if($_SESSION['user_id']){
	   $sql = "SELECT user_name FROM " . $ecs->table('users') ." WHERE user_id = '$_SESSION[user_id]'";
	   $username = $db->getOne($sql);
	   $smarty->assign('username', $username);
	}*/
	if($_SESSION['user_id']){
	  $sql = "SELECT * FROM " . $ecs->table('users') ." WHERE user_id = '$_SESSION[user_id]'";
	  $user_info = $db->getRow($sql);
	  if($user_info['parent_id'] == 0 && $user_info['is_institution'] == 2){
		$sql = "SELECT d_mobilePhone FROM " . $ecs->table('distributor') ." WHERE d_uid = '$_SESSION[user_id]'";
		$username = $db->getOne($sql);
		if(preg_match("/^1[1-9][0-9]\d{8}$/",$user_info['user_name'])){
			$smarty->assign('username', $user_info['user_name']);
		}else{
		  $smarty->assign('username', $username);
		}
		
	  }else{
		$sql = "SELECT user_name, mobile_phone FROM " . $ecs->table('users') ." WHERE user_id = '$_SESSION[user_id]'";
		$username = $db->getRow($sql);
		if(preg_match("/^1[1-9][0-9]\d{8}$/",$username['user_name'])){
			 $smarty->assign('username', $username['user_name']);
		 }else{
			$smarty->assign('username', $username['mobile_phone']);
		 }
	  }
	}
	
	    //获取剩余余额
    $smarty->assign('surplus_amount', "￥".get_user_surplus($user_id)."元");
    $smarty->assign('user_money',  $user_money);
    $smarty->assign('str_name',  $_LANG['transfer_accounts']);
    $smarty->display('user_transaction_transfer_accounts.dwt');
}//transfer_accounts

elseif ($action == 'transfer_accounts_update') //转账
{	
	require(ROOT_PATH . 'languages/zh_cn/admin/account_log.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user = new User;
	$user->transferAccountsUpdate();	
}

/* 会员账目明细界面 */
elseif ($action == 'account_detail')
{

    //获取剩余余额
    $surplus_amount = get_user_surplus($user_id);
    if (empty($surplus_amount))
    {
        $surplus_amount = 0;
    }


	include_once(ROOT_PATH . 'includes/lib_clips.php');
	//modify yes123 2015-01-05  抽取，微信端和PC端公用
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user = new User;
	$res = $user->getAccountDetail();
	$account_log = $res['account_log'];
	$pager = $res['pager'];
	$condition = $res['condition'];

    include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    $page = isset ($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
    if ($page > intval($pager['page_count'])){
    	EbaAdapter::responseBills('2');
    }else{
    	EbaAdapter::responseBills($account_log);
    }
    //end add by dingchaoyang 2014-12-3
    
    //模板赋值
    $smarty->assign('user_money', $surplus_amount);
    $smarty->assign('account_log',    $account_log);
	
    $smarty->assign('pager',          $pager);
    $smarty->assign('str_name',     $_LANG['add_surplus_log'] );
	/***
	 * 2014/7/29
	 * bhz
	 * 分配产品分类数据
	 */
	$smarty->assign('categories', $categories);
	$smarty->assign('condition', $condition);
	$smarty->assign('current_position', '账户明细');
	$smarty->assign('recent',$_REQUEST['recent']); //2014/11/19   add  @  liuhui
	
    $smarty->display('user_transaction.dwt');
}//account_detail

//收益明细
elseif ($action == 'account_income')
{	
	
	global $income_type_list;
	include_once(ROOT_PATH . 'includes/lib_clips.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user = new User;
	$res = $user->getAccountIncome();
	$account_log = $res['account_log'];
	$pager = $res['pager'];
	$page = $pager['page'];
	$surplus_amount = $res['surplus_amount'];
	$shouru_money_total = $res['shouru_money_total'];
	$record_count = $res['record_count'];
	$condition = $res['condition'];
	

    //add by dingchaoyang 2014-12-6
    //响应json数据到客户端
    //     print_r(iconv('utf-8', 'gb2312//ignore', json_encode($account_log)));
    include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    
	$more = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
    if ($more > intval($pager['page_count'])){
    	EbaAdapter::responseIncomeList('2');
    }else{
    	EbaAdapter::responseIncomeList($account_log,$record_count,price_format($shouru_money_total, false));
    }
    //end add by dingchaoyang 2014-12-6
    //模板赋值
    $smarty->assign('user_money', $surplus_amount);
    $smarty->assign('shouru_money_total', price_format($shouru_money_total, false));
    $smarty->assign('account_log',    $account_log);
    $smarty->assign('pager',          $pager);
    $smarty->assign('condition',      $condition);
    $smarty->assign('income_type_list',      $income_type_list);
	
	/***
	 * 2014/7/29
	 * bhz
	 * 分配产品分类数据
	 */
	$smarty->assign('categories', $categories);
	$smarty->assign('current_position', '收益明细');
    $smarty->display('user_transaction.dwt');
}//account_income



elseif ($action == 'btjr_info')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');
    	
    	$user_id = $_REQUEST['user_id'];
    	
        $sql = "SELECT * FROM " . $ecs->table('account_log') .
           " WHERE user_id = '$user_id'" .
           " AND $account_type <> 0 " .
           " AND incoming_type LIKE '%推荐%'" .
           " ORDER BY log_id DESC";
    

    $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

    $account_type = 'user_money';

    /* 获取记录条数 */
    $sql = "SELECT COUNT(*) FROM " .$ecs->table('account_log').
           " WHERE user_id = '$user_id'" .
           " AND incoming_type IS NOT NULL" .
           " AND $account_type <> 0 ";
    $record_count = $db->getOne($sql);

    //分页函数
    $pager = get_pager('user.php', array('act' => $action), $record_count, $page);

    //获取剩余余额
    $surplus_amount = get_user_surplus2($user_id);
    if (empty($surplus_amount))
    {
        $surplus_amount = 0;
    }

    //获取余额记录
    $account_log = array();
    $sql = "SELECT * FROM " . $ecs->table('account_log') .
           " WHERE user_id = '$user_id'" .
           " AND $account_type <> 0 " .
           " AND incoming_type IS NOT NULL" .
           " ORDER BY log_id DESC";
    $res = $GLOBALS['db']->selectLimit($sql, $pager['size'], $pager['start']);
    while ($row = $db->fetchRow($res))
    {
        $row['change_time'] = local_date($_CFG['date_format'], $row['change_time']);
        $row['type'] = $row[$account_type] > 0 ? $_LANG['account_inc'] : $_LANG['account_dec'];
        $row['user_money'] = price_format(abs($row['user_money']), false);
        $row['frozen_money'] = price_format(abs($row['frozen_money']), false);
        $row['rank_points'] = abs($row['rank_points']);
        $row['pay_points'] = abs($row['pay_points']);
        $row['short_change_desc'] = sub_str($row['change_desc'], 60);
        $row['amount'] = $row[$account_type];
        $row['cname'] = $row['cname'];
        $row['incoming_type'] = $row['incoming_type'];
        $row['order_sn'] = $row['order_sn'];
        $account_log[] = $row;
    }

    //模板赋值
    $smarty->assign('surplus_amount', price_format($surplus_amount, false));
    $smarty->assign('account_log',    $account_log);
    $smarty->assign('pager',          $pager);
    $smarty->display('user_transaction_btjr_info.dwt');
}//btjr_info

/* 会员充值和提现申请记录 */
elseif ($action == 'account_log')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');
    
    //modify yes123 2015-01-05  微信端要用，所以收取到User类中了
    include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user = new User;
	$res = $user->getAccountLog();
	$account_log = $res['account_log'];
	
	$pager = $res['pager'];
	$condition = $res['condition'];
 
    //获取剩余余额
    $surplus_amount = get_user_surplus($user_id);
    if (empty($surplus_amount))
    {
        $surplus_amount = 0;
    }

    //add by dingchaoyang 2014-12-3
    //响应json数据到客户端
    include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    $page = isset ($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
    if ($page > intval($pager['page_count'])){
    	EbaAdapter::responseRechargeList('2');
    }else{
    	EbaAdapter::responseRechargeList($account_log);
    }
    //end add by dingchaoyang 2014-12-3
    
    //分页函数
    //add yes123 2014-12-04  获取支付方式列表
    $sql = "SELECT pay_id,pay_name FROM " .$ecs->table('payment');
    $payment_list = $db->getAll($sql);
    
    //模板赋值
    
    //add yes123 2014-12-04 查询条件缓存,前台回显
    $smarty->assign('condition', $condition);
    $smarty->assign('payment_list', $payment_list);
    $smarty->assign('process_type', $condition['process_type']);
    $smarty->assign('user_money', $surplus_amount);
    $smarty->assign('account_log',    $account_log);
    $smarty->assign('pager',          $pager);
	
	if($condition['process_type'] === '0')
	{
		$smarty->assign('current_position', '充值记录');
	} 
	else if($condition['process_type'] === '1')
	{
		$smarty->assign('current_position', '提现记录');
	}
	$smarty->assign('str_name', $_LANG['view_application']);
	
	
    $smarty->display('user_transaction.dwt');
}//account_log

//add yes123 2015-01-26 提醒管理员尽快处理提现申请
elseif ($action == 'reminder_withdraw')
{	
    include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user = new User;
	$res = $user->reminderWithdraw();
	
	
}
//add yes123 20150608 提现确认信息
elseif ($action == 'withdraw_confirm_info')
{	
	$data = array();
	include_once(ROOT_PATH . 'includes/class/User.class.php');
    $user_obj = new User;
    $data['money_type'] = isset($_REQUEST['money_type'])?$_REQUEST['money_type']:'';
    $data['amount'] = isset($_REQUEST['withdraw_money'])?$_REQUEST['withdraw_money']:'';
    $res = $user_obj->countWithdrawRate($data);
    $res = json_encode($res);
	die($res);
	
}
/* 对会员余额申请的处理 */
elseif ($action == 'act_account')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');
    include_once(ROOT_PATH . 'includes/lib_order.php');
    include_once(ROOT_PATH . 'includes/class/User.class.php');
    $user_obj = new User;
    
    $amount = isset($_POST['withdraw_money']) ? floatval($_POST['withdraw_money']) : 0;
    if(!$amount){
    	$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    }
    
    $pay_code = isset($_POST['pay_code']) ? $_POST['pay_code'] : 0;
    $pay_document = isset($_POST['pay_document']) ? $_POST['pay_document'] : "";
    
    if ($amount <= 0)
    {
        show_message($_LANG['amount_gt_zero']);
    }
   $bid = $_POST['bid'];
   //add yes123 2015-01-05 微信的提现
   $weixin = isset($_POST['weixin']) ? $_POST['weixin'] : 0;

    /* 变量初始化 */
    $surplus = array(
            'user_id'      => $user_id,
            'rec_id'       => !empty($_POST['rec_id'])      ? intval($_POST['rec_id'])       : 0,
            'process_type' => isset($_POST['surplus_type']) ? intval($_POST['surplus_type']) : 0,
            'payment_id'   => isset($_POST['payment_id'])   ? intval($_POST['payment_id'])   : 0,
            'user_note'    => isset($_POST['user_note'])    ? trim($_POST['user_note'])      : '',
            'money_type'    => isset($_POST['money_type'])    ? trim($_POST['money_type'])      : '',
            'amount'       => $amount,
			'bid' => $bid,
			'pay_code'   => $pay_code, //add yes123 2014-12-12 添加获取支付方式code判断是否需要支付凭据
			'pay_document' => $pay_document //支付凭据
    );

	ss_log('process_type:'.$surplus['process_type']);
    /* 退款申请的处理 */
    if ($surplus['process_type'] == 1)
    {
    	$user_obj->withdrawMoney($surplus);
    	
    }
    /* 如果是会员预付款，跳转到下一步，进行线上支付的操作 */
    else
    {
    	$res = $user_obj->rechargeMoney($surplus);
    	
		$order = $res['order'];
		
		$payment_info = $res['payment'];
		
		$amount = $res['amount'];
		
        //add by dingchaoyang 2014-12-3
        //响应json数据到客户端
        // 		print_r(iconv('utf-8', 'gb2312//ignore', json_encode($order)));
        include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
        EbaAdapter::responseRecharge($order,$payment_info['pay_button']);
        //end add by dingchaoyang 2014-12-3
        /* 模板赋值 */
        $smarty->assign('payment', $payment_info);
        $smarty->assign('pay_fee', price_format($payment_info['pay_fee'], false));
        $smarty->assign('amount',  price_format($amount, false));
        $smarty->assign('order',   $order);
        $smarty->display('user_transaction_act_account.dwt');
    }
}
//补充充值，凭证
elseif ($action == 'save_pay_document')
{
	$img = trim($_REQUEST['img']);
	$id = trim($_REQUEST['id']);
	
	$sql = "UPDATE " .$ecs->table('user_account'). " SET pay_document='$img' WHERE id = '$id' ";
	ss_log("更新图片：".$sql);
    if($db->query($sql))
    {
    	$datas = json_encode(array('code'=>0,'msg'=>'保存成功！')); 
    }
    else
    {
    	$datas = json_encode(array('code'=>1,'msg'=>'保存失败！'));
    }
	die($datas);
}

/* 删除会员余额 ，申请提现和充值，不能取消了*/
elseif ($action == 'cancel-bak')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    //if ($id == 0 || $user_id == 0)
    if ($id == 1 || $user_id == 1)
    {
    	//add by dingchaoyang 2014-12-3
    	//响应json数据到客户端
    	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    	EbaAdapter::responseData('Eba_CancelRechargeFail');
    	//end add by dingchaoyang 2014-12-3
    	ecs_header("Location: user.php?act=account_log\n");
        exit;
    }


	exit;//add yes 123 2015-06-04 提现申请，不能取消了
    $result = del_user_account($id, $user_id);
    if ($result)
    {
    	//add by dingchaoyang 2014-12-3
    	//响应json数据到客户端
    	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    	EbaAdapter::responseData('Eba_CancelRechargeSuccess');
    	//end add by dingchaoyang 2014-12-3
    	ecs_header("Location: user.php?act=account_log&process_type=$_GET[process_type]\n");
        exit;
    }
    else{
    	//add by dingchaoyang 2014-12-3
    	//响应json数据到客户端
    	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    	EbaAdapter::responseData('Eba_CancelRechargeFail');
    	//end add by dingchaoyang 2014-12-3
    }
}

elseif ($action == 'email_affiliate'){
	 $msg="";
	 $successMsg="";	
	 $failMsg="";	
	 
	 $real_name = isset($_SESSION['real_name']) ? $_SESSION['real_name'] : $_SESSION['user_name'];
	 
	 // $email_title =  trim($_REQUEST['email_title']);
	 $email_title = $real_name."给您推荐".$_CFG['shop_name'];  //modifed by yes123 2014-11-26
	 $email_addrs = trim($_REQUEST['email_addrs']);
	 $add_arr = explode(";",$email_addrs);
	 $tpl = get_mail_template('email_affiliate');
	 $smarty->assign('email_affiliate_url', $_REQUEST['email_affiliate_url']);
     $content = $smarty->fetch('str:' . $tpl['template_content']);
     $content = str_replace('real_name',$real_name, $content);
     $content = str_replace('shop_url',$_SERVER['SERVER_NAME'], $content); 
     $content = str_replace('affiliate_url',$_SERVER['SERVER_NAME']."?u=".$_SESSION['user_id'], $content); //替换推荐地址
     $content = str_replace('affiliate_reg_url',$_SERVER['SERVER_NAME']."?act=register&is_institution=1&u=".$_SESSION['user_id'], $content); //替换推荐地址
    // $content =  str_replace("shop_url",$_SERVER['HTTP_HOST'], $content);
     //add yes123 2014-12-28 邮件推荐显示真名
     //$content =  str_replace("real_name",$real_name, $content);
     
	 foreach ($add_arr as $key => $value ) {
	 	
	 	$res = send_mail( "客户", $value, $email_title, $content, $tpl['is_html']);
	 	ss_log("邮件推荐，发送邮件返回值：".$res);
		if ($res)
		{	
		   $successMsg.=$value;
		}
		else
		{
		   $failMsg.=$value;
		}
	 }
	
	 if($successMsg!=''){
	 	$msg.=" 发送成功:".$successMsg." ";
	 }
	 if($failMsg!=''){
	 	$msg.="  发送失败:".$failMsg." ";
	 }
		
	 echo $msg;
	 
}

/* 会员通过账目明细列表进行再付款的操作 */
elseif ($action == 'pay')
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
        $payment_info['pay_button'] = $pay_obj->get_code($order, $payment);

        /* 模板赋值 */
        $smarty->assign('payment', $payment_info);
        $smarty->assign('order',   $order);
        $smarty->assign('pay_fee', price_format($payment_info['pay_fee'], false));
        $smarty->assign('amount',  price_format($order['surplus_amount'], false));
        $smarty->assign('action',  'act_account');
        $smarty->display('user_transaction.dwt');
    }
    /* 重新选择支付方式 */
    else
    {
        include_once(ROOT_PATH . 'includes/lib_clips.php');

        $smarty->assign('payment', get_online_payment_list());
        $smarty->assign('order',   $order);
        $smarty->assign('action',  'account_deposit');
        $smarty->display('user_transaction.dwt');
    }
}//pay

elseif ($action == 'search_pay_status')
{
	$log_id = isset($_GET['log_id'])?intval($_GET['log_id']):0;
	
	if($log_id){
		
		$sql = "SELECT is_paid FROM ".$ecs->table('pay_log')." WHERE log_id='$log_id'";
		$is_paid= $db->getOne($sql);
		die($is_paid);
	}
	
	
}

/* 添加标签(ajax) */
elseif ($action == 'add_tag')
{
    include_once('includes/cls_json.php');
    include_once('includes/lib_clips.php');

    $result = array('error' => 0, 'message' => '', 'content' => '');
    $id     = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $tag    = isset($_POST['tag']) ? json_str_iconv(trim($_POST['tag'])) : '';

    if ($user_id == 0)
    {
        /* 用户没有登录 */
        $result['error']   = 1;
        $result['message'] = $_LANG['tag_anonymous'];
    }
    else
    {
        add_tag($id, $tag); // 添加tag
        clear_cache_files('goods'); // 删除缓存

        /* 重新获得该商品的所有缓存 */
        $arr = get_tags($id);

        foreach ($arr AS $row)
        {
            $result['content'][] = array('word' => htmlspecialchars($row['tag_words']), 'count' => $row['tag_count']);
        }
    }

    $json = new JSON;

    echo $json->encode($result);
    exit;
}

/* 添加收藏商品(ajax) */
elseif ($action == 'collect')
{
    include_once(ROOT_PATH .'includes/cls_json.php');
    $json = new JSON();
    $result = array('error' => 0, 'message' => '');
    $goods_id = $_GET['id'];


    if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == 0)
    {
        $result['error'] = 1;
        $result['message'] = $_LANG['login_please'];
    }
    else
    {
        /* 检查是否已经存在于用户的收藏夹 */
        $sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('collect_goods') .
            " WHERE user_id='$_SESSION[user_id]' AND goods_id = '$goods_id'";
        if ($GLOBALS['db']->GetOne($sql) > 0)
        {
            $result['error'] = 1;
            $result['message'] = $GLOBALS['_LANG']['collect_existed'];
        }
        else
        {
            $time = gmtime();
            $sql = "INSERT INTO " .$GLOBALS['ecs']->table('collect_goods'). " (user_id, goods_id, add_time)" .
                    "VALUES ('$_SESSION[user_id]', '$goods_id', '$time')";

            if ($GLOBALS['db']->query($sql) === false)
            {
                $result['error'] = 1;
                $result['message'] = $GLOBALS['db']->errorMsg();
            }
            else
            {
                $result['error'] = 0;
                $result['message'] = $GLOBALS['_LANG']['collect_success'];
            }
        }
    }
    
    //add by dingchaoyang 2014-12-31
    include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    EbaAdapter::responseFavoriteIns($result['error']);
    //end add by dingchaoyang 2014-12-31
    die($json->encode($result));
}

/* 删除留言 */
elseif ($action == 'del_msg')
{
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $order_id = empty($_GET['order_id']) ? 0 : intval($_GET['order_id']);

    if ($id > 0)
    {
        $sql = 'SELECT user_id, message_img FROM ' .$ecs->table('feedback'). " WHERE msg_id = '$id' LIMIT 1";
        $row = $db->getRow($sql);
        if ($row && $row['user_id'] == $user_id)
        {
            /* 验证通过，删除留言，回复，及相应文件 */
            if ($row['message_img'])
            {
                @unlink(ROOT_PATH . DATA_DIR . '/feedbackimg/'. $row['message_img']);
            }
            $sql = "DELETE FROM " .$ecs->table('feedback'). " WHERE msg_id = '$id' OR parent_id = '$id'";
            $db->query($sql);
        }
    }
    ecs_header("Location: user.php?act=message_list&order_id=$order_id\n");
    exit;
}

/* 删除评论 */
elseif ($action == 'del_cmt')
{
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id > 0)
    {
        $sql = "DELETE FROM " .$ecs->table('comment'). " WHERE comment_id = '$id' AND user_id = '$user_id'";
        $db->query($sql);
    }
    ecs_header("Location: user.php?act=comment_list\n");
    exit;
}

/* 合并订单 */
elseif ($action == 'merge_order')
{
    include_once(ROOT_PATH .'includes/lib_transaction.php');
    include_once(ROOT_PATH .'includes/lib_order.php');
    $from_order = isset($_POST['from_order']) ? trim($_POST['from_order']) : '';
    $to_order   = isset($_POST['to_order']) ? trim($_POST['to_order']) : '';
    if (merge_user_order($from_order, $to_order, $user_id))
    {
        show_message($_LANG['merge_order_success'],$_LANG['order_list_lnk'],'user.php?act=order_list', 'info');
    }
    else
    {
        $err->show($_LANG['order_list_lnk']);
    }
}
/* 将指定订单中商品添加到购物车 */
elseif ($action == 'return_to_cart')
{
    include_once(ROOT_PATH .'includes/cls_json.php');
    include_once(ROOT_PATH .'includes/lib_transaction.php');
    $json = new JSON();

    $result = array('error' => 0, 'message' => '', 'content' => '');
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    if ($order_id == 0)
    {
        $result['error']   = 1;
        $result['message'] = $_LANG['order_id_empty'];
        die($json->encode($result));
    }

    if ($user_id == 0)
    {
        /* 用户没有登录 */
        $result['error']   = 1;
        $result['message'] = $_LANG['login_please'];
        die($json->encode($result));
    }

    /* 检查订单是否属于该用户 */
    $order_user = $db->getOne("SELECT user_id FROM " .$ecs->table('order_info'). " WHERE order_id = '$order_id'");
    if (empty($order_user))
    {
        $result['error'] = 1;
        $result['message'] = $_LANG['order_exist'];
        die($json->encode($result));
    }
    else
    {
        if ($order_user != $user_id)
        {
            $result['error'] = 1;
            $result['message'] = $_LANG['no_priv'];
            die($json->encode($result));
        }
    }

    $message = return_to_cart($order_id);

    if ($message === true)
    {
        $result['error'] = 0;
        $result['message'] = $_LANG['return_to_cart_success'];
        die($json->encode($result));
    }
    else
    {
        $result['error'] = 1;
        $result['message'] = $_LANG['order_exist'];
        die($json->encode($result));
    }

}

/* 编辑使用余额支付的处理 */
elseif ($action == 'act_edit_surplus')
{   
	//add by dingchaoyang 2014-12-31
    include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    //end add by dingchaoyang 2014-12-31
    /* 检查是否登录 */
    if ($_SESSION['user_id'] <= 0)
    {
        EbaAdapter::responseData('Eba_SessionInvalid');
    	ecs_header("Location: ./\n");
        exit;
    }

    /* 检查订单号 */
    $order_id = intval($_POST['order_id']);
    if ($order_id <= 0)
    {
    	EbaAdapter::responsePayUnpaidOrder(0);
        ecs_header("Location: ./\n");
        exit;
    }

    /* 检查余额 */
    $surplus = floatval($_POST['surplus']);
    
    
    //add by dingchaoyang 2015-1-18 原来是<=0,修改为<0.在app端 ，在未支付订单，不使用余额，但修改了支付方式，需要同步支付方式到订单
    include_once (ROOT_PATH . 'api/EBaoApp/platformEnvironment.class.php');
    if ($surplus < 0 && PlatformEnvironment::isMobilePlatform())
    {
    	EbaAdapter::responsePayUnpaidOrder(0);

    }
    elseif($surplus <= 0  && !PlatformEnvironment::isMobilePlatform()) //add yes123 2015-03-30 如果输入的小于等于0，那么提示用户
    {
    	$err->add($_LANG['error_surplus_invalid']);
        $err->show($_LANG['order_detail'], 'user.php?act=order_detail&order_id=' . $order_id);
    }
	
    $eba_pay_id = $_POST['payment'];//add by dingchaoyang
    
    include_once(ROOT_PATH . 'includes/lib_order.php');

    /* 取得订单 */
    $order = order_info($order_id);
    if (empty($order))
    {
    	EbaAdapter::responsePayUnpaidOrder(0);
        ecs_header("Location: ./\n");
        exit;
    }

    /* 检查订单用户跟当前用户是否一致 */
    if ($_SESSION['user_id'] != $order['user_id'])
    {
    	EbaAdapter::responsePayUnpaidOrder(0);
        ecs_header("Location: ./\n");
        exit;
    }

    /* 检查订单是否未付款，检查应付款金额是否大于0 */
    if ($order['pay_status'] != PS_UNPAYED || $order['order_amount'] <= 0)
    {
    	ss_log("pay_status:".$order['pay_status']."order_amount".$order['order_amount'].",".$_LANG['error_order_is_paid']);
    	EbaAdapter::responsePayUnpaidOrder(0);
    	$err->add($_LANG['error_order_is_paid']);
        $err->show($_LANG['order_detail'], 'user.php?act=order_detail&order_id=' . $order_id);
    }

    /* 计算应付款金额（减去支付费用） */
    $order['order_amount'] -= $order['pay_fee'];

    /* 余额是否超过了应付款金额，改为应付款金额 */
    if ($surplus > $order['order_amount'])
    {
    	ss_log("使用的余额"+$surplus+"大于因付款金额"+$order['order_amount']+"，把因付款金额赋给余额");
        $surplus = $order['order_amount'];
        ss_log("最后因付款金额：".$surplus);
    }
    

    
	
    /* 取得用户信息 */
    $user = user_info($_SESSION['user_id']);
		
    /* 用户账户余额是否足够 */
    if ($surplus > $user['user_money'] + $user['credit_line'])
    {
    	ss_log($_LANG['error_surplus_not_enough']);
    	EbaAdapter::responsePayUnpaidOrder(0);
    	$err->add($_LANG['error_surplus_not_enough']);
        $err->show($_LANG['order_detail'], 'user.php?act=order_detail&order_id=' . $order_id);
    }

    /* 修改订单，重新计算支付费用 */
    $order['surplus'] += $surplus;
    
    //应付款金额 减掉页面传过来的金额
    $order['order_amount'] -= $surplus;
    
    
    if ($order['order_amount'] > 0)
    {
        $cod_fee = 0;
        if ($order['shipping_id'] > 0)
        {
            $regions  = array($order['country'], $order['province'], $order['city'], $order['district']);
            $shipping = shipping_area_info($order['shipping_id'], $regions);
            if ($shipping['support_cod'] == '1')
            {
                $cod_fee = $shipping['pay_fee'];
            }
        }

        $pay_fee = 0;
        if ($order['pay_id'] > 0)
        {
            $pay_fee = pay_fee($order['pay_id'], $order['order_amount'], $cod_fee);
        }

        $order['pay_fee'] = $pay_fee;
        $order['order_amount'] += $pay_fee;
    }

    /* 如果全部支付，设为已确认、已付款 */
    if ($order['order_amount'] == 0)
    {
    	ss_log('因付款金额和使用的余额相等，修改订单状态为已确认，已付款');
    	
        if ($order['order_status'] == OS_UNCONFIRMED)
        {
            $order['order_status'] = OS_CONFIRMED;
            $order['confirm_time'] = gmtime();
        }
        $order['pay_status'] = PS_PAYED;
        $order['pay_time'] = time();
       
    }
    
    if ($eba_pay_id){
    	$order['pay_id'] = $eba_pay_id;
    	$payment_info = payment_info($eba_pay_id);
    	$order['pay_name'] = $payment_info['pay_name'];
    }
    
    $order = addslashes_deep($order);
    update_order($order_id, $order);
    
    /* 更新用户余额 */
    include_once(ROOT_PATH . 'includes/class/Order.class.php');
	$order_obj = new Order;
	
	
	$pay_data = array('surplus'=>$surplus,
		  'user_id'=>$order['user_id']);    
    $change_desc = sprintf($_LANG['pay_order_by_surplus'], $order['order_sn']);
    $order_obj->surplusPay($pay_data,$change_desc,$order['order_sn']);
    
    
    //log_account_change($user['user_id'], (-1) * $surplus, 0, 0, 0, $change_desc,$order['order_sn'],$order['order_sn']);

	//add 2014-10-08 yes123.处理追加余额支付的时候，没有佣金的问题	
	if($order['order_status']==1 && $order['pay_status']==2){
		//mod by zhangxi, 20150305, 修改函数名,分配佣金和投保
		//yongJin2($order['order_sn']);
		ss_log("已确认已付款，去投保。。");
		assign_commision_and_post_policy($order['order_sn']);
	}
	
	//add by dingchaoyang 2014-12-31
	$out_trade_no = trim($_REQUEST['out_trade_no']);
	$orderid = explode('-',$out_trade_no);
	$order['log_id'] = $orderid[1];
	EbaAdapter::responsePayUnpaidOrder($order);
	//end by dingchaoyang
    /* 跳转 */
   	ecs_header('Location: user.php?act=order_detail&order_id=' . $order_id . "\n");
    exit;
}
/* add yes123 2015-07-07 编辑使用金币支付的处理 */
elseif ($action == 'act_edit_service_money')
{
    /* 检查是否登录 */
    if ($_SESSION['user_id'] <= 0)
    {
        EbaAdapter::responseData('Eba_SessionInvalid');
    	ecs_header("Location: ./\n");
        exit;
    }

    /* 检查订单号 */
    $order_id = intval($_POST['order_id']);
    if ($order_id <= 0)
    {
    	EbaAdapter::responsePayUnpaidOrder(0);
        ecs_header("Location: ./\n");
        exit;
    }

    
    /*add yes123 2015-07-07 检查金币*/
    $service_money = floatval($_POST['service_money']);
    
	if($service_money <= 0 ) //add yes123 2015-03-30 如果输入的小于等于0，那么提示用户
    {
    	$err->add($_LANG['error_surplus_invalid']);
        $err->show($_LANG['order_detail'], 'user.php?act=order_detail&order_id=' . $order_id);
    }
	
    
    include_once(ROOT_PATH . 'includes/lib_order.php');

    /* 取得订单 */
    $order = order_info($order_id);
    if (empty($order))
    {
        ecs_header("Location: ./\n");
        exit;
    }

    /* 检查订单用户跟当前用户是否一致 */
    if ($_SESSION['user_id'] != $order['user_id'])
    {
        ecs_header("Location: ./\n");
        exit;
    }

    /* 检查订单是否未付款，检查应付款金额是否大于0 */
    if ($order['pay_status'] != PS_UNPAYED || $order['order_amount'] <= 0)
    {
    	ss_log("pay_status:".$order['pay_status']."order_amount".$order['order_amount'].",".$_LANG['error_order_is_paid']);
    	$err->add($_LANG['error_order_is_paid']);
        $err->show($_LANG['order_detail'], 'user.php?act=order_detail&order_id=' . $order_id);
    }

    /* 计算应付款金额（减去支付费用） */
    $order['order_amount'] -= $order['pay_fee'];
    
    /*add yes123 2015-07-07 金币是否超过了应付款金额，改为应付款金额 */
	if ($service_money > $order['order_amount'])
    {
    	ss_log("使用的金币"+$service_money+"大于因付款金额"+$order['order_amount']+"，把因付款金额赋给余额");
        $service_money = $order['order_amount'];
        ss_log("最后因付款金额：".$service_money);
    }
    
	
    /* 取得用户信息 */
    $user = user_info($_SESSION['user_id']);
		
    /* 用户账户余额是否足够 */
    if ($service_money > $user['user_money'] + $user['credit_line'])
    {
    	ss_log($_LANG['error_surplus_not_enough']);
    	$err->add($_LANG['error_surplus_not_enough']);
        $err->show($_LANG['order_detail'], 'user.php?act=order_detail&order_id=' . $order_id);
    }

    /* 修改订单，重新计算支付费用 */
    $order['service_money'] += $service_money;
    
    //应付款金额 减掉页面传过来的金额
    $order['order_amount'] -= $service_money;
    

    /* 如果全部支付，设为已确认、已付款 */
    if ($order['order_amount'] == 0)
    {
    	ss_log('因付款金额和使用的余额相等，修改订单状态为已确认，已付款');
    	
        if ($order['order_status'] == OS_UNCONFIRMED)
        {
            $order['order_status'] = OS_CONFIRMED;
            $order['confirm_time'] = gmtime();
        }
        $order['pay_status'] = PS_PAYED;
        $order['pay_time'] = time();
       
    }
    
    if ($eba_pay_id){
    	$order['pay_id'] = $eba_pay_id;
    	$payment_info = payment_info($eba_pay_id);
    	$order['pay_name'] = $payment_info['pay_name'];
    }
    
    $order = addslashes_deep($order);
    update_order($order_id, $order);
    
    /* 更新用户余额 */
    include_once(ROOT_PATH . 'includes/class/Order.class.php');
	$order_obj = new Order;
    $change_desc = sprintf($_LANG['pay_order_by_service_money'], $order['order_sn']);

    $pay_data = array('service_money'=>$service_money,
		  'user_id'=>$order['user_id']);
    $order_obj->surplusPay($pay_data,$change_desc,$order['order_sn']);
    /* 跳转 */
   	ecs_header('Location: user.php?act=order_detail&order_id=' . $order_id . "\n");
    exit;
}
/* 编辑使用余额支付的处理 */
elseif ($action == 'act_edit_payment')
{
	
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
	$res = $user_obj->editPayment();

	$order_id = intval($_POST['order_id']);
    /* 跳转 */
    ecs_header("Location: user.php?act=order_detail&order_id=$order_id\n");
    exit;
}

/* 保存订单详情收货地址 */
elseif ($action == 'save_order_address')
{
    include_once(ROOT_PATH .'includes/lib_transaction.php');
    
    $address = array(
        'consignee' => isset($_POST['consignee']) ? compile_str(trim($_POST['consignee']))  : '',
        'email'     => isset($_POST['email'])     ? compile_str(trim($_POST['email']))      : '',
        'address'   => isset($_POST['address'])   ? compile_str(trim($_POST['address']))    : '',
        'zipcode'   => isset($_POST['zipcode'])   ? compile_str(make_semiangle(trim($_POST['zipcode']))) : '',
        'tel'       => isset($_POST['tel'])       ? compile_str(trim($_POST['tel']))        : '',
        'mobile'    => isset($_POST['mobile'])    ? compile_str(trim($_POST['mobile']))     : '',
        'sign_building' => isset($_POST['sign_building']) ? compile_str(trim($_POST['sign_building'])) : '',
        'best_time' => isset($_POST['best_time']) ? compile_str(trim($_POST['best_time']))  : '',
        'order_id'  => isset($_POST['order_id'])  ? intval($_POST['order_id']) : 0
        );
    if (save_order_address($address, $user_id))
    {
        ecs_header('Location: user.php?act=order_detail&order_id=' .$address['order_id']. "\n");
        exit;
    }
    else
    {
        $err->show($_LANG['order_list_lnk'], 'user.php?act=order_list');
    }
}

/* 我的代金券列表 */
elseif ($action == 'bonus')
{
    include_once(ROOT_PATH .'includes/lib_transaction.php');

    $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
    $record_count = $db->getOne("SELECT COUNT(*) FROM " .$ecs->table('user_bonus'). " WHERE user_id = '$user_id'");

    $pager = get_pager('user.php', array('act' => $action), $record_count, $page);
    $bonus = get_user_bouns_list($user_id, $pager['size'], $pager['start']);

    $smarty->assign('pager', $pager);
    $smarty->assign('bonus', $bonus);
    $smarty->display('user_transaction_bonus.dwt');
}//bonus

/* 我的团购列表 */
elseif ($action == 'group_buy')
{
    include_once(ROOT_PATH .'includes/lib_transaction.php');
    /***
	 * 2014/7/29
	 * bhz
	 * 分配产品分类数据
	 */
	$smarty->assign('categories', $categories);
	
    //待议
    $smarty->display('user_transaction_group_buy.dwt');
}//group_buy

/* 团购订单详情 */
elseif ($action == 'group_buy_detail')
{
    include_once(ROOT_PATH .'includes/lib_transaction.php');

    //待议
    $smarty->display('user_transaction_group_buy_detail.dwt');
}//group_buy_detail

// 用户推荐页面
elseif ($action == 'affiliate')
{
	require(ROOT_PATH . 'languages/zh_cn/admin/account_log.php');
	
	//start modify yes123 2014-12-31获取我的推荐数据列表 抽取，，微信端要用
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user = new User;
	$res = $user->getAffiliate();
	$user_list = $res['user_list'];
	$condition = $res['condition'];
	$earnings_total = $res['earnings_total'];
    $pager = $res['pager'];
    //end modify yes123 2014-12-31获取我的推荐数据列表 抽取，，微信端要用
    $user_list_new=array();
    //add 被推荐人个数、总推荐收入、订单数 by dingchaoyang 2014-12-7
    $totalInfo;
    $totalInfo['recommendCount'] = 0;//$record_count;//被推荐人数
    $sql = "SELECT sum(user_money) as total FROM " . $ecs->table('account_log') . " WHERE user_id= '".$_SESSION['user_id'] .
    "'  AND incoming_type='".$_LANG['tui_jian']."' ";
    $totalIncome=$GLOBALS['db']->getOne($sql);
    $totalInfo['totalIncome']=price_format($totalIncome,false);//所有被推荐人带来的收益
    //end by dingchaoyang 2014-12-7
    
    foreach ( $user_list as $key => $value ) {
    	//通过用户名查询此用户带来的服务费
    	$sql = "SELECT sum(user_money) as total,count(*) as orderCount FROM " . $ecs->table('account_log') . " WHERE user_id= '".$_SESSION['user_id']."' AND cname= '". $value['user_id'] .
		"'  AND incoming_type='".$_LANG['tui_jian']."' ORDER BY sum(user_money)  DESC";
    	 $total=$GLOBALS['db']->getOne($sql);
    	 $value['income_total']=$total['total'];
    	 $value['orderCount']=empty($total['orderCount'])?0:$total['orderCount'];//add by dingchaoyang 订单数
    	 
    	 //会员等级
    	 if($value['user_rank'])
    	 {
    	 	$user_rank = get_user_rank_info($value['user_rank']);
    	 	$value['rank_name'] = $user_rank['rank_name'];
    	 	
    	 }
    	 
    	 $value['check_status_str'] = $check_status_list[$value['check_status']];
    	 $value['reg_time']=date("Y-m-d H:i:s",$value['reg_time']);
    	 $user_list_new[]=$value;
    }  

    //add 排序 by dingchaoyang 2014-12-15
	$condition['sort'] = trim($_REQUEST['sort']);
	$condition ['order'] = trim($_REQUEST['order']);
	if($condition['sort'] && $condition['order']){
		include_once(ROOT_PATH . 'includes/class/ArrayUtils.class.php');
		if($condition['order'] == 'desc'){
			$user_list_new = ArrayUtils::sortByCol($user_list_new, $condition['sort'], SORT_DESC);
		}
		if($condition['order'] == 'asc'){
			$user_list_new = ArrayUtils::sortByCol($user_list_new, $condition['sort'], SORT_ASC);
		}


	}

    //end by dingchaoyang 2014-12-15
    
    //add by dingchaoyang 2014-12-1
    //响应json数据到客户端
// 	print_r(iconv('utf-8', 'gb2312//ignore', json_encode($user_list)));
    include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    $page = isset ($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
    if ($page > intval($pager['page_count'])){
    	EbaAdapter::responseRecommendList('2');
    }else{
    	EbaAdapter::responseRecommendList($user_list_new,$totalInfo);
    }
    //end add by dingchaoyang 2014-12-1
    
 /*   echo "<pre>";print_r($user_list);
    exit;*/
    
    //$user_list = $GLOBALS['db']->selectLimit($sql, $pager['size'], $pager['start']);
    //echo "<pre>";print_r($user_list);exit;
	//$user_list=$GLOBALS['db']->getAll($sql);
	
	//echo "<pre>";print_r($pager); exit;
   	// $res = $GLOBALS['db']->selectLimit($sql, $pager['size'], $pager['start']);

	

	
/*	$count = count($user_list);
    for ($i=0; $i<$count; $i++)
    {
        $user_list[$i]['reg_time'] = local_date($GLOBALS['_CFG']['date_format'], $user_list[$i]['reg_time']);
    }
	
	
    $goodsid = intval(isset($_REQUEST['goodsid']) ? $_REQUEST['goodsid'] : 0);
    if(empty($goodsid))
    {
        //我的推荐页面

        $page       = !empty($_REQUEST['page'])  && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;
        $size       = !empty($_CFG['page_size']) && intval($_CFG['page_size']) > 0 ? intval($_CFG['page_size']) : 10;

        empty($affiliate) && $affiliate = array();

        if(empty($affiliate['config']['separate_by']))
        {
            //推荐注册分成
            $affdb = array();
            $num = count($affiliate['item']);
            $up_uid = "'$user_id'";
            $all_uid = "'$user_id'";
            for ($i = 1 ; $i <=$num ;$i++)
            {
                $count = 0;
                if ($up_uid)
                {
                    $sql = "SELECT user_id FROM " . $ecs->table('users') . " WHERE parent_id IN($up_uid)";
                    $query = $db->query($sql);
                    $up_uid = '';
                    while ($rt = $db->fetch_array($query))
                    {
                        $up_uid .= $up_uid ? ",'$rt[user_id]'" : "'$rt[user_id]'";
                        if($i < $num)
                        {
                            $all_uid .= ", '$rt[user_id]'";
                        }
                        $count++;
                    }
                }
                $affdb[$i]['num'] = $count;
                $affdb[$i]['point'] = $affiliate['item'][$i-1]['level_point'];
                $affdb[$i]['money'] = $affiliate['item'][$i-1]['level_money'];
            }
            $smarty->assign('affdb', $affdb);

            $sqlcount = "SELECT count(*) FROM " . $ecs->table('order_info') . " o".
        " LEFT JOIN".$ecs->table('users')." u ON o.user_id = u.user_id".
        " LEFT JOIN " . $ecs->table('affiliate_log') . " a ON o.order_id = a.order_id" .
        " WHERE o.user_id > 0 AND (u.parent_id IN ($all_uid) AND o.is_separate = 0 OR a.user_id = '$user_id' AND o.is_separate > 0)";

            $sql = "SELECT o.*, a.log_id, a.user_id as suid,  a.user_name as auser, a.money, a.point, a.separate_type FROM " . $ecs->table('order_info') . " o".
                    " LEFT JOIN".$ecs->table('users')." u ON o.user_id = u.user_id".
                    " LEFT JOIN " . $ecs->table('affiliate_log') . " a ON o.order_id = a.order_id" .
        " WHERE o.user_id > 0 AND (u.parent_id IN ($all_uid) AND o.is_separate = 0 OR a.user_id = '$user_id' AND o.is_separate > 0)".
                    " ORDER BY order_id DESC" ;

            
                SQL解释：

                订单、用户、分成记录关联
                一个订单可能有多个分成记录

                1、订单有效 o.user_id > 0
                2、满足以下之一：
                    a.直接下线的未分成订单 u.parent_id IN ($all_uid) AND o.is_separate = 0
                        其中$all_uid为该ID及其下线(不包含最后一层下线)
                    b.全部已分成订单 a.user_id = '$user_id' AND o.is_separate > 0

            

            $affiliate_intro = nl2br(sprintf($_LANG['affiliate_intro'][$affiliate['config']['separate_by']], $affiliate['config']['expire'], $_LANG['expire_unit'][$affiliate['config']['expire_unit']], $affiliate['config']['level_register_all'], $affiliate['config']['level_register_up'], $affiliate['config']['level_money_all'], $affiliate['config']['level_point_all']));
        }
        else
        {
            //推荐订单分成
            $sqlcount = "SELECT count(*) FROM " . $ecs->table('order_info') . " o".
                    " LEFT JOIN".$ecs->table('users')." u ON o.user_id = u.user_id".
                    " LEFT JOIN " . $ecs->table('affiliate_log') . " a ON o.order_id = a.order_id" .
                    " WHERE o.user_id > 0 AND (o.parent_id = '$user_id' AND o.is_separate = 0 OR a.user_id = '$user_id' AND o.is_separate > 0)";


            $sql = "SELECT o.*, a.log_id,a.user_id as suid, a.user_name as auser, a.money, a.point, a.separate_type,u.parent_id as up FROM " . $ecs->table('order_info') . " o".
                    " LEFT JOIN".$ecs->table('users')." u ON o.user_id = u.user_id".
                    " LEFT JOIN " . $ecs->table('affiliate_log') . " a ON o.order_id = a.order_id" .
                    " WHERE o.user_id > 0 AND (o.parent_id = '$user_id' AND o.is_separate = 0 OR a.user_id = '$user_id' AND o.is_separate > 0)" .
                    " ORDER BY order_id DESC" ;

            
                SQL解释：

                订单、用户、分成记录关联
                一个订单可能有多个分成记录

                1、订单有效 o.user_id > 0
                2、满足以下之一：
                    a.订单下线的未分成订单 o.parent_id = '$user_id' AND o.is_separate = 0
                    b.全部已分成订单 a.user_id = '$user_id' AND o.is_separate > 0

            

            $affiliate_intro = nl2br(sprintf($_LANG['affiliate_intro'][$affiliate['config']['separate_by']], $affiliate['config']['expire'], $_LANG['expire_unit'][$affiliate['config']['expire_unit']], $affiliate['config']['level_money_all'], $affiliate['config']['level_point_all']));

        }

        $count = $db->getOne($sqlcount);

        $max_page = ($count> 0) ? ceil($count / $size) : 1;
        if ($page > $max_page)
        {
            $page = $max_page;
        }

        $res = $db->SelectLimit($sql, $size, ($page - 1) * $size);
        $logdb = array();
        while ($rt = $GLOBALS['db']->fetchRow($res))
        {
            if(!empty($rt['suid']))
            {
                //在affiliate_log有记录
                if($rt['separate_type'] == -1 || $rt['separate_type'] == -2)
                {
                    //已被撤销
                    $rt['is_separate'] = 3;
                }
            }
            $rt['order_sn'] = substr($rt['order_sn'], 0, strlen($rt['order_sn']) - 5) . "***" . substr($rt['order_sn'], -2, 2);
            $logdb[] = $rt;
        }

        $url_format = "user.php?act=affiliate&page=";

        $pager = array(
                    'page'  => $page,
                    'size'  => $size,
                    'sort'  => '',
                    'order' => '',
                    'record_count' => $count,
                    'page_count'   => $max_page,
                    'page_first'   => $url_format. '1',
                    'page_prev'    => $page > 1 ? $url_format.($page - 1) : "javascript:;",
                    'page_next'    => $page < $max_page ? $url_format.($page + 1) : "javascript:;",
                    'page_last'    => $url_format. $max_page,
                    'array'        => array()
                );
        for ($i = 1; $i <= $max_page; $i++)
        {
            $pager['array'][$i] = $i;
        }

        $smarty->assign('url_format', $url_format);
        $smarty->assign('pager', $pager);


        $smarty->assign('affiliate_intro', $affiliate_intro);
        $smarty->assign('affiliate_type', $affiliate['config']['separate_by']);

        $smarty->assign('logdb', $logdb);
    }
    else
    {
        //单个商品推荐
        $smarty->assign('userid', $user_id);
        $smarty->assign('goodsid', $goodsid);

        $types = array(1,2,3,4,5);
        $smarty->assign('types', $types);

        $goods = get_goods_info($goodsid);
        $shopurl = $ecs->url();
        $goods['goods_img'] = (strpos($goods['goods_img'], 'http://') === false && strpos($goods['goods_img'], 'https://') === false) ? $shopurl . $goods['goods_img'] : $goods['goods_img'];
        $goods['goods_thumb'] = (strpos($goods['goods_thumb'], 'http://') === false && strpos($goods['goods_thumb'], 'https://') === false) ? $shopurl . $goods['goods_thumb'] : $goods['goods_thumb'];
        $goods['shop_price'] = price_format($goods['shop_price']);

        $smarty->assign('goods', $goods);
    }*/
	
	/*add yes123 2015-07-09 由于我的推荐页面有两个选项卡，要判断是选中那个*/
	$click = isset($_GET['click'])?$_GET['click']:2;
	$smarty->assign('click', $click);
	
    $smarty->assign('shopname', $_CFG['shop_name']);
    $smarty->assign('userid', $user_id);
    $smarty->assign('shopurl', $ecs->url());
    $smarty->assign('logosrc', 'themes/' . $_CFG['template'] . '/images/logo.gif');
	/***
	 * 2014/7/29
	 * bhz
	 * 分配产品分类数据
	 */
	$smarty->assign('categories', $categories);
		
	$smarty->assign('earnings_total', $earnings_total);
	$smarty->assign('user_list', $user_list_new);
	$smarty->assign('check_status_list', $check_status_list);
	$smarty->assign('user_rank_list', get_user_rank_list());
	$smarty->assign('condition', $condition);
	$smarty->assign('pager',          $pager);
    $smarty->display('user_clips_affiliate.dwt');
}

//首页邮件订阅ajax操做和验证操作
elseif ($action =='email_list')
{
    $job = $_GET['job'];

    if($job == 'add' || $job == 'del')
    {
        if(isset($_SESSION['last_email_query']))
        {
            if(time() - $_SESSION['last_email_query'] <= 30)
            {
                die($_LANG['order_query_toofast']);
            }
        }
        $_SESSION['last_email_query'] = time();
    }

    $email = trim($_GET['email']);
    $email = htmlspecialchars($email);

    if (!is_email($email))
    {
        $info = sprintf($_LANG['email_invalid'], $email);
        die($info);
    }
    $ck = $db->getRow("SELECT * FROM " . $ecs->table('email_list') . " WHERE email = '$email'");
    if ($job == 'add')
    {
        if (empty($ck))
        {
            $hash = substr(md5(time()), 1, 10);
            $sql = "INSERT INTO " . $ecs->table('email_list') . " (email, stat, hash) VALUES ('$email', 0, '$hash')";
            $db->query($sql);
            $info = $_LANG['email_check'];
            $url = $ecs->url() . "user.php?act=email_list&job=add_check&hash=$hash&email=$email";
            send_mail('', $email, $_LANG['check_mail'], sprintf($_LANG['check_mail_content'], $email, $_CFG['shop_name'], $url, $url, $_CFG['shop_name'], local_date('Y-m-d')), 1);
        }
        elseif ($ck['stat'] == 1)
        {
            $info = sprintf($_LANG['email_alreadyin_list'], $email);
        }
        else
        {
            $hash = substr(md5(time()),1 , 10);
            $sql = "UPDATE " . $ecs->table('email_list') . "SET hash = '$hash' WHERE email = '$email'";
            $db->query($sql);
            $info = $_LANG['email_re_check'];
            $url = $ecs->url() . "user.php?act=email_list&job=add_check&hash=$hash&email=$email";
            send_mail('', $email, $_LANG['check_mail'], sprintf($_LANG['check_mail_content'], $email, $_CFG['shop_name'], $url, $url, $_CFG['shop_name'], local_date('Y-m-d')), 1);
        }
        die($info);
    }
    elseif ($job == 'del')
    {
        if (empty($ck))
        {
            $info = sprintf($_LANG['email_notin_list'], $email);
        }
        elseif ($ck['stat'] == 1)
        {
            $hash = substr(md5(time()),1,10);
            $sql = "UPDATE " . $ecs->table('email_list') . "SET hash = '$hash' WHERE email = '$email'";
            $db->query($sql);
            $info = $_LANG['email_check'];
            $url = $ecs->url() . "user.php?act=email_list&job=del_check&hash=$hash&email=$email";
            send_mail('', $email, $_LANG['check_mail'], sprintf($_LANG['check_mail_content'], $email, $_CFG['shop_name'], $url, $url, $_CFG['shop_name'], local_date('Y-m-d')), 1);
        }
        else
        {
            $info = $_LANG['email_not_alive'];
        }
        die($info);
    }
    elseif ($job == 'add_check')
    {
        if (empty($ck))
        {
            $info = sprintf($_LANG['email_notin_list'], $email);
        }
        elseif ($ck['stat'] == 1)
        {
            $info = $_LANG['email_checked'];
        }
        else
        {
            if ($_GET['hash'] == $ck['hash'])
            {
                $sql = "UPDATE " . $ecs->table('email_list') . "SET stat = 1 WHERE email = '$email'";
                $db->query($sql);
                $info = $_LANG['email_checked'];
            }
            else
            {
                $info = $_LANG['hash_wrong'];
            }
        }
        show_message($info, $_LANG['back_home_lnk'], 'index.php');
    }
    elseif ($job == 'del_check')
    {
        if (empty($ck))
        {
            $info = sprintf($_LANG['email_invalid'], $email);
        }
        elseif ($ck['stat'] == 1)
        {
            if ($_GET['hash'] == $ck['hash'])
            {
                $sql = "DELETE FROM " . $ecs->table('email_list') . "WHERE email = '$email'";
                $db->query($sql);
                $info = $_LANG['email_canceled'];
            }
            else
            {
                $info = $_LANG['hash_wrong'];
            }
        }
        else
        {
            $info = $_LANG['email_not_alive'];
        }
        show_message($info, $_LANG['back_home_lnk'], 'index.php');
    }
}

/* ajax 发送验证邮件 */
elseif ($action == 'send_hash_mail')
{
    include_once(ROOT_PATH .'includes/cls_json.php');
    include_once(ROOT_PATH .'includes/lib_passport.php');
    $json = new JSON();

    $result = array('error' => 0, 'message' => '', 'content' => '');

    if ($user_id == 0)
    {
        /* 用户没有登录 */
        $result['error']   = 1;
        $result['message'] = $_LANG['login_please'];
        die($json->encode($result));
    }

    if (send_regiter_hash($user_id))
    {
        $result['message'] = $_LANG['validate_mail_ok'];
        die($json->encode($result));
    }
    else
    {
        $result['error'] = 1;
        $result['message'] = $GLOBALS['err']->last_message();
    }

    die($json->encode($result));
}
else if ($action == 'track_packages')
{
    include_once(ROOT_PATH . 'includes/lib_transaction.php');
    include_once(ROOT_PATH .'includes/lib_order.php');

    $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

    $orders = array();

    $sql = "SELECT order_id,order_sn,invoice_no,shipping_id FROM " .$ecs->table('order_info').
            " WHERE user_id = '$user_id' AND shipping_status = '" . SS_SHIPPED . "'";
    $res = $db->query($sql);
    $record_count = 0;
    while ($item = $db->fetch_array($res))
    {
        $shipping   = get_shipping_object($item['shipping_id']);

        if (method_exists ($shipping, 'query'))
        {
            $query_link = $shipping->query($item['invoice_no']);
        }
        else
        {
            $query_link = $item['invoice_no'];
        }

        if ($query_link != $item['invoice_no'])
        {
            $item['query_link'] = $query_link;
            $orders[]  = $item;
            $record_count += 1;
        }
    }
    $pager  = get_pager('user.php', array('act' => $action), $record_count, $page);
    $smarty->assign('pager',  $pager);
    $smarty->assign('orders', $orders);
	/***
	 * 2014/7/29
	 * bhz
	 * 分配产品分类数据
	 */
	$smarty->assign('categories', $categories);
	
    $smarty->display('user_transaction_track_packages.dwt');
}//track_packages
else if ($action == 'order_query')
{
    $_GET['order_sn'] = trim(substr($_GET['order_sn'], 1));
    $order_sn = empty($_GET['order_sn']) ? '' : addslashes($_GET['order_sn']);
    include_once(ROOT_PATH .'includes/cls_json.php');
    $json = new JSON();

    $result = array('error'=>0, 'message'=>'', 'content'=>'');

    if(isset($_SESSION['last_order_query']))
    {
        if(time() - $_SESSION['last_order_query'] <= 10)
        {
            $result['error'] = 1;
            $result['message'] = $_LANG['order_query_toofast'];
            die($json->encode($result));
        }
    }
    $_SESSION['last_order_query'] = time();

    if (empty($order_sn))
    {
        $result['error'] = 1;
        $result['message'] = $_LANG['invalid_order_sn'];
        die($json->encode($result));
    }

    $sql = "SELECT order_id, order_status, shipping_status, pay_status, ".
           " shipping_time, shipping_id, invoice_no, user_id ".
           " FROM " . $ecs->table('order_info').
           " WHERE order_sn = '$order_sn' LIMIT 1";

    $row = $db->getRow($sql);
    if (empty($row))
    {
        $result['error'] = 1;
        $result['message'] = $_LANG['invalid_order_sn'];
        die($json->encode($result));
    }

    $order_query = array();
    $order_query['order_sn'] = $order_sn;
    $order_query['order_id'] = $row['order_id'];
    $order_query['order_status'] = $_LANG['os'][$row['order_status']] . ',' . $_LANG['ps'][$row['pay_status']] . ',' . $_LANG['ss'][$row['shipping_status']];

    if ($row['invoice_no'] && $row['shipping_id'] > 0)
    {
        $sql = "SELECT shipping_code FROM " . $ecs->table('shipping') . " WHERE shipping_id = '$row[shipping_id]'";
        $shipping_code = $db->getOne($sql);
        $plugin = ROOT_PATH . 'includes/modules/shipping/' . $shipping_code . '.php';
        if (file_exists($plugin))
        {
            include_once($plugin);
            $shipping = new $shipping_code;
            $order_query['invoice_no'] = $shipping->query((string)$row['invoice_no']);
        }
        else
        {
            $order_query['invoice_no'] = (string)$row['invoice_no'];
        }
    }

    $order_query['user_id'] = $row['user_id'];
    /* 如果是匿名用户显示发货时间 */
    if ($row['user_id'] == 0 && $row['shipping_time'] > 0)
    {
        $order_query['shipping_date'] = local_date($GLOBALS['_CFG']['date_format'], $row['shipping_time']);
    }
    $smarty->assign('order_query',    $order_query);
    $result['content'] = $smarty->fetch('library/order_query.lbi');
    die($json->encode($result));
}
elseif ($action == 'transform_points')
{
    $rule = array();
    if (!empty($_CFG['points_rule']))
    {
        $rule = unserialize($_CFG['points_rule']);
    }
    $cfg = array();
    if (!empty($_CFG['integrate_config']))
    {
        $cfg = unserialize($_CFG['integrate_config']);
        $_LANG['exchange_points'][0] = empty($cfg['uc_lang']['credits'][0][0])? $_LANG['exchange_points'][0] : $cfg['uc_lang']['credits'][0][0];
        $_LANG['exchange_points'][1] = empty($cfg['uc_lang']['credits'][1][0])? $_LANG['exchange_points'][1] : $cfg['uc_lang']['credits'][1][0];
    }
    $sql = "SELECT user_id, user_name, pay_points, rank_points FROM " . $ecs->table('users')  . " WHERE user_id='$user_id'";
    $row = $db->getRow($sql);
    if ($_CFG['integrate_code'] == 'ucenter')
    {
        $exchange_type = 'ucenter';
        $to_credits_options = array();
        $out_exchange_allow = array();
        foreach ($rule as $credit)
        {
            $out_exchange_allow[$credit['appiddesc'] . '|' . $credit['creditdesc'] . '|' . $credit['creditsrc']] = $credit['ratio'];
            if (!array_key_exists($credit['appiddesc']. '|' .$credit['creditdesc'], $to_credits_options))
            {
                $to_credits_options[$credit['appiddesc']. '|' .$credit['creditdesc']] = $credit['title'];
            }
        }
        $smarty->assign('selected_org', $rule[0]['creditsrc']);
        $smarty->assign('selected_dst', $rule[0]['appiddesc']. '|' .$rule[0]['creditdesc']);
        $smarty->assign('descreditunit', $rule[0]['unit']);
        $smarty->assign('orgcredittitle', $_LANG['exchange_points'][$rule[0]['creditsrc']]);
        $smarty->assign('descredittitle', $rule[0]['title']);
        $smarty->assign('descreditamount', round((1 / $rule[0]['ratio']), 2));
        $smarty->assign('to_credits_options', $to_credits_options);
        $smarty->assign('out_exchange_allow', $out_exchange_allow);
    }
    else
    {
        $exchange_type = 'other';

        $bbs_points_name = $user->get_points_name();
        $total_bbs_points = $user->get_points($row['user_name']);

        /* 论坛积分 */
        $bbs_points = array();
        foreach ($bbs_points_name as $key=>$val)
        {
            $bbs_points[$key] = array('title'=>$_LANG['bbs'] . $val['title'], 'value'=>$total_bbs_points[$key]);
        }

        /* 兑换规则 */
        $rule_list = array();
        foreach ($rule as $key=>$val)
        {
            $rule_key = substr($key, 0, 1);
            $bbs_key = substr($key, 1);
            $rule_list[$key]['rate'] = $val;
            switch ($rule_key)
            {
                case TO_P :
                    $rule_list[$key]['from'] = $_LANG['bbs'] . $bbs_points_name[$bbs_key]['title'];
                    $rule_list[$key]['to'] = $_LANG['pay_points'];
                    break;
                case TO_R :
                    $rule_list[$key]['from'] = $_LANG['bbs'] . $bbs_points_name[$bbs_key]['title'];
                    $rule_list[$key]['to'] = $_LANG['rank_points'];
                    break;
                case FROM_P :
                    $rule_list[$key]['from'] = $_LANG['pay_points'];$_LANG['bbs'] . $bbs_points_name[$bbs_key]['title'];
                    $rule_list[$key]['to'] =$_LANG['bbs'] . $bbs_points_name[$bbs_key]['title'];
                    break;
                case FROM_R :
                    $rule_list[$key]['from'] = $_LANG['rank_points'];
                    $rule_list[$key]['to'] = $_LANG['bbs'] . $bbs_points_name[$bbs_key]['title'];
                    break;
            }
        }
        $smarty->assign('bbs_points', $bbs_points);
        $smarty->assign('rule_list',  $rule_list);
    }
    $smarty->assign('shop_points', $row);
    $smarty->assign('exchange_type',     $exchange_type);
    $smarty->assign('action',     $action);
    $smarty->assign('lang',       $_LANG);
    $smarty->display('user_transaction_transform_points.dwt');
}//transform_points
elseif ($action == 'act_transform_points')
{
    $rule_index = empty($_POST['rule_index']) ? '' : trim($_POST['rule_index']);
    $num = empty($_POST['num']) ? 0 : intval($_POST['num']);


    if ($num <= 0 || $num != floor($num))
    {
        show_message($_LANG['invalid_points'], $_LANG['transform_points'], 'user.php?act=transform_points');
    }

    $num = floor($num); //格式化为整数

    $bbs_key = substr($rule_index, 1);
    $rule_key = substr($rule_index, 0, 1);

    $max_num = 0;

    /* 取出用户数据 */
    $sql = "SELECT user_name, user_id, pay_points, rank_points FROM " . $ecs->table('users') . " WHERE user_id='$user_id'";
    $row = $db->getRow($sql);
    $bbs_points = $user->get_points($row['user_name']);
    $points_name = $user->get_points_name();

    $rule = array();
    if ($_CFG['points_rule'])
    {
        $rule = unserialize($_CFG['points_rule']);
    }
    list($from, $to) = explode(':', $rule[$rule_index]);

    $max_points = 0;
    switch ($rule_key)
    {
        case TO_P :
            $max_points = $bbs_points[$bbs_key];
            break;
        case TO_R :
            $max_points = $bbs_points[$bbs_key];
            break;
        case FROM_P :
            $max_points = $row['pay_points'];
            break;
        case FROM_R :
            $max_points = $row['rank_points'];
    }

    /* 检查积分是否超过最大值 */
    if ($max_points <=0 || $num > $max_points)
    {
        show_message($_LANG['overflow_points'], $_LANG['transform_points'], 'user.php?act=transform_points' );
    }

    switch ($rule_key)
    {
        case TO_P :
            $result_points = floor($num * $to / $from);
            $user->set_points($row['user_name'], array($bbs_key=>0 - $num)); //调整论坛积分
            log_account_change($row['user_id'], 0, 0, 0, $result_points, $_LANG['transform_points'], ACT_OTHER);
            show_message(sprintf($_LANG['to_pay_points'],  $num, $points_name[$bbs_key]['title'], $result_points), $_LANG['transform_points'], 'user.php?act=transform_points');

        case TO_R :
            $result_points = floor($num * $to / $from);
            $user->set_points($row['user_name'], array($bbs_key=>0 - $num)); //调整论坛积分
            log_account_change($row['user_id'], 0, 0, $result_points, 0, $_LANG['transform_points'], ACT_OTHER);
            show_message(sprintf($_LANG['to_rank_points'], $num, $points_name[$bbs_key]['title'], $result_points), $_LANG['transform_points'], 'user.php?act=transform_points');

        case FROM_P :
            $result_points = floor($num * $to / $from);
            log_account_change($row['user_id'], 0, 0, 0, 0-$num, $_LANG['transform_points'], ACT_OTHER); //调整商城积分
            $user->set_points($row['user_name'], array($bbs_key=>$result_points)); //调整论坛积分
            show_message(sprintf($_LANG['from_pay_points'], $num, $result_points,  $points_name[$bbs_key]['title']), $_LANG['transform_points'], 'user.php?act=transform_points');

        case FROM_R :
            $result_points = floor($num * $to / $from);
            log_account_change($row['user_id'], 0, 0, 0-$num, 0, $_LANG['transform_points'], ACT_OTHER); //调整商城积分
            $user->set_points($row['user_name'], array($bbs_key=>$result_points)); //调整论坛积分
            show_message(sprintf($_LANG['from_rank_points'], $num, $result_points, $points_name[$bbs_key]['title']), $_LANG['transform_points'], 'user.php?act=transform_points');
    }
}
elseif ($action == 'act_transform_ucenter_points')
{
    $rule = array();
    if ($_CFG['points_rule'])
    {
        $rule = unserialize($_CFG['points_rule']);
    }
    $shop_points = array(0 => 'rank_points', 1 => 'pay_points');
    $sql = "SELECT user_id, user_name, pay_points, rank_points FROM " . $ecs->table('users')  . " WHERE user_id='$user_id'";
    $row = $db->getRow($sql);
    $exchange_amount = intval($_POST['amount']);
    $fromcredits = intval($_POST['fromcredits']);
    $tocredits = trim($_POST['tocredits']);
    $cfg = unserialize($_CFG['integrate_config']);
    if (!empty($cfg))
    {
        $_LANG['exchange_points'][0] = empty($cfg['uc_lang']['credits'][0][0])? $_LANG['exchange_points'][0] : $cfg['uc_lang']['credits'][0][0];
        $_LANG['exchange_points'][1] = empty($cfg['uc_lang']['credits'][1][0])? $_LANG['exchange_points'][1] : $cfg['uc_lang']['credits'][1][0];
    }
    list($appiddesc, $creditdesc) = explode('|', $tocredits);
    $ratio = 0;

    if ($exchange_amount <= 0)
    {
        show_message($_LANG['invalid_points'], $_LANG['transform_points'], 'user.php?act=transform_points');
    }
    if ($exchange_amount > $row[$shop_points[$fromcredits]])
    {
        show_message($_LANG['overflow_points'], $_LANG['transform_points'], 'user.php?act=transform_points');
    }
    foreach ($rule as $credit)
    {
        if ($credit['appiddesc'] == $appiddesc && $credit['creditdesc'] == $creditdesc && $credit['creditsrc'] == $fromcredits)
        {
            $ratio = $credit['ratio'];
            break;
        }
    }
    if ($ratio == 0)
    {
        show_message($_LANG['exchange_deny'], $_LANG['transform_points'], 'user.php?act=transform_points');
    }
    $netamount = floor($exchange_amount / $ratio);
    include_once(ROOT_PATH . './includes/lib_uc.php');
    $result = exchange_points($row['user_id'], $fromcredits, $creditdesc, $appiddesc, $netamount);
    if ($result === true)
    {
        $sql = "UPDATE " . $ecs->table('users') . " SET {$shop_points[$fromcredits]}={$shop_points[$fromcredits]}-'$exchange_amount' WHERE user_id='{$row['user_id']}'";
        $db->query($sql);
        $sql = "INSERT INTO " . $ecs->table('account_log') . "(user_id, {$shop_points[$fromcredits]}, change_time, change_desc, change_type)" . " VALUES ('{$row['user_id']}', '-$exchange_amount', '". gmtime() ."', '" . $cfg['uc_lang']['exchange'] . "', '98')";
        $db->query($sql);
        show_message(sprintf($_LANG['exchange_success'], $exchange_amount, $_LANG['exchange_points'][$fromcredits], $netamount, $credit['title']), $_LANG['transform_points'], 'user.php?act=transform_points');
    }
    else
    {
        show_message($_LANG['exchange_error_1'], $_LANG['transform_points'], 'user.php?act=transform_points');
    }
}
/* 清除商品浏览历史 */
elseif ($action == 'clear_history')
{
    setcookie('ECS[history]',   '', 1);
}
/**
 * 2014/7/30
 * bhz
 * 银行账户 start
 */
 /* 查询信息 */
elseif($action == 'bank_account')
{
   /*include_once(ROOT_PATH . 'includes/lib_transaction.php');*/
   if (empty($_SESSION['user_id']))
   {
     header("Location:user.php?act=login");
   }
   $sql = "SELECT * FROM ". $ecs->table('bank') ."WHERE uid = $_SESSION[user_id]";
   $bank_account = $db->getRow($sql);
   $smarty->assign('bank', $bank_account);
  /***
   * 修改时间:2014/7/29
   * 修改者：鲍洪州
   * 功能：分配产品分类数据
   */
   $smarty->assign('categories', $categories);
   $smarty->assign('user_id',$_SESSION['user_id']);
   $sql = "SELECT * FROM ". $ecs->table('bank') ."WHERE uid = $_SESSION[user_id]";
   $bankAll=$db->getAll($sql);
   //add by dingchaoyang 2014-11-6
   //响应json数据到客户端
   include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
   EbaAdapter::responseUnionPay($bankAll);
   //end add by dingchaoyang 2014-11-6
   $smarty->assign('bankAll', $bankAll);
   //14-12-8 下午2:55 liangyanpeng
   $num = count($bankAll);
   $smarty->assign('num', $num);
   $smarty->display('user_clips_bank_account.dwt');
}
/* 插入信息 */
elseif($action == 'add_account')
{
	
    if (empty($_SESSION['user_id']))
    {
      header("Location:user.php?act=login");
    }
    $bank_code  = trim($_POST['bank_code']);
    
    $sql = "SELECT count(bid) FROM " . $ecs->table('bank'). " WHERE uid = '$_SESSION[user_id]'";
    $num = $db->getOne($sql);
    if($num>=5){
    	die('账户最多只能添加5个！');
    	//show_message('账户最多只能添加5个！', '', 'user.php?act=add_bank');
    }
    
	$sql = "SELECT count(bid) FROM " . $ecs->table('bank'). " WHERE bank_code = '$bank_code'";
	if($db->getOne($sql) > 0){
		//add by dingchaoyang 2014-12-15
		//响应json数据到客户端
		include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
		EbaAdapter::responseData('Eba_UnionPayAddExist');
		//end add by dingchaoyang 2014-12-15
		
		die('账户已存在!');
		//show_message('账户已存在,请检测...', '', 'user.php?act=add_bank');
	}else{
		/* 获得数据 */
		$bank_name = isset($_POST['bank_name']) ? trim($_POST['bank_name']) : '';
		if(empty($_POST['rests']))
		{
		   $b_account = $_POST['b_account'];
		}
		else
		{
		   $b_account = $_POST['rests'];
		}
		
		
/*		b_account	中国工商银行

		bank_code	 32536475754458845
		
		bank_name	 姚元福*/

		
		//$sub_branch = $_POST['sub_branch'];
		$userID = empty($_POST['userID'])?$_SESSION['user_id']:$_POST['userID'];
		if($bank_name != null && $b_account != null && $bank_code != null){
		  $sql="INSERT INTO ".$ecs->table('bank')." (uid, bank_name, b_account, bank_code) VALUES ($userID, '$bank_name', '$b_account', '$bank_code')";
		  $db->query($sql);
		  //add by dingchaoyang 2014-12-15
		  //响应json数据到客户端
		  include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
		  EbaAdapter::responseData('Eba_UnionPayAddSuccess');
		  //end add by dingchaoyang 2014-12-15
		  //header("Location:user.php?act=bank_account");
		  die('添加成功！');
		}else{
		  die('信息不完整!');
		  //show_message('以上信息均不可为空！', '', 'user.php?act=add_bank');
		}
	}
}
/**
 * 2014/7/30
 * bhz
 * 银行账户 end
 */
 
/**
 * 2014/8/7
 * bhz
 * 银行账户 部分 start
 */
/* 银行账户添加界面 */
elseif($action == 'add_bank'){
      $smarty->assign('categories', $categories);
      $smarty->assign('user_id',$_SESSION['user_id']);
      $smarty->display('user_clips_add_bank.dwt');
}
/* 删除银行账户 */
elseif($action == 'del_bank'){
   $bid = $_REQUEST['bid'];
   if($bid){
      $sql = "DELETE FROM " . $ecs->table('bank') . " WHERE bid = '$bid' AND uid=".$_SESSION['user_id']." ";
	  if($db->query($sql))
	  {
	  	//add by dingchaoyang 2014-11-6
	  	//响应json数据到客户端
	  	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
	  	EbaAdapter::responseData('Eba_DeleteUnionPaySuccess');
	  	//end add by dingchaoyang 2014-11-6
		//show_message('账户删除成功！', '', 'user.php?act=bank_account');
		
		die('账户删除成功！');    
	  }
	  else{
	  	//add by dingchaoyang 2014-11-6
	  	//响应json数据到客户端
	  	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
	  	EbaAdapter::responseData('Eba_DeleteUnionPayFail');
	  	//end add by dingchaoyang 2014-11-6
	    //show_message('删除账户失败！' , '', 'user.php?act=bank_account');
	    die('账户删除失败！'); 
	  }
   }
}
/* 修改账户界面 */
elseif($action == 'edit_bank'){
  $bid = $_REQUEST['bid'];
  $sql = "SELECT * FROM " . $ecs->table('bank'). " WHERE bid = '$bid' AND uid=$_SESSION[user_id] ";
  $bank_array = $db->getRow($sql);
  $smarty->assign('banks', $bank_array); 
  
  die(json_encode($bank_array));

}
/* 修改账户处理 */
elseif($action == 'act_edit_bank')
{
    $bid = $_POST['bid'];
    $bank_name = isset($_POST['bank_name']) ? $_POST['bank_name'] : '';
    $b_account = isset($_POST['b_account']) ? $_POST['b_account'] : '';
    $sub_branch = isset($_POST['sub_branch']) ? $_POST['sub_branch'] : '';
    $bank_code = isset($_POST['bank_code']) ? $_POST['bank_code'] : '';
	$sql = "UPDATE " . $ecs->table('bank') . " SET bank_name = '$bank_name', b_account = '$b_account', sub_branch = '$sub_branch', bank_code = '$bank_code' WHERE bid = '$bid'";
	if($db->query($sql))
	{
		//add by dingchaoyang 2014-11-6
		//响应json数据到客户端
		include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
		EbaAdapter::responseData('Eba_UpdateUnionPaySuccess');
		//end add by dingchaoyang 2014-11-6
      //show_message('修改成功！','','user.php?act=bank_account');	
      die('修改成功！');
	}else{
		//add by dingchaoyang 2014-11-6
		//响应json数据到客户端
		include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
		EbaAdapter::responseData('Eba_UpdateUnionPayFail');
		//end add by dingchaoyang 2014-11-6
		die('修改失败！请重新修改');
	  show_message('修改失败！请重新修改...','',"user.php?act=edit_bank&bid=$bid");
	}
}
/**
 * 2014/8/7
 * bhz
 * 银行账户 部分 end
 */
 
/**
 * 2014/8/5
 * bhz
 */
 
/* 密码修改界面 */
elseif($action == 'edit_pass')
{
  $smarty->assign('categories', $categories);
  $smarty->display('user_transaction_edit_pass.dwt');
}//edit_pass
/* 已购买 */
elseif($action == 'just_print')
{
  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
  $sql = "SELECT order_id FROM " . $ecs->table('order_info'). " WHERE user_id = $user_id";
  $order_id_array = $db->getAll($sql);
  $order_id_str = '';
  for($i=0;$i<count($order_id_array);$i++){
     $order_id_str .= $order_id_array[$i]['order_id'].",";
  }
  $order_id_str = rtrim($order_id_str,',');
  if(!empty($order_id_str)){	
	$sql = "SELECT group_concat(DISTINCT og.goods_name), og.goods_name, og.goods_id, oi.order_sn, oi.pay_time, og.product_id FROM ". $ecs->table('order_goods') . " AS og LEFT JOIN " . $ecs->table('order_info') . " AS oi ON og.order_id = oi.order_id WHERE og.order_id in($order_id_str) GROUP BY og.goods_name";
	$just_print = $db->getAll($sql);
	
	//add by yes123 2014-11-21 查询数量
	foreach($just_print as $key=>$value){
		$just_print[$key]['pay_time'] = date('Y-m-d', $value['pay_time']);
		//查询购买数量
		$sql = " SELECT COUNT(*) FROM bx_order_goods og INNER JOIN bx_order_info oi ON og.order_id=oi.order_id WHERE oi.user_id=".$_SESSION['user_id']." AND og.goods_id=".$value['goods_id'] ;
		$just_print[$key]['total'] = $db->getOne($sql);
	}
  }  
  $smarty->assign('just_print_array', $just_print);
  $smarty->assign('categories', $categories);
  $smarty->display('user_transaction_just_print.dwt');
}//just_print


elseif($action=='organ_manage')
{
	
	$smarty->display('user_transaction.dwt');
}
/**
 * 2014/8/19
 * bhz
 * 渠道部分
 */
/* 渠道--渠道信息界面 */
elseif($action == 'organ_info')
{   
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	
	//add yes123 2015-05-04 如果有子渠道ID，那么就是获取子渠道信息
	$user_obj = new User;
    $res = $user_obj->organInfo();
    $distributor_info = $res['distributor_info'];
    $region_list = get_region_list($distributor_info['province'],$distributor_info['city']);
	$smarty->assign('ur_here', "渠道信息");//
	$smarty->assign('province_list', $region_list['province_list']);//省
	$smarty->assign('city_list', $region_list['city_list']);//市
	$smarty->assign('district_list', $region_list['district_list']);//县
	$smarty->assign('distributor_info', $res['distributor_info']); //渠道基本信息
    $smarty->assign('categories', $categories);
	$smarty->assign('save_type', 'edit_organ_info');
    $smarty->display('user_transaction.dwt');
}//organ_info
/* 子渠道--子渠道信息界面 */
elseif($action == 'child_organ_info')
{   
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	
	//add yes123 2015-05-04 如果有子渠道ID，那么就是获取子渠道信息
	$child_organ_id = isset($_REQUEST['child_organ_id'])?$_REQUEST['child_organ_id']:0;
	$user_obj = new User;
    $res = $user_obj->organInfo($child_organ_id);
	$distributor_info = $res['distributor_info'];
	$region_list = get_region_list($distributor_info['province'],$distributor_info['city']);
	
	$smarty->assign('ur_here', "子渠道信息");
	$smarty->assign('province_list', $region_list['province_list']);//省
	
	$smarty->assign('city_list', $region_list['city_list']);//市
	$smarty->assign('district_list', $region_list['district_list']);//县
	$smarty->assign('distributor_info', $distributor_info); //渠道基本信息
	
    $smarty->assign('save_type', 'edit_child_organ_info');

    $smarty->display('user_transaction.dwt');
}
/* 渠道--添加/修改 渠道信息 */
elseif($action == 'edit_organ'){
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$d_uid = isset($_REQUEST['d_uid'])?$_REQUEST['d_uid']:0;
	$save_type = isset($_REQUEST['save_type'])?$_REQUEST['save_type']:'';
	$user_obj = new User;
    $res = $user_obj->editOrganInfo($d_uid);
    
    //编辑渠道信息和子渠道信息，走同一个action，当d_uid不等于 登录者user_id时，说明是编辑子渠道信息
    if($save_type=='edit_child_organ_info')//编辑子渠道
    {
    	$res['go_url']='user.php?act=child_organ_info&child_organ_id='.$d_uid;
    }
    else if($save_type=='edit_organ_info') //编辑自己渠道信息
    {
    	$res['go_url']='user.php?act=organ_info';
    }
    else if($save_type=='add_child_organ') //添加子渠道
    {
    	$res['go_url']='user.php?act=child_organ_list';
    }
    
    if($res['code']==0)
    {
	    show_message(sprintf('操作成功！'), '',$res['go_url']); 	
    }
    else
    {
    	show_message(sprintf($res['msg']), '',$res['go_url'],'warning',false); 	
    	
    }
    
    
    //$datas = json_encode($res); //如果用户不存在,返回-2
	//die($datas);
	
}
/* 渠道--会员管理 */
elseif($action == 'salesman_list')
{   
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
    $res = $user_obj->salesmanList();
    $user_list = $res['user_list'];
    $total_data = $res['total_data'];
    $pager = $res['pager'];
    $condition = $res['condition'];
    $organ_department_list = $res['organ_department_list'];
    
    $smarty->assign('pager',  $pager);
    $smarty->assign('organ_department_list',  $organ_department_list);
    $smarty->assign('condition',  $condition);
	$smarty->assign('user_list', $user_list);
	$smarty->assign('total_data', $total_data);
	$smarty->assign('user_rank_list', get_user_rank_list());
	$smarty->assign('check_status_list', $check_status_list);
    $smarty->assign('categories', $categories);
	$smarty->display('user_transaction.dwt');
}//salesman_list
/* 渠道--子渠道列表 */
elseif($action == 'child_organ_list')
{   
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
    $res = $user_obj->childOrganList();
    $user_list = $res['user_list'];
    $pager = $res['pager'];
    $condition = $res['condition'];
    $smarty->assign('pager',  $pager);
    $smarty->assign('condition',  $condition);
	$smarty->assign('user_list', $user_list);
	$smarty->assign('check_status_list', $check_status_list);
    $smarty->assign('categories', $categories);
	$smarty->display('user_transaction.dwt');
}

/* 渠道--添加会员界面 */
elseif($action == 'control_add'){
	
	//获取部门列表
	$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('organ_department') . " WHERE organ_id=$_SESSION[user_id]";
	$organ_department_list = $GLOBALS['db']->getAll($sql);
	
	$region_list = get_region_list();
	
    $smarty->assign('ur_here',      '会员管理-新增会员');
    $smarty->assign('type', 'add');
    $smarty->assign('organ_department_list', $organ_department_list);
    $smarty->assign('province_list',$region_list['province_list']);
	$smarty->display('user_transaction.dwt');
}//control_add
/* 渠道--添加会员 */
elseif($action == 'salesman'){
    /* 用户信息 */
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
    $res = $user_obj->addSalesman();
   	//$datas = json_encode($res); //如果用户不存在,返回-2
   	//die($datas);
   	show_message($res['msg']);
	
	
}

/* 渠道--禁用会员 */
elseif($action == 'remove_disable_salesman'){
    /* 用户信息 */
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
	$type = trim($_REQUEST['type']);
	
	$msg = "";
	switch ($type)
	{
		case "remove_salesman":
			$res = $user_obj->removeSalesman();
		 	if($res)
		 	{
		 		$msg = '移除成功！';
		 	}
		  break;  
		case "disable_salesman":
		    $res = $user_obj->disableOrEnabledSalesman(1);
		 	if($res)
		 	{
		 		$msg = "禁用成功！";
				
		 	}
		  break;
		case "remove_disable_salesman":
			$res = $user_obj->disableOrEnabledSalesman(1);
			if($res)
			{
				$res = $user_obj->removeSalesman();
				if($res)
			 	{
			 		$msg = '移除并禁用成功！';
					//show_message(sprintf('移除并禁用成功！'), '','user.php?act=salesman_list'); 	
			 	}
			 	else
			 	{
			 		$msg = '禁用成功，移除失败！';
			 		//show_message(sprintf('禁用成功，移除失败！'), '','user.php?act=salesman_list');
			 		ss_log("remove_disable_salesman:移除成功，禁用失败");
			 	}
			}
			else
			{
				$msg = '禁用失败！';
				//show_message(sprintf('禁用失败！'), '','user.php?act=salesman_list');
				ss_log("remove_disable_salesman:移除失败");
			}
		  break;
	}
	
	die($msg);
	
}

/* 渠道--启用会员 */
elseif($action == 'enabled_salesman'){
    /* 用户信息 */
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
    $res = $user_obj->disableOrEnabledSalesman(0);
 	if($res)
 	{
		show_message(sprintf('启用成功！'), '','user.php?act=salesman_list'); 	
 	}
}

/* 禁用或者启用会员 */
elseif($action == 'disable_enabled_salesman'){
    /* 用户信息 */
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
	$is_disable = $_REQUEST['is_disable'];
    $res = $user_obj->disableOrEnabledSalesman($is_disable);
 	if($res)
 	{
 		$datas = json_encode(array('code'=>0,'msg'=>'操作成功!')); //如果用户不存在,返回-2
	 	die($datas);
 	}
}


/* 渠道--查看会员明细 */
elseif($action == 'salesman_detail'){
    /* 用户信息 */
    include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
    $res = $user_obj->salesmanDetail();
    $user_info=$res['user_info'];
    
	$smarty->assign('province_list', $res['province_list']);
	$smarty->assign('city_list', $res['city_list']);
	$smarty->assign('district_list', $res['district_list']);
    
    $smarty->assign('organ_department_list', $res['organ_department_list']);
    $smarty->assign('ur_here', '会员管理-编辑会员');
    $smarty->assign('type', 'edit');
    $smarty->assign('profile', $user_info);
	$smarty->display('user_transaction.dwt');
}//salesman_detail

/*渠道--渠道下的订单列表*/
elseif($action == 'order_manage_list'){
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
    $user_obj = new User;
	$res = $user_obj->getOrderList(1);
	$payment = $res['payment'];
	$orders = $res['orders'];
	$condition = $res['condition'];
	$record_count = $res['record_count'];
	$pager = $res['pager'];
	$order_tatol_amount = $res['order_tatol_amount'];
	
    $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

    foreach ($orders as $key => $order )
    {
        $orders[$key]['warranty_no'] =!empty($order['policy_id'])?get_warranty_no($order['policy_id']):"";
     
    }
    
    $smarty->assign('order_tatol_amount',  $order_tatol_amount);

    $smarty->assign('payment',  $payment);
    $smarty->assign('pager',  $pager);
    $smarty->assign('orders', $orders);

	$smarty->assign('recent', $_REQUEST['recent']); //2014/11/19 add @ liuhui
	$smarty->assign('condition', $condition);
    $smarty->display('user_transaction.dwt');
}

//add yes123 2014-12-23 添加已有会员到渠道
elseif ($action == 'add_to_org')
{
	$user_id = $_SESSION['user_id'];
	$user_ids = $_REQUEST['user_ids'];
	$type = $_REQUEST['type'];
	if(!$user_ids)
	{
		die(json_encode(array('code'=>1,'msg'=>'操作失败，请联系管理员！')));
	}
	
	if($type=='remove')
	{
		$sql = "SELECT * FROM bx_users WHERE user_id =$user_ids";
		$user = $db->getRow($sql);
		if($user['parent_id']==$user_id){
			if($user['institution_id']==$user_id)
			{
				$sql="UPDATE ".$ecs->table('users'). "SET institution_id=0 WHERE user_id =$user_ids";
				ss_log("add_to_org remove".$sql);
				if($db->query($sql)){
					die(json_encode(array('code'=>0,'msg'=>'解除成功！','err'=>$err)));
				}
				
			}else{
				die(json_encode(array('code'=>1,'msg'=>'此用户所属其他渠道，无法解除！')));
			}
			
			
		}else{
			die(json_encode(array('code'=>1,'msg'=>'非推荐关系，无法添加')));
		}
		
	}
	
	$err="";
	if($type=='add')
	{
		//先查询是否是推荐与被推荐关系
		$sql = "SELECT * FROM bx_users WHERE user_id IN($user_ids)";
		$user_list = $db->getAll($sql);
		$msg="";
		$ids="";
		foreach ( $user_list as $key => $value ) {
	    	 if($value['parent_id']==$user_id ){
	    	 	if(!$value['institution_id'])
	    	 	{
	    	 		 $ids.=$value['user_id'].",";
	    	 	}else{
	    	 		$err.=$value['user_name']."已属其他渠道，不能再添加！\n";
	    	 	}
	    	 	
	    	 }else{
	    	 	$err.="非法操作：".$value['user_id'] ."的推荐人不是".$user_id."\n";
	    	 	ss_log("add_to_org 非法操作：".$value['user_id'] ."的推荐人不是".$user_id);
	    	 }
		}
		ss_log("add_to_org ids:".$ids."user_ids:".$user_ids);
		if($ids)
		{
			if (strstr($ids, ',')) {
				$ids = rtrim($ids, ',');
			}
				
			$sql="UPDATE ".$ecs->table('users'). "SET institution_id='$user_id' WHERE user_id IN ($ids)";
			ss_log("add_to_org add :".$sql);
			$r = $db->query($sql);
			if($r)
			{
				die(json_encode(array('code'=>0,'msg'=>'添加成功！','err'=>$err)));	
			}else{
				die(json_encode(array('code'=>1,'msg'=>'添加失败，请联系管理员！')));
			}
			
			
		}
	}
	die(json_encode(array('code'=>1,'msg'=>'添加失败，请联系管理员！','err'=>$err)));
	
}

/*渠道--渠道下的保单列表*/
elseif($action == 'policy_manage_list'){
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
	$res = $user_obj->getPolicyList("policy_manage_list");
	$warranty = $res['policy_list'];
	$record_count = $res['record_count'];
	$pager = $res['pager'];
	$condition = $res['condition'];
	$total_premium = $res['total_premium'];
	
    $page = $pager['page'];
    
    //add by dingchaoyang 2014-12-1
    //响应json数据到客户端
//  print_r(iconv('utf-8', 'gb2312//ignore', json_encode($warranty)));
    include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    $more = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
    if ($more > intval($pager['page_count'])){
    	EbaAdapter::responsePolicyList('2');
    }else{
    	EbaAdapter::responsePolicyList($warranty);
    } 
    //end add by dingchaoyang 2014-12-1
	
	$smarty->assign('pager',  $pager);
	$smarty->assign('total_premium',  $total_premium);
	$smarty->assign('warranty', $warranty);
	/***
	 * 2014/7/29
	 * bhz
	 * 分配产品分类数据
	 */
      
	$smarty->assign('brand_list', get_brand_list());
	$smarty->assign('recent', $_REQUEST['recent']); //2014/11/19 add @ liuhui
	$smarty->assign('condition', $condition);  		
    $smarty->display('user_transaction.dwt');
}//policy_manage_list

/*渠道--添加子渠道*/
elseif($action == 'child_organ_add')
{
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$region_list = get_region_list();
	
	$smarty->assign('ur_here', "新增子渠道");
	$smarty->assign('province_list', $region_list['province_list']);//省
	
	$smarty->assign('save_type', 'add_child_organ');
    $smarty->display('user_transaction.dwt');
	
}

/*渠道产品列表*/
elseif($action == 'organ_goods_list')
{
	    include_once(ROOT_PATH . 'includes/class/User.class.php');
		$user_obj = new User;
    	$res = $user_obj->organGoodsList($_SESSION['user_id']); 
    	$smarty->assign('pager',  $res['pager']);   
		$smarty->assign('condition',  $res['condition']);
		$smarty->assign('attribute_list',        $res['attribute_list']);
	 	$smarty->display('user_transaction.dwt');
}



/* 短信动态验证码 注册 */
elseif($action == 'phone_code'){
	
	get_check_code();
	
}

/*注册时验证码是否正确*/
elseif($action == 'check_captcha') 
{
	$captcha = $_REQUEST['captcha'];
	
    if (empty($captcha))
    {
        die("请输入正确验证码");
    }

    /* 检查验证码 */
    include_once('includes/cls_captcha.php');

    $validator = new captcha();
    if (!$validator->check_word($_POST['captcha']))
    {
    	die($_LANG['invalid_captcha']);
        
    }
    
    
    die("200");
	
}
/* 短信动态验证码 提现 充值 转账 */
elseif($action == 'withdrawals')
{	
	// modify yes123 2015-01-08 代码抽取，微信端要用
    include_once(ROOT_PATH.'sdk_sms.php');
    include_once(ROOT_PATH . 'includes/class/User.class.php');
    $user_obj = new User;
    $arr = $user_obj->withdrawals();
    $num = $arr['num'];
    $result = $arr['result'];
    
	 //add by dingchaoyang 2014-12-5
	 //响应json数据到客户端
	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
	EbaAdapter::responseSmsCheckCode($num);
	//end add by dingchaoyang 2014-12-5
	//add yes123 2014-11-30 不能给前台返回明文的验证码


	$num = md5($num);
	$datas = json_encode(array('data'=>$result,'num'=>$num));
	die($datas);
}

elseif($action == 'user_center_affiche')
{
	$article_list = get_affiche_list();
	$smarty->assign('article_list', $article_list);  		
    $smarty->display('user_transaction_user_center_affiche.dwt');	
}
//部门列表
elseif($action == 'department_list')
{
	$distrbutor_obj = new Distrbutor;
	$department_list = $distrbutor_obj->organDepartmentList($_SESSION['user_id']);
	$smarty->assign('department_list', $department_list);  		
    $smarty->display('user_transaction.dwt');	
}
//添加部门
elseif($action == 'save_organ_department')
{
	$distrbutor_obj = new Distrbutor;
	$distrbutor_obj->saveOrganDepartmentList($_SESSION['user_id']);
	die(json_encode(array('code'=>0,'msg'=>'ok')));
}
//添加部门
elseif($action == 'del_department')
{
	 $sql="DELETE  FROM ".$ecs->table('organ_department'). " WHERE department_id= '".$_REQUEST['department_id']."'"; 
	 $r = $db->query($sql);
	 if($r)
	 {
		 die(json_encode(array('code'=>0,'msg'=>'ok')));
	 }
}

/* 验证用户注册用户名是否可以注册 */
elseif ($action == 'is_exist_by_value')
{
    include_once(ROOT_PATH . 'includes/lib_passport.php');
	$field_name = isset($_REQUEST['field_name'])?trim($_REQUEST['field_name']):'';
	$field_value = isset($_REQUEST['field_value'])?trim($_REQUEST['field_value']):'';
	
	$flag = 'true';
	
	if($field_name && $field_value)
	{
		//校验用户名是否重复
		if($field_name=='user_name')
		{
			$field_value = json_str_iconv($field_value);
			$user->field_name = $field_name;
		    if ($user->check_user($field_value) || admin_registered($field_value))
		    {
		        $flag = 'false';
		    }
	
		    
		    $user->field_mobile_phone = 'mobile_phone';
		    if ($user->check_mobile_phone($field_value))
		    {
		        $flag = 'false';
		    }
			
		}
		elseif($field_name=='CardId')
		{
			$uid = isset($_REQUEST['user_id'])?intval($_REQUEST['user_id']):0;
			$sql = "SELECT id FROM ".$ecs->table('user_info')." WHERE CardId='$field_value' AND uid>0 AND uid<>$uid ";
			
			$id = $db->getOne($sql);
			if($id)
			{
				$flag = 'false';
			}
			else
			{
				$flag = 'true';
			}
		}
		
		
		
	}
	
	die($flag);

}

else if($action == 'go_pay_form')
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
    include_once(ROOT_PATH . 'includes/lib_payment.php');
    include_once(ROOT_PATH . 'includes/lib_order.php');
    include_once(ROOT_PATH . 'includes/lib_clips.php');
    
    
	$order_id = $_REQUEST['order_id'];
	
	$order = order_info($order_id);
	
	if($order['pay_status']==PS_UNPAYED)
	{
		$payment_info = get_payment_order($order);
		
		/* 调用相应的支付方式文件 */
		include_once(ROOT_PATH . 'includes/modules/payment/' . $payment_info['pay_code'] . '.php');
		
		/* 取得在线支付方式的支付按钮 */
	    $pay_obj    = new $payment_info['pay_code'];
	    
	    $payment_info = unserialize_config($payment_info['pay_config']);
	    
	    $pay_form = $pay_obj->get_pay_form($order, $payment_info);	
		
	    die(json_encode(array('pay'=>$pay_form,'code'=>0))) ;
		
	}
	else
	{
		die(json_encode(array('code'=>1,'msg '=>'订单已支付！')));
		
	}
	
}

/**
 * 查询会员余额的数量
 * @access  public
 * @param   int     $user_id        会员ID
 * @return  int
 */
function get_user_surplus2($user_id,$where)
{
	//modify yes123 2014-12-05  重构
    $sql = "SELECT SUM(user_money) FROM " .$GLOBALS['ecs']->table('account_log').$where;  

    return $GLOBALS['db']->getOne($sql);
}
/**
 * 根据保单id获取保单号
 * @access  public
 * @param   int     $t_order_id   保单ID
 * @return  String 
 */
function get_warranty_no($policy_id)
{
    $sql = "SELECT policy_no FROM t_insurance_policy WHERE policy_id = $policy_id";
    $res= $GLOBALS['db']->getRow($sql);
    return $res['policy_no']; 
}


/**
 * add yes123 2014-12-11 图片上传
 * 
 * */
function upload_img($save_path,$max_size)
{
	$user_id = $_SESSION['user_id'];
	include_once(ROOT_PATH . 'includes/class/imageUtils.class.php');
	$arr = ImageUtils::imageUpload($save_path,$user_id,$max_size);
	return $arr;
}


//added by zhangxi, 20141219, Y502处理
function y502_assign_user_policy_info($list_subject, $smarty, $product_id, $policy_id, $policy)
{
	//start by zhangxi, 2014116, start
    	$product_info2 = get_product_info($product_id);
    	if($product_info2['attribute_type'] == 'Y502')
    	{
	    	//var_dump($product_info2);
	    	//added by zhangxi, 20141216, Y502,用户订单信息查看内容增加
	    	
//	    	$TMP_PATH= str_replace ( 'user.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
//			include_once($TMP_PATH. 'oop/classes/PACGroupInsurance.php');
//	    	$pacGroupInsurance = new PACGroupInsurance();
//	    	$list_subject_ids = $pacGroupInsurance->get_duty_price_ids_by_policy_id_multi_subjects($policy_id);
//	    	foreach($list_subject_ids as $key=>$list_duty)
//	    	{
//	    		$duty_price_ids=implode(',',$list_duty);
//	    		ss_log(__FUNCTION__." duty_price_ids:".$duty_price_ids);
//	    		$list_product_duty_price = $pacGroupInsurance->get_user_choosen_product_price_list($duty_price_ids);
//	    		$list_subject[$key]['list_product_duty_price'] = $list_product_duty_price;
//	    	}
	    	
	    	//var_dump($list_product_duty_price);
	    	//added by zhangxi, 20141219, 解决订单详细信息中，保险
	    	$arr_time=array();
			$arr_time['start_date']= $policy['start_date'];
			$arr_time['end_date'] = $policy['end_date'];
			$y502_period = get_interval_by_strtime('month', $arr_time);
	    	$smarty->assign('y502_period', $y502_period['interval']);
	    	//$smarty->assign('list_product_duty_price', $list_product_duty_price);//comment by zhangxi, 20150731, 以后可以去掉
	    }
	    $smarty->assign('attribute_type_y502', $product_info2['attribute_type'] );
	    //end by zhangxi, 20141216
	    
	    return $list_subject;
}

?>