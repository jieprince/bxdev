<?php


/**
 * add wangcya, 2014-12-31
 * 定期执行任务的类
 *
 */
//在这里不能用./等相对路径，因为执行环境不一样
require_once (S_ROOT . '/source/function_debug.php');

class TaskActive {

	public function TaskActive()
	{
		
	}
	
	public function task_post_policy()
	{
		global $_SGLOBAL;
		
		ss_log_task("into ".__FUNCTION__);
		//-----------------查询未投保的保单开始-----------
		//$wheresql = 'pay_statua = 2';
		//$sql = "SELECT * FROM t_insurance_policy WHERE $wheresql LIMIT 1" ;
		//查找到已经是支付状态，并且投保状态是saved。
		//$sql = "SELECT * FROM t_insurance_policy WHERE pay_status='2' AND policy_status='payed'" ;
		$sql = "SELECT * FROM t_insurance_policy WHERE policy_status='payed'" ;
		ss_log_task($sql);
		
		$query = $_SGLOBAL['db']->query($sql);
		$count =0;
		
		$list_ids = array();
		ss_log_task("将要循环执行");
		while($value = $_SGLOBAL['db']->fetch_array($query))
		{
		
			$policy_id = $value['policy_id'];
			$order_sn = $value['order_sn'];
			ss_log_task("已支付未投保的异常单子有： policy_id: ".$policy_id." order_sn: ".$order_sn);
			
			$list_ids[] = $policy_id;
		
		
			$count++;
		
			//////////////////////////////////////////////////////////
		
		}
		
		ss_log_task("记录异常单子个数：".$count);
		//add by wangcya, 20150325,前面先记录起来，下面进行投保，因为投保可能被阻塞或者被崩溃中断。
		$count_exe = 0;
		foreach($list_ids as $key=>$value)
		{
			$policy_id = $value;
			
			ss_log_task("将要投保的policy_id: ".$policy_id);
			
			$result_attr = post_policy($policy_id);
			
			$result = $result_attr['retcode'];
			$retmsg = $result_attr['retmsg'];
			////////////////////////////////////////////////////////////
			if($result==0)
			{
				ss_log_task("追加投保成功，policy_id： ".$policy_id);
			}
			else
			{
				ss_log_task("追加投保失败，policy_id： ".$policy_id);
			}
			
			$count_exe++;
		}
		
		ss_log_task("计划任务执行结束，本次个数：".$count_exe);
	}//task_post_policy
	
	//定期获取那些已经投保成功但是还没获取到电子保单
	public function task_get_policy_file()
	{
		global $_SGLOBAL;
	
		ss_log_task("into ".__FUNCTION__);
		//-----------------查询未投保的保单开始-----------
		//$wheresql = 'pay_statua = 2';
		//$sql = "SELECT * FROM t_insurance_policy WHERE $wheresql LIMIT 1" ;
		//查找到已经是支付状态，并且投保状态是insured,并且电子保单下载状态为0的保单。
		$sql = "SELECT * FROM t_insurance_policy WHERE pay_status='2' AND policy_status='insured' AND getepolicy_status='0'" ;
		ss_log_task($sql);
	
		$query = $_SGLOBAL['db']->query($sql);
		$count =0;
	
		ss_log_task("将要循环执行获取电子保单");
		while($value = $_SGLOBAL['db']->fetch_array($query))
		{
	
			$policy_id = $value['policy_id'];
			$order_sn = $value['order_sn'];
			ss_log_task("将要获取电子保单的policy_id: ".$policy_id." order_sn: ".$order_sn);
	
			$readfile = false;
			$result_attr = get_policy_file($policy_id,"server_op",$readfile);
	
			$result = $result_attr['retcode'];
			$retmsg = $result_attr['retmsg'];
			////////////////////////////////////////////////////////////
			if($result==0)
			{
				ss_log_task("追加获取电子保单成功，policy_id： ".$policy_id);
			}
			else
			{
				ss_log_task("追加获取电子保单失败，policy_id： ".$policy_id);
			}
	
			$count++;
	
			//////////////////////////////////////////////////////////
	
		}
		ss_log_task("计划任务执行结束，本次个数：".$count);
	}//task_post_policy
	
