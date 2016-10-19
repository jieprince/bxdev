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
$java_class_name_cpic_cargo = 'com.yanda.imsp.util.ins.cpic.traffic.CPICTrafficIns';
$LOGFILE_CPIC_CARGO=S_ROOT."log/java_cpic_cargo_post_policy.log";
////////////////////////////////////////////////////////////////////////////
$cpic_cargo_endorsement_return_status = array (
									"AUDIT"=>"15",//15 待审核-----批单录入成功，提交人工审核
									"OK"=>"17",//非在线支付情况，状态：17 --批单生效
									"REJECT"=>"21"//21 审核退回或拒绝-----批单审核不通过的情况下，系统自动作废。可重新提交批改申请
									);
////////////////////////////////////////////////////////////////////////////////
//国内货运险，险种
$arr_cpic_cargo_internal_insurance_type =  array(
											"11040100" => "水路货运险",
											"11040200" => "公路货运险",
											"11040300" => "铁路货运险",
											"11040400" => "航空货运险",
											"11040700" => "其它国内货运险"
												);
//公路承运人责任保险
//险种代码为11042700

//公路承运人责任保险，也就是
//001 通用 002费率 003全额退保 004部分退保


//进出口货运险险种
$arr_cpic_cargo_entrance_insurance_type = array(
											"12040100" => "进口货运险",
											"12040200" => "出口货运险",
											"12040300" => "境外货运险",
												);
//包装代码
$arr_cpic_cargo_package_code =  array(
											"01" => "箱装",
											"02" => "袋装",
											"03" => "托盘",
											"04" => "散装",
											"05" => "裸装",
											"06" => "桶装",
											"07" => "灌装",
											"08" => "盘卷包装"
												);

//国内货运险运输方式
$arr_cpic_cargo_internal_transport_type = array("1"=>"海运",
												"2"=>"水运",
												"3"=>"铁路",
												"4"=>"公路",
												"5"=>"航空",
												"6"=>"邮包",
												"7"=>"管道运输",
												"8"=>"联运",
												"9"=>"其他",
												"10"=>"集装箱海运",
												"11"=>"集装箱陆运"
												);
//进出口货运险运输方式
$arr_cpic_cargo_entrance_transport_type = array("1"=>"海运",
												"3"=>"铁路",
												"4"=>"公路",
												"5"=>"航空",
												"6"=>"邮包",
												"10"=>"集装箱海运",
												"11"=>"集装箱陆运"
												);

