<?php
$ROOT_PATH_= str_replace ( 'oop/classes/SinosigProductBase.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_. 'oop/classes/ProductBase.php');
class SinosigProductBase extends ProductBase {
    
    private $obj;
    public function __construct($insurer_code)
    {
    	$this->obj = new ProductBase;
    }
    
    public function get_duty_price_list_by_product_id($age_id, $period_id,$career_id, $product_id)
    {
    	return parent::get_duty_price_list_by_product_id($age_id, $period_id,$career_id, $product_id);
    }
	 
 
    
}
