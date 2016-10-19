<?php



@define('IN_UCHOME', TRUE);

define('S_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('CONNECT_ROOT',			dirname(__FILE__) );

////////////////////////////////////////////////////////////////////////
include_once(S_ROOT.'./source/function_debug.php');//add by wangcya,20141017
include_once(S_ROOT.'./source/my_const.php');//add by wangcya,20121121
include_once(S_ROOT.'./source/function_common.php');


//add yes123 2015-02-06 ,不加，微信端会报错
global $_SC;   
////////////////////////////////////////////////////////////////////////

//set_magic_quotes_runtime(0);
ini_set("magic_quotes_runtime",0);

//GPC过滤，自动转�?_GET�?_POST�?_COOKIE中的特殊字符，防止SQL注入攻击
/*取得 PHP环境变量magic_quotes_gpc 的值�?
 �?magic_quotes_gpc 打开时，所有的 ' (单引�?, " (双引�?, \ (反斜�? and 空字符会自动转为含有反斜线的转义字符�?
 		*/
$magic_quote = get_magic_quotes_gpc();

if(empty($magic_quote))
{//没打开的时�?
	
	//ss_log("$magic_quote is empty, so saddslashes");
	//showmessage("come here!");
	//原先就有的代码，comment by wangcya , 20140307,for bug[898] 防止SQL注入，一开始就做了这个过滤的工作�?
	$_GET = saddslashes($_GET);

	$_POST = saddslashes($_POST);

}



//ss_log("in common.php, admin_id: ".$_SESSION['admin_id'] );
//ss_log("in common.php, admin_name: ".$_SESSION['admin_name'] );

//ss_log("into common.php");
//ss_log("useragent: ".$_SERVER['HTTP_USER_AGENT']);

$_SGLOBAL = $_SCONFIG = $_SBLOCK = $_TPL = $_SCOOKIE = $_SN = $space = array();

/////////////////////////////////////////////////////////////////////////

if(!@include_once(S_ROOT.'./config.php'))
//if(!include_once(S_ROOT.'./config.php'))
{
	
	ss_log("not include config.php success, exit");

	header("Location: install/index.php");//��װ

	exit();

}
/////////////////////////////////////////////////////////////////////////



/////////////////////////////////////////////////////////////////////////


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


$mtime = explode(' ', microtime());

$_SGLOBAL['timestamp'] = $mtime[1];

$_SGLOBAL['supe_starttime'] = $_SGLOBAL['timestamp'] + $mtime[0];

/*
if(empty($_SCONFIG['login_action'])) 
	$_SCONFIG['login_action'] = md5('login'.md5($_SCONFIG['sitekey']));

if(empty($_SCONFIG['register_action'])) 
	$_SCONFIG['register_action'] = md5('register'.md5($_SCONFIG['sitekey']));
*/


//��վURL

/*
if(empty($_SC['siteurl']))
	$_SC['siteurl'] = getsiteurl();
*/


//������ݿ�?
//echo "before dbconnect dbname-2: ".$_SC['dbname']."</br>";
//ss_log("before dbconnect, dbname: ".$_SC['dbname']);
//ss_log("--incommon.php--mobile_type: ".$_SGLOBAL['mobile_type']);

if($_SGLOBAL['mobile_type']!="pcweb")
{

	//echo "dbconnect: ".S_ROOT."</br>";
	include_once(S_ROOT . 'source/class_mysql.php');
	
	//echo "global db: ".$_SGLOBAL['db']."</br>";
	if(empty($_SGLOBAL['db']))
	{
	
		//ss_log("in common.php ,mobile_type: ".$_SGLOBAL['mobile_type']);
		//echo "new db!: "."</br>";
	
		/*
		 if($_SGLOBAL['mobile_type']!="pcweb")
		 {
		$_SC['dbhost'] = "localhost";
		$_SC['dbuser'] = "dbbxuser";
		$_SC['dbpw'] = "dbkl127jnmc";
		$_SC['dbname'] = "bxdb";
		$_SC['pconnect'] = 0;
		$_SC['dbcharset'] ="utf8";
		//include_once(S_ROOT.'./config.php')
		ss_log("mobile set config!");
		}
		//echo $_SC['dbhost']."-".$_SC['dbuser']."-".$_SC['dbpw']."-".$_SC['dbname']."-".$_SC['pconnect']."</br>";
		*/
		//ss_log("in common ,dbhost: ".$_SC['dbhost']." -dbuser: ".$_SC['dbuser']." -dbpw: ".$_SC['dbpw']." -dbname: ".$_SC['dbname']." -pconnect: ".$_SC['pconnect']);
	
		$_SGLOBAL['db'] = new dbstuff;
		$_SGLOBAL['db']->charset = $_SC['dbcharset'];
		$_SGLOBAL['db']->connect($_SC['dbhost'], $_SC['dbuser'], $_SC['dbpw'], $_SC['dbname'], $_SC['pconnect']);
	
	}	
}
else
{
	//ss_log("will dbconnect");
	dbconnect();//不知道为何，移动端访问时候，全局变量 $_SC和$_SGLOBAL失效了。
}

//ss_log("after dbconnect");

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
	 	ss_log("in common, session_start");
	 	session_start();
	 }
	 
	//add by rqs
}
 
// ss_log("session uid: ".$_SESSION['user_id']);
 //start add by wangcya, 20121126,for bug[348]///
 

///////////////////////////add by wangcya ,20140914/////////////
include_once(S_ROOT.'./source/function_baoxian_pingan.php');
include_once(S_ROOT.'./source/function_baoxian_huatai.php');
include_once(S_ROOT.'./source/function_baoxian_taipingyang.php');
//added by zhangxi, 20141229, for huaan
include_once(S_ROOT.'./source/function_baoxian_huaan.php');
//added by zhangxi, 20150325, for xinhua
include_once(S_ROOT.'./source/function_baoxian_xinhua.php');
//added by zhangxi, 20150325, for 国寿
include_once(S_ROOT.'./source/function_baoxian_chinalife.php');
////////////////////////////////////////////////////////////////
//增加人保寿险
include_once(S_ROOT.'./source/function_baoxian_picclife.php');

//增加人保财险
include_once(S_ROOT.'./source/function_baoxian_epicc.php');

//增加太平洋货运险
include_once(S_ROOT.'./source/function_baoxian_cpic_cargo.php');
include_once(S_ROOT.'./source/function_baoxian_sinosig.php');

//诉讼保全
include_once(S_ROOT.'./source/function_baoxian_ssbq.php');


include_once(S_ROOT.'./source/function_baoxian.php');

///////////start add by wangcya , 20140812//////////////////////////////////////////
if(!defined('IN_ECS'))
{
	define('IN_ECS', true);
}

@include_once('includes/init.php');
///////////end add by wangcya , 20140812//////////////////////////////////////////

//ss_log("in common. after init.php");

?>