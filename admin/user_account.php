<?php


/**
 *  会员账目管理(包括预付款，余额)
 * ============================================================================
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/

define('IN_ECS', true);

require (dirname(__FILE__) . '/includes/init.php');

/* act操作项的初始化 */
if (empty ($_REQUEST['act'])) {
	$_REQUEST['act'] = 'list';
} else {
	$_REQUEST['act'] = trim($_REQUEST['act']);
}

/*------------------------------------------------------ */
//-- 会员余额记录列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list') {
	
	$process_type = $_REQUEST['process_type'];
	
	/* 权限判断 */
	//modify yes123 2014-12-19 充值提现分开
	if($process_type==0){
		admin_priv('surplus_manage');
		$smarty->assign('ur_here', $_LANG['09_user_account']);
	}
	else if($process_type==1)
	{
		admin_priv('surplus_manage2');
		$smarty->assign('ur_here', $_LANG['09_user_account2']);
	}



	/* 指定会员的ID为查询条件 */
	$user_id = !empty ($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

	/* 获得支付方式列表 */
	$payment = array ();
	$sql = "SELECT pay_id, pay_name FROM " . $ecs->table('payment') .
	" WHERE enabled = 1 AND pay_code != 'cod' ORDER BY pay_id";
	$res = $db->query($sql);

	while ($row = $db->fetchRow($res)) {
		$payment[$row['pay_name']] = $row['pay_name'];
	}

	/* 模板赋值 */
	if (isset ($_REQUEST['process_type'])) {
		$smarty->assign('process_type_' . intval($_REQUEST['process_type']), 'selected="selected"');
	}
	if (isset ($_REQUEST['is_paid'])) {
		$smarty->assign('is_paid_' . intval($_REQUEST['is_paid']), 'selected="selected"');
	}
	
	$smarty->assign('id', $user_id);
	$smarty->assign('payment_list', $payment);
	$smarty->assign('action_link', array (
		'text' => $_LANG['surplus_add'],
		'href' => 'user_account.php?act=add'
	));

	$list = account_list();
	$smarty->assign('list', $list['list']);
	$smarty->assign('filter', $list['filter']);
	$smarty->assign('record_count', $list['record_count']);
	$smarty->assign('page_count', $list['page_count']);
	$smarty->assign('full_page', 1);

	assign_query_info();
	$smarty->display('user_account_list.htm');
}

