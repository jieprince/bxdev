<?php
/*
	--------------------------------------------------------------
	$Id: cp.php 13003 2009-08-05 06:46:06Z  $
*/

//include_once(S_ROOT.'./source/function_debug.php');
//include_once('./source/function_debug.php');
//ss_log("before common, ac:-----------".$_GET['ac']);

//通用文件
include_once('./common.php');
define('DEBUG_MODE', 0);

//echo "<pre>";print_r($_SESSION);exit;

//include_once(S_ROOT.'./source/function_cp.php');

//ss_log("ac:-----------".$_GET['ac']);

//直接允许通过的
$acs_pass = array(
		'product_buy_process_ret',//add by wangcya , 20150112,对java返回的处理
		'web_pc_yg_car_insurance',//阳光车险
		);

//允许的方法
$acs = array(
		'product_buy',
		'product_buy_process_ret',//add by wangcya , 20150112,对java返回的处理
		'web_pc_yg_car_insurance',//阳光车险
		'common',
		'product_buy_process_ajax_req',
		'product_upload',
		'admin_insurance_user',
		'admin_insurance_type',
		'admin_insurance_company',
		'admin_insurance_duty',
		'admin_career_category',
		'admin_insurance_product_attribute',
		'admin_insurance_product',
		'admin_insurance_additional',
		'admin_insurance_product_duty',
		'admin_insurance_product_duty_price',
		'admin_insurance_order',
		'admin_insurance_policy',
		'admin_insurance_product_influencingfactor',
		'admin_insurance_product_duty_price',
		'admin_customer_individual',
		'admin_customer_group',
		'admin_insurance_company_insure_type'
		);
		
$admin_action=array(
		'admin_insurance_user',
		'admin_insurance_type',
		'admin_insurance_company',
		'admin_insurance_duty',
		'admin_career_category',
		'admin_insurance_product_attribute',
		'admin_insurance_product',
		'admin_insurance_additional',
		'admin_insurance_product_duty',
		'admin_insurance_product_duty_price',
		'admin_insurance_order',
		'admin_insurance_policy',
		'admin_insurance_product_influencingfactor',
		'admin_insurance_product_duty_price',
		'admin_customer_individual',
		'admin_customer_group',
		'admin_sales_channel',
		'admin_insurance_company_insure_type'
		);

//echo $_GET['ac'];
		//add by rqs
