<?php

define('S_ROOT1', dirname(__FILE__).DIRECTORY_SEPARATOR);

include_once(S_ROOT1.'../common.php');

//ss_log(" in function_baoxian, common php path: ".S_ROOT1.'../common.php');
//include_once(S_ROOT.'/source/my_const.php');
//include_once(S_ROOT.'/source/function_common.php');
//mod by zhangxi, 20150709, 增加平安个财险种代码PAC04 平安大连Y502--PAC05
$INSURANCE_PAC = array("PAC","PAC01","PAC02","PAC03", "PAC04", "PAC05");

$G_WORDS_HUODONG="huodong";



$product_additional_attr_period_uint = array("year"=>"年","month"=>"个月","day"=>"天");
$attr_status = array(
		"saved"=>"已保存",
		"payed"=>"待出单",
		"insured"=>"已投保",
		"canceled"=>"已注销",
		"surrender"=>"已退保"
);

$attr_business_type = array("1"=>"个人",
		"2"=>"团体",
);


/////////////////////////////////////////////////////////////////////////////////////
$attr_obj_type_pingan = array(	"attr_relationship_with_insured"=>$attr_relationship_with_insured_pingan,
							  	"attr_certificates_type"=>$attr_certificates_type_pingan,
		                       	"attr_sex"=>$attr_sex_pingan,
		                        "attr_group_certificates_type"=>$attr_group_certificates_type_pingan,
		                        "attr_company_attribute_type"=>$attr_company_attribute_type_pingan,
		                        //added by zhangxi, 20150117,都统一起来
		                        "attr_certificates_type_single_unify"=>$attr_certificates_type_pingan_single_unify,  
		                        "attr_sex_unify"=>$attr_sex_pingan_unify,
		                        "attr_certificates_type_group_unify"=>$attr_certificates_type_pingan_group_unify
		                     );

$attr_obj_type_taipingyang = array(	"attr_relationship_with_insured"=>$attr_relationship_with_insured_taipingyang,
								"attr_certificates_type"=>$attr_certificates_type_taipingyang,
								"attr_sex"=>$attr_sex_taipingyang,
								"attr_group_certificates_type"=>$attr_group_certificates_type_taipingyang,
								"attr_company_attribute_type"=>$attr_company_attribute_type_taipingyang,
								//added by zhangxi, 20150117,都统一起来
		                        "attr_certificates_type_single_unify"=>$attr_certificates_type_pingan_single_unify,  
		                        "attr_sex_unify"=>$attr_sex_pingan_unify,
		                        "attr_certificates_type_group_unify"=>$attr_certificates_type_pingan_group_unify
								);
//added by zhangxi, 20150626, 太平洋天津财险								
$attr_obj_type_cpic_tj_property = array(	"attr_relationship_with_insured"=>$attr_relationship_with_insured_cpic_tj_property,
								"attr_certificates_type"=>$attr_certificates_type_cpic_tj_property,
								"attr_sex"=>$attr_sex_cpic_tj_property,
								"attr_group_certificates_type"=>$attr_group_certificates_type_cpic_tj_property,
								"attr_company_attribute_type"=>$attr_company_attribute_type_taipingyang,
								//added by zhangxi, 20150117,都统一起来
		                        "attr_certificates_type_single_unify"=>$attr_certificates_type_cpic_tj_property_single_unify,  
		                        "attr_sex_unify"=>$attr_sex_cpic_tj_property_unify,
		                        "attr_certificates_type_group_unify"=>$attr_certificates_type_cpic_tj_property_group_unify
								);
																  
$attr_obj_type_huatai = array(	"attr_relationship_with_insured"=>$attr_relationship_with_insured_huatai,
								"attr_certificates_type"=>$attr_certificates_type_huatai,
								"attr_sex"=>$attr_sex_huatai,
								"attr_group_certificates_type"=>$attr_group_certificates_type_huatai,
								"attr_company_attribute_type"=>$attr_company_attribute_type_huatai,
								//added by zhangxi, 20150117,都统一起来
		                        "attr_certificates_type_single_unify"=>$attr_certificates_type_huatai_single_unify,  
		                        "attr_sex_unify"=>$attr_sex_huatai_unify,
		                        "attr_certificates_type_group_unify"=>$attr_certificates_type_huatai_group_unify
								);
								
//added by zhangxi, 20141230, for huaan
$attr_obj_type_huaan = array(	"attr_relationship_with_insured"=>$attr_relationship_with_insured_huaan,
							  	"attr_certificates_type"=>$attr_certificates_type_huaan,
		                       	"attr_sex"=>$attr_applicant_gender_huaan,
		                        "attr_group_certificates_type"=>$attr_applicant_type_huaan,
		                        "attr_company_attribute_type"=>'',
		                     );

$attr_obj_type_xinhua = array(	"attr_relationship_with_insured"=>$attr_xinhua_relationship_with_applicant,
							  	"attr_certificates_type"=>$attr_xinhua_certificates_type,
		                       	"attr_sex"=>$attr_xinhua_applicant_gender,
		                        "attr_group_certificates_type"=>'',
		                        "attr_company_attribute_type"=>'',
		                     );
$attr_obj_type_chinalife = array(	"attr_relationship_with_insured"=>$attr_relationship_with_insured_chinalife,
							  	"attr_certificates_type"=>$attr_certificates_type_chinalife,
		                       	"attr_sex"=>$attr_applicant_gender_chinalife,
		                        "attr_group_certificates_type"=>'',
		                        "attr_company_attribute_type"=>'',
		                     );
								
$attr_obj_type_picclife = array(	"attr_relationship_with_insured"=>$attr_picclife_RelationRoleCode,
							  	"attr_certificates_type"=>$attr_picclife_GovtIDTC,
		                       	"attr_sex"=>$attr_picclife_Gender,
		                        "attr_group_certificates_type"=>'',
		                        "attr_company_attribute_type"=>'',
		                     );
		                     
$attr_obj_type_epicc = 	array(	"attr_relationship_with_insured"=>$attr_epicc_RelationRoleCode,
							  	"attr_certificates_type"=>$attr_epicc_GovtIDTC,
		                       	"attr_sex"=>$attr_epicc_Gender,
		                        "attr_group_certificates_type"=>$attr_epicc_GovtIDTC,
		                        "attr_company_attribute_type"=>'',
		                     );	                     
$attr_obj_type_sinosig = array(	"attr_relationship_with_insured"=>$attr_relationship_applicant_with_insured_sinosig,
							  	"attr_certificates_type"=>$attr_certificates_type_sinosig,
		                       	"attr_sex"=>$attr_sex_sinosig,
		                        "attr_group_certificates_type"=>$attr_certificates_type_sinosig,
		                        "attr_company_attribute_type"=>'',
		                     );	 

$global_attr_obj_type = array(	"PAC"=>$attr_obj_type_pingan,
								"PAC01"=>$attr_obj_type_pingan,
								"PAC02"=>$attr_obj_type_pingan,
								"PAC03"=>$attr_obj_type_pingan,
								"PAC04"=>$attr_obj_type_pingan,//added by zhangxi, 20150709,平安个财
								"PAC05"=>$attr_obj_type_pingan,//可能用于平安大连
								"TBC01"=>$attr_obj_type_taipingyang,
								"HTS"=>$attr_obj_type_huatai,
								//added by zhangxi, 20141230, for 华安
								"SINO"=>$attr_obj_type_huaan,
								//added by zhangxi, 20150401, 增加新华人寿，国寿
								"NCI"=>$attr_obj_type_xinhua,
								"CHINALIFE"=>$attr_obj_type_chinalife,
								//added by zhangxi,20150421,增加picclife
								"picclife"=>$attr_obj_type_picclife,
								//added by zhangxi, 20150506, 增加人保财险
								"EPICC"=>$attr_obj_type_epicc,
								"CPIC_TJ_PROPERTY"=>$attr_obj_type_cpic_tj_property,
								"sinosig"=>$attr_obj_type_sinosig,
								"SSBQ"=>''
			                 );
/////////////////////////////////////////////////////////////////////////////////
//added by zhangxi, 20150128, 通过手机
function get_customer_info_by_cart_id($cart_insure_id)
{
	global $_SGLOBAL;
	//先通过保单号，找到投保人的手机号
	if(!$cart_insure_id)
	{
		ss_log(__FUNCTION__.", cart_insure_id=0");
		return 0;
	}
	$policy_list = get_policy_by_group_id($cart_insure_id);
	$policy_attr = $policy_list[0];
	$user_info_applicant = $policy_attr['user_info_applicant'];
	$mobilephone = $user_info_applicant['mobiletelephone'];
	
	//再通过手机号，查找c端用户的信息，主要是得到c端用户的id
	$wheresql = "user_name='$mobilephone'";
	$sql = "SELECT * FROM bx_users_c WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$user_c = $_SGLOBAL['db']->fetch_array($query);
	
	return $user_c;
}

//added by zhangxi, 2015004, 生成文件路径,目录不存在则自动创建
function gen_file_fullpath($type, $insurer_code, $ROOT_DIR, $file_name)
{
	global $_SGLOBAL;
	$time=date('Y-m-d',$_SGLOBAL['timestamp']);
	if($type == 'policy')//投保相关的文件路径
	{
		$path = $ROOT_DIR."xml_new/".$insurer_code."/".$time;
		//getcwd
		//判断目录存在否，存在给出提示，不存在则创建目录
		if (is_dir($path)){  
			//目录已经存在
		}else{
			//第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
			//$res=mkdir(iconv("UTF-8", "GBK", $path),0777,true); 
			$res=mkdir($path,0755,true); 
			if ($res){
				//echo "目录 $path 创建成功";
				
			}else{
				//echo "目录 $path 创建失败"
				ss_log(__FUNCTION__.", 创建目录失败，dir=".$path);
				return $ROOT_DIR."/".$file_name;
			}
		}
		return $path."/".$file_name;
		
	}
	elseif($type == 'logfile')//日志记录相关
	{
		
	}
}

//added by zhangxi, 20150128,  通过商品id查找活动信息
function get_activity_info_by_goods($goods_id)
{
	global $_SGLOBAL;
	if(!$goods_id)
	{
		return 0;
	}

    $goods_id = ',' . $goods_id . ',';
	//这里要改，先跑通一下，再改完善
	$wheresql = "act_range='3' AND CONCAT(',', act_range_ext, ',') LIKE '%" . $goods_id . "%'";
	$sql = "SELECT * FROM bx_favourable_activity WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$activity_info = $_SGLOBAL['db']->fetch_array($query);
	//comment by zhangxi, 20150128, 有没有可能活动时，一个产品同时在两个活动中?
	//
	return $activity_info;
}

//added by wangcya, 20150207, 查找到C端用户的信息
function get_client_info_by_id($client_id)
{
	global $_SGLOBAL;
	/////////////////////////////////////////////
	ss_log("into function: ".__FUNCTION__);
	
	if(!$client_id)
	{
		ss_log("client_id is null ,so retunr");
		return 0;
	}

	//这里要改，先跑通一下，再改完善
	$wheresql = "user_id='$client_id'";
	$sql = "SELECT * FROM  bx_users_c WHERE $wheresql LIMIT 1";
	ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);
	$client_info = $_SGLOBAL['db']->fetch_array($query);
	//comment by zhangxi, 20150128, 有没有可能活动时，一个产品同时在两个活动中?
	//
	return $client_info;
}

function input_check_param_pre_process($insurer_code, $POST, $product)
{
	global $INSURANCE_PAC;
	
	ss_log(__FUNCTION__."insurer_code=".$insurer_code);

	if(in_array($insurer_code, $INSURANCE_PAC))//add by wangcya, 20150506
	{
		if($product['attribute_type'] == 'jingwailvyou')
		{
			$product_code= $product['product_code'];
			ss_log(__FUNCTION__."product_code: ".$product_code);
			
			$period_code = $POST['period_code'];
			ss_log(__FUNCTION__."period_code: ".$period_code);
			
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
			else//平安行境外保障计划
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
					ss_log(__FUNCTION__."aftter transfer,product_code: ".$product['product_code']);
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
					ss_log(__FUNCTION__."aftter transfer,product_code: ".$product['product_code']);
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
			ss_log(__FUNCTION__."apply_day: ".$apply_day);
			///////////////////////////////////////////////////////////////////////////////////
			$POST['apply_day'] = $apply_day;
		}
		//平安的简单产品
		elseif($product['attribute_type'] == 'product')
		{
			//不用做处理
		}
		
		
	}
	$param=array(
				'product'=>$product,
				'post'=>$POST
				);
	return $param;
}


