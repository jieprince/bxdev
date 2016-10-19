<?php


/**
 * ECSHOP mobile前台公共函数
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: init.php 15013 2008-10-23 09:31:42Z liuhui $
*/

if (!defined('IN_ECS')) {
	die('Hacking attempt');
}
define('ECS_WAP', true);

error_reporting(E_ALL);

if (__FILE__ == '') {
	die('Fatal error code: 0');
}

/* 取得当前ecshop所在的根目录 */
define('ROOT_PATH', str_replace('mobile/includes/init.php', '', str_replace('\\', '/', __FILE__)));

/* 初始化设置 */
@ ini_set('memory_limit', '64M'); //设置php可以使用的内存大小为64M。
@ ini_set('session.cache_expire', 180);//指定会话页面在客户端cache中的有限期（分钟）缺省下为180分钟。如果设置了session.cache_limiter=nocache时，此处设置无   效。
@ ini_set('session.use_cookies', 1); //是否使用cookie在客户端保存会话ID；
@ ini_set('session.auto_start', 0);//是否自动开session处理，设置为1时，程序中不用session_start()来手动开启session也可使用session，
@ ini_set('display_errors', 1);//设置错误信息的类别。
@ ini_set("arg_separator.output", "&amp;");

if (DIRECTORY_SEPARATOR == '\\') {
	@ ini_set('include_path', '.;' . ROOT_PATH);
} else {
	@ ini_set('include_path', '.:' . ROOT_PATH);
}

if (file_exists(ROOT_PATH . 'data/config.php')) {
	include (ROOT_PATH . 'data/config.php');
} else {
	include (ROOT_PATH . 'includes/config.php');
}

if (defined('DEBUG_MODE') == false) {
	define('DEBUG_MODE', 0);
}

if (PHP_VERSION >= '5.1' && !empty ($timezone)) {
	date_default_timezone_set($timezone);
}