if(in_array($_GET['ac'], $admin_action))
{
	//ss_log("ac: ".$_GET['ac']);
	//ss_log("in cp.php, admin_id: ".$_SESSION['admin_id'] );
	//ss_log("in cp.php, admin_name: ".$_SESSION['admin_name'] );
	
	if(empty($_SESSION['admin_name']))
	{
		ss_log("in cp ,admin , session is null ,so logout");
		$url = '../admin/privilege.php?act=logout';
		showmessage('do_success', $url, 0);
	}
	
	
	$_SGLOBAL['mobile_type'] = "pcweb";//add by wangcya , 20141104, 如果是管理端，则不要做wap了。
	
	//ss_log("in cp ,admin,session admin_name: ".$_SESSION['admin_name']);
	
	/////////////////////start by wangcya , 20140810 /////////////////////////////////
	ss_log("in cp, session admin_id: ".$_SESSION['admin_id']);
	ss_log("in cp, session admin_name: ".$_SESSION['admin_name']);
	
	$_SGLOBAL['supe_uid']= $_SESSION['admin_id'];
	// supe_username
	$_SGLOBAL['supe_username'] = $_SESSION['admin_name'];
	/////////////////////end by wangcya , 20140810 /////////////////////////////////
	//ss_log("in admin, supe_uid: ".$_SGLOBAL['supe_uid']." supe_username: ".$_SGLOBAL['supe_username']);
		
	
}
else
{
	//comment by zhangxi, 20150123, 活动的uid更新增加到这里?那么init中一些信息的更新就没法走入了
//包括这个用户导流进来的点击次数，最后登录ip地址等。
//comment by zhangxi, 20150123, 活动相关的uid处理增加到这里?

	//那么活动产品的访问，后面的操作都能够正常被执行到。
	$product_id = intval($_REQUEST['product_id']);//每个产品，也就是款式的id。
	if($product_id)//产品的id
	{
		include_once('../includes/lib_code.php');
		//echo "file:".__FILE__;
		ss_log(__FILE__."product_id: ".$product_id);
		$wheresql = "p.product_id='$product_id'";
		$sql = "SELECT p.*,padd.*,patt.* FROM ".tname('insurance_product_base')." p
		inner JOIN ".tname('insurance_product_additional')." padd ON padd.product_id=p.product_id
		inner JOIN ".tname('insurance_product_attribute')."  patt ON patt.attribute_id=p.attribute_id
		WHERE $wheresql";
		$query = $_SGLOBAL['db']->query($sql);
		$product = $product_arr = $_SGLOBAL['db']->fetch_array($query);
		//后续也可以加上保险公司分支处理
		ss_log(__FILE__.":product id=".$product_id.",attribute_type:".$product['attribute_type']);
		//if($product['attribute_type'] == "lvyouhuodong"
		//||$product['attribute_type'] == "jingwailvyouhuodong")
		//if(strstr($product['attribute_type'],$G_WORDS_HUODONG))//险种属性中只要含有huodong字符，则进入，保证代码通用性
		//{
					//$tstid=	decrypt("QUtQ", "syden1981");
					//showmessage("tstid:".$tstid);
			//进行校验
			//$uid_check = encrypt($_GET['uid'], "syden1981");
			//if($uid_check != $_GET['uid_check'])
			//{
				
			//	showmessage("用户信息有误！".$uid_check);
			//	return false;
			//}
			//解码uid并进行校验
			//mod by zhangxi, 20150312, 活动代码通用性处理
		//add by dingchaoyang 2015-3-23 条件
		$ROOT_PATH__1= str_replace ( 'baoxian/cp.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
		include_once ($ROOT_PATH__1 . 'api/EBaoApp/platformEnvironment.class.php');
		
		if(isset($_GET['uid']) && (!empty($_GET['uid'])) && !PlatformEnvironment::isMobilePlatform() && !PlatformEnvironment::isMobileHFivePlatform())
		{	
			ss_log(__FILE__."ZHX,uid: ".$_GET['uid']);
			if(!ebaoins_encrypt_check($_GET['uid'],"syden1981"))
			{
				showmessage("用户信息校验错误！");
				return false;
			}
		
			//$_SESSION['user_id'] = $_GET['uid'];
			//echo "test:".$_SESSION['user_id'];
			ss_log(__FILE__."ZHX,product_id: ".$product_id);
			
			// 找到了cookie, 验证cookie信息
			$str_uid = explode(',', $_GET['uid']);
			$real_uid = intval($str_uid[0]);
			$_SESSION['user_id'] = $real_uid;
	        $sql = "SELECT user_id, user_name FROM bx_users WHERE user_id = '" .$real_uid. "'";
	        $query = $_SGLOBAL['db']->query($sql);
			$row = $_SGLOBAL['db']->fetch_array($query);
	        
	        $_SESSION['user_name'] = $row['user_name'];
	        $_SESSION['login_type'] = "b";
	        $_SGLOBAL['supe_uid'] = $_SESSION['user_id'];
			// supe_username
			$_SGLOBAL['supe_username']=$_SESSION['user_name'];
			$_SGLOBAL['real_name']=$_SESSION['real_name'];
			
			//add yes123 2015-04-27 标识模仿b端登录
			$_SESSION['simulate_b_login'] = "simulate_b_login";
			ss_log(__FILE__.",simulate_b_login:".$_SESSION['simulate_b_login']);
		}
			
		//}
	}
		
	//add sessionmanager for app dingchaoyang 2014-12-21
	$ROOT_PATH__= str_replace ( 'baoxian/cp.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
	include_once ($ROOT_PATH__ . 'api/EBaoApp/eba_sessionManager.class.php');
	Eba_SessionManager::setSession();
	//end by dingchaoyang 2014-12-21
	
	//add log for app dingchaoyang 2014-12-23
	include_once ($ROOT_PATH__ . 'api/EBaoApp/eba_logManager.class.php');
	Eba_LogManager::log('data from cp.php for purchse policy .');
	//end
	
	//start add by wangcya , 20150112,对java返回的处理
	if(in_array($_GET['ac'], $acs_pass))//product_buy_process_ret
	{
		ss_log("ac:-----------".$_GET['ac']);
	}
	//end add by wangcya , 20150112,对java返回的处理
	
	
	else if(empty($_SESSION['user_id']))
	{
		ss_log("in cp, client,session is null" );
		
		if($_SGLOBAL['mobile_type']!="pcweb")
		{
			$url = '../mobile/user.php';
// 			//add by dingchaoyang 2014-12-18
// 			$ROOT_PATH__= str_replace ( 'baoxian/cp.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
// 			include_once ($ROOT_PATH__ . 'api/EBaoApp/platformEnvironment.class.php');
// 			if ((PlatformEnvironment::isMobilePlatform())){
// // 				header("HTTP/1.1 301 Moved Permanently");
// // 				header("Location: $url");
// // 				exit();
// 				//add by dingchaoyang 2014-12-12
// 				//响应json数据到客户端
// 				include_once ($ROOT_PATH__ . 'api/EBaoApp/eba_adapter.php');
// 				EbaAdapter::responseData('Eba_SessionInvalid');
// 				//end add by dingchaoyang 2014-12-12
// 			}
		}
		else
		{
			$url = '../user.php';
			
		}
		
		showmessage('do_success', $url, 0);
	}
	
	//ss_log("in cp, client: session user_id: ".$_SESSION['user_id']);
	/////////////////////start by wangcya , 20140810 /////////////////////////////////
	
	$_SGLOBAL['supe_uid'] = $_SESSION['user_id'];
	// supe_username
	$_SGLOBAL['supe_username']=$_SESSION['user_name'];
	$_SGLOBAL['real_name']=$_SESSION['real_name'];
	/////////////////////end by wangcya , 20140810 /////////////////////////////////
	//ss_log("in client, supe_uid: ".$_SGLOBAL['supe_uid']." supe_username: ".$_SGLOBAL['supe_username']);
		
}
// add by rqs

$ac = (empty($_GET['ac']) || !in_array($_GET['ac'], $acs))?'profile':$_GET['ac'];
$op = empty($_GET['op'])?'':$_GET['op'];

/*

//权限判断
if(empty($_SGLOBAL['supe_uid'])) 
{
	if($_SERVER['REQUEST_METHOD'] == 'GET') 
	{
		ssetcookie('_refer', rawurlencode($_SERVER['REQUEST_URI']));
	} 
	else 
	{
		ssetcookie('_refer', rawurlencode('cp.php?ac='.$ac));
	}
	
	if($_SGLOBAL['mobile_type'] == 'myself')
	{
		echo_result(100,'to_login');//add  by wangcya, 2030118
			
	}
	else
	{
		//ss_log('in cp.php to_login,HTTP_USER_AGENT:'.$_SERVER['HTTP_USER_AGENT']);
		showmessage('to_login', 'do.php?ac='.$_SCONFIG['login_action']);
	}
}


//获取空间信息
$space = getspace($_SGLOBAL['supe_uid']);
if(empty($space)) 
{
	showmessage('space_does_not_exist');
}

//是否关闭站点
if(!in_array($ac, array('common', 'pm'))) 
{
	checkclose();
	//空间被锁定
	if($space['flag'] == -1) {
		showmessage('space_has_been_locked');
	}
	//禁止访问
	if(checkperm('banvisit')) {
		ckspacelog();
		showmessage('you_do_not_have_permission_to_visit');
	}
	//验证是否有权限玩应用
	if($ac =='userapp' && !checkperm('allowmyop')) {
		showmessage('no_privilege');
	}
}

//菜单
$actives = array($ac => ' class="active"');
*/

include_once(S_ROOT.'./source/cp_'.$ac.'.php');

?>