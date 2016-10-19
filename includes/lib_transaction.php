<?php

/**
 * ECSHOP 用户交易相关函数库
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: lib_transaction.php 17217 2011-01-19 06:29:08Z liubo $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

/**
 * 修改个人资料（Email, 性别，生日)
 *
 * @access  public
 * @param   array       $profile       array_keys(user_id int, email string, sex int, birthday string);
 *
 * @return  boolen      $bool
 */
function edit_profile($profile)
{
    if (empty($profile['user_id']))
    {
        $GLOBALS['err']->add($GLOBALS['_LANG']['not_login']);

        return false;
    }

    $cfg = array();
    $cfg['username'] = $GLOBALS['db']->getOne("SELECT user_name FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_id='" . $profile['user_id'] . "'");
    if (isset($profile['sex']))
    {
        $cfg['gender'] = intval($profile['sex']);
    }
    if (!empty($profile['email']))
    {
        if (!is_email($profile['email']))
        {
            $GLOBALS['err']->add(sprintf($GLOBALS['_LANG']['email_invalid'], $profile['email']));

            return false;
        }
        $cfg['email'] = $profile['email'];
    }
    if (!empty($profile['birthday']))
    {
        $cfg['bday'] = $profile['birthday'];
    }

    if (!$GLOBALS['user']->edit_user($cfg))
    {
        if ($GLOBALS['user']->error == ERR_EMAIL_EXISTS)
        {
            $GLOBALS['err']->add(sprintf($GLOBALS['_LANG']['email_exist'], $profile['email']));
        }
        else
        {
            $GLOBALS['err']->add('DB ERROR!');
        }

        return false;
    }
    /**
	 * 2014/8/2
	 *
	 * bhz
	 *
	 * 添加 real_name(用户真实姓名)
	 */
    /* 过滤非法的键值 */
    $other_key_array = array('msn', 'qq', 'office_phone', 'home_phone', 'mobile_phone', 'real_name');
    foreach ($profile['other'] as $key => $val)
    {
        //删除非法key值
        if (!in_array($key, $other_key_array))
        {
            unset($profile['other'][$key]);
        }
        else
        {
            $profile['other'][$key] =  htmlspecialchars(trim($val)); //防止用户输入javascript代码
        }
    }
    /* 修改在其他资料 */
    if (!empty($profile['other']))
    {	
        $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('users'), $profile['other'], 'UPDATE', "user_id = '$profile[user_id]'");
    }
	
    return true;
}

/**
 * 获取用户账号信息
 *
 * @access  public
 * @param   int       $user_id        用户user_id
 *
 * @return void
 */
function get_profile($user_id)
{
    global $user;


    /* 会员账号信息 */
    $info  = array();
    $infos = array();
	/***
	 * 修改时间：2014/7/23
	 * 修改者 :鲍洪州
	 * 功能：添加关联查询表user_info
	 */	
    $sql  = "SELECT u.* ,".
        	" ui.* ".    //add yes123 2014-12-02 拿用户的照片
		"FROM " .$GLOBALS['ecs']->table('users'). "AS u LEFT JOIN ".$GLOBALS['ecs']->table('user_info')."AS ui ON u.user_id=ui.uid WHERE user_id = '$user_id' ";
    $infos = $GLOBALS['db']->getRow($sql);
    
    $infos['user_name'] = addslashes($infos['user_name']);

    $row = $user->get_profile_by_name($infos['user_name']); //获取用户账号信息
    $_SESSION['email'] = $row['email'];    //注册SESSION

    /* 会员等级 */
    if ($infos['user_rank'] > 0)
    {
        $sql = "SELECT rank_id, rank_name, discount FROM ".$GLOBALS['ecs']->table('user_rank') .
               " WHERE rank_id = '$infos[user_rank]'";
    }
    else
    {
        $sql = "SELECT rank_id, rank_name, discount, min_points".
               " FROM ".$GLOBALS['ecs']->table('user_rank') .
               " WHERE min_points<= " . intval($infos['rank_points']) . " ORDER BY min_points DESC";
    }

    if ($row = $GLOBALS['db']->getRow($sql))
    {
        $infos['rank_name']     = $row['rank_name'];
    }
    else
    {
        $infos['rank_name'] = $GLOBALS['_LANG']['undifine_rank'];
    }

    $cur_date = date('Y-m-d H:i:s');

    /* 会员代金券 */
    $bonus = array();
    $sql = "SELECT type_name, type_money ".
           "FROM " .$GLOBALS['ecs']->table('bonus_type') . " AS t1, " .$GLOBALS['ecs']->table('user_bonus') . " AS t2 ".
           "WHERE t1.type_id = t2.bonus_type_id AND t2.user_id = '$user_id' AND t1.use_start_date <= '$cur_date' ".
           "AND t1.use_end_date > '$cur_date' AND t2.order_id = 0";
    $bonus = $GLOBALS['db']->getAll($sql);
    if ($bonus)
    {
        for ($i = 0, $count = count($bonus); $i < $count; $i++)
        {
            $bonus[$i]['type_money'] = price_format($bonus[$i]['type_money'], false);
        }
    }
	
    $infos['discount']    = $_SESSION['discount'] * 100 . "%";
    $infos['rank_points'] = isset($infos['rank_points']) ? $infos['rank_points'] : '';
    $infos['pay_points']  = isset($infos['pay_points'])  ? $infos['pay_points']  : 0;
    $infos['user_money']  = isset($infos['user_money'])  ? $infos['user_money']  : 0;
    $infos['sex']         = isset($infos['sex'])      ? $infos['sex']      : 0;
    $infos['birthday']    = isset($infos['birthday']) ? $infos['birthday'] : '';
    $infos['question']    = isset($infos['question']) ? htmlspecialchars($infos['question']) : '';

    $infos['user_money']  = price_format($infos['user_money'], false);
    $infos['pay_points']  = $infos['pay_points'] . $GLOBALS['_CFG']['integral_name'];
    $infos['bonus']       = $bonus;
    
	if($GLOBALS['_CFG']['member_email_validate'])
    {
        $infos['is_validate'] = $infos['is_validated'];
    }   

    return $infos;
}

/**
 * 取得收货人地址列表
 * @param   int     $user_id    用户编号
 * @return  array
 */
function get_consignee_list($user_id)
{
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('user_address') .
            " WHERE user_id = '$user_id' LIMIT 5";

    return $GLOBALS['db']->getAll($sql);
}

/**
 *  给指定用户添加一个指定代金券
 *
 * @access  public
 * @param   int         $user_id        用户ID
 * @param   string      $bouns_sn       代金券序列号
 *
 * @return  boolen      $result
 */
