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
$java_class_name_xinhua = 'com.yanda.imsp.util.ins.NCIPolicyFecher';
$LOGFILE_XINHUA=S_ROOT."log/java_xinhua_post_policy.log";
////////////////////////////////////////////////////////////////////////////

$attr_xinhua_policy_interface = array(
									"100001"=>array("interface_name"=>"核保",
													"trans_code"=>"100001",
													"interface_url"=>"/insurance/proposal/apply.rest"
													),
									"100003"=>array("interface_name"=>"承保",
													"trans_code"=>"100003",
													"interface_url"=>"/insurance/policy/apply.rest"
													),
									"100005"=>array("interface_name"=>"保单价值查询",
													"trans_code"=>"100005",
													"interface_url"=>"/insurance/policy/valueQuery.rest"
													),
									"100006"=>array("interface_name"=>"退保核算",
													"trans_code"=>"100006",
													"interface_url"=>"/insurance/policy/refundCalc.rest"
													),
									"100007"=>array("interface_name"=>"退保",
													"trans_code"=>"100007",
													"interface_url"=>"/insurance/policy/refundApply.rest"
													),
									"100009"=>array("interface_name"=>"保单确认通知",
													"trans_code"=>"100009",
													"interface_url"=>"/insurance/policy/confirmNotify.rest"
													),
									"100012"=>array("interface_name"=>"保单详细查询",
													"trans_code"=>"100012",
													"interface_url"=>"/insurance/policy/detailQuery.rest"
													),
									"100013"=>array("interface_name"=>"保费追加",
													"trans_code"=>"100013",
													"interface_url"=>"/insurance/policy/appendPremium.rest"
													)
									);
									
//受益人与被保险人之间关系
$attr_xinhua_relationship_benefit_with_applicant = array(
	//"00"=>"本人",
	"01"=>"父子",
	"02"=>"父女",
	"03"=>"母子",
	"04"=>"母女",
	//"05"=>"祖孙",
	//"07"=>"夫妻",
//	"08"=>"兄弟",
//	"09"=>"兄妹",
//	"10"=>"姐弟",
//	"11"=>"姐妹",
//	"12"=>"叔侄",
//	"13"=>"姑侄",
//	"14"=>"外甥",
//	"15"=>"媳",
//	"16"=>"婿",
//	"17"=>"姐夫",
//	"18"=>"朋友",
//	"19"=>"同事",
//	"20"=>"师生",
//	"21"=>"劳动关系",
//	"22"=>"其他",
//	"23"=>"法定",
//	"24"=>"子女"
);

//被保险人与投保人关系,接安心宝贝，其他关系暂时注销掉

$attr_xinhua_relationship_with_applicant = array(
//		"1"=>"本人",
//		"2"=>"子女",
//		"3"=>"夫妻",
//		"7"=>"其他",
//		"10"=>"父母",
//		"11"=>"兄弟",
//		"12"=>"祖孙",
//		"16"=>"法人",
//		"18"=>"劳动关系",
		"21"=>"父子",
		"22"=>"母子",
//		"27"=>"叔侄",
//		"28"=>"姑侄",
//		"30"=>"外甥",
		"31"=>"父女",
		"32"=>"母女",
//		"33"=>"兄妹",
//		"34"=>"姐弟",
//		"35"=>"姐妹",
//		"36"=>"媳",
//		"37"=>"婿",
//		"38"=>"姐夫",
//		"39"=>"朋友",
//		"40"=>"同事",
//		"41"=>"师生",
		
);

$attr_xinhua_nation = array(
		"37"=>"中国",
		
);


//<!--证件类型，安心少儿的要求，所以其他的都注释掉了。20150515
$attr_xinhua_certificates_type = array(
		"1"=>"身份证",
		"2"=>"军人证",
		"3"=>"护照",
		//"4"=>"出生证明",
		"7"=>"户口本",
		//"9"=>"其它",
		//"13"=>"驾照",
		//"19"=>"数据转换证件",
		"20"=>"港澳台居民内地通行证",
);

$attr_xinhua_certificates_type_single_unify = array(
		"1"=>1,//'身份证';
		"3"=>4,//'护照';
		"20"=>5,//'港澳回乡证或台胞证';
		"2"=>8,//'军人证';
		"9"=>9,//'其他';
);
//缴费方式
$attr_xinhua_pay_type = array(
		"0"=>"无关或不确定",
		"1"=>"年交",
		"2"=>"半年缴",
		"3"=>"季交",
		"4"=>"月交",
		"5"=>"趸交",
		"6"=>"不定期",

);
//续期付款方式
$attr_xinhua_paymode = array(
		"1"=>"现金",
		"2"=>"支票",
		"3"=>"银行转账",
		"5"=>"内部转账",
		"30"=>"信用卡",
		"59"=>"现金存款薄",
		"63"=>"银行收款",
		"64"=>"POS机",
		"93"=>"银行代扣",
		"94"=>"邮政业务"
);
//保单状态
$attr_xinhua_policy_status = array(
		"0"=>"无记录",
		"1"=>"待核保",
		"2"=>"核保通过",
		"3"=>"核保失败",
		"4"=>"出单中",
		"5"=>"出单成功",
		"6"=>"出单失败",
);
//保全类型
$attr_xinhua_baoquan_type = array(
		"0"=>"当日撤销",
		"1"=>"犹豫期退保",
		"2"=>"正常退保",
		"3"=>"部分领取",
		"4"=>"全部领取",
);
$attr_xinhua_payment_deadline = array(
		"0"=>"无关",
		"1"=>"趸交",
		"2"=>"按年限交",
		"3"=>"交至某确定年龄",
		"4"=>"终生交费"
);

$attr_xinhua_anxinbaobei_payPeriodUnit = array(
	"1"=>"一次交清",
	"2"=>"3年交",
	"2"=>"5年交",
	"2"=>"10年交",
	"3"=>"交至18岁"
);
$attr_xinhua_applyNum_insAmount = array(
	"10" =>"10万",
	"15" =>"15万",
	"20" =>"20万",
	"25" =>"25万",
	"30" =>"30万"
); 

$attr_xinhua_applicant_gender = array(
		"M"=>"男",
		"F"=>"女"
);
							
$attr_xinhua_sex_unify = array("F"=>"F",
		"M"=>"M",
);

//保障年期代码
$attr_xinhua_period_unit = array(
		"1"=>"保终身",
		"2"=>"按年限保",
		"3"=>"保至某确定年龄",
		"4"=>"按月保",
		"5"=>"按天保",
);


$attr_certificates_type_xinhua_single_unify = array(
		"1"=>1,//'身份证';
		"13"=>2,//'驾驶证';
		"3"=>4,//'护照';
		"20"=>5,//'港澳回乡证或台胞证';
		"2"=>8,//'军人证';
		"9"=>9,//'其他';
);

$attr_pay_period_commision_rate = array(
		"0"=>"2",
		"3"=>"12",
		"5"=>"16",
		"6"=>"16",
		"6"=>"16",
		"7"=>"16",
		"8"=>"16",
		"9"=>"16",
		"10"=>"20",
		"11"=>"20",
		"12"=>"20",
		"13"=>"20",
		"14"=>"20",
		"15"=>"20",
		"16"=>"20",
		"17"=>"20",
		"18"=>"20",
);




