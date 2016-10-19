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
include_once('./includes/lib_common.php');
ss_log("after init");
//echo dirname(__FILE__)  . '/../baoxian/source/function_debug.php';
//echo $_GET['act'];
$actopt = isset($_GET['actopt'])?$_GET['actopt']:"";
ss_log("actopt: ".$actopt);
ss_log("into getcopenid php");

if($actopt == "getcopenid")
{	
	ss_log("start get c_openid");
	$appid="wxea0c670f3b2e1a22";
	$appsecret="2d240f57f3a28527929f6d0f4104db97";
	
	$cip = get_client_ip();
	ss_log("HTTP_CLIENT_IP:".$cip);
	$code =  isset($_GET['code'])?$_GET['code']:"";
	 if ($code) 
	 {
	 	$redirect_appid =  isset($_GET['appid'])?$_GET['appid']:"";
	 	if($appid==$redirect_appid)
	 	{
	 		ss_log('get code success go autologin');
	 		$request = get_openid_and_access_token_by_code($appid,$appsecret,$code);
		    $c_openid = $request['openid'];
		    if($c_openid)
		    {
		   		ss_log('c_openid:'.$c_openid);
			    $_SESSION['c_openid'] = $c_openid;
			    $getc_url =  isset($_GET['getc_url'])?$_GET['getc_url']:"";
			   $getc_url = $getc_url."&act=getc";
			    ss_log("getc_url:".$getc_url);
			    header("Location: $getc_url");
			    
		    }
		    else
		    {
		    	ss_log('c_openid is null');
		    	
		    }

		    
	 	}
	 	else
	 	{
	 		ss_log("get_c_openid appid：$appid 不等于 redirect_appid：$redirect_appid");
	 		
	 	}

	    
	 }
	 else
	 {
	 	get_c_openid();
	 }
}




function get_c_openid($url){
	$appid="wxea0c670f3b2e1a22";
	$appsecret="2d240f57f3a28527929f6d0f4104db97";
    if (!array_key_exists('code', $_GET)) {
         ss_log("get_c_openid, not code, go get code..");
         $url = "http://" . $_SERVER['HTTP_HOST'] . "/mobile/getcopenid.php?actopt=getcopenid&act=getc&appid=$appid&getc_url=".$url; //微信服务器会执行这个url，就到init去了
         handler_get_wx_code($appid,"snsapi_userinfo",$url);
    }
}



?>
