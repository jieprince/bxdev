<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
include_once (ROOT_PATH . 'includes/class/Index.class.php');
include_once(ROOT_PATH . 'includes/class/Article.class.php');
include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');

$index = new Index;
//常见问题
$article_obj = new Article;
$cat_name='常见问题';
$faq = $article_obj->getIndexFAQ($cat_name);
$smarty->assign('article_cat_list',  $faq['article_cat_list']);
$smarty->assign('article_list',       $faq['article_list']);
$smarty->assign('first_article',       $faq['first_article']);

$cat_id = $faq['article_cat_list'][0]['cat_id'];
$sql = "SELECT * FROM ".$ecs->table('article_cat')." WHERE cat_id='$cat_id'";

$current_article_cat = $GLOBALS['db']->getRow($sql);
$smarty->assign('current_article_cat', $current_article_cat);




//新闻动态和行业资讯
$new_article = $article_obj->getArticleListByCat('全联新闻',0,3);
$hy_article = $article_obj->getArticleListByCat('行业资讯',0,8);
$smarty->assign('new_article',       $new_article);
$smarty->assign('hy_article',       $hy_article);

//轮播图
$afficheimg = $index->getAfficheimg('en_index');
$smarty->assign('flash_data',       $afficheimg);




//获取保险产品服务 文章标题 链接 图片
$cat_name='保险产品';
$sql="SELECT cat_id FROM ".$GLOBALS['ecs']->table('article_cat')." WHERE parent_id IN (SELECT cat_id FROM ".$GLOBALS['ecs']->table('article_cat')." WHERE cat_name='保险产品' ) ";
$cat_id_list = $GLOBALS['db']->getAll($sql);
$cat_ids_str = CommonUtils :: arrToStr($cat_id_list, "cat_id");
$sql="SELECT article_id,title,description,article_img FROM ".$GLOBALS['ecs']->table('article')." WHERE cat_id IN($cat_ids_str) AND article_type=1 ORDER BY sort_order ASC,article_id DESC LIMIT 6";
$bx_services = $GLOBALS['db']->getAll($sql);
foreach ($bx_services as $key => $value ) 
{
       $bx_services[$key]['desc'] = $value['description'];
}
$smarty->assign('bx_services',       $bx_services);



/*------------------------------------------------------ */
//-- 判断是否存在缓存，如果存在则调用缓存，反之读取相应内容
/*------------------------------------------------------ */
/* 缓存编号 */
$cache_id = sprintf('%X', crc32($_SESSION['user_rank'] . '-' . $_CFG['lang']));

if (!$smarty->is_cached('default.dwt', $cache_id))
{

	assign_template('',null,'en_middle');
	$smarty->assign('keywords',        htmlspecialchars($_CFG['shop_keywords']));
	$smarty->assign('description',     htmlspecialchars($_CFG['shop_desc']));
    /* links */
    $links = $index->index_get_links();
    $smarty->assign('img_links',  $links['img']);
    $smarty->assign('txt_links',  $links['txt']);

}

$smarty->display('default.dwt', $cache_id);
?>
