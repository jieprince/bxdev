<?php
$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy_sinosig.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_. 'baoxian/source/function_baoxian_sinosig.php');
/*访问检查*/
function cp_buy_access_check_sinosig($product,$user_info)
{
	//return true;
	////////////////////////////////////////////////////////
	$product_code 	= $product['product_code'];
	
	
	return true;
}

/*阳光的简单产品处理*/
function process_product_sinosig_simple($product,$POST)
{
	//end
	$ret_policy_input  = input_check_sinosig($POST);
	
	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数
		
	return $ret_policy_output;
}


function process_product_sinosig_plan($product,$POST)
{
	$ret_policy_input  = input_check_sinosig($POST);
	
	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数
		
	return $ret_policy_output;
}


function process_product_sinosig_lvyou($product,$POST)
{
	$ret_policy_input  = input_check_sinosig($POST);

	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数


	return $ret_policy_output;
}


function process_product_sinosig_lvyou_jingwai($product,$POST)
{
	global $_SGLOBAL;

	////////////////////////////////////////////////////////////////////////////////
	ss_log("into fucntion process_product_pingan_lvyou_jingwai");
	
	$retattr = input_check_sinosig_other_jingwailvyou($POST);
	$global_info = $retattr['global_info'];
	///////////////////////////////////////////////////////////////////////////////////
	
	$product_code= $product['product_code'];
	ss_log("product_code: ".$product_code);
	
	$period_code = $global_info['period_code'];
	ss_log("period_code: ".$period_code);
	
	$list_product_code_kuailelvyou = array("00820","00821","00822","00826","00881");//平安快乐旅程Ⅲ
	if(in_array($product_code, $list_product_code_kuailelvyou))
	{
		
		if($period_code == "12" )//年度（每次出境时间不超过90天）
		{
			$period_product_code = array(
					'00820'=>'00823',
					'00821'=>'00824',
					'00822'=>'00825',
					'00826'=>'00827',
					'00881'=>'00883'
						
			);
	
			$product['product_code'] = $period_product_code[$product['product_code']];
			ss_log("aftter transfer,product_code: ".$product['product_code']);
		}
	
		//根据周期编码控制天数
		$attr_period = array(
				"1"=>"7",
				"2"=>"10",
				"3"=>"15",
				"4"=>"20",
				"5"=>"24",
				"6"=>"28",
				"7"=>"45",
				"8"=>"60",
				"9"=>"75",
				"10"=>"90",
				"11"=>"183",
				"12"=>"365"
		);
	}
	else
	{
		if($period_code == "8" )//年度（每次出境时间不超过90天）
		{
			$period_product_code = array(
					'01009'=>'01023',
					'01010'=>'01024',
					'01011'=>'01025',
					'01012'=>'01026',
					'01013'=>'01027',
					'01014'=>'01028',
					'01015'=>'01029'
			);
				
			$product['product_code'] = $period_product_code[$product['product_code']];
			ss_log("aftter transfer,product_code: ".$product['product_code']);
		}
		elseif($period_code == "9" )//一年（无90天限制）
		{
				
			$period_product_code = array(
					'01009'=>'01016',
					'01010'=>'01017',
					'01011'=>'01018',
					'01012'=>'01019',
					'01013'=>'01020',
					'01014'=>'01021',
					'01015'=>'01022'
			);
				
			$product['product_code'] = $period_product_code[$product['product_code']];
			ss_log("aftter transfer,product_code: ".$product['product_code']);
		}
	
		//根据周期编码控制天数
		$attr_period = array(
				"1"=>"10",
				"2"=>"15",
				"3"=>"20",
				"4"=>"30",
				"5"=>"60",
				"6"=>"90",
				"7"=>"180",
				"8"=>"365",
				"9"=>"365"
		);
	}
	
	$apply_day = $attr_period[$period_code];
	ss_log("apply_day: ".$apply_day);
	///////////////////////////////////////////////////////////////////////////////////
	$POST['apply_day'] = $apply_day;//add by wangcya , 20150110
	
	$ret_policy_input  = input_check_sinosig($POST);
	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数
	
	$policy_arr = $ret_policy_output['policy_arr'];
	$policy_id  = $policy_arr['policy_id'];
	
	/////////////附加信息/////////////////////////////////////////////////////////////////////////////

		
	///////////////////////////////////////////////////////////////////////////////////
	ss_log("准备插入平安境境外旅游的附加信息到数据库，policy_id: ".$policy_id);
	
	$attr_pingan_other_info = array(
			"policy_id"=>$policy_id,
			'period_code'=>$global_info['period_code'],
			"outgoingPurpose"=>$global_info['outgoingPurpose'],//出行目的
			"destinationCountry"=>$global_info['destinationCountry'],//出行目的地
			"schoolOrCompany"=>$global_info['schoolOrCompany'],
	        );
	
	inserttable("insurance_policy_pingan_otherinfo_jingwailvyou", $attr_pingan_other_info);
	///////////////////////////////////////////////////////////////////////////////////
	$wheresql = "policy_id='$policy_id'";
	$sql = "SELECT * FROM ".tname('insurance_policy_pingan_otherinfo_jingwailvyou')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$policy_pingan_therinfo_jingwailvyou = $_SGLOBAL['db']->fetch_array($query);
	
	if($policy_pingan_therinfo_jingwailvyou)
	{
		$ret_policy_output['policy_arr']["period_code"] = $policy_pingan_therinfo_jingwailvyou['outgoingPurpose'];//出行目的
		$ret_policy_output['policy_arr']["purpose"] = $policy_pingan_therinfo_jingwailvyou['destinationCountry'];//出行目的
		$ret_policy_output['policy_arr']["destination_area"] = $policy_pingan_therinfo_jingwailvyou['schoolOrCompany'];//出行目的地
	}
	
	return $ret_policy_output;
}