/*------------------------------------------------------ */
//-- 添加/编辑会员余额页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'edit') {
	admin_priv('surplus_manage'); //权限判断

	$ur_here = ($_REQUEST['act'] == 'add') ? $_LANG['surplus_add'] : $_LANG['surplus_edit'];
	$form_act = ($_REQUEST['act'] == 'add') ? 'insert' : 'update';
	$id = isset ($_GET['id']) ? intval($_GET['id']) : 0;

	/* 获得支付方式列表, 不包括“货到付款” */
	$user_account = array ();
	$payment = array ();
	$sql = "SELECT pay_id, pay_name FROM " . $ecs->table('payment') .
	" WHERE enabled = 1 AND pay_code != 'cod' ORDER BY pay_id";
	$res = $db->query($sql);

	while ($row = $db->fetchRow($res)) {
		$payment[$row['pay_name']] = $row['pay_name'];
	}

	if ($_REQUEST['act'] == 'edit') {
		/* 取得余额信息 */
		$user_account = $db->getRow("SELECT * FROM " . $ecs->table('user_account') . " WHERE id = '$id'");
		if($user_account['paid_time'])
		{
			$user_account['paid_time'] = date("Y-m-d H:i:s", $user_account['paid_time']) ; 
		}
		
		// 如果是负数，去掉前面的符号
		$user_account['amount'] = str_replace('-', '', $user_account['amount']);
		/* 查询当前的预付款信息 */
		$account = array ();
		$account = $user_account;
		//modify yes123 2015-01-25 时间格式化修正
		$account['add_time'] = date("Y-m-d H:i:s", $account['add_time']);
		//余额类型:预付款，退款申请，购买商品，取消订单
		if ($account['process_type'] == 0) {
			$process_type = $_LANG['surplus_type_0'];
		}
		elseif ($account['process_type'] == 1) {
			$process_type = $_LANG['surplus_type_1'];
		}
		elseif ($account['process_type'] == 2) {
			$process_type = $_LANG['surplus_type_2'];
		} else {
			$process_type = $_LANG['surplus_type_3'];
		}
		
		$sql = "SELECT user_name,real_name FROM " . $ecs->table('users') . " WHERE user_id = '$account[user_id]'";
		$user = $db->getRow($sql);
	
		$smarty->assign('real_name', $user['real_name']);
		$smarty->assign('user_name', $user['user_name']);
		
		/**
		 * 2014/8/6
		 * bhz 
		 * 获得账号
		 */
		$sql = "SELECT * FROM " . $ecs->table('bank') . " WHERE bid = (". "SELECT bid FROM " . $ecs->table('user_account')." WHERE id = $id".")";
		$bank_info = $db->getRow($sql);
	    $smarty->assign('bankinfo', $bank_info);
		/* 模板赋值 */
		$account['user_note'] = htmlspecialchars($account['user_note']);
		$smarty->assign('surplus', $account);
		$smarty->assign('process_type', $process_type);
		$smarty->assign('id', $id);
		$smarty->assign('user_money', get_user_surplus($account['user_id']));
		
	} else {
		$surplus_type = '';
		$user_name = '';
	}

	/* 模板赋值 */
	$smarty->assign('ur_here', $ur_here);
	$smarty->assign('form_act', $form_act);
	$smarty->assign('payment_list', $payment);
	$smarty->assign('action', $_REQUEST['act']);
	$smarty->assign('user_surplus', $user_account);
	$smarty->assign('user_name', $user['user_name']);
	if ($_REQUEST['act'] == 'add') {
		$href = 'user_account.php?act=list';
	} else {
		$href = 'user_account.php?act=list&' . list_link_postfix();
	}
	$smarty->assign('action_link', array (
		'href' => $href,
		'text' => $_LANG['09_user_account']
	));

	assign_query_info();
	$smarty->display('user_account_info.htm');
}

