<?php

/**
 * ECSHOP WAP首页
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: testyang $
 * $Id: goods_list.php 15013 2008-10-23 09:31:42Z testyang $
*/

define('IN_ECS', true);
define('ECS_ADMIN', true);
ini_set('display_errors', false);
require(dirname(__FILE__) . '/includes/init.php');
if ($_SESSION['user_id'] > 0)
{
	$smarty->assign('user_name', $_SESSION['user_name']);
}
$action  = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'hot_goods_list';
//热销产品
if($action=='hot_goods_list')
{

	include_once(ROOT_PATH . 'includes/lib_goods.php');
	include_once(ROOT_PATH . 'includes/class/GoodsList.class.php');
	$goodsList_obj = new GoodsList;
	$_REQUEST['page_size']=10;
	$_REQUEST['is_weixin']=1;
	$res = $goodsList_obj->getGoodsList();
	$goodslist = $res['goods_list']; //产品列表
	$brand_list = $res['brand_list']; //品牌
	$categorie = $res['categorie']; //分类
	
	$condition = $res['condition']; //条件
	$cat_id = $condition['cat_id'];	 //当前分类
	$size = $res['size'];	
	$count = $res['count'];
	$page = $res['page'];

	//排序用的
	if($condition['sort']=='add_time')
	{
		$smarty->assign('add_time_order', $condition['order']);
	}else if($condition['sort']=='goods_sales_volume'){
		$smarty->assign('sales_volume_order', $condition['order']);
	}
	
	$condition_str="";
	//start 当前位置
	if($condition['brand_id']){
		$brand = $goodsList_obj->getBrandNameById($condition['brand_id']);
		$temp_str = "<a href=javascript:goods_list_by_brand(".$brand['brand_id'].")>".$brand['brand_name']."</a>";
		$condition_str.=$temp_str;
	}
	
	if($condition['cat_id']){
		$category  = $goodsList_obj->getCategory($condition['cat_id']);
		$temp_str="<a href=goods_list.php?act=hot_goods_list&cat_id=".$category['cat_id'].">".$category['cat_name']."</a>";
		if($condition_str){
			$condition_str.="&nbsp;&nbsp;".$temp_str;
		}else
		{
			$condition_str.=$temp_str;
		}
	}
	
	if($condition['goods_name']){
		if($condition_str){
			$condition_str.="&nbsp;&nbsp;".$condition['goods_name'];
		}
		else
		{
			$condition_str.=$condition['goods_name'];
		}
	}
	
	//end 当前位置
   	
   //	echo "<pre>";print_r($goodslist);
	/* 获取记录条数 */
	$pagebar = mobilePagebar($count,'goods_list.php?act='.$action,$condition);
	$smarty->assign('pagebar' , $pagebar);

    $smarty->assign('new_themes_ur_here', "<a href='category.php?act=hot_goods_list'>热销产品</a>");  // 新模板的热门商品当前位置
	$smarty->assign('brand_id',       $condition['brand_id']);
    $smarty->assign('categorie',       $categorie); // 分类树
    
    $smarty->assign('brandlist',       $brand_list); //品牌和保险公司一样
    $smarty->assign('goods_data',       $goodslist);
    $smarty->assign('category',         $cat_id);
    $smarty->assign('condition',        $condition);
     $smarty->assign('condition_str',   $condition_str);
    //modify yes123 2014-12-09 条件处理!
    $pager = get_pager('category.php',$condition, $count, $page, $size);
    $smarty->assign('action',  'act='.$action);
    $smarty->assign('pager',  $pager);
	$smarty->display('category.dwt');exit;
}
?>