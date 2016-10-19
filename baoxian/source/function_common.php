<?php
/*
	kangyq.com
	$Id: function_common.php 13235 2009-08-24 09:48:36Z shirui $
*/

@define('IN_UCHOME', TRUE);


if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

include_once(S_ROOT . 'source/function_debug.php');


//added by zhangxi, 20150619 ,保险公司代码数据
$ARR_INS_COMPANY_NAME = array(
							"str_cpic_tj_property"=>"CPIC_TJ_PROPERTY",
								);

////////////////////////////////////////////////////////////////////////////////



//added by zhangxi,20150424, 根据出生日期获取年龄,几岁了
function get_age_by_birthday($birthday)
{
	if(empty($birthday))
	{
		return -1;
	}
	$age = date('Y', time()) - date('Y', strtotime($birthday)) - 1;  
	if (date('m', time()) == date('m', strtotime($birthday))){  
	  
	    if (date('d', time()) > date('d', strtotime($birthday))){  
	    $age++;  
	    }  
	}elseif (date('m', time()) > date('m', strtotime($birthday))){  
	    $age++;  
	}  
	return $age;  
}
/**************************************************************
       *
       *    使用特定function对数组中所有元素做处理
       *    @param  string  &$array     要处理的字符串
       *    @param  string  $function   要执行的函数
       *    @return boolean $apply_to_keys_also     是否也应用到key上
       *    @access public
       *
 *************************************************************/
function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
        	//mod by zhangxi, 数字啦出来不处理
        	if(is_numeric($value))
        	{
        		$array[$key] = $value;
        	}
        	else
        	{
        		$array[$key] = $function($value);
        	}
            
        }
 
        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
        }
    }
    $recursive_counter--;
}
 
/**************************************************************
 *
 *    将数组转换为JSON字符串（兼容中文）
 *    @param  array   $array      要转换的数组
 *    @return string      转换得到的json字符串
 *    @access public
 *
 *************************************************************/
function TO_JSON($array) {
    arrayRecursive($array, 'urlencode', true);
    $json = json_encode($array);
    return urldecode($json);
}




/* //add by wangcya , 20140307,for bug[898] 防止SQL注入
 *
* addslashes addcslashes 是有区别的，
addslashes(string)
addslashes() 函数在指定的预定义字符前添加反斜杠。

这些预定义字符是：

单引号 (')
双引号 (")
反斜杠 (\)
NULL

=========================================
addcslashes(string,characters)
addcslashes() 函数在指定的字符前添加反斜杠。
*/
function stripsearchkey($string) {
	$string = trim($string);
	$string = str_replace('*', '%', addcslashes($string, '%_'));
	$string = str_replace('_', '\_', $string);
	return $string;
}

//SQL ADDSLASHES
function saddslashes($string) {
	if(is_array($string)) {//如果是数组
		foreach($string as $key => $val) {
			$string[$key] = saddslashes($val);//递归调用,//comment by wangcya , 20140307,for bug[898] 防止SQL注入
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

//取HTML
function shtmlspecialchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = shtmlspecialchars($val);
		}
	} 
	else 
	{
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
			str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}

//址芗
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;	// 钥 取值 0-32;
				// 钥魏喂桑原暮钥全同芙也每尾同平讯取
				// 取值越谋涠侥变化 = 16  $ckey_length 畏
				// 值为 0 时虿徊钥

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

//cookie
function clearcookie() {
	global $_SGLOBAL;

	obclean();
	ssetcookie('auth', '', -86400 * 365);
	$_SGLOBAL['supe_uid'] = 0;
	$_SGLOBAL['supe_username'] = '';
	$_SGLOBAL['member'] = array();

}

//cookie
function ssetcookie($var, $value, $life=0) {
	global $_SGLOBAL, $_SC, $_SERVER;
	setcookie($_SC['cookiepre'].$var, $value, $life?($_SGLOBAL['timestamp']+$life):0, $_SC['cookiepath'], $_SC['cookiedomain'], $_SERVER['SERVER_PORT']==443?1:0);
}

//菘
function dbconnect() 
{
	global $_SGLOBAL, $_SC;
	////////////////////////////////////////////////////////
	//ss_log("will into function dbconnect");
	
	//ss_log("in function dbconnect, dbname: ".$_SC['dbname']);
	
    //var_dump($_SGLOBAL);
	//var_dump($_SC);
	
	//echo "dbname-4: ".$_SC['dbname']."</br>";
	//ss_log("into function dbconnect");
	//echo "dbconnect: ".S_ROOT."</br>";
	include_once(S_ROOT . 'source/class_mysql.php');

	//echo "global db: ".$_SGLOBAL['db']."</br>";
	if(empty($_SGLOBAL['db'])) 
	{
		
		//ss_log("in dbconnect ,mobile_type: ".$_SGLOBAL['mobile_type']);
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
		
		//ss_log("dbconnect, dbhost: ".$_SC['dbhost']." -dbuser: ".$_SC['dbuser']." -dbpw: ".$_SC['dbpw']." -dbname: ".$_SC['dbname']." -pconnect: ".$_SC['pconnect']);
		
		$_SGLOBAL['db'] = new dbstuff;
		$_SGLOBAL['db']->charset = $_SC['dbcharset'];
		$_SGLOBAL['db']->connect($_SC['dbhost'], $_SC['dbuser'], $_SC['dbpw'], $_SC['dbname'], $_SC['pconnect']);
	}
	else
	{
		//ss_log("dbconnect, global db already create!");
	}
}


//菘
function dbconnect_mobile($_SC)
{
	global $_SGLOBAL;
	////////////////////////////////////////////////////////
	ss_log("will into function dbconnect");

	ss_log("in function dbconnect, dbname: ".$_SC['dbname']);

	//var_dump($_SGLOBAL);
	//var_dump($_SC);

	//echo "dbname-4: ".$_SC['dbname']."</br>";

	//echo "dbconnect: ".S_ROOT."</br>";
	include_once(S_ROOT . 'source/class_mysql.php');

	//echo "global db: ".$_SGLOBAL['db']."</br>";
	if(empty($_SGLOBAL['db']))
	{

		ss_log("in dbconnect ,mobile_type: ".$_SGLOBAL['mobile_type']);
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
		ss_log("dbhost: ".$_SC['dbhost']." -dbuser: ".$_SC['dbuser']." -dbpw: ".$_SC['dbpw']." -dbname: ".$_SC['dbname']." -pconnect: ".$_SC['pconnect']);

		$_SGLOBAL['db'] = new dbstuff;
		$_SGLOBAL['db']->charset = $_SC['dbcharset'];
		$_SGLOBAL['db']->connect($_SC['dbhost'], $_SC['dbuser'], $_SC['dbpw'], $_SC['dbname'], $_SC['pconnect']);
	
	}
}


//start add by wangcya , 20131220 for bug[855] 	分词每次都打开文件，效率很低
function splitword() 
{
	global $_SGLOBAL, $_SC;
	//////////////////////////////////////////////////////////////////
	include_once(S_ROOT.'./source/lib_splitword_full.php');
	
	if(empty($GLOBALS['splitword']))
	{//每次都要操作，而不是全局
		
		$GLOBALS['splitword'] = new SplitWord();//这个只是局部全局，不是全站全局
		$GLOBALS['splitword']->createflag = true;
	}
	else
	{
		//ss_log("haved splitword!");
	}

	//$sp->Clear();//清理分词，释放空间。
}

//end add by wangcya , 20131220 for bug[855] 	分词每次都打开文件，效率很低

//取IP
function getonlineip($format=0) {
	global $_SGLOBAL;

	if(empty($_SGLOBAL['onlineip'])) {
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			$onlineip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$onlineip = getenv('REMOTE_ADDR');
		} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
		$_SGLOBAL['onlineip'] = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
	}
	if($format) {
		$ips = explode('.', $_SGLOBAL['onlineip']);
		for($i=0;$i<3;$i++) {
			$ips[$i] = intval($ips[$i]);
		}
		return sprintf('%03d%03d%03d', $ips[0], $ips[1], $ips[2]);
	} else {
		return $_SGLOBAL['onlineip'];
	}
}

//卸系前没录状态
function checkauth() {
	global $_SGLOBAL, $_SC, $_SCONFIG, $_SCOOKIE, $_SN;

	if($_SGLOBAL['mobile'] && $_GET['m_auth']) $_SCOOKIE['auth'] = $_GET['m_auth'];
	if($_SCOOKIE['auth']) {
		@list($password, $uid) = explode("\t", authcode($_SCOOKIE['auth'], 'DECODE'));
		$_SGLOBAL['supe_uid'] = intval($uid);
		if($password && $_SGLOBAL['supe_uid']) {
			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('session')." WHERE uid='$_SGLOBAL[supe_uid]'");
			if($member = $_SGLOBAL['db']->fetch_array($query)) {
				if($member['password'] == $password) {
					$_SGLOBAL['supe_username'] = addslashes($member['username']);
					$_SGLOBAL['session'] = $member;
				} else {
					$_SGLOBAL['supe_uid'] = 0;
				}
			} else {
				$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('member')." WHERE uid='$_SGLOBAL[supe_uid]'");
				if($member = $_SGLOBAL['db']->fetch_array($query)) {
					if($member['password'] == $password) {
						$_SGLOBAL['supe_username'] = addslashes($member['username']);
						$session = array('uid' => $_SGLOBAL['supe_uid'], 'username' => $_SGLOBAL['supe_username'], 'password' => $password);
						include_once(S_ROOT.'./source/function_space.php');
						insertsession($session);//录
					} else {
						$_SGLOBAL['supe_uid'] = 0;
					}
				} else {
					$_SGLOBAL['supe_uid'] = 0;
				}
			}
		}
	}
	if(empty($_SGLOBAL['supe_uid'])) {
		clearcookie();
	} else {
		$_SGLOBAL['username'] = $member['username'];
	}
}

//取没app斜
function getuserapp() {
	global $_SGLOBAL, $_SCONFIG;

	$_SGLOBAL['my_userapp'] = $_SGLOBAL['my_menu'] = array();
	$_SGLOBAL['my_menu_more'] = 0;

	if($_SGLOBAL['supe_uid'] && $_SCONFIG['my_status']) {
		$space = getspace($_SGLOBAL['supe_uid']);
		$showcount=0;
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('userapp')." WHERE uid='$_SGLOBAL[supe_uid]' ORDER BY menuorder DESC", 'SILENT');
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			$_SGLOBAL['my_userapp'][$value['appid']] = $value;
			if($value['allowsidenav'] && !isset($_SGLOBAL['userapp'][$value['appid']])) {
				if($space['menunum'] < 5) $space['menunum'] = 10;
				if($space['menunum'] > 100 || $showcount < $space['menunum']) {
					$_SGLOBAL['my_menu'][] = $value;
					$showcount++;
				} else {
					$_SGLOBAL['my_menu_more'] = 1;
				}
			}
		}
	}
}

/*
//取
function tname($name) {
	global $_SC;
	return $_SC['tablepre'].$name;
}
*/

//取
function tname($name) {
	global $_SC;
	return 't_'.$name;
}

//取
function bx_tname($name) {
	global $_SC;
	return "bx_".$name;
}


//add by wangcya ,201211
function echo_result($code,$msgkey,$values=array())
{
	global $_SGLOBAL;

	//ss_log($msgkey);
	//
	include_once(S_ROOT.'./language/lang_showmessage.php');
	if(isset($_SGLOBAL['msglang'][$msgkey]))
	{
		$message = lang_replace($_SGLOBAL['msglang'][$msgkey], $values);
	}
	else
	{
		$message = $msgkey;
	}

	//ss_log('echo message:'.$message);

	print "<result>"
	. "<code> <![CDATA[" .$code. "]]> </code>"
	. "<message> <![CDATA[" .$message. "]]> </message>"
	. "</result>\n";

	exit();//add by wangcya, 20130117
}


