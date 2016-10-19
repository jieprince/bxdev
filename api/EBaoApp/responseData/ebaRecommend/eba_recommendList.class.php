<?php
/**
 * 推荐列表
 * $Author: dingchaoyang $
 * 2014-12-7 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/ebaRecommend/eba_recommendList.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_RecommendListSuccess extends BaseResponse implements IResponse{
	private $list;
	private $totalInfo;
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_RECOMMENDLISTSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_RECOMMENDLIST;
	}
	
	function setData($data,$totalInfo){
		$this->totalInfo['totalIncome'] = $totalInfo['totalIncome'];//所有被推荐人带来的收益
		$this->totalInfo['recommendCount'] = $totalInfo['recommendCount'];//被推荐人数
		foreach ($data as $key=>$value){
			$this->list[$key]['recommendUserID'] = $value['user_id'];//被推荐人id
			$this->list[$key]['recommendUserName'] = $value['user_name'];//被推荐人登录名
			$this->list[$key]['recommendName'] = $value['real_name'];//被推荐人姓名
			$this->list[$key]['income'] = price_format($value['income_total'],false) ;//被推荐人带来的收益
			$this->list[$key]['orderCount'] = $value['orderCount'];//被推荐人带来的总订单数
			$this->list[$key]['isCheck'] = $value['is_check_key'];//是否正式会员
			$this->list[$key]['regDate'] = $value['reg_time'];//注册时间
		}
	}
	
	function responseResult(){
		$base = parent::responseResult();
		$curr = array_merge($base,$this->totalInfo);
		return array_merge($curr, array('recommend'=>$this->list));
	}
}

class Eba_RecommendListNoData extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_RECOMMENDLISTNODATA_CONTENT;
		$this->status = new ResStatus('2', $message);
		$this->command = APP_COMMAND_RECOMMENDLIST;
	}

	function responseResult(){
		$base = parent::responseResult();
		return $base;
	}
}

class Eba_RecommendListFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_RECOMMENDLISTFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_RECOMMENDLIST;
	}

	function responseResult(){
		$base = parent::responseResult();
		return $base;
	}
}

?>