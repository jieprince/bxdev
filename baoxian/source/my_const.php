<?php
$CONST_ROOT_PATH_= str_replace ( 'baoxian/source/my_const.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
define('S_ROOT_MY_LOG', dirname(__FILE__).DIRECTORY_SEPARATOR);
include_once(S_ROOT_MY_LOG.'function_debug.php');

define('JAVA_JAR', 'java/pdf_fetcher-1.2.30.11.jar');//最大的每页的个数
//define('JAVA_JAR', 'java/pdf_fetcher-1.2.30.10.jar');//最大的每页的个数
//货运险
//define('JAVA_JAR', 'java/pdf_fetcher-1.2.28.3.jar');//最大的每页的个数
define('JAVA_JAR_INC', 'java/Java.inc');

////////////////////////////////////////////////////////////////
define('MAX_PERPAGE_COUNT', 100);//最大的每页的个数

define('CLIENT_KEY_PINGAN', 'xml/config/EXV_BIS_IFRONT_PCIS_ZTXH_001_PRD.pfx');
define('CLIENT_KEY_PINGAN_DALIAN_Y502', 'xml/config/EXV_BIS_IFRONT_PCIS_ZHTXHBX_001_PRD.PFX');
define('CHANNEL_CODE', 'quanlian');
///////////////////////////////////////////////////////////////////

//define('IS_TEST', true);
//if(IS_TEST)

if(!empty($_SERVER))
{
	$local_port = $_SERVER["SERVER_PORT"];
}
else
{
	$local_port = 82;//尽量用测试环境
}
//////////////////////////////////////////////////////
//ss_log("local_port: ".$local_port);

$use_test_environmental = true;

if( defined('TASK_USE_FORMAL') || $local_port == 80 )
{
	$use_test_environmental = false;
}
else
{
	$use_test_environmental = true;
}


//ss_log("use_test_environmental: ".$use_test_environmental);

if( $use_test_environmental == true ) //add by wangcya, 20150114, 这句话一定不能删除，for task
{//测试环境
    //ss_log("进入测试环境");
	@define('CALLBACK_URL', "http://139.196.24.169:82/baoxian/cp.php?ac=product_buy_process_ret" );//add by wangcya , 20150112, 启用异步
    @define('USE_ASYN_JAVA', true );//add by wangcya , 20150112, 启用异步
    
    @define('EBAO_JAVA_SERVICE_IP',   '123.57.254.110');
    @define('EBAO_JAVA_SERVICE_PORT', '19090');
	@define('EBAO_JAVA_SERVICE_URL', '/ins/bizcenter/transmit/');
	@define('EBAO_JAVA_SERVICE_URL_ASYN', '/WEB_JINHUIJIA/ins/bizcenter/transmit/asyn/notify');//异步
	@define('EBAO_JAVA_SERVICE_URL_SYNC', '/WEB_JINHUIJIA/ins/bizcenter/transmit/sync/unnotify');//同步
	
	
	@define('URL_PINGAN_POST_POLICY', 'https://222.68.184.181:8107');//测试环境
	@define('URL_PINGAN_GET_POLICY_FILE', 'http://epcis-ptp-dmzstg2.pingan.com.cn:7080/epcis.ptp.partner.getAhsEPolicyPDFWithCert.do');//9080 or 7080测试环境

	
	define('URL_DIUBULIAO_CRETE_USER', 'http://adtest.diubuliao.com/open.php?m=Interface&a=create_user');//丢不了测试环境
												
	@define('URL_POST_POLICY_TAIPINGYANG', 'http://116.228.131.213/pcitx/itxsvc/param');//测试环境
	

	@define('URL_HUATAI_POST_POLICY',"http://219.143.162.220:7001/ui/services/ThirdRequest");
	//added by zhangxi, 20141222, 对接华安的测试环境
	@define('URL_HUAAN_POST_POLICY',"http://58.61.28.179:1090");
	@define('URL_HUAAN_XUEPINGXIAN_POST_POLICY',"http://fsllt.sinosafe.com.cn/datong");
	@define('URL_HUAAN_GET_POLICY_FILE', "http://58.251.33.182:18080/elec/netSaleQueryElecPlyServlet");
	@define('HUAAN_EXTENTERPCODE', "0701039292");
	
	//added by zhangxi , 20150323, 对接新华人寿的测试环境
	//新华人寿
	//核保	100001	/insurance/proposal/apply.rest
	//承保	100003	/insurance/policy/apply.rest
	//保单价值查询	100005	/insurance/policy/valueQuery.rest
	//退保核算	100006	/insurance/policy/refundCalc.rest
	//退保	100007	/insurance/policy/refundApply.rest
	//退保回调		
	//保单确认通知	100009	/insurance/policy/confirmNotify.rest
	//保单详细查询	100012	/insurance/policy/detailQuery.rest
	//保费追加	100013	/insurance/policy/appendPremium.rest
	//保费追加回调		
	//@define('URL_XINHUA_CHECK_POLICY',"http://uat.open.e-nci.com/api/insurance/proposal/apply.rest");
	//@define('URL_XINHUA_POST_POLICY',"http://uat.open.e-nci.com/api/insurance/policy/apply.rest");
	@define('URL_XINHUA_CHECK_POLICY',"http://uat.open.e-nci.com/api");
	@define('URL_XINHUA_POST_POLICY',"http://uat.open.e-nci.com/api");
	@define('XINHUA_SECRETKEY', "123456");
	
	@define('ATTRIBUTE_ID_COMPULSORY',43);
	@define('ATTRIBUTE_ID_COMMERCIAL',44);
//	@define('URL_SINOSIG_CAR',"http://219.143.230.175:7002/Partner/patnerDispaterAction.action"); //阳光测试环境
	@define('URL_SINOSIG_CAR',"http://1.202.156.227:7002/Partner/patnerDispaterAction.action"); //阳光测试环境
	
	//PICC寿险
	@define('URL_PICCLIFE_POST_POLICY',"http://e.picclife.com/picc/Partner/RemoteBusinessProcessController.jspx?_action=insure&partner=ebaoins");
	//http://e.picclife.com/picc/Partner/RemoteBusinessProcessController.jspx?_action=cancel&partner=ebaoins
	@define('URL_PICCLIFE_CANCEL_POLICY', "http://e.picclife.com/picc/Partner/RemoteBusinessProcessController.jspx?_action=cancel&partner=ebaoins");
	@define('PICCLIFE_SECRET_KEY', "ePb#I&aCoCins");
	
	//PICC财险
	//投保
	@define('URL_EPICC_POST_POLICY',"http://202.108.173.172:7003/EbsWeb/standard");
	//退保
	@define('URL_EPICC_CANCEL_POLICY',"http://202.108.173.172:7003/EbsWeb/standardEndorse");
	//added by zhangxi, 20150506, 下载电子保单
	@define('URL_EPICC_GET_POLICY_FILE',"http://test.mypicc.com.cn/epolicyserver/policyPDF/policyDownload");
	@define('EPICC_PLAT_FROM_CODES','AGE00013');
	@define('EPICC_PUBLIC_KEY','2e2b23e93f02f311');
	@define('EPICC_SECRET_KEY',"Picc123456Ticket");
	
	//added by zhangxi, 20150617,平安个财险接口地址
	@define('PINGAN_PROPERTY_USERNAME', "P_ZT_XH_RP");
	@define('PINGAN_PROPERTY_PASSWORD', "86HAWAx3");
	@define('PINGAN_PROPERTY_URL_GET_TOKEN', "https://test-api.pingan.com.cn:20443/oauth/oauth2/access_token?client_id=".PINGAN_PROPERTY_USERNAME."&grant_type=client_credentials&client_secret=".PINGAN_PROPERTY_PASSWORD);
	@define('PINGAN_PROPERTY_URL_POST_POLICY', "https://test-api.pingan.com.cn:20443/open/appsvr/property/riskPropertyApply?request_id=riskPropertyApply001&access_token=");
	//insurance/appsvr/common/electronicPolicyPDF
	@define('PINGAN_PROPERTY_URL_GET_POLICY_FILE', "https://test-api.pingan.com.cn:20443/open/appsvr/property/electronicPolicy?request_id=riskPropertyApply001&access_token=");
	//注销电子保单接口                                                                                                                                         
	@define('PINGAN_PROPERTY_URL_WITHDRAW', "https://test-api.pingan.com.cn:20443/open/appsvr/property/withdrawPolicy?request_id=riskPropertyApply001&access_token=");
	@define('PINGAN_PROPERTY_PREFIX', "");
	
	//add yes123 2015-04-26 吉林用户处理，吉林渠道ID
	@define('JILIN_INSTITUTION_ID',361);
	
	//added by zhangxi, 20150428, 太平洋天津货运险
	//http:// 116.228.131.200/freight/zrxservices/FreightCommonService?wsdl (电信)
	//http:// 112.64.185.137/freight/zrxservices/FreightCommonService?wsdl （联通）
	//112.64.185.136
	@define('URL_CPIC_CARGO_POST_POLICY',"http://112.64.185.136/freight/zrxservices/FreightCommonService?wsdl");
	//@define('URL_CPIC_CARGO_POST_POLICY',"http://116.228.131.200/freight/zrxservices/FreightCommonService?wsdl");
	@define('USERNAME_CPIC_CARGO',"EBTEST");
	@define('PASSWORD_CPIC_CARGO',"Cpic123456");
	@define('CHECKCODE_CPIC_CARGO', "checkcode");
	
	//added by zhangxi,20150619, 太保天津财产险接口地址，使用https
	//mod by zhangxi, 20150626, 需要修改成域名，证书才能正常使用，同时在服务器上的/etc/hosts中增加域名解析
	//@define('URL_POST_POLICY_TAIPINGYANG_TJ_PROPERTY', 'https://112.64.185.187/jttpitxhttps/itxsvc/param');//测试环境
	@define('URL_POST_POLICY_TAIPINGYANG_TJ_PROPERTY', 'https://test.cpic.com.cn/jttpitxhttps/itxsvc/param');//测试环境
	@define('USERNAME_TJ_PROPERTY','tjebxw');
	@define('PASSWORD_TJ_PROPERTY','123456');
	@define('PARTNERCODE_TJ_PROPERTY','TJEBXW');
	@define('TERMINALNO_TJ_PROPERTY','TJEBXW');//终端号
	@define('keystore_FileName_TJ_PROPERTY',$CONST_ROOT_PATH_.'baoxian/xml/config/cpic_jttp.keystore');//证书路径名
	@define('keystore_Password_TJ_PROPERTY','cpicJttp');//证书密码
	
	//天津家财
	@define('USERNAME_TJ_FAMILY_PROPERTY','A2');
	@define('PASSWORD_TJ_FAMILY_PROPERTY','ujJTh2rta8ItSm/1PYQGxq2GQZXtFEq1yHYhtsIztUi66uaVbfNG7IwX9eoQ817jy8UUeX7X3dMUVGTioLq0Ew==');
	@define('PARTNERCODE_TJ_FAMILY_PROPERTY','A2');
	@define('TERMINALNO_TJ_FAMILY_PROPERTY','1020100');//终端号
	
}
else
{//正式环境

	//ss_log("进入正式环境");
	
	@define('CALLBACK_URL', "http://139.196.24.169/baoxian/cp.php?ac=product_buy_process_ret" );//add by wangcya , 20150112, 启用异步
	@define('USE_ASYN_JAVA', true );//add by wangcya , 20150112, 启用异步
	
	@define('EBAO_JAVA_SERVICE_IP',   '123.57.254.110');
    @define('EBAO_JAVA_SERVICE_PORT', '9090');
	//@define('EBAO_JAVA_SERVICE_URL', '/ins/bizcenter/transmit/');
	@define('EBAO_JAVA_SERVICE_URL_ASYN', '/WEB_JINHUIJIA/ins/bizcenter/transmit/asyn/notify');//异步
	@define('EBAO_JAVA_SERVICE_URL_SYNC', '/WEB_JINHUIJIA/ins/bizcenter/transmit/sync/unnotify');//同步
	
	

	define('URL_PINGAN_POST_POLICY', 'https://202.69.19.43:8107');//正式环境
	define('URL_PINGAN_GET_POLICY_FILE', 'https://epcis-ptp.pingan.com.cn/epcis.ptp.partner.getAhsEPolicyPDFWithCert.do');//正式环境
	
	//我们的正式环境也先暂时连接到平安的测试环境。
	//define('URL_PINGAN_POST_POLICY', '');//
	//define('URL_PINGAN_GET_POLICY_FILE', '');//


	@define('URL_DIUBULIAO_CRETE_USER', 'http://ad.diubuliao.com/open.php?m=Interface&a=create_user');//丢不了正式环境
	
	@define('URL_POST_POLICY_TAIPINGYANG', 'http://cxb2bi.cpic.com.cn:9080/pcitx/itxsvc/param');//正式环境
	

	define('URL_HUATAI_POST_POLICY',"http://114.251.203.84:7001/ui/services/ThirdRequest");//正式环境
	
	//added by zhangxi, 20141222, 对接华安的测试环境 https://58.61.28.182:1090
	@define('URL_HUAAN_POST_POLICY',"https://58.61.28.182:1090");
	
	@define('URL_HUAAN_GET_POLICY_FILE', "https://www.sinosafe.com.cn:18080/elec/netSaleQueryElecPlyServlet");
	
	//http://open.e-nci.com/api
	@define('URL_XINHUA_CHECK_POLICY',"http://open.e-nci.com/api");
	@define('URL_XINHUA_POST_POLICY',"http://open.e-nci.com/api");
	@define('XINHUA_SECRETKEY', "DB80C445EF4F1CE160A8D4B7C081970D");
	
	@define('ATTRIBUTE_ID_COMPULSORY',64);
	@define('ATTRIBUTE_ID_COMMERCIAL',63);
	
	@define('URL_SINOSIG_CAR', "http://chexian.sinosig.com/Partner/patnerDispaterAction.action");//阳光生产环境
	
	//PICC寿险
	@define('URL_PICCLIFE_POST_POLICY',"http://www.e-picclife.com/sia4/Partner/RemoteBusinessProcessController.jspx?_action=insure&partner=ebaoins");
	//http://e.picclife.com/picc/Partner/RemoteBusinessProcessController.jspx?_action=cancel&partner=ebaoins
	@define('URL_PICCLIFE_CANCEL_POLICY', "http://www.e-picclife.com/sia4/Partner/RemoteBusinessProcessController.jspx?_action=cancel&partner=ebaoins");
	@define('PICCLIFE_SECRET_KEY', "ePb#I&aCoCins");
	
	//PICC财险
	//投保
	@define('URL_EPICC_POST_POLICY',"http://e.picclife.com/picc/Partner/RemoteBusinessProcessController.jspx?_action=insure&partner=ebaoins");
	//退保
	@define('URL_EPICC_CANCEL_POLICY',"http://202.108.173.172:7003/EbsWeb/standardEndorse");
	
	//add yes123 2015-04-26 吉林用户处理，吉林渠道ID
	@define('JILIN_INSTITUTION_ID',2572);
	
	//added by zhangxi, 20150617, 平安个财
	//added by zhangxi, 20150617,平安个财险接口地址
	@define('PINGAN_PROPERTY_USERNAME', "P_ZT_XH_RP");
	@define('PINGAN_PROPERTY_PASSWORD', "YrPi59X4");
	@define('PINGAN_PROPERTY_URL_GET_TOKEN', "https://api.pingan.com.cn/oauth/oauth2/access_token?client_id=P_ZTXH_RP&grant_type=client_credentials&client_secret=".PINGAN_PROPERTY_PASSWORD);
	@define('PINGAN_PROPERTY_URL_POST_POLICY', "https://api.pingan.com.cn/open/appsvr/property/riskPropertyApply?request_id=riskPropertyApply001&access_token=");
	//insurance/appsvr/common/electronicPolicyPDF
	@define('PINGAN_PROPERTY_URL_GET_POLICY_FILE', "https://api.pingan.com.cn/open/appsvr/property/electronicPolicy?request_id=riskPropertyApply001&access_token=");
	//注销电子保单接口                                                                                                                                         
	@define('PINGAN_PROPERTY_URL_WITHDRAW', "https://api.pingan.com.cn/open/appsvr/property/withdrawPolicy?request_id=riskPropertyApply001&access_token=");
	@define('PINGAN_PROPERTY_PREFIX', "");
	
	
	//added by zhangxi,20150619, 太保天津财产险接口地址，使用https
	//mod by zhangxi, 20150626, 需要修改成域名，证书才能正常使用，同时在服务器上的/etc/hosts中增加域名解析
	//@define('URL_POST_POLICY_TAIPINGYANG_TJ_PROPERTY', 'https://112.64.185.187/jttpitxhttps/itxsvc/param');//测试环境
	@define('URL_POST_POLICY_TAIPINGYANG_TJ_PROPERTY', 'https://jttp.cpic.com.cn:8443/jttp/itxsvc/param');//测试环境
	@define('USERNAME_TJ_PROPERTY','TJEBXW');
	@define('PASSWORD_TJ_PROPERTY','IvIvzGs4dYFhVKLPkaHHD/TQ6Rkd8jUZAKw6KXfRiEqilmePS76VfWNhzNTa+oHXQzhluY4QR2EZjmnU9WPKWQ==');
	@define('PARTNERCODE_TJ_PROPERTY','TJEBXW');
	@define('TERMINALNO_TJ_PROPERTY','TJEBXW');//终端号
	@define('keystore_FileName_TJ_PROPERTY',$CONST_ROOT_PATH_.'baoxian/xml/config/cpic_jttp.keystore');//证书路径名
	@define('keystore_Password_TJ_PROPERTY','cpicJttp');//证书密码
	
}
