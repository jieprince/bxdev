<?php
//第三方订单需要的一些公共函数
define('S_ROOT1', dirname(__FILE__).DIRECTORY_SEPARATOR);
include_once(S_ROOT1.'../common.php');

//include_once(S_ROOT.'../includes/lib_goods.php');
//include_once(S_ROOT.'../includes/lib_common.php');
include_once(S_ROOT . '../includes/init.php');
include_once(S_ROOT . '../includes/lib_order.php');
include_once(S_ROOT . '../includes/lib_clips.php');

class ThirdOrder {

	public function ThirdOrder()
	{


	}
	
	//产生预先存放的订单号
	public function gen_pre_order(	$uid,
									$goods_id,
									$attribute_id,
									$attribute_id_compulsory,
									$attribute_id_commercial
									)//,$TR,$BU)
	{
		global $_SGLOBAL;
		ss_log("into ".__FUNCTION__);
		///////////////////////////////////////////////////////////////////////////////////////////
		//$uid = $_SGLOBAL["supe_uid"];
		
		//下面这两个是需要在数据库中设置的，交强险和商业险的产品属性ID
		//$attribute_id_compulsory = 43;
		//$attribute_id_commercial = 44;
		
		$attr = array(
				"uid"=>$uid,
				"goods_id"=>$goods_id,
				"attribute_id"=>$attribute_id,
				"attribute_id_compulsory"=>$attribute_id_compulsory,
				"attribute_id_commercial"=>$attribute_id_commercial,
				//"TR"=>$TR,
				//"BU"=>$BU
		);
		
		$ore_order_id = inserttable("insurance_policy_car_third_pre_order",
				      $attr,
					  true
		  			);
		
		/*	
		$order_id = new_order();
		
		//把现在新成成的两个订单号关联到我们系统的订单中。将来要用
		$sql = "UPDATE bx_order_info SET TR='$TR',BU='$BU' WHERE order_id='$order_id'";
		//ss_log($sql);
		$_SGLOBAL['db']->query($sql, 'SILENT');
		*/
		$attr['pre_order_id'] = $ore_order_id;
		return $attr;
	}
	
	public function find_product_by_code($attribute_id,$product_code)
	{
		global $_SGLOBAL;
		ss_log("into ".__FUNCTION__);
		///////////////////////////////////////////////////////////////////////////
		
		$wheresql = "attribute_id='$attribute_id' AND product_code='$product_code'";
		
		$sql = "SELECT * FROM ".tname('insurance_product_base')." WHERE $wheresql";
		
		ss_log($sql);
		
		$query = $_SGLOBAL['db']->query($sql);
		
		$product_arr = $_SGLOBAL['db']->fetch_array($query);
		
		return $product_arr;
	}
	
