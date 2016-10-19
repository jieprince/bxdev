<?php

/**
 * add yes123 2014-12-8 我的客户类(个人)
 */
require_once(ROOT_PATH . 'baoxian/source/function_debug.php');
class Customer {
	public $uid;
	public $fullname;
	public $fullname_english;
	public $type; //0,被保险人，1投保人,2两者身份相同
	public $certificates_type; //证件类型
	public $certificates_code; //证件号码
	public $certificates_validdate; //有效期
	public $gender;
	public $birthday; //生日
	public $telephone; //电话
	public $mobiletelephone; //手机
	public $email;

	public $occupationClassCode; //职业类别编码
	public $educationInformation; //教育信息
	public $province; //城市
	public $province_code;
	public $city; //城市
	public $city_code;

	public $address; //地址
	public $zipcode; //邮政编码
	public $fading; //受益人
	public $agent_uid; //代理人

	public $start;
	public $page_size;
	public $limit;
	public $condition;
	public $where = " ";

	function Customer() {
	}

	public function getCustomerList() {
		//add policyCount by dingchaoyang 2014-12-13
		$sql = "SELECT *,(SELECT COUNT(*) FROM t_insurance_policy WHERE applicant_uid=uid AND policy_status='insured'  ) AS policyCount FROM t_user_info WHERE agent_uid = " . $this->agent_uid . $this->where. "ORDER BY uid DESC";
		
		$user_list = $GLOBALS['db']->SelectLimit($sql, $this->page_size, $this->start);

		$arr = array ();
		$c_type = $this->getCertificatesType();
		
		while ($user = $GLOBALS['db']->fetchRow($user_list)) {
			$user['certificates_type']=$c_type[$user['certificates_type_unify']];
			$user['cer_type'] = $user['certificates_type_unify'];// add by dingchaoyang 2014-12-16
			$user['gender'] = $user['gender_unify'];
			$user['cusType'] = 0;
			$arr[] = $user;
		}

		return $arr;
	}

	public function getCustomerCount() {
		$sql = "SELECT COUNT(*) FROM t_user_info WHERE agent_uid = " . $this->agent_uid . $this->where;
		return $GLOBALS['db']->getOne($sql);
	}

	public function getCusById($uid) {
		$sql = "SELECT * FROM t_user_info WHERE agent_uid=" . $this->agent_uid . " AND uid=" . $uid;
		$cus = $GLOBALS['db']->getRow($sql);
		$cus['certificates_type'] = $cus['certificates_type_unify'];
		$cus['gender'] = $cus['gender_unify'];
		$cus['phone'] = $cus['mobiletelephone'];
		return $cus;
	}

	public function getRegionById($p_id) {
		//判断id是不是数字
		if(is_numeric($p_id))
		{
			$sql = "SELECT * FROM bx_region WHERE parent_id=" . $p_id;
		}
		else
		{
			$sql = "SELECT region_id FROM bx_region WHERE region_name='" . trim($p_id)."'";
			$p_id = $GLOBALS['db']->getOne($sql);
			
			ss_log('region_id:'.$sql);
			
			if($p_id)
			{
				$sql = "SELECT * FROM bx_region WHERE parent_id=" . $p_id;
			}
		}
		
		ss_log('region_list:'.$sql);
		return $GLOBALS['db']->getAll($sql);
	}

