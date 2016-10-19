<?php
if(!defined('S_ROOT'))
{
	define('S_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
}

include_once(S_ROOT.'/source/my_const.php');
include_once(S_ROOT.'/source/function_common.php');

define('REVLEN_GUUID',-8);

$java_class_name_huatai = 'com.yanda.imsp.util.ins.HuaTaiInsurance';
/*
1-	居民身份证
2-	军人证
3-	护照
4-	出生证
5-	异常身份证
6-	回乡证
10- 居民户口薄
12- 军官证

 * */
$attr_certificates_type_huatai = array(
		"1"=>"身份证",
		"2"=>"军官证",
		"3"=>"护照",
		"4"=>"驾驶执照",
		"5"=>"返乡证",
		"6"=>"其他",
);
	
$attr_group_certificates_type_huatai =  array(
		"7" => '组织机构代码证'
);
/*
$attr_sex_huatai = array("1"=>"男",
		"0"=>"女",
);
	

$attr_relationship_with_insured_huatai = array(
		"a"=>"本人",
		"b"=>"妻子",
		"c"=>"丈夫",
		"d"=>"父亲",
		"e"=>"母亲",
		"f"=>"儿子",
		"g"=>"女儿",
);
*/

$attr_sex_huatai = array("M"=>"男",
		"F"=>"女",
);


/*
1配偶
2子女
3父母
4亲属
5本人
6其它
0无关或不确定
*/

$attr_relationship_with_insured_huatai = array(
							"1"=>"配偶",
							"2"=>"子女",
							"3"=>"父母",
							"4"=>"亲属",
							"5"=>"本人",
							"6"=>"其它",
							"0"=>"无关或不确定",
						);


//<!--（可空）单位性质，07:股份 01:国有 02:集体 33:个体 03:私营 04:中外合资 05:外商独资 08:机关事业 13:社团 39:中外合作 9:其他-->
$attr_company_attribute_type_huatai = array(	"07"=>"股份",
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

$attr_purpose_huatai = array(
		"1"=>"商务活动",
		"2"=>"留学",
		"3"=>"培训",
		"4"=>"探亲",
		"5"=>"旅游",
		"6"=>"访友",
);

$G_XLS_UPLOAD_FORMAT_HUATAI_LVYOU = array(
					'1'=>'applicant_type',//投保人类型
					'2'=>'applicant_name',//投保人名
					'3'=>'applicant_englishname',//投保人英文名
					'4'=>'applicant_certificates_type',//投保人证件类型
					'5'=>'applicant_certificates_code',//投保人证件号
					'6'=>'applicant_birthday',//出生日期
					'7'=>'applicant_gender',//性别
					'8'=>'applicant_mobilephone',//手机
					'9'=>'applicant_email',//email
					'10'=>'relationship',//与投保人关系
					'11'=>'assured_name',
					'12'=>'assured_englishname',
					'13'=>'assured_certificates_type',
					'14'=>'assured_certificates_code',
					'15'=>'assured_birthday',//出生日期列
					'16'=>'assured_gender',//性别列
					'17'=>'assured_mobilephone',//email列
					'18'=>'assured_email',//联系电话列
					'19'=>'destination',//旅行目的地
					'20'=>'purpose',//出行事由（目的）
					'21'=>'visacity'//签证城市
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

$attr_certificates_type_huatai_single_unify = array(
		"1"=>1,//'身份证';
		"4"=>2,//'驾驶证';
		"2"=>3,//'军官证';
		"3"=>4,//'护照';
		//"1"=>5,//'港澳回乡证或台胞证';
		//"1"=>6,//'返乡证';
		//"1"=>7,//'组织机构代码';
		//"1"=>8,//'军人证';
		"6"=>9,//'其他';
);

$attr_certificates_type_huatai_group_unify = array(
		"7"=>10,//"组织机构代码证"
		//"02"=>11,//"税务登记证"
		//"03"=>12//"异常证件"
);

$attr_sex_huatai_unify = array(
		"F"=>"F",
		"M"=>"M",
);
//////////////////end add by wangcya, 20141219 //////////////////////////////

/////////////////////////////////////////////////////////////////////////////

//检查华泰的输入函数
function input_check_huatai($POST)
{
	global $_SGLOBAL,$attr_certificates_type_huatai_single_unify,$attr_certificates_type_huatai_group_unify,$attr_sex_huatai_unify;
	////////////////////////////////////////////////////////////////////////////////////////////
	$agent_uid = $_SGLOBAL['supe_uid'];//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	
	$global_info = array();
	///////////////////////////////////////////////////////////
	
	$global_info['applyNum'] = intval($POST['applyNum']);//份数
	$global_info['totalModalPremium'] = $POST['totalModalPremium'];//总保费,应该从前端计算得到的

	//ss_log("totalModalPremium: ".$totalModalPremium);
	//$totalModalPremium'] = $apply_num*intval($product[premium]);//$POST['totalModalPremium'];//从费用

	$global_info['beneficiary'] = $POST['fading'];//受益人
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期

	$global_info['period_code'] = $POST['period_code'];//保险期限代码
	
	///////////////组合投保人信息//////////////////////////////////////////////////
	$user_info_applicant = array();


	$businessType = empty($POST['businessType'])?1:trim($POST['businessType']);
	if($businessType == 1)//个人
	{
		$user_info_applicant['certificates_type'] = $POST['certType'];
		$user_info_applicant['certificates_code'] = $POST['certCode'];
		
		$user_info_applicant['certificates_validdate'] = $POST['certificates_validdate'];//新增加的
		
		
		$user_info_applicant['fullname'] = $POST['appName'];
		$user_info_applicant['fullname_english'] = $POST['appEnglishName'];
		$user_info_applicant['gender'] = $POST['appSex'];
		$user_info_applicant['birthday'] = $POST['appBirthday'];
		$user_info_applicant['mobiletelephone'] = $POST['appCell'];
		$user_info_applicant['email'] = $POST['appEmail'];

		//start add by wangcya , 20140830, 新增加的信息
		$user_info_applicant['province_code'] = $POST['insureareaprov_code'];
		$user_info_applicant['province'] = $POST['insureareaprov'];
		
		$user_info_applicant['city_code'] = $POST['insureareacity_code'];
		$user_info_applicant['city'] = $POST['insureareacity'];
		
		$user_info_applicant['address'] = $POST['appAddress'];
		$user_info_applicant['zipcode'] = $POST['appPostcode'];
		//end add by wangcya , 20140830, 新增加的信息
		
		/////////////////////////////////////////////////
		//start add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		$user_info_applicant['certificates_type_unify'] = $attr_certificates_type_huatai_single_unify[$user_info_applicant['certificates_type']];
		$user_info_applicant['gender_unify'] = $attr_sex_huatai_unify[$user_info_applicant['gender']];
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
		
		$user_info_applicant['group_name'] = $POST['group_name'];
		$user_info_applicant['group_abbr'] = $POST['group_abbr'];
		$user_info_applicant['company_attribute'] = $POST['company_attribute'];
		$user_info_applicant['address'] = $POST['address'];
		$user_info_applicant['telephone'] = $POST['telephone'];
		$user_info_applicant['mobiletelephone'] = $POST['applicant_mobilePhone'];
		$user_info_applicant['email'] = $POST['applicant_email'];
		
		//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		$user_info_applicant['group_certificates_type_unify'] = $attr_certificates_type_huatai_group_unify[$user_info_applicant['group_certificates_type']];
		$user_info_applicant['agent_uid'] = $agent_uid;
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	}

	/////////////////////////////////////////////////////////////////////
	$assured_info = array();

	///////////////被保人是投保人//////////////////////////////////////////
	$relationshipWithInsured = trim($POST['relationShipApp']);
	////////////////投保人的身份信息////////////////////////////////
	//////////////////首先根据被保险人身份证到用户表中查询，如果查到则进行信息更新，如果没查到插入新纪录
	if($relationshipWithInsured=="5")//是本人的信息,这是华泰定义的
	{
		$assured_info = $user_info_applicant;
		$assured_info['type'] = 2;//add by wangcya, 20141219, 0,被保险人，1投保人,2两者身份相同
	}
	else
	{
		//$assured_post = $POST['assured'][0];
			
		$assured_info['certificates_type'] = trim($POST['insCertType']);
		$assured_info['certificates_code'] = trim($POST['insCertCpde']);
		
		$assured_info['fullname']  = trim($POST['insName']);
		$assured_info['fullname_english']  = trim($POST['insEnglishName']);
		
		$assured_info['gender']  = trim($POST['insSex']);
		$assured_info['birthday'] = trim($POST['insBirthday']);

		$assured_info['mobiletelephone'] = $POST['insCell'];
		$assured_info['email'] = $POST['insEmail'];
		
		///////////////////////////////////////////////////////////////////////////
		$assured_info['certificates_validdate'] = $POST['assured_validdate'];//新增加的

		//start add by wangcya , 20140830, 新增加的信息
		$assured_info['province_code'] = $POST['assured_prov_code'];
		$assured_info['province'] = $POST['assured_prov'];
		
		$assured_info['city_code'] = $POST['assured_city_code'];
		$assured_info['city'] = $POST['assured_city'];
		
		$assured_info['address'] = $POST['assured_ddress'];
		$assured_info['zipcode'] = $POST['assured_postcode'];

		//add by wangcya, 20141219 ,不同厂商的证件号码和性别
		$assured_info['certificates_type_unify'] = $attr_certificates_type_huatai_single_unify[$assured_info['certificates_type']];
		$assured_info['gender_unify'] = $attr_sex_huatai_unify[$assured_info['gender']];
		$assured_info['type'] = 0;//0,被保险人，1投保人,2两者身份相同
		$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别
	}

	///////////////start add by wangcya , 20141111, for ORA-20004: 同一被保险人同一保险期间不能拥有两张有效的出国险保单。//////////////////////////////////////////////////////////
	ss_log("将要判断被保险人是否在同一时间内投保两次");
	$assured_fullname = "";//$assured_info['fullname'] ;
	$certificates_type = $assured_info['certificates_type'];
	$certificates_code = $assured_info['certificates_code'];
	$policy_status = "insured";
	$insurer_code = "HTS";
	
	$policy_attr = get_policy_from_assured(	$assured_fullname,
											$certificates_type,
											$certificates_code,
											$policy_status,
											$insurer_code
										  );
	
	foreach ($policy_attr AS $key=> $value)
	{
		$start_date    = strtotime($value['start_date']);
		$end_date      = strtotime($value['end_date']);
		$new_startDate = strtotime($global_info['startDate']);
			
		if($new_startDate>=$start_date && $new_startDate<=$end_date)
		{
			showmessage("同一被保险人同一保险期间不能拥有两张有效的出国险保单!");
		}
	}
	///////////////start add by wangcya , 20141111, end ORA-20004: 同一被保险人同一保险期间不能拥有两张有效的出国险保单。/////////////////////////////////////////////////////////////
	
	//start add by wangcya , 20140830, 新增加的信息
	$global_info['fading'] =  trim($POST['fading']);//受益人
	
	//////////////////////////////////////////////////////////////////////
	$global_info['purpose'] =  trim($POST['Purpose']);//出行目的
	
	$global_info['destination_area'] =  trim($POST['destination_area']);//出行目的地
	$global_info['destination_country'] =  trim($POST['destination_country']);//出行目的地
	$global_info['visacity'] =  trim($POST['VisaCity']);//签证城市
	
	//end add by wangcya , 20140830, 新增加的信息
	////////////////////////////////////////////////////////////////

	////////////////////产品的信息///////////////////////////////////////////////
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


	//////////////////有效性检查//////////////////////////////////////////////////////
	if(		!isset($global_info['applyNum'])||
			!isset($global_info['startDate'])||
			!isset($global_info['endDate'])
		)
	{
		ss_log("您输入的投保信息不完整！"." applyNum: ".$global_info['applyNum']." startDate: ".$global_info['startDate']." endDate: ".$global_info['endDate']);
		showmessage("您输入的投保信息不完整！");
		exit(0);
	}
	
	if(		!isset($user_info_applicant['fullname'])||
			!isset($user_info_applicant['fullname_english'])||
			!isset($user_info_applicant['certificates_type'])||
			!isset($user_info_applicant['certificates_code'])||
			!isset($user_info_applicant['birthday'])||
			!isset($user_info_applicant['gender'])
	)
	{
		showmessage("您输入的投保人信息不完整！");
		exit(0);
	}
	
	
	if($POST['relationshipWithInsured']!="5")
	{
		if(	!isset($assured_info['fullname'])||
			!isset($assured_info['fullname_english'])||
			!isset($assured_info['certificates_type'])||
			!isset($assured_info['certificates_code'])||
			!isset($assured_info['birthday'])||
			!isset($assured_info['gender'])
		)
		{
			showmessage("您输入的被保险人信息不完整！");
			exit(0);
		}
	}
	////////////////////////////////////////////////////////////

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


function HandleXmlError($errno, $errstr, $errfile, $errline)
{
	ss_log("errno: ".$errno." errstr: ".$errstr);
	if ($errno==E_WARNING && (substr_count($errstr,"DOMDocument::loadXML()")>0))
	{
		throw new DOMException($errstr);
	}
	else
		return false;
}


function XmlLoader($strXml)
{
	//$xml = new DOMDocument("1.0","UTF-8");//DOMDocument("1.0","GBK");
	//$xml->preserveWhiteSpace = false;
	//$ret = $xml->loadXML($return_data);
	set_error_handler('HandleXmlError');
	$dom = new DOMDocument();
	
	//设置下不要,严格验证格式看看
	$dom->validateOnParse = false;
	$dom->formatOutput = false;
	
	$dom->loadXml($strXml);
	restore_error_handler();
	return $dom;
}

function gen_post_policy_xml_huatai(	
								$policy_arr,//保单相关信息
								$user_info_applicant,//投保人信息
								$list_subject,
								$UUID
								)
{
	global $_SGLOBAL;
	///////////////////////////////////////////////////////////////////

	$DATE = date('Ymd',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30
	$orderNum = $SERIAL = $policy_arr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_arr['order_sn'];

	
	$policy_id = $policy_arr['policy_id'];
	$business_type = $policy_arr['business_type'];// <!-- 渠道来源 1-个险 2-团险 3-银保 4-DM 非空 -->
	
	///////////////////////////////////////////////////////////////////////
	
	$wheresql = "policy_id='$policy_id'";
	$sql = "SELECT * FROM ".tname('insurance_policy_huatai_yiwai_otherinfo')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$policy_huatai_yiwai_otherinfo = $_SGLOBAL['db']->fetch_array($query);
	
	if($policy_huatai_yiwai_otherinfo)
	{
	
		$policy_arr["period_code"] = $policy_huatai_yiwai_otherinfo['period_code'];//出行目的
		$policy_arr["purpose"] = $policy_huatai_yiwai_otherinfo['purpose'];//出行目的
		$policy_arr["destination_area"] = $policy_huatai_yiwai_otherinfo['destination_area'];//出行目的地
		$policy_arr["destination_country"] = $policy_huatai_yiwai_otherinfo['destination_country'];//出行目的地
		$policy_arr["visacity"] = $policy_huatai_yiwai_otherinfo['visacity'];//签证城市
	
	}
	else
	{
		ss_log("policy_huatai_yiwai_otherinfo is null!");
	}
	//////////////////////////////////////////////////////////////////////
	
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
	
		$limit_note = $product_attribute['limit_note'];
		//$limit_note =  mb_convert_encoding($limit_note, "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
	
	}
	
	////////////////////////////////////////////////////////////
	if($policy_arr['business_type'] == 1)//<!-- (非空) 业务类型 1表示个人，2表示团体 -->
	{
		//$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant[fullname], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		$user_info_applicant_fullname = $user_info_applicant['fullname'];
	
		$birthday = strtotime($user_info_applicant['birthday']);
		//ss_log("app birthday: ".$birthday);
	
		$app_birthday = date('Ymd', $birthday);//生日格式要重新转化。从 2010-01-02 到20100102
	
		//echo "app_birthday: ".$app_birthday;
	
		ss_log("app_birthday: ".$app_birthday);
		//$user_info_applicant[certificates_validdate]
	
		$relationship_with_insured = $policy_arr['relationship_with_insured'];//被保人同投保人关系
		if($relationship_with_insured ==2)
		{
			$relationship_with_insured = 3;
		}
		elseif($relationship_with_insured ==3)
		{
			$relationship_with_insured = 2;
		}
	
			
		$insuranceApplicantInfo = '<!-- 投保人姓名 非空 -->
		<AppntName>'.$user_info_applicant_fullname.'</AppntName>
		<!-- 投保人英文姓名 非空 -->
		<AppntEnName>'.$user_info_applicant['fullname_english'].'</AppntEnName>
		<!-- 投保人性别 M-男 F-女 N–不确定 非空 -->
		<AppntSex>'.$user_info_applicant['gender'].'</AppntSex>
		<!-- 投保人生日 格式：yyyymmdd 非空 -->
		<AppntBirthday>'.$app_birthday.'</AppntBirthday>
		<!-- 投保人证件类型 1-居民身份证 2-军人证 3-护照 4-出生证 5-异常身份证 6-回乡证 10- 居民户口薄 12- 军官证 非空 -->
		<AppntIDType>'.$user_info_applicant['certificates_type'].'</AppntIDType>
		<!-- 投保人证件号码 非空 -->
		<AppntIDNo>'.$user_info_applicant['certificates_code'].'</AppntIDNo>
		<!-- 证件有效期 格式：yyyymmdd 非空 -->
		<AppIDValidDate>'.'20301221'.'</AppIDValidDate>
		<!-- 投保人国籍 156-中国 非空 -->
		<AppntCountry>156</AppntCountry>
		<!-- 投保人工作电话 -->
		<AppntOffTel />
		<!-- 投保人手机 非空 -->
		<AppntPhone>'.$user_info_applicant['mobiletelephone'].'</AppntPhone>
		<!-- 投保人家庭电话 -->
		<AppntFamTel />
		<!-- 投保人所在地区 101-北京 102-浙江 103-四川 104-江苏 105-上海 106-山东 107-河南 108-福建 109-湖南 110-广东 111-江西 112-内蒙古 113-湖北 114-河北 非空 -->
		<AppArea>'.$user_info_applicant['province_code'].'</AppArea>
		<!-- 投保人家庭住址 非空 -->
		<AppntAddress>'.$user_info_applicant['address'].'</AppntAddress>
		<!-- 投保人家庭住址邮编 非空 -->
		<AppntZip>'.$user_info_applicant['zipcode'].'</AppntZip>
		<!-- 投保人邮箱 -->
		<AppntEMail>'.$user_info_applicant['email'].'</AppntEMail>
		<!-- 投保人同被保人关系 1-配偶 2-子女 3-父母 4-亲属 5-本人 6-其它 0-无关或不确定 非空 -->
		<AppntRelationToInsured>'.$relationship_with_insured.'</AppntRelationToInsured>
		<!-- 投保人工作代码 -->
		<AppntJob />';
	
	}
	elseif($policy_arr['business_type'] == 2)
	{
		$user_info_applicant_fullname = $user_info_applicant['group_name'];
	
	}
	//$user_info_applicant['fullname'] = iconv('UTF-8', 'GKB', $user_info_applicant['fullname']);
	//$user_info_assured['fullname'] = iconv('UTF-8', 'GKB', $user_info_assured['fullname']);
	
	$subjectInfo_str = '';
	foreach($list_subject AS $key =>$value)
	{
		$index = $key+1;
		$list_subject_product  = $value['list_subject_product'] ;
		$list_subject_insurant = $value['list_subject_insurant'] ;
	
		//start del by wangcya , 20141017, 其实下面产品这部分是不需要的
		/*
			$product_str = '';
		$total_product_premium = 0;
		foreach($list_subject_product AS $key_product =>$value_product )
		{
		$product_str .=   '    <productInfo>
		<productCode>'.$value_product['product_code'].'</productCode>
		<applyNum>1</applyNum>
		<totalModalPremium>'.$value_product['premium'].'</totalModalPremium>
		</productInfo>';
		$total_product_premium = $total_product_premium + $value_product['premium'];
	
		}
		*/
		//end del by wangcya , 20141017
		////////////////////////////////////////////////////
		$product = $list_subject[0]['list_subject_product'][0];
	
		//得到多个被保险人。
		$insurantInfo_str = '';
		foreach($list_subject_insurant AS $key_insurant =>$value_insurant )
		{
			//$user_info_assured_fullname =  mb_convert_encoding($value_insurant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			$user_info_assured_fullname =  $value_insurant['fullname']; //已知原编码为UTF-8, 转换为GBK
				
			$insurant_birthday = date('Ymd', strtotime($value_insurant['birthday']));//生日格式要重新转化。从 2010-01-02 到20100102
			ss_log("insurant_birthday: ".$insurant_birthday);
				
			$insurantInfo_str .= '
			<!-- 被保人姓名 非空 -->
			<InsuredName>'.$user_info_assured_fullname.'</InsuredName>
			<!-- 被保人英文姓名 非空 -->
			<InsuredEnName>'.$value_insurant['fullname_english'].'</InsuredEnName>
			<!-- 被保险人类型 -->
			<InsuredType />
			<!-- 被保人性别 M-男 F-女 N–不确定 非空 -->
			<InsuredSex>'.$value_insurant['gender'].'</InsuredSex>
			<!-- 被保人生日 格式：yyyymmdd 非空 -->
			<InsuredBirthday>'.$insurant_birthday.'</InsuredBirthday>
			<!-- 被保人证件类型 1-居民身份证 2-军人证 3-护照 4-出生证 5-异常身份证 6-回乡证 10- 居民户口薄 12- 军官证 非空 -->
			<InsuredIDType>'.$value_insurant['certificates_type'].'</InsuredIDType>
			<!-- 被保人证件号码 非空 -->
			<InsuredIDNo>'.$value_insurant['certificates_code'].'</InsuredIDNo>
			<!-- 被保人国籍 156-中国 非空 -->
			<InsuredCountry>156</InsuredCountry>
			<!-- 被保人工作代码 -->
			<InsuredJob />
			<!-- 被保人所在地区 101-北京 102-浙江 103-四川 104-江苏 105-上海 106-山东 107-河南 108-福建 109-湖南 110-广东 111-江西 112-内蒙古 113-湖北 114-河北 非空 -->
			<InsArea>'.$value_insurant['province_code'].'</InsArea>
			<!-- 被保人家庭住址 非空 -->
			<InsuredAddress>'.$value_insurant['address'].'</InsuredAddress>
			<!-- 被保人家庭住址邮编 非空 -->
			<InsuredZip>'.$value_insurant['zipcode'].'</InsuredZip>
			<!-- 被保人手机 非空 -->
			<InsuredPhone>'.$value_insurant['mobiletelephone'].'</InsuredPhone>
			<!-- 被保人邮箱 -->
			<InsuredEMail>'.$value_insurant['email'].'</InsuredEMail>
			<!-- 同主被保人关系 1-配偶 2-子女 3-父母 4-亲属 5-本人 6-其它 0-无关或不确定 非空 -->
			<InsuredRelationToMain>5</InsuredRelationToMain>
			<!-- 同投保人关系 1-配偶 2-子女 3-父母 4-亲属 5-本人 6-其它 0-无关或不确定 非空 -->
			<InsuredRelationToAppnt>'.$policy_arr['relationship_with_insured'].'</InsuredRelationToAppnt>';
	
		}
	
	
		$insurantInfo .= '
		<InsuredInfo>
		'.$insurantInfo_str.'
		</InsuredInfo>';
	}
	
	//////////////////////////////////////////////////////////////////////
	$key =  md5($UUID."huatairenshou".$DATE);
	
	//$policy_arr['destination_country'] =  mb_convert_encoding($policy_arr['destination_country'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
	$start_date = date('Ymd', strtotime($policy_arr['start_date']));
	$end_date = date('Ymd', strtotime($policy_arr['end_date']));
	
	ss_log("start_date: ".$start_date." end_date: ".$end_date);
	
	$total_premium = $policy_arr['apply_num']*$policy_arr['total_premium'];
	$total_premium = sprintf("%01.2f", $total_premium );
	
	$policy_arr['total_premium'] = sprintf("%01.2f", $policy_arr['total_premium'] );
	
	
	$attr_product_code = array(
			"60041327"=>"A",
			"60041328"=>"B",
			"60041329"=>"C",
			"60041330"=>"D",
			"60041331"=>"E",
			"60041332"=>"F",
			"60041333"=>"G",
			"60041334"=>"H",
			"60041335"=>"I",
			"60041336"=>"J"
	);
	
	//start add by wangcya , 20141111, A款下的一年多次的转换
	if($policy_arr['period_code'] == "10" )//一年多次的
	{
		if($product['product_code'] == "60041327")
		{
			$policy_arr['product_code'] = "60041337";
		}
		if($product['product_code'] == "60041328")
		{
			$policy_arr['product_code'] = "60041338";
		}
		if($product['product_code'] == "60041329")
		{
			$policy_arr['product_code'] = "60041339";
		}
		if($product['product_code'] == "60041330")
		{
			$policy_arr['product_code'] = "60041340";
		}
		if($product['product_code'] == "60041331")
		{
			$policy_arr['product_code'] = "60041341";
		}
		if($product['product_code'] == "60041332")
		{
			$policy_arr['period_code'] = "60041342";
		}
		if($product['product_code'] == "60041333")
		{
			$policy_arr['product_code'] = "60041343";
		}
		if($product['product_code'] == "60041333")
		{
			$policy_arr[product_code] = "60041343";
		}
		if($product['product_code'] == "60041334")
		{
			$policy_arr['product_code'] = "60041344";
		}
		if($product['product_code'] == "60041335")
		{
			$policy_arr['product_code'] = "60041345";
		}
		if($product['product_code'] == "60041336")
		{
			$policy_arr['product_code'] = "60041346";
		}
	}
	//end add by wangcya , 20141111, A款下的一年多次的转换
	
	//////////////////////////////////////////////////////////////////////////
	
	$plan_code = $attr_product_code[$product['product_code']];
	
	//////////////////////////////////////////////////////////////////////////
	$strxml = '
	<?xml version="1.0" encoding="GBK"?>
	<TransData>
	<BaseInfo>
	<!-- 交易类型 非空 -->
	<TradeType>WEBSITE</TradeType>
	<!-- 交易代码 001-投保 非空 -->
	<TradeCode>001</TradeCode>
	<!-- 子交易代码 默认为：1 非空 -->
	<SubTradeCode>1</SubTradeCode>
	<!-- 交易流水号 -->
	<TradeSeq>'.$UUID.'</TradeSeq>
	<!-- 交易日期 格式：yyyymmdd 非空 -->
	<TradeDate>'.$DATE.'</TradeDate>
	<!-- 交易时间 格式：00:00:00 非空 -->
	<TradeTime>'.$TIME.'</TradeTime>
	<!-- 操作人 WEB003-中天信合 非空 -->
	<Operator>WEB003</Operator>
	<!-- 密文 加密内容：TradeSeq+“huatairenshou”+TradeDate 非空 -->
	<Key>'.$key.'</Key>
	</BaseInfo>
	<InputData>
	<!-- 信息流水号 同交易流水号 非空 -->
	<TradeSeq>'.$UUID.'</TradeSeq>
	<!-- 订单号码 非空 -->
	<OrderNo>'.$orderNum.'</OrderNo>
	<!-- 信息日期 同交易日期 非空 -->
	<TranDate>'.$DATE.'</TranDate>
	<!-- 信息时间 同交易时间 非空 -->
	<TranTime>'.$TIME.'</TranTime>
	<ContInfo>
	<!-- 合同号码 空 -->
	<ContNo />
	<!-- 印刷号码 空 -->
	<PrtNo />
	<!-- 投保日期 格式：yyyymmdd 非空 -->
	<PolApplyDate>'.$DATE.'</PolApplyDate>
	<!-- 渠道来源 1-个险 2-团险 3-银保 4-DM 非空 -->
	<PolicySource>'.$business_type.'</PolicySource>
	<!-- 总保费 非空 -->
	<AllPrem>'.$total_premium.'</AllPrem>
	<!-- 总保额 可以为空 -->
	<AllAmnt></AllAmnt>
	<!-- 机构代码 非空 -->
	<ManageCom>101</ManageCom>
	<!-- 代理机构代码 非空 -->
	<AgentCom>50010101010124</AgentCom>
	<!-- 生效日期 格式：yyyymmdd 非空 -->
	<CValiDate>'.$start_date.'</CValiDate>
	<!-- 结束日期 格式：yyyymmdd 非空 -->
	<ECValiDate>'.$end_date.'</ECValiDate>
	<!-- 缴费方式 1-现金 2-支票 3-客户银行转账 40-第三方支付 41-实时转账 非空 -->
	<PayMode>1</PayMode>
	<!-- 续期缴费方式 同缴费方式 -->
	<ExPayMode></ExPayMode>
	<!-- 保单递送方式 6-电子商务电子保单 非空 -->
	<SendType>6</SendType>
	<!-- 销售方式 2-兼业代理 18-专业代理 21-电子商务-团险 非空 -->
	<SellWay>2</SellWay>
	<!-- 出单渠道 4-网上出单 5-专业代理平台 非空 -->
	<SaleChnl>4</SaleChnl>
	'.$insuranceApplicantInfo.'
	<!-- 特别约定 -->
	<Spec></Spec>
	<!-- 出国事由 1-商务 -2学习 3-培训 4-探亲 5-旅游 6-访友 非空 -->
	<EvectionReason>'.$policy_arr['purpose'].'</EvectionReason>
	<!-- 前往国家 非空 -->
	<Destination>'.$policy_arr['destination_country'].'</Destination>
	<!-- 发票号码 -->
	<InvoiceNo />
	<!-- 账户名称 -->
	<AccName />
	<!-- 银行代码 -->
	<BankCode />
	<!-- 账户号码 -->
	<BankAccNo />
	</ContInfo>
	'.$insurantInfo.'
	<PolInfo>
	<RiskInfo>
	<!-- 保单号码 空 -->
	<PolNo />
	<!-- 险种代码 非空 -->
	<RiskCode>'.$product['product_code'].'</RiskCode>
	<!-- 主险代码 同险种代码 非空 -->
	<MainRiskCode>'.$product['product_code'].'</MainRiskCode>
	<!-- 保险款式编码 非空 -->
	<InterCode>'.$plan_code.'</InterCode>
	<!-- 保费 非空 -->
	<Prem>'.$policy_arr['total_premium'].'</Prem>
	<!-- 保额 非空 -->
	<Amnt></Amnt>
	<!-- 折扣率 -->
	<Discount />
	<!-- 份数 非空 -->
	<Mult>'.$policy_arr['apply_num'].'</Mult>
	<!-- 缴费间隔 5-趸交 非空-->
	<PayIntv>5</PayIntv>
	<!-- 缴费间隔类型 5-趸交 非空 -->
	<PayYearFlag>1</PayYearFlag>
	<!-- 交费期限 非空 -->
	<PayYear>0</PayYear>
	<!-- 保险年期类型 1-5天 2-10天 3-15天 4-20天 5-30天 6-45天 7-62天 8-92天 9-183天 10-一年（多次） 11-一年 非空 -->
	<InsuYearFlag>'.$policy_arr['period_code'].'</InsuYearFlag>
	<!-- 保险期限 -->
	<InsuYear>'.$policy_arr['period_name'].'</InsuYear>
	<!-- 险种是否可续保 -->
	<AutoReNewal />
	<!-- 投保期间单位 非空 -->
	<Unit>D</Unit>
	<!-- 投保期间 非空 -->
	<Term>1</Term>
	</RiskInfo>
	</PolInfo>
	<RnfInfo>
	<!-- 受益类型 1-法定 2-指定 非空 -->
	<RnfType>'.$policy_arr['fading'].'</RnfType>
	<!-- 受益人受益序号 -->
	<RnfNo />
	<!-- 受益人受益级别 -->
	<RnfOrder />
	<!-- 受益人姓名 -->
	<RnfName />
	<!-- 受益人性别 -->
	<RnfSex />
	<!-- 受益人生日 -->
	<RnfBirthday />
	<!-- 受益人证件类型 -->
	<BnfIDType />
	<!-- 受益人证件号码 -->
	<BnfIDNo />
	<!-- 受益人同被保人关系 -->
	<BnfRelationToInsured />
	<!-- 收益比列 -->
	<BnfRate />
	<!-- 受益人住址 -->
	<BnfAddress />
	</RnfInfo>
	</InputData>
	</TransData>';
	
	
	//iconv('UTF-8', 'GKB', $requestParam);
	$strxml = trim($strxml);
	//$strxml =  mb_convert_encoding($strxml, "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
	
	return $strxml;
}


function process_post_policy_return_xml_data_huatai($policy_id,
										$order_sn,
		                                $return_data//in
										)
{
	ss_log("into function： ".__FUNCTION__);
	
	/////////先解析出来返回的xml//////////////////////////////////
	//$return_data =  mb_convert_encoding($return_data, "UTF-8", "GBK");
	
	/* 张义明下面更改了，导致同步和异步处理不一样了
	$return_data = str_replace('encoding="GBK"', 'encoding="utf-8"', $return_data);
	$return_data = iconv('GB2312', 'GBK', $return_data);
	*/
	
	$xml = XmlLoader($return_data);
	if($xml)
	{
		$retmsg = "huatai load xml ok!";
		ss_log($retmsg);
	
	}
	else
	{
		$retcode = 110;
		$retmsg = "post loadXML fail!";
		ss_log($retmsg);
	
		$result_attr = array('retcode'=>$retcode,
				'retmsg'=> $retmsg
		);
	
		return $result_attr;
	}
	
	
	$root = $xml->documentElement;//使用DOM对象的documentElement属性可以访问XML文档的根元素
	
	$BaseInfos = $root->getElementsByTagName("BaseInfo");
	//$TradeType = $BaseInfos->item(1);
	
	foreach($BaseInfos as $BaseInfo)
	{
		//echo "1111dsfsfsdf"."<br />"; ;
		$TradeTypes = $BaseInfo->getElementsByTagName("TradeType");
		$TradeType = $TradeTypes->item(0)->nodeValue;
	
		$TradeTypes = $BaseInfo->getElementsByTagName("TradeCode");
		$TradeCode = $TradeTypes->item(0)->nodeValue;
	
		$TradeTypes = $BaseInfo->getElementsByTagName("TradeSeq");
		$TradeSeq = $TradeTypes->item(0)->nodeValue;
	
	}
	
	//////////////////////////////////////////////////////////////////
	$BaseInfos = $root->getElementsByTagName("OutputData");
	//$TradeType = $BaseInfos->item(1);
	
	foreach($BaseInfos as $BaseInfo)
	{
	
		$TradeTypes = $BaseInfo->getElementsByTagName("Flag");
		$messageStatusCode = $TradeTypes->item(0)->nodeValue;
	
		$TradeTypes = $BaseInfo->getElementsByTagName("Desc");
		$messageStatusSubDescription = $TradeTypes->item(0)->nodeValue;
	
	
	}
	
	
	ss_log("post policy : messageStatusCode: ".$messageStatusCode);
	ss_log("post policy : messageStatusSubDescription: ".$messageStatusSubDescription);
	
	if($messageStatusCode== '0' )//ok
	{
		ss_log("投保成功！policy_id: ".$policy_id);
		//////////////////////////////////////////////////////////////////
		$BaseInfos = $root->getElementsByTagName("OutputData");
		//$TradeType = $BaseInfos->item(1);
	
		foreach($BaseInfos as $BaseInfo)
		{
			//echo "1111dsfsfsdf"."<br />"; ;
			$TradeTypes = $BaseInfo->getElementsByTagName("TradeSeq");
			$TradeSeq = $TradeTypes->item(0)->nodeValue;
	
			$TradeTypes = $BaseInfo->getElementsByTagName("ContNo");
			$policyNo_ret = $TradeTypes->item(0)->nodeValue;
				
			$TradeTypes = $BaseInfo->getElementsByTagName("PrtNo");
			$PrtNo_ret = $TradeTypes->item(0)->nodeValue;
				
				
			ss_log("TradeSeq: ".$TradeSeq);
			ss_log("policyNo_ret: ".$policyNo_ret);
			ss_log("PrtNo_ret: ".$PrtNo_ret);
				
		}
	
	
		////////////////////////////////////////////////////////////////
	
		if($policyNo_ret)
		{
			ss_log("投保成功,获取到保单号！policy_no: ".$policyNo_ret);
				
			$policyNo = $policyNo_ret;
			//$policy_id = $policy_arr['policy_id'];//del by wangcy, 20150113
				
			//ss_log("policy_id: ".$policy_id);
			//////////////////////////////////////////////////////////////////////
				
			/*
				$wheresql = "policy_id='$policy_id'";//根据这个找到其对应的保单存放的数据
			$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$value = $_SGLOBAL['db']->fetch_array($query);
			*/
			if(1)//$value['policy_id'])//old
			{
				/////////////////////////////////////////////////////////////////////
				ss_log("return policyNo: ".$policyNo." order_sn: ".$order_sn);
				//保存放回结果。
				$setarr = array( /* 'policy_status'=>'insured',*///这个时候不更新状态，承保后才更新
						'policy_no'=>$policyNo,
						'policy_no_prt'=>$PrtNo_ret);
	
				//$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
				updatetable(	'insurance_policy',
								$setarr,
								array('policy_id'=>$policy_id)
								);
	
				//////////////////////////////////////////////////////////////////////
				$policy_arr['policy_no'] = $policyNo;//add by wangcya, 20141011,这里出现了BUG,导致第一次投保成功后下载电子保单失败
				$policy_arr['policy_no_prt'] = $PrtNo_ret;
				$policy_arr['policy_status'] = 'insured';
				///////////////////////////////////////////////////////////////
	
	
				///////////承保//////////////////////////////////////
				ss_log("华泰投保成功，将要进行承保");
				$result_attr = post_accept_policy_huatai($policyNo,$PrtNo_ret);
				$retcode = $result_attr['retcode'];
				if($retcode==0)
				{//只有承保成功才算真正成功
					$retcode  = 0;
					$retmsg = "承保成功 !";
						
					ss_log($retmsg);
						
					$result_attr = array(	'retcode'=>$retcode,
							'retmsg'=> $retmsg
					);
				}
			}
			else
			{
				$retcode = 110;
				$retmsg = "返回保单号，但是在数据库中没查到对应的投保单,policy_id: ".$policy_id;
				ss_log($retmsg);
	
				$result_attr = array(	'retcode'=>$retcode,
						'retmsg'=> $retmsg
				);
			}
		}
		else
		{
			$result_attr = array(	'retcode'=>110,
					'retmsg'=> "返回保单号为空"
			);
		}
	}
	else
	{
	
		$retmsg = $messageStatusSubDescription;
	
		ss_log($retmsg);
	
		//$retmsg = iconv('GB2312', 'UTF-8', $retmsg); //将字符串的编码从GB2312转到UTF-8
	
		$result_attr = array(	'retcode'=>$messageStatusCode,
				'retmsg'=> $retmsg
		);
	
	
	}	
	
	return $result_attr;
}

//进行华泰的投保动作
function post_policy_huatai(
								$attribute_type,//add by wangcya, 20141213
								$attribute_code,//add by wangcya, 20141213
								$policy_arr,
								$user_info_applicant,
								$list_subject
							
							)
{

	global $_SGLOBAL,$java_class_name_huatai;
	/////////////////////////////////////////////////////////
	$result = 1;
	$retmsg = "";
	
	ss_log("into function： ".__FUNCTION__);
	
	//$orderNum = $SERIAL = $policy_arr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_arr['order_sn'];
	$policy_id = $policy_arr['policy_id'];
	
	//////////////////////////////////////////////////////////////////////////////////
	$UUID = getRandOnly_Id(0);//这个要求每次在变化。
	ss_log("huatai rand gen UUID: ".$UUID);
	
	$strxml_post_policy_content = gen_post_policy_xml_huatai(
						$policy_arr,//保单相关信息
						$user_info_applicant,//投保人信息
						$list_subject,
						$UUID
						);
	

	if($strxml_post_policy_content)
	{
		$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_huatai_policy_post.xml";
		file_put_contents($af_path,$strxml_post_policy_content);
		
		
		//start add by wangcya, 20150209,把携带序列号的也保存起来
		$FILE_UUID = getRandOnly_Id(0,1);//这个要求每次在变化。
		$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_huatai_policy_post.xml";
		file_put_contents($af_path_serial,$strxml_post_policy_content);
		//end add by wangcya, 20150209,把携带序列号的也保存起来
	}
	else
	{
		$retcode = 110;
		$retmsg = "gen_post_policy_xml_huatai is null!";
		ss_log($retmsg);
			
		$result_attr = array('retcode'=>$retcode,
				'retmsg'=> $retmsg
		);
			
		return $result_attr;
	}
	
	////////////////////////////////////////////////////////////////////
	//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	if(!defined('USE_ASYN_JAVA'))
	{
		$java_class_name = $java_class_name_huatai;
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_huatai:$java_class_name;
	//end add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	
	ss_log("before create_java_obj, java_class_name: ".$java_class_name);
	$obj_java = create_java_obj($java_class_name);
	
	///////////////////////////////////////////////////////////////////////////
	$url = URL_HUATAI_POST_POLICY;
	ss_log("huatai URL_POST_POLICY_huatai: ".$url);
	
	$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_huatai_policy_post_ret.xml";
	
	////////////////////////////////////////////////////////////////////
	if(!defined('USE_ASYN_JAVA'))//add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	{
		//进行投保的工作
		//ss_log($strxml_post_policy_content);
			
		$respXmlFileName ="";//  返回报文保存文件全路径（可空，如果为空，则不保存）
		//isDev			是否输出日志文件
		$logFilePath =  S_ROOT."xml/log/".$order_sn."_huatai_policy_post.log";
		
		$return_data = (string)$obj_java->underWriting( 	$url , 
													$strxml_post_policy_content, 
													$respXmlFileName ,
													$logFilePath,
													"Y" 
													);
		if(empty($return_data))
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

		
		$return_data = trim($return_data);
		if($return_data)
		{

			file_put_contents($ret_af_path,$return_data);
		
			$result_attr = process_post_policy_return_xml_data_huatai(	$policy_id,
															$order_sn,
															$return_data//in
														  );
			return $result_attr;
		}
		else
		{
			ss_log("error, huatai return xml is empty!");
		}
	
	}
	//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	else
	{
		//////////////////////////////////////////
		ss_log("before use asyn,doService");
			
		$xmlContent = "";//$strxml_post_policy_content;
		ss_log("xmlContent: ".$xmlContent);
			
		$postXmlFileName = $af_path;
		ss_log("postXmlFileName: ".$postXmlFileName);
			
		$postXmlEncoding = "UTF-8";
		$respXmlFileName = $ret_af_path;
		$logFileName = S_ROOT."xml/log/huatai_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
			
		ss_log("logFileName: ".$logFileName);
			
		$insurer_code = $policy_arr['insurer_code'];
		ss_log("insurer_code: ".$insurer_code);
		$callBackURL = CALLBACK_URL;
		ss_log("callBackURL: ".$callBackURL);
		$type = "insure";
		$isDev = "Y";
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
			
		$keyFile ="";
		$port ="";
		
		
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
			
		ss_log("json param_other: ".var_export($param_other,true));
			
		$jsonStr = json_encode($param_other);
			
		ss_log("将要发送华泰的投保异步请求，will doService");
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
	/////////////////////////////////////////////////////////////

	return $result_attr;

}


function process_policy_accept_return_xml_data_huatai(
														$policy_id,
														$order_sn,
														$return_data,
														$obj_java
														)
{
	global $_SGLOBAL;
	/////////////////////////////////////////////////////////////////////////////////////
	/* 张义明下面更改了，导致同步和异步处理不一样了
	$return_data = str_replace('encoding="GBK"', 'encoding="utf-8"', $return_data);
	$return_data = iconv('GB2312', 'GBK', $return_data);
	*/
	
	$xml = XmlLoader($return_data);
	
	if($xml)
	{
		$retmsg = "accept load xml ok!";
		ss_log($retmsg);
	
	}
	else
	{
		$retcode = 110;
		$retmsg = "accept loadXML fail!";
		ss_log($retmsg);
	
		$result_attr = array('retcode'=>$retcode,
				'retmsg'=> $retmsg
		);
	
		return $result_attr;
	}
	
	
	$root = $xml->documentElement;//使用DOM对象的documentElement属性可以访问XML文档的根元素
	
	$BaseInfos = $root->getElementsByTagName("BaseInfo");
	//$TradeType = $BaseInfos->item(1);
	
	foreach($BaseInfos as $BaseInfo)
	{
		//echo "1111dsfsfsdf"."<br />"; ;
		$TradeTypes = $BaseInfo->getElementsByTagName("TradeType");
		$TradeType = $TradeTypes->item(0)->nodeValue;
	
		$TradeTypes = $BaseInfo->getElementsByTagName("TradeCode");
		$TradeCode = $TradeTypes->item(0)->nodeValue;
	
		$TradeTypes = $BaseInfo->getElementsByTagName("TradeSeq");
		$TradeSeq = $TradeTypes->item(0)->nodeValue;
	
	}
	
	//////////////////////////////////////////////////////////////////
	$BaseInfos = $root->getElementsByTagName("OutputData");
	//$TradeType = $BaseInfos->item(1);
	
	foreach($BaseInfos as $BaseInfo)
	{
	
		$TradeTypes = $BaseInfo->getElementsByTagName("Flag");
		$messageStatusCode = $TradeTypes->item(0)->nodeValue;
	
		$TradeTypes = $BaseInfo->getElementsByTagName("Desc");
		$messageStatusSubDescription = $TradeTypes->item(0)->nodeValue;
	
	
	}
	
	
	ss_log("accept policy : messageStatusCode: ".$messageStatusCode);
	ss_log("accept policy : messageStatusSubDescription: ".$messageStatusSubDescription);
	
	if($messageStatusCode== '0' )//ok
	{
		//////////////////////////////////////////////////////////////////
		$BaseInfos = $root->getElementsByTagName("OutputData");
		//$TradeType = $BaseInfos->item(1);
	
		foreach($BaseInfos as $BaseInfo)
		{
			//echo "1111dsfsfsdf"."<br />"; ;
			$TradeTypes = $BaseInfo->getElementsByTagName("TradeSeq");
			$TradeSeq = $TradeTypes->item(0)->nodeValue;
	
			$TradeTypes = $BaseInfo->getElementsByTagName("ContNo");
			$policyNo_ret = $TradeTypes->item(0)->nodeValue;
	
			$TradeTypes = $BaseInfo->getElementsByTagName("PrtNo");
			$PrtNo_ret = $TradeTypes->item(0)->nodeValue;
				
			ss_log("TradeSeq: ".$TradeSeq);
			ss_log("policyNo_ret: ".$policyNo_ret);
			ss_log("PrtNo_ret: ".$PrtNo_ret);
				
			$policy_no = $policyNo = $policyNo_ret;
	
			ss_log("policyNo_ret: ".$policyNo_ret);
	
			////////////////////////////////////////////////////////////////////////
				
			$TradeTypes = $BaseInfo->getElementsByTagName("PolicyFilePath");
			$PolicyFileCont = $TradeTypes->item(0)->nodeValue;//新接口存放的是电子保单的pdf内容
			if($PolicyFileCont)
			{
				//ss_log($PolicyFileCont);
	
				$filename = S_ROOT."xml/message/".$policy_no."_huatai_policy.pdf";
				ss_log($filename);
	
				if($obj_java)
				{//华泰的是直接得到电子保单，不必再次异步请求了。
					ss_log("将要保存华泰的电子保单pdf");
					$obj_java->savePDF2File( $PolicyFileCont , $filename );
				}
	
	
			}
				
	
		}
	
		////////////////////////////////////////////////////////////////
	
		if($policyNo_ret)
		{
			ss_log("返回保单号为: ".$policyNo_ret);
	
			if($policy_id)//old
			{
			
	
				////////////////////////////////////////////////////////////////////////////
				
				//更改订单中已经投保成功的保单数量
				updatetable(	'insurance_policy',
								array('policy_status'=>'insured'),
								array('policy_id'=>$policy_id)
								);
								

				//start add by wangcya, 20150325,投保成功，增加对应的订单上的投保成功的个数///////////////////
				
				
				$sql = "UPDATE bx_order_info SET insured_policy_num=insured_policy_num+1 WHERE order_sn=".$order_sn;
				ss_log($sql);
				$_SGLOBAL['db']->query($sql);
				//end add by wangcya, 20150325,投保成功，增加对应的订单上的投保成功的个数///////////////////
				
				
				/////////////////////////////////////////////////////////////////////
				//$policy_arr['policy_no'] = $policyNo;//add by wangcya, 20141011,这里出现了BUG,导致第一次投保成功后下载电子保单失败
				//$policy_arr['policy_no_prt'] = $PrtNo_ret;
				//$policy_arr['policy_status'] = 'insured';
				///////////////////////////////////////////////////////////////
				$retcode  = 0;
				$retmsg = "承保成功!";
	
				ss_log($retmsg);
	
				$result_attr = array(	'retcode'=>110,
						'retmsg'=> $retmsg
				);
	
				return $result_attr;
			}
			else
			{
				$retcode = 110;
				$retmsg = "返回保单号，但是在数据库中没查到对应的投保单,policyNo_ret: ".$policyNo_ret;
				ss_log($retmsg);
	
				$result_attr = array(	'retcode'=>$retcode,
						'retmsg'=> $retmsg
				);
			}
		}
		else
		{
			ss_log("返回保单号为空");
			$result_attr = array(	'retcode'=>110,
					'retmsg'=> "返回保单号为空"
			);
		}
	}
	else
	{
		//$retmsg = iconv('GB2312', 'UTF-8', $messageStatusSubDescription); //将字符串的编码从GB2312转到UTF-8
		$retmsg = $messageStatusSubDescription;
		
		//下层的java都进行了处理，是好像都是GBK的，包括太平洋，所以要转换。
		//$retmsg =  mb_convert_encoding($retmsg, "UTF-8","GBK" ); //已知原编码为GBK, 转换为UTF-8,为何不需做转换呢？
		
		//ss_log("after tranfer,将要保存保单的返回信息！".$retmsg );
		if(empty($messageStatusCode))
		{
			$result_attr = array(	'retcode'=>110,
					'retmsg'=> "华泰返回为空"
			);
		}
		else
		{
			$result_attr = array(	'retcode'=>$messageStatusCode,
					'retmsg'=> $retmsg
			);
		}
	
	}

	return $result_attr;
}

function gen_post_accept_policy_xml_huatai(	$policy_arr,
											$UUID
											)
{
	global $_SGLOBAL;
	
	ss_log("into function: ".__FUNCTION__);
	/////////////////////////////////////////////////////////////////
	$DATE = date('Ymd',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30
	$orderNum = $SERIAL = $policy_arr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_arr['order_sn'];
	$policy_no = $policy_arr['policy_no'];
	$policy_no_prt = $policy_arr['policy_no_prt'];
	///////////////////////////////////////////////////////////////////////
	
	ss_log("huatai rand gen UUID: ".$UUID);
	////////////////////////////////////////////////////////////
	$key =  md5($UUID."huatairenshou".$DATE);
	
	////////////////////////////////////////////////////////////
	$strxml = '<?xml version="1.0" encoding="GBK"?>
	<TransData>
	<BaseInfo>
	<TradeType>WEBSITE</TradeType>
	<TradeCode>002</TradeCode>
	<SubTradeCode>1</SubTradeCode>
	<TradeSeq>'.$UUID.'</TradeSeq>
	<TradeDate>'.$DATE.'</TradeDate>
	<TradeTime>'.$TIME.'</TradeTime>
	<Operator>WEB003</Operator>
	<Key>'.$key.'</Key>
	</BaseInfo>
	<InputData>
	<TradeSeq>'.$UUID.'</TradeSeq>
	<TranDate>'.$DATE.'</TranDate>
	<TranTime>'.$TIME.'</TranTime>
	<!-- 核保返回结果中查找 -->
	<PrtNo>'.$policy_no_prt.'</PrtNo>
	<!-- 核保返回结果中查找 -->
	<ContNo>'.$policy_no.'</ContNo>
	<PolApplyDate>'.$DATE.'</PolApplyDate>
	</InputData>
	</TransData>';
	//////////////////////////////////////////////////////////////////////////////
	$strxml = trim($strxml);	
	
	return $strxml;
}

//承保的请求
function post_accept_policy_huatai($policy_no,$policy_no_prt)
{

	global $_SGLOBAL,$java_class_name_huatai;
	///////////////////////////////////////////////////////////////////////

	ss_log("into function： ".__FUNCTION__);
	
	ss_log("policyNo_ret: ".$policy_no." PrtNo_ret: ".$policy_no_prt);
	/////////////////////////////////////////////////////////
	$result = 1;
	$retmsg = "";
	//////////////////////////////////////////////////////////////////////
	$wheresql = "policy_no='$policy_no'";//根据这个找到其对应的保单存放的数据
	$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$policy_arr = $_SGLOBAL['db']->fetch_array($query);
	if(!$policy_arr['policy_id'])
	{
		//在数据库中没找到对应的投保单
		$retcode = 110;
		$retmsg = "在数据库中没找到对应的投保单! policy_no: ".$policy_no;
		ss_log($retmsg);
		
		$result_attr = array('retcode'=>$retcode,
				'retmsg'=> $retmsg
		);
		
		return $result_attr;
	}
	//////////////////////////////////////////////////////////////////////
	$policy_id = $policy_arr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	$order_sn = $policy_arr['order_sn'];
	///////////////////////////////////////////////////
	$policy_arr['policy_no'] = $policy_no;
	$policy_arr['policy_no_prt'] = $policy_no_prt;

	/////////////////////////////////////////////////////////////////////
	$UUID = getRandOnly_Id(0);//这个要求每次在变化。
	
	$strxml_post_policy_content = gen_post_accept_policy_xml_huatai($policy_arr, $UUID);
	
	//$strxml_post_policy_content =  mb_convert_encoding($strxml_post_policy_content, "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
	
	if($strxml_post_policy_content)
	{
		$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_huatai_policy_post_accept.xml";
		file_put_contents($af_path,$strxml_post_policy_content);
		
		
		//start add by wangcya, 20150209,把携带序列号的也保存起来
		$FILE_UUID = getRandOnly_Id(0,1);
		$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_huatai_policy_post_accept.xml";
		file_put_contents($af_path_serial,$strxml_post_policy_content);
		//end add by wangcya, 20150209,把携带序列号的也保存起来
	}
	else
	{
		//$retmsg = iconv('GB2312', 'UTF-8', $retmsg); //将字符串的编码从GB2312转到UTF-8
		$retcode = 110;
		$retmsg = "huatai accept strxml_post_policy_content is null!";
		ss_log($retmsg);
			
		$result_attr = array('retcode'=>$retcode,
				'retmsg'=> $retmsg
		);
			
		return $result_attr;
	}
	
	//进行投保的工作
	$url = URL_HUATAI_POST_POLICY;
	ss_log("huatai URL_POST_POLICY_huatai: ".$url);
	
	////////////////////////////////////////////////////////
	$respXmlFileName = S_ROOT."xml/log/huatai/resp";//  返回报文保存文件全路径（可空，如果为空，则不保存）
	//isDev			是否输出日志文件
	$logFilePath =  S_ROOT."xml/log/".$order_sn."_huatai_policy_accept.log";
	
	////////////////////////////////////////////////////////////////////
	//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	if(!defined('USE_ASYN_JAVA'))
	{
		$java_class_name = $java_class_name_huatai;
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_huatai:$java_class_name;
	//end add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	
	
	$obj_java = create_java_obj($java_class_name);
	////////////////////////////////////////////////////////////////////
	$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_huatai_policy_post_accept_ret.xml";
	
	////////////////////////////////////////////////////////////////////
	if(!defined('USE_ASYN_JAVA'))//add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	{
		//进行投保的工作
		ss_log("huatai will java accept applyPolicy");
			
		$return_data = $obj_java->underWriting( 	$url ,
													$strxml_post_policy_content,
													$respXmlFileName ,
													$logFilePath,
													"Y"
												);
		ss_log("before huatai accept , underWriting, before".$return_data);
		
		$return_data = (string)$return_data;
		
		ss_log("before huatai accept , underWriting, after".$return_data);
		
		/////////////////////////////////////////////////////////////
		if(!empty($return_data))
		{
			
			file_put_contents($ret_af_path,$return_data);
				
			
			$result_attr = process_policy_accept_return_xml_data_huatai(
					$policy_id,
					$order_sn,
					$return_data,
					$obj_java
			);
		}
		else
		{
			//$retmsg = iconv('GB2312', 'UTF-8', $retmsg); //将字符串的编码从GB2312转到UTF-8
			$retcode = 110;
			$retmsg = "accept policy,result_attr return date is null!";
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
		ss_log("before use asyn,doService");
			
		$xmlContent = "";//$strxml_post_policy_content;
		ss_log("xmlContent: ".$xmlContent);
			
		$postXmlFileName = $af_path;
		ss_log("postXmlFileName: ".$postXmlFileName);
			
		$postXmlEncoding = "UTF-8";
		$respXmlFileName = $ret_af_path;
		$logFileName = S_ROOT."xml/log/huatai_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
			
		ss_log("logFileName: ".$logFileName);
			
		$insurer_code = $policy_arr['insurer_code'];
		ss_log("insurer_code: ".$insurer_code);
		$callBackURL = CALLBACK_URL;
		ss_log("callBackURL: ".$callBackURL);
		$type = "insure_accept";//这个承保要区分开
		ss_log("type: ".$type);
		$isDev = "Y";
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
			
		$keyFile ="";
		$port ="";
		
		
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
			
		ss_log("json param_other: ".var_export($param_other,true));
			
		$jsonStr = json_encode($param_other);
			
		ss_log("将要发送承保的异步请求，will doService");
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
	////////////////////////////////////////////////////////////////////////////
	return $result_attr;
}

//获取电子保单
function get_policyfile_huatai_luyou($pdf_filename,
									 $policy_attr
		                            )
{

	ss_log("into ".__FUNCTION__);
	///////////////////////////////////////////////////////////////////////////////////
	$use_asyn = false;
	$result_attr = get_policy_file_huatai($use_asyn,$policy_attr,$pdf_filename);
		
	return $result_attr;
	
}

function gen_xml_getpolicyfile_huatai($policy_attr)
{
	global $_SGLOBAL;
	//////////////////////////////////////////////////////
	$policy_id = $policy_attr['policy_id'];
	
	//////////////////////////////////////////////////////////
	if(!empty($policy_attr))
	{
		$policy_no 		= $policy_attr['policy_no'];
		$policy_no_prt 	= $policy_attr['policy_no_prt'];
		$orderNum 		= $policy_attr['order_num'];//现在用保单号来存放电子保单
		$order_sn 		= $policy_attr['order_sn'];
	
		$business_type 		= $policy_attr['business_type'];
	
		ss_log("policy_no: ".$policy_no);
		ss_log("policy_no_prt: ".$policy_no_prt);
		ss_log("orderNum: ".$orderNum);
		ss_log("order_sn: ".$order_sn);
	}
	else
	{
		$result = 110;
		$retmsg = "get policy file fail, not find policy , policy_id:".$policy_id;
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
	
		return $result_attr;
	}
	
	
	/////////////////////////////////////////////////////////////////
	$DATE = date('Ymd',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30
	$orderNum = $SERIAL = $policy_attr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_attr['order_sn'];
	$UUID = getRandOnly_Id(0);//这个要求每次在变化。
	
	ss_log("huatai rand gen UUID: ".$UUID);
	////////////////////////////////////////////////////////////
	$key =  md5($UUID."huatairenshou".$DATE);
	
	////////////////////////////////////////////////////////////
	$strxml = '<?xml version="1.0" encoding="GBK"?>
	<TransData>
	<BaseInfo>
	<TradeType>WEBSITE</TradeType>
	<TradeCode>003</TradeCode>
	<SubTradeCode>1</SubTradeCode>
	<TradeSeq>'.$UUID.'</TradeSeq>
	<TradeDate>'.$DATE.'</TradeDate>
	<TradeTime>'.$TIME.'</TradeTime>
	<Operator>WEB003</Operator>
	<Key>'.$key.'</Key>
	</BaseInfo>
	<InputData>
	<TradeSeq>'.$UUID.'</TradeSeq>
	<TranDate>'.$DATE.'</TranDate>
	<TranTime>'.$TIME.'</TranTime>
	<ContInfo>
	<!-- 核保返回结果中查找 -->
	<PrtNo>'.$policy_no_prt.'</PrtNo>
	<!-- 核保返回结果中查找 -->
	<ContNo>'.$policy_no.'</ContNo>
	</ContInfo>
	</InputData>
	</TransData>';
	//////////////////////////////////////////////////////////////////////////////
	$strxml = trim($strxml);
	
	return $strxml;
}

function process_getpolicyfile_return_xml_huatai(  $policy_attr, $return_data, $pdf_path , $obj_java )
{
	//$xml = new DOMDocument("1.0","GBK");
	//$ret = $xml->loadXML($return_data);
	//$ret = $xml->load($path);
	$return_data = str_replace('encoding="GBK"', 'encoding="utf-8"', $return_data);
	$return_data = iconv('GB2312', 'GBK', $return_data);
	
	$xml = XmlLoader($return_data);
	
	if($xml)
	{
		$retmsg = "accept load xml ok!";
		ss_log($retmsg);
	
	}
	else
	{
		$retcode = 110;
		$retmsg = "get plolicy pdf file, loadXML fail!";
		ss_log($retmsg);
	
		$result_attr = array('retcode'=>$retcode,
				'retmsg'=> $retmsg
		);
	
		return $result_attr;
	}
	
	
	$root = $xml->documentElement;//使用DOM对象的documentElement属性可以访问XML文档的根元素
	
	$BaseInfos = $root->getElementsByTagName("BaseInfo");
	//$TradeType = $BaseInfos->item(1);
	
	foreach($BaseInfos as $BaseInfo)
	{
		//echo "1111dsfsfsdf"."<br />"; ;
		$TradeTypes = $BaseInfo->getElementsByTagName("TradeType");
		$TradeType = $TradeTypes->item(0)->nodeValue;
	
		$TradeTypes = $BaseInfo->getElementsByTagName("TradeCode");
		$TradeCode = $TradeTypes->item(0)->nodeValue;
	
		$TradeTypes = $BaseInfo->getElementsByTagName("TradeSeq");
		$TradeSeq = $TradeTypes->item(0)->nodeValue;
	
	}
	
	//////////////////////////////////////////////////////////////////
	$BaseInfos = $root->getElementsByTagName("OutputData");
	//$TradeType = $BaseInfos->item(1);
	
	foreach($BaseInfos as $BaseInfo)
	{
	
		$TradeTypes = $BaseInfo->getElementsByTagName("Flag");
		$messageStatusCode = $TradeTypes->item(0)->nodeValue;
	
		$TradeTypes = $BaseInfo->getElementsByTagName("Desc");
		$messageStatusSubDescription = $TradeTypes->item(0)->nodeValue;
	
	
	}
	
	
	ss_log("get policy : messageStatusCode: ".$messageStatusCode);
	ss_log("get policy : messageStatusSubDescription: ".$messageStatusSubDescription);
	
	if($messageStatusCode== '0' )//ok
	{
		//////////////////////////////////////////////////////////////////
		$BaseInfos = $root->getElementsByTagName("OutputData");
		//$TradeType = $BaseInfos->item(1);
	
		foreach($BaseInfos as $BaseInfo)
		{
			//echo "1111dsfsfsdf"."<br />"; ;
			$TradeTypes = $BaseInfo->getElementsByTagName("TradeSeq");
			$TradeSeq = $TradeTypes->item(0)->nodeValue;
	
			//$TradeTypes = $BaseInfo->getElementsByTagName("ContNo");
			//$policyNo_ret = $TradeTypes->item(0)->nodeValue;
	
			//$TradeTypes = $BaseInfo->getElementsByTagName("PrtNo");
			//$PrtNo_ret = $TradeTypes->item(0)->nodeValue;
				
			ss_log("TradeSeq: ".$TradeSeq);
			//ss_log("policyNo_ret: ".$policyNo_ret);
			//ss_log("PrtNo_ret: ".$PrtNo_ret);
				
			//$policyNo = $policyNo_ret;
	
			//ss_log("policyNo_ret: ".$policyNo_ret);
	
			////////////////////////////////////////////////////////////////////////
				
			$TradeTypes = $BaseInfo->getElementsByTagName("PolicyFilePath");
			$PolicyFileCont = $TradeTypes->item(0)->nodeValue;//新接口存放的是电子保单的pdf内容
			if($PolicyFileCont)
			{
			
				$obj_java->savePDF2File( $PolicyFileCont , $pdf_path );
				////////////////////////////////////////////////////////////
				if (file_exists($pdf_path))//add by wangcya, 20150205, 如果这个文件已经存在，则返回给用户。
				{
					$policy_id = $policy_attr['policy_id'];
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
					//end add by wangcya, 20150205,不直接退出，先更新状态////////////////////////////////////
					
					if($readfile)
					{
						//add by dingchaoyang 2014-12-4 如果是手机端访问，不输出文件，直接返回文件路径
// 						include_once (str_replace('baoxian','',S_ROOT)  . 'api/EBaoApp/platformEnvironment.class.php');
// 						if (!(PlatformEnvironment::isMobilePlatform()))
// 						{
							header('Content-type: application/pdf');
							readfile($pdf_path);//则返回给用户。
							exit(0);
							///////////////////////////////////////////////////////////
// 						}
						//end by dingchaoyang 2014-12-4 如果是手机端访问，不输出文件，直接返回文件路径
					}
				
					$result_attr = array(	'retcode'=>$retcode,
							'retmsg'=> $retmsg,
							'retFileName'=>str_replace(S_ROOT,'',$pdf_path)
					);//add by dingchaoyang 2014-12-4添加retfilename
		
					return $result_attr;
				}
			}
				
	
		}//foreach
	}//ok
	else
	{
		if(empty($messageStatusCode))
		{
			$result = 110;
			$retmsg = "返回为空";
			ss_log($retmsg);
		
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
		}
		else
		{
			//$retmsg = iconv('GB2312', 'UTF-8', $messageStatusSubDescription); //将字符串的编码从GB2312转到UTF-8
			$retmsg = $messageStatusSubDescription;
			$result_attr = array(	'retcode'=>$messageStatusCode,
					'retmsg'=> $retmsg
			);
	
		}
	
	}
	
	return $result_attr;
}

function get_policy_file_huatai($use_asyn,
		                        $policy_attr,
								$pdf_filename
		                        )
{
	global $_SGLOBAL, $java_class_name_huatai;
	ss_log("into ".__FUNCTION__);
	//////////////////////////////////////////////////////
	$policy_id = $policy_attr['policy_id'];
	$order_sn  = $policy_attr['order_sn'];
	$policy_no  = $policy_attr['policy_no'];
	//////////////////////////////////////////////////////
	
	$strxml = gen_xml_getpolicyfile_huatai($policy_attr);

	//$strxml =  mb_convert_encoding($strxml, "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
	
	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_huatai_policy_get_pdf.xml";
	file_put_contents($af_path,$strxml);
	
	////////////////////////////////////////////////////////////////////

	//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	if(!$use_asyn)//!defined('USE_ASYN_JAVA'))
	{
		$java_class_name = $java_class_name_huatai;
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_huatai:$java_class_name;
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
	
	//进行投保的工作
	$url = URL_HUATAI_POST_POLICY;
	ss_log("huatai URL_POST_POLICY_huatai: ".$url);
	
	////////////////////////////////////////////////////////
	$respXmlFileName ="";//  返回报文保存文件全路径（可空，如果为空，则不保存）
	//isDev			是否输出日志文件

	$logFileName = S_ROOT."xml/log/taipingyang_withdraw_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	
	
	if(!$use_asyn)//!defined('USE_ASYN_JAVA'))//add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	{	
		$return_data = (string)$obj_java->underWriting( 
					                                	$url ,
														$strxml,
														$respXmlFileName ,
														$logFileName,
														"Y"
													 );
		/////////////////////////////////////////////////////////////
		if(empty($return_data))
		{
			//$retmsg = iconv('GB2312', 'UTF-8', $retmsg); //将字符串的编码从GB2312转到UTF-8
			$retcode = 110;
			$retmsg = "get policy pdf file,result_attr return date is null!";
			ss_log($retmsg);
		
			$result_attr = array('retcode'=>$retcode,
					'retmsg'=> $retmsg
			);
		
			return $result_attr;
		}
		
		////////////////////////////////////////////////////////////////////
	
		$return_data = trim($return_data);
		if($return_data)
		{
			$af_path_ret = S_ROOT."xml/".$order_sn."_".$policy_id."_huatai_policy_get_pdf_ret.xml";
			file_put_contents($af_path_ret,$return_data);
			
			/////////先解析出来返回的xml//////////////////////////////////
			//$pdf_path = S_ROOT."xml/message/".$policy_no."_huatai_policy.pdf";
			$result_attr = process_getpolicyfile_return_xml_huatai(  $policy_attr, $return_data, $pdf_filename , $obj_java );
			return $result_attr;
		}
		else
		{
			$result = 110;
			$retmsg = "get remote policy file ok, but file not find!";
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
			return $result_attr;
		}
	}
	else
	{
		//////////////////////////////////////////

		ss_log("将要发送获取电子保单的异步请求，will doService");
		
		$insurer_code = $policy_attr['insurer_code'];
		$callBackURL = CALLBACK_URL;
		$type = "getpolicyfile";
		
		ss_log("insurer_code: ".$insurer_code);
		ss_log("callBackURL: ".$callBackURL);
		ss_log("type: ".$type);
		ss_log("policy_id: ".$policy_id);
		//////////////////////////////////////////////////////////////////////////
	
			
		/*华泰下载电子保单参数json部分key：
		private String url;
		private String xmlContent;
		private String respXmlFileName;
		private String logFileName;
		private String postXmlFileName;
		private String postXmlEncoding;
		private String isDev;        取值：Y或N
		*/

		$xmlContent = "";//$strxml_post_policy_content;
		ss_log("xmlContent: ".$xmlContent);
		
		$postXmlFileName = $af_path;
		ss_log("postXmlFileName: ".$postXmlFileName);
		
		$postXmlEncoding = "UTF-8";
		$respXmlFileName = $af_path_ret;
			
		ss_log("logFileName: ".$logFileName);
		$isDev = "Y";
		
	
		$param_other = array(
				"url"=>$url ,//投保保险公司服务地址
				//"port"=>$port ,//端口
				"xmlContent"=>$xmlContent ,//投保报文内容
				"postXmlFileName"=>$postXmlFileName,//投保报文文件
				"postXmlEncoding"=>$postXmlEncoding ,//投保报文文件编码格式
				"respXmlFileName"=>$respXmlFileName,//返回报文保存全路径
				"logFileName"=>$logFileName, //日志文件
				"isDev"=>$isDev//
		);
		
		$jsonStr = json_encode($param_other);
	
		ss_log("param_other: ".var_export($param_other,true));
	
		///////////////////////////////////////////////////////////////////////////
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

////注销保单
function withdraw_policy_huatai_luyou( $policy_id )
{
	global $_SGLOBAL,$java_class_name_huatai;
	/////////////////////////////////////////////////////

	$result = 1;
	$retmsg = "";
	/////////////////////////////////////////////////////////
	ss_log("in function withdraw_policy_huatai_luyou: policy_id".$policy_id);

	if($policy_id)
	{
		$wheresql = "p.policy_id='$policy_id'";

		$sql = "SELECT * FROM ".tname('insurance_policy')." p WHERE $wheresql LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$policy_attr = $_SGLOBAL['db']->fetch_array($query);
	}
	/////////////////////////////////////////////////////

	if(!empty($policy_attr))
	{
		$policy_no 		= $policy_attr['policy_no'];
		$policy_no_prt 	= $policy_attr['policy_no_prt'];
		$orderNum = $SERIAL = $policy_attr['order_num'];//这个填写的就是订单号
		$order_sn = $policy_attr['order_sn'];//add by wangcya, 20141023
		
		$business_type 		= $policy_attr['business_type'];
		
		ss_log("policy_no: ".$policy_no);
		ss_log("policy_no_prt: ".$policy_no_prt);
		ss_log("orderNum: ".$orderNum);
		ss_log("order_sn: ".$order_sn);
	}
	else
	{
		$result = 110;
		$retmsg = "withdraw_policy fail, not find policy , policy_id: ".$policy_id;
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);

		return $result_attr;
	}


	ss_log("will get policy file, POLICYNO: ".$policy_no." policy_no_prt: ".$policy_no_prt." orderNum: ".$orderNum);
	ss_log("order_sn: ".$order_sn);
	///////////////////////////////////////////////////////////////////////////////////

	/////////////////////////////////////////////////////////////////
	$DATE = date('Ymd',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30

	$UUID = getRandOnly_Id(0);//这个要求每次在变化。
	//////////////////////////////////////////////////////////
	ss_log("huatai rand gen UUID: ".$UUID);
	
	////////////////////////////////////////////////////////////
	$key =  md5($UUID."huatairenshou".$DATE);
	

	////////////////////////////////////////////////////////////
	$strxml = '<?xml version="1.0" encoding="GBK"?>
							<TransData>
								<BaseInfo>
									<TradeType>WEBSITE</TradeType>
									<TradeCode>004</TradeCode>
									<SubTradeCode>1</SubTradeCode>
									<TradeSeq>'.$UUID.'</TradeSeq>
									<TradeDate>'.$DATE.'</TradeDate>
									<TradeTime>'.$TIME.'</TradeTime>
									<Operator>WEB003</Operator>
									<Key>'.$key.'</Key>
								</BaseInfo>
								<InputData>
									<TradeSeq>'.$UUID.'</TradeSeq>
									<TranDate>'.$DATE.'</TranDate>
									<TranTime>'.$TIME.'</TranTime>
									<!-- 核保返回结果中查找 -->
									<PrtNo>'.$policy_no_prt.'</PrtNo>
									<!-- 核保返回结果中查找 -->
									<ContNo>'.$policy_no.'</ContNo>
								</InputData>
							</TransData>';
	
	//////////////////////////////////////////////////////////////////////////////
	$strxml = trim($strxml);
	//$strxml =  mb_convert_encoding($strxml, "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
	
	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_huatai_policy_withdraw.xml";
	file_put_contents($af_path,$strxml);
	
	//start add by wangcya, 20150209,把携带序列号的也保存起来
	$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_huatai_policy_withdraw.xml";
	file_put_contents($af_path_serial,$strxml);
	//end add by wangcya, 20150209,把携带序列号的也保存起来
	
	////////////////////////////////////////////////////////////////////

	$p = create_java_obj($java_class_name_huatai);
	////////////////////////////////////////////////////////////////////
	
	//进行投保的工作
	$url = URL_HUATAI_POST_POLICY;
	ss_log("huatai URL_POST_POLICY_huatai: ".$url);
	
	//ss_log($strxml);
	
	
	//iconv('UTF-8', 'GKB', $requestParam);
	
	
	////////////////////////////////////////////////////////
	$respXmlFileName ="";//  返回报文保存文件全路径（可空，如果为空，则不保存）
	//isDev			是否输出日志文件
	$logFilePath =  S_ROOT."xml/log/".$order_sn."_huatai_policy_withdraw.log";
	
	$return_data = (string)$p->underWriting( 	$url ,
			$strxml,
			$respXmlFileName ,
			$logFilePath,
			"Y"
	);
	/////////////////////////////////////////////////////////////
	if(empty($return_data))
	{
		//$retmsg = iconv('GB2312', 'UTF-8', $retmsg); //将字符串的编码从GB2312转到UTF-8
		$retcode = 110;
		$retmsg = "withdraw policy,result_attr return date is null!";
		ss_log($retmsg);
	
		$result_attr = array('retcode'=>$retcode,
				'retmsg'=> $retmsg
		);
	
		return $result_attr;
	}
	
	////////////////////////////////////////////////////////////////////
	
	$return_data = trim($return_data);
	
	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_huatai_policy_withdraw_ret.xml";
	file_put_contents($af_path,$return_data);
	
	/////////先解析出来返回的xml//////////////////////////////////
	
	//$xml = new DOMDocument("1.0","GBK");
	//$ret = $xml->loadXML($return_data);
	//$ret = $xml->load($path);
	
	$return_data = str_replace('encoding="GBK"', 'encoding="utf-8"', $return_data);
	$return_data = iconv('GB2312', 'GBK', $return_data);
	
	$xml = XmlLoader($return_data);
	
	if($xml)
	{
		$retmsg = "accept load xml ok!";
		ss_log($retmsg);
	
	}
	else
	{
		$retcode = 110;
		$retmsg = "get plolicy pdf file, loadXML fail!";
		ss_log($retmsg);
	
		$result_attr = array('retcode'=>$retcode,
				'retmsg'=> $retmsg
		);
	
		return $result_attr;
	}
	
	
	$root = $xml->documentElement;//使用DOM对象的documentElement属性可以访问XML文档的根元素
	
	$BaseInfos = $root->getElementsByTagName("BaseInfo");
	//$TradeType = $BaseInfos->item(1);
	
	foreach($BaseInfos as $BaseInfo)
	{
		//echo "1111dsfsfsdf"."<br />"; ;
		$TradeTypes = $BaseInfo->getElementsByTagName("TradeType");
		$TradeType = $TradeTypes->item(0)->nodeValue;
	
		$TradeTypes = $BaseInfo->getElementsByTagName("TradeCode");
		$TradeCode = $TradeTypes->item(0)->nodeValue;
	
		$TradeTypes = $BaseInfo->getElementsByTagName("TradeSeq");
		$TradeSeq = $TradeTypes->item(0)->nodeValue;
	
	}
	
	//////////////////////////////////////////////////////////////////
	$BaseInfos = $root->getElementsByTagName("OutputData");
	//$TradeType = $BaseInfos->item(1);
	
	foreach($BaseInfos as $BaseInfo)
	{
	
		$TradeTypes = $BaseInfo->getElementsByTagName("Flag");
		$messageStatusCode = $TradeTypes->item(0)->nodeValue;
	
		$TradeTypes = $BaseInfo->getElementsByTagName("Desc");
		$messageStatusSubDescription = $TradeTypes->item(0)->nodeValue;
	
	
	}
	
	
	ss_log("get policy : messageStatusCode: ".$messageStatusCode);
	ss_log("get policy : messageStatusSubDescription: ".$messageStatusSubDescription);
	
	if($messageStatusCode== '0' )//ok
	{
		//////////////////////////////////////////////////////////////////
		$BaseInfos = $root->getElementsByTagName("OutputData");
		//$TradeType = $BaseInfos->item(1);
	
		foreach($BaseInfos as $BaseInfo)
		{
			//echo "1111dsfsfsdf"."<br />"; ;
			$TradeTypes = $BaseInfo->getElementsByTagName("TradeSeq");
			$TradeSeq = $TradeTypes->item(0)->nodeValue;
	
			//$TradeTypes = $BaseInfo->getElementsByTagName("ContNo");
			//$policyNo_ret = $TradeTypes->item(0)->nodeValue;
	
			//$TradeTypes = $BaseInfo->getElementsByTagName("PrtNo");
			//$PrtNo_ret = $TradeTypes->item(0)->nodeValue;
				
			ss_log("TradeSeq: ".$TradeSeq);
			//ss_log("policyNo_ret: ".$policyNo_ret);
			//ss_log("PrtNo_ret: ".$PrtNo_ret);
		
			$retcode  = 0;
			$retmsg = "注销电子保单成功!";
			
			ss_log($retmsg);
			
			$result_attr = array(	'retcode'=>$retcode,
									'retmsg'=> $retmsg
								);
			
			return $result_attr;
	
		}//foreach
	}//ok
	else
	{
		//start add by wangcya,20150202,有节点，但是没内容
		if(empty($messageStatusCode))
		{//平安返回为空
			
			$result = 110;
			$retmsg = "华泰返回有节点，但是节点内部无内容";
				
			ss_log("result：".$result );
			ss_log("解析返回报文错误！");
			
			$result_attr = array(	'retcode'=>$result,
									'retmsg'=> $retmsg
								);
		}
		//end add by wangcya,20150202,有节点，但是没内容
		else 
		{
			//$retmsg = iconv('GB2312', 'UTF-8', $messageStatusSubDescription); //将字符串的编码从GB2312转到UTF-8
			$retmsg = $messageStatusSubDescription;
			$result_attr = array(	'retcode'=>$messageStatusCode,
					'retmsg'=> $retmsg
				);
		}
	
	
	}
	
	return $result_attr;
}

//added by zhangxi ,20150119 ,华泰境外旅游
function process_file_xls_huatai_insured($uploadfile, $product)
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
	
    global $G_XLS_UPLOAD_FORMAT_HUATAI_LVYOU;
    $format_arr =  $G_XLS_UPLOAD_FORMAT_HUATAI_LVYOU;
	$rows = array();
	//added by zhangxi, 20141210, 
	//根据公司excel模板文件来处理
	//模板文件1列证件类型，2列证件号，3列职业类别，4列性别，5列生日，8列EMAIL，9列联系电话
	
	for ($row = 11;$row <= $highestRow; $row++)
	{
		$strs_row = array();
		//$index=0;
		$normal_row_flag =1;
		//注意highestColumnIndex的列数索引从0开始
		$applicant_cert_flag=0;
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
				
				if($format_arr[$col] == 'applicant_birthday')
				{
					if(strlen($strs_row[$format_arr[$col]]) == 5)
					{
						$n = intval(($strs_row[$format_arr[$col]] - 25569)*3600*24); //转换成1970年以来的秒数
						$strs_row[$format_arr[$col]]= gmdate('Y-m-d',$n);//格式化时间
					}
						
				}
				
				if($format_arr[$col] == 'assured_name' && empty($strs_row[$format_arr[$col]]))
				{	
					//被保险人姓名为空的行，直接不取
					$normal_row_flag =0;
					break;
				}
				//added by zhangxi, 20141217,出生日期格式校验
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

				}

			}
		}

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

?>