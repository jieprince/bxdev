<?php
include_once(S_ROOT.'./source/function_baoxian.php');
include_once(S_ROOT.'./source/cp_product_buy_process.php');
global $INSURANCE_PAC;


$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_. 'oop/classes/PACGroupInsurance.php');


if (defined('IN_ECS') == false)
{
    define('IN_ECS', true);
}

if($_SGLOBAL['mobile_type']!='pcweb') 
{
	include_once($ROOT_PATH_. 'mobile/includes/init.php');
	ss_log("init mobile/includes/init.php");
}
else
{
	include_once($ROOT_PATH_. 'includes/init.php');
	ss_log("init includes/init.php");
	
}

if(isset($_GET['uid']))
{
	ss_log(__FILE__."ZHX1,uid: ".$_GET['uid']);
}
////////////////////////////////////////////////////////////////////
$user_id = $_SGLOBAL['supe_uid'];
if(!$user_id)
{
	showmessage("请登录后进行操作！");
	return false;
}


//////////////////////用户信息//////////////////////////////////////////

//$wheresql = "u.user_id='$user_id' AND u.is_cheack='1'";
$wheresql = "u.user_id='$user_id'"; //add yes123 2015-04-26 吉林用户处理
$sql = "SELECT u.*,ui.Province FROM bx_users u LEFT JOIN bx_user_info ui ON u.user_id=ui.uid WHERE $wheresql";
ss_log('购买产品，获取当前用户信息：'.$sql);
$query = $_SGLOBAL['db']->query($sql);
$user_info = $_SGLOBAL['db']->fetch_array($query);
///////////////////////////////////////////////////////////////////////
//mod by zhangxi, 20150203, 活动的情况下，非正式会员也可以进行分享
$product_id = intval($_REQUEST['product_id']);//每个产品，也就是款式的id。
if($product_id)//产品的id
{
	//echo "file:".__FILE__;
	ss_log(__FILE__."product_id: ".$product_id);
	$wheresql = "p.product_id='$product_id'";
	$sql = "SELECT p.*,padd.*,patt.* FROM ".tname('insurance_product_base')." p
	inner JOIN ".tname('insurance_product_additional')." padd ON padd.product_id=p.product_id
	inner JOIN ".tname('insurance_product_attribute')."  patt ON patt.attribute_id=p.attribute_id
	WHERE $wheresql";
	$query = $_SGLOBAL['db']->query($sql);
	$product_info = $_SGLOBAL['db']->fetch_array($query);
	//后续也可以加上保险公司分支处理
	ss_log(__FILE__.":product id=".$product_id.",attribute_type:".$product_info['attribute_type']);
	if(!strstr($product_info['attribute_type'],$G_WORDS_HUODONG))//险种属性中只要含有huodong字符，则进入，保证代码通用性
	{
		//del yes123 2015-07-09 任何类型的用户都能下单
		if($user_info['check_status']!='checked')
		{
			ss_log("购买产品 institution_id:".$user_info['institution_id']."user_id:".$user_info['user_id']);
			ss_log("JILIN_INSTITUTION_ID:".JILIN_INSTITUTION_ID);
			//add yes123 2015-04-26 吉林用户处理，判断如果是吉林用户，非正式会员也能购买
			if($user_info['institution_id']!=JILIN_INSTITUTION_ID) 
			{
/*				showmessage("您还不是正式会员，不能购买产品！请提交完备的个人信息后通知管理员进行审批！");
				return false;*/
			}

		}
		
	}
}
else
{
/*	//del yes123 2015-07-09 任何类型的用户都能下单
	if($user_info['check_status']!='checked')
	{
		ss_log("institution_id:".$user_info['institution_id']."user_id:".$user_info['user_id']);
		ss_log("INSTITUTION_ID:".JILIN_INSTITUTION_ID);
		//add yes123 2015-04-26 吉林用户处理，判断如果是吉林用户，非正式会员也能购买
		if($user_info['institution_id']!=JILIN_INSTITUTION_ID) 
		{
			showmessage("您还不是正式会员，不能购买产品！请提交完备的个人信息后通知管理员进行审批！");
			return false;
		}
	}*/
	
}

/////////////////////////////////////////////////////////
$allow_direct_visit = 0;
$order_device_id = 0 ;
if(isset($_GET['allow_direct_visit']))
{
	$allow_direct_visit = intval($_GET['allow_direct_visit']);
	
}
if(isset($_GET['order_device_id']))
{
	$order_device_id = intval($_GET['order_device_id']);
}

