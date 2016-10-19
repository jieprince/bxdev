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
$java_class_name_sinosig = 'com.yanda.imsp.util.ins.PingAnPolicyFetcher';

////////////////////////////////////////////////////////////////////////////
$attr_sex_sinosig = array("2"=>"女",
		"1"=>"男",
);

//与被保险人关系 1:本人 2:配偶 3 :父子 4:父女 5:受益人 6:受益人 7:投保人 A:母子 B:母女 C:兄弟 D:姐弟 G:祖孙 H:雇佣 I:子女 9:其他 8:转换不详
$attr_relationship_applicant_with_insured_sinosig = array(
		"10"=>"本人",
		"61"=>"配偶",
		"62"=>"父母",
		"63"=>"子女",
		"64"=>"其他具有抚养或赡养关系的家庭成员或  近亲属",
		"65"=>"劳动关系",
		"66"=>"丈夫",
		"67"=>"妻子",
		"68"=>"父亲",
		"69"=>"母亲",
		"70"=>"儿子",
		"71"=>"女儿",
		"99"=>"其他",
);
//<!--（非空）证件类型，
$attr_certificates_type_sinosig = array(
		"10"=>"身份证",
		"11"=>"户口薄",
		"12"=>"驾驶证",
		"13"=>"军官证",
		"14"=>"士兵证",
		"17"=>"港澳通行证",
		"18"=>"台湾通行证",
		"99"=>"其他",
		"51"=>"护照",
		"61"=>"港台同胞证",
);
$attr_insured_certificates_type_sinosig = array(
		"10"=>"身份证",
		"11"=>"户口薄",
		"12"=>"驾驶证",
		"13"=>"军官证",
		"14"=>"士兵证",
		"17"=>"港澳通行证",
		"18"=>"台湾通行证",
		"99"=>"其他",
		"51"=>"护照",
		"61"=>"港台同胞证",
);




//////////////////start add by wangcya, 20141219 ,不同厂商的证件号码和性别//////////////////////////////
$attr_certificates_type_sinosig_single_unify = array(
		"10"=>1,//'身份证';
		"12"=>2,//'驾驶证';
		"51"=>4,//'护照';
		"17"=>5,//'港澳回乡证或台胞证';
		"13"=>8,//'军人证';
		"99"=>9,//'其他';
);


$attr_sex_sinosig_unify = array("2"=>"F",
		"1"=>"M",
);



//检查平安的输入的函数,同时又返回了需要传递的
function input_check_sinosig($POST)
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
		!isset($POST['applicant_sex'])
		)
	{
		showmessage("您输入的投保信息不完整！");
		exit(0);
	}
	
	////////////////////////////////////////////////////////////

	$global_info['applyNum'] = intval($POST['applyNum']);//份数
	$global_info['totalModalPremium'] = $POST['totalModalPremium'];//总保费,应该从前端计算得到的

	ss_log(__FUNCTION__.", in function input_check_pingan, totalModalPremium: ".$POST['totalModalPremium']);
	//$totalModalPremium'] = $apply_num*intval($product[premium]);//$POST['totalModalPremium'];//从费用

	$global_info['beneficiary'] = $POST['beneficiary'];//受益人
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期


	$global_info['apply_day'] = $apply_period = empty($POST['apply_day'])?intval($POST['period']):intval($POST['apply_day']);
	
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
	ss_log(__FUNCTION__." list_user=".var_export($list_user,true));
	/////////////////////////////////////////////////////////
	$relationshipWithInsured = trim($POST['relationshipWithInsured']);
	////////////////投保人的身份信息////////////////////////////////
	$list_assured = ebao_parse_post_insured_info($list_user);

	$product_id = intval($POST['product_id']);
	//////////一个subjectInfo内多个产品，多个被保险人///////////////////////////////////////////////////////////
	////////方案一是父母的////////////
	$subjectInfo1 = array();

	$list_product_id = array();
	$list_product_id[] = $product_id;//
	$subjectInfo1['list_product_id'] = $list_product_id;

	$subjectInfo1['list_assured'] = $list_assured;


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

function input_check_sinosig_other_jingwailvyou($POST)
{
	$global_info = array();
	
	
	$global_info['period_code'] = trim($POST['period_code']);
	$global_info['outgoingPurpose'] = trim($POST['outgoingPurpose']);
	$global_info['destinationCountry'] = trim($POST['destinationCountry']);
	$global_info['schoolOrCompany'] = trim($POST['schoolOrCompany']);
	
	$retattr = array(	"global_info"=>$global_info);
	
	return $retattr;
}


