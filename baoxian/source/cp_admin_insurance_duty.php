<?php
include_once(S_ROOT.'./source/function_baoxian_admin.php');

$duty_id = empty($_GET['duty_id'])?0:intval($_GET['duty_id']);
$op = empty($_GET['op'])?'':$_GET['op'];

$duty = $blog = array();
if($duty_id)
{
	//在两者表中查找日志记录并合并，主要用在编辑和删除的时候，wangcya
	$sql = "SELECT * FROM ".tname('insurance_duty')." WHERE duty_id='$duty_id'";
	$query = $_SGLOBAL['db']->query($sql);

	$duty = $blog = $_SGLOBAL['db']->fetch_array($query);
}



////////////////找到保险公司//////////////////////////////

$wheresql = "1";//不等于自己的其他

$countsql = "SELECT COUNT(*) FROM ".tname('insurance_company')." WHERE $wheresql";
$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
{

	$ordersql = " ORDER BY insurer_id";
	$sql = "SELECT * FROM ".tname('insurance_company')." WHERE $wheresql $ordersql LIMIT 0,$count";

	//ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);

	$list_company = array();
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		$list_company[] =  $value;
	}
}


/////////////////////////////////////////////////////////////////////
//权限检查
if(empty($duty))//没有，则一般为新增日志，则要检查，wangcya
{
	//showmessage("fffffffff");

	/*
	if(!checkperm('allowblog'))
	{
		ckspacelog();
		showmessage('no_authority_to_add_log');
	}
	
	
	//实名认证
	ckrealname('blog');

	//视频认证
	ckvideophoto('blog');

	//新用户见习
	cknewuser();

	//判断是否发布太快
	$waittime = interval_check('post');
	if($waittime > 0)
	{
		showmessage('operating_too_fast','',1,array($waittime));
	}
*/

}
else
{
	
	
	//有日志，则检查日志的UID就是所属者是否和当前访问者一致，wangcya

	if($_SGLOBAL['supe_uid'] != $duty['uid'] && !checkperm('manageblog'))
	{//不是本人，也没有管理权限，则不允许操作，因为只有编辑和删除的时候才用blogid,wangcya
		showmessage('no_authority_operation_of_the_log');
	}
	
	///////////////修改时候得到////////////////////////////////////
	$company_arr_selected = array($duty['insurer_id'] => ' selected');
	
}


//添加编辑操作
if(submitcheck('blogsubmit'))
{//日志的提交工作，wangcya
	
	if(empty($duty['duty_id']))
	{
		$duty = array();
	}
	else
	{
		/*
		if(!checkperm('allowblog'))
		{
			ckspacelog();
			showmessage('no_authority_to_add_log');
		}
		*/
	}
	
	//验证码
	if(checkperm('seccode') && !ckseccode($_POST['seccode']))
	{
		showmessage('incorrect_code');
	}
	
	

	if($newblog = admin_insurance_duty_post($_POST, $duty))
	{//提交帖子，wangcya
	
		
		if(empty($duty) && $newblog['topicid'])
		{
			$url = 'space.php?do=admin_insurance_duty';
		}
		else
		{//指向新发送的帖子，一般运行到这里，wangcya
			$url = 'space.php?do=admin_insurance_duty';
		}
		
		
		showmessage('do_success', $url, 0);
	}
	else
	{
		showmessage('that_should_at_least_write_things');
	}
}
elseif($_GET['op'] == 'delete')
{
	//删除
	if(submitcheck('deletesubmit'))
	{
		$ids = $_POST['ids'];
		
		include_once(S_ROOT.'./source/function_delete.php');
		if(admin_insurance_duty_delete($ids))
		{
			showmessage('do_success', "space.php?do=admin_insurance_duty");
		}
		else
		{
			showmessage('failed_to_delete_operation');
		}
	}

}

$_TPL['css'] = 'admin_product';
include_once template("cp_admin_insurance_duty");
