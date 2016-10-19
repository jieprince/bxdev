<?php
/**
 * 险种及产品
 * $Author: dingchaoyang $
 * 2014-11-14 $
 */
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/insuranceKind/insurance.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
// include_once ($ROOT_PATH_ . 'includes/init.php');
include_once ($ROOT_PATH_ . 'includes/class/commonUtils.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/platformEnvironment.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');
class EBAInsuranceSuccess extends BaseResponse implements IResponse {
	private $info;
	private $insureBaseData;
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_INSURANCESUCCESS_CONTENT;
		$this->status = new ResStatus ( '0', $message );
		$this->command = APP_COMMAND_INSURANCE;
		
		$this->insureBaseData ();
	}
	
	// 保险公司的证件类型 关系数据
	private function insureBaseData() {
		// 华泰
		$htsHolderKey = array (
				'1',
				'3' 
		);
		$htsHolderName = array (
				'身份证',
				'护照' 
		);
		$HTSHolder = array (
				'key' => $htsHolderKey,
				'name' => $htsHolderName 
		);
		// 投保人证件类型
		$HTSInsuredKey = array (
				'1',
				'2',
				'3',
				'4',
				'5',
				'6' 
		);
		$HTSInsuredName = array (
				'身份证',
				'军官证',
				'护照',
				'驾照',
				'返乡证',
				'其他' 
		);
		$HTSInsured = array (
				'key' => $HTSInsuredKey,
				'name' => $HTSInsuredName 
		);
		// 被保险人证件类型
		$HTSRelationKey = array (
				'1',
				'2',
				'3',
				'5' 
		);
		$HTSRelationName = array (
				'配偶',
				'子女',
				'父母',
				'本人' 
		);
		$HTSRelation = array (
				'key' => $HTSRelationKey,
				'name' => $HTSRelationName 
		);
		//团体投保人证件类型
		$HTSOgnHolderKey = array('7');
		$HTSOgnHolderName = array('组织机构代码');
		$HTSOgnHolder = array('key'=>$HTSOgnHolderKey,'name'=>$HTSOgnHolderName);
		//性别
		$HTSSexKey = array('M','F');
		$HTSSexName = array('男','女');
		$HTSSex = array('key'=>$HTSSexKey,'name'=>$HTSSexName);
		// 关系
		$HTS = array (
				'code' => 'HTS',
				'holder' => $HTSHolder,
				'insured' => $HTSInsured,
				'relation' => $HTSRelation ,
				'ognHolder'=>$HTSOgnHolder,
				'sex'=>$HTSSex
		);
		
		// 平安
		$PACHolderKey = array (
				'01',
				'02',
				'03',
				'05',
				'06',
				'99' 
		);
		$PACHolderName = array (
				'身份证',
				'护照',
				'军人证',
				'驾驶证',
				'港澳回乡证或台胞证',
				'其他' 
		);
		$PACHolder = array (
				'key' => $PACHolderKey,
				'name' => $PACHolderName 
		);
		// 投保人证件类型
		$PACInsuredKey = array (
				'01',
				'02',
				'03',
				'05',
				'06',
				'99' 
		);
		$PACInsuredName = array (
				'身份证',
				'护照',
				'军人证',
				'驾驶证',
				'港澳回乡证或台胞证',
				'其他' 
		);
		$PACInsured = array (
				'key' => $PACInsuredKey,
				'name' => $PACInsuredName 
		);
		// 被保险人证件类型
		$PACRelationKey = array (
				'1',
				'2',
				'3',
				'4',
				'5',
				'6',
				'7',
				'A',
				'B',
				'C',
				'D',
				'G',
				'H',
				'I',
				'9',
				'8' 
		);
		$PACRelationName = array (
				'本人',
				'配偶',
				'父子',
				'父女',
				'受益人',
				'被保人',
				'投保人',
				'母子',
				'母女',
				'兄弟 ',
				'姐弟',
				'祖孙',
				'雇佣',
				'子女',
				'其他',
				'转换不详' 
		);
		$PACRelation = array (
				'key' => $PACRelationKey,
				'name' => $PACRelationName 
		);
		// 关系
		//团体投保人证件类型
		$PACOgnHolderKey = array('01','02','03');
		$PACOgnHolderName = array('组织机构代码','税务登记证','异常证件');
		$PACOgnHolder = array('key'=>$PACOgnHolderKey,'name'=>$PACOgnHolderName);
		//性别
		$PACSexKey = array('M','F');
		$PACSexName = array('男','女');
		$PACSex = array('key'=>$PACSexKey,'name'=>$PACSexName);
		$PAC = array (
				'code' => 'PAC',
				'holder' => $PACHolder,
				'insured' => $PACInsured,
				'relation' => $PACRelation,
				'ognHolder'=>$PACOgnHolder,
				'sex'=>$PACSex
		);
		
		// 太平洋
		$TBCHolderKey = array (
				'1',
				'2',
				'3',
				'4',
				'5' 
		);
		$TBCHolderName = array (
				'身份证',
				'护照',
				'军官证',
				'驾照',
				'其他' 
		);
		$TBCHolder = array (
				'key' => $TBCHolderKey,
				'name' => $TBCHolderName 
		);
		// 投保人证件类型
		$TBCInsuredKey = array (
				'1',
				'2',
				'3',
				'4',
				'5' 
		);
		$TBCInsuredName = array (
				'身份证',
				'护照',
				'军官证',
				'驾照',
				'其他' 
		);
		$TBCInsured = array (
				'key' => $TBCInsuredKey,
				'name' => $TBCInsuredName 
		);
		// 被保险人证件类型
		$TBCRelationKey = array (
				'1',
				'2',
				'3',
				'4',
				'5',
				'6',
				'7' 
		);
		$TBCRelationName = array (
				'本人',
				'配偶',
				'子女',
				'父母',
				'女儿',
				'其他',
				'儿子' 
		);
		$TBCRelation = array (
				'key' => $TBCRelationKey,
				'name' => $TBCRelationName 
		);
		// 关系
		//团体投保人证件类型
		$TBCOgnHolderKey = array('6');
		$TBCOgnHolderName = array('组织机构代码');
		$TBCOgnHolder = array('key'=>$TBCOgnHolderKey,'name'=>$TBCOgnHolderName);
		//性别
		$TBCSexKey = array('1','2');
		$TBCSexName = array('男','女');
		$TBCSex = array('key'=>$TBCSexKey,'name'=>$TBCSexName);
		$TBC = array (
				'code' => 'TBC01',
				'holder' => $TBCHolder,
				'insured' => $TBCInsured,
				'relation' => $TBCRelation,
				'ognHolder' =>$TBCOgnHolder,
				'sex'=>$TBCSex
		);
		
		
		$this->insureBaseData = array (
				$HTS,
				$TBC,
				$PAC 
		);
	}
	public function setData($infos) {
		$i = 0;
		foreach ( $infos as $key => $value ) {
			if (intval ( $value ['is_show_in_app'] ) == 0) {
				continue;
			}
			// 险种信息
			$this->info [$i] ['insureCode'] = $value ['insurer_code']; // 保险公司代码
			$this->info [$i] ['catgoryID'] = $value ['cat_id']; // 险种大类Id
			$this->info [$i] ['id'] = $value ['goods_id']; // 险种Id
			$this->info [$i] ['name'] = $value ['goods_name']; // 险种名称
			$this->info [$i] ['isShowWebView'] = $value ['is_show_in_appwebview']; //此险种需要webview打开
			if ($value ['is_show_in_appwebview']){//webview的url
				$this->info[$i]['webUrl'] = "/mobile/goods.php?id=".$this->info [$i] ['id']."&uid=".ResUser::getInstance()->encryptedUid.'&platformId='.PlatformEnvironment::getPlatformID();
			}
			$this->info [$i] ['startDay'] = $value ['start_day']; // 控制保险起始日期是哪一天。
			                                                      // 险种上默认显示的承保年龄 保险期限                                        
			
			$defaultInfo = $value ['ins_product_list_select'];
			$this->info [$i] ['minAge'] = $defaultInfo ['age_min']; // 最小承保年龄
			$this->info [$i] ['maxAge'] = $defaultInfo ['age_max']; // 最大承保年龄
			$this->info [$i] ['premium'] = $defaultInfo ['premium']; // 保费
			$this->info [$i] ['number'] = $defaultInfo ['number']; // 最大投保份数
			$this->info [$i] ['period'] = $defaultInfo ['period']; // 承保期限
			$this->info [$i] ['rate'] = $value ['rate_myself']; // 服务费率
			$this->info [$i] ['favorite'] = empty ( $value ['is_collect'] ) ? "0" : "1"; // 是否已收藏
			                                                                             // 产品特色
			$this->info [$i] ['characteristic'] = htmlspecialchars ( $value ['product_characteristic'] );
			// 简介
			$this->info [$i] ['des'] = /*SERVERURL .*/ "/goodinfos.php?goodid=" . $value ['goods_id'] . "&field=description";
			// 投保须知
			$this->info [$i] ['notice'] = /*SERVERURL .*/ "/goodinfos.php?goodid=" . $value ['goods_id'] . "&field=cover_note";
			// //特别约定
			// $this->info [$i] ['appoint'] =SERVERURL . "/goodinfos.php?goodid=". $value ['goods_id'] . "&field=limit_note";
			// 保险条款
			global $GLOBALS;
			$query = "SELECT insurance_clauses FROM	bx_goods, t_insurance_product_attribute
				WHERE bx_goods.tid = t_insurance_product_attribute.attribute_id
		  		AND bx_goods.goods_id = '" . $value ['goods_id'] . "'";
			// echo $query;
			$resSet = $GLOBALS ['db']->getRow ( $query );
			
			$term = CommonUtils::getAllATag ( stripslashes($resSet ['insurance_clauses']) );
			
			$this->info [$i] ['term'] = $term; // SERVERURL . "/goodinfos.php?goodid=" . $value ['goods_id'] . "&field=insurance_clauses";
			                                   // 理赔指南
			$this->info [$i] ['claim'] =/* SERVERURL .*/ "/goodinfos.php?goodid=" . $value ['goods_id'] . "&field=claims_guide";
			// 投保声明
			$this->info [$i] ['declare'] = /*SERVERURL .*/ "/goodinfos.php?goodid=" . $value ['goods_id'] . "&field=insurance_declare";
			// 产品列表
			$j = 0;
			$list = array ();
			foreach ( $value ['ins_product_list'] as $product ) {
				if ($product ['is_show_in_app'] == '0') {
					continue;
				}
				// 获取保障范围
				$cover;
				global $GLOBALS;
				$query = "SELECT * FROM t_insurance_product_duty WHERE product_id = '" . $product ['product_id'] . "'";
				$resSet = $GLOBALS ['db']->getAll ( $query );
				if ($resSet) {
					foreach ( $resSet as $key => $value ) {
						$cover [$key] ['dutyName'] = $value ['duty_name']; // 保障名称
						$cover [$key] ['dutyAmount'] = $value ['amount']; // 保障价格
						$cover [$key] ['dutyDes'] = $value ['duty_note']; // 保障描述
					}
				}
				$list [$j] = array (
						'productId' => $product ['product_id'],
						'productCode' => $product ['product_code'],
						'productName' => $product ['product_name'],
						'minAge' => $product ['age_min'],
						'maxAge' => $product ['age_max'],
						'premium' => $product ['premium'],
						'number' => $product ['number'],
						'period' => $product ['period'],
						
						// 保障范围
						'coverage' => $cover, // SERVERURL . "/goodinfos_coverage.php?productid=" . $product ['product_id'] . " ",
						                      
						// 旅游保险的保险期限和价格对应
						'periodList' => $this->periodData ( $product ['product_id'] ) 
				);
				
				$j ++;
			}
			$this->info [$i] ['productList'] = $list;
			$i ++;
		}
	}
	
	// 旅游险 需要处理保险期限 和 保费的下拉数据和对应关系
	private function periodData($productId) {
		$attr_period = array (
				"1-5天" => 5,
				"6-10天" => 10,
				"11-15天" => 15,
				"16-20天" => 20,
				"21-30天" => 30,
				"31-45天" => 45,
				"46-62天" => 62,
				"63-92天" => 92,
				"93-183天" => 183,
				"一年（多次往返）" => 365,
				"一年（无时间限制）" => 365 
		);
		
		global $GLOBALS;
		$query = "
			SELECT 
			  factor_name,factor_code,factor_price 
			FROM
			  t_insurance_product_influencingfactor 
			WHERE product_influencingfactor_type = 'period' 
			  AND product_id = '" . $productId . "'";
		// echo $query;
		$resSet = $GLOBALS ['db']->getAll ( $query );
		foreach ( $resSet as $key => $value ) {
			$arr [$key] = array (
					'period' => $value ['factor_name'],
					'price' => $value ['factor_price'],
					'periodCode' => $value ['factor_code'], // add by wangcya,20141126
					'periodValue' => $attr_period [$value ['factor_name']] 
			);
		}
		return $arr;
	}
	function responseResult() {
		$crr = array (
				'data' => $this->info 
		);
		$insureBase = array (
				'insureBaseData' => $this->insureBaseData 
		);
		$crr = array_merge ( $insureBase, $crr );
		return array_merge ( parent::responseResult (), $crr );
	}
}
class EBAInsuranceNoData extends BaseResponse implements IResponse {
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_INSURANCENoData_CONTENT;
		$this->status = new ResStatus ( '2', $message );
		$this->command = APP_COMMAND_INSURANCE;
	}
	function responseResult() {
		return parent::responseResult ();
	}
}
class EBAInsuranceFail extends BaseResponse implements IResponse {
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_INSURANCEFAIL_CONTENT;
		$this->status = new ResStatus ( '1', $message );
		$this->command = APP_COMMAND_INSURANCE;
	}
	function responseResult() {
		return parent::responseResult ();
	}
}
?>