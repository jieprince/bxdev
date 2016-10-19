<?php

/**
 * ECSHOP 文章内容
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: article.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}


/*------------------------------------------------------ */
//-- INPUT
/*------------------------------------------------------ */


$_REQUEST['id'] = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$action = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : '';
$smarty->assign('act',  $action);

$article_id  = $_REQUEST['id'];

/*
 * 获取文章详情
 * 
 * */
if($_REQUEST['act']=='article_detail')
{
	$article_type = isset($_REQUEST['article_type'])?trim($_REQUEST['article_type']):'';
	
	include_once(ROOT_PATH . 'includes/class/Article.class.php');
    $article_obj = new Article;
    
    if($article_type=='information')
	{	
		$cat_name='资讯中心';
		$template_name='information.dwt';
	}
	elseif($article_type=='faq')
	{
		$cat_name='商城常见问题';
		$template_name='faq.dwt';
	}
	elseif($article_type=='en_faq')
	{	
		$cat_name='常见问题';
		$template_name='en_faq.dwt';
	}
	elseif($article_type=='article')
	{	
		$cat_name='用户协议';
		$template_name='article.dwt';
	}
	elseif($article_type=='com_article')
	{	
		
		$captcha = intval($_CFG['captcha']);
	    if (($captcha & CAPTCHA_LOGIN) && (!($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && $_SESSION['login_fail'] > 2)) && gd_version() > 0)
	    {
	        $GLOBALS['smarty']->assign('enabled_captcha', 1);
	        $GLOBALS['smarty']->assign('rand', mt_rand());
	    }
		
		$cat_name='平台入口';
		$template_name='com_article.dwt';
	}
	$child_article_cat_list = $article_obj->getChildCat(0,$cat_name);
	
	$smarty->assign('child_article_cat_list',  $child_article_cat_list);
	
	assign_template('',null,'en_middle');
	$article = $GLOBALS['db']->getRow("SELECT * FROM " . $ecs->table('article') . " WHERE article_id = '$article_id'");
	$article['formated_add_time'] = date('Y-m-d',$article['add_time']);
	$smarty->assign('article',  $article);
		
	//echo "<pre>";print_r($article);exit;
		
	//更新点击数
	$GLOBALS['db']->query("UPDATE " . $ecs->table('article') . " SET click_num=click_num+1 WHERE article_id = '$article_id'");
	
	//获取分类
	$sql = "SELECT * FROM " . $ecs->table('article_cat') . " WHERE cat_id = '$article[cat_id] '";
	$article_cat = $GLOBALS['db']->getRow($sql);
	$smarty->assign('article_cat',  $article_cat);
	
	if($article_type=='en_faq')
	{
		//获取相关问题
		$correlation_article = $GLOBALS['db']->getAll("SELECT * FROM " . $ecs->table('article') . " WHERE cat_id = '$article_cat[cat_id]' AND article_id!='$article[article_id]' ORDER BY sort_order ASC,article_id DESC LIMIT 3");
		
		foreach ( $correlation_article as $key => $value ) 
		{
       		$correlation_article[$key]['formated_add_time'] = date('Y-m-d',$value['add_time']);
		}
		
		$smarty->assign('correlation_article',  $correlation_article);
	}
	
	
	
	$smarty->assign('is_top_article_list',  $article_obj->getIsTopArticle($cat_name,1,5));
	$smarty->assign('article_type',  $article_type);

	
	$smarty->display($template_name);	

}
else if ($_REQUEST['act'] =='show_merging_article')
{
	
	
	$article_type = isset($_REQUEST['article_type'])?trim($_REQUEST['article_type']):'';
	
	include_once(ROOT_PATH . 'includes/class/Article.class.php');
    $article_obj = new Article;
    
    if($article_type=='information')
	{	
		$cat_name='资讯中心';
		$template_name='information.dwt';
	}
	elseif($article_type=='faq')
	{
		$cat_name='商城常见问题';
		$template_name='faq.dwt';
	}
	elseif($article_type=='en_faq')
	{	
		$cat_name='常见问题';
		$template_name='en_faq.dwt';
	}
	$child_article_cat_list = $article_obj->getChildCat(0,$cat_name);
	
	$smarty->assign('child_article_cat_list',  $child_article_cat_list);
	
	assign_template('',null,'en_middle');

		
	//更新点击数
	$GLOBALS['db']->query("UPDATE " . $ecs->table('article') . " SET click_num=click_num+1 WHERE article_id = ".$article_id);
	
	$cat_id = isset($_REQUEST['cat_id'])?intval($_REQUEST['cat_id']):0;
	//获取分类
	$sql = "SELECT * FROM " . $ecs->table('article_cat') . " WHERE cat_id = ".$cat_id ;
	$article_cat = $GLOBALS['db']->getRow($sql);
	$smarty->assign('article_cat',  $article_cat);
	
	if($article_type=='en_faq')
	{
		//获取相关问题
		$correlation_article = $GLOBALS['db']->getAll("SELECT * FROM " . $ecs->table('article') . " WHERE cat_id = '$article_cat[cat_id]' AND article_id!='$article[article_id]' ORDER BY sort_order ASC,article_id DESC LIMIT 3");
		
		foreach ( $correlation_article as $key => $value ) 
		{
       		$correlation_article[$key]['formated_add_time'] = date('Y-m-d',$value['add_time']);
		}
		
		$smarty->assign('correlation_article',  $correlation_article);
	}
	
	
	
	$smarty->assign('is_top_article_list',  $article_obj->getIsTopArticle($cat_name,1,5));
	$smarty->assign('article_type',  $article_type);
	
	
	
	$id = isset($_REQUEST['id'])?intval($_REQUEST['id']):'';	
	$merging_display_article = $GLOBALS['db']->getRow("SELECT * FROM " . $ecs->table('merging_display_article') . " WHERE id = '$id'");
	
	if($merging_display_article)
	{
		$sql = "SELECT *FROM ".$ecs->table('article')." WHERE article_id IN($merging_display_article[article_ids]) ";
		$article_list = $GLOBALS['db']->getAll($sql);
		
		$smarty->assign('merging_display_article_list',  $article_list);
		
		$smarty->display($template_name);	
	}
	else
	{
		show_message("错误，请重试！");
		
	}
	
}

