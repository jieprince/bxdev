<?php
//added by zhangxi, 20141210,
//将上传文件功能独立出来

//检查审核装填，如果没审核通过，则不允许购买产品。
$user_id = $_SGLOBAL['supe_uid'];
if(!$user_id)
{
	showmessage("请登录后进行操作！");
}

////////////////////////////////////////////////////////////////////////////////
$product_id = intval($_GET['product_id']);//每个产品，也就是款式的id。
if(!$product_id)
{
	$product_id = intval($_POST['product_id']);//每个产品，也就是款式的id。
}

if($product_id)//产品的id
{
	ss_log("product_id: ".$product_id);
	/////////////////得到产品的xinxi///////////////////////////////////////////
	$wheresql = "p.product_id='$product_id'";
	
	$sql = "SELECT *,patt.* FROM ".tname('insurance_product_base')." p
	inner JOIN ".tname('insurance_product_additional')." padd ON padd.product_id=p.product_id
	inner JOIN ".tname('insurance_product_attribute')."  patt ON patt.attribute_id=p.attribute_id
	WHERE $wheresql";
	
	$query = $_SGLOBAL['db']->query($sql);

	$product = $product_arr = $_SGLOBAL['db']->fetch_array($query);
	
	//根据产品得到产品的属性
	$attribute_id = $product['attribute_id'];
	$attribute_type = $product['attribute_type'];
	$insurer_code = $product['insurer_code'];
	$insurer_name = $product['insurer_name'];
	$partner_code = $product['partner_code'];
	$product_code = $product['product_code'];
	$attribute_name = $product['attribute_name'];
	$allow_sell = $product['allow_sell'];
	
	ss_log("in cp_product_buy, product_name: ".$product['product_name']);

}

//////////////////////////////////////////////////////////////////////
$server_time = time();//second, $_SGLOBAL['timestamp'];
ss_log("server_time: ".$server_time);

$servertime_str = date("Y-m-d H:i:s",$server_time);
ss_log("servertime_str: ".$servertime_str);


ss_log("uploadfile_insured_xls");
$FILE = $_FILES['inputExcel'];
$result_attr = post_policy_upload_file_xls($insurer_code,$product, $FILE);
