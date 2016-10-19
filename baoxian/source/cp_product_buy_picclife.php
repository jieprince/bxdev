<?php
$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy_picclife.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_. 'baoxian/source/function_baoxian_picclife.php');
/*人保寿险*/
function cp_buy_access_check_picclife($product,$user_info)
{
	//return true;
	////////////////////////////////////////////////////////
	$product_code 	= $product['product_code'];
	
	
	return true;
}

/*标准产品处理*/
function process_product_picclife_simple($product,$POST)
{
	//end
	ss_log(__FUNCTION__.", GET IN");
	$ret_policy_input  = input_check_picclife($POST);
	
	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数
	ss_log(__FUNCTION__.", GET OUT");
	return $ret_policy_output;
}
function process_product_picclife_shouxian($product,$POST)
{
	//end
	ss_log(__FUNCTION__.", GET IN");
	$ret_policy_input  = input_check_picclife_shouxian($POST);
	
	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数
	//寿险的附加信息处理
	$ret_policy_output = process_shouxian_additional_info($ret_policy_input, $ret_policy_output);
	
	ss_log(__FUNCTION__.", GET OUT");
	return $ret_policy_output;
}

function process_product_picclife_plan($product,$POST)
{
	$ret_policy_input  = input_check_picclife($POST);
	
	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数
		
	return $ret_policy_output;
}



function process_product_picclife($product,$POST)
{
	ss_log(__FUNCTION__.", GET IN");
	$attribute_type = $product['attribute_type'];
	$attribute_type = empty($attribute_type)?"product":$attribute_type;

	if($attribute_type == "product")//最简单的产品，下面组合参数
	{
		$ret_policy_output = process_product_picclife_simple($product,$POST);
	
	}
	else if($attribute_type == "ijiankang"
			|| $attribute_type == "BWSJ"
			|| $attribute_type == "KLWYLQX"
			|| $attribute_type == "TTL")
	{
		$ret_policy_output = process_product_picclife_shouxian($product,$POST);
	}
	else
	{
		$ret_policy_output = process_product_picclife_simple($product,$POST);
	}
	
	return $ret_policy_output;
}


//end add by wangcya, for bug[193],能够支持多人批量投保，


