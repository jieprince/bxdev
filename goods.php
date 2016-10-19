<?php

/**
 * ECSHOP 商品详情
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: goods.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

include_once(ROOT_PATH. 'baoxian/common.php');


if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}

$affiliate = unserialize($GLOBALS['_CFG']['affiliate']);
$smarty->assign('affiliate', $affiliate);

/*------------------------------------------------------ */
//-- INPUT
/*------------------------------------------------------ */

$goods_id = isset($_REQUEST['id'])  ? intval($_REQUEST['id']) : 0;

//如果没有商品的id
if(!$goods_id)
{
	//但是直接有产品的id
	$ins_product_id = isset($_REQUEST['ins_product_id'])  ? intval($_REQUEST['ins_product_id']) : 0;//add by wangcya , 20140806,具体是选择的那个产品
	
	//根据某个具体的产品找到其属性，进而找到商品
	if($ins_product_id)
	{
		$sql = "SELECT attribute_id FROM t_insurance_product_base WHERE product_id='$ins_product_id'";
		$attribute_id = $db->getOne($sql);
		if($attribute_id)
		{
			$sql = "SELECT goods_id FROM bx_goods WHERE tid='$attribute_id'";
			//comment by zhangxi, 20141204,商品id，不是产品id
			$goods_id = $db->getOne($sql);
		}
	}	
}

/*------------------------------------------------------ */
//-- 改变属性、数量时重新计算商品价格
/*------------------------------------------------------ */

if (!empty($_REQUEST['act']) && $_REQUEST['act'] == 'price')
{
    include('includes/cls_json.php');

    $json   = new JSON;
    $res    = array('err_msg' => '', 'result' => '', 'qty' => 1);

    $attr_id    = isset($_REQUEST['attr']) ? explode(',', $_REQUEST['attr']) : array();
    $number     = (isset($_REQUEST['number'])) ? intval($_REQUEST['number']) : 1;

    if ($goods_id == 0)
    {
        $res['err_msg'] = $_LANG['err_change_attr'];
        $res['err_no']  = 1;
    }
    else
    {
        if ($number == 0)
        {
            $res['qty'] = $number = 1;
        }
        else
        {
            $res['qty'] = $number;
        }

        $shop_price  = get_final_price($goods_id, $number, true, $attr_id);
        $res['result'] = price_format($shop_price * $number);
    }

}


/*------------------------------------------------------ */
//-- 商品购买记录ajax处理
/*------------------------------------------------------ */

if (!empty($_REQUEST['act']) && $_REQUEST['act'] == 'gotopage')
{
    include('includes/cls_json.php');

    $json   = new JSON;
    $res    = array('err_msg' => '', 'result' => '');

    $goods_id   = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
    $page    = (isset($_REQUEST['page'])) ? intval($_REQUEST['page']) : 1;

    if (!empty($goods_id))
    {
        $need_cache = $GLOBALS['smarty']->caching;
        $need_compile = $GLOBALS['smarty']->force_compile;

        $GLOBALS['smarty']->caching = false;
        $GLOBALS['smarty']->force_compile = true;

        /* 商品购买记录 */
        $sql = 'SELECT u.user_name, og.goods_number, oi.add_time, IF(oi.order_status IN (2, 3, 4), 0, 1) AS order_status ' .
               'FROM ' . $ecs->table('order_info') . ' AS oi LEFT JOIN ' . $ecs->table('users') . ' AS u ON oi.user_id = u.user_id, ' . $ecs->table('order_goods') . ' AS og ' .
               'WHERE oi.order_id = og.order_id AND ' . time() . ' - oi.add_time < 2592000 AND og.goods_id = ' . $goods_id . ' ORDER BY oi.add_time DESC LIMIT ' . (($page > 1) ? ($page-1) : 0) * 5 . ',5';
        $bought_notes = $db->getAll($sql);

        foreach ($bought_notes as $key => $val)
        {
            $bought_notes[$key]['add_time'] = local_date("Y-m-d G:i:s", $val['add_time']);
        }

        $sql = 'SELECT count(*) ' .
               'FROM ' . $ecs->table('order_info') . ' AS oi LEFT JOIN ' . $ecs->table('users') . ' AS u ON oi.user_id = u.user_id, ' . $ecs->table('order_goods') . ' AS og ' .
               'WHERE oi.order_id = og.order_id AND ' . time() . ' - oi.add_time < 2592000 AND og.goods_id = ' . $goods_id;
        $count = $db->getOne($sql);


        /* 商品购买记录分页样式 */
        $pager = array();
        $pager['page']         = $page;
        $pager['size']         = $size = 5;
        $pager['record_count'] = $count;
        $pager['page_count']   = $page_count = ($count > 0) ? intval(ceil($count / $size)) : 1;;
        $pager['page_first']   = "javascript:gotoBuyPage(1,$goods_id)";
        $pager['page_prev']    = $page > 1 ? "javascript:gotoBuyPage(" .($page-1). ",$goods_id)" : 'javascript:;';
        $pager['page_next']    = $page < $page_count ? 'javascript:gotoBuyPage(' .($page + 1) . ",$goods_id)" : 'javascript:;';
        $pager['page_last']    = $page < $page_count ? 'javascript:gotoBuyPage(' .$page_count. ",$goods_id)"  : 'javascript:;';

        $smarty->assign('notes', $bought_notes);
        $smarty->assign('pager', $pager);


        $res['result'] = $GLOBALS['smarty']->fetch('library/bought_notes.lbi');

        $GLOBALS['smarty']->caching = $need_cache;
        $GLOBALS['smarty']->force_compile = $need_compile;
    }

    die($json->encode($res));
}


/*------------------------------------------------------ */
//-- PROCESSOR
/*------------------------------------------------------ */

