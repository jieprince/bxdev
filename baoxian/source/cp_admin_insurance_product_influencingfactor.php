<?php
include_once(S_ROOT.'./source/function_baoxian_admin.php');


$list_type = array(
		'career'=>'career',//：职业类别
		'period'=>'period',//：投保期限
		'age'=>'age'//投保年龄
);


/////////////////////////////////////////////////////////////
$product_influencingfactor_id = empty($_GET['product_influencingfactor_id'])?0:intval($_GET['product_influencingfactor_id']);//为了修改用的
$op = empty($_GET['op'])?'':$_GET['op'];

$product_influencingfactor = array();
if($product_influencingfactor_id)
{
	//在两者表中查找日志记录并合并，主要用在编辑和删除的时候，wangcya
	$sql = "SELECT * FROM ".tname('insurance_product_influencingfactor')." WHERE product_influencingfactor_id='$product_influencingfactor_id'";
	$query = $_SGLOBAL['db']->query($sql);
	$product_influencingfactor = $_SGLOBAL['db']->fetch_array($query);
	
	$product_id = $product_influencingfactor['product_id'];
}

//////////////////////////////////////////////////////////////////
if(empty($product_id))
{
	$product_id = empty($_GET['product_id'])?0:intval($_GET['product_id']);
}
///////////////////////////////////////////////////////////////////
$product = array();
if($product_id)
{
	//在两者表中查找日志记录并合并，主要用在编辑和删除的时候，wangcya
	$sql = "SELECT * FROM ".tname('insurance_product_base')." pb LEFT JOIN 
	".tname('insurance_product_attribute')." pa ON pb.attribute_id=pa.attribute_id  
	WHERE pb.product_id='$product_id'";
	$query = $_SGLOBAL['db']->query($sql);

	$product = $_SGLOBAL['db']->fetch_array($query);
	$product_id = $product['product_id'];
	
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

//////////////////////////////////////////////////////////////////////////////////////////
$theUrl = "cp.php?ac=admin_insurance_product_influencingfactor&product_id=$product[product_id]";


//showmessage("fffffffff");
if(empty($product_influencingfactor))//没有，则一般为新增日志，则要检查，wangcya
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

	
	$type_arr_selected = array($product_influencingfactor['product_influencingfactor_type'] => 'selected');
	
}

//添加、 编辑操作
if(submitcheck('product_influencingfactor_submit'))
{//日志的提交工作，wangcya

	//说明是新添加的
	if(empty($product_influencingfactor['product_influencingfactor_id']))//new
	{
		$_POST['product_id'] = $product['product_id'];

		$product_influencingfactor = array();
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
		
		

	if($newblog = admin_insurance_product_influencingfactor_post($_POST, $product_influencingfactor))
	{//提交产品责任成功后，则提交产品责任价格
			
		$product_influencingfactor_id = $newblog['product_influencingfactor_id'];
			////////////////////////////////////////////////////////////////////////
		if(empty($newblog))
		{
			$url = 'space.php?do=admin_insurance_product&product_id='.$product_id;
		}
		else
		{//指向新发送的帖子，一般运行到这里，wangcya
			$url = 'space.php?do=admin_insurance_product&product_id='.$product_id;
		}
			
			
		showmessage('do_success', $url, 0);
	}
	else
	{
		showmessage('that_should_at_least_write_things');
	}
}
elseif(submitcheck('productduty_order_submit'))
{
	//ss_log("productduty_order_submit");

	//////////////////////////////////////////////////////////////////
	$view_order_ids = $_POST['view_order_ids'];
	foreach ($view_order_ids as $key=>$value)
	{
		$product_influencingfactor_id = intval($key);
		$view_order = intval($value);

		//ss_log("product_duty_id: ".$product_duty_id." view_order: ".$view_order);
		$setarr = array('view_order'=>$view_order);
		//$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_product_influencingfactor',
		$setarr,
		array('product_influencingfactor_id'=>$product_influencingfactor_id)
		);


	}
	
	
	//////////////////////////////////////////////////////////////////
	$factor_name_ids = $_POST['factor_name_ids'];
	foreach ($factor_name_ids as $key=>$value)
	{
		$product_influencingfactor_id = intval($key);
		$factor_name = $value;
	
		//ss_log("product_duty_id: ".$product_duty_id." view_order: ".$view_order);
		$setarr = array('factor_name'=>$factor_name);
		$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_product_influencingfactor',
		$setarr,
		array('product_influencingfactor_id'=>$product_influencingfactor_id)
		);
	
	
	}
	
	
	//////////////////////////////////////////////////////////////////
	$factor_code_ids = $_POST['factor_code_ids'];
	foreach ($factor_code_ids as $key=>$value)
	{
		$product_influencingfactor_id = intval($key);
		$factor_code = trim($value);
	
		//ss_log("product_duty_id: ".$product_duty_id." view_order: ".$view_order);
		$setarr = array('factor_code'=>$factor_code);
		$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_product_influencingfactor',
		$setarr,
		array('product_influencingfactor_id'=>$product_influencingfactor_id)
		);
	
	
	}
	
	//////////////////////////////////////////////////////////////////
	$factor_price_ids = $_POST['factor_price_ids'];
	foreach ($factor_price_ids as $key=>$value)
	{
		$product_influencingfactor_id = intval($key);
		$factor_price = trim($value);
	
		//ss_log("product_duty_id: ".$product_duty_id." view_order: ".$view_order);
		$setarr = array('factor_price'=>$factor_price);
		$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_product_influencingfactor',
		$setarr,
		array('product_influencingfactor_id'=>$product_influencingfactor_id)
		);
	
	
	}
	//////////////////////////////////////////////////////////////////
	
	$url = 'space.php?do=admin_insurance_product&product_id='.$product_id;

	showmessage('do_success', $url, 0);

}

if($_GET['op'] == 'delete')
{
	//ss_log("ddddddddddddddddfdfdfdf");
	//删除
	if(1)//submitcheck('deletesubmit'))
	{
		//$ids = $_POST['ids'];
		$product_influencingfactor_id = intval($product_influencingfactor_id);
			
		//ss_log("product_duty_id: ".$product_duty_id);
		
		$ids = array($product_influencingfactor_id);
		include_once(S_ROOT.'./source/function_baoxian_admin.php');
		if(admin_insurance_product_influencingfactor_delete($ids))
		{
			$url = 'space.php?do=admin_insurance_product&product_id='.$product_id;
			showmessage('do_success', $url);
		}
		else
		{
			$url = 'space.php?do=admin_insurance_product&product_id='.$product_id;
			showmessage('do_success', $url);
		}
	}
}

$list_count = array(0,1,2,3,4,5,6,7,8);

if(empty($product_influencingfactor['product_influencingfactor_id']))//new
{
	$_TPL['css'] = 'admin_product';
	include_once template("cp_admin_insurance_product_influencingfactor");
}
else
{
	$_TPL['css'] = 'admin_product';
	include_once template("cp_admin_insurance_product_influencingfactor_modify");
}