/*------------------------------------------------------ */
//- 添加/编辑会员余额的处理部分
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'insert' || $_REQUEST['act'] == 'update') {
	
	
	
	global $_LANG;
	require_once(ROOT_PATH . 'includes/lib_order.php');
	/* 权限判断 */
	admin_priv('surplus_manage');

	/* 初始化变量 */
	$id = isset ($_POST['id']) ? intval($_POST['id']) : 0;
	$is_paid = !empty ($_POST['is_paid']) ? intval($_POST['is_paid']) : 0;
	$amount = !empty ($_POST['amount']) ? floatval($_POST['amount']) : 0;
	$process_type = !empty ($_POST['process_type']) ? intval($_POST['process_type']) : 0;
	$user_name = !empty ($_POST['user_id']) ? trim($_POST['user_id']) : '';
	$admin_note = !empty ($_POST['admin_note']) ? trim($_POST['admin_note']) : '';
	$user_note = !empty ($_POST['user_note']) ? trim($_POST['user_note']) : '';
	$payment = !empty ($_POST['payment']) ? trim($_POST['payment']) : '';

	$user_id = $db->getOne("SELECT user_id FROM " . $ecs->table('users') . " WHERE user_name = '$user_name'");

	/* 此会员是否存在 */
	if ($user_id == 0) {
		$link[] = array (
			'text' => $_LANG['go_back'],
			'href' => 'javascript:history.back(-1)'
		);
		sys_msg($_LANG['username_not_exist'], 0, $link);
	}

	/* 退款，检查余额是否足够 */
	if ($process_type == 1) {
		$user_account = get_user_surplus($user_id);

		/* 如果扣除的余额多于此会员拥有的余额，提示 */
		if ($amount > $user_account) {
			$link[] = array (
				'text' => $_LANG['go_back'],
				'href' => 'javascript:history.back(-1)'
			);
			sys_msg($_LANG['surplus_amount_error'], 0, $link);
		}
	}

	if ($_REQUEST['act'] == 'insert') {
		
		
		/* 入库的操作 */
		if ($process_type == 1) {
			
			$amount = (-1) * $amount;
			
		}
		$sql = "INSERT INTO " . $ecs->table('user_account') ." (user_id,admin_user,amount,add_time,paid_time,admin_note,user_note,process_type,payment,is_paid) ".
		" VALUES ('$user_id', '$_SESSION[admin_name]', '$amount', '" . time() . "', '" . time() . "', '$admin_note', '$user_note', '$process_type', '$payment', '$is_paid')";
		$db->query($sql);
		$id = $db->insert_id();
		
		//只有提现才更新会员余额
		if ($process_type == 1) {
			$change_data = array(
				'user_id'=>$user_id,
				'user_money'=>$amount,
				'change_desc'=>$_LANG['surplus_type_1'],
				'account_name'=>'user_money',
			);
			
			$id = new_log_account_change($change_data);
		}
		
	} else {
		/* 更新数据表 */
		$sql = "UPDATE " . $ecs->table('user_account') . " SET " .
		"admin_note   = '$admin_note', " .
		"user_note    = '$user_note', " .
		"payment      = '$payment' " .
		"WHERE id      = '$id'";
		$db->query($sql);
	}

	// 如果已完成，并且不等于提现，则更新会员余额
	if ($is_paid == 1 && $process_type!=1) {
		
		$change_desc = $amount > 0 ? $_LANG['surplus_type_0'] : $_LANG['surplus_type_1'];
		$change_type = $amount > 0 ? ACT_SAVING : ACT_DRAWING;
		log_account_change($user_id, $amount, 0, 0, 0, $change_desc, $change_type);
	}

	//如果是预付款并且未确认，向pay_log插入一条记录
	if ($process_type == 0 && $is_paid == 0) {
		include_once (ROOT_PATH . 'includes/lib_order.php');

		/* 取支付方式信息 */
		$payment_info = array ();
		$payment_info = $db->getRow('SELECT * FROM ' . $ecs->table('payment') .
		" WHERE pay_name = '$payment' AND enabled = '1'");
		//计算支付手续费用
		$pay_fee = pay_fee($payment_info['pay_id'], $amount, 0);
		$total_fee = $pay_fee + $amount;

		/* 插入 pay_log */
		$sql = 'INSERT INTO ' . $ecs->table('pay_log') . " (order_id, order_amount, order_type, is_paid)" .
		" VALUES ('$id', '$total_fee', '" . PAY_SURPLUS . "', 0)";
		$db->query($sql);
	}

	/* 记录管理员操作 */
	if ($_REQUEST['act'] == 'update') {
		admin_log($user_name, 'edit', 'user_surplus');
	} else {
		$req_type=$_REQUEST['process_type']==0?"充值申请:":"提现申请:";
		$is_paid_str=$_REQUEST['is_paid']==0?",未确认":",已完成";
		$log_info=$req_type." 目标账户".$user_name.",金额".$amount."元".$is_paid_str;
		admin_log2($log_info);
	}

	/* 提示信息 */
	if ($_REQUEST['act'] == 'insert') {
		$href = 'user_account.php?act=list';
	} else {
		$href = 'user_account.php?act=list&' . list_link_postfix();
	}
	$link[0]['text'] = $_LANG['back_list'];
	$link[0]['href'] = $href;

	$link[1]['text'] = $_LANG['continue_add'];
	$link[1]['href'] = 'user_account.php?act=add';

	sys_msg($_LANG['attradd_succed'], 0, $link);
	
	
}