//曰
function showmessage($msgkey, $url_forward='', $second=1, $values=array()) {
	//add by dingchaoyang 2014-12-18
	$ROOT_PATH__= str_replace ( 'baoxian/source/function_common.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
	include_once ($ROOT_PATH__ . 'api/EBaoApp/platformEnvironment.class.php');
	if ((PlatformEnvironment::isMobilePlatform())){
		include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/ebaOtherError/eba_other_error.class.php');
		$response = new Eba_OtherError($msgkey,$_REQUEST['command']);
		$result = $response->responseResult();
		exit ( iconv ( 'utf-8', 'gb2312//ignore', json_encode ( $result ) ) );
	}

	global $_SGLOBAL, $_SC, $_SCONFIG, $_TPL, $space, $_SN;

	obclean();

	//去
	$_SGLOBAL['ad'] = array();
	
	//
	include_once(S_ROOT.'./language/lang_showmessage.php');
	if(isset($_SGLOBAL['msglang'][$msgkey])) 
	{
		$message = lang_replace($_SGLOBAL['msglang'][$msgkey], $values);
	}
	else
	{
		$message = $msgkey;
	}
	
	
	//start add by wangcya, 20130117///////////////////
	if($_SGLOBAL['mobile_type'] == 'myself' )
	{
		//ss_log('show_message2:'.$message);
		
		if($msgkey!='do_success')
			$code = 1;
		else
			$code = 0;
		
		//start add by wangcya , 20130627 ,for bug[533],showmessage返回的时候，不能正确输出前面的xml头
		//add by wangcya , 20130627 ,因为提前退出了，所以前面打印的encoding不能生效，所以在此单独输出
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		//end add by wangcya , 20130627 ,for bug[533],
		
		echo "<result>\n";
		echo "<code> <![CDATA[" .$code. "]]> </code>\n";
		echo "<message> <![CDATA[" .$message. "]]> </message>\n";
		echo "</result>\n";
		
		exit();
	}
	//end add by wangcya, 20130117///////////////////
	
	
	//只
	if(isset($_SGLOBAL['mobile']) && $_SGLOBAL['mobile']) 
	{
		include template('showmessage');
		exit();
	}
	
	
	//示
	if(empty($_SGLOBAL['inajax']) && $url_forward && empty($second)) 
	{
		//ss_log("333333333 ---------will forward login!");
		
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $url_forward");
	} 
	else 
	{
		if(isset($_SGLOBAL['inajax']) && $_SGLOBAL['inajax']) 
		{
			if($url_forward) 
			{
				$message = "<a href=\"$url_forward\">$message</a><ajaxok>";
			}
			//$message = "<h1>".$_SGLOBAL['msglang']['box_title']."</h1><a href=\"javascript:;\" onclick=\"hideMenu();\" class=\"float_del\">X</a><div class=\"popupmenu_inner\">$message</div>";
			echo $message;
			ob_out();
		} 
		else 
		{
			if($url_forward) 
			{
				$message = "<a href=\"$url_forward\">$message</a><script>setTimeout(\"window.location.href ='$url_forward';\", ".($second*1000).");</script>";
			}
			
			include template('showmessage');
		}
	}
	exit();
}

//卸峤磺啡�
function submitcheck($var) 
{
	global $_SGLOBAL;
	//add by dingchaoyang 2014-11-20
	//如果来自移动端，直接退出
	$ROOT_PATH_= str_replace ( 'baoxian/source/function_common.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
	include_once ($ROOT_PATH_ . 'api/EBaoApp/platformEnvironment.class.php');
	if (PlatformEnvironment::isMobilePlatform()){
		ss_log(__FUNCTION__.",111111111111111");
		return TRUE;
	}
	//end by dingchaoyang 2014-11-20	
	
	//ss_log("mobile_type: ".$_SGLOBAL['mobile_type']);
	ss_log("REQUEST_METHOD: ".$_SERVER['REQUEST_METHOD']);
	
	
	if(!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST') 
	{
		ss_log(__FUNCTION__.",2222222222222");
		
		if($_SGLOBAL['mobile_type'] == 'myself')
		{//add by wangcya ,20121012,

			//ss_log("will here: ".$_POST['formhash']);
			//如果带有空格，则歇菜了
			if( trim($_POST['formhash']) == '5a3e6ae2')
			{
				ss_log(__FUNCTION__.",333333333333333333");
				//ss_log("return true");
				return true;
			}
			else
			{
				//ss_log("return false");
				ss_log(__FUNCTION__.",444444444444444444444");
				return false;
			}
		}
		else
		{
			//showmessage('in web');
			if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) && $_POST['formhash'] == formhash()) 
			{
				ss_log(__FUNCTION__.",5555555555555555555");
				return true;
			}
			 else
			 {
			 	//ss_log("submit_invalid");
				showmessage('web:'.submit_invalid);
			}
		}
	}
	else
	{
		ss_log(__FUNCTION__.",66666666666666666666");
		return false;
	}
}


//
function inserttable($tablename, $insertsqlarr, $returnid=0, $replace = false, $silent=0) {
	global $_SGLOBAL;

	$insertkeysql = $insertvaluesql = $comma = '';
	foreach ($insertsqlarr as $insert_key => $insert_value) {
		$insertkeysql .= $comma.'`'.$insert_key.'`';
		$insertvaluesql .= $comma.'\''.$insert_value.'\'';
		$comma = ', ';
	}
	$method = $replace?'REPLACE':'INSERT';
	
	$sql = $method.' INTO '.tname($tablename).' ('.$insertkeysql.') VALUES ('.$insertvaluesql.')';
	//ss_log($sql);
	
	$_SGLOBAL['db']->query($sql, $silent?'SILENT':'');
	

	if($returnid && !$replace) 
	{
		return $_SGLOBAL['db']->insert_id();
	}
}

//
function bx_inserttable($tablename, $insertsqlarr, $returnid=0, $replace = false, $silent=0) {
	global $_SGLOBAL;

	$insertkeysql = $insertvaluesql = $comma = '';
	foreach ($insertsqlarr as $insert_key => $insert_value) {
		$insertkeysql .= $comma.'`'.$insert_key.'`';
		$insertvaluesql .= $comma.'\''.$insert_value.'\'';
		$comma = ', ';
	}
	$method = $replace?'REPLACE':'INSERT';

	$sql = $method.' INTO '.bx_tname($tablename).' ('.$insertkeysql.') VALUES ('.$insertvaluesql.')';

	//ss_log($sql);

	$_SGLOBAL['db']->query($sql, $silent?'SILENT':'');


	if($returnid && !$replace)
	{
		return $_SGLOBAL['db']->insert_id();
	}
}


//
function updatetable($tablename, $setsqlarr, $wheresqlarr, $silent=0) {
	global $_SGLOBAL;

	$setsql = $comma = '';
	foreach ($setsqlarr as $set_key => $set_value) {
		if(is_array($set_value)) {
			$setsql .= $comma.'`'.$set_key.'`'.'='.$set_value[0];
		} else {
			$setsql .= $comma.'`'.$set_key.'`'.'=\''.$set_value.'\'';
		}
		$comma = ', ';
	}
	$where = $comma = '';
	if(empty($wheresqlarr)) {
		$where = '1';
	} elseif(is_array($wheresqlarr)) {
		foreach ($wheresqlarr as $key => $value) {
			$where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
			$comma = ' AND ';
		}
	} else {
		$where = $wheresqlarr;
	}
	
	$sql = 'UPDATE '.tname($tablename).' SET '.$setsql.' WHERE '.$where;
	
	//ss_log($sql);
	$_SGLOBAL['db']->query($sql, $silent?'SILENT':'');
}

//取没占息
function getspace($key, $indextype='uid', $auto_open=0) 
{
	global $_SGLOBAL, $_SCONFIG, $_SN,$_UPDATETIME;
	//////////////////////////////////////////////////////
	//start add by wangcya , 20130801,for bug[569] 	按照space没办法区分自己是医生还是患者
	$myuid = $_SGLOBAL['supe_uid'];
	$query = $_SGLOBAL['db']->query("SELECT sf.*, s.* FROM ".tname('space')." s LEFT JOIN ".tname('spacefield')." sf ON sf.uid=s.uid 
			 WHERE s.uid='$myuid'");
	if($myspace = $_SGLOBAL['db']->fetch_array($query))
	{
		
	}
	//end add by wangcya , 20130801,for bug[569] 	按照space没办法区分自己是医生还是患者
	//////////////////////////////////////////////////////

	$var = "space_{$key}_{$indextype}";
	if(empty($_SGLOBAL[$var])) 
	{
		$space = array();
		$query = $_SGLOBAL['db']->query("SELECT sf.*, s.* FROM ".tname('space')." s LEFT JOIN ".tname('spacefield')." sf ON sf.uid=s.uid WHERE s.{$indextype}='$key'");
		if(!$space = $_SGLOBAL['db']->fetch_array($query)) 
		{
			$space = array();
			if($indextype=='uid' && $auto_open) {
				//远通占
				include_once(S_ROOT.'./uc_client/client.php');
				if($user = uc_get_user($key, 1)) {
					include_once(S_ROOT.'./source/function_space.php');
					$space = space_open($user[0], addslashes($user[1]), 0, addslashes($user[2]));//if modify,wangcya ,20120822
				}
			}
		}
		if($space) 
		{	
			//start add by wangcya, 20120822/////////////

			$_UPDATETIME[$space['uid']] = $space['updatetime'];//add by wangcya,20121221, for bug[198]
				
			$_SN[$space['uid']] = ($_SCONFIG['realname'] && $space['name'] && $space['namestatus'])?$space['name']:$space['username'];
			$space['self'] = ($space['uid']==$_SGLOBAL['supe_uid'])?1:0;//占UID捅士占UID同证约wangchengyuan

			//鸦
			$space['friends'] = array();
			if(empty($space['friend'])) 
			{
				if($space['friendnum']>0) 
				{
					
					$fstr = $fmod = '';
					$query = $_SGLOBAL['db']->query("SELECT fuid FROM ".tname('friend')." WHERE uid='$space[uid]' AND status='1'");
					while ($value = $_SGLOBAL['db']->fetch_array($query)) {
						$space['friends'][] = $value['fuid'];//FUID占friendswangchengyuan
						$fstr .= $fmod.$value['fuid'];
						$fmod = ',';
					}
					$space['friend'] = $fstr;
				}
				
				
			} 
			else 
			{
				$space['friends'] = explode(',', $space['friend']);
			}
			///////////add by wangcya,20121125,fans//////////////////////////
			//fans
			$space['fans'] = array();
			if(empty($space['fan']))
			{
				if($space['fansnum']>0)
				{
					
					$fstr = $fmod = '';
					$query = $_SGLOBAL['db']->query("SELECT uid FROM ".tname('follow')." WHERE fuid='$space[uid]' AND status='1'");
					while ($value = $_SGLOBAL['db']->fetch_array($query)) 
					{
						$space['fans'][] = $value['uid'];//FUID占friendswangchengyuan
						$fstr .= $fmod.$value['uid'];
						$fmod = ',';
					}
					$space['fan'] = $fstr;
					
					//showmessage($space['fan']);
				}
				else
				{
					//showmessage("not ffffffffff");
				}
			}
			else
			{
				$space['fans'] = explode(',', $space['fan']);
			}
			///////////end by wangcya,20121125,fans//////////////////////////

			$space['username'] = addslashes($space['username']);
			$space['name'] = addslashes($space['name']);
			
			//谢谢取说私通占时些耍comment by wangcya ,20120827
			$space['privacy'] = empty($space['privacy'])?(empty($_SCONFIG['privacy'])?array():$_SCONFIG['privacy']):unserialize($space['privacy']);

			//通知
			//把所有的总的值加起来。
			$space['allnotenum'] = 0;
			foreach (array('notenum','pokenum','addfriendnum','mtaginvitenum','eventinvitenum','myinvitenum') as $value) 
			{
				$space['allnotenum'] = $space['allnotenum'] + $space[$value];
			}
			
			/*
			//start add b wangcya , 20131225, for bug[905] 	增加对问答中动作的通知功能
			include_once(S_ROOT.'./source/function_notification.php');
			$qcount = notification_get_match_condition_count();
			if($qcount)
			{
				$space['allnotenum'] = $space['allnotenum'] + $qcount;
			}
			//end add b wangcya , 20131225, for bug[905] 	增加对问答中动作的通知功能
			*/
			
			if($space['self']) {
				$_SGLOBAL['member'] = $space;
			}
		}
		$_SGLOBAL[$var] = $space;
	}
	return $_SGLOBAL[$var];
}

//没UID
function getuid($name) {
	global $_SGLOBAL, $_SCONFIG;

	$wherearr[] = "(username='$name')";
	if($_SCONFIG['realname']) {
		$wherearr[] = "(name='$name' AND namestatus = 1)";
	}
	$uid = 0;
	$query = $_SGLOBAL['db']->query("SELECT uid,username,name,namestatus FROM ".tname('space')." WHERE ".implode(' OR ', $wherearr)." LIMIT 1");
	if($space = $_SGLOBAL['db']->fetch_array($query)) {
		$uid = $space['uid'];
	}
	return $uid;
}

//取前没息
function getmember() {
	global $_SGLOBAL, $space;

	if(empty($_SGLOBAL['member']) && $_SGLOBAL['supe_uid']) {
		if($space['uid'] == $_SGLOBAL['supe_uid']) {
			$_SGLOBAL['member'] = $space;
		} else {
			$_SGLOBAL['member'] = getspace($_SGLOBAL['supe_uid']);
		}
	}
}

//玫私私comment by wangcya ,
function ckprivacy($type, $feedmode=0)
{
	global $_SGLOBAL, $space, $_SCONFIG;

	$var = "ckprivacy_{$type}_{$feedmode}";
	if(isset($_SGLOBAL[$var])) 
	{
		return $_SGLOBAL[$var];
	}
	$result = false;
	if($feedmode) 
	{//feed堑选只一值涂伞
		if($type == 'spaceopen') 
		{
			if(!empty($_SCONFIG['privacy']['feed'][$type])) 
			{
				$result = true;
			}
		} 
		elseif(!empty($space['privacy']['feed'][$type])) 
		{
			$result = true;
		}
	}
	elseif($space['self'])//约约桑wangchengyuan
	{
		//约
		$result = true;
	} 
	else 
	{
		if(empty($space['privacy']['view'][$type])) 
		{
			$result = true;
		}
		//前 为盏前拢糯妫瑆angchengyuan
		if(!$result && $space['privacy']['view'][$type] == 1) //芽杉wangchengyuan
		{
			//欠
			if(!isset($space['isfriend'])) //没斜茫wangchengyuan
			{
				$space['isfriend'] = $space['self'];
				if($space['friends'] && in_array($_SGLOBAL['supe_uid'], $space['friends'])) 
				{//诤斜校wangchengyuan
					$space['isfriend'] = 1;//呛
				}
			}
			if($space['isfriend']) 
			{
				$result = true;
			}
		}
	}
	$_SGLOBAL[$var] = $result;//前页婊�
	return $result;
}

//APP私
function app_ckprivacy($privacy) {
	global $_SGLOBAL, $space;

	$var = "app_ckprivacy_{$privacy}";
	if(isset($_SGLOBAL[$var])) {
		return $_SGLOBAL[$var];
	}
	$result = false;
	switch ($privacy) {
		case 0://
			$result = true;
			break;
		case 1://
			if(!isset($space['isfriend'])) {
				$space['isfriend'] = $space['self'];
				if($space['friends'] && in_array($_SGLOBAL['supe_uid'], $space['friends'])) {
					$space['isfriend'] = 1;//呛
				}
			}
			if($space['isfriend']) {
				$result = true;
			}
			break;
		case 2://趾
			break;
		case 3://约
			if($space['self']) {
				$result = true;
			}
			break;
		case 4://
			break;
		case 5://没
			break;
		default:
			$result = true;
			break;
	}
	$_SGLOBAL[$var] = $result;
	return $result;
}

//取没
function getgroupid($experience, $gid=0) {
	global $_SGLOBAL;

	$needfind = false;
	if($gid) {
		if(@include_once(S_ROOT.'./data/data_usergroup_'.$gid.'.php')) {
			$group = $_SGLOBAL['usergroup'][$gid];
			if(empty($group['system'])) {
				if($group['exphigher']<$experience || $group['explower']>$experience) {
					$needfind = true;
				}
			}
		}
	} else {
		$needfind = true;
	}
	if($needfind) {
		$query = $_SGLOBAL['db']->query("SELECT gid FROM ".tname('usergroup')." WHERE explower<='$experience' AND system='0' ORDER BY explower DESC LIMIT 1");
		$gid = $_SGLOBAL['db']->result($query, 0);
	}
	return $gid;
}

//权
function checkperm($permtype) {
	global $_SGLOBAL, $space;
	
	return true;//add by wangcya , 20140802

	if($permtype == 'admin') 
		$permtype = 'manageconfig';

	$var = 'checkperm_'.$permtype;
	if(!isset($_SGLOBAL[$var])) 
	{
		if(empty($_SGLOBAL['supe_uid'])) 
		{
			$_SGLOBAL[$var] = '';
		} 
		else 
		{
			if(empty($_SGLOBAL['member'])) getmember();
			$gid = getgroupid($_SGLOBAL['member']['experience'], $_SGLOBAL['member']['groupid']);
			@include_once(S_ROOT.'./data/data_usergroup_'.$gid.'.php');

			if($gid != $_SGLOBAL['member']['groupid']) 
			{
				updatetable('space', array('groupid'=>$gid), array('uid'=>$_SGLOBAL['supe_uid']));
				//偷
				if($_SGLOBAL['usergroup'][$gid]['magicaward']) 
				{
					include_once(S_ROOT.'./source/inc_magicaward.php');
				}
			}
			
			$_SGLOBAL[$var] = empty($_SGLOBAL['usergroup'][$gid][$permtype])?'':$_SGLOBAL['usergroup'][$gid][$permtype];
			if(substr($permtype, 0, 6) == 'manage' && empty($_SGLOBAL[$var])) 
			{
				$_SGLOBAL[$var] = $_SGLOBAL['usergroup'][$gid]['manageconfig'];//权薷
				if(empty($_SGLOBAL[$var])) 
				{
					$_SGLOBAL[$var] = ckfounder($_SGLOBAL['supe_uid'])?1:0;//始
				}
			}
		}
	}
	return $_SGLOBAL[$var];
}

//写志
function runlog($file, $log, $halt=0) {
	global $_SGLOBAL, $_SERVER;

	$nowurl = $_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:($_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME']);
	$log = sgmdate('Y-m-d H:i:s', $_SGLOBAL['timestamp'])."\t$type\t".getonlineip()."\t$_SGLOBAL[supe_uid]\t{$nowurl}\t".str_replace(array("\r", "\n"), array(' ', ' '), trim($log))."\n";
	$yearmonth = sgmdate('Ym', $_SGLOBAL['timestamp']);
	$logdir = './data/log/';
	if(!is_dir($logdir)) mkdir($logdir, 0777);
	$logfile = $logdir.$yearmonth.'_'.$file.'.php';
	if(@filesize($logfile) > 2048000) {
		$dir = opendir($logdir);
		$length = strlen($file);
		$maxid = $id = 0;
		while($entry = readdir($dir)) {
			if(strexists($entry, $yearmonth.'_'.$file)) {
				$id = intval(substr($entry, $length + 8, -4));
				$id > $maxid && $maxid = $id;
			}
		}
		closedir($dir);
		$logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($maxid + 1).'.php';
		@rename($logfile, $logfilebak);
	}
	if($fp = @fopen($logfile, 'a')) {
		@flock($fp, 2);
		fwrite($fp, "<?PHP exit;?>\t".str_replace(array('<?', '?>', "\r", "\n"), '', $log)."\n");
		fclose($fp);
	}
	if($halt) exit();
}

//取址
function getstr($string, $length, $in_slashes=0, $out_slashes=0, $censor=0, $bbcode=0, $html=0) {
	global $_SC, $_SGLOBAL;

	$string = trim($string);

	if($in_slashes) 
	{
		//址slashes
		$string = sstripslashes($string);
	}
	
	if($html < 0) 
	{
		//去html签
		$string = preg_replace("/(\<[^\<]*\>|\r|\n|\s|\[.+?\])/is", ' ', $string);
		$string = shtmlspecialchars($string);
	} 
	elseif ($html == 0) 
	{
		//转html签
		$string = shtmlspecialchars($string);
	}
	
	if($censor) 
	{
		//
		@include_once(S_ROOT.'./data/data_censor.php');
		if($_SGLOBAL['censor']['banned'] && preg_match($_SGLOBAL['censor']['banned'], $string)) 
		{
			showmessage('information_contains_the_shielding_text');
		} 
		else 
		{
			$string = empty($_SGLOBAL['censor']['filter']) ? $string :
				@preg_replace($_SGLOBAL['censor']['filter']['find'], $_SGLOBAL['censor']['filter']['replace'], $string);
		}
	}
	if($length && strlen($string) > $length) {
		//囟址
		$wordscut = '';
		if(strtolower($_SC['charset']) == 'utf-8') {
			//utf8
			$n = 0;
			$tn = 0;
			$noc = 0;
			while ($n < strlen($string)) {
				$t = ord($string[$n]);
				if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
					$tn = 1;
					$n++;
					$noc++;
				} elseif(194 <= $t && $t <= 223) {
					$tn = 2;
					$n += 2;
					$noc += 2;
				} elseif(224 <= $t && $t < 239) {
					$tn = 3;
					$n += 3;
					$noc += 2;
				} elseif(240 <= $t && $t <= 247) {
					$tn = 4;
					$n += 4;
					$noc += 2;
				} elseif(248 <= $t && $t <= 251) {
					$tn = 5;
					$n += 5;
					$noc += 2;
				} elseif($t == 252 || $t == 253) {
					$tn = 6;
					$n += 6;
					$noc += 2;
				} else {
					$n++;
				}
				if ($noc >= $length) {
					break;
				}
			}
			if ($noc > $length) {
				$n -= $tn;
			}
			$wordscut = substr($string, 0, $n);
		} else {
			for($i = 0; $i < $length - 1; $i++) {
				if(ord($string[$i]) > 127) {
					$wordscut .= $string[$i].$string[$i + 1];
					$i++;
				} else {
					$wordscut .= $string[$i];
				}
			}
		}
		$string = $wordscut;
	}
	if($bbcode) {
		include_once(S_ROOT.'./source/function_bbcode.php');
		$string = bbcode($string, $bbcode);
	}
	if($out_slashes) {
		$string = saddslashes($string);
	}
	return trim($string);
}

//时式
function sgmdate($dateformat, $timestamp='', $format=0) {
	global $_SCONFIG, $_SGLOBAL;
	if(empty($timestamp)) {
		$timestamp = $_SGLOBAL['timestamp'];
	}
	$timeoffset = strlen($_SGLOBAL['member']['timeoffset'])>0?intval($_SGLOBAL['member']['timeoffset']):intval($_SCONFIG['timeoffset']);
	$result = '';
	if($format) {
		$time = $_SGLOBAL['timestamp'] - $timestamp;
		if($time > 24*3600) {
			$result = gmdate($dateformat, $timestamp + $timeoffset * 3600);
		} elseif ($time > 3600) {
			$result = intval($time/3600).lang('hour').lang('before');
		} elseif ($time > 60) {
			$result = intval($time/60).lang('minute').lang('before');
		} elseif ($time > 0) {
			$result = $time.lang('second').lang('before');
		} else {
			$result = lang('now');
		}
	} else {
		$result = gmdate($dateformat, $timestamp + $timeoffset * 3600);
	}
	return $result;
}

//址时浠�
function sstrtotime($string) {
	global $_SGLOBAL, $_SCONFIG;
	$time = '';
	if($string) {
		$time = strtotime($string);
		if(gmdate('H:i', $_SGLOBAL['timestamp'] + $_SCONFIG['timeoffset'] * 3600) != date('H:i', $_SGLOBAL['timestamp'])) {
			$time = $time - $_SCONFIG['timeoffset'] * 3600;
		}
	}
	return $time;
}

//页
function multi($num, $perpage, $curpage, $mpurl, $ajaxdiv='', $todiv='') 
{
	global $_SCONFIG, $_SGLOBAL;

	if(empty($ajaxdiv) && $_SGLOBAL['inajax']) {
		$ajaxdiv = $_GET['ajaxdiv'];
	}

	//ss_log($mpurl);
	
	$page = 5;
	if($_SGLOBAL['showpage']) 
		$page = $_SGLOBAL['showpage'];

	$multipage = '';
	$mpurl .= strpos($mpurl, '?') ? '&' : '?';
	$realpages = 1;
	if($num > $perpage) {
		$offset = 2;
		$realpages = @ceil($num / $perpage);
		$pages = $_SCONFIG['maxpage'] && $_SCONFIG['maxpage'] < $realpages ? $_SCONFIG['maxpage'] : $realpages;
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		$multipage = '';
		$urlplus = $todiv?"#$todiv":'';
		if($curpage - $offset > 1 && $pages > $page) {
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=1&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=1{$urlplus}\"";
			}
			$multipage .= " class=\"first\">1 ...</a>";
		}
		if($curpage > 1) {
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=".($curpage-1)."&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=".($curpage-1)."$urlplus\"";
			}
			$multipage .= " class=\"prev\">&lsaquo;&lsaquo;</a>";
		}
		for($i = $from; $i <= $to; $i++) {
			if($i == $curpage) {
				$multipage .= '<strong>'.$i.'</strong>';
			} else {
				$multipage .= "<a ";
				if($_SGLOBAL['inajax']) {
					$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=$i&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
				} else {
					$multipage .= "href=\"{$mpurl}page=$i{$urlplus}\"";
				}
				$multipage .= ">$i</a>";
			}
		}
		if($curpage < $pages) {
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=".($curpage+1)."&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=".($curpage+1)."{$urlplus}\"";
			}
			$multipage .= " class=\"next\">&rsaquo;&rsaquo;</a>";
		}
		if($to < $pages) {
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=$pages&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=$pages{$urlplus}\"";
			}
			$multipage .= " class=\"last\">... $realpages</a>";
		}
		if($multipage) {
			$multipage = '<em>&nbsp;'.$num.'&nbsp;</em>'.$multipage;
		}
	}
	
	//ss_log($multipage);
	
	return $multipage;
}


