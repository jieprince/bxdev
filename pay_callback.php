<?php

/**
 * ECSHOP 支付响应页面
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: respond.php 17217 2011-01-19 06:29:08Z liubo $
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_payment.php');
require(ROOT_PATH . 'includes/lib_order.php');
require_once(ROOT_PATH . 'baoxian/source/function_debug.php');
assign_template();


$act = isset($_REQUEST['act'])?$_REQUEST['act']:"";

if(!$act)
{
	show_message("错误！");
	
}

if($act=='springpass')
{
	$is_succ = isset($_POST['SUCC'])?$_POST['SUCC']:'N';

	$BillNo = isset($_POST['BillNo'])?$_POST['BillNo']:0;
	
	$sql = " SELECT order_id FROM ".$GLOBALS['ecs']->table('pay_log')." WHERE log_id='$BillNo'";
	$order_id = $GLOBALS['db']->getOne($sql);
	
	$url = "user.php?act=order_detail&order_id=$order_id";
	
	ss_log("into callback_springpass.php ：".$sql);
	ss_log("is_succ ：".$is_succ);
	
	if($is_succ=='Y')
	{
		show_message('支付成功！', '', $url, 'info',true);
		
	}
	else
	{
		show_message('支付失败！', $_LANG['back_up_page'], $url, 'warning',false);
		
	}
	
}

?>