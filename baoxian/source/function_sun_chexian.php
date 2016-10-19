<?php
/*
if(!defined('S_ROOT'))
{
	define('S_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
}
*/
define('S_ROOT1', dirname(__FILE__).DIRECTORY_SEPARATOR);
include_once(S_ROOT1.'../common.php');


include_once(S_ROOT . 'source/function_third_order.php');
include_once(S_ROOT . 'source/my_const.php');
//require(S_ROOT . 'source/function_debug.php');

class Sun_Car{
		
	//核保同步接口
	public function sun_process_car_proposal_sync($obj)
	{
		
		ss_log("into ".__FUNCTION__);
		
		global $_SGLOBAL,$use_test_environmental;
		
		///////////////////////////////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////////
		$applyInfo  = $obj['applyInfo'];
		
		$uid = $buyerId = $applyInfo['buyerId'];//代理人的uid
		//$buyerNick = $applyInfo['buyerNick'];
		$goods_id = $auctionId = $applyInfo['auctionId'];//goods_id，商品名，主要用在订单显示上
		
		ss_log("goods_id：".$goods_id);
		//测试环境和正式环境的商品id不一样
		if( $use_test_environmental == true )
		{
			ss_log("测试环境的 goods_id：".$goods_id);
		}
		else
		{
			$goods_id = 96;//因为页面中前端写死了，所以这里要适配过来
			ss_log("测试环境的 goods_id：".$goods_id);
		}
		
		//$auctionTitle = $applyInfo['auctionTitle'];
		//$promotionInfo = $applyInfo['promotionInfo'];
		if(empty($uid))
		{
			ss_log("error,not find uid, so retun!");
			return false;
		}
		
		if(empty($goods_id))
		{
			ss_log("error,not find goods_id, so retun!");
			return false;
		}
		
		ss_log("buyerId：".$buyerId);
		ss_log("auctionId：".$auctionId);
		
		////////////////////////////////////////////////////////////////////////////
		$sql = "SELECT tid FROM bx_goods WHERE goods_id='$goods_id' LIMIT 1";
		ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
		$goods = $_SGLOBAL['db']->fetch_array($query);
		$attribute_id = $goods['tid'];//找到产品属性
		
		ss_log("attribute_id：".$attribute_id);
		
		//////////返回才生成订单。在我们系统中生成新的订单，然后把这里的两个订单保存到我们系统的订单上
		$attribute_id_compulsory = ATTRIBUTE_ID_COMPULSORY;//43，测试和正式环境不一样
		$attribute_id_commercial = ATTRIBUTE_ID_COMMERCIAL;//44
		
		$obj_ThirdOrder = new ThirdOrder();
		$order_pre_attr = $obj_ThirdOrder->gen_pre_order(	$uid,
															$goods_id,
															$attribute_id,
															$attribute_id_compulsory,
															$attribute_id_commercial
															);//, $TR, $BU);//首先生成预先存放的本地订单
			
		//$order_pre_attr = $this->get_pre_order($obj);
		
		$pre_order_id = $order_pre_attr['pre_order_id'];
		if(empty($pre_order_id))
		{
			ss_log("error,not find pre_order_id, so retun!");
			return false;	
		}
		
		$agent_uid = isset($order_pre_attr['uid'])?$order_pre_attr['uid']:12;//根据订单号找到了代理人的UID
		$goods_id = $order_pre_attr['goods_id'];//阳光保险，用在订单上的
		$attribute_id = isset($order_pre_attr['attribute_id'])?$order_pre_attr['attribute_id']:45;//阳光保险，用在订单上的
		$attribute_id_compulsory = isset($order_pre_attr['attribute_id_compulsory'])?$order_pre_attr['attribute_id_compulsory']:43;//交强险，用在保单上
		$attribute_id_commercial = isset($order_pre_attr['attribute_id_commercial'])?$order_pre_attr['attribute_id_commercial']:44;//商业险，用在保单上
			
		
		ss_log("pre_order_id：".$pre_order_id);
		ss_log("agent_uid：".$agent_uid);
		ss_log("goods_id：".$goods_id);
		ss_log("attribute_id：".$attribute_id);
		ss_log("attribute_id_compulsory：".$attribute_id_compulsory);
		ss_log("attribute_id_commercial：".$attribute_id_commercial);
		
		////////////找到代理人的信息///////////////////////////////////////////////////
		$sql = "SELECT * FROM bx_users WHERE user_id='$agent_uid' LIMIT 1";
		ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
		$user_attr = $_SGLOBAL['db']->fetch_array($query);
		$user_name = $user_attr['user_name'];
		
		if($user_name)
		{
			//这里先设置后面需要用。
			$_SGLOBAL['supe_uid'] = $agent_uid;
			$_SGLOBAL['supe_username'] = $user_name;
			ss_log("代理人用户名为：".$user_name);
		}
		else
		{
			ss_log("not find user_name,so return");
			return false;
		}
		
		///////////////生成批量单//////////////////////////////////////////////////////
		
		$cart_insure_attr = array("user_id"=>$agent_uid,
				"product_id"=>$attribute_id,//$product['attribute_id'],
				"product_name"=>"阳光车险",//$product['attribute_name'],
		);
		
		$cart_insure_id = inserttable("cart_insure", $cart_insure_attr,true);
		ss_log("生成批量单, cart_insure_id: ".$cart_insure_id);
		
		//////////////////包含了多个订单，轮询出来////////////////////////////////////////
		ss_log("before loop orderInfoList" );
		$orderInfoList = $obj['orderInfoList']['orderInfo'];
		foreach ($orderInfoList as $key=>$value)
		{
			$orderInfo = $value;
						
			$tbOrderNo = $orderInfo['tbOrderNo'];
			$insuranceType = $orderInfo['insuranceType'];
			$outOrderId = $orderInfo['outOrderId'];//阳光车险的订单号
			$payOutTime = $orderInfo['payOutTime'];
			$payUrl = $orderInfo['payUrl'];
			$quoteTime = $orderInfo['quoteTime'];
			
			ss_log("-------------tbOrderNo: ".$tbOrderNo);
			ss_log("insuranceType: ".$insuranceType);
			ss_log("outOrderId: ".$outOrderId);
			ss_log("payOutTime: ".$payOutTime);
			ss_log("payUrl: ".$payUrl);
			ss_log("quoteTime: ".$quoteTime);
	
			ss_log("----------insureType: ".$insuranceType);
	
			
			//////////////根据产品的CODE找到产品的相关信息////////////////////////////////////////////////
			//在这个函数内部，要形成交强险和商业险的两个投保单
					
			if($insuranceType == 0)//交强险
			{
				$TR = $tbOrderNo;
				
				//先更新主检索的表
				updatetable("insurance_policy_car_third_pre_order",
							array("TR"=>$TR),
							array("pre_order_id"=>$pre_order_id)
							);
				
				$outOrderId_compulsory = $outOrderId;
				////////////////////////////////////////////////////////////////////////////////
				ss_log("准备生成交强险投保单，gen_policy_compulsory ,id: ".$attribute_id_compulsory);
				$ret1 = $this->gen_policy_compulsory(	$obj,
														$orderInfo,
														$cart_insure_id,
														$attribute_id_compulsory
														);
				
				$totalModalPremium_compulsory = $ret1["totalModalPremium"];
				$policy_id_compulsory =	$ret1["policy_id"];
			}
			
			if($insuranceType == 1)//商业险
			{
				$BU = $tbOrderNo;
				
				updatetable("insurance_policy_car_third_pre_order",
							array("BU"=>$BU),
							array("pre_order_id"=>$pre_order_id)
							);
				
				$outOrderId_commercial = $outOrderId;
				
				/////////生成商业险///////////////////////////////////////////////
				ss_log("准备生成商业险投保单，gen_policy_commercial");
				$ret2 = $this->gen_policy_commercial(	$obj,
														$orderInfo,
														$cart_insure_id,
														$attribute_id_commercial
														);
				
				$totalModalPremium_commercial = $ret2["totalModalPremium"];
				$policy_id_commercial =	$ret2["policy_id"];
			}
		}//foreach
			 
		////////////////更新批量单的信息///////////////////////
		$total_apply_num = 2;
		$totalModalPremium = $totalModalPremium_compulsory + $totalModalPremium_commercial;//$POST['totalModalPremium'];//所有保单的总价，就是订单总价
		updatetable("cart_insure",
					array(	"total_apply_num"=>$total_apply_num,
					"total_price"=>$totalModalPremium
					),
					array("rec_id"=>$cart_insure_id)
					);



		
		///////////////////////////////////////////////////////////////////
		/*				
		$sql = "SELECT * form bx_goods WHERE tid='$attribute_id' LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$goods = $_SGLOBAL['db']->fetch_array($query);
		$goods_id = $goods['goods_id'];
		*/
		//////////////////////////根据发送的订单号找到原先的订单号和代理人uid///////////////////////////////////////
		//goods_id，商品名，主要用在订单显示上
		$obj_ThirdOrder = new ThirdOrder();
		$order_id = $obj_ThirdOrder->gen_order($goods_id,$agent_uid,$cart_insure_id);
	
		ss_log("after gen_order, order_id: ".$order_id);
		////////////////////////////////////////////////////////////////
		//$outOrderId
		//$payUrl
		//$payOutTime
		//三种属性id已经在前面和t_insurance_policy_car_third_pre_order关联了
		//payUrl='$payUrl',
		$sql = "UPDATE t_insurance_policy_car_third_pre_order SET order_id='$order_id', 
				outOrderId_compulsory='$outOrderId_compulsory',outOrderId_commercial='$outOrderId_commercial',
				policy_id_compulsory='$policy_id_compulsory',policy_id_commercial='$policy_id_commercial',
				payUrl='$payUrl'   
				WHERE pre_order_id='$pre_order_id'";
		ss_log($sql);
		$_SGLOBAL['db']->query($sql, 'SILENT');
		
		
		$quoteTime_int = strtotime($quoteTime);//quoteTime,报价时间,投保接口时，此字段必填。用户提交投保成功的时间。
		$sql = "UPDATE bx_order_info SET add_time='$quoteTime_int' WHERE order_id='$order_id'";
		ss_log($sql);
		$_SGLOBAL['db']->query($sql, 'SILENT');
		//////////////////////////////////////////////////////////////////////////////
		
		updatetable("cart_insure",
					array("order_id"=>$order_id),
					array("rec_id"=>$cart_insure_id)
					);
					
		return true;
	}
	
