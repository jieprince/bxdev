<?php
defined('APPLICATION_PATH') or define('APPLICATION_PATH', realpath(dirname(__FILE__). '/../../') . '/');

//require_once APPLICATION_PATH . 'oop/core/init.php';
$ROOT_PATH_= str_replace ( 'oop/business/get_SINO_Data.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
//added by zhangxi, 20141209,����е�࣬��Ҫ��������ݿ⴦�?��
define('IN_ECS', true);

//$type = isset($_REQUEST['type'])?$_REQUEST['type']:"";
//include_once ($ROOT_PATH_. 'includes/init.php');  @liuhui  delete  20150330   影响微信端华安建工险用户id 因为华安建工险这一步初始化的不是微信端的init 而是pc端的  逻辑错误
include_once ($ROOT_PATH_. 'baoxian/common.php');

if(!empty($_REQUEST['action']))
{
	$action = $_REQUEST['action'];
	if($action == 'get_province_list')
	{
		$data = huaan_get_province_list();
		echo json_encode($data);
	
	}
	elseif($action == 'get_city_list')
	{
		$data = huaan_get_city_list_by_province(urldecode($_REQUEST['province_name']));
		echo json_encode($data);
	}
	exit(0);
}
else
{
	$cost_id = intval($_REQUEST['cost_id']);
	$period_id = intval($_REQUEST['period_id']);
	$product_id = intval($_REQUEST['product_id']);
	//echo $cost_id;
	$dataList=huaan_get_product_duty_price($cost_id,$period_id,$product_id);
	echo json_encode($dataList);
	exit(0);
}
function huaan_get_province_list()
{
	global $_SGLOBAL;
	//mod by zhangxi, 20150121,根据要求放开
	$sql="SELECT DISTINCT province_name FROM t_location_info_huaan";
	//$sql="SELECT DISTINCT province_name FROM t_location_info_huaan";
	
	$province_query = $_SGLOBAL['db']->query($sql);
	$province_list = array();
	
	//////////////////////////////////////////////////////////////////////////
	while ($province_row = $_SGLOBAL['db']->fetch_array($province_query))
	{
	
    	$province_list[] = $province_row['province_name'];
    	
	}

	return $province_list;
}	

function huaan_get_city_list_by_province($province_name)
{
	global $_SGLOBAL;
	//echo $province_name;
	$sql="SELECT DISTINCT city_name FROM t_location_info_huaan WHERE province_name='$province_name'";
	
	$city_query = $_SGLOBAL['db']->query($sql);
	$city_list = array();
	
	//////////////////////////////////////////////////////////////////////////
	while ($city_row = $_SGLOBAL['db']->fetch_array($city_query))
	{
	
    	$city_list[] = $city_row['city_name'];
    	
	}

	return $city_list;
}
	
function huaan_get_product_duty_list($cost_id, $period_id, $product_id)
{
	global $_SGLOBAL;
	///
	
	$sql="SELECT * FROM t_insurance_product_duty AS pd
	INNER JOIN t_insurance_duty AS id ON pd.duty_id=id.duty_id
	WHERE pd.product_id='$product_id'";
	
	$product_duty_query = $_SGLOBAL['db']->query($sql);
	$arr_list_product_duty = array();
	
	//////////////////////////////////////////////////////////////////////////
	while ($duty_row = $_SGLOBAL['db']->fetch_array($product_duty_query))
	{
	
		//获取每个产品责任id，结合职业类别，保险时长，获取保额/保费下拉列表信息
		//得到产品责任id:
		$product_duty_id = $duty_row['product_duty_id'];
		//echo "duty id:".$product_duty_id;
		$sql="SELECT product_duty_price_id,amount,premium FROM t_insurance_product_duty_price
		WHERE product_duty_id='$product_duty_id' AND " .
		"product_career_id='$cost_id' AND " .
		"product_period_id='$period_id' ORDER BY view_order";
		$duty_price_query = $_SGLOBAL['db']->query($sql);
		
		$list_product_duty_price = array();
		
	   		
		while($duty_price_row = $_SGLOBAL['db']->fetch_array($duty_price_query))
		{
			//mod by zhangx, 20150109, remove project code
			$duty_price_row['amount'] = substr($duty_price_row['amount'] ,0,strrpos($duty_price_row['amount'],','));
			$list_product_duty_price[] = $duty_price_row;
		}
	
	
    	$arr_list_product_duty[] = array(	'duty_code'=>$duty_row['duty_code'],
									    	'duty_name'=>$duty_row['duty_name'],
									    	'duty_price_list' => $list_product_duty_price,
									    	);
    	
	}

	return $arr_list_product_duty;
}
	
function huaan_get_product_duty_price($cost_id, $period_id, $product_id)
{
		
		return huaan_get_product_duty_list($cost_id, $period_id, $product_id);
}