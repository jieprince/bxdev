<?php
defined('APPLICATION_PATH') or define('APPLICATION_PATH', realpath(dirname(__FILE__). '/../../') . '/');
//comment by zhangxi, 这个文件好像不存在???
//require_once APPLICATION_PATH . 'oop/core/init.php';
$ROOT_PATH_= str_replace ( 'oop/business/get_NCI_Data.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
//added by zhangxi, 20141209,包含的有点多，主要是想用数据库处理函数
define('IN_ECS', true);

//include_once ($ROOT_PATH_. 'includes/init.php');
include_once ($ROOT_PATH_. 'baoxian/common.php');
include_once ($ROOT_PATH_. 'oop/classes/NCI_Insurance.php');
$op = isset($_POST['op'])? $_POST['op'] : 0;
if($op == 'get_duty_list')
{
	$period_factor_code = intval($_REQUEST['period_factor_code']);//对应缴费期限
	$gender_factor_code = $_REQUEST['gender_factor_code'];//对应性别，是男还是女, M-男，F-女
	$age_factor_code = intval($_REQUEST['age_factor_code']);//被保险人年龄
	$attribute_id = intval($_REQUEST['attribute_id']);//险种id
 	$nciobj = new NCI_Insurance();
 	//根据用户的选项，获取到相关的信息
    $dataList = $nciobj->get_Product_Price_List($attribute_id, 
    											$period_factor_code, 
    											$gender_factor_code,
    											$age_factor_code);
	echo json_encode($dataList);
	
}
//获取新华机构相关处理
elseif($op == 'get_organization')
{
	$parent_code = isset($_POST['parent_code'])? $_POST['parent_code'] : 0;
	$nciobj = new NCI_Insurance();
	$org_list = $nciobj->get_org_list($parent_code);
	echo json_encode($org_list);
}
//获取投保人地址信息相关处理
elseif($op == 'get_address_info')
{
	$action = isset($_POST['action'])? $_POST['action'] : 0;
	if($action == "get_province")
	{
	 	$nciobj = new NCI_Insurance();
	 	//根据用户的选项，获取到相关的信息
	    $dataList = $nciobj->get_province_list();
		echo json_encode($dataList);
	}
	elseif($action == "get_city_by_province")
	{
		$province_code = $_POST['province_code'];
		$nciobj = new NCI_Insurance();
	 	//根据用户的选项，获取到相关的信息
	    $dataList = $nciobj->get_city_list_by_province_code($province_code);
		echo json_encode($dataList);
	}
	elseif($action == "get_county_by_city")
	{
		$city_code = $_POST['city_code'];
		$nciobj = new NCI_Insurance();
	 	//根据用户的选项，获取到相关的信息
	    $dataList = $nciobj->get_county_list_by_city_code($city_code);
		echo json_encode($dataList);
	}
	
}
elseif($op == 'get_industry_info')
{
	$action = isset($_POST['action'])? $_POST['action'] : 0;
	if($action == "get_industry")//获取行业下拉列表
	{
	 	$nciobj = new NCI_Insurance();
	 	//根据用户的选项，获取到相关的信息
	    $dataList = $nciobj->get_industry_list();
		echo json_encode($dataList);
	}
	elseif($action == "get_career")
	{
		$industry_code = $_POST['industry_code'];
		$nciobj = new NCI_Insurance();
	 	//根据用户的选项，获取到相关的信息
	    $dataList = $nciobj->get_career_list_by_industry_code($industry_code);
		echo json_encode($dataList);
	}

	
}
elseif($op == 'get_bank_info')
{
	$action = isset($_POST['action'])? $_POST['action'] : 0;
	if($action == "get_bank_list")//获取行业下拉列表
	{
	 	$nciobj = new NCI_Insurance();
	 	//根据用户的选项，获取到相关的信息
	    $dataList = $nciobj->get_bank_list();
		echo json_encode($dataList);
	}
	
	
}
elseif($op == 'check_period')
{
	$age_factor_code = isset($_POST['age_factor_code'])? $_POST['age_factor_code'] : 0;
	$product_id = isset($_POST['product_id'])? $_POST['product_id'] : 0;
	$nciobj = new NCI_Insurance();
	$pay_period_list = $nciobj->get_pay_period_list($product_id, $age_factor_code);
	echo json_encode($pay_period_list);
}
else
{
	ss_log(__FILE__.", unknown request");
}

	
