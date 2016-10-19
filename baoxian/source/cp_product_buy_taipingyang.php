<?php

function cp_buy_access_check_taipingyang($product,$user_info)
{
	return true;
	
}
function process_product_cpic_tj_family_property($product,$POST)
{
	$retattr = input_check_cpic_tj_family_property($product, $POST);
	
	$ret_policy_output = process_gen_policy($retattr,$product);//公共函数

	return $ret_policy_output;
}
function process_product_cpic_tj_property($product,$POST)
{
	$retattr = input_check_cpic_tj_property($product, $POST);
	
	$ret_policy_output = process_gen_policy($retattr,$product);//公共函数

	return $ret_policy_output;
}
//////////////////////////////////////////////////////////////
function process_product_taipingyang_simple($product,$POST)
{
	$retattr = input_check_taipingyang($POST);
	
	$ret_policy_output = process_gen_policy($retattr,$product);//公共函数

	return $ret_policy_output;
}

function process_product_taipingyang_plan($product,$POST)
{
	$retattr = input_check_taipingyang($POST);

	$ret_policy_output = process_gen_policy($retattr,$product);//公共函数

	return $ret_policy_output;
}

function process_product_taipingyang_lvyou($product,$POST)
{
	$retattr = input_check_taipingyang($POST);

	$ret_policy_output = process_gen_policy($retattr,$product);//公共函数

	return $ret_policy_output;
}

