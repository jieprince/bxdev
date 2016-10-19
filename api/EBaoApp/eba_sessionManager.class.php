<?php
/**
 * 会话处理：针对手机app访问，处理会话。不用php的session
 * 原因：app访问有时有问题，且联调接口时，在投保请求时，会话一直失效
 * 原理：app每次启动，登录成功后，服务器返回uid和随机产生的token.之后再请求需要登录后的数据时，须上传uid和token
 * $Author: dingchaoyang $
 * 2014-12-21 $
 */
// $ROOT_PATH_ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
include_once 'platformEnvironment.class.php';
class Eba_SessionManager {
	public static function setSession() {
		if (PlatformEnvironment::isMobilePlatform () || PlatformEnvironment::isMobileHFivePlatform()) {
			// 请求的uid
			$uid = isset ( $_REQUEST ['uid'] ) && ! empty ( $_REQUEST ['uid'] ) ? trim ( $_REQUEST ['uid'] ) : '';
			//兼容h5页面上传加密后的uid
			$uidArray = explode ( ',', $uid );
			
			$uid = $uidArray [0];
			// todo
			// token未实现，会根据uid、token和platformId 查询uid和该uid是否一致。一致则有效用户，赋值session,否则告知用户登录失败
			// $checkUid = self::checkUID($uid);
			if ($uid) {
				global $_SGLOBAL;
				if (! isset ( $_SGLOBAL ['db'] )) {
					$ROOT_PATH_ = str_replace ( 'api/EBaoApp/eba_sessionManager.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
					include_once ($ROOT_PATH_ . 'baoxian/common.php');
				}
				
				$sql = "SELECT * FROM bx_users WHERE user_id='" . $uid . "'";
				$set = $_SGLOBAL ['db']->query ( $sql );
				$set = $_SGLOBAL ['db']->fetch_array ( $set );
				$_SESSION ['user_id'] = $set ['user_id'];
				$_REQUEST['uid'] = $set ['user_id'];
				$_SESSION ['user_name'] = $set ['user_name'];
				$_SESSION ['is_cheack'] = $set ['is_cheack'];
				// $_SESSION['user_id'] = $uid;
				// $_SESSION['user_name'] = $_REQUEST['username'];
			} else {
			}
		}
	}
	
	// 根据uid、token和platformId 查询uid
	private static function checkUID($uid) {
		global $_SGLOBAL;
		$platformId = PlatformEnvironment::getPlatformID ();
		$accessToken = PlatformEnvironment::getAccessToken ();
		$sql = "select * from bx_app_session_info where uid='" . $uid . "' and platform_id='" . $platformId . "' and sess_token='" . $accessToken . "'";
		$set = $_SGLOBAL ['db']->query ( $sql );
		$set = $_SGLOBAL ['db']->fetch_array ( $set );
		return $set ['uid'];
	}
	
	// 登录成功后，服务器会给app返回一个accessToken,其他需要身份信息的请求必须带上此accessToken，以user-agent形式发送。
	public static function getSessionToken($uid) {
		global $_SGLOBAL;
		$token = '';
		$platformId = PlatformEnvironment::getPlatformID ();
		$sql = "select * from bx_app_session_info where uid='" . $uid . "' and platform_id='" . $platformId . "'";
		$set = $_SGLOBAL ['db']->query ( $sql );
		$set = $_SGLOBAL ['db']->fetch_array ( $set );
		if ($set) {
			$token = self::createToken ( $uid );
			$updateSql = "update bx_app_session_info set sess_token='" . $token . "' where uid='" . $uid . "' and platform_id='" . $platformId . "'";
			$_SGLOBAL ['db']->query ( $updateSql );
		} else {
			$token = self::createToken ( $uid );
			$insertSql = "insert into bx_app_session_info (uid,platform_id,sess_token) values ('" . $uid . "','" . $platformId . "','" . $token . "')";
			$_SGLOBAL ['db']->query ( $insertSql );
		}
		
		return $token;
	}
	private static function createToken($uid) {
		return md5 ( $uid . PlatformEnvironment::getPlatformID () . date ( 'Y-m-d H:i:s:ms' ) );
	}
}

?>