$cache_id = $goods_id . '-' . $_SESSION['user_rank'].'-'.$_CFG['lang'];
$cache_id = sprintf('%X', crc32($cache_id));
if (!$smarty->is_cached('goods.dwt', $cache_id))
{
    $smarty->assign('image_width',  $_CFG['image_width']);
    $smarty->assign('image_height', $_CFG['image_height']);
    $smarty->assign('helps',        get_shop_help()); // 网店帮助
    $smarty->assign('id',           $goods_id);
    $smarty->assign('type',         0);
    $smarty->assign('cfg',          $_CFG);
    $smarty->assign('promotion',       get_promotion_info($goods_id));//促销信息
    $smarty->assign('promotion_info', get_promotion_info());

    /* 获得商品的信息 */
    $goods = get_goods_info($goods_id);
	
    //查询品牌
    if ($goods['brand_id'] > 0)
    {
    	$get_brand_sql = "SELECT * FROM ".$GLOBALS['ecs']->table('brand'). " WHERE brand_id=".$goods['brand_id'];
    	$goods_brand = $GLOBALS['db']->getRow($get_brand_sql);
    	$smarty->assign('goods_brand', $goods_brand);
    }
    
    
    if ($goods === false)
    {
        /* 如果没有找到任何记录则跳回到首页 */
        ecs_header("Location: ./\n");
        exit;
    }
    else
    {
        if ($goods['brand_id'] > 0)
        {
            $goods['goods_brand_url'] = build_uri('brand', array('bid'=>$goods['brand_id']), $goods['goods_brand']);
        }

        $shop_price   = $goods['shop_price'];
        $linked_goods = get_linked_goods($goods_id);

        $goods['goods_style_name'] = add_style($goods['goods_name'], $goods['goods_name_style']);

        /* 购买该商品可以得到多少钱的代金券 */
        if ($goods['bonus_type_id'] > 0)
        {
            $time = gmtime();
            $sql = "SELECT type_money FROM " . $ecs->table('bonus_type') .
                    " WHERE type_id = '$goods[bonus_type_id]' " .
                    " AND send_type = '" . SEND_BY_GOODS . "' " .
                    " AND send_start_date <= '$time'" .
                    " AND send_end_date >= '$time'";
            $goods['bonus_money'] = floatval($db->getOne($sql));
            if ($goods['bonus_money'] > 0)
            {
                $goods['bonus_money'] = price_format($goods['bonus_money']);
            }
        }
        //add yes123 2014-12-17 解析反斜杠
        $goods['product_characteristic'] =  stripslashes($goods['product_characteristic']);
		$goods['description'] =  stripslashes($goods['description']);
		$goods['limit_note'] =  stripslashes($goods['limit_note']);
		$goods['cover_note'] =  stripslashes($goods['cover_note']);
		$goods['claims_guide'] =  stripslashes($goods['claims_guide']);
		$goods['insurance_clauses'] =  stripslashes($goods['insurance_clauses']);
		$goods['insurance_declare'] =  stripslashes($goods['insurance_declare']);
		$goods['insurance_faq'] =  stripslashes($goods['insurance_faq']);
		
		
		//add by yes123 2014-11-18 去掉小数后面多余的0
		//include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
		//$goods['rate_myself']  	   = CommonUtils::decimal_zero_suppression($goods['rate_myself']);
		$goods['rate_myself'] = sprintf("%01.2f",$goods['rate_myself']);
        
        $smarty->assign('goods',              $goods);
        $smarty->assign('goods_id',           $goods['goods_id']);
        $smarty->assign('promote_end_time',   $goods['gmt_end_time']);
        $smarty->assign('categories',         get_categories_tree($goods['cat_id']));  // 分类树
        /* meta */
        $smarty->assign('keywords',           htmlspecialchars($goods['keywords']));
        $smarty->assign('description',        htmlspecialchars($goods['goods_brief']));


        $catlist = array();
        foreach(get_parent_cats($goods['cat_id']) as $k=>$v)
        {
            $catlist[] = $v['cat_id'];
        }

        assign_template('c', $catlist);

         /* 上一个商品下一个商品 */
        $prev_gid = $db->getOne("SELECT goods_id FROM " .$ecs->table('goods'). " WHERE cat_id=" . $goods['cat_id'] . " AND goods_id > " . $goods['goods_id'] . " AND is_on_sale = 1 AND is_alone_sale = 1 AND is_delete = 0 LIMIT 1");
        if (!empty($prev_gid))
        {
            $prev_good['url'] = build_uri('goods', array('gid' => $prev_gid), $goods['goods_name']);
            $smarty->assign('prev_good', $prev_good);//上一个商品
        }

        $next_gid = $db->getOne("SELECT max(goods_id) FROM " . $ecs->table('goods') . " WHERE cat_id=".$goods['cat_id']." AND goods_id < ".$goods['goods_id'] . " AND is_on_sale = 1 AND is_alone_sale = 1 AND is_delete = 0");
        if (!empty($next_gid))
        {
            $next_good['url'] = build_uri('goods', array('gid' => $next_gid), $goods['goods_name']);
            $smarty->assign('next_good', $next_good);//下一个商品
        }

        $position = assign_ur_here($goods['cat_id'], $goods['goods_name']);

        /* current position */
        $smarty->assign('page_title',          $position['title']);                    // 页面标题
        $smarty->assign('ur_here',             $position['ur_here']);                  // 当前位置

        $properties = get_goods_properties($goods_id);  // 获得商品的规格和属性
        $smarty->assign('properties',          $properties['pro']);                              // 商品属性
        $smarty->assign('specification',       $properties['spe']);                              // 商品规格
        $smarty->assign('attribute_linked',    get_same_attribute_goods($properties));           // 相同属性的关联商品
        $smarty->assign('related_goods',       $linked_goods);                                   // 关联商品
        $smarty->assign('goods_article_list',  get_linked_articles($goods_id));                  // 关联文章
        $smarty->assign('fittings',            get_goods_fittings(array($goods_id)));                   // 配件
        $smarty->assign('rank_prices',         get_user_rank_prices($goods_id, $shop_price));    // 会员等级价格
        $smarty->assign('pictures',            get_goods_gallery($goods_id));                    // 商品相册
        $smarty->assign('bought_goods',        get_also_bought($goods_id));                      // 购买了该商品的用户还购买了哪些商品
        $smarty->assign('goods_rank',          get_goods_rank($goods_id));                       // 商品的销售排名

        //获取tag
        $tag_array = get_tags($goods_id);
        $smarty->assign('tags',                $tag_array);                                       // 商品的标记

        //获取关联礼包
        $package_goods_list = get_package_goods_list($goods['goods_id']);
        $smarty->assign('package_goods_list',$package_goods_list);    // 获取关联礼包

        assign_dynamic('goods');
        $volume_price_list = get_volume_price_list($goods['goods_id'], '1');
        $smarty->assign('volume_price_list',$volume_price_list);    // 商品优惠价格区间
    }
}

/* 记录浏览历史 */
if (!empty($_COOKIE['ECS']['history']))
{
    $history = explode(',', $_COOKIE['ECS']['history']);

    array_unshift($history, $goods_id);
    $history = array_unique($history);

    while (count($history) > $_CFG['history_number'])
    {
        array_pop($history);
    }

    setcookie('ECS[history]', implode(',', $history), gmtime() + 3600 * 24 * 30);
}
else
{
    setcookie('ECS[history]', $goods_id, gmtime() + 3600 * 24 * 30);
}


