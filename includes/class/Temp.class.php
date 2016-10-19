<?php


/**
 * by yes123 2014-12-10
 * 临时类
 * 
 * 
 */
include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
class Temp {

	function Temp() {
	}

	//t_user_info 添加代理人ID
	public static function addAgentUid() {
		// applicant_uid 投保人uid
		// agent_uid 代理人uid

		$sql = "SELECT applicant_uid,agent_uid FROM t_insurance_policy ";
		$temp = $GLOBALS['db']->getAll($sql);

		foreach ($temp as $value) {
			$sql = "UPDATE t_user_info SET agent_uid = " . $value['agent_uid'] . "  WHERE uid='" . $value['applicant_uid'] . "'";
			echo $sql . "<br/>";
			$GLOBALS['db']->query($sql);
		}

	}
	
	//给发票添加保险公司code
	public static function addInsurerCode(){
		//获取保险公司为null的发票
		$sql = "SELECT id  FROM bx_receipt WHERE insurer_code='' OR insurer_code IS NULL";
		$invoiceId_list = $GLOBALS['db']->getAll($sql);
	
		foreach ( $invoiceId_list as $value ) {
       		$sql ="SELECT DISTINCT insurer_code,insurer_name FROM t_insurance_policy WHERE policy_id IN (" .
       				" SELECT policy_id  FROM bx_invoice_order_policy WHERE invoice_id = '$value[id]') ";
       		$insurer_list = $GLOBALS['db']->getAll($sql);
			
			foreach ( $insurer_list as $key => $insurer ) {
       			$sql = "UPDATE bx_receipt SET insurer_code='$insurer[insurer_code]',insurer_name='$insurer[insurer_name]' WHERE id='$value[id]'";
       			echo $sql . "<br/>";
       			$GLOBALS['db']->query($sql);
			}
       		
		}
	}
	
	//给产品加销量字段，同时更新销量
	public static function addSalesVolume(){
		$sql = "SELECT goods_id,tid FROM bx_goods ";
		$ids = $GLOBALS['db']->getAll($sql);
		foreach ( $ids as $key => $value ) {
       		$sql="SELECT count(rec_id) FROM ".$GLOBALS['ecs']->table('order_goods')." WHERE goods_id=$value[goods_id] ";
			$count = $GLOBALS['db']->getOne($sql);
			
			//更新bx_goods
			$sql = "UPDATE bx_goods SET goods_sales_volume = " . $count . "  WHERE goods_id='" . $value['goods_id'] . "'";
			$GLOBALS['db']->query($sql);
			echo '更新bx_goods的销量：'.$sql . "<br/>";
			
			//更新t_insurance_product_attribute
			$sql = "UPDATE t_insurance_product_attribute SET attribute_sales_volume = " . $count . "  WHERE attribute_id='" . $value['tid'] . "'";
			$GLOBALS['db']->query($sql);
			echo '更新t_insurance_product_attribute的销量：'.$sql . "<br/>";
			
		}
	}
	
	//发票快递公司信息更新
	public static function updateInvoiceMailInfo(){
		$sql = "SELECT * FROM bx_receipt WHERE receipt_assigned=1 AND express_company IS NOT NULL AND mail_sn IS NOT NULL AND express_company<>''";
		$receipt_list = $GLOBALS['db']->getAll($sql);
		if($receipt_list){
			foreach ( $receipt_list as $key => $value ) {
       			$sql = "INSERT INTO " . $GLOBALS['ecs']->table('invoice_send_info') . " (express_company, mail_sn, mail_time,invoice_id) VALUES " .
							"('$value[express_company]','$value[mail_sn]','$value[mail_time]','$value[id]')";
				echo $sql."<br/>";
				$r = $GLOBALS['db']->query($sql);
			}
			
		}
		
	}
	
	public static function getAllMoblePhone(){
		$sql = "SELECT * FROM bx_users";
		$users_list = $GLOBALS['db']->getAll($sql);	
		foreach ( $users_list as $key => $value ) {
       		if(preg_match("/1[3458]{1}\d{9}$/",$value['user_name'])){ 
       			if(!$value['mobile_phone']){
       				$sql = "UPDATE bx_users SET mobile_phone = " . $value['user_name'] . "  WHERE user_id='" . $value['user_id'] . "'";
					echo $sql."<br/>";
					$GLOBALS['db']->query($sql);
       			}
			}else{  
				
			}
		}
	}
}
?>