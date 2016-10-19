<?php


function user_base_info($user_id=0)
{
	
    $sql = "SELECT u.*,u2.user_name as parent_username,d.d_name,ui.* FROM " .$GLOBALS['ecs']->table('users'). " u " .
    		" LEFT JOIN " . $GLOBALS['ecs']->table('users') . " u2 ON u.parent_id = u2.user_id " .
    		" LEFT JOIN " . $GLOBALS['ecs']->table('user_info') . " ui ON u.user_id = ui.uid " .
			" LEFT JOIN " . $GLOBALS['ecs']->table('distributor') ." d ON d.d_uid=u.user_id  ".
			"WHERE u.user_id='$user_id'";
	
	$user = $GLOBALS['db']->getRow($sql);
	
	
	$res = array();
	
	/* 载入省份 */
    $res['province_list']= get_regions(1,1);
    
    
    //城市
    if($user['Province'])
    {
    	$res['city_list']=get_regions(2,$user['Province']);
    }
    //地区
    if($user['city'])
    {
    	$res['district_list']=get_regions(3,$user['city']);
    }

    if ($user)
    {
        $user['formated_user_money'] = price_format($user['user_money']);
        //add yes123 2015-06-10 服务费和奖励格式化
        $user['formated_service_money'] = price_format($user['service_money']);
        $user['formated_frozen_money'] = price_format($user['frozen_money']);
		//add yes123 2015-04-28 获取渠道信息
		if($user['institution_id'])
		{
			$sql = "SELECT d_name  FROM bx_distributor WHERE d_uid='$user[institution_id]'";
			$d_name = $GLOBALS['db']->getOne($sql);
			$user['d_name'] = $d_name;
		}
    }
    
 	$res['user']=$user;
 	
    return $res;
}
//获取所有渠道
function get_distributor($user_id=0)
{
	
	$user_rank = get_user_rank_info(0,ORGANIZATION_USER);
	
	$sql = "SELECT d.d_uid,d.d_name FROM bx_users u 
		   INNER JOIN  bx_distributor d ON u.user_id=d.d_uid WHERE u.user_id<>'$user_id' AND u.user_rank='".$user_rank['rank_id']."'";
	$organ_list = $GLOBALS['db']->getAll($sql);
	return $organ_list;
}


function action_list(){
	$user_id=$_SESSION['admin_id'];
    $sql = 'SELECT action_list FROM bx_admin_user WHERE user_id='.$user_id;
    return $GLOBALS['db'] ->getOne($sql);
}
?>