	//支付同步接口
	public function sun_process_car_paid_sync($obj)
	{
		ss_log("into ".__FUNCTION__);
		global $_SGLOBAL;
		//////////////////////////////////////////////////////////
		//交强险和商业险都挂在一个订单下面，所以找到订单就找到	
		$order_pre_attr = $this->get_pre_order($obj);
		$pre_order_id = $order_pre_attr['pre_order_id'];
		$order_id = $order_pre_attr['order_id'];
		
		ss_log("pre_order_id：".$pre_order_id);
		ss_log("order_id：".$order_id);
		
		if(empty($order_id))
		{
			ss_log("error,order_id is null ,so return");
			return false;
		}
		

		if(empty($pre_order_id))
		{
			ss_log("error,pre_order_id is null ,so return");
			return false;
		}
		
		ss_log("before loop orderInfoList" );
		
		$orderInfoList = $obj['orderInfoList']['orderInfo'];
		foreach ($orderInfoList as $key=>$value)
		{
			$orderInfo = $value;
			
			//两者是一样的时间
			$outPayId = $orderInfo['outPayId'];//支付序列号
			$payTime = $orderInfo['payTime'];
			$payWay = $orderInfo['payWay'];
			
			if($orderInfo['insuranceType'] == 0)//交强险
			{
				ss_log("交强险,outPayId: ".$outPayId);
				ss_log("交强险,payTime: ".$payTime);
				ss_log("交强险,payWay: ".$payWay);
			}
			elseif($orderInfo['insuranceType'] == 1)//商业险
			{
				ss_log("商业险,outPayId: ".$outPayId);
				ss_log("商业险,payTime: ".$payTime);
				ss_log("商业险,payWay: ".$payWay);
			}
		
		}
		
		//$payUrl
		//$payTime
		//$outPayId（交易号）,支付流水号,商业险和交强险是一样的
		
		
		$payTime_int = strtotime($payTime);//quoteTime,报价时间,投保接口时，此字段必填。用户提交投保成功的时间。
		
		//order_amount=''
		$sql = "UPDATE bx_order_info SET pay_time='$payTime_int',confirm_time='$payTime_int',pay_name='$payWay', 
		pay_status='2',order_status='1' WHERE order_id='$order_id'";
		ss_log($sql);
		$_SGLOBAL['db']->query($sql, 'SILENT');
		
		$sql = "UPDATE t_insurance_policy_car_third_pre_order SET outPayId='$outPayId' WHERE pre_order_id='$pre_order_id'";
		ss_log($sql);
		$_SGLOBAL['db']->query($sql, 'SILENT');
		
		/////////////////支付成功后分配佣金/////////////////////////////////////////////////
		
		$sql = "SELECT * FROM bx_order_info WHERE order_id='$order_id' LIMIT 1";
		ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
		$order_attr = $_SGLOBAL['db']->fetch_array($query);
		$order_sn = $order_attr['order_sn'];
		
		
		//$attribute_id_compulsory = $order_pre_attr["attribute_id_compulsory"];
		//$attribute_id_commercial = $order_pre_attr["attribute_id_commercial"];
		
		ss_log("支付成功后，就进行佣金的分配");
		$result_attr = sinosig_assign_commision($order_attr);
		
		return true;
	}
	
