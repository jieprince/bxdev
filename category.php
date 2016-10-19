<?php

/**
 * ECSHOP 商品分类
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: category.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
$smarty->assign('keywords',        htmlspecialchars($_CFG['shop_keywords']));
$smarty->assign('description',     htmlspecialchars($_CFG['shop_desc']));
if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}

$action = $_REQUEST['act'];


//add 常见问题页面 add 2014-10-05 yes123 
if($action=='article_list')
{
	
	$article_type = isset($_REQUEST['article_type'])?trim($_REQUEST['article_type']):'faq';
	
	$article_list = get_article_cat_list($cat_tag);
	$categorie = get_categories_tree(0);
	$smarty->assign('categorie',       $categorie); // 分类树
	
	if($article_type=='faq')
	{
		assign_template();
	}
	else
	{
		assign_template('',null,'en_middle');;
	}
	
	include_once(ROOT_PATH . 'includes/class/Article.class.php');
    $article_obj = new Article;
    
    if($article_type=='en_faq')
    {
	    $cat_name='常见问题';
    }
    elseif($article_type=='information')
    {
    	$cat_name='资讯中心';
    	
    }
    else
    {
    	$cat_name='商城常见问题';
    }
	$res = $article_obj->getArticleList($cat_name);

	$smarty->assign('act',  $action);
	$smarty->assign('article_list',       $res['article_list']); 
	$smarty->assign('child_article_cat_list',  $res['child_article_cat_list']); //文章分类列表
	$smarty->assign('article_cat',  $res['article_cat']); //当前文章分类
	$smarty->assign('is_top_article_list',  $res['is_top_article_list']);//热门文章
	$smarty->assign('condition',  $res['condition']);
	$smarty->assign('pager',  $res['pager']);
	
	
	$template_name = $article_type.'.dwt';

	$smarty->display($template_name);exit;
}
elseif($action=='en_article_list')
{
	$article_type=isset($_REQUEST['article_type'])?trim($_REQUEST['article_type']):'';
	
	//获取咨询分类
	include_once(ROOT_PATH . 'includes/class/Article.class.php');
    $article_obj = new Article;
    assign_template('',null,'en_middle');;
	$res = $article_obj->getArticleList();
	$smarty->assign('act',  $action);
	$smarty->assign('article_list',       $res['article_list']); 
	$smarty->assign('child_article_cat_list',  $res['child_article_cat_list']); //文章分类列表
	$smarty->assign('article_cat',  $res['article_cat']); //当前文章分类
	$smarty->assign('condition',  $res['condition']);
	$smarty->assign('pager',  $res['pager']);
	$smarty->assign('article_type',$article_type);
	$smarty->assign('article_type_list',  $article_type_list);
	$smarty->assign('global_article_type',  $article_type_list[$article_type]);
	$smarty->display('en_article_list.dwt');
	exit;
}

elseif($action=='en_faq')
{
	$article_type=isset($_REQUEST['article_type'])?trim($_REQUEST['article_type']):'';
	
	//获取咨询分类
	include_once(ROOT_PATH . 'includes/class/Article.class.php');
    $article_obj = new Article;
    assign_template('',null,'en_middle');;
	$res = $article_obj->getArticleList();
	$smarty->assign('act',  $action);
	$smarty->assign('article_list',       $res['article_list']); 
	$smarty->assign('child_article_cat_list',  $res['child_article_cat_list']); //文章分类列表
	$smarty->assign('article_cat',  $res['article_cat']); //当前文章分类
	$smarty->assign('condition',  $res['condition']);
	$smarty->assign('pager',  $res['pager']);
	$smarty->assign('article_type',$article_type);
	$smarty->assign('article_type_list',  $article_type_list);
	$smarty->assign('global_article_type',  $article_type_list[$article_type]);
	$smarty->display('eq_faq.dwt');
	exit;
}
if($action=='getAllMoblePhone'){
	include_once (ROOT_PATH . 'includes/class/Temp.class.php');
	Temp::getAllMoblePhone();
	exit;
}

if($action=='addInsurerCode'){
	include_once (ROOT_PATH . 'includes/class/Temp.class.php');
	Temp::addInsurerCode();
	exit;
}
if ($action == 'mdrt_meeting_email'){
	 $msg="";
	 $successMsg="";	
	 $failMsg="";	
	 
	 $email_title = "2015年首场MDRT名师公开课";  //modifed by yes123 2014-11-26
	 $add_arr = explode(";",$email_addrs);
	 $tpl = get_mail_template('mdrt_meeting');
     $content = $smarty->fetch('str:' . $tpl['template_content']);
     $sql="SELECT user_id,email FROM " . $GLOBALS['ecs']->table('users')." WHERE email IS NOT NULL AND email<>''";
     $user_list =  $GLOBALS['db']->getAll($sql);
 
     $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
     
     foreach ($user_list as $key => $value ) {
     	if(preg_match($pattern, $value['email'] ))
	    {
	       	if (send_mail( "客户", $value['email'], $email_title, $content, $tpl['is_html']))
			{	
			   echo '已发送邮件地址：'.$value['email']."<br/>";	
			   $successMsg.=$value;
			}
			else
			{
			  echo '发送失败：'.$value['email']."<br/>";		
			   $failMsg.=$value;
			}
	    }else{
	    	echo '邮件地址不合法：'.$value['email']."<br/>";	
	    	
	    }
		ss_log('批量发送邮件暂停3秒');	    
	    sleep(3);
	 }


	 if($successMsg!=''){
	 	$msg.=" 发送成功:".$successMsg." ";
	 }
	 if($failMsg!=''){
	 	$msg.="  发送失败:".$failMsg." ";
	 }
		
	 echo $msg;
	 exit;
}
//e保专题
if($action=='special')
{
	
	$page = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);
	$topic_id = empty($_REQUEST['topic_id']) || (intval($_REQUEST['topic_id']) <= 0) ? 0 : intval($_REQUEST['topic_id']);
	$size = 5;
	
	$smarty->assign('categorie',       get_categories_tree(0)); // 分类树
    //add by yes123 2014-11-12  新模板当前位置
    $smarty->assign('new_themes_ur_here', "<a href='category.php?act=special'>e保专题</a>");  // 新模板的热门商品当前位置
	
	$count = get_topic_count($topic_id);
    $max_page = ($count> 0) ? ceil($count / $size) : 1;
    if ($page > $max_page)
    {
        $page = $max_page;
    }
    
    $topic_list = get_topic_list($page,$size,$topic_id);
    if($display == 'grid')
    {
        if(count($goodslist) % 2 != 0)
        {
            $goodslist[] = array();
        }
    }
    
    $smarty->assign('topic_list',       $topic_list);
    $pager = get_pager('category.php', array('act' => $action), $count, $page, $size);
    $smarty->assign('pager',  $pager);
	$smarty->display('special.dwt');exit;
}


//add yes123 2015-01-04 自定义专题
if($action=='to_html')
{
	$html_name = $_GET['html_name'];
	$smarty->display($html_name.".dwt");exit;

}

//e保专题
if($action=='special_list')
{
	
	$page = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);
	$topic_id = empty($_REQUEST['topic_id']) || (intval($_REQUEST['topic_id']) <= 0) ? 0 : intval($_REQUEST['topic_id']);
	$size = 5;
	
	$smarty->assign('topic_cat',       get_topic_list(1,20,0)); // 分类
    //add by yes123 2014-11-12  新模板当前位置
    $parent_topic  = get_topic_by_id($topic_id);
    $smarty->assign('new_themes_ur_here', "<a href='category.php?act=special'>e保专题</a> > ".$parent_topic['title']);  // 新模板的热门商品当前位置
	
	$count = get_topic_count($topic_id);
    $max_page = ($count> 0) ? ceil($count / $size) : 1;
    if ($page > $max_page)
    {
        $page = $max_page;
    }
    
    $topic_list = get_topic_list($page,$size,$topic_id);
    $smarty->assign('topic_list',       $topic_list);
    $pager = get_pager('category.php', array('act' => $action), $count, $page, $size);
    $smarty->assign('pager',  $pager);
	$smarty->display('special_list.dwt');exit;
}


//e保专题
if($action=='topic_show')
{
	
	$topic_id = $_REQUEST['topic_id'];
	$topic  = get_topic_by_id($topic_id);
	$topic['add_time'] = local_date($GLOBALS['_CFG']['time_format'], $topic['add_time']);
    //add by yes123 2014-11-12  新模板当前位置
    $parent_topic  = get_topic_by_id($topic['parent_id']);
    $smarty->assign('new_themes_ur_here', "<a href='category.php?act=special'>e保专题</a> > <a href=category.php?act=special_list&topic_id=" .$parent_topic['topic_id'].">".$parent_topic['title']." </a> > ". $topic['title']);  // 新模板的热门商品当前位置
    
    $smarty->assign('topic',       $topic);
	$smarty->display('special_article.dwt');exit;
}


//热销产品
if($action=='hot_goods_list')
{
	
	include_once(ROOT_PATH . 'includes/class/GoodsList.class.php');
	include_once(ROOT_PATH . 'includes/lib_goods.php');
	
	$cooperator = isset($_GET['cooperator'])?$_GET['cooperator']:0;
	$buyerId = isset($_GET['buyerId'])?$_GET['buyerId']:0;
	if($cooperator && $buyerId)	
	{
		include_once(ROOT_PATH . 'Partner/partner_common.php');
		partner_login($cooperator,$buyerId);
	}
	
	$goodsList_obj = new GoodsList;
	$_REQUEST['page_size']=5;
	$res = $goodsList_obj->getGoodsList();
	$goodslist = $res['goods_list']; //产品列表
	$current_category = $res['current_category']; //产品列表
	$brand_list = $res['brand_list']; //品牌
	$categorie = $res['categorie']; //分类
	
	$condition = $res['condition']; //条件
	$cat_id = $condition['cat_id'];	 //当前分类
	$size = $res['size'];	
	$count = $res['count'];
	$page = $res['page'];

	//排序用的
	if($_REQUEST['sort']=='add_time')
	{
		$smarty->assign('add_time_order', $condition['order']);
	}else if($_REQUEST['sort']=='goods_sales_volume'){
		$smarty->assign('sales_volume_order', $condition['order']);
	}
	
	
	include_once(ROOT_PATH . 'includes/class/Article.class.php');
    $article_obj = new Article;
    $smarty->assign('is_top_article_list',  $article_obj->getIsTopArticle('商城常见问题',1,3));

    $smarty->assign('new_themes_ur_here', "<a href='category.php?act=hot_goods_list'>热销产品</a>");  // 新模板的热门商品当前位置
	$smarty->assign('brand_id',       $condition['brand_id']);
    $smarty->assign('categorie',       $categorie); // 分类树
    
    $smarty->assign('brandlist',       $brand_list);
    $smarty->assign('goods_list',       $goodslist);
    $smarty->assign('current_category', $current_category);
    $smarty->assign('category',         $cat_id);
    $smarty->assign('condition',        $condition);
    //modify yes123 2014-12-09 条件处理!
    $pager = get_pager('category.php',$condition, $count, $page, $size);
    $smarty->assign('action',  'act='.$action);
    $smarty->assign('pager',  $pager);
    assign_template();
	$smarty->display('product_list.dwt');exit;
}

/*企业站的咨询预约*/
if($_REQUEST['act']=='reservation')
{
	
	assign_template('',null,'en_middle');;
	$submit_a = isset($_REQUEST['submit_a'])?intval($_REQUEST['submit_a']):0;
	
	if($submit_a)
	{
		
		/* 验证码检查 */
        if ((intval($_CFG['captcha']) & CAPTCHA_MESSAGE) && gd_version() > 0)
        {
            if (empty($_POST['captcha']))
            {
                show_message($_LANG['invalid_captcha'], $_LANG['sign_up'], 'category.php?act=reservation', 'error');
            }

            /* 检查验证码 */
            include_once('includes/cls_captcha.php');

            $validator = new captcha();
            if (!$validator->check_word($_POST['captcha']))
            {
                show_message($_LANG['invalid_captcha'], $_LANG['sign_up'], 'category.php?act=reservation', 'error');
            }
        }
		
		
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
		if($r)
		{
			show_message('提交成功！');
		}
		
	}
	else
	{
		/* 验证码相关设置 */
	    if ((intval($_CFG['captcha']) & CAPTCHA_MESSAGE) && gd_version() > 0)
	    {
	        $smarty->assign('enabled_captcha', 1);
	        $smarty->assign('rand',            mt_rand());
	    }
		
		$smarty->display('reservation.dwt');exit;
	}
}


