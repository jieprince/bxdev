<?php
/**
 * 响应的提示消息类
 * $Author: dingchaoyang $
 * 2014-11-07 $
 */
class ResMessage{
	public $title;
	public $content;

	function __construct(){

	}

	function responseResult(){
		return array('title'=>$this->title,'content'=>$this->content);
	}
}
?>