	//出单同步接口
	public function sun_process_car_policy_sync($obj)
	{
		ss_log("into ".__FUNCTION__);
		global $_SGLOBAL;
		////////////////////////////////////////////////////////////////
		
		$order_pre_attr = $this->get_pre_order($obj);
		$pre_order_id = $order_pre_attr['pre_order_id'];
		$order_id = $order_pre_attr['order_id'];
		$policy_id_compulsory = $order_pre_attr['policy_id_compulsory'];
		$policy_id_commercial = $order_pre_attr['policy_id_commercial'];
		
		
		ss_log("pre_order_id：".$pre_order_id);
		ss_log("order_id：".$order_id);
		ss_log("policy_id_compulsory：".$policy_id_compulsory);
		ss_log("policy_id_commercial：".$policy_id_commercial);
		
		
		if(empty($pre_order_id))
		{
			ss_log("pre_order_id is null ,so return");
			return false;
		}
		
		if(empty($order_id))
		{
			ss_log("order_id is null ,so return");
			return false;
		}
		
		if(empty($policy_id_compulsory))
		{
			ss_log("policy_id_compulsory is null ,so return");
			return false;
		}
		
		if(empty($policy_id_commercial))
		{
			ss_log("policy_id_commercial is null ,so return");
			return false;
		}

		//$payUrl
		//$payTime
		//$outPayId,支付流水号
		//$payWay
		
		ss_log("before loop orderInfoList" );
		$orderInfoList = $obj['orderInfoList']['orderInfo'];
		
		$count = 0;
		$ret_msg = "投保成功，请到保险公司网站查询电子保单！";
		foreach ($orderInfoList as $key=>$value)
		{
			$orderInfo = $value;
				
			if($orderInfo['insuranceType'] == 0)//交强险
			{
				ss_log("更新交强险, policy_id_compulsory: ".$policy_id_compulsory);
				$policy_id = $policy_id_compulsory;
			
			}
			elseif($orderInfo['insuranceType'] == 1)//商业险
			{
				ss_log("更新商业险保单, policy_id_commercial: ".$policy_id_commercial);
				$policy_id = $policy_id_commercial;
			}
			
			////////////////////////////////////////////////////////////////////////
			$policy_no = $orderInfo['policyId'];
			$policyTime = $orderInfo['policyTime'];
			$policyUrl = $orderInfo['policyUrl'];
			$payWay = $orderInfo['payWay'];
			
			ss_log("policyTime: ".$policyTime);
			$policyTime_int = strtotime($policyTime);
			
			//这个时候更新投保单的状态
			$sql = "UPDATE t_insurance_policy SET policy_no='$policy_no',
			pay_status='2',policy_status='insured', ret_msg='$ret_msg',
			dateline='$policyTime_int' WHERE policy_id='$policy_id'";
			ss_log($sql);
			$_SGLOBAL['db']->query($sql, 'SILENT');
			
			$count++;
		
		}
		
		
		if($count)
		{
			$sql = "UPDATE bx_order_info SET insured_policy_num='$count',pay_name='$payWay' WHERE order_id='$order_id'";
			ss_log($sql);
			$_SGLOBAL['db']->query($sql, 'SILENT');
		}
		
		return true;
		
	}
	