/*------------------------------------------------------ */
//-- 审核会员余额页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'check') {
	/* 检查权限 */
	admin_priv('surplus_manage');
	/* 初始化 */
	$id = isset ($_GET['id']) ? intval($_GET['id']) : 0;
	/* 如果参数不合法，返回 */
	if ($id == 0) {
		ecs_header("Location: user_account.php?act=list\n");
		exit;
	}

	/* 查询当前的预付款信息 */
	$account = array ();
	$sql = "SELECT * FROM " . $ecs->table('user_account') . " WHERE id = '$id'";
	$account = $db->getRow($sql);
	//$order['formated_add_time'] =  date("Y-m-d H:i:s", $order['add_time']);
	//modify yes123 2015-01-25 时间格式化修正
	//$account['add_time'] = local_date($_CFG['time_format'], $account['add_time']);
	$account['add_time'] = date("Y-m-d H:i:s", $account['add_time']);
	//余额类型:预付款，退款申请，购买商品，取消订单
	if ($account['process_type'] == 0) {
		$process_type = $_LANG['surplus_type_0'];
	}
	elseif ($account['process_type'] == 1) {
		$process_type = $_LANG['surplus_type_1'];
	}
	elseif ($account['process_type'] == 2) {
		$process_type = $_LANG['surplus_type_2'];
	} else {
		$process_type = $_LANG['surplus_type_3'];
	}
	$sql = "SELECT user_name, real_name FROM " . $ecs->table('users') . " WHERE user_id = '$account[user_id]'";
	$user = $db->getRow($sql);
	$smarty->assign('real_name', $user['real_name']);
	
	
	/**
	 * 2014/8/6
	 * bhz 
	 * 获得账号
	 */
	$sql = "SELECT * FROM " . $ecs->table('bank') . " WHERE bid = (". "SELECT bid FROM " . $ecs->table('user_account')." WHERE id = $id".")";
	$bank_info = $db->getRow($sql);
    $smarty->assign('bankinfo', $bank_info);
	/* 模板赋值 */
	$smarty->assign('ur_here', $_LANG['check']);
	$account['user_note'] = htmlspecialchars($account['user_note']);
	$smarty->assign('surplus', $account);
	$smarty->assign('process_type', $process_type);
	$smarty->assign('user_name', $user['user_name']);
	$smarty->assign('id', $id);
	$smarty->assign('user_money', get_user_surplus($account['user_id']));
	$smarty->assign('action_link', array (
		'text' => $_LANG['09_user_account'],
		'href' => 'user_account.php?act=list&' . list_link_postfix()
	));

	/* 页面显示 */
	assign_query_info();
	$smarty->display('user_account_check.htm');
}

