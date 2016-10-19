<?php

/**
 * ECSHOP 购物流程函数库
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: lib_order.php 17217 2011-01-19 06:29:08Z liubo $
 */
if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

include_once(ROOT_PATH . 'baoxian/source/function_debug.php');
include_once(ROOT_PATH . 'baoxian/source/function_baoxian.php');// add by wangcya, 20141204,这句话不能取消掉，否则无法投保了！
include_once(ROOT_PATH . 'baoxian/source/my_const.php');
include_once(ROOT_PATH . 'includes/lib_payment.php');


/**
 * 处理序列化的支付、配送的配置参数
 * 返回一个以name为索引的数组
 *
 * @access  public
 * @param   string       $cfg
 * @return  void
 */
function unserialize_config($cfg)
{
    if (is_string($cfg) && ($arr = unserialize($cfg)) !== false)
    {
        $config = array();

        foreach ($arr AS $key => $val)
        {
            $config[$val['name']] = $val['value'];
        }

        return $config;
    }
    else
    {
        return false;
    }
}
/**
 * 取得已安装的配送方式
 * @return  array   已安装的配送方式
 */
function shipping_list()
{
    $sql = 'SELECT shipping_id, shipping_name ' .
            'FROM ' . $GLOBALS['ecs']->table('shipping') .
            ' WHERE enabled = 1';

    return $GLOBALS['db']->getAll($sql);
}

/**
 * 取得配送方式信息
 * @param   int     $shipping_id    配送方式id
 * @return  array   配送方式信息
 */
function shipping_info($shipping_id)
{
    $sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('shipping') .
            " WHERE shipping_id = '$shipping_id' " .
            'AND enabled = 1';

    return $GLOBALS['db']->getRow($sql);
}

/**
 * 取得可用的配送方式列表
 * @param   array   $region_id_list     收货人地区id数组（包括国家、省、市、区）
 * @return  array   配送方式数组
 */
function available_shipping_list($region_id_list)
{
    $sql = 'SELECT s.shipping_id, s.shipping_code, s.shipping_name, ' .
                's.shipping_desc, s.insure, s.support_cod, a.configure ' .
            'FROM ' . $GLOBALS['ecs']->table('shipping') . ' AS s, ' .
                $GLOBALS['ecs']->table('shipping_area') . ' AS a, ' .
                $GLOBALS['ecs']->table('area_region') . ' AS r ' .
            'WHERE r.region_id ' . db_create_in($region_id_list) .
            ' AND r.shipping_area_id = a.shipping_area_id AND a.shipping_id = s.shipping_id AND s.enabled = 1 ORDER BY s.shipping_order';

    return $GLOBALS['db']->getAll($sql);
}

/**
 * 取得某配送方式对应于某收货地址的区域信息
 * @param   int     $shipping_id        配送方式id
 * @param   array   $region_id_list     收货人地区id数组
 * @return  array   配送区域信息（config 对应着反序列化的 configure）
 */
function shipping_area_info($shipping_id, $region_id_list)
{
    $sql = 'SELECT s.shipping_code, s.shipping_name, ' .
                's.shipping_desc, s.insure, s.support_cod, a.configure ' .
            'FROM ' . $GLOBALS['ecs']->table('shipping') . ' AS s, ' .
                $GLOBALS['ecs']->table('shipping_area') . ' AS a, ' .
                $GLOBALS['ecs']->table('area_region') . ' AS r ' .
            "WHERE s.shipping_id = '$shipping_id' " .
            'AND r.region_id ' . db_create_in($region_id_list) .
            ' AND r.shipping_area_id = a.shipping_area_id AND a.shipping_id = s.shipping_id AND s.enabled = 1';
    $row = $GLOBALS['db']->getRow($sql);

    if (!empty($row))
    {
        $shipping_config = unserialize_config($row['configure']);
        if (isset($shipping_config['pay_fee']))
        {
            if (strpos($shipping_config['pay_fee'], '%') !== false)
            {
                $row['pay_fee'] = floatval($shipping_config['pay_fee']) . '%';
            }
            else
            {
                 $row['pay_fee'] = floatval($shipping_config['pay_fee']);
            }
        }
        else
        {
            $row['pay_fee'] = 0.00;
        }
    }

    return $row;
}

/**
 * 计算运费
 * @param   string  $shipping_code      配送方式代码
 * @param   mix     $shipping_config    配送方式配置信息
 * @param   float   $goods_weight       商品重量
 * @param   float   $goods_amount       商品金额
 * @param   float   $goods_number       商品数量
 * @return  float   运费
 */
function shipping_fee($shipping_code, $shipping_config, $goods_weight, $goods_amount, $goods_number='')
{
    if (!is_array($shipping_config))
    {
        $shipping_config = unserialize($shipping_config);
    }

    $filename = ROOT_PATH . 'includes/modules/shipping/' . $shipping_code . '.php';
    if (file_exists($filename))
    {
        include_once($filename);

        $obj = new $shipping_code($shipping_config);

        return $obj->calculate($goods_weight, $goods_amount, $goods_number);
    }
    else
    {
        return 0;
    }
}

/**
 * 获取指定配送的保价费用
 *
 * @access  public
 * @param   string      $shipping_code  配送方式的code
 * @param   float       $goods_amount   保价金额
 * @param   mix         $insure         保价比例
 * @return  float
 */
function shipping_insure_fee($shipping_code, $goods_amount, $insure)
{
    if (strpos($insure, '%') === false)
    {
        /* 如果保价费用不是百分比则直接返回该数值 */
        return floatval($insure);
    }
    else
    {
        $path = ROOT_PATH . 'includes/modules/shipping/' . $shipping_code . '.php';

        if (file_exists($path))
        {
            include_once($path);

            $shipping = new $shipping_code;
            $insure   = floatval($insure) / 100;

            if (method_exists($shipping, 'calculate_insure'))
            {
                return $shipping->calculate_insure($goods_amount, $insure);
            }
            else
            {
                return ceil($goods_amount * $insure);
            }
        }
        else
        {
            return false;
        }
    }
}

/**
 * 取得已安装的支付方式列表
 * @return  array   已安装的配送方式列表
 */
function payment_list()
{
    $sql = 'SELECT pay_id, pay_name,pay_code ' .
            'FROM ' . $GLOBALS['ecs']->table('payment') .
            ' WHERE enabled = 1';

    return $GLOBALS['db']->getAll($sql);
}

/**
 * 取得支付方式信息
 * @param   int     $pay_id     支付方式id
 * @return  array   支付方式信息
 */
function payment_info($pay_id)
{
    $sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('payment') .
            " WHERE pay_id = '$pay_id' AND enabled = 1";

    return $GLOBALS['db']->getRow($sql);
}

/**
 * 获得订单需要支付的支付费用
 *
 * @access  public
 * @param   integer $payment_id
 * @param   float   $order_amount
 * @param   mix     $cod_fee
 * @return  float
 */
function pay_fee($payment_id, $order_amount, $cod_fee=null)
{
    $pay_fee = 0;
    $payment = payment_info($payment_id);
    $rate    = ($payment['is_cod'] && !is_null($cod_fee)) ? $cod_fee : $payment['pay_fee'];

    if (strpos($rate, '%') !== false)
    {
        /* 支付费用是一个比例 */
        $val     = floatval($rate) / 100;
        $pay_fee = $val > 0 ? $order_amount * $val /(1- $val) : 0;
    }
    else
    {
        $pay_fee = floatval($rate);
    }

    return round($pay_fee, 2);
}

/**
 * 取得可用的支付方式列表
 * @param   bool    $support_cod        配送方式是否支持货到付款
 * @param   int     $cod_fee            货到付款手续费（当配送方式支持货到付款时才传此参数）
 * @param   int     $is_online          是否支持在线支付
 * @param   int     $is_mobile          手机支付
 * @return  array   配送方式数组
 */
 //mod by zhangxi, 20150128, 为了微信的活动，临时进行修改，增加一个输入参数$is_activity
function available_payment_list($support_cod, $cod_fee = 0, $is_mobile=0, $is_online = false, $is_activity = false)
{
	//add yes123 2015-12-01  如果是渠道，获取渠道自己的支付列表 start
	$user_info = user_info_by_userid($_SESSION['user_id']);
	if($user_info['renk_code']==ORGANIZATION_USER)	
	{
		$institution_id = $user_info['user_id'];
		
	}
	else if($user_info['institution_id'])
	{
		$institution_id = $user_info['institution_id'];
	}
	
	if($institution_id)
	{
		$sql = " SELECT * FROM ".$GLOBALS['ecs']->table('payment_organ')." WHERE user_id='$institution_id'  AND enabled=1 ";
		$res = $GLOBALS['db']->query($sql);
	
	}
	
	//add yes123 2015-12-01  如果是渠道，获取渠道自己的支付列表 end
	
	if(empty($res))
	{
	    $sql = "SELECT pay_id, pay_code, pay_name, pay_fee, pay_desc, pay_config, is_cod " .
	            " FROM " . $GLOBALS['ecs']->table('payment') .
	            " WHERE enabled = 1 AND applied_range!='private' ";
	    if (!$support_cod)
	    {
	        $sql .= 'AND is_cod = 0 '; // 如果不支持货到付款
	    }
	    if ($is_online)
	    {
	        $sql .= "AND is_online = '1' ";
	    }
	    if(empty($is_mobile))
	    {        
	        $sql .= "AND is_mobile <> 1 "; 
	    }
	    
	    $sql .= 'ORDER BY pay_order'; // 排序

	    $res = $GLOBALS['db']->query($sql);
	}
	
	
    $pay_list = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        if ($row['is_cod'] == '1')
        {
            $row['pay_fee'] = $cod_fee;
        }

        $row['format_pay_fee'] = strpos($row['pay_fee'], '%') !== false ? $row['pay_fee'] :
        price_format($row['pay_fee'], false);
        $modules[] = $row;
    }

    include_once(ROOT_PATH.'includes/lib_compositor.php');

    if(isset($modules))
    {
        return $modules;
    }
}

/**
 * 取得包装列表
 * @return  array   包装列表
 */
function pack_list()
{
    $sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('pack');
    $res = $GLOBALS['db']->query($sql);

    $list = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $row['format_pack_fee'] = price_format($row['pack_fee'], false);
        $row['format_free_money'] = price_format($row['free_money'], false);
        $list[] = $row;
    }

    return $list;
}

/**
 * 取得包装信息
 * @param   int     $pack_id    包装id
 * @return  array   包装信息
 */
function pack_info($pack_id)
{
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('pack') .
            " WHERE pack_id = '$pack_id'";

    return $GLOBALS['db']->getRow($sql);
}

/**
 * 根据订单中的商品总额来获得包装的费用
 *
 * @access  public
 * @param   integer $pack_id
 * @param   float   $goods_amount
 * @return  float
 */
function pack_fee($pack_id, $goods_amount)
{
    $pack = pack_info($pack_id);

    $val = (floatval($pack['free_money']) <= $goods_amount && $pack['free_money'] > 0) ? 0 : floatval($pack['pack_fee']);

    return $val;
}

/**
 * 取得贺卡列表
 * @return  array   贺卡列表
 */
function card_list()
{
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('card');
    $res = $GLOBALS['db']->query($sql);

    $list = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $row['format_card_fee'] = price_format($row['card_fee'], false);
        $row['format_free_money'] = price_format($row['free_money'], false);
        $list[] = $row;
    }

    return $list;
}

/**
 * 取得贺卡信息
 * @param   int     $card_id    贺卡id
 * @return  array   贺卡信息
 */
function card_info($card_id)
{
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('card') .
            " WHERE card_id = '$card_id'";

    return $GLOBALS['db']->getRow($sql);
}

/**
 * 根据订单中商品总额获得需要支付的贺卡费用
 *
 * @access  public
 * @param   integer $card_id
 * @param   float   $goods_amount
 * @return  float
 */
function card_fee($card_id, $goods_amount)
{
    $card = card_info($card_id);

    return ($card['free_money'] <= $goods_amount && $card['free_money'] > 0) ? 0 : $card['card_fee'];
}

/**
 * 取得订单信息
 * @param   int     $order_id   订单id（如果order_id > 0 就按id查，否则按sn查）
 * @param   string  $order_sn   订单号
 * @return  array   订单信息（金额都有相应格式化的字段，前缀是formated_）
 */
function order_info($order_id, $order_sn = '')
{
    /* 计算订单各种费用之和的语句 */
    $total_fee = " (goods_amount - discount + tax + shipping_fee + insure_fee + pay_fee + pack_fee + card_fee) AS total_fee ";
    $order_id = intval($order_id);
    if ($order_id > 0)
    {
        $sql = "SELECT *, " . $total_fee . " FROM " . $GLOBALS['ecs']->table('order_info') .
                " WHERE order_id = '$order_id'";
    }
    else
    {
        $sql = "SELECT *, " . $total_fee . "  FROM " . $GLOBALS['ecs']->table('order_info') .
                " WHERE order_sn = '$order_sn'";
    }

    $order = $GLOBALS['db']->getRow($sql);

    /* 格式化金额字段 */
    if ($order)
    {
        $order['formated_goods_amount']   = price_format($order['goods_amount'], false);
        $order['formated_discount']       = price_format($order['discount'], false);
        $order['formated_tax']            = price_format($order['tax'], false);
        $order['formated_shipping_fee']   = price_format($order['shipping_fee'], false);
        $order['formated_insure_fee']     = price_format($order['insure_fee'], false);
        $order['formated_pay_fee']        = price_format($order['pay_fee'], false);
        $order['formated_pack_fee']       = price_format($order['pack_fee'], false);
        $order['formated_card_fee']       = price_format($order['card_fee'], false);
        $order['formated_total_fee']      = price_format($order['total_fee'], false);
        $order['formated_money_paid']     = price_format($order['money_paid'], false);
        $order['formated_bonus']          = price_format($order['bonus'], false);
        $order['formated_integral_money'] = price_format($order['integral_money'], false);
        $order['formated_surplus']        = price_format($order['surplus'], false);
        $order['formated_service_money']  = price_format($order['service_money'], false);
        $order['formated_order_amount']   = price_format(abs($order['order_amount']), false);
        //modify yes123 2015-01-25 时间格式化
        //$order['formated_add_time']       = local_date($GLOBALS['_CFG']['time_format'], $order['add_time']);
        $order['formated_add_time'] =  date("Y-m-d H:i:s", $order['add_time']);
    }

    return $order;
}

/**
 * 判断订单是否已完成
 * @param   array   $order  订单信息
 * @return  bool
 */
function order_finished($order)
{
    return $order['order_status']  == OS_CONFIRMED &&
        ($order['shipping_status'] == SS_SHIPPED || $order['shipping_status'] == SS_RECEIVED) &&
        ($order['pay_status']      == PS_PAYED   || $order['pay_status'] == PS_PAYING);
}

/**
 * 取得订单商品
 * @param   int     $order_id   订单id
 * @return  array   订单商品数组
 */
function order_goods($order_id,$page_size=0)
{
    $sql = "SELECT rec_id, goods_id, goods_name, goods_sn, market_price, goods_number, " .
            "goods_price, goods_attr, is_real, parent_id, is_gift, " .
            "goods_price * goods_number AS subtotal, extension_code " .
            "FROM " . $GLOBALS['ecs']->table('order_goods') .
            " WHERE order_id = '$order_id'";
	
	if($page_size)
	{
		$sql.=" LIMIT 0,$page_size ";
	}
    $res = $GLOBALS['db']->query($sql);

    $goods_list = array();//add by wangcya, 20141217
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        if ($row['extension_code'] == 'package_buy')
        {
            $row['package_goods_list'] = get_package_goods($row['goods_id']);
        }
        $goods_list[] = $row;
    }

    //return $GLOBALS['db']->getAll($sql);
    return $goods_list;
}

/**
 * 取得订单总金额
 * @param   int     $order_id   订单id
 * @param   bool    $include_gift   是否包括赠品
 * @return  float   订单总金额
 */
function order_amount($order_id, $include_gift = true)
{
    $sql = "SELECT SUM(goods_price * goods_number) " .
            "FROM " . $GLOBALS['ecs']->table('order_goods') .
            " WHERE order_id = '$order_id'";
    if (!$include_gift)
    {
        $sql .= " AND is_gift = 0";
    }

    return floatval($GLOBALS['db']->getOne($sql));
}

/**
 * 取得某订单商品总重量和总金额（对应 cart_weight_price）
 * @param   int     $order_id   订单id
 * @return  array   ('weight' => **, 'amount' => **, 'formated_weight' => **)
 */
function order_weight_price($order_id)
{
    $sql = "SELECT SUM(g.goods_weight * o.goods_number) AS weight, " .
                "SUM(o.goods_price * o.goods_number) AS amount ," .
                "SUM(o.goods_number) AS number " .
            "FROM " . $GLOBALS['ecs']->table('order_goods') . " AS o, " .
                $GLOBALS['ecs']->table('goods') . " AS g " .
            "WHERE o.order_id = '$order_id' " .
            "AND o.goods_id = g.goods_id";

    $row = $GLOBALS['db']->getRow($sql);
    $row['weight'] = floatval($row['weight']);
    $row['amount'] = floatval($row['amount']);
    $row['number'] = intval($row['number']);

    /* 格式化重量 */
    $row['formated_weight'] = formated_weight($row['weight']);

    return $row;
}

/**
 * 获得订单中的费用信息
 *
 * @access  public
 * @param   array   $order
 * @param   array   $goods
 * @param   array   $consignee
 * @param   bool    $is_gb_deposit  是否团购保证金（如果是，应付款金额只计算商品总额和支付费用，可以获得的积分取 $gift_integral）
 * @return  array
 */
function order_fee($order, $goods, $consignee)
{
    /* 初始化订单的扩展code */
    if (!isset($order['extension_code']))
    {
        $order['extension_code'] = '';
    }

    if ($order['extension_code'] == 'group_buy')
    {
        $group_buy = group_buy_info($order['extension_id']);
    }

    $total  = array('real_goods_count' => 0,
                    'gift_amount'      => 0,
                    'goods_price'      => 0,
                    'market_price'     => 0,
                    'discount'         => 0,
                    'pack_fee'         => 0,
                    'card_fee'         => 0,
                    'shipping_fee'     => 0,
                    'shipping_insure'  => 0,
                    'integral_money'   => 0,
                    'bonus'            => 0,
                    'surplus'          => 0,
                    'cod_fee'          => 0,
                    'pay_fee'          => 0,
                    'tax'              => 0);
    $weight = 0;

    /* 商品总价 */
    foreach ($goods AS $val)
    {
        /* 统计实体商品的个数 */
        if ($val['is_real'])
        {
            $total['real_goods_count']++;
        }

        $total['goods_price']  += $val['goods_price'] * $val['goods_number'];
        $total['market_price'] += $val['market_price'] * $val['goods_number'];
    }

    $total['saving']    = $total['market_price'] - $total['goods_price'];
    $total['save_rate'] = $total['market_price'] ? round($total['saving'] * 100 / $total['market_price']) . '%' : 0;

    $total['goods_price_formated']  = price_format($total['goods_price'], false);
    $total['market_price_formated'] = price_format($total['market_price'], false);
    $total['saving_formated']       = price_format($total['saving'], false);

    /* 折扣 */
    if ($order['extension_code'] != 'group_buy')
    {
        $discount = compute_discount();
        $total['discount'] = $discount['discount'];
        if ($total['discount'] > $total['goods_price'])
        {
            $total['discount'] = $total['goods_price'];
        }
    }
    $total['discount_formated'] = price_format($total['discount'], false);

    /* 税额 */
    if (!empty($order['need_inv']) && $order['inv_type'] != '')
    {
        /* 查税率 */
        $rate = 0;
        foreach ($GLOBALS['_CFG']['invoice_type']['type'] as $key => $type)
        {
            if ($type == $order['inv_type'])
            {
                $rate = floatval($GLOBALS['_CFG']['invoice_type']['rate'][$key]) / 100;
                break;
            }
        }
        if ($rate > 0)
        {
            $total['tax'] = $rate * $total['goods_price'];
        }
    }
    $total['tax_formated'] = price_format($total['tax'], false);

    /* 包装费用 */
    if (!empty($order['pack_id']))
    {
        $total['pack_fee']      = pack_fee($order['pack_id'], $total['goods_price']);
    }
    $total['pack_fee_formated'] = price_format($total['pack_fee'], false);

    /* 贺卡费用 */
    if (!empty($order['card_id']))
    {
        $total['card_fee']      = card_fee($order['card_id'], $total['goods_price']);
    }
    $total['card_fee_formated'] = price_format($total['card_fee'], false);

    /* 代金券 */

    if (!empty($order['bonus_id']))
    {
        $bonus          = bonus_info($order['bonus_id']);
        $total['bonus'] = $bonus['type_money'];
    }
    $total['bonus_formated'] = price_format($total['bonus'], false);

    /* 线下代金券 */
     if (!empty($order['bonus_kill']))
    {
        $bonus          = bonus_info(0,$order['bonus_kill']);
        $total['bonus_kill'] = $order['bonus_kill'];
        $total['bonus_kill_formated'] = price_format($total['bonus_kill'], false);
    }



    /* 配送费用 */
    $shipping_cod_fee = NULL;

    if ($order['shipping_id'] > 0 && $total['real_goods_count'] > 0)
    {
        $region['country']  = $consignee['country'];
        $region['province'] = $consignee['province'];
        $region['city']     = $consignee['city'];
        $region['district'] = $consignee['district'];
        $shipping_info = shipping_area_info($order['shipping_id'], $region);

        if (!empty($shipping_info))
        {
            if ($order['extension_code'] == 'group_buy')
            {
                $weight_price = cart_weight_price(CART_GROUP_BUY_GOODS);
            }
            else
            {
                $weight_price = cart_weight_price();
            }

            // 查看购物车中是否全为免运费商品，若是则把运费赋为零
            $sql = 'SELECT count(*) FROM ' . $GLOBALS['ecs']->table('cart') . " WHERE  `session_id` = '" . SESS_ID. "' AND `extension_code` != 'package_buy' AND `is_shipping` = 0";
            $shipping_count = $GLOBALS['db']->getOne($sql);

            $total['shipping_fee'] = ($shipping_count == 0 AND $weight_price['free_shipping'] == 1) ?0 :  shipping_fee($shipping_info['shipping_code'],$shipping_info['configure'], $weight_price['weight'], $total['goods_price'], $weight_price['number']);

            if (!empty($order['need_insure']) && $shipping_info['insure'] > 0)
            {
                $total['shipping_insure'] = shipping_insure_fee($shipping_info['shipping_code'],
                    $total['goods_price'], $shipping_info['insure']);
            }
            else
            {
                $total['shipping_insure'] = 0;
            }

            if ($shipping_info['support_cod'])
            {
                $shipping_cod_fee = $shipping_info['pay_fee'];
            }
        }
    }

    $total['shipping_fee_formated']    = price_format($total['shipping_fee'], false);
    $total['shipping_insure_formated'] = price_format($total['shipping_insure'], false);

    // 购物车中的商品能享受代金券支付的总额
    $bonus_amount = compute_discount_amount();
    // 代金券和积分最多能支付的金额为商品总额
    $max_amount = $total['goods_price'] == 0 ? $total['goods_price'] : $total['goods_price'] - $bonus_amount;

    /* 计算订单总额 */
    if ($order['extension_code'] == 'group_buy' && $group_buy['deposit'] > 0)
    {
        $total['amount'] = $total['goods_price'];
    }
    else
    {
        $total['amount'] = $total['goods_price'] - $total['discount'] + $total['tax'] + $total['pack_fee'] + $total['card_fee'] +
            $total['shipping_fee'] + $total['shipping_insure'] + $total['cod_fee'];

        // 减去代金券金额
        $use_bonus        = min($total['bonus'], $max_amount); // 实际减去的代金券金额
        if(isset($total['bonus_kill']))
        {
            $use_bonus_kill   = min($total['bonus_kill'], $max_amount);
            $total['amount'] -=  $price = number_format($total['bonus_kill'], 2, '.', ''); // 还需要支付的订单金额
        }

        $total['bonus']   = $use_bonus;
        $total['bonus_formated'] = price_format($total['bonus'], false);

        $total['amount'] -= $use_bonus; // 还需要支付的订单金额
        $max_amount      -= $use_bonus; // 积分最多还能支付的金额

    }

    /* 余额 */
    $order['surplus'] = $order['surplus'] > 0 ? $order['surplus'] : 0;
    if ($total['amount'] > 0)
    {
        if (isset($order['surplus']) && $order['surplus'] > $total['amount'])
        {
            $order['surplus'] = $total['amount'];
            $total['amount']  = 0;
        }
        else
        {
            $total['amount'] -= floatval($order['surplus']);
        }
    }
    else
    {
        $order['surplus'] = 0;
        $total['amount']  = 0;
    }
    $total['surplus'] = $order['surplus'];
    $total['surplus_formated'] = price_format($order['surplus'], false);

    /* 积分 */
    $order['integral'] = $order['integral'] > 0 ? $order['integral'] : 0;
    if ($total['amount'] > 0 && $max_amount > 0 && $order['integral'] > 0)
    {
        $integral_money = value_of_integral($order['integral']);

        // 使用积分支付
        $use_integral            = min($total['amount'], $max_amount, $integral_money); // 实际使用积分支付的金额
        $total['amount']        -= $use_integral;
        $total['integral_money'] = $use_integral;
        $order['integral']       = integral_of_value($use_integral);
    }
    else
    {
        $total['integral_money'] = 0;
        $order['integral']       = 0;
    }
    $total['integral'] = $order['integral'];
    $total['integral_formated'] = price_format($total['integral_money'], false);

    /* 保存订单信息 */
    $_SESSION['flow_order'] = $order;

    $se_flow_type = isset($_SESSION['flow_type']) ? $_SESSION['flow_type'] : '';
    
    /* 支付费用 */
    if (!empty($order['pay_id']) && ($total['real_goods_count'] > 0 || $se_flow_type != CART_EXCHANGE_GOODS))
    {
        $total['pay_fee']      = pay_fee($order['pay_id'], $total['amount'], $shipping_cod_fee);
    }

    $total['pay_fee_formated'] = price_format($total['pay_fee'], false);

    $total['amount']           += $total['pay_fee']; // 订单总额累加上支付费用
    $total['amount_formated']  = price_format($total['amount'], false);

    /* 取得可以得到的积分和代金券 */
    if ($order['extension_code'] == 'group_buy')
    {
        $total['will_get_integral'] = $group_buy['gift_integral'];
    }
    elseif ($order['extension_code'] == 'exchange_goods')
    {
        $total['will_get_integral'] = 0;
    }
    else
    {
        $total['will_get_integral'] = get_give_integral($goods);
    }
    $total['will_get_bonus']        = $order['extension_code'] == 'exchange_goods' ? 0 : price_format(get_total_bonus(), false);
    $total['formated_goods_price']  = price_format($total['goods_price'], false);
    $total['formated_market_price'] = price_format($total['market_price'], false);
    $total['formated_saving']       = price_format($total['saving'], false);

    if ($order['extension_code'] == 'exchange_goods')
    {
        $sql = 'SELECT SUM(eg.exchange_integral) '.
               'FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c,' . $GLOBALS['ecs']->table('exchange_goods') . 'AS eg '.
               "WHERE c.goods_id = eg.goods_id AND c.session_id= '" . SESS_ID . "' " .
               "  AND c.rec_type = '" . CART_EXCHANGE_GOODS . "' " .
               '  AND c.is_gift = 0 AND c.goods_id > 0 ' .
               'GROUP BY eg.goods_id';
        $exchange_integral = $GLOBALS['db']->getOne($sql);
        $total['exchange_integral'] = $exchange_integral;
    }

    return $total;
}
/**
 * 计算保险单费用
 * 
 * rqs
 * 
 * 14-7-25 上午11:45
 *  
 */
 //mod by zhangxi, 20150127, 增加输入参数，投保单相关list
