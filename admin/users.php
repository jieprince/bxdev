<?php

/**
 * ECSHOP 会员管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: users.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

//@define('IN_UCHOME', TRUE);
//echo ROOT_PATH.'baoxian/source/function_common.php';
include_once(ROOT_PATH.'baoxian/common.php');
include_once(ROOT_PATH.'baoxian/source/function_debug.php');
include_once(ROOT_PATH . 'languages/zh_cn/admin/account_log.php');
include_once(ROOT_PATH . 'admin/includes/lib_users.php');
//echo ROOT_PATH.'baoxian/source/function_debug.php';

$action = isset($_REQUEST['act'])?$_REQUEST['act']:'';
$smarty->assign('action',   $action);
/*------------------------------------------------------ */
//-- 用户账号列表
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'list')
{
	
	admin_priv("users_list");
    /*接收审批状态值*/	
    $check_status = isset($_REQUEST['check_status']) ? $_REQUEST['check_status'] : CHECKED_CHECK_STATUS;
	
	$smarty->assign('ur_here',      "会员列表");
    /* 检查权限 */
    
    //start modify yes123 2015-03-04 会员审核权限问题
/*    if($check_status!=CHECKED_CHECK_STATUS) //不等于已审核
    {
	    admin_priv("03_users_list");
	    $smarty->assign('ur_here',      $_LANG['04_users_list']); //待审批
    }
    else
    {	
    	admin_priv("users_list_check");
    	$smarty->assign('ur_here',      $_LANG['03_users_list']);
    	
    }*/
    
    
    //end modify yes123 2015-03-04 会员审核权限问题
    
    $sql = "SELECT rank_id, rank_name, min_points FROM ".$ecs->table('user_rank')." ORDER BY min_points ASC ";
    $rs = $db->query($sql);

    $ranks = array();
    while ($row = $db->FetchRow($rs))
    {
        $ranks[$row['rank_id']] = $row['rank_name'];
    }

    $smarty->assign('user_ranks',   $ranks);
 
    $smarty->assign('action_link',  array('text' =>$_LANG['04_users_add'], 'href'=>'users.php?act=add'));
 
    $user_list = user_list();
    $smarty->assign('user_list',     $user_list['user_list']);
    
    $smarty->assign('province_list', get_regions(1,1));
    
    $smarty->assign('current_url',  $user_list['current_url']);
    $smarty->assign('filter',       $user_list['filter']);
    
    
    $check_status_list['other'] = '所有';
    $check_status_list = array_reverse($check_status_list, true);
    $smarty->assign('check_status_list',    $check_status_list);
    
    $smarty->assign('record_count', $user_list['record_count']);
    $smarty->assign('page_count',   $user_list['page_count']);
    $smarty->assign('full_page',    1);
    $smarty->assign('sort_user_id', '<img src="images/sort_desc.gif">');
    assign_query_info();
    
    if($_REQUEST['type'])
    {
    	$smarty->assign('d_id',  $_REQUEST['d_id']);//add yes123 2014-12-23 缓存渠道ID，添加会员到渠道用到
    	$smarty->display($_REQUEST['type'].'.htm');	
    	exit;
    }
    
    $smarty->display('users_list.htm');
}


/*------------------------------------------------------ */
//-- ajax返回用户列表
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $user_list = user_list();	
    $smarty->assign('current_url',  $user_list['current_url']);
    $smarty->assign('user_list',    $user_list['user_list']);
    $smarty->assign('filter',       $user_list['filter']);
    $smarty->assign('record_count', $user_list['record_count']);
    $smarty->assign('page_count',   $user_list['page_count']);

    $sort_flag  = sort_flag($user_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);
    
    if($_REQUEST['type'])
    {
    	make_json_result($smarty->fetch($_REQUEST['type'].'.htm'), '', array('filter' => $user_list['filter'], 'page_count' => $user_list['page_count']));
    	exit;
    }

    make_json_result($smarty->fetch('users_list.htm'), '', array('filter' => $user_list['filter'], 'page_count' => $user_list['page_count']));
}

/*------------------------------------------------------ */
//-- 添加会员账号
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    /* 检查权限 */
    admin_priv("users_add");
	$distributor_id = $_REQUEST['distributor_id'];
    $user = array(  'rank_points'   => $_CFG['register_points'],
                    'pay_points'    => $_CFG['register_points'],
                    'sex'           => 0,
                    'credit_line'   => 0
                    );

    /* 取出注册扩展字段 */
    $sql = 'SELECT * FROM ' . $ecs->table('reg_fields') . ' WHERE type < 2 AND display = 1 AND id != 6 ORDER BY dis_order, id';
    $extend_info_list = $db->getAll($sql);
    $smarty->assign('extend_info_list', $extend_info_list);
    $smarty->assign('distributor_id', $distributor_id);
    $smarty->assign('d_name', $_REQUEST['d_name']);
    
    if($_REQUEST['distributor_id']!=''){
   	 	$smarty->assign('ur_here',      "<a href='users.php?act=users_list_bydistributor_id&distributor_id=$distributor_id']'>".$_REQUEST['d_name']."</a>-添加会员");
    }else{
    	$smarty->assign('ur_here',      "<a href='users.php?act=users_list_bydistributor_id&distributor_id=$distributor_id']'>会员列表</a>-添加会员");
    }
    $smarty->assign('action_link',      array('text' => $_LANG['03_users_list'], 'href'=>'users.php?act=list'));
    
    $smarty->assign('form_action',      'insert');
    $smarty->assign('zhengjian_type_list',      $zhengjian_type_list);
    $smarty->assign('user',             $user);
    $smarty->assign('special_ranks',    get_rank_list(true));
    
    $smarty->assign('organ_list',get_distributor());
	/* 载入国家 */
    $smarty->assign('country_list', get_regions());
    assign_query_info();
    $smarty->display('user_info.htm');
}

