<?php
/**
 * 客户证件类型
 * $Author: dingchaoyang $
 * 2014-12-15 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/ebaCustomer/eba_customer_cer.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_CustomerCerList extends BaseResponse implements IResponse{
	private $list;
	private $ognlist;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message ->title = APP_ALERT_TITLE;
		$message->content = APP_CUSTOMERCERSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_CUSTOMERCER;
	}
	
	function setData($data,$data2){

		$i=0;
		foreach ($data as $key=>$value){
			$this->list[$i]['code'] = $key;
			$this->list[$i]['typeValue'] = $value;
			$i++;
		}
		
		$j=0;
		foreach ($data2 as $key=>$value){
			$this->ognlist[$j]['code'] = $key;
			$this->ognlist[$j]['typeValue'] = $value;
			$j++;
		}
	}
	
	function responseResult(){
		$base = parent::responseResult();
		$psn = array('psnCertificate'=>$this->list);
		$curr = array_merge($base,$psn);
		$ogn = array('ognCertificate'=>$this->ognlist);
		return array_merge($curr,$ogn);
	}
}

?>