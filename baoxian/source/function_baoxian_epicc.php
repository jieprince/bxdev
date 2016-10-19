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

$java_class_name_epicc = 'com.yanda.imsp.util.ins.PICCPropertyInsurance';
$LOGFILE_EPICC=S_ROOT."log/java_epicc_post_policy.log";
////////////////////////////////////////////////////////////////////////////

//ok										
//	01:身份证 02：户口簿 03：护照 04：军官证 05:驾驶执照 06：返乡证 07：港澳身份证 99：其他
$attr_epicc_GovtIDTC = array(
							"01"=>"身份证",
							"02"=>"户口薄",
							"03"=>"护照",
							"04"=>"军官证",
							"05"=>"驾照",
							"06"=>"返乡证",
							"07"=>"港澳身份证",
							"99"=>"其他"
					);	
//ok
$attr_epicc_Gender = array(
							"1"=>"男",
							"2"=>"女",
							);	
//added by zhangxi,20150506, 保险公司并没有投保人与被保险人之间的关系，
//这个是自己加上的。便于统一
$attr_epicc_RelationRoleCode = array(
							"1"=>"配偶",//
							"2"=>"子女",//
							"3"=>"父母",//
							"4"=>"亲属",//
							"5"=>"本人",//
							"6"=>"其他",//
							"7"=>"雇佣",//
							
								);	
							
$attr_certificates_type_epicc_single_unify = array(
		"01"=>1,//'身份证';
		//"05"=>2,//'驾驶证';
		"03"=>4,//'护照';
		"06"=>5,//'港澳回乡证或台胞证';
		"04"=>8,//'军人证';
		"99"=>9,//'其他';
);

$attr_sex_epicc_unify = array("2"=>"F",
		"1"=>"M",
);			

																												
////////////////////////////////////////////////////////////////////////////////


function input_check_epicc($product, $POST)
{
	global $_SGLOBAL;
	global $attr_epicc_GovtIDTC; //证件类型对应关系
	global $attr_sex_epicc_unify;//性别对应关系
	global $global_attr_obj_type;
	
	
	
	
	/*	$attr_certificates_type_epicc_single_unify = array(
			"01"=>1,//'身份证';
			//"05"=>2,//'驾驶证';
			"03"=>4,//'护照';
			"06"=>5,//'港澳回乡证或台胞证';
			"04"=>8,//'军人证';
			"99"=>9,//'其他';
	);*/
	global $attr_certificates_type_epicc_single_unify;
	
	
	
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	ss_log(__FUNCTION__.", GET IN");
	$insurer_code 	= $product['insurer_code'];
	
	$global_info = array();
	$agent_uid = $_SGLOBAL['supe_uid'];//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	
	//////////////////有效性检查////
	if(	!isset($POST['applyNum'])||
		!isset($POST['startDate'])||
		!isset($POST['endDate'])||
		!isset($POST['applicant_fullname'])||
		!isset($POST['applicant_certificates_type'])||
		!isset($POST['applicant_certificates_code'])||
		!isset($POST['applicant_birthday'])||
		!isset($POST['applicant_gender'])||
		!isset($POST['relationshipWithInsured'])
		)
	{
		showmessage("您输入的投保信息不完整！");
		exit(0);
	}
	
	////////////////////////////////////////////////////////////

	$global_info['applyNum'] = intval($POST['applyNum']);//份数
	$global_info['totalModalPremium'] = $POST['totalModalPremium'];//总保费,应该从前端计算得到的
	$global_info['beneficiary'] = $POST['beneficiary'];//受益人
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期

	$global_info['apply_day'] = empty($POST['apply_day'])?intval($POST['period']):intval($POST['apply_day']);//add by wangcya, 20150110, 投保的天数
	//added by zhangxi, 20150514, 旅游线仅比标准的产品多了个目的地，因此直接放到本函数中
	if($product['attribute_type'] == 'lvyou'
	||$product['attribute_type'] == 'jingwailvyou')
	{
		$global_info['destinationCountry'] = isset($POST['destinationCountry'])?$POST['destinationCountry']:0;
		$global_info['influencingfactor_id'] = isset($POST['product_influencingfactor_id'])?$POST['product_influencingfactor_id']:0;
	}
	
	///////////////组合投保人信息//////////////////////////////////////////////////
	$user_info_applicant = array();

	/////////////////////////////////////////////////////////
	$relationshipWithInsured = trim($POST['relationshipWithInsured']);//
	
	$businessType = empty($POST['businessType'])?1:trim($POST['businessType']);
	if($businessType == 1)//个人
	{
		$user_info_applicant['certificates_type'] = $POST['applicant_certificates_type'];
		$user_info_applicant['certificates_code'] = $POST['applicant_certificates_code'];
		$user_info_applicant['fullname'] = $POST['applicant_fullname'];
		$user_info_applicant['fullname_english'] = $POST['applicant_fullname_english'];
		$user_info_applicant['gender'] = $POST['applicant_gender'];
		$user_info_applicant['birthday'] = $POST['applicant_birthday'];
		$user_info_applicant['mobiletelephone'] = $POST['applicant_mobilephone'];
		$user_info_applicant['email'] = $POST['applicant_email'];
		$user_info_applicant['address'] = $POST['applicant_address'];
		//start add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		$user_info_applicant['certificates_type_unify'] = $attr_epicc_GovtIDTC[$user_info_applicant['certificates_type']];
		$user_info_applicant['gender_unify'] = $attr_sex_epicc_unify[$user_info_applicant['gender']];
		$user_info_applicant['type'] = 1;//0,被保险人，1投保人,2两者身份相同
		$user_info_applicant['agent_uid'] = $agent_uid;
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	}
	else//团体投保人
	{
		//投保人信息获取
		$user_info_applicant['group_name'] = $POST['applicant_group_name'];
		$user_info_applicant['group_certificates_type'] = $POST['applicant_group_certificates_type'];
		$user_info_applicant['group_certificates_code'] = $POST['applicant_group_certificates_code'];
		
		$user_info_applicant['group_abbr'] = $POST['group_abbr'];
		$user_info_applicant['company_attribute'] = $POST['company_attribute'];
		//$user_info_applicant['address'] = $POST['address'];
		//$user_info_applicant['telephone'] = $POST['telephone'];
		$user_info_applicant['mobiletelephone'] = $POST['applicant_group_mobilephone'];
		$user_info_applicant['email'] = $POST['applicant_group_email'];
		
		//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		$user_info_applicant['group_certificates_type_unify'] = $attr_certificates_type_epicc_single_unify[$user_info_applicant['group_certificates_type']];
		$user_info_applicant['agent_uid'] = $agent_uid;
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	}

	/////////////////////////////////////////////////////////////////////
	$assured_info = array();

	
	////////////////投保人的身份信息////////////////////////////////
	$real_apply_num = 0;
	if($businessType == 1)//投保人是个人
	{
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
			$assured_info['gender']  = trim($assured_post['assured_gender']);
			$assured_info['mobiletelephone'] =  trim($assured_post['assured_mobilephone']);
			$assured_info['email'] =  trim($assured_post['assured_email']);
			
			//add by wangcya, 20141219 ,不同厂商的证件号码和性别
			$assured_info['certificates_type_unify'] = $attr_epicc_GovtIDTC[$assured_info['certificates_type']];
			$assured_info['gender_unify'] = $attr_sex_epicc_unify[$assured_info['gender']];
			$assured_info['type'] = 0;//0,被保险人，1投保人,2两者身份相同
			$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
			//end add by wangcya, 20141219 ,不同厂商的证件号码和性别
		}
		$list_assured1 = array();
		$list_assured1[] = $assured_info;
		$real_apply_num = 1;//被保险人时一个人
	}
	else//以下是机构投保方式的被保险人信息
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
		global $attr_certificates_type_epicc_single_unify;
		
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
			$assured_info['certificates_type_unify'] = $attr_certificates_type_epicc_single_unify[$assured_info['certificates_type']];
			$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
			//end add by wangcya, 20141219 ,不同厂商的证件号码和性别
			
			$real_apply_num++;//团单不少于5人得判断
			$list_assured1[] = $assured_info;
		}
	}
	$global_info['insured_num'] = $real_apply_num;

	$product_id = intval($POST['product_id']);
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