/*------------------------------------------------------ */
//-- 添加会员账号
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'insert')
{
    /* 检查权限 */
    admin_priv(action_list());
    $username = empty($_POST['username']) ? '' : trim($_POST['username']);
	
	$real_name = empty($_POST['real_name']) ? '' :trim($_POST['real_name']);
  
    $password = empty($_POST['password']) ? '' : trim($_POST['password']);
    $email = empty($_POST['email']) ? '' : trim($_POST['email']);
    $sex = empty($_POST['sex']) ? 0 : intval($_POST['sex']);
    $sex = in_array($sex, array(0, 1, 2)) ? $sex : 0;
    $birthday = $_POST['birthdayYear'] . '-' .  $_POST['birthdayMonth'] . '-' . $_POST['birthdayDay'];
    $rank = empty($_POST['user_rank']) ? 0 : intval($_POST['user_rank']);
    $credit_line = empty($_POST['']) ? 0 : floatval($_POST['credit_line']);
    $users =& init_users();


	if($username=='ebaoins_user'){
		sys_msg("用户名已存在<script>alert('用户名已存在');</script>", 0, $link);		
		return;
	}

	/**
	 * 2014/8/1
	 * bhz
	 * 接收证件及住址信息 start
	 */
	$CertificatesType = $_POST['CertificatesType'];
	$CardId = $_POST['CardId'];
	$Category = $_POST['Category'];
	$CertificateNumber = $_POST['CertificateNumber'];
	$Province = $_POST['province'];
	$city = $_POST['city'];
	$address = $_POST['address'];
	$ZoneCode = $_POST['ZoneCode'];
	
	/**
	 * 接收证件及住址信息 end
	 */
    if (!$users->add_user($username, $password, $email))
    {
        /* 插入会员数据失败 */
        if ($users->error == ERR_INVALID_USERNAME)
        {
            $msg = $_LANG['username_invalid'];
        }
        elseif ($users->error == ERR_USERNAME_NOT_ALLOW)
        {
            $msg = $_LANG['username_not_allow'];
        }
   		elseif ($users->error == ERR_USERNAME_EXISTS)
        {
            $msg = $_LANG['username_exists'];
        }
        elseif ($users->error == ERR_INVALID_EMAIL)
        {
            $msg = $_LANG['email_invalid'];
        }
        elseif ($users->error == ERR_EMAIL_NOT_ALLOW)
        {
            $msg = $_LANG['email_not_allow'];
        }
        elseif ($users->error == ERR_EMAIL_EXISTS)
        {
            $msg = $_LANG['email_exists'];
        }
        else
        {
            //die('Error:'.$users->error_msg());
        }
        sys_msg($msg, 1);
    }

    /* 注册送积分 */
    if (!empty($GLOBALS['_CFG']['register_points']))
    {
        log_account_change($_SESSION['user_id'], 0, 0, $GLOBALS['_CFG']['register_points'], $GLOBALS['_CFG']['register_points'], $_LANG['register_points']);
    }

    /*把新注册用户的扩展信息插入数据库*/
    $sql = 'SELECT id FROM ' . $ecs->table('reg_fields') . ' WHERE type = 0 AND display = 1 ORDER BY dis_order, id';   //读出所有扩展字段的id
    $fields_arr = $db->getAll($sql);
	
    $extend_field_str = '';    //生成扩展字段的内容字符串
    $user_id_arr = $users->get_profile_by_name($username);
    foreach ($fields_arr AS $val)
    {
        $extend_field_index = 'extend_field' . $val['id'];
        if(!empty($_POST[$extend_field_index]))
        {
            $temp_field_content = strlen($_POST[$extend_field_index]) > 100 ? mb_substr($_POST[$extend_field_index], 0, 99) : $_POST[$extend_field_index];
            $extend_field_str .= " ('" . $user_id_arr['user_id'] . "', '" . $val['id'] . "', '" . $temp_field_content . "'),";
        }
    }
    $extend_field_str = substr($extend_field_str, 0, -1);
    if ($extend_field_str)      //插入注册扩展数据
    {
        $sql = 'INSERT INTO '. $ecs->table('reg_extend_info') . ' (`user_id`, `reg_field_id`, `content`) VALUES' . $extend_field_str;
        $db->query($sql);
    }
	/**
	 * 2014/8/1
	 * bhz
	 * 接收证件及住址信息 更新 start
	 */
    $uid = mysql_insert_id();
	$sql = "INSERT INTO ". $ecs->table('user_info') ." (uid, CertificatesType, CardId, Category, CertificateNumber, Province, city, address, ZoneCode) VALUES ('$uid', '$CertificatesType', '$CardId', '$Category', '$CertificateNumber', '$Province', '$city', '$address', '$ZoneCode')";
	$db->query($sql);
	/**
	 * 接收证件及住址信息 更新 end
	 */
	 
    /* 更新会员的其它信息 */
    $other =  array();
    $other['credit_line'] = $credit_line;
    $other['user_rank']  = $rank;
    $other['sex']        = $sex;
    $other['distributor_id']    = $_REQUEST['distributor_id'];
    $other['birthday']   = $birthday;
    $other['reg_time'] = time();
    $other['msn'] = isset($_POST['extend_field1']) ? htmlspecialchars(trim($_POST['extend_field1'])) : '';
    $other['qq'] = isset($_POST['extend_field2']) ? htmlspecialchars(trim($_POST['extend_field2'])) : '';
    $other['office_phone'] = isset($_POST['extend_field3']) ? htmlspecialchars(trim($_POST['extend_field3'])) : '';
    $other['home_phone'] = isset($_POST['extend_field4']) ? htmlspecialchars(trim($_POST['extend_field4'])) : '';
    $other['mobile_phone'] = isset($_POST['extend_field5']) ? htmlspecialchars(trim($_POST['extend_field5'])) : '';
    
    
    $user_rank = get_user_rank_info(0,COMMON_USER);
	$other['rank_id'] = isset($_POST['rank_id']) ? $_POST['rank_id'] : $user_rank['rank_id'];
	
	
	$other['institution_id'] = isset($_POST['institution_id']) ? $_POST['institution_id'] :0;
	$other['real_name'] = $real_name;
	$other['user_source'] = 1;
    $db->autoExecute($ecs->table('users'), $other, 'UPDATE', "user_name = '$username'");
    /* 记录管理员操作 */
    admin_log($_POST['username'], 'add', 'users');

    /* 提示信息 */
    
    $user_rank = get_user_rank_info($other['rank_id']);
    if($user_rank['rank_code']==ORGANIZATION_USER) //如果是渠道，那么跳转到渠道列表
    {
	    $link[] = array('text' => $_LANG['go_back'], 'href'=>'users.php?act=organization_list');
    }
    else
    {
    	$link[] = array('text' => $_LANG['go_back'], 'href'=>'users.php?act=list');
    }


	sys_msg(sprintf($_LANG['add_success'], htmlspecialchars(stripslashes($_POST['username']))), 0, $link);	
}


/**
 * 返回并且审批信息
 * 2014-09-10 yes123
 */

elseif ($_REQUEST['act'] == 'check_view')
{
	$user_id = $_REQUEST['id'];
	
	$row = user_info_by_userid($user_id);
	
	if($row['rank_code']==ORGANIZATION_USER)   //add yes123 2015-03-16 取渠道数据
	{
		include_once(ROOT_PATH . 'includes/class/User.class.php');
		$user_obj = new User;
		$res = $user_obj->organInfo($user_id);
		$res['distributor_info']['rank_code']=ORGANIZATION_USER;
		
		if($res['distributor_info']['d_zone'])
		{
			$sql = "SELECT *FROM ".$ecs->table('region')." WHERE region_id IN(".$res['distributor_info']['d_zone'].")";
			$region_list = $db->getAll($sql);
			$smarty->assign('d_zone',$region_list[0]['region_name']." ".$region_list[1]['region_name']." ".$region_list[2]['region_name']);//县
		}
		
		$smarty->assign('country_list', get_regions());
		$smarty->assign('provinces', $res['province']);//省
		$smarty->assign('city', $res['city']);//市
		$smarty->assign('user_id', $user_id);
		$smarty->assign('district', $res['district']);//县
		$smarty->assign('user', $res['distributor_info']); //渠道基本信息
	}
	elseif($row['rank_code']!=ORGANIZATION_USER)  //add yes123 2015-03-16 取普通会员数据
	{
		//modify yes123 2014-12-01 修正地址显示
		if($row['Province']){
			$sql="SELECT region_name  FROM ".$ecs->table('region')." WHERE region_id=".$row['Province'];
			$row['Province'] = $db->getOne($sql);
		}
		else
		{
			$row['Province']='';
		}
		
		if($row['city'])
		{
			$sql="SELECT region_name  FROM ".$ecs->table('region')." WHERE region_id=".$row['city'];
			$row['city'] = $db->getOne($sql);
			
		}
		else{
			$row['city']='';
		}
		
		if($row['district'])
		{
			$sql="SELECT region_name  FROM ".$ecs->table('region')." WHERE region_id=".$row['district'];
			$row['district'] = $db->getOne($sql);
			
		}
		else{
			$row['district']='';
		}
		
		$row['sex']=$row['sex']==0? '保密':$row['sex']==1? '男':'女';
		
		
		
	    $smarty->assign('user',$row);
		
	}
	
    $smarty->display('check_user.htm');
	
	
}

/**
 * 返回并且编辑审批信息
 * 2014-09-10 yes123
 */

