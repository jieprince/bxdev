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
 * $Author: liuhui $
 * $Id: order.php 15013 2008-10-23 09:31:42Z liuhui $
*/

define('IN_ECS', true);
define('ECS_ADMIN', true);

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_order.php');

/* 载入语言文件 */
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/user.php');
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/common.php');
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/shopping_flow.php');

$flow_type = 0;
$_LANG['gram'] = '克';
$_LANG['kilogram'] = '千克';
$tips = '订单提交成功！';

if ($_SESSION['user_id'] > 0)
{
	$smarty->assign('user_name', $_SESSION['user_name']);
}

ss_log("order act: ".$_REQUEST['act']);

if($_REQUEST['act'] == 'order_lise')
{//相当于pc端的add_bx   
	// add by dingchaoyang 2015-3-31
	include_once (ROOT_PATH . 'api/EBaoApp/eba_sessionManager.class.php');
	Eba_SessionManager::setSession();
	//end 
	include_once(ROOT_PATH . 'includes/class/Order.class.php');
	//added by zhangxi, 20150129
	include_once(ROOT_PATH . 'includes/lib_code.php');
	$order_obj = new Order;
	$res = $order_obj->add_bx();
	$smarty->assign('footer', get_footer());
	$smarty->display('order.dwt');
	exit;
}
elseif ($_REQUEST['act'] == 'select_shipping')
{
    /*------------------------------------------------------ */
    //-- 改变配送方式
    /*------------------------------------------------------ */
    include_once('includes/cls_json.php');
    $json = new JSON;
    $result = array('error' => '', 'content' => '', 'need_insure' => 0);

    /* 取得购物类型 */
    $flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

    /* 获得收货人信息 */
    $consignee = get_consignee($_SESSION['user_id']);

    /* 对商品信息赋值 */

    $cart_goods = cart_goods($flow_type); // 取得商品列表，计算合计

    if (empty($cart_goods))
    {
        $result['error'] = '您的购物车中没有商品！';
    }
    else
    {
        /* 取得购物流程设置 */
        $smarty->assign('config', $_CFG);

        /* 取得订单信息 */
        $order = flow_order_info();

        $order['shipping_id'] = intval($_REQUEST['shipping']);
        $regions = array($consignee['country'], $consignee['province'], $consignee['city'], $consignee['district']);
        $shipping_info = shipping_area_info($order['shipping_id'], $regions);

        /* 计算订单的费用 */
        $total = order_fee($order, $cart_goods, $consignee);
        $smarty->assign('total', $total);

        /* 取得可以得到的积分和代金券 */
        $smarty->assign('total_integral', cart_amount(false, $flow_type) - $total['bonus'] - $total['integral_money']);
        $smarty->assign('total_bonus',    price_format(get_total_bonus(), false));

        /* 团购标志 */
        if ($flow_type == CART_GROUP_BUY_GOODS)
        {
            $smarty->assign('is_group_buy', 1);
        }

        $result['cod_fee']     = $shipping_info['pay_fee'];
        if (strpos($result['cod_fee'], '%') === false)
        {
            $result['cod_fee'] = price_format($result['cod_fee'], false);
        }
        $result['need_insure'] = ($shipping_info['insure'] > 0 && !empty($order['need_insure'])) ? 1 : 0;
        $result['content']     = $smarty->fetch('order_total.dwt');
    }

    echo $json->encode($result);
    exit;
}
elseif ($_REQUEST['act'] == 'select_insure')
{
    /*------------------------------------------------------ */
    //-- 选定/取消配送的保价
    /*------------------------------------------------------ */

    include_once('includes/cls_json.php');
    $json = new JSON;
    $result = array('error' => '', 'content' => '', 'need_insure' => 0);

    /* 取得购物类型 */
    $flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

    /* 获得收货人信息 */
    $consignee = get_consignee($_SESSION['user_id']);

    /* 对商品信息赋值 */
    $cart_goods = cart_goods($flow_type); // 取得商品列表，计算合计

    if (empty($cart_goods))
    {
        $result['error'] = '您的购物车中没有商品！';
    }
    else
    {
        /* 取得购物流程设置 */
        $smarty->assign('config', $_CFG);

        /* 取得订单信息 */
        $order = flow_order_info();

        $order['need_insure'] = intval($_REQUEST['insure']);

        /* 保存 session */
        $_SESSION['flow_order'] = $order;

        /* 计算订单的费用 */
        $total = order_fee($order, $cart_goods, $consignee);
        $smarty->assign('total', $total);

        /* 取得可以得到的积分和代金券 */
        $smarty->assign('total_integral', cart_amount(false, $flow_type) - $total['bonus'] - $total['integral_money']);
        $smarty->assign('total_bonus',    price_format(get_total_bonus(), false));

        /* 团购标志 */
        if ($flow_type == CART_GROUP_BUY_GOODS)
        {
            $smarty->assign('is_group_buy', 1);
        }

        $result['content'] = $smarty->fetch('/order_total.dwt');
    }

    echo $json->encode($result);
    exit;
}
elseif ($_REQUEST['act'] == 'select_pack')
{
    /*------------------------------------------------------ */
    //-- 改变商品包装
    /*------------------------------------------------------ */

    include_once('includes/cls_json.php');
    $json = new JSON;
    $result = array('error' => '', 'content' => '', 'need_insure' => 0);

    /* 取得购物类型 */
    $flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

    /* 获得收货人信息 */
    $consignee = get_consignee($_SESSION['user_id']);

    /* 对商品信息赋值 */
    $cart_goods = cart_goods($flow_type); // 取得商品列表，计算合计

    if (empty($cart_goods) || !check_consignee_info($consignee, $flow_type))
    {
        $result['error'] = '您的购物车中没有商品！';
    }
    else
    {
        /* 取得购物流程设置 */
        $smarty->assign('config', $_CFG);

        /* 取得订单信息 */
        $order = flow_order_info();

        $order['pack_id'] = intval($_REQUEST['pack']);

        /* 保存 session */
        $_SESSION['flow_order'] = $order;

        /* 计算订单的费用 */
        $total = order_fee($order, $cart_goods, $consignee);
        $smarty->assign('total', $total);

        /* 取得可以得到的积分和代金券 */
        $smarty->assign('total_integral', cart_amount(false, $flow_type) - $total['bonus'] - $total['integral_money']);
        $smarty->assign('total_bonus',    price_format(get_total_bonus(), false));

        /* 团购标志 */
        if ($flow_type == CART_GROUP_BUY_GOODS)
        {
            $smarty->assign('is_group_buy', 1);
        }

        $result['content'] = $smarty->fetch('order_total.dwt');
    }

    echo $json->encode($result);
    exit;
}
elseif ($_REQUEST['act'] == 'select_card')
{
    /*------------------------------------------------------ */
    //-- 改变贺卡
    /*------------------------------------------------------ */

    include_once('includes/cls_json.php');
    $json = new JSON;
    $result = array('error' => '', 'content' => '', 'need_insure' => 0);

    /* 取得购物类型 */
    $flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

    /* 获得收货人信息 */
    $consignee = get_consignee($_SESSION['user_id']);

    /* 对商品信息赋值 */
    $cart_goods = cart_goods($flow_type); // 取得商品列表，计算合计

    if (empty($cart_goods) || !check_consignee_info($consignee, $flow_type))
    {
        $result['error'] = '您的购物车中没有商品！';
    }
    else
    {
        /* 取得购物流程设置 */
        $smarty->assign('config', $_CFG);

        /* 取得订单信息 */
        $order = flow_order_info();

        $order['card_id'] = intval($_REQUEST['card']);

        /* 保存 session */
        $_SESSION['flow_order'] = $order;

        /* 计算订单的费用 */
        $total = order_fee($order, $cart_goods, $consignee);
        $smarty->assign('total', $total);

        /* 取得可以得到的积分和代金券 */
        $smarty->assign('total_integral', cart_amount(false, $flow_type) - $order['bonus'] - $total['integral_money']);
        $smarty->assign('total_bonus',    price_format(get_total_bonus(), false));

        /* 团购标志 */
        if ($flow_type == CART_GROUP_BUY_GOODS)
        {
            $smarty->assign('is_group_buy', 1);
        }

        $result['content'] = $smarty->fetch('order_total.dwt');
    }

    echo $json->encode($result);
    exit;
}
elseif ($_REQUEST['act'] == 'select_payment')
{
    /*------------------------------------------------------ */
    //-- 改变支付方式
    /*------------------------------------------------------ */

    include_once('includes/cls_json.php');
    $json = new JSON;
    $result = array('error' => '', 'content' => '', 'need_insure' => 0, 'payment' => 1);

    /* 取得购物类型 */
    $flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

    /* 获得收货人信息 */
    $consignee = get_consignee($_SESSION['user_id']);

    /* 对商品信息赋值 */
    $cart_goods = cart_goods($flow_type); // 取得商品列表，计算合计

//    if (empty($cart_goods))
//    {
//        $result['error'] = '您的购物车中没有商品！';
//    }
//    else
//    {
        /* 取得购物流程设置 */
        $smarty->assign('config', $_CFG);

        /* 取得订单信息 */
        $order = flow_order_info();

        $order['pay_id'] = intval($_REQUEST['payment']);
        $payment_info = payment_info($order['pay_id']);
        $result['pay_code'] = $payment_info['pay_code'];

        /* 保存 session */
        $_SESSION['flow_order'] = $order;

        /* 计算订单的费用 */
        $total = order_fee($order, $cart_goods, $consignee);
        $smarty->assign('total', $total);

        /* 取得可以得到的积分和代金券 */
        $smarty->assign('total_integral', cart_amount(false, $flow_type) - $total['bonus'] - $total['integral_money']);
        $smarty->assign('total_bonus',    price_format(get_total_bonus(), false));

        /* 团购标志 */
        if ($flow_type == CART_GROUP_BUY_GOODS)
        {
            $smarty->assign('is_group_buy', 1);
        }

        $result['content'] = $smarty->fetch('order_total.dwt');
//    }

    echo $json->encode($result);
    exit;
}
elseif ($_REQUEST['act'] == 'change_surplus')
{
    /*------------------------------------------------------ */
    //-- 改变余额
    /*------------------------------------------------------ */
    include_once('includes/cls_json.php');

    $surplus   = floatval($_GET['surplus']);
    $user_info = user_info($_SESSION['user_id']);

    if ($user_info['user_money'] + $user_info['credit_line'] < $surplus)
    {
        $result['error'] = '您的购物车中没有商品！';
    }
    else
    {
        /* 取得购物类型 */
        $flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

        /* 取得购物流程设置 */
        $smarty->assign('config', $_CFG);

        /* 获得收货人信息 */
        $consignee = get_consignee($_SESSION['user_id']);

        /* 对商品信息赋值 */
        $cart_goods = cart_goods($flow_type); // 取得商品列表，计算合计

        if (empty($cart_goods))
        {
            $result['error'] = '您的购物车中没有商品！';
        }
        else
        {
            /* 取得订单信息 */
            $order = flow_order_info();
            $order['surplus'] = $surplus;

            /* 计算订单的费用 */
            $total = order_fee($order, $cart_goods, $consignee);
            $smarty->assign('total', $total);

            /* 团购标志 */
            if ($flow_type == CART_GROUP_BUY_GOODS)
            {
                $smarty->assign('is_group_buy', 1);
            }

            $result['content'] = $smarty->fetch('order_total.dwt');
        }
    }

    $json = new JSON();
    die($json->encode($result));
}
elseif ($_REQUEST['act'] == 'change_bonus')
{
    /*------------------------------------------------------ */
    //-- 改变代金券
    /*------------------------------------------------------ */
    include_once('includes/cls_json.php');
    $result = array('error' => '', 'content' => '');

    /* 取得购物类型 */
    $flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

    /* 获得收货人信息 */
    $consignee = get_consignee($_SESSION['user_id']);

    /* 对商品信息赋值 */
    $cart_goods = cart_goods($flow_type); // 取得商品列表，计算合计

    if (empty($cart_goods) || !check_consignee_info($consignee, $flow_type))
    {
        $result['error'] = '您的购物车中没有商品！';
    }
    else
    {
        /* 取得购物流程设置 */
        $smarty->assign('config', $_CFG);

        /* 取得订单信息 */
        $order = flow_order_info();

        $bonus = bonus_info(intval($_GET['bonus']));

        if ((!empty($bonus) && $bonus['user_id'] == $_SESSION['user_id']) || $_GET['bonus'] == 0)
        {
            $order['bonus_id'] = intval($_GET['bonus']);
        }
        else
        {
            $order['bonus_id'] = 0;
            $result['error'] = '您选择的代金券并不存在。';
        }

        /* 计算订单的费用 */
        $total = order_fee($order, $cart_goods, $consignee);
        $smarty->assign('total', $total);

        /* 团购标志 */
        if ($flow_type == CART_GROUP_BUY_GOODS)
        {
            $smarty->assign('is_group_buy', 1);
        }

        $result['content'] = $smarty->fetch('order_total.dwt');
    }

    $json = new JSON();
    die($json->encode($result));
}
/* 验证代金券序列号 */
elseif ($_REQUEST['act'] == 'validate_bonus')
{
    $bonus_sn = trim($_REQUEST['bonus_sn']);
    if (is_numeric($bonus_sn))
    {
        $bonus = bonus_info(0, $bonus_sn);
    }
    else
    {
        $bonus = array();
    }

//    if (empty($bonus) || $bonus['user_id'] > 0 || $bonus['order_id'] > 0)
//    {
//        die($_LANG['bonus_sn_error']);
//    }
//    if ($bonus['min_goods_amount'] > cart_amount())
//    {
//        die(sprintf($_LANG['bonus_min_amount_error'], price_format($bonus['min_goods_amount'], false)));
//    }
//    die(sprintf($_LANG['bonus_is_ok'], price_format($bonus['type_money'], false)));
    $bonus_kill = price_format($bonus['type_money'], false);

    include_once('includes/cls_json.php');
    $result = array('error' => '', 'content' => '');

    /* 取得购物类型 */
    $flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

    /* 获得收货人信息 */
    $consignee = get_consignee($_SESSION['user_id']);

    /* 对商品信息赋值 */
    $cart_goods = cart_goods($flow_type); // 取得商品列表，计算合计

    if (empty($cart_goods) || !check_consignee_info($consignee, $flow_type))
    {
        $result['error'] = '您的购物车中没有商品！';
    }
    else
    {
        /* 取得购物流程设置 */
        $smarty->assign('config', $_CFG);

        /* 取得订单信息 */
        $order = flow_order_info();


        if (((!empty($bonus) && $bonus['user_id'] == $_SESSION['user_id']) || ($bonus['type_money'] > 0 && empty($bonus['user_id']))) && $bonus['order_id'] <= 0)
        {
            //$order['bonus_kill'] = $bonus['type_money'];
            $now = time();
            if ($now > $bonus['use_end_date'])
            {
                $order['bonus_id'] = '';
                $result['error']='该代金券已经过了使用期！';
            }
            else
            {
                $order['bonus_id'] = $bonus['bonus_id'];
                $order['bonus_sn'] = $bonus_sn;
            }
        }
        else
        {
            //$order['bonus_kill'] = 0;
            $order['bonus_id'] = '';
            $result['error'] = '您选择的代金券并不存在。';
        }

        /* 计算订单的费用 */
        $total = order_fee($order, $cart_goods, $consignee);

        if($total['goods_price']<$bonus['min_goods_amount'])
        {
         $order['bonus_id'] = '';
         /* 重新计算订单 */
         $total = order_fee($order, $cart_goods, $consignee);
         $result['error'] = sprintf('订单商品金额没有达到使用该代金券的最低金额 %s', price_format($bonus['min_goods_amount'], false));
        }

        $smarty->assign('total', $total);

        /* 团购标志 */
        if ($flow_type == CART_GROUP_BUY_GOODS)
        {
            $smarty->assign('is_group_buy', 1);
        }

        $result['content'] = $smarty->fetch('order_total.dwt');
    }
    $json = new JSON();
    die($json->encode($result));
}
elseif ($_REQUEST['act'] == 'change_integral')
{
    /*------------------------------------------------------ */
    //-- 改变积分
    /*------------------------------------------------------ */
    include_once('includes/cls_json.php');

    $points    = floatval($_GET['points']);
    $user_info = user_info($_SESSION['user_id']);

    /* 取得订单信息 */
    $order = flow_order_info();

    $flow_points = flow_available_points();  // 该订单允许使用的积分
    $user_points = $user_info['pay_points']; // 用户的积分总数

    if ($points > $user_points)
    {
        $result['error'] = '您使用的积分不能超过您现有的积分。';
    }
    elseif ($points > $flow_points)
    {
        $result['error'] = sprintf("您使用的积分不能超过%d", $flow_points);
    }
    else
    {
        /* 取得购物类型 */
        $flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

        $order['integral'] = $points;

        /* 获得收货人信息 */
        $consignee = get_consignee($_SESSION['user_id']);

        /* 对商品信息赋值 */
        $cart_goods = cart_goods($flow_type); // 取得商品列表，计算合计

        if (empty($cart_goods) || !check_consignee_info($consignee, $flow_type))
        {
            $result['error'] = '您的购物车中没有商品！';
        }
        else
        {
            /* 计算订单的费用 */
            $total = order_fee($order, $cart_goods, $consignee);
            $smarty->assign('total',  $total);
            $smarty->assign('config', $_CFG);

            /* 团购标志 */
            if ($flow_type == CART_GROUP_BUY_GOODS)
            {
                $smarty->assign('is_group_buy', 1);
            }

            $result['content'] = $smarty->fetch('order_total.dwt');
            $result['error'] = '';
        }
    }

    $json = new JSON();
    die($json->encode($result));
}
elseif ($_REQUEST['act'] == 'change_needinv')
{
    /*------------------------------------------------------ */
    //-- 改变发票的设置
    /*------------------------------------------------------ */
    include_once('includes/cls_json.php');
    $result = array('error' => '', 'content' => '');
    $json = new JSON();
    $_GET['inv_type'] = !empty($_GET['inv_type']) ? json_str_iconv(urldecode($_GET['inv_type'])) : '';
    $_GET['invPayee'] = !empty($_GET['invPayee']) ? json_str_iconv(urldecode($_GET['invPayee'])) : '';
    $_GET['inv_content'] = !empty($_GET['inv_content']) ? json_str_iconv(urldecode($_GET['inv_content'])) : '';

    /* 取得购物类型 */
    $flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;

    /* 获得收货人信息 */
    $consignee = get_consignee($_SESSION['user_id']);

    /* 对商品信息赋值 */
    $cart_goods = cart_goods($flow_type); // 取得商品列表，计算合计

    if (empty($cart_goods) || !check_consignee_info($consignee, $flow_type))
    {
        $result['error'] = '您的购物车中没有商品！';
        die($json->encode($result));
    }
    else
    {
        /* 取得购物流程设置 */
        $smarty->assign('config', $_CFG);

        /* 取得订单信息 */
        $order = flow_order_info();

        if (isset($_GET['need_inv']) && intval($_GET['need_inv']) == 1)
        {
            $order['need_inv']    = 1;
            $order['inv_type']    = trim(stripslashes($_GET['inv_type']));
            $order['inv_payee']   = trim(stripslashes($_GET['inv_payee']));
            $order['inv_content'] = trim(stripslashes($_GET['inv_content']));
        }
        else
        {
            $order['need_inv']    = 0;
            $order['inv_type']    = '';
            $order['inv_payee']   = '';
            $order['inv_content'] = '';
        }

        /* 计算订单的费用 */
        $total = order_fee($order, $cart_goods, $consignee);
        $smarty->assign('total', $total);

        /* 团购标志 */
        if ($flow_type == CART_GROUP_BUY_GOODS)
        {
            $smarty->assign('is_group_buy', 1);
        }

        die($smarty->fetch('order_total.dwt'));
    }
}
elseif($_REQUEST['act'] == 'done')
{
	ss_log("order done");
	$user_id = $_SESSION['user_id'];
	
	$goods_id = intval($_REQUEST['goods_id']);//好像在post参数中
	
	//add yes123 2015-02-06 如果是活动产品，判断是否
/*	include_once(ROOT_PATH . 'includes/hongdong_function.php');
	$data = check_legal($user_id,$goods_id);
	if($data['code']==10){
		 echo "<script language='javascript'>alter(".$data['msg'].");window.history.back(-1);</script>";exit;
	}*/
	
	$ROOT_PATH__= str_replace ( 'order.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
    $cart_insure_id = intval($_REQUEST['cart_insure_id']);

    
	ss_log("flow done!，cart_insure_id： ".$cart_insure_id);
	
	if(!$cart_insure_id)
	{
		ss_log("批量投保单不能为空!");
        echo "<script language='javascript'>alter(投保单不能为空!);window.history.back(-1);</script>";exit;
	}
	
	if(@$_GET['auto_done'])
	{
		ss_log("step done , skip to auto_done");
	}
	/*------------------------------------------------------ */
	//-- 完成所有订单操作，提交到数据库
	/*------------------------------------------------------ */

    include_once('includes/lib_clips.php');
    include_once('includes/lib_code.php');
	include_once(ROOT_PATH . 'includes/class/Order.class.php');
	
	$order_obj = new Order;
	$res = $order_obj->done();
	
	//start add by wangcya, 20150129
	if($res)
	{
	
	}
	else
	{
		//$tips = "订单提交失败!";
	}
	//end add by wangcya, 20150129
	
	//////////////////////////////////////////////////////////////
    if ($_SESSION['user_id'] > 0)
    {
        $smarty->assign('user_name', $_SESSION['user_name']);
    }
    
    $smarty->assign('footer', get_footer());
    $smarty->assign('tips', $tips);
    $smarty->assign('module_title', "完成订单");
    $smarty->display('order_done.dwt');
    
    exit;

}
//start add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能
elseif($_REQUEST['act'] == 'order_lise_meeting')
{
	ss_log("into order order_lise_meeting");
	//相当于pc端的add_bx
	include_once(ROOT_PATH . 'includes/class/MeetingActive.class.php');
	$ObjMeeting = new MeetingActive;
	$res = $ObjMeeting->add_bx_meeting();
	$smarty->assign('footer', get_footer());
	$smarty->display('order_meeting.dwt');
	exit;
}
elseif($_REQUEST['act'] == 'done_meeting')
{
	$ROOT_PATH__= str_replace ( 'order.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
	$cart_insure_id = intval($_POST['cart_insure_id']);


	ss_log("into done_meeting!，cart_insure_id： ".$cart_insure_id);

	if(!$cart_insure_id)
	{
		ss_log("批量投保单不能为空!");
		echo "<script language='javascript'>alter(投保单不能为空!);window.history.back(-1);</script>";
	}

	if(@$_GET['auto_done'])
	{
		ss_log("step done , skip to auto_done");
	}
	/*------------------------------------------------------ */
	//-- 完成所有订单操作，提交到数据库
	/*------------------------------------------------------ */

	include_once('includes/lib_clips.php');
	
	//start add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能
	include_once(ROOT_PATH . 'includes/class/MeetingActive.class.php');
	$ObjMeeting = new MeetingActive;
	
	$res = $ObjMeeting->done_meeting();

	//start add by wangcya, 20150129
	if($res)
	{

	}
	else
	{
		//$tips = "订单提交失败!";
	}
	//end add by wangcya, 20150129

	//////////////////////////////////////////////////////////////
	if ($_SESSION['user_id'] > 0)
	{
		$smarty->assign('user_name', $_SESSION['user_name']);
	}

	$smarty->assign('footer', get_footer());
	$smarty->assign('tips', $tips);
	$smarty->display('order_done.dwt');


	exit;

}
//end add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能

function flow_available_points()
{
	$sql = "SELECT SUM(g.integral * c.goods_number) ".
			"FROM " . $GLOBALS['ecs']->table('cart') . " AS c, " . $GLOBALS['ecs']->table('goods') . " AS g " .
			"WHERE c.session_id = '" . SESS_ID . "' AND c.goods_id = g.goods_id AND c.is_gift = 0 AND g.integral > 0 " .
			"AND c.rec_type = '" . CART_GENERAL_GOODS . "'";

	$val = intval($GLOBALS['db']->getOne($sql));

	return integral_of_value($val);
}

/**
 * 检查订单中商品库存
 *
 * @access  public
 * @param   array   $arr
 *
 * @return  void
 */
function flow_cart_stock($arr)
{
	foreach ($arr AS $key => $val)
	{
		$val = intval(make_semiangle($val));
		if ($val <= 0)
		{
			continue;
		}

		$sql = "SELECT `goods_id`, `goods_attr_id`, `extension_code` FROM" .$GLOBALS['ecs']->table('cart').
			   " WHERE rec_id='$key' AND session_id='" . SESS_ID . "'";
		$goods = $GLOBALS['db']->getRow($sql);

		$sql = "SELECT g.goods_name, g.goods_number, c.product_id ".
				"FROM " .$GLOBALS['ecs']->table('goods'). " AS g, ".
					$GLOBALS['ecs']->table('cart'). " AS c ".
				"WHERE g.goods_id = c.goods_id AND c.rec_id = '$key'";
		$row = $GLOBALS['db']->getRow($sql);

		//系统启用了库存，检查输入的商品数量是否有效
		if (intval($GLOBALS['_CFG']['use_storage']) > 0 && $goods['extension_code'] != 'package_buy')
		{
			if ($row['goods_number'] < $val)
			{
				show_message(sprintf($GLOBALS['_LANG']['stock_insufficiency'], $row['goods_name'],
				$row['goods_number'], $row['goods_number']));
				exit;
			}

			/* 是货品 */
			$row['product_id'] = trim($row['product_id']);
			if (!empty($row['product_id']))
			{
				$sql = "SELECT product_number FROM " .$GLOBALS['ecs']->table('products'). " WHERE goods_id = '" . $goods['goods_id'] . "' AND product_id = '" . $row['product_id'] . "'";
				$product_number = $GLOBALS['db']->getOne($sql);
				if ($product_number < $val)
				{
					show_message(sprintf($GLOBALS['_LANG']['stock_insufficiency'], $row['goods_name'],
					$row['goods_number'], $row['goods_number']));
					exit;
				}
			}
		}
		elseif (intval($GLOBALS['_CFG']['use_storage']) > 0 && $goods['extension_code'] == 'package_buy')
		{
			if (judge_package_stock($goods['goods_id'], $val))
			{
				show_message($GLOBALS['_LANG']['package_stock_insufficiency']);
				exit;
			}
		}
	}

}
?>