/**
 * add yes123 2014-12-08  app端 获取我的收藏
 */
if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == "favoriteinskind"){
	//add sessionmanager for app dingchaoyang 2014-12-21
	include_once (ROOT_PATH . 'api/EBaoApp/eba_sessionManager.class.php');
	Eba_SessionManager::setSession();
	//end by dingchaoyang 2014-12-21
	
	//add log for app dingchaoyang 2014-12-23
	include_once (ROOT_PATH . 'api/EBaoApp/eba_logManager.class.php');
	Eba_LogManager::log('data from category.php for favoriteproduct .');
	//end
	//这里添加good_id in (我的收藏表中的good_id)
	$user_id = $_SESSION['user_id']==null?$_REQUEST['uid']:$_SESSION['user_id'];
	if($user_id)
	{
		$sql = " SELECT goods_id FROM ". $GLOBALS['ecs']->table('collect_goods') . " WHERE user_id = '$user_id' ORDER BY rec_id DESC";
    	$goods_id_list = $GLOBALS['db'] -> getAll($sql);

    	if($goods_id_list)
    	{
    		include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
    		$goods_id_srt = CommonUtils::arrToStr($goods_id_list,"goods_id");
    		
    		include_once(ROOT_PATH . 'includes/class/GoodsList.class.php');
    		include_once(ROOT_PATH . 'includes/lib_goods.php');
    		$goodsList_obj = new GoodsList;
    		$_REQUEST['page_size']=5;
    		$res = $goodsList_obj->getGoodsList();
    		$goodslist = $res['goods_list']; //产品列表
    		
    		//add by dingchaoyang 2014-12-8
    		//响应json数据到客户端
    		//     print_r(iconv('utf-8', 'gb2312//ignore', json_encode($goodslist)));
    		include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
    		EbaAdapter::responseFavoriteProductList($goodslist);
    		//end add by dingchaoyang 2014-12-8
//   			$data = array();
//   			//排好序再返回!
//   			foreach ( $goods_id_list as $value ) {
//        			$data[] = $goodslist[$value['goods_id']];
       			
// 			}
// 			die(json_encode($data));
    	}
    	
	}

}