	public function gen_policy_single(	$insurer_code,
										$cart_insure_id,
										$list_product_ids,
										$user_info_applicant,
										$totalModalPremium,
										$startDate,
										$endDate,
										$partner_code
									  )
	{
		global $_SGLOBAL;
		ss_log("into ".__FUNCTION__);
		//////////////////可以拿第一个产品来找吗？，全局信息是可以的，但是每个子险种作为产品id,这个如果提前没配置到，则在会找不到的////
		$product_id = $list_product_ids[0];
		
		$wheresql = "p.product_id='$product_id'";
		
		//inner JOIN ".tname('insurance_product_additional')." padd ON padd.product_id=p.product_id
		
		$sql = "SELECT p.*,patt.* FROM ".tname('insurance_product_base')." p 
		inner JOIN ".tname('insurance_product_attribute')."  patt ON patt.attribute_id=p.attribute_id
		WHERE $wheresql";
		
		ss_log($sql);
		
		$query = $_SGLOBAL['db']->query($sql);
		
		$product = $product_arr = $_SGLOBAL['db']->fetch_array($query);
		
		$product_id = $product['product_id'];
		$insurer_code 	= $product['insurer_code'];

		//应该从外面传入进来，通过产品找到不太可靠吧，如果后台没配置，则就会出错
		$attribute_id 	= $product['attribute_id'];//从第一个产品上找到的险种，这个要决定将来的服务费率
		$attribute_name = $product['attribute_name'];
				
		$attribute_type = $product['attribute_type'];
		$insurer_code 	= $product['insurer_code'];
		$insurer_name 	= $product['insurer_name'];
		$partner_code 	= $product['partner_code'];
		$product_code 	= $product['product_code'];


		ss_log("attribute_id: ".$attribute_id);
		ss_log("attribute_type: ".$attribute_type);
		ss_log("insurer_code: ".$insurer_code);
		ss_log("insurer_name: ".$insurer_name);
		ss_log("partner_code: ".$partner_code);
		ss_log("product_code: ".$product_code);
		ss_log("attribute_name: ".$attribute_name);

		/////////////////////////////////////////////////////////////////////////////
		$relationshipWithInsured = 1;
			
		/////////////////////////////////////////////////////////////////////////////
		
		/////////start add by wangcya, 20140914
		$global_info = array();
		
			
		
		//////////////////主要关注到存放在全局变量中的信息////////////////////////////
		$global_info['applyNum'] = 1;//份数
		$global_info['totalModalPremium'] = $totalModalPremium;//该投保单的总保费
		
		//ss_log("totalModalPremium: ".$totalModalPremium);
		//$totalModalPremium = $apply_num*intval($product[premium]);//$POST['totalModalPremium'];//从费用
		
		$global_info['beneficiary'] = $beneficiary = 1;//受益人
		$global_info['startDate'] = $startDate;//保险开始日期
		$global_info['endDate'] = $endDate;//保险结束日期
		
		/////////start add by wangcya, 20140914
		

		$global_info['fading'] = $fading = 1;//受益人
		$global_info['apply_day'] = $apply_day = 365;//add by wangcya, 20150110，都为一年期
	
		////
		$global_info['insurer_code'] = $insurer_code;//厂商代码
		$global_info['insurer_name'] = $insurer_name;//厂商名称
		$global_info['partner_code'] = $partner_code;//合作伙伴代码
		$global_info['product_code'] = $product_code;//add by wangcya,20141102
		
		ss_log("insurer_code: ".$insurer_code);
		ss_log("insurer_name: ".$insurer_name);
		ss_log("partner_code: ".$partner_code);
		ss_log("product_code: ".$product_code);
	
		
		/////////////////////////////////////////////////////////////////////
		
		$businessType = 1;//当前只支持个人投保，
		$relationshipWithInsured = 1;//只能是本人
		
		/////////////////////////////////////////////////////////////////////////
		//////////一个subjectInfo内多个产品，多个被保险人///////////////////////////////////////////////////////////
		////////方案一是父母的////////////
		$subjectInfo1 = array();
		$subjectInfo1['list_product_id'] = $list_product_ids;//把多个商业险的子险种会统一到subject下面
		
		/*
		$list_assured1 = array();
		$list_assured1[] = $assured_info;
		$subjectInfo1['list_assured'] = $list_assured1;
		*/
		
		$list_subjectinfo = array();
		$list_subjectinfo[] = $subjectInfo1;
		
		////////////////////////////////////////////////////////////

			
		
		/////////end add by wangcya, 20140914
		//comment by zhangxi, 20150114, 原来的附加信息都是在本函数中插入，现在注释掉了
		//放外面了? 而且需要产生投保单好之后才能够进行附加信息的插入操作。
		$policy_id = $this->insurance_gen_policy_car(	$attribute_id,
														$global_info,
														$businessType,
														$relationshipWithInsured,
														$user_info_applicant,
														$list_subjectinfo,
														$cart_insure_id//add by wangcya , 20141225,批量的标志
													);//产生订单和保单
		
		ss_log("after insurance_gen_policy_car, policy_id: ".$policy_id);

		return $policy_id;
	}
	
	
	////////////////提交订单进行投保///////////////////////////////
	private function insurance_gen_policy_car( 	$attribute_id,
												$global_info,
												$businessType,
												$relationshipWithInsured,
												$user_info_applicant,
												$list_subjectinfo =array(),//层级列表
												$cart_insure_id//add by wangcya , 20141225,批量的标志
											)
	{
		global $_SGLOBAL, $_SC, $space;
		ss_log("into ".__FUNCTION__);
		////////////先要进行邮箱行检查//////////////////////////////////////////////
	
		ss_log("insurance_gen_policy_order, businessType: ".$businessType);
	
		$agent_uid = $_SGLOBAL['supe_uid'];
		//////////////////投保人是个人或者团体///////////////////////////////////////////
		if($businessType == 1)//个人
		{
			$applicant_certificates_type = $user_info_applicant['certificates_type'];
			$applicant_certificates_code = $user_info_applicant['certificates_code'];
			if(strlen($applicant_certificates_code)>30)
			{
				showmessage("证件号码太长");
			}
	
			$applicant_username = $user_info_applicant['fullname'];
			/////////////////////////////////////////////////////////////////
			//modify by wangcya , 20140912, 要根据代理人uid， 姓名，证件类型和证件号码查找，当时出现了相同的投保人姓名就是这里引起的。
			//属于某个代理人的
			$wheresql = "agent_uid='$agent_uid' AND fullname='$applicant_username' AND certificates_type='$applicant_certificates_type' AND certificates_code='$applicant_certificates_code'";
			$sql = "SELECT * FROM ".tname('user_info')." WHERE $wheresql";
	
			ss_log($sql);
	
			$query = $_SGLOBAL['db']->query($sql);
			$user_info_applicant_db = $_SGLOBAL['db']->fetch_array($query);
	
		
			/////////////////////////////////////////////////////////////////////////
			
			$user_info_applicant_attr = $user_info_applicant;//add by wangcya , 20140929,前面整理的信息应该要和数据库表中的一致，这里就直接使用即可。
	
			/////////////////先插入投保人的身份信息///////////////////////////
			if(empty($user_info_applicant_db['uid']))//new
			{
				ss_log("插入个人信息");
				/*
				 $user_info_applicant_attr['fullname'] = $applicantUsername;
				$user_info_applicant_attr['certificates_type'] = $applicantcertificates_type;
				$user_info_applicant_attr['certificates_code'] = $applicantcertificates_code;
				*/
					
				$user_info_applicant_attr = saddslashes($user_info_applicant_attr);//add by wangcya , 20141211,for sql Injection
				$applicantUid1 = inserttable("user_info", $user_info_applicant_attr,true);
				$user_info_applicant['uid'] = $applicantUid1;
					
				$applicantUsername1 = $applicant_username;
			}
			else
			{//update
				//start modify by wangcya , 20140912, 要根据姓名，证件类型和证件号码查找，当时出现了相同的投保人姓名就是这里引起的。
	
				ss_log("更新个人信息, applicant_username: ".$applicant_username);
				ss_log("更新个人信息, applicant_certificates_type: ".$applicant_certificates_type);
				ss_log("更新个人信息, applicant_certificates_code: ".$applicant_certificates_code);
					
				$wheresqlarr = array('agent_uid'=>$agent_uid,//add by wangcya , 20141219，属于哪个代理人的
						'fullname'=>$applicant_username,
						'certificates_type'=>$applicant_certificates_type,
						'certificates_code'=>$applicant_certificates_code);
					
				$user_info_applicant_attr = saddslashes($user_info_applicant_attr);//add by wangcya , 20141211,for sql Injection
				updatetable("user_info", $user_info_applicant_attr, $wheresqlarr);
				//end modify by wangcya , 20140912, 要根据姓名，证件类型和证件号码查找，当时出现了相同的投保人姓名就是这里引起的。
					
				////////////////////////////////////////////////////////////
				$applicantUid1 = $user_info_applicant_db['uid'];
				$applicantUsername1 = $applicant_username;
			}
		}
		elseif($businessType == 2)//团体
		{
			$applicant_certificates_type = $user_info_applicant['group_certificates_type'];
			$applicant_certificates_code = $user_info_applicant['group_certificates_code'];
			if(strlen($applicant_certificates_code)>30)
			{
				showmessage("证件号码太长");
			}
	
			$group_name = $user_info_applicant['group_name'];
			///////////////////////////////////////////////////////////////////////////////
			//start modify by wangcya , 20140912, 要根据姓名，证件类型和证件号码查找，当时出现了相同的投保人姓名就是这里引起的。
	
			$wheresql = "agent_uid='$agent_uid' AND group_name='$group_name' AND group_certificates_type='$applicant_certificates_type' AND group_certificates_code='$applicant_certificates_code'";
			$sql = "SELECT * FROM ".tname('group_info')." WHERE $wheresql";
			$query = $_SGLOBAL['db']->query($sql);
			$user_info_applicant_db = $_SGLOBAL['db']->fetch_array($query);//会把进入的进行了替换
	
			/////////////////////////////////////////////////////
	
		
			$user_info_applicant_attr = $user_info_applicant;
			/////////////////先插入投保人的身份信息///////////////////////////
			if(empty($user_info_applicant_db['gid']))//new
			{
				ss_log("插入团体信息, group_name: ".$group_name);
				/*
				 $user_info_applicant_attr['group_name'] = $group_name;
				$user_info_applicant_attr['group_certificates_type'] = $applicantcertificates_type;
				$user_info_applicant_attr['group_certificates_code'] = $applicantcertificates_code;
				*/
				$user_info_applicant_attr = saddslashes($user_info_applicant_attr);//add by wangcya , 20141211,for sql Injection
				$applicantUid1 = inserttable("group_info", $user_info_applicant_attr,true);
				$applicantUsername1 = $group_name;
					
				$user_info_applicant['uid'] = $applicantUid1;
					
			}
			else
			{
				//start modify by wangcya , 20140912, 要根据姓名，证件类型和证件号码查找，当时出现了相同的投保人姓名就是这里引起的。
	
				ss_log("更新团体信息, group_name: ".$group_name);
				ss_log("更新团体信息, group_certificates_type: ".$applicant_certificates_type);
				ss_log("更新团体信息, group_certificates_code: ".$applicant_certificates_code);
					
				$wheresqlarr = array('agent_uid'=>$agent_uid,//add by wangcya , 20141219，属于哪个代理人的
						'group_name'=>$group_name,
						'group_certificates_type'=>$applicant_certificates_type,
						'group_certificates_code'=>$applicant_certificates_code);
					
				$user_info_applicant_attr = saddslashes($user_info_applicant_attr);//add by wangcya , 20141211,for sql Injection
				updatetable("group_info", $user_info_applicant_attr, $wheresqlarr);
					
				//end modify by wangcya , 20140912, 要根据姓名，证件类型和证件号码查找，当时出现了相同的投保人姓名就是这里引起的。
					
					
				//不能从数据库中获取了，只能从前端提交的得到，并更新到数据库中。并且按照名字查询。
					
				$applicantUid1 = $user_info_applicant_db['gid'];
				$applicantUsername1 = $group_name;
					
					
			}
		}
	
	
		///////////////先把保单信息保存起来////////////////////////////////////////////
	
		//////////////add by wangcya , 20140807, 得到产品属性///////////////////////////////////
	
		if($attribute_id)
		{
			$wheresql = "attribute_id='$attribute_id'";
			$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE $wheresql";
			ss_log($sql);
			
			$query = $_SGLOBAL['db']->query($sql);
			$product_attribute = $_SGLOBAL['db']->fetch_array($query);
			$attribute_name = $product_attribute['attribute_name'];
			$attribute_type = $product_attribute['attribute_type'];
			$attribute_code = $product_attribute['attribute_code'];
		}
	
	
		$applicant_uid = $applicantUid1;//投保人
		$applicant_username = $applicantUsername1;
		$agent_uid = $_SGLOBAL['supe_uid'];//代理人
		$agent_username = $_SGLOBAL['supe_username'];
	
		ss_log("in gen_policy, agent_uid: ".$agent_uid." agent_username: ".$agent_username);
	
		$apply_num = $global_info['applyNum'];//份数
		$totalModalPremium = $global_info['totalModalPremium'];//该投保单的总保费
	
		//ss_log("totalModalPremium: ".$totalModalPremium);
		//$totalModalPremium = $apply_num*intval($product[premium]);//$POST['totalModalPremium'];//从费用
	
		$beneficiary= $global_info['beneficiary'];//受益人
		$startDate= $global_info['startDate'];//保险开始日期
		$endDate= $global_info['endDate'];//保险结束日期
	
		/////////start add by wangcya, 20140914
		$insurer_code = $global_info['insurer_code'];//
		$insurer_name = $global_info['insurer_name'];//
	
		$partner_code = $global_info['partner_code'];//
		$fading = $global_info['fading'];//受益人
	
		$apply_day  = $global_info['apply_day'];//add by wangcya, 20150110
		$product_code = $global_info['product_code'];//add by wangcya , 20150110
	
		ss_log("will gen policy, apply_day: ".$apply_day);
		ss_log("will gen policy, product_code: ".$product_code);
		/////////end add by wangcya, 20140914
	
		//start add by wangcya, for bug[193],能够支持多人批量投保
		if(empty($cart_insure_id))
		{//没有则生成一个，为了对付单个的情况
			$cart_insure_attr = array("user_id"=>$agent_uid,
					"product_id"=>$attribute_id,
					"product_name"=>$attribute_name,
					"total_price"=>$totalModalPremium
			);
	
			$cart_insure_id = inserttable("cart_insure", $cart_insure_attr,true);
		}
		//end add by wangcya, for bug[193],能够支持多人批量投保
		//$POST['subject'] = getstr(trim($POST['subject']), 80, 1, 1, 1);
	
		ss_log("product_code: ".$product_code);
	
		$len_guid = 0;
		if($insurer_code == "HTS")
		{//如果是华泰，则最多为20个字符串。
			ss_log("华泰要求转变order_num ".$insurer_code);
			$len_guid = 20;
		}
		else
		{
			$len_guid = 0;
		}
	
		//////////////////下面生成投保单的////////////////////////////////////////////////////////////////
		
		$order_num = getRandOnly_Id($len_guid);//1400150010655;//$POST['applicant_fullname'];//自己本地生成的唯一的号码，这个有可能重复，这回产生问题。
	
		$policy_attr = array(
				'order_num'=>$order_num,//add by wangcya , 20140831,
				'attribute_id'=>$attribute_id,
				'attribute_code'=>$attribute_code,//add by wangcya, 20150127
				'attribute_name' => $attribute_name,
				'attribute_type' => $attribute_type,
				'agent_uid'=> $agent_uid,
				'agent_username'=> $agent_username,
				'apply_num'=>$apply_num,
				'total_premium'=>$totalModalPremium,
				'applicant_uid'=>$applicant_uid,
				'applicant_username'=>$applicant_username,
				'beneficiary'=>$beneficiary,
				'relationship_with_insured'=>$relationshipWithInsured,
				'apply_day'=>$apply_day,//add by wangcya, 20150110
				'start_date'=>$startDate,
				'end_date'=>$endDate,
				'business_type'=>$businessType,
				'fading'=>$fading,
				'product_code'=>$product_code,//add by wangcya, 20150110
				'insurer_code'=>$insurer_code,
				'insurer_name'=>$insurer_name,
				'partner_code'=>$partner_code,//新增加的合作伙伴代码
				'policy_status'=>'saved',//保单状态
				'dateline'=> $_SGLOBAL['timestamp'],
				'cart_insure_id'=> $cart_insure_id //start add by wangcya, for bug[193],能够支持多人批量投保
				);
	
		//add platformid by dingchaoyang 2014-12-19

		$policy_attr['platform_id'] = "sun_web";
		
		//end by dingchaoyang 2014-12-19
		$var = print_r($policy_attr , true);
		ss_log("将要插入保单，policy_attr:--------------".$var);
		
		//ss_log("--before policy_id: ".$policy_id);
		$policy_attr = saddslashes($policy_attr);//add by wangcya , 20141211,for sql Injection
		$policy_id = inserttable('insurance_policy', $policy_attr, 1);
		//ss_log("--after policy_id: ".$policy_id);


		////////////增加多个层级////////////////////////////////////////////
		foreach($list_subjectinfo AS $key=>$value_subjectinfo)
		{
			$subjectinfo = $value_subjectinfo;

			$list_product_id = $subjectinfo['list_product_id'];//多个产品列表
			$list_assured = $subjectinfo['list_assured'];//多个被保险人

			$policy_subject_arr = array(
					'policy_id'=>$policy_id,
			);

			//ss_log("policy_id ".$policy_id." uid: ".$insurant_uid);
			$policy_subject_arr = saddslashes($policy_subject_arr);//add by wangcya , 20141211,for sql Injection
			$policy_subject_id = inserttable('insurance_policy_subject', $policy_subject_arr , true );

			//////////////////该层级下面增加多个产品/////////////////////////////////
			foreach($list_product_id AS $key1=>$value_product)
			{
				$product_id = intval($value_product);
					
				$policy_subject_product_arr = array(
						'policy_subject_id'=>$policy_subject_id,
						'product_id'=>$product_id,
				);
					
				$setarr = saddslashes($policy_subject_product_arr);//add by wangcya , 20141211,for sql Injection
				inserttable('insurance_policy_subject_products', $setarr);
					
			}
			
			/////////////////该层级下面不必出现多个投保人的处理了//////////////////////////

		}//$list_subjectinfo

		////////////////////////////////////////////////////////////////////////////////////////
	
				///////////////以上已经存放到了数据库中了，下面则是生成XML的保单进行投保//////////////////////
		ss_log("--will return policy_id: ".$policy_id);
		return $policy_id;
	}
		
