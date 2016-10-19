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


$theurl ="space.php?do=admin_insurance_user";

///////////////////////////////////////////////////////////////////
$op = empty($_GET['op'])?'':$_GET['op'];
$uid = empty($_GET['uid'])?0:intval($_GET['uid']);

ss_log("op: ".$op." uid: ".$uid);

if($uid)
{
	$wheresql = "user_id='$uid'";
	$sql = "SELECT * FROM bx_users WHERE $wheresql";
	$query = $_SGLOBAL['db']->query($sql);
	$userinfo = $_SGLOBAL['db']->fetch_array($query);

	
	//$_TPL['css'] = "admin_product";
	//include_once template("space_admin_insurance_user_view");
	
}

/////////////////////////////////////////////////////
if($op =='gentwodimensionalcode')
{
	
	$user_id 		= $userinfo['user_id'];
	$user_name 		= $userinfo['user_name'];
	$user_email 	= $userinfo['email'];
	$user_mobile 	= empty($userinfo['mobile_phone'])?"13611265328":$userinfo['mobile_phone'];
	
	$ret = create_user_in_diubuliao(		$user_id,
										    	$user_name,
										    	$user_email,
										    	$user_mobile
										    	);
	
	if($ret['status']!=0)
	{
		$str_err = $ret['msg'];
		showmessage($str_err);
		return;
	}
	
	$peerid = $ret["peerid"];
	$pic = $ret["pic"];
	$status = $ret["status"];
	$str_err = $ret["msg"];
	

	$_TPL['css'] = "admin_product";
	include_once template("space_admin_insurance_peer_user_view");

	return;
}
else
{
	$wheresql = "check_status='".CHECKED_CHECK_STATUS."'";
	if($searchkey = stripsearchkey($_GET['searchkey']))
	{
		$wheresql .= " AND name LIKE '%$searchkey%' ";
		$theurl .= "&searchkey=$_GET[searchkey]";
	}

	$countsql = "SELECT COUNT(*) FROM bx_users WHERE $wheresql";
	//echo $countsql;
	//ss_log($countsql);
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
	if($count)
	{
		$ordersql = " ORDER BY user_id DESC";//dateline_update
		$sql = "SELECT * FROM bx_users WHERE $wheresql $ordersql LIMIT $start,$perpage";

		//ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);

		$list_user = array();
		while ($value = $_SGLOBAL['db']->fetch_array($query))
		{
			$list_user[] =  $value;
		}

		$multi = multi($count, $perpage, $page, $theurl);
	}

	$_TPL['css'] = "admin_product";
	include_once template("space_admin_insurance_user");
}


