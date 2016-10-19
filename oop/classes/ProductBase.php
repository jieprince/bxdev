<?php

/*
 * 所有保险产品的基类
 * comment by zhangxi, 20150916, use now
 */
$ROOT_PATH_= str_replace ( 'oop/classes/ProductBase.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_. 'oop/classes/DB.php');
/**
 * Description of ProductBase
 *
 * @author ytyan
 */
class ProductBase {
    
    public  $attributeId = 0;
    public  $productAttribute = array(); //产品属性
    public  $allData = array();
    public  $db;
    
    
    /*
     * 构造函数
     * @access  public
     * @param   string      $attributeId       产品属性id
     */
    public function __construct()
    {
    	global $_SGLOBAL;
       // $this->db = DB::getInstance();
        $this->db = $_SGLOBAL['db'];
        //$this->attributeId = $attributeId;
        //$this->getProductAttribute();
    }
    
    //获取产品属性
    public function getProductAttribute()
    {
        $sql = 'SELECT * FROM  t_insurance_product_attribute  WHERE attribute_id =' . $this->attributeId;
        $this->db->query($sql);
        $this->productAttribute = $this->db->first();
    }
    
    //获取产品属性附加信息
    public function getProductAdditional()
    {
        $this->getProducts();
        foreach ($this->allData['product_data'] as $product) {
            $sql = 'SELECT * FROM  t_insurance_product_additional WHERE product_id =' . $product['product_id'];
            $this->db->query($sql);
            $this->allData['additional'][$product['product_id']] = $this->db->first();
        }
    }
    
    //获取产品属性对应的产品列表
    public function getProducts(){
        switch ($this->productAttribute['attribute_type'])
        {
            case "product":
                break;
            case "plan":
                $this->getPlanProducts();
                break;
            case "Y502":
                break;
            case "mproduct":
                break;
            
        }
    }
    
    //获取计划型产品
    public function getPlanProducts()
    {
        $sql = "SELECT * FROM t_insurance_product_base WHERE attribute_id = " . $this->attributeId;
        $this->db->query($sql);
        $this->allData['product_data'] = $this->db->results();
    }
    
    //获取产品责任
    public function getProductDuties($productId)
    {
        $sql = 'SELECT  *  FORM t_insurance_product_duty'
            . ' WHERE product_id = ' . $productId;
        $this->db->query($sql);
        $duties = $this->db->results();
        return $duties;
    }
    
    public function get_duty_price_list_by_product_id($age_factor_code, 
													    $period_factor_code,
													    $career_factor_code, 
													    $product_id)
    {
    	global $_SGLOBAL;
		$sql="SELECT * FROM t_insurance_product_duty AS pd
		INNER JOIN t_insurance_duty AS id ON pd.duty_id=id.duty_id
		WHERE pd.product_id='$product_id'";
		
		//$product_duty_query = $this->db->query($sql);
		$product_duty_query = $_SGLOBAL['db']->query($sql);
		$arr_list_product_duty = array();
		
		
		//////////////////////////////////////////////////////////////////////////
		while ($duty_row = $_SGLOBAL['db']->fetch_array($product_duty_query))
		//while ($duty_row = $this->db->fetch_array($product_duty_query))//循环一个产品下的多个责任
		{
			if($period_factor_code != -1)
			{
				$sql="SELECT product_influencingfactor_id FROM t_insurance_product_influencingfactor
	    		WHERE product_id='$product_id' AND product_influencingfactor_type='period' AND factor_code='$period_factor_code'";
	    		$period_query = $_SGLOBAL['db']->query($sql);
	    		$period_row = $_SGLOBAL['db']->fetch_row($period_query);
	    		$product_period_id = $period_row[0];
			}
			else
			{
				$product_period_id = -1;
			}
			
    		if($career_factor_code != -1)
    		{
    			$sql="SELECT product_influencingfactor_id FROM t_insurance_product_influencingfactor
	    		WHERE product_id='$product_id' AND product_influencingfactor_type='career' AND factor_code='$career_factor_code'";
	    		$career_query = $_SGLOBAL['db']->query($sql);
	    		$career_row = $_SGLOBAL['db']->fetch_row($career_query);
	    		$product_career_id = $career_row[0];
    		}
    		else
    		{
    			$product_career_id = -1;
    		}
    		
			if($age_factor_code != -1)
			{
				$sql="SELECT product_influencingfactor_id FROM t_insurance_product_influencingfactor
	    		WHERE product_id='$product_id' AND product_influencingfactor_type='age' AND factor_code='$age_factor_code'";
	    		$age_query = $_SGLOBAL['db']->query($sql);
	    		$age_row = $_SGLOBAL['db']->fetch_row($age_query);
	    		$product_age_id = $age_row[0];
			}
			else
			{
				$product_age_id = -1;
			}
			
			$product_duty_id = $duty_row['product_duty_id'];
			$where_sql = "WHERE product_duty_id='$product_duty_id' ";
			
			if($product_age_id != -1)
			{
				$where_sql .= "AND product_age_id='$product_age_id'";
			}
			if($product_period_id != -1)
			{
				$where_sql .= "AND product_period_id='$product_period_id'";
			}
			if($product_career_id != -1)
			{
				$where_sql .= "AND product_career_id='$product_career_id'";
			}
			$sql="SELECT product_duty_price_id,amount,premium FROM t_insurance_product_duty_price "
			.$where_sql." ORDER BY view_order";
			//echo $sql;
			//$duty_price_query = $this->db->query($sql);
			$duty_price_query = $_SGLOBAL['db']->query($sql);
			
			$list_product_duty_price = array();
			
		   		
			//while($duty_price_row = $this->db->fetch_array($duty_price_query))
			while($duty_price_row = $_SGLOBAL['db']->fetch_array($duty_price_query))
			{
				$list_product_duty_price[] = $duty_price_row;
			}
		
		
	    	$arr_list_product_duty[] = array(	'duty_code'=>$duty_row['duty_code'],
										    	'duty_name'=>$duty_row['duty_name'],
										    	'duty_price_list' => $list_product_duty_price,
										    	);
	    	
		}
	
		return $arr_list_product_duty;
	    	
    }
    
     public function get_duty_price_list_by_attr_id($attribute_id, $period_factor_code, $career_factor_code)
    {
		//获取产品列表信息 
		global $_SGLOBAL;
	
		///找产品列表
		$sql="SELECT * FROM t_insurance_product_base AS pb
		INNER JOIN t_insurance_product_additional AS pa ON pa.product_id=pb.product_id
		WHERE pb.attribute_id='$attribute_id'";
		$product_query = $_SGLOBAL['db']->query($sql);
		$arr_list_product = array();
		//当前险种下的所有产品
		while ($product_row = $_SGLOBAL['db']->fetch_array($product_query))
		{
			//echo "product_id".$product_row['product_id'];
			//根据产品id，获取产品责任信息,可能多个责任信息
			$arr_list_product_duty = $this->get_product_duty_list($product_row['product_id'], $product_row['product_code'],$period_factor_code,$career_factor_code);
			
			$arr_list_product[] = array(
									'product_id'=>$product_row['product_id'],
									'product_code'=>$product_row['product_code'],
									'product_name'=>$product_row['product_name'],
									'product_type'=>$product_row['product_type'],
									'product_duty_list' =>$arr_list_product_duty,//具体每一个产品的责任信息
									);
		}
		
		return $arr_list_product;
		
    }
    
     private function get_product_duty_list($product_id,$product_code, $period_factor_code,$career_factor_code)
    {
    	global $_SGLOBAL;
    	////////////////////////////////////////////////////////////////////
    	
    	$sql="SELECT * FROM t_insurance_product_duty AS pd
    	INNER JOIN t_insurance_duty AS id ON pd.duty_id=id.duty_id
    	WHERE pd.product_id='$product_id'";
    	
    	$product_duty_query = $_SGLOBAL['db']->query($sql);
    	$arr_list_product_duty = array();
    	
    	
    	//////////////////////////////////////////////////////////////////////////
    	while ($duty_row = $_SGLOBAL['db']->fetch_array($product_duty_query))
    	{
    		//根据产品id，产品影响因素类型，和影响因素代码，查出影响因素id
    		//$product_id = $product_row['product_id'];
    		$sql="SELECT product_influencingfactor_id FROM t_insurance_product_influencingfactor
    		WHERE product_id='$product_id' AND product_influencingfactor_type='period' AND factor_code='$period_factor_code'";
    		
    		//ss_log($sql);
    		
    		$period_query = $_SGLOBAL['db']->query($sql);
    		$period_row = $_SGLOBAL['db']->fetch_row($period_query);
    		$product_period_id = $period_row[0];
    	
    		$sql="SELECT product_influencingfactor_id FROM t_insurance_product_influencingfactor
    		WHERE product_id='$product_id' AND product_influencingfactor_type='career' AND factor_code='$career_factor_code'";
    		$career_query = $_SGLOBAL['db']->query($sql);
    		$career_row = $_SGLOBAL['db']->fetch_row($career_query);
    		$product_career_id = $career_row[0];
    	
    		//获取每个产品责任id，结合职业类别，保险时长，获取保额/保费下拉列表信息
    		//得到产品责任id:
    		$product_duty_id = $duty_row['product_duty_id'];
    	
    		$sql="SELECT product_duty_price_id,amount,premium FROM t_insurance_product_duty_price
    		WHERE product_duty_id='$product_duty_id' AND " .
    		"product_career_id='$product_career_id' AND " .
    		"product_period_id='$product_period_id' ORDER BY view_order";
    		$duty_price_query = $_SGLOBAL['db']->query($sql);
    		
    		$list_product_duty_price = array();
    		
    	   		
    		while($duty_price_row = $_SGLOBAL['db']->fetch_array($duty_price_query))
    		{
    			$list_product_duty_price[] = $duty_price_row;
    		}
    	
    	
	    	$arr_list_product_duty[] = array(	'duty_code'=>$duty_row['duty_code'],
										    	'duty_name'=>$duty_row['duty_name'],
										    	//'duty_row'=>$duty_row,
										    	'duty_price_list' => $list_product_duty_price,
										    	);
	    	
    	}

    	return $arr_list_product_duty;
    }
}
