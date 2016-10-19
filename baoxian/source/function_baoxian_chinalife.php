<?php

if(!defined('S_ROOT'))
{
	define('S_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
}
//modify yes123 2015-01-05 如果常量不存在，再定义
if(!defined('IS_NO_GBK')){ 
	define('IS_NO_GBK', 1);
}
//需要增加华安的通信接口道my_const.php中
include_once(S_ROOT.'/source/my_const.php');
include_once(S_ROOT.'/source/function_common.php');

////////////////////////////////////////////////////////////////////////////
$LOGFILE_CHINALIFE=S_ROOT."log/java_chinalife_post_policy.log";
////////////////////////////////////////////////////////////////////////////

//中国人寿没有提供，暂时使用平安的
$attr_relationship_with_insured_chinalife = array(
		"3"=>"父子",
		"4"=>"父女",
		"A"=>"母子",
		"B"=>"母女",
		"G"=>"祖孙",

);


//中国人寿没有提供，暂时使用平安的
$attr_certificates_type_chinalife = array(
		"01"=>"身份证",
		"02"=>"护照",
		"03"=>"军人证",
		"05"=>"驾驶证",
		"06"=>"港澳回乡证或台胞证",
		"99"=>"其他",
);

$attr_certificates_type_chinalife_single_unify = array(
		"01"=>1,//'身份证';
		"05"=>2,//'驾驶证';
		"02"=>4,//'护照';
		"06"=>5,//'港澳回乡证或台胞证';
		"03"=>8,//'军人证';
		"99"=>9,//'其他';
);

$attr_applicant_gender_chinalife = array(
		"M"=>"男",
		"F"=>"女"
);

$attr_sex_chinalife_unify = array("F"=>"F",
		"M"=>"M",
);
////////////////////////////////////////////////////////////////////////////////


function input_check_chinalife($POST)
{
	global $_SGLOBAL;
	global $attr_certificates_type_chinalife_single_unify;
	global $attr_sex_chinalife_unify;
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	ss_log(__FUNCTION__.", GET IN");
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

	//ss_log("totalModalPremium: ".$totalModalPremium);
	//$totalModalPremium'] = $apply_num*intval($product[premium]);//$POST['totalModalPremium'];//从费用

	$global_info['beneficiary'] = $POST['beneficiary'];//受益人
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期

	$global_info['apply_day'] = empty($POST['apply_day'])?intval($POST['period']):intval($POST['apply_day']);//add by wangcya, 20150110, 投保的天数
	
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
		$user_info_applicant['certificates_type_unify'] = $user_info_applicant['certificates_type'];
		$user_info_applicant['gender_unify'] = $attr_sex_chinalife_unify[$user_info_applicant['gender']];
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
		$user_info_applicant['group_certificates_type_unify'] = $user_info_applicant['group_certificates_type'];
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
		//added by zhangxi, 20150427, 获取被保险人的省，市，县信息
		$assured_info['city'] =  trim($assured_post['city_name']);
		$assured_info['county'] =  trim($assured_post['county_name']);
		$assured_info['province'] =  trim($assured_post['province_name']);
		
		//add by wangcya, 20141219 ,不同厂商的证件号码和性别
		$assured_info['certificates_type_unify'] = $attr_certificates_type_chinalife_single_unify[$assured_info['certificates_type']];
		$assured_info['gender_unify'] = $attr_sex_chinalife_unify[$assured_info['gender']];
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

?>