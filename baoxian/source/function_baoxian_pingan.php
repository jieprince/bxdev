<?php

if(!defined('S_ROOT'))
{
	define('S_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
}

//modify yes123 2015-01-05 如果常量不存在，再定义
if(!defined('IS_NO_GBK')){ 
	define('IS_NO_GBK', 1);
}


include_once(S_ROOT.'/source/my_const.php');
include_once(S_ROOT.'/source/function_common.php');

////////////////////////////////////////////////////////////////////////////
$java_class_name_pingan = 'com.yanda.imsp.util.ins.PingAnPolicyFetcher';
$java_class_name_pingan_property = 'com.yanda.imsp.util.ins.PingAnPropertyFecher';
                    
////////////////////////////////////////////////////////////////////////////
$g_ATTRIBUTE_TYPE_PAC = array("product",
		"mproduct",
		"lvyou",
		"jingwailvyou",
		"lvyouhuodong",
		"jingwailvyouhuodong",
		"Y502"
);

//added by zhangxi, 20150205, 平安境外旅游国家，地区定义
$pingan_jingwailvyou_Schengen = array(
					"法国 FRANCE",
					"德国 GERMANY",
					"意大利 ITALY",
					"荷兰 THENETHERLANDS",
					"西班牙 SPAIN",
					"比利时 BELGIUM",
					"奥地利 AUSTRIA",
					"希腊 GREECE",	
					"葡萄牙 PORTUGAL",
					"拉脱维亚 LATVIA",
					"瑞典 SWEDEN",
					"瑞士 SWITZERLAND",
					"挪威 NORWAY",
					"丹麦 DENMARK",
					"芬兰 FINLAND",
					"冰岛 ICELAND",
					"捷克 CZECH REPUBLIC",
					"斯洛伐克 SLOVAKIA",
					"波兰 POLAND",
					"斯洛文尼亚 SLOVENIA",
					"爱沙尼亚 ESTONIA",
					"立陶宛 LITHUANIA",
					"卢森堡 LUXEMBURG",
					"马尔他 MALTA",	
					"匈牙利 HUNGARY",
					"列支敦士登 LIECHTENSTEIN",
				);
$pingan_jingwailvyou_no_Schengen = array(
					"美国 USA",
					"英国 UK",
					"加拿大 CANADA",
					"澳大利亚  AUSTRALIA",
					"日本 JAPAN",
					"韩国 KOREA",
					"马来西亚 MALAYSIA",
					"新西兰 NEW ZEALAND", 
					"新加坡  SINGAPORE",
					"泰国  THAILAND",
				);
$pingan_jingwailvyou_country = array(
					"美国 USA"=>"非申根协议国家",
					"英国 UK"=>"非申根协议国家",
					"加拿大 CANADA"=>"非申根协议国家",
					"澳大利亚  AUSTRALIA"=>"非申根协议国家",
					"日本 JAPAN"=>"非申根协议国家",
					"韩国 KOREA"=>"非申根协议国家",
					"马来西亚 MALAYSIA"=>"非申根协议国家",
					"新西兰 NEW ZEALAND"=>"非申根协议国家", 
					"新加坡  SINGAPORE"=>"非申根协议国家",
					"泰国  THAILAND"=>"非申根协议国家",
					"香港  HONG KONG"=>"非申根协议地区",
					"台湾 TAIWAN"=>"非申根协议地区",
					"澳门  MACAU"=>"非申根协议地区",
					
					"法国 FRANCE"=>"申根协议国家",
					"德国 GERMANY"=>"申根协议国家",
					"意大利 ITALY"=>"申根协议国家",
					"荷兰 THENETHERLANDS"=>"申根协议国家",
					"西班牙 SPAIN"=>"申根协议国家",
					"比利时 BELGIUM"=>"申根协议国家",
					"奥地利 AUSTRIA"=>"申根协议国家",
					"希腊 GREECE"=>"申根协议国家",	
					"葡萄牙 PORTUGAL"=>"申根协议国家",
					"拉脱维亚 LATVIA"=>"申根协议国家",
					"瑞典 SWEDEN"=>"申根协议国家",
					"瑞士 SWITZERLAND"=>"申根协议国家",
					"挪威 NORWAY"=>"申根协议国家",
					"丹麦 DENMARK"=>"申根协议国家",
					"芬兰 FINLAND"=>"申根协议国家",
					"冰岛 ICELAND"=>"申根协议国家",
					"捷克 CZECH REPUBLIC"=>"申根协议国家",
					"斯洛伐克 SLOVAKIA"=>"申根协议国家",
					"波兰 POLAND"=>"申根协议国家",
					"斯洛文尼亚 SLOVENIA"=>"申根协议国家",
					"爱沙尼亚 ESTONIA"=>"申根协议国家",
					"立陶宛 LITHUANIA"=>"申根协议国家",
					"卢森堡 LUXEMBURG"=>"申根协议国家",
					"马尔他 MALTA"=>"申根协议国家",	
					"匈牙利  HUNGARY"=>"申根协议国家",
					"列支敦士登 LIECHTENSTEIN"=>"申根协议国家",
					
					"其他"=>"其他",
							);

$attr_sex_pingan = array("F"=>"女",
		"M"=>"男",
);



//与被保险人关系 1:本人 2:配偶 3 :父子 4:父女 5:受益人 6:受益人 7:投保人 A:母子 B:母女 C:兄弟 D:姐弟 G:祖孙 H:雇佣 I:子女 9:其他 8:转换不详
$attr_relationship_with_insured_pingan = array(
		"1"=>"本人",
		"2"=>"配偶",
		"3"=>"父子",
		"4"=>"父女",
		"5"=>"受益人",
		"6"=>"受益人",
		"7"=>"投保人",
		"8"=>"关系不详",
		"9"=>"其他",
		"A"=>"母子",
		"B"=>"母女",
		"C"=>"兄弟",
		"D"=>"姐弟",
		"G"=>"祖孙",
		"H"=>"雇佣",
		"I"=>"子女",
);

//added by zhangxi, 20150716, 房屋结构说明
$attr_buildingStructure_type_pingan_property = array(
													"01"=>"钢混",
													"02"=>"砖混",
													//"03"=>"砖木",
													//"99"=>"其他"
													);


//<!--（非空）证件类型，01：身份证，02：护照，03：军人证，05：驾驶证，06：港澳回乡证或台胞证，99：其他。-->
  
$attr_certificates_type_pingan = array(
		"01"=>"身份证",
		"02"=>"护照",
		"03"=>"军人证",
		"05"=>"驾驶证",
		"06"=>"港澳回乡证或台胞证",
		"99"=>"其他",
);
//平安个财关于证件类型定义
//证件类型,01:身份证、02：护照、03：军人证、04：港澳通行证，05：驾驶证、06：港澳回乡证或台胞证，07：临时身份证、99：其他

//<!--（可空）团体证件类型，01表示组织机构代码证，02表示税务登记证，03表示异常证件-->

$attr_group_certificates_type_pingan = array("01"=>"组织机构代码证",
		"02"=>"税务登记证",
		"03"=>"异常证件"
);


//<!--（可空）单位性质，07:股份 01:国有 02:集体 33:个体 03:私营 04:中外合资 05:外商独资 08:机关事业 13:社团 39:中外合作 9:其他-->
$attr_company_attribute_type_pingan = array(	"07"=>"股份",
		"01"=>"国有",
		"02"=>"集体",
		"33"=>"个体",
		"03"=>"私营",
		"04"=>"中外合资",
		"05"=>"外商独资",
		"08"=>"机关事业",
		"13"=>"社团 ",
		"39"=>"中外合作",
		"9"=>"其他",
);

//////////////////start add by wangcya, 20141219 ,不同厂商的证件号码和性别//////////////////////////////
$attr_certificates_type_pingan_single_unify = array(
		"01"=>1,//'身份证';
		"05"=>2,//'驾驶证';
		"02"=>4,//'护照';
		"06"=>5,//'港澳回乡证或台胞证';
		"03"=>8,//'军人证';
		"99"=>9,//'其他';
);


$attr_certificates_type_pingan_group_unify = array(
		"01"=>10,//"组织机构代码证"
		"02"=>11,//"税务登记证"
		"03"=>12//"异常证件"
);

$attr_sex_pingan_unify = array("F"=>"F",
		"M"=>"M",
);
//added by zhangxi, 20150105, for 平安出单账号和产品代码对应关系数组

$map_acount_product_code = array(
		//平安财险（北京大兴）
		array('partnerName'=>'ZTXH',
				'BANK_CODE'=>'9855',
				'BRNO'=>'98550000',
				'product_code_list'=>array(
								'Y502','01296','01297','01097','01098','01225',
								'01099','01121','01122','01123','01124','01125',
								'01126',),
		),
		//平安财险（北京01）
		array('partnerName'=>'ZTXH01',
				'BANK_CODE'=>'7880',
				'BRNO'=>'78800000',
				'product_code_list'=>array('01009',
										'01010','01011','01012','01013','01014','01015',
										'01016','01017','01018','01019','01020','01021',
										'01022','01023','01024','01025','01026','01027',
										'01028','01029','00820','00821','00822','00823',
										'00824','00825','00826','00827','00881','00883',
									),
		),
		//平安财险（北京02）
		array('partnerName'=>'ZTXH02',
				'BANK_CODE'=>'7881',
				'BRNO'=>'78810000',
				'product_code_list'=>array('01331',
									  '01332',
									),
		),
		//added by zhangxi, 20150128,增加燃气险，交通意外险的支持
		//平安财险（重客）
		array('partnerName'=>'ZTXH01',
				'BANK_CODE'=>'7891',
				'BRNO'=>'78910000',
				'product_code_list'=>array('01343','01344','01345','01346','01347',
			/*	53001 53002
				53003
				53061
				53062
				53063
			*/	
				
									),
		),
);
//////////////////end add by wangcya, 20141219 //////////////////////////////

//added by zhangxi, 20150113, 产品代码进行分类
$pingan_product_type=array(
						'lvyou'=>array('01121','01122','01123','01124','01125','01126',
										'00820','00821','00822','00823','00824','00825',
										'00826','00827','00881','00883',
										'01009','01010','01011','01012','01013','01014',
										'01015','01016','01017','01018','01019','01020',
										'01021','01022','01023','01024','01025','01026',
										'01027','01028','01029',
										),
						 'yiwai'=>array('01296','01297','01097','01098',
						 				'01225','01099',
						 				),
							);


function find_BRNO_by_product_code($product_code)
{
	global $_SGLOBAL;
	////////////////////////////////////////////////
	//mod by zhangxi, 20150612, 如果产品代码受影响因素影响，那么通过产品代码是找不出来险种的，这样
	//就先对产品代码临时矫正，方便找到其真正对应的险种
	$jingwailvxing = array('01009',
							'01010','01011','01012','01013','01014','01015',
							'01016','01017','01018','01019','01020','01021',
							'01022','01023','01024','01025','01026','01027',
							'01028','01029');
	$kuailelvcheng = array('00820','00821','00822','00823',
							'00824','00825','00826','00827','00881','00883');
	if(in_array($product_code, $jingwailvxing))	
	{
		$product_code = '01009';
	}					
	elseif(in_array($product_code, $kuailelvcheng))
	{
		$product_code = '00820';
	}
	
	$wheresql = "product_code='$product_code'";
	$sql = "SELECT attribute_id FROM ".tname('insurance_product_base')." WHERE $wheresql LIMIT 1";
	ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);
	$product = $_SGLOBAL['db']->fetch_array($query);
	$attribute_id = $product['attribute_id'];
	
	////////////////////////////////////////////////
	if($attribute_id)
	{
		$wheresql = "attribute_id='$attribute_id'";
		$sql = "SELECT insurer_id FROM ".tname('insurance_product_attribute')." WHERE $wheresql LIMIT 1";
		ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
		$product_attribute = $_SGLOBAL['db']->fetch_array($query);
		$insurer_id = $product_attribute['insurer_id'];
	}
	
	
	////////////////////////////////////////////////
	if($insurer_id)
	{
		$wheresql = "insurer_id='$insurer_id'";
		$sql = "SELECT * FROM ".tname('insurance_company')." WHERE $wheresql LIMIT 1";
		ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
		$insurance_company = $_SGLOBAL['db']->fetch_array($query);
		
		return $insurance_company;
	
	}

}

//added by zhangxi, 20150709, 增加平安家财险
function input_check_pingan_property($product, $POST)
{
	global $_SGLOBAL,$attr_certificates_type_pingan_single_unify,$attr_certificates_type_pingan_group_unify,$attr_sex_pingan_unify;
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$global_info = array();
	
	$agent_uid = $_SGLOBAL['supe_uid'];//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	//////////////////有效性检查//////////////////////////////////////////////////////
	if(	!isset($POST['applyNum'])||
		!isset($POST['startDate'])||
		!isset($POST['endDate'])||
		!isset($POST['applicant_certificates_type'])||
		!isset($POST['applicant_certificates_code'])||
		!isset($POST['applicant_email'])||
		!isset($POST['applicant_mobilephone'])||
		!isset($POST['businessType'])||
		!isset($POST['data_user_info'])
		)
	{
		showmessage("您输入的投保信息不完整哦！");
		exit(0);
	}
	
	//与被保险人之间的关系类型判断
	//$POST['relationshipWithInsured']，这个参数还需要判断吗？

	////////////////////////////////////////////////////////////
	//这个地方的投保份数，界面是一份，但实际值应该等于投保人数
	$global_info['applyNum'] = intval($POST['applyNum']);//份数
	
	$global_info['totalModalPremium'] = $POST['totalModalPremium'];//总保费,应该从前端计算得到的
	$global_info['beneficiary'] = $POST['beneficiary'];//受益人
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期
	//added by zhangxi, 20150718,对于家居综合中的其中一个保障，需要制定保额的情况
	if($product['attribute_type'] == 'jiajuzonghe' 
		&& isset($POST['house_amount']))
	{
		$global_info['policy_amount'] = intval(round($POST['house_amount'],4)*10000);
	}
	
	
	///////////////组合投保人信息//////////////////////////////////////////////////
	$user_info_applicant = array();

	$businessType = empty($POST['businessType'])?1:trim($POST['businessType']);
	if($businessType != 1)
	{
		//input check error
		showmessage("投保人类型不正确！");
		exit(0);
	}
	
	//投保人信息获取
	$user_info_applicant['fullname'] = $POST['applicant_fullname'];
	$user_info_applicant['certificates_type'] = $POST['applicant_certificates_type'];
	$user_info_applicant['certificates_code'] = $POST['applicant_certificates_code'];
	
	
	//$user_info_applicant['telephone'] = $POST['telephone'];//这个没有
	$user_info_applicant['mobiletelephone'] = $POST['applicant_mobilephone'];
	$user_info_applicant['email'] = $POST['applicant_email'];
	$user_info_applicant['birthday'] = $POST['applicant_birthday'];
	$user_info_applicant['gender'] = $POST['applicant_sex'];
	
	//标的物，也就是房屋信息，可以存放到投保人信息中
	$user_info_applicant['address'] = $POST['address'];
	$user_info_applicant['province'] = $POST['province'];
	$user_info_applicant['province_code'] = $POST['province_code'];
	$user_info_applicant['city'] = $POST['city'];
	$user_info_applicant['city_code'] = $POST['city_code'];
	$user_info_applicant['county'] = $POST['county'];
	$user_info_applicant['county_code'] = $POST['county_code'];
	
	$user_info_applicant['agent_uid'] = $_SGLOBAL['supe_uid'];
	$user_info_applicant['certificates_type_unify'] = $attr_certificates_type_pingan_single_unify[$user_info_applicant['certificates_type']];
	$user_info_applicant['gender_unify'] = $attr_sex_pingan_unify[$user_info_applicant['gender']];
	if(($product['attribute_type'] == 'jiajuzonghe' 
	||$product['attribute_type'] == 'jiacaizonghe')
		&& isset($POST['house_type']))
	{
		$user_info_applicant['house_type'] = trim($POST['house_type']);
	}
	
	ss_log(__FUNCTION__."，group name:".$user_info_applicant['fullname']);

	$user_info_applicant['agent_uid'] = $agent_uid;
	//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	
	/////////////////////////////////////////////////////////////////////
	$assured_info = array();
	/////////////////////////////////////////////////////////

	$relationshipWithInsured = 1;
	//$relationshipWithInsured = trim($POST['relationshipWithInsured']);//（非空）与投保人关系。1:本人 2:配偶 3 :父子 4:父女 5:受益人 6:被保人 7:投保人 A:母子 B:母女 C:兄弟 D:姐弟 G:祖孙 H:雇佣 I:子女 9:其他 8:转换不详
	////////////////投保人的身份信息////////////////////////////////
	//////////////////首先根据被保险人身份证到用户表中查询，如果查到则进行信息更新，如果没查到插入新纪录

	//主险的产品ID,有多个主险呢？怎么处理呢？好像没用
	$product_id = intval($POST['product_id']);

	

	//获取产品列表，是被投保的产品列表

	$duty_price_ids = $_POST['duty_price_ids'];
	
	if(!isset($duty_price_ids))
	{
		showmessage("投保产品信息缺失,缺少责任，价格变量！");
		exit(0);
	}

	//循环获取所有的被保险人信息
	$data_user = stripslashes($POST['data_user_info']);
	//$data_user = '{"result_code":"0","result_msg":"success","data":[{"fullname":"王  健","certificates_type":"01","certificates_code":"211103197703030617","career_type":"0001001","gender":"M","birthday":"1977-03-03","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"方  薇","certificates_type":"01","certificates_code":"110108196805184820","career_type":"0001001","gender":"F","birthday":"1968-05-18","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"范本勇","certificates_type":"01","certificates_code":"320682198101040473","career_type":"0001001","gender":"M","birthday":"1981-01-04","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"郭慧芬","certificates_type":"01","certificates_code":"230106196411062067","career_type":"2001003","gender":"F","birthday":"1964-11-06","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"田志勇","certificates_type":"01","certificates_code":"630121196506291514","career_type":"2001003","gender":"M","birthday":"1965-06-29","email":"","mobiletelephone":"","level_num":"2"}]}';
	if(empty($data_user))
	{
		showmessage("获取被保险人信息失败，data_user变量为空！");
		exit(0);
	}
	ss_log(__FUNCTION__." get post data:".$POST['data_user_info']);

	$list_user = json_decode($data_user, true);
	ss_log(__FUNCTION__." json decode data:".$list_user);
	$list_assured1 = array();
	//获取被保险人信息，这里需要考虑多个被保险人信息
	foreach($list_user as $k=>$val)
	{
		if(trim($val['type']) == 2)//被保险人是投保人的情况
		{
			$assured_info = $user_info_applicant;
			$assured_info['type'] = trim($val['type']);
			$assured_info['relation'] = trim($val['relation']);
			$assured_info['amount1'] = trim($val['p_1'])*10000;
			$assured_info['amount2'] = trim($val['p_2'])*10000;
		}
		else
		{
			$assured_info['certificates_type'] = trim($val['certificates_type']);
			$assured_info['certificates_code'] = trim($val['certificates_code']);
			$assured_info['birthday'] = trim($val['birthday']);
			$assured_info['fullname']  = trim($val['fullname']);
			$assured_info['gender']  = trim($val['gender']);
			$assured_info['mobiletelephone'] =  trim($val['mobiletelephone']);
			$assured_info['email'] =  trim($val['email']);
			$assured_info['relation'] = trim($val['relation']);
			$assured_info['amount1'] = trim($val['p_1'])*10000;
			$assured_info['amount2'] = trim($val['p_2'])*10000;
	
			//add by wangcya, 20141219 ,不同厂商的证件号码和性别
			$assured_info['certificates_type_unify'] = $attr_certificates_type_pingan_single_unify[$assured_info['certificates_type']];
			$assured_info['gender_unify'] = $attr_sex_pingan_unify[$assured_info['gender']];
			$assured_info['type'] = trim($val['type']);//0,被保险人，1投保人,2两者身份相同
			$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
			//end add by wangcya, 20141219 ,不同厂商的证件号码和性别
		}
		
		$list_assured1[] = $assured_info;
	}
	
	//前端计算出来的保费再和后台计算出来的保费进行一下校验。
	$check_ids=$duty_price_ids;
	$check_ids_new = str_replace("_", ",", $check_ids);
	ss_log(__FUNCTION__.", check_ids=".$check_ids);
	ss_log(__FUNCTION__.", check_ids_new=".$check_ids_new);
	$total_db_premium=get_premium_by_duty_price_ids($check_ids_new);
	$total_db_premium = round($total_db_premium,2);
	$global_info['totalModalPremium'] = round($global_info['totalModalPremium'],2);
	//echo $total_db_premium;
	//动态保额的保费矫正
	if($product['attribute_type'] == 'jiajuzonghe'
	  && isset($POST['house_amount']))
	{
		if($global_info['policy_amount']>500000)
		{
			$difference = ($global_info['policy_amount']-500000)*0.0002;
			$total_db_premium += $difference;
			$total_db_premium = round($total_db_premium,2);
		}
	}
	
	
	if($total_db_premium != $global_info['totalModalPremium'])
	{
		showmessage("保费校验错误！");
		exit(0);
	}
	
	//mod by zhangxi, 20150709,多层次处理
	$list_ids = explode('_', $duty_price_ids);
	ss_log(__FUNCTION__.", list_ids=".var_export($list_ids, true));

	$list_subjectinfo = array();
	foreach($list_ids as $key=>$value_ids)//遍历多个产品的情况
	{
		$list_duty_price_id = explode(',', $value_ids);
		$sql="SELECT DISTINCT ipd.product_id FROM t_insurance_product_duty AS ipd 
		  INNER JOIN t_insurance_product_duty_price AS ipdp 
		  ON ipd.product_duty_id=ipdp.product_duty_id 
		  WHERE ipdp.product_duty_price_id IN ($value_ids)";
		//$sql="SELECT product_duty_id FROM t_insurance_product_duty_price 
		//WHERE product_duty_price_id IN ($duty_price_ids)";
		
		$product_id_query = $_SGLOBAL['db']->query($sql);
		$product_id_row = array();
		$list_product_id = array();
		while ($product_id_row = $_SGLOBAL['db']->fetch_row($product_id_query))
		{
			$list_product_id[] = $product_id_row[0];
			ss_log(__FUNCTION__.", add product id is：".$product_id_row[0]);
		}
		
		ss_log(__FUNCTION__.", add product id show down ");
		$list_subjectinfo[] = array('list_product_id'=>$list_product_id,
									'list_assured'=>$list_assured1,
									'list_duty_price_id'=>$list_duty_price_id,
									);
		
	}

	////////////////////////////////////////////////////////////
	$retattr = array(
			"global_info"=>$global_info,
			"businessType"=>$businessType,
			"relationshipWithInsured"=>$relationshipWithInsured,
			"user_info_applicant"=>$user_info_applicant,
			"list_subjectinfo"=>$list_subjectinfo
	);

	///////////////////////////////////////////////////////////
	return $retattr;
}


////////////////////////////////////////////////////////////////////////////////
//added by zhangxi, 20141210, y502的产品的输入检查，暂时独立出来
//以免影响平安其他上线的产品
function input_check_pingan_y502($POST)
{
	global $_SGLOBAL,$attr_certificates_type_pingan_single_unify,$attr_certificates_type_pingan_group_unify,$attr_sex_pingan_unify;
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$global_info = array();
	
	$agent_uid = $_SGLOBAL['supe_uid'];//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	//////////////////有效性检查//////////////////////////////////////////////////////
	if(	!isset($POST['applyNum'])||
		!isset($POST['startDate'])||
		!isset($POST['endDate'])||
		!isset($POST['applicant_group_name'])||
		!isset($POST['applicant_certificates_type'])||
		!isset($POST['applicant_certificates_code'])||
		!isset($POST['applicant_email'])||
		!isset($POST['applicant_mobilephone'])||
		!isset($POST['businessType'])||
		!isset($POST['data_user_info'])
		)
	{
		showmessage("您输入的投保信息不完整哦！");
		exit(0);
	}
	
	//与被保险人之间的关系类型判断
	//$POST['relationshipWithInsured']，这个参数还需要判断吗？

	////////////////////////////////////////////////////////////
	//这个地方的投保份数，界面是一份，但实际值应该等于投保人数
	$global_info['applyNum'] = intval($POST['applyNum']);//份数
	
	$global_info['totalModalPremium'] = $POST['totalModalPremium'];//总保费,应该从前端计算得到的
	$global_info['beneficiary'] = $POST['beneficiary'];//受益人
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期
	//借用一下变量来存储
	$global_info['cast_policy_no'] = $POST['product_flag'];

	///////////////组合投保人信息//////////////////////////////////////////////////
	$user_info_applicant = array();

	$businessType = empty($POST['businessType'])?1:trim($POST['businessType']);
	if($businessType != 2)
	{
		//input check error
		showmessage("投保人类型不正确！");
		exit(0);
	}
	
	//团体投保人信息获取
	$user_info_applicant['group_name'] = $POST['applicant_group_name'];
	$user_info_applicant['group_certificates_type'] = $POST['applicant_certificates_type'];
	$user_info_applicant['group_certificates_code'] = $POST['applicant_certificates_code'];
	
	$user_info_applicant['group_abbr'] = $POST['group_abbr'];//这个没有
	$user_info_applicant['company_attribute'] = $POST['company_attribute'];//这个没有
	$user_info_applicant['address'] = $POST['address'];//这个没有
	$user_info_applicant['telephone'] = $POST['telephone'];//这个没有
	$user_info_applicant['mobiletelephone'] = $POST['applicant_mobilephone'];
	$user_info_applicant['email'] = $POST['applicant_email'];
	$user_info_applicant['applicant_fullname'] = $POST['applicant_fullname'];
	
	ss_log(__FILE__.":group name:".$user_info_applicant['group_name']);

	//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	$user_info_applicant['group_certificates_type_unify'] = $attr_certificates_type_pingan_group_unify[$user_info_applicant['group_certificates_type']];
	$user_info_applicant['agent_uid'] = $agent_uid;
	//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	
	//放到时被保险的职业类别信息，
	$user_info_applicant['career_type'] = $POST['career'];
	
	/////////////////////////////////////////////////////////////////////
	$assured_info = array();
	/////////////////////////////////////////////////////////
	//团体投保，投保人和被保险人之间是什么关系呢? 应该是"其他"的关系
	$relationshipWithInsured = 9;
	//$relationshipWithInsured = 1;
	//$relationshipWithInsured = trim($POST['relationshipWithInsured']);//（非空）与投保人关系。1:本人 2:配偶 3 :父子 4:父女 5:受益人 6:被保人 7:投保人 A:母子 B:母女 C:兄弟 D:姐弟 G:祖孙 H:雇佣 I:子女 9:其他 8:转换不详
	////////////////投保人的身份信息////////////////////////////////
	//////////////////首先根据被保险人身份证到用户表中查询，如果查到则进行信息更新，如果没查到插入新纪录

	//主险的产品ID
	$product_id = intval($POST['product_id']);
	//获取用户选定的投保险种，如果主险没有选，则异常
	if(!isset($POST['career']))
	{
		showmessage("投保产品人员职业类别career变量没有设置！");
		exit(0);
	}
	$career= $POST['career'];
	$list_career = explode('_', $career);
	
	//////////一个subjectInfo内多个产品，多个被保险人//
	$subjectInfo1 = array();

	//获取产品列表，是被投保的产品列表
	$list_product_id1 = array();
	$duty_price_ids = $_POST['duty_price_ids'];
	
	if(!isset($duty_price_ids))
	{
		showmessage("投保产品信息缺失,缺少责任，价格变量！");
		exit(0);
	}
	
	
	//循环获取所有的被保险人信息
	$data_user = stripslashes($POST['data_user_info']);
	$data_user = stripslashes($data_user);
	//$data_user = '{"result_code":"0","result_msg":"success","data":[{"fullname":"王  健","certificates_type":"01","certificates_code":"211103197703030617","career_type":"0001001","gender":"M","birthday":"1977-03-03","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"方  薇","certificates_type":"01","certificates_code":"110108196805184820","career_type":"0001001","gender":"F","birthday":"1968-05-18","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"范本勇","certificates_type":"01","certificates_code":"320682198101040473","career_type":"0001001","gender":"M","birthday":"1981-01-04","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"郭慧芬","certificates_type":"01","certificates_code":"230106196411062067","career_type":"2001003","gender":"F","birthday":"1964-11-06","email":"","mobiletelephone":"","level_num":"2"},{"fullname":"田志勇","certificates_type":"01","certificates_code":"630121196506291514","career_type":"2001003","gender":"M","birthday":"1965-06-29","email":"","mobiletelephone":"","level_num":"2"}]}';
	if(empty($data_user))
	{
		showmessage("获取被保险人信息失败！");
		exit(0);
	}
	ss_log(__FUNCTION__." get post data:".$POST['data_user_info']);
	$list_user = json_decode($data_user, true);
	ss_log(__FUNCTION__." json decode data:".var_export($list_user, true));
	
	////////////////////////////////////////////////////////////////
	
	//mod by zhangxi, 20150709,多层次处理
	$list_ids = explode('_', $duty_price_ids);
	ss_log(__FUNCTION__.", list_ids=".var_export($list_ids, true));

	$list_subjectinfo = array();
	$real_apply_num = 0;
	$type_one_num = 0;
	$type_four_num = 0;
	$type_five_num = 0;
	$type_one_premium = 0;
	$type_four_premium = 0;
	$type_five_premium = 0;
	foreach($list_ids as $key=>$value_ids)//遍历多个层级
	{
		$list_duty_price_id = explode(',', $value_ids);
		$sql="SELECT DISTINCT ipd.product_id FROM t_insurance_product_duty AS ipd 
		  INNER JOIN t_insurance_product_duty_price AS ipdp 
		  ON ipd.product_duty_id=ipdp.product_duty_id 
		  WHERE ipdp.product_duty_price_id IN ($value_ids)";
		  
		$product_id_query = $_SGLOBAL['db']->query($sql);
		$product_id_row = array();
		$list_product_id = array();
		while ($product_id_row = $_SGLOBAL['db']->fetch_row($product_id_query))
		{
			$list_product_id[] = $product_id_row[0];
			ss_log(__FUNCTION__.", add product id is：".$product_id_row[0]);
		}
		
		//被保险人员列表分类并获取
		$list_assured1 = array();
		ss_log(__FUNCTION__.",loop list_user=".var_export($list_user, true));
		//获取被保险人信息，这里需要考虑多个被保险人信息
		foreach($list_user as $k=>$val)//遍历被保险人
		{
			if(!isset($val['level_num']))
			{
				showmessage("被保险人 ".$val['fullname']." 职业类别参数没有传递");
				exit(0);
			}
			$level_num = intval(trim($val['level_num']));
			
			if(($list_career[$key] == 1) && ($level_num>0)  &&  ($level_num <=3))//找到1到3类人
			{
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
				$list_assured1[] = $assured_info;
				$type_one_num++;
				
			}
			elseif($list_career[$key] == 4 && $level_num==4)//找到4类人
			{
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
				$list_assured1[] = $assured_info;
				$type_four_num++;
			}
			elseif($list_career[$key] == 5 && ( $level_num>=5 && $level_num <=6))//找到5-6类人
			{
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
				$list_assured1[] = $assured_info;
				$type_five_num++;
			}
			else
			{
				//这里不能做任何处理
			}
			
		}//end foreach($list_user as $k=>$val)
		
		if($list_career[$key] == 1)
		{
			$type_one_premium =$type_one_num* get_premium_by_duty_price_ids($value_ids);
		}
		elseif($list_career[$key] == 4)
		{
			$type_four_premium =$type_four_num* get_premium_by_duty_price_ids($value_ids);
		}
		elseif($list_career[$key] == 5)
		{
			$type_five_premium =$type_five_num* get_premium_by_duty_price_ids($value_ids);
		}
		else
		{
			showmessage("被保险人职业类别不属于允许的投保范围".$list_career[$key]);
				exit(0);
		}
		//统计1-3类人的时候，没有人员
		if(($list_career[$key] == 1) && ($type_one_num == 0))
		{
			continue;
		}
		elseif(($list_career[$key] == 4) && ($type_four_num == 0))
		{
			continue;
		}
		elseif(($list_career[$key] == 5) && ($type_five_num == 0))
		{
			continue;
		}
		else
		{
			
		}
		
		ss_log(__FUNCTION__.", ------------------ ");
		$list_subjectinfo[] = array('list_product_id'=>$list_product_id,
									'list_assured'=>$list_assured1,
									'list_duty_price_id'=>$list_duty_price_id,
									);
		
	}//end foreach($list_ids as $key=>$value_ids)
	
	$real_apply_num = ($type_one_num+$type_four_num+$type_five_num);
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
	
	//前端计算出来的保费再和后台计算出来的保费进行一下校验。
	
	$total_db_premium=($type_one_premium+$type_four_premium+$type_five_premium);
	
	
	$total_db_premium = round($total_db_premium,2);
	$global_info['totalModalPremium'] = round($global_info['totalModalPremium'],2);
	//echo $total_db_premium;
	if($total_db_premium != $global_info['totalModalPremium'])
	{
		showmessage("保费校验错误！".$total_db_premium." ".$global_info['totalModalPremium']);
		exit(0);
	}
	
	//真正的投保份数
	$global_info['applyNum'] = $real_apply_num;

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

	///////////////////////////////////////////////////////////
	return $retattr;

}