elseif ($_REQUEST['act'] == 'check')
{
	//modify yes123 2015-01-06 else 里也要用，所以拿到外面来了
	$user_id = $_REQUEST['id'];
	$last_time=date('Y-m-d H:i:s', time()); 
	$row = user_info_by_userid($user_id);
	$parent_id = $row['parent_id'];
	$check_status = isset($_REQUEST['check_status'])?$_REQUEST['check_status']:NO_PASS_CHECK_STATUS;
	
	if($check_status==CHECKED_CHECK_STATUS)//审批通过
	{
		ss_log('审批通过user_id ：'.$user_id.",check_status:".$check_status);
		
		
		admin_priv('user_check');
		
		// add yes123 2015-01-29   如果已经是审核通过的，下面代码就不需要再执行 
		if($row['check_status']==CHECKED_CHECK_STATUS){
				$str_err = "该用户已经通过审核，无需重复操作！";
				ss_log($str_err);
				$links[] = array('text' => $str_err , 'href'=>'users.php?act=list&cheackValue=1');
				sys_msg($str_err, 1, $links);
		}
		
		if($row['rank_code']!=ORGANIZATION_USER) //提交审核时，校验个人信息
		{
			if($row['user_id'])
			{
				$real_name = $row['real_name'];
				$username = $row['user_name'];
					
				$user_name = empty($real_name)?$username:$real_name;//$userinfo['username'];
				$user_email = $row['email'];
				$user_mobile = $row['mobile_phone'];
				
				if(empty($real_name)||empty($user_email)||empty($user_mobile))
				{
					$str_err = "该用户信息不完整 ，无法审批通过！需要填写真实姓名、手机号、email。";
					ss_log($str_err);
						
					$links[] = array('text' => $str_err , 'href'=>'users.php?act=list&cheackValue=0');
						
					sys_msg($str_err, 1, $links);
						
					return;
				}
				
			}
			else
			{
				$str_err = "该用户不存在！";
				ss_log($str_err);
					
				$links[] = array('text' => $str_err , 'href'=>'users.php?act=list&cheackValue=0');
					
				sys_msg($str_err, 1, $links);
					
				return;
			}
			
		}
		elseif($row['rank_code']==ORGANIZATION_USER) //渠道校验
		{
			if($row['user_id'])
			{
				$d_name = $row['d_name'];
				$user_email = $row['email'];
				$user_mobile = $row['mobile_phone'];
				$str_err="";
				if(empty($d_name)||empty($user_email)||empty($user_mobile))
				{
					$str_err = "该渠道信息不完整 ，无法审批通过！需要填写渠道名称、手机号、email。\n";
		
				}
				
				//校验证件
				//1.企业组织代码
				if(strlen($row['d_identificationcode'])<6)
				{
					$str_err="企业组织代码格式不对！\n";
				}
				//2.从业资格证书
/*				if(strlen($row['d_qualificationNumber'])<6)
				{
					$str_err="从业资格证书格式不对！\n";
				}*/
				
				if($str_err)
				{
					$links[] = array('text' => $str_err , 'href'=>'users.php?act=list&cheackValue=0');
					sys_msg($str_err, 1, $links);
					return;
				}
				
				
			}
			else
			{
				$str_err = "该用户不存在！";
				ss_log($str_err);
					
				$links[] = array('text' => $str_err , 'href'=>'users.php?act=list&cheackValue=0');
					
				sys_msg($str_err, 1, $links);
					
				return;
			}
			
			
			
		}
		
		
		///end add by wangcya, 20141121,通过手机号验证用户地区/////////////////////////////////////////
		$mobilephone = empty($user_name)?$user_mobile:$user_name;
		$res = checkMobileValidity($mobilephone);
		if(!$res)
		{
			//echo "mobile is not Validity";
			
			$str_err = "该用户名不是有效的手机号！";
			ss_log($str_err);
			
			$links[] = array('text' => $str_err , 'href'=>'users.php?act=list&cheackValue=0');
			
			sys_msg($str_err, 1, $links);
			
			return;
			
		}
		
		$zone = checkMobilePlace($mobilephone);
		if(empty($res))
		{
			$str_err = "获取手机号的地区错误！";
			ss_log($str_err);
			
			$links[] = array('text' => $str_err , 'href'=>'users.php?act=list&cheackValue=0');
			
			sys_msg($str_err, 1, $links);
			
			return;
			
		}
		else
		{
			$province = $zone["province"];
			$catName  = $zone["catName"];
		
			ss_log("审批用户，province: ".$province);
			ss_log("审批用户，catName: ".$catName);

			$sql = "SELECT region_id FROM " . $ecs->table('region') ." WHERE parent_id = 1 AND region_name = '$province'";//china
			$region_id = $db->getOne($sql);
			
			
		}
		///end add by wangcya, 20141121,通过手机号验证用户地区/////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////////
		
		
			
		//更新状态
		$sql="UPDATE ".$ecs->table('users'). "SET check_status='$check_status',last_update_time='$last_time' WHERE user_id=".$user_id ;
		
		ss_log("更新状态:sql:".$sql);
		
		$db->query($sql);
		
		
		//更新审核结果
		$sql="UPDATE ".$ecs->table('user_info'). "SET check_result='' WHERE uid=".$user_id ;
		$db->query($sql);
		
		//add yes123 2015-01-06 审核通过后，短信通知用户
		$mobile_phone = $row['mobile_phone'];
		$content = "您已通过本网站的会员信息审核，可以登录网站进行正常操作。";
		send_msg($mobile_phone,$content,0,CHECK_SMS);
		
		//add push by dingchaoyang 2014-12-15
		include_once(ROOT_PATH.'api/EBaoApp/eba_adapter.php');
		EbaAdapter::pushCheckUserNotification($row['user_id'],'0');
		//end by dingchaoyang 2014-12-15
		
		//modify yes123 2015-08-13 更改赠送方式
		send_recommend_award($row);
		
		
		
		$str_err = "操作成功！";
		$links[] = array('text' => $str_err , 'href'=>'users.php?act=list&check_status=other');
		sys_msg($str_err, 0, $links);
		exit(0);
	}
	elseif($check_status==NO_PASS_CHECK_STATUS)
	{
		
		ss_log('审批不通过user_id ：'.$user_id.",check_status:".$check_status);
		
		admin_priv('user_check');
		
		//不通过发送邮件		
		 $email_title =  '易保险用户资料审核不通过';
		 $email_addrs = trim($_REQUEST['email']);
	
		 $shop_name = $GLOBALS['_CFG']['shop_name'];
		 $tpl = get_mail_template('user_check');
		
	     $context = trim($_REQUEST['check_desc']);
		 $smarty->assign('check_result', $context);
		 $smarty->assign('shop_name', $shop_name);
		 $smarty->assign('user_name', trim($_REQUEST['user_name']));
		 $smarty->assign('send_date', date('Y年m月d日 H:i:s',time()));
	     
	     $content = $smarty->fetch('str:' . $tpl['template_content']);
	     $content =  str_replace("shop_url",$_SERVER['HTTP_HOST'], $content);
	     
	     
	     //更新状态
		 $sql="UPDATE ".$ecs->table('users'). "SET check_status='$check_status',last_update_time='$last_time' WHERE user_id=".$user_id ;
		 $db->query($sql);
		
		
		 //更新审核结果
		 $sql="UPDATE ".$ecs->table('user_info'). "SET check_result='".$context."' WHERE uid=".$_REQUEST['id'];
		 $db->query($sql);
	     
	     
	     
	        send_mail($_CFG['shop_name'],$email_addrs , $shop_name."用户资料审核不通过", $content, $tpl['is_html']);
		
		
		//add yes123 2015-01-06 发送手机
		$mobile_phone = $row['mobile_phone'];
		$content = "您提交的会员信息未通过审核，原因为:$context 请完善个人信息后重新提交，如有疑问，请致电客服：4006900618咨询。";
		send_msg($mobile_phone,$content,0,CHECK_SMS);
		
		//add push by dingchaoyang 2014-12-15
		include_once(ROOT_PATH.'api/EBaoApp/eba_adapter.php');
		EbaAdapter::pushCheckUserNotification($row['user_id'],'1');
		//end by dingchaoyang 2014-12-15
		
		
		 Header("Location: users.php?act=list");  
		 
		 exit;
	}

	
}
//查看用户信息
elseif($_REQUEST['act'] == 'user_view')
{
	
	admin_priv('meeting_list');
	$sql="SELECT u.*,i.CertificatesType,i.CardId,i.Province,i.city,i.address,i.ZoneCode,i.Category,i.CertificateNumber FROM ". $ecs->table('users') ." u".
	" LEFT JOIN ".$ecs->table('user_info')."i ON u.user_id=i.uid  WHERE u.user_id=".$_REQUEST['user_id'];
	$row = $db->getRow($sql);
	
	//modify yes123 2014-12-01 修正地址显示
	if($row['Province']){
		$sql="SELECT region_name  FROM ".$ecs->table('region')." WHERE region_id=".$row['Province'];
		$row['Province'] = $db->getOne($sql);
	}
	else
	{
		$row['Province']='';
	}
	
	if($row['city'])
	{
		$sql="SELECT region_name  FROM ".$ecs->table('region')." WHERE region_id=".$row['city'];
		$row['city'] = $db->getOne($sql);
		
	}
	else{
		$row['city']='';
	}
	$row['sex']=$row['sex']==0? '保密':$row['sex']==1? '男':'女';
	
	
	
    $smarty->assign('user',$row);
	
    $smarty->display('user_view.htm');
	
	
}
/*------------------------------------------------------ */
//-- 编辑用户账号
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'edit')
{
    /* 检查权限 */
    admin_priv(action_list());
	
	$user_id = $_GET['id'];
	$res = user_base_info($user_id);
	$user = $res['user'];
	
	
	
	$province_list = $res['province_list'];
	$city_list = isset($res['city_list'])?$res['city_list']:null;
	$district_list = isset($res['district_list'])?$res['district_list']:null;
    $smarty->assign('province_list',      $province_list);
    $smarty->assign('city_list',          $city_list);
    $smarty->assign('district_list',      $district_list);
    $smarty->assign('user',             $user);
    
    assign_query_info();
    $smarty->assign('ur_here',          $_LANG['users_edit']);
    $smarty->assign('action_link',      array('text' => $_LANG['03_users_list'], 'href'=>'users.php?act=list&' . list_link_postfix()));
    $smarty->assign('special_ranks',    get_rank_list(true));
	
    $smarty->assign('organ_list',get_distributor($user_id));
    
    $smarty->assign('form_action', 'update');
	$smarty->assign('zhengjian_type_list',      $zhengjian_type_list);
    $smarty->display('user_info.htm');
}