/*航意险*/
function process_product_taipingyang_hangyi($product,$POST)
{
	
	global $_SGLOBAL;
	ss_log("into function process_product_taipingyang_hangyi");
	//////////////////////////////////////////////////////////////////////////
	$retattr = input_check_taipingyang($POST);
	$ret_policy_output = process_gen_policy($retattr,$product);//公共函数
	
	//////////////////////////////////////////////////////////////////
	$global_info = $retattr["global_info"];
	//start add by wangcya , 20141029
	//if(trim($POST['product_code'])=="2101700000000009")//航意险
	{
		//$global_info['product_code'] = $POST['product_code'];
	
		$global_info['orderDate'] = $POST['orderDate'];
		$global_info['flightNo'] = $POST['flightNo'];
		$global_info['flightFrom'] = $POST['flightFrom'];
		$global_info['flightTo'] = $POST['flightTo'];
		$global_info['takeoffDate'] = $POST['takeoffDate'];
		$global_info['landDate'] = $POST['landDate'];
		$global_info['eTicketNo'] = $POST['eTicketNo'];
		$global_info['pnrCode'] = $POST['pnrCode'];
		$global_info['ticketAmount'] = $POST['ticketAmount'];
		$global_info['travelAgency'] = $POST['travelAgency'];
	
	}

	//////////////////////////////////////////////////////////////////
	$policy_id = $ret_policy_output['policy_arr']['policy_id'];
	ss_log("准备插入太平洋的附加信息到数据库");
		
	$attr_taipingyang_other_info_hangkong = array(
			"policy_id"=>$policy_id,
			"orderDate"=>$global_info['orderDate'],//订票日期
			"flightNo"=>$global_info['flightNo'],//航班号或火车班次
			"flightFrom"=>$global_info['flightFrom'],//出发地
			"flightTo"=>$global_info['flightTo'],//目标地
			"takeoffDate"=>$global_info['takeoffDate'],//出发时间
			"landDate"=>$global_info['landDate'],//到达时间
			"eTicketNo"=>$global_info['eTicketNo'],//电子票号
			"pnrCode"=>$global_info['pnrCode'],//PNR码
			"ticketAmount"=>$global_info['ticketAmount'],//机票金额
			"travelAgency"=>$global_info['travelAgency']//签约旅行社
	);
		
	inserttable("insurance_policy_taipingyang_yiwai_otherinfo_hangkong",$attr_taipingyang_other_info_hangkong);

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$wheresql = "policy_id='$policy_id'";
	$sql = "SELECT * FROM ".tname('insurance_policy_taipingyang_yiwai_otherinfo_hangkong')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$policy_yiwai_otherinfo = $_SGLOBAL['db']->fetch_array($query);
	
	if($policy_yiwai_otherinfo)
	{
		ss_log("得到太平洋航意险的附加信息");
		$ret_policy_output['policy_arr']["orderDate"] = $policy_yiwai_otherinfo['orderDate'];//
		$ret_policy_output['policy_arr']["flightNo"] = $policy_yiwai_otherinfo['flightNo'];//
		$ret_policy_output['policy_arr']["flightFrom"] = $policy_yiwai_otherinfo['flightFrom'];//
		$ret_policy_output['policy_arr']["flightTo"] = $policy_yiwai_otherinfo['flightTo'];//
		$ret_policy_output['policy_arr']["takeoffDate"] = $policy_yiwai_otherinfo['takeoffDate'];//
		$ret_policy_output['policy_arr']["landDate"] = $policy_yiwai_otherinfo['landDate'];
		$ret_policy_output['policy_arr']["eTicketNo"] = $policy_yiwai_otherinfo['eTicketNo'];
		$ret_policy_output['policy_arr']["pnrCode"] = $policy_yiwai_otherinfo['pnrCode'];
		$ret_policy_output['policy_arr']["ticketAmount"] = $policy_yiwai_otherinfo['ticketAmount'];//机票金额
		$ret_policy_output['policy_arr']["travelAgency"] = $policy_yiwai_otherinfo['travelAgency'];//签约旅行社
	
	}
	
	return $ret_policy_output;
}
//////////////////////////////////////////////////////////////////////////////
function process_product_taipingyang($product,$POST)
{
	ss_log("into function process_product_taipingyang, product_code: ".$product['product_code']);
	
	$attribute_type = $product['attribute_type'];
	$attribute_type = empty($attribute_type)?"product":$attribute_type;
	
	ss_log("into function process_product_taipingyang, attribute_type: ".$attribute_type);
	
	/////////////////////////////////////////////////////////////////////////////
	if($attribute_type == "product")//最简单的产品，下面组合参数
	{
		$ret_policy_output = process_product_taipingyang_simple($product,$POST);
	
	}
	elseif( ($product['product_code'] == "2101700000000009")||
			$attribute_type =="hangyi")
	{//航意险
		$ret_policy_output = process_product_taipingyang_hangyi($product,$POST);
	}
	elseif($attribute_type =="plan")
	{
		$ret_policy_output = process_product_taipingyang_plan($product,$POST);
	}
	//added by zhx, 20141201, for travelling product
	elseif($attribute_type =="lvyou")
	{
		$ret_policy_output = process_product_taipingyang_lvyou($product,$POST);
	}
	//added by zhangxi, 20150623, 增加太平洋天津财险处理
	elseif($attribute_type =="tj_yiwai"
	|| $attribute_type =="tj_mazuiyiwai"
	|| $attribute_type =="tj_junanxing")
	{
		$ret_policy_output = process_product_cpic_tj_property($product,$POST);
	}
	elseif($attribute_type =="tj_property")//天津家财险
	{
		$ret_policy_output = process_product_cpic_tj_family_property($product,$POST);
	}
	elseif($attribute_type =="tj_project")//建工险
	{
		
	}
	else
	{
		$ret_policy_output = process_product_taipingyang_simple($product,$POST);
	}
	
	return $ret_policy_output;
}

