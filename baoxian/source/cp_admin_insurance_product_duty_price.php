<?php
include_once(S_ROOT.'./source/function_baoxian_admin.php');

/////////////////////////////////////////////////////////////
$product_duty_price_id = empty($_GET['product_duty_price_id'])?0:intval($_GET['product_duty_price_id']);//为了修改用的
$op = empty($_GET['op'])?'':$_GET['op'];

$product_duty_price = array();
if($product_duty_price_id)
{
	//在两者表中查找日志记录并合并，主要用在编辑和删除的时候，wangcya
	$sql = "SELECT * FROM ".tname('insurance_product_duty_price').
	" WHERE product_duty_price_id='$product_duty_price_id'";
	
	$query = $_SGLOBAL['db']->query($sql);

	$product_duty_price = $_SGLOBAL['db']->fetch_array($query);
	$product_duty_id = $product_duty_price['product_duty_id'];
}
//////////////////////////////////////////////////////////////////

if(empty($product_duty_id))
{
	$product_duty_id = empty($_GET['product_duty_id'])?0:intval($_GET['product_duty_id']);
}
///////////////////////////////////////////////////////////////////
$product_duty = array();
if($product_duty_id)
{
	//在两者表中查找日志记录并合并，主要用在编辑和删除的时候，wangcya
	$sql = "SELECT * FROM ".tname('insurance_product_duty').
	" WHERE product_duty_id='$product_duty_id'";
	$query = $_SGLOBAL['db']->query($sql);
	$product_duty = $_SGLOBAL['db']->fetch_array($query);
}

$product_id = $product_duty['product_id'];

//start add by wangcya , 20141211,锁定产品
if($product_id)
{
	$sql = "SELECT * FROM ".tname('insurance_product_base')." WHERE product_id='$product_id'";
	$query = $_SGLOBAL['db']->query($sql);
	$product = $_SGLOBAL['db']->fetch_array($query);
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
	
}
//end add by wangcya , 20141211,锁定产品

///////////////////////////////////////////////////////
$list_influencingfactor = array('0','1','2','3','4','5','6','7');
/////////////////////找到保费影响因素///////////////////////////////////////////
$type = 'career';
$list_product_influencingfactor_career = admin_get_insurance_product_influencingfactor($product_id,$type);
ss_log(__FILE__.", list_product_influencingfactor_career=".var_export($list_product_influencingfactor_career,true));
$type = 'period';
$list_product_influencingfactor_period = admin_get_insurance_product_influencingfactor($product_id,$type);
ss_log(__FILE__.", list_product_influencingfactor_period=".var_export($list_product_influencingfactor_period,true));

$type = 'age';
$list_product_influencingfactor_age = admin_get_insurance_product_influencingfactor($product_id,$type);
ss_log(__FILE__.", list_product_influencingfactor_age=".var_export($list_product_influencingfactor_age,true));
////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////
$theUrl = "cp.php?ac=admin_insurance_product_duty&product_id=$product[product_id]";


//showmessage("fffffffff");
if(empty($product_duty_price))//没有，则一般为新增日志，则要检查，wangcya
{	
	ss_log(__FILE__.", product_duty_price=".$product_duty_price);
}
else
{
	ss_log(__FILE__.", 2 product_duty_price=".$product_duty_price);
	//有日志，则检查日志的UID就是所属者是否和当前访问者一致，wangcya
	if($_SGLOBAL['supe_uid'] != $product_duty_price['uid'] && !checkperm('manageblog'))
	{//不是本人，也没有管理权限，则不允许操作，因为只有编辑和删除的时候才用blogid,wangcya
		showmessage('no_authority_operation_of_the_log');
	}

	$career_select = array($product_duty_price['product_career_id'] => ' selected');
	$period_select = array($product_duty_price['product_period_id'] => ' selected');
	$age_select = array($product_duty_price['product_age_id'] => ' selected');
	
}



