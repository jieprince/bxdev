<?php
/**
 * 输出产品的
 * 	投保须知：cover_note
 *	特别约定：limit_note
 *	产品简介：description
 *	简单介绍：product_characteristic
 *	投保声明：insurance_declare
 *	保险条款：insurance_clauses
 *	理赔指南：claims_guide
 * $Author: dingchaoyang $
 * 2014-11-19 $
 */
define('IN_ECS', true);

include_once(dirname(__FILE__) . '/includes/init.php');

//goodid
$goodId = isset($_REQUEST['goodid']) && !empty($_REQUEST['goodid']) ? $_REQUEST['goodid'] : '';
//查询的字段
$fieldName =  isset($_REQUEST['field']) && !empty($_REQUEST['field']) ? $_REQUEST['field'] : '';
global $GLOBALS;
$query = "
		SELECT " . 
		  $fieldName . 
		" FROM
		  bx_goods,
		  t_insurance_product_attribute 
		WHERE bx_goods.tid = t_insurance_product_attribute.attribute_id 
		  AND bx_goods.goods_id = '" .  $goodId . "'" ;
// echo $query;
$resSet =$GLOBALS['db']->getRow($query);
if ($resSet){
	echo $resSet[$fieldName];
}

?>