function bx_order_fee($order, $total_premium, $consignee, $list_bx_policy=NULL, $gid=0)
{
    /* 初始化订单的扩展code */
    if (!isset($order['extension_code']))
    {
        $order['extension_code'] = '';
    }

    if ($order['extension_code'] == 'group_buy')
    {
        $group_buy = group_buy_info($order['extension_id']);
    }

    ss_log("bx_order_fee total_premium ".$total_premium);
    
    $total  = array('real_goods_count' => 0,
                    'gift_amount'      => 0,
                    'goods_price'      => 0,
                    'market_price'     => 0,
                    'discount'         => 0,
                    'pack_fee'         => 0,
                    'card_fee'         => 0,
                    'shipping_fee'     => 0,
                    'shipping_insure'  => 0,
                    'integral_money'   => 0,
                    'bonus'            => 0,
                    'surplus'          => 0,
                    'cod_fee'          => 0,
                    'pay_fee'          => 0,
                    'tax'              => 0,
                    'service_money'    => 0 //add yes123 2015-07-06金币
                    );
    $weight = 0;
    
    
   	//comment by zhangxi, 20150126, 商品总价
    $total['goods_price']= $total_premium;//$bx_policy[0]['total_premium'];//add by wangcya, 20141104
   
    ss_log("in function bx_order_fee, goods_price set to total_premium: ".$total['goods_price']);
    
    $total['goods_price_formated']  = price_format($total['goods_price'], false );
   
    //added by zhangxi, 20150126, 原来的代码先弄过来，在这个上面改， start
        /* 商品总价 */
//    foreach ($goods AS $val)
//    {
//        /* 统计实体商品的个数 */
//        if ($val['is_real'])
//        {
//            $total['real_goods_count']++;
//        }
//
//        $total['goods_price']  += $val['goods_price'] * $val['goods_number'];
//        $total['market_price'] += $val['market_price'] * $val['goods_number'];
//    }

//    $total['saving']    = $total['market_price'] - $total['goods_price'];
//    $total['save_rate'] = $total['market_price'] ? round($total['saving'] * 100 / $total['market_price']) . '%' : 0;
//
//    $total['goods_price_formated']  = price_format($total['goods_price'], false);
//    $total['market_price_formated'] = price_format($total['market_price'], false);
//    $total['saving_formated']       = price_format($total['saving'], false);


    /* 折扣 */
    //added by zhangxi, 20150126,这个地方我们需要使用
    //是否会影响正常产品流程?
    if ($order['extension_code'] != 'group_buy')
    {
        $discount = compute_discount($list_bx_policy, $gid);
        ss_log(__FUNCTION__.": discount=".$discount['discount']);
        $total['discount'] = $discount['discount'];
        if ($total['discount'] > $total['goods_price'])
        {
            $total['discount'] = $total['goods_price'];
        }
    }
    $total['discount_formated'] = price_format($total['discount'], false);
//
//    /* 税额 */
//    if (!empty($order['need_inv']) && $order['inv_type'] != '')
//    {
//        /* 查税率 */
//        $rate = 0;
//        foreach ($GLOBALS['_CFG']['invoice_type']['type'] as $key => $type)
//        {
//            if ($type == $order['inv_type'])
//            {
//                $rate = floatval($GLOBALS['_CFG']['invoice_type']['rate'][$key]) / 100;
//                break;
//            }
//        }
//        if ($rate > 0)
//        {
//            $total['tax'] = $rate * $total['goods_price'];
//        }
//    }
//    $total['tax_formated'] = price_format($total['tax'], false);

//    /* 包装费用 */
//    if (!empty($order['pack_id']))
//    {
//        $total['pack_fee']      = pack_fee($order['pack_id'], $total['goods_price']);
//    }
//    $total['pack_fee_formated'] = price_format($total['pack_fee'], false);
//
//    /* 贺卡费用 */
//    if (!empty($order['card_id']))
//    {
//        $total['card_fee']      = card_fee($order['card_id'], $total['goods_price']);
//    }
//    $total['card_fee_formated'] = price_format($total['card_fee'], false);
//
//    /* 代金券 */
//
//    if (!empty($order['bonus_id']))
//    {
//        $bonus          = bonus_info($order['bonus_id']);
//        $total['bonus'] = $bonus['type_money'];
//    }
//    $total['bonus_formated'] = price_format($total['bonus'], false);
//
//    /* 线下代金券 */
//     if (!empty($order['bonus_kill']))
//    {
//        $bonus          = bonus_info(0,$order['bonus_kill']);
//        $total['bonus_kill'] = $order['bonus_kill'];
//        $total['bonus_kill_formated'] = price_format($total['bonus_kill'], false);
//    }
//
//
//
//    /* 配送费用 */
//    $shipping_cod_fee = NULL;
//
//    if ($order['shipping_id'] > 0 && $total['real_goods_count'] > 0)
//    {
//        $region['country']  = $consignee['country'];
//        $region['province'] = $consignee['province'];
//        $region['city']     = $consignee['city'];
//        $region['district'] = $consignee['district'];
//        $shipping_info = shipping_area_info($order['shipping_id'], $region);
//
//        if (!empty($shipping_info))
//        {
//            if ($order['extension_code'] == 'group_buy')
//            {
//                $weight_price = cart_weight_price(CART_GROUP_BUY_GOODS);
//            }
//            else
//            {
//                $weight_price = cart_weight_price();
//            }
//
//            // 查看购物车中是否全为免运费商品，若是则把运费赋为零
//            $sql = 'SELECT count(*) FROM ' . $GLOBALS['ecs']->table('cart') . " WHERE  `session_id` = '" . SESS_ID. "' AND `extension_code` != 'package_buy' AND `is_shipping` = 0";
//            $shipping_count = $GLOBALS['db']->getOne($sql);
//
//            $total['shipping_fee'] = ($shipping_count == 0 AND $weight_price['free_shipping'] == 1) ?0 :  shipping_fee($shipping_info['shipping_code'],$shipping_info['configure'], $weight_price['weight'], $total['goods_price'], $weight_price['number']);
//
//            if (!empty($order['need_insure']) && $shipping_info['insure'] > 0)
//            {
//                $total['shipping_insure'] = shipping_insure_fee($shipping_info['shipping_code'],
//                    $total['goods_price'], $shipping_info['insure']);
//            }
//            else
//            {
//                $total['shipping_insure'] = 0;
//            }
//
//            if ($shipping_info['support_cod'])
//            {
//                $shipping_cod_fee = $shipping_info['pay_fee'];
//            }
//        }
//    }
//
//    $total['shipping_fee_formated']    = price_format($total['shipping_fee'], false);
//    $total['shipping_insure_formated'] = price_format($total['shipping_insure'], false);
    //added by zhangxi, 20150126, 原来的代码先弄过来，在这个上面改， end
    ///////////////////////////////////////////////////////////////////////////////////
    

    
    ss_log("in function bx_order_fee, goods_price_formated: ".$total['goods_price_formated']);
    //comment by zhangxi, 20150228, 折扣
    $total['discount_formated'] = price_format($total['discount'], false);

    /* 税额 */
    if (!empty($order['need_inv']) && $order['inv_type'] != '')
    {
        /* 查税率 */
        $rate = 0;
        foreach ($GLOBALS['_CFG']['invoice_type']['type'] as $key => $type)
        {
            if ($type == $order['inv_type'])
            {
                $rate = floatval($GLOBALS['_CFG']['invoice_type']['rate'][$key]) / 100;
                break;
            }
        }
        if ($rate > 0)
        {
            $total['tax'] = $rate * $total['goods_price'];
        }
    }
    $total['tax_formated'] = price_format($total['tax'], false);

    /* 包装费用 */
    if (!empty($order['pack_id']))
    {
        $total['pack_fee']      = pack_fee($order['pack_id'], $total['goods_price']);
    }
    $total['pack_fee_formated'] = price_format($total['pack_fee'], false);

    /* 贺卡费用 */
    if (!empty($order['card_id']))
    {
        $total['card_fee']      = card_fee($order['card_id'], $total['goods_price']);
    }
    $total['card_fee_formated'] = price_format($total['card_fee'], false);

    /* 代金券 */

    if (!empty($order['bonus_id']))
    {
        $bonus          = bonus_info($order['bonus_id']);
        $total['bonus'] = $bonus['money'];
    }
    $total['bonus_formated'] = price_format($total['bonus'], false);

    /* 线下代金券 */
     if (!empty($order['bonus_kill']))
    {
        $bonus          = bonus_info(0,$order['bonus_kill']);
        $total['bonus_kill'] = $order['bonus_kill'];
        $total['bonus_kill_formated'] = price_format($total['bonus_kill'], false);
    }



    /* 配送费用 */
    $shipping_cod_fee = NULL;

    if ($order['shipping_id'] > 0 && $total['real_goods_count'] > 0)
    {
        $region['country']  = $consignee['country'];
        $region['province'] = $consignee['province'];
        $region['city']     = $consignee['city'];
        $region['district'] = $consignee['district'];
        $shipping_info = shipping_area_info($order['shipping_id'], $region);

        if (!empty($shipping_info))
        {
            if ($order['extension_code'] == 'group_buy')
            {
                $weight_price = cart_weight_price(CART_GROUP_BUY_GOODS);
            }
            else
            {
                $weight_price = cart_weight_price();
            }

            // 查看购物车中是否全为免运费商品，若是则把运费赋为零
            $sql = 'SELECT count(*) FROM ' . $GLOBALS['ecs']->table('cart') . " WHERE  `session_id` = '" . SESS_ID. "' AND `extension_code` != 'package_buy' AND `is_shipping` = 0";
            $shipping_count = $GLOBALS['db']->getOne($sql);

            $total['shipping_fee'] = ($shipping_count == 0 AND $weight_price['free_shipping'] == 1) ?0 :  shipping_fee($shipping_info['shipping_code'],$shipping_info['configure'], $weight_price['weight'], $total['goods_price'], $weight_price['number']);

            if (!empty($order['need_insure']) && $shipping_info['insure'] > 0)
            {
                $total['shipping_insure'] = shipping_insure_fee($shipping_info['shipping_code'],
                    $total['goods_price'], $shipping_info['insure']);
            }
            else
            {
                $total['shipping_insure'] = 0;
            }

            if ($shipping_info['support_cod'])
            {
                $shipping_cod_fee = $shipping_info['pay_fee'];
            }
        }
    }

    $total['shipping_fee_formated']    = price_format($total['shipping_fee'], false);
    $total['shipping_insure_formated'] = price_format($total['shipping_insure'], false);

    // 购物车中的商品能享受代金券支付的总额
    $bonus_amount = compute_discount_amount();
    // 代金券和积分最多能支付的金额为商品总额
    
    //add by wangcya, 20141102,使用代金券了，存在风险吧？
    $max_amount = $total['goods_price'] == 0 ? $total['goods_price'] : $total['goods_price'] - $bonus_amount;

    /* 计算订单总额 */
    if ($order['extension_code'] == 'group_buy' && $group_buy['deposit'] > 0)
    {
    	ss_log(__FUNCTION__.":zhangxi2 total goods_price=".$total['goods_price'].",total discount".$total['discount']);
        $total['amount'] = $total['goods_price'];
    }
    else
    {
    	//comment by zhangxi, 20150126, 最后用户真正要付的钱?
        $total['amount'] = $total['goods_price'] - $total['discount'] + $total['tax'] + $total['pack_fee'] + $total['card_fee'] +
            $total['shipping_fee'] + $total['shipping_insure'] + $total['cod_fee'];
		ss_log(__FUNCTION__.":zhangxi1 total amount=".$total['amount'].",total goods_price=".$total['goods_price'].",total discount".$total['discount']);
        // 减去代金券金额
        $use_bonus        = min($total['bonus'], $max_amount); // 实际减去的代金券金额
        if(isset($total['bonus_kill']))
        {
            $use_bonus_kill   = min($total['bonus_kill'], $max_amount);
            $total['amount'] -=  $price = number_format($total['bonus_kill'], 2, '.', ''); // 还需要支付的订单金额
        }

        $total['bonus']   = $use_bonus;
        $total['bonus_formated'] = price_format($total['bonus'], false);

        $total['amount'] -= $use_bonus; // 还需要支付的订单金额
        $max_amount      -= $use_bonus; // 积分最多还能支付的金额

    }

    /* 余额 */
    $order['surplus'] = $order['surplus'] > 0 ? $order['surplus'] : 0;
    if ($total['amount'] > 0)
    {
        if (isset($order['surplus']) && $order['surplus'] > $total['amount'])
        {
            $order['surplus'] = $total['amount'];
            $total['amount']  = 0;
        }
        else
        {
            $total['amount'] -= floatval($order['surplus']);
        }
    }
    else
    {
        $order['surplus'] = 0;
        $total['amount']  = 0;
    }
    $total['surplus'] = $order['surplus'];
    $total['surplus_formated'] = price_format($order['surplus'], false);
    
    
   	/* add yes123 2015-07-06 金币 start*/
    $order['service_money'] = $order['service_money'] > 0 ? $order['service_money'] : 0;
    if ($total['amount'] > 0)
    {
        if (isset($order['service_money']) && $order['service_money'] > $total['amount'])
        {
            $order['service_money'] = $total['service_money'];
            $total['amount']  = 0;
        }
        else
        {
            $total['amount'] -= floatval($order['service_money']);
        }
    }
    else
    {
        $order['service_money'] = 0;
        $total['amount']  = 0;
    }
    
    $total['service_money'] = $order['service_money'];
    $total['service_money_formated'] = price_format($order['service_money'], false);
    /* add yes123 2015-07-06 金币 end*/

    /* 积分 */
    $order['integral'] = $order['integral'] > 0 ? $order['integral'] : 0;
    if ($total['amount'] > 0 && $max_amount > 0 && $order['integral'] > 0)
    {
        $integral_money = value_of_integral($order['integral']);

        // 使用积分支付
        $use_integral            = min($total['amount'], $max_amount, $integral_money); // 实际使用积分支付的金额
        $total['amount']        -= $use_integral;
        $total['integral_money'] = $use_integral;
        $order['integral']       = integral_of_value($use_integral);
    }
    else
    {
        $total['integral_money'] = 0;
        $order['integral']       = 0;
    }
    $total['integral'] = $order['integral'];
    $total['integral_formated'] = price_format($total['integral_money'], false);

    /* 保存订单信息 */
    
    
    
    $_SESSION['flow_order'] = $order;
    

    $se_flow_type = isset($_SESSION['flow_type']) ? $_SESSION['flow_type'] : '';
    
    /* 支付费用 */
    if (!empty($order['pay_id']) && ($total['real_goods_count'] > 0 || $se_flow_type != CART_EXCHANGE_GOODS))
    {
        $total['pay_fee']      = pay_fee($order['pay_id'], $total['amount'], $shipping_cod_fee);
    }

    $total['pay_fee_formated'] = price_format($total['pay_fee'], false);

    $total['amount']           += $total['pay_fee']; // 订单总额累加上支付费用
    $total['amount_formated']  = price_format($total['amount'], false);

    /* 取得可以得到的积分和代金券 */
    if ($order['extension_code'] == 'group_buy')
    {
        $total['will_get_integral'] = $group_buy['gift_integral'];
    }
    elseif ($order['extension_code'] == 'exchange_goods')
    {
        $total['will_get_integral'] = 0;
    }
    else
    {
        $total['will_get_integral'] = get_give_integral(@$goods);
    }
    $total['will_get_bonus']        = $order['extension_code'] == 'exchange_goods' ? 0 : price_format(get_total_bonus(), false);
    $total['formated_goods_price']  = price_format($total['goods_price'], false);
    $total['formated_market_price'] = price_format($total['market_price'], false);
    $total['formated_saving']       = price_format(@$total['saving'], false);

    if ($order['extension_code'] == 'exchange_goods')
    {
        $sql = 'SELECT SUM(eg.exchange_integral) '.
               'FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c,' . $GLOBALS['ecs']->table('exchange_goods') . 'AS eg '.
               "WHERE c.goods_id = eg.goods_id AND c.session_id= '" . SESS_ID . "' " .
               "  AND c.rec_type = '" . CART_EXCHANGE_GOODS . "' " .
               '  AND c.is_gift = 0 AND c.goods_id > 0 ' .
               'GROUP BY eg.goods_id';
        $exchange_integral = $GLOBALS['db']->getOne($sql);
        $total['exchange_integral'] = $exchange_integral;
    }
    return $total;
}




/**
 * 修改订单
 * @param   int     $order_id   订单id
 * @param   array   $order      key => value
 * @return  bool
 */
function update_order($order_id, $order)
{
    return $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'),
        $order, 'UPDATE', "order_id = '$order_id'");
}

/**
 * 得到新订单号
 * @return  string
 */
function get_order_sn()
{
    /* 选择一个随机的方案 */
    mt_srand((double) microtime() * 1000000);
    $uid = $_SESSION['user_id'];

    //return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    //$uid = sprintf("%d",$uid);
    $len_pad = 5-strlen($uid);
    //ss_log("len_pad: ".$len_pad);
    if($len_pad)
    {
    	$rand = mt_rand(1, $len_pad);
    	//ss_log("rand: ".$rand);
    	$uid = $rand.$uid;
    	//ss_log("after rand: ".$uid);
    }
   
    $uid_pad = str_pad($uid, 5, '0', STR_PAD_LEFT);
    return date('Ymd').$uid_pad.str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);//假设我们支持十万用户量
}

/**
 * 取得购物车商品
 * @param   int     $type   类型：默认普通商品
 * @return  array   购物车商品数组
 */
function cart_goods($type = CART_GENERAL_GOODS)
{
	//comment by wangcya, 20141225, for bug[193],通过 session_id 来找到改笔订单中购物车中商品的价格
    $sql = "SELECT rec_id, user_id, goods_id, goods_name, goods_sn, goods_number, " .
            "market_price, goods_price, goods_attr, is_real, extension_code, parent_id, is_gift, is_shipping, " .
            "goods_price * goods_number AS subtotal " .
            "FROM " . $GLOBALS['ecs']->table('cart') .
            " WHERE session_id = '" . SESS_ID . "' " .
            "AND rec_type = '$type'";

    $arr = $GLOBALS['db']->getAll($sql);

    /* 格式化价格及礼包商品 */
    foreach ($arr as $key => $value)
    {
        $arr[$key]['formated_market_price'] = price_format($value['market_price'], false);
        $arr[$key]['formated_goods_price']  = price_format($value['goods_price'], false);
        $arr[$key]['formated_subtotal']     = price_format($value['subtotal'], false);

        if ($value['extension_code'] == 'package_buy')
        {
            $arr[$key]['package_goods_list'] = get_package_goods($value['goods_id']);
        }
    }

    return $arr;
}

/**
 * 取得购物车总金额
 * @params  boolean $include_gift   是否包括赠品
 * @param   int     $type           类型：默认普通商品
 * @return  float   购物车总金额
 */
function cart_amount($include_gift = true, $type = CART_GENERAL_GOODS)
{
    $sql = "SELECT SUM(goods_price * goods_number) " .
            " FROM " . $GLOBALS['ecs']->table('cart') .
            " WHERE session_id = '" . SESS_ID . "' " .
            "AND rec_type = '$type' ";

    if (!$include_gift)
    {
        $sql .= ' AND is_gift = 0 AND goods_id > 0';
    }

    return floatval($GLOBALS['db']->getOne($sql));
}

/**
 * 检查某商品是否已经存在于购物车
 *
 * @access  public
 * @param   integer     $id
 * @param   array       $spec
 * @param   int         $type   类型：默认普通商品
 * @return  boolean
 */
function cart_goods_exists($id, $spec, $type = CART_GENERAL_GOODS)
{
    /* 检查该商品是否已经存在在购物车中 */
    $sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('cart').
            "WHERE session_id = '" .SESS_ID. "' AND goods_id = '$id' ".
            "AND parent_id = 0 AND goods_attr = '" .get_goods_attr_info($spec). "' " .
            "AND rec_type = '$type'";

    return ($GLOBALS['db']->getOne($sql) > 0);
}

/**
 * 获得购物车中商品的总重量、总价格、总数量
 *
 * @access  public
 * @param   int     $type   类型：默认普通商品
 * @return  array
 */
function cart_weight_price($type = CART_GENERAL_GOODS)
{
    $package_row['weight'] = 0;
    $package_row['amount'] = 0;
    $package_row['number'] = 0;

    $packages_row['free_shipping'] = 1;

    /* 计算超值礼包内商品的相关配送参数 */
    $sql = 'SELECT goods_id, goods_number, goods_price FROM ' . $GLOBALS['ecs']->table('cart') . " WHERE extension_code = 'package_buy' AND session_id = '" . SESS_ID . "'";
    $row = $GLOBALS['db']->getAll($sql);

    if ($row)
    {
        $packages_row['free_shipping'] = 0;
        $free_shipping_count = 0;

        foreach ($row as $val)
        {
            // 如果商品全为免运费商品，设置一个标识变量
            $sql = 'SELECT count(*) FROM ' .
                    $GLOBALS['ecs']->table('package_goods') . ' AS pg, ' .
                    $GLOBALS['ecs']->table('goods') . ' AS g ' .
                    "WHERE g.goods_id = pg.goods_id AND g.is_shipping = 0 AND pg.package_id = '"  . $val['goods_id'] . "'";
            $shipping_count = $GLOBALS['db']->getOne($sql);

            if ($shipping_count > 0)
            {
                // 循环计算每个超值礼包商品的重量和数量，注意一个礼包中可能包换若干个同一商品
                $sql = 'SELECT SUM(g.goods_weight * pg.goods_number) AS weight, ' .
                    'SUM(pg.goods_number) AS number FROM ' .
                    $GLOBALS['ecs']->table('package_goods') . ' AS pg, ' .
                    $GLOBALS['ecs']->table('goods') . ' AS g ' .
                    "WHERE g.goods_id = pg.goods_id AND g.is_shipping = 0 AND pg.package_id = '"  . $val['goods_id'] . "'";

                $goods_row = $GLOBALS['db']->getRow($sql);
                $package_row['weight'] += floatval($goods_row['weight']) * $val['goods_number'];
                $package_row['amount'] += floatval($val['goods_price']) * $val['goods_number'];
                $package_row['number'] += intval($goods_row['number']) * $val['goods_number'];
            }
            else
            {
                $free_shipping_count++;
            }
        }

        $packages_row['free_shipping'] = $free_shipping_count == count($row) ? 1 : 0;
    }

    /* 获得购物车中非超值礼包商品的总重量 */
    $sql    = 'SELECT SUM(g.goods_weight * c.goods_number) AS weight, ' .
                    'SUM(c.goods_price * c.goods_number) AS amount, ' .
                    'SUM(c.goods_number) AS number '.
                'FROM ' . $GLOBALS['ecs']->table('cart') . ' AS c '.
                'LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON g.goods_id = c.goods_id '.
                "WHERE c.session_id = '" . SESS_ID . "' " .
                "AND rec_type = '$type' AND g.is_shipping = 0 AND c.extension_code != 'package_buy'";
    $row = $GLOBALS['db']->getRow($sql);

    $packages_row['weight'] = floatval($row['weight']) + $package_row['weight'];
    $packages_row['amount'] = floatval($row['amount']) + $package_row['amount'];
    $packages_row['number'] = intval($row['number']) + $package_row['number'];
    /* 格式化重量 */
    $packages_row['formated_weight'] = formated_weight($packages_row['weight']);

    return $packages_row;
}