if (isset ( $_REQUEST ['command'] ) && $_REQUEST ['command'] == "insurance"){
	//add sessionmanager for app dingchaoyang 2015-1-12 显示该用户收藏产品的标识
	include_once (ROOT_PATH . 'api/EBaoApp/eba_sessionManager.class.php');
	Eba_SessionManager::setSession();
	//end by dingchaoyang 2015-1-12
	//add log for app dingchaoyang 2014-12-23
	include_once (ROOT_PATH . 'api/EBaoApp/eba_logManager.class.php');
	Eba_LogManager::log('data from category.php for productlist.');
	//end
	include_once(ROOT_PATH . 'includes/class/GoodsList.class.php');
	include_once(ROOT_PATH . 'includes/lib_goods.php');
	$goodsList_obj = new GoodsList;
	$_REQUEST['page_size']=5;
	$res = $goodsList_obj->getGoodsList();
	$goodslist = $res['goods_list']; //产品列表

	//add by dingchaoyang 2014-11-14
	//响应json数据到客户端
	//      print_r(iconv('utf-8', 'gb2312//ignore', json_encode($goodslist)));
	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
	EbaAdapter::responseProductList($goodslist);
	//end add by dingchaoyang 2014-11-14
}

//热销产品
if($action=='introduce')
{
	$smarty->display('introduce.dwt');exit;
}
/*------------------------------------------------------ */
//-- INPUT
/*------------------------------------------------------ */

/* 获得请求的分类 ID */
if (isset($_REQUEST['id']))
{
    $cat_id = intval($_REQUEST['id']);
    if (isset($_REQUEST['good_id'])){
    	$good_id_ = intval($_REQUEST['good_id']);//add by dingchaoyang 2014-12-10
    }
}
elseif (isset($_REQUEST['category']))
{
    $cat_id = intval($_REQUEST['category']);
}
else
{
    /* 如果分类ID为0，则返回首页 */
    ecs_header("Location: ./\n");

    exit;
}



/* 初始化分页信息 */
$page = isset($_REQUEST['page'])   && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;
$size = isset($_CFG['page_size'])  && intval($_CFG['page_size']) > 0 ? intval($_CFG['page_size']) : 10;
$brand = isset($_REQUEST['brand']) && intval($_REQUEST['brand']) > 0 ? intval($_REQUEST['brand']) : 0;
$price_max = isset($_REQUEST['price_max']) && intval($_REQUEST['price_max']) > 0 ? intval($_REQUEST['price_max']) : 0;
$price_min = isset($_REQUEST['price_min']) && intval($_REQUEST['price_min']) > 0 ? intval($_REQUEST['price_min']) : 0;
$filter_attr_str = isset($_REQUEST['filter_attr']) ? htmlspecialchars(trim($_REQUEST['filter_attr'])) : '0';

$filter_attr_str = trim(urldecode($filter_attr_str));
$filter_attr_str = preg_match('/^[\d\.]+$/',$filter_attr_str) ? $filter_attr_str : '';
$filter_attr = empty($filter_attr_str) ? '' : explode('.', $filter_attr_str);


/* 排序、显示方式以及类型 */
$default_display_type = $_CFG['show_order_type'] == '0' ? 'list' : ($_CFG['show_order_type'] == '1' ? 'grid' : 'text');
$default_sort_order_method = $_CFG['sort_order_method'] == '0' ? 'DESC' : 'ASC';
$default_sort_order_type   = $_CFG['sort_order_type'] == '0' ? 'goods_id' : ($_CFG['sort_order_type'] == '1' ? 'shop_price' : 'last_update');
//add comment by dingchaoyang 2014-12-12
// $sort  = (isset($_REQUEST['sort'])  && in_array(trim(strtolower($_REQUEST['sort'])), array('goods_id', 'shop_price', 'last_update'))) ? trim($_REQUEST['sort'])  : $default_sort_order_type;
// $order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), array('ASC', 'DESC')))                              ? trim($_REQUEST['order']) : $default_sort_order_method;
$sort =  $_REQUEST['sort'] == '' ? 'og_total' : trim($_REQUEST['sort']);
$order  = trim($_REQUEST['order'])==''?'desc':trim($_REQUEST['order']);
//排序用的
if($sort=='add_time')
{
	//add yes123 2014-12-10 添加add_time所属表
	$sort =' g.add_time ';
	//del yes123 2014-12-10  ,重构，不需要了
}
$goods_name_filter = trim($_REQUEST['goods_name']);
//end by dingchaoyang 2014-12-12
$display  = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), array('list', 'grid', 'text'))) ? trim($_REQUEST['display'])  : (isset($_COOKIE['ECS']['display']) ? $_COOKIE['ECS']['display'] : $default_display_type);
//$display  = in_array($display, array('list', 'grid', 'text')) ? $display : 'text';



