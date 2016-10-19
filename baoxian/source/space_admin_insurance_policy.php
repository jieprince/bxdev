<?php
include_once(S_ROOT.'./source/function_baoxian_admin.php');
/*
if(1)//$_SGLOBAL['mobile_type'] != 'myself')
{
	ss_log("supe_uid: ".$_SGLOBAL ['supe_uid']);
	//start add by wangcya, 20121126,for bug[348]////////////////////////////////////////////////
	if (empty ( $_SGLOBAL ['supe_uid'] ))
	{
		if ($_SERVER ['REQUEST_METHOD'] == 'GET')
		{
			ssetcookie ( '_refer', rawurlencode ( $_SERVER ['REQUEST_URI'] ) );
		}
		else
		{
			ssetcookie ( '_refer', rawurlencode ( 'cp.php?ac=' . $ac ) );
		}

		//add by wangcya, 20130114, for bug[348]
		if($_SGLOBAL['mobile_type'] == 'myself')
		{
			echo_result(100,'to_login');
		}
		else
		{
			showmessage ( 'to_login', 'do.php?ac=' . $_SCONFIG ['login_action'] );
		}
		//	end add by wangcya, 20121126////////////////////////////////////////////////

		//权限
		if(!checkperm('managehealthnews'))
		{
			showmessage('no_authority_management_operation');
		}
	}
}
*/
/////////////////////////////////////////////

$perpage = 10;

$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1)
	$page=1;

$perpage = mob_perpage($perpage);

$start = ($page-1)*$perpage;


ckstart($start, $perpage);


$theurl ="space.php?do=admin_insurance_policy";

///////////////////////////////////////////////////////////////////
$status_msg = array('0'=> '已保存',
		'1'=> '投保成功',
		'2'=> '已注销'
);

$policy_id = empty($_GET['policy_id'])?0:intval($_GET['policy_id']);


if($policy_id)
{
	$wheresql = "policy_id='$policy_id'";
		
	$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql";
	$query = $_SGLOBAL['db']->query($sql);
	$value = $_SGLOBAL['db']->fetch_array($query);

	$relationship_attr = array(	"1"=>"本人",
			"2"=>"配偶",
			"3"=>"父子",
			"4"=>"父女",
			"5"=>"受益人",
			"6"=>"被保人",
			"7"=>"投保人",
			"A"=>"母子",
			"B"=>"母女 ",
			"C"=>"兄弟 ",
			"D"=>" 姐弟 ",
			"G"=>"祖孙",
			"H"=>"雇佣",
			"I"=>"子女",
			"9"=>"其他",
			"8"=>"转换不详"
	);

	$value[relationship_with_insured] = $relationship_attr[$value[relationship_with_insured]];

	$businessType_attr = array( "1"=>"个人",
			"2"=>"团体"
	);

	$value[business_type] = $businessType_attr[$value[business_type]];

	$value[dateline] = date('Y-m-d H:i',$value[dateline]);


	//$value[statusmsg] = $status_msg[$value[policy_status]];

	$_TPL['css'] = "admin_product";
	include_once template("space_admin_insurance_policy_view");
}
else
{
	$wheresql = "1";
	if($searchkey = stripsearchkey($_GET['searchkey']))
	{
		$wheresql .= " AND product_name LIKE '%$searchkey%' ";
		$theurl .= "&searchkey=$_GET[searchkey]";
	}

	$countsql = "SELECT COUNT(*) FROM ".tname('insurance_policy')." WHERE $wheresql";
	//echo $countsql;
	//ss_log($countsql);
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
	if($count)
	{

		$ordersql ="ORDER BY dateline DESC";
		$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql $ordersql LIMIT $start,$perpage";

		//ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);

		$list = array();
		while ($value = $_SGLOBAL['db']->fetch_array($query))
		{
			$value['insurepolicyurl'] = "cp.php?ac=product_buy&policy_id=$value[policy_id]&op=insure";
			$value['getpolicyfileurl'] = "cp.php?ac=product_buy&policy_id=$value[policy_id]&op=getpolicyfile";
			$value['querypolicyfileurl'] = "cp.php?ac=product_buy&policy_id=$value[policy_id]&op=querypolicyfile";
			$value['withdrawpolicyurl']   = "cp.php?ac=product_buy&policy_id=$value[policy_id]&op=withdraw";


			if($value['policy_status'] == 'saved')//已保存
			{

			}
			elseif($value['policy_status'] == 'insured')//已投保
			{
				//$value[policyfileurl] = "/baoxian/xml/$value[orderNum].pdf";

					
			}
			elseif($value['policy_status'] == 'canceled')//已注销
			{
					
			}
	
			$value['dateline'] = date('m-d H:i',$value['dateline']);

			$list[] =  $value;
		}

		$multi = multi($count, $perpage, $page, $theurl);
	}

	$_TPL['css'] = "admin_product";
	include_once template("space_admin_insurance_policy");
}