//添加编辑操作
if(submitcheck('product_duty_price_submit'))//新添加也走这里吧？
{//日志的提交工作，wangcya
	ss_log(__FILE__.", product_duty_price_submit=true");
	if(empty($product_duty_price['product_duty_price_id']))//new
	{
		$_POST['product_duty_id'] = $product_duty_id;

		$product_duty_price = array();
	}
	else
	{

	}
		
	//验证码
	if(checkperm('seccode') && !ckseccode($_POST['seccode']))
	{
		showmessage('incorrect_code');
	}
		
		

	if($newblog = admin_insurance_product_duty_price_multiple_post($_POST, $product_duty_price))
	{//提交产品责任成功后，则提交产品责任价格
			
		//$product_duty_id = $newblog['product_duty_id'];
		////////////////////////////////////////////////////////////////////////
		if(!$product_duty_id)
		{
			//$product_duty_id = $newblog['product_duty_id'];
			$url = 'space.php?do=admin_insurance_product&product_duty_id='.$product_duty_id;
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
elseif(submitcheck('product_duty_price_order_submit'))
{
	ss_log(__FILE__.", product_duty_price_order_submit=true");
	$view_order_ids = $_POST['view_order_ids'];
	foreach ($view_order_ids as $key=>$value)
	{
		$product_duty_price_id = intval($key);
		$view_order = intval($value);
	
		//ss_log("product_duty_id: ".$product_duty_id." view_order: ".$view_order);
		
		$setarr = array('view_order'=>$view_order);
		//$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_product_duty_price',
		$setarr,
		array('product_duty_price_id'=>$product_duty_price_id)
		);
	
	
	}	
	
	$amount_ids = $_POST['amount_ids'];
	foreach ($amount_ids as $key=>$value)
	{
		$product_duty_price_id = intval($key);
		$amount = trim($value);
	
		//ss_log("product_duty_id: ".$product_duty_id." view_order: ".$view_order);
	
		$setarr = array('amount'=>$amount);
		
		$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_product_duty_price',
		$setarr,
		array('product_duty_price_id'=>$product_duty_price_id)
		);
	
	
	}
	
	$premium_ids = $_POST['premium_ids'];
	foreach ($premium_ids as $key=>$value)
	{
		$product_duty_price_id = intval($key);
		$premium = trim($value);
	
		//ss_log("product_duty_id: ".$product_duty_id." view_order: ".$view_order);
	
		$setarr = array('premium'=>$premium);
		//$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_product_duty_price',
		$setarr,
		array('product_duty_price_id'=>$product_duty_price_id)
		);
	
	
	}
	
	/////////////////////////////////////////////////////////////////
	$ids = $_POST['ids'];
	if($ids)
	{
		
		include_once(S_ROOT.'./source/function_delete.php');
		if(admin_insurance_product_duty_price_delete($ids))
		{
			
		}
	}
	//////////////////////////////////////////////////////////////////
	$url = 'space.php?do=admin_insurance_product_duty_price&product_duty_id='.$product_duty_id;
	showmessage('do_success', $url, 0);

}

if($_GET['op'] == 'delete')
{
	//删除
	if(submitcheck('deletesubmit'))
	{
		
		$product_duty_price_id = intval($product_duty_price_id);
		if(!empty($product_duty_price_id))
		{
			//ss_log("product_duty_id: ".$product_duty_id);
		
			$ids = array($product_duty_price_id);
		}
		else
		{
			$ids = $_POST['ids'];
		}
		
		/////////////////////////////////////////////////////////////////////
		include_once(S_ROOT.'./source/function_delete.php');
		if(admin_insurance_product_duty_price_delete($ids))
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

if(empty($product_duty_price['product_duty_price_id']))//new,准备新的添加操作的页面
{
	$_TPL['css'] = 'admin_product';
	include_once template("cp_admin_insurance_product_duty_price");
}
else//修改操作的页面，责任，影响因素，价格数据
{
	$_TPL['css'] = 'admin_product';
	include_once template("cp_admin_insurance_product_duty_price_modify");
}
