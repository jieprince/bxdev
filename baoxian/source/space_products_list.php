<?php

if(0)
{
	$DATE = date('Ymd',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30
	echo $DATE."<br />";
	echo $TIME."<br />";
	exit(0);
}

$product_id = intval($_GET['product_id']);
if($product_id)
{
	$wheresql = "product_id='$product_id'";
	$sql = "SELECT * FROM ".tname('insurance_product_base')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$value = $value = $_SGLOBAL['db']->fetch_array($query);
	$attribute_id = $value['attribute_id'];
}
else
{
	$attribute_id = intval($_GET['attribute_id']);
}
///////////////////////////////////////////////////////////////////////////////////

$theurl = "space.php?do=products_list";
//首先得到保险公司的列表
if($attribute_id)
{//下面的代码不执行了，现在产品主页用的是他们的代码了。
	$wheresql = "attribute_id='$attribute_id'";

	$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql";

	//echo ($sql);
	$query = $_SGLOBAL['db']->query($sql);
		
	$product_attribute = $value = $_SGLOBAL['db']->fetch_array($query);
	

	////////////////////////////////////////////////////////////////////////////////
	$attribute_id = $product_attribute['attribute_id'];
	
	///////////////////保险////////////////////////////////////////
	if($product_attribute['attribute_type']=="product")//单个产品的形式
	{
		
		//保险产品列表
		$wheresql = "attribute_id=$attribute_id";
		$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_base')." WHERE $wheresql";
		$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
		if($count)
		{
			$ordersql = " ORDER BY product_id";
			$sql = "SELECT * FROM ".tname('insurance_product_base')." WHERE $wheresql $ordersql LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$product = $value = $_SGLOBAL['db']->fetch_array($query);
			
		}
		
		$product_id = $value['product_id'];
		
		
		if($product[product_duty_price_type]=='single')
		{
			/////////////////附加信息////////////////////////////////////////////
			$wheresql = "product_id='$product_id'";
		
			$sql = "SELECT * FROM ".tname('insurance_product_additional')." WHERE $wheresql LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$product_additional = $_SGLOBAL['db']->fetch_array($query);
		}
		///////////////////////////////////////////////////////////////
		
		//////////////得到保险责任////////////////////////////////
		$wheresql = "pt.product_id='$product_id'";
		$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_duty')." pt WHERE $wheresql";
		//echo $countsql;
		//ss_log($countsql);
		$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
		if($count)
		{
		
			$ordersql = "ORDER BY pt.duty_id";
				
			if($product['product_duty_price_type'] == 'single')
			{
				$sql = "SELECT * FROM ".tname('insurance_product_duty')." pt
				LEFT JOIN ".tname('insurance_product_duty_price')." ptp
				ON pt.product_duty_id=ptp.product_duty_id
				WHERE $wheresql $ordersql LIMIT 0,20";
			}
			else
			{
				$sql = "SELECT * FROM ".tname('insurance_product_duty')." pt WHERE $wheresql $ordersql LIMIT 0,20";
		
			}
			//ss_log($sql);
			$query = $_SGLOBAL['db']->query($sql);
		
			$urlduty = "cp.php?ac=admin_insurance_product&op=duty&product_id=$product_id";
			$product_duty_list = array();
			while ($valued = $_SGLOBAL['db']->fetch_array($query))
			{
				$product_duty_list[] =  $valued;
			}
		
					
		}
		///////////////////////////////////////////////////////////////
	
		$_TPL['css'] = 'client_product';
		include_once template("space_product_view");
	}//product
	elseif($product_attribute['attribute_type']=="plan")
	{
		//保险产品列表
		$wheresql = "attribute_id=$attribute_id";
		$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_base')." WHERE $wheresql";
		$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
		if($count)
		{
			$ordersql = " ORDER BY product_id";
			$sql = "SELECT * FROM ".tname('insurance_product_base')." WHERE $wheresql $ordersql LIMIT 0,5";
			$query = $_SGLOBAL['db']->query($sql);
			
			$list_product = array();
			while($value = $_SGLOBAL['db']->fetch_array($query))
			{
				$list_product[] = $value;
			}
				
		}
		
		$_TPL['css'] = 'client_product';
		include_once template("space_product_plan_view");
	
	}//plan
	elseif($product_attribute['attribute_type']=="Y502")
	{
	
	
		$_TPL['css'] = 'space_product_view_plan';
		include_once template("space_product_y502_view");
	
	}//plan
	////////////////////////////////////////////////////////////////
	
	
	
	
	/*
	$_TPL['css'] = 'client_product';
	if( $product['code']=="01225"||//平安少儿保险卡（C卡）
		$product['code']=="01097"||//平安少儿保险卡（A卡）
		$product['code']=="01098"//平安少儿保险卡（B卡）
		)
	{
		
		include_once template("space_product_view");
	}
	elseif($product['code']=="XPX_YEZH"||//太平洋学生幼儿综合意外保险个单
			$product['code']=="XPX_YEZH_TD"//太平洋学生幼儿综合意外保险团单
		  )
	{
		include_once template("space_product_view_plan");
	}
	*/

}
else
{

	//保险公司列表
	$wheresql = "1";
	$countsql = "SELECT COUNT(*) FROM ".tname('insurance_company')." WHERE $wheresql";
	//echo $countsql;
	//ss_log($countsql);
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
	if($count)
	{
	
		$sql = "SELECT * FROM ".tname('insurance_company')." WHERE $wheresql LIMIT 0,10";
	
		//ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
	
		$list_insurer = array();
		while ($value = $_SGLOBAL['db']->fetch_array($query))
		{
			$list_insurer[] =  $value;
			
		}
	}
	
	
	/////////////保险产品//////////////////////////////
	
	
	$perpage = 10;
	
	$page = empty($_GET['page'])?1:intval($_GET['page']);
	if($page<1)
		$page=1;
	
	$perpage = mob_perpage($perpage);
	
	$start = ($page-1)*$perpage;
	
	
	ckstart($start, $perpage);
	
	
	
	$wheresql = "interface_flag='1'";
	$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_attribute')." WHERE $wheresql";
	//echo $countsql;
	//ss_log($countsql);
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
	if($count)
	{
		
	
		$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql LIMIT $start,$perpage";
	
		//ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
	
		$list_products = array();
		while ($value = $_SGLOBAL['db']->fetch_array($query))
		{
			$list_products_attribute[] =  $value;
		}
		
		$multi = multi($count, $perpage, $page, $theurl);
		
	}
	
	$_TPL['css'] = 'client_product';
	
	include_once template("space_products_list");
	}

