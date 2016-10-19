<?php

/**
 * ECSHOP 首页文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: index.php 17217 2011-01-19 06:29:08Z liubo $
*/


define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
include_once (ROOT_PATH . 'includes/class/Index.class.php');
$smarty->assign('keywords',        htmlspecialchars($_CFG['shop_keywords']));
$smarty->assign('description',     htmlspecialchars($_CFG['shop_desc']));
/**
 * 2014-10-04
 * yes123
 * 获取用户总数、某某用户完成注册，某某用户收入，获取广告轮播图,平台资讯，常见问题
 * start
 */
 	$index = new Index;
    //获取index页面基本数据
	$inde_basedata = $index->getIndexBaseDate('shop');
	//$show_info = $inde_basedata['show_info'];
	//shuffle($show_info);
	//$user_count = $inde_basedata['user_count'];
	$article_information = $inde_basedata['article_information'];
	$ebao_hot_goods = $inde_basedata['ebao_hot_goods'];
	//ss_log("特色推荐：".print_r($ebao_hot_goods,true));
	$afficheimg = $inde_basedata['afficheimg'];
	
	
	//总数
 	$smarty->assign('user_count',$index->getUserCount());
	//友情链接
    $links = $index->index_get_links('friend_link');
    $smarty->assign('img_links',       $links['img']);
    $smarty->assign('txt_links',       $links['txt']);
    
    
    
    /*合作伙伴*/
	$partners = $index->index_get_links('partner_link');
	$partners['img'] = array_chunk($partners['img'],12);
	$smarty->assign('img_partners',   $partners['img']);
    $smarty->assign('txt_partners',   $partners['txt']);
    
    //常见问题
	$smarty->assign('article_cat_list',  $inde_basedata['faq']['article_cat_list']);
	$smarty->assign('article_list',       $inde_basedata['faq']['article_list']);
	$smarty->assign('first_article',       $inde_basedata['faq']['first_article']);

	//add by dingchaoyang 2014-11-11
	//响应json数据到客户端
	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
	EbaAdapter::responseMemberNews($show_info);
	//end add by dingchaoyang 2014-11-11
	$smarty->assign('show_info', $show_info);

 	//获取广告轮播图的信息
	$smarty->assign('flash_data', $afficheimg);  
	
	//add by dingchaoyang 2014-11-11
	//响应json数据到客户端
	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
	EbaAdapter::responseArticleInfo($article_information);
	//end add by dingchaoyang 2014-11-11
	$smarty->assign('article_information', $article_information); 
	

	//add by dingchaoyang 2014-11-12
	//响应json数据到客户端
	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
	EbaAdapter::responseHotProduct($ebao_hot_goods);
	//end add by dingchaoyang 2014-11-12
	$smarty->assign('ebao_hot_goods', $ebao_hot_goods);
	
	//ss_log(print_r($ebao_hot_goods,true));
	
	//如果用户已经登录，则获取用户基本信息
	if($_SESSION['user_id'])
	{
		include_once(ROOT_PATH . 'includes/lib_user.php');
		$user_base_info = user_base_info($_SESSION['user_id']);
		$smarty->assign('user_base_info', $user_base_info);
	}
	
	//验证码处理
	$captcha = intval($_CFG['captcha']);
    if (($captcha & CAPTCHA_LOGIN) && (!($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && $_SESSION['login_fail'] > 2)) && gd_version() > 0)
    {
        $GLOBALS['smarty']->assign('enabled_captcha', 1);
        $GLOBALS['smarty']->assign('rand', mt_rand());
    }
	
    /* 首页推荐分类 */
    $cat_recommend_res = $db->getAll("SELECT c.cat_id, c.cat_name, cr.recommend_type,c.background_img FROM " . $ecs->table("cat_recommend") . " AS cr INNER JOIN " . $ecs->table("category") . " AS c ON cr.cat_id=c.cat_id WHERE cr.recommend_type=1");
    if (!empty($cat_recommend_res))
    {
        $cat_rec_array = array();
        foreach($cat_recommend_res as $cat_recommend_data)
        {
        	
        	//所有子分类
        	$children_cat = get_child_tree($cat_recommend_data['cat_id']);
        	
        	
        	//此分类下的所有产品
        	$children = get_children($cat_recommend_data['cat_id']);
        	$sql = "SELECT g.goods_id,g.goods_name,g.shop_price,goods_img,goods_brief,ipa.product_characteristic,description  FROM " . $ecs->table('goods'). " AS g INNER JOIN t_insurance_product_attribute AS ipa ON ipa.attribute_id=g.tid ".
               " WHERE ($children OR " . get_extension_goods($children) . ') AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 ORDER BY sort_order  LIMIT 3  ';
               //获得当前分类下商品价格的最大值、最小值
        	$goods_list = $db->getAll($sql);
        	
            $cat_rec[$cat_recommend_data['recommend_type']][] = array('cat_id' => $cat_recommend_data['cat_id'], 'cat_name' => $cat_recommend_data['cat_name'],'background_img'=>$cat_recommend_data['background_img'],'children_cat'=>$children_cat,'goods_list'=>$goods_list);
            
            
        }
        $smarty->assign('cat_rec', $cat_rec);
        
        
    }
	    
    $categorie = get_categories_tree(0);
	$smarty->assign('categorie',       $categorie); // 分类树
	
/**
 * 2014-10-04
 * yes123
 * end
 */
assign_template();
$smarty->display('index.dwt', $cache_id);

?>