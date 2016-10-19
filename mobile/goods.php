<?php

/**
 * ECSHOP 商品页
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: testyang $
 * $Id: goods.php 15013 2008-10-23 09:31:42Z testyang $
*/

define('IN_ECS', true);
define('ECS_ADMIN', true);

require(dirname(__FILE__) . '/includes/init.php');
//require(dirname(__FILE__) .'/baoxian/common.php');
include_once(dirname(__FILE__) .'./../baoxian/common.php');

$goods_id = !empty($_GET['id']) ? intval($_GET['id']) : '';
$act = !empty($_GET['act']) ? $_GET['act'] : '';
if ($_SESSION['user_id'] > 0)
{
	$smarty->assign('user_name', $_SESSION['user_name']);

}

//如果没有商品的id
if(!$goods_id)
{
	//但是直接有产品的id
	$ins_product_id = isset($_REQUEST['ins_product_id'])  ? intval($_REQUEST['ins_product_id']) : 0;//add by wangcya , 20140806,具体是选择的那个产品
	
	$attribute_id = isset($_REQUEST['attribute_id'])  ? intval($_REQUEST['attribute_id']) : 0;//add add yes123 2015-01-10 产品属性ID
	
	//根据某个具体的产品找到其属性，进而找到商品
	if($ins_product_id)
	{
		$sql = "SELECT attribute_id FROM t_insurance_product_base WHERE product_id='$ins_product_id'";
		$attribute_id = $db->getOne($sql);
	}
	if($attribute_id)
	{
		$sql = "SELECT goods_id FROM bx_goods WHERE tid='$attribute_id'";
		$goods_id = $db->getOne($sql);
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

    die($json->encode($res));
}

$_LANG['kilogram'] = '千克';
$_LANG['gram'] = '克';
$_LANG['home'] = '首页';
$_LANG['goods_attr'] = '';

$smarty->assign('goods_id', $goods_id);
//add by dingchaoyang 2015-3-25 
$smarty->assign('platformId',isset($_REQUEST['platformId'])?$_REQUEST['platformId']:'');
$smarty->assign('uid',isset($_REQUEST['uid'])?$_REQUEST['uid']:'');
//end
$goods_info = get_goods_info($goods_id);
if ($goods_info === false)
{
   /* 如果没有找到任何记录则跳回到首页 */
   ecs_header("Location: ./\n");
   exit;
}

$goods_info['goods_name'] = encode_output($goods_info['goods_name']);
$goods_info['goods_brief'] = encode_output($goods_info['goods_brief']);
$goods_info['promote_price'] = encode_output($goods_info['promote_price']);
$goods_info['market_price'] = encode_output($goods_info['market_price']);
$goods_info['shop_price'] = encode_output($goods_info['shop_price']);
$goods_info['shop_price_formated'] = encode_output($goods_info['shop_price_formated']);
$goods_info['goods_number'] = encode_output($goods_info['goods_number']);
$goods_info['rate_myself'] = doubleval($goods_info['rate_myself']);

$smarty->assign('goods', $goods_info);

$goods_info['tid'] = encode_output($goods_info['tid']);
$smarty->assign('goods_info', $goods_info);

$shop_price   = $goods_info['shop_price'];
$smarty->assign('rank_prices',		 get_user_rank_prices($goods_id, $shop_price));	// 会员等级价格
$smarty->assign('goods_info2',		 get_goods_info2($goods_id));
$smarty->assign('related_goods',		 get_linked_goods($goods_id));
        $properties = get_goods_properties($goods_id);  // 获得商品的规格和属性

        $smarty->assign('properties',          $properties['pro']);                              // 商品属性
$smarty->assign('footer', get_footer());

/* 查看商品图片操作 */
if ($act == 'view_img')
{       /* 产品简介 */
        //$smarty->assign('goods_desc' , $goods_info['goods_desc']);
        $smarty->assign('desc' ,'产品简介' );
        $smarty->assign('in_description' , stripslashes($goods_info['in_description']));
	$smarty->display('goods_img.dwt');
	exit();
}
/* baohongzhou 14-12-15 下午1:58 start */
elseif($act == 'cover_note'){
    /* 投保须知 */
    $smarty->assign('desc' ,'投保须知' );
    $smarty->assign('cover_note' , stripslashes($goods_info['cover_note']));
    $smarty->display('goods_img.dwt');
    exit();
}elseif($act == 'limit_note'){
    /* 常见问题 */
    $smarty->assign('desc' ,'常见问题' );
    $smarty->assign('limit_note' , stripslashes($goods_info['limit_note']));
    $smarty->display('goods_img.dwt');
    exit();
}elseif($act == 'insurance_clauses'){
    /* 保险条款 */
    $smarty->assign('desc' ,'保险条款' );
    $smarty->assign('insurance_clauses' , stripslashes($goods_info['insurance_clauses']));
    $smarty->display('goods_img.dwt');
    exit();
}elseif($act == 'claims_guide'){
    /* 理赔指南 */
    $smarty->assign('desc' ,'理赔指南' );
    $smarty->assign('claims_guide' , stripslashes($goods_info['claims_guide']));
    $smarty->display('goods_img.dwt');
    exit();
}
/* baohongzhou 14-12-15 下午1:58 end */

/* 检查是否有商品品牌 */
if (!empty($goods_info['brand_id']))
{
	$brand_name = $db->getOne("SELECT brand_name FROM " . $ecs->table('brand') . " WHERE brand_id={$goods_info['brand_id']}");
	$smarty->assign('brand_name', encode_output($brand_name));
}
/* 显示分类名称 */
$cat_array = get_parent_cats($goods_info['cat_id']);
krsort($cat_array);
$cat_str = '';
foreach ($cat_array as $key => $cat_data)
{
	$cat_array[$key]['cat_name'] = encode_output($cat_data['cat_name']);
	$cat_str .= "<a href='category.php?c_id={$cat_data['cat_id']}'>" . encode_output($cat_data['cat_name']) . "</a>-&gt;";
}
$smarty->assign('cat_array', $cat_array);



//start add by wangcya , 20140802, 得到该产品属性下面的产品信息列表
$goods = $goods_info;

$smarty->assign('goods',              $goods);
$smarty->assign('goods_id',           $goods['goods_id']);



$insurer_code = $goods['insurer_code'];//这个属性字段来决定显示的方式。
$attribute_type = trim($goods['attribute_type']);//这个属性字段来决定显示的方式。

$smarty->assign('insurer_code',$insurer_code);
$smarty->assign('attribute_type',$attribute_type);


//平安
if(	$insurer_code =="PAC"||
	$insurer_code =="PAC01"||
	$insurer_code =="PAC02"||
	$insurer_code =="PAC03"||
	$insurer_code =="PAC05"
	)
{

	$attribute_id = $goods['tid'];
	$retattr = get_product_view_info($attribute_id,$attribute_type);

	//added by zhangxi,  20141204, Y502产品显示页面，初次数据获取处理
	if('Y502' == $attribute_type)
	{
		global $_SGLOBAL;
		//获取y502产品主险product_id
		$sql="SELECT product_id FROM t_insurance_product_base
		WHERE  product_type='main' AND attribute_id='$attribute_id' ";
		//$product_query = $_SGLOBAL['db']->query($sql);
		//$row = $_SGLOBAL['db']->fetch_row($product_query);
		//$product_id = $row[0];
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
		//echo "<pre>";
		//echo $product_id;
		//var_dump($arr_career_list);
		//echo "----------------------------------------------------";

		//var_dump($retattr);
		$smarty->assign('arr_period_list',$arr_period_list);
		$smarty->assign('arr_career_list',$arr_career_list);
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
	if($ins_product_id){
		$ins_product_selected = $arr_ins_product_list[$ins_product_id];//选择哪个产品就显示那个产品信息
		$smarty->assign('ins_product_selected',$ins_product_selected);
	}
	

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
    $smarty->assign('attribute_id',$attribute_id);
	$smarty->assign('classattr',$classattr);
	$smarty->assign('ins_product_list',$arr_ins_product_list);
	
	$ins_product_selected = $arr_ins_product_list[$ins_product_id];//选择哪个产品就显示那个产品信息
	$smarty->assign('ins_product_selected',$ins_product_selected);
	
	$ROOT_PATH_= str_replace ( 'goods.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
	include_once ($ROOT_PATH_. '../baoxian/source/function_baoxian_huaan.php');
	$arr_project_list = get_huaan_project_cost_peroid($product_id);
	$smarty->assign('arr_project_list',$arr_project_list);
	//echo "<pre>";
	//var_dump($arr_project_list);
	
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
//added by zhangxi, 20150318, 新华人寿产品
elseif( $insurer_code == "NCI" )
{
	$attribute_id = $goods['tid']; 
	$retattr = get_product_view_info($attribute_id,$attribute_type);
	
	$server_time = time();//second, $_SGLOBAL['timestamp'];
	ss_log("server_time: ".$server_time);
	$servertime_str = date("Y-m-d H:i:s",$server_time);
	ss_log("servertime_str: ".$servertime_str);

	//added by zhangxi,  20141204, Y502产品显示页面，初次数据获取处理
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

//end add by wangcya , 20140802, 得到该产品属性下面的产品信息列表
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
/*
 //echo "insurer_code: ".$insurer_code;
ss_log("mobile insurer_code: ".$insurer_code);
//平安和华泰
if($insurer_code =="PAC")
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


//end add by wangcya , 20140802, 得到该产品属性下面的产品信息列表
*/


$properties = get_goods_properties($goods_id);  // 获得商品的规格和属性
$smarty->assign('specification',	   $properties['spe']);  // 商品规格


$comment = assign_comment($goods_id, 0);
$smarty->assign('comment', $comment);

$goods_gallery = get_goods_gallery($goods_id);
$smarty->assign('picturesnum', count($goods_gallery));// 相册数
$smarty->assign('pictures', $goods_gallery);// 商品相册
$smarty->assign('now_time',  time()); // 当前系统时间






$smarty->display('goods.dwt');

/**
 * 获得指定商品的各会员等级对应的价格
 *
 * @access  public
 * @param   integer	 $goods_id
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
						'price'	 => price_format($row['price']));
	}

	return $arr;
}


?>