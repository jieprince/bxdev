<?php


/**
 * add wangcya, 2014-12-31
 * 用户中心类
 * 
 */
require_once (ROOT_PATH . 'baoxian/common.php');
require_once (ROOT_PATH . 'baoxian/source/function_debug.php');

define('MEETING_ATTRIBUTE_ID', 37);
define('MEETING_GID', 69);

class MeetingActive {

	public function MeetingActive() 
	{
		
		
	}
	
	
	public function pay_meeting($user_id,
			                    $mr_id//我的会议id
			                   )
	{
	
		ss_log("将要跳转到让活动参与者缴费页面");
		
		if(!$mr_id|| !$user_id)
		{
			ss_log("mr_id OR user_id为空");
			return;
		}
		
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('meeting_register') . " WHERE uid='$user_id' AND mr_id='$mr_id'";
		ss_log($sql);
		$attr_my_activity = $GLOBALS['db']->getRow($sql);
		if($attr_my_activity)
		{
			$user_id = $attr_my_activity['uid'];
			$act_id  = $attr_my_activity['act_id'];
			
			include_once(ROOT_PATH . 'includes/hongdong_function.php');
			$product_info = get_product_attribute_info_by_activity_id($act_id);
				
			$goods_id = $product_info['goods_id'];
			$premium = $product_info['premium'];
			$attribute_id = $product_info['attribute_id'];
			$attribute_name = $product_info['attribute_name'];
			
			ss_log("goods_id: ".$goods_id);
			ss_log("user_id: ".$user_id);
			ss_log("act_id: ".$act_id);
			ss_log("attribute_id: ".$attribute_id);
			ss_log("attribute_name: ".$attribute_name);
			ss_log("premium: ".$premium);
			
			/////////////////////////////////////////////////
			//$agent_username = $_SGLOBAL['supe_username'];
			$cart_insure_attr = array(	"user_id"=>$user_id,
										"product_id"=>$attribute_id,//MEETING_ATTRIBUTE_ID,//37,$product['attribute_id'],
										"product_name"=>$attribute_name,//"MDRT参会报名",//$product['attribute_name'],
										"total_apply_num"=>1,//$total_apply_num,
										//"type"=>"meeting",//$total_apply_num,
										"mr_id"=>$mr_id,
										"total_price"=>$premium//200.00//$totalModalPremium
										);
			
			$cart_insure_id = inserttable("cart_insure", $cart_insure_attr,true);
			
			////////////////////////////////////////////////
			//$cart_insure_id = 1363;
			$uid = $user_id;//$_SESSION['user_id'];
			$gid = $goods_id;//MEETING_GID;//69;
	
			$url = "order.php?act=order_lise_meeting&gid=$gid&cart_insure_id=$cart_insure_id&is_weixin=1&uid=$uid";//支付方式页
			//header("location: ".$url);//跳转到微信的支付页面
			//exit;
			return $url;
		}
		else
		{
			ss_log("没找到我的参会");
		}
		
	}
	
	
	//start add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能
	
	public function add_bx_meeting()
	{
		global $smarty;

		//通过这里到订单生成页
		//add by dingchaoyang 2014-12-19 如果会话失效 统一跳转到user.php登录界面
		if (empty($_SESSION['user_id']))
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ../user.php");
			exit;
		}
		
		
		///////////////////////////////////////////////////////////////////
		$res = array();
	
		$gid = $_GET['gid'];
		
		ss_log("folw add_bx, gid: ".$_GET['gid']);
		
		$cart_insure_id = intval($_GET['cart_insure_id']);//add by wangcya, for bug[193],批量投保的id,一束
		ss_log("add_bx , cart_insure_id: ".$cart_insure_id);
		
		/* added by zhangxi, 20150127, 活动 */
		$goods_id = intval($_GET['gid']);
		$goods = get_goods_info($goods_id);
		$smarty->assign('goods', $goods);
		//if($goods['attribute_type'] == "lvyouhuodong"
		//|| $goods['attribute_type'] == "jingwailvyouhuodong")
			
		/*
		global $G_WORDS_HUODONG;
		if(strstr($goods['attribute_type'],$G_WORDS_HUODONG))//险种属性中只要含有huodong字符，则进入
		{
			if(intval($_GET['uid'])>0)
			{
	
				$_SESSION['user_id'] = intval($_GET['uid']);
				global $_SGLOBAL;
				$sql = "SELECT user_id, user_name FROM bx_users WHERE user_id = '" .$_SESSION['user_id']. "'";
				$query = $_SGLOBAL['db']->query($sql);
				$row = $_SGLOBAL['db']->fetch_array($query);
	
				$_SESSION['user_name'] = $row['user_name'];
	
				$_SGLOBAL['supe_uid'] = $_SESSION['user_id'];
				// supe_username
				$_SGLOBAL['supe_username']=$_SESSION['user_name'];
			}
	
		}
		*/
	

	
		/*
		//comment by zhangxi, 20150127, 单个投保这里也是会走的
		if($cart_insure_id)//批量投保下的所有投保单
		{
			$sql="select * from t_insurance_policy where cart_insure_id=$cart_insure_id";
			ss_log($sql);
			$list_bx_policy = $GLOBALS['db']->getAll($sql);
			 
			$smarty->assign('list_bx_policy',    $list_bx_policy);
			//价格都在投保单那里，而且是总的，可能一个投保单购买的产品有多份。
			$sql="select SUM(total_premium) as total_premium_mutil from t_insurance_policy where cart_insure_id=$cart_insure_id";
			ss_log(__FUNCTION__.":SQL=".$sql);
			 
			//计算得到批量投保的总保费
			$total_premium_mutil = $GLOBALS['db']->getOne($sql);
			ss_log("add_bx , total_premium_mutil: ".$total_premium_mutil);
			$_SESSION['total_premium'] = $total_premium_mutil;
			 
			$smarty->assign('cart_insure_id',    $cart_insure_id);
			$smarty->assign('total_premium_mutil',    $total_premium_mutil);
		}
		*/
		
		$list_bx_policy = NULL;
		
		$sql="select total_price from t_cart_insure where rec_id='$cart_insure_id'";
		ss_log($sql);
		$total_premium_mutil = $GLOBALS['db']->getOne($sql);
		
		ss_log("total_premium_mutil: ".$total_premium_mutil);
		
		$smarty->assign('cart_insure_id',    $cart_insure_id);
		$smarty->assign('total_premium_mutil',    $total_premium_mutil);
		

	
		/*------------------------------------------------------ */
		//-- 订单确认
		/*------------------------------------------------------ */
	
		/* 取得购物类型 */
		$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;
		$_SESSION['flow_order']['extension_code'] = '';
	
	
		$consignee = get_consignee($_SESSION['user_id']);
	
		/* 对商品信息赋值 */
		$cart_goods = cart_goods($flow_type); // 取得商品列表，计算合计
		$smarty->assign('goods_list', $cart_goods);
		$smarty->assign('gid', $gid);
	
	
		/*
		 * 取得订单信息
		*/
		$order = flow_order_info();
		$smarty->assign('order', $order);
	
		$total_premium = $total_premium_mutil;//add by wangcya , 20141225, 计算总价，这个很关键
	