/**
 * 添加商品到购物车
 *
 * @access  public
 * @param   integer $goods_id   商品编号
 * @param   integer $num        商品数量
 * @param   array   $spec       规格值对应的id数组
 * @param   integer $parent     基本件
 * @return  boolean
 */
function addto_cart($goods_id, $num = 1, $spec = array(), $parent = 0)
{
    $GLOBALS['err']->clean();
    $_parent_id = $parent;

    /* 取得商品信息 */
    $sql = "SELECT g.goods_name, g.goods_sn, g.is_on_sale, g.is_real, ".
                "g.market_price, g.shop_price AS org_price, g.promote_price, g.promote_start_date, ".
                "g.promote_end_date, g.goods_weight, g.integral, g.extension_code, ".
                "g.goods_number, g.is_alone_sale, g.is_shipping,".
                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price ".
            " FROM " .$GLOBALS['ecs']->table('goods'). " AS g ".
            " LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp ".
                    "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' ".
            " WHERE g.goods_id = '$goods_id'" .
            " AND g.is_delete = 0";
    $goods = $GLOBALS['db']->getRow($sql);

    if (empty($goods))
    {
        $GLOBALS['err']->add($GLOBALS['_LANG']['goods_not_exists'], ERR_NOT_EXISTS);

        return false;
    }

    /* 如果是作为配件添加到购物车的，需要先检查购物车里面是否已经有基本件 */
    if ($parent > 0)
    {
        $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('cart') .
                " WHERE goods_id='$parent' AND session_id='" . SESS_ID . "' AND extension_code <> 'package_buy'";
        if ($GLOBALS['db']->getOne($sql) == 0)
        {
            $GLOBALS['err']->add($GLOBALS['_LANG']['no_basic_goods'], ERR_NO_BASIC_GOODS);

            return false;
        }
    }

    /* 是否正在销售 */
    if ($goods['is_on_sale'] == 0)
    {
        $GLOBALS['err']->add($GLOBALS['_LANG']['not_on_sale'], ERR_NOT_ON_SALE);

        return false;
    }

    /* 不是配件时检查是否允许单独销售 */
    if (empty($parent) && $goods['is_alone_sale'] == 0)
    {
        $GLOBALS['err']->add($GLOBALS['_LANG']['cannt_alone_sale'], ERR_CANNT_ALONE_SALE);

        return false;
    }

    /* 如果商品有规格则取规格商品信息 配件除外 */
    $sql = "SELECT * FROM " .$GLOBALS['ecs']->table('products'). " WHERE goods_id = '$goods_id' LIMIT 0, 1";
    $prod = $GLOBALS['db']->getRow($sql);

    if (is_spec($spec) && !empty($prod))
    {
        $product_info = get_products_info($goods_id, $spec);
    }
    if (empty($product_info))
    {
        $product_info = array('product_number' => '', 'product_id' => 0);
    }

    /* 检查：库存 */
    if ($GLOBALS['_CFG']['use_storage'] == 1)
    {
        //检查：商品购买数量是否大于总库存
        if ($num > $goods['goods_number'])
        {
            $GLOBALS['err']->add(sprintf($GLOBALS['_LANG']['shortage'], $goods['goods_number']), ERR_OUT_OF_STOCK);

            return false;
        }

        //商品存在规格 是货品 检查该货品库存
        if (is_spec($spec) && !empty($prod))
        {
            if (!empty($spec))
            {
                /* 取规格的货品库存 */
                if ($num > $product_info['product_number'])
                {
                    $GLOBALS['err']->add(sprintf($GLOBALS['_LANG']['shortage'], $product_info['product_number']), ERR_OUT_OF_STOCK);
    
                    return false;
                }
            }
        }       
    }

    /* 计算商品的促销价格 */
    $spec_price             = spec_price($spec);
    $goods_price            = get_final_price($goods_id, $num, true, $spec);
    $goods['market_price'] += $spec_price;
    $goods_attr             = get_goods_attr_info($spec);
    $goods_attr_id          = join(',', $spec);

    /* 初始化要插入购物车的基本件数据 */
    $parent = array(
        'user_id'       => $_SESSION['user_id'],
        'session_id'    => SESS_ID,
        'goods_id'      => $goods_id,
        'goods_sn'      => addslashes($goods['goods_sn']),
        'product_id'    => $product_info['product_id'],
        'goods_name'    => addslashes($goods['goods_name']),
        'market_price'  => $goods['market_price'],
        'goods_attr'    => addslashes($goods_attr),
        'goods_attr_id' => $goods_attr_id,
        'is_real'       => $goods['is_real'],
        'extension_code'=> $goods['extension_code'],
        'is_gift'       => 0,
        'is_shipping'   => $goods['is_shipping'],
        'rec_type'      => CART_GENERAL_GOODS
    );

    /* 如果该配件在添加为基本件的配件时，所设置的“配件价格”比原价低，即此配件在价格上提供了优惠， */
    /* 则按照该配件的优惠价格卖，但是每一个基本件只能购买一个优惠价格的“该配件”，多买的“该配件”不享 */
    /* 受此优惠 */
    $basic_list = array();
    $sql = "SELECT parent_id, goods_price " .
            "FROM " . $GLOBALS['ecs']->table('group_goods') .
            " WHERE goods_id = '$goods_id'" .
            " AND goods_price < '$goods_price'" .
            " AND parent_id = '$_parent_id'" .
            " ORDER BY goods_price";
    $res = $GLOBALS['db']->query($sql);
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $basic_list[$row['parent_id']] = $row['goods_price'];
    }

    /* 取得购物车中该商品每个基本件的数量 */
    $basic_count_list = array();
    if ($basic_list)
    {
        $sql = "SELECT goods_id, SUM(goods_number) AS count " .
                "FROM " . $GLOBALS['ecs']->table('cart') .
                " WHERE session_id = '" . SESS_ID . "'" .
                " AND parent_id = 0" .
                " AND extension_code <> 'package_buy' " .
                " AND goods_id " . db_create_in(array_keys($basic_list)) .
                " GROUP BY goods_id";
        $res = $GLOBALS['db']->query($sql);
        while ($row = $GLOBALS['db']->fetchRow($res))
        {
            $basic_count_list[$row['goods_id']] = $row['count'];
        }
    }

    /* 取得购物车中该商品每个基本件已有该商品配件数量，计算出每个基本件还能有几个该商品配件 */
    /* 一个基本件对应一个该商品配件 */
    if ($basic_count_list)
    {
        $sql = "SELECT parent_id, SUM(goods_number) AS count " .
                "FROM " . $GLOBALS['ecs']->table('cart') .
                " WHERE session_id = '" . SESS_ID . "'" .
                " AND goods_id = '$goods_id'" .
                " AND extension_code <> 'package_buy' " .
                " AND parent_id " . db_create_in(array_keys($basic_count_list)) .
                " GROUP BY parent_id";
        $res = $GLOBALS['db']->query($sql);
        while ($row = $GLOBALS['db']->fetchRow($res))
        {
            $basic_count_list[$row['parent_id']] -= $row['count'];
        }
    }

    /* 循环插入配件 如果是配件则用其添加数量依次为购物车中所有属于其的基本件添加足够数量的该配件 */
    foreach ($basic_list as $parent_id => $fitting_price)
    {
        /* 如果已全部插入，退出 */
        if ($num <= 0)
        {
            break;
        }

        /* 如果该基本件不再购物车中，执行下一个 */
        if (!isset($basic_count_list[$parent_id]))
        {
            continue;
        }

        /* 如果该基本件的配件数量已满，执行下一个基本件 */
        if ($basic_count_list[$parent_id] <= 0)
        {
            continue;
        }

        /* 作为该基本件的配件插入 */
        $parent['goods_price']  = max($fitting_price, 0) + $spec_price; //允许该配件优惠价格为0
        $parent['goods_number'] = min($num, $basic_count_list[$parent_id]);
        $parent['parent_id']    = $parent_id;

        /* 添加 */
        $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('cart'), $parent, 'INSERT');

        /* 改变数量 */
        $num -= $parent['goods_number'];
    }

    /* 如果数量不为0，作为基本件插入 */
    if ($num > 0)
    {
        /* 检查该商品是否已经存在在购物车中 */
        $sql = "SELECT goods_number FROM " .$GLOBALS['ecs']->table('cart').
                " WHERE session_id = '" .SESS_ID. "' AND goods_id = '$goods_id' ".
                " AND parent_id = 0 AND goods_attr = '" .get_goods_attr_info($spec). "' " .
                " AND extension_code <> 'package_buy' " .
                " AND rec_type = 'CART_GENERAL_GOODS'";

        $row = $GLOBALS['db']->getRow($sql);

        if($row) //如果购物车已经有此物品，则更新
        {
            $num += $row['goods_number'];
            if(is_spec($spec) && !empty($prod) )
            {
             $goods_storage=$product_info['product_number'];
            }
            else
            {
                $goods_storage=$goods['goods_number'];
            }
            if ($GLOBALS['_CFG']['use_storage'] == 0 || $num <= $goods_storage)
            {
                $goods_price = get_final_price($goods_id, $num, true, $spec);
                $sql = "UPDATE " . $GLOBALS['ecs']->table('cart') . " SET goods_number = '$num'" .
                       " , goods_price = '$goods_price'".
                       " WHERE session_id = '" .SESS_ID. "' AND goods_id = '$goods_id' ".
                       " AND parent_id = 0 AND goods_attr = '" .get_goods_attr_info($spec). "' " .
                       " AND extension_code <> 'package_buy' " .
                       "AND rec_type = 'CART_GENERAL_GOODS'";
                $GLOBALS['db']->query($sql);
            }
            else
            {
               $GLOBALS['err']->add(sprintf($GLOBALS['_LANG']['shortage'], $num), ERR_OUT_OF_STOCK);

                return false;
            }
        }
        else //购物车没有此物品，则插入
        {
            $goods_price = get_final_price($goods_id, $num, true, $spec);
            $parent['goods_price']  = max($goods_price, 0);
            $parent['goods_number'] = $num;
            $parent['parent_id']    = 0;
            $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('cart'), $parent, 'INSERT');
        }
    }

    /* 把赠品删除 */
    $sql = "DELETE FROM " . $GLOBALS['ecs']->table('cart') . " WHERE session_id = '" . SESS_ID . "' AND is_gift <> 0";
    $GLOBALS['db']->query($sql);

    return true;
}

/**
 * 清空购物车
 * @param   int     $type   类型：默认普通商品
 */
function clear_cart($type = CART_GENERAL_GOODS)
{
    $sql = "DELETE FROM " . $GLOBALS['ecs']->table('cart') .
            " WHERE session_id = '" . SESS_ID . "' AND rec_type = '$type'";
    $GLOBALS['db']->query($sql);
}

/**
 * 获得指定的商品属性
 *
 * @access      public
 * @param       array       $arr        规格、属性ID数组
 * @param       type        $type       设置返回结果类型：pice，显示价格，默认；no，不显示价格
 *
 * @return      string
 */
function get_goods_attr_info($arr, $type = 'pice')
{
    $attr   = '';

    if (!empty($arr))
    {
        $fmt = "%s:%s[%s] \n";

        $sql = "SELECT a.attr_name, ga.attr_value, ga.attr_price ".
                "FROM ".$GLOBALS['ecs']->table('goods_attr')." AS ga, ".
                    $GLOBALS['ecs']->table('attribute')." AS a ".
                "WHERE " .db_create_in($arr, 'ga.goods_attr_id')." AND a.attr_id = ga.attr_id";
        $res = $GLOBALS['db']->query($sql);

        while ($row = $GLOBALS['db']->fetchRow($res))
        {
            $attr_price = round(floatval($row['attr_price']), 2);
            $attr .= sprintf($fmt, $row['attr_name'], $row['attr_value'], $attr_price);
        }

        $attr = str_replace('[0]', '', $attr);
    }

    return $attr;
}

/**
 * 取得用户信息
 * @param   int     $user_id    用户id
 * @return  array   用户信息
 */
function user_info($user_id)
{
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('users') .
            " WHERE user_id = '$user_id'";
    $user = $GLOBALS['db']->getRow($sql);

    unset($user['question']);
    unset($user['answer']);

    /* 格式化账户余额 */
    if ($user)
    {
//        if ($user['user_money'] < 0)
//        {
//            $user['user_money'] = 0;
//        }
        $user['formated_user_money'] = price_format($user['user_money'], false);
        $user['formated_frozen_money'] = price_format($user['frozen_money'], false);
    }

    return $user;
}

//add yes123 2015-06-09 余额总额是这三个账户总和
function get_total_amount_by_user($user_info)
{
	$user_money = $user_info['user_money']+$user_info['service_money']+$user_info['award_money'];
	return $user_money;
}
/**
 * 修改用户
 * @param   int     $user_id   订单id
 * @param   array   $user      key => value
 * @return  bool
 */
function update_user($user_id, $user)
{
    return $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('users'),
        $user, 'UPDATE', "user_id = '$user_id'");
}

/**
 * 取得用户地址列表
 * @param   int     $user_id    用户id
 * @return  array
 */
function address_list($user_id)
{
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('user_address') .
            " WHERE user_id = '$user_id'";

    return $GLOBALS['db']->getAll($sql);
}

/**
 * 取得用户地址信息
 * @param   int     $address_id     地址id
 * @return  array
 */
function address_info($address_id)
{
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('user_address') .
            " WHERE address_id = '$address_id'";

    return $GLOBALS['db']->getRow($sql);
}

/**
 * 取得用户当前可用代金券
 * @param   int     $user_id        用户id
 * @param   float   $goods_amount   订单商品金额
 * @return  array   代金券数组
 */
function user_bonus($user_id, $goods_amount = 0)
{
    $day    = getdate();
    $today  = local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);

/*    $sql = "SELECT t.type_id, t.type_name, t.type_money, b.bonus_id,t.use_end_date " .
            "FROM " . $GLOBALS['ecs']->table('bonus_type') . " AS t," .
                $GLOBALS['ecs']->table('user_bonus') . " AS b " .
            "WHERE t.type_id = b.bonus_type_id " .
            "AND t.use_start_date <= '$today' AND t.use_end_date >= '$today' " .
            "AND t.min_goods_amount <= '$goods_amount' " .
            "AND b.user_id<>0 " .
            "AND b.user_id = '$user_id' " .
            "AND b.order_id = 0";*/
      $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('user_bonus') . " " .
            "WHERE use_s_date <= '$today' AND use_e_date >= '$today' " .
            "AND user_id<>0 " .
            "AND user_id = '$user_id' " .
            "AND order_id = 0";    
            
    return $GLOBALS['db']->getAll($sql);
}

/**
 * 取得代金券信息
 * @param   int     $bonus_id   代金券id
 * @param   string  $bonus_sn   代金券序列号
 * @param   array   代金券信息
 */
function bonus_info($bonus_id, $bonus_sn = '')
{
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('user_bonus');
    if ($bonus_id > 0)
    {
        $sql .= " WHERE bonus_id = '$bonus_id'";
    }
    else
    {
        $sql .= "WHERE bonus_sn = '$bonus_sn'";
    }

    return $GLOBALS['db']->getRow($sql);
}

/**
 * 检查代金券是否已使用
 * @param   int $bonus_id   代金券id
 * @return  bool
 */
function bonus_used($bonus_id)
{
    $sql = "SELECT order_id FROM " . $GLOBALS['ecs']->table('user_bonus') .
            " WHERE bonus_id = '$bonus_id'";

    return  $GLOBALS['db']->getOne($sql) > 0;
}

/**
 * 设置代金券为已使用
 * @param   int     $bonus_id   代金券id
 * @param   int     $order_id   订单id
 * @return  bool
 */
function use_bonus($bonus_id, $order_id)
{
    $sql = "UPDATE " . $GLOBALS['ecs']->table('user_bonus') .
            " SET order_id = '$order_id', used_time = '" . time() . "' " .
            "WHERE bonus_id = '$bonus_id' LIMIT 1";

    return  $GLOBALS['db']->query($sql);
}

/**
 * 设置代金券为未使用
 * @param   int     $bonus_id   代金券id
 * @param   int     $order_id   订单id
 * @return  bool
 */
function unuse_bonus($bonus_id)
{
    $sql = "UPDATE " . $GLOBALS['ecs']->table('user_bonus') .
            " SET order_id = 0, used_time = 0 " .
            "WHERE bonus_id = '$bonus_id' LIMIT 1";

    return  $GLOBALS['db']->query($sql);
}

/**
 * 计算积分的价值（能抵多少钱）
 * @param   int     $integral   积分
 * @return  float   积分价值
 */
function value_of_integral($integral)
{
    $scale = floatval($GLOBALS['_CFG']['integral_scale']);

    return $scale > 0 ? round(($integral / 100) * $scale, 2) : 0;
}

/**
 * 计算指定的金额需要多少积分
 *
 * @access  public
 * @param   integer $value  金额
 * @return  void
 */
function integral_of_value($value)
{
    $scale = floatval($GLOBALS['_CFG']['integral_scale']);

    return $scale > 0 ? round($value / $scale * 100) : 0;
}

/**
 * 订单退款
 * @param   array   $order          订单
 * @param   int     $refund_type    退款方式 1 到账户余额 2 到退款申请（先到余额，再申请提款） 3 不处理
 * @param   string  $refund_note    退款说明
 * @param   float   $refund_amount  退款金额（如果为0，取订单已付款金额）
 * @return  bool
 */
function order_refund($order, $refund_type, $refund_note, $refund_amount = 0)
{
    /* 检查参数 */
    $user_id = $order['user_id'];
    if ($user_id == 0 && $refund_type == 1)
    {
        die('anonymous, cannot return to account balance');
    }

    $amount = $refund_amount > 0 ? $refund_amount : $order['money_paid'];
    if ($amount <= 0)
    {
        return true;
    }

    if (!in_array($refund_type, array(1, 2, 3)))
    {
        die('invalid params');
    }

    /* 备注信息 */
    if ($refund_note)
    {
        $change_desc = $refund_note;
    }
    else
    {
        include_once(ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/admin/order.php');
        $change_desc = sprintf($GLOBALS['_LANG']['order_refund'], $order['order_sn']);
    }

    /* 处理退款 */
    if (1 == $refund_type)
    {
        log_account_change($user_id, $amount, 0, 0, 0, $change_desc);

        return true;
    }
    elseif (2 == $refund_type)
    {
        /* 如果非匿名，退回余额 */
        if ($user_id > 0)
        {
            log_account_change($user_id, $amount, 0, 0, 0, $change_desc);
        }

        /* user_account 表增加提款申请记录 */
        $account = array(
            'user_id'      => $user_id,
            'amount'       => (-1) * $amount,
            'add_time'     => time(),
            'user_note'    => $refund_note,
            'process_type' => SURPLUS_RETURN,
            'admin_user'   => $_SESSION['admin_name'],
            'admin_note'   => sprintf($GLOBALS['_LANG']['order_refund'], $order['order_sn']),
            'is_paid'      => 0
        );
        $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_account'), $account, 'INSERT');

        return true;
    }
    else
    {
        return true;
    }
}

/**
 * 获得购物车中的商品
 *
 * @access  public
 * @return  array
 */
function get_cart_goods()
{
    /* 初始化 */
    $goods_list = array();
    $total = array(
        'goods_price'  => 0, // 本店售价合计（有格式）
        'market_price' => 0, // 市场售价合计（有格式）
        'saving'       => 0, // 节省金额（有格式）
        'save_rate'    => 0, // 节省百分比
        'goods_amount' => 0, // 本店售价合计（无格式）
    );

    /* 循环、统计 */
    $sql = "SELECT *, IF(parent_id, parent_id, goods_id) AS pid " .
            " FROM " . $GLOBALS['ecs']->table('cart') . " " .
            " WHERE session_id = '" . SESS_ID . "' AND rec_type = '" . CART_GENERAL_GOODS . "'" .
            " ORDER BY pid, parent_id";
    $res = $GLOBALS['db']->query($sql);

    /* 用于统计购物车中实体商品和虚拟商品的个数 */
    $virtual_goods_count = 0;
    $real_goods_count    = 0;

    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $total['goods_price']  += $row['goods_price'] * $row['goods_number'];
        $total['market_price'] += $row['market_price'] * $row['goods_number'];

        $row['subtotal']     = price_format($row['goods_price'] * $row['goods_number'], false);
        $row['goods_price']  = price_format($row['goods_price'], false);
        $row['market_price'] = price_format($row['market_price'], false);

        /* 统计实体商品和虚拟商品的个数 */
        if ($row['is_real'])
        {
            $real_goods_count++;
        }
        else
        {
            $virtual_goods_count++;
        }

        /* 查询规格 */
        if (trim($row['goods_attr']) != '')
        {
            $row['goods_attr']=addslashes($row['goods_attr']);
            $sql = "SELECT attr_value FROM " . $GLOBALS['ecs']->table('goods_attr') . " WHERE goods_attr_id " .
            db_create_in($row['goods_attr']);
            $attr_list = $GLOBALS['db']->getCol($sql);  
            foreach ($attr_list AS $attr)
            {
                $row['goods_name'] .= ' [' . $attr . '] ';
            }
        }
        /* 增加是否在购物车里显示商品图 */
        if (($GLOBALS['_CFG']['show_goods_in_cart'] == "2" || $GLOBALS['_CFG']['show_goods_in_cart'] == "3") && $row['extension_code'] != 'package_buy')
        {
            $goods_thumb = $GLOBALS['db']->getOne("SELECT `goods_thumb` FROM " . $GLOBALS['ecs']->table('goods') . " WHERE `goods_id`='{$row['goods_id']}'");
            $row['goods_thumb'] = get_image_path($row['goods_id'], $goods_thumb, true);
        }
        if ($row['extension_code'] == 'package_buy')
        {
            $row['package_goods_list'] = get_package_goods($row['goods_id']);
        }
        $goods_list[] = $row;
    }
    $total['goods_amount'] = $total['goods_price'];
    $total['saving']       = price_format($total['market_price'] - $total['goods_price'], false);
    if ($total['market_price'] > 0)
    {
        $total['save_rate'] = $total['market_price'] ? round(($total['market_price'] - $total['goods_price']) *
        100 / $total['market_price']).'%' : 0;
    }
    $total['goods_price']  = price_format($total['goods_price'], false);
    $total['market_price'] = price_format($total['market_price'], false);
    $total['real_goods_count']    = $real_goods_count;
    $total['virtual_goods_count'] = $virtual_goods_count;

    return array('goods_list' => $goods_list, 'total' => $total);
}

/**
 * 取得收货人信息
 * @param   int     $user_id    用户编号
 * @return  array
 */
function get_consignee($user_id)
{
    if (isset($_SESSION['flow_consignee']))
    {
        /* 如果存在session，则直接返回session中的收货人信息 */

        return $_SESSION['flow_consignee'];
    }
    else
    {
        /* 如果不存在，则取得用户的默认收货人信息 */
        $arr = array();

        if ($user_id > 0)
        {
            /* 取默认地址 */
            $sql = "SELECT ua.*".
                    " FROM " . $GLOBALS['ecs']->table('user_address') . "AS ua, ".$GLOBALS['ecs']->table('users').' AS u '.
                    " WHERE u.user_id='$user_id' AND ua.address_id = u.address_id";

            $arr = $GLOBALS['db']->getRow($sql);
        }

        return $arr;
    }
}

/**
 * 查询购物车（订单id为0）或订单中是否有实体商品
 * @param   int     $order_id   订单id
 * @param   int     $flow_type  购物流程类型
 * @return  bool
 */
