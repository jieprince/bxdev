<?php

/**
 * ECSHOP 会员等级管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: user_rank.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$exc = new exchange($ecs->table("user_rank"), $db, 'rank_id', 'rank_name');
$exc_user = new exchange($ecs->table("users"), $db, 'user_rank', 'user_rank');

/*------------------------------------------------------ */
//-- 会员等级列表
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'list')
{
    $ranks = array();
    $ranks = $db->getAll("SELECT * FROM " .$ecs->table('user_rank'));

    $smarty->assign('ur_here',      $_LANG['05_user_rank_list']);
    $smarty->assign('action_link',  array('text' => $_LANG['add_user_rank'], 'href'=>'user_rank.php?act=add'));
    $smarty->assign('full_page',    1);

    $smarty->assign('user_ranks',   $ranks);

    assign_query_info();
    $smarty->display('user_rank.htm');
}

/*------------------------------------------------------ */
//-- 翻页，排序
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $ranks = array();
    $ranks = $db->getAll("SELECT * FROM " .$ecs->table('user_rank'));

    $smarty->assign('user_ranks',   $ranks);
    make_json_result($smarty->fetch('user_rank.htm'));
}

/*------------------------------------------------------ */
//-- 添加会员等级
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'add')
{
    admin_priv('user_rank');
	
    $rank['rank_id']      = 0;
    $rank['rank_special'] = 0;
    $rank['show_price']   = 1;
    $rank['min_points']   = 0;
    $rank['max_points']   = 0;
    $rank['discount']     = 100;

    $form_action          = 'insert';

    $smarty->assign('rank',        $rank);
    $smarty->assign('ur_here',     $_LANG['add_user_rank']);
    $smarty->assign('action_link', array('text' => $_LANG['05_user_rank_list'], 'href'=>'user_rank.php?act=list'));
    $smarty->assign('form_action', $form_action);

    assign_query_info();
    $smarty->display('user_rank_info.htm');
}

