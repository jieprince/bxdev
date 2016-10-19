<?php
class Software {
	private $platformId;
	private $editionId;
	private $partner;
	private $language;
	private $country;
	private $brand;
	private $model;
	private $imei;
	private $imsi;
	private $token;
	function __construct() {
		
		$ROOT_PATH_ = str_replace ( 'api/EBaoApp/requestData/software.php', '', str_replace ( '\\', '/', __FILE__ ) );
		// print_r(json_encode($userAgentArray));
		include_once ($ROOT_PATH_.'api/EBaoApp/constant.php');
		include_once ($ROOT_PATH_.'api/EBaoApp/platformEnvironment.class.php');
		$userAgentArray = PlatformEnvironment::ParseUseragent();
		$this->editionId = $userAgentArray [EDITIONID];
		$this->platformId = $userAgentArray [PLATFORMID];
		$this->token = $userAgentArray [DEVICETOKEN];
		// echo $this->editionId. '--'.$this->platformId.'--'.$this->token;
		$this->partner = isset ( $_REQUEST ['partner'] ) ? $_REQUEST ['partner'] : '';
		$this->language = isset ( $_REQUEST ['language'] ) ? $_REQUEST ['language'] : '';
		$this->country = isset ( $_REQUEST ['country'] ) ? $_REQUEST ['country'] : '';
		$this->brand = isset ( $_REQUEST ['brand'] ) ? $_REQUEST ['brand'] : '';
		$this->model = isset ( $_REQUEST ['model'] ) ? $_REQUEST ['model'] : '';
		$this->imei = isset ( $_REQUEST ['imei'] ) ? $_REQUEST ['imei'] : '';
		$this->imsi = isset ( $_REQUEST ['imsi'] ) ? $_REQUEST ['imsi'] : '';
	}
	// 更新数据库
	public function exeData() {
		// 查询
		$searchSql = "select * from bx_app_client_info where uid='" . $_SESSION ['user_id'] . "' and platformId='" . $this->platformId . "'";
// 		 echo $searchSql;
		
		$sSet = $GLOBALS ['db']->getRow ( $searchSql );
		// print_r($sSet);
		// echo count ( $sSet );
		if (count ( $sSet ) > 1) {
			if ($sSet ['token'] != $this->token) {
				// 更新数据库
				$updateSql = "update bx_app_client_info set token = '" . $this->token . "',editionId = '" . $this->editionId . "',platformId = '" . $this->platformId . "' where uid=" . $_SESSION ['user_id'];
				// echo $updateSql;
				$GLOBALS ['db']->query ( $updateSql );
			}
		} else {
			// 插入
			$sql = "INSERT INTO " . $GLOBALS ['ecs']->table ( 'app_client_info' ) . "(uid," . "platformId," . "editionId,token," . "partner," . "LANGUAGE," . "country," . "brand," . "model," . "imei," . "imsi)" . "VALUES('" . $_SESSION ['user_id'] . "','" . $this->platformId . "','" . $this->editionId . "','" . $this->token . "','" . $this->partner . "','" . $this->language . "','" . $this->country . "','" . $this->brand . "','" . $this->model . "','" . $this->imei . "','" . $this->imei . "')";
			// echo $sql;
			$GLOBALS ['db']->query ( $sql );
		}
	}
}

?>