//通过文章id获取文章,,"ajax"  yes123 2014-11-11
elseif($_REQUEST['act']=='article_by_id')
{
	include_once(ROOT_PATH . 'baoxian/source/function_debug.php');
	 $data=array();
	 
	 $article = $GLOBALS['db']->getRow("SELECT * FROM " . $ecs->table('article') . " WHERE article_id = ".$article_id);
	 $article['add_time'] = local_date($GLOBALS['_CFG']['time_format'], $value['add_time']);
	 	
	 // 当前位置
	 $sql="SELECT cat_name,cat_id FROM " . $ecs->table('article_cat') . " WHERE cat_id = ".$article['cat_id'];
	 $article_cat = $GLOBALS['db']->getRow($sql);
	 if($article_cat['cat_name']==='关于我们')
	 {
	 	$current_place="<a href=category.php?act=about>关于我们</a>>>".$article['title'];
	 }else{
		 $current_place="<a href=category.php?act=about>关于我们</a>>>"."<a href=javascript:get_article_list(".$article_cat['cat_id'].");>".$article_cat['cat_name']."</a>>>".$article['title'];
	 }
	
	 // add yes123 2014-11-25 查找替换文章里的shop_name变量
	 $article['content'] =  str_replace("shop_name",$_CFG['shop_name'], $article['content']);
	 $data['article']=$article;
	 $data['current_place']=$current_place;
	 $json_str = json_encode($data);
	 die($json_str);
}


//首页的文章链接 yes123 2014-11-12
elseif($_REQUEST['act']=='index_article_by_id')
{
	 $click = isset($_REQUEST['click'])?$_REQUEST['click']:'';	
	 $article_list = get_article_cat_list($cat_tag);

	
	 
	 //通过文章ID获取具体文章
	 $article = get_article_by_id($article_id);
	 $article['add_time'] = local_date($GLOBALS['_CFG']['time_format'], $value['add_time']);
	 
	 // 当前位置
	 $sql="SELECT cat_name,cat_id FROM " . $ecs->table('article_cat') . " WHERE cat_id = ".$article['cat_id'];
	 $article_cat = $GLOBALS['db']->getRow($sql);
	 
	
	 
	 if($article_cat['cat_name']==='关于我们')
	 {
	 	$current_place="<a href=category.php?act=about>关于我们</a>>>".$article['title'];
	 }else{
		 $current_place="<a href=category.php?act=about>关于我们</a>>>"."<a href=javascript:get_article_list(".$article_cat['cat_id'].");>".$article_cat['cat_name']."</a>>>".$article['title'];
	 }
	 // add yes123 2014-11-25 查找替换文章里的shop_name变量
	 $article['content'] =  str_replace("shop_name",$_CFG['shop_name'], $article['content']);
	 $smarty->assign('article_list',  $article_list);
	 $smarty->assign('default_article',  $article);	
	 $smarty->assign('default_article',  $article);	
	 $smarty->assign('click',$click );
	 $smarty->assign('current_place',  $current_place);	
	 $smarty->display('about.dwt');
	 exit;
}