//进行各险种的投保动作
function post_policy_sinosig(
							$attribute_type,//add by wangcya, 20141213
							$attribute_code,//add by wangcya, 20141213
							$policy_arr,
							$user_info_applicant,
							$list_subject
						   )
{

	global $_SGLOBAL;
	/////////////////////////////////////////////////////////
	
	ss_log("into function ".__FUNCTION__);
	
	$order_sn	= $policy_arr['order_sn'];
	$policy_id	= $policy_arr['policy_id'];
	$data_interface = array("policy_arr"=>$policy_arr,
							"user_info_applicant"=>$user_info_applicant,
							"list_subject"=>$list_subject
							);
	$jsonStr = TO_JSON($data_interface);

	$INSURER_STR = "insurer";
	if(!empty($jsonStr))
	{
		$af_path = gen_file_fullpath('policy','yangguang',S_ROOT, $order_sn."_".$policy_id."_yangguang_policy_post.json");
		$bytes_num = file_put_contents($af_path,$jsonStr);	
		$FILE_UUID = getRandOnly_Id(0,1);
		$af_path_serial = gen_file_fullpath('policy','yangguang',S_ROOT,$policy_id."_".$FILE_UUID."_yangguang_policy_post.json");
		file_put_contents($af_path_serial,$jsonStr);
		$return_path = gen_file_fullpath('policy','yangguang',S_ROOT, $order_sn."_".$policy_id."_yangguang_policy_post_ret.json");
		ss_log("return_path: ".$return_path);
		$post_str = "jsonStr=".$jsonStr;
		$post_str .= "&callBackURL=".CALLBACK_URL;
		$post_str .= "&insurer_code=".$policy_arr['insurer_code'];
		$post_str .= "&insurer_product_code=";
		$post_str .= "&insurer_attribute_code=".$attribute_code;
		$post_str .= "&channel_code=";
		$post_str .= "&insurer_type=".$INSURER_STR;
		$post_str .= "&keyid=".$policy_id;
		
		//POST_POLICY_URL
		$ret = post_request_to_service('http',POST_SERVER_IP,POST_SERVER_PORT,$post_str,POST_POLICY_URL);
		$result = $ret;
		$retmsg = "post policying";
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg);
	}//not empty($jsonStr)
	else
	{
		ss_log(__FUNCTION__.":gen jsonStr null");
		$result = 110;
		$retmsg = "post policy return is null!";
		ss_log($retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg);
	}
	/////////////////////////////////////////////////////////////
	
	return $result_attr;

}


//start add by wangcya , 20150106,模块化

