<?php
/**
 * 用户信息
 * $Author: dingchaoyang $
 * 2014-11-07 $
 */
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/profile/profile.class.php', '', str_replace ( '\\', '/', __FILE__ ) );

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');
class ProfileSuccess extends BaseResponse implements IResponse {
	private $infos;
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_PROFILESUCCESS_CONTENT;
		$this->status = new ResStatus ( '0', $message );
		$this->command = APP_COMMAND_PROFILE;
	}
	function initProfile($infos) {
		$this->infos = $infos;
	}
	function responseResult() {
		$profile = array (
				'realname' => $this->nullObj($this->infos ['real_name']) ,
				'username' => $this->nullObj($this->infos ['user_name']),
				'birthday' => $this->nullObj($this->infos ['birthday']),
				'sex' => $this->nullObjToInt($this->infos ['sex']),
				'email' => $this->nullObj($this->infos ['email']),
				'phone' => $this->nullObjToInt($this->infos ['mobile_phone']),
				'IDType' => $this->nullObjToInt($this->infos ['CertificatesType']),
				'IDCode' => $this->nullObj($this->infos ['CardId']),
				'certificateType' => $this->nullObjToInt($this->infos ['Category']),
				'certificateID' => $this->nullObj($this->infos ['CertificateNumber']),
				'region' => array (
						'country' => '中国',
						'province' => $this->nullObj($this->infos ['Province']),
						'city' => $this->nullObj($this->infos ['city']) 
				),
				'address' => $this->nullObj($this->infos ['address']),
				'postCode' => $this->nullObj($this->infos ['ZoneCode']),
				'IDFrontUrl' => empty ( $this->infos ['card_img1'] ) ? '' : '/' . $this->infos ['card_img1'], // 身份证正面照
				'IDBackUrl' => empty ( $this->infos ['card_img2'] ) ? '' : '/' . $this->infos ['card_img2'], // 身份证北面照
				'certificateUrl' => empty ( $this->infos ['certificate_img'] ) ? '' : '/' . $this->infos ['certificate_img'], // 证件照
				'avatar' => empty ( $this->infos ['avatar'] ) ? '' : '/' . $this->infos ['avatar'] 
		) // 用户头像
;
		$result = array_merge ( parent::responseResult (), $profile );
		return $result;
	}
}
class ProfileFail extends BaseResponse implements IResponse {
	private $infos;
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_PROFILEFAIL_CONTENT;
		$this->status = new ResStatus ( '1', $message );
		$this->command = APP_COMMAND_PROFILE;
	}
	function responseResult() {
		$result = parent::responseResult ();
		return $result;
	}
}
?>