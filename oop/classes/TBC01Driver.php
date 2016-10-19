<?php
/*
*货车司机意外险
 */

/**
 * @author ytyan
 */
class TBC01Driver extends TBC01ProductBase {
   
  
    //得到产品详情页展示所需的数据
    public function getProductDetailData()
    {
        $this->getProducts();
        $this->getProductAdditional();
        $this->getDuties();
        $showData = array(
            'attribute_name'=>$this->productAttribute['attribute_name'],
            'description'=>$this->productAttribute['description'],
            'limit_note'=>$this->productAttribute['limit_note'],
            'cover_note'=>$this->productAttribute['cover_note'],
            'additional'=>$this->allData['additional'],
            'products'=>$this->allData['product_data'],
            'duties'=>$this->allData['duties'],
            
        );
        return $showData;
    }
    
    private function getDuties()
    {
        foreach($this->allData['product_data'] as $product)
        {
            $sql = "SELECT * FROM t_insurance_product_duty pt"
                    . " LEFT JOIN t_insurance_product_duty_price ptp"
                    . " ON pt.product_duty_id=ptp.product_duty_id"
                    . " WHERE product_id =  " . $product['product_id']
                    . " ORDER BY pt.duty_id LIMIT 0,20";
            $this->db->query($sql);
            $duties = $this->db->results();
            $this->allData['duties'][$product['product_id']] = $duties;
        }
    }
    
   
    public function post()
    {
        //投保人
        $applicant = array(
            'certificateType' => '', //证件类型
            'certificateCode' => '', //证件号码
            'birthDate' => '', //出生日期
            'fullName' => '', //姓名
            'addressInfo' => array(
                'provinceCode' => '', //省代码
                'cityCode' => '')   //城市代码
        );
        //被保险人
        $insured = array(
            'unitCount' => 1, //购买份数
            'certificateType' => '', //证件类型
            'certificateCode' => '', //证件号码
            'birthDate' => '', //出生日期
            'fullName' => '', //姓名
            'insuredRelationCode' => '', //与投保人关系
            'addressInfo' => array(
                'provinceCode' => '', //省代码
                'cityCode' => '')   //城市代码
        );
    }
    //保存保单到数据库
    public function savePolicyToDB()
    {
        
    }
    //生成订单
    public function genOrder()
    {
        
    }
    //调用java程序进行投保
    public function postPolicy()
    {
        
    }
}