/*------------------------------------------------------ */
//-- 更新用户账号
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'update')
{
	//ss_log("update userinfo");
    /* 检查权限 */
    admin_priv("user_update");
    $user_id = empty($_POST['id']) ? '' : trim($_POST['id']);
    if(!$user_id)
    {
    	return ;
    }
    
    $username = empty($_POST['username']) ? '' : trim($_POST['username']);
	
	$real_name = empty($_POST['real_name']) ? '' : trim($_POST['real_name']);
	
    $password = empty($_POST['password']) ? '' : trim($_POST['password']);
    $email = empty($_POST['email']) ? '' : trim($_POST['email']);
    $sex = empty($_POST['sex']) ? 0 : intval($_POST['sex']);
    $sex = in_array($sex, array(0, 1, 2)) ? $sex : 0;
    $birthday = $_POST['birthdayYear'] . '-' .  $_POST['birthdayMonth'] . '-' . $_POST['birthdayDay'];
    $rank = empty($_POST['user_rank']) ? 0 : intval($_POST['user_rank']);
    $credit_line = empty($_POST['credit_line']) ? 0 : floatval($_POST['credit_line']);
    
    
    
    //user_info表的信息
    $user_info_data = array();
    $user_info_data['CertificatesType'] = $_POST['CertificatesType'];
	$user_info_data['CardId'] = $_POST['CardId'];
	$user_info_data['Category'] = $_POST['Category'];
	$user_info_data['CertificateNumber'] = $_POST['CertificateNumber'];
	$user_info_data['Province'] = $_POST['province'];
	$user_info_data['district'] = $_POST['district'];
	$user_info_data['city'] = $_POST['city'];
	$user_info_data['district'] = $_POST['district'];
	$user_info_data['address'] = $_POST['address'];
	$user_info_data['ZoneCode'] = $_POST['ZoneCode'];
	
	 //add yes12 2014-12-31 证书有效期 
     $user_info_data['certificate_expiration_date'] = isset($_POST['certificate_expiration_date']) ? trim($_POST['certificate_expiration_date']) : '';
     //对永久有效另外处理
     $certificate_expiration_date_no = isset($_POST['certificate_expiration_date_no']) ? trim($_POST['certificate_expiration_date_no']) : '';
     if($certificate_expiration_date_no){
     	$user_info_data['certificate_expiration_date'] = $certificate_expiration_date_no;
     }
     
     
     //start add yes123 2014-12-31 校验关键信息为一性
     $msg='';
     //1. 证件 modify yes123 2015-01-06 加非空条件
     $sql = "SELECT count(*) FROM " . $ecs->table('user_info') . " WHERE CardId = '$user_info_data[CardId]' AND CertificatesType = '$user_info_data[CertificatesType]' AND CardId IS NOT NULL AND CardId<>''AND uid <> '$user_id'";
	 $r = $db->getOne($sql);
     if($r)
     {
     	$msg = $_LANG['edit_cardId_failed'];
     }
     
     //2.资格证  modify yes123 2015-01-06 加非空条件
     $sql = "SELECT count(*) FROM " . $ecs->table('user_info') . " WHERE CertificateNumber = '$user_info_data[CertificateNumber]' AND Category='$user_info_data[Category]' AND CertificateNumber IS NOT NULL AND CertificateNumber <>'' AND uid <> '$user_id'";
	 $r = $db->getOne($sql);
     if($r)
     {
     	$msg = $_LANG['edit_certificateNumber_failed'];
     }
     
     //3.手机号码 modify yes123 2015-01-06 加非空条件
     $other['mobile_phone'] = isset($_POST['mobile_phone']) ? htmlspecialchars(trim($_POST['mobile_phone'])) : '';
     $sql = "SELECT count(*) FROM " . $ecs->table('users') . " WHERE mobile_phone = '$other[mobile_phone]' AND mobile_phone IS NOT NULL AND mobile_phone<>'' AND user_id <> '$user_id'";
	 $r = $db->getOne($sql);
     if($r)
     {
     	$msg = $_LANG['edit_phone_failed'];
     }
     
     if($msg){
     	 sys_msg($msg, 1);
     }
     //end add yes123 2014-12-31 校验关键信息为一性
     
	$users  =& init_users();
    if (!$users->edit_user(array('username'=>$username, 'password'=>$password, 'email'=>$email, 'gender'=>$sex, 'bday'=>$birthday ), 1))
    {
        if ($users->error == ERR_EMAIL_EXISTS)
        {
            $msg = $_LANG['email_exists'];
        }
        else
        {
            $msg = $_LANG['edit_user_failed'];
        }
        sys_msg($msg, 1);
    }
    if(!empty($password))
    {
			$sql="UPDATE ".$ecs->table('users'). "SET `ec_salt`='0' WHERE user_name= '".$username."'";
			$db->query($sql);
	}

	//start add by wangcya , 20141024
	$sql = 'SELECT uid FROM ' . $ecs->table('user_info') . "  WHERE uid = '$user_id'";
	if ($db->getOne($sql))      //如果之前没有记录，则插入
	{
		$db->autoExecute($ecs->table('user_info'), $user_info_data, "UPDATE", "uid = $user_id");  
	}
	else
	{
		$user_info_data['uid']=$user_id;
		$db->autoExecute($ecs->table('user_info'), $user_info_data, "INSERT");  
	}
	//end add by wangcya , 20141024
	
    /* 更新会员的其它信息 */
    $other =  array();
    $other['credit_line'] = $credit_line;
    $other['user_rank'] = $rank;
    $other['mobile_phone'] = isset($_POST['mobile_phone']) ? $_POST['mobile_phone'] : '';
    $other['email'] = isset($_POST['email']) ? $_POST['email'] : '';
    $other['birthday'] = isset($_POST['birthday']) ? $_POST['birthday'] : '';
    
    
    if(!$rank)
    {
    	 $user_rank = get_user_rank_info(0,COMMON_USER);
    	 $other['user_rank'] = $user_rank['rank_id'];
    }
    else
    {
    	$other['user_rank'] = $rank;
    }
   
    
    
    
    $other['institution_id'] = isset($_POST['institution_id']) ? $_POST['institution_id'] :0;
    
	
	
    //add yes123 2015-04-29 更新手机号码时，把用户名也更新掉
    if($other['mobile_phone'])
    {
    	$other['user_name'] = $other['mobile_phone'];
    }

	//处理会员升级的问题
	$user_rank = get_user_rank_info($other['user_rank']);
	$user_info = user_info_by_userid($user_id);
	//普通会员升级到认证会员或者服务专员
	if($user_rank['rank_code']==COMMON_USER && $user_info['rank_code']==AUTHORIZE_USER)
	{
		$other['target_rank']='';
		$other['check_status']=CHECKED_CHECK_STATUS;
		
	} 	//如果已经是服务专员或者渠道，也清空
	elseif($user_rank['rank_code']==ADVISER_USER || $user_rank['rank_code']==ORGANIZATION_USER)
	{
		$other['target_rank']='';
		$other['check_status']=CHECKED_CHECK_STATUS;
		
	}
	
	
	
	$other['real_name'] = $real_name;
    $db->autoExecute($ecs->table('users'), $other, 'UPDATE', "user_name = '$username'");

    /* 记录管理员操作 */
    admin_log($username, 'edit', 'users');
	
   
   
    
    $links[0]['text']    = $_LANG['goto_list'];
    if($user_rank['rank_code']==ORGANIZATION_USER) //如果是渠道，那么跳转到渠道列表
    {
    	$links[0]['href']    = 'distributor.php?act=organization_list';
	}
	else //编辑正式用户
	{
		$links[0]['href']    = 'users.php?act=list';
	}
    $links[1]['text']    = $_LANG['go_back'];
    $links[1]['href']    = 'javascript:history.back()';

  
  
    /////////end add by wangcya , 20140801//////////////////////////////
    sys_msg($_LANG['update_success'].$msg, 0, $links);
}

