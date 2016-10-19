<?php
/**
 * 保单列表
 * $Author: dingchaoyang $
 * 2014-11-28 $
 */
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/policyList/eba_policy_detail.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
// require($ROOT_PATH_ . 'includes/init.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');
class Eba_PolicyDetailSuccess extends BaseResponse implements IResponse {
	private $list;
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_POLICYDETAILSUCCESS_CONTENT;
		$this->status = new ResStatus ( '0', $message );
		$this->command = APP_COMMAND_POLICYDETAIL;
	}
	function setData($data) {
		// 保单基本信息
		$this->list ['policyNO'] = $data ['policy_arr'] ['policy_no']; // 保单号
		$this->list ['orderSN'] = $data ['policy_arr'] ['order_sn']; // 订单号
		$this->list ['policyPremium'] = $data ['policy_arr'] ['total_premium']; // 总保费
		$this->list ['startDate'] = date ( 'Y-m-d', strtotime ( $data ['policy_arr'] ['start_date'] ) ); // 保险起期
		$this->list ['endDate'] = date ( 'Y-m-d', strtotime ( $data ['policy_arr'] ['end_date'] ) ); // 保险止期
		$this->list ['relationship'] = $data ['policy_arr'] ['relationship_with_insured_str']; // 被保险人与投保人关系
		$this->list ['beneficiary'] = $data ['policy_arr'] ['beneficiary_str']; // 受益人
		$this->list ['policyStatus'] = $data ['policy_arr'] ['policy_status']; // 保单状态
		$this->list ['policyStatusStr'] = $data ['policy_arr'] ['policy_status_str']; // 保单状态
		$this->list ['bizType'] = $data ['policy_arr'] ['business_type_str']; // 业务类型
		
		if (strtotime(date('Y-m-d')) >= strtotime($this->list ['startDate'])){
			$this->list ['isCancelPolicy'] = '1'; // 不能注销保单
		}else{
			$this->list ['isCancelPolicy'] = '0'; // 可注销保单
		}

		// 投保人信息
		$this->list ['policyHolderInfo'] ['name'] = $data ['user_info_applicant'] ['fullname']; // 投保人姓名
		$this->list ['policyHolderInfo'] ['sex'] = $data ['user_info_applicant'] ['gender_str']; // 性别
		$this->list ['policyHolderInfo'] ['IDType'] = $data ['user_info_applicant'] ['certificates_type_str']; // 证件类型
		$this->list ['policyHolderInfo'] ['IDCode'] = $data ['user_info_applicant'] ['certificates_code']; // 证件号码
		// 产品信息
		$this->list ['productInfo'] ['insuranceName'] = $data ['list_subject'] [0] ['list_subject_product'] [0] ['product_name']; // 险种名称
		$this->list ['productInfo'] ['productName'] = $data ['list_subject'] [0] ['list_subject_product'] [0] ['plan_name']; // 产品名称
		$this->list ['productInfo'] ['period'] = $data ['list_subject'] [0] ['list_subject_product'] [0]['period']; // 保险期限
		$this->list ['productInfo'] ['ageRange'] = $data ['list_subject'] [0] ['list_subject_product'] [0]['age_min'] . '-' . $data ['list_subject'] [0] ['list_subject_product'][0] ['age_max'] . '周岁'; // 承保年龄
		$this->list ['productInfo'] ['number'] = $data ['list_subject'] [0] ['list_subject_product'] [0]['number']; //最大投保份数
		$this->list ['productInfo'] ['premium'] = $data ['list_subject'] [0] ['list_subject_product'][0] ['premium']; //价格
		//被保险人信息
		$this->list ['insuredInfo'] ['name'] = $data ['list_subject'] [0] ['list_subject_insurant'][0] ['fullname']; // 被保险人姓名
		$this->list ['insuredInfo'] ['sex'] = $data ['list_subject'] [0] ['list_subject_insurant'] [0]['gender_str']; // 被保险人性别
		$this->list ['insuredInfo'] ['birthday'] = $data ['list_subject'] [0] ['list_subject_insurant'][0] ['birthday']; // 被保险人生日
		$this->list ['insuredInfo'] ['IDType'] = $data ['list_subject'] [0] ['list_subject_insurant'][0] ['certificates_type_str'] ; // 证件类型
		$this->list ['insuredInfo'] ['IDCode'] = $data ['list_subject'] [0] ['list_subject_insurant'][0] ['certificates_code']; //证件号码
	}
	function responseResult() {
		$base = parent::responseResult();
		$curr = array('policy'=>$this->list);
		return array_merge($base,$curr);
	}
}

class Eba_PolicyDetailFail extends BaseResponse implements IResponse {
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_POLICYDETAILFAIL_CONTENT;
		$this->status = new ResStatus ( '1', $message );
		$this->command = APP_COMMAND_POLICYDETAIL;
	}
	
	function responseResult() {
		return parent::responseResult();
	}
}
?>