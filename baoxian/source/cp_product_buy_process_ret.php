<?php
//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯

/*
include_once('./function_debug.php');
include_once('./function_baoxian.php');
include_once('./cp_product_buy_process.php');
*/

include_once(S_ROOT.'/source/function_debug.php');
include_once(S_ROOT.'/source/function_baoxian.php');
include_once(S_ROOT.'/source/cp_product_buy_process.php');


ss_log("will into cp_buy_product_process_ret");
ss_log(S_ROOT.'/source/function_baoxian.php');

/////////////////////////////////////////////////////////////////////////////
ss_log("将要进入回调异步处理流程， file: ".__FILE__);
ss_log("将要进入回调异步处理流程， post: ".var_export($_POST,true));
$retparam = trim($_POST['retparam']);

if(empty($retparam))
{
	$message = "retparam is empty！";
	ss_log($message);
	$ret_attr = array(
			"type"=>"insure",
			"status"=>"400",
			"message"=>$message,
			"keyid"=>0
	);
	echo json_encode($ret_attr);
	
	exit(0);
}

ss_log("retparam: ".$retparam);
$json = sstripslashes($retparam);
//ss_log("after transfer, retparam: ".$json);


//assoc 当该参数为 TRUE 时，将返回 array 而非 object 。 
//var_dump(json_decode($json));

$respond = json_decode($json,true);

ss_log("respond: ".var_export($respond,true));
///////////////////////////////////////////////////
$ret_type = trim($respond['type']);
$policy_id = $respond['keyid'];

///////////////////////////////////////////////////

ss_log("ret_type: ".$ret_type);
ss_log("policy_id: ".$policy_id);

///////////////////////////////////////////////////////////////////////////////////
if(empty($policy_id))
{
	$message = "post json, policy_id is empty！";
	ss_log($message);
	
	$ret_attr = array(
						"type"=>$ret_type,
						"status"=>"400",
						"message"=>$message,
						"keyid"=>0
						);
				
	echo json_encode($ret_attr);
	exit(0);
}

////////////////////////////////////////////////////////////////////////////////////
$wheresql = "policy_id='$policy_id'";
$sql = "SELECT * FROM ".tname('insurance_policy')." WHERE $wheresql LIMIT 1";
ss_log($sql);
$query = $_SGLOBAL['db']->query($sql);
$policy_attr = $_SGLOBAL['db']->fetch_array($query);

$policy_id     = $policy_attr['policy_id'];
$applicant_uid = $policy_attr['applicant_uid'];//comment by zhangxi, 20141211, 投保人uid?
$business_type = $policy_attr['business_type'];//add by wangcya, 20140807
$attribute_id  = $policy_attr['attribute_id'];
$attribute_type  = $policy_attr['attribute_type'];
$insurer_code     = $policy_attr['insurer_code'];//add by wangcya , 20140914, 增加厂商代码为了区分显示

