<?php
$ROOT_PATH_= str_replace ( 'oop/classes/InterfaceProduct.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_. 'oop/classes/SinosigProductBase.php');
class InterfaceProduct {
    
    public $obj;
    
	public function __construct($insurer_code)
    {
    	$this->obj=null;
        if($insurer_code == 'sinosig')
        {
        	$this->obj = new SinosigProductBase();
        }

    }
    
     public function get_duty_price_list_by_product_id($age_id, $period_id,$career_id, $product_id)
     {
     	return $this->obj->get_duty_price_list_by_product_id($age_id, $period_id,$career_id, $product_id);
     }
 
    
}
