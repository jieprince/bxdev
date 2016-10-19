<?php

function check_client_time($server_time,$POST)
{
	//start add by wangcya , 20141121,防止客户端时间设置有问题
	$startDate  = strtotime($POST['startDate']);//second
	ss_log("startDate: ".$startDate);
	
	$start_date = intval($POST['start_day']);
	ss_log("start_day: ".$start_date);
	
	$startDate_str = date("Y-m-d H:i:s",$startDate);
	ss_log("before startDate_str: ".$startDate_str);
	
	$startDate = $startDate - $start_date*3600*24;
	ss_log("after startDate: ".$startDate);
	
	$startDate_tr = date("Y-m-d H:i:s",$startDate);
	ss_log("after startDate: ".$startDate_tr);
	//$DATE = date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);//20140515
	
	
	$server_time_1 = ($server_time-3600*24);
	ss_log("compare ,startDate: ".$startDate." server_time_1: ".$server_time_1);
	//偏差一个小时是不允许的
	 
	if( $startDate <$server_time_1)
	{
		showmessage("您本地的时间不正确!");
		return false;
	}
	
	//end add by wangcya , 20141121,防止客户端时间设置有问题

	return true;
}
//added by zhangxi, 20150520, 寿险的附加信息处理函数
function process_shouxian_additional_info($ret_policy_input, $ret_policy_output)
{
	global $_SGLOBAL;
	//寿险附加信息处理
	//有附加信息需要存入数据库的在这里处理
	$global_info = $ret_policy_input["global_info"];
	$policy_id = $ret_policy_output['policy_arr']['policy_id'];
	ss_log(__FUNCTION__.", 准备插入寿险的附加信息到数据库");
		
	$attr_additional_info = array(
			"policy_id"=>$policy_id,
			"partPayMode"=>isset($global_info['partPayMode']) ? $global_info['partPayMode'] :NULL,//支付方式
			"partPayBankCode"=>isset($global_info['partPayBankCode'])?$global_info['partPayBankCode']:NULL,//银行代码
			"partPayAcctNo"=>isset($global_info['partPayAcctNo'])?$global_info['partPayAcctNo']:NULL,//银行账号
			"partPayAcctName"=>isset($global_info['partPayAcctName'])?$global_info['partPayAcctName']:NULL,
			"serviceOrgCode"=>isset($global_info['serviceOrgCode'])?$global_info['serviceOrgCode']:NULL,//保单服务机构
			"payPeriod"=>isset($global_info['payPeriod'])?$global_info['payPeriod']:NULL,//缴费期限
			"payPeriodUnit"=>isset($global_info['payPeriodUnit'])?$global_info['payPeriodUnit']:NULL,//缴费期限单位
			"payMode"=>isset($global_info['payMode'])?$global_info['payMode']:NULL,//缴费方式，趸交，年交???
			"insPeriod"=>isset($global_info['insPeriod'])?$global_info['insPeriod']:NULL,//保险期限
			"insPeriodUnit"=>isset($global_info['insPeriodUnit'])?$global_info['insPeriodUnit']:NULL,//保险期限单位
	);
		
	inserttable("insurance_policy_shouxian_additional_info",$attr_additional_info);
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$wheresql = "policy_id='$policy_id'";
	$sql = "SELECT * FROM ".tname('insurance_policy_shouxian_additional_info')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$policy_additional_info = $_SGLOBAL['db']->fetch_array($query);
	
	if($policy_additional_info)
	{
		ss_log(__FUNCTION__.",得到的附加信息:policy_additional_info=".var_export($policy_additional_info, true));
		$ret_policy_output['policy_arr']["partPayMode"] = $policy_additional_info['partPayMode'];//
		$ret_policy_output['policy_arr']["partPayBankCode"] = $policy_additional_info['partPayBankCode'];//
		$ret_policy_output['policy_arr']["partPayAcctNo"] = $policy_additional_info['partPayAcctNo'];//
		$ret_policy_output['policy_arr']["partPayAcctName"] = $policy_additional_info['partPayAcctName'];//
		$ret_policy_output['policy_arr']["serviceOrgCode"] = $policy_additional_info['serviceOrgCode'];//
		$ret_policy_output['policy_arr']["payPeriod"] = $policy_additional_info['payPeriod'];
		$ret_policy_output['policy_arr']["payPeriodUnit"] = $policy_additional_info['payPeriodUnit'];
		$ret_policy_output['policy_arr']["payMode"] = $policy_additional_info['payMode'];
		$ret_policy_output['policy_arr']["insPeriodUnit"] = $policy_additional_info['insPeriodUnit'];
		$ret_policy_output['policy_arr']["insPeriod"] = $policy_additional_info['insPeriod'];
	
	}
	ss_log(__FUNCTION__.",done!!!");
	return $ret_policy_output;
}
function process_gen_policy($retattr,
		                    $product,
		                    $cart_insure_id=0//add by wangcya , 20141225,批量的标志
		                    )
{
	
	$attribute_id 	= $product['attribute_id'];
	$attribute_type = $product['attribute_type'];
	$insurer_code 	= $product['insurer_code'];
	$insurer_name 	= $product['insurer_name'];
	$partner_code 	= $product['partner_code'];
	$product_code 	= $product['product_code'];
	$attribute_name = $product['attribute_name'];
	
	/////////////////////////////////////////////////////////////////////////////
	$global_info = $retattr["global_info"];
	$businessType = $retattr["businessType"];
	$relationshipWithInsured = $retattr["relationshipWithInsured"];
	$user_info_applicant = $retattr["user_info_applicant"];
	$list_subjectinfo = $retattr["list_subjectinfo"];
	/////////////////////////////////////////////////////////////////////////////
	
	/////////start add by wangcya, 20140914
	$global_info['insurer_code'] = $insurer_code;//厂商代码
	$global_info['insurer_name'] = $insurer_name;//厂商名称
	$global_info['partner_code'] = $partner_code;//合作伙伴代码
	
	$global_info['product_code'] = $product_code;//add by wangcya,20141102
	
	/////////end add by wangcya, 20140914
	//comment by zhangxi, 20150114, 原来的附加信息都是在本函数中插入，现在注释掉了
	//放外面了? 而且需要产生投保单好之后才能够进行附加信息的插入操作。
	$policy_id = insurance_gen_policy_order($attribute_id,
											$global_info,
											$businessType,
											$relationshipWithInsured,
											$user_info_applicant,
											$list_subjectinfo,
											$cart_insure_id//add by wangcya , 20141225,批量的标志
											);//产生订单和保单
	
	//add by dingchaoyang 移到cp_product_buy,因为华泰保险 生成投保单后，还有操作。放在此处，造成华泰保险投保不成功
// 	//add by dingchaoyang 2014-11-20
// 	//响应json数据到客户端
// 	//$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
// 	include_once (S_ROOT . '../api/EBaoApp/eba_adapter.php');
// 	EbaAdapter::responseTempPolicy($policy_id);//comment by wangcya, 20141226, 这个地方要传入$cart_insure_id
// 	//end add by dingchaoyang 2014-11-20
	
	////////////////////////////////////////////////////////////////////
	
	ss_log("afer insurance_gen_policy_order , policy_id: ".$policy_id);
		
	//这里不进行投保了，只保存投保信息即可。
		
	
	////////////////////////////////////////////////////
	//第二步要从保单中显示出所有的产品信息。
	////////////上面把订单保存后，下面进行投保的工作///////////////////////////////////////
	//comment by zhangxi, 20150114, 也获取了附加信息?
	$ret = get_policy_info($policy_id);//得到保单信息
	//add by dingchaoyang policy_id
	$ret['policy_id']=$policy_id;
	
	return $ret;
}
//comment by zhangxi, 20141229, 投保确认页面展现
function show_policy_info_view(
								$ret_policy_output , 
								$gid_in ,
								$bat_post_policy
								)
{
	
	//头部和底部的公共信息
	$config_data = assign_template();	
	
	global $_SGLOBAL , $global_attr_obj_type;
	//added by zhangxi, 20150331, 是否在支付之前进行核保的变量标示
	$flag_check_policy=0;
	global $ARR_INS_COMPANY_NAME;
	
	ss_log("into function ".__FUNCTION__);
	
	$gid = $gid_in;
	/////////////////////////////////////////////////////////////////////////////
	$policy_arr 				= $ret_policy_output['policy_arr'];

	
	$business_type = $policy_arr['business_type'];//add by wangcya, 20150106
	
	ss_log(__FUNCTION__.", show_policy_info_view, business_type: ".$policy_arr['business_type']);
	/////////////////////////////////////////////////////////////////////////////
	$cart_insure_id = $policy_arr['cart_insure_id'];//add by wangcya, 20141225
	$wheresql = "rec_id='$cart_insure_id'";
	$sql = "SELECT * FROM ".tname('cart_insure')." WHERE $wheresql LIMIT 1";
	ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);
	$cart_insure_arr = $_SGLOBAL['db']->fetch_array($query);
	if($cart_insure_arr)
	{
		$total_price = $cart_insure_arr['total_price'];
	}
	
	ss_log("cart_insure , total_price: ".$total_price);
	////////////////////////////////////////////////////////////////////////////////////////
	
	///////////////////////////////////////////////////////////////////////////////////////
	$user_info_applicant 		= $ret_policy_output['user_info_applicant'];
	$list_subject 				= $ret_policy_output['list_subject'];//多层级一个链表
	$relationship_with_insured 	= $policy_arr['relationship_with_insured'];
	/////////////////////////////////////////////////////////////////////////////
	$policy_id = $policy_arr['policy_id'];
	
	//add yes123 2014-12-01 去掉价格后面的0
	$policy_arr['total_premium'] = sprintf("%01.2f",$policy_arr['total_premium']);
	/////////////////////////////////////////////////////////////
	$attribute_id = $policy_arr['attribute_id'];
	
	$wheresql = "attribute_id='$attribute_id'";
	$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql LIMIT 1";
	ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);
	$product_attribute_arr = $_SGLOBAL['db']->fetch_array($query);
	$product_attribute_arr['insurance_clauses'] = stripslashes($product_attribute_arr['insurance_clauses']); //add yes123 2015-01-10 转义
	$insurer_code = $product_attribute_arr['insurer_code'];
	$attribute_type = $product_attribute_arr['attribute_type'];
	
	ss_log("insurer_code: ".$insurer_code);
	ss_log("attribute_type: ".$attribute_type);
	////////////////////////////////////////////////////////////////////
	$attr_certificates_type='';
	if(isset($global_attr_obj_type[$insurer_code]['attr_certificates_type']))
	{
		$attr_certificates_type = $global_attr_obj_type[$insurer_code]['attr_certificates_type'];
	}
	
	$attr_sex='';
	if(isset($global_attr_obj_type[$insurer_code]['attr_sex']))
	{
		$attr_sex  = $global_attr_obj_type[$insurer_code]['attr_sex'];
	}
	
	
	$attr_relationship_with_insured  = $global_attr_obj_type[$insurer_code]['attr_relationship_with_insured'];
	
	$attr_group_certificates_type  = $global_attr_obj_type[$insurer_code]['attr_group_certificates_type'];
	$attr_company_attribute_type  = $global_attr_obj_type[$insurer_code]['attr_company_attribute_type'];
	
	/////////////////////////////////////////////////////////////////////
	$product = $product_info = $list_subject[0]['list_subject_product'][0];//add by wangcya, 20141204
	$product_id = $product_info['product_id'];
	//comment by zhangxi, 20141211, 这里有问题了, 需要适应y502的情况
	
	
	$product_duty_list = get_product_duty_list($product_id,"single");//在投保确认页展示的产品责任，现在责任和价格之间都是单个关系，以后再说。
	/////////////////////////////////////////////////////////////////////
	global $attr_nationality_huaan;
	if( $policy_arr['business_type'] == 1)//个人
	{
		ss_log("before user_info_applicant certificates_type: ".$user_info_applicant['certificates_type']);
		$user_info_applicant['certificates_type_str'] = $attr_certificates_type[$user_info_applicant['certificates_type']];
		ss_log("after user_info_applicant certificates_type: ".$user_info_applicant['certificates_type_str']);
			
		$user_info_applicant['gender_str'] = $attr_sex[$user_info_applicant['gender']];
		//$user_info_applicant['nation_code'] = $attr_nationality_huaan[$user_info_applicant['nation_code']];
		
	}
	else
	{
		$user_info_applicant['group_certificates_type_str'] = $attr_group_certificates_type[$user_info_applicant['group_certificates_type']];
		//$user_info_applicant['nation_code'] = $attr_nationality_huaan[$user_info_applicant['nation_code']];
	
	}
	

	
	///////////////////////////////////////////////////////////////////////
	$list_subject_product  = $list_subject[0]['list_subject_product'] ;
	$list_subject_insurant = $list_subject[0]['list_subject_insurant'] ;//被保险人信息
	
	$insurant_info = $list_subject_insurant[0];
	
	ss_log("before insurant_info certificates_type: ".$insurant_info[certificates_type]);
	
	$insurant_info['certificates_type_str'] = $attr_certificates_type[$insurant_info['certificates_type']];
	ss_log("after insurant_info certificates_type: ".$insurant_info['certificates_type_str']);
	
	$insurant_info['gender_str'] = $attr_sex[$insurant_info['gender']];
	
	$relationship_with_insured_str = $attr_relationship_with_insured[$relationship_with_insured];	
	
	ss_log("insurer_code: ".$insurer_code);
	ss_log("attribute_type: ".$attribute_type);
	
	//added by zhangxi, 20150123, 增加uid的传递,活动页面通过uid授权
	$uid = $_REQUEST['uid'];
	global $INSURANCE_PAC;
	if(in_array($insurer_code, $INSURANCE_PAC))
	{
		if ($attribute_type == 'lvyou'
			|| $attribute_type == 'lvyouhuodong')
		{
			include_once template("cp_product_buy_pingan_lvyou_view");
		}
		elseif ($attribute_type == 'jingwailvyou'
			|| $attribute_type == 'jingwailvyouhuodong')
		{
			include_once template("cp_product_buy_pingan_lvyou_jingwai_view");
		}
		elseif ($attribute_type == 'Y502')
		{
			//added by zhangxi, 20141212, 看502产品需要其他哪些参数
			//$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy_pingan.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
			//include_once ($ROOT_PATH_. '../oop/classes/PACGroupInsurance.php');
			
			include_once ('../oop/classes/PACGroupInsurance.php');
			//echo $ROOT_PATH_. 'oop/classes/PACGroupInsurance.php';
			
			$duty_price_ids = $_REQUEST['duty_price_ids'];
			$list_ids = explode('_',$duty_price_ids);
			//产品，用户投保的责任 ，都需要列出来，y502产品比较复杂，这里单独使用变量来存储
			$pacGroupInsurance = new PACGroupInsurance();
			foreach($list_ids as $key=>$value)
			{
				$list_product_duty_price[] = $pacGroupInsurance->get_user_choosen_product_price_list($value);
			}
			//added by zhangxi, 20150807, 增加变量用来区分y502的标准产品和非标准产品
			$policy_arr['product_flag'] = $policy_arr['cast_policy_no']; 
			
			
			 
			//被保险人列表信息获取就通过$list_subject_insurant
			$gid = $_POST['gid'];
			$product['attribute_type'] = "Y502";
			
			$cmpy_attr=$user_info_applicant['company_attribute'];
			$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy_process.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
			include_once ($ROOT_PATH_. 'baoxian/source/function_baoxian_pingan.php');
			global $attr_company_attribute_type_pingan;
			$user_info_applicant['company_attribute'] = $attr_company_attribute_type_pingan[$cmpy_attr];
				
			include_once template("cp_product_buy_pingan_y502_view");
		}
		elseif($attribute_type == 'jiacaizonghe')
		{
			global $attr_buildingStructure_type_pingan_property;
			
			include_once ('../oop/classes/PACGroupInsurance.php');
			$duty_price_ids = $_REQUEST['duty_price_ids'];
			$new_duty_price_ids = str_replace('_',',',$duty_price_ids);
			$pacGroupInsurance = new PACGroupInsurance();
			$list_product_duty_price = $pacGroupInsurance->get_user_choosen_product_price_list($new_duty_price_ids);
			$user_info_applicant['house_type'] = $attr_buildingStructure_type_pingan_property[$user_info_applicant['house_type']];
			//echo "<pre>";
			//echo var_export($insurant_info);
			include_once template("cp_product_buy_pingan_jiacaizonghe_view");
		}
		elseif($attribute_type == 'jiajuzonghe')
		{
			global $attr_buildingStructure_type_pingan_property;
			//echo "<pre>";
			//echo var_export($insurant_info);
			include_once ('../oop/classes/PACGroupInsurance.php');
			$duty_price_ids = $_REQUEST['duty_price_ids'];
			$new_duty_price_ids = str_replace('_',',',$duty_price_ids);
			$pacGroupInsurance = new PACGroupInsurance();
			$list_product_duty_price = $pacGroupInsurance->get_user_choosen_product_price_list($new_duty_price_ids);
			$user_info_applicant['house_type'] = $attr_buildingStructure_type_pingan_property[$user_info_applicant['house_type']];
			include_once template("cp_product_buy_pingan_jiajuzonghe_view");
		}
		elseif($attribute_type == 'zhanghuzijin')
		{
			//echo "<pre>";
			//echo var_export($insurant_info);
			include_once ('../oop/classes/PACGroupInsurance.php');
			$duty_price_ids = $_REQUEST['duty_price_ids'];
			$new_duty_price_ids = str_replace('_',',',$duty_price_ids);
			$pacGroupInsurance = new PACGroupInsurance();
			$list_product_duty_price = $pacGroupInsurance->get_user_choosen_product_price_list($new_duty_price_ids);
			include_once template("cp_product_buy_pingan_zhanghuzijin_view");
		}
		else
		{
			include_once template("cp_product_buy_pingan_yiwai_view");
		}
	} 
	elseif ($insurer_code == "TBC01")
	{
		//start add by wangcya, 20150320,航空意外险
		if ($attribute_type == 'hangyiwai')
		{
			$wheresql = "policy_id='$policy_id'";
			$sql = "SELECT * FROM ".tname('insurance_policy_taipingyang_yiwai_otherinfo_hangkong')." WHERE $wheresql LIMIT 1";
			//echo $sql;
			$query = $_SGLOBAL['db']->query($sql);
			$policy_hangyiwai_info = $_SGLOBAL['db']->fetch_array($query);
				
			if($policy_hangyiwai_info)
			{
				$policy_arr["flightNo"] = $policy_hangyiwai_info['flightNo'];//
				$policy_arr["flightFrom"] = $policy_hangyiwai_info['flightFrom'];//
				$policy_arr["takeoffDate"] = $policy_hangyiwai_info['takeoffDate'];//
				
			}			
				
			include_once template("cp_product_buy_taipingyang_hangyiwai_view");
		}
		//end add by wangcya, 20150320,航空意外险
		else
		{
			include_once template("cp_product_buy_taipingyang_yiwai_view");
		}
				
	}
	//太平洋天津财险
	elseif ($insurer_code == $ARR_INS_COMPANY_NAME['str_cpic_tj_property'])
	{
		if ($attribute_type == 'tj_property')
		{
			include_once template("cp_product_buy_taipingyang_tj_jiatingcaichan_view");
		}
		elseif($attribute_type == 'tj_project')
		{
			
		}
		else
		{
			include_once template("cp_product_buy_taipingyang_tj_product_view");
		}
		
	}
	elseif ($insurer_code == "HTS")
	{
		include_once template("cp_product_buy_huatai_chuguo_yiwai_view");
	}
	//added by zhangxi, 20141229, 增加华安的支持
	elseif ($insurer_code == "SINO")
	{
		//var_dump($list_product_duty_price);
		
		//echo "<pre>";
		//var_dump($list_subject_insurant);
		global $attr_assured_type_huaan;
		global $attr_applicant_type_huaan;
		global $attr_be_assured;
		global $attr_applicant_gender_huaan;
		if($attribute_type == 'project')
		{
			//mod by zhangxi, 投保人国籍放到具体的险种中处理
			$user_info_applicant['nation_code'] = $attr_nationality_huaan[$user_info_applicant['nation_code']];
			$list_product_duty_price = get_duty_price_by_id($_REQUEST["duty_price_ids"]);
			//added by zhangxi, 20150107
			$wheresql = "policy_id='$policy_id'";
			$sql = "SELECT * FROM ".tname('insurance_policy_huaan_project_info')." WHERE $wheresql LIMIT 1";
			//echo $sql;
			$query = $_SGLOBAL['db']->query($sql);
			$policy_huaan_project_info = $_SGLOBAL['db']->fetch_array($query);
			
			if($policy_huaan_project_info)
			{	
				$policy_arr["project_name"] = $policy_huaan_project_info['project_name'];//
				$policy_arr["project_price"] = $policy_huaan_project_info['project_price'];//
				$policy_arr["project_start_date"] = $policy_huaan_project_info['project_start_date'];//
				$policy_arr["project_end_date"] = $policy_huaan_project_info['project_end_date'];//
				$policy_arr["project_total_month"] = $policy_huaan_project_info['project_total_month'];//
				$policy_arr["project_content"] = $policy_huaan_project_info['project_content'];
				$policy_arr["project_location"] = $policy_huaan_project_info['project_location'];
				$policy_arr["zipcode"] = $policy_huaan_project_info['zipcode'];
				$policy_arr["project_type"] = $policy_huaan_project_info['project_type'];
				$policy_arr["province_name"] = $policy_huaan_project_info['province_name'];
				$policy_arr["city_name"] = $policy_huaan_project_info['city_name'];	
			}
			
			include_once template("cp_product_buy_huaan_project_view");
		}
		elseif($attribute_type == 'xuepingxian')
		{
			include_once template("cp_product_buy_huaan_xuepingxian_view");
		}
		
	}
	elseif ($insurer_code == "NCI")//新华人寿
	{
		global $attr_xinhua_nation;
		global $attr_xinhua_applicant_gender;
		global $attr_xinhua_certificates_type;
		global $attr_xinhua_relationship_with_applicant;
		global $attr_xinhua_relationship_benefit_with_applicant;
		if($attribute_type == 'shaoer')
		{
			
			//获取受益人信息
			if($policy_arr['beneficiary'] == 0)//不是指定法定受益人的情况下
			{
				$list_beneficiary = $insurant_info['list_beneficiary'];	
				//echo "<pre>";
				//var_dump($list_beneficiary);
				//受益人信息显示转换
				
				foreach($list_beneficiary as $key=>$val)
				{
					ss_log(__FUNCTION__.", cardType=".$val['cardType']);
					$list_beneficiary[$key]['nativePlace'] = $attr_xinhua_nation[$val['nativePlace']];
					$list_beneficiary[$key]['sex'] = $attr_xinhua_applicant_gender[$val['sex']];
					$list_beneficiary[$key]['cardType'] = $attr_xinhua_certificates_type[$val['cardType']];
					$list_beneficiary[$key]['certificates_code'] = $val['cardNo'];
					$list_beneficiary[$key]['benefitRelation'] = $attr_xinhua_relationship_benefit_with_applicant[$val['benefitRelation']];
					ss_log(__FUNCTION__.",after transfer, cardType=".$list_beneficiary[$key]['cardType']);
				}
				
			}
			$list_product_duty_price = xinhua_get_duty_price_by_id($_REQUEST["duty_price_ids"]);
			
			
			$wheresql = "policy_id='$policy_id'";
			$sql = "SELECT * FROM ".tname('insurance_policy_xinhua_renshou_additional_info')." WHERE $wheresql LIMIT 1";
			//echo $sql;
			$query = $_SGLOBAL['db']->query($sql);
			$policy_xinhua_additional_info = $_SGLOBAL['db']->fetch_array($query);
			
			if($policy_xinhua_additional_info)
			{	
				$policy_arr["partPayMode"] = $policy_xinhua_additional_info['partPayMode'];//
				$policy_arr["partPayBankCode"] = $policy_xinhua_additional_info['partPayBankCode'];//
				$policy_arr["partPayAcctNo"] = $policy_xinhua_additional_info['partPayAcctNo'];//
				$policy_arr["partPayAcctName"] = $policy_xinhua_additional_info['partPayAcctName'];//
				$policy_arr["serviceOrgCode"] = $policy_xinhua_additional_info['serviceOrgCode'];//
				$policy_arr["payPeriod"] = $policy_xinhua_additional_info['payPeriod'];
				$policy_arr["payPeriodUnit"] = $policy_xinhua_additional_info['payPeriodUnit'];
				$policy_arr["payMode"] = $policy_xinhua_additional_info['payMode'];
				$policy_arr["insPeriod"] = $policy_xinhua_additional_info['insPeriod'];
				$policy_arr["insPeriodUnit"] = $policy_xinhua_additional_info['insPeriodUnit'];

			}
			include_once ('../oop/classes/NCI_Insurance.php');
			$nciobj = new NCI_Insurance();
			//更新相关信息显示：
			$policy_arr["serviceOrgCode"] = $nciobj->get_service_name_by_code($policy_arr["serviceOrgCode"]);
			
    		//通过省代码获取省份名称
			$user_info_applicant['province_code'] = $nciobj->get_province_name_by_code($user_info_applicant['province_code']);
			$insurant_info['province_code'] = $nciobj->get_province_name_by_code($insurant_info['province_code']);
    		//通过市代码获取市名称
			$user_info_applicant['city_code'] = $nciobj->get_city_name_by_code($user_info_applicant['city_code']);
			$insurant_info['city_code'] = $nciobj->get_city_name_by_code($insurant_info['city_code']);
    		//通过县代码获取县名称
			$user_info_applicant['county_code'] = $nciobj->get_county_name_by_code($user_info_applicant['county_code']);
			$insurant_info['county_code'] = $nciobj->get_county_name_by_code($insurant_info['county_code']);
			
			$user_info_applicant['business_type'] = $nciobj->get_industry_name_by_code($user_info_applicant['business_type']);
			$insurant_info['business_type'] = $nciobj->get_industry_name_by_code($insurant_info['business_type']);
			
    		//通过职业代码获取职业名称
			$user_info_applicant['occupationClassCode'] = $nciobj->get_career_name_by_code($user_info_applicant['occupationClassCode']);
			$insurant_info['occupationClassCode'] = $nciobj->get_career_name_by_code($insurant_info['occupationClassCode']);
			
			$policy_arr["partPayBankCode"] = $nciobj->get_bank_name_by_code($policy_arr["partPayBankCode"]);
			
			//国籍
			
			$user_info_applicant['nation_code'] = $attr_xinhua_nation[$user_info_applicant['nation_code']];
			$insurant_info['nation_code'] = $attr_xinhua_nation[$insurant_info['nation_code']];
			//需要支付前先核保
			$flag_check_policy = 1;
			include_once template("cp_product_buy_xinhua_shaoer_view");
		}
		
	}
	elseif ($insurer_code == "CHINALIFE")//中国人寿
	{
		if($attribute_type == 'product')
		{
			include_once template("cp_product_buy_chinalife_product_view");
		}
		
	}
	elseif($insurer_code == "picclife")
	{
		global $attr_picclife_GovtIDTC;
		global $attr_picclife_RelationRoleCode;
		global $attr_picclife_Gender;
		if($attribute_type == 'product')
		{
			include_once template("cp_product_buy_picclife_product_view");
		}
		elseif($attribute_type == 'ijiankang'
		||$attribute_type == 'BWSJ'
		||$attribute_type == 'TTL'
		||$attribute_type == 'KLWYLQX')
		{
			//获取受益人信息
			if($policy_arr['beneficiary'] == 0)//不是指定法定受益人的情况下
			{
				$list_beneficiary = $insurant_info['list_beneficiary'];	
				//echo "<pre>";
				//var_dump($list_beneficiary);
				//受益人信息显示转换
				
				foreach($list_beneficiary as $key=>$val)
				{
					ss_log(__FUNCTION__.", cardType=".$val['cardType']);
					$list_beneficiary[$key]['sex'] = $attr_picclife_Gender[$val['sex']];
					$list_beneficiary[$key]['cardType'] = $attr_picclife_GovtIDTC[$val['cardType']];
					$list_beneficiary[$key]['certificates_code'] = $val['cardNo'];
					$list_beneficiary[$key]['benefitRelation'] = $attr_picclife_RelationRoleCode[$val['benefitRelation']];
					ss_log(__FUNCTION__.",after transfer, cardType=".$list_beneficiary[$key]['cardType']);
				}
				
			}
			include_once ('../baoxian/source/function_baoxian.php');
			$policy_arr = get_shouxian_additional_info($policy_id, $policy_arr);
			
			include_once ('../oop/classes/picclife_Insurance.php');
			$picclife_obj = new picclife_Insurance();
			$user_info_applicant['occupationClassCode'] = $picclife_obj->get_career_name_by_code($user_info_applicant['occupationClassCode']);
			$list_product_duty_price = picclife_get_duty_price_by_id($_REQUEST["duty_price_ids"]);
			//需要支付前先核保
			$flag_check_policy = 1;
			if($attribute_type == 'ijiankang')
			{
				include_once template("cp_product_buy_picclife_ijiankang_view");
			}
			elseif($attribute_type == 'BWSJ')
			{
				include_once template("cp_product_buy_picclife_bwsj_view");
			}
			elseif($attribute_type == 'TTL')
			{
				include_once template("cp_product_buy_picclife_ttl_view");
			}
			elseif($attribute_type == 'KLWYLQX')
			{
				include_once template("cp_product_buy_picclife_klwylqx_view");
			}
			
		}
	}
	elseif($insurer_code == "EPICC")
	{
		if($attribute_type == 'product')
		{
			include_once template("cp_product_buy_epicc_product_view");
		}
		else if($attribute_type == 'lvyou')
		{
			include_once template("cp_product_buy_epicc_lvyou_view");
		}
		else if($attribute_type == 'jingwailvyou')
		{
			$wheresql = "policy_id='$policy_id'";
			$sql = "SELECT * FROM ".tname('insurance_policy_epicc_otherinfo_lvyou')." WHERE $wheresql LIMIT 1";
			//echo $sql;
			$query = $_SGLOBAL['db']->query($sql);
			$policy_additional_info = $_SGLOBAL['db']->fetch_array($query);
			
			if($policy_additional_info)
			{	
				$policy_arr['destinationCountry'] = $policy_additional_info['destinationCountry'];//

			}
			include_once template("cp_product_buy_epicc_lvyou_view");
		}
	}
	elseif($insurer_code == "CPIC_CARGO")
	{
		$policy_arr = get_cpic_cargo_additional_info($policy_id, $policy_arr, $attribute_type);
		//转换显示
		global $arr_cpic_cargo_internal_insurance_type;
		global $arr_cpic_cargo_package_code;
		global $arr_cpic_cargo_internal_transport_type;
		if($attribute_type == 'internal_cargo')
		{
			$policy_arr["packcode"] = $arr_cpic_cargo_package_code[$policy_arr["packcode"]];
			$policy_arr["classtype"] = $arr_cpic_cargo_internal_insurance_type[$policy_arr["classtype"]];
			$policy_arr["kind"] = $arr_cpic_cargo_internal_transport_type[$policy_arr["kind"]];
			//货物类型
			include_once ('../oop/classes/CPIC_CARGO_Insurance.php');
			$obj = new CPIC_CARGO_Insurance();
			$policy_arr["itemcode"] = $obj->get_cargo_type_by_code($policy_arr["itemcode"]);
			
			include_once template("cp_product_buy_cpic_cargo_internal_view");
		}
		elseif($attribute_type == 'other_cargo')
		{
			include_once template("cp_product_buy_cpic_cargo_other_view");
		}
		
	}
	elseif($insurer_code == "sinosig")
	{
		include_once template("cp_product_buy_yangguang_product_view");
	}
	elseif ($insurer_code == "SSBQ")
	{
		global $attr_certificates_type_ssbq;
		//获取附件
		$all_attachment = bx_get_all_attachment($policy_id);
			
		//$temp = print_r($all_attachment,true);
		//ss_log(__FUNCTION__.",temp:".$temp);
		
		include_once template("cp_product_buy_susongbaoquan_view");
	}
	//include_once template("cp_product_buy_view");
	
	return;
}