//added by zhangxi, 20151208, 平安公司发现了A,B,C卡等标准产品保险时间长度有问题，独立处理
function input_check_pingan_product($product, $POST)
{
	global $_SGLOBAL,$attr_certificates_type_pingan_single_unify,$attr_certificates_type_pingan_group_unify,$attr_sex_pingan_unify;
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$global_info = array();
	$agent_uid = $_SGLOBAL['supe_uid'];//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	
	//////////////////有效性检查//////////////////////////////////////////////////////
	if(	!isset($POST['applyNum'])||
		!isset($POST['startDate'])||
		!isset($POST['endDate'])||
		!isset($POST['applicant_fullname'])||
		!isset($POST['applicant_certificates_type'])||
		!isset($POST['applicant_certificates_code'])||
		!isset($POST['applicant_birthday'])||
		!isset($POST['applicant_sex'])||
		!isset($POST['relationshipWithInsured'])
		)
	{
		showmessage("您输入的投保信息不完整！");
		exit(0);
	}
	
	//与被保险人之间的关系类型判断
	if($POST['relationshipWithInsured']!=1)
	{
		//被保险人输入信息检查
		$assured_post = $POST['assured'][0];
		if(		!isset($assured_post['assured_fullname'])||
				!isset($assured_post['assured_certificates_type'])||
				!isset($assured_post['assured_certificates_code'])||
				!isset($assured_post['assured_sex'])||
				!isset($assured_post['assured_birthday'])
		
		)
		{
			showmessage("您输入的被保险人信息不完整！");
			exit(0);
		}
	}
	////////////////////////////////////////////////////////////

	$global_info['applyNum'] = intval($POST['applyNum']);//份数
	$global_info['totalModalPremium'] = $POST['totalModalPremium'];//总保费,应该从前端计算得到的

	ss_log("in function input_check_pingan, totalModalPremium: ".$POST['totalModalPremium']);
	//$totalModalPremium'] = $apply_num*intval($product[premium]);//$POST['totalModalPremium'];//从费用

	$global_info['beneficiary'] = $POST['beneficiary'];//受益人
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期

	//start modify by waqngcya, 20150506,适应那种标准产品但是保险期间不是一年的产品
	//begin add by wangcya, 20150506,投保天数、
	
	/*
	$period_uint = empty($POST['period_uint'])?"month":$POST['period_uint'];//add by wangcya, 20150110, 投保的天数
	$period_uint = trim($period_uint);
	*/
	
	$global_info['apply_day'] = $apply_period = empty($POST['apply_day'])?intval($POST['period']):intval($POST['apply_day']);//add by wangcya, 20150110, 投保的天数
	
	/*
	if($period_uint=="year")
	{
		ss_log("period_uint is year!!!");
	}
	elseif($period_uint=="month")
	{
		$global_info['apply_month'] = $apply_period;//add by wangcya, 20150110, 投保的天数
		
	}
	elseif($period_uint=="day")
	{
		$global_info['apply_day'] = $apply_period;//add by wangcya, 20150110, 投保的天数
		
	}
	else
	{
		ss_log("not find period_uint!!!");
		//$global_info['apply_day'] = $apply_period;
	}
	*/
	//前端后端保费较验
	//check_input_premiums();
	$total_db_premium = round($POST['applyNum']*$product['premium'] ,2);
	$submit_premium = round($global_info['totalModalPremium'],2);
	if($total_db_premium != $submit_premium)
	{
		showmessage("保费校验错误！实际保费:".$total_db_premium."元, 提交保费".$global_info['totalModalPremium']."元");
		exit(0);
	}
	
	
	//end modify by waqngcya, 20150506,适应那种标准产品但是保险期间不是一年的产品
	
	//////////////////////////////////////////////////////////////////////////

	
	///////////////组合投保人信息//////////////////////////////////////////////////
	$user_info_applicant = array();

	
	$businessType = empty($POST['businessType'])?1:trim($POST['businessType']);
	if($businessType == 1)//个人
	{
		$user_info_applicant['certificates_type'] = $POST['applicant_certificates_type'];
		$user_info_applicant['certificates_code'] = $POST['applicant_certificates_code'];
		$user_info_applicant['fullname'] = $POST['applicant_fullname'];
		$user_info_applicant['fullname_english'] = $POST['applicant_fullname_english'];
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
	else//团体投保人
	{
		//投保人信息获取
		$user_info_applicant['group_name'] = $POST['group_name'];
		$user_info_applicant['group_certificates_type'] = $POST['applicant_group_certificates_type'];
		$user_info_applicant['group_certificates_code'] = $POST['applicant_group_certificates_code'];
		
		$user_info_applicant['group_abbr'] = $POST['group_abbr'];
		$user_info_applicant['company_attribute'] = $POST['company_attribute'];
		$user_info_applicant['address'] = $POST['address'];
		$user_info_applicant['telephone'] = $POST['telephone'];
		$user_info_applicant['mobiletelephone'] = $POST['applicant_mobilephone'];
		$user_info_applicant['email'] = $POST['applicant_email'];
		
		//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		$user_info_applicant['group_certificates_type_unify'] = $attr_certificates_type_pingan_group_unify[$user_info_applicant['group_certificates_type']];
		$user_info_applicant['agent_uid'] = $agent_uid;
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	}

	/////////////////////////////////////////////////////////////////////
	$assured_info = array();

	/////////////////////////////////////////////////////////
	$relationshipWithInsured = trim($POST['relationshipWithInsured']);//（非空）与投保人关系。1:本人 2:配偶 3 :父子 4:父女 5:受益人 6:被保人 7:投保人 A:母子 B:母女 C:兄弟 D:姐弟 G:祖孙 H:雇佣 I:子女 9:其他 8:转换不详
	////////////////投保人的身份信息////////////////////////////////
	//////////////////首先根据被保险人身份证到用户表中查询，如果查到则进行信息更新，如果没查到插入新纪录
	if($relationshipWithInsured=="1")//是本人的信息
	{
		//被保险人就是投保人本人，得到被保险人信息
		$assured_info = $user_info_applicant;
		$assured_info['type'] = 2;//add by wangcya, 20141219, 0,被保险人，1投保人,2两者身份相同
			
	}
	else//
	{
		//获取被保险人信息，这里考虑获取多个人信息了么？
		$assured_post = $POST['assured'][0];
			
		$assured_info['certificates_type'] = trim($assured_post['assured_certificates_type']);
		$assured_info['certificates_code'] = trim($assured_post['assured_certificates_code']);
		$assured_info['birthday'] = trim($assured_post['assured_birthday']);
		$assured_info['fullname']  = trim($assured_post['assured_fullname']);
		$assured_info['fullname_english']  = trim($assured_post['assured_fullname_english']);
		$assured_info['gender']  = trim($assured_post['assured_sex']);
		$assured_info['mobiletelephone'] =  trim($assured_post['assured_mobilephone']);
		$assured_info['email'] =  trim($assured_post['assured_email']);
		
		//add by wangcya, 20141219 ,不同厂商的证件号码和性别
		$assured_info['certificates_type_unify'] = $attr_certificates_type_pingan_single_unify[$assured_info['certificates_type']];
		$assured_info['gender_unify'] = $attr_sex_pingan_unify[$assured_info['gender']];
		$assured_info['type'] = 0;//0,被保险人，1投保人,2两者身份相同
		$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别
	}

	$product_id = intval($POST['product_id']);
	//////////一个subjectInfo内多个产品，多个被保险人///////////////////////////////////////////////////////////
	////////方案一是父母的////////////
	$subjectInfo1 = array();

	$list_product_id1 = array();
	$list_product_id1[] = $product_id;//
	$subjectInfo1['list_product_id'] = $list_product_id1;

	$list_assured1 = array();
	$list_assured1[] = $assured_info;
	$subjectInfo1['list_assured'] = $list_assured1;


	////////////////////////////////////////////////////////////
	$list_subjectinfo = array();
	$list_subjectinfo[] = $subjectInfo1;
	////////////////////////////////////////////////////////////

	$retattr = array(	"global_info"=>$global_info,
			"businessType"=>$businessType,
			"relationshipWithInsured"=>$relationshipWithInsured,
			"user_info_applicant"=>$user_info_applicant,
			"list_subjectinfo"=>$list_subjectinfo
	);

	///////////////////////////////////////////////////////////
	return $retattr;

}

//检查平安的输入的函数,同时又返回了需要传递的
function input_check_pingan($product, $POST)
{
	global $_SGLOBAL,$attr_certificates_type_pingan_single_unify,$attr_certificates_type_pingan_group_unify,$attr_sex_pingan_unify;
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$global_info = array();
	$agent_uid = $_SGLOBAL['supe_uid'];//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	
	//////////////////有效性检查//////////////////////////////////////////////////////
	if(	!isset($POST['applyNum'])||
		!isset($POST['startDate'])||
		!isset($POST['endDate'])||
		!isset($POST['applicant_fullname'])||
		!isset($POST['applicant_certificates_type'])||
		!isset($POST['applicant_certificates_code'])||
		!isset($POST['applicant_birthday'])||
		!isset($POST['applicant_sex'])||
		!isset($POST['relationshipWithInsured'])
		)
	{
		showmessage("您输入的投保信息不完整！");
		exit(0);
	}
	
	//与被保险人之间的关系类型判断
	if($POST['relationshipWithInsured']!=1)
	{
		//被保险人输入信息检查
		$assured_post = $POST['assured'][0];
		if(		!isset($assured_post['assured_fullname'])||
				!isset($assured_post['assured_certificates_type'])||
				!isset($assured_post['assured_certificates_code'])||
				!isset($assured_post['assured_sex'])||
				!isset($assured_post['assured_birthday'])
		
		)
		{
			showmessage("您输入的被保险人信息不完整！");
			exit(0);
		}
	}
	////////////////////////////////////////////////////////////

	$global_info['applyNum'] = intval($POST['applyNum']);//份数
	$global_info['totalModalPremium'] = round($POST['totalModalPremium'],2);//总保费,应该从前端计算得到的

	ss_log("in function input_check_pingan, totalModalPremium: ".$POST['totalModalPremium']);
	//$totalModalPremium'] = $apply_num*intval($product[premium]);//$POST['totalModalPremium'];//从费用

	$global_info['beneficiary'] = $POST['beneficiary'];//受益人
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期

	//start modify by waqngcya, 20150506,适应那种标准产品但是保险期间不是一年的产品
	//begin add by wangcya, 20150506,投保天数、
	
	
	//$period_uint = empty($POST['period_uint'])?"month":$POST['period_uint'];//
	//$period_uint = trim($period_uint);
	
	$global_info['apply_day'] = $apply_period = empty($POST['apply_day'])?intval($POST['period']):intval($POST['apply_day']);//add by wangcya, 20150110, 投保的天数
	
	
	
	$attribute_type = $product['attribute_type'];
	if($attribute_type == 'lvyou')
	{
		//period_code
		if(!isset($POST['period_code']))
		{
			showmessage("产品参数缺失！");
			exit(0);
		}
		$product_id = $product['product_id'];
		$period_code = $POST['period_code'];
		$wheresql = "product_id='$product_id' and product_influencingfactor_type='period' and factor_code='$period_code'";//根据这个找到其对应的保单存放的数据
		$sql = "SELECT * FROM ".tname('insurance_product_influencingfactor')." WHERE $wheresql LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$ret = $_SGLOBAL['db']->fetch_array($query);
		$factor_price = $ret['factor_price'];
		$total_db_premium = round($POST['applyNum']*$factor_price, 2);
		
		if($total_db_premium != $global_info['totalModalPremium'])
		{
			showmessage("保费校验错误！实际保费:".$total_db_premium."元, 提交保费".$global_info['totalModalPremium']."元");
			exit(0);
		}
	}
	elseif($attribute_type == 'jingwailvyou')
	{
		//period_code
		if((!isset($POST['period_code'])) || (!isset($POST['factor_id_age'])) )
		{
			showmessage("产品参数缺失！");
			exit(0);
		}
		$product_id = $product['product_id'];
		$period_code = $POST['period_code'];
		$age_code = $POST['factor_id_age'];
		
		$wheresql = "product_id='$product_id' and product_influencingfactor_type='period' and factor_code='$period_code'";//根据这个找到其对应的保单存放的数据
		$sql = "SELECT * FROM ".tname('insurance_product_influencingfactor')." WHERE $wheresql LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$ret = $_SGLOBAL['db']->fetch_array($query);
		$factor_price = $ret['factor_price'];
		
		$wheresql = "product_id='$product_id' and product_influencingfactor_type='age' and factor_code='$age_code'";//根据这个找到其对应的保单存放的数据
		$sql = "SELECT * FROM ".tname('insurance_product_influencingfactor')." WHERE $wheresql LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$ret = $_SGLOBAL['db']->fetch_array($query);
		$factor_age_price = $ret['factor_price'];
		
		$total_db_premium = round($POST['applyNum']*($factor_price*$factor_age_price), 2);
		
		if($total_db_premium != $global_info['totalModalPremium'])
		{
			showmessage("保费校验错误！实际保费:".$total_db_premium."元, 提交保费".$global_info['totalModalPremium']."元");
			exit(0);
		}
	}
	
	
	/*
	if($period_uint=="year")
	{
		ss_log("period_uint is year!!!");
	}
	elseif($period_uint=="month")
	{
		$global_info['apply_month'] = $apply_period;//add by wangcya, 20150110, 投保的天数
		
	}
	elseif($period_uint=="day")
	{
		$global_info['apply_day'] = $apply_period;//add by wangcya, 20150110, 投保的天数
		
	}
	else
	{
		ss_log("not find period_uint!!!");
		//$global_info['apply_day'] = $apply_period;
	}
	
	*/
	
	
	//end modify by waqngcya, 20150506,适应那种标准产品但是保险期间不是一年的产品
	
	//////////////////////////////////////////////////////////////////////////

	
	///////////////组合投保人信息//////////////////////////////////////////////////
	$user_info_applicant = array();

	
	$businessType = empty($POST['businessType'])?1:trim($POST['businessType']);
	if($businessType == 1)//个人
	{
		$user_info_applicant['certificates_type'] = $POST['applicant_certificates_type'];
		$user_info_applicant['certificates_code'] = $POST['applicant_certificates_code'];
		$user_info_applicant['fullname'] = $POST['applicant_fullname'];
		$user_info_applicant['fullname_english'] = $POST['applicant_fullname_english'];
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
	else//团体投保人
	{
		//投保人信息获取
		$user_info_applicant['group_name'] = $POST['group_name'];
		$user_info_applicant['group_certificates_type'] = $POST['applicant_group_certificates_type'];
		$user_info_applicant['group_certificates_code'] = $POST['applicant_group_certificates_code'];
		
		$user_info_applicant['group_abbr'] = $POST['group_abbr'];
		$user_info_applicant['company_attribute'] = $POST['company_attribute'];
		$user_info_applicant['address'] = $POST['address'];
		$user_info_applicant['telephone'] = $POST['telephone'];
		$user_info_applicant['mobiletelephone'] = $POST['applicant_mobilephone'];
		$user_info_applicant['email'] = $POST['applicant_email'];
		
		//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		$user_info_applicant['group_certificates_type_unify'] = $attr_certificates_type_pingan_group_unify[$user_info_applicant['group_certificates_type']];
		$user_info_applicant['agent_uid'] = $agent_uid;
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	}

	/////////////////////////////////////////////////////////////////////
	$assured_info = array();

	/////////////////////////////////////////////////////////
	$relationshipWithInsured = trim($POST['relationshipWithInsured']);//（非空）与投保人关系。1:本人 2:配偶 3 :父子 4:父女 5:受益人 6:被保人 7:投保人 A:母子 B:母女 C:兄弟 D:姐弟 G:祖孙 H:雇佣 I:子女 9:其他 8:转换不详
	////////////////投保人的身份信息////////////////////////////////
	//////////////////首先根据被保险人身份证到用户表中查询，如果查到则进行信息更新，如果没查到插入新纪录
	if($relationshipWithInsured=="1")//是本人的信息
	{
		//被保险人就是投保人本人，得到被保险人信息
		$assured_info = $user_info_applicant;
		$assured_info['type'] = 2;//add by wangcya, 20141219, 0,被保险人，1投保人,2两者身份相同
			
	}
	else//
	{
		//获取被保险人信息，这里考虑获取多个人信息了么？
		$assured_post = $POST['assured'][0];
			
		$assured_info['certificates_type'] = trim($assured_post['assured_certificates_type']);
		$assured_info['certificates_code'] = trim($assured_post['assured_certificates_code']);
		$assured_info['birthday'] = trim($assured_post['assured_birthday']);
		$assured_info['fullname']  = trim($assured_post['assured_fullname']);
		$assured_info['fullname_english']  = trim($assured_post['assured_fullname_english']);
		$assured_info['gender']  = trim($assured_post['assured_sex']);
		$assured_info['mobiletelephone'] =  trim($assured_post['assured_mobilephone']);
		$assured_info['email'] =  trim($assured_post['assured_email']);
		
		//add by wangcya, 20141219 ,不同厂商的证件号码和性别
		$assured_info['certificates_type_unify'] = $attr_certificates_type_pingan_single_unify[$assured_info['certificates_type']];
		$assured_info['gender_unify'] = $attr_sex_pingan_unify[$assured_info['gender']];
		$assured_info['type'] = 0;//0,被保险人，1投保人,2两者身份相同
		$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别
	}

	$product_id = intval($POST['product_id']);
	//////////一个subjectInfo内多个产品，多个被保险人///////////////////////////////////////////////////////////
	////////方案一是父母的////////////
	$subjectInfo1 = array();

	$list_product_id1 = array();
	$list_product_id1[] = $product_id;//
	$subjectInfo1['list_product_id'] = $list_product_id1;

	$list_assured1 = array();
	$list_assured1[] = $assured_info;
	$subjectInfo1['list_assured'] = $list_assured1;


	////////////////////////////////////////////////////////////
	$list_subjectinfo = array();
	$list_subjectinfo[] = $subjectInfo1;
	////////////////////////////////////////////////////////////

	$retattr = array(	"global_info"=>$global_info,
			"businessType"=>$businessType,
			"relationshipWithInsured"=>$relationshipWithInsured,
			"user_info_applicant"=>$user_info_applicant,
			"list_subjectinfo"=>$list_subjectinfo
	);

	///////////////////////////////////////////////////////////
	return $retattr;

}

function input_check_pingan_other_jingwailvyou($product, $POST)
{
	$global_info = array();
	
	
	$global_info['period_code'] = trim($POST['period_code']);
	$global_info['outgoingPurpose'] = trim($POST['outgoingPurpose']);
	$global_info['destinationCountry'] = trim($POST['destinationCountry']);
	$global_info['schoolOrCompany'] = trim($POST['schoolOrCompany']);
	
	$retattr = array(	"global_info"=>$global_info);
	
	return $retattr;
}

function get_premium_by_duty_price_ids($duty_price_ids)
{
	global $_SGLOBAL;
	$wheresql = "product_duty_price_id IN ($duty_price_ids)";
	$sql = "SELECT premium from t_insurance_product_duty_price
	WHERE ". $wheresql;
	//ss_log("get product duty price sql:".$sql);
	$query = $_SGLOBAL['db']->query($sql);
	//循环当前产品下的每一个duty
	$total_premium=0;
	while($product_duty_price = $_SGLOBAL['db']->fetch_row($query) )
	{
		$total_premium+=$product_duty_price[0];
	}
	return $total_premium;
}

function gen_pingan_property_json_saleInfo($policy_arr,//保单相关信息
											$user_info_applicant,//投保人信息
											$list_subject)
{
	$employeeInfoList =array("employeeInfoList"=>array(
													"documentCost"=>0.0253,
									                "employeeCode"=>"2361300027",
									                "employeeProfCertifNo"=>"",
									                "marginCharge1"=>0.23,
									                "performanceValue1Default"=>0.23,
									                "performanceValue1Modify"=>0.23,
									                "performanceValue2Default"=>0,
									                "performanceValue2Modify"=>0,
									                "saleGroupCode"=>"23613090416"
													));
	$saleInfo["saleInfo"] = $employeeInfoList;
	return $saleInfo;								
							
}
function gen_pingan_property_json_applicantInfoList(
											$attribute_type,
											$attribute_code,
											$policy_arr,//保单相关信息
											$user_info_applicant,//投保人信息
											$list_subject)
{
	$applicantInfoList =array("applicantInfo"=>array(
													"sexCode"=>$user_info_applicant['gender'],//
													"nationality"=>"156",
													"personnelType"=>"1",//【必填】":"个团标志[1个人，0团体] String"
													"certificateType"=>$user_info_applicant['certificates_type'],
													"homeTelephone"=>$user_info_applicant['telephone'],
													"address"=>$user_info_applicant['address'],
													"isConfirm"=>5,
									                "birthday"=>$user_info_applicant['birthday'],
									                "certificateNo"=>$user_info_applicant['certificates_code'],
									                "mobileTelephone"=>$user_info_applicant['mobiletelephone'],
									                "name"=>$user_info_applicant['fullname'],
									                "email"=>$user_info_applicant['email'],
									                  
            										
													));
	return $applicantInfoList;													
}
function gen_pingan_property_json_productInfoList(
											$attribute_type,
											$attribute_code,
											$policy_arr,//保单相关信息
											$user_info_applicant,//投保人信息
											$list_subject)
{
	global $_SGLOBAL;
	
	//baseInfo，  PINGAN_PROPERTY_PREFIX
	 $baseInfo = array("baseInfo"=>array(
						                //"dataSource"=>"",//数据产生源 String 必填？？？待确定
						                "departmentCode"=>"",//出单机构 String 必填,？？？待确定
						                "renewalType"=>"0",//【选填默认0】":"续保类型 0-新保、1-从人从车续保、2-从人续保 String",
						                "isSelfCard"=>"0",
						                "inputNetworkFlag"=>"internet",
						                //"inputBy"=>"", //【必填】":"录入人,录入人员的UM代码 String ？？？待确定
						                "inputByName"=>"ZTXHEBX",//【必填】":"录入人名字 String" ？？？待确定
						                "productClass"=>"02",//产品大类，01车险，02财产险，03意健险
						                "version"=>"1.0",
						                "productCode"=>$attribute_code,//【必填】":"产品编码,通过某一险种定义出来的产品的编码，非标准险种的产品编码 String"
						                "productKind"=>"0",//【必填】":"产品类型：0-常规，1-自助卡 String
						                "productName"=>$policy_arr['attribute_name'],//根据配置获取即可
						                "productVersion"=>"1.0",//【必填】":"产品版本,区分同一个险种的不同版本 String",
						                //"quotationNo"=>"Q361309390000002592220",//没看到说明
						                "discount"=>1,//折扣系数 Double
						                "insuranceBeginDate"=>$policy_arr['start_date'],
						                "insuranceEndDate"=>$policy_arr['end_date'],
						                "totalStandardPremium"=>floatval($policy_arr['total_premium']),//基准保费合计 Double 必填
						                "totalActualPremium"=>floatval($policy_arr['total_premium']),//实交保费 Double 必填
						                "totalAgreePremium"=>floatval($policy_arr['total_premium']),//应交保费 Double 必填
						                "isRound"=>"0",//【选填】":" 保费是否规整(1-是，0-否) String",
						            )
            			);
	//extendInfo
	$extendInfo = array("extendInfo"=>array(
							                "applyApproach"=>"01",//【可选默认01】":"默认单笔出单，单笔出单-01、批量多人一单出单-02 String",
							                "disputedSettleMode"=>1,//【必填】":"争议解决方式(1-诉讼;2-仲裁) String"
							                "isPolicyBeforePayfee"=>"0",// 是否是见费出单(1-是;0-否) String"
							                "remark"=>"个财"
							                )
            			);
            			
	//insurantInfoList，被保险人信息
	$list_insurant = $list_subject[0]['list_subject_insurant'];
	$list_product  = $list_subject[0]['list_subject_product'] ;
	$arr_insurantInfoList = array();
	//通过
	//两个险种放在不同的层级
	//获取被保险人个数
	if($list_product[0]['product_code']== 'MP02000013')//账户资金产品
	{
		$per_insurant = $list_insurant[0];
		$age = get_age_by_birthday($per_insurant['birthday']);
		$arr_insurantInfoList[]=array( "age"=>$age,//年龄 Short"
					                    "birthday"=>$per_insurant['birthday'],//【必填】":"出生年月 Date yyyy-MM-dd",
					                    "certificateNo"=>$per_insurant['certificates_code'],//【必填】":"证件号码 String",
					                    "certificateType"=>$per_insurant['certificates_type'],//【必填】":" 证件类型,01:身份证、02：护照、03：军人证、04：港澳通行证，05：驾驶证、06：港澳回乡证或台胞证，07：临时身份证、99：其他", 
					                    "homeTelephone"=>$per_insurant['telephone'],
					                    "mobileTelephone"=>$per_insurant['mobiletelephone'],
					                    "name"=>$per_insurant['fullname'],//【必填】":"名称 ",
					                    "officeTelephone"=>"",
					                    "personnelType"=>"1",//【必填】":"个团标志[1个人，0团体] String"
					                    "sexCode"=>$per_insurant['gender']); //【必填】":"性别 String",
		$num_insurant = 1;					                    
	}
	else//家居综合，家财
	{
		$num_insurant = 0;
		foreach($list_insurant as $key=>$per_insurant)//多个被保险人的情况，如果是资金账户损失险产品，被保险人只有一个，且不是投保人本人呢？
		{
			$age = get_age_by_birthday($per_insurant['birthday']);
			if($per_insurant['type'] == 2)
			{
				$arr_insurantInfoList[]=array( "age"=>$age,//年龄 Short"
					                    "birthday"=>$per_insurant['birthday'],//【必填】":"出生年月 Date yyyy-MM-dd",
					                    "certificateNo"=>$per_insurant['certificates_code'],//【必填】":"证件号码 String",
					                    "certificateType"=>$per_insurant['certificates_type'],//【必填】":" 证件类型,01:身份证、02：护照、03：军人证、04：港澳通行证，05：驾驶证、06：港澳回乡证或台胞证，07：临时身份证、99：其他", 
					                    "homeTelephone"=>$per_insurant['telephone'],
					                    "mobileTelephone"=>$per_insurant['mobiletelephone'],
					                    "name"=>$per_insurant['fullname'],//【必填】":"名称 ",
					                    "officeTelephone"=>"",
					                    "personnelType"=>"1",//【必填】":"个团标志[1个人，0团体] String"
					                    "sexCode"=>$per_insurant['gender']); //【必填】":"性别 String",
					
			}
			
	         $num_insurant++;   
		}
	}
	
	$insurantInfoList['insurantInfoList'] = $arr_insurantInfoList;
	
	//productCode
	//$productCode['productCode'] = "MP02000057";//应该对应我们自己的险种代码
	
	//riskGroupInfoList
	//标的信息
	//for 循环多个层级,即多个subject的情况
	$house_id = getRandOnly_Id(0);
	$riskGroupInfoList = array();
	foreach($list_subject AS $key =>$value)
	{
		$property_id = getRandOnly_Id(0);
		//$index = $key+1;
		//产品信息列表
		$list_subject_product  = $value['list_subject_product'] ;
		//被保险人信息列表
		$list_subject_insurant = $value['list_subject_insurant'] ;
		//层级id？？？
		$policy_subject_id = $value['policy_subject_id'] ;
		
		//保险责任价格列表信息
		$list_subject_product_duty_price = $value['list_subject_product_duty_price'] ;
	
		//planInfo
		$subject_total_premium = 0;

		//for 循环添加多个planInfo，即多个产品信息，由于
		//这个是组合产品，得根据产品代码区分不同的险种下的产品（包括主险和附加险）
		$planInfoList = array();
		//先换遍历一个险种下的多个产品
		foreach($list_subject_product as $keys=>$product_value)
		{
			
			$plan_total_premium = 0;
			//duty信息,责任信息
			//for 循环添加多个dutyInfo
			//得先找到当前用户已投产品下用户选取的责任信息，可能一个也可能多个
				$wheresql = "ipd.product_id=$product_value[product_id] AND  pspdp.policy_subject_id=$policy_subject_id";
				
				$sql = "SELECT d.duty_name, d.duty_code,ipdp.premium,ipdp.amount,ipdp.limitAmount FROM ".tname('insurance_product_duty_price')." AS ipdp 
						INNER JOIN ".tname('insurance_policy_subject_product_duty_prices')." AS pspdp 
				        ON pspdp.product_duty_price_id= ipdp.product_duty_price_id 
				        INNER JOIN ".tname('insurance_product_duty')." AS ipd 
				        ON ipdp.product_duty_id= ipd.product_duty_id 
						INNER JOIN ".tname('insurance_duty')." AS d 
				        ON d.duty_id= ipd.duty_id 
						WHERE ". $wheresql;
				ss_log(__FUNCTION__.", get product duty price sql:".$sql);
				$query = $_SGLOBAL['db']->query($sql);
				//循环当前产品下的每一个duty
				$dutyInfoList=array();
				//循环便利一个产品下的一个或者是多个责任。
				
				//$dutyNoclaimInfoList[]= array();
			
				while($product_duty_price = $_SGLOBAL['db']->fetch_array($query) )
				{
					$dutyNoclaimInfoList=array();
					$dutycode= $product_duty_price['duty_code'];
					$duty_limitAmount = $product_duty_price['limitAmount'];
					
					//mod by zhangxi, 20150718,对于前端用户自动设置保额的情况，这要要矫正
					//保额
					if($product_value['product_code'] == 'MP02000005')
					{
						$dutyAount = $policy_arr['policy_amount'];
						$duty_totalModalPremium = round($dutyAount*0.0002);//单个责任的保费
					}
					else
					{
						$dutyAount = $product_duty_price['amount'];
						$duty_totalModalPremium = $product_duty_price['premium'];//单个责任的保费
					}
					
					//如果是意外险， 多个被保险人，是否应该是多个责任关联呢，一个责任关联一个人
					//mod by zhangxi, 20150720 
					
					$riskDutyRelationInfoList = array();
				
					if('CV57013' == $dutycode
					|| 'CV72016' == $dutycode)
					{
						$tmp_indx = 0;
						foreach($list_insurant as $key=>$per_insurant)
						{
							$riskDutyRelationInfoList[] = array("riskInfoId"=>$property_id.$tmp_indx,
																"dutyCode"=>$dutycode,
																"insuredAmount"=>$per_insurant['amount1'],
																"actualPremium"=>$duty_totalModalPremium,                                                    
												                "agreePremium"=>$duty_totalModalPremium,
												                "standardPremium"=>$duty_totalModalPremium
																);
																$tmp_indx++;
						}
					}
					elseif('CV57024' == $dutycode
					|| 'CV72017' == $dutycode)
					{
						$tmp_indx = 0;
						foreach($list_insurant as $key=>$per_insurant)
						{
							$riskDutyRelationInfoList[] = array("riskInfoId"=>$property_id.$tmp_indx,
														"dutyCode"=>$dutycode,
														"insuredAmount"=>$per_insurant['amount2'],
														"actualPremium"=>$duty_totalModalPremium,                                                    
										                "agreePremium"=>$duty_totalModalPremium,
										                "standardPremium"=>$duty_totalModalPremium
														);
														$tmp_indx++;
						}
					}
					else
					{
//							$riskDutyRelationInfoList[] = array("riskInfoId"=>$property_id,
//														"dutyCode"=>$dutycode,
//														"insuredAmount"=>$dutyAount,
//														"actualPremium"=>$duty_totalModalPremium,                                                    
//										                "agreePremium"=>$duty_totalModalPremium,
//										                "standardPremium"=>$duty_totalModalPremium
//														);
					}
					
					
					//免赔信息处理
					if('CV72002' == $dutycode)	
					{
						$dutyNoclaimInfoList[] = array("dutyCode"=>$dutycode,
														"noclaimAmount"=>300,
														//"noclaimRate"=>0
														); 
					}									
															
															
					$dutyInfoList[] =array("dutyChineseName"=>$product_duty_price['duty_name'],
										"dutyCode"=>$product_duty_price['duty_code'],
										"dutyName"=>$product_duty_price['duty_name'],
										"totalStandardPremium"=>$duty_totalModalPremium,
						                "totalAgreePremium"=>$duty_totalModalPremium,
						                "totalActualPremium"=>$duty_totalModalPremium,
										"dutyNoclaimInfoList"=>$dutyNoclaimInfoList,
										"fixAmount"=>floatval($dutyAount),
										"forceSelected"=>"1",
										"insuredAmount"=>floatval($dutyAount),
										"compensationMaxAmount"=>floatval($duty_limitAmount),//每次赔偿限额 Double
										"isDutySharedAmount"=>0,
										"totalInsuredAmount"=>floatval($dutyAount),
										"riskDutyRelationInfoList"=>$riskDutyRelationInfoList
										);
					//每一个产品的保费统计
					$plan_total_premium += $product_duty_price['premium']*$policy_arr['apply_num'];
				}//end for dutyInfo
				
				//对应我们的产品列表，有主产品，附加产品

				$plan_totalModalPremium = $plan_total_premium;
				$planInfoList[] = array("dutyInfoList"=>$dutyInfoList,
										"planCode"=>$product_value['plan_code'],
										"planName"=>$product_value['plan_name'],
										);					
					
			//每一个险种的保费
			$subject_total_premium += $plan_total_premium;
				
		}//end for planInfo
		
		$riskPropertyInfoList=array();
		$productClass = '02';
		//家财宝
		if($product_value['product_code'] == 'MP02000001'//家财宝
		|| $product_value['product_code'] == 'MP02000004')//家居财务保障
		{
			
			$riskGroupType = '01';
			$riskPropertyInfoList[] = array("id"=>$property_id, 
				                        "riskPropertyMap"=>array(
				                            "id"=>$property_id, 
				                            "buildingStructure"=>$user_info_applicant['house_type'], //房屋结构字段
				                            "country"=>"01", 
				                            "province"=>$user_info_applicant['province_code'], 
				                            "address"=>($user_info_applicant['province']."".$user_info_applicant['city']."".$user_info_applicant['county']."".$user_info_applicant['address']), 
				                            "city"=>$user_info_applicant['city_code'], 
				                            "county"=>$user_info_applicant['county_code'],
				                            //"buildingAssessPrice"=>0,
				                            //"loanAmount"=>0,
				                            
				                        ), 
				                        "riskAddressId"=>$house_id
				                        );
			$num_insurant = $policy_arr['apply_num'];
		}
		elseif($product_value['product_code'] == 'MP02000005')//家居综合相关
		{
			$product_value['product_code'] = 'MP02000004';
			$riskGroupType = '01';
			$riskPropertyInfoList[] = array("id"=>$property_id, 
				                        "riskPropertyMap"=>array(
				                            "id"=>$property_id, 
				                            "buildingStructure"=>$user_info_applicant['house_type'], //房屋结构字段
				                            "country"=>"01", 
				                            "province"=>$user_info_applicant['province_code'], 
				                            "address"=>($user_info_applicant['province']."".$user_info_applicant['city']."".$user_info_applicant['county']."".$user_info_applicant['address']), 
				                            "city"=>$user_info_applicant['city_code'], 
				                            "county"=>$user_info_applicant['county_code'],
				                            //"buildingAssessPrice"=>0,
				                            //"loanAmount"=>0,
				                            
				                        ), 
				                        "riskAddressId"=>$house_id
				                        );
			$num_insurant = $policy_arr['apply_num'];
		}
		//意外保障
		elseif($product_value['product_code'] == 'MP02000007'//家财宝意外
		 || $product_value['product_code'] == 'MP02000008')//家居宝意外
		{
			$geyi_id = getRandOnly_Id(0);
			$riskGroupType = '41';
			$productClass='03';
			//多个被保险人
			$tmp_index=0;
			foreach($list_subject_insurant as $insurant_key=>$insurant_value)
			{
				//mod by zhangxi, 20150720,要多个被保险人每个人和对应的riskDutyRelationInfoList
				//中的项对应
				$riskPropertyInfoList[] = array(
                        					"id"=>$property_id.$tmp_index, 
                        					"riskPropertyMap"=>array(
										                            "id"=>$property_id.$tmp_index, 
										                            "relation"=>$insurant_value['relation'], 
										                            "personnelName"=>$insurant_value['fullname'], 
										                            "birthday"=>$insurant_value['birthday']
										                            ),
				                        	);
				 $tmp_index++;
			}
			
		}
		//A80个人账户资金的情况
		elseif($product_value['product_code'] == 'MP02000013')
		{
			$insurant_value = $list_subject_insurant[0];
			$riskGroupType = '01';//这个值还没有获取，得保险公司提供
			$riskPropertyInfoList[] = array(
                        					"id"=>$property_id, 
                        					"riskPropertyMap"=>array(
										                            "id"=>$property_id, 
										                            "relation"=>$insurant_value['relation'], 
										                            "personnelName"=>$insurant_value['fullname'], 
										                            "birthday"=>$insurant_value['birthday']
										                            ),
				                        	);
		}
		$beneficaryInfoList = array("name"=>"法定");
		//mod by zhangxi, 20150713, A款，B款这样所对应的代码信息获取
		ss_log(__FUNCTION__.", list_subject_product_duty_price=".var_export($list_subject_product_duty_price,true));
		$product_duty_price_id = $list_subject_product_duty_price[0]['product_duty_price_id'];
		$wheresql = "ipdp.product_duty_price_id=$product_duty_price_id";
				
		$sql = "SELECT ipi.* FROM ".tname('insurance_product_influencingfactor')." AS ipi 
		        INNER JOIN ".tname('insurance_product_duty_price')." AS ipdp 
		        ON ipdp.product_period_id= ipi.product_influencingfactor_id 
				WHERE ". $wheresql;
		$query = $_SGLOBAL['db']->query($sql);
		$influencingfactor = $_SGLOBAL['db']->fetch_array($query);
		
		
		$riskGroupInfoList[] = array("riskGroupType"=>$riskGroupType,//??? 
                "combinedProductCode"=>$product_value['product_code'], 
                "combinedProductVersion"=>"1.0", 
                "productName"=>$product_value['product_name'], //具体产品名称
                "productClass"=>$productClass, 
                "beneficaryInfoList"=>$beneficaryInfoList,
                "riskPropertyInfoList"=>$riskPropertyInfoList,
                "planInfoList"=>$planInfoList,
                 "productPackageType"=>$influencingfactor['additional_code'], 
                "riskGroupName"=>$influencingfactor['factor_name'], //后续再确认下
                "totalStandardPremium"=>$subject_total_premium, 
                "totalActualPremium"=>$subject_total_premium, 
                "totalAgreePremium"=>$subject_total_premium, 
                "applyRiskNum"=>$num_insurant,//这里有问题，意外保障可能跟被保险人个数有关
      
									);
		$total_premium += $subject_total_premium;	
		
	}//end foreach $subjectlist
	$riskGroupInfoList1 = array("riskGroupInfoList"=>$riskGroupInfoList);
	
	//地址信息,家财和家居会有

	$addressinfo = array(  "id"=>$house_id, 
		                "wholeAddress"=>($user_info_applicant['province']."".$user_info_applicant['city']."".$user_info_applicant['county']."".$user_info_applicant['address']), 
		                "country"=>"01", 
		                "province"=>$user_info_applicant['province_code'], 
		                "city"=>$user_info_applicant['city_code'], 
		                "county"=>$user_info_applicant['county_code']
						);
	$riskAddressInfoList=array("riskAddressInfo"=>$addressinfo
														);
	$arr_product_info[] = array_merge($baseInfo, $extendInfo , $riskGroupInfoList1,$insurantInfoList,$riskAddressInfoList);

	
	$productInfoList=array("productInfoList"=>$arr_product_info);
	return $productInfoList;
}

//生成投保的json报文
function gen_pingan_property_json($attribute_type,
											$attribute_code,
									$policy_arr,//保单相关信息
											$user_info_applicant,//投保人信息
											$list_subject)//subject信息，被保险人信息也在这里面
{
	//partnerName
	$partnerName["partnerName"] = PINGAN_PROPERTY_PREFIX.PINGAN_PROPERTY_USERNAME;
//	$saleInfo = gen_pingan_property_json_saleInfo($policy_arr,//保单相关信息
//													$user_info_applicant,//投保人信息
//													$list_subject);
	$applicantInfoList = gen_pingan_property_json_applicantInfoList(
													$attribute_type,
													$attribute_code,
													$policy_arr,//保单相关信息
													$user_info_applicant,//投保人信息
													$list_subject);
	$productInfoList = gen_pingan_property_json_productInfoList(
													$attribute_type,
													$attribute_code,
													$policy_arr,//保单相关信息
													$user_info_applicant,//投保人信息
													$list_subject);	
	$arr_all = array_merge($partnerName, $applicantInfoList, $productInfoList);																			
	return $arr_all;
}

function get_pingan_property_accessToken($logFileName)
{
		global $java_class_name_pingan_property;
		$url = PINGAN_PROPERTY_URL_GET_TOKEN;
		$obj_java = create_java_obj($java_class_name_pingan_property);
		if(!$obj_java)
		{
			$result = 112;
			$retmsg = "create_java_obj fail!";
			ss_log($retmsg);
				
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
				
				
			return NULL;
		}
		$return_data = (string)$obj_java->getAccessToken( 
													$url ,
													$logFileName
											);
		ss_log(__FUNCTION__.", return org data: ".$return_data);
	$return_data = json_decode((string)$return_data, true);
	$error = json_last_error();
	ss_log(__FUNCTION__.", json errorno: ".$error);
	ss_log(__FUNCTION__.", return tranfered data: ".var_export($return_data,true));									
	if($return_data['ret'] == '0')
	{
		$data = $return_data['data'];
		$accessToken = $data['access_token'];
	}	
	else
	{
		return NULL;
	}
	return $accessToken;
}

//added by zhangxi, 20150617, 财产险的投保接口
function post_policy_pingan_property($attribute_type,//add by wangcya, 20141213
									$attribute_code,//add by wangcya, 20141213
									$policy_arr,
									$user_info_applicant,
									$list_subject)
{
	global $_SGLOBAL, $java_class_name_pingan_property;
	$policy_id = $policy_arr['policy_id'];
	$orderNum =  $policy_arr['order_num'];
	$order_sn = $policy_arr['order_sn'];
	$policy_obj=gen_pingan_property_json($attribute_type,
										$attribute_code,
										$policy_arr,//保单相关信息
										$user_info_applicant,//投保人信息
										$list_subject);
		//中文不进行编码
		$strjson_post_policy_content = TO_JSON($policy_obj);
	//$strjson_post_policy_content = json_encode($policy_obj);															
	if(!empty($policy_obj))
	{
		ss_log(__FUNCTION__.":json文件生成完成");
		$af_path = gen_file_fullpath('policy','pingan',S_ROOT,$order_sn."_".$policy_id."_pingan_property_policy_post.json");
		//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_property_policy_post.json";

		$bytes_num = file_put_contents($af_path,$strjson_post_policy_content);
		ss_log(__FUNCTION__."：写入字节数:".$bytes_num);
		
		$FILE_UUID = getRandOnly_Id(0,1);//这个要求每次在变化。
		//$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_pingan_property_policy_post.json";
		$af_path_serial = gen_file_fullpath('policy', 'pingan', S_ROOT, $order_sn."_".$policy_id."_".$FILE_UUID."_pingan_property_policy_post.json");
		file_put_contents($af_path_serial,$strjson_post_policy_content);
		////////////////////////////////////////////////////////////////
		//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
		if(!defined('USE_ASYN_JAVA'))
		{
			$java_class_name = $java_class_name_pingan_property;
		}
		else
		{
			$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
		}
		$java_class_name = empty($java_class_name)?$java_class_name_pingan_property:$java_class_name;
		$obj_java = create_java_obj($java_class_name);
		if(!$obj_java)
		{
			$result = 112;
			$retmsg = "create_java_obj fail!";
			ss_log($retmsg);
				
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
				
				
			return $result_attr;
		}
		$logFileName = S_ROOT."xml/log/pingan_property_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";	
		$accessToken = get_pingan_property_accessToken($logFileName);
		$url = PINGAN_PROPERTY_URL_POST_POLICY.$accessToken;
		$respXmlFileName = "";
		
		ss_log("url: ".$url);
		//$return_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_property_policy_post_ret.json";
		$return_path = gen_file_fullpath('policy', 'pingan', S_ROOT, $order_sn."_".$policy_id."_pingan_property_policy_post_ret.json");
		ss_log("return_path: ".$return_path);
		
		if(!defined('USE_ASYN_JAVA'))//同步投保方式
		{
			ss_log(__FUNCTION__.", 将要进行同步投保，will sendMessage");
			$return_data = (string)$obj_java->insure( 
													$url ,
													'input',
													$strjson_post_policy_content ,
													$logFileName
											);
			
			ss_log(__FUNCTION__.", after post call function insure");
			

			//////////////////////////////////////////////////////////////
			if(!empty($return_data))
			{
				//投保动作的返回XML信息，先保存一份
				
				file_put_contents($return_path,$return_data);
				$use_asyn=false;
				$result_attr = process_return_json_data_pingan_property(
																$policy_id,
																$return_data,
																$order_sn,
																$orderNum,
																$use_asyn
														);
				
				//////////////////////////////////////////////////////////
			}
			else
			{
				$result = 110;
				$retmsg = "post policy return is null!";
				ss_log(__FUNCTION__.", ".$retmsg);
				$result_attr = array(	'retcode'=>$result,
						'retmsg'=> $retmsg
				);
				
			}//end $return_data
			return $result_attr;
		
		}//USE_ASYN_JAVA end
		else
		{//使用异步方式
			ss_log(__FUNCTION__.", 将要发送投保的异步请求，will doService");
			//ss_log("before use asyn,doService");
			
			$xmlContent = "";//$strxml_post_policy_content;
			$postXmlFileName = $af_path;
	
			$postXmlEncoding = "UTF-8";//GBK";
			$respXmlFileName = $return_path;
			$logFileName = S_ROOT."xml/log/pingan_property_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
					
			$insurer_code = $policy_arr['insurer_code'];
			$callBackURL = CALLBACK_URL;
			$type = "insure";
		
			ss_log(__FUNCTION__." insurer_code: ".$insurer_code);
			ss_log(__FUNCTION__." type: ".$type);
			ss_log(__FUNCTION__." callBackURL: ".$callBackURL);
			ss_log(__FUNCTION__." policy_id: ".$policy_id);

			$param_other = array(   "url"=>$url ,//投保保险公司服务地址
									"key"=>'input' ,//端口
									"xmlContent"=>$xmlContent ,//投保报文内容
									"postXmlFileName"=>$postXmlFileName,//投保报文文件
									"postXmlEncoding"=>$postXmlEncoding ,//投保报文文件编码格式
									"respXmlFileName"=>$respXmlFileName,//返回报文保存全路径
									"respXmlFileEncoding"=>'UTF-8',
									"logFileName"=>$logFileName //日志文件
									);
			
			ss_log(__FUNCTION__.", param_other: ".var_export($param_other,true));
			
			$jsonStr = json_encode($param_other);
			
		
			$successAccept = (string)$obj_java->doService(
													$insurer_code,
													$type,
													$policy_id,
													$callBackURL,
													$jsonStr
													);
			
			$result = 0;
			$retmsg = "after use asyn, ret: ".$successAccept;
			ss_log(__FUNCTION__.", ".$retmsg);
			$result_attr = array(	'retcode'=>$result,
									'retmsg'=> $retmsg
									);
			
			return $result_attr;
		}
	}//not empty($strxml_post_policy_content)
	else
	{
		ss_log(__FUNCTION__.": gen strxml null");
	}
	return $result_attr;
	
}


//进行平安各险种的投保动作
function post_policy_pingan(
							$attribute_type,//add by wangcya, 20141213
							$attribute_code,//add by wangcya, 20141213
							$policy_arr,
							$user_info_applicant,
							$list_subject
						   )
{

	global $_SGLOBAL,$g_ATTRIBUTE_TYPE_PAC;
	
	ss_log("into function post_policy_pingan");
	
	$policy_id = $policy_arr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	

	$list_subject_product  = $list_subject[0]['list_subject_product'] ;
	$product_info = $list_subject_product[0];//add by wangcya, 20141204
	
	if(!in_array($attribute_type, $g_ATTRIBUTE_TYPE_PAC))//add by wangcya, 20150506
	{
		ss_log(__FUNCTION__.", in function post_policy_pingan, not in scope, so return,attribute_type: ".$attribute_type);
		return ;
	}
	
	$orderNum =  $policy_arr['order_num'];//这个填写的就是唯一的号码
	$order_sn = $policy_arr['order_sn'];//add by wangcya, 20141023
	$insurer_code = $policy_arr['insurer_code'];
	
	if($attribute_type == "Y502")
	{
		ss_log("into function post_policy_pingan, will gen_pingan_policy_xml_Y502");
		$strxml_post_policy_content = $strxml = gen_pingan_policy_xml_Y502($policy_arr,//保单相关信息
											$user_info_applicant,//投保人信息
											$list_subject);//subject信息，被保险人信息也在这里面
	}
	elseif($attribute_type == "jingwailvyou")//
	{
		ss_log("into function gen_post_policy_xml_pingan_jingwailvyou, will gen_post_policy_xml_pingan ");
		
		$product_code = $policy_arr['product_code'];//add by wangcya, 20150110, 这个时候使用的是policy中的product_code,这是转换后的。
		ss_log("use policy's product_code: ".$product_code);
		if(empty($product_code))
		{
			$product_code = $list_subject[0]['list_subject_product'][0]['product_code'];
		}
		
		ss_log("product_code: ".$product_code);
		
		$insurance_company = find_BRNO_by_product_code($product_code);
		if(empty($insurance_company))
		{
			ss_log(__FUNCTION__.", find_BRNO_by_product_code fail!");
			return ;
		}
		//start add by wangcya,. 20150506,找到业务落地点
		$partnerName = $insurance_company['partnerName'];
		$BANK_CODE = $insurance_company['BANK_CODE'];
		$BRNO = $insurance_company['BRNO'];
		//end add by wangcya,. 20150506,找到业务落地点
		
		ss_log("partnerName: ".$partnerName." BANK_CODE: ".$BANK_CODE." BRNO: ".$BRNO);
		
		$strxml_post_policy_content = $strxml = gen_post_policy_xml_pingan_jingwailvyou(
				$policy_arr,//保单相关信息
				$user_info_applicant,//投保人信息
				$list_subject,
				$partnerName,
				$BANK_CODE,
				$BRNO
			);
		
	}	
	else
	{
		ss_log("into function post_policy_pingan, will gen_post_policy_xml_pingan ");
		
		//start add by wangcya, 20150110
		$product_code = $policy_arr['product_code'];//add by wangcya, 20150110, 这个时候使用的是policy中的product_code,这是转换后的。
		ss_log("use policy's product_code: ".$product_code);
		if(empty($product_code))
		{
			$product_code = $list_subject[0]['list_subject_product'][0]['product_code'];
		}
		
		$insurance_company = find_BRNO_by_product_code($product_code);
		if(empty($insurance_company))
		{
			ss_log("find_BRNO_by_product_code fail!");
			return ;
		}
		
		//start add by wangcya,. 20150506,找到业务落地点
		$partnerName = $insurance_company['partnerName'];
		$BANK_CODE = $insurance_company['BANK_CODE'];
		$BRNO = $insurance_company['BRNO'];
		
		ss_log("partnerName: ".$partnerName." BANK_CODE: ".$BANK_CODE." BRNO: ".$BRNO);
		//end add by wangcya,. 20150506,找到业务落地点
		
		$strxml_post_policy_content = gen_post_policy_xml_pingan(
												$policy_arr,//保单相关信息
												$user_info_applicant,//投保人信息
												$list_subject,
												$partnerName,
												$BANK_CODE,
												$BRNO
											 );
	}
		if(!empty($strxml_post_policy_content))
	{
		ss_log(__FUNCTION__.":xml 生成正确");
		//$toubaoshu_xml_path = $af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_policy_post.xml";
		$toubaoshu_xml_path= $af_path = gen_file_fullpath('policy','pingan',S_ROOT, $order_sn."_".$policy_id."_pingan_policy_post.xml");

		$bytes_num = file_put_contents($af_path,$strxml_post_policy_content);//zhx ,生成准备投保的文件
		ss_log(__FUNCTION__."：写入字节数:".$bytes_num);
	}
	
	/////////////////////////////////////////////////////////////////
	if(!defined('USE_ASYN_JAVA'))
	{
		//同步
		$commModel = 'S';
		$url = EBAO_JAVA_SERVICE_URL_SYNC;
	}
	else
	{
		//异步
		$commModel = 'A';
		$url = EBAO_JAVA_SERVICE_URL_ASYN;
	}

	
	
	$type = "insure_accept";
	$UUID = getRandOnlyId();
	$channelcode = CHANNEL_CODE;
	
	$post_url = $url."?channelCode=$channelcode&signType=MD5&type=$type&keyid=$policy_id&insurer_code=$insurer_code&attribute_type=$attribute_type";
	
	$product_code = $policy_arr['product_code'];
	$insurance_company = find_BRNO_by_product_code($product_code);
		if(empty($insurance_company))
		{
			ss_log(__FUNCTION__.", find_BRNO_by_product_code fail!");
			return ;
		}
		
		//start add by wangcya,. 20150506,找到业务落地点
		$partnerName = $insurance_company['partnerName'];
		$BANK_CODE = $insurance_company['BANK_CODE'];
		$BRNO = $insurance_company['BRNO'];
		$policy_arr['brno'] = $BRNO;
		$policy_arr['partnerName'] = $partnerName;
		$policy_arr['bank_code'] = $BANK_CODE;
		$policy_arr['ptp_code'] = '2011402';
		$policy_arr['alterableSpecialPromise'] = '';
		$destinationCountry = $policy_arr['destinationCountry'];
		global $pingan_jingwailvyou_country;
		//mod by zhangxi, 20150206, 增加申根协议国家名字显示格式的处理
		if($pingan_jingwailvyou_country[$destinationCountry] == "申根协议国家")
		{
			$name = explode(" ", $destinationCountry);
			$destinationCountry = $name[0].", 申根协议国家/".$name[1].", SCHENGEN STATES";
		}
		$policy_arr['destinationCountry'] = $destinationCountry;
		
		
		if($attribute_type == "Y502")
		{
			$arr_time=array('start_date'=>$policy_arr['start_date'],
					'end_date'=>$policy_arr['end_date'],
					);
			$arr_interval = get_interval_by_strtime('month', $arr_time);
			ss_log("get month is ".$arr_interval['interval']."start time".$arr_time['start_date']."end time ".$arr_time['end_date']);
			$policy_arr['apply_month'] = $arr_interval['interval'];
		}
		//$policy_arr['$partnerName']
	//added by zhx, 20151009
	$data_interface = array("channelCode"=>"ebaoins",
							"type"=>$type,
							"keyid"=>$policy_id,
							"transSn"=>$UUID,
							"commModel"=>$commModel,
							"transTime"=>date('Y-m-d H:i:s',$_SGLOBAL['timestamp']),
							"callBackURL"=>CALLBACK_URL,
							"policy_arr"=>$policy_arr,
							"user_info_applicant"=>$user_info_applicant,
							"list_subject"=>$list_subject
							);
	$jsonStr = TO_JSON($data_interface);
	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_policy_post.json";
	//$af_path = gen_file_fullpath('policy','pingan',$order_sn."_".$policy_id."_pingan_policy_post.json");
	
	file_put_contents($af_path,$jsonStr);
	$post_str = $jsonStr;
	$ret = post_request_to_service('http',EBAO_JAVA_SERVICE_IP,EBAO_JAVA_SERVICE_PORT,
							$post_str,$post_url,$type,$commModel);
	//异步处理
	if($commModel == 'A')
	{
		if($ret['rspCode'] == '100000'//处理中
		|| $ret['rspCode'] == '000000')
		{
			$result_attr = array(	'retcode'=>0,
									'retmsg'=> "send post request success,waiting reponse"
									);
		}
		else
		{
			$result_attr = array(	'retcode'=>110,
									'retmsg'=> "send error"
									);
		}
	}		
	//同步处理
	else
	{
		//没有用到，暂时不写
	}	
	
	

	return $result_attr;
	
	//////////下面的都是要删除的代码
	//add by zhangxi, 20141208, add code here for y502 ,在这里走不同的分支进行处理
	
	//////////////////////////////////
							
	if(!empty($strxml_post_policy_content))
	{
		ss_log(__FUNCTION__.":xml 生成正确");
		//$toubaoshu_xml_path = $af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_policy_post.xml";
		$toubaoshu_xml_path= $af_path = gen_file_fullpath('policy','pingan',S_ROOT, $order_sn."_".$policy_id."_pingan_policy_post.xml");

		$bytes_num = file_put_contents($af_path,$strxml_post_policy_content);//zhx ,生成准备投保的文件
		ss_log(__FUNCTION__."：写入字节数:".$bytes_num);
		
		//start add by wangcya, 20150209,把携带序列号的也保存起来
		$FILE_UUID = getRandOnly_Id(0,1);//这个要求每次在变化。
		//$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_pingan_policy_post.xml";
		$af_path_serial = gen_file_fullpath('policy','pingan',S_ROOT,$policy_id."_".$FILE_UUID."_pingan_policy_post.xml");
		file_put_contents($af_path_serial,$strxml_post_policy_content);

		////////////////////////////////////////////////////////////////
		//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
		
		$url = URL_PINGAN_POST_POLICY;
		$port =  8107;
		//mod by zhangxi, 20150727, 
		if($attribute_type == "Y502")
		{
			$keyFile = S_ROOT.CLIENT_KEY_PINGAN_DALIAN_Y502;
			$keypassword = 'paic1234';
		}
		else
		{
			$keyFile = S_ROOT.CLIENT_KEY_PINGAN;
			$keypassword = 'yangmingsong';
		}
		
		$respXmlFileName = "";
		
		ss_log("url: ".URL_PINGAN_POST_POLICY);
		ss_log("keyFile: ".$keyFile);
		
		//$return_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_policy_post_ret.xml";
		$return_path = gen_file_fullpath('policy','pingan',S_ROOT, $order_sn."_".$policy_id."_pingan_policy_post_ret.xml");
		ss_log("return_path: ".$return_path);
		
		
		if(!defined('USE_ASYN_JAVA'))//同步投保方式
		{
			ss_log("将要进行同步投保，will sendMessage");
			$return_data = (string)$obj_java->sendMessage( 
													$url ,
													$port ,
													$strxml_post_policy_content ,
													$respXmlFileName,
													$keyFile,
													$keypassword,
													$keypassword
											);
			//////////////////////////////////////////////////////////////
			if(!empty($return_data))
			{
				//投保动作的返回XML信息，先保存一份
				
				file_put_contents($return_path,$return_data);
		
				$result_attr = process_return_xml_data_pingan(
																$policy_id,
																$return_data,
																$order_sn,
																$orderNum
														);
				if($result_attr['retcode']==0)//success
				{
					/////////////start add by wangcya, 20141204,投保书////////////////////////////////
					if( $product_code=="01097"||//A
							$product_code== "01098"||//B
							$product_code == "01225"||//C
							$product_code == "01099"||//安相伴少儿卡
							$product_code == "01331"||//平安少儿综合保险
							$product_code == "01332"//平安少儿综合保险
					)
					{
		
						gen_toubaoshu_pdf(
											$strxml_post_policy_content,
											$order_sn,
											$policy_id
											);
					}
					/////////////end add by wangcya, 20141204,投保书////////////////////////////////
				}
				//////////////////////////////////////////////////////////
			}
			else
			{
				$result = 110;
				$retmsg = "post policy return is null!";
				ss_log($retmsg);
				$result_attr = array(	'retcode'=>$result,
						'retmsg'=> $retmsg
				);
				return $result_attr;
			}//end $return_data
		
		}//USE_ASYN_JAVA end
		//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
		else
		{//批量投保时候使用异步方式
			ss_log("将要发送投保的异步请求，will doService");
			$xmlContent = "";//$strxml_post_policy_content;
			$postXmlFileName = $af_path;
	
			$postXmlEncoding = "UTF-8";//GBK";
			$respXmlFileName = $return_path;
			$logFileName = S_ROOT."xml/log/pingan_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
					
			$insurer_code = $policy_arr['insurer_code'];
			$callBackURL = CALLBACK_URL;
			$type = "insure";
		
			ss_log("insurer_code: ".$insurer_code);
			ss_log("type: ".$type);
			ss_log("callBackURL: ".$callBackURL);
			ss_log("policy_id: ".$policy_id);
			
			
			$param_other = array(   "url"=>$url ,//投保保险公司服务地址
									"port"=>$port ,//端口
									"xmlContent"=>$xmlContent ,//投保报文内容
									"postXmlFileName"=>$postXmlFileName,//投保报文文件
									"postXmlEncoding"=>$postXmlEncoding ,//投保报文文件编码格式
									"respXmlFileName"=>$respXmlFileName,//返回报文保存全路径
									"logFileName"=>$logFileName, //日志文件
									"keyFile"=>$keyFile,//平安证书文件 ,//投保保险公司服务地址
									"keyStorePassword"=>$keypassword,
									"trustStorePassword"=>$keypassword
									);
			
			ss_log("param_other: ".var_export($param_other,true));
			
			$jsonStr = json_encode($param_other);
			
		
			$successAccept = (string)$obj_java->doService(
													$insurer_code,
													$type,
													$policy_id,
													$callBackURL,
													$jsonStr
													);
			
			$result = 0;
			$retmsg = "after use asyn, ret: ".$successAccept;
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$result,
									'retmsg'=> $retmsg
									);
			
			return $result_attr;
		}
	}//not empty($strxml_post_policy_content)
	else
	{
		ss_log(__FUNCTION__.":gen strxml null");
	}
	

	/////////////////////////////////////////////////////////////
	
	return $result_attr;

}


//start add by wangcya , 20150106,模块化

function gen_post_policy_xml_pingan(
		$policy_arr,//保单相关信息
		$user_info_applicant,//投保人信息
		$list_subject,
		$partnerName,
		$BANK_CODE,
		$BRNO
	   )
{
	global $_SGLOBAL;
	///////////////////////////////////////////////////////////////////
	ss_log("into function ".__FUNCTION__);
	
	$result = 1;
	$retmsg = "";
	/////////////////////////////////////////////////////////////////
	$DATE = date('Ymd',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30
	//$orderNum = $policy_arr['order_num'];//如果这个重复了，就会导致返回为空，但是有节点。这个填写的就是订单号,
	$BK_SERIAL = getRandOnly_Id(0);//add by wangcya, 20150326,平安要求每次交易流水号（也就是每次请求）需要变化
	
	//$partnerSystemSeriesNo 可以当做投保单号
	$partnerSystemSeriesNo = $policy_arr['policy_id'].$policy_arr['order_num'];////add by wangcya, 20150326,policy_id这是唯一的，$orderNum
	
	ss_log("before gen post xml, policy_id: ".$policy_arr['policy_id']." SERIAL: ".$BK_SERIAL);
	
	///////////////////////////////////////////////////////////////////////

	if($list_subject)
	{
		ss_log("list_subject is ok");
	}
	else
	{
		$result = 1;
		$retmsg = "list_subject list is null";
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		return $result_attr;
	}

	$attribute_id = $policy_arr['attribute_id'];
	if($attribute_id)
	{

		$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE attribute_id='$attribute_id' LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$product_attribute = $_SGLOBAL['db']->fetch_array($query);


		//comment by zhangxi, 20141205, 特别约定信息
		if(($product_attribute['insurer_code']=="BYTPA_000011"||//丢不了
				$product_attribute['insurer_code']=="BYTPA_000012")&&//丢不了
				!empty($product_attribute['limit_note']))
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

	}//$attribute_id

	//start add by wangcya, 20150106,
	ss_log("policy_arr business_type: ".$policy_arr['business_type']);
	$businessType = $policy_arr['business_type'];
	//end add by wangcya, 20150106, 要更改为1为个人
	
	////////////////////////////////////////////////////////////
	if($policy_arr['business_type'] == 1)//<!-- (非空) 业务类型 1表示个人，2表示团体 -->
	{
		if(defined('IS_NO_GBK'))
		{
			$user_info_applicant_fullname =  $user_info_applicant['fullname']; //已知原编码为UTF-8, 转换为GBK
			$user_info_applicant_fullname = '<![CDATA['.$user_info_applicant_fullname.']]>';
		}
		else
		{
			$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}

		$insuranceApplicantInfo = '<individualPersonnelInfo>
		<personnelName>'.$user_info_applicant_fullname.'</personnelName>
		<sexCode>'.$user_info_applicant['gender'].'</sexCode>
		<certificateType>'.$user_info_applicant['certificates_type'].'</certificateType>
		<certificateNo>'.$user_info_applicant['certificates_code'].'</certificateNo>
		<birthday>'.$user_info_applicant['birthday'].'</birthday>
		<familyNameSpell />
		<firstNameSpell />
		<personnelAge />
		<mobileTelephone>'.$user_info_applicant['mobiletelephone'].'</mobileTelephone>
		<email>'.$user_info_applicant['email'].'</email>
		</individualPersonnelInfo>';

	}//business_type ==1
	elseif($policy_arr['business_type'] == 2 )//团体投保
	{

		/*del by wangya , 20140904 ,存的时候已经增加了
		 if($applicant_vice_name)//投保人增加一个附件说明
		{
		$user_info_applicant_fullname = $user_info_applicant_fullname."/".$applicant_vice_name;
		}
		*/

		if(defined('IS_NO_GBK'))
		{
			$user_info_applicant_fullname = $user_info_applicant['group_name']; //已知原编码为UTF-8, 转换为GBK
			$user_info_applicant_fullname = '<![CDATA['.$user_info_applicant_fullname.']]>';

			$groupAbbr =  $user_info_applicant['group_abbr']; //已知原编码为UTF-8, 转换为GBK
			$groupAbbr = '<![CDATA['.$groupAbbr.']]>';

			$address   =  $user_info_applicant['address']; //已知原编码为UTF-8, 转换为GBK
			$address = '<![CDATA['.$address.']]>';
		}
		else
		{
			$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['group_name'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			$groupAbbr =  mb_convert_encoding($user_info_applicant['group_abbr'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			$address =  mb_convert_encoding($user_info_applicant['address'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}



		$insuranceApplicantInfo =
		'<groupPersonnelInfo>
		<groupName>'.$user_info_applicant_fullname.'</groupName>
		<groupCertificateNo>'.$user_info_applicant['group_certificates_code'].'</groupCertificateNo>
		<groupCertificateType>'.$user_info_applicant['group_certificates_type'].'</groupCertificateType>
		<groupAbbr>'.$groupAbbr.'</groupAbbr>
		<address>'.$address.'</address>
		<postCode/>
		<companyAttribute>'.$user_info_applicant['company_attribute'].'</companyAttribute>
		<industryCode/>
		<businessRegisterId/>
		<phoneExchangeArea/>
		<phoneExchange/>
		<bankCode/>
		<bankAccount/>
		<businessRegionType/>
		<linkManName/>
		<linkManSexCode/>
		<linkManMobileTelephone/>
		<linkManEmail/>
		</groupPersonnelInfo>';
	}//business_type ==2
	//zhangxi, 没有else处理， 需要加上

	//$user_info_applicant[fullname] = iconv('UTF-8', 'GKB', $user_info_applicant[fullname]);
	//$user_info_assured[fullname] = iconv('UTF-8', 'GKB', $user_info_assured[fullname]);

	/*
	 6.	报文类实时交易报文中多次出现totalModalPremium（保费）的字段，他们的含义是什么样？是否都是同一个值？
答：报文类实时交易报文中出现totalModalPremium（保费）的字段有三个地方：
		/eaiAhsXml/Request/ahsPolicy/policyBaseInfo/totalModalPremium    称为保单保费
		/eaiAhsXml/Request/ahsPolicy/subjectInfo/subjectInfo/totalModalPremium  称为层级保费
/eaiAhsXml/Request/ahsPolicy/subjectInfo/subjectInfo/productInfo /productInfo/totalModalPremium  称为产品保费
例如：平安业务定义了产品号012345的产品销售价格为：1份10元，2份18元，3份26元的价格，那么产品保费就是填写10或18或26。而以上三个保费的关系公式是：
	层级保费==产品保费之和
	保单保费==层级保费之和
	目前大多数情况下，只有一个产品且只有一个层级，那么就形成了
	保单保费==层级保费==产品保费  的关系了。

	 */
	$apply_num = empty($policy_arr['apply_num'])?1:$policy_arr['apply_num'];
	
	$totalModalPremium = 0;

	$subjectInfo_str = '';
	
	foreach($list_subject AS $key =>$value)
	{
		$index = $key+1;
		$list_subject_product  = $value['list_subject_product'] ;
		$list_subject_insurant = $value['list_subject_insurant'] ;

		$product_info = $list_subject_product[0];//add by wangcya, 20141204

		$product_str = '';
	
		$subject_premium = 0;//一定要放到这里
		
		foreach($list_subject_product AS $key_product =>$value_product )
		{
			//mod by zhangxi, 20141202, 暂时从保单中取得投保份数和投保金额
			//如果有保单中有多个产品下，这样是不对的 。
			//但是目前数据中，对产品价格受影响因素影响的产品 ，是没法从保单获取产品具体定价的
			//这个后续在保单相关的信息中需要增加相关信息才行
	
			//mod by wangcya, 20150602
			//$policy_arr['total_premium'] = sprintf("%01.2f", $policy_arr['total_premium'] );
			
			//要看$policy_arr['apply_num']和$policy_arr['total_premium']的来源
			
			/*del by wangcya, 20150602
			$product_str .=   '
			<productInfo>
			<productCode>'.$value_product['product_code'].'</productCode>
			<applyNum>'.$policy_arr['apply_num'].'</applyNum>
			<totalModalPremium>'.$policy_arr['total_premium'].'</totalModalPremium>
			</productInfo>';
			*/
			//<applyNum>'.$value_product['number'].'</applyNum>
			//现在都是一份的价格，没有说多份的打折，number含义是最大可购买数目，和这里含义不相同。
			
			
			//mod by zhangxi , 20150610, 旅游线不能够从产品信息中获取保费，因为它是
			//保费受旅游时长影响的产品
			if($product_attribute['attribute_type'] =='lvyou'
			|| $product_attribute['attribute_type'] =='lvyouhuodong')
			{
				$policy_arr['total_premium'] = sprintf("%01.2f", $policy_arr['total_premium'] );
				$product_str .=   '
								<productInfo>
								<productCode>'.$value_product['product_code'].'</productCode>
								<applyNum>'.$policy_arr['apply_num'].'</applyNum>
								<totalModalPremium>'.$policy_arr['total_premium'].'</totalModalPremium>
								</productInfo>';
				$subject_premium = $subject_premium + $policy_arr['total_premium'];
			}
			else
			{
				$value_product['premium'] = sprintf("%01.2f", $value_product['premium'] );
				ss_log("产品 ".$value_product['product_name']."的每份价格为： ".$value_product['premium']);
				
				$product_str .=   '
								<productInfo>
								<productCode>'.$value_product['product_code'].'</productCode>
								<applyNum>'.$apply_num.'</applyNum>
								<totalModalPremium>'.($value_product['premium']*$apply_num).'</totalModalPremium>
								</productInfo>';
				//modify by wangcya, 20150602,
				$product_premium = $value_product['premium'];//$policy_arr['total_premium'];
				//汇总的为各个产品保费总和
				//$total_product_premium = $total_product_premium + $policy_arr['total_premium'];
				$subject_premium = $subject_premium + $product_premium*$apply_num;
			}
			
		}//$list_subject_product

		//得到多个被保险人。
		$insurantInfo_str = '';
		foreach($list_subject_insurant AS $key_insurant =>$value_insurant )
		{
			if(defined('IS_NO_GBK'))
			{
				$user_info_assured_fullname =  $value_insurant['fullname']; //已知原编码为UTF-8, 转换为GBK
				$user_info_assured_fullname = '<![CDATA['.$user_info_assured_fullname.']]>';
			}
			else
			{
				$user_info_assured_fullname =  mb_convert_encoding($value_insurant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			}

			$insurantInfo_str .= '
			<insurantInfo>
			<personnelAttribute>100</personnelAttribute>
			<virtualInsuredNum />
			<personnelName>'.$user_info_assured_fullname.'</personnelName>
			<sexCode>'.$value_insurant['gender'].'</sexCode>
			<certificateType>'.$value_insurant['certificates_type'].'</certificateType>
			<certificateNo>'.$value_insurant['certificates_code'].'</certificateNo>
			<birthday>'.$value_insurant['birthday'].'</birthday>
			<mobileTelephone>'.$value_insurant['mobiletelephone'].'</mobileTelephone>
			<email>'.$value_insurant['email'].'</email>
			</insurantInfo>';

		}//$list_subject_insurant

		//add by wangcya, 20150602

		
		$subjectInfo_str .= '
		<subjectInfo>
		<subjectid>'.$index.'</subjectid>
		<applyPersonnelNum>'.$apply_num.'</applyPersonnelNum>
		<totalModalPremium>'. $subject_premium .'</totalModalPremium>
		<productInfo>
		'.$product_str.'
		</productInfo>
		<insurantInfo>'
		.$insurantInfo_str.
		'</insurantInfo>
		</subjectInfo>';
		
		
		$totalModalPremium = $totalModalPremium + $subject_premium;
	}//$list_subject

	//added by zhangxi, 20141202, for travelling product
	//投保时长配置的设置
	
	//start modify by waqngcya, 20150506,适应那种标准产品但是保险期间不是一年的产品
	
	if($policy_arr['apply_day'])
	{
		$apply_day = $policy_arr['apply_day'];
				
		$str_time_interval = 	'
		<applyMonth></applyMonth>
		<applyDay>'.$apply_day.'</applyDay>
		';
	}
	elseif($policy_arr['apply_month'])
	{
		$apply_month = $policy_arr['apply_month'];//empty($policy_arr['apply_month'])?12:$policy_arr['apply_month'];
		
		$str_time_interval = 	'
		<applyMonth>'.$apply_month.'</applyMonth>
		<applyDay/>
		';
	}
	else
	{
		ss_log("err,出现了异常，两者都没有apply_month or apply_day!!!");
		//added by zhangxi, 20150508, 兼容原有两者为空的情况,设置默认情况
		$apply_month = 12;
		$str_time_interval = 	'
		<applyMonth>'.$apply_month.'</applyMonth>
		<applyDay/>
		';
	}
	//end modify by waqngcya, 20150506,适应那种标准产品但是保险期间不是一年的产品

	if(isset($product_attribute))
	{
		//mod by zhangxi, 20150204, 这里活动没有走，需要改
		if($product_attribute['attribute_type'] =='lvyou'
			|| $product_attribute['attribute_type'] =='lvyouhuodong')
		{
			$arr_time=array();
			$arr_time['start_date']= $policy_arr['start_date'];
			$arr_time['end_date'] = $policy_arr['end_date'];
			$interval = get_interval_by_strtime('day', $arr_time);
			$str_time_interval = 	'
			<applyMonth/>
			<applyDay>'.$interval['interval'].'</applyDay>
			';
		}
			
	}

	//mod by zhangxi, 20150608, 旅游险的产品不能够这样处理，因为旅游险的产品有天数的影响因子，
	//保费时变动的.已经引起了旅游线投保的bug
	$policy_arr['total_premium'] = sprintf("%01.2f", $policy_arr['total_premium'] );
	$totalModalPremium = sprintf("%01.2f", $totalModalPremium );

	//如果前面计算出来的总保费不等于前端页面传送过来的总保费，则做警告处理
	if($totalModalPremium != $policy_arr['total_premium'])
	{
		ss_log("error!,计算出来的总保费!=前端页面传送过来的总保费");
		return;//返回
	
	}
	else
	{
		ss_log("ok!,计算出来的总保费=前端页面传送过来的总保费");
	}

	
	ss_log(__FUNCTION__.", 计算出的保费： ".$totalModalPremium);
	ss_log(__FUNCTION__.", 前端传入的保费： ".$policy_arr['total_premium']);
	
	//comment by zhangxi, 20141202, 组织投保报文
	$strxml = '<?xml version="1.0" encoding="GBK"?>
	<eaiAhsXml>
	<Header>
	<TRAN_CODE>100083</TRAN_CODE>
	<BANK_CODE>'.$BANK_CODE.'</BANK_CODE>
	<BRNO>'.$BRNO.'</BRNO>
	<TELLERNO/>
	<BK_ACCT_DATE>'.$DATE.'</BK_ACCT_DATE>
	<BK_ACCT_TIME>'.$TIME.'</BK_ACCT_TIME>
	<BK_SERIAL>'.$BK_SERIAL.'</BK_SERIAL>
	<BK_TRAN_CHNL>WEB</BK_TRAN_CHNL>
	<REGION_CODE>000000</REGION_CODE>
	</Header>
	<Request>
	<ahsPolicy>
	<policyBaseInfo>
	<applyPersonnelNum>1</applyPersonnelNum>
	<!--（非空）与被保人关系。1:本人 2:配偶 3 :父子 4:父女 5:受益人 6:被保人 7:投保人 A:母子 B:母女 C:兄弟 D:姐弟 G:祖孙 H:雇佣 I:子女 9:其他 8:转换不详-->
	<relationshipWithInsured>'.$policy_arr['relationship_with_insured'].'</relationshipWithInsured>
	<totalModalPremium>'.$totalModalPremium.'</totalModalPremium>
	<insuranceBeginTime>'.$policy_arr['start_date'].'</insuranceBeginTime>
	<insuranceEndTime>'.$policy_arr['end_date'].'</insuranceEndTime>' .
	$str_time_interval.
	'<businessType>'.$businessType.'</businessType>
	<currecyCode>01</currecyCode>
	<alterableSpecialPromise>'.$limit_note.'</alterableSpecialPromise>
	</policyBaseInfo>
	<policyExtendInfo>
	<partnerName>'.$partnerName.'</partnerName>
	<partnerSystemSeriesNo>'.$partnerSystemSeriesNo.'</partnerSystemSeriesNo>
	</policyExtendInfo>
	<insuranceApplicantInfo>
	'.$insuranceApplicantInfo.'
	</insuranceApplicantInfo>
	<subjectInfo>'
	.$subjectInfo_str.
	'</subjectInfo>
	</ahsPolicy>
	</Request>
	</eaiAhsXml>';

	///////////////////////zhangxi

	$strxml = trim($strxml);

	return $strxml;
}


function gen_post_policy_xml_pingan_jingwailvyou(
		$policy_arr,//保单相关信息
		$user_info_applicant,//投保人信息
		$list_subject,
		$partnerName,
		$BANK_CODE,
		$BRNO
	)
{
	global $_SGLOBAL;
	///////////////////////////////////////////////////////////////////
	ss_log("into function ".__FUNCTION__);

	$result = 1;
	$retmsg = "";
	/////////////////////////////////////////////////////////////////
	$DATE = date('Ymd',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30
	//$orderNum = $policy_arr['order_num'];//这个填写的就是订单号
	$BK_SERIAL = getRandOnly_Id(0);//add by wangcya, 20150326,平安要求每次交易流水号（也就是每次请求）需要变化
	$partnerSystemSeriesNo = $policy_arr['policy_id'];////add by wangcya, 20150326,policy_id这是唯一的，$orderNum
	
	ss_log("before gen post xml, policy_id: ".$policy_arr['policy_id']." SERIAL: ".$BK_SERIAL);
	
	///////////////////////////////////////////////////////////////////////

	if($list_subject)
	{
		ss_log("list_subject is ok");
	}
	else
	{
		$result = 1;
		$retmsg = "list_subject list is null";
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		return $result_attr;
	}

	$attribute_id = $policy_arr['attribute_id'];
	if($attribute_id)
	{

		$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE attribute_id='$attribute_id' LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$product_attribute = $_SGLOBAL['db']->fetch_array($query);


		//comment by zhangxi, 20141205, 特别约定信息
		if(($product_attribute['insurer_code']=="BYTPA_000011"||//丢不了
				$product_attribute['insurer_code']=="BYTPA_000012")&&//丢不了
				!empty($product_attribute['limit_note']))
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

	}//$attribute_id

	//start add by wangcya, 20150106,
	ss_log("policy_arr business_type: ".$policy_arr['business_type']);
	$businessType = $policy_arr['business_type'];
	//end add by wangcya, 20150106, 要更改为1为个人

	////////////////////////////////////////////////////////////
	if($policy_arr['business_type'] == 1)//<!-- (非空) 业务类型 1表示个人，2表示团体 -->
	{
		if(defined('IS_NO_GBK'))
		{
			$user_info_applicant_fullname =  $user_info_applicant['fullname']; //已知原编码为UTF-8, 转换为GBK
			$user_info_applicant_fullname = '<![CDATA['.$user_info_applicant_fullname.']]>';
		}
		else
		{
			$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}

		$insuranceApplicantInfo = '<individualPersonnelInfo>
		<personnelName>'.$user_info_applicant_fullname.'</personnelName>
		<sexCode>'.$user_info_applicant['gender'].'</sexCode>
		<certificateType>'.$user_info_applicant['certificates_type'].'</certificateType>
		<certificateNo>'.$user_info_applicant['certificates_code'].'</certificateNo>
		<birthday>'.$user_info_applicant['birthday'].'</birthday>
		<familyNameSpell />
		<firstNameSpell />
		<personnelAge />
		<mobileTelephone>'.$user_info_applicant['mobiletelephone'].'</mobileTelephone>
		<email>'.$user_info_applicant['email'].'</email>
		</individualPersonnelInfo>';

	}//business_type ==1
	elseif($policy_arr['business_type'] == 2 )//团体投保
	{

		/*del by wangya , 20140904 ,存的时候已经增加了
		 if($applicant_vice_name)//投保人增加一个附件说明
		{
		$user_info_applicant_fullname = $user_info_applicant_fullname."/".$applicant_vice_name;
		}
		*/

		if(defined('IS_NO_GBK'))
		{
			$user_info_applicant_fullname = $user_info_applicant['group_name']; //已知原编码为UTF-8, 转换为GBK
			$user_info_applicant_fullname = '<![CDATA['.$user_info_applicant_fullname.']]>';

			$groupAbbr =  $user_info_applicant['group_abbr']; //已知原编码为UTF-8, 转换为GBK
			$groupAbbr = '<![CDATA['.$groupAbbr.']]>';

			$address   =  $user_info_applicant['address']; //已知原编码为UTF-8, 转换为GBK
			$address = '<![CDATA['.$address.']]>';
	
		}
		else
		{
			$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['group_name'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			$groupAbbr =  mb_convert_encoding($user_info_applicant['group_abbr'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			$address =  mb_convert_encoding($user_info_applicant['address'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			
		}


		//comment by zhangxi, 20150117, 平安的投保人是机构，则需要机构属性，现在暂时
		//只能写死，方便投保人为机构的情况下的批量投保
		$insuranceApplicantInfo =
		'<groupPersonnelInfo>
		<groupName>'.$user_info_applicant_fullname.'</groupName>
		<groupCertificateNo>'.$user_info_applicant['group_certificates_code'].'</groupCertificateNo>
		<groupCertificateType>'.$user_info_applicant['group_certificates_type'].'</groupCertificateType>
		<groupAbbr>'.$groupAbbr.'</groupAbbr>
		<address>'.$address.'</address>
		<postCode/>
		<!--<companyAttribute>'.$user_info_applicant['company_attribute'].'</companyAttribute>-->
		<companyAttribute>9</companyAttribute>
		<industryCode/>
		<businessRegisterId/>
		<phoneExchangeArea/>
		<phoneExchange/>
		<bankCode/>
		<bankAccount/>
		<businessRegionType/>
		<linkManName/>
		<linkManSexCode/>
		<linkManMobileTelephone>'.$user_info_applicant['mobiletelephone'].'</linkManMobileTelephone>
		<linkManEmail>'.$user_info_applicant['email'].'</linkManEmail>
		</groupPersonnelInfo>';
	}//business_type ==2
	//zhangxi, 没有else处理， 需要加上

	global $pingan_jingwailvyou_country;
	
	//start add by wangcya, 20150108
	if(defined('IS_NO_GBK'))
	{
		
		$destinationCountry = $policy_arr['destinationCountry'];
		//mod by zhangxi, 20150206, 增加申根协议国家名字显示格式的处理
		if($pingan_jingwailvyou_country[$destinationCountry] == "申根协议国家")
		{
			$name = explode(" ", $destinationCountry);
			$destinationCountry = $name[0].", 申根协议国家/".$name[1].", SCHENGEN STATES";
		}
		$destinationCountry = '<![CDATA['.$destinationCountry.']]>';
			
		$schoolOrCompany = $policy_arr['schoolOrCompany'];
		$schoolOrCompany = '<![CDATA['.$schoolOrCompany.']]>';
			
		$outgoingPurpose = $policy_arr['outgoingPurpose'];
		$outgoingPurpose = '<![CDATA['.$outgoingPurpose.']]>';
	}
	else
	{
		$destinationCountry = $policy_arr['destinationCountry'];
		//mod by zhangxi, 20150206, 增加申根协议国家名字显示格式的处理
		if($pingan_jingwailvyou_country[$destinationCountry] == "申根协议国家")
		{
			$name = explode(" ", $destinationCountry);
			$destinationCountry = $name[0].", 申根协议国家/".$name[1].", SCHENGEN STATES";
		}
		$destinationCountry =  mb_convert_encoding($policy_arr['destinationCountry'], "GBK", "UTF-8" );
		$schoolOrCompany =  mb_convert_encoding($policy_arr['schoolOrCompany'], "GBK", "UTF-8" );
		$outgoingPurpose =  mb_convert_encoding($policy_arr['outgoingPurpose'], "GBK", "UTF-8" );
	}
	//end add by wangcya, 20150108
	
	
	//$user_info_applicant[fullname] = iconv('UTF-8', 'GKB', $user_info_applicant[fullname]);
	//$user_info_assured[fullname] = iconv('UTF-8', 'GKB', $user_info_assured[fullname]);


	//start add by wangcya, 20150108,根据期限为一年的，对产品代码进行转换。
	$product = $list_subject[0]['list_subject_product'][0];
		//start add by wangcya , 20141111, A款下的一年多次的转换

	$product_code = $policy_arr['product_code'];//add by wangcya, 20150110, 这个时候使用的是policy中的product_code,这是转换后的。
	ss_log("use policy's product_code: ".$product_code);
	
    $applyDay = $policy_arr['apply_day'];
    ss_log("applyDay: ".$applyDay);
    
    ss_log("period_code: ".$policy_arr['period_code']);
		
	//end add by wangcya, 20150108,根据期限为一年的，对产品代码进行转换。
	
	/////////////////////////////////////////////////////////////
	
	$subjectInfo_str = '';
	foreach($list_subject AS $key =>$value)
	{
		$index = $key+1;
		$list_subject_product  = $value['list_subject_product'] ;
		$list_subject_insurant = $value['list_subject_insurant'] ;

		$product_info = $list_subject_product[0];//add by wangcya, 20141204

		$product_str = '';
		$total_product_premium = 0;
		foreach($list_subject_product AS $key_product =>$value_product )
		{
			
			$policy_arr['total_premium'] = sprintf("%01.2f", $policy_arr['total_premium'] );
			//<productCode>'.$value_product[product_code].'</productCode>
			
			$product_str .=   '<productInfo>
			<productCode>'.$product_code.'</productCode>
			<applyNum>'.$policy_arr['apply_num'].'</applyNum>
			<totalModalPremium>'.$policy_arr['total_premium'].'</totalModalPremium>
			</productInfo>';
			$total_product_premium = $total_product_premium + $policy_arr['total_premium'];
		}//$list_subject_product

		//得到多个被保险人。
		$insurantInfo_str = '';
		foreach($list_subject_insurant AS $key_insurant =>$value_insurant )
		{
			if(defined('IS_NO_GBK'))
			{
				$user_info_assured_fullname =  $value_insurant['fullname']; //已知原编码为UTF-8, 转换为GBK
				$user_info_assured_fullname = '<![CDATA['.$user_info_assured_fullname.']]>';
			}
			else
			{
				$user_info_assured_fullname =  mb_convert_encoding($value_insurant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			}

			$insurantInfo_str .= '
			<insurantInfo>
			<personnelAttribute>100</personnelAttribute>
			<virtualInsuredNum />
			<personnelName>'.$user_info_assured_fullname.'</personnelName>
			<!--（可空）姓拼音-->
			<familyNameSpell></familyNameSpell>
			<!--（可空）名拼音-->
			<firstNameSpell>'.$value_insurant['fullname_english'].'</firstNameSpell>
			<sexCode>'.$value_insurant['gender'].'</sexCode>
			<certificateType>'.$value_insurant['certificates_type'].'</certificateType>
			<certificateNo>'.$value_insurant['certificates_code'].'</certificateNo>
			<birthday>'.$value_insurant['birthday'].'</birthday>
			<mobileTelephone>'.$value_insurant['mobiletelephone'].'</mobileTelephone>
			<email>'.$value_insurant['email'].'</email>
			<!--境外产品信息 begin，如果不出境外产品产品，将该部分删除 -->
			<!--(可空)目的地国家或地区-->
			<destinationCountry>'.$destinationCountry.'</destinationCountry>
			<!--(可空)境外工作职业-->
			<overseasOccupation></overseasOccupation>
			<!--(可空)境外留学学校或境外工作公司-->
			<schoolOrCompany>'.$schoolOrCompany.'</schoolOrCompany>
			<!--(可空)出行目的-->
			<outgoingPurpose>'.$outgoingPurpose.'</outgoingPurpose>
			</insurantInfo>
			';

		}//$list_subject_insurant


		$subjectInfo_str .= '
		<subjectInfo>
		<subjectid>'.$index.'</subjectid>
		<applyPersonnelNum>1</applyPersonnelNum>
		<totalModalPremium>'. $total_product_premium .'</totalModalPremium>
		<productInfo>
		'.$product_str.'
		</productInfo>
		<insurantInfo>'
		.$insurantInfo_str.
		'</insurantInfo>
		</subjectInfo>
		';
	}//$list_subject

	//added by zhangxi, 20141202, for travelling product
	//投保时长配置的设置
	$str_time_interval = 	'
	<applyMonth></applyMonth>
	<applyDay>'.$applyDay.'</applyDay>
	';
	
	if(isset($product_attribute))
	{
		//comment by zhangxi, 20150204, 境外旅游的处理，不会有旅游的相关处理，
		//一下代码是垃圾代码
		if($product_attribute['attribute_type'] =='lvyou')
		{
			$arr_time=array();
			$arr_time['start_date']= $policy_arr['start_date'];
			$arr_time['end_date'] = $policy_arr['end_date'];
			$interval = get_interval_by_strtime('day', $arr_time);
			$str_time_interval = 	'
			<applyMonth/>
			<applyDay>'.$interval['interval'].'</applyDay>
			';
		}
			
	}



	//comment by zhangxi, 20141202, 组织投保报文
	$strxml = '<?xml version="1.0" encoding="GBK"?>'.
	'<eaiAhsXml>
	<Header>
	<TRAN_CODE>100083</TRAN_CODE>
	<BANK_CODE>'.$BANK_CODE.'</BANK_CODE>
	<BRNO>'.$BRNO.'</BRNO>
	<TELLERNO/>
	<BK_ACCT_DATE>'.$DATE.'</BK_ACCT_DATE>
	<BK_ACCT_TIME>'.$TIME.'</BK_ACCT_TIME>
	<BK_SERIAL>'.$BK_SERIAL.'</BK_SERIAL>
	<BK_TRAN_CHNL>WEB</BK_TRAN_CHNL>
	<REGION_CODE>000000</REGION_CODE>
	</Header>
	<Request>
	<ahsPolicy>
	<policyBaseInfo>
	<applyPersonnelNum>1</applyPersonnelNum>
	<!--（非空）与被保人关系。1:本人 2:配偶 3 :父子 4:父女 5:受益人 6:被保人 7:投保人 A:母子 B:母女 C:兄弟 D:姐弟 G:祖孙 H:雇佣 I:子女 9:其他 8:转换不详-->
	<relationshipWithInsured>'.$policy_arr['relationship_with_insured'].'</relationshipWithInsured>
	<totalModalPremium>'.$policy_arr['total_premium'].'</totalModalPremium>
	<insuranceBeginTime>'.$policy_arr['start_date'].'</insuranceBeginTime>
	<insuranceEndTime>'.$policy_arr['end_date'].'</insuranceEndTime>' .
	$str_time_interval.
	'<businessType>'.$businessType.'</businessType>
	<currecyCode>01</currecyCode>
	<alterableSpecialPromise>'.$limit_note.'</alterableSpecialPromise>
	</policyBaseInfo>
	<policyExtendInfo>
	<partnerName>'.$partnerName.'</partnerName>
	<partnerSystemSeriesNo>'.$partnerSystemSeriesNo.'</partnerSystemSeriesNo>
	</policyExtendInfo>
	<insuranceApplicantInfo>
	'.$insuranceApplicantInfo.'
	</insuranceApplicantInfo>
	<subjectInfo>'
	.$subjectInfo_str.
	'</subjectInfo>
	</ahsPolicy>
	</Request>
	</eaiAhsXml>';

	///////////////////////zhangxi

	$strxml = trim($strxml);

	return $strxml;
}

//生成下载电子保单的投保报文
function gen_pingan_property_getpolicyfile_json($policy_no)
{
	$BK_SERIAL = getRandOnly_Id(0);
	$arr_getpolicy_file=array("partnerName"=>PINGAN_PROPERTY_PREFIX.PINGAN_PROPERTY_USERNAME,
								"applicationId"=>$BK_SERIAL,
								"requestId"=>$BK_SERIAL,
								"policyNo"=>$policy_no
								);
	return $arr_getpolicy_file;
}

//处理承保返回报文，同步和异步的承保，都调用本函数？
function process_return_json_data_pingan_property(
													$policy_id,
						                            $return_data,
													$order_sn,
													$orderNum,
													$use_asyn=true
												         )
{
	global $_SGLOBAL;
	
	ss_log("into function: ".__FUNCTION__);
	////////////////////////////////////////////////////////////////////////////
	/////////先解析出来返回的json//////////////////////////////////
	ss_log(__FUNCTION__.", policy_id=".$policy_id." return org data: ".$return_data);
	$return_data = json_decode((string)$return_data, true);
	$error = json_last_error();
	ss_log(__FUNCTION__.", json errorno: ".$error);
	ss_log(__FUNCTION__.", return tranfered data: ".var_export($return_data,true));
    //BE:待审批，B1：待审核，BH：待验车，B2：待缴费，B3：待承保，B5：已承保
	if(isset($return_data['data']['resultCode'])
	&& $return_data['data']['resultCode'] == '200')
	{
		
		ss_log(__FUNCTION__.", pingan property, 承保成功");
		ss_log(__FUNCTION__.", return code: ".$return_data['data']['resultCode']." order_sn: ".$order_sn);
		ss_log(__FUNCTION__.", order_sn: ".$order_sn);
		if(empty($policy_id))
		{
			ss_log("policy_id is empty, so search by order_num");
			$wheresql = "order_num='$orderNum'";//del by wangcya, 20141120,根据这个找到其对应的保单存放的数据
			$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
			ss_log($sql);
			$query = $_SGLOBAL['db']->query($sql);
			$value = $_SGLOBAL['db']->fetch_array($query);
			$policy_id = $value['policy_id'];
		}
		
		ss_log("order_sn: ".$order_sn." policy_id: ".$policy_id);
			
		if($policy_id)//根据返回的找到了对应的保单，old
		{
			ss_log("will update policy, policyNo: ".$return_data['data']['policyNo']);
	
			//$policy_id = $value['policy_id'];
			//更改该保单状态
			//$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
			updatetable(	'insurance_policy',
							array('policy_status'=>'insured','policy_no'=>$return_data['data']['policyNo']),
							array('policy_id'=>$policy_id)
							);
					
			//////////////////////////////////////////////////////////////////////
			$policy_attr['policy_no'] = $return_data['data']['policyNo'];//add by wangcya, 20141011,这里出现了BUG,导致第一次投保成功后下载电子保单失败
			$policy_attr['policy_status'] = 'insured';
			///////////////////////////////////////////////////////////////

			//start add by wangcya, 20150325,投保成功，增加对应的订单上的投保成功的个数///////////////////
			
			$sql = "UPDATE bx_order_info SET insured_policy_num=insured_policy_num+1 WHERE order_sn=".$order_sn;
			ss_log($sql);
			$_SGLOBAL['db']->query($sql);
			//end add by wangcya, 20150325,投保成功，增加对应的订单上的投保成功的个数///////////////////
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			/////////////下面进行获取电子保单或者发送到救援公司///////////////////////////////////////////////////////////////////
			ss_log(__FUNCTION__.",ready to get in get_policy_info");
			$ret = get_policy_info($policy_id);//得到保单信息
				
			ss_log("after function get_policy_info!");
				
			$policy_attr = $ret['policy_arr'];
			$user_info_applicant = $ret['user_info_applicant'];
			$list_subject = $ret['list_subject'];//多层级一个链表
			///////////////////////////////////////////////////////////////////////////////

			///////////获取电子保单,能否得到无所谓了//////////////////////////////////////
			ss_log("投保成功，将要获取电子保单");
			ss_log("policy_no: ".$policy_attr['policy_no']);
			//end start add by wangcya , 20150107
			
			$pdf_filename = S_ROOT."xml/message/".$return_data['data']['policyNo']."_pingan_policy.pdf";
			ss_log("pdf policy filename: ".$pdf_filename);
			
			$policy_attr['readfile'] = false;//
			//进行电子保单的下载
			return java_get_policy_file_pingan_property($use_asyn,
														$policy_attr,
														$pdf_filename);

		}
		else
		{
			///////////////////////////////////////////////////////////////
			$retcode  = 120;
			$retmsg = "post policy success! but not find policy by order_sn: ".$order_sn;
	
			ss_log($retmsg);
	
		}



		
			
	}//
	elseif(isset($return_data['data']['resultCode']))
	{
		$result = $return_data['data']['resultCode'];
		$retmsg = $return_data['data']['resultMessage'];
		ss_log(__FUNCTION__."，post gen policy fail! rspCode=".$result.", return message: ".$retmsg);
		ss_log(__FUNCTION__."，before tranfer,将要保存保单的返回信息！".$retmsg );
	}
	else
	{
		//投保返回异常
		$result = $return_data['data']['resultCode'];
		$retmsg = $return_data['data']['resultMessage'];
		ss_log(__FUNCTION__."，post gen policy fail! rspCode=".$result.", return message: ".$retmsg);
		ss_log(__FUNCTION__."，before tranfer,将要保存保单的返回信息！".$retmsg );
	}

	$result_attr = array('retcode'=>$result,
			'retmsg'=> $retmsg,
			'policy_no'=>$return_data['policyNo']//add by wangcya , 20150107, 一定要把这个电子保单号返回
	);
	
	return $result_attr;
	
}



function process_return_xml_data_pingan(
									$policy_id,
		                            $respond,
									$order_sn,
									$orderNum
								         )
{
	global $_SGLOBAL;
	///////////////////////////////////////////////////////////////////
	ss_log("into ".__FUNCTION__);

//	preg_match_all('/<BK_SERIAL>(.*)<\/BK_SERIAL>/isU',$return_data,$arr);
//	if($arr[1])
//		$BK_SERIAL = trim($arr[1][0]);
//	
//	preg_match_all('/<PA_RSLT_CODE>(.*)<\/PA_RSLT_CODE>/isU',$return_data,$arr);
//	if($arr[1])
//		$PA_RSLT_CODE = trim($arr[1][0]);
//	
//	preg_match_all('/<PA_RSLT_MESG>(.*)<\/PA_RSLT_MESG>/isU',$return_data,$arr);
//	if($arr[1])
//		$PA_RSLT_MESG = trim($arr[1][0]);
//	
//	preg_match_all('/<policyNo>(.*)<\/policyNo>/isU',$return_data,$arr);
//	if($arr[1])
//		$policy_no = $POLICYNO = trim($arr[1][0]);//modify by wangcya, 20141119
//	
//	preg_match_all('/<validateCode>(.*)<\/validateCode>/isU',$return_data,$arr);
//	if($arr[1])
//		$VALIDATECODE = trim($arr[1][0]);
//	
	
	//$PA_RSLT_MESG = iconv('GB2312', 'UTF-8', $PA_RSLT_MESG); //将字符串的编码从GB2312转到UTF-8
	
	//ss_log("PA_RSLT_CODE: ".$PA_RSLT_CODE);
	//ss_log("PA_RSLT_MESG: ".$PA_RSLT_MESG);
	/////////下载电子保单/////////////////////////////
	
	if($respond['rspCode'] =="000000"////生成保单成功
	)
	{//投保成功
		ss_log(__FUNCTION__.", pingan retunrn date success");
		ss_log(__FUNCTION__.", order_sn: ".$order_sn);
		$result = 0;
			
		//status,保单的状态：0 保存但是未提交；1 投保成功；2 注销了，注销后不能再次提交。
		if($policy_id)
		{
			$wheresql = "policy_id='$policy_id'";//根据这个找到其对应的保单存放的数据
		}
		else
		{
			$wheresql = "order_num='$orderNum'";//根据这个找到其对应的保单存放的数据
	
		}
		
		$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
		ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
		$policy_attr = $_SGLOBAL['db']->fetch_array($query);
	
		if($policy_attr['policy_id'])//old
		{
			$policy_id = $policy_attr['policy_id'];
			//$business_type = $policy_attr['business_type'];//del by wangcya, 20150202
			//$electronic_policy 	= $policy_attr['electronic_policy'];//add by wangcya, 20141124,是否共保。
	
			$wheresql = "policy_id='$policy_id'";
			$sql = "SELECT * FROM ".tname('insurance_policy_additional')." WHERE $wheresql LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$insurance_policy_additional = $_SGLOBAL['db']->fetch_array($query);
	
			///////////保存到数据库中////////////////////////////
			ss_log("will update policy status");
			
			$setsqlarr = array( 'PA_RSLT_CODE'=>$respond['rspCode'],
								'PA_RSLT_MESG'=>$respond['rspMsg'],
								'validateCode'=>$respond['validateCode'],
								);
	
			if($insurance_policy_additional['policy_id'])
			{
				updatetable('insurance_policy_additional', $setsqlarr, array('policy_id'=>$policy_id));
	
			}
			else
			{
				$setsqlarr['policy_id'] = $policy_id;
				inserttable('insurance_policy_additional', $setsqlarr);
			}
	
			ss_log("return policyNo: ".$respond['policyNo']." order_sn: ".$order_sn);
	
			//在这里更改该保单状态
			updatetable('insurance_policy',
						array('policy_status'=>'insured','policy_no'=>$respond['policyNo']),
						array('policy_id'=>$policy_id)
						);
						
			
			//start add by wangcya, 20150325,投保成功，增加对应的订单上的投保成功的个数///////////////////
				
			$sql = "UPDATE bx_order_info SET insured_policy_num=insured_policy_num+1 WHERE order_sn=".$order_sn;
			ss_log($sql);
			$_SGLOBAL['db']->query($sql);
			//end add by wangcya, 20150325,投保成功，增加对应的订单上的投保成功的个数///////////////////
			
			
			
			///////////////////////////////////////////////////////////////
			$result = 0;
			$retmsg = "post policy success!";
	
			/////////////add by wangcya, 20150203,必须增加这些，后面要用//////////////////////////
			$policy_attr['PA_RSLT_CODE']	=	$respond['rspCode'];
			$policy_attr['PA_RSLT_MESG']	=	$respond['rspMsg'];
			$policy_attr['validateCode']	=	$respond['validateCode'];
			$policy_attr['policy_no']	=	$respond['policyNo'];
			$policy_attr['policy_status']	=	"insured";
			
		}
		else
		{
			$result = 120;
			$retmsg = "post policy success,but not find policy by order num:".$orderNum;
			ss_log("order_sn: ".$order_sn);
			ss_log($retmsg);
			
			$result_attr = array('retcode'=>$result,
					'retmsg'=> $retmsg
			);
			
			
			return $result_attr;
		}
	
	
		//////////////start add by wangcya, 20150207////////////////////////////////
		$client_id 		= $policy_attr['client_id'];
		$activity_id 	= $policy_attr['activity_id'];
		ss_log("client_id: ".$client_id);
		ss_log("activity_id: ".$activity_id);
		

		////////////////获取电子保单///////////////////////////////////
		if(1)
		{//20150203，如果定义了异步方式，则投保时候，异步方式获取电子保单。否则不在投保阶段不获取
		
			ss_log("投保时候将要获取电子保单");
			//电子保单的路径暂时不动，涉及到打包下载的问题
			$filename = S_ROOT."xml/message/".$respond['policyNo']."_pingan_policy.pdf";//add by wangcya, 20141119
			ss_log("pdf path: ".$filename);
	
			$product_code = $policy_attr['product_code'];//add by wangcya,20150109
			ss_log("product_code: ".$product_code);

			$policy_attr['readfile'] = false;//add by wangcya, 20150207
			$use_asyn = true;//异步方式
			$result_attr = java_get_policy_file_pingan($use_asyn,$policy_attr,	$product_code, $filename);
			return $result_attr;
		}	


	
	}//生成保单成功
	else//生成保单失败
	{
		if(1)
		{//平安返回为空
			
			$result = $respond['rspCode'];
			$retmsg = $respond['rspMsg'];
			
			ss_log(__FUNCTION__.", result：".$result );
			
			//start add by wangcya, 20150126，如果返回报文为空，但是有节点，就一般是order_num出现了重复了所导致，所以要重新生成。
			$order_num = getRandOnly_Id(0);
			
			ss_log("re gen order_num: ".$order_num);
			ss_log("will update policy_id: ".$policy_id." order_num: ".$order_num);
			//这个有什么用吗？
			updatetable("insurance_policy", array("order_num"=>$order_num), array("policy_id"=>$policy_id));
			//end add by wangcya, 20150126
		}
		else
		{

			ss_log("result：".$result );
			//下层的java都进行了处理，是好像都是GBK的，包括太平洋，所以要转换。
			ss_log("before tranfer,retmsg：".$retmsg );
			//mod by zhangxi, 20150730,不知道是平安改了还是jar包邮变化，返回的就是UTF-8编码了，
			//不需要转换了
			//$retmsg =  mb_convert_encoding($retmsg, "UTF-8","GBK" ); //已知原编码为GBK, 转换为UTF-8
						
			ss_log("after tranfer,retmsg：".$retmsg );
		}
	
	}
	
	
	$result_attr = array('retcode'=>$result,
			'retmsg'=> $retmsg
	);
	
	
	return $result_attr;
	
}

//对于四款少儿的卡要生成投保书pdf
function gen_toubaoshu_pdf(
		                    $strxml,
							$order_sn,
							$policy_id
		                  )
{
	
	//////////////////////////////////////////////////////////
	ss_log("into function gen_toubaoshu_pdf");
	//////////////////////////////////////////////////////////
				
	$toubaoshu_pdf_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_policy_post_toubaoshu.pdf";
		
	$java_class_name_pingan_toubaoshu = "com.demon.java2pdf.PdfPingAnChild3CardTools";
	
	$java_obj = create_java_obj($java_class_name_pingan_toubaoshu);
	if(!$java_obj)
	{
		$result = 112;
		$retmsg = "pingan toubaoshu ,create_java_obj fail!";
		ss_log($retmsg);
	
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
	
	
		//return $result_attr;
	}
	else
	{
	
	
		ss_log("pingan toubaoshu_pdf_path: ".$toubaoshu_pdf_path);
		//$toubaoshu_xml_path
		$ret = $java_obj->generatorPDF($toubaoshu_pdf_path,"" ,"GBK",$strxml);
		if(!$ret)
		{
			ss_log("after java generatorPDF, fail!");
		}
		else
		{
			ss_log("after java generatorPDF,success!");
		}
	}
	
}
//end add by wangcya , 20150106,模块化

function get_toubaoqueren_policy_file_pingan_yiwai($policy_attr)
{
	global $_SGLOBAL;
	/////////////////////////////////////////////////////
	$policy_id = $policy_attr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	
	$order_sn		= $policy_attr['order_sn'];//add by wangcya, 20141119
	$toubaoshu_pdf_path = $filename = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_policy_post_toubaoshu.pdf";
	
	ss_log("toubaoshu ,policy file: ".$filename);
	if (file_exists($filename))//如果这个文件已经存在，则返回给用户。
	{
		header('Content-type: application/pdf');
		readfile($filename);
		
		////////////////////////////////////////////////////////////////////////////
		exit(0);
	}
	else
	{
		/////////////start add by wangcya, 20141204,投保书////////////////////////////////

		$java_class_name_pingan_toubaoshu = "com.demon.java2pdf.PdfPingAnChild3CardTools";
	
		$java_obj = create_java_obj($java_class_name_pingan_toubaoshu);
		if(!$java_obj)
		{
			$result = 112;
			$retmsg = "pingan toubaoshu ,create_java_obj fail!";
			ss_log($retmsg);
				
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
				
				
			//return $result_attr;
		}
		else
		{
	
			$toubaoshu_xml_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_policy_post.xml";
			ss_log("pingan toubaoshu_pdf_path: ".$toubaoshu_pdf_path);
			//$toubaoshu_xml_path
			$ret = $java_obj->generatorPDF($toubaoshu_pdf_path, $toubaoshu_xml_path ,"GBK","");
			if(!$ret)
			{
				ss_log("after java generatorPDF, fail!");
			}
			else
			{
				ss_log("after java generatorPDF,success!");
				
				header('Content-type: application/pdf');
				readfile($filename);
			}
		}
	
		
		/////////////end add by wangcya, 20141204,投保书////////////////////////////////
	}
	
	exit(0);
}
//获取平安个财的电子保单
function get_policyfile_pingan_property($pdf_filename,
		                             $policy_attr,
									 $user_info_applicant,
									 $list_subject		                           
		                            )
{
	ss_log("into function :".__FUNCTION__);
	
	$use_asyn = false;//同步方式
	$result_attr = java_get_policy_file_pingan_property( $use_asyn, $policy_attr, $pdf_filename );
	
	return $result_attr;
}

//获取电子保单
function get_policyfile_pingan_yiwai($pdf_filename,
		                             $policy_attr,
									 $user_info_applicant,
									 $list_subject		                           
		                            )
{

	ss_log("into function :".__FUNCTION__);
	
	///////////////////////////////////////////////////////////////////////////////////
	$product_code = $list_subject[0]['list_subject_product'][0]['product_code'];//add by wangcya, 20150109
	
	$use_asyn = false;
	$result_attr = java_get_policy_file_pingan( $use_asyn, $policy_attr,$product_code, $pdf_filename );
	
	////////////////////////////////////////////////////////////////////////////////////
	
	return $result_attr;
}


//查询电子保单
function query_policyfile_pingan_yiwai($policy_id)
{
	global $_SGLOBAL;
	/////////////////////////////////////////////////////
	$result = 1;
	$retmsg = "";
	/////////////////////////////////////////////////////////

	if($policy_id)
	{
		$wheresql = "policy_id='$policy_id'";
			
		$sql = "SELECT * FROM ".tname('insurance_policy')." p
		LEFT JOIN ".tname('insurance_policy_additional')." pa ON p.policy_id=pa.policy_id
		WHERE $wheresql LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$policy_attr = $_SGLOBAL['db']->fetch_array($query);
	}

	if(empty($policy_attr))
	{
				
		$result = 110;
		$retmsg = "query policy fail, policy_attr is null , policy_id:".$policy_id;
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		return $result_attr;
	}

	$order_sn = $policy_attr['order_sn'];

	$filename = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_searchpolicy_ret.xml";

	ss_log("query policy file: ".$filename);
	if (file_exists($filename))//如果这个文件已经存在，则返回给用户。
	{
		header('Content-type: application/xml');
		readfile($filename);
		return;
	}
	else//否则重新获取
	{
		ss_log("query policyfileurl: "." orser_sn: ".$order_sn);
			
		$result = get_policy_file_xml( $policy_attr	);
		if($result==0)
		{

			usleep(1);//wait for file cache
			//ss_log("policy file: ".$filename);
			if (file_exists($filename))//如果这个文件已经存在，则返回给用户。
			{
				header('Content-type: application/xml');
				readfile($filename);
				return;
			}
		}
		else
		{
				
			$result = 110;
			$retmsg = "query policy file fail!";
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
			return $result_attr;
		}

	}

	///////////////////////////////////////////////////////////////////

	$result_attr = array('retcode'=>$result,
			'retmsg'=> $retmsg
	);
	return $result_attr;
}



//查询电子保单，得到XML格式的
function 	get_policy_file_xml($policy_attr)
{
	global $_SGLOBAL;
	$result = 1;
	/////////////////////////////////////////////////////////////////
	$policy_id = $policy_attr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	
	$POLICYNO 		= $policy_attr['policy_no'];
	$VALIDATECODE 	= $policy_attr['validateCode'];
	$orderNum 		= $policy_attr['order_num'];
	$order_sn 		= $policy_attr['order_sn'];
	/////////////////////////////////////////////////////////////////
	$DATE = date('Ymd',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30
	$BK_SERIAL = getRandOnly_Id(0);//add by wangcya, 20150326,平安要求每次交易流水号（也就是每次请求）需要变化
	
	/////////////////////////////////////////////////////////////////////

	$strxml = '<?xml version="1.0" encoding="GBK"?>
	<eaiAhsXml>
	<Header>
	<TRAN_CODE>100084</TRAN_CODE>
	<BANK_CODE>9855</BANK_CODE>
	<BRNO>98550000</BRNO>
	<TELLERNO/>
	<BK_ACCT_DATE>'.$DATE.'</BK_ACCT_DATE>
	<BK_ACCT_TIME>'.$TIME.'</BK_ACCT_TIME>
	<BK_SERIAL>'.$BK_SERIAL.'</BK_SERIAL>
	<BK_TRAN_CHNL>WEB</BK_TRAN_CHNL>
	<REGION_CODE>000000</REGION_CODE>
	</Header>
	<Request>
	<policyInfo>
	<policyInfo>
	<policyNo>'.$POLICYNO.'</policyNo>
	<validateCode>'.$VALIDATECODE.'</validateCode>
	</policyInfo>
	</policyInfo>
	</Request>
	</eaiAhsXml>';

	if(!empty($strxml))
	{
		$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_searchpolicy_post.xml";
		file_put_contents($af_path,$strxml);

		$ch = curl_init();
		////////////////////////////////////////////////////////////////
		$flagpost= 1;
		$url= 'https://222.68.184.181:8107';
			
		$port =  8107;
		//发送请求到保险公司服务器
		$return_data = curlPost($ch,$flagpost,$url, $port, $strxml, 10, 0);

		if(!empty($return_data))
		{
			//先保存一份
			$return_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_searchpolicy_ret.xml";
			file_put_contents($return_path,$return_data);

			$result = 0;
			//////////////////////////////////////////////////////////
		}
		else
		{
			ss_log("post return is null!");
			$result = 1;
		}
	}//$strxml
	else
	{
		$result = 1;
		ss_log("gen search policy xml fail!");
	}

	return $result;
}

////////////////////////////////////////////////////////////////////////////////
/*
 *  �������Ҫ��ͻ�����֤����Ҫ��pfx֤��ת����pem��ʽ

openssl pkcs12 -clcerts -nokeys -in cert.pfx -out client.pem    #�ͻ��˸���֤��Ĺ�Կ

openssl pkcs12 -nocerts -nodes -in cert.pfx -out key.pem #�ͻ��˸���֤���˽Կ

Ҳ����ת��Ϊ��Կ��˽Կ�϶�Ϊһ���ļ�

openssl pkcs12 -in  cert.pfx -out all.pem -nodes                                   #�ͻ��˹�Կ��˽Կ��һ�����all.pem��


ִ��curl����

����ʹ��client.pem+key.pem

curl -k --cert client.pem --key key.pem https://www.xxxx.com



2��ʹ��all.pem

curl -k --cert all.pem  https://www.xxxx.com
* */

//通过流解码得到pdf电子保单
function get_policy_pdf_file_pingan_property($policy_id,
												$order_sn,
												 $pdf_filename, 
												 $pdf_buffer,
												 $readfile=false )
{
	ss_log(__FUNCTION__.", in ,pdf_buffer=".$pdf_buffer);
	if(!isset($obj_java))
	{
		$obj_java = create_java_obj("com.yanda.imsp.util.ins.cpic.traffic.CPICTrafficIns");
	}
	if(!$obj_java)
	{
		$result = 112;
		$retmsg = "create_java_obj fail!";
		ss_log($retmsg);
	
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
	
		return $result_attr;
	}
	
	////////////////////////////////////////////////////////////////////
	
	ss_log(__FUNCTION__.":pdfFile: ".$pdf_filename);
	$logFileName = S_ROOT."xml/log/pingan_property_download_policyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	
	ss_log(__FUNCTION__.",pingan property order_sn: ".$order_sn);
	//ss_log(__FUNCTION__.",pingan property pdf_buffer: ".$pdf_buffer);
	(string)$obj_java->savePDF2File( 	$pdf_buffer,
										$pdf_filename,
										$logFileName
									);

	if (file_exists($pdf_filename))//文件已经存在，说明保存电子保单成功。
	{
		$result = 0;
		$retmsg = "get policy file ok!";
	
		ss_log(__FUNCTION__.", ".$retmsg);

		//start add by wangcya, 20150205,不直接退出，先更新状态//////////////////////////////////
		$setarr = array('ret_code'=>$result,
						'ret_msg'=>$retmsg,
				         'getepolicy_status'=>1//获取电子保单成功了
				         );
		updatetable('insurance_policy',
					$setarr	,
					array('policy_id'=>$policy_id)
					);
		if($readfile)//直接从前端点击下载文件，应该就走这里
		{		
				header('Content-type: application/pdf');
				readfile($pdf_filename);
				exit(0);
		}		

		$result_attr = array(	'retcode'=>$result,
								'retmsg'=> $retmsg,
								'retFileName'=>str_replace(S_ROOT,'',$pdf_filename)
								);
		return $result_attr;
	}
	else
	{
		$result = 110;
		$retmsg = "get remote policy file ok, but not find file!";
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
								'retmsg'=> $retmsg
								);
		return $result_attr;
			
	}
}

//获取平安财险的电子保单
function java_get_policy_file_pingan_property($use_asyn,
									$policy_attr,
									$pdf_filename )
{
	global $java_class_name_pingan_property;
	if(!empty($policy_attr))
	{
		$policy_id	= $policy_attr['policy_id'];
		$VALIDATECODE 	= $policy_attr['validateCode'];
		$orderNum 		= $policy_attr['order_num'];//现在用保单号来存放电子保单
		$order_sn 		= $policy_attr['order_sn'];//现在用保单号来存放电子保单
		$policy_no		= $policy_attr['policy_no'];//add by wangcya, 20141119
		$business_type 		= $policy_attr['business_type'];
		$electronic_policy 	= $policy_attr['electronic_policy'];//add by wangcya, 20141124,是否共保。
	}
	else
	{
		$result = 110;
		$retmsg = "get policy file fail, policy_attr is null , policy_id:".$policy_attr['policy_id'];
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
	
		return $result_attr;
	}
	
	$arr_post = gen_pingan_property_getpolicyfile_json($policy_attr['policy_no']);
	$json_post = json_encode($arr_post);
	if(!$use_asyn)//!defined('USE_ASYN_JAVA'))
	{
		$java_class_name = $java_class_name_pingan_property;
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$logFileName = S_ROOT."xml/log/pingan_property_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	$access_token =get_pingan_property_accessToken($logFileName);
	$url = PINGAN_PROPERTY_URL_GET_POLICY_FILE.$access_token;
	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_property_getpolicy_post.json";
	ss_log(__FUNCTION__."：获取路径:".$af_path);
	$bytes_num = file_put_contents($af_path,$json_post);
	$obj_java = create_java_obj($java_class_name);
	if(!$obj_java)
	{
		$result = 112;
		$retmsg = "create_java_obj fail!";
		ss_log($retmsg);
			
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		return $result_attr;
	}
	$return_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_property_getpolicy_post_ret.json";
	if(!$use_asyn)//同步
	{
		ss_log(__FUNCTION__.":将使用同步方式获取电子保单");
		ss_log(__FUNCTION__.":pingan property will downLoadEPolicy");
		$ret_json_data = (string)$obj_java->downLoadEPolicy( 
													$url,
													'input',
													$json_post,
													$logFileName
													);
		
		
		
	
		//ss_log(__FUNCTION__.", downLoadEPolicy ret: ".$ret_json_data);
		file_put_contents($return_path,$ret_json_data);
		if(!empty($ret_json_data))
		{
			//在这里进行电子保单的保存处理
			ss_log(__FUNCTION__.", policy_id=".$policy_id." return org data: ".$ret_json_data);
			$return_data = json_decode((string)$ret_json_data, true);
			$error = json_last_error();
			ss_log(__FUNCTION__.", json errorno: ".$error);
			ss_log(__FUNCTION__.", return tranfered data: ".var_export($return_data,true));
			
			if (isset($return_data['data']['resultCode'])&&
			$return_data['data']['resultCode'] == 'Y')//获取电子保单返回报文成哦功
			{
				$result_attr = get_policy_pdf_file_pingan_property($policy_id,
												$order_sn,
												 $pdf_filename, 
												 $return_data['data']['result']['returnPdfValue']);
				return $result_attr ;												 
			}
			else
			{
				$result = 110;
				$retmsg = "get policy file return data error!";
				ss_log(__FUNCTION__.", ".$retmsg);
				$result_attr = array(	'retcode'=>$result,
						'retmsg'=> $retmsg
				);
				return $result_attr;
			}

		}
		else
		{
			$result = 110;
			$retmsg = "获取电子保单失败";
		}
		
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
								'retmsg'=> $retmsg
								);
		
		return $result_attr;
	}
	else
	{
		//////////////////////////////////////////
		ss_log(__FUNCTION__.", 将通过异步方式获取电子保单，will doService");
		
		$insurer_code = $policy_attr['insurer_code'];
		$callBackURL = CALLBACK_URL;
		$type = "getpolicyfile";
		$respXmlFileName = $return_path;
		
		ss_log("insurer_code: ".$insurer_code);
		ss_log("type: ".$type);
		ss_log("policy_id: ".$policy_id);
		ss_log("callBackURL: ".$callBackURL);

		$param_other = array(   "url"=>$url ,//投保保险公司服务地址
								"key"=>'input' ,//投保报文内容
								"xmlContent"=>'',//投保报文文件
								"postXmlFileName"=>$af_path ,//投保报文文件编码格式
								"postXmlFileEncoding"=>'UTF-8',//返回报文保存全路径
								"respXmlFileName"=>$respXmlFileName, //日志文件
								"respXmlFileEncoding"=>'UTF-8',
								"logFileName"=>$logFileName
							);
			
		ss_log("param_other: ".var_export($param_other,true));
		
		$jsonStr = json_encode($param_other);

		///////////////////////////////////////////////////////////////
		$successAccept = (string)$obj_java->doService(
											$insurer_code,
											$type,
											$policy_id,
											$callBackURL,
											$jsonStr
											);
			
		$result = 0;
		$retmsg = "after use asyn, ret: ".$successAccept;
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
			
		return $result_attr;
	}
	
}

//得到pdf格式的电子保单
function java_get_policy_file_pingan( $use_asyn,
								$policy_attr ,
		                		$product_code,
								$pdfFile
						     	)
{

	global $java_class_name_pingan;
	/////////////////////////////////////////////////////////////////
	
	ss_log("into fuction ".__FUNCTION__);
	
	$url = URL_PINGAN_GET_POLICY_FILE;
	ss_log("policy get url: ".$url);
	/////////////////////////////////////////////////////////////////////
	//mod by zhangxi, 20150727, 
	if($policy_attr['attribute_type'] == "Y502")
	{
		$keyFile = S_ROOT.CLIENT_KEY_PINGAN_DALIAN_Y502;
		$keypassword = 'paic1234';
	}
	else
	{
		$keyFile = S_ROOT.CLIENT_KEY_PINGAN;
		$keypassword = 'yangmingsong';
	}
	$logFile = "";
	$isDevS = '';	

	ss_log(__FUNCTION__."keyFile: ".$keyFile);
	ss_log(__FUNCTION__."pdfFile: ".$pdfFile);

	/////////////////////////////////////////////////////////////////////////////
	if(!empty($policy_attr))
	{
		$policy_id	= $policy_attr['policy_id'];
		//$POLICYNO 		= $policy_attr['policy_no'];
		$VALIDATECODE 	= $policy_attr['validateCode'];
		$orderNum 		= $policy_attr['order_num'];//现在用保单号来存放电子保单
		$order_sn 		= $policy_attr['order_sn'];//现在用保单号来存放电子保单
		$policy_no		= $policy_attr['policy_no'];//add by wangcya, 20141119
		$business_type 		= $policy_attr['business_type'];
		$electronic_policy 	= $policy_attr['electronic_policy'];//add by wangcya, 20141124,是否共保。
	}
	else
	{
		$result = 110;
		$retmsg = "get policy file fail, policy_attr is null , policy_id:".$policy_attr['policy_id'];
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
	
		return $result_attr;
	}
	
	

	ss_log(__FUNCTION__."order_sn: ".$order_sn);
	////////////////////////////////////////////////////////////////////////////////////
	//jdk1.7.0_25
	ss_log('POLICYNO: '.$policy_no.' VALIDATECODE: '.$VALIDATECODE.' orderNum: '.$orderNum);


	/*1.调整接口，新添加的isSeperated参数，isSeperated 为空时，是空白字符串，不是null
	 取值：single/group/[null]         如果是个单，填写空；如果是团单团打，填写group；如果是团单个单（是团单但是是一个被保险人），填写single
	*/
	if($business_type==1)
	{
		$isSeperated = "";//single ,20140919，十岁以下是空白，根据zhangym
	}
	elseif($business_type==2)
	{
		$isSeperated = "team";
	}
	else
	{
		$isSeperated = "team";
	}
	///////////////////////////////////////////////////////////////////
	if($electronic_policy)
	{
		$electronicPolicy = "elecpy2";
	}
	else
	{
		$electronicPolicy = "";//elecpy2 add by wangcya,20141023
	}
	
	ss_log(__FUNCTION__.":isSeperated".$isSeperated);
	ss_log(__FUNCTION__.":electronicPolicy".$electronicPolicy);
	
	//start add by wangcya, 20150109,
	ss_log(__FUNCTION__.":product_code: ".$product_code);
	
	if($product_code)
	{
		$BRNO_value = find_BRNO_by_product_code($product_code);
		
		$partnerName = $BRNO_value['partnerName'];
		$BANK_CODE = $BRNO_value['BANK_CODE'];
		$BRNO = $BRNO_value['BRNO'];
	}


	ss_log(" product_code: ".$product_code);
	ss_log(" BRNO: ".$BRNO);

	$BRNO = empty($BRNO)?"98550000":$BRNO;
	
	//mod by zhangxi, 20150727, 临时修改
	if($policy_attr['attribute_type'] == 'Y502')
	{
		$BRNO = '79610000';
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////

	//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	if(!$use_asyn)//!defined('USE_ASYN_JAVA'))
	{
		$java_class_name = $java_class_name_pingan;
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_pingan:$java_class_name;
	//end add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	
	ss_log("java_class_name: ".$java_class_name);

	$insurer_code = $policy_attr['insurer_code'];
	$attribute_type = $policy_attr['attribute_type'];
	//////////////////////////////////////////////////////////////////////////////////////
	$logFileName = S_ROOT."xml/log/pingan_getpolicyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	ss_log(__FUNCTION__.":logFileName: ".$logFileName);
	$type = 'getpolicyfile';
	$pdf_filename = $pdfFile;
	$channelcode = CHANNEL_CODE;
	if(!$use_asyn)//同步
	{
		ss_log(__FUNCTION__.":将使用同步方式获取电子保单");
		ss_log(__FUNCTION__.":pingan will applyElectricPolicyBill");
		
	
		//ss_log("applyElectricPolicyBill ret: ".$ret);
		$UUID = getRandOnlyId();
		
		$post_url = EBAO_JAVA_SERVICE_URL_SYNC."?channelCode=$channelcode&signType=MD5&type=$type&keyid=$policy_id&insurer_code=$insurer_code&attribute_type=$attribute_type";
		global $_SGLOBAL;
		//同步获取电子保单,获取电子保单的报文形成
		$data_interface = array("channelCode"=>$channelcode,
							"type"=>$type,
							"keyid"=>$policy_id,
							"transSn"=>$UUID,
							"commModel"=>"S",
							"transTime"=>date('Y-m-d H:i:s',$_SGLOBAL['timestamp']),
							//"callBackURL"=>CALLBACK_URL,
							"orderNum"=>$orderNum,
							"policyNo"=>$policy_no,
							"validateCode"=>$VALIDATECODE,
							"BRNO"=>$BRNO
							);
	$jsonStr = TO_JSON($data_interface);
	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_policy_get_policyfile.json";
	file_put_contents($af_path,$jsonStr);
	$post_str =$jsonStr;
	$ret = post_request_to_service('http',EBAO_JAVA_SERVICE_IP,EBAO_JAVA_SERVICE_PORT,
							$post_str,$post_url,$type,"S");
							
		if($ret['rspCode'] == '000000')
		{
			//进行电子保单文件的保存
			
			if($ret['PolicyStatus'] == 0)
			{
				$logFileName = S_ROOT."xml/log/pingan_download_policyfile_java_sync_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
				$result = save_policy_file($ret['policyStream'],$pdf_filename,$logFileName);
				$retmsg = "获取电子保单成功";
			}
			else
			{
				$result = 110;
				$retmsg = "获取电子保单失败";
			}

			if (file_exists($pdf_filename))//如果这个文件已经存在，则返回给用户。
			{
				/////////////////////////////////////////////////////////
				//$policy_id = $policy_attr['policy_id'];
				$readfile = $policy_attr['readfile'];
				
				$result = 0;
				$retmsg = " 获取电子保单成功";
			
				ss_log(__FUNCTION__.$retmsg);

				//start add by wangcya, 20150205,不直接退出，先更新状态//////////////////////////////////
				$setarr = array('ret_code'=>$result,'ret_msg'=>$retmsg,
						         'getepolicy_status'=>1//获取电子保单成功了
						         );
				updatetable('insurance_policy',
							$setarr	,
							array('policy_id'=>$policy_id)
							);
				if($readfile)//这个是干什么用的？
				{		
						header('Content-type: application/pdf');
						readfile($pdf_filename);
						exit(0);
				}		

				$result_attr = array(	'retcode'=>$result,
										'retmsg'=> $retmsg
										);
				return $result_attr;
			}
			else
			{
				$result = 110;
				$retmsg = "get remote policy file ok, but not find file!";
				ss_log($retmsg);
				$result_attr = array(	'retcode'=>$result,
						'retmsg'=> $retmsg
				);
				return $result_attr;
					
			}

		}
		else
		{
			$result = 110;
			$retmsg = "获取电子保单失败";
		}
		
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
								'retmsg'=> $retmsg
								);
		
		return $result_attr;
	}
	else
	{
		//////////////////////////////////////////
		ss_log("将通过异步方式获取电子保单，will doService");
		
		$insurer_code = $policy_attr['insurer_code'];
		$callBackURL = CALLBACK_URL;
//		$type = "getpolicyfile";
		
		ss_log("insurer_code: ".$insurer_code);
//		ss_log("type: ".$type);
		ss_log("policy_id: ".$policy_id);
		ss_log("callBackURL: ".$callBackURL);
	
		
		$UUID = getRandOnlyId();
		$post_url = EBAO_JAVA_SERVICE_URL_ASYN."?channelCode=$channelcode&signType=MD5&type=$type&keyid=$policy_id&insurer_code=$insurer_code&attribute_type=$attribute_type";
		global $_SGLOBAL;
		//异步获取电子保单,获取电子保单的报文形成
		$data_interface = array("channelCode"=>$channelcode,
							"type"=>$type,
							"keyid"=>$policy_id,
							"transSn"=>$UUID,
							"commModel"=>"A",
							"transTime"=>date('Y-m-d H:i:s',$_SGLOBAL['timestamp']),
							"callBackURL"=>CALLBACK_URL,
							"orderNum"=>$orderNum,
							"policyNo"=>$policy_no,
							"validateCode"=>$VALIDATECODE,
							"BRNO"=>$BRNO
							);
	$jsonStr = TO_JSON($data_interface);
	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_policy_get_policyfile.json";
	file_put_contents($af_path,$jsonStr);
	$post_str = $jsonStr;
	$ret = post_request_to_service('http',EBAO_JAVA_SERVICE_IP,EBAO_JAVA_SERVICE_PORT,
							$post_str,$post_url,$type,"A");
							
								
		$result = 0;
		$retmsg = "after use asyn, ret: ".$ret['rspCode'];
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
			
		return $result_attr;
	}

}

function curlPost_insurance_policy($ch,
		$flagpost,
		$url,
		$port,
		$data,// = array(),
		$timeout = 30,
		$CA
)
{
	$SSL = substr($url, 0, 8) == "https://" ? true : false;

	//$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout-2);
	//curl_setopt($ch, CURLOPT_COOKIEFILE, "/Library/WebServer/Documents/tmp/cookieFileName");
	//curl_setopt($ch, CURLOPT_HEADER, true);

	curl_setopt($ch, CURLOPT_HEADER, 0); // ��ʾ���ص�Header��������
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // ��ȡ����Ϣ���ļ�������ʽ����

	//curl_setopt($ch, CURLOPT_USERPWD, "admin:12345678");
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/xml', 'Content-length: '. strlen($data)) );

	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); //����data��ݹ�����
	//curl_setopt($ch, CURLOPT_VERBOSE, 1); //debugģʽ

	curl_setopt($ch, CURLOPT_PORT, $port);
	//curl_setopt($ch, CURLOPT_VERBOSE, 1); //debugģʽ

	/*
	 if($SSL)
	 {


	//echo "will ssl<br />";
	//curl_setopt($ch2, CURLOPT_SSLVERSION, 3);
	//curl_setopt($ch, CURLOPT_SSLCERT, "./keys/client.crt"); //client.crt�ļ�·��
		
	//$clientkey = S_ROOT."keys/EXV_BIS_IFRONT_PCIS_ZTXH_001_PRD.pfx";
	$clientkey = S_ROOT."keys/all.pem";

	//echo $clientkey."<br />";

	curl_setopt($ch,CURLOPT_SSLCERTTYPE,'pem');
	curl_setopt($ch,CURLOPT_SSLCERT,$clientkey);
	//curl_setopt($ch,CURLOPT_SSLCERTPASSWD,'yangmingsong'); //client֤������
	//curl_setopt($ch,CURLOPT_SSLKEYTYPE,'pem');
	//curl_setopt($ch,CURLOPT_SSLKEY,$clientkey);

	}


	if ($SSL && $CA)
	{
	//echo "ssl and ca<br />";
	$cacert = getcwd() . '/cacert.pem'; //CA��֤��
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);   // ֻ����CA�䲼��֤��
	curl_setopt($ch, CURLOPT_CAINFO, $cacert); // CA��֤�飨������֤����վ֤���Ƿ���CA�䲼��
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // ���֤�����Ƿ������������Ƿ����ṩ��������ƥ��
	}
	else if ($SSL && !$CA)
	{

	//echo "ssl no ca<br />";


	//curl_setopt($ch, CURLOPT_RESOLVE, $host);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // ���ζԷ���������֤�飬������֤��


	//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // ��֤���м��SSL�����㷨�Ƿ����
	//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // // ���֤�����Ƿ���������,0����֤

	}


	$fields_post = array(
	'postcode' => $zip,
	'queryKind' => 2,
	'reqCode' => 'gotoSearch',
	'search_button.x'=>37,
	'search_button.y'=>12
	);


	$fields_string = http_build_query ( $fields_post, '&' );


	*/

	if($flagpost)
	{
		//echo "will post:<br />";//.$data;
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	}
	else
	{
		curl_setopt($ch, CURLOPT_POST, false);
	}
	//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); //data with URLEncode

	//curl_setopt_array($ch, $opt);

	/*
	 curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($ch, $str) {
	 		// 第一个参数是curl资源，第二个参数是每一行独立的header!
	 		list ($name, $value) = array_map('trim', explode(':', $str, 2));
	 		$name = strtolower($name);

	 		ss_log($str);

	 		// 判断大小啦
	 		if ( strstr($str,'pdf') )
	 		{
	 			
	 		$b_pdf =  1;
	 			
	 		}
	 		elseif ('content-length' == $name)
	 		{
	 			
	 		}

	 		});
	*/
	/////////////////////////////////////////////////////////////////
	$ret = curl_exec($ch);


	if (curl_errno($ch))
	{
		ss_log("err: ".curl_error($ch));
		var_dump(curl_error($ch));  //�鿴������Ϣ
		$flag = 1;
		$ret= "";
	}
	else
	{
		ss_log("ret: ".$ret);
		$flag = 0;
	}

	//echo $ret;


	//echo "<br />will return!<br />";

	//curl_close($ch);
	return $ret;
}

//注销平安个财的保单
function withdraw_policyfile_pingan_property($policy_id)
{
	
	global $_SGLOBAL;
	/////////////////////////////////////////////////////
	if($policy_id)
	{
		$wheresql = "p.policy_id='$policy_id'";
			
		$sql = "SELECT * FROM ".tname('insurance_policy')." p
		WHERE $wheresql LIMIT 1";
		
		ss_log(__FUNCTION__.", ".$sql);
		
		$query = $_SGLOBAL['db']->query($sql);
		$policy_attr = $_SGLOBAL['db']->fetch_array($query);
	}
	/////////////////////////////////////////////////////
	if(!empty($policy_attr))
	{
		$result_attr = post_withdraw_policy_pingan_property(	$policy_attr  );
	
	}
	else
	{
		$result = 110;
		$retmsg = "withdraw policy fail, policy_attr is null , policy_id:".$policy_id;
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
								'retmsg'=> $retmsg
							);
		
		
	}

	return $result_attr;
	
}
////注销保单
function withdraw_policyfile_pingan_yiwai(	$policy_id,
											$product_code
											)
{
	global $_SGLOBAL;
	/////////////////////////////////////////////////////
	if($policy_id)
	{
		$wheresql = "p.policy_id='$policy_id'";
			
		$sql = "SELECT p.*,pa.* FROM ".tname('insurance_policy')." p
		INNER JOIN ".tname('insurance_policy_additional')." pa ON p.policy_id=pa.policy_id
		WHERE $wheresql LIMIT 1";
		
		ss_log($sql);
		
		$query = $_SGLOBAL['db']->query($sql);
		$policy_attr = $_SGLOBAL['db']->fetch_array($query);
	}
	/////////////////////////////////////////////////////
	if(!empty($policy_attr))
	{
		$result_attr = post_withdraw_policy_pingan(	$policy_attr , $product_code );
	
	}
	else
	{
		$result = 110;
		$retmsg = "withdraw policy fail, policy_attr is null , policy_id:".$policy_id;
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
								'retmsg'=> $retmsg
							);
		
		
	}

	/////////////////////////////////////////////////////////////
	
	return $result_attr;
}
function post_withdraw_policy_pingan_property($policy_attr)
{
	
	global $_SGLOBAL, $java_class_name_pingan_property;
	//////////////////////////////////////////////////////
	$policy_id = $policy_attr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	
	$POLICYNO 		= $policy_attr['policy_no'];
	$VALIDATECODE 	= $policy_attr['validateCode'];
	$orderNum 		= $policy_attr['order_num'];
	$order_sn 		= $policy_attr['order_sn'];
	
	ss_log("will withdraw policy , POLICYNO: ".$POLICYNO." VALIDATECODE: ".$VALIDATECODE." orderNum: ".$orderNum);
	ss_log("order_sn: ".$order_sn);
	
	$DATE = date('Ymd',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30

	$result = 1;

	///////////////////////////////////////////////////////
	$TELLERNO = $BK_SERIAL = getRandOnly_Id(0);
	
	$strxml = array("partnerName"=>PINGAN_PROPERTY_PREFIX.PINGAN_PROPERTY_USERNAME,
					"policyNo"=>$POLICYNO);
	$withdraw_json = json_encode($strxml);
	///////////////////////////////////////////////////////////////////
	//注销的时候，不存在异步方式，仅仅同步方式。
	if(!empty($withdraw_json))
	{
		//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_property_policy_withdraw.json";
		$af_path = gen_file_fullpath('policy','pingan',S_ROOT, $order_sn."_".$policy_id."_pingan_property_policy_withdraw.json");
		file_put_contents($af_path,$withdraw_json);

		//start add by wangcya, 20150209,把携带序列号的也保存起来
		$UUID = $BK_SERIAL;
		//$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_pingan_property_policy_withdraw.json";
		$af_path_serial = gen_file_fullpath('policy','pingan',S_ROOT, $order_sn."_".$policy_id."_".$UUID."_pingan_property_policy_withdraw.json");
		file_put_contents($af_path_serial,$withdraw_json);
	
		
		$p = create_java_obj($java_class_name_pingan_property);
		if(!$p)
		{
			$result = 112;
			$retmsg = "create_java_obj fail!";
			ss_log($retmsg);
			
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
			
			
			return $result_attr;
		}
		$logFileName = S_ROOT."xml/log/pingan_property_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
		$access_token=get_pingan_property_accessToken($logFileName);
		$url = PINGAN_PROPERTY_URL_WITHDRAW.$access_token;
		$respXmlFileName = "";
		ss_log(__FUNCTION__.", url: ".$url);
		
		$return_data = (string)$p->cancel( 
												$url , 
												'input',
												$withdraw_json ,
												$logFileName
												
												);

		ss_log(__FUNCTION__.", after withdraw call function sendMessage");
		
		if(!empty($return_data))
		{
			
			//先保存一份
			//$return_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_property_policy_withdraw_ret.json";
			$return_path = gen_file_fullpath('policy','pingan',S_ROOT, $order_sn."_".$policy_id."_pingan_property_policy_withdraw_ret.json");
			file_put_contents($return_path,$return_data);

			//////////////////////////////////////////////////////////
		}
		else
		{
			$result = 110;
			$retmsg = "post withdraw policy return is null!";
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
			return $result_attr;
		}
		
		//ss_log("withdraw policy return code: ".$PA_RSLT_CODE_OUT." message: ".$PA_RSLT_MESG_OUT);
		$return_data = json_decode((string)$return_data, true);
		$error = json_last_error();
		ss_log(__FUNCTION__.", json errorno: ".$error);
		ss_log(__FUNCTION__.", return tranfered data: ".var_export($return_data,true));
		if( $return_data['data']['resultCode'] =="Y")
		{
			
			$result = 0;
			$retmsg = "各个返回项匹配，注销成功！";
			ss_log($retmsg);
			//////////////////////////////////////////////////////////////////////
			
			$result_attr = array(	'retcode'=>$result,
									'retmsg'=> $retmsg
								);
		}
		else
		{
			
			$result_attr = array(	'retcode'=>'110',//防止没有值的情况，会有问题。
									'retmsg'=> $return_data['data']['resultMessage']
								);
		}
		////////////////////////////////////////////////////////////
	}

	return $result_attr;

	
}

//注销保单
function post_withdraw_policy_pingan( $policy_attr , $product_code )
{
	global $_SGLOBAL, $java_class_name_pingan;
	//////////////////////////////////////////////////////
	$policy_id = $policy_attr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	
	$POLICYNO 		= $policy_attr['policy_no'];
	$VALIDATECODE 	= $policy_attr['validateCode'];
	$orderNum 		= $policy_attr['order_num'];
	$order_sn 		= $policy_attr['order_sn'];
	$insurer_code   = $policy_attr['insurer_code'];
	$attribute_type   = $policy_attr['attribute_type'];
	ss_log("will withdraw policy , POLICYNO: ".$POLICYNO." VALIDATECODE: ".$VALIDATECODE." orderNum: ".$orderNum);
	ss_log("order_sn: ".$order_sn);
	
	$DATE = date('Ymd',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30

	$result = 1;
	
	//start add by wangcya , 20150109, 根据不同产品代码找到合作伙伴代码以及平安的分公司账号
	ss_log("product_code: ".$product_code);
	
	$BRNO_value = find_BRNO_by_product_code($product_code);
	if($BRNO_value)
	{
		$partnerName = $BRNO_value['partnerName'];
		$BANK_CODE = $BRNO_value['BANK_CODE'];
		$BRNO = $BRNO_value['BRNO'];
	}
	else
	{
		$partnerName = 'ZTXH';
		$BANK_CODE = '9855';
		$BRNO = '98550000';
	}
	//end add by wangcya , 20150109, 根据不同产品代码找到合作伙伴代码以及平安的分公司账号
	//added by zhangxi, 20150727 ,临时代码
	if($policy_attr['attribute_type'] == 'Y502')
	{
		$partnerName='ZTXHBXDL';
		$BANK_CODE='7961';
		$BRNO = '79610000';
	}
	
	///////////////////////////////////////////////////////
	$TELLERNO = $BK_SERIAL = getRandOnly_Id(0);
	
	/*TELLERNO投保时候不填写，注销时候也不填写，
	 BK_SERIAL，每次都要唯一产生，和改投保单无关系
	BK_ORGN_SRIL，是投保单上保存的那个
	*/
	
	$strxml = '<?xml version="1.0" encoding="GBK"?>
	<eaiAhsXml>
	<Header>
	<TRAN_CODE>101083</TRAN_CODE>
	<BANK_CODE>'.$BANK_CODE.'</BANK_CODE>
	<BRNO>'.$BRNO.'</BRNO>
	<TELLERNO></TELLERNO>
	<BK_ACCT_DATE>'.$DATE.'</BK_ACCT_DATE>
	<BK_ACCT_TIME>'.$TIME.'</BK_ACCT_TIME>
	<BK_SERIAL>'.$BK_SERIAL.'</BK_SERIAL>
	<BK_ORGN_SRIL>'.$orderNum.'</BK_ORGN_SRIL>
	<BK_TRAN_CHNL>WEB</BK_TRAN_CHNL>
	<REGION_CODE>000000</REGION_CODE>
	</Header>
	<Request>
	<policyInfo>
	<policyInfo>
	<policyNo>'.$POLICYNO.'</policyNo>
	<validateCode>'.$VALIDATECODE.'</validateCode>
	</policyInfo>
	</policyInfo>
	</Request>
	</eaiAhsXml>';
	///////////////////////////////////////////////////////////////////
	//$strxml =  mb_convert_encoding($strxml, "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
	//注销的时候，不存在异步方式，仅仅同步方式。

//		//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_policy_withdraw.xml";
//		$af_path = gen_file_fullpath('policy','pingan',S_ROOT, $order_sn."_".$policy_id."_pingan_policy_withdraw.xml");
//		file_put_contents($af_path,$strxml);
//
//		//start add by wangcya, 20150209,把携带序列号的也保存起来
//		$UUID = $BK_SERIAL;
//		//$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_pingan_policy_withdraw.xml";
//		$af_path_serial = gen_file_fullpath('policy','pingan',S_ROOT, $order_sn."_".$policy_id."_".$UUID."_pingan_policy_withdraw.xml");
//		file_put_contents($af_path_serial,$strxml);
//		//end add by wangcya, 20150209,把携带序列号的也保存起来
		
//		$p = create_java_obj($java_class_name_pingan);
//		if(!$p)
//		{
//			$result = 112;
//			$retmsg = "create_java_obj fail!";
//			ss_log($retmsg);
//			
//			$result_attr = array(	'retcode'=>$result,
//					'retmsg'=> $retmsg
//			);
//			
//			
//			return $result_attr;
//		}
//		
//		$url = URL_PINGAN_POST_POLICY;
//		$port =  8107;
//		if($policy_attr['attribute_type'] == "Y502")
//		{
//			$keyFile = S_ROOT.CLIENT_KEY_PINGAN_DALIAN_Y502;
//			$keypassword = 'paic1234';
//		}
//		else
//		{
//			$keyFile = S_ROOT.CLIENT_KEY_PINGAN;
//			$keypassword = 'yangmingsong';
//		}
//		//$keyFile = S_ROOT.CLIENT_KEY_PINGAN;
//		$respXmlFileName = "";
//		ss_log("url: ".URL_PINGAN_POST_POLICY);
//		ss_log("keyFile: ".$keyFile);
//		
//		$return_data = (string)$p->sendMessage( 
//												$url , 
//												$port , 
//												$strxml ,
//												$respXmlFileName,
//												$keyFile,
//												$keypassword,
//												$keypassword
//												
//												);
	$type = 'withdraw';
	$UUID = getRandOnlyId();
	$channelcode = CHANNEL_CODE;
	$post_url = EBAO_JAVA_SERVICE_URL_SYNC."?channelCode=$channelcode&signType=MD5&type=$type&keyid=$policy_id&insurer_code=$insurer_code&attribute_type=$attribute_type";
	//added by zhx, 20151009
	$data_interface = array("channelCode"=>$channelcode,
							"type"=>"withdraw",
							"keyid"=>$policy_id,
							"transSn"=>$UUID,
							"commModel"=>"S",//同步
							"transTime"=>date('Y-m-d H:i:s',$_SGLOBAL['timestamp']),
							//"callBackURL"=>CALLBACK_URL,
							"policyNo"=>$POLICYNO,
							"orderNum"=>$orderNum,
							"bank_code"=>$BANK_CODE,
							"brno"=>$BRNO,
							"bk_serial"=>$BK_SERIAL,
							"validateCode"=>$VALIDATECODE
							);
	$jsonStr = TO_JSON($data_interface);
	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_policy_withdraw.json";
	file_put_contents($af_path,$jsonStr);
	$post_str = $jsonStr;
	$ret = post_request_to_service('http',EBAO_JAVA_SERVICE_IP,EBAO_JAVA_SERVICE_PORT,
							$post_str,$post_url,$type, "S");
							
	if($ret['rspCode'] == '000000')
	{
			$result = 0;
			$retmsg = "各个返回项匹配，注销成功！";
			ss_log($retmsg);
			//////////////////////////////////////////////////////////////////////
			$result_attr = array(	'retcode'=>$result,
									'retmsg'=> $retmsg
								);
	}
	else
	{
		$result_attr = array(	'retcode'=>$ret['rspCode'],
								'retmsg'=> $ret['rspMsg']
								);
	}
		
		ss_log(__FUNCTION__.", after withdraw call function sendMessage");
		

		/////////先解析出来返回的xml//////////////////////////////////
//		
//		preg_match_all('/<BK_SERIAL>(.*)<\/BK_SERIAL>/isU',$return_data,$arr);
//		if($arr[1])
//		{
//			$BK_SERIAL_OUT = trim($arr[1][0]);
//		}
//
//		preg_match_all('/<PA_RSLT_CODE>(.*)<\/PA_RSLT_CODE>/isU',$return_data,$arr);
//		if($arr[1])
//		{
//			$PA_RSLT_CODE_OUT = trim($arr[1][0]);
//		}
//
//		preg_match_all('/<PA_RSLT_MESG>(.*)<\/PA_RSLT_MESG>/isU',$return_data,$arr);
//		if($arr[1])
//		{
//			$PA_RSLT_MESG_OUT = trim($arr[1][0]);
//		}
//
//		preg_match_all('/<policyNo>(.*)<\/policyNo>/isU',$return_data,$arr);
//		if($arr[1])
//		{
//			$POLICYNO_OUT = trim($arr[1][0]);
//		}
//
//		preg_match_all('/<validateCode>(.*)<\/validateCode>/isU',$return_data,$arr);
//		if($arr[1])
//		{
//			$VALIDATECODE_OUT = trim($arr[1][0]);
//		}
//
//		ss_log("PA_RSLT_CODE_OUT: ".$PA_RSLT_CODE_OUT);
//		ss_log("PA_RSLT_MESG_OUT: ".$PA_RSLT_MESG_OUT);
//		
//		
//		ss_log("BK_SERIAL_OUT: ".$BK_SERIAL_OUT);
//		ss_log("POLICYNO_OUT: ".$POLICYNO_OUT);
//		
//		ss_log("orderNum: ".$orderNum);
//		ss_log("POLICYNO: ".$POLICYNO);
//		ss_log("order_sn: ".$order_sn);


		//ss_log("withdraw policy return code: ".$PA_RSLT_CODE_OUT." message: ".$PA_RSLT_MESG_OUT);

//		if( $PA_RSLT_CODE_OUT =="999999"&&////注销保单成功
//				$BK_SERIAL_OUT == $BK_SERIAL&&//并且这个要和订单号相等$orderNum
//				$POLICYNO_OUT == $POLICYNO
//		)
//		{
//			
//			$result = 0;
//			$retmsg = "各个返回项匹配，注销成功！";
//			ss_log($retmsg);
//			//////////////////////////////////////////////////////////////////////
//			
//			$result_attr = array(	'retcode'=>$result,
//									'retmsg'=> $retmsg
//								);
//		}
//		else
//		{
//			//start add by wangcya,20150202,有节点，但是没内容
//			if(empty($PA_RSLT_CODE_OUT))
//			{//平安返回为空
//				
//				$result = 110;
//				$retmsg = "pingan返回有节点，但是内容为空";
//					
//				ss_log("result：".$result );
//				ss_log("解析返回报文错误！");
//				
//				$result_attr = array(	'retcode'=>$result,
//						'retmsg'=> $retmsg
//				);
//
//			}
//			//end add by wangcya,20150202,有节点，但是没内容
//			else
//			{
//
//				$retmsg = "withdraw fail!,PA_RSLT_MESG_OUT: ".$PA_RSLT_MESG_OUT;
//				ss_log($retmsg);
//	
//						
//				$result_attr = array(	'retcode'=>$PA_RSLT_CODE_OUT,
//										'retmsg'=> $PA_RSLT_MESG_OUT
//									);
//			}
//			
//		}
		////////////////////////////////////////////////////////////
	

	return $result_attr;

}


//get_Insurance_policy
function  sign_Insurance_policy(
		$POLICYNO,
		$VALIDATECODE
)
{
	global $_SGLOBAL;
	//////////////////////////////////////////////////////

	$UMCODE = '98550000';
	$curTime = 	date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);//20140515
	$ISSEPERATED ='single';//"group";

	ss_log("curTime: ".$curTime);
	$data=$UMCODE.$POLICYNO.$VALIDATECODE.$ISSEPERATED.$curTime;//待签名参数顺序不能变化
	ss_log("sig input data: ".$data);

	//$KEYSTORE_FILENAME = S_ROOT."keys/EXV_BIS_IFRONT_PCIS_ZTXH_001_PRD.pfx";
	$KEYSTORE_FILENAME = S_ROOT."keys/all.pem";
	$KEYSTORE_PASSWORD = "yangmingsong";//密钥库的密码
	$KEYSTORE_ALIAS = "1";//密钥库的别名

	$data = trim($data);
	$cipherText= signData(data,$KEYSTORE_FILENAME,$KEYSTORE_PASSWORD,$KEYSTORE_ALIAS);//签名

	ss_log("cipherText: ".$cipherText);


	$requestParam="umCode=$UMCODE&policyNo=$POLICYNO&validateCode=$VALIDATECODE&isSeperated=$ISSEPERATED&curTime=$curTime&cipherText=$cipherText";
	//发送请求到保险公司服务器

	ss_log("after sig: ".$requestParam);


	return $requestParam;
}

/**
 * 签名算法
 * @author HXS
 * @date 2012-7-2
 * @todo TODO
 * @param data  需要签名的内容
 * @param keyStoreFileName  含私钥的文件
 * @param keyStorePassword  含私钥文件的密码
 * @param keyStoreAlias  别名
 * @return
 */
function signData($data,
		$keyStoreFileName,
		$keyStorePassword,
		$keyStoreAlias
)
{

	$privatekeyFile = $keyStoreFileName;//'/path/to/private.key';
	$passphrase = $keyStorePassword;

	// 摘要及签名的算法
	$digestAlgo = 'sha512';
	$algo = OPENSSL_ALGO_SHA1;

	ss_log("privatekeyFile: ".$keyStoreFileName);
	ss_log("passphrase: ".$passphrase);
	// 加载私钥
	$privatekey = openssl_pkey_get_private(file_get_contents($privatekeyFile), $passphrase);

	ss_log("privatekey: ".$privatekey);
	// 生成摘要

	if($privatekey)
	{
		//解到原因了，如果出现ascii以外的unicode字符那么加签名 结果就不一样了，看来又是node内部编码问题了
		$digest = $data;
		//$digest = openssl_digest($data, $digestAlgo);
			
		// 签名
		$signatureout = '';
		openssl_sign($digest, $signatureout, $privatekey, $algo);//$signature 为输出

		//ss_log("signatureout: ".$signatureout);

		$signature = base64_encode($signatureout);
	}

	openssl_free_key($privatekey);

	//var_dump($signature);

	return $signature;
}

function two_subject_test_code($policy_arr)
{
	global $_SGLOBAL;
	$BK_SERIAL = getRandOnly_Id(0);//add by wangcya, 20150326,平安要求每次交易流水号（也就是每次请求）需要变化
	
	$partnerSystemSeriesNo = $policy_arr['policy_id'];////add by wangcya, 20150326,policy_id这是唯一的，$orderNum
	$date = date('Ymd',$_SGLOBAL['timestamp']);
	$act_time = date('H:i:s',$_SGLOBAL['timestamp']);
	return $strxml = '<?xml version="1.0" encoding="GB2312"?>
	<eaiAhsXml>
	<Header>
		<TRAN_CODE>100083</TRAN_CODE>
		<BANK_CODE>7961</BANK_CODE>
		<BRNO>79610000</BRNO>
		<TELLERNO/>
		<BK_ACCT_DATE>'.$date.'</BK_ACCT_DATE>
		<BK_ACCT_TIME>'.$act_time.'</BK_ACCT_TIME>
		<BK_SERIAL>'.$BK_SERIAL.'</BK_SERIAL>
		<BK_TRAN_CHNL>WEB</BK_TRAN_CHNL>
		<REGION_CODE>000000</REGION_CODE>
	</Header>
			<Request>
				<ahsPolicy>
						<policyBaseInfo>
							<applyPersonnelNum>10</applyPersonnelNum>
							<relationshipWithInsured>9</relationshipWithInsured>
							<totalModalPremium>900</totalModalPremium>
							<insuranceBeginTime>2015-08-20 00:00:00</insuranceBeginTime>
							<insuranceEndTime>2016-08-19 23:59:59</insuranceEndTime>
							<applyMonth>12</applyMonth>
							<applyDay/>
							<businessType>2</businessType>
							<currecyCode>01</currecyCode>
							<alterableSpecialPromise><![CDATA[1、保险对象：年龄在18周岁—65周岁的职员
									2、 被保险人因遭受意外伤害事故并在医院进行治疗，本公司就本次事故发生之日起一百八十天内实际支出的合理医疗费用超过人民币0元部分按100%比例给付意外伤害医疗保险金.
									3、平安住院安心团体健康保险仅承担一般住院津贴责任，且初次投保有30天住院观察期，绝对免赔天数3天。
									4、无其他特别约定。	]]></alterableSpecialPromise>
						</policyBaseInfo>
					 
					 <policyExtendInfo>
						<partnerName>ZTXHBXDL</partnerName>
						<partnerSystemSeriesNo>'.$partnerSystemSeriesNo.'</partnerSystemSeriesNo>
					  </policyExtendInfo>
					 
					<insuranceApplicantInfo>
						<groupPersonnelInfo>
							<groupName><![CDATA[张-测试单]]></groupName>
							<groupCertificateNo>432503198109152775</groupCertificateNo>
							<groupCertificateType>01</groupCertificateType>
							<groupAbbr><![CDATA[]]></groupAbbr>
							<address><![CDATA[]]></address>
							<postCode/>
							<companyAttribute>01</companyAttribute>
							<industryCode/>
							<businessRegisterId/>
							<phoneExchangeArea/>
							<phoneExchange/>
							<bankCode/>
							<bankAccount/>
							<businessRegionType/>
							<linkManName><![CDATA[张-测试单]]></linkManName>
							<linkManSexCode/>
							<linkManMobileTelephone>15810239380</linkManMobileTelephone>
							<linkManEmail>2@126.com</linkManEmail>
						</groupPersonnelInfo>
				    </insuranceApplicantInfo>
								
								<subjectInfo>
												<subjectInfo>
									<subjectName>1</subjectName>
									<applyPersonnelNum>5</applyPersonnelNum>
									<totalModalPremium>400</totalModalPremium>
									<planInfo>
									<planInfo>
										<planCode>Y502</planCode>	
										<applyNum>5</applyNum>
										<totalModalPremium>400</totalModalPremium>
										<applyMonth>12</applyMonth>
										<applyDay/>
									<dutyInfo>
									<dutyInfo>
										<dutyCode>YA01</dutyCode>
										<totalModalPremium>80.00</totalModalPremium>
										<dutyAount>100000</dutyAount>
										
							<dutyFactorInfo>
								<dutyFactorInfo>
								<factorId>F05</factorId>
								<factorValue>12</factorValue>
								</dutyFactorInfo>
							</dutyFactorInfo>
						</dutyInfo></dutyInfo></planInfo>
					                </planInfo>
		                        
									<insurantInfo>
											<insurantInfo>
												<professionCode>0001001</professionCode>
												<personnelAttribute>100</personnelAttribute>
												<personnelName><![CDATA[王健]]></personnelName>
												<sexCode>M</sexCode>
												<certificateType>01</certificateType>
												<certificateNo>211103197703030618</certificateNo>
												<birthday>1977-03-03</birthday>
												<mobileTelephone>13011234568</mobileTelephone>
												<email>2@12.com</email>
												<familyNameSpell/>
												<firstNameSpell/>
												<personnelAge/>
											</insurantInfo>
			                               
											<insurantInfo>
												<professionCode>0001001</professionCode>
												<personnelAttribute>100</personnelAttribute>
												<personnelName><![CDATA[方  薇]]></personnelName>
												<sexCode>F</sexCode>
												<certificateType>01</certificateType>
												<certificateNo>110108196805184825</certificateNo>
												<birthday>1968-05-18</birthday>
												<mobileTelephone>13004234565</mobileTelephone>
												<email>3@12.com</email>
												<familyNameSpell/>
												<firstNameSpell/>
												<personnelAge/>
											</insurantInfo>
			                               
											<insurantInfo>
												<professionCode>0001001</professionCode>
												<personnelAttribute>100</personnelAttribute>
												<personnelName><![CDATA[范本勇]]></personnelName>
												<sexCode>M</sexCode>
												<certificateType>01</certificateType>
												<certificateNo>320682198101040475</certificateNo>
												<birthday>1981-01-04</birthday>
												<mobileTelephone>13001634566</mobileTelephone>
												<email>4@15.com</email>
												<familyNameSpell/>
												<firstNameSpell/>
												<personnelAge/>
											</insurantInfo>
			                               
											<insurantInfo>
												<professionCode>2001003</professionCode>
												<personnelAttribute>100</personnelAttribute>
												<personnelName><![CDATA[郭慧芬]]></personnelName>
												<sexCode>F</sexCode>
												<certificateType>01</certificateType>
												<certificateNo>230106196411062068</certificateNo>
												<birthday>1964-11-06</birthday>
												<mobileTelephone>13001235569</mobileTelephone>
												<email>1@12.com</email>
												<familyNameSpell/>
												<firstNameSpell/>
												<personnelAge/>
											</insurantInfo>
			                               
											<insurantInfo>
												<professionCode>2001003</professionCode>
												<personnelAttribute>100</personnelAttribute>
												<personnelName><![CDATA[田志勇]]></personnelName>
												<sexCode>M</sexCode>
												<certificateType>01</certificateType>
												<certificateNo>630121196506291516</certificateNo>
												<birthday>1965-06-29</birthday>
												<mobileTelephone>13001234566</mobileTelephone>
												<email>5@12.com</email>
												<familyNameSpell/>
												<firstNameSpell/>
												<personnelAge/>
											</insurantInfo>
			                               </insurantInfo>
		                           </subjectInfo>
		                           	
								   	<subjectInfo>
								   	<subjectName>2</subjectName>
									<applyPersonnelNum>5</applyPersonnelNum>
									<totalModalPremium>500</totalModalPremium>
									<planInfo>
									<planInfo>
										<planCode>Y502</planCode>	
										<applyNum>5</applyNum>
										<totalModalPremium>400</totalModalPremium>
										<applyMonth>12</applyMonth>
										<applyDay/>
									<dutyInfo>
													<dutyInfo>
														<dutyCode>YA01</dutyCode>
														<totalModalPremium>100.00</totalModalPremium>
														<dutyAount>100000</dutyAount>
														
							<dutyFactorInfo>
								<dutyFactorInfo>
								<factorId>F05</factorId>
								<factorValue>12</factorValue>
								</dutyFactorInfo>
							</dutyFactorInfo>
						</dutyInfo></dutyInfo></planInfo>
					                
					                </planInfo>
		                        
									<insurantInfo>
											<insurantInfo>
												<professionCode>0301001</professionCode>
												<personnelAttribute>100</personnelAttribute>
												<personnelName><![CDATA[王哥]]></personnelName>
												<sexCode>F</sexCode>
												<certificateType>01</certificateType>
												<certificateNo>513022198603224589</certificateNo>
												<birthday>1986-03-22</birthday>
												<mobileTelephone>13001234512</mobileTelephone>
												<email>2@1.com</email>
												<familyNameSpell/>
												<firstNameSpell/>
												<personnelAge/>
											</insurantInfo>
			                               
											<insurantInfo>
												<professionCode>0301001</professionCode>
												<personnelAttribute>100</personnelAttribute>
												<personnelName><![CDATA[方 不会]]></personnelName>
												<sexCode>M</sexCode>
												<certificateType>01</certificateType>
												<certificateNo>110101196805081018</certificateNo>
												<birthday>1968-05-08</birthday>
												<mobileTelephone>13001234514</mobileTelephone>
												<email>3@1.com</email>
												<familyNameSpell/>
												<firstNameSpell/>
												<personnelAge/>
											</insurantInfo>
			                               
											<insurantInfo>
												<professionCode>0301001</professionCode>
												<personnelAttribute>100</personnelAttribute>
												<personnelName><![CDATA[范小子]]></personnelName>
												<sexCode>F</sexCode>
												<certificateType>01</certificateType>
												<certificateNo>110108196911285449</certificateNo>
												<birthday>1969-11-28</birthday>
												<mobileTelephone>13001234570</mobileTelephone>
												<email>4@1.com</email>
												<familyNameSpell/>
												<firstNameSpell/>
												<personnelAge/>
											</insurantInfo>
			                               
											<insurantInfo>
												<professionCode>0301001</professionCode>
												<personnelAttribute>100</personnelAttribute>
												<personnelName><![CDATA[郭明白]]></personnelName>
												<sexCode>F</sexCode>
												<certificateType>01</certificateType>
												<certificateNo>342623198110088124</certificateNo>
												<birthday>1981-10-08</birthday>
												<mobileTelephone>13001234545</mobileTelephone>
												<email>1@1.com</email>
												<familyNameSpell/>
												<firstNameSpell/>
												<personnelAge/>
											</insurantInfo>
			                               
											<insurantInfo>
												<professionCode>0301001</professionCode>
												<personnelAttribute>100</personnelAttribute>
												<personnelName><![CDATA[田明明]]></personnelName>
												<sexCode>F</sexCode>
												<certificateType>01</certificateType>
												<certificateNo>110105195903020447</certificateNo>
												<birthday>1959-03-02</birthday>
												<mobileTelephone>13001234568</mobileTelephone>
												<email>5@1.com</email>
												<familyNameSpell/>
												<firstNameSpell/>
												<personnelAge/>
											</insurantInfo>
			                               </insurantInfo>
		                           </subjectInfo>
												</subjectInfo>
								</ahsPolicy>
			</Request>
			</eaiAhsXml>';

}

/*
 * author  :    zhangxi
 * date    :    20141205
 * function:    创建XML中的元素,本函数需要通过DomDocument对象来创建
 * input   
 * 		$dom    :
 * 		$item   ：
 * 		$data   ：
 * 		$attribute：
 * return ：
 * 
 * */
function gen_pingan_policy_xml_Y502($policy_arr,
								$user_info_applicant,
								$list_subject)
{
	//comment by zhangxi, 20150727, 双层级测试代码
	//return two_subject_test_code($policy_arr);
	ss_log("into function: ".__FUNCTION__);
	
	$str_header  = gen_pingan_header_Y502($policy_arr);
	//增加request元素
	$str_request = gen_pingan_request_Y502(  $policy_arr,
											$user_info_applicant,
											$list_subject);
	
	$str_xml='<?xml version="1.0" encoding="GB2312"?>
	<eaiAhsXml>'."\n".
	$str_header.
	$str_request.
	'</eaiAhsXml>';
	
	
	/*
	
	//////////////////////////////////////////////////////////////////////////////////////
	$str_xml = preg_replace('/((\s)*(\n)+(\s)*)/i','',$str_xml);//End_php
	
	//$af_path = S_ROOT."xml/"."1111111111_pingan_policy_post.xml";
	//file_put_contents($af_path,$str_xml);//zhx ,生成准备投保的文件
	
	
	$doc=new DOMDocument("1.0","utf-8");	// 实例化一个对象，并设置 XML 版本和编码
	
	$doc->formatOutput=true;			// 格式化输出，保留缩进
	$doc->preservaWhiteSpace=false;		// 不保留空格，这个是辅助格式化输出的
	$doc->loadXML($str_xml);
	
	$str_xml = $doc->saveXML();
	
	//////////////////////////////////////////////////////////////////////////////////////
	*/
	
	return $str_xml;
}
//added by zhangxi, 20141205 
function gen_pingan_header_Y502($policy_arr)
{
	global $_SGLOBAL;
	$BK_SERIAL = getRandOnly_Id(0);//add by wangcya, 20150326,平安要求每次交易流水号（也就是每次请求）需要变化
	
	$str_header="
	<Header>
		<TRAN_CODE>100083</TRAN_CODE>
		<BANK_CODE>7961</BANK_CODE>
		<BRNO>79610000</BRNO>
		<TELLERNO/>
		<BK_ACCT_DATE>".date('Ymd',$_SGLOBAL['timestamp'])."</BK_ACCT_DATE>
		<BK_ACCT_TIME>".date('H:i:s',$_SGLOBAL['timestamp'])."</BK_ACCT_TIME>
		<BK_SERIAL>".$BK_SERIAL."</BK_SERIAL>
		<BK_TRAN_CHNL>WEB</BK_TRAN_CHNL>
		<REGION_CODE>000000</REGION_CODE>
	</Header>";
	return $str_header;
	
}
function gen_pingan_request_Y502(		$policy_arr,
									$user_info_applicant,
									$list_subject)
{
	global $_SGLOBAL;
	$total_premium = 0;
	$partnerSystemSeriesNo = $policy_arr['policy_id'];////add by wangcya, 20150326,policy_id这是唯一的，$orderNum
	
	///////////////////////////////////////////////////////////////////////////////////////
	$arr_time=array('start_date'=>$policy_arr['start_date'],
					'end_date'=>$policy_arr['end_date'],
					);
	$arr_interval = get_interval_by_strtime('month', $arr_time);
	ss_log("get month is ".$arr_interval['interval']."start time".$arr_time['start_date']."end time ".$arr_time['end_date']);
	//销单节点信息，投保单不需要
	$insuranceApplicantInfo='';
	
	
	//保单扩展信息policyExtendInfo	
	$str_Extend_Info="
					 <policyExtendInfo>
						<partnerName>ZTXHBXDL</partnerName>
						<partnerSystemSeriesNo>".$partnerSystemSeriesNo."</partnerSystemSeriesNo>
					  </policyExtendInfo>
					 " ;
	
	
	//保单车票信息policyTicketInfo,暂无
	//保单配送 信息 policySendInfo， 暂无
	//保单支付信息policyPayInfo,暂无
	
	//保单中投保人信息insuranceApplicantInfo
	//根据投保类型决定是个人还是团体信息
	if($policy_arr['business_type'] == 1)//个人
	{
		
		if(defined('IS_NO_GBK'))
		{
			$user_info_applicant_fullname =  $user_info_applicant['fullname']; //已知原编码为UTF-8, 转换为GBK
			$user_info_applicant_fullname = '<![CDATA['.$user_info_applicant_fullname.']]>';
		}
		else
		{
			$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}
		//echo "user info".$user_info_applicant_fullname;
		//投保人是个人的，Y502不用考虑
	}
	elseif($policy_arr['business_type'] == 2)//团体
	{
		if(defined('IS_NO_GBK'))
		{
			//$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['group_name'], "GBK", "UTF-8" ); 
			$user_info_applicant_fullname = $user_info_applicant['group_name']; //已知原编码为UTF-8, 转换为GBK
			$user_info_applicant_fullname = '<![CDATA['.$user_info_applicant_fullname.']]>';
			
			$groupAbbr =  $user_info_applicant['group_abbr']; //已知原编码为UTF-8, 转换为GBK
			$groupAbbr = '<![CDATA['.$groupAbbr.']]>';
			
			$address   =  $user_info_applicant['address']; //已知原编码为UTF-8, 转换为GBK
			$address = '<![CDATA['.$address.']]>';
			
			$linkman_fullname = $user_info_applicant['applicant_fullname']; 
			$linkman_fullname = '<![CDATA['.$linkman_fullname.']]>';
			
		}
		else
		{
			$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['group_name'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			$groupAbbr =  mb_convert_encoding($user_info_applicant['group_abbr'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			$address =  mb_convert_encoding($user_info_applicant['address'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}
		
		
		//投保人信息
		$insuranceApplicantInfo ="
					<insuranceApplicantInfo>
						<groupPersonnelInfo>
							<groupName>".$user_info_applicant_fullname."</groupName>
							<groupCertificateNo>".$user_info_applicant[group_certificates_code]."</groupCertificateNo>
							<groupCertificateType>".$user_info_applicant[group_certificates_type]."</groupCertificateType>
							<groupAbbr>".$groupAbbr."</groupAbbr>
							<address>".$address."</address>
							<postCode/>
							<companyAttribute>".$user_info_applicant[company_attribute]."</companyAttribute>
							<industryCode/>
							<businessRegisterId/>
							<phoneExchangeArea/>
							<phoneExchange/>
							<bankCode/>
							<bankAccount/>
							<businessRegionType/>
							<linkManName>".$linkman_fullname."</linkManName>
							<linkManSexCode/>
							<linkManMobileTelephone>".$user_info_applicant[mobiletelephone]."</linkManMobileTelephone>
							<linkManEmail>".$user_info_applicant[email]."</linkManEmail>
						</groupPersonnelInfo>
				    </insuranceApplicantInfo>
								";
	}
	else
	{
		//error， log here
	}
	
	
	
	
	//保单建工险信息policyProjectInfo,暂无
	
	//以下是层级信息， 重点
	
	//for 循环多个层级,即多个subject的情况，这个就是实际情况，y502是多层级
	$index=0;
	$str_subject_info = '';
	foreach($list_subject AS $key =>$value)
	{
		$index++;
		//$index = $key+1;
		//产品信息列表
		$list_subject_product  = $value['list_subject_product'] ;
		//被保险人信息列表
		$list_subject_insurant = $value['list_subject_insurant'] ;
		
		$policy_subject_id = $value['policy_subject_id'] ;
		
		//保险责任价格列表信息
		$list_subject_product_duty_price = $value['list_subject_product_duty_price'] ;
	
		//循环获取所有被保险人信息
		$insurant_info='';
		$insurant_num = 0;
		
		foreach($list_subject_insurant AS $key_insurant =>$value_insurant )
		{
			if(defined('IS_NO_GBK'))
			{
				//$user_info_assured_fullname =  mb_convert_encoding($value_insurant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
				$user_info_assured_fullname =  $value_insurant['fullname']; //已知原编码为UTF-8, 转换为GBK
				$user_info_assured_fullname = '<![CDATA['.$user_info_assured_fullname.']]>';
			}
			else
			{
				$user_info_assured_fullname =  mb_convert_encoding($value_insurant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			}
			//职业类别，人员属性，固定100，被保险人姓名，性别，证件类型，证件号，出生日期，
			//可空配置项: 电话号码？ email？ 年龄（自动计算？）
			//for 被保险人信息，end
			$insurant_info = $insurant_info."
											<insurantInfo>
												<professionCode>".$value_insurant[occupationClassCode]."</professionCode>
												<personnelAttribute>100</personnelAttribute>
												<personnelName>".$user_info_assured_fullname."</personnelName>
												<sexCode>".$value_insurant[gender]."</sexCode>
												<certificateType>".$value_insurant[certificates_type]."</certificateType>
												<certificateNo>".$value_insurant[certificates_code]."</certificateNo>
												<birthday>".$value_insurant[birthday]."</birthday>
												<mobileTelephone>".$value_insurant[mobiletelephone]."</mobileTelephone>
												<email>".$value_insurant[email]."</email>
												<familyNameSpell/>
												<firstNameSpell/>
												<personnelAge/>
											</insurantInfo>
			                               ";
			  $insurant_num++;
			
	
		}//end foreach $list_subject_insurant, 被保险人设置循环结束
		
		$str_total_insurant_info = "
									<insurantInfo>".$insurant_info.
									"
									</insurantInfo>
		                           ";
	
	
	
	
		//productInfo和planInfo可能同时有数据吗？
		//subject的多层元素 
		//productInfo
		
		//planInfo
		$subject_total_premium = 0;
		$str_plan_info ='';
		//for 循环添加多个planInfo，即多个产品信息
		foreach($list_subject_product as $keys=>$product_value)
		{
			
			$plan_total_premium = 0;
			
			//duty信息,责任信息
			//for 循环添加多个dutyInfo
			//得先找到当前用户已投产品下用户选取的责任信息，可能一个也可能多个
				$wheresql = "ipd.product_id=$product_value[product_id] AND  pspdp.policy_subject_id=$policy_subject_id";
				$sql = "SELECT d.duty_code,ipdp.premium,ipdp.amount FROM ".tname('insurance_product_duty_price')." AS ipdp 
						INNER JOIN ".tname('insurance_policy_subject_product_duty_prices')." AS pspdp 
				        ON pspdp.product_duty_price_id= ipdp.product_duty_price_id 
				        INNER JOIN ".tname('insurance_product_duty')." AS ipd 
				        ON ipdp.product_duty_id= ipd.product_duty_id 
						INNER JOIN ".tname('insurance_duty')." AS d 
				        ON d.duty_id= ipd.duty_id 
						WHERE ". $wheresql;
				ss_log("get product duty price sql:".$sql);
				$query = $_SGLOBAL['db']->query($sql);
				//循环当前产品下的每一个duty
				$str_duty_info='';
				while($product_duty_price = $_SGLOBAL['db']->fetch_array($query) )
				{
					$dutycode= $product_duty_price['duty_code'];
					$duty_totalModalPremium = $product_duty_price['premium'];
					$dutyAount = $product_duty_price['amount'];
					//增加该duty下的影响因子
					$str_factor_duty_info = gen_xml_add_factor_for_duty($product_duty_price['duty_code'],$arr_interval['interval']);
					$str_duty_info =$str_duty_info. '
													<dutyInfo>
														<dutyCode>'.$dutycode.'</dutyCode>
														<totalModalPremium>'.$duty_totalModalPremium.'</totalModalPremium>
														<dutyAount>'.$dutyAount.'</dutyAount>
														' .$str_factor_duty_info.
													'</dutyInfo>';
					//每一个产品的保费统计=每个产品下的多个责任保费的累加
					$plan_total_premium += $product_duty_price['premium']*$insurant_num;
				}//end for dutyInfo
				
				$total_duty_info = '
									<dutyInfo>' .
									$str_duty_info.
									'</dutyInfo>';
									
				$plancode = $product_value['product_code'];
				$applynum = $insurant_num;
				$plan_totalModalPremium = $plan_total_premium;
				$plan_applyMonth = $arr_interval['interval'];
									
				//mod by zhangxi,20141225, 如果是Y502主险，则放到第一位
				if($plancode == 'Y502')
				{
					$str_plan_info	='
									<planInfo>
										<planCode>'.$plancode.'</planCode>	
										<applyNum>'.$applynum.'</applyNum>
										<totalModalPremium>'.$plan_totalModalPremium.'</totalModalPremium>
										<applyMonth>'.$plan_applyMonth.'</applyMonth>
										<applyDay/>' .
										$total_duty_info.
									'</planInfo>
					                '.$str_plan_info;
				}
				else
				{
					$str_plan_info	=$str_plan_info.'
									<planInfo>
										<planCode>'.$plancode.'</planCode>	
										<applyNum>'.$applynum.'</applyNum>
										<totalModalPremium>'.$plan_totalModalPremium.'</totalModalPremium>
										<applyMonth>'.$plan_applyMonth.'</applyMonth>
										<applyDay/>' .
										$total_duty_info.
									'</planInfo>
					                ';
				}
			
			//每一个plan的保费进行累加
			$subject_total_premium += $plan_total_premium;
				
		}//end for planInfo
		
		$str_total_plan_info = '	
									<totalModalPremium>'.$subject_total_premium.'</totalModalPremium>
									<planInfo>' .
										$str_plan_info.
									'</planInfo>
		                        ';
		
		
		
		
		$total_premium += $subject_total_premium;	
		//多个subject汇总
		$str_subject_info= $str_subject_info. "
												<subjectInfo>
												<subjectName>".$index."</subjectName>
												<applyPersonnelNum>".$insurant_num."</applyPersonnelNum>" .
													$str_total_plan_info.
													$str_total_insurant_info.
												"</subjectInfo>
												";
		
	}//end foreach $subjectlist
	
	
	
	$str_total_subject_info = "
								<subjectInfo>"
									.$str_subject_info.
								"</subjectInfo>
								";
	//这里可以增加个校验，被保险人加起来应该等于$policy_arr['apply_num']
	
	//保单基本信息 policyBaseInfo,放到了最后面，是为了能够计算得出总的保费
	$limit_note=get_limit_note_by_attribute_id($policy_arr['attribute_id']);
	$str_base_info = "
						<policyBaseInfo>
							<applyPersonnelNum>".$policy_arr['apply_num']."</applyPersonnelNum>
							<relationshipWithInsured>".$policy_arr['relationship_with_insured']."</relationshipWithInsured>
							<totalModalPremium>".$total_premium."</totalModalPremium>
							<insuranceBeginTime>".$policy_arr['start_date']."</insuranceBeginTime>
							<insuranceEndTime>".$policy_arr['end_date']."</insuranceEndTime>
							<applyMonth>".$arr_interval['interval']."</applyMonth>
							<applyDay/>
							<businessType>".$policy_arr['business_type']."</businessType>
							<currecyCode>01</currecyCode>
							<alterableSpecialPromise>".$limit_note."</alterableSpecialPromise>
						</policyBaseInfo>
					 ";
		
	$str_request = "
			<Request>
				<ahsPolicy>" .
					$str_base_info.
					$str_Extend_Info.
					$insuranceApplicantInfo.
					$str_total_subject_info.
				"</ahsPolicy>
			</Request>
			";
	
	return $str_request;
		
}

//added by zhangxi, 20141205 
function gen_pingan_request_element($dom, $parent_element, 
									$policy_arr,
									$user_info_applicant,
									$list_subject)
{
	$partnerSystemSeriesNo = $policy_arr['policy_id'];////add by wangcya, 20150326,policy_id这是唯一的，$orderNum
	
	$ele_request = $dom->createElement('Request');
	$parent_element->appendchild($ele_request);
	$ele_ashPolicy = $dom->createElement('ashPolicy');
	$ele_request->appendchild($ele_ashPolicy);
	global $_SGLOBAL;
	$total_premium = 0;
	
	$arr_time=array('start_date'=>$policy_arr['start_date'],
					'end_date'=>$policy_arr['end_date'],
					);
	$arr_interval = get_interval_by_strtime('month', $arr_time);
	ss_log("get month is ".$arr_interval['interval']."start time".$arr_time['start_date']."end time ".$arr_time['end_date']);
	//销单节点信息，投保单不需要
	
	
	//保单扩展信息policyExtendInfo
	$ele_policyExtendInfo = $dom->createElement('policyExtendInfo');
	$ele_ashPolicy->appendchild($ele_policyExtendInfo);
	$policyExtendInfo_array = array(
					    'partnerName' => 'ZTXH',
					    'partnerSystemSeriesNo' =>$partnerSystemSeriesNo,
					    );
	xml_create_item($dom, $ele_policyExtendInfo, $policyExtendInfo_array);
	
	
	
	//保单车票信息policyTicketInfo,暂无
	//保单配送 信息 policySendInfo， 暂无
	//保单支付信息policyPayInfo,暂无
	
	//保单中投保人信息insuranceApplicantInfo
	$ele_insuranceApplicantInfo = $dom->createElement('insuranceApplicantInfo');
	$ele_ashPolicy->appendchild($ele_insuranceApplicantInfo);
	//根据投保类型决定是个人还是团体信息
	if($policy_arr['business_type'] == 1)//个人
	{
		
		if(defined('IS_NO_GBK'))
		{
			$user_info_applicant_fullname =  $user_info_applicant['fullname']; //已知原编码为UTF-8, 转换为GBK
			$user_info_applicant_fullname = '<![CDATA['.$user_info_applicant_fullname.']]>';
		}
		else
		{
			$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}
		
		$ele_individualPersonnelInfo = $dom->createElement('individualPersonnelInfo');
		$ele_insuranceApplicantInfo->appendchild($ele_individualPersonnelInfo);
		//echo "user info".$user_info_applicant_fullname;
		//投保人是个人的，Y502不用考虑
		$individualPersonnelInfo_array = array(
					    'personnelName' => $user_info_applicant_fullname,
					    'sexCode' => $user_info_applicant[gender],
					    'certificateType' => $user_info_applicant[certificates_type],
					    'certificateNo' => $user_info_applicant[certificates_code],
					    'birthday' => $user_info_applicant[birthday],
					    //'familyNameSpell ' => '',
					    //'firstNameSpell ' => '',
					    //'personnelAge' => '',//需要填写么? 但样例报文中有，平安样例报文中，这个也是可空的
					    'mobileTelephone' => $user_info_applicant[mobiletelephone],
					    'email' => $user_info_applicant[email],
					    );
					    
		xml_create_item($dom, $ele_individualPersonnelInfo, $individualPersonnelInfo_array);
		
	}
	elseif($policy_arr['business_type'] == 2)//团体
	{
		ss_log("zhx2".$user_info_applicant['group_name']);
		//$user_info_applicant_fullname = iconv("UTF-8","GBK", $user_info_applicant['group_name']);
		
		$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['group_name'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		$groupAbbr =  mb_convert_encoding($user_info_applicant['group_abbr'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		$address =  mb_convert_encoding($user_info_applicant['address'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
	    ss_log("user_info_applicant_fullname:".$user_info_applicant_fullname);
		$ele_groupPersonnelInfo = $dom->createElement('groupPersonnelInfo');
		$ele_insuranceApplicantInfo->appendchild($ele_groupPersonnelInfo);
		
					$ele_groupname=$dom->createElement('groupName');
					$ele_groupPersonnelInfo->appendchild($ele_groupname);
					
					$gname=$dom->createCDATASection("test");
					$gname->nodeValue = $user_info_applicant_fullname;
					//ss_log("gname =".$gname);
					$ele_groupname->appendchild($gname);
		
		
		$groupPersonnelInfo_array = array(
					   //'groupName' => $user_info_applicant_fullname,
					    'groupCertificateNo' => $user_info_applicant[group_certificates_code],
					    'groupCertificateType' => $user_info_applicant[group_certificates_type],
					    'groupAbbr' => $groupAbbr,
					    'address' => $address,//投保人地址
					    //'postCode' => '',
					    'companyAttribute' => $user_info_applicant[company_attribute],
					    //'industryCode' => '',//可空,行业类别
					    //'businessRegisterId' => '',//
					    //'phoneExchangeArea' => '',
					    //'phoneExchange' => '',
					    //'bankCode' => '',
					    //'bankAccount' => '',
					    //'businessRegionType' => '',
					    //从代理人信息中拿么?
					    //'linkManName' => '',//报文样例中，这个是有值的。但平安文档中也是可空的
					    //'linkManSexCode' => '',//报文样例中，这个是有值的。但平安文档中也是可空的
					    //'linkManMobileTelephone' => '',//报文样例中，这个是有值的，但平安文档中也是可空的
					    );
		xml_create_item($dom, $ele_groupPersonnelInfo, $groupPersonnelInfo_array);
	}
	else
	{
		//error， log here
	}
	
	
	
	
	//保单建工险信息policyProjectInfo,暂无
	
	//以下是层级信息， 重点
	$ele_subjectInfo = $dom->createElement('subjectInfo');
	$ele_ashPolicy->appendchild($ele_subjectInfo);
	
	//for 循环多个层级,即多个subject的情况
	foreach($list_subject AS $key =>$value)
	{
		//$index = $key+1;
		//产品信息列表
		$list_subject_product  = $value['list_subject_product'] ;
		//被保险人信息列表
		$list_subject_insurant = $value['list_subject_insurant'] ;
		
		$policy_subject_id = $value['policy_subject_id'] ;
		
		//保险责任价格列表信息
		$list_subject_product_duty_price = $value['list_subject_product_duty_price'] ;
	
		$ele_subjectInfo1 = $dom->createElement('subjectInfo');
		$ele_subjectInfo->appendchild($ele_subjectInfo1);
		

		//productInfo和planInfo可能同时有数据吗？
		//subject的多层元素 
		//productInfo
		
		//planInfo
		$ele_planInfo = $dom->createElement('planInfo');
		$ele_subjectInfo1->appendchild($ele_planInfo);
		$subject_total_premium = 0;
		//for 循环添加多个planInfo，即多个产品信息
		foreach($list_subject_product as $keys=>$product_value)
		{
			
			$plan_total_premium = 0;
			$ele_planInfo1 = $dom->createElement('planInfo');
			$ele_planInfo->appendchild($ele_planInfo1);
			
			
			//duty信息,责任信息
			$ele_dutyInfo = $dom->createElement('dutyInfo');
			$ele_planInfo1->appendchild($ele_dutyInfo);
			//for 循环添加多个dutyInfo
			//得先找到当前用户已投产品下用户选取的责任信息，可能一个也可能多个
				$wheresql = "ipd.product_id=$product_value[product_id] AND  pspdp.policy_subject_id=$policy_subject_id";
				$sql = "SELECT d.duty_code,ipdp.premium,ipdp.amount FROM ".tname('insurance_product_duty_price')." AS ipdp 
						INNER JOIN ".tname('insurance_policy_subject_product_duty_prices')." AS pspdp 
				        ON pspdp.product_duty_price_id= ipdp.product_duty_price_id 
				        INNER JOIN ".tname('insurance_product_duty')." AS ipd 
				        ON ipdp.product_duty_id= ipd.product_duty_id 
						INNER JOIN ".tname('insurance_duty')." AS d 
				        ON d.duty_id= ipd.duty_id 
						WHERE ". $wheresql;
				ss_log("get product duty price sql:".$sql);
				$query = $_SGLOBAL['db']->query($sql);
				//循环当前产品下的每一个duty
				while($product_duty_price = $_SGLOBAL['db']->fetch_array($query) )
				{
					$ele_dutyInfo1 = $dom->createElement('dutyInfo');
					$ele_dutyInfo->appendchild($ele_dutyInfo1);
					$dutyInfo_array = array(
										'dutyCode' => $product_duty_price['duty_code'],
										'totalModalPremium' => $product_duty_price['premium'],
										'dutyAount' => $product_duty_price['amount'],
										);
					xml_create_item($dom, $ele_dutyInfo1, $dutyInfo_array);
					//增加该duty下的影响因子
					gen_xml_add_factor_for_duty($dom, $ele_dutyInfo1, $product_duty_price['duty_code'],$arr_interval['interval']);
					//每一个产品的保费统计
					$plan_total_premium += $product_duty_price['premium']*$policy_arr['apply_num'];
				}//end for dutyInfo
				
				$planInfo_array = array(
								'planCode'=>$product_value['product_code'],//产品代码
								'applyNum'=>$policy_arr['apply_num'],
								'totalModalPremium'=>$plan_total_premium,//通过责任累加计算出来
								'applyMonth'=> $arr_interval['interval'],//如何获取月份?
								//'applyDay'=>'',	
							);
			xml_create_item($dom, $ele_planInfo1, $planInfo_array);
			
			//每一个plan的保费进行累加
			$subject_total_premium += $plan_total_premium;
				
		}//end for planInfo
		
		//subject中的单层元素，当前保单总额,应该是没给plan的累加
		$subject_array = array(
						    'totalModalPremium' => $subject_total_premium,//通过将其中产品投保累加得到
						    );
		xml_create_item($dom, $ele_subjectInfo1, $subject_array);

		
		//被保险人信息, insurantInfo
		$ele_insurantInfo = $dom->createElement('insurantInfo');
		$ele_subjectInfo1->appendchild($ele_insurantInfo);
		
		//循环获取所有被保险人信息
		foreach($list_subject_insurant AS $key_insurant =>$value_insurant )
		{
			if(defined('IS_NO_GBK'))
			{
				$user_info_assured_fullname =  $value_insurant['fullname']; //已知原编码为UTF-8, 转换为GBK
				$user_info_assured_fullname = '<![CDATA['.$user_info_assured_fullname.']]>';
			}
			else
			{
				$user_info_assured_fullname =  mb_convert_encoding($value_insurant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			}
			//职业类别，人员属性，固定100，被保险人姓名，性别，证件类型，证件号，出生日期，
			//可空配置项: 电话号码？ email？ 年龄（自动计算？）
			$ele_insurantInfo1 = $dom->createElement('insurantInfo');
			$ele_insurantInfo->appendchild($ele_insurantInfo1);
			$insurantInfo_array = array(
									'professionCode' => $value_insurant[occupationClassCode],//职业类别代码
									'personnelAttribute'=>'100',
									'personnelName' => $user_info_assured_fullname,
									'sexCode' => $value_insurant[gender],
									'certificateType' => $value_insurant[certificates_type],
									'certificateNo'=>$value_insurant[certificates_code],
									'birthday' => $value_insurant[birthday],
									'mobileTelephone'=> $value_insurant[mobiletelephone],
									'email' => $value_insurant[email],
									//'familyNameSpell' =>'',
									//'firstNameSpell'=>'',
									//'personnelAge'=>'',//Y502样例报文中也有这项, 但平安的报文范例中可空
									);
			xml_create_item($dom, $ele_insurantInfo1, $insurantInfo_array);
			//for 被保险人信息，end
	
		}//end foreach $list_subject_insurant, 被保险人设置循环结束
		
		$total_premium += $subject_total_premium;	
		
	}//end foreach $subjectlist
	
	//保单基本信息 policyBaseInfo,放到了最后面，是为了能够计算得出总的保费
	$ele_policyBaseInfo = $dom->createElement('policyBaseInfo');
	$ele_ashPolicy->appendchild($ele_policyBaseInfo);
	$policyBaseInfo_array = array(
					    'applyPersonnelNum' => $policy_arr['apply_num'],//投保人数
					    'relationshipWithInsured' => '1',//待确认,平安的样例表单上是1
					    //'totalModalPremium' => $policy_arr['total_premium'],//总的保费
						'totalModalPremium' => $total_premium,//总的保费
					    'insuranceBeginTime' => $policy_arr['start_date'],
					    'insuranceEndTime' => $policy_arr['end_date'],
					    'applyMonth' => $arr_interval['interval'],//这里如何获取?
					    //'applyDay'=> '',
					    'businessType' => $policy_arr['business_type'],  
					    'currecyCode' => '01',//币种，01代表人民币
					    'alterableSpecialPromise' => get_limit_note_by_attribute_id($policy_arr['attribute_id']),//特别约定说明
					    );
	xml_create_item($dom, $ele_policyBaseInfo, $policyBaseInfo_array);
}

//added by zhangxi, 20141213, 创建责任下的影响因子信息
function gen_xml_add_factor_for_duty($duty_code, $interval)
{
	//factor信息,不同产品的影响因子不一样
	/*产品                           责任                          影响因子
	 * Y502        YA01          
	 * J513        JA17
	 * J511        JD06
	 *                           F05保险期间：传入值=1个月，3个月，6个月，1年
								 F09被保险人年龄：18≤传入值＜66
								 F36职业类型：传入值=一类，二类，三类，四类

	 * Y003        YA08
	 * 			   YA09
	 * 			   YA10
	 * 			   YA11
	 *                           F05保险期间：传入值=1个月，3个月，6个月，1年
								 F09被保险人年龄：18≤传入值＜66
	 * J512        JD01
	 * 							F05保险期间：传入值=1个月，3个月，6个月，1年
								F09被保险人年龄：18≤传入值＜66
								F25给付档次：传入值=一档，二挡，三档
	 * F006        JE01
	 * 							常量，跟着J513走。
	 * 
	 * 
	 */
	
	$duty_factor_info='';
	if($duty_code == 'YA01' || $duty_code == 'JA17' || $duty_code == 'JD06')
	{
		$duty_factor_info='
							<dutyFactorInfo>
								<dutyFactorInfo>
								<factorId>F05</factorId>
								<factorValue>'.$interval.'</factorValue>
								</dutyFactorInfo>
							</dutyFactorInfo>
						';
		
/*
		$ele_dutyFactorInfo2 = $dom->createElement('dutyFactorInfo');
		$ele_dutyFactorInfo->appendchild($ele_dutyFactorInfo2);
		$dutyFactorInfo_array = array(
								'factorId'=>'F36',//职业类别，1或者3
								'factorValue'=>'',
								);
		xml_create_item($dom, $ele_dutyFactorInfo2, $dutyFactorInfo_array);
		*/		
	}
	elseif($duty_code == 'YA08' 
			|| $duty_code == 'YA09' 
			|| $duty_code == 'YA10'
			|| $duty_code == 'YA11')
	{
		$duty_factor_info='
							<dutyFactorInfo>
								<dutyFactorInfo>
								<factorId>F05</factorId>
								<factorValue>'.$interval.'</factorValue>
								</dutyFactorInfo>
							</dutyFactorInfo>
						';
	}
	elseif($duty_code == 'JD01')
	{
		$duty_factor_info='
							<dutyFactorInfo>
								<dutyFactorInfo>
								<factorId>F25</factorId>
								<factorValue>10</factorValue>
								</dutyFactorInfo>
							</dutyFactorInfo>
						';
		/*
		$ele_dutyFactorInfo1 = $dom->createElement('dutyFactorInfo');
		$ele_dutyFactorInfo->appendchild($ele_dutyFactorInfo1);
		$dutyFactorInfo_array = array(
								'factorId'=>'F25',//档次？
								'factorValue'=>'10',
								);
		xml_create_item($dom, $ele_dutyFactorInfo1, $dutyFactorInfo_array);
		*/
	}
	elseif($duty_code == 'JE01')
	{
		/*
		$ele_dutyFactorInfo1 = $dom->createElement('dutyFactorInfo');
		$ele_dutyFactorInfo->appendchild($ele_dutyFactorInfo1);
		$dutyFactorInfo_array = array(
								'factorId'=>'',
								'factorValue'=>'',
								);
		xml_create_item($dom, $ele_dutyFactorInfo1, $dutyFactorInfo_array);
		*/
	}
	else
	{
		ss_log("error, unkown duty type!");
	}
	return $duty_factor_info;
		
}

/*
 * author  :    zhangxi
 * date    :    20141205
 * function:    创建XML中的元素,本函数需要通过DomDocument对象来创建
 * input   
 * 		$dom    :
 * 		$item   ：
 * 		$data   ：
 * 		$attribute：
 * return ：
 * 
 * */
function xml_create_item($dom, $item, $data, $attribute) {
    if (is_array($data)) {
        foreach ($data as $key => $val) {
            //  创建元素
            $$key = $dom->createElement($key);
            $item->appendchild($$key);
 
            //  创建元素值
            $text = $dom->createTextNode($val);
            $$key->appendchild($text);
 
            if (isset($attribute[$key])) {
            //  如果此字段存在相关属性需要设置
                foreach ($attribute[$key] as $akey => $row) {
                    //  创建属性节点
                    $$akey = $dom->createAttribute($akey);
                    $$key->appendchild($$akey);
 
                    // 创建属性值节点
                    $aval = $dom->createTextNode($row);
                    $$akey->appendChild($aval);
                }
            }   //  end if
        }
    }   //  end if
}   //  end function
//added by zhangxi , 20141211, 申明全局的
/*$G_USERINFO_PINGAN = array(
					'3'=>'fullname',//姓名列,must be first
					'6'=>'certificates_type',//证件类型列
					'7'=>'certificates_code',//证件号码列
					'8'=>'career_type',//职业类别列
					'9'=>'gender',//性别列
					'10'=>'birthday',//生日列
					'13'=>'email',//email列
					'14'=>'mobiletelephone',//联系电话列
					);
					*/
$G_USERINFO_PINGAN = array(
					'1'=>'fullname',//姓名列,must be first
					'2'=>'certificates_type',//证件类型列
					'3'=>'certificates_code',//证件号码列
					'8'=>'career_type',//职业类别列
					'5'=>'gender',//性别列
					'4'=>'birthday',//生日列
					'7'=>'email',//email列
					'6'=>'mobiletelephone',//联系电话列
					);
					
$G_XLS_UPLOAD_FORMAT_PINGAN_LVYOU = array(
					'1'=>'applicant_type',//投保人类型
					'2'=>'applicant_name',//投保人名
					'3'=>'applicant_certificates_type',//投保人证件类型
					'4'=>'applicant_certificates_code',//投保人证件号
					'5'=>'applicant_birthday',//出生日期
					'6'=>'applicant_gender',//性别
					'7'=>'applicant_mobilephone',//手机
					'8'=>'applicant_email',//email
					'9'=>'relationship',//与被保险人关系
					'10'=>'assured_name',
					'11'=>'assured_certificates_type',
					'12'=>'assured_certificates_code',
					'13'=>'assured_birthday',//性别列
					'14'=>'assured_gender',//生日列
					'15'=>'assured_mobilephone',//email列
					'16'=>'assured_email',//联系电话列
					'17'=>'destination',//旅行目的地
					);
$G_XLS_UPLOAD_FORMAT_PINGAN_JINGWAI_LVYOU = array(
					'1'=>'applicant_type',//投保人类型
					'2'=>'applicant_name',//投保人名
					'3'=>'applicant_certificates_type',//投保人证件类型
					'4'=>'applicant_certificates_code',//投保人证件号
					'5'=>'applicant_birthday',//出生日期
					'6'=>'applicant_gender',//性别
					'7'=>'applicant_mobilephone',//手机
					'8'=>'applicant_email',//email
					'9'=>'relationship',//与被保险人关系
					'10'=>'assured_name',
					'11'=>'assured_englishname',
					'12'=>'assured_certificates_type',
					'13'=>'assured_certificates_code',
					'14'=>'assured_birthday',//性别列
					'15'=>'assured_gender',//生日列
					'16'=>'assured_mobilephone',//email列
					'17'=>'assured_email',//联系电话列
					'18'=>'destination',//旅行目的地
					);
$G_XLS_UPLOAD_FORMAT_PINGAN_YIWAI = array(
					'1'=>'applicant_type',//投保人类型
					'2'=>'applicant_name',//投保人名
					'3'=>'applicant_certificates_type',//投保人证件类型
					'4'=>'applicant_certificates_code',//投保人证件号
					'5'=>'applicant_birthday',//出生日期
					'6'=>'applicant_gender',//性别
					'7'=>'applicant_mobilephone',//手机
					'8'=>'applicant_email',//email
					'9'=>'relationship',//与被保险人关系
					'10'=>'assured_name',
					'11'=>'assured_certificates_type',//被保险人证件类型
					'12'=>'assured_certificates_code',//被保险人证件号码
					'13'=>'assured_birthday',//被保险人出生日期列
					'14'=>'assured_gender',//被保险人性别列
					'15'=>'assured_mobilephone',//被保险人手机列
					'16'=>'assured_email',//被保险人email列
					'17'=>'occupation',//职业类别列
					);
					
function process_pingan_lvyou_xls_upload($uploadfile, $data_format)
{
	
	ss_log("into function ".__FUNCTION__.":".$uploadfile);
	ob_start(); 
	////////////////////////////////////////////////////////////////////
	$path = dirname(S_ROOT);

	//自己设置的上传文件存放路径
	require_once $path.'/oop/lib/Excel/PHPExcel.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/IOFactory.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/Reader/Excel5.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/Reader/Excel2007.php';
	/////////////////////////////////////////////////////////////////////
	//03,还是07自动判断
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
	
	for ($row = 11;$row <= $highestRow; $row++)
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
				
				//mod by zhangxi, 20150203, xls解析代码优化
				//if($applicant_cert_flag && ($format_arr[$col] == 'applicant_birthday'))
				//{
				//	$n = intval(($strs_row[$format_arr[$col]] - 25569)*3600*24); //转换成1970年以来的秒数
				//	$strs_row[$format_arr[$col]]= gmdate('Y-m-d',$n);//格式化时间	
				//}
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
				elseif($format_arr[$col] == 'certificates_type')
				{
					//不需要
				}
				elseif($format_arr[$col] == 'gender')
				{
					//根据不同的保险公司进行值的转换
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

//added by zhangxi, 20150119, 平安意外儿童，A,B,C卡等
function process_pingan_yiwai_xls_upload($uploadfile)
{
	ss_log("into function ".__FUNCTION__.":".$uploadfile);
	ob_start(); 
	////////////////////////////////////////////////////////////////////
	$path = dirname(S_ROOT);

	//自己设置的上传文件存放路径
	require_once $path.'/oop/lib/Excel/PHPExcel.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/IOFactory.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/Reader/Excel5.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/Reader/Excel2007.php';
	/////////////////////////////////////////////////////////////////////
	//03,还是07自动判断
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
	
    global $G_XLS_UPLOAD_FORMAT_PINGAN_YIWAI;
    $format_arr = $G_XLS_UPLOAD_FORMAT_PINGAN_YIWAI;
	$rows = array();
	for ($row = 11;$row <= $highestRow; $row++)
	{
		$strs_row = array();
		//$index=0;
		$normal_row_flag =1;
		
		$applicant_cert_flag=0;
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
				
//				if($applicant_cert_flag && ($format_arr[$col] == 'applicant_birthday'))
//				{
//					$n = intval(($strs_row[$format_arr[$col]] - 25569)*3600*24); //转换成1970年以来的秒数
//					$strs_row[$format_arr[$col]]= gmdate('Y-m-d',$n);//格式化时间	
//				}
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
				//added by zhangxi, 20141217,出生日期格式校验
				elseif($format_arr[$col] == 'assured_birthday')//被保险人
				{
					//mod by zhangxi, 20150203, xls解析代码优化
					if(strlen($strs_row[$format_arr[$col]]) == 5)
					{
						$n = intval(($strs_row[$format_arr[$col]] - 25569)*3600*24); //转换成1970年以来的秒数
						$strs_row[$format_arr[$col]]= gmdate('Y-m-d',$n);//格式化时间	
					}
				}
				elseif($format_arr[$col] == 'certificates_type')
				{
					//不需要
				}
				elseif($format_arr[$col] == 'gender')
				{
					//根据不同的保险公司进行值的转换
				}
				//$index++;
			}
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
					
function process_pingan_y502_xls_upload($uploadfile)
{
	ss_log("into function ".__FUNCTION__.":".$uploadfile);
	//03,还是07自动判断
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
	$rows = array();
	ss_log("highestRow=".$highestRow.",highestColumnIndex=".$highestColumnIndex);
	//echo 'highestColumnIndex='.$highestColumnIndex;
	//echo "<br>";
	//最多一次投保人数为3000人
	if($highestRow > 3000)
	{
		return array('result_code'=>'1',
						'result_msg'=>'failed , limited users exceed 3000',
						);
	}
	//added by zhangxi, 20141210, 
	//根据公司excel模板文件来处理
	//模板文件1列证件类型，2列证件号，3列职业类别，4列性别，5列生日，8列EMAIL，9列联系电话
	global $G_USERINFO_PINGAN;
	for ($row = 9;$row <= $highestRow; $row++)
	{
		$strs_row = array();
		//$index=0;
		$normal_row_flag =1;
		//注意highestColumnIndex的列数索引从0开始
		for ($col = 0;$col < $highestColumnIndex;$col++)
		{
			if(in_array($col,array_keys($G_USERINFO_PINGAN)))
			{
				//mod by zhangxi, 20150110, 获取公式计算之后的值
				$value = trim($objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue());
				$strs_row[$G_USERINFO_PINGAN[$col]] = strval($value);
				ss_log("index:".$G_USERINFO_PINGAN[$col].$strs_row[$G_USERINFO_PINGAN[$col]]);
				if($G_USERINFO_PINGAN[$col] == 'fullname' && empty($strs_row[$G_USERINFO_PINGAN[$col]]))
				{	
					//姓名为空的行，直接不取
					$normal_row_flag =0;
					break;
				}
				//added by zhangxi, 20141217,出生日期格式校验
				elseif($G_USERINFO_PINGAN[$col] == 'birthday')
				{
					if(strlen($strs_row[$G_USERINFO_PINGAN[$col]]) == 5)
					{
						$n = intval(($strs_row[$G_USERINFO_PINGAN[$col]] - 25569)*3600*24); //转换成1970年以来的秒数
						$strs_row[$G_USERINFO_PINGAN[$col]]= gmdate('Y-m-d',$n);//格式化时间	
					}
					
				}
				elseif($G_USERINFO_PINGAN[$col] == 'certificates_type')
				{
					if($strs_row[$G_USERINFO_PINGAN[$col]] == '身份证')
					{
						$strs_row[$G_USERINFO_PINGAN[$col]] = '01';
					}
					elseif($strs_row[$G_USERINFO_PINGAN[$col]] == '护照')
					{
						$strs_row[$G_USERINFO_PINGAN[$col]] = '02';
					}
					elseif($strs_row[$G_USERINFO_PINGAN[$col]] == '其他')
					{
						$strs_row[$G_USERINFO_PINGAN[$col]] = '03';
					}
				}
				elseif($G_USERINFO_PINGAN[$col] == 'gender')
				{
					if($strs_row[$G_USERINFO_PINGAN[$col]] == '男')
					{
						$strs_row[$G_USERINFO_PINGAN[$col]] = 'M';
					}
					elseif($strs_row[$G_USERINFO_PINGAN[$col]] == '女')
					{
						$strs_row[$G_USERINFO_PINGAN[$col]] = 'F';
					}
				}
				//$index++;
			}
			//$strcol .= $strs[$col]." ";
		}
		if($normal_row_flag == 0)
		{
			continue;
		}
		
		if(!empty($strs_row))
		{
			//增加一个职业类别列,这里写死了，数组$G_USERINFO_PINGAN变动，这里也要变动
			$strs_row['level_num'] = get_level_num_by_career_code($strs_row[$G_USERINFO_PINGAN['8']]);
			$rows[] = $strs_row;
		}	
	}

	$result=array('result_code'=>'0',
					'result_msg'=>'success',
					//'filepath'=>$uploadfile,
					'data'=>$rows,
					);
					
	return $result;
}
//added by zhangxi, 20141209, 平安被投保人文件上传处理
function process_file_xls_pingan_insured($uploadfile, $product)
{
	ss_log("into function ".__FUNCTION__.": ".$uploadfile);
	global $pingan_product_type;
	if(empty($uploadfile))
	{	
		return array('result_code'=>'1',
						'result_msg'=>'fail, file not exist',
						);
	}
	////////////////////////////////////////////////////////////////////
	$path = dirname(S_ROOT);

	//自己设置的上传文件存放路径
	require_once $path.'/oop/lib/Excel/PHPExcel.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/IOFactory.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/Reader/Excel5.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/Reader/Excel2007.php';
	/////////////////////////////////////////////////////////////////////

    //comment by zhangxi, 20150113, 一下是Y502产品的处理
	
	//旅游产品的处理
	//这里可以根据产品属性判断好，好事产品代码判断好?????
	//if(in_array($product['product_code'],$pingan_product_type['lvyou']))
	global $G_XLS_UPLOAD_FORMAT_PINGAN_LVYOU;
	global $G_XLS_UPLOAD_FORMAT_PINGAN_JINGWAI_LVYOU;
	if($product['attribute_type'] == 'lvyou')
	{
		$result = process_pingan_lvyou_xls_upload($uploadfile,$G_XLS_UPLOAD_FORMAT_PINGAN_LVYOU);
	}
	else if($product['attribute_type'] == 'jingwailvyou')
	{
		$result = process_pingan_lvyou_xls_upload($uploadfile,$G_XLS_UPLOAD_FORMAT_PINGAN_JINGWAI_LVYOU);
	}
	//意外产品的处理，平安A,B,C卡，综合意外险
	else if($product['attribute_type'] == 'product')
	{
		$result = process_pingan_yiwai_xls_upload($uploadfile);
	}
	//Y502产品的处理,
	else if($product['attribute_type'] == 'Y502')
	{
		$result = process_pingan_y502_xls_upload($uploadfile);
	}
	else
	{
		
	}
	
						
	//上传的文件，处理完成之后，是否在这里就应该删除
	
	return $result;
}

//added by zhangxi, 20141210, 通过职业类别查询到职业分类等级
function get_level_num_by_career_code($career_code)
{
	global $_SGLOBAL;
	//mod by zhangxi, 20141219, 修改成新的表获取数据
	$sql="SELECT level_num FROM t_career_category_pingan
			WHERE code='$career_code'";
	
	$query_level = $_SGLOBAL['db']->query($sql);
	$row = $_SGLOBAL['db']->fetch_row($query_level);
	return $row[0];
}

?>