//added by zhangxi, 20150115, 附加信息的统一处理
function process_additional_info_bat($ret_policy_output, $retattr, $product)
{
	global $_SGLOBAL,$INSURANCE_PAC;
	
	//////////////////////////////////////////////////
	$policy_attr = $ret_policy_output['policy_arr'];
	$policy_id = $policy_attr['policy_id'];
	$global_info = $retattr['global_info'];
	
	$insurer_code = $product['insurer_code'];
	if(in_array($insurer_code, $INSURANCE_PAC))//add by wangcya, 20150506
	{	
		//境外旅游
		if($product['attribute_type'] == 'jingwailvyou')
		{
			///////////////////////////////////////////////////////////////////////////////////
			ss_log(__FUNCTION__."准备插入平安境境外旅游的附加信息到数据库，policy_id: ".$policy_id);
			
			$attr_pingan_other_info = array(
					"policy_id"=>$policy_id,
					'period_code'=>$global_info['period_code'],
					"outgoingPurpose"=>$global_info['travel_destionation'],//出行目的
					"destinationCountry"=>$global_info['travel_destionation'],//出行目的地
					"schoolOrCompany"=>$global_info['travel_destionation'],
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
			
		}
	}
	//华泰产品附加信息处理
	elseif($product['insurer_code'] == 'HTS')
	{
		ss_log(__FUNCTION__."准备插入华泰的附加信息到数据库,policy_id: ".$policy_id);
	
		$global_info = $retattr['global_info'];

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
		global $attr_purpose_huatai;
		if($policy_huatai_yiwai_otherinfo)
		{
				
			$ret_policy_output['policy_arr']["period_code"] = $policy_huatai_yiwai_otherinfo['period_code'];//出行目的
			$ret_policy_output['policy_arr']["purpose"] = $policy_huatai_yiwai_otherinfo['purpose'];//出行目的
			$ret_policy_output['policy_arr']["destination_area"] = $policy_huatai_yiwai_otherinfo['destination_area'];//出行目的地
			$ret_policy_output['policy_arr']["destination_country"] = $policy_huatai_yiwai_otherinfo['destination_country'];//出行目的地
			$ret_policy_output['policy_arr']["visacity"] = $policy_huatai_yiwai_otherinfo['visacity'];//签证城市
			$ret_policy_output['policy_arr']["purpose"] = $attr_purpose_huatai[$policy_huatai_yiwai_otherinfo["purpose"]];
		}
	}
	return $ret_policy_output;
}


//added by zhangxi, 20150120, 不同保险公司不一样的信息
function process_additional_global_info_bat($insurer_code,$product,$POST, $val, $global_info)
{
	global $INSURANCE_PAC;

	if(in_array($insurer_code, $INSURANCE_PAC))//add by wangcya, 20150506
	{
		//平安境外旅行目的地信息获取
		$global_info['travel_destionation'] = trim($val['destination']);
		//平安境外旅游需要传递的参数
		$global_info['period_code'] = trim($POST['period_code']);
	}
	elseif($insurer_code == 'HTS')//华泰旅游
	{
		global $attr_purpose_huatai;
		$global_info['period_code'] = $POST['period_code'];//保险期限代码
		
		$global_info['purpose'] =  trim($val['purpose']);//出行目的
		//文字转换成保险公司的代码
		$global_info['purpose'] = array_search($global_info['purpose'],$attr_purpose_huatai);
		
		$global_info['destination_area'] =  trim($val['destination']);//出行目的地
		$global_info['destination_country'] =  trim($val['destination']);//出行目的地
		$global_info['visacity'] =  trim($val['visacity']);//签证城市
	}
	
	return $global_info;
}

//added by  zhangxi, 20150120, 设置默认的投保人与被保险人之间的关系
function set_default_relationship_info_bat($insurer_code)
{
	global $INSURANCE_PAC;
	////////////////////////////////////////////////////

	if(in_array($insurer_code, $INSURANCE_PAC))//add by wangcya, 20150506
	{
		$relationshipWithInsured = 6;
	}
	elseif($insurer_code == "HTS")//华泰
	{
		$relationshipWithInsured = 5;
	}
	elseif($insurer_code == "TBC01")
	{
		
	}
	elseif($insurer_code == "SINO")
	{
		$relationshipWithInsured = "601003";
	}
	return $relationshipWithInsured;	
}

//added by  zhangxi, 20150120, 设置默认的证件类型代码
function set_default_certificates_type_bat($insurer_code)
{
	global $INSURANCE_PAC;

	if(in_array($insurer_code, $INSURANCE_PAC))//add by wangcya, 20150506
	{
		$default_certificates_type = 99;
	}
	elseif($insurer_code == "HTS")//华泰
	{
		$default_certificates_type = 5;
	}
	elseif($insurer_code == "TBC01")
	{
		
	}
	return $default_certificates_type;	
}

//added by zhangxi, 20150113, 批量投保处理
function input_check_temp_bat($insurer_code, $product, $POST)
{
	
	//echo "<pre>";print_r($POST);exit;
	
	global $_SGLOBAL, $global_attr_obj_type;
	/////////////////////////////////////////////////
	
	ss_log("into function ".__FUNCTION__);
	$global_info = array();

	$agent_uid = $_SGLOBAL['supe_uid'];//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	//////////////////有效性检查//////////////////////////////////////////////////////
	if(	!isset($POST['applyNum'])||
		!isset($POST['totalModalPremium_single'])||
		!isset($POST['totalModalPremium'])||//总的保费，用来校验的，等于 totalModalPremium_single*applyNum*usernum
			!isset($POST['startDate'])||
			!isset($POST['endDate'])||
			!isset($POST['data_user_info'])
		)
	{
		ss_log(__FUNCTION__.":applyNum:".$POST['applyNum'].",totalModalPremium_single=".$POST['totalModalPremium_single'].",totalModalPremium=".$POST['totalModalPremium'].
						",startDate=".$POST['startDate'].",endDate=".$POST['endDate'].",data_user_info=".$POST['data_user_info']);
		showmessage("您输入的投保信息不完整哦！");
		exit(0);
	}
	////////////////////////////////////////////////////////////
	//这个地方的投保份数，界面是一份，但实际值应该等于投保人数
	ss_log("in input_check_pingan_bat, 每个人的投保份数, post applyNum: ".$POST['applyNum']);
	ss_log("in input_check_pingan_bat, 所有人的总保费，订单的总保费，post totalModalPremium: ".$POST['totalModalPremium']);
	ss_log("in input_check_pingan_bat, 单价，不乘份数，post totalModalPremium_single: ".$POST['totalModalPremium_single']);
	
	$businessType = empty($POST['businessType'])?1:trim($POST['businessType']);

	$bat_post_policy = empty($POST['bat_post_policy'])?1:trim($POST['bat_post_policy']);
	if($bat_post_policy != 1)
	{
		//input check error
		showmessage("投保方式不正确！");
		exit(0);
	}
	
	$attr_certificates_type_single_unify = $global_attr_obj_type[$insurer_code]['attr_certificates_type_single_unify'];
	$attr_sex_unify = $global_attr_obj_type[$insurer_code]['attr_sex_unify'];
	$attr_certificates_type_group_unify = $global_attr_obj_type[$insurer_code]['attr_certificates_type_group_unify'];
	

	//主险的产品ID
	$product_id = intval($POST['product_id']);
	//获取用户选定的投保险种，如果主险没有选，则异常

	//一个subjectInfo内多个产品，多个被保险人
	$subjectInfo1 = array();

	$data_user = stripslashes($POST['data_user_info']);
	if(empty($data_user))
	{
		showmessage("获取批量投保信息失败！");
		exit(0);
	}

	ss_log(__FUCNTION__.":get post data:".$POST['data_user_info']);

	$list_user = json_decode($data_user, true);
	ss_log(__FUCNTION__."json decode data:".$list_user);

	//获取被保险人信息，这里需要考虑多个被保险人信息
	$real_apply_num = count($list_user);//被保险人个数

	if($real_apply_num > 3000 )
	{
		showmessage("最大投保人数为3000人！".$real_apply_num);
		exit(0);
	}

	ss_log("in input_check_pingan_bat, 被保险人总数，real_apply_num: ".$real_apply_num);
	/////////////////////////////////////////////////////////////////////

	$policy_attr = array();
	$assured_num = 0;
	//根据被保险人的个数形成多个保单数据
	foreach($list_user as $k=>$val)
	{
		$assured_num++;
		//全局信息
		$global_info = array();
		$global_info['applyNum'] = intval($POST['applyNum']);//每个人份数
		//单个保单的保费，还是多个保单的?
		$global_info['totalModalPremium'] = $global_info['applyNum']*$POST['totalModalPremium_single'];//单价乘以投保份数等于每个投保单的总保费
		//兼容华泰的传递的数据，华泰使用fading传递
		$global_info['beneficiary'] = trim($POST['beneficiary']);//受益人， 这个地方需要传递给后台
		$global_info['fading'] =  trim($POST['fading']);//受益人，华泰
		
		$global_info['startDate'] = $POST['startDate'];//保险开始日期
		$global_info['endDate'] = $POST['endDate'];//保险结束日期
		//added by zhangxi, 20150508, 批量中，每一投保单的被保险人个数都是一个。
		//以下这个变量主要给EPICC用的，不可或缺。
		$global_info['insured_num'] = 1;
		
		$global_info['apply_day'] = $apply_period = empty($POST['apply_day'])?intval($POST['period']):intval($POST['apply_day']);//add by wangcya, 20150110, 投保的天数
		
		$global_info = process_additional_global_info_bat($insurer_code,$product, $POST, $val, $global_info);
		//中文比较还是不太靠谱，但前端展现要求的是中文
		//可以考虑加个字段
		$user_info_applicant = array();
		//投保人信息
		if(trim($val['applicant_type']) == '个人')//个人
		{
			$business_type = 1;
			$user_info_applicant['certificates_type'] = trim($val['applicant_certificates_type']);//要转换成保险公司代码
			$attr_certificates_type = $global_attr_obj_type[$insurer_code]['attr_certificates_type'];
			$user_info_applicant['certificates_type'] = array_search($user_info_applicant['certificates_type'], $attr_certificates_type);
			if(empty($user_info_applicant['certificates_type']))
			{
				$user_info_applicant['certificates_type'] = set_default_certificates_type_bat($insurer_code);//
			}
			ss_log(__FUNCTION__.":applicant_name =".$val['applicant_name']);
			$user_info_applicant['certificates_code'] = trim($val['applicant_certificates_code']);
			$user_info_applicant['fullname'] = trim($val['applicant_name']);
			$user_info_applicant['fullname_english'] = trim($val['applicant_englishname']);//英文名
			$user_info_applicant['gender'] = trim($val['applicant_gender']);//要转换成保险公司代码
			$attr_sex = $global_attr_obj_type[$insurer_code]['attr_sex'];
			$user_info_applicant['gender'] = array_search($user_info_applicant['gender'], $attr_sex);
			$user_info_applicant['birthday'] = trim($val['applicant_birthday']);
			$user_info_applicant['mobiletelephone'] = trim($val['applicant_mobilephone']);
			$user_info_applicant['email'] = trim($val['applicant_email']);
			
			
			//start add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
			//这个跟平安相关的转换，最好也改成通过险种可以找到
			$user_info_applicant['certificates_type_unify'] = $attr_certificates_type_single_unify[$user_info_applicant['certificates_type']];
			$user_info_applicant['gender_unify'] = $attr_sex_unify[$user_info_applicant['gender']];
			$user_info_applicant['type'] = 1;//0,被保险人，1投保人,2两者身份相同
			$user_info_applicant['agent_uid'] = $agent_uid;
			//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		}
		else if(trim($val['applicant_type']) == '机构')
		{
			$business_type = 2;
			//团体投保人信息获取
			$user_info_applicant['group_name'] = trim($val['applicant_name']);
			$user_info_applicant['group_certificates_type'] = trim($val['applicant_certificates_type']);
			//转换都要统一弄成支持所有保险公司的转换
			$attr_group_certificates_type = $global_attr_obj_type[$insurer_code]['attr_group_certificates_type'];
			$user_info_applicant['group_certificates_type'] = array_search($user_info_applicant['group_certificates_type'], $attr_group_certificates_type);
			//$user_info_applicant['group_certificates_type'] = 01;
			$user_info_applicant['group_certificates_code'] = trim($val['applicant_certificates_code']);
		
			//$user_info_applicant['group_abbr'] = $POST['group_abbr'];//这个没有
			//$user_info_applicant['company_attribute'] = $POST['company_attribute'];//这个没有
			//$user_info_applicant['address'] = $POST['address'];//这个没有
			//$user_info_applicant['telephone'] = $POST['telephone'];//这个没有
			$user_info_applicant['mobiletelephone'] = trim($val['applicant_mobilephone']);
			$user_info_applicant['email'] = trim($val['applicant_email']);
		
			ss_log(__FILE__.":group name:".$user_info_applicant['group_name']);
		
			//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
			$user_info_applicant['group_certificates_type_unify'] = $attr_certificates_type_group_unify[$user_info_applicant['group_certificates_type']];
			$user_info_applicant['agent_uid'] = $agent_uid;
		}
		else
		{
			ss_log("ERROR:[".__FUNCTIOIN__."]:unknown applicant type!");
			showmessage("ERROR: 未知的投保人类型:".trim($val['applicant_type']));
			exit(0);
		}
		
		//以下是被保险人信息获取
		$assured_info = array();
		$assured_info['fullname']  = trim($val['assured_name']);
		$assured_info['fullname_english']  = trim($val['assured_englishname']);
		$assured_info['certificates_type'] = trim($val['assured_certificates_type']);
		$assured_certificates_type = $global_attr_obj_type[$insurer_code]['attr_certificates_type'];
		$assured_info['certificates_type'] = array_search($assured_info['certificates_type'], $assured_certificates_type);
		$assured_info['certificates_code'] = trim($val['assured_certificates_code']);
		$assured_info['birthday'] = trim($val['assured_birthday']);
		$assured_info['gender']  = trim($val['assured_gender']);
		$assured_attr_sex = $global_attr_obj_type[$insurer_code]['attr_sex'];
		$assured_info['gender'] = array_search($assured_info['gender'], $assured_attr_sex);
		$assured_info['mobiletelephone'] =  trim($val['assured_mobilephone']);
		$assured_info['email'] =  trim($val['assured_email']);
		
		//太保可能有的职业类别参数
		$assured_info['occupationClassCode'] =  trim($val['occupation']);
		
		//added by zhangxi, 20150817, 华安学平险被保险人学校
		if(isset($val['assured_school_name']))
		{
			$assured_info['school_name'] =  trim($val['assured_school_name']);
		}

		//add by wangcya, 20141219 ,不同厂商的证件号码和性别
		$assured_info['certificates_type_unify'] = $attr_certificates_type_single_unify[$assured_info['certificates_type']];
		$assured_info['gender_unify'] = $attr_sex_unify[$assured_info['gender']];
		$assured_info['type'] = 0;//0,被保险人，1投保人,2两者身份相同
		$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别
		
		//与被保险人关系
		//传上来的是汉字,转换成保险公司的关系代码
		$relationshipWithInsured = trim($val['relationship']);
		if($relationshipWithInsured == '本人')
		{
			$assured_info['type'] = 2;
		}
		else
		{
			ss_log(__FUNCTION__.", relationshipWithInsured=".$relationshipWithInsured);
		}
		
		$attr_relationship_with_insured = $global_attr_obj_type[$insurer_code]['attr_relationship_with_insured'];
		$relationshipWithInsured = array_search($relationshipWithInsured, $attr_relationship_with_insured);
		if(empty($relationshipWithInsured))
		{
			$relationshipWithInsured = set_default_relationship_info_bat($insurer_code);//如没有填写，则设置本人成受益人
		}

		////////////////////////////////////////////////////////////////////////////////
		$subjectInfo1 = array();
		
		$list_product_id1 = array();
		$list_product_id1[] = $product_id;//
		ss_log("bat check input, post product_id: ".$product_id);
		$subjectInfo1['list_product_id'] = $list_product_id1;

		$list_assured1 = array();
		$list_assured1[] = $assured_info;
		$subjectInfo1['list_assured'] = $list_assured1;
		ss_log(__FUNCTION__.", list_assured1=".var_export($list_assured1, true));

		////////////////////////////////////////////////////////////
		$list_subjectinfo = array();
		$list_subjectinfo[] = $subjectInfo1;
		////////////////////////////////////////////////////////////
		
		$retattr = array(
				"global_info"=>$global_info,
				"businessType"=>$business_type,
				"relationshipWithInsured"=>$relationshipWithInsured,
				"user_info_applicant"=>$user_info_applicant,
				"list_subjectinfo"=>$list_subjectinfo
		);

		$policy_attr[] = $retattr;//为形成多个投保单做准备
	}//foreach， $list_user
	
	//comment by zhangxi, 20150116, 价格校验
	//前端传过来的计算出来的总保费
	$custom_visitor_premium = $POST['totalModalPremium'];//订单总的保费，用来校验的，应该等于 totalModalPremium_single*applyNum*usernum
	//后端计算的总保费
	$calculated_premium = $POST['totalModalPremium_single']*$POST['applyNum']*$assured_num;
	$calculated_premium = round($calculated_premium,2);
	$custom_visitor_premium = round($custom_visitor_premium,2);
	if($calculated_premium != $custom_visitor_premium)
	{
		showmessage("保费校验错误！");//退出
		exit(0);
	}
	

	///////////////////////////////////////////////////////////
	return $policy_attr;
}

//start add by wangcya, for bug[193],能够支持多人批量投保，

function process_product_simple_loop($insurer_code,
		                             $product,
		                             $POST
		                             )
{
	global $_SGLOBAL, $INSURANCE_PAC;
	///////////////////////////////////////////////////////////////////////////
	ss_log(__FUNCTION__);
	
	//added by zhangxi, 20150115,
	//输出参数可能需要预处理的，统一先进行处理,这个最后没有问题后，移到本函数最前面
	$param = input_check_param_pre_process($insurer_code, $POST, $product);
	$POST = $param['post'];
	$product = $param['product'];

	if(in_array($insurer_code, $INSURANCE_PAC))//add by wangcya, 20150506
	{
		//如果是境外旅游产品，走这里
		if($product['attribute_type'] == 'jingwailvyou'
		||$product['attribute_type'] == 'lvyou'//支持平安境内旅游批量投保
		||$product['attribute_type'] == 'product')//再支持上平安的少儿综合，以及卡相关的
		{
			$policy_attr_list = input_check_temp_bat($insurer_code, $product, $POST);
		}
		else
		{	//后续这个代码可能没有用了，就去掉
			$policy_attr_list = input_check_pingan_bat($POST);//多个被保险人的时候，	
		}
		
	}
	elseif($insurer_code =="TBC01")//太平洋
	{
		//mod by zhangxi, 20150119 , 准备好代码
		$policy_attr_list = input_check_temp_bat($insurer_code, $product, $POST);
		//$policy_attr_list = input_check_taipingyang_bat($POST);//多个被保险人的时候，
	}
	elseif($insurer_code =="HTS")
	{
		$policy_attr_list = input_check_temp_bat($insurer_code, $product, $POST);
	}
	elseif($insurer_code =="SINO")
	{
		$policy_attr_list = input_check_temp_bat($insurer_code, $product, $POST);
	}
	//人保财险批量
	elseif($insurer_code =="EPICC")
	{
		$policy_attr_list = input_check_temp_bat($insurer_code, $product, $POST);
	}
	else
	{
		$policy_attr_list = input_check_temp_bat($insurer_code, $product, $POST);
	}

	//start add by wangcya, for bug[193],能够支持多人批量投保
	$agent_uid = $_SGLOBAL['supe_uid'];//代理人
	$total_apply_num = count($policy_attr_list);
	$totalModalPremium = $POST['totalModalPremium'];//所有保单的总价，就是订单总价


	//$agent_username = $_SGLOBAL['supe_username'];
	$cart_insure_attr = array("user_id"=>$agent_uid,
			"product_id"=>$product['attribute_id'],
			"product_name"=>$product['attribute_name'],
			"total_apply_num"=>$total_apply_num,
			"total_price"=>$totalModalPremium
				
	);

	$cart_insure_id = inserttable("cart_insure", $cart_insure_attr,true);

	//end add by wangcya, for bug[193],能够支持多人批量投保

	$policy_list = array();
	foreach($policy_attr_list AS $key=>$retattr)
	{
		$ret_policy_output = process_gen_policy($retattr,$product,$cart_insure_id);//公共函数
		//added by zhangxi, 20150115, 需要加入各保险公司可能的附加信息
		//导入数据库和查询输出
		$ret_policy_output = process_additional_info_bat($ret_policy_output, $retattr, $product);
		$policy_list[] = $ret_policy_output;
	}

	return $policy_list;
}
//end add by wangcya, for bug[193],能够支持多人批量投保，

function get_product_info( $product_id )
{
	global $_SGLOBAL;
	////////////////////////////////////////////////////////////////////////
		
	$wheresql = "p.product_id='$product_id'";
	
	$sql = "SELECT * FROM ".tname('insurance_product_base')." p
	LEFT JOIN ".tname('insurance_product_additional')." padd ON padd.product_id=p.product_id
	INNER JOIN ".tname('insurance_product_attribute')."  patt ON patt.attribute_id=p.attribute_id
	WHERE p.product_id='$product_id'";
	
	//p INNER JOIN
	//$sql = "SELECT * FROM (SELECT p.* FROM t_insurance_product_base p, t_insurance_product_attribute patt where patt.attribute_id=p.attribute_id and p.product_id ='6' ) as c  INNER JOIN t_insurance_product_additional padd ON padd.product_id=c.product_id";
	
	//ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);
	
	$product = $_SGLOBAL['db']->fetch_array($query);	
	
	return $product;
}

function create_java_obj($java_class_name)
{
	global $_SGLOBAL;//add by wangcya, 20141107,不要重复建立了
	/////////////////////////////////////////////////////
	ss_log("in function create_java_obj, java_class_name: ".$java_class_name);
	
	$path_java_inc = S_ROOT.JAVA_JAR_INC;
	ss_log("java inc path: ".$path_java_inc);
	
	require_once($path_java_inc); //必须包含的配置文件
	ini_set('display_errors', 1);
	
	$path_java = S_ROOT.JAVA_JAR;
	//$path_java = S_ROOT."./java/pdf_fetcher-1.1.6.jar";
	ss_log("java path: ".$path_java);
	
	java_require($path_java);//comment by wangcya, 20150331,这个地方很不稳定，例如网银支付成功，我们也收到到款状态，但是投保不成功的现象就和这里有关。
	
	ss_log("before get java obj");
		
	/*
	 在PHP中，假如需要在页面之间共享数据，需要手动将变量保存到预定义的全局变量$GLOBALS或$_SESSION中。
	 PHP会将这些变量保存在某个 文件中，以便下次执行页面时读取。但是，这种方式存在着极大的限制，
	 除了效率的低下外，它还无法保存引用外部资源的变量，例如文件、Socket、数据库 连接等，
	 而正是这些资源最需要被缓存。
	 */
	///////////////////////////////////////////////////////////////////
	
	if($_SERVER["SERVER_PORT"] == 80)
	{
		$key_str = $java_class_name."formal";
		$port_memcache = 12001;
	}
	else
	{
		$key_str = $java_class_name."test";
		$port_memcache = 12000;
	}
		
	ss_log("in function create_java_obj,key_str:".$key_str);
	
	$key = md5($key_str);
	
	//$memcache = new Memcache;
	if(0)//$memcache)
	{
		ss_log("new memcache ok, port_memcache: ".$port_memcache);
		//localhost
		$ret = $memcache->connect('127.0.0.1', $port_memcache);// or die ("Could not connect");
		ss_log("memche connet ret: ".$ret);
		if(!$ret)
		{
			ss_log("memche connet fail, so new java and return");
			$obj_java = new Java($java_class_name);//'com.yanda.imsp.util.ins.CPICPolicyFecher'
			return $obj_java;
		}
		
		//可能会执行到这里失败
		ss_log("will get java object from memcache!, key: ".$key);
		$obj_java = $memcache->get($key);//这里出错，不返回，可能其实与java bridge 可能无关，而是重新启动memcached即可。还是因为过期失效了呢?
		//如果成功，则返回key对应的值，如果失败则返回false.
		if(!$obj_java) 
		{
			ss_log("get java object from memcache fail!");
			
			$obj_java = new Java($java_class_name);//'com.yanda.imsp.util.ins.CPICPolicyFecher'
			if($obj_java)
			{
				ss_log("new java success,will set to memcache! ".$obj_java);
				$memcache ->set($key, $obj_java , false, 36000);//second
			}
			else
			{
				ss_log("in memcache, new java fail!");
			}
		}
		else
		{
			ss_log("get key from memcache success: ".$obj_java);
				
			//  $return=$mem->get($city);
			//  echo json_encode($return);
		}
		
		ss_log("will close memcache");
		$memcache->close();//add by wangcya, 20150323,使用完毕一定要及时关闭
	}
	else
	{
		ss_log("new memcache fail,so use global");
		if(empty($_SGLOBAL[$key]))//add by wangcya, 20141107,不要重复建立了
		{
			//下面这个函数好像经常被阻塞。
			$obj_java = new Java($java_class_name);//'com.yanda.imsp.util.ins.CPICPolicyFecher'
			if($obj_java)
			{
				ss_log("new java success,will set to global!");
		
				//$_SESSION[$java_class_name] = $obj_java;
				$_SGLOBAL[$key] = $obj_java;
		
			}
			else
			{
				ss_log("in global ,new java fail!");
			}
		}
		else
		{
			ss_log("SGLOBAL objjava already exisit, so use it!");
			$obj_java = $_SGLOBAL[$key];
			//$_SESSION[$java_class_name] = $_SGLOBAL[$java_class_name];
				
		}
	}
	
	ss_log("after get java obj!");
		
	return $obj_java;
}



//这个函数被双方共用
function get_product_duty_list( $product_id, 
						$product_duty_price_type 
					  )
{
	global $_SGLOBAL;
	////////////////////////////////////////////////////////////////////////
//////////////得到保险责任////////////////////////////////
	$wheresql = "pt.product_id='$product_id'";
	$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_duty')." pt WHERE $wheresql";
	//echo $countsql;
	//ss_log($countsql);
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
	if($count)
	{

		$ordersql = "ORDER BY pt.view_order";

		/*del by wangcya,20140921 ,统一到一张表中
		if( $product_duty_price_type == 'single')
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
		*/
			
		$sql = "SELECT * FROM ".tname('insurance_product_duty')." pt INNER JOIN	".tname('insurance_duty')." inst ON pt.duty_id=inst.duty_id
		   WHERE $wheresql $ordersql LIMIT 0,20";
		
		
		////////////////////////////////////////////////////////////////////////
		//ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);

		$urlduty = "cp.php?ac=admin_insurance_product&op=duty&product_id=$product_id";
		$product_duty_list = array();
		while ($valued = $_SGLOBAL['db']->fetch_array($query))
		{
			$product_duty_list[] =  $valued;
		}
	}
	
	return @$product_duty_list;
}


//得到产品的影响因素列表,也是被双方共用
function get_product_influencingfactor_list( $product_id,
									 $product_influencingfactor_type
											)
{
	global $_SGLOBAL;
	$product_influencingfactor_list = array();
	////////////////////////////////////////////////////////////////////////
	//////////////得到保险责任////////////////////////////////
	$wheresql = "pt.product_id='$product_id'";
	
	if($product_influencingfactor_type)
	{
		$wheresql .= " AND pt.product_influencingfactor_type='$product_influencingfactor_type'";
	}

	
	$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_influencingfactor')." pt WHERE $wheresql";
	//echo $countsql;
	//ss_log($countsql);
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
	if($count)
	{

		$ordersql = "ORDER BY pt.view_order";
		$sql = "SELECT * FROM ".tname('insurance_product_influencingfactor')." pt WHERE $wheresql $ordersql LIMIT 0,200";

		//ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);

		
		
		while ($valued = $_SGLOBAL['db']->fetch_array($query))
		{
			$product_influencingfactor_list[] =  $valued;
		}
	}

	return $product_influencingfactor_list;
}

////////////////提交订单进行投保///////////////////////////////
function insurance_gen_policy_order( $attribute_id,
									 $global_info, 
									 $businessType,
									 $relationshipWithInsured,
									 $user_info_applicant,
								     $list_subjectinfo =array(),//层级列表
									 $cart_insure_id//add by wangcya , 20141225,批量的标志
									  )
{
	global $_SGLOBAL, $_SC, $space;
	
		
	////////////////投保人的身份信息////////////////////////////////

	ss_log("insurance_gen_policy_order, businessType: ".$businessType);
	
	$agent_uid = $_SGLOBAL['supe_uid'];
	//////////////////投保人是个人或者团体///////////////////////////////////////////
	if($businessType == 1)//个人
	{
		$applicant_certificates_type = $user_info_applicant['certificates_type'];
		$applicant_certificates_code = $user_info_applicant['certificates_code'];
		if(strlen($applicant_certificates_code)>30)
		{
			showmessage("证件号码太长");
		}
		
		$applicant_username = $user_info_applicant['fullname'];
		/////////////////////////////////////////////////////////////////
		//modify by wangcya , 20140912, 要根据代理人uid， 姓名，证件类型和证件号码查找，当时出现了相同的投保人姓名就是这里引起的。
		
		$wheresql = "agent_uid='$agent_uid' AND fullname='$applicant_username' AND certificates_type='$applicant_certificates_type' AND certificates_code='$applicant_certificates_code'";
		$sql = "SELECT * FROM ".tname('user_info')." WHERE $wheresql";
		
		ss_log($sql);
		
		$query = $_SGLOBAL['db']->query($sql);
		$user_info_applicant_db = $_SGLOBAL['db']->fetch_array($query);
		
		$user_info_applicant_attr = $user_info_applicant;//add by wangcya , 20140929,前面整理的信息应该要和数据库表中的一致，这里就直接使用即可。
		
		/////////////////先插入投保人的身份信息///////////////////////////
		if(empty($user_info_applicant_db['uid']))//new
		{	
			ss_log(__FUNCTION__.", 插入投保人个人信息, applicant_username: ".$applicant_username);
			/*
			$user_info_applicant_attr['fullname'] = $applicantUsername;
			$user_info_applicant_attr['certificates_type'] = $applicantcertificates_type;
			$user_info_applicant_attr['certificates_code'] = $applicantcertificates_code;
			*/
			
			$user_info_applicant_attr = saddslashes($user_info_applicant_attr);//add by wangcya , 20141211,for sql Injection
			$applicantUid1 = inserttable("user_info", $user_info_applicant_attr,true);
			$user_info_applicant['uid'] = $applicantUid1;
			
			$applicantUsername1 = $applicant_username;
			ss_log(__FUNCTION__.", 插入投保人个人信息, uid: ".$applicantUid1);
		}
		else//已经有该投保人信息的情况
		{//update
			//start modify by wangcya , 20140912, 要根据姓名，证件类型和证件号码查找，当时出现了相同的投保人姓名就是这里引起的。

			ss_log(__FUNCTION__.", 更新投保人个人信息, applicant_username: ".$applicant_username);
			ss_log(__FUNCTION__.", 更新投保人个人信息, applicant_certificates_type: ".$applicant_certificates_type);
			ss_log(__FUNCTION__.", 更新投保人个人信息, applicant_certificates_code: ".$applicant_certificates_code);
			
			$wheresqlarr = array('agent_uid'=>$agent_uid,//add by wangcya , 20141219，属于哪个代理人的
								'fullname'=>$applicant_username,
								'certificates_type'=>$applicant_certificates_type,
								'certificates_code'=>$applicant_certificates_code);
			
			$user_info_applicant_attr = saddslashes($user_info_applicant_attr);//add by wangcya , 20141211,for sql Injection
			updatetable("user_info", $user_info_applicant_attr, $wheresqlarr);
			//end modify by wangcya , 20140912, 要根据姓名，证件类型和证件号码查找，当时出现了相同的投保人姓名就是这里引起的。
					
			////////////////////////////////////////////////////////////
			$applicantUid1 = $user_info_applicant_db['uid'];
			$applicantUsername1 = $applicant_username;
		}
	}
	elseif($businessType == 2)//团体
	{
		$applicant_certificates_type = $user_info_applicant['group_certificates_type'];
		$applicant_certificates_code = $user_info_applicant['group_certificates_code'];
		if(strlen($applicant_certificates_code)>30)
		{
			showmessage("证件号码太长");
		}
		
		$group_name = $user_info_applicant['group_name'];
		///////////////////////////////////////////////////////////////////////////////
		//start modify by wangcya , 20140912, 要根据姓名，证件类型和证件号码查找，当时出现了相同的投保人姓名就是这里引起的。

		$wheresql = "agent_uid='$agent_uid' AND group_name='$group_name' AND group_certificates_type='$applicant_certificates_type' AND group_certificates_code='$applicant_certificates_code'";
		$sql = "SELECT * FROM ".tname('group_info')." WHERE $wheresql";
		$query = $_SGLOBAL['db']->query($sql);
		$user_info_applicant_db = $_SGLOBAL['db']->fetch_array($query);//会把进入的进行了替换
		
		/////////////////////////////////////////////////////
		$user_info_applicant_attr = $user_info_applicant;
		/////////////////先插入投保人的身份信息///////////////////////////
		if(empty($user_info_applicant_db['gid']))//new
		{
			ss_log("插入团体信息, group_name: ".$group_name);
			$user_info_applicant_attr = saddslashes($user_info_applicant_attr);//add by wangcya , 20141211,for sql Injection
			$applicantUid1 = inserttable("group_info", $user_info_applicant_attr,true);
			$applicantUsername1 = $group_name;
			
			$user_info_applicant['uid'] = $applicantUid1;
			
		}
		else
		{
			//start modify by wangcya , 20140912, 要根据姓名，证件类型和证件号码查找，当时出现了相同的投保人姓名就是这里引起的。

			ss_log(__FUNCTION__.", 更新团体信息, group_name: ".$group_name);
			ss_log(__FUNCTION__.", 更新团体信息, group_certificates_type: ".$applicant_certificates_type);
			ss_log(__FUNCTION__.", 更新团体信息, group_certificates_code: ".$applicant_certificates_code);
			
			$wheresqlarr = array('agent_uid'=>$agent_uid,//add by wangcya , 20141219，属于哪个代理人的
								 'group_name'=>$group_name,
					             'group_certificates_type'=>$applicant_certificates_type,
								 'group_certificates_code'=>$applicant_certificates_code);
			
			$user_info_applicant_attr = saddslashes($user_info_applicant_attr);//add by wangcya , 20141211,for sql Injection
			updatetable("group_info", $user_info_applicant_attr, $wheresqlarr);
			
			//end modify by wangcya , 20140912, 要根据姓名，证件类型和证件号码查找，当时出现了相同的投保人姓名就是这里引起的。
					
			
			//不能从数据库中获取了，只能从前端提交的得到，并更新到数据库中。并且按照名字查询。
			
			$applicantUid1 = $user_info_applicant_db['gid'];
			$applicantUsername1 = $group_name;
			
			
		}
	}
	
	
	///////////////先把保单信息保存起来////////////////////////////////////////////
		
	//////////////add by wangcya , 20140807, 得到产品属性///////////////////////////////////
	
	if($attribute_id)
	{
		$wheresql = "attribute_id='$attribute_id'";
		$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql";
		$query = $_SGLOBAL['db']->query($sql);
		$product_attribute = $_SGLOBAL['db']->fetch_array($query);
		$attribute_name = $product_attribute['attribute_name'];
		$attribute_type = $product_attribute['attribute_type'];
		$attribute_code = $product_attribute['attribute_code'];
	}
	
	
	$applicant_uid = $applicantUid1;//投保人
	$applicant_username = $applicantUsername1;
	$agent_uid = $_SGLOBAL['supe_uid'];//代理人
	$agent_username = $_SGLOBAL['supe_username'];
	
	ss_log("in gen_policy, agent_uid: ".$agent_uid." agent_username: ".$agent_username);
	
	$apply_num = $global_info['applyNum'];//份数
	$totalModalPremium = $global_info['totalModalPremium'];//该投保单的总保费
	
	//ss_log("totalModalPremium: ".$totalModalPremium);
	//$totalModalPremium = $apply_num*intval($product[premium]);//$POST['totalModalPremium'];//从费用
	
	$beneficiary= $global_info['beneficiary'];//受益人
	$startDate= $global_info['startDate'];//保险开始日期
	$endDate= $global_info['endDate'];//保险结束日期
	
	/////////start add by wangcya, 20140914
	$insurer_code = $global_info['insurer_code'];//
	$insurer_name = $global_info['insurer_name'];//

	$partner_code = $global_info['partner_code'];//
	$fading = $global_info['fading'];//受益人
	
	$apply_day  = $global_info['apply_day'];//add by wangcya, 20150110
	$apply_month  = $global_info['apply_month'];//add by wangcya, 20150110
	
	$product_code = $global_info['product_code'];//add by wangcya , 20150110
	//added by zhangxi, 20150618, 人保寿的康乐无忧产品的保额是用户自己设置的
	$policy_amount =isset($global_info['policy_amount']) ? $global_info['policy_amount']:0;

	//added by zhangxi, 20150514, 增加影响因素id
	$influencingfactor_id = isset($global_info['influencingfactor_id']) ? $global_info['influencingfactor_id'] : 0;
	
	ss_log("will gen policy, apply_day: ".$apply_day);
	ss_log("will gen policy, apply_month: ".$apply_month);
	
	ss_log("will gen policy, product_code: ".$product_code);
	/////////end add by wangcya, 20140914
	
	//start add by wangcya, for bug[193],能够支持多人批量投保
	if(empty($cart_insure_id))
	{//没有则生成一个，为了对付单个的情况
		$cart_insure_attr = array("user_id"=>$agent_uid,
								  "product_id"=>$attribute_id,
								  "product_name"=>$attribute_name,
								  "total_price"=>$totalModalPremium
				                  );
		
		$cart_insure_id = inserttable("cart_insure", $cart_insure_attr,true);
	}
	//end add by wangcya, for bug[193],能够支持多人批量投保
	//$POST['subject'] = getstr(trim($POST['subject']), 80, 1, 1, 1);
	
	ss_log("product_code: ".$product_code);
	
	$len_guid = 0;
	if($insurer_code == "HTS")
	{//如果是华泰，则最多为20个字符串。
		ss_log("华泰要求转变order_num ".$insurer_code);
		$len_guid = 20;
	}
	else
	{
		$len_guid = 0;
	}
	
	
	if($insurer_code=='EPICC') //如果是人保财险的需要生成投保单号
	{
		$cast_policy_no = get_proposalNo();
	}
	else
	{
		if(isset($global_info['cast_policy_no']))
		{
			$cast_policy_no = $global_info['cast_policy_no'];
		}
		else
		{
			$cast_policy_no=""; //投保单号
		}
		
	}
	
	$order_num = getRandOnly_Id($len_guid);//1400150010655;//$POST['applicant_fullname'];//自己本地生成的唯一的号码，这个有可能重复，这回产生问题。
		
	$policy_attr = array(
					'order_num'=>$order_num,//add by wangcya , 20140831,
					'attribute_id'=>$attribute_id,
					'attribute_code'=>$attribute_code,//add by wangcya, 20150127
					'attribute_name' => $attribute_name,
					'attribute_type' => $attribute_type,
					'agent_uid'=> $agent_uid,
					'agent_username'=> $agent_username,
					'apply_num'=>$apply_num,
					'total_premium'=>$totalModalPremium,
					'applicant_uid'=>$applicant_uid,
					'applicant_username'=>$applicant_username,
					'beneficiary'=>$beneficiary,
					'relationship_with_insured'=>$relationshipWithInsured,
					'apply_day'=>$apply_day,//add by wangcya, 20150110
					'apply_month'=>$apply_month,//add by wangcya, 20150506
					'start_date'=>$startDate,
					'end_date'=>$endDate,
					'business_type'=>$businessType,
					'fading'=>$fading,
					'product_code'=>$product_code,//add by wangcya, 20150110
					'insurer_code'=>$insurer_code,
					'insurer_name'=>$insurer_name,
					'partner_code'=>$partner_code,//新增加的合作伙伴代码
				    'policy_status'=>'saved',//保单状态
					'dateline'=> $_SGLOBAL['timestamp'],
			        'cart_insure_id'=> $cart_insure_id, //start add by wangcya, for bug[193],能够支持多人批量投保
			        'cast_policy_no'=> $cast_policy_no,
			        'insured_num' => $global_info['insured_num'],//added by zhangxi, 20150508,增加被保险人个数参数
			        'policy_amount'=>$policy_amount,//added by zhangxi,20150618
					);

	//add platformid by dingchaoyang 2014-12-19
	$ROOT_PATH_= str_replace ( 'baoxian/source/function_baoxian.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
	include_once ($ROOT_PATH_ . 'api/EBaoApp/platformEnvironment.class.php');
	$platform_id = PlatformEnvironment::getPlatformID();
	if ($platform_id){
		$policy_attr['platform_id'] = $platform_id;
	}
	//end by dingchaoyang 2014-12-19
	
	//ss_log("--before policy_id: ".$policy_id);
	$policy_attr = saddslashes($policy_attr);//add by wangcya , 20141211,for sql Injection
	$policy_id = inserttable('insurance_policy', $policy_attr, 1);
	
	//ss_log("--after policy_id: ".$policy_id);
	

	////////////增加多个层级////////////////////////////////////////////
	foreach($list_subjectinfo AS $key=>$value_subjectinfo)
	{
		$subjectinfo = $value_subjectinfo;
		
		$list_product_id = $subjectinfo['list_product_id'];//多个产品列表
		$list_assured = $subjectinfo['list_assured'];//多个被保险人
	
		$policy_subject_arr = array(
				'policy_id'=>$policy_id,
			);
	
		//ss_log("policy_id ".$policy_id." uid: ".$insurant_uid);
		$policy_subject_arr = saddslashes($policy_subject_arr);//add by wangcya , 20141211,for sql Injection
		$policy_subject_id = inserttable('insurance_policy_subject', $policy_subject_arr , true );
	
		//////////////////该层级下面增加多个产品/////////////////////////////////
		foreach($list_product_id AS $key1=>$value_product)
		{
			$product_id = intval($value_product);
			
			$policy_subject_product_arr = array(
					'policy_subject_id'=>$policy_subject_id,
					'product_id'=>$product_id,
					'influencingfactor_id' =>$influencingfactor_id,//added by zhangxi, 20150514, 人保财险旅游，获取影响因子id
					);
			
			$setarr = saddslashes($policy_subject_product_arr);//add by wangcya , 20141211,for sql Injection
			inserttable('insurance_policy_subject_products', $setarr);
			
		}
		
		//added by zhangxi,20141211, 对于y502产品,需要增加一个表,同时
		//进行数据库表的更新操作
		//comment by zhangxi, 20150327, 对于新华人寿的少儿安心宝贝，这里也会执行
		//还有人保寿险的i健康产品也会执行
		$list_product_duty_price_id = $subjectinfo['list_duty_price_id'];
		if(!empty($list_product_duty_price_id))
		{
			foreach($list_product_duty_price_id AS $key3=>$value_product)
			{
				$product_duty_price_id = intval($value_product);
				
				$policy_subject_product_duty_attr = array(
						'policy_subject_id'=>$policy_subject_id,
						'product_duty_price_id'=>$product_duty_price_id,
						);
				
				$wheresql = "policy_subject_id='$policy_subject_id' AND product_duty_price_id='$product_duty_price_id'";
				$sql = "SELECT * FROM ".tname('insurance_policy_subject_product_duty_prices')." WHERE $wheresql";
				$query = $_SGLOBAL['db']->query($sql);
				$duty_price_info = $_SGLOBAL['db']->fetch_array($query);
				if(empty($duty_price_info['policy_subject_id']))//add new
				{
					ss_log("insert data to insurance_policy_subject_product_duty_prices");
					$policy_subject_product_duty_attr = saddslashes($policy_subject_product_duty_attr);
					inserttable('insurance_policy_subject_product_duty_prices', $policy_subject_product_duty_attr);
					ss_log("insert data to insurance_policy_subject_product_duty_prices done");
				}
				else//error
				{
					ss_log("error, policy subject duty prices info exist: policy_subject_id="
					.$duty_price_info['policy_subject_id'].",product_duty_price_id=".$duty_price_info['product_duty_price_id']);
				}
			}
			
			
			
		}
		//add ended by zhangxi, 20141211
		
		//////////////////该层级下面增加多个被保险人/////////////////////////////////
		foreach($list_assured AS $key2=>$value_assured)
		{
			//////////////////////////////////////////////////////
			$assured_certificates_type = $value_assured['certificates_type'];
			$assured_certificates_code = $value_assured['certificates_code'];
			$assured_fullname		   = $value_assured['fullname'];
			//added by zhangxi, 20150407,删除被保险人数组中的受益人数组，这样被保险人
			//信息才能够存入数据库。因为被保险人没有字段list_beneficiary
			$list_beneficiary = $value_assured['list_beneficiary'];
			unset($value_assured['list_beneficiary']);
				
			if(strlen($assured_certificates_code)>20)
			{
				ss_log(__FUNCTION__.",ERROR 证件号码太长, assured_certificates_code=".$assured_certificates_code);
				continue;
			}

			//start modify by wangcya , 20140912, 要根据姓名，证件类型和证件号码查找，当时出现了相同的投保人姓名就是这里引起的。
					
			$wheresql = "agent_uid='$agent_uid' AND fullname='$assured_fullname' AND certificates_type='$assured_certificates_type' AND certificates_code='$assured_certificates_code'";
			$sql = "SELECT * FROM ".tname('user_info')." WHERE $wheresql";
			$query = $_SGLOBAL['db']->query($sql);
			$user_info_assured = $_SGLOBAL['db']->fetch_array($query);
			
			/////////////////////////////////////////////////////////////////
			
			$user_info_assured_attr = $value_assured;//add by wangcya  20140929,不要经过中间环节过多，容易出错。
				
			/////////////////先插入被保险人的身份信息///////////////////////////
			if(empty($user_info_assured['uid']))//new
			{
				///////////////////////插入多个/////////////////////////////////////////
				ss_log(__FUNCTION__.", 插入被保险人个人信息, assured_fullname: ".$assured_fullname);
				$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
				$assuredUid1 = inserttable("user_info", $user_info_assured_attr,true);
			
				$assuredUsername1 = $assured_fullname;
				$user_info_assured['uid'] = $assuredUid1;
				ss_log(__FUNCTION__.", 插入被保险人个人信息, uid: ".$assuredUid1);
				
					
			}
			else//update
			{
				//start modify by wangcya , 20140912, 要根据姓名，证件类型和证件号码查找，当时出现了相同的投保人姓名就是这里引起的。
				ss_log(__FUNCTION__.", 更新被保险人个人信息, assured_fullname: ".$assured_fullname);
				$wheresqlarr = array(	'agent_uid'=>$agent_uid,//add by wangcya , 20141219，属于哪个代理人的
										'fullname'=>$assured_fullname,
										'certificates_type'=>$assured_certificates_type,
										'certificates_code'=>$assured_certificates_code);

				ss_log(__FUNCTION__.", 将要更新投保人的信息,姓名：".$assured_fullname." assured_certificates_code: ".$assured_certificates_code);
				ss_log(__FUNCTION__.", province: ".$value_assured[province]." province_code: ".$value_assured[province_code]);
				$user_info_assured_attr = saddslashes($user_info_assured_attr);//add by wangcya , 20141211,for sql Injection
				
				updatetable("user_info", $user_info_assured_attr, $wheresqlarr);
					
				//end modify by wangcya , 20140912, 要根据姓名，证件类型和证件号码查找，当时出现了相同的投保人姓名就是这里引起的。
				
				$assuredUid1 = $user_info_assured['uid'];//增加到列表中
				ss_log(__FUNCTION__.", 更新被保险人个人信息, uid: ".$assuredUid1);
				
			}
			/////////////////////////////////////////////////////
			//added by zhangxi, 20150327, 如果被保险人有指定的受益人信息，则也要添加到数据库, start
			//
			//$list_beneficiary = $value_assured['list_beneficiary'];
			if(!empty($list_beneficiary))
			{
				foreach($list_beneficiary AS $key_benficiary=>$val_benficiary)
				{
					$val_benficiary['policy_subject_id'] = $policy_subject_id;
					$val_benficiary['uid'] = $assuredUid1;//被保险人uid
				
					ss_log(__FUNCTION__.", insert data to insurance_policy_beneficiary_info");
					ss_log(__FUNCTION__.", var_benficiary=".var_export($val_benficiary, true));
					$policy_beneficiary_info = saddslashes($val_benficiary);
					inserttable('insurance_policy_beneficiary_info', $policy_beneficiary_info);
					ss_log("insert data to insurance_policy_beneficiary_info done");
				}
			}
			//added by zhangxi, 20150327, 如果被保险人有指定的受益人信息，则也要添加到数据库, end	
			
			
			
			$policy_subject_assured_user_arr = array(
					'policy_subject_id'=>$policy_subject_id,
					'uid'=>$assuredUid1,
			);	
			ss_log(__FUNCTION__.", policy_subject_assured_user_arr=".var_export($policy_subject_assured_user_arr,true));
			$setarr = saddslashes($policy_subject_assured_user_arr);//add by wangcya , 20141211,for sql Injection
			inserttable('insurance_policy_subject_insurant_user', $setarr);
				
		}
	}
	
	///////////////以上已经存放到了数据库中了，下面则是生成XML的保单进行投保//////////////////////
	ss_log("--will return policy_id: ".$policy_id);
	return $policy_id;
}

//added by zhangxi, 20141211
//找到层级下面的产品责任价格列表信息
function get_subject_product_duty_price_list($policy_subject_id)
{
	global $_SGLOBAL, $_SC, $space;
	////////////////////////////////////////////////////////
	$wheresql = "pspdp.policy_subject_id='$policy_subject_id'";
		
		//mod by zhangxi, 20151013,	
	//$sql = "SELECT * FROM ".tname('insurance_policy_subject_product_duty_prices')." AS spdp 
	//        INNER JOIN ".tname('insurance_product_duty_price')." AS pdp ON pdp.product_duty_price_id= spdp.product_duty_price_id 
	//		WHERE $wheresql";
	$sql = "SELECT d.duty_code,ipdp.premium,ipdp.amount,pspdp.product_duty_price_id,pspdp.policy_subject_id,ipd.product_id FROM ".tname('insurance_product_duty_price')." AS ipdp 
						INNER JOIN ".tname('insurance_policy_subject_product_duty_prices')." AS pspdp 
				        ON pspdp.product_duty_price_id= ipdp.product_duty_price_id 
				        INNER JOIN ".tname('insurance_product_duty')." AS ipd 
				        ON ipdp.product_duty_id= ipd.product_duty_id 
						INNER JOIN ".tname('insurance_duty')." AS d 
				        ON d.duty_id= ipd.duty_id 
				WHERE $wheresql";
	//ss_log($sql);
	
	//ss_log($sql);
	//echo "policy_subject_id: ".$policy_subject_id." sql: ".$sql;
	$query = $_SGLOBAL['db']->query($sql);
	$list_subject_product_duty_prices = array();
	while( $value = $_SGLOBAL['db']->fetch_array($query))
	{		
		//echo "policy_subject_id: ".$policy_subject_id." product_code: ".$value[product_code];
		$list_subject_product_duty_prices[] = $value;
	}
	
	return $list_subject_product_duty_prices;
}


////////////找到层级下面的产品id列表////////////////////////////////////////////////
function get_subject_product_list($policy_subject_id)
{
	global $_SGLOBAL, $_SC, $space;
	////////////////////////////////////////////////////////
	$wheresql = "sp.policy_subject_id='$policy_subject_id'";

	//把产品的信息都选择得到了，包括产品的价格
	$sql = "SELECT * FROM ".tname('insurance_policy_subject_products')." sp 
	        INNER JOIN ".tname('insurance_product_base')." p ON p.product_id= sp.product_id 
			INNER JOIN ".tname('insurance_product_additional')." padd ON padd.product_id=p.product_id 
			WHERE $wheresql";
	
	//ss_log($sql);
	//echo "policy_subject_id: ".$policy_subject_id." sql: ".$sql;
	$query = $_SGLOBAL['db']->query($sql);
	$list_subject_products = array();
	while( $value = $_SGLOBAL['db']->fetch_array($query))
	{		
		
		//added by zhangxi, 20150514, 
		$list_influencingfactor_id = array();
		if($value['influencingfactor_id'])
		{
			$tmpid = $value['influencingfactor_id'];
			$wheresql = "product_influencingfactor_id = '$tmpid'";
			$sql = "SELECT * FROM ".tname('insurance_product_influencingfactor')." WHERE $wheresql";
			$query = $_SGLOBAL['db']->query($sql);
			while( $single_influencingfactor_id = $_SGLOBAL['db']->fetch_array($query))
			{
				$list_influencingfactor_id[] = $single_influencingfactor_id;
			}	
			$value['list_influencingfactor_id'] = $list_influencingfactor_id;
		}
		
		
		//echo "policy_subject_id: ".$policy_subject_id." product_code: ".$value[product_code];
		$list_subject_products[] = $value;
	}
	
	return $list_subject_products;
}

//获取某一个被保险人的受益人信息
function get_subject_insurant_beneficiary($policy_subject_id,
											$uid)//被保险人uid
{
	global $_SGLOBAL;
	
	$wheresql = "pbi.policy_subject_id='$policy_subject_id' AND pbi.uid='$uid'";

	$sql = "SELECT * FROM ".tname('insurance_policy_beneficiary_info')." pbi  
			WHERE $wheresql";
	$query = $_SGLOBAL['db']->query($sql);
	
	$list_subject_beneficiary = array();
	while( $value = $_SGLOBAL['db']->fetch_array($query))
	{	
		$list_subject_beneficiary[] = $value;
	}

	return $list_subject_beneficiary;		
	
			
}

////////////找到层级下面的被保险人的id列表////////////////////////////////////////////////
function get_subject_insurant_user_list($insurer_code,//add by wangcya , 20140914, 增加厂商代码为了区分显示
		                                $policy_subject_id,
		                                $policy_attr//added by zhangxi , 20150408, 增加一个输入参数
		                               )
{
	global $_SGLOBAL,$global_attr_obj_type;
	
	//////////////start add by wangcya , 20140914,为了适应不同厂商的显示时候类型的不同//////////////////////////////////////////////
	if(isset($global_attr_obj_type[$insurer_code]['attr_certificates_type']))
	{
		$attr_certificates_type = $global_attr_obj_type[$insurer_code]['attr_certificates_type'];
		$attr_sex  = $global_attr_obj_type[$insurer_code]['attr_sex'];
	}
	else
	{
		ss_log(__FUNCTION__.", warning not set insurer_code:".$insurer_code);
	}
	
	//////////////end add by wangcya , 20140914,为了适应不同厂商的显示时候类型的不同//////////////////////////////////////////////
	
	////////////////////////////////////////////////////////
	$wheresql = "si.policy_subject_id='$policy_subject_id'";

	$sql = "SELECT * FROM ".tname('insurance_policy_subject_insurant_user')." si 
	        INNER JOIN ".tname('user_info')." ui ON ui.uid=si.uid  
			WHERE $wheresql";
	
	$query = $_SGLOBAL['db']->query($sql);
	$list_subject_insurant_users = array();
	while( $value = $_SGLOBAL['db']->fetch_array($query))
	{
		//modify yes123 2015-01-05 如果key存在，再从数组中取数据，不然微信端报错
		if($value['certificates_type'] && isset($attr_certificates_type))
		{
			$value['certificates_type_str'] = $attr_certificates_type[$value['certificates_type']];
		}
		if(isset($value['gender']) && isset($attr_sex))
		{
			$value['gender_str'] = $attr_sex[$value['gender']];
		}
		//comment by zhangxi， 这里怎么没有呢？
		if($policy_attr['beneficiary'] == 0)//不是选择的法定受益人的情况下
		{
			//added by zhangxi, 20150408, 如果有指定的受益人，则需要获取被保险人的受益人信息
			$value['list_beneficiary'] = get_subject_insurant_beneficiary($policy_subject_id, $value['uid']);	
		}
		
		$list_subject_insurant_users[] = $value;
	}

	return $list_subject_insurant_users;
}

/////////////////得到投保的信息////////////////////////////////////////////////////
function get_policy_info($policy_id)
{
	global $_SGLOBAL, $_SC, $space, $INSURANCE_PAC;

	////////////////////////////////////////////////////////////////////////////////
	ss_log("into function get_policy_info");
	//////////////得到保单信息信息///////////////////////////////////////////////////////
	if($policy_id)
	{
		
		//add yes123 2016-05-09 高危漏洞，不能查看他人保单
		$wheresql = "policy_id='$policy_id'";
		/*
		if(!$_SESSION['admin_id'])
		{
			$wheresql = "policy_id='$policy_id' AND agent_uid='$_SESSION[user_id]'";
			
		}*/
		
		$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
		ss_log("获取保单信息：".$sql);
		
		$query = $_SGLOBAL['db']->query($sql);
		$policy_attr = $_SGLOBAL['db']->fetch_array($query);
	
		$policy_id     = $policy_attr['policy_id'];
		$applicant_uid = $policy_attr['applicant_uid'];//comment by zhangxi, 20141211, 投保人uid?
		$business_type = $policy_attr['business_type'];//add by wangcya, 20140807
		$attribute_id  = $policy_attr['attribute_id'];
		$attribute_type  = $policy_attr['attribute_type'];
		/////////////////////////////////////////////////////////////////////////////
		$insurer_code     = $policy_attr['insurer_code'];//add by wangcya , 20140914, 增加厂商代码为了区分显示
		if(empty($insurer_code)&&$attribute_id)
		{//如果为空，则从中找到，然后更新到保单中

			$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE attribute_id='$attribute_id' LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$product_attribute = $_SGLOBAL['db']->fetch_array($query);
		
			$insurer_code = $product_attribute['insurer_code'];
			$insurer_name = $product_attribute['insurer_name'];
			////////////////////////////////////////////////////////////////////
			$setarr = array('insurer_code'=>$insurer_code,'insurer_name'=>$insurer_name);
			$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
			updatetable('insurance_policy',
						$setarr,
			            array('policy_id'=>$policy_id)
			            );
			
				
		}
		
		//start add by wangcya , 20141117
		$order_sn  = $policy_attr['order_sn'];//add by wangcya , 20140914, 增加厂商代码为了区分显示
		if(empty($order_sn))
		{//如果为空，则从中找到，然后更新到保单中
		
			$sql = "SELECT order_sn FROM bx_order_info WHERE policy_id='$policy_id' LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$order = $_SGLOBAL['db']->fetch_array($query);
			
			$order_sn = $order['order_sn'];
			
			////////////////////////////////////////////////////////////////////
			$setarr = array('order_sn'=>$order_sn);
			//$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
			updatetable('insurance_policy',$setarr , array('policy_id'=>$policy_id));
			$policy_attr['order_sn'] = $order_sn;
			
			
		}
		//end add by wangcya , 20141117
	
	}
	
	//////////////投保人///////////////////////////////////////////////////////////////
	if($applicant_uid)
	{
	
		if($business_type ==1) //个人信息
		{
			$wheresql = "uid='$applicant_uid'";
			$sql = "SELECT * FROM ".tname('user_info')." WHERE $wheresql LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$user_info_applicant = $_SGLOBAL['db']->fetch_array($query);
		}
		elseif($business_type ==2||
			   $business_type ==3//add by wangcya, for bug[193],能够支持多人批量投保，
				) //团体信息
		{
			$wheresql = "gid='$applicant_uid'";
			$sql = "SELECT * FROM ".tname('group_info')." WHERE $wheresql LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$user_info_applicant = $_SGLOBAL['db']->fetch_array($query);
		}
	
	}
	
	///////////////////////多个层级关系////////////////////////////////////////////
	if($policy_id)
	{
		$wheresql = "policy_id='$policy_id'";
		$sql = "SELECT * FROM ".tname('insurance_policy_subject')." WHERE $wheresql";
		$query = $_SGLOBAL['db']->query($sql);
		$list_subject = array();
		while( $value = $_SGLOBAL['db']->fetch_array($query))
		{		
			$policy_subject_id = intval($value['policy_subject_id']);
			//added by zhangxi, 20141212, 
			$value['policy_subject_id'] = $policy_subject_id;
			$value['list_subject_product']  = get_subject_product_list($policy_subject_id);
			//comment by zhangxi, 20150408, 得到被保险人信息,增加一个输入参数
			$value['list_subject_insurant'] = get_subject_insurant_user_list($insurer_code,$policy_subject_id, $policy_attr);
			//added by zhangxi, 20141211, 增加层级下的责任价格列表信息
			$value['list_subject_product_duty_price'] = get_subject_product_duty_price_list($policy_subject_id);
			$list_subject[] = $value;
			
			$policy_attr['product_name'] = $value['list_subject_product'][0]['product_name'];
		}
	
	}
	
	//start add by wangcya , 20150108，保单的附加信息
	
	if(in_array($insurer_code, $INSURANCE_PAC))//add by wangcya, 20150506
	{
		ss_log("insurer_code: ".$insurer_code);
		
		//mod by zhangxi, 20150204, 要考虑活动的附加信息获取
		if($attribute_type == "jingwailvyou"
			|| $attribute_type == "jingwailvyouhuodong")
		{
			ss_log("attribute_type: ".$attribute_type);
			
			$wheresql = "policy_id='$policy_id'";
			$sql = "SELECT * FROM ".tname('insurance_policy_pingan_otherinfo_jingwailvyou')." WHERE $wheresql LIMIT 1";
			ss_log(__FUNCTION__.$sql);
			
			$query = $_SGLOBAL['db']->query($sql);
			$policy_pingan_therinfo_jingwailvyou = $_SGLOBAL['db']->fetch_array($query);
			
			$policy_attr['period_code'] = $policy_pingan_therinfo_jingwailvyou['period_code'];
			$policy_attr['outgoingPurpose'] = $policy_pingan_therinfo_jingwailvyou['outgoingPurpose'];
			$policy_attr['destinationCountry'] = $policy_pingan_therinfo_jingwailvyou['destinationCountry'];
			$policy_attr['schoolOrCompany'] = $policy_pingan_therinfo_jingwailvyou['schoolOrCompany'];
		}
		//added by zhangxi, 20150731, 增加Y502的处理
		elseif($attribute_type == "Y502")
		{
			//$TMP_PATH= str_replace ( 'baoxian/source/function_baoxian.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
			include_once(S_ROOT1. '../../oop/classes/PACGroupInsurance.php');
	    	$pacGroupInsurance = new PACGroupInsurance();
	    	//mod by zhangxi, 20150731, 支持多层级产品信息的显示
	    	$list_subject_ids = $pacGroupInsurance->get_duty_price_ids_by_policy_id_multi_subjects($policy_id);
	    	foreach($list_subject_ids as $key=>$list_duty)
	    	{
	    		$duty_price_ids=implode(',',$list_duty);
	    		ss_log(__FUNCTION__." duty_price_ids:".$duty_price_ids);
	    		$list_product_duty_price = $pacGroupInsurance->get_user_choosen_product_price_list($duty_price_ids);
	    		$list_subject[$key]['list_product_duty_price'] = $list_product_duty_price;
	    	}
		}
		
		//start add by wangcya, 20150202
		$wheresql = "policy_id='$policy_id'";
		$sql = "SELECT * FROM ".tname('insurance_policy_additional')." WHERE $wheresql LIMIT 1";
		ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
		$policy_additional = $_SGLOBAL['db']->fetch_array($query);
		if($policy_additional)//有的可能没找到
		{
			$policy_attr['PA_RSLT_CODE'] = $policy_additional['PA_RSLT_CODE'];
			$policy_attr['PA_RSLT_MESG'] = $policy_additional['PA_RSLT_MESG'];
			$policy_attr['validateCode'] = $policy_additional['validateCode'];
		}
		else
		{
			ss_log("get insurance_policy_additional fail: ".$policy_id);
		}
		
		//end add by wangcya, 20150202
	}
	elseif( $insurer_code == "HTS")
	{
		global $attr_purpose_huatai;
		
		ss_log("insurer_code: ".$insurer_code);
		if($attribute_type == "jwlvyou")
		{
			ss_log("attribute_type: ".$attribute_type);
				
			$wheresql = "policy_id='$policy_id'";
			$sql = "SELECT * FROM ".tname('insurance_policy_huatai_yiwai_otherinfo')." WHERE $wheresql LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$policy_pingan_therinfo_jingwailvyou = $_SGLOBAL['db']->fetch_array($query);
				
			$policy_attr['purpose'] = $attr_purpose_huatai[$policy_pingan_therinfo_jingwailvyou['purpose']];
			$policy_attr['destination_country'] = $policy_pingan_therinfo_jingwailvyou['destination_country'];
			$policy_attr['visacity'] = $policy_pingan_therinfo_jingwailvyou['visacity'];
		}
	}
	//added by zhangxi, 20150325, 新华人寿保单的附加信息获取
	elseif($insurer_code == "NCI")
	{
		ss_log(__FUNCTION__.", insurer_code: ".$insurer_code);
		if($attribute_type == "shaoer")
		{
			$wheresql = "policy_id='$policy_id'";
			$sql = "SELECT * FROM ".tname('insurance_policy_xinhua_renshou_additional_info')." WHERE $wheresql LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$additional_info = $_SGLOBAL['db']->fetch_array($query);
				
			$policy_attr['partPayMode'] = $additional_info['partPayMode'];
			$policy_attr['partPayBankCode'] = $additional_info['partPayBankCode'];
			$policy_attr['partPayAcctNo'] = $additional_info['partPayAcctNo'];
			$policy_attr['partPayAcctName'] = $additional_info['partPayAcctName'];
			$policy_attr['serviceOrgCode'] = $additional_info['serviceOrgCode'];
			$policy_attr['payPeriod'] = $additional_info['payPeriod'];
			$policy_attr['payPeriodUnit'] = $additional_info['payPeriodUnit'];
			$policy_attr['payMode'] = $additional_info['payMode'];
			$policy_attr['insPeriod'] = $additional_info['insPeriod'];
			$policy_attr['insPeriodUnit'] = $additional_info['insPeriodUnit'];
			
		}
	}
	//added by zhangxi, 20150514, 人保财险，境外旅游的附加信息
	elseif($insurer_code == "EPICC")
	{
		ss_log(__FUNCTION__.", insurer_code: ".$insurer_code);
		if($attribute_type == "jingwailvyou")
		{
			$wheresql = "policy_id='$policy_id'";
			$sql = "SELECT * FROM ".tname('insurance_policy_epicc_otherinfo_lvyou')." WHERE $wheresql LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$additional_info = $_SGLOBAL['db']->fetch_array($query);
			if(!empty($additional_info))
			{
				$policy_attr['destinationCountry'] = $additional_info['destinationCountry'];	
			}	
			
		}
	}
	elseif($insurer_code == "picclife")
	{
		if($attribute_type == "ijiankang"
		||$attribute_type == "BWSJ"
		||$attribute_type == "TTL"
		||$attribute_type == "KLWYLQX")
		{
			$policy_attr = get_shouxian_additional_info($policy_id, $policy_attr);
		}
	}
	//added by zhangxi, 20150609, 增加太平洋货运险的处理
	elseif($insurer_code == "CPIC_CARGO")
	{
		
		$policy_attr = get_cpic_cargo_additional_info($policy_id, $policy_attr,$attribute_type);

	}
	//end add by wangcya , 20150108，保单的附加信息
	
	/////////////////////////////////////////////////////
	$ret = array(
			'policy_arr'=>$policy_attr,
			'user_info_applicant'=>$user_info_applicant,
			'list_subject'=>$list_subject,
			);
	
	return $ret;
	
}

//start add by wangcya, 20150106, 得到一批的投保单
function get_policy_by_group_id($cart_insure_id)
{
	global $_SGLOBAL, $_SC, $space;
	///////////////////////////////////////////////////////////////////
	ss_log("into function get_policy_by_group_id");
	
	
	
	//
	//$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE cart_insure_id='$cart_insure_id'";
	$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE cart_insure_id='$cart_insure_id' AND agent_uid='$_SESSION[user_id]'";
	ss_log(__FUNCTION__.",get_policy_by_group_id:".$sql);
	
	$query = $_SGLOBAL['db']->query($sql);
	
	$policy_list = array();
	while($value = $_SGLOBAL['db']->fetch_array($query))
	{
		$policy_id = $value['policy_id'];
		ss_log(__FUNCTION__.",ready to get in get_policy_info");
		$policy_list[] = get_policy_info($policy_id);//得到保单信息
	}
	

	return $policy_list;
}
//end add by wangcya, 20150106, 得到一批的投保单


function add_goods($attribute_id, $attribute_name){
	$goods_id = bx_inserttable('goods',array('goods_name'=>$attribute_name,'tid'=> $attribute_id,'cat_id'=>12) , 0);
}

function query_policy_all($policy_id)
{
	global $_SGLOBAL;
	ss_log(__FUNCTION__.", start query policy file");
	$result_attr = query_policy($policy_id);
ss_log(__FUNCTION__.",ready to get in get_policy_info");
	$ret = get_policy_info($policy_id);//得到保单信息
	$policy_attr = $ret['policy_arr'];
	//$user_info_applicant = $ret['user_info_applicant'];
	//$list_subject = $ret['list_subject'];//多层级一个链表
	$attribute_id = $policy_attr['attribute_id'];
	$wheresql = "attribute_id='$attribute_id'";
	$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$product_attribute_arr = $_SGLOBAL['db']->fetch_array($query);
	$insurer_code = $product_attribute_arr['insurer_code'];
	
	if($insurer_code == 'CPIC_CARGO' && isset($result_attr['retcode']) && $result_attr['retcode']==0)
	{
		ss_log(__FUNCTION__.", cpic cargo will execute function withdraw_order");
	
		require_once (S_ROOT . '../includes/init.php');
		$path_lib_order = S_ROOT.'../includes/lib_order.php';
		
		ss_log(__FILE__.", ".$path_lib_order);
		include_once($path_lib_order);
		
		ss_log(__FILE__." will withdraw_order");
		withdraw_order($policy_id);//
		ss_log(__FILE__." after withdraw_order");
	}
	


	ss_log(__FILE__." will before json_encode");	
	ss_log(__FILE__." result code: ".$result_attr['retcode']);
	ss_log(__FILE__." result message: ".$result_attr['retmsg']);
	
	//ss_log("before json_encode: ".print_r($result_attr));
	ss_log(__FILE__." json_encode: ".json_encode($result_attr));
	//add by dingchaoyang 2014-12-2
	//响应json数据到客户端
	$ROOT_PATH_= str_replace ( 'baoxian/source/function_baoxian.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
	include_once ($ROOT_PATH_ . 'api/EBaoApp/eba_adapter.php');
	EbaAdapter::responseCancelPolicy($result_attr);
	//end add by dingchaoyang 2014-12-2
	
	
	return $result_attr;
}
//查询电子保单
function query_policy($policy_id)
{
	global $_SGLOBAL, $INSURANCE_PAC;
ss_log(__FUNCTION__.",ready to get in get_policy_info");
	////////////上面把订单保存后，下面进行投保的工作///////////////////////////////////////
	$ret = get_policy_info($policy_id);//得到保单信息

	$policy_attr = $ret['policy_arr'];
	$user_info_applicant = $ret['user_info_applicant'];
	$list_subject = $ret['list_subject'];//多层级一个链表


	/////////////////////////////////////////////////////////////
	$attribute_id = $policy_attr['attribute_id'];

	$wheresql = "attribute_id='$attribute_id'";
	$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$product_attribute_arr = $_SGLOBAL['db']->fetch_array($query);

	$insurer_code = $product_attribute_arr['insurer_code'];
	$attribute_type = $product_attribute_arr['attribute_type'];
	////////////////////////////////////////////////////////////////////////
	/////////////////每个公司的产品的投保都不太一样，或者每个产品不太一样，则需要区分开
	if(in_array($insurer_code, $INSURANCE_PAC))//add by wangcya, 20150506
	{
		$result_attr = query_policyfile_pingan_yiwai($policy_id);
			

	}
	elseif($insurer_code == "CPIC_CARGO")
	{
		//前端的查询操作用异步还是同步呢？必须得用同步呀，因为查询万了之后的返回值要用来判断返回值是什么？
		//如果查询到了批单，或者保单，如果是批单，比如注销，则还有注销成功的后续处理的。
		$ret_code = $policy_attr['ret_code'];
		$type = ($ret_code == 7) ? 'insure_accept':'endorseno';
		$result_attr = query_policyfile_cpic_cargo($policy_attr,false,false, $type);
		if ($result_attr['retcode'] == 0 && $type == 'endorseno') //success，批单成功，暂时只考虑注销的批改情况
		{

			ss_log(__FUNCTION__." will update policy status to canceled, policy_id: ".$policy_id);
			/////////////一旦注销，则不能再次投保了，需更改该订单状态//////////////////////
			$policy_status = 'canceled';
			//修改订单的支付状态和订单状态
			$setarr = array('policy_status'=>$policy_status,
							'pay_status'=>0,//
							'ret_code'=>$result_attr['retcode'],
							'ret_msg'=>$result_attr['retmsg']);
			$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
			updatetable(	'insurance_policy',
							$setarr	,
							array('policy_id'=>$policy_id)
						);
			ss_log(__FUNCTION__." retmsg: ".$result_attr['retmsg']);

		}
		else
		{
	
			ss_log("withdraw_policy fail!,so not into function withdraw_order!");
			
			
			ss_log(__FUNCTION__." withdraw fail, retmsg: ".$result_attr['retmsg']);
			
			//保存该保单的返回原因
			$setarr = array('ret_code'=>$result_attr['retcode'],
							'ret_msg'=>$result_attr['retmsg']);
			$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
			updatetable(
						'insurance_policy',	
						$setarr,
						array('policy_id'=>$policy_id)
						);
			
		}
	}
	elseif($insurer_code == "TBC01")//太平洋
	{


	}
	elseif( $insurer_code =="HTS")//华泰保险
	{

	}


	return $result_attr;
}


//注销电子保单文件
function withdraw_policy($policy_id)
{
	global $_SGLOBAL, $INSURANCE_PAC;
	global $ARR_INS_COMPANY_NAME;
	///////////////////////////////////////////////////////////////////
	$type = isset($_REQUEST['type'])?$_REQUEST['type']:"";
	ss_log("in function withdraw_policy, policy_id: ".$policy_id.",type:".$type);
	////////////上面把订单保存后，下面进行投保的工作///////////////////////////////////////
	ss_log(__FUNCTION__.",ready to get in get_policy_info");
	$ret = get_policy_info($policy_id);//得到保单信息
	
	$policy_attr = $ret['policy_arr'];
	$user_info_applicant = $ret['user_info_applicant'];
	$list_subject = $ret['list_subject'];//多层级一个链表
	////////////////////////////////////////////////////////////////
	if($type!='surrender' && time()>=strtotime($policy_attr['start_date']))
	{
		$retcode = 110;
		$retmsg = "注销电子保单失败，原因：您当前的保单已经生效！";
		
		ss_log($retmsg);
		
		$result_attr = array('retcode'=>$retcode,
				          'retmsg'=>$retmsg
							);
		
	
		return $result_attr;
	}
	
	//退保要求保单必须是已经生效
	if($type =='surrender' && time()< strtotime($policy_attr['start_date']))
	{
		$retcode = 110;
		$retmsg = "不能退保，原因：您当前的保单还没到生效时间！";
	
		ss_log($retmsg);
	
		$result_attr = array('retcode'=>$retcode,
				'retmsg'=>$retmsg
		);
	
	
		return $result_attr;
	}
	
	//退保要求保单必须是在保期间
	if($type =='surrender' && time()> strtotime($policy_attr['end_date']))
	{
		$retcode = 110;
		$retmsg = "不能退保，原因：已过保！";
	
		ss_log($retmsg);
	
		$result_attr = array('retcode'=>$retcode,
				'retmsg'=>$retmsg
		);
	
	
		return $result_attr;
	}
	
	
	
	///////////////////////////////////////////////////////////////////////////////
	
	
	
	if(	$policy_attr['pay_status']!='2' ||
			$policy_attr['policy_status']!='insured'
	)
	{
		ss_log("withdraw_policy fun pay_status： ".$policy_attr['pay_status'].",policy_status:".$policy_attr['policy_status']);
		
		$retcode = 110;
		$retmsg = "订单状态不正确，无法注销电子保单！";
		
		ss_log($retmsg);
		
		$result_attr = array('retcode'=>$retcode,
				'retmsg'=>$retmsg
		);
		
		
		return $result_attr;
		
	}
	
	/////////////////////////////////////////////////////////////
	$attribute_id = $policy_attr['attribute_id'];
	
	$wheresql = "attribute_id='$attribute_id'";
	$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql LIMIT 1";

	ss_log(__FUNCTION__.", ".$sql);
	
	$query = $_SGLOBAL['db']->query($sql);
	$product_attribute_arr = $_SGLOBAL['db']->fetch_array($query);
	
	$insurer_code = $product_attribute_arr['insurer_code'];
	$attribute_type = $product_attribute_arr['attribute_type'];
	////////////////////////////////////////////////////////////////////////
	ss_log(__FUNCTION__.", insurer_code: ".$insurer_code);
	/////////////////每个公司的产品的投保都不太一样，或者每个产品不太一样，则需要区分开
	
	if($type!='surrender')
	{
		ss_log(__FUNCTION__." 准备注销保单");
		
		if($insurer_code == "PAC"
		||$insurer_code == "PAC01"
		||$insurer_code == "PAC02"
		||$insurer_code == "PAC03"
		||$insurer_code == "PAC05")//mod by zhangxi, 20150806, 平安大连Y502
		{
			
			$product_code = $list_subject[0]['list_subject_product'][0]['product_code'];//add by wangcya, 20150109
			ss_log("product_code: ".$product_code);
			
			$result_attr = withdraw_policyfile_pingan_yiwai($policy_id,$product_code);
				
		
		}
		elseif($insurer_code == "PAC04")
		{
			$result_attr = withdraw_policyfile_pingan_property($policy_id);
		}
		elseif($insurer_code == "TBC01")//太平洋
		{
			$result_attr = withdraw_policyfile_taipingyang_yiwai($policy_attr,
										 						 $user_info_applicant
																);
			
		
		}
		//added by zhangxi, 20150625, 太平洋保险，天津分公司财产险注销
		elseif($insurer_code == $ARR_INS_COMPANY_NAME['str_cpic_tj_property'])//太平洋
		{
			$result_attr = withdraw_policyfile_cpic_tj_property($policy_attr,
										 						 $user_info_applicant
																);
			
		
		}
		elseif( $insurer_code =="HTS")//华泰保险
		{
			$result_attr = withdraw_policy_huatai_luyou($policy_id);
		}
		//added by zhangxi, 20141226, 增加华安产品注销处理
		elseif($insurer_code =="SINO")
		{
			$result_attr = withdraw_policy_huaan($policy_id,$user_info_applicant);
		}
		//added by zhangxi, 20150410, 新华产品注销操作
		elseif($insurer_code =="NCI")
		{
			$result_attr = withdraw_policy_xinhua($policy_id,$user_info_applicant);
		}
		//added by zhangxi, 20150410, 人保财险注销操作
		elseif($insurer_code =="EPICC")
		{
			$result_attr = withdraw_policy_epicc($policy_id,$user_info_applicant);
		}
		elseif($insurer_code =="picclife")
		{
			$result_attr = withdraw_policy_picclife($policy_id,$user_info_applicant);
		}                        
		elseif($insurer_code == "CPIC_CARGO")
		{
			$result_attr = withdraw_policy_cpic_cargo($policy_id,$user_info_applicant);
		}
		else//added by zhangxi, 20151204, 新增的保险公司的注销，使用回调的方式来处理
		{
			 $function_name = "withdraw_policy_$insurer_code";
			 
			 if(function_exists($function_name))
			 {
	     		 $result_attr = call_user_func_array($function_name, array($policy_id,$user_info_applicant));															
			 }
			 else
			 {
			 	$result_attr['retcode'] = 110;
			 	$result_attr['retmsg'] = "注销入口不存在，注销失败！";
			 	
			 }
	
		}
		
		
		$policy_status = 'canceled';
		
	}
	else
	{
		ss_log(__FUNCTION__.", will surrender, 准备退保 ");
		$policy_status = 'surrender';
		$result_attr['retcode']=0;
	}
	///////////////////////////////////////////////////////////////////
	$retcode = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
	
	if(isset($retcode)&&//有值
	   $retcode == 0
		)
	{
		ss_log(__FUNCTION__." will update policy status to canceled, policy_id: ".$policy_id);
		/////////////一旦注销，则不能再次投保了，需更改该订单状态//////////////////////
		$retmsg = addslashes($retmsg);
		
		
		//修改订单的支付状态和订单状态
		$setarr = array('policy_status'=>$policy_status,
						'pay_status'=>0,//add by wangcya, 20150114 for bug[115], 后台一定有一个定时任务，对那些已经付款的订单对应的没成功投保的保单进行投保。
						'ret_code'=>$retcode,
						'ret_msg'=>$retmsg);
		
		$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable(	'insurance_policy',
						$setarr	,
						array('policy_id'=>$policy_id)
					);
		ss_log("retmsg: ".$retmsg);

	}
	else
	{
		ss_log(__FUNCTION__." withdraw fail, retmsg: ".$retmsg);
		
		//start add by wangcya , 20140926, 保存该保单的返回原因
		$retmsg = addslashes($retmsg);
		
		$setarr = array('ret_code'=>$retcode,'ret_msg'=>$retmsg);
		$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable(
			'insurance_policy',	
			$setarr,
			array('policy_id'=>$policy_id)
			);
		//end add by wangcya , 20140926, 保存该保单的返回原因
	}
	

	return $result_attr;	
}


//start add by wangcya , 20141213,发送信息到救援公司
//注销电子保单文件
function rescue_policy($policy_id)
{
	global $_SGLOBAL;

	//////////////////////////////////////////////////////////////////////////////
	ss_log("in function rescue_policy");
	////////////上面把订单保存后，下面进行投保的工作///////////////////////////////////////
	$result_attr = rescue_policy_taipingyang($policy_id);
	return $result_attr;
	
}
//end add by wangcya , 20141213,发送信息到救援公司
//added by zhangxi, 20150612, 封装一下
function get_policy_file_name($insurer_code, $base_dir, $policy_no)
{
	global $_SGLOBAL, $INSURANCE_PAC;
	global $ARR_INS_COMPANY_NAME;
	$pdf_filename = '';
	if(in_array($insurer_code, $INSURANCE_PAC))//add by wangcya, 20150506
	{
		$pdf_filename = $base_dir."xml/message/".$policy_no."_pingan_policy.pdf";
	}
	elseif($insurer_code == "TBC01")//太平洋
	{
	
		$pdf_filename = $base_dir."xml/message/".$policy_no."_taipingyang_policy.pdf";
	
	}
	elseif( $insurer_code =="HTS")//华泰保险
	{
		$pdf_filename = $base_dir."xml/message/".$policy_no."_huatai_policy.pdf";
	}
	//added by zhangxi, 20141230, 增加华安保险获取电子保单功能支持
	elseif( $insurer_code =="SINO")
	{
		$pdf_filename = $base_dir."xml/message/".$policy_no."_huaan_policy.pdf";
	}
	elseif( $insurer_code =="CHINALIFE")
	{
		$pdf_filename = $base_dir."xml/message/".$policy_no."_chinalife_policy.pdf";
	}
	
	elseif( $insurer_code =="NCI")
	{
		$pdf_filename = $base_dir."xml/message/".$policy_no."_xinhua_policy.pdf";
	}
	elseif( $insurer_code =="picclife")
	{
		$pdf_filename = $base_dir."xml/message/".$policy_no."_picclife_policy.pdf";
	}
	elseif( $insurer_code =="EPICC")
	{
		$pdf_filename = $base_dir."xml/message/".$policy_no."_epicc_policy.pdf";
	}
	elseif( $insurer_code =="CPIC_CARGO")
	{
		$pdf_filename = $base_dir."xml/message/".$policy_no."_cpic_cargo_policy.pdf";
	}
	elseif( $insurer_code =="SSBQ")
	{
		$pdf_filename = $base_dir."xml/message/".$policy_no."_ssbq_policy.pdf";
	}
	elseif( $insurer_code ==$ARR_INS_COMPANY_NAME['str_cpic_tj_property'])
	{
		$pdf_filename = $base_dir."xml/message/".$policy_no."_cpic_tj_property_policy.pdf";
	}
	return $pdf_filename;
	
}

//added by zhangxi, 20150615, 批量压缩下载电子保单功能
function download_ziped_policy_files($list_policy_ids,
									$form_op="client_op")
{
	global $_SGLOBAL;
	
	ss_log("into ".__FUNCTION__);
	////////////上面把订单保存后，下面进行投保的工作///////////////////////////////////////
	$arr_policy_id = explode(',', $list_policy_ids);
	ss_log(__FUNCTION__.", arr_policy_id=".var_export($arr_policy_id, true));
	$order_sn ='';
	$policy_file_list = array();
	foreach($arr_policy_id as $key=>$policy_id)
	{
		$ret = get_policy_info($policy_id);//得到保单信息
		if(empty($ret))
		{
			$result = 110;
			$retmsg = "获取投保信息异常";
			ss_log(__FUNCTION__.", ".$retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
			
			return $result_attr;
		}
		
		$policy_attr = $ret['policy_arr'];
		$user_info_applicant = $ret['user_info_applicant'];
		$list_subject = $ret['list_subject'];//多层级一个链表
		
		//start add by wangcya, 20141215,如果来自client端，防止操作别人的
		if($form_op == "client_op" && $_SGLOBAL['supe_uid']!=$policy_attr['agent_uid'])
		{
			ss_log(__FUNCTION__." policy_id: ".$policy_id);
			ss_log(__FUNCTION__." applicant_uid: ".$policy_attr['agent_uid']);
			ss_log(__FUNCTION__." supe_uid: ".$_SGLOBAL['supe_uid']);
			showmessage("非本人，您无权操作！");
			return array("retcode"=>1,
							"retmsg"=>"非本人，您无权进行操作");
			
		}
		//end add by wangcya, 20141215,防止操作别人的
		
		
		if(	$policy_attr['pay_status']!='2' || 
			$policy_attr['policy_status']!='insured'
		  )
		{
			ss_log(__FUNCTION__." 订单状态不正确，无法下载电子保单 ，policy_id: ".$policy_id);
			showmessage("订单状态不正确，无法下载电子保单！");
			return array("retcode"=>1,
						 "retmsg"=>"订单状态不正确，无法下载电子保单！");
		}
		
		
		/////////////////////////////////////////////////////////////
		$attribute_id = $policy_attr['attribute_id'];
		
		$wheresql = "attribute_id='$attribute_id'";
		$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$product_attribute_arr = $_SGLOBAL['db']->fetch_array($query);
		
		$insurer_code = $product_attribute_arr['insurer_code'];
		$attribute_type = $product_attribute_arr['attribute_type'];
		
		ss_log(__FUNCTION__." insurer_code: ".$insurer_code);
		
		//start badd by wangcya, 20150205//////////////////////////////////////////////////////////////////////////
		//$policy_id = $policy_attr['policy_id'];
		$policy_no		= $policy_attr['policy_no'];
		
		//added by zhangxi, 20150612    ,--------rrrr------kkkllll--------------
		$pdf_filename = get_policy_file_name($insurer_code, S_ROOT, $policy_no);
		$order_sn = $policy_attr['order_sn'];
		$policy_file_list[] = $pdf_filename;
	}
	
	ss_log(__FUNCTION__.", START CREATE OBJ ZIPARCHIVE");
	
	//
	$za = new ZipArchive(); 
	if(!$za)
	{
		ss_log(__FUNCTION__.",  CREATE OBJ ZIPARCHIVE failed");
		return array("retcode"=>"0",
				"retmsg"=>"create obj ZipArchive failed");
	}
	$zipname = S_ROOT.'xml/message/policyfiles_order_'.$order_sn.'.zip';
	$dir_name = dirname($zipname);
	
	ss_log(__FUNCTION__.", dir name=".$dir_name);
	
	chdir($dir_name);
	
	if($za->open(basename($zipname),ZIPARCHIVE::OVERWRITE))
	{
		  foreach ((array) $policy_file_list as $key => $value) 
		  {
		  		ss_log(__FUNCTION__.", file base name=".basename($value));
		  		if(file_exists($value))
		  		{
		  			$za->addFile(basename($value));
		  		}
		  		else
		  		{
		  			ss_log(__FUNCTION__.', 文件暂时不存在，可能电子保单还没有从服务器上下载，file name='.$value);
		  		}
		        
		  }
		   $za->close();
	}
	else
	{
		ss_log(__FUNCTION__.', error, open zip file error');
	}
	
	///Then download the zipped file.    
	//header('Content-Type: application/octet-stream');
	header('Content-Type: application/zip');
	//header('Content-disposition: attachment; filename='.$zipname);
	header('Content-Disposition: attachment; filename="'.basename($zipname).'"');
	//header('Content-Length: ' . filesize($zipname));
	readfile($zipname);
	//exit(0);
	return array("retcode"=>"0",
				"retmsg"=>"打包下载电子保单ok");
	
}

//获取电子保单文件
function get_policy_file(	$policy_id,
							$form_op="client_op",//来自客户端的请求
							$readfile=true//是否读取文件，当定时任务时候，不需要读取文件
							)
{
	global $_SGLOBAL, $INSURANCE_PAC;
	global$ARR_INS_COMPANY_NAME;
	ss_log("into ".__FUNCTION__);
	////////////上面把订单保存后，下面进行投保的工作///////////////////////////////////////
	$ret = get_policy_info($policy_id);//得到保单信息
	if(empty($ret))
	{
		$result = 110;
		$retmsg = "获取投保信息异常";
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		
		return $result_attr;
	}
	
	$policy_attr = $ret['policy_arr'];
	$user_info_applicant = $ret['user_info_applicant'];
	$list_subject = $ret['list_subject'];//多层级一个链表
	
	//start add by wangcya, 20141215,如果来自client端，防止操作别人的
	if($form_op == "client_op" && $_SGLOBAL['supe_uid']!=$policy_attr['agent_uid'])
	{
		ss_log("policy_id: ".$policy_id);
		ss_log("applicant_uid: ".$policy_attr['agent_uid']);
		ss_log("supe_uid: ".$_SGLOBAL['supe_uid']);
		showmessage("非本人，您无权操作！");
		
	}
	//end add by wangcya, 20141215,防止操作别人的
	
	
	if(	$policy_attr['pay_status']!='2' || 
		$policy_attr['policy_status']!='insured'
	  )
	{
		ss_log("订单状态不正确，无法下载电子保单 ，policy_id: ".$policy_id);
		showmessage("订单状态不正确，无法下载电子保单！");
	}
	
	
	/////////////////////////////////////////////////////////////
	$attribute_id = $policy_attr['attribute_id'];
	
	$wheresql = "attribute_id='$attribute_id'";
	$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$product_attribute_arr = $_SGLOBAL['db']->fetch_array($query);
	
	$insurer_code = $product_attribute_arr['insurer_code'];
	$attribute_type = $product_attribute_arr['attribute_type'];
	
	ss_log("insurer_code: ".$insurer_code);
	
	//start badd by wangcya, 20150205//////////////////////////////////////////////////////////////////////////
	//$policy_id = $policy_attr['policy_id'];
	$policy_no		= $policy_attr['policy_no'];
	
	//added by zhangxi, 20150612    ,--------rrrr------kkkllll--------------
	$pdf_filename = get_policy_file_name($insurer_code, S_ROOT, $policy_no);
	
	//comment by zhangxi, 20141202 ,xml/message目录下，根据订单号区分的电子保单保存
	ss_log(__FUNCTION__.", policy file: ".$pdf_filename);
	if (file_exists($pdf_filename))//如果这个文件已经存在，则返回给用户。
	{
		//start add by wangcya, 20150205,不直接退出，先更新状态//////////////////////////////////
		if($policy_attr['getepolicy_status']==0)
		{//已经存在，但是下载状态为0
	
			ss_log(__FUNCTION__.", 电子保单存在，更新其下载状态");
				
			$result = 0;
			$retmsg = "获取电子保单成功";
				
			$setarr = array('ret_code'=>$result,'ret_msg'=>$retmsg,
					'getepolicy_status'=>1//获取电子保单成功了
			);
				
			
			updatetable('insurance_policy',
						$setarr	,
						array('policy_id'=>$policy_id)
						);
		}
		//end add by wangcya, 20150205,不直接退出，先更新状态////////////////////////////////////
	
		if($readfile)//add by wangcya, 20150205,需要读取文件时候才读取
		{
				updatetable('insurance_policy',
						array('ret_code'=>0,
								'ret_msg'=>'获取电子保单成功',
								'getepolicy_status'=>1//获取电子保单成功了
								),
						array('policy_id'=>$policy_id)
						);
				$file_name = $policy_no."_insured.pdf";
				header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
				header('Content-type: application/pdf');
				readfile($pdf_filename);
				exit(0);
// 			}
			//end by dingchaoyang 2014-12-4 如果是手机端访问，不输出文件，直接返回文件路径
		}
		
		$result = 0;
		$retmsg = "get local policy file ok!";
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg,'retFileName'=>str_replace(S_ROOT,'',$pdf_filename)
			);//add by dingchaoyang 2014-12-4添加retfilename
	
		return $result_attr;
	}//file_exists
	//end badd by wangcya, 20150205//////////////////////////////////////////////////////////////////////////
	
	
	
	$policy_attr['readfile'] = $readfile;//保存到这里，则少传送一个参数
	////////////////////////////////////////////////////////////////////////
	/////////////////每个公司的产品的投保都不太一样，或者每个产品不太一样，则需要区分开
	if( $insurer_code =="PAC"||
	 	$insurer_code =="PAC01"||
	  	$insurer_code =="PAC02"||
	  	$insurer_code =="PAC03"||
	  	$insurer_code =="PAC05"//mod by zhangxi, 20150806, 平安大连Y502
	  )
	{
		$result_attr = get_policyfile_pingan_yiwai($pdf_filename,$policy_attr,$user_info_applicant,$list_subject);
	}
	elseif($insurer_code == "PAC04")
	{
		$result_attr = get_policyfile_pingan_property($pdf_filename,$policy_attr,$user_info_applicant,$list_subject);
	}
	elseif($insurer_code == "TBC01")//太平洋
	{
		
		$result_attr = get_policyfile_taipingyang_yiwai($pdf_filename,$policy_attr,$user_info_applicant,$list_subject);

	}
	elseif( $insurer_code =="HTS")//华泰保险
	{
		$result_attr = get_policyfile_huatai_luyou($pdf_filename,$policy_attr);
	}
	//added by zhangxi, 20141230, 增加华安保险获取电子保单功能支持
	elseif( $insurer_code =="SINO")
	{
		$result_attr = get_policyfile_huaan($pdf_filename,$policy_attr,$user_info_applicant);
	}
	//added by zhangxi, 20150325, 增加新华人寿获取电子保单的功能支持
	elseif($insurer_code =="NCI")
	{
		$result_attr = get_policyfile_xinhua($pdf_filename,$policy_attr,$user_info_applicant);
	}
	elseif($insurer_code =="EPICC")
	{
		$result_attr = get_policyfile_epicc($pdf_filename,$policy_attr,$user_info_applicant);
	}
	//added by zhangxi, 20150528, 人保寿险电子保单下载
	elseif($insurer_code =="picclife")
	{
		$result_attr = get_policyfile_picclife($pdf_filename,$policy_attr,$user_info_applicant);
	}
	elseif($insurer_code =="CPIC_CARGO")
	{	//保单没有下载的情况下，到保险公司服务器上去获取
		$result_attr = get_policyfile_cpic_cargo($pdf_filename,$policy_attr,$user_info_applicant);
	}
	//added by zhangxi, 20150630, 太平洋天津财险
	elseif($insurer_code ==$ARR_INS_COMPANY_NAME['str_cpic_tj_property'])
	{
		$result_attr = get_policyfile_cpic_tj_property($pdf_filename,$policy_attr,$user_info_applicant);
	}
	
	//start add by wangcya , 20140926, 保存该保单的返回原因
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
		
	//$retmsg = addslashes($retmsg);
	
	$setarr = array('ret_code'=>$result,'ret_msg'=>$retmsg);
	$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
	
	updatetable(	'insurance_policy',
					$setarr	,
					array('policy_id'=>$policy_id)
			   );
	
	//end add by wangcya , 20140926, 保存该保单的返回原因
	

	
	return $result_attr;	
}

//获取投保确认书
function get_toubaoqueren_policy_file($policy_id)
{
	global $_SGLOBAL, $INSURANCE_PAC;

	////////////上面把订单保存后，下面进行投保的工作///////////////////////////////////////
	$ret = get_policy_info($policy_id);//得到保单信息

	$policy_attr = $ret['policy_arr'];
	$user_info_applicant = $ret['user_info_applicant'];
	$list_subject = $ret['list_subject'];//多层级一个链表


	/////////////////////////////////////////////////////////////
	$attribute_id = $policy_attr['attribute_id'];

	$wheresql = "attribute_id='$attribute_id'";
	$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$product_attribute_arr = $_SGLOBAL['db']->fetch_array($query);

	$insurer_code = $product_attribute_arr['insurer_code'];
	$attribute_type = $product_attribute_arr['attribute_type'];
	////////////////////////////////////////////////////////////////////////
	/////////////////每个公司的产品的投保都不太一样，或者每个产品不太一样，则需要区分开

	if(in_array($insurer_code, $INSURANCE_PAC))//add by wangcya, 20150506
	{

		$product_info = $list_subject[0]['list_subject_product'][0];
		
		if($product_info['product_code']=="01097"||//A
				$product_info['product_code']=="01098"||//B
				$product_info['product_code']=="01225"||//C
				$product_info['product_code']=="01099"//安相伴少儿卡
			)
		{
			$result_attr = get_toubaoqueren_policy_file_pingan_yiwai($policy_attr);
		}

	}

	//start add by wangcya , 20140926, 保存该保单的返回原因
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];

	//end add by wangcya , 20140926, 保存该保单的返回原因

	return $result_attr;
}


//投保的时候，从xls导入被保险人信息
function post_policy_upload_file_xls($insurer_code,$product,$FILE)
{
	global $_SGLOBAL, $INSURANCE_PAC;
	global $ARR_INS_COMPANY_NAME;
	////////////上面把订单保存后，下面进行投保的工作///////////////////////////////////////
	/*
	$ret = get_policy_info($policy_id);//得到保单信息
	
	$policy_attr = $ret['policy_arr'];
	$user_info_applicant = $ret['user_info_applicant'];
	$list_subject = $ret['list_subject'];//多层级一个链表
	
	
	/////////////////////////////////////////////////////////////
	$attribute_id = $policy_attr['attribute_id'];
	
	$wheresql = "attribute_id='$attribute_id'";
	$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$product_attribute_arr = $_SGLOBAL['db']->fetch_array($query);
	
	$insurer_code = $product_attribute_arr['insurer_code'];
	$attribute_type = $product_attribute_arr['attribute_type'];
	*/
	ss_log("insurer_code: ".$insurer_code);
	/////////////////每个公司的产品的投保都不太一样，或者每个产品不太一样，则需要区分开
	
	//////////////////////////////////////////////////////////
	$allowfiletype = array('xls','xlsx');
	$ret_attr = create_temp_upload_file($FILE,$allowfiletype);
	$result = $ret_attr['ret_code'];
	//echo $result;
	//comment by zhangxi, 20141204, 文件上传成功之后，先做校验，校验完了，
	//等用户修改完不符合项后，其继续上传，直到，校验没问题了，
	//再执行excel文件的导入工作
	if($result) //如果上传文件不成功，则返回
	{
		$msg = $ret_attr['ret_msg'];
		//echo $msg;
		ss_log(__FUNCTION__."upload file error,,msg:".$msg);
		return $ret_attr;
	}
	else
	{
		$uploadfile = $ret_attr['ret_msg'];
	}
	
	ss_log(__FUNCTION__.":uploadfile: ".$uploadfile);
	//////////////////////////////////////////////////////
	/////////////////每个公司的产品的投保都不太一样，或者每个产品不太一样，则需要区分开

	if(in_array($insurer_code, $INSURANCE_PAC))//add by wangcya, 20150506
	{
		//added by zhangxi, 20141209, 平安投保，被保险人上传,和处理
		$rows = process_file_xls_pingan_insured($uploadfile, $product);
		echo json_encode($rows);
		
		exit(0);
	}
	elseif($insurer_code == "TBC01")//太平洋
	{
		//mod by zhangxi, 20141209, 修改函数名字，
		//$rows = process_file_xls_taipingyang_insured($uploadfile);
		$rows = process_file_xls_taipingyang_insured_new_template($uploadfile);
		echo json_encode($rows);
		
		exit(0);
	}
	//太平洋天津财险
	elseif($insurer_code == $ARR_INS_COMPANY_NAME['str_cpic_tj_property'])//
	{
		$rows = process_file_xls_cpic_tj_property_insured($uploadfile);
		echo json_encode($rows);
		
		exit(0);
	}
	elseif( $insurer_code =="HTS")//华泰保险
	{
		//added by zhangxi, 20150119, 华泰批量投保处理，目前华泰主要是旅游
		$rows = process_file_xls_huatai_insured($uploadfile, $product);
		echo json_encode($rows);
		
		exit(0);
	}
	elseif($insurer_code =="SINO")//华安保险的团单，和批量
	{
		$rows = process_file_xls_huaan_insured($uploadfile, $product);
		echo json_encode($rows);
		
		exit(0);
	}
	elseif($insurer_code =="EPICC")//人保财险保险的团单，和批量
	{
		$rows = process_file_xls_epicc_insured($uploadfile, $product);
		echo json_encode($rows);
		
		exit(0);
	}
	elseif($insurer_code =="picclife")//增加人保寿险
	{
		$rows = process_file_xls_picclife_insured($uploadfile, $product);
		echo json_encode($rows);
		
		exit(0);
		
	}
	
	
	
	///////////////////////////////////////////////////////////////////
	$result_attr = array();
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
	
	return $retmsg;
}
//added by zhangxi, 20151020,电子保单数据流保存
function save_policy_file($policyStream, $pdfFile, $logfile)
{
		
		//base64解码，并保存com.demon.insurance.pub.InsuranceDispatcher
		$java_class_name = "com.yanda.imsp.util.ins.cpic.traffic.CPICTrafficIns";
	 	$obj_java = create_java_obj($java_class_name);
	 	if(!$obj_java)
	 	{
	 		$result = 112;
	 		$retmsg = "create_java_obj fail!";
	 		ss_log(__FUNCTION__.$retmsg);
	 	
	 		return $result;
	 	
	 		
	 	}
	 	else//对象创建成功的情况
	 	{
	 		//0正常返回    -1未找到指定文件    -2数据传输错误
	 		//ss_log(__FUNCTION__.$policyStream);
	 		
			$ret = $obj_java->savePDF2File($policyStream,
											$pdfFile,
												$logfile
												);

			$retmsg = " get pdf ret: ".$ret;
			ss_log(__FUNCTION__.$retmsg);
			
			return $ret;
	 	}
}

//added by zhangxi, 20150428, 抽象出来的核保处理流程接口函数
function process_underWriting($insurer_code,
											$attribute_type,//
											$attribute_code,//
											$policy_attr,
											$user_info_applicant,
											$list_subject,
											$flag_policy_check_only =0)
{
	ss_log(__FUNCTION__.", get in");
	include_once(S_ROOT1.'../../includes/lib_order.php');
	$result_attr = array();
	$result_attr['retcode'] = 0;
	$policy_attr['order_sn'] = get_order_sn ();
	
	
	if($insurer_code == 'NCI')
	{
		//新华的在核保时使用的订单号，就是最终用户缴费后，投保成功使用的订单号
		$result_attr = post_policy_xinhua(
											$attribute_type,//
											$attribute_code,//
											$policy_attr,
											$user_info_applicant,
											$list_subject,
											$flag_policy_check_only);
	}
	//added by zhangxi, 20150529, 人保寿险的核保也提前
	elseif($insurer_code == 'picclife')
	{
		//核保临时使用的订单号。界面不体现，只有在后台得投保xml文件名中有体现。
		$policy_attr['order_sn'] .="TEMP"; 
		$result_attr = post_policy_picclife(
											$attribute_type,//
											$attribute_code,//
											$policy_attr,
											$user_info_applicant,
											$list_subject,
											$flag_policy_check_only);
	}
	return $result_attr;
	
}

//进行投保的动作
function post_policy($policy_id,$regen_serial =0)
{
	ss_log("into ".__FUNCTION__." policy_id: ".$policy_id);
	
	global $_SGLOBAL, $INSURANCE_PAC;
	global $ARR_INS_COMPANY_NAME;
	////////////////////////////////////////////////////////
	////////////上面把订单保存后，下面进行投保的工作///////////////////////////////////////
	if($policy_id<=0)
	{
		$result = 112;
		$retmsg = "policy_id is empty，不能投保";
		ss_log($retmsg);
		
		$result_attr = array("retcode"=>$result,
				"retmsg"=>$retmsg
		);
		
		return $result_attr;

	}
	/////////////////////////////////////////////////////////////////////
	ss_log(__FUNCTION__.",ready to get in get_policy_info");
	$ret = get_policy_info($policy_id);//得到保单信息
	
	ss_log("after function get_policy_info!");
	
	$policy_attr = $ret['policy_arr'];
	$user_info_applicant = $ret['user_info_applicant'];
	
	//echo "<pre>";print_r($ret);
	
	$list_subject = $ret['list_subject'];//多层级一个链表
	/////////////////////////////////////////////////////////////////////////
	$insurer_code = $policy_attr['insurer_code'];
	if($regen_serial)//如果邀请重新产生,针对注销掉然后重新投保的情况
	{
		$len_guid = 0;
		if($insurer_code == "HTS")
		{//如果是华泰，则最多为20个字符串。
			$len_guid = 20;
		}
		else
		{
			$len_guid = 0;
		}
		
	
		$order_num = getRandOnly_Id($len_guid);
	
		ss_log("will regen policy order_num!!!!!!, order_num: ".$order_num);
		
		//同时要更新数据库中的
		//$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable(	'insurance_policy',	array('order_num'=>$order_num),
						array('policy_id'=>$policy_id)
					);
					
		$policy_attr['order_num'] = $order_num;//add by wangcya, 20150127，必须要增加上
		
	}

	//start add by wangcya , 20141023
	if(empty($policy_attr['order_sn']))
	{
		ss_log("没有找到订单号，则在这里补充");
		$order_id = $policy_attr['order_id'];
		
		$wheresql = "order_id='$order_id'";
		$sql = "SELECT order_sn FROM bx_order_info WHERE $wheresql LIMIT 1";
		ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
		$order_info = $_SGLOBAL['db']->fetch_array($query);
		$order_sn = $order_info['order_sn'];
		if(!empty($order_sn))
		{
			ss_log("order_sn: ".$order_sn);
			//$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
			updatetable(	'insurance_policy',	array('order_sn'=>$order_sn),
			array('policy_id'=>$policy_id)
			);
			
			$policy_attr['order_sn'] = $order_sn;
		}
		else
		{
			ss_log("not find order_sn!,policy_id: ".$policy_id);
		}
		
	}
	//end add by wangcya , 20141023
	
	//start add by wangcya, 20150114 for bug[115], 后台一定有一个定时任务，对那些已经付款的订单对应的没成功投保的保单进行投保。 
	$order_id = $policy_attr['order_id'];
	
	$wheresql = "order_id='$order_id'";
	$sql = "SELECT pay_status FROM bx_order_info WHERE $wheresql LIMIT 1";
	ss_log(__FUNCTION__.", ".$sql);
	$query = $_SGLOBAL['db']->query($sql);
	$order_info = $_SGLOBAL['db']->fetch_array($query);
	$pay_status = $order_info['pay_status'];
	/*
	if($pay_status!=2)
	{
		$result = 112;
		$retmsg = "订单未支付，不能投保";
		ss_log($retmsg);
		
		$result_attr = array("retcode"=>$result,
				             "retmsg"=>$retmsg
				            );
		
		return $result_attr;
	}
	
	elseif($policy_attr['policy_status']!="payed")
	{//add by wangcya,20150202
		$result = 113;
		$retmsg = "投保单不是已支付状态，不能投保，防止重复投保";
		ss_log($retmsg);
		
		$result_attr = array("retcode"=>$result,
				"retmsg"=>$retmsg
		);
		
		return $result_attr;
	}
	*/
	if($policy_attr['pay_status']!=2)
	{
		ss_log("更新投保单的状态为已支付,policy_id: ".$policy_id." pay_status: ".$pay_status);
		updatetable("insurance_policy", array("pay_status"=>$pay_status), array("policy_id"=>$policy_id));
	}
	//end add by wangcya, 20150114 for bug[115], 后台一定有一个定时任务，对那些已经付款的订单对应的没成功投保的保单进行投保。
	
	/////////////////////////////////////////////////////////////
	$attribute_id = $policy_attr['attribute_id'];

	$wheresql = "attribute_id='$attribute_id'";
	$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$product_attribute_arr = $_SGLOBAL['db']->fetch_array($query);

	$insurer_code = $product_attribute_arr['insurer_code'];
	$attribute_type = $product_attribute_arr['attribute_type'];
	$attribute_code = $product_attribute_arr['attribute_code'];
	
	//start add by wangcya, 20150117,
	$interface_flag = $product_attribute_arr['interface_flag'];
	//mod by zhangxi, 20150319, 未对接的值为2
	if($interface_flag == 2)
	{
		$order_sn = $policy_attr['order_sn'];
		
		$result = 112;
		$retmsg = "该产品未对接，不能自动投保！order_sn: ".$order_sn." policy_id: ".$policy_id;
		ss_log($retmsg);
		
		$result_attr = array("retcode"=>$result,
				             "retmsg"=>$retmsg
				            );
		//mod by zhangxi, 20150319, 未对接的产品，保单的retcode等要更新到数据库中
		$retmsg = addslashes($retmsg);
		$setarr = array('ret_code'=>$result,'ret_msg'=>$retmsg);
		$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable(	'insurance_policy',	
		                $setarr,
						array('policy_id'=>$policy_id)
				   );
		
		return $result_attr;
	}	
	//added by zhangxi, 20150319, 手动对接方式出单
	elseif($interface_flag == 3)
	{
		$order_sn = $policy_attr['order_sn'];
		
		$result = 1000;
		$retmsg = "手工出单产品！order_sn: ".$order_sn." policy_id: ".$policy_id;
		ss_log($retmsg);
		
		$result_attr = array("retcode"=>$result,
				             "retmsg"=>$retmsg
				            );
		//$retmsg = addslashes($retmsg);
		$setarr = array('ret_code'=>$result,'ret_msg'=>'产品核保，后台手工出单中，需要一定时间，请耐心等待短信通知');
		$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable(	'insurance_policy',	
		                $setarr,
						array('policy_id'=>$policy_id)
				   );
		
		return $result_attr;
	}
	//end add by wangcya, 20150117,
	
	ss_log("insurer_code: ".$insurer_code);
	/////////////////每个公司的产品的投保都不太一样，或者每个产品不太一样，则需要区分开
	if(in_array($insurer_code, $INSURANCE_PAC))//add by wangcya, 20150506
	{
		//mod by zhangxi, 20150709, 增加平安个财的处理
		if($insurer_code == 'PAC04')
		{
			$result_attr = post_policy_pingan_property(
											$attribute_type,
											$attribute_code,
											$policy_attr,
											$user_info_applicant,
											$list_subject
											);
		}
		else
		{
			$result_attr = post_policy_pingan(
											$attribute_type,//add by wangcya, 20141213
											$attribute_code,//add by wangcya, 20141213
											$policy_attr,
											$user_info_applicant,
											$list_subject
											);
		}
		

	}
	elseif($insurer_code == "TBC01")//太平洋
	{

		$result_attr = post_policy_taipingyang_yiwai(
					$attribute_type,//add by wangcya, 20141213
					$attribute_code,//add by wangcya, 20141213
					$policy_attr,
					$user_info_applicant,
					$list_subject
		);
	}
	//added by zhangxi, 20150623, 
	elseif($insurer_code == $ARR_INS_COMPANY_NAME['str_cpic_tj_property'])//太平洋天津财险
	{

		$result_attr = post_policy_cpic_tj_property(
					$attribute_type,//add by wangcya, 20141213
					$attribute_code,//add by wangcya, 20141213
					$policy_attr,
					$user_info_applicant,
					$list_subject
		);
	}
	elseif( $insurer_code =="HTS")//华泰保险
	{

		$result_attr = post_policy_huatai(
										$attribute_type,//add by wangcya, 20141213
										$attribute_code,//add by wangcya, 20141213
										$policy_attr,
										$user_info_applicant,
										$list_subject
								);
			

	}
	//added by zhangxi, 20141222, 增加华安保险的投保处理
	elseif($insurer_code =="SINO")
	{
		$result_attr = post_policy_huaan(
				$attribute_type,//add by wangcya, 20141213
				$attribute_code,//add by wangcya, 20141213
				$policy_attr,
				$user_info_applicant,
				$list_subject
		);
			
	}
	//added by zhangxi , 20150325, 增加新华人寿的投保处理
	elseif($insurer_code =="NCI")
	{
		//mod by zhangxi,20150430 ,直接进入承保处理流程
//		$result_attr = post_policy_xinhua(
//											$attribute_type,//
//											$attribute_code,//
//											$policy_attr,
//											$user_info_applicant,
//											$list_subject);
		$policy_id = $policy_attr['policy_id'];
		$wheresql = "policy_id='$policy_id'";
		$sql = "SELECT * FROM t_insurance_policy_nci_underwriting_return WHERE $wheresql LIMIT 1";
		ss_log(__FUNCTION__.", ".$sql);
		$query = $_SGLOBAL['db']->query($sql);
		$return_data = $_SGLOBAL['db']->fetch_array($query);
		$return_data_json = gen_xinhua_policy_accept_json($return_data);
		//进行投保操作
		return post_accept_policy_xinhua($policy_id, $return_data_json );
	}
	//added by zhangxi,20150416, 人保寿险
	elseif($insurer_code =="picclife")
	{
		$result_attr = post_policy_picclife(
										$attribute_type,//
										$attribute_code,//
										$policy_attr,
										$user_info_applicant,
										$list_subject);
	}
	//added by yes123,20150416, 人保财险
	elseif($insurer_code =="EPICC")
	{
		$result_attr = post_policy_epicc(
										$attribute_type,//
										$attribute_code,//
										$policy_attr,
										$user_info_applicant,
										$list_subject);
	}
	elseif($insurer_code =="CPIC_CARGO")
	{
		$result_attr = post_policy_cpic_cargo(
										$attribute_type,//
										$attribute_code,//
										$policy_attr,
										$user_info_applicant,
										$list_subject);
	}
	elseif($insurer_code =="sinosig")
	{
		$result_attr = post_policy_sinosig(
										$attribute_type,//
										$attribute_code,//
										$policy_attr,
										$user_info_applicant,
										$list_subject);
	}
	else//added by zhangxi, 20141211, error log here
	{
		ss_log("error, unknown  insurer_code:".$insurer_code);
	}
	
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
	
	//只有是同步的时候才处理下面的
	if(!defined('USE_ASYN_JAVA'))//add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	{
		//start add by wangcya , 20140926, 保存该保单的返回原因
		
	}
	else
	{
		if(!$result)
		{//同步和异步混合的状态
			
		}
		else
		{
			$result = 0;
			$retmsg = "保险公司正在处理，请稍后查询电子保单！";
		}
	}
	//end add by wangcya , 20140926, 保存该保单的返回原因
	
	
	$retmsg = addslashes($retmsg);
	
	$setarr = array('ret_code'=>$result,'ret_msg'=>$retmsg);
	
	$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
	updatetable(	'insurance_policy',	
	                $setarr,
					array('policy_id'=>$policy_id)
			   );

	
	return $result_attr;

}
//added by zhangxi, 20150923, 暂时写一个方法，如有需要再封装成类
function post_request_to_service($protocol_type, 
									$srv_ip, 
									$srv_port, 
									$post_str,
									$url, 
									$insurer_type,
									$async)
{
		$back_str = '';
	  $content_length =strlen($post_str);
	  $post_data = "POST $url HTTP/1.0\r\n";
	  $post_data .= "Content-Type: application/json\r\n";
	  $post_data .= "User-Agent: Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.59 QQ/7.4.15203.201 Safari/537.36\r\n";
	  $post_data .= "Accept: application/json, text/javascript, */*; q=0.01\r\n";
	 
	  //$post_data .= "Accept-Encoding: gzip, deflate\r\n";
	  if($srv_port == '80' && $protocol_type == 'http')
	  {
	  	$post_data .= "Host: ".$srv_ip."\r\n";
	  	
	  }
	  else
	  {
	  	$post_data .= "Host: ".$srv_ip.":".$srv_port."\r\n";
	  }
	  
	  $post_data .= "Content-Length: ".$content_length."\r\n";
	  //$post_data .= "Connection: Keep-Alive\r\n\r\n";
	  $post_data .= "Connection: close\r\n\r\n";
	  $post_data .= $post_str;
	  $connectTimeout = 0;
	  $recvTimeout = 0;
	  $fsock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	  if(!$fsock) {
		  ss_log(__FUNCTION__.", create socket failed!!!");
	      return false;
	  }

	if($async == 'A')
	{
		//异步的处理
		$connectTimeout =10 ;
		$recvTimeout = 20;
	}
	else
	{
		//同步的处理
		$connectTimeout =10 ;
		$recvTimeout = 30;
		
	}
	//设置发送超时时间
	@socket_set_option($fsock, SOL_SOCKET, SO_SNDTIMEO, array("sec" =>$connectTimeout, "usec" => 0 ) );
	//设置接收超时时间
	@socket_set_option($fsock, SOL_SOCKET, SO_RCVTIMEO, array("sec" =>$recvTimeout, "usec" => 0 ) );
	                                    
	$ret   = socket_connect($fsock, $srv_ip, $srv_port);
	$err_num = socket_last_error();
	if(!$ret)
	{
		ss_log(__FUNCTION__.", connect server failed, srv_ip=".$srv_ip.", srv_port=".$srv_port.", error num=".$err_num);
		return array("rspCode"=>110,
						"rspMsg"=>"连接核心系统失败！");
		//exit(0);
	}
//	//看看同步和异步怎么处理,非服务器端，这个有必要吗？
//	$ret = socket_select($fd_read = array($fsock), $fd_write=array($fsock),
//	                                   $except = NULL, $connectTimeout, 0);
////	
//	if($ret != 1){
//	    #trigger_error("connect error or timeout");
//	    ss_log(__FUNCTION__.", socket_select failed!!!");
//	    @socket_close($fsock);
//	    return array("flag"=>"fail",
//				"data"=>$back_str);
//	}
	
	if(!socket_write($fsock, $post_data, strlen($post_data))) 
	{
	    ss_log(__FUNCTION__.", socket_write() failed: reason: " . socket_strerror($fsock) . "\n");
	}
	else 
	{
	    ss_log(__FUNCTION__.", 发送到服务器信息成功！\n");
	    ss_log(__FUNCTION__.", post data=".$post_data);
	    //echo "发送的内容为:<font color='red'>$post_data</font> <br>";
	}
	 
//	 while($out = socket_read($fsock, 8192)) {
//	     ss_log(__FUNCTION__.", 接收服务器回传信息成功！\n");
//	     ss_log(__FUNCTION__.", 接受的内容为:",$out);
//	     $back_str.=$out;
//	 }
	//sleep(3);
	 $out='';
	   do
	  {
		   if (false === ($out = socket_read($fsock, 8192,PHP_BINARY_READ)))
		   {
		   		$err_num = socket_last_error($fsock);
			    ss_log("SOCKET_READ_ERROR: " . socket_strerror($err_num).", err_num=".$err_num);
			    if($err_num == 11)
			    {
			    	ss_log(__FUNCTION__.",continue");

			    }
			    else
			    {
			    	ss_log(__FUNCTION__.",continue");
			    	break;	
			    }
			    
		   }
		   //ss_log(__FUNCTION__.",222 接受的内容为:".$out);
	//	   if (time() - $start_time > 1)
	//	   {
	//	    echo ("SOCKET_READ_ERROR: Timeout!!!");
	//	    $str = "";
	//	    break;
	//	   }
		   $back_str .= $out;
		  // echo $buf;
	  } while ($out != "");
	 
	 
	 //echo ss_log(__FUNCTION__.", 关闭SOCKET...\n");
	 socket_close($fsock);
	 ss_log(__FUNCTION__.",接收的完整内容为:".$back_str);
	 $str_tag = "retparam=";
	 if(FALSE == ($pos=strpos($back_str, "$str_tag")))
	 {
	 	ss_log(__FUNCTION__.",响应内容body解析错误:");
	 	
	 }
	 $rsp = substr($back_str, $pos+strlen($str_tag));
	 ss_log(__FUNCTION__.",响应body:".$rsp);
	 
	 $arr_decode = json_decode($rsp,true);
  	ss_log(__FUNCTION__.",decode array:".var_export($arr_decode, true));
	return $arr_decode;
}

//得到保单的详细信息，各个不同厂商的定义类型不一样，所以这里要进行区分。
function get_policy_info_view($policy_id, $smarty=0)
{
	global $_SGLOBAL,$global_attr_obj_type,$attr_status,$attr_business_type;
	/////////////////////////////////////////////////////////////////

	////////////上面把订单保存后，下面进行投保的工作///////////////////////////////////////
	ss_log(__FUNCTION__.",ready to get in get_policy_info");
	$ret = get_policy_info($policy_id);//得到保单信息,同时得到了附加信息
	
	////////////////////////////////////////////////////////
	
	$policy = $ret['policy_arr'];
	$user_info_applicant = $ret['user_info_applicant'];
	$list_subject = $ret['list_subject'];//多层级一个链表
	
	//////////////start add by wangcya , 20140914,为了适应不同厂商的显示时候类型的不同//////////////////////////////////////////////
		
	$insurer_code = $policy['insurer_code'];
	
	if(isset($global_attr_obj_type[$insurer_code]))
	{
		$attr_certificates_type = $global_attr_obj_type[$insurer_code]['attr_certificates_type'];
		$attr_sex  = $global_attr_obj_type[$insurer_code]['attr_sex'];
		$attr_relationship_with_insured  = $global_attr_obj_type[$insurer_code]['attr_relationship_with_insured'];
		//var_dump($attr_relationship_with_insured);
		$attr_group_certificates_type  = $global_attr_obj_type[$insurer_code]['attr_group_certificates_type'];
		$attr_company_attribute_type  = $global_attr_obj_type[$insurer_code]['attr_company_attribute_type'];
	}
	else
	{
		ss_log(__FUNCTION__." warning insurer_code=".$insurer_code." is not set!!!");
	}
	
	//////////////end add by wangcya , 20140914,为了适应不同厂商的显示时候类型的不同//////////////////////////////////////////////
	
	//start add by wangcya, 20141126,为了处理显示关系
	$insurer_code     = $policy['insurer_code'];
	if( $insurer_code =="PAC")//平安
	{//把关系颠倒过来
		$policy['show_relationship_with_insured_str'] = "与被保人关系";
	}
	else
	{
		$policy['show_relationship_with_insured_str'] = "与投保人关系";
	}
	//end add by wangcya, 20141126,为了处理显示关系
	
	
	/////////////////////////////////////////////////////////////////////////////////
	$policy['policy_status_str'] = $attr_status[$policy['policy_status']];
	$policy['business_type_str'] = $attr_business_type[$policy['business_type']];
	if(isset($attr_relationship_with_insured) 
		&&isset($attr_relationship_with_insured[$policy['relationship_with_insured']]))
	{
		//mod by zhangxi, 20150811, 针对华安学平险的特殊处理
		if($insurer_code == 'SINO' && $policy['attribute_type'] == 'xuepingxian')
		{
			$policy['relationship_with_insured_str'] = '航空意外险：本人；学生幼儿意外险：'.$attr_relationship_with_insured[$policy['relationship_with_insured']]; 
		}
		else
		{
			$policy['relationship_with_insured_str'] = $attr_relationship_with_insured[$policy['relationship_with_insured']]; //modify yes123 2014-12-30 key加引号
		}
		
	}
	$policy['dateline_str'] = date('Y-m-d H:i',$policy['dateline']);
	//mod by zhangxi, 20150522
	if($policy['beneficiary'] == 0)
	{
		$policy['beneficiary_str'] = "指定";
	}
	else
	{
		$policy['beneficiary_str'] = "法定";
	}
	
	$policy['total_premium_str'] = sprintf("%01.2f", $policy['total_premium']);
	
	////////start add by wangcya , 20141213/////////////////////////////////////////////////////////////////////////
	
	if($policy['policy_status'] == 'insured' && $policy['status_jiuyuanret'] != 'success')
	{
		$policy['policy_rescue'] = 1;
	}
	else
	{
		$policy['policy_rescue'] = 0;
	}
	
	////////end add by wangcya , 20141213/////////////////////////////////////////////////////////////////////////
	
	
	
	
	if($policy['business_type']==1)//个人
	{
		if(isset($attr_certificates_type))
		{
			$user_info_applicant['certificates_type_str'] = $attr_certificates_type[$user_info_applicant['certificates_type']];
		}	
		if(isset($attr_sex))
		{
			$user_info_applicant['gender_str'] = $attr_sex[$user_info_applicant['gender']];
		}
	}
	else
	{
		if(isset($attr_group_certificates_type))
		{
			$user_info_applicant['group_certificates_type_str'] = $attr_group_certificates_type[$user_info_applicant['group_certificates_type']];
		}
		if(isset($attr_company_attribute_type))
		{
			$user_info_applicant['company_attribute_str'] = $attr_company_attribute_type[$user_info_applicant['company_attribute']];
		}		
	}
	
	//added by zhangxi, 20150410, 应该统一增加在本函数中，关于数据的转换。
	//这样前端页面和后端信息就不用改两个地方了
	if($policy['insurer_code'] == 'NCI')
    {
    	global $attr_xinhua_nation;
		global $attr_xinhua_applicant_gender;
		global $attr_xinhua_certificates_type;
		global $attr_xinhua_relationship_with_applicant;
		global $attr_xinhua_relationship_benefit_with_applicant;
    	//policy中包含了附加信息
    	
    	//投保人信息$user_info_applicant
    	
    	//被保险人信息 list_subject
    	
    	//投保人国籍，被保险人国籍
    	require_once(S_ROOT1 . '../../oop/classes/NCI_Insurance.php');
		$nciobj = new NCI_Insurance();
		//更新相关信息显示：
		
		//通过省代码获取省份名称
		$user_info_applicant['province_code'] = $nciobj->get_province_name_by_code($user_info_applicant['province_code']);
		//通过市代码获取市名称
		$user_info_applicant['city_code'] = $nciobj->get_city_name_by_code($user_info_applicant['city_code']);		
		//通过县代码获取县名称
		$user_info_applicant['county_code'] = $nciobj->get_county_name_by_code($user_info_applicant['county_code']);		
		$user_info_applicant['business_type'] = $nciobj->get_industry_name_by_code($user_info_applicant['business_type']);		
		//通过职业代码获取职业名称
		$user_info_applicant['occupationClassCode'] = $nciobj->get_career_name_by_code($user_info_applicant['occupationClassCode']);
		
		$policy["serviceOrgCode"] = $nciobj->get_service_name_by_code($policy["serviceOrgCode"]);
		$policy["partPayBankCode"] = $nciobj->get_bank_name_by_code($policy["partPayBankCode"]);
		//国籍
		$user_info_applicant['nation_code'] = $attr_xinhua_nation[$user_info_applicant['nation_code']];
		
		
		$list_insurant = $list_subject[0]['list_subject_insurant'];
		$list_beneficiary_arr = array();
		foreach($list_insurant as $key=>$value)
		{
			$list_subject[0]['list_subject_insurant'][$key]['province_code'] = $nciobj->get_province_name_by_code($value['province_code']);
			$list_subject[0]['list_subject_insurant'][$key]['city_code'] = $nciobj->get_city_name_by_code($value['city_code']);
			$list_subject[0]['list_subject_insurant'][$key]['county_code'] = $nciobj->get_county_name_by_code($value['county_code']);
			$list_subject[0]['list_subject_insurant'][$key]['nation_code'] = $attr_xinhua_nation[$value['nation_code']];
			$list_subject[0]['list_subject_insurant'][$key]['occupationClassCode'] = $nciobj->get_career_name_by_code($value['occupationClassCode']);
			$list_subject[0]['list_subject_insurant'][$key]['business_type'] = $nciobj->get_industry_name_by_code($value['business_type']);
			//每个被保险人下都有，
			$list_beneficiary_arr[] = $value['list_beneficiary'];
		}
		if($policy['beneficiary'] == 0)//不是法定受益人，则有受益人相关信息
		{
			//暂时只用一个呗保险人的受益人
			$list_beneficiary = $list_beneficiary_arr[0];
			if(!empty($list_beneficiary))
			{
				foreach($list_beneficiary as $key=>$val)
				{
					$list_beneficiary[$key]['nativePlace'] = $attr_xinhua_nation[$val['nativePlace']];
					$list_beneficiary[$key]['sex'] = $attr_xinhua_applicant_gender[$val['sex']];
					$list_beneficiary[$key]['cardType'] = $attr_xinhua_certificates_type[$val['cardType']];
					//$list_beneficiary[$key]['cardNo'] = $val['cardNo'];
					$list_beneficiary[$key]['benefitRelation'] = $attr_xinhua_relationship_benefit_with_applicant[$val['benefitRelation']];
				}
				if($smarty != 0)
				{
					$smarty->assign('list_beneficiary', $list_beneficiary );		
				}
			}
			
		}
		
		
		//$insurant_info['province_code'] = $nciobj->get_province_name_by_code($insurant_info['province_code']);
		//$insurant_info['city_code'] = $nciobj->get_city_name_by_code($insurant_info['city_code']);
		//$insurant_info['county_code'] = $nciobj->get_county_name_by_code($insurant_info['county_code']);
		//$insurant_info['nation_code'] = $attr_xinhua_nation[$insurant_info['nation_code']];
		//$insurant_info['occupationClassCode'] = $nciobj->get_career_name_by_code($insurant_info['occupationClassCode']);
		//$insurant_info['business_type'] = $nciobj->get_industry_name_by_code($insurant_info['business_type']);
		
		
		//新华人寿增加受益人信息获取变量  $list_beneficiary
		
    }
    //added by zhangxi,20150511, 平安境外旅游，需要显示旅游地点
    elseif($policy['insurer_code'] == 'PAC01')
    {
    	if($policy['attribute_type'] == 'jingwailvyou')
    	{
    		$wheresql = "policy_id='$policy_id'";
	
			$sql = "SELECT * FROM ".tname('insurance_policy_pingan_otherinfo_jingwailvyou')." WHERE $wheresql LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$pingan_additional = $_SGLOBAL['db']->fetch_array($query);
			if(!empty($pingan_additional))
			{
				$policy['destinationCountry'] = $pingan_additional['destinationCountry'];
			}
    	}
    }
    elseif($policy['insurer_code'] == 'PAC04')
    {
    	//added by zhangxi, 20150721, 
		global $attr_buildingStructure_type_pingan_property;
		$user_info_applicant['house_type'] = $attr_buildingStructure_type_pingan_property[$user_info_applicant['house_type']];
    }
    //added by zhangxi, 20150522, 受益人信息显示，人保人寿
    elseif($policy['insurer_code'] == 'picclife')
    {
    	if($policy['attribute_type'] == 'ijiankang'
    	||$policy['attribute_type'] == 'BWSJ')
    	{
    		global $attr_picclife_Gender;					
			global $attr_picclife_GovtIDTC;							
			global $attr_picclife_RelationRoleCode;
    		
	    	//policy中包含了附加信息
	    	
	    	//投保人信息$user_info_applicant
	    	
	    	//被保险人信息 list_subject
	    	
	    	//投保人国籍，被保险人国籍
	    	require_once(S_ROOT1 . '../../oop/classes/picclife_Insurance.php');
			$nciobj = new picclife_Insurance();
			//更新相关信息显示：
				
			//通过职业代码获取职业名称
			$user_info_applicant['occupationClassCode'] = $nciobj->get_career_name_by_code($user_info_applicant['occupationClassCode']);
			
			$list_insurant = $list_subject[0]['list_subject_insurant'];
			$list_beneficiary_arr = array();
			foreach($list_insurant as $key=>$value)
			{
				$list_subject[0]['list_subject_insurant'][$key]['occupationClassCode'] = $nciobj->get_career_name_by_code($value['occupationClassCode']);
				//每个被保险人下都有，
				$list_beneficiary_arr[] = $value['list_beneficiary'];
			}
			if($policy['beneficiary'] == 0)//不是法定受益人，则有受益人相关信息
			{
				//暂时只用一个呗保险人的受益人
				$list_beneficiary = $list_beneficiary_arr[0];
				if(!empty($list_beneficiary))
				{
					foreach($list_beneficiary as $key=>$val)
					{
						$list_beneficiary[$key]['sex'] = $attr_picclife_Gender[$val['sex']];
						$list_beneficiary[$key]['cardType'] = $attr_picclife_GovtIDTC[$val['cardType']];
						//$list_beneficiary[$key]['cardNo'] = $val['cardNo'];
						$list_beneficiary[$key]['benefitRelation'] = $attr_picclife_RelationRoleCode[$val['benefitRelation']];
					}
					if($smarty != 0)
					{
						$smarty->assign('list_beneficiary', $list_beneficiary );		
					}
				}
				
			}
			
    	}
    	
		
    }
    //added by zhangxi, 20150610,为了显示而进行的转换
    elseif($policy['insurer_code'] == 'CPIC_CARGO')
    {
    	//转换显示
		global $arr_cpic_cargo_internal_insurance_type;
		global $arr_cpic_cargo_package_code;
		global $arr_cpic_cargo_internal_transport_type;
		if(isset($policy["packcode"]))
		{
			$policy["packcode"] = $arr_cpic_cargo_package_code[$policy["packcode"]];
		}
		if(isset($policy["classtype"]))
		{
			$policy["classtype"] = $arr_cpic_cargo_internal_insurance_type[$policy["classtype"]];
		}
		if(isset($policy["kind"]))
		{
			$policy["kind"] = $arr_cpic_cargo_internal_transport_type[$policy["kind"]];
		}
		
		
		
		if(isset($policy["itemcode"]))
		{
			include_once (S_ROOT1 . '../../oop/classes/CPIC_CARGO_Insurance.php');
		$obj = new CPIC_CARGO_Insurance();
		$policy["itemcode"] = $obj->get_cargo_type_by_code($policy["itemcode"]);
		//$list_insurant = $list_subject[0]['list_subject_insurant'];
		//$insurant = $list_insurant[0];
		//$policy['insurant_name'] = $insurant['fullname'];
		}
		
		
    }
	
	
	if($policy['insurer_code']=='SSBQ')
	{
		include_once(ROOT_PATH . 'baoxian/source/function_baoxian_ssbq.php');
		$all_attachment = bx_get_all_attachment($policy['policy_id']);
		$policy['all_attachment'] = $all_attachment;
		global $required_upload_file_attr,$other_file_attr,$attr_certificates_type_ssbq;
		$all_attachment_attr = array();
		$all_attachment_attr['required_upload_file_attr'] = $required_upload_file_attr;
		$all_attachment_attr['other_file_attr'] = $other_file_attr;
		$policy['all_attachment_attr'] = $all_attachment_attr;
		$policy['attr_certificates_type_ssbq'] = $attr_certificates_type_ssbq;
	}
	
		
	///////////////////////////////////////////////////////
	$ret_attr = array(
			'policy_arr'=>$policy,
			'user_info_applicant'=>$user_info_applicant,
			'list_subject'=>$list_subject,
	);
	
	return $ret_attr;
}

function get_product_id_by_code($product_code)
{
	global $_SGLOBAL;
	////////////////////////////////////////////////////////////////////////
	
	$wheresql = "product_code='$product_code'";
	$sql = "SELECT product_id FROM ".tname('insurance_product_base')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$attr_product = $_SGLOBAL['db']->fetch_array($query);
	$product_id = $attr_product['product_id'];
	return $product_id;
}

function get_order_info($order_id)
{
	global $_SGLOBAL;
	////////////////////////////////////////////////////////////////////////

	$wheresql = "order_id='$order_id'";
	$sql = "SELECT * FROM bx_order_info WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$attr_order = $_SGLOBAL['db']->fetch_array($query);
	
	return $attr_order;
}

//得到产品主页信息
function get_product_view_info(  $attribute_id,
								 $attribute_type//产品属性类型
							  )
{
	global $_SGLOBAL,$product_additional_attr_period_uint;
	////////////////////////////////////////////////////////////////////////
	/*
	$sql="SELECT * FROM t_insurance_product_base AS pb
	LEFT JOIN t_insurance_product_additional AS pa ON pa.product_id=pb.product_id
	WHERE pb.attribute_id='$attribute_id'";
	*/
	//找到这个产品属性下面的所有产品
	$sql="SELECT *,pb.product_id as product_id FROM t_insurance_product_base AS pb
	LEFT JOIN t_insurance_product_additional AS pa ON pa.product_id=pb.product_id
	WHERE pb.attribute_id='$attribute_id'";
	
	ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);
	
	$index = 0;
	$arr_ins_product_list = array();
	while ($row = $_SGLOBAL['db']->fetch_array($query))
	{
		//ss_log("product_name: ".$row['product_name']);
		//start add by wangcya , 20140802
		$row['goods_name']       = $row['product_name'];
		//$row['goods_brief']      = $row['in_description'];
		//end add by wangcya , 20140802
	
		//$premium =  $row['premium'] ;
		$row['premium'] = sprintf("%01.2f",$row['premium']);
		//echo "goods_name: ".$row['goods_name'];
		
		//start modify by waqngcya, 20150506,适应那种标准产品但是保险期间不是一年的产品
		$row['period'] = empty($row['period'])?12:intval($row['period']);
		
		$period_uint = empty($row['period_uint'])?"month":$row['period_uint'];//add by wangcya, 20150506,支持多种类型的保险期限
		$row['period_uint'] =  $product_additional_attr_period_uint[$period_uint];//对应到中文
		//end modify by waqngcya, 20150506,适应那种标准产品但是保险期间不是一年的产品
		
		$product_id = $row['product_id'];
		ss_log("product_id: ".$product_id);
		
		if($index ==0)
		{
			$product_id_select = $product_id;
		}
		
		$product_duty_price_type = $row['product_duty_price_type'];
			
		$row['product_duty_list'] = get_product_duty_list( $product_id, $product_duty_price_type );
		$row['product_influencingfactor_list'] = get_product_influencingfactor_list( $product_id, "period" );//得到产品的影响因素列表
		$row['product_influencingfactor_list_age'] = get_product_influencingfactor_list( $product_id, "age" );//得到产品的影响因素列表
		//$arr_ins_product_list[$row['product_id']]['product_id']         = $row['product_id'];
		$arr_ins_product_list[$product_id] = $row;
		
		
		
		$index++;
	}
		
	ss_log("index: ".$index);
	///////////////////////////////////////////////////////////
		
	$retattr = array('arr_ins_product_list'=>$arr_ins_product_list,
			         'product_id'=>$product_id_select,
			        );
	
	return $retattr;
}