/* 禁用或者启用会员 */
elseif($action == 'disable_enabled_salesman')
{
    /* 用户信息 */
	include_once(ROOT_PATH . 'includes/class/User.class.php');
	$user_obj = new User;
	$is_disable = $_REQUEST['is_disable'];
	$id = $_REQUEST['user_id'];
    
	$sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET is_disable =$is_disable WHERE user_id=".$id ;

	if($GLOBALS['db']->query($sql))
    {
    	$datas = json_encode(array('code'=>0,'msg'=>'操作成功!')); //如果用户不存在,返回-2
	 	
    }
    else
    {
    	$datas = json_encode(array('code'=>1,'msg'=>'操作失败!')); //如果用户不存在,返回-2
    }
    die($datas);
}

/*------------------------------------------------------ */
//-- 批量删除会员账号
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'batch_remove')
{
    /* 检查权限 */
    admin_priv("user_remove");

    if (isset($_POST['checkboxes']))
    {
        $sql = "SELECT user_name FROM " . $ecs->table('users') . " WHERE user_id " . db_create_in($_POST['checkboxes']);
        $col = $db->getCol($sql);
        $usernames = implode(',',addslashes_deep($col));
        $count = count($col);
        /* 通过插件来删除用户 */
        $users =& init_users();
        $users->remove_user($col);

        admin_log($usernames, 'batch_remove', 'users');

        $lnk[] = array('text' => $_LANG['go_back'], 'href'=>'users.php?act=list');
        sys_msg(sprintf($_LANG['batch_remove_success'], $count), 0, $lnk);
    }
    else
    {
        $lnk[] = array('text' => $_LANG['go_back'], 'href'=>'users.php?act=list');
        sys_msg($_LANG['no_select_user'], 0, $lnk);
    }
}

/* 编辑用户名 */
elseif ($_REQUEST['act'] == 'edit_username')
{
    /* 检查权限 */
    admin_priv(action_list());

    $username = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));
    $id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);

    if ($id == 0)
    {
        make_json_error('NO USER ID');
        return;
    }

    if ($username == '')
    {
        make_json_error($GLOBALS['_LANG']['username_empty']);
        return;
    }

    $users =& init_users();

    if ($users->edit_user($id, $username))
    {
        if ($_CFG['integrate_code'] != 'ecshop')
        {
            /* 更新商城会员表 */
            $db->query('UPDATE ' .$ecs->table('users'). " SET user_name = '$username' WHERE user_id = '$id'");
        }

        admin_log(addslashes($username), 'edit', 'users');
        make_json_result(stripcslashes($username));
    }
    else
    {
        $msg = ($users->error == ERR_USERNAME_EXISTS) ? $GLOBALS['_LANG']['username_exists'] : $GLOBALS['_LANG']['edit_user_failed'];
        make_json_error($msg);
    }
}

/*------------------------------------------------------ */
//-- 编辑email
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_email')
{
    /* 检查权限 */
    admin_priv(action_list());

    $id = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
    $email = empty($_REQUEST['val']) ? '' : json_str_iconv(trim($_REQUEST['val']));

    $users =& init_users();

    $sql = "SELECT user_name FROM " . $ecs->table('users') . " WHERE user_id = '$id'";
    $username = $db->getOne($sql);


    if (is_email($email))
    {
        if ($users->edit_user(array('username'=>$username, 'email'=>$email)))
        {
            admin_log(addslashes($username), 'edit', 'users');

            make_json_result(stripcslashes($email));
        }
        else
        {
            $msg = ($users->error == ERR_EMAIL_EXISTS) ? $GLOBALS['_LANG']['email_exists'] : $GLOBALS['_LANG']['edit_user_failed'];
            make_json_error($msg);
        }
    }
    else
    {
        make_json_error($GLOBALS['_LANG']['invalid_email']);
    }
}

/*------------------------------------------------------ */
//-- 删除会员账号
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'remove')
{
    /* 检查权限 */
    admin_priv("user_remove");

    $sql = "SELECT user_name FROM " . $ecs->table('users') . " WHERE user_id = '" . $_GET['id'] . "'";
    $username = $db->getOne($sql);
    
    //判断是否是公共账户
    if(trim($username)=='ebaoins_user'){
    	sys_msg(sprintf('此账户是网站公共账户,不能删除!', $username), 0, $link);
    	return;
    }
    
    //判断如果是渠道前台账户,就不能删除
     $sql = "SELECT is_organization FROM " . $ecs->table('admin_user') . " WHERE user_name = '".trim($username)."'";
     $is_organization = $db->getOne($sql);
     if($is_organization==1){
     	sys_msg(sprintf('此账户是渠道账户,不能删除!', $username), 0, $link);
    	return;
     }
     
    /* 通过插件来删除用户 */
    $users =& init_users();
    $users->remove_user($username); //已经删除用户所有数据

    /* 记录管理员操作 */
    admin_log(addslashes($username), 'remove', 'users');

    /* 提示信息 */
/*    if($_REQUEST['distributor_id']!=''){
    	$link[] = array('text' => $_LANG['go_back'], 'href'=>'users.php?act=users_list_bydistributor_id&distributor_id='.$_REQUEST['distributor_id']);
    }else{
    	$link[] = array('text' => $_LANG['go_back'], 'href'=>'users.php?act=list');
    }*/
    sys_msg(sprintf($_LANG['remove_success'], $username), 0, $link);
}

/*------------------------------------------------------ */
//--  收货地址查看
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'address_list')
{
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $sql = "SELECT a.*, c.region_name AS country_name, p.region_name AS province, ct.region_name AS city_name, d.region_name AS district_name ".
           " FROM " .$ecs->table('user_address'). " as a ".
           " LEFT JOIN " . $ecs->table('region') . " AS c ON c.region_id = a.country " .
           " LEFT JOIN " . $ecs->table('region') . " AS p ON p.region_id = a.province " .
           " LEFT JOIN " . $ecs->table('region') . " AS ct ON ct.region_id = a.city " .
           " LEFT JOIN " . $ecs->table('region') . " AS d ON d.region_id = a.district " .
           " WHERE user_id='$id'";
    $address = $db->getAll($sql);
    $smarty->assign('address',          $address);
    assign_query_info();
    $smarty->assign('ur_here',          $_LANG['address_list']);
    $smarty->assign('action_link',      array('text' => $_LANG['03_users_list'], 'href'=>'users.php?act=list&' . list_link_postfix()));
    $smarty->display('user_address_list.htm');
}

