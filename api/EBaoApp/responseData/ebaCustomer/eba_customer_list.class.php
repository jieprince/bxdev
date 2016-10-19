<?php
/**
 * 客户信息列表
 * $Author: dingchaoyang $
 * 2014-12-13 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/ebaCustomer/eba_customer_list.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_CustomerListSuccess extends BaseResponse implements IResponse{
	private $list;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_CUSTOMERLISTSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_CUSTOMERLIST;
	}
	
	function setData($data){
		foreach ($data as $key=>$value){
			$this->list[$key]['customerID'] = $value['uid'];//客户id
			$this->list[$key]['customerType'] = $value['cusType'];//客户类型
			$this->list[$key]['customerName'] = $value['fullname'];//客户名称
// 			$this->list[$key]['customerContact'] = $value['fullname'];//联系人 渠道客户
			$this->list[$key]['certificateType'] = $value['cer_type'];//证件类型
			$this->list[$key]['certificateCode'] = $value['certificates_code'];//证件号码
			$this->list[$key]['customerContact'] = (isset($value['cus_contact'])&&!empty($value['cus_contact']))?$value['cus_contact']:'';//证件号码
			$this->list[$key]['telPhone'] = $value['mobiletelephone'];//手机号
			$this->list[$key]['email'] = $value['email'];//邮箱
			$this->list[$key]['image1Url'] = $value['img1'];//图片1
			$this->list[$key]['image2Url'] = $value['img2'];//图片2
			$this->list[$key]['policyCount'] = $value['policyCount'];//保单个数
		}
	}
	
	function responseResult(){
		$base = parent::responseResult();
		$curr = array('customer'=>$this->list);
		return array_merge($base,$curr);
	}
}

class Eba_CustomerListNoData extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_CUSTOMERLISTNODATA_CONTENT;
		$this->status = new ResStatus('2', $message);
		$this->command = APP_COMMAND_CUSTOMERLIST;
	}

	function responseResult(){
		$base = parent::responseResult();
		return $base;
	}
}

class Eba_CustomerListFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_CUSTOMERLISTNODATA_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_CUSTOMERLIST;
	}

	function responseResult(){
		$base = parent::responseResult();
		return $base;
	}
}

?>