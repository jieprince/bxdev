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


$theurl ="space.php?do=admin_insurance_product_duty_price";

///////////////////////////////////////////////////////////////////


$product_duty_id = empty($_GET['product_duty_id'])?0:intval($_GET['product_duty_id']);

if($product_duty_id)
{
	$wheresql = "product_duty_id='$product_duty_id'";
	$sql = "SELECT * FROM ".tname('insurance_product_duty')." WHERE $wheresql";
	$query = $_SGLOBAL['db']->query($sql);
	$product_duty = $_SGLOBAL['db']->fetch_array($query);
	$product_id = $product_duty['product_id'];
	
	if($product_id)
	{
		$sql = "SELECT * FROM ".tname('insurance_product_base')." pb LEFT JOIN
		".tname('insurance_product_attribute')." pa ON pb.attribute_id=pa.attribute_id
		WHERE pb.product_id='$product_id'";
		$query = $_SGLOBAL['db']->query($sql);
		
		$product = $_SGLOBAL['db']->fetch_array($query);

	}
}

/////////////////////找到保费影响因素///////////////////////////////////////////
if($product_id)
{
	$wheresql = "product_id='$product_id'";//不等于自己的其他
	$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_influencingfactor')." WHERE $wheresql";
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
	{

		$ordersql = " ORDER BY dateline_create";
		$sql = "SELECT * FROM ".tname('insurance_product_influencingfactor')." WHERE $wheresql $ordersql";
		$query = $_SGLOBAL['db']->query($sql);

		$list_product_influencingfactor = array();
		while ($value = $_SGLOBAL['db']->fetch_array($query))
		{
			$list_product_influencingfactor[$value['product_influencingfactor_id']] =  $value;
		}
	}

}
/////////////////////////////////////////////////////////////
if($product_duty_id)
{
	$wheresql = "product_duty_id='$product_duty_id'";
	$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_duty_price')." WHERE $wheresql";
	//echo $countsql;
	//ss_log($countsql);
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
	if($count)
	{

		$sql = "SELECT * FROM ".tname('insurance_product_duty_price')."
		WHERE $wheresql order by view_order LIMIT 0,500";
		$query = $_SGLOBAL['db']->query($sql);

		$list_product_duty_price = array();
		while ($value = $_SGLOBAL['db']->fetch_array($query))
		{
			$list_product_duty_price[] =  $value;
		}

		//$multi = multi($count, $perpage, $page, $theurl);
	}

}

$_TPL['css'] = "admin_product";
include_once template("space_admin_insurance_product_duty_price_view");

