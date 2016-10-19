<?php

/**
 * ECSHOP 友情链接管理
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: userbg_img.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH . 'includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);

$exc = new exchange($ecs->table('userbg_img'), $db, 'id', 'img_name');

/* act操作项的初始化 */
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}
else
{
    $_REQUEST['act'] = trim($_REQUEST['act']);
}

/*------------------------------------------------------ */
//-- 友情链接列表页面
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    /* 模板赋值 */
    $smarty->assign('ur_here',     $_LANG['list_link']);
    $smarty->assign('action_link', array('text' => $_LANG['add_link'], 'href' => 'userbg_img.php?act=add'));
     $smarty->assign('full_page',   1);

    /* 获取友情链接数据 */
    $ubg_list = get_userbg_list();
    $smarty->assign('ubg_list',      $ubg_list['list']);
    $smarty->assign('filter',          $ubg_list['filter']);
    $smarty->assign('record_count',    $ubg_list['record_count']);
    $smarty->assign('page_count',      $ubg_list['page_count']);

    $sort_flag  = sort_flag($ubg_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    assign_query_info();
    $smarty->display('userbg_list.htm');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    /* 获取友情链接数据 */
    $ubg_list = get_userbg_list();

    $smarty->assign('ubg_list',      $ubg_list['list']);
    $smarty->assign('filter',          $ubg_list['filter']);
    $smarty->assign('record_count',    $ubg_list['record_count']);
    $smarty->assign('page_count',      $ubg_list['page_count']);

    $sort_flag  = sort_flag($ubg_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('userbg_list.htm'), '',
        array('filter' => $ubg_list['filter'], 'page_count' => $ubg_list['page_count']));
}

/*------------------------------------------------------ */
//-- 添加新链接页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    admin_priv('user_bg_img');

    $smarty->assign('ur_here',     $_LANG['add_link']);
    $smarty->assign('action_link', array('href'=>'userbg_img.php?act=list', 'text' => $_LANG['list_link']));
    $smarty->assign('action',      'add');
    $smarty->assign('form_act',    'insert');

    assign_query_info();
    $smarty->display('userbg_info.htm');
}

/*------------------------------------------------------ */
//-- 处理添加的链接
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'insert')
{
    /* 变量初始化 */
    $img_url = '';
    $show_order = (!empty($_POST['show_order'])) ? intval($_POST['show_order']) : 0;
    $is_default = (!empty($_POST['is_default'])) ? intval($_POST['is_default']) : 0;
    $name  = (!empty($_POST['name']))  ? sub_str(trim($_POST['name']), 250, false) : '';

    /* 查看链接名称是否有重复 */
    if ($exc->num("name", $name) == 0)
    {
        /* 处理上传的LOGO图片 */
        if ((isset($_FILES['img_url']['error']) && $_FILES['img_url']['error'] == 0) || (!isset($_FILES['img_img']['error']) && isset($_FILES['img_img']['tmp_name']) && $_FILES['img_img']['tmp_name'] != 'none'))
        {
        	$image->data_dir = 'images';
        	$userbg_img = "userbg_img";
            $img_up_info = @basename($image->upload_image($_FILES['img_url'], $userbg_img));
            
            $img_url = $image->data_dir."/".$userbg_img."/".$img_up_info;
        }

		
		
		if($is_default)
		{
		    $sql = "UPDATE " .$ecs->table('userbg_img'). " SET is_default=0 ";
		    $db->query($sql);
		}
		
        /* 插入数据 */
        $sql    = "INSERT INTO ".$ecs->table('userbg_img')." (name, img_url, show_order,is_default) ".
                  "VALUES ('$name', '$img_url', '$show_order','$is_default')";
        $db->query($sql);

        /* 记录管理员操作 */
        admin_log($_POST['name'], 'add', 'user_bg_img');

        /* 清除缓存 */
        clear_cache_files();

        /* 提示信息 */
        $link[0]['text'] = $_LANG['continue_add'];
        $link[0]['href'] = 'userbg_img.php?act=add';

        $link[1]['text'] = $_LANG['back_list'];
        $link[1]['href'] = 'userbg_img.php?act=list';

        sys_msg($_LANG['add'] . "&nbsp;" .stripcslashes($_POST['name']) . " " . $_LANG['attradd_succed'],0, $link);

    }
    else
    {
        $link[] = array('text' => $_LANG['go_back'], 'href'=>'javascript:history.back(-1)');
        sys_msg($_LANG['img_name_exist'], 0, $link);
    }
}

/*------------------------------------------------------ */
//-- 友情链接编辑页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit')
{
    admin_priv('user_bg_img');

    /* 取得友情链接数据 */
    $sql = "SELECT * ".
           "FROM " .$ecs->table('userbg_img'). " WHERE id = '".intval($_REQUEST['id'])."'";
    $img_arr = $db->getRow($sql);

    $img_arr['name'] = sub_str($img_arr['name'], 250, false); // 截取字符串为250个字符避免出现非法字符的情况
																			  
    /* 模板赋值 */
    $smarty->assign('ur_here',     $_LANG['edit_link']);
    $smarty->assign('action_link', array('href'=>'userbg_img.php?act=list&' . list_link_postfix(), 'text' => $_LANG['list_link']));
    $smarty->assign('form_act',    'update');
    $smarty->assign('action',      'edit');

    $smarty->assign('img_arr',    $img_arr);

    assign_query_info();
    $smarty->display('userbg_info.htm');
}

