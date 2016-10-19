<?php
session_start();
define('IN_ECS', true);
define('ECS_ADMIN', true);
require(dirname(__FILE__) . '/includes/init.php');
if ($_SESSION['user_id'] > 0)
{
	$smarty->assign('user_name', $_SESSION['user_name']);
}
/* 载入语言文件 */
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/user.php');

$user_id = $_SESSION['user_id'] ? $_SESSION['user_id'] : '';
$act = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'account_log';
$smarty->assign('action', $act);
$smarty->assign('act', $act);
/* 用户中心 */
if ($user_id <= 0){
	$smarty->assign('footer', get_footer());
	$smarty->assign('gourl', "account.php");
	$smarty->display('login.dwt');
	exit;
}
/* 会员充值和提现申请记录 */
if ($act == 'account_log'){
	include_once(ROOT_PATH . 'includes/lib_clips.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	
	$user = new User;
	$res = $user->getAccountLog();
	$account_log = $res['account_log'];
	$pager = $res['pager'];
	$condition = $res['condition'];
	$record_count = $pager['record_count'];
	
	
	/* 获取记录条数 */
	$pagebar = mobilePagebar($record_count,'account.php?act=account_log',$condition);
	$smarty->assign('pagebar' , $pagebar);
	$smarty->assign('condition', $condition);
	$smarty->assign('account_log', $account_log);
	
	//获取剩余余额
	$surplus_amount = get_user_surplus($user_id);
	if (empty($surplus_amount)){
		$surplus_amount = 0;
	}
	//模板赋值
	$smarty->assign('surplus_amount', price_format($surplus_amount, false));
	$smarty->assign('act', 'account_log');
	$smarty->display('account.dwt');
}
/* 会员账目明细界面 */
elseif ($act == 'account_detail')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');
    include_once(ROOT_PATH . 'includes/lib_order.php');
    //modify yes123 2015-01-05 和PC端合并
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user = new User;
	$res = $user->getAccountDetail();
	$account_log = $res['account_log'];
	$pager = $res['pager'];
	$condition = $res['condition'];
	$record_count = $pager['record_count'];
	
    //分页函数
	if($record_count){
		$pagebar = mobilePagebar($record_count,'account.php?act=account_detail',$condition);
		$smarty->assign('pagebar' , $pagebar);
	}

    //获取剩余余额
	$user_info = user_info ( $_SESSION ['user_id'] );
	$surplus_amount = $user_info['user_money'];
    
    if (empty($surplus_amount))
    {
        $surplus_amount = 0;
    }

    //模板赋值
    $smarty->assign('condition',    $condition);
    $smarty->assign('surplus_amount', price_format($surplus_amount, false));
    $smarty->assign('account_log',    $account_log);
    $smarty->assign('act', 'account_detail');
    $smarty->display('account.dwt');
}

//收入明细
elseif ($act == 'account_income')
{	
	$_LANG['ge_ren'];
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
	
	    //分页函数
	if($record_count){
		$pagebar = mobilePagebar($record_count,'account.php?act=account_income',$condition);
		$smarty->assign('pagebar' , $pagebar);
	}
	
	    //模板赋值
	$smarty->assign('user_money', $surplus_amount);
    $smarty->assign('shouru_money_total', price_format($shouru_money_total, false));
    $smarty->assign('condition',    $condition);
    $smarty->assign('account_log',    $account_log);
    $smarty->assign('act', $act);
    $smarty->display('account.dwt');
	
}

