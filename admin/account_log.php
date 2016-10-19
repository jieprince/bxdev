<?php

/**
 * ECSHOP 管理中心账户变动记录
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: account_log.php 17217 2011-01-19 06:29:08Z liubo $
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH . 'includes/lib_order.php');
include_once(ROOT_PATH . 'baoxian/source/function_debug.php');

/*------------------------------------------------------ */
//-- 办事处列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{	
		/* 检查参数 */
	$user_id = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);
	    if ($user_id <= 0)
	    {
	    	sys_msg('invalid param');
	    }
	

    $user = user_info($user_id);
    //add yes123 三个账户分开，重新计算总金额
    $user['formated_user_money'] = price_format($user['user_money'], false);
    
    if (empty($user))
    {
        sys_msg($_LANG['user_not_exist']);
    }
    

    if (empty($_REQUEST['account_type']) || !in_array($_REQUEST['account_type'],
        array('user_money', 'frozen_money', 'rank_points', 'pay_points')))
    {
        $account_type = '';
    }
    else
    {
        $account_type = $_REQUEST['account_type'];
    }
    $smarty->assign('account_type', $account_type);
    $smarty->assign('incoming_type', $_REQUEST['incoming_type']);

    $smarty->assign('ur_here',      $_LANG['account_list']);
    $smarty->assign('action_link',  array('text' => $_LANG['add_account'], 'href' => 'account_log.php?act=add&user_id=' . $user_id));
    $smarty->assign('full_page',    1);
	

	$account_list = get_accountlist($user_id, $account_type);

 	$smarty->assign('user_id', $user_id);
    $smarty->assign('account_list', $account_list['account']);
    $smarty->assign('filter',       $account_list['filter']);
	$smarty->assign('total', $user['formated_user_money']);
          
    $smarty->assign('user', $user);
    $smarty->assign('record_count', $account_list['record_count']);
    $smarty->assign('page_count',   $account_list['page_count']);

    assign_query_info();
    $smarty->display('account_list.htm');
}

/**
 * 2014-08-14 yaoyuanfu
 * 收入排行统计
 */
if ($_REQUEST['act'] == 'revenue_ranking'){
	
	require(ROOT_PATH . 'languages/zh_cn/admin/account_log.php');
	
	include_once (ROOT_PATH . 'includes/class/Statistics.class.php');
	$statistics = new Statistics;
	$res = $statistics->statisticList($filter);
	
	$filter = $res['filter'];
    $smarty->assign('ur_here',     $_LANG['revenue_ranking'] );
    $smarty->assign('action_link',  array('text' => $_LANG['add_account'], 'href' => 'account_log.php?act=add&user_id=' . $user_id));
    $smarty->assign('full_page',    1);
	
	
	
	$smarty->assign('page_count',  $res['page_count']);
	$smarty->assign('filter', $res['filter']);
	$smarty->assign('data_list', $res['data']);
	$smarty->assign('record_count', $res['record_count']);
    assign_query_info();


	$smarty->display('record_count.htm');
}

if ($_REQUEST['act'] == 'web_account_list')
{	
	$sql="SELECT user_id FROM ". $GLOBALS['ecs']->table('users') ."WHERE user_name='ebaoins_user'";
	$user_id= $GLOBALS['db']->getOne($sql);
	
	$user = user_info($user_id);
	               	//处理最近时间
	if(trim($_REQUEST['recent'])!=''){
		require_once(ROOT_PATH . 'languages/zh_cn/user.php');
			$current_time = time();
			$old_time="";
			//最近一周
			if(trim($_REQUEST['recent'])==$_LANG['one_week']){
				$old_time = $current_time-(86400*7);
			}elseif(trim($_REQUEST['recent'])==$_LANG['one_month']){
				//最近一个月
				$old_time = $current_time-(86400*30);
			}else{ 
				//剩下的就是最近三个月了
				$old_time = $current_time-(86400*30*3);
			}
			
			$_REQUEST['s_change_time']=date('Y-m-d H:i',$old_time);
			$_REQUEST['e_change_time']=date('Y-m-d H:i',$current_time);
	}
	
	$conditions = array(
        'user_id'       => $user_id,
        's_change_time' =>$_REQUEST['s_change_time'],
        'change_desc' =>$_REQUEST['change_desc'],
        'e_change_time' =>$_REQUEST['e_change_time']
    );
   
   
	$smarty->assign('conditions', $conditions);
	$smarty->assign('user', $user);

	
	
    $smarty->assign('ur_here',      $_LANG['account_list']);
    $smarty->assign('action_link',  array('text' => $_LANG['add_account'], 'href' => 'account_log.php?act=add&user_id=' . $user_id));
    $smarty->assign('full_page',    1);
	
	
	$account_list = get_accountlist($user_id, $account_type,"list");	

 
    $smarty->assign('account_list', $account_list['account']);
    $smarty->assign('filter',       $account_list['filter']);
	$smarty->assign('total', $account_list['total']);
    
    $smarty->assign('ur_here', "实际账户记录");    
    
    $smarty->assign('record_count', $account_list['record_count']);
    $smarty->assign('page_count',   $account_list['page_count']);

    assign_query_info();
    $smarty->display('ebaoins_user.htm');
}