function add_bonus($user_id, $bouns_sn)
{
    if (empty($user_id))
    {
        $GLOBALS['err']->add($GLOBALS['_LANG']['not_login']);

        return false;
    }

    /* 查询代金券序列号是否已经存在 */
    $sql = "SELECT bonus_id, bonus_sn, user_id, bonus_type_id FROM " .$GLOBALS['ecs']->table('user_bonus') .
           " WHERE bonus_sn = '$bouns_sn'";
    $row = $GLOBALS['db']->getRow($sql);
    if ($row)
    {
        if ($row['user_id'] == 0)
        {
            //代金券没有被使用
            $sql = "SELECT send_end_date, use_end_date ".
                   " FROM " . $GLOBALS['ecs']->table('bonus_type') .
                   " WHERE type_id = '" . $row['bonus_type_id'] . "'";

            $bonus_time = $GLOBALS['db']->getRow($sql);

            $now = gmtime();
            if ($now > $bonus_time['use_end_date'])
            {
                $GLOBALS['err']->add($GLOBALS['_LANG']['bonus_use_expire']);
                return false;
            }

            $sql = "UPDATE " .$GLOBALS['ecs']->table('user_bonus') . " SET user_id = '$user_id' ".
                   "WHERE bonus_id = '$row[bonus_id]'";
            $result = $GLOBALS['db'] ->query($sql);
            if ($result)
            {
                 return true;
            }
            else
            {
                return $GLOBALS['db']->errorMsg();
            }
        }
        else
        {
            if ($row['user_id']== $user_id)
            {
                //代金券已经添加过了。
                $GLOBALS['err']->add($GLOBALS['_LANG']['bonus_is_used']);
            }
            else
            {
                //代金券被其他人使用过了。
                $GLOBALS['err']->add($GLOBALS['_LANG']['bonus_is_used_by_other']);
            }

            return false;
        }
    }
    else
    {
        //代金券不存在
        $GLOBALS['err']->add($GLOBALS['_LANG']['bonus_not_exist']);
        return false;
    }

}

/**
 *  获取用户指定范围的订单列表
 *
 * @access  public
 * @param   int         $user_id        用户ID号
 * @param   int         $num            列表最大数量
 * @param   int         $start          列表起始位置
 * @return  array       $order_list     订单列表
 */
