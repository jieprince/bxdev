<?php


/**
 * ECSHOP 管理员信息以及权限管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: privilege.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require (dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH.'baoxian/source/function_debug.php');
include_once(ROOT_PATH . 'admin/includes/lib_users.php');
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/account_log.php');
include_once(ROOT_PATH . 'includes/class/distrbutor.class.php');

$action = isset($_REQUEST['act'])?$_REQUEST['act']:'';
$smarty->assign('action',      $action);
/* 渠道列表 */
if($action == 'list')
{
    /* 检查权限 */
    admin_priv('organization_list');
	
	$user_list = insurance_company_list();	
	
	$ur_here = "渠道列表";
	
	//子渠道列表
	if($_REQUEST['parent_institution_id'])
	{
		$sql = "SELECT d_name FROM bx_distributor WHERE d_uid='$_REQUEST[parent_institution_id]'";	
		$d_name = $db->getOne($sql);
		if($d_name)
		{
			$ur_here=$d_name."-子渠道列表";
		}
		
	}
	
	
    $smarty->assign('user_list',    $user_list['user_list']);
    $smarty->assign('ur_here',    $ur_here);
    $smarty->assign('filter',       $user_list['filter']);
    $smarty->assign('record_count', $user_list['record_count']);
    $smarty->assign('page_count',   $user_list['page_count']);
    $smarty->assign('full_page',    1);
    $smarty->assign('action_link',  array('text' =>'添加渠道', 'href'=>'distributor.php?act=organization_add'));
    $smarty->assign('sort_user_id', '<img src="images/sort_desc.gif">');
    $smarty->assign('distributor_id', $_REQUEST['distributor_id']); 
	//echo "<pre>";print_r($user_list);
    assign_query_info();
    $smarty->display('insurance_company_list.htm');
}

/* 渠道列表 */
elseif($action == 'organization_list_query')
{
	
    $user_list = organization_list();
    $smarty->assign('user_list',    $user_list['user_list']);
    $smarty->assign('filter',       $user_list['filter']);
    $smarty->assign('record_count', $user_list['record_count']);
    $smarty->assign('page_count',   $user_list['page_count']);

    $sort_flag  = sort_flag($user_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('organization_list.htm'), '', array('filter' => $user_list['filter'], 'page_count' => $user_list['page_count']));
}

/* start 渠道--通过渠道ID获取渠道下的会员 */
elseif($action == 'user_list_by_organid')
{
	$organ_id = $_REQUEST['id'];
	$data = user_list_by_organid($_REQUEST['id']);
	
	$sql = "SELECT d_name FROM bx_distributor WHERE d_uid='$organ_id'";	
	$d_name = $db->getOne($sql);
    $smarty->assign('user_list',   $data['user_list']);
    $smarty->assign('ur_here',    $d_name."-会员列表");
    $smarty->assign('filter',       $data['filter']);
    $smarty->assign('record_count', $data['record_count']);
    $smarty->assign('page_count',   $data['page_count']);
    $smarty->assign('full_page',    1);
    //$smarty->assign('action_link',  array('text' =>$_LANG['04_users_add'], 'href'=>'users.php?act=organization_add'));
    $smarty->assign('sort_user_id', '<img src="images/sort_desc.gif">');
    $smarty->assign('distributor_id', $_REQUEST['distributor_id']); 
    assign_query_info();
    $smarty->display('user_list_by_organ.htm');

}
/* 渠道列表 */
elseif($action == 'user_list_by_organid_query')
{
    $user_list = user_list_by_organid($_REQUEST['id']);
    $smarty->assign('user_list',    $user_list['user_list']);
    $smarty->assign('filter',       $user_list['filter']);
    $smarty->assign('record_count', $user_list['record_count']);
    $smarty->assign('page_count',   $user_list['page_count']);

    $sort_flag  = sort_flag($user_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('user_list_by_organ.htm'), '', array('filter' => $user_list['filter'], 'page_count' => $user_list['page_count']));
}
/* end 渠道--通过渠道ID获取渠道下的会员 */

/* 禁用或者启用会员 */
elseif($action == 'disable_enabled_salesman')
{
    /* 用户信息 */
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
	$is_disable = $_REQUEST['is_disable'];
    $res = $user_obj->disableOrEnabledSalesman($is_disable);
 	if($res)
 	{
 		$datas = json_encode(array('code'=>0,'msg'=>'操作成功!')); //如果用户不存在,返回-2
	 	die($datas);
 	}
}
//添加渠道页面
elseif ($action == 'organization_add')
{
    /* 检查权限 */
    admin_priv('organization_add');
		
	$sql="SELECT * FROM bx_region  WHERE parent_id=1";
	$provinces = $db->getAll($sql);
	
	include_once(ROOT_PATH . 'includes/lib_goods.php');
	$categorie = get_categories_tree(0);
	$smarty->assign('categorie',   $categorie );
	
     /* 模板赋值 */
    $smarty->assign('ur_here',     $_LANG['admin_add']);
    $smarty->assign('action_link', array('href'=>'distributor.php?act=organization_list', 'text' => $_LANG['admin_list']));
    $smarty->assign('save_type',    'insert');
	$smarty->assign('country_list', get_regions());
	$smarty->assign('provinces',$provinces);
	$smarty->assign('form_action', 'insert');
	$smarty->assign('click', 'organinfo');
	
	$smarty->assign('organ_list',get_distributor());
	
    /* 显示页面 */
    assign_query_info();
    $smarty->display('organization_info.htm');
}

