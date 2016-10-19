<?php
define('IN_ECS', true);

require(str_replace('\\','/',dirname(__FILE__)) . '/includes/init.php');

$act = isset($_REQUEST['act'])?trim($_REQUEST['act']):'';

/*企业站的咨询预约*/
if($act=='reservation')
{
	$data['real_name'] = isset($_REQUEST['real_name'])?trim($_REQUEST['real_name']):'';
	$data['sex'] = isset($_REQUEST['sex'])?trim($_REQUEST['sex']):1;
	$data['tel'] = isset($_REQUEST['tel'])?trim($_REQUEST['tel']):'';
	$data['user_email'] = isset($_REQUEST['user_email'])?trim($_REQUEST['user_email']):'';
	$data['address'] = isset($_REQUEST['address'])?trim($_REQUEST['address']):'';
	$data['qq'] = isset($_REQUEST['qq'])?trim($_REQUEST['qq']):'';
	$data['msg_content'] = isset($_REQUEST['msg_content'])?trim($_REQUEST['msg_content']):'';
	$data['msg_time'] = time();
	$data['msg_type'] = M_RESERVATION;
	
	
	$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('feedback'), $data, "INSERT");
	
	$content="提交成功！";
	$msg['content'] = $content;
    $smarty->assign('message', $msg);
    
    $smarty->assign('module_title','提交咨询');
    $smarty->display('message.dwt');
    exit;
/*	if($r)
	{
		
	}*/

}
?>