/*------------------------------------------------------ */
//-- 编辑会员等级
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'edit')
{
    admin_priv('user_rank');
	
	$rank_id = $_GET['id'];
	$sql = "SELECT * FROM bx_user_rank WHERE rank_id='$rank_id'";
	$user_rank = $db->getRow($sql);
	
	$sql = "SELECT * FROM bx_user_rank_config WHERE rank_id='$rank_id'";
	$config_list = $db->getAll($sql);
	foreach ( $config_list as $key => $cfg ) 
	{
		$smarty->assign($cfg['rc_type'], $cfg);
    	   
	}
	
    $form_action          = 'update';

    $smarty->assign('rank',        $user_rank);
    $smarty->assign('ur_here',     "编辑会员等级");
    $smarty->assign('action_link', array('text' => $_LANG['05_user_rank_list'], 'href'=>'user_rank.php?act=list'));
    $smarty->assign('form_action', $form_action);
	
    assign_query_info();
    $smarty->display('user_rank_info.htm');
}
/*------------------------------------------------------ */
//-- 增加会员等级到数据库
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'insert' || $_REQUEST['act'] == 'update')
{
    admin_priv('user_rank');
	
	$act = $_REQUEST['act'];
	
    $special_rank =  isset($_POST['special_rank']) ? intval($_POST['special_rank']) : 0;
    $rank_id =  isset($_POST['id']) ? intval($_POST['id']) : 0;
    $_POST['min_points'] = empty($_POST['min_points']) ? 0 : intval($_POST['min_points']);
    $_POST['max_points'] = empty($_POST['max_points']) ? 0 : intval($_POST['max_points']);
	
	
	
	if($act=='update')
	{
		$exc->id='rank_id';
	}
	    /* 检查是否存在重名的会员等级 */
    if (!$exc->is_only('rank_name', trim($_POST['rank_name']),$rank_id))
    {
        sys_msg(sprintf($_LANG['rank_name_exists'], trim($_POST['rank_name'])), 1);
    }
    
    		
    /* 非特殊会员组检查积分的上下限是否合理 */
    if ($_POST['min_points'] >= $_POST['max_points'] && $special_rank == 0)
    {
        sys_msg($_LANG['js_languages']['integral_max_small'], 1);
    }
	    

    /* 特殊等级会员组不判断积分限制 */
    if ($special_rank == 0)
    {
        /* 检查下限制有无重复 */
        if (!$exc->is_only('min_points', intval($_POST['min_points']),$rank_id))
        {
            sys_msg(sprintf($_LANG['integral_min_exists'], intval($_POST['min_points'])));
        }
    }

    /* 特殊等级会员组不判断积分限制 */
    if ($special_rank == 0)
    {
        /* 检查上限有无重复 */
        if (!$exc->is_only('max_points', intval($_POST['max_points']),$rank_id))
        {
            sys_msg(sprintf($_LANG['integral_max_exists'], intval($_POST['max_points'])));
        }
    }
	
	
	
	
	$user_rank_data = array(
			'rank_name'=>$_POST['rank_name'],
			'rank_code'=>$_POST['rank_code'],
			'min_points'=>intval($_POST['min_points']),
			'max_points'=>intval($_POST['max_points']),
			'discount'=>$_POST['discount'],
			'special_rank'=>$special_rank,
			'show_price'=>intval($_POST['show_price'])
	
	);
	
	if($act=='insert')
	{
		
		$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank'), $user_rank_data, "INSERT");  
			    
		$rank_id = $GLOBALS['db']->insert_id(); //等级ID
		
	    insert_user_rank_config($rank_id);
	    /* 管理员日志 */
	    admin_log(trim($_POST['rank_name']), 'add', 'user_rank');
	    
	    
	    $lnk[] = array('text' => $_LANG['back_list'],    'href'=>'user_rank.php?act=list');
    	$lnk[] = array('text' => $_LANG['add_continue'], 'href'=>'user_rank.php?act=add');
	}
	elseif($act='update')
	{
		$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank'), $user_rank_data, "UPDATE","rank_id=$rank_id");  
			
		update_user_rank_config($rank_id);
		
		$lnk[] = array('text' => $_LANG['back_list'],    'href'=>'user_rank.php?act=list');
		
	}
	
	
    
    clear_cache_files();


    sys_msg($_LANG['add_rank_success'], 0, $lnk);
}



/*------------------------------------------------------ */
//-- 删除会员等级
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('user_rank');

    $rank_id = intval($_GET['id']);

    if ($exc->drop($rank_id))
    {
        /* 更新会员表的等级字段 */
        $exc_user->edit("user_rank = 0", $rank_id);

        $rank_name = $exc->get_name($rank_id);
        admin_log(addslashes($rank_name), 'remove', 'user_rank');
        clear_cache_files();
    }

    $url = 'user_rank.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;

}
/*
 *  编辑会员等级名称
 */
elseif ($_REQUEST['act'] == 'edit_name')
{
    $id = intval($_REQUEST['id']);
    $val = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));
    check_authz_json('user_rank');
    if ($exc->is_only('rank_name', $val, $id))
    {
        if ($exc->edit("rank_name = '$val'", $id))
        {
            /* 管理员日志 */
            admin_log($val, 'edit', 'user_rank');
            clear_cache_files();
            make_json_result(stripcslashes($val));
        }
        else
        {
            make_json_error($db->error());
        }
    }
    else
    {
        make_json_error(sprintf($_LANG['rank_name_exists'], htmlspecialchars($val)));
    }
}

/*
 *  编辑会员等级编码
 */
elseif ($_REQUEST['act'] == 'edit_code')
{
    $id = intval($_REQUEST['id']);
    $val = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));
    check_authz_json('user_rank');
    if ($exc->is_only('rank_code', $val, $id))
    {
        if ($exc->edit("rank_code = '$val'", $id))
        {
            /* 管理员日志 */
            admin_log($val, 'edit', 'user_rank');
            clear_cache_files();
            make_json_result(stripcslashes($val));
        }
        else
        {
            make_json_error($db->error());
        }
    }
    else
    {
        make_json_error(sprintf($_LANG['rank_code_exists'], htmlspecialchars($val)));
    }
}