function get_user_orders($user_id, $num = 10, $start = 0,$where_sql)
{
    /* 取得订单列表 */
    $arr    = array();	
	$order_by="ORDER BY oi.order_id DESC ";
	
	if(!empty($_REQUEST['order_by_money'])){
		$order_by=" ORDER BY goods_amount ".$_REQUEST['order_by_money'];
	}
			
	if(!empty($where_sql)){
		$where_sql = $where_sql;
	}else{
	    $where_sql = '';
	}
	
	//modify  2015-02-05 yes123 把LEFT 修改为INNER
/*    $sql = "SELECT p.pay_name,p.pay_id,oi.policy_num,oi.order_id, oi.order_sn, oi.order_status, oi.shipping_status, oi.policy_id, oi.pay_status,oi.receipt_assigned,oi.receipt_id ,oi.add_time,oi.need_receipt,oi.surplus ,oi.client_id," .
           "(oi.goods_amount + oi.shipping_fee + oi.insure_fee + oi.pay_fee + oi.pack_fee + oi.card_fee + oi.tax - oi.discount) AS total_fee ".
           ", ip.apply_num,ip.policy_no, ip.policy_status,ip.applicant_uid,ip.applicant_username,ip.insurer_code,ip.insurer_name ".//add by yes123 , 2014-12-05  
           " FROM " .$GLOBALS['ecs']->table('order_info') ." AS oi ".
           " INNER JOIN ".$GLOBALS['ecs']->table('payment')." AS p ON oi.pay_id = p.pay_id ".  //add by yes123 , 2014-12-02  支付方式
           " LEFT JOIN t_insurance_policy AS ip ON oi.order_id = ip.order_id ".  //add by wangcya, for bug[193],能够支持多人批量投保
           $where_sql ." group by oi.order_id ".$order_by;*/

/*    $sql = "SELECT u.user_name,u.real_name,p.pay_name,p.pay_id,oi.policy_num,oi.order_id, oi.order_sn, oi.order_status, oi.shipping_status, oi.policy_id, oi.pay_status,oi.receipt_assigned,oi.receipt_id ,oi.add_time,oi.need_receipt,oi.surplus ,oi.client_id," .
           "(oi.goods_amount + oi.shipping_fee + oi.insure_fee + oi.pay_fee + oi.pack_fee + oi.card_fee + oi.tax - oi.discount) AS total_fee ".
           ", ip.apply_num,ip.policy_no, ip.policy_status,ip.applicant_uid,ip.applicant_username,ip.insurer_code,ip.insurer_name ".//add by yes123 , 2014-12-05  
           " FROM " .$GLOBALS['ecs']->table('order_info') ." AS oi ".
           " INNER JOIN ".$GLOBALS['ecs']->table('users')." AS u ON u.user_id = oi.user_id ".  //add by yes123 , 2014-12-02  支付方式
           " LEFT JOIN ".$GLOBALS['ecs']->table('payment')." AS p ON oi.pay_id = p.pay_id ".  //add by yes123 , 2014-12-02  支付方式
           " LEFT JOIN t_insurance_policy AS ip ON oi.order_id = ip.order_id ".  //add by wangcya, for bug[193],能够支持多人批量投保
           $where_sql ." group by oi.order_id ".$order_by;*/
           
     $sql = "SELECT u.user_name,u.real_name,p.pay_name,p.pay_id,oi.*".
           " FROM " .$GLOBALS['ecs']->table('order_info') ." AS oi ".
           " LEFT JOIN ".$GLOBALS['ecs']->table('users')." AS u ON u.user_id = oi.user_id ".  //add by yes123 , 2014-12-02  支付方式
           " LEFT JOIN ".$GLOBALS['ecs']->table('payment')." AS p ON oi.pay_id = p.pay_id ".  //add by yes123 , 2014-12-02  支付方式
           $where_sql ." group by oi.order_id ".$order_by;

    ss_log('前台获取订单列表：'.$sql);
    $res = $GLOBALS['db']->SelectLimit($sql, $num, $start);
	
    ///////////////////////////////////////////////////////////////
    //include_once(ROOT_PATH . 'baoxian/source/function_debug.php');
    $attr_status = array("saved"=>"失败",
    		"insured"=>"成功",
    		"surrender"=>"退保",
    		"canceled"=>"已注销"
    );
    
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
    	//comment by zhangxi, 20150212, 保险公司的名称要修改，在这里加代码	
    	
		
    	//add by dingchaoyang 2014-11-28 关联日志表，查询服务费（建议服务费属于基础数据，直接写订单上，日志是日志
    	$serviceFeeSql = "select user_money from bx_account_log where change_type=2 and order_sn = '" . $row['order_sn'] . "'";
    	$serviceFeeSet = $GLOBALS['db']->getRow($serviceFeeSql);
    	$row['serviceFee'] = $serviceFeeSet['user_money'];
    	//end by dingchaoyang 2014-11-28
    	
    	
    	if($row['policy_num'] ==  $row['insured_policy_num'])
    	{//如果订单下面的保单个数和已经投保的个数相符，则表示完成
    		$row['policy_status'] = "完成";
    	}
    	else
    	{
		    if($row['order_status']==1 && $row['pay_status']==2) //订单状态是已支付，已确认
			{
				$row['policy_status_red'] = 1;//突出显示
				$row['policy_status'] = "未完成";
			}
			else
			{
				$row['policy_status'] = "";//没投保的就不填写
			}
    		
    	}

    	
    	/*start add by wangcya , 20140930, 显示订单商品 */
    	include_once(ROOT_PATH . 'includes/lib_order.php');
    	$goods_list = order_goods($row['order_id'],5);
    	foreach ($goods_list AS $key => $value)
    	{
    		$goods_list[$key]['market_price'] = price_format($value['market_price'], false);
    		$goods_list[$key]['goods_price']  = price_format($value['goods_price'], false);
    		$goods_list[$key]['subtotal']     = price_format($value['subtotal'], false);
    	}
    	
    	$row['goods_list'] = $goods_list;
    	//end add by wangcya , 20140930, 显示订单商品
    	
    	
    	//投保人
    	$row['applicant_username_arr'] = array();
    	if (strstr($row['applicant_username_str'], ',')) 
    	{
			$applicant_username_arr = explode(",",$row['applicant_username_str']);
			$row['applicant_username_arr'] = $applicant_username_arr;
		}
    	
    	
    	
    	
        if ($row['order_status'] == OS_UNCONFIRMED) //未确认
        {
            $row['handler'] = "<a href=\"user.php?act=cancel_order&order_id=" .$row['order_id']. "\" onclick=\"if (!confirm('".$GLOBALS['_LANG']['confirm_cancel']."')) return false;\">".$GLOBALS['_LANG']['cancel']."</a>";
            if ($row['pay_status'] == PS_UNPAYED) //未付款
            {
                //add yes123 2015-04-13 如果是阳光的产品，支付方式链接换成阳光的
            	$sql = "SELECT payUrl FROM t_insurance_policy_car_third_pre_order WHERE order_id=$row[order_id]";
            	$payUrl = $GLOBALS['db']->getOne($sql);
            	if($payUrl)
            	{
	                @$row['handler'] .= "&#12288;<a href=$payUrl>" .$GLOBALS['_LANG']['pay_money']. '</a>'; //付款
            	}
            	else
            	{
            		@$row['handler'] .= "&#12288;<a href=\"user.php?act=order_detail&order_id=" .$row['order_id']. '">' .$GLOBALS['_LANG']['pay_money']. '</a>'; //付款
            	}	
            }
            
        }
        else if ($row['order_status'] == OS_SPLITED) //已分单
        {
            /* 对配送状态的处理 */
            if ($row['shipping_status'] == SS_SHIPPED) //已发货
            {
                @$row['handler'] = "<a href=\"user.php?act=affirm_received&order_id=" .$row['order_id']. "\" onclick=\"if (!confirm('".$GLOBALS['_LANG']['confirm_received']."')) return false;\">".$GLOBALS['_LANG']['received']."</a>";
            }
            elseif ($row['shipping_status'] == SS_RECEIVED) //已收货
            {
                @$row['handler'] = '<span style="color:red">'.$GLOBALS['_LANG']['ss_received'] .'</span>'; //收货确认
            }
            else
            {
                if ($row['pay_status'] == PS_UNPAYED) //未付款
                {
                	//add yes123 2015-04-13 如果是阳光的产品，支付方式链接换成阳光的
                	$payUrl="";
                	if($row['type']=='third')
                	{
                		$sql = "SELECT payUrl FROM t_insurance_policy_car_third_pre_order WHERE order_id=$row[order_id]";
	                	ss_log('获取payUrl：'.$sql);
	                	$payUrl = $GLOBALS['db']->getOne($sql);
                	}

                	if($payUrl)
                	{
		                @$row['handler'] = "&#12288;<a href=$row[payUrl]>" .$GLOBALS['_LANG']['pay_money']. '</a>'; //付款
                	}
                	else
                	{
                		@$row['handler'] = "&#12288;<a href=\"user.php?act=order_detail&order_id=" .$row['order_id']. '">' .$GLOBALS['_LANG']['pay_money']. '</a>'; //付款
                	}
	                
                    
                }
                else
                {
                    @$row['handler'] = "<a href=\"user.php?act=order_detail&order_id=" .$row['order_id']. '">' .$GLOBALS['_LANG']['view_order']. '</a>'; //查看订单
                }

            }
        }
        else
        {
        	
        	if($row['order_status']==OS_CANCELED){
        		$row['handler'] = "<span style=color:red>".$GLOBALS['_LANG']['os'][$row['order_status']] ."</span>";
        	}else{
        		if($row['order_status']==OS_CONFIRMED && $row['pay_status']==PS_PAYED){
        			if($row['receipt_id']){
        				//判断发票是否已经受理，如果受理，不能修改，只能查看
        				if($row['receipt_assigned']){
        								$row['handler'] = "<span style=color:red>".$GLOBALS['_LANG']['os'][$row['order_status']];
        					//"<a style='color: #0E2DF6' href=javascript:show_fp(".$row['order_sn'].");>查看发票</a></span>";
        				}else{
        					$row['handler'] = "<span style=color:red>".$GLOBALS['_LANG']['os'][$row['order_status']];
        					//"<a style='color:red' href=javascript:fp(".$row['order_sn'].");>修改发票</a></span>";
        				}

        			}else{
        				$row['handler'] = "<span style=color:red>".$GLOBALS['_LANG']['os'][$row['order_status']] ; 
        					//"<a href=javascript:fp(".$row['order_sn'].");>申请发票</a></span>";
        				
        			}
        		}else{
        			$row['handler'] = "<span style=color:red>".$GLOBALS['_LANG']['os'][$row['order_status']] ."</span>";
        		}
        		
        	}
            
        }

        $row['shipping_status'] = ($row['shipping_status'] == SS_SHIPPED_ING) ? SS_PREPARING : $row['shipping_status'];
        /*$row['order_status'] = $GLOBALS['_LANG']['os'][$row['order_status']] . ',' . $GLOBALS['_LANG']['ps'][$row['pay_status']] . ',' . $GLOBALS['_LANG']['ss'][$row['shipping_status']];*/
		/**
		 * 2014/8/6
		 * bhz
		 * 取消 未发货 状态
		 */
        //add by dingchaoyang 2014-11-28 订单状态 和付款状态独立出来
        $row['orderStatus'] = $GLOBALS['_LANG']['os'][$row['order_status']];
        $row['orderStatusID'] = $row['order_status'];
        //end by dingchaoyang 2014-11-28
		$row['order_status'] = $GLOBALS['_LANG']['os'][$row['order_status']] . ',' . $GLOBALS['_LANG']['ps'][$row['pay_status']];
		
		//add by out_trade_no for pay dingchaoyang 2014-12-27
		$log_id    = get_paylog_id($row['order_id'], $pay_type = PAY_ORDER);
		//end
		
		//comment by zhangxi, 20150212, 订单列表信息
        $arr[] = array('order_id'       => $row['order_id'],
                       'order_sn'       => $row['order_sn'],
                       'user_name'       => $row['user_name'], //add yes123 2015-03-09 
                       'real_name'       => $row['real_name'],
                       'order_sn'       => $row['order_sn'],
                      // 'order_time'   => local_date($GLOBALS['_CFG']['time_format'], $row['add_time']),
					  'order_time'      => date('Y-m-d H:i', $row['add_time']),
                       'policy_status'   => $row['policy_status'],
        				'policy_num'   => $row['policy_num'],
        				'order_status'   => $row['order_status'],
        				//'applicant_username'  =>$row['applicant_username'],
        				//'insurer_name'  =>$row['insurer_name'],
        				//'insurer_code'  =>$row['insurer_code'],
        				//'applicant_uid'  =>$row['applicant_uid'],//add by yes123 , 2014-11-26
        				'pay_name'  =>$row['pay_name'],//add by yes123 , 2014-12-02  支付方式
                      // 'total_fee'      => price_format($row['total_fee'], false),
                       'formated_goods_amount'	=> price_format($row['goods_amount'], false),
                       'goods_amount'	=> $row['goods_amount'],
                        'goods_list'    => $row['goods_list'],//add by wangcya , 20140930
        				'policy_id'     => $row['policy_id'],
        				'need_receipt'     => $row['need_receipt'],
                       'handler'        => $row['handler'],
                       'applicant_username_str'        => $row['applicant_username_str'], 
                       'applicant_username_arr'        => $row['applicant_username_arr'],
                       //add by dingchaoyang 2014-11-28
                       'order_time_all'=> date('Y-m-d H:i', $row['add_time']),//返回带年的时间
                       //'amount'        =>  $row['total_fee'],//订单金额
                       'serviceFee'    => $row['serviceFee'],//服务费
                       'orderStatus'   => $row['orderStatus'],//订单状态
                       'orderStatusID' => $row['orderStatusID'],//订单状态id
                       'payStatus'     => $GLOBALS['_LANG']['ps'][$row['pay_status']],//付款状态
                     //  'policyNO'      =>$row['policy_no'],//保单号
                      // 'policyNum'     =>$row['apply_num'],//投保份数
                       'payType'       =>$row['pay_id'],//支付方式
                       'useBalance'    =>$row['surplus'],//使用余额
                       'client_id'    =>$row['client_id'],//使用余额
                       'out_trade_no'        =>$row['order_sn'].'-'. $log_id //日志id
                       //end by dingchaoyang 
        );
    }

    return $arr;
}




