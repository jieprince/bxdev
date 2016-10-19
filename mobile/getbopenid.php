<?php

/**
 * ECSHOP mobile首页
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: index.php 15013 2010-03-25 09:31:42Z liuhui $
*/

@define('IN_ECS', true);
@define('ECS_ADMIN', true);

//'require(dirname(__FILE__) . '/includes/init.php');
include_once(dirname(__FILE__)  . '/../baoxian/source/function_debug.php');
ss_log("before init");
include_once('./includes/init.php');
include_once(ROOT_PATH.'includes/lib_common.php');
include_once(ROOT_PATH.'includes/inc_constant.php');
ss_log("after init");
//echo dirname(__FILE__)  . '/../baoxian/source/function_debug.php';
//echo $_GET['act'];
$actopt = isset($_GET['actopt'])?$_GET['actopt']:"";
ss_log("actopt: ".$actopt);
ss_log("into getbopenid php");

if($actopt == "getbopenid")
{	
	ss_log("start get b_openid");
	$cip = get_client_ip();
	ss_log("HTTP_CLIENT_IP:".$cip);
	$code =  isset($_GET['code'])?$_GET['code']:"";
	 if ($code) 
	 {
	 	$redirect_appid =  isset($_GET['appid'])?$_GET['appid']:"";
	 	if(APPID==$redirect_appid)
	 	{
	 		ss_log('get code success go autologin');
	 		$request = get_openid_and_access_token_by_code(APPID,APPSECRET,$code);
		    $b_openid = $request['openid'];
		    if($b_openid)
		    {
		   		ss_log('b_openid:'.$b_openid);
			    $_SESSION['b_openid'] = $b_openid;
			    $user = get_user_by_bopenid($b_openid);
			    if($user){
					set_session($user);
			    }
			    else
			    {
			    	ss_log('by b_openid get user is null:'.$b_openid);
			    }
			    
		    }
		    else
		    {
		    	ss_log('b_openid is null');
		    	
		    }
		    
		    $go_url = isset($_REQUEST['go_url'])?$_REQUEST['go_url']:"";
		    if($go_url)
		    {
		    	$go_url = str_replace("**","&",$go_url);
		    	ss_log("B端完成自动登录后重定向：".$go_url);
				header("Location: $go_url");
		    }
		    
	 	}
	 	else
	 	{
	 		ss_log("get_b_openid appid：".APPID." 不等于 redirect_appid：$redirect_appid");
	 		
	 	}

	    
	 }
	 else
	 {
	 	get_b_openid();
	 }
}

/*获取活动使用的b openid*/
else if($actopt == "activity_getbopenid")
{
	ss_log("start get activity_b_openid");
	
	$cip = get_client_ip();
	ss_log("HTTP_CLIENT_IP:".$cip);
	$code =  isset($_GET['code'])?$_GET['code']:"";
	 if ($code) 
	 {
	 	$redirect_appid =  isset($_GET['appid'])?$_GET['appid']:"";
 		ss_log('get activity code success go autologin');
 		$request = get_openid_and_access_token_by_code(APPID,APPSECRET,$code);
	    $b_openid = $request['openid'];
	    if($b_openid)
	    {
	   		ss_log('b_openid:'.$b_openid);
		    $_SESSION['b_openid'] = $b_openid;
			

	    }
	    else
	    {
	    	ss_log('activity_b_openid is null');
	    	
	    }

				    	
    	$getc_url = isset($_REQUEST['getc_url'])?$_REQUEST['getc_url']:"";
    	$getc_url = $getc_url."&act=getc";
    	ss_log("获取活动 B端openid后，跳转的URL：".$url);
    	header("Location: $getc_url");
	    
	 }
	 else
	 {
	 	get_activity_b_openid();
	 }
	
}

function get_activity_b_openid($url){
    if (!array_key_exists('code', $_GET)) {
         ss_log("get_b_openid, not code, go get code..");
         $url = "http://" . $_SERVER['HTTP_HOST'] . "/mobile/getbopenid.php?actopt=activity_getbopenid&act=getc&appid=".APPID."&getc_url=".$url; //微信服务器会执行这个url，就到init去了
         handler_get_wx_code(APPID,"snsapi_userinfo",$url);
    }
}



function get_b_openid($go_url=""){
    if (!array_key_exists('code', $_GET)) {
         ss_log("get_b_openid, not code, go get code..");
         $url = "http://" . $_SERVER['HTTP_HOST'] . "/mobile/getbopenid.php?actopt=getbopenid&appid=".APPID."&go_url=$go_url"; //微信服务器会执行这个url，就到init去了
         handler_get_wx_code(APPID,"snsapi_userinfo",$url);
    }
}

function set_session($user){
	ss_log("start set_session by b_openid");
	$_SESSION['user_id'] = $user['user_id'];
	$_SESSION['user_name'] = $user['user_name'];    
	$_SESSION['real_name'] = $user['real_name']; 
	$_SESSION['check_status'] =  $user['check_status']; 
	$_SESSION['login_type'] =  "b"; 
	//set_website_type($user['user_id']);  
}

//通过openid 获取用户信息
function get_user_by_bopenid($b_openid) {
    $sql = "SELECT * FROM" . $GLOBALS['ecs']->table('users') ." WHERE openid = '" . $b_openid . "'";
    return $GLOBALS['db']->getRow($sql);
}

?>