elseif ($_REQUEST['act'] == 'change_index_faq')
{

    include_once('includes/cls_json.php');
    
    $cat_id = isset($_REQUEST['cat_id'])?$_REQUEST['cat_id']:0;
    
    $sql = "SELECT * FROM ".$ecs->table('article_cat')." WHERE cat_id='$cat_id'";
    $current_article_cat = $GLOBALS['db']->getRow($sql);
    $smarty->assign('current_article_cat', $current_article_cat);
    
    
    $article_type = isset($_REQUEST['article_type'])?$_REQUEST['article_type']:'';
    if($cat_id)
    {
    	include_once(ROOT_PATH . 'includes/class/Article.class.php');
	    $article_obj = new Article;
		$res = $article_obj->getIndexFAQByCatId($cat_id);
		
		$smarty->assign('first_article', $res['first_article']);
		$smarty->assign('article_list', $res['article_list']);
		  
		if($article_type=='faq')
		{
	    	$result['content'] = $smarty->fetch('library/index_faq.lbi');
		}  
    	else
    	{
    		$result['content'] = $smarty->fetch('library/en_index_faq.lbi');
    	}
    }
                
            
    $json = new JSON();
    die($json->encode($result));
}

elseif($_REQUEST['act']=='settlement_service')
{
	$id = isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
	$sql="SELECT * FROM ".$GLOBALS['ecs']->table('article')." WHERE article_id=$id";
	$row = $GLOBALS['db']->getRow($sql);
	$smarty->assign('article', $row);
	
	
	
	include_once(ROOT_PATH . 'includes/class/Article.class.php');
    $article_obj = new Article;
    $smarty->assign('is_top_article_list',  $article_obj->getIsTopArticle('商城常见问题',1,3));
		
			//获取分类树
	$categorie = get_categories_tree(0);
	$smarty->assign('categorie',       $categorie); // 分类树
	
	assign_template();
	$smarty->display('settlement_service.dwt');
}

