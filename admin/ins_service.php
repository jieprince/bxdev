<?php

/**
 * ECSHOP 管理中心品牌管理
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: brand.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH . 'includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);
$ins_service_logo = "ins_service_logo";
$exc = new exchange($ecs->table("ins_service"), $db, 'ins_id', 'ins_name');

/*------------------------------------------------------ */
//-- 品牌列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    $smarty->assign('ur_here',      '列表');
    $smarty->assign('action_link',  array('text' => '添加', 'href' => 'ins_service.php?act=add'));
    $smarty->assign('full_page',    1);

    $res = get_ins_service_list();

    $smarty->assign('ins_list',   $res['ins_list']);
    $smarty->assign('filter',       $res['filter']);
    $smarty->assign('record_count', $res['record_count']);
    $smarty->assign('page_count',   $res['page_count']);

    assign_query_info();
    $smarty->display('ins_service_list.htm');
}

/*------------------------------------------------------ */
//-- 添加品牌
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{

    $smarty->assign('ur_here',     '添加');
    $smarty->assign('action_link', array('text' => '列表', 'href' => 'ins_service.php?act=list'));
    $smarty->assign('form_action', 'insert');

    assign_query_info();
    $smarty->assign('ins_data', array('sort_order'=>50, 'is_show'=>1));
    $smarty->display('ins_service_info.htm');
}
elseif ($_REQUEST['act'] == 'insert')
{
	
    $is_show = isset($_REQUEST['is_show']) ? intval($_REQUEST['is_show']) : 0;
    $article_id_list = isset($_REQUEST['article_id']) ? $_REQUEST['article_id'] : 0;
    $article_ids="";
    if($article_id_list)
    {
	    $article_ids=implode(",",$article_id_list);
    	
    }

    $ins_desc = isset($_REQUEST['ins_desc']) ? trim($_REQUEST['ins_desc']) : '';
    $is_only = $exc->is_only('ins_name', $_POST['ins_name']);

    if (!$is_only)
    {
        sys_msg(sprintf($_LANG['brandname_exist'], stripslashes($_POST['ins_name'])), 1);
    }

     /*处理图片*/
    $image->data_dir = 'images';
    $img_name = basename($image->upload_image($_FILES['ins_logo'],$ins_service_logo));
	$img_name = $image->data_dir."/".$ins_service_logo."/".$img_name;
	
	
     /*处理URL*/
    $site_url = sanitize_url( $_POST['site_url'] );

    /*插入数据*/

    $sql = "INSERT INTO ".$ecs->table('ins_service')."(ins_name, site_url, ins_desc, ins_logo, is_show, sort_order,article_ids) ".
           "VALUES ('$_POST[ins_name]', '$site_url', '$_POST[ins_desc]', '$img_name', '$is_show', '$_POST[sort_order]','$article_ids')";
    $db->query($sql);

    admin_log($_POST['ins_name'],'add','ins_service');

    /* 清除缓存 */
    clear_cache_files();

    $link[0]['text'] = $_LANG['continue_add'];
    $link[0]['href'] = 'ins_service.php?act=add';

    $link[1]['text'] = $_LANG['back_list'];
    $link[1]['href'] = 'ins_service.php?act=list';

    sys_msg($_LANG['brandadd_succed'], 0, $link);
}

