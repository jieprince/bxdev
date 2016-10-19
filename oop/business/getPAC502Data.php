<?php
defined('APPLICATION_PATH') or define('APPLICATION_PATH', realpath(dirname(__FILE__). '/../../') . '/');
//comment by zhangxi, ����ļ����񲻴���???
//require_once APPLICATION_PATH . 'oop/core/init.php';
$ROOT_PATH_= str_replace ( 'oop/business/getPAC502Data.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
//added by zhangxi, 20141209,�������е�࣬��Ҫ���������ݿ⴦����
define('IN_ECS', true);

include_once ($ROOT_PATH_. 'includes/init.php');
include_once ($ROOT_PATH_. 'baoxian/common.php');
include_once ($ROOT_PATH_. 'oop/classes/PACGroupInsurance.php');

//mod by zhangxi, 20150708, ����ƽ�����Ʋ�Ʒ�Ĵ���ͬʱҲ��Ӱ��ԭ����Y502��Ʒ
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
//added by zhangxi, 20150804, ����Y502��Ʒǰ��ְҵ���Ĳ�ѯ����
elseif(isset($_POST['query_career_code']))
{
	$query_career_code = $_POST['query_career_code'];
	$pacGroupInsurance = new PACGroupInsurance();
	if($query_career_code == 'level_one')//��ѯ��һ��������
	{
		$dataList = $pacGroupInsurance->pac_query_level_one_career_list();
		echo json_encode($dataList);
		
	}
	elseif($query_career_code == 'level_two')//��ѯ�ڶ���������
	{
		$level_one = $_POST['level_one']; 
		$dataList = $pacGroupInsurance->pac_query_level_two_career_list($level_one);
		echo json_encode($dataList);
	}
	elseif($query_career_code == 'level_three')//��ѯ�������������
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
	