/* 删除会员余额 */
elseif ($act == 'cancel')
{
	include_once(ROOT_PATH . 'includes/lib_clips.php');

	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	if ($id == 0 || $user_id == 0)
	{
		header("Location: account.php?act=account_log\n");
		exit;
	}
	$result = del_user_account($id, $user_id);
	if ($result)
	{
		header("Location: account.php?act=account_log\n");
		exit;
	}
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
		ecs_header("Location: account.php?act=account_log\n");
		exit;
	}

	//如果原来的支付方式已禁用或者已删除, 重新选择支付方式
	if ($payment_id == 0)
	{
		ecs_header("Location: account.php?act=account_deposit&id=".$surplus_id."\n");
		exit;
	}

	//获取单条会员账目信息
	$order = array();
	$order = get_surplus_info($surplus_id);

	//支付方式的信息
	$payment_info = array();
	$payment_info = payment_info($payment_id);
	
	//获取剩余余额
	$surplus_amount = get_user_surplus($user_id);
	if (empty($surplus_amount)){
		$surplus_amount = 0;
	}
	$smarty->assign('surplus_amount', price_format($surplus_amount, false));
	
	/* 如果当前支付方式没有被禁用，进行支付的操作 */
	if (!empty($payment_info))
	{
		//取得支付信息，生成支付代码
		$payment = unserialize_config($payment_info['pay_config']);

		//生成伪订单号
		$order['order_sn'] = $surplus_id;

		//获取需要支付的log_id
		$order['log_id'] = get_paylog_id($surplus_id, $pay_type = PAY_SURPLUS);

		$order['user_name']	  = $_SESSION['user_name'];
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
		$smarty->assign('order', $order);
		$smarty->assign('pay_fee', price_format($payment_info['pay_fee'], false));
		$smarty->assign('amount', price_format($order['surplus_amount'], false));
		$smarty->assign('act', 'act_account');
		$smarty->display('account.dwt');
	}
	/* 重新选择支付方式 */
	else
	{
		include_once(ROOT_PATH . 'includes/lib_clips.php');

		$smarty->assign('payment', get_online_payment_list());
		$smarty->assign('order',   $order);
		$smarty->assign('act',  'account_deposit');
		$smarty->display('account.dwt');
	}
}
/* 会员预付款界面 */
elseif ($act == 'account_deposit')
{
	include_once(ROOT_PATH . 'includes/lib_clips.php');

	$surplus_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	$account	= get_surplus_info($surplus_id);

	//获取剩余余额
	$surplus_amount = get_user_surplus($user_id);
	if (empty($surplus_amount)){
		$surplus_amount = 0;
	}
	
	//只取微信支付
	$payment = get_online_payment_list(false,1);
	foreach ($payment as $key => $value ) {
    	  if($value['pay_code']!='weixinpay'){
    	  	unset($payment[$key]);
    	  }
	}
	
	$smarty->assign('operation_timestamp',time());//add yes123 2015-01-20 防止重复提交的时间戳 
	$smarty->assign('payment',$payment);
	$smarty->assign('surplus_amount', price_format($surplus_amount, false));
	$smarty->assign('order', $account);
	$smarty->assign('act', 'account_deposit');
	$smarty->display('account.dwt');
}
/*----------------------------------------
 *  提现
 -----------------------------------------*/
elseif($act == 'account_withdraw'){
	
	$user_id = $_SESSION['user_id'];
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
	$res = $user_obj->checkAccountWithdraw($user_id);
	
    /* 账号余额 */ 
	$smarty->assign('str_name', $_LANG['surplus_type_1']);

	//提现配置列表
	$smarty->assign('withdraw_rule_cfg_list',$res['withdraw_rule_cfg']);
	//手机号码
	$smarty->assign('mobile_phone',$res['user']['mobile_phone']);
	//银行列表
	$smarty->assign('banks',$res['bank_list']);
	
    $smarty->display('account.dwt');
}
/*----------------------------------------
 * 提现和充值处理
 ---------------------------------------- */