/*------------------------------------------------------ */
//-- 编辑品牌
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit')
{

    $sql = "SELECT * FROM " .$ecs->table('ins_service'). " WHERE ins_id='$_REQUEST[id]'";
    $ins_data = $db->GetRow($sql);
	
			
	if($ins_data['article_ids'])
	{
		$sql = "SELECT article_id,title FROM ".$GLOBALS['ecs']->table('article')." WHERE article_id IN($ins_data[article_ids]) ";
		$article_list = $GLOBALS['db']->getAll($sql); 
		$ins_data['article_list'] = $article_list;
	}
	
    $smarty->assign('ur_here',     $_LANG['brand_edit']);
    $smarty->assign('action_link', array('text' => '列表', 'href' => 'ins_service.php?act=list&' . list_link_postfix()));
    $smarty->assign('ins_data',       $ins_data);
    $smarty->assign('form_action', 'updata');

    assign_query_info();
    $smarty->display('ins_service_info.htm');
}
elseif ($_REQUEST['act'] == 'updata')
{
	
	$article_id_list = isset($_REQUEST['article_id']) ? $_REQUEST['article_id'] : 0;
	if($article_id_list)
	{
	    $article_ids=implode(",",$article_id_list);
		
	}
	

    if ($_POST['ins_name'] != $_POST['old_brandname'])
    {
        /*检查品牌名是否相同*/
        $is_only = $exc->is_only('ins_name', $_POST['ins_name'], $_POST['id']);

        if (!$is_only)
        {
            sys_msg(sprintf($_LANG['brandname_exist'], stripslashes($_POST['ins_name'])), 1);
        }
    }

    /*对描述处理*/
    if (!empty($_POST['ins_desc']))
    {
        $_POST['ins_desc'] = $_POST['ins_desc'];
    }

    $is_show = isset($_REQUEST['is_show']) ? intval($_REQUEST['is_show']) : 0;
     /*处理URL*/
    $site_url = sanitize_url( $_POST['site_url'] );
	
    /* 处理图片 */
    $param = "ins_name = '$_POST[ins_name]',  site_url='$site_url', ins_desc='$_POST[ins_desc]', is_show='$is_show', sort_order='$_POST[sort_order]' ";
   	if ((isset($_FILES['ins_logo']['error']) && $_FILES['ins_logo']['error'] == 0) || (!isset($_FILES['ins_logo']['error']) && isset($_FILES['ins_logo']['tmp_name']) && $_FILES['ins_logo']['tmp_name'] != 'none'))
	{
	 	$image->data_dir = 'images';
		$img_name = $image->upload_image($_FILES['ins_logo'],$ins_service_logo);
        //有图片上传
        $param .= " ,ins_logo = '$img_name' ";
	}
    
    
    echo $img_name;
	
	
	if($article_ids)
	{
		  $param .= " ,article_ids = '$article_ids' ";
	}
	else
	{
		$param .= " ,article_ids = '0' ";
	}
	
	
    if ($exc->edit($param,  $_POST['id']))
    {
        /* 清除缓存 */
        clear_cache_files();

        admin_log($_POST['ins_name'], 'edit', 'brand');

        $link[0]['text'] = $_LANG['back_list'];
        $link[0]['href'] = 'ins_service.php?act=list&' . list_link_postfix();
        $note = vsprintf($_LANG['brandedit_succed'], $_POST['ins_name']);
        sys_msg($note, 0, $link);
    }
    else
    {
        die($db->error());
    }
}

/*------------------------------------------------------ */
//-- 编辑品牌名称
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_ins_name')
{

    $id     = intval($_POST['id']);
    $name   = json_str_iconv(trim($_POST['val']));

    /* 检查名称是否重复 */
    if ($exc->num("ins_name",$name, $id) != 0)
    {
        make_json_error(sprintf($_LANG['brandname_exist'], $name));
    }
    else
    {
        if ($exc->edit("ins_name = '$name'", $id))
        {
            admin_log($name,'edit','brand');
            make_json_result(stripslashes($name));
        }
        else
        {
            make_json_result(sprintf($_LANG['brandedit_fail'], $name));
        }
    }
}

elseif($_REQUEST['act'] == 'add_brand')
{
    $brand = empty($_REQUEST['brand']) ? '' : json_str_iconv(trim($_REQUEST['brand']));

    if(brand_exists($brand))
    {
        make_json_error($_LANG['ins_name_exist']);
    }
    else
    {
        $sql = "INSERT INTO " . $ecs->table('ins_service') . "(ins_name)" .
               "VALUES ( '$brand')";

        $db->query($sql);
        $ins_id = $db->insert_id();

        $arr = array("id"=>$ins_id, "brand"=>$brand);

        make_json_result($arr);
    }
}
/*------------------------------------------------------ */
//-- 编辑排序序号
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_sort_order')
{
    $id     = intval($_POST['id']);
    $order  = intval($_POST['val']);
    $name   = $exc->get_name($id);

    if ($exc->edit("sort_order = '$order'", $id))
    {
        admin_log(addslashes($name),'edit','brand');

        make_json_result($order);
    }
    else
    {
        make_json_error(sprintf($_LANG['brandedit_fail'], $name));
    }
}