/*
 *  ajax编辑积分下限
 */
elseif ($_REQUEST['act'] == 'edit_min_points')
{
    check_authz_json('user_rank');

    $rank_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
    $val = empty($_REQUEST['val']) ? 0 : intval($_REQUEST['val']);

    $rank = $db->getRow("SELECT max_points, special_rank FROM " . $ecs->table('user_rank') . " WHERE rank_id = '$rank_id'");
    if ($val >= $rank['max_points'] && $rank['special_rank'] == 0)
    {
        make_json_error($_LANG['js_languages']['integral_max_small']);
    }

    if ($rank['special_rank'] ==0 && !$exc->is_only('min_points', $val, $rank_id))
    {
        make_json_error(sprintf($_LANG['integral_min_exists'], $val));
    }

    if ($exc->edit("min_points = '$val'", $rank_id))
    {
        $rank_name = $exc->get_name($rank_id);
        admin_log(addslashes($rank_name), 'edit', 'user_rank');
        make_json_result($val);
    }
    else
    {
        make_json_error($db->error());
    }
}

/*
 *  ajax修改积分上限
 */
elseif ($_REQUEST['act'] == 'edit_max_points')
{
     check_authz_json('user_rank');

    $rank_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
    $val = empty($_REQUEST['val']) ? 0 : intval($_REQUEST['val']);

    $rank = $db->getRow("SELECT min_points, special_rank FROM " . $ecs->table('user_rank') . " WHERE rank_id = '$rank_id'");

    if ($val <= $rank['min_points'] && $rank['special_rank'] == 0)
    {
        make_json_error($_LANG['js_languages']['integral_max_small']);
    }

    if ($rank['special_rank'] ==0 && !$exc->is_only('max_points', $val, $rank_id))
    {
        make_json_error(sprintf($_LANG['integral_max_exists'], $val));
    }
    if ($exc->edit("max_points = '$val'", $rank_id))
    {
        $rank_name = $exc->get_name($rank_id);
        admin_log(addslashes($rank_name), 'edit', 'user_rank');
        make_json_result($val);
    }
    else
    {
        make_json_error($db->error());
    }
}

/*
 *  修改折扣率
 */
elseif ($_REQUEST['act'] == 'edit_discount')
{
    check_authz_json('user_rank');

    $rank_id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
    $val = empty($_REQUEST['val']) ? 0 : intval($_REQUEST['val']);

    if ($val < 1 || $val > 100)
    {
        make_json_error($_LANG['js_languages']['discount_invalid']);
    }

    if ($exc->edit("discount = '$val'", $rank_id))
    {
        $rank_name = $exc->get_name($rank_id);
         admin_log(addslashes($rank_name), 'edit', 'user_rank');
         clear_cache_files();
         make_json_result($val);
    }
    else
    {
        make_json_error($val);
    }
}

/*------------------------------------------------------ */
//-- 切换是否是特殊会员组
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_special')
{
    check_authz_json('user_rank');

    $rank_id       = intval($_POST['id']);
    $is_special    = intval($_POST['val']);

    if ($exc->edit("special_rank = '$is_special'", $rank_id))
    {
        $rank_name = $exc->get_name($rank_id);
        admin_log(addslashes($rank_name), 'edit', 'user_rank');
        make_json_result($is_special);
    }
    else
    {
        make_json_error($db->error());
    }
}
/*------------------------------------------------------ */
//-- 切换是否显示价格
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_showprice')
{
    check_authz_json('user_rank');

    $rank_id       = intval($_POST['id']);
    $is_show    = intval($_POST['val']);

    if ($exc->edit("show_price = '$is_show'", $rank_id))
    {
        $rank_name = $exc->get_name($rank_id);
        admin_log(addslashes($rank_name), 'edit', 'user_rank');
        clear_cache_files();
        make_json_result($is_show);
    }
    else
    {
        make_json_error($db->error());
    }
}

