<?php
$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy_cpic_cargo.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_. 'baoxian/source/function_baoxian_cpic_cargo.php');
/*人保寿险*/
function cp_buy_access_check_cpic_cargo($product,$user_info)
{
	//return true;
	////////////////////////////////////////////////////////
	$product_code 	= $product['product_code'];
	
	
	return true;
}
function cp_buy_access_check_cpic_tj_property($product,$user_info)
{
	//return true;
	////////////////////////////////////////////////////////
	$product_code 	= $product['product_code'];
	
	
	return true;
}
function cpic_cargo_other_update_additional_info($ret_policy_input, $ret_policy_output)
{
	global $_SGLOBAL;
	
	$global_info = $ret_policy_input["global_info"];
	$policy_id = $ret_policy_output['policy_arr']['policy_id'];
	ss_log(__FUNCTION__.", 准备插入的附加信息到数据库");
		
	$attr_additional_info = array(
			"policy_id"=>$policy_id,
			"BusinessType"=>$global_info['BusinessType'],//
			"RoadLicenseNumber"=>$global_info['RoadLicenseNumber'],//
			"GoodsName"=>$global_info['GoodsName'],//
			"TransportVehiclesApprovedTotal"=>$global_info['TransportVehiclesApprovedTotal'],
			"VehicleOwners"=>$global_info['VehicleOwners'],//
			"CarNumber"=>$global_info['CarNumber'],//
			"FrameNumber"=>$global_info['FrameNumber'],//
			"EngineNo"=>$global_info['EngineNo'],//
			"EachAccidentFranchise"=>$global_info['EachAccidentFranchise'],//
			"TimeAmount"=>$global_info['TimeAmount'],//
			"SumAmount"=>$global_info['SumAmount'],//
			"Rate"=>$global_info['Rate'],//
			"ItemCode"=>$global_info['ItemCode'],//
			"Specialterm"=>$global_info['Specialterm'],//
			
			"TotalInsuredCar"=>$global_info['TotalInsuredCar'],//
			"SailDate"=>$global_info['SailDate'],//
			"StartPlace"=>$global_info['StartPlace'],//
			"EndPlace"=>$global_info['EndPlace'],//
			"DeliverorderNo"=>$global_info['DeliverorderNo'],//
			"Premium"=>$global_info['Premium'],//
			"classtype"=>$global_info['classtype'],
	);
		
	inserttable("insurance_policy_cpic_cargo_other_info",$attr_additional_info);
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$wheresql = "policy_id='$policy_id'";
	$sql = "SELECT * FROM ".tname('insurance_policy_cpic_cargo_other_info')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$policy_additional_info = $_SGLOBAL['db']->fetch_array($query);
	
	if($policy_additional_info)
	{
		ss_log(__FUNCTION__.",得到附加信息");
		$ret_policy_output['policy_arr']["BusinessType"] = $policy_additional_info['BusinessType'];//
		$ret_policy_output['policy_arr']["RoadLicenseNumber"] = $policy_additional_info['RoadLicenseNumber'];//
		$ret_policy_output['policy_arr']["GoodsName"] = $policy_additional_info['GoodsName'];//
		$ret_policy_output['policy_arr']["TransportVehiclesApprovedTotal"] = $policy_additional_info['TransportVehiclesApprovedTotal'];//
		$ret_policy_output['policy_arr']["VehicleOwners"] = $policy_additional_info['VehicleOwners'];//
		$ret_policy_output['policy_arr']["CarNumber"] = $policy_additional_info['CarNumber'];
		$ret_policy_output['policy_arr']["FrameNumber"] = $policy_additional_info['FrameNumber'];
		$ret_policy_output['policy_arr']["EngineNo"] = $policy_additional_info['EngineNo'];
		$ret_policy_output['policy_arr']["EachAccidentFranchise"] = $policy_additional_info['EachAccidentFranchise'];
		$ret_policy_output['policy_arr']["TimeAmount"] = $policy_additional_info['TimeAmount'];
		
		$ret_policy_output['policy_arr']["SumAmount"] = $policy_additional_info['SumAmount'];
		$ret_policy_output['policy_arr']["Rate"] = $policy_additional_info['Rate'];
		$ret_policy_output['policy_arr']["ItemCode"] = $policy_additional_info['ItemCode'];
		$ret_policy_output['policy_arr']["Specialterm"] = $policy_additional_info['Specialterm'];
		
		$ret_policy_output['policy_arr']["TotalInsuredCar"] = $policy_additional_info['TotalInsuredCar'];
		$ret_policy_output['policy_arr']["SailDate"] = $policy_additional_info['SailDate'];
		$ret_policy_output['policy_arr']["StartPlace"] = $policy_additional_info['StartPlace'];
		$ret_policy_output['policy_arr']["EndPlace"] = $policy_additional_info['EndPlace'];
		$ret_policy_output['policy_arr']["DeliverorderNo"] = $policy_additional_info['DeliverorderNo'];
		$ret_policy_output['policy_arr']["Premium"] = $policy_additional_info['Premium'];
		$ret_policy_output['policy_arr']["classtype"] = $policy_additional_info['classtype'];
		
	}
	
	return $ret_policy_output;
}


