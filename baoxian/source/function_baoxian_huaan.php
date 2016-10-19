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
$java_class_name_huaan = 'com.yanda.imsp.util.ins.SinosafePolicyFecher';
$LOGFILE_HUAAN=S_ROOT."log/java_huaan_post_policy.log";
////////////////////////////////////////////////////////////////////////////
$attr_school_type_huaan = array(
						"高等院校",
						"专科学校",
						"中等学校",
						"小学",
						"幼儿园"
						);
$attr_relationship_with_insured_huaan_xueping = array(
		"601005"=>"本人",
		"601003"=>"父母",
		"601017"=>"祖父母或外祖父母",
		"601019"=>"监护人",
);

//与被保人关系
$attr_relationship_with_insured_huaan = array(
		"601001"=>"雇佣",
		"601002"=>"子女",
		"601003"=>"父母",
		"601004"=>"配偶",
		"601005"=>"本人",
		"601012"=>"兄弟姐妹",
		"601017"=>"祖父母或外祖父母",
		"601018"=>"祖孙或外祖孙",
		"601019"=>"监护人",
);


//<!--证件类型，
$attr_certificates_type_huaan = array(
		"120001"=>"身份证",
		"120002"=>"军官证",
		"120005"=>"护照",
		"120006"=>"港澳返乡证",
		"120010"=>"士兵证",
		"120011"=>"组织机构代码证",
		"120014"=>"其他",
);
$attr_certificates_type_huaan_xueping_insurant = array(
		"120001"=>"身份证",
		//"120015"=>"出生日期",//这个还没有代码
		"120014"=>"其他",
);
//学平险被保险人证件类型
$attr_certificates_type_huaan_xueping = array(
		"120001"=>"身份证",
		"120002"=>"军官证",
		"120005"=>"护照",
		"120006"=>"港澳返乡证",
		"120010"=>"士兵证",
		"120014"=>"其他",
);


$attr_certificates_type_huaan_single_unify = array(
		"120001"=>1,//'身份证';
		"120005"=>4,//'护照';
		"120006"=>5,//'港澳回乡证或台胞证';
		"120002"=>8,//'军人证';
		"120014"=>9,//'其他';
);

//施工方类型
//413001	工程所有人413002	工程承包人413005	其他关系方
$attr_assured_type_huaan = array(
		"413001"=>"工程所有人",
		"413002"=>"工程承包人",
		"413005"=>"其他关系方",
);

//工程类型
$attr_project_type_huaan=array(
		"室内装饰装修工程",
		//"其他工程"		
);

//境内，境外
$attr_nationality_huaan=array(
	"193001"=>"境内",
	"193002"=>"境外"
);

//<!--投保人证件类型

$attr_applicant_type_huaan = array(
		"120001"=>"身份证",
		"120011"=>"组织机构代码证",//这个类型在接口文档中没有，但在测试样例报文中有，先加上
		"120014"=>"其他"
);
//added by zhangxi, 20150319, 机构的证件类型
$attr_group_applicant_type_huaan = array(
		"120011"=>"组织机构代码证",//这个类型在接口文档中没有，但在测试样例报文中有，先加上
		"120014"=>"其他"
);
$attr_applicant_gender_huaan = array(
		"0"=>"男",
		"1"=>"女"
);

//是否是被保险人
$attr_be_assured = array(
		"0"=>"否",
		"1"=>"是"
);

$attr_cost_limit = array(
			"20万以内"=>"0,20",
			"20(含)-50万"=>"20,50",
			"50(含)-100万"=>"50,100",
			"100(含)-200万"=>"100,200",
			);
			
$attr_period_limit = array(
							"2个月内"=>"2",
							"小于3个月"=>"3",
							"3个月内"=>"3",
							"小于4个月"=>"4",
							"4个月内"=>"4",
							"小于5个月"=>"5",
							"5个月内"=>"5",
							"小于6个月"=>"6"
							);
							
$attr_sex_huaan_unify = array("1"=>"F",
		"0"=>"M",
);

////////////////////////////////////////////////////////////////////////////////


