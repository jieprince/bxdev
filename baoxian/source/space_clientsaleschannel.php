<?php

ss_log("into clientsaleschannel");
//define('S_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
//include_once(S_ROOT.'/source/function_cachelock.php');
ss_log(S_ROOT.'source/function_cachelock.php');

//include_once(S_ROOT.'source/function_cachelock.php');
ss_log("into clientsaleschannel,after: function_cachelock");
//client_sales_channel
$channel = empty($_GET['channel'])?'':$_GET['channel'];


$theurl = "cp.php?ac=product_buy";
//首先得到保险公司的产品列表



/////////////////////////////////////////////////////////
if($channel =="ydwl")
{
	
	////////////////////////////////////////////////
	$ret_attr = array();
	/////////////////////////////////////////////////////////
	$olduid = $_SGLOBAL['supe_uid'];//代理人
	$oldusername = $_SGLOBAL['supe_username'];
	

	////////////////////////////////////////////////////////////

	
	//ss_log("json_data post: ".$json_data_post);
	
	//$json = '{"a":1,"b":2,"c":3,"d":4,"e":5}';
	
	
	//$urldecode_data = urldecode($json_data_post);
	//ss_log("url decode data: ".$urldecode_data);
	/*
	$magic_quote = get_magic_quotes_gpc();
	if(empty($magic_quote)) 
	{//没打开的时候
		
	}
	
	if(ini_get("magic_quotes_gpc")=="1")
	{
		$json_string = stripslashes($json_data_post);
	}
	*/

	
	$json_data_post = $_POST['policydata'];
	$json_string = stripslashes($json_data_post);
	
	$jsondata = json_decode($json_string,true);
		
	$v = var_export($jsondata, TRUE);
	ss_log("------接收到丢不了的原始报文是: " . $v);
		
	//echo var_dump($jsondata);
	$ret_json_err = json_last_error();
	
	if($ret_json_err!=JSON_ERROR_NONE)
	{
		$str_err = "post poblicy json data is NULL!";
		ss_log($str_err);
		
		$ret_attr['status']= "110";
		$ret_attr['info']= $str_err;
		echo json_encode($ret_attr);
		exit(0);
	}
	
		
	/*
	if(empty($jsondata))
	{
	
	}
	*/
	
	//ss_log("product_id: ".$jsondata['product_id']);
	//ss_log("user_id: ".$jsondata['user_id']);

	$uid = $agent_uid = intval($jsondata['user_id']);
	$peer_order_id = $jsondata['peer_order_id'];//对方的订单id
	$sig   = trim($jsondata['sig']);
	$hardwareprice = trim($jsondata['hardwareprice']);
	$en_str = "fei93-tgie13jwi.6s78wwmerti";
	$totalpremium = trim($jsondata['totalpremium']);//总保费
	
	//收货地址和邮编
	$user_addr = trim($jsondata['user_addr']);
	$user_postcode = trim($jsondata['user_postcode']);
		
	ss_log("diubuliao peer_order_id: ".$peer_order_id);
	ss_log("diubuliao hardwareprice: ".$hardwareprice);
	ss_log("diubuliao totalpremium: ".$totalpremium);
	
	if(empty($hardwareprice))//表的价格为空
	{
		$ret_attr['status']= "111";
		$ret_attr['info']= "watch price is null!";
		echo json_encode($ret_attr);
		exit(0);
	}
	
	////////////////////////////////////////////////////////////
	/////////////////找代理人的信息///////////////////////////////////////////////
	if($uid)
	{
		$wheresql = "user_id='$uid'";////agent uid
		$sql = "SELECT * FROM bx_users WHERE $wheresql";
	
		//ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
		$agent_userinfo = $_SGLOBAL['db']->fetch_array($query);
		if(empty($agent_userinfo['user_id']))
		{
	
			$ret_attr['status']= "113";
			$ret_attr['info']= "this user is not exist!";
			//$ret_attr['data']= array();
			echo json_encode($ret_attr);
			exit(0);
		}
	
		$agent_uid = $agent_userinfo['user_id'];
		$agent_username = $agent_userinfo['user_name'];
	
		//ss_log("agent_uid: ".$agent_uid." agent_username: ".$agent_username);
	}
	elseif(0 == $uid)
	{
		$agent_uid = 1674;
		$agent_username = 'diubuliao';
	}
	
	////////////////////////////////////////////////////////////
	$lock = new CacheLock('key_name');
	$lock->lock();
	///////////////////////////////////////////////////////////////
	
	
	if($peer_order_id)//  就是说可能出现刷新导致重复提交。
	{
		//先查找为了防止重复
		$wheresql = "peer_order_id='$peer_order_id'";
		$sql = "SELECT * FROM ".tname('order_device')." WHERE $wheresql LIMIT 1";
		ss_log($sql);
		
		$query = $_SGLOBAL['db']->query($sql);
		$attr_order_other_device = $_SGLOBAL['db']->fetch_array($query);
		if($attr_order_other_device['peer_order_id'])
		{//已经存在了对方的这个订单号
			$ret_attr['status']= "113";
			$ret_attr['info']= "your order id is already exisit!";
			
			ss_log($ret_attr['info']);
			//my_order_id
			//
			$ret_attr['data']= array();
	        $ret_attr['data']['user_id'] = $attr_order_other_device['agent_uid'];
	        $ret_attr['data']['order_id'] = $attr_order_other_device['my_order_id'];
			echo json_encode($ret_attr);
			
			$lock->unlock();
			exit(0);
		}
		else//没有，则插入一条
		{
			$order_status ="";
			$type  = "watch";
			
			$service_fee = 0;//1;//$hardwareprice*0.20; 每个订单为1元
			$agent_user_id = $uid;
			
			$order_arr = array(
					'agent_uid'=> $agent_uid,
					'agent_username'=> $agent_username,
					//'my_order_id'=> $order_id,
					'channel' => $channel,
					'type'=>  $type,
					'peer_order_id' =>  $peer_order_id,
					'hardware_price' => $hardwareprice,
					'service_fee' => $service_fee,
					'dateline' => $_SGLOBAL['timestamp']
			);
			
			ss_log("--will before insert order_device is :");
			
			//这个表中记录的为丢不了的信息
			$order_arr = saddslashes($order_arr);//add by wangcya , 20141211,for sql Injection
			$order_device_id = inserttable('order_device', $order_arr,true);
			ss_log("--will after insert order_device id is :".$order_device_id);
			if($order_device_id<=0)
			{
				$msg = "your order id is alread exist! diubuliao_order_id: ".$peer_order_id;
				ss_log($msg);
				
				$ret_attr['status']= "115";
				$ret_attr['info']= $msg;
				echo json_encode($ret_attr);
				
				$lock->unlock();
				exit(0);
			}
			
		}
	
	}
	else
	{//对方的订单不存在，也不行。
		$ret_attr['status']= "114";
		$ret_attr['info']= "your order id is null!";
		
		ss_log($ret_attr['info']);
		
		echo json_encode($ret_attr);
		
		$lock->unlock();
		exit(0);
	
	}
	
	//////////////////////////////////////////////////////
	//logic here
	$lock->unlock();
	//使用过程中需要注意下文件锁所在路径需要有写权限.
	
	//////////////////////////////////////////////////////
	$flag_have_insurance  = 1;//
	$relationshipWithInsured = '';
	if(!empty($totalpremium))//有保险的情况
	{//有总保费，也就是证明有保险
		$flag_have_insurance = 1;
		
		$totalModalPremium = $hardwareprice+$totalpremium;
		//$totalModalPremium = $totalpremium;//add by wangcya , 20140826
		/////////////////////////////////////////////////////////////////////
		//ss_log("totalpremium: ".$totalpremium);
		////////////////////////////////////////////////////////////////
		$relationshipWithInsured = $jsondata['relationshipwithInsured'];//与被保人关系
		$startdate = $jsondata['startdate'];
		$enddate   = $jsondata['enddate'];
		
		$product_id = intval($jsondata['product_id']);//$jsondata['product_id'];
		ss_log("diubuliao product_id: ".$product_id);
		
		$parent   = $jsondata['parent'];
		$children   = $jsondata['children'];
		/////////////////////////////////////////////////////////////////////////////////
		$parent_assured_certificates_type = trim($parent['assured_certificates_type']);
		$parent_assured_certificates_code = trim($parent['assured_certificates_code']);
		$parent_assured_birthday = $parent['assured_birthday'];
		$parent_assured_fullname = $parent['assured_fullname'];
		$parent_assured_sex = $parent['assured_sex'];
		$parent_assured_mobilephone = $parent['assured_mobilephone'];
		$parent_assured_email = $parent['assured_email'];
		
		$children_assured_certificates_type = trim($children['assured_certificates_type']);
		$children_assured_certificates_code = trim($children['assured_certificates_code']);
		$children_assured_birthday = $children['assured_birthday'];
		$children_assured_fullname = $children['assured_fullname'];
		$children_assured_sex = $children['assured_sex'];
		$children_assured_mobilephone = $children['assured_mobilephone'];
		$children_assured_email = $children['assured_email'];
		
		
		if(		empty($parent_assured_certificates_type)||
				empty($parent_assured_certificates_code)||
				empty($children_assured_certificates_type)||
				empty($children_assured_certificates_code))
		{
			$ret_attr['status']= "112";
			$ret_attr['info']= "policy info is not completed!";
			//$ret_attr['data']= array();
			echo json_encode($ret_attr);
			exit(0);
		}
		
		///////////////////////////////////////////////////////////////////////////
		$md_str_sourece = $peer_order_id.$uid.$product_id.$parent_assured_certificates_code.$children_assured_certificates_code.$totalpremium.$hardwareprice.$en_str;
		
	}
	else//只有表
	{
		$totalModalPremium = $hardwareprice;
		
		$flag_have_insurance = 0;
		$md_str_sourece = $peer_order_id.$uid.$hardwareprice.$en_str;
		
	}
		
	$str_sig = md5($md_str_sourece);
	/////////////////////////////////////////////////////
	
	if($str_sig!=$sig)
	{
		
		$ret_attr['status']= "112";
		$ret_attr['info']= "sig data error!";
		//$ret_attr['data']= array();
		echo json_encode($ret_attr);
		exit(0);
	}
		
	/////////////////////上面验证通过后，下面进行投保信息的填充工作/////////////////////////////////////////////
	include_once(S_ROOT.'./source/function_common.php');
	include_once(S_ROOT.'./source/function_baoxian.php');
	
	///////////形成我们本地订单///////////////////////////////////////////
	////////////////////////////////////////////////////////////
	$_SGLOBAL['supe_uid'] = $agent_uid;//代理人
	$_SGLOBAL['supe_username'] = $agent_username;
	/////////////////////////////////////////////////////////////////////
	if($flag_have_insurance)//有保险的情况
	{
	
		////////////////////////////////////////////////////////////////////////////
		$_POST['attribute_id'] = 11;//丢不了的属性
		$_POST['attribute_name'] = "丢不了产品";
		$_POST['attribute_type'] = "mproduct";
		
		////////////////////////丢不了的这种方式不是购买一个产品，而是购买多个产品的////
			
		$apply_num = 1;//intval($_POST['applyNum']);//份数
		$beneficiary= 1;//$_POST['beneficiary'];//受益人
			

		
		///////////////////////////////////////////////////
		
		////////////////////投保人的信息//////////////////
		/*公司名称：深圳市云动物联信息技术有限公司
		税务登记证号码：440300083878707
		地址：深圳市南山区南山街道南海大道以西花样年美年广场5栋605-013
		*/
			
		/////////////////////////////////////////////////////////////////////
		if('' == $relationshipWithInsured)
		{
			$relationshipWithInsured = '9';//关系。1:本人 2:配偶 3 :父子 4:父女 5:受益人 6:被保人 7:投保人 A:母子 B:母女 C:兄弟 D:姐弟 G:祖孙 H:雇佣 I:子女 9:其他 8:转换不详-->
		}
		
		
		
		////////////////////////////////////////////////////////
		$parent_in = array();
		$parent_in['certificates_type'] = $parent_assured_certificates_type;
		$parent_in['certificates_code'] = $parent_assured_certificates_code;
		$parent_in['birthday'] = $parent_assured_birthday;
		$parent_in['fullname'] = $parent_assured_fullname;
		$parent_in['gender'] = $parent_assured_sex;//modify by wangcya , 20140929,要统一
		$parent_in['mobiletelephone'] = $parent_assured_mobilephone;//modify by wangcya , 20140929,要统一
		$parent_in['email'] = $parent_assured_email;
		
		
		$children_in = array();
		$children_in['certificates_type'] = $children_assured_certificates_type;
		$children_in['certificates_code'] = $children_assured_certificates_code;
		$children_in['birthday'] = $children_assured_birthday;
		$children_in['fullname'] = $children_assured_fullname;
		$children_in['gender'] = $children_assured_sex;//modify by wangcya , 20140929,要统一
		$children_in['mobiletelephone'] = $children_assured_mobilephone;//modify by wangcya , 20140929,要统一
		$children_in['email'] = $children_assured_email;
		
		////////add by wangcya, 20140904, 把投保人信息后面携带监护人姓名////////////////////////
		
			////////////////////////////////////////////////////////
		$global_info = array();
		
		$global_info['applyNum'] = $apply_num;//份数
		$global_info['totalModalPremium'] = $totalpremium;//保单的总保费
		
		$global_info['beneficiary'] = $beneficiary;//受益人
		$global_info['startDate'] = $startdate;//保险开始日期
		$global_info['endDate'] = $enddate;//保险结束日期
		
		
		///////////////////////////////////////////////////////////////////////////
		$user_info_applicant = array();
		
		//地址和邮编保存到投保人的信息中
		$user_info_applicant['address'] = $user_addr;
		$user_info_applicant['zipcode'] = $user_postcode;
	
		//根据生日获得孩子当前的年龄
		$age = getAge($children_assured_birthday);
		$age = intval($age);
		ss_log("child age: ".$age);

		//都是以云动物联/监护人作为投保人的方式，例如：深圳市云动物联信息技术有限公司/孙汉杰
		//并且要有两个产品，所以需要两个层级subject来实现
		if($age>=10)//孩子大于十岁则走团单
		{
			ss_log("大于十岁走团单");
			//add  by wangcya , 20140910 ,不同年龄段走的是团单或者个单。
			$businessType = 2;//(非空) 业务类型 1表示个人，2表示团体
			
			//////////////////////////////////////////////////////////////////////////
			//$user_info_applicant['group_name'] = "深圳市云动物联信息技术有限公司";
			$user_info_applicant['type'] = 1;//0,被保险人，1投保人
			
			$user_info_applicant['group_name'] = "深圳市云动物联信息技术有限公司/".$parent_assured_fullname;

			$user_info_applicant['group_certificates_type'] = "01";//型，01表示组织机构代码证，02表示税务登记证，03表示异常证件-->
			$user_info_applicant['group_certificates_code'] =  "08387870-7";
						
			$user_info_applicant['company_attribute'] =  "03";//非空）单位性质，07:股份 01:国有 02:集体 33:个体 03:私营 04:中外合资 05:外商独资 08:机关事业 13:社团 39:中外合作 9:其他
			//$user_info_applicant['group_abbr'] ="";
			//$user_info_applicant['address'] = "深圳市南山区南山街道南海大道以西花样年美年广场5栋605-013";
			//$user_info_applicant['telephone'] = "";
			//$user_info_applicant['mobiletelephone'] = "";
			//$user_info_applicant['email'] = "";
			
			//////////////////////////////////////////////////////////////////////////
							

				
			/////////////////////////////////////////////////////////////////////////
			
			$product_code = "01296";
			$product_id_parent = get_product_id_by_code($product_code);
			
			$product_code = "01297";
			$product_id_child = get_product_id_by_code($product_code);
			
			//$product_id_parent = 9;
			//$product_id_child  = 10;
				
		}
		else//小于十岁则走个单。
		{
			//个蛋
			ss_log("小于十岁走个单");
			//add  by wangcya , 20140910 ,不同年龄段走的是团单或者个单。
			$businessType = 1;//(非空) 业务类型 1表示个人，2表示团体
			
			$user_info_applicant['type'] = 1;//0,被保险人，1投保人
			
			$user_info_applicant['fullname'] = "深圳市云动物联信息技术有限公司/".$parent_assured_fullname;

			$user_info_applicant['certificates_type'] = $parent_assured_certificates_type;
			$user_info_applicant['certificates_code'] =  $parent_assured_certificates_code;
				
			$user_info_applicant['gender'] = $parent_assured_sex;
			$user_info_applicant['birthday'] = $parent_assured_birthday;
			
			//////////////////////////////////////////////////////////////////
			
			$product_code = "01305";//丢不了监护人保障方案
			$product_id_parent = get_product_id_by_code($product_code);
			
			$product_code = "01306";//丢不了儿童保障方案（10岁以下）
			$product_id_child = get_product_id_by_code($product_code);
			
			//$product_id_parent = 11;
			//$product_id_child  = 12;
		}
		
		ss_log("product_id_parent: ".$product_id_parent."-----product_id_child: ".$product_id_child);
		
		
		/////////////////////////////////////////////////////////////////////

		$wheresql = "product_id='$product_id_parent'";
		$sql = "SELECT attribute_id FROM ".tname('insurance_product_base')." WHERE $wheresql LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$attr_product = $_SGLOBAL['db']->fetch_array($query);
		$attribute_id = $attr_product['attribute_id'];
		
		//$attribute_id = 11;//丢不了的产品属性
		
		ss_log("product attribute_id: ".$attribute_id);
		//////////一个subjectInfo内多个产品，多个被保险人///////////////////////////////////////////////////////////
		
		////////////////////多层级的方式来处理////////////////////////////////////
		////////方案一是父母的////////////
		$subjectInfo1 = array();
			
		$list_product_id1 = array();
		$list_product_id1[] = $product_id_parent;//这是在系统中定义的丢不了的两个产品的id。
		$subjectInfo1['list_product_id'] = $list_product_id1;
	
		$list_assured1 = array();
		$list_assured1[] = $parent_in;//被保险人列表
		$subjectInfo1['list_assured'] = $list_assured1;
		
		//////方案二是孩子的///////////////////////////////////////////
		$subjectInfo2 = array();
		
		$list_product_id2 = array();
		$list_product_id2[] = $product_id_child;//这是在系统中定义的丢不了的两个产品的id。
		$subjectInfo2['list_product_id'] = $list_product_id2;
		
		$list_assured2 = array();
		$list_assured2[] = $children_in;
		$subjectInfo2['list_assured'] = $list_assured2;
		
		////////////////////////////////////////////////////////////
		$list_subjectinfo = array();
		$list_subjectinfo[] = $subjectInfo1;
		$list_subjectinfo[] = $subjectInfo2;
			
		//////////////////////////////////////////////////////////////////////////////////
		ss_log("准备产生丢不了的批量单");
			
		$cart_insure_attr = array("user_id"=>$agent_uid,
									"product_id"=>$attribute_id,
									"product_name"=>'diubuliao',
									"total_price"=>$totalModalPremium
								 );
		
		$cart_insure_id = inserttable("cart_insure", $cart_insure_attr,true);
		
		ss_log("------cart_insure_id: ".$cart_insure_id);
	
		
		$global_info['insurer_code'] = "PAC";//厂商代码
		$global_info['insurer_name'] = "平安财险";//厂商名称
		
		$policy_id = insurance_gen_policy_order($attribute_id,
				                                $global_info, 
												$businessType,
												$relationshipWithInsured,
												$user_info_applicant, 
												$list_subjectinfo,
												$cart_insure_id
												);//产生订单和保单
		////////////上面把订单保存后，下面进行投保的工作///////////////////////////////////////
		$ret = get_policy_info($policy_id);//得到保单信息
		
		$policy_arr = $ret['policy_arr'];
		$user_info_applicant = $ret['user_info_applicant'];
		$list_subject = $ret['list_subject'];//多层级一个链表
		
		$attribute_type = $policy_arr['attribute_type'];
		///////////////////////////////////////////////////////////
		/////////////////每个公司的产品的投保都不太一样，或者每个产品不太一样，则需要区分开

		$order_id = $policy_arr['order_id'];//返回应该为订单id，因为这个时候没保单了。
		////////////////////////////////////////////////////////////////////////
		$retmsg = "diubuliao gen_policy_order success!";
		ss_log($retmsg);
	
	}//有保险的情况
	else//没有保险，只有表的情况
	{
		/*
		$order_status ="";
		$order_num = getRandOnly_Id(0);//1400150010655;//$POST['applicant_fullname'];//自己本地生成的唯一的号码
	
		$totalModalPremium = $hardwareprice;
		$order_arr = array(	'order_num'=>$order_num,
				//'policy_id'=>$policy_id,
				'order_premium' => $totalModalPremium,
				'order_status'=> $order_status,
				'uid' => $_SGLOBAL['supe_uid'],
				'username' => $_SGLOBAL['supe_username'],
				'dateline' => $_SGLOBAL['timestamp']
		);
	
		$order_id = inserttable('insurance_orders', $order_arr, 1);
		$retmsg = "post only watch success!";
		*/
		////////////////只有表的情况，则虚拟出一个产品来//////////////////////////////////////////
		$product_code = "diubuliao123";
		$product_id_parent = get_product_id_by_code($product_code);
			
		ss_log("product_id_parent: ".$product_id_parent."-----product_id_child: ".$product_id_child);
		
		
		/////////////////////////////////////////////////////////////////////
		
		$wheresql = "product_id='$product_id_parent'";
		$sql = "SELECT attribute_id FROM ".tname('insurance_product_base')." WHERE $wheresql LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$attr_product = $_SGLOBAL['db']->fetch_array($query);
		$attribute_id = $attr_product['attribute_id'];
		
		//$attribute_id = 11;//丢不了的产品属性
		
		ss_log("product attribute_id: ".$attribute_id);
	}
	
	///////////////插入一条道系统的订单中/////////////////////////////////////////////////////////////////


	define('IN_ECS', true);
	
	include_once('../includes/init.php');
	
	include_once('../includes/lib_order.php');
	include_once('../includes/inc_constant.php');
	////////////////////////////////////////////////////////////////
	

	
	$user_id = $uid;
    if(0 == $user_id)
    {
        $user_id = 1674;
    }
	$order_status = OS_CONFIRMED;//1,已确认
	$pay_status = PS_PAYED;//2，已支付
	$goods_amount = $totalModalPremium;//这是总的保费价格
	//$insure_fee = $totalModalPremium;
	//$order_amount = $totalModalPremium;
	
	//total_fee = goods_amount + shipping_fee + insure_fee + pay_fee + pack_fee + card_fee + tax - discount;
	//$order_sn = get_order_sn(); //获取新订单号
	
	/////////////////////////是否有保险，都要增加到订单中。
	$order = array(
			'user_id'     => $user_id,
			'order_status' => $order_status,
			'shipping_status' => SS_UNSHIPPED,
			'pay_status'  => $pay_status,
			'goods_amount'=> $goods_amount,
			'order_amount' => $goods_amount,
			//'insure_fee' => $insure_fee,
			'policy_id' => $policy_id,
			'add_time'=> $_SGLOBAL['timestamp'],
			'pay_id' => 1,//add by wangcya, 20150602
			'pay_name' => '余额支付',//add by wangcya, 20150602
			'surplus' => $goods_amount,//add by wangcya, 20150602
			'confirm_time'=> $_SGLOBAL['timestamp'],//add by wangcya, 20150602
			'pay_time'=> $_SGLOBAL['timestamp'],//add by wangcya, 20150602
			'platform_id'=>'pc',//add by wangcya, 20150602
			'policy_num'=>1,//add by wangcya, 20150602
			//'insured_policy_num'=>1,//add by wangcya, 20150602,投保后会自动更新
			'cart_insure_id'=>$cart_insure_id//add by wangcya, 20150602
			);
			//withdrawed_policy_num
	

//UPDATE  `t_insurance_policy` SET pay_status =2,insured_num=1,policy_num=1 WHERE  `attribute_name` like '丢不了产品' AND policy_status='insured'
//UPDATE  `bx_order_info` SET policy_num=1,insured_policy_num=1 WHERE  `attribute_name` like '丢不了产品' AND pay_status=2
/*
 SELECT * 
FROM  `bx_order_info` AS o
LEFT JOIN  `bx_order_goods` AS og ON o.order_id = og.order_id
WHERE og.`goods_name` LIKE  '丢不了产品' GROUP BY o.order_id

 */
			/////////////////////////////////////////////////////////////
	
	
	/* 插入订单表 */
	
	$error_no = 0;
	do
	{
		$order['order_sn'] = get_order_sn(); //获取新订单号
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'), $order, 'INSERT');
	
		$error_no = $GLOBALS['db']->errno();
	
		if ($error_no > 0 && $error_no != 1062)
		{
			die($GLOBALS['db']->errorMsg());
		}
	}
	while ($error_no == 1062); //如果是订单号重复则重新提交数据
	
	$order_id = $new_order_id = $db->insert_id();
	$order['order_id'] = $new_order_id;


	////////////////增加商品到订单中///////////////////////////////////////////////
	$goods_price = $totalModalPremium;
	$goods_attr = "";
	
	//$sql = "SELECT product_id FROM " . $ecs->table('products') . " WHERE goods_attr = '$goods_attr' AND goods_id = '" . $goods['goods_id'] . "'";
	//$product_id = $db->getOne($sql);
	$product_id = 0;
	/////////////////////////////////////////////////////////////////////////////
	
	$sql = "INSERT INTO " . $ecs->table('order_goods') . "( " .
			"order_id, goods_id, goods_name, goods_sn, product_id, goods_number, market_price, ".
			"goods_price, goods_attr, is_real, extension_code, parent_id, is_gift) ".
			" SELECT '$new_order_id', goods_id, goods_name, goods_sn, '$product_id','1', market_price, ".
			"'$goods_price', '$goods_attr', is_real, extension_code, 0, 0 ".
			" FROM bx_goods WHERE tid = '$attribute_id'";
	
	ss_log($sql);
	$db->query($sql);
	
	/////////////////////根据订单进行佣金的结算,该订单下面多个产品呢？////////////////////////////////////////////////////////////
	if($flag_have_insurance)//有保险的情况下才算佣金
	{
		/////////start add by wangcya , 20140830, 在这里生成了订单，所以应该也在这里把订单和保单关联起来
		$order_sn = $order['order_sn'];
		
		$sql = "UPDATE t_insurance_policy SET order_id='$new_order_id',order_sn='$order_sn',pay_status='2' WHERE policy_id=".$policy_id;
		ss_log($sql);
		$GLOBALS['db']->query($sql);
		
		/////////end add by wangcya , 20140830, 在这里生成了订单，所以应该也在这里把订单和保单关联起来
		ss_log("fron diubuliao, into assign_commision_and_post_policy");
		//mod by zhangxi, 20150305, 修改函数名,分配佣金和投保
		//$result_attr = yongJin2($order['order_sn']);
		$result_attr = assign_commision_and_post_policy($order['order_sn']);
		
		$retcode = $result_attr['retcode'];
		$retmsg  = $result_attr['retmsg'];
	}
	
	/////////////////////保存地址信息到发票中//////////////////////////////////////////////
	/*
	$address = $user_addr;
	$zipcode = $user_postcode;
	
	$sql = "INSERT INTO ". $ecs->table('receipt') ."(fp_title, username, phone, tel,address, postscript) VALUES ('".$fp_title."','".$username."','".$phone."','".$tel."','".$address."','".$postscript."')";
	$GLOBALS['db']->query($sql);
	$fp_id = $db->insert_id();
		
	//更新订单
	$sql="UPDATE ".$ecs->table('order_info') ." SET receipt_id=".$fp_id." WHERE order_id=".$new_order_id;
	$GLOBALS['db']->query($sql);
	*/
	
		
	updatetable('order_device', array('my_order_id'=>$order_id), array('order_device_id'=>$order_device_id));
	/////////////////////////////////////////////////////////
	ss_log("--will ret_msg :" . $retmsg);
	
	$ret_attr['status']= $retcode;//"0"; modify by wangcya, 20141010
	$ret_attr['info']= $retmsg;
	
	/////////////////////////////////////////////////////
	$ret_attr['data']= array();
	
	$ret_attr['data']['user_id'] = $uid;
	$ret_attr['data']['order_id'] = $order_id;
	$ret_attr['data']['policy_id'] = $policy_id;
	
	$v = var_export($ret_attr, TRUE); 
	ss_log("--will before return json is : ". $v);
	$str_json = json_encode($ret_attr);
	ss_log("--will return json is : ".$str_json);
	echo $str_json;
	
	/////////////////////////////////////////////////////////
	$_SGLOBAL['supe_uid'] = $olduid;//代理人
	$_SGLOBAL['supe_username'] = $oldusername;
	
	//echo "aaaaaaaaaaa";
}//云动互联

exit(0);