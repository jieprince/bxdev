<?php
defined('APPLICATION_PATH') or define('APPLICATION_PATH', realpath(dirname(__FILE__). '/../../') . '/');

$ROOT_PATH_= str_replace ( 'oop/business/getProductData.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

define('IN_ECS', true);

include_once ($ROOT_PATH_. 'includes/init.php');
include_once ($ROOT_PATH_. 'baoxian/common.php');
include_once ($ROOT_PATH_. 'oop/classes/InterfaceProduct.php');

if(isset($_POST['insurer_code']) )
{
	$period_factor_code =isset($_REQUEST['period_factor_code']) ? $_REQUEST['period_factor_code']:-1;
	$career_factor_code = isset($_REQUEST['career_factor_code']) ? $_REQUEST['career_factor_code']:-1;
	$age_factor_code = isset($_REQUEST['age_factor_code']) ? $_REQUEST['age_factor_code']:-1;

	$product_id = intval($_REQUEST['product_id']);
	$insurer_code = $_POST['insurer_code'];
 	$obj_intface = new InterfaceProduct($insurer_code);
 	$dataList = $obj_intface->get_duty_price_list_by_product_id($age_factor_code, 
															 	$period_factor_code,
															 	$career_factor_code,
															 	$product_id);
												 	
	echo json_encode($dataList);
}

elseif(isset($_POST['query_career_code']))
{}
else
{}
	