function process_product_sinosig($product,$POST)
{
	$attribute_type = $product['attribute_type'];
	$attribute_type = empty($attribute_type)?"product":$attribute_type;

	if($attribute_type == "product")//最简单的产品，下面组合参数
	{
		$ret_policy_output = process_product_sinosig_simple($product,$POST);
	
	}
	elseif($attribute_type =="plan")
	{
		$ret_policy_output = process_product_sinosig_plan($product,$POST);
	}
	elseif($attribute_type =="lvyou"
			||$attribute_type =="lvyouhuodong")//comment by zhangxi, 20150123,春节微信营销
	{
		$ret_policy_output = process_product_sinosig_lvyou($product,$POST);
	}
	elseif($attribute_type =="jingwailvyou"
			||$attribute_type =="jingwailvyouhuodong")//comment by zhangxi, 20150123,春节微信营销
	{
		$ret_policy_output = process_product_sinosig_lvyou_jingwai($product,$POST);
	}
	else
	{
		$ret_policy_output = process_product_sinosig_simple($product,$POST);
	}
	
	return $ret_policy_output;
}


//阳光批量投保批量信息处理
function input_check_sinosig_bat($POST)
{
	global $_SGLOBAL,$attr_certificates_type_pingan_single_unify,$attr_certificates_type_pingan_group_unify,$attr_sex_pingan_unify;
	/////////////////////////////////////////////////////////////////////////////////////////////////////

	ss_log("into function input_check_pingan_bat");
	$global_info = array();

	$agent_uid = $_SGLOBAL['supe_uid'];//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	//////////////////有效性检查//////////////////////////////////////////////////////
	if(	!isset($POST['applyNum'])||
		!isset($POST['totalModalPremium_single'])||
		!isset($POST['totalModalPremium'])||
			!isset($POST['startDate'])||
			!isset($POST['endDate'])||
			!isset($POST['applicant_group_name'])||
			!isset($POST['applicant_group_certificates_type'])||
			!isset($POST['applicant_group_certificates_code'])||
			!isset($POST['applicant_email'])||
			!isset($POST['applicant_mobilephone'])||
			!isset($POST['businessType'])||
			!isset($POST['data_user_info'])
			//!isset($POST['applicant_birthday'])||
			//!isset($POST['applicant_sex'])||
			//!isset($POST['relationshipWithInsured'])
	)
	{
		showmessage("您输入的投保信息不完整哦！");
		exit(0);
	}

	//与被保险人之间的关系类型判断
	//$POST['relationshipWithInsured']，这个参数还需要判断吗？

	////////////////////////////////////////////////////////////
	//这个地方的投保份数，界面是一份，但实际值应该等于投保人数
	ss_log("in input_check_pingan_bat, post applyNum: ".$POST['applyNum']);
	ss_log("in input_check_pingan_bat, post totalModalPremium: ".$POST['totalModalPremium']);
	ss_log("in input_check_pingan_bat, post totalModalPremium_single: ".$POST['totalModalPremium_single']);

	$global_info['applyNum'] = intval($POST['applyNum']);//份数
	//comment by zhangxi, 2150116, 批量投保少收保费的问题
	//$global_info['totalModalPremium'] =$POST['totalModalPremium_single'];
	$global_info['totalModalPremium'] = $global_info['applyNum']*$POST['totalModalPremium_single'];// $POST['totalModalPremium'];//总保费,应该从前端计算得到的
	

	$global_info['beneficiary'] = $POST['beneficiary'];//受益人
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期

	///////////////组合投保人信息//////////////////////////////////////////////////
	$user_info_applicant = array();

	$businessType = empty($POST['businessType'])?1:trim($POST['businessType']);

	$bat_post_policy = empty($POST['bat_post_policy'])?1:trim($POST['bat_post_policy']);
	if($bat_post_policy != 1)
	{
		//input check error
		showmessage("投保方式不正确！");
		exit(0);
	}

	if($businessType ==1)//个人
	{
		$user_info_applicant['certificates_type'] = $POST['applicant_certificates_type'];
		$user_info_applicant['certificates_code'] = $POST['applicant_certificates_code'];
		$user_info_applicant['fullname'] = $POST['applicant_fullname'];
		$user_info_applicant['gender'] = $POST['applicant_sex'];
		$user_info_applicant['birthday'] = $POST['applicant_birthday'];
		$user_info_applicant['mobiletelephone'] = $POST['applicant_mobilephone'];
		$user_info_applicant['email'] = $POST['applicant_email'];
		
		//start add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		$user_info_applicant['certificates_type_unify'] = $attr_certificates_type_pingan_single_unify[$user_info_applicant['certificates_type']];
		$user_info_applicant['gender_unify'] = $attr_sex_pingan_unify[$user_info_applicant['gender']];
		$user_info_applicant['type'] = 1;//0,被保险人，1投保人,2两者身份相同
		$user_info_applicant['agent_uid'] = $agent_uid;
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	}
	else
	{
		//团体投保人信息获取
		$user_info_applicant['group_name'] = $POST['applicant_group_name'];
		$user_info_applicant['group_certificates_type'] = $POST['applicant_group_certificates_type'];
		$user_info_applicant['group_certificates_code'] = $POST['applicant_group_certificates_code'];
	
		$user_info_applicant['group_abbr'] = $POST['group_abbr'];//这个没有
		$user_info_applicant['company_attribute'] = $POST['company_attribute'];//这个没有
		$user_info_applicant['address'] = $POST['address'];//这个没有
		$user_info_applicant['telephone'] = $POST['telephone'];//这个没有
		$user_info_applicant['mobiletelephone'] = $POST['applicant_group_mobilephone'];
		$user_info_applicant['email'] = $POST['applicant_group_email'];
	
		ss_log(__FILE__.":group name:".$user_info_applicant['group_name']);
	
		//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		$user_info_applicant['group_certificates_type_unify'] = $attr_certificates_type_pingan_group_unify[$user_info_applicant['group_certificates_type']];
		$user_info_applicant['agent_uid'] = $agent_uid;
	}
	
	//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一

	/////////////////////////////////////////////////////////
	//团体投保，投保人和被保险人之间是什么关系呢? 应该是"其他"的关系
	$relationshipWithInsured = 6;//其他
	//主险的产品ID
	$product_id = intval($POST['product_id']);
	//获取用户选定的投保险种，如果主险没有选，则异常


	//////////一个subjectInfo内多个产品，多个被保险人//
	$subjectInfo1 = array();


	//循环获取所有的被保险人信息
	$data_user = stripslashes($POST['data_user_info']);
	//$data_user = '{"result_code":"0","result_msg":"success","data":[{"fullname":"王  健","certificates_type":"01","certificates_code":"211103197703030617","career_type":"0001001","gender":"M","birthday":"1977-03-03","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"方  薇","certificates_type":"01","certificates_code":"110108196805184820","career_type":"0001001","gender":"F","birthday":"1968-05-18","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"范本勇","certificates_type":"01","certificates_code":"320682198101040473","career_type":"0001001","gender":"M","birthday":"1981-01-04","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"郭慧芬","certificates_type":"01","certificates_code":"230106196411062067","career_type":"2001003","gender":"F","birthday":"1964-11-06","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"田志勇","certificates_type":"01","certificates_code":"630121196506291514","career_type":"2001003","gender":"M","birthday":"1965-06-29","email":"","mobiletelephone":"","level_num":"2"}]}';
	if(empty($data_user))
	{
		showmessage("获取被保险人信息失败！");
		exit(0);
	}

	ss_log("get post data:".$POST['data_user_info']);
	//include_once(S_ROOT.'/../includes/shopex_json.php');

	//ss_log("data_user2:".$data_user);
	//$data_user = '{"result_code":"0","result_msg":"success","data":[{"fullname":"王  健","certificates_type":"01","certificates_code":"211103197703030617","career_type":"0001001","gender":"M","birthday":"1977-03-03","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"方  薇","certificates_type":"01","certificates_code":"110108196805184820","career_type":"0001001","gender":"F","birthday":"1968-05-18","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"范本勇","certificates_type":"01","certificates_code":"320682198101040473","career_type":"0001001","gender":"M","birthday":"1981-01-04","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"郭慧芬","certificates_type":"01","certificates_code":"230106196411062067","career_type":"2001003","gender":"F","birthday":"1964-11-06","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"田志勇","certificates_type":"01","certificates_code":"630121196506291514","career_type":"2001003","gender":"M","birthday":"1965-06-29","email":"","mobiletelephone":"","level_num":"2"}]}';
	//$data_user = "{\"result_code\":\"0\",\"result_msg\":\"success\",\"data\":[{\"fullname\":\"王  健\",\"certificates_type\":\"01\",\"certificates_code\":\"211103197703030617\",\"career_type\":\"0001001\",\"gender\":\"M\",\"birthday\":\"1977-03-03\",\"email\":\"\",\"mobiletelephone\":\"\",\"level_num\":\"2\"},{\"fullname\":\"方  薇\",\"certificates_type\":\"01\",\"certificates_code\":\"110108196805184820\",\"career_type\":\"0001001\",\"gender\":\"F\",\"birthday\":\"1968-05-18\",\"email\":\"\",\"mobiletelephone\":\"\",\"level_num\":\"2\"},{\"fullname\":\"范本勇\",\"certificates_type\":\"01\",\"certificates_code\":\"320682198101040473\",\"career_type\":\"0001001\",\"gender\":\"M\",\"birthday\":\"1981-01-04\",\"email\":\"\",\"mobiletelephone\":\"\",\"level_num\":\"2\"},{\"fullname\":\"郭慧芬\",\"certificates_type\":\"01\",\"certificates_code\":\"230106196411062067\",\"career_type\":\"2001003\",\"gender\":\"F\",\"birthday\":\"1964-11-06\",\"email\":\"\",\"mobiletelephone\":\"\",\"level_num\":\"2\"},{\"fullname\":\"田志勇\",\"certificates_type\":\"01\",\"certificates_code\":\"630121196506291514\",\"career_type\":\"2001003\",\"gender\":\"M\",\"birthday\":\"1965-06-29\",\"email\":\"\",\"mobiletelephone\":\"\",\"level_num\":\"2\"}]}";
	$list_user = json_decode($data_user, true);
	ss_log("json decode data:".$list_user);

	//获取被保险人信息，这里需要考虑多个被保险人信息
	$real_apply_num = count($list_user);
	if($real_apply_num < 5 )
	{
		showmessage("最少投保人数为5人！".$real_apply_num);
		exit(0);
	}

	if($real_apply_num > 3000 )
	{
		showmessage("最大投保人数为3000人！".$real_apply_num);
		exit(0);
	}

	//$global_info['applyNum'] = $real_apply_num;
	ss_log("in input_check_pingan_bat, global_info applyNum: ".$real_apply_num);

	/////////////////////////////////////////////////////////////////////

	$policy_attr = array();
	$assured_num =0;
	//根据被保险人的个数形成多个保单数据
	foreach($list_user as $k=>$val)
	{
		$assured_info = array();

		$assured_info['certificates_type'] = trim($val['certificates_type']);
		$assured_info['certificates_code'] = trim($val['certificates_code']);
		$assured_info['birthday'] = trim($val['birthday']);
		$assured_info['fullname']  = trim($val['fullname']);
		$assured_info['gender']  = trim($val['gender']);
		$assured_info['mobiletelephone'] =  trim($val['mobiletelephone']);
		$assured_info['email'] =  trim($val['email']);
		$assured_info['occupationClassCode'] =  trim($val['career_type']);

		//add by wangcya, 20141219 ,不同厂商的证件号码和性别
		$assured_info['certificates_type_unify'] = $attr_certificates_type_pingan_single_unify[$assured_info['certificates_type']];
		$assured_info['gender_unify'] = $attr_sex_pingan_unify[$assured_info['gender']];
		$assured_info['type'] = 0;//0,被保险人，1投保人,2两者身份相同
		$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别
		//ss_log("zhx,name=".$assured_info['fullname']);

		$assured_num++;

		////////////////////////////////////////////////////////////////////////////////
		$subjectInfo1 = array();

		$list_product_id1 = array();
		$list_product_id1[] = $product_id;//
		ss_log("bat check input, post product_id: ".$product_id);
		$subjectInfo1['list_product_id'] = $list_product_id1;

		$list_assured1 = array();
		$list_assured1[] = $assured_info;
		$subjectInfo1['list_assured'] = $list_assured1;


		////////////////////////////////////////////////////////////
		$list_subjectinfo = array();
		$list_subjectinfo[] = $subjectInfo1;
		////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////
		//showmessage("test code INPUT CHECK SUCCESStestend");
		//exit(0);
		
		$retattr = array(
				"global_info"=>$global_info,
				"businessType"=>$businessType,
				"relationshipWithInsured"=>$relationshipWithInsured,
				"user_info_applicant"=>$user_info_applicant,
				"list_subjectinfo"=>$list_subjectinfo
		);

		$policy_attr[] = $retattr;
	}//foreach
	
	
	//comment by zhangxi, 20150116, 价格校验
	//前端传过来的计算出来的总保费
	$custom_visitor_premium = $POST['totalModalPremium'];
	//后端计算的总保费
	$calculated_premium = $POST['totalModalPremium_single']*$POST['applyNum']*$assured_num;
	$calculated_premium = round($calculated_premium,2);
	$custom_visitor_premium = round($custom_visitor_premium,2);
	if($calculated_premium != $custom_visitor_premium)
	{
		showmessage("保费校验错误！");
		exit(0);
	}
	
	

	///////////////////////////////////////////////////////////
	return $policy_attr;

}