function gen_epicc_policy_xml($attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject)
{
	$generalInfo = gen_general_info($attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject);
	$policyInfos = gen_policyInfos($attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject);

	$xml = "<?xml version='1.0' encoding='GBK'?>
			<ApplyInfo>
			$generalInfo
			$policyInfos
			</ApplyInfo>";
	ss_log("gen_epicc_policy_xml xml:".$xml);
	return $xml;
}



//added by zhangxi, 20150507, 注销入口，
function withdraw_policy_epicc($policy_id,$user_info_applicant)
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
		$result_attr = post_withdraw_policy_epicc(	$policy_attr, $user_info_applicant );
	
	}
	else
	{
		$result = 110;
		$retmsg = "withdraw policy fail, policy_attr is null , policy_id:".$policy_id;
		ss_log(__FUNCTION__.", ".$retmsg);
		$result_attr = array(	'retcode'=>$result,
								'retmsg'=> $retmsg
							);
	}

	/////////////////////////////////////////////////////////////
	
	return $result_attr;
}

//注销保单
function post_withdraw_policy_epicc( $policy_attr, $user_info_applicant )
{
	global $_SGLOBAL, $java_class_name_epicc;
	global $LOGFILE_EPICC;
	
	//////////////////////////////////////////////////////
	$policy_id = $policy_attr['policy_id'];
	
	$POLICYNO 		= $policy_attr['policy_no'];
	$orderNum 		= $policy_attr['order_num'];
	$order_sn 		= $policy_attr['order_sn'];
	
	ss_log(__FUNCTION__.", will withdraw policy , POLICYNO: ".$POLICYNO." orderNum: ".$orderNum);
	ss_log(__FUNCTION__.", order_sn: ".$order_sn);
	

	$DATE = date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);//20140515
	
	//$ProposalNo = $policy_attr['cast_policy_no'];
	//渠道代码 $generalInfo['AgentCode']
	$agent_code = get_picc_constant('AgentCode');
	$content_unencrypted = $POLICYNO.$agent_code;
	//使用java提供的接口来加密吧
	$obj_java = create_java_obj($java_class_name_epicc);
	if(!$obj_java)
	{
		ss_log(__FUNCTION__.", error,  create java object error,name=".$java_class_name_epicc);
	}
	//由投保单号+渠道码，字符串拼接后，进行MD5加密
	$content_encrypted = (string)$obj_java->md5(
												$content_unencrypted,
												EPICC_SECRET_KEY,//投保秘钥
												$LOGFILE_EPICC
												);

	




	$result = 1;
	//保单注销报文
	$strxml = '<?xml version="1.0" encoding="GBK" standalone="yes"?>
			<PolicyEndorsement>
				<Header>			
					<BusinessSource>'.get_picc_constant('BusinessSource').'</BusinessSource>
					<ComCode>'.get_picc_constant('ComCode').'</ComCode>
					<MakeCode>'.get_picc_constant('MakeCode').'</MakeCode>
					<AgentCode>'.get_picc_constant('AgentCode').'</AgentCode>
					<Md5Value>'.$content_encrypted.'</Md5Value>
				</Header>
				<EndorseInfo>
					<PolicyNo>'.$POLICYNO.'</PolicyNo>
					<EndorseType>00</EndorseType>
					<EndorseDate>'.$DATE.'</EndorseDate>
					<EndorseEffectDate>'.$DATE.'</EndorseEffectDate>
				</EndorseInfo>
			</PolicyEndorsement>
			';

	if(!empty($strxml))
	{
		$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_epicc_policy_withdraw.xml";
		file_put_contents($af_path,$strxml);
		
		//start add by wangcya, 20150209,把携带序列号的也保存起来
		$UUID = $orderNum;
		$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_epicc_policy_withdraw.json";
		file_put_contents($af_path_serial,$strxml);
		//end add by wangcya, 20150209,把携带序列号的也保存起来
		if(!$obj_java)
		{
			$p = create_java_obj($java_class_name_epicc);
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
		
		$url = URL_EPICC_CANCEL_POLICY;
		$respXmlFileName = "";
		ss_log(__FUNCTION__."epicc, withdraw url: ".URL_EPICC_CANCEL_POLICY);
		//ss_log("keyFile: ".$keyFile);
		
		$return_data = (string)$p->cancel( 
											$url , 
											$strxml ,
											"",
											"",
											$LOGFILE_EPICC
											);

		ss_log("after epicc withdraw call function sendMessage");
		
		if(!empty($return_data))
		{
			
			//先保存一份
			$return_path = S_ROOT."xml/".$order_sn."_".$policy_id."_epicc_policy_withdraw_ret.xml";
			file_put_contents($return_path,$return_data);

			//////////////////////////////////////////////////////////
		}
		else
		{
			$result = 110;
			$retmsg = "post epicc withdraw policy return is null!";
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
			return $result_attr;
		}

		/////////先解析出来返回的xml//
		$withdraw_ret = parse_withdraw_xml_data_to_array_epicc($return_data);

		if( $withdraw_ret['ResponseCode'] =="00"&&////注销保单成功
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

function parse_withdraw_xml_data_to_array_epicc($return_data)
{
	ss_log(__FUNCTION__.", get in");
	$policy_ret = array();

	preg_match_all('/<PolicyNo>(.*)<\/PolicyNo>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['PolicyNo'] = trim($arr[1][0]);
	}

	preg_match_all('/<ResponseCode>(.*)<\/ResponseCode>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['ResponseCode'] = trim($arr[1][0]);
	}

	preg_match_all('/<ResponseMessage>(.*)<\/ResponseMessage>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['ResponseMessage'] = trim($arr[1][0]);
	}
	ss_log(__FUNCTION__.", policy_ret=".var_export($policy_ret, true));
	return $policy_ret;
}
//
function parse_xml_data_to_array_epicc($return_data)
{
	ss_log(__FUNCTION__.", get in");
	$policy_ret = array();
	preg_match_all('/<CommandId>(.*)<\/CommandId>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['CommandId'] = trim($arr[1][0]);
	}

	preg_match_all('/<ProposalNo>(.*)<\/ProposalNo>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['ProposalNo'] = trim($arr[1][0]);
	}

	preg_match_all('/<PolicyNo>(.*)<\/PolicyNo>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['PolicyNo'] = trim($arr[1][0]);
	}

	preg_match_all('/<ResponseCode>(.*)<\/ResponseCode>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['ResponseCode'] = trim($arr[1][0]);
	}

	preg_match_all('/<ResponseMessage>(.*)<\/ResponseMessage>/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['ResponseMessage'] = trim($arr[1][0]);
	}
	ss_log(__FUNCTION__.", policy_ret=".var_export($policy_ret, true));
	return $policy_ret;
}

function process_return_xml_data_epicc(
									 	$policy_id,
				                      	$orderNum,
										$order_sn,
										$return_data,
										$user_info_applicant,
										$return_type='insure',
										$obj_java = NULL
										)
{
	global $_SGLOBAL;
	
	ss_log("into function: ".__FUNCTION__);
	////////////////////////////////////////////////////////////////////////////
	ss_log(__FUNCTION__.", return org data: ".$return_data);
	
	$post_policy_ret = parse_xml_data_to_array_epicc($return_data);

	if($return_type == "insure"//是投保的返回处理
	  )
	{
		if($post_policy_ret['ResponseCode'] == "00" )//投保成功
		{
			ss_log(__FUNCTION__.", epicc投保成功");
			ss_log(__FUNCTION__.", return data: ".$return_data." order_sn: ".$order_sn);
			ss_log(__FUNCTION__.", order_sn: ".$order_sn);
			
			//存储保单号到数据库中
			//status,保单的状态：0 保存但是未提交；1 投保成功；2 注销了，注销后不能再次提交。
			$wheresql = "order_num='$orderNum'";//根据这个找到其对应的保单存放的数据
			$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$policy_attr = $_SGLOBAL['db']->fetch_array($query);
		
			if($policy_attr['policy_id'])//old
			{
				$policy_id = $policy_attr['policy_id'];
				ss_log(__FUNCTION__.", return policyNo: ".$post_policy_ret['PolicyNo']." order_sn: ".$order_sn);
				
				$policy_no = $post_policy_ret['PolicyNo'];
				
				//在这里更改该保单状态,存储保险公司返回的保单号，同时保存电子保单url到数据库中
				updatetable('insurance_policy',
								array('policy_status'=>'insured','policy_no'=>$policy_no),
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
				//开始获取电子保单
				ss_log(__FUNCTION__." policy_id: ".$policy_id);
				$policy_attr['policy_no'] = $policy_no;
				$pdf_filename = S_ROOT."xml/message/".$policy_no."_epicc_policy.pdf";
				
				$policy_attr['readfile'] = false;//
				//进行电子保单下载操作
				$use_asyn = true;
				return get_policy_file_epicc($use_asyn,
						                       $policy_attr,
						                       $user_info_applicant,
						                       $pdf_filename,
						                       $obj_java);
				
		
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
		
			

				
		}
		else
		{
			//核保失败处理
			$result = $post_policy_ret['ResponseCode'];
			$retmsg = $post_policy_ret['ResponseMessage'];
			ss_log(__FUNCTION__."[".__LINE__."], 承保失败，ResponseCode=".$post_policy_ret['ResponseCode']);
			
		}
		
		
	}
	else
	{
		//epicc投保返回异常
		$result = $post_policy_ret['ResponseCode'];
		$retmsg = $post_policy_ret['ResponseMessage'];
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


//获取电子保单函数
function get_policy_file_epicc($use_asyn,
			                       $policy_attr,
			                       $user_info_applicant,
			                       $pdf_filename,
			                       $obj_java = NULL
			                      )
{
	global $java_class_name_epicc;
	ss_log("into fuction: ".__FUNCTION__);
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
		$retmsg = "get policy file fail, policy_attr is null , policy_id:".$policy_attr['policy_id'];
		ss_log(__FUNCTION__.", ".$retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
	
		return $result_attr;
	}
	
	if(!$use_asyn)//同步处理接口
	{
		$java_class_name = $java_class_name_epicc;
	}
	else//异步接口
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_epicc:$java_class_name;
	
	ss_log(__FUNCTION__.", java_class_name: ".$java_class_name);
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
	$logFileName = S_ROOT."xml/log/epicc_download_policyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	
	//需要根据承保接口获取到的url来获取保单下载的url
	$applicant_identify = $user_info_applicant['certificates_code'];
	$encrypted_data = gen_encrypt_data_epicc($POLICYNO,$logFileName);
	$url = URL_EPICC_GET_POLICY_FILE;
	$url = $url."?policyNo=".$POLICYNO."&platfromcodes=".EPICC_PLAT_FROM_CODES."&password=".$encrypted_data;
	//jdk1.7.0_25
	ss_log(__FUNCTION__.", policy get url: ".$url);

	if(!$use_asyn)//同步的分支
	{
		
		ss_log(__FUNCTION__.", epicc will get policy file, POLICYNO: ".$POLICYNO." orderNum: ".$orderNum);
		ss_log(__FUNCTION__.", epicc order_sn: ".$order_sn);
		$logFileName = S_ROOT."xml/log/epicc_download_policyfile_java_syn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
		$ret = (string)$obj_java->downloadPolicy( 	$url,
													$pdf_filename,
													$logFileName
												);
	
		ss_log(__FUNCTION__.", applyElectricPolicyBill ret: ".$ret);
	
		if($ret==true)//下载电子保单，成功返回true，失败返回false
		{
			$result = 0;
			$retmsg = "获取电子保单成功";
		
			usleep(1);//wait for file cache,如果去掉这句话将得到的PDF不完整
			if (file_exists($pdf_filename))//如果这个文件已经存在，则返回给用户。
			{
				$readfile = $policy_attr['readfile'];
				
				$result = 0;
				$retmsg = "获取电子保单成功";
			
				ss_log(__FUNCTION__.", ".$retmsg);

				//start add by wangcya, 20150205,不直接退出，先更新状态//////////////////////////////////
				$setarr = array('ret_code'=>$result,'ret_msg'=>$retmsg,
						         'getepolicy_status'=>1//获取电子保单成功了
						         );
				updatetable('insurance_policy',
							$setarr	,
							array('policy_id'=>$policy_id)
							);
				if($readfile)//
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
	
		ss_log(__FUNCTION__.", ".$retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
	
		return $result_attr;
	}
	else//异步的分支
	{
		//////////////////////////////////////////
	
		ss_log(__FUNCTION__.", epicc将要发送获取电子保单的异步请求，will doService");
	
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
		ss_log(__FUNCTION__.", ".$retmsg);
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
	}
	
	return $result_attr;
	
}


//获取pdf格式的电子保单
function get_policyfile_epicc($pdf_filename,
		                      $policy_attr,
		                      $user_info_applicant
		                    )
{

	$use_asyn = false;//同步方式
	$result_attr = get_policy_file_epicc($use_asyn,$policy_attr,$pdf_filename);
	return $result_attr;
}



//人保财险投保
function post_policy_epicc(
							$attribute_type,//add by wangcya, 20141213
							$attribute_code,//add by wangcya, 20141213
							$policy_arr,
							$user_info_applicant,
							$list_subject
						   )
{
	global $_SGLOBAL, $java_class_name_epicc;
	/////////////////////////////////////////////////////////
	ss_log("into ".__FUNCTION__);
	
	$policy_id = $policy_arr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	
	$orderNum = $policy_arr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_arr['order_sn'];//add by wangcya, 20141023
	ss_log(__FUNCTION__.", list_subject=".var_export($list_subject,true));
	//生成需要进行承保的xml文件
	$strxml = gen_epicc_policy_xml(
									$attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject);//subject信息，被保险人信息也在这里面
	
	ss_log(__FUNCTION__.", after gen_epicc_policy_xml,strxml=".$strxml);

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
	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_epicc_policy_post.xml";
	file_put_contents($af_path,$strxml);//核保文件先存起来
	
	//start add by wangcya, 20150209,把携带序列号的也保存起来
	$UUID = $policy_arr['order_num'];
	$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_epicc_policy_post.xml";
	file_put_contents($af_path_serial,$strxml);
	//end add by wangcya, 20150209,把携带序列号的也保存起来
	
	if(!defined('USE_ASYN_JAVA'))
	{
		$java_class_name = $java_class_name_epicc;//新华人寿同步处理请求
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_epicc:$java_class_name;

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
	$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_epicc_policy_post_ret.xml";
	ss_log($ret_af_path);
	
	$url = URL_EPICC_POST_POLICY;//承保URL
	ss_log(__FUNCTION__."url: ".URL_EPICC_POST_POLICY);
	
	if(!defined('USE_ASYN_JAVA'))//同步投保处理流程
	{
		global $LOGFILE_EPICC;

		//进行核保调用处理
		ss_log(__FUNCTION__.", epicc will java insure");
		$strjson_check_policy_ret = $return_data = (string)$obj_java->insure(
																		$url,
																		$strxml ,//投保报文
																		"",
																		"",
																		$LOGFILE_EPICC
																		);

		ss_log(__FUNCTION__."after post call function sendMessage");

		//////////////////////////////////////////////////////////
		if(!empty($strjson_check_policy_ret))//投保返回报文
		{
			ss_log(__FUNCTION__."xinhua will process return_data");
		
			file_put_contents($ret_af_path, $strjson_check_policy_ret);
				
			//////////////////////////////////////////////////////////////////////////
			$result_attr = process_return_xml_data_epicc(
														 	$policy_id,
									                      	$orderNum,
															$order_sn,
															$return_data,
															$user_info_applicant,
															"insure",
															$obj_java
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
	else//异步投保情况
	{//USE_ASYN_JAVA
		//////////////////////////////////////////
		ss_log(__FUNCTION__.", 将要发送epicc核保的异步请求，will doService");
	
		$respXmlFileName = $ret_af_path;//承保返回报文存储路径
		$logFileName = S_ROOT."xml/log/epicc_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	
		$xmlContent = "";//$strxml_post_policy_content;
		$postXmlFileName = $af_path;
		$postXmlEncoding = "UTF-8";
		ss_log(__FUNCTION__.", logFileName: ".$logFileName);
		//异步的投保
		$type = "insure";
		$insurer_code = $policy_arr['insurer_code'];
		//投保之后的回调url
		$callBackURL = CALLBACK_URL;
					
		ss_log(__FUNCTION__.", policy_id: ".$policy_id);
		ss_log(__FUNCTION__.", type: ".$type);
		ss_log(__FUNCTION__.", insurer_code: ".$insurer_code);
		ss_log(__FUNCTION__.", callBackURL: ".$callBackURL);
			
		$param_other = array(
							"url"=>$url ,//投保保险公司服务地址
							"xmlContent"=>$xmlContent ,//核保请求报文
							"postXmlFileName"=>$postXmlFileName,//投保报文内容
							"postXmlFileEncoding"=>$postXmlEncoding,//投保报文编码
							"respXmlFileName"=>$respXmlFileName,//返回报文保存全路径
							"respXmlFileEncoding"=>"UTF-8",
							"logFileName"=>$logFileName//日志文件
						);
	
		ss_log(__FUNCTION__.", param_other: ".var_export($param_other,true));
		$jsonStr = json_encode($param_other);
		ss_log(__FUNCTION__.", jsonStr: ".$jsonStr);
		
		$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_epicc_policy_post_to_java.xml";
		file_put_contents($af_path,$strxml);
	
		//异步投保调用接口函数
		$successAccept = (string)$obj_java->doService(
											$insurer_code,//险种代码
											$type,//第一步是投保
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
//获取加密信息
function gen_encrypt_data_epicc($need_encrpyt_data,$logFileName)
{
	global $java_class_name_epicc;
	$obj_java = create_java_obj($java_class_name_epicc);
	$ret = (string)$obj_java->getEncryptStr( 	$need_encrpyt_data,
													EPICC_PUBLIC_KEY,
													$logFileName
												);
	return $ret;
	
}

function gen_general_info($attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject)
{
	
	global $java_class_name_epicc, $LOGFILE_EPICC;
	
	$generalInfo = array();
	$generalInfo['BusinessSource']=get_picc_constant('BusinessSource');; //业务来源(平台标识)由人保总公司下发。由分公司或者项目组通知对接方上传。
	
	//这个要设置
	if($policy_arr['business_type'] == 1)
	{
		$generalInfo['PolicyType']="01"; //01:个人保单   04：团体保单	
	}
	else if($policy_arr['business_type'] == 2)
	{
		$generalInfo['PolicyType']="04"; //01:个人保单   04：团体保单	
	}
	
	
	//start 由PICC提供
	$generalInfo['MakeCode']=get_picc_constant('MakeCode');
	$generalInfo['ComCode']=get_picc_constant('ComCode');
	$generalInfo['Handler1Code']=get_picc_constant('Handler1Code');
	$generalInfo['HandlerCode']=get_picc_constant('HandlerCode');
	$generalInfo['AgentCode']=get_picc_constant('AgentCode');//这个就是渠道码了
	$generalInfo['OperatorCode']=get_picc_constant('OperatorCode');
	//end 由PICC提供
	
	
	/*Md5Value:由投保单号+渠道码(如果是个单批量（团单不支持批量上送）上送则是由每份保单的 投保单号+渠道码拼接而成)，字符串拼接后，进行MD5加密。固定位数是32位。MD5密钥由PICC提供。测试环境密钥为Picc123456Ticket
		正式密钥在项目上线前会通知对接方
		例：
		投保单号：TEAK201431011006A00001
		渠道码：0555555
		则加密内容为：TEAK201431011006A000010555555
		加密密钥：Picc123456Ticket
		生成的加密串：160c4e0c2c8b1f367ce728bf3e9a0b3e
	*/
	
	//这里要处理
	//EPICC_SECRET_KEY ---秘钥
	
	//投保单号
	$ProposalNo = $policy_arr['cast_policy_no'];
	//渠道代码 $generalInfo['AgentCode']
	$content_unencrypted = $ProposalNo.$generalInfo['AgentCode'];
	//使用java提供的接口来加密吧
	$obj_java = create_java_obj($java_class_name_epicc);
	if(!$obj_java)
	{
		ss_log(__FUNCTION__.", error,  create java object error,name=".$java_class_name_epicc);
	}
	
	$content_encrypted = (string)$obj_java->md5(
												$content_unencrypted,
												EPICC_SECRET_KEY,//投保报文
												$LOGFILE_EPICC
												);
	
	
	$generalInfo['Md5Value']=$content_encrypted;//由投保单号+渠道码，字符串拼接后，进行MD5加密
	
	$xml="<GeneralInfo>";
	
	foreach ( $generalInfo as $key => $value ) {
       $xml.="<".$key.">".$value."</".$key.">";
	}
	$xml.="</GeneralInfo>";
	
	return $xml;
	
}

function gen_policyInfos($attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject)
{
	//	投保单/保单 信息 
	$main_xml = get_main_xml($attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject);
	
	// 自然人信息 
	$natureInfo_xml = get_natureInfo_xml($policy_arr,
										$user_info_applicant,//投保人信息
										$list_subject);

	$ExtendInfo = get_extend_Info($attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject);
	
	
	return "<PolicyInfos><PolicyInfo>".$main_xml.$natureInfo_xml.$ExtendInfo."</PolicyInfo></PolicyInfos>";
	
	
}

function get_main_xml($attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject)
{
	//产品代码应该配置来，需要改一下，现在放在了险种代码上
	//$riskCode = get_picc_constant('RiskCode');
	$riskCode = $attribute_code;
	//唯一，长度为12到22个字符，这里满足要求么？
	//要确认，订单号长度23位，太长
	//$main['CommandId']=$policy_arr['order_num'];
	$main['CommandId']= getRandOnly_Id(0);
	$main['CommandId'] = substr($main['CommandId'],0,22);
	
	//按要求组织的投保单号
	$main['ProposalNo']=$policy_arr['cast_policy_no']; /*投保单号,一张保单的惟一标志由对接方公司生成。
									生成规则：T+产品代码+年+归属机构+A+序列号。如：T DAA 2009 11026604 A00009.
									如果序列号大于99999则把英文字母A变为B序列号在从00001开累加以此类推
									*/
									
	$main['PolicyNo']=""; //保单号,必须为空	由电商系统生成并返回，对接方不需赋值该节点。
	
	if($policy_arr['business_type'] == 1)//个单
	{
		//根据具体情况设置本值
		$main['PolicyType']="01"; //保单类型01:个人保单 ，04：团体保单
	}
	else
	{
		//根据具体情况设置本值
		$main['PolicyType']="04"; //保单类型01:个人保单 ，04：团体保单
	}
	
	
	$main['RiskCode']=$riskCode;//产品代码
	$main['ComCode']=get_picc_constant('ComCode'); //机构代码
	
	//通过产品代码来设置
	$product_info = $list_subject[0]['list_subject_product'][0];
	ss_log(__FUNCTION__.", list_subject=".var_export($list_subject, true));
	ss_log(__FUNCTION__.", product_info=".var_export($product_info, TRUE));
	ss_log(__FUNCTION__.", attribute_type=".$attribute_type);
	//mod by zhangxi, 20150514, 方案代码，对于旅游险来说，跟旅游天数是有关系的
	if($attribute_type == 'lvyou'
	||$attribute_type == 'jingwailvyou')
	{
		if(isset($product_info['list_influencingfactor_id']))
		{
			$tmp_id = $product_info['list_influencingfactor_id'][0];
			$main['RationType'] =  $tmp_id['additional_code'];
			ss_log(__FUNCTION__.", get tmp_id=".$tmp_id);
		}
	}
	else
	{
		$main['RationType']=$product_info['product_code']; //方案代码
	}
	
	//单证识别码 可空，$main['VisaCode']="11027972";
	//打印流水号  可空，$main['PrintNo']="08027206";
	global $_SGLOBAL;

	//$main['OperateDate']=$policy_arr['start_date']; //签单日期 与录入日期一致
	$main['OperateDate'] = date("Y-m-d H:i:s",$policy_arr['dateline']);
	//$main['OperateDate']=date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);
	//保险起期日期和时间
	$s_time = strtotime($policy_arr['start_date']);	
	$main['StartDate']=date("Y-m-d",$s_time);
	$main['StartTime']="00:00";
	
	//保险止期日期和时间
	$e_time = strtotime($policy_arr['end_date']);	
	$main['EndDate']=date("Y-m-d",$e_time);
	$main['EndTime']="23:59";
	
	
	$main['JudicalScope']="中国境内（包含港、澳、台）"; /*司法管辖由PICC提供具体代码
										例：世界范围（包含美、加地区）
										世界范围（美、加地区除外）
										中国境内（包含港、澳、台）
										中国境内（港、澳、台除外）
										注：具体以实际情况为准				*/
										
	//争议解决方式，1诉讼，2仲裁
	$main['ArguSolution']="2";
	
	//录入时间
	$main['InputTime']=date("Y-m-d H:i:s",$policy_arr['dateline']);
	$main['ProposalNum']=$policy_arr['insured_num'];//投保人数,这个根据实际情况来设置
	
	$main['RationCount']=$policy_arr['apply_num']; //每人投保的分数
	
	//单人单份的保额
	$main['PersonalAmount']=$product_info['sum_insured'];
	
	//如果是旅游险，则需要取的具体的值
	//单人单份保费
	if($attribute_type == 'lvyou'
	||$attribute_type == 'jingwailvyou')
	{
		$tmp_id = $product_info['list_influencingfactor_id'][0];
		$main['PersonalPremium'] =  $tmp_id['factor_price'];
		ss_log(__FUNCTION__.", get tmp_id=".$tmp_id);
	}
	else
	{
		$main['PersonalPremium']=$product_info['premium'];
	}
	
	//EngageCode 这个不用填写
	
	//总保额=人数*份数*每人每份的保额
	$main['SumAmount']=$product_info['sum_insured']*$policy_arr['apply_num']*$policy_arr['insured_num'];
	//总保费 = 人数*份数*每人每份的保费
	$main['SumPremium']=$policy_arr['total_premium'];
	$main['PlanFee']=0; //预收保费，如果没有则传为0
	
	$xml="<Main>";
	
	foreach ( $main as $key => $value ) {
       $xml.="<".$key.">".$value."</".$key.">";
	}
	$xml.="</Main>";
	
	return $xml;
}
function get_extend_Info($attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject)
{
	$extendinfo = "";
	//如果是旅游险,如何区分境内，境外呢？
	if($attribute_type == 'jingwailvyou')
	{
		$extendinfo = '<ExtendInfo>
						<ExtendThird>'.$policy_arr['destinationCountry'].'</ExtendThird>
						</ExtendInfo>';
	}
	return $extendinfo;
}

function get_natureInfo_xml($policy_arr,
							$user_info_applicant,//投保人信息
							$list_subject)
{	
	//echo "<pre>";print_r($user_info_applicant);
	
	//投保人
	$applicant_arr = array();
	//1：被保险人，2：投保人，9：受益人
	$applicant_arr['InsuredFlag']=2;
	if($policy_arr['business_type'] == 1)//投保人是个人
	{
		$applicant_arr['InsuredName']=$user_info_applicant['fullname'];
	
		//01:身份证 02：户口簿 03：护照 04：军官证 05:驾驶执照 06：返乡证 07：港澳身份证 99：其它 
		$applicant_arr['IdentifyType']=$user_info_applicant['certificates_type'];
		$applicant_arr['IdentifyNumber']=$user_info_applicant['certificates_code'];
		$applicant_arr['InsuredAddress']=$user_info_applicant['address'];
		$applicant_arr['Mobile']=$user_info_applicant['mobiletelephone'];
		$applicant_arr['SendSMS']="N"; //是否发送短信，Y:发送N:不发送
		$applicant_arr['Email']=$user_info_applicant['email'];
		$applicant_arr['InsuredEName'] = $user_info_applicant['fullname_english'];
		$applicant_arr['BenefitRate']=1; //受益份额,不能为空，写死为1
		$applicant_arr['Sex'] = $user_info_applicant['gender'];
		$applicant_arr['Birthday'] = $user_info_applicant['birthday'];
		//added by zhangxi, 20150611, 增加年龄节点
		$applicant_arr['Age'] = get_age_by_birthday($user_info_applicant['birthday']);
		
	}
	else if($policy_arr['business_type'] == 2)//投保人是机构
	{
		$applicant_arr['InsuredName']=$user_info_applicant['group_name'];
	
		//01:身份证 02：户口簿 03：护照 04：军官证 05:驾驶执照 06：返乡证 07：港澳身份证 99：其它 
		$applicant_arr['IdentifyType']=$user_info_applicant['group_certificates_type'];
		$applicant_arr['IdentifyNumber']=$user_info_applicant['group_certificates_code'];
		$applicant_arr['InsuredAddress']=$user_info_applicant['address'];
		$applicant_arr['Mobile']=$user_info_applicant['mobiletelephone'];
		$applicant_arr['SendSMS']="N"; //是否发送短信，Y:发送N:不发送
		$applicant_arr['Email']=$user_info_applicant['email'];
		//$applicant_arr['InsuredEName'] = $user_info_applicant['fullname_english'];
		$applicant_arr['BenefitRate']=1; //受益份额,不能为空，写死为1
		//$applicant_arr['Sex'] = $user_info_applicant['gender'];
		//$applicant_arr['Birthday'] = $user_info_applicant['birthday'];
	}
	
	
	$applicant_xml="<ItemNature>";
	
	foreach ( $applicant_arr as $key => $value ) {
       $applicant_xml.="<".$key.">".$value."</".$key.">";
	}
	$applicant_xml.="</ItemNature>";
	
	ss_log("applicant_xml:".$applicant_xml);
	
	
	
	//被保险人
	$list_insurant = $list_subject[0]['list_subject_insurant'];	
	$insurant_xml = '';
	foreach($list_insurant as $key=>$insurant_info)
	{
		$insurant_arr = array();
		$insurant_arr['InsuredFlag']=1;//被保险人
		$insurant_arr['InsuredName']=$insurant_info['fullname'];
		
		//01:身份证 02：户口簿 03：护照 04：军官证 05:驾驶执照 06：返乡证 07：港澳身份证 99：其它 
		$insurant_arr['IdentifyType']=$insurant_info['certificates_type'];
		$insurant_arr['IdentifyNumber']=$insurant_info['certificates_code'];
		$insurant_arr['InsuredAddress']=$insurant_info['address'];
		$insurant_arr['Mobile']=$insurant_info['mobiletelephone'];
		$insurant_arr['SendSMS']="N"; //是否发送短信，Y:发送N:不发送
		$insurant_arr['Email']=$insurant_info['email'];
		$insurant_arr['InsuredEName'] = $insurant_info['fullname_english'];
		$insurant_arr['BenefitRate']=1; //受益份额,不能为空，写死为1
		$insurant_arr['Sex'] = $insurant_info['gender'];
		$insurant_arr['Birthday'] = $insurant_info['birthday'];
		//added by zhangxi, 20150611, 增加年龄节点
		$insurant_arr['Age'] = get_age_by_birthday($insurant_info['birthday']);
		
		$insurant_xml.="<ItemNature>";
		foreach ( $insurant_arr as $key => $value ) {
	       $insurant_xml.="<".$key.">".$value."</".$key.">";
		}	
		$insurant_xml.="</ItemNature>";	
	}
	
	
	ss_log(__FUNCTION__.", insurant_xml:".$insurant_xml);
	
	return "<NatureInfo>".$applicant_xml.$insurant_xml."</NatureInfo>";
	
}

//生成投保单号,P+险种+年限+归属地机构+A+5位流水号
function get_proposalNo()
{
	global $_SGLOBAL;
	$riskCode = get_picc_constant('RiskCode');//险种
	//获取上一个投保单号
	$sql = "SELECT MAX(policy_id) AS policy_id FROM t_insurance_policy WHERE insurer_code='EPICC'";
	$query = $_SGLOBAL['db']->query($sql);
	$max_policy_id = $_SGLOBAL['db']->fetch_array($query);
	$max_policy_id = $max_policy_id['policy_id'];
	ss_log(__FUNCTION__." max_policy_id:".$max_policy_id);
	
	
	$sql = "SELECT * FROM t_insurance_policy WHERE policy_id='$max_policy_id'";
	$query = $_SGLOBAL['db']->query($sql);
	$insurance_policy = $_SGLOBAL['db']->fetch_array($query);
	$last_proposalNo = $insurance_policy['cast_policy_no'];
	ss_log(__FUNCTION__." last_proposalNo:".$last_proposalNo);
	//年
	$year = date('Y');
	//归属地机构
	$comCode = get_picc_constant('ComCode');
	
	if($last_proposalNo)
	{
		//获取字母
		$letter = substr($last_proposalNo,-6,1);
		//获取5位流水号
		$number = substr($last_proposalNo,-5);
		if($number=='99999')
		{
			$letter = get_next_letter($letter);
			$number='00001';
		}
		else
		{
			$number = str_pad(++$number, 5, '0', STR_PAD_LEFT);
		}
		
	}
	else
	{
		$letter="A";
		$number='00001';
	}
	
	ss_log("riskCode:".$riskCode);
	ss_log("year:".$year);
	ss_log("comCode:".$comCode);
	ss_log("letter:".$letter);
	ss_log(" number:".$number);
	$newpProposalNo = "P".$riskCode.$year.$comCode.$letter.$number;
	ss_log(__FUNCTION__."newpProposalNo:".$newpProposalNo);
	return $newpProposalNo;
}

//获取PICC提供的常量
function get_picc_constant($key=''){
	$data  = array(
		'BusinessSource'=>"BZJ000037",
		'MakeCode'=>"11027972",//出单机构代码
		'ComCode'=>"11027972",//归属地
		'Handler1Code'=>"08027206",//归属业务员
		'HandlerCode'=>"08027206",//经办人
		'AgentCode'=>"110021100088",//渠道码
		'OperatorCode'=>"08027206",//操作员
		'RiskCode'=>"EJP"//产品代码
	);
	
	return $data[$key];
}

//获取下一个字母
function get_next_letter($letter)
{
	if($letter=='Z')
	{
		$next_letter="A";
	}
	else
	{
		$next_letter = ++$letter;//out C
	}
	return $next_letter;
}

$G_XLS_UPLOAD_FORMAT_EPICC_TRAFFIC_TUANDAN = array(
					'1'=>'assured_name',
					'2'=>'assured_certificates_type',//被保险人证件类型
					'3'=>'assured_certificates_code',//被保险人证件号码
					'4'=>'assured_birthday',
					'5'=>'assured_gender',//被保险人出生日期列
					'6'=>'assured_mobilephone',//手机
					'7'=>'assured_email',//email
					);
$G_XLS_UPLOAD_FORMAT_EPICC_TRAVELLING_TUANDAN = array(
					'1'=>'assured_name',
					'2'=>'assured_name_english',
					'3'=>'assured_certificates_type',//被保险人证件类型
					'4'=>'assured_certificates_code',//被保险人证件号码
					'5'=>'assured_birthday',
					'6'=>'assured_gender',//被保险人出生日期列
					'7'=>'assured_mobilephone',//手机
					'8'=>'assured_email',//email
					);

					
$G_XLS_UPLOAD_FORMAT_EPICC_TRAFFIC_PILIANG = array(
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
					
$G_XLS_UPLOAD_FORMAT_EPICC_TRAVELLING_PILIANG = array(
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
					'11'=>'assured_name_english',
					'12'=>'assured_certificates_type',//被保险人证件类型
					'13'=>'assured_certificates_code',//被保险人证件号码
					'14'=>'assured_birthday',//被保险人出生日期列
					'15'=>'assured_gender',//被保险人性别列
					'16'=>'assured_mobilephone',//被保险人手机列
					'17'=>'assured_email',//被保险人email列
					'18'=>'destination'//出访目的地国家，地区
					);
function process_file_xls_epicc_insured($uploadfile, $product)
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
	global $G_XLS_UPLOAD_FORMAT_EPICC_TRAFFIC_TUANDAN;
	global $G_XLS_UPLOAD_FORMAT_EPICC_TRAFFIC_PILIANG;
	global $G_XLS_UPLOAD_FORMAT_EPICC_TRAVELLING_PILIANG;
	global $G_XLS_UPLOAD_FORMAT_EPICC_TRAVELLING_TUANDAN;
	
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
			$result = process_file_xls_upload_common($uploadfile, $product, $G_XLS_UPLOAD_FORMAT_EPICC_TRAFFIC_TUANDAN,9);
		}
		//批量上传	
		elseif($commit_type == 'piliang')
		{
			$result = process_file_xls_upload_common($uploadfile, $product, $G_XLS_UPLOAD_FORMAT_EPICC_TRAFFIC_PILIANG);
		}
		elseif($commit_type == 'jingwai_piliang')
		{
			$result = process_file_xls_upload_common($uploadfile, $product, $G_XLS_UPLOAD_FORMAT_EPICC_TRAVELLING_PILIANG);
		}
		elseif($commit_type == 'jingwai_tuandan')
		{
			$result = process_file_xls_upload_common($uploadfile, $product, $G_XLS_UPLOAD_FORMAT_EPICC_TRAVELLING_TUANDAN,9);
		}
		else
		{
			ss_log(__FUNCTION__."commit_type=".$commit_type);
		}
	}
	
	
	return $result;
}
?>