<?php

function cp_buy_access_check_huatai($product,$user_info)
{
	return true;

}

function process_product_huatai_jingwailvyou($product,$POST)
{
	
	global $_SGLOBAL,$attr_purpose_huatai;
	//////////////////////////////////////////////////////////////////////////////////////////////

	$retattr = input_check_huatai($POST);//检查并组合成为参数后，则直接进行投保（后面付款后再进行承保）
	
	$ret_policy_output = process_gen_policy($retattr,$product);//公共函数
	
	//////////////////////////////////////////////////////////////////////////////////////////////
	$policy_arr = $ret_policy_output['policy_arr'];
	$policy_id = $policy_arr['policy_id'];
	
	ss_log("准备插入太平洋的附加信息到数据库,policy_id: ".$policy_id);
	
	$global_info = $retattr['global_info'];
	
	
	///////////////////////////////////////////////////////////////////////////////////
	ss_log("插入华泰的附加信息到数据库");
	$attr_huatai_other_info = array(
			"policy_id"=>$policy_id,
			"period_code"=>$global_info['period_code'],
			"purpose"=>$global_info['purpose'],//出行目的
			"destination_area"=>$global_info['destination_area'],//出行目的地
			"destination_country"=>$global_info['destination_country'],//出行目的地
			"visacity"=>$global_info['visacity']//签证城市
	);

	inserttable("insurance_policy_huatai_yiwai_otherinfo", $attr_huatai_other_info);
	///////////////////////////////////////////////////////////////////////////////////
	$wheresql = "policy_id='$policy_id'";
	$sql = "SELECT * FROM ".tname('insurance_policy_huatai_yiwai_otherinfo')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$policy_huatai_yiwai_otherinfo = $_SGLOBAL['db']->fetch_array($query);
	
	if($policy_huatai_yiwai_otherinfo)
	{
			
		$ret_policy_output['policy_arr']["period_code"] = $policy_huatai_yiwai_otherinfo['period_code'];//出行目的
		$ret_policy_output['policy_arr']["purpose"] = $policy_huatai_yiwai_otherinfo['purpose'];//出行目的
		$ret_policy_output['policy_arr']["destination_area"] = $policy_huatai_yiwai_otherinfo['destination_area'];//出行目的地
		$ret_policy_output['policy_arr']["destination_country"] = $policy_huatai_yiwai_otherinfo['destination_country'];//出行目的地
		$ret_policy_output['policy_arr']["visacity"] = $policy_huatai_yiwai_otherinfo['visacity'];//签证城市
		$ret_policy_output['policy_arr']["purpose"] = $attr_purpose_huatai[$policy_huatai_yiwai_otherinfo["purpose"]];
	}

	return $ret_policy_output;
}

function process_product_huatai($product,$POST)
{
	ss_log("into function process_product_huatai,product_code: ".$product['product_code']);
	
	$attribute_type = $product['attribute_type'];
	$attribute_type = empty($attribute_type)?"jingwailvyou":$attribute_type;
	
	ss_log("into function process_product_huatai,attribute_type: ".$attribute_type);
	
	//add by dingchaoyang 2015-1-9
	if (!isset($POST['fading'])){
		$POST['fading'] = '1';
	}
	//end
	////////////////////////////////////////////////////////////////////////////////////////
	if($attribute_type =="jingwailvyou"||$attribute_type =="plan")
	{
		$ret_policy_output = process_product_huatai_jingwailvyou($product,$POST);
	}
	else
	{
		$ret_policy_output = process_product_huatai_jingwailvyou($product,$POST);
	}

	return $ret_policy_output;
}

function gen_cp_buy_view_info_huatai($product,$POST)
{
	$product_arr= $product;
	
	ss_log("into function gen_cp_buy_view_info_huatai,product_code: ".$product['product_code']);
	//////////////////////////////////////////////////////
	$_TPL['css'] = 'cp_product_buy_huatai';
	
	$attribute_type = $product['attribute_type'];
	
	$attribute_type = empty($attribute_type)?"lvyou":$attribute_type;
	
	ss_log("into function gen_cp_buy_view_info_huatai,attribute_type: ".$attribute_type);
	///////////////////////////////////////////////////////
	$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
	
	if($attribute_type=="lvyou"||$attribute_type=="plan"||$attribute_type=="jwlvyou")
	{
		$businessType = 1;//要返回给前端的，
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		//start 这些信息一般需要从服务器端返回给客户端。
		/*
		start_day,保险起期，一般也是要从服务器端返回。
		$period_day，保险期限，
		$server_time,服务器的时间，用来控制投保起点
		$businessType,团单或者个人投保。
		$prodcutMarkPrice，= totalModalPremium
		*/
		//end 这些信息一般需要从服务器端返回给客户端。
		
		////////////////////////////////////////////////////////////////////////////////
		
		$prodcutMarkPrice = $POST['prodcutMarkPrice'];//要返回给前端的，由前一个产品主页计算好的提交上来的。
		ss_log("huatai 单价: ".$prodcutMarkPrice);
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价
		
		$prodcutPeriod = trim($POST['prodcutPeriod']);//由前一个产品主页选择好的的提交上来的。
		ss_log("huatai prodcutPeriod: ".$prodcutPeriod);
		
		////////////////单位为天////////////////////////////////////
		/*
		 $attr_period = array(
		 		"1-5天"=>5,
		 		"6-10天"=>10,
		 		"11-15天"=>15,
		 		"16-20天"=>20,
		 		"21-30天"=>30,
		 		"31-45天"=>45,
		 		"46-62天"=>62,
		 		"63-92天"=>92,
		 		"93-183天"=>183,
		 		"一年（多次往返）"=>365,
		 		"一年（无时间限制）"=>365
		 );
		
		$period_day = $attr_period[$prodcutPeriod];//这里得到期限，然后提供给购买也计算止期。
		
		*/
		$factor_code_peroid = empty($POST['factor_code_peroid'])?1:trim($POST['factor_code_peroid']);//add by wangcya, 20141126
		ss_log("factor_code_peroid: ".$factor_code_peroid);
		
		$attr_period = array(
				"1"=>5,
				"2"=>10,
				"3"=>15,
				"4"=>20,
				"5"=>30,
				"6"=>45,
				"7"=>62,
				"8"=>92,
				"9"=>183,
				"10"=>365,
				"11"=>365
		);
		
		$period_day = $attr_period[$factor_code_peroid];//这里得到期限，然后提供给购买也计算止期。
		
		ss_log("huatai period_day: ".$period_day);
		
		/////////////////////////////////////////////////////////
		
		 	
		 	 	
		/*
		 //<!-- 保险年期类型 1-5天 2-10天 3-15天 4-20天 5-30天 6-45天 7-62天 8-92天 9-183天 10-一年（多次） 11-一年 非空 -->
		$attr_period_code = array(
				"1-5天"=>1,
				"6-10天"=>2,
				"11-15天"=>3,
				"16-20天"=>4,
				"21-30天"=>5,
				"31-45天"=>6,
				"46-62天"=>7,
				"63-92天"=>8,
				"93-183天"=>9,
				"一年（多次往返）"=>10,
				"一年（无时间限制）"=>11
		);
		
		$period_code = $attr_period_code[$prodcutPeriod];//这里得到期限，得到编码
		*/
		$period_code = $factor_code_peroid;
		
		ss_log("huatai period_code: ".$period_code);
		
		
		/////////////////////////////////////////////////////////////
		
		include_once template("cp_product_buy_huatai_chuguo_yiwai");
	}
	else
	{
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		include_once template("cp_product_buy_huatai_chuguo_yiwai");
	}
}