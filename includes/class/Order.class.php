<?php
class Order {
	function Order() {
	}
	public function add_bx() {
		global $smarty;
		$res = array ();
		
		/* added by zhangxi, 20150127, 活动 */
		$goods_id = isset($_GET ['gid'])?intval($_GET ['gid']):0;
		ss_log ( 'add_bx gid :' . $goods_id );
		$goods = get_goods_info ( $goods_id );
		// global $G_WORDS_HUODONG;
		
		// ss_log('add_bx goods[attribute_type] :'.$goods['attribute_type'].'G_WORDS_HUODONG:'.$G_WORDS_HUODONG);
		// mod by zhangxi, 20150312, 活动代码通用性修改
		// if(strstr($goods['attribute_type'],$G_WORDS_HUODONG))//险种属性中只要含有huodong字符，则进入
		// {
		// add by dingchaoyang 2015-3-31 区分活动
		$ROOT_PATH__1 = str_replace ( 'includes/class/Order.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
		include_once ($ROOT_PATH__1 . 'api/EBaoApp/platformEnvironment.class.php');
		// end
		
		$is_activity = false;
		if (isset ( $_GET ['uid'] ) && (! empty ( $_GET ['uid'] )) && ! PlatformEnvironment::isMobilePlatform () && ! PlatformEnvironment::isMobileHFivePlatform ()) {
			// zhangxi, 20150205,测试时先打开余额支付,活动的产品关闭余额支付
			
			global $_SGLOBAL;
			if (! ebaoins_encrypt_check ( $_GET ['uid'], "syden1981" )) {
				showmessage ( "用户信息校验错误！" );
				return false;
			}
			$is_activity = true;
			$str_uid = explode ( ',', $_GET ['uid'] );
			$real_uid = intval ( $str_uid [0] );
			if (! $real_uid) {
				return false;
			}
			$_SESSION ['user_id'] = $real_uid;
			
			$sql = "SELECT user_id, user_name FROM bx_users WHERE user_id = '" . $real_uid . "'";
			ss_log ( 'add_bx huodong get user by user_id  set session:' . $sql );
			$query = $_SGLOBAL ['db']->query ( $sql );
			$row = $_SGLOBAL ['db']->fetch_array ( $query );
			
			$_SESSION ['user_name'] = $row ['user_name'];
			$_SGLOBAL ['supe_uid'] = $_SESSION ['user_id'];
			
			$smarty->assign ( 'uid', $_GET ['uid'] );
			// del 2015-02-04 yes123 目前没有用 ,会报错 Notice: Undefined index: real_name
			// $_SGLOBAL['supe_username']=$_SESSION['user_name'];
			// $_SGLOBAL['real_name']=$_SESSION['real_name'];
		}
		
		// }
		
		// 通过这里到订单生成页
		// add by dingchaoyang 2014-12-19 如果会话失效 统一跳转到user.php登录界面
		// if (empty($_SESSION['user_id']))
		if (!$_SESSION ['user_id']) {
			ss_log ( 'add_bx user_id is null goto user.php,user_id:'.$_SESSION ['user_id'] );
			header ( "HTTP/1.1 301 Moved Permanently" );
			header ( "Location: user.php" );
			exit ();
		}
		
		$cart_insure_id = isset($_GET ['cart_insure_id'])?$_GET ['cart_insure_id']:0;
		$cart_insure_id = intval ( $cart_insure_id ); // add by wangcya, for bug[193],批量投保的id,一束
		ss_log ( "add_bx , cart_insure_id: " . $cart_insure_id );
		
		//add yes123 2015-05-19 批量付款，也就是模拟购物车
		$policy_ids = isset($_REQUEST['policy_ids'])?$_REQUEST['policy_ids']:0;
		if($policy_ids)
		{
			$res = $this->checkPolicyStatus($policy_ids);
			if($res['code']==1)
			{
				$msg = $res['msg']."已绑定其他订单，请到订单列表里支付！";
				ss_log(__FUNCTION__.",".$msg);
				show_message ($msg, '', 'user.php?act=order_list' );
				return ;
			}
			
			
			$user_id = $_SESSION['user_id'];
			$cart_insure_id = $this->resetCartInsureIdByPolicyIds($policy_ids,$user_id);
		}
		
		
		// comment by zhangxi, 20150127, 单个投保这里也是会走的
		if ($cart_insure_id) // 批量投保下的所有投保单
		{
			$sql = "select * from t_insurance_policy where cart_insure_id=$cart_insure_id";
			ss_log ( $sql );
			$list_bx_policy = $GLOBALS ['db']->getAll ( $sql );
			
			$smarty->assign ( 'list_bx_policy', $list_bx_policy );
			// 价格都在投保单那里，而且是总的，可能一个投保单购买的产品有多份。
			$sql = "select SUM(total_premium) as total_premium_mutil from t_insurance_policy where cart_insure_id=$cart_insure_id";
			ss_log ( __FUNCTION__ . ":SQL=" . $sql );
			
			// 计算得到批量投保的总保费
			$total_premium_mutil = $GLOBALS ['db']->getOne ( $sql );
			ss_log ( "add_bx , total_premium_mutil: " . $total_premium_mutil );
			$_SESSION ['total_premium'] = $total_premium_mutil;
			
			$smarty->assign ( 'cart_insure_id', $cart_insure_id );
			$smarty->assign ( 'total_premium_mutil', $total_premium_mutil );
		}
		
		
		/* ------------------------------------------------------ */
		// -- 订单确认
		/* ------------------------------------------------------ */
		
		/* 取得购物类型 */
		$flow_type = isset ( $_SESSION ['flow_type'] ) ? intval ( $_SESSION ['flow_type'] ) : CART_GENERAL_GOODS;
		$_SESSION ['flow_order'] ['extension_code'] = '';
		
		$consignee = get_consignee ( $_SESSION ['user_id'] );
		
		/* 对商品信息赋值 */
		$cart_goods = cart_goods ( $flow_type ); // 取得商品列表，计算合计
		$smarty->assign ( 'goods_list', $cart_goods );
		$smarty->assign ( 'gid', $goods_id );
		
		/*
		 * 取得订单信息
		 */
		$order = flow_order_info ();
		$smarty->assign ( 'order', $order );
		
		$total_premium = $total_premium_mutil; // add by wangcya , 20141225, 计算总价，这个很关键
		
		ss_log ( "before bx_order_fee， total_premium：" . $total_premium );
		$total = bx_order_fee ( $order, $total_premium, $consignee, $list_bx_policy, $is_activity ? $gid : 0 ); // comment by wangcya , 20141225, 计算总价，这个很关键
		
		$smarty->assign ( 'total', $total );
		
		/* 取得支付列表 */
		if ($order ['shipping_id'] == 0) {
			$cod = true;
			$cod_fee = 0;
		} else {
			$shipping = shipping_info ( $order ['shipping_id'] );
			$cod = $shipping ['support_cod'];
			
			if ($cod) {
				/* 如果是团购，且保证金大于0，不能使用货到付款 */
				if ($flow_type == CART_GROUP_BUY_GOODS) {
					$group_buy_id = $_SESSION ['extension_id'];
					if ($group_buy_id <= 0) {
						show_message ( 'error group_buy_id' );
					}
					$group_buy = group_buy_info ( $group_buy_id );
					if (empty ( $group_buy )) {
						show_message ( 'group buy not exists: ' . $group_buy_id );
					}
					
					if ($group_buy ['deposit'] > 0) {
						$cod = false;
						$cod_fee = 0;
						
						/* 赋值保证金 */
						$smarty->assign ( 'gb_deposit', $group_buy ['deposit'] );
					}
				}
				
				if ($cod) {
					$shipping_area_info = shipping_area_info ( $order ['shipping_id'], $region );
					$cod_fee = $shipping_area_info ['pay_fee'];
				}
			} else {
				$cod_fee = 0;
			}
		}
		
		
		// 给货到付款的手续费加<span id>，以便改变配送的时候动态显示
		$is_weixin = isset($_REQUEST ['is_weixin'])?$_REQUEST ['is_weixin']:0;
		if ($is_weixin == 1) {
			// mod by zhangxi, 20150128, 为了活动，临时取消余额支付的方式
			$payment_list = available_payment_list ( 1, $cod_fee, 1, false, $is_activity );
		} else {
			$payment_list = available_payment_list ( 1, $cod_fee );
		}
		
		if (isset ( $payment_list )) {
			foreach ( $payment_list as $key => $payment ) {
				// add yes123 2015-01-22 下订单时 不支持银行汇款转账
				if ($payment ['pay_code'] == 'bank') {
					unset ( $payment_list [$key] );
				}
				
				if ($payment ['is_cod'] == '1') {
					$payment_list [$key] ['format_pay_fee'] = '<span id="ECS_CODFEE">' . $payment ['format_pay_fee'] . '</span>';
				}
				/* 如果有易宝神州行支付 如果订单金额大于300 则不显示 */
				if ($payment ['pay_code'] == 'yeepayszx' && $total ['amount'] > 300) {
					unset ( $payment_list [$key] );
				}
				/* 如果有余额支付 */
				if ($payment ['pay_code'] == 'balance') {
					/* 如果未登录，不显示 */
					if ($_SESSION ['user_id'] == 0) {
						unset ( $payment_list [$key] );
					} else {
						if ($_SESSION ['flow_order'] ['pay_id'] == $payment ['pay_id']) {
							$smarty->assign ( 'disable_surplus', 1 );
						}
					}
				}
			}
		}
		$smarty->assign ( 'payment_list', $payment_list );
		
		/*add yes123 2015-07-06 使用金币*/
		$user_info = user_info ( $_SESSION ['user_id'] );
		
		ss_log("user_info['service_money']:".$user_info['service_money']);
		ss_log("user_info['user_money']:".$user_info['user_money']);
		
		if($user_info['service_money']>1)
		{
			$smarty->assign('your_service_money', $user_info['service_money']);
			$smarty->assign('allow_use_service_money', 1);
		}
		
	
		/* 如果使用余额，取得用户余额 */
		if ((! isset ( $_CFG ['use_surplus'] ) || $_CFG ['use_surplus'] == '1') && $_SESSION ['user_id'] > 0 && $user_info ['user_money'] > 0) {
			// 能使用余额
			$smarty->assign ( 'allow_use_surplus', 1 );
			$smarty->assign ( 'your_surplus', $user_info ['user_money'] );
		}
		
		// add yes123 2015-02-03 缓存余额，微信端选择支付方式为余额支付的时候要判断
		$smarty->assign ('user_money', $user_info ['user_money'] );
		/* 保存 session */
		$_SESSION ['flow_order'] = $order;
		
		/* 如果使用代金券，取得用户可以使用的代金券及用户选择的代金券 */
	    if ((!isset($_CFG['use_bonus']) || $_CFG['use_bonus'] == '1')
	        && ($flow_type != CART_GROUP_BUY_GOODS && $flow_type != CART_EXCHANGE_GOODS))
	    {
	        // 取得用户可用代金券
	        $user_bonus = user_bonus($_SESSION['user_id'], $total['goods_price']);
	        if (!empty($user_bonus))
	        {
	            foreach ($user_bonus AS $key => $val)
	            {
	                $user_bonus[$key]['bonus_money_formated'] = price_format($val['money'], false);
	            }
	            $smarty->assign('bonus_list', $user_bonus);
	        }
	
	        // 能使用代金券
	        $smarty->assign('allow_use_bonus', 1);
	    }
		
		
		$insurer_code = $_REQUEST['insurer_code'];
		if($insurer_code=='SSBQ')
		{
			ss_log("诉讼保全产品，不用支付，直接生成订单。。。");
			//获取已有非余额的支付方式
			$payment=0;
			foreach ( $payment_list as  $payment_info ) {
       			if($payment_info['pay_code']=='alipay')
       			{
       				$payment = $payment_info['pay_id'];
       				break;
       			}
			}
			//获取来源，不是PC的话，跳转手机端
			$user_agent = $_SERVER["HTTP_USER_AGENT"];
			$policy_id = $_GET['policy_id'];
			if(strstr($user_agent,'MicroMessenger'))//来自微信浏览器
			{
				$url = "order.php?act=done&insurer_code=$insurer_code&cart_insure_id=$cart_insure_id&goods_id=$goods_id&payment=$payment&surplus=0";
			}
			else
			{
				$url = "flow.php?step=done&insurer_code=$insurer_code&cart_insure_id=$cart_insure_id&goods_id=$goods_id&payment=$payment&surplus=0";
			}
			
			ss_log(__FUNCTION__.",跳转的url:".$url);
			header("location:$url");
			exit;
		}
		
		
		
	}
	public function done() {
		ss_log ( 'start into done' );
		/* added by zhangxi, 20150127, 活动 */
		$goods_id = intval ( $_REQUEST ['goods_id'] ); // 好像在post参数中
		ss_log ( __FUNCTION__ . "zhx, goods_id=" . $goods_id );
		$goods = get_goods_info ( $goods_id );
		
		// mod by zhangxi, 20150312, 活动代码通用性修改
		// global $G_WORDS_HUODONG;
		// $is_activity = false;
		// if(strstr($goods['attribute_type'],$G_WORDS_HUODONG))//险种属性中只要含有huodong字符，则进入
		// {
		//add by dingchaoyang 2015-3-31 条件
		$ROOT_PATH__1= str_replace ( 'includes/class/Order.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
		include_once ($ROOT_PATH__1 . 'api/EBaoApp/platformEnvironment.class.php');
		$is_activity = false;
		if (isset ( $_POST ['uid'] ) && (! empty ( $_POST ['uid'] )) && !PlatformEnvironment::isMobilePlatform() && !PlatformEnvironment::isMobileHFivePlatform()) {
			global $_SGLOBAL;
			if (! ebaoins_encrypt_check ( $_POST ['uid'], "syden1981" )) {
				showmessage ( "用户信息校验错误！" );
				return false;
			}
			$is_activity = true;
			
			$str_uid = explode ( ',', $_POST ['uid'] );
			$real_uid = intval ( $str_uid [0] );
			$_SESSION ['user_id'] = $real_uid;
			
			$sql = "SELECT user_id, user_name FROM bx_users WHERE user_id = '" . $real_uid . "'";
			$query = $_SGLOBAL ['db']->query ( $sql );
			$row = $_SGLOBAL ['db']->fetch_array ( $query );
			
			$_SESSION ['user_name'] = $row ['user_name'];
			$_SGLOBAL ['supe_uid'] = $_SESSION ['user_id'];
			// supe_username
			$_SGLOBAL ['supe_username'] = $_SESSION ['user_name'];
			$_SGLOBAL ['real_name'] = $_SESSION ['real_name'];
		}
		
		// }
		
		global $smarty, $_LANG, $_CFG;
		
		/*
		 * 检查用户是否已经登录
		 * 如果用户已经登录了则检查是否有默认的收货地址
		 * 如果没有登录则跳转到登录和注册页面
		 */
		if (empty ( $_SESSION ['direct_shopping'] ) && $_SESSION ['user_id'] == 0) {
			ss_log ( "in order process,没登录，则跳转到登录页面 " );
			/* 用户没有登录且没有选定匿名购物，转向到登录页面 */
			ecs_header ( "Location: flow.php?step=login\n" );
			exit ();
		}
		
		// 控制支付方式
		$is_weixin = isset ( $_REQUEST ['is_weixin'] ) ? trim ( $_REQUEST ['is_weixin'] ) : 0;
		
		/* 取得购物类型 */
		$flow_type = isset ( $_SESSION ['flow_type'] ) ? intval ( $_SESSION ['flow_type'] ) : CART_GENERAL_GOODS;
		
		// comment by wangcya , 20150105, 这个地方同样的$cart_insure_id可能被进入了两次
		$cart_insure_id = intval ( $_REQUEST ['cart_insure_id'] );
		ss_log ( "flow done!，cart_insure_id： " . $cart_insure_id );
		if ($cart_insure_id == 0) {
			ss_log ( "flow done, cart_insure_id 为空!" );
			// show_message("投保单不能为空!");
			
			// ////////////下面这段代码是为了处理历史问题的////////////////////
			$policy_id = intval ( $_POST ['policy_id'] );
			ss_log ( "flow done!，policy_id： " . $policy_id );
			
			if ($policy_id) {
				$sql = "SELECT cart_insure_id FROM t_insurance_policy WHERE policy_id=$policy_id";
				ss_log ( $sql );
				$cart_insure_id = $GLOBALS ['db']->getOne ( $sql );
				if ($cart_insure_id) {
					ss_log ( "find by policy_id, cart_insure_id: " . $cart_insure_id );
				}
			}
			// ////////////上面这段代码是为了处理历史问题的////////////////////
		}
		// start add by wangcya , 20150105, for bug[202],订单中的商品为空
		
		
		if ($cart_insure_id) {
			$sql = "SELECT COUNT(*) FROM bx_order_info WHERE cart_insure_id='$cart_insure_id'";
			ss_log ( $sql );
			$order_num = $GLOBALS ['db']->getOne ( $sql );
			if ($order_num > 0) {
				// add by dingchaoyang 2015-1-12
				// 响应json数据到客户端
				include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
				EbaAdapter::responseOrderSubmit ( '0' );
				// end add by dingchaoyang 2015-1-12
				ss_log ( "该订单和批量单已经关联,cart_insure_id: " . $cart_insure_id );
				return;
			}
			
			
			// 找到所有的投保单
			$sql = "select * from t_insurance_policy where cart_insure_id=$cart_insure_id";
			ss_log ( $sql );
			// end add by wangcya, for bug[193],能够支持多人批量投保，
			// 查找一下是为了验证数据库中是否存在，其实下面没用。
			$bx_policy = $GLOBALS ['db']->getAll ( $sql );
			if (empty ( $bx_policy )) {
				ss_log ( "没对应投保单!" );
				show_message ( "没对应投保单!" );
			}
			
			$policy_id = $bx_policy [0] ['policy_id']; // add by wangcya, 20141225
			$attribute_id = $bx_policy [0] ['attribute_id'];  //add yes123 2015-05-05 如果goods_id 拿不到，可以通过险种ID获取        
			include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
			$policy_ids_str = CommonUtils :: arrToStr($bx_policy, "policy_id");
			
			//判断这些保单是否已经属于其他订单
			$res = $this->checkPolicyStatus($policy_ids_str);
			if($res['code']==1)
			{
				$res['msg']= $res['msg']."已绑定其他订单，生成新定单";
				show_message ( 	$res['msg'] );
				return $res;
			}
			
		} else {
			ss_log ( "flow done, 批单id为空!" );
			show_message ( "批单id不能为空!" );
			exit ( 0 );
		}
		
		// end add by wangcya , 20150105, for bug[202],订单中的商品为空
		$auto_done = isset ( $_GET ['auto_done'] ) ? trim ( $_GET ['auto_done'] ) : '';
		if ($auto_done) {
			ss_log ( "step done , skip to auto_done" );
		}
		
		// /////////////////////////////////////////////////
		
		$consignee = get_consignee ( $_SESSION ['user_id'] );
		
		$_POST ['how_oos'] = isset ( $_POST ['how_oos'] ) ? intval ( $_POST ['how_oos'] ) : 0;
		$_POST ['card_message'] = isset ( $_POST ['card_message'] ) ? compile_str ( $_POST ['card_message'] ) : '';
		$_POST ['inv_type'] = ! empty ( $_POST ['inv_type'] ) ? compile_str ( $_POST ['inv_type'] ) : '';
		$_POST ['inv_payee'] = isset ( $_POST ['inv_payee'] ) ? compile_str ( $_POST ['inv_payee'] ) : '';
		$_POST ['inv_content'] = isset ( $_POST ['inv_content'] ) ? compile_str ( $_POST ['inv_content'] ) : '';
		$_POST ['postscript'] = isset ( $_POST ['postscript'] ) ? compile_str ( $_POST ['postscript'] ) : '';
		
		// start add by wangcya, for bug[193],能够支持多人批量投保
		$sql = "SELECT COUNT(*)  FROM t_insurance_policy WHERE cart_insure_id=$cart_insure_id";
		ss_log ( $sql );
		$policy_num = $GLOBALS ['db']->getOne ( $sql );
		ss_log ( "flow done,policy_num: " . $policy_num );
		$withdrawed_policy_num = 0;
		// end add by wangcya, for bug[193],能够支持多人批量投保
		// comment by zhangxi, 20150127, 对应bx_order_info表
		// added by zhangxi, 20150129, 增加两个字段的数据, activity_id, client_id
		$customer_info = false;
		$activity_info = false;
		if ($is_activity) {
			$customer_info = get_customer_info_by_cart_id ( $cart_insure_id );
			$activity_info = get_activity_info_by_goods ( $goods_id );
		}
		
		// add yes123 2015-03-12判断是否有渠道，有的话，保存渠道ID
		$temp_user_id = isset ( $_SESSION ['user_id'] ) ? $_SESSION ['user_id'] : 0;
		$sql = "SELECT institution_id FROM " . $GLOBALS ['ecs']->table ( 'users' ) . " WHERE user_id=$temp_user_id";
		$institution_id = $GLOBALS ['db']->getOne ( $sql );
		
		$order = array (
				'shipping_id' => intval ( isset ( $_POST ['shipping'] ) ? trim ( $_POST ['shipping'] ) : 0 ),
				'pay_id' => intval ( $_REQUEST ['payment'] ),
				'pack_id' => isset ( $_POST ['pack'] ) ? intval ( $_POST ['pack'] ) : 0,
				'card_id' => isset ( $_POST ['card'] ) ? intval ( $_POST ['card'] ) : 0,
				'card_message' => trim ( $_POST ['card_message'] ),
				'surplus' => isset ( $_POST ['surplus'] ) ? floatval ( $_POST ['surplus'] ) : 0.00,
				'service_money' => isset ( $_POST ['service_money'] ) ? floatval ( $_POST ['service_money'] ) : 0.00,//金币
				'integral' => isset ( $_POST ['integral'] ) ? intval ( $_POST ['integral'] ) : 0,
				'bonus_id' => isset ( $_POST ['bonus'] ) ? intval ( $_POST ['bonus'] ) : 0,
				'need_inv' => empty ( $_POST ['need_inv'] ) ? 0 : 1,
				'inv_type' => $_POST ['inv_type'],
				'inv_payee' => trim ( $_POST ['inv_payee'] ),
				'inv_content' => $_POST ['inv_content'],
				'postscript' => trim ( $_POST ['postscript'] ),
				'how_oos' => isset ( $_LANG ['oos'] [$_POST ['how_oos']] ) ? addslashes ( $_LANG ['oos'] [$_POST ['how_oos']] ) : '',
				'need_insure' => isset ( $_POST ['need_insure'] ) ? intval ( $_POST ['need_insure'] ) : 0,
				'user_id' => $_SESSION ['user_id'],
				// 'add_time' => time(),
				'add_time' => time (),
				'order_status' => OS_UNCONFIRMED,
				'shipping_status' => SS_UNSHIPPED,
				'pay_status' => PS_UNPAYED,
				'cart_insure_id' => $cart_insure_id, // add by wangcya, for bug[193],能够支持多人批量投保
				'policy_num' => $policy_num, // //add by wangcya, for bug[193],能够支持多人批量投保,保单个数
				'withdrawed_policy_num' => $withdrawed_policy_num, // //add by wangcya, for bug[193],能够支持多人批量投保,被注销的保单个数
				'policy_id' => isset ( $_POST ['policy_id'] ) ? intval ( $_POST ['policy_id'] ) : 0, // del by wangcya, for bug[193],能够支持多人批量投保，留着。
				'agency_id' => get_agency_by_regions ( array (
						$consignee ['country'],
						$consignee ['province'],
						$consignee ['city'],
						$consignee ['district'] 
				) ),
				'client_id' => $customer_info ? $customer_info ['user_id'] : 0, // added by zhangxi, 20150129,活动的c端用户id
				'activity_id' => $activity_info ? $activity_info ['act_id'] : 0, // added by zhangxi, 活动的id
				'organ_user_id' => $institution_id 
		); // add yes123 2015-03-12判断是否有渠道，有的话，保存渠道ID

		
		// add by dingchaoyang 2015-2-3添加险种名称，在支付时 需要赋值给第三方支付的参数 商品名称或订单名称
		if ($goods) {
			$order ['trade_subject'] = $goods ['attribute_name'];
		}
		// end
		
		/* 扩展信息 */
		if (isset ( $_SESSION ['flow_type'] ) && intval ( $_SESSION ['flow_type'] ) != CART_GENERAL_GOODS) {
			$order ['extension_code'] = $_SESSION ['extension_code'];
			$order ['extension_id'] = $_SESSION ['extension_id'];
		} else {
			$order ['extension_code'] = '';
			$order ['extension_id'] = 0;
		}
		
		/* 检查积分余额是否合法 */
		$user_id = $_SESSION ['user_id'];
		if ($user_id > 0) {
			$user_info = user_info ( $user_id );
			
			$order ['surplus'] = min ( $order ['surplus'], $user_info ['user_money'] + $user_info ['credit_line'] );
			if ($order ['surplus'] < 0) {
				$order ['surplus'] = 0;
			}
			
			//add yes123 2015-07-07 检查金币
			$order ['service_money'] = min ( $order ['service_money'], $user_info ['service_money'] + $user_info ['credit_line'] );
			if ($order ['service_money'] < 0) {
				$order ['service_money'] = 0;
			}
			
			// 查询用户有多少积分
			$flow_points = flow_available_points (); // 该订单允许使用的积分
			$user_points = $user_info ['pay_points']; // 用户的积分总数
			
			$order ['integral'] = min ( $order ['integral'], $user_points, $flow_points );
			if ($order ['integral'] < 0) {
				$order ['integral'] = 0;
			}
		} else {
			$order ['surplus'] = 0;
			$order ['integral'] = 0;
			$order ['service_money']=0;
		}
		

		
		// comment by wangcya, 20150127, 找到该批量下面所有的投保单的总保费
		$sql = "SELECT SUM(total_premium) as total_premium_mutil FROM t_insurance_policy WHERE cart_insure_id=$cart_insure_id";
		ss_log ("找到该批量下面所有的投保单的总保费:".$sql );
		$total_premium_mutil = $GLOBALS ['db']->getOne ( $sql );
		ss_log ( "flow done,total_premium_mutil: " . $total_premium_mutil );
		
		if ($total_premium_mutil <= 0 && $_REQUEST['insurer_code']!='SSBQ') {
			ss_log ( "总保费不合规!" );
			show_message ( "总保费不合规!" );
			exit ( 0 );
		}
		
		// start add by wangcya, 20150116,再次校验
		$sql = "SELECT * FROM t_cart_insure WHERE rec_id='$cart_insure_id'";
		ss_log ( $sql );
		$cart_insure_attr = $GLOBALS ['db']->getRow ($sql);
		if ($cart_insure_attr) {
			if (floatval ( $cart_insure_attr ['total_price'] ) != floatval ( $total_premium_mutil )) {
				showmessage ( "生成订单时候检查到总价不相符" );
				exit ( 0 );
			} else {
				ss_log ( "批保单总费用等于保单费用的汇总" );
			}
		} else {
			showmessage ( "没找到批保单" );
			exit ( 0 );
		}
		// end add by wangcya, 20150116,再次校验
		
                      
		
		/* 计算订单的费用 */
		// $total_premium = $bx_policy['all_total_premium'];//del by wangcya, for bug[193],能够支持多人批量投保，
		$total_premium = $total_premium_mutil; // add by wangcya, for bug[193],能够支持多人批量投保，
		                                       
		// comment by zhangxi, 20150127, 再次计算用户真正需要实际需要付的钱
		                                       // $total = bx_order_fee($order, $total_premium, $consignee);//设置到总保费中
		$total = bx_order_fee ( $order, $total_premium, $consignee, $bx_policy, $is_activity ? $goods_id : 0 ); // 设置到总保费中
		
		ss_log ( "order done, goods_price" . $total ['goods_price'] );
		
		$order ['bonus'] = $total ['bonus'];
		$order ['goods_amount'] = $total ['goods_price']; // comment by wangcya ,这个就是商品的总价。
		$order ['discount'] = $total ['discount']; // comment by zhangxi, 20150127, 折扣
		$order ['surplus'] = $total ['surplus'];
		$order ['service_money'] = $total ['service_money'];
		$order ['tax'] = $total ['tax'];
		
		ss_log ( "order done, goods_price： " . $total ['goods_price'] . ",discount=" . $order ['discount'].",service_money:".$total ['service_money'].",surplus:".$order ['surplus'] );
		
	
		/* 检查代金券是否存在 */
		if ($order ['bonus_id'] > 0) {
			$bonus = bonus_info ( $order ['bonus_id'] );
			
			if (empty ( $bonus ) || $bonus ['user_id'] != $user_id || $bonus ['order_id'] > 0 || $bonus ['min_goods_amount'] > $order ['goods_amount']) {
				$order ['bonus_id'] = 0;
			}
		} elseif (isset ( $_POST ['bonus_sn'] )) {
			$bonus_sn = trim ( $_POST ['bonus_sn'] );
			$bonus = bonus_info ( 0, $bonus_sn );
			// $now = time();
			$now = time ();
			if (empty ( $bonus ) || $bonus ['user_id'] > 0 || $bonus ['order_id'] > 0 || $bonus ['min_goods_amount'] > $order ['goods_amount'] || $now > $bonus ['use_end_date']) {
				
			} else {
				if ($user_id > 0) {
					$sql = "UPDATE " . $GLOBALS ['ecs']->table ( 'user_bonus' ) . " SET user_id = '$user_id' WHERE bonus_id = '$bonus[bonus_id]' LIMIT 1";
					$GLOBALS ['db']->query ( $sql );
				}
				$order ['bonus_id'] = $bonus ['bonus_id'];
				$order ['bonus_sn'] = $bonus_sn;
			}
		}
		
		
		
		
		// comment by zhangxi, 20150127, 暂时没有使用
		// 购物车中的商品能享受代金券支付的总额
		$discount_amout = compute_discount_amount ();
		
		// 代金券和积分最多能支付的金额为商品总额
		$temp_amout = $order ['goods_amount'] - $discount_amout;
		if ($temp_amout <= 0) {
			$order ['bonus_id'] = 0;
		}
		
		/* 支付方式 */
		if ($order ['pay_id'] > 0) {
			$payment = payment_info ( $order ['pay_id'] );
			$order ['pay_name'] = addslashes ( $payment ['pay_name'] );
		}
		$order ['pay_fee'] = $total ['pay_fee'];
		$order ['cod_fee'] = $total ['cod_fee'];
		
		/* 商品包装 */
		if ($order ['pack_id'] > 0) {
			$pack = pack_info ( $order ['pack_id'] );
			$order ['pack_name'] = addslashes ( $pack ['pack_name'] );
		}
		$order ['pack_fee'] = $total ['pack_fee'];
		
		/* 祝福贺卡 */
		if ($order ['card_id'] > 0) {
			$card = card_info ( $order ['card_id'] );
			$order ['card_name'] = addslashes ( $card ['card_name'] );
		}
		$order ['card_fee'] = $total ['card_fee'];
		
		// comment by zhangxi, 20150127, 用户真正要支付的钱
		$order ['order_amount'] = number_format ( $total ['amount'], 2, '.', '' );
		
		/* 如果全部使用余额支付，检查余额是否足够 */
		if ($payment ['pay_code'] == 'balance' && $order ['order_amount'] > 0) {
			if ($order ['surplus'] > 0) // 余额支付里如果输入了一个金额
			{ // 这里感觉有问题???
				$order ['order_amount'] = $order ['order_amount'] + $order ['surplus'];
				$order ['surplus'] = 0;
			}
			// comment by zhangxi, 20150227, 余额不足的情况
			if ($order ['order_amount'] > ($user_info ['user_money'] + $user_info ['credit_line'])) {
				
				//add yes123 2015-06-09 提示用于余额不足，可以还其他支付方式
				$smarty->assign ( 'surplus_scarce_msg', $_LANG['balance_not_enough'] );
				// 当余额不足时，也能正常提交订单 yes123 2014-09-22
				// show_message($_LANG['balance_not_enough']);
			} 
			else // 余额足的情况
			{ 
				$order ['surplus'] = $order ['order_amount'];
				$order ['order_amount'] = 0;
			}
		}
		
		/* 如果订单金额为0（使用余额或积分或代金券支付），修改订单状态为已确认、已付款 */
		if ($order ['order_amount'] <= 0) {
			$order ['order_status'] = OS_CONFIRMED; // 订单状态为已确认
			$order ['confirm_time'] = time ();
			$order ['pay_status'] = PS_PAYED; // comment by zhangxi, 20150227, 余额支付的情况，这里就修改了状态。
			$order ['pay_time'] = time ();
			$order ['order_amount'] = 0;
		}
		
		// comment by zhangxi, 20150228, 这个什么用,好像是积分相关的
		$order ['integral_money'] = $total ['integral_money'];
		$order ['integral'] = $total ['integral'];
		
		if ($order ['extension_code'] == 'exchange_goods') {
			$order ['integral_money'] = 0;
			$order ['integral'] = $total ['exchange_integral'];
		}
		
		$order ['from_ad'] = ! empty ( $_SESSION ['from_ad'] ) ? $_SESSION ['from_ad'] : '0';
		$order ['referer'] = ! empty ( $_SESSION ['referer'] ) ? addslashes ( $_SESSION ['referer'] ) : '';
		
		/* 记录扩展信息 */
		if ($flow_type != CART_GENERAL_GOODS) {
			$order ['extension_code'] = $_SESSION ['extension_code'];
			$order ['extension_id'] = $_SESSION ['extension_id'];
		}
		
		$affiliate = unserialize ( $_CFG ['affiliate'] );
		if (isset ( $affiliate ['on'] ) && $affiliate ['on'] == 1 && $affiliate ['config'] ['separate_by'] == 1) {
			// 推荐订单分成
			$parent_id = get_affiliate ();
			if ($user_id == $parent_id) {
				$parent_id = 0;
			}
		} elseif (isset ( $affiliate ['on'] ) && $affiliate ['on'] == 1 && $affiliate ['config'] ['separate_by'] == 0) {
			// 推荐注册分成
			$parent_id = 0;
		} else {
			// 分成功能关闭
			$parent_id = 0;
		}
		$order ['parent_id'] = $parent_id;
		
		/* 插入订单表 */
		
		// $sql = "SELECT count(*) FROM ".$GLOBALS['ecs']->table('order_info')." WHERE policy_id = '$policy_id'";
		
		$sql = "SELECT count(*) FROM " . $GLOBALS ['ecs']->table ( 'order_info' ) . " WHERE cart_insure_id = '$cart_insure_id'"; // add by wangcya, 20150127
		ss_log ( $sql );
		
		$policy_count = $GLOBALS ['db']->getOne ( $sql );
		
		// comment by zhangxi, 20150227, 批量id搜索到的投保单个数为0，才进行
		if ($policy_count == 0) { // 为了防止重复提交订单的情况。
			$error_no = 0;
			do {
				// add platformid by dingchaoyang 2014-12-19
				include_once (ROOT_PATH . 'api/EBaoApp/platformEnvironment.class.php');
				$platform_id = PlatformEnvironment::getPlatformID ();
				if ($platform_id) {
					$order ['platform_id'] = $platform_id;
				}
				// end by dingchaoyang 2014-12-19
				
				
				// mod by zhangxi, 20150430, 核保提前，订单号可能已经生成，比如NCI的产品
				//后续统一规划
				$wheresql = "policy_id='$policy_id'";
				$sql = "SELECT * FROM t_insurance_policy WHERE $wheresql LIMIT 1";
				$policy_info = $GLOBALS ['db']->getRow($sql);
				//mod by zhangxi, 20150525, 如果是新华的才进行处理
				if(!empty($policy_info) && $policy_info['insurer_code'] == 'NCI')
				{
					$wheresql = "policy_id='$policy_id'";
					$sql = "SELECT * FROM t_insurance_policy_nci_underwriting_return WHERE $wheresql LIMIT 1";
					ss_log(__FUNCTION__.", ".$sql);
					$return_data = $GLOBALS ['db']->getRow($sql);
					if(!empty($return_data))
					{
						$order ['order_sn'] = $return_data['partnerOrderId'];
					}
					else
					{
						$order ['order_sn'] = get_order_sn (); // 获取新订单号
					}
				}
				else
				{
					$order ['order_sn'] = get_order_sn (); // 获取新订单号
				}
				
				
				//如果是诉讼保全，设置为未支付
				if($_REQUEST['insurer_code']=='SSBQ')
				{
					$order['order_status'] = OS_UNCONFIRMED;
					$order['pay_status'] = PS_UNPAYED;
	
				}
				
				
				$GLOBALS ['db']->autoExecute ( $GLOBALS ['ecs']->table ( 'order_info' ), $order, 'INSERT' );
				
				$error_no = $GLOBALS ['db']->errno ();
				
				if ($error_no > 0 && $error_no != 1062) {
					die ( $GLOBALS ['db']->errorMsg () );
				}
			} while ( $error_no == 1062 ); // 如果是订单号重复则重新提交数据
			
			$new_order_id = $GLOBALS ['db']->insert_id ();
			$order ['order_id'] = $new_order_id;
			
			ss_log ( "new_order_id: " . $new_order_id . " order_sn: " . $order ['order_sn'] );
			
			// ///////start add by wangcya , 20140830, 在这里生成了订单，所以应该也在这里把订单和保单关联起来
			$order_sn = $order ['order_sn']; // add by wangcya , 20141023,把订单号增加到保单上面。
			                                
			// $sql = "UPDATE t_insurance_policy SET order_id='$new_order_id',order_sn='$order_sn' WHERE policy_id=".$order['policy_id'];
			                                
			// 更新所有这些投保单的的order_id，order_sn,并且没有订单号的
			if ($cart_insure_id) {
				// 千万不能把那些已经存在的订单更新了
				// mod by zhangxi, 20150129, 增加client_id和活动id的更新
				$client_id = $customer_info ? $customer_info ['user_id'] : 0;
				$activity_id = $activity_info ? $activity_info ['act_id'] : 0;
				// $sql = "UPDATE t_insurance_policy SET order_id='$new_order_id',order_sn='$order_sn',client_id='$client_id',activity_id='$activity_id' WHERE cart_insure_id='$cart_insure_id' AND order_id='0'";
				$sql = "UPDATE t_insurance_policy SET order_id='$new_order_id',order_sn='$order_sn',client_id='$client_id',activity_id='$activity_id',organ_user_id='$institution_id',policy_status='saved' WHERE cart_insure_id='$cart_insure_id' AND order_id='0'";
				// $sql = "UPDATE t_insurance_policy SET order_id='$new_order_id',order_sn='$order_sn' WHERE cart_insure_id='$cart_insure_id' AND order_id='0'";//modify by wangcya, for bug[193],能够支持多人批量投保，
				ss_log ( $sql );
				$GLOBALS ['db']->query ( $sql );
				
				$sql = "UPDATE t_cart_insure SET order_id='$new_order_id' WHERE rec_id='$cart_insure_id'"; // modify by wangcya, for bug[193],能够支持多人批量投保，
				ss_log ( $sql );
				$GLOBALS ['db']->query ( $sql );
				
				
				//更新投保人
				$sql = "SELECT applicant_username  FROM t_insurance_policy WHERE order_id='$new_order_id'";
				$applicant_username_arr = $GLOBALS ['db']->getAll($sql);
				$applicant_username_str = CommonUtils :: arrToStr($applicant_username_arr, "applicant_username");
				$sql = "UPDATE " . $GLOBALS ['ecs']->table ( 'order_info' ) . " SET applicant_username_str='$applicant_username_str' WHERE order_id='$new_order_id'";
				$GLOBALS ['db']->query ( $sql );
				
				
				
			}
			// ///////end add by wangcya , 20140830, 在这里生成了订单，所以应该也在这里把订单和保单关联起来
			
			
			
			//add yes123 2015-05-21 循环插入订单下的商品
			$sql ="SELECT COUNT(attribute_id) AS num,attribute_id FROM t_insurance_policy " .
					"  WHERE cart_insure_id='$cart_insure_id' GROUP BY attribute_id";
		   $attribute_num_list =  $GLOBALS ['db']->getAll( $sql );
			/* 插入订单商品 */
			foreach ($attribute_num_list as $key => $attribute ) 
			{
	       		$attribute_id = $attribute['attribute_id'];
	       		$num = $attribute['num'];
	       		if($attribute_id)
				{
					$sql = "SELECT goods_id FROM bx_goods WHERE tid='$attribute_id' LIMIT 1";
					ss_log('提交订单，没有goods_id，通过险种ID获取： '.$sql);
					$goods_id = $GLOBALS ['db']->getOne($sql);
					if($goods_id)
					{	
						$sql = "INSERT INTO " . $GLOBALS ['ecs']->table ( 'order_goods' ) . "( " . "order_id, goods_id, goods_name, goods_sn, goods_number, market_price, " . "goods_price, is_real, extension_code) " . " SELECT '$new_order_id', goods_id, goods_name, goods_sn, $num, market_price, " . "shop_price, is_real, extension_code" . " FROM " . $GLOBALS ['ecs']->table ( 'goods' ) . " WHERE goods_id = '" . $goods_id . "'";
						ss_log ("插入订单商品:".$sql );
						$GLOBALS ['db']->query ( $sql );
						
					}
					
				}
	       		
			}

		} else {
			// add by dingchaoyang 2014-11-24
			// 响应json数据到客户端
			include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
			EbaAdapter::responseOrderSubmit ();
			// end add by dingchaoyang 2014-11-24
			
			show_message ( '订单已存在！', '', 'user.php?act=order_list' );
			exit ();
		}
		
		/* 修改拍卖活动状态 */
		if ($order ['extension_code'] == 'auction') {
			$sql = "UPDATE " . $GLOBALS ['ecs']->table ( 'goods_activity' ) . " SET is_finished='2' WHERE act_id=" . $order ['extension_id'];
			$GLOBALS ['db']->query ( $sql );
		}
		
		ss_log ( "from flow.php, into function log_account_change" );
		// require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/shopping_flow.php');
		/* 处理余额、金币、积分、代金券 */
		// comment by zhangxi, 20150228, 真正的余额支付的处理
		if ($order ['user_id'] > 0 ) 
		{
			$change_desc=sprintf ( $_LANG ['pay_order'], '' );
			$this->surplusPay($order,$change_desc,$order ['order_sn']);
		
		}
		
		// added by zhangxi, 20150127,有折扣的情况时，订单中折扣的钱得从总账户中扣除
		if ($order ['user_id'] > 0 && $order ['discount'] > 0) {
			// 不是扣操作当前订单的账户，而是扣公司的账户
			// ebaoins_user=user_name
			// 根据用户名查询用户id，
			ss_log ( __FUNCTION__ . "user_id=" . $order ['user_id'] . ",discount=" . $order ['discount'] );
			$sql = "SELECT user_id " . "FROM " . $GLOBALS ['ecs']->table ( 'users' ) . " WHERE user_name = 'ebaoins_user'";
			$ebaoins_user_id = $GLOBALS ['db']->getOne ( $sql );
			log_account_change ( $ebaoins_user_id, $order ['discount'] * (- 1), 0, 0, 0, sprintf ( $_LANG ['pay_order'], "营销活动,订单号:" . $order ['order_sn'] ), ACT_OTHER, $order ['order_sn'] );
		}
		
		if ($order ['bonus_id'] > 0 && $temp_amout > 0) {
			use_bonus ( $order ['bonus_id'], $new_order_id );
		}
		
		/* 如果使用库存，且下订单时减库存，则减少库存 
		if ($_CFG ['use_storage'] == '1' && $_CFG ['stock_dec_time'] == SDT_PLACE) {
			change_order_goods_storage ( $order ['order_id'], true, SDT_PLACE );
		}
		
		/* 如果需要，发短信 , ask by wangcya, 20141011, 这里和支付宝的地方的sms_order_payed不一样 */
		/*
		 * del yes123 2015-01-19 echop 自带的短信功能，取消 if ($_CFG['sms_order_placed'] == '1' && $_CFG['sms_shop_mobile'] != '')
		 * {
		 * include_once('includes/cls_sms.php');
		 * $sms = new sms();
		 * $msg = $order['pay_status'] == PS_UNPAYED ?
		 * $_LANG['order_placed_sms'] : $_LANG['order_placed_sms'] . '[' . $_LANG['sms_paid'] . ']';
		 * $flow_type = isset($order['tel']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;
		 * $sms->send($_CFG['sms_shop_mobile'], sprintf($msg, $order['consignee'], $order['tel']),'', 13,1);
		 * }
		 */
		
		/* 如果订单金额为0 处理虚拟卡 */
		if ($order ['order_amount'] <= 0) {
			$sql = "SELECT goods_id, goods_name, goods_number AS num FROM " . $GLOBALS ['ecs']->table ( 'cart' ) . " WHERE is_real = 0 AND extension_code = 'virtual_card'" . " AND session_id = '" . SESS_ID . "' AND rec_type = '$flow_type'";
			
			$res = $GLOBALS ['db']->getAll ( $sql );
			
			$virtual_goods = array ();
			foreach ( $res as $row ) {
				$virtual_goods ['virtual_card'] [] = array (
						'goods_id' => $row ['goods_id'],
						'goods_name' => $row ['goods_name'],
						'num' => $row ['num'] 
				);
			}
			
			if ($virtual_goods and $flow_type != CART_GROUP_BUY_GOODS) {
				/* 虚拟卡发货 */
				if (virtual_goods_ship ( $virtual_goods, $msg, $order ['order_sn'], true )) {
					/* 如果没有实体商品，修改发货状态，送积分和代金券 */
					$sql = "SELECT COUNT(*)" . " FROM " . $GLOBALS ['ecs']->table ( 'order_goods' ) . " WHERE order_id = '$order[order_id]' " . " AND is_real = 1";
					if ($GLOBALS ['db']->getOne ( $sql ) <= 0) {
						/* 修改订单状态 */
						update_order ( $order ['order_id'], array (
								'shipping_status' => SS_SHIPPED,
								'shipping_time' => time () 
						) );
						
						/* 如果订单用户不为空，计算积分，并发给用户；发代金券 */
						if ($order ['user_id'] > 0) {
							/* 取得用户信息 */
							$user = user_info ( $order ['user_id'] );
							
							/* 计算并发放积分 */
							$integral = integral_to_give ( $order );
							log_account_change ( $order ['user_id'], 0, 0, intval ( $integral ['rank_points'] ), intval ( $integral ['custom_points'] ), sprintf ( $_LANG ['order_gift_integral'], $order ['order_sn'], $order ['order_sn'] ) );
							
							/* 发放代金券 */
							send_order_bonus ( $order ['order_id'] );
						}
					}
				}
			}
		}
		
		/* 清空购物车 */
		clear_cart ( $flow_type );
		/* 清除缓存，否则买了商品，但是前台页面读取缓存，商品数量不减少 */
		clear_all_files ();
		
		/* 插入支付日志，这个地方很重要 */
		ss_log ( "将要进行函数 insert_pay_log " );
		
		// add by wangcya, 20150127,下面这个函数非常重要，要前提
		$order ['log_id'] = insert_pay_log ( $new_order_id, $order ['order_amount'], PAY_ORDER );
		
		$pay_online = '';
		/* 取得支付信息，生成支付代码 */
		// comment by zhangxi ， 20150127， 进行支付的处理流程
		if ($order ['order_amount'] > 0) {
			$payment = get_payment_order($order);
			//$payment = payment_info ( $order ['pay_id'] );
			
			include_once ('includes/modules/payment/' . $payment ['pay_code'] . '.php');
			
			$pay_obj = new $payment ['pay_code'] ();
			
			ss_log ( "will into pay get_code, pay_code: " . $payment ['pay_code'] );
			ss_log ( "done order activity_id: " . $order ['activity_id'] );
			
			// comment by wangcya, 20150127,这个函数很重要，生成例如支付宝支付的按钮和表单。
			$pay_online = $pay_obj->get_code ( $order, unserialize_config ( $payment ['pay_config'] ) );
			ss_log ( $pay_online );
			$order ['pay_desc'] = $payment ['pay_desc'];
			
			$smarty->assign ( 'pay_online', $pay_online );
		}
		
		if (! empty ( $order ['shipping_name'] )) {
			$order ['shipping_name'] = trim ( stripcslashes ( $order ['shipping_name'] ) );
		}
		
		/* 订单信息 */
		$smarty->assign ( 'order', $order );
		$smarty->assign ( 'total', $total );
		$smarty->assign ( 'order_submit_back', sprintf ( $_LANG ['order_submit_back'], $_LANG ['back_home'], $_LANG ['goto_user_center'] ) ); // 返回提示
		
		ss_log ( "from follow done, into function assign_commision_and_post_policy!" );
		// 处理佣金,同时进行投保操作
		
		// comment by wangcya, 20150127,其实这个函数内部判断是否已经支付，所以根据支付状态来结算佣金
		// mod by zhangxi, 20150305, 修改函数名,分配佣金和投保
		// $result_attr = yongJin2($order['order_sn']);
		$result_attr = assign_commision_and_post_policy ( $order ['order_sn'] );
		// ////////////////////////////////////////////////////////////////////
		
		user_uc_call ( 'add_feed', array (
				$order ['order_id'],
				BUY_GOODS 
		) ); // 推送feed到uc
		unset ( $_SESSION ['flow_consignee'] ); // 清除session中保存的收货人信息
		unset ( $_SESSION ['flow_order'] );
		unset ( $_SESSION ['direct_shopping'] );
		
		// start add yes123 2015-01-23 销售量+1 ，用来统计销量的。
		// bx_goods 销量加1
		if ($_REQUEST ['goods_id']) {
			$sql = "UPDATE " . $GLOBALS ['ecs']->table ( 'goods' ) . " SET goods_sales_volume=goods_sales_volume+1 WHERE goods_id='$_REQUEST[goods_id]'";
			$GLOBALS ['db']->query ( $sql );
			ss_log ( 'bx_goods 产品销量+1:' . $sql );
			
			// t_insurance_product_attribute 销量加1
			$sql = "SELECT tid FROM " . $GLOBALS ['ecs']->table ( 'goods' ) . " WHERE goods_id=$_REQUEST[goods_id] ";
			ss_log ( $sql );
			$tid = $GLOBALS ['db']->getOne ( $sql );
			if ($tid) // add by wangcya, 20150127
{
				$sql = "UPDATE t_insurance_product_attribute SET attribute_sales_volume=attribute_sales_volume+1 WHERE attribute_id=" . $tid;
				ss_log ( $sql );
				$GLOBALS ['db']->query ( $sql );
			} else {
				ss_log ( "find tid by goods_id ,is null" );
			}
			
			ss_log ( 't_insurance_product_attribute 险种销量+1:' . $sql );
		} else {
			ss_log ( "_REQUEST goods_id is empty!" );
		}
		// end add yes123 2015-01-23 销售量+1
		
		// comment by zhangxi, 以下是手机端浏览器关于订单的处理
		// add by dingchaoyang 2014-11-24
		// 响应json数据到客户端
		include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
		EbaAdapter::responseOrderSubmit ( $order ['order_sn'] . '-' . $order ['log_id'], $pay_online );
		// end add by dingchaoyang 2014-11-24
		
		// mod by zhangxi, 20150312, 活动代码通用性修改
		// if(strstr($goods['attribute_type'],$G_WORDS_HUODONG))//险种属性中只要含有huodong字符，则进入，保证代码通用性
		// {
		// add yes123 2015-01-29
		// include_once(ROOT_PATH.'includes/hongdong_function.php');
		// done_finish_operation($order['order_id']);
		if ($is_activity) {
			$_SESSION ['user_id'] = 0;
			$_SESSION ['user_name'] = 0;
			$_SGLOBAL ['supe_uid'] = 0;
			$_SESSION ['login_type'] = "";
			// supe_username
			$_SGLOBAL ['supe_username'] = 0;
		}
		
		// }
		
		//如果是诉讼保全，邮件通知管理员
		if($_REQUEST['insurer_code']=='SSBQ')
		{
			
			$mail_address_list = array('wangyl@aalib.cn','chenlei@aalib.cn','jiangjc@aalib.cn');
			
		    /* 给商家发邮件 */
	        $tpl = get_mail_template('remind_of_new_order');
	        $smarty->assign('order', $order);
	        $smarty->assign('shop_name', $_CFG['shop_name']);
	        $smarty->assign('send_date', date($_CFG['time_format']));
	        $content = $smarty->fetch('str:' . $tpl['template_content']);
	        
	        foreach ( $mail_address_list as $key => $mail_address ) 
	        {
		        send_mail($_CFG['shop_name'], $mail_address, $tpl['template_subject'], $content, $tpl['is_html']);
       
			}
	        
		}
		
		
	}
	
	
	
	private function resetCartInsureIdByPolicyIds($policy_ids,$user_id)
	{
		$sql=" SELECT SUM(total_premium) AS total_premium,count(*) AS policy_num FROM t_insurance_policy WHERE policy_id IN($policy_ids) ";
		ss_log(__FUNCTION__.",获取保单总额，".$sql);
		$total_premium_policy_num = $GLOBALS['db']->getRow($sql);
		$total_price = $total_premium_policy_num['total_premium'];
		$policy_num = $total_premium_policy_num['policy_num'];
		
		$sql = "INSERT INTO t_cart_insure (user_id,total_apply_num,total_price) VALUES('$user_id','$policy_num','$total_price')";
		ss_log(__FUNCTION__.",INSERT t_cart_insure，".$sql);
		$r = $GLOBALS['db']->query($sql);
		$cart_insure_id = $GLOBALS['db']->insert_id();
		if($cart_insure_id)
		{
			$sql = "UPDATE t_insurance_policy SET cart_insure_id='$cart_insure_id' WHERE policy_id IN($policy_ids)";
			ss_log(__FUNCTION__.",更新cart_insure_id字段，".$sql);
			$GLOBALS['db']->query($sql);
		}
		return 	$cart_insure_id;
	}
	
	
	public function checkPolicyStatus($policy_ids)
	{
		$res = array();
		$res['code']=0;
		
		$sql="SELECT * FROM t_insurance_policy WHERE policy_id IN($policy_ids) ";
		ss_log(__FUNCTION__.",获取所有保单".$sql);
		$policy_list= $GLOBALS['db']->getAll($sql);
		
		$fail_ids = ""; 
		foreach ( $policy_list as $key => $policy ) {
       		//判断如果保单不是saved或者cart状态，未支付，没有订单绑定，才可以删除
       		$policy_status = $policy['policy_status'];
       		$order_id = $policy['order_id'];
       		$pay_status = $policy['pay_status'];
       		ss_log(__FUNCTION__.",policy_status:".$policy_status);
       		ss_log(__FUNCTION__.",order_id:".$order_id);
       		ss_log(__FUNCTION__.",pay_status:".$pay_status);
       		
       		if($order_id!=0)
       		{
       			$fail_ids.=$policy['policy_id'].",";
       		}
       		
		}
		if($fail_ids)
		{
			$res['code']=1;
			$res['msg']=$fail_ids;
			ss_log(__FUNCTION__.",".$fail_ids."已绑定其他订单！");
			return $res;
		}
		else
		{
			return $res; 
		}
		
	}
	
	public function get_surplus_money($user_id,$surplus)
	{
		ss_log(__FUNCTION__."，user_id:".$user_id.",surplus:".$surplus);
		$res = array();
		$res['user_id']=$user_id;
		$res['award_money']=0;
		$res['service_money']=0;
		$res['user_money']=0;
		$res['flag']=false;
		$sql=" SELECT * FROM bx_users WHERE user_id = '$user_id'";
		$user_info = $GLOBALS['db']->getRow($sql);
		
		if($user_info)
		{
			if($user_info['award_money']>0)
			{
				$res['award_money']=$user_info['award_money'];
				if($user_info['award_money']>=$surplus)
				{
					$res['award_money']=$surplus;
					$res['flag']=true;
					ss_log(__FUNCTION__.",奖励账户足够付订单award_money:".$res['award_money']);
					return $res;
				}
	
			}
			else
			{
				ss_log(__FUNCTION__.",奖励账户没有金额");
			}
			
			//使用服务费账户金额	
			if($user_info['service_money']>0)
			{
				$res['service_money']=$user_info['service_money'];
				if(($res['award_money']+$user_info['service_money'])>=$surplus)
				{
					$res['service_money']=$surplus-$res['award_money'];
					$res['flag']=true;
					ss_log(__FUNCTION__.",奖励账户和服务费账户足够付订单award_money:".$res['award_money'].",service_money:".$res['service_money']);
					return $res;				
				}
	
			}
			else
			{
				ss_log(__FUNCTION__.",服务费账户没有金额");
			}	
			
			//使用充值账户金额
			if($user_info['user_money']>0)
			{
				ss_log("充值账户余额user_money:".$user_info['user_money']);	
				ss_log("使用奖励账户金额award_money:".$res['award_money']);	
				ss_log("使用服务费账户金额service_money:".$res['service_money']);	
				
				if(($user_info['user_money']+$res['award_money']+$res['service_money'])>=$surplus)
				{
					$res['user_money']=$surplus-($res['award_money']+$res['service_money']);
					ss_log("计算后的user_money:".$res['user_money']);	
				}
				
			}
			
			
		}
		else
		{
			ss_log(__FUNCTION__.",查询不到用户，无法支付");
		}
		
		ss_log(__FUNCTION__.",应扣除award_money：".$res['award_money']);
		ss_log(__FUNCTION__.",应扣除service_money：".$res['service_money']);
		ss_log(__FUNCTION__.",应扣除user_money：".$res['user_money']);
		$res['flag']=true;
		return $res;
		
	}
	
	/**
	 * 记录账户变动
	 * @param   array   $res          保存着每个账户里应扣除的费用
	 * @param   string  $change_desc  描述
	 * @param   string  $order_sn     订单号
	 * @return  void
	 */
	public function surplusPay($pay_data,$change_desc,$order_sn)
	{
		$user_id = $pay_data['user_id'];
		if($pay_data['service_money']>0)
		{
			$temp_change_desc = $change_desc;
			$temp_change_desc.=',从金币账户扣除';
			log_account_change ( $user_id, $pay_data['service_money'] * (- 1), 0, 0, 0, $temp_change_desc, ACT_OTHER, $order_sn,
					'','',0,'order_service_money','service_money'	
			);
		}
		if($pay_data['surplus']>0)
		{
			$temp_change_desc = $change_desc;
			$temp_change_desc.=',从充值账户扣除';
			log_account_change ( $user_id, $pay_data['surplus'] * (- 1), 0, 0, 0, $temp_change_desc, ACT_OTHER, $order_sn,
					'','',0,'order_user_money','user_money'	
			);
		}
		
		if ($pay_data ['integral'] > 0) {
			$temp_change_desc = $change_desc;
			$temp_change_desc.=',从消费积分账户扣除';
			log_account_change($user_id, 0, 0, 0, $pay_data['integral'] * (-1), $temp_change_desc,ACT_OTHER,$order_sn);
		}
		
		
	}
	
	
	
}
?>