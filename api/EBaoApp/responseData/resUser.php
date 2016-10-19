<?php
/**
 * 响应的用户信息类
 * $Author: dingchaoyang $
 * 2014-11-07 $
 */
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/resUser.php', '', str_replace ( '\\', '/', __FILE__ ) );
include_once ($ROOT_PATH_ . 'includes/lib_code.php');
class ResUser {
	public $uid;
	public $name;
	public $vip;
	public $encryptedUid;
	private static $instance;
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new ResUser ();
		}
		
		return self::$instance;
	}
	private function __construct() {
		$this->uid = isset ( $_SESSION ['user_id'] ) && ! empty ( $_SESSION ['user_id'] ) ? $_SESSION ['user_id'] : '';
		$this->name = isset ( $_SESSION ['user_name'] ) && ! empty ( $_SESSION ['user_name'] ) ? $_SESSION ['user_name'] : '';
		$this->vip = isset ( $_SESSION ['is_cheack'] ) && ! empty ( $_SESSION ['is_cheack'] ) ? $_SESSION ['is_cheack'] : '0';
		if ($this->uid) {
			$this->encryptedUid = ebaoins_encrypt ( $this->uid, "syden1981" );
		} else {
			$this->encryptedUid = '';
		}
	}
	private function __clone() {
	}
	function responseResult() {
		return array (
				'uid' => $this->uid,
				'userName' => $this->name,
				'vip' => $this->vip,
				'encryptUid' => $this->encryptedUid 
		);
	}
}
?>