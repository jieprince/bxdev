<?php


/**
 * add yes123 2014-12-31
 * 用户中心类
 * 
 */
require_once (ROOT_PATH . 'baoxian/source/function_debug.php');
include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
class Distrbutor 
{
	
	
	/*通过渠道ID获取下面所有的保单个数和总保费*/
	public function get_accumulate_data_by_organ($organ_id)
	{
		
		$user_rank = get_user_rank_info(0,ORGANIZATION_USER);
		$rank_id = $user_rank['rank_id'];
		
		$data=array();
		//获取渠道自己的保单个数和总保费
		$sql = "SELECT COUNT(policy_id) AS policy_num,SUM(total_premium) AS total_premium FROM t_insurance_policy WHERE (organ_user_id = '$organ_id') OR (agent_uid='$organ_id') AND policy_status='insured'";
		$res = $GLOBALS['db']->getRow($sql);  
		if(!empty($res))
		{
			$data['policy_num'] = $res['policy_num'];
			$data['total_premium'] = $res['total_premium'];
		}
		
		//获取普通会员数量
		
	   	$sql = "SELECT COUNT(user_id) FROM bx_users WHERE institution_id = '$organ_id' AND user_rank !='".$rank_id."'";
		$user_num = $GLOBALS['db']->getOne($sql);
		if($user_num)
		{	
			$data['user_num'] = $user_num;
		}
		
		//子渠道数量	
		$sql = "SELECT user_id FROM bx_users WHERE institution_id = '$organ_id' AND user_rank='".$rank_id."'";
		$organ_list = $GLOBALS['db']->getAll($sql);
		$data['organ_num'] = count($organ_list);
		
		foreach ($organ_list as $key => $organ ) 
		{
	    	
			$res = $this->get_accumulate_data_by_organ($organ['user_id']);
			$data['user_num'] += $res['user_num'];
			$data['policy_num'] += $res['policy_num'];
			$data['total_premium'] += $res['total_premium'];
			$data['organ_num'] += $res['organ_num'];
	
		}
		
		return $data;
		
	}
	
	//渠道部门列表
	public function organDepartmentList($organ_id=0)
	{
		if(!$organ_id)
		{
			$organ_id = $_SESSION['user_id'];
		}
		
		$sql= " SELECT * FROM bx_organ_department WHERE organ_id=$organ_id";	
		$department_list = $GLOBALS['db']->getAll($sql);
		
		//获取会员个数
		foreach ( $department_list as $key => $department ) 
		{
       		$sql = "SELECT COUNT(user_id) FROM bx_users WHERE department_id=$department[department_id] ";
       		$user_num = $GLOBALS['db']->getOne($sql);
       		$department_list[$key]['user_num'] = $user_num;
       		
		}
		
		
		return $department_list;
	}	
	
	
	public function saveOrganDepartmentList($organ_id=0)
	{
		$save_type = isset($_REQUEST['save_type'])?$_REQUEST['save_type']:'insert';
		
		if(!$organ_id)
		{
			$organ_id = $_SESSION['user_id'];
		}
		$data['department_name'] = isset($_REQUEST['department_name'])?$_REQUEST['department_name']:'';
		$data['organ_id'] = $organ_id;
		if($save_type=='insert')
		{
			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('organ_department'), $data, 'INSERT');	
		}
		else
		{
			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('organ_department'), $data, 'UPDATE','department_id='.$_REQUEST['department_id']);	
		}
		
	}
}
?>