/* 更新点击次数 */
$db->query('UPDATE ' . $ecs->table('goods') . " SET click_count = click_count + 1 WHERE goods_id = '$_REQUEST[id]'");

$smarty->assign('now_time',  gmtime());           // 当前系统时间
/***
 * 修改时间：2014/7/19
 * 修改者：鲍洪州
 * 功能：分配产品分类数据
 */

  $smarty->assign('categorie',   $categories);


 /*** 
 * 修改时间：2014/7/21
 * 修改者：鲍洪州
 * 功能：查询goods_brief字段值
 */
/*产品简介已经有了，不必再次查询了吧？ delete by wangcya , 20140802
 $sql="SELECT goods_brief FROM ".$ecs->table('goods')."WHERE goods_id=$goods_id";
 $goods_brief = $db->getOne($sql);

 $smarty->assign('goods_brief',$goods_brief);
  */

//start add by wangcya , 20140802, 得到该产品属性下面的产品信息列表
  
//add yes123 2015-07-28 判断能否购买此产品
if($_SESSION['user_id'])
{
	$flag = is_buy_goods($goods['tid'],$_SESSION['user_id']);
	if(!$flag)
	{
		$error_msg = "<script>alert('无法访问');history.back();</script>";
		die($error_msg);
	}
}


$insurer_code = $goods['insurer_code'];//这个属性字段来决定显示的方式。
$attribute_type = trim($goods['attribute_type']);//这个属性字段来决定显示的方式。
ss_log("insurer_code:".$insurer_code);
$smarty->assign('insurer_code',$insurer_code);
$smarty->assign('attribute_type',$attribute_type);

