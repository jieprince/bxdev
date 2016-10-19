<?php
defined('APPLICATION_PATH') or define('APPLICATION_PATH', realpath(dirname(__FILE__). '/../../') . '/');
//comment by zhangxi, 这个文件好像不存在???
//require_once APPLICATION_PATH . 'oop/core/init.php';
$ROOT_PATH_= str_replace ( 'oop/business/get_EPICC_Data.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
//added by zhangxi, 20141209,包含的有点多，主要是想用数据库处理函数
define('IN_ECS', true);

//include_once ($ROOT_PATH_. 'includes/init.php');
include_once ($ROOT_PATH_. 'baoxian/common.php');
include_once ($ROOT_PATH_. 'oop/classes/EPICC_Insurance.php');
$op = isset($_POST['op'])? $_POST['op'] : 0;
if($op == 'get_destination_list')
{
 	$epiccobj = new EPICC_Insurance();
 	//根据用户的选项，获取到相关的信息
    $dataList = $epiccobj->get_destination_List();
	echo json_encode($dataList);
	
}
else if($op == 'get_shengen_destination_list')
{
	$epiccobj = new EPICC_Insurance();
 	//根据用户的选项，获取到相关的信息
    $dataList = $epiccobj->get_shengen_destination_List();
	echo json_encode($dataList);
}

	