//编辑渠道页面
elseif ($action == 'organization_edit')
{
    /* 检查权限 */
    admin_priv('organization_edit');
	
	$user_id = $_REQUEST['user_id'];
	
	
	//add yes123 2015-07-08  会员的基本信息 start
	$res = user_base_info($user_id);
	$user = $res['user'];
	$province_list = $res['province_list'];
	$city_list = isset($res['city_list'])?$res['city_list']:null;
	$district_list = isset($res['district_list'])?$res['district_list']:null;
    $smarty->assign('province_list',      $province_list);
    $smarty->assign('city_list',          $city_list);
    $smarty->assign('district_list',      $district_list);
     $smarty->assign('special_ranks',    get_rank_list(true));
    $smarty->assign('user',             $user);
	$smarty->assign('form_action', 'update');
	//add yes123 2015-07-08  会员的基本信息 end
	
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	include_once(ROOT_PATH . 'includes/lib_goods.php');
	$user_obj = new User;
	$res = $user_obj->organInfo($user_id);
	$smarty->assign('country_list', get_regions());
	$categorie = get_categories_tree(0);
	$smarty->assign('categorie',   $categorie );
	 
	$sql="SELECT * FROM bx_region  WHERE parent_id=1";
	$provinces = $db->getAll($sql);
	$smarty->assign('provinces', $provinces);//省
	$smarty->assign('city', $res['city']);//市
	$smarty->assign('save_type', 'update');
	$smarty->assign('user_id', $user_id);
	$smarty->assign('district', $res['district']);//县
	$smarty->assign('distributor_info', $res['distributor_info']); //渠道基本信息
	
    $smarty->assign('ur_here',     $_LANG['admin_add']);
    $smarty->assign('action_link', array('href'=>'distributor.php?act=organization_list', 'text' => $_LANG['admin_list']));
    $smarty->assign('save_type',    'update');
    $smarty->assign('form_action', 'update');
   
    $smarty->assign('organ_list',get_distributor());
    
    
    
    $click = isset($_REQUEST['click'])?$_REQUEST['click']:'';
    $smarty->assign('click', $click);
    $res = organ_ipa_rate_config_list($user_id);	
    $smarty->assign('config_list',    $res['config_list']);

    /* 显示页面 */
    assign_query_info();
    $smarty->display('organization_info.htm');
}