/***
 * 修改时间:2014/7/24
 * 修改者：鲍洪州
 * 功能：列表显示默认样式 text
 */
 $display = 'text';
setcookie('ECS[display]', $display, gmtime() + 86400 * 7);
/*------------------------------------------------------ */
//-- PROCESSOR
/*------------------------------------------------------ */

/* 页面的缓存ID */
$cache_id = sprintf('%X', crc32($cat_id . '-' . $display . '-' . $sort  .'-' . $order  .'-' . $page . '-' . $size . '-' . $_SESSION['user_rank'] . '-' .
    $_CFG['lang'] .'-'. $brand. '-' . $price_max . '-' .$price_min . '-' . $filter_attr_str));


if (!$smarty->is_cached('category.dwt', $cache_id))
{
    /* 如果页面没有被缓存则重新获取页面的内容 */

    $children = get_children($cat_id);

    $cat = get_cat_info($cat_id);   // 获得分类的相关信息

    if (!empty($cat))
    {
        $smarty->assign('keywords',    htmlspecialchars($cat['keywords']));
        $smarty->assign('description', htmlspecialchars($cat['cat_desc']));
        $smarty->assign('cat_style',   htmlspecialchars($cat['style']));
    }
    else
    {
        /* 如果分类不存在则返回首页 */
        ecs_header("Location: ./\n");

        exit;
    }

    /* 赋值固定内容 */
    if ($brand > 0)
    {
        $sql = "SELECT brand_name FROM " .$GLOBALS['ecs']->table('brand'). " WHERE brand_id = '$brand'";
        $brand_name = $db->getOne($sql);
    }
    else
    {
        $brand_name = '';
    }

    /* 获取价格分级 */
    if ($cat['grade'] == 0  && $cat['parent_id'] != 0)
    {
        $cat['grade'] = get_parent_grade($cat_id); //如果当前分类级别为空，取最近的上级分类
    }

    if ($cat['grade'] > 1)
    {
        /* 需要价格分级 */

        /*
            算法思路：
                1、当分级大于1时，进行价格分级
                2、取出该类下商品价格的最大值、最小值
                3、根据商品价格的最大值来计算商品价格的分级数量级：
                        价格范围(不含最大值)    分级数量级
                        0-0.1                   0.001
                        0.1-1                   0.01
                        1-10                    0.1
                        10-100                  1
                        100-1000                10
                        1000-10000              100
                4、计算价格跨度：
                        取整((最大值-最小值) / (价格分级数) / 数量级) * 数量级
                5、根据价格跨度计算价格范围区间
                6、查询数据库

            可能存在问题：
                1、
                由于价格跨度是由最大值、最小值计算出来的
                然后再通过价格跨度来确定显示时的价格范围区间
                所以可能会存在价格分级数量不正确的问题
                该问题没有证明
                2、
                当价格=最大值时，分级会多出来，已被证明存在
        */

        $sql = "SELECT min(g.shop_price) AS min, max(g.shop_price) as max ".
               " FROM " . $ecs->table('goods'). " AS g ".
               " WHERE ($children OR " . get_extension_goods($children) . ') AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1  ';
               //获得当前分类下商品价格的最大值、最小值

        $row = $db->getRow($sql);

        // 取得价格分级最小单位级数，比如，千元商品最小以100为级数
        $price_grade = 0.0001;
        for($i=-2; $i<= log10($row['max']); $i++)
        {
            $price_grade *= 10;
        }

        //跨度
        $dx = ceil(($row['max'] - $row['min']) / ($cat['grade']) / $price_grade) * $price_grade;
        if($dx == 0)
        {
            $dx = $price_grade;
        }

        for($i = 1; $row['min'] > $dx * $i; $i ++);

        for($j = 1; $row['min'] > $dx * ($i-1) + $price_grade * $j; $j++);
        $row['min'] = $dx * ($i-1) + $price_grade * ($j - 1);

        for(; $row['max'] >= $dx * $i; $i ++);
        $row['max'] = $dx * ($i) + $price_grade * ($j - 1);

        $sql = "SELECT (FLOOR((g.shop_price - $row[min]) / $dx)) AS sn, COUNT(*) AS goods_num  ".
               " FROM " . $ecs->table('goods') . " AS g ".
               " WHERE ($children OR " . get_extension_goods($children) . ') AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 '.
               " GROUP BY sn ";

        $price_grade = $db->getAll($sql);

        foreach ($price_grade as $key=>$val)
        {
            $temp_key = $key + 1;
            $price_grade[$temp_key]['goods_num'] = $val['goods_num'];
            $price_grade[$temp_key]['start'] = $row['min'] + round($dx * $val['sn']);
            $price_grade[$temp_key]['end'] = $row['min'] + round($dx * ($val['sn'] + 1));
            $price_grade[$temp_key]['price_range'] = $price_grade[$temp_key]['start'] . '&nbsp;-&nbsp;' . $price_grade[$temp_key]['end'];
            $price_grade[$temp_key]['formated_start'] = price_format($price_grade[$temp_key]['start']);
            $price_grade[$temp_key]['formated_end'] = price_format($price_grade[$temp_key]['end']);
            $price_grade[$temp_key]['url'] = build_uri('category', array('cid'=>$cat_id, 'bid'=>$brand, 'price_min'=>$price_grade[$temp_key]['start'], 'price_max'=> $price_grade[$temp_key]['end'], 'filter_attr'=>$filter_attr_str), $cat['cat_name']);

            /* 判断价格区间是否被选中 */
            if (isset($_REQUEST['price_min']) && $price_grade[$temp_key]['start'] == $price_min && $price_grade[$temp_key]['end'] == $price_max)
            {
                $price_grade[$temp_key]['selected'] = 1;
            }
            else
            {
                $price_grade[$temp_key]['selected'] = 0;
            }
        }

        $price_grade[0]['start'] = 0;
        $price_grade[0]['end'] = 0;
        $price_grade[0]['price_range'] = $_LANG['all_attribute'];
        $price_grade[0]['url'] = build_uri('category', array('cid'=>$cat_id, 'bid'=>$brand, 'price_min'=>0, 'price_max'=> 0, 'filter_attr'=>$filter_attr_str), $cat['cat_name']);
        $price_grade[0]['selected'] = empty($price_max) ? 1 : 0;

        $smarty->assign('price_grade',     $price_grade);

    }


    /* 品牌筛选 */

    $sql = "SELECT b.brand_id, b.brand_name, COUNT(*) AS goods_num ".
            "FROM " . $GLOBALS['ecs']->table('brand') . "AS b, ".
                $GLOBALS['ecs']->table('goods') . " AS g LEFT JOIN ". $GLOBALS['ecs']->table('goods_cat') . " AS gc ON g.goods_id = gc.goods_id " .
            "WHERE g.brand_id = b.brand_id AND ($children OR " . 'gc.cat_id ' . db_create_in(array_unique(array_merge(array($cat_id), array_keys(cat_list($cat_id, 0, false))))) . ") AND b.is_show = 1 " .
            " AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 ".
            "GROUP BY b.brand_id HAVING goods_num > 0 ORDER BY b.sort_order, b.brand_id ASC";

   
    $brands = $GLOBALS['db']->getAll($sql);

    foreach ($brands AS $key => $val)
    {
        $temp_key = $key + 1;
        $brands[$temp_key]['brand_name'] = $val['brand_name'];
        $brands[$temp_key]['url'] = build_uri('category', array('cid' => $cat_id, 'bid' => $val['brand_id'], 'price_min'=>$price_min, 'price_max'=> $price_max, 'filter_attr'=>$filter_attr_str), $cat['cat_name']);

        /* 判断品牌是否被选中 */
        if ($brand == $brands[$key]['brand_id'])
        {
            $brands[$temp_key]['selected'] = 1;
        }
        else
        {
            $brands[$temp_key]['selected'] = 0;
        }
    }

    $brands[0]['brand_name'] = $_LANG['all_attribute'];
    $brands[0]['url'] = build_uri('category', array('cid' => $cat_id, 'bid' => 0, 'price_min'=>$price_min, 'price_max'=> $price_max, 'filter_attr'=>$filter_attr_str), $cat['cat_name']);
    $brands[0]['selected'] = empty($brand) ? 1 : 0;

    $smarty->assign('brands', $brands);


    /* 属性筛选 */
    $ext = ''; //商品查询条件扩展
    if ($cat['filter_attr'] > 0)
    {
        $cat_filter_attr = explode(',', $cat['filter_attr']);       //提取出此分类的筛选属性
        $all_attr_list = array();

        foreach ($cat_filter_attr AS $key => $value)
        {
            $sql = "SELECT a.attr_name FROM " . $ecs->table('attribute') . " AS a, " . $ecs->table('goods_attr') . " AS ga, " . $ecs->table('goods') . " AS g WHERE ($children OR " . get_extension_goods($children) . ") AND a.attr_id = ga.attr_id AND g.goods_id = ga.goods_id AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND a.attr_id='$value'";
            if($temp_name = $db->getOne($sql))
            {
                $all_attr_list[$key]['filter_attr_name'] = $temp_name;

                $sql = "SELECT a.attr_id, MIN(a.goods_attr_id ) AS goods_id, a.attr_value AS attr_value FROM " . $ecs->table('goods_attr') . " AS a, " . $ecs->table('goods') .
                       " AS g" .
                       " WHERE ($children OR " . get_extension_goods($children) . ') AND g.goods_id = a.goods_id AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 '.
                       " AND a.attr_id='$value' ".
                       " GROUP BY a.attr_value";

                
                $attr_list = $db->getAll($sql);

                $temp_arrt_url_arr = array();

                for ($i = 0; $i < count($cat_filter_attr); $i++)        //获取当前url中已选择属性的值，并保留在数组中
                {
                    $temp_arrt_url_arr[$i] = !empty($filter_attr[$i]) ? $filter_attr[$i] : 0;
                }

                $temp_arrt_url_arr[$key] = 0;                           //“全部”的信息生成
                $temp_arrt_url = implode('.', $temp_arrt_url_arr);
                $all_attr_list[$key]['attr_list'][0]['attr_value'] = $_LANG['all_attribute'];
                $all_attr_list[$key]['attr_list'][0]['url'] = build_uri('category', array('cid'=>$cat_id, 'bid'=>$brand, 'price_min'=>$price_min, 'price_max'=>$price_max, 'filter_attr'=>$temp_arrt_url), $cat['cat_name']);
                $all_attr_list[$key]['attr_list'][0]['selected'] = empty($filter_attr[$key]) ? 1 : 0;

                foreach ($attr_list as $k => $v)
                {
                    $temp_key = $k + 1;
                    $temp_arrt_url_arr[$key] = $v['goods_id'];       //为url中代表当前筛选属性的位置变量赋值,并生成以‘.’分隔的筛选属性字符串
                    $temp_arrt_url = implode('.', $temp_arrt_url_arr);

                    $all_attr_list[$key]['attr_list'][$temp_key]['attr_value'] = $v['attr_value'];
                    $all_attr_list[$key]['attr_list'][$temp_key]['url'] = build_uri('category', array('cid'=>$cat_id, 'bid'=>$brand, 'price_min'=>$price_min, 'price_max'=>$price_max, 'filter_attr'=>$temp_arrt_url), $cat['cat_name']);

                    if (!empty($filter_attr[$key]) AND $filter_attr[$key] == $v['goods_id'])
                    {
                        $all_attr_list[$key]['attr_list'][$temp_key]['selected'] = 1;
                    }
                    else
                    {
                        $all_attr_list[$key]['attr_list'][$temp_key]['selected'] = 0;
                    }
                }
            }

        }

        $smarty->assign('filter_attr_list',  $all_attr_list);
        /* 扩展商品查询条件 */
        if (!empty($filter_attr))
        {
            $ext_sql = "SELECT DISTINCT(b.goods_id) FROM " . $ecs->table('goods_attr') . " AS a, " . $ecs->table('goods_attr') . " AS b " .  "WHERE ";
            $ext_group_goods = array();

            foreach ($filter_attr AS $k => $v)                      // 查出符合所有筛选属性条件的商品id */
            {
                if (is_numeric($v) && $v !=0 &&isset($cat_filter_attr[$k]))
                {
                    $sql = $ext_sql . "b.attr_value = a.attr_value AND b.attr_id = " . $cat_filter_attr[$k] ." AND a.goods_attr_id = " . $v;
                    $ext_group_goods = $db->getColCached($sql);
                    $ext .= ' AND ' . db_create_in($ext_group_goods, 'g.goods_id');
                }
            }
        }
    }

    assign_template('c', array($cat_id));

    $position = assign_ur_here($cat_id, $brand_name);
    $smarty->assign('page_title',       $position['title']);    // 页面标题
    $smarty->assign('ur_here',          $position['ur_here']);  // 当前位置
    
    
    //add by yes123 2014-11-12  新模板当前位置
    $new_themes_ur_here = explode('<code>&gt;</code>',$position['ur_here']);
    $smarty->assign('new_themes_ur_here', $new_themes_ur_here[1]);  // 新模板的热门商品当前位置
    
	$smarty->assign('categories',   $categories); // 总分类树
    $smarty->assign('categorie',       get_categories_tree($cat_id)); // 分类树
    $smarty->assign('helps',            get_shop_help());              // 网店帮助
    $smarty->assign('top_goods',        get_top10());                  // 销售排行
    $smarty->assign('show_marketprice', $_CFG['show_marketprice']);
    $smarty->assign('category',         $cat_id);
    $smarty->assign('brand_id',         $brand);
    $smarty->assign('price_max',        $price_max);
    $smarty->assign('price_min',        $price_min);
    $smarty->assign('filter_attr',      $filter_attr_str);
    $smarty->assign('feed_url',         ($_CFG['rewrite'] == 1) ? "feed-c$cat_id.xml" : 'feed.php?cat=' . $cat_id); // RSS URL

    if ($brand > 0)
    {
        $arr['all'] = array('brand_id'  => 0,
                        'brand_name'    => $GLOBALS['_LANG']['all_goods'],
                        'brand_logo'    => '',
                        'goods_num'     => '',
                        'url'           => build_uri('category', array('cid'=>$cat_id), $cat['cat_name'])
                    );
    }
    else
    {
        $arr = array();
    }

    $brand_list = array_merge($arr, get_brands($cat_id, 'category'));

    $smarty->assign('data_dir',    DATA_DIR);
    $smarty->assign('brand_list',      $brand_list);
    //add yes123 2014-12-04 全部品牌列表
    $smarty->assign('brandlist',      getBrands()); 
    $smarty->assign('promotion_info', get_promotion_info());


    /* 调查 */
    $vote = get_vote();
    if (!empty($vote))
    {
        $smarty->assign('vote_id',     $vote['id']);
        $smarty->assign('vote',        $vote['content']);
    }

    $smarty->assign('best_goods',      get_category_recommend_goods('best', $children, $brand, $price_min, $price_max, $ext));
    $smarty->assign('promotion_goods', get_category_recommend_goods('promote', $children, $brand, $price_min, $price_max, $ext));
    $smarty->assign('hot_goods',       get_category_recommend_goods('hot', $children, $brand, $price_min, $price_max, $ext));

    if($goods_name_filter)//add by dingchaoyang 2014-12-12
    {
    	$children.=" AND ipa.attribute_name LIKE '%".$goods_name_filter."%'" ;
    }
    $count = get_cagtegory_goods_count($children, $brand, $price_min, $price_max, $ext);
    $max_page = ($count> 0) ? ceil($count / $size) : 1;
    if ($page > $max_page)
    {
        $page = $max_page;
    }
    
    //comment by wangcya , 根据分类找到产品列表，
    $goodslist = category_get_goods($children, $brand, $price_min, $price_max, $ext, $size, $page, $sort, $order,$good_id_);
    
    //add yes123 2014-11-14 上架时间排序用的 start
 	if($order=='asc')
	{
		$smarty->assign('add_time_order',       "desc"); 
		
	}
	else
	{
		$smarty->assign('add_time_order',       "asc"); 
	}
    
    //add yes123 2014-11-14 上架时间排序用的 end
    
    if($display == 'grid')
    {
        if(count($goodslist) % 2 != 0)
        {
            $goodslist[] = array();
        }
    }
    //echo "<pre>";print_r($goodslist);exit;
    $smarty->assign('goods_list',       $goodslist);
    $smarty->assign('category',         $cat_id);
    $smarty->assign('action',         'id='.$cat_id); //add yes123 2014-11-14 上架时间排序用的
    $smarty->assign('script_name', 'category');

    assign_pager('category',            $cat_id, $count, $size, $sort, $order, $page, '', $brand, $price_min, $price_max, $display, $filter_attr_str); // 分页
    assign_dynamic('category'); // 动态内容
}
$properties = get_goods_properties();  // 获得商品的规格和属性