/*------------------------------------------------------ */
//-- 脱离推荐关系
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'remove_parent')
{
	//modify yes123 2015-01-07 修改解除推荐关系日志 和 跳转url
    /* 检查权限 */
    admin_priv(action_list());
    
	$sql = "SELECT user_name,parent_id FROM " . $ecs->table('users') . " WHERE user_id = '" . $_GET['id'] . "'";
    $user = $db->getRow($sql);
	
	
    $sql = "UPDATE " . $ecs->table('users') . " SET parent_id = 0 WHERE user_id = '" . $_GET['id'] . "'";
    $db->query($sql);
	
    /* 记录管理员操作 */
    $sql = "SELECT user_name FROM " . $ecs->table('users') . " WHERE user_id = '" . $user['parent_id'] . "'";
    $parent_user_name = $db->getOne($sql);
    
    
	$content="编辑：解除".$user['user_name']."的推荐人关系，原来的推荐人是:".$parent_user_name;
    //记录管理员操作 
    admin_log2($content,'warn');
    
    die(json_encode(array('code'=>0,'msg'=>'移除成功！')));
}
/*add yes123 2015-01-07 添加推荐人*/
elseif ($_REQUEST['act'] == 'add_parent')
{
	$user_id = $_POST['user_id'];
	$parent_id = $_POST['parent_id'];
    /* 检查权限 */
    admin_priv(action_list());
	$sql = "SELECT user_id,user_name FROM" . $ecs->table('users') . " WHERE user_id =".$parent_id;
	$parent = $db->getRow($sql);
	
	
	$sql = "SELECT user_name,parent_id FROM " . $ecs->table('users') . " WHERE user_id = '" .$user_id. "'";
    $user = $db->getRow($sql);
    
	if($user['parent_id']){
		die(json_encode(array('code'=>1,'msg'=>'已存在推荐人，请先解除关系再绑定')));
	}
	
	
    if($parent['user_id'])
    {
	    $sql = "UPDATE " . $ecs->table('users') . " SET parent_id = '$parent_id' WHERE user_id = '$user_id'";
	    $db->query($sql);
    }
    else
    {
    	die(json_encode(array('code'=>1,'msg'=>'推荐人ID不存在')));
    }


	$content="编辑：".$user['user_name']."的推荐人设置为".$parent['user_name'];
    //记录管理员操作 
    admin_log2($content,'warn');
    /* 提示信息 */
	die(json_encode(array('code'=>0,'msg'=>'修改成功！')));
}
/*add yes123 2015-04-28 添加机构*/
elseif ($_REQUEST['act'] == 'add_institution')
{
	$user_id = $_POST['user_id'];
	$institution_id = $_POST['institution_id'];
    /* 检查权限 */
    admin_priv(action_list());
	$sql = "SELECT user_id,user_name,user_rank FROM" . $ecs->table('users') . " WHERE user_id =".$institution_id;
	ss_log("get institution by institution_id:".$sql);
	$institution = $db->getRow($sql);
	
	
	$user_rank = get_user_rank_info($institution['user_rank']);
	
	if($user_rank['rank_code']!=ORGANIZATION_USER){
		die(json_encode(array('code'=>1,'msg'=>'填写的ID不是渠道或团队长，无法添加')));
	}
	
	
	
	$sql = "SELECT user_name,institution_id FROM " . $ecs->table('users') . " WHERE user_id = '" .$user_id. "'";
    $user = $db->getRow($sql);
    
	if($user['institution_id']){
		die(json_encode(array('code'=>1,'msg'=>'已绑定其他渠道，请先解除关系再绑定')));
	}
	
	
    if($institution['user_id'])
    {
	    $sql = "UPDATE " . $ecs->table('users') . " SET institution_id = '$institution_id' WHERE user_id = '$user_id'";
	    $db->query($sql);
    }
    else
    {
    	die(json_encode(array('code'=>1,'msg'=>'机构ID不存在')));
    }


	$content="编辑：".$user['user_name']."的机构设置为".$institution['user_name'];
    //记录管理员操作 
    admin_log2($content,'warn');
    /* 提示信息 */
	die(json_encode(array('code'=>0,'msg'=>'修改成功！')));
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
	die(json_encode(array('code'=>0,'msg'=>'移除成功！')));
}

// 导出会员列表
elseif ($_REQUEST['act'] == 'export_users_list')
{
	export_user_info();
	
/*	if($_SESSION['admin_name']=='admin')
	{
		}
	else
	{
		 sys_msg($_LANG['priv_error'], 0, $link);
	}*/
}
/**
 *  返回用户列表数据
 *
 * @access  public
 * @param
 *
 * @return void
 */