$order_sn  = $policy_attr['order_sn'];
$orderNum  = $policy_attr['order_num'];//这个填写的就是唯一的号码
$product_code = $policy_attr['product_code'];//add by wangcya, 20150110, 这个时候使用的是policy中的product_code,这是转换后的。
ss_log("policy's product_code: ".$product_code);
/////////////////////////////////////////////////////////////////////////////
if( $insurer_code =="PAC"||
	$insurer_code =="PAC01"||
	$insurer_code =="PAC02"||
	$insurer_code =="PAC03"||
	$insurer_code =="PAC05"//大连Y502
	)//平安的
{
	
	if($ret_type == "insure_accept")
	{
		ss_log("asyn process, insurer_code: ".$insurer_code);
	
		if(0)
		{}
		else
		{
			
			//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
//			$FILE_UUID = getRandOnly_Id(0,1);
//			//$return_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_pingan_policy_post_ret.xml";
//			$return_path_serial = gen_file_fullpath('policy', 'pingan', S_ROOT, $order_sn."_".$policy_id."_".$FILE_UUID."_pingan_policy_post_ret.xml");
//			ss_log($return_path_serial);
//			file_put_contents($return_path_serial,$return_data);
			//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
			
			
			//start add by wangcya, 20150401,处理异常
			if($policy_attr['policy_status'] == "insured")
			{//如果前面是已经设置投保成功了，则这里存在异常，就不应该处理了
				$message = "！！！出现了异常，已经投保成功，却重复返回！";
				ss_log($message);
					
				$ret_attr = array(
						"type"=>$ret_type,
						"status"=>"401",
						"message"=>$message,
						"keyid"=>$policy_id
				);
					
				echo json_encode($ret_attr);
				exit(0);
			}
			//end add by wangcya, 20150401,处理异常
			
			//投保动作的返回XML信息，先保存一份
			//start add by wangcya, 20150110
			ss_log("得到返回数据正确，将要处理返回数据XML");
			
			if(empty($product_code))
			{
				//$product_code = $list_subject[0]['list_subject_product'][0]['product_code'];
			}
			
			$result_attr = process_return_xml_data_pingan(
														$policy_id,
							                            $respond,
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
					
					//$post_xml_path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_policy_post.xml";
					$post_xml_path = gen_file_fullpath('policy', 'pingan', S_ROOT, $order_sn."_".$policy_id."_pingan_policy_post.xml");
					$strxml = file_get_contents($post_xml_path);
					
					gen_toubaoshu_pdf(
									$strxml,
									$order_sn,
									$policy_id
									);
				}
				/////////////end add by wangcya, 20141204,投保书////////////////////////////////
			}
			else
			{
				ss_log("处理投保返回报文错误");
			}
			//////////////////////////////////////////////////////////
		}
	}//op=insure
	elseif($ret_type == "getpolicyfile")
	{//获取保单文件的异步仅仅用在投保过程，当用户下载的时候还是同步方式。也就是异步先在投保过程下载，用户下载时候用同步。
		$policy_no     = $policy_attr['policy_no'];
		$filename = S_ROOT."xml/message/".$policy_no."_pingan_policy.pdf";

		ss_log("policy file: ".$filename);
		if (file_exists($filename))//如果这个文件已经存在，则返回给用户。
		{
			$result = 0;
			$retmsg = "get local policy pdf file ok!";
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg,'retFileName'=>str_replace(S_ROOT,'',$filename)
			);//add by dingchaoyang 2014-12-4添加retfilename
			
			//return $result_attr;
		}
		else//进行电子保单的获取处理
		{
			if(($respond['rspCode'] == '000000') && ($respond['policyStatus'] == 0))
			{
				$logFileName = S_ROOT."xml/log/pingan_download_policyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
				if($respond['policyStream'] != '')
				{
					$ret = save_policy_file($respond['policyStream'], $filename, $logFileName);
					if($ret == 0)
					{
						$retmsg = '获取电子保单成功';
						$result_attr = array(	'retcode'=>$ret,
								'retmsg'=> $retmsg
						);
						if (file_exists($filename))//如果这个文件已经存在，则返回给用户。
						{
							$result = 0;
							$retmsg = "get local policy pdf file ok!";
							ss_log(__FILE__.", ".$retmsg);
							$result_attr = array(	'retcode'=>$result,
									'retmsg'=> $retmsg,'retFileName'=>str_replace(S_ROOT,'',$filename)
							);
						}
					}
					else
					{
						$retmsg = '获取电子保单失败';
						$result_attr = array(	'retcode'=>$ret,
								'retmsg'=> $retmsg
						);
					}
				}
				else
				{
					$retmsg = '获取电子保单失败';
					$result_attr = array(	'retcode'=>$ret,
							'retmsg'=> $retmsg
					);
				}
				
			}
			else
			{
				$result = $respond['rspCode'];
				$retmsg = $respond['rspMsg'];
				ss_log(__FUNCTION__.", ".$retmsg);
				$result_attr = array(	'retcode'=>$result,
						'retmsg'=> "(getpolicyfile)".$retmsg
				);
				//return $result_attr;
			}
			
			
		}
		 
	}
	elseif($ret_type == "withdraw")
	{
		$policy_id = $policy_attr['policy_id'];
		$rspCode = $respond['rspCode'];
		if($rspCode == "000000")
		{
			$result = $rspCode;
			$retmsg = "withdraw policy ok!";
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$result,
									'retmsg'=> $retmsg
									);
									
				//修改订单的支付状态和订单状态
			$setarr = array('policy_status'=>'canceled',
							'pay_status'=>0,//add by wangcya, 20150114 for bug[115], 后台一定有一个定时任务，对那些已经付款的订单对应的没成功投保的保单进行投保。
							'ret_code'=>$result,
							'ret_msg'=>$retmsg);
			
			$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
			updatetable(	'insurance_policy',
							$setarr	,
							array('policy_id'=>$policy_id)
						);
			ss_log("retmsg: ".$retmsg);
			
			//保费的退回处理
			require_once (S_ROOT . '../includes/init.php');
			include_once(S_ROOT.'../includes/lib_order.php');
			
			ss_log(__FILE__.", will withdraw_order");
			withdraw_order($policy_id);//
			ss_log(__FILE__.", after withdraw_order");						
		}
		else
		{
			$retmsg = "withdraw policy failed!";
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$respond['rspCode'],
									'retmsg'=> $respond['rspMsg']
									);
		}
	}
	else
	{
		
	}

	//start add by wangcya , 20140926, 保存该保单的返回原因
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
	

}
//added by zhangxi, 20150710, 增加平安个财的处理
elseif($insurer_code =="PAC04")
{
	if($ret_type == "insure")//投保异步返回
	{
		ss_log("asyn process, insurer_code: ".$insurer_code);
		
		//$path = S_ROOT."xml/".$order_sn."_".$policy_id."_pingan_property_policy_post_ret.json";
		$path = gen_file_fullpath('policy', 'pingan', S_ROOT, $order_sn."_".$policy_id."_pingan_property_policy_post_ret.json");
		$return_path = empty($respXmlFileName)?$path:$respXmlFileName;
		ss_log($return_path);
		
		$return_data = file_get_contents($return_path);
		if(empty($return_data))
		{
			$message = "asyn process, get pingan property policy ret path fail！";
			ss_log($message);
		
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);
				
			echo json_encode($ret_attr);
			exit(0);
		}
		else
		{
			
			//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
			$FILE_UUID = getRandOnly_Id(0,1);
			//$return_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_pingan_property_policy_post_ret.json";
			$return_path_serial = gen_file_fullpath('policy', 'pingan', S_ROOT, $order_sn."_".$policy_id."_".$FILE_UUID."_pingan_property_policy_post_ret.json");
			ss_log($return_path_serial);
			file_put_contents($return_path_serial,$return_data);
			
			//start add by wangcya, 20150401,处理异常
			if($policy_attr['policy_status'] == "insured")
			{//如果前面是已经设置投保成功了，则这里存在异常，就不应该处理了
				$message = "！！！出现了异常，已经投保成功，却重复返回！";
				ss_log($message);
					
				$ret_attr = array(
						"type"=>$ret_type,
						"status"=>"401",
						"message"=>$message,
						"keyid"=>$policy_id
				);
					
				echo json_encode($ret_attr);
				exit(0);
			}
			//end add by wangcya, 20150401,处理异常
			
			//投保动作的返回XML信息，先保存一份
			//start add by wangcya, 20150110
			ss_log("得到返回数据正确，将要处理返回数据JSON");
			$result_attr = process_return_json_data_pingan_property(
																$policy_id,
									                            $return_data,
																$order_sn,
																$orderNum
																);
			if($result_attr['retcode']==0)//success
			{
				
			}
			else
			{
				ss_log("处理投保返回报文错误");
			}
			//////////////////////////////////////////////////////////
		}
	}//op=insure
	elseif($ret_type == "getpolicyfile")
	{//获取保单文件的异步仅仅用在投保过程，当用户下载的时候还是同步方式。也就是异步先在投保过程下载，用户下载时候用同步。
		$policy_no     = $policy_attr['policy_no'];
		$filename = S_ROOT."xml/message/".$policy_no."_pingan_property_policy.pdf";

		ss_log("policy file: ".$filename);
		if (file_exists($filename))//如果这个文件已经存在，则返回给用户。
		{
			$result = 0;
			$retmsg = "get local policy pdf file ok!";
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg,'retFileName'=>str_replace(S_ROOT,'',$filename)
			);
		}
		 
	}
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
	
}
elseif($insurer_code == "TBC01")//太平洋
{
	ss_log("asyn process, insurer_code: ".$insurer_code);
	
	//////////////////////////////////////////////////////////////////////////
	if($ret_type == "insure")
	{
		$path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_taipingyang_policy_post_ret.xml");
		$return_path = empty($respXmlFileName)?$path:$respXmlFileName;
		ss_log($return_path);
		$strxml_post_policy_ret = file_get_contents($return_path);
		if(empty($strxml_post_policy_ret))
		{
			$message = "asyn process, get taipingyang policy ret path fail！";
			ss_log($message);
				
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);
			
			echo json_encode($ret_attr);
			exit(0);
		}
		
		//得到发送时候的报文
		//$return_path = S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_post.xml";
		$return_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_taipingyang_policy_post.xml");
		ss_log($return_path);
		$strxml_post_policy_content = file_get_contents($return_path);
		if(empty($strxml_post_policy_content))
		{
			$message = "get policy post file fail！";
			ss_log($message);
		
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);
		
			echo json_encode($ret_attr);
			exit(0);
		}
		
		//////////////////////////////////////////////////////////////////
		
		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
		$FILE_UUID = getRandOnly_Id(0,1);
		//$return_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_taipingyang_policy_post_ret.xml";
		$return_path_serial = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_".$FILE_UUID."_taipingyang_policy_post_ret.xml");
		ss_log($return_path_serial);
		file_put_contents($return_path_serial,$strxml_post_policy_ret);
		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
		
		//start add by wangcya, 20150401,处理异常
		if($policy_attr['policy_status'] == "insured")
		{//如果前面是已经设置投保成功了，则这里存在异常，就不应该处理了
			$message = "！！！出现了异常，已经投保成功，却重复返回！";
			ss_log($message);
			
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"401",
					"message"=>$message,
					"keyid"=>$policy_id
			);
			
			echo json_encode($ret_attr);
			exit(0);
		}
		//end add by wangcya, 20150401,处理异常
		
		////////////////////////////////////////////////////////////////////////////////////
		ss_log("得到返回数据正确，将要处理返回数据XML");
		
		$result_attr = process_return_xml_data_taipingyang(
				$policy_id,
				$orderNum,
				$order_sn,
				$strxml_post_policy_ret,//在先
				$strxml_post_policy_content//在后
   		        );
		
				
		
	 }//op=insure
	 elseif($ret_type == "getpolicyfile")
	 {
	 	//$return_path = $responseFileName;//S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_post_ret.xml";
	 	
	 	//$path = S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_get_pdf_ret.xml";
	 	$path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_taipingyang_policy_get_pdf_ret.xml");
	 	$return_path = empty($respXmlFileName)?$path:$respXmlFileName;
	 	ss_log($return_path);
	 	$strxml_post_policy_ret = file_get_contents($return_path);
	 	if(empty($strxml_post_policy_ret))
	 	{
	 		$message = "asyn process, get taipingyang policy ret path fail！";
	 		ss_log($message);
	 	
	 		$ret_attr = array(
	 				"type"=>$ret_type,
	 				"status"=>"400",
	 				"message"=>$message,
	 				"keyid"=>$policy_id
	 		);
	 			
	 		echo json_encode($ret_attr);
	 		exit(0);
	 	}
	 	
	 
	 	////////////////////////////////////////////////////////////////////////////////////
	 	ss_log("得到返回数据正确，将要处理返回数据XML");

	 	$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
	 	$obj_java = create_java_obj($java_class_name);
	 	if(!$obj_java)
	 	{
	 		$result = 112;
	 		$retmsg = "create_java_obj fail!";
	 		ss_log($retmsg);
	 	
	 		$result_attr = array(	'retcode'=>$result,
	 				'retmsg'=> $retmsg
	 		);
	 	
	 	
	 		
	 	}
	 	else
	 	{
	 		$policy_no     = $policy_attr['policy_no'];
	 		
	 		$pdf_path = S_ROOT."xml/message/".$policy_no."_taipingyang_policy.pdf";
	 		
	 		$policy_attr['readfile'] = false;
	 		$result_attr = process_return_xml_data_getpolicyfile( 	$policy_attr,//add by wangcya, 20150205
	 		                                                        $strxml_post_policy_ret, 
	 				                                                $pdf_path , 
	 				                                                $obj_java 
	 				                                            );
	 	}
	 	
	 }

	 //start add by wangcya , 20140926, 保存该保单的返回原因
	 $result = $result_attr['retcode'];
	 $retmsg = $result_attr['retmsg'];
	 

}
elseif($insurer_code == $ARR_INS_COMPANY_NAME['str_cpic_tj_property'])//太平洋天津财险
{
	ss_log("asyn process, insurer_code: ".$insurer_code);
	
	//////////////////////////////////////////////////////////////////////////
	if($ret_type == "insure")
	{
		
		//$path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_tj_property_policy_post_ret.xml";
		$path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_cpic_tj_property_policy_post_ret.xml");
		$return_path = empty($respXmlFileName)?$path:$respXmlFileName;
		ss_log($return_path);
		$strxml_post_policy_ret = file_get_contents($return_path);
		if(empty($strxml_post_policy_ret))
		{
			$message = "asyn process, get taipingyang policy ret path fail！";
			ss_log($message);
				
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);
			
			echo json_encode($ret_attr);
			exit(0);
		}
		
		//得到发送时候的报文
		//$return_path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_tj_property_policy_post.xml";
		$return_path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_cpic_tj_property_policy_post.xml");
		ss_log(__FILE__.", ".$return_path);
		$strxml_post_policy_content = file_get_contents($return_path);
		if(empty($strxml_post_policy_content))
		{
			$message = "get policy post file fail！";
			ss_log($message);
		
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);
		
			echo json_encode($ret_attr);
			exit(0);
		}
		
		//////////////////////////////////////////////////////////////////
		
		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
		$FILE_UUID = getRandOnly_Id(0,1);
		//$return_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_cpic_tj_property_policy_post_ret.xml";
		$return_path_serial = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_".$FILE_UUID."_cpic_tj_property_policy_post_ret.xml");
		ss_log(__FILE__.", ".$return_path_serial);
		file_put_contents($return_path_serial,$strxml_post_policy_ret);
		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
		
		//start add by wangcya, 20150401,处理异常
		if($policy_attr['policy_status'] == "insured")
		{//如果前面是已经设置投保成功了，则这里存在异常，就不应该处理了
			$message = "！！！出现了异常，已经投保成功，却重复返回！";
			ss_log($message);
			
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"401",
					"message"=>$message,
					"keyid"=>$policy_id
			);
			
			echo json_encode($ret_attr);
			exit(0);
		}
		//end add by wangcya, 20150401,处理异常
		
		////////////////////////////////////////////////////////////////////////////////////
		ss_log(__FILE__.", 得到返回数据正确，将要处理返回数据XML");
		
		$result_attr = process_return_xml_data_cpic_tj_property($policy_attr,
															$policy_id,
															$orderNum,
															$order_sn,
															$strxml_post_policy_ret//返回值
											   		        );
		
				
		
	 }//op=insure
	 elseif($ret_type == "getpolicyfile")
	 {
	 	$path = gen_file_fullpath('policy','taipingyang',S_ROOT, $order_sn."_".$policy_id."_taipingyang_policy_get_pdf_ret.xml");
	 	$strxml_post_policy_ret = file_get_contents($path);
	 	$policy_no     = $policy_attr['policy_no'];
	 	$pdf_path = S_ROOT."xml/message/".$policy_no."_taipingyang_policy.pdf";
	 	$policy_attr['readfile'] = false;
	 	$result_attr = process_return_xml_data_getpolicyfile_cpic_tj($policy_attr,$strxml_post_policy_ret,$pdf_path);
	 }

	 $result = $result_attr['retcode'];
	 $retmsg = $result_attr['retmsg'];
	 

}
elseif( $insurer_code =="HTS")//华泰保险
{
	ss_log("asyn process, insurer_code: ".$insurer_code);
	//////////////////////////////////////////////////////////////////////////
	if($ret_type == "insure")
	{
		
		$path = S_ROOT."xml/".$order_sn."_".$policy_id."_huatai_policy_post_ret.xml";
		$return_path = empty($respXmlFileName)?$path:$respXmlFileName;
		//$return_path = $respXmlFileName;//S_ROOT."xml/".$order_sn."_".$policy_id."_huatai_policy_post_ret.xml";
		
		ss_log($return_path);
		
		$strxml_post_policy_ret = $return_data = file_get_contents($return_path);
		if(empty($return_data))
		{
			$message = "asyn process, get huatai policy ret path fail！";
			ss_log($message);
		
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);
				
			echo json_encode($ret_attr);
			exit(0);
		}
		
		
		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
		$FILE_UUID = getRandOnly_Id(0,1);
		$return_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_huatai_policy_post_ret.xml";
		ss_log($return_path_serial);
		file_put_contents($return_path_serial,$strxml_post_policy_ret);
		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
			
			
		//start add by wangcya, 20150401,处理异常
		if($policy_attr['policy_status'] == "insured")
		{//如果前面是已经设置投保成功了，则这里存在异常，就不应该处理了
			$message = "！！！出现了异常，已经投保成功，却重复返回！";
			ss_log($message);
				
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"401",
					"message"=>$message,
					"keyid"=>$policy_id
			);
				
			echo json_encode($ret_attr);
			exit(0);
		}
		//end add by wangcya, 20150401,处理异常
		//////////////////////////////////////////////////////////////////
		ss_log("得到返回数据正确，将要处理华泰投保返回数据XML");
		$result_attr = process_post_policy_return_xml_data_huatai(	
																	$policy_id,
																	$order_sn,
																	$return_data//in
															);
	}//op=insure
	elseif($ret_type == "insure_accept")
	{
		$path = S_ROOT."xml/".$order_sn."_".$policy_id."_huatai_policy_post_accept_ret.xml";
		$return_path = empty($respXmlFileName)?$path:$respXmlFileName;
		
		//$return_path = $respXmlFileName;//S_ROOT."xml/".$order_sn."_".$policy_id."_huatai_policy_post_accept_ret.xml";
		ss_log($return_path);
		
		$strxml_post_policy_ret = $return_data = file_get_contents($return_path);
		if(empty($return_data))
		{
			$message = "asyn process, get huatai accept policy ret file fail！";
			ss_log($message);
		
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);
		
			echo json_encode($ret_attr);
			exit(0);
		}
		//////////////////////////////////////////////////////////////////
		ss_log("得到返回数据正确，将要处理华泰承保的返回数据XML");
		////////////////////////////////////////////////////////////////////
		//start add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
		$java_class_name_huatai = 'com.yanda.imsp.util.ins.HuaTaiInsurance';
		//end add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
		
		
		$obj_java = create_java_obj($java_class_name_huatai);
		
		$result_attr = process_policy_accept_return_xml_data_huatai(	
												$policy_id,
												$order_sn,
												$return_data,//in
												$obj_java
										);
	}//op=insure_accept
	elseif($ret_type == "getpolicyfile")
	{
		//$return_path = $responseFileName;//S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_post_ret.xml";
		$path = S_ROOT."xml/".$order_sn."_".$policy_id."_huatai_policy_get_pdf_ret.xml";
		$return_path = empty($respXmlFileName)?$path:$respXmlFileName;
		ss_log($return_path);
		$return_data = $strxml_post_policy_ret = file_get_contents($return_path);
		if(empty($strxml_post_policy_ret))
		{
			$message = "asyn process, get taipingyang policy ret path fail！";
			ss_log($message);
			 
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);
		 	
			echo json_encode($ret_attr);
			exit(0);
		}
		 
	
		////////////////////////////////////////////////////////////////////////////////////
		ss_log("得到返回数据正确，将要处理返回数据XML");
	
		$java_class_name = "com.demon.insurance.pub.InsuranceDispatcher";
		$obj_java = create_java_obj($java_class_name);
		if(!$obj_java)
		{
			$result = 112;
			$retmsg = "create_java_obj fail!";
			ss_log($retmsg);
			 
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg
			);
			 
		}
		else
		{	
			$policy_no     = $policy_attr['policy_no'];
			$pdf_filename = S_ROOT."xml/message/".$policy_no."_huatai_policy.pdf";
			
			$policy_attr['readfile'] = false;
			$result_attr = process_getpolicyfile_return_xml_huatai( $policy_attr, $return_data, $pdf_filename , $obj_java );
		}
		 
	}
		
	//start add by wangcya , 20140926, 保存该保单的返回原因
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
	
	ss_log("before tranfer,将要保存保单的返回信息！".$retmsg );
	

}
//added by zhangxi, 20141222, 增加华安保险的投保处理
elseif($insurer_code =="SINO")
{
	ss_log("SINO asyn process, insurer_code: ".$insurer_code);
	
	//////////////////////////////////////////////////////////////////////////
	if($ret_type == "insure")
	{
		//$return_path = $responseFileName;//S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_post_ret.xml";
	
		$path = S_ROOT."xml/".$order_sn."_".$policy_id."_huaan_policy_post_ret.xml";
		$return_path = empty($respXmlFileName)?$path:$respXmlFileName;
		ss_log($return_path);
		$strxml_post_policy_ret = file_get_contents($return_path);
		if(empty($strxml_post_policy_ret))
		{
			$message = "asyn process, get huaan policy ret path fail！";
			ss_log($message);
	
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);
				
			echo json_encode($ret_attr);
			exit(0);
		}
		//comment by zhangxi, 20150206, empty not means insure policy success.
		//已经在下面的函数process_return_xml_data_huaan中进行了是否投保成功的判断
		//

		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
		$FILE_UUID = getRandOnly_Id(0,1);
		$return_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_huaan_policy_post_ret.xml";
		ss_log($return_path_serial);
		file_put_contents($return_path_serial,$strxml_post_policy_ret);
		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
			
			
		//start add by wangcya, 20150401,处理异常
		if($policy_attr['policy_status'] == "insured")
		{//如果前面是已经设置投保成功了，则这里存在异常，就不应该处理了
			$message = "！！！出现了异常，已经投保成功，却重复返回！";
			ss_log($message);
			
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"401",
					"message"=>$message,
					"keyid"=>$policy_id
			);
			
			echo json_encode($ret_attr);
			exit(0);
		}
		//end add by wangcya, 20150401,处理异常
		//////////////投保人///////////////////////////////////////////////////////////////
		$applicant_uid = $policy_attr['applicant_uid'];
		if($applicant_uid)
		{		
			if($business_type ==1) //个人信息
			{
				$wheresql = "uid='$applicant_uid'";
				$sql = "SELECT * FROM ".tname('user_info')." WHERE $wheresql LIMIT 1";
				$query = $_SGLOBAL['db']->query($sql);
				$user_info_applicant = $_SGLOBAL['db']->fetch_array($query);
			}
			elseif($business_type ==2||
					$business_type ==3//add by wangcya, for bug[193],能够支持多人批量投保，
			) //团体信息
			{
				$wheresql = "gid='$applicant_uid'";
				$sql = "SELECT * FROM ".tname('group_info')." WHERE $wheresql LIMIT 1";
				$query = $_SGLOBAL['db']->query($sql);
				$user_info_applicant = $_SGLOBAL['db']->fetch_array($query);
			}
		
		}
		////////////////////////////////////////////////////////////////////////////////////
		ss_log("得到返回数据正确，将要处理返回数据XML");
		//added by zhangxi,20150414, 异步情况需要等待保险公司服务器电子保单生成
		sleep(20);
		$result_attr = process_return_xml_data_huaan(
								$policy_id,
								$orderNum,
								$order_sn,
								$strxml_post_policy_ret,
								$user_info_applicant
								);
	
	
	
	}//op=insure	
	elseif($ret_type == "getpolicyfile")
	{
		$policy_no     = $policy_attr['policy_no'];
		$filename = S_ROOT."xml/message/".$policy_no."_huaan_policy.pdf";
		ss_log("policy file: ".$filename);
		if (file_exists($filename))//如果这个文件已经存在，则返回给用户。
		{			
			$result = 0;
			$retmsg = "get local policy pdf file ok!";
			ss_log($retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg,'retFileName'=>str_replace(S_ROOT,'',$filename)
			);//add by dingchaoyang 2014-12-4添加retfilename
				
			//return $result_attr;
		}
			
	}
	
	//start add by wangcya , 20140926, 保存该保单的返回原因
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
	
	ss_log("before tranfer,将要保存保单的返回信息！".$retmsg );
}
//added by zhangxi, 20141222, 增加到环球救援公司的处理
elseif($insurer_code =="Huanqiu")
{
	ss_log("环球救援异步返回处理，Huanqiu asyn process, insurer_code: ".$insurer_code);

	//////////////////////////////////////////////////////////////////////////
	if($ret_type == "pushinfo")
	{
		//$return_path = $responseFileName;//S_ROOT."xml/".$order_sn."_".$policy_id."_taipingyang_policy_post_ret.xml";

		$path = S_ROOT."xml/".$order_sn."_".$policy_id."_huanqiu_policy_post_jiuyuan_ret.xml";
		
		$return_path = empty($respXmlFileName)?$path:$respXmlFileName;
		ss_log($return_path);
		$strxml_post_policy_ret = file_get_contents($return_path);
		if(empty($strxml_post_policy_ret))
		{
			$message = "asyn process, get Huanqiu policy ret path fail！";
			ss_log($message);

			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);

			echo json_encode($ret_attr);
			exit(0);
		}

		////////////////////////////////////////////////////////////////////////////////////
		ss_log("环球救援得到返回数据正确，将要处理返回数据XML，policy_id".$policy_id);

		$UUID = "";
		$result_attr = process_return_xml_data_Huanqiu(
				$policy_id,
				$strxml_post_policy_ret
			);

	}//op=insure

	//start add by wangcya , 20140926, 保存该保单的返回原因
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];

	ss_log("before tranfer,将要保存保单的返回信息！".$retmsg );
}
//added by zhangxi, 20150325, 增加新华人寿的处理
elseif($insurer_code == "NCI")
{
	ss_log("asyn process, insurer_code: ".$insurer_code);
	//////////////////////////////////////////////////////////////////////////
	if($ret_type == "insure")//核保
	{
		
//		$path = S_ROOT."xml/".$order_sn."_".$policy_id."_xinhua_policy_post_ret.json";
//		$return_path = $path;		
//		ss_log($return_path);
//		//读取核保后返回内容
//		$strxml_post_policy_ret = $return_data = file_get_contents($return_path);
//		if(empty($return_data))
//		{
//			$message = "asyn process, get xinhua policy ret path fail！";
//			ss_log($message);
//		
//			$ret_attr = array(
//					"type"=>$ret_type,
//					"status"=>"400",
//					"message"=>$message,
//					"keyid"=>$policy_id
//			);
//				
//			echo json_encode($ret_attr);
//			exit(0);
//		}
		
//		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
//		$FILE_UUID = getRandOnly_Id(0,1);
//		$return_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_xinhua_policy_post_ret.json";
//		ss_log($return_path_serial);
//		file_put_contents($return_path_serial,$strxml_post_policy_ret);
//		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
//			
			
		//start add by wangcya, 20150401,处理异常
		if($policy_attr['policy_status'] == "insured")
		{//如果前面是已经设置投保成功了，则这里存在异常，就不应该处理了
			$message = "！！！出现了异常，已经投保成功，却重复返回！";
			ss_log($message);
				
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"401",
					"message"=>$message,
					"keyid"=>$policy_id
			);
				
			echo json_encode($ret_attr);
			exit(0);
		}
		//end add by wangcya, 20150401,处理异常
		//////////////////////////////////////////////////////////////////
		ss_log("得到返回数据正确，将要处理xinhua核保返回数据JSON");
		$result_attr = process_return_json_data_xinhua($policy_id,
														$orderNum,
														$order_sn,
														$respond);
	}//op=insure
	elseif($ret_type == "insure_accept")//承保
	{
//		$path = S_ROOT."xml/".$order_sn."_".$policy_id."_xinhua_policy_post_accept_ret.json";
//		$return_path = empty($respXmlFileName)?$path:$respXmlFileName;
//		
//		ss_log($return_path);
//		//读取承保后返回内容
//		$strxml_post_policy_ret = $return_data = file_get_contents($return_path);
//		if(empty($return_data))//返回异常处理
//		{
//			$message = "asyn process, get xinhua accept policy ret file fail！";
//			ss_log($message);
//		
//			$ret_attr = array(
//					"type"=>$ret_type,
//					"status"=>"400",
//					"message"=>$message,
//					"keyid"=>$policy_id
//			);
//		
//			echo json_encode($ret_attr);
//			exit(0);
//		}
		//////////////////////////////////////////////////////////////////
		ss_log("insure_accept 得到返回数据正确，将要处理xinhua承保的返回数据json");
		////////////////////////////////////////////////////////////////////
		//异步获取电子保单的情况，先sleep 一下,等待保单生成
		//sleep(5);
		$result_attr = process_return_json_data_xinhua(	
												$policy_id,
												$orderNum,
												$order_sn,
												$respond
										);
	}//op=insure_accept
	elseif($ret_type == "getpolicyfile")
	{
		$policy_no     = $policy_attr['policy_no'];
		$filename = S_ROOT."xml/message/".$policy_no."_xinhua_policy.pdf";
		ss_log(__FILE__.", policy file: ".$filename);
		if(($respond['rspCode'] == '000000') && ($respond['policyStatus'] == 0))
		{
			$logFileName = S_ROOT."xml/log/xinhua_download_policyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
			if($respond['policyStream'] != '')
			{
				$ret = save_policy_file($respond['policyStream'], $filename,$logFileName);
				if($ret == 0)
				{
					$retmsg = '获取电子保单成功';
					$result_attr = array(	'retcode'=>$ret,
							'retmsg'=> $retmsg
					);
					if (file_exists($filename))//如果这个文件已经存在，则返回给用户。
					{
						$result = 0;
						$retmsg = "get local policy pdf file ok!";
						ss_log(__FILE__.", ".$retmsg);
						$result_attr = array(	'retcode'=>$result,
								'retmsg'=> $retmsg,'retFileName'=>str_replace(S_ROOT,'',$filename)
						);
					}
				}
				else
				{
					$retmsg = '获取电子保单失败';
					$result_attr = array(	'retcode'=>$ret,
							'retmsg'=> $retmsg
					);
				}
			}
			else
			{
				$retmsg = '获取电子保单失败';
				$result_attr = array(	'retcode'=>$ret,
						'retmsg'=> $retmsg
				);
			}
			
		}
		else
		{
			$retmsg = '获取电子保单失败';
				$result_attr = array(	'retcode'=>$ret,
						'retmsg'=> $retmsg
				);
		}
		
		
	}
		
	//保存该保单的返回原因
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
	
	ss_log("before tranfer,将要保存保单的返回信息！".$retmsg );
	
}
elseif($insurer_code == "CHINALIFE")
{
	
	ss_log("asyn process, insurer_code: ".$insurer_code);
	//////////////////////////////////////////////////////////////////////////
	if($ret_type == "insure_accept")//承保
	{

		//////////////////////////////////////////////////////////////////
		ss_log("insure_accept 得到返回数据正确，将要处理xinhua承保的返回数据json");
		////////////////////////////////////////////////////////////////////
		//异步获取电子保单的情况，先sleep 一下,等待保单生成
		//sleep(5);
		$result_attr = process_return_xml_data_chinalife(	
												$policy_id,
												$orderNum,
												$order_sn,
												$respond
										);
	}//op=insure_accept
	elseif($ret_type == "getpolicyfile")
	{
		$policy_no     = $policy_attr['policy_no'];
		$filename = S_ROOT."xml/message/".$policy_no."_chinalife_policy.pdf";
		ss_log(__FILE__.", policy file: ".$filename);
		if(($respond['rspCode'] == '000000') && ($respond['policyStatus'] == 0))
		{
			$logFileName = S_ROOT."xml/log/chinalife_download_policyfile_java_asyn_".date("Y-m-d")."_".$order_sn."_".$policy_id.".log";
			if($respond['policyStream'] != '')
			{
				$ret = save_policy_file($respond['policyStream'], $filename,$logFileName);
				if($ret == 0)
				{
					$retmsg = '获取电子保单成功';
					$result_attr = array(	'retcode'=>$ret,
							'retmsg'=> $retmsg
					);
					if (file_exists($filename))//如果这个文件已经存在，则返回给用户。
					{
						$result = 0;
						$retmsg = "get local policy pdf file ok!";
						ss_log(__FILE__.", ".$retmsg);
						$result_attr = array(	'retcode'=>$result,
								'retmsg'=> $retmsg,'retFileName'=>str_replace(S_ROOT,'',$filename)
						);
					}
				}
				else
				{
					$retmsg = '获取电子保单失败';
					$result_attr = array(	'retcode'=>$ret,
							'retmsg'=> $retmsg
					);
				}
			}
			else
			{
				$retmsg = '获取电子保单失败';
				$result_attr = array(	'retcode'=>$ret,
						'retmsg'=> $retmsg
				);
			}
			
		}
		else
		{
			$retmsg = '获取电子保单失败';
				$result_attr = array(	'retcode'=>$ret,
						'retmsg'=> $retmsg
				);
		}
		
		
	}
		
	//保存该保单的返回原因
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
	
	ss_log("before tranfer,将要保存保单的返回信息！".$retmsg );
	
	
}
elseif($insurer_code == "picclife")
{
	ss_log("asyn process, insurer_code: ".$insurer_code);
	//////////////////////////////////////////////////////////////////////////
	if($ret_type == "insure")//核保
	{
		
		$path = S_ROOT."xml/".$order_sn."_".$policy_id."_picclife_policy_post_ret.xml";
		$return_path = empty($respXmlFileName)?$path:$respXmlFileName;		
		ss_log($return_path);
		//读取核保后返回内容
		$strxml_post_policy_ret = $return_data = file_get_contents($return_path);
		if(empty($return_data))
		{
			$message = "asyn process, get xinhua policy ret path fail！";
			ss_log($message);
		
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);
				
			echo json_encode($ret_attr);
			exit(0);
		}
		
		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
		$FILE_UUID = getRandOnly_Id(0,1);
		$return_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_picclife_policy_post_ret.xml";
		ss_log($return_path_serial);
		file_put_contents($return_path_serial,$strxml_post_policy_ret);
		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
			
			
		//start add by wangcya, 20150401,处理异常
		if($policy_attr['policy_status'] == "insured")
		{//如果前面是已经设置投保成功了，则这里存在异常，就不应该处理了
			$message = "！！！出现了异常，已经投保成功，却重复返回！";
			ss_log($message);
				
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"401",
					"message"=>$message,
					"keyid"=>$policy_id
			);
				
			echo json_encode($ret_attr);
			exit(0);
		}
		//end add by wangcya, 20150401,处理异常
		//////////////////////////////////////////////////////////////////
		ss_log("得到返回数据正确，将要处理picclife核保返回数据xml");
		$result_attr = process_return_xml_data_picclife($policy_id,
								                      	$orderNum,
														$order_sn,
														$return_data);
	}//op=insure
	elseif($ret_type == "insure_accept")//承保
	{
		$path = S_ROOT."xml/".$order_sn."_".$policy_id."_picclife_policy_post_accept_ret.xml";
		$return_path = empty($respXmlFileName)?$path:$respXmlFileName;
		
		ss_log($return_path);
		//读取承保后返回内容
		$strxml_post_policy_ret = $return_data = file_get_contents($return_path);
		if(empty($return_data))//返回异常处理
		{
			$message = "asyn process, get picclife accept policy ret file fail！";
			ss_log($message);
		
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);
		
			echo json_encode($ret_attr);
			exit(0);
		}
		//////////////////////////////////////////////////////////////////
		ss_log("insure_accept 得到返回数据正确，将要处理picclife承保的返回数据json");
		////////////////////////////////////////////////////////////////////
		//异步获取电子保单的情况，先sleep 一下,等待保单生成
		sleep(5);
		$result_attr = process_return_xml_data_picclife(	
												$policy_id,
												$orderNum,
												$order_sn,
												$return_data
										);
	}//op=insure_accept
	elseif($ret_type == "getpolicyfile")
	{
		$policy_no     = $policy_attr['policy_no'];
		$filename = S_ROOT."xml/message/".$policy_no."_picclife_policy.pdf";
		ss_log(__FILE__.", policy file: ".$filename);
		if (file_exists($filename))//如果这个文件已经存在，则返回给用户。
		{
			$result = 0;
			$retmsg = "get local policy pdf file ok!";
			ss_log(__FILE__.", ".$retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg,'retFileName'=>str_replace(S_ROOT,'',$filename)
			);
		}
	}
		
	//保存该保单的返回原因
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
	
	ss_log("before tranfer,将要保存保单的返回信息！".$retmsg );
	
}
//增加人保财险的异步处理
elseif($insurer_code == "EPICC")
{
	ss_log(__FILE__.", asyn process, insurer_code: ".$insurer_code);
	//////////////////////////////////////////////////////////////////////////
	if($ret_type == "insure")//投保
	{
		//投保成功后的返回报文路径
		$path = S_ROOT."xml/".$order_sn."_".$policy_id."_epicc_policy_post_ret.xml";
		$return_path = empty($respXmlFileName)?$path:$respXmlFileName;		
		ss_log($return_path);
		//读取核保后返回内容
		$strxml_post_policy_ret = $return_data = file_get_contents($return_path);
		if(empty($return_data))
		{
			$message = "asyn process, get epicc policy ret path fail！";
			ss_log(__FILE__.", ".$message);
		
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);
				
			echo json_encode($ret_attr);
			exit(0);
		}
		
		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
		$FILE_UUID = getRandOnly_Id(0,1);
		$return_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_epicc_policy_post_ret.xml";
		ss_log(__FILE__.", ".$return_path_serial);
		file_put_contents($return_path_serial,$strxml_post_policy_ret);
		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
			
			
		//start add by wangcya, 20150401,处理异常
		if($policy_attr['policy_status'] == "insured")
		{//如果前面是已经设置投保成功了，则这里存在异常，就不应该处理了
			$message = "！！！出现了异常，已经投保成功，却重复返回！";
			ss_log(__FILE__.", ".$message);
				
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"401",
					"message"=>$message,
					"keyid"=>$policy_id
			);
				
			echo json_encode($ret_attr);
			exit(0);
		}
		//end add by wangcya, 20150401,处理异常
		
		//////////////投保人///////////////////////////////////////////////////////////////
		$applicant_uid = $policy_attr['applicant_uid'];
		$user_info_applicant = get_applicant_info_by_uid($applicant_uid, $business_type);
		////////////////////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////
		ss_log(__FILE__.", 得到返回数据正确，将要处理epicc核保返回数据xml");
		$result_attr = process_return_xml_data_epicc($policy_id,
								                      	$orderNum,
														$order_sn,
														$return_data,
														$user_info_applicant);
	}//op=insure
	elseif($ret_type == "getpolicyfile")
	{
		$policy_no     = $policy_attr['policy_no'];
		$filename = S_ROOT."xml/message/".$policy_no."_epicc_policy.pdf";
		ss_log(__FILE__.", policy file: ".$filename);
		if (file_exists($filename))//如果这个文件已经存在，则返回给用户。
		{
			$result = 0;
			$retmsg = "get local policy pdf file ok!";
			ss_log(__FILE__.", ".$retmsg);
			$result_attr = array(	'retcode'=>$result,
					'retmsg'=> $retmsg,'retFileName'=>str_replace(S_ROOT,'',$filename)
			);
		}
	}
		
	//保存该保单的返回原因
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
	
	ss_log("before tranfer,将要保存保单的返回信息！".$retmsg );
	
}
//增加货运险的异步处理
elseif($insurer_code == "CPIC_CARGO")
{
	ss_log(__FILE__.", asyn process, insurer_code: ".$insurer_code);
	//////////////////////////////////////////////////////////////////////////
	if($ret_type == "insure_accept")//承保
	{
		//投保成功后的返回报文路径
		$path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_cargo_policy_post_ret.xml";
		$return_path = empty($respXmlFileName)?$path:$respXmlFileName;		
		ss_log(__FUNCTION__.", ".$return_path);
		//读取核保后返回内容
		$strxml_post_policy_ret = $return_data = file_get_contents($return_path);
		if(empty($return_data))
		{
			$message = "asyn process, get cpic cargo policy ret path fail！";
			ss_log(__FILE__.", ".$message);
		
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);
				
			echo json_encode($ret_attr);
			exit(0);
		}
		
		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
		$FILE_UUID = getRandOnly_Id(0,1);
		$return_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_cpic_cargo_policy_post_ret.xml";
		ss_log(__FILE__.", ".$return_path_serial);
		file_put_contents($return_path_serial,$strxml_post_policy_ret);
		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
			
			
		//start add by wangcya, 20150401,处理异常
		if($policy_attr['policy_status'] == "insured")
		{//如果前面是已经设置投保成功了，则这里存在异常，就不应该处理了
			$message = "！！！出现了异常，已经投保成功，却重复返回！";
			ss_log(__FILE__.", ".$message);
				
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"401",
					"message"=>$message,
					"keyid"=>$policy_id
			);
				
			echo json_encode($ret_attr);
			exit(0);
		}
		//end add by wangcya, 20150401,处理异常
		
		ss_log(__FILE__.", 得到返回数据正确，将要处理cpic cargo 承保返回数据xml");
		$result_attr = process_return_xml_data_cpic_cargo($policy_id,
								                      	$orderNum,
														$order_sn,
														$return_data,
														NULL,
														$ret_type);
	}
	//查询接口返回处理
	elseif($ret_type == "querypolicystatus"
	|| $ret_type == "querypolicystatus_endorse")//
	{
		if($ret_type == "querypolicystatus")
		{
			$path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_cargo_policy_query_ret.xml";
		}
		else
		{
			$path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_cargo_endorse_query_ret.xml";
		}
		
		
		$return_path = empty($respXmlFileName)?$path:$respXmlFileName;		
		ss_log(__FUNCTION__.", ".$return_path);
		//读取核保后返回内容
		$strxml_post_policy_ret = $return_data = file_get_contents($return_path);
		if(empty($return_data))
		{
			$message = "asyn process, get cpic cargo policy ret path fail！";
			ss_log(__FILE__.", ".$message);
		
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);
				
			echo json_encode($ret_attr);
			exit(0);
		}
		
		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
		$FILE_UUID = getRandOnly_Id(0,1);
		if($ret_type == "querypolicystatus")
		{
			$return_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_cpic_cargo_policy_query_ret.xml";
		}
		else
		{
			$return_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_cpic_cargo_endorse_query_ret.xml";
		}
		
		ss_log(__FILE__.", ".$return_path_serial);
		file_put_contents($return_path_serial,$strxml_post_policy_ret);
		
		ss_log(__FILE__.", 得到返回数据正确，将要处理cpic cargo 查询接口返回数据xml, ret_type=".$ret_type);
		$result_attr = process_return_xml_data_cpic_cargo($policy_id,
								                      	$orderNum,
														$order_sn,
														$return_data,
														NULL,
														$ret_type);
	}
	//批单接口返回处理
	elseif($ret_type == "endorse")
	{

		$path = S_ROOT."xml/".$order_sn."_".$policy_id."_cpic_cargo_policy_endorse_ret.xml";
		
		$return_path = empty($respXmlFileName)?$path:$respXmlFileName;		
		ss_log(__FUNCTION__.", ".$return_path);
		//读取核保后返回内容
		$return_data = file_get_contents($return_path);
		if(empty($return_data))
		{
			$message = "asyn process, get cpic cargo policy ret path fail！";
			ss_log(__FILE__.", ".$message);
		
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"400",
					"message"=>$message,
					"keyid"=>$policy_id
			);
				
			echo json_encode($ret_attr);
			exit(0);
		}
		
		//start add by wangcya, 20150401,对返回的也做备份，防止重复的覆盖
		$FILE_UUID = getRandOnly_Id(0,1);
		$return_path_serial = S_ROOT."xml/".$order_sn."_".$policy_id."_".$FILE_UUID."_cpic_cargo_policy_endorse_ret.xml";

		ss_log(__FILE__.", ".$return_path_serial);
		file_put_contents($return_path_serial,$return_data);

		if($policy_attr['policy_status'] == "insured")
		{//如果前面是已经设置投保成功了，则这里存在异常，就不应该处理了
			$message = "！！！出现了异常，已经投保成功，却重复返回！";
			ss_log(__FILE__.", ".$message);
				
			$ret_attr = array(
					"type"=>$ret_type,
					"status"=>"401",
					"message"=>$message,
					"keyid"=>$policy_id
			);
				
			echo json_encode($ret_attr);
			exit(0);
		}
		ss_log(__FILE__.", 得到返回数据正确，将要处理cpic cargo 批单接口返回数据xml");
		$result_attr = process_return_xml_data_cpic_cargo($policy_id,
								                      	$orderNum,
														$order_sn,
														$return_data,
														NULL,
														$ret_type);
	}
	//报案接口返回处理
	elseif($ret_type == "claimsettl")
	{
		ss_log(__FILE__.", 得到返回数据正确，将要处理cpic cargo 报案接口返回数据xml");
		$result_attr = process_return_xml_data_cpic_cargo($policy_id,
								                      	$orderNum,
														$order_sn,
														$return_data,
														NULL,
														$ret_type);
	}
		
	//保存该保单的返回原因
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
	
	ss_log("before tranfer,将要保存保单的返回信息！".$retmsg );
	
}
else//added by zhangxi, 20141211, error log here
{
	ss_log("error, unknown  insurer_code:".$insurer_code);
}


