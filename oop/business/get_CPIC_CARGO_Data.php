<?php
defined('APPLICATION_PATH') or define('APPLICATION_PATH', realpath(dirname(__FILE__). '/../../') . '/');
$ROOT_PATH_= str_replace ( 'oop/business/get_CPIC_CARGO_Data.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
//added by zhangxi, 20150609
define('IN_ECS', true);

include_once ($ROOT_PATH_. 'baoxian/common.php');
include_once ($ROOT_PATH_. 'oop/classes/CPIC_CARGO_Insurance.php');
$op = isset($_POST['op'])? $_POST['op'] : 0;
if($op == 'get_cargo_type')//货物类型
{
	$name_main = isset($_REQUEST['name_main']) ? $_REQUEST['name_main']:-1;//对应缴费期限

 	$obj = new CPIC_CARGO_Insurance();
 	//
 	if($name_main == -1)
 	{
 		$dataList = $obj->get_main_cargo_list();
 	}
 	else
 	{
 		$dataList = $obj->get_small_type_cargo_by_main($name_main);
 	}
    
	echo json_encode($dataList);
	
}
//获取国内货运险主条款列表
elseif($op == 'get_cargo_internal_main_clause')
{
	$obj = new CPIC_CARGO_Insurance();
	$dataList=$obj->get_internal_cargo_main_clause();
	echo json_encode($dataList);
	
}
else
{
	ss_log(__FILE__.", unknown request");
}

	