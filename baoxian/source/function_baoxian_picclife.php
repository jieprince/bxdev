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
$java_class_name_picclife = 'com.yanda.imsp.util.ins.PICCLifeInsurance';
$LOGFILE_PICCLIFE=S_ROOT."log/java_picclife_post_policy.log";
////////////////////////////////////////////////////////////////////////////
$attr_picclife_TransType = array(
							"103"=>"New Business Submission",
							);


$attr_picclife_TransMode = array(
							"1"=>"trial",//核保与保费试算
							"2"=>"Original",//投保确认
							"3"=>"Renewal",//续保
							);	
$attr_picclife_ApplicantMode = array(
							"1"=>"自助",
							);	
$attr_picclife_PolicyPaymentType = array(
							"0"=>"AIPAY",
							"1"=>"SNZO",
							"2"=>"ALIPAYSETTLEMENT",
							"3"=>"ALLINPAY",
							"4"=>"YEPAY",
							"5"=>"BOCPAY",
							"6"=>"CMBCPAY",
							"7"=>"ICBCPAY",
							"11"=>"TENPAY",
							"12"=>"DOLLARSPAY",
							"13"=>"UNIONPAY",
							"14"=>"PAYTOPAY",
							"15"=>"NETYEEPAY",
							);	
							
$attr_picclife_ProductLevel = array(
							"0"=>"COMMON",
							"1"=>"VIP",
							"2"=>"EXCELLENT",
							"5"=>"选择一",
							"6"=>"选择二",
							"7"=>"选择三",
							"3"=>"A",
							"4"=>"B",
							"8"=>"C",
							"9"=>"D",
							"10"=>"UNDERAGE",
							"11"=>"CAREFREE",
							"12"=>"ELITE",
							"13"=>"PLATINA",
							"14"=>"E",
							"15"=>"F",
							"16"=>"G",
							"17"=>"H",
							"18"=>"I",
							"19"=>"J",
							"20"=>"K",
							
								);	
								
$attr_picclife_ApplicantMode = array(
							"1"=>"自助",
							"2"=>"远程",
							"4"=>"激活卡"
								);		
$attr_picclife_GovtIDTC = array(
							"1"=>"身份证",
							"2"=>"军官证",
							"3"=>"护照",
							"4"=>"出生证",
							"5"=>"异常身份证",
							"6"=>"回乡证",
							"7"=>"户口本",
							"8"=>"警官证",
							"9"=>"其他"
								);	
$attr_picclife_Gender = array(
							"1"=>"男",
							"2"=>"女",
							);	
							
$attr_picclife_AddressTypeCode = array(
							"1"=>"居住地址",
							"17"=>"邮寄地址",
								);			
$attr_picclife_PhoneTypeCode = array(
							"1"=>"家庭电话号码",
							"2"=>"办公电话号码",
							"12"=>"移动电话号码",
								);				
								
$attr_picclife_RelatedObjectType = array(
							"4"=>"Holding",
							"6"=>"Party",
								);		
$attr_picclife_RelationRoleCode = array(
							"0"=>"无关",//无关
							"1"=>"配偶",//配偶关系
							"2"=>"子女",//子女
							"3"=>"父母",//父母关系
							"4"=>"亲属",//亲属关系
							"5"=>"本人",//本人关系
							"6"=>"其他",//其他关系
							"7"=>"雇佣",//雇佣关系
							"8"=>"投保人",//投保人关系
							"32"=>"被保险人",//被保人关系
							"34"=>"受益人",//受益人关系
							
								);	
$attr_picclife_RelationRoleCode_product = array(
							"2"=>"子女",
							"5"=>"本人",
							
							);
								
$attr_picclife_OriginatingObjectType = array(
							"4"=>"Holding",
							"6"=>"Party"
							);			
$attr_picclife_ResultCode = array(
							"1"=>"成功",
							"5"=>"失败"
							);	
$attr_picclife_TravelType = array(
							"1"=>"OVERSEAS",//出境旅游
							"2"=>"INCOMING",//入境旅游
							"3"=>"CISBORDER"//国内旅游
							);			
$attr_picclife_FeeStatus = array(
							"1"=>"OVERDUE",//欠费承保
							);			
							
							
$attr_certificates_type_picclife_single_unify = array(
		"1"=>1,//'身份证';
		//"05"=>2,//'驾驶证';
		"3"=>4,//'护照';
		"6"=>5,//'港澳回乡证或台胞证';
		"2"=>8,//'军人证';
		"9"=>9,//'其他';
);

$attr_sex_picclife_unify = array(
		"2"=>"F",
		"1"=>"M",
);									

$attr_picclife_applyNum_insAmount = array(
	"5" =>"5万",
	"10" =>"10万",
	"15" =>"15万",
	"20" =>"20万",
	"25" =>"25万",
	"30" =>"30万",
	"35" =>"35万",
	"40" =>"40万",
); 				


$G_XLS_UPLOAD_FORMAT_PICCLIFE_ESHIDAI_PILIANG = array(
					'1'=>'applicant_type',//投保人类型
					'2'=>'applicant_name',//投保人名
					'3'=>'applicant_certificates_type',//投保人证件类型
					'4'=>'applicant_certificates_code',//投保人证件号
					'5'=>'applicant_birthday',//出生日期
					'6'=>'applicant_gender',//性别
					'7'=>'applicant_mobilephone',//手机
					'8'=>'applicant_email',//email
					//'9'=>'applicant_address',//通信地址
					//'10'=>'applicant_postcode',//邮政编码
					
					'9'=>'relationship',//与被保险人关系
					
					
					'10'=>'assured_name', //被保人姓名
					'11'=>'assured_certificates_type',//被保险人证件类型
					'12'=>'assured_certificates_code',//被保险人证件号码
					'13'=>'assured_birthday',//被保险人出生日期列
					'14'=>'assured_gender',//被保险人性别列
					'15'=>'assured_mobilephone',//被保险人手机列
					'16'=>'assured_email',//被保险人email列
					//'19'=>'assured_address',//通信地址
					//'20'=>'assured_postcode',//邮政编码
					);
																		
////////////////////////////////////////////////////////////////////////////////


