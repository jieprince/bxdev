<?php
class ReqUser {
	public $uid;
	public $name;
	public $vip;
	function __construct() {
		$this->uid = isset ( $_REQUEST ['uid'] ) ? $_REQUEST ['uid'] : '';
		$this->name = isset ( $_REQUEST ['username'] ) ? $_REQUEST ['username'] : '';
	}
}
?>