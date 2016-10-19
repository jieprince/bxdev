<?php
/**
 * 
 * $Author: dingchaoyang $
 * 2014-12-10 $
 */
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/insuranceKind/eba_favoriteIns.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
// include_once ($ROOT_PATH_ . 'includes/init.php');
include_once ($ROOT_PATH_ . 'includes/class/commonUtils.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class Eba_FavoriteIns extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_FAVORITEINSSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_FAVORITEINS;
	}
	
	function responseResult(){
		return parent::responseResult();
	}
}

class Eba_FavoriteInsFail extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_FAVORITEINSFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_FAVORITEINS;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

class Eba_CancelFavoriteIns extends BaseResponse implements IResponse{
	function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_CANCELFAVORITEINSSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_FAVORITEINS;
	}

	function responseResult(){
		return parent::responseResult();
	}
}

?>