	///////////////////////////////////////////////////////////////////////////////
	private function get_pre_order($obj)
	{
		ss_log("接收到阳光的信息，sun_process_car_proposal_sync");
		
		global $_SGLOBAL;
		////////////////////////////////////////////////////////////////////////////////////
		$tbOrderNo = $obj['orderInfoList']['orderInfo'][0]['tbOrderNo'];
		ss_log("first btbOrderNo: ".$tbOrderNo);
		
		//BU为商业险订单前缀，TR为交强险订单前缀
		$sql = "SELECT * FROM t_insurance_policy_car_third_pre_order WHERE TR='$tbOrderNo' OR BU='$tbOrderNo' LIMIT 1";
		ss_log($sql);
		
		$query = $_SGLOBAL['db']->query($sql);
		$order_pre_attr = $_SGLOBAL['db']->fetch_array($query);
		
		return $order_pre_attr;
		
		
		
	}
	
	//产生交强险投保单
	private function gen_policy_compulsory(	$obj,
									$orderInfo,
									$cart_insure_id,
									$attribute_id
									)
	{
		/////////////////////////////////////////////////////////////////////////////////////////
		ss_log("into ".__FUNCTION__);
				
		//////////////交强险保费和车船税////////////////////////////////////////////////////////////
		$insurePremium = $orderInfo['compulsory']['insurePremium'];
		$travelTax = $orderInfo['compulsory']['travelTax'];
	
		$totalModalPremium = $insurePremium+$travelTax;
	
		//////////////////////////////////////////////////////////////////////////////////////////
		$insurer_code = "sinosig";//SUN_CAR";//阳光车险，这是我们定义的
		
		$product_code =  "100";//这个是阳光保险定义的险种代码，测试环境和正式环境都可固定
	
		//////////////////////////////////////////////////////////////////////////////////////////
	
		$obj_ThirdOrder = new ThirdOrder();
		$product_attr = $obj_ThirdOrder->find_product_by_code($attribute_id,$product_code);
		$product_id = $product_attr['product_id'];
		ss_log("find product id: ".$product_id);
	
		//准备增加到投保单的产品中
		$list_product_ids = array();
		$list_product_ids[] = $product_id;//只是一个产品
		
		////////////////////////////////////////////////////////////////
		$policy_id = $this->gen_policy_single_car(	$obj,
									$insurer_code,
									$cart_insure_id,
									$list_product_ids,
									$totalModalPremium
									);
	
		///////////////增加到附加表保费信息表中一份//////////////////////////////////////////////
		$insertsqlarr = array(
				"policy_id"=>$policy_id,
				"product_id"=>$product_id,//把产品id也保存起来
				"insurePremium" => $orderInfo['compulsory']['insurePremium'],
				"travelTax" => $orderInfo['compulsory']['travelTax']
				//"insureAmount" => $orderInfo['compulsory']['insureAmount']//交强险不需要保额的，是固定的。
		);
	
		$var = print_r($insertsqlarr , true);
		ss_log("insurance_policy_car_sun_compulsory:--------------".$var);
		
		inserttable("insurance_policy_car_sun_compulsory", $insertsqlarr);
		
		/////////////////////////////////////////////////////////////////////////////
		$ret = array("totalModalPremium"=>$totalModalPremium,
				"policy_id"=>$policy_id
		);
	
		return $ret;
	}
	