ss_log("cp_buy_product, allow_direct_visit: ".$allow_direct_visit);
ss_log("cp_buy_product, order_device_id: ".$order_device_id);
/////////////////////////////////////////////
//mod by zhangxi, 20141224
//$gid=intval($_GET['gid']);

$gid = isset($_REQUEST['gid'])?$_REQUEST['gid']:0;

if(!empty($gid))
{
	$_SESSION['gid'] = $gid;
}

if(!empty($_SESSION['gid']))
{
	$gid = $_SESSION['gid'];
}


///////////////////根据订单号得到订单////////////////////////////////////
$policy_id = empty($_GET['policy_id'])?0:intval($_GET['policy_id']);

//////////////////////////////////////////////////////////////////////
$op = empty($_GET['op'])?'':$_GET['op'];
//$type = empty($_GET['type'])?'pingan_yiwai_ertongA':intval($_GET['type']);

$step = empty($_GET['step'])?1:intval($_GET['step']);

$_TPL['css'] = 'client_product';

ss_log("will process cp_product_buy, step: ".$step." op： ".$op);

////////////////////////////////////////////////////////////////////////////////
$product_id = intval($_GET['product_id']);//每个产品，也就是款式的id。
if(!$product_id)
{
	$product_id = intval($_POST['product_id']);//每个产品，也就是款式的id。
}

$theurl = "cp.php?ac=product_buy";
//首先得到保险公司的产品列表