/**
 * 取消一个用户订单
 *
 * @access  public
 * @param   int         $order_id       订单ID
 * @param   int         $user_id        用户ID
 *
 * @return void
 */
function cancel_order($order_id, $user_id = 0)
{
    /* 查询订单信息，检查状态 */
    $sql = "SELECT user_id, order_id, order_sn , surplus , integral , bonus_id, order_status, shipping_status, pay_status FROM " .$GLOBALS['ecs']->table('order_info') ." WHERE order_id = '$order_id'";
    $order = $GLOBALS['db']->GetRow($sql);

    if (empty($order))
    {
        $GLOBALS['err']->add($GLOBALS['_LANG']['order_exist']);
        return false;
    }

    // 如果用户ID大于0，检查订单是否属于该用户
    if ($user_id > 0 && $order['user_id'] != $user_id)
    {
        $GLOBALS['err'] ->add($GLOBALS['_LANG']['no_priv']);

        return false;
    }

    // 订单状态只能是“未确认”或“已确认”
    if ($order['order_status'] != OS_UNCONFIRMED && $order['order_status'] != OS_CONFIRMED)
    {
        $GLOBALS['err']->add($GLOBALS['_LANG']['current_os_not_unconfirmed']);

        return false;
    }

    //订单一旦确认，不允许用户取消
    if ( $order['order_status'] == OS_CONFIRMED)
    {
        $GLOBALS['err']->add($GLOBALS['_LANG']['current_os_already_confirmed']);

        return false;
    }

    // 发货状态只能是“未发货”
    if ($order['shipping_status'] != SS_UNSHIPPED)
    {
        $GLOBALS['err']->add($GLOBALS['_LANG']['current_ss_not_cancel']);

        return false;
    }

    // 如果付款状态是“已付款”、“付款中”，不允许取消，要取消和商家联系
    if ($order['pay_status'] != PS_UNPAYED)
    {
        $GLOBALS['err']->add($GLOBALS['_LANG']['current_ps_not_cancel']);

        return false;
    }

    //将用户订单设置为取消
    $sql = "UPDATE ".$GLOBALS['ecs']->table('order_info') ." SET order_status = '".OS_CANCELED."' WHERE order_id = '$order_id'";
    if ($GLOBALS['db']->query($sql))
    {
        /* 记录log */
        order_action($order['order_sn'], OS_CANCELED, $order['shipping_status'], PS_UNPAYED,$GLOBALS['_LANG']['buyer_cancel'],'buyer');
        /* 退货用户余额、积分、代金券 */
        if ($order['user_id'] > 0 && $order['surplus'] > 0)
        {
            $change_desc = sprintf($GLOBALS['_LANG']['return_surplus_on_cancel'], $order['order_sn']);
            log_account_change($order['user_id'], $order['surplus'], 0, 0, 0, $change_desc);
        }
        if ($order['user_id'] > 0 && $order['integral'] > 0)
        {
            $change_desc = sprintf($GLOBALS['_LANG']['return_integral_on_cancel'], $order['order_sn']);
            log_account_change($order['user_id'], 0, 0, 0, $order['integral'], $change_desc);
        }
        if ($order['user_id'] > 0 && $order['bonus_id'] > 0)
        {
            change_user_bonus($order['bonus_id'], $order['order_id'], false);
        }

        /* 如果使用库存，且下订单时减库存，则增加库存 */
        if ($GLOBALS['_CFG']['use_storage'] == '1' && $GLOBALS['_CFG']['stock_dec_time'] == SDT_PLACE)
        {
            change_order_goods_storage($order['order_id'], false, 1);
        }

        /* 修改订单 */
        $arr = array(
            'bonus_id'  => 0,
            'bonus'     => 0,
            'integral'  => 0,
            'integral_money'    => 0,
            'surplus'   => 0
        );
        update_order($order['order_id'], $arr);

        return true;
    }
    else
    {
        die($GLOBALS['db']->errorMsg());
    }

}

