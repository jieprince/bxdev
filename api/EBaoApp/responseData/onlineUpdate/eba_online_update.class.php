<?php
/**
 * 在线更新
 * $Author: dingchaoyang $
 * 2015-1-7 $
 */
//
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/onlineUpdate/eba_online_update.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/platformEnvironment.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/eba_sessionManager.class.php');

// include_once ($ROOT_PATH_ . 'includes/init.php');
class OnlineUpdate extends BaseResponse implements IResponse {
	private $fileInfo;
	// private $updateContent;
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_ONLINEUPDATECONTENT;
		$this->status = new ResStatus ( '0', $message );
		$this->command = APP_COMMAND_ONLINEUPDATE;
		
		$this->setData ( $_REQUEST );
	}
	private function setData($data) {
		global $GLOBALS;
		$sql = "SELECT * FROM bx_app_online_update where releaseFlag=1 and platformId='".PlatformEnvironment::getPlatformID()."'";
		$resSet = $GLOBALS ['db']->getRow ( $sql );
		if ($resSet) {
//  			echo PlatformEnvironment::getEditionID().'--'.$resSet ['version'];
			if (PlatformEnvironment::getEditionID() != $resSet ['version']) {
				// 文件实际大小
				$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/onlineUpdate/eba_online_update.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
				$fileName = 'mobile/app_apk/' . $resSet ['fileName'];
				$size = filesize ( $ROOT_PATH_ . $fileName );
				// echo $ROOT_PATH_ . 'mobile/app_apk/' . $resSet['fileName'];
				$this->fileInfo = array (
						'version' => $resSet ['version'],
						'size' => empty($size)?0:$size,
						'appUrl' => '/' . $fileName,
						'updateContent' => $resSet ['content'],
						'update' => 0 
				);
			} else {
				$this->fileInfo = array (
						'update' => 1 
				);
			}
		}
	}
	function responseResult() {
		$base = parent::responseResult ();
		$curr = array_merge ( $base, array (
				'fileInfo' => $this->fileInfo 
		) );
		return $curr;
	}
}

?>