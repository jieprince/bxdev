<?php
/**
 * 对于在目前web系统上咱是提供不了的数据，在这里统一处理
 * 根据客户端上传过来的command名字，通过工厂类直接生成相应的对象并作响应
 * $Author: dingchaoyang $
 * 2014-11-12 $
 */
define ( 'IN_ECS', true );
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/responseCenter.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_ . 'api/EBaoApp/eba_adapter.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');

$command = $_REQUEST['command'];
// echo 'a'.$command;
//根据command和不同的参数 调用相应的接口
if (isset($command) && !empty($command))
{
	if ($command == APP_COMMAND_POLICYRESULT){
		EbaAdapter::responsePolicyResult($_REQUEST['orderSN']);
	}
}

//没有其他参数，只有command，根据command直接生成对应的类对象
if (isset($command) && !empty($command)){
// 	echo $command;
	EbaAdapter::responseData($command);
}

?>