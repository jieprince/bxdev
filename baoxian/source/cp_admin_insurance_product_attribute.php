<?php
include_once(S_ROOT.'./source/function_baoxian_admin.php');

$attribute_id = empty($_GET['attribute_id'])?0:intval($_GET['attribute_id']);
$op = empty($_GET['op'])?'':$_GET['op'];

$product_attribute = $blog = array();
if($attribute_id)
{
	//在两者表中查找日志记录并合并，主要用在编辑和删除的时候，wangcya
	$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE attribute_id='$attribute_id'";
	$query = $_SGLOBAL['db']->query($sql);

	$product_attribute = $blog = $_SGLOBAL['db']->fetch_array($query);
	
	 //add yes123 2014-12-17 解析反斜杠
	$blog['product_characteristic'] =  stripslashes($blog['product_characteristic']);
	$blog['description'] =  stripslashes($blog['description']);
	$blog['limit_note'] =  stripslashes($blog['limit_note']);
	$blog['cover_note'] =  stripslashes($blog['cover_note']);
	$blog['claims_guide'] =  stripslashes($blog['claims_guide']);
	$blog['insurance_clauses'] =  stripslashes($blog['insurance_clauses']);
	$blog['insurance_declare'] =  stripslashes($blog['insurance_declare']);
	$blog['insurance_faq'] =  stripslashes($blog['insurance_faq']);
	
	//
	if(submitcheck('product_lock_submit'))
	{
		$islock = intval($_POST['islock']);
		
		$setarr = array('islock'=>$islock);
		//$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		
		updatetable("insurance_product_attribute",$setarr , array('attribute_id'=>$attribute_id));
		
		
		$url = 'space.php?do=admin_insurance_product_attribute&attribute_id='.$attribute_id;
		showmessage('do_success', $url, 0);
	
	}
	
	//start add by wangcya , 20141211,锁定产品
	if($product_attribute['islock'])
	{
		showmessage("该产品已经被锁定！需要修改请联系相关管理员。");
	}
	//end add by wangcya , 20141211,锁定产品
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
		$id = strval($value['insurer_id']);
		$all_list_company_insure_type["$id"] = admin_get_company_insure_type_list($value['insurer_id']);
	}

}

///////////////////找到保险类别///////////////////////////////////////////

$wheresql = "1";//不等于自己的其他

$countsql = "SELECT COUNT(*) FROM ".tname('insurance_type')." WHERE $wheresql";
$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
{


	$ordersql = " ORDER BY id";
	$sql = "SELECT * FROM ".tname('insurance_type')." WHERE $wheresql $ordersql LIMIT 0,$count";

	//ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);

	$list_type = array();
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		$list_type[] =  $value;
	}
}

/////////////////////////////////////////////////////////////////////
//权限检查
if(empty($product_attribute))//没有，则一般为新增日志，则要检查，wangcya
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

	if($_SGLOBAL['supe_uid'] != $product_attribute['uid'] && !checkperm('manageblog'))
	{//不是本人，也没有管理权限，则不允许操作，因为只有编辑和删除的时候才用blogid,wangcya
		showmessage('no_authority_operation_of_the_log');
	}
	
	///////////////修改时候得到////////////////////////////////////
	$company_arr_selected = array($product_attribute['insurer_id'] => ' selected');
	
	///////////////修改时候得到原先的父亲////////////////////////////////////
	$ins_type_arr_selected = array($product_attribute['ins_type_id'] => ' selected');
	
	

	$interface_flag_select = array($product_attribute['interface_flag'] => ' selected');

	$allow_sell_select = array($product_attribute['allow_sell'] => ' selected');
	
	$type_select = array($product_attribute['attribute_type'] => ' selected');
	
	$business_type_selected = array($product_attribute['business_type'] => ' selected');
	
	$electronic_policy_checked = $product_attribute['electronic_policy']==1?"checked":"";
	//array($product_attribute['$electronic_policy'] => ' checked');
	
	
}



//添加编辑操作
if(submitcheck('blogsubmit'))
{//日志的提交工作，wangcya
	
	if(empty($product_attribute['attribute_id']))
	{
		$product_attribute = array();
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
	
	

	if($newblog = admin_insurance_product_attribute_post($_POST, $product_attribute))
	{//提交帖子，wangcya
	
		
		if(empty($product_attribute) && $newblog['topicid'])
		{
			$url = 'space.php?do=admin_insurance_product_attribute&attribute_id='.$newblog[$attribute_id];
		}
		else
		{//指向新发送的帖子，一般运行到这里，wangcya
			$url = 'space.php?do=admin_insurance_product_attribute&attribute_id='.$newblog[attribute_id];
		}
		
		
		showmessage('do_success', $url, 0);
	}
	else
	{
		showmessage('that_should_at_least_write_things');
	}
}
elseif(submitcheck('product_order_submit'))
{
	//ss_log("productduty_order_submit");

	$view_order_ids = $_POST['view_order_ids'];
	foreach ($view_order_ids as $key=>$value)
	{
		$product_id = intval($key);
		$view_order = intval($value);

		//ss_log("product_duty_id: ".$product_duty_id." view_order: ".$view_order);
		$setarr = array('view_order'=>$view_order);
		//$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_product_base',
		$setarr,
		array('product_id'=>$product_id)
		);


	}


	//////////////////////////////////////////////////////////////////

	$url = 'space.php?do=admin_insurance_product_attribute&attribute_id='.$attribute_id;

	showmessage('do_success', $url, 0);

}
elseif($_GET['op'] == 'delete')
{
	//删除
	if(submitcheck('deletesubmit'))
	{
		$ids = $_POST['ids'];
		
		include_once(S_ROOT.'./source/function_delete.php');
		if(admin_insurance_product_attribute_delete($ids))
		{
			showmessage('do_success', "space.php?do=admin_insurance_product_attribute");
		}
		else
		{
			showmessage('failed_to_delete_operation');
		}
	}

}

//start add by wangcya, 20141213,险种代码
if(empty($product_attribute['attribute_code']))
{//没有才产生新的，
	$sql = "SELECT (MAX(attribute_id)+1) AS max_id FROM ".tname('insurance_product_attribute');
	$query = $_SGLOBAL['db']->query($sql);
	$product_attribute_max = $_SGLOBAL['db']->fetch_array($query);
	$max_id     		= $product_attribute_max['max_id'] ;

	ss_log("product_attribute , max_id: ".$max_id);
		
	$attribute_code   	= admin_generate_attribute_code_sn($max_id);
	ss_log("product_attribute , gen new attribute_code: ".$attribute_code);
}
else
{
	$attribute_code = $product_attribute['attribute_code'];
}
//end add by wangcya, 20141213,险种代码

$_TPL['css'] = 'admin_product';
include_once template("cp_admin_insurance_product_attribute");
