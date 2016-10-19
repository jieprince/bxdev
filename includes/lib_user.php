<?php

/**
 * 和用户有recommend关的函数库
 */

/**
 *  integer $user_id 用户id
 */
function user_base_info($user_id)
{
	include_once (ROOT_PATH . 'includes/lib_clips.php');
	include_once (ROOT_PATH . 'includes/lib_order.php');
    $user_base_info=array();
	//收藏商品总数
	$sql="SELECT count(*) FROM ". $GLOBALS['ecs']->table('collect_goods') . " WHERE user_id =".$user_id;
	$collect_goods_total =$GLOBALS['db']->getOne($sql);
	
	//推荐总数
	$sql="SELECT count(*) FROM ". $GLOBALS['ecs']->table('users') . " WHERE parent_id =".$user_id;
	$recommend_total =$GLOBALS['db']->getOne($sql);
	
	//累计收入
	$sql =  "SELECT sum(user_money) FROM " .$GLOBALS['ecs']->table('account_log')." WHERE user_id =".$user_id.
			" AND incoming_type IS NOT NULL AND incoming_type <>'' ";
	$income_total =$GLOBALS['db']->getOne($sql);
	
	//账余额
	$user_info =user_info($user_id);
	
	//会员总数
	$sql="SELECT count(*) FROM ". $GLOBALS['ecs']->table('users') . " WHERE institution_id =".$user_id;
	$user_total =$GLOBALS['db']->getOne($sql);
	
	
	//最新公告
	$user_base_info['article']= get_new_affiche();
	
	
	//获取用户中心背景图片
	$sql="SELECT img_url FROM ". $GLOBALS['ecs']->table('userbg_img') . " WHERE id ='$user_info[bg_img]'";
	$img_url =$GLOBALS['db']->getOne($sql);

	//默认图片
	$sql="SELECT img_url FROM ". $GLOBALS['ecs']->table('userbg_img') . " WHERE  is_default=1 ORDER BY id DESC LIMIT 1 ";
	$default_img_url =$GLOBALS['db']->getOne($sql);
	
	$user_base_info['default_bg_img_url']=$default_img_url;
	$user_base_info['img_url']=$img_url;
	$user_base_info['collect_goods_total']=$collect_goods_total;
	$user_base_info['recommend_total']=$recommend_total;
	$user_base_info['income_total']=$income_total;
	$user_base_info['user_money']=$user_info['user_money'];
	$user_base_info['award_money']=$user_info['award_money'];
	$user_base_info['service_money']=$user_info['service_money'];
	$user_base_info['user_total']=$user_total;
    return $user_base_info;
}



//add yes123 2014-12-16 获取手机号码
function get_user_mobile_phone($mobile_phone){
	$sql="SELECT user_id FROM ".$ecs->table('users'). " WHERE user_name= '".$mobile_phone."'";
	$user_id=$GLOBALS['db']->getOne($sql);
	if($user_id){
		return $mobile_phone;
	}else{
		$sql="SELECT user_id FROM ".$ecs->table('users'). " WHERE mobile_phone= '".$mobile_phone."'";
		$user_id=$GLOBALS['db']->getOne($sql);
		if($user_id){
			return $mobile_phone;
		}
	}
}

function get_affiche_list()
{
	//最新公告
	$sql="SELECT cat_id FROM ". $GLOBALS['ecs']->table('article_cat') . " WHERE cat_name LIKE '%最新公告%'";
	$cat_id =$GLOBALS['db']->getOne($sql);
	if($cat_id)
	{
		$sql="SELECT * FROM ". $GLOBALS['ecs']->table('article') . " WHERE cat_id='$cat_id' AND is_open=1 ORDER BY article_id DESC";
		$article_list =$GLOBALS['db']->getAll($sql);
		foreach ( $article_list as $key => $value ) {
			if($value['add_time'])
			{
	       		$article_list[$key]['add_time']=date("Y-m-d",$value['add_time']);
			}
		}		
		
		return $article_list;
	}
	
}

function get_new_affiche()
{
	$sql="SELECT cat_id FROM ". $GLOBALS['ecs']->table('article_cat') . " WHERE cat_name LIKE '%最新公告%'";
	$cat_id =$GLOBALS['db']->getOne($sql);
	if($cat_id)
	{
		$sql="SELECT * FROM ". $GLOBALS['ecs']->table('article') . " WHERE cat_id='$cat_id' AND is_open=1 ORDER BY article_id DESC LIMIT 1";
		$article =$GLOBALS['db']->getRow($sql);
		if($article['add_time'])
		{
      		$article['add_time']=date("Y-m-d",$article['add_time']);
		}
		return $article;
	}
	
}
?>