elseif($act == 'act_account'){
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
   $bid = isset($_POST['bid']) ? $_POST['bid'] : 0;
   //add yes123 2015-01-05 微信的提现
   $weixin = isset($_POST['weixin']) ? $_POST['weixin'] : 0;

    /* 变量初始化 */
    $surplus = array(
            'user_id'      => $user_id,
            'rec_id'       => !empty($_POST['rec_id'])      ? intval($_POST['rec_id'])       : 0,
            'process_type' => isset($_POST['surplus_type']) ? intval($_POST['surplus_type']) : 0,
            'payment_id'   => isset($_POST['payment_id'])   ? intval($_POST['payment_id'])   : 0,
            'operation_timestamp'   => isset($_POST['operation_timestamp'])   ? $_POST['operation_timestamp']  : time(),
            'user_note'    => isset($_POST['user_note'])    ? trim($_POST['user_note'])      : '',
            'money_type'    => isset($_POST['money_type'])    ? trim($_POST['money_type'])      : '',
            'amount'       => $amount,
			'bid' => $bid,
			'pay_code'   => $pay_code, //add yes123 2014-12-12 添加获取支付方式code判断是否需要支付凭据
			'pay_document' => $pay_document //支付凭据
    );


    /* 退款申请的处理 */
    if ($surplus['process_type'] == 1)
    {
    	$user_obj->withdrawMoney($surplus);
    	
    }
    /* 如果是会员预付款，跳转到下一步，进行线上支付的操作 */
    else
    {
    	//start add yes123 2015-01-20 先判断操作的时间戳是否不一直，如果一致，属于重复提交
    	$sql = "SELECT id FROM " . $GLOBALS['ecs']->table('user_account') .
			" WHERE user_id='$_SESSION[user_id]'  AND operation_timestamp = '" .$surplus['operation_timestamp'] . "'";
		$id = $GLOBALS['db']->getOne($sql);
		if($id)
		{
			header("location: account.php?process_type=0");exit;
		}
		//end add yes123 2015-01-20 先判断操作的时间戳是否不一直，如果一致，属于重复提交
    	
    	$res = $user_obj->rechargeMoney($surplus);
    	
		$order = $res['order'];
		
		$payment_info = $res['payment'];
		
		$amount = $res['amount'];

		/* 模板赋值 */
		$smarty->assign('payment', $payment_info);
		$smarty->assign('pay_fee', price_format($payment_info['pay_fee'], false));
		$smarty->assign('amount', price_format($amount, false));
		$smarty->assign('order', $order);
		$smarty->assign('act', 'act_account');
		$smarty->display('account.dwt');
    }
}
/*------------------------------------------------
 * 转账
 ------------------------------------------------*/
elseif($act == 'account_transfer'){
    include_once(ROOT_PATH . 'includes/lib_clips.php');
    include_once(ROOT_PATH . 'includes/lib_order.php');
    
    //获取剩余余额
    $surplus_amount = get_user_surplus($user_id);
    if(empty($surplus_amount)){
            $surplus_amount = 0;
    }
    $smarty->assign('user_money', $surplus_amount);
    
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
    $smarty->assign('act', 'account_transfer');
    $smarty->display('account.dwt');
}
/*-----------------------------------------------
 *  转账处理
 -----------------------------------------------*/
elseif ($act == 'transfer_accounts_update') //转账
{	
	require(ROOT_PATH . 'languages/zh_cn/admin/account_log.php');
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user = new User;
	$user->transferAccountsUpdate();	
}
//add yes123 20150608 提现确认信息
elseif ($act == 'withdraw_confirm_info')
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
/* 短信动态验证码 提现 充值 转账 */
elseif($act == 'withdrawals')
{	
    include_once(ROOT_PATH.'sdk_sms.php');
    include_once(ROOT_PATH . 'includes/class/User.class.php');
    $user_obj = new User;
    $arr = $user_obj->withdrawals();
    $num = $arr['num'];
    $result = $arr['result'];

	$num = md5($num);
	$datas = json_encode(array('data'=>$result,'num'=>$num));
	die($datas);
}
/**
 * 记录账户变动
 * @param   int     $user_id        用户id
 * @param   float   $user_money     可用余额变动
 * @param   float   $frozen_money   冻结余额变动
 * @param   int     $rank_points    等级积分变动
 * @param   int     $pay_points     消费积分变动
 * @param   string  $change_desc    变动说明
 * @param   int     $change_type    变动类型：参见常量文件
 */