//页
function smulti($start, $perpage, $count, $url, $ajaxdiv='') {
	global $_SGLOBAL;

	$multi = array('last'=>-1, 'next'=>-1, 'begin'=>-1, 'end'=>-1, 'html'=>'');
	if($start > 0) {
		if(empty($count)) {
			showmessage('no_data_pages');
		} else {
			$multi['last'] = $start - $perpage;
		}
	}

	$showhtml = 0;
	if($count == $perpage) {
		$multi['next'] = $start + $perpage;
	}
	$multi['begin'] = $start + 1;
	$multi['end'] = $start + $count;

	if($multi['begin'] >= 0) {
		if($multi['last'] >= 0) {
			$showhtml = 1;
			if($_SGLOBAL['inajax']) {
				$multi['html'] .= "<a href=\"javascript:;\" onclick=\"ajaxget('$url&ajaxdiv=$ajaxdiv', '$ajaxdiv')\">|&lt;</a> <a href=\"javascript:;\" onclick=\"ajaxget('$url&start=$multi[last]&ajaxdiv=$ajaxdiv', '$ajaxdiv')\">&lt;</a> ";
			} else {
				$multi['html'] .= "<a href=\"$url\">|&lt;</a> <a href=\"$url&start=$multi[last]\">&lt;</a> ";
			}
		} else {
			$multi['html'] .= "&lt;";
		}
		$multi['html'] .= " $multi[begin]~$multi[end] ";
		if($multi['next'] >= 0) {
			$showhtml = 1;
			if($_SGLOBAL['inajax']) {
				$multi['html'] .= " <a href=\"javascript:;\" onclick=\"ajaxget('$url&start=$multi[next]&ajaxdiv=$ajaxdiv', '$ajaxdiv')\">&gt;</a> ";
			} else {
				$multi['html'] .= " <a href=\"$url&start=$multi[next]\">&gt;</a>";
			}
		} else {
			$multi['html'] .= " &gt;";
		}
	}

	return $showhtml?$multi['html']:'';
}