		ss_log("before bx_order_fee， total_premium：".$total_premium);
		$total = bx_order_fee($order, $total_premium, $consignee, $list_bx_policy, $gid);//comment by wangcya , 20141225, 计算总价，这个很关键
	
		$smarty->assign('total', $total);
	
		/* 取得支付列表 */
		if ($order['shipping_id'] == 0)
		{
			$cod        = true;
			$cod_fee    = 0;
		}
		else
		{
			$shipping = shipping_info($order['shipping_id']);
			$cod = $shipping['support_cod'];
	
			if ($cod)
			{
				/* 如果是团购，且保证金大于0，不能使用货到付款 */
				if ($flow_type == CART_GROUP_BUY_GOODS)
				{
					$group_buy_id = $_SESSION['extension_id'];
					if ($group_buy_id <= 0)
					{
						show_message('error group_buy_id');
					}
					$group_buy = group_buy_info($group_buy_id);
					if (empty($group_buy))
					{
						show_message('group buy not exists: ' . $group_buy_id);
					}
	
					if ($group_buy['deposit'] > 0)
					{
						$cod = false;
						$cod_fee = 0;
	
						/* 赋值保证金 */
						$smarty->assign('gb_deposit', $group_buy['deposit']);
					}
				}
	
				if ($cod)
				{
					$shipping_area_info = shipping_area_info($order['shipping_id'], $region);
					$cod_fee            = $shipping_area_info['pay_fee'];
				}
			}
			else
			{
				$cod_fee = 0;
			}
		}
		// 给货到付款的手续费加<span id>，以便改变配送的时候动态显示
		if($_REQUEST['is_weixin']==1){
			$payment_list = available_payment_list(1, $cod_fee,1);
		}else{
			$payment_list = available_payment_list(1, $cod_fee);
		}
	
		if(isset($payment_list))
		{
			foreach ($payment_list as $key => $payment)
			{
				//add yes123 2015-01-22 下订单时 不支持银行汇款转账
				if($payment['pay_code']=='bank')
				{
					unset($payment_list[$key]);
				}
	
				if ($payment['is_cod'] == '1')
				{
					$payment_list[$key]['format_pay_fee'] = '<span id="ECS_CODFEE">' . $payment['format_pay_fee'] . '</span>';
				}
				/* 如果有易宝神州行支付 如果订单金额大于300 则不显示 */
				if ($payment['pay_code'] == 'yeepayszx' && $total['amount'] > 300)
				{
					unset($payment_list[$key]);
				}
				/* 如果有余额支付 */
				if ($payment['pay_code'] == 'balance')
				{
					/* 如果未登录，不显示 */
					if ($_SESSION['user_id'] == 0)
					{
						unset($payment_list[$key]);
					}
					else
					{
						if ($_SESSION['flow_order']['pay_id'] == $payment['pay_id'])
						{
							$smarty->assign('disable_surplus', 1);
						}
					}
				}
			}
		}
		$smarty->assign('payment_list', $payment_list);
	