function input_check_picclife($POST)
{
	global $_SGLOBAL;
	global $attr_certificates_type_picclife_single_unify;
	global $attr_sex_picclife_unify;
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
	
	//与投保人之间的关系类型判断
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
		$user_info_applicant['address'] = $POST['applicant_address'];
		$user_info_applicant['zipcode'] = $POST['applicant_zipcode'];
		
		//start add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		$user_info_applicant['certificates_type_unify'] = $user_info_applicant['certificates_type'];
		$user_info_applicant['gender_unify'] = $attr_sex_picclife_unify[$user_info_applicant['gender']];
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
	if($relationshipWithInsured=="5")//是本人的信息
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
		$assured_info['address'] =  trim($assured_post['assured_address']);
		$assured_info['zipcode'] =  trim($assured_post['assured_zipcode']);
		
		//add by wangcya, 20141219 ,不同厂商的证件号码和性别
		$assured_info['certificates_type_unify'] = $attr_certificates_type_picclife_single_unify[$assured_info['certificates_type']];
		$assured_info['gender_unify'] = $attr_sex_picclife_unify[$assured_info['gender']];
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


function input_check_picclife_shouxian($POST)
{
	global $_SGLOBAL;
	global $attr_certificates_type_picclife_single_unify;
	global $attr_sex_picclife_unify;
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
	
	//与投保人之间的关系类型判断
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

	$agent_uid = $_SGLOBAL['supe_uid'];	
	//被保险人信息检查
	$businessType = $POST['applicant_type'];
	
	$global_info['applyNum'] = intval($POST['applyNum']);//份数
	$global_info['totalModalPremium'] = $POST['totalModalPremium'];//总保费,应该从前端计算得到的
	$global_info['beneficiary'] = $POST['beneficiary'];////受益人，1是法定，0则是需要另外指定
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期
	$global_info['payPeriod'] = $POST['payPeriod'];//缴费期间
	$global_info['insPeriod'] = intval($POST['insPeriod']);//保险期间
	$global_info['insPeriodUnit'] = intval($POST['insPeriodUnit']);//单位
	//保额，康乐无忧两全险需要
	if(isset($POST['policy_amount']))
	{
		$global_info['policy_amount'] = trim($POST['policy_amount']);
	}
	$global_info['apply_day'] = empty($POST['apply_day'])?intval($POST['period']):intval($POST['apply_day']);//add by wangcya, 20150110, 投保的天数
	
	///////////////组合投保人信息//////////////////////////////////////////////////
	$businessType = empty($POST['businessType'])?1:trim($POST['businessType']);
	
	$user_info_applicant = array();
	
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
		$user_info_applicant['address'] = $POST['applicant_address'];
		$user_info_applicant['province_code'] = $POST['applicant_province_code'];
		$user_info_applicant['province'] = $POST['applicant_province'];
		$user_info_applicant['zipcode'] = $POST['applicant_zipcode'];
		$user_info_applicant['address'] = $POST['applicant_address'];
		$user_info_applicant['income'] = $POST['applicant_income'];//年收入
		//投保人职业代码
		$user_info_applicant['occupationClassCode'] = $POST['applicant_occupationClassCode'];
		
		
		//start add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		$user_info_applicant['certificates_type_unify'] = $user_info_applicant['certificates_type'];
		$user_info_applicant['gender_unify'] = $attr_sex_picclife_unify[$user_info_applicant['gender']];
		$user_info_applicant['type'] = 1;//0,被保险人，1投保人,2两者身份相同
		$user_info_applicant['agent_uid'] = $agent_uid;
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	}
	else//团体投保人,寿险目前还没有碰到团体投保人
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
		
		$user_info_applicant['province_code'] = $POST['applicant_province_code'];
		$user_info_applicant['province'] = $POST['applicant_province'];
		
		$user_info_applicant['group_certificates_type_unify'] = $user_info_applicant['group_certificates_type'];
		$user_info_applicant['agent_uid'] = $agent_uid;

	}

	/////////////////////////////////////////////////////////////////////
	$assured_info = array();

	/////////////////////////////////////////////////////////
	$relationshipWithInsured = trim($POST['relationshipWithInsured']);//（非空）与投保人关系。1:本人 2:配偶 3 :父子 4:父女 5:受益人 6:被保人 7:投保人 A:母子 B:母女 C:兄弟 D:姐弟 G:祖孙 H:雇佣 I:子女 9:其他 8:转换不详
	////////////////投保人的身份信息////////////////////////////////
	//////////////////首先根据被保险人身份证到用户表中查询，如果查到则进行信息更新，如果没查到插入新纪录
	if($relationshipWithInsured=="5")//被保险人是投保人本人的情况
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
		$assured_info['address'] =  trim($assured_post['assured_address']);
		
		$assured_info['zipcode'] =  trim($assured_post['assured_zipcode']);
		
		$assured_info['occupationClassCode'] = trim($assured_post['assured_occupationClassCode']);
		//add by wangcya, 20141219 ,不同厂商的证件号码和性别
		$assured_info['certificates_type_unify'] = $attr_certificates_type_picclife_single_unify[$assured_info['certificates_type']];
		$assured_info['gender_unify'] = $attr_sex_picclife_unify[$assured_info['gender']];
		$assured_info['type'] = 0;//0,被保险人，1投保人,2两者身份相同
		$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别
	}
	
	$list_assured1[] = $assured_info;
	
	//以下开始是受益人信息
	$list_beneficiary1 = array();
	if(!$global_info['beneficiary'])//如果不是指定的法定,则获取用户填写的受益人
	{
		$data_user = stripslashes($POST['beneficiary_info']);
		if(empty($data_user))
		{
			showmessage("获取受益人信息失败！");
			exit(0);
		}
		ss_log(__FUNCTION__.",get post data:".$POST['beneficiary_info']);
		$list_beneficiary = json_decode($data_user, true);
		ss_log(__FUNCTION__.",json decode data:".$list_beneficiary);
		
		$beneficiary_info = array();
		//获取受益人信息，这里要考虑多个受益人的情况
		foreach($list_beneficiary as $k=>$val)
		{
			
			$beneficiary_info['cardType'] = trim($val['beneficiary_certificates_type']);
			ss_log(__FUNCTION__.", get cardType=".$beneficiary_info['cardType']);
			$beneficiary_info['cardNo'] = trim($val['beneficiary_certificates_code']);
			//$assured_info['birthday'] = trim($val['birthday']);
			$beneficiary_info['name']  = trim($val['beneficiary_name']);
			$beneficiary_info['sex']  = trim($val['beneficiary_sex']);
			$beneficiary_info['cardValidDate'] =  trim($val['beneficiary_cardValidDate']);//工程关系方手机号
			$beneficiary_info['cardExpDate'] =  trim($val['beneficiary_cardExpDate']);
			$beneficiary_info['birthday'] =  trim($val['beneficiary_birthday']);//
			$beneficiary_info['nativePlace'] =  trim($val['beneficiary_nativePlace']);//地址
			$beneficiary_info['benefitRelation'] = trim($val['beneficiary_benefitRelation']);
			$beneficiary_info['benefitScale'] = trim($val['beneficiary_benefitScale']);//受益人受益比例
			$beneficiary_info['benefitSort'] = trim($val['beneficiary_benefitSort']);//受益顺序号

			$list_beneficiary1[] = $beneficiary_info;
		}
	}
	//暂时考虑了一个被保险人的情况
	$list_assured1[0]['list_beneficiary'] = $list_beneficiary1;//受益人应该挂在具体的某一个被保险人下面
	
	//险种下主产品的id
	$product_id = intval($POST['product_id']);
	//////////一个subjectInfo内多个产品，多个被保险人///////////////////////////////////////////////////////////
	////////方案一是父母的////////////
	$subjectInfo1 = array();
	
	//获取用户选定的投保险种，如果主险没有选，则异常,新华安心宝贝，默认都选择
	//获取产品列表，是被投保的产品列表
	$list_product_id1 = array();
	$duty_price_ids = $_POST['duty_price_ids'];
	
	if(!isset($duty_price_ids))
	{
		showmessage("投保产品信息缺失！");
		exit(0);
	}
	//责任价格id列表获取
	$list_duty_price_id = explode(',', $duty_price_ids);
	
	///找产品id列表
	$sql="SELECT DISTINCT ipd.product_id FROM t_insurance_product_duty AS ipd 
		  INNER JOIN t_insurance_product_duty_price AS ipdp 
		  ON ipd.product_duty_id=ipdp.product_duty_id 
		  WHERE ipdp.product_duty_price_id IN ($duty_price_ids)";
	$product_id_query = $_SGLOBAL['db']->query($sql);
	$product_id_row = array();
	while ($product_id_row = $_SGLOBAL['db']->fetch_row($product_id_query))
	{
		$list_product_id1[] = $product_id_row[0];
		ss_log(__FUNCTION__.", add product id is：".$product_id_row[0]);
	}
	$subjectInfo1['list_product_id'] = $list_product_id1;
	//被保险人信息列表
	$subjectInfo1['list_assured'] = $list_assured1;
	$subjectInfo1['list_duty_price_id'] = $list_duty_price_id;//责任保费列表id

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


//这里以后最好加个参数，是核保请求报文，还是投保请求报文，最好区别一下。
function gen_trans_common_info($policy_arr, $transmode)
{
	global $_SGLOBAL;
	global $attr_picclife_TransMode;
	$transSn = getRandOnly_Id(0);
	$common_info = '
	  <!-- 交易流水号 -->
		<TransRefGUID>'.$transSn.'</TransRefGUID>
		<!-- 交易码 -->
    <TransType tc="103">New Business Submission</TransType>
    <!-- 交易模式 1:Trial 2:Original -->
    <!-- 1.核保与保费试算,2投保确认 3续保请求 -->
    <TransMode tc="'.$transmode.'">'.$attr_picclife_TransMode[$transmode].'</TransMode>
		<!-- 交易日期 -->
		<TransExeDate>'.date('Y-m-d',$_SGLOBAL['timestamp']).'</TransExeDate>
		<!-- 交易时间 -->
		<TransExeTime>'.date('H:i:s',$_SGLOBAL['timestamp']).'</TransExeTime>
		';
	return $common_info;
}