/*------------------------------------------------------ */
//-- 更新会员余额的状态
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'action') {
	require_once(ROOT_PATH . 'includes/lib_order.php');
	/* 检查权限 */
	admin_priv('surplus_manage');

	/* 初始化 */
	$id = isset ($_POST['id']) ? intval($_POST['id']) : 0;
	$is_paid = isset ($_POST['is_paid']) ? intval($_POST['is_paid']) : 0;
	$admin_note = isset ($_POST['admin_note']) ? trim($_POST['admin_note']) : '';

	/* 如果参数不合法，返回 */
	if ($id == 0 || empty ($admin_note)) {
		ecs_header("Location: user_account.php?act=list\n");
		exit;
	}

	/* 查询当前的预付款信息 */
	$account = array ();
	$account = $db->getRow("SELECT * FROM " . $ecs->table('user_account') . " WHERE id = '$id'");
	$amount = $account['amount'];
	$user_name = $db->getOne("SELECT user_name FROM " . $ecs->table('users') . " WHERE user_id = ".$account['user_id'] );
	//echo $user_name;
	/*查询用户名，方便做日志记录 2014-09-25 yes123*/
	

	//如果状态为未确认
	if ($account['is_paid'] == 0) {
		//如果是退款申请, 并且已完成,更新此条记录,扣除相应的余额
		if ($is_paid == '1' && $account['process_type'] == '1') 
		{
            $user_account = get_user_surplus($account['user_id']);
            $fmt_amount   = str_replace('-', '', $amount);

            //如果扣除的余额多于此会员拥有的余额，提示
            if ($fmt_amount > $user_account)
            {
                $link[] = array('text' => $_LANG['go_back'], 'href'=>'javascript:history.back(-1)');
                sys_msg($_LANG['surplus_amount_error'], 0, $link);
            }

            update_user_account($id, $amount, $admin_note, $is_paid);
          
 
            //更新会员余额数量
            log_account_change($account['user_id'], $amount, 0, 0, 0, $_LANG['surplus_type_1'], ACT_DRAWING);
        
        }
		elseif ($is_paid == '1' && $account['process_type'] == '0') {
			//如果是预付款，并且已完成, 更新此条记录，增加相应的余额
			update_user_account($id, $amount, $admin_note, $is_paid);
			//更新会员余额数量
			log_account_change($account['user_id'], $amount, 0, 0, 0, $_LANG['surplus_type_0'], ACT_SAVING);
			
		}
		elseif ($is_paid == '0') {
			/* 否则更新信息 */
			$sql = "UPDATE " . $ecs->table('user_account') . " SET " .
			"admin_user    = '$_SESSION[admin_name]', " .
			"admin_note    = '$admin_note', " .
			"is_paid       = 0 WHERE id = '$id'";
			$db->query($sql);
		}

		/* 记录管理员日志 2014-09-25 yes123*/
		//admin_log('(' . addslashes($_LANG['check']) . ')' . $admin_note, 'edit', 'user_surplus');
		$is_paid_str = $is_paid==0?",未确认":",已确认";
		$log_info = "编辑会员余额(到款审核),金额:".$amount."元,".$user_name.$is_paid_str;
		admin_log2($log_info.", 备注:".$admin_note);
		
		/* 提示信息 */
		$link[0]['text'] = $_LANG['back_list'];
		$link[0]['href'] = 'user_account.php?act=list&' . list_link_postfix();

		sys_msg($_LANG['attradd_succed'], 0, $link);
	}
}

