<?php
$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy_huaan.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_. 'baoxian/source/function_baoxian_huaan.php');
/*华安的访问检查*/
function cp_buy_access_check_huaan($product,$user_info)
{
	$product_code 	= $product['product_code'];
	
	///////////////////////////////////////////////////////////////
	return true;
}



//用户填写完投保信息后，需要点击提交，然后会进行输入检查，并且生成投保单信息存入数据库
function process_product_huaan($product,$POST)
{
	global $_SGLOBAL;
	$attribute_type = $product['attribute_type'];
	$attribute_type = empty($attribute_type)?"project":$attribute_type;
	//增加险种输入参数
	//$insurer_code, 准备传入险种代码
	$insurer_code 	= $product['insurer_code'];
	$ret_policy_input  = input_check_huaan($attribute_type, $POST, $insurer_code);

	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数
	
	
	if($attribute_type == 'project')
	{
		$policy_arr = $ret_policy_output['policy_arr'];
		$policy_id = $policy_arr['policy_id'];
		//华安保险额外信息处理
		$global_info = $ret_policy_input['global_info'];
		
		///////////////////////////////////////////////////////////////////////////////////
		ss_log("插入华安的附加信息到数据库");
		$attr_huaan_project_info = array(
				"policy_id"=>$policy_id,
				"project_name"=>$global_info['project_name'],
				"project_price"=>$global_info['project_price'],//
				"project_start_date"=>$global_info['project_start_date'],//
				"project_end_date"=>$global_info['project_end_date'],
				"project_total_month"=>$global_info['project_total_month'],
				"project_content"=>$global_info['project_content'],
				"project_location"=>$global_info['project_location'],
				"zipcode"=>$global_info['zipcode'],
				"project_type" =>$global_info['project_type'],
				"province_name" =>$global_info['province_name'],
				"city_name" =>$global_info['city_name'],
		);
	
		inserttable("insurance_policy_huaan_project_info", $attr_huaan_project_info);
		///////////////////////////////////////////////////////////////////////////////////
		$wheresql = "policy_id='$policy_id'";
		$sql = "SELECT * FROM ".tname('insurance_policy_huaan_project_info')." WHERE $wheresql LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$policy_huaan_project_info = $_SGLOBAL['db']->fetch_array($query);
		
		if($policy_huaan_project_info)
		{
				
			$ret_policy_output['policy_arr']["project_name"] = $policy_huaan_project_info['project_name'];//
			$ret_policy_output['policy_arr']["project_price"] = $policy_huaan_project_info['project_price'];//
			$ret_policy_output['policy_arr']["project_start_date"] = $policy_huaan_project_info['project_start_date'];//
			$ret_policy_output['policy_arr']["project_end_date"] = $policy_huaan_project_info['project_end_date'];//
			$ret_policy_output['policy_arr']["project_total_month"] = $policy_huaan_project_info['project_total_month'];//
			$ret_policy_output['policy_arr']["project_content"] = $policy_huaan_project_info['project_content'];
			$ret_policy_output['policy_arr']["project_location"] = $policy_huaan_project_info['project_location'];
			$ret_policy_output['policy_arr']["zipcode"] = $policy_huaan_project_info['zipcode'];
			$ret_policy_output['policy_arr']["project_type"] = $policy_huaan_project_info['project_type'];
			$ret_policy_output['policy_arr']["province_name"] = $policy_huaan_project_info['province_name'];
			$ret_policy_output['policy_arr']["city_name"] = $policy_huaan_project_info['city_name'];
			
		}
	}

	return $ret_policy_output;
}
///////////////////////////////////////////////////////////////////////////////
function gen_cp_buy_view_info_huaan($product,$POST)
{
	//投保人与被保险关系列表
	global $attr_relationship_with_insured_huaan;
	global $attr_relationship_with_insured_huaan_xueping;
	//被保险人类型
	global $attr_assured_type_huaan;
	//被保险人证件类型
	global $attr_certificates_type_huaan;
	global $attr_certificates_type_huaan_xueping;
	//工程类型
	global $attr_project_type_huaan;
	//投保人证件类型
	global $attr_applicant_type_huaan;
	global $attr_nationality_huaan;

	//是否是被保险人
	global $attr_be_assured;
	global $attr_applicant_gender_huaan;
	global $attr_cost_limit, $attr_period_limit;
	
	global $global_attr_obj_type;
	global $attr_school_type_huaan;
	global $attr_group_applicant_type_huaan;
	
	$product_arr= $product;
	//////////////////////////////////////////////////////
	
	$attribute_type = $product['attribute_type'];
	
	$attribute_type = empty($attribute_type)?"project":$attribute_type;
	
	ss_log("into function gen_cp_buy_view_info_huaan, product_code: ".$product['product_code']);
	ss_log("into function gen_cp_buy_view_info_huaan, attribute_type: ".$attribute_type);
	///////////////////////////////////////////////////////
	$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
	
	if( $attribute_type =='project')
	{
		$prodcutMarkPrice = $POST['price'];//传递保费
		$prodcutMarkPrice = round($prodcutMarkPrice, 2);
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价
		$_TPL['css'] = 'cp_product_buy_huaan';
		//获取用户选择的工程期限范围
		$period_factor_code = intval($POST['period_factor_code']);
		$period_name = $POST['period_name'];
		$period_limit = $attr_period_limit[$POST['period_name']];
		//需要给前端传递用户选择的工程期限。
		//array('1'=>'',
		//		'2'=>'',
		//		'3'=>'',
		//		'4'=>''
		//		);
		//...
		//获取用户选择的工程造价范围
		$career_factor_code= intval($POST['cost_factor_code']);
		$cost_name= $POST['cost_name'];
		$cost_limit= $attr_cost_limit["$cost_name"];
		//需要给前端数据，好判断用户填写的工程造价是否在范围内
		//...
		//这里是字符串，不能转，获取传过来的责任价格id
		$duty_price_ids=$POST['duty_price_ids'];
		//需要通过id获取责任信息。
		//
		$list_product_duty_price = get_duty_price_by_id($duty_price_ids);
		//var_dump($list_product_duty_price);
		
		$gid = $_POST['gid'];//增加商品id
		 
		include_once template("cp_product_buy_huaan_project");
	}
	//added by zhangxi, 20150317, 华安学平险接入
	else if($attribute_type =='xuepingxian')
	{
		$attr_relationship_with_insured_huaan = $attr_relationship_with_insured_huaan_xueping;
		global $attr_certificates_type_huaan_xueping_insurant;
		$bat_post_policy = 0;
		$prodcutMarkPrice = $product['premium'];
		$period_day = 365;//一年的
		
		$totalModalPremium_single = $prodcutMarkPrice;//产品价格等于单价
		$_TPL['css'] = 'cp_product_buy_huaan_xueping';
		include_once template("cp_product_buy_huaan_xuepingxian");
	}
	else
	{  
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		ss_log("process attribute_type: other");
		$_TPL['css'] = 'cp_product_buy_huaan_xueping';
		include_once template("cp_product_buy_huaan_xuepingxian");
	}
			
	
}