//ob
function obclean() {
	global $_SC;

	ob_end_clean();
	if ($_SC['gzipcompress'] && function_exists('ob_gzhandler')) {
		ob_start('ob_gzhandler');
	} else {
		ob_start();
	}
}

//模
function template($name) {
	global $_SCONFIG, $_SGLOBAL;

	if(isset($_SGLOBAL['mobile']) && $_SGLOBAL['mobile']) {
		$objfile = S_ROOT.'./api/mobile/tpl_'.$name.'.php';
		if (!file_exists($objfile)) {
			showmessage('m_function_is_disable_on_wap');
		}
	} 
	else 
	{
		if(strexists($name,'/')) 
		{
			$tpl = $name;
		} 
		elseif($_SGLOBAL['mobile_type'] != 'pcweb')
		{
			ss_log("will process mobile html");
			
			if(!isset($_SCONFIG['template']))
			{
				$_SCONFIG['template'] = '';
			}
			
			
			if(isset($_SCONFIG['template']) && $_SCONFIG['template'] )
			{
				$tpl = "template/$_SCONFIG[template]/mobile/$name";
			}
			else
			{
				$tpl = "template/mobile/$name";
			}
			ss_log("in function template,tpl: ".$tpl);
			
			$objfile = S_ROOT.'./data/tpl_cache/mobile/'.str_replace('/','_',$tpl).'.php';
			
			//ss_log("objfile: ".$objfile);
		}
		else 
		{
			$tpl = "template/$_SCONFIG[template]/$name";
			
			//$tpl = "template/default/$name";
			$objfile = S_ROOT.'data/tpl_cache/'.str_replace('/','_',$tpl).'.php';
		}
		
	
		

		
		//if(!file_exists($objfile)) { //del by wangcya ,20120812,for bug[573]	现在调试阶段，关闭了缓存功能. 调试程序时候，为了使缓存失效
			include_once(S_ROOT.'source/function_template.php');
			parse_template($tpl);
		//}//del by wangcya ,20120812
	}
	
	//ss_log("objfile: ".$objfile);
	
	return $objfile;
	
}

//模录
function subtplcheck($subfiles, $mktime, $tpl) {
	global $_SC, $_SCONFIG;

	if($_SC['tplrefresh'] && ($_SC['tplrefresh'] == 1 || mt_rand(1, $_SC['tplrefresh']) == 1)) {
		$subfiles = explode('|', $subfiles);
		foreach ($subfiles as $subfile) {
			$tplfile = S_ROOT.'./'.$subfile.'.htm';
			if(!file_exists($tplfile)) {
				$tplfile = str_replace('/'.$_SCONFIG['template'].'/', '/default/', $tplfile);
			}
			@$submktime = filemtime($tplfile);
			if($submktime > $mktime) {
				include_once(S_ROOT.'./source/function_template.php');
				parse_template($tpl);
				break;
			}
		}
	}
}

//模
function block($param) {
	global $_SBLOCK;

	include_once(S_ROOT.'./source/function_block.php');
	block_batch($param);
}

//取目
function getcount($tablename, $wherearr=array(), $get='COUNT(*)') {
	global $_SGLOBAL;
	if(empty($wherearr)) {
		$wheresql = '1';
	} else {
		$wheresql = $mod = '';
		foreach ($wherearr as $key => $value) {
			$wheresql .= $mod."`$key`='$value'";
			$mod = ' AND ';
		}
	}
	$sql = "SELECT $get FROM ".tname($tablename)." WHERE $wheresql LIMIT 1";
	//showmessage($sql);
	//ss_log($sql);
	return $_SGLOBAL['db']->result($_SGLOBAL['db']->query($sql), 0);
}

//
function ob_out() {
	global $_SGLOBAL, $_SCONFIG, $_SC;
	
	$content = ob_get_contents();

	$preg_searchs = $preg_replaces = $str_searchs = $str_replaces = array();

	if($_SCONFIG['allowrewrite']) {
		$preg_searchs[] = "/\<a href\=\"space\.php\?(uid|do)+\=([a-z0-9\=\&]+?)\"/ie";
		$preg_searchs[] = "/\<a href\=\"space.php\"/i";
		$preg_searchs[] = "/\<a href\=\"network\.php\?ac\=([a-z0-9\=\&]+?)\"/ie";
		$preg_searchs[] = "/\<a href\=\"network.php\"/i";

		$preg_replaces[] = 'rewrite_url(\'space-\',\'\\2\')';
		$preg_replaces[] = '<a href="space.html"';
		$preg_replaces[] = 'rewrite_url(\'network-\',\'\\1\')';
		$preg_replaces[] = '<a href="network.html"';
	}
	if($_SCONFIG['linkguide']) {
		$preg_searchs[] = "/\<a href\=\"http\:\/\/(.+?)\"/ie";
		$preg_replaces[] = 'iframe_url(\'\\1\')';
	}

	if($_SGLOBAL['inajax']) {
		$preg_searchs[] = "/([\x01-\x09\x0b-\x0c\x0e-\x1f])+/";
		$preg_replaces[] = ' ';

		$str_searchs[] = ']]>';
		$str_replaces[] = ']]&gt;';
	}

	if($preg_searchs) {
		$content = preg_replace($preg_searchs, $preg_replaces, $content);
	}
	if($str_searchs) {
		$content = trim(str_replace($str_searchs, $str_replaces, $content));
	}

	obclean();
	if(isset($_SGLOBAL['inajax']) && $_SGLOBAL['inajax']) {
		xml_out($content);
	} else{
		if(isset($_SCONFIG['headercharset']) && ($_SCONFIG['headercharset'])) {
			@header('Content-Type: text/html; charset='.$_SC['charset']);
		}
		echo $content;
		if(D_BUG) {
			@include_once(S_ROOT.'./source/inc_debug.php');
		}
	}
}

function xml_out($content) {
	global $_SC;
	@header("Expires: -1");
	@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
	@header("Pragma: no-cache");
	@header("Content-type: application/xml; charset=$_SC[charset]");
	echo '<'."?xml version=\"1.0\" encoding=\"$_SC[charset]\"?>\n";
	echo "<root><![CDATA[".trim($content)."]]></root>";
	exit();
}

//rewrite
function rewrite_url($pre, $para) {
	$para = str_replace(array('&','='), array('-', '-'), $para);
	return '<a href="'.$pre.$para.'.html"';
}

//
function iframe_url($url) {
	$url = rawurlencode($url);
	return "<a href=\"link.php?url=http://$url\"";
}


//
function cksearch($theurl) {
	global $_SGLOBAL, $_SCONFIG, $space;
	
	$theurl = stripslashes($theurl)."&page=".$_GET['page'];
	if($searchinterval = checkperm('searchinterval')) {
		$waittime = $searchinterval - ($_SGLOBAL['timestamp'] - $space['lastsearch']);
		if($waittime > 0) {
			showmessage('search_short_interval', '', 1, array($waittime, $theurl));
		}
	}
	if(!checkperm('searchignore')) {
		$reward = getreward('search', 0);
		if($reward['credit'] || $reward['experience']) {
			if(empty($_GET['confirm'])) {
				$theurl .= '&confirm=yes';
				showmessage('points_deducted_yes_or_no', '', 1, array($reward['credit'], $reward['experience'], $theurl));
			} else {
				if($space['credit'] < $reward['credit'] || $space['experience'] < $reward['experience']) {
					showmessage('points_search_error');
				} else {
					//鄯
					$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET lastsearch='$_SGLOBAL[timestamp]', credit=credit-$reward[credit], experience=experience-$reward[experience] WHERE uid='$_SGLOBAL[supe_uid]'");
				}
			}
		}
	}
}

//欠味
function isholddomain($domain) {
	global $_SCONFIG;

	$domain = strtolower($domain);

	if(preg_match("/^[^a-z]/i", $domain)) return true;
	$holdmainarr = empty($_SCONFIG['holddomain'])?array('www'):explode('|', $_SCONFIG['holddomain']);
	$ishold = false;
	foreach ($holdmainarr as $value) {
		if(strpos($value, '*') === false) {
			if(strtolower($value) == $domain) {
				$ishold = true;
				break;
			}
		} else {
			$value = str_replace('*', '', $value);
			if(@preg_match("/$value/i", $domain)) {
				$ishold = true;
				break;
			}
		}
	}
	return $ishold;
}

//址
function simplode($ids) {
	return "'".implode("','", $ids)."'";
}

//示檀时
function debuginfo() {
	global $_SGLOBAL, $_SC, $_SCONFIG;

	if(empty($_SCONFIG['debuginfo'])) {
		$info = '';
	} else {
		$mtime = explode(' ', microtime());
		$totaltime = number_format(($mtime[1] + $mtime[0] - $_SGLOBAL['supe_starttime']), 4);
		$info = 'Processed in '.$totaltime.' second(s), '.$_SGLOBAL['db']->querynum.' queries'.
				($_SC['gzipcompress'] ? ', Gzip enabled' : NULL);
	}

	return $info;
}

//式小
function formatsize($size) {
	$prec=3;
	$size = round(abs($size));
	$units = array(0=>" B ", 1=>" KB", 2=>" MB", 3=>" GB", 4=>" TB");
	if ($size==0) return str_repeat(" ", $prec)."0$units[0]";
	$unit = min(4, floor(log($size)/log(2)/10));
	$size = $size * pow(2, -10*$unit);
	$digi = $prec - 1 - floor(log($size)/log(10));
	$size = round($size * pow(10, $digi)) * pow(10, -$digi);
	return $size.$units[$unit];
}

//取募
function sreadfile($filename) {
	$content = '';
	if(function_exists('file_get_contents')) {
		@$content = file_get_contents($filename);
	} else {
		if(@$fp = fopen($filename, 'r')) {
			@$content = fread($fp, filesize($filename));
			@fclose($fp);
		}
	}
	return $content;
}

//写募
function swritefile($filename, $writetext, $openmod='w') {
	if(@$fp = fopen($filename, $openmod)) {
		flock($fp, 2);
		fwrite($fp, $writetext);
		fclose($fp);
		return true;
	} else {
		runlog('error', "File: $filename write error.");
		return false;
	}
}

//址
function random($length, $numeric = 0) {
	PHP_VERSION < '4.2.0' ? mt_srand((double)microtime() * 1000000) : mt_srand();
	$seed = base_convert(md5(print_r($_SERVER, 1).microtime()), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed[mt_rand(0, $max)];
	}
	return $hash;
}

//卸址欠
function strexists($haystack, $needle) {
	return !(strpos($haystack, $needle) === FALSE);
}

//取
function data_get($var, $isarray=0) {
	global $_SGLOBAL;

	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('data')." WHERE var='$var' LIMIT 1");
	if($value = $_SGLOBAL['db']->fetch_array($query)) {
		return $isarray?$value:$value['datavalue'];
	} else {
		return '';
	}
}

//
function data_set($var, $datavalue, $clean=0) {
	global $_SGLOBAL;

	if($clean) {
		$_SGLOBAL['db']->query("DELETE FROM ".tname('data')." WHERE var='$var'");
	} else {
		if(is_array($datavalue)) $datavalue = serialize(sstripslashes($datavalue));
		$_SGLOBAL['db']->query("REPLACE INTO ".tname('data')." (var, datavalue, dateline) VALUES ('$var', '".addslashes($datavalue)."', '$_SGLOBAL[timestamp]')");
	}
}