/**
 * 确认一个用户订单
 *
 * @access  public
 * @param   int         $order_id       订单ID
 * @param   int         $user_id        用户ID
 *
 * @return  bool        $bool
 */
function affirm_received($order_id, $user_id = 0)
{
    /* 查询订单信息，检查状态 */
    $sql = "SELECT user_id, order_sn , order_status, shipping_status, pay_status FROM ".$GLOBALS['ecs']->table('order_info') ." WHERE order_id = '$order_id'";

    $order = $GLOBALS['db']->GetRow($sql);

    // 如果用户ID大于 0 。检查订单是否属于该用户
    if ($user_id > 0 && $order['user_id'] != $user_id)
    {
        $GLOBALS['err'] -> add($GLOBALS['_LANG']['no_priv']);

        return false;
    }
    /* 检查订单 */
    elseif ($order['shipping_status'] == SS_RECEIVED)
    {
        $GLOBALS['err'] ->add($GLOBALS['_LANG']['order_already_received']);

        return false;
    }
    elseif ($order['shipping_status'] != SS_SHIPPED)
    {
        $GLOBALS['err']->add($GLOBALS['_LANG']['order_invalid']);

        return false;
    }
    /* 修改订单发货状态为“确认收货” */
    else
    {
        $sql = "UPDATE " . $GLOBALS['ecs']->table('order_info') . " SET shipping_status = '" . SS_RECEIVED . "' WHERE order_id = '$order_id'";
        if ($GLOBALS['db']->query($sql))
        {
            /* 记录日志 */
            order_action($order['order_sn'], $order['order_status'], SS_RECEIVED, $order['pay_status'], '', $GLOBALS['_LANG']['buyer']);

            return true;
        }
        else
        {
            die($GLOBALS['db']->errorMsg());
        }
    }

}

/**
 * 保存用户的收货人信息
 * 如果收货人信息中的 id 为 0 则新增一个收货人信息
 *
 * @access  public
 * @param   array   $consignee
 * @param   boolean $default        是否将该收货人信息设置为默认收货人信息
 * @return  boolean
 */
function save_consignee($consignee, $default=false)
{
    if ($consignee['address_id'] > 0)
    {
        /* 修改地址 */
        $res = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_address'), $consignee, 'UPDATE', 'address_id = ' . $consignee['address_id']." AND `user_id`= '".$_SESSION['user_id']."'");
    }
    else
    {
        /* 添加地址 */
        $res = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_address'), $consignee, 'INSERT');
        $consignee['address_id'] = $GLOBALS['db']->insert_id();
    }

    if ($default)
    {
        /* 保存为用户的默认收货地址 */
        $sql = "UPDATE " . $GLOBALS['ecs']->table('users') .
            " SET address_id = '$consignee[address_id]' WHERE user_id = '$_SESSION[user_id]'";

        $res = $GLOBALS['db']->query($sql);
    }

    return $res !== false;
}

/**
 * 删除一个收货地址
 *
 * @access  public
 * @param   integer $id
 * @return  boolean
 */
function drop_consignee($id)
{
    $sql = "SELECT user_id FROM " .$GLOBALS['ecs']->table('user_address') . " WHERE address_id = '$id'";
    $uid = $GLOBALS['db']->getOne($sql);

    if ($uid != $_SESSION['user_id'])
    {
        return false;
    }
    else
    {
        $sql = "DELETE FROM " .$GLOBALS['ecs']->table('user_address') . " WHERE address_id = '$id'";
        $res = $GLOBALS['db']->query($sql);

        return $res;
    }
}

/**
 *  添加或更新指定用户收货地址
 *
 * @access  public
 * @param   array       $address
 * @return  bool
 */
function update_address($address)
{
    $address_id = intval($address['address_id']);
    unset($address['address_id']);

    if ($address_id > 0)
    {
         /* 更新指定记录 */
        $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_address'), $address, 'UPDATE', 'address_id = ' .$address_id . ' AND user_id = ' . $address['user_id']);
    }
    else
    {
        /* 插入一条新记录 */
        $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_address'), $address, 'INSERT');
        $address_id = $GLOBALS['db']->insert_id();
    }

    if (isset($address['defalut']) && $address['default'] > 0 && isset($address['user_id']))
    {
        $sql = "UPDATE ".$GLOBALS['ecs']->table('users') .
                " SET address_id = '".$address_id."' ".
                " WHERE user_id = '" .$address['user_id']. "'";
        $GLOBALS['db'] ->query($sql);
    }

    return true;
}

/**
 *  获取指订单的详情
 *
 * @access  public
 * @param   int         $order_id       订单ID
 * @param   int         $user_id        用户ID
 *
 * @return   arr        $order          订单所有信息的数组
 */