//echo "insurer_code: ".$insurer_code;
//mod by zhangxi, 20150708, 增加平安个财
//mod by zhangxi, 20150806, 增加平安大连代码PAC05
$INSURANCE_PAC = array("PAC","PAC01","PAC02","PAC03","PAC04","PAC05");
if(in_array($insurer_code, $INSURANCE_PAC))//add by wangcya, 20150506
{
	global $_SGLOBAL;
	$attribute_id = $goods['tid']; 
	$retattr = get_product_view_info($attribute_id,$attribute_type);
	
	$attr_sql="SELECT * FROM t_insurance_product_attribute 
				 WHERE attribute_id='$attribute_id'";
	$attr_query = $_SGLOBAL['db']->query($attr_sql);
	$attr_row = $_SGLOBAL['db']->fetch_array($attr_query);
	$attribute_code = $attr_row['attribute_code'];
	//added by zhangxi,  20141204, Y502产品显示页面，初次数据获取处理
	if('Y502' == $attribute_type)
	{
		//added by zhangxi , 20150806, 标准产品配置的Y502相关产品，还需要实际的Y502产品的险种id，
		//来查询数据
		if($attribute_code == 'Y502_A' || $attribute_code == 'Y502_B')
		{
			$attr1_sql="SELECT * FROM t_insurance_product_attribute 
				 		WHERE attribute_type='Y502' AND attribute_code='Y502'";
			$attr1_query = $_SGLOBAL['db']->query($attr1_sql);
			$attr1_row = $_SGLOBAL['db']->fetch_array($attr1_query);
			$Y502_attribute_id = $attr1_row['attribute_id'];
			$smarty->assign('Y502_attribute_id',$Y502_attribute_id);
		}
		
		//获取y502产品主险product_id
		$sql="SELECT product_id FROM t_insurance_product_base 
				WHERE  product_type='main' AND attribute_id='$attribute_id' ";

		$product_id = $GLOBALS['db']->getOne($sql);
	
		//得到产品周期列表，只获取主产品即可，副产品和主产品一样
		$sql="SELECT DISTINCT factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
				WHERE pi.product_influencingfactor_type='period' AND pi.product_id='$product_id' order by view_order";
		$query = $_SGLOBAL['db']->query($sql);

		//comment by zhangxi, 20141204, 一个险种下的多个产品列表,主险附加险的情况不适用
		$arr_peroid_list = array();
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$arr_period_list[] = $row;
		}
		//echo "<pre>";
		//echo $product_id;
		//var_dump($arr_period_list);
		//得到产品职业列表，只获取主产品即可
		$sql="SELECT DISTINCT factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
				WHERE pi.product_influencingfactor_type='career' AND pi.product_id='$product_id' order by view_order";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_career_list = array();
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$arr_career_list[] = $row;
		}
		//var_dump($retattr);
		$smarty->assign('arr_period_list',$arr_period_list);
		$smarty->assign('arr_career_list',$arr_career_list);
		$smarty->assign('attribute_id',$attribute_id);
		
	}
	//家财综合
	else if('jiacaizonghe' == $attribute_type
	|| 'jiajuzonghe' == $attribute_type
	|| 'zhanghuzijin' == $attribute_type)
	{
		global $_SGLOBAL;
		//
		$sql="SELECT product_id,product_code FROM t_insurance_product_base 
				WHERE  product_type='main' AND attribute_id='$attribute_id' ";
		$query = $_SGLOBAL['db']->query($sql);
		$main_product_id_list = array();
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			//echo $attribute_id;
			$product_id = $row['product_id'];
			//得到产品周期列表，只获取主产品即可，副产品和主产品一样
			$sql="SELECT DISTINCT factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
					WHERE pi.product_influencingfactor_type='period' AND pi.product_id='$product_id' order by view_order";
			$query_period = $_SGLOBAL['db']->query($sql);
			//comment by zhangxi, 20141204, 一个险种下的多个产品列表,主险附加险的情况不适用
			$arr_period_list = array();
			unset($arr_period_list);
			while ($row_period = $_SGLOBAL['db']->fetch_array($query_period))
			{
				$arr_period_list[] = $row_period;
			}
			$main_product_id_list[]=array("product_code"=>$row['product_code'],
											"period_list"=>$arr_period_list);
		}
	
		//echo "<pre>";
		//var_dump($main_product_id_list);
		$smarty->assign('main_product_id_list',$main_product_id_list);
		$smarty->assign('attribute_id',$attribute_id);
		
	}
	
	$arr_ins_product_list = $retattr['arr_ins_product_list'];
	$product_id = $retattr['product_id'];
	/////////////////////////////////////////////////////////////////////
	
	$ins_product_id = isset($_REQUEST['ins_product_id'])  ? intval($_REQUEST['ins_product_id']) : $product_id;//add by wangcya , 20140806,具体是选择的那个产品
	
	/////////////////////////////////////////////////////////////////////
	$smarty->assign('attribute_code',$attribute_code);
	$classattr = array($ins_product_id=>"current");
	
	$smarty->assign('classattr',$classattr);
	$smarty->assign('ins_product_list',$arr_ins_product_list);
	
	$ins_product_selected = $arr_ins_product_list[$ins_product_id];//选择哪个产品就显示那个产品信息
	$smarty->assign('ins_product_selected',$ins_product_selected);
	
}
elseif($insurer_code =="HTS")//华泰
{
	$attribute_id = $goods['tid'];
	$retattr = get_product_view_info($attribute_id,$attribute_type);
	
	$arr_ins_product_list = $retattr['arr_ins_product_list'];
	$product_id = $retattr['product_id'];
	/////////////////////////////////////////////////////////////////////
	
	$ins_product_id = isset($_REQUEST['ins_product_id'])  ? intval($_REQUEST['ins_product_id']) : $product_id;//add by wangcya , 20140806,具体是选择的那个产品
	
	/////////////////////////////////////////////////////////////////////
	$classattr = array($ins_product_id=>"current");
	
	$smarty->assign('classattr',$classattr);
	$smarty->assign('ins_product_list',$arr_ins_product_list);
	
	$ins_product_selected = $arr_ins_product_list[$ins_product_id];//选择哪个产品就显示那个产品信息
	$smarty->assign('ins_product_selected',$ins_product_selected);
	
}
elseif($insurer_code =="TBC01")//太平洋
{
        $attribute_id = $goods['tid']; 
	$retattr = get_product_view_info($attribute_id,$attribute_type);
	
	$arr_ins_product_list = $retattr['arr_ins_product_list'];
	$product_id = $retattr['product_id'];
	/////////////////////////////////////////////////////////////////////
	
	$ins_product_id = isset($_REQUEST['ins_product_id'])  ? intval($_REQUEST['ins_product_id']) : $product_id;//add by wangcya , 20140806,具体是选择的那个产品
	
	/////////////////////////////////////////////////////////////////////
	$classattr = array($ins_product_id=>"current");
	
	$smarty->assign('classattr',$classattr);
	$smarty->assign('ins_product_list',$arr_ins_product_list);
	
	$ins_product_selected = $arr_ins_product_list[$ins_product_id];//选择哪个产品就显示那个产品信息
        $ins_product_selected['premium'] = sprintf("%01.2f", $ins_product_selected['premium']);
	$smarty->assign('ins_product_selected',$ins_product_selected);
        
}
//added by zhangxi,20150619, 太平洋天津产险
elseif($insurer_code == $ARR_INS_COMPANY_NAME['str_cpic_tj_property'])//太平洋
{
	ss_log(__FILE__.", insurer_code=".$insurer_code);
    $attribute_id = $goods['tid']; 
	$retattr = get_product_view_info($attribute_id,$attribute_type);
	
	$arr_ins_product_list = $retattr['arr_ins_product_list'];
	$product_id = $retattr['product_id'];
	/////////////////////////////////////////////////////////////////////
	
	$ins_product_id = isset($_REQUEST['ins_product_id'])  ? intval($_REQUEST['ins_product_id']) : $product_id;//add by wangcya , 20140806,具体是选择的那个产品
	
	/////////////////////////////////////////////////////////////////////
	$classattr = array($ins_product_id=>"current");
	
	$smarty->assign('classattr',$classattr);
	$smarty->assign('ins_product_list',$arr_ins_product_list);
	
	$ins_product_selected = $arr_ins_product_list[$ins_product_id];//选择哪个产品就显示那个产品信息
        $ins_product_selected['premium'] = sprintf("%01.2f", $ins_product_selected['premium']);
	$smarty->assign('ins_product_selected',$ins_product_selected);
        
}
//added by zhangxi, 20150608, 增加太平洋货运险
elseif($insurer_code =="CPIC_CARGO")//太平洋
{
    $attribute_id = $goods['tid']; 
	$retattr = get_product_view_info($attribute_id,$attribute_type);
	
	$arr_ins_product_list = $retattr['arr_ins_product_list'];
	$product_id = $retattr['product_id'];
	/////////////////////////////////////////////////////////////////////
	
	$ins_product_id = isset($_REQUEST['ins_product_id'])  ? intval($_REQUEST['ins_product_id']) : $product_id;//add by wangcya , 20140806,具体是选择的那个产品
	
	/////////////////////////////////////////////////////////////////////
	$classattr = array($ins_product_id=>"current");
	
	$smarty->assign('classattr',$classattr);
	$smarty->assign('ins_product_list',$arr_ins_product_list);
	
	$ins_product_selected = $arr_ins_product_list[$ins_product_id];//选择哪个产品就显示那个产品信息
    $ins_product_selected['premium'] = sprintf("%01.2f", $ins_product_selected['premium']);
	$smarty->assign('ins_product_selected',$ins_product_selected);
        
}
//added by zhangxi, 20141222, for 华安保险
elseif( $insurer_code == "SINO" )
{
	 
	$attribute_id = $goods['tid']; 
	$retattr = get_product_view_info($attribute_id,$attribute_type);
	
	$arr_ins_product_list = $retattr['arr_ins_product_list'];
	$product_id = $retattr['product_id'];
	/////////////////////////////////////////////////////////////////////
	
	$ins_product_id = isset($_REQUEST['ins_product_id'])  ? intval($_REQUEST['ins_product_id']) : $product_id;//add by wangcya , 20140806,具体是选择的那个产品
	
	/////////////////////////////////////////////////////////////////////
	$classattr = array($ins_product_id=>"current");
	
	$smarty->assign('classattr',$classattr);
	$smarty->assign('ins_product_list',$arr_ins_product_list);
	
	$ins_product_selected = $arr_ins_product_list[$ins_product_id];//选择哪个产品就显示那个产品信息
	$smarty->assign('ins_product_selected',$ins_product_selected);
	//echo "<pre>";
	//var_dump($ins_product_selected);
	
	
	$ROOT_PATH_= str_replace ( 'goods.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
	include_once ($ROOT_PATH_. 'baoxian/source/function_baoxian_huaan.php');
	$arr_project_list = get_huaan_project_cost_peroid($product_id);
	$smarty->assign('arr_project_list',$arr_project_list);
	//echo "<pre>";
	//var_dump($arr_project_list);
	
}
//added by zhangxi, 20150318, 新华人寿产品
elseif( $insurer_code == "NCI" )
{
	$attribute_id = $goods['tid']; 
	$retattr = get_product_view_info($attribute_id,$attribute_type);
	
	$server_time = time();//second, $_SGLOBAL['timestamp'];
	ss_log("server_time: ".$server_time);
	$servertime_str = date("Y-m-d H:i:s",$server_time);
	ss_log("servertime_str: ".$servertime_str);

	//added by zhangxi,  
	if('shaoer' == $attribute_type)
	{
		global $_SGLOBAL;
		global $attr_xinhua_applyNum_insAmount;
		//获取 产品主险product_id
		$sql="SELECT product_id FROM t_insurance_product_base 
				WHERE  product_type='main' AND attribute_id='$attribute_id' ";
		//$product_query = $_SGLOBAL['db']->query($sql);
		//$row = $_SGLOBAL['db']->fetch_row($product_query);
		//$product_id = $row[0];
		$product_id = $GLOBALS['db']->getOne($sql);
	
		//得到产品缴费期限列表，只获取主产品即可，副产品和主产品一样
		$sql="SELECT DISTINCT factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
				WHERE pi.product_influencingfactor_type='period' AND pi.product_id='$product_id' order by view_order";
		$query = $_SGLOBAL['db']->query($sql);

		//comment by zhangxi, 20141204, 一个险种下的多个产品列表,主险附加险的情况不适用
		$arr_peroid_list = array();
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$arr_period_list[] = $row;//缴费期限列表
		}
		//echo "<pre>";
		//echo $product_id;
		//var_dump($arr_period_list);
		
		//得到产品性别列表，只获取主产品即可
		$sql="SELECT DISTINCT factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
				WHERE pi.product_influencingfactor_type='career' AND pi.product_id='$product_id' order by view_order";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_gender_list = array();
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$arr_gender_list[] = $row;
		}
		//echo "<pre>";
		//echo $product_id;
		//var_dump($arr_career_list);
		//echo "----------------------------------------------------";
		
		//得到产品保费影响因素中的被保险人年龄，只获取主产品即可,但是前端可能不用
		$sql="SELECT DISTINCT factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
				WHERE pi.product_influencingfactor_type='age' AND pi.product_id='$product_id' order by view_order";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_age_list = array();
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$arr_age_list[] = $row;
		}
		
		//var_dump($retattr);
		
		$smarty->assign('server_time',$server_time);
		$smarty->assign('attr_applyNum_insAmount',$attr_xinhua_applyNum_insAmount);
		$smarty->assign('arr_period_list',$arr_period_list);
		$smarty->assign('arr_gender_list',$arr_gender_list);
		$smarty->assign('arr_age_list',$arr_age_list);//前端可能不用
		$smarty->assign('attribute_id',$attribute_id);
		
	}
	
	$arr_ins_product_list = $retattr['arr_ins_product_list'];
	$product_id = $retattr['product_id'];
	/////////////////////////////////////////////////////////////////////
	
	$ins_product_id = isset($_REQUEST['ins_product_id'])  ? intval($_REQUEST['ins_product_id']) : $product_id;//add by wangcya , 20140806,具体是选择的那个产品
	
	/////////////////////////////////////////////////////////////////////
	$classattr = array($ins_product_id=>"current");
	
	$smarty->assign('classattr',$classattr);
	$smarty->assign('ins_product_list',$arr_ins_product_list);
	
	$ins_product_selected = $arr_ins_product_list[$ins_product_id];//选择哪个产品就显示那个产品信息
	$smarty->assign('ins_product_selected',$ins_product_selected);
	
}
elseif( $insurer_code == "CHINALIFE" )
{
	$attribute_id = $goods['tid']; 
	$retattr = get_product_view_info($attribute_id,$attribute_type);
	$arr_ins_product_list = $retattr['arr_ins_product_list'];
	$product_id = $retattr['product_id'];
	/////////////////////////////////////////////////////////////////////
	
	$ins_product_id = isset($_REQUEST['ins_product_id'])  ? intval($_REQUEST['ins_product_id']) : $product_id;//add by wangcya , 20140806,具体是选择的那个产品
	
	/////////////////////////////////////////////////////////////////////
	$classattr = array($ins_product_id=>"current");
	
	$smarty->assign('classattr',$classattr);
	$smarty->assign('ins_product_list',$arr_ins_product_list);
	
	$ins_product_selected = $arr_ins_product_list[$ins_product_id];//选择哪个产品就显示那个产品信息
	$smarty->assign('ins_product_selected',$ins_product_selected);
}
elseif( $insurer_code == "picclife" )
{
	$attribute_id = $goods['tid']; 
	$retattr = get_product_view_info($attribute_id,$attribute_type);
	
	$server_time = time();//second, $_SGLOBAL['timestamp'];
	ss_log("server_time: ".$server_time);
	$servertime_str = date("Y-m-d H:i:s",$server_time);
	ss_log("servertime_str: ".$servertime_str);
	
	//added by zhangxi,  20150515, i健康险种的处理
	if('ijiankang' == $attribute_type)
	{
		global $_SGLOBAL;
		global $attr_picclife_applyNum_insAmount;
		//获取 产品主险product_id
		$sql="SELECT product_id FROM t_insurance_product_base 
				WHERE  product_type='main' AND attribute_id='$attribute_id' ";
		//$product_query = $_SGLOBAL['db']->query($sql);
		//$row = $_SGLOBAL['db']->fetch_row($product_query);
		//$product_id = $row[0];
		$product_id = $GLOBALS['db']->getOne($sql);
	
		//得到产品缴费期限列表，只获取主产品即可，副产品和主产品一样
		$sql="SELECT DISTINCT factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
				WHERE pi.product_influencingfactor_type='period' AND pi.product_id='$product_id' order by view_order";
		$query = $_SGLOBAL['db']->query($sql);

		//comment by zhangxi, 20141204, 一个险种下的多个产品列表,主险附加险的情况不适用
		$arr_peroid_list = array();
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$arr_period_list[] = $row;//缴费期限列表
		}
		//echo "<pre>";
		//echo $product_id;
		//var_dump($arr_period_list);
		
		//得到产品性别列表，只获取主产品即可
		$sql="SELECT DISTINCT factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
				WHERE pi.product_influencingfactor_type='career' AND pi.product_id='$product_id' order by view_order";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_gender_list = array();
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$arr_gender_list[] = $row;
		}
		//echo "<pre>";
		//echo $product_id;
		//var_dump($arr_career_list);
		//echo "----------------------------------------------------";
		
		//得到产品保费影响因素中的被保险人年龄，只获取主产品即可,但是前端可能不用
		$sql="SELECT DISTINCT factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
				WHERE pi.product_influencingfactor_type='age' AND pi.product_id='$product_id' order by view_order";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_age_list = array();
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$arr_age_list[] = $row;
		}
		
		//var_dump($retattr);
		
		$smarty->assign('server_time',$server_time);
		$smarty->assign('attr_applyNum_insAmount',$attr_picclife_applyNum_insAmount);
		$smarty->assign('arr_period_list',$arr_period_list);
		$smarty->assign('arr_gender_list',$arr_gender_list);
		$smarty->assign('arr_age_list',$arr_age_list);//前端可能不用
		$smarty->assign('attribute_id',$attribute_id);
		
	}
	//added by zhangxi, 20150522,百万身价产品， 类似于平安的Y502
	elseif('BWSJ' == $attribute_type
	|| 'TTL' == $attribute_type
	|| 'KLWYLQX' == $attribute_type)
	{
		global $_SGLOBAL;
		//获取产品主险product_id
		$sql="SELECT product_id FROM t_insurance_product_base 
				WHERE  product_type='main' AND attribute_id='$attribute_id' ";
		$product_id = $GLOBALS['db']->getOne($sql);
	
		//得到产品缴费周期列表，只获取主产品即可，副产品和主产品一样
		$sql="SELECT DISTINCT factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
				WHERE pi.product_influencingfactor_type='period' AND pi.product_id='$product_id' order by view_order";
		$query = $_SGLOBAL['db']->query($sql);

		$arr_peroid_list = array();
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$arr_period_list[] = $row;
		}
		//echo "<pre>";
		//echo $product_id;
		//var_dump($arr_period_list);
		//得到产品购买年龄列表
		$sql="SELECT DISTINCT factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
				WHERE pi.product_influencingfactor_type='age' AND pi.product_id='$product_id' order by view_order";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_career_list = array();
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$arr_age_list[] = $row;
		}
		
		//得到产品性别列表，只获取主产品即可
		$sql="SELECT DISTINCT factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
				WHERE pi.product_influencingfactor_type='career' AND pi.product_id='$product_id' order by view_order";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_gender_list = array();
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$arr_gender_list[] = $row;
		}
		
		//var_dump($retattr);
		$smarty->assign('server_time',$server_time);
		$smarty->assign('arr_period_list',$arr_period_list);
		$smarty->assign('arr_gender_list',$arr_gender_list);
		$smarty->assign('arr_age_list',$arr_age_list);
		$smarty->assign('attribute_id',$attribute_id);
	}
	
	$arr_ins_product_list = $retattr['arr_ins_product_list'];
	$product_id = $retattr['product_id'];
	/////////////////////////////////////////////////////////////////////
	
	$ins_product_id = isset($_REQUEST['ins_product_id'])  ? intval($_REQUEST['ins_product_id']) : $product_id;//add by wangcya , 20140806,具体是选择的那个产品
	
	/////////////////////////////////////////////////////////////////////
	$classattr = array($ins_product_id=>"current");

	$smarty->assign('classattr',$classattr);
	$smarty->assign('ins_product_list',$arr_ins_product_list);
	
	$ins_product_selected = $arr_ins_product_list[$ins_product_id];//选择哪个产品就显示那个产品信息
	$smarty->assign('ins_product_selected',$ins_product_selected);
}
elseif( $insurer_code == "EPICC" )
{
	$attribute_id = $goods['tid']; 
	$retattr = get_product_view_info($attribute_id,$attribute_type);
	$arr_ins_product_list = $retattr['arr_ins_product_list'];
	$product_id = $retattr['product_id'];
	/////////////////////////////////////////////////////////////////////
	
	$ins_product_id = isset($_REQUEST['ins_product_id'])  ? intval($_REQUEST['ins_product_id']) : $product_id;//add by wangcya , 20140806,具体是选择的那个产品
	
	/////////////////////////////////////////////////////////////////////
	$classattr = array($ins_product_id=>"current");
	
	$smarty->assign('classattr',$classattr);
	$smarty->assign('ins_product_list',$arr_ins_product_list);
	
	$ins_product_selected = $arr_ins_product_list[$ins_product_id];//选择哪个产品就显示那个产品信息
	$smarty->assign('ins_product_selected',$ins_product_selected);
}
//added by zhx, 20150909, 阳光保险
elseif( $insurer_code == "sinosig" )
{
	$attribute_id = $goods['tid']; 
	$retattr = get_product_view_info($attribute_id,$attribute_type);
	$arr_ins_product_list = $retattr['arr_ins_product_list'];
	$product_id = $retattr['product_id'];
	/////////////////////////////////////////////////////////////////////
	
	$ins_product_id = isset($_REQUEST['ins_product_id'])  ? intval($_REQUEST['ins_product_id']) : $product_id;//add by wangcya , 20140806,具体是选择的那个产品
	
	/////////////////////////////////////////////////////////////////////
	
	if('personal_account' == $attribute_type)
	{
		$sql="SELECT product_id FROM t_insurance_product_base 
				WHERE  product_type='main' AND attribute_id='$attribute_id' ";

		$product_id = $GLOBALS['db']->getOne($sql);
	
		//得到产品投保期间列表，
		$sql="SELECT DISTINCT factor_name,factor_code FROM t_insurance_product_influencingfactor AS pi
				WHERE pi.product_influencingfactor_type='period' AND pi.product_id='$product_id' order by view_order";
		$query = $_SGLOBAL['db']->query($sql);

		//comment by zhangxi, 20141204, 一个险种下的多个产品列表,主险附加险的情况不适用
		$arr_peroid_list = array();
		while ($row = $_SGLOBAL['db']->fetch_array($query))
		{
			$arr_period_list[] = $row;
		}

		$smarty->assign('arr_period_list',$arr_period_list);
		
	}
	
	
	
	$classattr = array($ins_product_id=>"current");
	
	$smarty->assign('classattr',$classattr);
	$smarty->assign('ins_product_list',$arr_ins_product_list);
	
	$ins_product_selected = $arr_ins_product_list[$ins_product_id];//选择哪个产品就显示那个产品信息
	$smarty->assign('ins_product_selected',$ins_product_selected);
}
elseif($insurer_code =="SSBQ")//诉讼保全
{

	global $_SGLOBAL;
	$attribute_id = $goods['tid']; 
	$retattr = get_product_view_info($attribute_id,$attribute_type);
	
	$attr_sql="SELECT * FROM t_insurance_product_attribute 
				 WHERE attribute_id='$attribute_id'";
	$attr_query = $_SGLOBAL['db']->query($attr_sql);
	$attr_row = $_SGLOBAL['db']->fetch_array($attr_query);
	$attribute_code = $attr_row['attribute_code'];

	
	$arr_ins_product_list = $retattr['arr_ins_product_list'];
	$product_id = $retattr['product_id'];
	/////////////////////////////////////////////////////////////////////
	
	$ins_product_id = isset($_REQUEST['ins_product_id'])  ? intval($_REQUEST['ins_product_id']) : $product_id;//add by wangcya , 20140806,具体是选择的那个产品
	
	/////////////////////////////////////////////////////////////////////
	$smarty->assign('attribute_code',$attribute_code);
	$classattr = array($ins_product_id=>"current");
	
	$smarty->assign('classattr',$classattr);
	$smarty->assign('ins_product_list',$arr_ins_product_list);
	
	$ins_product_selected = $arr_ins_product_list[$ins_product_id];//选择哪个产品就显示那个产品信息
	$smarty->assign('ins_product_selected',$ins_product_selected);
        
}
//end add by wangcya , 20140802, 得到该产品属性下面的产品信息列表