//站欠乇
function checkclose() {
	global $_SGLOBAL, $_SCONFIG;

	//站乇
	if($_SCONFIG['close'] && !ckfounder($_SGLOBAL['supe_uid']) && !checkperm('closeignore')) {
		if(empty($_SCONFIG['closereason'])) {
			showmessage('site_temporarily_closed');
		} else {
			showmessage($_SCONFIG['closereason']);
		}
	}
	//IP始
	if((!ipaccess($_SCONFIG['ipaccess']) || ipbanned($_SCONFIG['ipbanned'])) && !ckfounder($_SGLOBAL['supe_uid']) && !checkperm('closeignore')) {
		showmessage('ip_is_not_allowed_to_visit');
	}
}

//站
function getsiteurl() {
	global $_SCONFIG;

	if(empty($_SCONFIG['siteallurl'])) {
		$uri = $_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:($_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME']);
		return shtmlspecialchars('http://'.$_SERVER['HTTP_HOST'].substr($uri, 0, strrpos($uri, '/')+1));
	} else {
		return $_SCONFIG['siteallurl'];
	}
}

//取募缀
function fileext($filename) {
	return strtolower(trim(substr(strrchr($filename, '.'), 1)));
}

//去slassh
function sstripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = sstripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}

//示
function adshow($pagetype) {
	global $_SGLOBAL;

	@include_once(S_ROOT.'./data/data_ad.php');
	if(empty($_SGLOBAL['ad']) || empty($_SGLOBAL['ad'][$pagetype])) return false;
	$ads = $_SGLOBAL['ad'][$pagetype];
	$key = mt_rand(0, count($ads)-1);
	$id = $ads[$key];
	$file = S_ROOT.'./data/adtpl/'.$id.'.htm';
	echo sreadfile($file);
}

//转
function siconv($str, $out_charset, $in_charset='') {
	global $_SC;

	$in_charset = empty($in_charset)?strtoupper($_SC['charset']):strtoupper($in_charset);
	$out_charset = strtoupper($out_charset);
	if($in_charset != $out_charset) {
		if (function_exists('iconv') && (@$outstr = iconv("$in_charset//IGNORE", "$out_charset//IGNORE", $str))) {
			return $outstr;
		} elseif (function_exists('mb_convert_encoding') && (@$outstr = mb_convert_encoding($str, $out_charset, $in_charset))) {
			return $outstr;
		}
	}
	return $str;//转失
}

//取没
function getpassport($username, $password) {
	global $_SGLOBAL, $_SC;

	$passport = array();
	if(!@include_once S_ROOT.'./uc_client/client.php') 
	{
		showmessage('system_error');
	}

	
	$ucresult = uc_user_login($username, $password);
	if($ucresult[0] > 0) 
	{
		$passport['uid'] = $ucresult[0];
		$passport['username'] = $ucresult[1];
		$passport['email'] = $ucresult[3];
	}
	return $passport;
}

//没时
function interval_check($type) {
	global $_SGLOBAL, $space;

	return 0;//add by wangcya , 20140804
	
	$intervalname = $type.'interval';
	$lastname = 'last'.$type;

	$waittime = 0;
	if($interval = checkperm($intervalname)) {
		$lasttime = isset($space[$lastname])?$space[$lastname]:getcount('space', array('uid'=>$_SGLOBAL['supe_uid']), $lastname);
		$waittime = $interval - ($_SGLOBAL['timestamp'] - $lasttime);
	}
	return $waittime;
}

//洗图片
function pic_get($filepath, $thumb, $remote, $return_thumb=1) {
	global $_SCONFIG, $_SC;

	if(empty($filepath)) 
	{
		$url = 'image/nopic.gif';
	} 
	else 
	{
		$url = $filepath;
		if($return_thumb && $thumb) 
			$url .= '.thumb.jpg';
		
		if($remote) 
		{
			$url = $_SCONFIG['ftpurl'].$url;
		} 
		else 
		{
			$url = $_SC['attachurl'].$url;
		}
	}

	return $url;
}

//梅图片
function pic_cover_get($pic, $picflag) {
	global $_SCONFIG, $_SC;

	if(empty($pic)) {
		$url = 'image/nopic.gif';
	} else {
		if($picflag == 1) {//
			$url = $_SC['hostpath'].$_SC['attachurl'].$pic;//modify by wangcya, $_SC['hostpath']
		} elseif ($picflag == 2) {//远
			$url = $_SCONFIG['ftpurl'].$pic;
		} else {//
			$url = $pic;
		}
	}

	return $url;
}

//
function getstar($experience) {
	global $_SCONFIG;

	$starimg = '';
	if($_SCONFIG['starcredit'] > 1) {
		//
		$starnum = intval($experience/$_SCONFIG['starcredit']) + 1;
		if($_SCONFIG['starlevelnum'] < 2) {
			if($starnum > 10) $starnum = 10;
			for($i = 0; $i < $starnum; $i++) {
				$starimg .= '<img src="image/star_level10.gif" align="absmiddle" />';
			}
		} else {
			//燃(10)
			for($i = 10; $i > 0; $i--) {
				$numlevel = intval($starnum / pow($_SCONFIG['starlevelnum'], ($i - 1)));
				if($numlevel > 10) $numlevel = 10;
				if($numlevel) {
					for($j = 0; $j < $numlevel; $j++) {
						$starimg .= '<img src="image/star_level'.$i.'.gif" align="absmiddle" />';
					}
					break;
				}
			}
		}
	}
	if(empty($starimg)) $starimg = '<img src="image/credit.gif" alt="'.$experience.'" align="absmiddle" alt="'.$experience.'" title="'.$experience.'" />';
	return $starimg;
}

//取状态
function getfriendstatus($uid, $fuid) {
	global $_SGLOBAL;

	$query = $_SGLOBAL['db']->query("SELECT status FROM ".tname('friend')." WHERE uid='$uid' AND fuid='$fuid' LIMIT 1");
	if($value = $_SGLOBAL['db']->fetch_array($query)) {
		return $value['status'];
	} else {
		return -1;//没屑录
	}
}

//榻�
function renum($array) {
	$newnums = $nums = array();
	foreach ($array as $id => $num) {
		$newnums[$num][] = $id;
		$nums[$num] = $num;
	}
	return array($nums, $newnums);
}

//ip
function ipaccess($ipaccess) {
	return empty($ipaccess)?true:preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($ipaccess, '/')).")/", getonlineip());
}

//ip式止
function ipbanned($ipbanned) {
	return empty($ipbanned)?false:preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($ipbanned, '/')).")/", getonlineip());
}

//start
function ckstart($start, $perpage) {
	global $_SCONFIG;

	$maxstart = $perpage*intval($_SCONFIG['maxpage']);
	if($start < 0 || ($maxstart > 0 && $start >= $maxstart)) {
		showmessage('length_is_not_within_the_scope_of');
	}
}

//头
function avatar($uid, $size='small', $returnsrc = FALSE) 
{
	global $_SCONFIG, $_SN , $_UPDATETIME,$_SGLOBAL;
	
	$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'small';
	
	if($uid)
		$avatarfile = avatar_file($uid, $size);
	else
		$avatarfile = "/image/anonymous.png";
	//start add by wangcya,20121221, for bug[198]
	if($_SGLOBAL['mobile_type'] == 'myself')
	{
		
		if(!$_UPDATETIME[$uid])
		{
			$sql = "SELECT updatetime FROM ".tname('space')." WHERE uid='$uid'";
			
			//ss_log($sql);
			
			$query = $_SGLOBAL['db']->query($sql);
			
			$value = $_SGLOBAL['db']->fetch_array($query);
			$updatetime = $value['updatetime'];
		}
		else
		{
			$updatetime = $_UPDATETIME[$uid];
		}
		
		$ret = $returnsrc ? UC_API.'/data/avatar/'.$avatarfile.'?updatetime='.$updatetime : '<img src="'.UC_API.'/data/avatar/'.$avatarfile.'" onerror="this.onerror=null;this.src=\''.UC_API.'/images/noavatar_'.$size.'.gif\'">';
		
		//showmessage($ret);
		
		return $ret;
	}
	//end add by wangcya,20121221, for bug[198]
	else
	{
		//return $returnsrc ? UC_API.'/data/avatar/'.$avatarfile : '<img src="'.UC_API.'/data/avatar/'.$avatarfile.'" onerror="this.onerror=null;this.src=\''.UC_API.'/images/noavatar_'.$size.'.gif\'">';
		//modify by wangcya , 20130402,
		return $returnsrc ? UC_API.'/data/avatar/'.$avatarfile : '<img src="'.UC_API.'/data/avatar/'.$avatarfile.'" alt="'.$_SN[$uid].'" onerror="this.onerror=null;this.src=\''.UC_API.'/images/noavatar_'.$size.'.gif\'">';
		
	}
	
	
}

//玫头
function avatar_file($uid, $size) {
	global $_SGLOBAL, $_SCONFIG;

	$type = empty($_SCONFIG['avatarreal'])?'virtual':'real';
	$var = "avatarfile_{$uid}_{$size}_{$type}";
	if(empty($_SGLOBAL[$var])) {
		$uid = abs(intval($uid));
		$uid = sprintf("%09d", $uid);
		$dir1 = substr($uid, 0, 3);
		$dir2 = substr($uid, 3, 2);
		$dir3 = substr($uid, 5, 2);
		$typeadd = $type == 'real' ? '_real' : '';
		$_SGLOBAL[$var] = $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).$typeadd."_avatar_$size.jpg";
	}
	return $_SGLOBAL[$var];
}

//欠录
function checklogin() 
{
	global $_SGLOBAL, $_SCONFIG;

	if(empty($_SGLOBAL['supe_uid'])) 
	{
		ssetcookie('_refer', rawurlencode($_SERVER['REQUEST_URI']));

		//ss_log('in function checklogin ,to_login:');
		
		//add by wangcya, 20130114, for bug[348]
		if($_SGLOBAL['mobile_type'] == 'myself')
		{
			echo_result(100,'to_login');//add  by wangcya, 2030118
			
		}
		else
		{
			
			showmessage('to_login', 'do.php?ac='.$_SCONFIG['login_action']);
		}
	}
}

//前台
function lang($key, $vars=array()) {
	global $_SGLOBAL;

	include_once(S_ROOT.'./language/lang_source.php');
	if(isset($_SGLOBAL['sourcelang'][$key])) {
		$result = lang_replace($_SGLOBAL['sourcelang'][$key], $vars);
	} else {
		$result = $key;
	}
	return $result;
}

//煤台
function cplang($key, $vars=array()) {
	global $_SGLOBAL;

	include_once(S_ROOT.'./language/lang_cp.php');
	if(isset($_SGLOBAL['cplang'][$key])) {
		$result = lang_replace($_SGLOBAL['cplang'][$key], $vars);
	} else {
		$result = $key;
	}
	return $result;
}

//婊�
function lang_replace($text, $vars) {
	if($vars) {
		foreach ($vars as $k => $v) {
			$rk = $k + 1;
			$text = str_replace('\\'.$rk, $v, $text);
		}
	}
	return $text;
}

//没
function getfriendgroup() {
	global $_SCONFIG, $space;

	$groups = array();
	$spacegroup = empty($space['privacy']['groupname'])?array():$space['privacy']['groupname'];
	for($i=0; $i<$_SCONFIG['groupnum']; $i++) {
		if($i == 0) {
			$groups[0] = lang('friend_group_default');
		} else {
			if(!empty($spacegroup[$i])) {
				$groups[$i] = $spacegroup[$i];
			} else {
				if($i<8) {
					$groups[$i] = lang('friend_group_'.$i);
				} else {
					$groups[$i] = lang('friend_group').$i;
				}
			}
		}
	}
	return $groups;
}


//取
function sub_url($url, $length) {
	if(strlen($url) > $length) {
		$url = str_replace(array('%3A', '%2F'), array(':', '/'), rawurlencode($url));
		$url = substr($url, 0, intval($length * 0.5)).' ... '.substr($url, - intval($length * 0.3));
	}
	return $url;
}

/*

//SQL ADDSLASHES
function saddslashes($string) {
	if(is_array($string)) {//如果是数组
		foreach($string as $key => $val) {
			$string[$key] = saddslashes($val);//递归调用,//comment by wangcya , 20140307,for bug[898] 防止SQL注入
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

*/


//取械
function sarray_rand($arr, $num=1) 
{
	$r_values = array();
	if($arr && count($arr) > $num) {
		if($num > 1) {
			$r_keys = array_rand($arr, $num);
			foreach ($r_keys as $key) {
				$r_values[$key] = $arr[$key];
			}
		} else {
			$r_key = array_rand($arr, 1);
			$r_values[$r_key] = $arr[$r_key];
		}
	} else {
		$r_values = $arr;
	}
	return $r_values;
}