/*------------------------------------------------------ */
//-- ajax账户信息列表
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query') {
	$list = account_list();
	$smarty->assign('list', $list['list']);
	$smarty->assign('filter', $list['filter']);
	$smarty->assign('record_count', $list['record_count']);
	$smarty->assign('page_count', $list['page_count']);

	$sort_flag = sort_flag($list['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);

	make_json_result($smarty->fetch('user_account_list.htm'), '', array (
		'filter' => $list['filter'],
		'page_count' => $list['page_count']
	));
}
/*------------------------------------------------------ */
//-- ajax删除一条信息
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove') {
	/* 检查权限 */
	check_authz_json('surplus_manage');
	$id = @ intval($_REQUEST['id']);
	$sql = "SELECT u.user_name FROM " . $ecs->table('users') . " AS u, " .
	$ecs->table('user_account') . " AS ua " .
	" WHERE u.user_id = ua.user_id AND ua.id = '$id' ";
	$user_name = $db->getOne($sql);
	$sql = "DELETE FROM " . $ecs->table('user_account') . " WHERE id = '$id'";
	if ($db->query($sql, 'SILENT')) {
		admin_log(addslashes($user_name), 'remove', 'user_surplus');
		$url = 'user_account.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
		ecs_header("Location: $url\n");
		exit;
	} else {
		make_json_error($db->error());
	}
}
//add yes123 2014-12-23 发送短信
elseif ($_REQUEST['act'] == 'send_sms') {
	include_once(ROOT_PATH.'sdk_sms.php');
	$content = trim($_REQUEST['content']);
	$content = iconv( "UTF-8", "gb2312//IGNORE" ,$content);
	$id = $_REQUEST['id'];
	$sql = "SELECT mobile_phone FROM " . $GLOBALS['ecs']->table('users') .
	" WHERE user_id = (SELECT user_id FROM " . $GLOBALS['ecs']->table('user_account') .
	" WHERE id = '$id')";
	
	$mobile_phone = $GLOBALS['db']->getOne($sql);
	if($mobile_phone)
	{
		$result = gxmt_post($sdk_sn,$sdk_pwd,$mobile_phone,$content);
		if($result<0){
			//1.发邮件给管理员
			 $content = "短信余额不足,请及时充值!";
				//send_mail( $_CFG['shop_name']."管理员", "25190080@qq.com","短信余额不足", $content,0);
			//2.写日志
			$sql = "INSERT INTO " . $GLOBALS['ecs']->table('admin_log') . " (log_time, user_id, log_info, ip_address, log_level) " .
					"VALUES (".time().", '0', '$content', '".real_ip()."', 'error')";
						
			$GLOBALS['db']->query($sql);
			$datas = json_encode(array('code'=>$result,'msg'=>'发送失败'));
	
		}
		else
		{
			$datas = json_encode(array('code'=>$result,'msg'=>'发送成功','sql'=>$sql));
		}
		
		die($datas);
	}

}
//add yes123 2015-05-28  提现申请规则
elseif ($_REQUEST['act'] == 'withdraw_rule_cfg_list') 
{
	/* 检查权限 */
	$res = withdraw_rule_cfg_list();	
	$organ_id = $_REQUEST['institution_id'];
    $smarty->assign('config_list',    $res['config_list']);

    $smarty->assign('ur_here',    $ur."-提现规则列表");
    $add_url ="user_account.php?act=withdraw_rule_cfg_add";
    $smarty->assign('filter',       $res['filter']);
    $smarty->assign('record_count', $res['record_count']);
    $smarty->assign('page_count',   $res['page_count']);
    $smarty->assign('full_page',    1);
    $smarty->assign('action_link',  array('text' =>'添加配置', 'href'=>$add_url));
    $smarty->assign('sort_user_id', '<img src="images/sort_desc.gif">');

	$smarty->display('withdraw_rule_cfg_list.htm');
}
//add yes123 2015-05-28  提现申请规则
elseif ($_REQUEST['act'] == 'withdraw_rule_cfg_add') 
{
	global $money_type_arr;
	$id = isset($_GET['id'])?$_GET['id']:0;
	if($id)
	{
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('withdraw_rule_cfg')." WHERE id=$id ";
		$smarty->assign('wrc',  $GLOBALS['db']->getRow($sql));
	}
	
	$smarty->assign('money_type_arr',    $money_type_arr);
	$smarty->display('withdraw_rule_cfg_info.htm');
}
//add yes123 2015-05-28  提现申请规则
elseif ($_REQUEST['act'] == 'withdraw_rule_cfg_save') 
{
	$id = isset($_POST['id'])?$_POST['id']:0;
	$data = array();
	$data['month_withdraw_num'] = $_POST['month_withdraw_num'];//每月提现次数
	$data['min_withdraw_money'] = $_POST['min_withdraw_money'];//提现最小金额
	$data['max_withdraw_money'] = $_POST['max_withdraw_money'];//提现最大金额
	$data['income_tax'] = $_POST['income_tax'];//个人所得税
	$data['withdraw_poundage'] = $_POST['withdraw_poundage'];//手续费
	$data['mobile_phone'] = $_POST['mobile_phone'];//财务电话
	$data['money_type'] = $_POST['money_type'];//提现目标，例如，充值，服务费，奖励等
	$data['is_permit_withdraw'] = $_POST['is_permit_withdraw'];//提现目标，例如，充值，服务费，奖励等
	
	if($id) //ID存在则更新
	{
		
		 $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('withdraw_rule_cfg'), $data, 'UPDATE', "id = '$id'");
		
	}
	else
	{
		
		 $sql =" SELECT id FROM " . $GLOBALS['ecs']->table('withdraw_rule_cfg') ." WHERE money_type='$data[money_type]'";
		 $old_id  = $GLOBALS['db']->getOne($sql);
		 if($old_id)
		 {
		 	echo "<script>alert('添加失败，此规则已存在！');window.location.href='user_account.php?act=withdraw_rule_cfg_list';</script>";
			exit;
		 }
		 
		 $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('withdraw_rule_cfg'), $data, 'INSERT');
		
	}
	
	echo "<script>alert('保存成功');window.location.href='user_account.php?act=withdraw_rule_cfg_list';</script>";
	exit;
	
}
//add yes123 2015-05-28  提现申请规则
elseif ($_REQUEST['act'] == 'withdraw_rule_cfg_remove') 
{
	$id=$_GET['id'];
	if($id)
	{
		$sql =" DELETE FROM ". $GLOBALS['ecs']->table('withdraw_rule_cfg') ." WHERE id='$id'";
		$res = $GLOBALS['db']->query($sql);
		if($res)
		{
			die(json_encode(array (
					'code' => 0,
					'msg' => '已删除！'
			)));
		}
	}
	
}
/*------------------------------------------------------ */
//-- 会员余额函数部分
/*------------------------------------------------------ */
/**
 * 查询会员余额的数量
 * @access  public
 * @param   int     $user_id        会员ID
 * @return  int
 */
