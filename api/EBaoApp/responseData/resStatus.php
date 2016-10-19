<?php
/**
 * 响应的状态类
 * $Author: dingchaoyang $
 * 2014-11-07 $
 */

class ResStatus{
	public $code;
	public $message;

	function __construct($code,$message){
		$this->code = $code;
		$this->message = $message;
	}

	function responseResult(){
		return array('code'=>$this->code,
				'message'=>$this->message->responseResult());
	}
}
?>