	////////////////产生订单/////////////////////////////////////////////////
	public function gen_order($goods_id,$user_id,$cart_insure_id)
	{
	
		global $smarty,$_LANG,$_CFG;
		ss_log("into 生成订单函数：".__FUNCTION__);
		//////////////////////////////////////////////////////////
			
		$_REQUEST['goods_id'] = $goods_id;
		$_SESSION['user_id'] = $user_id;
		//$_REQUEST['is_weixin']
		//$_SESSION['flow_type']
		$_POST['cart_insure_id'] = $cart_insure_id;
		$_POST['payment'] = "5";
				
		/* added by zhangxi, 20150127, 活动 */
		$goods_id = intval($_REQUEST['goods_id']);//好像在post参数中
		ss_log(__FUNCTION__."zhx, goods_id=".$goods_id);
		$goods = get_goods_info($goods_id);
		
	
		/*
		 * 检查用户是否已经登录
		* 如果用户已经登录了则检查是否有默认的收货地址
		* 如果没有登录则跳转到登录和注册页面
		*/
		if (empty($_SESSION['direct_shopping']) && $_SESSION['user_id'] == 0)
		{
			ss_log("in order process,没登录，则跳转到登录页面 ");
			/* 用户没有登录且没有选定匿名购物，转向到登录页面 */
			ecs_header("Location: flow.php?step=login\n");
			exit;
		}
	
		//控制支付方式
		$is_weixin = isset ($_REQUEST['is_weixin']) ?trim($_REQUEST['is_weixin']):0;
	
		/* 取得购物类型 */
		$flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;
	
	
			
		//comment by wangcya , 20150105, 这个地方同样的$cart_insure_id可能被进入了两次
		$cart_insure_id = intval($_POST['cart_insure_id']);
		ss_log("flow done!，cart_insure_id： ".$cart_insure_id);
			
		if($cart_insure_id)
		{
			$sql="SELECT COUNT(*) FROM bx_order_info WHERE cart_insure_id='$cart_insure_id'";
			ss_log($sql);
			$order_num  = $GLOBALS['db']->getOne($sql);
			if($order_num>0)
			{
				//add by dingchaoyang 2015-1-12
				//响应json数据到客户端
				include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
				EbaAdapter::responseOrderSubmit('0');
				//end add by dingchaoyang 2015-1-12
				ss_log("该订单和批量单已经关联,cart_insure_id: ".$cart_insure_id);
				return false;
			}
		}
		else
		{
			ss_log("flow done, 批单id为空!");
			show_message("批单id不能为空!");
			return false;
			//exit(0);
		}
	
		
		$consignee = get_consignee($_SESSION['user_id']);
	
	
		$_POST['how_oos'] = isset($_POST['how_oos']) ? intval($_POST['how_oos']) : 0;
		$_POST['card_message'] = isset($_POST['card_message']) ? compile_str($_POST['card_message']) : '';
		$_POST['inv_type'] = !empty($_POST['inv_type']) ? compile_str($_POST['inv_type']) : '';
		$_POST['inv_payee'] = isset($_POST['inv_payee']) ? compile_str($_POST['inv_payee']) : '';
		$_POST['inv_content'] = isset($_POST['inv_content']) ? compile_str($_POST['inv_content']) : '';
		$_POST['postscript'] = isset($_POST['postscript']) ? compile_str($_POST['postscript']) : '';
	
		//start add by wangcya, for bug[193],能够支持多人批量投保
		$sql="SELECT COUNT(*) FROM t_insurance_policy WHERE cart_insure_id=$cart_insure_id";
		ss_log($sql);
		$policy_num  = $GLOBALS['db']->getOne($sql);
		ss_log("flow done,policy_num: ".$policy_num);
		$withdrawed_policy_num = 0;
		//end add by wangcya, for bug[193],能够支持多人批量投保
		//comment by zhangxi, 20150127, 对应bx_order_info表
		$order = array(
				'shipping_id'     => intval(isset ($_POST['shipping']) ?trim($_POST['shipping']):0),
				'pay_id'          => intval($_POST['payment']),
				'pack_id'         => isset($_POST['pack']) ? intval($_POST['pack']) : 0,
				'card_id'         => isset($_POST['card']) ? intval($_POST['card']) : 0,
				'card_message'    => trim($_POST['card_message']),
				'surplus'         => isset($_POST['surplus']) ? floatval($_POST['surplus']) : 0.00,
				'integral'        => isset($_POST['integral']) ? intval($_POST['integral']) : 0,
				'bonus_id'        => isset($_POST['bonus']) ? intval($_POST['bonus']) : 0,
				'need_inv'        => empty($_POST['need_inv']) ? 0 : 1,
				'inv_type'        => $_POST['inv_type'],
				'inv_payee'       => trim($_POST['inv_payee']),
				'inv_content'     => $_POST['inv_content'],
				'postscript'      => trim($_POST['postscript']),
				'how_oos'         => isset($_LANG['oos'][$_POST['how_oos']]) ? addslashes($_LANG['oos'][$_POST['how_oos']]) : '',
				'need_insure'     => isset($_POST['need_insure']) ? intval($_POST['need_insure']) : 0,
				'user_id'         => $_SESSION['user_id'],
				'type'         => "third",//add by wangcya,20150129
				//'add_time'        => time(),
		'add_time'        => time(),
		'order_status'    => OS_UNCONFIRMED,
		'shipping_status' => SS_UNSHIPPED,
		'pay_status'      => PS_UNPAYED,
		'cart_insure_id'  => $cart_insure_id,//add by wangcya, for bug[193],能够支持多人批量投保
		'policy_num'	  => $policy_num,////add by wangcya, for bug[193],能够支持多人批量投保,保单个数
		'withdrawed_policy_num'	  => $withdrawed_policy_num,////add by wangcya, for bug[193],能够支持多人批量投保,被注销的保单个数
		'policy_id'      => isset($_POST['policy_id']) ? intval($_POST['policy_id']) : 0,//del by wangcya, for bug[193],能够支持多人批量投保，留着。
		'agency_id'       => get_agency_by_regions(array($consignee['country'], $consignee['province'], $consignee['city'], $consignee['district'])),
		'client_id'       =>0,//added by zhangxi, 20150129,活动的c端用户id
		'activity_id'     =>0//added by zhangxi, 活动的id
	
		);
	
		/* 扩展信息 */
		if (isset($_SESSION['flow_type']) && intval($_SESSION['flow_type']) != CART_GENERAL_GOODS)
		{
			$order['extension_code'] = $_SESSION['extension_code'];
			$order['extension_id'] = $_SESSION['extension_id'];
		}
		else
		{
			$order['extension_code'] = '';
			$order['extension_id'] = 0;
		}
	
		/* 检查积分余额是否合法 */
		
		$user_id = $_SESSION['user_id'];
		if ($user_id > 0)
		{
			$user_info = user_info($user_id);
	
			$order['surplus'] = min($order['surplus'], $user_info['user_money'] + $user_info['credit_line']);
			if ($order['surplus'] < 0)
			{
				$order['surplus'] = 0;
			}
	
			/*	del by wangcya, 20150410
			// 查询用户有多少积分
			$flow_points = flow_available_points();  // 该订单允许使用的积分
			$user_points = $user_info['pay_points']; // 用户的积分总数
	
			$order['integral'] = min($order['integral'], $user_points, $flow_points);
			if ($order['integral'] < 0)
			{
				$order['integral'] = 0;
			}
			*/
		}
		else
		{
			$order['surplus']  = 0;
			$order['integral'] = 0;
		}
	
		/* 检查代金券是否存在 */
		if ($order['bonus_id'] > 0)
		{
			$bonus = bonus_info($order['bonus_id']);
	
			if (empty($bonus) || $bonus['user_id'] != $user_id || $bonus['order_id'] > 0 || $bonus['min_goods_amount'] > cart_amount(true, $flow_type))
			{
				$order['bonus_id'] = 0;
			}
		}
		elseif (isset($_POST['bonus_sn']))
		{
			$bonus_sn = trim($_POST['bonus_sn']);
			$bonus = bonus_info(0, $bonus_sn);
			//$now = time();
			$now = time();
			if (empty($bonus) || $bonus['user_id'] > 0 || $bonus['order_id'] > 0 || $bonus['min_goods_amount'] > cart_amount(true, $flow_type) || $now > $bonus['use_end_date'])
			{
			}
			else
			{
				if ($user_id > 0)
				{
					$sql = "UPDATE " . $GLOBALS['ecs']->table('user_bonus') . " SET user_id = '$user_id' WHERE bonus_id = '$bonus[bonus_id]' LIMIT 1";
					$GLOBALS['db']->query($sql);
				}
				$order['bonus_id'] = $bonus['bonus_id'];
				$order['bonus_sn'] = $bonus_sn;
			}
		}
	
		///////////////////////////////////////////////////////////////////////////////////
		$sql="SELECT * FROM t_cart_insure WHERE rec_id='$cart_insure_id'";
		ss_log($sql);
		$cart_insure_attr  = $GLOBALS['db']->getRow($sql);
		$total_premium_mutil = floatval($cart_insure_attr['total_price']);
	
		/* 计算订单的费用 */
		//$total_premium = $bx_policy['all_total_premium'];//del by wangcya, for bug[193],能够支持多人批量投保，
		$total_premium = $total_premium_mutil;//add by wangcya, for bug[193],能够支持多人批量投保，
	
		//comment by zhangxi, 20150127, 再次计算用户真正需要实际需要付的钱
		// $total = bx_order_fee($order, $total_premium, $consignee);//设置到总保费中
		$bx_policy = null;
		$total = bx_order_fee($order, $total_premium, $consignee, $bx_policy, $goods_id);//设置到总保费中
	
		ss_log("order done, goods_price".$total['goods_price']);
	
		$order['bonus']        = $total['bonus'];
		$order['goods_amount'] = $total['goods_price'];//comment by wangcya ,这个就是商品的总价。
		$order['discount']     = $total['discount'];//comment by zhangxi, 20150127, 折扣
		$order['surplus']      = $total['surplus'];
		$order['tax']          = $total['tax'];
	
		ss_log("order done, goods_price： ".$total['goods_price'].",discount=".$order['discount']);
	
		//comment by zhangxi, 20150127, 暂时没有使用
		// 购物车中的商品能享受代金券支付的总额
		$discount_amout = compute_discount_amount();
		// 代金券和积分最多能支付的金额为商品总额
		$temp_amout = $order['goods_amount'] - $discount_amout;
		if ($temp_amout <= 0)
		{
			$order['bonus_id'] = 0;
		}
	
	
		/* 支付方式 */
		if ($order['pay_id'] > 0)
		{
			$payment = payment_info($order['pay_id']);
			$order['pay_name'] = addslashes($payment['pay_name']);
		}
		$order['pay_fee'] = $total['pay_fee'];
		$order['cod_fee'] = $total['cod_fee'];
	
		/* 商品包装 */
		if ($order['pack_id'] > 0)
		{
			$pack               = pack_info($order['pack_id']);
			$order['pack_name'] = addslashes($pack['pack_name']);
		}
		$order['pack_fee'] = $total['pack_fee'];
	
		/* 祝福贺卡 */
		if ($order['card_id'] > 0)
		{
			$card               = card_info($order['card_id']);
			$order['card_name'] = addslashes($card['card_name']);
		}
		$order['card_fee']      = $total['card_fee'];
	
		//comment by zhangxi, 20150127, 用户真正要支付的钱
		$order['order_amount']  = number_format($total['amount'], 2, '.', '');
	
		/* 如果全部使用余额支付，检查余额是否足够 */
		if ($payment['pay_code'] == 'balance' && $order['order_amount'] > 0)
		{
			if($order['surplus'] >0) //余额支付里如果输入了一个金额
			{	//这里感觉有问题???
				$order['order_amount'] = $order['order_amount'] + $order['surplus'];
				$order['surplus'] = 0;
			}
			if ($order['order_amount'] > ($user_info['user_money'] + $user_info['credit_line']))
			{
				//当余额不足时，也能正常提交订单 yes123 2014-09-22
				// show_message($_LANG['balance_not_enough']);
			}
			else
			{
				$order['surplus'] = $order['order_amount'];
				$order['order_amount'] = 0;
			}
		}
	
		/* 如果订单金额为0（使用余额或积分或代金券支付），修改订单状态为已确认、已付款 */
		if ($order['order_amount'] <= 0)
		{
			$order['order_status'] = OS_CONFIRMED;
			$order['confirm_time'] = time();
			$order['pay_status']   = PS_PAYED;
			$order['pay_time']     = time();
			$order['order_amount'] = 0;
		}
		//这个什么用？？？
		$order['integral_money']   = $total['integral_money'];
		$order['integral']         = $total['integral'];
	
		if ($order['extension_code'] == 'exchange_goods')
		{
			$order['integral_money']   = 0;
			$order['integral']         = $total['exchange_integral'];
		}
	
		$order['from_ad']          = !empty($_SESSION['from_ad']) ? $_SESSION['from_ad'] : '0';
		$order['referer']          = !empty($_SESSION['referer']) ? addslashes($_SESSION['referer']) : '';
	
		/* 记录扩展信息 */
		if ($flow_type != CART_GENERAL_GOODS)
		{
			$order['extension_code'] = $_SESSION['extension_code'];
			$order['extension_id'] = $_SESSION['extension_id'];
		}
	
	
		$affiliate = unserialize($_CFG['affiliate']);
		if(isset($affiliate['on']) && $affiliate['on'] == 1 && $affiliate['config']['separate_by'] == 1)
		{
			//推荐订单分成
			$parent_id = get_affiliate();
			if($user_id == $parent_id)
			{
				$parent_id = 0;
			}
		}
		elseif(isset($affiliate['on']) && $affiliate['on'] == 1 && $affiliate['config']['separate_by'] == 0)
		{
			//推荐注册分成
			$parent_id = 0;
		}
		else
		{
			//分成功能关闭
			$parent_id = 0;
		}
		$order['parent_id'] = $parent_id;
	
		/*插入订单表*/
	
		//$sql = "SELECT count(*) FROM ".$GLOBALS['ecs']->table('order_info')." WHERE policy_id = '$policy_id'";
	
		$sql = "SELECT count(*) FROM ".$GLOBALS['ecs']->table('order_info')." WHERE cart_insure_id = '$cart_insure_id'";//add by wangcya, 20150127
		ss_log($sql);
	
		$policy_count = $GLOBALS['db']->getOne($sql);
		if($policy_count == 0)
		{//为了防止重复提交订单的情况。
			$error_no = 0;
			do
			{
				//add platformid by dingchaoyang 2014-12-19
				/*
				include_once (ROOT_PATH . 'api/EBaoApp/platformEnvironment.class.php');
				$platform_id = PlatformEnvironment::getPlatformID();
				if ($platform_id){
					$order['platform_id'] = $platform_id;
				}
				*/
				$order['platform_id'] = "sun_web";
				
				//end by dingchaoyang 2014-12-19
	
				//comment by zhangxi, 20150127, 插入订单数据到数据库中
				$order['order_sn'] = get_order_sn(); //获取新订单号
				$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'), $order, 'INSERT');
	
				$error_no = $GLOBALS['db']->errno();
	
				if ($error_no > 0 && $error_no != 1062)
				{
					die($GLOBALS['db']->errorMsg());
				}
			}
			while ($error_no == 1062); //如果是订单号重复则重新提交数据
	
			$new_order_id = $GLOBALS['db']->insert_id();
			$order['order_id'] = $new_order_id;
	
			ss_log("new_order_id: ".$new_order_id." order_sn: ".$order['order_sn']);
	
			/////////start add by wangcya , 20140830, 在这里生成了订单，所以应该也在这里把订单和保单关联起来
			$order_sn = $order['order_sn'];//add by wangcya , 20141023,把订单号增加到保单上面。
	
	
			//$sql = "UPDATE t_insurance_policy SET order_id='$new_order_id',order_sn='$order_sn' WHERE policy_id=".$order['policy_id'];
	
			//更新所有这些投保单的的order_id，order_sn,并且没有订单号的
			if($cart_insure_id)
			{
				//千万不能把那些已经存在的订单更新了
				$sql = "UPDATE t_insurance_policy SET order_id='$new_order_id',order_sn='$order_sn' WHERE cart_insure_id='$cart_insure_id' AND order_id='0'";//modify by wangcya, for bug[193],能够支持多人批量投保，
				ss_log($sql);
				$GLOBALS['db']->query($sql);
	
				$sql = "UPDATE t_cart_insure SET order_id='$new_order_id' WHERE rec_id='$cart_insure_id'";//modify by wangcya, for bug[193],能够支持多人批量投保，
				ss_log($sql);
				$GLOBALS['db']->query($sql);
			}
			/////////end add by wangcya , 20140830, 在这里生成了订单，所以应该也在这里把订单和保单关联起来
	
			//$_REQUEST['goods_id']为何为零呢？
	
			/* 插入订单商品 */
			$sql = "INSERT INTO " . $GLOBALS['ecs']->table('order_goods') . "( " .
					"order_id, goods_id, goods_name, goods_sn, goods_number, market_price, ".
					"goods_price, is_real, extension_code) ".
					" SELECT '$new_order_id', goods_id, goods_name, goods_sn, 1, market_price, ".
					"shop_price, is_real, extension_code".
					" FROM " .$GLOBALS['ecs']->table('goods') .
					" WHERE goods_id = '".$_REQUEST['goods_id']."'";
	
			ss_log($sql);
			$GLOBALS['db']->query($sql);
	
	
		}
		else
		{
			//add by dingchaoyang 2014-11-24
			//响应json数据到客户端
			include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
			EbaAdapter::responseOrderSubmit();
			//end add by dingchaoyang 2014-11-24
	
			show_message('订单已存在！', '', 'user.php?act=order_list');
			exit;
		}
	
		/* 修改拍卖活动状态 */
		if ($order['extension_code']=='auction')
		{
			$sql = "UPDATE ". $GLOBALS['ecs']->table('goods_activity') ." SET is_finished='2' WHERE act_id=".$order['extension_id'];
			ss_log($sql);
			$GLOBALS['db']->query($sql);
		}
	
		
		//require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/shopping_flow.php');
		/* 处理余额、积分、代金券 */
		
		/*del by wangcya, 20150410,这个不能更改用户账户余额
		if ($order['user_id'] > 0 && $order['surplus'] > 0)
		{
			//modify yes123 2015-01-08 订单号修正
			//log_account_change($order['user_id'], $order['surplus'] * (-1), 0, 0, 0, sprintf($_LANG['pay_order'], $order['order_sn']),$order['order_sn']);
			log_account_change($order['user_id'], $order['surplus'] * (-1), 0, 0, 0, sprintf($_LANG['pay_order'], ''),ACT_OTHER,$order['order_sn']);
		}
		if ($order['user_id'] > 0 && $order['integral'] > 0)
		{
			//modify yes123 2015-01-08 订单号修正
			//log_account_change($order['user_id'], 0, 0, 0, $order['integral'] * (-1), sprintf($_LANG['pay_order'], $order['order_sn']),$order['order_sn']);
			log_account_change($order['user_id'], 0, 0, 0, $order['integral'] * (-1), sprintf($_LANG['pay_order'], ''),ACT_OTHER,$order['order_sn']);
		}
		*/
		
		
		/*
		 //added by zhangxi, 20150127,有折扣的情况时，订单中折扣的钱得从总账户中扣除
		if ($order['user_id'] > 0 && $order['discount'] >0)
		{
		//不是扣操作当前订单的账户，而是扣公司的账户
		//ebaoins_user=user_name
		//根据用户名查询用户id，
		ss_log(__FUNCTION__."user_id=".$order['user_id'].",discount=".$order['discount']);
		$sql = "SELECT user_id " .
		"FROM " . $GLOBALS['ecs']->table('users') .
		" WHERE user_name = 'ebaoins_user'";
		$ebaoins_user_id = $GLOBALS['db']->getOne($sql);
		log_account_change($ebaoins_user_id, $order['discount'] * (-1), 0, 0, 0, sprintf($_LANG['pay_order'], "活动,sn:".$order['order_sn']),ACT_OTHER,$order['order_sn']);
		}
		*/
			
		if ($order['bonus_id'] > 0 && $temp_amout > 0)
		{
			use_bonus($order['bonus_id'], $new_order_id);
		}
	
		/* 如果使用库存，且下订单时减库存，则减少库存 */
		if ($_CFG['use_storage'] == '1' && $_CFG['stock_dec_time'] == SDT_PLACE)
		{
			change_order_goods_storage($order['order_id'], true, SDT_PLACE);
		}
	
	
		/* 如果需要，发短信 , ask by wangcya, 20141011, 这里和支付宝的地方的sms_order_payed不一样*/
		/*	del yes123 2015-01-19  echop 自带的短信功能，取消    if ($_CFG['sms_order_placed'] == '1' && $_CFG['sms_shop_mobile'] != '')
		 {
		include_once('includes/cls_sms.php');
		$sms = new sms();
		$msg = $order['pay_status'] == PS_UNPAYED ?
		$_LANG['order_placed_sms'] : $_LANG['order_placed_sms'] . '[' . $_LANG['sms_paid'] . ']';
		$flow_type = isset($order['tel']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;
		$sms->send($_CFG['sms_shop_mobile'], sprintf($msg, $order['consignee'], $order['tel']),'', 13,1);
		}*/
	
		/* 如果订单金额为0 处理虚拟卡 */
		if ($order['order_amount'] <= 0)
		{
			$sql = "SELECT goods_id, goods_name, goods_number AS num FROM ".
					$GLOBALS['ecs']->table('cart') .
					" WHERE is_real = 0 AND extension_code = 'virtual_card'".
					" AND session_id = '".SESS_ID."' AND rec_type = '$flow_type'";
	
			$res = $GLOBALS['db']->getAll($sql);
	
			$virtual_goods = array();
			foreach ($res AS $row)
			{
				$virtual_goods['virtual_card'][] = array('goods_id' => $row['goods_id'], 'goods_name' => $row['goods_name'], 'num' => $row['num']);
			}
	
			if ($virtual_goods AND $flow_type != CART_GROUP_BUY_GOODS)
			{
				/* 虚拟卡发货 */
				if (virtual_goods_ship($virtual_goods,$msg, $order['order_sn'], true))
				{
					/* 如果没有实体商品，修改发货状态，送积分和代金券 */
					$sql = "SELECT COUNT(*)" .
							" FROM " . $GLOBALS['ecs']->table('order_goods') .
							" WHERE order_id = '$order[order_id]' " .
							" AND is_real = 1";
					if ($GLOBALS['db']->getOne($sql) <= 0)
					{
						/* 修改订单状态 */
						update_order($order['order_id'], array('shipping_status' => SS_SHIPPED, 'shipping_time' => time()));
	
						/* 如果订单用户不为空，计算积分，并发给用户；发代金券 */
						if ($order['user_id'] > 0)
						{
							/* 取得用户信息 */
							$user = user_info($order['user_id']);
	
							/* 计算并发放积分 */
							$integral = integral_to_give($order);
							log_account_change($order['user_id'], 0, 0, intval($integral['rank_points']), intval($integral['custom_points']), sprintf($_LANG['order_gift_integral'], $order['order_sn'],$order['order_sn']));
	
							/* 发放代金券 */
							send_order_bonus($order['order_id']);
						}
					}
				}
			}
	
		}
	
		/* 清空购物车 */
		clear_cart($flow_type);
		/* 清除缓存，否则买了商品，但是前台页面读取缓存，商品数量不减少 */
		clear_all_files();
	
		/* 插入支付日志，这个地方很重要 */
		
	
		/* 取得支付信息，生成支付代码 */
		/*del by wangcya, 20150410
		 * 
		ss_log("将要进行函数 insert_pay_log ");
		//add by wangcya, 20150127,下面这个函数非常重要，要前提
		$order['log_id'] = insert_pay_log($new_order_id, $order['order_amount'], PAY_ORDER);
	
		$pay_online='';
		
		//comment by zhangxi ， 20150127， 进行支付的处理流程
		
		
		if ($order['order_amount'] > 0)
		{
			$payment = payment_info($order['pay_id']);
	
			include_once('includes/modules/payment/' . $payment['pay_code'] . '.php');
	
			$pay_obj    = new $payment['pay_code'];
	
			ss_log("will into pay get_code, pay_code: ".$payment['pay_code']);
			//comment by wangcya, 20150127,这个函数很重要，生成例如支付宝支付的按钮和表单。
			$pay_online = $pay_obj->get_code($order, unserialize_config($payment['pay_config']));
			ss_log($pay_online);
			$order['pay_desc'] = $payment['pay_desc'];
	
			$smarty->assign('pay_online', $pay_online);
	
		}
		if(!empty($order['shipping_name']))
		{
			$order['shipping_name']=trim(stripcslashes($order['shipping_name']));
		}
	
		*/
		
		/* 订单信息 */
		/*
		$smarty->assign('order',      $order);
		$smarty->assign('total',      $total);
		$smarty->assign('order_submit_back', sprintf($_LANG['order_submit_back'], $_LANG['back_home'], $_LANG['goto_user_center'])); // 返回提示
		*/
	
	
		
		//处理佣金,同时进行投保操作
	
		/*
		ss_log("from follow done, into function assign_commision_and_post_policy!");
		//comment by wangcya, 20150127,其实这个函数内部判断是否已经支付，所以根据支付状态来结算佣金
		ss_log("将要进行佣金的处理");
		//mod by zhangxi, 20150305, 修改函数名,分配佣金和投保
		//$result_attr = yongJin2($order['order_sn']);
		
		$var = print_r($order , true);
		ss_log("order:--------------".$var);
		
		$result_attr = assign_commision_and_post_policy($order['order_sn']);
		//////////////////////////////////////////////////////////////////////
		*/	
		ss_log("gen_order, order_id: ". $order['order_id']);
		
		return $order['order_id'];
	
	}//done_meeting
	
	
	
}//end class