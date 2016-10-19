<?php
/**
 * 新华人寿安心宝贝，少儿相关
*/

 class NCI_Insurance 
 {

    //added by zhangxi, 20150326,
    // 通过险种id，保险期间，职业类别，直接获取每个险种的保费和保额下拉列表
    private function get_product_duty_list($product_id,
    										$product_code,
    										 $period, //缴费期限
    										 $gender, //性别,F女，M 男
    										 $age)//被保险人年龄
    {
    	global $_SGLOBAL;
    	////////////////////////////////////////////////////////////////////
    	
    	$sql="SELECT * FROM t_insurance_product_duty AS pd
    	INNER JOIN t_insurance_duty AS id ON pd.duty_id=id.duty_id
    	WHERE pd.product_id='$product_id'";
    	
    	$product_duty_query = $_SGLOBAL['db']->query($sql);
    	$arr_list_product_duty = array();
    	
    	//////////////////////////////////////////////////////////////////////////
    	//循环遍历当前产品下的责任信息
    	while ($duty_row = $_SGLOBAL['db']->fetch_array($product_duty_query))
    	{
    		//根据产品id，产品影响因素类型，和影响因素代码，查出影响因素id
    		//$product_id = $product_row['product_id'];
    		$sql="SELECT product_influencingfactor_id FROM t_insurance_product_influencingfactor
    		WHERE product_id='$product_id' AND product_influencingfactor_type='period' AND factor_code='$period'";
    		$period_query = $_SGLOBAL['db']->query($sql);
    		$period_row = $_SGLOBAL['db']->fetch_row($period_query);
    		$product_period_id = $period_row[0];
    	
    		//性别的影响因子获取
    		$sql="SELECT product_influencingfactor_id FROM t_insurance_product_influencingfactor
    		WHERE product_id='$product_id' AND product_influencingfactor_type='career' AND factor_code='$gender'";
    		$gender_query = $_SGLOBAL['db']->query($sql);
    		$career_row = $_SGLOBAL['db']->fetch_row($gender_query);
    		$product_gender_id = $career_row[0];
    		
    		//被保险人当前年龄
    		$sql="SELECT product_influencingfactor_id FROM t_insurance_product_influencingfactor
    		WHERE product_id='$product_id' AND product_influencingfactor_type='age' AND factor_code='$age'";
    		$age_query = $_SGLOBAL['db']->query($sql);
    		$age_row = $_SGLOBAL['db']->fetch_row($age_query);
    		$product_age_id = $age_row[0];
    	
    		//获取每个产品责任id，结合职业类别，保险时长，获取保额/保费下拉列表信息
    		//得到产品责任id:
    		$product_duty_id = $duty_row['product_duty_id'];
    		
    		$sql="SELECT product_duty_price_id,amount,premium FROM t_insurance_product_duty_price
    		WHERE product_duty_id='$product_duty_id' AND " .
    		"product_career_id='$product_gender_id' AND " .
    		"product_age_id='$product_age_id' AND " .
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
    
    public function get_Product_Price_List($attribute_id, $period, $gender, $age)
    {
		//获取产品列表信息 
		global $_SGLOBAL;
	
		///找到当前险种下的产品列表
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
			$arr_list_product_duty = $this->get_product_duty_list($product_row['product_id'], $product_row['product_code'],$period, $gender, $age);
			
			$arr_list_product[] = array(
									'product_id'=>$product_row['product_id'],
									'product_code'=>$product_row['product_code'],
									'product_name'=>$product_row['product_name'],
									'product_type'=>$product_row['product_type'],
									'product_duty_list' =>$arr_list_product_duty,//产品责任列表
									);
		}
		
		return $arr_list_product;
		
    }
    
    
    //获取省的下拉列表
    public function get_province_list()
    {
    	global $_SGLOBAL;
    	$sql="SELECT * FROM t_insurance_xinhua_provincecode";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_list_province = array();
    	while ($row = $_SGLOBAL['db']->fetch_array($query))
    	{
    		$arr_list_province[] = $row;
    	}
    	return $arr_list_province;
    }
    //获取某一个省下的市下拉列表
    public function get_city_list_by_province_code($province_code)
    {
    	global $_SGLOBAL;
    	$pa = substr($province_code,0,2);
    	$sql="SELECT * FROM t_insurance_xinhua_citycode WHERE city_code like '$pa%'";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_list_city = array();
    	while ($row = $_SGLOBAL['db']->fetch_array($query))
    	{
    		$arr_list_city[] = $row;
    	}
    	return $arr_list_city;
    }
    //获取某一个市下的县下拉列表
    public function get_county_list_by_city_code($city_code)
    {
    	global $_SGLOBAL;
    	$pa = substr($city_code,0,4);
    	$sql="SELECT * FROM t_insurance_xinhua_countycode WHERE county_code like '$pa%'";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_list_county = array();
    	while ($row = $_SGLOBAL['db']->fetch_array($query))
    	{
    		$arr_list_county[] = $row;
    	}
    	return $arr_list_county;
    }
     //获取缴费银行列表
    public function get_bank_list()
    {
    	global $_SGLOBAL;

    	$sql="SELECT * FROM t_insurance_xinhua_bankcode";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_list_bank = array();
    	while ($row = $_SGLOBAL['db']->fetch_array($query))
    	{
    		$arr_list_bank[] = $row;
    	}
    	return $arr_list_bank;
    }
    
     //获取行业下拉列表
    public function get_industry_list()
    {
    	global $_SGLOBAL;

    	$sql="SELECT * FROM t_insurance_xinhua_industrycode";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_list_industry = array();
    	while ($row = $_SGLOBAL['db']->fetch_array($query))
    	{
    		$arr_list_industry[] = $row;
    	}
    	return $arr_list_industry;
    }
    //通过行业获取当前行业下的职业列表
    public function get_career_list_by_industry_code($industry_code)
    {
    	global $_SGLOBAL;

    	$sql="SELECT * FROM t_insurance_xinhua_careercode WHERE industry_code='$industry_code'";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_list_career = array();
    	while ($row = $_SGLOBAL['db']->fetch_array($query))
    	{
    		$arr_list_career[] = $row;
    	}
    	return $arr_list_career;
    }

    //获取某一机构下的机构列表
    public function get_org_list($parent_org_code)
    {
    	global $_SGLOBAL;

    	$sql="SELECT * FROM t_insurance_xinhua_organizationcode WHERE org_parent_code='$parent_org_code'";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_list_org = array();
    	while ($row = $_SGLOBAL['db']->fetch_array($query))
    	{
    		$arr_list_org[] = $row;
    	}
    	return $arr_list_org;
    }
    //通过机构代码获取机构名称
    public function get_service_name_by_code($code)
    {
    	global $_SGLOBAL;

    	$sql="SELECT org_name FROM t_insurance_xinhua_organizationcode WHERE org_code='$code'";
		$query = $_SGLOBAL['db']->query($sql);

    	$row = $_SGLOBAL['db']->fetch_row($query);

    	return $row[0];
    }
    //通过省代码获取省份名称
    public function get_province_name_by_code($code)
    {
    	global $_SGLOBAL;

    	$sql="SELECT province_name FROM t_insurance_xinhua_provincecode WHERE province_code='$code'";
		$query = $_SGLOBAL['db']->query($sql);

    	$row = $_SGLOBAL['db']->fetch_row($query);

    	return $row[0];
    }
    //通过市代码获取市名称
    public function get_city_name_by_code($code)
    {
    	global $_SGLOBAL;

    	$sql="SELECT city_name FROM t_insurance_xinhua_citycode WHERE city_code='$code'";
		$query = $_SGLOBAL['db']->query($sql);

    	$row = $_SGLOBAL['db']->fetch_row($query);

    	return $row[0];
    }
    //通过县代码获取县名称
    public function get_county_name_by_code($code)
    {
    	global $_SGLOBAL;

    	$sql="SELECT county_name FROM t_insurance_xinhua_countycode WHERE county_code='$code'";
		$query = $_SGLOBAL['db']->query($sql);

    	$row = $_SGLOBAL['db']->fetch_row($query);

    	return $row[0];
    }
    //通过职业代码获取职业名称
    public function get_career_name_by_code($code)
    {
    	global $_SGLOBAL;

    	$sql="SELECT career_name FROM t_insurance_xinhua_careercode WHERE career_code='$code'";
		$query = $_SGLOBAL['db']->query($sql);

    	$row = $_SGLOBAL['db']->fetch_row($query);

    	return $row[0];
    }
    //通过行业代码，获取行业名称
    public function get_industry_name_by_code($code)
    {
    	global $_SGLOBAL;

    	$sql="SELECT industry_name FROM t_insurance_xinhua_industrycode WHERE industry_code='$code'";
		$query = $_SGLOBAL['db']->query($sql);

    	$row = $_SGLOBAL['db']->fetch_row($query);

    	return $row[0];
    }
    //通过银行代码获取银行名称
    public function get_bank_name_by_code($code)
    {
    	global $_SGLOBAL;

    	$sql="SELECT bank_name FROM t_insurance_xinhua_bankcode WHERE bank_code='$code'";
		$query = $_SGLOBAL['db']->query($sql);

    	$row = $_SGLOBAL['db']->fetch_row($query);

    	return $row[0];
    }
    //通过被保险人年纪，得到他可以的缴费期间代码
    public function get_pay_period_list($product_id, $age_factor_code)
    {
    	global $_SGLOBAL;

    	$sql="SELECT product_influencingfactor_id FROM t_insurance_product_influencingfactor WHERE product_id='$product_id' AND product_influencingfactor_type='age' AND factor_code='$age_factor_code'";
		$query = $_SGLOBAL['db']->query($sql);
    	$row = $_SGLOBAL['db']->fetch_row($query);
		$product_age_id = $row[0];
		//echo "product_age_id=".$product_age_id;
		ss_log(__FUNCTION__.", product_age_id=".$product_age_id);
		$sql = "SELECT DISTINCT product_period_id FROM t_insurance_product_duty_price WHERE product_age_id='$product_age_id'";
		$query = $_SGLOBAL['db']->query($sql);
		
		$list = array();
    	while($row = $_SGLOBAL['db']->fetch_row($query))
    	{
    		$list[] = $row[0];
    	}
    	//echo var_export($list);
    	ss_log(__FUNCTION__.", row=".var_export($list,true));
    	$str_cond = implode(",", $list);
    	//echo $str_cond;
    	$sql = "SELECT DISTINCT factor_code FROM t_insurance_product_influencingfactor WHERE product_influencingfactor_id IN ($str_cond) ORDER BY view_order";
		$query = $_SGLOBAL['db']->query($sql);
		$data = array();
    	while($row = $_SGLOBAL['db']->fetch_row($query))
    	{
    		$data[] = $row[0];
    	}
    	return $data;
    }
 }