function user_list()
{
	
    include_once(ROOT_PATH.'includes/lib_order.php');
    
    $result = get_filter();
    
    if ($result === false)
    {
        
        /* 过滤条件 */
        $filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
        {
            $filter['keywords'] = json_str_iconv($filter['keywords']);
        }
       
        //start add by wangcya , 20140903
       
        $filter['user_name'] = empty($_REQUEST['user_name']) ? '' : trim($_REQUEST['user_name']);
        $filter['real_name'] = empty($_REQUEST['real_name']) ? '' : trim($_REQUEST['real_name']);
        $filter['type'] = empty($_REQUEST['type']) ? '' : trim($_REQUEST['type']); //add yes123 2014-12-23 窗口化会员列表
        $filter['user_ids'] = empty($_REQUEST['user_ids']) ? '' : trim($_REQUEST['user_ids']); //add yes123 2014-12-23 窗口化会员列表
        

        $filter['mobile_phone'] = empty($_REQUEST['mobile_phone']) ? '' : trim($_REQUEST['mobile_phone']);
    
        
        $filter['email'] = empty($_REQUEST['email']) ? '' : trim($_REQUEST['email']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
        {
        	$filter['email'] = json_str_iconv($filter['email']);
        }

        
        $filter['rank'] = empty($_REQUEST['rank']) ? 0 : intval($_REQUEST['rank']);
        $filter['pay_points_gt'] = empty($_REQUEST['pay_points_gt']) ? 0 : intval($_REQUEST['pay_points_gt']);
        $filter['pay_points_lt'] = empty($_REQUEST['pay_points_lt']) ? 0 : intval($_REQUEST['pay_points_lt']);
        $filter['sort_by']    = empty($_REQUEST['sort_by'])    ? 'user_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC'     : trim($_REQUEST['sort_order']);
		
		//add yes123 2014-01-06 按照最后更新时间排序
        $filter['last_time']    = empty($_REQUEST['last_time'])    ? '' : trim($_REQUEST['last_time']);
        if($filter['last_time'])
        {
        	$filter['sort_by']='last_time';
        	$filter['sort_order']=$filter['last_time'];
        }
        
        $filter['referrer']    = empty($_REQUEST['referrer'])    ? '' : trim($_REQUEST['referrer']);
        $filter['referrer_real_name']    = empty($_REQUEST['referrer_real_name'])    ? '' : trim($_REQUEST['referrer_real_name']);
        $filter['d_name']    = empty($_REQUEST['d_name'])    ? '' : trim($_REQUEST['d_name']); //通过渠道名称查询
        $filter['institution_id']    = empty($_REQUEST['institution_id'])    ? '' : trim($_REQUEST['institution_id']); //通过渠道ID查询
        $filter['platform_id']    = empty($_REQUEST['platform_id'])    ? '' : trim($_REQUEST['platform_id']); //通过渠道ID查询
        
        $filter['province']    = empty($_REQUEST['province'])    ? '' : trim($_REQUEST['province']); //通过渠道ID查询
        
        $filter['check_status']    = empty($_REQUEST['check_status'])    ? CHECKED_CHECK_STATUS : trim($_REQUEST['check_status']); //通过渠道ID查询
        
        
       	$ex_where = ' WHERE 1' ;
		
		if($filter['institution_id'])
		{
			$ex_where .= " AND u.institution_id = $filter[institution_id] ";
		}
        
        if ($filter['keywords'])
        {
            $ex_where .= " AND u.user_name LIKE '%" . mysql_like_quote($filter['keywords']) ."%'";
        }
        
        //start add by wangcya , 20140903
        if ($filter['real_name'])
        {
        	$ex_where .= " AND u.real_name LIKE '%" . mysql_like_quote($filter['real_name']) ."%'";
        }
        
       	if ($filter['user_name'])
        {
        	$ex_where .= " AND u.user_name LIKE '%" . mysql_like_quote($filter['user_name']) ."%'";
        }
        
        if ($filter['mobile_phone'])
        {
        	$ex_where .= " AND u.mobile_phone LIKE '%" . mysql_like_quote($filter['mobile_phone']) ."%'";
        }
        if ($filter['email'])
        {
        	$ex_where .= " AND u.email LIKE '%" . mysql_like_quote($filter['email']) ."%'";
        }
        //end add by wangcya , 20140903

      
        
        //add yes123 2015-01-06 添加“是否禁用”
        $filter['is_disable'] = !empty($_REQUEST['is_disable']) ? $_REQUEST['is_disable']:-1;
        if($filter['is_disable']!=-1)
        {	
        	$ex_where .= " AND u.is_disable=$filter[is_disable] ";
        	
        }
        
        
        //add yes123 2015-03-13 按照注册时间查询
        $filter['start_reg_time'] = isset($_REQUEST['start_reg_time'])?$_REQUEST['start_reg_time']:"";
        $filter['end_reg_time'] = isset($_REQUEST['end_reg_time'])?$_REQUEST['end_reg_time']:"";
        if($filter['start_reg_time'])
        {
        	$ex_where .= " AND u.reg_time>=".strtotime($filter['start_reg_time']);
        }
        
        if($filter['end_reg_time'])
        {
        	$ex_where .= " AND u.reg_time<=".strtotime($filter['end_reg_time']);;
        }
        
        
        if ($filter['rank'])
        {
            $sql = "SELECT min_points, max_points, special_rank FROM ".$GLOBALS['ecs']->table('user_rank')." WHERE rank_id = '$filter[rank]'";
            $row = $GLOBALS['db']->getRow($sql);
            if ($row['special_rank'] > 0)
            {
                /* 特殊等级 */
                $ex_where .= " AND u.user_rank = '$filter[rank]' ";
            }
            else
            {
                $ex_where .= " AND u.rank_points >= " . intval($row['min_points']) . " AND u.rank_points < " . intval($row['max_points']);
            }
        }
        if ($filter['pay_points_gt'])
        {
             $ex_where .=" AND u.pay_points >= '$filter[pay_points_gt]' ";
        }
        if ($filter['pay_points_lt'])
        {
            $ex_where .=" AND u.pay_points < '$filter[pay_points_lt]' ";
        }
		
		if($_REQUEST['type']=='dialog_organ_list')
		{
			 $ex_where .=" AND u.is_institution =2  ";
		}
        
        
        if($filter['referrer'])
        {
        	//$ex_where .=" AND p.user_name ='$filter[referrer]'";
        	$ex_where.=" AND u.parent_id IN(SELECT user_id FROM bx_users WHERE user_name LIKE '%$filter[referrer]%')";
        	
        }
        
        if($filter['referrer_real_name'])
        {
        	//$ex_where .=" AND p.real_name ='$filter[referrer_real_name]'";
        	$ex_where.=" AND u.parent_id IN(SELECT user_id FROM bx_users WHERE real_name LIKE '%$filter[referrer_real_name]%')";
        }
        
        if($filter['d_name'])
        {
        	//$ex_where .=" AND d.d_name ='$filter[d_name]'";
        	$ex_where.=" AND u.institution_id IN(SELECT d_uid FROM bx_distributor WHERE d_name LIKE '%$filter[d_name]%')";
        }
        
        
        if($filter['province'])
        {
   			//$ex_where .=" AND ui.Province='$filter[province]' ";
   			$ex_where.=" AND u.user_id IN(SELECT uid FROM bx_user_info WHERE Province=$filter[province])";
        	
        }
        
        
        if($filter['platform_id'])
        {
        	$ex_where .=" AND u.platform_id = '$filter[platform_id]' ";
        }	
        if($filter['user_ids'])
        {
        	$ex_where .=" AND u.user_id IN($filter[user_ids]) ";
        }	
        
        
        
        
        $sql = "SELECT COUNT(u.user_id) FROM bx_users u  $ex_where ";
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        /* 分页大小 */
        $filter = page_and_size($filter);
        $sql = "SELECT
				  u.*
				FROM bx_users u $ex_where
				  ORDER BY $filter[sort_by] $filter[sort_order]
                  LIMIT $filter[start] , $filter[page_size] ";
         
        if($_REQUEST['type']=='dialog_organ_list')
        {
            $sql = "SELECT u.*
				FROM bx_users u $ex_where
				  ORDER BY $filter[sort_by] $filter[sort_order]
                  LIMIT $filter[start] , $filter[page_size] ";
        } 
        ss_log('user_list:'.$sql) ;    
        $filter['keywords'] = stripslashes($filter['keywords']);
        set_filter($filter, $sql);
    }
    else
    {
      
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
   
    $user_list = $GLOBALS['db']->getAll($sql);

    $count = count($user_list);
	
	//modify yes123 2015-01-07 显示推荐人,省份，渠道名称
	foreach ( $user_list as $key => $user ) {
       $user_list[$key]['reg_time'] = date('Y-m-d H:i', $user['reg_time']); //add by wangcya, 20141128
        
       //获取省份名称
   	   $sql = "SELECT region_name FROM bx_region WHERE region_id =" .
   				" (SELECT Province FROM bx_user_info WHERE uid=$user[user_id]) ";
       $region_name = $GLOBALS['db']->getOne($sql);
       $user_list[$key]['region_name'] = $region_name;
	   
	   //获取推荐人
	   if($user['parent_id'])
	   {
	   		$sql = "SELECT user_name,real_name FROM bx_users WHERE user_id = $user[parent_id] ";
        	$parent = $GLOBALS['db']->getRow($sql);
        	$user_list[$key]['referrer_user_name'] = $parent['user_name'];
        	$user_list[$key]['referrer_real_name'] = $parent['real_name'];
	   }
	   
	   //获取渠道名称
	   if($user['institution_id'])
	   {
	   		$sql = "SELECT d_name FROM bx_distributor WHERE d_uid = $user[institution_id] ";
        	$d_name = $GLOBALS['db']->getOne($sql);
        	$user_list[$key]['institution_username'] = $d_name;
	   }
	   
	   //如果审核未通过，获取原因
	   if($user['check_status']==NO_PASS_CHECK_STATUS)
	   {
	   	
	   		$sql = "SELECT check_result FROM bx_user_info WHERE uid = $user[user_id] ";
        	$check_result = $GLOBALS['db']->getOne($sql);
        	$user_list[$key]['check_result'] = $check_result;
	   	
	   }
	   
	   //会员等级
	   if($user['user_rank'])
	   {
	   		$sql = "SELECT rank_name,rank_code FROM bx_user_rank WHERE rank_id = $user[user_rank] ";
        	$user_rank_info = $GLOBALS['db']->getRow($sql);
        	$user_list[$key]['rank_name'] = $user_rank_info['rank_name'];
        	$user_list[$key]['rank_code'] = $user_rank_info['rank_code'];
	   }
	   
	   //最后更新时间格式化
	   if($user['last_update_time'])
	   {
		   $user_list[$key]['last_update_time'] = date("Y-m-d H:i",$user['last_update_time']);
	   }
	   
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



//add yes123 2015-08-13 会员审核通过，推荐人获取推荐奖励
function send_recommend_award($user)
{
	send_recommend_bonus($user);
	send_recommend_service_money($user);
	
}


//add yes123 2015-08-13 给推荐人发送代金券
function send_recommend_bonus($user)
{
	$user_id = $user['user_id'];
	$parent_id = $user['parent_id'];
	
	if(!$parent_id)
	{
		ss_log(__FUNCTION__.",审核通过，没有推荐人，不赠送代金券,user_id:".$user_id);
		return;
		
	}
	
	$parent = user_info_by_userid($parent_id);
	
	//查询是否赠送过
	$where=" WHERE source_type='".SEND_BY_PARENT."' AND source_id='$user_id' AND user_id='$parent_id' ";
	$sql = "SELECT COUNT(bonus_id) FROM bx_user_bonus $where ";
	ss_log(__FUNCTION__.",查询是否赠送过代金券:".$sql);
	$count = $GLOBALS['db']->getOne($sql);
	
	if($count)
	{
		ss_log(__FUNCTION__.",已赠送过代金券，不能重复赠送");
		return;
	}
	
	
	//赠送代金券
	$where = " WHERE rank_id=$parent[user_rank] AND is_enabled=1 AND rc_type='".RECOMMEND_GIVE_USER_BONUS_RANK."'";
	$sql = "SELECT * FROM bx_user_rank_config  $where ";
	ss_log(__FUNCTION__.",获取配置bx_user_rank_config,".$sql);
	$bonus_cfg = $GLOBALS['db']->getRow($sql);
	if($bonus_cfg)
	{
		$deadline = $bonus_cfg['deadline'];
		$start_date=time();
		$end_date=$start_date+24*3600*$deadline;

		date('Y-m-d H:i:s',strtotime('+1 day'));
		
		$bouns_data = array(
				'money'=>$bonus_cfg['value'],
				'user_id'=>$parent_id,
				'source_type'=>SEND_BY_PARENT,
				'use_s_date'=>$start_date,
				'use_e_date'=>$end_date,
				'source_id'=>$user_id
		
		);
		
		$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_bonus'), $bouns_data, "INSERT");
		
	}
	else
	{
		ss_log(__FUNCTION__.",推荐人用户等级为：".$user['user_rank'].",不赠送代金券");
	}
	
	
}

//add yes123 2015-08-13 给推荐人发送代金券
function send_recommend_service_money($user)
{
	$user_id = $user['user_id'];
	$parent_id = $user['parent_id'];
	
	if(!$parent_id)
	{
		ss_log(__FUNCTION__.",审核通过，没有推荐人，不赠送金币,user_id:".$user_id);
		return;
	}
	
	//查询是否赠送过，如果赠送过，就不重复赠送了
	$where="WHERE amount_type='".RECOMMEND_GIVE_SERVICE_MONEY_RANK."' AND user_id='$parent_id' AND cname='$user_id'";
	$sql = "SELECT COUNT(log_id) FROM bx_account_log $where";
	ss_log("查询是否赠送过推荐通过奖励：".$sql);
	$count = $GLOBALS['db']->getOne($sql);

	if(empty($count))
	{

		if($user['active_id'] == 0)
		{
			
			//当前注册用户不是吉林渠道的，才送钱
			if($user['institution_id'] != JILIN_INSTITUTION_ID)
			{
				$parent = user_info_by_userid($parent_id);
				//赠送金币
				$where = " WHERE rank_id=$parent[user_rank] AND is_enabled=1 AND rc_type='".RECOMMEND_GIVE_SERVICE_MONEY_RANK."'";
				$sql = "SELECT * FROM bx_user_rank_config  $where ";
				$service_money_cfg = $GLOBALS['db']->getRow($sql);
				if($service_money_cfg)
				{
					if($service_money_cfg['value']>0)
					{
						$desc = "新用户审核通过,赠送推荐人金币 ".$service_money_cfg['value']." 元！";
						log_account_change($parent_id, $service_money_cfg['value'], 0, 0, 0, $desc, 
							  ACT_OTHER,'','',$user_id,0,RECOMMEND_GIVE_SERVICE_MONEY_RANK,'service_money');
						
					}
				}
			
			}
			
			
		}
		else if($user['active_id'] > 0)
		{
			//给活动中的推荐人算钱
			include_once (ROOT_PATH . 'includes/hongdong_function.php');
        	$act_info = get_current_activity_by_id($user['active_id']);
        	if(!empty($act_info))//看来当前活动处于活动期间
        	{
        		$to_referee_money = $act_info['to_referee_money'];//给推荐人的钱
        		//有钱要赠送的情况
        		if(intval($to_referee_money*100)>0)
        		{
        			$desc = "新用户审核通过,通过活动[".$act_info['act_name']."],赠送推荐人账户金额 ".$to_referee_money."元！";
					log_account_change($parent_id, $to_referee_money, 0, 0, 0, $desc,
							  ACT_OTHER,'','',$user_id,0,RECOMMEND_GIVE_SERVICE_MONEY_RANK,'service_money');	
						
        		}
        	}
			
		}
	}
	
}

//add yes123 2015-03-30  导出会员信息
function export_user_info()
{
	
	include_once(ROOT_PATH . 'oop/lib/Excel/PHPExcel.php');
	
	
	$title=array('用户ID','用户名','姓名','手机号码','省份','推荐人','类型','是否启用','可用资金','注册日期','平台来源');

	$res = user_list();
	$user_list = array_reverse($res['user_list']);

	$objExcel = new PHPExcel();
	
	$objWriter = new PHPExcel_Writer_Excel5($objExcel);     // 用于其他版本格式   
	
		//设置当前的sheet索引，用于后续的内容操作。   
	$objExcel->setActiveSheetIndex(0);   
	$objActSheet = $objExcel->getActiveSheet();   
	
	$objActSheet->setTitle('sheet1');   

	//modify yes123 2015-01-19 如果是后台导出保单，需要导出支付方式和使用的余额
	
	$abc = array('A','B','C','D','E','F','G','H','I','J','K');
	/*设置特别表格的宽度*/  
	$objExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(10); //用户ID
	$objExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(20); //用户名
   	$objExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(20); //姓名
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(25);  //手机号
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(15);  //省份
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(15);  //推荐人
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(15);  //会员类型
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(10);  //是否启用
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(10);  //可用资金
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('J')->setWidth(25);  //注册日期
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('K')->setWidth(15);  //平台来源

    //设置内容居中
	
	$user_array= array();
	$user_array[0]=$title;
	
	if(is_array($user_list))    //add
	{ 
		//$title=array('用户ID','用户名','姓名','手机号码','省份','推荐人','类型','是否启用','可用资金','注册日期');
		foreach ($user_list as $key => $value ) 
		{
			$user[0]= $value['user_id']." ";
			$user[1]= $value['user_name']." ";
			$user[2]= $value['real_name'];
			$user[3]= $value['mobile_phone']." ";
			$user[4]= $value['region_name'];
			$user[5]= $value['referrer_real_name'];
			$user[6]= $value['rank_name'];
			$user[7]= $value['is_disable']==1?"否":"是";
			$user[8]= $value['user_money'];
			$user[9]= $value['reg_time'];
			$user[10]= $value['platform_id'];
			
			if(!$value['real_name'])
			{
				$user[1]= $value['user_name']." ";
			}
			
	 		$user_array[]=$user;
		}
	}
	$title_fontcolor="#ff6600";
	$title_bgcolor="#000000";
	

	foreach($user_array as $k => $v)
	{//每一个$k为一行数据
			$k++;
			foreach($v as $kk => $vs)
			{//每一个$vs是一行中的列
				$objActSheet->setCellValue($abc[$kk] . $k, $vs);  // 字符串内容  
				$objStyleA1 = $objActSheet->getStyle($abc[$kk] . $k);  
				$objAlignA1 = $objStyleA1->getAlignment();  
				$objAlignA1->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);    //左右居中  
				
				if($k==1)
				{
					//字体及颜色  
				    $objFontA1 = $objStyleA1->getFont();  
				    $objFontA1->setName('黑体');  
				    $objFontA1->setSize(12);  
				    $objFontA1->getColor()->setARGB($title_bgcolor);  
				    
				    $objStyleA1->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
    				$objStyleA1->getFill()->getStartColor()->setARGB($title_fontcolor); 
				}
			}
	}
	$outputFileName = date('Y-m-d H:i',time()).".xls";
	header("Content-Type:application/octet-stream;charset=utf-8");
	header('Content-Disposition: attachment; filename=' . $outputFileName); 
	$objWriter->save('php://output');
	
}

?>