/*根据被保险的身份证或者名字找到保单*/
function get_policy_from_assured(	$assured_fullname,
									$certificates_type,
									$certificates_code,
									$policy_status,
									$insurer_code
							    )
{
	global $_SGLOBAL;
	
	$policy_attr = array();
	//////////////////////////////////////////////////////////////
	//被保险人
	if ($assured_fullname || ($certificates_type&&$certificates_code) )
	{
		$temp_where = " 1 ";
		if($assured_fullname)
		{
			$temp_where.=" AND fullname='$assured_fullname'";
		}
		 
		if($certificates_code)
		{
			$temp_where.=" AND (certificates_type='$certificates_type' AND certificates_code='$certificates_code')";
		}
		 
		 
		$uid_by_uname_sql = "SELECT uid FROM t_user_info WHERE ".$temp_where;
		$query = $_SGLOBAL['db']->query($uid_by_uname_sql);
		ss_log($uid_by_uname_sql);
		$uids = array();
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$uids[] = $row['uid'];
		}
		
		////////////////////////////////////////////////////////////////
		$where = " 1 ";
		
		if($policy_status)
		{
			$where .= " AND policy_status='$policy_status'";
			
		}
		
		if($insurer_code)
		{
			$where .= " AND insurer_code='$insurer_code'";
				
		}
		
			
		if($uids)
		{
			$sql="SELECT policy_id FROM t_insurance_policy_subject WHERE policy_subject_id in(" .
					"SELECT policy_subject_id FROM t_insurance_policy_subject_insurant_user WHERE uid 
					 in(" .simplode($uids)."))";
			
			ss_log($sql);
			$query = $_SGLOBAL['db']->query($sql);
			
			$policy_ids = array();
			while ($row = $_SGLOBAL['db']->fetch_array($query))
			{
				$policy_ids[] = $row['policy_id'];
			}
			
			$where .= " AND policy_id in(".simplode($policy_ids).")";
		}
		else
		{
			$where .= " AND policy_id =0 ";
		}
		
		$sql = "SELECT * FROM t_insurance_policy WHERE ".$where;
		ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$policy_attr[] = $row;
		}
		
	}	
	
	return $policy_attr;
}


