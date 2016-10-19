<?php
include_once(S_ROOT.'./source/function_baoxian_admin.php');

$insurance_company_insure_type_id = empty($_GET['insurance_company_insure_type_id'])?0:intval($_GET['insurance_company_insure_type_id']);
$insurance_company_insure_type_attr = array();
if($insurance_company_insure_type_id)
{
	//在两者表中查找日志记录并合并，主要用在编辑和删除的时候，wangcya
	$sql = "SELECT * FROM ".tname('insurance_company_insure_type')." WHERE insurance_company_insure_type_id='$insurance_company_insure_type_id' LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$insurance_company_insure_type_attr = $_SGLOBAL['db']->fetch_array($query);
	$insurer_id = $insurance_company_insure_type_attr['insurer_id'];
}

//////////////////////////////////////////////////////////////////
if(empty($insurer_id))
{
	$insurer_id = empty($_GET['insurer_id'])?0:intval($_GET['insurer_id']);
}

$insurance_company_attribute = array();
if($insurer_id)
{
	//在两者表中查找日志记录并合并，主要用在编辑和删除的时候，wangcya
	$sql = "SELECT * FROM ".tname('insurance_company')." WHERE insurer_id='$insurer_id' LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$insurance_company_attribute = $_SGLOBAL['db']->fetch_array($query);
	$insurer_id = $insurance_company_attribute['insurer_id'];
}

///////////////////////////////////////////////////////////////////////////
$theUrl = "cp.php?ac=admin_insurance_company_insure_type&insurer_id=$insurer_id";

//showmessage("fffffffff");
if(empty($insurance_company_insure_type_attr))//没有，则一般为新增日志，则要检查，wangcya
{
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
	}*/
}
else
{
	//有日志，则检查日志的UID就是所属者是否和当前访问者一致，wangcya
	if($_SGLOBAL['supe_uid'] != $insurance_company_insure_type_attr['uid'] && !checkperm('manageblog'))
	{//不是本人，也没有管理权限，则不允许操作，因为只有编辑和删除的时候才用blogid,wangcya
		showmessage('no_authority_operation_of_the_log');
	}


	//$type_arr_selected = array($insurance_company_insure_type_attr['product_influencingfactor_type'] => 'selected');

}

//添加编辑操作
if(submitcheck('insurance_company_insure_type_submit'))
{//日志的提交工作，wangcya
	
	if(empty($insurance_company_insure_type_attr['insurance_company_insure_type_id']))//new
	{
		$_POST['insurer_id'] = $insurance_company_attribute['insurer_id'];
	
		$insurance_company_insure_type_attr = array();
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
	
	

	if($newblog = admin_insurance_company_insure_type_post($_POST, $insurance_company_insure_type_attr))
	{//提交产品责任成功后，则提交产品责任价格
		
		$insurance_company_insure_type_attr_id = $newblog['insurance_company_insure_type_id'];
		////////////////////////////////////////////////////////////////////////
		if(empty($newblog))
		{
			$url = "space.php?do=admin_insurance_company&insurer_id=$insurer_id";
		}
		else
		{//指向新发送的帖子，一般运行到这里，wangcya
			$url = "space.php?do=admin_insurance_company&insurer_id=$insurer_id";
		}
			
		showmessage('do_success', $url, 0);
	}
	else
	{
		showmessage('that_should_at_least_write_things');
	}
}
elseif(submitcheck('company_insure_order_submit'))
{
	ss_log("company_insure_order_submit");

	//////////////////////////////////////////////////////////////////
	$view_order_ids = $_POST['view_order_ids'];
	foreach ($view_order_ids as $key=>$value)
	{
		$insurance_company_insure_type_id = intval($key);
		$view_order = intval($value);

		ss_log("insurance_company_insure_type_attr_id: ".$insurance_company_insure_type_attr_id." view_order: ".$view_order);

		$setarr = array('view_order'=>$view_order);
		//$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_company_insure_type',
		$setarr,
		array('insurance_company_insure_type_id'=>$insurance_company_insure_type_id)
		);


	}


	//////////////////////////////////////////////////////////////////
	$attribute_type_ids = $_POST['attribute_type_ids'];
	foreach ($attribute_type_ids as $key=>$value)
	{
		$insurance_company_insure_type_id = intval($key);
		$attribute_type = trim($value);

		//ss_log("product_duty_id: ".$product_duty_id." view_order: ".$view_order);
		$setarr = array('attribute_type'=>$attribute_type);
		//$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		
		updatetable('insurance_company_insure_type',
		$setarr,
		array('insurance_company_insure_type_id'=>$insurance_company_insure_type_id)
		);


	}


	//////////////////////////////////////////////////////////////////
	$note_ids = $_POST['note_ids'];
	foreach ($note_ids as $key=>$value)
	{
		$insurance_company_insure_type_id = intval($key);
		$note = trim($value);

		//ss_log("product_duty_id: ".$product_duty_id." view_order: ".$view_order);
		$setarr = array('note'=>$note);
		$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_company_insure_type',
		$setarr,
		array('insurance_company_insure_type_id'=>$insurance_company_insure_type_id)
		);


	}

	//////////////////////////////////////////////////////////////////

	$url = "space.php?do=admin_insurance_company&insurer_id=$insurer_id";

	showmessage('do_success', $url, 0);

}

if($_GET['op'] == 'delete')
{
	//ss_log("ddddddddddddddddfdfdfdf");
	//删除
	if(1)//submitcheck('deletesubmit'))
	{
		//$ids = $_POST['ids'];
		$insurance_company_insure_type_id = intval($insurance_company_insure_type_id);
			
		//ss_log("product_duty_id: ".$product_duty_id);

		$ids = array($insurance_company_insure_type_id);
		include_once(S_ROOT.'./source/function_delete.php');
		if(admin_insurance_company_insure_type_delete($ids))
		{
			$url = "space.php?do=admin_insurance_company&insurer_id=$insurer_id";
			showmessage('do_success', $url);
		}
		else
		{
			$url = "space.php?do=admin_insurance_company&insurer_id=$insurer_id";
			showmessage('do_success', $url);
		}
	}
}

////////////////////////////////////////////////////////////////////////////////
$list_count = array(0,1,2,3,4,5,6,7,8);

if(empty($insurance_company_insure_type_attr['insurance_company_insure_type_id']))//new
{
	$_TPL['css'] = 'admin_product';
	include_once template("cp_admin_insurance_company_insure_type");
}
else
{
	$_TPL['css'] = 'admin_product';
	include_once template("cp_admin_insurance_company_insure_type_modify");
}