if ($_REQUEST['act'] == 'org_list')
{
    /* 检查参数 */
    $user_id = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);
    if ($user_id <= 0)
    {
        sys_msg('invalid param');
    }
    $user = user_info($user_id);
    if (empty($user))
    {
        sys_msg($_LANG['user_not_exist']);
    }
    $smarty->assign('user', $user);

    if (empty($_REQUEST['account_type']) || !in_array($_REQUEST['account_type'],
        array('user_money', 'frozen_money', 'rank_points', 'pay_points')))
    {
        $account_type = '';
    }
    else
    {
        $account_type = $_REQUEST['account_type'];
    }
    $smarty->assign('account_type', $account_type);

    $smarty->assign('ur_here',      $_LANG['account_list']);
    $smarty->assign('ur_here',      "<a href='privilege.php?act=organization_list'>渠道列表</a><a href='account_log.php?act=org_list&user_id=$user_id'>-会员账户变动明细</a>");
    $smarty->assign('action_link',  array('text' => $_LANG['add_account'], 'href' => 'account_log.php?act=add&user_id=' . $user_id));
    $smarty->assign('full_page',    1);

    $account_list = get_accountlist($user_id, $account_type,'org_list');
    
    $smarty->assign('account_list', $account_list['account']);
    $smarty->assign('filter',       $account_list['filter']);
    $smarty->assign('record_count', $account_list['record_count']);
    $smarty->assign('page_count',   $account_list['page_count']);

    assign_query_info();
    $smarty->display('account_list.htm');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    /* 检查参数 */
    $user_id = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);
    if ($user_id <= 0)
    {
        sys_msg('invalid param');
    }
    $user = user_info($user_id);
    if (empty($user))
    {
        sys_msg($_LANG['user_not_exist']);
    }
    $smarty->assign('user', $user);

    if (empty($_REQUEST['account_type']) || !in_array($_REQUEST['account_type'],
        array('user_money', 'frozen_money', 'rank_points', 'pay_points')))
    {
        $account_type = '';
    }
    else
    {
        $account_type = $_REQUEST['account_type'];
    }
    $smarty->assign('account_type', $account_type);

	$account_list = get_accountlist($user_id, $account_type);
    
    
    $smarty->assign('account_list', $account_list['account']);
	
	$smarty->assign('total', $account_list['total']);
    
    $smarty->assign('filter',       $account_list['filter']);
    $smarty->assign('record_count', $account_list['record_count']);
    $smarty->assign('page_count',   $account_list['page_count']);

    make_json_result($smarty->fetch('account_list.htm'), '',
        array('filter' => $account_list['filter'], 'page_count' => $account_list['page_count']));
}


/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'web_account_list_query')
{
    /* 检查参数 */
    $user_id = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);
    if ($user_id <= 0)
    {
        sys_msg('invalid param');
    }
    $user = user_info($user_id);
    if (empty($user))
    {
        sys_msg($_LANG['user_not_exist']);
    }
    $smarty->assign('user', $user);

    if (empty($_REQUEST['account_type']) || !in_array($_REQUEST['account_type'],
        array('user_money', 'frozen_money', 'rank_points', 'pay_points')))
    {
        $account_type = '';
    }
    else
    {
        $account_type = $_REQUEST['account_type'];
    }
    $smarty->assign('account_type', $account_type);

	$account_list = get_accountlist($user_id, $account_type);
    
    
    $smarty->assign('account_list', $account_list['account']);
	
	$smarty->assign('total', $account_list['total']);
    
    $smarty->assign('filter',       $account_list['filter']);
    $smarty->assign('record_count', $account_list['record_count']);
    $smarty->assign('page_count',   $account_list['page_count']);

    make_json_result($smarty->fetch('ebaoins_user.htm'), '',
        array('filter' => $account_list['filter'], 'page_count' => $account_list['page_count']));
}