//added by zhangxi, 20150115, 重新组织投保人，被保险人信息，
//用于投保信息填写确认页面的展示
function get_applicant_insuraned_info_bat($insurer_code, $attribute_type, $ret_policy_output_list)
{
	global $global_attr_obj_type, $_SGLOBAL;
	
	$attr_relationship_with_insured  = $global_attr_obj_type[$insurer_code]['attr_relationship_with_insured'];
	$attr_certificates_type = $global_attr_obj_type[$insurer_code]['attr_certificates_type'];
	$attr_sex  = $global_attr_obj_type[$insurer_code]['attr_sex'];
	$list_applicant_insuraned_info = array();
	foreach($ret_policy_output_list AS $key=>$value_policy)
	{
		
		$policy_arr = $value_policy['policy_arr'];
		$policy_id = $policy_arr['policy_id'];
		$relationship_with_insured 	= $policy_arr['relationship_with_insured'];
		$business_type = $policy_arr['business_type'];
		$applicant_insuraned_info = array();
		////////////////////////////////////////////////////////////////////////////////////////////////
		//投保人处理
		//投保人类型需要获取
		$user_info_applicant = $value_policy['user_info_applicant'];
		
		$applicant_insuraned_info['applicant_name'] = ($business_type==1) ? $user_info_applicant['fullname'] : $user_info_applicant['group_name'];
		$applicant_insuraned_info['applicant_englishname'] = $user_info_applicant['fullname_english'];
		$certificates_type =  ($business_type==1) ?$user_info_applicant['certificates_type'] : $user_info_applicant['group_certificates_type'];
		$applicant_insuraned_info['applicant_certificates_type'] =$attr_certificates_type[$certificates_type];
		$applicant_insuraned_info['applicant_certificates_code'] = ($business_type==1) ?$user_info_applicant['certificates_code'] : $user_info_applicant['group_certificates_code'];
		$applicant_insuraned_info['applicant_birthday'] = ($business_type==1) ?$user_info_applicant['birthday'] : "";
		$applicant_insuraned_info['applicant_gender'] = ($business_type==1) ?$attr_sex[$user_info_applicant['gender']] : "";
		$applicant_insuraned_info['applicant_mobilephone'] = $user_info_applicant['mobiletelephone'];
		$applicant_insuraned_info['applicant_email'] = $user_info_applicant['email'];
		
		//与被保险人关系
		
		$applicant_insuraned_info['relationship'] = $attr_relationship_with_insured[$relationship_with_insured];//需要转换一下
		$applicant_insuraned_info['applicant_type'] = ($business_type == 1)?"个人":"机构";//转换一下
		
		//被保险人处理
		$list_subject_temp 		   = $value_policy['list_subject'];//多层级一个链表
		$list_subject_insurant_temp = $list_subject_temp[0]['list_subject_insurant'] ;
		$insurant_info_temp = $list_subject_insurant_temp[0];
		
		$insurant_info_temp['certificates_type_str'] = $attr_certificates_type[$insurant_info_temp['certificates_type']];
		$insurant_info_temp['gender_str'] = $attr_sex[$insurant_info_temp['gender']];
		$applicant_insuraned_info['assured_name'] = $insurant_info_temp['fullname'];
		$applicant_insuraned_info['assured_englishname'] = $insurant_info_temp['fullname_english'];
		$applicant_insuraned_info['assured_certificates_type'] = $insurant_info_temp['certificates_type_str'];
		$applicant_insuraned_info['assured_certificates_code'] = $insurant_info_temp['certificates_code'];
		$applicant_insuraned_info['assured_birthday'] = $insurant_info_temp['birthday'];
		$applicant_insuraned_info['assured_gender'] = $insurant_info_temp['gender_str'];
		$applicant_insuraned_info['assured_mobilephone'] = $insurant_info_temp['mobiletelephone'];
		$applicant_insuraned_info['assured_email'] = $insurant_info_temp['email'];
		$applicant_insuraned_info['assured_school_name'] = $insurant_info_temp['school_name'];
		//echo "insurer code".$insurer_code;
		//旅游目的地，职业类型等差异信息处理
		if($insurer_code == "PAC"
			||$insurer_code == "PAC01"
			||$insurer_code == "PAC02"
			||$insurer_code == "PAC03")
		{
			//平安境外旅游
			//echo "attribute type".$attribute_type;
			if($attribute_type == "jingwailvyou")
			{
				//获取附加信息$policy_id
				$wheresql = "policy_id='$policy_id'";
				$sql = "SELECT * FROM ".tname('insurance_policy_pingan_otherinfo_jingwailvyou')." WHERE $wheresql LIMIT 1";
				$query = $_SGLOBAL['db']->query($sql);
				$policy_pingan_therinfo_jingwailvyou = $_SGLOBAL['db']->fetch_array($query);
				
				$applicant_insuraned_info['destination'] = $policy_pingan_therinfo_jingwailvyou['destinationCountry'];
				//echo "<pre>";
				//echo "tewst".$applicant_insuraned_info['destination'];
				//var_dump($applicant_insuraned_info);
			}
			//在这里增加平安其他产品
		}
		elseif($insurer_code == "HTS")
		{
				//获取附加信息$policy_id
				$wheresql = "policy_id='$policy_id'";
				$sql = "SELECT * FROM ".tname('insurance_policy_huatai_yiwai_otherinfo')." WHERE $wheresql LIMIT 1";
				$query = $_SGLOBAL['db']->query($sql);
				$policy_huatai_yiwai_otherinfo = $_SGLOBAL['db']->fetch_array($query);
				global $attr_purpose_huatai;
				if($policy_huatai_yiwai_otherinfo)
				{
						
					//$ret_policy_output['policy_arr']["period_code"] = $policy_huatai_yiwai_otherinfo['period_code'];//出行目的
					//$applicant_insuraned_info['purpose'] = $policy_huatai_yiwai_otherinfo['purpose'];//出行目的
					//$ret_policy_output['policy_arr']["destination_area"] = $policy_huatai_yiwai_otherinfo['destination_area'];//出行目的地
					$applicant_insuraned_info['destination'] = $policy_huatai_yiwai_otherinfo['destination_country'];//出行目的地
					$applicant_insuraned_info['visacity'] = $policy_huatai_yiwai_otherinfo['visacity'];//签证城市
					$applicant_insuraned_info['purpose'] = $attr_purpose_huatai[$policy_huatai_yiwai_otherinfo["purpose"]];
				}
			
		}
		
		
		$list_applicant_insuraned_info[] = $applicant_insuraned_info;
	}
	
	return $list_applicant_insuraned_info;
}
//start add by wangcya, for bug[193],能够支持多人批量投保
//comment by zhangxi, 20150114, y502会走到这个函数中来么？不会
function show_policy_info_view_mutil(
									$ret_policy_output_list , 
									$gid_in,
									$bat_post_policy
									 )
{
	global $_SGLOBAL , $global_attr_obj_type;
	global $ARR_INS_COMPANY_NAME;
	//print_r($ret_policy_output_list);
	ss_log("into function show_policy_info_view_mutil");
	
	
	$gid = $gid_in;
	/////////////////////////////////////////////////////////////////////////////
	$ret_policy_output = $ret_policy_output_list[0];
	
	$policy_arr 				= $ret_policy_output['policy_arr'];
	$user_info_applicant 		= $ret_policy_output['user_info_applicant'];
	$list_subject 				= $ret_policy_output['list_subject'];//多层级一个链表
	
	$relationship_with_insured 	= $policy_arr['relationship_with_insured'];
	/////////////////////////////////////////////////////////////////////////////
	$policy_id = $policy_arr['policy_id'];
	$business_type = $policy_arr['business_type'];//add by wangcya, 20150106
	/////////////////////////////////////////////////////////////////////////////
	$cart_insure_id = $policy_arr['cart_insure_id'];//add by wangcya, 20141225
	$wheresql = "rec_id='$cart_insure_id'";
	$sql = "SELECT * FROM ".tname('cart_insure')." WHERE $wheresql LIMIT 1";
	ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);
	$cart_insure_arr = $_SGLOBAL['db']->fetch_array($query);
	if($cart_insure_arr)
	{
		$total_price = $cart_insure_arr['total_price'];
	}
	
	ss_log("cart_insure , total_price: ".$total_price);
	////////////////////////////////////////////////////////////////////////////////////////
	
	
	//add yes123 2014-12-01 去掉价格后面的0
	$policy_arr['total_premium'] = sprintf("%01.2f",$policy_arr['total_premium']);
	/////////////////////////////////////////////////////////////
	$attribute_id = $policy_arr['attribute_id'];

	$wheresql = "attribute_id='$attribute_id'";
	$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$product_attribute_arr = $_SGLOBAL['db']->fetch_array($query);

	$insurer_code = $product_attribute_arr['insurer_code'];
	$attribute_type = $product_attribute_arr['attribute_type'];

	////////////////////////////////////////////////////////////////////
	$attr_certificates_type = $global_attr_obj_type[$insurer_code]['attr_certificates_type'];
	$attr_sex  = $global_attr_obj_type[$insurer_code]['attr_sex'];
	//与被保险人关系
	$attr_relationship_with_insured  = $global_attr_obj_type[$insurer_code]['attr_relationship_with_insured'];
	//机构证件类型
	$attr_group_certificates_type  = $global_attr_obj_type[$insurer_code]['attr_group_certificates_type'];
	//机构属性
	$attr_company_attribute_type  = $global_attr_obj_type[$insurer_code]['attr_company_attribute_type'];

	/////////////////////////////////////////////////////////////////////
	
	$product_arr = $product = $product_info = $list_subject[0]['list_subject_product'][0];//add by wangcya, 20141204
	$product_id = $product_info['product_id'];
	
	//comment by zhangxi, 20141211, 这里有问题了, 需要适应y502的情况
	ss_log("product_id: ".$product_id);
	ss_log("product_name: ".$product_info['product_name']);
	
	$product_duty_list = get_product_duty_list($product_id,"single");//在投保确认页展示的产品责任，现在责任和价格之间都是单个关系，以后再说。
	
	//print_r($product_duty_list);
	/////////////////////////////////////////////////////////////////////
	if($business_type == 1)//singel
	{
		ss_log("certificates_type: ".$user_info_applicant['certificates_type']);		
		$user_info_applicant['certificates_type_str'] = $attr_certificates_type[$user_info_applicant['certificates_type']];
		$user_info_applicant['gender_str'] = $attr_sex[$user_info_applicant['gender']];
	}
	else
	{
		ss_log("group_certificates_type: ".$user_info_applicant['group_certificates_type']);
		$user_info_applicant['group_certificates_type_str'] = $attr_group_certificates_type[$user_info_applicant['group_certificates_type']];
	}
	
	

	///////////////////////////////////////////////////////////////////////
	$list_subject_product  = $list_subject[0]['list_subject_product'] ;
	$relationship_with_insured_str = $attr_relationship_with_insured[$relationship_with_insured];
	
	/*
	$list_subject_insurant = $list_subject[0]['list_subject_insurant'] ;

	$insurant_info = $list_subject_insurant[0];

	$insurant_info[certificates_type] = $attr_certificates_type[$insurant_info[certificates_type]];
	$insurant_info[gender] = $attr_sex[$insurant_info[gender]];
   */

	////////////////////////////////////////////////////////////////////////////////////////////////////
	$list_subject_insurant = array();
	//$policy_ids = array();
	
	foreach($ret_policy_output_list AS $key=>$value_policy)
	{
		$policy_arr_temp 		  = $value_policy['policy_arr'];
		//$policy_ids[] = $policy_arr_temp['policy_id'];
		////////////////////////////////////////////////////////////////////////////////////////////////
		
		$list_subject_temp 		   = $value_policy['list_subject'];//多层级一个链表
		$list_subject_insurant_temp = $list_subject_temp[0]['list_subject_insurant'] ;
		
		$insurant_info_temp = $list_subject_insurant_temp[0];
		
		$insurant_info_temp['certificates_type_str'] = $attr_certificates_type[$insurant_info_temp['certificates_type']];
		$insurant_info_temp['gender_str'] = $attr_sex[$insurant_info_temp['gender']];
		
		$list_subject_insurant[] = $insurant_info_temp;
	}

	$attribute_type = $policy_arr['attribute_type'];
	//print_r($policy_ids);
	ss_log("show_policy_info_view_mutil , insurer_code: ".$insurer_code." attribute_type: ".$attribute_type);
	///////start add by wangcya, 20141224///////////////////////////////////////////////////////////////
	$list_applicant_insuraned_info = get_applicant_insuraned_info_bat($insurer_code, $attribute_type, $ret_policy_output_list);
	if ($insurer_code == "PAC"||
		$insurer_code == "PAC01"||
		$insurer_code == "PAC02"||
		$insurer_code == "PAC03"
		)
	{
		
		//echo "<pre>";
		//var_Dump($list_applicant_insuraned_info);
		if ($attribute_type == 'lvyou')
		{
			
			ss_log("into cp_product_buy_pingan_lvyou_view");
			include_once template("cp_product_buy_pingan_lvyou_view");
		}
		elseif ($attribute_type == 'jingwailvyou')
		{
			//added by zhangxi, 20150114, 先解决旅游的，然后慢慢将本函数调整过来
			//批量的显示，得有变量区分，投保人信息与被保险人信息展示
			//模板通过$bat_post_policy这个变量来是否是批量投保
			//遍历当前列表$ret_policy_output_list获取需要的信息展示就ok了
			//没必要再重复再这里处理, 浪费性能
			//echo "<pre>";
			//var_Dump($ret_policy_output_list);
			
			//echo "<pre>";
			//var_Dump($list_applicant_insuraned_info);
			ss_log(__FUNCTION__.":GET cp_product_buy_pingan_lvyou_jingwai_view.htm");
			include_once template("cp_product_buy_pingan_lvyou_jingwai_view");
		}
		else
		{
			include_once template("cp_product_buy_pingan_yiwai_view");
		}
	}
	elseif ($insurer_code == "TBC01")
	{
		include_once template("cp_product_buy_taipingyang_yiwai_view");
	}
	//added by zhangxi, 20150629, 太平洋天津财险
	elseif ($insurer_code == $ARR_INS_COMPANY_NAME['str_cpic_tj_property'])
	{
		include_once template("cp_product_buy_taipingyang_tj_product_view");
	}
	elseif ($insurer_code == "HTS")
	{
		include_once template("cp_product_buy_huatai_chuguo_yiwai_view");
	}
	elseif($insurer_code == "SINO")//华安批量模板，默认暂用学平险的
	{
		include_once template("cp_product_buy_huaan_xuepingxian_view");
	}
	elseif($insurer_code == "EPICC")//人保财险确认页面
	{
		include_once template("cp_product_buy_epicc_product_view");
	}
	elseif($insurer_code == "picclife")//人保人寿
	{
		include_once template("cp_product_buy_picclife_product_view");
	}
	///////end add by wangcya, 20141224///////////////////////////////////////////////////////////////

	//include_once template("cp_product_buy_view");

	return;
}
//end add by wangcya, for bug[193],能够支持多人批量投保
