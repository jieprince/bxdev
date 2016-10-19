<?php
$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy_xinhua.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_. 'baoxian/source/function_baoxian_xinhua.php');
/*新华的访问检查*/
function cp_buy_access_check_xinhua($product,$user_info)
{
	$product_code 	= $product['product_code'];
	
	///////////////////////////////////////////////////////////////
	return true;
}


//用户填写完投保信息后，需要点击提交，然后会进行输入检查，并且生成投保单信息存入数据库
function process_product_xinhua($product,$POST)
{
	global $_SGLOBAL;
	$attribute_type = $product['attribute_type'];
	$attribute_type = empty($attribute_type)?"shaoer":$attribute_type;
	//增加险种输入参数
	//$insurer_code, 准备传入险种代码
	$insurer_code 	= $product['insurer_code'];
	$ret_policy_input  = input_check_xinhua($attribute_type, $POST, $insurer_code);

	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数
	
	//有附加信息需要存入数据库的在这里处理
	$global_info = $ret_policy_input["global_info"];
	$policy_id = $ret_policy_output['policy_arr']['policy_id'];
	ss_log(__FUNCTION__.", 准备插入xinhua的附加信息到数据库");
		
	$attr_xinhua_additional_info = array(
			"policy_id"=>$policy_id,
			"partPayMode"=>$global_info['partPayMode'],//支付方式
			"partPayBankCode"=>$global_info['partPayBankCode'],//银行代码
			"partPayAcctNo"=>$global_info['partPayAcctNo'],//银行账号
			"partPayAcctName"=>$global_info['partPayAcctName'],
			"serviceOrgCode"=>$global_info['serviceOrgCode'],//保单服务机构
			"payPeriod"=>$global_info['payPeriod'],//缴费期限
			"payPeriodUnit"=>$global_info['payPeriodUnit'],//缴费期限单位
			"payMode"=>$global_info['payMode'],//缴费方式，趸交，年交???
			"insPeriod"=>$global_info['insPeriod'],//保险期限
			"insPeriodUnit"=>$global_info['insPeriodUnit'],//保险期限单位
	);
		
	inserttable("insurance_policy_xinhua_renshou_additional_info",$attr_xinhua_additional_info);
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$wheresql = "policy_id='$policy_id'";
	$sql = "SELECT * FROM ".tname('insurance_policy_xinhua_renshou_additional_info')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$policy_xinhua_additional_info = $_SGLOBAL['db']->fetch_array($query);
	
	if($policy_xinhua_additional_info)
	{
		ss_log(__FUNCTION__.",得到xinhua的附加信息");
		$ret_policy_output['policy_arr']["partPayMode"] = $policy_xinhua_additional_info['partPayMode'];//
		$ret_policy_output['policy_arr']["partPayBankCode"] = $policy_xinhua_additional_info['partPayBankCode'];//
		$ret_policy_output['policy_arr']["partPayAcctNo"] = $policy_xinhua_additional_info['partPayAcctNo'];//
		$ret_policy_output['policy_arr']["partPayAcctName"] = $policy_xinhua_additional_info['partPayAcctName'];//
		$ret_policy_output['policy_arr']["serviceOrgCode"] = $policy_xinhua_additional_info['serviceOrgCode'];//
		$ret_policy_output['policy_arr']["payPeriod"] = $policy_xinhua_additional_info['payPeriod'];
		$ret_policy_output['policy_arr']["payPeriodUnit"] = $policy_xinhua_additional_info['payPeriodUnit'];
		$ret_policy_output['policy_arr']["payMode"] = $policy_xinhua_additional_info['payMode'];
		$ret_policy_output['policy_arr']["insPeriodUnit"] = $policy_xinhua_additional_info['insPeriodUnit'];
		$ret_policy_output['policy_arr']["insPeriod"] = $policy_xinhua_additional_info['insPeriod'];
	
	}

	return $ret_policy_output;
}
//added by zhangxi, 20150327, 新华少儿保单填写页面获取
function gen_cp_buy_view_info_xinhua($product,$POST)
{
	global $attr_xinhua_relationship_with_applicant ;
	global $attr_xinhua_relationship_benefit_with_applicant;
	//<!--证件类型，
	global $attr_xinhua_certificates_type;
	global $attr_xinhua_certificates_type_single_unify;
	//缴费方式
	global $attr_xinhua_pay_type;//安心宝贝只支持年交
	//续期付款方式
	global $attr_xinhua_paymode;//安心宝贝前端写死，只支持银行转账
	//保全类型
	global $attr_xinhua_baoquan_type;
	global $attr_xinhua_payment_deadline;
	global $attr_xinhua_applicant_gender;
	//保障年期代码
	global $attr_xinhua_period_unit;
	
	$product_arr= $product;
	//////////////////////////////////////////////////////
	
	$attribute_type = $product['attribute_type'];
	
	$attribute_type = empty($attribute_type)?"shaoer":$attribute_type;
	
	ss_log("into function ".__FUNCTION__.", product_code: ".$product['product_code']);
	ss_log("into function ".__FUNCTION__.", attribute_type: ".$attribute_type);
	///////////////////////////////////////////////////////
	$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
	
	if( $attribute_type =='shaoer')//新华人寿少儿，
	{
		$prodcutMarkPrice = $POST['price'];//传递保费
		$prodcutMarkPrice = round($prodcutMarkPrice, 2);
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价
		$_TPL['css'] = 'cp_product_buy_xinhua';

		//这里是字符串，不能转，获取传过来的责任价格id
		$duty_price_ids=$POST['duty_price_ids'];
		//需要通过id获取责任信息。
		//要不要再定
		
		
		$list_product_duty_price = xinhua_get_duty_price_by_id($duty_price_ids);
		//var_dump($list_product_duty_price);
		
		//保险启期，止期都传递，还有投保份数
		$applyNum = $POST['applyNum'];
		$startDate = $POST['startDate'];
		$endDate = $POST['endDate'];
		$assured_birthday = $POST['assured_birthday'];
		$payPeriodUnit = $POST['payPeriodUnit'];
		$payPeriod = $POST['payPeriod'];
		$assured_gender = $POST['assured_gender'];
		
		$gid = $_REQUEST['gid'];//增加商品id
		include_once template("cp_product_buy_xinhua_shaoer");
	}
	else
	{  
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		ss_log("process attribute_type: other");
		$_TPL['css'] = 'cp_product_buy_xinhua_default';
		include_once template("cp_product_buy_xinhua_default");
	}
			
	
}