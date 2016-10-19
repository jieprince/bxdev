<?php
/**
 * $Author: dingchaoyang $
 * 2014-11-04 $
 */
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
include_once 'platformEnvironment.class.php';
include_once 'constant.php';
if (! (PlatformEnvironment::isMobilePlatform ())) {
	return;
}
if (isset ( $_REQUEST ['command'] )) {
	if (! ($_REQUEST ['command'] == APP_COMMAND_TEMPPOLICY) && ! ($_REQUEST ['command'] == APP_COMMAND_DOWNEPOLICY && ! ($_REQUEST ['command'] == APP_COMMAND_LIMITPURCHASE))) {
		include_once ($ROOT_PATH_ . 'includes/init.php');
	}
}

// include_once ($ROOT_PATH_ . 'api/EBaoApp/responseFactory.php');
// include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');

include_once 'platformEnvironment.class.php';
define ( 'INCHASET', 'utf-8' );
define ( 'OUTCHASET', 'gb2312//IGNORE' );
class EbaAdapter {
	
	// 响应报文的函数
	private static function exitResponse($result) {
		self::logResponse ( $result );
		exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
	}
	
	// 打印日志的函数
	private static function logResponse($result) {
		if (1) {
			include_once 'eba_logManager.class.php';
			
			Eba_LogManager::log ( ' Response ' . iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
		}
	}
	
	// 不需要返回除了基础数据外的接口，使用此方法
	public static function responseData($className) {
		if (PlatformEnvironment::isMobilePlatform ()) {
			
			// 处理请求过来的数据
			if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_LOGIN) {
				include_once (ROOT_PATH . 'api/EBaoApp/requestData/baseRequset.php');
				$request = new BaseRequest ();
				$request->exeData ();
			}
			
			// 响应数据
			include_once 'responseFactory.php';
			
			$response = ResponseFactory::createResponse ( $className );
			// $response = new $className;
			$result = $response->responseResult ();
			// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
			self::exitResponse ( $result );
		}
	}
	
