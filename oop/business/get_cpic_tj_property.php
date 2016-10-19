<?php
defined('APPLICATION_PATH') or define('APPLICATION_PATH', realpath(dirname(__FILE__). '/../../') . '/');

//require_once APPLICATION_PATH . 'oop/core/init.php';
$ROOT_PATH_= str_replace ( 'oop/business/get_cpic_tj_property.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
//added by zhangxi, 20150629
define('IN_ECS', true);
include_once ($ROOT_PATH_. 'baoxian/common.php');

if(!empty($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 'get_province_list')
	{
		$data = cpic_tj_property_get_province_list();
		echo json_encode($data);
	
	}
	elseif($action == 'get_city_list')
	{
		$data = cpic_tj_property_get_city_list_by_provincecode(urldecode($_REQUEST['province_code']));
		echo json_encode($data);
	}
	elseif($action == 'get_county_list')
	{
		$data = cpic_tj_property_get_county_list_by_citycode(urldecode($_REQUEST['city_code']));
		echo json_encode($data);
	}
	exit(0);
}
else
{
	exit(0);
}
function cpic_tj_property_get_province_list()
{
	global $_SGLOBAL;
	//mod by zhangxi, 20150121,根据要求放开
	$sql="SELECT DISTINCT province_code,province_name FROM t_insurance_cpic_tj_property_citycode";

	$query = $_SGLOBAL['db']->query($sql);
	$province_list = array();
	
	//////////////////////////////////////////////////////////////////////////
	while ($row = $_SGLOBAL['db']->fetch_array($query))
	{
	
    	$province_list[] = $row;
    	
	}

	return $province_list;
}	

function cpic_tj_property_get_city_list_by_provincecode($province_code)
{
	global $_SGLOBAL;
	//echo $province_name;
	$sql="SELECT DISTINCT city_code,city_name FROM t_insurance_cpic_tj_property_citycode WHERE province_code='$province_code'";
	
	$query = $_SGLOBAL['db']->query($sql);
	$city_list = array();
	
	//////////////////////////////////////////////////////////////////////////
	while ($row = $_SGLOBAL['db']->fetch_array($query))
	{
	
    	$city_list[] = $row;
    	
	}

	return $city_list;
}
function cpic_tj_property_get_county_list_by_citycode($city_code)
{
	global $_SGLOBAL;
	//echo $province_name;
	$sql="SELECT DISTINCT county_code,county_name FROM t_insurance_cpic_tj_property_citycode WHERE city_code='$city_code'";
	
	$query = $_SGLOBAL['db']->query($sql);
	$county_list = array();
	
	//////////////////////////////////////////////////////////////////////////
	while ($row = $_SGLOBAL['db']->fetch_array($query))
	{
	
    	$county_list[] = $row;
    	
	}

	return $county_list;
}
	