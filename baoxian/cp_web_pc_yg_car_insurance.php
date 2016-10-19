<?php
//cp_web_pc_yg_car_insurance.php
//http://127.0.0.1:84/baoxian/cp.php?ac=web_pc_yg_car_insurance

/*http://219.143.230.175:7002/Partner/patnerDispaterAction.action?citySelected=110100&cooperator=W03210001&tbsn=TR3268677163736,BU3268678163736
 * 
 * http://jinrong.test.58v5.cn/car/third/yg_car_insurance?sign=102150CF73C26A3DEECF6936EAD2A49F&signType=MD5&sellerId=1&tbsn=TR3268677163736,BU3268678163736
 * */

//通知：外网测试环境地址还是用：219.143.230.175，1.202.156.227有点小问题

include_once('./common.php');
include_once(S_ROOT.'../includes/class/commonUtils.class.php');
include_once(S_ROOT.'../includes/class/XMLToArray.class.php');
//echo "fsdfsdfsd";

/////////////////////////////////////////////////////////////////
/*$sign = $_REQUEST['sign'];
$signType = $_REQUEST['signType'];
$sellerId = $_REQUEST['sellerId'];
$tbsn = $_REQUEST['tbsn'];//tbsn=TR3268677163736,BU3268678163736

$xml = $_REQUEST['data'];
*/

$sign = trim($_GET['sign']);
$signType = trim($_GET['signType']);
$sellerId = trim($_GET['sellerId']);
$tbsn = trim($_GET['tbsn']);//tbsn=TR3268677163736,BU3268678163736

ss_log("        ");
ss_log("----------------------开始接收到阳关的请求");
ss_log("sign=".$sign);
ss_log("signType=".$signType);
ss_log("sellerId=".$sellerId);
ss_log("tbsn=".$tbsn);

$var = print_r($_REQUEST , true);
ss_log("_REQUEST--------------".$var);

//ss_log("post--------------".$_POST);

//$postdata = implode($_POST);
//ss_log("post implode--------------".$postdata);

//$postdata1 = var_export($_POST,TRUE);
//ss_log("var_export--------------".$postdata1);
//http_get_request_body();
$finishTime = date("Y-m-d H:i",time());

$xml = $data = trim($_REQUEST['data']);
//ss_log($data);
if(empty($data))
{
	
	ss_log("not receive car post data!!!!");
	
	$body = @file_get_contents('php://input');

	if($body)
	{
		ss_log("find body: ");
		$data = $body;
	}
	else
	{
		
		$ret_xml=
		"<?xml version=\"1.0\" encoding=\"GBK\"?>
		<response finishTime=\"$finishTime\">
		<isSuccess>F</isSuccess>
		<outOrderId></outOrderId>
		<errorCode></errorCode>
		<errorReason>data is null</errorReason>
		</response>";
		
		echo $ret_xml;
		
		exit(0);
	}
}

/////////////////////////////////////////////////////////////////
ss_log("接收到阳光车险的信息");
//$data = urldecode(trim($data));

//////////////////////////////////////////////////////////////////
ss_log("编码转换之前保存");

$xml_path_body1 = S_ROOT."log/sun_".$tbsn."_1"."_data.xml";
ss_log("xml_path_body: ".$xml_path_body1);
file_put_contents($xml_path_body1,$data);

/*
if(0)
{
	$data =  mb_convert_encoding($data, "UTF-8","GBK");//从GBK转换为utf-8
	
	ss_log("编码转换之后保存");
	$xml_path_body2 = S_ROOT."log/sun_".$tbsn."_2"."_data.xml";
	ss_log("xml_path_body: ".$xml_path_body2);
	file_put_contents($xml_path_body2,$data);
}
*/

//////////////////////////////////////////////////////////////////
//ss_log("data: ".$data);
//ss_log("data: ".$data);
//////////////////////////////////////////////////////////////////
ss_log("准备进行签名的验证");
///首先校验数据完整性

$sigcode = "W02410792";

/*
$sig_local = md5($sigcode.$data.$sellerId.$tbsn);
ss_log("要进行MD5加密的字符串：".$sigcode.$data.$sellerId.$tbsn);
ss_log("sig_local:".$sig_local);
ss_log("sign:".$sign);
*/
ss_log("sign:".$sign);