	// 短信验证码
	public static function responseSmsCheckCode($checkCode) {
		// exit("fdsaf".$_SGLOBAL ['mobile_type']);
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_SMS) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				include_once 'responseFactory.php';
				$response = ResponseFactory::createResponse ( 'SmsCheckCode' );
				// exit($response);
				$response->checkCode = $checkCode;
				$result = $response->responseResult ();
				// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				self::exitResponse ( $result );
			}
		}
	}
	
	// 用户资料信息
	public static function responseProfile($infos) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_PROFILE) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				
				if (count ( $infos ) > 0) {
					include_once 'responseFactory.php';
					$response = ResponseFactory::createResponse ( 'ProfileSuccess' );
					$response->initProfile ( $infos );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					$response = ResponseFactory::createResponse ( 'ProfileFail' );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 会员动态信息
	public static function responseMemberNews($infos) {
		// echo ("responseMemberNews".$_REQUEST ['command']);
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_MEMBERNEWS) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				if (count ( $infos ) > 0) {
					include_once 'responseFactory.php';
					$response = ResponseFactory::createResponse ( 'MemberNewsSuccess' );
					$response->setNews ( $infos );
					// $result = $response->responseResult ();
					// print_r($infos);
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					include_once 'responseFactory.php';
					$response = ResponseFactory::createResponse ( 'MemberNewsFail' );
					// $result = $response->responseResult ();
					// print_r($infos);
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 资讯信息
	public static function responseArticleInfo($infos) {
		// echo ("responseArticleInfo".$_REQUEST ['command']);
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_ARTICLEINFO) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				if (count ( $infos ) > 0) {
					include_once 'responseFactory.php';
					$response = ResponseFactory::createResponse ( 'ArticleInfoSuccess' );
					$response->setArticle ( $infos );
					// $result = $response->responseResult ();
					// print_r($infos);
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					include_once 'responseFactory.php';
					$response = ResponseFactory::createResponse ( 'ArticleInfoFail' );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 热销产品
	public static function responseHotProduct($infos) {
		// echo ("responseHotProduct".$_REQUEST ['command']);
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_HOTPRODUCT) {
			
			if (PlatformEnvironment::isMobilePlatform ()) {
				if (count ( $infos ) > 0) {
					include_once 'responseFactory.php';
					$response = ResponseFactory::createResponse ( 'HotProductSuccess' );
					$response->setHotProduct ( $infos );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					include_once 'responseFactory.php';
					$response = ResponseFactory::createResponse ( 'HotProductFail' );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 根据险种大类id,返回产品列表，返回险种及其下面的产品
	public static function responseProductList($infos) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_INSURANCE) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				if (count ( $infos ) > 0) {
					include_once 'responseFactory.php';
					$response = ResponseFactory::createResponse ( 'EBAInsuranceSuccess' );
					$response->setData ( $infos );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} elseif (count ( $infos ) == 0) {
					include_once 'responseFactory.php';
					$response = ResponseFactory::createResponse ( 'EBAInsuranceNoData' );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					include_once 'responseFactory.php';
					$response = ResponseFactory::createResponse ( 'EBAInsuranceFail' );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 根据收藏的险种id,返回产品列表，返回险种及其下面的产品
	public static function responseFavoriteProductList($infos) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == "favoriteinskind") {
			
			if (PlatformEnvironment::isMobilePlatform ()) {
				if (count ( $infos ) > 0) {
					include_once 'responseFactory.php';
					$response = ResponseFactory::createResponse ( 'EBAInsuranceSuccess' );
					$response->setData ( $infos );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} elseif (count ( $infos ) == 0) {
					include_once 'responseFactory.php';
					$response = ResponseFactory::createResponse ( 'EBAInsuranceNoData' );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					include_once 'responseFactory.php';
					$response = ResponseFactory::createResponse ( 'EBAInsuranceFail' );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 投保单，返回投保单id
	public static function responseTempPolicy($infos) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_TEMPPOLICY) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/tempPolicy/tempPolicy.class.php');
				if (! empty ( $infos )) {
					$response = new TempPolicySuccess ();
					$response->setData ( $infos );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					$response = new TempPolicyFail ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 提交订单,如果余额全部支付订单金额，会进行投保。返回带保单号
	public static function responseOrderSubmit($info='', $payUrl='') {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_ORDERSUBMIT) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/submitOrder/orderSubmit.class.php');
				if (! empty ( $info )) {
					$response = new OrderSubmitSuccess ();
					$response->setData ( $info, $payUrl );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} elseif ($info == "0") {
					$response = new OrderSubmitExist ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					$response = new OrderSubmitFail ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 查询投保结果：第三方支付方式 支付成功后，服务器进行投保
	public static function responsePolicyResult($info) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_POLICYRESULT) {
			// echo $_REQUEST ['command']
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/policyResult/policyResult.class.php');
				if ($info) {
					$response = new PolicyResult ();
					$response->setData ( $info );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 我的订单
	public static function responseOrderList($data) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_ORDERLIST) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/orderList/orderList.class.php');
				if ($data == '2' || $data == null) {
					$response = new Eba_OrderNoList ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} elseif (! empty ( $data )) {
					$response = new Eba_OrderList ();
					$response->setData ( $data );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					$response = new Eba_OrderFail ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 取消订单
	public static function responseCancelOrder($data) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_CANCELORDER) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/orderList/cancelOrder.class.php');
				if ($data == '0') {
					$response = new Eba_CancelOrderSuccess ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					$response = new Eba_CancelOrderFail ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 我的保单
	public static function responsePolicyList($data) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_POLICYLIST) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/policyList/eba_policyList.class.php');
				if ($data == '2' || $data == null) {
					$response = new Eba_PolicyListNoData ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} elseif (! empty ( $data )) {
					$response = new Eba_PolicyListSuccess ();
					$response->setData ( $data );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					$response = new Eba_PolicyListFail ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 保单详情
	public static function responsePolicyDetail($data) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_POLICYDETAIL) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/policyList/eba_policy_detail.class.php');
				if (! empty ( $data )) {
					$response = new Eba_PolicyDetailSuccess ();
					$response->setData ( $data );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					$response = new Eba_PolicyDetailFail ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 注销保单
	public static function responseCancelPolicy($data) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_CANCELPOLICY) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/policyList/eba_cancel_policy.class.php');
				if (! empty ( $data )) {
					if ($data ['retcode'] == '0') {
						$response = new Eba_CancelPolicySuccess ();
						$response->setData ( $data ['retmsg'] );
						// $result = $response->responseResult ();
						// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
					} else {
						$response = new Eba_CancelPolicyFail ();
						$response->setData ( $data ['retmsg'] );
						// $result = $response->responseResult ();
						// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
					}
				} else {
					$response = new Eba_CancelPolicyFail ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 找回密码
	public static function responseRetriPassword($data, $mobile) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_RETRIEVEPW) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/retrievePassword/retrievePassword.class.php');
				if ($data == '0') {
					$response = new RetrievePasswordSuccess ();
					$response->setData ( $mobile );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} elseif ($data == '1') {
					$response = new RetrievePasswordFail ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 账单明细
	public static function responseBills($data) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_BILLLIST) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/financeManage/eba_bill.class.php');
				if ($data == '2' || count ( $data ) == 0) {
					$response = new Eba_BillNoData ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} elseif (! empty ( $data )) {
					$response = new Eba_BillSuccess ();
					$response->setData ( $data );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					$response = new Eba_BillFail ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 充值或提现记录
	public static function responseRechargeList($data) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_RECHARGELIST) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/financeManage/eba_recharge_list.class.php');
				if ($data == '2' || count ( $data ) == 0) {
					$response = new Eba_RechargeListNoData ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} elseif (count ( $data ) > 0) {
					$response = new Eba_RechargeListSuccess ();
					$response->setData ( $data );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					$response = new Eba_RechargeListFail ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 充值
	public static function responseRecharge($data, $payUrl) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_RECHARGE) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/financeManage/eba_recharge.class.php');
				if (! empty ( $data )) {
					$response = new Eba_RechargeSuccess ();
					$response->setData ( $data, $payUrl );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					$response = new Eba_RechargeFail ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 下载电子保单
	public static function responseDownEPolicy($data) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_DOWNEPOLICY) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/policyList/eba_down_epolicy.class.php');
				if (intval ( $data ['retcode'] ) == 0) {
					$response = new Eba_EPolicySuccess ();
					$response->setData ( $data ['policyID'] );
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				} else {
					$response = new Eba_EPolicyFail ();
					// $result = $response->responseResult ();
					// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				}
				$result = $response->responseResult ();
				self::exitResponse ( $result );
			}
		}
	}
	
	// 获取银行卡
	public static function responseUnionPay($data) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_UNIONPAY) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/ebaUnionPay/eba_unionpay.class.php');
				if ($data) {
					if (count ( $data ) > 0) {
						$response = new Eba_UnionPaySuccess ();
						$response->setData ( $data );
					} else {
						$response = new Eba_UnionPayNoData ();
					}
				} else {
					$response = new Eba_UnionPayFail ();
				}
				
				$result = $response->responseResult ();
				// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				self::exitResponse ( $result );
			}
		}
	}
	
	// 收益列表
	public static function responseIncomeList($data, $count, $totalAmount) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_IMCOMELIST) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/ebaIncome/eba_income.class.php');
				
				if ($data == '2' || count ( $data ) == 0) {
					$response = new Eba_IncomeListNoData ();
				} elseif (count ( $data ) > 0) {
					$response = new Eba_IncomeListSuccess ();
					$response->setData ( $data, $count, $totalAmount );
				} else {
					$response = new Eba_IncomeListFail ();
				}
				
				$result = $response->responseResult ();
				// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				self::exitResponse ( $result );
			}
		}
	}
	
	// 发票列表
	public static function responseInvoiceList($data) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_INVOICELIST) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/ebaInvoice/eba_invoiceList.class.php');
				
				if ($data == '2' || count ( $data ) == 0) {
					$response = new Eba_InvoiceListNoData ();
				} elseif (count ( $data ) > 0) {
					$response = new Eba_InvoiceListSuccess ();
					$response->setData ( $data, $count, $totalAmount );
				} else {
					$response = new Eba_InvoiceListFail ();
				}
				
				$result = $response->responseResult ();
				// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				self::exitResponse ( $result );
			}
		}
	}
	
	// 我的推荐列表
	public static function responseRecommendList($data, $totalInfo) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_RECOMMENDLIST) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/ebaRecommend/eba_recommendList.class.php');
				
				if ($data == '2' || count ( $data ) == 0) {
					$response = new Eba_RecommendListNoData ();
				} elseif (count ( $data ) > 0) {
					$response = new Eba_RecommendListSuccess ();
					$response->setData ( $data, $totalInfo );
				} else {
					$response = new Eba_RecommendListFail ();
				}
				
				$result = $response->responseResult ();
				// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				self::exitResponse ( $result );
			}
		}
	}
	
	// 图片上传
	public static function responseUplaodImage($data) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_UPLOADIMAGE) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/updateProfile/eba_upload_image.class.php');
				if ($data) {
					if (intval ( $data ['code'] ) == 0) {
						$response = new Eba_UploadImageSuccess ();
						$response->setData ( $data ['pic'] );
					} elseif (intval ( $data ['code'] ) == 3) {
						$response = new Eba_UploadImageNoMatchType ();
					} elseif (intval ( $data ['code'] ) == 4) {
						$response = new Eba_UploadImageOverMax ();
					}
				} else {
					$response = new Eba_UploadImageFail ();
				}
				
				$result = $response->responseResult ();
				// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				self::exitResponse ( $result );
			}
		}
	}
	
	// 客户列表
	public static function responseCustomerList($data) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_CUSTOMERLIST) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/ebaCustomer/eba_customer_list.class.php');
				
				if ($data == '2' || count ( $data ) == 0) {
					$response = new Eba_CustomerListNoData ();
				} elseif (count ( $data ) > 0) {
					$response = new Eba_CustomerListSuccess ();
					$response->setData ( $data );
				} else {
					$response = new Eba_CustomerListFail ();
				}
				
				$result = $response->responseResult ();
				// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				self::exitResponse ( $result );
			}
		}
	}
	
	// 客户证件类型列表
	public static function responseCustomerCerList($data, $data2) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_CUSTOMERCER) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/ebaCustomer/eba_customer_cer.class.php');
				
				if ($data) {
					if (count ( $data ) == 0) {
						// $response = new Eba_CustomerListNoData ();
					} elseif (count ( $data ) > 0) {
						$response = new Eba_CustomerCerList ();
						
						$response->setData ( $data, $data2 );
					}
				} else {
					// $response = new Eba_CustomerListFail ();
				}
				
				$result = $response->responseResult ();
				// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				self::exitResponse ( $result );
			}
		}
	}
	
	// 限制购买
	public static function responseLimitPurchase($purchseable) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_LIMITPURCHASE) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/ebaCheckLimitPurchase/eba_limitPurchase.class.php');
				if ($purchseable) {
					$response = new Eba_Purchaseble_Pingan ();
				} else {
					$response = new Eba_LimitPurchase_Pingan ();
				}
				
				$result = $response->responseResult ();
				// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				self::exitResponse ( $result );
			}
		}
	}
	
	// 会员审核 推送通知
	public static function pushCheckUserNotification($uid, $success) {
		// $ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
		// include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/eba_pushnotification/eba_checkuser_pn.class.php');
		// $push = new Eba_CheckUserPush ( $uid, $success );
		// $push->pushNotification ();
	}
	
	// 在订单列表对未支付订单进行支付宝网页支付 时，返回支付url
	public static function responsePaymentUrl($data) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_ALIPAYURL) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/ebaPayment/eba_payment.class.php');
				if ($data) {
					$response = new Eba_PaymentSuccess ();
					$response->setData ( $data );
				} else {
					$response = new Eba_PaymentFail ();
				}
				
				$result = $response->responseResult ();
				// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				self::exitResponse ( $result );
			}
		}
	}
	
	// 在订单列表对未支付订单进行支付,处理使用余额 时
	public static function responsePayUnpaidOrder($data) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_PAYUNPAIDORDER) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/orderList/eba_payunpaid.class.php');
				if ($data) {
					$response = new Eba_PayUnpaidOrder ();
					$response->setData ( $data );
				} else {
					$response = new Eba_PayUnpaidOrderFail ();
				}
				
				$result = $response->responseResult ();
				// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				self::exitResponse ( $result );
			}
		}
	}
	
	// 收藏产品
	public static function responseFavoriteIns($result) {
		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_FAVORITEINS) {
			if (PlatformEnvironment::isMobilePlatform ()) {
				
				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/insuranceKind/eba_favoriteIns.class.php');
				if (intval ( $result ) == 0) {
					$response = new Eba_FavoriteIns ();
				} elseif (intval ( $result ) == 3) {
					$response = new Eba_CancelFavoriteIns ();
				} else {
					$response = new Eba_FavoriteInsFail ();
				}
				
				$result = $response->responseResult ();
				// exit ( iconv ( INCHASET, OUTCHASET, json_encode ( $result ) ) );
				self::exitResponse ( $result );
			}
		}
	}
	
// 	// 活动
// 	public static function responsePromotions() {
// 		if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == APP_COMMAND_PROMOTION) {
// 			if (PlatformEnvironment::isMobilePlatform ()) {
	
// 				$ROOT_PATH__ = str_replace ( 'api/EBaoApp/eba_adapter.php', '', str_replace ( '\\', '/', __FILE__ ) );
// 				include_once ($ROOT_PATH__ . 'api/EBaoApp/responseData/ebaPromotion/eba_promotion.class.php');
// 				$response = new Eba_Promotion();
	
// 				$result = $response->responseResult ();
// 				self::exitResponse ( $result );
// 			}
// 		}
// 	}
}
?>


