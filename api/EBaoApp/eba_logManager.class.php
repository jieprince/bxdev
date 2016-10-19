<?php
/**
 * 日志打印
 * $Author: dingchaoyang $
 * 2014-12-22 $
 */
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/eba_logManager.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
include_once 'platformEnvironment.class.php';
include_once ($ROOT_PATH_ . 'baoxian/source/function_debug.php');

class Eba_LogManager{
	public static function log($from=''){
		return;
		ss_log(self:: logConent(iconv('utf-8', 'gb2312//ignore', json_encode($_SERVER ['HTTP_USER_AGENT']))));
		if (PlatformEnvironment::isMobilePlatform()){

			ss_log(self:: logConent($from));
		}
	}
	
	public static function logPayResonse($from=''){
			ss_log(self:: logConent($from));
	}
	
	private static function logConent($from){
		$edition = 'editionID:'.PlatformEnvironment::getEditionID() . ';';
		$platform = 'platformID:' . PlatformEnvironment::getPlatformID() . ';';
		$deviceToken = 'deviceToken:' .PlatformEnvironment::getDeviceToken() . ';';
		$getMsg = 'getMsg:' . iconv('utf-8', 'gb2312//ignore', json_encode($_GET)) . ';';
		$postMsg = 'postMsg:' . iconv('utf-8', 'gb2312//ignore', json_encode($_POST)) . ';';
		$log ='ebalogmanager:'. $from . $edition . $platform . $deviceToken . $getMsg . $postMsg;
		return $log;
	}
}

?>