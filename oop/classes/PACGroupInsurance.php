<?php
/**
 * 平安502团体险
*/

 class PACGroupInsurance 
 {
    //获取初始数据
    public function getDefaultData($period, $career)
    {
        $dutyList = array();
        foreach ( $this->insuranceCode as $product )
        {
           
                 foreach ($product['duties'] as $dutyKey => $dutyValue)
                 {
                  
                    if($dutyKey == 'JE01')
                    {
                        $dutyList[$dutyKey] = $dutyValue;
                    }else {
                        $dutyList[$dutyKey] = $this->searchDutyPremium($dutyValue, $period, $career);
                    }
                 }
             
        }
        return $dutyList;
    }
    //added by zhangxi, 20150708, 增加平安个财产品处理
    private function pac04_get_product_duty_list($product_id,$product_code, $period)
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
    		$product_period_id = -1;
    		if($period != -1)
    		{
    			$sql="SELECT product_influencingfactor_id FROM t_insurance_product_influencingfactor
	    		WHERE product_id='$product_id' AND product_influencingfactor_type='period' AND factor_code='$period'";
	    		$period_query = $_SGLOBAL['db']->query($sql);
	    		$period_row = $_SGLOBAL['db']->fetch_row($period_query);
	    		$product_period_id = $period_row[0];
    		}
    		
    	
//    		$sql="SELECT product_influencingfactor_id FROM t_insurance_product_influencingfactor
//    		WHERE product_id='$product_id' AND product_influencingfactor_type='career' AND factor_code='$career'";
//    		$career_query = $_SGLOBAL['db']->query($sql);
//    		$career_row = $_SGLOBAL['db']->fetch_row($career_query);
//    		$product_career_id = $career_row[0];
//    	
    		//获取每个产品责任id，结合职业类别，保险时长，获取保额/保费下拉列表信息
    		//得到产品责任id:
    		$product_duty_id = $duty_row['product_duty_id'];
    	
    		$sql="SELECT product_duty_price_id,amount,premium FROM t_insurance_product_duty_price
    		WHERE product_duty_id='$product_duty_id' AND " .
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
    
    //added by zhangxi, 20141209,
    // 通过险种id，保险期间，职业类别，直接获取每个险种的保费和保额下拉列表
    private function y502_get_product_duty_list($product_id,$product_code, $period,$career)
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
    		WHERE product_id='$product_id' AND product_influencingfactor_type='period' AND factor_code='$period'";
    		
    		//ss_log($sql);
    		
    		$period_query = $_SGLOBAL['db']->query($sql);
    		$period_row = $_SGLOBAL['db']->fetch_row($period_query);
    		$product_period_id = $period_row[0];
    	
    		$sql="SELECT product_influencingfactor_id FROM t_insurance_product_influencingfactor
    		WHERE product_id='$product_id' AND product_influencingfactor_type='career' AND factor_code='$career'";
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
    
    
    public function get_duty_price_ids_by_policy_id($policy_id)
    {
    	global $_SGLOBAL;
    	
    	$sql = 	"SELECT DISTINCT ipspdp.product_duty_price_id from t_insurance_policy_subject_product_duty_prices AS ipspdp 
				INNER JOIN t_insurance_policy_subject AS ips ON ips.policy_subject_id=ipspdp.policy_subject_id 
				WHERE ips.policy_id=$policy_id";
		$query = $_SGLOBAL['db']->query($sql);
		$list_duty_ids = array();
		//当前险种下的所有产品
		while ($row = $_SGLOBAL['db']->fetch_row($query))
		{
			$list_duty_ids[] = $row[0];
		}
    	//返回的是一个数组
    	return $list_duty_ids;
    	
    }
    //added by zhangxi, 20150731, 多层级情况的支持
    public function get_duty_price_ids_by_policy_id_multi_subjects($policy_id)
    {
    	global $_SGLOBAL;
    	$sql = 	"SELECT DISTINCT ips.policy_subject_id from t_insurance_policy_subject AS ips 
				WHERE ips.policy_id=$policy_id";
		$query = $_SGLOBAL['db']->query($sql);
		$list_subject_ids = array();
		while ($row = $_SGLOBAL['db']->fetch_row($query))
		{
			$subject_id = $row[0];
			$sql = 	"SELECT DISTINCT ipspdp.product_duty_price_id from t_insurance_policy_subject_product_duty_prices AS ipspdp 
				WHERE ipspdp.policy_subject_id=$subject_id";
			$query1 = $_SGLOBAL['db']->query($sql);
			$list_duty_ids = array();
			while ($row1 = $_SGLOBAL['db']->fetch_row($query1))
			{
				$list_duty_ids[] = $row1[0];
			}
			$list_subject_ids[] = $list_duty_ids;
			
		}

    	return $list_subject_ids;
    	
    }
    public function get_user_choosen_product_price_list($duty_price_list)
    {

		global $_SGLOBAL;
	
		///找产品名，责任名，等信息
		$sql="SELECT ipb.product_name,ipb.product_type, ipd.duty_name,ipdp.amount,ipdp.premium FROM t_insurance_product_duty_price AS ipdp
		INNER JOIN t_insurance_product_duty AS ipd ON ipd.product_duty_id=ipdp.product_duty_id
		INNER JOIN t_insurance_product_base AS ipb ON ipd.product_id=ipb.product_id 
		WHERE ipdp.product_duty_price_id IN ($duty_price_list)";
    	$product_query = $_SGLOBAL['db']->query($sql);
		$arr_choosen_product_duty_list = array();
		//当前险种下的所有产品
		while ($product_row = $_SGLOBAL['db']->fetch_array($product_query))
		{
			$arr_choosen_product_duty_list[] = array(
    											'product_name'=>$product_row['product_name'],
    											'product_type'=>$product_row['product_type'],
    											'duty_name'=>$product_row['duty_name'],
    											'amount'=>$product_row['amount'],
    											'price'=>$product_row['premium'],
    											);	
		}
		return $arr_choosen_product_duty_list;
    	
    }
    //added by zhangxi, 20150804, 
    public function pac_query_level_one_career_list()
    {
    	global $_SGLOBAL;
	
		///
		$sql="SELECT DISTINCT type_one FROM t_insurance_pingan_career_type";
    	$query = $_SGLOBAL['db']->query($sql);
		$list = array();
		//当前险种下的所有产品
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$list[] = $row['type_one'];	
		}
		return $list;
    }
    //added by zhangxi, 20150804, 
    public function pac_query_level_two_career_list($type_one)
    {
    	global $_SGLOBAL;
	
		///
		$sql="SELECT DISTINCT type_two FROM t_insurance_pingan_career_type 
			 WHERE type_one='$type_one'";
    	$query = $_SGLOBAL['db']->query($sql);
		$list = array();
		//当前险种下的所有产品
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$list[] = $row['type_two'];	
		}
		return $list;
    }
    //added by zhangxi, 20150804, 
    public function pac_query_level_three_career_list($type_two)
    {
    	global $_SGLOBAL;
	
		///
		$sql="SELECT DISTINCT type_three,career_code,career_type FROM t_insurance_pingan_career_type 
				 WHERE type_two='$type_two'";
    	$query = $_SGLOBAL['db']->query($sql);
		$list = array();
		//当前险种下的所有产品
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$list[] = array(
									'type_three'=>$row['type_three'],
									'career_code'=>$row['career_code'],
									'career_type'=>$row['career_type'],
									);
		}
		return $list;
    }
    
    
    //added by zhangxi, 20150708, 平安个财产品的处理
    public function pac04_getProductPriceList($attribute_id, $period, $product_code)
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
			if($product_code == $product_row['product_code'])
			{
				$arr_list_product_duty = $this->pac04_get_product_duty_list($product_row['product_id'], $product_row['product_code'],$period);
			
				$arr_list_product[] = array(
									'product_id'=>$product_row['product_id'],
									'product_code'=>$product_row['product_code'],
									'product_name'=>$product_row['product_name'],
									'product_type'=>$product_row['product_type'],
									'product_duty_list' =>$arr_list_product_duty,//具体每一个产品的责任信息
									);
			}
			else
			{
				//log here
				ss_log(__FUNCTION__.", product_code=".$product_code.", queryed product code=".$product_row['product_code']);
			}
			
		}
		
		return $arr_list_product;
    }
    
    
    public function getProductPriceList($attribute_id, $period, $career)
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
			$arr_list_product_duty = $this->y502_get_product_duty_list($product_row['product_id'], $product_row['product_code'],$period,$career);
			
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
    
    private function searchDutyPremium($dutyPremiums, $period, $career)
    {
       
        $arrayDutyPremium = array();
        foreach($dutyPremiums as $key  => $value)
        {
            foreach ($value as $item)
            {
                if(($item['period'] == $period) && ($item['career'] == $career) )
                {
                    if (!array_key_exists($key, $arrayDutyPremium)) {
                        $arrayDutyPremium[$key] = $item['money'];
                    }
                }
            }
        }
        return $arrayDutyPremium;
    }
    
    public function changePremium($premium, $period, $dutyCode, $career)
    {
      
        foreach ( $this->insuranceCode as $product )
        {
           
                 foreach ($product['duties'] as $dutyKey => $dutyValue)
                 {
                  
                    if($dutyKey == $dutyCode)
                    {
                         $arrayPremiumFeeAndCareer = $this->getPremiumFeeAndCareer($dutyValue, $premium, $period, $career);
                         return $arrayPremiumFeeAndCareer;
                    }
                 }
             
        }
        
    }
    private function getPremiumFeeAndCareer($dutyPremiums, $premium, $period, $career)
    {
       
        $arrayPremiumFeeAndCareer = array('money' => 0,
                                          'career' => array());
        foreach($dutyPremiums as $key  => $value)
        {
            if($key == $premium )
            {    
                foreach ($value as $item)
                {
                    if(($item['period'] == $period) && ($item['career'] == $career) )
                    {
                         $arrayPremiumFeeAndCareer['money'] = $item['money'];
                    }
                    if(!in_array($item['career'], $arrayPremiumFeeAndCareer['career']))
                    {
                        array_push($arrayPremiumFeeAndCareer['career'], $item['career']);
                    }
                }
            }else{
                continue;
            }
        }
        return $arrayPremiumFeeAndCareer;
    }
    
    public function changePeriod( $period, $career, $duties)
    {
        $trimDuties =  rtrim($duties, '|');
        $arrDuties = explode('|', $trimDuties);
        
        $returnStr = '';
        for($i = 0; $i < count($arrDuties); $i++)
        {
            $arrDuty = explode(';', $arrDuties[$i]);
           
            $tempPremiumFee = $this->changePremium($arrDuty[1], $period, $arrDuty[0], $career);
          
            $returnStr .= $arrDuty[0] . ';' . $tempPremiumFee['money'] . '|';
        }
        $returnStr = rtrim($returnStr,'|');
        return $returnStr;
    }
    
    
 }