//新增或者修改渠道
elseif ($action == 'organization_save')
{
	//上级渠道
	$institution_id = isset($_REQUEST['institution_id'])?trim($_REQUEST['institution_id']):0;
	
	$d_mobilePhone = isset($_REQUEST['mobilePhone'])?trim($_REQUEST['mobilePhone']):"";
	$password = isset($_REQUEST['password'])?trim($_REQUEST['password']):"";
	$d_name = isset($_REQUEST['d_name'])?trim($_REQUEST['d_name']):"";
	$d_identificationcode = isset($_REQUEST['identificationcode'])?trim($_REQUEST['identificationcode']):"";
	$d_type = isset($_REQUEST['d_type'])?trim($_REQUEST['d_type']):"";
	//$d_qualificationNumber = isset($_REQUEST['qualificationNumber'])?$_REQUEST['qualificationNumber']:"";
	$d_contacts = isset($_REQUEST['d_contacts'])?trim($_REQUEST['d_contacts']):"";
	$d_email = isset($_REQUEST['email'])?trim($_REQUEST['email']):"";
	$province = isset($_REQUEST['province'])?trim($_REQUEST['province']):0;
	$city = isset($_REQUEST['city'])?trim($_REQUEST['city']):0;
	$district = isset($_REQUEST['district'])?trim($_REQUEST['district']):0;
	$d_address = isset($_REQUEST['d_address'])?trim($_REQUEST['d_address']):"";
	$save_type = isset($_REQUEST['save_type'])?trim($_REQUEST['save_type']):"";
	$d_institution_type = isset($_REQUEST['d_institution_type'])?trim($_REQUEST['d_institution_type']):"";
	
	$d_zone=$province.",".$city.",".$district;
	
	
	//配置信息
	$organ_cfg = array();
	
	$organ_cfg['website_name'] = isset($_REQUEST['website_name'])?trim($_REQUEST['website_name']):"";
	$organ_cfg['domain_name'] = isset($_REQUEST['domain_name'])?trim($_REQUEST['domain_name']):0;
	$cat_ids = isset($_REQUEST['cat_ids'])?$_REQUEST['cat_ids']:null;
	$organ_cfg['appId'] = isset($_REQUEST['appId'])?trim($_REQUEST['appId']):'';
	$organ_cfg['appSecret'] = isset($_REQUEST['appSecret'])?trim($_REQUEST['appSecret']):"";
	$organ_cfg['service_phone'] = isset($_REQUEST['service_phone'])?trim($_REQUEST['service_phone']):"";
	$organ_cfg['service_email'] = isset($_REQUEST['service_email'])?trim($_REQUEST['service_email']):"";
	$organ_cfg['service_qq'] = isset($_REQUEST['service_qq'])?trim($_REQUEST['service_qq']):"";
	$organ_cfg['website_logo'] = isset($_REQUEST['website_logo'])?trim($_REQUEST['website_logo']):"";
	
	
	if(!empty($cat_ids))
	{
		$cat_ids= implode(',',$cat_ids); 
		$organ_cfg['cat_ids']=$cat_ids;
		ss_log('cat_ids:'.$cat_ids);
	}
	
	
	$$user_info = array();
	$user_info['Province'] = $province;
	$user_info['city'] = $city;
	$user_info['district'] = $district;
	$user_info['address'] = $d_addres;
	
	
	$user_rank = get_user_rank_info(0,ORGANIZATION_USER);
	$rank_id = $user_rank['rank_id'];
	
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user = new User;
	if($save_type=='insert')
	{
		//校验唯一
		$res = $user->validate_data();
		if($res)
		{
			$datas = json_encode(array('code'=>1,'msg'=>$res)); 
			die($datas);
		}
		
		$reg_time =time();
		$md5_password=md5($password);
		//1.插入users表
		$sql = "INSERT INTO ".$ecs->table('users')." (user_name,password,email,mobile_phone,reg_time,institution_id,user_rank) ".
	           " VALUES ('$d_mobilePhone','$md5_password','$d_email', '$d_mobilePhone', '$reg_time','$institution_id','".$rank_id."')";
		$r = $GLOBALS['db']->query($sql);
		
		
		if($r)
		{
			$organ_uid = $GLOBALS['db']->insert_id(); //新渠道ID
			if($organ_uid)
			{
				//user_info 表
				$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_info'), $user_info, 'INSERT');	
				
				//2.插入bx_distributor表
				$sql = "INSERT INTO ". $GLOBALS['ecs']->table('distributor') ." (d_uid, d_name, d_identificationcode, d_type, d_contacts, d_mobilePhone, d_email, d_zone, d_address,d_institution_type) " .
					   " VALUES ('$organ_uid', '$d_name', '$d_identificationcode', '$d_type', '$d_contacts', '$d_mobilePhone', '$d_email', '$d_zone', '$d_address','$d_institution_type')";
				if($GLOBALS['db']->query($sql))
				{
					$organ_cfg['d_uid'] = $organ_uid;
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('distributor_cfg'), $organ_cfg, 'INSERT');
					$datas = json_encode(array('code'=>0,'msg'=>'添加成功!')); //如果用户不存在,返回-2
	 				die($datas);
				}
				
				
			}
			
		}
		
	}
	
	if($save_type=='update')
	{
		$organ_uid = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
		
		if(!$organ_uid)
		{
			return;
		}
		$organ_cfg['d_uid'] = $organ_uid;
		
		
		//校验唯一
		$res = $user->validate_data($organ_uid);
		if($res)
		{
			$datas = json_encode(array('code'=>1,'msg'=>$res)); 
			die($datas);
		}
		
		$update_time =time();
		$md5_password=md5($password);
		//1.更新users表
		$sql="UPDATE ".$ecs->table('users'). "SET user_name='$d_mobilePhone', email='$d_email',mobile_phone='$d_mobilePhone',last_time='$update_time',institution_id='$institution_id' WHERE user_id=".$organ_uid ;
		$r = $GLOBALS['db']->query($sql);
		
		
		//判断是否有密码，有的话，重置密码
		if($password)
		{
			$md5_password=md5($password);
			$sql="UPDATE ".$ecs->table('users'). "SET password='$md5_password', ec_salt='' WHERE user_id=".$organ_uid ;
			$r = $GLOBALS['db']->query($sql);
		}
		
		//先查询是否有扩展表
		
		$sql="SELECT * FROM bx_distributor  WHERE  d_uid=".$organ_uid;
		$row = $db->getRow($sql);
		if(empty($row))
		{
			//2.插入bx_distributor表
			$sql = "INSERT INTO ". $GLOBALS['ecs']->table('distributor') ." (d_uid, d_name, d_identificationcode, d_type, d_contacts, d_mobilePhone, d_email, d_zone, d_address,d_institution_type) " .
				   " VALUES ('$organ_uid', '$d_name', '$d_identificationcode', '$d_type',  '$d_contacts', '$d_mobilePhone', '$d_email', '$d_zone', '$d_address','$d_institution_type')";
			$r = $GLOBALS['db']->query($sql);
		}
		else
		{
			//2.更新bx_distributor表
			$sql="UPDATE ".$ecs->table('distributor'). "SET
					  d_name='$d_name', d_identificationcode='$d_identificationcode',d_type='$d_type',
					  d_mobilePhone='$d_mobilePhone',d_contacts='$d_contacts',d_address='$d_address',
					  d_email='$d_email',d_zone='$d_zone',d_institution_type='$d_institution_type'
					  WHERE d_uid=$organ_uid";
			
			$r = $GLOBALS['db']->query($sql);
		}
		
		
		$sql="SELECT * FROM bx_user_info  WHERE  uid=".$organ_uid;
		$res = $db->getRow($sql);
		if(empty($res))
		{
			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_info'), $user_info, 'INSERT');	
		}
		else
		{
			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_info'), $user_info, 'UPDATE', 'uid = ' .$organ_uid);
		}

		
		if($r)
		{
			$sql="SELECT * FROM ".$GLOBALS['ecs']->table('distributor_cfg')."  WHERE  d_uid=".$organ_uid;
			$row = $db->getRow($sql);
			if(empty($row))
			{
				$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('distributor_cfg'), $organ_cfg, 'INSERT');
				
			}
			else
			{
				$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('distributor_cfg'), $organ_cfg, 'UPDATE', 'd_uid = ' .$organ_uid);
			}
			
			$datas = json_encode(array('code'=>0,'msg'=>'更新成功!')); 
	 		die($datas);
		}
		
	}

}
/* 渠道--渠道产品配置列表 */
elseif($action == 'organ_ipa_rate_config_list')
{
	$res = organ_ipa_rate_config_list();	
	$d_name = isset($_REQUEST['d_name'])?trim($_REQUEST['d_name']):"";
	$organ_id = $_REQUEST['institution_id'];
    $smarty->assign('config_list',    $res['config_list']);
    
    
    if($d_name)
  	{
  		$ur = "<a href=distributor.php?act=organ_ipa_rate_config_list&institution_id=$organ_id&d_name=$d_name>$d_name</a>";
  	}
    $smarty->assign('ur_here',    $ur."-渠道产品配置列表");
    //<a href="users.php?act=organ_ipa_rate_config_add&organ_id={$user.user_id}&d_name={$user.d_name}&user_name={$user.user_name}&save_type=insert">
    $add_url ="distributor.php?act=organ_ipa_rate_config_add&organ_id=$organ_id&d_name=$d_name&save_type=insert";
    $smarty->assign('filter',       $res['filter']);
    $smarty->assign('record_count', $res['record_count']);
    $smarty->assign('page_count',   $res['page_count']);
    $smarty->assign('full_page',    1);
    $smarty->assign('d_name',    $d_name);
    $smarty->assign('action_link',  array('text' =>'添加配置', 'href'=>$add_url));
    $smarty->assign('sort_user_id', '<img src="images/sort_desc.gif">');
    $smarty->assign('distributor_id', $_REQUEST['distributor_id']); 
	//echo "<pre>";print_r($user_list);
    assign_query_info();
    $smarty->display('organ_ipa_rate_config_list.htm');
}