$data2 =  mb_convert_encoding($data, "UTF-8","GBK");//从GBK转换为utf-8
$sig_local2 = md5($sigcode.$data2.$sellerId.$tbsn);
ss_log("before case , sig_local2: ".$sig_local2);
$sig_local2 = trim(strtoupper($sig_local2));//转换为大写就可
ss_log("after case , sig_local2: ".$sig_local2);

//if(0)
if($sig_local2 != $sign)
{
	ss_log("签名验证失败！");
	
	$ret_xml=
	"<?xml version=\"1.0\" encoding=\"GBK\"?>
	<response finishTime=\"$finishTime\">
	<isSuccess>F</isSuccess>
	<outOrderId></outOrderId>
	<errorCode></errorCode>
	<errorReason>sig fail!</errorReason>
	</response>";
	
	echo $ret_xml;
	
	exit(0);
}

////////////////////////////////////////////////////////////////
ss_log("解析xml前");
$array_result = CommonUtils::xml_to_array($data);
ss_log("解析xml后");
$ret_type = $array_result['request']['@attributes']['func'];//请求类型
$ret_return = $array_result['request']['@attributes']['return'];

if($ret_return)
{
	ss_log("阳光的范围结果正确，下面可继续处理");	
}
else
{
	ss_log("阳光的返回结果为失败，下面不需要继续处理，so return");
	
	$ret_xml=
	"<?xml version=\"1.0\" encoding=\"GBK\"?>
	<response finishTime=\"$finishTime\">
	<isSuccess>F</isSuccess>
	<outOrderId></outOrderId>
	<errorCode></errorCode>
	<errorReason></errorReason>
	</response>";
	
	echo $ret_xml;
	
	exit(0);
	
}

///////////////////////////////////////////////////////////////////////
$xml_path_body = S_ROOT."log/sun_".$tbsn."_".$ret_type."_data.xml";
ss_log("xml_path_body: ".$xml_path_body);
file_put_contents($xml_path_body,$data);
///////////////////////////////////////////////////////////////////////

$obj = $array_result['request']['content'];

ss_log("ret_type: ".$ret_type);

$var = print_r($obj , true);
ss_log("obj--------------".$var);

///////////////////////////////////////////////////////////////////
//exit;
//ss_log("before new Sun_Car");
//ss_log(S_ROOT.'/source/function_sun_chexian.php');
include_once(S_ROOT.'/source/function_sun_chexian.php');

ss_log("33355 begin cp_web_pc_yg_car_insurance");

///////////////////////////////////////////////////////////////////

$obj_sun_car = new Sun_Car();

if($ret_type == "car_proposal_sync")//核保同步接口
{
	ss_log("into 核保同步接口");
	$ret = $obj_sun_car->sun_process_car_proposal_sync($obj);
}
elseif($ret_type == "car_paid_sync")//支付同步接口
{
	ss_log("into 支付同步接口");
	$ret = $obj_sun_car->sun_process_car_paid_sync($obj);
}
elseif($ret_type == "car_policy_sync")//出单同步接口
{
	ss_log("into 出单同步接口");
	$ret = $obj_sun_car->sun_process_car_policy_sync($obj);
}

/////////////////////////////////////////////////////////

ss_log("before echo xml ret，ret_type: ".$ret_type);
if($ret)
{
	$ret_xml=
			"<?xml version=\"1.0\" encoding=\"GBK\"?>
			<response finishTime=\"$finishTime\">
			<isSuccess>T</isSuccess>
			<outOrderId>345345345345345345345</outOrderId>
			<errorCode></errorCode>
			<errorReason></errorReason>
			</response>";
}
else
{
	$ret_xml=
			"<?xml version=\"1.0\" encoding=\"GBK\"?>
			<response finishTime=\"$finishTime\">
			<isSuccess>F</isSuccess>
			<outOrderId>345345345345345345345</outOrderId>
			<errorCode></errorCode>
			<errorReason>失败</errorReason>
			</response>";	
}

echo $ret_xml;

exit(0);
