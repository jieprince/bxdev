<?php
/**
 * 输出产品的
 *	保障范围
 * $Author: dingchaoyang $
 * 2014-11-19 $
 */
define('IN_ECS', true);

include_once(dirname(__FILE__) . '/includes/init.php');

//产品id
$productId = isset($_REQUEST['productid']) && !empty($_REQUEST['productid']) ? $_REQUEST['productid'] : '';

global $GLOBALS;
$query = "
		SELECT 
		  * 
		FROM
		  t_insurance_product_duty 
		WHERE product_id = '" . $productId . "'"; 
// echo $query;
$resSet =$GLOBALS['db']->getAll($query);
// print_r($resSet) ;
if ($resSet){
// 	echo "<table>";
	foreach ($resSet as $key => $value) {
// 		echo "<tr>";
// 		echo "<td>";
		echo "<b>" . $value['duty_name'] . "</b>";
		echo "</br>";
// 		echo "</td>";
// 		echo "</tr>";
// 		echo "<tr>";
// 		echo "<td>";
		echo $value['amount'];
		echo "</br>";
// 		echo "</td>";
// 		echo "</tr>";
		
// 		echo "<tr>";
// 		echo "<td>";
		echo $value['duty_note'];
		echo "</br>";
// 		echo "</td>";
// 		echo "</tr>";

// 		echo "<tr>";
// 		echo "<td>";
// 		echo "<br/>";
// 		echo "</td>";
// 		echo "</tr>";
		echo "</p>";
		echo "</p>";
	}
	
// 	echo "</table>";
}

?>