<?php
/**
 * 响应的基类
 * $Author: dingchaoyang $
 * 2014-11-07 $
 */
class BaseResponse{
	public $user;
	public $status;
	public $command;
	public $version;

	function __construct()
	{
		$this->version = APP_PROTOCOL_VERSION;
		$this->user = ResUser::getInstance();
// 		echo "base response init";
	}

	function responseResult(){
// 		echo $this->command;
		$result = array('command'=>$this->command,
				'version'=>$this->version,
				'userInfo'=>$this->user->responseResult(),
				'status'=>$this->status->responseResult()
		);
		return $result;
	}
	
	function nullObj($obj){
		$root_path__ = str_replace ( 'api/EBaoApp/responseData/baseResponse.php', '', str_replace ( '\\', '/', __FILE__ ) );
		include_once ($root_path__ . 'api/EBaoApp/ebaUtils.class.php');
		return EbaUtils::nullObj($obj);
	}
	
	function nullObjToInt($obj){
		$root_path__ = str_replace ( 'api/EBaoApp/responseData/baseResponse.php', '', str_replace ( '\\', '/', __FILE__ ) );
		include_once ($root_path__ . 'api/EBaoApp/ebaUtils.class.php');
		return EbaUtils::nullObjToInt($obj);
	}
}
?>