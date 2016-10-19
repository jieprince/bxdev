<?php
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseFactory.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
// echo ROOT_PATH;
// include_once ($ROOT_PATH_ . 'includes/init.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/login/login.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/register/RegisterResponse.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/smsCheck/smsCheckCode.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/retrievePassword/retrievePassword.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/profile/profile.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/updateProfile/updateProfile.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/memberNews/memberNews.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/articleInfo/articleInfo.class.php');
// include_once (ROOT_PATH . 'api/EBaoApp/responseData/responseCenter.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/cycleNews/CycleNews.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/hotProduct/hotProduct.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/locationResponse/locationResponse.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/shortShow/shortShow.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/insuranceKind/insuranceKind.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/insuranceKind/insurance.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/logout/eba_logout.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/retrievePassword/eba_modify_password.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/policyList/eba_down_epolicy.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/financeManage/eba_recharge_cancel.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/ebaUnionPay/eba_update_unionpay.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/ebaUnionPay/eba_delete_unionpay.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/ebaInvoice/eba_applyInvoice.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/financeManage/eba_companyRecAccount.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/insuranceKind/eba_insurerCompany.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/ebaSession/eba_session_invalid.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/ebaCustomer/eba_customer_add.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/ebaCustomer/eba_customer_del.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/ebaUnionPay/eba_add_unionpay.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/financeManage/eba_withdraw_cash.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/onlineUpdate/eba_online_update.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/ebaFeedback/eba_feedback.class.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/ebaPromotion/eba_promotion.class.php');

class ResponseFactory{
	public static function createResponse($className){
		if (!class_exists($className)){
// 			echo $className.'fff';
			exit;
		}
		$obj = new $className;
// 		if ($className){
// 			echo "obj is created successfully";
// 			print_r($obj);
// 		}
		return $obj;
	}
}
?>