$smarty->display('goods.dwt',      $cache_id);

/*------------------------------------------------------ */
//-- PRIVATE FUNCTION
/*------------------------------------------------------ */

/**
 * 获得指定商品的关联商品
 *
 * @access  public
 * @param   integer     $goods_id
 * @return  array
 */
function get_linked_goods($goods_id)
{
    $sql = 'SELECT g.goods_id, g.goods_name, g.goods_thumb, g.goods_img, g.shop_price AS org_price, ' .
                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, ".
                'g.market_price, g.promote_price, g.promote_start_date, g.promote_end_date ' .
            'FROM ' . $GLOBALS['ecs']->table('link_goods') . ' lg ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON g.goods_id = lg.link_goods_id ' .
            "LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp ".
                    "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' ".
            "WHERE lg.goods_id = '$goods_id' AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 ".
            "LIMIT " . $GLOBALS['_CFG']['related_goods_number'];
    $res = $GLOBALS['db']->query($sql);
    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $arr[$row['goods_id']]['goods_id']     = $row['goods_id'];
        $arr[$row['goods_id']]['goods_name']   = $row['goods_name'];
        $arr[$row['goods_id']]['short_name']   = $GLOBALS['_CFG']['goods_name_length'] > 0 ?
            sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
        $arr[$row['goods_id']]['goods_thumb']  = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $arr[$row['goods_id']]['goods_img']    = get_image_path($row['goods_id'], $row['goods_img']);
        $arr[$row['goods_id']]['market_price'] = price_format($row['market_price']);
        $arr[$row['goods_id']]['shop_price']   = price_format($row['shop_price']);
        $arr[$row['goods_id']]['url']          = build_uri('goods', array('gid'=>$row['goods_id']), $row['goods_name']);

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

/**
 * 获得指定商品的关联文章
 *
 * @access  public
 * @param   integer     $goods_id
 * @return  void
 */
function get_linked_articles($goods_id)
{
    $sql = 'SELECT a.article_id, a.title, a.file_url, a.open_type, a.add_time ' .
            'FROM ' . $GLOBALS['ecs']->table('goods_article') . ' AS g, ' .
                $GLOBALS['ecs']->table('article') . ' AS a ' .
            "WHERE g.article_id = a.article_id AND g.goods_id = '$goods_id' AND a.is_open = 1 " .
            'ORDER BY a.add_time DESC';
    $res = $GLOBALS['db']->query($sql);

    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $row['url']         = $row['open_type'] != 1 ?
            build_uri('article', array('aid'=>$row['article_id']), $row['title']) : trim($row['file_url']);
        $row['add_time']    = local_date($GLOBALS['_CFG']['date_format'], $row['add_time']);
        $row['short_title'] = $GLOBALS['_CFG']['article_title_length'] > 0 ?
            sub_str($row['title'], $GLOBALS['_CFG']['article_title_length']) : $row['title'];

        $arr[] = $row;
    }

    return $arr;
}