/* 渠道--渠道产品配置列表  query*/
elseif($action == 'organ_ipa_rate_config_list_query'){
	
    $res = organ_ipa_rate_config_list();
    $smarty->assign('config_list',    $res['config_list']);
    $smarty->assign('filter',       $res['filter']);
    $smarty->assign('record_count', $res['record_count']);
    $smarty->assign('page_count',   $res['page_count']);

    $sort_flag  = sort_flag($res['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('organ_ipa_rate_config_list.htm'), '', array('filter' => $res['filter'], 'page_count' => $res['page_count']));
}

/* 渠道--渠道产品配置列表  添加配置*/
elseif($action == 'organ_ipa_rate_config_add'){
  
  
  $id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
  $organ_id = isset($_REQUEST['organ_id'])?$_REQUEST['organ_id']:0;
  $d_name = isset($_REQUEST['d_name'])?$_REQUEST['d_name']:"";
  $user_name = isset($_REQUEST['user_name'])?$_REQUEST['user_name']:"";
  $save_type = $_REQUEST['save_type'];
  $ur_here = "";
  if(!$d_name)
  {
  	$d_name = $user_name;
  }
  if($d_name)
  {
  	
  	$ur = "<a href=distributor.php?act=organ_ipa_rate_config_list&institution_id=$organ_id&d_name=$d_name>$d_name</a>";
  	
  }
  
  if($save_type=='edit')
  {
  	  admin_priv('organ_ipa_rate_config_edit'); 
  	  $ur_here=$ur."-修改渠道费率配置";
  	  $smarty->assign('id',  $id);
  	  $sql="SELECT organization.*,oirc.*,ipa.attribute_name,ipa.rate_total FROM bx_organ_ipa_rate_config oirc 
		  LEFT JOIN (SELECT u.user_id,u.user_name,d.d_name,d.d_identificationcode FROM bx_users u LEFT JOIN bx_distributor d ON u.user_id=d.d_uid WHERE is_institution=2) AS organization ON oirc.institution_id=organization.user_id
		  LEFT JOIN t_insurance_product_attribute ipa ON ipa.attribute_id=oirc.attribute_id WHERE id=$id";
	  $organ_ipa_rate_config = $db->getRow($sql);
	  
	  //计算可用的渠道佣金
	  $usable_rate = $organ_ipa_rate_config['rate_total']-$organ_ipa_rate_config['rate_recommend']-$organ_ipa_rate_config['rate_myself'];
	  $smarty->assign('usable_rate',  "最大可设置".$usable_rate);
	  $smarty->assign('rate_total',  $organ_ipa_rate_config['rate_total']);
	  $smarty->assign('organ_ipa_rate_config',  $organ_ipa_rate_config);

  }
	if($save_type=='insert')
	{
		 admin_priv('organ_ipa_rate_config_add'); 
		$ur_here=$ur."-新增渠道费率配置";
	}
	elseif($save_type=='edit')
	{
		 admin_priv('organ_ipa_rate_config_add'); 
		$ur_here=$ur."-编辑渠道费率配置";
	}
 

	$smarty->assign('ur_here',  $ur_here); 
	$smarty->assign('d_name',  $d_name); 
	$smarty->assign('organ_id',  $organ_id);
	$smarty->assign('save_type',  $save_type);
	$smarty->display('organ_ipa_rate_config_info.htm');
}
/* 渠道--渠道产品配置列表  插入或者修改配置*/
elseif($action == 'organ_ipa_rate_config_save'){
	
	$organ_ipa_rate_config['attribute_id'] = $attribute_id = $_REQUEST['attribute_id'];	
	$attribute_name = $_REQUEST['attribute_name'];
	$organ_ipa_rate_config['institution_id'] = $organ_id = $_REQUEST['organ_id'];
	
	$organ_ipa_rate_config['rate_myself'] = $rate_myself = $_REQUEST['rate_myself'];
	$organ_ipa_rate_config['rate_recommend'] = $rate_recommend = $_REQUEST['rate_recommend'];
	$organ_ipa_rate_config['rate_organization'] = $rate_organization = $_REQUEST['rate_organization'];
	
	$save_type = $_REQUEST['save_type'];
	$update_to_child_organ = $_REQUEST['update_to_child_organ'];
	
	$organ_ipa_rate_config['myself_enabled'] = $myself_enabled = $_REQUEST['myself_enabled'];
	$organ_ipa_rate_config['recommend_enabled'] = $recommend_enabled = $_REQUEST['recommend_enabled'];
	$organ_ipa_rate_config['organization_enabled'] = $organization_enabled = $_REQUEST['organization_enabled'];
	
	//合作伙伴代码
	$organ_ipa_rate_config['partner_code'] = $partner_code = trim($_REQUEST['partner_code']);
	
	
	
	//校验数据
	$sql="SELECT rate_total,rate_myself,rate_recommend,rate_organization FROM t_insurance_product_attribute  WHERE attribute_id=".$attribute_id;
	ss_log('act organ_ipa_rate_config_save sql'.$sql);
	$row = $db->getRow($sql);
	if(($row['rate_myself']+$row['rate_recommend']+$rate_organization)>$row['rate_total'])
	{
		$msg="渠道服务费率($rate_organization)+个人服务费率($row[rate_myself])+推荐服务费($row[rate_recommend])率不能大于总费率($row[rate_total])";
		$datas = json_encode(array('code'=>1,'msg'=>$msg)); //如果用户不存在,返回-2
		die($datas);
	}
	
	
	$sql = "SELECT d_name FROM bx_distributor WHERE d_uid='$organ_id'";
	$d_name = $db->getOne($sql);
	
	$msg='';
	
	$url = "distributor.php?act=organization_edit&user_id=$organ_id&click=product";
	if($save_type=='insert')
	{
		//校验此产品是否已添加过
		$sql="SELECT id FROM bx_organ_ipa_rate_config  WHERE attribute_id=$attribute_id AND institution_id=$organ_id ";
		if($db->getOne($sql))
		{
			$link[] = array('text' => $_LANG['go_back'], 'href'=>'distributor.php?act=organ_ipa_rate_config_add&organ_id='.$organ_id.'&d_name='.$d_name.'&save_type=insert');
			sys_msg("添加失败<script>alert('添加失败，此产品已经添加过')</script>", 0, $link);
		}
		
		$res = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('organ_ipa_rate_config'), $organ_ipa_rate_config, 'INSERT');	
		if($res)
		{
			$link[] = array('text' => $_LANG['go_back'], 'href'=>'distributor.php?act=organ_ipa_rate_config_list&institution_id='.$organ_id);
			$msg = "添加成功<script>alert('添加成功');window.location.href='$url'; </script>";
			
		}	
		
		
		
	}
	elseif($save_type=='edit')
	{
		$id = $_REQUEST['id'];
		$res = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('organ_ipa_rate_config'), $organ_ipa_rate_config, 'UPDATE', 'id = ' .$id);

		if($res)
		{
			$link[] = array('text' => $_LANG['go_back'], 'href'=>'distributor.php?act=organ_ipa_rate_config_list&institution_id='.$organ_id);
			$msg="更新成功<script>alert('更新成功');window.location.href='$url'; </script>";
		}		
		
	}
	
	//往子渠道插入新配置
	if($update_to_child_organ)
	{
		insert_child_organ_config($organ_ipa_rate_config,$save_type);
	}
	
	
	sys_msg($msg, 0, $link);
	
	
	
	exit;
}