$smarty->display('category.dwt', $cache_id);
/*------------------------------------------------------ */
//-- PRIVATE FUNCTION
/*------------------------------------------------------ */

/**
 * 获得分类的信息
 *
 * @param   integer $cat_id
 *
 * @return  void
 */
function get_cat_info($cat_id)
{
    return $GLOBALS['db']->getRow('SELECT cat_name, keywords, cat_desc, style, grade, filter_attr, parent_id FROM ' . $GLOBALS['ecs']->table('category') .
        " WHERE cat_id = '$cat_id'");
}

/**
 * 获得分类下的商品
 *
 * @access  public
 * @param   string  $children
 * @return  array
 */
function category_get_goods($children, $brand, $min, $max, $ext, $size, $page, $sort, $order,$goodID=0)//add $goodID by dingchaoyang 2014-12-10
{
    $display = $GLOBALS['display'];

	if($children){
		//modify yes123 2014-12-11 不需要找 商品扩展分类下的商品
    	//$where = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND ($children OR " . get_extension_goods($children) . ')';
    	$where = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND $children ";
    }else{ //否则不通过分类查询,也就是查询所有产品
    	$where = "g.is_on_sale = 1 AND g.is_alone_sale = 1 ";
    }

	$brand= $_REQUEST['brand_id'];
    if ($brand > 0)
    {
        $where .=  "AND g.brand_id=$brand ";
    }

    if ($min > 0)
    {
        $where .= " AND g.shop_price >= $min ";
    }

    if ($max > 0)
    {
        $where .= "  AND g.shop_price <= $max ";
    }
	
   $user_id = $_SESSION['user_id']==null?"0":$_SESSION['user_id'];
   //add by dingchaoyang 2014-12-10
   if ($goodID == 0){
		$goodIDFilter = "";		
   }else{
   		$goodIDFilter = " and g.goods_id = " . $goodID . " ";
   }
   	//end by dingchaoyang 2014-12-10  
	
    /* 获得商品列表 */  //2014-11-11 yes123 添加查询字段 ipa.apply_scope; add ipa.start_day by dingchaoyang 2014-11-21
    $sql = 'SELECT ipa.insurer_code,ipa.is_show_in_app, ipa.start_day, ipa.product_characteristic,ipa.insurer_id,ipa.rate_myself, ipa.description AS in_description , ipa.attribute_name AS in_attribute_name ,ipa.attribute_id ,ipa.apply_scope,
                  g.goods_id, g.goods_name, g.goods_name_style, g.market_price, g.is_new, g.is_best, g.is_hot, g.shop_price AS org_price, ' .
                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, g.promote_price, g.goods_type, " .
                'g.promote_start_date, g.promote_end_date, g.goods_brief, g.goods_thumb , g.goods_img ,cg.rec_id AS is_collect,' . //add yes123 is_collect是否收藏,有值就是收藏,否则反之
                ' COUNT(og.goods_id ) AS og_total,g.cat_id '. //add g.cat_id by dingchaoyang 2014-12-10
            'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
             ' LEFT JOIN ' . $GLOBALS['ecs']->table('order_goods') . ' AS og ON g.goods_id = og.goods_id  ' .
             ' LEFT JOIN ' . $GLOBALS['ecs']->table('collect_goods') . ' AS cg ON g.goods_id = cg.goods_id AND  cg.user_id= '.$user_id .//add yes123 是否收藏
             ' LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp ' .
                "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' " .
            " INNER JOIN t_insurance_product_attribute AS ipa ON ipa.attribute_id = g.tid  " . 
            " WHERE $where $ext $goodIDFilter GROUP BY og.goods_id ORDER BY $sort $order";
	
	ss_log($sql);
    $res = $GLOBALS['db']->selectLimit($sql, $size, ($page - 1) * $size);
    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        if ($row['promote_price'] > 0)
        {
            $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
        }
        else
        {
            $promote_price = 0;
        }

        /* 处理商品水印图片 */
        $watermark_img = '';

        if ($promote_price != 0)
        {
            $watermark_img = "watermark_promote_small";
        }
        elseif ($row['is_new'] != 0)
        {
            $watermark_img = "watermark_new_small";
        }
        elseif ($row['is_best'] != 0)
        {
            $watermark_img = "watermark_best_small";
        }
        elseif ($row['is_hot'] != 0)
        {
            $watermark_img = 'watermark_hot_small';
        }

        if ($watermark_img != '')
        {
            $arr[$row['goods_id']]['watermark_img'] =  $watermark_img;
        }

        $arr[$row['goods_id']]['goods_id']         = $row['goods_id'];
        if($display == 'grid')
        {
            $arr[$row['goods_id']]['goods_name']       = $GLOBALS['_CFG']['goods_name_length'] > 0 ? sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
        }
        else
        {
            $arr[$row['goods_id']]['goods_name']       = $row['goods_name'];
        }
        
        //start add by wangcya , 20140802
        $row['goods_name'] = $row['in_attribute_name'];//add by wangcya , 20140802

        $arr[$row['goods_id']]['goods_name']       = $row['in_attribute_name'];
        $arr[$row['goods_id']]['apply_scope']       = $row['apply_scope']; //add by yes123 2014-11-11
        $arr[$row['goods_id']]['goods_brief']      = $row['in_description'];
        $arr[$row['goods_id']]['start_day']      = $row['start_day'];//add by dingchaoyang 2014-11-21
        $arr[$row['goods_id']]['insurer_code']      = $row['insurer_code'];//add by dingchaoyang 2015-1-10
        $arr[$row['goods_id']]['is_show_in_app']      = $row['is_show_in_app'];//add by dingchaoyang 2014-12-8
        $arr[$row['goods_id']]['cat_id']      = $row['cat_id'];//add by dingchaoyang 2014-12-10
        $arr[$row['goods_id']]['product_characteristic']      = $row['product_characteristic'];//add by wangcya , 20140913
        
        //add yes123 2014-12-11  添加销量
        $arr[$row['goods_id']]['og_total']      = $row['og_total'];//add by wangcya , 20140913
        
        
        ///////得到该产品属性下面的产品列表
        $arr[$row['goods_id']]['ins_product_list'] = get_ins_product_list_by_attribute($row['attribute_id']);
        
        //end add by wangcya , 20140802
        
        $arr[$row['goods_id']]['ins_product_list_select'] = $arr[$row['goods_id']]['ins_product_list'][0];//add by wangcya , 20140914

        //$arr[$row['goods_id']]['goods_brief']      = $row['goods_brief'];
        
       
         $arr[$row['goods_id']]['name']             = $row['goods_name'];

      
		
        
        $arr[$row['goods_id']]['goods_style_name'] = add_style($row['goods_name'],$row['goods_name_style']);
      
        $arr[$row['goods_id']]['market_price']     = price_format($row['market_price']);
        $arr[$row['goods_id']]['shop_price']       = price_format($row['shop_price']);
        //add start  2014-10-04 yes123  热门商品显示的必要属性
        $insurance_company =insurer_name_logoByinsurer_Id($row['insurer_id']);
        $arr[$row['goods_id']]['insurer_name']     = $insurance_company['insurer_name'];
        $arr[$row['goods_id']]['logo']     		   = $insurance_company['logo'];
        //end  
 		
 		//add by yes123 2014-11-18 去掉小数后面多余的0      
        include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
        $arr[$row['goods_id']]['rate_myself']  	   = CommonUtils::decimal_zero_suppression($row['rate_myself']); 
   		
   		//add yes123 是否收藏
   		$arr[$row['goods_id']]['is_collect']             = $row['is_collect'];
        
        $arr[$row['goods_id']]['type']             = $row['goods_type'];
        $arr[$row['goods_id']]['promote_price']    = ($promote_price > 0) ? price_format($promote_price) : '';
        $arr[$row['goods_id']]['goods_thumb']      = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $arr[$row['goods_id']]['goods_img']        = get_image_path($row['goods_id'], $row['goods_img']);
        $arr[$row['goods_id']]['url']              = build_uri('goods', array('gid'=>$row['goods_id']), $row['goods_name']);
	
    }

    return $arr;
}