///////////////////////////////////////////////////////////////////////////////
function gen_cp_buy_view_info_picclife($product,$POST)
{
	$product_arr= $product;
	
	global $attr_picclife_RelationRoleCode_product;//与投保人关系，标准产品情况
	global $attr_picclife_GovtIDTC;//证件类型
	global $attr_picclife_Gender;//性别
	global $attr_picclife_RelationRoleCode;
	//////////////////////////////////////////////////////
	$_TPL['css'] = 'cp_product_buy_picclife';
	
	$attribute_type = $product['attribute_type'];
	
	$attribute_type = empty($attribute_type)?"product":$attribute_type;
	
	ss_log(__FUNCTION__.", product_code: ".$product['product_code']);
	ss_log(__FUNCTION__.", attribute_type: ".$attribute_type);
	///////////////////////////////////////////////////////
	$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
	
	//mod by zhangxi, 20150312, 活动相关代码
	$uid = $_REQUEST['uid'];
	$gid = $_GET['gid'];

	//如果保险公司有险种代码的情况，最好还是按照保险公司的险种代码来定义，
	if( $attribute_type == 'product')//标准产品，例如价格不会变
	{
		ss_log(__FUNCTION__."process attribute_type: product");
		
		$businessType = 1;
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		$prodcutMarkPrice = $product['premium'];
		$period_day = $product['period'];//一年的
		$period_uint = $product['period_uint'];//单位，天，月，年
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品价格等于单价
	
		include_once template("cp_product_buy_picclife_product");
		ss_log(__FUNCTION__."template, cp_product_buy_picclife_product");	
	}
	else if( $attribute_type == 'ijiankang')//
	{
		ss_log(__FUNCTION__."process attribute_type: ijiankang");
		
		$businessType = 1;
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		$prodcutMarkPrice = $POST['price'];//传递保费
		$prodcutMarkPrice = round($prodcutMarkPrice, 2);
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价

		//这里是字符串，不能转，获取传过来的责任价格id
		$duty_price_ids=$POST['duty_price_ids'];

		$list_product_duty_price = picclife_get_duty_price_by_id($duty_price_ids);
		
		$applyNum = $POST['applyNum'];
		$startDate = $POST['startDate'];
		$endDate = $POST['endDate'];
		$assured_birthday = $POST['assured_birthday'];
		$payPeriod = $POST['payPeriod'];
		$assured_gender = $POST['assured_gender'];
		$insPeriod = intval($POST['insPeriod']);
		
		$_TPL['css'] = 'cp_product_buy_picclife_ijiankang';
		include_once template("cp_product_buy_picclife_ijiankang");
		ss_log(__FUNCTION__."template, cp_product_buy_picclife_product");	
	}
	else if( $attribute_type == 'BWSJ')//
	{
		ss_log(__FUNCTION__."process attribute_type: BWSJ");
		$businessType = 1;
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		$prodcutMarkPrice = $POST['price'];//传递保费
		$prodcutMarkPrice = round($prodcutMarkPrice, 2);
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价

		//这里是字符串，不能转，获取传过来的责任价格id
		$duty_price_ids=$POST['duty_price_ids'];

		$list_product_duty_price = picclife_get_duty_price_by_id($duty_price_ids);
		
		$applyNum = $POST['applyNum'];//投保分数
		//$startDate = $POST['startDate'];
		//$endDate = $POST['endDate'];
		//$assured_birthday = $POST['assured_birthday'];
		$payPeriod = $POST['payPeriod'];
		//$assured_gender = $POST['assured_gender'];
		$insPeriod = intval($POST['insPeriod']);
		$insured_age_range = $POST['insured_age_range'];//被保险人年龄范围
		
		
		$_TPL['css'] = 'cp_product_buy_picclife_bwsj';
		include_once template("cp_product_buy_picclife_bwsj");
		ss_log(__FUNCTION__."template, cp_product_buy_picclife_bwsj");	
	}
	else if( $attribute_type == 'KLWYLQX')//
	{
		ss_log(__FUNCTION__."process attribute_type: KLWY");
		$businessType = 1;
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		$prodcutMarkPrice = $POST['price'];//传递保费
		$prodcutMarkPrice = round($prodcutMarkPrice, 2);
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价

		//这里是字符串，不能转，获取传过来的责任价格id
		$duty_price_ids=$POST['duty_price_ids'];

		$list_product_duty_price = picclife_get_duty_price_by_id($duty_price_ids);
		
		$applyNum = $POST['applyNum'];//投保分数
		//$startDate = $POST['startDate'];
		//$endDate = $POST['endDate'];
		//$assured_birthday = $POST['assured_birthday'];
		$payPeriod = $POST['payPeriod'];
		//$assured_gender = $POST['assured_gender'];
		$insPeriod = intval($POST['insPeriod']);
		$insured_age_range = $POST['insured_age_range'];//被保险人年龄范围
		//保额
		$policy_amount = $POST['policy_amount'];
		$startDate = $POST['startDate'];
		$endDate = $POST['endDate'];
		$assured_birthday = $POST['assured_birthday'];
		$assured_gender = $POST['assured_gender'];
		
		$_TPL['css'] = 'cp_product_buy_picclife_klwylqx';
		include_once template("cp_product_buy_picclife_klwylqx");
		ss_log(__FUNCTION__."template, cp_product_buy_picclife_klwylqx");	
	}
	else if( $attribute_type == 'TTL')//
	{
		ss_log(__FUNCTION__."process attribute_type: TTL");
		$businessType = 1;
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		$prodcutMarkPrice = $POST['price'];//传递保费
		$prodcutMarkPrice = round($prodcutMarkPrice, 2);
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价

		//这里是字符串，不能转，获取传过来的责任价格id
		$duty_price_ids=$POST['duty_price_ids'];

		$list_product_duty_price = picclife_get_duty_price_by_id($duty_price_ids);
		
		$applyNum = $POST['applyNum'];//投保分数
		//$startDate = $POST['startDate'];
		//$endDate = $POST['endDate'];
		//$assured_birthday = $POST['assured_birthday'];
		$payPeriod = $POST['payPeriod'];
		//$assured_gender = $POST['assured_gender'];
		$insPeriod = intval($POST['insPeriod']);
		$insured_age_range = $POST['insured_age_range'];//被保险人年龄范围
		
		
		$_TPL['css'] = 'cp_product_buy_picclife_ttl';
		include_once template("cp_product_buy_picclife_ttl");
		ss_log(__FUNCTION__."template, cp_product_buy_picclife_ttl");	
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
		include_once template("cp_product_buy_picclife_product");
	}
	
}