function get_user_surplus($user_id) {
	$sql = "SELECT SUM(user_money) FROM " . $GLOBALS['ecs']->table('account_log') .
	" WHERE user_id = '$user_id'";

	return $GLOBALS['db']->getOne($sql);
}

/**
 * 更新会员账目明细
 *
 * @access  public
 * @param   array     $id          账目ID
 * @param   array     $admin_note  管理员描述
 * @param   array     $amount      操作的金额
 * @param   array     $is_paid     是否已完成
 *
 * @return  int
 */
function update_user_account($id, $amount, $admin_note, $is_paid) {
	$sql = "UPDATE " . $GLOBALS['ecs']->table('user_account') . " SET " .
	"admin_user  = '$_SESSION[admin_name]', " .
	"amount      = '$amount', " .
	"paid_time   = '" . time() . "', " .
	"admin_note  = '$admin_note', " .
	"is_paid     = '$is_paid' WHERE id = '$id'";
	return $GLOBALS['db']->query($sql);
}

/**
 *
 *
 * @access  public
 * @param
 *
 * @return void
 */
function account_list() {
	$result = get_filter();
	
	if ($result === false) {
		/* 过滤列表 */
		$filter['user_id'] = !empty ($_REQUEST['user_id']) ? intval($_REQUEST['user_id']) : 0;
		$filter['keywords'] = empty ($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);
		if (isset ($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1) {
			$filter['keywords'] = json_str_iconv($filter['keywords']);
		}

		$filter['process_type'] = isset ($_REQUEST['process_type']) ? intval($_REQUEST['process_type']) : -1;
		$filter['payment'] = empty ($_REQUEST['payment']) ? '' : trim($_REQUEST['payment']);
		$filter['real_name'] = empty ($_REQUEST['real_name']) ? '' : trim($_REQUEST['real_name']);
		$filter['is_paid'] = isset ($_REQUEST['is_paid']) ? intval($_REQUEST['is_paid']) : -1;
		$filter['sort_by'] = empty ($_REQUEST['sort_by']) ? 'add_time' : trim($_REQUEST['sort_by']);
		$filter['sort_order'] = empty ($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
		$filter['start_date'] = empty ($_REQUEST['start_date']) ? '' : $_REQUEST['start_date'];
		$filter['end_date'] = empty ($_REQUEST['end_date']) ? '' : $_REQUEST['end_date'];

		$where = " WHERE 1 ";
		if ($filter['user_id'] > 0) {
			$where .= " AND ua.user_id = '$filter[user_id]' ";
		}
		if ($filter['process_type'] != -1) {
			$where .= " AND ua.process_type = '$filter[process_type]' ";
		} else {
			$where .= " AND ua.process_type " . db_create_in(array (
				SURPLUS_SAVE,
				SURPLUS_RETURN
			));
		}
		if ($filter['payment']) {
			$where .= " AND ua.payment = '$filter[payment]' ";
		}
		
		if ($filter['real_name']) {
			//modify yes123 2015-01-21 .真名查询优化
			include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
			$user_id_by_realName="SELECT user_id FROM ".$GLOBALS['ecs']->table('users')." WHERE real_name LIKE '%".$filter['real_name']."%' ";
			$user_id_list = $GLOBALS['db']->getAll($user_id_by_realName);
			$user_ids = CommonUtils :: arrToStr($user_id_list, "user_id");
			if($user_ids){
				$where .= " AND ua.user_id IN($user_ids) ";
			}else{
				$where .= " AND ua.user_id =0 ";
			}
		}
		
		if ($filter['is_paid'] != -1) {
			$where .= " AND ua.is_paid = '$filter[is_paid]' ";
		}

		if ($filter['keywords']) {
			$where .= " AND u.user_name LIKE '%" . mysql_like_quote($filter['keywords']) . "%'";
			$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('user_account') . " AS ua, " .
			$GLOBALS['ecs']->table('users') . " AS u " . $where;
		}
		/*　时间过滤　*/
		if (!empty ($filter['start_date'])) {
			$where .= "AND add_time >= '" .strtotime( $filter['start_date'])."' ";
		}
		
		if (!empty ($filter['end_date'])) {
			$where .= "AND add_time <= '" . strtotime($filter['end_date']) . "' ";
		}

		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('user_account') . " AS ua, " .
		$GLOBALS['ecs']->table('users') . " AS u " . $where." AND ua.user_id=u.user_id ";
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);
		/* 分页大小 */
		$filter = page_and_size($filter);

		/* 查询数据 */
		$sql = 'SELECT ua.*, u.user_name,u.real_name,u.user_money FROM ' .
		$GLOBALS['ecs']->table('user_account') . ' AS ua LEFT JOIN ' .
		$GLOBALS['ecs']->table('users') . ' AS u ON ua.user_id = u.user_id' .
		$where . "ORDER by " . $filter['sort_by'] . " " . $filter['sort_order'] . " LIMIT " . $filter['start'] . ", " . $filter['page_size'];

		$filter['keywords'] = stripslashes($filter['keywords']);
		set_filter($filter, $sql);
	} else {
		$sql = $result['sql'];
		$filter = $result['filter'];
	}
	
	
	$list = $GLOBALS['db']->getAll($sql);
	foreach ($list AS $key => $value) {
		$list[$key]['surplus_amount'] = price_format(abs($value['amount']), false);
		//modify yes123 2015-01-25时间转换调整
		//$list[$key]['add_date'] = local_date($GLOBALS['_CFG']['time_format'], $value['add_time']);
		$list[$key]['add_date'] = date("Y-m-d H:i:s", $value['add_time']) ; 
		if($value['paid_time'])
		{
			$list[$key]['paid_time'] = date("Y-m-d H:i:s", $value['paid_time']) ; 
			
		}
		$list[$key]['process_type_name'] = $GLOBALS['_LANG']['surplus_type_' . $value['process_type']];
	}
	$arr = array (
		'list' => $list,
		'filter' => $filter,
		'page_count' => $filter['page_count'],
		'record_count' => $filter['record_count']
	);
	

	return $arr;
}

function withdraw_rule_cfg_list() 
{
	$result = get_filter();
    if ($result === false)
    {
		$where_sql="";
		$record_count_sql = " SELECT COUNT(id) FROM bx_withdraw_rule_cfg ";
		$filter['record_count'] = $GLOBALS['db']->getOne($record_count_sql);

        /* 分页大小 */
        $filter = page_and_size($filter);
		$sql=" SELECT * FROM bx_withdraw_rule_cfg $where_sql ";
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

    $config_list = $GLOBALS['db']->getAll($sql);
	global $money_type_arr;
	foreach ( $config_list as $key => $value ) {
       $config_list[$key]['money_type']= $money_type_arr[$value['money_type']];
	   $config_list[$key]['is_permit_withdraw'] =$value['is_permit_withdraw']=='y'?'是':'否';       
	}
	
    $arr = array('config_list' => $config_list, 'filter' => $filter,
                 'page_count' => $filter['page_count'],
                 'record_count' => $filter['record_count']);
    return $arr;
}

?>