//form伪
function formhash() {
	global $_SGLOBAL, $_SCONFIG;
	
	
	if(!isset($_SCONFIG['sitekey']))
	{
		$_SCONFIG['sitekey'] = '';
		
	}
	
	if(empty($_SGLOBAL['formhash'])) {
		$hashadd = defined('IN_ADMINCP') ? 'Only For UCenter Home AdminCP' : '';
		$_SGLOBAL['formhash'] = substr(md5(substr($_SGLOBAL['timestamp'], 0, -7).'|'.$_SGLOBAL['supe_uid'].'|'.md5($_SCONFIG['sitekey']).'|'.$hashadd), 8, 8);
	}
	return $_SGLOBAL['formhash'];
}

//欠效
function isemail($email) {
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

//证
function question() {
	global $_SGLOBAL;

	include_once(S_ROOT.'./data/data_spam.php');
	if($_SGLOBAL['spam']['question']) {
		$count = count($_SGLOBAL['spam']['question']);
		$key = $count>1?mt_rand(0, $count-1):0;
		ssetcookie('seccode', $key);
		echo $_SGLOBAL['spam']['question'][$key];
	}
}

//MYOP息疟
function my_checkupdate() {
	global $_SGLOBAL, $_SCONFIG;
	if($_SCONFIG['my_status'] && empty($_SCONFIG['my_closecheckupdate']) && checkperm('admin')) {
		$sid = $_SCONFIG['my_siteid'];
		$ts = $_SGLOBAL['timestamp'];
		$key = md5($sid.$ts.$_SCONFIG['my_sitekey']);
		echo '<script type="text/javascript" src="http://notice.uchome.manyou.com/notice?sId='.$sid.'&ts='.$ts.'&key='.$key.'" charset="UTF-8"></script>';
	}
}

//没图
function g_icon($gid) {
	global $_SGLOBAL;
	include_once(S_ROOT.'./data/data_usergroup.php');
	if(empty($_SGLOBAL['grouptitle'][$gid]['icon'])) {
		echo '';
	} else {
		echo ' <img src="'.$_SGLOBAL['grouptitle'][$gid]['icon'].'" align="absmiddle"> ';
	}
}

//没色
function g_color($gid) {
	global $_SGLOBAL;
	include_once(S_ROOT.'./data/data_usergroup.php');
	if(empty($_SGLOBAL['grouptitle'][$gid]['color'])) {
		echo '';
	} else {
		echo ' style="color:'.$_SGLOBAL['grouptitle'][$gid]['color'].';"';
	}
}

//欠始
function ckfounder($uid) {
	global $_SC;

	$founders = empty($_SC['founder'])?array():explode(',', $_SC['founder']);
	if($uid && $founders) {
		return in_array($uid, $founders);
	} else {
		return false;
	}
}

//取目录
function sreaddir($dir, $extarr=array()) {
	$dirs = array();
	if($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if(!empty($extarr) && is_array($extarr)) {
				if(in_array(strtolower(fileext($file)), $extarr)) {
					$dirs[] = $file;
				}
			} else if($file != '.' && $file != '..') {
				$dirs[] = $file;
			}
		}
		closedir($dh);
	}
	return $dirs;
}


//start add by wangcya ,20130321, for bug[36], 要做IP限制 ,限制当日的可使用的查询数目
function limit_ip_perday($type , //访问的类型 
		                 $period , //在指定时间内
		                 $limitcount//指定时间内限制的次数。
		                 )
{
	global $_SGLOBAL, $_SC, $space , $_SCONFIG;
	//检查IP
	//////////////////////////////////////////////
	//
	
	$onlineip = getonlineip();
	$dateline = $_SGLOBAL['timestamp'];
	$uid = $_SGLOBAL['supe_uid'];
	$username = $_SGLOBAL['supe_username'];
	
	if(!checkperm('managehealthnews'))//不是信息管理员
	{
		$sql = "SELECT * FROM ".tname('ipsearchlimit')." WHERE regip='$onlineip' AND type='$type' LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);//ORDER BY dateline DESC 
		//showmessage($sql);
		if($value = $_SGLOBAL['db']->fetch_array($query))
		{
			if($value['totalbl']>=3)//总共犯规超过了三次，则计入黑名单
			{
				/*
				$n_url = "";
				$note_type = 'blacklist_ip';
				///////start add by wangcya, 20121211/////////////////////////////
				$notice_title = cplang('note_blacklist_ip');
				$notice_url = $n_url;
				$notice_subject = "black ipaddress";
				$note = cplang('note_blacklist_ip', array($n_url, "black ipaddress:".$value['regip']));
				///////end add by wangcya, 20121211/////////////////////////////
				
				//发送通知
				notification_add(1, $note_type, $note
				,0,$notice_title ,$notice_url,$notice_subject//start add by wangcya, 20121211
				);
				*/
				//这里应该通知管理员
				showmessage('你来自'.$onlineip.'的IP涉嫌违规访问，已经被记入黑名单！');
				return false;
				
			}
			//showmessage('beyond_limit_count'.$_SGLOBAL['timestamp']);
			//if($_SGLOBAL['timestamp'] - $value['dateline'] < $_SCONFIG['regipdate']*3600)
			if($_SGLOBAL['timestamp'] - $value['dateline'] < $period*3600)//如果在N小时之内
			{
				if($value['count']>=$limitcount)
				{
					
					return false;
				}
				else
				{
					
					//这里操作影响性能，应该在内存中操作，但是没办法了。
					updatetable('ipsearchlimit', array('count'=>($value['count']+1), 'dateline'=>$dateline,'uid'=>$uid,'username'=>$username),
				                 array('regip'=>$onlineip,'type'=>$type));
				
					return true;
				}
			}
			else//beyond on house,then reset,超过指定时间则清空
			{
				//如果在一小时之内的次数超限，则记录一次，如果三小时连续出现，则证明恶意访问，则放入黑名单。当然了要对搜索引擎开放。
				if($value['count']>=$limitcount)
				{ //一小时内计数器归零同时，黑名单计数器增加一。
					updatetable('ipsearchlimit', array('count'=>0,'dateline'=>$dateline,'uid'=>$uid,'username'=>$username,'totalbl'=>($value['totalbl']+1)),
					array('regip'=>$onlineip,'type'=>$type));
				}
				else
				{	
					
					updatetable('ipsearchlimit', array('count'=>0,'dateline'=>$dateline,'uid'=>$uid,'username'=>$username,), 
					            array('regip'=>$onlineip,'type'=>$type));
				}
				      
			  	return true;
			}
		}//
		else//not find this ip. 
		{
			
			inserttable('ipsearchlimit', 
			             array( 'regip'=>$onlineip,
			            		'type'=>$type,
			             		'count'=>0,
			                    'dateline'=>$dateline,
			                    'uid'=>$uid,
			                    'username'=>$username
			                    ),
			            0, true);
			            
			return true;
		}
		
		return false;
	}
	else//信息管理员都开放。
	{
		return true;
	}
	
	
	return false;//默认禁止
	
}
//end add by wangcya ,20130321, for bug[36], 要做IP限制 ,限制当日的可使用的查询数目

//start add by wangcya, 20130622, for bug[518],标签的最长匹配问题
function process_same_word($array_tags)
{
	return $array_tags;
	
	//$array_tags_new = array_unique($array_tags);//移除数组中的重复的值，并返回结果数组。
	foreach($array_tags as $key => $value)
	{
		foreach($array_tags as $key1 => $value1)
		{
			if($key == $key1)//自己则绕过去。
			{
				continue;
			}
			else
			{
				if( strstr($value[tagname], $value1[tagname]))
				{
					//ss_log("key: ".$key." key1: ".$key1." tagname: ".$value[tagname]." tagname1: ".$value1[tagname]);
					unset($array_tags[$key1]);
				}
				
			}
		}
	}
	
	return $array_tags;
}

function a_array_unique($array)//写的比较好
{
	$out = array();
	foreach ($array as $key=>$value) {
		if (!in_array($value, $out))
		{
			$out[$key] = $value;
		}
	}
	return $out;
}


//start add by wangcya, 20130728 for bug[567] 	医生回答后，进行了两次重复收费
/**
 * CacheLock 进程锁,主要用来进行cache失效时的单进程cache获取，防止过多的SQL请求穿透到数据库
* 用于解决PHP在并发时候的锁控制,通过文件/eaccelerator进行进程间锁定
* 如果没有使用eaccelerator则进行进行文件锁处理，会做对应目录下产生对应粒度的锁
* 使用了eaccelerator则在内存中处理，性能相对较高
* 不同的锁之间并行执行，类似mysql innodb的行级锁
* 本类在sunli的phplock的基础上做了少许修改  http://code.google.com/p/phplock
* @author yangxinqi
*
*/
class CacheLock
{
	//文件锁存放路径
	private $path = null;
	//文件句柄
	private $fp = null;
	//锁粒度,设置越大粒度越小
	private $hashNum = 100;
	//cache key
	private $name;
	//是否存在eaccelerator标志
	private  $eAccelerator = false;

	/**
	 * 构造函数
	 * 传入锁的存放路径，及cache key的名称，这样可以进行并发
	 * @param string $path 锁的存放目录，以"/"结尾
	 * @param string $name cache key
	 */
	public function __construct($name,$path='lock\\')
	{
		//判断是否存在eAccelerator,这里启用了eAccelerator之后可以进行内存锁提高效率
		$this->eAccelerator = function_exists("eaccelerator_lock");
		if(!$this->eAccelerator)
		{
			$this->path = $path.($this->_mycrc32($name) % $this->hashNum).'.txt';
		}
		$this->name = $name;
	}

	/**
	 * crc32
	 * crc32封装
	 * @param int $string
	 * @return int
	 */
	private function _mycrc32($string)
	{
		$crc = abs (crc32($string));
		if ($crc & 0x80000000) {
			$crc ^= 0xffffffff;
			$crc += 1;
		}
		return $crc;
	}
	/**
	 * 加锁
	 * Enter description here ...
	 */
	public function lock()
	{
		//如果无法开启ea内存锁，则开启文件锁
		if(!$this->eAccelerator)
		{
			//配置目录权限可写
			$this->fp = fopen($this->path, 'w+');
			if($this->fp === false)
			{
				return false;
			}
			return flock($this->fp, LOCK_EX);
		}else{
			return eaccelerator_lock($this->name);
		}
	}

	/**
	 * 解锁
	 * Enter description here ...
	 */
	public function unlock()
	{
		if(!$this->eAccelerator)
		{
			if($this->fp !== false)
			{
				flock($this->fp, LOCK_UN);
				clearstatcache();
			}
			//进行关闭
			fclose($this->fp);
		}else{
			return eaccelerator_unlock($this->name);
		}
	}
}
//end add by wangcya, 20130728 for bug[567] 	医生回答后，进行了两次重复收费


/**
 * @desc 根据生日获取年龄
 * @param     string $birthday
 * @return    integer
 */
function getAge($birthday)
{
	$birthday=getDate(strtotime($birthday));
	//$birthday=getDate($birthday);
	$now=getDate();
	$month=0;
	if($now['month']>$birthday['month'])
		$month=1;
	if($now['month']==$birthday['month'])
		if($now['mday']>=$birthday['mday'])
		$month=1;
	return $now['year']-$birthday['year']+$month;
}

//获取日志图片
function getmessagepic($message) {
	$pic = '';
	$message = stripslashes($message);
	$message = preg_replace("/\<img src=\".*?image\/face\/(.+?).gif\".*?\>\s*/is", '', $message);	//移除表情符
	preg_match("/src\=[\"\']*([^\>\s]{25,105})\.(jpg|gif|png)/i", $message, $mathes);
	if(!empty($mathes[1]) || !empty($mathes[2])) {
		$pic = "{$mathes[1]}.{$mathes[2]}";
	}
	return addslashes($pic);
}

//检查验证码
function ckseccode($seccode) {
	global $_SGLOBAL, $_SCOOKIE, $_SCONFIG;

	return true;//add by wangcya , 20140802
	////////////////////////////////////////////////////
	$check = true;
	if(empty($_SGLOBAL['mobile'])) {
		if($_SCONFIG['questionmode']) {
			include_once(S_ROOT.'./data/data_spam.php');
			$cookie_seccode = intval($_SCOOKIE['seccode']);
			$seccode = trim($seccode);
			if($seccode != $_SGLOBAL['spam']['answer'][$cookie_seccode]) {
				$check = false;
			}
		} else {
			$cookie_seccode = empty($_SCOOKIE['seccode'])?'':authcode($_SCOOKIE['seccode'], 'DECODE');
			if(empty($cookie_seccode) || strtolower($cookie_seccode) != strtolower($seccode)) {
				$check = false;
			}
		}
	}
	return $check;
}