function cpic_cargo_update_additional_info($ret_policy_input, $ret_policy_output)
{
	global $_SGLOBAL;
	$global_info = $ret_policy_input["global_info"];
	$policy_id = $ret_policy_output['policy_arr']['policy_id'];
	ss_log(__FUNCTION__.", 准备插入的附加信息到数据库");
		
	$attr_additional_info = array(
			"policy_id"=>$policy_id,
			"classtype"=>$global_info['classtype'],//
			"mark"=>$global_info['mark'],//
			"quantity"=>$global_info['quantity'],//
			"item"=>$global_info['item'],
			"packcode"=>$global_info['packcode'],//
			"itemcode"=>$global_info['itemcode'],//
			"flightareacode"=>$global_info['flightareacode'],//
			"kind"=>$global_info['kind'],//
			"kindname"=>$global_info['kindname'],//
			"voyno"=>$global_info['voyno'],//
			"startport"=>$global_info['startport'],//
			"transport1"=>$global_info['transport1'],//
			"endport"=>$global_info['endport'],//
			"claimagent"=>$global_info['claimagent'],//
			"mainitemcode"=>$global_info['mainitemcode'],//
			"itemcontent"=>$global_info['itemcontent'],//
			"currencycode"=>$global_info['currencycode'],//
			"pricecond"=>$global_info['pricecond'],//
			"invamount"=>$global_info['invamount'],//
			"incrate"=>$global_info['incrate'],//
			"amount"=>$global_info['amount'],//
			"rate"=>$global_info['rate'],//
			"premium"=>$global_info['premium'],//
			"fcurrencycode"=>$global_info['fcurrencycode'],//
			"claimcurrencycode"=>$global_info['claimcurrencycode'],//
			"claimpayplace"=>$global_info['claimpayplace'],//
			"effectdate"=>$global_info['effectdate'],//
			"saildate"=>$global_info['saildate'],//
			"franchise"=>$global_info['franchise'],//
			"specialize"=>$global_info['specialize'],
			"comments"=>$global_info['comments'],
	);
		
	inserttable("insurance_policy_cpic_cargo_internal_entrance_otherinfo",$attr_additional_info);
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$wheresql = "policy_id='$policy_id'";
	$sql = "SELECT * FROM ".tname('insurance_policy_cpic_cargo_internal_entrance_otherinfo')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$policy_additional_info = $_SGLOBAL['db']->fetch_array($query);
	
	if($policy_additional_info)
	{
		ss_log(__FUNCTION__.",得到附加信息");
		$ret_policy_output['policy_arr']["classtype"] = $policy_additional_info['classtype'];//
		$ret_policy_output['policy_arr']["mark"] = $policy_additional_info['mark'];//
		$ret_policy_output['policy_arr']["quantity"] = $policy_additional_info['quantity'];//
		$ret_policy_output['policy_arr']["item"] = $policy_additional_info['item'];//
		$ret_policy_output['policy_arr']["packcode"] = $policy_additional_info['packcode'];//
		$ret_policy_output['policy_arr']["itemcode"] = $policy_additional_info['itemcode'];
		$ret_policy_output['policy_arr']["flightareacode"] = $policy_additional_info['flightareacode'];
		$ret_policy_output['policy_arr']["kind"] = $policy_additional_info['kind'];
		$ret_policy_output['policy_arr']["kindname"] = $policy_additional_info['kindname'];
		$ret_policy_output['policy_arr']["voyno"] = $policy_additional_info['voyno'];
		
		$ret_policy_output['policy_arr']["startport"] = $policy_additional_info['startport'];
		$ret_policy_output['policy_arr']["transport1"] = $policy_additional_info['transport1'];
		$ret_policy_output['policy_arr']["endport"] = $policy_additional_info['endport'];
		$ret_policy_output['policy_arr']["claimagent"] = $policy_additional_info['claimagent'];
		$ret_policy_output['policy_arr']["mainitemcode"] = $policy_additional_info['mainitemcode'];
		$ret_policy_output['policy_arr']["itemcontent"] = $policy_additional_info['itemcontent'];
		$ret_policy_output['policy_arr']["currencycode"] = $policy_additional_info['currencycode'];
		$ret_policy_output['policy_arr']["pricecond"] = $policy_additional_info['pricecond'];
		$ret_policy_output['policy_arr']["invamount"] = $policy_additional_info['invamount'];
		$ret_policy_output['policy_arr']["incrate"] = $policy_additional_info['incrate'];
		$ret_policy_output['policy_arr']["amount"] = $policy_additional_info['amount'];
		$ret_policy_output['policy_arr']["rate"] = $policy_additional_info['rate'];
		$ret_policy_output['policy_arr']["premium"] = $policy_additional_info['premium'];
		$ret_policy_output['policy_arr']["fcurrencycode"] = $policy_additional_info['fcurrencycode'];
		$ret_policy_output['policy_arr']["claimcurrencycode"] = $policy_additional_info['claimcurrencycode'];
		
		$ret_policy_output['policy_arr']["claimpayplace"] = $policy_additional_info['claimpayplace'];
		$ret_policy_output['policy_arr']["effectdate"] = $policy_additional_info['effectdate'];
		$ret_policy_output['policy_arr']["saildate"] = $policy_additional_info['saildate'];
		$ret_policy_output['policy_arr']["franchise"] = $policy_additional_info['franchise'];
		$ret_policy_output['policy_arr']["specialize"] = $policy_additional_info['specialize'];
		$ret_policy_output['policy_arr']["comments"] = $policy_additional_info['comments'];
		
		
	
	}
	
	return $ret_policy_output;
}
function process_product_cpic_cargo_other($product,$POST)
{
	global $_SGLOBAL;
	ss_log(__FUNCTION__.", GET IN");
	$ret_policy_input  = input_check_cpic_cargo_other($product, $POST);
	
	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数 ,生成保单
	
	//增加附加信息数据到附加数据库中
	$ret_policy_output = cpic_cargo_other_update_additional_info($ret_policy_input, $ret_policy_output);
	return $ret_policy_output;
}
/*标准产品处理*/
function process_product_cpic_cargo_simple($product,$POST)
{
	//end
	global $_SGLOBAL;
	ss_log(__FUNCTION__.", GET IN");
	$ret_policy_input  = input_check_cpic_cargo($product, $POST);
	
	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数 ,生成保单
	
	//增加附加信息数据到附加数据库中
	$ret_policy_output = cpic_cargo_update_additional_info($ret_policy_input, $ret_policy_output);
	return $ret_policy_output;
}


