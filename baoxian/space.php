<?php
/*
	
	$Id: space.php 13003 2009-08-05 06:46:06Z  $
*/

include_once('./common.php');

$dos = array(
		'products_list',
		'agent',
		'home',
		'introduce',
		'paymentclaims',
		'livesearch',
		'admin_insurance_user',
		'admin_insurance_type',
		'admin_insurance_company',
		'admin_insurance_duty',
		'admin_career_category',
		'admin_insurance_product_attribute',
		'admin_insurance_product',
		'admin_insurance_additional',
		'admin_insurance_product_duty',
		'admin_insurance_product_duty_price',
		'admin_insurance_order',
		'admin_insurance_policy',
		'admin_insurance_product_influencingfactor',
		'admin_insurance_product_duty_price',
		'admin_customer_individual',
		'admin_customer_group',
		'admin_tool',
		'admin_sales_channel',
		'clientsaleschannel'
		);//add by wangcya 

$admin_action=array(
		'admin_insurance_user',
		'admin_insurance_type',
		'admin_insurance_company',
		'admin_insurance_duty',
		'admin_career_category',
		'admin_insurance_product_attribute',
		'admin_insurance_product',
		'admin_insurance_additional',
		'admin_insurance_product_duty',
		'admin_insurance_product_duty_price',
		'admin_insurance_order',
		'admin_insurance_policy',
		'admin_insurance_product_influencingfactor',
		'admin_insurance_product_duty_price',
		'admin_customer_individual',
		'admin_customer_group',
		'admin_tool',
		'admin_sales_channel'
		);
		//add by rqs



$activetree = array('insurance_type' => 'node',
		'insurance_company' => 'node',
		'insurance_product' => 'node',
		'insurance_additional' => 'node',
		'insurance_duty' => 'node',
		'career_category' => 'node',
		'insurance_order' => 'node',
		'insurance_policy' => 'node',
		'insurance_product_attribute' => 'node',
		'insurance_user' => 'node',
		);

$activetree[$_GET['view']] = 'nodeSel';

//ss_log($_GET['do']);
//echo $_GET['do'];


//ss_log("1,in space, do: ".$_GET['do']);

$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))?$_GET['do']:'index';


$ydhl_action = array("clientsaleschannel","livesearch","admin_tool");
if(in_array($do, $ydhl_action))
{//丢不了的直接过去了。
	//ss_log("pass do: ".$do);
}
elseif(in_array($do, $admin_action))
{
	//ss_log("2,in space, do: ".$do);
	//ss_log("in space.php, admin_id: ".$_SESSION['admin_id'] );
	//ss_log("in space.php, admin_name: ".$_SESSION['admin_name'] );	
	
	//print_r($_SESSION);
	if(empty($_SESSION['admin_name']))
	{
		//ss_log("in space,admin, session is null, so logout!");
		$url = '../admin/privilege.php?act=logout';
		showmessage('do_success', $url, 0);
	}
	else
	{
	
		$_SGLOBAL['mobile_type'] = "pcweb";//add by wangcya , 20141104, 如果是管理端，则不要做wap了。
		
		//ss_log("in space,admin,session: ".$_SESSION['admin_name']." , do: ".$do);
		
		/////////////////////start by wangcya , 20140810 /////////////////////////////////
		
		$_SGLOBAL['supe_uid']= $_SESSION['admin_id'];
		// supe_username
		$_SGLOBAL['supe_username'] = $_SESSION['admin_name'];
		/////////////////////end by wangcya , 20140810 /////////////////////////////////
		//ss_log("in admin, supe_uid: ".$_SGLOBAL['supe_uid']." supe_username: ".$_SGLOBAL['supe_username']);
	}
	
}
else
{
	if(empty($_SESSION['user_id']))
	{
		ss_log("in space,client, session is null, so logout!");
		$url = '../user.php';
		showmessage('do_success', $url, 0);
	}
	
	ss_log("in space, client, user_id: ".$_SESSION['user_id']." do: ".$do);
	/////////////////////start by wangcya , 20140810 /////////////////////////////////
	
	$_SGLOBAL['supe_uid']=$_SESSION['user_id'];
	// supe_username
	$_SGLOBAL['supe_username']=$_SESSION['user_name'];
	/////////////////////end by wangcya , 20140810 /////////////////////////////////
	//ss_log("in client, supe_uid: ".$_SGLOBAL['supe_uid']." supe_username: ".$_SGLOBAL['supe_username']);
	
}

// add by rqs
//ss_log($do);
//echo $do;
//����
include_once(S_ROOT."./source/space_{$do}.php");

?>