function change_account_log($user_id, $user_money = 0,$frozen_money = 0, $rank_points = 0, $pay_points = 0, $change_desc = ''){
	ss_log("in function log_account_change,user_id ".$user_id." ,order_sn: ".@$order_sn." change_desc: ".$change_desc);
	ss_log("in function log_account_change,user_money: ".$user_money);
	/* 插入账户变动记录 */
        $account_log = array(
            'user_id'       => $user_id,
            'user_money'    => $user_money,
            'frozen_money'  => $frozen_money,
            'rank_points'   => $rank_points,
            'pay_points'    => $pay_points,
            'change_time'   => time(),
            'change_desc'   => $change_desc,
            'change_type'   => ACT_OTHER,
        );
 
    $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('account_log'), $account_log, 'INSERT');
    /* 更新用户信息 */
    $sql = "UPDATE " . $GLOBALS['ecs']->table('users') .
            " SET user_money = user_money + ('$user_money')," .
            " frozen_money = frozen_money + ('$frozen_money')," .
            " rank_points = rank_points + ('$rank_points')," .
            " pay_points = pay_points + ('$pay_points')" .
            " WHERE user_id = '$user_id' LIMIT 1";
    $GLOBALS['db']->query($sql);
}

/**
 * 查询会员余额的操作记录
 *
 * @access  public
 * @param   int	 $user_id	会员ID
 * @param   int	 $num		每页显示数量
 * @param   int	 $start	  开始显示的条数
 * @return  array
 */
function get_m_account_log($user_id, $num, $start, $where)
{       
	$account_log = array();
	$sql = 'SELECT * FROM ' .$GLOBALS['ecs']->table('user_account').
		   " WHERE user_id = '$user_id'" .
		   " AND process_type " . db_create_in(array(SURPLUS_SAVE, SURPLUS_RETURN)) .$where.
		   " ORDER BY add_time DESC";

	$res = $GLOBALS['db']->selectLimit($sql, $num, $start);

	if ($res)
	{
		while ($rows = $GLOBALS['db']->fetchRow($res))
		{
			$rows['add_time']		 = local_date($GLOBALS['_CFG']['date_format'], $rows['add_time']);
			$rows['admin_note']	   = nl2br(htmlspecialchars($rows['admin_note']));
			$rows['short_admin_note'] = ($rows['admin_note'] > '') ? sub_str($rows['admin_note'], 30) : 'N/A';
			$rows['user_note']		= nl2br(htmlspecialchars($rows['user_note']));
			$rows['short_user_note']  = ($rows['user_note'] > '') ? sub_str($rows['user_note'], 30) : 'N/A';
			$rows['pay_status']	   = ($rows['is_paid'] == 0) ? $GLOBALS['_LANG']['un_confirm'] : $GLOBALS['_LANG']['is_confirm'];
			$rows['amount']		   = price_format(abs($rows['amount']), false);

			/* 会员的操作类型： 冲值，提现 */
			if ($rows['process_type'] == 0)
			{
				$rows['type'] = $GLOBALS['_LANG']['surplus_type_0'];
			}
			else
			{
				$rows['type'] = $GLOBALS['_LANG']['surplus_type_1'];
			}

			/* 支付方式的ID */
			$sql = 'SELECT pay_id FROM ' .$GLOBALS['ecs']->table('payment').
				   " WHERE pay_name = '$rows[payment]' AND enabled = 1";
			$pid = $GLOBALS['db']->getOne($sql);

			/* 如果是预付款而且还没有付款, 允许付款 */
			if (($rows['is_paid'] == 0) && ($rows['process_type'] == 0))
			{
				$rows['handle'] = '<a href="account.php?act=pay&id='.$rows['id'].'&pid='.$pid.'">'.$GLOBALS['_LANG']['pay'].'</a>';
			}

			$account_log[] = $rows;
		}

		return $account_log;
	}
	else
	{
		 return false;
	}
}
?>