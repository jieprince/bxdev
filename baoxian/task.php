<?php
//start add by wangcya, 20150114 for bug[115], 后台一定有一个定时任务，对那些已经付款的订单对应的没成功投保的保单进行投保。

/*
service crond restart
/usr/bin/php /var/bxdev/baoxian/task.php ddc093kjlcidp112
/var/spool/cron/root

每五分钟执行  *\/5 * * * *

每小时执行     0 * * * *

每天执行        0 0 * * *

每周执行       0 0 * * 0

每月执行        0 0 1 * *

每年执行       0 0 1 1 *
 
*/


//---------初始化变量和函数开始------------------
ini_set('display_errors', 0);
@define('IN_UCHOME', TRUE);
define('S_ROOT',  dirname(__FILE__).  DIRECTORY_SEPARATOR );
define('CONNECT_ROOT', dirname(__FILE__) );
//////////////////////////////////////////////////////////////////////
include_once(S_ROOT.'./source/function_debug.php');

//ss_log_task("into ".__FILE__);


//echo "test task!";
//exit(0);
//----------校验是否为控制台调用开始--------------
$checkCode = $argv[1];
if(!isset($checkCode))
{
	echo "no checkCode";
	exit();
}
if($checkCode != 'ddc093kjlcidp112')
{
	echo "checkCode in not right";
	exit();
}
//---------校验是否为控制台调用结束--------------


////////////////////////////////////////////////////////////////


include_once(S_ROOT  . 'config.php');
$_SGLOBAL = $_SCONFIG = $_SBLOCK = $_TPL = $_SCOOKIE = $_SN = $space = array();

include_once(S_ROOT.'./source/my_const.php');//add by wangcya,20121121
include_once(S_ROOT.'./source/function_common.php');
include_once(S_ROOT.'./source/function_baoxian.php');
include_once(S_ROOT.'./source/function_task.php');

$mtime = explode(' ', microtime());
$_SGLOBAL['timestamp'] = $mtime[1];
$_SGLOBAL['supe_starttime'] = $_SGLOBAL['timestamp'] + $mtime[0];
dbconnect();
//---------初始化变量和函数结束-------------------
ss_log_task("将要执行计划任务");

$task = new TaskActive;
if($task)
{
	ss_log_task("将要执行 task_post_policy------------");
	$task->task_post_policy();//定期投保
	
	ss_log_task("将要执行 task_get_policy_file------------");
	$task->task_get_policy_file();//定期获取电子保单
	/////////////////////////////////////////////////////////////////////////////
	
	ss_log_task("将要执行 check_policy_not_payed------------");
	$task->check_policy_not_payed();//检查异常订单
	/////////////////////////////////////////////////////////////////////////////
	
	//发送到救援公司
	$task->check_policy_taipingyang_jiuyuan();
}

ss_log_task("执行计划任务结束");