		$user_info = user_info($_SESSION['user_id']);
		/* 如果使用余额，取得用户余额 */
		if ((!isset($_CFG['use_surplus']) || $_CFG['use_surplus'] == '1')
				&& $_SESSION['user_id'] > 0
				&& $user_info['user_money'] > 0)
		{
			// 能使用余额
			$smarty->assign('allow_use_surplus', 1);
			$smarty->assign('your_surplus', $user_info['user_money']);
		}
	
	
		/* 保存 session */
		$_SESSION['flow_order'] = $order;
	
	}
	
	public function done_meeting()
	{
	
		global $smarty,$_LANG,$_CFG;
		//////////////////////////////////////////////////////////
		 
		/* added by zhangxi, 20150127, 活动 */
		$goods_id = intval($_REQUEST['goods_id']);//好像在post参数中
		ss_log(__FUNCTION__."zhx, goods_id=".$goods_id);
		$goods = get_goods_info($goods_id);
		 
		/*
		 global $G_WORDS_HUODONG;
		// if($goods['attribute_type'] == "lvyouhuodong"
				// || $goods['attribute_type'] == "jingwailvyouhuodong")
			if(strstr($goods['attribute_type'],$G_WORDS_HUODONG))//险种属性中只要含有huodong字符，则进入
		{
		if(intval($_GET['uid'])>0)
		{
		$_SESSION['user_id'] = intval($_GET['uid']);
		global $_SGLOBAL;
		$sql = "SELECT user_id, user_name FROM bx_users WHERE user_id = '" .$_SESSION['user_id']. "'";
		$query = $_SGLOBAL['db']->query($sql);
		$row = $_SGLOBAL['db']->fetch_array($query);
	
		$_SESSION['user_name'] = $row['user_name'];
	
		$_SGLOBAL['supe_uid'] = $_SESSION['user_id'];
		// supe_username
		$_SGLOBAL['supe_username']=$_SESSION['user_name'];
		}
	
		}
		*/
		 
	
		/*
		 * 检查用户是否已经登录
		* 如果用户已经登录了则检查是否有默认的收货地址
		* 如果没有登录则跳转到登录和注册页面
		*/
		if (empty($_SESSION['direct_shopping']) && $_SESSION['user_id'] == 0)
		{
			ss_log("in order process,没登录，则跳转到登录页面 ");
			/* 用户没有登录且没有选定匿名购物，转向到登录页面 */
			ecs_header("Location: flow.php?step=login\n");
			exit;
		}
	
		//控制支付方式
		$is_weixin = isset ($_REQUEST['is_weixin']) ?trim($_REQUEST['is_weixin']):0;
	
		/* 取得购物类型 */
		$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;
	
	
		 
		//comment by wangcya , 20150105, 这个地方同样的$cart_insure_id可能被进入了两次
		$cart_insure_id = intval($_POST['cart_insure_id']);
		ss_log("flow done!，cart_insure_id： ".$cart_insure_id);
		 
		/*
		 if($cart_insure_id==0)
		 {
		ss_log("flow done, cart_insure_id 为空!");
		//show_message("投保单不能为空!");
		 
		//////////////下面这段代码是为了处理历史问题的////////////////////
		$policy_id = intval($_POST['policy_id']);
		ss_log("flow done!，policy_id： ".$policy_id);
		 
		if($policy_id)
		{
		$sql="SELECT cart_insure_id FROM t_insurance_policy WHERE policy_id=$policy_id";
		ss_log($sql);
		$cart_insure_id  = $GLOBALS['db']->getOne($sql);
		if($cart_insure_id)
		{
		ss_log("find by policy_id, cart_insure_id: ".$cart_insure_id);
		}
		}
		//////////////上面这段代码是为了处理历史问题的////////////////////
		}
		//start add by wangcya , 20150105, for bug[202],订单中的商品为空
		*/
		 
		if($cart_insure_id)
		{
			$sql="SELECT COUNT(*) FROM bx_order_info WHERE cart_insure_id='$cart_insure_id'";
			ss_log($sql);
			$order_num  = $GLOBALS['db']->getOne($sql);
			if($order_num>0)
			{
				//add by dingchaoyang 2015-1-12
				//响应json数据到客户端
				include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
				EbaAdapter::responseOrderSubmit('0');
				//end add by dingchaoyang 2015-1-12
				ss_log("该订单和批量单已经关联,cart_insure_id: ".$cart_insure_id);
				return false;
			}
		}
		else
		{
			ss_log("flow done, 批单id为空!");
			show_message("批单id不能为空!");
			return false;
			//exit(0);
		}
	
		/*
		 //end add by wangcya , 20150105, for bug[202],订单中的商品为空
		$auto_done = isset ($_GET['auto_done']) ?trim($_GET['auto_done']): ''  ;
		if($auto_done)
		{
		ss_log("step done , skip to auto_done");
		}
		*/
		///////////////////////////////////////////////////
	
		$consignee = get_consignee($_SESSION['user_id']);
	
	
		$_POST['how_oos'] = isset($_POST['how_oos']) ? intval($_POST['how_oos']) : 0;
		$_POST['card_message'] = isset($_POST['card_message']) ? compile_str($_POST['card_message']) : '';
		$_POST['inv_type'] = !empty($_POST['inv_type']) ? compile_str($_POST['inv_type']) : '';
		$_POST['inv_payee'] = isset($_POST['inv_payee']) ? compile_str($_POST['inv_payee']) : '';
		$_POST['inv_content'] = isset($_POST['inv_content']) ? compile_str($_POST['inv_content']) : '';
		$_POST['postscript'] = isset($_POST['postscript']) ? compile_str($_POST['postscript']) : '';
	
		//start add by wangcya, for bug[193],能够支持多人批量投保
		$sql="SELECT COUNT(*) FROM t_insurance_policy WHERE cart_insure_id=$cart_insure_id";
		ss_log($sql);
		$policy_num  = $GLOBALS['db']->getOne($sql);
		ss_log("flow done,policy_num: ".$policy_num);
		$withdrawed_policy_num = 0;
		//end add by wangcya, for bug[193],能够支持多人批量投保
		//comment by zhangxi, 20150127, 对应bx_order_info表
		$order = array(
				'shipping_id'     => intval(isset ($_POST['shipping']) ?trim($_POST['shipping']):0),
				'pay_id'          => intval($_POST['payment']),
				'pack_id'         => isset($_POST['pack']) ? intval($_POST['pack']) : 0,
				'card_id'         => isset($_POST['card']) ? intval($_POST['card']) : 0,
				'card_message'    => trim($_POST['card_message']),
				'surplus'         => isset($_POST['surplus']) ? floatval($_POST['surplus']) : 0.00,
				'integral'        => isset($_POST['integral']) ? intval($_POST['integral']) : 0,
				'bonus_id'        => isset($_POST['bonus']) ? intval($_POST['bonus']) : 0,
				'need_inv'        => empty($_POST['need_inv']) ? 0 : 1,
				'inv_type'        => $_POST['inv_type'],
				'inv_payee'       => trim($_POST['inv_payee']),
				'inv_content'     => $_POST['inv_content'],
				'postscript'      => trim($_POST['postscript']),
				'how_oos'         => isset($_LANG['oos'][$_POST['how_oos']]) ? addslashes($_LANG['oos'][$_POST['how_oos']]) : '',
				'need_insure'     => isset($_POST['need_insure']) ? intval($_POST['need_insure']) : 0,
				'user_id'         => $_SESSION['user_id'],
				'type'         => "meeting",//add by wangcya,20150129
				//'add_time'        => time(),
				'add_time'        => time(),
				'order_status'    => OS_UNCONFIRMED,
				'shipping_status' => SS_UNSHIPPED,
				'pay_status'      => PS_UNPAYED,
				'cart_insure_id'  => $cart_insure_id,//add by wangcya, for bug[193],能够支持多人批量投保
				'policy_num'	  => $policy_num,////add by wangcya, for bug[193],能够支持多人批量投保,保单个数
				'withdrawed_policy_num'	  => $withdrawed_policy_num,////add by wangcya, for bug[193],能够支持多人批量投保,被注销的保单个数
				'policy_id'      => isset($_POST['policy_id']) ? intval($_POST['policy_id']) : 0,//del by wangcya, for bug[193],能够支持多人批量投保，留着。
				'agency_id'       => get_agency_by_regions(array($consignee['country'], $consignee['province'], $consignee['city'], $consignee['district'])),
				'client_id'       =>0,//added by zhangxi, 20150129,活动的c端用户id
				'activity_id'     =>0//added by zhangxi, 活动的id
				
		   );
				
				
	
	
		/* 扩展信息 */
		if (isset($_SESSION['flow_type']) && intval($_SESSION['flow_type']) != CART_GENERAL_GOODS)
		{
			$order['extension_code'] = $_SESSION['extension_code'];
			$order['extension_id'] = $_SESSION['extension_id'];
		}
		else
		{
			$order['extension_code'] = '';
			$order['extension_id'] = 0;
		}
	
		/* 检查积分余额是否合法 */
		$user_id = $_SESSION['user_id'];
		if ($user_id > 0)
		{
			$user_info = user_info($user_id);
	
			$order['surplus'] = min($order['surplus'], $user_info['user_money'] + $user_info['credit_line']);
			if ($order['surplus'] < 0)
			{
				$order['surplus'] = 0;
			}
	
			// 查询用户有多少积分
			$flow_points = flow_available_points();  // 该订单允许使用的积分
			$user_points = $user_info['pay_points']; // 用户的积分总数
	
			$order['integral'] = min($order['integral'], $user_points, $flow_points);
			if ($order['integral'] < 0)
			{
				$order['integral'] = 0;
			}
		}
		else
		{
			$order['surplus']  = 0;
			$order['integral'] = 0;
		}
	
		/* 检查代金券是否存在 */
		if ($order['bonus_id'] > 0)
		{
			$bonus = bonus_info($order['bonus_id']);
	
			if (empty($bonus) || $bonus['user_id'] != $user_id || $bonus['order_id'] > 0 || $bonus['min_goods_amount'] > cart_amount(true, $flow_type))
			{
				$order['bonus_id'] = 0;
			}
		}
		elseif (isset($_POST['bonus_sn']))
		{
			$bonus_sn = trim($_POST['bonus_sn']);
			$bonus = bonus_info(0, $bonus_sn);
			//$now = time();
			$now = time();
			if (empty($bonus) || $bonus['user_id'] > 0 || $bonus['order_id'] > 0 || $bonus['min_goods_amount'] > cart_amount(true, $flow_type) || $now > $bonus['use_end_date'])
			{
			}
			else
			{
				if ($user_id > 0)
				{
					$sql = "UPDATE " . $GLOBALS['ecs']->table('user_bonus') . " SET user_id = '$user_id' WHERE bonus_id = '$bonus[bonus_id]' LIMIT 1";
					$GLOBALS['db']->query($sql);
				}
				$order['bonus_id'] = $bonus['bonus_id'];
				$order['bonus_sn'] = $bonus_sn;
			}
		}
	
		/*
		 //comment by wangcya, 20150127, 找到该批量下面所有的投保单的总保费
		$sql="SELECT SUM(total_premium) as total_premium_mutil FROM t_insurance_policy WHERE cart_insure_id=$cart_insure_id";
		ss_log($sql);
		$total_premium_mutil  = $GLOBALS['db']->getOne($sql);
		ss_log("flow done,total_premium_mutil: ".$total_premium_mutil);
		if ($total_premium_mutil<=0)
		{
		ss_log("总保费不合规!");
		show_message("总保费不合规!");
		return false;
		//exit(0);
		}
	
		//start add by wangcya, 20150116,再次校验
		$sql="SELECT * FROM t_cart_insure WHERE rec_id='$cart_insure_id'";
		ss_log($sql);
		$cart_insure_attr  = $GLOBALS['db']->getRow($sql);
		if($cart_insure_attr)
		{
		if(floatval($cart_insure_attr['total_price'])!=floatval($total_premium_mutil))
		{
		showmessage("生成订单时候检查到总价不相符");
		exit(0);
		}
		else
		{
		ss_log("批保单总费用等于保单费用的汇总");
		}
		}
		else
		{
		showmessage("没找到批保单");
		return false;//
		//exit(0);
		}
		//end add by wangcya, 20150116,再次校验
	
		//找到所有的投保单
		$sql = "select * from t_insurance_policy where cart_insure_id=$cart_insure_id";
		ss_log($sql);
		//end add by wangcya, for bug[193],能够支持多人批量投保，
		//查找一下是为了验证数据库中是否存在，其实下面没用。
		$bx_policy = $GLOBALS['db']->getAll($sql);
		if(empty($bx_policy))
		{
		ss_log("没对应投保单!");
		show_message("没对应投保单!");
		}
		//elseif ($bx_policy['total_premium']<=0)//del by wangcya, for bug[193],能够支持多人批量投保，
	
	
		$policy_id = $bx_policy[0]['policy_id'];//add by wangcya, 20141225
		//$order['t_order_id'] = $policy_id;//add by wangcya,20141103
		*/
		$sql="SELECT * FROM t_cart_insure WHERE rec_id='$cart_insure_id'";
		ss_log($sql);
		$cart_insure_attr  = $GLOBALS['db']->getRow($sql);
		$total_premium_mutil = floatval($cart_insure_attr['total_price']);
	
		/* 计算订单的费用 */
		//$total_premium = $bx_policy['all_total_premium'];//del by wangcya, for bug[193],能够支持多人批量投保，
		$total_premium = $total_premium_mutil;//add by wangcya, for bug[193],能够支持多人批量投保，
	
		//comment by zhangxi, 20150127, 再次计算用户真正需要实际需要付的钱
		// $total = bx_order_fee($order, $total_premium, $consignee);//设置到总保费中
		$bx_policy = null;
		$total = bx_order_fee($order, $total_premium, $consignee, $bx_policy, $goods_id);//设置到总保费中
	
		ss_log("order done, goods_price".$total['goods_price']);
	
		$order['bonus']        = $total['bonus'];
		$order['goods_amount'] = $total['goods_price'];//comment by wangcya ,这个就是商品的总价。
		$order['discount']     = $total['discount'];//comment by zhangxi, 20150127, 折扣
		$order['surplus']      = $total['surplus'];
		$order['tax']          = $total['tax'];
	
		ss_log("order done, goods_price： ".$total['goods_price'].",discount=".$order['discount']);
	
		//comment by zhangxi, 20150127, 暂时没有使用
		// 购物车中的商品能享受代金券支付的总额
		$discount_amout = compute_discount_amount();
		// 代金券和积分最多能支付的金额为商品总额
		$temp_amout = $order['goods_amount'] - $discount_amout;
		if ($temp_amout <= 0)
		{
			$order['bonus_id'] = 0;
		}
	
	
		/* 支付方式 */
		if ($order['pay_id'] > 0)
		{
			$payment = payment_info($order['pay_id']);
			$order['pay_name'] = addslashes($payment['pay_name']);
		}
		$order['pay_fee'] = $total['pay_fee'];
		$order['cod_fee'] = $total['cod_fee'];
	
		/* 商品包装 */
		if ($order['pack_id'] > 0)
		{
			$pack               = pack_info($order['pack_id']);
			$order['pack_name'] = addslashes($pack['pack_name']);
		}
		$order['pack_fee'] = $total['pack_fee'];
	
		/* 祝福贺卡 */
		if ($order['card_id'] > 0)
		{
			$card               = card_info($order['card_id']);
			$order['card_name'] = addslashes($card['card_name']);
		}
		$order['card_fee']      = $total['card_fee'];
	
		//comment by zhangxi, 20150127, 用户真正要支付的钱
		$order['order_amount']  = number_format($total['amount'], 2, '.', '');
	
		/* 如果全部使用余额支付，检查余额是否足够 */
		if ($payment['pay_code'] == 'balance' && $order['order_amount'] > 0)
		{
			if($order['surplus'] >0) //余额支付里如果输入了一个金额
			{	//这里感觉有问题???
				$order['order_amount'] = $order['order_amount'] + $order['surplus'];
				$order['surplus'] = 0;
			}
			if ($order['order_amount'] > ($user_info['user_money'] + $user_info['credit_line']))
			{
				//当余额不足时，也能正常提交订单 yes123 2014-09-22
				// show_message($_LANG['balance_not_enough']);
			}
			else
			{
				$order['surplus'] = $order['order_amount'];
				$order['order_amount'] = 0;
			}
		}
	
		/* 如果订单金额为0（使用余额或积分或代金券支付），修改订单状态为已确认、已付款 */
		if ($order['order_amount'] <= 0)
		{
			$order['order_status'] = OS_CONFIRMED;
			$order['confirm_time'] = time();
			$order['pay_status']   = PS_PAYED;
			$order['pay_time']     = time();
			$order['order_amount'] = 0;
		}
		//这个什么用？？？
		$order['integral_money']   = $total['integral_money'];
		$order['integral']         = $total['integral'];
	
		if ($order['extension_code'] == 'exchange_goods')
		{
			$order['integral_money']   = 0;
			$order['integral']         = $total['exchange_integral'];
		}
	
		$order['from_ad']          = !empty($_SESSION['from_ad']) ? $_SESSION['from_ad'] : '0';
		$order['referer']          = !empty($_SESSION['referer']) ? addslashes($_SESSION['referer']) : '';
	
		/* 记录扩展信息 */
		if ($flow_type != CART_GENERAL_GOODS)
		{
			$order['extension_code'] = $_SESSION['extension_code'];
			$order['extension_id'] = $_SESSION['extension_id'];
		}
	
	
		$affiliate = unserialize($_CFG['affiliate']);
		if(isset($affiliate['on']) && $affiliate['on'] == 1 && $affiliate['config']['separate_by'] == 1)
		{
			//推荐订单分成
			$parent_id = get_affiliate();
			if($user_id == $parent_id)
			{
				$parent_id = 0;
			}
		}
		elseif(isset($affiliate['on']) && $affiliate['on'] == 1 && $affiliate['config']['separate_by'] == 0)
		{
			//推荐注册分成
			$parent_id = 0;
		}
		else
		{
			//分成功能关闭
			$parent_id = 0;
		}
		$order['parent_id'] = $parent_id;
	
		/*插入订单表*/
	
		//$sql = "SELECT count(*) FROM ".$GLOBALS['ecs']->table('order_info')." WHERE policy_id = '$policy_id'";
	
		$sql = "SELECT count(*) FROM ".$GLOBALS['ecs']->table('order_info')." WHERE cart_insure_id = '$cart_insure_id'";//add by wangcya, 20150127
		ss_log($sql);
	
		$policy_count = $GLOBALS['db']->getOne($sql);
		if($policy_count == 0)
		{//为了防止重复提交订单的情况。
			$error_no = 0;
			do
			{
				//add platformid by dingchaoyang 2014-12-19
				include_once (ROOT_PATH . 'api/EBaoApp/platformEnvironment.class.php');
				$platform_id = PlatformEnvironment::getPlatformID();
				if ($platform_id){
					$order['platform_id'] = $platform_id;
				}
				//end by dingchaoyang 2014-12-19
	
				//comment by zhangxi, 20150127, 插入订单数据到数据库中
				$order['order_sn'] = get_order_sn(); //获取新订单号
				$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'), $order, 'INSERT');
	
				$error_no = $GLOBALS['db']->errno();
	
				if ($error_no > 0 && $error_no != 1062)
				{
					die($GLOBALS['db']->errorMsg());
				}
			}
			while ($error_no == 1062); //如果是订单号重复则重新提交数据
	
			$new_order_id = $GLOBALS['db']->insert_id();
			$order['order_id'] = $new_order_id;
	
			ss_log("new_order_id: ".$new_order_id." order_sn: ".$order['order_sn']);
	
			/////////start add by wangcya , 20140830, 在这里生成了订单，所以应该也在这里把订单和保单关联起来
			$order_sn = $order['order_sn'];//add by wangcya , 20141023,把订单号增加到保单上面。
			 
			 
			//$sql = "UPDATE t_insurance_policy SET order_id='$new_order_id',order_sn='$order_sn' WHERE policy_id=".$order['policy_id'];
			 
			//更新所有这些投保单的的order_id，order_sn,并且没有订单号的
			if($cart_insure_id)
			{
				//千万不能把那些已经存在的订单更新了
				$sql = "UPDATE t_insurance_policy SET order_id='$new_order_id',order_sn='$order_sn' WHERE cart_insure_id='$cart_insure_id' AND order_id='0'";//modify by wangcya, for bug[193],能够支持多人批量投保，
				ss_log($sql);
				$GLOBALS['db']->query($sql);
	
				$sql = "UPDATE t_cart_insure SET order_id='$new_order_id' WHERE rec_id='$cart_insure_id'";//modify by wangcya, for bug[193],能够支持多人批量投保，
				ss_log($sql);
				$GLOBALS['db']->query($sql);
			}
			/////////end add by wangcya , 20140830, 在这里生成了订单，所以应该也在这里把订单和保单关联起来
	
			//$_REQUEST['goods_id']为何为零呢？
			 
			/* 插入订单商品 */
			$sql = "INSERT INTO " . $GLOBALS['ecs']->table('order_goods') . "( " .
					"order_id, goods_id, goods_name, goods_sn, goods_number, market_price, ".
					"goods_price, is_real, extension_code) ".
					" SELECT '$new_order_id', goods_id, goods_name, goods_sn, 1, market_price, ".
					"shop_price, is_real, extension_code".
					" FROM " .$GLOBALS['ecs']->table('goods') .
					" WHERE goods_id = '".$_REQUEST['goods_id']."'";
	
			ss_log($sql);
			$GLOBALS['db']->query($sql);
			 
			 
		}
		else
		{
			//add by dingchaoyang 2014-11-24
			//响应json数据到客户端
			include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
			EbaAdapter::responseOrderSubmit();
			//end add by dingchaoyang 2014-11-24
	
			show_message('订单已存在！', '', 'user.php?act=order_list');
			exit;
		}
	
		/* 修改拍卖活动状态 */
		if ($order['extension_code']=='auction')
		{
			$sql = "UPDATE ". $GLOBALS['ecs']->table('goods_activity') ." SET is_finished='2' WHERE act_id=".$order['extension_id'];
			$GLOBALS['db']->query($sql);
		}
	
		ss_log("from flow.php, into function log_account_change");
		//require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/shopping_flow.php');
		/* 处理余额、积分、代金券 */
		if ($order['user_id'] > 0 && $order['surplus'] > 0)
		{
			//modify yes123 2015-01-08 订单号修正
			//log_account_change($order['user_id'], $order['surplus'] * (-1), 0, 0, 0, sprintf($_LANG['pay_order'], $order['order_sn']),$order['order_sn']);
			log_account_change($order['user_id'], $order['surplus'] * (-1), 0, 0, 0, sprintf($_LANG['pay_order'], ''),ACT_OTHER,$order['order_sn']);
		}
		if ($order['user_id'] > 0 && $order['integral'] > 0)
		{
			//modify yes123 2015-01-08 订单号修正
			//log_account_change($order['user_id'], 0, 0, 0, $order['integral'] * (-1), sprintf($_LANG['pay_order'], $order['order_sn']),$order['order_sn']);
			log_account_change($order['user_id'], 0, 0, 0, $order['integral'] * (-1), sprintf($_LANG['pay_order'], ''),ACT_OTHER,$order['order_sn']);
		}
	
		/*
		 //added by zhangxi, 20150127,有折扣的情况时，订单中折扣的钱得从总账户中扣除
		if ($order['user_id'] > 0 && $order['discount'] >0)
		{
		//不是扣操作当前订单的账户，而是扣公司的账户
		//ebaoins_user=user_name
		//根据用户名查询用户id，
		ss_log(__FUNCTION__."user_id=".$order['user_id'].",discount=".$order['discount']);
		$sql = "SELECT user_id " .
		"FROM " . $GLOBALS['ecs']->table('users') .
		" WHERE user_name = 'ebaoins_user'";
		$ebaoins_user_id = $GLOBALS['db']->getOne($sql);
		log_account_change($ebaoins_user_id, $order['discount'] * (-1), 0, 0, 0, sprintf($_LANG['pay_order'], "活动,sn:".$order['order_sn']),ACT_OTHER,$order['order_sn']);
		}
		*/
		 
		if ($order['bonus_id'] > 0 && $temp_amout > 0)
		{
			use_bonus($order['bonus_id'], $new_order_id);
		}
	
		/* 如果使用库存，且下订单时减库存，则减少库存 */
		if ($_CFG['use_storage'] == '1' && $_CFG['stock_dec_time'] == SDT_PLACE)
		{
			change_order_goods_storage($order['order_id'], true, SDT_PLACE);
		}
	
	
		/* 如果需要，发短信 , ask by wangcya, 20141011, 这里和支付宝的地方的sms_order_payed不一样*/
		/*	del yes123 2015-01-19  echop 自带的短信功能，取消    if ($_CFG['sms_order_placed'] == '1' && $_CFG['sms_shop_mobile'] != '')
		 {
		include_once('includes/cls_sms.php');
		$sms = new sms();
		$msg = $order['pay_status'] == PS_UNPAYED ?
		$_LANG['order_placed_sms'] : $_LANG['order_placed_sms'] . '[' . $_LANG['sms_paid'] . ']';
		$flow_type = isset($order['tel']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;
		$sms->send($_CFG['sms_shop_mobile'], sprintf($msg, $order['consignee'], $order['tel']),'', 13,1);
		}*/
	
		/* 如果订单金额为0 处理虚拟卡 */
		if ($order['order_amount'] <= 0)
		{
			$sql = "SELECT goods_id, goods_name, goods_number AS num FROM ".
					$GLOBALS['ecs']->table('cart') .
					" WHERE is_real = 0 AND extension_code = 'virtual_card'".
					" AND session_id = '".SESS_ID."' AND rec_type = '$flow_type'";
	
			$res = $GLOBALS['db']->getAll($sql);
	
			$virtual_goods = array();
			foreach ($res AS $row)
			{
				$virtual_goods['virtual_card'][] = array('goods_id' => $row['goods_id'], 'goods_name' => $row['goods_name'], 'num' => $row['num']);
			}
	
			if ($virtual_goods AND $flow_type != CART_GROUP_BUY_GOODS)
			{
				/* 虚拟卡发货 */
				if (virtual_goods_ship($virtual_goods,$msg, $order['order_sn'], true))
				{
					/* 如果没有实体商品，修改发货状态，送积分和代金券 */
					$sql = "SELECT COUNT(*)" .
							" FROM " . $GLOBALS['ecs']->table('order_goods') .
							" WHERE order_id = '$order[order_id]' " .
							" AND is_real = 1";
					if ($GLOBALS['db']->getOne($sql) <= 0)
					{
						/* 修改订单状态 */
						update_order($order['order_id'], array('shipping_status' => SS_SHIPPED, 'shipping_time' => time()));
	
						/* 如果订单用户不为空，计算积分，并发给用户；发代金券 */
						if ($order['user_id'] > 0)
						{
							/* 取得用户信息 */
							$user = user_info($order['user_id']);
	
							/* 计算并发放积分 */
							$integral = integral_to_give($order);
							log_account_change($order['user_id'], 0, 0, intval($integral['rank_points']), intval($integral['custom_points']), sprintf($_LANG['order_gift_integral'], $order['order_sn'],$order['order_sn']));
	
							/* 发放代金券 */
							send_order_bonus($order['order_id']);
						}
					}
				}
			}
	
		}
	
		/* 清空购物车 */
		clear_cart($flow_type);
		/* 清除缓存，否则买了商品，但是前台页面读取缓存，商品数量不减少 */
		clear_all_files();
	
		/* 插入支付日志，这个地方很重要 */
		ss_log("将要进行函数 insert_pay_log ");
	
		//add by wangcya, 20150127,下面这个函数非常重要，要前提
		$order['log_id'] = insert_pay_log($new_order_id, $order['order_amount'], PAY_ORDER);
	
		$pay_online='';
		/* 取得支付信息，生成支付代码 */
		//comment by zhangxi ， 20150127， 进行支付的处理流程
		if ($order['order_amount'] > 0)
		{
			$payment = payment_info($order['pay_id']);
	
			include_once('includes/modules/payment/' . $payment['pay_code'] . '.php');
	
			$pay_obj    = new $payment['pay_code'];
	
			ss_log("will into pay get_code, pay_code: ".$payment['pay_code']);
			//comment by wangcya, 20150127,这个函数很重要，生成例如支付宝支付的按钮和表单。
			$pay_online = $pay_obj->get_code($order, unserialize_config($payment['pay_config']));
			ss_log($pay_online);
			$order['pay_desc'] = $payment['pay_desc'];
	
			$smarty->assign('pay_online', $pay_online);
			 
		}
		if(!empty($order['shipping_name']))
		{
			$order['shipping_name']=trim(stripcslashes($order['shipping_name']));
		}
	
		/* 订单信息 */
		$smarty->assign('order',      $order);
		$smarty->assign('total',      $total);
		$smarty->assign('order_submit_back', sprintf($_LANG['order_submit_back'], $_LANG['back_home'], $_LANG['goto_user_center'])); // 返回提示
	
	
		
		 ss_log("from follow done, into function assign_commision_and_post_policy!");
		//处理佣金,同时进行投保操作
	
		//comment by wangcya, 20150127,其实这个函数内部判断是否已经支付，所以根据支付状态来结算佣金
		ss_log("将要进行佣金的处理");
		//mod by zhangxi, 20150305, 修改函数名,分配佣金和投保
		//$result_attr = yongJin2($order['order_sn']);
		$result_attr = assign_commision_and_post_policy($order['order_sn']);
		//////////////////////////////////////////////////////////////////////
	    /*
		user_uc_call('add_feed', array($order['order_id'], BUY_GOODS)); //推送feed到uc
		unset($_SESSION['flow_consignee']); // 清除session中保存的收货人信息
		unset($_SESSION['flow_order']);
		unset($_SESSION['direct_shopping']);
	
	
		//start add yes123 2015-01-23 销售量+1 ，用来统计销量的。
		//bx_goods 销量加1
		if($_REQUEST['goods_id'])
		{
		$sql = "UPDATE ".$GLOBALS['ecs']->table('goods')." SET goods_sales_volume=goods_sales_volume+1 WHERE goods_id='$_REQUEST[goods_id]'";
		$GLOBALS['db']->query($sql);
		ss_log('bx_goods 产品销量+1:'.$sql);
	
	
		//t_insurance_product_attribute 销量加1
		$sql="SELECT tid FROM ".$GLOBALS['ecs']->table('goods')." WHERE goods_id=$_REQUEST[goods_id] ";
		ss_log($sql);
		$tid = $GLOBALS['db']->getOne($sql);
		if($tid)//add by wangcya, 20150127
		{
		$sql = "UPDATE t_insurance_product_attribute SET attribute_sales_volume=attribute_sales_volume+1 WHERE attribute_id=".$tid;
		ss_log($sql);
		$GLOBALS['db']->query($sql);
		}
		else
		{
		ss_log("find tid by goods_id ,is null");
		}
		 
		ss_log('t_insurance_product_attribute 险种销量+1:'.$sql);
		}
		else
		{
		ss_log("_REQUEST goods_id is empty!");
		}
		//end add yes123 2015-01-23 销售量+1
	
	
		//comment by zhangxi, 以下是手机端浏览器关于订单的处理
		//add by dingchaoyang 2014-11-24
		//响应json数据到客户端
		include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
		EbaAdapter::responseOrderSubmit($order['order_sn'].'-'.$order['log_id'],$pay_online);
		//end add by dingchaoyang 2014-11-24
	
		if(strstr($goods['attribute_type'],$G_WORDS_HUODONG))//险种属性中只要含有huodong字符，则进入，保证代码通用性
		{
		$_SESSION['user_id'] = 0;
		$_SESSION['user_name'] = 0;
		$_SGLOBAL['supe_uid'] = 0;
		// supe_username
		$_SGLOBAL['supe_username']=0;
		}
		*/
	
	
	}//done_meeting
	//end add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能
	
	//start add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能,可以在这个地方做跳转
	public function editProfile_meeting()
	{
		$this->is_weixin = isset ($_POST['is_weixin']) ? trim($_POST['is_weixin']) : '';
		$user_id = $_SESSION['user_id'];
		$real_name = empty ($_POST['real_name']) ? '' : $_POST['real_name'];
		$email = isset ($_POST['email']) ? trim($_POST['email']) : '';
		$mobile_phone = isset ($_POST['extend_field5']) ? trim($_POST['extend_field5']) : '';
	
		//user_info表的信息
		//证件类型
		$user_info['card_type'] = isset ($_POST['CertificatesType']) ? trim($_POST['CertificatesType']) : '';
		$user_info['card_id'] = isset ($_POST['CardId']) ? trim($_POST['CardId']) : '';
	
		//证书类型
		$user_info['Category'] = isset ($_POST['Category']) ? trim($_POST['Category']) : '';
		//证书编号
		$user_info['CertificateNumber'] = isset ($_POST['CertificateNumber']) ? trim($_POST['CertificateNumber']) : '';
	
		//证书有效期
		$user_info['certificate_expiration_date'] = isset ($_POST['certificate_expiration_date']) ? trim($_POST['certificate_expiration_date']) : '';
		//add yes12 2014-12-31 对永久有效另外处理
		$certificate_expiration_date_no = isset ($_POST['certificate_expiration_date_no']) ? trim($_POST['certificate_expiration_date_no']) : '';
		if ($certificate_expiration_date_no) {
			$user_info['certificate_expiration_date'] = $certificate_expiration_date_no;
		}
	
		//modify yes123 2014-12-16 为了验证地区，把这部分代码移动到上面来了
		//所在省份
		$user_info['province'] = isset ($_POST['province']) ? trim($_POST['province']) : '';
		//所在市/区
		$user_info['city'] = isset ($_POST['city']) ? trim($_POST['city']) : '';
		//家庭住址
		$user_info['address'] = isset ($_POST['address']) ? trim($_POST['address']) : '';
		//邮政编码
		$user_info['ZoneCode'] = isset ($_POST['ZoneCode']) ? trim($_POST['ZoneCode']) : '';
	
		//modify yes123 2014-12-01没有通过审核的用户才需要验证
		if ($_SESSION['check_status']!=CHECKED_CHECK_STATUS)
		{
			//校验数据格式是否正确
			global $_LANG;
				
			/* del by wangcya, 20150129 ,不检查这些
				if (!is_email($email))
				{
			if ($this->is_weixin)
			{
			die(json_encode(array (
					'code' => 1,
					'msg' => $_LANG['msg_email_format']
			)));
			} //add yes123 2015-01-06 微信端提示
			show_message($_LANG['msg_email_format']);
			}
			else
			{
	
	
			$sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('users') . " WHERE email = '$email' AND user_id <> '$user_id'";
			$r = $GLOBALS['db']->getOne($sql);
			if ($r)
			{
			include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
			EbaAdapter :: responseData('UpdateProfileEmailExist');
			if ($this->is_weixin) {
			die(json_encode(array (
					'code' => 1,
					'msg' => '邮件地址已存在！'
			)));
			} //add yes123 2015-01-06 微信端提示
			show_message('邮件地址已存在！', '', 'user.php?act=profile');
			}
	
	
			}
			*/
				
			///////////////////////////////////////////////////////////////////////////////////////
			if (!empty ($mobile_phone) && !preg_match('/^[\d-\s]+$/', $mobile_phone))
			{
				if ($this->is_weixin)
				{
					die(json_encode(array (
							'code' => 1,
							'msg' => $_LANG['passport_js']['mobile_phone_invalid']
					)));
				} //add yes123 2015-01-06 微信端提示
				show_message($_LANG['passport_js']['mobile_phone_invalid']);
			}
	
			if (empty ($mobile_phone))
			{
	
				if ($this->is_weixin) {
					die(json_encode(array (
							'code' => 1,
							'msg' => '手机为必填项！'
					)));
				} //add yes123 2015-01-06 微信端提示
	
				show_message('手机为必填项！', '', 'user.php?act=profile');
				exit;
			}
			else
			{
				$sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('users') . " WHERE mobile_phone = '$mobile_phone' AND user_id <> '$user_id'";
				$r = $GLOBALS['db']->getOne($sql);
				if ($r) {
					if ($this->is_weixin) {
						die(json_encode(array (
								'code' => 1,
								'msg' => '手机号码已存在！'
						)));
					} //add yes123 2015-01-06 微信端提示
					show_message('手机号码已存在！', '', 'user.php?act=profile');
				}
			}
	
			if (!$real_name)
			{
				if ($this->is_weixin)
				{
					die(json_encode(array (
							'code' => 1,
							'msg' => '真实姓名为必填项！'
					)));
				} //add yes123 2015-01-06 微信端提示
				show_message('真实姓名为必填项！', '', 'user.php?act=profile');
			}
	
			//证件编号
			if (empty ($user_info['card_id']))
			{
				if ($this->is_weixin)
				{
					die(json_encode(array (
							'code' => 1,
							'msg' => '证件号码不能为空！'
					)));
				} //add yes123 2015-01-06 微信端提示
				show_message('证件号码不能为空！', '', 'user.php?act=profile');
			} else {
				$sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('user_info') . " WHERE CardId = '$user_info[card_id]' AND CertificatesType = '$user_info[card_type]' AND uid <> '$user_id'";
				$r = $GLOBALS['db']->getOne($sql);
				if ($r > 0) {
					//add by dingchaoyang 2014-11-10
					//响应json数据到客户端
					include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
					EbaAdapter :: responseData('UpdateProfileIDExist');
					//end add by dingchaoyang 2014-11-10
					if ($this->is_weixin) {
						die(json_encode(array (
								'code' => 1,
								'msg' => '证件号码已存在！'
						)));
					} //add yes123 2015-01-06 微信端提示
					show_message('证件号码已存在！', '', 'user.php?act=profile');
				}
	
			}
	
			/*  del by wangcya, 20150129 ,不检查这些
				if (empty ($user_info['CertificateNumber'])) {
			if ($this->is_weixin) {
			die(json_encode(array (
					'code' => 1,
					'msg' => '资格证书编号不能为空！'
			)));
			} //add yes123 2015-01-06 微信端提示
			show_message('资格证书编号不能为空！', '', 'user.php?act=profile');
			}
			elseif (strlen($user_info['certificate_expiration_date']) < 4) {
			if ($this->is_weixin) {
			die(json_encode(array (
					'code' => 1,
					'msg' => '资格证格式不对！'
			)));
			} //add yes123 2015-01-06 微信端提示
			show_message('资格证格式不对！', '', 'user.php?act=profile');
			}
			else
			{
			$sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('user_info') . " WHERE CertificateNumber = '$user_info[CertificateNumber]' AND Category='$user_info[Category]' AND uid <> '$user_id'";
			$r = $GLOBALS['db']->getOne($sql);
			if ($r > 0) {
			//add by dingchaoyang 2014-11-10
			//响应json数据到客户端
			include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
			EbaAdapter :: responseData('UpdateProfileNumExist');
			//end add by dingchaoyang 2014-11-10
			if ($this->is_weixin) {
			die(json_encode(array (
					'code' => 1,
					'msg' => '资格证书编号已存在！'
			)));
			} //add yes123 2015-01-06 微信端提示
			show_message('资格证书编号已存在！', '', 'user.php?act=profile');
			}
			}
	
			if (!$user_info['province']) {
			if ($this->is_weixin) {
			die(json_encode(array (
					'code' => 1,
					'msg' => '省份不能为空！'
			)));
			} //add yes123 2015-01-06 微信端提示
			show_message('省份不能为空！', '', 'user.php?act=profile');
			}
	
			if (!$user_info['city']) {
			if ($this->is_weixin) {
			die(json_encode(array (
					'code' => 1,
					'msg' => '城市不能为空！'
			)));
			} //add yes123 2015-01-06 微信端提示
			show_message('城市不能为空！', '', 'user.php?act=profile');
			}
	
			if (!$user_info['address']) {
			if ($this->is_weixin) {
			die(json_encode(array (
					'code' => 1,
					'msg' => '详细地址不能为空！'
			)));
			} //add yes123 2015-01-06 微信端提示
			show_message('详细地址不能为空！', '', 'user.php?act=profile');
			}
			*/
	
		}
	
		//更新bx_users表
		$this->editUsersTable_meeting($user_id);
		//保存照片
		
		include_once(ROOT_PATH . 'includes/class/User.class.php');
		$obj_User = new User;
		
		//$this->saveImgs($user_id);
		//申请审核
		if ($_POST['apply_check'] == 1) {
			$obj_User->applyCheck($user_id);
		}
		//更新user_info表
		$r = $obj_User->editUserInfoTable($user_id, $user_info);
		
		
		return $r;
	
	}
	

	//更新bx_users表
	private function editUsersTable_meeting($user_id)
	{
		global $_LANG;
	
		$last_time = date('Y-m-d H:i:s', time());
		$real_name = isset ($_POST['real_name']) ? trim($_POST['real_name']) : '';
		$birthday = isset ($_POST['birthday']) ? trim($_POST['birthday']) : '';
		//add by dingchaoyang 适应最初的参数
		/*
			if (!$birthday)
			{
		$birthday = $_REQUEST['birthdayYear'] . '-' . $_REQUEST['birthdayMonth'] . '-' . $_REQUEST['birthdayDay'];
		}
		*/
	
		//end
		$email = isset ($_POST['email']) ? trim($_POST['email']) : '';
		$mobile_phone = isset ($_POST['extend_field5']) ? trim($_POST['extend_field5']) : '';
		$sex = isset ($_POST['sex']) ? trim($_POST['sex']) : '';
	
		$sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET birthday = '$birthday' , " .
		"email = '$email' , mobile_phone = '$mobile_phone' , sex='$sex' ,last_time='$last_time',real_name='$real_name' WHERE user_id=" . $user_id;
		ss_log('update usertable:' . $sql);
		$r = $GLOBALS['db']->query($sql);
	
		if ($r)
		{
			return $r;
		}
		else
		{
			//add by dingchaoyang 2014-11-10
			//响应json数据到客户端
			include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
			EbaAdapter :: responseData('UpdateProfileFail');
			//end add by dingchaoyang 2014-11-10
			if ($this->is_weixin)
			{
				die(json_encode(array (
						'code' => 1,
						'msg' => $_LANG['edit_profile_failed']
				)));
			} //add yes123 2015-01-06 微信端提示
			$msg = $_LANG['edit_profile_failed'];
			show_message($msg, '', '', 'info');
		}
	}
	
	public function update_meeting_by_order($rec_id,
			                                $order_sn,
											$user_id
			                              )
	{
		
		ss_log("into ".__FUNCTION__);

		if(!$rec_id)
		{
			$retcode = 110;
			$retmsg = "cart_insure_id is null";
			ss_log($retmsg);
		
			$result_attr['retcode'] = $retcode;
			$result_attr['retmsg'] = $retmsg;
		
			return $result_attr;
				
		}
		
		$sql="select mr_id from t_cart_insure WHERE rec_id=".$rec_id;
		ss_log($sql);
		$mr_id = $GLOBALS['db']->getOne($sql);
		if(!$mr_id)
		{
			$retcode = 110;
			$retmsg = "mr_id is null";
			ss_log($retmsg);
				
			$result_attr['retcode'] = $retcode;
			$result_attr['retmsg'] = $retmsg;
				
			return $result_attr;
		}
		
		////////////////////////////////////////////////////////////////////////
		ss_log("更新我的参会的状态为已支付,mr_id: ".$mr_id);
		$dateline_pay = time();
		$sql = "UPDATE bx_meeting_register SET registered='1',dateline_pay='$dateline_pay' WHERE mr_id='$mr_id'";
		ss_log($sql);
		$GLOBALS['db']->query($sql);
		
		
		////////////生成凭证：时间，地点，会议名称（MDRT会议），参会第几名（报名号），姓名，会务组联系电话。//////////////////////////////
		$sql="select * from bx_meeting_register mr INNER JOIN bx_favourable_activity_content ac ON ac.act_id= mr.act_id 
		 WHERE mr_id='$mr_id'";
		ss_log($sql);
		$favourable_activity_meeting = $GLOBALS['db']->getRow($sql);
		if($favourable_activity_meeting)
		{//
			//发送短信
			$act_name = $favourable_activity_meeting['act_name'];
			$time_note = $favourable_activity_meeting['time_note'];
			$address = $favourable_activity_meeting['address'];
			$contact_telephone = $favourable_activity_meeting['contact_telephone'];
			//$order_sn
			//把以上字段以短信的方式发送给该用户///////////////////////////////
			$sql="select user_name,real_name,check_status,mobile_phone from ". $GLOBALS['ecs']->table('users') ."  WHERE user_id=".$user_id;
			ss_log($sql);
			$user=$GLOBALS['db']->getRow($sql);
			if($user)
			{
				/*您已成功报名参加MDRT专题活动，参会ID号码是：2105013110968，请保留本条短信，凭ID号码准时参会。时间：2015年2月4日9:30-17:00。地点： 清华大学东门东10米，华业大厦5层1510教室。联系电话：4006-900-618。
*/
				//add yes123 2015-01-06 发送短信
				$url="http://www.ebaoins.cn/mobile_page/meetingactivity.html";
				$content = "您已成功报名参加".$act_name.",参会ID号码：".$order_sn."，请保留本条短信，凭ID号码准时参会。时间：".$time_note."。地点：".$address."。联系电话：".$contact_telephone;
				send_msg($user['mobile_phone'],$content);
			}
		}
		else
		{
			$retcode = 110;
			$retmsg = "favourable_activity_meeting is null";
			ss_log($retmsg);
			
			$result_attr['retcode'] = $retcode;
			$result_attr['retmsg'] = $retmsg;
			
			return $result_attr;
		}
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$retcode = 0;
		$retmsg = "会议报名订单，则更新状态会议的支付状态后返回，order_sn: ".$order_sn;
		ss_log($retmsg);
			
		$result_attr['retcode'] = $retcode;
		$result_attr['retmsg'] = $retmsg;
			
		return $result_attr;
	}
	//end add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能,可以在这个地方做跳转
}
?>