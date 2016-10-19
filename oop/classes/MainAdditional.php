<?php
/**
 * 处理主险+附加险的产品,目前的产品是Y502.
 * User: ytyan
 * Date: 14-8-10
 * Time: 下午3:15
 */

class MainAdditional {
    private $additional = array(); //附加险
    private $period = array();     //保险期间
    private $career = array(); //职业
    private $ages = array();//年龄
    private $db = null;
    private $json_data = '';
    private $default_value = array('period'=>'1年','career'=>'1-2类','premium'=>80);
    private $coefficient_map = array('1个月'=>0.2,'3个月'=>0.4,'6个月'=>0.7,'12个月'=>1);
    private $product_id=0;
    private $product_name='';
    private $attributeId=0;
    private $all_data = array();

    public function __construct($attributeId)
    {
        $this->db = DB::getInstance();
        $this->attributeId = $attributeId;
    }

    public function  test()
    {
        $sql = 'SELECT * FROM  t_insurance_product_attribute WHERE attribute_id =' . $this->attributeId;
        echo $sql;
        $this->db->query($sql);
        $results = $this->db->results();
        print_r($results);
    }  

    //得到主险产品信息
    public function getMain()
    {
        $sql = 'SELECT a.product_code, a.product_name, a.product_type, a.product_duty_price_type, a.attribute_name, '
            . 'b.attribute_type, b.description, b.limit_note, b.cover_note, b.file_path'
            . ' FROM t_insurance_product_base a, t_insurance_product_attribute b'
            . ' WHERE a.attribute_id = b.attribute_id AND a.product_id=' . $this->product_id;
        $result = $this->db->query($sql);
        $row = mysql_fetch_array($result, MYSQL_ASSOC);
        $this->product_name = $row['product_name'];
        $this->all_data['product_data'] = $row;
        mysql_free_result($result);

    }

    //得到产品的责任信息
    public function getDuty($productId)
    {

        $duties = array();
        $sql = 'SELECT  *  FORM t_insurance_product_duty'
            . ' WHERE product_id = ' . $productId;
        $result = $this->db->query($sql);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            array_push($duties, $row);
        }
        return $duties;

    }
    //得到某个责任的保费和保额
    public function getPrices($product_duty_id)
    {
        $dutyPrices = array();
        $sql = 'SELECT * from t_insurance_product_duty_price '
            .' ORDER BY product_career_id,premium'
            .' WHERE product_duty_id=' . $product_duty_id;
        $result = $this->db->query($sql);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            array_push($duty_prices, $row);
        }
        return $dutyPrices;
    }
    //得到附加险信息
    public function getAdditional()
    {
        $additionals = array();
        $sql = 'SELECT a.product_id, a.product_code, a.product_name, a.product_type, a.product_duty_price_type, a.attribute_name, '
            . 'b.attribute_type, b.description, b.limit_note, b.cover_note, b.file_path'
            . ' FROM t_insurance_product_base a, t_insurance_product_attribute b'
            . ' WHERE a.attribute_id = b.attribute_id AND a.parent_id=' . $this->product_id;
        $result = $this->db->query($sql);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            array_push($additionals, $row);
        }
        $this->all_data['additionals'] = $additionals;
        mysql_free_result($result);
    }
    //得到附加险的责任
    public function getAdditionalDuties()
    {
        $addtionals = $this->all_data['additionals'];
        if(count($addtionals) > 0)
        {
            foreach($addtionals as $product){

                $duties = $this->getDuty($product['product_id']);
                $this->all_data['additionals']['duties'][$product['product_id']] = $duties;

            }
        }
    }

    public function getFactors()
    {
        $factors = array();
        $sql = 'SELECT  * FROM t_insurance_product_influencingfactor'
            ." WHERE product_id = {$this->product_id} ORDER BY product_influencingfactor_type" ;
        $result = $this->db->query($sql);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            array_push($factors, $row);
        }
        $this->all_data['factors'] = $factors;
    }
} 