//远页
function mob_perpage($perpage) {
	global $_SGLOBAL;

	$newperpage = isset($_GET['perpage'])?intval($_GET['perpage']):0;
	if($_SGLOBAL['mobile'] && $newperpage>0 && $newperpage<500) {
		$perpage = $newperpage;
	}
	return $perpage;
}

/*
前端开发工程师都知道javascript有编码函数escape()和对应的解码函数unescape()，
而php中只有个urlencode和urldecode，这个编码和解码函数对encodeURI和encodeURIComponent有效，但是对escape的是无效的。
javascript中的escape()函数和unescape()函数用户字符串编码，类似于PHP中的urlencode()函数，下面是php实现的escape函数代码：


由于PHP5不支持unicode，而JS的escape方法转义后的代码就是UNICODE的，所以PHP需要先解析unicode编码为utf8,然后再把utf8编码转成想要的编码

*/

/*
function unescape($str){
	$ret = '';
	$len = strlen($str);
	for ($i = 0; $i < $len; $i++){
		if ($str[$i] == '%' && $str[$i+1] == 'u'){
			$val = hexdec(substr($str, $i+2, 4));
			if ($val < 0x7f) $ret .= chr($val);
			else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));
			else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));
			$i += 5;
		}
		else if ($str[$i] == '%'){
			$ret .= urldecode(substr($str, $i, 3));
			$i += 2;
		}
		else $ret .= $str[$i];
	}
	return $ret;
}
*/

function unescape($str)
{
	$ret = '';
	$len = strlen($str);
	for ($i = 0; $i < $len; $i ++)
	{
	if ($str[$i] == '%' && $str[$i + 1] == 'u')
	{
	$val = hexdec(substr($str, $i + 2, 4));
	if ($val < 0x7f)
		$ret .= chr($val);
		else
		if ($val < 0x800)
		$ret .= chr(0xc0 | ($val >> 6)) .
		chr(0x80 | ($val & 0x3f));
		else
		$ret .= chr(0xe0 | ($val >> 12)) .
		chr(0x80 | (($val >> 6) & 0x3f)) .
		chr(0x80 | ($val & 0x3f));
		$i += 5;
	} else
		if ($str[$i] == '%')
		{
		$ret .= urldecode(substr($str, $i, 3));
		$i += 2;
	} else
			$ret .= $str[$i];
	}
	return $ret;
	}


	//start add by wangyca , 20140102  for bug[922] 悬赏问答中对问题进行匿名设置参与的处理得到是否已经匿名参与了改问题///////
	function get_anonymous_status($uid , $qid )
	{
		global $_SGLOBAL;
		//////////////////////////////////////////////////////////
		$sql = "SELECT * FROM ".tname('ask_anonymous')." WHERE qid='$qid' AND uid='$uid'";
		//ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
		$olds = $_SGLOBAL['db']->fetch_array($query);
		if($olds['id'])
		{
			$anonymous  = intval($olds['anonymous']);
		}
	
		return $anonymous;
		 
	}
	

//从目录下面得到所有的文件面，然后进行添加到数据库中。
function getfileName($dir)
{
	global $_SGLOBAL;
	////////////////////////////////////////////////////////
	$array=array();
	//1、先打开要操作的目录，并用一个变量指向它
	//打开当前目录下的目录pic下的子目录common。
	$handler = opendir($dir);
	//2、循环的读取目录下的所有文件
	/*其中$filename = readdir($handler)是每次循环的时候将读取的文件名赋值给$filename，为了不陷于死循环，所以还要让$filename !== false。一定要用!==，因为如果某个文件名如果叫’0′，或者某些被系统认为是代表false，用!=就会停止循环*/
	while( ($filename = readdir($handler)) !== false )
	{
		// 3、目录下都会有两个文件，名字为’.'和‘..’，不要对他们进行操作
		if($filename != '.' && $filename != '..')
		{
			// 4、进行处理
			array_push($array,$filename);
				
			//jy_read_xml($filename);
				
			//$ret_array = add_deteck_method_form_xml($filename);
				
			/*
			 $wheresql  = "mdname='$ret_array[mdname]'";
			$sql = "SELECT * FROM ".tname('health_detectmethod')." WHERE $wheresql";
			$query = $_SGLOBAL['db']->query($sql);
			$value = $_SGLOBAL['db']->fetch_array($query);
			if(empty($value['mdid']))
			{
			return ;
			}
			*/

				
				
				

				
		}
	}
	//5、关闭目录
	closedir($handler);

	//echo "finish!";
	return $array;
}

//start add by wangcya, 20150126
function gen_uuid() {
	if (function_exists ( 'com_create_guid' )) {
		return com_create_guid ();
	} else {
		mt_srand ( ( double ) microtime () * 10000 ); //optional for php 4.2.0 and up.随便数播种，4.2.0以后不需要了。
		$charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) ); //根据当前时间（微秒计）生成唯一id.
		$hyphen = chr ( 45 ); // "-"
		$uuid = '' . //chr(123)// "{"
				substr ( $charid, 0, 8 ) . $hyphen . substr ( $charid, 8, 4 ) . $hyphen . substr ( $charid, 12, 4 ) . $hyphen . substr ( $charid, 16, 4 ) . $hyphen . substr ( $charid, 20, 12 );
		//.chr(125);// "}"
		return $uuid;
	}
}
//end add by wangcya, 20150126

//唯一性和长短要一样。如果服务器时间变了呢?递增
function getRandOnly_Id($len_guid,$split=0)
{
	global $_SGLOBAL;
	//////////////////////////////////
	ss_log("into ".__FUNCTION__);
	//$filepath = "{$_SGLOBAL['supe_uid']}_{$_SGLOBAL['timestamp']}".random(4).".$fileext";
	//$UUID = "{$_SGLOBAL['supe_uid']}{$_SGLOBAL['timestamp']}".rand(10,99);//random(4);
	//$curtime=time();//当前时间戳
	//$UUID = $_SGLOBAL['supe_uid'].time().rand(10,99);//random(4);
	
	//$UUID = $_SGLOBAL['supe_uid'].gen_uuid().rand(10,99);//random(4);
	
	
	$mtime=explode(' ',microtime());

	//ss_log("mtime1: ".$mtime[1]);
	//ss_log("mtime0: ".$mtime[0]);
	
	$str_tmp = $mtime[0];
	$str_tmp = substr($str_tmp,2,strlen($str_tmp)-2);
	//ss_log("str_tmp: ".$str_tmp);
	
	$startTime=$mtime[1].$str_tmp;
	//ss_log("startTime: ".$startTime);
	
	
	if($_SGLOBAL['supe_uid'])
	{
		$uid = $_SGLOBAL['supe_uid'];
		//ss_log("supe_uid: ".$uid);
	}
	else
	{
		$uid = $_SESSION['user_id'];
		//ss_log("session_uid: ".$uid);
	}
	//$mtime[0] = preg_replace('/0./i','',$mtime[0]);  // 这里做了更改..
	//ss_log("after mtime0: ".$mtime[0]);
		
	$randnum = rand(10,99);
	
	ss_log("when gen UUID , uid: ".$uid);
	ss_log("when gen UUID , startTime: ".$startTime);
	ss_log("when gen UUID , randnum: ".$randnum);
	
	if(!$split)
	{
		$UUID = $uid.$startTime.$randnum;//random(4);
	}
	else
	{
		$UUID = $uid."_".$startTime."_".$randnum;//random(4);
	}
	
	ss_log("UUID: ".$UUID);
	
	////////////////////////////////////////////////////////
	//$len_guid - strlen($UUID);
	if($len_guid>0)
	{
		ss_log("UUID截断前： ".$UUID);
		$UUID = substr($UUID, 0, $len_guid);
		ss_log("UUID截断后： ".$UUID);
	}
	else
	{
		ss_log("UUID不必截断： ".$UUID);
	}
	
	//不截断，则是22个字符
	
	ss_log(__FUNCTION__."，最终随机串：".$UUID);
	return $UUID;
}

function getRandOnlyId()
{
	//新时间截定义,基于世界未日2012-12-21的时间戳。
	$endtime=1356019200;//2012-12-21时间戳
	$curtime=time();//当前时间戳
	$newtime=$curtime-$endtime;//新时间戳
	$rand=rand(0,99);//两位随机
	$all=$rand.$newtime;
	//$onlyid=base_convert($all,10,36);//把10进制转为36进制的唯一ID
	$onlyid = $all;
	return $onlyid;
}

function guid()
{
	if (function_exists('com_create_guid'))
	{
		return com_create_guid();
	}
	else
	{
		mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45);// "-"
		$uuid = chr(123)// "{"
		.substr($charid, 0, 8).$hyphen
		.substr($charid, 8, 4).$hyphen
		.substr($charid,12, 4).$hyphen
		.substr($charid,16, 4).$hyphen
		.substr($charid,20,12)
		.chr(125);// "}"
		return $uuid;
	}
}


//end by wangcya, 20140812


function curlPost($ch,
		$flagpost,
		$url,
		$port,
		$data,// = array(),
		$timeout = 30,
		$CA
)
{
	$SSL = substr($url, 0, 8) == "https://" ? true : false;

	//$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout-2);
	//curl_setopt($ch, CURLOPT_COOKIEFILE, "/Library/WebServer/Documents/tmp/cookieFileName");
	//curl_setopt($ch, CURLOPT_HEADER, true);

	curl_setopt($ch, CURLOPT_HEADER, 0); // ��ʾ���ص�Header��������
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // ��ȡ����Ϣ���ļ�������ʽ����

	//curl_setopt($ch, CURLOPT_USERPWD, "admin:12345678");
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/xml', 'Content-length: '. strlen($data)) );

	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); //����data��ݹ�����
	//curl_setopt($ch, CURLOPT_VERBOSE, 1); //debugģʽ

	curl_setopt($ch, CURLOPT_PORT, $port);
	//curl_setopt($ch, CURLOPT_VERBOSE, 1); //debugģʽ

	if($SSL)
	{

		/*
		 *  �������Ҫ��ͻ�����֤����Ҫ��pfx֤��ת����pem��ʽ

		openssl pkcs12 -clcerts -nokeys -in cert.pfx -out client.pem    #�ͻ��˸���֤��Ĺ�Կ

		openssl pkcs12 -nocerts -nodes -in cert.pfx -out key.pem #�ͻ��˸���֤���˽Կ

		Ҳ����ת��Ϊ��Կ��˽Կ�϶�Ϊһ���ļ�

		openssl pkcs12 -in  cert.pfx -out all.pem -nodes                                   #�ͻ��˹�Կ��˽Կ��һ�����all.pem��


		ִ��curl����

		����ʹ��client.pem+key.pem

		curl -k --cert client.pem --key key.pem https://www.xxxx.com

			

		2��ʹ��all.pem

		curl -k --cert all.pem  https://www.xxxx.com
		* */

		//echo "will ssl<br />";
		//curl_setopt($ch2, CURLOPT_SSLVERSION, 3);
		//curl_setopt($ch, CURLOPT_SSLCERT, "./keys/client.crt"); //client.crt�ļ�·��
			
		//$clientkey = S_ROOT."keys/EXV_BIS_IFRONT_PCIS_ZTXH_001_PRD.pfx";
		$clientkey = S_ROOT."keys/all.pem";

		//echo $clientkey."<br />";

		curl_setopt($ch,CURLOPT_SSLCERTTYPE,'pem');
		curl_setopt($ch,CURLOPT_SSLCERT,$clientkey);
		//curl_setopt($ch,CURLOPT_SSLCERTPASSWD,'yangmingsong'); //client֤������
		//curl_setopt($ch,CURLOPT_SSLKEYTYPE,'pem');
		//curl_setopt($ch,CURLOPT_SSLKEY,$clientkey);

	}


	if ($SSL && $CA)
	{
		//echo "ssl and ca<br />";
		$cacert = getcwd() . '/cacert.pem'; //CA��֤��
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);   // ֻ����CA�䲼��֤��
		curl_setopt($ch, CURLOPT_CAINFO, $cacert); // CA��֤�飨������֤����վ֤���Ƿ���CA�䲼��
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // ���֤�����Ƿ������������Ƿ����ṩ��������ƥ��
	}
	else if ($SSL && !$CA)
	{

		//echo "ssl no ca<br />";


		//curl_setopt($ch, CURLOPT_RESOLVE, $host);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // ���ζԷ���������֤�飬������֤��


		//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // ��֤���м��SSL�����㷨�Ƿ����
		//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // // ���֤�����Ƿ���������,0����֤

	}


	if($flagpost)
	{
		//echo "will post:<br />";//.$data;
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	}
	else
	{
		curl_setopt($ch, CURLOPT_POST, false);
	}
	//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); //data with URLEncode

	//curl_setopt_array($ch, $opt);

	$ret = curl_exec($ch);


	if (curl_errno($ch))
	{
		var_dump(curl_error($ch));  //�鿴������Ϣ
		$flag = 1;
		$ret= "error!";
	}
	else
	{
		//ss_log($ret);
		$flag = 0;
	}

	//echo $ret;


	//echo "<br />will return!<br />";

	//curl_close($ch);
	return $ret;
}