function gen_post_policy_xml_sinosig(
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


function gen_post_policy_xml_sinosig_jingwailvyou(
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
			//mod by zhangxi, 20141202, 暂时从保单中取得投保份数和投保金额
			//如果有保单中有多个产品下，这样是不对的 。
			//但是目前数据中，对产品价格受影响因素影响的产品 ，是没法从保单获取产品具体定价的
			//这个后续在保单相关的信息中需要增加相关信息才行
			/*
				$value_product[premium] = sprintf("%01.2f", $value_product[premium] );

			$product_str .=   '    <productInfo>
			<productCode>'.$value_product[product_code].'</productCode>
			<applyNum>1</applyNum>
			<totalModalPremium>'.$value_product[premium].'</totalModalPremium>
			</productInfo>';
			$total_product_premium = $total_product_premium + $value_product[premium];
			*/
			
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




function process_return_xml_data_sinosig(
									$policy_id,
		                            $return_data,
									$order_sn,
									$orderNum
								         )
{
	global $_SGLOBAL;
	///////////////////////////////////////////////////////////////////
	ss_log("into ".__FUNCTION__);
	/////////先解析出来返回的xml//////////////////////////////////
	/*
	 $xml = simplexml_load_string($return_data);
	
	$BK_SERIAL = $xml->BK_SERIAL;
	
	$PA_RSLT_CODE = $xml->PA_RSLT_CODE;
	$PA_RSLT_MESG = $xml->PA_RSLT_MESG;
	$POLICYNO = $xml->policyNo;//这里返回的依然是个SimpleXMLElement对象
	$VALIDATECODE = $xml->validateCode;
	*/
	preg_match_all('/<BK_SERIAL>(.*)<\/BK_SERIAL>/isU',$return_data,$arr);
	if($arr[1])
		$BK_SERIAL = trim($arr[1][0]);
	
	preg_match_all('/<PA_RSLT_CODE>(.*)<\/PA_RSLT_CODE>/isU',$return_data,$arr);
	if($arr[1])
		$PA_RSLT_CODE = trim($arr[1][0]);
	
	preg_match_all('/<PA_RSLT_MESG>(.*)<\/PA_RSLT_MESG>/isU',$return_data,$arr);
	if($arr[1])
		$PA_RSLT_MESG = trim($arr[1][0]);
	
	preg_match_all('/<policyNo>(.*)<\/policyNo>/isU',$return_data,$arr);
	if($arr[1])
		$policy_no = $POLICYNO = trim($arr[1][0]);//modify by wangcya, 20141119
	
	preg_match_all('/<validateCode>(.*)<\/validateCode>/isU',$return_data,$arr);
	if($arr[1])
		$VALIDATECODE = trim($arr[1][0]);
	
	
	//$PA_RSLT_MESG = iconv('GB2312', 'UTF-8', $PA_RSLT_MESG); //将字符串的编码从GB2312转到UTF-8
	
	ss_log("PA_RSLT_CODE: ".$PA_RSLT_CODE);
	ss_log("PA_RSLT_MESG: ".$PA_RSLT_MESG);
	/////////下载电子保单/////////////////////////////
	
	if($PA_RSLT_CODE=="999999"////生成保单成功
		//$BK_SERIAL== $policy_id//$orderNum//并且这个要和订单号相等,by wangcya, 20150326,这里可以不必处理，不必对应投保单了？
	)
	{//投保成功
		ss_log("pingan retunrn date success 999999");
		ss_log("return code: ".$PA_RSLT_CODE." BK_SERIAL: ".$BK_SERIAL);
		ss_log("order_sn: ".$order_sn);
		
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
			
			$setsqlarr = array( 'PA_RSLT_CODE'=>$PA_RSLT_CODE,
								'PA_RSLT_MESG'=>$PA_RSLT_MESG,
								'validateCode'=>$VALIDATECODE,
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
	
			ss_log("return policyNo: ".$POLICYNO." order_sn: ".$order_sn);
	
			//在这里更改该保单状态
			updatetable('insurance_policy',
						array('policy_status'=>'insured','policy_no'=>$POLICYNO),
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
			$policy_attr['PA_RSLT_CODE'];//add by wangcya,20150109
			$policy_attr['PA_RSLT_CODE']	=	$PA_RSLT_CODE;
			$policy_attr['PA_RSLT_MESG']	=	$PA_RSLT_MESG;
			$policy_attr['validateCode']	=	$VALIDATECODE;
			$policy_attr['policy_no']	=	$POLICYNO;
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
		
		if($client_id&& $activity_id)
		{
			ss_log("该保单来自C端直接投保，policy_id: ".$policy_id." client id :".$client_id);
			$client_info = get_client_info_by_id($client_id);
			$mobile_phone = $client_info['mobile_phone'];
			if($mobile_phone)
			{
				ss_log("将要发送短信给C端用户,mobile_phone: ".$mobile_phone);
				$content = "您的投保已经成功，保单号：".$POLICYNO." ，请关注微信公众号：e_baoxian 查询电子保单。";
		
				ss_log($content);
				
				include_once(S_ROOT.'../includes/lib_common.php');
				ss_log(S_ROOT.'../includes/lib_common.php');
				
				baoxian_send_msg($mobile_phone,$content);//加参数就是测试环境要发短信
				
				ss_log("短信发送完毕");
			}
			else
			{
				ss_log("not find client mobile phone!");
			}
		}
		else
		{
			//ss_log("client_id is null");
		}
		//////////////end add by wangcya, 20150207////////////////////////////////
		
		////////////////获取电子保单///////////////////////////////////
		if(1)//defined('USE_ASYN_JAVA'))//暂时不获取点在保单，点击的时候获取。
		{//20150203，如果定义了异步方式，则投保时候，异步方式获取电子保单。否则不在投保阶段不获取
		
			ss_log("投保时候将要获取电子保单");
			//电子保单的路径暂时不动，涉及到打包下载的问题
			$filename = S_ROOT."xml/message/".$policy_no."_pingan_policy.pdf";//add by wangcya, 20141119
			ss_log("pdf path: ".$filename);
	
			$product_code = $policy_attr['product_code'];//add by wangcya,20150109
			ss_log("product_code: ".$product_code);

			$policy_attr['readfile'] = false;//add by wangcya, 20150207
			$use_asyn = true;
			$result_attr = java_get_policy_file_pingan($use_asyn,$policy_attr,	$product_code, $filename);
			return $result_attr;
		}	


	
	}//生成保单成功
	else//生成保单失败
	{
		//ss_log("PA_RSLT_CODE: ".$PA_RSLT_CODE);
		//ss_log("PA_RSLT_MESG: ".$PA_RSLT_MESG);
			
		if(empty($PA_RSLT_CODE))
		{//平安返回为空
			
			$result = 110;
			$retmsg = "pingan返回为空";
			
			ss_log("result：".$result );
			ss_log("解析返回报文错误！");
			
			//start add by wangcya, 20150126，如果返回报文为空，但是有节点，就一般是order_num出现了重复了所导致，所以要重新生成。
			$order_num = getRandOnly_Id(0);
			
			ss_log("re gen order_num: ".$order_num);
			ss_log("will update policy_id: ".$policy_id." order_num: ".$order_num);
			updatetable("insurance_policy", array("order_num"=>$order_num), array("policy_id"=>$policy_id));
			//end add by wangcya, 20150126
		}
		else
		{
			$result = $PA_RSLT_CODE;
			$retmsg = $PA_RSLT_MESG;
			
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


//获取电子保单
function get_policyfile_sinosig_yiwai($pdf_filename,
		                             $policy_attr,
									 $user_info_applicant,
									 $list_subject		                           
		                            )
{

	ss_log("into function :".__FUNCTION__);
	
	///////////////////////////////////////////////////////////////////////////////////
	$product_code = $list_subject[0]['list_subject_product'][0]['product_code'];//add by wangcya, 20150109
	
	$use_asyn = false;
	$result_attr = java_get_policy_file_sinosig( $use_asyn, $policy_attr,$product_code, $pdf_filename );
	
	////////////////////////////////////////////////////////////////////////////////////
	
	return $result_attr;
}


//得到pdf格式的电子保单
function java_get_policy_file_sinosig( $use_asyn,
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
	//$keyFile = S_ROOT.CLIENT_KEY_PINGAN;
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
	//$url = "http://epcis-ptp-dmzstg2.pingan.com.cn:9080/epcis.ptp.partner.getAhsEPolicyPDFWithCert.do";//测试环境地址
	//$url = "https://epcis-ptp.pingan.com.cn/epcis.ptp.partner.getAhsEPolicyPDFWithCert.do";//正式环境的地址
	
	/*
	 * getPolicyURL 取得电子保单的URL
				policyNo 保单号
				policyValidateNo 验证码
				orderNum    订单编号
				isSeperated 取值："single"/"group"/""/"team" 如果是个单，填写空；如果是团单团打，填写group；如果是团单个单，填写single
			electronicPolicy参数取值范围: null|"elecpy2"   其中：null或空字符串表示非共保    "elecpy2"为共保打印
				path 文件路径两个作用：1.从%path%/config下取得证书文件；2.得到的保单会保存到 %path%/message下
				isDevS Y/N Y表示调试，输出测试信息 N表示不输出测试信息 
*/


	
	//start add by wangcya, 20150109,
	ss_log(__FUNCTION__.":product_code: ".$product_code);
	
	if($product_code)
	{
		$BRNO_value = find_BRNO_by_product_code($product_code);
		
		$partnerName = $BRNO_value['partnerName'];
		$BANK_CODE = $BRNO_value['BANK_CODE'];
		$BRNO = $BRNO_value['BRNO'];
	}

	//$BRNO = '78800000';
	ss_log(" product_code: ".$product_code);
	ss_log(" BRNO: ".$BRNO);
	//end add by wangcya, 20150109
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
	
	//////////////////////////////////////////////////////////////////////////////////////
	$logFileName = S_ROOT."xml/log/pingan_getpolicyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	ss_log(__FUNCTION__.":logFileName: ".$logFileName);
	
	if(!$use_asyn)//同步
	{
		ss_log(__FUNCTION__.":将使用同步方式获取电子保单");
		ss_log(__FUNCTION__.":pingan will applyElectricPolicyBill");
		$ret = (string)$obj_java->applyElectricPolicyBill( 
													$url,
													$policy_no,
													$VALIDATECODE,
													$orderNum,
													$isSeperated,
												    $electronicPolicy,
													$keyFile,//  证书文件全名
													$keypassword,
	                								$pdfFile,//  保存电子保单文件全名
	                								$logFileName,//$logFile,//  保存日志文件
													$isDevS,
													$BRNO
													);
		
		
		
	
		ss_log("applyElectricPolicyBill ret: ".$ret);
		
		if($ret==0)
		{
			//usleep(1);//del by wangcya, 20150202,wait for file cache,
			//ss_log("policy file: ".$filename);
			if (file_exists($pdfFile))//如果这个文件已经存在，则返回给用户。
			{
				//$policy_id = $policy_attr['policy_id'];
				$readfile = $policy_attr['readfile'];
				/////////////////////////////////////////////////////////
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
					//add by dingchaoyang 2014-12-4 如果是手机端访问，不输出文件，直接返回文件路径
// 					include_once (str_replace('baoxian','',S_ROOT)  . 'api/EBaoApp/platformEnvironment.class.php');
// 					if (!(PlatformEnvironment::isMobilePlatform()))
// 					{
						//mod by zhangxi, 20150207, 手机浏览器关于文件下载的问题。
						$file_name = "insured.pdf";
						header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
						header('Content-type: application/pdf');
						readfile($pdfFile);
						exit(0);
// 					}
					//end by dingchaoyang 2014-12-4 如果是手机端访问，不输出文件，直接返回文件路径
				}
                
				$result_attr = array(	'retcode'=>$result,
						'retmsg'=> $retmsg,'retFileName'=>str_replace(S_ROOT,'',$pdfFile)
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
		$type = "getpolicyfile";
		
		ss_log("insurer_code: ".$insurer_code);
		ss_log("type: ".$type);
		ss_log("policy_id: ".$policy_id);
		ss_log("callBackURL: ".$callBackURL);
		
		/*
			private String url;
			private String keyFile;
			private String logFileName;
			private String policyNo;
			private String policyValidateNo;
			private String orderNum ;
			private String isSeperated ;
			private String electronicPolicy;
			private String pdfFileName;
			private String isDevS;
			private String account;
		*/
		
		$param_other = array(   "url"=>$url ,//投保保险公司服务地址
				"policyNo"=>$policy_no ,//投保报文内容
				"policyValidateNo"=>$VALIDATECODE,//投保报文文件
				"orderNum"=>$orderNum ,//投保报文文件编码格式
				"isSeperated"=>$isSeperated,//返回报文保存全路径
				"electronicPolicy"=>$electronicPolicy, //日志文件
				"pdfFileName"=>$pdfFile,
				"isDevS"=>$isDevS,
				"account"=>$BRNO,
				"logFileName"=>$logFileName,//$logFile,
				"keyFile"=>$keyFile,//平安证书文件 ,//投保保险公司服务地址
				"keyStorePassword"=>$keypassword,
				"trustStorePassword"=>$keypassword,
				
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

	return $result_attr;

}


////注销保单
function withdraw_policyfile_sinosig_yiwai(	$policy_id,
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

//注销保单
function post_withdraw_policy_sinosig( $policy_attr , $product_code )
{
	global $_SGLOBAL, $java_class_name_pingan;
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
	if(!empty($strxml))
	{
		
		//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_policy_withdraw.xml";
		$af_path = gen_file_fullpath('policy','pingan',S_ROOT, $order_sn."_".$policy_id."_pingan_policy_withdraw.xml");
		file_put_contents($af_path,$strxml);

		//start add by wangcya, 20150209,把携带序列号的也保存起来
		$UUID = $BK_SERIAL;
		//$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_pingan_policy_withdraw.xml";
		$af_path_serial = gen_file_fullpath('policy','pingan',S_ROOT, $order_sn."_".$policy_id."_".$UUID."_pingan_policy_withdraw.xml");
		file_put_contents($af_path_serial,$strxml);
		//end add by wangcya, 20150209,把携带序列号的也保存起来
		/*
		$ch = curl_init();
		////////////////////////////////////////////////////////////////
		$flagpost= 1;
		//$url= 'https://222.68.184.181:8107';

		$url = URL_PINGAN_POST_POLICY;
		$port =  8107;
		if(empty($url))
		{
			$result = 110;
			$retmsg = "URL_PINGAN_POST_POLICY is null!";
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
			return $result_attr;
		}

		ss_log("withdraw policy url:".$url." port: ".$port);
		//发送请求到保险公司服务器
		$return_data = curlPost($ch,$flagpost,$url, $port, $strxml, 10, 0);
		*/
		
		$p = create_java_obj($java_class_name_pingan);
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
		
		$url = URL_PINGAN_POST_POLICY;
		$port =  8107;
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
		//$keyFile = S_ROOT.CLIENT_KEY_PINGAN;
		$respXmlFileName = "";
		ss_log("url: ".URL_PINGAN_POST_POLICY);
		ss_log("keyFile: ".$keyFile);
		
		$return_data = (string)$p->sendMessage( 
				$url , 
				$port , 
				$strxml ,
				$respXmlFileName,
				$keyFile,
				$keypassword,
				$keypassword
				
				);

		ss_log("after withdraw call function sendMessage");
		
		if(!empty($return_data))
		{
			
			//先保存一份
			
			//$return_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_policy_withdraw_ret.xml";
			$return_path = gen_file_fullpath('policy','pingan',S_ROOT, $order_sn."_".$policy_id."_pingan_policy_withdraw_ret.xml");
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

		/////////先解析出来返回的xml//////////////////////////////////
		/*
		$xml = new DOMDocument();
		$xml->loadXML($return_data);
		$root = $xml->documentElement;
		$nodes = $root->getElementsByTagName("policyInfo");
		$policyInfo = $nodes->item(0)->nodeValue;
		if(empty($policyInfo))
		{
			$retmsg = "withdraw policy , ret policyInfo is NULL!";
			ss_log($retmsg);
			echo $retmsg;
			exit(0);
		}
		*/
		
		preg_match_all('/<BK_SERIAL>(.*)<\/BK_SERIAL>/isU',$return_data,$arr);
		if($arr[1])
		{
			$BK_SERIAL_OUT = trim($arr[1][0]);
		}

		preg_match_all('/<PA_RSLT_CODE>(.*)<\/PA_RSLT_CODE>/isU',$return_data,$arr);
		if($arr[1])
		{
			$PA_RSLT_CODE_OUT = trim($arr[1][0]);
		}

		preg_match_all('/<PA_RSLT_MESG>(.*)<\/PA_RSLT_MESG>/isU',$return_data,$arr);
		if($arr[1])
		{
			$PA_RSLT_MESG_OUT = trim($arr[1][0]);
		}

		preg_match_all('/<policyNo>(.*)<\/policyNo>/isU',$return_data,$arr);
		if($arr[1])
		{
			$POLICYNO_OUT = trim($arr[1][0]);
		}

		preg_match_all('/<validateCode>(.*)<\/validateCode>/isU',$return_data,$arr);
		if($arr[1])
		{
			$VALIDATECODE_OUT = trim($arr[1][0]);
		}

		ss_log("PA_RSLT_CODE_OUT: ".$PA_RSLT_CODE_OUT);
		ss_log("PA_RSLT_MESG_OUT: ".$PA_RSLT_MESG_OUT);
		
		
		ss_log("BK_SERIAL_OUT: ".$BK_SERIAL_OUT);
		ss_log("POLICYNO_OUT: ".$POLICYNO_OUT);
		
		ss_log("orderNum: ".$orderNum);
		ss_log("POLICYNO: ".$POLICYNO);
		ss_log("order_sn: ".$order_sn);
		//ss_log("withdraw policy return code: ".$PA_RSLT_CODE_OUT." message: ".$PA_RSLT_MESG_OUT);

		if( $PA_RSLT_CODE_OUT =="999999"&&////注销保单成功
				$BK_SERIAL_OUT == $BK_SERIAL&&//并且这个要和订单号相等$orderNum
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
			if(empty($PA_RSLT_CODE_OUT))
			{//平安返回为空
				
				$result = 110;
				$retmsg = "pingan返回有节点，但是内容为空";
					
				ss_log("result：".$result );
				ss_log("解析返回报文错误！");
				
				$result_attr = array(	'retcode'=>$result,
						'retmsg'=> $retmsg
				);

			}
			//end add by wangcya,20150202,有节点，但是没内容
			else
			{

				$retmsg = "withdraw fail!,PA_RSLT_MESG_OUT: ".$PA_RSLT_MESG_OUT;
				ss_log($retmsg);
	
						
				$result_attr = array(	'retcode'=>$PA_RSLT_CODE_OUT,
										'retmsg'=> $PA_RSLT_MESG_OUT
									);
			}
			
		}
		////////////////////////////////////////////////////////////
	}

	return $result_attr;

}




?>