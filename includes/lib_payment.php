<?php

/**
 * ECSHOP 支付接口函数库
 * ============================================================================
 * 版权所有 2005-2010 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: yehuaixiao $
 * $Id: lib_payment.php 17218 2011-01-24 04:10:41Z yehuaixiao $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

include_once(ROOT_PATH . 'baoxian/source/function_debug.php');

//include_once(ROOT_PATH.'../baoxian/common.php');
//include_once(ROOT_PATH.'baoxian/source/function_common.php');

/**
 * 
 * 取得返回信息地址
 * @param   string  $code   支付方式代码
 */
function return_url($code)
{
    return $GLOBALS['ecs']->url() . 'respond.php?code=' . $code;
}

/**
 *  取得某支付方式信息
 *  @param  string  $code   支付方式代码
 */
function get_payment($code)
{ 
    $sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('payment').
           " WHERE pay_code = '$code' AND enabled = '1'";
    $payment = $GLOBALS['db']->getRow($sql);

    if ($payment)
    {
        $config_list = unserialize($payment['pay_config']);

        foreach ($config_list AS $config)
        {
            $payment[$config['name']] = $config['value'];
        }
    }

    return $payment;
}
/**
 *  取得某支付方式信息
 *  @param  string  $code   支付方式代码
 */
function get_payment_organ($pay_id,$user_id=0)
{ 
    $sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('payment_organ').
           " WHERE pay_id = '$pay_id' AND user_id='$user_id' AND enabled = '1'";
    $payment = $GLOBALS['db']->getRow($sql);

    if ($payment)
    {
        $config_list = unserialize($payment['pay_config']);

        foreach ($config_list AS $config)
        {
            $payment[$config['name']] = $config['value'];
        }
    }

    return $payment;
}

/**
 *  取得某支付方式信息
 *  @param  string  $code   支付方式代码
 */
function get_payment_byid($pay_id)
{ 
    $sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('payment').
           " WHERE pay_id = '$pay_id' AND enabled = '1'";
    $payment = $GLOBALS['db']->getRow($sql);

    if ($payment)
    {
        $config_list = unserialize($payment['pay_config']);

        foreach ($config_list AS $config)
        {
            $payment[$config['name']] = $config['value'];
        }
    }

    return $payment;
}

/**
 *  通过订单sn取得订单ID
 *  @param  string  $order_sn   订单sn
 *  @param  blob    $voucher    是否为会员充值
 */
function get_order_id_by_sn($order_sn, $voucher = 'false')
{
    if ($voucher == 'true')
    {
        if(is_numeric($order_sn))
        {
              return $GLOBALS['db']->getOne("SELECT log_id FROM " . $GLOBALS['ecs']->table('pay_log') . " WHERE order_id=" . $order_sn . ' AND order_type=1');
        }
        else
        {
            return "";
        }
    }
    else
    {
        if(is_numeric($order_sn))
        {
            $sql = 'SELECT order_id FROM ' . $GLOBALS['ecs']->table('order_info'). " WHERE order_sn = '$order_sn'";
            $order_id = $GLOBALS['db']->getOne($sql);
        }
        if (!empty($order_id))
        {
            $pay_log_id = $GLOBALS['db']->getOne("SELECT log_id FROM " . $GLOBALS['ecs']->table('pay_log') . " WHERE order_id='" . $order_id . "'");
            return $pay_log_id;
        }
        else
        {
            return "";
        }
    }
}

/**
 *  通过订单ID取得订单商品名称
 *  @param  string  $order_id   订单ID
 */
function get_goods_name_by_id($order_id)
{
    $sql = 'SELECT goods_name FROM ' . $GLOBALS['ecs']->table('order_goods'). " WHERE order_id = '$order_id'";
    $goods_name = $GLOBALS['db']->getCol($sql);
    return implode(',', $goods_name);
}

/**
 * 检查支付的金额是否与订单相符
 *
 * @access  public
 * @param   string   $log_id      支付编号
 * @param   float    $money       支付接口返回的金额
 * @return  true
 */
function check_money($log_id, $money)
{
    if(is_numeric($log_id))
    {
        $sql = 'SELECT order_amount FROM ' . $GLOBALS['ecs']->table('pay_log') .
              " WHERE log_id = '$log_id'";
        $amount = $GLOBALS['db']->getOne($sql);
    }
    else
    {
        return false;
    }
    if ($money == $amount)
    {
        return true;
    }
    else
    {
        return false;
    }
}