//检查华安的输入的函数,同时又返回了需要传递的
function input_check_huaan($attribute_type, $POST,$insurer_code)
{
	global $_SGLOBAL;
	global $attr_sex_huaan_unify;
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
	if($attribute_type == 'project')
	{
		//工程信息检查
		if(!isset($POST['project_name'])||
			!isset($POST['project_price'])||
			!isset($POST['project_start_date'])||
			!isset($POST['project_end_date'])||
			!isset($POST['project_content'])||
			!isset($POST['project_location'])||
			!isset($POST['project_type']))
		{
			showmessage("您输入的工程信息不完整！");
			exit(0);
		}
	}
		
	//被保险人信息检查
	
	$businessType = $POST['applicant_type'];
	
	//全局的投保信息
	$global_info['applyNum'] = intval($POST['applyNum']);//份数
	$global_info['totalModalPremium'] = $POST['totalModalPremium'];//总保费,应该从前端计算得到的
	//ss_log("totalModalPremium: ".$totalModalPremium);
	$global_info['beneficiary'] = $POST['beneficiary'];//受益人
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期

	//////投保人信息/////////
	$user_info_applicant = array();
	if($businessType == 1)//投保人是个人
	{
		$user_info_applicant['certificates_type'] = trim($POST['applicant_certificates_type']);
		$user_info_applicant['certificates_code'] = trim($POST['applicant_certificates_code']);
		$user_info_applicant['fullname'] = $POST['applicant_fullname'];
		$user_info_applicant['gender'] = trim($POST['applicant_sex']);
		$user_info_applicant['gender_unify'] = $attr_sex_huaan_unify[$user_info_applicant['gender']];
		$user_info_applicant['birthday'] = $POST['applicant_birthday'];
		$user_info_applicant['mobiletelephone'] = $POST['applicant_mobilephone'];
		$user_info_applicant['email'] = $POST['applicant_email'];
		$user_info_applicant['business_type'] = $POST['business_type'];//投保人职业，不要了
		$user_info_applicant['zipcode'] = $POST['zipcode'];//不要了
		$user_info_applicant['address'] = isset($POST['applicant_address'])?$POST['applicant_address']:'';//
		
		//投保人国籍存储
		$user_info_applicant['nation_code'] = $POST['nation_code'];
			
	}
	else//机构投保
	{
		//投保人信息获取
		$user_info_applicant['group_name'] = $POST['applicant_fullname'];
		$user_info_applicant['group_certificates_type'] = $POST['applicant_certificates_type'];
		$user_info_applicant['group_certificates_code'] = $POST['applicant_certificates_code'];
		
		//投保人国籍存储，建工险需要
		$user_info_applicant['nation_code'] = $POST['nation_code'];
		
		//$user_info_applicant['group_abbr'] = $POST['group_abbr'];
		//$user_info_applicant['company_attribute'] = $POST['company_attribute'];
		$user_info_applicant['address'] = $POST['applicant_address'];//华安建工险不需要，学平险需要
		//$user_info_applicant['telephone'] = $POST['telephone'];
		$user_info_applicant['mobiletelephone'] = $POST['applicant_mobilephone'];
		$user_info_applicant['email'] = $POST['applicant_email'];
		$user_info_applicant['zipcode'] = $POST['zipcode'];//不要了
		//机构需要填写固定电话
		$user_info_applicant['telephone'] = $POST['telephone'];//建工险需要，学平险不需要
		//学平险中的学校类型,使用school_name字段来存放
		$user_info_applicant['school_type'] = $POST['school_type'];//学平险需要，建工险不需要
		
		//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		//$user_info_applicant['group_certificates_type_unify'] = $attr_certificates_type_pingan_group_unify[$user_info_applicant['group_certificates_type']];
		//$user_info_applicant['agent_uid'] = $agent_uid;
		//end add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
		
		
	}
		
	

	///下面是被保险人信息//////////////////////////////////////////////////////
	if($attribute_type == 'project')//工程一切险
	{
		//关系是本人
		$relationshipWithInsured = 601005;
		//获取被保险人信息，可能多个被保险人
		//通过数组传递过来
		//邮编是否必填？
		$data_user = stripslashes($POST['assured_info']);
		if(empty($data_user))
		{
			showmessage("获取被保险人信息失败！");
			exit(0);
		}
		ss_log("get post data:".$POST['assured_info']);
		//include_once(S_ROOT.'/../includes/shopex_json.php');
		
		$list_user = json_decode($data_user, true);
		ss_log("json decode data:".$list_user);
		$list_assured1 = array();
		//获取被保险人信息，这里需要考虑多个被保险人信息
		$real_apply_num = 0;
		foreach($list_user as $k=>$val)
		{
			$assured_info['certificates_type'] = trim($val['certificates_type']);
			$assured_info['certificates_code'] = trim($val['certificates_code']);
			//$assured_info['birthday'] = trim($val['birthday']);
			$assured_info['fullname']  = trim($val['fullname']);
			//$assured_info['gender']  = trim($val['gender']);
			$assured_info['mobiletelephone'] =  trim($val['assured_mobilephone']);//工程关系方手机号
			//$assured_info['email'] =  trim($val['assured_email']);
			//$assured_info['business_type'] =  trim($val['business_type']);//职业
			$assured_info['address'] =  trim($val['adress']);//地址
			$assured_info['insurant_type'] = trim($val['assured_type']);
			$assured_info['insurant_flag'] = trim($val['be_assured']);
			
	
			//add by wangcya, 20141219 ,不同厂商的证件号码和性别
			//$assured_info['certificates_type_unify'] = $attr_certificates_type_pingan_single_unify[$assured_info['certificates_type']];
			//$assured_info['gender_unify'] = $attr_sex_pingan_unify[$assured_info['gender']];
			//$assured_info['type'] = 0;//0,被保险人，1投保人,2两者身份相同
			$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
	
			
			$real_apply_num++;
			$list_assured1[] = $assured_info;
		}
		
		//获取工程信息
		$global_info['project_name']=$POST['project_name'];//必填
		$global_info['project_price']=$POST['project_price'];//工程造价必填
		$global_info['project_start_date']=$POST['project_start_date'];//必填
		$global_info['project_end_date']=$POST['project_end_date'];//必填
		$global_info['project_total_month']=$POST['project_total_month'];
		$global_info['project_content']=$POST['project_content'];//工程内容
		$global_info['project_location']=$POST['project_location'];//施工地点
		$global_info['zipcode']=$POST['project_zipcode'];
		$global_info['project_type']=$POST['project_type'];
		$global_info['province_name']=$POST['province_name'];
		$global_info['city_name']=$POST['city_name'];
		$duty_price_ids = $_POST['duty_price_ids'];
		
		if(!isset($duty_price_ids))
		{
			showmessage("投保产品信息缺失！");
			exit(0);
		}
		$list_duty_price_id = explode(',', $duty_price_ids);
			
	}
	elseif($attribute_type == 'xuepingxian')
	{
		if($businessType == 1)//投保人是个人
		{
			$assured_post = $POST['assured'][0];
		
			$assured_info['certificates_type'] = trim($assured_post['assured_certificates_type']);
			$assured_info['certificates_code'] = trim($assured_post['assured_certificates_code']);
			$assured_info['birthday'] = trim($assured_post['assured_birthday']);
			$assured_info['fullname']  = trim($assured_post['assured_fullname']);
			$assured_info['gender']  = trim($assured_post['assured_sex']);
			$assured_info['gender_unify'] = $attr_sex_huaan_unify[$assured_info['gender']];
			$assured_info['mobiletelephone'] =  trim($assured_post['assured_mobilephone']);//工程关系方手机号
			//$assured_info['email'] =  trim($assured_post['assured_email']);
			$assured_info['school_name'] =  trim($assured_post['assured_school']);//学校地址
			$assured_info['address'] =  trim($assured_post['assured_address']);//地址
			//$assured_info['insurant_type'] = trim($assured_post['assured_type']);
			
			$relationshipWithInsured = $POST['relationshipWithInsured'];
			$list_assured1[] = $assured_info;
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
			global $attr_certificates_type_huaan_single_unify;
			$real_apply_num = 0;
			foreach($list_user as $k=>$val)
			{
				//证件类型需要转换为保险公司相关代码
				$assured_info['certificates_type'] = trim($val['assured_certificates_type']);
				$attr_certificates_type = $global_attr_obj_type[$insurer_code]['attr_certificates_type'];
				$assured_info['certificates_type'] = array_search($assured_info['certificates_type'], $attr_certificates_type);
				$assured_info['certificates_code'] = trim($val['assured_certificates_code']);
				$assured_info['birthday'] = trim($val['assured_birthday']);
				$assured_info['fullname']  = trim($val['assured_name']);
				//$assured_info['gender']  = trim($val['gender']);
				//$assured_info['mobiletelephone'] =  trim($val['mobiletelephone']);
				//$assured_info['email'] =  trim($val['email']);
				
				//add by wangcya, 20141219 ,不同厂商的证件号码和性别
				$assured_info['certificates_type_unify'] = $attr_certificates_type_huaan_single_unify[$assured_info['certificates_type']];
				//$assured_info['gender_unify'] = $attr_sex_pingan_unify[$assured_info['gender']];
				$assured_info['agent_uid'] = $_SGLOBAL['supe_uid'];
				//end add by wangcya, 20141219 ,不同厂商的证件号码和性别
				//ss_log("zhx,name=".$assured_info['fullname']);
				
				$real_apply_num++;
				$list_assured1[] = $assured_info;
			}
		}
		
	}
	

	$product_id = intval($POST['product_id']);
	
	//一个subjectInfo内多个产品，多个被保险人
	$subjectInfo1 = array();
	$list_product_id1 = array();
	$list_product_id1[] = $product_id;//
	$subjectInfo1['list_product_id'] = $list_product_id1;

	$subjectInfo1['list_assured'] = $list_assured1;
	$subjectInfo1['list_duty_price_id'] = $list_duty_price_id;


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

//得到华安的工程造价，建设周期等配置信息
function get_huaan_project_cost_peroid($product_id)
{
	global $_SGLOBAL;

	$sql="SELECT DISTINCT product_influencingfactor_id,factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
			WHERE pi.product_influencingfactor_type='career' AND pi.product_id='$product_id' order by view_order";
	$query = $_SGLOBAL['db']->query($sql);

	$arr_project_list = array();
	while ($row = $_SGLOBAL['db']->fetch_array($query))
	{
		$arr_period_list = array();
		//得到每个工程的周期列表
		$factor_code = $row['factor_code'];
		$sql1="SELECT DISTINCT product_influencingfactor_id,factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
			WHERE pi.product_influencingfactor_type='period'" .
					"AND pi.factor_code='$factor_code' AND pi.product_id='$product_id' order by view_order";
		$query1 = $_SGLOBAL['db']->query($sql1);
		while($row_period = $_SGLOBAL['db']->fetch_array($query1))
		{
			$arr_period_list[]=$row_period;
		}
		
		$arr_project_list[] = array('cost'=>$row,
									'period'=>$arr_period_list,
									);
		
	}
	return $arr_project_list;
}

function get_duty_price_by_id($duty_price_list)
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
		//mod by zhangxi, 20150109, for huaan 投保单勾选位置的bug
		$product_row['amount'] = substr($product_row['amount'] ,0,strrpos($product_row['amount'],','));
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


//进行华安各险种的投保动作
function post_policy_huaan(
							$attribute_type,//add by wangcya, 20141213
							$attribute_code,//add by wangcya, 20141213
							$policy_arr,
							$user_info_applicant,
							$list_subject
						   )
{
	global $_SGLOBAL, $java_class_name_huaan;
	/////////////////////////////////////////////////////////
	ss_log("into ".__FUNCTION__);
	
	$policy_id = $policy_arr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	
	$orderNum = $policy_arr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_arr['order_sn'];//add by wangcya, 20141023
	
	$strxml = gen_huaan_policy_xml(
									$attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject);//subject信息，被保险人信息也在这里面
	//echo $strxml;
	//exit(0);

	$strxml = trim($strxml);
	//iconv('UTF-8', 'GKB', $requestParam);
	//$strxml =  mb_convert_encoding($strxml, "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
	//print $strxml;
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
	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_huaan_policy_post.xml";
	file_put_contents($af_path,$strxml);//zhx ,生成准备投保的XML文件
	
	//start add by wangcya, 20150209,把携带序列号的也保存起来
	$UUID = $policy_arr['order_num'];
	$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_huaan_policy_post.xml";
	file_put_contents($af_path_serial,$strxml);
	//end add by wangcya, 20150209,把携带序列号的也保存起来
	
	//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	if(!defined('USE_ASYN_JAVA'))
	{
		$java_class_name = $java_class_name_huaan;
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_huaan:$java_class_name;
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
	$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_huaan_policy_post_ret.xml";
	ss_log($ret_af_path);
	
	if($attribute_type == 'project')
	{
		$url = URL_HUAAN_POST_POLICY;
		$insuerEncoding = "GBK";
		$respXmlFileEncoding="GBK";
	}
	else
	{
		$url = URL_HUAAN_XUEPINGXIAN_POST_POLICY;
		$insuerEncoding = "UTF-8";
		$respXmlFileEncoding="UTF-8";
	}
	
	ss_log(__FUNCTION__."url: ".$url);
	
	if(!defined('USE_ASYN_JAVA'))//同步投保流程
	{
		global $LOGFILE_HUAAN;
		
		//这里需要指定华安产品的投保接口
		
		$respXmlFileName = "";
		
		//进行投保的工作
		ss_log("huaan will java applyPolicy");
		
		$strxml_post_policy_ret = $return_data = (string)$obj_java->applyPolicy(
																	$url,
																	$strxml ,
																	$insuerEncoding,
																	$LOGFILE_HUAAN
															);

		ss_log("after post call function sendMessage");

		//////////////////////////////////////////////////////////
		if(!empty($strxml_post_policy_ret))
		{
			ss_log("taipingyang will process return_data");
		
			file_put_contents($ret_af_path,$strxml_post_policy_ret);
				
			//////////////////////////////////////////////////////////////////////////
			$result_attr = process_return_xml_data_huaan(
									 	$policy_id,
				                      	$orderNum,
										$order_sn,
										$return_data,
										$user_info_applicant
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
		ss_log(__FUNCTION__."将要发送huaan投保的异步请求，will doService");
	
		$xmlContent = "";//$strxml_post_policy_content;
		$postXmlFileName = $af_path;
		$postXmlEncoding = "UTF-8";
		$respXmlFileName = $ret_af_path;
		$logFileName = S_ROOT."xml/log/huaan_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	
		ss_log("logFileName: ".$logFileName);
	
		$type = "insure";
		$insurer_code = $policy_arr['insurer_code'];
		//投保之后的回调url
		$callBackURL = CALLBACK_URL;
					
		ss_log("policy_id: ".$policy_id);
		ss_log("type: ".$type);
		ss_log("insurer_code: ".$insurer_code);
		ss_log(__FUNCTION__."callBackURL: ".$callBackURL);
	
		$keyFile ="";
		$port ="";
			
			
		$param_other = array(
				"url"=>$url ,//投保保险公司服务地址
				"port"=>$port ,//端口
				"insuerEncoding"=>$insuerEncoding,
				"xmlContent"=>$xmlContent ,//投保报文内容
				"postXmlFileName"=>$postXmlFileName,//投保报文文件
				"postXmlEncoding"=>$postXmlEncoding ,//投保报文文件编码格式
				"respXmlFileName"=>$respXmlFileName,//返回报文保存全路径
				"respXmlFileEncoding"=>$respXmlFileEncoding,
				"logFileName"=>$logFileName//日志文件
			);
	
		ss_log("param_other: ".var_export($param_other,true));
	
		$jsonStr = json_encode($param_other);
	
		//异步投保调用接口函数
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
	/////////////////////////////////////////////////////////////
	return array('retcode'=>$result,
				'retmsg'=> $retmsg
				);
}

function process_return_xml_data_huaan(
									 	$policy_id,
				                      	$orderNum,
										$order_sn,
										$return_data,
										$user_info_applicant
										)
{
	global $_SGLOBAL;
	
	ss_log("into function: ".__FUNCTION__);
	////////////////////////////////////////////////////////////////////////////
	//以下要针对华安的做处理
	/////////先解析出来返回的xml//////////////////////////////////
	//这里要等于order_num号才行
	preg_match_all('/<SVCSEQNO>(.*)<\/SVCSEQNO>/isU',$return_data,$arr);
	$extseqno = trim($arr[1][0]);
	
	//返回状态码
	preg_match_all('/<RESPONSECODE>(.*)<\/RESPONSECODE>/isU',$return_data,$arr);
	$HUAAN_RSLT_CODE = trim($arr[1][0]);
	//返回状态消息
	preg_match_all('/<ERRORMESSAGE>(.*)<\/ERRORMESSAGE>/isU',$return_data,$arr);
	$HUAAN_RSLT_MESG = trim($arr[1][0]);
	//保险公司返回的保单号，需要存储起来
	preg_match_all('/<C_PLY_NO1>(.*)<\/C_PLY_NO1>/isU',$return_data,$arr);
	$POLICYNO = trim($arr[1][0]);
	
	//保险公司返回的保单号，需要存储起来
	preg_match_all('/<C_PLY_NO2>(.*)<\/C_PLY_NO2>/isU',$return_data,$arr);
	$POLICYNO2 = trim($arr[1][0]);
	
	//获取返回的总保费
	preg_match_all('/<SUMPREMIUM>(.*)<\/SUMPREMIUM>/isU',$return_data,$arr);
	$totalpremium = trim($arr[1][0]);
	
	//只有学平险有，跟建工险做区分，就知道期编码格式了
	preg_match_all('/<SYSCODE>(.*)<\/SYSCODE>/isU',$return_data,$arr);
	$SYSCODE = trim($arr[1][0]);
	
	
	//$PA_RSLT_MESG = iconv('GB2312', 'UTF-8', $PA_RSLT_MESG); //将字符串的编码从GB2312转到UTF-8
	
	ss_log("HUAAN_RSLT_CODE: ".$HUAAN_RSLT_CODE." HUAAN_RSLT_MESG: ".$HUAAN_RSLT_MESG);
	
	/////////下载电子保单/////////////////////////////
	
	if(($HUAAN_RSLT_CODE=="1001" || $HUAAN_RSLT_CODE=='C00000000')&&////生成保单成功
		$extseqno== $orderNum//并且这个要和订单号相等
	  )
	{
		ss_log("huaan投保成功");
		ss_log("return code: ".$HUAAN_RSLT_CODE." ordernum: ".$orderNum);
		ss_log("order_sn: ".$order_sn);
		$result = 0;
			
		//status,保单的状态：0 保存但是未提交；1 投保成功；2 注销了，注销后不能再次提交。
		$wheresql = "order_num='$orderNum'";//根据这个找到其对应的保单存放的数据
		$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$policy_attr = $_SGLOBAL['db']->fetch_array($query);
	
		if($policy_attr['policy_id'])//old
		{
			$policy_id = $policy_attr['policy_id'];
			ss_log("return policyNo: ".$POLICYNO." order_sn: ".$order_sn);
	
			//在这里更改该保单状态,存储保险公司返回的保单号
			updatetable('insurance_policy',
							array('policy_status'=>'insured','policy_no'=>$POLICYNO),
							array('policy_id'=>$policy_id)
							);
			
			//start add by wangcya, 20150325,投保成功，增加对应的订单上的投保成功的个数///////////////////
			//global $_SGLOBAL;
			
			$sql = "UPDATE bx_order_info SET insured_policy_num=insured_policy_num+1 WHERE order_sn=".$order_sn;
			ss_log($sql);
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
	
		
		ss_log("将要异步获取电子保单, policy_id: ".$policy_id);
		//start add by wangcya, 20150204
		$policy_attr['policy_status'] = 'insured';//更改投保成功标示
		$policy_attr['policy_no'] = $POLICYNO;
		
		$pdf_filename = S_ROOT."xml/message/".$POLICYNO."_huaan_policy.pdf";
		
		$policy_attr['readfile'] = false;//add by wangcya, 20150207
		$use_asyn = true;
		$result_attr = get_policy_file_huaan($use_asyn,$policy_attr,$user_info_applicant,$pdf_filename);
		//end add by wangcya, 20150204
		
	}//生成保单成功
	else
	{
		//华安投保失败
		$result = $HUAAN_RSLT_CODE;
		$retmsg = $HUAAN_RSLT_MESG;
		ss_log(__FUNCTION__."post gen policy fail! return msg: ".$HUAAN_RSLT_MESG);
		
		ss_log(__FUNCTION__."before tranfer,将要保存保单的返回信息！".$retmsg );
		
		if(!empty($SYSCODE) && $SYSCODE == 'ZHONGTIANXINHE')
		{
			;//本来就是UTF-8，不用进行转换
		}
		else
		{
			//下层的java都进行了处理，是好像都是GBK的
			;//$retmsg =  mb_convert_encoding($retmsg, "UTF-8","GBK" ); //已知原编码为GBK, 转换为UTF-8
		
		}
		
		ss_log(__FUNCTION__."after tranfer,将要保存保单的返回信息！".$retmsg );
		
	}
	//echo $strxml;	
	
	$result_attr = array('retcode'=>$result,
			'retmsg'=> $retmsg,
			'policy_no'=>$POLICYNO//add by wangcya , 20150107, 一定要把这个电子保单号返回
	);
	
	return $result_attr;
	
}


function get_policy_file_huaan($use_asyn,
		                       $policy_attr,
							   $user_info_applicant,
		                       $pdf_filename
		                      )
{
	global $LOGFILE_HUAAN, $java_class_name_huaan;
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
		$java_class_name = $java_class_name_huaan;
	}
	else//异步接口
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_huaan:$java_class_name;
	
	ss_log(__FUNCTION__.",java_class_name: ".$java_class_name);
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
	
	ss_log(__FUNCTION__.":pdfFile: ".$pdf_filename);
	$logFileName = S_ROOT."xml/log/huaan_download_policyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	
	$url = URL_HUAAN_GET_POLICY_FILE;
	//jdk1.7.0_25
	ss_log(__FUNCTION__."policy get url: ".$url);
	if($business_type == 1)
	{
		$certificates_code = $user_info_applicant['certificates_code'];
	}
	else
	{
		$certificates_code = $user_info_applicant['group_certificates_code'];
	}
	
	if(!$use_asyn)//同步的分支
	{
		
		ss_log(__FUNCTION__.",huaan will get policy file, POLICYNO: ".$POLICYNO." orderNum: ".$orderNum);
		ss_log(__FUNCTION__.",huaan order_sn: ".$order_sn);
		
		$ret = (string)$obj_java->downLoadPolicy( 	$url,
													$policy_no,
													$certificates_code,//$insurant_certificate,
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
				//end add by wangcya, 20150205,不直接退出，先更新状态////////////////////////////////////
				
				if($readfile)
				{		
					//add by dingchaoyang 2014-12-4 如果是手机端访问，不输出文件，直接返回文件路径
// 					include_once (str_replace('baoxian','',S_ROOT)  . 'api/EBaoApp/platformEnvironment.class.php');
// 					if (!(PlatformEnvironment::isMobilePlatform())){
						header('Content-type: application/pdf');
						readfile($pdf_filename);
						exit(0);
// 					}
					//end by dingchaoyang 2014-12-4 如果是手机端访问，不输出文件，直接返回文件路径
				}		

				$result_attr = array(	'retcode'=>$result,
										'retmsg'=> $retmsg,
										'retFileName'=>str_replace(S_ROOT,'',$pdf_filename)
										);
				//add by dingchaoyang 2014-12-4添加retfilename
		
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
	
		ss_log(__FUNCTION__."将要发送获取电子保单的异步请求，will doService");
	
		$insurer_code = $policy_attr['insurer_code'];
		$callBackURL = CALLBACK_URL;
		$type = "getpolicyfile";
	
		ss_log("insurer_code: ".$insurer_code);
		ss_log("callBackURL: ".$callBackURL);
		ss_log("type: ".$type);
		ss_log("policy_id: ".$policy_id);
		//////////////////////////////////////////////////////////////////////////
	
	
		/*华安下载电子保单参数json部分key：
		private String url;
		private String logFileName;
		private String policyNo;
		private String idCard;//投保人身份证号 
		private String pdfFileName;
		*/
	
		ss_log("logFileName: ".$logFileName);
		$isDev = "Y";
	
	
		$param_other = array(
				"url"=>$url ,//投保保险公司服务地址
				"policyNo"=>$policy_no ,//投保单号
				"idCard"=>$certificates_code,//投保人身份证号 投保人身份证号 
				"pdfFileName"=>$pdf_filename ,//投保报文文件编码格式
				"logFileName"=>$logFileName, //日志文件
				"isDev"=>$isDev//
		);
	
		$jsonStr = json_encode($param_other);
	
		ss_log(__FUNCTION__.",param_other: ".var_export($param_other,true));
	
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
	}
	
	return $result_attr;
	
}
//获取pdf格式的电子保单
function get_policyfile_huaan($pdf_filename,
		                      $policy_attr,
		                      $user_info_applicant
		                    )
{

	$use_asyn = false;//使用同步下载
	$result_attr = get_policy_file_huaan($use_asyn,$policy_attr,$user_info_applicant,$pdf_filename);
	return $result_attr;
}


////////////////////////////////////////////////////////////////////////////////

//得到pdf格式的电子保单
function java_get_huaan_policy_file(  $url,
		$POLICYNO,
		$VALIDATECODE,
		$orderNum,
		$path,
		$business_type,
		$b_electronic_policy//是否共保
)
{

	global $java_class_name_pingan;
	/////////////////////////////////////////////////////////////////
	
	//jdk1.7.0_25
	ss_log('POLICYNO: '.$POLICYNO.' VALIDATECODE: '.$VALIDATECODE.' orderNum: '.$orderNum);
	ss_log("policy save path: ".$path);
	ss_log("policy get url: ".$url);

	/*1.调整接口，新添加的isSeperated参数，isSeperated 为空时，是空白字符串，不是null
	 取值：single/group/[null]         如果是个单，填写空；如果是团单团打，填写group；如果是团单个单，填写single
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
	
	/////////////////////////////////////////////////////////////////////
	$keyFile = S_ROOT.CLIENT_KEY_PINGAN;
	$pdfFile = $path;
	$logFile = "";
	
	if($b_electronic_policy)
	{
		$electronicPolicy = "elecpy2";
	}
	else
	{
		$electronicPolicy = "";//elecpy2 add by wangcya,20141023
	}
	
	//$url = "http://epcis-ptp-dmzstg2.pingan.com.cn:9080/epcis.ptp.partner.getAhsEPolicyPDFWithCert.do";//测试环境地址
	//$url = "https://epcis-ptp.pingan.com.cn/epcis.ptp.partner.getAhsEPolicyPDFWithCert.do";//正式环境的地址
	ss_log("java_class_name: ".$java_class_name_pingan);

	$p = create_java_obj($java_class_name_pingan);


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

	
	ss_log("keyFile: ".$keyFile);
	ss_log("pdfFile: ".$pdfFile);
	
	$ret = (string)$p->applyElectricPolicyBill( 
												$url,
												$POLICYNO,
												$VALIDATECODE,
												$orderNum,
												$isSeperated,
											    $electronicPolicy,
												$keyFile,//  证书文件全名
                								$pdfFile,//  保存电子保单文件全名
                								$logFile,//  保存日志文件
												''
												);
	
	ss_log("applyElectricPolicyBill ret: ".$ret);

	return $ret;

}


//added by zhangxi, 20141230, 华安保单注销入口
function withdraw_policy_huaan($policy_id,$user_info_applicant)
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
		$result_attr = post_withdraw_policy_huaan(	$policy_attr, $user_info_applicant );
	
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
function post_withdraw_policy_huaan( $policy_attr, $user_info_applicant )
{
	global $_SGLOBAL, $java_class_name_huaan;
	global $LOGFILE_HUAAN;
	$UUID = getRandOnly_Id(0);
	$id_card = $user_info_applicant['certificates_code'];
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
	//华安保单注销报文
	if($policy_attr['attribute_type'] == 'xuepingxian')
	{
			$strxml = '<?xml version="1.0" encoding="GBK"?>
<PACKET type="REQUEST" version="1.0">
		<!-- 我方属性 -->
	<HEAD>
		<!-- 同步异步请求标志-->
		<!-- @desc SNY:同步请求 异步暂不支持 非空-->
		<TRANSTYPE>SNY</TRANSTYPE>
		<!-- 请求系统编码 -->
		<!-- @desc 参照文军ESB定义 非空-->
		<SYSCODE>ZHONGTIANXINHE</SYSCODE>
		<!-- 业务属性格式 -->
		<!-- @desc XML：xml格式  JSON：JSON格式 STR：字符串格式  目前只支持XML格式 非空-->
		<CONTENTTYPE>XML</CONTENTTYPE>
		<!-- 验证类型-->
		<!-- @desc 1:用户名密码验证 0或其他不认证 非空-->
		<VERIFYTYPE>1</VERIFYTYPE>
		<!-- 验证用户名 -->
		<USER>yunlibao</USER>
		<!-- 验证用户密码 -->
		<PASSWORD>yunlibao</PASSWORD>
		<!-- 加密串 -->
		<!--  @desc 暂没使用 -->
		<CIPHERTEXT></CIPHERTEXT>
		<!--交易标识号-->
		<!-- @desc 唯一标识一笔交易的序号 例如投保，保单查询、当日撤单等-->
		<SVCSEQNO>'.$orderNum.'</SVCSEQNO>
		<!--交易状态 非空  成功默认1-->
		<SVCCODE>1</SVCCODE>
	</HEAD>
	<!-- 第三方属性 -->
	<THIRD>
		<!-- 渠道编码 -->
		<EXTENTERPCODE>0701053547</EXTENTERPCODE>
		<!-- 产品编码 -->
		<PRODNO>0636-0612</PRODNO>
		<!-- 方案编码 -->
		<PLANNO>0000</PLANNO>
		<!-- 交易代码 -->
		<!-- @desc 如 100000：承保 100001：保单查询 100002：保费试算  非空-->
		<TRANSCODE>100002</TRANSCODE>		
		<!-- 地区代码 -->
		<EXTPROVCODE>1417392525959</EXTPROVCODE>
		<!-- 第三方交易流水号 -->
		<!-- @desc 交易标识号的子节点，如投保交易中发生了多次交互-->
		<EXTSEQUENCENO>'.$orderNum.'</EXTSEQUENCENO>
		<!-- 内部交易流水号 -->
		<!-- @desc 每次请求都有唯一的交易流水号-->
		<INTSEQUENCENO>'.$UUID.'</INTSEQUENCENO>
		<!-- 内部机构代码 -->
		<INNERCOMCODE>07040000</INNERCOMCODE>
		<!-- 交易日期 -->
		<TRANSDATE>'.$DATE.'</TRANSDATE>
		<!-- 交易时间 -->
		<TRANSTIME>'.$TIME.'</TRANSTIME>
		<!-- 外部操作员代码 -->
		<EXTOPERATORCODE>test</EXTOPERATORCODE>
	</THIRD>
	<BODY>
		<C_PLY_NO>'.$POLICYNO.'</C_PLY_NO>
		<C_APP_ID_CARD>'.$id_card.'</C_APP_ID_CARD>
	</BODY>
</PACKET>
';
	}
	else//建工险的注销
	{
		$strxml = '<?xml version="1.0" encoding="GBK"?>
	<PACKET type="REQUEST" version="1.0">
	<!-- HEAD -->
    <HEAD>
      <!--message request type-->
        <TRANSTYPE>SYN</TRANSTYPE>
        <!--transcode-->
        <TRANSCODE>100009</TRANSCODE>
        <!--username-->
        <USER>eies</USER>
        <!--password-->
        <PASSWORD>eies</PASSWORD>
        <SVCSEQNO>'.$orderNum.'</SVCSEQNO >
    </HEAD>
    <!-- HEAD end -->
	<THIRD>
	<EXTENTERPCODE>0701020603</EXTENTERPCODE>
	<EXTSEQUENCENO>'.$orderNum.'</EXTSEQUENCENO>
	<TRANSDATE>'.$DATE.'</TRANSDATE>
	<TRANSTIME>'.$TIME.'</TRANSTIME>
	</THIRD>
	<BODY>
	<C_PLY_NO>'.$POLICYNO.'</C_PLY_NO>
	</BODY>
	</PACKET>';
	}
	

	if(!empty($strxml))
	{
		$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_huaan_policy_withdraw.xml";
		file_put_contents($af_path,$strxml);
		
		//start add by wangcya, 20150209,把携带序列号的也保存起来
		$UUID = $orderNum;
		$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_huaan_policy_withdraw.xml";
		file_put_contents($af_path_serial,$strxml);
		//end add by wangcya, 20150209,把携带序列号的也保存起来
		
		$p = create_java_obj($java_class_name_huaan);
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
		if($policy_attr['attribute_type'] == 'project')
		{
			$url = URL_HUAAN_POST_POLICY;
			$insuerEncoding = "GBK";
			//$respXmlFileEncoding="GBK";
		}
		else
		{
			$url = URL_HUAAN_XUEPINGXIAN_POST_POLICY;
			$insuerEncoding = "UTF-8";
			//$respXmlFileEncoding="UTF-8";
		}
		//$url = URL_HUAAN_POST_POLICY;
		$respXmlFileName = "";
		ss_log("huaan , withdraw url: ".$url);
		//ss_log("keyFile: ".$keyFile);
		
		$return_data = (string)$p->applyPolicy( 
											$url , 
											$strxml ,
											$insuerEncoding,
											$LOGFILE_HUAAN
											);

		ss_log("after huaan withdraw call function sendMessage");
		
		if(!empty($return_data))
		{
			
			//先保存一份
			$return_path = S_ROOT."xml/".$order_sn."_".$policy_id."_huaan_policy_withdraw_ret.xml";
			file_put_contents($return_path,$return_data);

			//////////////////////////////////////////////////////////
		}
		else
		{
			$result = 110;
			$retmsg = "post huaan withdraw policy return is null!";
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

		if( ($HUAAN_RSLT_CODE =="1001" || $HUAAN_RSLT_CODE =="C00000000")&&////注销保单成功
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
			{//平安返回为空
				
				$result = 110;
				$retmsg = "华安返回有节点，但是节点内部无内容";
				
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



/*
 * author  :    zhangxi
 * date    :    20141222
 * function:    创建华安的XML投保单
 * input   :
 * return ：
 * 
 * */
function gen_huaan_policy_xml(
								$attribute_type,
								$attribute_code,
								$policy_arr,
								$user_info_applicant,
								$list_subject)
{

	if($attribute_type == 'project')
	{
		$str_header  = gen_huaan_header($policy_arr);
	
		$str_third = gen_huaan_third($policy_arr);
		//增加body元素
		$str_body = gen_huaan_body(  	
									$attribute_type,
									$attribute_code,		
									$policy_arr,
									$user_info_applicant,
									$list_subject);
		
		$str_xml='<?xml version="1.0" encoding="GBK"?>
		<PACKET type="REQUEST" version="1.0">'.
		$str_header.
		$str_third.
		$str_body.
		'</PACKET>';
	}
	elseif($attribute_type == 'xuepingxian')
	{
		$str_header  = gen_huaan_header_xuepingxian($policy_arr);
	
		$str_third = gen_huaan_third_xuepingxian($policy_arr,$list_subject);
		//增加body元素
		$str_body = gen_huaan_body_xuepingxian(  	
									$attribute_type,
									$attribute_code,		
									$policy_arr,
									$user_info_applicant,
									$list_subject);
		
		$str_xml='<?xml version="1.0" encoding="UTF-8"?>
					<PACKET type="REQUEST" version="1.0">'.
					$str_header.
					$str_third.
					$str_body.
					'</PACKET>';
	}
	else
	{
		
	}
	

	
	return $str_xml;
}
//added by zhangxi, 20141205 
function gen_huaan_header($policy_arr)
{
	$str_header="
	<HEAD>
		<TRANSTYPE>SYN</TRANSTYPE>
		<TRANSCODE>100001</TRANSCODE>
		<USER>eies</USER>
		<PASSWORD>eies</PASSWORD>
		<SVCSEQNO>".$policy_arr['order_num']."</SVCSEQNO>
	</HEAD>";
	return $str_header;
	
}

function gen_huaan_header_xuepingxian($policy_arr)
{
	$str_header="
	<HEAD>
		<TRANSTYPE>SYN</TRANSTYPE>
		<SYSCODE>ZHONGTIANXINHE</SYSCODE>
		<CONTENTTYPE>XML</CONTENTTYPE>
		<VERIFYTYPE>1</VERIFYTYPE>
		<USER>yunlibao</USER>
		<PASSWORD>yunlibao</PASSWORD>
		<SVCSEQNO>".$policy_arr['order_num']."</SVCSEQNO>
		<SVCCODE>1</SVCCODE>
	</HEAD>";
	return $str_header;
	
}


function gen_huaan_third($policy_arr)
{
	global $_SGLOBAL;
	//$order_sn $policy_arr['order_sn']
	$str_third="
	<THIRD>
		<EXTENTERPCODE>0701020603</EXTENTERPCODE>
		<EXTSEQUENCENO>".$policy_arr['order_num']."</EXTSEQUENCENO>
		<TRANSDATE>".date('Y-m-d',$_SGLOBAL['timestamp'])."</TRANSDATE>
		<TRANSTIME>".date('H:i:s',$_SGLOBAL['timestamp'])."</TRANSTIME>
	</THIRD>";
	return $str_third;
}
function gen_huaan_third_xuepingxian($policy_arr,$list_subject)
{
	global $_SGLOBAL;
	$transSn = getRandOnly_Id(0);
	$product = $list_subject[0]['list_subject_product'][0];
	//$order_sn $policy_arr['order_sn']
	$str_third="
	<THIRD>
		<!-- partner code -->
		<EXTENTERPCODE>0701053547</EXTENTERPCODE>
		<!-- product code-->
		<PRODNO>".$product['product_code']."</PRODNO>
		<!-- fangan bianma-->
		<PLANNO>0000</PLANNO>
		<!-- jiaoyidaima-->
		<!-- @desc like 100000 chengbao 100001 baodanchaxun 100002 baofeishisuan-->
		<TRANSCODE>100001</TRANSCODE>
		<!-- area code-->
		<EXTPROVCODE>1417392525959</EXTPROVCODE>
		<!-- @desc -->
		<EXTSEQUENCENO>".$policy_arr['order_num']."</EXTSEQUENCENO>
		<!-- @desc -->
		<INTSEQUENCENO>".$transSn."</INTSEQUENCENO>
		<!-- neibu jigou daima  -->
		<INNERCOMCODE>07040000</INNERCOMCODE>
		<TRANSDATE>".date('Y-m-d',$_SGLOBAL['timestamp'])."</TRANSDATE>
		<TRANSTIME>".date('H:i:s',$_SGLOBAL['timestamp'])."</TRANSTIME>
		<!-- operate code -->
		<EXTOPERATORCODE>test</EXTOPERATORCODE>
		 <!--null-->
        <SERVICECODE></SERVICECODE>
        <!--null-->
        <PROCESSCODE></PROCESSCODE>
	</THIRD>";
	return $str_third;
}

function gen_huaan_body_xuepingxian(	
						$attribute_type,
						$attribute_code,
						$policy_arr,
						$user_info_applicant,
						$list_subject)
{
	global $_SGLOBAL;
	$product = $list_subject[0]['list_subject_product'][0];
	//var_dump($list_subject[0]['policy_subject_id']);
	$policy_subject_id = $list_subject[0]['policy_subject_id'];
	$list_subject_insurant = $list_subject[0]['list_subject_insurant'];
	
	$arr_time=array('start_date'=>$policy_arr['start_date'],
					'end_date'=>$policy_arr['end_date'],
					);
	$arr_interval = get_interval_by_strtime('day', $arr_time);
			
	$arr_start = explode(" ", $policy_arr['start_date']);
	$arr_end = explode(" ", $policy_arr['end_date']);
	$start_date = $arr_start[0];
	$end_date = $arr_end[0];
	$apple_date = date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);
	$common_info="
				<PAY_CAR_NO />
		<PAY_TYPE />
		<PAY_NAME />
		<PAYER_ID_TYPE />
		<PAYER_ID_NO />
		<PAY_TIME />
		<C_APP_TM>".$apple_date."</C_APP_TM>
		<C_PROD_NO>".$product['product_code']."</C_PROD_NO>
		<C_SCHEME_TYPE>".$product['plan_code']."</C_SCHEME_TYPE>
		<C_PROD_TYPE />
		<INVESTCOUNT>".$policy_arr['apply_num']."</INVESTCOUNT>
		<C_INSRNC_PERIOD>0</C_INSRNC_PERIOD>
		<C_INSRNC_DAYS>".$arr_interval['interval']."</C_INSRNC_DAYS>
		<T_INSRNC_BGN_TM>".$start_date."</T_INSRNC_BGN_TM>
		<T_INSRNC_END_TM>".$end_date."</T_INSRNC_END_TM>
					";
	
	
	//根据投保类型决定是个人还是团体信息
	if($policy_arr['business_type'] == 1)//个人
	{		
		$app_custtype = 1;
		if(defined('IS_NO_GBK'))
		{
			$user_info_applicant_fullname =  $user_info_applicant['fullname']; //已知原编码为UTF-8, 转换为GBK
			//$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant_fullname, "GBK", "UTF-8" );
			//$user_info_applicant_fullname = '<![CDATA['.$user_info_applicant_fullname.']]>';
			$applicant_business_type = $user_info_applicant['business_type'];//个人职业类型
			
			$certificates_type = $user_info_applicant['certificates_type'];
			$certificates_code = $user_info_applicant['certificates_code'];
			//$applicant_business_type =  mb_convert_encoding($user_info_applicant[business_type], "GBK", "UTF-8" );
			//$applicant_business_type = '<![CDATA['.$applicant_business_type.']]>';
			$address = $user_info_applicant['address'];
			//$address =  mb_convert_encoding($user_info_applicant['address'], "GBK", "UTF-8" ); 
			//$address = '<![CDATA['.$address.']]>';
			
		}
		else
		{
			$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}
		//echo "user info".$user_info_applicant_fullname;
		//投保人信息
		$insuranceApplicantInfo ="
				<C_APP_NME>".$user_info_applicant_fullname."</C_APP_NME>
		<!--0 legal,1  personal-->
		<C_APPCUST_TYPE>1</C_APPCUST_TYPE>
		<C_APP_SEX>".$user_info_applicant['gender']."</C_APP_SEX>
		<C_APP_ID_TYPE>".$certificates_type."</C_APP_ID_TYPE>
		<C_APP_ID_CARD>".$certificates_code."</C_APP_ID_CARD>
		<C_APP_ADDR>".$user_info_applicant['address']."</C_APP_ADDR>
		<C_APP_TEL>".$user_info_applicant['telephone']."</C_APP_TEL>
		<C_APP_MOBILE>".$user_info_applicant['mobiletelephone']."</C_APP_MOBILE>
		<C_APP_EMAIL>".$user_info_applicant['email']."</C_APP_EMAIL>
		<C_APP_BIRTHDAY>".$user_info_applicant['birthday']."</C_APP_BIRTHDAY>
		<PLY_ISTNUM>1</PLY_ISTNUM>
		<C_EMAIL>".$user_info_applicant['email']."</C_EMAIL>
		<C_INVOICE_FLAG>0</C_INVOICE_FLAG>
		<POSTCODE />
		<INVOICE_ADDR />
		<SEND_NAME />
		<SEND_PHONE />
		<SUMAMOUNT>".round($product['sum_insured'],2)."</SUMAMOUNT>
		<SUMPREMIUM>".round($policy_arr['total_premium'],2)."</SUMPREMIUM>
		<PLY_TGT>
			<C_INSRNT_PROP_ADDR />
		</PLY_TGT>
					";
		
	}
	elseif($policy_arr['business_type'] == 2)//团体
	{
		$app_custtype = 0;
		if(defined('IS_NO_GBK'))
		{
			//$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['group_name'], "GBK", "UTF-8" ); 
			$user_info_applicant_fullname = $user_info_applicant['group_name']; //已知原编码为UTF-8, 转换为GBK
			//$user_info_applicant_fullname = '<![CDATA['.$user_info_applicant_fullname.']]>';
			
			//$groupAbbr =  $user_info_applicant['group_abbr']; //已知原编码为UTF-8, 转换为GBK
			//$groupAbbr = '<![CDATA['.$groupAbbr.']]>';
			
			//$address =  mb_convert_encoding($user_info_applicant['address'], "GBK", "UTF-8" ); 
			$address = $user_info_applicant['address'];
			//$address = '<![CDATA['.$address.']]>';
			$certificates_type = $user_info_applicant[group_certificates_type];
			$certificates_code = $user_info_applicant[group_certificates_code];
			
			//$applicant_business_type =  mb_convert_encoding($user_info_applicant[business_type], "GBK", "UTF-8" );
			//$applicant_business_type = '<![CDATA['.$applicant_business_type.']]>';
		}
		else
		{
			$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['group_name'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			$groupAbbr =  mb_convert_encoding($user_info_applicant['group_abbr'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			$address =  mb_convert_encoding($user_info_applicant['address'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}
		//投保人信息
		$insuranceApplicantInfo ="

							";
	}
	else
	{
		//error， log here
	}
	
	
	//循环获取所有被保险人信息
	$insurant_info='';
	$index = 0;
	$project_relationship=array();
	foreach($list_subject_insurant AS $key_insurant =>$value_insurant )
	{
		if(defined('IS_NO_GBK'))
		{
			$user_info_assured_fullname = $value_insurant['fullname'];
			//$user_info_assured_fullname =  mb_convert_encoding($value_insurant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			//$user_info_assured_fullname =  $value_insurant['fullname']; //已知原编码为UTF-8, 转换为GBK
			//$user_info_assured_fullname = '<![CDATA['.$user_info_assured_fullname.']]>';
			
			$business_type = $value_insurant['business_type'];
			//$business_type =  mb_convert_encoding($business_type, "GBK", "UTF-8" );
			//$business_type = '<![CDATA['.$business_type.']]>';
			$address = $value_insurant['address']; 
		}
		else
		{
			$user_info_assured_fullname =  mb_convert_encoding($value_insurant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}
		$index++;
		$project_relationship[] = $value_insurant['insurant_type'];
		$insurant_info = $insurant_info."
				<INSLIFE_DATA>
					<SEQ_NO>".$index."</SEQ_NO>
					<NAME>".$user_info_assured_fullname."</NAME>
					<SEX>".$value_insurant['gender']."</SEX>
					<BIRTHDAY>".$value_insurant['birthday']."</BIRTHDAY>
					<INSUREDIDNUM>".$value_insurant['certificates_code']."</INSUREDIDNUM>
					<BIZ_CERT_TYPE>".$value_insurant['certificates_type']."</BIZ_CERT_TYPE>
					<C_INSRNT_TEL>".$value_insurant['telephone']."</C_INSRNT_TEL>
					<C_INSRNT_MOBILE>".$value_insurant['mobiletelephone']."</C_INSRNT_MOBILE>
					<C_INSRNT_EMAIL>".$value_insurant['email']."</C_INSRNT_EMAIL>
					<C_SCHOOL_NAME>".$value_insurant['school_name']."</C_SCHOOL_NAME>
					<STAFF_TYPE>1</STAFF_TYPE>
					<!--relation -->
					<C_RELA_CDE>".$policy_arr['relationship_with_insured']."</C_RELA_CDE>
				</INSLIFE_DATA>
		                               ";
		

	}//end foreach $list_subject_insurant, 被保险人设置循环结束
	
	$str_total_insurant_info = "<INSLIFE_ISTNUM>".$index."</INSLIFE_ISTNUM>
								<INSLIFE_LIST>".$insurant_info.
								"</INSLIFE_LIST>
	                           ";

	
	$arr_target_info = get_huana_policy_project_info($policy_arr['policy_id']);
	
	
	$other_info = "<KIND_ISTNUM>0</KIND_ISTNUM>
		<KIND_LIST>
			<KIND_DATA>
				<N_SEQ_NO/>
				<C_INSRNC_CDE/>
				<INVESTCOUNT/>
				<N_AMT />
				<N_PRM />
			</KIND_DATA>
		</KIND_LIST>
		<CAR_INFO />
		<PET_INFO />
		<IS_AUTO_REL>0</IS_AUTO_REL>
		<REL_INFO />";
	
	$str_body = "
			<BODY>".
			$common_info.
			$insuranceApplicantInfo.
			$str_total_insurant_info.
			$other_info.
			"</BODY>
			";
	
	return $str_body;
		
}
function gen_huaan_body(	
						$attribute_type,
						$attribute_code,
						$policy_arr,
						$user_info_applicant,
						$list_subject)
{
	global $_SGLOBAL;
	$product = $list_subject[0]['list_subject_product'][0];
	//var_dump($list_subject[0]['policy_subject_id']);
	$policy_subject_id = $list_subject[0]['policy_subject_id'];
	$list_subject_insurant = $list_subject[0]['list_subject_insurant'];
	
	$arr_time=array('start_date'=>$policy_arr['start_date'],
					'end_date'=>$policy_arr['end_date'],
					);
	$arr_interval = get_interval_by_strtime('day', $arr_time);
	
	
	$wheresql = "ipd.product_id=$product[product_id] AND  pspdp.policy_subject_id=$policy_subject_id";
	$sql = "SELECT d.duty_code,ipdp.premium,ipdp.amount FROM ".tname('insurance_product_duty_price')." AS ipdp 
			INNER JOIN ".tname('insurance_policy_subject_product_duty_prices')." AS pspdp 
	        ON pspdp.product_duty_price_id= ipdp.product_duty_price_id 
	        INNER JOIN ".tname('insurance_product_duty')." AS ipd 
	        ON ipdp.product_duty_id= ipd.product_duty_id 
			INNER JOIN ".tname('insurance_duty')." AS d 
	        ON d.duty_id= ipd.duty_id 
			WHERE ". $wheresql;
	ss_log("huaan get product duty price sql:".$sql);
	$query = $_SGLOBAL['db']->query($sql);
	$product_duty_price = $_SGLOBAL['db']->fetch_row($query);
	//var_dump($product_duty_price);
	//exit(0);
	//$dutycode= $product_duty_price['duty_code'];
	$duty_totalModalPremium = $product_duty_price[1];
	$dutyAmount = $product_duty_price[2];
	ss_log("huaan dutyAmount:".$dutyAmount);
	$arr_amount = explode(',', $dutyAmount);
	if(!$arr_amount)
	{
		ss_log("error, gen huaan xml file failed! ");
		exit(0);
	}
	
	$limit_amount = ($arr_amount[0])*10000;
	ss_log("limit_amount:".$limit_amount);			
	$arr_start = explode(" ", $policy_arr['start_date']);
	$arr_end = explode(" ", $policy_arr['end_date']);
	$start_date = $arr_start[0];
	$end_date = $arr_end[0];
	//added by zhangxi, 20150109, for huaan  bug
	$project_code = $arr_amount[3];
	//product info
	$product_info="
				<!-- date-->
				<C_APP_TM>".date('Y-m-d H:i:s',($_SGLOBAL['timestamp']-3600*24))."</C_APP_TM>
				<!-- product code-->
				<C_PROD_NO>".$product[product_code]."</C_PROD_NO>
				<C_PROD_TYPE>".$project_code."</C_PROD_TYPE>
				<INVESTCOUNT>1</INVESTCOUNT>
				 		<!--  duration of insurance(months)--> 
				<C_INSRNC_PERIOD>0</C_INSRNC_PERIOD>
						<!-- duration of insurance(days) -->
				<C_INSRNC_DAYS>".$arr_interval['interval']."</C_INSRNC_DAYS>
						<!-- insurance start date-->
				<T_INSRNC_BGN_TM>".$start_date."</T_INSRNC_BGN_TM>
						<!-- insurance end date-->
				<T_INSRNC_END_TM>".$end_date."</T_INSRNC_END_TM>
						<!-- operate date-->
				<OPERATEDATE>".date('Y-m-d',$_SGLOBAL['timestamp'])."</OPERATEDATE>
					";
	
	
	//根据投保类型决定是个人还是团体信息
	if($policy_arr['business_type'] == 1)//个人
	{		
		$app_custtype = 1;
		if(defined('IS_NO_GBK'))
		{
			$user_info_applicant_fullname =  $user_info_applicant['fullname']; //已知原编码为UTF-8, 转换为GBK
			//$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant_fullname, "GBK", "UTF-8" );
			//$user_info_applicant_fullname = '<![CDATA['.$user_info_applicant_fullname.']]>';
			$applicant_business_type = $user_info_applicant['business_type'];//个人职业类型
			
			$certificates_type = $user_info_applicant['certificates_type'];
			$certificates_code = $user_info_applicant['certificates_code'];
			//$applicant_business_type =  mb_convert_encoding($user_info_applicant[business_type], "GBK", "UTF-8" );
			//$applicant_business_type = '<![CDATA['.$applicant_business_type.']]>';
			$address = $user_info_applicant['address'];
			//$address =  mb_convert_encoding($user_info_applicant['address'], "GBK", "UTF-8" ); 
			//$address = '<![CDATA['.$address.']]>';
			
		}
		else
		{
			$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}
		//echo "user info".$user_info_applicant_fullname;
		//投保人信息
		$insuranceApplicantInfo ="
				<C_APP_NME>".$user_info_applicant_fullname."</C_APP_NME>
				<C_APP_BUSINESS_TYPE>-----</C_APP_BUSINESS_TYPE>
				<C_APP_SEX>".$user_info_applicant['gender']."</C_APP_SEX>
				<!--applicant type: 1 personal, 0 legal person -->
				<C_APPCUST_TYPE>".$app_custtype."</C_APPCUST_TYPE>
				<C_APP_ID_TYPE>".$certificates_type."</C_APP_ID_TYPE>
				<C_APP_ID_CARD>".$certificates_code."</C_APP_ID_CARD>
				<C_APP_EMAIL>".$user_info_applicant['email']."</C_APP_EMAIL>
				<C_APP_MOBILE>".$user_info_applicant['mobiletelephone']."</C_APP_MOBILE>
				<C_APP_ZIP>-----</C_APP_ZIP>
				<C_APP_TEL>".$user_info_applicant['mobiletelephone']."</C_APP_TEL>
				<C_APP_ADDR>-----</C_APP_ADDR>
				<!--   country   193001	china    193002	other --> 
				<C_APP_NATIONALITY>".$user_info_applicant['nation_code']."</C_APP_NATIONALITY>
				<!--added by zhx, 20150422-->
				<!-- 总保费(必填)-->
				<SUMPREMIUM>".$policy_arr['total_premium']."</SUMPREMIUM>
				<!-- 受益人 -->
				<BENEFICIARY>".$policy_arr['beneficiary']."</BENEFICIARY>
					";
		
	}
	elseif($policy_arr['business_type'] == 2)//团体
	{
		$app_custtype = 0;
		if(defined('IS_NO_GBK'))
		{
			//$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['group_name'], "GBK", "UTF-8" ); 
			$user_info_applicant_fullname = $user_info_applicant['group_name']; //已知原编码为UTF-8, 转换为GBK
			//$user_info_applicant_fullname = '<![CDATA['.$user_info_applicant_fullname.']]>';
			
			//$groupAbbr =  $user_info_applicant['group_abbr']; //已知原编码为UTF-8, 转换为GBK
			//$groupAbbr = '<![CDATA['.$groupAbbr.']]>';
			
			//$address =  mb_convert_encoding($user_info_applicant['address'], "GBK", "UTF-8" ); 
			$address = $user_info_applicant['address'];
			//$address = '<![CDATA['.$address.']]>';
			$certificates_type = $user_info_applicant[group_certificates_type];
			$certificates_code = $user_info_applicant[group_certificates_code];
			
			//$applicant_business_type =  mb_convert_encoding($user_info_applicant[business_type], "GBK", "UTF-8" );
			//$applicant_business_type = '<![CDATA['.$applicant_business_type.']]>';
		}
		else
		{
			$user_info_applicant_fullname =  mb_convert_encoding($user_info_applicant['group_name'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			$groupAbbr =  mb_convert_encoding($user_info_applicant['group_abbr'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			$address =  mb_convert_encoding($user_info_applicant['address'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}
		//投保人信息
		$insuranceApplicantInfo ="
				<C_APP_NME>".$user_info_applicant_fullname."</C_APP_NME>
				<C_APP_BUSINESS_TYPE>-----</C_APP_BUSINESS_TYPE>
				<C_APP_SEX>".$user_info_applicant['gender']."</C_APP_SEX>
				<!--applicant type: 1 personal, 0 legal person -->
				<C_APPCUST_TYPE>".$app_custtype."</C_APPCUST_TYPE>
				<C_APP_ID_TYPE>".$certificates_type."</C_APP_ID_TYPE>
				<C_APP_ID_CARD>".$certificates_code."</C_APP_ID_CARD>
				<C_APP_EMAIL>".$user_info_applicant['email']."</C_APP_EMAIL>
				<C_APP_MOBILE>".$user_info_applicant['mobiletelephone']."</C_APP_MOBILE>
				<C_APP_ZIP>-----</C_APP_ZIP>
				<C_APP_TEL>".$user_info_applicant['telephone']."</C_APP_TEL>
				<C_APP_ADDR>-----</C_APP_ADDR>
				<!--   country   193001	china    193002	other --> 
				<C_APP_NATIONALITY>".$user_info_applicant['nation_code']."</C_APP_NATIONALITY>
				<!--added by zhx, 20150422-->
				<!-- 总保费(必填)-->
				<SUMPREMIUM>".$policy_arr['total_premium']."</SUMPREMIUM>
				<!-- 受益人 -->
				<BENEFICIARY>".$policy_arr['beneficiary']."</BENEFICIARY>
							";
	}
	else
	{
		//error， log here
	}
	
	
	//循环获取所有被保险人信息
	$insurant_info='';
	$index = 0;
	$project_relationship=array();
	foreach($list_subject_insurant AS $key_insurant =>$value_insurant )
	{
		if(defined('IS_NO_GBK'))
		{
			$user_info_assured_fullname = $value_insurant['fullname'];
			//$user_info_assured_fullname =  mb_convert_encoding($value_insurant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			//$user_info_assured_fullname =  $value_insurant['fullname']; //已知原编码为UTF-8, 转换为GBK
			//$user_info_assured_fullname = '<![CDATA['.$user_info_assured_fullname.']]>';
			
			$business_type = $value_insurant['business_type'];
			//$business_type =  mb_convert_encoding($business_type, "GBK", "UTF-8" );
			//$business_type = '<![CDATA['.$business_type.']]>';
			$address = $value_insurant['address'];
			//$address = mb_convert_encoding($value_insurant[address], "GBK", "UTF-8" );
			//$address = '<![CDATA['.$address.']]>';
			//$business_type = "制造业";
			//$business_type = mb_convert_encoding("制造业", "GBK", "UTF-8" );
			//$business_type = '<![CDATA['.$business_type.']]>';
			 
		}
		else
		{
			$user_info_assured_fullname =  mb_convert_encoding($value_insurant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}
		$index++;
		$project_relationship[] = $value_insurant['insurant_type'];
		$insurant_info = $insurant_info."
				<INSLIFE_DATA>
					<SEQ_NO>".$index."</SEQ_NO>
					<NME>".$user_info_assured_fullname."</NME>
					<C_BUSINESS_TYPE>-----</C_BUSINESS_TYPE>
					<C_INSRNT_ADDR>-----</C_INSRNT_ADDR>
					<C_BIZ_CERT_NO>".$value_insurant['certificates_code']."</C_BIZ_CERT_NO>
					<C_BIZ_CERT_TYPE>".$value_insurant['certificates_type']."</C_BIZ_CERT_TYPE>
					<!-- insurant type 413001 suoyouren  413002	chengbaoren  413005	qitaguanxifang-->
					<STAFF_TYPE>".$value_insurant['insurant_type']."</STAFF_TYPE>
					<C_RELA_CDE>601005</C_RELA_CDE>
					<!--  0 is not insurant ,1 yes-->
					<C_INSRANT_FLG>".$value_insurant['insurant_flag']."</C_INSRANT_FLG>
					<!-- 被保人联系号码 -->
					<C_INSRNT_TEL>".$value_insurant['mobiletelephone']."</C_INSRNT_TEL>
					<!--  被保险人国籍   193001	境内    193002	境外 -->
					<C_INSUR_NATIONALITY>193001</C_INSUR_NATIONALITY>
					<C_ZIP>-----</C_ZIP>
				</INSLIFE_DATA>
		                               ";
		                               //
		                               //
		                               //
		

	}//end foreach $list_subject_insurant, 被保险人设置循环结束
	
	//mod by zhangxi, 20150119, 按照华安要求修改报文
	if(!in_array('413001', $project_relationship))
	{
		$index++;
		$insurant_info = $insurant_info."
				<INSLIFE_DATA>
					<SEQ_NO>".$index."</SEQ_NO>
					<NME>-----</NME>
					<C_BUSINESS_TYPE>-----</C_BUSINESS_TYPE>
					<C_INSRNT_ADDR>-----</C_INSRNT_ADDR>
					<C_BIZ_CERT_NO>-----</C_BIZ_CERT_NO>
					<C_BIZ_CERT_TYPE>120001</C_BIZ_CERT_TYPE>
					<!-- insurant type 413001 suoyouren  413002	chengbaoren  413005	qitaguanxifang-->
					<STAFF_TYPE>413001</STAFF_TYPE>
					<C_RELA_CDE>601005</C_RELA_CDE>
					<!--  0 is not insurant ,1 yes-->
					<C_INSRANT_FLG>0</C_INSRANT_FLG>
					<!-- 被保人联系号码 -->
					<C_INSRNT_TEL>-----</C_INSRNT_TEL>
					<!--  被保险人国籍   193001	境内    193002	境外 -->
					<C_INSUR_NATIONALITY>193001</C_INSUR_NATIONALITY>
					<C_ZIP>-----</C_ZIP>
				</INSLIFE_DATA>
		                               ";
		                               
	}
	if(!in_array('413002', $project_relationship))
	{
		$index++;
		$insurant_info = $insurant_info."
				<INSLIFE_DATA>
					<SEQ_NO>".$index."</SEQ_NO>
					<NME>-----</NME>
					<C_BUSINESS_TYPE>-----</C_BUSINESS_TYPE>
					<C_INSRNT_ADDR>-----</C_INSRNT_ADDR>
					<C_BIZ_CERT_NO>-----</C_BIZ_CERT_NO>
					<C_BIZ_CERT_TYPE>120001</C_BIZ_CERT_TYPE>
					<!-- insurant type 413001 suoyouren  413002	chengbaoren  413005	qitaguanxifang-->
					<STAFF_TYPE>413002</STAFF_TYPE>
					<C_RELA_CDE>601005</C_RELA_CDE>
					<!--  0 is not insurant ,1 yes-->
					<C_INSRANT_FLG>0</C_INSRANT_FLG>
					<!-- 被保人联系号码 -->
					<C_INSRNT_TEL>-----</C_INSRNT_TEL>
					<!--  被保险人国籍   193001	境内    193002	境外 -->
					<C_INSUR_NATIONALITY>193001</C_INSUR_NATIONALITY>
					<C_ZIP>-----</C_ZIP>
				</INSLIFE_DATA>
		                               ";
	}
	if(!in_array('413005', $project_relationship))
	{
		$index++;
		$insurant_info = $insurant_info."
				<INSLIFE_DATA>
					<SEQ_NO>".$index."</SEQ_NO>
					<NME>-----</NME>
					<C_BUSINESS_TYPE>-----</C_BUSINESS_TYPE>
					<C_INSRNT_ADDR>-----</C_INSRNT_ADDR>
					<C_BIZ_CERT_NO>-----</C_BIZ_CERT_NO>
					<C_BIZ_CERT_TYPE>120001</C_BIZ_CERT_TYPE>
					<!-- insurant type 413001 suoyouren  413002	chengbaoren  413005	qitaguanxifang-->
					<STAFF_TYPE>413005</STAFF_TYPE>
					<C_RELA_CDE>601005</C_RELA_CDE>
					<!--  0 is not insurant ,1 yes-->
					<C_INSRANT_FLG>0</C_INSRANT_FLG>
					<!-- 被保人联系号码 -->
					<C_INSRNT_TEL>-----</C_INSRNT_TEL>
					<!--  被保险人国籍   193001	境内    193002	境外 -->
					<C_INSUR_NATIONALITY>193001</C_INSUR_NATIONALITY>
					<C_ZIP>-----</C_ZIP>
				</INSLIFE_DATA>
		                               ";
	}
	$str_total_insurant_info = "<INSLIFE_ISTNUM>".$index."</INSLIFE_ISTNUM>
								<INSLIFE_LIST>".$insurant_info.
								"</INSLIFE_LIST>
	                           ";

	
	$arr_target_info = get_huana_policy_project_info($policy_arr['policy_id']);
	
	if(defined('IS_NO_GBK'))
		{
			$project_type = $arr_target_info['project_type'];
			//$project_type =  mb_convert_encoding($arr_target_info[project_type], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
			//$project_type = '<![CDATA['.$project_type.']]>';
			$project_name = $arr_target_info['project_name'];
			//$project_name =  mb_convert_encoding($arr_target_info[project_name], "GBK", "UTF-8" );
			//$project_name = '<![CDATA['.$project_name.']]>';
			$project_content = $arr_target_info['project_content'];
			//$project_content =  mb_convert_encoding($arr_target_info[project_content], "GBK", "UTF-8" );
			//$project_content = '<![CDATA['.$project_content.']]>';
			$project_location = $arr_target_info['project_location'];
			//$project_location =  mb_convert_encoding($arr_target_info[project_location], "GBK", "UTF-8" );
			//$project_location = '<![CDATA['.$project_location.']]>';
			 
		}
		else
		{
			$user_info_assured_fullname =  mb_convert_encoding($value_insurant['fullname'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}
	
	$arr_project_start = explode(" ", $arr_target_info['project_start_date']);
	$arr_project_end = explode(" ", $arr_target_info['project_end_date']);
	$project_start_date = $arr_project_start[0];
	$project_end_date = $arr_project_end[0];
	$arr_time=array();
	$arr_time['start_date']= $arr_target_info['project_start_date'];
	$arr_time['end_date'] = $arr_target_info['project_end_date'];
	$total_project_days = get_interval_by_strtime('day', $arr_time);
	$target_info = "<PLY_TGT>
				<!-- project type-->
				<C_TGT_FLD1>".$project_type."</C_TGT_FLD1>
					<!--project name-->
				<C_TGT_FLD2>".$project_name."</C_TGT_FLD2>
				<!-- project price--> 
				<N_TGT_FLD1>".$arr_target_info['project_price']."</N_TGT_FLD1>
				<!--total project days-->
				<N_TGT_FLD2>".$total_project_days['interval']."</N_TGT_FLD2>
				<!-- xian e--> 
				<N_TGT_FLD3>".$limit_amount."</N_TGT_FLD3>
				<T_TGT_FLD5>".$policy_arr['start_date']."</T_TGT_FLD5>
				<T_TGT_FLD6>".$policy_arr['end_date']."</T_TGT_FLD6>
				<!-- contract start date-->
				<T_TGT_FLD7>".$arr_target_info['project_start_date']."</T_TGT_FLD7>
				<!-- contract end date-->
				<T_TGT_FLD8>".$arr_target_info['project_end_date']."</T_TGT_FLD8>
				<!-- project content-->
				<C_TGT_FLD20>".$project_content."</C_TGT_FLD20>
				<C_TGT_FLD14>".$arr_target_info['province_name']."</C_TGT_FLD14>
				<C_TGT_FLD15>".$arr_target_info['city_name']."</C_TGT_FLD15>
				<C_TGT_FLD16>".$arr_target_info['city_name']."</C_TGT_FLD16>  
				<!-- project address-->
				<C_TGT_FLD3>".$arr_target_info['province_name'].",".$arr_target_info['city_name'].",".$project_location."</C_TGT_FLD3>
				<!--   address correct flag  --> 
				<C_TGT_FLD13>1</C_TGT_FLD13>
				<!--   zipcode  -->
				<C_TGT_FLD17>".$arr_target_info['zipcode']."</C_TGT_FLD17>
               </PLY_TGT>";
	
	$str_body = "
			<BODY>".
			$product_info.
			$insuranceApplicantInfo.
			$str_total_insurant_info.
			$target_info.
			"</BODY>
			";
	
	return $str_body;
		
}
function get_huana_policy_project_info($policy_id)
{
	global $_SGLOBAL;
	$wheresql = "policy_id='$policy_id'";
	$sql = "SELECT * FROM ".tname('insurance_policy_huaan_project_info')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$policy_huaan_project_info = $_SGLOBAL['db']->fetch_array($query);
	
	return $policy_huaan_project_info;
}


$G_XLS_UPLOAD_FORMAT_HUAAN_XUEPINGXIAN_TUANDAN = array(
					'1'=>'assured_name',
					'2'=>'assured_certificates_type',//被保险人证件类型
					'3'=>'assured_certificates_code',//被保险人证件号码
					'4'=>'assured_gender',//被保险人出生日期列
					);
$G_XLS_UPLOAD_FORMAT_HUAAN_XUEPINGXIAN_PILIANG = array(
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
					'15'=>'assured_school_name',//被保险人所在学校
					);
					


//added by zhangxi, 20150319, 华安产品xls文件上传
function process_file_xls_huaan_insured($uploadfile, $product)
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
	global $G_XLS_UPLOAD_FORMAT_HUAAN_XUEPINGXIAN_TUANDAN;
	global $G_XLS_UPLOAD_FORMAT_HUAAN_XUEPINGXIAN_PILIANG;
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
			$result = process_file_xls_upload_common($uploadfile, $product, $G_XLS_UPLOAD_FORMAT_HUAAN_XUEPINGXIAN_TUANDAN);
		}
		//批量上传	
		elseif($commit_type == 'piliang')
		{
			$result = process_file_xls_upload_common($uploadfile, $product, $G_XLS_UPLOAD_FORMAT_HUAAN_XUEPINGXIAN_PILIANG);
		}
		else
		{
			ss_log(__FUNCTION__."commit_type=".$commit_type);
		}
	}
	
	
	return $result;
}

?>