//add by wangcya, 20141114, 手工更新投保单的电子保单和保单号
function update_policy_pdf_no($policy_id, $policy_no, $tmp_name)
{
	global $_SGLOBAL, $INSURANCE_PAC;
	//////////////////////////////////////////////////////////////////////////
	ss_log("into function update_policy_pdf_no");
	
	$sql="SELECT order_sn,insurer_code FROM t_insurance_policy WHERE policy_id='$policy_id'";
	
	$query = $_SGLOBAL['db']->query($sql);
	$policy = $_SGLOBAL['db']->fetch_array($query);
	
	$order_sn 		= $policy['order_sn'];
	$insurer_code 	= $policy['insurer_code'];
	////////////////////////////////////////////////////////
	if(!empty($order_sn))
	{
		if(in_array($insurer_code, $INSURANCE_PAC))//add by wangcya, 20150506
		{
			$new_name = S_ROOT."xml/message/".$policy_no."_pingan_policy.pdf";
		}
		elseif( $insurer_code =="HTS")//华泰
		{
			$new_name = S_ROOT."xml/message/".$policy_no."_huatai_policy.pdf";
			
		}
		elseif($insurer_code == "TBC01")//太平洋
		{
			$new_name = S_ROOT."xml/message/".$policy_no."_taipingyang_policy.pdf";
		}
		//added by zhangxi, 20150319, 增加华安的产品支持
		elseif($insurer_code == "SINO")
		{
			$new_name = S_ROOT."xml/message/".$policy_no."_huaan_policy.pdf";
		}
		//增加新华人寿的支持，zhangxi， 20150331
		elseif($insurer_code == "CHINALIFE")
		{
			$new_name = S_ROOT."xml/message/".$policy_no."_chinalife_policy.pdf";
		}
		//诉讼保全
		elseif($insurer_code == "SSBQ")
		{
			$new_name = S_ROOT."xml/message/".$policy_no."_ssbq_policy.pdf";
		}
		///////////////////////////////////////////////////////////////////////////////
		
		$result = 0;
		if($result = @copy($tmp_name, $new_name))
		{
			@unlink($tmp_name);
		}
		elseif((function_exists('move_uploaded_file') && $result = @move_uploaded_file($tmp_name, $new_name)))
		{

		}
		elseif($result = @rename($tmp_name, $new_name))
		{
		}
		else
		{

		}


		////////////////////////////////////////////////
		
		//更改该保单状态, mod by zhangxi, 20150319, 
		//同时更新保单的返回状态，返回码和返回信息 
		$setarr = array('policy_status'=>'insured','policy_no'=>$policy_no,'ret_code'=>'1001', 'ret_msg'=>'手工出单成功');
	
		updatetable('insurance_policy',
					$setarr	,
					array('policy_id'=>$policy_id)
					);

		//start add by wangcya, 20150325,投保成功，增加对应的订单上的投保成功的个数///////////////////
		
		$sql = "UPDATE bx_order_info SET insured_policy_num=insured_policy_num+1 WHERE order_sn='$order_sn'";
		ss_log(__FUNCTION__.",更新insured_policy_num：".$sql);
		$_SGLOBAL['db']->query($sql);
		//end add by wangcya, 20150325,投保成功，增加对应的订单上的投保成功的个数///////////////////
		
		//////////////////////////////////////////////////////////////////////
		if( $insurer_code =="PAC")//平安
		{
			$setsqlarr = array(     'PA_RSLT_CODE'=>"",
					'PA_RSLT_MESG'=>"",
					'validateCode'=>"",
			);
			
			if(0)//$insurance_policy_additional['policy_id'])
			{
				$setsqlarr = saddslashes($setsqlarr);//add by wangcya , 20141211,for sql Injection
				updatetable('insurance_policy_additional', $setsqlarr, array('policy_id'=>$policy_id));
			
			}
			else
			{
				$setsqlarr['policy_id'] = $policy_id;
				
				//$setsqlarr = saddslashes($setsqlarr);//add by wangcya , 20141211,for sql Injection
				inserttable('insurance_policy_additional', $setsqlarr);
			}
		}//end 平安
	}

	//////////////////////////////////////////////////////////
	return $order_sn;
}