ss_log("将要更新投保单的返回信息, ret_code: ".$result." retmsg: ".$retmsg);

if($insurer_code !="Huanqiu")
{
	global $_SGLOBAL;
	$now = date('Y-m-d H:i:s',$_SGLOBAL['timestamp']);
	//不是救援公司的才更新状态到保单中
	$retmsg = addslashes($retmsg);
	//更新到数据库中
	$setarr = array('ret_code'=>$result,'ret_msg'=>$now." ".$retmsg);
	$setarr = saddslashes($setarr);//add by wangcya , 20141211,for sql Injection
	updatetable(	'insurance_policy',	
	                $setarr,
					array('policy_id'=>$policy_id)
			   );
}

//end add by wangcya , 20140926, 保存该保单的返回原因

//////////返回给java//////////////////////////////////////////////////
if($result==0)
{
	$message = "asyn process policy ok！";
	ss_log($message);
	$status = 200;

}
else
{
	$message = $retmsg;
	ss_log($message);
	$status = $result;
}

$ret_attr = array(
		"type"=>$ret_type,
		"status"=>$status,
		"message"=>$message,
		"keyid"=>$policy_id
		);

echo json_encode($ret_attr);
ss_log(__FILE__." finish!");

function get_applicant_info_by_uid($applicant_uid, $business_type)
{
	global $_SGLOBAL;
	$user_info_applicant = array();
	if($applicant_uid)
		{		
			if($business_type ==1) //个人信息
			{
				$wheresql = "uid='$applicant_uid'";
				$sql = "SELECT * FROM ".tname('user_info')." WHERE $wheresql LIMIT 1";
				$query = $_SGLOBAL['db']->query($sql);
				$user_info_applicant = $_SGLOBAL['db']->fetch_array($query);
			}
			elseif($business_type ==2||
					$business_type ==3//add by wangcya, for bug[193],能够支持多人批量投保，
			) //团体信息
			{
				$wheresql = "gid='$applicant_uid'";
				$sql = "SELECT * FROM ".tname('group_info')." WHERE $wheresql LIMIT 1";
				$query = $_SGLOBAL['db']->query($sql);
				$user_info_applicant = $_SGLOBAL['db']->fetch_array($query);
			}
		
		}
		return $user_info_applicant;
}

exit(0);



//end add by wangcya, 20150112,for bug[203],启用异步方式和java通讯
