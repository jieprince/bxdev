<?php
define('IN_ECS', true);

require (dirname(__FILE__) . '/includes/init.php');
require_once (ROOT_PATH . 'includes/lib_main.php');
require_once (ROOT_PATH . 'includes/lib_policy.php');
require_once (ROOT_PATH . 'includes/lib_order.php');

require_once (ROOT_PATH . '/baoxian/common.php');
require_once (ROOT_PATH . '/baoxian/source/function_baoxian.php');
require_once (ROOT_PATH . '/baoxian/source/function_debug.php');//add by wangcya, 20141010
///////////////////////////////////////////////////////////////////////////
$policy_id = isset ($_GET['policy_id']) ? intval($_GET['policy_id']) : 0;



//////////////////////////////////////////////////////////////////////////////////////

$action = $_REQUEST['act'];

//echo "action: ".$action;

if ($_REQUEST['act'] == 'list') 
{
	admin_priv('app_update_manage');
    $res = app_update_list();
	$smarty->assign('ur_here', "app升级记录");
	$smarty->assign('action_link',  array('text' =>'添加升级日志', 'href'=>'app.php?act=add_edit_view&type=add'));
	$smarty->assign('full_page', 1);
	$smarty->assign('data', $res['app_update_list']);
	$smarty->assign('filter', $res['filter']);
	$smarty->assign('record_count', $res['record_count']);
	$smarty->assign('page_count', $res['page_count']);
	
	$smarty->display('app_update_list.htm');

}
elseif ($_REQUEST['act'] == 'query')
{
    $res = app_update_list();
    $smarty->assign('data', $res['app_update_list']);
    $smarty->assign('filter',       $res['filter']);
    $smarty->assign('record_count', $res['record_count']);
    $smarty->assign('page_count',   $res['page_count']);

    $sort_flag  = sort_flag($res['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);
    
    if($_REQUEST['type'])
    {
    	make_json_result($smarty->fetch($_REQUEST['type'].'.htm'), '', array('filter' => $res['filter'], 'page_count' => $res['page_count']));
    	exit;
    }

    make_json_result($smarty->fetch('app_update_list.htm'), '', array('filter' => $res['filter'], 'page_count' => $res['page_count']));
}
elseif ($_REQUEST['act'] == 'add_edit_view')
{
	$type=isset($_REQUEST['type'])?$_REQUEST['type']:"";
	if($type=='edit')
	{
		$sql = "SELECT * FROM " .$GLOBALS['ecs']->table('app_online_update')." WHERE ID='$_REQUEST[id]'";
		$row = $GLOBALS['db']->getRow($sql);
		$row['releaseTime']=date("Y-m-d H:i", $row['releaseTime']) ; 
		$smarty->assign('data', $row);
		$smarty->assign('type', 'edit');
		$smarty->assign('ur_here',      "<a href='app.php?act=list'>升级管理</a>-编辑会员");
	}else{
		$smarty->assign('ur_here',      "<a href='app.php?act=list'>升级管理</a>-添加会员");
		$smarty->assign('type', 'add');
	}

	$smarty->display('app_update_info.htm');	
}
elseif ($_REQUEST['act'] == 'insert_or_update')
{
	$fileName = isset($_REQUEST['fileName'])?$_REQUEST['fileName']:"";
	$version = isset($_REQUEST['version'])?$_REQUEST['version']:"";
	$size = isset($_REQUEST['size'])?$_REQUEST['size']:"";
	$platformId = isset($_REQUEST['platformId'])?$_REQUEST['platformId']:""; //平台
	//ss_log("releaseTime:".$_REQUEST['releaseTime'].",strtotime:".strtotime($_REQUEST['releaseTime']));
	$releaseTime = isset($_REQUEST['releaseTime'])?$_REQUEST['releaseTime']:""; //发布时间
	$releaseFlag = isset($_REQUEST['releaseFlag'])?$_REQUEST['releaseFlag']:0; //是否需要检查
	$content = isset($_REQUEST['content'])?$_REQUEST['content']:""; //是否需要检查
	
	$type=isset($_REQUEST['type'])?$_REQUEST['type']:"";
	if($type=='add')
	{
		$msg="添加成功！";
		$sql = "INSERT INTO ".$ecs->table('app_online_update')." (fileName,version,size,platformId,releaseTime,releaseFlag,content) ".
	       " VALUES ('$fileName', '$version', '$size', '$platformId', '$releaseTime','$releaseFlag','$content')";
	}else if($type=='edit'){
		$sql ="UPDATE ".$ecs->table('app_online_update')." SET fileName='$fileName',version='$version',size='$size',
		platformId='$platformId',releaseTime='$releaseTime',releaseFlag='$releaseFlag',content='$content' WHERE id='$_REQUEST[id]'";
		$msg="更新成功！";
	}
	ss_log("insert_or_update:".$sql);
	$r = $GLOBALS['db']->query($sql);
	
	if($r)
	{
		die(json_encode(array (
			'code' => 0,
			'msg' => $msg
		)));
	}
	else
	{
		die(json_encode(array (
			'code' => 1,
			'msg' => '添加失败！'
		)));	
	}
	
}
elseif ($_REQUEST['act'] == 'remove')
{
	$sql = "DELETE FROM ".$ecs->table('app_online_update')." WHERE ID='$_REQUEST[id]'";
	if($GLOBALS['db']->query($sql))
	{
		$msg= '删除成功！';
		die(json_encode(array (
			'code' => 0,
			'msg' =>$msg
		)));	
		
	}else{
		$msg= '删除失败！';
		die(json_encode(array (
			'code' => 1,
			'msg' =>$msg
		)));
	}	
}



function app_update_list()
{
	$result = get_filter();
    if ($result === false)
    {
        /* 过滤条件 */
        
        $filter['fileName'] = isset ($_REQUEST['fileName']) ? trim($_REQUEST['fileName']) : "";
		$filter['platformId'] = isset ($_REQUEST['platformId']) ? trim($_REQUEST['platformId']) : ""; //平台
		$filter['version'] = isset ($_REQUEST['version']) ? trim($_REQUEST['version']) : ""; //版本号
		$filter['releaseFlag'] = isset ($_REQUEST['releaseFlag']) ? trim($_REQUEST['releaseFlag']) : ""; //版本号
        
        $where_sql = " WHERE 1 ";
		if($filter['fileName']){
				
			$where_sql.=" AND fileName LIKE '%$filter[fileName]%'";
		}
		
		if($filter['platformId']){
				
			$where_sql.=" AND platformId='$filter[platformId]'";
		}
		
		if($filter['version']){
				
			$where_sql.=" AND version LIKE '%$filter[version]%'";
		}
		
		if($filter['releaseFlag']){
				
			$where_sql.=" AND releaseFlag = '$filter[releaseFlag]'";
		}
		$record_count_sql = "SELECT count(ID) FROM " .$GLOBALS['ecs']->table('app_online_update');
		$filter['record_count'] = $GLOBALS['db']->getOne($record_count_sql.$where_sql);

        /* 分页大小 */
        $filter = page_and_size($filter);
		/***
		 * 修改时间: 2014/7/15
		 * 修改人: 鲍洪州
		 * 作用: SQL语句添加了查询is_cheack字段
		 */
        $sql  = "SELECT * FROM bx_app_online_update 
			$where_sql  LIMIT $filter[start] , $filter[page_size]";

        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

    $app_update_list = $GLOBALS['db']->getAll($sql);
    $arr = array('app_update_list' => $app_update_list, 'filter' => $filter,
        'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    return $arr;
	
}