<?php

/**
 * 
 * 和短信相关
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH . 'baoxian/source/function_debug.php');

//start modify yes123 2014-12-18 


if ($_REQUEST['act'] == 'send_history_list')
{
	
	admin_priv('send_history');
	//查询当前剩余短信
	require_once(ROOT_PATH . '/includes/GetBalance.php');
	$res = get_balance();
	if($res)
	{
		if($res['code']==0)
		{
			$smarty->assign('sms_count',  "当前短信剩余条数为:".$res['num']. "  <a href=sms.php?act=display_send_history_ui>【重新获取】</a>");
		}
	}
	
    $smarty->assign('ur_here',     '短信发送日志');
    $smarty->assign('full_page',   1);

    $res = sms_log();

	$send_type_list = array(MANUALLY_SEND_SMS,
							SERVICE_CHARGE_SEND_SMS,
							VERIFICATION_CODE_SMS,
							BONUS_SEND_SMS,
							CHECK_SMS);
	
    $smarty->assign('send_type_list',    $send_type_list);
    $smarty->assign('log_list',    $res['log_list']);
    
    
    $smarty->assign('log_list',    $res['log_list']);
    $smarty->assign('filter',       $res['filter']);
    $smarty->assign('record_count', $res['record_count']);
    $smarty->assign('page_count',   $res['page_count']);

    assign_query_info();
    $smarty->display('sms_send_log.htm');
}

/*------------------------------------------------------ */
//-- 翻页、排序
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'query')
{
	$res = sms_log();
	$smarty->assign('log_list', $res['log_list']);
	$smarty->assign('filter', $res['filter']);
	$smarty->assign('record_count', $res['record_count']);
	$smarty->assign('page_count', $res['page_count']);

	$sort_flag = sort_flag($res['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);

	make_json_result($smarty->fetch('sms_send_log.htm'), '', array (
		'filter' => $res['filter'],
		'page_count' => $res['page_count']
	));
}

if ($_REQUEST['act'] == 'remove_log')
{
	$is_remove=1;
	$res = sms_log($is_remove);
	die(json_encode($res));
	
}
//显示发送的页面
elseif ($_REQUEST['act'] == 'display_send_ui')
{
	
	$smarty->display('sms_send.htm');

}
//发送
elseif ($_REQUEST['act'] == 'send')
{
	$content = isset ($_REQUEST['content']) ? trim($_REQUEST['content']) : "";
	$mobile_phone="";
	if(!$content){
		die(json_encode(array (
					'code' => '11',
					'msg' => "请输入发送内容！"
		)));
	}
	
	$mobile_phone = trim($_REQUEST['mobile_phone']);
	if(!$mobile_phone){
		die(json_encode(array (
					'code' => '11',
					'msg' => "请输入手机号码!"
					
		)));
	}
		

	$result = send_msg($mobile_phone,$content,80,MANUALLY_SEND_SMS);
	die(json_encode(array (
				'code' => $result,
				'msg'=>''
	)));

}
//end modify yes123 2014-12-18 


function sms_log($is_remove=0)
{
	$where = " WHERE 1 ";
	    /* 查询条件 */
    $filter['sort_by'] = empty($_REQUEST['sort_order']) ? 'id' : trim($_REQUEST['sort_by']);
    $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
    $filter['mobile_phone'] = empty($_REQUEST['mobile_phone']) ? 0 : $_REQUEST['mobile_phone'];
    $filter['content'] = empty($_REQUEST['content']) ? '' : $_REQUEST['content'];
    $filter['start_time'] = empty($_REQUEST['start_time']) ? 0 : $_REQUEST['start_time'];
    $filter['end_time'] = empty($_REQUEST['end_time']) ? 0 : $_REQUEST['end_time'];
    
    $filter['send_type'] = empty($_REQUEST['send_type']) ? '' : $_REQUEST['send_type'];
    $filter['send_result'] = empty($_REQUEST['send_result']) ? 0 : $_REQUEST['send_result'];
	
	if($_SESSION['admin_name']!='admin')
	{
		/*$filter['send_type']=REG_VERIFICATION_CODE_SMS;
		$where.=" AND (send_type='$filter[send_type]' OR admin_id=$_SESSION[admin_id]) ";*/
		$where.=" AND  admin_id=$_SESSION[admin_id] ";
		
	}

	
	
	

	if($filter['start_time']){
    		$where.=" AND send_time >='".local_strtotime($filter['start_time'])."'";
    }
    
    if($filter['end_time']){
    		$where.=" AND send_time <='".local_strtotime($filter['end_time'])."'";
    }
    if($filter['mobile_phone']){
    		$where.=" AND mobile_phone LIKE '%$filter[mobile_phone]%'";
    }
    if($filter['content']){
    		$where.=" AND send_content LIKE '%$filter[content]%'";
    }
    if($filter['admin_id']){
    		$where.=" AND admin_id='$filter[admin_id]'";
    }
    if($filter['send_result']){
    	if($filter['send_result']=='success')
    	{
    		$where.=" AND send_result=0 ";
    	}
    	else
    	{
    		$where.=" AND send_result!=0 ";
    	}
    	
    }
	
	
	
	
	
	
	if($is_remove==1)
	{
		admin_priv('del_send_history');
		//删除日志
		$sql = "DELETE FROM ".$GLOBALS['ecs']->table('sms_send_log'). " $where " ;
		ss_log("删除发送日志:".$sql);
		if($GLOBALS['db']->query($sql))
		{
			return array('code'=>0,'msg'=>'删除成功');
		}
	}
	
	
	
    $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('sms_send_log'). $where;
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    /* 分页大小 */
    $filter = page_and_size($filter);

    $sql = "SELECT * ".
          " FROM ".$GLOBALS['ecs']->table('sms_send_log'). " $where ".
          " ORDER BY ".$filter['sort_by']." ".$filter['sort_order'].
          " LIMIT ". $filter['start'] .", $filter[page_size]";
    
    
    ss_log("查询日志列表：".$sql);
    $log_list = $GLOBALS['db']->getAll($sql);
	
	foreach ( $log_list as $key => $value )
	{
        $log_list[$key]['send_time'] = date('Y-m-d H:i', $value['send_time']); 
        
        if($value['admin_id'])
        {
        	$sql = "SELECT user_name FROM ".$GLOBALS['ecs']->table('admin_user'). " WHERE user_id=$value[admin_id]";
    		$admin_name = $GLOBALS['db']->getOne($sql);
    		$log_list[$key]['admin_name'] = $admin_name;
        	
        }
        
	}

    $arr = array('log_list' => $log_list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
	
}

?>