/**
 * add  2014-10-04
 * yes123
 * 通过保险公司ID获取，保险公司名称和logo
 */
function insurer_name_logoByinsurer_Id($insurer_id){
	$sql="SELECT insurer_name,substring(logo,4) logo FROM t_insurance_company WHERE insurer_id = ".$insurer_id;
	return  $GLOBALS['db']->getRow($sql);
	
}

/**
 * add  2014-10-04
 * yes123
 * 获取FAQ列表
 */
function get_faq_list(){
	$sql="SELECT *FROM ". $GLOBALS['ecs']->table('article') . " WHERE cat_id=(SELECT cat_id FROM ". $GLOBALS['ecs']->table('article_cat') . " WHERE cat_name='常见问题')";
	$r =$GLOBALS['db']->getAll($sql);
	return  $r;
} 

/**
 * 获得分类下的商品总数
 *
 * @access  public
 * @param   string     $cat_id
 * @return  integer
 */
function get_cagtegory_goods_count($children, $brand = 0, $min = 0, $max = 0, $ext='')
{
   
    if($children){
    	//modify yes123 2014-12-11 不需要找 商品扩展分类下的商品
    	//$where = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND ($children OR " . get_extension_goods($children) . ')';
    	$where = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND $children ";
    }else{ //否则不通过分类查询,也就是查询所有产品
    	$where = "g.is_on_sale = 1 AND g.is_alone_sale = 1 ";
    }
   
   
	$brand= $_REQUEST['brand_id'];
    if ($brand > 0)
    {
        $where .=  " AND g.brand_id = $brand ";
    }

    if ($min > 0)
    {
        $where .= " AND g.shop_price >= $min ";
    }

    if ($max > 0)
    {
        $where .= " AND g.shop_price <= $max ";
    }
    
    //modify yes123 2014-12-11 修改获取商品总数
    $sql = 'SELECT COUNT(*)'.
            'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
             ' LEFT JOIN ' . $GLOBALS['ecs']->table('order_goods') . ' AS og ON g.goods_id = og.goods_id  ' .
            " INNER JOIN t_insurance_product_attribute AS ipa ON ipa.attribute_id = g.tid  " . 
            " WHERE $where $ext  GROUP BY og.goods_id ";
    
	$count = count($GLOBALS['db']->getAll($sql));
    /* 返回商品总数 */
   return $count;

}

