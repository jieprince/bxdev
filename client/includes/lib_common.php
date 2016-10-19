<?php
/**
 * mobile 公共函数库
 */

//add yes123 2015-01-12 微信分页
function mobilePagebar($record_count, $url,$condition='') {
	
	if($condition){
		foreach ($condition AS $key => $value)
		{
			if($key!='act'){
				$url.="&".$key."=".$value;
			}
		}
	}
		
	$page_num = '10';
	$page = !empty ($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
	$pages = ceil($record_count / $page_num);
	if ($page <= 0) {
		$page = 1;
	}
	if ($pages == 0) {
		$pages = 1;
	}
	if ($page > $pages) {
		$page = $pages;
	}
	$pagebar = get_wap_pager($record_count, $page_num, $page, $url, 'page');
	return $pagebar;
}


?>