//added by zhangxi, 20150609, 货运险附加信息获取
function get_cpic_cargo_additional_info($policy_id, $policy_arr,$attribute_type)
{
	global $_SGLOBAL;
	if($attribute_type == 'internal_cargo')
	{
		$wheresql = "policy_id='$policy_id'";
		$sql = "SELECT * FROM ".tname('insurance_policy_cpic_cargo_internal_entrance_otherinfo')." WHERE $wheresql LIMIT 1";
		//echo $sql;
		$query = $_SGLOBAL['db']->query($sql);
		$policy_additional_info = $_SGLOBAL['db']->fetch_array($query);
		
		if($policy_additional_info)
		{	
			$policy_arr["classtype"] = $policy_additional_info['classtype'];//
			$policy_arr["mark"] = $policy_additional_info['mark'];//
			$policy_arr["quantity"] = $policy_additional_info['quantity'];//
			$policy_arr["item"] = $policy_additional_info['item'];//
			$policy_arr["packcode"] = $policy_additional_info['packcode'];//
	
			$policy_arr["itemcode"] = $policy_additional_info['itemcode'];
			$policy_arr["flightareacode"] = $policy_additional_info['flightareacode'];
			$policy_arr["kind"] = $policy_additional_info['kind'];
			$policy_arr["kindname"] = $policy_additional_info['kindname'];
			$policy_arr["voyno"] = $policy_additional_info['voyno'];
			$policy_arr["startport"] = $policy_additional_info['startport'];
			$policy_arr["transport1"] = $policy_additional_info['transport1'];
			$policy_arr["endport"] = $policy_additional_info['endport'];
			$policy_arr["claimagent"] = $policy_additional_info['claimagent'];
			$policy_arr["mainitemcode"] = $policy_additional_info['mainitemcode'];
			$policy_arr["itemcontent"] = $policy_additional_info['itemcontent'];
			$policy_arr["currencycode"] = $policy_additional_info['currencycode'];
			$policy_arr["pricecond"] = $policy_additional_info['pricecond'];
			$policy_arr["invamount"] = $policy_additional_info['invamount'];
			$policy_arr["incrate"] = $policy_additional_info['incrate'];
			$policy_arr["amount"] = $policy_additional_info['amount'];
			$policy_arr["rate"] = $policy_additional_info['rate'];
			$policy_arr["premium"] = $policy_additional_info['premium'];
			$policy_arr["fcurrencycode"] = $policy_additional_info['fcurrencycode'];
			$policy_arr["claimcurrencycode"] = $policy_additional_info['claimcurrencycode'];
			$policy_arr["claimpayplace"] = $policy_additional_info['claimpayplace'];
			$policy_arr["effectdate"] = $policy_additional_info['effectdate'];
			$policy_arr["saildate"] = $policy_additional_info['saildate'];
			$policy_arr["franchise"] = $policy_additional_info['franchise'];
			$policy_arr["specialize"] = $policy_additional_info['specialize'];
			$policy_arr["comments"] = $policy_additional_info['comments'];
			
	
		}	
	}
	elseif($attribute_type == 'other_cargo')
	{
		$wheresql = "policy_id='$policy_id'";
		$sql = "SELECT * FROM ".tname('insurance_policy_cpic_cargo_other_info')." WHERE $wheresql LIMIT 1";
		//echo $sql;
		$query = $_SGLOBAL['db']->query($sql);
		$policy_additional_info = $_SGLOBAL['db']->fetch_array($query);
		
		if($policy_additional_info)
		{	
			$policy_arr["BusinessType"] = $policy_additional_info['BusinessType'];//
			$policy_arr["RoadLicenseNumber"] = $policy_additional_info['RoadLicenseNumber'];//
			$policy_arr["GoodsName"] = $policy_additional_info['GoodsName'];//
			$policy_arr["TransportVehiclesApprovedTotal"] = $policy_additional_info['TransportVehiclesApprovedTotal'];//
			$policy_arr["VehicleOwners"] = $policy_additional_info['VehicleOwners'];//
			$policy_arr["CarNumber"] = $policy_additional_info['CarNumber'];
			$policy_arr["FrameNumber"] = $policy_additional_info['FrameNumber'];
			$policy_arr["EngineNo"] = $policy_additional_info['EngineNo'];
			$policy_arr["EachAccidentFranchise"] = $policy_additional_info['EachAccidentFranchise'];
			$policy_arr["TimeAmount"] = $policy_additional_info['TimeAmount'];
			
			$policy_arr["SumAmount"] = $policy_additional_info['SumAmount'];
			$policy_arr["Rate"] = $policy_additional_info['Rate'];
			$policy_arr["ItemCode"] = $policy_additional_info['ItemCode'];
			$policy_arr["Specialterm"] = $policy_additional_info['Specialterm'];
			
			$policy_arr["SailDate"] = $policy_additional_info['SailDate'];
			$policy_arr["StartPlace"] = $policy_additional_info['StartPlace'];
			$policy_arr["EndPlace"] = $policy_additional_info['EndPlace'];
			$policy_arr["DeliverorderNo"] = $policy_additional_info['DeliverorderNo'];
			$policy_arr["TotalInsuredCar"] = $policy_additional_info['TotalInsuredCar'];
			$policy_arr["Premium"] = $policy_additional_info['Premium'];
			$policy_arr["classtype"] = $policy_additional_info['classtype'];
					
		}	
	}
	
	return $policy_arr;
}
function input_check_cpic_cargo_other($product, $POST)
{
	
	global $_SGLOBAL;

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	ss_log(__FUNCTION__.", GET IN");
	$global_info = array();
	$agent_uid = $_SGLOBAL['supe_uid'];//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	
	//////////////////有效性检查//////////////////////////////////////////////////////
	if(	!isset($POST['applyNum'])||
		!isset($POST['startDate'])||
		!isset($POST['assured_fullname'])
		)
	{
		showmessage("您输入的投保信息不完整！");
		exit(0);
	}

	$global_info['applyNum'] = intval($POST['applyNum']);//份数
	$global_info['totalModalPremium'] = $POST['totalModalPremium'];//总保费,应该从前端计算得到的
	$global_info['beneficiary'] = 1;//$POST['beneficiary'];//受益人
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期
	
	//主要附加信息
	$global_info['BusinessType'] = trim($POST['BusinessType']);//
	$global_info['RoadLicenseNumber'] = trim($POST['RoadLicenseNumber']);//
	$global_info['GoodsName'] = trim($POST['GoodsName']);//
	$global_info['TransportVehiclesApprovedTotal'] = trim($POST['TransportVehiclesApprovedTotal']);//
	$global_info['VehicleOwners'] = trim($POST['VehicleOwners']);//
	$global_info['CarNumber'] = trim($POST['CarNumber']);//
	$global_info['FrameNumber'] = trim($POST['FrameNumber']);//
	$global_info['EngineNo'] = trim($POST['EngineNo']);//
	$global_info['EachAccidentFranchise'] = trim($POST['EachAccidentFranchise']);//
	$global_info['TimeAmount'] = trim($POST['TimeAmount']);//
	$global_info['SumAmount'] = trim($POST['SumAmount']);//
	$global_info['Rate'] = trim($POST['Rate']);//
	$global_info['ItemCode'] = trim($POST['ItemCode']);//
	$global_info['Specialterm'] = trim($POST['Specialterm']);//
	$global_info['TotalInsuredCar'] = 1;
	
	$global_info['SailDate'] = trim($POST['SailDate']);//
	$global_info['StartPlace'] = trim($POST['StartPlace']);//
	$global_info['EndPlace'] = trim($POST['EndPlace']);//
	$global_info['DeliverorderNo'] = trim($POST['DeliverorderNo']);//
	$global_info['classtype'] = trim($POST['classtype']);//
	
	$global_info['Premium'] = $global_info['totalModalPremium'];
	///////////////组合投保人信息//////////////////////////////////////////////////
	$user_info_applicant = array();
	
	$businessType = empty($POST['businessType'])?1:trim($POST['businessType']);
	$businessType = 1;

	$user_info_applicant['fullname'] = $POST['applicant_fullname'];
	$user_info_applicant['mobiletelephone'] = $POST['applicant_mobiletelephone'];
	$user_info_applicant['email'] = $POST['applicant_email'];
	
	
	/////////////////////////////////////////////////////////////////////
	$assured_info = array();

	/////////////////////////////////////////////////////////
	$relationshipWithInsured = trim($POST['relationshipWithInsured']);
	////////////////投保人的身份信息////////////////////////////////
	//////////////////首先根据被保险人身份证到用户表中查询，如果查到则进行信息更新，如果没查到插入新纪录

	$assured_info['fullname'] = trim($_POST['assured_fullname']);
	$assured_info['address'] = trim($_POST['assured_address']);
	$assured_info['zipcode'] = trim($_POST['assured_zipcode']);
	$assured_info['certificates_code'] = trim($_POST['assured_certificate']);

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

function input_check_cpic_cargo($product, $POST)
{
	global $_SGLOBAL;

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	ss_log(__FUNCTION__.", GET IN");
	$global_info = array();
	$agent_uid = $_SGLOBAL['supe_uid'];//add by wangcya, 20141219 ,不同厂商的证件号码和性别统一
	
	//////////////////有效性检查//////////////////////////////////////////////////////
	if(	!isset($POST['applyNum'])||
		!isset($POST['startDate'])||
		!isset($POST['group_name'])||
		!isset($POST['assured_fullname'])
		)
	{
		showmessage("您输入的投保信息不完整！");
		exit(0);
	}

	$global_info['applyNum'] = intval($POST['applyNum']);//份数
	$global_info['totalModalPremium'] = $POST['totalModalPremium'];//总保费,应该从前端计算得到的
	$global_info['beneficiary'] = 1;//$POST['beneficiary'];//受益人
	$global_info['startDate'] = $POST['startDate'];//保险开始日期
	$global_info['endDate'] = $POST['endDate'];//保险结束日期
	
	//主要附加信息
	$global_info['classtype'] = trim($POST['classtype']);//险种代码。选值参考数据字典。
	$global_info['mark'] = trim($POST['mark']);//标记/发票号码/运单号，长度不能大于500字符
	$global_info['quantity'] = trim($POST['quantity']);//包装及数量，长度不能大于500字符
	$global_info['item'] = trim($POST['item']);//货物名称，长度不能大于500字符
	$global_info['packcode'] = trim($POST['packcode']);//包装代码。选值参考数据字典。
	$global_info['itemcode'] = trim($POST['itemcode']);//货物类型代码。选值参考数据字典。
	$global_info['flightareacode'] = trim($POST['flightareacode']);//航行区域代码(★ 进出口必填)。选值参考数据字典。
	$global_info['kind'] = trim($POST['kind']);//运输方式代码。选值参考数据字典。
	$global_info['kindname'] = trim($POST['kindname']);//运输工具名称。长度不能大于30字符
	$global_info['voyno'] = trim($POST['voyno']);//航（班）次。长度不能大于30字符
	$global_info['startport'] = trim($POST['startport']);//运输路线 自。长度不能大于80字符
	$global_info['transport1'] = trim($POST['transport1']);//运输路线 经
	$global_info['endport'] = trim($POST['endport']);//运输路线到。长度不能大于80字符
	$global_info['claimagent'] = trim($POST['claimagent']);//理赔代理地代码。（★ 进出口必填）
	$global_info['mainitemcode'] = trim($POST['mainitemcode']);//主险条款代码。选值参考数据字典
	$global_info['itemcontent'] = trim($POST['itemcontent']);//主险条款内容。选值参考数据字典
	$global_info['currencycode'] = trim($POST['currencycode']);//投保币种代码。选值参考数据字典
	$global_info['pricecond'] = trim($POST['pricecond']);//价格条件只能为CIF。（★ 进出口必填）
	$global_info['invamount'] = trim($POST['invamount']);//发票金额。（★ 进出口必填）
	$global_info['incrate'] = trim($POST['incrate']);//加成比例。（★ 进出口必填）
	$global_info['amount'] = trim($POST['amount']);//保险金额。计算公式：保险金额 = 发票金额*(1+加成比例)
	$global_info['rate'] = trim($POST['rate']);//费率。不能小于核定最小费率。
	$global_info['premium'] = trim($POST['premium']);//保费。计算公式：保费= 保险金额*费率。保留两位有效数字。
	$global_info['fcurrencycode'] = trim($POST['fcurrencycode']);//保费币种，默认值为人民币。取值范围请参考附件：币种代码信息.txt
	$global_info['claimcurrencycode'] = trim($POST['claimcurrencycode']);//赔偿币种代码。（★ 进出口必填） 选值参考数据字典。
	$global_info['claimpayplace'] = trim($POST['claimpayplace']);//赔款偿付地。（★ 进出口必填）
	$global_info['effectdate'] = trim($POST['effectdate']);//起保日期。起保日期不能晚于起运日期
	$global_info['saildate'] = trim($POST['saildate']);//起运日期
	$global_info['franchise'] = trim($POST['franchise']);//免赔条件
	
	$global_info['specialize'] = trim($POST['specialize']);//特别约定 国内货运险选填，进出口可忽略
	$global_info['comments'] = trim($POST['comments']);//投保说明 （注：本投保说明内容不显示在保单上，只作为投保说明。） 不能大于2000字符。
	
	///////////////组合投保人信息//////////////////////////////////////////////////
	$user_info_applicant = array();
	
	$businessType = empty($POST['businessType'])?1:trim($POST['businessType']);
	$businessType = 2;
	if($businessType == 2)//团体
	{
			//投保人信息获取
		$user_info_applicant['group_name'] = $POST['group_name'];
	}

	/////////////////////////////////////////////////////////////////////
	$assured_info = array();

	/////////////////////////////////////////////////////////
	$relationshipWithInsured = trim($POST['relationshipWithInsured']);
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
		$assured_info['fullname']  = trim($_POST['assured_fullname']);
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

//批单操作接口
function insurance_endorsement_cpic_cargo($policy_arr,$use_async=true)
{
	
	global $_SGLOBAL, $java_class_name_cpic_cargo;
	/////////////////////////////////////////////////////////
	ss_log("into ".__FUNCTION__);
	
	$policy_id = $policy_arr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	
	$orderNum = $policy_arr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_arr['order_sn'];//add by wangcya, 20141023
	
	//生成需要批单请求处理的xml报文,
	//mod by zhangxi, 20150625, 国内进出口货运险和其他货运险的批单报文是不一样的
	if($policy_arr['attribute_type'] == 'other_cargo')
	{
		$policy_arr['endorse_type'] = '003';
		$endorsement_type = $policy_arr['endorse_type'];
		//保存到数据库中
		updatetable('insurance_policy',
								array("endorse_type"=>$policy_arr['endorse_type'],
										),
								array('policy_id'=>$policy_id)
								);
		$strxml = gen_cpic_cargo_other_insurance_endorsement_xml($policy_arr,
														$endorsement_type);
	}
	else
	{
		$policy_arr['endorse_type'] = '002';
		$endorsement_type = $policy_arr['endorse_type'];
		//保存到数据库中
		updatetable('insurance_policy',
								array("endorse_type"=>$policy_arr['endorse_type'],
										),
								array('policy_id'=>$policy_id)
								);
		$strxml = gen_cpic_cargo_insurance_endorsement_xml($policy_arr,
														$endorsement_type);
		
	}
	
	
	
	ss_log(__FUNCTION__.", after gen_cpic_cargo_insurance_endorse_xml,strxml=".$strxml);

	$strxml = trim($strxml);
	if(empty($strxml))
	{		
		$result = 112;
		$retmsg = "gen strxml null!";
		ss_log(__FUNCTION__.", ".$retmsg);
		
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		
		return $result_attr;
		
	}
	
	/////////////////////////////////////////////////////////////////////////////////////
	//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_cargo_endorse.xml";
	$af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_cpic_cargo_endorse.xml");
	file_put_contents($af_path,$strxml);//核保文件先存起来
	
	//start add by wangcya, 20150209,把携带序列号的也保存起来
	$UUID = $policy_arr['order_num'];
	//$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_cargo_policy_endorse.xml";
	$af_path_serial = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_".$UUID."_cargo_policy_endorse.xml");
	file_put_contents($af_path_serial,$strxml);
	//end add by wangcya, 20150209,把携带序列号的也保存起来
	

	if(!$use_async)
	{
		$java_class_name = $java_class_name_cpic_cargo;//同步
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_cpic_cargo:$java_class_name;
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
	//$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_cargo_endorse_ret.xml";
	$ret_af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_cpic_cargo_endorse_ret.xml");
	ss_log(__FUNCTION__.", ".$ret_af_path);
	
	$url = URL_CPIC_CARGO_POST_POLICY;//核保，投保保URL
	ss_log(__FUNCTION__."url: ".$url);
	$userName = USERNAME_CPIC_CARGO;
	$password = PASSWORD_CPIC_CARGO;
	$passNeedMd5 = 'n';
	$xmlContent = $strxml;
	$postXmlFileName = '';
	$postXmlFileEncoding = '';
	$checkCode = CHECKCODE_CPIC_CARGO;
	if(!$use_async)//同步批单处理流程
	{
		global $LOGFILE_CPIC_CARGO;

		//进行核保调用处理
		ss_log(__FUNCTION__.", cpic cargo will java check policy,同步批单请求开始");
		
		$strxml_check_policy_ret = $return_data = (string)$obj_java->endorse(
																			$url,//			服务地址
																			$userName,//用户名
																			$password,//密码
																			$passNeedMd5,//密码是否需要md5加密（y/yes是需要，其他不需要）
																			$policy_arr['classtype'],//险种代码     请参考freight_dictionary_20091125.xls
																			$endorsement_type,//批改类型代码  106 通用批改 104 费率批改  002 退保批改
																			$checkCode,//MD5校验码
																			$xmlContent,//报文内容
																			$postXmlFileName,//存储报文文件名称
																			$postXmlFileEncoding,//存储报文文件编码格式
																			$LOGFILE_CPIC_CARGO
																			);

		ss_log(__FUNCTION__.", after post call function underWriting");

		//////////////////////////////////////////////////////////
		if(!empty($strxml_check_policy_ret))//批单请求的返回处理
		{
			ss_log(__FUNCTION__.", cpic cargo will process return_data");
		
			file_put_contents($ret_af_path, $strxml_check_policy_ret);
				
			//////////////////////////////////////////////////////////////////////////
			$result_attr = process_return_xml_data_cpic_cargo(
														 	$policy_id,
									                      	$orderNum,
															$order_sn,
															$return_data,
															NULL,
															"endorse",
															false,
															false
															);
		}
		else
		{
			//$retmsg = iconv('GB2312', 'UTF-8', $retmsg); //将字符串的编码从GB2312转到UTF-8
			$retcode = 110;
			$retmsg = "result_attr return date is null!";
			ss_log(__FUNCTION__.", ".$retmsg);
				
			$result_attr = array('retcode'=>$retcode,
									'retmsg'=> $retmsg
									);
		}
		//更新到数据库中
		$setarr = array('ret_code'=>$result_attr['retcode'],
						'ret_msg'=>$result_attr['retmsg']);
		$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
		updatetable(	'insurance_policy',	
		                $setarr,
						array('policy_id'=>$policy_id)
				   );	
		
		return $result_attr;
	
	}
	else//异步批改
	{//USE_ASYN_JAVA
		//////////////////////////////////////////
		ss_log(__FUNCTION__."将要发送cpic cargo批改的异步请求，will doService");
		$xmlContent = "";
		$postXmlFileName = $af_path;

		$postXmlEncoding = "UTF-8";//GBK";
		$respXmlFileName = $ret_af_path;//批改返回报文存储路径
		$logFileName = S_ROOT."xml/log/cpic_cargo_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	
		ss_log(__FUNCTION__."logFileName: ".$logFileName);
	
		$type = "endorse";//批改
		
		$insurer_code = $policy_arr['insurer_code'];
		//批改之后的回调url
		$callBackURL = CALLBACK_URL;
					
		ss_log(__FUNCTION__.", policy_id: ".$policy_id);
		ss_log(__FUNCTION__."type: ".$type);
		ss_log(__FUNCTION__."insurer_code: ".$insurer_code);
		ss_log(__FUNCTION__."callBackURL: ".$callBackURL);
			
		$param_other = array(
							'url'=>$url,//保险公司地址
							'username'=>$userName,//          用户名
							'password'=>$password,//          用户密码
							'passNeedMd5'=>$passNeedMd5,//      密码是否需加密（y/yes表示需要，其他表示不加密）
							'classesCode'=>$policy_arr['classtype'],//		险种代码     请参考freight_dictionary_20091125.xls
							'checkCode'=>$checkCode,//         MD5校验码
							'typeCode'=>$endorsement_type,    //      批改类型代码  106 通用批改 104 费率批改  002 退保批改
							'xmlContent'=>$xmlContent,//		报文内容
							'postXmlFileName'=>$postXmlFileName,//	报文文件名
							'postXmlFileEncoding'=>$postXmlEncoding,//报文文件编码  
							'respXmlFileName'=> $respXmlFileName,    //存储返回报文文件名
							'respXmlFileEncoding'=>'UTF-8', // 返回报文存储文件编码格式
							'logFileName'=>$logFileName

						);
	
		ss_log(__FUNCTION__.", param_other: ".var_export($param_other,true));

		$jsonStr = json_encode($param_other);
		ss_log(__FUNCTION__.", jsonStr: ".$jsonStr);
		
		//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_cargo_endorse_to_java.json";
		$af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_cpic_cargo_endorse_to_java.json");
		file_put_contents($af_path,$jsonStr);
	
		//异步投保调用接口函数
		$successAccept = (string)$obj_java->doService(
											$insurer_code,//险种代码
											$type,//
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

//理赔接口报文生成
function gen_cpic_cargo_insurance_claims_xml()
{
	
}
//报案接口报文生成
function gen_cpic_cargo_insurance_report_xml()
{
	
}
//其他货运险的批单报文生成，同时也是退保接口
////001 通用 002费率 003全额退保 004部分退保
function gen_cpic_cargo_other_insurance_endorsement_xml($policy_attr, $endorsement_type)
{
	$transSn = getRandOnly_Id(0);
	$header='<HEAD>
	<!--唯一码 Y  STRING(20)-->
	<APPLYID>'.$transSn.'</APPLYID>
	<!--保单ID STRING(20)-->
	<POLICYID></POLICYID>
	<!--分公司代码 Y STRING(7)-->
	<UNITCODE>3010200</UNITCODE>
	<!--保单号 Y STRING(20)-->
	<POLICYNO>'.$policy_attr['policy_no'].'</POLICYNO>
	<!--险种代码 Y STRING(12)-->
	<CLASSESCODE>'.$policy_attr['classtype'].'</CLASSESCODE>
  </HEAD>';
	if($endorsement_type == '001')//通用批改
	{
		$xml_endorsement = '<?xml version="1.0" encoding="GBK"?>
<OTHERFRIEHGTENDORSE>
  '.$header.'
  <BODY>
	<!--通用批改必填-->
	<GENERALENDORSE>
		<!--被保人名称-->
		<INSURANTNAME></INSURANTNAME>
		<!--被保人地址-->
		<INSURANTADDRESS></INSURANTADDRESS>
		<!--被保人组织机构代码(身份证号)-->
		<INSURANTCERC></INSURANTCERC>
		<!--被保人地址邮编-->
		<POSTCODE>DHG</POSTCODE>
		<!--被保人联系人 -->
		<LINKMAN></LINKMAN>
		<!-- E-MAIL -->
		<EMAIL></EMAIL>
		<!-- 电话-->
		<TELEPHONE></TELEPHONE>
		<ITEMS>
			<ITEMCODE>LIAM43</ITEMCODE>
			<ITEMCODE>LIAM46</ITEMCODE>
		</ITEMS>
		<!-- 保险起期 必填 -->
		<POLICYBEGINDATE>2013-04-28</POLICYBEGINDATE>
		<!-- 保险止期 必填 -->
		<POLICYENDDATE>2014-04-27</POLICYENDDATE>
		<!-- 特别约定 -->
		<SPECIALTERM>SRWERTWETWETWERTWERTWERT</SPECIALTERM>
		<BUSINESS>
			<!-- 道路运输许可证号码 -->
			<ROADLICENSENUMBER>56756767</ROADLICENSENUMBER>
			<!--如果未批改，下送空 -->
			<!-- 承保车辆信息1 -->
			<VEHICLEINFORMATION>
				<!-- 车辆编号 Y -->
				<VEHICLEID></VEHICLEID>
				<!-- 运输货物名称 -->
				<GOODSNAME>5</GOODSNAME>
				<!-- 运输车辆核定载质量  *必填* -->
				<TRANSPORTVEHICLESAPPROVEDTOTAL>457</TRANSPORTVEHICLESAPPROVEDTOTAL>
				<!-- 行驶证车主  -->
				<VEHICLEOWNERS>4</VEHICLEOWNERS>
				<!-- 运输车辆号牌  *必填* -->
				<CARNUMBER>747</CARNUMBER>
				<!-- 运输车辆车架号/识别代码  *必填* -->
				<FRAMENUMBER>47</FRAMENUMBER>
				<!-- 发动机号码  -->
				<ENGINENO>457</ENGINENO>
				<!-- 批改类型 1.修改；Y  -->	
				<TYPE>1</TYPE>
			</VEHICLEINFORMATION>
			<!-- 业务类型  01-单车  05 -单程-->
			<BUSINESSTYPE></BUSINESSTYPE>
			<SINGLEBUSSINESS>
				  <!--起运日期-->
			      <SAILDATE>2015-06-26</SAILDATE>
			      <!--始发地-->
				  <STARTPLACE>11</STARTPLACE>
				  <!--目的地-->
			      <ENDPLACE>11</ENDPLACE>
			      <!--运单号-->
				  <DELIVERORDERNO>11</DELIVERORDERNO>
			</SINGLEBUSSINESS>
		</BUSINESS>
	</GENERALENDORSE>
  </BODY>
</OTHERFRIEHGTENDORSE>';
	}
	elseif($endorsement_type == '002')
	{
		$xml_endorsement = '<?xml version="1.0" encoding="GBK"?>
<OTHERFRIEHGTENDORSE>
  '.$header.'
  <BODY>
	<!--费率批改必填-->
	<FEEENDORSE>
	  <!-- 每次事故赔偿限额（元） -->
      <TIMEAMOUNT>344</TIMEAMOUNT>
	  <!-- 保单最新保险金额（元） -->
      <SUMAMOUNT>4</SUMAMOUNT>
	  <!-- 保单最新保费（元） -->
      <PREMIUM>44</PREMIUM>
	  <!-- 费率 -->
	  <RATE></RATE>
	  <!-- 每次事故免陪额（元） -->
      <EACHACCIDENTFRANCHISE>44</EACHACCIDENTFRANCHISE>	
	</FEEENDORSE>
  </BODY>
</OTHERFRIEHGTENDORSE>';
	}
	//相当于注销
	elseif($endorsement_type == '003')//退保批改
	{
		$xml_endorsement = '<?xml version="1.0" encoding="GBK"?>
<OTHERFRIEHGTENDORSE>
  '.$header.'
  <BODY>
	<!--退保批改必填-->
	<CANCELENDORSE>
		<!--退保原因说明 按短期费率-->
		<ENDORSETEXT>退保</ENDORSETEXT>
		<!--退保后剩余保费 如不填系统自动计算-->
		<CANCELPREMIUM>0</CANCELPREMIUM>
	</CANCELENDORSE>
  </BODY>
</OTHERFRIEHGTENDORSE>';
	}
	
	
	return $xml_endorsement;
}
//批单请求报文生成, 同时也是退保接口
function gen_cpic_cargo_insurance_endorsement_xml($policy_attr, $endorsement_type)
{
	$transSn = getRandOnly_Id(0);
	$classtype = ($policy_attr['attribute_type'] == 'internal_cargo') ? 1:2;
	if($endorsement_type == '106')//通用批改
	{
		$xml_endorsement = '<?xml version="1.0" encoding="GBK"?>
<FREIGHTCPIC>
	<DATAS>
		<DATA>
			<!--★ 唯一码 String(200)-->
			<ApplyId>'.$transSn.'</ApplyId>
			<!--★ 保单号 承保接口返回数据-->
			<POLICYNO>'.$policy_attr['policy_no'].'</POLICYNO>
			<!--★ 险种类型 1，国内货运险 2，进出口货运险-->
			<CLASSTYPE>'.$classtype.'</CLASSTYPE>
			<!--★ 险种代码。选值参考数据字典。同录单-->
			<CLASSCODE>'.$policy_attr['classtype'].'</CLASSCODE>
			<GENERALENDORSE>
				<!-- 投保人名称，长度不能大于200字符-->
				<APPLYNAME>蔡亮</APPLYNAME>
				<!-- 被保险人名称，长度不能大于200字符-->
				<INSURANTNAME>许宝</INSURANTNAME>
				<!--标记/发票号码/运单号，长度不能大于500字符-->
				<MARK>标记200130325.1</MARK>
				<!-- 包装及数量，长度不能大于500字符-->
				<QUANTITY>包装200130325.1</QUANTITY>
				<!-- 货物名称，长度不能大于500字符-->
				<ITEM>货物200130325.1</ITEM>
				<!-- 包装代码。选值参考数据字典。-->
				<PACKCODE>02</PACKCODE>
				<!-- 货物类型 大类-->
				<ITEMCODE>0803</ITEMCODE>
				<!-- 航行区域代码(★ 进出口必填)。选值参考数据字典。                     目的地-->
				<FLIGHTAREACODE>ASCN</FLIGHTAREACODE>
				<!-- 运输方式代码。选值参考数据字典。-->
				<KIND>1</KIND>
				<!-- 运输工具名称。长度不能大于30字符-->
				<KINDNAME>运200130325</KINDNAME>
				<!--航（班）次/车牌号。长度不能大于30字符-->
				<VOYNO>航200130325</VOYNO>
				<!-- 运输路线 自。长度不能大于80字符-->
				<STARTPORT>200130325自</STARTPORT>
				<!--运输路线 经-->
				<TRANSPORT1>200130325经</TRANSPORT1>
				<!--运输路线 到-->
				<ENDPORT>200130325到</ENDPORT>
				<!--理赔代理地-->
				<CLAIMAGENT>498534500</CLAIMAGENT>
				<!--起运日期 -->
				<SAILDATE>2013-04-09</SAILDATE>
				<!--★ 起保日期。起保日期不能晚于起运日期-->
				<EFFECTDATE>2013-04-08</EFFECTDATE>
				<!--赔款地点-->
				<CLAIMPAYPLACE>赔款200130325</CLAIMPAYPLACE>
                <!--★ 主险条款代码。选值参考数据字典。-->
			    <MAINITEMCODE>ZH</MAINITEMCODE>
			    <!--★ 主险条款内容。选值参考数据字典。-->
			    <ITEMCONTENT>国内水路、陆路货物运输保险条款综合险</ITEMCONTENT>
                <!--附加条款请参照数据字典填★ 进出口必填）-->
			    <ITEMADD>
				 <ITEMADDCODE>Z000001C</ITEMADDCODE>
				 <ITEMADDCONTENT>偷窃、提货不着险</ITEMADDCONTENT>
				 <ITEMADDCODE>Z000002C</ITEMADDCODE>
				 <ITEMADDCONTENT>罢工民变险</ITEMADDCONTENT>
			    </ITEMADD>
			    <!-- 免赔条件 -->
			    <FRANCHISE>免赔条件20130325.1</FRANCHISE>
				<!--特别约定-->
				<SPECIALIZE>特别约定20130325.1</SPECIALIZE>
				<!--投保说明 （注：本投保说明内容不显示在保单上，只作为投保说明。） 不能大于2000字符。-->
			    <COMMENTS>投保说明20130325.1</COMMENTS>
			</GENERALENDORSE>
		</DATA>
	</DATAS>
</FREIGHTCPIC>';
	}
	//批单修改费率 104 费率批改  002 退保批改
	elseif($endorsement_type == '104')
	{
		$xml_endorsement = '<?xml version="1.0" encoding="GBK"?>
<FREIGHTCPIC>
	<DATAS>
		<DATA>
			<!--★ 唯一码 String(200)-->
			<ApplyId>'.$transSn.'</ApplyId>
			<!--★ 保单号 承保接口返回数据-->
			<POLICYNO>'.$policy_attr['policy_no'].'</POLICYNO>
			<!--★ 险种类型 1，国内货运险 2，进出口货运险-->
			<CLASSTYPE>'.$classtype.'</CLASSTYPE>
			<!--★ 险种代码。选值参考数据字典。同录单-->
			<CLASSCODE>'.$policy_attr['classtype'].'</CLASSCODE>
			<!--费率批改必填-->
			<FEEENDORSE>
				<!-- 保险金额-->
				<AMOUNT>'.$policy_attr['amount'].'</AMOUNT>
				<!--费率-->
				<RATE>'.$policy_attr['rate'].'</RATE>
				<!-- 保费-->
				<PREMIUM>'.$policy_attr['premium'].'</PREMIUM>
				<!--发票金额 ★ 进出口必填-->
				<INVAMOUNT>'.$policy_attr['invamount'].'</INVAMOUNT>
				<!--加成比例 ★ 进出口必填-->
				<INCRATE>'.$policy_attr['incrate'].'</INCRATE>
			</FEEENDORSE>
		</DATA>
	</DATAS>
</FREIGHTCPIC>';
	}
	//相当于注销
	elseif($endorsement_type == '002')//退保批改
	{
		$xml_endorsement = '<?xml version="1.0" encoding="GBK"?>
<FREIGHTCPIC>
	<DATAS>
		<DATA>
			<!--★ 唯一码 String(200)-->
			<ApplyId>'.$transSn.'</ApplyId>
			<!--★ 保单号 承保接口返回数据-->
			<POLICYNO>'.$policy_attr['policy_no'].'</POLICYNO>
			<!--★ 险种类型 1，国内货运险 2，进出口货运险-->
			<CLASSTYPE>'.$classtype.'</CLASSTYPE>
			<!--★ 险种代码。选值参考数据字典。同录单-->
			<CLASSCODE>'.$policy_attr['classtype'].'</CLASSCODE>
			<!--退保批改必填-->
			<CANCELENDORSE>
				<!--★ 退保原因说明-->
				<ENDORSETEXT>信息错误</ENDORSETEXT>
			</CANCELENDORSE>
		</DATA>
	</DATAS>
</FREIGHTCPIC>';
	}
	
	
	return $xml_endorsement;
}
//核保查询报文生成
function gen_cpic_cargo_query_policy_xml($policy_attr, $type='insure_accept')
{
	$applyendorseno = ($type == 'insure_accept')? '':$policy_attr['applyendorseno'];
	 
	 if($policy_attr['attribute_type'] == 'other_cargo')
	 {
	 	 //承保的或者是批单的查询报文
	$query_xml = '<?xml version="1.0" encoding="UTF-8"?>
<ROOT>
	<CONFIG>
		<!--TYPE为IN表示传入B2B数据，OUT为B2B返回数据-->
		<!--WORKTYPE为0表示新增，为1表示新增返回,为2表示查询，为3表示查询返回-->
		<TYPE>IN</TYPE>
		<WORKTYPE>2</WORKTYPE>
	</CONFIG>
	<DATA>
		<!--主要信息-->
		<POLICY>
			<!-- 必填 保险公司分公司代码？-->
			<UNITCODE>3010200</UNITCODE>
			<!-- 必填 ，投保单号-->
			<APPLYNO>'.$policy_attr['cast_policy_no'].'</APPLYNO>
			<!-- 查询投保单核保结果可为空 -->
			<APPLYENDORSENO>'.$applyendorseno.'</APPLYENDORSENO>
		</POLICY>
	</DATA>
</ROOT>';
	 }
	 else
	 {
	 	 //承保的或者是批单的查询报文
	$query_xml = '<?xml version="1.0" encoding="UTF-8"?>
<ROOT>
	<CONFIG>
		<!--TYPE为IN表示传入B2B数据，OUT为B2B返回数据-->
		<!--WORKTYPE为0表示新增，为1表示新增返回,为2表示查询，为3表示查询返回-->
		<TYPE>IN</TYPE>
		<WORKTYPE>2</WORKTYPE>
	</CONFIG>
	<DATA>
		<!--主要信息-->
		<POLICY>
			<!-- 必填 保险公司分公司代码？-->
			<UNITCODE>3010100</UNITCODE>
			<!-- 必填 ，投保单号-->
			<APPLYNO>'.$policy_attr['cast_policy_no'].'</APPLYNO>
			<!-- 查询投保单核保结果可为空 -->
			<APPLYENDORSENO>'.$applyendorseno.'</APPLYENDORSENO>
		</POLICY>
	</DATA>
</ROOT>';
	 }
	
	return $query_xml;
}


//承保请求报文生成
function gen_cpic_cargo_post_policy_xml($attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject)//默认为核保请求
{
	
	if($attribute_type == 'internal_cargo'
	||$attribute_type == 'entrance_cargo')
	{
		$xml=gen_internal_entrance_cargo_xml($attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject);
	}
	elseif($attribute_type == 'other_cargo')
	{
		$xml = gen_other_cargo_xml($attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject);
	}
	
	return $xml;
}
//其他货运险
function gen_other_cargo_xml($attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject)
{
	global $_SGLOBAL;
	$transSn = getRandOnly_Id(0);
	$insurant = $list_subject[0]['list_subject_insurant'][0];
	
	$attribute_id = $policy_arr['attribute_id'];
	$limit_note='';
	if($attribute_id)
	{

		$sql = "SELECT * FROM t_insurance_product_attribute WHERE attribute_id='$attribute_id' LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$product_attribute = $_SGLOBAL['db']->fetch_array($query);
		//特别约定
		if(defined('IS_NO_GBK'))
		{
			$limit_note = trim($product_attribute['limit_note']);
			$limit_note = '<![CDATA['.$limit_note.']]>';
			
			ss_log("after limit_note: ".$limit_note);
		}
		else
		{
			$limit_note =  mb_convert_encoding($product_attribute['limit_note'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}

	}//$attribute_id
	
	//车辆信息报文，可能有多个车辆
	 $list_vechile = '<!-- 承保车辆信息1 -->
      <VehicleInformation>
	    <!-- 运输货物名称 maxLength：40-->
        <GoodsName>'.$policy_arr['GoodsName'].'</GoodsName>
		<!-- 运输车辆核定载质量  *必填* -->
        <TransportVehiclesApprovedTotal>'.$policy_arr['TransportVehiclesApprovedTotal'].'</TransportVehiclesApprovedTotal>
		<!-- 行驶证车主  maxLength：100-->
        <VehicleOwners>'.$policy_arr['VehicleOwners'].'</VehicleOwners>
		<!-- 运输车辆号牌  maxLength：20 *必填* -->
        <CarNumber>'.$policy_arr['CarNumber'].'</CarNumber>
		<!-- 运输车辆车架号/识别代码 maxLength：20 *必填* -->
        <FrameNumber>'.$policy_arr['FrameNumber'].'</FrameNumber>
		<!-- 发动机号码 maxLength：100 -->
        <EngineNo>'.$policy_arr['EngineNo'].'</EngineNo>
      </VehicleInformation>';
	
	//mod by zhangxi, 20150629, 这个得要
	//$additional_ins='';
	$additional_ins = '<!-- 险条款，只送主险  -->
	<Items>
      <ItemCode>LIAM43</ItemCode>
    </Items>';
	$SingleBusiness='';
	//单词的情况
	if($policy_arr['BusinessType'] == '05')
	{
		$SingleBusiness='<!--单程业务必填-->
	<SingleBussiness>
	  <!--起运日期-->
      <SailDate>'.$policy_arr['SailDate'].'</SailDate>
      <!--始发地-->
	  <StartPlace>'.$policy_arr['StartPlace'].'</StartPlace>
	  <!--目的地-->
      <EndPlace>'.$policy_arr['EndPlace'].'</EndPlace>
      <!--运单号-->
	  <DeliverorderNo>'.$policy_arr['DeliverorderNo'].'</DeliverorderNo>
    </SingleBussiness>';
	}
	
	//其他货运险中每个产品都不一样
	$xml = '<?xml version="1.0" encoding="GBK"?>
<HWCApply>
  <Head>
    <!-- ApplyId   maxLength：40位，唯一性 标识码 *必填* -->
    <ApplyId>'.$transSn.'</ApplyId>
  </Head>
  <DATA>
      <!-- 被保险人名称  maxLength：500*必填* -->
      <InsuredName>'.$insurant['fullname'].'</InsuredName>
	  <!-- 被保险人地址 maxLength：500  *必填* -->
      <InsuredAddress>'.$insurant['address'].'</InsuredAddress>
	  <!-- 邮编  maxLength：6 *必填* -->
      <PostCode>'.$insurant['zipcode'].'</PostCode>
      <!-- 被保险人组织结构代码（身份证号）  maxLength：20 *必填* -->
	  <CretCode>'.$insurant['certificates_code'].'</CretCode>
	  <!-- 联系人  maxLength：30 *必填* -->
	  <LinkMan>'.$user_info_applicant['fullname'].'</LinkMan>
	  <!-- E-MAIL  maxLength：20 *必填* -->
	  <EMail>'.$user_info_applicant['email'].'</EMail>
	  <!-- 电话   maxLength：20 *必填* -->
	  <Telephone>'.$user_info_applicant['mobiletelephone'].'</Telephone>
    <Business>
	  <!-- 业务类型  01-单车 05-单次 *必填* -->
      <BusinessType>'.$policy_arr['BusinessType'].'</BusinessType>
	  <!-- 道路运输许可证号码  maxLength：60 *必填* -->
      <RoadLicenseNumber>'.$policy_arr['RoadLicenseNumber'].'</RoadLicenseNumber>
	  <!-- 累计承保车辆数量   *必填* -->
      <TotalInsuredCar>'.$policy_arr['TotalInsuredCar'].'</TotalInsuredCar>
	  '.$list_vechile.'
    </Business>
    '.$SingleBusiness.'
	<!-- 费用信息 -->
    <PremiumInformation>
	  <!-- 无用节点 -->
      <EachAccidentFranchise>0</EachAccidentFranchise>
	  <!-- 每次事故赔偿限额（元）精确小数点后两位 -->
      <TimeAmount>'.$policy_arr['TimeAmount'].'</TimeAmount>
	  <!-- 累计赔偿限额（元）精确小数点后两位 -->
      <SumAmount>'.$policy_arr['SumAmount'].'</SumAmount>
	  <!-- 保费率  -->
      <Rate>'.$policy_arr['Rate'].'</Rate>
      <Premium>'.$policy_arr['Premium'].'</Premium>
    </PremiumInformation>
    <Deductible>'.$policy_arr['EachAccidentFranchise'].'</Deductible>
    '.$additional_ins.'
	<!-- 保险起期 必填 yyyy-mm-dd -->
    <ApplyBeginDate>'.$policy_arr['start_date'].'</ApplyBeginDate>
	<!-- 保险止期 必填yyyy-mm-dd  -->
    <ApplyEndDate>'.$policy_arr['end_date'].'</ApplyEndDate>
	<!-- 特别约定 maxLength：2000-->
    <Specialterm>'.$limit_note.'</Specialterm>
  </DATA>
</HWCApply>';
	return $xml;
}



//进出口货运险投保请求报文生成
function gen_internal_entrance_cargo_xml($attribute_type,
									$attribute_code,
									$policy_arr,//保单相关信息
									$user_info_applicant,//投保人信息
									$list_subject)
{
	global $_SGLOBAL;
	$transSn = getRandOnly_Id(0);
	$CLASSESTYPE = ($attribute_type == 'internal_cargo') ? 1:2;
	$insurant = $list_subject[0]['list_subject_insurant'][0];
	
	$attribute_id = $policy_arr['attribute_id'];
	$limit_note='';
	if($attribute_id)
	{

		$sql = "SELECT * FROM t_insurance_product_attribute WHERE attribute_id='$attribute_id' LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$product_attribute = $_SGLOBAL['db']->fetch_array($query);
		//特别约定
		if(defined('IS_NO_GBK'))
		{
			$limit_note = trim($product_attribute['limit_note']);
			//$limit_note = '<![CDATA['.$limit_note.']]>';
			
			ss_log("after limit_note: ".$limit_note);
		}
		else
		{
			$limit_note =  mb_convert_encoding($product_attribute['limit_note'], "GBK", "UTF-8" ); //已知原编码为UTF-8, 转换为GBK
		}

	}//$attribute_id
	
	if($attribute_type == 'internal_cargo')
	{
		$xml='<?xml version="1.0" encoding="GBK"?>
	<FREIGHTCPIC>
	    <!--标为★号的节点为必填项-->
		<HEADER> 
			<!--★ 唯一码 String(200)-->
			<ApplyId>'.$transSn.'</ApplyId>
			<!--★ CLASSESTYPE大险种类别，1国内货运险；2进出口货运险-->
			<CLASSESTYPE>'.$CLASSESTYPE.'</CLASSESTYPE>
		</HEADER>
		<DATAS>
			<DATA>
				<!--★ 投保人名称，长度不能大于200字符-->
				<APPLYNAME>'.$user_info_applicant['group_name'].'</APPLYNAME>
				<!--★ 被保险人名称，长度不能大于200字符-->
				<INSURANTNAME>'.$insurant['fullname'].'</INSURANTNAME>
				<!-- 被保险人电话，长度不能大于20-->
				<INSURANTTEL>'.$insurant['mobiletelephone'].'</INSURANTTEL>
				<!-- 被保险人邮箱，长度不能大于60-->
				<INSURANTEMAIL>'.$insurant['email'].'</INSURANTEMAIL>
				<!--★ 险种代码。选值参考数据字典。-->
				<CLASSTYPE>'.$policy_arr['classtype'].'</CLASSTYPE>
				<!--标记/发票号码/运单号，长度不能大于500字符-->
				<MARK>'.$policy_arr['mark'].'</MARK>
				<!--★ 包装及数量，长度不能大于500字符-->
				<QUANTITY>'.$policy_arr['quantity'].'</QUANTITY>
				<!--★ 货物名称，长度不能大于500字符-->
				<ITEM>'.$policy_arr['item'].'</ITEM>
				<!--★ 包装代码。选值参考数据字典。-->
				<PACKCODE>'.$policy_arr['packcode'].'</PACKCODE>
				<!--★ 货物类型代码。选值参考数据字典。-->
				<ITEMCODE>'.$policy_arr['itemcode'].'</ITEMCODE>			
				<!-- 航行区域代码(★ 进出口必填)。选值参考数据字典。-->
				<FLIGHTAREACODE>'.$policy_arr['flightareacode'].'</FLIGHTAREACODE>
				<!--★ 运输方式代码。选值参考数据字典。-->
				<KIND>'.$policy_arr['kind'].'</KIND>
				<!--★ 运输工具名称。长度不能大于30字符-->
				<KINDNAME>'.$policy_arr['kindname'].'</KINDNAME>
				<!--航（班）次。长度不能大于30字符-->
				<VOYNO>'.$policy_arr['voyno'].'</VOYNO>
				<!--★ 运输路线 自。长度不能大于80字符-->
				<STARTPORT>'.$policy_arr['startport'].'</STARTPORT>
				<!--运输路线 经-->
				<TRANSPORT1>'.$policy_arr['transport1'].'</TRANSPORT1>
				<!--★ 运输路线到。长度不能大于80字符-->
				<ENDPORT>'.$policy_arr['endport'].'</ENDPORT>
				<!--★ 主险条款代码。选值参考数据字典。-->
				<MAINITEMCODE>'.$policy_arr['mainitemcode'].'</MAINITEMCODE>
				<!--★ 主险条款内容。选值参考数据字典。-->
				<ITEMCONTENT>'.$policy_arr['itemcontent'].'</ITEMCONTENT>
				<!--★ 投保币种代码。选值参考数据字典。-->
				<CURRENCYCODE>'.$policy_arr['currencycode'].'</CURRENCYCODE>
				<!-- 价格条件只能为CIF。（★ 进出口必填）-->
				<PRICECOND>'.$policy_arr['pricecond'].'</PRICECOND>
				<!-- 发票金额。（★ 进出口必填）-->
				<INVAMOUNT>'.$policy_arr['invamount'].'</INVAMOUNT>
				<!--加成比例。（★ 进出口必填）-->
				<INCRATE>'.$policy_arr['incrate'].'</INCRATE>
				<!--★ 保险金额。计算公式：保险金额 = 发票金额*(1+加成比例)-->
				<AMOUNT>'.$policy_arr['amount'].'</AMOUNT>
				<!--★ 费率。不能小于核定最小费率。-->
				<RATE>'.$policy_arr['rate'].'</RATE>
				<!--★ 保费。计算公式：保费= 保险金额*费率。保留两位有效数字。-->
				<PREMIUM>'.$policy_arr['premium'].'</PREMIUM>
				<!--★ 保费币种，默认值为人民币。取值范围请参考附件：币种代码信息.txt-->
				<FCURRENCYCODE>'.$policy_arr['fcurrencycode'].'</FCURRENCYCODE>
				<!--赔偿币种代码。（★ 进出口必填） 选值参考数据字典。-->
				<CLAIMCURRENCYCODE>'.$policy_arr['fcurrencycode'].'</CLAIMCURRENCYCODE>
				<!-- 赔款偿付地。（★ 进出口必填）-->
				<CLAIMPAYPLACE>'.$policy_arr['claimpayplace'].'</CLAIMPAYPLACE>
				<!--★ 起保日期。起保日期不能晚于起运日期-->
				<EFFECTDATE>'.$policy_arr['effectdate'].'</EFFECTDATE>
				<!--★ 起运日期-->
				<SAILDATE>'.$policy_arr['saildate'].'</SAILDATE>
				<!--★ 免赔条件-->
				<FRANCHISE>'.$policy_arr['franchise'].'</FRANCHISE>
				<!--特别约定 国内货运险选填，进出口可忽略 填了就不能自动核保了-->
				<SPECIALIZE>'.$limit_note.'</SPECIALIZE>
				<!--投保说明 （注：本投保说明内容不显示在保单上，只作为投保说明。） 不能大于2000字符。-->
				<COMMENTS>'.$policy_arr['comments'].'</COMMENTS>
				<!--自定义查询编码-->
				<USERNO></USERNO>
				<!--销售人员信息-->
				<SALERINFOS>
					<!--销售人员姓名 -->
					<SALERINFONAME></SALERINFONAME> 
					<!--销售人员 -->
					<SALERINFOCERT></SALERINFOCERT> 
				</SALERINFOS>
				<!--是否需要发票-->
				<IFBILL>1</IFBILL>
				<!--发票寄送地址-->
				<BILLADDRESS>1</BILLADDRESS>
			</DATA>
		</DATAS>
	</FREIGHTCPIC>';
	//同时约定： 
	}
	elseif($attribute_type == 'entrance_cargo')
	{
		$xml='<?xml version="1.0" encoding="GBK"?>
	<FREIGHTCPIC>
	    <!--标为★号的节点为必填项-->
		<HEADER> 
			<!--★ 唯一码 String(200)-->
			<ApplyId>'.$transSn.'</ApplyId>
			<!--★ CLASSESTYPE大险种类别，1国内货运险；2进出口货运险-->
			<CLASSESTYPE>'.$CLASSESTYPE.'</CLASSESTYPE>
		</HEADER>
		<DATAS>
			<DATA>
				<!--★ 投保人名称，长度不能大于200字符-->
				<APPLYNAME>'.$user_info_applicant['group_name'].'</APPLYNAME>
				<!--★ 被保险人名称，长度不能大于200字符-->
				<INSURANTNAME>'.$insurant['fullname'].'</INSURANTNAME>
				<!-- 被保险人电话，长度不能大于20-->
				<INSURANTTEL>'.$insurant['mobiletelephone'].'</INSURANTTEL>
				<!-- 被保险人邮箱，长度不能大于60-->
				<INSURANTEMAIL>'.$insurant['email'].'</INSURANTEMAIL>
				<!--★ 险种代码。选值参考数据字典。-->
				<CLASSTYPE>'.$policy_arr['classtype'].'</CLASSTYPE>
				<!--标记/发票号码/运单号，长度不能大于500字符-->
				<MARK>'.$policy_arr['mark'].'</MARK>
				<!--★ 包装及数量，长度不能大于500字符-->
				<QUANTITY>'.$policy_arr['quantity'].'</QUANTITY>
				<!--★ 货物名称，长度不能大于500字符-->
				<ITEM>'.$policy_arr['item'].'</ITEM>
				<!--★ 包装代码。选值参考数据字典。-->
				<PACKCODE>'.$policy_arr['packcode'].'</PACKCODE>
				<!--★ 货物类型代码。选值参考数据字典。-->
				<ITEMCODE>'.$policy_arr['itemcode'].'</ITEMCODE>			
				<!-- 航行区域代码(★ 进出口必填)。选值参考数据字典。-->
				<FLIGHTAREACODE>'.$policy_arr['flightareacode'].'</FLIGHTAREACODE>
				<!--★ 运输方式代码。选值参考数据字典。-->
				<KIND>'.$policy_arr['kind'].'</KIND>
				<!--★ 运输工具名称。长度不能大于30字符-->
				<KINDNAME>'.$policy_arr['kindname'].'</KINDNAME>
				<!--航（班）次。长度不能大于30字符-->
				<VOYNO>'.$policy_arr['voyno'].'</VOYNO>
				<!--★ 运输路线 自。长度不能大于80字符-->
				<STARTPORT>'.$policy_arr['startport'].'</STARTPORT>
				<!--运输路线 经-->
				<TRANSPORT1>'.$policy_arr['transport1'].'</TRANSPORT1>
				<!--★ 运输路线到。长度不能大于80字符-->
				<ENDPORT>'.$policy_arr['endport'].'</ENDPORT>
				<!-- 理赔代理地代码。（★ 进出口必填）-->
				<CLAIMAGENT>'.$policy_arr['claimagent'].'</CLAIMAGENT>
				<!--★ 主险条款代码。选值参考数据字典。-->
				<MAINITEMCODE>'.$policy_arr['mainitemcode'].'</MAINITEMCODE>
				<!--★ 主险条款内容。选值参考数据字典。-->
				<ITEMCONTENT>'.$policy_arr['itemcontent'].'</ITEMCONTENT>
	            <!--附加条款请参照数据字典填写英文条款代码和英文名称（★ 进出口必填）-->
				<ITEMADD>
					<ITEMADDCODE>Z000001E</ITEMADDCODE>
					<ITEMADDCONTENT>偷窃、提货不着险</ITEMADDCONTENT>
					<ITEMADDCODE>Z000002C</ITEMADDCODE>
					<ITEMADDCONTENT>罢工民变险</ITEMADDCONTENT>
					<ITEMADDCODE>Z000003C</ITEMADDCODE>
					<ITEMADDCONTENT>淡水雨淋险</ITEMADDCONTENT>
				</ITEMADD>
				<!--★ 投保币种代码。选值参考数据字典。-->
				<CURRENCYCODE>'.$policy_arr['currencycode'].'</CURRENCYCODE>
				<!-- 价格条件只能为CIF。（★ 进出口必填）-->
				<PRICECOND>'.$policy_arr['pricecond'].'</PRICECOND>
				<!-- 发票金额。（★ 进出口必填）-->
				<INVAMOUNT>'.$policy_arr['invamount'].'</INVAMOUNT>
				<!--加成比例。（★ 进出口必填）-->
				<INCRATE>'.$policy_arr['incrate'].'</INCRATE>
				<!--★ 保险金额。计算公式：保险金额 = 发票金额*(1+加成比例)-->
				<AMOUNT>'.$policy_arr['amount'].'</AMOUNT>
				<!--★ 费率。不能小于核定最小费率。-->
				<RATE>'.$policy_arr['rate'].'</RATE>
				<!--★ 保费。计算公式：保费= 保险金额*费率。保留两位有效数字。-->
				<PREMIUM>'.$policy_arr['premium'].'</PREMIUM>
				<!--★ 保费币种，默认值为人民币。取值范围请参考附件：币种代码信息.txt-->
				<FCURRENCYCODE>'.$policy_arr['fcurrencycode'].'</FCURRENCYCODE>
				<!--赔偿币种代码。（★ 进出口必填） 选值参考数据字典。-->
				<CLAIMCURRENCYCODE>'.$policy_arr['claimcurrencycode'].'</CLAIMCURRENCYCODE>
				<!-- 赔款偿付地。（★ 进出口必填）-->
				<CLAIMPAYPLACE>'.$policy_arr['claimpayplace'].'</CLAIMPAYPLACE>
				<!--★ 起保日期。起保日期不能晚于起运日期-->
				<EFFECTDATE>'.$policy_arr['effectdate'].'</EFFECTDATE>
				<!--★ 起运日期-->
				<SAILDATE>'.$policy_arr['saildate'].'</SAILDATE>
				<!--★ 免赔条件-->
				<FRANCHISE>'.$policy_arr['franchise'].'</FRANCHISE>
				<!--特别约定 国内货运险选填，进出口可忽略-->
				<SPECIALIZE></SPECIALIZE>
				<!--投保说明 （注：本投保说明内容不显示在保单上，只作为投保说明。） 不能大于2000字符。-->
				<COMMENTS></COMMENTS>
				<!--自定义查询编码-->
				<USERNO>自定义查询编码</USERNO>
				<!--销售人员信息-->
				<SALERINFOS>
					<!--销售人员姓名 -->
					<SALERINFONAME></SALERINFONAME> 
					<!--销售人员 -->
					<SALERINFOCERT></SALERINFOCERT> 
				</SALERINFOS>
				<!--是否需要发票-->
				<IFBILL>1</IFBILL>
				<!--发票寄送地址-->
				<BILLADDRESS>1</BILLADDRESS>
			</DATA>
		</DATAS>
	</FREIGHTCPIC>';
	}
	
	
	return $xml;
}
//通过查询接口获取电子保单的方式，查询有承保查询和批单查询
function query_policyfile_cpic_cargo($policy_arr, 
									$use_async,
									$readfile=false,//是否同时向前端展示保单文件
									$type='insure_accept')//默认承保查询，可能有批单的查询
{
	global $_SGLOBAL, $java_class_name_cpic_cargo;
	/////////////////////////////////////////////////////////
	ss_log("into ".__FUNCTION__);
	
	$policy_id = $policy_arr['policy_id'];
	$orderNum = $policy_arr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_arr['order_sn'];
	//生成承保或者是批单的查询报文
	$strxml = gen_cpic_cargo_query_policy_xml($policy_arr, $type);
	
	ss_log(__FUNCTION__.", after gen_cpic_cargo_query_policy_xml,strxml=".$strxml);

	$strxml = trim($strxml);
	if(empty($strxml))
	{		

		$result = 112;
		$retmsg = "gen strxml null!";
		ss_log(__FUNCTION__.", ".$retmsg);
		
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
		
		return $result_attr;
		
	}
	
	/////////////////////////////////////////////////////////////////////////////////////
	if($type == 'insure_accept')
	{
		//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_cargo_policy_query.xml";
		$af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_cpic_cargo_policy_query.xml");
	}
	else
	{
		
		//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_cargo_endorse_query.xml";
		$af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_cpic_cargo_endorse_query.xml");
	}
	
	file_put_contents($af_path,$strxml);//核保文件先存起来
	
	//start add by wangcya, 20150209,把携带序列号的也保存起来
	$UUID = $policy_arr['order_num'];
	if($type == 'insure_accept')
	{
		//$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_cpic_cargo_policy_query.xml";
		$af_path_serial = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_".$UUID."_cpic_cargo_policy_query.xml");
	}
	else
	{
		//$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_cpic_cargo_endorse_query.xml";
		$af_path_serial = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_".$UUID."_cpic_cargo_endorse_query.xml");
	}
	
	file_put_contents($af_path_serial,$strxml);

	//同步查询
	if(!$use_async)
	{
		$java_class_name = $java_class_name_cpic_cargo;//同步
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_cpic_cargo:$java_class_name;
	//end add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
	
	$obj_java = create_java_obj($java_class_name);
	if(!$obj_java)
	{
		$result = 112;
		$retmsg = "create_java_obj fail!";
		ss_log(__FUNCTION__.", ".$retmsg);
			
		$result_attr = array(	'retcode'=>$result,
				'retmsg'=> $retmsg
		);
			
		return $result_attr;
	}
			
	///////////////////////////////////////////////////////////////////////////////////////////
	if($type == 'insure_accept')
	{
		//$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cargo_cpic_policy_query_ret.xml";
		$ret_af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_cargo_cpic_policy_query_ret.xml");
	}
	else
	{
		//$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cargo_cpic_endorse_query_ret.xml";
		$ret_af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_cargo_cpic_endorse_query_ret.xml");
	}
	ss_log(__FUNCTION__.", ".$ret_af_path);
	
	$url = URL_CPIC_CARGO_POST_POLICY;//
	ss_log(__FUNCTION__."url: ".$url);
	//查询的返回类型
	if($type=='insure_accept')
	{
		$query_ret_type = 'querypolicystatus';
	}
	else
	{
		$query_ret_type = 'querypolicystatus_endorse';
	}
	

	$userName = USERNAME_CPIC_CARGO;
	$password = PASSWORD_CPIC_CARGO;
	$passNeedMd5 = 'n';
	$xmlContent = '';
	$postXmlFileName = $af_path;
	$postXmlFileEncoding = 'UTF-8';
	$checkCode=CHECKCODE_CPIC_CARGO;
	if(!$use_async)//同步查询处理流程
	{
		global $LOGFILE_CPIC_CARGO;

		//进行核保调用处理
		ss_log(__FUNCTION__.", cpic cargo will java check policy,同步查询开始");
		
		$strxml_query_policy_ret = $return_data = (string)$obj_java->queryPolicyStatus(
																					$url,//服务地址
																					$userName ,//投保报文
																					$password,//密码
																					$passNeedMd5,//密码是否需要md5加密（y/yes是需要，其他不需要）
																					$policy_arr['classtype'],//险种代码
																					$checkCode,//MD5校验码
																					$xmlContent,//报文内容
																					$postXmlFileName,//存储报文文件名称
																					$postXmlFileEncoding,//存储报文文件编码格式
																					$LOGFILE_CPIC_CARGO
																					);

		ss_log(__FUNCTION__." after post call function queryPolicyStatus");

		//////////////////////////////////////////////////////////
		if(!empty($strxml_query_policy_ret))//查询完了的返回处理
		{
			ss_log(__FUNCTION__." cpic cargo will process query return data");
		
			file_put_contents($ret_af_path, $strxml_query_policy_ret);
				
			//////////////////////////////////////////////////////////////////////////
			$result_attr = process_return_xml_data_cpic_cargo(
														 	$policy_id,
									                      	$orderNum,
															$order_sn,
															$return_data,
															NULL,
															$query_ret_type,
															$readfile,//
															false
															);
				
			///////////////////////////////////////////////////////////////////////
			return $result_attr;
		
		}
		else
		{
			//$retmsg = iconv('GB2312', 'UTF-8', $retmsg); //将字符串的编码从GB2312转到UTF-8
			$retcode = 110;
			$retmsg = "result_attr return date is null!";
			ss_log(__FUNCTION__.", ".$retmsg);
				
			$result_attr = array('retcode'=>$retcode,
					'retmsg'=> $retmsg
			);
				
			return $result_attr;
		}
		
	
	}
	else//异步查询情况
	{//USE_ASYN_JAVA
		//////////////////////////////////////////
		ss_log(__FUNCTION__."将要发送cpic cargo查询的异步请求，will doService");
		$xmlContent = "";
		$postXmlFileName = $af_path;

		$postXmlEncoding = "UTF-8";//GBK";
		$respXmlFileName = $ret_af_path;//承保返回报文存储路径
		$logFileName = S_ROOT."xml/log/cpic_cargo_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	
		ss_log(__FUNCTION__."logFileName: ".$logFileName);
	
		$type = $query_ret_type;//查询
		
		$insurer_code = $policy_arr['insurer_code'];
		//投保之后的回调url
		$callBackURL = CALLBACK_URL;
					
		ss_log(__FUNCTION__.", policy_id: ".$policy_id);
		ss_log(__FUNCTION__."type: ".$type);
		ss_log(__FUNCTION__."insurer_code: ".$insurer_code);
		ss_log(__FUNCTION__."callBackURL: ".$callBackURL);
			
		$param_other = array(
							"url"=>$url ,//投保保险公司服务地址
							"username"=>$userName,
							"password"=>$password,
							"passNeedMd5"=>$passNeedMd5,
							"classesCode"=>$policy_arr['classtype'],
							"checkCode"=>$checkCode,
							"xmlContent"=>$xmlContent ,//核保请求报文
							"postXmlFileName"=>$postXmlFileName ,//投保报文内容
							"postXmlFileEncoding"=>$postXmlEncoding,//投保报文文件
							"respXmlFileName"=>$respXmlFileName,//返回报文保存全路径
							"respXmlFileEncoding"=>"UTF-8",
							"logFileName"=>$logFileName//日志文件
						);
	
		ss_log(__FUNCTION__.", param_other: ".var_export($param_other,true));

		$jsonStr = json_encode($param_other);
		ss_log(__FUNCTION__.", jsonStr: ".$jsonStr);
		if($type=='insure_accept')
		{
			//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_cargo_policy_query_to_java.json";
			$af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_cpic_cargo_policy_query_to_java.json");
		}
		else
		{
			//$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_cargo_endorse_query_to_java.json";
			$af_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_cpic_cargo_endorse_query_to_java.json");
		}	
		file_put_contents($af_path,$jsonStr);
	
		//异步投保调用接口函数
		$successAccept = (string)$obj_java->doService(
														$insurer_code,//险种代码
														$type,//
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

	/////////////////////////////////////////////////////////////
	return array('retcode'=>$result,
				'retmsg'=> $retmsg
				);
}

function process_return_xml_data_cpic_cargo(
									 	$policy_id,
				                      	$orderNum,
										$order_sn,
										$return_data,
										$obj_java = NULL,
										$ret_type = 'insure_accept',//返回的类型，是承保返回还是查询，批单等返回
										$readfile=false,
										$asnyc=true//默认是异步
										)
{
	global $_SGLOBAL;
	
	ss_log("into function: ".__FUNCTION__);
	////////////////////////////////////////////////////////////////////////////
	ss_log(__FUNCTION__.", return org data: ".$return_data);
	
	

	$wheresql = "order_num='$orderNum'";//根据这个找到其对应的保单存放的数据
	$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
	$query = $_SGLOBAL['db']->query($sql);
	$policy_attr = $_SGLOBAL['db']->fetch_array($query);
	//其他货运险和国内货运险的返回字段不一样，得根据不同的险种做不同的处理	
	$attribute_type = $policy_attr['attribute_type'];
	$arr_result = parse_xml_data_to_array_cpic_cargo($return_data , $ret_type, $asnyc, $attribute_type);
	
	if(isset($arr_result['errorCode']) && !empty($arr_result['errorCode']))
	{
		//更新错误码到数据库中
		ss_log(__FUNCTION__.", erro happened, errorCode=".$arr_result['errorCode'].", errorMsg=".$arr_result['errorMsg']);
		updatetable('insurance_policy',
							array("errorCode"=>$arr_result['errorCode'],
									"errorMsg"=>$arr_result['errorMsg']),
							array('policy_id'=>$policy_id)
							);
		if($ret_type == 'endorse')
		{
			return array("retcode"=>'110',
						"retmsg"=>'批单请求失败'
							);
		}
		else
		{
			//这个返回码不动,原来是0，现在还返回零，就是注销成功了。就有问题。
			return array("retcode"=>$policy_attr['ret_code'],
						"retmsg"=>$policy_attr['ret_msg']
							);
		}
		
	}
	//ss_log(__FUNCTION__.", arr_result=".var_export($arr_result,true));
	if($ret_type== "insure_accept"//承保返回处理
	  )
	{
		if(isset($arr_result['STATUS']) && $arr_result['STATUS'] == 10 )//保单已经生效，而且能够获取电子保单信息
		{
			//存储保单号到数据库中
			//status,保单的状态：0 保存但是未提交；1 投保成功；2 注销了，注销后不能再次提交。
			
			if($policy_attr['policy_id'])//old
			{
				$policy_id = $policy_attr['policy_id'];
				ss_log(__FUNCTION__.", return policyNo: ".$arr_result['POLICYNO']." order_sn: ".$order_sn);
				
				$policy_no = $arr_result['POLICYNO'];
				$cast_policy_no = $arr_result['APPLYNO'];
				//在这里更改该保单状态,存储保险公司返回的保单号，同时保存电子保单url到数据库中
				//同时也要保存投保单号，用于后续可能的查询
				updatetable('insurance_policy',
								array('policy_status'=>'insured',
										'cast_policy_no'=>$cast_policy_no,
										'policy_no'=>$policy_no),
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
			$policy_attr['policy_no'] = $policy_no;
			$pdf_filename = S_ROOT."xml/message/".$policy_no."_cpic_cargo_policy.pdf";
			
			$policy_attr['readfile'] = $readfile;//
			//进行电子保单保存操作
			return get_policy_file_cpic_cargo($policy_attr,
					                       $pdf_filename,
					                       $obj_java,
					                       $arr_result);
				
		}
		elseif(isset($arr_result['STATUS']) && $arr_result['STATUS'] == 7)//待人工审核,循环查询接口还是使用crontab会好些？
		{
			ss_log(__FUNCTION__.", 承保成功返回，人工审核中");
			ss_log(__FUNCTION__.", APPLYNO: ".$arr_result['APPLYNO']." order_sn: ".$order_sn);
					
			//投保单号需要存储起来先
			$setarr = array(
						'cast_policy_no'=>$arr_result['APPLYNO']);
			updatetable(	'insurance_policy',
							$setarr,
							array('policy_id'=>$policy_id)
							);
			ss_log(__FUNCTION__.", policy_id=".$policy_id.", arr_result=".var_export($arr_result, true));

			$result = $arr_result['STATUS'];
			$retmsg = $arr_result['COMMENTS'];
			ss_log(__FUNCTION__.",COMMENTS=".$retmsg);
			//GBK to UTF-8
			//$after_tranfer =  mb_convert_encoding($retmsg, "UTF-8", "GBK" );
			//ss_log(__FUNCTION__.",COMMENTS=".$after_tranfer);
			
			//下一步就要进入查询接口了，还是统一用crontab处理好些？但是也应该提供手动的查询接口
			updatetable('insurance_policy',
								array('ret_code'=>$result,
										'ret_msg'=>$retmsg),
								array('policy_id'=>$policy_id)
								);
			$result_attr = array('retcode'=>$result,
								'retmsg'=> $retmsg,
								);
	
			return $result_attr;
		}
		else
		{
			//承保失败的返回处理
			$result = $arr_result['STATUS'];//这个状态应该都存起来了，便于用来查询接口的查询操作
			$retmsg = $arr_result['COMMENTS'];
			updatetable('insurance_policy',
								array('ret_code'=>$result,
										'ret_msg'=>$retmsg),
								array('policy_id'=>$policy_id)
								);
			//核保失败处理
			ss_log(__FUNCTION__."[".__LINE__."], 承保失败，result=".$result.", comments=".$retmsg);
			$result_attr = array('retcode'=>$result,
								'retmsg'=> $retmsg,
								);
	
			return $result_attr;
			
		}
		
		
	}//
	elseif($ret_type== "querypolicystatus"//查询返回处理,承保单查询，
	  )
	{
		ss_log(__FUNCTION__.", 承保请求的查询处理, arr_result=".var_export($arr_result,true));
		if(isset($arr_result['STATUS']) && $arr_result['STATUS'] == 10 )//保单已经生效了
		{
			ss_log(__FUNCTION__.", cpic cargo 查询保单成功");
			ss_log(__FUNCTION__.", return code: ".$arr_result['P_AUDIT_WORK__RESULTCODE'].", return content:".$arr_result['P_AUDIT_WORK__RESULT']." ordernum: ".$orderNum.", order_sn: ".$order_sn);
			$result = 0;
			
			//获取电子保单，获取保单号,
			$policy_no = $arr_result['P_AUDIT_WORK__POLICYNO'];
			$policy_file_content = $arr_result['FILE_EPOLICY'];
			ss_log(__FUNCTION__.", policy_no=".$policy_no.", policy file content=".$policy_file_content);

			if($policy_attr['policy_id'])//old
			{
				$policy_id = $policy_attr['policy_id'];
				ss_log(__FUNCTION__.", return policyNo: ".$return_data['policyNo']." order_sn: ".$order_sn);
				
				//在这里更改该保单状态,存储保险公司返回的保单号，同时保存电子保单url到数据库中
				updatetable('insurance_policy',
								array('policy_status'=>'insured',
										'policy_no'=>$policy_no, 
										'policy_file'=>$policy_file_content),
								array('policy_id'=>$policy_id)
								);
				//start add by wangcya, 20150325,投保成功，增加对应的订单上的投保成功的个数///////////////////
				$sql = "UPDATE bx_order_info SET insured_policy_num=insured_policy_num+1 WHERE order_sn=".$order_sn;
				ss_log(__FUNCTION__.", ".$sql);
				$_SGLOBAL['db']->query($sql);
				//end add by wangcya, 20150325,投保成功，增加对应的订单上的投保成功的个数///////////////////
				///////////////////////////////////////////////////////////////
				$result = 0;
				$retmsg = $arr_result['P_AUDIT_WORK__RESULT'];
		
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
			$policy_attr['policy_file'] = $policy_file_content;
			
			$pdf_filename = S_ROOT."xml/message/".$policy_no."_cpic_cargo_policy.pdf";
			
			$policy_attr['readfile'] = $readfile;

			$result_attr = get_policy_file_cpic_cargo($policy_attr,
													  $pdf_filename,
													  $obj_java,
													  $arr_result);
			
		}
		else//查询返回是其他的结果
		{
			ss_log(__FUNCTION__.", 查询，STATUS=".$arr_result['STATUS']);
			ss_log(__FUNCTION__.", 查询，P_AUDIT_WORK__RESULTCODE=".$arr_result['P_AUDIT_WORK__RESULTCODE']);
			$result_attr = array('retcode'=>$arr_result['STATUS'],
									'retmsg'=> $arr_result['P_AUDIT_WORK__RESULT'],
									'cast_policy_no'=>$arr_result['P_AUDIT_WORK__APPLYNO']//投保单号
									);
									
			return $result_attr;
		}
	}
	//查询批单的返回处理
	elseif($ret_type== "querypolicystatus_endorse")
	{
		ss_log(__FUNCTION__.", 批单的查询返回处理, arr_result=".var_export($arr_result,true));
		/*
		 * 37 待签发-----人工审核通过，等待缴费 （非在线支付情况，状态：17 --批单生效）
			21 审核退回或拒绝-----批单审核不通过的情况下，系统自动作废。可重新提交批改申请
		*/
		if(isset($arr_result['STATUS']) && $arr_result['STATUS'] == 17 )//批单已经生效了
		{
			ss_log(__FUNCTION__.", cpic cargo 查询批单成功");
			ss_log(__FUNCTION__.", return code: ".$arr_result['P_AUDIT_WORK__RESULTCODE'].", return content:".$arr_result['P_AUDIT_WORK__RESULT']." ordernum: ".$orderNum.", order_sn: ".$order_sn);
			$result = 0;
			
			//获取电子保单的url地址，获取批单号 P_AUDIT_WORK__POLICYNO
			$policy_no = $arr_result['P_AUDIT_WORK__POLICYNO'];//批单对应的原有保单保单号
			$endorseno = $arr_result['P_AUDIT_WORK__ENDORSENO'];//批单号
			$policy_file_content = $arr_result['FILE_EPOLICY'];//电子保单
			ss_log(__FUNCTION__.", policy_no=".$policy_no.", policy file content=".$policy_file_content);

			if($policy_attr['policy_id'])//old
			{
				$policy_id = $policy_attr['policy_id'];
				ss_log(__FUNCTION__.", return policyNo: ".$policy_no." order_sn: ".$order_sn);
				
				//在这里更改该保单状态,存储保险公司返回的保单号，同时保存电子保单url到数据库中
				updatetable('insurance_policy',
								//这里应该就是批单成功了。保单号应该是原来保单号
								array('policy_status'=>'insured',
										'policy_no'=>$policy_no, 
										'policy_file'=>$policy_file_content, 
										'endorseno'=>$endorseno),
								array('policy_id'=>$policy_id)
								);
				$result = 0;
				$retmsg = $arr_result['P_AUDIT_WORK__RESULT'];
		
			}
			else
			{
				$result = 120;
				$retmsg = "get endorse query return success,but not find policy by order num:".$orderNum;
				ss_log(__FUNCTION__.", order_sn: ".$order_sn);
				ss_log(__FUNCTION__.", ".$retmsg);
				//comment by zhangxi, 20150206, 这里是否应该返回？毕竟是异常，就不要进行取保单操作了
				//should add code here
				return  array("retcode"=>$result,
									"retmsg"=>$retmsg);
			}
		
			//如果是退保的批单，是没有电子保单的
			if($policy_attr['endorse_type'] == '002')
			{
				//注销则直接返回
				ss_log(__FUNCTION__.", ENDORSE_TYPE=002,return");
				return array("retcode"=>$result,
								"retmsg"=>$retmsg
								);
			}
		
			ss_log(__FUNCTION__." policy_id: ".$policy_id);
			//$policy_attr['policy_status'] = 'insured';//更改投保成功标示
			$policy_attr['policy_no'] = $policy_no;
			$policy_attr['policy_file'] = $policy_file_content;
			$policy_attr['readfile'] = $readfile;

			//批单产生的电子保单，单独？因为保单号不一样？
			$pdf_filename = S_ROOT."xml/message/".$policy_no."_cpic_cargo_policy.pdf";
			//直接从报文中得到电子保单内容
			$result_attr = get_policy_file_cpic_cargo($policy_attr,
													  $pdf_filename,
													  $obj_java,
													  $arr_result);
			
		}
		else//查询返回是其他的结果
		{
			//批单待审核15， 或者拒批21？or 20?
			//如果是15的话，说明是提交审核，这里数据库中是否应该有个批单的状态字段呢？
			
			ss_log(__FUNCTION__.", 批单查询未立即获得批单文件，P_AUDIT_WORK__RESULTCODE=".$arr_result['P_AUDIT_WORK__RESULTCODE']);
			$result_attr = array('retcode'=>$arr_result['STATUS'],
									'retmsg'=> $arr_result['P_AUDIT_WORK__RESULT'],
									);
									
			return $result_attr;
		}
	}
	elseif($ret_type== "endorse"//批单请求的返回处理
	  )
	{
		ss_log(__FUNCTION__.", 批单返回处理, arr_result=".var_export($arr_result,true));
		global $cpic_cargo_endorsement_return_status;
		if(isset($arr_result['STATUS']) && $arr_result['STATUS'] == $cpic_cargo_endorsement_return_status["OK"] )//批单自动处理成功
		{
			ss_log(__FUNCTION__.", 批单成功");
			ss_log(__FUNCTION__.", STATUS: ".$arr_result['STATUS']." ordernum: ".$orderNum.", order_sn: ".$order_sn);
			$result = 0;
			
			
			//获取电子保单的url地址，获取批单的投保单号
			$cast_policy_no = $arr_result['APPLYNO'];
			//批单申请号
			$policy_endorseno = $arr_result['APPLYENDORSENO'];
			ss_log(__FUNCTION__.", policy_no=".$policy_no.", policy_endorseno=".$policy_endorseno);

			if(isset($arr_result['POLICYNO']) && !empty($arr_result['POLICYNO']))
			{
				$policy_no = $arr_result['POLICYNO'];//批单成功时对应的批单的保单号	
			}
			else
			{
				$policy_no = $policy_attr['policy_no'];//否则不变
			}
			if($policy_attr['policy_id'])//old
			{
				$policy_id = $policy_attr['policy_id'];
				ss_log(__FUNCTION__.", return POLICYNO: ".$arr_result['POLICYNO']." order_sn: ".$order_sn);
				
				//在这里更改该保单状态,存储保险公司返回的保单号，同时保存电子保单url到数据库中
				updatetable('insurance_policy',
								array('policy_no'=>$policy_no,
										'cast_policy_no'=>$cast_policy_no, 
										'endorseno'=>$policy_endorseno),//批单号
								array('policy_id'=>$policy_id)
								);
				$result = 0;
				$retmsg = "endorseno policy success!";
		
			}
			else
			{
				$result = 120;
				$retmsg = "endorseno policy success,but not find policy by order num:".$orderNum;
				ss_log(__FUNCTION__.", order_sn: ".$order_sn);
				ss_log(__FUNCTION__.", ".$retmsg);
				//comment by zhangxi, 20150206, 这里是否应该返回？毕竟是异常，就不要进行取保单操作了
				//should add code here
			}
		
			//如果是注销的批改，还查什么呢？？？
			
			
			
			ss_log(__FUNCTION__." policy_id: ".$policy_id);
			//$policy_attr['policy_status'] = 'insured';//更改投保成功标示
			
			$policy_attr['policy_no'] = $policy_no;
			$policy_attr['cast_policy_no'] = $cast_policy_no;
			$policy_attr['endorseno'] = $policy_endorseno;
			//应该马上进入查询接口处理???,现在只有注销，能否不进入查询接口？
			//$result_attr = query_policyfile_cpic_cargo($policy_attr,true,false,"insurance_endorsement");
			//
			$result_attr = array("retcode"=>"0",
								  "retmsg"=>"批改退保成功"
									);
			return $result_attr;
			
		}//批单系统处于待审核状态，后续需要去查询，这个状态是否要存起来？？？，否则后续
		//怎么知道哪些保单和批单是需要定期查询的呢？
		elseif(isset($arr_result['STATUS']) && $arr_result['STATUS'] == $cpic_cargo_endorsement_return_status["AUDIT"] )
		{
			
			//后台应该有循环查询的脚本会执行
			ss_log(__FUNCTION__.", 批单审核中，STATUS=".$arr_result['STATUS'].", COMMENTS=".$arr_result['COMMENTS']);
			$result_attr = array('retcode'=>$arr_result['STATUS'],
									'retmsg'=> $arr_result['COMMENTS'],
									);
			updatetable('insurance_policy',
								array('ret_code'=>$result_attr['retcode'],
										'ret_msg'=>$result_attr['retmsg'],
										'applyendorseno'=>$arr_result['APPLYENDORSENO']//必须保留批单申请号，用于查询
										),
										
								array('policy_id'=>$policy_id)
								);
			
		}
		else//批单申请失败
		{
			ss_log(__FUNCTION__.", 批单申请失败，STATUS=".$arr_result['STATUS'].", COMMENTS=".$arr_result['COMMENTS']);
			$result_attr = array('retcode'=>$arr_result['STATUS'],
									'retmsg'=> $arr_result['COMMENTS'],
									);
			updatetable('insurance_policy',
								array('ret_code'=>$result_attr['retcode'],
										'ret_msg'=>$result_attr['retmsg']),
								array('policy_id'=>$policy_id)
								);
			
		}
		
		return $result_attr;
	}
	elseif($ret_type== "claimsettl"//报案返回处理
	  )
	{
		ss_log(__FUNCTION__.", 报案返回处理, arr_result=".var_export($arr_result,true));
		if($arr_result['ResultCode_tc'] == 1 )//报案成功
		{
			ss_log(__FUNCTION__.", cpic cargo 报案成功");
			ss_log(__FUNCTION__.", return code: ".$arr_result['ResultCode_tc']." ordernum: ".$orderNum.", order_sn: ".$order_sn);
			$result = 0;
			
			
			//获取电子保单的url地址，获取保单号
			$policy_no = $arr_result['PolNumber'];
			$policy_file = $arr_result['PolicyUrl'];
			ss_log(__FUNCTION__.", policy_no=".$policy_no.", policy_file=".$policy_file);

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
			
			$pdf_filename = S_ROOT."xml/message/".$policy_no."_cpic_cargo_policy.pdf";
			

			
		}
		else//报案失败
		{
			ss_log(__FUNCTION__.", 承保失败，ret_flag=".$arr_result['ResultCode_tc']);
		}
	}
	elseif($ret_type== "insurance_claims"//理赔返回处理
	  )
	{}
	else
	{
		//picclife投保返回异常
		$result = $arr_result['STATUS'];
		//$retmsg = $arr_result['ResultInfoDesc'];
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
//保单的注销
function post_withdraw_policy_cpic_cargo(	$policy_attr, $user_info_applicant )
{
	global $_SGLOBAL, $java_class_name_cpic_cargo;
	global $LOGFILE_CPIC_CARGO;
	ss_log(__FUNCTION__.", in file name ".__FILE__);
	//////////////////////////////////////////////////////
	$policy_id = $policy_attr['policy_id'];
	
	$POLICYNO 		= $policy_attr['policy_no'];
	$orderNum 		= $policy_attr['order_num'];
	$order_sn 		= $policy_attr['order_sn'];
	
	ss_log(__FUNCTION__.", will withdraw policy , POLICYNO: ".$POLICYNO." orderNum: ".$orderNum);
	ss_log(__FUNCTION__.", order_sn: ".$order_sn);
	//走批单接口的注销流程
	$result_attr = insurance_endorsement_cpic_cargo($policy_attr, false);
	
	return $result_attr;
}
//走批单接口进行保单的注销操作
function withdraw_policy_cpic_cargo($policy_id, $user_info_applicant)
{
	
	
	ss_log(__FUNCTION__.", in file name ".__FILE__);
	global $_SGLOBAL;
	/////////////////////////////////////////////////////
	if($policy_id)
	{
		$ret = get_policy_info($policy_id);//得到保单信息
	
		ss_log(__FUNCTION__." after function get_policy_info!");
	
		$policy_attr = $ret['policy_arr'];
	}
	/////////////////////////////////////////////////////
	if(!empty($policy_attr))
	{
		$result_attr = post_withdraw_policy_cpic_cargo(	$policy_attr, $user_info_applicant );
	
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

function get_policy_file_by_return_data()
{
	;
}

//获取电子保单函数
function get_policy_file_cpic_cargo($policy_attr,
			                       $pdf_filename,
			                       $obj_java = NULL,
			                       $arr_result
			                      )
{
	global $java_class_name_cpic_cargo;
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
	
	//保存电子保单没有异步和同步之分了
	$java_class_name = $java_class_name_cpic_cargo;

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
	$logFileName = S_ROOT."xml/log/cpic_cargo_download_policyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	
	
	ss_log(__FUNCTION__.",picclife will get policy file, POLICYNO: ".$POLICYNO." orderNum: ".$orderNum);
	ss_log(__FUNCTION__.",picclife order_sn: ".$order_sn);
	
	(string)$obj_java->savePDF2File( 	$arr_result['FILE_EPOLICY'],
										$pdf_filename,
										$logFileName
									);

	if (file_exists($pdf_filename))//文件已经存在，说明保存电子保单成功。
	{
		/////////////////////////////////////////////////////////
		//$policy_id = $policy_attr['policy_id'];
		$readfile = $policy_attr['readfile'];
		
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

	ss_log(__FUNCTION__.", ".$retmsg);
	$result_attr = array(	'retcode'=>$result,
							'retmsg'=> $retmsg
						);
	
	return $result_attr;
	
}


//获取pdf格式的电子保单，用户前端点击下载的情况，
//如果保单原来没有，就重新下载的处理
function get_policyfile_cpic_cargo($pdf_filename,
		                      $policy_attr,
		                      $user_info_applicant
		                    )
{
	$use_asyn = false;
	//调用查询接口同步获取电子保单
	$result_attr = query_policyfile_cpic_cargo($policy_attr,false,true);
	//$result_attr = get_policy_file_cpic_cargo($use_asyn,$policy_attr,$pdf_filename);
	return $result_attr;
}



//发送投保请求
function post_accept_policy_cpic_cargo($policy_id,
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

//太平洋货运险投保
function post_policy_cpic_cargo(
							$attribute_type,//add by wangcya, 20141213
							$attribute_code,//add by wangcya, 20141213
							$policy_arr,
							$user_info_applicant,
							$list_subject
						   )
{
	global $_SGLOBAL, $java_class_name_cpic_cargo;
	/////////////////////////////////////////////////////////
	ss_log("into ".__FUNCTION__);
	
	$policy_id = $policy_arr['policy_id'];//add by wangcya , 20150105 ,for bug[193],能够支持多人批量投保，
	
	$orderNum = $policy_arr['order_num'];//这个填写的就是订单号
	$order_sn = $policy_arr['order_sn'];//add by wangcya, 20141023
	
	//生成需要进行核/投保的xml文件
	$strxml = gen_cpic_cargo_post_policy_xml(
										$attribute_type,
										$attribute_code,
										$policy_arr,//保单相关信息
										$user_info_applicant,//投保人信息
										$list_subject
										);//subject信息，被保险人信息也在这里面
		
	ss_log(__FUNCTION__.", after gen_cpic_cargo_post_policy_xml,strxml=".$strxml);

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
	$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_cargo_policy_post.xml";
	file_put_contents($af_path,$strxml);//核保文件先存起来
	
	//start add by wangcya, 20150209,把携带序列号的也保存起来
	$UUID = $policy_arr['order_num'];
	$af_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$UUID."_cargo_policy_policy_post.xml";
	file_put_contents($af_path_serial,$strxml);
	//end add by wangcya, 20150209,把携带序列号的也保存起来
	
	//if(1)
	if(!defined('USE_ASYN_JAVA'))
	{
		$java_class_name = $java_class_name_cpic_cargo;//同步
	}
	else
	{
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	}
	$java_class_name = empty($java_class_name)?$java_class_name_cpic_cargo:$java_class_name;
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
	$ret_af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_cargo_policy_post_ret.xml";
	ss_log($ret_af_path);
	
	$url = URL_CPIC_CARGO_POST_POLICY;//核保，投保保URL
	ss_log(__FUNCTION__."url: ".$url);
	$userName = USERNAME_CPIC_CARGO;
	$password = PASSWORD_CPIC_CARGO;
	$passNeedMd5 = 'n';
	$checkCode = CHECKCODE_CPIC_CARGO;
	$xmlContent = '';
	$postXmlFileName = $af_path;
	$postXmlFileEncoding = 'UTF-8';
	//if(1)
	if(!defined('USE_ASYN_JAVA'))//同步核保处理流程
	{
		global $LOGFILE_CPIC_CARGO;

		//进行核保调用处理
		ss_log(__FUNCTION__.", cpic cargo will java check policy,同步承保开始");
		
		$strxml_check_policy_ret = $return_data = (string)$obj_java->approval(
																		$url,//服务地址
																		$userName ,//投保报文
																		$password,//密码
																		$passNeedMd5,//密码是否需要md5加密（y/yes是需要，其他不需要）
																		$policy_arr['classtype'],//险种代码
																		$checkCode,//MD5校验码,如果报文在java中进行了编码转换，在这里先算出校验值，是有问题的
																		$xmlContent,//报文内容
																		$postXmlFileName,//存储报文文件名称
																		$postXmlFileEncoding,//存储报文文件编码格式
																		$LOGFILE_CPIC_CARGO
																		);

		ss_log(__FUNCTION__."after post call function underWriting");

		//////////////////////////////////////////////////////////
		if(!empty($strxml_check_policy_ret))//核保完了的返回处理
		{
			ss_log(__FUNCTION__.", cpic cargo will process return_data");
		
			file_put_contents($ret_af_path, $strxml_check_policy_ret);
				
			//////////////////////////////////////////////////////////////////////////
			$result_attr = process_return_xml_data_cpic_cargo(
														 	$policy_id,
									                      	$orderNum,
															$order_sn,
															$return_data,
															NULL,
															'insure_accept',
															false,
															false
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
	else//异步承保
	{//USE_ASYN_JAVA
		//////////////////////////////////////////
		ss_log(__FUNCTION__."将要发送cpic cargo核保的异步请求，will doService");
		$xmlContent = "";
		$postXmlFileName = $af_path;

		$postXmlEncoding = "UTF-8";//GBK";
		$respXmlFileName = $ret_af_path;//承保返回报文存储路径
		$logFileName = S_ROOT."xml/log/cpic_cargo_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
	
		ss_log(__FUNCTION__."logFileName: ".$logFileName);
	
		$type = "insure_accept";//承保
		
		$insurer_code = $policy_arr['insurer_code'];
		//投保之后的回调url
		$callBackURL = CALLBACK_URL;
					
		ss_log(__FUNCTION__.", policy_id: ".$policy_id);
		ss_log(__FUNCTION__."type: ".$type);
		ss_log(__FUNCTION__."insurer_code: ".$insurer_code);
		ss_log(__FUNCTION__."callBackURL: ".$callBackURL);
			
		$param_other = array(
							"url"=>$url ,//投保保险公司服务地址
							 "username"=>$userName,
							 "password"=>$password,
							 "passNeedMd5"=>$passNeedMd5,
							 "classesCode"=>$policy_arr['classtype'],
							 "checkCode"=>$checkCode,
							"xmlContent"=>$xmlContent ,//
							"postXmlFileName"=>$postXmlFileName ,//投保报文内容
							"postXmlFileEncoding"=>$postXmlEncoding,//投保报文编码
							"respXmlFileName"=>$respXmlFileName,//返回报文保存全路径
							"respXmlFileEncoding"=>"UTF-8",
							"logFileName"=>$logFileName//日志文件
						);
	
		ss_log(__FUNCTION__.", param_other: ".var_export($param_other,true));

		$jsonStr = json_encode($param_other);
		ss_log(__FUNCTION__.", jsonStr: ".$jsonStr);
		
		$af_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_cargo_policy_post_to_java.json";
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



//added by zhangxi, 20150528, 返回报文解析到数组
function parse_xml_data_to_array_cpic_cargo($return_data,
												 $ret_type = 'insure_accept',
												 $asnyc = true,//默认是异步
												 $attribute_type
													)
{
	/**/
	ss_log(__FUNCTION__.", get in, ret_type=".$ret_type);
	$policy_ret = array();
	$ret_res = 0;
	//resultStatus=F,errorType=C,errorCode=000002,errorMsg:原保单未同步到核心系统之前，暂不允许批改，请稍候再试！,policyInfonull,CheckCode=null
	preg_match_all('/errorCode=(.*),/isU',$return_data,$arr);
	if($arr[1])
	{
		$policy_ret['errorCode'] = trim($arr[1][0]);
		preg_match_all('/errorMsg:(.*),/isU',$return_data,$arr);
		if($arr[1])
		{
			$policy_ret['errorMsg'] = trim($arr[1][0]);
		}
		ss_log(__FUNCTION__.", errorMsg=".$policy_ret['errorMsg']);
		//说明发生错误了，直接返回
		return $policy_ret;
	}
	
	if($ret_type == 'insure_accept')
	{
		//其他货运险
		if($attribute_type == 'other_cargo')
		{
			
			preg_match_all('/<applyId>(.*)<\/applyId>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['ApplyId'] = trim($arr[1][0]);
			}
			//投保单号
			preg_match_all('/<applyNo>(.*)<\/applyNo>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['APPLYNO'] = trim($arr[1][0]);
			}
			//保单号
			preg_match_all('/<policyNo>(.*)<\/policyNo>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['POLICYNO'] = trim($arr[1][0]);
			}
			//保单状态 7待审核 10已生效  36待签发 19提交失败
			//这个节点好像没有啊
			preg_match_all('/<epolicyStatus>(.*)<\/epolicyStatus>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['STATUS'] = trim($arr[1][0]);//
				if($policy_ret['STATUS'] == 1)
				{
					$policy_ret['STATUS'] = 10;
				}
				elseif(!empty($policy_ret['POLICYNO']) && $policy_ret['STATUS'] == 2)
				{
					$policy_ret['STATUS'] = 7;
					//只是电子保单还没有难道，但是投保成功了。
				}
			}
			
			//电子保单状态  0创建失败 1创建成功 2未创建
			preg_match_all('/<epolicyStatus>(.*)<\/epolicyStatus>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['STATUS_EPOLICY'] = trim($arr[1][0]);//
			}
			
			$start = strpos($return_data, "<epolicyInfo>");
			if($start>0)
			{
				$start += strlen("<epolicyInfo>");
				$end  = strpos($return_data, "</epolicyInfo>");
				$FILE_EPOLICY = substr($return_data, $start,$end-$start);
				ss_log(__FUNCTION__.", matched epolicyInfo=".$FILE_EPOLICY);
				$policy_ret['FILE_EPOLICY'] = $FILE_EPOLICY;
			}
			
			//COMMENTS，相关提示信息
			preg_match_all('/<applyResult>(.*)<\/applyResult>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['COMMENTS'] = trim($arr[1][0]);//提示信息
				if($asnyc)
				{
					$policy_ret['COMMENTS'] = mb_convert_encoding($policy_ret['COMMENTS'], "UTF-8", "GBK" );	
				}
				
			}
		
			
		}
		else
		{
			preg_match_all('/<ApplyId>(.*)<\/ApplyId>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['ApplyId'] = trim($arr[1][0]);
			}
			//投保单号
			preg_match_all('/<APPLYNO>(.*)<\/APPLYNO>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['APPLYNO'] = trim($arr[1][0]);
			}
			//保单号
			preg_match_all('/<POLICYNO>(.*)<\/POLICYNO>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['POLICYNO'] = trim($arr[1][0]);
			}
			//保单状态 7待审核 10已生效  36待签发 19提交失败
			preg_match_all('/<STATUS>(.*)<\/STATUS>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['STATUS'] = trim($arr[1][0]);//
			}
			
			//电子保单状态  0创建失败 1创建成功 2未创建
			preg_match_all('/<STATUS_EPOLICY>(.*)<\/STATUS_EPOLICY>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['STATUS_EPOLICY'] = trim($arr[1][0]);//
			}
			//电子保单内容             FILE_EPOLICY
	//		$ret_res = preg_match_all('/<FILE_EPOLICY>(.*)<\/FILE_EPOLICY>/isU',$return_data,$arr);
	//		ss_log(__FUNCTION__.", ret_res=".$ret_res);
	//		if($arr[1])
	//		{
	//			ss_log(__FUNCTION__.", matched FILE_EPOLICY,=".$arr[1][0]);
	//			$policy_ret['FILE_EPOLICY'] = trim($arr[1][0]);//保单内容
	//		}
			$start = strpos($return_data, "<FILE_EPOLICY>");
			if($start>0)
			{
				$start += strlen("<FILE_EPOLICY>");
				$end  = strpos($return_data, "</FILE_EPOLICY>");
				$FILE_EPOLICY = substr($return_data, $start,$end-$start);
				ss_log(__FUNCTION__.", matched FILE_EPOLICY=".$FILE_EPOLICY);
				$policy_ret['FILE_EPOLICY'] = $FILE_EPOLICY;
			}
			
			//原币种保费
			preg_match_all('/<PREMIUM>(.*)<\/PREMIUM>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['PREMIUM'] = trim($arr[1][0]);//保单内容
			}
			//原币种保费折合人民币保费
			preg_match_all('/<RMB_PREMIUM>(.*)<\/RMB_PREMIUM>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['RMB_PREMIUM'] = trim($arr[1][0]);//保单内容
			}
			//原保费币种人民币转换汇率-
			preg_match_all('/<EXCHANGE_RATE>(.*)<\/EXCHANGE_RATE>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['EXCHANGE_RATE'] = trim($arr[1][0]);//保单内容
			}
			
			//COMMENTS，相关提示信息
			preg_match_all('/<COMMENTS>(.*)<\/COMMENTS>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['COMMENTS'] = trim($arr[1][0]);//提示信息
				if($asnyc)
				{
					$policy_ret['COMMENTS'] = mb_convert_encoding($policy_ret['COMMENTS'], "UTF-8", "GBK" );	
				}
				
			}
		}
			
	}
	//查询返回的xml数据解析
	elseif($ret_type == 'querypolicystatus'|| $ret_type == 'querypolicystatus_endorse')
	{
		//其他货运险
		if($attribute_type == 'other_cargo')
		{
			preg_match_all('/<P_AUDIT_WORK__RESULTCODE>(.*)<\/P_AUDIT_WORK__RESULTCODE>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['P_AUDIT_WORK__RESULTCODE'] = trim($arr[1][0]);
				
			}
			//投保单号
			preg_match_all('/<P_AUDIT_WORK__APPLYNO>(.*)<\/P_AUDIT_WORK__APPLYNO>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['P_AUDIT_WORK__APPLYNO'] = trim($arr[1][0]);
			}
			//保单号                           P_AUDIT_WORK__POLICYNO
			preg_match_all('/<P_AUDIT_WORK__POLICYNO>(.*)<\/P_AUDIT_WORK__POLICYNO>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['P_AUDIT_WORK__POLICYNO'] = trim($arr[1][0]);
			}
			//批单号
			//P_AUDIT_WORK__ENDORSENO
			preg_match_all('/<P_AUDIT_WORK__ENDORSENO>(.*)<\/P_AUDIT_WORK__ENDORSENO>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['P_AUDIT_WORK__ENDORSENO'] = trim($arr[1][0]);
			}
			//保单状态 7 待审核10已生效 36待签发 18 审核退回 9拒保
			preg_match_all('/<STATUS>(.*)<\/STATUS>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['STATUS'] = trim($arr[1][0]);//
			}
			
			//电子保单状态  0创建失败 1创建成功 2未创建
			preg_match_all('/<STATUS_EPOLICY>(.*)<\/STATUS_EPOLICY>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['STATUS_EPOLICY'] = trim($arr[1][0]);//
			}
			if(isset($policy_ret['STATUS_EPOLICY'] )
			&& $policy_ret['STATUS_EPOLICY'] == 1
			&& !isset($policy_ret['STATUS']))
			{
				$policy_ret['STATUS'] = 10;
			}
			$start = strpos($return_data, "<FILE_EPOLICY>");
			if($start>0)
			{
				$start += strlen("<FILE_EPOLICY>");
				$end  = strpos($return_data, "</FILE_EPOLICY>");
				$FILE_EPOLICY = substr($return_data, $start,$end-$start);
				ss_log(__FUNCTION__.", matched FILE_EPOLICY=".$FILE_EPOLICY);
				$policy_ret['FILE_EPOLICY'] = $FILE_EPOLICY;
			}
			
			
			//COMMENTS，两核处理结果
			preg_match_all('/<P_AUDIT_WORK__RESULT>(.*)<\/P_AUDIT_WORK__RESULT>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['P_AUDIT_WORK__RESULT'] = trim($arr[1][0]);//
				if($asnyc)
				{
					$policy_ret['P_AUDIT_WORK__RESULT'] = mb_convert_encoding($policy_ret['P_AUDIT_WORK__RESULT'], "UTF-8", "GBK" );
				}
				
			}
		}
		else
		{
			/*
			 1        核保通过
			 2        核保拒绝
			 3        退回修改
			 A        核保过程中
			 36       投保单待支付
			 37       批单待支付
			*/
			preg_match_all('/<P_AUDIT_WORK__RESULTCODE>(.*)<\/P_AUDIT_WORK__RESULTCODE>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['P_AUDIT_WORK__RESULTCODE'] = trim($arr[1][0]);
				
			}
			//投保单号
			preg_match_all('/<P_AUDIT_WORK__APPLYNO>(.*)<\/P_AUDIT_WORK__APPLYNO>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['P_AUDIT_WORK__APPLYNO'] = trim($arr[1][0]);
			}
			//保单号                           P_AUDIT_WORK__POLICYNO
			preg_match_all('/<P_AUDIT_WORK__POLICYNO>(.*)<\/P_AUDIT_WORK__POLICYNO>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['P_AUDIT_WORK__POLICYNO'] = trim($arr[1][0]);
			}
			//批单号
			//P_AUDIT_WORK__ENDORSENO
			preg_match_all('/<P_AUDIT_WORK__ENDORSENO>(.*)<\/P_AUDIT_WORK__ENDORSENO>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['P_AUDIT_WORK__ENDORSENO'] = trim($arr[1][0]);
			}
			//保单状态 7 待审核10已生效 36待签发 18 审核退回 9拒保
			preg_match_all('/<STATUS>(.*)<\/STATUS>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['STATUS'] = trim($arr[1][0]);//
			}
			
			//电子保单状态  0创建失败 1创建成功 2未创建
			preg_match_all('/<STATUS_EPOLICY>(.*)<\/STATUS_EPOLICY>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['STATUS_EPOLICY'] = trim($arr[1][0]);//
			}
			//电子保单内容
	//		preg_match_all('/<FILE_EPOLICY>(.*)<\/FILE_EPOLICY>/isU',$return_data,$arr);
	//		if($arr[1])
	//		{
	//			$policy_ret['FILE_EPOLICY'] = trim($arr[1][0]);//保单内容
	//		}
			$start = strpos($return_data, "<FILE_EPOLICY>");
			if($start>0)
			{
				$start += strlen("<FILE_EPOLICY>");
				$end  = strpos($return_data, "</FILE_EPOLICY>");
				$FILE_EPOLICY = substr($return_data, $start,$end-$start);
				ss_log(__FUNCTION__.", matched FILE_EPOLICY=".$FILE_EPOLICY);
				$policy_ret['FILE_EPOLICY'] = $FILE_EPOLICY;
			}
			
			
			//COMMENTS，两核处理结果
			preg_match_all('/<P_AUDIT_WORK__RESULT>(.*)<\/P_AUDIT_WORK__RESULT>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['P_AUDIT_WORK__RESULT'] = trim($arr[1][0]);//
				if($asnyc)
				{
					$policy_ret['P_AUDIT_WORK__RESULT'] = mb_convert_encoding($policy_ret['P_AUDIT_WORK__RESULT'], "UTF-8", "GBK" );
				}
				
			}
		}
		
	}
	//批单返回
	elseif($ret_type == 'endorse')
	{
		//其他货运险
		if($attribute_type == 'other_cargo')
		{
			preg_match_all('/<APPLYENDORSENO>(.*)<\/APPLYENDORSENO>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['APPLYNO'] = trim($arr[1][0]);//批单的投保单号
				$policy_ret['STATUS'] = 17;//批单操作成功的情况
			}
			//返回提示信息
			preg_match_all('/<ENDORSETEXT>(.*)<\/ENDORSETEXT>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['COMMENTS'] = trim($arr[1][0]);
			}
			
			
		}
		else
		{
			preg_match_all('/<ApplyId>(.*)<\/ApplyId>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['ApplyId'] = trim($arr[1][0]);
			}
			//分公司
			preg_match_all('/<UNITCODE>(.*)<\/UNITCODE>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['UNITCODE'] = trim($arr[1][0]);
			}
			//保单号
			preg_match_all('/<POLICYNO>(.*)<\/POLICYNO>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['POLICYNO'] = trim($arr[1][0]);
			}
			//投保单号
			preg_match_all('/<APPLYNO>(.*)<\/APPLYNO>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['APPLYNO'] = trim($arr[1][0]);
			}
			
			/*
			 * 15 待审核-----批单录入成功，提交人工审核
			 * 37 待签发-----批单录入成功，自动核保通过 （非在线支付情况，状态 17 --批单生效）
			 * 20 提交失败----批单录入成功，系统提交核保失败，需联系技术人员处理
			 * */
			preg_match_all('/<STATUS>(.*)<\/STATUS>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['STATUS'] = trim($arr[1][0]);//
			}
			
			//批单申请号，             APPLYENDORSENO
			preg_match_all('/<APPLYENDORSENO>(.*)<\/APPLYENDORSENO>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['APPLYENDORSENO'] = trim($arr[1][0]);//
			}
			//电子保单内容
			preg_match_all('/<COMMENTS>(.*)<\/COMMENTS>/isU',$return_data,$arr);
			if($arr[1])
			{
				$policy_ret['COMMENTS'] = trim($arr[1][0]);//提示信息
				if($asnyc)
				{
					$policy_ret['COMMENTS'] = mb_convert_encoding($policy_ret['COMMENTS'], "UTF-8", "GBK" );
				}
				
			}
		}
		
	}
	//报案返回
	elseif($ret_type == 'claimsettl')
	{
		
	}
	//理赔返回
	elseif($ret_type == 'insurance_claims')
	{
		
	}
	
	
	ss_log(__FUNCTION__.", policy_ret=".var_export($policy_ret, true));
	return $policy_ret;
}


?>