function gen_trans_olife($policy_arr,$user_info_applicant,$list_subject, $attribute_code,$attribute_type)
{
	global $_SGLOBAL,$attr_picclife_ProductLevel;
	
	$node_fenhong = '';
	//权益信息
	if($attribute_type == 'ijiankang'
	||$attribute_type == 'BWSJ'
	||$attribute_type == 'TTL'
	||$attribute_type == 'KLWYLQX')
	{
		//获取产品信息
		$subject_total_premium = 0;
		foreach($list_subject AS $key =>$value)
		{
			$index = $key+1;
			$list_subject_product  = $value['list_subject_product'] ;
			//被保险人信息列表
			$list_subject_insurant = $value['list_subject_insurant'] ;
			$policy_subject_id = $value['policy_subject_id'] ;
		
			//保险责任价格列表信息,这个也得用
			$list_subject_product_duty_price = $value['list_subject_product_duty_price'] ;
	
			$product_str = '';
			$total_product_premium = 0;
			foreach($list_subject_product AS $key_product =>$value_product )
			{
				$plan_total_premium = 0;
			//duty信息,责任信息
			//for 循环添加多个dutyInfo
			//得先找到当前用户已投产品下用户选取的责任信息，可能一个也可能多个
				$wheresql = "ipd.product_id=$value_product[product_id] AND  pspdp.policy_subject_id=$policy_subject_id";
				
				$sql = "SELECT d.duty_code,ipdp.premium,ipdp.amount FROM ".tname('insurance_product_duty_price')." AS ipdp 
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
				$str_duty_info='';
				//目前只有一个责任
				while($product_duty_price = $_SGLOBAL['db']->fetch_array($query) )
				{
					$dutycode= $product_duty_price['duty_code'];
					$duty_totalModalPremium = $product_duty_price['premium'];//保费
					$dutyAount = $product_duty_price['amount'];
					//每一个产品的保费统计
					//mod by zhangxi, 20150528, BWSJ产品后台配置的保费已经乘以份数了，所以这里不要再乘
					if($attribute_type == 'BWSJ')
					{
						$plan_total_premium += $product_duty_price['premium'];
					}
					else if($attribute_type == 'ijiankang'
					|| $attribute_type == 'TTL')
					{
						$plan_total_premium += $product_duty_price['premium']*$policy_arr['apply_num'];
					}
					else
					{
						$num = round($policy_arr['policy_amount']/10000,2);//这个是对哪个险种呢? 是不是康乐无忧分红险?
						$plan_total_premium += $product_duty_price['premium']*$policy_arr['apply_num']*$num;
					}
					
				}//end for dutyInfo
				
				$product_code = $value_product['product_code'];
				
				$plan_totalModalPremium = $plan_total_premium;//一个险种的保费
				
				ss_log(__FUNCTION__.", PRODUCT TYPE=".$value_product['product_type'].", payperiod=".$policy_arr['payPeriod']);					

			
				//每一个险种的保费进行累加
				$subject_total_premium += $plan_totalModalPremium;
				
				
				$product_type = $value_product['product_type'];
				$flag = ($product_type == 'main') ? 1:2;
				$str_flag = ($flag == 1) ? 'Base':'RIDER';
				
				if($attribute_type == 'ijiankang')
				{
					$CurrentAmt = 10000*$policy_arr['apply_num'];//保额
				}
				elseif($attribute_type == 'BWSJ')
				{
					$CurrentAmt = $dutyAount;
				}
				elseif($attribute_type == 'TTL')
				{
					//有问题，后续再看
					//$CurrentAmt = $product_duty_price['premium']*$policy_arr['apply_num'];//保额
					$CurrentAmt = 1000*$policy_arr['apply_num'];//保额
				}
				elseif($attribute_type == 'KLWYLQX')
				{
					$CurrentAmt = $policy_arr['policy_amount'];//保额
					ss_log(__FUNCTION__.", 保额:".$CurrentAmt);
					$CurrentAmt = round($CurrentAmt,0);
				}
				
				//缴费类型  年缴：2   趸交：1 
				$PaymentDurationMode = ($policy_arr['payPeriod'] == 1) ?  1:2;
				
				//缴费频次 年缴：1   趸交：5
				$ChargeType = ($PaymentDurationMode == 1) ? 5:1;
				if(0)//(defined('IS_NO_GBK'))
				{
					
					
					$pro_name =  mb_convert_encoding($value_product['product_name'], "GBK", "UTF-8" ); 
				}
				else
				{
					$pro_name = $value_product['product_name'];
				}
				$tmp_payPeriod = ($policy_arr['payPeriod'] == 1) ? 0:$policy_arr['payPeriod'];
				
				//mod by zhangxi, 20150623, 主险需要放到前面
				if($flag == 1)
				{
					$product_str =   '
							<Coverage VendorCode="10">
				             	<!-- 险种代码 -->
			                    <ProductCode>'.$value_product['product_code'].'</ProductCode>
			                    <PlanName>'.$pro_name.'</PlanName>
			                    <!-- 主险,附加险标识 -->
			                    <IndicatorCode tc="'.$flag.'">'.$str_flag.'</IndicatorCode>
			                    <!-- 保额 -->
			                    <CurrentAmt>'.$CurrentAmt.'</CurrentAmt>
			                    <!-- 保费 -->
			                    <ModalPremAmt>'.$plan_totalModalPremium.'</ModalPremAmt>
			                    <!-- 缴费类型  年缴：2   趸交：1 -->
			                    <PaymentDurationMode tc="'.$PaymentDurationMode.'">ANNUAL</PaymentDurationMode>
			                    <!-- 缴费频次   年缴：1   趸交：5-->
			                    <ChargeType tc="'.$ChargeType.'">ANNUAL</ChargeType>
			                    <!-- 缴费期间  -->
			                    <PaymentDuration>'.$tmp_payPeriod.'</PaymentDuration>
			                    <BenefitLimit>
			                    		<!-- 保障期间 保障多少年？或者保障到多少岁-->
			                        <MaxBenefitDuration>'.$policy_arr['insPeriod'].'</MaxBenefitDuration>
			                        <!-- 保障类型  tc=2 按照多少年  tc=3是多少岁-->
			                        <MaxBenefitDurPeriod tc="'.$policy_arr['insPeriodUnit'].'">2</MaxBenefitDurPeriod>
			                    </BenefitLimit>
			                </Coverage>'.$product_str;
				}
				else
				{
					$product_str .=   '
							<Coverage VendorCode="10">
				             	<!-- 险种代码 -->
			                    <ProductCode>'.$value_product['product_code'].'</ProductCode>
			                    <PlanName>'.$pro_name.'</PlanName>
			                    <!-- 主险,附加险标识 -->
			                    <IndicatorCode tc="'.$flag.'">'.$str_flag.'</IndicatorCode>
			                    <!-- 保额 -->
			                    <CurrentAmt>'.$CurrentAmt.'</CurrentAmt>
			                    <!-- 保费 -->
			                    <ModalPremAmt>'.$plan_totalModalPremium.'</ModalPremAmt>
			                    <!-- 缴费类型  年缴：2   趸交：1 -->
			                    <PaymentDurationMode tc="'.$PaymentDurationMode.'">ANNUAL</PaymentDurationMode>
			                    <!-- 缴费频次   年缴：1   趸交：5-->
			                    <ChargeType tc="'.$ChargeType.'">ANNUAL</ChargeType>
			                    <!-- 缴费期间  -->
			                    <PaymentDuration>'.$tmp_payPeriod.'</PaymentDuration>
			                    <BenefitLimit>
			                    		<!-- 保障期间 保障多少年？或者保障到多少岁-->
			                        <MaxBenefitDuration>'.$policy_arr['insPeriod'].'</MaxBenefitDuration>
			                        <!-- 保障类型  tc=2 按照多少年  tc=3是多少岁-->
			                        <MaxBenefitDurPeriod tc="'.$policy_arr['insPeriodUnit'].'">2</MaxBenefitDurPeriod>
			                    </BenefitLimit>
			                </Coverage>';
				}
				
				
			}//$list_subject_product
		}
		
		$subject_total_premium = sprintf("%01.2f", $subject_total_premium );
		$policy_arr['total_premium'] = sprintf("%01.2f", $policy_arr['total_premium'] );
		if($attribute_type != 'KLWYLQX')
		{
			if($subject_total_premium != $policy_arr['total_premium'])
			{
				ss_log(__FUNCTION__."ERROR, subject_total_premium=".$subject_total_premium.", total_premium=".$policy_arr['total_premium']);
				die("error, exit");
			}	
		}
		
		$start = explode(' ', $policy_arr['start_date']);
		$end = explode(' ', $policy_arr['end_date']);
		
		if(0)//(defined('IS_NO_GBK'))
		{
			
			
			$attr_name =  mb_convert_encoding($policy_arr['attribute_name'], "GBK", "UTF-8" ); 
		}
		else
		{
			$attr_name = $policy_arr['attribute_name'];
		}
		if($attribute_type == 'KLWYLQX')
		{
			$node_fenhong = '<!-- 分红类型 -->
						     <DivType tc="1"/>';
						     //<PolicyPaymentType>ALIPAY</PolicyPaymentType> 
		}
		$applynum = $policy_arr['apply_num'];
		if($attribute_type == 'ijiankang')
		{
			$attr_code = explode('_', $attribute_code);
			$attribute_code = $attr_code[0];
			
		}
		if($attribute_type == 'ijiankang'
		|| $attribute_type == 'BWSJ')
		{
			$applynum = 1;
		}
		$data_holding = '
					<Holding id="Holding_1">
							<Policy>	
								<!-- 出单类型 1.自助  -->	
							  <ApplicantMode tc="1">auto</ApplicantMode> 
							  <!-- 收费状态  -->	
							  <FeeStatus tc="1">OVERDUE</FeeStatus>   
							  <!-- 产品代码  -->	       
								<ProductCode>'.$attribute_code.'</ProductCode>
								<!-- 单证号码 -->
			          		<ApplicationInfo>
										<!-- 投保单号 -->
										<HOAppFormNumber>'.$policy_arr['cast_policy_no'].'</HOAppFormNumber>
										<!-- 投保受理日期 -->
										<SubmissionDate>'.date('Y-m-d',$_SGLOBAL['timestamp']).'</SubmissionDate>
										<!-- 签单日期 -->
										<SignedDate>'.date('Y-m-d',$_SGLOBAL['timestamp']).'</SignedDate>
			          		</ApplicationInfo>
						<!-- 产品名称  -->
						<PlanName>'.$attr_name.'</PlanName>
						<!-- 生效日期 -->
					  <EffDate>'.$start[0].'</EffDate>
					  <!-- 失效日期 -->
					  <TermDate>'.$end[0].'</TermDate>
					  <!-- 签单日期 -->
					  <IssueDate>'.date('Y-m-d',$_SGLOBAL['timestamp']).'</IssueDate>
					  <Teller>3211023368</Teller>
					  <!-- 总保费 -->
					  <PaymentAmt>'.floatval($policy_arr['total_premium']).'</PaymentAmt>
					  <!-- 纸质保单邮寄方式 -->
					  <PolicyReceiveMode tc="2">MAIL</PolicyReceiveMode>
								<Life>
										<!--保单购买份数???-->
								     <FaceUnits>'.$applynum.'</FaceUnits>
								     '.$node_fenhong.'
								     <PolicyPaymentType>ALIPAY</PolicyPaymentType>
				           			'.$product_str.'
								</Life>
							</Policy>	
						</Holding>';
	}
	else//默认走短险
	{
		
		//add yes123 2015-06-15 计算生效日期和保障期间
		$start_date = date('Y-m-d', strtotime($policy_arr['start_date']));
		$end_date = date('Y-m-d', strtotime($policy_arr['end_date']));

		$end_day_num = diffBetweenTwoDays($start_date,$end_date);
		$end_day_num++;
		//mod by zhangxi, 20150703, 全年航空意外不同处理
		if($attribute_code == 'E-YEARHKBZ')
		{
			$end_day_num=1;//一年
		}
		
		$data_holding = '
				<Holding id="Holding_1">
				<Policy>	
					<!-- 出单类型 1.自助 2.远程 4.激活卡 -->
					<ApplicantMode tc="1">auto</ApplicantMode>
					<!-- 收费状态 -->
					<FeeStatus tc="1">OVERDUE</FeeStatus>
					<!-- 产品代码 -->	
					<ProductCode>'.$attribute_code.'</ProductCode>
					<!-- 产品名称 -->	
					<!--<PlanName>e时代全年航空保障计划</PlanName>-->					
					<!-- 保单号 应该是续保请求才有-->	
					<PolNumber></PolNumber>
					<!-- 生效日期 -->	
					<EffDate>'.$start_date.'</EffDate>
					<!-- 保障期间 -->	
					<BenefitPeriod>'.$end_day_num.'</BenefitPeriod>
          <!-- 总保费 -->
          <PaymentAmt>'.floatval($policy_arr['total_premium']).'</PaymentAmt>
          	<Teller>3211023368</Teller>
			<Life>
					 <!-- 购买份数 -->	
			     <FaceUnits>'.$policy_arr['apply_num'].'</FaceUnits>
			     <!-- 支付类型, 欠费承保没有此节点 -->	
			     <!--<PolicyPaymentType tc="0">ALIPAY</PolicyPaymentType>-->	
			     <!-- 产品档次（电商提供的枚举值，根据每个产品不同枚举值不同）可选，暂时不填 -->	
			    	 <ProductLevel tc="'.$policy_arr['product_code'].'">'.$attr_picclife_ProductLevel[$policy_arr['product_code']].'</ProductLevel>
			     <!-- 旅游类型:1出境旅游 2入境旅游 3国内旅游 如果产品支持旅游类型需要以下3个结点 -->
			    <!-- <TravelType tc="3">CISBORDER</TravelType>-->
			     <!-- 出发地 -->
			    <!-- <Departure>北京</Departure>-->
			     <!-- 目的地 -->
			     <!-- <Destination>上海,广东,海南</Destination>-->
			     <!-- 航班号 -->
			     <!-- <FlightNumber>GH000001</FlightNumber>-->
			     <!-- 起飞时间 yyyy-MM-dd HH:mm:ss -->					     
			     <!-- <DepartureTime>2012-11-13 10:02:30</DepartureTime>   --> 
			</Life>
          <ApplicationInfo>
            <!-- 投保单号 -->
            <!-- 如果核保操作，在承保时此结点必输 -->          
            <HOAppFormNumber></HOAppFormNumber>
            <!-- 投保受理日期 -->
            <SubmissionDate>'.date('Y-m-d',$_SGLOBAL['timestamp']).'</SubmissionDate>
            <!-- 签单日期 -->            
            <SignedDate>'.date('Y-m-d',$_SGLOBAL['timestamp']).'</SignedDate>
          </ApplicationInfo>					
				</Policy>
			</Holding>';
			
			
	}
	
			
			
	global $attr_picclife_GovtIDTC;
	global $attr_picclife_Gender;
	global $attr_picclife_RelationRoleCode;
			
	//party info,用户信息
	//投保人
	
	$applicant_cert_type = $user_info_applicant['certificates_type'];
	$applicant_gender = $user_info_applicant['gender'];
	if(0)//(defined('IS_NO_GBK'))
	{
		$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		$applicant_id_type =  mb_convert_encoding($attr_picclife_GovtIDTC[$applicant_cert_type], "GBK", "UTF-8" );
		$applicant_gender_type =  mb_convert_encoding($attr_picclife_Gender[$applicant_gender], "GBK", "UTF-8" );
	}
	else
	{
		
		$user_info_applicant_fullname =  $user_info_applicant['fullname']; //已知原编码为UTF-8, 转换为GBK
		$applicant_id_type =  $attr_picclife_GovtIDTC[$applicant_cert_type];
		$applicant_gender_type =  $attr_picclife_Gender[$applicant_gender];
		
	}
	
	
	
	
	//投保人信息
	//得获取职业名称描述信息
	$career_code = $user_info_applicant['occupationClassCode'];
	
	include_once (S_ROOT.'../oop/classes/picclife_Insurance.php');
	
	
	$picclife_obj = new picclife_Insurance();
	
	$career_desc = $picclife_obj->get_career_name_by_code($career_code);
	
	//暂时不用，写死为2
	$career_type = $picclife_obj->get_career_type_by_code($career_code);
	
	//通过职业代码获取职业类别

	$applicant_info = '
				<Party id="Party_applicant">
                <!-- 投保人姓名 --> 
                <FullName>'.$user_info_applicant_fullname.'</FullName>
                <!-- 投保人证件号码 --> 
                <GovtID>'.$user_info_applicant['certificates_code'].'</GovtID>
                <!-- 投保人证件类型 --> 
                <GovtIDTC tc="'.$applicant_cert_type.'">'.$applicant_id_type.'</GovtIDTC>
                <Salary>'.$user_info_applicant['income'].'</Salary>
                <Person>
                    <!-- 投保人性别 --> 
                    <Gender tc="'.$applicant_gender.'">'.$applicant_gender_type.'</Gender>
                    <!-- 投保人出生日期 --> 
                    <BirthDate>'.$user_info_applicant['birthday'].'</BirthDate>
                    <!-- 职业代码 -->
          			<OccupationType tc="2'.$user_info_applicant['occupationClassCode'].'">'.$career_type.$user_info_applicant['occupationClassCode'].'</OccupationType>
          			<!-- 职业描述 -->
          			<Occupation>'.$career_desc.'</Occupation>	
                </Person>
                <Address id="Address_1">
                    <Line1>'.$user_info_applicant['address'].'</Line1>
                    <Zip>'.$user_info_applicant['zipcode'].'</Zip>
                </Address>
                <Phone>
                    <!-- 电话类型 手机:12;其他:1; -->
                    <PhoneTypeCode tc="12">12</PhoneTypeCode>
                    <DialNumber>'.$user_info_applicant['mobiletelephone'].'</DialNumber>
                    <Email>'.$user_info_applicant['email'].'</Email>
                </Phone>
            </Party>';
	
	
	
	foreach($list_subject AS $key =>$value)
	{
		//被保险人信息列表
		$list_subject_insurant = $value['list_subject_insurant'] ;
		
		$insurant_info='';
		$beneficiary = '';//受益人数据
		//被保险人,暂时不考虑多个被保险人????
		$index = 1;
		foreach($list_subject_insurant AS $key_insurant =>$value_insurant )
		{
			$insurant_cert_type = $value_insurant['certificates_type'];
			$insurant_gender = $value_insurant['gender'];
			if(0)//(defined('IS_NO_GBK'))
			{
				$user_info_assured_fullname =  mb_convert_encoding($value_insurant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
				//证件类型转换
				$id_type = mb_convert_encoding($attr_picclife_GovtIDTC[$insurant_cert_type],"GBK", "UTF-8");
				//性别
				$gender_type = mb_convert_encoding($attr_picclife_Gender[$insurant_gender],"GBK", "UTF-8");
			}
			else
			{
				$user_info_assured_fullname =  $value_insurant['fullname']; //已知原编码为UTF-8, 转换为GBK
				//证件类型转换
				$id_type = $attr_picclife_GovtIDTC[$insurant_cert_type];
				//性别
				$gender_type = $attr_picclife_Gender[$insurant_gender];
			}
			
			$picclife_obj = new picclife_Insurance();
			//用投保人的
			//$insurant_career_desc = $picclife_obj->get_career_name_by_code($value_insurant['occupationClassCode']);
			$insurant_career_desc = $picclife_obj->get_career_name_by_code($user_info_applicant['occupationClassCode']);
			//暂时不用，写死为2
			$insurant_career_type = $picclife_obj->get_career_type_by_code($value_insurant['occupationClassCode']);
			$insurant_info = $insurant_info.'
								<Party id="Party_insurant_'.$index.'">
								<!-- 被保人姓名 --> 
								<FullName>'.$user_info_assured_fullname.'</FullName>
								<!-- 被保人证件号码 --> 
								<GovtID>'.$value_insurant['certificates_code'].'</GovtID>
								<!-- 被保人证件类型 --> 
								<GovtIDTC tc="'.$insurant_cert_type.'">'.$id_type.'</GovtIDTC>
								<Salary>'.$value_insurant['income'].'</Salary>
								<Person>
									<!-- 被保人性别 --> 
									<Gender tc="'.$insurant_gender.'">'.$gender_type.'</Gender>
									<!-- 被保人出生日期 --> 
									<BirthDate>'.$value_insurant['birthday'].'</BirthDate>
									<!-- 职业代码 ,用投保人的-->
				          			<OccupationType tc="2'.$user_info_applicant['occupationClassCode'].'">'.$insurant_career_type.$user_info_applicant['occupationClassCode'].'</OccupationType>
				          			<!-- 职业描述 -->
				          			<Occupation>'.$insurant_career_desc.'</Occupation>
								</Person>
				                <Address id="Address_1">
				                    <Line1>'.$user_info_applicant['address'].'</Line1>
				                    <Zip>'.$user_info_applicant['zipcode'].'</Zip>
				                </Address>
								<Phone>
									<!-- 电话类型 手机:12;其他:1; -->
									<PhoneTypeCode tc="12">12</PhoneTypeCode>
									<DialNumber>'.$value_insurant['mobiletelephone'].'</DialNumber>
									<Email>'.$value_insurant['email'].'</Email>
								</Phone>
							</Party>';
				
				
				//受益人
				
				//不是法定的情况下，指定了受益人的情况
				if($policy_arr['beneficiary'] == 0)
				{
					//受益人信息需要查询处理
					$uid = $value_insurant['uid'];
					$wheresql = "uid=$uid AND policy_subject_id='$policy_subject_id'";
					$sql = "SELECT * FROM ".tname('insurance_policy_beneficiary_info')." WHERE $wheresql";
					$query = $_SGLOBAL['db']->query($sql);
					$benefitDTOList = array();
					$ben_index = 1;
					while ($one_beneficiary_info = $_SGLOBAL['db']->fetch_array($query))
					{
						$CARD_TYPE = $one_beneficiary_info['cardType'];
						$SEX = $one_beneficiary_info['sex'];
						
						if(0)//(defined('IS_NO_GBK'))
						{
							
							$beneficiary_fullname =  mb_convert_encoding($one_beneficiary_info['name'], "GBK", "UTF-8" ); 
							$ben_id_type =  mb_convert_encoding($attr_picclife_GovtIDTC[$CARD_TYPE], "GBK", "UTF-8" ); 
							$gender_id_type =  mb_convert_encoding($attr_picclife_Gender[$SEX], "GBK", "UTF-8" );
						}
						else
						{
							$beneficiary_fullname =  $one_beneficiary_info['name']; 
							$ben_id_type =  $attr_picclife_GovtIDTC[$CARD_TYPE]; 
							$gender_id_type =  $attr_picclife_Gender[$SEX];
						}
						$beneficiary =$beneficiary.'
													<!-- 受益人一 -->
											      <Party id="beneficiary_'.$ben_index.'">
											        <!-- 受益人姓名 -->      
											        <FullName>'.$beneficiary_fullname.'</FullName>
											        <!-- 受益人证件号码 -->        
											        <GovtID>'.$one_beneficiary_info['cardNo'].'</GovtID>
											        <!-- 受益人证件类型 -->        
											        <GovtIDTC tc="'.$one_beneficiary_info['cardType'].'">'.$ben_id_type.'</GovtIDTC>
											        <Person>
											          <!-- 受益人性别 -->        
											          <Gender tc="'.$one_beneficiary_info['sex'].'">'.$gender_id_type.'</Gender>
											          <!-- 受益人出生日期 -->
											          <BirthDate>'.$one_beneficiary_info['birthday'].'</BirthDate>
											        </Person>
											      </Party>';
						//本受益人与权益关系
						$beneficiary = $beneficiary.'
													<Relation OriginatingObjectID="Holding_1" RelatedObjectID="beneficiary_'.$ben_index.'" id="Relation_Holding_1_with_beneficiary_'.$ben_index.'">
												        <OriginatingObjectType tc="4">Holding</OriginatingObjectType>
												        <RelatedObjectType tc="6">Party</RelatedObjectType>
												        <!-- 受益人关系 -->
												        <RelationRoleCode tc="34">Beneficiary</RelationRoleCode>
												        <!-- 受益百分数 -->        
												        <InterestPercent>'.(100*$one_beneficiary_info['benefitScale']).'</InterestPercent>
												        <!-- 顺序 -->        
												        <Sequence>'.$one_beneficiary_info['benefitSort'].'</Sequence>
												      </Relation> ';
						$relatioin_ben_with_insurant = $one_beneficiary_info['benefitRelation'];
						if(0)//(defined('IS_NO_GBK'))
						{
							
							
							$relation_role_code =  mb_convert_encoding($attr_picclife_RelationRoleCode[$relatioin_ben_with_insurant], "GBK", "UTF-8" ); 
						}
						else
						{
							$relation_role_code =  $attr_picclife_RelationRoleCode[$relatioin_ben_with_insurant];
						}
						//本受益人与被保险人关系
						$beneficiary = $beneficiary.' 
													 <Relation OriginatingObjectID="Party_insurant_'.$index.'" RelatedObjectID="beneficiary_'.$ben_index.'" id="Relation_Party_insurant_'.$index.'with_beneficiary_'.$ben_index.'">
												        <OriginatingObjectType tc="6">Party</OriginatingObjectType>
												        <RelatedObjectType tc="6">Party</RelatedObjectType>
												        <!-- 受益人与被保人关系 -->
												        <RelationRoleCode tc="'.$relatioin_ben_with_insurant.'">'.$relation_role_code.'</RelationRoleCode>
												      </Relation>';
						
						
						
						$ben_index++;
						
					}
				}	
				
				$index ++;
		}
		
		
	}
	
	
	
	
	//未成人告知信息
//	<FormInstance id="form1" ReceiverPartyID="Party_2">
//            <!-- 被保人（未成人）告知项，每个被保人对应一个FormInstance对象 -->
//                <!--表单类型 1:告知表单 2:发票 -->
//              <DocumentControlType>1</DocumentControlType>
//              <!--表单印刷号 -->
//        			<DocumentControlNumber>123456</DocumentControlNumber>
//        			<!-- 如果同一个被保人有多个告知项，则有多个FormResponse对象 -->
//              <FormResponse id="form2_resp_1">                
//                    <!-- 问题编号 -->
//					          <QuestionNumber>926</QuestionNumber>
//					          <!-- 问题文本 -->
//					          <QuestionText>被保险人是否已拥有或正在申请本公司或其他保险公司的以死亡为给付保险金条件的人身保险?</QuestionText>          
//					          <!-- 答案代码(多个答案用逗号分隔) -->
//					          <ResponseCode>Y</ResponseCode>
//					          <!-- 答案文本(多个答案用逗号分隔,多行用分号分隔)-->
//					          <!-- 格式为:投保公司,投保公司名称,投保日期,保单生效日期,投保险种名称,身故保险金额(元) -->
//					          <!-- 投保公司：0人保寿险 1外公司 -->
//					          <!-- 必填项:投保公司,投保险种名称,身故保险金额(元) -->   
//					          <ResponseText>1,中国人寿,2011-06-06,2011-06-07,公共交通意外,0</ResponseText>   
//       		 		</FormResponse>
//            </FormInstance>	

	//关系
	//权益与投保人关系
	$relationship_hold_with_applicant = '
									<Relation OriginatingObjectID="Holding_1" RelatedObjectID="Party_applicant" id="Relation_1">
										<OriginatingObjectType tc="4">Holding</OriginatingObjectType>
										<RelatedObjectType tc="6">Party</RelatedObjectType>
										<RelationRoleCode tc="8">Owner</RelationRoleCode>
									</Relation>';
	
	
	
	//权益与被保险人关系
	$relationship_hold_with_insurant = '
										<Relation OriginatingObjectID="Holding_1" RelatedObjectID="Party_insurant_1" id="Relation_2">
											<OriginatingObjectType tc="4">Holding</OriginatingObjectType>
											<RelatedObjectType tc="6">Party</RelatedObjectType>
											<RelationRoleCode tc="32">Insured</RelationRoleCode>
										</Relation>';
	
	//被保险人与投保人的关系
	$relationship = $policy_arr['relationship_with_insured'];
	if(0)//(defined('IS_NO_GBK'))
	{
		
		$relation_name =  mb_convert_encoding($attr_picclife_RelationRoleCode[$relationship], "GBK", "UTF-8" ); 
		
	}
	else
	{
		$relation_name = $attr_picclife_RelationRoleCode[$relationship];
	}
	$relationship_insurant_with_applicant = '
											<Relation OriginatingObjectID="Party_applicant" RelatedObjectID="Party_insurant_1" id="Relation_3">
											<OriginatingObjectType tc="6">Party</OriginatingObjectType>
											<RelatedObjectType tc="6">Party</RelatedObjectType>
											<!-- 与投保人关系 -->
											<RelationRoleCode tc="'.$relationship.'">'.$relation_name.'</RelationRoleCode>
										</Relation>';
	
	//comment by zhangxi, 20150520, 奇葩，关系节点必须在人物节点前面
	
	
	$olife_info = '
			<OLifE>'.$data_holding.$relationship_hold_with_applicant.$relationship_hold_with_insurant.$relationship_insurant_with_applicant.$applicant_info.$insurant_info.$beneficiary.'
			</OLifE>';
	
	return $olife_info;
}
function gen_tran_olife_extension($user_info_applicant)
{
	$olife_ext = '
		<OLifEExtension VendorCode="1">
            <CarrierCode>PICC</CarrierCode>
            <!-- 机构代码/同时代表客户选择的投保地区 -->
            <Branch>1110000</Branch>
       			<!-- 中介代码 -->
       			<AgencyCode></AgencyCode>
            <!-- 合作伙伴代码 -->
            <BankCode>ebaoins</BankCode>
        </OLifEExtension>';
    return $olife_ext;
}
function gen_picclife_policy_xml($attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject,
									$transmode=1)//默认为核保请求
{
	
	$common_info = gen_trans_common_info($policy_arr, $transmode);
	
	$olife = gen_trans_olife($policy_arr,$user_info_applicant,$list_subject,$attribute_code,$attribute_type);
	
	$olife_extension = gen_tran_olife_extension($user_info_applicant);
	
	$xml = "<?xml version='1.0' encoding='GBK'?>
			<TXLife>
			<TXLifeRequest>
			" .
			$common_info.
			$olife.
			$olife_extension.
			"
			</TXLifeRequest>
			</TXLife>";
			
	ss_log(__FUNCTION__.",返回报文");		
	return $xml;
}



function process_return_xml_data_picclife(
									 	$policy_id,
				                      	$orderNum,
										$order_sn,
										$return_data,
										$user_info_applicant,
										$obj_java = NULL,
										$flag_policy_check_only = 0 
										)
{
	global $_SGLOBAL;
	
	ss_log("into function: ".__FUNCTION__);
	////////////////////////////////////////////////////////////////////////////
	ss_log(__FUNCTION__.", return org data: ".$return_data);
	
	$arr_result = parse_xml_data_to_array_picclife($return_data);

	//ss_log(__FUNCTION__.", arr_result=".var_export($arr_result,true));
	if($arr_result['TransMode_tc'] == "1"//核保处理

	  )
	{
		if($arr_result['ResultCode_tc'] == 1 )//核保成功
		{
			ss_log(__FUNCTION__.", picclife核保成功");
			ss_log(__FUNCTION__.", return code: ".$return_data['rspCode']." order_sn: ".$order_sn);
			ss_log(__FUNCTION__.", order_sn: ".$order_sn);
			
			
			//投保单号需要存储起来先
			$setarr = array(
						'cast_policy_no'=>$arr_result['HOAppFormNumber']);
			updatetable(	'insurance_policy',
							$setarr,
							array('policy_id'=>$policy_id)
							);
			ss_log(__FUNCTION__.", policy_id=".$policy_id.", return_data=".var_export($return_data, true));
			//仅仅是进行核保就ok了，就不往下了
			if($flag_policy_check_only == 1)
			{
				$result_attr = array('retcode'=>0,
									'retmsg'=> $arr_result['ResultInfoDesc'],
									'cast_policy_no'=>$arr_result['HOAppFormNumber']//放的是投保单号
							);
				return $result_attr;
			}
			else
			{
				//这里需要将投保单号更新到数据库中吗？？？只是核保产生的投保单就没有必要
				//是否应该接着进行投保动作呢?要区分是同步还是异步
				//同步的投保处理,根据核保产生的响应报文，再生成投保报文
				$ret = get_policy_info($policy_id);//得到保单信息
		
				ss_log(__FUNCTION__.", 核保成功，准备进行投保 !");
				
				$policy_attr = $ret['policy_arr'];
				$user_info_applicant = $ret['user_info_applicant'];
				$list_subject = $ret['list_subject'];//多层级一个链表
				/////////////////////////////////////////////////////////////////////////
				$insurer_code = $policy_attr['insurer_code'];
				$attribute_id = $policy_attr['attribute_id'];
	
				$wheresql = "attribute_id='$attribute_id'";
				$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql LIMIT 1";
				$query = $_SGLOBAL['db']->query($sql);
				$product_attribute_arr = $_SGLOBAL['db']->fetch_array($query);
			
				$insurer_code = $product_attribute_arr['insurer_code'];
				$attribute_type = $product_attribute_arr['attribute_type'];
				$attribute_code = $product_attribute_arr['attribute_code'];
				//生成投保报文
				$return_data_xml = gen_picclife_policy_xml($attribute_type,
															$attribute_code,
															$policy_attr,//保单相关信息
															$user_info_applicant,//投保人信息
															$list_subject,
															2);
				//进行投保操作
				return post_accept_policy_picclife($policy_id, $return_data_xml, $obj_java );
			}
			

				
		}
		else
		{
			//核保失败的返回处理
			if($flag_policy_check_only == 1)
			{
				$result_attr = array('retcode'=>$arr_result['ResultCode_tc'],
									'retmsg'=> $arr_result['ResultInfoDesc']
							);
				return $result_attr;
			}
			
			$result = $arr_result['ResultCode_tc'];
			$retmsg = $arr_result['ResultInfoDesc'];
			//核保失败处理
			ss_log(__FUNCTION__."[".__LINE__."], 承保失败，result=".$result);
		}
		
		
	}//
	elseif($arr_result['TransMode_tc'] == "2"//承保的返回处理
	  )
	{
		ss_log(__FUNCTION__.", 承保处理, arr_result=".var_export($arr_result,true));
		if($arr_result['ResultCode_tc'] == 1 )//承保成功
		{
			ss_log(__FUNCTION__.", picclife承保成功");
			ss_log(__FUNCTION__.", return code: ".$arr_result['ResultCode_tc']." ordernum: ".$orderNum.", order_sn: ".$order_sn);
			$result = 0;
			
			
			//获取电子保单的url地址，获取保单号
			$policy_no = $arr_result['PolNumber'];
			//mod by zhangxi, 20150528, url地址要将转义符转回来
			$arr_result['PolicyUrl'] = preg_replace('/&amp;/','&',$arr_result['PolicyUrl']);
			
			$policy_file = $arr_result['PolicyUrl'];
			ss_log(__FUNCTION__.", policy_no=".$policy_no.", policy_file=".$policy_file);
			//status,保单的状态：0 保存但是未提交；1 投保成功；2 注销了，注销后不能再次提交。
			$wheresql = "order_num='$orderNum'";//根据这个找到其对应的保单存放的数据
			$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$policy_attr = $_SGLOBAL['db']->fetch_array($query);
		
			if($policy_attr['policy_id'])//old
			{
				$policy_id = $policy_attr['policy_id'];
				ss_log(__FUNCTION__.", return policyNo: ".$return_data['policyNo']." order_sn: ".$order_sn);
				
				//在这里更改该保单状态,存储保险公司返回的保单号，同时保存电子保单url到数据库中
				updatetable('insurance_policy',
								array('policy_status'=>'insured','policy_no'=>$policy_no, 'policy_file'=>$policy_file),
								array('policy_id'=>$policy_id)
								);
				//start add by wangcya, 20150325,投保成功，增加对应的订单上的投保成功的个数///////////////////
				$sql = "UPDATE bx_order_info SET insured_policy_num=insured_policy_num+1 WHERE order_sn=".$order_sn;
				ss_log(__FUNCTION__.", ".$sql);
				$_SGLOBAL['db']->query($sql);
				//end add by wangcya, 20150325,投保成功，增加对应的订单上的投保成功的个数///////////////////
				///////////////////////////////////////////////////////////////
				$result = 0;
				$retmsg = "post policy success!";
		
			}
			else
			{
				$result = 120;
				$retmsg = "post policy success,but not find policy by order num:".$orderNum;
				ss_log(__FUNCTION__.", order_sn: ".$order_sn);
				ss_log(__FUNCTION__.", ".$retmsg);
				//comment by zhangxi, 20150206, 这里是否应该返回？毕竟是异常，就不要进行取保单操作了
				//should add code here
			}
		
			ss_log(__FUNCTION__." policy_id: ".$policy_id);
			//$policy_attr['policy_status'] = 'insured';//更改投保成功标示
			$policy_attr['policy_no'] = $policy_no;
			$policy_attr['policy_file'] = $policy_file;
			
			$pdf_filename = S_ROOT."xml/message/".$policy_no."_picclife_policy.pdf";
			
			$policy_attr['readfile'] = false;//
			//好像异步下载电子保单不好使啊！！！！
			if(!defined('USE_ASYN_JAVA'))//同步
			{
				$use_asyn = false;
			}
			else//异步
			{
				$use_asyn = true;
			}
			
			$result_attr = get_policy_file_picclife($use_asyn,
													 $policy_attr,
													  $pdf_filename,
													  $obj_java);
			
		}
		else//承保失败
		{
			ss_log(__FUNCTION__.", 承保失败，ret_flag=".$arr_result['ResultCode_tc']);
		}
	}
	else
	{
		//picclife投保返回异常
		$result = $arr_result['ResultCode_tc'];
		$retmsg = $arr_result['ResultInfoDesc'];
		ss_log(__FUNCTION__."，post gen policy fail! rspCode=".$result.", return message: ".$retmsg);
		ss_log(__FUNCTION__."，before tranfer, 将要保存保单的返回信息！".$retmsg );
	}
	//echo $strxml;	
	
	$result_attr = array('retcode'=>$result,
			'retmsg'=> $retmsg,
			'policy_no'=>$return_data['policyNo']//add by wangcya , 20150107, 一定要把这个电子保单号返回
	);
	
	return $result_attr;
	
}

function withdraw_policy_picclife($policy_id,$user_info_applicant)
{
	global $_SGLOBAL;
	/////////////////////////////////////////////////////
	if($policy_id)
	{
		$wheresql = "policy_id='$policy_id'";
			
		$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
		
		ss_log("in fun:".__FUNCTION__.":".$sql);
		
		$query = $_SGLOBAL['db']->query($sql);
		$policy_attr = $_SGLOBAL['db']->fetch_array($query);
	}
	
	/////////////////////////////////////////////////////
	if(!empty($policy_attr))
	{
		if($policy_attr['attribute_type'] == 'ijiankang'
		||$policy_attr['attribute_type'] == 'BWSJ'
		||$policy_attr['attribute_type'] == 'KLWYLQX'
		||$policy_attr['attribute_type'] == 'TTL')
		{
			//寿险不允许在线注销
			return $result_attr = array(	'retcode'=>"110",
						'retmsg'=> "该产品不支持在线注销操作，需线下进行注销！");
			
		}
		else if($policy_attr['attribute_type'] == 'product')
		{
			$result_attr = post_withdraw_policy_picclife($policy_attr, $user_info_applicant );
		}
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

function post_withdraw_policy_picclife($policy_attr, $user_info_applicant)
{
	
	global $_SGLOBAL, $java_class_name_picclife;
	global $LOGFILE_PICCLIFE;
	
	//////////////////////////////////////////////////////
	$policy_id = $policy_attr['policy_id'];
	
	$POLICYNO 		= $policy_attr['policy_no'];
	$orderNum 		= $policy_attr['order_num'];
	$order_sn 		= $policy_attr['order_sn'];
	
	ss_log(__FUNCTION__.", will withdraw policy , POLICYNO: ".$POLICYNO." orderNum: ".$orderNum);
	ss_log(__FUNCTION__.", order_sn: ".$order_sn);
	$obj_java = create_java_obj($java_class_name_picclife);
	if(!$obj_java)
	{
		ss_log(__FUNCTION__.", error,  create java object error,name=".$java_class_name_picclife);
	}
	
	
	$DATE = date('Y-m-d',$_SGLOBAL['timestamp']);//2014-05-15
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30

 	$transSn = getRandOnly_Id(0);

	$result = 1;
	//保单注销报文
	$strxml = '<?xml version="1.0" encoding="GBK"?>
			<TXLife>
			  	<TXLifeRequest>
			  			<!-- 交易流水号(可选)-->
					<TransRefGUID>'.$transSn.'</TransRefGUID>
					<!-- 交易代码(固定) -->
					<TransType tc="119">EBSCBS_NB_CA_0001</TransType>
					<!-- 系统当前日期(必填) -->		
					<TransExeDate>'.$DATE.'</TransExeDate>
					<!-- 系统当前时间(必填) -->
					<TransExeTime>'.$TIME.'</TransExeTime>
					
					<OLifE>
				       <Holding id="Holding_1">
				           <Policy>
				           		 <!-- 保单号 (必填) -->
				               <PolNumber>'.$POLICYNO.'</PolNumber>                  
				           </Policy>
				        </Holding>
				    </OLifE>
				    		
					<OLifEExtension VendorCode="1">
				    <!-- 保险公司代码 (固定)-->
						<CarrierCode>PICC</CarrierCode>
						<!-- 网点代码 (可选)-->
						<Branch></Branch>
						<!-- 中介代码 (可选)-->
						<AgencyCode></AgencyCode>
						<!-- 交易伙伴代码 -->
						<BankCode>ebaoins</BankCode>
					</OLifEExtension>
				</TXLifeRequest>
			</TXLife>';

	if(!empty($strxml))
	{
		$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_picclife_policy_withdraw.xml";
		file_put_contents($af_path,$strxml);
		
		//start add by wangcya, 20150209,把携带序列号的也保存起来
		$UUID = $orderNum;
		$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_picclife_policy_withdraw.json";
		file_put_contents($af_path_serial,$strxml);
		//end add by wangcya, 20150209,把携带序列号的也保存起来
		if(!$obj_java)
		{
			$p = create_java_obj($java_class_name_picclife);
		}
		else
		{
			$p = $obj_java;
		}
		
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
		
		$url = URL_PICCLIFE_CANCEL_POLICY;
		$respXmlFileName = "";
		ss_log(__FUNCTION__."picclife, withdraw url: ".URL_PICCLIFE_CANCEL_POLICY);
		//ss_log("keyFile: ".$keyFile);
		
		$return_data = (string)$p->cancel( 
											$url , 
											$strxml ,
											"",
											"",
											PICCLIFE_SECRET_KEY,
											$LOGFILE_PICCLIFE
											);

		ss_log("after picclife withdraw call function sendMessage");
		
		if(!empty($return_data))
		{
			
			//先保存一份
			$return_path = S_ROOT."xml/".$order_sn."_".$policy_id."_picclife_policy_withdraw_ret.xml";
			file_put_contents($return_path,$return_data);

			//////////////////////////////////////////////////////////
		}
		else
		{
			$result = 110;
			$retmsg = "post picclife withdraw policy return is null!";
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
			return $result_attr;
		}

		/////////先解析出来返回的xml//
		$withdraw_ret = parse_withdraw_xml_data_to_array_picclife($return_data);

		//临时，接口有问题
		if( $withdraw_ret['ResultCode_tc'] ==1 &&////注销保单成功
				$withdraw_ret['PolicyNo'] == $POLICYNO
		)
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
			if(empty($withdraw_ret))
			{	
				$result = 110;
				$retmsg = "epicc返回有节点，但是节点内部无内容";
				
				ss_log(__FUNCTION__.", result：".$result.", 解析返回报文错误！" );
				
				$result_attr = array(	'retcode'=>$result,
										'retmsg'=> $retmsg
										);
			}
			else
			{

				$retmsg = "withdraw fail!,: ".$withdraw_ret['ResponseMessage'];
				ss_log(__FUNCTION__.", ".$retmsg);
				$result_attr = array(	'retcode'=>$withdraw_ret['ResponseCode'],
						'retmsg'=> $retmsg
						);
					
			}
			
	
		}
	}
	else
	{
		$result = 110;
		$retmsg = "生成注销XML报文错误";
		ss_log(__FUNCTION__.", ".$retmsg);
		//////////////////////////////////////////////////////////////////////
			
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
	}

	return $result_attr;
	
	
}

function parse_withdraw_xml_data_to_array_picclife($return_data)
{
	ss_log(__FUNCTION__.", return_data=".$return_data);
	ss_log(__FUNCTION__.", get in");
	$policy_ret = array();

	preg_match_all('/<PolNumber>(.*)<\/PolNumber>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['PolicyNo'] = trim($arr[1][0]);
	}

	preg_match_all('/<ResultInfoDesc>(.*)<\/ResultInfoDesc>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['ResultInfoDesc'] = trim($arr[1][0]);
	}
	
	//获取TC
	preg_match_all('/<ResultCode tc="(.*)"[ ]*\/>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['ResultCode_tc'] = trim($arr[1][0]);
	}

	ss_log(__FUNCTION__.", policy_ret=".var_export($policy_ret, true));
	return $policy_ret;
}



//获取电子保单函数
function get_policy_file_picclife($use_asyn,
			                       $policy_attr,
			                       $pdf_filename,
			                       $obj_java = NULL
			                      )
{
	global $java_class_name_picclife;
	ss_log("into fuction:".__FUNCTION__);
	////////////////////////////////////////////////////////////////////
	$result = 110;
	$retmsg = "";
	/////////////////////////////////////////////////////////////////
	if(!empty($policy_attr))
	{
		$POLICYNO 		= $policy_attr['policy_no'];
		$orderNum 		= $policy_attr['order_num'];//现在用保单号来存放电子保单
		$order_sn 		= $policy_attr['order_sn'];//现在用保单号来存放电子保单
		$policy_no		= $policy_attr['policy_no'];//add by wangcya, 20141119
		$business_type 	= $policy_attr['business_type'];
		$policy_id 		= $policy_attr['policy_id'];
	}
	else
	{
		$result = 110;
		$retmsg = "get policy file fail, policy_attr is null , policy_id:".$policy_id;
		ss_log(__FUNCTION__.", ".$retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
	
		return $result_attr;
	}
	
	//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	if(!$use_asyn)//同步处理接口
	{
		$java_class_name = $java_class_name_picclife;
	}
	else//异步接口
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_picclife:$java_class_name;
	
	ss_log(__FUNCTION__.",java_class_name: ".$java_class_name);
	if(!isset($obj_java))
	{
		$obj_java = create_java_obj($java_class_name);
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
	$logFileName = S_ROOT."xml/log/picclife_download_policyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	
	//需要根据承保接口获取到的url来获取保单下载的url
	$url = $policy_attr['policy_file'];
	ss_log(__FUNCTION__.",policy get url: ".$url);

	if(!$use_asyn)//同步的分支
	{
		
		ss_log(__FUNCTION__.",picclife will get policy file, POLICYNO: ".$POLICYNO." orderNum: ".$orderNum);
		ss_log(__FUNCTION__.",picclife order_sn: ".$order_sn);
		
		$ret = (string)$obj_java->downloadPolicy( 	$url,
													$pdf_filename,
													$logFileName
												);
	
		ss_log("applyElectricPolicyBill ret: ".$ret);
	
		if($ret==true)//下载电子保单，成功返回true，失败返回false
		{
			$result = 0;
			$retmsg = "获取电子保单成功";
		
			usleep(1);//wait for file cache,如果去掉这句话将得到的PDF不完整
			if (file_exists($pdf_filename))//如果这个文件已经存在，则返回给用户。
			{
				/////////////////////////////////////////////////////////
				//$policy_id = $policy_attr['policy_id'];
				$readfile = $policy_attr['readfile'];
				
				$result = 0;
				$retmsg = "获取电子保单成功";
			
				ss_log($retmsg);

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
		

			////////////////////////////////////////////////////
		}
		else
		{
			$result = 110;
			$retmsg = "获取电子保单失败了";
		}
	
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
	
		return $result_attr;
	}
	else//异步的分支
	{
		//////////////////////////////////////////
	
		ss_log(__FUNCTION__.", 将要发送获取电子保单的异步请求，will doService");
	
		$insurer_code = $policy_attr['insurer_code'];
		$callBackURL = CALLBACK_URL;
		$type = "getpolicyfile";
	
		ss_log(__FUNCTION__.", policy_id: ".$policy_id.", type: ".$type.", insurer_code: ".$insurer_code);
		ss_log(__FUNCTION__.", callBackURL: ".$callBackURL);
		//////////////////////////////////////////////////////////////////////////
	
		ss_log(__FUNCTION__.",logFileName: ".$logFileName);
		ss_log(__FUNCTION__.", url: ".$url);
	
		$param_other = array(
				"url"=>$url ,//投保保险公司服务地址
				"pdfFileName"=>$pdf_filename ,//pdf保单文件保存完全路径
				"logFileName"=>$logFileName, //日志文件
		);
	
		$jsonStr = json_encode($param_other);
	
		ss_log(__FUNCTION__.",param_other: ".var_export($param_other,true));
	
		///////////////////////////////////////////////////////////////////////////
		$successAccept = (string)$obj_java->doService(
													$insurer_code,
													$type,//类型
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
	}
	
	return $result_attr;
	
}


//获取pdf格式的电子保单
function get_policyfile_picclife($pdf_filename,
		                      $policy_attr,
		                      $user_info_applicant
		                    )
{

	$use_asyn = false;//同步方式
	$result_attr = get_policy_file_picclife($use_asyn,$policy_attr,$pdf_filename);
	return $result_attr;
}



//发送投保请求
function post_accept_policy_picclife($policy_id,
									 $return_data_xml, //承保报文
									 $obj_java= NULL)
{

	global $_SGLOBAL,$java_class_name_picclife;
	///////////////////////////////////////////////////////////////////////

	ss_log("into function： ".__FUNCTION__);
	$result = 1;
	$retmsg = "";
	//////////////////////////////////////////////////////////////////////
	$wheresql = "policy_id='$policy_id'";//根据这个找到其对应的保单存放的数据
	$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$policy_arr = $_SGLOBAL['db']->fetch_array($query);
	if(!$policy_arr['policy_id'])
	{
		//在数据库中没找到对应的投保单
		$retcode = 110;
		$retmsg = "在数据库中没找到对应的投保单! policy_id: ".$policy_id;
		ss_log($retmsg);
		
		$result_attr = array('retcode'=>$retcode,
				'retmsg'=> $retmsg
		);
		
		return $result_attr;
	}
	//////////////////////////////////////////////////////////////////////
	$order_sn = $policy_arr['order_sn'];
	///////////////////////////////////////////////////
	//生成承保报文

	if($return_data_xml)
	{
		$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_picclife_policy_post_accept.xml";
		file_put_contents($af_path,$return_data_xml);
		
		//start add by wangcya, 20150209,把携带序列号的也保存起来
		$FILE_UUID = getRandOnly_Id(0,1);
		$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_picclife_policy_post_accept.xml";
		file_put_contents($af_path_serial, $return_data_xml);
		//end add by wangcya, 20150209,把携带序列号的也保存起来
	}
	else
	{
		$retcode = 110;
		$retmsg = "picclife accept strjson_post_policy_content is null!";
		ss_log($retmsg);
			
		$result_attr = array('retcode'=>$retcode,
				'retmsg'=> $retmsg
		);
			
		return $result_attr;
	}
	
	//进行承保的处理
	$url = URL_PICCLIFE_POST_POLICY;
	$secretKey = PICCLIFE_SECRET_KEY;
	ss_log(__FUNCTION__.", post picclife url= ".$url);
	
	////////////////////////////////////////////////////////
	//isDev			是否输出日志文件
	$logFilePath =  S_ROOT."xml/log/".$order_sn."_picclife_policy_accept.log";
	
	////////////////////////////////////////////////////////////////////
	if(!defined('USE_ASYN_JAVA'))
	{
		$java_class_name = $java_class_name_picclife;
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_picclife:$java_class_name;
	
	if(!isset($obj_java))
	{
		$obj_java = create_java_obj($java_class_name);	
	}
	////////////////////////////////////////////////////////////////////
	$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_picclife_policy_post_accept_ret.xml";
	
	////////////////////////////////////////////////////////////////////
	if(!defined('USE_ASYN_JAVA'))//同步处理
	{
		//进行承保的工作
		$secretKey="ePb#I&aCoCins";
		ss_log(__FUNCTION__.", picclife will java insure");
		$return_data = $obj_java->insure( 	$url ,
											$return_data_xml,
											"",
											"",
											$secretKey,
											$logFilePath
										);
		ss_log(__FUNCTION__.", before picclife accept , underWriting, before".$return_data);
		
		$return_data = (string)$return_data;
		
		ss_log(__FUNCTION__.", before picclife accept , underWriting, after".$return_data);
		
		/////////////////////////////////////////////////////////////
		if(!empty($return_data))
		{
			
			file_put_contents($ret_af_path,$return_data);
			$result_attr = process_return_xml_data_picclife(
															$policy_id,
															$order_sn,
															$return_data,
															$obj_java
													);
		}
		else
		{
			$retcode = 110;
			$retmsg = "accept policy,result_attr return date is null!";
			ss_log($retmsg);
			
			$result_attr = array('retcode'=>$retcode,
					'retmsg'=> $retmsg
			);
			
			return $result_attr;
		}
	}
	else//异步处理
	{
		ss_log("before use asyn,doService");
			
		$respJsonFileName = $ret_af_path;
		$logFileName = S_ROOT."xml/log/picclife_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
			
		ss_log("logFileName: ".$logFileName);
			
		$insurer_code = $policy_arr['insurer_code'];
		ss_log("insurer_code: ".$insurer_code);
		$callBackURL = CALLBACK_URL;
		ss_log("callBackURL: ".$callBackURL);
		$type = "insure_accept";//这个承保要区分开
		ss_log("type: ".$type);

		$param_other = array(
						"url"=>$url ,//投保保险公司服务地址
						"xmlContent"=>"" ,//核保请求报文
						"postXmlFileName"=>$af_path ,//投保报文内容
						"postXmlFileEncoding"=>"UTF-8",//投保报文文件
						"Key"=>$secretKey ,//投保报文文件编码格式
						"respXmlFileName"=>$ret_af_path,//返回报文保存全路径
						"respXmlFileEncoding"=>"UTF-8",
						"logFileName"=>$logFileName//日志文件
					);

			
		ss_log("json param_other: ".var_export($param_other,true));
			
		$jsonStr = json_encode($param_other);
			
		ss_log(__FUNCTION__.", 将要发送承保的异步请求，will doService");
		$successAccept = (string)$obj_java->doService(
														$insurer_code,
														$type,
														$policy_id,//keyid
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
	////////////////////////////////////////////////////////////////////////////
	return $result_attr;
}

//人保寿险投保
function post_policy_picclife(
							$attribute_type,//add by wangcya, 20141213
							$attribute_code,//add by wangcya, 20141213
							$policy_arr,
							$user_info_applicant,
							$list_subject,
							$flag_policy_check_only =0//如果是1表示仅仅是核保
						   )
{
	global $_SGLOBAL, $java_class_name_picclife;
	/////////////////////////////////////////////////////////
	ss_log("into ".__FUNCTION__);
	
	$policy_id = $policy_arr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	
	$orderNum = $policy_arr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_arr['order_sn'];//add by wangcya, 20141023
	
	//生成需要进行核/投保的xml文件
	$strxml = gen_picclife_policy_xml(
									$attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject
									//($flag_policy_check_only == 1) ? 1 :2 //
									);//subject信息，被保险人信息也在这里面
	
	ss_log(__FUNCTION__.", after gen_picclife_policy_xml,strxml=".$strxml);

	$strxml = trim($strxml);
	if(empty($strxml))
	{		
		ss_log("");
		
		$result = 112;
		$retmsg = "gen strxml null!";
		ss_log($retmsg);
		
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		
		return $result_attr;
		
	}
	
	/////////////////////////////////////////////////////////////////////////////////////
	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_picclife_policy_post.xml";
	file_put_contents($af_path,$strxml);//核保文件先存起来
	
	//start add by wangcya, 20150209,把携带序列号的也保存起来
	$UUID = $policy_arr['order_num'];
	$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_picclife_policy_post.xml";
	file_put_contents($af_path_serial,$strxml);
	//end add by wangcya, 20150209,把携带序列号的也保存起来
	
	//仅仅是核保，则也走同步
	if(!defined('USE_ASYN_JAVA') || $flag_policy_check_only == 1)
	{
		$java_class_name = $java_class_name_picclife;//同步
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_picclife:$java_class_name;
	//end add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	
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
			
	///////////////////////////////////////////////////////////////////////////////////////////
	$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_picclife_policy_post_ret.xml";
	ss_log($ret_af_path);
	
	$url = URL_PICCLIFE_POST_POLICY;//核保，投保保URL
	ss_log(__FUNCTION__."url: ".$url);
	$secretKey=PICCLIFE_SECRET_KEY;//"ePb#I&aCoCins";
	//$secretKey = "ePb#I&aCoCins";        
	ss_log(__FUNCTION__.", secretKey=".$secretKey);
	if(!defined('USE_ASYN_JAVA') || $flag_policy_check_only == 1)//同步核保处理流程
	{
		global $LOGFILE_PICCLIFE;

		//进行核保调用处理
		ss_log(__FUNCTION__.", picclife will java check policy,同步核保开始");
		
		$strxml_check_policy_ret = $return_data = (string)$obj_java->underWriting(
																		$url,
																		$strxml ,//投保报文
																		"",
																		"",
																		$secretKey,
																		$LOGFILE_PICCLIFE
																		);

		ss_log(__FUNCTION__."after post call function sendMessage");

		//////////////////////////////////////////////////////////
		if(!empty($strxml_check_policy_ret))//核保完了的返回处理
		{
			ss_log(__FUNCTION__."xinhua will process return_data");
		
			file_put_contents($ret_af_path, $strxml_check_policy_ret);
				
			//////////////////////////////////////////////////////////////////////////
			$result_attr = process_return_xml_data_picclife(
														 	$policy_id,
									                      	$orderNum,
															$order_sn,
															$return_data,
															$user_info_applicant,
															NULL,
															$flag_policy_check_only
															);
				
			///////////////////////////////////////////////////////////////////////
			return $result_attr;
		
		}
		else
		{
			//$retmsg = iconv('GB2312', 'UTF-8', $retmsg); //将字符串的编码从GB2312转到UTF-8
			$retcode = 110;
			$retmsg = "result_attr return date is null!";
			ss_log($retmsg);
				
			$result_attr = array('retcode'=>$retcode,
					'retmsg'=> $retmsg
			);
				
			return $result_attr;
		}
		
	
	}
	else//异步核保情况
	{//USE_ASYN_JAVA
		//////////////////////////////////////////
		ss_log(__FUNCTION__."将要发送picclife核保的异步请求，will doService");
		$xmlContent = "";
		$postXmlFileName = $af_path;

		$postXmlEncoding = "UTF-8";//GBK";
		$respXmlFileName = $ret_af_path;//承保返回报文存储路径
		$logFileName = S_ROOT."xml/log/picclife_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	
		ss_log(__FUNCTION__."logFileName: ".$logFileName);
	
		$type = "insure";//核保
		
		$insurer_code = $policy_arr['insurer_code'];
		//投保之后的回调url
		$callBackURL = CALLBACK_URL;
					
		ss_log(__FUNCTION__.", policy_id: ".$policy_id);
		ss_log(__FUNCTION__."type: ".$type);
		ss_log(__FUNCTION__."insurer_code: ".$insurer_code);
		ss_log(__FUNCTION__."callBackURL: ".$callBackURL);
			
		$param_other = array(
							"url"=>$url ,//投保保险公司服务地址
							"xmlContent"=>$xmlContent ,//核保请求报文
							"postXmlFileName"=>$postXmlFileName ,//投保报文内容
							"postXmlFileEncoding"=>$postXmlEncoding,//投保报文文件
							"Key"=>$secretKey ,//投保报文文件编码格式
							"respXmlFileName"=>$respXmlFileName,//返回报文保存全路径
							"respXmlFileEncoding"=>"UTF-8",
							"logFileName"=>$logFileName//日志文件
						);
	
		ss_log(__FUNCTION__.", param_other: ".var_export($param_other,true));

		$jsonStr = json_encode($param_other);
		ss_log(__FUNCTION__.", jsonStr: ".$jsonStr);
		
		$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_picclife_policy_post_to_java.json";
		file_put_contents($af_path,$jsonStr);
	
		//异步投保调用接口函数
		$successAccept = (string)$obj_java->doService(
											$insurer_code,//险种代码
											$type,//第一步是承保
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

	/////////////////////////////////////////////////////////////
	return array('retcode'=>$result,
				'retmsg'=> $retmsg
				);
}


//获取责任价格
function picclife_get_duty_price_by_id($duty_price_list)
{
	global $_SGLOBAL;

	///找产品名，责任名，等信息
	$sql="SELECT ipb.product_name,ipb.product_type, ipd.duty_name,ipdp.amount,ipdp.premium FROM t_insurance_product_duty_price AS ipdp
	INNER JOIN t_insurance_product_duty AS ipd ON ipd.product_duty_id=ipdp.product_duty_id
	INNER JOIN t_insurance_product_base AS ipb ON ipd.product_id=ipb.product_id 
	WHERE ipdp.product_duty_price_id IN ($duty_price_list)";
	$product_query = $_SGLOBAL['db']->query($sql);
	$arr_choosen_product_duty_list = array();
	//当前险种下的所有产品
	while ($product_row = $_SGLOBAL['db']->fetch_array($product_query))
	{
		$arr_choosen_product_duty_list[] = array(
											'product_name'=>$product_row['product_name'],
											'product_type'=>$product_row['product_type'],
											'duty_name'=>$product_row['duty_name'],
											'amount'=>$product_row['amount'],
											'price'=>$product_row['premium'],
											);	
	}
	return $arr_choosen_product_duty_list;
    
}


//added by zhangxi, 20150528, 返回报文解析到数组
function parse_xml_data_to_array_picclife($return_data)
{
	/*<?xml version="1.0" encoding="GBK"?><TXLife><TXLifeResponse><TransType tc="103">NEW_BUSINESS_SUBMISSION</TransType><TransRefGUID>22414327777790479530069</TransRefGUID><TransMode tc="1">TRIAL</TransMode><TransExeDate>2015-05-28</TransExeDate><TransExeTime>09:52:14</TransExeTime><OLifE><Holding id="Holding_1"><Policy><ProductCode>IJKLQ</ProductCode><ApplicationInfo><HOAppFormNumber>000039524924158</HOAppFormNumber></ApplicationInfo><TermDate>2045-05-28</TermDate></Policy></Holding></OLifE><TransResult><ResultCode tc="1" >Success</ResultCode> <ResultInfo><ResultInfoDesc>核保成功</ResultInfoDesc></ResultInfo></TransResult><OLifEExtension VendorCode="1"><BankCode>ebaoins</BankCode></OLifEExtension></TXLifeResponse></TXLife>
	 * */
	ss_log(__FUNCTION__.", get in");
	$policy_ret = array();
	preg_match_all('/<ResultCode tc="(.*)" >(.*)<\/ResultCode>/isU',$return_data,$arr);
	if($arr[1] && $arr[2])
	{
		$policy_ret['ResultCode_tc'] = trim($arr[1][0]);
		$policy_ret['ResultCode'] = trim($arr[2][0]);
	}

	preg_match_all('/<TransMode tc="(.*)"[ ]*>(.*)<\/TransMode>/isU',$return_data,$arr);
	if($arr[1] && $arr[2])
	{
		$policy_ret['TransMode_tc'] = trim($arr[1][0]);
		$policy_ret['TransMode'] = trim($arr[2][0]);
	}
	
	preg_match_all('/<ResultInfoDesc>(.*)<\/ResultInfoDesc>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['ResultInfoDesc'] = trim($arr[1][0]);
	}

	preg_match_all('/<ProductCode>(.*)<\/ProductCode>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['ProductCode'] = trim($arr[1][0]);
	}

	preg_match_all('/<TransRefGUID>(.*)<\/TransRefGUID>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['TransRefGUID'] = trim($arr[1][0]);
	}
	//核保返回的投保单号
	preg_match_all('/<HOAppFormNumber>(.*)<\/HOAppFormNumber>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['HOAppFormNumber'] = trim($arr[1][0]);//投保单号
	}
	
	//PolicyUrl，承保返回的保单地址
	preg_match_all('/<PolicyUrl>(.*)<\/PolicyUrl>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['PolicyUrl'] = trim($arr[1][0]);//投保单号
	}
	//承保返回的保单号 PolNumber
	preg_match_all('/<PolNumber>(.*)<\/PolNumber>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['PolNumber'] = trim($arr[1][0]);//保单号码
	}
	
	ss_log(__FUNCTION__.", policy_ret=".var_export($policy_ret, true));
	return $policy_ret;
}


/**
 * 求两个日期之间相差的天数
 * (针对1970年1月1日之后，求之前可以采用泰勒公式)
 * @param string $day1
 * @param string $day2
 * @return number
 */
function diffBetweenTwoDays ($day1, $day2)
{
  $second1 = strtotime($day1);
  $second2 = strtotime($day2);
    
  if ($second1 < $second2) {
    $tmp = $second2;
    $second2 = $second1;
    $second1 = $tmp;
  }
  return ($second1 - $second2) / 86400;
}



function process_file_xls_picclife_insured($uploadfile, $product)
{
	ss_log(__FUNCTION__.": ".$uploadfile);
	if(empty($uploadfile))
	{	
		return array('result_code'=>'1',
						'result_msg'=>'fail, file not exist',
						);
	}
	////////////////////////////////////////////////////////////////////
	$path = dirname(S_ROOT);
	global $G_XLS_UPLOAD_FORMAT_PICCLIFE_ESHIDAI_PILIANG;

	
	//自己设置的上传文件存放路径
	require_once $path.'/oop/lib/Excel/PHPExcel.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/IOFactory.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/Reader/Excel5.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/Reader/Excel2007.php';

	if(isset($_POST['commit_type']))
	{
		$commit_type = 	trim($_POST['commit_type']);

		//批量上传	
		if($commit_type == 'piliang')
		{
			$result = process_file_xls_upload_common($uploadfile, $product, $G_XLS_UPLOAD_FORMAT_PICCLIFE_ESHIDAI_PILIANG);
		}
	
	}
	
	
	return $result;
}

?>