elseif ($_REQUEST['act'] == 'revenue_query')
{
	$smarty->assign('account_type', $account_type);
	
	include_once (ROOT_PATH . 'includes/class/Statistics.class.php');
	$statistics = new Statistics;
	$res = $statistics->statisticList($filter);
    
    $smarty->assign('data_list', $res['data']);
    $smarty->assign('filter',       $res['filter']);
          
    
    $smarty->assign('record_count', $res['record_count']);
    $smarty->assign('page_count',   $res['page_count']);
 	$smarty->assign('filter',       $res['filter']);
    make_json_result($smarty->fetch('record_count.htm'), '',
      array('filter' => $res['filter'], 'page_count' => $res['page_count']));
}

/*------------------------------------------------------ */
//-- 调节账户
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    /* 检查权限 */
    admin_priv('account_manage');
    /* 检查参数 */
    $user_id = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);
    if ($user_id <= 0)
    {
        sys_msg('invalid param');
    }
    $user = user_info($user_id);
    if (empty($user))
    {
        sys_msg($_LANG['user_not_exist']);
    }
    $smarty->assign('user', $user);
	
    /* 显示模板 */
    $smarty->assign('ur_here', $_LANG['add_account']);
    $smarty->assign('ur_here',      "<a href='privilege.php?act=organization_list'>渠道列表</a><a href='account_log.php?act=org_list&user_id=$user_id'>-会员账户变动明细</a>-调节会员账户");
    $smarty->assign('action_link', array('href' => 'account_log.php?act=list&user_id=' . $user_id, 'text' => $_LANG['account_list']));
    assign_query_info();
    $smarty->display('account_info.htm');
}

//add yes123 2014-12-23 发送短信
elseif ($_REQUEST['act'] == 'send_sms') {
	include_once(ROOT_PATH.'sdk_sms.php');
	$content = trim($_REQUEST['content']);
	$content = iconv( "UTF-8", "gb2312//IGNORE" ,$content);
	$id = $_REQUEST['id'];
	$sql = "SELECT mobile_phone FROM " . $GLOBALS['ecs']->table('users') .
	" WHERE user_id =".$id;
	
	$mobile_phone = $GLOBALS['db']->getOne($sql);
	if($mobile_phone)
	{
		$result = gxmt_post($sdk_sn,$sdk_pwd,$mobile_phone,$content);
		if($result==-4){
			//1.发邮件给管理员
			 $content = "短信余额不足,请及时充值!";
				//send_mail( $_CFG['shop_name']."管理员", "25190080@qq.com","短信余额不足", $content,0);
			//2.写日志
			$sql = "INSERT INTO " . $GLOBALS['ecs']->table('admin_log') . " (log_time, user_id, log_info, ip_address, log_level) " .
					"VALUES (".time().", '0', '$content', '".real_ip()."', 'error')";
						
			$GLOBALS['db']->query($sql);
			$datas = json_encode(array('code'=>$result,'msg'=>'发送失败'));
	
		}
		else
		{
			$datas = json_encode(array('code'=>$result,'msg'=>'发送成功','sql'=>$sql));
		}
		
		die($datas);
	}

}

/*------------------------------------------------------ */
//-- 提交添加、编辑办事处
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'insert' || $_REQUEST['act'] == 'update')
{
    /* 检查权限 */
    admin_priv('account_manage');
    $token=trim($_POST['token']);
    if($token!=$_CFG['token'])
    {
        sys_msg($_LANG['no_account_change'], 1);
    }



    /* 检查参数 */
    $user_id = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);
    
    
    if ($user_id <= 0)
    {
        sys_msg('invalid param');
    }
    $user = user_info($user_id);
    if (empty($user))
    {
        sys_msg($_LANG['user_not_exist']);
    }

    /* 提交值 */
    $change_desc    = sub_str($_POST['change_desc'], 255, false);
    $user_money     = floatval($_POST['add_sub_user_money']) * abs(floatval($_POST['user_money']));
    $frozen_money   = floatval($_POST['add_sub_frozen_money']) * abs(floatval($_POST['frozen_money']));
    $rank_points    = floatval($_POST['add_sub_rank_points']) * abs(floatval($_POST['rank_points']));
    $pay_points     = floatval($_POST['add_sub_pay_points']) * abs(floatval($_POST['pay_points']));

    if ($user_money == 0 && $frozen_money == 0 && $rank_points == 0 && $pay_points == 0)
    {
        sys_msg($_LANG['no_account_change']);
    }


	/*调节会员金额时，记录日志  2014-09-25 yes123*/
	$sql="SELECT user_name FROM ". $GLOBALS['ecs']->table('users') . " WHERE user_id=".$user_id;
	$user_name = $GLOBALS['db']->getOne($sql);
	
	$log_info=$_POST['add_sub_user_money']>0?"调节".$user_name."账户,增加:".$user_money."元":"调节".$user_name."账户,减少:".$user_money."元";
    admin_log2($log_info);
	
    /* 保存 */
    log_account_change($user_id, $user_money, $frozen_money, $rank_points, $pay_points, $change_desc, ACT_ADJUSTING,$order_sn = '',
	$source_user_id = '',$desc = '');

    /* 提示信息 */
    $links = array(
        array('href' => 'account_log.php?act=list&user_id=' . $user_id, 'text' => $_LANG['account_list'])
    );
    sys_msg($_LANG['log_account_change_ok'], 0, $links);
}