/**
 * 取得最近的上级分类的grade值
 *
 * @access  public
 * @param   int     $cat_id    //当前的cat_id
 *
 * @return int
 */
function get_parent_grade($cat_id)
{
    static $res = NULL;

    if ($res === NULL)
    {
        $data = read_static_cache('cat_parent_grade');
        if ($data === false)
        {
            $sql = "SELECT parent_id, cat_id, grade ".
                   " FROM " . $GLOBALS['ecs']->table('category');
            $res = $GLOBALS['db']->getAll($sql);
            write_static_cache('cat_parent_grade', $res);
        }
        else
        {
            $res = $data;
        }
    }

    if (!$res)
    {
        return 0;
    }

    $parent_arr = array();
    $grade_arr = array();

    foreach ($res as $val)
    {
        $parent_arr[$val['cat_id']] = $val['parent_id'];
        $grade_arr[$val['cat_id']] = $val['grade'];
    }

    while ($parent_arr[$cat_id] >0 && $grade_arr[$cat_id] == 0)
    {
        $cat_id = $parent_arr[$cat_id];
    }

    return $grade_arr[$cat_id];

}

/**
 * 
 * add yes123 2014-11-13 专题
 * start 
 */

function get_topic_list($page,$size,$parent_id=0){
	
	$sql = "SELECT * FROM " .$GLOBALS['ecs']->table('topic')." WHERE parent_id=".$parent_id. " ORDER BY add_time DESC ";
	
	$res = $GLOBALS['db']->selectLimit($sql, $size, ($page - 1) * $size);
	
	$arr = array();
	while ($row = $GLOBALS['db']->fetchRow($res))
	{
		$arr[$row['topic_id']]['topic_id']       = $row['topic_id'];
		$arr[$row['topic_id']]['title']       = $row['title'];
        $arr[$row['topic_id']]['description']       = $row['description']; 
        $arr[$row['topic_id']]['topic_img']      = $row['topic_img'];
        $arr[$row['topic_id']]['parent_id']      = $row['parent_id'];
        $arr[$row['topic_id']]['add_time'] = date('m月d日 H:i',$row['add_time']);
		
	}
	
	return $arr;
}

function get_topic_count($parent_id=0){
	$sql = "SELECT count(*) FROM " .$GLOBALS['ecs']->table('topic')." WHERE parent_id=".$parent_id;
	return $GLOBALS['db']->getOne($sql);
}

//add by yes123  2014-11-20 通过主题ID获取主题
function get_topic_by_id($topic_id){
	$sql = "SELECT * FROM " .$GLOBALS['ecs']->table('topic')." WHERE topic_id=".$topic_id;
	return $GLOBALS['db']->getRow($sql);
}


/**
 * 
 * add yes123 2014-11-13 专题
 * end 
 */


/**
 * 取得品牌列表
 * @return array 品牌列表 id => name
 */
function getBrands()
{
    $sql = 'SELECT brand_id, brand_name FROM ' . $GLOBALS['ecs']->table('brand') . ' WHERE is_show=1 ORDER BY sort_order  ';
    $res = $GLOBALS['db']->getAll($sql);
    return $res;
}

?>