function insert_user_rank_config($rank_id)
{
		
	$is_withdraw = isset($_REQUEST['is_withdraw'])?$_REQUEST['is_withdraw']:0;
	$order_service_money = isset($_REQUEST['order_service_money'])?$_REQUEST['order_service_money']:0;
	$recommend_service_money = isset($_REQUEST['recommend_service_money'])?$_REQUEST['recommend_service_money']:0;
	$organ_service_money = isset($_REQUEST['organ_service_money'])?$_REQUEST['organ_service_money']:0;
			
	//是否可以提现
	$is_withdraw_cfg = array(
			'rank_id'=>$rank_id,
			'rc_type'=>'is_withdraw',
			'is_enabled'=>$is_withdraw
	);
	$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank_config'), $is_withdraw_cfg, "INSERT");  
    
    //是否分配订单服务费
    $order_service_money_cfg = array(
			'rank_id'=>$rank_id,
			'rc_type'=>'order_service_money',
			'is_enabled'=>$order_service_money,
			'value'=>floatval($_REQUEST['order_service_money_ratio']),
	);
	$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank_config'), $order_service_money_cfg, "INSERT");  
    
    //是否分配推荐服务费
    $recommend_service_money_cfg = array(
			'rank_id'=>$rank_id,
			'rc_type'=>'recommend_service_money',
			'is_enabled'=>$recommend_service_money,
			'value'=>floatval($_REQUEST['recommend_service_money_ratio']),
	);
	$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank_config'), $recommend_service_money_cfg, "INSERT");  
    
	
	//推荐奖励
	//奖励类型，代金券（代金券）或者金币
	$recommend_award_type = isset($_REQUEST['recommend_award_type'])?$_REQUEST['recommend_award_type']:0;
	//奖励的金额
	$recommend_award_sum = isset($_REQUEST['recommend_award_sum'])?floatval($_REQUEST['recommend_award_sum']):0;
	
	//代金券
	if($recommend_award_type=='recommend_give_user_bonus')
	{
		//代金券的有效期
		$recommend_award_deadline = isset($_REQUEST['recommend_award_deadline'])?$_REQUEST['recommend_award_deadline']:0;
		
		//是否分配推荐服务费
	    $give_user_bonus_cfg = array(
				'rank_id'=>$rank_id,
				'rc_type'=>'recommend_give_user_bonus',
				'is_enabled'=>$recommend_award_sum>0?1:0,
				'value'=>$recommend_award_sum,
				'deadline'=>$recommend_award_deadline
		);
		$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank_config'), $give_user_bonus_cfg, "INSERT");  
		
		
	}
	
	//金币
	if($recommend_award_type=='recommend_give_service_money')
	{
		//是否分配推荐服务费
	    $give_service_money_cfg = array(
				'rank_id'=>$rank_id,
				'rc_type'=>'recommend_give_service_money',
				'is_enabled'=>$recommend_award_sum>0?1:0,
				'value'=>$recommend_award_sum,
		);
		$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank_config'), $give_service_money_cfg, "INSERT");  
		
		
	}
	
	
}