/*------------------------------------------------------ */
//-- 导出统计数据
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'revenue_ranking_list' )
{
	include_once (ROOT_PATH . 'includes/class/Statistics.class.php');
	$statistics = new Statistics;
	$ids = $_REQUEST['user_ids'];
	$res = $statistics->exportStatisticsList($ids);
	
}

/* 导出用户资金明细*/
elseif ($_REQUEST['act'] == 'export_account_detail' )
{
	if (empty($_REQUEST['account_type']) || !in_array($_REQUEST['account_type'],
        array('user_money', 'frozen_money', 'rank_points', 'pay_points')))
    {
        $account_type = '';
    }
    else
    {
        $account_type = $_REQUEST['account_type'];
    }
    $user_id = $_GET['user_id'];
    $user_name = $_GET['user_name'];
    
	$account_list = get_accountlist($user_id, $account_type);
	$data = $account_list['account'];
	
	
	include_once(ROOT_PATH . 'oop/lib/Excel/PHPExcel.php');
	
	
	$title=array('账户变动时间','变动原因','类型','金额','来源会员','订单号','保单号');
	
	$objExcel = new PHPExcel();
	$objWriter = new PHPExcel_Writer_Excel5($objExcel);     // 用于其他版本格式   
		//设置当前的sheet索引，用于后续的内容操作。   
	$objExcel->setActiveSheetIndex(0);   
	$objActSheet = $objExcel->getActiveSheet();   
	
	$objActSheet->setTitle('sheet1');   

	//modify yes123 2015-01-19 如果是后台导出保单，需要导出支付方式和使用的余额
		
	$abc = array('A','B','C','D','E','F','G');
	/*设置特别表格的宽度*/  
	$objExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(20); //账户变动时间
   	$objExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(30); //变动原因
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(25);  //类型
	$objExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(15); //金额
   	$objExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(20); //来源会员
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(25);  //订单号
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(15);  //保单号

    //设置内容居中
	
	$user_array= array();
	$user_array[0]=$title;
	
	if(is_array($data))    //add
	{ 
		foreach ($data as $key => $value ) 
		{
			$cname_user_name='';
	        if($value['cname'])
	        {
	        	$sql = "SELECT user_name FROM " . $GLOBALS['ecs']->table('users')." WHERE user_id='$value[cname]'";
	        	$cname_user_name = $GLOBALS['db']->getOne($sql);
	        }
			
			$user[0]= $value['change_time']." ";
			$user[1]= $value['change_desc']." ";
			$user[2]= $value['incoming_type']." ";
			$user[3]= $value['user_money']." ";
			$user[4]= $cname_user_name;
			$user[5]= $value['order_sn']." ";
			$user[6]= $value['policy_id']." ";
			
			
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
	$outputFileName = $user_name."_".date('Y-m-d H:i',time()).".xls";
	header("Content-Type:application/octet-stream;charset=utf-8");
	header('Content-Disposition: attachment; filename=' . $outputFileName); 
	$objWriter->save('php://output');
	
	
}


/**
 * 取得账户明细
 * @param   int     $user_id    用户id
 * @param   string  $account_type   账户类型：空表示所有账户，user_money表示可用资金，
 *                  frozen_money表示冻结资金，rank_points表示等级积分，pay_points表示消费积分
 * @return  array
 */
function get_accountlist($user_id, $account_type = '')
{   
	
	global $income_type_list;
    /* 初始化分页参数 */
    $filter = array(
        'user_id'       => $user_id,
        's_change_time' =>$_REQUEST['s_change_time'],
        'change_desc' =>trim($_REQUEST['change_desc']),
        'user_name' =>trim($_REQUEST['user_name']),
        'real_name' =>trim($_REQUEST['real_name']),
        'order_sn' =>trim($_REQUEST['order_sn']),
        'incoming_type' =>$_REQUEST['incoming_type'],
        'e_change_time' =>$_REQUEST['e_change_time']
    );
	

	$where_sql = " WHERE user_id ='$user_id' ";
	$order_by_sql=" ORDER BY log_id DESC";

   	if($filter['change_desc']){
 		$where_sql.= " AND change_desc Like '%".$filter['change_desc']."%' ";
    }  
        
    if($_REQUEST['s_change_time']){
    		$where_sql.=" AND change_time >='".local_strtotime($_REQUEST['s_change_time'])."'";
    }
    if($_REQUEST['e_change_time']){
    		$where_sql.=" AND change_time <='".local_strtotime($_REQUEST['e_change_time'])."'";
    }
    
    if($filter['order_sn']){
    		$where_sql.=" AND order_sn = '".$filter['order_sn']."' ";
    }

    
    $count_sql="SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('account_log');
    $filter['record_count'] = $GLOBALS['db']->getOne($count_sql.$where_sql);
    $filter = page_and_size($filter);
  
    /* 查询记录 */
    if($_REQUEST['log_ids'])
    {
    	$where_sql.=" AND log_id IN($_REQUEST[log_ids])";
        $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('account_log').$where_sql.$order_by_sql;
    }
    else
    {
	    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('account_log').$where_sql.$order_by_sql." LIMIT $filter[start] , $filter[page_size] ";
    	
    }
    $res = $GLOBALS['db']->getAll($sql);
    
    foreach ( $res as $key => $value ) {
        $res[$key]['change_time'] = date('Y-m-d H:i', $value['change_time']); 
        if($value['cname'])
        {
        	$sql = "SELECT user_id,user_name,real_name FROM " . $GLOBALS['ecs']->table('users')." WHERE user_id='$value[cname]'";
        	$cname_arr = $GLOBALS['db']->getRow($sql);
        	$res[$key]['cname_user_id'] = $cname_arr['user_id'];
        	$res[$key]['cname_user_name'] = $cname_arr['user_name'];
        	$res[$key]['cname_real_name'] = $cname_arr['real_name'];
        	
        }
        
        
       	if($value['incoming_type'])
        {
	        $res[$key]['incoming_type'] = $income_type_list[$value['incoming_type']];
        }
	}

    
    return array('account' => $res,
	    		 'filter' => $filter, 
	    		'page_count' => $filter['page_count'], 
	    		'record_count' => $filter['record_count']
	    		);
}
/**
 * modify yes123 2014-12-10 基本数据统计，推荐人数，总收入，消费总额，账户余额  start
 * 收入排行统计   
 */
function revenue_ranking_list(){
	require(ROOT_PATH . 'languages/zh_cn/admin/account_log.php');
	 /* 初始化分页参数 */
    $filter = array(
        'page' =>trim($_REQUEST['page']),
        's_change_time' =>$_REQUEST['s_change_time'],
        'e_change_time' =>$_REQUEST['e_change_time'],
        'user_name' =>trim($_REQUEST['user_name']),
        'real_name' =>trim($_REQUEST['real_name']),
        'incoming_type' =>$_REQUEST['incoming_type'],
        'order' =>$_REQUEST['order'],
        'sort' =>$_REQUEST['sort']
    );
	
	
	$select_sql = "SELECT SUM(a.user_money) AS total, b.user_name,b.real_name,b.user_money,b.user_id FROM " .
	$GLOBALS['ecs']->table('account_log')." a ," . $GLOBALS['ecs']->table('users')." b WHERE  a.user_id=b.user_id  ";
	
	$where_sql=" ";
	
	$count_sql="SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('account_log') ." a ," . $GLOBALS['ecs']->table('users')." b  WHERE  a.user_id=b.user_id ";
		
	$groupBy=" GROUP BY a.user_id ";
	$orderBy=" ORDER BY SUM(a.user_money)  DESC ";
	if($_REQUEST['order_by_money']!=''){
		if(trim($_REQUEST['order_by_money'])=='DESC'){
			$orderBy=" ORDER BY SUM(a.user_money)  DESC ";
			$filter['order_by_money']='DESC';
		}elseif(trim($_REQUEST['order_by_money'])=='ASC'){
			$orderBy=" ORDER BY SUM(a.user_money)  ASC ";
			$filter['order_by_money']='ASC';
		}
	}
	
		//处理最近时间
	if(trim($_REQUEST['recent'])!=''){
		require_once(ROOT_PATH . 'languages/zh_cn/user.php');
			$current_time = time();
			$old_time="";
			//最近一周
			if(trim($_REQUEST['recent'])==$_LANG['one_week']){
				$old_time = $current_time-(86400*7);
			}elseif(trim($_REQUEST['recent'])==$_LANG['one_month']){
				//最近一个月
				$old_time = $current_time-(86400*30);
			}else{ 
				//剩下的就是最近三个月了
				$old_time = $current_time-(86400*30*3);
			}
			
			$filter['s_change_time']=$old_time;
			$filter['e_change_time']=$current_time;
	}
	
	
		if(trim($_REQUEST['user_name'])!=''){
			//同过用户名查询ID
			$user_id_sql ="SELECT user_id FROM " . $GLOBALS['ecs']->table('users') ." WHERE user_name='".trim($_REQUEST['user_name'])."' ";
			$user_id = $GLOBALS['db']->getOne($user_id_sql);
			
			$select_sql = "SELECT SUM(a.user_money) AS total,b.user_id,b.user_name FROM " .
				$GLOBALS['ecs']->table('account_log')." a ," . $GLOBALS['ecs']->table('users')." b";
			$where_sql.= " WHERE  a.user_id=b.user_id AND a.user_id=".$user_id;
			$groupBy="";
			$orderBy="";
		}
		
		//处理类型条件，渠道还是个人	   
	   $incoming_type = trim($_REQUEST['incoming_type']);
	   if($incoming_type==$_LANG['quan_bu']){
	   		//$where_sql.= " AND incoming_type IS NOT NULL AND incoming_type <> '' ";
	   }
	   
	   if($incoming_type!='' && $incoming_type!=$_LANG['quan_bu']){
	 		$where_sql.= " AND incoming_type='".$incoming_type."' ";
	   }

		//处理时间条件
		if($filter['s_change_time']!=''){
	    		$where_sql.=" AND change_time >='".$filter['s_change_time']."'";
	    }
	    if($filter['e_change_time']!=''){
	    		$where_sql.=" AND change_time <='".$filter['e_change_time']."'";
	    }
	   
	    if($filter['real_name']!=''){
	    	$userIdByRealName="SELECT user_id FROM ".$GLOBALS['ecs']->table('users')." WHERE real_name='".$filter['real_name']."'";
	    	$userId = $GLOBALS['db']->getOne($userIdByRealName);
	    	if($userId){
	    		$where_sql.=" AND a.user_id =".$userId." ";
	    	}
	    }
	    

   	//计算总记录数
	$arr_all = $GLOBALS['db']->getAll($count_sql.$where_sql.$groupBy);
	$filter['record_count'] = count($arr_all);
    $filter = page_and_size($filter);
    
    ss_log( $select_sql.$where_sql.$groupBy.$orderBy."LIMIT ".$filter['start'].",".$filter['page_size']);
    /* 查询记录 */
 	$res = $GLOBALS['db']->getAll($select_sql.$where_sql.$groupBy.$orderBy."LIMIT ".$filter['start'].",".$filter['page_size']);
	
	$arr = array();
	foreach ( $res as $key => $value ) {
      //获取会员总消费
        $user_id = $value['user_id'];
        $sql="SELECT sum(user_money) as xiaofei_total FROM ". $GLOBALS['ecs']->table('account_log') ."WHERE user_money<0  AND user_id=".$user_id;
        $value['xiaofei_total']=$GLOBALS['db']->getOne($sql);
       
      //add yes123 2014-12-10 获取推荐数
        $sql="SELECT count(*) as recommend_total FROM ". $GLOBALS['ecs']->table('users') ."WHERE parent_id=".$user_id;
        $value['recommend_total']=$GLOBALS['db']->getOne($sql);
        $arr[]=$value;
       
	}
	if($filter['s_change_time']!='' || $filter['s_change_time']!=null){
		$filter['s_change_time']=date('Y-m-d H:i',$filter['s_change_time']);
	}
	if($filter['e_change_time']!='' || $filter['e_change_time']!=null){
		$filter['e_change_time']=date('Y-m-d H:i',$filter['e_change_time']);
	}
	//echo "<pre>";print_r($arr);
    return array('revenue_ranking_list' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count'],'user_name'=>$_REQUEST['user_name']);
	
}

?>