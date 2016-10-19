<?php

include_once(S_ROOT.'./source/function_baoxian_admin.php');

// if(1)//$_SGLOBAL['mobile_type'] != 'myself')
// {
// 	//start add by wangcya, 20121126,for bug[348]////////////////////////////////////////////////
// 	if (empty ( $_SGLOBAL ['supe_uid'] ))
// 	{
// 		if ($_SERVER ['REQUEST_METHOD'] == 'GET')
// 		{
// 			ssetcookie ( '_refer', rawurlencode ( $_SERVER ['REQUEST_URI'] ) );
// 		}
// 		else
// 		{
// 			ssetcookie ( '_refer', rawurlencode ( 'cp.php?ac=' . $ac ) );
// 		}

// 		//add by wangcya, 20130114, for bug[348]
// 		if($_SGLOBAL['mobile_type'] == 'myself')
// 		{
// 			echo_result(100,'to_login');
// 		}
// 		else
// 		{
// 			showmessage ( 'to_login', 'do.php?ac=' . $_SCONFIG ['login_action'] );
// 		}
// 		//	end add by wangcya, 20121126////////////////////////////////////////////////

// 		//权限
// 		if(!checkperm('managehealthnews'))
// 		{
// 			showmessage('no_authority_management_operation');
// 		}
// 	}
// }

$interface_atrr = array(1=>"已对接",	2=>"未对接", 3=>"手工出单对接");
$allow_sell_atrr = array(0=>"允许",	1=>"禁止");
$electronic_policy_atrr = array(0=>"否",1=>"是");
$islock_atrr = array(0=>"否",1=>"是");
/////////////////////////////////////////////

$perpage = 10;

$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1)
	$page=1;

$perpage = mob_perpage($perpage);

$start = ($page-1)*$perpage;


ckstart($start, $perpage);


$theurl ="space.php?do=admin_insurance_product_attribute";

///////////////////////////////////////////////////////////////////

$attribute_id = empty($_GET['attribute_id'])?0:intval($_GET['attribute_id']);
if($attribute_id)
{
	$wheresql = "attribute_id='$attribute_id'";

	$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql";

	//echo ($sql);
	$query = $_SGLOBAL['db']->query($sql);
		
	$product_attribute = $value = $_SGLOBAL['db']->fetch_array($query);

	$value['interface_flag'] = $interface_atrr[$value['interface_flag']];
	$value['allow_sell'] = $allow_sell_atrr[$value['allow_sell']];
	
	$value['str_islock'] = $islock_atrr[$value['islock']];
	
	$islock_checked_atrr = array($value['islock'] => ' checked');
	
	
	$value['is_electronic_policy'] = $electronic_policy_atrr[$value['electronic_policy']];
	
	
	
	//////////////////////////////////////////////////////////////////////
	$list_product = admin_get_product_from_attributeid($attribute_id);

	$_TPL['css'] = "admin_product";
	include_once template("space_admin_insurance_product_attribute_view");
}
else
{

	$wheresql = "1";
	
	$condition = array();
	if($searchkey = stripsearchkey($_GET['searchkey']))
	{
		$condition['searchkey'] = $searchkey; //条件回显
		$wheresql .= " AND attribute_name LIKE '%$searchkey%' ";
		$theurl .= "&searchkey=$_GET[searchkey]";
	}
	
	//add yes123 2014-12-16 添加保险公司查询
	if($insurer_id = stripsearchkey($_GET['insurer_id']))
	{
		$condition['insurer_id'] = $insurer_id;
		$wheresql .= " AND insurer_id=".$insurer_id;
		$theurl .= "&insurer_id=$_GET[insurer_id]";
	}

	$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_attribute')." WHERE $wheresql";
	//echo $countsql;
	//ss_log($countsql);
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
	if($count)
	{

		$ordersql = " ORDER BY attribute_id";//dateline_update
		$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql $ordersql LIMIT $start,$perpage";

		//ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);

		$list = array();
			
	
		

		while ($value = $_SGLOBAL['db']->fetch_array($query))
		{
			$value['interface_flag'] = $interface_atrr[$value['interface_flag']];
			$value['allow_sell'] = $allow_sell_atrr[$value['allow_sell']];
			$value['islock'] = $islock_atrr[$value['islock']];
			
			
			$list[] =  $value;
		}

		$multi = multi($count, $perpage, $page, $theurl);
	}

	//add yes123 2014-12-16 获取所有保险公司列表
	$sql = "SELECT * FROM ".tname('insurance_company');
	$query = $_SGLOBAL['db']->query($sql);
	$list_company = array();
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		$list_company[] =  $value;
	}
	
	//ss_log($multi);

	$_TPL['css'] = "admin_product";
	

	include_once template("space_admin_insurance_product_attribute");

}