/**
 * 获得指定商品的各会员等级对应的价格
 *
 * @access  public
 * @param   integer     $goods_id
 * @return  array
 */
function get_user_rank_prices($goods_id, $shop_price)
{
    $sql = "SELECT rank_id, IFNULL(mp.user_price, r.discount * $shop_price / 100) AS price, r.rank_name, r.discount " .
            'FROM ' . $GLOBALS['ecs']->table('user_rank') . ' AS r ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . " AS mp ".
                "ON mp.goods_id = '$goods_id' AND mp.user_rank = r.rank_id " .
            "WHERE r.show_price = 1 OR r.rank_id = '$_SESSION[user_rank]'";
    $res = $GLOBALS['db']->query($sql);

    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {

        $arr[$row['rank_id']] = array(
                        'rank_name' => htmlspecialchars($row['rank_name']),
                        'price'     => price_format($row['price']));
    }

    return $arr;
}

/**
 * 获得购买过该商品的人还买过的商品
 *
 * @access  public
 * @param   integer     $goods_id
 * @return  array
 */
function get_also_bought($goods_id)
{
    $sql = 'SELECT COUNT(b.goods_id ) AS num, g.goods_id, g.goods_name, g.goods_thumb, g.goods_img, g.shop_price, g.promote_price, g.promote_start_date, g.promote_end_date ' .
            'FROM ' . $GLOBALS['ecs']->table('order_goods') . ' AS a ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('order_goods') . ' AS b ON b.order_id = a.order_id ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON g.goods_id = b.goods_id ' .
            "WHERE a.goods_id = '$goods_id' AND b.goods_id <> '$goods_id' AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 " .
            'GROUP BY b.goods_id ' .
            'ORDER BY num DESC ' .
            'LIMIT ' . $GLOBALS['_CFG']['bought_goods'];
    $res = $GLOBALS['db']->query($sql);

    $key = 0;
    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $arr[$key]['goods_id']    = $row['goods_id'];
        $arr[$key]['goods_name']  = $row['goods_name'];
        $arr[$key]['short_name']  = $GLOBALS['_CFG']['goods_name_length'] > 0 ?
            sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
        $arr[$key]['goods_thumb'] = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $arr[$key]['goods_img']   = get_image_path($row['goods_id'], $row['goods_img']);
        $arr[$key]['shop_price']  = price_format($row['shop_price']);
        $arr[$key]['url']         = build_uri('goods', array('gid'=>$row['goods_id']), $row['goods_name']);

        if ($row['promote_price'] > 0)
        {
            $arr[$key]['promote_price'] = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
            $arr[$key]['formated_promote_price'] = price_format($arr[$key]['promote_price']);
        }
        else
        {
            $arr[$key]['promote_price'] = 0;
        }

        $key++;
    }

    return $arr;
}

