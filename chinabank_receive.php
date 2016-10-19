<?php

/**
 * 网银在线自动对账接口
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: chinabank_receive.php 17063 2010-11-03 06:35:46Z liubo $
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_payment.php');
require(ROOT_PATH . 'includes/lib_order.php');
include_once(ROOT_PATH . 'baoxian/source/function_debug.php');//add by wangcya, 20141124
ss_log(__FILE__.", GET IN ");
ss_log(__FILE__.", _GET=".var_export($_GET, true));
ss_log(__FILE__.", _POST=".var_export($_POST, true));

$key = '';

$payment = $db->getOne("SELECT pay_config FROM " . $ecs->table('payment') . " WHERE pay_code = 'chinabank' AND enabled = 1");
if (!empty($payment))
{
    $payment = unserialize($payment);
    foreach($payment as $k=>$v)
    {
        if ($v['name'] == 'chinabank_key')
        {
        	ss_log(__FUNCTION__.", GET KEY SUCCESS, key = ".$v['value'] );
            $key = trim($v['value']);
        }
    }
}
else
{
	ss_log(__FILE__.", die");
    die('error');
}

$v_oid     =trim($_POST['v_oid']);//订单号
$v_pmode   =trim($_POST['v_pmode']);
$v_pstatus =trim($_POST['v_pstatus']);
$v_pstring =trim($_POST['v_pstring']);
$v_amount  =trim($_POST['v_amount']);
$v_moneytype  =trim($_POST['v_moneytype']);
$remark1   =trim($_POST['remark1' ]);
$remark2   =trim($_POST['remark2' ]);
$v_md5str  =trim($_POST['v_md5str' ]);//支付平台传过来的教验值

$md5string = strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));
ss_log(__FILE__.", order_sn: ".$v_oid);
if ($v_md5str == $md5string)
{
   if($v_pstatus == '20')
    {
        if ($remark1 == 'voucher')
        {
            $v_oid = get_order_id_by_sn($v_oid, "true");
        }
        else
        {
            $v_oid = get_order_id_by_sn($v_oid);
        }
        
        ss_log(__FILE__.", chinabank into order_paid ,order_sn: ".$v_oid);
        order_paid($v_oid);
    }
    else
    {
    	ss_log(__FUNCTION__.", ERROR HAPPENED, v_pstatus=".$v_pstatus);
    }
    echo 'ok';
}else{
	ss_log(__FILE__.", error happened, v_md5str: ".$v_md5str.", md5string=".$md5string);
    echo 'error';
}
?>