function exist_real_goods($order_id = 0, $flow_type = CART_GENERAL_GOODS)
{
    if ($order_id <= 0)
    {
        $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('cart') .
                " WHERE session_id = '" . SESS_ID . "' AND is_real = 1 " .
                "AND rec_type = '$flow_type'";
    }
    else
    {
        $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('order_goods') .
                " WHERE order_id = '$order_id' AND is_real = 1";
    }

    return $GLOBALS['db']->getOne($sql) > 0;
}

/**
 * 检查收货人信息是否完整
 * @param   array   $consignee  收货人信息
 * @param   int     $flow_type  购物流程类型
 * @return  bool    true 完整 false 不完整
 */
function check_consignee_info($consignee, $flow_type)
{
    if (exist_real_goods(0, $flow_type))
    {
        /* 如果存在实体商品 */
        $res = !empty($consignee['consignee']) &&
            !empty($consignee['country']) &&
            !empty($consignee['email']) &&
            !empty($consignee['tel']);

        if ($res)
        {
            if (empty($consignee['province']))
            {
                /* 没有设置省份，检查当前国家下面有没有设置省份 */
                $pro = get_regions(1, $consignee['country']);
                $res = empty($pro);
            }
            elseif (empty($consignee['city']))
            {
                /* 没有设置城市，检查当前省下面有没有城市 */
                $city = get_regions(2, $consignee['province']);
                $res = empty($city);
            }
            elseif (empty($consignee['district']))
            {
                $dist = get_regions(3, $consignee['city']);
                $res = empty($dist);
            }
        }

        return $res;
    }
    else
    {
        /* 如果不存在实体商品 */
        return !empty($consignee['consignee']) &&
            !empty($consignee['email']) &&
            !empty($consignee['tel']);
    }
}

/**
 * 获得上一次用户采用的支付和配送方式
 *
 * @access  public
 * @return  void
 */
function last_shipping_and_payment()
{
    $sql = "SELECT shipping_id, pay_id " .
            " FROM " . $GLOBALS['ecs']->table('order_info') .
            " WHERE user_id = '$_SESSION[user_id]' " .
            " ORDER BY order_id DESC LIMIT 1";
    $row = $GLOBALS['db']->getRow($sql);

    if (empty($row))
    {
        /* 如果获得是一个空数组，则返回默认值 */
        $row = array('shipping_id' => 0, 'pay_id' => 0);
    }

    return $row;
}

/**
 * 取得当前用户应该得到的代金券总额
 */
function get_total_bonus()
{
    $day    = getdate();
    $today  = local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);

    /* 按商品发的代金券 */
    $sql = "SELECT SUM(c.goods_number * t.type_money)" .
            "FROM " . $GLOBALS['ecs']->table('cart') . " AS c, "
                    . $GLOBALS['ecs']->table('bonus_type') . " AS t, "
                    . $GLOBALS['ecs']->table('goods') . " AS g " .
            "WHERE c.session_id = '" . SESS_ID . "' " .
            "AND c.is_gift = 0 " .
            "AND c.goods_id = g.goods_id " .
            "AND g.bonus_type_id = t.type_id " .
            "AND t.send_type = '" . SEND_BY_GOODS . "' " .
            "AND t.send_start_date <= '$today' " .
            "AND t.send_end_date >= '$today' " .
            "AND c.rec_type = '" . CART_GENERAL_GOODS . "'";
    $goods_total = floatval($GLOBALS['db']->getOne($sql));

    /* 取得购物车中非赠品总金额 */
    $sql = "SELECT SUM(goods_price * goods_number) " .
            "FROM " . $GLOBALS['ecs']->table('cart') .
            " WHERE session_id = '" . SESS_ID . "' " .
            " AND is_gift = 0 " .
            " AND rec_type = '" . CART_GENERAL_GOODS . "'";
    $amount = floatval($GLOBALS['db']->getOne($sql));

    /* 按订单发的代金券 */
    $sql = "SELECT FLOOR('$amount' / min_amount) * type_money " .
            "FROM " . $GLOBALS['ecs']->table('bonus_type') .
            " WHERE send_type = '" . SEND_BY_ORDER . "' " .
            " AND send_start_date <= '$today' " .
            "AND send_end_date >= '$today' " .
            "AND min_amount > 0 ";
    $order_total = floatval($GLOBALS['db']->getOne($sql));

    return $goods_total + $order_total;
}

/**
 * 处理代金券（下订单时设为使用，取消（无效，退货）订单时设为未使用
 * @param   int     $bonus_id   代金券编号
 * @param   int     $order_id   订单号
 * @param   int     $is_used    是否使用了
 */
function change_user_bonus($bonus_id, $order_id, $is_used = true)
{
    if ($is_used)
    {
        $sql = 'UPDATE ' . $GLOBALS['ecs']->table('user_bonus') . ' SET ' .
                'used_time = ' . time() . ', ' .
                "order_id = '$order_id' " .
                "WHERE bonus_id = '$bonus_id'";
    }
    else
    {
        $sql = 'UPDATE ' . $GLOBALS['ecs']->table('user_bonus') . ' SET ' .
                'used_time = 0, ' .
                'order_id = 0 ' .
                "WHERE bonus_id = '$bonus_id'";
    }
    $GLOBALS['db']->query($sql);
}

/**
 * 获得订单信息
 *
 * @access  private
 * @return  array
 */
function flow_order_info()
{
    $order = isset($_SESSION['flow_order']) ? $_SESSION['flow_order'] : array();

    /* 初始化配送和支付方式 */
    if (!isset($order['shipping_id']) || !isset($order['pay_id']))
    {
        /* 如果还没有设置配送和支付 */
        if ($_SESSION['user_id'] > 0)
        {
            /* 用户已经登录了，则获得上次使用的配送和支付 */
            $arr = last_shipping_and_payment();

            if (!isset($order['shipping_id']))
            {
                $order['shipping_id'] = $arr['shipping_id'];
            }
            if (!isset($order['pay_id']))
            {
                $order['pay_id'] = $arr['pay_id'];
            }
        }
        else
        {
            if (!isset($order['shipping_id']))
            {
                $order['shipping_id'] = 0;
            }
            if (!isset($order['pay_id']))
            {
                $order['pay_id'] = 0;
            }
        }
    }

    if (!isset($order['pack_id']))
    {
        $order['pack_id'] = 0;  // 初始化包装
    }
    if (!isset($order['card_id']))
    {
        $order['card_id'] = 0;  // 初始化贺卡
    }
    if (!isset($order['bonus']))
    {
        $order['bonus'] = 0;    // 初始化代金券
    }
    if (!isset($order['integral']))
    {
        $order['integral'] = 0; // 初始化积分
    }
    if (!isset($order['surplus']))
    {
        $order['surplus'] = 0;  // 初始化余额
    }

    /* 扩展信息 */
    if (isset($_SESSION['flow_type']) && intval($_SESSION['flow_type']) != CART_GENERAL_GOODS)
    {
        $order['extension_code'] = $_SESSION['extension_code'];
        $order['extension_id'] = $_SESSION['extension_id'];
    }

    return $order;
}

/**
 * 合并订单
 * @param   string  $from_order_sn  从订单号
 * @param   string  $to_order_sn    主订单号
 * @return  成功返回true，失败返回错误信息
 */
function merge_order($from_order_sn, $to_order_sn)
{
    /* 订单号不能为空 */
    if (trim($from_order_sn) == '' || trim($to_order_sn) == '')
    {
        return $GLOBALS['_LANG']['order_sn_not_null'];
    }

    /* 订单号不能相同 */
    if ($from_order_sn == $to_order_sn)
    {
        return $GLOBALS['_LANG']['two_order_sn_same'];
    }

    /* 取得订单信息 */
    $from_order = order_info(0, $from_order_sn);
    $to_order   = order_info(0, $to_order_sn);

    /* 检查订单是否存在 */
    if (!$from_order)
    {
        return sprintf($GLOBALS['_LANG']['order_not_exist'], $from_order_sn);
    }
    elseif (!$to_order)
    {
        return sprintf($GLOBALS['_LANG']['order_not_exist'], $to_order_sn);
    }

    /* 检查合并的订单是否为普通订单，非普通订单不允许合并 */
    if ($from_order['extension_code'] != '' || $to_order['extension_code'] != 0)
    {
        return $GLOBALS['_LANG']['merge_invalid_order'];
    }

    /* 检查订单状态是否是已确认或未确认、未付款、未发货 */
    if ($from_order['order_status'] != OS_UNCONFIRMED && $from_order['order_status'] != OS_CONFIRMED)
    {
        return sprintf($GLOBALS['_LANG']['os_not_unconfirmed_or_confirmed'], $from_order_sn);
    }
    elseif ($from_order['pay_status'] != PS_UNPAYED)
    {
        return sprintf($GLOBALS['_LANG']['ps_not_unpayed'], $from_order_sn);
    }
    elseif ($from_order['shipping_status'] != SS_UNSHIPPED)
    {
        return sprintf($GLOBALS['_LANG']['ss_not_unshipped'], $from_order_sn);
    }

    if ($to_order['order_status'] != OS_UNCONFIRMED && $to_order['order_status'] != OS_CONFIRMED)
    {
        return sprintf($GLOBALS['_LANG']['os_not_unconfirmed_or_confirmed'], $to_order_sn);
    }
    elseif ($to_order['pay_status'] != PS_UNPAYED)
    {
        return sprintf($GLOBALS['_LANG']['ps_not_unpayed'], $to_order_sn);
    }
    elseif ($to_order['shipping_status'] != SS_UNSHIPPED)
    {
        return sprintf($GLOBALS['_LANG']['ss_not_unshipped'], $to_order_sn);
    }

    /* 检查订单用户是否相同 */
    if ($from_order['user_id'] != $to_order['user_id'])
    {
        return $GLOBALS['_LANG']['order_user_not_same'];
    }

    /* 合并订单 */
    $order = $to_order;
    $order['order_id']  = '';
    $order['add_time']  = time();

    // 合并商品总额
    $order['goods_amount'] += $from_order['goods_amount'];

    // 合并折扣
    $order['discount'] += $from_order['discount'];

    if ($order['shipping_id'] > 0)
    {
        // 重新计算配送费用
        $weight_price       = order_weight_price($to_order['order_id']);
        $from_weight_price  = order_weight_price($from_order['order_id']);
        $weight_price['weight'] += $from_weight_price['weight'];
        $weight_price['amount'] += $from_weight_price['amount'];
        $weight_price['number'] += $from_weight_price['number'];

        $region_id_list = array($order['country'], $order['province'], $order['city'], $order['district']);
        $shipping_area = shipping_area_info($order['shipping_id'], $region_id_list);

        $order['shipping_fee'] = shipping_fee($shipping_area['shipping_code'],
            unserialize($shipping_area['configure']), $weight_price['weight'], $weight_price['amount'], $weight_price['number']);

        // 如果保价了，重新计算保价费
        if ($order['insure_fee'] > 0)
        {
            $order['insure_fee'] = shipping_insure_fee($shipping_area['shipping_code'], $order['goods_amount'], $shipping_area['insure']);
        }
    }

    // 重新计算包装费、贺卡费
    if ($order['pack_id'] > 0)
    {
        $pack = pack_info($order['pack_id']);
        $order['pack_fee'] = $pack['free_money'] > $order['goods_amount'] ? $pack['pack_fee'] : 0;
    }
    if ($order['card_id'] > 0)
    {
        $card = card_info($order['card_id']);
        $order['card_fee'] = $card['free_money'] > $order['goods_amount'] ? $card['card_fee'] : 0;
    }

    // 代金券不变，合并积分、余额、已付款金额
    $order['integral']      += $from_order['integral'];
    $order['integral_money'] = value_of_integral($order['integral']);
    $order['surplus']       += $from_order['surplus'];
    $order['money_paid']    += $from_order['money_paid'];

    // 计算应付款金额（不包括支付费用）
    $order['order_amount'] = $order['goods_amount'] - $order['discount']
                           + $order['shipping_fee']
                           + $order['insure_fee']
                           + $order['pack_fee']
                           + $order['card_fee']
                           - $order['bonus']
                           - $order['integral_money']
                           - $order['surplus']
                           - $order['money_paid'];

    // 重新计算支付费
    if ($order['pay_id'] > 0)
    {
        // 货到付款手续费
        $cod_fee          = $shipping_area ? $shipping_area['pay_fee'] : 0;
        $order['pay_fee'] = pay_fee($order['pay_id'], $order['order_amount'], $cod_fee);

        // 应付款金额加上支付费
        $order['order_amount'] += $order['pay_fee'];
    }

    /* 插入订单表 */
    do
    {
        $order['order_sn'] = get_order_sn();
        if ($GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'), addslashes_deep($order), 'INSERT'))
        {
            break;
        }
        else
        {
            if ($GLOBALS['db']->errno() != 1062)
            {
                die($GLOBALS['db']->errorMsg());
            }
        }
    }
    while (true); // 防止订单号重复

    /* 订单号 */
    $order_id = $GLOBALS['db']->insert_id();

    /* 更新订单商品 */
    $sql = 'UPDATE ' . $GLOBALS['ecs']->table('order_goods') .
            " SET order_id = '$order_id' " .
            "WHERE order_id " . db_create_in(array($from_order['order_id'], $to_order['order_id']));
    $GLOBALS['db']->query($sql);

    include_once(ROOT_PATH . 'includes/lib_clips.php');
    /* 插入支付日志 */
    insert_pay_log($order_id, $order['order_amount'], PAY_ORDER);

    /* 删除原订单 */
    $sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('order_info') .
            " WHERE order_id " . db_create_in(array($from_order['order_id'], $to_order['order_id']));
    $GLOBALS['db']->query($sql);

    /* 删除原订单支付日志 */
    $sql = 'DELETE FROM ' . $GLOBALS['ecs']->table('pay_log') .
            " WHERE order_id " . db_create_in(array($from_order['order_id'], $to_order['order_id']));
    $GLOBALS['db']->query($sql);

    /* 返还 from_order 的代金券，因为只使用 to_order 的代金券 */
    if ($from_order['bonus_id'] > 0)
    {
        unuse_bonus($from_order['bonus_id']);
    }

    /* 返回成功 */
    return true;
}

/**
 * 查询配送区域属于哪个办事处管辖
 * @param   array   $regions    配送区域（1、2、3、4级按顺序）
 * @return  int     办事处id，可能为0
 */
function get_agency_by_regions($regions)
{
    if (!is_array($regions) || empty($regions))
    {
        return 0;
    }

    $arr = array();
    $sql = "SELECT region_id, agency_id " .
            "FROM " . $GLOBALS['ecs']->table('region') .
            " WHERE region_id " . db_create_in($regions) .
            " AND region_id > 0 AND agency_id > 0";
    $res = $GLOBALS['db']->query($sql);
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $arr[$row['region_id']] = $row['agency_id'];
    }
    if (empty($arr))
    {
        return 0;
    }

    $agency_id = 0;
    for ($i = count($regions) - 1; $i >= 0; $i--)
    {
        if (isset($arr[$regions[$i]]))
        {
            return $arr[$regions[$i]];
        }
    }
}

/**
 * 获取配送插件的实例
 * @param   int   $shipping_id    配送插件ID
 * @return  object     配送插件对象实例
 */
function &get_shipping_object($shipping_id)
{
    $shipping  = shipping_info($shipping_id);
    if (!$shipping)
    {
        $object = new stdClass();
        return $object;
    }

    $file_path = ROOT_PATH.'includes/modules/shipping/' . $shipping['shipping_code'] . '.php';

    include_once($file_path);

    $object = new $shipping['shipping_code'];
    return $object;
}

/**
 * 改变订单中商品库存
 * @param   int     $order_id   订单号
 * @param   bool    $is_dec     是否减少库存
 * @param   bool    $storage     减库存的时机，1，下订单时；0，发货时；
 */
function change_order_goods_storage($order_id, $is_dec = true, $storage = 0)
{
    /* 查询订单商品信息 */
    switch ($storage)
    {
        case 0 :
            $sql = "SELECT goods_id, SUM(send_number) AS num, MAX(extension_code) AS extension_code, product_id FROM " . $GLOBALS['ecs']->table('order_goods') .
                    " WHERE order_id = '$order_id' AND is_real = 1 GROUP BY goods_id, product_id";
        break;

        case 1 :
            $sql = "SELECT goods_id, SUM(goods_number) AS num, MAX(extension_code) AS extension_code, product_id FROM " . $GLOBALS['ecs']->table('order_goods') .
                    " WHERE order_id = '$order_id' AND is_real = 1 GROUP BY goods_id, product_id";
        break;
    }

    $res = $GLOBALS['db']->query($sql);
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        if ($row['extension_code'] != "package_buy")
        {
            if ($is_dec)
            {
                change_goods_storage($row['goods_id'], $row['product_id'], - $row['num']);
            }
            else
            {
                change_goods_storage($row['goods_id'], $row['product_id'], $row['num']);
            }
            $GLOBALS['db']->query($sql);
        }
        else
        {
            $sql = "SELECT goods_id, goods_number" .
                   " FROM " . $GLOBALS['ecs']->table('package_goods') .
                   " WHERE package_id = '" . $row['goods_id'] . "'";
            $res_goods = $GLOBALS['db']->query($sql);
            while ($row_goods = $GLOBALS['db']->fetchRow($res_goods))
            {
                $sql = "SELECT is_real" .
                   " FROM " . $GLOBALS['ecs']->table('goods') .
                   " WHERE goods_id = '" . $row_goods['goods_id'] . "'";
                $real_goods = $GLOBALS['db']->query($sql);
                $is_goods = $GLOBALS['db']->fetchRow($real_goods);

                if ($is_dec)
                {
                    change_goods_storage($row_goods['goods_id'], $row['product_id'], - ($row['num'] * $row_goods['goods_number']));
                }
                elseif ($is_goods['is_real'])
                {
                    change_goods_storage($row_goods['goods_id'], $row['product_id'], ($row['num'] * $row_goods['goods_number']));
                }
            }
        }
    }

}

/**
 * 商品库存增与减 货品库存增与减
 *
 * @param   int    $good_id         商品ID
 * @param   int    $product_id      货品ID
 * @param   int    $number          增减数量，默认0；
 *
 * @return  bool               true，成功；false，失败；
 */
function change_goods_storage($good_id, $product_id, $number = 0)
{
    if ($number == 0)
    {
        return true; // 值为0即不做、增减操作，返回true
    }

    if (empty($good_id) || empty($number))
    {
        return false;
    }

    $number = ($number > 0) ? '+ ' . $number : $number;

    /* 处理货品库存 */
    $products_query = true;
    if (!empty($product_id))
    {
        $sql = "UPDATE " . $GLOBALS['ecs']->table('products') ."
                SET product_number = product_number $number
                WHERE goods_id = '$good_id'
                AND product_id = '$product_id'
                LIMIT 1";
        $products_query = $GLOBALS['db']->query($sql);
    }

    /* 处理商品库存 */
    $sql = "UPDATE " . $GLOBALS['ecs']->table('goods') ."
            SET goods_number = goods_number $number
            WHERE goods_id = '$good_id'
            LIMIT 1";
    $query = $GLOBALS['db']->query($sql);

    if ($query && $products_query)
    {
        return true;
    }
    else
    {
        return false;
    }
}

/**
 * 取得支付方式id列表
 * @param   bool    $is_cod 是否货到付款
 * @return  array
 */
function payment_id_list($is_cod)
{
    $sql = "SELECT pay_id FROM " . $GLOBALS['ecs']->table('payment');
    if ($is_cod)
    {
        $sql .= " WHERE is_cod = 1";
    }
    else
    {
        $sql .= " WHERE is_cod = 0";
    }

    return $GLOBALS['db']->getCol($sql);
}

/**
 * 生成查询订单的sql
 * @param   string  $type   类型
 * @param   string  $alias  order表的别名（包括.例如 o.）
 * @return  string
 */
function order_query_sql($type = 'finished', $alias = '')
{
    /* 已完成订单 */
    if ($type == 'finished')
    {
        return " AND {$alias}order_status " . db_create_in(array(OS_CONFIRMED, OS_SPLITED)) .
               " AND {$alias}shipping_status " . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) .
               " AND {$alias}pay_status " . db_create_in(array(PS_PAYED, PS_PAYING)) . " ";
    }
    /* 待发货订单 */
    elseif ($type == 'await_ship')
    {
        return " AND   {$alias}order_status " .
                 db_create_in(array(OS_CONFIRMED, OS_SPLITED, OS_SPLITING_PART)) .
               " AND   {$alias}shipping_status " .
                 db_create_in(array(SS_UNSHIPPED, SS_PREPARING, SS_SHIPPED_ING)) .
               " AND ( {$alias}pay_status " . db_create_in(array(PS_PAYED, PS_PAYING)) . " OR {$alias}pay_id " . db_create_in(payment_id_list(true)) . ") ";
    }
    /* 待付款订单 */
    elseif ($type == 'await_pay')
    {
        return " AND   {$alias}order_status " . db_create_in(array(OS_CONFIRMED, OS_SPLITED)) .
               " AND   {$alias}pay_status = '" . PS_UNPAYED . "'" .
               " AND ( {$alias}shipping_status " . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) . " OR {$alias}pay_id " . db_create_in(payment_id_list(false)) . ") ";
    }
    /* 未确认订单 */
    elseif ($type == 'unconfirmed')
    {
        return " AND {$alias}order_status = '" . OS_UNCONFIRMED . "' ";
    }
    /* 未处理订单：用户可操作 */
    elseif ($type == 'unprocessed')
    {
        return " AND {$alias}order_status " . db_create_in(array(OS_UNCONFIRMED, OS_CONFIRMED)) .
               " AND {$alias}shipping_status = '" . SS_UNSHIPPED . "'" .
               " AND {$alias}pay_status = '" . PS_UNPAYED . "' ";
    }
    /* 未付款未发货订单：管理员可操作 */
    elseif ($type == 'unpay_unship')
    {
        return " AND {$alias}order_status " . db_create_in(array(OS_UNCONFIRMED, OS_CONFIRMED)) .
               " AND {$alias}shipping_status " . db_create_in(array(SS_UNSHIPPED, SS_PREPARING)) .
               " AND {$alias}pay_status = '" . PS_UNPAYED . "' ";
    }
    /* 已发货订单：不论是否付款 */
    elseif ($type == 'shipped')
    {
        return " AND {$alias}order_status = '" . OS_CONFIRMED . "'" .
               " AND {$alias}shipping_status " . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) . " ";
    }
    else
    {
        die('函数 order_query_sql 参数错误');
    }
}

/**
 * 生成查询订单总金额的字段
 * @param   string  $alias  order表的别名（包括.例如 o.）
 * @return  string
 */
function order_amount_field($alias = '')
{
    return "   {$alias}goods_amount + {$alias}tax + {$alias}shipping_fee" .
           " + {$alias}insure_fee + {$alias}pay_fee + {$alias}pack_fee" .
           " + {$alias}card_fee ";
}

/**
 * 生成计算应付款金额的字段
 * @param   string  $alias  order表的别名（包括.例如 o.）
 * @return  string
 */
function order_due_field($alias = '')
{
    return order_amount_field($alias) .
            " - {$alias}money_paid - {$alias}surplus - {$alias}integral_money" .
            " - {$alias}bonus - {$alias}discount ";
}

/**
 * 计算折扣：根据购物车和优惠活动
 * @return  float   折扣
 *comment by zhangxi, 20150127, 折扣计算,增加输入参数
 */