function process_product_cpic_cargo_plan($product,$POST)
{
	$ret_policy_input  = input_check_cpic_cargo($product, $POST);
	
	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数
		
	return $ret_policy_output;
}



function process_product_cpic_cargo($product,$POST)
{
	ss_log(__FUNCTION__.", GET IN");
	$attribute_type = $product['attribute_type'];
	$attribute_type = empty($attribute_type)?"product":$attribute_type;

	$insurer_code 	= $product['insurer_code'];
	if($attribute_type == "internal_cargo"
	||$attribute_type == "entrance_cargo")//最简单的产品，下面组合参数
	{
		$ret_policy_output = process_product_cpic_cargo_simple($product,$POST);
	
	}
	elseif($attribute_type == "other_cargo")
	{
		$ret_policy_output = process_product_cpic_cargo_other($product,$POST);
	}
	else
	{
		$ret_policy_output = process_product_cpic_cargo_simple($product,$POST);
	}
	
	return $ret_policy_output;
}


//end add by wangcya, for bug[193],能够支持多人批量投保，


///////////////////////////////////////////////////////////////////////////////
function gen_cp_buy_view_info_cpic_cargo($product,$POST)
{
	$product_arr= $product;
	       
	global $arr_cpic_cargo_internal_insurance_type;
	global $arr_cpic_cargo_package_code;
	global $arr_cpic_cargo_internal_transport_type;
	global $_SGLOBAL;
	//////////////////////////////////////////////////////
	
	$attribute_type = $product['attribute_type'];
	
	$attribute_type = empty($attribute_type)?"product":$attribute_type;
	$product['sum_insured'] = round($product['sum_insured'], 2);//保额只保留两位小数
	ss_log(__FUNCTION__.", product_code: ".$product['product_code']);
	ss_log(__FUNCTION__.", attribute_type: ".$attribute_type);
	///////////////////////////////////////////////////////
	$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
	
	//mod by zhangxi, 20150312, 活动相关代码
	$uid = $_REQUEST['uid'];
	$gid = $_GET['gid'];

	//如果保险公司有险种代码的情况，最好还是按照保险公司的险种代码来定义，
	if( $attribute_type == 'internal_cargo')//
	{
		$product_id = $product['product_id'];
		$wheresql = "p.product_id='$product_id'";
		$sql = "SELECT * FROM ".tname('insurance_product_duty')." p
		WHERE $wheresql";
	
		$query = $_SGLOBAL['db']->query($sql);
		$duty_info = $_SGLOBAL['db']->fetch_array($query);
		
		ss_log(__FUNCTION__."process attribute_type: product");
		$_TPL['css'] = 'cp_product_buy_cpic_cargo';
		$businessType = 1;
		$bat_post_policy = 0;
		
		$prodcutMarkPrice = $product['premium'];
		$classtype = $product['product_code'];
		$transport_type = 4;
		//$period_day = 365;//一年的
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品价格等于单价
	
		include_once template("cp_product_buy_cpic_cargo_internal");
		ss_log(__FUNCTION__."template, cp_product_buy_cpic_cargo_internal");	
	}
	else if( $attribute_type == 'entrance_cargo')//
	{
		ss_log(__FUNCTION__."process attribute_type: ".$attribute_type);
		$_TPL['css'] = 'cp_product_buy_cpic_cargo';
		$businessType = 1;
		$bat_post_policy = 0;//
		$prodcutMarkPrice = $product['premium'];;

		$period_min = isset($_POST['period_min'])? intval($_POST['period_min']): 0;
		$period_max = isset($_POST['period_max'])? intval($_POST['period_max']): 0;
		$product_influencingfactor_id = isset($_POST['product_influencingfactor_id'])? intval($_POST['product_influencingfactor_id']): 0;
		
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品价格等于单价
	
		include_once template("cp_product_buy_cpic_cargo_entrance");
		ss_log(__FUNCTION__."template, cp_product_buy_cpic_cargo_entrance");	
	}
	else if( $attribute_type == 'other_cargo')//
	{
		ss_log(__FUNCTION__."process attribute_type: ".$attribute_type);
		$_TPL['css'] = 'cp_product_buy_cpic_cargo_other';
		$businessType = 1;
		$bat_post_policy = 0;//
		$prodcutMarkPrice = $product['premium'];;

		$period_min = isset($_POST['period_min'])? intval($_POST['period_min']): 0;
		$period_max = isset($_POST['period_max'])? intval($_POST['period_max']): 0;
		$product_influencingfactor_id = isset($_POST['product_influencingfactor_id'])? intval($_POST['product_influencingfactor_id']): 0;
		
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品价格等于单价
	
		include_once template("cp_product_buy_cpic_cargo_other");
		ss_log(__FUNCTION__."template, cp_product_buy_cpic_cargo_other");	
	}
	else
	{  
		ss_log("process attribute_type: other");
		//这个分支是为了保证兼容性，不影响原有的没有可虑到的可能流程
		
		$businessType = 1;
		$bat_post_policy = 0;//被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		
		$prodcutMarkPrice = $product['premium'];
		$period_day = 365;//一年的
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价
		include_once template("cp_product_buy_cpic_cargo_internal");
	}
	
}