//start by wangcya, 20140812////////
function create_user_in_diubuliao(	$user_id,
		$user_name,
		$user_email,
		$user_mobile
)
{

	///////////////////////////////////////////////////
	//include_once(ROOT_PATH.'includes/lib_base.php');
	//include_once('../includes/lib_base.php');
	//define('IN_ECS', true);
	//include_once('includes/init.php');


	$ret_attr = array();
	///////////////////////////////////////////////////
	$user_email_real = $user_email;//用户真实邮箱，后面有用
	$user_email = $user_id."_service@ebaoins.cn";
	$user_mobile = "4006900618";

	if( empty($user_id)||
			empty($user_name)||
			empty($user_email)||
			empty($user_mobile)
	)
	{
		$str_err = "input param is not complete!";
		ss_log($str_err);

		$ret_attr["peerid"] = 0;
		$ret_attr["status"] = 110;
		$ret_attr["msg"] = $str_err;

		return $ret_attr;
	}

	//echo "sdfdsfffffffffff";
	$ch = curl_init();
	////////////////////////////////////////////////////////////////

	$flagpost= 1;//

	$url = URL_DIUBULIAO_CRETE_USER;

	ss_log("to diubuliao create user url: ".$url);

	$port =  80;


	$mdstr = 'fei93-tgie13jwi.6s78wwmerti';
	$sig = md5($user_id.$user_email.$user_mobile.$mdstr);

	$str_log =  "diubuliao two-dimension user_id: ".$user_id." user_name: ".$user_name." user_email: ".$user_email." user_mobile: ".$user_mobile;
	ss_log($str_log);

	$userdata="user_id=$user_id&user_name=$user_name&user_email=$user_email&user_mobile=$user_mobile&sig=$sig";
	//发送请求到保险公司服务器

	//ss_log("userdata: ".$userdata);

	$return_data = curlPost($ch,$flagpost,$url, $port, $userdata, 10, 0);
	if(empty($return_data))
	{

		$str_err = "diubuliao two-dimension return is null!";
		ss_log($str_err);
			
		$ret_attr["peerid"] = 0;
		$ret_attr["status"] = 110;
		$ret_attr["msg"] = $str_err;
		return $ret_attr;

	}
	else
	{
		//先保存一份
		$return_path = S_ROOT."xml/".$user_id."_uid_diubuliao_return.xml";
		file_put_contents($return_path,$return_data);


		$ret_json = json_decode($return_data,true);
		$ret_json_err = json_last_error();
		//echo "fsdfdsfs";
		if($ret_json_err!=JSON_ERROR_NONE)
		{
			$str_err = "diubuliao two-dimension return json format error!";
			ss_log($str_err);

			$ret_attr["peerid"] = 0;
			$ret_attr["status"] = 110;
			$ret_attr["msg"] = $str_err;
			return $ret_attr;
		}
		/*
		 switch (json_last_error())
		 {
		case JSON_ERROR_NONE:
		echo ' - No errors';
		break;
		case JSON_ERROR_DEPTH:
		echo ' - Maximum stack depth exceeded';
		break;
		case JSON_ERROR_STATE_MISMATCH:
		echo ' - Underflow or the modes mismatch';
		break;
		case JSON_ERROR_CTRL_CHAR:
		echo ' - Unexpected control character found';
		break;
		case JSON_ERROR_SYNTAX:
		echo ' - Syntax error, malformed JSON';
		break;
		case JSON_ERROR_UTF8:
		echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
		break;
		default:
		echo ' - Unknown error';
		break;
		}
		*/

			
		$zt_uid = intval($ret_json[data][ztid]);//对方返回的保险平台的uid
		$peerid = intval($ret_json[data][id]);
		$pic = $ret_json[data][dcode];
		$pic_url = $ret_json[data][dcode_url];//二维码对应的url


		$info = $ret_json[info];
		$status = $ret_json[status];
		if($status==0)
		{
			ss_log('diubuliao two-dimension, status: '.$status.' peer_id: '.$peerid.' zt_uid:'.$zt_uid.' pic: '.$pic.' info: '.$info);
			if($zt_uid!=$user_id)
			{
				$str_err = "err,peer two-dimension return zt_uid is not same as local user_uid!";
				ss_log($str_err);


				$ret_attr["peerid"] = 0;
				$ret_attr["status"] = 130;
				$ret_attr["msg"] = $str_err;
				return $ret_attr;
			}

			if(empty($pic))//如果对方返回的状态正常，但是二维码连接却为空，则也不对
			{
				$str_err = "err,peer two-dimension url is null!";
				ss_log($str_err);


				$ret_attr["peerid"] = 0;
				$ret_attr["status"] = 120;
				$ret_attr["msg"] = $str_err;
				return $ret_attr;
			}

			$mail_subject = "这是您售卖丢不了手表的二维码";

			if(1)//html
			{
				//$mail_pic = "<img src=\"$pic\"/>";
				$mail_pic = "请点击该链接获取二维码图片:<br/>"."<a href=\"$pic\">$pic</a>"
				."<br>也可输入该链接到手机浏览器中进行产品购买:<br><a href=\"$pic_url\" target=\"_blank\">$pic_url</a>";
				$mail_content = $mail_pic;

				ss_log('user_email_real: '.$user_email_real." mail_subject: ".$mail_subject." mail_content: ".$mail_content);

				$ret = send_mail('', $user_email_real, $mail_subject , $mail_content ,1);
			}
			else//common
			{
				//$mail_pic = "<img src=\"$pic\"/>";
				$mail_content = $pic;

				ss_log('user_email_real: '.$user_email_real." mail_subject: ".$mail_subject." mail_content: ".$mail_content);

				$ret = send_mail('', $user_email_real, $mail_subject , $mail_content ,0);
			}

			if($ret !=true)
			{

				$str_err = "send diubuliao two-dimension pic mail to agent user fail!";
				ss_log($str_err);


				$ret_attr["peerid"] = 0;
				$ret_attr["status"] = 110;
				$ret_attr["msg"] = $str_err;
				return $ret_attr;
			}
			else
			{

				ss_log("send pic mail success!");

			}
		}
		else
		{
			$str_err = "diubuliao two-dimension return is err,status: ".$info;
			ss_log($str_err);


			$ret_attr["peerid"] = 0;
			$ret_attr["status"] = 110;
			$ret_attr["msg"] = $str_err;
			return $ret_attr;
		}

		//////////////////////////////////////////////////////////
	}

	$ret_attr["peerid"] = $peerid;
	$ret_attr["pic"] = $pic;
	$ret_attr["status"] = $status;
	$ret_attr["msg"] = $str_err;


	return $ret_attr;

}


//获取上传路径
function getfilepath($fileext, $mkdir=false) {
	global $_SGLOBAL, $_SC;

	$filepath = "{$_SGLOBAL['supe_uid']}_{$_SGLOBAL['timestamp']}".random(4).".$fileext";
	$name1 = gmdate('Ym');
	$name2 = gmdate('j');

	if($mkdir)
	{
		$newfilename = $_SC['attachdir'].'./'.$name1;
		if(!is_dir($newfilename)) {
			if(!@mkdir($newfilename)) {
				runlog('error', "DIR: $newfilename can not make");
				return $filepath;
			}
		}
		$newfilename .= '/'.$name2;
		if(!is_dir($newfilename)) {
			if(!@mkdir($newfilename)) {
				runlog('error', "DIR: $newfilename can not make");
				return $name1.'/'.$filepath;
			}
		}
	}
	return $name1.'/'.$name2.'/'.$filepath;
}
//end by wangcya, 20140812////////

function create_temp_upload_file($FILE , $allowpictype)
{
	$str = "";
	
	/////////////////////////////////////////////////////////
	
	//下面的路径按照你PHPExcel的路径来修改
	$path = dirname(S_ROOT);
	$tempfilePath = $path.'/temp/';
	
	ss_log(__FUNCTION__."tempfilePath: ".$tempfilePath);
	//注意设置时区
	$time=date("y-m-d-H-i-s");//去当前上传的时间
	//获取上传文件的扩展名
	//$allowpictype = array('xls');
	//////////////////////////////////////////////////////
	//echo "<br>";
	//echo "file name: ".$FILE['name'];
	
	$fileext = fileext($FILE['name']);
	//echo "<br>";
	//echo "fileext: ".$fileext;
	
	if(!in_array($fileext, $allowpictype))
	{
		
		$msg = "文件扩展名不合规！";
		
		$ret_attr = array('ret_code'=>1,
						  'ret_msg'=>$msg
				          );
		
		return $ret_attr;
	}
	//$FILE['size']
	
	if(!$filepath = getfilepath($fileext, true))
	{
		$msg = "建立本地文件路径错误！";
		
		$ret_attr = array('ret_code'=>1,
						  'ret_msg'=>$msg
					    );
		
		return $ret_attr;
	}
	else
	{
		//echo "<br>";
		//echo "local path: ".$filepath;
	}
	//本地上传
	$new_name = $tempfilePath.$filepath;
	
	ss_log(__FUNCTION__.", new file name=".$new_name);
	//echo "<br>";
	//echo "new_name: ".$new_name;
	
	$tmp_name = $FILE['tmp_name'];
	
	$result = 0;
	if($result = @copy($tmp_name, $new_name))
	{
		@unlink($tmp_name);//进行了临时文件的删除操作
	}
	//comment by zhangxi, 20150319, copy不行就执行move操作
	elseif((function_exists('move_uploaded_file') && $result = @move_uploaded_file($tmp_name, $new_name)))
	{
		ss_log(__FUNCTION__.", move file ".$tmp_name." to ".$new_name);
	}
	//再尝试重命名文件的操作
	elseif($result = @rename($tmp_name, $new_name))
	{
		ss_log(__FUNCTION__.", rename file ".$tmp_name." to ".$new_name);
	}
	else
	{
		$msg = "move file error！";
		
		$ret_attr = array('ret_code'=>1,
						'ret_msg'=>$msg
						);
		
		return $ret_attr;
	
	}
	
	//是否压缩
	//获取上传后图片大小
	if(@$newfilesize = filesize($new_name))
	{
		$FILE['size'] = $newfilesize;
	}
	////////////////////////////////////////////////////
	//上传后的文件名
	$uploadfile = $new_name;
	////////////////////////////////////////////////////////////
	$ret_attr = array('ret_code'=>0,
					'ret_msg'=>$uploadfile
					);
	
	return $ret_attr;
}



// 手机号验证 
function checkMobileValidity($mobilephone)
{ 
	return true;                                                                                                                                  
	$exp = "/^13[0-9]{1}[0-9]{8}$|15[012356789]{1}[0-9]{8}$|18[012356789]{1}[0-9]{8}$|14[57]{1}[0-9]$/"; 
	if(preg_match($exp,$mobilephone))
	{ 
		return true; 
	}
	else
	{ 
		return false; 
	}
} 
// 手机号码归属地(返回: 如 广东移动) 
function checkMobilePlace($mobilephone)
{ 
	$url = "http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=".$mobilephone."&t=".time(); 
	$content = file_get_contents($url); 
	ss_log($content);
	/*
	 __GetZoneResult_ = {
    mts:'1361126',
    province:'北京',
    catName:'中国移动',
    telString:'13611260420',
	areaVid:'29400',
	ispVid:'3236139',
	carrier:'北京移动'
	}
	*/

	/*
	$ret = json_decode($content,true);
	//$ret = $content;
	print_r($ret);
	$province  = $ret['__GetZoneResult_']['province'];
	$catName   = $ret['__GetZoneResult_']['catName'];
	*/
	
	$province  = substr($content, 56, 4);
	$catName = substr($content, 81, 4);
	
	$province = conv2utf8($province);
	$catName = conv2utf8($catName);
	
	ss_log("province: ".$province);
	ss_log("catName: ".$catName);
	
	$ret = array("province"=>$province,
			     "catName"=>$catName);
	
	//$str = conv2utf8($p).conv2utf8($mo); 
	
	return $ret;
} 
// 转换字符串编码为 UTF8 
function conv2utf8($text)
{ 
	return mb_convert_encoding($text,'UTF-8','ASCII,GB2312,GB18030,GBK,UTF-8'); 
} 

//start add yes 123 2014-10-05 解析XML的方法 
function xml_to_array($xml) 
{
	$array = (array) (simplexml_load_string($xml,null, LIBXML_NOCDATA));
	foreach ($array as $key => $item) {
		$array[$key] = CommonUtils :: struct_to_array((array) $item);
	}
	return $array;
}
function struct_to_array($item) 
{
	if (!is_string($item)) {
		$item = (array) $item;
		foreach ($item as $key => $val) {
			$item[$key] = CommonUtils :: struct_to_array($val);
		}
	}
	return $item;
}
//end add yes 123 2014-10-05 解析XML的方法 
?>