/**
 * 修改订单的支付状态
 *
 * @access  public
 * @param   string  $log_id     支付编号
 * @param   integer $pay_status 状态
 * @param   string  $note       备注
 * @return  void
 */
function order_paid($log_id, $pay_status = PS_PAYED, $note = '')
{
//	include_once(ROOT_PATH. 'baoxian/common.php');//这个会产生严重的错误
	
	ss_log("into function order_paid,log_id: ".$log_id." pay_status: ".$pay_status);//add by wangcya , 20140926
	
    /* 取得支付编号 */
    $log_id = intval($log_id);
    if ($log_id > 0)
    {
        /* 取得要修改的支付记录信息 */
        $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('pay_log') .
                " WHERE log_id = '$log_id'";
        $pay_log = $GLOBALS['db']->getRow($sql);
        
        ss_log("in pay_log , sql: ".$sql);
        ss_log("in pay_log , log_id: ".$pay_log['log_id']);
        ss_log("in pay_log , order_id: ".$pay_log['order_id']);
        ss_log("in pay_log , is_paid: ".$pay_log['is_paid']);
        ss_log("in pay_log , order_type: ".$pay_log['order_type']);
        
        if ($pay_log && $pay_log['is_paid'] == 0)//没支付成功
        {
        	ss_log("订单的支付状态还没修改，准备修改");
            /* 修改此次支付操作的状态为已付款 */
            $sql = 'UPDATE ' . $GLOBALS['ecs']->table('pay_log') .
                    " SET is_paid = '1' WHERE log_id = '$log_id'";
            ss_log($sql);
            
            $GLOBALS['db']->query($sql);

            /* 根据记录类型做相应处理 */
            if ($pay_log['order_type'] == PAY_ORDER)
            {
                /* 取得订单信息 */
                $sql = 'SELECT order_id, user_id, order_sn,order_amount,money_paid,consignee, address, tel, shipping_id, extension_code, extension_id, goods_amount ' .
                        'FROM ' . $GLOBALS['ecs']->table('order_info') .
                       " WHERE order_id = '$pay_log[order_id]'";
                ss_log($sql);
                
                $order    = $GLOBALS['db']->getRow($sql);
                $order_id = $order['order_id'];
                $order_sn = $order['order_sn'];
				
				
				$order_status = OS_CONFIRMED;	
				$order_amount = 0; 
				$money_paid = $order['money_paid']; 
                ss_log("将要修改订单的状态为已支付,订单号：".$order_sn." order_id: ".$order_id);
                /* 修改订单状态为已付款 */
                if(($order['order_amount']-$pay_log['order_amount'])>0)
                {
                	$pay_status = PS_UNPAYED;
                	$order_amount= $order['order_amount']-$pay_log['order_amount'];
                	$money_paid+=$pay_log['order_amount'];
                }
                else
                {
                	$money_paid = $order['order_amount']+$money_paid;
                	
                }
                
                $sql = 'UPDATE ' . $GLOBALS['ecs']->table('order_info') .
                            " SET order_status = '" . $order_status . "', " .
                                " confirm_time = '" . time() . "', " .
                                " pay_status = '$pay_status', " .
                                " pay_time = '".time()."', " .
                                " money_paid = $money_paid," .
                                " order_amount =$order_amount ".
                       "WHERE order_id = '$order_id'";
                
                ss_log($sql);
                
                $GLOBALS['db']->query($sql);
                
            	    
                //虚拟账户金额日志
                $user_name_sql="SELECT user_name FROM ". $GLOBALS['ecs']->table('users')." WHERE user_id=".$order['user_id'];

                ss_log($user_name_sql);
                $user_name =$GLOBALS['db']->getRow($user_name_sql);             
               	//update_amount_log($order['user_id'],$pay_log['order_amount'],"支付宝付款,订单号:".$order_sn,0);
                /* 记录订单操作记录 */
                order_action($order_sn, OS_CONFIRMED, SS_UNSHIPPED, $pay_status, $note, $GLOBALS['_LANG']['buyer']);

                ss_log(__FUNCTION__.",记录订单操作记录 ");
                /* cooment by wangcya , 20140926, 如果需要，发短信 */
                if ($GLOBALS['_CFG']['sms_order_payed'] == '1' && $GLOBALS['_CFG']['sms_shop_mobile'] != '')
                {
                    include_once(ROOT_PATH.'includes/cls_sms.php');
                    $sms = new sms();
                    $sms->send($GLOBALS['_CFG']['sms_shop_mobile'],
                        sprintf($GLOBALS['_LANG']['order_payed_sms'], $order_sn, $order['consignee'], $order['tel']),'', 13,1);
                }

                /* 对虚拟商品的支持 */
                $virtual_goods = get_virtual_goods($order_id);
                if (!empty($virtual_goods))
                {
                	ss_log("virtual_goods is not empty");
                	
                    $msg = '';
                    if (!virtual_goods_ship($virtual_goods, $msg, $order_sn, true))
                    {
                        $GLOBALS['_LANG']['pay_success'] .= '<div style="color:red;">'.$msg.'</div>'.$GLOBALS['_LANG']['virtual_goods_ship_fail'];
                    }

                    /* 如果订单没有配送方式，自动完成发货操作 */
                    if ($order['shipping_id'] == -1)
                    {
                    	ss_log("如果订单没有配送方式，自动完成发货操作 ");
                    	
                        /* 将订单标识为已发货状态，并记录发货记录 */
                        $sql = 'UPDATE ' . $GLOBALS['ecs']->table('order_info') .
                               " SET shipping_status = '" . SS_SHIPPED . "', shipping_time = '" . time() . "'" .
                               " WHERE order_id = '$order_id'";
                        $GLOBALS['db']->query($sql);

                         /* 记录订单操作记录 */
                        order_action($order_sn, OS_CONFIRMED, SS_SHIPPED, $pay_status, $note, $GLOBALS['_LANG']['buyer']);
                        $integral = integral_to_give($order);
                        log_account_change($order['user_id'], 0, 0, intval($integral['rank_points']), intval($integral['custom_points']), sprintf($GLOBALS['_LANG']['order_gift_integral'], $order['order_sn']),$order['order_sn']);
                    }
                }//end virtual_goods

                
                //////comment by wangcya, 20140626,这里进行佣金以及投保的操作等。
                ss_log("from order_paid, into function assign_commision_and_post_policy!");
                //mod by zhangxi, 20150305, 修改函数名,分配佣金和投保
				//yongJin2($order_sn, true);//modify by wangcya, add true
				
				
				if($pay_status==PS_PAYED)
				{
					
					if(function_exists('assign_commision_and_post_policy'))
					{
						ss_log('assign_commision_and_post_policy 函数存在,开始进入');
						assign_commision_and_post_policy($order_sn, true);
					}
					else
					{
						ss_log('assign_commision_and_post_policy 未定义'.ROOT_PATH . 'includes/lib_order.php');
						
						
						if(file_exists(ROOT_PATH . 'includes/lib_order.php'))
						{
							ss_log(ROOT_PATH . 'includes/lib_order.php 文件存在，开始include_once');
							include_once(ROOT_PATH . 'includes/lib_order.php');
							ss_log('include_once 完成');
							
						}
						else{
							
							ss_log(ROOT_PATH . 'includes/lib_order.php 不存在');
						}
							
						
						if(function_exists('assign_commision_and_post_policy'))
						{
							ss_log('重新包含lib_order.php，函数存在,开始进入');
							assign_commision_and_post_policy($order_sn, true);
						}
						
						
					}
					
				}
				else
				{
					ss_log('没有完全支付，不投保和发放佣金');
				}
                
                ////////////////////////////////////////////////////////////////
                
            }//PAY_ORDER
            elseif ($pay_log['order_type'] == PAY_SURPLUS)
            {
            	ss_log("用户预支付，部分用支付宝等其他方式支付");
            	ss_log("pay_log order_type: PAY_SURPLUS");
            	
                $sql = 'SELECT `id` FROM ' . $GLOBALS['ecs']->table('user_account') .  " WHERE `id` = '$pay_log[order_id]' AND `is_paid` = 1  LIMIT 1";
                ss_log($sql);
                
                $res_id=$GLOBALS['db']->getOne($sql);
                if(empty($res_id))
                {
                	ss_log(" 更新会员预付款的到款状态");
                    /* 更新会员预付款的到款状态 */
                    $sql = 'UPDATE ' . $GLOBALS['ecs']->table('user_account') .
                           " SET paid_time = '" .time(). "', is_paid = 1" .
                           " WHERE id = '$pay_log[order_id]' LIMIT 1";
                    ss_log($sql);
                    $GLOBALS['db']->query($sql);

                    /* 取得添加预付款的用户以及金额 */
                    $sql = "SELECT user_id, amount,payment FROM " . $GLOBALS['ecs']->table('user_account') .
                            " WHERE id = '$pay_log[order_id]'";
                    ss_log($sql);
                    $arr = $GLOBALS['db']->getRow($sql);

                    /* 修改会员账户金额 */
                    $_LANG = array();
                    include_once(ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/user.php');
                    
                    log_account_change($arr['user_id'], $arr['amount'], 0, 0, 0,
		                   $arr['payment']."充值", ACT_SAVING,'','',0,0,'money_recharge','user_money');
                    
                }
                else
                {
                	ss_log("没有，更新会员预付款的到款状态");
                }
            }//PAY_SURPLUS
            else
            {
            	ss_log("订单的其他状态类型 pay_log order_type：".$pay_log['order_type']);
            }
            
            
            
        }//end $pay_log && $pay_log['is_paid'] == 0
        else
        {//is_paid ,已经支付成功了。
        	ss_log("已经支付成功了，is_paid ：".$pay_log['is_paid']);
        	ss_log("in order_paid, 取得已发货的虚拟商品信息");
        	
             /* 取得已发货的虚拟商品信息 */
            $post_virtual_goods = get_virtual_goods($pay_log['order_id'], true);

            /* 有已发货的虚拟商品 */
            if (!empty($post_virtual_goods))
            {
                $msg = '';
                /* 检查两次刷新时间有无超过12小时 */
                ss_log("检查两次刷新时间有无超过12小时");
                
                $sql = 'SELECT pay_time, order_sn FROM ' . $GLOBALS['ecs']->table('order_info') . " WHERE order_id = '$pay_log[order_id]'";
                ss_log($sql);
                
                $row = $GLOBALS['db']->getRow($sql);
                $intval_time = time() - $row['pay_time'];
                if ($intval_time >= 0 && $intval_time < 3600 * 12)
                {
                    $virtual_card = array();
                    foreach ($post_virtual_goods as $code => $goods_list)
                    {
                        /* 只处理虚拟卡 */
                        if ($code == 'virtual_card')
                        {
                            foreach ($goods_list as $goods)
                            {
                                if ($info = virtual_card_result($row['order_sn'], $goods))
                                {
                                    $virtual_card[] = array('goods_id'=>$goods['goods_id'], 'goods_name'=>$goods['goods_name'], 'info'=>$info);
                                }
                            }

                            $GLOBALS['smarty']->assign('virtual_card',      $virtual_card);
                        }
                    }
                }
                else
                {
                    $msg = '<div>' .  $GLOBALS['_LANG']['please_view_order_detail'] . '</div>';
                }

                $GLOBALS['_LANG']['pay_success'] .= $msg;
            }//end post_virtual_goods

           /* 取得未发货虚拟商品 */
           $virtual_goods = get_virtual_goods($pay_log['order_id'], false);
           if (!empty($virtual_goods))
           {
               $GLOBALS['_LANG']['pay_success'] .= '<br />' . $GLOBALS['_LANG']['virtual_goods_ship_fail'];
           }
           
           //start add by wangcya, 20141204,支付宝再次触发////////////////////////////////////////////////
            
           /*del by wangcya, 20150330, 如果订单状态已经被更改，则没必要执行这里的。
           if($pay_log['order_id'])
           {
	           	$sql = 'SELECT order_id, user_id, order_sn ' .
	           			'FROM ' . $GLOBALS['ecs']->table('order_info') .
	           			" WHERE order_id = '$pay_log[order_id]'";
	           	ss_log($sql);
	           
	           	$order    = $GLOBALS['db']->getRow($sql);
	           	//$order_id = $order['order_id'];
	           	$order_sn = $order['order_sn'];
	           
	           	ss_log("已经支付成功了，取得已发货的商品信息,订单号：".$order_sn);
	           
	           
	           	//////////////如果再次触发，则再次进行投保的过程/////////////////////////
	           	ss_log("from order_paid, 从已支付的商品信息, into function assign_commision_and_post_policy!");
	           	
	           	//modify 支付宝充值会走这里，而order_sn 为空导致报错
	           	if($order_sn)
	           	{//comment by wangcya, 20150209, 虚拟产品的投保，可能这里要取消掉
		           	 //mod by zhangxi, 20150305, 修改函数名,分配佣金和投保
					//yongJin2($order_sn, true);//modify by wangcya, add true
					ss_log("将进入已支付商品的处理：assign_commision_and_post_policy ");
					assign_commision_and_post_policy($order_sn, true);
	           	}
	           	////////////////////////////////////////////////////////////////
           }
           */
           //end add by wangcya, 20141204/////////////////////////////////////////////////
                
        }//end 取得已发货的虚拟商品信息
    }
    else
    {
    	ss_log("in function order_paid, log_id<0 ".$log_id);
    }
    
    ss_log("将要离开函数order_paid");
}

/**
 * 更改微信订单状态
 * @param type $order_sn
 * @param type $trade_no
 * @param type $pay_status
 * @param type $note
 * cuikai 14-9-28 下午4:39
 */
function order_paid_reset_with_weixin($order_sn, $trade_no='', $pay_status = PS_PAYED, $note = '')
{
    $sql = "select order_id from " . $GLOBALS['ecs']->table('order_info') . " where order_sn='$order_sn'";    
    $order_id = $GLOBALS['db']->getOne($sql);
    $sql = "select log_id from " . $GLOBALS['ecs']->table('pay_log') . " where order_id='$order_id'";    
    $log_id = $GLOBALS['db']->getOne($sql);
    
    /* 取得支付编号 */
    $log_id = intval($log_id);
    if ($log_id > 0)
    {
        /* 取得要修改的支付记录信息 */
        $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('pay_log') .
                " WHERE log_id = '$log_id'";
        $pay_log = $GLOBALS['db']->getRow($sql);
        if ($pay_log && $pay_log['is_paid'] == 0)
        {
            /* 修改此次支付操作的状态为已付款 */
            $sql = 'UPDATE ' . $GLOBALS['ecs']->table('pay_log') .
                    " SET is_paid = '1' WHERE log_id = '$log_id'";
            $GLOBALS['db']->query($sql);

            /* 根据记录类型做相应处理 */
            if ($pay_log['order_type'] == PAY_ORDER)
            {
                /* 取得订单信息 */
                $sql = 'SELECT order_id, user_id, order_sn, consignee, address, tel, shipping_id, extension_code, extension_id, goods_amount ' .
                        'FROM ' . $GLOBALS['ecs']->table('order_info') .
                       " WHERE order_id = '$pay_log[order_id]'";
                $order    = $GLOBALS['db']->getRow($sql);
                $order_id = $order['order_id'];
                $order_sn = $order['order_sn'];

                /* 修改订单状态为已付款 */
                $sql = 'UPDATE ' . $GLOBALS['ecs']->table('order_info') .
                            " SET order_status = '" . OS_CONFIRMED . "', " .
                                " confirm_time = '" . time() . "', " .
                                " pay_status = '$pay_status', " .
                                " pay_time = '".time()."', " .
                                " money_paid = order_amount," .
                                " order_amount = 0,";
                if(!empty($trade_no)) 
                {
                    $sql .= " transaction_id='$trade_no' ";
                }
                
                $sql .= "WHERE order_id = '$order_id'";
                $GLOBALS['db']->query($sql);

                /* 记录订单操作记录 */
                order_action($order_sn, OS_CONFIRMED, SS_UNSHIPPED, $pay_status, $note, $GLOBALS['_LANG']['buyer']);

                /* 如果需要，发短信 */
                if ($GLOBALS['_CFG']['sms_order_payed'] == '1' && $GLOBALS['_CFG']['sms_shop_mobile'] != '')
                {
                    include_once(ROOT_PATH.'includes/cls_sms.php');
                    $sms = new sms();
                    $sms->send($GLOBALS['_CFG']['sms_shop_mobile'],
                        sprintf($GLOBALS['_LANG']['order_payed_sms'], $order_sn, $order['consignee'], $order['tel']),'', 13,1);
                }

                /* 对虚拟商品的支持 */
                $virtual_goods = get_virtual_goods($order_id);
                if (!empty($virtual_goods))
                {
                    $msg = '';
                    if (!virtual_goods_ship($virtual_goods, $msg, $order_sn, true))
                    {
                        $GLOBALS['_LANG']['pay_success'] .= '<div style="color:red;">'.$msg.'</div>'.$GLOBALS['_LANG']['virtual_goods_ship_fail'];
                    }

                    /* 如果订单没有配送方式，自动完成发货操作 */
                    if ($order['shipping_id'] == -1)
                    {
                        /* 将订单标识为已发货状态，并记录发货记录 */
                        $sql = 'UPDATE ' . $GLOBALS['ecs']->table('order_info') .
                               " SET shipping_status = '" . SS_SHIPPED . "', shipping_time = '" . time() . "'" .
                               " WHERE order_id = '$order_id'";
                        $GLOBALS['db']->query($sql);

                         /* 记录订单操作记录 */
                        order_action($order_sn, OS_CONFIRMED, SS_SHIPPED, $pay_status, $note, $GLOBALS['_LANG']['buyer']);
                        $integral = integral_to_give($order);
                        log_account_change($order['user_id'], 0, 0, intval($integral['rank_points']), intval($integral['custom_points']), sprintf($GLOBALS['_LANG']['order_gift_integral'], $order['order_sn']));
                    }
                }

            }
            elseif ($pay_log['order_type'] == PAY_SURPLUS)
            {
                $sql = 'SELECT `id` FROM ' . $GLOBALS['ecs']->table('user_account') .  " WHERE `id` = '$pay_log[order_id]' AND `is_paid` = 1  LIMIT 1";
                $res_id=$GLOBALS['db']->getOne($sql);
                if(empty($res_id))
                {
                    /* 更新会员预付款的到款状态 */
                    $sql = 'UPDATE ' . $GLOBALS['ecs']->table('user_account') .
                           " SET paid_time = '" .time(). "', is_paid = 1" .
                           " WHERE id = '$pay_log[order_id]' LIMIT 1";
                    $GLOBALS['db']->query($sql);

                    /* 取得添加预付款的用户以及金额 */
                    $sql = "SELECT user_id, amount FROM " . $GLOBALS['ecs']->table('user_account') .
                            " WHERE id = '$pay_log[order_id]'";
                    $arr = $GLOBALS['db']->getRow($sql);

                    /* 修改会员帐户金额 */
                    $_LANG = array();
                    include_once(ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/user.php');
                    log_account_change($arr['user_id'], $arr['amount'], 0, 0, 0, $_LANG['surplus_type_0'], ACT_SAVING);
                }
            }
        }
        else
        {
            /* 取得已发货的虚拟商品信息 */
            $post_virtual_goods = get_virtual_goods($pay_log['order_id'], true);

            /* 有已发货的虚拟商品 */
            if (!empty($post_virtual_goods))
            {
                $msg = '';
                /* 检查两次刷新时间有无超过12小时 */
                $sql = 'SELECT pay_time, order_sn FROM ' . $GLOBALS['ecs']->table('order_info') . " WHERE order_id = '$pay_log[order_id]'";
                $row = $GLOBALS['db']->getRow($sql);
                $intval_time = time() - $row['pay_time'];
                if ($intval_time >= 0 && $intval_time < 3600 * 12)
                {
                    $virtual_card = array();
                    foreach ($post_virtual_goods as $code => $goods_list)
                    {
                        /* 只处理虚拟卡 */
                        if ($code == 'virtual_card')
                        {
                            foreach ($goods_list as $goods)
                            {
                                if ($info = virtual_card_result($row['order_sn'], $goods))
                                {
                                    $virtual_card[] = array('goods_id'=>$goods['goods_id'], 'goods_name'=>$goods['goods_name'], 'info'=>$info);
                                }
                            }

                            $GLOBALS['smarty']->assign('virtual_card',      $virtual_card);
                        }
                    }
                }
                else
                {
                    $msg = '<div>' .  $GLOBALS['_LANG']['please_view_order_detail'] . '</div>';
                }

                $GLOBALS['_LANG']['pay_success'] .= $msg;
            }

           /* 取得未发货虚拟商品 */
           $virtual_goods = get_virtual_goods($pay_log['order_id'], false);
           if (!empty($virtual_goods))
           {
               $GLOBALS['_LANG']['pay_success'] .= '<br />' . $GLOBALS['_LANG']['virtual_goods_ship_fail'];
           }
        }
    }
}
?>