/*------------------------------------------------------ */
//-- //add yes123 2015-04-28  脱离渠道关系
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'remove_institution')
{
	//modify yes123 2015-01-07 修改解除推荐关系日志 和 跳转url
    /* 检查权限 */
    admin_priv(action_list());
    
	$sql = "SELECT user_name,institution_id FROM " . $ecs->table('users') . " WHERE user_id = '" . $_GET['id'] . "'";
    $user = $db->getRow($sql);
	
	
    $sql = "UPDATE " . $ecs->table('users') . " SET institution_id = 0 WHERE user_id = '" . $_GET['id'] . "'";
    $db->query($sql);
	
    /* 记录管理员操作 */
    $sql = "SELECT user_name FROM " . $ecs->table('users') . " WHERE user_id = '" . $user['institution_id'] . "'";
    $institution_user_name = $db->getOne($sql);
    
    
	$content="编辑：解除".$user['user_name']."的渠道关系，原来的渠道是:".$institution_user_name;
    //记录管理员操作 
    admin_log2($content,'warn');
    /* 提示信息 */
    $link[] = array('text' => $_LANG['go_back'], 'href'=>'distributor.php?act=edit&id='.$_GET['id']);
    sys_msg(sprintf($_LANG['update_success'], $username), 0, $link);
}

//上传图片
elseif ($action == 'upload_img')
{   
	$admin_id = $_SESSION['admin_id'];
	
	$img_type = isset($_REQUEST['img_type'])?$_REQUEST['img_type']:1; //0头像,1为身份证正面,2为身份证反面  3为证书正面
	$max_size = $img_type==0?2048000:5120000;
	$path_type = $_REQUEST['path_type'];
	$path_arr = array(
		'4'=>'../images/website_logo',
	);
	$save_path = $path_arr[$path_type];
	
	
	include_once(ROOT_PATH . 'includes/class/imageUtils.class.php');
	$arr = ImageUtils::imageUpload($save_path,$admin_id,$max_size);

	die( json_encode($arr));
} 


/* 渠道--渠道产品配置列表  插入或者修改配置*/
elseif($action == 'get_attribute_by_name'){
	$res = attribute_list();
    $smarty->assign('filter',       $res['filter']);
    $smarty->assign('record_count', $res['record_count']);
    $smarty->assign('page_count',   $res['page_count']);
	$smarty->assign('attribute_list',  $res['attribute_list']);
	$smarty->assign('full_page',    1);
	$smarty->display('dialog_attribute_list.htm');
    exit;
}

/* 渠道--渠道产品配置列表  插入或者修改配置*/
elseif($action == 'get_attribute_by_name_query'){
	
	$res = attribute_list();
    $smarty->assign('filter',       $res['filter']);
    $smarty->assign('record_count', $res['record_count']);
    $smarty->assign('page_count',   $res['page_count']);
	$smarty->assign('attribute_list',  $res['attribute_list']);
 	make_json_result($smarty->fetch('dialog_attribute_list.htm'), '', array('filter' => $res['filter'], 'page_count' => $res['page_count']));
}

/* 渠道--渠道产品配置列表  通过ID险种*/
elseif($action == 'get_attribute_by_id'){
	$attribute_id = isset($_REQUEST['attribute_id'])?$_REQUEST['attribute_id']:0;
	$sql="SELECT rate_total,rate_myself,rate_recommend,rate_organization FROM t_insurance_product_attribute  WHERE attribute_id=".$attribute_id;
	$row = $db->getRow($sql);
	if($row)
	{
	   $datas = json_encode(array('code'=>0,'data'=>$row));
	  
	}
	else
	{
	   $datas = json_encode(array('code'=>1));	
	}
	
	die($datas);
}

//移除配置
elseif($action == 'rate_config_remove')
{
	 admin_priv('rate_config_remove');
	$attribute_id= $_REQUEST['attribute_id'];
	$institution_id= $_REQUEST['institution_id'];
	$remove_all = $_REQUEST['remove_all'];
	$res = rate_config_remove($attribute_id,$institution_id,$remove_all);
	if($res)
	{
		$datas = json_encode(array('code'=>0,'msg'=>'删除成功！'));	
	}
	else
	{
		$datas = json_encode(array('code'=>1,'msg'=>'删除失败！'));	
	}
		
	
	die($datas);
	
}

function rate_config_remove($attribute_id=0,$institution_id=0,$remove_all=0)
{

	$sql="DELETE FROM ".$GLOBALS['ecs']->table('organ_ipa_rate_config'). " WHERE attribute_id='$attribute_id' AND institution_id='$institution_id' ";
	$GLOBALS['db']->query($sql);
	
	//是否删除子渠道
	if($remove_all)
	{
		//获取子渠道
		
		$user_rank = get_user_rank_info(0,ORGANIZATION_USER);
		
		$sql = " SELECT user_id FROM ".$GLOBALS['ecs']->table('users'). " WHERE institution_id='$institution_id' AND user_rank='".$user_rank['rank_id']."'";
		$organ_ipa_rate_config_list = $GLOBALS['db']->getAll($sql);
		foreach ( $organ_ipa_rate_config_list as $key => $organ_ipa_rate_config ) 
		{
       		rate_config_remove($attribute_id,$organ_ipa_rate_config['user_id'],$remove_all);
		}

	}
	
	return true;
}


