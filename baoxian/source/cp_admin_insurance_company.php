<?php
include_once(S_ROOT.'./source/function_baoxian_admin.php');


$insurer_id = empty($_GET['insurer_id'])?0:intval($_GET['insurer_id']);
$op = empty($_GET['op'])?'':$_GET['op'];

$blog = array();
if($insurer_id)
{
	//在两者表中查找日志记录并合并，主要用在编辑和删除的时候，wangcya
	$sql = "SELECT * FROM ".tname('insurance_company')." WHERE insurer_id='$insurer_id'";
	$query = $_SGLOBAL['db']->query($sql);

	$insurance_company = $blog = $_SGLOBAL['db']->fetch_array($query);
}

///////////////////查找父亲列表/////////////////////////////
$wheresql = "insurer_id!='$insurer_id'";//不等于自己的其他

$countsql = "SELECT COUNT(*) FROM ".tname('insurance_company')." WHERE $wheresql";
$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
{

	$ordersql = " ORDER BY dateline_update";
	$sql = "SELECT * FROM ".tname('insurance_company')." WHERE $wheresql $ordersql LIMIT 0,$count";

	//ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);

	$list_parent = array();
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		$list_parent[] =  $value;
	}
}



//权限检查
if(empty($blog))//没有，则一般为新增日志，则要检查，wangcya
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

	if($_SGLOBAL['supe_uid'] != $blog['uid'] && !checkperm('manageblog'))
	{//不是本人，也没有管理权限，则不允许操作，因为只有编辑和删除的时候才用blogid,wangcya
		showmessage('no_authority_operation_of_the_log');
	}
	
	
	$parent_arr = array($blog['parent_id'] => ' selected');
}

//添加编辑操作
if(submitcheck('blogsubmit'))
{//日志的提交工作，wangcya
	
	if(empty($blog['insurer_id']))
	{
		$blog = array();
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
	
	/*
	//验证码
	if(checkperm('seccode') && !ckseccode($_POST['seccode']))
	{
		showmessage('incorrect_code');
	}
	*/
	

	if($newblog = admin_insurance_company_post($_POST, $blog))
	{//提交帖子，wangcya
	
		
		if(empty($blog) && $newblog['topicid'])
		{
			$url = 'space.php?do=admin_insurance_company&insurer_id='.$newblog[insurer_id];
		}
		else
		{//指向新发送的帖子，一般运行到这里，wangcya
			$url = 'space.php?do=admin_insurance_company&insurer_id='.$newblog[insurer_id];
		}
		
		
		showmessage('do_success', $url, 0);
	}
	else
	{
		showmessage('that_should_at_least_write_things');
	}
}

if($_GET['op'] == 'delete')
{
	//删除
	if(submitcheck('deletesubmit'))
	{
		$ids = $_POST['ids'];
		
		include_once(S_ROOT.'./source/function_delete.php');
		if(admin_insurance_company_delete($ids))
		{
			showmessage('do_success', "space.php?do=admin_insurance_company");
		}
		else
		{
			showmessage('failed_to_delete_operation');
		}
	}

}

$_TPL['css'] = 'admin_product';

include_once template("cp_admin_insurance_company");
