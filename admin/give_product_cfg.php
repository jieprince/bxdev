<?php

/**
 * ECSHOP 管理中心积分兑换商品程序文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author $
 * $Id $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
$action = $_REQUEST['act'];
/*初始化数据交换对象 */
$exc   = new exchange($ecs->table("exchange_goods"), $db, 'goods_id', 'exchange_integral');
//$image = new cls_image();

/*------------------------------------------------------ */
//-- 商品列表
/*------------------------------------------------------ */
if ($action == 'list')
{
    /* 权限判断 */
    admin_priv('give_product_cfg');

    /* 取得过滤条件 */
    $filter = array();
    $smarty->assign('ur_here',      '产品赠送配置列表');
    $smarty->assign('action_link',  array('text' => '添加 ', 'href' => 'give_product_cfg.php?act=add'));
    $smarty->assign('full_page',    1);
    $smarty->assign('filter',       $filter);

    $give_list = get_give_product_list();

    $smarty->assign('give_list',    $give_list['arr']);
    $smarty->assign('filter',        $give_list['filter']);
    $smarty->assign('record_count',  $give_list['record_count']);
    $smarty->assign('page_count',    $give_list['page_count']);
	$smarty->assign('page_count',    $give_list['page_count']);
    $sort_flag  = sort_flag($give_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    assign_query_info();
    $smarty->display('give_product_cfg_list.htm');
}

/*------------------------------------------------------ */
//-- 翻页，排序
/*------------------------------------------------------ */
elseif ($action == 'query')
{
    check_authz_json('give_product_cfg');

    $give_list = get_give_product_list();

    $smarty->assign('give_list',     $give_list['arr']);
    $smarty->assign('filter',        $give_list['filter']);
    $smarty->assign('record_count',  $give_list['record_count']);
    $smarty->assign('page_count',    $give_list['page_count']);

    $sort_flag  = sort_flag($give_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('give_product_cfg_list.htm'), '',
        array('filter' => $give_list['filter'], 'page_count' => $give_list['page_count']));
}

/*------------------------------------------------------ */
//-- 添加商品
/*------------------------------------------------------ */
if ($action == 'add')
{
    /* 权限判断 */
    admin_priv('give_product_cfg');

	//获取产品列表
	$sql = "SELECT * FROM " . $ecs->table('goods') ." WHERE is_delete = 0";
    $goods_list = $db->GetAll($sql);
	
    $smarty->assign('goods_list',    $goods_list);
    
    $smarty->assign('ur_here',     '添加配置');
    $smarty->assign('action_link', array('text' => $_LANG['15_exchange_goods_list'], 'href' => 'exchange_goods.php?act=list'));
    $smarty->assign('form_action', 'insert');

    assign_query_info();
    $smarty->display('give_prduct_info.htm');
}

/*------------------------------------------------------ */
//-- 添加商品
/*------------------------------------------------------ */
if ($action == 'insert')
{
    /* 权限判断 */
    admin_priv('give_product_cfg');
    
    $user_name = isset ($_REQUEST['user_name']) ? $_REQUEST['user_name'] : 0;
    $give_count = isset ($_REQUEST['give_count']) ? $_REQUEST['give_count'] : 0;
    $goods_id = isset ($_REQUEST['goods_id']) ? $_REQUEST['goods_id'] : 0;
    
	$sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users') ." WHERE user_name='$user_name'";
	$user_id =  $GLOBALS['db']->getOne($sql);
	if(!$user_id)
	{
		$url="give_product_cfg.php?act=add";
		$str_err = '用户名不存在，请检查！';
		$links[] = array('text' => $str_err , 'href'=>$url);
 	  	sys_msg($str_err, $links);
	}


    /*插入数据*/
    $add_time = time();
    if (empty($_POST['goods_id']))
    {
        $_POST['goods_id'] = 0;
    }
    $sql = "INSERT INTO ".$ecs->table('give_product_cfg')."(user_id, goods_id, give_count,add_time) ".
            "VALUES ('$user_id', '$goods_id', '$give_count','$add_time')";
    $db->query($sql);

    $link[0]['text'] = $_LANG['continue_add'];
    $link[0]['href'] = 'exchange_goods.php?act=add';

    $link[1]['text'] = $_LANG['back_list'];
    $link[1]['href'] = 'give_product_cfg.php?act=list';


    clear_cache_files(); // 清除相关的缓存文件

    sys_msg($_LANG['articleadd_succeed'],0, $link);
}

/*------------------------------------------------------ */
//-- 编辑
/*------------------------------------------------------ */
if ($action == 'edit')
{
    /* 权限判断 */
    admin_priv('give_product_cfg');
	
	$id = $_REQUEST['id'];
    /* 取商品数据 */
    $sql = 'SELECT gpc.*,u.user_name,u.real_name,g.goods_name FROM ' .$GLOBALS['ecs']->table('give_product_cfg'). ' AS gpc '.
       'LEFT JOIN ' .$GLOBALS['ecs']->table('users'). ' AS u ON gpc.user_id = u.user_id '.
       'LEFT JOIN ' .$GLOBALS['ecs']->table('goods'). ' AS g ON g.goods_id = gpc.goods_id '.
       'WHERE id='.$id;

    $give_product = $db->GetRow($sql);
	//获取产品列表
	$sql = "SELECT * FROM " . $ecs->table('goods') ." WHERE is_delete = 0";
    $goods_list = $db->GetAll($sql);
	
    $smarty->assign('give_product',       $give_product);
    $smarty->assign('ur_here',     '编辑');
     $smarty->assign('goods_list',    $goods_list);
   	//$smarty->assign('action_link', array('text' => $_LANG['15_exchange_goods_list'], 'href' => 'exchange_goods.php?act=list&' . list_link_postfix()));
    $smarty->assign('form_action', 'update');
    assign_query_info();
    $smarty->display('give_prduct_info.htm');
}

/*------------------------------------------------------ */
//-- 编辑
/*------------------------------------------------------ */
if ($action =='update')
{
    /* 权限判断 */
    admin_priv('give_product_cfg');
	$id = $_REQUEST['id'];
	$user_name = $_REQUEST['user_name'];
	$sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users') ." WHERE user_name='$user_name'";
	$user_id =  $GLOBALS['db']->getOne($sql);
	if(!$user_id)
	{
		$url="give_product_cfg.php?act=edit&id='.$id";
		$str_err = '用户名不存在，请检查！';
		$links[] = array('text' => $str_err , 'href'=>$url);
 	  	sys_msg($str_err, $links);
	}
	$update_time = time();
	$give_count = $_REQUEST['give_count'];
	$goods_id = $_REQUEST['goods_id'];
	$sql = "UPDATE " . $GLOBALS['ecs']->table('give_product_cfg') . " SET goods_id ='$goods_id' ,give_count='$give_count',update_time='$update_time' WHERE id='$id'";
  	$GLOBALS['db']->query($sql);
  	$url = $_SESSION['current_url'];
	Header("Location: $url");    	
}

/*------------------------------------------------------ */
//-- 编辑使用积分值
/*------------------------------------------------------ */
elseif ($action == 'edit_exchange_integral')
{
    check_authz_json('give_product_cfg');

    $id                = intval($_POST['id']);
    $exchange_integral = floatval($_POST['val']);

    /* 检查文章标题是否重复 */
    if ($exchange_integral < 0 || $exchange_integral == 0 && $_POST['val'] != "$goods_price")
    {
        make_json_error($_LANG['exchange_integral_invalid']);
    }
    else
    {
        if ($exc->edit("exchange_integral = '$exchange_integral'", $id))
        {
            clear_cache_files();
            admin_log($id, 'edit', 'exchange_goods');
            make_json_result(stripslashes($exchange_integral));
        }
        else
        {
            make_json_error($db->error());
        }
    }
}

/*------------------------------------------------------ */
//-- 切换是否兑换
/*------------------------------------------------------ */
elseif ($action == 'toggle_exchange')
{
    check_authz_json('give_product_cfg');

    $id     = intval($_POST['id']);
    $val    = intval($_POST['val']);

    $exc->edit("is_exchange = '$val'", $id);
    clear_cache_files();

    make_json_result($val);
}

/*------------------------------------------------------ */
//-- 切换是否兑换
/*------------------------------------------------------ */
elseif ($action == 'toggle_hot')
{
    check_authz_json('give_product_cfg');

    $id     = intval($_POST['id']);
    $val    = intval($_POST['val']);

    $exc->edit("is_hot = '$val'", $id);
    clear_cache_files();

    make_json_result($val);
}

/*------------------------------------------------------ */
//-- 批量删除商品
/*------------------------------------------------------ */
elseif ($action == 'batch_remove')
{
    admin_priv('give_product_cfg');

    if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes']))
    {
        sys_msg($_LANG['no_select_goods'], 1);
    }

    $count = 0;
    foreach ($_POST['checkboxes'] AS $key => $id)
    {
        if ($exc->drop($id))
        {
            admin_log($id,'remove','exchange_goods');
            $count++;
        }
    }

    $lnk[] = array('text' => $_LANG['back_list'], 'href' => 'exchange_goods.php?act=list');
    sys_msg(sprintf($_LANG['batch_remove_succeed'], $count), 0, $lnk);
}

/*------------------------------------------------------ */
//-- 删除商品
/*------------------------------------------------------ */
elseif ($action == 'remove')
{
    check_authz_json('give_product_cfg');

    $id = intval($_GET['id']);
    if ($exc->drop($id))
    {
        admin_log($id,'remove','article');
        clear_cache_files();
    }

    $url = 'exchange_goods.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/*------------------------------------------------------ */
//-- 搜索商品
/*------------------------------------------------------ */

elseif ($action == 'search_goods')
{
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;

    $filters = $json->decode($_GET['JSON']);

    $arr = get_goods_list($filters);

    make_json_result($arr);
}

/* 获得商品列表 */
function get_give_product_list()
{
    $result = get_filter();
    if ($result === false)
    {
        $filter = array();
        $filter['goods_name']    = empty($_REQUEST['goods_name']) ? '' : trim($_REQUEST['goods_name']);
        $filter['user_name']    = empty($_REQUEST['user_name']) ? '' : trim($_REQUEST['user_name']);

        $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'g.goods_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $where = '';
        if ($filter['goods_name'])
        {
            $where = " AND g.goods_name LIKE '%" . $filter['goods_name']. "%'";
        }
        
        if ($filter['user_name'])
        {
            $where = " AND u.user_name LIKE '%" . $filter['user_name']. "%'";
        }
        

        /* 总数 */
        $sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('give_product_cfg'). ' AS gpc '.
               'LEFT JOIN ' .$GLOBALS['ecs']->table('users'). ' AS u ON gpc.user_id = u.user_id '.
               'LEFT JOIN ' .$GLOBALS['ecs']->table('goods'). ' AS g ON g.goods_id = gpc.goods_id '.
               'WHERE 1 ' .$where;
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        $filter = page_and_size($filter);

        /* 获取数据 */
        $sql = 'SELECT gpc.*,u.user_name,u.real_name,g.goods_name FROM ' .$GLOBALS['ecs']->table('give_product_cfg'). ' AS gpc '.
               'LEFT JOIN ' .$GLOBALS['ecs']->table('users'). ' AS u ON gpc.user_id = u.user_id '.
               'LEFT JOIN ' .$GLOBALS['ecs']->table('goods'). ' AS g ON g.goods_id = gpc.goods_id '.
               'WHERE 1 ' .$where. ' ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];
        $filter['keyword'] = stripslashes($filter['keyword']);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    $arr = array();
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
    	//获取剩余数量
    	//$surplus_count = get_policy_count($rows['user_id'],$rows['goods_id']);
    	//$rows['surplus_count'] =$rows['give_count']-$surplus_count;
        $arr[] = $rows;
    }
    
    	
	$current_url="give_product_cfg.php?act=list";
	foreach ($filter AS $key => $value)
	{
		$current_url.="&".$key."=".$value;
			
	}
    
    $_SESSION['current_url'] = $current_url;
    
    return array('arr' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count'],'current_url'=>$current_url);
}

function get_policy_count($user_id,$goods_id){
	$num=0;
	$sql = "SELECT tid FROM " . $GLOBALS['ecs']->table('goods') ." WHERE goods_id='$goods_id'";
	$tid =  $GLOBALS['db']->getOne($sql);
	 if($tid){
	 	$sql = "SELECT count(policy_id) FROM t_insurance_policy WHERE client_id!=0 AND agent_uid='$user_id'  AND attribute_id='$tid' ";
		$num =  $GLOBALS['db']->getOne($sql);
	 }
	return $num;
}
?>