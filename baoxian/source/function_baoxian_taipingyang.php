<?php

if(!defined('S_ROOT'))
{
	define('S_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
}

include_once(S_ROOT.'/source/my_const.php');
include_once(S_ROOT.'/source/function_common.php');

$attr_certificates_type_taipingyang = array(
		1 => '身份证',
		2 => '护照',
		3 => '军官证',
		4 => '驾照',
		5 => '其他',
		6 => '组织机构代码证',//mod by zhangxi, 20150123, 必须与批量投保下拉框内容完全一致
);


//<!--（可空）团体证件类型，01表示组织机构代码证，02表示税务登记证，03表示异常证件-->
$attr_group_certificates_type_taipingyang =  array(
		6 => '组织机构代码证'//mod by zhangxi, 20150123, 必须与批量投保下拉框内容完全一致
);
$attr_certificates_type_cpic_tj_property = array(
		1 => '身份证',
		2 => '护照',
		3 => '其他',
);
//<!--（可空）团体证件类型，-->
$attr_group_certificates_type_cpic_tj_property =  array(
		4 => '组织机构代码证',
		5 => '营业证号',
		6 => '其他',
);
$attr_industry_type_cpic_tj_property = array(
									'A'=>'农、林、牧、渔业',
									'B'=>'采矿业',
									'C'=>'制造业',
									'D'=>'电力、燃气及水的生产和供应业',	
									'E'=>'建筑业',	
									'F'=>'交通运输、仓储和邮政业',	
									'G'=>'信息传输、计算机服务和软件业',	
									'H'=>'批发和零售业',	
									'I'=>'住宿和餐饮业',	
									'J'=>'金融业',	
									'K'=>'房地产业',	
									'L'=>'租赁和商务服务业',	
									'M'=>'科学研究、技术服务和地质勘查业',	
									'N'=>'水利、环境和公共设施管理业',	
									'O'=>'居民服务和其他服务业',	
									'P'=>'教育',	
									'Q'=>'卫生、社会保障和社会福利业',	
									'R'=>'文化、体育和娱乐业',	
									'S'=>'公共管理和社会组织',	
									'T'=>'国际组织',	
									'U'=>'楼宇',	
									);

$attr_benefit_type_cpic_tj_property = array(
									0 => '其他',
									1 => '法定',
									2 => '均分',
									3 => '比例',
									4 => '顺位'
									
									);


//<!--（可空）单位性质，07:股份 01:国有 02:集体 33:个体 03:私营 04:中外合资 05:外商独资 08:机关事业 13:社团 39:中外合作 9:其他-->
$attr_company_attribute_type_taipingyang = array();



$attr_sex_taipingyang = array(
		1 => '男',
		2 => '女',
);
$attr_sex_cpic_tj_property = array(
		1 => '男',
		2 => '女',
);
$attr_relationship_with_insured_taipingyang = array(
		1 => '本人',
		2 => '配偶',
		3 => '子女',
		4 => '父母',
		5 => '女儿',
		6 => '其他',
		7 => '儿子'
);
$attr_relationship_with_insured_cpic_tj_property = array(
		0 => '本人',
		1 => '配偶',
		2 => '子女',
		3 => '父母',
		4 => '其他',

);

//added by zhangxi, 20150119,太平洋意外
$G_XLS_UPLOAD_FORMAT_TAIPINGYANG_YIWAI = array(
					'1'=>'applicant_type',//投保人类型
					'2'=>'applicant_name',//投保人名
					'3'=>'applicant_certificates_type',//投保人证件类型
					'4'=>'applicant_certificates_code',//投保人证件号
					'5'=>'applicant_birthday',//出生日期
					'6'=>'applicant_gender',//性别
					'7'=>'applicant_mobilephone',//手机
					'8'=>'applicant_email',//email
					'9'=>'relationship',//与被保险人关系
					'10'=>'assured_name',//被保险人姓名
					'11'=>'assured_certificates_type',//被保险人证件类型
					'12'=>'assured_certificates_code',//被保险人证件号
					'13'=>'assured_birthday',//被保险人生日列
					'14'=>'assured_gender',//被保险人性别列
					'15'=>'assured_mobilephone',//被保险人手机列
					'16'=>'assured_email',//被保险人email列
					'17'=>'occupation',//职业类别
					);
$G_XLS_UPLOAD_FORMAT_TAIPINGYANG_TUANDAN = array(
					'1'=>'assured_name',//被保险人姓名
					'2'=>'assured_certificates_type',//被保险人证件类型
					'3'=>'assured_certificates_code',//被保险人证件号
					'4'=>'assured_birthday',//被保险人生日列
					'5'=>'assured_gender',//被保险人性别列
					'6'=>'assured_mobilephone',//被保险人手机列
					'7'=>'assured_email',//被保险人email列
					//'8'=>'occupation',//职业类别
					);
//////////////////start add by wangcya, 20141219 ,不同厂商的证件号码和性别//////////////////////////////
/*
 		$c_type = array();
		$c_type["1"]='身份证';
		$c_type["2"]='驾驶证';
		$c_type["3"]='军官证';
		$c_type["4"]='护照';
		$c_type["5"]='港澳回乡证或台胞证';
		$c_type["6"]='返乡证';
		$c_type["7"]='组织机构代码';
		$c_type["8"]='军人证';
		$c_type["9"]='其他';
*/

$attr_certificates_type_taipingyang_single_unify = array(
		"1"=>1,//'身份证';
		"4"=>2,//'驾驶证';
		"3"=>3,//'军官证';
		"2"=>4,//'护照';
		//"1"=>5,//'港澳回乡证或台胞证';
		//"1"=>6,//'返乡证';
		//"1"=>7,//'组织机构代码';
		//"1"=>8,//'军人证';
		"5"=>9,//'其他';
);
$attr_certificates_type_cpic_tj_property_single_unify = array(
		"1"=>1,//'身份证';
		"2"=>4,//'护照';
		"3"=>9,//'其他';
);

$attr_certificates_type_taipingyang_group_unify = array(
		"6"=>10,//"组织机构代码证"
		//"02"=>11,//"税务登记证"
		//"03"=>12//"异常证件"
);
$attr_certificates_type_cpic_tj_property_group_unify = array(
		"4"=>10,//"组织机构代码证"
		//"02"=>11,//"税务登记证"
		//"03"=>12//"异常证件"
);

$attr_sex_taipingyang_unify = array(
		"2"=>"F",
		"1"=>"M",
);
$attr_sex_cpic_tj_property_unify = array(
		"2"=>"F",
		"1"=>"M",
);
//////////////////end add by wangcya, 20141219 //////////////////////////////

$java_class_name_taipingyang = 'com.yanda.imsp.util.ins.CPICPolicyFecher';

function input_check_cpic_tj_family_property($product, $POST)
{
	global $_SGLOBAL;
	global $attr_certificates_type_cpic_tj_property_single_unify;
	global $attr_certificates_type_cpic_tj_property_group_unify;
	global $attr_sex_cpic_tj_property_unify;
	global $global_attr_obj_type;
	$insurer_code = $product['insurer_code'];
	////////////////////////////////////////////////////////////////////////////////////////
	$agent_uid = $_SGLOBAL['supe_uid'];//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	
	$global_info = array();
	$businessType = empty($POST['businessType'])?1:trim($POST['businessType']);
	
	ss_log(__FUNCTION__.", businessType: ".$businessType);
	//////////////////有效性检查//////////////////////////////////////////////////////
	
	if(	!isset($POST['applyNum'])||
			!isset($POST['startDate'])||
			!isset($POST['endDate'])
		)
	
	{
		showmessage("您输入的投保信息不完整！");
		exit(0);
	}
	
	if($businessType==1)
	{
		if(	!isset($POST['applicant_fullname'])||
			!isset($POST['applicant_certificates_type'])||
			!isset($POST['applicant_certificates_code'])||
			!isset($POST['applicant_mobilephone'])||//add by wangcya, 20141018
			!isset($POST['applicant_email'])
					
		  )
	
		{
			showmessage("您输入的投保人信息不完整！");
			exit(0);
		}
	}
	else
	{

		showmessage("本产品投保只支持个单！");
		exit(0);

	}
	
	$POST['relationshipWithInsured'] = 6;
	if($POST['relationshipWithInsured']!=0)//不是本人
	{
		$assured_info = $POST['assured'][0];
		////////////////////////////////////////////////////////////////////
		
		$assured_fullname = $assured_info['assured_fullname'];
		$assured_certificates_type = $assured_info['assured_certificates_type'];
		$assured_certificates_code = $assured_info['assured_certificates_code'];
		$assured_sex = $assured_info['assured_sex'];
		$assured_birthday = $assured_info['assured_birthday'];
		
		$assured_mobilephone = $assured_info['assured_mobilephone'];//add by wangcya, 20141018
		$assured_email = $assured_info['assured_email'];
		
		ss_log(__FUNCTION__." assured_fullname: ".$assured_fullname);
		ss_log(__FUNCTION__." assured_certificates_type: ".$assured_certificates_type);
		ss_log(__FUNCTION__." assured_certificates_code: ".$assured_certificates_code);
		ss_log(__FUNCTION__." assured_sex: ".$assured_sex);
		ss_log(__FUNCTION__." assured_birthday: ".$assured_birthday);
		
		//////////////////////////////////////////////////////	
		if(		!isset($assured_fullname)||
				!isset($assured_certificates_type)||
				!isset($assured_certificates_code)||
				!isset($assured_mobilephone)||
				!isset($assured_email)
		
		)
		{
			showmessage("您输入的被保险人信息不完整！");
			exit(0);
		}

	}
	////////////////////////////////////////////////////////////////////////

	//投保公共信息处理
	$global_info['applyNum'] = intval($POST['applyNum']);//份数
	$global_info['totalModalPremium'] = $POST['totalModalPremium'];//总保费,应该从前端计算得到的

	$global_info['beneficiary'] = $POST['beneficiary'];//受益人
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期

	//投保人信息处理///
	$user_info_applicant = array();

	//下面的操作要和数据库的表中的字段一致。
	if($businessType == 1)//个人
	{
		$user_info_applicant['certificates_type'] = $POST['applicant_certificates_type'];
		$user_info_applicant['certificates_code'] = $POST['applicant_certificates_code'];
		$user_info_applicant['fullname'] = $POST['applicant_fullname'];
		$user_info_applicant['gender'] = $POST['applicant_sex'];
		$user_info_applicant['birthday'] = $POST['applicant_birthday'];
		$user_info_applicant['mobiletelephone'] = $POST['applicant_mobilephone'];
		$user_info_applicant['email'] = $POST['applicant_email'];

		$user_info_applicant['province_code'] = $POST['applicant_province_code'];
		$user_info_applicant['province'] = $POST['applicant_province'];
		$user_info_applicant['city_code'] = $POST['applicant_city_code'];
		$user_info_applicant['city'] = $POST['applicant_city'];
		
		$user_info_applicant['zipcode'] = $POST['applicant_zipcode'];
		$user_info_applicant['address'] = $POST['applicant_address'];

		$user_info_applicant['occupationClassCode'] = $POST['applicant_occupationClassCode'];
		
		
		ss_log(__FUNCTION__." applicant province_code: ".$user_info_applicant['province_code']);
		ss_log(__FUNCTION__." applicant province: ".$user_info_applicant['province']);
		ss_log(__FUNCTION__." applicant city_code: ".$user_info_applicant['city_code']);
		ss_log(__FUNCTION__." applicant city: ".$user_info_applicant['city']);
		
		/////////////////////////////////////////////////
		//start add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		$user_info_applicant['certificates_type_unify'] = $attr_certificates_type_cpic_tj_property_single_unify[$user_info_applicant['certificates_type']];
		$user_info_applicant['gender_unify'] = $attr_sex_cpic_tj_property_unify[$user_info_applicant['gender']];
		$user_info_applicant['type'] = 1;//0,被保险人，1投保人,2两者身份相同
		$user_info_applicant['agent_uid'] = $agent_uid;
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		
	}
	else//团体投保人
	{
	}
	//以上是投保人信息处理

	
	//被保险人信息处理///
	$assured_info = array();
	$list_assured1 = array();
	/////////////////////////////////////////////////////////
	$relationshipWithInsured = trim($POST['relationshipWithInsured']);//（非空）与投保人关系。1:本人 2:配偶 3 :父子 4:父女 5:受益人 6:被保人 7:投保人 A:母子 B:母女 C:兄弟 D:姐弟 G:祖孙 H:雇佣 I:子女 9:其他 8:转换不详
	////////////////投保人的身份信息////////////////////////////////
	//////////////////首先根据被保险人身份证到用户表中查询，如果查到则进行信息更新，如果没查到插入新纪录
	
	$real_apply_num=0;
	if($businessType == 1)//个单情况
	{
		if($relationshipWithInsured=="0")//是本人的信息
		{
			$assured_info = $user_info_applicant;
			$assured_info['type'] = 2;//add by wangcya, 20141219, 0,被保险人，1投保人,2两者身份相同
		}
		else
		{//new
			$assured_post = $POST['assured'][0];
		
			$assured_info['certificates_type'] = trim($assured_post['assured_certificates_type']);
			$assured_info['certificates_code'] = trim($assured_post['assured_certificates_code']);
			$assured_info['birthday'] = trim($assured_post['assured_birthday']);
			$assured_info['fullname']  = trim($assured_post['assured_fullname']);
			$assured_info['gender']  = trim($assured_post['assured_sex']);
			$assured_info['mobiletelephone'] =  trim($assured_post['assured_mobilephone']);
			$assured_info['email'] =  trim($assured_post['assured_email']);
	
			$assured_info['province_code'] = $assured_post['insured_province_code'];
			$assured_info['province'] = $assured_post['insured_province'];
			
			$assured_info['city_code'] = $assured_post['insured_city_code'];
			$assured_info['city'] = $assured_post['insured_city'];
			
			////////////////////////////////////////////////////////////
			$assured_info['occupationClassCode'] = $assured_post['insured_occupationClassCode'];
			
			$assured_info['zipcode'] = $assured_post['insured_zipcode'];
			$assured_info['address'] = $assured_post['insured_address'];
			
			//add by wangcya, 20141219 ,不同厂商的证件号码和性别
			$assured_info['certificates_type_unify'] = $attr_certificates_type_cpic_tj_property_single_unify[$assured_info['certificates_type']];
			$assured_info['gender_unify'] = $attr_sex_cpic_tj_property_unify[$assured_info['gender']];
			$assured_info['type'] = 0;//0,被保险人，1投保人,2两者身份相同
			$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
			//end add by wangcya, 20141219 ,不同厂商的证件号码和性别
		}
		$list_assured1[] = $assured_info;
		
	}
	else//团单的被保险人
	{}
		
	
	
	$global_info['insured_num'] = $real_apply_num;
	////////////////////////////////////////////////////////////////////
	$global_info['beneficiary'] = $POST['beneficiary'];//受益人，一般为法定。
	////////////////////////////////////////////////////////////////////

	$product_id = intval($POST['product_id']);//comment by wangcya, 20141017, 这里很关键的，是产品信息。
	
	ss_log("check input, post product_id: ".$product_id);
	//////////一个subjectInfo内多个产品，多个被保险人///////////////////////////////////////////////////////////
	////////方案一是父母的////////////
	$subjectInfo1 = array();

	$list_product_id1 = array();
	$list_product_id1[] = $product_id;//
	$subjectInfo1['list_product_id'] = $list_product_id1;

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
function input_check_cpic_tj_property($product, $POST)
{
	
	global $_SGLOBAL;
	global $attr_certificates_type_cpic_tj_property_single_unify;
	global $attr_certificates_type_cpic_tj_property_group_unify;
	global $attr_sex_cpic_tj_property_unify;
	global $global_attr_obj_type;
	$insurer_code = $product['insurer_code'];
	////////////////////////////////////////////////////////////////////////////////////////
	$agent_uid = $_SGLOBAL['supe_uid'];//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	
	$global_info = array();
	$businessType = empty($POST['businessType'])?1:trim($POST['businessType']);
	
	ss_log(__FUNCTION__.", businessType: ".$businessType);
	//////////////////有效性检查//////////////////////////////////////////////////////
	
	if(	!isset($POST['applyNum'])||
			!isset($POST['startDate'])||
			!isset($POST['endDate'])||
			!isset($POST['relationshipWithInsured'])
		)
	
	{
		showmessage("您输入的投保信息不完整！");
		exit(0);
	}
	
	if($businessType==1)
	{
		if(	!isset($POST['applicant_fullname'])||
			!isset($POST['applicant_certificates_type'])||
			!isset($POST['applicant_certificates_code'])||
			!isset($POST['applicant_birthday'])||
			!isset($POST['applicant_sex'])||
			!isset($POST['applicant_mobilephone'])||//add by wangcya, 20141018
			!isset($POST['applicant_email'])
					
		  )
	
		{
			showmessage("您输入的投保人信息不完整！");
			exit(0);
		}
	}
	elseif($businessType==2)//group
	{
		if(	!isset($POST['applicant_group_name'])||
				!isset($POST['applicant_group_certificates_type'])||
				!isset($POST['applicant_group_certificates_code'])||
				!isset($POST['applicant_group_mobilephone'])||
				!isset($POST['applicant_group_email'])
		  )
		
		{
			showmessage("您输入的投保机构信息不完整！");
			exit(0);
		}
	}
	
	
	if($POST['relationshipWithInsured']!=0)//不是本人
	{
		$assured_info = $POST['assured'][0];
		////////////////////////////////////////////////////////////////////
		
		$assured_fullname = $assured_info['assured_fullname'];
		$assured_certificates_type = $assured_info['assured_certificates_type'];
		$assured_certificates_code = $assured_info['assured_certificates_code'];
		$assured_sex = $assured_info['assured_sex'];
		$assured_birthday = $assured_info['assured_birthday'];
		
		$assured_mobilephone = $assured_info['assured_mobilephone'];//add by wangcya, 20141018
		$assured_email = $assured_info['assured_email'];
		
		ss_log(__FUNCTION__." assured_fullname: ".$assured_fullname);
		ss_log(__FUNCTION__." assured_certificates_type: ".$assured_certificates_type);
		ss_log(__FUNCTION__." assured_certificates_code: ".$assured_certificates_code);
		ss_log(__FUNCTION__." assured_sex: ".$assured_sex);
		ss_log(__FUNCTION__." assured_birthday: ".$assured_birthday);
		
		//////////////////////////////////////////////////////	
		if(		!isset($assured_fullname)||
				!isset($assured_certificates_type)||
				!isset($assured_certificates_code)||
				!isset($assured_sex)||
				!isset($assured_birthday)||
				!isset($assured_mobilephone)||
				!isset($assured_email)
		
		)
		{
			showmessage("您输入的被保险人信息不完整！");
			exit(0);
		}

	}
	////////////////////////////////////////////////////////////////////////

	//投保公共信息处理
	$global_info['applyNum'] = intval($POST['applyNum']);//份数
	$global_info['totalModalPremium'] = $POST['totalModalPremium'];//总保费,应该从前端计算得到的

	$global_info['beneficiary'] = $POST['beneficiary'];//受益人
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期

	//投保人信息处理///
	$user_info_applicant = array();

	//下面的操作要和数据库的表中的字段一致。
	if($businessType == 1)//个人
	{
		$user_info_applicant['certificates_type'] = $POST['applicant_certificates_type'];
		$user_info_applicant['certificates_code'] = $POST['applicant_certificates_code'];
		$user_info_applicant['fullname'] = $POST['applicant_fullname'];
		$user_info_applicant['gender'] = $POST['applicant_sex'];
		$user_info_applicant['birthday'] = $POST['applicant_birthday'];
		$user_info_applicant['mobiletelephone'] = $POST['applicant_mobilephone'];
		$user_info_applicant['email'] = $POST['applicant_email'];

		$user_info_applicant['province_code'] = $POST['applicant_province_code'];
		$user_info_applicant['province'] = $POST['applicant_province'];
		$user_info_applicant['city_code'] = $POST['applicant_city_code'];
		$user_info_applicant['city'] = $POST['applicant_city'];
		
		$user_info_applicant['zipcode'] = $POST['applicant_zipcode'];
		$user_info_applicant['address'] = $POST['applicant_address'];

		$user_info_applicant['occupationClassCode'] = $POST['applicant_occupationClassCode'];
		
		
		ss_log(__FUNCTION__." applicant province_code: ".$user_info_applicant['province_code']);
		ss_log(__FUNCTION__." applicant province: ".$user_info_applicant['province']);
		ss_log(__FUNCTION__." applicant city_code: ".$user_info_applicant['city_code']);
		ss_log(__FUNCTION__." applicant city: ".$user_info_applicant['city']);
		
		/////////////////////////////////////////////////
		//start add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		$user_info_applicant['certificates_type_unify'] = $attr_certificates_type_cpic_tj_property_single_unify[$user_info_applicant['certificates_type']];
		$user_info_applicant['gender_unify'] = $attr_sex_cpic_tj_property_unify[$user_info_applicant['gender']];
		$user_info_applicant['type'] = 1;//0,被保险人，1投保人,2两者身份相同
		$user_info_applicant['agent_uid'] = $agent_uid;
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		
	}
	else//团体投保人
	{
		////////////////////////////////////////////////////
		$user_info_applicant['group_name'] = $POST['applicant_group_name'];
		$user_info_applicant['group_certificates_type'] = $POST['applicant_group_certificates_type'];
		$user_info_applicant['group_certificates_code'] = $POST['applicant_group_certificates_code'];
		
		$user_info_applicant['mobiletelephone'] = $POST['applicant_group_mobilephone'];
		$user_info_applicant['email'] = $POST['applicant_group_email'];
		
		$user_info_applicant['group_abbr'] = $POST['applicant_group_abbr'];
		$user_info_applicant['company_attribute'] = $POST['applicant_company_attribute'];
		$user_info_applicant['address'] = $POST['applicant_group_address'];
		$user_info_applicant['telephone'] = $POST['applicant_group_telephone'];
		
		$user_info_applicant['province'] = trim($POST['province']);
		$user_info_applicant['province_code'] = trim($POST['province_code']);
		$user_info_applicant['city'] = trim($POST['city']);
		$user_info_applicant['city_code'] = trim($POST['city_code']);
		$user_info_applicant['county'] = trim($POST['county']);
		$user_info_applicant['county_code'] = trim($POST['county_code']);

		//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		$user_info_applicant['group_certificates_type_unify'] = $attr_certificates_type_cpic_tj_property_group_unify[$user_info_applicant['group_certificates_type']];
		$user_info_applicant['agent_uid'] = $agent_uid;
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	}
	//以上是投保人信息处理

	
	//被保险人信息处理///
	$assured_info = array();
	$list_assured1 = array();
	/////////////////////////////////////////////////////////
	$relationshipWithInsured = trim($POST['relationshipWithInsured']);//（非空）与投保人关系。1:本人 2:配偶 3 :父子 4:父女 5:受益人 6:被保人 7:投保人 A:母子 B:母女 C:兄弟 D:姐弟 G:祖孙 H:雇佣 I:子女 9:其他 8:转换不详
	////////////////投保人的身份信息////////////////////////////////
	//////////////////首先根据被保险人身份证到用户表中查询，如果查到则进行信息更新，如果没查到插入新纪录
	
	$real_apply_num=0;
	if($businessType == 1)//个单情况
	{
		if($relationshipWithInsured=="0")//是本人的信息
		{
			$assured_info = $user_info_applicant;
			$assured_info['type'] = 2;//add by wangcya, 20141219, 0,被保险人，1投保人,2两者身份相同
		}
		else
		{//new
			$assured_post = $POST['assured'][0];
		
			$assured_info['certificates_type'] = trim($assured_post['assured_certificates_type']);
			$assured_info['certificates_code'] = trim($assured_post['assured_certificates_code']);
			$assured_info['birthday'] = trim($assured_post['assured_birthday']);
			$assured_info['fullname']  = trim($assured_post['assured_fullname']);
			$assured_info['gender']  = trim($assured_post['assured_sex']);
			$assured_info['mobiletelephone'] =  trim($assured_post['assured_mobilephone']);
			$assured_info['email'] =  trim($assured_post['assured_email']);
	
			$assured_info['province_code'] = $assured_post['insured_province_code'];
			$assured_info['province'] = $assured_post['insured_province'];
			
			$assured_info['city_code'] = $assured_post['insured_city_code'];
			$assured_info['city'] = $assured_post['insured_city'];
			
			////////////////////////////////////////////////////////////
			$assured_info['occupationClassCode'] = $assured_post['insured_occupationClassCode'];
			
	
			$assured_info['zipcode'] = $assured_post['insured_zipcode'];
			$assured_info['address'] = $assured_post['insured_address'];
			
			//add by wangcya, 20141219 ,不同厂商的证件号码和性别
			$assured_info['certificates_type_unify'] = $attr_certificates_type_cpic_tj_property_single_unify[$assured_info['certificates_type']];
			$assured_info['gender_unify'] = $attr_sex_cpic_tj_property_unify[$assured_info['gender']];
			$assured_info['type'] = 0;//0,被保险人，1投保人,2两者身份相同
			$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
			//end add by wangcya, 20141219 ,不同厂商的证件号码和性别
		}
		$list_assured1[] = $assured_info;
		
	}
	else//团单的被保险人
	{
				
		//added by zhangxi, 20150320, 被保险人信息获取
		$data_user = stripslashes($POST['data_user_info']);
		if(empty($data_user))
		{
			showmessage("获取被保险人信息失败！");
			exit(0);
		}
		ss_log(__FUNCTION__."get post data:".$POST['data_user_info']);
		
		$list_user = json_decode($data_user, true);
		ss_log(__FUNCTION__."json decode data:".$list_user);
		$list_assured1 = array();
		//获取被保险人信息，这里需要考虑多个被保险人信息
		global $attr_certificates_type_cpic_tj_property_single_unify;
		
		foreach($list_user as $k=>$val)
		{
			//证件类型需要转换为保险公司相关代码
			$assured_info['certificates_type'] = trim($val['assured_certificates_type']);
			$attr_certificates_type = $global_attr_obj_type[$insurer_code]['attr_certificates_type'];
			$assured_info['certificates_type'] = array_search($assured_info['certificates_type'], $attr_certificates_type);
			$assured_info['certificates_code'] = trim($val['assured_certificates_code']);
			$assured_info['birthday'] = trim($val['assured_birthday']);
			$assured_info['fullname']  = trim($val['assured_name']);
			$assured_info['fullname_english']  = trim($val['assured_name_english']);
			$attr_epicc_gender = $global_attr_obj_type[$insurer_code]['$attr_epicc_Gender'];
			$assured_info['gender']  = trim($val['assured_gender']);
			$assured_info['gender'] = array_search($assured_info['gender'], $attr_epicc_gender);
			
			$assured_info['mobiletelephone'] =  trim($val['assured_mobilephone']);
			$assured_info['email'] =  trim($val['assured_email']);
			
			//add by wangcya, 20141219 ,不同厂商的证件号码和性别
			$assured_info['certificates_type_unify'] = $attr_certificates_type_cpic_tj_property_single_unify[$assured_info['certificates_type']];
			$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
			//end add by wangcya, 20141219 ,不同厂商的证件号码和性别
			
			$real_apply_num++;//团单不少于5人得判断
			$list_assured1[] = $assured_info;
		}

	}
		
	
	
	$global_info['insured_num'] = $real_apply_num;
	////////////////////////////////////////////////////////////////////
	$global_info['beneficiary'] = $POST['beneficiary'];//受益人，一般为法定。
	////////////////////////////////////////////////////////////////////

	$product_id = intval($POST['product_id']);//comment by wangcya, 20141017, 这里很关键的，是产品信息。
	
	ss_log("check input, post product_id: ".$product_id);
	//////////一个subjectInfo内多个产品，多个被保险人///////////////////////////////////////////////////////////
	////////方案一是父母的////////////
	$subjectInfo1 = array();

	$list_product_id1 = array();
	$list_product_id1[] = $product_id;//
	$subjectInfo1['list_product_id'] = $list_product_id1;

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
//检查太平洋的输入的函数
function input_check_taipingyang($POST)
{
	global $_SGLOBAL,$attr_certificates_type_taipingyang_single_unify;
	global $attr_certificates_type_taipingyang_group_unify;
	global $attr_sex_taipingyang_unify;
	////////////////////////////////////////////////////////////////////////////////////////
	$agent_uid = $_SGLOBAL['supe_uid'];//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	
	
	$global_info = array();
	$businessType = empty($POST['businessType'])?1:trim($POST['businessType']);
	
	ss_log("input_check_taipingyang, businessType: ".$businessType);
	//////////////////有效性检查//////////////////////////////////////////////////////
	
	if(	!isset($POST['applyNum'])||
			!isset($POST['startDate'])||
			!isset($POST['endDate'])||
			!isset($POST['relationshipWithInsured'])
		)
	
	{
		showmessage("您输入的投保信息不完整！");
		exit(0);
	}
	
	if($businessType==1)
	{
		if(	!isset($POST['applicant_fullname'])||
			!isset($POST['applicant_certificates_type'])||
			!isset($POST['applicant_certificates_code'])||
			!isset($POST['applicant_birthday'])||
			!isset($POST['applicant_sex'])||
			!isset($POST['applicant_mobilephone'])||//add by wangcya, 20141018
			!isset($POST['applicant_email'])
					
		  )
	
		{
			showmessage("您输入的投保人信息不完整！");
			exit(0);
		}
	}
	elseif($businessType==2)//group
	{
		if(	!isset($POST['applicant_group_name'])||
				!isset($POST['applicant_group_certificates_type'])||
				!isset($POST['applicant_group_certificates_code'])||
				!isset($POST['applicant_group_mobilephone'])||
				!isset($POST['applicant_group_email'])
		  )
		
		{
			showmessage("您输入的投保机构信息不完整！");
			exit(0);
		}
	}
	
	
	if($POST['relationshipWithInsured']!=1)//不是本人
	{
		$assured_info = $POST['assured'][0];
		////////////////////////////////////////////////////////////////////
		
		$assured_fullname = $assured_info['assured_fullname'];
		$assured_certificates_type = $assured_info['assured_certificates_type'];
		$assured_certificates_code = $assured_info['assured_certificates_code'];
		$assured_sex = $assured_info['assured_sex'];
		$assured_birthday = $assured_info['assured_birthday'];
		
		$assured_mobilephone = $assured_info['assured_mobilephone'];//add by wangcya, 20141018
		$assured_email = $assured_info['assured_email'];
		
		ss_log("assured_fullname: ".$assured_fullname);
		ss_log("assured_certificates_type: ".$assured_certificates_type);
		ss_log("assured_certificates_code: ".$assured_certificates_code);
		ss_log("assured_sex: ".$assured_sex);
		ss_log("assured_birthday: ".$assured_birthday);
		
		//////////////////////////////////////////////////////
		
		if(		!isset($assured_fullname)||
				!isset($assured_certificates_type)||
				!isset($assured_certificates_code)||
				!isset($assured_sex)||
				!isset($assured_birthday)||
				!isset($assured_mobilephone)||
				!isset($assured_email)
		
		)
		{
			showmessage("您输入的被保险人信息不完整！");
			exit(0);
		}
		//////////////////////////////////////////////////////
	
		
	}
	////////////////////////////////////////////////////////////////////////

	$global_info['applyNum'] = intval($POST['applyNum']);//份数
	$global_info['totalModalPremium'] = $POST['totalModalPremium'];//总保费,应该从前端计算得到的

	//ss_log("totalModalPremium: ".$totalModalPremium);
	//$totalModalPremium'] = $apply_num*intval($product[premium]);//$POST['totalModalPremium'];//从费用

	$global_info['beneficiary'] = $POST['beneficiary'];//受益人
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期

	///////////////组合投保人信息//////////////////////////////////////////////////
	$user_info_applicant = array();

	//下面的操作要和数据库的表中的字段一致。
	if($businessType == 1)//个人
	{
		$user_info_applicant['certificates_type'] = $POST['applicant_certificates_type'];
		$user_info_applicant['certificates_code'] = $POST['applicant_certificates_code'];
		$user_info_applicant['fullname'] = $POST['applicant_fullname'];
		$user_info_applicant['gender'] = $POST['applicant_sex'];
		$user_info_applicant['birthday'] = $POST['applicant_birthday'];
		$user_info_applicant['mobiletelephone'] = $POST['applicant_mobilephone'];
		$user_info_applicant['email'] = $POST['applicant_email'];

		$user_info_applicant['province_code'] = $POST['applicant_province_code'];
		$user_info_applicant['province'] = $POST['applicant_province'];
		$user_info_applicant['city_code'] = $POST['applicant_city_code'];
		$user_info_applicant['city'] = $POST['applicant_city'];
		
		$user_info_applicant['zipcode'] = $POST['applicant_zipcode'];
		$user_info_applicant['address'] = $POST['applicant_address'];

		$user_info_applicant['occupationClassCode'] = $POST['applicant_occupationClassCode'];
		
		
		ss_log("applicant province_code: ".$user_info_applicant['province_code']);
		ss_log("applicant province: ".$user_info_applicant['province']);
		ss_log("applicant city_code: ".$user_info_applicant['city_code']);
		ss_log("applicant city: ".$user_info_applicant['city']);
		
		/////////////////////////////////////////////////
		//start add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		$user_info_applicant['certificates_type_unify'] = $attr_certificates_type_taipingyang_single_unify[$user_info_applicant['certificates_type']];
		$user_info_applicant['gender_unify'] = $attr_sex_taipingyang_unify[$user_info_applicant['gender']];
		$user_info_applicant['type'] = 1;//0,被保险人，1投保人,2两者身份相同
		$user_info_applicant['agent_uid'] = $agent_uid;
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		
	}
	else//团体投保人
	{
		////////////////////////////////////////////////////
		$user_info_applicant['group_name'] = $POST['applicant_group_name'];
		$user_info_applicant['group_certificates_type'] = $POST['applicant_group_certificates_type'];
		$user_info_applicant['group_certificates_code'] = $POST['applicant_group_certificates_code'];
		
		$user_info_applicant['mobiletelephone'] = $POST['applicant_group_mobilephone'];
		$user_info_applicant['email'] = $POST['applicant_group_email'];
		
		$user_info_applicant['group_abbr'] = $POST['applicant_group_abbr'];
		$user_info_applicant['company_attribute'] = $POST['applicant_company_attribute'];
		$user_info_applicant['address'] = $POST['applicant_group_address'];
		$user_info_applicant['telephone'] = $POST['applicant_group_telephone'];

		//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		$user_info_applicant['group_certificates_type_unify'] = $attr_certificates_type_taipingyang_group_unify[$user_info_applicant['group_certificates_type']];
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
		$assured_info = $user_info_applicant;
		$assured_info['type'] = 2;//add by wangcya, 20141219, 0,被保险人，1投保人,2两者身份相同
	}
	else//被保险人非投保人本人的情况
	{//new
		$assured_post = $POST['assured'][0];
			
		$assured_info['certificates_type'] = trim($assured_post['assured_certificates_type']);
		$assured_info['certificates_code'] = trim($assured_post['assured_certificates_code']);
		$assured_info['birthday'] = trim($assured_post['assured_birthday']);
		$assured_info['fullname']  = trim($assured_post['assured_fullname']);
		$assured_info['gender']  = trim($assured_post['assured_sex']);
		$assured_info['mobiletelephone'] =  trim($assured_post['assured_mobilephone']);
		$assured_info['email'] =  trim($assured_post['assured_email']);

		$assured_info['province_code'] = $assured_post['insured_province_code'];
		$assured_info['province'] = $assured_post['insured_province'];
		
		$assured_info['city_code'] = $assured_post['insured_city_code'];
		$assured_info['city'] = $assured_post['insured_city'];
		
		////////////////////////////////////////////////////////////
		$assured_info['occupationClassCode'] = $assured_post['insured_occupationClassCode'];
		

		$assured_info['zipcode'] = $assured_post['insured_zipcode'];
		$assured_info['address'] = $assured_post['insured_address'];
		
		//add by wangcya, 20141219 ,不同厂商的证件号码和性别
		$assured_info['certificates_type_unify'] = $attr_certificates_type_taipingyang_single_unify[$assured_info['certificates_type']];
		$assured_info['gender_unify'] = $attr_sex_taipingyang_unify[$assured_info['gender']];
		$assured_info['type'] = 0;//0,被保险人，1投保人,2两者身份相同
		$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别
		
	}

	////////////////////////////////////////////////////////////////////
	$global_info['beneficiary'] = $POST['beneficiary'];//受益人，一般为法定。
	////////////////////////////////////////////////////////////////////

	$product_id = intval($POST['product_id']);//comment by wangcya, 20141017, 这里很关键的，是产品信息。
	
	ss_log("check input, post product_id: ".$product_id);
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


//检查太平洋的输入的函数,批量投保方式
function input_check_taipingyang_bat($POST)
{
	global $_SGLOBAL,$attr_certificates_type_pingan_single_unify,$attr_certificates_type_pingan_group_unify,$attr_sex_pingan_unify;
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	$global_info = array();
	
	$agent_uid = $_SGLOBAL['supe_uid'];//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	//////////////////有效性检查//////////////////////////////////////////////////////
	if(	!isset($POST['applyNum'])||
		!isset($POST['totalModalPremium'])||
		!isset($POST['totalModalPremium_single'])||
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
	ss_log("in input_check_taipingyang_bat, post applyNum".$POST['applyNum']);
	ss_log("in input_check_taipingyang_bat, post totalModalPremium: ".$POST['totalModalPremium']);
	ss_log("in input_check_taipingyang_bat, post totalModalPremium_single: ".$POST['totalModalPremium_single']);
	
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
	
	if($businessType == 1)//个人
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
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	}
	
	/////////////////////////////////////////////////////////
	//团体投保，投保人和被保险人之间是什么关系呢? 应该是"其他"的关系
	$relationshipWithInsured = 6;//其他
	//$relationshipWithInsured = 1;
	//$relationshipWithInsured = trim($POST['relationshipWithInsured']);//（非空）与投保人关系。1:本人 2:配偶 3 :父子 4:父女 5:受益人 6:被保人 7:投保人 A:母子 B:母女 C:兄弟 D:姐弟 G:祖孙 H:雇佣 I:子女 9:其他 8:转换不详
	////////////////投保人的身份信息////////////////////////////////
	//////////////////首先根据被保险人身份证到用户表中查询，如果查到则进行信息更新，如果没查到插入新纪录

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
	
	if($real_apply_num > 3000 )
	{
		showmessage("最大投保人数为3000人！".$real_apply_num);
		exit(0);
	}
	
	//$global_info['applyNum'] = $real_apply_num;
	ss_log("in input_check_taipingyang_bat, global_info applyNum: ".$real_apply_num);
	
	/////////////////////////////////////////////////////////////////////
		
	$policy_attr = array();
	$assured_num = 0;
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

function gen_cpic_tj_personal_accident_xml_header($policy_attr,//保单相关信息
											$user_info_applicant,//投保人信息
											$list_subject,
											$partnerCode)
{
	global $_SGLOBAL;
	$UUID = getRandOnly_Id(0);
	$DATE = date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);
	ss_log("into function ".__FUNCTION__);
	$header='<head>
		<partnerCode>'.PARTNERCODE_TJ_PROPERTY.'</partnerCode>
		<!-- 108001	submit 108002	policy query 108003	tuibao-->
		<transactionCode>108001</transactionCode>
		<messageId>'.$UUID.'</messageId>
		<transactionEffectiveDate>'.$DATE.'</transactionEffectiveDate>
		<user>'.USERNAME_TJ_PROPERTY.'</user>
		<password>'.PASSWORD_TJ_PROPERTY.'</password>
	</head>';
	return $header;
}

//太平洋天津室内工程一切险
function gen_cpic_tj_project_xml($policy_attr,//保单相关信息
											$user_info_applicant,//投保人信息
											$list_subject,
											$partnerCode)
{
	
	global $_SGLOBAL;
	$UUID = getRandOnly_Id(0);
	$DATE = date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);
	ss_log("into function ".__FUNCTION__);
	$product = $list_subject[0]['list_subject_product'][0];
	$xml_header = '<head>
		<partnerCode>'.PARTNERCODE_TJ_FAMILY_PROPERTY.'</partnerCode>
		<transactionCode>107001</transactionCode>
		<messageId>'.$UUID.'</messageId>
		<transactionEffectiveDate>'.$DATE.'</transactionEffectiveDate>
		<user>'.USERNAME_TJ_FAMILY_PROPERTY.'</user>
		<password>'.PASSWORD_TJ_FAMILY_PROPERTY.'</password>
	</head>';
	$start_date = date('YmdH',strtotime($policy_attr['start_date']));
	$end_date = date('YmdH',strtotime($policy_attr['end_date']));
	$xml_plcBase = '<plcBase>
				<plcTerminalNo>'.TERMINALNO_TJ_FAMILY_PROPERTY.'</plcTerminalNo>
				<plcBusinessNo>'.$policy_attr['order_sn'].'</plcBusinessNo>
				<plcPlanCode>'.$product['product_code'].'</plcPlanCode>
				<plcStartDate>'.$start_date.'</plcStartDate>
				<plcEndDate>'.$end_date.'</plcEndDate>
				<plcCopies>'.$policy_attr['apply_num'].'</plcCopies>
				<plcElcFlag>0</plcElcFlag>
			</plcBase>';
	$xml_applicant='<applicant>
				<apltName>'.$user_info_applicant['fullname'].'</apltName>
				<apltCretType>'.$user_info_applicant['certificates_type'].'</apltCretType>
				<apltCretCode>'.$user_info_applicant['certificates_code'].'</apltCretCode>
				<apltTelephone>'.$user_info_applicant['telephone'].'</apltTelephone>
				<apltEmail>'.$user_info_applicant['email'].'</apltEmail>
				<apltMobile>'.$user_info_applicant['mobiletelephone'].'</apltMobile>
			</applicant>';
			
	$user_info_assured_list = $list_subject[0]['list_subject_insurant'];
	$insuredList = '';
	foreach($user_info_assured_list as $key=>$value)
	{
		$insuredList = $insuredList.'<insured>
					<isrdName>'.$value['fullname'].'</isrdName>
					<isrdCretType>'.$value['certificates_type'].'</isrdCretType>
					<isrdCretCode>'.$value['certificates_code'].'</isrdCretCode>
					<isrdTelephone>'.$value['telephone'].'</isrdTelephone>
					<isrdEmail>'.$value['email'].'</isrdEmail>
					<isrdMobile>'.$value['mobiletelephone'].'</isrdMobile>
					<isrdAddress>'.$value['address'].'</isrdAddress>
				</insured>';
	}
	$xml_insuredList = '<insuredList>
				'.$insuredList.'
			</insuredList>';
			
	$xml_project = '<pconstruct>
				<!--工程名称(必须输入)-->
				<projectname>安装工程一切险</projectname>
				<!--工程造价(必须输入)-->
				<constructamount>100000</constructamount>
				<!--省代码(必须输入)-->
				<provinceCode>SHH</provinceCode>
				<!--市代码(必须输入)-->
				<cityCode>200000</cityCode>
				<!--邮编(必须输入)-->
				<zip>200070</zip>
				<!--工程地址(必须输入)-->
				<businessaddress>上海市闸北区</businessaddress>
				<!--建筑/安装起期(必须输入) -->
				<buildstartdate>20150807</buildstartdate>
				<!--建筑/安装止期(必须输入)-->
				<buildenddate>20160807</buildenddate>
				<!--工程期限月数(必须输入)-->
				<projectmonth>1.5</projectmonth>
			</pconstruct>';
			
	$xml_elcPolicy = '<elcPolicy>
						<elcMsgFlag>1</elcMsgFlag>
						<elcMobile>'.$user_info_applicant['mobiletelephone'].'</elcMobile>
						<elcEmlFlag>0</elcEmlFlag>
						<elcEmail/>
					</elcPolicy>';
			
	$xml_body = '<body>
					<entity>
						'.$xml_plcBase.'
						'.$xml_applicant.'
						'.$xml_insuredList.'
						'.$xml_project.'			
			            '.$xml_elcPolicy.'
					</entity>
				</body>';	
	$str_xml = '<?xml version="1.0" encoding="UTF-8"?>
				<request>
					'.$xml_header.'
					'.$xml_body.'
				</request>';
	return 	$str_xml;
}

function gen_cpic_tj_family_property_xml($policy_attr,//保单相关信息
											$user_info_applicant,//投保人信息
											$list_subject,
											$partnerCode)
{
	global $_SGLOBAL;
	$UUID = getRandOnly_Id(0);
	$DATE = date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);
	ss_log("into function ".__FUNCTION__);
	$product = $list_subject[0]['list_subject_product'][0];
	$xml_header = '<head>
		<partnerCode>'.PARTNERCODE_TJ_FAMILY_PROPERTY.'</partnerCode>
		<transactionCode>107001</transactionCode>
		<messageId>'.$UUID.'</messageId>
		<transactionEffectiveDate>'.$DATE.'</transactionEffectiveDate>
		<user>'.USERNAME_TJ_FAMILY_PROPERTY.'</user>
		<password>'.PASSWORD_TJ_FAMILY_PROPERTY.'</password>
	</head>';
	$start_date = date('YmdH',strtotime($policy_attr['start_date']));
	$end_date = date('YmdH',strtotime($policy_attr['end_date']));
	$xml_plcBase = '<plcBase>
				<plcTerminalNo>'.TERMINALNO_TJ_FAMILY_PROPERTY.'</plcTerminalNo>
				<plcBusinessNo>'.$policy_attr['order_sn'].'</plcBusinessNo>
				<plcPlanCode>'.$product['product_code'].'</plcPlanCode>
				<plcStartDate>'.$start_date.'</plcStartDate>
				<plcEndDate>'.$end_date.'</plcEndDate>
				<plcCopies>'.$policy_attr['apply_num'].'</plcCopies>
				<plcElcFlag>0</plcElcFlag>
			</plcBase>';
	$xml_applicant='<applicant>
				<apltName>'.$user_info_applicant['fullname'].'</apltName>
				<apltCretType>'.$user_info_applicant['certificates_type'].'</apltCretType>
				<apltCretCode>'.$user_info_applicant['certificates_code'].'</apltCretCode>
				<apltTelephone>'.$user_info_applicant['telephone'].'</apltTelephone>
				<apltEmail>'.$user_info_applicant['email'].'</apltEmail>
				<apltMobile>'.$user_info_applicant['mobiletelephone'].'</apltMobile>
			</applicant>';
	
	
	$user_info_assured_list = $list_subject[0]['list_subject_insurant'];
	$insuredList = '';
	foreach($user_info_assured_list as $key=>$value)
	{
		$insuredList = $insuredList.'<insured>
					<isrdName>'.$value['fullname'].'</isrdName>
					<isrdCretType>'.$value['certificates_type'].'</isrdCretType>
					<isrdCretCode>'.$value['certificates_code'].'</isrdCretCode>
					<isrdTelephone>'.$value['telephone'].'</isrdTelephone>
					<isrdEmail>'.$value['email'].'</isrdEmail>
					<isrdMobile>'.$value['mobiletelephone'].'</isrdMobile>
					<isrdAddress>'.$value['address'].'</isrdAddress>
				</insured>';
	}
	$xml_insuredList = '<insuredList>
				'.$insuredList.'
			</insuredList>';
			
	$xml_addressList = '<addressList>
				<address>
					<adsCity>300000</adsCity>
					<adsPostCode>'.$value['zipcode'].'</adsPostCode>
					<adsAddress>'.$value['address'].'</adsAddress>
				</address>
			</addressList>';
			
	$xml_elcPolicy = '<elcPolicy>
						<elcMsgFlag>1</elcMsgFlag>
						<elcMobile>'.$user_info_applicant['mobiletelephone'].'</elcMobile>
						<elcEmlFlag>0</elcEmlFlag>
						<elcEmail/>
					</elcPolicy>';
			
	$xml_body = '<body>
					<entity>
						'.$xml_plcBase.'
						'.$xml_applicant.'
						'.$xml_insuredList.'
						'.$xml_addressList.'			
			            '.$xml_elcPolicy.'
					</entity>
				</body>';	
	$str_xml = '<?xml version="1.0" encoding="UTF-8"?>
				<request>
					'.$xml_header.'
					'.$xml_body.'
				</request>';
	return 	$str_xml;
}
function gen_cpic_tj_personal_accident_xml_body($policy_attr,//保单相关信息
											$user_info_applicant,//投保人信息
											$list_subject)
{
	ss_log("into function ".__FUNCTION__);
	$product = $list_subject[0]['list_subject_product'][0];

	$businessType = ($policy_attr['business_type'] == 1) ? "S":"G";
	$policy_basic_info='<PolicyBaseInfo>
				<!--judge weather repeat submit policy-->
				<uniqueFlag>'.$policy_attr['policy_id'].'</uniqueFlag>
				<terminalNo>'.TERMINALNO_TJ_PROPERTY.'</terminalNo>
                 <planCode>'.$product['product_code'].'</planCode>
                 <!--S gedan，G tuandan-->
				<groupInsuranceFlag>'.$businessType.'</groupInsuranceFlag>
				<!--print type, 0 dianzi   1	zhizhi   3	qianfa-->
				<billType>0</billType>
				<!--num-->
				<coverageCopies>'.$policy_attr['apply_num'].'</coverageCopies>
				<billNo></billNo>
				<startDate>'.$policy_attr['start_date'].'</startDate>
				<endDate>'.$policy_attr['end_date'].'</endDate>
				<sumInsured/>
				<policyPremium/>
			</PolicyBaseInfo>';
	
	if($policy_attr['business_type'] == 1)//single
	{
		$certificates_type = $user_info_applicant['certificates_type'];
		$certificates_code = $user_info_applicant['certificates_code'];
	}
	else//group
	{
		$user_info_applicant['fullname'] = $user_info_applicant['group_name'];
		$certificates_type = $user_info_applicant['group_certificates_type'];
		$certificates_code = $user_info_applicant['group_certificates_code'];
	}
	//如果是团单的情况
	if($policy_attr['business_type'] == 2)
	{
		$user_info_applicant['certificates_type'] = $user_info_applicant['group_certificates_type'];
		$user_info_applicant['certificates_code'] = $user_info_applicant['group_certificates_code'];
	}
	$applicant = '<Applicant>
				<customerName>'.$user_info_applicant['fullname'].'</customerName>
				<certificateType>'.$user_info_applicant['certificates_type'].'</certificateType>
				<certificateCode>'.$user_info_applicant['certificates_code'].'</certificateCode>
				<customerGender>'.$user_info_applicant['gender'].'</customerGender>
				<customerBirthday>'.$user_info_applicant['birthday'].'</customerBirthday>
				<areaCode>'.$user_info_applicant['county_code'].'</areaCode>
				<customerIndustryType>G</customerIndustryType>
				<comAddress>'.$user_info_applicant['address'].'</comAddress>
				<mobile>'.$user_info_applicant['mobiletelephone'].'</mobile>
				<email>'.$user_info_applicant['email'].'</email>
				<customerWithTheInsured>'.$policy_attr['relationship_with_insured'].'</customerWithTheInsured>
			</Applicant>';
		$list_insurant = $list_subject[0]['list_subject_insurant'];
		$insurant='';
		$insured_index =1;
		foreach($list_insurant as $key=>$one_insurant)
		{
			$insurant .= '<Insured>
					<insuredCode>'.$insured_index.'</insuredCode>
					<customerName>'.$one_insurant['fullname'].'</customerName>
					<customerNamePingYing>'.$one_insurant['fullname_english'].'</customerNamePingYing>
					<certificateType>'.$one_insurant['certificates_type'].'</certificateType>
					<certificateCode>'.$one_insurant['certificates_code'].'</certificateCode>
					<customerGender>'.$one_insurant['gender'].'</customerGender>
					<customerBirthday>'.$one_insurant['birthday'].'</customerBirthday>
					<!--0	qita  1	fading  2	junfen  3	bili  4	shunwei-->
					<benefitWay>1</benefitWay>
				</Insured>';
			$insured_index++;
		}
		$insurant_list = '<InsuredList>
				'.$insurant.'
			</InsuredList>';
			
		$CoverageList = '<CoverageList>
			</CoverageList>';
			
		if($policy_attr['attribute_type'] == 'tj_yiwai')
		{
			
			if($policy_attr['attribute_code'] == 'low_risk')
			{
				$FactorList	='<FactorList>
				<Factor>
					<factorCode>A0004</factorCode>
					<factorValue>一类(新)</factorValue>
				</Factor>
				<Factor>
					<factorCode>K6001</factorCode>
					<factorValue></factorValue>
				</Factor>
			</FactorList>';
			}
			elseif($policy_attr['attribute_code'] == 'mid_risk')
			{
				$FactorList	='<FactorList>
				<Factor>
					<factorCode>A0006</factorCode>
					<factorValue>三类(新)</factorValue>
				</Factor>
				<Factor>
					<factorCode>K6001</factorCode>
					<factorValue></factorValue>
				</Factor>
			</FactorList>';
			}
			else
			{
				$FactorList	='<FactorList>
				<Factor>
					<factorCode>K6001</factorCode>
					<factorValue></factorValue>
				</Factor>
			</FactorList>';
			}
			
			
		}
		else
		{
			$FactorList	='';
		}		
			
		$epolicy_info = '<EPolicyInfo>
			<messageFlag>1</messageFlag>
			<electronPolicyMobile>'.$user_info_applicant['mobiletelephone'].'</electronPolicyMobile>
			<emailFlag>1</emailFlag>
			<electronPolicyEmail>'.$user_info_applicant['email'].'</electronPolicyEmail>
			<returnPDFFlag>1</returnPDFFlag>
		</EPolicyInfo>';
		
		$body = '<body>
		<PolicyApplyRequest>
			'.$policy_basic_info.'
			'.$applicant.'
			'.$insurant_list.'
			'.$CoverageList.'	
			'.$FactorList.'
			'.$epolicy_info.'
		</PolicyApplyRequest>
	</body>';//$policy_basic_info.$applicant.$insurant_list.$CoverageList.$FactorList.$epolicy_info;
	return $body;
}


function gen_post_policy_xml_cpic_tj_property($attribute_type,
										  $policy_attr,//保单相关信息
											$user_info_applicant,//投保人信息
											$list_subject,
											$partnerCode											
		                                   )
{
	global $_SGLOBAL;
	/////////////////////////////////////////////////////////////
	
	ss_log("into function ".__FUNCTION__);
	/////////////////////////////////////////////////////////////////
	$DATE = date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);
	
	$policy_id = $policy_attr['policy_id'];
	$order_sn = $policy_attr['order_sn'];

	
	//天津财险，
	if($attribute_type == 'tj_property')
	{
		$str_xml = gen_cpic_tj_family_property_xml($policy_attr,//保单相关信息
											$user_info_applicant,//投保人信息
											$list_subject,
											$partnerCode);
	}
	//室内工程一切险
	elseif($attribute_type == 'tj_project')
	{
		$str_xml = gen_cpic_tj_project_xml($policy_attr,//保单相关信息
											$user_info_applicant,//投保人信息
											$list_subject,
											$partnerCode);
	}
	else
	{//人意，君安行等。
		$header = gen_cpic_tj_personal_accident_xml_header($policy_attr,//保单相关信息
											$user_info_applicant,//投保人信息
											$list_subject,
											$partnerCode);
		$body = gen_cpic_tj_personal_accident_xml_body($policy_attr,//保单相关信息
												$user_info_applicant,//投保人信息
												$list_subject);
		$str_xml = '<?xml version="1.0" encoding="UTF-8"?>
					<request>
						'.$header.'
						'.$body.'
					</request>';
	}
	


	return $str_xml;
	
}

//start add by wangcya, 20150106 ,模块化
function gen_post_policy_xml_taipingyang(  $policy_attr,//保单相关信息
											$user_info_applicant,//投保人信息
											$list_subject,
											$partnerCode											
		                                   )
{
	global $_SGLOBAL;
	/////////////////////////////////////////////////////////////
	
	ss_log("into function gen_post_policy_xml_taipingyang");
	/////////////////////////////////////////////////////////
	$result = 1;
	$retmsg = "";
	
	/////////////////////////////////////////////////////////////////
	$DATE = date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);//20140515
	//$DATE = date('Y-m-d',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30
	
	$policy_id = $policy_attr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	//$orderNum = $policy_attr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_attr['order_sn'];
	/////////////////////////////////////////////////////////////////////////////
	$UUID = getRandOnly_Id(0);////add by wangcya, 20150326,这个要求每次在变化。
	$thirdPartyTransNo = $policy_id;//add by wangcya, 20150326,如果同一投保单，即使不同的连接（交易）则需要唯一。
	
	//activateFlag， add by wangcya, 20150326,  activateFlag 0，则只是核保，1则为（投保）承保
	
	
	ss_log("taipingyang rand gen UUID: ".$UUID);
	///////////////////////////////////////////////////////////////////////
	//$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant[fullname], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
	//$user_info_assured_fullname =  mb_convert_encoding($user_info_assured[fullname], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
	
	//$user_info_applicant[fullname] = iconv('UTF-8', 'GKB', $user_info_applicant[fullname]);
	//$user_info_assured[fullname] = iconv('UTF-8', 'GKB', $user_info_assured[fullname]);
	
	$product = $list_subject[0]['list_subject_product'][0];
	$DateTime = $DATE;
	
	$groupPolicy = $policy_attr['business_type']==1?0:1;//0个单，1团单。
	ss_log("taipingyang business_type ".$groupPolicy);
	
	if(!$groupPolicy)//single
	{
		$certificates_type = $user_info_applicant['certificates_type'];
		$certificates_code = $user_info_applicant['certificates_code'];
	}
	else//group
	{
		$user_info_applicant['fullname'] = $user_info_applicant['group_name'];
		$certificates_type = $user_info_applicant['group_certificates_type'];
		$certificates_code = $user_info_applicant['group_certificates_code'];
		$user_info_applicant['birthday'] = "1978-06-02";
	}
	
	//<groupPolicy>'.$groupPolicy.'</groupPolicy>
	
	$strxml_post_policy = '<?xml version="1.0" encoding="UTF-8"?>
	<request>
	<head>
	<partnerCode>'.$partnerCode.'</partnerCode>
	<transactionCode>8041</transactionCode>
	<messageId>'.$UUID.'</messageId>
	<transactionEffectiveDate>'.$DATE.'</transactionEffectiveDate>
	<user></user>
	<password></password>
	</head>
	
	<body>
	<!--投保信息,可重复 -->
	<application>
	<!-- 合作伙伴业务序号 -->
	<applySequenceNo>'.$UUID.'</applySequenceNo>
	<!--保单起期 -->
	<startDate>'.$policy_attr['start_date'].'</startDate>
	<endDate>'.$policy_attr['end_date'].'</endDate>
	<!--购买份数 -->
	<!--产品代码 -->
	<insurerProductCode>'.$product['product_code'].'</insurerProductCode>
	<!--产品名称 -->
	<insurerProductName>'.$product['product_name'].'</insurerProductName>
	<!--签单日期 -->
	<signedDate>'.$DATE.'</signedDate>
	<!-- 保单配送方式 -->
	<!-- <deliveryType>01</deliveryType> -->
	<!-- 是否电子保单 -->
	<isEPolicy>1</isEPolicy>
	<!-- 是否激活 -->
	<activateFlag>1</activateFlag>
	<!-- 计划代码 -->
	<planCode>'.$product['plan_code'].'</planCode>
	<!-- 计划名称 -->
	<planName>'.$product['plan_name'].'</planName>
	<!--团单：1，个单：0-->
	<groupPolicy>0</groupPolicy>
	<!--投保人 -->
	<applicant>
	<!-- 投保方标志 -->
	<!--<applicantLogo>test</applicantLogo>-->
	<!--邮箱地址 -->
	<email>'.$user_info_applicant['email'].'</email>
	<!--手机号码 -->
	<mobilePhone>'.$user_info_applicant['mobiletelephone'].'</mobilePhone>
	<!--办公电话 -->
	<businessPhone>'.$user_info_applicant['telephone'].'</businessPhone>
	<!--家庭电话 -->
	<homePhone>'.$user_info_applicant['telephone'].'</homePhone>
	<!--证件类型 -->
	<certificateType>'.$certificates_type.'</certificateType>
	<!--证件号码 -->
	<certificateCode>'.$certificates_code.'</certificateCode>
	<!--性别代码 -->
	<gender>'.$user_info_applicant['gender'].'</gender>
	<!--出生日期 -->
	<birthDate>'.$user_info_applicant['birthday'].'</birthDate>
	<!--姓名 -->
	<fullName>'.$user_info_applicant['fullname'].'</fullName>
	<!--职业类别编码 -->
	<occupationClassCode>'.$user_info_applicant['occupationClassCode'].'</occupationClassCode>
	<!--教育信息 -->
	<educationInformation>'.$user_info_applicant['educationInformation'].'</educationInformation>
	<!--地址信息 -->
	<addressInfo>
	<!--地址类型 -->
	<type>0</type>
	<!--地址 -->
	<address>'.$user_info_applicant['address'].'</address>
	<!--省,直辖市，自治区代码 -->
	<provinceCode>'.$user_info_applicant['province_code'].'</provinceCode>
	<!--省,直辖市，自治区名称 -->
	<provinceName>'.$user_info_applicant['province'].'</provinceName>
	<!--城市代码 -->
	<cityCode>'.$user_info_applicant['city_code'].'</cityCode>
	<!--城市名称 -->
	<cityName>'.$user_info_applicant['city'].'</cityName>
	<!-- 邮政编码 -->
	<postCode>'.$user_info_applicant['zipcode'].'</postCode>
	</addressInfo>
	</applicant>
	';
	
	$user_info_assured_list = $list_subject[0]['list_subject_insurant'];
	
	
	foreach($user_info_assured_list AS $key=>$value)
	{
	
		$user_info_assured = $value;
	
	
		//start add by wangcya , 201409,这是临时代码，将来要删除掉
		if($policy_attr['attribute_id'] == 13)//太平洋人身意外险A款（全国）
		{
			$user_info_assured['occupationClassCode'] = "A0004";
		}
		elseif($policy_attr['attribute_id'] == 14)//运达通-太平洋营运货车司机保险
		{
			$user_info_assured['occupationClassCode'] = "A0009";
		}
		elseif($policy_attr['attribute_id'] == 15)////太平洋人身意外险B款（全国）
		{
			$user_info_assured['occupationClassCode'] = "A0006";
		}
		elseif($policy_attr['attribute_id'] == 17)//太平洋幼儿综合保险（全国）
		{
			$user_info_assured['occupationClassCode'] = "A0004";
		}
		else
		{
			$user_info_assured['occupationClassCode'] = "A0004";
		}
		//end add by wangcya , 201409,这是临时代码，将来要删除掉
	
		//太平洋幼儿综合保险（全国）
	
		$strxml_post_policy .=
		'<!--被保人 -->
		<insured>
		';
			
		//if(!$groupPolicy)
		{
			$strxml_post_policy .=
			'  <unitCount>'.$policy_attr['apply_num'].'</unitCount>';
	
		}
	
		$strxml_post_policy .=
		'
		<!-- 计划代码 -->
		<planCode>'.$product['plan_code'].'</planCode>
		<planName>'.$product['plan_name'].'</planName>
		<initFlag>0</initFlag>
		<!--邮箱地址 -->
		<email>'.$user_info_assured['email'].'</email>
		<!--手机号码 -->
		<mobilePhone>'.$user_info_assured['mobiletelephone'].'</mobilePhone>
		<!--办公电话 -->
		<businessPhone>'.$user_info_assured['telephone'].'</businessPhone>
		<!--家庭电话 -->
		<homePhone>'.$user_info_assured['telephone'].'</homePhone>
		<!--证件类型 -->
		<certificateType>'.$user_info_assured['certificates_type'].'</certificateType>
		<!--证件号码 -->
		<certificateCode>'.$user_info_assured['certificates_code'].'</certificateCode>
		<!--性别代码 -->
		<gender>'.$user_info_assured['gender'].'</gender>
		<!--出生日期 -->
		<birthDate>'.$user_info_assured['birthday'].'</birthDate>
		<!--姓名 -->
		<fullName>'.$user_info_assured['fullname'].'</fullName>
		<!--职业类别编码 -->
		<occupationClassCode>'.$user_info_assured['occupationClassCode'].'</occupationClassCode>
		<!--教育信息 -->
		<educationInformation>'.$user_info_assured['educationInformation'].'</educationInformation>
		<!--与投保人关系 -->
		<insuredRelationCode>'.$policy_attr['relationship_with_insured'].'</insuredRelationCode>
		<!-- 驾照编号 -->
		<driverLicense></driverLicense>
		<!--地址信息 -->
		<addressInfo>
		<!--地址类型 -->
		<type>0</type>
		<!--地址 -->
		<address>'.$user_info_assured['address'].'</address>
		<!--省,直辖市，自治区代码 -->
		<provinceCode>'.$user_info_assured['province_code'].'</provinceCode>
		<!--省,直辖市，自治区名称 -->
		<provinceName>'.$user_info_assured['province'].'</provinceName>
		<!--城市代码 -->
		<cityCode>'.$user_info_assured['city_code'].'</cityCode>
		<!--城市名称 -->
		<cityName>'.$user_info_assured['city'].'</cityName>
		<!-- 邮政编码 -->
		<postCode>'.$user_info_assured['zipcode'].'</postCode>
		</addressInfo>
		</insured>';
	}
	
	ss_log("taipingyang after gen post xml");
	
	//start add by wangcya , 20141029
	if($product['product_code']=="2101700000000009")//航意险
	{
		ss_log("taipingyang 增加航意险信息到XML");
	
		$wheresql = "policy_id='$policy_id'";
		$sql = "SELECT * FROM ".tname('insurance_policy_taipingyang_yiwai_otherinfo_hangkong')." WHERE $wheresql LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$policy_yiwai_otherinfo = $_SGLOBAL['db']->fetch_array($query);
	
		if($policy_yiwai_otherinfo)
		{
	
			$policy_attr["orderDate"] = $policy_yiwai_otherinfo['orderDate'];//
			$policy_attr["flightNo"] = $policy_yiwai_otherinfo['flightNo'];//
			$policy_attr["flightFrom"] = $policy_yiwai_otherinfo['flightFrom'];//
			$policy_attr["flightTo"] = $policy_yiwai_otherinfo['flightTo'];//
			$policy_attr["takeoffDate"] = $policy_yiwai_otherinfo['takeoffDate'];//
			$policy_attr["landDate"] = $policy_yiwai_otherinfo['landDate'];
			$policy_attr["eTicketNo"] = $policy_yiwai_otherinfo['eTicketNo'];
			$policy_attr["pnrCode"] = $policy_yiwai_otherinfo['pnrCode'];
			$policy_attr["ticketAmount"] = $policy_yiwai_otherinfo['ticketAmount'];//机票金额
			$policy_attr["travelAgency"] = $policy_yiwai_otherinfo['travelAgency'];//签约旅行社
	
			$strxml_post_policy .= '
			<transportationInfo>
			<!--订票日期 2012-09-11 09:26:44-->
			<orderDate>'.$policy_attr["orderDate"].'</orderDate>
			<!--航班号或火车班次 -->
			<flightNo>'.$policy_attr["flightNo"].'</flightNo>
			<!--出发地 -->
			<flightFrom>'.$policy_attr["flightFrom"].'</flightFrom>
			<!--目标地 -->
			<flightTo>'.$policy_attr["flightTo"].'</flightTo>
			<!--出发时间 2014-04-20 00:00:00-->
			<takeoffDate>'.$policy_attr["takeoffDate"].":00".'</takeoffDate>
			<!--到达时间 2014-04-22 23:26:44-->
			<landDate>'.$policy_attr["landDate"].'</landDate>
			<!--电子票号 -->
			<eTicketNo>'.$policy_attr["eTicketNo"].'</eTicketNo>
			<!--PNR码 -->
			<pnrCode>'.$policy_attr["pnrCode"].'</pnrCode>
			<!--机票金额 -->
			<ticketAmount>'.$policy_attr["ticketAmount"].'</ticketAmount>
			<!-- 签约旅行社 -->
			<travelAgency>'.$policy_attr["travelAgency"].'</travelAgency>
			</transportationInfo>
			<!--订单 -->';
		}
		else
		{
			ss_log("policy_huatai_yiwai_otherinfo is null!");
		}
		//////////////////////////////////////////////////////////////////////
	
	
	}
	//end add by wangcya , 20141029
	
	$strxml_post_policy .= '
	<!--订单 -->
	<order>
	<!--支付信息 -->
	<payment>
	<!--银行代码 -->
	<bankCode>111111</bankCode>
	<!--交易渠道 -->
	<transChannel>1</transChannel>
	<!--交易流水号 -->
	<transNo>111</transNo>
	<!--银行账号 -->
	<account>1111</account>
	<!--第三方交易商户号 -->
	<thirdPartyTransCommerId>11111</thirdPartyTransCommerId>
	<!--第三方交易流水号 -->
	<thirdPartyTransNo>'.$thirdPartyTransNo.'</thirdPartyTransNo>
	<!--第三方支付账号 -->
	<thirdPartyAccount>11232</thirdPartyAccount>
	<!--第三方支付类型 -->
	<thirdPartyPaymentType>1</thirdPartyPaymentType>
	<!--票据号 -->
	<billNo>11111</billNo>
	<!--批次号 -->
	<batchNo>11</batchNo>
	<!--支付金额 -->
	<paymentAmount>250</paymentAmount>
	<!--支付方式 1 划卡 2 转账 3 其它，为转账时开户银行和账号不能为空。 默认1 -->
	<payType>1</payType>
	<!--到账时间 -->
	<paidDate>2012-10-12 11:15:03</paidDate>
	<!--联系人 -->
	<linkmanName>张三</linkmanName>
	<!--联系电话 -->
	<phoneNumber>13000000000</phoneNumber>
	</payment>
	</order>
	</application>
	</body>
	</request>';
	
	$strxml_post_policy = trim($strxml_post_policy);
	
	return $strxml_post_policy;
	
}