/**
 * 获得指定商品的销售排名
 *
 * @access  public
 * @param   integer     $goods_id
 * @return  integer
 */
function get_goods_rank($goods_id)
{
    /* 统计时间段 */
    $period = intval($GLOBALS['_CFG']['top10_time']);
    if ($period == 1) // 一年
    {
        $ext = " AND o.add_time > '" . local_strtotime('-1 years') . "'";
    }
    elseif ($period == 2) // 半年
    {
        $ext = " AND o.add_time > '" . local_strtotime('-6 months') . "'";
    }
    elseif ($period == 3) // 三个月
    {
        $ext = " AND o.add_time > '" . local_strtotime('-3 months') . "'";
    }
    elseif ($period == 4) // 一个月
    {
        $ext = " AND o.add_time > '" . local_strtotime('-1 months') . "'";
    }
    else
    {
        $ext = '';
    }

    /* 查询该商品销量 */
    $sql = 'SELECT IFNULL(SUM(g.goods_number), 0) ' .
        'FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS o, ' .
            $GLOBALS['ecs']->table('order_goods') . ' AS g ' .
        "WHERE o.order_id = g.order_id " .
        "AND o.order_status = '" . OS_CONFIRMED . "' " .
        "AND o.shipping_status " . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) .
        " AND o.pay_status " . db_create_in(array(PS_PAYED, PS_PAYING)) .
        " AND g.goods_id = '$goods_id'" . $ext;
    $sales_count = $GLOBALS['db']->getOne($sql);

    if ($sales_count > 0)
    {
        /* 只有在商品销售量大于0时才去计算该商品的排行 */
        $sql = 'SELECT DISTINCT SUM(goods_number) AS num ' .
                'FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS o, ' .
                    $GLOBALS['ecs']->table('order_goods') . ' AS g ' .
                "WHERE o.order_id = g.order_id " .
                "AND o.order_status = '" . OS_CONFIRMED . "' " .
                "AND o.shipping_status " . db_create_in(array(SS_SHIPPED, SS_RECEIVED)) .
                " AND o.pay_status " . db_create_in(array(PS_PAYED, PS_PAYING)) . $ext .
                " GROUP BY g.goods_id HAVING num > $sales_count";
        $res = $GLOBALS['db']->query($sql);

        $rank = $GLOBALS['db']->num_rows($res) + 1;

        if ($rank > 10)
        {
            $rank = 0;
        }
    }
    else
    {
        $rank = 0;
    }

    return $rank;
}