/*------------------------------------------------------ */
//-- 编辑链接的处理页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'update')
{
    /* 变量初始化 */
    $id         = (!empty($_REQUEST['id']))      ? intval($_REQUEST['id'])      : 0;
    $show_order = (!empty($_POST['show_order'])) ? intval($_POST['show_order']) : 0;
    $name  = (!empty($_POST['name']))  ? trim($_POST['name'])    : '';
    $is_default = (!empty($_POST['is_default'])) ? intval($_POST['is_default']) : 0;

    /* 如果有图片LOGO要上传 */
    if ((isset($_FILES['img_url']['error']) && $_FILES['img_url']['error'] == 0) || (!isset($_FILES['img_url']['error']) && isset($_FILES['img_url']['tmp_name']) && $_FILES['img_url']['tmp_name'] != 'none'))
    {
    	$image->data_dir = 'images';
    	$userbg_img = "userbg_img";
        $img_up_info = @basename($image->upload_image($_FILES['img_url'], $userbg_img));
        $img_url = $image->data_dir."/".$userbg_img."/".$img_up_info;
    }
    elseif (!empty($_POST['old_img_url']))
    {
        $img_url =$_POST['old_img_url'];
    }
    

    //如果要修改链接图片, 删除原来的图片
    if (!empty($img_up_info))
    {
        //获取链子LOGO,并删除
        $old_logo = $db->getOne("SELECT img_url FROM " .$ecs->table('userbg_img'). " WHERE id = '$id'");
        if ((strpos($old_logo, 'http://') === false) && (strpos($old_logo, 'https://') === false))
        {
            $img_name = basename($old_logo);
            @unlink(ROOT_PATH . "images" . '/userbg_img/' . $img_name);
        }
    }



	if($is_default)
	{
	    $sql = "UPDATE " .$ecs->table('userbg_img'). " SET is_default=0 ";
	    $db->query($sql);
	}

    /* 更新信息 */
    $sql = "UPDATE " .$ecs->table('userbg_img'). " SET ".
            "name = '$name', ".
            "img_url = '$img_url', ".
            "show_order = '$show_order', ".
            "is_default = '$is_default' ".
            "WHERE id = '$id'";

    $db->query($sql);
    /* 记录管理员操作 */
    admin_log($_POST['name'], 'edit', 'user_bg_img');

    /* 清除缓存 */
    clear_cache_files();

    /* 提示信息 */
    $link[0]['text'] = $_LANG['back_list'];
    $link[0]['href'] = 'userbg_img.php?act=list&' . list_link_postfix();

    sys_msg($_LANG['edit'] . "&nbsp;" .stripcslashes($_POST['name']) . "&nbsp;" . $_LANG['attradd_succed'],0, $link);
}

/*------------------------------------------------------ */
//-- 编辑链接名称
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_img_name')
{
    check_authz_json('user_bg_img');

    $id        = intval($_POST['id']);
    $name = json_str_iconv(trim($_POST['val']));

    /* 检查链接名称是否重复 */
    if ($exc->num("name", $name, $id) != 0)
    {
        make_json_error(sprintf($_LANG['img_name_exist'], $name));
    }
    else
    {
        if ($exc->edit("name = '$name'", $id))
        {
            admin_log($img_name, 'edit', 'userbg_img');
            clear_cache_files();
            make_json_result(stripslashes($name));
        }
        else
        {
            make_json_error($db->error());
        }
    }
}

/*------------------------------------------------------ */
//-- 删除友情链接
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('userbg_img');

    $id = intval($_GET['id']);

    /* 获取链子LOGO,并删除 */
    $img_url = $exc->get_name($id, "img_url");

    if ((strpos($img_url, 'http://') === false) && (strpos($img_url, 'https://') === false))
    {
        $img_name = basename($img_url);
        @unlink(ROOT_PATH. "images" . '/userbg_img/'.$img_name);
    }

    $exc->drop($id);
    clear_cache_files();
    admin_log('', 'remove', 'user_bg_img');

    $url = 'userbg_img.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/*------------------------------------------------------ */
//-- 编辑排序
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_show_order')
{
    check_authz_json('user_bg_img');

    $id    = intval($_POST['id']);
    $order = json_str_iconv(trim($_POST['val']));

    /* 检查输入的值是否合法 */
    if (!preg_match("/^[0-9]+$/", $order))
    {
        make_json_error(sprintf($_LANG['enter_int'], $order));
    }
    else
    {
        if ($exc->edit("show_order = '$order'", $id))
        {
            clear_cache_files();
            make_json_result(stripslashes($order));
        }
    }
}

/* 获取友情链接数据列表 */
function get_userbg_list()
{
    $result = get_filter();
    if ($result === false)
    {
        $filter = array();
        $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        /* 获得总记录数据 */
        $sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('userbg_img');
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        $filter = page_and_size($filter);

        /* 获取数据 */
        $sql  = 'SELECT *'.
               ' FROM ' .$GLOBALS['ecs']->table('userbg_img').
                " ORDER by $filter[sort_by] $filter[sort_order]";

        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    $list = array();
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        if (empty($rows['img_url']))
        {
            $rows['img_url'] = '';
        }
        else
        {
            if ((strpos($rows['img_url'], 'http://') === false) && (strpos($rows['img_url'], 'https://') === false))
            {
                $rows['img_url'] = "<img src='" .'../'.$rows['img_url']. "' width=88 height=31 />";
            }
            else
            {
                $rows['img_url'] = "<img src='".$rows['img_url']."' width=88 height=31 />";
            }
        }

        $list[] = $rows;
    }

    return array('list' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

?>