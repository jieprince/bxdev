<?php
$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy_chinalife.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_. 'baoxian/source/function_baoxian_chinalife.php');
/*中国人寿*/
function cp_buy_access_check_chinalife($product,$user_info)
{
	//return true;
	////////////////////////////////////////////////////////
	$product_code 	= $product['product_code'];
	
	///////////////////////////////////////////////////////////////
	
	return true;
}

/*国寿的标准产品处理*/
function process_product_chinalife_simple($product,$POST)
{
	//end
	ss_log(__FUNCTION__.", GET IN");
	$ret_policy_input  = input_check_chinalife($POST);
	
	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数
	ss_log(__FUNCTION__.", GET OUT");
	return $ret_policy_output;
}


function process_product_chinalife_plan($product,$POST)
{
	$ret_policy_input  = input_check_chinalife($POST);
	
	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数
		
	return $ret_policy_output;
}



function process_product_chinalife($product,$POST)
{
	ss_log(__FUNCTION__.", GET IN");
	$attribute_type = $product['attribute_type'];
	$attribute_type = empty($attribute_type)?"product":$attribute_type;

	if($attribute_type == "product")//最简单的产品，下面组合参数
	{
		$ret_policy_output = process_product_chinalife_simple($product,$POST);
	
	}
	else
	{
		$ret_policy_output = process_product_chinalife_simple($product,$POST);
	}
	
	return $ret_policy_output;
}


//end add by wangcya, for bug[193],能够支持多人批量投保，


///////////////////////////////////////////////////////////////////////////////
function gen_cp_buy_view_info_chinalife($product,$POST)
{
	$product_arr= $product;
	
	global $attr_relationship_with_insured_chinalife;
	global $attr_certificates_type_chinalife;
	//////////////////////////////////////////////////////
	$_TPL['css'] = 'cp_product_buy_chinalife';
	
	$attribute_type = $product['attribute_type'];
	
	$attribute_type = empty($attribute_type)?"product":$attribute_type;
	
	ss_log(__FUNCTION__.", product_code: ".$product['product_code']);
	ss_log(__FUNCTION__.", attribute_type: ".$attribute_type);
	///////////////////////////////////////////////////////
	$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
	
	//mod by zhangxi, 20150312, 活动相关代码
	$uid = $_REQUEST['uid'];
	$gid = $_GET['gid'];
	$simulate_b_login = $_SESSION['simulate_b_login'];

	if( $attribute_type == 'product')//标准产品，例如价格不会变
	{
		ss_log(__FUNCTION__."process attribute_type: product");
		
		$businessType = 1;
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		$prodcutMarkPrice = $product[premium];
		$period_day = 365;//一年的
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品价格等于单价
		//mod by zhangxi, 20150313, 活动和非活动走不同的模板
		ss_log("_GET['uid']:".$_GET['uid']);
		if(isset($_GET['uid']) && (!empty($_GET['uid'])))
		{
			$activity_id = $_GET['activity_id'];
			include_once template("cp_product_buy_chinalife_product");
		}
		else
		{
			include_once template("cp_product_buy_chinalife_product");
			ss_log(__FUNCTION__."template, cp_product_buy_chinalife_product");
		}
		
	}
	else
	{  
		ss_log("process attribute_type: other");
		//这个分支是为了保证兼容性，不影响原有的没有可虑到的可能流程
		
		$businessType = 1;
		$bat_post_policy = 0;//被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		
		$prodcutMarkPrice = $product[premium];
		$period_day = 365;//一年的
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价
		include_once template("cp_product_buy_chinalife_product");
	}
	
}