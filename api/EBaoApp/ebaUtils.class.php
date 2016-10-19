<?php
/**
 * 工具类
 * $Author: dingchaoyang $
 * 2015-1-8 $
 */
class EbaUtils{
	static function nullObj($obj){
		return isset($obj)?$obj:'';
	}
	
	static function nullObjToInt($obj){
		return $obj?$obj:0;
	}
}

?>