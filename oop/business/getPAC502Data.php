<?php
defined('APPLICATION_PATH') or define('APPLICATION_PATH', realpath(dirname(__FILE__). '/../../') . '/');
//comment by zhangxi, 这个文件好像不存在???
//require_once APPLICATION_PATH . 'oop/core/init.php';
$ROOT_PATH_= str_replace ( 'oop/business/getPAC502Data.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
//added by zhangxi, 20141209,包含的有点多，主要是想用数据库处理函数
define('IN_ECS', true);

include_once ($ROOT_PATH_. 'includes/init.php');
include_once ($ROOT_PATH_. 'baoxian/common.php');
include_once ($ROOT_PATH_. 'oop/classes/PACGroupInsurance.php');

//mod by zhangxi, 20150708, 增加平安个财产品的处理，同时也不影响原来的Y502产品
if(isset($_POST['insurer_code']) && $_POST['insurer_code'] == 'PAC04')
{
	$period_factor_code = $_REQUEST['period_factor_code'];
	$product_code = $_REQUEST['product_code'];
	$attribute_id = intval($_REQUEST['attribute_id']);
 	$pacGroupInsurance = new PACGroupInsurance();
    $dataList = $pacGroupInsurance-> pac04_getProductPriceList($attribute_id, 
    											$period_factor_code, 
    											$product_code);
	echo json_encode($dataList);
}
//added by zhangxi, 20150804, 增加Y502产品前端职业类别的查询功能
elseif(isset($_POST['query_career_code']))
{
	$query_career_code = $_POST['query_career_code'];
	$pacGroupInsurance = new PACGroupInsurance();
	if($query_career_code == 'level_one')//查询第一级的请求
	{
		$dataList = $pacGroupInsurance->pac_query_level_one_career_list();
		echo json_encode($dataList);
		
	}
	elseif($query_career_code == 'level_two')//查询第二级的请求
	{
		$level_one = $_POST['level_one']; 
		$dataList = $pacGroupInsurance->pac_query_level_two_career_list($level_one);
		echo json_encode($dataList);
	}
	elseif($query_career_code == 'level_three')//查询第三级别的请求
	{
		$level_two = $_POST['level_two'];
		$dataList = $pacGroupInsurance->pac_query_level_three_career_list($level_two);
		echo json_encode($dataList);
	}
}
else
{
	$period_factor_code = intval($_REQUEST['period_factor_code']);
	$career_factor_code = intval($_REQUEST['career_factor_code']);
	$attribute_id = intval($_REQUEST['attribute_id']);
 	$pacGroupInsurance = new PACGroupInsurance();
    $dataList = $pacGroupInsurance-> getProductPriceList($attribute_id, 
    											$period_factor_code, 
    											$career_factor_code);
	echo json_encode($dataList);
}
	