/*------------------------------------------------------ */
//-- 切换是否显示
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_show')
{
    $id     = intval($_POST['id']);
    $val    = intval($_POST['val']);

    $exc->edit("is_show='$val'", $id);

    make_json_result($val);
}

/*------------------------------------------------------ */
//-- 删除品牌
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    $id = intval($_GET['id']);

    /* 删除该品牌的图标 */
    $sql = "SELECT ins_logo FROM " .$ecs->table('ins_service'). " WHERE ins_id = '$id'";
    $logo_name = $db->getOne($sql);
    if (!empty($logo_name))
    {
        @unlink(ROOT_PATH . "images" . '/'.$ins_service_logo.'/' .$logo_name);
    }

    $exc->drop($id);


    $url = 'ins_service.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/*------------------------------------------------------ */
//-- 删除品牌图片
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'drop_logo')
{
 
    $ins_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    /* 取得logo名称 */
    $sql = "SELECT ins_logo FROM " .$ecs->table('ins_service'). " WHERE ins_id = '$ins_id'";
    $logo_name = $db->getOne($sql);

    if (!empty($logo_name))
    {
        @unlink(ROOT_PATH ."/".$logo_name);
        $sql = "UPDATE " .$ecs->table('ins_service'). " SET ins_logo = '' WHERE ins_id = '$ins_id'";
        $db->query($sql);
    }
    $link= array(array('text' => $_LANG['brand_edit_lnk'], 'href' => 'ins_service.php?act=edit&id=' . $ins_id), array('text' => $_LANG['brand_list_lnk'], 'href' => 'ins_service.php?act=list'));
    sys_msg($_LANG['drop_ins_logo_success'], 0, $link);
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $res = get_ins_service_list();
    $smarty->assign('ins_list',     $res['ins_list']);
    $smarty->assign('filter',       $res['filter']);
    $smarty->assign('record_count', $res['record_count']);
    $smarty->assign('page_count',   $res['page_count']);

    make_json_result($smarty->fetch('brand_list.htm'), '',
        array('filter' => $res['filter'], 'page_count' => $res['page_count']));
}
/*------------------------------------------------------ */

//-- 搜索商品或者ISBN，仅返回名称及ID

/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'search_article')
{
   $name = $_GET['name'];

   $data = array();
   
   $sql = "SELECT article_id,title FROM " . $ecs->table('article') . " WHERE title LIKE '%$name%' ";

   $row = $db->getAll($sql);   

   if(empty($row))
   {
   	 	die(json_encode(array('code'=>1))); 
   }

   die(json_encode($row));  

}
/**
 * 获取品牌列表
 *
 * @access  public
 * @return  array
 */
function get_ins_service_list()
{
    $result = get_filter();
    if ($result === false)
    {
        /* 分页大小 */
        $filter = array();

    
        $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('ins_service');
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        $filter = page_and_size($filter);
  
        $sql = "SELECT * FROM ".$GLOBALS['ecs']->table('ins_service')." ORDER BY sort_order ASC";

        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

    $arr = array();
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $ins_logo = empty($rows['ins_logo']) ? '' :
            '<a href="../'.$rows['ins_logo'].'" target="_brank"><img src="images/picflag.gif" width="16" height="16" border="0" alt='.$GLOBALS['_LANG']['ins_logo'].' /></a>';
        $site_url   = empty($rows['site_url']) ? 'N/A' : '<a href="'.$rows['site_url'].'" target="_brank">'.$rows['site_url'].'</a>';

        $rows['ins_logo'] = $ins_logo;
        $rows['site_url']   = $site_url;
        $arr[]= $rows;
    }
	
	return array('ins_list' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

?>
