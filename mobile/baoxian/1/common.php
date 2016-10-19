<?php

@define('IN_UCHOME', TRUE);

define('S_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('CONNECT_ROOT',			dirname(__FILE__) );


///////////start add by wangcya , 20140812//////////////////////////////////////////
if(!defined('IN_ECS'))
{
	define('IN_ECS', true);
}


@include_once('includes/init.php');
///////////end add by wangcya , 20140812//////////////////////////////////////////


/////////start add by wangcya 20140811///////////////////////////////
//define('IN_ECS', true);
//require(S_ROOT.'../includes/init.php');
/////////end add by wangcya 20140811///////////////////////////////


if(!@include_once(S_ROOT.'./config.php')) {

	header("Location: install/index.php");//��װ

	exit();

}

$_SGLOBAL = $_SCONFIG = $_SBLOCK = $_TPL = $_SCOOKIE = $_SN = $space = array();



include_once(S_ROOT.'./source/my_const.php');//add by wangcya,20121121
include_once(S_ROOT.'./source/function_common.php');
include_once(S_ROOT.'./source/function_debug.php');//add by wangcya,20141017

///////////////////////////add by wangcya ,20140914/////////////
include_once(S_ROOT.'./source/function_baoxian_pingan.php');
include_once(S_ROOT.'./source/function_baoxian_huatai.php');
include_once(S_ROOT.'./source/function_baoxian_taipingyang.php');
////////////////////////////////////////////////////////////////

include_once(S_ROOT.'./source/function_baoxian.php');
////////////////////////////////////////////////////////////////


$mtime = explode(' ', microtime());

$_SGLOBAL['timestamp'] = $mtime[1];

$_SGLOBAL['supe_starttime'] = $_SGLOBAL['timestamp'] + $mtime[0];

/*
if(empty($_SCONFIG['login_action'])) 
	$_SCONFIG['login_action'] = md5('login'.md5($_SCONFIG['sitekey']));

if(empty($_SCONFIG['register_action'])) 
	$_SCONFIG['register_action'] = md5('register'.md5($_SCONFIG['sitekey']));
*/

//GPC过滤，自动转�?_GET�?_POST�?_COOKIE中的特殊字符，防止SQL注入攻击
/*取得 PHP环境变量magic_quotes_gpc 的值�?
 �?magic_quotes_gpc 打开时，所有的 ' (单引�?, " (双引�?, \ (反斜�? and 空字符会自动转为含有反斜线的转义字符�?
*/
$magic_quote = get_magic_quotes_gpc();

if(empty($magic_quote)) {//没打开的时�?
	//showmessage("come here!");
	//原先就有的代码，comment by wangcya , 20140307,for bug[898] 防止SQL注入，一开始就做了这个过滤的工作�?
	$_GET = saddslashes($_GET);

	$_POST = saddslashes($_POST);

}


//��վURL

/*
if(empty($_SC['siteurl']))
	$_SC['siteurl'] = getsiteurl();
*/


//������ݿ�?
//echo "before dbconnect dbname-2: ".$_SC['dbname']."</br>";
dbconnect();



//COOKIE

$prelength = strlen($_SC['cookiepre']);

foreach($_COOKIE as $key => $val) {

	if(substr($key, 0, $prelength) == $_SC['cookiepre']) {

		$_SCOOKIE[(substr($key, $prelength))] = empty($magic_quote) ? saddslashes($val) : $val;

	}

}


//����GIP

if ($_SC['gzipcompress'] && function_exists('ob_gzhandler')) {

	ob_start('ob_gzhandler');

} else {

	ob_start();

}


$_SGLOBAL['mobile_type']='web';//add by wangcya ,20121005

$_SGLOBAL['supe_uid'] = 0;



if(!isset($_SERVER['REQUEST_URI'])) {

	$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];

	if(isset($_SERVER['QUERY_STRING']))
		$_SERVER['REQUEST_URI'] .= '?'.$_SERVER['QUERY_STRING'];

}

if($_SERVER['REQUEST_URI']) {

	$temp = urldecode($_SERVER['REQUEST_URI']);

	if(strexists($temp, '<') || strexists($temp, '"')) {

		$_GET = shtmlspecialchars($_GET);//XSS

	}

}


//ss_log("useragent: ".$_SERVER['HTTP_USER_AGENT']);
if(stristr($_SERVER['HTTP_USER_AGENT'],'kangyq'))//add by wangcya , 20130521,来自我们自己的手机客户端
{
	$_SGLOBAL['mobile_type'] = 'myself';
}
elseif(stristr($_SERVER['HTTP_USER_AGENT'],'Android'))

{
	//ss_log('in Android');

	$_SGLOBAL['mobile_type'] = 'android';

}

elseif(stristr($_SERVER['HTTP_USER_AGENT'],'iPhone'))

{

	//ss_log('in iPhone');
	$_SGLOBAL['mobile_type'] = 'iphone';

}
/*
 elseif(stristr($_SERVER['HTTP_USER_AGENT'],'iPad'))

 {

//ss_log('in iPhone');
$_SGLOBAL['mobile_type'] = 'ipad';

}*/
elseif(stristr($_SERVER['HTTP_USER_AGENT'],'posttest'))//来自于测试工�?
{

	//ss_log('in common mobile_type:'.$_SGLOBAL['mobile_type']);
	//ss_log('in common useragent:'. $_SERVER['HTTP_USER_AGENT']);

	//ss_log('in posttest');
	$_SGLOBAL['mobile_type'] = 'posttest';

}
else
{
	//ss_log('in web');

	$_SGLOBAL['mobile_type'] = 'pcweb';

}


//////////////end add wangcya,20121003///////////////////
//date_default_timezone_set('PRC');//add by wangcya , 20140811 ,统一设置的时区�?

// checkauth(); ，把我们自己的检测是否登录的取消掉�?

$_SGLOBAL['uhash'] = md5($_SGLOBAL['supe_uid']."\t".substr($_SGLOBAL['timestamp'], 0, 6));

///////////////////////////////////////////////////////////////
/*
$mydo = $_GET['do'];
$ydhl_action = array("clientsaleschannel");
if(in_array($mydo, $ydhl_action))
{

}
else
*/
{
	//开启session
 
	 //print_r($GLOBALS['_SESSION']);
	 //print_r($_SESSION);
	 //exit(0);
	 
	 $a = session_id();
	 if(empty($a)) 
	 {
	 	ss_log("session_start");
	 	session_start();
	 }
	 
	//add by rqs
}
 
// ss_log("session uid: ".$_SESSION['user_id']);
 //start add by wangcya, 20121126,for bug[348]///
?>