function compute_discount($list_bx_policy=NULL, $gid=0)
{
    /* 查询优惠活动 */
    $now = time();
    $user_rank = ',' . $_SESSION['user_rank'] . ',';
    $sql = "SELECT *" .
            "FROM " . $GLOBALS['ecs']->table('favourable_activity') .
            " WHERE start_time <= '$now'" .
            " AND end_time >= '$now'" .
            " AND CONCAT(',', user_rank, ',') LIKE '%" . $user_rank . "%'" .
            " AND act_type " . db_create_in(array(FAT_DISCOUNT, FAT_PRICE));
    $favourable_list = $GLOBALS['db']->getAll($sql);
    if (!$favourable_list)
    {
    	ss_log(__FUNCTION__.": sql:".$sql);
    	ss_log(__FUNCTION__.": have no favourable_list, return");
        return 0;
    }
	//到这里，已经说明有当前时间范围的活动 
	if($gid == 0)
	{
		return 0;
	}
	
	
	 $sql = "SELECT *" .
            "FROM " . $GLOBALS['ecs']->table('goods') .
            " WHERE goods_id = '$gid'";
    $goods = $GLOBALS['db']->getRow($sql);

    /* 查询购物车商品 */
    //这里要进行修改了，改成从用户真正的订单数据来获取用户的商品是否打折
//    $sql = "SELECT c.goods_id, c.goods_price * c.goods_number AS subtotal, g.cat_id, g.brand_id " .
//            "FROM " . $GLOBALS['ecs']->table('cart') . " AS c, " . $GLOBALS['ecs']->table('goods') . " AS g " .
//            "WHERE c.goods_id = g.goods_id " .
//            "AND c.session_id = '" . SESS_ID . "' " .
//            "AND c.parent_id = 0 " .
//            "AND c.is_gift = 0 " .
//            "AND rec_type = '" . CART_GENERAL_GOODS . "'";
//     
//    $goods_list = $GLOBALS['db']->getAll($sql);
    //改成投保单信息的获取
    
    $policy_list =$list_bx_policy;

    //
    if (!$policy_list)
    {
    	ss_log(__FUNCTION__."cant get goods_list, discount=0");
        return 0;
    }

    /* 初始化折扣 */
    $discount = 0;
    $favourable_name = array();

    /* 循环计算每个优惠活动的折扣 */
    foreach ($favourable_list as $favourable)
    {
        $total_amount = 0;//comment by zhangxi, 20150127, 用户购买的商品中，符合 某一个优惠活动下的总的商品价格之和统计
        if ($favourable['act_range'] == FAR_ALL)
        {
            foreach ($policy_list as $policy)
            {
                $total_amount += $policy['total_premium'];
            }
        }
        elseif ($favourable['act_range'] == FAR_CATEGORY)
        {
            /* 找出分类id的子分类id */
            $id_list = array();
            $raw_id_list = explode(',', $favourable['act_range_ext']);
            foreach ($raw_id_list as $id)
            {
                $id_list = array_merge($id_list, array_keys(cat_list($id, 0, false)));
            }
            $ids = join(',', array_unique($id_list));

            foreach ($policy_list as $policy)
            {
                if (strpos(',' . $ids . ',', ',' . $goods['cat_id'] . ',') !== false)
                {
                    $total_amount += $policy['total_premium'];
                }
            }
        }
        elseif ($favourable['act_range'] == FAR_BRAND)
        {
            foreach ($policy_list as $policy)
            {
                if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $goods['brand_id'] . ',') !== false)
                {
                    $total_amount += $policy['total_premium'];
                }
            }
        }
        elseif ($favourable['act_range'] == FAR_GOODS)//商品
        {
            foreach ($policy_list as $policy)
            {
                if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $goods['goods_id'] . ',') !== false)
                {
                    $total_amount += $policy['total_premium'];
                }
            }
        }
        else
        {
            continue;
        }

        /* 如果金额满足条件，累计折扣 */
        ss_log(__FUNCTION__.": total_amount=".$total_amount);
        if ($total_amount > 0 && $total_amount >= $favourable['min_amount'] && ($total_amount <= $favourable['max_amount'] || $favourable['max_amount'] == 0))
        {
            if ($favourable['act_type'] == FAT_DISCOUNT)
            {
                $discount += $total_amount * (1 - $favourable['act_type_ext'] / 100);

                $favourable_name[] = $favourable['act_name'];
            }
            elseif ($favourable['act_type'] == FAT_PRICE)
            {
                $discount += $favourable['act_type_ext'];

                $favourable_name[] = $favourable['act_name'];
            }
        }
    }

    return array('discount' => $discount, 'name' => $favourable_name);
}

/**
 * 取得购物车该赠送的积分数
 * @return  int     积分数
 */
function get_give_integral()
{
        $sql = "SELECT SUM(c.goods_number * IF(g.give_integral > -1, g.give_integral, c.goods_price))" .
                "FROM " . $GLOBALS['ecs']->table('cart') . " AS c, " .
                          $GLOBALS['ecs']->table('goods') . " AS g " .
                "WHERE c.goods_id = g.goods_id " .
                "AND c.session_id = '" . SESS_ID . "' " .
                "AND c.goods_id > 0 " .
                "AND c.parent_id = 0 " .
                "AND c.rec_type = 0 " .
                "AND c.is_gift = 0";

        return intval($GLOBALS['db']->getOne($sql));
}

/**
 * 取得某订单应该赠送的积分数
 * @param   array   $order  订单
 * @return  int     积分数
 */
function integral_to_give($order)
{
    /* 判断是否团购 */
    if ($order['extension_code'] == 'group_buy')
    {
        include_once(ROOT_PATH . 'includes/lib_goods.php');
        $group_buy = group_buy_info(intval($order['extension_id']));

        return array('custom_points' => $group_buy['gift_integral'], 'rank_points' => $order['goods_amount']);
    }
    else
    {
        $sql = "SELECT SUM(og.goods_number * IF(g.give_integral > -1, g.give_integral, og.goods_price)) AS custom_points, SUM(og.goods_number * IF(g.rank_integral > -1, g.rank_integral, og.goods_price)) AS rank_points " .
                "FROM " . $GLOBALS['ecs']->table('order_goods') . " AS og, " .
                          $GLOBALS['ecs']->table('goods') . " AS g " .
                "WHERE og.goods_id = g.goods_id " .
                "AND og.order_id = '$order[order_id]' " .
                "AND og.goods_id > 0 " .
                "AND og.parent_id = 0 " .
                "AND og.is_gift = 0 AND og.extension_code != 'package_buy'";

        return $GLOBALS['db']->getRow($sql);
    }
}

/**
 * 发代金券：发货时发代金券
 * @param   int     $order_id   订单号
 * @return  bool
 */
function send_order_bonus($order_id)
{
    /* 取得订单应该发放的代金券 */
    $bonus_list = order_bonus($order_id);

    /* 如果有代金券，统计并发送 */
    if ($bonus_list)
    {
        /* 用户信息 */
        $sql = "SELECT u.user_id, u.user_name, u.email " .
                "FROM " . $GLOBALS['ecs']->table('order_info') . " AS o, " .
                          $GLOBALS['ecs']->table('users') . " AS u " .
                "WHERE o.order_id = '$order_id' " .
                "AND o.user_id = u.user_id ";
        $user = $GLOBALS['db']->getRow($sql);

        /* 统计 */
        $count = 0;
        $money = '';
        foreach ($bonus_list AS $bonus)
        {
            $count += $bonus['number'];
            $money .= price_format($bonus['type_money']) . ' [' . $bonus['number'] . '], ';

            /* 修改用户代金券 */
            $sql = "INSERT INTO " . $GLOBALS['ecs']->table('user_bonus') . " (bonus_type_id, user_id) " .
                    "VALUES('$bonus[type_id]', '$user[user_id]')";
            for ($i = 0; $i < $bonus['number']; $i++)
            {
                if (!$GLOBALS['db']->query($sql))
                {
                    return $GLOBALS['db']->errorMsg();
                }
            }
        }

        /* 如果有代金券，发送邮件 */
        if ($count > 0)
        {
            $tpl = get_mail_template('send_bonus');
            $GLOBALS['smarty']->assign('user_name', $user['user_name']);
            $GLOBALS['smarty']->assign('count', $count);
            $GLOBALS['smarty']->assign('money', $money);
            $GLOBALS['smarty']->assign('shop_name', $GLOBALS['_CFG']['shop_name']);
            $GLOBALS['smarty']->assign('send_date', local_date($GLOBALS['_CFG']['date_format']));
            $GLOBALS['smarty']->assign('sent_date', local_date($GLOBALS['_CFG']['date_format']));
            $content = $GLOBALS['smarty']->fetch('str:' . $tpl['template_content']);
            send_mail($user['user_name'], $user['email'], $tpl['template_subject'], $content, $tpl['is_html']);
        }
    }

    return true;
}

/**
 * 返回订单发放的代金券
 * @param   int     $order_id   订单id
 */
function return_order_bonus($order_id)
{
    /* 取得订单应该发放的代金券 */
    $bonus_list = order_bonus($order_id);

    /* 删除 */
    if ($bonus_list)
    {
        /* 取得订单信息 */
        $order = order_info($order_id);
        $user_id = $order['user_id'];

        foreach ($bonus_list AS $bonus)
        {
            $sql = "DELETE FROM " . $GLOBALS['ecs']->table('user_bonus') .
                    " WHERE bonus_type_id = '$bonus[type_id]' " .
                    "AND user_id = '$user_id' " .
                    "AND order_id = '0' LIMIT " . $bonus['number'];
            $GLOBALS['db']->query($sql);
        }
    }
}

/**
 * 取得订单应该发放的代金券
 * @param   int     $order_id   订单id
 * @return  array
 */
function order_bonus($order_id)
{
    /* 查询按商品发的代金券 */
    $day    = getdate();
    $today  = local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);

    $sql = "SELECT b.type_id, b.type_money, SUM(o.goods_number) AS number " .
            "FROM " . $GLOBALS['ecs']->table('order_goods') . " AS o, " .
                      $GLOBALS['ecs']->table('goods') . " AS g, " .
                      $GLOBALS['ecs']->table('bonus_type') . " AS b " .
            " WHERE o.order_id = '$order_id' " .
            " AND o.is_gift = 0 " .
            " AND o.goods_id = g.goods_id " .
            " AND g.bonus_type_id = b.type_id " .
            " AND b.send_type = '" . SEND_BY_GOODS . "' " .
            " AND b.send_start_date <= '$today' " .
            " AND b.send_end_date >= '$today' " .
            " GROUP BY b.type_id ";
    $list = $GLOBALS['db']->getAll($sql);

    /* 查询定单中非赠品总金额 */
    $amount = order_amount($order_id, false);

    /* 查询订单日期 */
    $sql = "SELECT add_time " .
            " FROM " . $GLOBALS['ecs']->table('order_info') .
            " WHERE order_id = '$order_id' LIMIT 1";
    $order_time = $GLOBALS['db']->getOne($sql);

    /* 查询按订单发的代金券 */
    $sql = "SELECT type_id, type_money, IFNULL(FLOOR('$amount' / min_amount), 1) AS number " .
            "FROM " . $GLOBALS['ecs']->table('bonus_type') .
            "WHERE send_type = '" . SEND_BY_ORDER . "' " .
            "AND send_start_date <= '$order_time' " .
            "AND send_end_date >= '$order_time' ";
    $list = array_merge($list, $GLOBALS['db']->getAll($sql));

    return $list;
}

/**
 * 计算购物车中的商品能享受代金券支付的总额
 * @return  float   享受代金券支付的总额
 */
function compute_discount_amount()
{
    /* 查询优惠活动 */
    $now = time();
    $user_rank = ',' . $_SESSION['user_rank'] . ',';
    $sql = "SELECT *" .
            "FROM " . $GLOBALS['ecs']->table('favourable_activity') .
            " WHERE start_time <= '$now'" .
            " AND end_time >= '$now'" .
            " AND CONCAT(',', user_rank, ',') LIKE '%" . $user_rank . "%'" .
            " AND act_type " . db_create_in(array(FAT_DISCOUNT, FAT_PRICE));
    $favourable_list = $GLOBALS['db']->getAll($sql);
    if (!$favourable_list)
    {
        return 0;
    }

    /* 查询购物车商品 */
    $sql = "SELECT c.goods_id, c.goods_price * c.goods_number AS subtotal, g.cat_id, g.brand_id " .
            "FROM " . $GLOBALS['ecs']->table('cart') . " AS c, " . $GLOBALS['ecs']->table('goods') . " AS g " .
            "WHERE c.goods_id = g.goods_id " .
            "AND c.session_id = '" . SESS_ID . "' " .
            "AND c.parent_id = 0 " .
            "AND c.is_gift = 0 " .
            "AND rec_type = '" . CART_GENERAL_GOODS . "'";
    $goods_list = $GLOBALS['db']->getAll($sql);
    if (!$goods_list)
    {
        return 0;
    }

    /* 初始化折扣 */
    $discount = 0;
    $favourable_name = array();

    /* 循环计算每个优惠活动的折扣 */
    //comment by zhangxi, 20150126, $discount是计算出本次购物总的折扣数量
    foreach ($favourable_list as $favourable)
    {
        $total_amount = 0;
        if ($favourable['act_range'] == FAR_ALL)
        {
            foreach ($goods_list as $goods)
            {
                $total_amount += $goods['subtotal'];
            }
        }
        elseif ($favourable['act_range'] == FAR_CATEGORY)
        {
            /* 找出分类id的子分类id */
            $id_list = array();
            $raw_id_list = explode(',', $favourable['act_range_ext']);
            foreach ($raw_id_list as $id)
            {
                $id_list = array_merge($id_list, array_keys(cat_list($id, 0, false)));
            }
            $ids = join(',', array_unique($id_list));

            foreach ($goods_list as $goods)
            {
                if (strpos(',' . $ids . ',', ',' . $goods['cat_id'] . ',') !== false)
                {
                    $total_amount += $goods['subtotal'];
                }
            }
        }
        elseif ($favourable['act_range'] == FAR_BRAND)//zhangxi, 指定品牌情况
        {
            foreach ($goods_list as $goods)
            {
                if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $goods['brand_id'] . ',') !== false)
                {
                    $total_amount += $goods['subtotal'];
                }
            }
        }
        elseif ($favourable['act_range'] == FAR_GOODS)//zhangxi, 指定商品范围的折扣
        {
            foreach ($goods_list as $goods)
            {
                if (strpos(',' . $favourable['act_range_ext'] . ',', ',' . $goods['goods_id'] . ',') !== false)
                {
                    $total_amount += $goods['subtotal'];
                }
            }
        }
        else
        {
            continue;
        }
        if ($total_amount > 0 && $total_amount >= $favourable['min_amount'] && ($total_amount <= $favourable['max_amount'] || $favourable['max_amount'] == 0))
        {
        	//comment by zhangxi, 20150126, 比例折扣
            if ($favourable['act_type'] == FAT_DISCOUNT)
            {
                $discount += $total_amount * (1 - $favourable['act_type_ext'] / 100);
            }
            //指定具体减免数值
            elseif ($favourable['act_type'] == FAT_PRICE)
            {
                $discount += $favourable['act_type_ext'];
            }
        }
    }


    return $discount;
}

/**
 * 添加礼包到购物车
 *
 * @access  public
 * @param   integer $package_id   礼包编号
 * @param   integer $num          礼包数量
 * @return  boolean
 */
function add_package_to_cart($package_id, $num = 1)
{
    $GLOBALS['err']->clean();

    /* 取得礼包信息 */
    $package = get_package_info($package_id);

    if (empty($package))
    {
        $GLOBALS['err']->add($GLOBALS['_LANG']['goods_not_exists'], ERR_NOT_EXISTS);

        return false;
    }

    /* 是否正在销售 */
    if ($package['is_on_sale'] == 0)
    {
        $GLOBALS['err']->add($GLOBALS['_LANG']['not_on_sale'], ERR_NOT_ON_SALE);

        return false;
    }

    /* 现有库存是否还能凑齐一个礼包 */
    if ($GLOBALS['_CFG']['use_storage'] == '1' && judge_package_stock($package_id))
    {
        $GLOBALS['err']->add(sprintf($GLOBALS['_LANG']['shortage'], 1), ERR_OUT_OF_STOCK);

        return false;
    }

    /* 检查库存 */
//    if ($GLOBALS['_CFG']['use_storage'] == 1 && $num > $package['goods_number'])
//    {
//        $num = $goods['goods_number'];
//        $GLOBALS['err']->add(sprintf($GLOBALS['_LANG']['shortage'], $num), ERR_OUT_OF_STOCK);
//
//        return false;
//    }

    /* 初始化要插入购物车的基本件数据 */
    $parent = array(
        'user_id'       => $_SESSION['user_id'],
        'session_id'    => SESS_ID,
        'goods_id'      => $package_id,
        'goods_sn'      => '',
        'goods_name'    => addslashes($package['package_name']),
        'market_price'  => $package['market_package'],
        'goods_price'   => $package['package_price'],
        'goods_number'  => $num,
        'goods_attr'    => '',
        'goods_attr_id' => '',
        'is_real'       => $package['is_real'],
        'extension_code'=> 'package_buy',
        'is_gift'       => 0,
        'rec_type'      => CART_GENERAL_GOODS
    );

    /* 如果数量不为0，作为基本件插入 */
    if ($num > 0)
    {
         /* 检查该商品是否已经存在在购物车中 */
        $sql = "SELECT goods_number FROM " .$GLOBALS['ecs']->table('cart').
                " WHERE session_id = '" .SESS_ID. "' AND goods_id = '" . $package_id . "' ".
                " AND parent_id = 0 AND extension_code = 'package_buy' " .
                " AND rec_type = '" . CART_GENERAL_GOODS . "'";

        $row = $GLOBALS['db']->getRow($sql);

        if($row) //如果购物车已经有此物品，则更新
        {
            $num += $row['goods_number'];
            if ($GLOBALS['_CFG']['use_storage'] == 0 || $num > 0)
            {
                $sql = "UPDATE " . $GLOBALS['ecs']->table('cart') . " SET goods_number = '" . $num . "'" .
                       " WHERE session_id = '" .SESS_ID. "' AND goods_id = '$package_id' ".
                       " AND parent_id = 0 AND extension_code = 'package_buy' " .
                       " AND rec_type = '" . CART_GENERAL_GOODS . "'";
                $GLOBALS['db']->query($sql);
            }
            else
            {
                $GLOBALS['err']->add(sprintf($GLOBALS['_LANG']['shortage'], $num), ERR_OUT_OF_STOCK);
                return false;
            }
        }
        else //购物车没有此物品，则插入
        {
            $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('cart'), $parent, 'INSERT');
        }
    }

    /* 把赠品删除 */
    $sql = "DELETE FROM " . $GLOBALS['ecs']->table('cart') . " WHERE session_id = '" . SESS_ID . "' AND is_gift <> 0";
    $GLOBALS['db']->query($sql);

    return true;
}

/**
 * 得到新发货单号
 * @return  string
 */