$php_self = isset ($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
if ('/' == substr($php_self, -1)) {
	$php_self .= 'index.php';
}
define('PHP_SELF', $php_self);
require (ROOT_PATH . 'includes/inc_constant.php');
require (ROOT_PATH . 'includes/cls_ecshop.php');
require (ROOT_PATH . 'includes/lib_goods.php');
require (ROOT_PATH . 'includes/lib_base.php');
require (ROOT_PATH . 'includes/lib_common.php');
require (ROOT_PATH . 'mobile/includes/lib_common.php'); //add yes123 2015-01-13 mobile公共函数
require (ROOT_PATH . 'includes/lib_time.php');
require (ROOT_PATH . 'includes/lib_main.php');
require (ROOT_PATH . 'mobile/includes/lib_main.php');
require (ROOT_PATH . 'includes/cls_error.php');
include_once(ROOT_PATH . 'baoxian/source/function_debug.php');
//add yes123 2015-02-06 不加微信端报错
include_once(ROOT_PATH . 'baoxian/config.php');

/* 对用户传入的变量进行转义操作。*/
if (!get_magic_quotes_gpc()) {
	if (!empty ($_GET)) {
		$_GET = addslashes_deep($_GET);
	}
	if (!empty ($_POST)) {
		$_POST = addslashes_deep($_POST);
	}

	$_COOKIE = addslashes_deep($_COOKIE);
	$_REQUEST = addslashes_deep($_REQUEST);
}

/* 创建 ECSHOP 对象 */
$ecs = new ECS($db_name, $prefix);

/* 初始化数据库类 */
require (ROOT_PATH . 'includes/cls_mysql.php');
$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
$db_host = $db_user = $db_pass = $db_name = NULL;

/* 创建错误处理对象 */
$err = new ecs_error('message.dwt');

/* 载入系统参数 */
$_CFG = load_config();

/* 初始化session */
require (ROOT_PATH . 'includes/cls_session.php');
$sess = new cls_session($db, $ecs->table('sessions'), $ecs->table('sessions_data'), 'ecsid');
define('SESS_ID', $sess->get_session_id());

if (!defined('INIT_NO_SMARTY')) {
	header('Cache-control: private');
	header('Content-type: text/html; charset=utf-8');

	/* 创建 Smarty 对象。*/
	require (ROOT_PATH . 'includes/cls_template.php');
	$smarty = new cls_template;

	$smarty->cache_lifetime = $_CFG['cache_time'];
	$smarty->template_dir = ROOT_PATH . 'mobile/templates';
	$smarty->cache_dir = ROOT_PATH . 'temp/caches';
	$smarty->compile_dir = ROOT_PATH . 'temp/compiled/mobile';

	if ((DEBUG_MODE & 2) == 2) {
		$smarty->direct_output = true;
		$smarty->force_compile = true;
	} else {
		$smarty->direct_output = false;
		$smarty->force_compile = false;
	}
}

ss_log("defined('INIT_NO_USERS'):".defined('INIT_NO_USERS'));
if (!defined('INIT_NO_USERS')) {
	/* 会员信息 */
	$user = & init_users();
	if (empty ($_SESSION['user_id'])) {
		if ($user->get_cookie()) {
			/* 如果会员已经登录并且还没有获得会员的账户余额、积分以及优惠券 */
			if ($_SESSION['user_id'] > 0 && !isset ($_SESSION['user_money'])) {
				update_user_info();
			}
		} else {
			$_SESSION['user_id'] = 0;
			$_SESSION['user_name'] = '';
			$_SESSION['email'] = '';
			$_SESSION['user_rank'] = 0;
			$_SESSION['discount'] = 1.00;
		}
	}
}
	
/* 设置推荐会员 */
if (isset($_GET['u']))
{
	clear_user();
	ss_log("_GET['u']:".$_GET['u']);
    set_affiliate();
}
    
if ((DEBUG_MODE & 1) == 1) {
	error_reporting(E_ALL);
} else {
	error_reporting(E_ALL ^ E_NOTICE);
}
if ((DEBUG_MODE & 4) == 4) {
	include (ROOT_PATH . 'includes/lib.debug.php');
}

/* 判断是否支持gzip模式 */
if (gzip_enabled()) {
	ob_start('ob_gzhandler');
}

//add yes123 2015-02-09 判断如果session里有user_id,登录类型不是b端的，清空session信息
$user_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
$login_type = isset($_SESSION['login_type'])?$_SESSION['login_type']:"";
if($user_id && $login_type=='c'){
	clear_user();
}


$GLOBALS['_CFG']['wxlogin'] = 1;
$b_openid = isset($_SESSION['b_openid']) ? $_SESSION['b_openid'] : '';
ss_log("init b_openid: ".$b_openid);
$act = isset($_REQUEST['act'])?$_REQUEST['act']:""; //如果act不等于getc，就获取B端openid，活动就不获取，活动自己获取
// start add yes123 2014-12-26 获取openid

//不需要获取openid的请求
$no_get_openid = array('getc','clear_session','phone_code');

/*if(!in_array($act,$no_get_openid))
{
		if (!$b_openid) {
		 if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) 
		 {  
		 	include_once(ROOT_PATH . 'mobile/getbopenid.php');
		 	ss_log("get_b_openid path: ".ROOT_PATH . 'mobile/getbopenid.php');
		 	$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		 	$query_string = $_SERVER["QUERY_STRING"];
		 	if($query_string){
		 		$query_string = str_replace("&","**",$query_string);
		 		$url.="?".$query_string;
		 	}
			get_b_openid($url);
		 }
		}
}*/
// end add yes123 2014-12-26 获取openid


/* wap头文件 */
//if (substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/')) != '/user.php')
header("Content-Type:text/html; charset=utf-8");

if (empty ($_CFG['wap_config'])) {
	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><title>" . $GLOBALS['_CFG']['shop_name'] . "</title></head><body><p align='left'>对不起,{$_CFG['shop_name']}暂时没有开启手机购物功能</p></body></html>";
	exit ();
}

function clear_user()
{
	$_SESSION['user_id'] = 0;
	$_SESSION['user_name'] = "";    
	$_SESSION['real_name'] = ""; 
	$_SESSION['b_openid'] = ""; 
	$_SESSION['c_openid'] = "";
	$_SESSION['login_type']="";
}

?>