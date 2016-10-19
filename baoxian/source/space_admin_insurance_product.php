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
/////////////////////////////////////////////

$perpage = 10;

$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1)
	$page=1;

$perpage = mob_perpage($perpage);

$start = ($page-1)*$perpage;


ckstart($start, $perpage);


$theurl ="space.php?do=admin_insurance_product";

///////////////////////////////////////////////////////////////////

$product_id = empty($_GET['product_id'])?0:intval($_GET['product_id']);
if($product_id)
{
	$wheresql = "product_id='$product_id'";

	$sql = "SELECT * FROM ".tname('insurance_product_base')." WHERE $wheresql";

	//echo ($sql);
	$query = $_SGLOBAL['db']->query($sql);
		
	$product = $_SGLOBAL['db']->fetch_array($query);
	$product_id = $product[product_id];


	////////////////找到保费影响因素//////////////////////////////
	/*
	$wheresql = "product_id='$product_id'";//不等于自己的其他
	$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_influencingfactor')." WHERE $wheresql";
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
	{

		$ordersql = " ORDER BY product_influencingfactor_type,dateline_create";
		$sql = "SELECT * FROM ".tname('insurance_product_influencingfactor')." WHERE $wheresql $ordersql";
		ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);

		$list_product_influencingfactor = array();
		while ($value = $_SGLOBAL['db']->fetch_array($query))
		{
			$list_product_influencingfactor[] =  $value;
		}
	}
	*/
	
	$list_product_influencingfactor = get_product_influencingfactor_list($product_id,"");

	///////////////////得到保险的属性////////////////////////////////////////////
	$attribute_id = $product[attribute_id];

	$wheresql = "attribute_id='$attribute_id'";

	$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql";
	$query = $_SGLOBAL['db']->query($sql);
	$product_attribute = $_SGLOBAL['db']->fetch_array($query);

	/////////////////附加信息////////////////////////////////////////////
	$wheresql = "product_id='$product_id'";

	$sql = "SELECT * FROM ".tname('insurance_product_additional')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$product_additional = $_SGLOBAL['db']->fetch_array($query);

	
	global $product_additional_attr_period_uint;
	
	$str_period_uint =  $product_additional_attr_period_uint[$product_additional['period_uint']];

	$product_duty_list = get_product_duty_list( $product_id,	$product['product_duty_price_type']);
			

	/////////////////////////////////////////////////////////////
	$_TPL['css'] = "admin_product";
	include_once template("space_admin_insurance_product_view");
		//echo "dsfdsfsd";
}
else
{
	$wheresql = "1";
	
	$condition = array();
	if($searchkey = stripsearchkey($_GET['searchkey']))
	{
		$condition['searchkey'] = $searchkey;
		$wheresql .= " AND ipb.product_name LIKE '%$searchkey%' ";
		$theurl .= "&searchkey=$_GET[searchkey]";
	}
	
	if($product_code = stripsearchkey($_GET['product_code']))
	{
		$condition['product_code'] = $product_code;
		$wheresql .= " AND ipb.product_code LIKE '%$product_code%' ";
		$theurl .= "&product_code=$_GET[product_code]";
	}
	
	
	
	//add yes123 2014-12-16 添加保险公司查询
	if($insurer_id = stripsearchkey($_GET['insurer_id']))
	{
		$condition['insurer_id'] = $insurer_id;
		$wheresql .= " AND ipa.insurer_id=".$insurer_id;
		$theurl .= "&insurer_id=$_GET[insurer_id]";
	}

	//$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_base')." WHERE $wheresql";
	$countsql = "SELECT COUNT(ipb.product_id) FROM ".tname('insurance_product_base')." AS ipb INNER JOIN " .
				tname('insurance_product_attribute')." AS ipa ON ipb.attribute_id = ipa.attribute_id " .
				" WHERE ". $wheresql;
	
	//echo $countsql;
	//ss_log($countsql);
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
	if($count)
	{
		$ordersql = "ORDER BY dateline_update desc";
		//modify yes123 2014-12-16 按照保险公司查询
		//$sql = "SELECT * FROM ".tname('insurance_product_base')." WHERE $wheresql $ordersql LIMIT $start,$perpage";
		$sql = "SELECT ipb.*,ipa.attribute_name,ipa.insurer_name,ipa.attribute_type FROM ".tname('insurance_product_base')." AS ipb INNER JOIN " .
				tname('insurance_product_attribute')." AS ipa ON ipb.attribute_id = ipa.attribute_id " .
				" WHERE $wheresql $ordersql LIMIT $start,$perpage";
	
		//ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
	
		$list = array();
		while ($value = $_SGLOBAL['db']->fetch_array($query))
		{
			//echo "sdfsdfsdf";
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
	
	$_TPL['css'] = "admin_product";
	include_once template("space_admin_insurance_product");
}