elseif($_REQUEST['act']=='services')
{
	assign_template('',null,'en_middle');
	$id = isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
	$nav_id = isset($_REQUEST['nav_id'])?intval($_REQUEST['nav_id']):0;
	
	
	if($nav_id)
	{
		$sql="SELECT name FROM " . $GLOBALS['ecs']->table('nav')." WHERE id='$nav_id'";
		$nav_name = $GLOBALS['db']->getOne($sql);
		
		$sql="SELECT * FROM " . $GLOBALS['ecs']->table('article')." WHERE title LIKE '%$nav_name%' ";
		$article = $GLOBALS['db']->getRow($sql);
		if($article)
		{
			$id = $article['article_id'];
		}
		
	}
	
	
	
	$cat_name = '企业服务';
	$article_cat_sql="SELECT cat_id FROM ". $GLOBALS['ecs']->table('article_cat') . " WHERE cat_name='$cat_name'";
	
	$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('article')." WHERE cat_id IN ($article_cat_sql) ORDER BY sort_order ASC,article_id DESC ";
	$article_list = $GLOBALS['db']->getAll($sql);
	
	if($id)
	{
		foreach ( $article_list as $key => $value ) {
       		if($id==$value['article_id'])
       		{
       			$smarty->assign('article',$value);
       			break;
       		}
		}
		
	}
	else
	{
		
		$smarty->assign('article',$article_list[0]);
	}
	
	
	$smarty->assign('article_list',$article_list);
	
	$smarty->display('services.dwt');
	
}
elseif($_REQUEST['act']=='about')
{
	assign_template('',null,'en_middle');
	$id = isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
	$nav_id = isset($_REQUEST['nav_id'])?intval($_REQUEST['nav_id']):0;
	
	
	if($nav_id)
	{
		$sql="SELECT name FROM " . $GLOBALS['ecs']->table('nav')." WHERE id='$nav_id'";
		$nav_name = $GLOBALS['db']->getOne($sql);
		
		$sql="SELECT * FROM " . $GLOBALS['ecs']->table('article')." WHERE title LIKE '%$nav_name%' ";
		$article = $GLOBALS['db']->getRow($sql);
		if($article)
		{
			$id = $article['article_id'];
		}
		
	}
	
	
	
	$cat_name = '关于我们';
	$article_cat_sql="SELECT cat_id FROM ". $GLOBALS['ecs']->table('article_cat') . " WHERE cat_name='$cat_name'";
	
	$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('article')." WHERE cat_id IN ($article_cat_sql) ORDER BY sort_order ASC,article_id DESC ";
	$article_list = $GLOBALS['db']->getAll($sql);
	
	if($id)
	{
		foreach ( $article_list as $key => $value ) {
       		if($id==$value['article_id'])
       		{
       			$smarty->assign('article',$value);
       			break;
       		}
		}
		
	}
	else
	{
		
		$smarty->assign('article',$article_list[0]);
	}
	
	
	$smarty->assign('article_list',$article_list);
	
	$smarty->display('about.dwt');
	
}
elseif($_REQUEST['act']=='search_article')
{
	assign_template('',null,'en_middle');
	$title = isset($_REQUEST['title'])?trim($_REQUEST['title']):'';
	
	$where= " WHERE title LIKE '%$title%' ";
	include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
	$sql="SELECT cat_id FROM ".$GLOBALS['ecs']->table('article_cat')." WHERE cat_name='保险产品'";
	$top_cat_id = $GLOBALS['db']->getOne($sql);
	if($top_cat_id)
	{
		$sql="SELECT cat_id FROM ".$GLOBALS['ecs']->table('article_cat')." WHERE parent_id='$top_cat_id'";
		$child_cat_id_list = $GLOBALS['db']->getAll($sql);
		
		if($child_cat_id_list)
		{
			$child_cat_id_str = CommonUtils::arrToStr($child_cat_id_list,"cat_id");
			$child_cat_id_str.=$child_cat_id_str.",$top_cat_id";
			$where.=" AND  cat_id IN($child_cat_id_str)";
			
		}
		else
		{
			$where.="  AND cat_id IN($top_cat_id)";
		}
	  
	}
	

	if($title)
	{
		$sql="SELECT * FROM " . $GLOBALS['ecs']->table('article')." $where ORDER BY article_id DESC";
   		$article_list = $GLOBALS['db']->getAll($sql);
   		$smarty->assign('search_title',$title);
   		$smarty->assign('article_list',$article_list);
   		
   		$smarty->display('search_result.dwt');
	}
}
elseif($_REQUEST['act']=='product_article')
{
	
	assign_template('',null,'en_middle');
	
	
	$id = isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
	$title = isset($_REQUEST['title'])?trim($_REQUEST['title']):'';
	//保险产品的分类
	$cat_name="保险产品";
	$sql="SELECT cat_id FROM " . $GLOBALS['ecs']->table('article_cat')." WHERE cat_name='$cat_name'";
	$sql="SELECT cat_name,cat_id FROM " . $GLOBALS['ecs']->table('article_cat')." WHERE parent_id=($sql) ORDER BY sort_order ASC,cat_id DESC ";
	$article_cat_list = $GLOBALS['db']->getAll($sql);
	
	if(!empty($article_cat_list))
	{
		foreach ( $article_cat_list as $key => $value ) {
       		
       		$sql="SELECT article_id,title,link FROM " . $GLOBALS['ecs']->table('article')." WHERE cat_id='$value[cat_id]' ORDER BY sort_order ASC,article_id DESC";
       		$article_title_list = $GLOBALS['db']->getAll($sql);
       		foreach ( $article_title_list as $key2 => $article ) {
       			if($article['link']=='http://' || $article['link']=='http:///')
       			{
       				$article_title_list[$key2]['link']='';
       				
       			}
			}
       		
       		$article_cat_list[$key]['article_title_list'] = $article_title_list;
		}
		
	}
	
	if($title)
	{
	    $sql="SELECT article_id FROM " . $GLOBALS['ecs']->table('article')." WHERE title = '$title' ";
   		$article_id = $GLOBALS['db']->getOne($sql);
		if($article_id)
		{
			$id = $article_id;
		}
		else
		{
			$sql="SELECT article_id FROM " . $GLOBALS['ecs']->table('article')." WHERE title LIKE '%$title%' ";
   			$article_id = $GLOBALS['db']->getOne($sql);
			if($article_id)
			{
				$id=$article_id;
			}
			else
			{
				$id=-100;
				
			}
		}
	}
	
	
	if(!$id)
	{
		$id = $article_cat_list[0]['article_title_list'][0]['article_id'];
	}
	
	
	
	
	if($id)
	{
		//获取当前文章
		$sql="SELECT * FROM " . $GLOBALS['ecs']->table('article')." WHERE article_id='$id'";
		$article = $GLOBALS['db']->getRow($sql);
		$article['formated_add_time'] = date('Y-m-d',$article['add_time']);
		
		//获取上级分类名称
		$sql="SELECT cat_name FROM " . $GLOBALS['ecs']->table('article_cat')." WHERE cat_id='$article[cat_id]'";
		$cat_name = $GLOBALS['db']->getOne($sql);
		$article['cat_name'] = $cat_name;
		$smarty->assign('article',$article);
		//更新浏览次数
		$sql = " UPDATE ". $GLOBALS['ecs']->table('article')." SET click_num=click_num+1 WHERE article_id='$id'";
		$cat_name = $GLOBALS['db']->query($sql);	
	}
	
	
	$smarty->assign('article_cat_list',$article_cat_list);
	
	$smarty->display('product_article.dwt');
}
elseif($_REQUEST['act']=='special_topic')
{
	$captcha = intval($_CFG['captcha']);
    if (($captcha & CAPTCHA_LOGIN) && (!($captcha & CAPTCHA_LOGIN_FAIL) || (($captcha & CAPTCHA_LOGIN_FAIL) && $_SESSION['login_fail'] > 2)) && gd_version() > 0)
    {
        $GLOBALS['smarty']->assign('enabled_captcha', 1);
        $GLOBALS['smarty']->assign('rand', mt_rand());
    }
	
	assign_template();
	$id = isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
	
	$sql="SELECT * FROM " . $GLOBALS['ecs']->table('article')." WHERE article_id='$id' AND is_open=1 ";
	$article = $GLOBALS['db']->getRow($sql);
	
	if($article)
	{
		$smarty->assign('article',$article);
		$smarty->display('special_topic.dwt');
	}
	else
	{
		$smarty->assign('article',$article);
		$smarty->display('special_topic.dwt');
	}
}