function update_user_rank_config($rank_id)
{
		
	$is_withdraw = isset($_REQUEST['is_withdraw'])?$_REQUEST['is_withdraw']:0;
	$order_service_money = isset($_REQUEST['order_service_money'])?$_REQUEST['order_service_money']:0;
	$recommend_service_money = isset($_REQUEST['recommend_service_money'])?$_REQUEST['recommend_service_money']:0;
	$organ_service_money = isset($_REQUEST['organ_service_money'])?$_REQUEST['organ_service_money']:0;
			
	//是否可以提现
	$is_withdraw_cfg = array(
			'rc_type'=>'is_withdraw',
			'is_enabled'=>$is_withdraw
	);
	$where = "rank_id=$rank_id AND rc_type='is_withdraw'";
	$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank_config'), $is_withdraw_cfg, "UPDATE",$where);  
    
    
    //是否分配订单服务费
    $order_service_money_cfg = array(
			'is_enabled'=>$order_service_money,
			'value'=>floatval($_REQUEST['order_service_money_ratio']),
	);
	$where = "rank_id=$rank_id AND rc_type='order_service_money'";
	$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank_config'), $order_service_money_cfg, "UPDATE",$where);  
    
    
    //是否分配推荐服务费
    $recommend_service_money_cfg = array(
			'is_enabled'=>$recommend_service_money,
			'value'=>floatval($_REQUEST['recommend_service_money_ratio']),
	);
	$where = "rank_id=$rank_id AND rc_type='recommend_service_money'";
	$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank_config'), $recommend_service_money_cfg, "UPDATE",$where);  
    
    
    
    //奖励类型，代金券（代金券）或者金币
	$recommend_award_type = isset($_REQUEST['recommend_award_type'])?$_REQUEST['recommend_award_type']:0;
	//奖励的金额
	$recommend_award_sum = isset($_REQUEST['recommend_award_sum'])?floatval($_REQUEST['recommend_award_sum']):0;
	
	//代金券
	$where = "rank_id=$rank_id AND rc_type='recommend_give_user_bonus'";
	if($recommend_award_type=='recommend_give_user_bonus')
	{
		$recommend_award_deadline = isset($_REQUEST['recommend_award_deadline'])?$_REQUEST['recommend_award_deadline']:0;
		//代金券的有效期
		
		//是否分配推荐服务费
	    $give_user_bonus_cfg = array(
				'is_enabled'=>$recommend_award_sum>0?1:0,
				'value'=>$recommend_award_sum,
				'deadline'=>$recommend_award_deadline
		);
		
		//判断是否有记录
		$sql = " SELECT id FROM bx_user_rank_config WHERE $where";
		if($GLOBALS['db']->getOne($sql))
		{
			$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank_config'), $give_user_bonus_cfg, "UPDATE",$where);  
		}
		else
		{
			//是否分配推荐服务费
		    $give_user_bonus_cfg = array(
					'rank_id'=>$rank_id,
					'rc_type'=>'recommend_give_user_bonus',
					'is_enabled'=>$recommend_award_sum>0?1:0,
					'value'=>$recommend_award_sum,
					'deadline'=>$recommend_award_deadline
			);
			$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank_config'), $give_user_bonus_cfg, "INSERT");  
			
			
		}
		
		
	}
	else //禁用
	{
		$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank_config'), array('is_enabled'=>0), "UPDATE",$where);
		
	}
	
	
	
	//金币
	$where = "rank_id=$rank_id AND rc_type='recommend_give_service_money'";
	if($recommend_award_type=='recommend_give_service_money')
	{
		//是否分配推荐服务费
	    $give_service_money_cfg = array(
				'is_enabled'=>$recommend_award_sum>0?1:0,
				'value'=>$recommend_award_sum,
		);
		
		
				//判断是否有记录
		$sql = " SELECT id FROM bx_user_rank_config WHERE $where";
		if($GLOBALS['db']->getOne($sql))
		{
			$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank_config'), $give_service_money_cfg, "UPDATE",$where);  
			
		}
		else
		{
			//是否分配推荐服务费
		    $give_service_money_cfg = array(
					'rank_id'=>$rank_id,
					'rc_type'=>'recommend_give_service_money',
					'is_enabled'=>$recommend_award_sum>0?1:0,
					'value'=>$recommend_award_sum,
			);
			$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank_config'), $give_service_money_cfg, "INSERT");  
			
		}
		
		
	}
	else
	{
		$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_rank_config'), array('is_enabled'=>0), "UPDATE",$where);
		
	}
    
	
}

?>