function get_delivery_sn()
{
    /* 选择一个随机的方案 */
    mt_srand((double) microtime() * 1000000);

    return date('YmdHi') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

/**
 * 检查礼包内商品的库存
 * @return  boolen
 */
function judge_package_stock($package_id, $package_num = 1)
{
    $sql = "SELECT goods_id, product_id, goods_number
            FROM " . $GLOBALS['ecs']->table('package_goods') . "
            WHERE package_id = '" . $package_id . "'";
    $row = $GLOBALS['db']->getAll($sql);
    if (empty($row))
    {
        return true;
    }

    /* 分离货品与商品 */
    $goods = array('product_ids' => '', 'goods_ids' => '');
    foreach ($row as $value)
    {
        if ($value['product_id'] > 0)
        {
            $goods['product_ids'] .= ',' . $value['product_id'];
            continue;
        }

        $goods['goods_ids'] .= ',' . $value['goods_id'];
    }

    /* 检查货品库存 */
    if ($goods['product_ids'] != '')
    {
        $sql = "SELECT p.product_id
                FROM " . $GLOBALS['ecs']->table('products') . " AS p, " . $GLOBALS['ecs']->table('package_goods') . " AS pg
                WHERE pg.product_id = p.product_id
                AND pg.package_id = '$package_id'
                AND pg.goods_number * $package_num > p.product_number
                AND p.product_id IN (" . trim($goods['product_ids'], ',') . ")";
        $row = $GLOBALS['db']->getAll($sql);

        if (!empty($row))
        {
            return true;
        }
    }

    /* 检查商品库存 */
    if ($goods['goods_ids'] != '')
    {
        $sql = "SELECT g.goods_id
                FROM " . $GLOBALS['ecs']->table('goods') . "AS g, " . $GLOBALS['ecs']->table('package_goods') . " AS pg
                WHERE pg.goods_id = g.goods_id
                AND pg.goods_number * $package_num > g.goods_number
                AND pg.package_id = '" . $package_id . "'
                AND pg.goods_id IN (" . trim($goods['goods_ids'], ',') . ")";
        $row = $GLOBALS['db']->getAll($sql);

        if (!empty($row))
        {
            return true;
        }
    }

    return false;
}



/**
 * 注销保单，要修改的订单信息
 * 2014-09-23 yes123
 * 
 * $other_parm  其他参数  
 */
function withdraw_order($policy_id,$other_parm = array())
{
	
	ss_log("注销订单，into function withdraw_order");
	
	
	//1.查询保单总额
	
	
	//add yes123 2016-05-09 高危漏洞，不能注销他人保单
	$where_sql = " WHERE policy_id='$policy_id'";
	if(!$_SESSION['admin_id'])
	{
		$where_sql.=" AND agent_uid='$_SESSION[user_id]' ";
		
	}
	
	$sql="SELECT * FROM t_insurance_policy $where_sql";
	ss_log($sql);
	$insurance_policy  = $GLOBALS['db']->getRow($sql);
	
	if(empty($insurance_policy))
	{
		return false;
		
	}
	
	$order_id = $insurance_policy['order_id'];
	//查询订单
	$sql="SELECT * FROM " . $GLOBALS['ecs']->table('order_info') . " WHERE order_id=".$order_id;
	ss_log($sql);
	$order_info  = $GLOBALS['db']->getRow($sql);

	ss_log("order_status: ".$order_info['order_status']);
	ss_log("pay_status: ".$order_info['pay_status']);
	ss_log("fee_assigned: ".$order_info['fee_assigned']);

	//如果是一个订单对应多个保单，则不能这样操作了。
	if($order_info['order_status']==1 && $order_info['pay_status']==2) //订单状态是已支付，已确认
	{
		//modify yes123 fee_assigned字段不用了，不同类型佣金都有自己的是否分配字段
		if($order_info['fee_assigned']==1){}//add by wangcya , 20141012,订单已经分配了佣金
		
		$agent_uid = $order_info['user_id'];//$insurance_policy['agent_uid'];
		//$agent_uid = $order_info['goods_amount'];//？？？？
		
		//add yes123 2015-05-07 如果是C端的单子，不能退款，只能退服务费
		ss_log("insurance_policy['client_id']:".$insurance_policy['client_id']);
		if(!$insurance_policy['client_id'])
		{
			
			//如果用了红包或者金币
			if ($order_info['bonus_id'] > 0 || $order_info['service_money'] > 0)
		    {
		        return_policy_premium($order_info,$insurance_policy);
		    }
			else
			{
				//2. 把该保单的保费退还到代理人的余额中
				ss_log("把该保单对应的保费退还到代理人的余额中， policy_id：".$policy_id);
				//log_account_change2($agent_uid, $insurance_policy['total_premium'], $agent_uid,"",0, 0, 0,"退保","",$order_info['order_sn'],$policy_id);
				
				
				$return_money = $insurance_policy['total_premium'];
				$withdraw_money_type = isset($other_parm['withdraw_money_type'])?$other_parm['withdraw_money_type']:1;
				$withdraw_type = isset($other_parm['type'])?$other_parm['type']:'canceled';
				
				if($withdraw_money_type==2)
				{
					$return_money = isset($other_parm['withdraw_money'])?$other_parm['withdraw_money']:0;
					$return_money = $return_money>$insurance_policy['total_premium']?$insurance_policy['total_premium']:$return_money;
					
				}
				
				if($return_money>0)
				{
					if($withdraw_type=='canceled')
					{
						$change_desc = "注销";
						
					}
					else if($withdraw_type=='surrender')
					{
						$change_desc = "退保";
						
					}
					
					
					//判断是不是春秋积分支付
					$pay_id = $order_info['pay_id'];
					$pyament = get_payment_byid($pay_id);
					if($pyament['pay_code']=='springpass')
					{
						springpass_refund($policy_id,$order_info);
					}
					else
					{
						log_account_change($agent_uid,$return_money,
											0,0,0,$change_desc,0,$order_info['order_sn'],
											'',0,$policy_id,'','user_money');
					}
				}
				update_refund_amount($insurance_policy['total_premium'],$order_info['order_id']);
			}
			
		}
		else //add yes123 2015-05-08 打标记
		{
			$sql="UPDATE t_insurance_policy SET manual_refund='yes' WHERE policy_id='$policy_id'";
			ss_log(__FUNCTION__.",标识需要手工退款：".$sql);
			$GLOBALS['db']->query($sql);
					
		}
		
		//4: 扣回佣金到我们网站账户，一定先退保费，然后扣回佣金
		ss_log("扣回佣金到我们网站账户，一定先退保费，然后扣回佣金。function withdraw_fee");
		$ret = withdraw_fee($policy_id);//把代理人本人和推荐人的服务费都拿回到网站账户。
		if($ret)//只有扣回服务费成功，才能更新该状态
		{
			ss_log("withdraw_fee success!");
			
		}
		else
		{
			ss_log("withdraw_fee fail!");
		}
		
		
		
		//退保一个则在订单上计数,注销数量增加1，成功注销数量减少1
		$sql="UPDATE " . $GLOBALS['ecs']->table('order_info') . " SET withdrawed_policy_num=withdrawed_policy_num+1 WHERE order_id=".$order_id;
		ss_log($sql);
		$GLOBALS['db']->query($sql);
					
		//start add by wangcya, 20150325 , 注销后，把成功投保的个数减少1
		if($order_info['insured_policy_num']>0)
		{
			$sql="UPDATE " . $GLOBALS['ecs']->table('order_info') . " SET insured_policy_num=insured_policy_num-1 WHERE order_id=".$order_id;
			ss_log($sql);
			$GLOBALS['db']->query($sql);
		}
		//end start add by wangcya, 20150325
		
		
		
		
		//////////////////////////////先查找出该订单下已经被注销的保单个数////////////////////////////////////////////////
		$sql="SELECT * FROM " . $GLOBALS['ecs']->table('order_info') . " WHERE order_id=".$order_id;
		ss_log($sql);
		$order_info  = $GLOBALS['db']->getRow($sql);
		
		ss_log("order policy_num: ".$order_info['policy_num']);
		ss_log("order withdrawed_policy_num: ".$order_info['withdrawed_policy_num']);
		
		//只有订单退保全部完毕，才能统一处理。
		if($order_info['policy_num'] == $order_info['withdrawed_policy_num'])
		{
			if($order_info['fee_assigned']==1)
			{//只有分配了佣金的，才更改佣金分配状态
				ss_log("更新订单状态为佣金未分配");
				$sql="UPDATE " . $GLOBALS['ecs']->table('order_info') . " SET fee_assigned=0 WHERE order_id=".$order_id;
				$GLOBALS['db']->query($sql);
			}
			
			//3.钱还了，就要修改订单状态为退保，未支付。
			ss_log("钱还了，就要修改订单状态为退保，未支付。");
			quxiao_order($order_info);

		}
	}
	
	ss_log("return from withdraw_order");
	
	return;
	
	
}




/**
 * 取消“投保单”，当有部分保单投保失败的时候，可以用此函数，取消保单，退换此保单的保费，和相应服务费
 * 2015-08-03 yes123
 */
function cancel_policy($policy_id)
{
	
	ss_log("注销订单，into function withdraw_order");
	//1.查询保单总额
	$sql="SELECT * FROM t_insurance_policy WHERE policy_id=".$policy_id;
	ss_log($sql);
	$insurance_policy  = $GLOBALS['db']->getRow($sql);
	
	$order_id = $insurance_policy['order_id'];
	$total_premium = $insurance_policy['total_premium'];
	
	//查询订单
	$sql="SELECT * FROM " . $GLOBALS['ecs']->table('order_info') . " WHERE order_id=".$order_id;
	ss_log($sql);
	$order_info  = $GLOBALS['db']->getRow($sql);

	ss_log("order_status: ".$order_info['order_status']);
	ss_log("pay_status: ".$order_info['pay_status']);
	ss_log("fee_assigned: ".$order_info['fee_assigned']);

	//如果是一个订单对应多个保单，则不能这样操作了。
	if($order_info['order_status']==1 && $order_info['pay_status']==2) //订单状态是已支付，已确认
	{
		$agent_uid = $order_info['user_id'];
	
		//已经投保成功的保单不能取消
		if($insurance_policy['policy_status']!='payed') 
		{
			return array('flag'=>false,'msg'=>"保单状态为：".$insurance_policy['policy_status'].",不能取消");
		}
		
			
		
		//add yes123 2015-05-07 如果是C端的单子，不能退款，只能退服务费
		ss_log("insurance_policy['client_id']:".$insurance_policy['client_id']);
		if(!$insurance_policy['client_id'])
		{
			
			if ($order_info['bonus_id'] > 0 || $order_info['service_money'] > 0 )
		    {
				return_policy_premium($order_info,$insurance_policy);      
		    }
			else
			{
				//2. 把该保单的保费退还到代理人的余额中
				ss_log("把该保单对应的保费退还到代理人的余额中， policy_id：".$policy_id);
				log_account_change($agent_uid,$total_premium,
									0,0,0,'取消投保单',0,$order_info['order_sn'],
									'',0,$policy_id,'','user_money');
									
				update_refund_amount($insurance_policy['total_premium'],$order_info['order_id']);
			}
			
		}
		else //add yes123 2015-05-08 打标记
		{
			$sql="UPDATE t_insurance_policy SET manual_refund='yes' WHERE policy_id='$policy_id'";
			ss_log(__FUNCTION__.",标识需要手工退款：".$sql);
			$GLOBALS['db']->query($sql);
					
		}
		
		//4: 扣回佣金到我们网站账户，一定先退保费，然后扣回佣金
		ss_log("扣回佣金到我们网站账户，一定先退保费，然后扣回佣金。function withdraw_fee");
		$ret = withdraw_fee($policy_id);//把代理人本人和推荐人的服务费都拿回到网站账户。
		if($ret)//只有扣回服务费成功，才能更新该状态
		{
			ss_log("withdraw_fee success!");
			
		}
		else
		{
			ss_log("withdraw_fee fail!");
			return array('flag'=>false,'msg'=>"服务费退回失败");
			
		}
		
		//取消完毕后，把保单数减一
		if($order_info['policy_num']==1) //如果等于1，修改订单状态为取消
		{
			quxiao_order($order_info);
			
		}
		else if($order_info['policy_num']>1)
		{
			$sql="UPDATE " . $GLOBALS['ecs']->table('order_info') . " SET goods_amount=goods_amount-$total_premium, policy_num=policy_num-1 WHERE order_id=".$order_id;
			ss_log("取消保单，保单个数-1：".$sql);
			$GLOBALS['db']->query($sql);
			
			//查询是否用余额支付
			$sql="SELECT pay_code FROM " . $GLOBALS['ecs']->table('payment') . " WHERE pay_id='$order_info[pay_id]'";
			ss_log('获取pay_code');
			$pay_code  = $GLOBALS['db']->getOne($sql);
			if($pay_code=='balance')
			{
				ss_log("surplus:".$order_info['surplus']);
				if($order_info['surplus']>=$total_premium)
				{
					$sql="UPDATE " . $GLOBALS['ecs']->table('order_info') . " SET surplus=surplus-$total_premium WHERE order_id=".$order_id;
					ss_log("取消保单，余额支付数据更新：".$sql);
					$GLOBALS['db']->query($sql);
					
				}
			}
			
		}
	}
	else
	{
		return array('flag'=>false,'msg'=>"订单状态未支付或者未确认,不能取消");
		
	}
	ss_log("return from withdraw_order");
	
	return array('flag'=>true,'msg'=>"ok");
	
	
}


/*注销后，重新进行投保*/
function re_insure($policy_id)
{
	ss_log("into function : ".__FUNCTION__);

	$sql="select order_id,order_sn,goods_amount,user_id,order_status from ". $GLOBALS['ecs']->table('order_info') ."  WHERE policy_id='$policy_id'";
	ss_log($sql);
	
	$order_info = $GLOBALS['db']->getRow($sql);
	
	$order_id = $order_info['order_id'];
	$order_sn = $order_info['order_sn'];
	$agent_uid = $order_info['user_id'];
	$goods_amount = $order_info['goods_amount'];
	$order_status = $order_info['order_status'];
	
	ss_log("order_id: ".$order_id);
	ss_log("order_sn: ".$order_sn);
	ss_log("agent_uid: ".$agent_uid);
	ss_log("goods_amount: ".$goods_amount);
	ss_log("order_status: ".$order_status);
	
	//////////////查找用余额是否够用///////////////////////////
	$sql="select user_money from ". $GLOBALS['ecs']->table('users') ."  WHERE user_id='$agent_uid'";
	ss_log($sql);
	
	$user_money = $GLOBALS['db']->getOne($sql);
	
	if($user_money<$goods_amount)//钱不够运用了
	{
		$retcode = 110;
		$retmsg = "用户余额不够运用了,user_id: ".$agent_uid." policy_id: ".$policy_id;
		ss_log($retmsg);
		
		$result_attr = array();
		$result_attr['retcode'] = $retcode;
		$result_attr['retmsg'] = $retmsg;
		
		return $result_attr;
	}
		
	/////////////////////////////////////////////////////////////////////////
	
	if($order_id)//&& $order_status==OS_RETURNED)//只有是订单状态是已退货，才执行
	{
			
		/////////////////////////////////////////////////
		$sql="SELECT * FROM t_insurance_policy WHERE policy_id=".$policy_id;
		$insurance_policy  = $GLOBALS['db']->getRow($sql);
		//////////////////////////////////////////////////////////////////////////////////
		$policy_no = $insurance_policy['policy_no'];
		ss_log("重新投保,policy_id: ".$policy_id." order_sn：".$order_sn." ,之前老的保单号为：".$policy_no);
		
		/////////////////先进行投保，根据投保的结果决定下面的保费扣除和佣金返回////////////////////////////////////////////////////
		ss_log("in re_insure， will execute function post_policy, policy_id: ".$policy_id);
		$result_attr = post_policy($policy_id,1);//要求重新产生流水号
		$result = $result_attr['retcode'];
		$retmsg = $result_attr['retmsg'];
		
		if($result!=0)
		{
			$retcode = 110;
			$retmsg = "post_policy fail ！ 返回，不进行佣金和保费扣除了,原因：".$retmsg;
			ss_log($retmsg);
				
			$result_attr = array();
			$result_attr['retcode'] = $retcode;
			$result_attr['retmsg'] = $retmsg;
				
			return $result_attr;
		}
	
			
		///////////////////////////////////////////////////////////////
		$total_premium = $insurance_policy['total_premium'];
		$goods_amount = $order_info['goods_amount'];//总价格
		
		ss_log("goods_amount: ".$goods_amount);
		ss_log("total_premium: ".$total_premium);
		
		if($total_premium != $goods_amount)
		{
			ss_log("total_premium!=goods_amount, order_id: ".$order_id." order_sn: ".$order_sn." policy_id: ".$policy_id);
		}
		
		$price = $total_premium?$total_premium:$goods_amount;
		///////////////////////////////////////////////////////////////////////////////
		
		$money = $price;
		
		//PS_PAYED , order_status
		ss_log("从代理人的余额中扣除保费,agent_uid: ".$agent_uid);
		
		//log_account_change2($agent_uid, -$goods_amount, $insurance_policy['applicant_username'],"",0, 0, 0,"退保","",$order_info['order_sn']);
		log_account_change2($agent_uid, "-".$money,"","",0, 0, 0,"扣除保费".$money, ACT_ADJUSTING,$order_info['order_sn']);
		
	
		ss_log("admin将要修改订单的状态为已支付,订单号：".$order_sn." order_id: ".$order_id);
	
		$pay_status = PS_PAYED;
		//$order_status= OS_CONFIRMED;
		$shipping_status= SS_SHIPPED;
		/* 修改订单状态为已付款 */
		$sql = 'UPDATE ' . $GLOBALS['ecs']->table('order_info') .
		" SET order_status = '" . OS_CONFIRMED . "', " .
		" confirm_time = '" . time() . "', " .
		" pay_status = '$pay_status', " .
		" shipping_status = '$shipping_status', " .
		" pay_time = '".time()."', " .
		" money_paid = order_amount," .
		" order_amount = 0 ".
		"WHERE order_id = '$order_id'";
	
		ss_log($sql);
	
		$GLOBALS['db']->query($sql);
		///////////////////////////////////////////////////////////////////////////////
		ss_log("from reinsure, into assign_commision_and_post_policy");
		//mod by zhangxi, 20150305, 修改函数名,分配佣金和投保
		//$result_attr = yongJin2($order_sn,0);//这里就不进行投保了
		$result_attr = assign_commision_and_post_policy($order_sn,0);
		
	}
	else
	{
		
		$retcode = 110;
		$retmsg = "没找到该订单或者订单状态不是退货,policy_id: ".$policy_id." order_status: ".$order_status;
		ss_log($retmsg);
		
		$result_attr = array();
		$result_attr['retcode'] = $retcode;
		$result_attr['retmsg'] = $retmsg;
		
		
	}
	
	return $result_attr;
		
}


//added by zhangxi, 20150424, 看佣金率是否需要动态更新
function update_rate_of_commision($org_rate, 
									$order_sn, 
									$insurer_code, 
									$attribute_type)
{
	ss_log(__FUNCTION__.", org_rate=".$org_rate.", order_sn=".$order_sn.", insurer_code=".$insurer_code.", attribute_type=".$attribute_type);
	if($insurer_code == 'NCI')
	{
		if($attribute_type == 'shaoer')
		{
			ss_log(__FUNCTION__.", xinhua shaoer process");
			$org_rate = update_rate_of_commision_xinhua_shaoer($org_rate, $order_sn);
		}
	}
	ss_log(__FUNCTION__.", after transfer, org_rate=".$org_rate);
	return $org_rate;
}

//added by zhangxi, 20150305, 这里只根据订单进行佣金的分配，如果已经分配过佣金，则直接返回
/*function assign_commision_by_order($order_sn, $order_info)
{
	ss_log("准备分配佣金函数，into function: ".__FUNCTION__);
	
	require_once(ROOT_PATH . 'languages/zh_cn/admin/account_log.php');
	//global $_LANG;
	ss_log(__FUNCTION__.", ROOT_PATH=".ROOT_PATH);
	$order_id = $order_info['order_id'];	
	
	$fee_assigned = intval($order_info['fee_assigned']);
	if($fee_assigned)//如果已经分配了佣金，则不要再次分配了
	{
		$retcode = 0;
		$retmsg = "该订单的佣金已经分配，fee have already assigned!，所以返回";
		ss_log(__FUNCTION__.", ".$retmsg);

		$result_attr['retcode'] = $retcode;
		$result_attr['retmsg']  = $retmsg;
		return $result_attr;
	}
	
	ss_log(__FUNCTION__.":该订单的佣金还没分配，准备给用户分配佣金! order_sn: ".$order_sn);
	
	$sql="SELECT * FROM t_insurance_policy WHERE order_id=".$order_id;//add by wangcya, for bug[193],能够支持多人批量投保，
	ss_log($sql);
	$list_insurance_policy  = $GLOBALS['db']->getAll($sql);
	$product_attribute_id = $list_insurance_policy[0]['attribute_id'];
	
	//comment by zhangxi , 20150306, 通过订单计算出来的投保单总保费
	$sql="SELECT SUM(total_premium) as total_premium_mutil FROM t_insurance_policy WHERE order_id=".$order_id;
	$total_premium_mutil  = $GLOBALS['db']->getOne($sql);
	ss_log(__FUNCTION__.":total_premium_mutil: ".$total_premium_mutil);
	
	$total_premium = $total_premium_mutil;//modify by wangcya, for bug[193],能够支持多人批量投保，
	//comment by zhangxi, 20150306, 订单中直接记录的订单商品价格。
	$goods_amount = $order_info['goods_amount'];//总价格
	
	ss_log(__FUNCTION__."，product_attribute_id: ".$product_attribute_id);
	ss_log(__FUNCTION__."，goods_amount: ".$goods_amount);
	ss_log(__FUNCTION__."，total_premium: ".$total_premium);
	
	//comment by zhangxi, 20150205, 这里不一定相等吧？产品有了折扣以后，
	//不相等的情况应该是一定的。
	if($total_premium != $goods_amount)
	{
		ss_log(__FUNCTION__.",warning：total_premium!=goods_amount, order_id: ".$order_id." order_sn: ".$order_sn);
	}
	
	$price = $total_premium?$total_premium:$goods_amount;
	///////////////////////////////////////////////////////////////////////////////
	
	
	//1.判断是否通过审核的用户
	//$sql="select is_cheack,distributor_id,parent_id from ". $GLOBALS['ecs']->table('users') ."  WHERE user_id=".$order_info['user_id'];
	$sql="select user_id,user_name,real_name,is_cheack,is_disable,institution_id,parent_id,mobile_phone from ". $GLOBALS['ecs']->table('users') ."  WHERE user_id=".$order_info['user_id'];
	ss_log($sql);
	$user=$GLOBALS['db']->getRow($sql);
	
	
	if($user['institution_id']==JILIN_INSTITUTION_ID && $user['is_cheack']!=1)
	{
		$retcode = 0;
		$retmsg = "吉林用户，未审核通过! user_id: ".$order_info['user_id'];
		ss_log($retmsg);
		
		$result_attr['retcode'] = $retcode;
		$result_attr['retmsg'] = $retmsg;
		return $result_attr;
	}
	
	
	//add yes123 2015-03-20 缓存各种费率。佣金分配完毕后，把费率保存到保单中，以便退保时，退佣金
	$rate_myself=0;
	$rate_recommend=0;
	$rate_organization=0;
	
	//add yes123 2015-03-26  缓存赠送的费用，用来判断是否该发短信
	$user_money_parent=0;
	$user_money_organization=0;
	$user_money_myself=0;
	
	
	if($user['is_cheack']==1&&!$user['is_disable'])//正式用户并且没被禁用。
	{
		ss_log(__FUNCTION__." 准备给正式用户分配佣金!");
		
		
		//获取产品的各种佣金比例
		//$sql = "SELECT rate_myself,rate_organization,rate_recommend FROM t_insurance_product_attribute WHERE attribute_id IN(" .
		//		"SELECT attribute_id FROM t_insurance_product_base WHERE product_id=".$product_id.")";
		//$sql = "SELECT rate_myself,rate_organization,rate_recommend FROM t_insurance_product_attribute WHERE attribute_id =".$product_attribute_id;
		$sql = "SELECT rate_myself,rate_organization,rate_recommend,insurer_code,attribute_type FROM t_insurance_product_attribute WHERE attribute_id =".$product_attribute_id;
		ss_log(__FUNCTION__.", ".$sql);
		$blArr=$GLOBALS['db']->getRow($sql);
		
		
		$blArr = get_rate_by_policy($list_insurance_policy[0]);	
		
		
		
		//added by zhangxi, 需要通过产品险种来区分，有的险种佣金率也不是固定不变的，而是有影响因子的
		$blArr['rate_myself'] = update_rate_of_commision($blArr['rate_myself'], 
															$order_sn,
															$blArr['insurer_code'],
															$blArr['attribute_type']
															);
		//个人服务费收入
		if($blArr['rate_myself']>0)
		{
				
			//echo "user_id: ".$order_info['user_id']." price: ".$price." rate_myself: ".$blArr['rate_myself']."</br>";
				
			$change_desc =$_LANG['ge_ren'];
			$user_money_myself     = $price*$blArr['rate_myself']/100;
				
			ss_log(__FUNCTION__." 给代理人自己分配佣金! user_id: ".$order_info['user_id']." user_money: ".$user_money_myself);
			// 保存   2014-10-9  edit yes123  由于，后台要显示收入来源的姓名和用户名，所以日志里存放user_id,比较方便获取
			//log_account_change2($order_info['user_id'], $user_money,$user_name['real_name'],$_LANG['ge_ren'] ,0, 0, 0, $change_desc, ACT_ADJUSTING,$order_sn);
			ss_log(__FUNCTION__." before log_account_change2");
				
			log_account_change2($order_info['user_id'], $user_money_myself,$order_info['user_id'],$_LANG['ge_ren'] ,0, 0, 0, $change_desc, ACT_ADJUSTING,$order_sn);
		
			ss_log(__FUNCTION__." after log_account_change2, change_desc=".$change_desc);
			$rate_myself = $blArr['rate_myself'];//add yes123 2015-03-20 缓存费率。佣金分配完毕后，把费率保存到保单中，以便退保时，退佣金
		}
		else
		{
			ss_log(__FUNCTION__." 本人的佣金率为: rate_myself: ".$blArr['rate_myself']);
		}
		
		
		/////////////////////////////////////////////////////////////////////////////////////
		
				
		ss_log(__FUNCTION__." 准备给介绍人分配佣金，rate_recommend: ".$blArr['rate_recommend']);
		//if($blArr['rate_recommend']>0&&$user['parent_id']>0)
		if($blArr['rate_recommend']>0&&$user['parent_id']>0 && $user['parent_id']!=$user['user_id'])
		{//非正式会员也能拿到佣金，但是不能提现，所以这里不必判断是否是正式会员。
			//处理介绍人佣金
				
			//add yes123 2015-03-24 判断推荐人是否禁用
			$sql="select is_disable FROM ". $GLOBALS['ecs']->table('users') ."  WHERE user_id=".$user['parent_id'];
			ss_log('查询推荐人是否被禁用:'.$sql);
			$is_disable = $GLOBALS['db']->getOne($sql);
			if($is_disable==1)
			{
				ss_log("佣金分配，推荐人已被禁用，不分配佣金");
			}
			else
			{
				$change_desc = $_LANG['tui_jian'];
				$user_money_parent  = $price*$blArr['rate_recommend']/100;
					
				ss_log(__FUNCTION__." 给介绍人分配佣金! parent_id: ".$user['parent_id']." user_money: ".$user_money_parent);
					
				ss_log(__FUNCTION__." before log_account_change2");
					
				// 2014-10-9  edit yes123  由于，后台要显示收入来源的姓名和用户名，所以日志里存放user_id,比较方便获取
				//log_account_change2($user['parent_id'], $user_money,$user_name['real_name'],$_LANG['tui_jian'],0, 0, 0, $change_desc, ACT_ADJUSTING,$order_sn);
				log_account_change2($user['parent_id'], $user_money_parent,$order_info['user_id'],$_LANG['tui_jian'],0, 0, 0, $change_desc, ACT_ADJUSTING,$order_sn);
					
				ss_log(__FUNCTION__." after log_account_change2");
					
				$rate_recommend = $blArr['rate_recommend'];//add yes123 2015-03-20 缓存费率。佣金分配完毕后，把费率保存到保单中，以便退保时，退佣金
				
				//保存推荐人ID
				if($order_id)
				{//提前把一些注销时候需要的数据保存到保单上。
					$sql = "UPDATE t_insurance_policy SET parent_id='$user[parent_id]' WHERE order_id=$order_id";
					ss_log("设置保单的parent_id:".$sql);
					$GLOBALS['db']->query($sql);	
				}
				
			}
		}
		else
		{
			//ss_log(__FUNCTION__." 推荐人的佣金率为: ".$blArr['rate_recommend']);
		}
		
		
		//elseif($user['distributor_id']>0 && $blArr['rate_organization']>0)
		//if($user['institution_id']>0 )
		if($user['institution_id']>0 && $user['institution_id']!= $user['user_id'])
		{//institution_id代表渠道或者团队长，都要走到这里
			ss_log("准备给渠道分配服务费，rate_organization: ".$blArr['rate_organization']);
			
			//add yes123 2015-03-24 判断渠道是否禁用
			$sql="select is_disable FROM ". $GLOBALS['ecs']->table('users') ."  WHERE user_id=".$user['institution_id'];
			ss_log('查询渠道是否被禁用:'.$sql);
			$is_disable = $GLOBALS['db']->getOne($sql);
			if($is_disable==1)
			{
				ss_log(__FUNCTION__.", 佣金分配，渠道已被禁用，不分配佣金");
			}
			else
			{
				//通过渠道ID查询渠道用户ID
				//$sql ="SELECT user_id FROM" . $GLOBALS['ecs']->table('users') . " WHERE user_name=(SELECT user_name FROM" . $GLOBALS['ecs']->table('admin_user') . " WHERE user_id=".$user['distributor_id']. ")";
				//ss_log($sql);
				//$jigou_id = $GLOBALS['db']->getOne($sql);
				$change_desc = $_LANG['ji_gou'];
				
				//start add yes123 2015-03-20 查询是否有自定义的渠道费率 
				if($product_attribute_id)
				{
					$sql ="SELECT rate_organization FROM" . $GLOBALS['ecs']->table('organ_ipa_rate_config') . " WHERE institution_id=$user[institution_id] AND attribute_id=$product_attribute_id";
					ss_log('佣金分配时，查询自定义费率');
					$defined_rate_organization = $GLOBALS['db']->getOne($sql);
					if($defined_rate_organization)//渠道产品的佣金率
					{//扎到了这里就做替换即可
						$blArr['rate_organization']=$defined_rate_organization;
						ss_log('defined_rate_organization:'.$defined_rate_organization);
					}
					
				}
				//end add yes123 2015-03-20 查询是否有自定义的渠道费率 
				
				if($blArr['rate_organization']>0)
				{
					$user_money_organization     = $price*$blArr['rate_organization']/100;
					ss_log("给渠道分配佣金! institution_id: ".$user['institution_id']." user_money: ".$user_money_organization);
					// 保存   2014-10-9  edit yes123  由于，后台要显示收入来源的姓名和用户名，所以日志里存放user_id,比较方便获取
					//log_account_change2($jigou_id, $user_money,$user_name['real_name'],$_LANG['ji_gou'],0, 0, 0, $change_desc, ACT_ADJUSTING,$order_sn);
					ss_log(__FUNCTION__." before log_account_change2");
					log_account_change2($user['institution_id'], $user_money_organization,$order_info['user_id'],$_LANG['ji_gou'],0, 0, 0, $change_desc, ACT_ADJUSTING,$order_sn);	
					ss_log(__FUNCTION__." after log_account_change2");
					$rate_organization = $blArr['rate_organization'];//add yes123 2015-03-20 缓存费率。佣金分配完毕后，把费率保存到保单中，以便退保时，退佣金
					
				}
			
					
			}

		}
		else
		{
			ss_log("没有给渠道或者团队长分配佣金institution_id：".$user['institution_id'].",rate_organization:".$blArr['rate_organization']);
		}
			
		
		//echo "is_cheack: ".$user['is_cheack']."</br>";
		
		/////////////////要更新订单状态，表示该订单已经分配了佣金/////////////
		ss_log(__FUNCTION__." 更新订单状态为已经分配佣金");
		$sql = "UPDATE bx_order_info SET fee_assigned='1' WHERE order_id=".$order_id;
		$GLOBALS['db']->query($sql);
		
		
		//add yes123 2015-03-20 把订单下面的每个保单都保存了三种费率
		$sql ="UPDATE t_insurance_policy SET rate_myself=$rate_myself,rate_recommend=$rate_recommend,rate_organization=$rate_organization WHERE order_id=$order_id ";
		ss_log('把佣金费率都保存到保单里:'.$sql);
		$GLOBALS['db']->query($sql);
		
		$sql ="UPDATE t_insurance_policy SET money_myself=$user_money_myself,money_recommend=$user_money_parent,money_organization=$user_money_organization WHERE order_id=$order_id ";
		ss_log('把佣金都保存到保单里:'.$sql);
		$GLOBALS['db']->query($sql);
				
		///////////////////发送短信不重要，放到后面执行//////////////////////////////////////////////////////////////
		//add yes123 2015-01-06 发送短信
		assign_finish_send_sms($user_money_myself,$user_money_parent,$user_money_organization,$order_sn,$user);

		////////////////////////////////////////////////////////////////////////////////////////
		$result_attr['retcode'] = 0;
		$result_attr['retmsg'] = "分配佣金完毕！user_id：".$order_info['user_id'];	
		
	}
	else
	{//非正式用户
		$retcode = 0;
		$retmsg = "非正式用户，不分配佣金! user_id: ".$order_info['user_id'];
		ss_log($retmsg);
		
		$result_attr['retcode'] = $retcode;
		$result_attr['retmsg'] = $retmsg;

	}
	return $result_attr;
}*/


//完全购买后，则到这里进行佣金结算。
//mod by zhangxi, 20150305, 修改函数名,分配佣金和投保
//function yongJin2($order_sn , $postflag = true)
function assign_commision_and_post_policy($order_sn , $postflag = true)
{
	////////////////////////////////////////////////////////////////////
	ss_log("into function assign_commision_and_post_policy! order_sn: ".$order_sn);
	////////////////////////////////////////////////////////////////////
	$result_attr = array();
	////////////////////////////////////////////////////////////////////
	//del by zhangxi , 20150317
	//require_once(ROOT_PATH . 'languages/zh_cn/admin/account_log.php');
	ss_log(__FUNCTION__.", ROOT_PATH=".ROOT_PATH);
	//判断订单是否已付款
	//$sql="select type,cart_insure_id,fee_assigned,policy_id,pay_status,goods_amount,order_id,user_id,activity_id from ". $GLOBALS['ecs']->table('order_info') ."  WHERE order_sn=".$order_sn;
	$sql="select * from ". $GLOBALS['ecs']->table('order_info') ."  WHERE order_sn='$order_sn'";
	ss_log(__FUNCTION__.", ".$sql);
	$order_info = $GLOBALS['db']->getRow($sql);
	$order_id = $order_info['order_id'];
	
	/////////////////////////////////////////////////////////////////////////////
	if($order_info['pay_status']!=2)//未支付
	{
		//echo "order_sn".$order_sn."</br>";
			
		$retcode = 110;
		$retmsg = "order 未支付，所以不执行下面的佣金分配和投保过程，order_sn: ".$order_sn;
		ss_log(__FUNCTION__.$retmsg);
			
		$result_attr['retcode'] = $retcode;
		$result_attr['retmsg'] = $retmsg;
			
		return $result_attr;
	
	}
	else
	{
		ss_log(__FUNCTION__."：已经支付，准备给用户分配佣金!，order_sn: ".$order_sn);
	}
	//////////////////////////////////////////////////////////////////////////////
	
	//start add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能
	$order_type = $order_info['type'];
	if($order_type=="meeting")
	{
		ss_log("参会订单已经支付完毕，进行后续处理");
		
		$rec_id = $order_info['cart_insure_id'];
		$user_id = $order_info['user_id'];
		
		include_once(ROOT_PATH . 'includes/class/MeetingActive.class.php');
		$obj_meeting = new MeetingActive;
		$result_attr = $obj_meeting->update_meeting_by_order($rec_id , $order_sn , $user_id );
					
		return $result_attr;

	}
	//end add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能
	
	//$policy_id = $order_info['policy_id'];//del by wangcya, for bug[193],能够支持多人批量投保，
	//$sql="SELECT policy_status,policy_no,total_premium,attribute_id FROM t_insurance_policy WHERE policy_id=".$policy_id;
	
	//start add by wangcya, for bug[193],能够支持多人批量投保，
	$order_id = $order_info['order_id'];
	
	//////////////start add by wangcya, 20150217
	ss_log("更新该订单下面的投保单的状态为已支付,order_sn: ".$order_sn);
	updatetable("insurance_policy", array("pay_status"=>2,"policy_status"=>"payed"), array("order_id"=>$order_id));
	//////////////end add by wangcya, 20150217
	
	///////////////////////////////////////////////////////////////////////////////
	$sql="SELECT * FROM t_insurance_policy WHERE order_id=".$order_id;//add by wangcya, for bug[193],能够支持多人批量投保，
	ss_log($sql);
	//end add by wangcya, for bug[193],能够支持多人批量投保
	$list_insurance_policy  = $GLOBALS['db']->getAll($sql);

	////////////////////////////////////////////////////////////////////////////////
	//mod by zhangxi, 20150316, 非活动产品才进入佣金分配函数
	if($order_info['activity_id'] == 0)
	{
		//comment by zhangxi, 20150205, 完成的订单是否进行了佣金分配的标识，订单可能投保失败，但支付成功，
		//支付成功后，就进行了佣金分配，可能再次投保，这时就不进行佣金分配了
		//$result_attr = assign_commision_by_order($order_sn, $order_info);
		$result_attr = sinosig_assign_commision($order_info);
	}
	
	//added by zhangxi, 20150426, 增加国寿学平险的特殊处理，
	//正式用户，国寿学平险走活动流程，但却要结算佣金
	if($order_info['activity_id'] > 0)
	{
		$policy_id = $list_insurance_policy[0]['policy_id'];
		
		//只能通过order_sn查找投保单表，获取信息
		$ret = get_policy_info($policy_id);//得到保单信息		
		//$policy_attr = $ret['policy_arr'];
		//$user_info_applicant = $ret['user_info_applicant'];
		$list_subject_tmp = $ret['list_subject'];//
		$product_info_list_tmp = $list_subject_tmp[0]['list_subject_product'];
		$product_info_tmp = $product_info_list_tmp[0];
		if($product_info_tmp['product_code'] == 'CNL1001'
		||$product_info_tmp['product_code'] == 'CNL1002'
		||$product_info_tmp['product_code'] == 'CNL1003')
		{//吉林国寿佣金需要计算
			//$result_attr = assign_commision_by_order($order_sn, $order_info);
			$result_attr = sinosig_assign_commision($order_info);
		}
	}
	
	
	//////////////start add by wangcya, 20140812, for bug[193],进行批量投保////////////////
	//循环找到每个投保单进行投保
	foreach ($list_insurance_policy as $key=>$insurance_policy)//add by wangcya, for bug[193],能够支持多人批量投保，
	{
		$policy_id = $insurance_policy['policy_id'];//add by wangcya, for bug[193],能够支持多人批量投保，
		
		if($policy_id)
		{
			if($insurance_policy['policy_status'] !="insured" || empty($insurance_policy['policy_no']) )//没投保成功
			{
				ss_log("还没投保，准备投保, policy_id: ".$policy_id);
				
				if(!defined('IN_UCHOME'))
				{
					@define('IN_UCHOME', TRUE);
				}
		
				//echo "postflag: ".$postflag."</br>";
				
				//只有保单id并且要投保的情况下才启动
				if($policy_id&&$postflag)
				{
					//echo "will post: "."</br>";
					//echo "ROOT_PATH: ".ROOT_PATH;
					ss_log("will execute function post_policy, policy_id: ".$policy_id);
					
					//comment by zhangxi, 20150215, 进行投保操作
					$result_attr = post_policy($policy_id);
				}
				else
				{
					$retcode = 0;
					$retmsg = "not execute function post_policy!";
					ss_log($retmsg);
					
					
					$result_attr['retcode'] = $retcode;
					$result_attr['retmsg'] = $retmsg;
				}
			}
			else
			{
				$retcode = 0;
				$retmsg = __FUNCTION__.", 已经投保成功,不必再次投保了, policy_id: ".$policy_id." policy_no: ".$insurance_policy['policy_no'];
				ss_log($retmsg);
					
				$result_attr['retcode'] = $retcode;
				$result_attr['retmsg'] = $retmsg;
			}
		}
		else
		{
			ss_log("准备投保, 但是policy_id为空");
		}
	}//add by wangcya, for bug[193],能够支持多人批量投保，
	//////////////end add by wangcya, 20140812,进行投保////////////////
	
	//start add yes123 2015-02-04 如果是活动产品,需要做其他操作
	if($order_info['activity_id'])
	{
		ss_log('活动产品，开始执行 done_finish_operation 函数.');
		include_once(ROOT_PATH.'includes/hongdong_function.php');
		done_finish_operation($order_id);
	}
	//end add yes123 2015-02-04 如果是活动产品,需要做其他操作
	
	return $result_attr;//add by wangcya , 20141010,返回投保的结果
}



/**
 * 保单退保要处理的服务费函数
 * yes123
 * 2014-09-22
 */
function withdraw_fee($policy_id)
{
	
	global $income_type_list;
	require_once(ROOT_PATH . 'languages/zh_cn/admin/account_log.php');
	///////////////////////////////////////////////////////////////////////
	ss_log("进入退保的函数 withdraw_fee");
	
	//获取要注销保单的信息
	$sql="SELECT * FROM t_insurance_policy WHERE policy_id=".$policy_id;
	ss_log("退保，查询要注销的保单信息：".$sql);
	$insurance_policy  = $GLOBALS['db']->getRow($sql);
	
	$price = $insurance_policy['total_premium'];
	$agent_uid = $insurance_policy['agent_uid'];
	
	$sql="SELECT * FROM bx_order_info WHERE order_id=".$insurance_policy['order_id'];
	ss_log("退保，查询要注销的订单信息：".$sql);
	$order_info  = $GLOBALS['db']->getRow($sql);
	$order_sn = $order_info['order_sn'];

		
	//1.退个人服务费率 start
	ss_log(__FUNCTION__."开始退还个人佣金-----------------------------------");
	ss_log("insurance_policy['money_myself']:".$insurance_policy['money_myself']);
	ss_log("insurance_policy['fee_assigned_myself']:".$insurance_policy['fee_assigned_myself']);
	if($insurance_policy['money_myself']>0 && $insurance_policy['fee_assigned_myself'])
	{
		$money = $insurance_policy['money_myself'];
		ss_log("扣回个人的服务费,金额:".$money);
		
		$change_desc = "退保,扣除".$income_type_list[ORDER_SERVICE_MONEY].$money;
		
		$account_name = 'service_money';
		
		log_account_change($agent_uid,$money* (- 1),
							0,0,0,$change_desc,0,$order_info['order_sn'],
							ORDER_SERVICE_MONEY,$agent_uid,$policy_id,'money_myself',$account_name);
		
		
		//修改佣金分配状态为未分配
		update_policy_fee_assigned_by_policy_id('fee_assigned_myself',0,$policy_id);
	}
	//1.退个人服务费率 end	

	
	//2.退推荐服务费 start
	ss_log(__FUNCTION__."开始退还推荐佣金-----------------------------------");
	ss_log("insurance_policy['parent_id']:".$insurance_policy['parent_id']);
	ss_log("insurance_policy['money_recommend']:".$insurance_policy['money_recommend']);
	ss_log("insurance_policy['fee_assigned_recommend']:".$insurance_policy['fee_assigned_recommend']);
	if($insurance_policy['parent_id'] && $insurance_policy['money_recommend']>0 && $insurance_policy['fee_assigned_recommend'])//每条记录里面记录了推荐人的uid
	{
		$parent_id = $insurance_policy['parent_id'];
		$money =$insurance_policy['money_recommend'];
		
		$change_desc = "退保,扣除".$income_type_list[RECOMMEND_SERVICE_MONEY].$money;
		
		$account_name =  'service_money';
		
		log_account_change($parent_id,$money* (- 1),
					0,0,0,$change_desc,0,$order_info['order_sn'],
					RECOMMEND_SERVICE_MONEY,$agent_uid,$policy_id,'money_recommend',$account_name);
		
		//修改佣金分配状态为未分配
		update_policy_fee_assigned_by_policy_id('fee_assigned_recommend',0,$policy_id);
	}
	//2.退推荐服务费 end
	
	
	//3.退还渠道服务费率 start
	ss_log(__FUNCTION__."开始退还渠道佣金-----------------------------------");
	ss_log("insurance_policy['money_organization']:".$insurance_policy['money_organization']);
	ss_log("insurance_policy['organ_user_id']:".$insurance_policy['organ_user_id']);
	ss_log("insurance_policy['fee_assigned_organization']:".$insurance_policy['fee_assigned_organization']);
	if($insurance_policy['money_organization'] && $insurance_policy['organ_user_id'] && $insurance_policy['fee_assigned_organization'])
	{
		$organ_user_id = $insurance_policy['organ_user_id'];
		$money = $insurance_policy['money_organization'];
		$change_desc = "退保,扣除".$income_type_list[ORGAN_SERVICE_MONEY].$money;
		
		$account_name =  'service_money';
		
		log_account_change($organ_user_id,$money* (- 1),
					0,0,0,$change_desc,0,$order_info['order_sn'],
					ORGAN_SERVICE_MONEY,$agent_uid,$policy_id,'money_organization',$account_name);
		
		//修改佣金分配状态为未分配
		update_policy_fee_assigned_by_policy_id('fee_assigned_organization',0,$policy_id);
	}
	//3.退还渠道服务费率 end
	
	//不出任何异常的话，就正常返回true
	return true;
}


//add yes123 2015-02-12 后台取消订单，退回佣金
function withdraw_order_fee($order_info)
{
	
	$user_id = $order_info['user_id'];
	$order_id = $order_info['order_id'];
	$order_sn = $order_info['order_sn'];
	
	$parent_id = $order_info['parent_id'];
	$organ_user_id = $order_info['organ_user_id'];
	
	
	$order_money_myself = $order_info['order_money_myself'];
	$order_money_recommend = $order_info['order_money_recommend'];
	$order_money_organization = $order_info['order_money_organization'];
	
	$order_fee_assigned_myself = $order_info['order_fee_assigned_myself'];
	$order_fee_assigned_recommend = $order_info['order_fee_assigned_recommend'];
	$order_fee_assigned_organization = $order_info['order_fee_assigned_organization'];
	
	
	
	require_once(ROOT_PATH . 'languages/zh_cn/admin/account_log.php');
	///////////////////////////////////////////////////////////////////////
	ss_log("进入取消订单函数 ".__FUNCTION__);

	//开始发放佣金
	if($order_info['order_status']==1 && $order_info['pay_status']==2) //订单状态是已支付，已确认
	{
		//1.退个人服务费率 start
		ss_log(__FUNCTION__."开始退还个人佣金-----------------------------------");
		ss_log("order_money_myself:".$order_money_myself);
		ss_log("order_fee_assigned_myself:".$order_fee_assigned_myself);
		if($order_money_myself>0 && $order_fee_assigned_myself)
		{
			ss_log("扣回个人的服务费,金额:".$order_money_myself);
			//log_account_change2($user_id, "-".$order_money_myself,$user_id,$_LANG['ge_ren'],0, 0, 0, "取消订单,扣除".$_LANG['ge_ren'].$order_money_myself, ACT_ADJUSTING,$order_sn,0);
			
			$change_desc = "取消订单,扣除".ORDER_SERVICE_MONEY.$order_money_myself;
			$acaccount_name = get_acaccount_name_withdraw($user_id,$order_money_myself);
			
			log_account_change($user_id,$order_money_myself * (- 1),
								0,0,0,$change_desc,0,$order_sn,
								ORDER_SERVICE_MONEY,$user_id,0,'money_myself',$acaccount_name);
			
			
			//修改佣金分配状态为未分配
			update_order_fee_assigned_by_order_id('order_fee_assigned_myself',0,$order_id);
		}
		//1.退个人服务费率 end	
		
		
				
		//2.退推荐服务费 start
		ss_log(__FUNCTION__."开始退还推荐佣金-----------------------------------");
		ss_log("parent_id:".$parent_id);
		ss_log("order_money_recommend:".$order_money_recommend);
		ss_log("order_fee_assigned_recommend:".$order_fee_assigned_recommend);
		if($parent_id && $order_money_recommend>0 && $order_fee_assigned_recommend)//每条记录里面记录了推荐人的uid
		{
			$change_desc = "取消订单,扣除".RECOMMEND_SERVICE_MONEY.$order_money_recommend;
			
			$acaccount_name = get_acaccount_name_withdraw($parent_id,$order_money_recommend);
			
			log_account_change($parent_id,$order_money_recommend * (- 1),
								0,0,0,$change_desc,0,$order_sn,
								RECOMMEND_SERVICE_MONEY,$user_id,0,'money_recommend',$acaccount_name);
			
			//修改佣金分配状态为未分配
			update_order_fee_assigned_by_order_id('order_fee_assigned_recommend',0,$order_id);
		}
		//2.退推荐服务费 end
		
		
		//3.退还渠道服务费率 start
		ss_log(__FUNCTION__."开始退还渠道佣金-----------------------------------");
		ss_log("order_money_organization:".$order_money_organization);
		ss_log("organ_user_id:".$organ_user_id);
		ss_log("fee_assigned_organization:".$order_info['fee_assigned_organization']);
		if($$order_money_organization && $organ_user_id && $order_fee_assigned_organization)
		{
			//log_account_change2($organ_user_id, "-".$order_money_organization,$user_id,$_LANG['ji_gou'],0, 0, 0, "退保,扣除".$_LANG['ji_gou'].$order_money_organization, ACT_ADJUSTING,$order_sn,0);
			
			$change_desc = "取消订单,扣除".ORGAN_SERVICE_MONEY.$order_money_recommend;
			
			$acaccount_name = get_acaccount_name_withdraw($organ_user_id,$order_money_organization);
			
			log_account_change($organ_user_id,$order_money_organization * (- 1),
								0,0,0,$change_desc,0,$order_sn,
								ORGAN_SERVICE_MONEY,$user_id,0,'money_organization',$acaccount_name);
			
			
			//修改佣金分配状态为未分配
			update_order_fee_assigned_by_order_id('order_fee_assigned_organization',0,$order_id);
		}
		//3.退还渠道服务费率 end		
		
	}
	
	return true;
		
	
}


/**
 * 更新网站账户账目
 * $Author:Yao Yuan Fu
 * @access  public
 * @param   array     $user_id     提现或者充值的账户
 * @param   array     $user_money  充值或者提现的金额
 * @param   array     $desc        充值还是提现
 * @param   array     $type        1表示支付宝充值，0表示支付宝付款
 */
 //update_amount_log($user_id,$amount,$process_type==1?"提现":"充值");
function update_amount_log($user_id, $user_money, $desc,$type=0) {
	//更新bx_amount_log表
	$change_time = time();

	//获取网站账户
/*	$sql ="SELECT count(*) FROM " . $GLOBALS['ecs']->table('users') . "WHERE 
	user_name='ebaoins_user'";
	$count = $GLOBALS['db']->getOne($sql);
	if($count>0){
		$sql = "SELECT *FROM " . $GLOBALS['ecs']->table('users') . "WHERE user_name='ebaoins_user'";
		$bx_admin = $GLOBALS['db']->getRow($sql);
			//通过提现的用户ID 查询用户名
		$sql = "SELECT user_name FROM " . $GLOBALS['ecs']->table('users') . "WHERE user_id=" . $user_id;
		$user_name = $GLOBALS['db']->getOne($sql);
		log_account_change($bx_admin['user_id'], $user_money, 0, 0, 0, $user_name.$desc, ACT_DRAWING,'',$user_id,$desc);
		//log_account_change($user_id, $user_money, 0, 0, 0, $desc, ACT_DRAWING);
	}else{
		$sql = "INSERT INTO " . $GLOBALS['ecs']->table('users') . " (user_name, password,is_cheack) " .
                    "VALUES('ebaoins_user', '7fef6171469e80d32c0559f88b377245',1)";
        $GLOBALS['db']->query($sql);
         
         
        $sql = "SELECT *FROM " . $GLOBALS['ecs']->table('users') . "WHERE user_name='ebaoins_user'";
		$bx_admin = $GLOBALS['db']->getRow($sql);
			//通过提现的用户ID 查询用户名
		$sql = "SELECT user_name FROM " . $GLOBALS['ecs']->table('users') . "WHERE user_id=" . $user_id;
		$user_name = $GLOBALS['db']->getOne($sql);
		log_account_change($bx_admin['user_id'], $user_money, 0, 0, 0, 
						  $user_name . $desc, ACT_DRAWING,$user_id,'','',0);
		//log_account_change($user_id, $user_money, 0, 0, 0, $desc, ACT_SAVING);
	}*/
	
	//2014-09-29 yes123,解决支付宝付款也向余额加前的问题
	if($type){
		//支付宝充值 
		//modify yes123 201-12-04 缺少参数,程序会警告 
		//log_account_change($user_id, $user_money, 0, 0, 0, $desc, ACT_DRAWING);
		
		//modify yes123 2015-01-08 （ACT_SAVING：账户充值   ，ACT_DRAWING:提现 ，ACT_OTHER：其他类型）
		//log_account_change($user_id, $user_money, 0, 0, 0, $desc, ACT_DRAWING,'','','');
		log_account_change($user_id, $user_money, 0, 0, 0,
		                   $desc, ACT_SAVING,'','',0,0,'money_recharge','user_money');
		
	}else{
		//支付宝付款就不用写日志了
		//log_account_change3($user_id, $user_money,$desc);
	}
		
}


//modify yes123 2015-05-12 
function sinosig_assign_commision($order_info)
{

	ss_log("into ".__FUNCTION__);

	$order_sn = $order_info['order_sn'];
	$order_id = $order_info['order_id'];
	//获取用户信息
	$user = user_info_by_userid($order_info['user_id']);
	$order_service_money_config = get_rank_cfg($user['user_rank'],ORDER_SERVICE_MONEY_RANK);
	
	//add yes123 2015--5-12 校验是否可分配佣金
	$result_attr = check_distributable_rate($order_info,$user);
	if(!$result_attr['is_distributable'])
	{
		return;
	}
	

	
	//获取推荐人信息
	$parent=0;
 	if($user['parent_id'])
	{
		$sql="select * from ". $GLOBALS['ecs']->table('users') ."  WHERE user_id=".$user['parent_id'];
		ss_log("get parent:".$sql);
		$parent= $GLOBALS['db']->getRow($sql);
		$recommend_service_money_config = get_rank_cfg($parent['user_rank'],RECOMMEND_SERVICE_MONEY_RANK);
	}
	
	$institution=0;
	//获取机构
	if($user['institution_id'])
	{
		$sql="select * from ". $GLOBALS['ecs']->table('users') ."  WHERE user_id=".$user['institution_id'];
		ss_log("get institution:".$sql);
		$institution= $GLOBALS['db']->getRow($sql);
	}
	
	$result_attr=array();
	$user_money_parent=0;
	$user_money_organization=0;
	$user_money_myself=0;
	
	
	
	$is_calculate_recommend=0; //是否计算推荐人佣金
	$is_calculate_institution=0; //是否计算渠道佣金
	//判断是否要分配推荐人佣金
	if($parent && !$parent['is_desable'] && $parent['user_id']!=$user['user_id'] && !empty($recommend_service_money_config))
	{
		$is_calculate_recommend=1;
	}
	else
	{
		ss_log("条件不成立，不分配推荐人佣金");
	}
	
	if($institution && !$institution['is_desable'] && $institution['user_id']!=$user['user_id'])
	{
		$is_calculate_institution=1;
	}
	else
	{
		ss_log("条件不成立，不分配渠道佣金");
	}
	
	$sql="SELECT * FROM t_insurance_policy WHERE order_id=".$order_id;
	ss_log("获取要分配佣金的所有保单 :".$sql);
	$list_insurance_policy  = $GLOBALS['db']->getAll($sql);
	
	
	
	$rate_status_arr_list = array();
	//循环累加佣金，统一发放,遍历一个订单下的所有投保单
	foreach ($list_insurance_policy as $key => $policy) 
	{
   		$price = $policy['total_premium'];
   		$attribute_id = $policy['attribute_id'];
   		$policy_id = $policy['policy_id'];
		
   		$money_myself=0;
		$money_recommend=0;
		$money_organization=0;
		
		$rate_status_arr = array();
		
		//取费率
		$blArr = get_rate_by_policy($policy,$order_sn);
		
		
		//个人服务费率
   		if($blArr['rate_myself']>0 && !empty($order_service_money_config))
		{
			if($policy['fee_assigned_myself'])
			{
				ss_log("个人服务费已分配，不能重复分配。policy_id:".$policy['policy_id']);
			}
			else
			{
				$money_myself = $price*$blArr['rate_myself']/100;
				
				//add yes123 2015-08-19 根据等级配置自定义
				ss_log("计算保单服务费，原本订单服务费：".$money_myself);
				$money_myself = $money_myself*($order_service_money_config['value']/100);
				ss_log("计算保单服务费，按照代理人等级计算后的订单服务费：".$money_myself);
				$user_money_myself += $money_myself;
				
				$rate_status_arr['fee_assigned_myself'] =$policy_id;
				
			}
							
		}
		else
		{
			ss_log("个人服务费率小于0，或者不启用订单服务费，不分配佣金。policy_id:".$policy['policy_id']);
		}
   		
   		//推荐人服务费率
		if($is_calculate_recommend && $blArr['rate_recommend']>0)
		{
			if($policy['fee_assigned_recommend'])
			{
				ss_log("推荐服务费已分配，不能重复分配。policy_id:".$policy['policy_id']);
			}
			else
			{
				$money_recommend = $price*$blArr['rate_recommend']/100;
				
				//add yes123 2015-08-19 根据等级配置自定义
				ss_log("计算保单服务费，原本推荐服务费：".$money_recommend);
				$money_recommend = $money_recommend*($recommend_service_money_config['value']/100);
				ss_log("计算保单服务费，按照推荐人等级计算后的推荐服务费：".$money_recommend);
				
				$user_money_parent += $money_recommend;
				$rate_status_arr['fee_assigned_recommend'] =$policy_id;
			}

		}
		else
		{
			ss_log("推荐费率小于0，不分配policy_id:".$policy['policy_id']);
   		}
  
       		
     
       		//机构服务费率	
		if($is_calculate_institution && $blArr['rate_organization']>0 )
		{
			if($policy['fee_assigned_organization'])
			{
				ss_log("渠道服务费已分配，不能重复分配。policy_id:".$policy['policy_id']);
			}
			else
			{
			   	$money_organization = $price*$blArr['rate_organization']/100;
				$user_money_organization += $money_organization;
				$rate_status_arr['fee_assigned_organization'] =$policy_id;	
			}

		}
		else
		{
			ss_log("渠道费率小于0，不分配policy_id:".$policy['policy_id']);
		}
   		
   		
   		$rate_status_arr_list[]=$rate_status_arr;//保存是否已分配状态，用于更新保单上佣金是否分配
   			
       //add yes123 2015-03-20 把订单下面的每个保单都保存了三种费率
       save_commission_to_policy($money_myself,$money_recommend,$money_organization,$policy_id);
	
       
	}
		
	ss_log("user_money_myself:".$user_money_myself);
	ss_log("user_money_parent:".$user_money_parent);
	ss_log("user_money_organization:".$user_money_organization);
	
	

	
	//开始发放佣金
	issue_commission($user_money_myself,$user_money_parent,$user_money_organization,$order_info,$user);
	
	//更新保单佣金是否分配字段
	update_policy_fee_assigned_by_arr($rate_status_arr_list);	
	
	//发送短信
	assign_finish_send_sms($user_money_myself,$user_money_parent,$user_money_organization,$order_sn,$user);
	
	//把总费率保存到订单，取消订单时用到。
	save_commission_to_order($user_money_myself,$user_money_parent,$user_money_organization,$order_id);
	
	$result_attr['retcode'] = 0;
	$result_attr['retmsg'] = "分配佣金完毕！user_id：".$order_info['user_id'];	
	
	return $result_attr;//add by wangcya , 20141010,返回投保的结果
	
}


//佣金分配完毕后发送短信
function assign_finish_send_sms($user_money_myself=0,
								$user_money_parent=0,
								$user_money_organization=0,$order_sn,$user)
{
	
	//个人
	if($user_money_myself>0)
	{
		//add yes123 2015-01-06 发送短信
		if($user['mobile_phone'])
		{
			ss_log(__FUNCTION__." 准备给代理人本人发送短信");
			$content = "您的订单已支付成功，订单号：".$order_sn."，服务费:".$user_money_myself."元已到账，请登录网站查询。";
			send_msg($user['mobile_phone'],$content,0,SERVICE_CHARGE_SEND_SMS);
		}
		else
		{
			ss_log(__FUNCTION__." 代理人本人的手机号为空");
		}
	}
	
	
	//推荐人
	if($user_money_parent>0)
	{
		$sql="select mobile_phone from ". $GLOBALS['ecs']->table('users') ."  WHERE user_id=".$user['parent_id'];
		$parent_mobile_phone = $GLOBALS['db']->getOne($sql);
		if($parent_mobile_phone)
		{
			ss_log(__FUNCTION__." 准备给推荐人发送短信");
			$content = "您推荐的会员".$user['user_name']."在本网已成功下单，您因此获得推荐奖励".$user_money_parent."元已到账，请登录网站查询。";
			send_msg($parent_mobile_phone,$content,0,SERVICE_CHARGE_SEND_SMS);
		}
		else
		{
			ss_log(__FUNCTION__." 推荐人的手机号为空");
		}
	}
	
	//机构
	if($user_money_organization>0)
	{
		
		//查询渠道电话号码
		$sql="select mobile_phone from ". $GLOBALS['ecs']->table('users') ."  WHERE user_id=".$user['institution_id'];
		$organization_mobile_phone = $GLOBALS['db']->getOne($sql);
		if(!$organization_mobile_phone)
		{
			$sql="select d_mobilePhone from ". $GLOBALS['ecs']->table('distributor') ."  WHERE d_uid=".$user['institution_id'];
			$organization_mobile_phone = $GLOBALS['db']->getOne($sql);
		}
		
		
		if($organization_mobile_phone)
		{
			ss_log(__FUNCTION__." 准备给渠道发送短信");
			$content = "您的会员".$user['user_name']."在本网已成功下单，您因此获得奖励".$user_money_organization."元已到账，请登录网站查询。";
			send_msg($organization_mobile_phone,$content,0,SERVICE_CHARGE_SEND_SMS);
		}
		else
		{
			ss_log(__FUNCTION__." 渠道的手机号为空");
		}
		
	}
	
	
}

//add yes123 2015-05-12  获取服务费费率
function get_rate_by_policy($policy,$order_sn)
{
	
	//获取险种上的服务费率值
	$sql="SELECT * FROM t_insurance_product_attribute  WHERE attribute_id='$policy[attribute_id]'";
	ss_log(__FUNCTION__.", t_insurance_product_attribute sql:".$sql);
	$attribute = $GLOBALS['db']->getRow($sql);
	
	
	//判断是不是诉讼保全的，如果是，从保单里取费率
	if($policy['insurer_code']=='SSBQ')
	{
		$attribute['rate_myself'] = $policy['rate_myself'];
		$attribute['rate_recommend'] = $policy['rate_recommend'];
		$attribute['rate_organization'] = $policy['rate_organization'];
		
		return $attribute;
	}
	
	
	
	//added by zhangxi, 需要通过产品险种来区分，有的险种佣金率也不是固定不变的，而是有影响因子的
	$attribute['rate_myself'] = update_rate_of_commision($attribute['rate_myself'], 
														$order_sn,
														$attribute['insurer_code'],
														$attribute['attribute_type']
														);
	if($policy['organ_user_id'])//渠道的情况
	{
		$sql="SELECT * FROM ". $GLOBALS['ecs']->table('organ_ipa_rate_config') ."  WHERE institution_id='$policy[organ_user_id]' AND attribute_id='$policy[attribute_id]'";
		ss_log(__FUNCTION__.", organization sql:".$sql);
		$organ_ipa_rate_config = $GLOBALS['db']->getRow($sql);
		if($organ_ipa_rate_config)
		{
			if($organ_ipa_rate_config['rate_myself'] && $organ_ipa_rate_config['myself_enabled'])
			{
				$attribute['rate_myself'] = $organ_ipa_rate_config['rate_myself'];
				ss_log(__FUNCTION__.", organization rate_myself:".$attribute['rate_myself']);
			}
			
			if($organ_ipa_rate_config['rate_recommend'] && $organ_ipa_rate_config['recommend_enabled'])
			{
				$attribute['rate_recommend'] = $organ_ipa_rate_config['rate_recommend'];
				ss_log(__FUNCTION__.", organization rate_recommend:".$attribute['rate_recommend']);
			}
			
			if($organ_ipa_rate_config['rate_organization'] && $organ_ipa_rate_config['organization_enabled'])
			{
				$attribute['rate_organization'] = $organ_ipa_rate_config['rate_organization'];
				ss_log(__FUNCTION__.", organization rate_organization:".$attribute['rate_organization']);
			}
			
		}
		
	}
	
	
	if($policy['activity_id'])//活动的
	{
		$sql="SELECT * FROM ". $GLOBALS['ecs']->table('activity_rate') ."  WHERE activity_id='$policy[activity_id]'";
		ss_log(__FUNCTION__.", activity sql:".$sql);
		$activity_rate_list = $GLOBALS['db']->getAll($sql);
		
		if($activity_rate_list)
		{
			$organ_rate_arr = array();
			foreach ( $activity_rate_list as $key => $value ) {
				
       			if($value['rate_type']=='rate_myself' && $value['is_enabled']==1)
	       		{
	       		   	$attribute['rate_myself'] = $value['rate_value'];
	       			ss_log(__FUNCTION__.",activity rate_myself:".$attribute['rate_myself']);
	       		}
	       		if($value['rate_type']=='rate_recommend' && $value['is_enabled']==1)
	       		{
	       			$attribute['rate_recommend'] = $value['rate_value'];
	       			ss_log(__FUNCTION__.",activity rate_recommend:".$attribute['rate_recommend']);
	       		}
	       		
	       		if($value['rate_type']=='rate_organization' && $value['is_enabled']==1)
	       		{
	       			$attribute['rate_organization'] = $value['rate_value'];
	       			ss_log(__FUNCTION__.",activity rate_organization:".$attribute['rate_organization']);
	       		}
       			
			}
		}

		
	}
	
	return $attribute;
	
}

//add yes123 2015-05-12  发放佣金

/**
 * 发放或者退还
 * $Author:Yao Yuan Fu
 * @access  public
 * @param   int     $user_money_myself     个人服务费
 * @param   int     $user_money_parent  推荐服务费
 * @param   int     $user_money_organization    渠道服务费
 * @param   array   $order_info    订单
 * @param   array     $user    当前用户
 */
function  issue_commission($user_money_myself=0,
						   $user_money_parent=0,
						   $user_money_organization=0,$order_info,$user)
{
	
	global $income_type_list;
	require_once(ROOT_PATH . 'languages/zh_cn/admin/account_log.php');
	$order_id = $order_info['order_id'];
	$order_sn = $order_info['order_sn'];
	
		//开始分配佣金
	//1.个人
	if($user_money_myself>0)
	{
		$change_desc =$income_type_list[ORDER_SERVICE_MONEY];
		log_account_change($user['user_id'],$user_money_myself,
				0,0,0,$change_desc,ACT_OTHER,$order_sn,
				ORDER_SERVICE_MONEY,$user['user_id'],
				0,'money_myself','service_money' 
		);
		ss_log(__FUNCTION__." after log_account_change2, change_desc=".$change_desc);

		
	}
	
	//2.推荐
	if($user_money_parent>0)
	{
		$change_desc = $income_type_list[RECOMMEND_SERVICE_MONEY];
		log_account_change($user['parent_id'],$user_money_parent,
				0,0,0,$change_desc,ACT_OTHER,$order_sn,
				RECOMMEND_SERVICE_MONEY,$user['user_id'],
				0,'money_recommend','service_money' 
		);
		
		//保存推荐人ID
		if($order_id)
		{//提前把一些注销时候需要的数据保存到保单上。
			$sql = "UPDATE t_insurance_policy SET parent_id='$user[parent_id]' WHERE order_id=$order_id";
			ss_log("设置保单的parent_id:".$sql);
			$GLOBALS['db']->query($sql);	
			
			$sql = "UPDATE bx_order_info SET parent_id='$user[parent_id]' WHERE order_id=$order_id";
			ss_log("设置订单的parent_id:".$sql);
			$GLOBALS['db']->query($sql);	
		}
	}
	
	//3.机构
	if($user_money_organization>0)
	{
		$change_desc = $income_type_list[ORGAN_SERVICE_MONEY];
		log_account_change($user['institution_id'],$user_money_organization,
				0,0,0,$change_desc,ACT_OTHER,$order_sn,
				ORGAN_SERVICE_MONEY,$user['user_id'],
				0,'money_organization','service_money' 
		);
		
	}
	
	
}



/**
 * yes123 2015-05-12 更新佣金相关字段为已分配
 */
function update_policy_fee_assigned_by_arr($rate_status_arr_list)
{
	foreach ( $rate_status_arr_list as $key => $rate_status_arr ) {
       foreach ($rate_status_arr as $key => $value ) {
	       	$sql = "UPDATE t_insurance_policy SET $key='1' WHERE policy_id='$value'";
			ss_log(__FUNCTION__." sql:".$sql);
			$GLOBALS['db']->query($sql);
	   }
	}
}
/**
 * yes123 2015-05-12 更新佣金相关字段为已分配
 */
function update_policy_fee_assigned_by_policy_id($fee_assigned_str,$value,$policy_id)
{
    $sql = "UPDATE t_insurance_policy SET $fee_assigned_str='$value' WHERE policy_id='$policy_id'";
	ss_log(__FUNCTION__." sql:".$sql);
	$GLOBALS['db']->query($sql);
}
/**
 * yes123 2015-05-20 取消订单，更新佣金相关字段为已分配
 */
function update_order_fee_assigned_by_order_id($fee_assigned_str,$value,$order_id)
{
	$str_array = array();
    $sql = "UPDATE bx_order_info SET $fee_assigned_str='$value' WHERE order_id='$order_id'";
	ss_log(__FUNCTION__." sql:".$sql);
	$GLOBALS['db']->query($sql);
	
	
/*	fee_assigned_myself  个人服务费是否分配，标志1为已分配
	fee_assigned_recommend   推荐服务费是否分配，标志1为已分配
	fee_assigned_organization 渠道服务费是否分配，标志1为已分配*/
	
	$temp_arr = array('order_fee_assigned_myself'=>'fee_assigned_myself',
					   'order_fee_assigned_recommend'=>'fee_assigned_recommend',
					   'order_fee_assigned_organization'=>'fee_assigned_organization',
	);
	
	
    $sql = "UPDATE t_insurance_policy SET $temp_arr[$fee_assigned_str]='$value' WHERE order_id='$order_id'";
	ss_log(__FUNCTION__." sql:".$sql);
	$GLOBALS['db']->query($sql);

}

// yes123 2015-05-12 校验是否可分配
function check_distributable_rate($order_info,$user)
{
	
	$order_id = $order_info['order_id'];	
	$result_attr['is_distributable'] = false;
	
	
	if($user['institution_id']==JILIN_INSTITUTION_ID && $user['check_status']!=CHECKED_CHECK_STATUS)
	{
		$retmsg = "吉林用户，未审核通过! user_id: ".$order_info['user_id'];
		ss_log($retmsg);
		$result_attr['retmsg'] = $retmsg;
		return $result_attr;
	}
	

	//被禁用的会员不分配佣金
	if($user['is_disable']==1)
	{
		$retmsg = "代理人为普通会员或者已被删除禁用，不分配佣金!，所以返回";
		ss_log($retmsg);
		$result_attr['retmsg']  = $retmsg;
		return $result_attr;
	}
	
	//检查订单是否支付过
	if($order_info['pay_status']!=2)//未支付
	{
			
		$retmsg = "order 未支付，所以不执行下面的佣金分配和投保过程，order_id: ".$order_id;
		ss_log(__FUNCTION__.$retmsg);
		$result_attr['retmsg'] = $retmsg;
		return $result_attr;
	
	}
	
	
	$retmsg = " 校验通过，可以分配佣金，order_id: ".$order_id;
	ss_log(__FUNCTION__.$retmsg);
	$result_attr['retmsg'] = $retmsg;
	$result_attr['is_distributable'] = true;
	return $result_attr;

	
}


// yes123 2015-05-12 保存费率到保单
function save_commission_to_policy($money_myself,$money_recommend,$money_organization,$policy_id)
{
    
    if($money_myself>0)
    {
    	$sql ="UPDATE t_insurance_policy SET money_myself='$money_myself' WHERE policy_id='$policy_id'";
		ss_log('把订单服务费保存到保单里:'.$sql);
		$GLOBALS['db']->query($sql);
    }
    if($money_recommend>0)
    {
    	$sql ="UPDATE t_insurance_policy SET money_recommend='$money_recommend' WHERE policy_id='$policy_id'";
		ss_log('把推荐服务费保存到保单里:'.$sql);
		$GLOBALS['db']->query($sql);
    }
    if($money_organization>0)
    {
    	$sql ="UPDATE t_insurance_policy SET money_organization='$money_organization' WHERE policy_id='$policy_id'";
		ss_log('把渠道服务费保存到保单里:'.$sql);
		$GLOBALS['db']->query($sql);
    }
    
}
// yes123 2015-05-22 保存费率到订单
function save_commission_to_order($order_money_myself,$order_money_recommend,$order_money_organization,$order_id)
{
    if($order_money_myself>0)
    {
    	$sql ="UPDATE bx_order_info SET order_money_myself='$order_money_myself',order_fee_assigned_myself=1  WHERE order_id='$order_id'";
		ss_log('把订单服务费保存到订单里:'.$sql);
		$GLOBALS['db']->query($sql);
    }
    if($order_money_recommend>0)
    {
    	$sql ="UPDATE bx_order_info SET order_money_recommend='$order_money_recommend',order_fee_assigned_recommend=1 WHERE order_id='$order_id'";
		ss_log('把推荐服务费保存到订单里:'.$sql);
		$GLOBALS['db']->query($sql);
    }
    if($order_money_organization>0)
    {
    	$sql ="UPDATE bx_order_info SET order_money_organization='$order_money_organization',order_fee_assigned_organization=1 WHERE order_id='$order_id'";
		ss_log('把渠道服务费保存到订单里:'.$sql);
		$GLOBALS['db']->query($sql);
    }
    
}

//退保时，获取应该从哪个账户扣除
function get_acaccount_name_withdraw($user_id,$money)
{
	$account_name='user_money';
	$user_info  = user_info($user_id);
	//如果服务费大于退还的佣金，那么直接从服务费里扣除，否则从保费里扣除
	if($user_info['service_money']>=$money)
	{ 
		$account_name='service_money';
	}
	return $account_name;
	
}


//add yes123 2015-08-18 取消订单
function quxiao_order($order_info)
{
	ss_log("into ".__FUNCTION__);
	$order_id = $order_info['order_id'];
	
	$arr = array(
        'order_status'  => OS_CANCELED,
        'pay_status'    => PS_UNPAYED,
        'pay_time'      => 0,
        'money_paid'    => 0,
        'order_amount'  => $order_info['money_paid']
    );
    
	update_order($order_id, $arr);
	
	
	if ($order_info['bonus_id'] > 0)
    {
        unuse_bonus($order_info['bonus_id']);
    }

    /* 修改订单 */
    $arr = array(
        'bonus_id'  => 0,
        'bonus'     => 0,
        'integral'  => 0,
        'service_money'  => 0,
        'integral_money'    => 0,
        'surplus'   => 0
    );
    
	update_order($order_info['order_id'], $arr);
	
}

//注销保单，退保费处理
function return_policy_premium($order_info,$policy_info)
{
	//40 60    money_paid+surplus=70  bonus=30   refund_amount
	$order_id = $order_info['order_id'];
	$refund_amount = $order_info['refund_amount'];
	$surplus = $order_info['surplus']; //余额支付金额
	$service_money = $order_info['service_money']; //余额支付金额
	$money_paid = $order_info['money_paid'];//其他支付方式金额
	$total_premium = $policy_info['total_premium'];
	ss_log(__FUNCTION__.",refund_amount:".$refund_amount);
	ss_log(__FUNCTION__.",service_money:".$service_money);
	ss_log(__FUNCTION__.",surplus:".$surplus);
	ss_log(__FUNCTION__.",money_paid:".$money_paid);
	ss_log(__FUNCTION__.",total_premium:".$total_premium);
	
	
	$surplus_amount = $surplus+$money_paid+$service_money-$refund_amount;
	ss_log(__FUNCTION__.",surplus_amount:".$surplus_amount);
	
	if($surplus_amount>$total_premium)
	{
		log_account_change($order_info['user_id'],$total_premium,
					0,0,0,'退保',0,$order_info['order_sn'],
					'',0,$policy_info['policy_id'],'','user_money');
					
		update_refund_amount($total_premium,$order_id);	
	}
	else if($surplus_amount>0)
	{
		log_account_change($order_info['user_id'],$surplus_amount,
					0,0,0,'退保',0,$order_info['order_sn'],
					'',0,$policy_info['policy_id'],'','user_money');
					
		update_refund_amount($surplus_amount,$order_id);
		ss_log(__FUNCTION__.",没有足够支付金额退还保费，所以只退还：".$surplus_amount);
	}
	
}

function update_refund_amount($total_premium=0,$order_id=0)
{
	$sql = "UPDATE bx_order_info set refund_amount=refund_amount+$total_premium WHERE order_id=$order_id";		
	ss_log(__FUNCTION__.",更新refund_amount:".$sql);
	$GLOBALS['db']->query($sql);
	
}

function springpass_refund($policy_id,$order_info){
	
	$order_id = $order_info['order_id'];
	
	$sql = " SELECT *FROM ".$GLOBALS['ecs']->table('pay_log')." WHERE order_id='$order_id' AND is_paid=1";
	$pay_log_list = $GLOBALS['db']->getAll($sql);
	
	$pay_id = $order_info['pay_id'];
	//获取商户号
	$organ_user_id = $order_info['organ_user_id'];
	$pyament = get_payment_organ($pay_id,$organ_user_id);
	
	foreach ( $pay_log_list as $key => $pay_log ) 
	{
		$springpass_refund = array();
		//生成退款文件
		
		$springpass_refund['springpass_account'] = $pyament['springpass_account'];
		//货币代码
		$springpass_refund['currency_code'] = "988";
		//交易金额
		$springpass_refund['transaction_amount'] = $pay_log['order_amount'];
		
		//原交易流水号
		$springpass_refund['source_serial_number'] = $pay_log['log_id'];
		
		//终端号
		$springpass_refund['requesting_terminal'] = "00000000";
		
		//时间
		$springpass_refund['add_time'] = time();
		$springpass_refund['order_id'] = $order_id;
		$springpass_refund['policy_id'] = $policy_id;
		
		
		//生成文件流水号 start===================================
		$sql=" SELECT file_serial_number FROM ".$GLOBALS['ecs']->table('springpass_refund'). " ORDER BY id DESC LIMIT 1";
		$max_springpass_refund = $GLOBALS['db']->getRow($sql);
		if($max_springpass_refund['file_serial_number']==999)
		{
			$springpass_refund['file_serial_number']=1;
		}
		else
		{
			$springpass_refund['file_serial_number']=$max_springpass_refund['file_serial_number']+1;
			
		}
		//生成文件流水号 end===================================
		
		
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('springpass_refund'), $springpass_refund, "INSERT"); 
		$springpass_refund['serial_number'] = $springpass_refund_id = $GLOBALS['db']->insert_id(); //自增ID当作是交易流水号
		
		
		ss_log("春秋注销保单退款文件.".print_r($springpass_refund,true));

		$file_serial_number=sprintf("%03d", $springpass_refund['file_serial_number']);
		$springpass_refund_file_path = "attachment/springpass_refund/$springpass_refund[springpass_account]_20020_".date("Ymd").$file_serial_number.".txt";
		
		if(!file_exists(ROOT_PATH."attachment/springpass_refund"))
		{
			mkdir(ROOT_PATH."attachment/springpass_refund");
			
		}
		
		
		$springpass_refund_file = fopen(ROOT_PATH.$springpass_refund_file_path,'w');
		
		fwrite($springpass_refund_file,
			$springpass_refund['springpass_account'].//商户号
			",".$springpass_refund['currency_code'].//货币代码
			",".$springpass_refund['transaction_amount'].//交易金额
			",".$springpass_refund['transaction_amount'].//交易金额
			",".$springpass_refund['transaction_amount'].//退款金额
			",".$springpass_refund['serial_number'].//交易流水号
			",".$springpass_refund['source_serial_number'].//原交易流水号
			",".$springpass_refund['requesting_terminal']//交易终端号
		);
		
		fclose($springpass_refund_file);
		
		$GLOBALS['db']->query("UPDATE ".$GLOBALS['ecs']->table('springpass_refund')." SET file='$springpass_refund_file_path' WHERE id='$springpass_refund_id'");
		
	}
					
	
	
}

?>