/*
 * author:zhangxi
 * date:20141202
 * function:通过字符格式的开始时间和结束时间，计算出之间的时间间隔
 * input：
 * 		type:字符类型，表征需要获取的时间间隔的单位类型，是day，month or year？
 * 		strtime：array类型，记录开始和结束时间的数组
 * return：array类型， 表征时间间隔的数组
 * 
 * */
 function get_interval_by_strtime($type='day', $strtime)
 {
 	if(!isset($strtime))
 	{
 		return ;
 	}
 	$arr_interval = array();
 	$interval = strtotime($strtime['end_date'])-strtotime($strtime['start_date']);
 	//echo "interval" .$interval;
 	if($interval < 0)
 	{
 		return;
 	}
 	if($type == 'day')
 	{
 		$day = intval($interval/86400);
 		$left = $interval%86400;
 		if($left)
 		{
 			$day += 1;
 		}
 		$arr_interval['type'] = $type;
 		$arr_interval['interval'] = $day;
 	}
 	elseif($type == 'month')
 	{
 		//mod by zhangxi, 20141213
 		$month_num = round($interval/(86400*30),0);
 		$arr_interval['type']= $type;
 		$arr_interval['interval'] = $month_num;
 	}
 	elseif($type == 'year')
 	{
 		//not support now
 		return ;
 	}
 	else
 	{
 		return;
 	}
 	//echo "zhx, return".$arr_interval['interval'];
 	return $arr_interval;
 }


