<?php
/**
 * 保单列表
 * $Author: dingchaoyang $
 * 2014-11-28 $
 */
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/policyList/eba_policyList.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
// require($ROOT_PATH_ . 'includes/init.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_PolicyListSuccess extends BaseResponse implements IResponse{
	private $list;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_POLICYLISTSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_POLICYLIST;
		
	}
	
	function setData($data){
		foreach ($data as $key=>$value){
			$this->list[$key]['policyNO']=$value['policy_no'];//保单号
			$this->list[$key]['policyID']=$value['policy_id'];//投保单号
			$this->list[$key]['goodName'] = $value['attribute_name'];//险种名称
			$this->list[$key]['policyHolder'] = $value['applicant_username'];//投保人
			$this->list[$key]['policyPremium'] = $value['total_premium'];//保费
			$this->list[$key]['policyDate'] = $value['policyDate'];//投保时间
			$this->list[$key]['startDate'] = date('Y-m-d',strtotime($value['start_date']));//保险起期
			$this->list[$key]['endDate'] = date('Y-m-d',strtotime($value['end_date']));//保险止期
			$this->list[$key]['policyStatus'] = $value['policy_status'];//保单状态 saved 已保存",insured 已投保,canceled 已注销
		}
	}
	
	function responseResult(){
		$base = parent::responseResult();
		$curr = array('policyList'=>$this->list);
		return array_merge($base,$curr);
	}
}

class Eba_PolicyListNoData extends BaseResponse implements IResponse{
	private $list;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_POLICYLISTNODATA_CONTENT;
		$this->status = new ResStatus('2', $message);
		$this->command = APP_COMMAND_POLICYLIST;

	}

	function responseResult(){
		return parent::responseResult();
	}
}

class Eba_PolicyListFail extends BaseResponse implements IResponse{
	private $list;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_POLICYLISTFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_POLICYLIST;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

?>