////////////////////////////////////////////////////////////////////////////
if(isset($_GET['uid']))
{
	ss_log(__FILE__."ZHX2,uid: ".$_GET['uid']);
}
if($product_id)//产品的id
{
	ss_log("product_id: ".$product_id);
	/////////////////得到产品的xinxi///////////////////////////////////////////
	$wheresql = "p.product_id='$product_id'";
	
	$sql = "SELECT p.*,padd.*,patt.* FROM ".tname('insurance_product_base')." p
	inner JOIN ".tname('insurance_product_additional')." padd ON padd.product_id=p.product_id
	inner JOIN ".tname('insurance_product_attribute')."  patt ON patt.attribute_id=p.attribute_id
	WHERE $wheresql";
	
	$query = $_SGLOBAL['db']->query($sql);

	$product = $product_arr = $_SGLOBAL['db']->fetch_array($query);
	$insurer_code 	= $product['insurer_code'];
	
	ss_log("------insurer_code: ".$insurer_code);
	ss_log("------product_code: ".$product['product_code']);
	/////////////////////////////////////////////////////////////////////
	if(empty($product['product_id']))
	{
		//$blog = array();
		//订单不能修改，所以这里要必须输入；
		showmessage("没有对应的产品信息");
		return false;
	}
	
	///////////////////////////////////////////////////////////////////
	$allow_sell = $product['allow_sell'];
	//检查审核装填，如果没审核通过，则不允许购买产品。
	if($allow_sell)
	{
		showmessage("该产品处于测试中，暂时不允许售卖！");
		return false;
	}
	

	/////////////////访问检查//////////////////////////////////////////
	$ret_access = false;
	
	///////////////////////////////////////////////////////////////////////
	if(in_array($insurer_code, $INSURANCE_PAC))
	{
		ss_log("cp_buy_access_check_pingan");
		include_once(S_ROOT.'./source/cp_product_buy_pingan.php');
		$ret_access = cp_buy_access_check_pingan($product,$user_info);

	}
	elseif($insurer_code == "TBC01")//太平洋
	{
		ss_log("cp_buy_access_check_taipingyang");
		include_once(S_ROOT.'./source/cp_product_buy_taipingyang.php');
		$ret_access = cp_buy_access_check_taipingyang($product,$user_info);
	}
	elseif( $insurer_code =="HTS")//华泰
	{
		ss_log("cp_buy_access_check_huatai");
		include_once(S_ROOT.'./source/cp_product_buy_huatai.php');
		$ret_access = cp_buy_access_check_huatai($product,$user_info);
	}
	//added by zhangxi, 20141222, for 华安产品
	elseif($insurer_code =="SINO")
	{
		ss_log("cp_buy_access_check_huatai");
		include_once(S_ROOT.'./source/cp_product_buy_huaan.php');
		$ret_access = cp_buy_access_check_huaan($product,$user_info);
	}
	elseif($insurer_code =="NCI")
	{
		ss_log("cp_buy_access_check_nci");
		include_once(S_ROOT.'./source/cp_product_buy_xinhua.php');
		$ret_access = cp_buy_access_check_xinhua($product,$user_info);
	}
	elseif($insurer_code =="CHINALIFE")
	{
		ss_log("cp_buy_access_check_chinalife");
		include_once(S_ROOT.'./source/cp_product_buy_chinalife.php');
		$ret_access = cp_buy_access_check_chinalife($product,$user_info);
	}
	elseif($insurer_code =="picclife")
	{
		ss_log("cp_buy_access_check_picclife");
		include_once(S_ROOT.'./source/cp_product_buy_picclife.php');
		$ret_access = cp_buy_access_check_picclife($product,$user_info);
	}
	elseif($insurer_code =="EPICC")
	{
		ss_log("cp_buy_access_check_epicc");
		include_once(S_ROOT.'./source/cp_product_buy_epicc.php');
		$ret_access = cp_buy_access_check_epicc($product,$user_info);
	}
	elseif($insurer_code =="CPIC_CARGO")
	{
		ss_log("cp_buy_access_check_cpic_cargo");
		include_once(S_ROOT.'./source/cp_product_buy_cpic_cargo.php');
		$ret_access = cp_buy_access_check_cpic_cargo($product,$user_info);
	}
	elseif($insurer_code ==$ARR_INS_COMPANY_NAME['str_cpic_tj_property'])
	{
		ss_log("cp_buy_access_check_epicc");
		include_once(S_ROOT.'./source/cp_product_buy_cpic_cargo.php');
		include_once(S_ROOT.'./source/cp_product_buy_taipingyang.php');
		$ret_access = cp_buy_access_check_cpic_tj_property($product,$user_info);
	}
	elseif($insurer_code =='sinosig')
	{
		//ss_log("cp_buy_access_check_epicc");
		include_once(S_ROOT.'./source/cp_product_buy_sinosig.php');
		$ret_access=true;
	}
	elseif($insurer_code =='SSBQ')
	{
		//ss_log("cp_buy_access_check_epicc");
		include_once(S_ROOT.'./source/cp_product_buy_ssbq.php');
		$ret_access=true;
	}
	//add by dingchaoyang 2014-12-18
	//响应json数据到客户端
	// 	print_r($result_attr);
	$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
	include_once ($ROOT_PATH_ . 'api/EBaoApp/eba_adapter.php');
	EbaAdapter::responseLimitPurchase($ret_access);
	//end add by dingchaoyang 2014-12-18
	
	if(!$ret_access)
	{
		ss_log("access deny!");
		exit(0);
	}
	
	/////////////////////////////////////////////////////////////////////
	$attribute_type = $product['attribute_type'];

	$start_day = $product['start_day'];
	
	$attribute_id = $product['attribute_id'];
	
	ss_log("in cp_product_buy, attribute_id: ".$attribute_id);
	
	ss_log("in cp_product_buy, start_day: ".$start_day);
	ss_log("in cp_product_buy, insurer_code: ".$insurer_code);

	ss_log("in cp_product_buy, product_name: ".$product['product_name']);
	/*
	if(!$product['start_day'])
	{
		$product['start_day'] = 6;
	}
	*/
	$price_total = $product['premium'];
	
	$product['premium'] = sprintf("%01.2f", $product['premium']);
	$product['insurance_clauses'] = stripslashes($product['insurance_clauses']);
	$product['cover_note'] = stripslashes($product['cover_note']);
	
}


///////////////下面的时间是要返回给客户端的，用来保证客户端的时间选择是从服务器端拿到的时间//////////////////////////////////////////////////
$server_time = time();//second, $_SGLOBAL['timestamp'];
ss_log("server_time: ".$server_time);

$servertime_str = date("Y-m-d H:i:s",$server_time);
ss_log("servertime_str: ".$servertime_str);

