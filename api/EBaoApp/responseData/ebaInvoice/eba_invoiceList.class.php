<?php
/**
 * 发票列表
 * $Author: dingchaoyang $
 * 2014-12-7 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/ebaInvoice/eba_invoiceList.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_InvoiceListSuccess extends BaseResponse implements IResponse{
	private $list;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_INVOICELISTSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_INVOICELIST;
	}
	
	function setData($data){
		foreach ($data as $key=>$value){
			$this->list[$key]['invoiceID'] = $value['id'];//发票id
			$this->list[$key]['amount'] = $this->nullObjToInt( $value['invoice_total']);//发票金额
			$this->list[$key]['title'] = $value['fp_title'];//发票抬头
			$this->list[$key]['receivedBy'] = $value['username'];//收件人
			$this->list[$key]['linkTel'] = $value['phone'];//联系电话
			$this->list[$key]['phone'] = $value['tel'];//手机号码
			$this->list[$key]['postAddress'] = $value['address'];//邮寄地址
			$this->list[$key]['postCode'] = $value['zonecode'];//邮编
			$this->list[$key]['postMemo'] = $value['postscript'];//附言
			$this->list[$key]['orderCount'] = $this->nullObjToInt( $value['order_count']);//开发票的订单记录数
			$this->list[$key]['date'] = $value['receipt_add_time'];//申请发票时间
			$this->list[$key]['status'] = $value['receipt_assigned']==1?'已处理':'未处理';//处理状态。1已处理，0，未处理
			$this->list[$key]['insurerCompany'] = $value['insurer_name'];//保险公司
			$this->list[$key]['policyHolder'] = $value['applicant_username'];//投保人
		}
	}
	
	function responseResult(){
		$base = parent::responseResult();
		$curr = array('invoice'=>$this->list) ;
		return array_merge($base,$curr);
	}
}

class Eba_InvoiceListNoData extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_INVOICELISTNODATA_CONTENT;
		$this->status = new ResStatus('2', $message);
		$this->command = APP_COMMAND_INVOICELIST;
	}

	function responseResult(){
		$base = parent::responseResult();
		return $base;
	}
}

class Eba_InvoiceListFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_INVOICELISTFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_INVOICELIST;
	}

	function responseResult(){
		$base = parent::responseResult();
		return $base;
	}
}

?>