	//检查哪些已经投保确没付款的保单
	public function check_policy_not_payed()
	{
		global $_SGLOBAL;
	
		ss_log_task("into ".__FUNCTION__);
		//-----------------查询未投保的保单开始-----------
		//$wheresql = 'pay_statua = 2';
		//$sql = "SELECT * FROM t_insurance_policy WHERE $wheresql LIMIT 1" ;
		//查找到已经是支付状态，并且投保状态是saved。
		$sql = "SELECT p.* FROM  bx_order_info o INNER JOIN t_insurance_policy p ON o.order_id=p.order_id WHERE o.pay_status!='2' AND p.policy_status='insured'" ;
		ss_log_task($sql);
	
		$query = $_SGLOBAL['db']->query($sql);
		$count =0;
	
		ss_log_task("将要循环执行");
		
		ss_log_policy("检查出的异常保单（未付费却投保的保单）如下：");
		while($value = $_SGLOBAL['db']->fetch_array($query))
		{
	
			$policy_no = $value['policy_no'];
			$order_sn  = $value['order_sn'];
			ss_log_policy("order_sn   ".$order_sn."   policy_no: ".$policy_no);
	
			$count++;
	
			//////////////////////////////////////////////////////////
	
		}
		ss_log_task("计划任务执行结束，本次个数：".$count);
	}//task_post_policy
	
	//SELECT p.* FROM  bx_order_info o INNER JOIN t_insurance_policy p ON o.order_id=p.order_id WHERE o.pay_status!='2' AND p.policy_status='insured';

	

	public function check_policy_taipingyang_jiuyuan()
	{
		global $_SGLOBAL;
		///////////////测试发送到环球救援公司的任务///////////////////////////////////////////////////////////////////////
		ss_log_task("将要循环检查是否发送到环球救援的成功");
		$sql = "SELECT * FROM t_insurance_policy WHERE pay_status='2' AND policy_status='insured' AND insurer_code='TBC01'" ;
		ss_log_task($sql);
	
		$query = $_SGLOBAL['db']->query($sql);
		$count =0;
	
		ss_log_task("将要循环执行到环球就");
		while($value = $_SGLOBAL['db']->fetch_array($query))
		{
			$policy_id = $value['policy_id'];
			//$order_sn  = $value['order_sn'];
			
			ss_log_task("将要发送到救援公司的policy_id: ".$policy_id);
					
			$result_attr = rescue_policy_taipingyang($policy_id);
			/////////////////////////////////////////////////////////////////////////////
	
			$result = $result_attr['retcode'];
			$retmsg = $result_attr['retmsg'];
			/////////////////////////////////////////////////////////////////////////////
			if($result==0)
			{
				ss_log_task("发送到救援公司成功，policy_id： ".$policy_id);
			}
			else
			{
				ss_log_task("发送到救援公司失败，policy_id： ".$policy_id);
			}
	
			$count++;
	
			//////////////////////////////////////////////////////////
	
		}
	
		ss_log_task("发送到环球救援的计划任务执行结束，本次个数：".$count);
	
	}//if 0
	
	//added by zhangxi, 20150609, 增加太平洋货运险的查询接口
	public function cpic_cargo_post_query_request()
	{
		global $_SGLOBAL;
	
		ss_log_task("into ".__FUNCTION__);
		//-----------------查询未投保的保单开始-----------
		//$wheresql = 'pay_statua = 2';
		//$sql = "SELECT * FROM t_insurance_policy WHERE $wheresql LIMIT 1" ;
		//查找到已经是支付状态，并且投保状态是insured,并且电子保单下载状态为0的保单。
		$sql = "SELECT * FROM t_insurance_policy WHERE pay_status='2' AND policy_status='insured' AND insurer_code='CPIC_CARGO' AND (ret_code='7' OR ret_code='15')" ;
		ss_log_task(__FUNCTION__.", ".$sql);
	
		$query = $_SGLOBAL['db']->query($sql);
		$count =0;
	
		ss_log_task(__FUNCTION__.", 将要循环执行保单或者批单查询");
		while($value = $_SGLOBAL['db']->fetch_array($query))
		{
	
			$policy_id = $value['policy_id'];
			$order_sn = $value['order_sn'];
			ss_log_task(__FUNCTION__.", 将要查询电子保单的policy_id: ".$policy_id." order_sn: ".$order_sn);
			$type = ($value['ret_code'] == 7)?"insure_accept":"insurance_endorsement";
			$result_attr = query_policyfile_cpic_cargo($value,true, false, $type);
	
			$result = $result_attr['retcode'];
			$retmsg = $result_attr['retmsg'];
			////////////////////////////////////////////////////////////
			if($result==0)
			{
				ss_log_task(__FUNCTION__.", 查询保单成功，policy_id： ".$policy_id);
			}
			else
			{
				ss_log_task(__FUNCTION__.", 查询保单失败，policy_id： ".$policy_id);
			}
	
			$count++;
	
			//////////////////////////////////////////////////////////
	
		}
		ss_log_task(__FUNCTION__.", 计划任务执行结束，本次处理保单个数：".$count);
	}
}

?>