$product['server_time'] = $server_time;//把服务器的时间存放到这里，是来控制客户端时间的
///////////////////////////////////////////////////////////////////
if(empty($op))//用户直接提交的方式。
{
	if($step==1)//第一步显示提交投保的页面
	{
		///////////////////////////////////////////////////////////////////////
		//获取投保确认页面
		if(submitcheck('buysubmit')||$allow_direct_visit)
		{

			//start add by wangcya , 20141121,防止客户端时间设置有问题
			// add yes123 2015-12-04 诉讼保全 没有保险起期和止期，不用校验
			if($insurer_code !="SSBQ")
			{
				$ret_access = check_client_time($server_time,$_POST);
			}
			
			
			if(!$ret_access)
			{
				ss_log("check time err ,so exit!");
				exit(0);
			}
			//end add by wangcya , 20141121,防止客户端时间设置有问题
			
			ss_log("in buysubmit, insurer_code: ".$insurer_code);
			
			$bat_post_policy = trim($_POST['bat_post_policy']);//add by wangcya, for bug[193],能够支持多人批量投保
			ss_log("bat_post_policy: ".$bat_post_policy);
			
			if(in_array($insurer_code, $INSURANCE_PAC))
			{		
				//start add by wangcya, for bug[193],能够支持多人批量投保
				if($bat_post_policy == 1)//批量投保的方式
				{
					$ret_policy_output_list = process_product_simple_loop($insurer_code,$product,$_POST);
				}
				//end add by wangcya, for bug[193],能够支持多人批量投保，
				else
				{
					$ret_policy_output = process_product_pingan($product,$_POST);
				}
				//////////////////////////////////////////////////////////////////////////////
				if($insurer_code == 'PAC04')
				{
					$_TPL['css'] = 'cp_product_buy_pingan_property';
				}
				else
				{
					$_TPL['css'] = 'cp_product_buy_pingan';
				}
				
					
			}//PAC
			elseif($insurer_code == "TBC01")//太平洋
			{
				//start add by wangcya, for bug[193],能够支持多人批量投保
				if($bat_post_policy == 1)//批量投保的方式
				{
					$ret_policy_output_list = process_product_simple_loop($insurer_code,$product,$_POST);
				}
				//end add by wangcya, for bug[193],能够支持多人批量投保，
				else
				{
					$ret_policy_output = process_product_taipingyang($product,$_POST);
				}
					
				$_TPL['css'] = 'cp_product_buy_taipingyang';
			}//TBC01
			elseif($insurer_code == $ARR_INS_COMPANY_NAME['str_cpic_tj_property'])//太平洋天津财险
			{
				//start add by wangcya, for bug[193],能够支持多人批量投保
				if($bat_post_policy == 1)//批量投保的方式
				{
					$ret_policy_output_list = process_product_simple_loop($insurer_code,$product,$_POST);
				}
				//end add by wangcya, for bug[193],能够支持多人批量投保，
				else
				{
					$ret_policy_output = process_product_taipingyang($product,$_POST);
				}
					
				$_TPL['css'] = 'cp_product_buy_taipingyang';
			}//TBC01
			elseif( $insurer_code =="HTS")//华泰
			{
			
				/////////////////////////////////////////////////////////////////////////////////
				//$totalModalPremium = $_POST['totalModalPremium'];
				//ss_log("post huatai: ".$totalModalPremium);
				//////////////////////////////////////////////////////////////////
				if($bat_post_policy == 1)//批量投保的方式
				{
					$ret_policy_output_list = process_product_simple_loop($insurer_code,$product,$_POST);
				}
				else
				{
					$ret_policy_output = process_product_huatai($product,$_POST);	
				}
				
				///////////////////////////////////////////////////////////////////////
				$_TPL['css'] = 'cp_product_buy_huatai';
					
				
			}//HTS
			//added by zhangxi, 20141222, for 华安产品接入
			elseif($insurer_code =="SINO")
			{
				//mod by zhangxi,20150415, 增加批量投保支持处理
				if($bat_post_policy == 1)//批量投保的方式
				{
					$ret_policy_output_list = process_product_simple_loop($insurer_code,$product,$_POST);	
				}
				else
				{
					$ret_policy_output = process_product_huaan($product,$_POST);
				}
				
				///////////////////////////////////////////////////////////////////////
				if($product['attribute_type'] == 'project')
				{
					$_TPL['css'] = 'cp_product_buy_huaan';
				}
				elseif($product['attribute_type'] == 'xuepingxian')
				{
					$_TPL['css'] = 'cp_product_buy_huaan_xueping';
				}
				
			}
			//added by zhangxi, 20150323, 新华人寿产品接入
			elseif($insurer_code =="NCI")//
			{
				$ret_policy_output = process_product_xinhua($product,$_POST);
				$_TPL['css'] = 'cp_product_buy_xinhua';
			}
			//added by zhangxi, 20150331, 增加中国人寿产品
			elseif($insurer_code =="CHINALIFE")
			{
				$ret_policy_output = process_product_chinalife($product,$_POST);
				$_TPL['css'] = 'cp_product_buy_chinalife';
			}
			elseif($insurer_code =="picclife")//增加人保寿险
			{
				if($bat_post_policy == 1)//批量投保的方式
				{
					$ret_policy_output_list = process_product_simple_loop($insurer_code,$product,$_POST);
				}
				else
				{
					$ret_policy_output = process_product_picclife($product,$_POST);
					
				}
				
				if($product['attribute_type'] == 'ijiankang')
				{
					$_TPL['css'] = 'cp_product_buy_picclife_ijiankang';
				}
				elseif($product['attribute_type'] == 'BWSJ')
				{
					$_TPL['css'] = 'cp_product_buy_picclife_bwsj';
				}
				elseif($product['attribute_type'] == 'TTL')
				{
					$_TPL['css'] = 'cp_product_buy_picclife_ttl';
				}
				elseif($product['attribute_type'] == 'KLWYLQX')
				{
					$_TPL['css'] = 'cp_product_buy_picclife_klwylqx';
				}
				else
				{
					$_TPL['css'] = 'cp_product_buy_picclife';	
				}
				
			}
			elseif($insurer_code =="EPICC")//增加人保财险
			{
				//mod by zhangxi,20150415, 增加批量投保支持处理
				if($bat_post_policy == 1)//批量投保的方式
				{
					$ret_policy_output_list = process_product_simple_loop($insurer_code,$product,$_POST);
				}
				else
				{
					$ret_policy_output = process_product_epicc($product,$_POST);
				}
				
				$_TPL['css'] = 'cp_product_buy_epicc';
			}
			//added by zhangxi, 20150601, 增加太平洋货运险
			elseif($insurer_code =="CPIC_CARGO")//
			{

				$ret_policy_output = process_product_cpic_cargo($product,$_POST);
				$_TPL['css'] = 'cp_product_buy_cpic_cargo';
			}
			elseif($insurer_code =="sinosig")//阳光保险
			{
				$ret_policy_output = process_product_sinosig($product,$_POST);
			}
			elseif($insurer_code =="SSBQ")//诉讼保全
			{
				$ret_policy_output = process_product_ssbq($product,$_POST);
				process_upload_file($ret_policy_output,'insurance_policy');
			
			}
			
			//add by dingchaoyang 2015-1-9
			//响应json数据到客户端
			//$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
			include_once (S_ROOT . '../api/EBaoApp/eba_adapter.php');
			EbaAdapter::responseTempPolicy($ret_policy_output['policy_id']);//comment by wangcya, 20141226, 这个地方要传入$cart_insure_id
			//end add by dingchaoyang 2015-1-9
			/////////////////////////////////////////////////////////
			ss_log("----allow_direct_visit:------".$allow_direct_visit);
						
			if(!$allow_direct_visit)
			{
				if($bat_post_policy == 1)//批量投保的方式
				{
					$cart_insure_id = $ret_policy_output_list[0]['policy_arr']['cart_insure_id'];
				}
				else
				{
					$cart_insure_id = $ret_policy_output['policy_arr']['cart_insure_id'];
				}
				//mod by zhangxi,20150107, product_id, duty_price_ids需要带上
				$duty_price_ids=$_REQUEST['duty_price_ids'];
				//mod by zhangxi, 20150123, 为了营销活动，增加uid的传递
				$uid = $_GET['uid'];
				if(empty($uid))
				{
					$uid = $_POST['uid'];			
				}
				if(isset($_GET['uid']))
				{
					ss_log(__FILE__."ZHX3,uid: ".$_GET['uid']."uid: ".$uid);
				}
				$platformId = isset ( $_REQUEST ['platformId'])?$_REQUEST['platformId']:'';
				$url = "cp.php?ac=product_buy&cart_insure_id=$cart_insure_id&bat_post_policy=$bat_post_policy&step=2&product_id=$product_id&duty_price_ids=$duty_price_ids&uid=$uid&platformId=$platformId";
				ss_log("will goto step 2, url: ".$url);
			  	showmessage('do_success', $url, 0);
				
			}
			else//直接进入处理的，不需要确认了。
			{//comment by zhangxi, 20150331, 这里的代码还有用么????
				/*
				 $_GET['step'] = 'add_bx';
				$_GET['policy_id'] = $policy_id;
				$_GET['gid'] = $gid;
				*/
			
				$policy_id = $ret_policy_output['policy_arr']['policy_id'];
				$_REQUEST['step'] = 'add_bx';
				$_REQUEST['allow_direct_visit'] = $allow_direct_visit;
			
				$_GET['policy_id'] = $policy_id;
				$_GET['gid'] = $gid;
				$_GET['price_total'] = $price_total;
				$_GET['order_device_id'] = $order_device_id;
				$_POST['insurer_code'] = $insurer_code;
			
				ss_log("will redirect to folw.php, policy_id: ".$policy_id." gid: ".$gid);
			
				//echo S_ROOT."../flow.php";
				include_once(S_ROOT."../flow.php");
			}

		}//submitcheck
		else//保单信息填写页面展现，走此流程，获取投保填写页面流程
		{
			ss_log("in not buysubmit, insurer_code: ".$insurer_code);
			ss_log("in not buysubmit, insurer_code: ".$ARR_INS_COMPANY_NAME['str_cpic_tj_property']);
			if(in_array($insurer_code, $INSURANCE_PAC))
			{
				gen_cp_buy_view_info_pingan($product,$_POST);
			}////PAC
			elseif($insurer_code == "TBC01")//太平洋
			{
				gen_cp_buy_view_info_taipingyang($product,$_POST);
			}//TBC01
			elseif($insurer_code == $ARR_INS_COMPANY_NAME['str_cpic_tj_property'])
			{
				ss_log("before execute fun gen_cp_buy_view_info_taipingyang");
				gen_cp_buy_view_info_taipingyang($product, $_POST);
				ss_log("AFTER execute fun gen_cp_buy_view_info_taipingyang");
			}
			elseif( $insurer_code =="HTS")//华泰
			{
				gen_cp_buy_view_info_huatai($product,$_POST);
			}//HTS
			//added by zhangxi, 20141222, for 华安产品接入
			elseif ($insurer_code =="SINO")
			{
				gen_cp_buy_view_info_huaan($product,$_POST);
			}
			//added by zhangxi, 20150327, 获取新华投保信息填写页面
			elseif($insurer_code =="NCI")
			{
				gen_cp_buy_view_info_xinhua($product,$_POST);
			}
			//added by zhangxi, 20150327, 获取中国人寿产品投保填写页面
			elseif($insurer_code == "CHINALIFE")
			{
				ss_log("before call function gen_cp_buy_view_info_chinalife");
				gen_cp_buy_view_info_chinalife($product,$_POST);
			}
			//added by zhangxi, 20150415, 获取人保寿险的投保填写页面
			elseif($insurer_code == "picclife")
			{
				ss_log("before call function gen_cp_buy_view_info_picclife");
				gen_cp_buy_view_info_picclife($product,$_POST);
			}
			//added by yes123, 20150420, 获取人保财险的投保填写页面
			elseif($insurer_code == "EPICC")
			{
				ss_log("before call function gen_cp_buy_view_info_epicc");
				gen_cp_buy_view_info_epicc($product,$_POST);
			}
			//added by zhangxi, 20150601, 太平洋货运险
			elseif($insurer_code == "CPIC_CARGO")
			{
				ss_log("before call function gen_cp_buy_view_info_epicc");
				gen_cp_buy_view_info_cpic_cargo($product,$_POST);
			}
			elseif($insurer_code == "sinosig")
			{
				gen_cp_buy_view_info_sinosig($product,$_POST);
			}
			elseif($insurer_code == "SSBQ")
			{
				gen_cp_buy_view_info_ssbq($product,$_POST);
			}
			else
			{
				ss_log("error happened, unkown insurer_code=".$insurer_code);
			}
		}
	}//step 1
	//start add by wangcya, 20150106,为了防止多次提交
	elseif($step == 2)
	{//进入投保单展示页,或者是订单提交页面
		
		ss_log("into cp_buy_product , step 2");
		
		if(submitcheck('orderbuysubmit'))//投保确认后，对订单进行购买的操作,进入支付方式页面了
		{//跳转到订单生成页
			if(!$allow_direct_visit)
			{
				//start add by wangcya, for bug[193],能够支持多人批量投保
				$cart_insure_id = $_POST['cart_insure_id'];//add by wangcya, for bug[193],批量投保的id,一束
			
				//$policy_ids = $_POST['policy_ids'];//从投保确认页面传递过来的。
				//$_POST['policy_ids'] = $_SESSION['policy_ids'] = implode(",",$policy_ids);
				//ss_log("orderbuysubmit，session policy ids: ".$policy_ids);
				
				//mod by zhangxi, 20150123, 跳入订单支付页面了,也得带uid
				//$uid=$_REQUEST['uid'];
				$uid = $_GET['uid'];
				if(empty($uid))
				{
					$uid = $_POST['uid'];			
				}
				$platformId = isset ( $_REQUEST ['platformId'])?$_REQUEST['platformId']:'';
				
				//mod by zhangxi,20150422, 应该在这里插入核保处理流程
				$flag_check = isset($_GET['flag_check_policy']) ? $_GET['flag_check_policy'] : 0;
				if($flag_check)//需要插入核保流程得情况
				{
					ss_log(__FILE__.", check policy first, policy_id=".$policy_id);
					$ret = get_policy_info($policy_id);//得到保单信息
					
					$policy_attr = $ret['policy_arr'];
					$user_info_applicant = $ret['user_info_applicant'];
					$list_subject = $ret['list_subject'];//
					
					
					//comment by zhangxi, 20150428, 这里应该区分具体的保险公司，甚至具体的产品
					//通过投保单获取相关传入参数

					//处理核保接口
					$result_attr = process_underWriting($insurer_code,
														$attribute_type,//
														$product_arr['attribute_code'],//
														$policy_attr,
														$user_info_applicant,
														$list_subject,
														1);
					
					//$result_attr['retcode']=0;
					if($result_attr['retcode'] != 0)//核保不通过的情况
					{
						//说明核保失败了。重定向到投保填写页面,看来得重新做模板
						ss_log(__FILE__.", 投保过程中，初步核保失败, policy_id=".$policy_id);
						$url = "../goods.php?id=$gid";
						ss_log("will goto step 2, url: ".$url);
			  			showmessage('核保失败,请重新填写!错误信息:'.$result_attr['retmsg'], $url, 5);//重定向到投保确认页面去
					}
					else
					{
						//说明核保成功，记录日志，允许继续往下进行，进入订单支付页面
						ss_log("投保过程中，初步核保成功, policy_id=".$policy_id);
					}
				}
				
				
				ss_log("订单完成_SGLOBAL['mobile_type']===".$_SGLOBAL['mobile_type']);
				if($_SGLOBAL['mobile_type'] != 'pcweb')
				{
					//$url = "../mobile/order.php?act=order_lise&policy_id=$policy_id&gid=$gid&cart_insure_id=$cart_insure_id&is_weixin=1";//支付方式页
					
					$url = "../mobile/order.php?act=order_lise&policy_id=$policy_id&gid=$gid&cart_insure_id=$cart_insure_id&is_weixin=1&uid=$uid&platformId=$platformId&insurer_code=$insurer_code";//支付方式页
				}
				else
				{
					//$url = "../flow.php?step=add_bx&policy_id=$policy_id&gid=$gid&cart_insure_id=$cart_insure_id";
					$url = "../flow.php?step=add_bx&policy_id=$policy_id&gid=$gid&cart_insure_id=$cart_insure_id&uid=$uid&insurer_code=$insurer_code";
				}
					
				ss_log("flow url: ".$url);
				showmessage('do_success', $url, 0);//url跳转了
				
			
			}//!$allow_direct_visit
		
		}// end orderbuysubmit
		else
		{//进入投保信息确认页面
		
			$cart_insure_id = intval($_GET['cart_insure_id']);
			if(!$cart_insure_id)
			{
				ss_log("step 2, cart_insure_id: ".$cart_insure_id);
				showmessage("批量投保参数错误！");
			}
			else
			{	//comment by zhangxi, 20150113, 对应多个投保单号
				$ret_policy_output_list = get_policy_by_group_id($cart_insure_id);
			}
	
			ss_log("step 2, get bat_post_policy: ".$_GET['bat_post_policy']);
			
			$bat_post_policy = intval($_GET['bat_post_policy']);
			
			ss_log("step 2, bat_post_policy: ".$bat_post_policy);
			
			//start add by wangcya, for bug[193],能够支持多人批量投保
			if($bat_post_policy == 1)//批量投保的方式
			{			
				show_policy_info_view_mutil($ret_policy_output_list , $gid , $bat_post_policy);
			}
			//end add by wangcya, for bug[193],能够支持多人批量投保
			else
			{
				$ret_policy_output = $ret_policy_output_list[0];
				//投保信息确认页面信息展现
				show_policy_info_view($ret_policy_output , $gid , $bat_post_policy);
			}
		
		}
		
	}//end step == 2
	//end add by wangcya, 20150106,为了防止多次提交
		
}
elseif($op=="insure")//再次从保单管理中进行投保的方式
{

	///进行投保///////////////////////////////////////////////////////////////////

	$result_attr = post_policy($policy_id);
	
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
	////////////////////////////////////////////////////////////
	if($result==0)			
	{
		$url = '/user.php?act=policy_detail&policy_id='.$policy_id;
		showmessage('do_success', $url, 0);
	}
	else
	{
		showmessage("投保失败！请联系管理员，电话：010-83005550");
	}		
}
//comment by zhangxi, 20141230, 下载pdf格式的电子保单入口
elseif($op=="getpolicyfile")//获取电子保单
{
	$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
	include_once ($ROOT_PATH_ . 'api/EBaoApp/platformEnvironment.class.php');
	if(PlatformEnvironment::isMobilePlatform() && !isset($_REQUEST['platformId'])){
		$result_attr = get_policy_file($policy_id,"client_op",false);
		//add by dingchaoyang 2014-12-2
		//响应json数据到客户端
// 			print_r($result_attr);
		$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
		include_once ($ROOT_PATH_ . 'api/EBaoApp/eba_adapter.php');
		$result_attr['policyID'] = $policy_id;
		EbaAdapter::responseDownEPolicy($result_attr);
		//end add by dingchaoyang 2014-12-2
	}
	else{
		//added by zhangxi, 20150615, 20150615
		//增加pc端批量打包下载功能
		if(isset($_POST['list_policy_ids']) && !empty($_POST['list_policy_ids']))
		{
			ss_log(__FUNCTION__.", start get zip packet");
			$result_attr = download_ziped_policy_files($_POST['list_policy_ids'],
														"client_op");
		}
		
		else
		{
			$result_attr = get_policy_file($policy_id,"client_op",true);
		}
		
	}
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];

	
	//start add by wangcya, 20150205
	if($result!=0)
	{
		showmessage("获取电子保单失败，请联系管理员，电话：010-83005550");
	}
	//end add by wangcya, 20150205
}
elseif($op=="gettoubaoqurenpolicyfile")//
{
	$result_attr = get_toubaoqueren_policy_file($policy_id);

}
//comment by zhangxi,20141230, 这个接口用么???
elseif($op=="querypolicyfile")//查询电子保单
{
	$result_attr = query_policy_all($policy_id);
	
	echo json_encode($result_attr);	
	ss_log(__FILE__." after json_encode");
	exit(0);
	
}
//added by zhangxi, 20150603, 增加批单接口
elseif($op=="insurance_endorsement")
{
	$result_attr = insurance_endorsement($policy_id);
}
elseif($op=="withdraw")//注销保单
{
	ss_log("in cp_buy_product ,will withdraw，将要注销保单。");
	//add 2014-10-09   yes123 判断是否已到保险起期 BUG编号:#129
	$result_attr = withdraw_policy($policy_id);
	
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
	
	if ($result == 0) //success
	{
		ss_log("will execute function withdraw_order");
		
		//$url = 'policy.php?act=info&policy_id='.$policy_id;
		//2014-09-23 yes123 撤销保单的一系列关联，1.修改订单状态，2.退佣金
		//define('S_ROOT_BX', dirname(__FILE__).DIRECTORY_SEPARATOR);
		//include_once(S_ROOT1.'../common.php');
		//S_ROOT的根目录是 baoxian
		require_once (S_ROOT . '../includes/init.php');
		//require_once (S_ROOT . '../includes/lib_main.php');
		$path_lib_order = S_ROOT.'../includes/lib_order.php';
		
		ss_log($path_lib_order);
		include_once($path_lib_order);
		
		ss_log(__FILE__.", will withdraw_order");
		withdraw_order($policy_id);//
		ss_log(__FILE__.", after withdraw_order");
	}
	else
	{

		ss_log(__FILE__.", withdraw_policy fail!,so not into function withdraw_order!");
	}
	ss_log(__FILE__.", will before json_encode");	
	ss_log(__FILE__.", result code: ".$result);
	ss_log(__FILE__.", result message: ".$retmsg);
	
	//ss_log("before json_encode: ".print_r($result_attr));
	ss_log("json_encode: ".json_encode($result_attr));
	//add by dingchaoyang 2014-12-2
	//响应json数据到客户端
	$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
	include_once ($ROOT_PATH_ . 'api/EBaoApp/eba_adapter.php');
	EbaAdapter::responseCancelPolicy($result_attr);
	//end add by dingchaoyang 2014-12-2
	
	echo json_encode($result_attr);	
	
	ss_log(__FILE__.", after json_encode");
	exit(0);
}
elseif($op=="uploadfile_insured_xls")//上传XLS文件
{
	ss_log("uploadfile_insured_xls");
	$FILE = $_FILES['inputExcel'];
	$result_attr = post_policy_upload_file_xls($insurer_code,$FILE);
}
elseif($op=="save_to_cart")// add yes123 2015-05-25 把保单添加到购物车
{
	$cart_insure_id = isset($_REQUEST['cart_insure_id'])?$_REQUEST['cart_insure_id']:0;
	if($cart_insure_id)
	{
		$result = save_to_cart($cart_insure_id);
		echo json_encode($result);	
	}
}