function gen_cp_buy_view_info_taipingyang($product,$POST)
{
	ss_log(__FUNCTION__.", GET IN");
	$product_arr= $product;
	//////////////////////////////////////////////////////
	$_TPL['css'] = 'cp_product_buy_taipingyang';
	
	
	$attribute_type = $product['attribute_type'];
	
	$attribute_type = empty($attribute_type)?"product":$attribute_type;
	
	ss_log("into function gen_cp_buy_view_info_taipingyang, attribute_type： ".$attribute_type);
	/////////////////////////////////////////////////////////
	$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
	if( $attribute_type =='product')
	{
		$businessType = 1;
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		
		$prodcutMarkPrice = $product['premium'];//需要显示到页面中
		
		$period_day = 365;//一年的，//需要显示到页面中
		ss_log("taipingyang period_day: ".$period_day);
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价
		
		include_once template("cp_product_buy_taipingyang_yiwai");
	}
	elseif( $attribute_type =='plan')
	{
		$businessType = 1;
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		
		$prodcutMarkPrice = $product['premium'];
		
		$period_day = 365;//一年的
		ss_log("taipingyang period_day: ".$period_day);
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价
		
		include_once template("cp_product_buy_taipingyang_yiwai");
	}
	//start add by wangcya, 20150320 ,航空意外险
	elseif( $attribute_type =='hangyiwai')
	{
		$businessType = 1;
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
	
		$prodcutMarkPrice = $product['premium'];
	
		$period_day = 365;//一年的
		ss_log(__FUNCTION__." taipingyang period_day: ".$period_day);
	
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价
	
		include_once template("cp_product_buy_taipingyang_hangyiwai");
	}
	//end add by wangcya, 20150320 ,航空意外险
	//added by zhangxi, 20150619, 太平洋天津产险
	elseif( $attribute_type =='tj_yiwai'
	||$attribute_type =='tj_junanxing')
	{
		global $attr_certificates_type_cpic_tj_property;
		global $attr_group_certificates_type_cpic_tj_property;
		global $attr_relationship_with_insured_cpic_tj_property;
		global $attr_industry_type_cpic_tj_property;
		$_TPL['css'] = 'cp_product_buy_taipingyang_tj_property';
		ss_log(__FUNCTION__.", attribute_type =".$attribute_type);
		$businessType = 1;
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
	
		$prodcutMarkPrice = $product['premium'];
	
		$period_day = 365;//一年的
		ss_log(__FUNCTION__." taipingyang period_day: ".$period_day);
	
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价
	
		//include_once template("cp_product_buy_taipingyang_tj_product");
		//cp_product_buy_taipingyang_yiwai
		include_once template("cp_product_buy_taipingyang_tj_product");
	}

	elseif($attribute_type =='tj_mazuiyiwai')
	{
		global $attr_certificates_type_cpic_tj_property;
		global $attr_group_certificates_type_cpic_tj_property;
		global $attr_relationship_with_insured_cpic_tj_property;
		global $attr_industry_type_cpic_tj_property;
		
		$_TPL['css'] = 'cp_product_buy_taipingyang_tj_property';
		ss_log(__FUNCTION__.", attribute_type =".$attribute_type);
		$businessType = 1;
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
	
		$prodcutMarkPrice = $product['premium'];
	
		$period_day = 365;//一年的
		ss_log(__FUNCTION__." taipingyang period_day: ".$period_day);
	
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价
	
		include_once template("cp_product_buy_taipingyang_tj_product");
	}
	elseif($attribute_type =='tj_property')//太保天津家财
	{
		global $attr_certificates_type_cpic_tj_property;
		global $attr_group_certificates_type_cpic_tj_property;
		global $attr_relationship_with_insured_cpic_tj_property;
		global $attr_industry_type_cpic_tj_property;
		
		$_TPL['css'] = 'cp_product_buy_taipingyang_tj_jiatingcaichan';
		ss_log(__FUNCTION__.", attribute_type =".$attribute_type);
		$businessType = 1;
		$bat_post_policy = 0;//
	
		$prodcutMarkPrice = $product['premium'];
	
		$period_day = 365;//一年的
		ss_log(__FUNCTION__." taipingyang period_day: ".$period_day);
	
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价
	
		include_once template("cp_product_buy_taipingyang_tj_jiatingcaichan");
	}
	elseif($attribute_type =='tj_project')//太保天津建工险
	{
		
	}
	else
	{
		$businessType = 1;
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		
		$prodcutMarkPrice = $product['premium'];
		
		$period_day = 365;//一年的
		ss_log("taipingyang period_day: ".$period_day);
		ss_log(__FUNCTION__.", ERROR , UNKOWN ATTRIBUTE TYPE");
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价
		
		include_once template("cp_product_buy_taipingyang_yiwai");
	}
	
	//////////////////////////////////////////////////////////////////////////

}