	//产生商业险投保单
	private function gen_policy_commercial( $obj,
											$orderInfo,
											$cart_insure_id,
											$attribute_id
											)
	{
		ss_log("into ".__FUNCTION__);
		//////////////////找到商业险下面的所有产品//////////////////////////////////////

		$insurePremium = $orderInfo['commercial']['insurePremium'];
		ss_log("商业险的总保费： ".$insurePremium);
		
		$insurer_code = "sinosig";//SUN_CAR";//阳光车险,我们定义的
		$totalModalPremium = $insurePremium;
		
			
		///////////////////////////////////////////////////////////////////////////
	
		$obj_ThirdOrder = new ThirdOrder();
		$list_product_ids = array();
		
		$insuranceList = $orderInfo['commercial']['insuranceList']['insure'];
		if(empty($insuranceList))
		{
			ss_log("insuranceList is empty,so return");
			return false;
		}
		
		//把各个商业险列出来，所以这些商业险如果没设置到商业险种上，则找不到产品id。
		foreach($insuranceList as $key=>$value)
		{
			$product_code =  $value['insureCode'];
			ss_log("insureCode: ".$product_code);
			
			//如果这里没把每个子险种配置到数据库中，则会查找不到
			$product_attr = $obj_ThirdOrder->find_product_by_code($attribute_id,$product_code);
			$product_id = $product_attr['product_id'];
			$list_product_ids[] = $product_id;//增加到产品列表中
	
		}
		
		////////////////////////////////////////////////////////////////
		$policy_id = $this->gen_policy_single_car(	$obj,
													$insurer_code,
													$cart_insure_id,
													$list_product_ids,
													$totalModalPremium
												 );

		
		/////////////下面会把同一个投保单下面的多个自保险都统一关联到保单下面，包括代码/////////////////////////////////////////
		//所以前面即使没配置，也不会弄丢的
		foreach($insuranceList as $key=>$value)
		{
			$product_code =  $value['insureCode'];
	
			$product_attr = $obj_ThirdOrder->find_product_by_code($attribute_id,$product_code);
			$product_id = $product_attr['product_id'];
	
			//插入多条，但是绑定同一个policy_id
			$insertsqlarr = array(
					"policy_id"=>$policy_id,
					"product_id"=>$product_id,
					"insureType" =>$value['insureType'],
					"insureCode"=>$value['insureCode'],
					"insureName"=>$value['insureName'],
					"insureAmount" => $value['insureAmount'],
					"insurePremium" => $value['insurePremium']
			);
	
			$var = print_r($insertsqlarr , true);
			ss_log("insurance_policy_car_sun_commercial:--------------".$var);
			inserttable("insurance_policy_car_sun_commercial", $insertsqlarr);
		}
	
		/////////////////////////////////////////////////////////////////////
		$ret = array("totalModalPremium"=>$totalModalPremium,
					 "policy_id"=>$policy_id
					);
	
		return $ret;
	}
	