function getIP()
{
	if (isset($_SERVER)) 
	{
		if (isset($_SERVER[HTTP_X_FORWARDED_FOR])) 
		{
			$realip = $_SERVER[HTTP_X_FORWARDED_FOR];
		} 
		elseif (isset($_SERVER[HTTP_CLIENT_IP])) 
		{
			$realip = $_SERVER[HTTP_CLIENT_IP];
		} 
		else 
		{
			$realip = $_SERVER[REMOTE_ADDR];
		}
	} 
	else 
	{
		if(getenv("HTTP_X_FORWARDED_FOR")) 
		{
			$realip = getenv( "HTTP_X_FORWARDED_FOR");
		}
		elseif (getenv("HTTP_CLIENT_IP")) 
		{
			$realip = getenv("HTTP_CLIENT_IP");
		}
		else
		{
			$realip = getenv("REMOTE_ADDR");
		}
	}
	
	return $realip;
}


//added by zhangxi, 20141211, 
//通过属性id获取到特别约定说明
function get_limit_note_by_attribute_id($attribute_id)
{
	global $_SGLOBAL;
	if($attribute_id)
	{

		$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE attribute_id='$attribute_id' LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$product_attribute = $_SGLOBAL['db']->fetch_array($query);

		//comment by zhangxi, 20141205, 特别约定信息
		if(!empty($product_attribute['limit_note']))
		{
			//特别约定
			if(defined('IS_NO_GBK'))
			{	
				$limit_note = trim($product_attribute['limit_note']);
				$limit_note = '<![CDATA['.$limit_note.']]>';
				//ss_log("before limit_note: ".$product_attribute['limit_note']);
				//$limit_note = iconv('UTF-8', 'GKB', $product_attribute['limit_note']);
				//$limit_note =  mb_convert_encoding($limit_note, "UTF-8","GBK"); //已知原编码为UTF-8, 转换为GBK
						
				ss_log("after limit_note: ".$limit_note);
			}
			else
			{
				$limit_note =  mb_convert_encoding($product_attribute['limit_note'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			}
		}
		else
		{
			$limit_note = "";
		}
	}
	
	return $limit_note;
}

/**
 * 对 MYSQL LIKE 的内容进行转义
 *
 * @access      public
 * @param       string      string  内容
 * @return      string
 */
function mysql_like_quote_my($str)
{
	return strtr($str, array("\\\\" => "\\\\\\\\", '_' => '\_', '%' => '\%', "\'" => "\\\\\'"));
}



//added by zhangxi, 20150107, 华安产品工程信息获取
function huaan_assign_project_info($smarty, $product_id, $policy_id)
{
	$product_info2 = get_product_info($product_id);
	if($product_info2['attribute_type'] == 'project')
	{
		global $_SGLOBAL;
		$wheresql = "policy_id='$policy_id'";
		$sql = "SELECT * FROM ".tname('insurance_policy_huaan_project_info')." WHERE $wheresql LIMIT 1";
		//echo $sql;
		$query = $_SGLOBAL['db']->query($sql);
		$policy_huaan_project_info = $_SGLOBAL['db']->fetch_array($query);
		
		if($policy_huaan_project_info)
		{	
			$project_info["project_name"] = $policy_huaan_project_info['project_name'];//
			$project_info["project_price"] = $policy_huaan_project_info['project_price'];//
			$project_info["project_start_date"] = $policy_huaan_project_info['project_start_date'];//
			$project_info["project_end_date"] = $policy_huaan_project_info['project_end_date'];//
			$project_info["project_total_month"] = $policy_huaan_project_info['project_total_month'];//
			$project_info["project_content"] = $policy_huaan_project_info['project_content'];
			$project_info["project_location"] = $policy_huaan_project_info['project_location'];
			$project_info["zipcode"] = $policy_huaan_project_info['zipcode'];
			$project_info["project_type"] = $policy_huaan_project_info['project_type'];
			$project_info["province_name"] = $policy_huaan_project_info['province_name'];
			$project_info["city_name"] = $policy_huaan_project_info['city_name'];	
		}
		$smarty->assign('project_info', $project_info);
		//echo "<pre>";
		//var_dump($project_info);
		//$project_info
	}
}


/**
 * 调用array_combine函数
 *
 * @param   array  $mobile_phone
 * @param   array  $content
 *
 * @return  $combined
 */
function baoxian_send_msg($mobile_phone,$msg,$local_port=0)
{
	ss_log('into function:'.__FUNCTION__);
	ss_log($msg);
	
	//modify yes123 2015-01-24 某些短信在测试化境上也要发送
	if(!$local_port){
		$local_port = $_SERVER["SERVER_PORT"];
	}
	if($local_port != 82)//82测试环境不发送短信
	{
		
		include_once(S_ROOT.'../sdk_sms.php');
		ss_log(S_ROOT.'../sdk_sms.php');
		//include_once('../sdk_sms.php');
		//global $sdk_sn,$sdk_pwd;

		$sdk_sn = "SDK-GHD-010-00014";
		$sdk_pwd = "014355";

		ss_log("sdk_sn: ".$sdk_sn);
		ss_log("sdk_pwd: ".$sdk_pwd);

		$content = iconv( "UTF-8", "gb2312//IGNORE" ,$msg);

		if($mobile_phone)
		{
			$r = strstr($mobile_phone,',');
			if($r){
				$result = post_mt($sdk_sn,$sdk_pwd,$mobile_phone,$content);
				ss_log('post_mt群发发送短信后'.$mobile_phone.",内容为:".$msg.",返回值为:".$result);
			}else{
				$result = gxmt_post($sdk_sn,$sdk_pwd,$mobile_phone,$content);
				ss_log('单发发送短信后'.$mobile_phone.",内容为:".$msg.",返回值为:".$result);
			}
				
			if($result==-4){
				writeLog();
			}

			return $result;
		}
		return 11;
	}
	else
	{
		ss_log('82测试环境不发送短信:'.$mobile_phone.",内容为:".$msg);
		return 11;

	}

}

//xls文件批量处理函数，通用行的函数
function process_file_xls_upload_common($uploadfile, $product, $data_format, $start_row=11)
{
	$fileType =  PHPExcel_IOFactory::identify($uploadfile);
	$objReader = PHPExcel_IOFactory::createReader($fileType);
	$objPHPExcel = $objReader->load($uploadfile);
	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow();           //取得总行数
	$highestColumn = $sheet->getHighestColumn(); //取得总列数
	
	$objWorksheet = $objPHPExcel->getActiveSheet();
	$highestRow = $objWorksheet->getHighestRow();
	$highestColumn = $objWorksheet->getHighestColumn();
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
	
	ss_log("highestRow=".$highestRow.",highestColumnIndex=".$highestColumnIndex);
	if($highestRow > 3000)
	{
		return array('result_code'=>'1',
						'result_msg'=>'failed , limited users exceed 3000',
						);
	}
	
    $format_arr = $data_format;
	$rows = array();
	//added by zhangxi, 20141210, 
	//根据公司excel模板文件来处理
	//模板文件1列证件类型，2列证件号，3列职业类别，4列性别，5列生日，8列EMAIL，9列联系电话
	
	for ($row = $start_row; $row <= $highestRow; $row++)
	{
		$strs_row = array();
		//$index=0;
		$normal_row_flag =1;
		
		$applicant_cert_flag = 0;
		//注意highestColumnIndex的列数索引从0开始
		for ($col = 0;$col < $highestColumnIndex; $col++)
		{
			if(in_array($col,array_keys($format_arr)))
			{
				//MOD BY ZHANGXI,20150110, 获取计算之后的值
				$value = trim($objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue());
				$strs_row[$format_arr[$col]] = strval($value);
				ss_log("index:".$format_arr[$col].$strs_row[$format_arr[$col]]);
				
				if(($format_arr[$col] == 'applicant_certificates_type') && ($strs_row[$format_arr[$col]] != '身份证'))
				{
					$applicant_cert_flag = 1;
				}
				
				if($format_arr[$col] == 'applicant_birthday'
				&& (strlen($strs_row[$format_arr[$col]]) == 5))
				{
					$n = intval(($strs_row[$format_arr[$col]] - 25569)*3600*24); //转换成1970年以来的秒数
					$strs_row[$format_arr[$col]]= gmdate('Y-m-d',$n);//格式化时间
				}
				
				if($format_arr[$col] == 'assured_name' && empty($strs_row[$format_arr[$col]]))
				{	
					//被保险人姓名为空的行，直接不取
					$normal_row_flag =0;
					break;
				}
				//added by zhangxi, 20141217,被保险人，出生日期格式校验
				elseif($format_arr[$col] == 'assured_birthday')
				{
					if(strlen($strs_row[$format_arr[$col]]) == 5)
					{
						$n = intval(($strs_row[$format_arr[$col]] - 25569)*3600*24); //转换成1970年以来的秒数
						$strs_row[$format_arr[$col]]= gmdate('Y-m-d',$n);//格式化时间
					}	
				}
				//$index++;
			}
			//$strcol .= $strs[$col]." ";
		}
		//跳过不正常的行
		if($normal_row_flag == 0)
		{
			//continue;
			break;//为了性能，直接break
		}
		
		if(!empty($strs_row))
		{
			$rows[] = $strs_row;
		}
			
	}
	ob_clean(); 
	ob_end_flush(); 
	
		$result=array('result_code'=>'0',
						'result_msg'=>'success',
						//'filepath'=>$uploadfile,
						'data'=>$rows,
						);
						
	//上传的文件，处理完成之后，是否在这里就应该删除?
	
		return $result;
}

//added by zhangxi, 20150520, 寿险附加信息处理函数
function get_shouxian_additional_info($policy_id, $policy_arr)
{
	global $_SGLOBAL;
	$wheresql = "policy_id='$policy_id'";
	$sql = "SELECT * FROM ".tname('insurance_policy_shouxian_additional_info')." WHERE $wheresql LIMIT 1";
	//echo $sql;
	$query = $_SGLOBAL['db']->query($sql);
	$policy_additional_info = $_SGLOBAL['db']->fetch_array($query);
	
	if($policy_additional_info)
	{	
		$policy_arr["partPayMode"] = $policy_additional_info['partPayMode'];//
		$policy_arr["partPayBankCode"] = $policy_additional_info['partPayBankCode'];//
		$policy_arr["partPayAcctNo"] = $policy_additional_info['partPayAcctNo'];//
		$policy_arr["partPayAcctName"] = $policy_additional_info['partPayAcctName'];//
		$policy_arr["serviceOrgCode"] = $policy_additional_info['serviceOrgCode'];//
		$policy_arr["payPeriod"] = $policy_additional_info['payPeriod'];
		$policy_arr["payPeriodUnit"] = $policy_additional_info['payPeriodUnit'];
		$policy_arr["payMode"] = $policy_additional_info['payMode'];
		$policy_arr["insPeriod"] = $policy_additional_info['insPeriod'];
		$policy_arr["insPeriodUnit"] = $policy_additional_info['insPeriodUnit'];

	}
	return $policy_arr;
}
function get_xinhua_shouxian_additional_info($policy_id, $policy_arr)
{
	global $_SGLOBAL;
	$wheresql = "policy_id='$policy_id'";
	$sql = "SELECT * FROM ".tname('insurance_policy_xinhua_renshou_additional_info')." WHERE $wheresql LIMIT 1";
	//echo $sql;
	$query = $_SGLOBAL['db']->query($sql);
	$policy_additional_info = $_SGLOBAL['db']->fetch_array($query);
	
	if($policy_additional_info)
	{	
		$policy_arr["partPayMode"] = $policy_additional_info['partPayMode'];//
		$policy_arr["partPayBankCode"] = $policy_additional_info['partPayBankCode'];//
		$policy_arr["partPayAcctNo"] = $policy_additional_info['partPayAcctNo'];//
		$policy_arr["partPayAcctName"] = $policy_additional_info['partPayAcctName'];//
		$policy_arr["serviceOrgCode"] = $policy_additional_info['serviceOrgCode'];//
		$policy_arr["payPeriod"] = $policy_additional_info['payPeriod'];
		$policy_arr["payPeriodUnit"] = $policy_additional_info['payPeriodUnit'];
		$policy_arr["payMode"] = $policy_additional_info['payMode'];
		$policy_arr["insPeriod"] = $policy_additional_info['insPeriod'];
		$policy_arr["insPeriodUnit"] = $policy_additional_info['insPeriodUnit'];
		$policy_arr["policyPassword"] = $policy_additional_info['policyPassword'];
		$policy_arr["firstPayBankCode"] = $policy_additional_info['firstPayBankCode'];
		$policy_arr["firstPayAcctNo"] = $policy_additional_info['firstPayAcctNo'];
		$policy_arr["firstPayAcctName"] = $policy_additional_info['firstPayAcctName'];
		$policy_arr["firstPayMode"] = $policy_additional_info['firstPayMode'];
		

	}
	return $policy_arr;
}
//add yes123 2015-05-25 把保单添加到购物车
function save_to_cart($cart_insure_id)
{
	$wheresqlarr = array('cart_insure_id'=>$cart_insure_id);
	$policy_status_attr = array('policy_status'=>'cart');
	updatetable("insurance_policy", $policy_status_attr, $wheresqlarr);
	return array('code'=>200,'msg'=>'保存成功！');
}

//add yes123 2015-07-24  通过险种ID获取合作伙伴代码
function get_partner_code($attribute_id=0,$user_id=0)
{
	
	$where_sql = " AND institution_id='$user_id' ";
	$sql = "SELECT partner_code FROM bx_organ_ipa_rate_config WHERE attribute_id='$attribute_id' $where_sql";
		
	ss_log(__FUNCTION__."--1:".$sql);
	$query = $_SGLOBAL['db']->query($sql);
	$partner_code = $_SGLOBAL['db']->fetch_array($query);
	if($partner_code)
	{
		return $partner_code;
	}
	
	//获取用户信息
	$sql = "SELECT user_rank,institution_id FROM bx_users WHERE user_id='$user_id'";
	$query = $_SGLOBAL['db']->query($sql);
	$user = $_SGLOBAL['db']->fetch_array($query);
	
	$sql = "SELECT rank_code FROM bx_user_rank WHERE rank_id='$user[user_rank]'";
	$rank_code_query = $_SGLOBAL['db']->query($sql);
	$rank_code = $_SGLOBAL['db']->fetch_array($rank_code_query);
	
	if($rank_code!='organization' && !$user['institution_id'])
	{
		
		return false;		
	}
	
	$res = get_partner_code($attribute_id,$user['institution_id']);
	return $res;

	
}

//处理上传的被保险人数据
function ebao_parse_post_insured_info($list_user)
{
	$list_assured = array();
	$num_index = 0;
	global $_SGLOBAL;
	foreach($list_user as $k=>$val)//遍历被保险人
	{
		$assured_info['certificates_type'] = trim($val['certificates_type']);
		$assured_info['certificates_code'] = trim($val['certificates_code']);
		$assured_info['birthday'] = trim($val['birthday']);
		$assured_info['fullname']  = trim($val['fullname']);
		$assured_info['gender']  = trim($val['gender']);
		$assured_info['mobiletelephone'] =  trim($val['mobiletelephone']);
		$assured_info['email'] =  trim($val['email']);
		
		$assured_info['relation'] =  trim($val['assured_relationship']);
		//$assured_info['certificates_type_unify'] = $attr_certificates_type_pingan_single_unify[$assured_info['certificates_type']];
		//$assured_info['gender_unify'] = $attr_sex_pingan_unify[$assured_info['gender']];
		$assured_info['type'] = 0;
		$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
		$list_assured[] = $assured_info;
		$num_index++;

		
	}//end foreach($list_user as $k=>$val)
	ss_log(__FUNCTION__.", list_assured=".var_export($list_assured,true));
	return $list_assured;
}
/**
 * 检查文件类型
 *
 * @access      public
 * @param       string      filename            文件名
 * @param       string      realname            真实文件名
 * @param       string      limit_ext_types     允许的文件类型
 * @return      string
 */
function bx_check_file_type($filename, $realname = '', $limit_ext_types = '')
{
    if ($realname)
    {
        $extname = strtolower(substr($realname, strrpos($realname, '.') + 1));
    }
    else
    {
        $extname = strtolower(substr($filename, strrpos($filename, '.') + 1));
    }

    if ($limit_ext_types && stristr($limit_ext_types, '|' . $extname . '|') === false)
    {
        return '';
    }

    $str = $format = '';

    $file = @fopen($filename, 'rb');
    if ($file)
    {
        $str = @fread($file, 0x400); // 读取前 1024 个字节
        @fclose($file);
    }
    else
    {
        if (stristr($filename, ROOT_PATH) === false)
        {
            if ($extname == 'jpg' || $extname == 'jpeg' || $extname == 'gif' || $extname == 'png' || $extname == 'doc' ||
                $extname == 'xls' || $extname == 'txt'  || $extname == 'zip' || $extname == 'rar' || $extname == 'ppt' ||
                $extname == 'pdf' || $extname == 'rm'   || $extname == 'mid' || $extname == 'wav' || $extname == 'bmp' ||
                $extname == 'swf' || $extname == 'chm'  || $extname == 'sql' || $extname == 'cert'|| $extname == 'pptx' || 
                $extname == 'xlsx' || $extname == 'docx')
            {
                $format = $extname;
            }
        }
        else
        {
            return '';
        }
    }

    if ($format == '' && strlen($str) >= 2 )
    {
        if (substr($str, 0, 4) == 'MThd' && $extname != 'txt')
        {
            $format = 'mid';
        }
        elseif (substr($str, 0, 4) == 'RIFF' && $extname == 'wav')
        {
            $format = 'wav';
        }
        elseif (substr($str ,0, 3) == "\xFF\xD8\xFF")
        {
            $format = 'jpg';
        }
        elseif (substr($str ,0, 4) == 'GIF8' && $extname != 'txt')
        {
            $format = 'gif';
        }
        elseif (substr($str ,0, 8) == "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A")
        {
            $format = 'png';
        }
        elseif (substr($str ,0, 2) == 'BM' && $extname != 'txt')
        {
            $format = 'bmp';
        }
        elseif ((substr($str ,0, 3) == 'CWS' || substr($str ,0, 3) == 'FWS') && $extname != 'txt')
        {
            $format = 'swf';
        }
        elseif (substr($str ,0, 4) == "\xD0\xCF\x11\xE0")
        {   // D0CF11E == DOCFILE == Microsoft Office Document
            if (substr($str,0x200,4) == "\xEC\xA5\xC1\x00" || $extname == 'doc')
            {
                $format = 'doc';
            }
            elseif (substr($str,0x200,2) == "\x09\x08" || $extname == 'xls')
            {
                $format = 'xls';
            } elseif (substr($str,0x200,4) == "\xFD\xFF\xFF\xFF" || $extname == 'ppt')
            {
                $format = 'ppt';
            }
        } elseif (substr($str ,0, 4) == "PK\x03\x04")
        {
            if (substr($str,0x200,4) == "\xEC\xA5\xC1\x00" || $extname == 'docx')
            {
                $format = 'docx';
            }
            elseif (substr($str,0x200,2) == "\x09\x08" || $extname == 'xlsx')
            {
                $format = 'xlsx';
            } elseif (substr($str,0x200,4) == "\xFD\xFF\xFF\xFF" || $extname == 'pptx')
            {
                $format = 'pptx';
            }else
            {
                $format = 'zip';
            }
        } elseif (substr($str ,0, 4) == 'Rar!' && $extname != 'txt')
        {
            $format = 'rar';
        } elseif (substr($str ,0, 4) == "\x25PDF")
        {
            $format = 'pdf';
        } elseif (substr($str ,0, 3) == "\x30\x82\x0A")
        {
            $format = 'cert';
        } elseif (substr($str ,0, 4) == 'ITSF' && $extname != 'txt')
        {
            $format = 'chm';
        } elseif (substr($str ,0, 4) == "\x2ERMF")
        {
            $format = 'rm';
        } elseif ($extname == 'sql')
        {
            $format = 'sql';
        } elseif ($extname == 'txt')
        {
            $format = 'txt';
        }
    }

    if ($limit_ext_types && stristr($limit_ext_types, '|' . $format . '|') === false)
    {
        $format = '';
    }

    return $format;
}

/**
 * 生成随机的数字串
 *
 * @author: weber liu
 * @return string
 */
function bx_random_filename()
{
    $str = '';
    for($i = 0; $i < 9; $i++)
    {
        $str .= mt_rand(0, 9);
    }

    return gmtime() . $str;
}
?>