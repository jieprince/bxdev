<?php
include_once(S_ROOT.'./source/function_baoxian_admin.php');

/////////////////////////////////////////////////////////////
$product_duty_id = empty($_GET['product_duty_id'])?0:intval($_GET['product_duty_id']);//为了修改用的
$op = empty($_GET['op'])?'':$_GET['op'];

$product_duty = array();
if($product_duty_id)
{
	//在两者表中查找日志记录并合并，主要用在编辑和删除的时候，wangcya
	/*del by wangcya,20140921 ,统一到一张表中
	$sql = "SELECT * FROM ".tname('insurance_product_duty')." pt 
	 LEFT JOIN ".tname('insurance_product_duty_price')." ptp 
	 ON pt.product_duty_id= ptp.product_duty_id 
	 WHERE pt.product_duty_id='$product_duty_id'";
	 */
	$sql = "SELECT * FROM ".tname('insurance_product_duty')." pt INNER JOIN	".tname('insurance_duty')." inst ON pt.duty_id=inst.duty_id
	  WHERE pt.product_duty_id='$product_duty_id'";
	  
	$query = $_SGLOBAL['db']->query($sql);

	$product_duty = $_SGLOBAL['db']->fetch_array($query);
	$product_id = $product_duty['product_id'];
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
	if($product_id)
	{
		
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
}

//////////////////////////////////////////////////////////////////////////////////////////
$theUrl = "cp.php?ac=admin_insurance_product_duty&product_id=$product[product_id]";


//showmessage("fffffffff");
if(empty($product_duty))//没有，则一般为新增日志，则要检查，wangcya
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

	
}

//添加编辑操作
if(submitcheck('product_duty_submit'))
{//日志的提交工作，wangcya
	
	
	if(empty($product_duty['product_duty_id']))//new
	{
		$_POST['product_id'] = $product['product_id'];

		$product_duty = array();
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
		
		
		
	
	if($newblog = admin_insurance_product_duty_post($_POST, $product_duty))
	{//提交产品责任成功后，则提交产品责任价格

		/*单个的时候，价格则放到一张表中
		$product_duty_id = $newblog['product_duty_id'];
		if($product['product_duty_price_type']=="single")//直接设置价格的方式，则需要在另外一个表中进行增加一条记录。
		{
			
			$sql = "SELECT * FROM ".tname('insurance_product_duty_price')." WHERE product_duty_id='$product_duty_id' LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			
			$product_duty_price = $_SGLOBAL['db']->fetch_array($query);
			////////////////////////////////////////////////////////////////////////////////
			if(empty($product_duty_price))
			{
				$_POST['product_duty_id'] = $product_duty_id;
			}
			
			admin_insurance_product_duty_price_single_post($_POST,$product_duty_price);
		}
		*/
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
	ss_log("productduty_order_submit");
	
	if($product['product_duty_price_type']=='single')//一对一的时候
	{
		$duty_ids = $_POST['duty_ids'];
		/////////////////////////////////////////////////////////////////
		
		$view_order_ids = $_POST['view_order_ids'];
		foreach ($view_order_ids as $key=>$value)
		{
			$product_duty_id = intval($key);
			$view_order = intval($value);
			
			//ss_log("product_duty_id: ".$product_duty_id." view_order: ".$view_order);
			$setarr = array('view_order'=>$view_order);
			//$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
			updatetable('insurance_product_duty', 
						$setarr, 
						array('product_duty_id'=>$product_duty_id)
						);
			
			
		}
		
		//////////////////////////////////////////////////////////////////
		$amount_ids = $_POST['amount_ids'];
		foreach ($amount_ids as $key=>$value)
		{
			$product_duty_id = intval($key);
			$amount = trim($value);
		
			//ss_log("product_duty_id: ".$product_duty_id." view_order: ".$view_order);
			$setarr = array('amount'=>$amount);
				
			$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
			updatetable('insurance_product_duty',
			$setarr,
			array('product_duty_id'=>$product_duty_id)
			);
		
		
		}
		
		///////////////////下面的则操作另外一张表///////////////////////////////////////////////
		$duty_name_ids = $_POST['duty_name_ids'];
		foreach ($duty_name_ids as $key=>$value)
		{
			$product_duty_id = intval($key);
			$duty_name = trim($value);			

			/////////////////////////////////////////////////////////////////
			/////////////////////////////////////////////////////
			$duty_id = $duty_ids[$product_duty_id] ;
			
			//ss_log("product_duty_id: ".$product_duty_id." view_order: ".$view_order);
			$setarr = array('duty_name'=>$duty_name);
			$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
			
			updatetable('insurance_duty',
			$setarr,
			array('duty_id'=>$duty_id)
			);
		
		
		}
		
		//////////////////////////////////////////////////////////////////

		
		$duty_code_ids = $_POST['duty_code_ids'];
		foreach ($duty_code_ids as $key=>$value)
		{
			$product_duty_id = intval($key);
			$duty_code = trim($value);
		
			/////////////////////////////////////////////////////
			$duty_id = $duty_ids[$product_duty_id] ;
			//ss_log("product_duty_id: ".$product_duty_id." view_order: ".$view_order);
			$setarr = array('duty_code'=>$duty_code);
		
			$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
			updatetable('insurance_duty',
			$setarr,
			array('duty_id'=>$duty_id)
			);
		
		
		}
		
		
		//////////////////////////////////////////////////////////////////
		$duty_note_ids = $_POST['duty_note_ids'];
		foreach ($duty_note_ids as $key=>$value)
		{
			$product_duty_id = intval($key);
			$duty_note = trim($value);
		
			/////////////////////////////////////////////////////
			$duty_id = $duty_ids[$product_duty_id] ;
			//ss_log("product_duty_id: ".$product_duty_id." view_order: ".$view_order);
			$setarr = array('duty_note'=>$duty_note);
			
			$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection

			updatetable('insurance_duty',
			$setarr,
			array('duty_id'=>$duty_id)
			);
		
		
		}
	}
	//////////////////////////////////////////////////////////////////
	
	$url = 'space.php?do=admin_insurance_product&product_id='.$product_id;
	
	showmessage('do_success', $url, 0);
	
}

if($_GET['op'] == 'delete')
{
	//删除
	if(1)//submitcheck('deletesubmit'))
	{
		//$ids = $_POST['ids'];
		$product_duty_id = intval($product_duty_id);
			
		//ss_log("product_duty_id: ".$product_duty_id);
		
		$ids = array($product_duty_id);
		include_once(S_ROOT.'./source/function_delete.php');
		if(admin_insurance_product_duty_delete($ids))
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



if(empty($product_duty['product_duty_id']))//new
{
	$_TPL['css'] = 'admin_product';
	include_once template("cp_admin_insurance_product_duty");
}
else
{
	$_TPL['css'] = 'admin_product';
	include_once template("cp_admin_insurance_product_duty_modify");
}