///////////////////////////////////////////////////////////////////////////////
function gen_cp_buy_view_info_sinosig($product,$POST)
{

	$config_data = assign_template();	
	
	$product_arr= $product;
	//////////////////////////////////////////////////////
	//$_TPL['css'] = 'cp_product_buy_pingan';
	
	$attribute_type = $product['attribute_type'];
	
	$attribute_type = empty($attribute_type)?"product":$attribute_type;
	
	global $attr_certificates_type_sinosig;
	global $attr_relationship_applicant_with_insured_sinosig;
	
	
	ss_log(__FUNCTION__.", product_code: ".$product['product_code']);
	ss_log(__FUNCTION__.", attribute_type: ".$attribute_type);
	///////////////////////////////////////////////////////
	$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
	
	//mod by zhangxi, 20150312, 活动相关代码
	$uid = $_REQUEST['uid'];
	$gid = $_GET['gid'];

	//comment by zhangxi, 20150115, 平安的A, B,C 卡，少儿综合计划都走这里
	if( $attribute_type =='product')//标准产品，例如卡类，价格不会变
	{
		ss_log("process attribute_type: product");
		
		$businessType = 1;
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		$prodcutMarkPrice = $product['premium'];
		//start modify by waqngcya, 20150506,适应那种标准产品但是保险期间不是一年的产品
		$period_day  = empty($product['period'])?365:intval($product['period']);
		ss_log("period_day: ".$period_day);
		
		$period_uint = empty($product['period_uint'])?"day":$product['period_uint'];//add by wangcya, 20150506,支持多种类型的保险期限
		//end modify by waqngcya, 20150506,适应那种标准产品但是保险期间不是一年的产品
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品价格等于单价

		include_once template("cp_product_buy_yangguang_product");
	}
	elseif( $attribute_type == 'lvyou')//境内旅游，价格根据投保期间变化
	{}
	elseif($attribute_type == 'jingwailvyouhuodong' )
	{}
	elseif( $attribute_type == 'jingwailvyou')//境外旅游，价格有多种因素影响
	{}
	
	else
	{  
		ss_log("process attribute_type: other");
		//这个分支是为了保证兼容性，不影响原有的没有可虑到的可能流程
		
		$businessType = 1;
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		
		$prodcutMarkPrice = $product[premium];
		$period_day = 365;//一年的
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价
		include_once template("cp_product_buy_yangguang_product");
	}
	
}