/**
 * 获得商品选定的属性的附加总价格
 *
 * @param   integer     $goods_id
 * @param   array       $attr
 *
 * @return  void
 */
function get_attr_amount($goods_id, $attr)
{
    $sql = "SELECT SUM(attr_price) FROM " . $GLOBALS['ecs']->table('goods_attr') .
        " WHERE goods_id='$goods_id' AND " . db_create_in($attr, 'goods_attr_id');

    return $GLOBALS['db']->getOne($sql);
}

/**
 * 取得跟商品关联的礼包列表
 *
 * @param   string  $goods_id    商品编号
 *
 * @return  礼包列表
 */
function get_package_goods_list($goods_id)
{
    $now = gmtime();
    $sql = "SELECT pg.goods_id, ga.act_id, ga.act_name, ga.act_desc, ga.goods_name, ga.start_time,
                   ga.end_time, ga.is_finished, ga.ext_info
            FROM " . $GLOBALS['ecs']->table('goods_activity') . " AS ga, " . $GLOBALS['ecs']->table('package_goods') . " AS pg
            WHERE pg.package_id = ga.act_id
            AND ga.start_time <= '" . $now . "'
            AND ga.end_time >= '" . $now . "'
            AND pg.goods_id = " . $goods_id . "
            GROUP BY ga.act_id
            ORDER BY ga.act_id ";
    $res = $GLOBALS['db']->getAll($sql);

    foreach ($res as $tempkey => $value)
    {
        $subtotal = 0;
        $row = unserialize($value['ext_info']);
        unset($value['ext_info']);
        if ($row)
        {
            foreach ($row as $key=>$val)
            {
                $res[$tempkey][$key] = $val;
            }
        }

        $sql = "SELECT pg.package_id, pg.goods_id, pg.goods_number, pg.admin_id, p.goods_attr, g.goods_sn, g.goods_name, g.market_price, g.goods_thumb, IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS rank_price
                FROM " . $GLOBALS['ecs']->table('package_goods') . " AS pg
                    LEFT JOIN ". $GLOBALS['ecs']->table('goods') . " AS g
                        ON g.goods_id = pg.goods_id
                    LEFT JOIN ". $GLOBALS['ecs']->table('products') . " AS p
                        ON p.product_id = pg.product_id
                    LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp
                        ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]'
                WHERE pg.package_id = " . $value['act_id']. "
                ORDER BY pg.package_id, pg.goods_id";

        $goods_res = $GLOBALS['db']->getAll($sql);

        foreach($goods_res as $key => $val)
        {
            $goods_id_array[] = $val['goods_id'];
            $goods_res[$key]['goods_thumb']  = get_image_path($val['goods_id'], $val['goods_thumb'], true);
            $goods_res[$key]['market_price'] = price_format($val['market_price']);
            $goods_res[$key]['rank_price']   = price_format($val['rank_price']);
            $subtotal += $val['rank_price'] * $val['goods_number'];
        }

        /* 取商品属性 */
        $sql = "SELECT ga.goods_attr_id, ga.attr_value
                FROM " .$GLOBALS['ecs']->table('goods_attr'). " AS ga, " .$GLOBALS['ecs']->table('attribute'). " AS a
                WHERE a.attr_id = ga.attr_id
                AND a.attr_type = 1
                AND " . db_create_in($goods_id_array, 'goods_id');
        $result_goods_attr = $GLOBALS['db']->getAll($sql);

        $_goods_attr = array();
        foreach ($result_goods_attr as $value)
        {
            $_goods_attr[$value['goods_attr_id']] = $value['attr_value'];
        }

        /* 处理货品 */
        $format = '[%s]';
        foreach($goods_res as $key => $val)
        {
            if ($val['goods_attr'] != '')
            {
                $goods_attr_array = explode('|', $val['goods_attr']);

                $goods_attr = array();
                foreach ($goods_attr_array as $_attr)
                {
                    $goods_attr[] = $_goods_attr[$_attr];
                }

                $goods_res[$key]['goods_attr_str'] = sprintf($format, implode('，', $goods_attr));
            }
        }

        $res[$tempkey]['goods_list']    = $goods_res;
        $res[$tempkey]['subtotal']      = price_format($subtotal);
        $res[$tempkey]['saving']        = price_format(($subtotal - $res[$tempkey]['package_price']));
        $res[$tempkey]['package_price'] = price_format($res[$tempkey]['package_price']);
    }

    return $res;
}

?>