function get_order_detail($order_id, $user_id = 0)
{
    include_once(ROOT_PATH . 'includes/lib_order.php');
    $order_id = intval($order_id);
    if ($order_id <= 0)
    {
        $GLOBALS['err']->add($GLOBALS['_LANG']['invalid_order_id']);

        return false;
    }
    $order = order_info($order_id);
  
    //检查订单是否属于该用户
    // modify yes123 2015-01-30 添加C端订单详情查询
    //if ($user_id > 0 && $user_id != $order['user_id'])
    if ($user_id > 0 && ($user_id != $order['user_id'] && $user_id != $order['client_id']))
    {
        $GLOBALS['err']->add($GLOBALS['_LANG']['no_priv']);
        
        return false;
    }

    /* 对发货号处理 */
    if (!empty($order['invoice_no']))
    {
         $shipping_code = $GLOBALS['db']->GetOne("SELECT shipping_code FROM ".$GLOBALS['ecs']->table('shipping') ." WHERE shipping_id = '$order[shipping_id]'");
         $plugin = ROOT_PATH.'includes/modules/shipping/'. $shipping_code. '.php';
         if (file_exists($plugin))
        {
              include_once($plugin);
              $shipping = new $shipping_code;
              $order['invoice_no'] = $shipping->query($order['invoice_no']);
        }
    }

    /* 只有未确认才允许用户修改订单地址 */
    if ($order['order_status'] == OS_UNCONFIRMED)
    {
        $order['allow_update_address'] = 1; //允许修改收货地址
    }
    else
    {
        $order['allow_update_address'] = 0;
    }

    /* 获取订单中实体商品数量 */
    $order['exist_real_goods'] = exist_real_goods($order_id);

    /* 如果是未付款状态，生成支付按钮 */
    if ($order['pay_status'] == PS_UNPAYED &&
        ($order['order_status'] == OS_UNCONFIRMED ||
        $order['order_status'] == OS_CONFIRMED))
    {
        /*
         * 在线支付按钮
         */
        //支付方式信息
        $payment_info = array();
        //modify yes123 2015-11-29 如果是渠道，获取自己的支付方式配置
        $payment_info = get_payment_order($order);
        //$payment_info = payment_info($order['pay_id']);
        //无效支付方式
        if ($payment_info === false)
        {
            $order['pay_online'] = '';
        }
        else
        {
        	if($order['type']=='third')
        	{
        		$sql = "SELECT payUrl FROM t_insurance_policy_car_third_pre_order WHERE order_id=$order[order_id]";
            	ss_log('获取payUrl：'.$sql);
            	$payUrl = $GLOBALS['db']->getOne($sql);
            	if($payUrl)
            	{
	               $order['pay_online']="<a href='$payUrl' class='btn_on02'>付款</a>";
            	}
        	}
        	else
        	{
	            //取得支付信息，生成支付代码
	            $payment = unserialize_config($payment_info['pay_config']);
	
	            //获取需要支付的log_id
	            $order['log_id']    = get_paylog_id($order['order_id'], $pay_type = PAY_ORDER);
	            $order['user_name'] = $_SESSION['user_name'];
	            $order['pay_desc']  = $payment_info['pay_desc'];
	
	            /* 调用相应的支付方式文件 */
	            include_once(ROOT_PATH . 'includes/modules/payment/' . $payment_info['pay_code'] . '.php');
	
	            /* 取得在线支付方式的支付按钮 */
	            $pay_obj    = new $payment_info['pay_code'];
	            $order['pay_online'] = $pay_obj->get_code($order, $payment);
        	}
        }
    }
    else
    {
        $order['pay_online'] = '';
    }

    /* 无配送时的处理 */
    $order['shipping_id'] == -1 and $order['shipping_name'] = $GLOBALS['_LANG']['shipping_not_need'];

    /* 其他信息初始化 */
    $order['how_oos_name']     = $order['how_oos'];
    $order['how_surplus_name'] = $order['how_surplus'];

    /* 虚拟商品付款后处理 */
    if ($order['pay_status'] != PS_UNPAYED)
    {
        /* 取得已发货的虚拟商品信息 */
        $virtual_goods = get_virtual_goods($order_id, true);
        $virtual_card = array();
        foreach ($virtual_goods AS $code => $goods_list)
        {
            /* 只处理虚拟卡 */
            if ($code == 'virtual_card')
            {
                foreach ($goods_list as $goods)
                {
                    if ($info = virtual_card_result($order['order_sn'], $goods))
                    {
                        $virtual_card[] = array('goods_id'=>$goods['goods_id'], 'goods_name'=>$goods['goods_name'], 'info'=>$info);
                    }
                }
            }
            /* 处理超值礼包里面的虚拟卡 */
            if ($code == 'package_buy')
            {
                foreach ($goods_list as $goods)
                {
                    $sql = 'SELECT g.goods_id FROM ' . $GLOBALS['ecs']->table('package_goods') . ' AS pg, ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
                           "WHERE pg.goods_id = g.goods_id AND pg.package_id = '" . $goods['goods_id'] . "' AND extension_code = 'virtual_card'";
                    $vcard_arr = $GLOBALS['db']->getAll($sql);

                    foreach ($vcard_arr AS $val)
                    {
                        if ($info = virtual_card_result($order['order_sn'], $val))
                        {
                            $virtual_card[] = array('goods_id'=>$goods['goods_id'], 'goods_name'=>$goods['goods_name'], 'info'=>$info);
                        }
                    }
                }
            }
        }
        $var_card = deleteRepeat($virtual_card);
        $GLOBALS['smarty']->assign('virtual_card', $var_card);
    }

    /* 确认时间 支付时间 发货时间 */
    if ($order['confirm_time'] > 0 && ($order['order_status'] == OS_CONFIRMED || $order['order_status'] == OS_SPLITED || $order['order_status'] == OS_SPLITING_PART))
    {
       	//$order['confirm_time'] = sprintf($GLOBALS['_LANG']['confirm_time'], local_date($GLOBALS['_CFG']['time_format'], $order['confirm_time']));
        $order['confirm_time'] = date("Y-m-d H:i:s",$order['confirm_time']);
    }
    else
    {
        $order['confirm_time'] = '';
    }
    if ($order['pay_time'] > 0 && $order['pay_status'] != PS_UNPAYED)
    {
        //$order['pay_time'] = sprintf($GLOBALS['_LANG']['pay_time'], local_date($GLOBALS['_CFG']['time_format'], $order['pay_time']));
        $order['pay_time'] = date("Y-m-d H:i:s",$order['pay_time']);
    }
    else
    {
        $order['pay_time'] = '';
    }
    if ($order['shipping_time'] > 0 && in_array($order['shipping_status'], array(SS_SHIPPED, SS_RECEIVED)))
    {
        $order['shipping_time'] = sprintf($GLOBALS['_LANG']['shipping_time'], local_date($GLOBALS['_CFG']['time_format'], $order['shipping_time']));
    }
    else
    {
        $order['shipping_time'] = '';
    }
 
    return $order;

}

/**
 *  获取用户可以和并的订单数组
 *
 * @access  public
 * @param   int         $user_id        用户ID
 *
 * @return  array       $merge          可合并订单数组
 */