/*------------------------------------------------------ */
//-- PRIVATE FUNCTION
/*------------------------------------------------------ */

/**
 * 获得指定的文章的详细信息
 *
 * @access  private
 * @param   integer     $article_id
 * @return  array
 */
function get_article_info($article_id)
{
    /* 获得文章的信息 */
    $sql = "SELECT a.*, IFNULL(AVG(r.comment_rank), 0) AS comment_rank ".
            "FROM " .$GLOBALS['ecs']->table('article'). " AS a ".
            "LEFT JOIN " .$GLOBALS['ecs']->table('comment'). " AS r ON r.id_value = a.article_id AND comment_type = 1 ".
            "WHERE a.is_open = 1 AND a.article_id = '$article_id' GROUP BY a.article_id";
    $row = $GLOBALS['db']->getRow($sql);

    if ($row !== false)
    {
        $row['comment_rank'] = ceil($row['comment_rank']);                              // 用户评论级别取整
        $row['add_time']     = local_date($GLOBALS['_CFG']['date_format'], $row['add_time']); // 修正添加时间显示

        /* 作者信息如果为空，则用网站名称替换 */
        if (empty($row['author']) || $row['author'] == '_SHOPHELP')
        {
            $row['author'] = $GLOBALS['_CFG']['shop_name'];
        }
    }

    return $row;
}

/**
 * 获得文章关联的商品
 *
 * @access  public
 * @param   integer $id
 * @return  array
 */
function article_related_goods($id)
{
    $sql = 'SELECT g.goods_id, g.goods_name, g.goods_thumb, g.goods_img, g.shop_price AS org_price, ' .
                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, ".
                'g.market_price, g.promote_price, g.promote_start_date, g.promote_end_date ' .
            'FROM ' . $GLOBALS['ecs']->table('goods_article') . ' ga ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON g.goods_id = ga.goods_id ' .
            "LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp ".
                    "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' ".
            "WHERE ga.article_id = '$id' AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0";
    $res = $GLOBALS['db']->query($sql);

    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $arr[$row['goods_id']]['goods_id']      = $row['goods_id'];
        $arr[$row['goods_id']]['goods_name']    = $row['goods_name'];
        $arr[$row['goods_id']]['short_name']   = $GLOBALS['_CFG']['goods_name_length'] > 0 ?
            sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
        $arr[$row['goods_id']]['goods_thumb']   = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $arr[$row['goods_id']]['goods_img']     = get_image_path($row['goods_id'], $row['goods_img']);
        $arr[$row['goods_id']]['market_price']  = price_format($row['market_price']);
        $arr[$row['goods_id']]['shop_price']    = price_format($row['shop_price']);
        $arr[$row['goods_id']]['url']           = build_uri('goods', array('gid' => $row['goods_id']), $row['goods_name']);

        if ($row['promote_price'] > 0)
        {
            $arr[$row['goods_id']]['promote_price'] = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
            $arr[$row['goods_id']]['formated_promote_price'] = price_format($arr[$row['goods_id']]['promote_price']);
        }
        else
        {
            $arr[$row['goods_id']]['promote_price'] = 0;
        }
    }

    return $arr;
}


?>