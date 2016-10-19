<?php

/**
 * ECSHOP mobile首页
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: index.php 15013 2010-03-25 09:31:42Z liuhui $
*/

define('IN_ECS', true);

require(str_replace('\\','/',dirname(__FILE__)) . '/includes/init.php');
include_once (ROOT_PATH . 'includes/lib_article.php');


$url_arr = array(
	'jianjie'=>"产品介绍",
	'anli'=>"投保案例",
	'xiangguanzixun'=>"相关资讯",
	'toubaoziliao'=>"投保资料",
	'toubaoliucheng'=>"投保流程",
	'liuyan'=>"在线咨询",
	'lipeifuwu'=>"理赔服务",
	'lianxiwomen'=>"联系我们"
);



//单篇文章
$article_arr = array('jianjie','toubaoziliao','toubaoliucheng','zixun_one','lipeifuwu');
$article_list_arr = array('anli','xiangguanzixun');


$_REQUEST['is_weixin']=1;

$module_id  =  $_REQUEST['module_id'];
$smarty->assign('module_title',$url_arr[$module_id]);

//单篇文章
if(in_array($module_id,$article_arr))
{
	//获取
	$article_data = wx_get_article();
	$smarty->assign('article_data', $article_data);
	
	$module_id='article';
}

//文章列表
if(in_array($module_id,$article_list_arr))
{
	wx_get_article_list();

}


if($module_id){
    $smarty->display($module_id.".dwt");
}



function wx_get_article()
{
	global $smarty;
	$a_id = !empty($_GET['a_id']) ? intval($_GET['a_id']) : '';
	if ($a_id > 0)
	{
		$article_row = $GLOBALS['db']->getRow('SELECT title, content FROM ' . $GLOBALS['ecs']->table('article') . ' WHERE article_id = ' . $a_id . ' AND cat_id > 0 AND is_open = 1');
		if (!empty($article_row))
		{
			$article_row['title'] = encode_output($article_row['title']);
			$replace_tag = array('<br />' , '<br/>' , '<br>' , '</p>');
			$article_row['content'] = htmlspecialchars_decode(encode_output($article_row['content']));
/*			$article_row['content'] = str_replace($replace_tag, '{br}' , $article_row['content']);
			$article_row['content'] = strip_tags($article_row['content']);
			$article_row['content'] = str_replace('{br}' , '<br />' , $article_row['content']);*/
			$smarty->assign('article_data', $article_row);
			
			return $article_row;
		}
	}

}

function wx_get_article_list()
{
	global $smarty,$module_id;
	/* 获得指定的分类ID */
	if (!empty($_GET['id']))
	{
	    $cat_id = intval($_GET['id']);
	}
	elseif (!empty($_GET['category']))
	{
	    $cat_id = intval($_GET['category']);
	}
	else
	{
	    ecs_header("Location: ./\n");
	
	    exit;
	}
	
	$condition = array('id'=>$cat_id);
	
	
	/* 获得当前页码 */
	$page   = !empty($_REQUEST['page'])  && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;
	    /* 获得文章总数 */
    $size   = isset($_CFG['article_page_size']) && intval($_CFG['article_page_size']) > 0 ? intval($_CFG['article_page_size']) : 20;
    $count  = get_article_count($cat_id);
    $pages  = ($count > 0) ? ceil($count / $size) : 1;

    if ($page > $pages)
    {
        $page = $pages;
    }
    $pager['search']['id'] = $cat_id;

    $count  = get_article_count($cat_id, '');
    $pages  = ($count > 0) ? ceil($count / $size) : 1;
    if ($page > $pages)
    {
        $page = $pages;
    }

    
    $pagebar = mobilePagebar($count,'get_module.php?act='.$module_id.'',$condition);

	$smarty->assign('pagebar' , $pagebar);
    
    $smarty->assign('artciles_list',    get_cat_articles($cat_id, $page, $size ,''));
    $smarty->assign('cat_id',    $cat_id);
    /* 分页 */
    assign_pager('article_cat', $cat_id, $count, $size, '', '', $page, '');
	
}

?>