function get_policy_pdf_file_cpic_tj_property($policy_id,
												$order_sn,
												 $pdf_filename, 
												 $pdf_buffer,
												 $readfile=false )
{
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
	$logFileName = S_ROOT."xml/log/cpic_tj_property_download_policyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	
	ss_log(__FUNCTION__.",cpic property order_sn: ".$order_sn);
	ss_log(__FUNCTION__.",cpic property pdf_buffer: ".$pdf_buffer);
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
function process_return_xml_data_cpic_tj_property( $policy_attr,
													$policy_id,
							                      	$orderNum,
													$order_sn,
													$return_data)
{
	global $_SGLOBAL;
	/////////////////////////////////////////////////////////////
	ss_log("into function ".__FUNCTION__." , policy_id: ".$policy_id);
	
	$retcode  = 110;
	/////////先解析出来返回的xml//

	preg_match_all('/<partnerCode>(.*)<\/partnerCode>/isU',$return_data,$arr);
	if($arr[1])
		$partnerCode = trim($arr[1][0]);

	preg_match_all('/<transactionCode>(.*)<\/transactionCode>/isU',$return_data,$arr);
	if($arr[1])
		$transactionCode = trim($arr[1][0]);

	preg_match_all('/<messageId>(.*)<\/messageId>/isU',$return_data,$arr);
	if($arr[1])
		$messageId_ret = trim($arr[1][0]);

	preg_match_all('/<messageStatusCode>(.*)<\/messageStatusCode>/isU',$return_data,$arr);
	if($arr[1])
		$messageStatusCode = trim($arr[1][0]);

	preg_match_all('/<messageStatusSubCode>(.*)<\/messageStatusSubCode>/isU',$return_data,$arr);
	if($arr[1])
		$messageStatusSubCode = trim($arr[1][0]);

	preg_match_all('/<messageStatusSubDescription>(.*)<\/messageStatusSubDescription>/isU',$return_data,$arr);
	if($arr[1])
		$messageStatusSubDescription = trim($arr[1][0]);
	
	ss_log(__FUNCTION__.' local orderNum: '.$orderNum);
	ss_log(__FUNCTION__.' local order_sn: '.$order_sn);
	
	if($messageStatusCode == '000000' )//ok
	{
		ss_log("return success , 000000");
		///////////////////////////////////////////////////////////////////////////////
		if($partnerCode == PARTNERCODE_TJ_FAMILY_PROPERTY)//新系统，太保家财，太保建工
		{
			preg_match_all('/<plcApplyNo>(.*)<\/plcApplyNo>/isU',$return_data,$arr);
			if($arr[1])
				$applyNo = trim($arr[1][0]);
			preg_match_all('/<plcNo>(.*)<\/plcNo>/isU',$return_data,$arr);
			if($arr[1])
				$policyNo = trim($arr[1][0]);
				preg_match_all('/<plcStatus>(.*)<\/plcStatus>/isU',$return_data,$arr);
			if($arr[1])
				$policyStatus = trim($arr[1][0]);
				preg_match_all('/<plcSumInsured>(.*)<\/plcSumInsured>/isU',$return_data,$arr);
			if($arr[1])
				$sumInsured = trim($arr[1][0]);
		
			preg_match_all('/<plcPremium>(.*)<\/plcPremium>/isU',$return_data,$arr);
			if($arr[1])
				$policyPremium = trim($arr[1][0]);
				preg_match_all('/<plcEffctDate>(.*)<\/plcEffctDate>/isU',$return_data,$arr);
			if($arr[1])
				$effectiveDate = trim($arr[1][0]);
				
			if(empty($policy_id))
			{
				ss_log("policy_id is empty, so search by order_num");
				$wheresql = "order_num='$orderNum'";//del by wangcya, 20141120,根据这个找到其对应的保单存放的数据
				//$wheresql = "order_sn='$order_sn'";//add by wangcya, 20141120,
				$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
				ss_log(__FUNCTION__.", ".$sql);
				$query = $_SGLOBAL['db']->query($sql);
				$value = $_SGLOBAL['db']->fetch_array($query);
				$policy_id = $value['policy_id'];
			}
			
			ss_log(__FUNCTION__.", order_sn: ".$order_sn." policy_id: ".$policy_id);
				
			if($policy_id)//根据返回的找到了对应的保单，old
			{
				ss_log("will update policy, policyNo: ".$policyNo);
	
				updatetable(	'insurance_policy',
								array('policy_status'=>'insured','policy_no'=>$policyNo),
								array('policy_id'=>$policy_id)
								);
						
				//////////////////////////////////////////////////////////////////////
				$policy_attr['policy_no'] = $policyNo;//add by wangcya, 20141011,这里出现了BUG,导致第一次投保成功后下载电子保单失败
				$policy_attr['policy_status'] = 'insured';
				///////////////////////////////////////////////////////////////	
				$sql = "UPDATE bx_order_info SET insured_policy_num=insured_policy_num+1 WHERE order_sn=".$order_sn;
				ss_log($sql);
				$_SGLOBAL['db']->query($sql);
	
				ss_log(__FUNCTION__." 投保成功，准备发送获取电子保单请求");
	
				ss_log(__FUNCTION__." policy_no: ".$policy_attr['policy_no']);
			
				$pdf_filename = S_ROOT."xml/message/".$policyNo."_cpic_tj_property_policy.pdf";
				ss_log(__FUNCTION__." pdf policy filename: ".$pdf_filename);
				
				$policy_attr['readfile'] = false;//add by wangcya, 20150207
				$use_asyn = true;
				$result_attr = get_policyfile_cpic_tj_family_property_xml(
											                $use_asyn,
											                $policy_attr,
															$pdf_filename
															);
	
			}
				//ss_log(__FUNCTION__.", ".$retmsg);
		}
		else
		{
			preg_match_all('/<applyNo>(.*)<\/applyNo>/isU',$return_data,$arr);
			if($arr[1])
				$applyNo = trim($arr[1][0]);
			preg_match_all('/<policyNo>(.*)<\/policyNo>/isU',$return_data,$arr);
			if($arr[1])
				$policyNo = trim($arr[1][0]);
				
				
			preg_match_all('/<policyType>(.*)<\/policyType>/isU',$return_data,$arr);
			if($arr[1])
				$policyType = trim($arr[1][0]);
		
			preg_match_all('/<policyStatus>(.*)<\/policyStatus>/isU',$return_data,$arr);
			if($arr[1])
				$policyStatus = trim($arr[1][0]);
				
			preg_match_all('/<sumInsured>(.*)<\/sumInsured>/isU',$return_data,$arr);
			if($arr[1])
				$sumInsured = trim($arr[1][0]);
		
			preg_match_all('/<policyPremium>(.*)<\/policyPremium>/isU',$return_data,$arr);
			if($arr[1])
				$policyPremium = trim($arr[1][0]);
				
			preg_match_all('/<effectiveDate>(.*)<\/effectiveDate>/isU',$return_data,$arr);
			if($arr[1])
				$effectiveDate = trim($arr[1][0]);
			preg_match_all('/<billNo>(.*)<\/billNo>/isU',$return_data,$arr);
			if($arr[1])
				$billNo = trim($arr[1][0]);
			preg_match_all('/<epolicyStatus>(.*)<\/epolicyStatus>/isU',$return_data,$arr);
			if($arr[1])
				$epolicyStatus = trim($arr[1][0]);	
				
			$start = strpos($return_data, "<epolicyPDF>");
			if($start>0)
			{
				$start += strlen("<epolicyPDF>");
				$end  = strpos($return_data, "</epolicyPDF>");
				$epolicyPDF = substr($return_data, $start,$end-$start);
				ss_log(__FUNCTION__.", matched epolicyPDF=".$epolicyPDF);
			}
	
			if(empty($policy_id))
			{
				ss_log("policy_id is empty, so search by order_num");
				$wheresql = "order_num='$orderNum'";//del by wangcya, 20141120,根据这个找到其对应的保单存放的数据
				//$wheresql = "order_sn='$order_sn'";//add by wangcya, 20141120,
				$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
				ss_log(__FUNCTION__.", ".$sql);
				$query = $_SGLOBAL['db']->query($sql);
				$value = $_SGLOBAL['db']->fetch_array($query);
				$policy_id = $value['policy_id'];
			}
			
			ss_log(__FUNCTION__.", order_sn: ".$order_sn." policy_id: ".$policy_id);
				
			if($policy_id)//根据返回的找到了对应的保单，old
			{
				ss_log("will update policy, policyNo: ".$policyNo);
	
				updatetable(	'insurance_policy',
								array('policy_status'=>'insured','policy_no'=>$policyNo),
								array('policy_id'=>$policy_id)
								);
						
				//////////////////////////////////////////////////////////////////////
				$policy_attr['policy_no'] = $policyNo;//add by wangcya, 20141011,这里出现了BUG,导致第一次投保成功后下载电子保单失败
				$policy_attr['policy_status'] = 'insured';
				///////////////////////////////////////////////////////////////	
				$sql = "UPDATE bx_order_info SET insured_policy_num=insured_policy_num+1 WHERE order_sn=".$order_sn;
				ss_log($sql);
				$_SGLOBAL['db']->query($sql);
				
	
				ss_log(__FUNCTION__." 投保成功，直接获取电子保单");
				
				ss_log(__FUNCTION__." policy_no: ".$policy_attr['policy_no']);
				//end start add by wangcya , 20150107
				
				$pdf_filename = S_ROOT."xml/message/".$policyNo."_cpic_tj_property_policy.pdf";
				ss_log(__FUNCTION__." pdf policy filename: ".$pdf_filename);
				//直接从报文中获取电子保单
				$retcode  = 0;
				if($epolicyStatus == 1)//成功返回了电子保单
				{
					$result_attr = get_policy_pdf_file_cpic_tj_property($policy_id,
													$order_sn,
													$pdf_filename, 
													 $epolicyPDF,
													 false );
					if($result_attr['retcode'] == 0)
					{
						$retmsg = "投保成功，电子保单保存成功!";	
					}		
					else
					{
						$retcode = $result_attr['retcode'];
						$retmsg = $result_attr['retmsg'];
					}					 
					
				}
				else
				{
					$retmsg = "投保成功，但还没有成功生成电子保单内容!";
				}
	
				
				ss_log(__FUNCTION__.", ".$retmsg);
				
			}
			else
			{
				///////////////////////////////////////////////////////////////
				$retcode  = 120;
				$retmsg = "post policy success! but not find policy by order_sn: ".$order_sn;
		
				ss_log(__FUNCTION__.", ".$retmsg);
		
			}
		}
		
			

	}
	else
	{//not 000000

		$retcode = $messageStatusCode;
		$retmsg = $messageStatusSubDescription;

		ss_log(__FUNCTION__." before tranfer,将要保存保单的返回信息！".$retmsg );
		
		//下层的java都进行了处理，是好像都是GBK的，包括太平洋，所以要转换。
		$retmsg =  mb_convert_encoding($retmsg, "UTF-8","GBK" ); //已知原编码为GBK, 转换为UTF-8
		
		ss_log(__FUNCTION__." after tranfer,将要保存保单的返回信息！".$retmsg );
	}
	
	$result_attr = array('retcode'=>$retcode,
						 'retmsg'=> $retmsg,
						 'policy_no'=>$policyNo//
						);
	
	return $result_attr;
}

//处理太平洋的返回数据
function process_return_xml_data_taipingyang(
					                        $policy_id,
					                      	$orderNum,
											$order_sn,
											$return_data,
											$strxml_post_policy_content
										    )
{
	global $_SGLOBAL;
	/////////////////////////////////////////////////////////////
	ss_log("into function ".__FUNCTION__." , policy_id: ".$policy_id);
	
	$retcode  = 110;
	/////////先解析出来返回的xml//////////////////////////////////

	preg_match_all('/<partnerCode>(.*)<\/partnerCode>/isU',$return_data,$arr);
	if($arr[1])
		$partnerCode = trim($arr[1][0]);

	preg_match_all('/<transactionCode>(.*)<\/transactionCode>/isU',$return_data,$arr);
	if($arr[1])
		$transactionCode = trim($arr[1][0]);

	preg_match_all('/<messageId>(.*)<\/messageId>/isU',$return_data,$arr);
	if($arr[1])
		$messageId_ret = trim($arr[1][0]);

	preg_match_all('/<messageStatusCode>(.*)<\/messageStatusCode>/isU',$return_data,$arr);
	if($arr[1])
		$messageStatusCode = trim($arr[1][0]);

	preg_match_all('/<messageStatusSubCode>(.*)<\/messageStatusSubCode>/isU',$return_data,$arr);
	if($arr[1])
		$messageStatusSubCode = trim($arr[1][0]);

	preg_match_all('/<messageStatusSubDescription>(.*)<\/messageStatusSubDescription>/isU',$return_data,$arr);
	if($arr[1])
		$messageStatusSubDescription = trim($arr[1][0]);
	
	///////////////////////////////////////////////////////////////////////////
		
	ss_log('taiping yang messageStatusCode: '.$messageStatusCode);
	ss_log('taiping yang messageStatusSubCode: '.$messageStatusSubCode);
	ss_log("messageStatusSubDescription: ".$messageStatusSubDescription);
	
	//ss_log('taiping yang messageId_ret: '.$messageId_ret.' local UUID: '.$UUID);
	ss_log('local orderNum: '.$orderNum);
	ss_log('local order_sn: '.$order_sn);
	
	if($messageStatusCode=='000000'
	   //&&$messageId_ret == $UUID//del by wangcya , 20150113, 因为异步处理，这个不要了，这两个必须相等
	)//ok
	{
			
		ss_log("return success , 000000");
		///////////////////////////////////////////////////////////////////////////////
		preg_match_all('/<applicationNo>(.*)<\/applicationNo>/isU',$return_data,$arr);
		if($arr[1])
			$applicationNo = trim($arr[1][0]);
			
			
		preg_match_all('/<policyNo>(.*)<\/policyNo>/isU',$return_data,$arr);
		if($arr[1])
			$policyNo = trim($arr[1][0]);
			
			
		preg_match_all('/<statusCode>(.*)<\/statusCode>/isU',$return_data,$arr);
		if($arr[1])
			$statusCode = trim($arr[1][0]);
	
		preg_match_all('/<productCode>(.*)<\/productCode>/isU',$return_data,$arr);
		if($arr[1])
			$productCode = trim($arr[1][0]);
			
		preg_match_all('/<orderNo>(.*)<\/orderNo>/isU',$return_data,$arr);
		if($arr[1])
			$orderNo = trim($arr[1][0]);
	
		preg_match_all('/<orderAmount>(.*)<\/orderAmount>/isU',$return_data,$arr);
		if($arr[1])
			$orderAmount = trim($arr[1][0]);
			
		ss_log("return policyNo: ".$policyNo." order_sn: ".$order_sn);
		///////////保存到数据库中////////////////////////////
	
		ss_log("remote return orderNo: ".$orderNo);
	
		//status,保单的状态：0 保存但是未提交；1 投保成功；2 注销了，注销后不能再次提交。
			
		if(empty($policy_id))
		{
			ss_log("policy_id is empty, so search by order_num");
			$wheresql = "order_num='$orderNum'";//del by wangcya, 20141120,根据这个找到其对应的保单存放的数据
			//$wheresql = "order_sn='$order_sn'";//add by wangcya, 20141120,
			$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
			ss_log($sql);
			$query = $_SGLOBAL['db']->query($sql);
			$value = $_SGLOBAL['db']->fetch_array($query);
			$policy_id = $value['policy_id'];
		}
		
		ss_log("order_sn: ".$order_sn." policy_id: ".$policy_id);
			
		if($policy_id)//根据返回的找到了对应的保单，old
		{
			ss_log("will update policy, policyNo: ".$policyNo);
	
			//$policy_id = $value['policy_id'];
			//更改该保单状态
			//$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
			updatetable(	'insurance_policy',
							array('policy_status'=>'insured','policy_no'=>$policyNo),
							array('policy_id'=>$policy_id)
							);
					
			//////////////////////////////////////////////////////////////////////
			$policy_attr['policy_no'] = $policyNo;//add by wangcya, 20141011,这里出现了BUG,导致第一次投保成功后下载电子保单失败
			$policy_attr['policy_status'] = 'insured';
			///////////////////////////////////////////////////////////////

			//start add by wangcya, 20150325,投保成功，增加对应的订单上的投保成功的个数///////////////////
			
			$sql = "UPDATE bx_order_info SET insured_policy_num=insured_policy_num+1 WHERE order_sn=".$order_sn;
			ss_log($sql);
			$_SGLOBAL['db']->query($sql);
			//end add by wangcya, 20150325,投保成功，增加对应的订单上的投保成功的个数///////////////////
			
			//$retmsg = iconv('GB2312', 'UTF-8', $retmsg); //将字符串的编码从GB2312转到UTF-8
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			/////////////下面进行获取电子保单或者发送到救援公司///////////////////////////////////////////////////////////////////
			ss_log("will get_policy_info");
			$ret = get_policy_info($policy_id);//得到保单信息
				
			ss_log("after function get_policy_info!");
				
			$policy_attr = $ret['policy_arr'];
			$user_info_applicant = $ret['user_info_applicant'];
			$list_subject = $ret['list_subject'];//多层级一个链表
			///////////////////////////////////////////////////////////////////////////////
				
			if(1)//add by wangcya, 20150117,
			{
				///////////获取电子保单,能否得到无所谓了//////////////////////////////////////
				ss_log("投保成功，将要获取电子保单");
		
				ss_log("policy_no: ".$policy_attr['policy_no']);
				//end start add by wangcya , 20150107
				
				$pdf_filename = S_ROOT."xml/message/".$policyNo."_taipingyang_policy.pdf";
				ss_log("pdf policy filename: ".$pdf_filename);
				
				$policy_attr['readfile'] = false;//add by wangcya, 20150207
				$use_asyn = true;
				$result_attr = get_policyfile_taipingyang_yiwai_xml(
						                $use_asyn,
						                $policy_attr,
										$user_info_applicant,
										$list_subject,
										$pdf_filename
										);
			}
				
		
			//start add by wangcya, 20141213,增加救援公司接口////////////////////////////////////////////////////////////////
			
			$attribute_id = $policy_attr['attribute_id'];
			
			sendto_huanqiu_jiuyuan(
									$order_sn,
									$policy_id,
									$strxml_post_policy_content,
									$return_data,
									$attribute_id
									);

			////////////////////////////////////////////////////////////////////
			$retcode  = 0;
			$retmsg = "投保成功!";
			
			ss_log($retmsg);
		}
		else
		{
			///////////////////////////////////////////////////////////////
			$retcode  = 120;
			$retmsg = "post policy success! but not find policy by order_sn: ".$order_sn;
	
			ss_log($retmsg);
	
		}
			

	}
	else
	{//not 000000
			
		ss_log("local order_num != messageId_ret");
		ss_log("messageStatusCode error ".$messageStatusCode);
		ss_log("messageStatusSubDescription error ".$messageStatusSubDescription);
	
		//$retmsg = iconv('GB2312', 'UTF-8', $retmsg); //将字符串的编码从GB2312转到UTF-8
			
		$retcode = $messageStatusCode;
		$retmsg = $messageStatusSubDescription;

		ss_log("before tranfer,将要保存保单的返回信息！".$retmsg );
		
		//下层的java都进行了处理，是好像都是GBK的，包括太平洋，所以要转换。
		$retmsg =  mb_convert_encoding($retmsg, "UTF-8","GBK" ); //已知原编码为GBK, 转换为UTF-8
		
		ss_log("after tranfer,将要保存保单的返回信息！".$retmsg );
	}
	
	$result_attr = array('retcode'=>$retcode,
						 'retmsg'=> $retmsg,
						 'policy_no'=>$policyNo//add by wangcya , 20150107, 一定要把这个电子保单号返回
						);
	
	return $result_attr;
	
}
//end add by wangcya, 20150106 ,模块化

function sendto_huanqiu_jiuyuan(	
							    $order_sn,
								$policy_id,
								$strxml_post_policy_content,
								$return_data,
								$attribute_id
							    )
{
	global $_SGLOBAL;
	$ret = false;
	/////////////////////////////////////////////////////////////
	ss_log("环球救援，into function: ".__FUNCTION__);
	
	if(empty($strxml_post_policy_content))
	{
		ss_log("strxml_post_policy_content is null, so return");
		return false;
	}
	
	if(empty($return_data))
	{
		ss_log("strxml_post_policy_content_ret is null, so return");
		return false;
	}
	
	/////////////////////////////////////////////////////////////
	$wheresql = "attribute_id='$attribute_id'";
	$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql LIMIT 1";
	ss_log($sql);
	
	$query = $_SGLOBAL['db']->query($sql);
	$product_attribute_arr = $_SGLOBAL['db']->fetch_array($query);
	
	$insurer_code = $product_attribute_arr['insurer_code'];
	$attribute_type = $product_attribute_arr['attribute_type'];
	$attribute_code = $product_attribute_arr['attribute_code'];
	
	ss_log("attribute_code: ".$attribute_code);
	ss_log("insurer_code: ".$insurer_code);

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if($insurer_code == "TBC01")//太平洋
	{
		ss_log("attribute_code: ".$attribute_code);
		
		if( $attribute_code =="BYTPA_000013"||//太平洋人身意外险A款（全国）
			$attribute_code =="BYTPA_000014"||//运达通-太平洋营运货车司机保险
			$attribute_code =="BYTPA_000015"||//太平洋人身意外险B款（全国）
			$attribute_code =="BYTPA_000017"//太平洋幼儿综合保险（全国）"
		)
		{
		
			ss_log("投保成功，将要发送到救援公司，attribute_code: ".$attribute_code." policy_id: ".$policy_id);
			$ret = taipingyang_send_message_to_jiuyuan(
					$attribute_code,
					$order_sn,
					$policy_id,
					$strxml_post_policy_content,
					$return_data
			);
		}
	}//end $insurer_code
	
	//end add by wangcya, 20141213,增加救援公司接口//

	return $ret;
}
//added by zhangxi, 20150623, 增加太平洋天津财险,投保
function post_policy_cpic_tj_property($attribute_type,
										$attribute_code,
										$policy_attr,
										$user_info_applicant,
										$list_subject)
{
	global $_SGLOBAL, $java_class_name_taipingyang,$use_test_environmental;
	//////////////////////////////////////////////////////////////////////////////////////
	ss_log("into function ".__FUNCTION__);
	
	$policy_id = $policy_attr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	$orderNum = $policy_attr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_attr['order_sn'];

	////////////////////合作伙伴的代码，中天的，在后台定义到产品属性上。///////////////////////////////////
	if($attribute_type == 'tj_property')
	{
		$partnerCode = PARTNERCODE_TJ_FAMILY_PROPERTY;
	}
	else
	{
		$partnerCode = empty($policy_attr['partner_code'])?PARTNERCODE_TJ_PROPERTY:$policy_attr['partner_code'];
	}
	
	
	$strxml_post_policy_content = gen_post_policy_xml_cpic_tj_property(
															$attribute_type,
															$policy_attr,//保单相关信息
															$user_info_applicant,//投保人信息
															$list_subject,
															$partnerCode
															);
	
	if(!empty($strxml_post_policy_content))
	{
		//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_tj_property_policy_post.xml";
		$af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_cpic_tj_property_policy_post.xml");
		ss_log(__FUNCTION__.", ".$af_path);
		file_put_contents($af_path,$strxml_post_policy_content);

		//把携带序列号的也保存起来
		$FILE_UUID = getRandOnly_Id(0,1);
		//$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_cpic_tj_property_policy_post.xml";
		$af_path_serial = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_".$FILE_UUID."_cpic_tj_property_policy_post.xml");
		ss_log(__FUNCTION__.", ".$af_path_serial);
		file_put_contents($af_path_serial,$strxml_post_policy_content);

		ss_log(__FUNCTION__." taipingyang will create_java_obj");
		//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
		if(!defined('USE_ASYN_JAVA'))
		{
			$java_class_name = "com.yanda.imsp.util.ins.CPICPolicyFecher";
		}
		else
		{
			$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
		}
		//end add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
		
		
		$obj_java = create_java_obj($java_class_name);//难道被调用生成多次导致阻塞。？？？
		////////////////////////////////////////////////////////////////////
		//$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_tj_property_policy_post_ret.xml";
		$ret_af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_cpic_tj_property_policy_post_ret.xml");
		$url = URL_POST_POLICY_TAIPINGYANG_TJ_PROPERTY;
		ss_log(__FUNCTION__." taipingyang url: ".$url);
		$logFileName = S_ROOT."xml/log/cpic_tj_property_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";	
		if(!defined('USE_ASYN_JAVA'))//同步方式
		{//同步方式
			//进行投保的工作
			ss_log(__FUNCTION__." taipingyang will java applyPolicy");
			$strxml_post_policy_ret = (string)$obj_java->applyPolicy( $url , 
																		$strxml_post_policy_content, 
																		$partnerCode,
																		keystore_FileName_TJ_PROPERTY,
																		keystore_Password_TJ_PROPERTY,
																		$logFileName );
			if(!empty($strxml_post_policy_ret))
			{			
				ss_log(__FUNCTION__." taipingyang will process return_data");
		
				ss_log($ret_af_path);
				
				file_put_contents($ret_af_path,$strxml_post_policy_ret);
					
				//////////////////////////////////////////////////////////////////////////
				$result_attr = process_return_xml_data_cpic_tj_property(
											                        $policy_id,
											                      	$orderNum,
																	$order_sn,
																	$strxml_post_policy_ret,
																	$strxml_post_policy_content
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
		//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
		else
		{
			//////////////////////////////////////////
			ss_log(__FUNCTION__." 将要发送投保的异步请求，will doService");

				
			$type = "insure";
			$insurer_code = $policy_attr['insurer_code'];
			$callBackURL = CALLBACK_URL;
				
			
			ss_log(__FUNCTION__." policy_id: ".$policy_id);
			ss_log(__FUNCTION__." type: ".$type);
			ss_log(__FUNCTION__." insurer_code: ".$insurer_code);
			ss_log(__FUNCTION__." callBackURL: ".$callBackURL);

			$xmlContent = "";//$strxml_post_policy_content;
			$postXmlFileName = $af_path;
			$postXmlEncoding = "UTF-8";
			$respXmlFileName = $ret_af_path;
			$logFileName = S_ROOT."xml/log/cpic_tj_property_postpolicy_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
			
			ss_log(__FUNCTION__." logFileName: ".$logFileName);
				
			$keyFile ="";
			$port ="";
						
			$param_other = array(   
					"url"=>$url ,//投保保险公司服务地址
					"xmlContent"=>$xmlContent ,//投保报文内容
					"postXmlFileName"=>$postXmlFileName,//投保报文文件
					"postXmlEncoding"=>$postXmlEncoding ,//投保报文文件编码格式
					"respXmlFileName"=>$respXmlFileName,//返回报文保存全路径
					"logFileName"=>$logFileName, //日志文件
					"partnerCode"=>$partnerCode,
					"keystoreFileName" =>keystore_FileName_TJ_PROPERTY,
					"keystorePassword" =>keystore_Password_TJ_PROPERTY
					
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
		
	}//empty($strxml)
	else
	{
		ss_log(__FUNCTION__." cpic tianjin property gen strxml null");

	}
	
	return $result_attr;
}

//进行投保的动作
function post_policy_taipingyang_yiwai(
		$attribute_type,//add by wangcya, 20141213
		$attribute_code,//add by wangcya, 20141213
		$policy_attr,
		$user_info_applicant,
		$list_subject
		)
{

	global $_SGLOBAL, $java_class_name_taipingyang,$use_test_environmental;
	//////////////////////////////////////////////////////////////////////////////////////
	ss_log("into function post_policy_taipingyang_yiwai");
	
	$policy_id = $policy_attr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	$orderNum = $policy_attr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_attr['order_sn'];

	////////////////////合作伙伴的代码，中天的，在后台定义到产品属性上。///////////////////////////////////
	$partnerCode = empty($policy_attr['partner_code'])?"U9Y":$policy_attr['partner_code'];
	
	//iconv('UTF-8', 'GKB', $requestParam);
	//$strxml =  mb_convert_encoding($strxml, "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
	
	
	$strxml_post_policy_content = gen_post_policy_xml_taipingyang($policy_attr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject,
									$partnerCode
									);
	
	if(!empty($strxml_post_policy_content))
	{
		//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_post.xml";
		$af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_taipingyang_policy_post.xml");
		ss_log($af_path);
		file_put_contents($af_path,$strxml_post_policy_content);

		//start add by wangcya, 20150209,把携带序列号的也保存起来
		$FILE_UUID = getRandOnly_Id(0,1);
		//$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_taipingyang_policy_post.xml";
		$af_path_serial = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_".$FILE_UUID."_taipingyang_policy_post.xml");
		ss_log($af_path_serial);
		file_put_contents($af_path_serial,$strxml_post_policy_content);
		//end add by wangcya, 20150209,把携带序列号的也保存起来
		
		ss_log("taipingyang will create_java_obj");
		////////////////////////////////////////////////////////////////////
		$java_class_name = 'com.yanda.imsp.util.ins.CPICPolicyFecher';
		
		//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
		if(!defined('USE_ASYN_JAVA'))
		{
			$java_class_name = "com.yanda.imsp.util.ins.CPICPolicyFecher";
		}
		else
		{
			$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
		}
		$java_class_name = empty($java_class_name)?$java_class_name_taipingyang:$java_class_name;
		//end add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
		
		
		$obj_java = create_java_obj($java_class_name);//难道被调用生成多次导致阻塞。？？？
		////////////////////////////////////////////////////////////////////
		//$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_post_ret.xml";
		$ret_af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_taipingyang_policy_post_ret.xml");
		$url = URL_POST_POLICY_TAIPINGYANG;
		ss_log(__FUNCTION__.",taipingyang URL_POST_POLICY_TAIPINGYANG: ".$url);
		
		if(!defined('USE_ASYN_JAVA'))//add by wangcya, 20150112,for bug[203],启用同步方式和java通讯
		{//同步方式
			//进行投保的工作
			ss_log("taipingyang will java applyPolicy");
			$strxml_post_policy_ret = (string)$obj_java->applyPolicy( $url , $strxml_post_policy_content, $partnerCode );
			if(!empty($strxml_post_policy_ret))
			{			
				ss_log("taipingyang will process return_data");
			
			
				
				ss_log($ret_af_path);
				
				file_put_contents($ret_af_path,$strxml_post_policy_ret);
					
				//////////////////////////////////////////////////////////////////////////
				$result_attr = process_return_xml_data_taipingyang(
											                        $policy_id,
											                      	$orderNum,
																	$order_sn,
																	$strxml_post_policy_ret,
																	$strxml_post_policy_content
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
		//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
		else
		{
			//////////////////////////////////////////
			ss_log("将要发送投保的异步请求，will doService");

				
			$type = "insure";
			$insurer_code = $policy_attr['insurer_code'];
			$callBackURL = CALLBACK_URL;
				
			
			ss_log("policy_id: ".$policy_id);
			ss_log("type: ".$type);
			ss_log("insurer_code: ".$insurer_code);
			ss_log("callBackURL: ".$callBackURL);
			/*
			 private String url;
			private String port;
			private String xmlContent;
			private String respXmlFileName;
			private String logFileName;
			private String postXmlFileName;
			private String postXmlEncoding;
			private String keyFile;
			*/

			$xmlContent = "";//$strxml_post_policy_content;
			$postXmlFileName = $af_path;
			$postXmlEncoding = "UTF-8";
			$respXmlFileName = $ret_af_path;
			$logFileName = S_ROOT."xml/log/taipingyang_postpolicy_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
			
			ss_log("logFileName: ".$logFileName);
				
			$keyFile ="";
			$port ="";
						
			$param_other = array(   
					"url"=>$url ,//投保保险公司服务地址
					//"port"=>$port ,//端口
					"xmlContent"=>$xmlContent ,//投保报文内容
					"postXmlFileName"=>$postXmlFileName,//投保报文文件
					"postXmlEncoding"=>$postXmlEncoding ,//投保报文文件编码格式
					"respXmlFileName"=>$respXmlFileName,//返回报文保存全路径
					"logFileName"=>$logFileName, //日志文件
					"partnerCode"=>$partnerCode//
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
		//end add by wangcya, 20150112,for bug[203],启用异步方式和java通讯

		//echo $strxml;
	}//empty($strxml)
	else
	{
		ss_log("taipingyang gen strxml null");

	}
	
	return $result_attr;

}//taipingyang

function gen_xml_getpolicyfile_cpic_tj_property($policy_attr,
												$user_info_applicant										  
												)
{
	global $_SGLOBAL;
	$UUID = getRandOnly_Id(0);
	$DateTime = $DATE = date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);
	$xml = '<?xml version="1.0" encoding="UTF-8"?>
<request>
	<head>
		<partnerCode>'.PARTNERCODE_TJ_PROPERTY.'</partnerCode>
		<transactionCode>108005</transactionCode>
		<messageId>'.$UUID.'</messageId>
		<transactionEffectiveDate>'.$DateTime.'</transactionEffectiveDate>
		<user>'.USERNAME_TJ_PROPERTY.'</user>
		<password>'.PASSWORD_TJ_PROPERTY.'</password>
	</head>
	<body>
	<epolicyInfoReprint>
	     <messageFlag>1</messageFlag>
	     <emailFlag>1</emailFlag>
	     <policyNo>'.$policy_attr['policy_no'].'</policyNo>
         <returnPDFFlag>1</returnPDFFlag>
</epolicyInfoReprint>
	</body>
</request>
';
	return $xml;
}
function gen_xml_getpolicyfile_cpic_tj_family_property(   $policy_attr								 
														)
{
	global $_SGLOBAL;
	//////////////////////////////////////////////////////////////
	ss_log("into function: ".__FUNCTION__);
	
	$result = 1;
	
	$UUID = getRandOnly_Id(0);//这个要求每次在变化。
	
	ss_log(__FUNCTION__.", taipingyang   get policy file ,rand gen UUID: ".$UUID);
	
	/////////////////////////////////////////////////////////////////
	$DateTime = $DATE = date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30
	
	$policy_id = $policy_attr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	
	$orderNum = $policy_attr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_attr['order_sn'];
	$policyNo = $policy_attr['policy_no'];
	
	$business_type = $policy_attr['business_type'];
	
	$business_type = 1;

	ss_log("taipingyang  get policy file order_sn: ".$order_sn." policyNo: ".$policyNo);
	
	////////////////////////////////////////////////////////////
	$xml_get_policy = '<?xml version="1.0" encoding="UTF-8"?>
	<request>
	<head>
		<partnerCode>'.PARTNERCODE_TJ_FAMILY_PROPERTY.'</partnerCode>
		<transactionCode>107003</transactionCode>
		<messageId>'.$UUID.'</messageId>
		<transactionEffectiveDate>'.$DATE.'</transactionEffectiveDate>
		<user>'.USERNAME_TJ_FAMILY_PROPERTY.'</user>
		<password>'.PASSWORD_TJ_FAMILY_PROPERTY.'</password>
	</head>
	<body>
	<entity>
	<plcNo>'.$policyNo.'</plcNo>
	<elcMsgFlag></elcMsgFlag>
	<elcMobile></elcMobile>
	<elcEmlFlag></elcEmlFlag>
	<elcEmail></elcEmail>
	<returnPDF>1</returnPDF>
	</entity>
	</body>
	</request>';
	
	$xml_get_policy = trim($xml_get_policy);
	
	return $xml_get_policy;
		
}
function gen_xml_getpolicyfile_taipingyang(   $policy_attr,
                                  $user_info_applicant,
								  $list_subject,
								  $partnerCode								 
								)
{
	global $_SGLOBAL;
	//////////////////////////////////////////////////////////////
	ss_log("into function: ".__FUNCTION__);
	
	$result = 1;
	
	$UUID = getRandOnly_Id(0);//这个要求每次在变化。
	
	ss_log("taipingyang   get policy file ,rand gen UUID: ".$UUID);
	
	/////////////////////////////////////////////////////////////////
	$DateTime = $DATE = date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30
	
	$policy_id = $policy_attr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	
	$orderNum = $policy_attr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_attr['order_sn'];
	$policyNo = $policy_attr['policy_no'];
	
	$business_type = $policy_attr['business_type'];
	
	$business_type = 1;
	//个单的时候填写被保险人，团单的时候填写投保人。
	if($business_type ==1)//single
	{
		//$certificates_type = $user_info_applicant['certificates_type'];
		//$certificates_code = $user_info_applicant['certificates_code'];
		$user_info_assured_list = $list_subject[0]['list_subject_insurant'];
		$user_info_assured = $user_info_assured_list[0];
	
		$certificates_type = $user_info_assured['certificates_type'];
		$certificates_code = $user_info_assured['certificates_code'];
	}
	elseif($business_type ==2)
	{
		$certificates_type = $user_info_applicant['group_certificates_type'];
		$certificates_code = $user_info_applicant['group_certificates_code'];
	}
	
	ss_log("taipingyang  get policy file order_sn: ".$order_sn." policyNo: ".$policyNo);
	
	////////////////////////////////////////////////////////////
	$xml_get_policy = '<?xml version="1.0" encoding="UTF-8"?>
	<request>
	<!--报文头-->
	<head>
	<!--合作伙伴编码-->
	<partnerCode>'.$partnerCode.'</partnerCode>
	<!--业务交易码-->
	<transactionCode>8014</transactionCode>
	<!--对方交易流水号,不同商户必须唯一，itx会校验-->
	<messageId>'.$UUID.'</messageId>
	<!--交易时间，当天日期，itx会校验-->
	<transactionEffectiveDate>'.$DateTime.'</transactionEffectiveDate>
	<!--用户-->
	<user>taobao</user>
	<!--密码-->
	<password>123456</password>
	</head>
	<body>
	<policy>
	<!--保单-->
	<policyNo>'.$policyNo.'</policyNo>
	<!--投保人信息-->
	<applicant>
	<!--证件类型-->
	<certificateType>'.$certificates_type.'</certificateType>
	<!--证件编号-->
	<certificateCode>'.$certificates_code.'</certificateCode>
	</applicant>
	</policy>
	</body>
	</request>';
	
	$xml_get_policy = trim($xml_get_policy);
	
	return $xml_get_policy;
		
}
function process_return_xml_data_getpolicyfile_cpic_tj_property($policy_attr,
		                                        $return_data,
												$pdf_path,
												$obj_java)
{
	

	ss_log("into function: ".__FUNCTION__);

	preg_match_all('/<partnerCode>(.*)<\/partnerCode>/isU',$return_data,$arr);
	if($arr[1])
		$partnerCode = trim($arr[1][0]);
		
	preg_match_all('/<transactionCode>(.*)<\/transactionCode>/isU',$return_data,$arr);
	if($arr[1])
		$transactionCode = trim($arr[1][0]);
		
	preg_match_all('/<messageId>(.*)<\/messageId>/isU',$return_data,$arr);
	if($arr[1])
		$messageId_file_ret = trim($arr[1][0]);

	preg_match_all('/<messageStatusCode>(.*)<\/messageStatusCode>/isU',$return_data,$arr);
	if($arr[1])
		$messageStatusCode = trim($arr[1][0]);
		
	preg_match_all('/<messageStatusSubCode>(.*)<\/messageStatusSubCode>/isU',$return_data,$arr);
	if($arr[1])
		$messageStatusSubCode = trim($arr[1][0]);
		
	preg_match_all('/<messageStatusSubDescription>(.*)<\/messageStatusSubDescription>/isU',$return_data,$arr);
	if($arr[1])
		$messageStatusSubDescription = trim($arr[1][0]);

	if(!isset($messageStatusCode))
	{
		$result = 110;
		$retmsg = "太平洋返回报文异常";
		ss_log($retmsg);
			
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		
		return $result_attr;
	}
	
	ss_log(__FUNCTION__.", get policy file: messageStatusCode: ".$messageStatusCode);
	ss_log(__FUNCTION__.", get policy file: messageStatusSubDescription: ".$messageStatusSubDescription);
	////////////////////////////////////////////////////////////////////////////////
	if($messageStatusCode=='000000')//ok
	{
		ss_log("get plolicy ok, messageStatusCode: ".$messageStatusCode);
			
		preg_match_all('/<policyNo>(.*)<\/policyNo>/isU',$return_data,$arr);
		if($arr[1])
			$policyNo_ret = trim($arr[1][0]);

		$start = strpos($return_data, "<epolicyPDF>");
		if($start>0)
		{
			$start += strlen("<epolicyPDF>");
			$end  = strpos($return_data, "</epolicyPDF>");
			$epolicyPDF = substr($return_data, $start,$end-$start);
			ss_log(__FUNCTION__.", matched epolicyPDF=".$epolicyPDF);
		}
		////////////////////////////////////////////////////////////////////////
		if($epolicyPDF)
		{
			ss_log("will saveContent2Pdf");
			//0正常返回    -1未找到指定文件    -2数据传输错误
			$ret = $obj_java->saveContent2Pdf( (string)$epolicyPDF,(string)$pdf_path);

			$result = 0;
			$retmsg = "get pdf ret: ".$ret;
			ss_log($retmsg);
			
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
			
			if($result==0)
			{
				if (file_exists($pdf_path))//如果这个文件已经存在，则返回给用户。
				{
					$policy_id = $policy_attr['policy_id'];
					$readfile = $policy_attr['readfile'];
					////////////////////////////////////////////////////////////////////
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
					//end add by wangcya, 20150205,不直接退出，先更新状态////////////////////////////////////
					
					if($readfile)//add by wangcya, 20150205,需要读取文件
					{
							header('Content-type: application/pdf');
							readfile($pdf_path);
							exit(0);
				   }
					//////////////////////////////////////////////////////
				}
							
				$result_attr = array(	'retcode'=>$result,
						'retmsg'=> $retmsg,'retFileName'=>str_replace(S_ROOT,'',$pdf_path)
				);//add by dingchaoyang 2014-12-4添加retfilename
					
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


		}//$ePolicyPdf
		else
		{
			$result = 110;
			$retmsg = "parse ePolicyPdf error";
			ss_log($retmsg);

			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
		}
			
			

	}//000000
	else
	{
		if(empty($messageStatusCode))
		{
			$result = 110;
			$retmsg = "getpolicyfile返回为空";
			ss_log($retmsg);

			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
		}
		else
		{
			$result_attr = array(	'retcode'=>$messageStatusCode,
					'retmsg'=> $messageStatusSubDescription
			);
		}

			
	}


	return $result_attr;

	
}
//家财
function process_return_xml_data_getpolicyfile_cpic_tj(	$policy_attr,
		                                        $return_data,
												$pdf_filename
												)
{

	ss_log("into function: ".__FUNCTION__);
	/////////先解析出来返回的xml//////////////////////////////////
		
	preg_match_all('/<partnerCode>(.*)<\/partnerCode>/isU',$return_data,$arr);
	if($arr[1])
		$partnerCode = trim($arr[1][0]);
	preg_match_all('/<transactionCode>(.*)<\/transactionCode>/isU',$return_data,$arr);
	if($arr[1])
		$transactionCode = trim($arr[1][0]);
	preg_match_all('/<messageId>(.*)<\/messageId>/isU',$return_data,$arr);
	if($arr[1])
		$messageId_file_ret = trim($arr[1][0]);
	preg_match_all('/<messageStatusCode>(.*)<\/messageStatusCode>/isU',$return_data,$arr);
	if($arr[1])
		$messageStatusCode = trim($arr[1][0]);	
	preg_match_all('/<messageStatusSubCode>(.*)<\/messageStatusSubCode>/isU',$return_data,$arr);
	if($arr[1])
		$messageStatusSubCode = trim($arr[1][0]);
	preg_match_all('/<messageStatusSubDescription>(.*)<\/messageStatusSubDescription>/isU',$return_data,$arr);
	if($arr[1])
		$messageStatusSubDescription = trim($arr[1][0]);

	if(!isset($messageStatusCode))
	{
		$result = 110;
		$retmsg = "太平洋返回报文异常";
		ss_log($retmsg);
			
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		
		return $result_attr;
	}
	
	ss_log("get policy file: messageStatusCode: ".$messageStatusCode);
	ss_log("get policy file: messageStatusSubDescription: ".$messageStatusSubDescription);
	////////////////////////////////////////////////////////////////////////////////
	if($messageStatusCode=='000000')//ok
	{
		ss_log("get plolicy ok, messageStatusCode: ".$messageStatusCode);
			
		preg_match_all('/<policyNo>(.*)<\/policyNo>/isU',$return_data,$arr);
		if($arr[1])
			$policyNo_ret = trim($arr[1][0]);

		preg_match_all('/<plcElcStatus>(.*)<\/plcElcStatus>/isU',$return_data,$arr);
		if($arr[1])
			$plcElcStatus = trim($arr[1][0]);
			
		$start = strpos($return_data, "<elcPDF>");
		if($start>0)
		{
			$start += strlen("<elcPDF>");
			$end  = strpos($return_data, "</elcPDF>");
			$epolicyPDF = substr($return_data, $start,$end-$start);
			ss_log(__FUNCTION__.", matched epolicyPDF=".$epolicyPDF);
		}
		////////////////////////////////////////////////////////////////////////
		if(isset($epolicyPDF) && ($plcElcStatus == 1))
		{

			$result_attr = get_policy_pdf_file_cpic_tj_property($policy_attr['policy_id'],
											$policy_attr['order_sn'],
											$pdf_filename, 
											 $epolicyPDF,
											 false );
			if($result_attr['retcode'] == 0)
			{
				$retmsg = "投保成功，电子保单保存成功!";	
			}		
			else
			{
				$retcode = $result_attr['retcode'];
				$retmsg = $result_attr['retmsg'];
			}					 
					
		}//$ePolicyPdf
		else
		{
			$result = 110;
			$retmsg = "parse ePolicyPdf error";
			ss_log($retmsg);

			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
		}
			
	}//000000
	else
	{
		if(empty($messageStatusCode))
		{
			$result = 110;
			$retmsg = "getpolicyfile返回为空";
			ss_log($retmsg);

			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
		}
		else
		{
			$result_attr = array(	'retcode'=>$messageStatusCode,
					'retmsg'=> $messageStatusSubDescription
			);
		}

			
	}


	return $result_attr;
}


function process_return_xml_data_getpolicyfile(	$policy_attr,
		                                        $return_data,
												$pdf_path,
												$obj_java
												)
{

	ss_log("into function: ".__FUNCTION__);
	/////////先解析出来返回的xml//////////////////////////////////
		
	preg_match_all('/<partnerCode>(.*)<\/partnerCode>/isU',$return_data,$arr);
	if($arr[1])
		$partnerCode = trim($arr[1][0]);
		
	preg_match_all('/<transactionCode>(.*)<\/transactionCode>/isU',$return_data,$arr);
	if($arr[1])
		$transactionCode = trim($arr[1][0]);
		
	preg_match_all('/<messageId>(.*)<\/messageId>/isU',$return_data,$arr);
	if($arr[1])
		$messageId_file_ret = trim($arr[1][0]);

	preg_match_all('/<messageStatusCode>(.*)<\/messageStatusCode>/isU',$return_data,$arr);
	if($arr[1])
		$messageStatusCode = trim($arr[1][0]);
		
	preg_match_all('/<messageStatusSubCode>(.*)<\/messageStatusSubCode>/isU',$return_data,$arr);
	if($arr[1])
		$messageStatusSubCode = trim($arr[1][0]);
		
	preg_match_all('/<messageStatusSubDescription>(.*)<\/messageStatusSubDescription>/isU',$return_data,$arr);
	if($arr[1])
		$messageStatusSubDescription = trim($arr[1][0]);


		
	if(!isset($messageStatusCode))
	{
		$result = 110;
		$retmsg = "太平洋返回报文异常";
		ss_log($retmsg);
			
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		
		return $result_attr;
	}
	
	ss_log("get policy file: messageStatusCode: ".$messageStatusCode);
	ss_log("get policy file: messageStatusSubDescription: ".$messageStatusSubDescription);
	////////////////////////////////////////////////////////////////////////////////
	if($messageStatusCode=='000000')//ok
	{
		ss_log("get plolicy ok, messageStatusCode: ".$messageStatusCode);
			
		preg_match_all('/<policyNo>(.*)<\/policyNo>/isU',$return_data,$arr);
		if($arr[1])
			$policyNo_ret = trim($arr[1][0]);

		preg_match_all('/<certificateType>(.*)<\/certificateType>/isU',$return_data,$arr);
		if($arr[1])
			$certificateType = trim($arr[1][0]);
			
		preg_match_all('/<certificateCode>(.*)<\/certificateCode>/isU',$return_data,$arr);
		if($arr[1])
			$certificateCode = trim($arr[1][0]);

		/*
			preg_match_all('/<ePolicyPdf>(.*)<\/ePolicyPdf>/isU',$return_data,$arr);
		$ePolicyPdf = trim($arr[1][0]);*/
			
		$xml = new DOMDocument();
		$xml->loadXML($return_data);
		$root = $xml->documentElement;
		$nodes = $root->getElementsByTagName("ePolicyPdf");
		$ePolicyPdf = $nodes->item(0)->nodeValue;
			
		//ss_log("ePolicyPdf: ".$ePolicyPdf);

		////////////////////////////////////////////////////////////////////////
		if($ePolicyPdf)
		{
			ss_log("will saveContent2Pdf");
			//0正常返回    -1未找到指定文件    -2数据传输错误
			$ret = $obj_java->saveContent2Pdf( (string)$ePolicyPdf,(string)$pdf_path);

			$result = 0;
			$retmsg = "get pdf ret: ".$ret;
			ss_log($retmsg);
			
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
			
			if($result==0)
			{
			
				//usleep(1);//wait for file cache
				//ss_log("policy file: ".$filename);
				if (file_exists($pdf_path))//如果这个文件已经存在，则返回给用户。
				{
					$policy_id = $policy_attr['policy_id'];
					$readfile = $policy_attr['readfile'];
					////////////////////////////////////////////////////////////////////
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
					//end add by wangcya, 20150205,不直接退出，先更新状态////////////////////////////////////
					
					if($readfile)//add by wangcya, 20150205,需要读取文件
					{
						header('Content-type: application/pdf');
						readfile($pdf_path);
						exit(0);
				   }
					//////////////////////////////////////////////////////
				}
							
				$result_attr = array(	'retcode'=>$result,
						'retmsg'=> $retmsg,'retFileName'=>str_replace(S_ROOT,'',$pdf_path)
				);//add by dingchaoyang 2014-12-4添加retfilename
					
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


		}//$ePolicyPdf
		else
		{
			$result = 110;
			$retmsg = "parse ePolicyPdf error";
			ss_log($retmsg);

			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
		}
			
			

	}//000000
	else
	{
		if(empty($messageStatusCode))
		{
			$result = 110;
			$retmsg = "getpolicyfile返回为空";
			ss_log($retmsg);

			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
		}
		else
		{
			$result_attr = array(	'retcode'=>$messageStatusCode,
					'retmsg'=> $messageStatusSubDescription
			);
		}

			
	}


	return $result_attr;
}
//added by zhangxi, 20150630, 获取电子保单
function get_policyfile_cpic_tj_property_xml(
				                             $use_asyn,
				                             $policy_attr,
											 $user_info_applicant,
				                             $pdf_filename				
											)
{
	global $_SGLOBAL;
	ss_log("into ".__FUNCTION__);
	/////////////////////////////////////////////////////////////////////////////////
	$policy_id = $policy_attr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	$order_sn = $policy_attr['order_sn'];
	////////////////////////////////////////////////////////////////////////////////
	//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_tj_property_policy_get_pdf.xml";
	$af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_cpic_tj_property_policy_get_pdf.xml");
	ss_log(__FUNCTION__.", ".$af_path);
	
	if($policy_attr['attribute_type'] == 'tj_property'
	|| $policy_attr['attribute_type'] == 'tj_project')
	{
		$xml_get_policy = gen_xml_getpolicyfile_cpic_tj_family_property($policy_attr);
		$partnercode = PARTNERCODE_TJ_FAMILY_PROPERTY;
	}
	else
	{
		$xml_get_policy = gen_xml_getpolicyfile_cpic_tj_property($policy_attr,
															$user_info_applicant										  
															);
		$partnercode = PARTNERCODE_TJ_PROPERTY;
	}
	
														
							
	if($xml_get_policy)
	{
		file_put_contents($af_path,$xml_get_policy);
	}
	else
	{
		$result = 112;
		$retmsg = "gen_xml_getpolicyfile_taipingyang return null!";
		ss_log($retmsg);
		
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		
		
		return $result_attr;
	}
	
	////////////////////////////////////////////////////////////////////
	
	
	////////////////////////////////////////////////////////////////
	//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	if(!$use_asyn)//
	{
		$java_class_name = 'com.yanda.imsp.util.ins.CPICPolicyFecher';
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	ss_log(__FUNCTION__.", ".$java_class_name);
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
	////////////////////////////////////////////////////////////////////
	$url = URL_POST_POLICY_TAIPINGYANG_TJ_PROPERTY;
	ss_log("url: ".$url);
	
	//$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_tj_property_policy_get_pdf_ret.xml";
	$ret_af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_cpic_tj_property_policy_get_pdf_ret.xml");
	ss_log($ret_af_path);
	$logFileName = S_ROOT."xml/log/cpic_tj_property_getpolicyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	if(!$use_asyn)//同步
	{
		ss_log(__FUNCTION__.", 将使用同步过程获取太平洋电子保单");
		ss_log(__FUNCTION__.", taipingyang will applyPolicy");	
		
		$return_data = (string)$obj_java->applyPolicy( $url , 
														$xml_get_policy ,
														 $partnercode,
														 keystore_FileName_TJ_PROPERTY,
														 keystore_Password_TJ_PROPERTY,
														 $logFileName);
		if($return_data)
		{
			ss_log(__FUNCTION__.", cpic tj property return_data is not empty!");
			ss_log(__FUNCTION__.", return get policy file data:".$return_data);
		
			file_put_contents($ret_af_path,$return_data);
			////////////////////////////////////////////////////////////////////////
			
			$result_attr = process_return_xml_data_getpolicyfile_cpic_tj_property( 	 $policy_attr,//add by wangcya, 20150205
							                                                         $return_data,
									                                                 $pdf_filename , 
									                                                 $obj_java 
									                                             );
			
			return $result_attr;
			
		}
		else
		{
			$result = 110;
			$retmsg = "withdraw policy return is null!";
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
			return $result_attr;
		}//end $return_data

	
	}
	else
	{
		ss_log("将要发送获取电子保单的异步请求，will doService");
		//////////////////////////////////////////
		
		$type = "getpolicyfile";
		$insurer_code = $policy_attr['insurer_code'];
		$callBackURL = CALLBACK_URL;

		ss_log("policy_id: ".$policy_id);
		ss_log("type: ".$type);
		ss_log("insurer_code: ".$insurer_code);
		ss_log("callBackURL: ".$callBackURL);
	
		$xmlContent = "";//$strxml_post_policy_content;
		$postXmlFileName = $af_path;
		$postXmlEncoding = "UTF-8";
		$respXmlFileName = $ret_af_path;
		$logFileName = S_ROOT."xml/log/cpic_tj_property_getpolicyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
			
		
		$keyFile ="";
		$port ="";
			
		$param_other = array(
								"url"=>$url ,//投保保险公司服务地址
					"xmlContent"=>$xmlContent ,//投保报文内容
					"postXmlFileName"=>$postXmlFileName,//投保报文文件
					"postXmlEncoding"=>$postXmlEncoding ,//投保报文文件编码格式
					"respXmlFileName"=>$respXmlFileName,//返回报文保存全路径
					"logFileName"=>$logFileName, //日志文件
					"partnerCode"=>$partnercode,
					"keystoreFileName" =>keystore_FileName_TJ_PROPERTY,
					"keystorePassword" =>keystore_Password_TJ_PROPERTY
		);
		
		$jsonStr = json_encode($param_other);
		////////////////////////////////////////////////////////////////

		ss_log("param_other: ".var_export($param_other,true));
		
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
	}
	
	return $result_attr;	
}
//added by zhangxi, 20150819, 生成获取电子保单请求，并发送获取电子保单请求
function get_policyfile_cpic_tj_family_property_xml($use_asyn,
		                                      $policy_attr,
											  $pdf_path)
{
	
	global $_SGLOBAL;
	
	ss_log("into ".__FUNCTION__);
	/////////////////////////////////////////////////////////////////////////////////
	$policy_id = $policy_attr['policy_id'];
	$order_sn = $policy_attr['order_sn'];
	$partnerCode = PARTNERCODE_TJ_FAMILY_PROPERTY;
	$af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_taipingyang_policy_get_pdf.xml");
	ss_log(__FUNCTION__.", ".$af_path);
	
	//生成获取电子保单请求的xml文件
	$xml_get_policy = gen_xml_getpolicyfile_cpic_tj_family_property(	$policy_attr											  
																		);			
	if($xml_get_policy)
	{
		file_put_contents($af_path, $xml_get_policy);
	}
	else
	{
		$result = 112;
		$retmsg = "gen_xml_getpolicyfile_taipingyang return null!";
		ss_log($retmsg);
		
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		return $result_attr;
	}
	if(!$use_asyn)//!defined('USE_ASYN_JAVA'))
	{
		$java_class_name = 'com.yanda.imsp.util.ins.CPICPolicyFecher';
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}

	ss_log(__FUNCTION__.", ".$java_class_name);
	
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
	////////////////////////////////////////////////////////////////////
	$url = URL_POST_POLICY_TAIPINGYANG_TJ_PROPERTY;
	ss_log(__FUNCTION__.", taipingyang get policy file url: ".$url);
	
	//$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_get_pdf_ret.xml";
	$ret_af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_taipingyang_policy_get_pdf_ret.xml");
	ss_log(__FUNCTION__.", ".$ret_af_path);
	$logFileName = S_ROOT."xml/log/cpic_tj_getpolicyfile_java_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	if(!$use_asyn)//同步
	{
		ss_log(__FUNCTION__.", 将使用同步过程获取太平洋电子保单");
		ss_log(__FUNCTION__.", taipingyang will applyPolicy");	
		
		$return_data = (string)$obj_java->applyPolicy( $url , 
														$xml_get_policy, 
														$partnerCode,
														keystore_FileName_TJ_PROPERTY,
														keystore_Password_TJ_PROPERTY,
														$logFileName);
		 
		if($return_data)
		{
			ss_log(__FUNCTION__.", taipingyang return_data is not empty!");
			ss_log(__FUNCTION__.", return get policy file data:".$return_data);
		
			file_put_contents($ret_af_path,$return_data);
			////////////////////////////////////////////////////////////////////////
			//
			$result_attr = process_return_xml_data_getpolicyfile_cpic_tj( 	 $policy_attr,
					                                                         $return_data,
							                                                 $pdf_path  
							                                                  
							                                             );
			return $result_attr;
		}
		else
		{
			$result = 110;
			$retmsg = "withdraw policy return is null!";
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
			return $result_attr;
		}//end $return_data
	}
	else
	{
		ss_log(__FUNCTION__.", 将要发送获取电子保单的异步请求，will doService");
		//////////////////////////////////////////
		
		$type = "getpolicyfile";
		$insurer_code = $policy_attr['insurer_code'];
		$callBackURL = CALLBACK_URL;

		ss_log("policy_id: ".$policy_id);
		ss_log("type: ".$type);
		ss_log("insurer_code: ".$insurer_code);
		ss_log("callBackURL: ".$callBackURL);
	
		$xmlContent = "";//$strxml_post_policy_content;
		$postXmlFileName = $af_path;
		$postXmlEncoding = "UTF-8";
		$respXmlFileName = $ret_af_path;
		$logFileName = S_ROOT."xml/log/cpic_tj_getpolicyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
			
		
		$keyFile ="";
		$port ="";
			
		$param_other = array(
					"url"=>$url ,//投保保险公司服务地址
					"xmlContent"=>$xmlContent ,//投保报文内容
					"postXmlFileName"=>$postXmlFileName,//投保报文文件
					"postXmlEncoding"=>$postXmlEncoding ,//投保报文文件编码格式
					"respXmlFileName"=>$respXmlFileName,//返回报文保存全路径
					"logFileName"=>$logFileName, //日志文件
					"partnerCode"=>$partnerCode,
					"keystoreFileName" =>keystore_FileName_TJ_PROPERTY,
					"keystorePassword" =>keystore_Password_TJ_PROPERTY
		);
		
		
		$jsonStr = json_encode($param_other);
		////////////////////////////////////////////////////////////////

		ss_log("param_other: ".var_export($param_other,true));
		
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
		
		//return $result_attr;
	}
	
	return $result_attr;
		
	
}

//得到电子保单
function get_policyfile_taipingyang_yiwai_xml($use_asyn,
		                                      $policy_attr,
			                                  $user_info_applicant,
											  $list_subject,
											  $pdf_path
											 )
{
	global $_SGLOBAL;
	
	ss_log("into ".__FUNCTION__);
	/////////////////////////////////////////////////////////////////////////////////
	$policy_id = $policy_attr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	$order_sn = $policy_attr['order_sn'];
	$partnerCode = "U9Y";
	////////////////////////////////////////////////////////////////////////////////
	//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_get_pdf.xml";
	$af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_taipingyang_policy_get_pdf.xml");
	ss_log($af_path);
	
	$xml_get_policy = gen_xml_getpolicyfile_taipingyang(	$policy_attr,
												$user_info_applicant,
												$list_subject,
												$partnerCode											  
												);
							
	if($xml_get_policy)
	{
		file_put_contents($af_path,$xml_get_policy);
	}
	else
	{
		$result = 112;
		$retmsg = "gen_xml_getpolicyfile_taipingyang return null!";
		ss_log($retmsg);
		
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		
		
		return $result_attr;
	}
	
	////////////////////////////////////////////////////////////////////
	$java_class_name_taipingyang = 'com.yanda.imsp.util.ins.CPICPolicyFecher';
	
	////////////////////////////////////////////////////////////////
	//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	if(!$use_asyn)//!defined('USE_ASYN_JAVA'))
	{
		$java_class_name = $java_class_name_taipingyang;
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	
	$java_class_name = empty($java_class_name)?$java_class_name_taipingyang:$java_class_name;
	ss_log($java_class_name);
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
	////////////////////////////////////////////////////////////////////
	//String fileName = null;
	//$path = "/var/www/html/baoxian/xml/";
		
	////////////////////////////////////////////////////////////////////
	$url = URL_POST_POLICY_TAIPINGYANG;
	ss_log("taipingyang URL_GET_POLICY_FILE_TAIPINGYANG: ".$url);
	
	//$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_get_pdf_ret.xml";
	$ret_af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_taipingyang_policy_get_pdf_ret.xml");
	ss_log($ret_af_path);
	
	if(!$use_asyn)//同步
	{
		ss_log(__FUNCTION__.", 将使用同步过程获取太平洋电子保单");
		ss_log(__FUNCTION__.", taipingyang will applyPolicy");	
		
		$return_data = (string)$obj_java->applyPolicy( $url , $xml_get_policy , $partnerCode);
		if($return_data)
		{
			ss_log(__FUNCTION__.", taipingyang return_data is not empty!");
			ss_log(__FUNCTION__.", return get policy file data:".$return_data);
		
			file_put_contents($ret_af_path,$return_data);
			////////////////////////////////////////////////////////////////////////
			
			$result_attr = process_return_xml_data_getpolicyfile( 	 $policy_attr,//add by wangcya, 20150205
			                                                         $return_data,
					                                                 $pdf_path , 
					                                                 $obj_java 
					                                             );
			
			return $result_attr;
			
		}
		else
		{
			$result = 110;
			$retmsg = "withdraw policy return is null!";
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
			return $result_attr;
		}//end $return_data

	
	}
	else
	{
		ss_log("将要发送获取电子保单的异步请求，will doService");
		//////////////////////////////////////////
		
		$type = "getpolicyfile";
		$insurer_code = $policy_attr['insurer_code'];
		$callBackURL = CALLBACK_URL;

		ss_log("policy_id: ".$policy_id);
		ss_log("type: ".$type);
		ss_log("insurer_code: ".$insurer_code);
		ss_log("callBackURL: ".$callBackURL);
	
		$xmlContent = "";//$strxml_post_policy_content;
		$postXmlFileName = $af_path;
		$postXmlEncoding = "UTF-8";
		$respXmlFileName = $ret_af_path;
		$logFileName = S_ROOT."xml/log/taipingyang_getpolicyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
			
		
		$keyFile ="";
		$port ="";
			
		$param_other = array(
				"url"=>$url ,//投保保险公司服务地址
				//"port"=>$port ,//端口
				"xmlContent"=>$xmlContent ,//投保报文内容
				"postXmlFileName"=>$postXmlFileName,//投保报文文件
				"postXmlEncoding"=>$postXmlEncoding ,//投保报文文件编码格式
				"respXmlFileName"=>$respXmlFileName,//返回报文保存全路径
				"logFileName"=>$logFileName, //日志文件
				"partnerCode"=>$partnerCode//
		);
		
		$jsonStr = json_encode($param_other);
		////////////////////////////////////////////////////////////////

		ss_log("param_other: ".var_export($param_other,true));
		
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
		
		//return $result_attr;
	}
	
	return $result_attr;
		
}
function get_policyfile_cpic_tj_property($pdf_filename,
											$policy_attr,
											$user_info_applicant)
{
	ss_log("into ".__FUNCTION__);
	////////////////////////////////////////////////////////////////

	$use_asyn = false;
	$result_attr = get_policyfile_cpic_tj_property_xml(
							                             $use_asyn,
							                             $policy_attr,
														 $user_info_applicant,
							                             $pdf_filename				
														);
	
	////////////////////////////////////////////////////
	return $result_attr;
}

//获取电子保单
function get_policyfile_taipingyang_yiwai(	 $pdf_filename,
		                                     $policy_attr,
											 $user_info_applicant,
											 $list_subject
										 )
{

	ss_log("into ".__FUNCTION__);
	////////////////////////////////////////////////////////////////

	$use_asyn = false;
	$result_attr = get_policyfile_taipingyang_yiwai_xml(
			                             $use_asyn,
			                             $policy_attr,
										 $user_info_applicant,
										 $list_subject,
			                             $pdf_filename				
										);
	
	////////////////////////////////////////////////////
	return $result_attr;
}
//added by zhangxi, 20150625, 太平洋天津财险
function withdraw_policyfile_cpic_tj_property($policy_attr,
									 			$user_info_applicant)
{
	global $_SGLOBAL;
	//////////////////////////////////////////////////////////////
	ss_log("into function ".__FUNCTION__);
	$result = 1;
	$retmsg = "";
	/////////////////////////////////////////////////////////////////
	$DateTime = $DATE = date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30
	
	$policy_id = $policy_attr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	
	$orderNum = $policy_attr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_attr['order_sn'];
	$policyNo = $policy_attr['policy_no'];
	
	ss_log(__FUNCTION__." taipingyang withdraw order_sn: ".$order_sn." policyNo: ".$policyNo);
	
	$UUID = getRandOnly_Id(0);//这个要求每次在变化。
	
	ss_log("taipingyang withdraw,rand gen UUID: ".$UUID);
	
	$url = URL_POST_POLICY_TAIPINGYANG_TJ_PROPERTY;
	ss_log("taipingyang withdraw url: ".$url);
	
	////////////////////////////////////////////////////////////
	$xml_withdraw_policy = '<?xml version="1.0" encoding="UTF-8"?>
<request>
	<head>
		<partnerCode>'.PARTNERCODE_TJ_PROPERTY.'</partnerCode>
		<!--quan e tui bao-->
		<transactionCode>108003</transactionCode>
		<messageId>'.$UUID.'</messageId>
		<transactionEffectiveDate>'.$DateTime.'</transactionEffectiveDate>
		<user>'.USERNAME_TJ_PROPERTY.'</user>
		<password>'.PASSWORD_TJ_PROPERTY.'</password>
	</head>
	<body>
		<PolicyCancellationRequest>
			<terminalNo>'.TERMINALNO_TJ_PROPERTY.'</terminalNo>
			<PolicyCancellationBaseInfo>
				<policyNo>'.$policyNo.'</policyNo>
				<applicationReason>申请原因</applicationReason>
				<billType>0</billType>
				<billNo></billNo>
				<!--存疑-->
				<contentType>1</contentType>
			</PolicyCancellationBaseInfo>
			<Proposer>
				<customerName>'.$user_info_applicant['fullname'].'</customerName>
				<certificateType>'.$user_info_applicant['certificates_type'].'</certificateType>
				<certificateCode>'.$user_info_applicant['certificates_code'].'</certificateCode>
				<accountName></accountName>
				<accountBank></accountBank>
				<account></account>
			</Proposer>
			<EPolicyInfo>
				<messageFlag>0</messageFlag>
				<electronPolicyMobile>1234567893</electronPolicyMobile>
				<emailFlag>0</emailFlag>
				<electronPolicyEmail>email@email.com</electronPolicyEmail>
			</EPolicyInfo>
		</PolicyCancellationRequest>
	</body>
</request>';
	
	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_tj_property_policy_withdraw.xml";
	file_put_contents($af_path,$xml_withdraw_policy);
	$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_cpic_tj_property_policy_withdraw.xml";
	file_put_contents($af_path_serial,$xml_withdraw_policy);

	////////////////////////////////////////////////////////////////////
	$java_class_name = 'com.yanda.imsp.util.ins.CPICPolicyFecher';
	
	ss_log(__FUNCTION__." will create_java_obj: ".$java_class_name );
	$p = create_java_obj($java_class_name);
	////////////////////////////////////////////////////////////////////
	
	ss_log(__FUNCTION__." will withdaw applyPolicy");
	//太平洋天津财险注销接口
	$logFileName = S_ROOT."xml/log/cpic_tj_property_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	$return_data = (string)$p->applyPolicy( $url , 
											$xml_withdraw_policy , 
											PARTNERCODE_TJ_PROPERTY,
											keystore_FileName_TJ_PROPERTY,
											keystore_Password_TJ_PROPERTY,
											$logFileName	);
	ss_log(__FUNCTION__." after withdaw applyPolicy");
	if($return_data)
	{
		
		ss_log(__FUNCTION__." return_data: ".$return_data);
		
		$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_tj_property_policy_withdraw_ret.xml";
		file_put_contents($af_path,$return_data);
		/////////先解析出来返回的xml//////////////////////////////////
			//resultCode
		preg_match_all('/<partnerCode>(.*)<\/partnerCode>/isU',$return_data,$arr);
		if($arr[1])
			$partnerCode = trim($arr[1][0]);
			
		preg_match_all('/<transactionCode>(.*)<\/transactionCode>/isU',$return_data,$arr);
		if($arr[1])
			$transactionCode = trim($arr[1][0]);
			
		preg_match_all('/<messageId>(.*)<\/messageId>/isU',$return_data,$arr);
		if($arr[1])
			$messageId_ret = trim($arr[1][0]);

		preg_match_all('/<messageStatusCode>(.*)<\/messageStatusCode>/isU',$return_data,$arr);
		if($arr[1])
			$resultCode = trim($arr[1][0]);

		ss_log("withdraw: messageId_ret: ".$messageId_ret);
		
		if($resultCode=='000000'&&
			$messageId_ret == $UUID	)//ok
		{
		
			preg_match_all('/<policyNo>(.*)<\/policyNo>/isU',$return_data,$arr);
			if($arr[1])
				$policyNo_ret = trim($arr[1][0]);
			
			ss_log("policyNo_old: ".$policyNo);
			ss_log("policyNo_ret: ".$policyNo_ret);
			
			if($policyNo_ret != $policyNo)//返回保单号不等于发送的保单号
			{
				
				$result = 110;
				$retmsg = "返回保单号不等于发送的保单号,taipingyang withdraw ret policyNo: ".$policyNo_ret;
				ss_log($retmsg);
				
				$result_attr = array(	'retcode'=>$result,
										'retmsg'=> $retmsg
									);
				
				return $result_attr;//add by wangcya , 20141017
			}

			////////////////////////////////////////////////////////////////////////
			$result = 0;
			$retmsg = "taipingyang withdraw policy success!";
			ss_log($retmsg);
		}
		else
		{
			
			//start add by wangcya,20150202,有节点，但是没内容
			if(empty($resultCode))
			{//
			
				$result = 110;
				$retmsg = "太平洋返回有节点，但是节点内部无内容";
					
				ss_log(__FUNCTION__." result：".$result );
				ss_log(__FUNCTION__." 解析返回报文错误！");
				
				$result_attr = array(	'retcode'=>$result,
						'retmsg'=> $retmsg
				);
			
			}
			//end add by wangcya,20150202,有节点，但是没内容
			else
			{
				$result = $resultCode;
				$retmsg = '';
				ss_log(__FUNCTION__.", ".$retmsg);
			}
			
			
		}
	}
	else
	{
		$result = 110;
		$retmsg = "太平洋天津财险返回为空";
	
		ss_log($retmsg);
		
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
	}
	
	$result_attr = array(	'retcode'=>$result,
							'retmsg'=> $retmsg
						);
	
	return $result_attr;
}

////注销保单
function withdraw_policyfile_taipingyang_yiwai( $policy_attr,
									 			$user_info_applicant
												)
{
	global $_SGLOBAL;
	//////////////////////////////////////////////////////////////
	ss_log("into function withdraw_policyfile_taipingyang_yiwai");
	
	$partnerCode = "U9Y";
	$result = 1;
	$retmsg = "";
	/////////////////////////////////////////////////////////////////
	$DateTime = $DATE = date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30
	
	$policy_id = $policy_attr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	
	$orderNum = $policy_attr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_attr['order_sn'];
	$policyNo = $policy_attr['policy_no'];
	
	ss_log("taipingyang withdraw order_sn: ".$order_sn." policyNo: ".$policyNo);
	
	$UUID = getRandOnly_Id(0);//这个要求每次在变化。
	
	ss_log("taipingyang withdraw,rand gen UUID: ".$UUID);
	
	$url = URL_POST_POLICY_TAIPINGYANG;
	ss_log("taipingyang withdraw url: ".$url);
	
	////////////////////////////////////////////////////////////
	$xml_withdraw_policy = '<?xml version="1.0" encoding="UTF-8"?>
	<request>
	<!--报文头-->
	<head>
	<!--合作伙伴编码-->
	<partnerCode>'.$partnerCode.'</partnerCode>
	<!--业务交易码-->
	<transactionCode>8009</transactionCode>
	<!--对方交易流水号,不同商户必须唯一，itx会校验-->
	<messageId>'.$UUID.'</messageId>
	<!--交易时间，当天日期，itx会校验-->
	<transactionEffectiveDate>'.$DateTime.'</transactionEffectiveDate>
	<!--用户-->
	<user>taobao</user>
	<!--密码-->
	<password>123456</password>
	</head>
	<body>
		<policy>
			<!--保单-->
			<policyNo>'.$policyNo.'</policyNo>
			<!-- 交易时间 -->
		    <transactionDate>'.$DateTime.'</transactionDate>
			<!--退保份数-->
			<unitCount>1</unitCount>
			<!--投保人信息-->
			<applicant>
				<!--证件类型-->
				<certificateType>'.$user_info_applicant[certificates_type].'</certificateType>
				<!--证件编号-->
				<certificateCode>'.$user_info_applicant[certificates_code].'</certificateCode>
			</applicant>
			<!--退保信息-->
			<cancellation>
				<!--退保原因-->
				<cancelReason>001</cancelReason>
			</cancellation>
		</policy>
	</body>
	</request>';
	
	//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_withdraw.xml";
	$af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_taipingyang_policy_withdraw.xml");
	file_put_contents($af_path,$xml_withdraw_policy);

	//start add by wangcya, 20150209,把携带序列号的也保存起来
	//$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_taipingyang_policy_withdraw.xml";
	$af_path_serial = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_".$UUID."_taipingyang_policy_withdraw.xml");
	file_put_contents($af_path_serial,$xml_withdraw_policy);
	//end add by wangcya, 20150209,把携带序列号的也保存起来
	/////////先解析出来返回的xml//////////////////////////////////
	
	////////////////////////////////////////////////////////////////////
	$java_class_name = 'com.yanda.imsp.util.ins.CPICPolicyFecher';
	
	ss_log("will create_java_obj: ".$java_class_name );
	$p = create_java_obj($java_class_name);
	////////////////////////////////////////////////////////////////////
	
	ss_log("will withdaw applyPolicy");
	$return_data = (string)$p->applyPolicy( $url , $xml_withdraw_policy , $partnerCode	);
	ss_log("after withdaw applyPolicy");
	if($return_data)
	{
		
		ss_log("return_data: ".$return_data);
		
		//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_withdraw_ret.xml";
		$af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_taipingyang_policy_withdraw_ret.xml");
		file_put_contents($af_path,$return_data);
		/////////先解析出来返回的xml//////////////////////////////////
			
		preg_match_all('/<partnerCode>(.*)<\/partnerCode>/isU',$return_data,$arr);
		if($arr[1])
			$partnerCode = trim($arr[1][0]);
			
		preg_match_all('/<transactionCode>(.*)<\/transactionCode>/isU',$return_data,$arr);
		if($arr[1])
			$transactionCode = trim($arr[1][0]);
			
		preg_match_all('/<messageId>(.*)<\/messageId>/isU',$return_data,$arr);
		if($arr[1])
			$messageId_ret = trim($arr[1][0]);
		
		preg_match_all('/<messageStatusCode>(.*)<\/messageStatusCode>/isU',$return_data,$arr);
		if($arr[1])
			$messageStatusCode = trim($arr[1][0]);
			
		preg_match_all('/<messageStatusSubCode>(.*)<\/messageStatusSubCode>/isU',$return_data,$arr);
		if($arr[1])
			$messageStatusSubCode = trim($arr[1][0]);
			
		preg_match_all('/<messageStatusSubDescription>(.*)<\/messageStatusSubDescription>/isU',$return_data,$arr);
		if($arr[1])
			$messageStatusSubDescription = trim($arr[1][0]);
	
		ss_log("withdraw: messageId_ret: ".$messageId_ret);
		ss_log("withdraw: messageStatusCode: ".$messageStatusCode);
		ss_log("withdraw: messageStatusSubDescription: ".$messageStatusSubDescription);
		
		if($messageStatusCode=='000000'&&
			$messageId_ret == $UUID	)//ok
		{
		
			preg_match_all('/<policyNo>(.*)<\/policyNo>/isU',$return_data,$arr);
			if($arr[1])
				$policyNo_ret = trim($arr[1][0]);
			
			ss_log("policyNo_old: ".$policyNo);
			ss_log("policyNo_ret: ".$policyNo_ret);
			
			if($policyNo_ret != $policyNo)//返回保单号不等于发送的保单号
			{
				
				$result = 110;
				$retmsg = "返回保单号不等于发送的保单号,taipingyang withdraw ret policyNo: ".$policyNo_ret;
				ss_log($retmsg);
				
				$result_attr = array(	'retcode'=>$result,
										'retmsg'=> $retmsg
									);
				
				return $result_attr;//add by wangcya , 20141017
			}
	
			preg_match_all('/<certificateType>(.*)<\/certificateType>/isU',$return_data,$arr);
			if($arr[1])
				$certificateType = trim($arr[1][0]);
			
			preg_match_all('/<certificateCode>(.*)<\/certificateCode>/isU',$return_data,$arr);
			if($arr[1])
				$certificateCode = trim($arr[1][0]);
				
			/*
			preg_match_all('/<ePolicyPdf>(.*)<\/ePolicyPdf>/isU',$return_data,$arr);
			$ePolicyPdf = trim($arr[1][0]);*/
			
			/*
			$xml = new DOMDocument();
			$xml->loadXML($return_data);
			$root = $xml->documentElement;
			$nodes = $root->getElementsByTagName("ePolicyPdf");
			$ePolicyPdf = $nodes->item(0)->nodeValue;
			
			ss_log("ePolicyPdf: ".$ePolicyPdf);
			*/
			////////////////////////////////////////////////////////////////////////
			$result = 0;
			$retmsg = "taipingyang withdraw policy success!";
			ss_log($retmsg);
		}
		else
		{
			
			//start add by wangcya,20150202,有节点，但是没内容
			if(empty($messageStatusCode))
			{//平安返回为空
			
				$result = 110;
				$retmsg = "太平洋返回有节点，但是节点内部无内容";
					
				ss_log("result：".$result );
				ss_log("解析返回报文错误！");
				
				$result_attr = array(	'retcode'=>$result,
						'retmsg'=> $retmsg
				);
			
			}
			//end add by wangcya,20150202,有节点，但是没内容
			else
			{
				$result = $messageStatusCode;
				$retmsg = $messageStatusSubDescription;
				ss_log($retmsg);
			}
			
			
		}
	}
	else
	{
		$result = 110;
		$retmsg = "太平洋返回为空";
	
		ss_log($retmsg);
		
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
	}
	
	$result_attr = array(	'retcode'=>$result,
							'retmsg'=> $retmsg
						);
	
	return $result_attr;
}
$G_XLS_UPLOAD_FORMAT_CPIC_TJ_PROPERTY_TUANDAN = array(
					'1'=>'assured_name',
					'2'=>'assured_certificates_type',//被保险人证件类型
					'3'=>'assured_certificates_code',//被保险人证件号码
					'4'=>'assured_birthday',
					'5'=>'assured_gender',//被保险人出生日期列
					'6'=>'assured_mobilephone',//手机
					'7'=>'assured_email',//email
					);
$G_XLS_UPLOAD_FORMAT_CPIC_TJ_PROPERTY_PILIANG = array(
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
					);
//added by zhangxi, 20150629, 太平洋天津财险，团单和批量的xls上传和解析
function process_file_xls_cpic_tj_property_insured($uploadfile)
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
	global $G_XLS_UPLOAD_FORMAT_CPIC_TJ_PROPERTY_TUANDAN;
	global $G_XLS_UPLOAD_FORMAT_CPIC_TJ_PROPERTY_PILIANG;
	
	//自己设置的上传文件存放路径
	require_once $path.'/oop/lib/Excel/PHPExcel.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/IOFactory.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/Reader/Excel5.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/Reader/Excel2007.php';

	if(isset($_POST['commit_type']))
	{
		$commit_type = 	trim($_POST['commit_type']);
		//团单的xls上传
		if($commit_type == 'tuandan')
		{
			$result = process_file_xls_upload_common($uploadfile, NULL, $G_XLS_UPLOAD_FORMAT_CPIC_TJ_PROPERTY_TUANDAN,9);
		}
		//批量上传	
		elseif($commit_type == 'piliang')
		{
			$result = process_file_xls_upload_common($uploadfile, NULL, $G_XLS_UPLOAD_FORMAT_CPIC_TJ_PROPERTY_PILIANG);
		}
		else
		{
			ss_log(__FUNCTION__."commit_type=".$commit_type);
		}
	}
	
	
	return $result;
}
//added by zhangxi , 20150119, 使用新的上传模板处理函数
function process_file_xls_taipingyang_insured_new_template($uploadfile)
{
	ss_log(__FUNCTION__.": ".$uploadfile);
	if(empty($uploadfile))
	{	
		return array('result_code'=>'1',
						'result_msg'=>'fail, file not exist',
						);
	}
	//ob_start(); 
	////////////////////////////////////////////////////////////////////
	$path = dirname(S_ROOT);
	 global $G_XLS_UPLOAD_FORMAT_TAIPINGYANG_YIWAI;
	global $G_XLS_UPLOAD_FORMAT_TAIPINGYANG_TUANDAN;
	//自己设置的上传文件存放路径
	require_once $path.'/oop/lib/Excel/PHPExcel.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/IOFactory.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/Reader/Excel5.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/Reader/Excel2007.php';
	/////////////////////////////////////////////////////////////////////
	//03,还是07自动判断
	if(isset($_POST['commit_type']))
	{
		$commit_type = 	trim($_POST['commit_type']);
		//团单的xls上传
		if($commit_type == 'tuandan')
		{
			$result = process_file_xls_upload_common($uploadfile, '', $G_XLS_UPLOAD_FORMAT_TAIPINGYANG_TUANDAN,9);
		}
	}
	else//兼容原有的批量导入功能分之代码
	{
		$result = process_file_xls_upload_common($uploadfile, '', $G_XLS_UPLOAD_FORMAT_TAIPINGYANG_YIWAI);
	}
	
	
	
	
//	$fileType =  PHPExcel_IOFactory::identify($uploadfile);
//	$objReader = PHPExcel_IOFactory::createReader($fileType);
//	$objPHPExcel = $objReader->load($uploadfile);
//	$sheet = $objPHPExcel->getSheet(0);
//	$highestRow = $sheet->getHighestRow();           //取得总行数
//	$highestColumn = $sheet->getHighestColumn(); //取得总列数
//	
//	$objWorksheet = $objPHPExcel->getActiveSheet();
//	$highestRow = $objWorksheet->getHighestRow();
//	$highestColumn = $objWorksheet->getHighestColumn();
//	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
//	
//	ss_log("highestRow=".$highestRow.",highestColumnIndex=".$highestColumnIndex);
//	if($highestRow > 3000)
//	{
//		return array('result_code'=>'1',
//						'result_msg'=>'failed , limited users exceed 3000',
//						);
//	}
//	
//    global $G_XLS_UPLOAD_FORMAT_TAIPINGYANG_YIWAI;
//    $format_arr=$G_XLS_UPLOAD_FORMAT_TAIPINGYANG_YIWAI;
//	$rows = array();
//	for ($row = 11;$row <= $highestRow; $row++)
//	{
//		$strs_row = array();
//		//$index=0;
//		$normal_row_flag =1;
//		//注意highestColumnIndex的列数索引从0开始
//		$applicant_cert_flag=0;
//		for ($col = 0;$col < $highestColumnIndex; $col++)
//		{
//			if(in_array($col,array_keys($format_arr)))
//			{
//				//MOD BY ZHANGXI,20150110, 获取计算之后的值
//				$value = trim($objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue());
//				$strs_row[$format_arr[$col]] = strval($value);
//				ss_log("index:".$format_arr[$col].$strs_row[$format_arr[$col]]);
//				
//				if(($format_arr[$col] == 'applicant_certificates_type') && ($strs_row[$format_arr[$col]] != '身份证'))
//				{
//					$applicant_cert_flag = 1;
//				}
//				
//				if($applicant_cert_flag && ($format_arr[$col] == 'applicant_birthday'))
//				{
//					if(strlen($strs_row[$format_arr[$col]]) == 5 )
//					{
//						$n = intval(($strs_row[$format_arr[$col]] - 25569)*3600*24); //转换成1970年以来的秒数
//						$strs_row[$format_arr[$col]]= gmdate('Y-m-d',$n);//格式化时间	
//					}
//						
//				}
//				
//				
//				if($format_arr[$col] == 'assured_name' && empty($strs_row[$format_arr[$col]]))
//				{	
//					//被保险人姓名为空的行，直接不取
//					$normal_row_flag =0;
//					break;
//				}
//				//added by zhangxi, 20141217,出生日期格式校验
//				elseif($format_arr[$col] == 'assured_birthday')
//				{
//					if(strlen($strs_row[$format_arr[$col]]) == 5 )
//					{
//						$n = intval(($strs_row[$format_arr[$col]] - 25569)*3600*24); //转换成1970年以来的秒数
//						$strs_row[$format_arr[$col]]= gmdate('Y-m-d',$n);//格式化时间	
//					}
//				}
//				elseif($format_arr[$col] == 'certificates_type')
//				{
//					//不需要
//				}
//				elseif($format_arr[$col] == 'gender')
//				{
//					//根据不同的保险公司进行值的转换
//				}
//				//$index++;
//			}
//			//$strcol .= $strs[$col]." ";
//		}
//		//跳过不正常的行
//		if($normal_row_flag == 0)
//		{
//			//continue;
//			break;//为了性能，直接break
//		}
//		
//		if(!empty($strs_row))
//		{
//			$rows[] = $strs_row;
//		}
//			
//	}
//	ob_clean(); 
//	ob_end_flush(); 
//	
//		$result=array('result_code'=>'0',
//						'result_msg'=>'success',
//						//'filepath'=>$uploadfile,
//						'data'=>$rows,
//						);
//						
//	//上传的文件，处理完成之后，是否在这里就应该删除?
		return $result;
}
//mod by zhangxi, 20141209, 修改函数名字，导入Excel文件
function process_file_xls_taipingyang_insured($uploadfile)//$file,$filetempname
{
ss_log("into function process_file_xls_pingan_insured:".$uploadfile);
	
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
	//03,还是07自动判断
	$fileType =  PHPExcel_IOFactory::identify($uploadfile);
	$objReader = PHPExcel_IOFactory::createReader($fileType);
	$objPHPExcel = $objReader->load($uploadfile);
	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow();           //取得总行数
	$highestColumn = $sheet->getHighestColumn(); //取得总列数
	
	//判断记录数是否小于5条，小于则返回
	//先前端判断吧
	
	$objWorksheet = $objPHPExcel->getActiveSheet();
	$highestRow = $objWorksheet->getHighestRow();
	$highestColumn = $objWorksheet->getHighestColumn();
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
	
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
	
    global $G_USERINFO_PINGAN;
	$rows = array();
	//added by zhangxi, 20141210, 
	//根据公司excel模板文件来处理
	//模板文件1列证件类型，2列证件号，3列职业类别，4列性别，5列生日，8列EMAIL，9列联系电话
	
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
				//MOD BY ZHANGXI,20150110, 获取计算之后的值
				$value = $objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
				$strs_row[$G_USERINFO_PINGAN[$col]] = strval($value);
				ss_log("index:".$G_USERINFO_PINGAN[$col].$strs_row[$G_USERINFO_PINGAN[$col]]);
				if($G_USERINFO_PINGAN[$col] == 'fullname' && empty($strs_row[$G_USERINFO_PINGAN[$col]]))
				{	
					//姓名为空的行，直接不取
					$normal_row_flag =0;
					break;
				}
				
				/*
				1 => '身份证',
				2 => '护照',
				3 => '军官证',
				4 => '驾照',
				5 => '其他',
				6 => '组织机构代码',
				*/
				
				/*
				 		1 => '男',
						2 => '女',
				*/
				//added by zhangxi, 20141217,出生日期格式校验
				elseif($G_USERINFO_PINGAN[$col] == 'birthday')
				{
					$n = intval(($strs_row[$G_USERINFO_PINGAN[$col]] - 25569)*3600*24); //转换成1970年以来的秒数
					$strs_row[$G_USERINFO_PINGAN[$col]]= gmdate('Y-m-d',$n);//格式化时间	
				}
				elseif($G_USERINFO_PINGAN[$col] == 'certificates_type')
				{
					if($strs_row[$G_USERINFO_PINGAN[$col]] == '身份证')
					{
						$strs_row[$G_USERINFO_PINGAN[$col]] = '1';
					}
					elseif($strs_row[$G_USERINFO_PINGAN[$col]] == '护照')
					{
						$strs_row[$G_USERINFO_PINGAN[$col]] = '2';
					}
					elseif($strs_row[$G_USERINFO_PINGAN[$col]] == '其他')
					{
						$strs_row[$G_USERINFO_PINGAN[$col]] = '5';
					}
				}
				elseif($G_USERINFO_PINGAN[$col] == 'gender')
				{
					if($strs_row[$G_USERINFO_PINGAN[$col]] == '男')
					{
						$strs_row[$G_USERINFO_PINGAN[$col]] = '1';
					}
					elseif($strs_row[$G_USERINFO_PINGAN[$col]] == '女')
					{
						$strs_row[$G_USERINFO_PINGAN[$col]] = '2';
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
			$strs_row['level_num'] = "1";//get_level_num_by_career_code($strs_row[$G_USERINFO_PINGAN['8']]);
			$rows[] = $strs_row;
		}
		ss_log("-----------------");
			
	}
	
		$result=array('result_code'=>'0',
						'result_msg'=>'success',
						//'filepath'=>$uploadfile,
						'data'=>$rows,
						);
						
	//上传的文件，处理完成之后，是否在这里就应该删除
	
		return $result;
}

//导入职业类别的Excel文件
function process_file_xls_taipingyang_occupationClassCode($uploadfile)//$file,$filetempname
{
	////////////////////////////////////////////////////////////////////
	$path = dirname(S_ROOT);

	//自己设置的上传文件存放路径
	require_once $path.'/oop/lib/Excel/PHPExcel.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/IOFactory.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/Reader/Excel5.php';
	/////////////////////////////////////////////////////////////////////

	//include "conn.php";
	$objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
	$objPHPExcel = $objReader->load($uploadfile);
	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow();           //取得总行数
	$highestColumn = $sheet->getHighestColumn(); //取得总列数

	/* 第一种方法
	 //循环读取excel文件,读取一条,插入一条
	for($j=1;$j<=$highestRow;$j++)                        //从第一行开始读取数据
	{
	for($k='A';$k<=$highestColumn;$k++)            //从A列读取数据
	{
	//
	这种方法简单，但有不妥，以''合并为数组，再分割为字段值插入到数据库
	实测在excel中，如果某单元格的值包含了导入的数据会为空
	//
	$str .=$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().'';//读取单元格
	}
	//echo $str; die();
	//explode:函数把字符串分割为数组。
	$strs = explode("",$str);
	$sql = "INSERT INTO te(`1`, `2`, `3`, `4`, `5`) VALUES (
			'{$strs[0]}',
			'{$strs[1]}',
			'{$strs[2]}',
			'{$strs[3]}',
			'{$strs[4]}')";
	//die($sql);
	if(!mysql_query($sql))
	{
	return false;
	echo 'sql语句有误';
	}
	$str = "";
	}
	unlink($uploadfile); //删除上传的excel文件
	$msg = "导入成功！";
	*/

	/* 第二种方法*/

	$objWorksheet = $objPHPExcel->getActiveSheet();
	$highestRow = $objWorksheet->getHighestRow();
	//echo 'highestRow='.$highestRow;
	//echo "<br>";
	$highestColumn = $objWorksheet->getHighestColumn();
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
	//echo 'highestColumnIndex='.$highestColumnIndex;
	//echo "<br>";



	$headtitle=array();
	for ($row = 1;$row <= $highestRow;$row++)
	{
		$strs=array();
			
		echo "<br>";
		$strcol = "";
		//注意highestColumnIndex的列数索引从0开始
		for ($col = 0;$col < $highestColumnIndex;$col++)
		{
			$strs[$col] =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();

			$strcol .= $strs[$col]." ";
		}
			
		echo $strcol;
		/*
		 $sql = "INSERT INTO te(`1`, `2`, `3`, `4`, `5`) VALUES (
		 		'{$strs[0]}',
		 		'{$strs[1]}',
		 		'{$strs[2]}',
		 		'{$strs[3]}',
		 		'{$strs[4]}')";
		//die($sql);
		if(!mysql_query($sql))
		{
		return false;
		echo 'sql语句有误';
		}
		*/
	}

	return ;
}

//发送信息到救援公司
function taipingyang_send_message_to_jiuyuan($attribute_code,
											$order_sn,
											 $policy_id,
											 $strxml_post_policy,
											 $strxml_post_policy_ret
		                                     )
{
	global $_SGLOBAL,$use_test_environmental;
	///////////////////////////////////////////////////////////////////

	ss_log("into function: ".__FUNCTION__." policy_id: ".$policy_id);
	$ret = false;
	
	ss_log("SERVER_PORT: ".$_SERVER["SERVER_PORT"]);
	ss_log("use_test_environmental value is: ".$use_test_environmental);
	
	//测试环境则什么也不错
	if($_SERVER["SERVER_PORT"] == "82" || (isset($use_test_environmental)&&$use_test_environmental == true) )
	{
		ss_log("测试环境，直接返回，不发送到救援公司");
		return false;
	}
	else
	{
		ss_log("正式环境，准备发送到救援公司");
	}
	

	ss_log("准备发送到救援公司");
	//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	$java_class_name_huanqiujiuyuan = 'www.ebaoins.cn.rescue.buma.BumaRemoteRequest';
	
	if(!defined('USE_ASYN_JAVA'))
	{
		$java_class_name = $java_class_name_huanqiujiuyuan;
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_huanqiujiuyuan:$java_class_name;
	//end add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
		
	$obj_java = create_java_obj($java_class_name);//难道被调用生成多次导致阻塞。？？？
	if(empty($obj_java))
	{
		ss_log("发送到救援公司时候，create java obj fail: ".$java_class_name);
		$ret = false;
		return $ret;
	}

	
	$url = "http://www.buma.net.cn:8080/BUMAWs/services/BumaDataInputService";
	ss_log("taipingyang jiuyuan url: ".$url);
	
	//$ret_af_path = $return_data_jiuyuan_file = S_ROOT."xml/".$order_sn."_".$policy_id."_huanqiu_policy_post_jiuyuan_ret.xml";
	$ret_af_path = $return_data_jiuyuan_file = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_huanqiu_policy_post_jiuyuan_ret.xml");
	$creatorGuidFromBuma    = "8d431bc37b1f22be5c6d9fca0ecb77dd";
	
	if(!defined('USE_ASYN_JAVA'))//add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	{//同步方式
		ss_log("同步方式发送到救援公司, policy_id: ".$policy_id);
		
		$postXmlFileName = "";
		$retXmlFileName = "";
		$logFile = S_ROOT."xml/log/".$order_sn."_huanqiu_policy_post_jiuyuan_ret.log";
				
		ss_log("使用同步方式，taipingyang will 发送报文到救援公司");
	
		$return_data_jiuyuan = (string)$obj_java->sendMessage2Buma(
							$url ,
							$postXmlFileName,
							"UTF-8",
							$strxml_post_policy,
							$retXmlFileName,
							"UTF-8",
							$strxml_post_policy_ret,
							$logFile ,
							$creatorGuidFromBuma
					);
		ss_log("救援公司返回： ".$return_data_jiuyuan);
		if(empty($return_data_jiuyuan))//success
		{//返回为空，则就证明成功了。
			ss_log("救援公司返回成功！");
			$ret = true;
		}
		else
		{
			ss_log("救援公司返回不成功！to see: ".$return_data_jiuyuan_file);
			file_put_contents($return_data_jiuyuan_file,$return_data_jiuyuan);
			
			$ret = false;
		}
			
		//////////////////////////////////////////////////////////////////////////
		if($ret)
		{
			$ret_status_jiuyuan = "success";
		}
		else
		{
			$ret_status_jiuyuan = "fail";
		}
		
		ss_log("更新投保单的发到救援公司状态为: ".$ret_status_jiuyuan);
		updatetable(	'insurance_policy',
						array('status_jiuyuanret'=>$ret_status_jiuyuan),
						array('policy_id'=>$policy_id)
						);
	}
	//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	else
	{//异步方式
		
		ss_log("异步方式发送到救援公司, policy_id: ".$policy_id);
	
		
		$xmlContent = "";//$strxml_post_policy;//$strxml_post_policy_content;
		//ss_log("xmlContent: ".$xmlContent);
			
		//其实这里重复了，传入的参数就包含了文件的内容
		//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_post.xml";
		$af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_taipingyang_policy_post.xml");
		$postXmlFileName = $af_path;
		ss_log("postXmlFileName: ".$postXmlFileName);
			
		$retXmlContent = "";//$strxml_post_policy_ret;
		//$af_path_ret = S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_post_ret.xml";
		$af_path_ret = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_taipingyang_policy_post_ret.xml");
		$retXmlFileName = $af_path_ret;
		ss_log("retXmlFileName: ".$retXmlFileName);
				
		
		//////////////////////////////////////////////////////////////////////////////////////////
		$postXmlEncoding = "UTF-8";
		$respXmlFileName = $ret_af_path;
		
		ss_log("respXmlFileName: ".$respXmlFileName);
		
		$logFileName = S_ROOT."xml/log/Huanqiu_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
			
		ss_log("logFileName: ".$logFileName);
			
		$insurer_code = "Huanqiu";//$policy_attr['insurer_code'];
		ss_log("insurer_code: ".$insurer_code);
		$callBackURL = CALLBACK_URL;
		ss_log("callBackURL: ".$callBackURL);
		$type = "pushinfo";//这个承保要区分开
		$isDev = "Y";

		$port ="";
		
		/*
		 private String url;         //环球救援的URL地址

		//文件名(编码)和文件内容指定其一即可
		private String postXmlFileName;   //投保报文
		private String encoding;		//投保报文编码
		private String postXmlContent;  //投保报文内容
		
		//文件名(编码)和文件内容指定其一即可
		private String retXmlContent;      //投保保险公司返回文件内容
		private String retEncoding;			//投保保险公司返回文件编码
		private String retXmlFileName;    //投保保险公司返回文件
					
		private String creatorGuidFromBuma="8d431bc37b1f22be5c6d9fca0ecb77dd"; //推送者，请使用分配的GUID
		
		private String logFileName;			//日志文件
		private String respXmlFileName;     //返回内容填写文件，返回给王总

		 */
		$param_other = array(
				"url"=>$url ,//投保保险公司服务地址
				"port"=>$port ,//端口
				"postXmlContent"=>$xmlContent ,//投保报文内容
				"encoding"=>$postXmlEncoding ,//投保报文文件编码格式
				"postXmlFileName"=>$postXmlFileName,//投保报文文件
				"retXmlContent"=>$retXmlContent ,//投保报文内容
				"retEncoding"=>$postXmlEncoding,
				"retXmlFileName"=>$retXmlFileName,//投保报文文件
				"creatorGuidFromBuma"=>$creatorGuidFromBuma,//投保报文文件
				"respXmlFileName"=>$respXmlFileName,//返回报文保存全路径
				"logFileName"=>$logFileName, //日志文件
				"isDev"=>$isDev//
		);
		
		/*
		$param_other = array(   
				"url"=>$url ,//投保保险公司服务地址
				"port"=>$port ,//端口
				"xmlContent"=>$xmlContent ,//投保报文内容
				"postXmlFileName"=>$postXmlFileName,//投保报文文件
				"postXmlEncoding"=>$postXmlEncoding ,//投保报文文件编码格式
				"respXmlFileName"=>$respXmlFileName,//返回报文保存全路径
				"logFileName"=>$logFileName, //日志文件
				"isDev"=>$isDev//
		);
		*/
			
		ss_log("json param_other: ".var_export($param_other,true));
			
		$jsonStr = json_encode($param_other);
			
		ss_log("将要发送到救援公司的异步请求，will doService");
		ss_log("insurer_code: ".$insurer_code);
		ss_log("type: ".$type);
		ss_log("policy_id: ".$policy_id);
		ss_log("callBackURL: ".$callBackURL);
		
		$successAccept = (string)$obj_java->doService(
				$insurer_code,
				$type,
				$policy_id,
				$callBackURL,
				$jsonStr
		);
			
		/*
		$result = 0;
		$retmsg = "after use asyn, ret: ".$successAccept;
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		
		
		return $result_attr;
		*/
		
		return true;
		

	}//end 异步方式
	//end add by wangcya, 20150112,for bug[203],启用异步方式和java通讯

	return $ret;
}



function process_return_xml_data_Huanqiu(
										$policy_id,
										$return_data
									   )
{
	global $_SGLOBAL;
	////////////////////////////////////////////////////////////////////////////
	//以下要针对华安的做处理
	
	ss_log("into function ".__FUNCTION__);
	//////////////////////////////////////////////////////////////////////////
	if($return_data == "[content is null.]")
	{
		$ret_status_jiuyuan = "success";
		$result = 0;
		$retmsg = "发送数据到救援公司成功";
	}
	else
	{
		$ret_status_jiuyuan = "fail";
		$result = 110;
		$retmsg = "发送数据到救援公司失败";
	}
	
	ss_log("更新投保单的（发到救援公司）状态为: ".$ret_status_jiuyuan." policy_id: ".$policy_id);
	
	updatetable(	'insurance_policy',
					array('status_jiuyuanret'=>$ret_status_jiuyuan),
					array('policy_id'=>$policy_id)
					);
	
	$result_attr = array('retcode'=>$result,
			'retmsg'=> $retmsg,
			);

	return $result_attr;

}

//start add by wangcya , 20141213,发送信息到救援公司
function rescue_policy_taipingyang($policy_id)
{
	global $_SGLOBAL;

	//////////////////////////////////////////////////////////////////////////////
	ss_log("in function ".__FUNCTION__." policy_id: ".$policy_id);
	////////////上面把订单保存后，下面进行投保的工作///////////////////////////////////

	$ret = get_policy_info($policy_id);//得到保单信息
	
	$policy_attr = $ret['policy_arr'];
	
	$attribute_id = $policy_attr['attribute_id'];
	$order_sn = $policy_attr['order_sn'];
	//////////////////////////////////////////////////////////////////////////////

	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_post.xml";
	ss_log($af_path);
	$strxml_post_policy = file_get_contents($af_path);

	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_post_ret.xml";
	ss_log($af_path);
	$strxml_post_policy_ret = file_get_contents($af_path);

	/////////////////每个公司的产品的投保都不太一样，或者每个产品不太一样，则需要区分开
	//是太平洋的，已经投保成功，但是还没发送成功的
	if(	$policy_attr['policy_status'] == 'insured' &&
		$policy_attr['status_jiuyuanret'] != 'success'
	)
	{

		sendto_huanqiu_jiuyuan(
								$order_sn,
								$policy_id,
								$strxml_post_policy,
								$strxml_post_policy_ret,
								$attribute_id
								);

							
		$retcode = 0;
		$retmsg  = "success";
	}
	else
	{
		$retcode = 112;
		$retmsg  = "fail";
	}
	
								
	$result_attr = array('retcode'=>$retcode,
						'retmsg'=>$retmsg
						);
	
	return $result_attr;
}
//end add by wangcya , 20141213,发送信息到救援公司

?>