//检查华安的输入的函数,同时又返回了需要传递的
function input_check_xinhua($attribute_type, $POST,$insurer_code)
{
	global $_SGLOBAL;
	global $attr_sex_xinhua_unify;
	global $global_attr_obj_type;
	$global_info = array();

	//投保人信息检查
	if(	!isset($POST['applyNum'])||
		!isset($POST['startDate'])||
		!isset($POST['endDate'])||
		!isset($POST['applicant_fullname'])||
		!isset($POST['applicant_certificates_type'])||
		!isset($POST['applicant_certificates_code'])||
		!isset($POST['applicant_type'])
		)
	{
		showmessage("您输入的投保信息不完整！");
		exit(0);
	}

	$agent_uid = $_SGLOBAL['supe_uid'];	
	//被保险人信息检查
	$businessType = $POST['applicant_type'];
	
	//全局的投保信息
	$global_info['applyNum'] = intval($POST['applyNum']);//购买份数
	$global_info['totalModalPremium'] = $POST['totalModalPremium'];//总保费,应该从前端计算得到的

	$global_info['beneficiary'] = trim($POST['beneficiary']);//受益人，1是法定，0则是需要另外指定
	$global_info['startDate'] = trim($POST['startDate']);//保险开始日期
	$global_info['endDate'] = trim($POST['endDate']);//保险结束日期
	

	//////投保人信息/////////
	$user_info_applicant = array();
	if($businessType == 1)//投保人是个人
	{
		global $attr_certificates_type_xinhua_single_unify;
		$user_info_applicant['certificates_type'] = trim($POST['applicant_certificates_type']);//
		$user_info_applicant['certificates_code'] = trim($POST['applicant_certificates_code']);//
		$user_info_applicant['cardValidDate']=trim($POST['applicant_cardValidDate']);//证件生效日
		$user_info_applicant['cardExpDate']=trim($POST['applicant_cardExpDate']);//证件过期日
		
		$user_info_applicant['fullname'] = trim($POST['applicant_fullname']);//
		$user_info_applicant['gender'] = trim($POST['applicant_gender']);//
		$user_info_applicant['gender_unify'] = $attr_sex_xinhua_unify[$user_info_applicant['gender']];
		$user_info_applicant['birthday'] = trim($POST['applicant_birthday']);//
		$user_info_applicant['mobiletelephone'] = trim($POST['applicant_mobiletelephone']);//
		$user_info_applicant['email'] = trim($POST['applicant_email']);//
		$user_info_applicant['business_type'] = $POST['applicant_business_type'];//投保人所处行业
		$user_info_applicant['zipcode'] = trim($POST['applicant_zipcode']);//
		
		//投保人国籍代码，一般就是默认中国，代码37
		$user_info_applicant['nation_code'] = trim($POST['applicant_nation_code']);//
		$user_info_applicant['province_code'] = trim($POST['applicant_province_code']);//省代码
		$user_info_applicant['city_code'] = trim($POST['applicant_city_code']);//市代码
		$user_info_applicant['county_code'] = trim($POST['applicant_county_code']);//县代码
		//详细地址
		$user_info_applicant['address'] = isset($POST['applicant_address'])?trim($POST['applicant_address']):'';//
		//职业类型
		$user_info_applicant['occupationClassCode'] = trim($POST['applicant_occupationClassCode']);
		$user_info_applicant['certificates_type_unify'] = $attr_certificates_type_xinhua_single_unify[$user_info_applicant['certificates_type']];
		$user_info_applicant['agent_uid'] = $agent_uid;
		
	}
	else//机构投保,新华人寿暂时没有机构，有再支持上
	{
		//投保人信息获取
		$user_info_applicant['group_name'] = $POST['applicant_fullname'];
		$user_info_applicant['group_certificates_type'] = $POST['applicant_certificates_type'];
		$user_info_applicant['group_certificates_code'] = $POST['applicant_certificates_code'];
		
		//$user_info_applicant['group_abbr'] = $POST['group_abbr'];
		//$user_info_applicant['company_attribute'] = $POST['company_attribute'];
		$user_info_applicant['address'] = $POST['applicant_address'];//华安建工险不需要，学平险需要
		//$user_info_applicant['telephone'] = $POST['telephone'];
		$user_info_applicant['mobiletelephone'] = $POST['applicant_mobilephone'];
		$user_info_applicant['email'] = $POST['applicant_email'];
		$user_info_applicant['zipcode'] = $POST['zipcode'];//不要了
		
	}
		

	///下面是被保险人信息//////////////////////////////////////////////////////
	$assured_post = $POST['assured'][0];
	
	$assured_info['certificates_type'] = trim($assured_post['assured_certificates_type']);//
	$assured_info['certificates_code'] = trim($assured_post['assured_certificates_code']);//
	$assured_info['birthday'] = trim($assured_post['assured_birthday']);//
	$assured_info['fullname']  = trim($assured_post['assured_fullname']);//
	$assured_info['gender']  = trim($assured_post['assured_gender']);//性别
	$assured_info['gender_unify'] = $attr_sex_xinhua_unify[$assured_info['gender']];
	$assured_info['mobiletelephone'] =  trim($assured_post['assured_mobilephone']);//
	$assured_info['email'] =  trim($assured_post['assured_email']);//
	$assured_info['address'] =  trim($assured_post['assured_address']);//
	$assured_info['zipcode'] =  trim($assured_post['assured_zipcode']);//
	$assured_info['nation_code'] =  37;//trim($assured_post['assured_nation_code']);//
	$assured_info['province_code'] =  trim($assured_post['assured_province_code']);//
	$assured_info['city_code'] =  trim($assured_post['assured_city_code']);//
	$assured_info['county_code'] =  trim($assured_post['assured_county_code']);//
	$assured_info['business_type'] =  trim($assured_post['assured_business_type']);//所处行业
	$assured_info['occupationClassCode'] =  trim($assured_post['assured_occupationClassCode']);//职业代码
	$assured_info['cardValidDate'] =  trim($assured_post['assured_cardValidDate']);//
	$assured_info['cardExpDate'] =  trim($assured_post['assured_cardExpDate']);//
	
	$relationshipWithInsured = $POST['relationshipWithInsured'];//与投保人的关系
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
	
	//附加信息处理
	$global_info['partPayMode'] = trim($POST['partPayMode']);
	$global_info['partPayBankCode'] = trim($POST['partPayBankCode']);
	$global_info['partPayAcctNo'] = trim($POST['partPayAcctNo']);
	$global_info['partPayAcctName'] = trim($POST['partPayAcctName']);
	$global_info['serviceOrgCode'] = trim($POST['serviceOrgCode']);//保单服务机构
	$global_info['payPeriod'] = trim($POST['payPeriod']);//缴费期限
	$global_info['payPeriodUnit'] = trim($POST['payPeriodUnit']);//缴费期限单位
	$global_info['payMode'] = trim($POST['payMode']);//缴费方式，趸交，年交???
	$global_info['insPeriod'] = 25;//保险期限，只是针对安心宝贝
	$global_info['insPeriodUnit'] = 3;//保险期限单位，

	//主险的产品ID
	$product_id = intval($POST['product_id']);
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
	
	
	ss_log(__FUNCTION__.", add product id show down ");
	$subjectInfo1['list_product_id'] = $list_product_id1;
	
	
	//一个subjectInfo内多个产品，多个被保险人
	$subjectInfo1 = array();

	$subjectInfo1['list_product_id'] = $list_product_id1;//产品信息

	$subjectInfo1['list_assured'] = $list_assured1;//被保险人信息
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

//
//function get_huaan_project_cost_peroid($product_id)
//{
//	global $_SGLOBAL;
//
//	$sql="SELECT DISTINCT product_influencingfactor_id,factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
//			WHERE pi.product_influencingfactor_type='career' AND pi.product_id='$product_id' order by view_order";
//	$query = $_SGLOBAL['db']->query($sql);
//
//	$arr_project_list = array();
//	while ($row = $_SGLOBAL['db']->fetch_array($query))
//	{
//		$arr_period_list = array();
//		//得到每个工程的周期列表
//		$factor_code = $row['factor_code'];
//		$sql1="SELECT DISTINCT product_influencingfactor_id,factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
//			WHERE pi.product_influencingfactor_type='period'" .
//					"AND pi.factor_code='$factor_code' AND pi.product_id='$product_id' order by view_order";
//		$query1 = $_SGLOBAL['db']->query($sql1);
//		while($row_period = $_SGLOBAL['db']->fetch_array($query1))
//		{
//			$arr_period_list[]=$row_period;
//		}
//		
//		$arr_project_list[] = array('cost'=>$row,
//									'period'=>$arr_period_list,
//									);
//		
//	}
//	return $arr_project_list;
//}

//获取责任价格
function xinhua_get_duty_price_by_id($duty_price_list)
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


//新华人寿的承保请求发送
function post_accept_policy_xinhua($policy_id, $return_data_json, $obj_java= NULL)
{

	global $_SGLOBAL,$java_class_name_xinhua;
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
	//$strjson_post_policy_content = gen_xinhua_policy_accept_json($return_data_json);
	//comment by zhangxi, 20150410, 外面已经生成了json报文
	$strjson_post_policy_content = $return_data_json;

	if($strjson_post_policy_content)
	{
		$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_xinhua_policy_post_accept.json";
		file_put_contents($af_path,$strjson_post_policy_content);
		
		//start add by wangcya, 20150209,把携带序列号的也保存起来
		$FILE_UUID = getRandOnly_Id(0,1);
		$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_xinhua_policy_post_accept.json";
		file_put_contents($af_path_serial, $strjson_post_policy_content);
		//end add by wangcya, 20150209,把携带序列号的也保存起来
	}
	else
	{
		$retcode = 110;
		$retmsg = "xinhua accept strjson_post_policy_content is null!";
		ss_log($retmsg);
			
		$result_attr = array('retcode'=>$retcode,
				'retmsg'=> $retmsg
		);
			
		return $result_attr;
	}
	
	//进行承保的处理
	$url = URL_XINHUA_POST_POLICY;
	ss_log(__FUNCTION__.", toubao xinhua url= ".$url);
	
	////////////////////////////////////////////////////////
	//isDev			是否输出日志文件
	$logFilePath =  S_ROOT."xml/log/".$order_sn."_xinhua_policy_accept.log";
	
	////////////////////////////////////////////////////////////////////
	if(!defined('USE_ASYN_JAVA'))
	{
		$java_class_name = $java_class_name_xinhua;
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_xinhua:$java_class_name;
	
	if(!isset($obj_java))
	{
		$obj_java = create_java_obj($java_class_name);	
	}
	////////////////////////////////////////////////////////////////////
	$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_xinhua_policy_post_accept_ret.json";
	$secretKey=XINHUA_SECRETKEY;
	////////////////////////////////////////////////////////////////////
	if(!defined('USE_ASYN_JAVA'))//同步处理
	{
		//进行承保的工作
		
		ss_log(__FUNCTION__.", xinhua will java insure");
		$return_data = $obj_java->insure( 	$url ,
											$strjson_post_policy_content,
											$secretKey,
											$logFilePath
										);
		ss_log(__FUNCTION__.", before xinhua accept , underWriting, before".$return_data);
		
		$return_data = (string)$return_data;
		
		ss_log(__FUNCTION__.", before xinhua accept , underWriting, after".$return_data);
		
		/////////////////////////////////////////////////////////////
		if(!empty($return_data))
		{
			
			file_put_contents($ret_af_path,$return_data);
			$result_attr = process_return_json_data_xinhua(
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
		$logFileName = S_ROOT."xml/log/xinhua_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
			
		ss_log("logFileName: ".$logFileName);
			
		$insurer_code = $policy_arr['insurer_code'];
		ss_log("insurer_code: ".$insurer_code);
		$callBackURL = CALLBACK_URL;
		ss_log("callBackURL: ".$callBackURL);
		$type = "insure_accept";//这个承保要区分开
		ss_log("type: ".$type);
		
		$obj_message = json_decode($strjson_post_policy_content, true);
		$param_other = array(   
				"url"=>$url ,//投保保险公司服务地址
				"message"=>$obj_message ,//投保报文
				"messageFileName"=>"" ,//投保报文文件名，保留字段
				"messageFileEncoding"=>"",//文件编码，保留字段
				"secretKey"=>$secretKey ,//秘钥
				"respFileName"=>$respJsonFileName,//返回报文保存全路径
				"respXmlFileEncoding"=>"UTF-8",
				"logFileName"=>$logFileName //日志文件
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


//进行新华人寿的各险种的核保动作
function post_policy_xinhua(
							$attribute_type,//add by wangcya, 20141213
							$attribute_code,//add by wangcya, 20141213
							$policy_arr,
							$user_info_applicant,
							$list_subject,
							$flag_policy_check_only =0//added by zhangxi, 20150422, 是否只进行核保标示
						   )
{
	global $_SGLOBAL, $java_class_name_xinhua;
	/////////////////////////////////////////////////////////
	ss_log("into ".__FUNCTION__);
	
	$policy_id = $policy_arr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	
	$orderNum = $policy_arr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_arr['order_sn'];
	
	//生成需要进行核保保的json文件
	$policy_obj = gen_xinhua_policy_json(
									$attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject);//subject信息，被保险人信息也在这里面
	$json_body_data = json_encode($policy_obj);
	ss_log(__FUNCTION__.", after gen_xinhua_policy_json,json=".$json_body_data);

	//$strxml = trim($strxml);
	if(empty($policy_obj))
	{		
		ss_log("");
		
		$result = 112;
		$retmsg = "gen strjson null!";
		ss_log($retmsg);
		
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		
		return $result_attr;
		
	}
	

	/////////////////////////////////////////////////////////////////////////////////////
	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_xinhua_policy_post.json";
	file_put_contents($af_path,$json_body_data);//核保文件先存起来
	
	//start add by wangcya, 20150209,把携带序列号的也保存起来
	$UUID = $policy_arr['order_num'];
	$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_xinhua_policy_post.json";
	file_put_contents($af_path_serial,$json_body_data);
	//end add by wangcya, 20150209,把携带序列号的也保存起来
	
	//
	if(!defined('USE_ASYN_JAVA') || $flag_policy_check_only == 1)
	{
		$java_class_name = $java_class_name_xinhua;//新华人寿同步处理请求
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_xinhua:$java_class_name;
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
	$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_xinhua_policy_post_ret.json";
	ss_log($ret_af_path);
	
	$url = URL_XINHUA_CHECK_POLICY;//承保URL
	ss_log(__FUNCTION__.", url: ".URL_XINHUA_CHECK_POLICY);
	$secretKey=XINHUA_SECRETKEY;
	//只进行核保时，也走同步流程
	if(!defined('USE_ASYN_JAVA') || $flag_policy_check_only == 1)//同步核保处理流程
	{
		global $LOGFILE_XINHUA;

		//进行核保调用处理
		ss_log(__FUNCTION__.", xinhua will java applyPolicy");
		
		$strjson_check_policy_ret = $return_data = (string)$obj_java->underWriting(
																		$url,
																		$json_body_data ,//投保报文
																		$secretKey,
																		$LOGFILE_XINHUA
																		);

		ss_log(__FUNCTION__."after post call function sendMessage");

		//////////////////////////////////////////////////////////
		if(!empty($strjson_check_policy_ret))//核保完了就要承保了
		{
			ss_log(__FUNCTION__."xinhua will process return_data");
		
			file_put_contents($ret_af_path, $strjson_check_policy_ret);
				
			//////////////////////////////////////////////////////////////////////////
			$result_attr = process_return_json_data_xinhua(
														 	$policy_id,
									                      	$orderNum,
															$order_sn,
															$return_data,
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
		ss_log(__FUNCTION__."将要发送xinhua核保的异步请求，will doService");
	
		$respXmlFileName = $ret_af_path;//承保返回报文存储路径
		$logFileName = S_ROOT."xml/log/xinhua_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	
		ss_log(__FUNCTION__."logFileName: ".$logFileName);
	
		$type = "insure";
		$insurer_code = $policy_arr['insurer_code'];
		//投保之后的回调url
		$callBackURL = CALLBACK_URL;
					
		ss_log(__FUNCTION__.", policy_id: ".$policy_id);
		ss_log(__FUNCTION__."type: ".$type);
		ss_log(__FUNCTION__."insurer_code: ".$insurer_code);
		ss_log(__FUNCTION__."callBackURL: ".$callBackURL);
			
		$param_other = array(
							"url"=>$url ,//投保保险公司服务地址
							"message"=>$policy_obj ,//核保请求报文
							"messageFileName"=>"" ,//投保报文内容
							"messageFileEncoding"=>"",//投保报文文件
							"secretKey"=>$secretKey ,//投保报文文件编码格式
							"respFileName"=>$respXmlFileName,//返回报文保存全路径
							"respXmlFileEncoding"=>"UTF-8",
							"logFileName"=>$logFileName//日志文件
						);
	
		ss_log(__FUNCTION__.", param_other: ".var_export($param_other,true));
	
		//中文不进行unicode编码
		//$jsonStr = json_encode($param_other);
		$jsonStr = TO_JSON($param_other);
		ss_log(__FUNCTION__.", jsonStr: ".$jsonStr);
		
		$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_xinhua_policy_post_to_java.json";
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
	//end add by wangcya, 20150112,for bug[203],启用异步方式和java通讯

	/////////////////////////////////////////////////////////////
	return array('retcode'=>$result,
				'retmsg'=> $retmsg
				);
}

function process_return_json_data_xinhua(
									 	$policy_id,
				                      	$orderNum,
										$order_sn,
										$return_data,
										$obj_java = NULL,
										$flag_policy_check_only = 0
										)
{
	global $_SGLOBAL;
	
	ss_log("into function: ".__FUNCTION__);
	////////////////////////////////////////////////////////////////////////////
	//以下要针对新华的做处理
	/////////先解析出来返回的json//////////////////////////////////
	ss_log(__FUNCTION__.", policy_id=".$policy_id." return org data: ".$return_data);
	$return_data = json_decode((string)$return_data, true);
	$error = json_last_error();
	ss_log(__FUNCTION__.", json errorno: ".$error);
	ss_log(__FUNCTION__.", return tranfered data: ".var_export($return_data,true));

	if($return_data['transCode']=="100001"&&////核保
		$return_data['partnerOrderId']== $order_sn//并且这个要和订单序列号相等
	  )
	{
		if($return_data['isSuccess'] == 1 )//核保成功
		{
			ss_log(__FUNCTION__.", xinhua核保成功");
			ss_log(__FUNCTION__.", return code: ".$return_data['rspCode']." order_sn: ".$order_sn);
			ss_log(__FUNCTION__.", order_sn: ".$order_sn);
			
			if($flag_policy_check_only == 1)//只是进行核保的情况
			{
				//comment by zhangxi, 20150429, 这里要存储核保单号等信息了
				$wheresql = "policy_id='$policy_id'";
				$sql = "SELECT * FROM ".tname('insurance_policy_nci_underwriting_return')." WHERE $wheresql LIMIT 1";
				$query = $_SGLOBAL['db']->query($sql);
				$insurance_policy_additional = $_SGLOBAL['db']->fetch_array($query);
				ss_log(__FUNCTION__.", sql=".$sql);
				ss_log(__FUNCTION__.", insurance_policy_additional=".var_export($insurance_policy_additional,true));
				//comment by zhangxi, 20150430, 保存到数据库中//////////////////
				ss_log(__FUNCTION__.", will update policy status");
				
				$setsqlarr = array( 'appId'=>$return_data['appId'],
									'partnerOrderId'=>$return_data['partnerOrderId'],
									'payAmount'=>$return_data['payAmount'],
									'proposalNo'=>$return_data['proposalNo'],
									'totalPremium'=>$return_data['totalPremium'],
									);
		
				if(!empty($insurance_policy_additional))
				{
					ss_log(__FUNCTION__.", update info to insurance_policy_nci_underwriting_return");
					updatetable('insurance_policy_nci_underwriting_return', $setsqlarr, array('policy_id'=>$policy_id));
		
				}
				else
				{
					ss_log(__FUNCTION__.", add info to insurance_policy_nci_underwriting_return");
					$setsqlarr['policy_id'] = $policy_id;
					inserttable('insurance_policy_nci_underwriting_return', $setsqlarr);
				}
				
				
				$result_attr = array('retcode'=>0,
									'retmsg'=> $return_data['message'],
									'policy_no'=>$return_data['policyNo']//add by wangcya , 20150107, 一定要把这个电子保单号返回
							);
				return $result_attr;
			}
			else
			{
				//这里需要将投保单号更新到数据库中吗？？？只是核保产生的投保单就没有必要
				//是否应该接着进行投保动作呢?要区分是同步还是异步
				//同步的投保处理,根据核保产生的响应报文，再生成投保报文
				$return_data_json = gen_xinhua_policy_accept_json($return_data);
				//进行投保操作
				return post_accept_policy_xinhua($policy_id, $return_data_json, $obj_java );
			}
			
			

				
		}
		else
		{
			//核保失败处理,得更新数据库啊
			ss_log(__FUNCTION__.",xinhua, policy_id=".$policy_id.", rspCode=".$return_data['rspCode'].", message=".$return_data['message']);
			$result_attr = array('retcode'=>$return_data['rspCode'],
									'retmsg'=> $return_data['message'],
									'policy_no'=>$return_data['policyNo']//add by wangcya , 20150107, 一定要把这个电子保单号返回
							);
				return $result_attr;
		}
		
		
	}//
	elseif($return_data['transCode']=="100003"&&////承保的返回处理
		$return_data['partnerOrderId']== $order_sn//并且这个要和订单号相等
	  )
	{
		if($return_data['isSuccess'] == 1 )//承保成功
		{
			ss_log(__FUNCTION__.", xinhua承保成功");
			ss_log(__FUNCTION__.", return code: ".$return_data['rspCode']." ordernum: ".$orderNum.", order_sn: ".$order_sn);
			$result = 0;
				
			//status,保单的状态：0 保存但是未提交；1 投保成功；2 注销了，注销后不能再次提交。
			$wheresql = "order_num='$orderNum'";//根据这个找到其对应的保单存放的数据
			$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$policy_attr = $_SGLOBAL['db']->fetch_array($query);
		
			if($policy_attr['policy_id'])//old
			{
				$policy_id = $policy_attr['policy_id'];
				ss_log(__FUNCTION__."return policyNo: ".$return_data['policyNo']." order_sn: ".$order_sn);
		
				//在这里更改该保单状态,存储保险公司返回的保单号，同时保存电子保单url到数据库中
				updatetable('insurance_policy',
								array('policy_status'=>'insured','policy_no'=>$return_data['policyNo'], 'policy_file'=>$return_data['policyUrl']),
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
				ss_log("order_sn: ".$order_sn);
				ss_log($retmsg);
				//comment by zhangxi, 20150206, 这里是否应该返回？毕竟是异常，就不要进行取保单操作了
				//should add code here
			}
		
			ss_log(__FUNCTION__." policy_id: ".$policy_id);
			//$policy_attr['policy_status'] = 'insured';//更改投保成功标示
			$policy_attr['policy_no'] = $return_data['policyNo'];
			$policy_attr['policy_file'] = $return_data['policyUrl'];
			
			$pdf_filename = S_ROOT."xml/message/".$return_data['policyNo']."_xinhua_policy.pdf";
			
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
			
			$result_attr = get_policy_file_xinhua($use_asyn,$policy_attr,$pdf_filename);
			
		}
		else//承保失败
		{
			
		}
	}
	//退保核算处理
	elseif($return_data['transCode']=="100006"&&////退保核算的返回处理
		$return_data['partnerOrderId']== $order_sn//并且这个要和订单号相等
	  )
	{
		
	}
	//退保处理
	elseif($return_data['transCode']=="100007"&&////退保的返回处理
		$return_data['partnerOrderId']== $order_sn//并且这个要和订单号相等
	  )
	{
		
	}
	else
	{
		//新华投保返回异常
		$result = $return_data['rspCode'];
		$retmsg = $return_data['message'];
		ss_log(__FUNCTION__."，post gen policy fail! rspCode=".$result.", return message: ".$retmsg);
		ss_log(__FUNCTION__."，before tranfer,将要保存保单的返回信息！".$retmsg );
	}
	//echo $strxml;	
	
	$result_attr = array('retcode'=>$result,
			'retmsg'=> $retmsg,
			'policy_no'=>$return_data['policyNo']//add by wangcya , 20150107, 一定要把这个电子保单号返回
	);
	
	return $result_attr;
	
}

//获取电子保单函数
function get_policy_file_xinhua($use_asyn,
		                       $policy_attr,
		                       $pdf_filename,
		                       $obj_java = NULL
		                      )
{
	global $java_class_name_xinhua;
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
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
	
		return $result_attr;
	}
	
	//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	if(!$use_asyn)//同步处理接口
	{
		$java_class_name = $java_class_name_xinhua;
	}
	else//异步接口
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_xinhua:$java_class_name;
	
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
	$logFileName = S_ROOT."xml/log/xinhua_download_policyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	
	//需要根据承保接口获取到的url来获取保单下载的url
	$url = $policy_attr['policy_file'];
	//jdk1.7.0_25
	ss_log(__FUNCTION__.",policy get url: ".$url);

	if(!$use_asyn)//同步的分支
	{
		
		ss_log(__FUNCTION__.",xinhua will get policy file, POLICYNO: ".$POLICYNO." orderNum: ".$orderNum);
		ss_log(__FUNCTION__.",xinhua order_sn: ".$order_sn);
		
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
	
		ss_log(__FUNCTION__.", insurer_code: ".$insurer_code);
		ss_log(__FUNCTION__.", callBackURL: ".$callBackURL);
		ss_log(__FUNCTION__.", type: ".$type);
		ss_log(__FUNCTION__.", policy_id: ".$policy_id);
		//////////////////////////////////////////////////////////////////////////
	
		ss_log(__FUNCTION__.",logFileName: ".$logFileName);
		ss_log(__FUNCTION__.", url: ".$url);
	
		$param_other = array(
				"url"=>$url ,//投保保险公司服务地址
				"pdfFileName"=>$pdf_filename ,//投保报文文件编码格式
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
function get_policyfile_xinhua($pdf_filename,
		                      $policy_attr,
		                      $user_info_applicant
		                    )
{

	$use_asyn = false;//同步方式
	$result_attr = get_policy_file_xinhua($use_asyn,$policy_attr,$pdf_filename);
	return $result_attr;
}


////////////////////////////////////////////////////////////////////////////////



//added by zhangxi, 20141230, 新华保单注销入口，好像暂时不提供住下接口
function withdraw_policy_xinhua($policy_id,$user_info_applicant)
{
	
	//新华接的安心少儿暂时不允许在线注销
	return $result_attr = array(	'retcode'=>"110",
				'retmsg'=> "该产品不支持在线注销操作，需线下进行注销！");
	
	
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
		$result_attr = post_withdraw_policy_xinhua(	$policy_attr, $user_info_applicant );
	
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

//注销保单
function post_withdraw_policy_xinhua( $policy_attr, $user_info_applicant )
{
	global $_SGLOBAL, $java_class_name_xinhua;
	global $LOGFILE_XINHUA;
	
	//////////////////////////////////////////////////////
	$policy_id = $policy_attr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	
	$POLICYNO 		= $policy_attr['policy_no'];
	$orderNum 		= $policy_attr['order_num'];
	$order_sn 		= $policy_attr['order_sn'];
	
	ss_log("will withdraw policy , POLICYNO: ".$POLICYNO." orderNum: ".$orderNum);
	ss_log("order_sn: ".$order_sn);
	

	$DATE = date('Y-m-d',$_SGLOBAL['timestamp']);//20140515
	$TIME = date('H:i:s',$_SGLOBAL['timestamp']);//18:33:30

	$result = 1;
	//保单注销报文
	$strxml = '';

	if(!empty($strxml))
	{
		$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_xinhua_policy_withdraw.json";
		file_put_contents($af_path,$strxml);
		
		//start add by wangcya, 20150209,把携带序列号的也保存起来
		$UUID = $orderNum;
		$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_xinhua_policy_withdraw.json";
		file_put_contents($af_path_serial,$strxml);
		//end add by wangcya, 20150209,把携带序列号的也保存起来
		
		$p = create_java_obj($java_class_name_xinhua);
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
		
		$url = URL_XINHUA_POST_POLICY;
		$respXmlFileName = "";
		ss_log(__FUNCTION__."xinhua, withdraw url: ".URL_XINHUA_POST_POLICY);
		//ss_log("keyFile: ".$keyFile);
		
		$return_data = (string)$p->applyPolicy( 
											$url , 
											$strxml ,
											$LOGFILE_XINHUA
											);

		ss_log("after xinhua withdraw call function sendMessage");
		
		if(!empty($return_data))
		{
			
			//先保存一份
			$return_path = S_ROOT."xml/".$order_sn."_".$policy_id."_xinhua_policy_withdraw_ret.xml";
			file_put_contents($return_path,$return_data);

			//////////////////////////////////////////////////////////
		}
		else
		{
			$result = 110;
			$retmsg = "post xinhua withdraw policy return is null!";
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
			return $result_attr;
		}

		/////////先解析出来返回的xml//
		preg_match_all('/<EXTSEQUENCENO>(.*)<\/EXTSEQUENCENO>/isU',$return_data,$arr);
		$extseqno = trim($arr[1][0]);

		//返回状态码
		preg_match_all('/<RESPONSECODE>(.*)<\/RESPONSECODE>/isU',$return_data,$arr);
		$HUAAN_RSLT_CODE = trim($arr[1][0]);
		//返回状态消息
		preg_match_all('/<ERRORMESSAGE>(.*)<\/ERRORMESSAGE>/isU',$return_data,$arr);
		$HUAAN_RSLT_MESG = trim($arr[1][0]);
		//保险公司返回的保单号，需要存储起来
		preg_match_all('/<C_PLY_NO1>(.*)<\/C_PLY_NO1>/isU',$return_data,$arr);
		$POLICYNO_OUT = trim($arr[1][0]);
		
		ss_log("HUAAN_RSLT_CODE_OUT: ".$HUAAN_RSLT_CODE);
		ss_log("HUAAN_RSLT_MESG_OUT: ".$HUAAN_RSLT_MESG);

		ss_log("orderNum: ".$orderNum."EXTSEQUENCENO:".$extseqno);
		ss_log("POLICYNO: ".$POLICYNO."POLICYNO_OUT: ".$POLICYNO_OUT);
		ss_log("order_sn: ".$order_sn);
		//ss_log("withdraw policy return code: ".$PA_RSLT_CODE_OUT." message: ".$PA_RSLT_MESG_OUT);

		if( $HUAAN_RSLT_CODE =="1001"&&////注销保单成功
				$extseqno == $orderNum &&//并且这个要和订单号相等$orderNum
				$POLICYNO_OUT == $POLICYNO
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
			//start add by wangcya,20150202,有节点，但是没内容
			if(empty($HUAAN_RSLT_CODE))
			{	
				$result = 110;
				$retmsg = "新华返回有节点，但是节点内部无内容";
				
				ss_log("result：".$result );
				ss_log("解析返回报文错误！");
				
				$result_attr = array(	'retcode'=>$result,
										'retmsg'=> $retmsg
										);
			}
			//end add by wangcya,20150202,有节点，但是没内容
			else
			{

				$retmsg = "withdraw fail!,HUAAN_RSLT_MESG: ".$HUAAN_RSLT_MESG;
				ss_log($retmsg);
				$result_attr = array(	'retcode'=>$HUAAN_RSLT_CODE,
						'retmsg'=> $HUAAN_RSLT_MESG
						);
					
			}
			
	
		}
	}
	else
	{
		$result = 110;
		$retmsg = "错误！";
		ss_log($retmsg);
		//////////////////////////////////////////////////////////////////////
			
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
	}

	return $result_attr;

}
////生成guid函数,function_common.php中已经有定义
//function guid(){
//    if (function_exists('com_create_guid')){
//        return com_create_guid();
//    }else{
//        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
//        $charid = strtoupper(md5(uniqid(rand(), true)));
//        $hyphen = chr(45);// "-"
//        $uuid = chr(123)// "{"
//                .substr($charid, 0, 8).$hyphen
//                .substr($charid, 8, 4).$hyphen
//                .substr($charid,12, 4).$hyphen
//                .substr($charid,16, 4).$hyphen
//                .substr($charid,20,12)
//                .chr(125);// "}"
//        return $uuid;
//    }
//}
//新华投保单报文生成函数,传入参数就是数组，不是json数据
function gen_xinhua_policy_accept_json($return_data)
{
	global $_SGLOBAL;
	//$arr = json_decode($return_data_json,true);
	$transSn = getRandOnly_Id(0);
	$policy_accept = array(
						"transCode"=>"100003",
					    "appId"=>$return_data['appId'],
					    "transSn"=>$transSn,
					    "transTime"=>date('Y-m-d H:i:s',$_SGLOBAL['timestamp']),
					    "rspMode"=>"S",
					    "partnerOrderId"=>$return_data['partnerOrderId'],//订单号
					    "payTime"=>date('Y-m-d H:i:s',$_SGLOBAL['timestamp']),
					    "payAmount"=>$return_data['totalPremium'],
					    "accountDate"=>date('Y-m-d',$_SGLOBAL['timestamp']),
					    "proposalNo"=>$return_data['proposalNo'],//投保单号
					    "totalPremium"=>$return_data['totalPremium'],//总保费
					    "postFee"=>0//邮递费
						);
	ss_log(__FUNCTION__.", policy_accept=".var_export($policy_accept,true));
	$json_policy_accept=json_encode($policy_accept);
	
	return $json_policy_accept;
}

/*
新华人寿核保单生成
 * */
function gen_xinhua_policy_json(
								$attribute_type,
								$attribute_code,
								$policy_arr,
								$user_info_applicant,
								$list_subject)
{
	//
	ss_log(__FUNCTION__.", start gen xinhua post policy json file");
	$str_common  = gen_xinhua_common_info($policy_arr);
	
	//生成订单相关数据
	//生成投保险种信息
	$str_order_product = gen_xinhua_orderDTO_productDTO($policy_arr,
								$user_info_applicant,
								$list_subject);
	//生成投保人信息
	$str_holder = gen_xinhua_holderDTO($policy_arr,
										$user_info_applicant,
										$list_subject);
	//生成被保险人信息
	$str_insuredList = gen_xinhua_insuredListDTO(  	
								$attribute_type,
								$attribute_code,		
								$policy_arr,
								$user_info_applicant,
								$list_subject);
	$obj_all = array_merge($str_common, $str_order_product, $str_holder, $str_insuredList);
	//$str_json=json_encode($str_all);
	ss_log(__FUNCTION__.", end gen xinhua post policy json file");
	return $obj_all;
}
//added by zhangxi, 20150323， 新华核保报文生成处理 
function gen_xinhua_common_info($policy_arr)
{
	global $_SGLOBAL;
	$transSn = getRandOnly_Id(0);
	ss_log(__FUNCTION__.", get in");
	$str_header=array("transCode"=>"100001",
						"appId"=>"20000030",//由新华电商提供
						"transSn"=>$transSn,//交易流水号，新华定义最长36个字节
						"transTime"=>date('Y-m-d H:i:s',$_SGLOBAL['timestamp']),//交易时间，格式2014-09-11 16:36:10
						"rspMode"=>"S",//标示交易模式是同步还是异步，默认同步，新华暂时不支持异步
						);
	return $str_header;
	
}

//订单信息和产品信息获取，因为订单信息中含有总保费，需要从产品中获取
function gen_xinhua_orderDTO_productDTO($policy_arr,
								$user_info_applicant,
								$list_subject)
{
	global $_SGLOBAL;
	$apply_num = $policy_arr['apply_num'];
	ss_log(__FUNCTION__.", get in");
	
	$main_data = array();
	$ext_data = array();
	$order_info = array();
	$ext_data = array();
	//多个层级，但是新华人寿没有多个，只有一个，所以只循环一次
	foreach($list_subject AS $key =>$value)
	{
		//产品信息列表，包括主险和附加险了
		$list_subject_product  = $value['list_subject_product'] ;
		//被保险人信息列表
		$list_subject_insurant = $value['list_subject_insurant'] ;
		
		$policy_subject_id = $value['policy_subject_id'] ;
		
		//保险责任价格列表信息,这个也得用
		$list_subject_product_duty_price = $value['list_subject_product_duty_price'] ;
	
		$str_subject_info = '';
	
		//planInfo
		$subject_total_premium = 0;
		$str_plan_info ='';
		//for 循环添加多个产品信息
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
					$plan_total_premium += $product_duty_price['premium']*$policy_arr['apply_num'];
				}//end for dutyInfo
				
				$product_code = $product_value['product_code'];
				$applynum = $policy_arr['apply_num'];
				$plan_totalModalPremium = $plan_total_premium;//一个险种的保费
				
				ss_log(__FUNCTION__.", PRODUCT TYPE=".$product_value['product_type'].", payperiod=".$policy_arr['payPeriod']);					
				//mod by zhangxi,20150325, 是主险的处理
				if($product_value['product_type'] == 'main')//主险的处理
				{
					//再组织主险
					if($policy_arr['payPeriod'] == 0)//如果是趸交的情况
					{
						$main_data = array(
										"productCode"=>$product_code,//险种代码
								        "amount"=>$dutyAount,//保额
								        "premium"=>$duty_totalModalPremium,//保费
								        "discountRate"=>1,//折扣率
								        "discountPremium"=>$duty_totalModalPremium,//折扣后的保费
								        "applyNum"=>$apply_num,//投保份数
								        "payPeriodUnit"=>$policy_arr['payPeriodUnit'],//缴费单位
								        "payMode"=>$policy_arr['payMode'],//缴费方式,趸交，还是年交
										);
					}
					else
					{
						$main_data = array(
										"productCode"=>$product_code,//险种代码
								        "amount"=>$dutyAount,//保额
								        "premium"=>$duty_totalModalPremium,//保费
								        "discountRate"=>1,//折扣率
								        "discountPremium"=>$duty_totalModalPremium,//折扣后的保费
								        "applyNum"=>$apply_num,//投保份数
								        "payPeriod"=>$policy_arr['payPeriod'],//缴费期限
								        "payPeriodUnit"=>$policy_arr['payPeriodUnit'],//缴费单位
								        "payMode"=>$policy_arr['payMode'],//缴费方式,趸交，还是年交
										);
					}
					ss_log(__FUNCTION__.", GET MAIN INFO,main_data=".var_export($main_data, true));
					
				}
				else//附加险的处理
				{
					$temp = array();
					if($policy_arr['payPeriod'] == 0)//趸交的情况
					{
						$temp[] = array(
				    										"productCode"=>$product_code,//险种代码
				    										"amount"=>$dutyAount,//附加险的保额好像是固定的
				    										"premium"=>$duty_totalModalPremium,//保费
				    										"discountRate"=>1,//折扣率
				    										"discountPremium"=>$duty_totalModalPremium,//折扣后的保费
				    										"applyNum"=>$apply_num,//购买的份数
				    										"payPeriodUnit"=>$policy_arr['payPeriodUnit']//缴费期限单位
				    										);
					}
					else
					{
						$temp[] = array(
				    										"productCode"=>$product_code,//险种代码
				    										"amount"=>$dutyAount,//附加险的保额好像是固定的
				    										"premium"=>$duty_totalModalPremium,//保费
				    										"discountRate"=>1,//折扣率
				    										"discountPremium"=>$duty_totalModalPremium,//折扣后的保费
				    										"applyNum"=>$apply_num,//购买的份数
				    										"payPeriod"=>$policy_arr['payPeriod'],//缴费期限,从额外信息中获取
				    										"payPeriodUnit"=>$policy_arr['payPeriodUnit']//缴费期限单位
				    										);
					}
					
					 //附加险相关信息
				    $ext_data = array(
				    				"extraneousProductDTOList"=>$temp,
				    				);
				    ss_log(__FUNCTION__.", GET ADDITIONAL INFO,ext_data=".var_export($ext_data, true));
				    				
				}
			
			//每一个险种的保费进行累加
			$subject_total_premium += $plan_totalModalPremium;
				
		}//end for 产品循环
		
		
		//订单信息获取
		$partnerOrderId = getRandOnly_Id(0);
		if($policy_arr['payPeriod'] == 0)//趸交情况
		{
			$order_info= array("orderDTO"=>array( "partnerOrderId"=>$policy_arr['order_sn'],//"ZTXH".$partnerOrderId,//传递的订单号，最长30个字节
									        "totalPremium"=>$subject_total_premium,//总的保费
									        "insBeginDate"=>$policy_arr['start_date'],//保险启期
									        "insEndDate"=>$policy_arr['end_date'],//保险止期
									        "insPeriod"=>$policy_arr['insPeriod'],//保险期限,到25周岁
									        "insPeriodUnit"=>$policy_arr['insPeriodUnit'],//保险期限单位代码，3：保至某确定年龄
									        "totalApplyNum"=>$policy_arr['apply_num'],//购买份数
									        "serviceOrgCode"=>$policy_arr['serviceOrgCode']."00",//新华人寿保单服务机构代码
										),
						);
		}
		else
		{
			$order_info= array("orderDTO"=>array( "partnerOrderId"=>$policy_arr['order_sn'],//传递的订单号，最长100个字节
									        "totalPremium"=>$subject_total_premium,//总的保费
									        "insBeginDate"=>$policy_arr['start_date'],//保险启期
									        "insEndDate"=>$policy_arr['end_date'],//保险止期
									        "insPeriod"=>$policy_arr['insPeriod'],//保险期限,到25周岁
									        "insPeriodUnit"=>$policy_arr['insPeriodUnit'],//保险期限单位代码，3：保至某确定年龄
									        "totalApplyNum"=>$policy_arr['apply_num'],//购买份数
									        "serviceOrgCode"=>$policy_arr['serviceOrgCode']."00",//新华人寿保单服务机构代码
									        "partPayMode"=>$policy_arr['partPayMode'],//续期付款方式，一般是3，银行转账
									        "partPayBankCode"=>$policy_arr['partPayBankCode'],//银行代码
									        "partPayAcctNo"=>$policy_arr['partPayAcctNo'],//用户填写的账号
									        "partPayAcctName"=>$policy_arr['partPayAcctName']//账户名称
										),
						);
		}
		
		
	}//end foreach $subjectlist
	
	
	ss_log(__FUNCTION__.", EXT_DATA=".var_export($ext_data, true));
	ss_log(__FUNCTION__.", MAIN_DATA=".var_export($main_data, true)); 
	
	$product_info = array_merge($main_data, $ext_data);
	
	$product_info1 = array("productDTO"=>$product_info);
	
	$order_product_info = array_merge($order_info, $product_info1);
	
	
	return $order_product_info;
}



//投保人
function gen_xinhua_holderDTO($policy_arr,
								$user_info_applicant,
								$list_subject)
{
	ss_log(__FUNCTION__.", get in");
	if($policy_arr['business_type'] == 1)//个人
	{
		
		if(defined('IS_NO_GBK'))
		{
			$user_info_applicant_fullname =  $user_info_applicant['fullname']; //已知原编码为UTF-8, 转换为GBK
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
			$user_info_applicant_fullname = $user_info_applicant['group_name']; //已知原编码为UTF-8, 转换为GBK		
			$groupAbbr =  $user_info_applicant['group_abbr']; //已知原编码为UTF-8, 转换为GBK		
			$address   =  $user_info_applicant['address']; //已知原编码为UTF-8, 转换为GBK	
			$linkman_fullname = $user_info_applicant['applicant_fullname']; 
			
		}
		else
		{
			$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['group_name'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			$groupAbbr =  mb_convert_encoding($user_info_applicant['group_abbr'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			$address =  mb_convert_encoding($user_info_applicant['address'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}
	}
	else
	{
		//error， log here
	}
	 $holder_info = array("holderDTO"=>array("name"=>$user_info_applicant_fullname,//投保人名称
						        "sex"=>$user_info_applicant['gender'],//投保人性别
						        "cardType"=>$user_info_applicant['certificates_type'],//证件类型
						        "cardNo"=>$user_info_applicant['certificates_code'],//证件号
						        "cardValidDate"=>$user_info_applicant['cardValidDate'],//证件生效日
						        "cardExpDate"=>$user_info_applicant['cardExpDate'],//证件过期日
						        "birthday"=>$user_info_applicant['birthday'],//出生日期
						        "mobile"=>$user_info_applicant['mobiletelephone'],//手机号
						        "email"=>$user_info_applicant['email'],
						        "nativePlace"=>$user_info_applicant['nation_code'],//国籍代码，CD10，安心宝贝只能是中国
						        "residentProvince"=>$user_info_applicant['province_code'],//省代码
						        "residentCity"=>$user_info_applicant['city_code'],//市代码
						        "residentCounty"=>$user_info_applicant['county_code'],//县代码
						        "address"=>$user_info_applicant['address'],//详细地址
						        "zip"=>$user_info_applicant['zipcode'],//邮政编码
						        "industry"=>$user_info_applicant['business_type'],//行业代码
						        "jobs"=>$user_info_applicant['occupationClassCode']//职业代码
						        )
						        );
					        
	return $holder_info;
}

//被保险人信息获取，包括受益人信息
function gen_xinhua_insuredListDTO(	
						$attribute_type,
						$attribute_code,
						$policy_arr,
						$user_info_applicant,
						$list_subject)
{
	global $_SGLOBAL;
	ss_log(__FUNCTION__.", get in");
	
	$product = $list_subject[0]['list_subject_product'][0];//产品信息
	$policy_subject_id = $list_subject[0]['policy_subject_id'];
	//被保险人列表
	$list_subject_insurant = $list_subject[0]['list_subject_insurant'];
	
	
	//循环获取所有被保险人信息，同时处理每个被保险人的可能的受益人信息
	$insurant_info=array();
	$insuredDTOList = array();
	foreach($list_subject_insurant AS $key_insurant =>$value_insurant )
	{
		if(defined('IS_NO_GBK'))
		{
			$user_info_assured_fullname =  $value_insurant['fullname']; 
		}
		else
		{
			$user_info_assured_fullname =  mb_convert_encoding($value_insurant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}
		//先组织被保险人员的受益人信息
		//通过投保单号和被保险人uid来查询受益人信息
		$benefitListDTO = array();
		if($policy_arr['beneficiary'])//是否指定受益人是法定
		{
			$benefitListDTO = array("benefitListDTO"=>array(
															"isLegal"=>"1")
									);
		}
		else//
		{
			//受益人信息需要查询处理
			$uid = $value_insurant['uid'];
			$wheresql = "uid=$uid AND policy_subject_id='$policy_subject_id'";
			$sql = "SELECT * FROM ".tname('insurance_policy_beneficiary_info')." WHERE $wheresql";
			$query = $_SGLOBAL['db']->query($sql);
			$benefitDTOList = array();
			while ($one_beneficiary_info = $_SGLOBAL['db']->fetch_array($query))
			{
				$benefitDTOList[] = array(
											"name"=>$one_beneficiary_info['name'],
				                            "sex"=>$one_beneficiary_info['sex'],
				                            "cardType"=>$one_beneficiary_info['cardType'],
				                            "cardNo"=>$one_beneficiary_info['cardNo'],
				                            "cardValidDate"=>$one_beneficiary_info['cardValidDate'],
				                            "cardExpDate"=>$one_beneficiary_info['cardExpDate'],
				                            "birthday"=>$one_beneficiary_info['birthday'],
				                            "nativePlace"=>$one_beneficiary_info['nativePlace'],
				                            "benefitRelation"=>$one_beneficiary_info['benefitRelation'],
				                            "benefitScale"=>$one_beneficiary_info['benefitScale'],
				                            "benefitSort"=>$one_beneficiary_info['benefitSort']
											);
			}
			$benefitListDTO = array("benefitListDTO"=>array(
															"isLegal"=>"0",
															"benefitDTOList"=>$benefitDTOList
															)
									);
		}
		$one_insurant_info = array(
									"name"=>$value_insurant['fullname'],
					                "sex"=>$value_insurant['gender'],
					                "cardType"=>$value_insurant['certificates_type'],
					                "cardNo"=>$value_insurant['certificates_code'],
					                "cardValidDate"=>$value_insurant['cardValidDate'],
					                "cardExpDate"=>$value_insurant['cardExpDate'],
					                "birthday"=>$value_insurant['birthday'],
					                "mobile"=>$value_insurant['mobiletelephone'],
					                "email"=>$user_info_applicant['email'],//被保险人没有，使用投保人的
					                "nativePlace"=>$value_insurant['nation_code'],//国籍
					                "residentProvince"=>$value_insurant['province_code'],//省
					                "residentCity"=>$value_insurant['city_code'],//市
					                "residentCounty"=>$value_insurant['county_code'],//县
					                "address"=>$value_insurant['address'],
					                "zip"=>$user_info_applicant['zipcode'],//被保险人没有，使用投保人的
					                "industry"=>$value_insurant['business_type'],//所处行业代码
					                "jobs"=>$value_insurant['occupationClassCode'],
					                //暂时取投保单中的被保险人与投保人关系字段，
					                //如果有多个被保险人，而且关系又不一样，那到时得修改代码，
					                //在t_user_info中增加关系字段，再处理
					                "insuredRelation"=>$policy_arr['relationship_with_insured'],
									);
									
		$insuredDTOList[] = array_merge($one_insurant_info, $benefitListDTO);
		
	}//end foreach $list_subject_insurant, 被保险人设置循环结束

	$insuredListDTO = array("insuredListDTO"=>array("insuredDTOList"=>$insuredDTOList)
												);

	return $insuredListDTO;
		
}

//////////////////////////////////////////////////////////////////////////////////
function get_xinhua_policy_additional_info($policy_id)
{
	global $_SGLOBAL;
	$wheresql = "policy_id='$policy_id'";
	$sql = "SELECT * FROM ".tname('insurance_policy_xinhua_renshou_additional_info')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$policy_xinhua_additional_info = $_SGLOBAL['db']->fetch_array($query);
	
	return $policy_xinhua_additional_info;
}


//added by zhangxi, 20150424, 根据投保单的实际信息更新佣金率
function update_rate_of_commision_xinhua_shaoer($org_rate, 
													$order_sn)
{
	global $_SGLOBAL;
	global $attr_pay_period_commision_rate;
	$wheresql = "order_sn='$order_sn'";
	$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$policy_info = $_SGLOBAL['db']->fetch_array($query);
	
	
	$additional_info = get_xinhua_policy_additional_info($policy_info['policy_id']);
	if(empty($additional_info))
	{
		ss_log(__FUNCTION__.", error, NCI additional info is empty");
		return $org_rate;
	}
	//$additional_info['payPeriod'];//缴费期间
	$pay_period = $additional_info['payPeriod'];
	if($pay_period == 18)//缴至18岁的情况
	{
		//算出实际需要缴费的年数
		$ret = get_policy_info($policy_info['policy_id']);//得到保单信息
	
		ss_log(__FUNCTION__.", after function get_policy_info!");
	
		//$policy_attr = $ret['policy_arr'];
		//$user_info_applicant = $ret['user_info_applicant'];
		$list_subject = $ret['list_subject'];//多层级一个链表
		//获取被保险人的出生日期
		$birthday = '';
		foreach($list_subject AS $key =>$value)
		{
			$list_subject_product  = $value['list_subject_product'] ;
			$list_subject_insurant = $value['list_subject_insurant'] ;
			////////////////////////////////////////////////////
			$product = $list_subject[0]['list_subject_product'][0];
			
			$birthday = $list_subject_insurant[0]['birthday'];
			//得到多个被保险人。
//			foreach($list_subject_insurant AS $key_insurant =>$value_insurant )
//			{
//				$birthday = $value_insurant['birthday'];
//			}
		}
		//根据被保险人出生日期，算出需要缴多少年保费
		$age = get_age_by_birthday($birthday);
		ss_log(__FUNCTION__.", get insurant age=".$age);
		if($age>=0)
		{
			$pay_period = 18 - $age;
			
		}
		
		
	}
	
	$new_rate = $attr_pay_period_commision_rate[$pay_period];
	ss_log(__FUNCTION__.", new_rate=".$new_rate.", org_rate=".$org_rate);
	
	return empty($new_rate) ? $org_rate : $new_rate;
}







$G_XLS_UPLOAD_FORMAT_XINHUA_PILIANG = array(
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
					


//added by zhangxi, 20150319, 新华产品xls文件上传
function process_file_xls_xinhua_insured($uploadfile, $product)
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
	global $G_XLS_UPLOAD_FORMAT_XINHUA_PILIANG;
	//自己设置的上传文件存放路径
	require_once $path.'/oop/lib/Excel/PHPExcel.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/IOFactory.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/Reader/Excel5.php';
	require_once $path.'/oop/lib/Excel/PHPExcel/Reader/Excel2007.php';


	$result = process_file_xls_upload_common($uploadfile, $product, $G_XLS_UPLOAD_FORMAT_XINHUA_PILIANG);
	
	return $result;
}


?>