function get_user_merge($user_id)
{
    include_once(ROOT_PATH . 'includes/lib_order.php');
    $sql  = "SELECT order_sn FROM ".$GLOBALS['ecs']->table('order_info') .
            " WHERE user_id  = '$user_id' " . order_query_sql('unprocessed') .
                "AND extension_code = '' ".
            " ORDER BY add_time DESC";
    $list = $GLOBALS['db']->GetCol($sql);

    $merge = array();
    foreach ($list as $val)
    {
        $merge[$val] = $val;
    }

    return $merge;
}

/**
 *  合并指定用户订单
 *
 * @access  public
 * @param   string      $from_order         合并的从订单号
 * @param   string      $to_order           合并的主订单号
 *
 * @return  boolen      $bool
 */
function merge_user_order($from_order, $to_order, $user_id = 0)
{
    if ($user_id > 0)
    {
        /* 检查订单是否属于指定用户 */
        if (strlen($to_order) > 0)
        {
            $sql = "SELECT user_id FROM " .$GLOBALS['ecs']->table('order_info').
                   " WHERE order_sn = '$to_order'";
            $order_user = $GLOBALS['db']->getOne($sql);
            if ($order_user != $user_id)
            {
                $GLOBALS['err']->add($GLOBALS['_LANG']['no_priv']);
            }
        }
        else
        {
            $GLOBALS['err']->add($GLOBALS['_LANG']['order_sn_empty']);
            return false;
        }
    }

    $result = merge_order($from_order, $to_order);
    if ($result === true)
    {
        return true;
    }
    else
    {
        $GLOBALS['err']->add($result);
        return false;
    }
}

/**
 *  将指定订单中的商品添加到购物车
 *
 * @access  public
 * @param   int         $order_id
 *
 * @return  mix         $message        成功返回true, 错误返回出错信息
 */
function return_to_cart($order_id)
{
    /* 初始化基本件数量 goods_id => goods_number */
    $basic_number = array();

    /* 查订单商品：不考虑赠品 */
    $sql = "SELECT goods_id, product_id,goods_number, goods_attr, parent_id, goods_attr_id" .
            " FROM " . $GLOBALS['ecs']->table('order_goods') .
            " WHERE order_id = '$order_id' AND is_gift = 0 AND extension_code <> 'package_buy'" .
            " ORDER BY parent_id ASC";
    $res = $GLOBALS['db']->query($sql);

    $time = gmtime();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        // 查该商品信息：是否删除、是否上架

        $sql = "SELECT goods_sn, goods_name, goods_number, market_price, " .
                "IF(is_promote = 1 AND '$time' BETWEEN promote_start_date AND promote_end_date, promote_price, shop_price) AS goods_price," .
                "is_real, extension_code, is_alone_sale, goods_type " .
                "FROM " . $GLOBALS['ecs']->table('goods') .
                " WHERE goods_id = '$row[goods_id]' " .
                " AND is_delete = 0 LIMIT 1";
        $goods = $GLOBALS['db']->getRow($sql);

        // 如果该商品不存在，处理下一个商品
        if (empty($goods))
        {
            continue;
        }
        if($row['product_id'])
        {
            $order_goods_product_id=$row['product_id'];
            $sql="SELECT product_number from ".$GLOBALS['ecs']->table('products')."where product_id='$order_goods_product_id'";
            $product_number=$GLOBALS['db']->getOne($sql);
        }
        // 如果使用库存，且库存不足，修改数量
        if ($GLOBALS['_CFG']['use_storage'] == 1 && ($row['product_id']?($product_number<$row['goods_number']):($goods['goods_number'] < $row['goods_number'])))
        {
            if ($goods['goods_number'] == 0 || $product_number=== 0)
            {
                // 如果库存为0，处理下一个商品
                continue;
            }
            else
            {
                if($row['product_id'])
                {
                 $row['goods_number']=$product_number;
                }
                else
                {
                // 库存不为0，修改数量
                $row['goods_number'] = $goods['goods_number'];
                }
            }
        }

        //检查商品价格是否有会员价格
        $sql = "SELECT goods_number FROM" . $GLOBALS['ecs']->table('cart') . " " .
                "WHERE session_id = '" . SESS_ID . "' " .
                "AND goods_id = '" . $row['goods_id'] . "' " .
                "AND rec_type = '" . CART_GENERAL_GOODS . "' LIMIT 1";
        $temp_number = $GLOBALS['db']->getOne($sql);
        $row['goods_number'] += $temp_number;

        $attr_array           = empty($row['goods_attr_id']) ? array() : explode(',', $row['goods_attr_id']);
        $goods['goods_price'] = get_final_price($row['goods_id'], $row['goods_number'], true, $attr_array);

        // 要返回购物车的商品
        $return_goods = array(
            'goods_id'      => $row['goods_id'],
            'goods_sn'      => addslashes($goods['goods_sn']),
            'goods_name'    => addslashes($goods['goods_name']),
            'market_price'  => $goods['market_price'],
            'goods_price'   => $goods['goods_price'],
            'goods_number'  => $row['goods_number'],
            'goods_attr'    => empty($row['goods_attr']) ? '' : addslashes($row['goods_attr']),
            'goods_attr_id'    => empty($row['goods_attr_id']) ? '' : addslashes($row['goods_attr_id']),
            'is_real'       => $goods['is_real'],
            'extension_code'=> addslashes($goods['extension_code']),
            'parent_id'     => '0',
            'is_gift'       => '0',
            'rec_type'      => CART_GENERAL_GOODS
        );

        // 如果是配件
        if ($row['parent_id'] > 0)
        {
            // 查询基本件信息：是否删除、是否上架、能否作为普通商品销售
            $sql = "SELECT goods_id " .
                    "FROM " . $GLOBALS['ecs']->table('goods') .
                    " WHERE goods_id = '$row[parent_id]' " .
                    " AND is_delete = 0 AND is_on_sale = 1 AND is_alone_sale = 1 LIMIT 1";
            $parent = $GLOBALS['db']->getRow($sql);
            if ($parent)
            {
                // 如果基本件存在，查询组合关系是否存在
                $sql = "SELECT goods_price " .
                        "FROM " . $GLOBALS['ecs']->table('group_goods') .
                        " WHERE parent_id = '$row[parent_id]' " .
                        " AND goods_id = '$row[goods_id]' LIMIT 1";
                $fitting_price = $GLOBALS['db']->getOne($sql);
                if ($fitting_price)
                {
                    // 如果组合关系存在，取配件价格，取基本件数量，改parent_id
                    $return_goods['parent_id']      = $row['parent_id'];
                    $return_goods['goods_price']    = $fitting_price;
                    $return_goods['goods_number']   = $basic_number[$row['parent_id']];
                }
            }
        }
        else
        {
            // 保存基本件数量
            $basic_number[$row['goods_id']] = $row['goods_number'];
        }

        // 返回购物车：看有没有相同商品
        $sql = "SELECT goods_id " .
                "FROM " . $GLOBALS['ecs']->table('cart') .
                " WHERE session_id = '" . SESS_ID . "' " .
                " AND goods_id = '$return_goods[goods_id]' " .
                " AND goods_attr = '$return_goods[goods_attr]' " .
                " AND parent_id = '$return_goods[parent_id]' " .
                " AND is_gift = 0 " .
                " AND rec_type = '" . CART_GENERAL_GOODS . "'";
        $cart_goods = $GLOBALS['db']->getOne($sql);
        if (empty($cart_goods))
        {
            // 没有相同商品，插入
            $return_goods['session_id'] = SESS_ID;
            $return_goods['user_id']    = $_SESSION['user_id'];
            $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('cart'), $return_goods, 'INSERT');
        }
        else
        {
            // 有相同商品，修改数量
            $sql = "UPDATE " . $GLOBALS['ecs']->table('cart') . " SET " .
                    "goods_number = '" . $return_goods['goods_number'] . "' " .
                    ",goods_price = '" . $return_goods['goods_price'] . "' " .
                    "WHERE session_id = '" . SESS_ID . "' " .
                    "AND goods_id = '" . $return_goods['goods_id'] . "' " .
                    "AND rec_type = '" . CART_GENERAL_GOODS . "' LIMIT 1";
            $GLOBALS['db']->query($sql);
        }
    }

    // 清空购物车的赠品
    $sql = "DELETE FROM " . $GLOBALS['ecs']->table('cart') .
            " WHERE session_id = '" . SESS_ID . "' AND is_gift = 1";
    $GLOBALS['db']->query($sql);

    return true;
}

