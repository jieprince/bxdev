<?php
class PlatformEnvironment {
	private static $userAgent;
	public static function isMobilePlatform() {
		if (strstr ( $_SERVER ['HTTP_USER_AGENT'], 'EBA_Android' ) || strstr ( $_SERVER ['HTTP_USER_AGENT'], 'EBA_iPhone' )) {
			return TRUE;
		}
		return false;
	}
	public static function isMobileHFivePlatform() {
		if (isset ( $_REQUEST ['platformId'] ) && ($_REQUEST ['platformId'] == '103' || $_REQUEST ['platformId'] == '123')) {
			return true;
		}
		return false;
	}
	
	// 返回客户端版本号
	public static function getEditionID() {
		self::ParseUseragent ();
		
		if (self::$userAgent) {
			return self::$userAgent ['editionId'];
		}
	}
	
	// 返回platformid
	public static function getPlatformID() { // modify by wangcya, 20150119
		include_once 'constant.php';
		
		$is_weixin = isset ( $_REQUEST ['is_weixin'] ) ? $_REQUEST ['is_weixin'] : 0;
		if ($is_weixin) {
			return WEIXINPLATFORM;
		}
		
		if (isset ( $_REQUEST ['platformId'] ) && ! empty ( $_REQUEST ['platformId'] )) {
			return $_REQUEST ['platformId'];
		}
		
		self::ParseUseragent ();
		
		if (self::$userAgent) {
			return self::$userAgent ['platformId'];
		} else // start add by wangcya, 20150119
{
			return PLATFORM_PC;
		} // end add by wangcya, 20150119
	}
	
	// 返回device token
	public static function getDeviceToken() {
		self::ParseUseragent ();
		if (self::$userAgent) {
			return self::$userAgent ['token'];
		}
	}
	
	// 返回access token
	public static function getAccessToken() {
		self::ParseUseragent ();
		if (self::$userAgent) {
			return self::$userAgent ['accessToken'];
		}
	}
	
	// 解析useragent
	public static function ParseUseragent() {
		if (self::isMobilePlatform ()) {
			$userAgent = explode ( ';', $_SERVER ['HTTP_USER_AGENT'] );
			foreach ( $userAgent as $key => $value ) {
				if (strstr ( $value, '=' )) {
					$temp = explode ( '=', $value );
					self::$userAgent [$temp [0]] = $temp [1];
				}
			}
			
			return self::$userAgent;
		}
	}
}
?>