<?php
include_once 'software.php';
include_once 'reqUser.php';
// class ReqUser {
// 	public $uid;
// 	public $name;
// 	public $vip;
// 	function __construct() {
// 		$this->uid = isset ( $_REQUEST ['uid'] ) ? $_REQUEST ['uid'] : '';
// 		$this->name = isset ( $_REQUEST ['username'] ) ? $_REQUEST ['username'] : '';
// 	}
// }
class BaseRequest {
	public $software;
	public $user;
	function __construct() {
		$this->software = new Software ();
		$this->user = new ReqUser ();
	}
	function exeData() {
		$this->software->exeData ();
	}
}
?>