/**
 *  保存用户收货地址
 *
 * @access  public
 * @param   array   $address        array_keys(consignee string, email string, address string, zipcode string, tel string, mobile stirng, sign_building string, best_time string, order_id int)
 * @param   int     $user_id        用户ID
 *
 * @return  boolen  $bool
 */
function save_order_address($address, $user_id)
{
    $GLOBALS['err']->clean();
    /* 数据验证 */
    empty($address['consignee']) and $GLOBALS['err']->add($GLOBALS['_LANG']['consigness_empty']);
    empty($address['address']) and $GLOBALS['err']->add($GLOBALS['_LANG']['address_empty']);
    $address['order_id'] == 0 and $GLOBALS['err']->add($GLOBALS['_LANG']['order_id_empty']);
    if (empty($address['email']))
    {
        $GLOBALS['err']->add($GLOBALS['email_empty']);
    }
    else
    {
        if (!is_email($address['email']))
        {
            $GLOBALS['err']->add(sprintf($GLOBALS['_LANG']['email_invalid'], $address['email']));
        }
    }
    if ($GLOBALS['err']->error_no > 0)
    {
        return false;
    }

    /* 检查订单状态 */
    $sql = "SELECT user_id, order_status FROM " .$GLOBALS['ecs']->table('order_info'). " WHERE order_id = '" .$address['order_id']. "'";
    $row = $GLOBALS['db']->getRow($sql);
    if ($row)
    {
        if ($user_id > 0 && $user_id != $row['user_id'])
        {
            $GLOBALS['err']->add($GLOBALS['_LANG']['no_priv']);
            return false;
        }
        if ($row['order_status'] != OS_UNCONFIRMED)
        {
            $GLOBALS['err']->add($GLOBALS['_LANG']['require_unconfirmed']);
            return false;
        }
        $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'), $address, 'UPDATE', "order_id = '$address[order_id]'");
        return true;
    }
    else
    {
        /* 订单不存在 */
        $GLOBALS['err']->add($GLOBALS['_LANG']['order_exist']);
        return false;
    }
}

/**
 *
 * @access  public
 * @param   int         $user_id         用户ID
 * @param   int         $num             列表显示条数
 * @param   int         $start           显示起始位置
 *
 * @return  array       $arr             红保列表
 */
function get_user_bouns_list($user_id, $num = 10, $start = 0)
{
	global $bonus_type_list;
    $sql = "SELECT u.* ".
           " FROM " .$GLOBALS['ecs']->table('user_bonus'). " AS u WHERE u.user_id = '" .$user_id. "' ORDER BY bonus_id DESC";
    $res = $GLOBALS['db']->selectLimit($sql, $num, $start);
    $arr = array();

    $day = getdate();
    $cur_date = local_mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']);

    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        /* 先判断是否被使用，然后判断是否开始或过期 */
        if (empty($row['order_id']))
        {
            /* 没有被使用 */
            if ($row['use_s_date'] > $cur_date)
            {
                $row['status'] = $GLOBALS['_LANG']['not_start'];
            }
            else if ($row['use_e_date'] < $cur_date)
            {
                $row['status'] = $GLOBALS['_LANG']['overdue'];
            }
            else
            {
                $row['status'] = $GLOBALS['_LANG']['not_use'];
            }
        }
        else
        {
            $row['status'] = '<a href="user.php?act=order_detail&order_id=' .$row['order_id']. '" >' .$GLOBALS['_LANG']['had_use']. '</a>';
        }

        $row['use_startdate']   = local_date($GLOBALS['_CFG']['date_format'], $row['use_s_date']);
        $row['use_enddate']     = local_date($GLOBALS['_CFG']['date_format'], $row['use_e_date']);
		
		$row['type_name'] = $bonus_type_list[$row['source_type']];
		
		
        $arr[] = $row;
    }
    return $arr;

}

/**
 * 获得会员的团购活动列表
 *
 * @access  public
 * @param   int         $user_id         用户ID
 * @param   int         $num             列表显示条数
 * @param   int         $start           显示起始位置
 *
 * @return  array       $arr             团购活动列表
 */
function get_user_group_buy($user_id, $num = 10, $start = 0)
{
    return true;
}

 /**
  * 获得团购详细信息(团购订单信息)
  *
  *
  */
 function get_group_buy_detail($user_id, $group_buy_id)
 {
     return true;
 }

 /**
  * 去除虚拟卡中重复数据
  *
  *
  */
function deleteRepeat($array){
    $_card_sn_record = array();
    foreach ($array as $_k => $_v){
        foreach ($_v['info'] as $__k => $__v){
            if (in_array($__v['card_sn'],$_card_sn_record)){
                unset($array[$_k]['info'][$__k]);
            } else {
                array_push($_card_sn_record,$__v['card_sn']);
            }
        }
    }
    return $array;
}
?>