	public function saveCustomer($agent_uid) {
		$this->condition = array ();
		$cus_id = trim($_REQUEST['cus_id']);
		$fullname = trim($_REQUEST['fullname']);
		$certificates_type = trim($_REQUEST['certificates_type']);
		$certificates_code = trim($_REQUEST['certificates_code']);
		$birthday = trim($_REQUEST['birthday']);
		$gender = trim($_REQUEST['gender']);
		$mobiletelephone = trim($_REQUEST['phone']);
		$email = trim($_REQUEST['email']);
		$province_code = trim($_REQUEST['province']);
		$city_code = trim($_REQUEST['city']);
		$address = trim($_REQUEST['address']);
		$img1 = trim($_REQUEST['img_type1']);
		$img2 = trim($_REQUEST['img_type2']);
		//modify yes123 2015-01-07 手动新增的是投保人
		//$type = trim($_REQUEST['type']);
		$type = 1;//0,被保险人，1投保人,2两者身份相同
		$province="";
		$city="";
		if($province_code)
		{
			$province = $this->getNameByRegion_code($province_code);
		}
		
		if($city_code)
		{
			$city = $this->getNameByRegion_code($city_code);
		}

		//如果存在 就是更新，否则是插入
		if($cus_id)
		{
			$sql = "UPDATE t_user_info set fullname='".$fullname."',certificates_type_unify='".$certificates_type."'," .
			"certificates_code='".$certificates_code."'," .
			"birthday='".$birthday. "', gender_unify='" .$gender."',mobiletelephone='".$mobiletelephone."'," .
			"email='".$email."',province_code='".$province_code."', ".
			"city_code='".$city_code. "',province='".$province."',city='".$city."', " .
			"address='".$address."',img1='".$img1."',img2='".$img2."' WHERE uid=".$cus_id ;

			ss_log("update_sql:".$sql);
			
			$GLOBALS['db']->query($sql);
			return $cus_id;
		}
		else
		{	
			//add platformid by dingchaoyang 2014-12-19 则给platform_id赋值
			$ROOT_PATH__= str_replace ( 'includes/class/Customer.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
			include_once ($ROOT_PATH__ . 'api/EBaoApp/platformEnvironment.class.php');
			$platform_id = PlatformEnvironment::getPlatformID();
			//end
			$$type = 1;//0,被保险人，1投保人,2两者身份相同
			$sql = "INSERT INTO t_user_info (fullname,type, certificates_type_unify, certificates_code,birthday, " .
				"gender_unify,mobiletelephone,email, province_code,city_code,province,city,address,agent_uid,img1,img2,platform_id)" .
				" VALUES ('" . $fullname . "','".$type."','" . $certificates_type . "','" . $certificates_code . 
				"','" .$birthday . "','" . $gender . "','" . $mobiletelephone . "','" . $email . 
		        "','".$province_code."','" .$city_code ."','".$province."','" .$city ."','" .$address. "','".$agent_uid."','".$img1."','".$img2."','".$platform_id."')";
				
			$GLOBALS['db']->query($sql);
			$uid = $GLOBALS['db']->insert_id(); //发票ID
			return $uid;
		}
		

	}

	//条件拼接
	public function whereCondition($type) {

		$this->condition = array ();

		$this->condition['fullname'] = trim($_REQUEST['fullname']);
		$this->condition['type'] = trim($_REQUEST['type']);
		$this->condition['certificates_type'] = trim($_REQUEST['certificates_type']);
		$this->condition['certificates_code'] = trim($_REQUEST['certificates_code']);
		$this->condition['gender'] = trim($_REQUEST['gender']);
		$this->condition['email'] = trim($_REQUEST['email']);
		$this->condition['mobiletelephone'] = trim($_REQUEST['phone']);//add 手机号查询 by dingchaoyang 2014-12-13
		$this->condition['cus_contact'] = trim($_REQUEST['cus_contact']);
		
		if($type==0){
				if ($this->condition['fullname']) {
					$this->where .= " AND fullname LIKE '%" . $this->condition['fullname'] . "%'";
				}
		
				if ($this->condition['gender']) {
					if ($this->condition['gender'] == 'M') {
						$this->where .= " AND gender_unify ='M' ";
					} else {
						$this->where .= " AND gender_unify ='F' ";
					}
				}
				
				if ($this->condition['certificates_type']) {
					$this->where .= " AND certificates_type_unify = '" . $this->condition['certificates_type'] . "'";
				}
				
				if ($this->condition['certificates_code']) {
					$this->where .= " AND certificates_code = '" . $this->condition['certificates_code'] . "'";
				}
				
				$this->where.=" AND type IN(1)"; //0,被保险人，1投保人,2两者身份相同
				
		}
		else if($type==1){
				if ($this->condition['fullname']) {
					$this->where .= " AND group_name LIKE '%" . $this->condition['fullname'] . "%'";
				}
	
				
				if ($this->condition['certificates_type']) {
					$this->where .= " AND group_certificates_type_unify = '" . $this->condition['certificates_type'] . "'";
				}
				
				if ($this->condition['certificates_code']) {
					$this->where .= " AND group_certificates_code = '" . $this->condition['certificates_code'] . "'";
				}
				
				if ($this->condition['cus_contact']) {
					$this->where .= " AND cus_contact LIKE '%" . $this->condition['cus_contact'] . "%'";
				}
				$this->where.=" AND type IN(1)"; 

		}

		
		//add by dingchaoyang 2014-12-13
		if ($this->condition['email']) {
			$this->where .= " AND email = '" . $this->condition['email'] . "'";
		}
		
		if ($this->condition['mobiletelephone']) {
			$this->where .= " AND mobiletelephone = '" . $this->condition['mobiletelephone'] . "'";
		}
		
		//end by dingchaoyang 2014-12-13

		return $this->condition;
	}
	
	public function getNameByRegion_code($region_code){
		$sql = "SELECT region_name FROM t_region WHERE region_code=" . $region_code;
		return $GLOBALS['db']->getOne($sql);
	}
	
	
	//通过省份code 获取城市列表
	public function getCityListByPcode($p_code){
		$new_code = $p_code/10000;
		$sql = "SELECT * FROM t_region WHERE region_code LIKE '".$new_code."%' AND region_code<>'".$p_code."'";
		return $GLOBALS['db']->getAll($sql);
	}
	
	
	public function delCustomer($uid){
		$sql = "DELETE FROM t_user_info WHERE uid=" . $uid;
		$r = $GLOBALS['db']->query($sql);
		if($r)
		{
			return array('code'=>0,'msg'=>"删除成功");
		}else
		{
			return array('code'=>1,'msg'=>"删除失败");
		}
	}
	//个人证件类型
	public  function getCertificatesType(){
		$c_type = array();
		$c_type["1"]='身份证';
		$c_type["2"]='驾驶证';
		$c_type["3"]='军官证';
		$c_type["4"]='护照';
		$c_type["5"]='港澳回乡证或台胞证';
		$c_type["6"]='返乡证';
		//$c_type["7"]='组织机构代码';
		$c_type["8"]='军人证';
		$c_type["9"]='其他';
		
		return $c_type;
	}
	
		//机构证件类型
	public  function getOrgCertificatesType(){
		$c_type = array();
		$c_type["10"]='组织机构代码证';
		$c_type["11"]='税务登记证';
		$c_type["12"]='异常证件';
		return $c_type;
	}
	
	
	//机构列表
	public function getOrganizationCustomerList() {
		$sql = "SELECT *,(SELECT COUNT(*) FROM t_insurance_policy WHERE applicant_uid=gid AND policy_status='insured'  ) AS policyCount FROM t_group_info WHERE agent_uid = " . $this->agent_uid . $this->where. "ORDER BY gid DESC";
		ss_log("获取机构列表:".$sql);
		$cus_list = $GLOBALS['db']->SelectLimit($sql, $this->page_size, $this->start);

		$arr = array ();
		$c_type = $this->getOrgCertificatesType();
		
		while ($cus = $GLOBALS['db']->fetchRow($cus_list)) {
			$cus['certificates_type']=$c_type[$cus['group_certificates_type_unify']];
			$cus['cer_type'] = $cus['group_certificates_type_unify'];// add by dingchaoyang 2015-1-14
			$cus['certificates_code']=$cus['group_certificates_code'];
			$cus['fullname']=$cus['group_name'];
			$cus['uid']=$cus['gid'];
			$cus['cusType'] = 1;
			$arr[] = $cus;
		}
		//echo "<pre>";print_r($arr);
		return $arr;
	}
	
	public function saveOrganizationCustomer($agent_uid) {
		$this->condition = array ();
		$cus_id = trim($_REQUEST['cus_id']);
		$fullname = trim($_REQUEST['fullname']); //机构名称
		$cus_contact = trim($_REQUEST['cus_contact']); //联系人
		$certificates_type = trim($_REQUEST['certificates_type']);
		$certificates_code = trim($_REQUEST['certificates_code']);
		$mobiletelephone = trim($_REQUEST['phone']);
		$email = trim($_REQUEST['email']);
		$img1 = trim($_REQUEST['img_type1']);
		$img2 = trim($_REQUEST['img_type2']);
		$type = 1; //新增的都是投保人
		
		//如果存在 就是更新，否则是插入
		if($cus_id)
		{
			$sql = "UPDATE t_group_info set group_name='".$fullname."',group_certificates_type_unify='".$certificates_type."'," .
			"group_certificates_code='".$certificates_code."'," .
			"cus_contact='".$cus_contact. "'," .
			"mobiletelephone='".$mobiletelephone."'," .
			"email='".$email."'," .
			"img1='".$img1."',img2='".$img2."' WHERE gid=".$cus_id ;
			
			ss_log("update_sql:".$sql);
			$GLOBALS['db']->query($sql);
			return $cus_id;
		}
		else
		{	
			//add platformid by dingchaoyang 2014-12-19 则给platform_id赋值
			$ROOT_PATH__= str_replace ( 'includes/class/Customer.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
			include_once ($ROOT_PATH__ . 'api/EBaoApp/platformEnvironment.class.php');
			$platform_id = PlatformEnvironment::getPlatformID();
			//end
			$$type = 1;//0,被保险人，1投保人,2两者身份相同
			$sql = "INSERT INTO t_group_info (group_name,type, group_certificates_type_unify, group_certificates_code,cus_contact, " .
				"mobiletelephone,email,agent_uid,img1,img2,platform_id)" .
				" VALUES ('$fullname','$type','$certificates_type','$certificates_code','$cus_contact','$mobiletelephone','$email','$agent_uid','$img1','$img2','$platform_id')";
			
			ss_log("INSERT_sql:".$sql);
			$GLOBALS['db']->query($sql);
			$uid = $GLOBALS['db']->insert_id(); //发票ID
			return $uid;
		}
		

	}
	
	public function getOrganizationCustomerCount() {
		$sql = "SELECT COUNT(*) FROM t_group_info WHERE agent_uid = " . $this->agent_uid . $this->where;
		return $GLOBALS['db']->getOne($sql);
	}
	
	public function getOrganizationCusById($uid) {
		$sql = "SELECT * FROM t_group_info WHERE agent_uid=" . $this->agent_uid . " AND gid=" . $uid;
		$cus = $GLOBALS['db']->getRow($sql);
		$cus['certificates_type']=$cus['group_certificates_type_unify'];
		$cus['certificates_code']=$cus['group_certificates_type_unify'];
		$cus['fullname']=$cus['group_name'];
		$cus['uid']=$cus['gid'];
		return $cus;
	}
	
	public function delOrganizationCustomer($uid){
		$sql = "DELETE FROM t_group_info WHERE gid=" . $uid;
		ss_log("删除客户：".$sql);
		$r = $GLOBALS['db']->query($sql);
		if($r)
		{
			return array('code'=>0,'msg'=>"删除成功");
		}else
		{
			return array('code'=>1,'msg'=>"删除失败");
		}
	}
}
?>