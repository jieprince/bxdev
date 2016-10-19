<?php
include_once(S_ROOT.'./source/function_baoxian_admin.php');


$product_id = empty($_GET['product_id'])?0:intval($_GET['product_id']);
$op = empty($_GET['op'])?'':$_GET['op'];

$product = array();
if($product_id)
{
	//在两者表中查找日志记录并合并，主要用在编辑和删除的时候，wangcya
	$sql = "SELECT * FROM ".tname('insurance_product_base')." WHERE product_id='$product_id'";
	$query = $_SGLOBAL['db']->query($sql);

	$product = $_SGLOBAL['db']->fetch_array($query);
	

	if(1)//$product[product_duty_price_type]=='single')
	{
		/////////////////附加信息////////////////////////////////////////////
		$wheresql = "product_id='$product_id'";
		
		$sql = "SELECT * FROM ".tname('insurance_product_additional')." WHERE $wheresql LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$product_additional = $_SGLOBAL['db']->fetch_array($query);
	}
	
	//start add by wangcya , 20141211,锁定产品
	if($product['attribute_id'])
	{
		$attribute_id = $product['attribute_id'];
		//在两者表中查找日志记录并合并，主要用在编辑和删除的时候，wangcya
		$sql = "SELECT islock FROM ".tname('insurance_product_attribute')." WHERE attribute_id='$attribute_id'";
		$query = $_SGLOBAL['db']->query($sql);
	
		$product_attribute = $_SGLOBAL['db']->fetch_array($query);
	
		if($product_attribute['islock'])
		{
			showmessage("该产品已经被锁定！需要修改请联系相关管理员。");
		}
	}
	//end add by wangcya , 20141211,锁定产品
}

////////////////////////////////////////////////////////////////////
////////////////找到属性//////////////////////////////

$attribute_id = empty($_GET['attribute_id'])?0:intval($_GET['attribute_id']);
if($attribute_id)
{
	$wheresql = "attribute_id='$attribute_id'";
}
else
{
	$wheresql = "1";//不等于自己的其他
}

$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_attribute')." WHERE $wheresql";
$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
{

	$ordersql = " ORDER BY attribute_id";
	$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql $ordersql";

	//ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);

	$list_product_attribute = array();
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		$list_product_attribute[] =  $value;
	}
}

/////////////////////////////////////////////////////////////////////
//权限检查
if(empty($product))//没有，则一般为新增日志，则要检查，wangcya
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

	if($_SGLOBAL['supe_uid'] != $product['uid'] && !checkperm('manageblog'))
	{//不是本人，也没有管理权限，则不允许操作，因为只有编辑和删除的时候才用blogid,wangcya
		showmessage('no_authority_operation_of_the_log');
	}
	
	///////////////修改时候得到////////////////////////////////////
	$attribute_arr_selected = array($product['attribute_id'] => ' selected');
	
	$product_type_arr_selected = array($product['product_type'] => ' selected');
	
	$product_duty_price_type_arr_selected = array($product['product_duty_price_type'] => ' selected');
	
	$product_period_uint_checked = array($product_additional['period_uint'] => ' checked');
}


//添加编辑操作
if(submitcheck('blogsubmit'))
{//日志的提交工作，wangcya
	
	if(empty($product['product_id']))
	{
		$product = array();
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
	
	

	if($newblog = admin_insurance_product_post($_POST, $product))
	{//提交帖子，wangcya
	
		
		if(empty($product) && $newblog['topicid'])
		{
			$url = 'space.php?do=admin_insurance_product&product_id='.$newblog[product_id];
		}
		else
		{//指向新发送的帖子，一般运行到这里，wangcya
			$url = 'space.php?do=admin_insurance_product&product_id='.$newblog[product_id];
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
		if(admin_insurance_product_delete($ids))
		{
			showmessage('do_success', "space.php?do=admin_insurance_product");
		}
		else
		{
			showmessage('failed_to_delete_operation');
		}
	}

}

$_TPL['css'] = 'admin_product';
include_once template("cp_admin_insurance_product");