function user_list_by_organid($id)
{
	$result = get_filter();
    if ($result === false)
    {
        /* 过滤条件 */
        
        $filter['id'] = $id;
        $filter['user_name'] = isset ($_REQUEST['user_name']) ? trim($_REQUEST['user_name']) : "";
		$filter['real_name'] = isset ($_REQUEST['real_name']) ? trim($_REQUEST['real_name']) : "";
		$filter['start_time'] = isset ($_REQUEST['start_time']) ? trim($_REQUEST['start_time']) : "";
		$filter['end_time'] = isset ($_REQUEST['end_time']) ? trim($_REQUEST['end_time']) : "";
		$filter['check_status'] = isset ($_REQUEST['check_status']) ? trim($_REQUEST['check_status']) : "";
		$filter['is_disable'] = isset ($_REQUEST['is_disable']) ? trim($_REQUEST['is_disable']) : 0;
		$filter['sort_by'] = isset ($_REQUEST['sort_by']) ? trim($_REQUEST['sort_by']) : "policy_num";
		$filter['sort_order'] = isset ($_REQUEST['sort_order']) ? trim($_REQUEST['sort_order']) : "DESC";
        
        
        $user_rank = get_user_rank_info(0,ORGANIZATION_USER);
        $rank_id = $user_rank['rank_id'];
        
        
        //查询渠道的名称
        $sql = "SELECT d_name FROM bx_distributor WHERE d_uid='$id'";
        $d_name = $GLOBALS['db']->getOne($sql);
        
        $where_sql = " WHERE u.institution_id=$filter[id] ";
		$policy_where=" WHERE policy_status='insured' "; //保单日期查询条件
		$$sort='';
		if($filter['sort_by']=='total_premium')
		{
			$sort=" ORDER BY total_premium_user.total_premium ";
		}
		elseif($filter['sort_by']=='policy_num')
		{
			$sort=" ORDER BY total_premium_user.policy_num ";
		}
		elseif($filter['sort_by'])
		{
			$sort =" ORDER BY u.$filter[sort_by] ";
		}
		
		
		if($filter['user_name']!=''){
				
			$where_sql.=" AND u.user_name LIKE '%$filter[user_name]%'";
		}
		
		if($filter['real_name']!=''){
				
			$where_sql.=" AND u.real_name LIKE '%$filter[real_name]%'";
		}
		
		if($filter['start_time']){
			$policy_where.=" AND dateline>".strtotime($filter['start_time']);
		}
		
		if($filter['end_time']){
			$policy_where.=" AND dateline<".strtotime($filter['end_time']);
		}
		
		
		if($filter['check_status']){
			$where_sql.=" AND u.check_status='$filter[check_status]'";
		}
		
		if($filter['is_disable']){
			if($filter['is_disable']==2){
				$where_sql.=" AND u.is_disable=0 ";
			}
			else
			{
				$where_sql.=" AND u.is_disable='$filter[is_disable]'";
			}	
		}
		
		
		$record_count_sql = "SELECT count(u.user_id) FROM " .$GLOBALS['ecs']->table('users')." AS u ";
		$filter['record_count'] = $GLOBALS['db']->getOne($record_count_sql.$where_sql." AND u.user_rank !='".$rank_id."'");

        /* 分页大小 */
        $filter = page_and_size($filter);
		
		global $_LANG,$check_status_list; 
        $sql  = "SELECT
			  u.user_id,
			  u.user_name,
			  u.real_name, 
			  u.is_disable,
			  u.check_status,
			  u.reg_time,
			  total_premium_user.policy_num,
			  total_premium_user.total_premium,
			  account_log.service_money
			FROM bx_users u 
			  LEFT JOIN (SELECT agent_uid,COUNT(policy_id) AS policy_num ,SUM(total_premium) AS total_premium
			             FROM t_insurance_policy $policy_where GROUP BY agent_uid) AS total_premium_user
			    ON total_premium_user.agent_uid = u.user_id
			  LEFT JOIN (SELECT SUM(user_money) AS service_money,user_id
			             FROM bx_account_log  WHERE incoming_type='$_LANG[ji_gou]') AS account_log
			    ON account_log.user_id = u.user_id
			$where_sql AND u.user_rank !='".$rank_id."' $sort $filter[sort_order] LIMIT $filter[start] , $filter[page_size]";
		
		
		ss_log("user_list_by_organid:".$sql);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

    $user_list = $GLOBALS['db']->getAll($sql);
    foreach ( $user_list as $key => $user ) 
	{
	   $user_list[$key]['reg_time'] = date('Y-m-d H:i',$user['reg_time']);;
       $user_list[$key]['d_name'] = $d_name;

       if(!$user['policy_num'])
       {
       		$user_list[$key]['policy_num'] = 0;
       }
       
       if(!$user['total_premium'])
       {
       		$user_list[$key]['total_premium'] = 0;
       }
       
       if(!$user['service_money'])
       {
       		$user_list[$key]['service_money'] = 0;
       }
       
       $user_list[$key]['check_status'] = $check_status_list[$user['check_status']];
       
	}
    
    
    
    //start 查询子渠道
/*    $child_organ_sql = " SELECT user_id FROM bx_users u $where_sql AND u.user_type='".ORGANIZATION_USER."'";	       
	$child_organ_list = $GLOBALS['db']->getAll($child_organ_sql);     
	if(!empty($child_organ_list))
	{
		foreach ( $child_organ_list as $key => $child_organ ) {
       		$res = user_list_by_organid($child_organ['user_id']);
       		    
			if(!empty($res['user_list']))
			{
				foreach ( $res['user_list'] as $key => $value2 ) {
			       $user_list[] = $value2;
				}
			}
			
			$filter['record_count']+=$res['record_count'];
       		
		}
	}*/
	//end 查询子渠道
	
	
    $arr = array('user_list' => $user_list, 'filter' => $filter,
        'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    return $arr;
	
}

/*渠道列表*/
function insurance_company_list($organ_id=0)
{
    $result = get_filter();
    if ($result === false)
    {
        $filter['user_name'] = empty($_REQUEST['user_name']) ? '' : trim($_REQUEST['user_name']);
        $filter['real_name'] = empty($_REQUEST['real_name']) ? '' : trim($_REQUEST['real_name']);
        $filter['d_name'] = empty($_REQUEST['d_name']) ? '' : trim($_REQUEST['d_name']); //渠道名称
        $filter['d_identificationcode'] = empty($_REQUEST['d_identificationcode']) ? '' : trim($_REQUEST['d_identificationcode']); //企业组织代码
		$filter['mobile_phone'] = empty($_REQUEST['mobile_phone']) ? 0 : trim($_REQUEST['mobile_phone']);
        $filter['is_disable'] = empty($_REQUEST['is_disable']) ? "" : trim($_REQUEST['is_disable']);
        $filter['is_check'] = empty($_REQUEST['is_check']) ? 0 : trim($_REQUEST['is_check']);
        $filter['check_status'] = empty($_REQUEST['check_status']) ? "" : trim($_REQUEST['check_status']);
        $filter['sort'] = empty($_REQUEST['sort']) ? "reg_time" : trim($_REQUEST['sort']);
        $filter['order'] = empty($_REQUEST['order']) ? "DESC" : trim($_REQUEST['order']);
        
        if(!$organ_id)
        {
	        $filter['parent_institution_id'] = empty($_REQUEST['parent_institution_id']) ? "" : trim($_REQUEST['parent_institution_id']);
        }
        else
        {
        	 $filter['parent_institution_id'] = $organ_id;
        }
        
        
        $order_by ="";
        if($filter['sort']=='reg_time' || $filter['sort']=='check_status' || $filter['sort']=='is_disable')
        {
        	$order_by=" ORDER BY u.$filter[sort] $filter[order]";
        }
        elseif($filter['sort']=='user_num')
        {
        	$order_by=" ORDER BY institution_num_user.$filter[sort] $filter[order]";
        }
        elseif($filter['sort']=='total_premium' || $filter['sort']=='policy_num')
        {
        	$order_by=" ORDER BY total_premium_user.$filter[sort] $filter[order] ";
        	
        }elseif($filter['sort']=='ogran_money_total')
        {
        	$order_by=" ORDER BY ogran_income_total.$filter[sort] $filter[order] ";
        }elseif($filter['sort']=='d_institution_type') 
        {
        	$order_by=" ORDER BY d.$filter[sort] $filter[order] ";
        }elseif($filter['sort']=='child_organ_num') 
        {
        	$order_by=" ORDER BY u2.$filter[sort] $filter[order] ";
        }
        
        $user_rank = get_user_rank_info(0,ORGANIZATION_USER);
        
       	$ex_where = " WHERE u.user_rank= '".$user_rank['rank_id']."' ";
       	
       	if ($filter['user_name'])
        {
        	$ex_where .= " AND u.user_name LIKE '%$filter[user_name]%' ";
        }
       	
        if ($filter['real_name'])
        {
        	$ex_where .= " AND u.real_name LIKE '%$filter[real_name]%' ";
        }
        
        if ($filter['d_name'])
        {
        	$ex_where .= " AND d.d_name LIKE '%$filter[d_name]%' ";
        }
        
        if ($filter['d_identificationcode'])
        {
        	$ex_where .= " AND d.d_identificationcode LIKE '%$filter[d_identificationcode]%' ";
        }
        
        if ($filter['mobile_phone'])
        {
        	$ex_where .= " AND u.mobile_phone LIKE '%$filter[mobile_phone]%' ";
        }
        if ($filter['email'])
        {
        	$ex_where .= " AND d.email LIKE '%$filter[email]%' ";
        }
 
        if($filter['is_disable'])
        {
        	if($filter['is_disable']==2){
        		$ex_where .= " AND u.is_disable=0 ";
        	}
        	else
        	{
	        	$ex_where .= " AND u.is_disable=1 ";
        	}
        }
        
        if($filter['check_status'])
        {
        	$ex_where .= " AND u.check_status='$filter[check_status]'";
        }
        
        //add yes123 2015-05-15 查询子渠道
        if($filter['parent_institution_id'])
        {
        	 $ex_where .=" AND u.institution_id =" .$filter['parent_institution_id'];
        }
        
       $record_count_sql =  "SELECT count(u.user_id) FROM " . $GLOBALS['ecs']->table('users') . " u LEFT JOIN ".
       			$GLOBALS['ecs']->table('distributor'). " d ON u.user_id=d.d_uid $ex_where ";
       			
       	ss_log("organization_list record_count_sql:".$record_count_sql);		
       $filter['record_count'] = $GLOBALS['db']->getOne($record_count_sql);
		
        /* 分页大小 */
        $filter = page_and_size($filter);
		/***
		 * 修改时间: 2014/7/15
		 * 修改人: 鲍洪州
		 * 作用: SQL语句添加了查询is_cheack字段
		 */
        $sql = "SELECT u.*,d.* FROM " . $GLOBALS['ecs']->table('users') . " u LEFT JOIN ".$GLOBALS['ecs']->table('distributor'). " d " .
        		" ON u.user_id=d.d_uid $ex_where $order_by " .
                " LIMIT " . $filter['start'] . ',' . $filter['page_size'];
        
        global $_LANG;        
        $sql= "SELECT
			  u.user_id,u.check_status,u.is_disable,u.institution_id,u.user_name,u.reg_time,u.is_institution,
			  d.*,ogran_income_total.ogran_money_total
			FROM bx_users u
			  LEFT JOIN bx_distributor d
			    ON d.d_uid = u.user_id
			  LEFT JOIN (SELECT
				       user_id,
			               SUM(user_money)   AS ogran_money_total
			             FROM bx_account_log WHERE incoming_type='$_LANG[ji_gou]'
			             GROUP BY user_id) AS ogran_income_total
			    ON ogran_income_total.user_id = u.user_id
			    $ex_where $order_by  LIMIT ". $filter['start'] . ',' . $filter['page_size'];      
        ss_log("organization_list:".$sql);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    $user_list = $GLOBALS['db']->getAll($sql);
	//modify yes123 2015-01-07 显示推荐人
	foreach ( $user_list as $key => $user ) {
	   if($user['reg_time'])
	   {
	       $user_list[$key]['reg_time'] = date('Y-m-d H:i', $user['reg_time']); //add by wangcya, 20141128
	   }
       if($user['institution_id'])
       {
       		$sql = "SELECT d_name FROM bx_distributor WHERE d_uid = '$user[institution_id]'";
        	$user_list[$key]['parent_d_name'] = $GLOBALS['db']->getOne($sql);
       }
       
       $distrbutor_obj = new Distrbutor;
	   $data = $distrbutor_obj->get_accumulate_data_by_organ($user['user_id']);
       $user_list[$key]['user_num'] = empty($data['user_num'])?0:$data['user_num'];
       $user_list[$key]['policy_num'] = empty($data['policy_num'])?0:$data['policy_num'];
       $user_list[$key]['total_premium'] = empty($data['total_premium'])?0:$data['total_premium'];
       $user_list[$key]['child_organ_num'] = empty($data['organ_num'])?0:$data['organ_num']; 
       
       if(!$user['ogran_money_total'])
       {
       		$user_list[$key]['ogran_money_total']= 0;	
       }
       
       //判断是否有子渠道，有的话，也列出来
/*       if($filter['parent_institution_id'])
       {
       		$sql = "SELECT user_id FROM bx_users WHERE institution_id=$user[user_id] AND user_type='".ORGANIZATION_USER."'";
       		$temp_user_id = $GLOBALS['db']->getAll($sql);
       		if(!empty($temp_user_id))
       		{
	       		$res = organization_list($user['user_id']);
       			if(!empty($res['user_list']))
       			{
       				foreach ( $res['user_list'] as $key => $value ) {
       					$user_list[] = $value;
					}
					$filter['record_count']+=$res['record_count'];
					
       			}
       		}
       }*/
	}
	
	//add yes123 2014-11-27  当前页面url,点击受理完毕后用的	
	$current_url="";
	foreach ($filter AS $key => $value)
	{
		$current_url.="&".$key."=".$value;
			
	}
	

    $arr = array('user_list' => $user_list, 'filter' => $filter,
        'page_count' => $filter['page_count'], 'record_count' => $filter['record_count'],'current_url'=>$current_url);
    return $arr;
	
}



function organ_ipa_rate_config_list($organ_id=0) 
{
	$result = get_filter();
    if ($result === false)
    {
        /* 过滤条件 */
        $where_sql = " WHERE 1 ";
        
		$filter['institution_id']= isset($_REQUEST['institution_id'])?$_REQUEST['institution_id']:$organ_id;
		$filter['attribute_name']= isset($_REQUEST['attribute_name'])?$_REQUEST['attribute_name']:"";
		
		if($filter['institution_id'])
		{
			$where_sql.=" AND oirc.institution_id=".$filter['institution_id'];
		}
		
		if($filter['attribute_name'])
		{
			$where_sql.=" AND ipa.attribute_name LIKE '%$filter[attribute_name]%'";
		}
		
		
		$record_count_sql = "SELECT count(oirc.id) FROM bx_organ_ipa_rate_config oirc 
			LEFT JOIN (SELECT u.user_id,u.user_name,d.d_name,d.d_identificationcode FROM bx_users u LEFT JOIN bx_distributor d ON u.user_id=d.d_uid WHERE is_institution=2) AS organization ON oirc.institution_id=organization.user_id
			LEFT JOIN t_insurance_product_attribute ipa ON ipa.attribute_id=oirc.attribute_id  ";
		$filter['record_count'] = $GLOBALS['db']->getOne($record_count_sql.$where_sql);

        /* 分页大小 */
        $filter = page_and_size($filter);
		 
		//$sql="SELECT * FROM bx_organ_ipa_rate_config  oirc LEFT JOIN $where_sql LIMIT $filter[start] , $filter[page_size]";
		$sql="SELECT organization.*,oirc.*,ipa.attribute_name,ipa.rate_total FROM bx_organ_ipa_rate_config oirc 
			  LEFT JOIN (SELECT u.user_id,u.user_name,d.d_name,d.d_identificationcode FROM bx_users u LEFT JOIN bx_distributor d ON u.user_id=d.d_uid WHERE is_institution=2) AS organization ON oirc.institution_id=organization.user_id
			  LEFT JOIN t_insurance_product_attribute ipa ON ipa.attribute_id=oirc.attribute_id $where_sql";
	   	ss_log("organ_ipa_rate_config_list:".$sql);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

    $config_list = $GLOBALS['db']->getAll($sql);

    $arr = array('config_list' => $config_list, 'filter' => $filter,
        'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    return $arr;
}


// 渠道--险种列表
function attribute_list()
{
	
	$result = get_filter();
    if ($result === false)
    {
        /* 过滤条件 */
        $where_sql = " WHERE 1 ";
		
		$filter['attribute_name']= isset($_REQUEST['attribute_name'])?$_REQUEST['attribute_name']:"";
		
		if($filter['attribute_name'])
		{
			$where_sql.=" AND attribute_name LIKE '%$filter[attribute_name]%'";
		}
		
		
		$record_count_sql = "SELECT count(attribute_id) FROM t_insurance_product_attribute ";
		$filter['record_count'] = $GLOBALS['db']->getOne($record_count_sql.$where_sql);

        /* 分页大小 */
        $filter = page_and_size($filter);
		 
		$sql="SELECT * FROM t_insurance_product_attribute $where_sql LIMIT $filter[start] , $filter[page_size]";
	   	ss_log("attribute_list:".$sql);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

    $attribute_list = $GLOBALS['db']->getAll($sql);

    $arr = array('attribute_list' => $attribute_list, 'filter' => $filter,
        'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    return $arr;
	
	
}


function insert_child_organ_config($organ_ipa_rate_config)
{
	$attribute_id = $organ_ipa_rate_config['attribute_id'];
	$institution_id = $organ_ipa_rate_config['institution_id'];
	
	$user_rank = get_user_rank_info(0,ORGANIZATION_USER);
	
	$sql="SELECT user_id,institution_id FROM bx_users WHERE institution_id='$institution_id' AND user_rank='".$user_rank['rank_id']."'";
	ss_log(__FUNCTION__.",查询子渠道：".$sql);
	$child_organ_list = $GLOBALS['db']->getAll($sql);
	if($child_organ_list)
	{
		foreach ( $child_organ_list as $key => $child_organ ) 
		{
			$organ_ipa_rate_config['institution_id'] =$child_organ['user_id'] ;
       		//查询是否添加过
			$sql="SELECT id FROM bx_organ_ipa_rate_config  WHERE attribute_id=$attribute_id AND institution_id=$child_organ[user_id] ";
			$id = $GLOBALS['db']->getOne($sql);
			
			if(!$id)
       		{
       			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('organ_ipa_rate_config'), $organ_ipa_rate_config, 'INSERT');	
       		}
       		else
       		{
       			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('organ_ipa_rate_config'), $organ_ipa_rate_config, 'UPDATE',"id=$id");	
       		}
       		
       		insert_child_organ_config($organ_ipa_rate_config);
		}
	}
}

?> 