	private function gen_policy_single_car(	$obj,
										$insurer_code,
										$cart_insure_id,
										$list_product_ids,
										$totalModalPremium
									 )
	{
		ss_log("into ".__FUNCTION__);
		//////////////////////////////////////////////////////////////////////////////////////
		$startDate = $obj['applyInfo']['effectiveDate'];//保单起保日期,格式为yyyy-MM-dd
		//$var = print_r($obj['applyInfo'] , true);
		//ss_log("ApplyInfo--------------".$var);
		
		ss_log("gen_policy_single_car:startDate->".$startDate);
		$endDate = "";
		
		$partner_code = "";
		
		/////////////////////////////////////////////////////////////////////////////////////
		$applyInfo  = $obj['applyInfo'];
		
		//////////////代理人信息/////////////////////////////////////////////////////////////
		$buyerId = $applyInfo['buyerId'];
		$buyerNick = $applyInfo['buyerNick'];
		$auctionId = $applyInfo['auctionId'];
		$auctionTitle = $applyInfo['auctionTitle'];
		$promotionInfo = $applyInfo['promotionInfo'];
		
		ss_log("buyerId: ".$buyerId);
		ss_log("auctionId: ".$auctionId);
		
		//车主信息/////////////////////////////////////////////////////////////////////////
		
		$vehicleOwnerInfo = $applyInfo['vehicleOwnerInfo'];
		
		///////////////////////////////////////////////////////////////////////////
		$driverName = $vehicleOwnerInfo['driverName'];//老王
		$cardType = $vehicleOwnerInfo['cardType'];//01
		$cardNo = $vehicleOwnerInfo['cardNo'];//130721199005055159
		
		//////////////////contact////////////////////////////////////////////
		$contact = $applyInfo['contact'];
		
		//人员联系方式
		$mobile = $contact['mobile'];
		$email = $contact['email'];
		$address = $contact['address'];
		
		////////////////投保人信息/////////////////////////////////////////////////////////
		$user_info_applicant = array();
		$user_info_applicant['certificates_type'] = $cardType;
		$user_info_applicant['certificates_code'] = $cardNo;
		$user_info_applicant['fullname'] = $driverName;
		
		$user_info_applicant['mobiletelephone'] = $mobile;
		$user_info_applicant['email'] = $email;
		$user_info_applicant['address'] = $address;
		//////////////////////////////////////////////////////////////////////////////////////
		//$policy_type = "car";
		$obj_ThirdOrder = new ThirdOrder();
		
		/////////////产生一个标准的投保单//////////////////////////////////////////
		$policy_id = $obj_ThirdOrder->gen_policy_single($insurer_code,
														$cart_insure_id,
														$list_product_ids,
														$user_info_applicant,
														$totalModalPremium,
														$startDate,
														$endDate,
														$partner_code
														);
		
	
		ss_log("after gen_policy_single ,policy_id: ".$policy_id);
		///////////////////车辆信息/////////////////////////////////////////////////
		$vehicleInfo = $applyInfo['vehicleInfo'];
		
		$VIN = $vehicleInfo['VIN'];//67656584686765754
		$carCity = $vehicleInfo['carCity'];//440100
		$carProvince = $vehicleInfo['carProvince'];//440000
		$carID = $vehicleInfo['carID'];//粤A55514
		$carType = $vehicleInfo['carType'];//北京现代BH7240MW轿车
		$engineId = $vehicleInfo['engineId'];//547856755464
		$firstRegisterDate = $vehicleInfo['firstRegisterDate'];//2015-03-10
		
		
		////////把车辆的信息保存起来////////////////////////////////////////////////
		$setsqlarr = array(	
							"policy_id" =>$policy_id,
							"VIN" => $vehicleInfo['VIN'],//车架号,67656584686765754
							"carCity" => $vehicleInfo['carCity'],//车辆行驶城市代码, 数字，440100
							"carProvince" => $vehicleInfo['carProvince'],//车辆行驶省份代码，数字，440000
							"carID"=> $vehicleInfo['carID'],//车牌号,粤A55514
							"carType" => $vehicleInfo['carType'],//行驶证车辆型号，北京现代BH7240MW轿车
							"engineId" => $vehicleInfo['engineId'],//发动机号，547856755464
							"displacement"=>$vehicleInfo['displacement'],//排气量,数值
							"seatNumber"=>$vehicleInfo['seatNumber'],//座位数
							"transferFlag"=>$vehicleInfo['transferFlag'],//是否过户车,数值
							"transferDate"=>$vehicleInfo['transferDate'],//过户日期，日期
							"firstRegisterDate" => $vehicleInfo['firstRegisterDate']//车辆初登日期，2015-03-10);
						);
		
		$var = print_r($setsqlarr , true);
		ss_log("insurance_policy_car_vehicleinfo:--------------".$var);
		
		$id = inserttable("insurance_policy_car_vehicleinfo", $setsqlarr,true);
		ss_log("insert insurance_policy_car_vehicleinfo".$id);
		
		return $policy_id;
		
	}

}//Sun_Car
//process_product_pingan_simple($product);

//$ret_policy_output = process_product_pingan_simple($product,$POST);