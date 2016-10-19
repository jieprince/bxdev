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


$theurl ="space.php?do=admin_insurance_company";

///////////////////////////////////////////////////////////////////

$insurer_id = empty($_GET['insurer_id'])?0:intval($_GET['insurer_id']);
if($insurer_id)
{
	$wheresql = "insurer_id='$insurer_id'";
		
	$sql = "SELECT * FROM ".tname('insurance_company')." WHERE $wheresql";

	//echo ($sql);
	$query = $_SGLOBAL['db']->query($sql);
		
	$company_attr =  $value = $_SGLOBAL['db']->fetch_array($query);
	
	/////////////////////////////////////////////////////////////////////////////////////
	$insurer_id = $company_attr['insurer_id'];
	$list_company_insure_type = admin_get_company_insure_type_list($insurer_id);
	/////////////////////////////////////////////////////////////////////////////////////	

	$_TPL['css'] = "admin_product";
	include_once template("space_admin_insurance_company_view");
}
else
{
	$wheresql = "1";
	if($searchkey = stripsearchkey($_GET['searchkey']))
	{
		$wheresql .= " AND insurer_name LIKE '%$searchkey%' ";
		$theurl .= "&searchkey=$_GET[searchkey]";
	}

	$countsql = "SELECT COUNT(*) FROM ".tname('insurance_company')." WHERE $wheresql";
	//echo $countsql;
	//ss_log($countsql);
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
	if($count)
	{
		$ordersql = " ORDER BY insurer_id";//dateline_update
		$sql = "SELECT * FROM ".tname('insurance_company')." WHERE $wheresql $ordersql LIMIT $start,$perpage";

		//ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);

		$list = array();
		while ($value = $_SGLOBAL['db']->fetch_array($query))
		{
			$list[] =  $value;
		}

		$multi = multi($count, $perpage, $page, $theurl);
	}

	$_TPL['css'] = "admin_product";
	include_once template("space_admin_insurance_company");
}
