<?php


if (!defined('IN_ECS'))
{
	die('Hacking attempt');
}

include_once(ROOT_PATH . 'baoxian/source/function_debug.php');
include_once(ROOT_PATH . 'baoxian/source/function_baoxian.php');
//include_once(ROOT_PATH.'baoxian/common.php');
//include_once(ROOT_PATH.'baoxian/source/function_common.php');
//add by wangcya, 20140812

function get_policy_subject_insurant_user($policy_subject_id)
{
	
	
	//<!--（非空）证件类型，01：身份证，02：护照，03：军人证，05：驾驶证，06：港澳回乡证或台胞证，99：其他。-->
	
	
	$wheresql = "psu.policy_subject_id='$policy_subject_id'";
	$sql = "SELECT * FROM t_insurance_policy_subject_insurant_user psu
	INNER JOIN t_user_info ui ON psu.uid=ui.uid WHERE $wheresql";
	$res = $GLOBALS['db']->selectLimit($sql, 20, 0);

	$list_subject_user = array();
	while ($row = $GLOBALS['db']->fetchRow($res))
	{
		$list_subject_user[] = $row;
	}

	return $list_subject_user;
}


function get_policy_subject_insurant_product($policy_subject_id)
{
	$wheresql = "psp.policy_subject_id='$policy_subject_id'";
	$sql = "SELECT * FROM t_insurance_policy_subject_products psp
	INNER JOIN t_insurance_product_base pb ON pb.product_id= psp.product_id
	INNER JOIN t_insurance_product_additional pa ON pa.product_id=pb.product_id
	WHERE $wheresql";
	$res = $GLOBALS['db']->selectLimit($sql, 20, 0);

	$list_subject_product = array();
	while ($row = $GLOBALS['db']->fetchRow($res))
	{
		$list_subject_product[] = $row;
	}

	return $list_subject_product;
}


/**
 *  获取用户指定范围的保单列表
 *
 * @access  public
 * @param   int         $user_id        用户ID号
 * @param   int         $num            列表最大数量
 * @param   int         $start          列表起始位置
 * @return  array       $warranty_list     保单列表
 */

function get_user_policy($user_id, $num = 10, $start = 0,$where_sql)
{
	ss_log(__FUNCTION__." where_sql:".$where_sql);
	/* 取得订单列表 */
	$arr    = array();
	
	/*
	$attr_status_policy = array("saved"=>"已保存",
			"insured"=>"已投保",
			"canceled"=>"已注销",
	);
	*/
	
	global $attr_status;
	
	$attr_status_policy = $attr_status;
	//////////////////////////////////////////////////////
	
	$order_by=" ORDER BY p.policy_id DESC ";
	
	if(!empty($_REQUEST['warranty_money']))
	{
		$order_by=" ORDER BY p.total_premium ".$_REQUEST['warranty_money'];
	}
	
	if(!empty($where_sql))
	{
	  $where_sql = $where_sql;
	}
	else
	{
	  $where_sql = '';
	}
	
	if(0)
	{
		$sql = "SELECT *  FROM t_insurance_policy p ".$where_sql.$order_by;
	}
	else
	{
//		$sql = "SELECT u.fullname as assured_fullname,p.*,o.pay_status,o.order_sn,o.order_id FROM t_insurance_policy p ".
//		       " INNER JOIN bx_order_info o ON p.order_id=o.order_id 
//		         INNER JOIN t_insurance_policy_subject ps ON ps.policy_id=p.policy_id 
//		         INNER JOIN t_insurance_policy_subject_insurant_user psiu ON psiu.policy_subject_id=ps.policy_subject_id 
//		         INNER JOIN t_user_info u ON u.uid=psiu.uid	"
//		       .$where_sql." GROUP BY policy_id ".$order_by;

		//modify yes123 2015-04-13 用内链接，阳光的保单查不出来
	/*	$sql = "SELECT bxu.user_name,bxu.real_name, u.fullname as assured_fullname,p.*,o.pay_status,o.order_sn,o.order_id FROM t_insurance_policy p ".
	       " LEFT JOIN bx_order_info o ON p.order_id=o.order_id 
	         LEFT JOIN t_insurance_policy_subject ps ON ps.policy_id=p.policy_id 
	         LEFT JOIN bx_users bxu ON bxu.user_id=p.agent_uid 
	         LEFT JOIN t_insurance_policy_subject_insurant_user psiu ON psiu.policy_subject_id=ps.policy_subject_id 
	         LEFT JOIN t_user_info u ON u.uid=psiu.uid	".$where_sql." GROUP BY p.policy_id ".$order_by;*/
		$sql = "SELECT bxu.user_name,bxu.real_name, p.*,o.pay_status,o.order_sn,o.order_id FROM t_insurance_policy p ".
	       " LEFT JOIN bx_order_info o ON p.order_id=o.order_id 
	         LEFT JOIN bx_users bxu ON bxu.user_id=p.agent_uid  $where_sql $order_by";
	     
	}
	
	ss_log("get_user_policy:".$sql);
	$res = $GLOBALS['db']->SelectLimit($sql, $num, $start);

	if ($res !== false)
	{
		include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
		$arr = array();
		while ($row = mysql_fetch_assoc($res))
		{
			$row['policy_status_str'] = $attr_status_policy[$row['policy_status']];
			
			$row['policyDate'] = date('Y-m-d H:i',$row['dateline']);//add by dingchaoyang 2014-11-28 投保时间
			$row['dateline'] = date('Y-m-d H:i',$row['dateline']);
			$row['total_premium'] = price_format($row['total_premium'], false);//add by yes123 2014-11-25  格式化金额
			$row['formated_start_date'] = date('Y-m-d',strtotime($row['start_date']));
			$row['formated_end_date'] = date('Y-m-d',strtotime($row['end_date']));
			
			//add yes123 2015-05-20 获取被保险人
			$sql = "SELECT policy_subject_id FROM t_insurance_policy_subject WHERE policy_id = $row[policy_id]";
			$policy_subject_id_list = $GLOBALS['db']->getAll($sql);
			if($policy_subject_id_list)
			{
				$policy_subject_id_str = CommonUtils::arrToStr($policy_subject_id_list,"policy_subject_id");
				$sql ="SELECT uid FROM t_insurance_policy_subject_insurant_user WHERE policy_subject_id IN ($policy_subject_id_str) " ;
				$uid_list = $GLOBALS['db']->getAll($sql);
				if($uid_list)
				{
					$uid_str = CommonUtils::arrToStr($uid_list,"uid");
					$sql = "SELECT * FROM t_user_info WHERE uid IN($uid_str)";
					$assured_user_list = $GLOBALS['db']->getAll($sql);
					$row['assured_user_list'] = $assured_user_list;
				}
				
			}
    		
    		
			$arr[] = $row;
		}

		return $arr;
	}
	else
	{

		return false;
	}
}

/* del by wangcya , 20140914, 用统一的函数
function get_policy_view_info($policy_id)
{

	$attr_status = array("saved"=>"已保存",
			"insured"=>"已投保",
			"canceled"=>"已注销",
	);
	 
	$attr_business_type = array("1"=>"个人",
			"2"=>"团体",
	);
	
	//1:本人 2:配偶 3 :父子 4:父女 5:受益人 6:受益人 7:投保人 A:母子 B:母女 C:兄弟 D:姐弟 G:祖孙 H:雇佣 I:子女 9:其他 8:转换不详
	$attr_relationship_with_insured = array("1"=>"本人",
			"2"=>"配偶",
			"3"=>"父子",
			"4"=>"父女",
			"5"=>"受益人",
			"6"=>"受益人",
			"7"=>"投保人",
			"8"=>"关系不详",
			"9"=>"其他",
			"A"=>"母子",
			"B"=>"母女",
			"C"=>"兄弟",
			"D"=>"姐弟",
			"G"=>"祖孙",
			"H"=>"雇佣",
			"I"=>"子女",
	);
	

	$sql="select * from t_insurance_policy where policy_id=$policy_id  ";
	
	$policy = $GLOBALS['db']->getRow($sql);
	
	$policy['policy_status'] = $attr_status[$policy['policy_status']];
	$policy['business_type'] = $attr_business_type[$policy['business_type']];
	$policy['relationship_with_insured'] = $attr_relationship_with_insured[$policy['relationship_with_insured']];
	$policy['dateline'] = date('Y-m-d H:i',$policy['dateline']);
	
	
	////////////////////////////////////////////////////////////////////
	
	///////start add by wangcya , 20140807 ///////////////////////////////////////////
	$policy_id = $policy['policy_id'];
	if($policy_id)
	{
	
		/////////////////////////////////////////////////////////////////
		$wheresql = "policy_id='$policy_id'";
		$sql = "SELECT * FROM t_insurance_policy_subject WHERE $wheresql";
		$res = $GLOBALS['db']->selectLimit($sql, 10, 0);
	
		$list_subject = array();
		while ($row = $GLOBALS['db']->fetchRow($res))
		{
			$policy_subject_id = $row['policy_subject_id'];
			$row['list_insurant_user'] = get_policy_subject_insurant_user($policy_subject_id);
			$row['list_insurant_product'] = get_policy_subject_insurant_product($policy_subject_id);
	
			$list_subject[] = $row;
		}
			
		
	}
	
	$ret_attr = array('policy'=>$policy,
			     'list_subject'=>$list_subject
			    );
	
	return $ret_attr;
		
}
*/
//end by wangcya, 20140812


function get_pingan_ertong_proposal_list()
{
	global $attr_status;
	/* 取得订单列表 */
	$arr = array ();
	$filter = array ();
	$result = get_filter();
	if ($result === false)
	{
		$filter['sort_by'] = empty ($_REQUEST['sort_by']) ? 'dateline' : trim($_REQUEST['sort_by']);
		$filter['sort_order'] = empty ($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
		$filter['policy_no'] = trim($_REQUEST['policy_no']);
		
		$filter['platform_id'] = trim($_REQUEST['platform_id']);//add by wangcya, 20150119
		
		$filter['attribute_name'] = trim($_REQUEST['attribute_name']);
		$filter['applicant_username'] = trim($_REQUEST['applicant_username']);
		//$filter['agent_username'] = trim($_REQUEST['agent_username']);
		//$filter['agent_realname'] = trim($_REQUEST['agent_realname']);//add by wangcya , 20141106
		$filter['assured_fullname'] = empty($_REQUEST['assured_fullname']) ? '' : trim($_REQUEST['assured_fullname']);
		$filter['certificates_code'] = empty($_REQUEST['certificates_code']) ? '' : trim($_REQUEST['certificates_code']);

		$filter['policy_status'] = trim($_REQUEST['policy_status']);
		//$filter['insurer_code'] = trim($_REQUEST['insurer_code']);//add by wangcya , 20141106

		$filter['start_time'] = empty ($_REQUEST['start_time']) ? '' : (strpos($_REQUEST['start_time'], '-') > 0 ? local_strtotime($_REQUEST['start_time']) : $_REQUEST['start_time']);
		$filter['end_time'] = empty ($_REQUEST['end_time']) ? '' : (strpos($_REQUEST['end_time'], '-') > 0 ? local_strtotime($_REQUEST['end_time']) : $_REQUEST['end_time']);

		////////////////////////////////////////////////////////////////////////////
		$insurer_code = "PAC";
		$attribute_name_A = "平安少儿保险卡A卡";
		$attribute_name_B = "平安少儿保险卡B卡";
		$attribute_name_C = "平安少儿保险卡C卡";

		//od.pay_status = '2'
		/*
		$where = "WHERE p.insurer_code='$insurer_code'
		AND ( p.attribute_name='$attribute_name_A' OR p.attribute_name='$attribute_name_B' OR p.attribute_name='$attribute_name_C' )";//已经支付的订单
		*/
		$where = "WHERE 1 ";//
		
		
		if ($filter['start_time']) {
			$where .= " AND p.dateline >= '$filter[start_time]'";
		}
		if ($filter['end_time']) {
			$where .= " AND p.dateline <= '$filter[end_time]'";
		}

		//start add by wangcya, 20150119
		if ($filter['platform_id']) {
			$where .= " AND p.platform_id = '$filter[platform_id]'";
		}
		//end add by wangcya, 20150119
		
		
		if ($filter['policy_no']) {
			$where .= " AND p.policy_no = '$filter[policy_no]'";
		}
		if ($filter['attribute_name']) {
			$where .= " AND p.attribute_name LIKE '%" . $filter[attribute_name] . "%'";
		}

		if ($filter['applicant_username']) {
			$where .= " AND p.applicant_username LIKE '%" . $filter[applicant_username] . "%'";
		}

		/*
		 if ($filter['agent_username']) {
		$where .= " AND p.agent_username LIKE '%" . $filter['agent_username'] . "%'";
		}

		//start add by wangcya, 20141106
		if ($filter['agent_realname'])
		{
		$sql = "SELECT user_id FROM bx_users WHERE real_name='$filter[agent_realname]'";
		//ss_log($sql);
		$row = $GLOBALS['db']->getAll($sql);

		$attr_uid = array();
		foreach ($row AS $key=>$value)
		{
		$attr_uid[] = intval($value['user_id']);
		}

		$where .= " AND p.agent_uid IN (".implode(',', $attr_uid).")";
		}
		*/
		//被保险人
		if ($filter['assured_fullname'] || $filter['certificates_code'] )
		{
			$temp_where=" ";
			if($filter['assured_fullname']){
				$temp_where.=" AND fullname='".$filter['assured_fullname']."'";
			}

			if($filter['certificates_code']){
				$temp_where.=" AND certificates_code='".$filter['certificates_code']."'";
			}


			$uid_by_uname_sql="SELECT uid FROM t_user_info WHERE 1=1 ".$temp_where;
			$uids= $GLOBALS['db']->getAll($uid_by_uname_sql);
			$ids="";
			foreach ($uids AS $key => $value){
				$id = $value['uid'];
				$ids.=$id.",";
			}
			$uids = rtrim($ids, ",");

			if($uids){
				$sql="SELECT policy_id FROM t_insurance_policy_subject WHERE policy_subject_id in(" .
						"SELECT policy_subject_id FROM t_insurance_policy_subject_insurant_user WHERE uid in(" .
						$uids."))";
				$policy_ids= $GLOBALS['db']->getAll($sql);

				$ids="";
				foreach ($policy_ids AS $key => $value){
					$id = $value['policy_id'];
					$ids.=$id.",";
				}
				$policy_ids = rtrim($ids, ",");
				$where .= " AND p.policy_id in(".$policy_ids.")";
			}else{
				$where .= " AND p.policy_id =0 ";
			}

		}

		/*
		 if ($filter['insurer_code']) {
		$where .= " AND p.insurer_code =  '$filter[insurer_code]'";
		}
		*/

		//ss_log($where);
		//end add by wangcya, 20141106

		//2014-09-28  yes123  加保单查询条件
		if (!empty($_REQUEST['policy_status']))
		{
			$where .= " AND p.policy_status ='" . $_REQUEST['policy_status'] . "'";
		}
		else
		{
			//$where .= " AND (p.policy_status='insured' OR p.policy_status='canceled')";
		}

		/* 分页大小 */
		$filter['page'] = empty ($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

		if (isset ($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) {
			$filter['page_size'] = intval($_REQUEST['page_size']);
		}
		elseif (isset ($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0) {
			$filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
		} else {
			$filter['page_size'] = 15;
		}

		/* 记录总数 */


		if ($filter['user_name']) {
			$sql = "SELECT COUNT(*) FROM t_insurance_policy AS p ," .
					$GLOBALS['ecs']->table('users') . " AS u " . $where;
		} else {
			$sql = "SELECT COUNT(*) FROM t_insurance_policy AS p INNER JOIN bx_order_info AS od ON p.order_id= od.order_id " . $where;
		}

		$filter['record_count'] = $GLOBALS['db']->getOne($sql);
		$filter['page_count'] = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;


		///////////////////////////////////////////////////////////////////////////////////
		//2014-09-28  yes123  已中文显示保单状态
		$sql = "SELECT p.* 
		FROM t_insurance_policy AS p INNER JOIN bx_order_info AS od ON p.order_id= od.order_id " . $where .
		" ORDER BY $filter[sort_by] $filter[sort_order] " .
		" LIMIT " . ($filter['page'] - 1) * $filter['page_size'] . ",".$filter['page_size'];

		ss_log($sql);

		$row = $GLOBALS['db']->getAll($sql);

	}
	else
	{
		$sql    = $result['sql'];
		$filter = $result['filter'];
	}
	/* 格式话数据 */

	foreach ($row AS $key => $value)
	{
		$row[$key]['policy_status'] = $attr_status[$value['policy_status']];
		$row[$key]['dateline'] = date('Y-m-d H:i', $value['dateline']);
	}

	////////start add by wangcya , 20141107/////////////////////
	$sql = "SELECT SUM(p.total_premium) FROM t_insurance_policy AS p
	INNER JOIN bx_order_info AS od ON p.order_id=od.order_id " . $where;

	//ss_log($sql);
	$s_totalpremium =  $GLOBALS['db']->getOne($sql);

	ss_log("totalpremium: ".$s_totalpremium);
	////////end add by wangcya , 20141107/////////////////////



	$arr = array (
			'policys' => $row,
			's_totalpremium'=>$s_totalpremium,//add by wangcya , 20141107
			'filter' => $filter,
			'page_count' => $filter['page_count'],
			'record_count' => $filter['record_count']
	);

	return $arr;

}

//start badd by wangcya, 20150130
function get_policy_list_by_order_id($order_id)
{
	global $attr_status;
	ss_log("into ".__FUNCTION__);
	$sql="SELECT * FROM t_insurance_policy WHERE order_id ='$order_id'";
	ss_log($sql);
	$policy_list = $GLOBALS['db']->getAll($sql);	
	
	foreach ($policy_list AS $key => $value)
	{
		$policy_list[$key]['dateline'] = date('Y-m-d H:i', $value['dateline']);
		if($value['policy_status'])
		{
			$policy_list[$key]['policy_status'] = $policy_list[$key]['policy_status_str']  = $attr_status[$value['policy_status']];
		}
		
	}
	return $policy_list;
}
//end badd by wangcya, 20150130

function get_alluser_policy_list($policy_status,$policy_list_type='')
{
	global $attr_status;
	/* 取得订单列表 */
	$arr = array ();
	$filter = array ();
	$result = get_filter();
	if ($result === false)
	{
		$filter['platform_id'] = trim($_REQUEST['platform_id']);//start add by wangcya,20150119
		
		$filter['order_id'] = trim($_REQUEST['order_id']);//start add by wangcya, for bug[193],能够支持多人批量投保

		$filter['sort_by'] = empty ($_REQUEST['sort_by']) ? 'dateline' : trim($_REQUEST['sort_by']);
		$filter['sort_order'] = empty ($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
		$filter['policy_no'] = trim($_REQUEST['policy_no']);
		$filter['attribute_name'] = trim($_REQUEST['attribute_name']);
		$filter['applicant_username'] = trim($_REQUEST['applicant_username']);
		$filter['agent_username'] = trim($_REQUEST['agent_username']);
		$filter['agent_realname'] = trim($_REQUEST['agent_realname']);//add by wangcya , 20141106
		$filter['policy_status'] = trim($_REQUEST['policy_status']);
		$filter['insurer_code'] = trim($_REQUEST['insurer_code']);//add by wangcya , 20141106
		
		$filter['organ_user_id'] = empty ($_REQUEST['organ_user_id']) ? 0: intval($_REQUEST['organ_user_id']);
		
		//modify yes123 2015-01-25时间条件修正
		$filter['start_time'] =  isset($_REQUEST['start_time'])?$_REQUEST['start_time']:"";
		$filter['end_time'] =  isset($_REQUEST['end_time'])?$_REQUEST['end_time']:"";
		
		//add yes123 2015-03-13 按照推荐人查询保单
		$filter['parent_real_name'] =  isset($_REQUEST['parent_real_name'])?$_REQUEST['parent_real_name']:"";
		
		$filter['assured_fullname'] = empty($_REQUEST['assured_fullname']) ? '' : trim($_REQUEST['assured_fullname']);
		$filter['assured_certificates_code'] = empty($_REQUEST['assured_certificates_code']) ? '' : trim($_REQUEST['assured_certificates_code']);
		$filter['mobile_phone'] = empty($_REQUEST['mobile_phone']) ? '' : trim($_REQUEST['mobile_phone']);
		////////////////////////////////////////////////////////////////////////////
		
		if($policy_list_type=='cast_policy_list')
		{
			$where = "WHERE p.pay_status=2 ";
		}	
		else
		{
			$where = "WHERE 1 ";
		}
		ss_log(__FUNCTION__." where:".$where);

		//start add by wangcya, for bug[193],能够支持多人批量投保
		if ($filter['order_id'])
		{
			$where .= " AND p.order_id = '$filter[order_id]'";
		}
		//end add by wangcya, for bug[193],能够支持多人批量投保
		
		
		//modify yes123 2015-01-25时间条件修正
		if ($filter['start_time']) {
			$where .= " AND p.dateline >= '".strtotime($filter['start_time'])."'";
		}
		if ($filter['end_time']) {
			$where .= " AND p.dateline <= '".strtotime($filter['end_time'])."'";
		}

		if ($filter['policy_no']) {
			$where .= " AND p.policy_no = '$filter[policy_no]'";
		}
		if ($filter['attribute_name']) {
			$where .= " AND p.attribute_name LIKE '%" . $filter['attribute_name'] . "%'";
		}

		if ($filter['applicant_username']) {
			$where .= " AND p.applicant_username LIKE '%" . $filter['applicant_username'] . "%'";
		}
		if ($filter['agent_username']) {
			$where .= " AND p.agent_username LIKE '%" . $filter['agent_username'] . "%'";
		}
		
		if ($filter['mobile_phone']) {
			$where .= " AND u.mobile_phone='$filter[mobile_phone]'";
		}

		//start add by wangcya, 20141106
		if ($filter['agent_realname'])
		{
			$sql = "SELECT user_id FROM bx_users WHERE real_name='$filter[agent_realname]'";
			//ss_log($sql);
			$row = $GLOBALS['db']->getAll($sql);
				
			$attr_uid = array();
			foreach ($row AS $key=>$value)
			{
				$attr_uid[] = intval($value['user_id']);
			}
			//modify yes123 2015-01-12 如果查询不到给0
			if($attr_uid){
				$where .= " AND p.agent_uid IN (".implode(',', $attr_uid).")";
			}
			else
			{
				$where .= " AND p.agent_uid IN (0)";
			}
			
		}
		
		
		//start add by wangcya, 20150119
		if ($filter['platform_id'])
		{
			$where.=" AND p.platform_id LIKE '%".$filter['platform_id']."%'";
		}
		//end add by wangcya, 20150119
		

		if ($filter['insurer_code']) {
			$where .= " AND p.insurer_code =  '$filter[insurer_code]'";
		}
		
		if ($filter['organ_user_id']) {
			$where .= " AND p.organ_user_id =  '$filter[organ_user_id]'";
		}
		

		
		//1.获取角色ID
		$role_sql = "SELECT role_id FROM " . $GLOBALS['ecs']->table('admin_user') . " WHERE user_id=".$_SESSION['admin_id'];
		ss_log("1.获取角色ID：".$role_sql);
		$role_id = $GLOBALS['db']->getOne($role_sql);
		
		//add by yes123 2015-04-28 获取当前用户所属角色->获取保险公司->保险公司编码
		if($role_id)
		{
			//2.获取角色
			$role_name_sql = "SELECT * FROM " . $GLOBALS['ecs']->table('role') . " WHERE role_id=".$role_id;
			ss_log("2.获取角色：".$role_name_sql);
			$role = $GLOBALS['db']->getRow($role_name_sql);
			if($role['action_list'])
			{
				$action_list = explode(",",$role['action_list']);
		
				if(in_array('proposal_list',$action_list))
				{
					//add by yes123 2014-11-12 获取当前用户所属角色->获取保险公司->保险公司编码
					$sql = "SELECT insurer_code FROM bx_role_factor WHERE role_name='$role[role_name]'";
					ss_log("2.获取insurer_code：".$sql);
					$insurer_code = $GLOBALS['db']->getOne($sql);
					if($insurer_code)//find
					{
						$where .= " AND p.insurer_code='$insurer_code' ";
					}
					
					
				}
				
			}
			
		}


		
	
		//ss_log($where);
		//end add by wangcya, 20141106
		//start add by wangcya, 20141119 , for 被保险人
		if ($filter['assured_fullname'] || $filter['assured_certificates_code'] )
		{
			$temp_where=" ";
			if($filter['assured_fullname'])
			{
				$temp_where.=" AND fullname='".$filter['assured_fullname']."'";
			}
				
			if($filter['assured_certificates_code'])
			{
				$temp_where.=" AND certificates_code='".$filter['assured_certificates_code']."'";
			}
				
				
			$uid_by_uname_sql="SELECT uid FROM t_user_info WHERE 1=1 ".$temp_where;
			//ss_log($uid_by_uname_sql);
			$uids= $GLOBALS['db']->getAll($uid_by_uname_sql);
			$ids="";

			foreach ($uids AS $key => $value)
			{
				$id = $value['uid'];
				$ids.=$id.",";
			}

			$uids = rtrim($ids, ",");
				
			if($uids)
			{
				$sql="SELECT policy_id FROM t_insurance_policy_subject WHERE policy_subject_id in(" .
						"SELECT policy_subject_id FROM t_insurance_policy_subject_insurant_user WHERE uid in(" .
						$uids."))";
				$policy_ids= $GLOBALS['db']->getAll($sql);

				$ids="";
				foreach ($policy_ids AS $key => $value)
				{
					$id = $value['policy_id'];
					$ids.=$id.",";
				}
				$policy_ids = rtrim($ids, ",");

				$where .= " AND p.policy_id in(".$policy_ids.")";
			}
			else
			{
				$where .= " AND p.policy_id =0 ";
			}

		}
		
		//modify yes123 2015-03-13 通过推荐人姓名查询
		if($filter['parent_real_name'] )
		{
			$sql="SELECT user_id FROM bx_users WHERE parent_id IN (SELECT user_id FROM bx_users WHERE real_name LIKE '%$filter[parent_real_name]%')";
			$user_ids= $GLOBALS['db']->getAll($sql);
			include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
			$user_ids = CommonUtils :: arrToStr($user_ids, "user_id");
			if($user_ids)
			{
				$where .= " AND u.user_id in($user_ids) ";
			}
			else
			{
				$where .= " AND u.user_id=0 ";
			}
			
		}
		
		
		//end add by wangcya, 20141119 , for 被保险人
		//2014-09-28  yes123  加保单查询条件
		if ($filter['policy_status'])
		{
			$where .= " AND p.policy_status ='" . $_REQUEST['policy_status'] . "'";
		}
		//start add by wangcya, for bug[193],能够支持多人批量投保
		elseif (!empty($_REQUEST['order_id']))
		{
			
		}
		//end add by wangcya, for bug[193],能够支持多人批量投保
		elseif ($policy_status=="all")//add by wangcya, 20150206
		{
			//这个时候则全部要过滤。
		}
		else
		{//默认
			//$where .= " AND (p.policy_status='insured' OR p.policy_status='canceled')";
			//$where .= " AND (p.policy_status='insured')";
			$where .= " AND (p.policy_status='$policy_status')";
		}

		/* 分页大小 */
		$filter['page'] = empty ($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

		if (isset ($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) {
			$filter['page_size'] = intval($_REQUEST['page_size']);
		}
		elseif (isset ($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0) {
			$filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
		} else {
			$filter['page_size'] = 15;
		}

		/* 记录总数 */
		//modify yes123 2015-03-13 修正查询记录数
		$sql = "SELECT COUNT(*) FROM t_insurance_policy AS p LEFT JOIN bx_users AS u ON p.agent_uid=u.user_id ". $where;
		ss_log("保单总数：".$sql);
		$filter['record_count'] = $GLOBALS['db']->getOne($sql);
		$filter['page_count'] = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;


		///////////////////////////////////////////////////////////////////////////////////
		//2014-09-28  yes123  已中文显示保单状态
		$sql = "SELECT pu.fullname AS assured_fullname,u.*,p.*
		FROM t_insurance_policy AS p
		LEFT JOIN bx_users AS u ON p.agent_uid=u.user_id
		LEFT JOIN t_insurance_policy_subject ps ON ps.policy_id=p.policy_id
		LEFT JOIN t_insurance_policy_subject_insurant_user psiu ON psiu.policy_subject_id=ps.policy_subject_id
		LEFT JOIN t_user_info pu ON pu.uid=psiu.uid
		" . $where .
		" GROUP BY p.policy_id ORDER BY $filter[sort_by] $filter[sort_order] " .
		" LIMIT " . ($filter['page'] - 1) * $filter['page_size'] . ",".$filter['page_size'];

		ss_log("get_alluser_policy_list:".$sql);

		$policy_list = $GLOBALS['db']->getAll($sql);

	}
	else
	{
		$sql    = $result['sql'];
		$filter = $result['filter'];
	}
	/* 格式话数据 */

	foreach ($policy_list AS $key => $value)
	{
		$policy_list[$key]['dateline'] = date('Y-m-d H:i', $value['dateline']);
		//add yes123 2015-03-13
		//查询推荐人姓名
		$sql = "SELECT real_name FROM bx_users WHERE user_id=$value[parent_id]";
		$parent_real_name = $GLOBALS['db']->getOne($sql);
		$policy_list[$key]['parent_real_name'] = $parent_real_name;
		if($value['policy_status'])
		{
			$policy_list[$key]['policy_status'] = $attr_status[$value['policy_status']];
		}
		
		if(!$value['real_name'])
		{
			$policy_list[$key]['real_name']=$value['user_name'];
		}
		if(!$value['mobile_phone'])
		{
			$policy_list[$key]['mobile_phone']=$value['user_name'];
		}
		
	}


	////////start add by wangcya , 20141107/////////////////////
	$sql = "SELECT SUM(p.total_premium) FROM t_insurance_policy AS p
	INNER JOIN bx_users AS u ON p.agent_uid=u.user_id " . $where;

	//ss_log($sql);
	$s_totalpremium =  $GLOBALS['db']->getOne($sql);

	ss_log("totalpremium: ".$s_totalpremium);
	////////end add by wangcya , 20141107/////////////////////
	
	$arr = array (
			'policys' => $policy_list,
			's_totalpremium'=>$s_totalpremium,//add by wangcya , 20141107
			'filter' => $filter,
			'attr_status'=>$attr_status,
			'page_count' => $filter['page_count'],
			'record_count' => $filter['record_count']
	);

	return $arr;
}

//add yes123 2015-01-08 导出保单功能
/**
 *  通过订单sn取得订单ID
 *  @param  array  $title   标题数组
 *  @param  string    $title_bgcolor    标题颜色
 *  @param  string    $title_fontcolor    标题字体颜色
 *  @param  string    $type    表示前台还是后台，默认是前台
 */
function export_policy_list($title,$title_bgcolor,$title_fontcolor,$type=''){
	global $attr_status;
	$policy_ids = $_REQUEST['policy_ids'];
	$ids_array=explode(',',$policy_ids);
	$id_count = count($ids_array);
	
	ss_log("导出保单个数：".$id_count);
	if(($id_count-300)>0)
	{
		/* 提示信息 */
	    $links[0]['text']    = "返回上一页";
	    $links[0]['href']    = 'javascript:history.back()';
	    sys_msg("导出保单个数不能超过300个",0, $links);
	    exit;
	}
	
	include_once(ROOT_PATH . 'oop/lib/Excel/PHPExcel.php');
	
	$sql = "SELECT u.real_name AS agent_real_name,u.user_name,p.*
	 FROM t_insurance_policy AS p INNER JOIN bx_users AS u ON p.agent_uid=u.user_id 
	 WHERE p.policy_id IN ($policy_ids)";

	$policy_list = $GLOBALS['db']->getAll($sql);
	$policy_list = array_reverse($policy_list);
	
	$objExcel = new PHPExcel();
	
	$objWriter = new PHPExcel_Writer_Excel5($objExcel);     // 用于其他版本格式   
	
		//设置当前的sheet索引，用于后续的内容操作。   
	$objExcel->setActiveSheetIndex(0);   
	$objActSheet = $objExcel->getActiveSheet();   
	
	$objActSheet->setTitle('sheet1');   

	//modify yes123 2015-01-19 如果是后台导出保单，需要导出支付方式和使用的余额
	if($type=='admin'){
		
		$abc = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R');
		/*设置特别表格的宽度*/  
	   	$objExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(20);
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(25);  
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(20);  
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(50);  
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(30);  
	    
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(10);   //保费
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(15);   //支付方式
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(10);   //支付金额
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(10);   //使用余额
	    
	    
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('J')->setWidth(15);  //下单日期
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('K')->setWidth(15);  //起保日期
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('L')->setWidth(15);  //终止日期
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('M')->setWidth(15);  //投保人
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('N')->setWidth(15);  //被保险人
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('O')->setWidth(10);  //投保状态
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('P')->setWidth(10);  //保单ID
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('Q')->setWidth(10);  //用户id
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('R')->setWidth(20);  //用户名
		
	}else{ //前台导出
		$abc = array('A','B','C','D','E','F','G','H','I','J');
		/*设置特别表格的宽度*/  
	   	$objExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(25);//表示设置A这一列的宽度,以下一样  
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(20);  //代理人名称
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(50);  //保险公司
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(40);  //险种
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(15);  //保费
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(20);  //下单日期
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(20);  //起保起期
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(20);  //终止日期
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(15); //投保人
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('J')->setWidth(15); //被保险人
		
	}

    //设置内容居中
	
	$policy_array= array();
	$policy_array[0]=$title;
	if(is_array($policy_list))    //add
	{ 
		foreach ($policy_list as $key => $value ) 
		{
			    include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
				//取被保险人
			    $sql = "SELECT policy_subject_id FROM t_insurance_policy_subject WHERE policy_id=$value[policy_id]";	
        		$policy_subject_id_list = $GLOBALS['db']->getAll($sql);
			    $policy_subject_ids = commonUtils::arrToStr($policy_subject_id_list,"policy_subject_id");
				if($policy_subject_ids){
					$sql = " SELECT uid  FROM t_insurance_policy_subject_insurant_user WHERE policy_subject_id IN ($policy_subject_ids)" ;
					$uid_list = $GLOBALS['db']->getAll($sql);
					if(count($uid_list)==1)
					{
						$sql = "SELECT fullname FROM t_user_info WHERE uid=".$uid_list[0]['uid'];	
						$value['assured_fullname'] = $GLOBALS['db']->getOne($sql);
					}
		        }
		        
				
				$policy_list[$key]['policy_status']  = $value['policy_status'] = $attr_status[$value['policy_status']];
				
				$policy=array();
				//modify yes123 2015-01-19 如果是后台导出保单，需要导出支付方式和使用的余额
				if($type=='admin'){
					$order_id = $value['order_id'];
					//查询订单下个保单个数
					$sql = " SELECT count(policy_id) FROM t_insurance_policy WHERE order_id = '$order_id'" ;
					$num = $GLOBALS['db']->getOne($sql);
					//查询支付方式和使用的余额
					$sql = " SELECT order_sn,pay_name,surplus FROM bx_order_info WHERE order_id = '$order_id'" ;
					$order = $GLOBALS['db']->getRow($sql);
					$policy[0]= $order['order_sn']." ";
					$policy[1]= $value['policy_no']." ";
					$policy[2]=$value['agent_real_name'];
					$policy[3]=$value['insurer_name'];
					$policy[4]=$value['attribute_name'];
					$policy[5]=$value['total_premium'];
					$policy[6]=$order['pay_name']; //支付方式
					
					if($order['pay_name']!='余额支付'){
						if($order['surplus']>0){
							$surplus = $order['surplus']/$num;
							$policy[7] = ($value['total_premium']-$surplus); //支付金额
						}else{
							$policy[7] = $value['total_premium'];
						}
						
					}else{
						$policy[7]=0;
					}
				
					$policy[8]=$order['surplus']/$num; //使用余额
					
					if($value['dateline']){
						$policy[9]=date("Y-m-d",$value['dateline']);//下单日期
					}
					
					
					$policy[10]= date("Y-m-d",strtotime($value['start_date']));
					$policy[11]=date("Y-m-d",strtotime($value['end_date']));
					$policy[12]=$value['applicant_username'];
					$policy[13]=$value['assured_fullname'];
					$policy[14]=$value['policy_status'];
					$policy[15]=$value['policy_id'];//保单ID
					$policy[16]=$value['agent_uid'];//用户ID
					$policy[17]=$value['user_name'];//用户名
					
				}else{
					$policy[0]= $value['policy_no']." ";
					$policy[1]=$value['agent_real_name'];
					$policy[2]=$value['insurer_name'];
					$policy[3]=$value['attribute_name'];
					$policy[4]=$value['total_premium'];
					if($value['dateline']){
						$policy[5]=date("Y-m-d",$value['dateline']);//下单日期
					}
					
					
					$policy[6]= date("Y-m-d",strtotime($value['start_date']));
					$policy[7]=date("Y-m-d",strtotime($value['end_date']));
					$policy[8]=$value['applicant_username'];
					$policy[9]=$value['assured_fullname'];
					
				}
				$policy_array[]=$policy;
		}
	}
	
	foreach($policy_array as $k => $v)
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
				    $objFontA1->getColor()->setARGB($title_fontcolor);  
				    
				    $objStyleA1->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
    				$objStyleA1->getFill()->getStartColor()->setARGB($title_bgcolor); 
				}
			}
	}
	$outputFileName = date('Y-m-d H:i',time()).".xls";
	header("Content-Type:application/octet-stream;charset=utf-8");
	header('Content-Disposition: attachment; filename=' . $outputFileName); 
	$objWriter->save('php://output');
	
}

//add yes123 2015-05-06 导出投保单功能
/**
 *  通过订单sn取得订单ID
 *  @param  array  $title   标题数组
 *  @param  string    $title_bgcolor    标题颜色
 *  @param  string    $title_fontcolor    标题字体颜色
 *  @param  string    $type    表示前台还是后台，默认是前台
 */
function export_chinalife_cast_policy_list($title,$title_bgcolor,$title_fontcolor){
	include_once(ROOT_PATH . 'oop/lib/Excel/PHPExcel.php');
	include_once (ROOT_PATH. 'baoxian/source/function_baoxian_chinalife.php');
	global $attr_status,$attr_relationship_with_insured_chinalife;
	$policy_ids = $_REQUEST['policy_ids'];
	
	$sql = "SELECT p.*,ui.*
	 FROM t_insurance_policy AS p INNER JOIN t_user_info AS ui ON ui.uid=p.applicant_uid 
	 WHERE p.policy_id IN ($policy_ids)";

	$policy_list = $GLOBALS['db']->getAll($sql);
	$policy_list = array_reverse($policy_list);
	
	$objExcel = new PHPExcel();
	
	$objWriter = new PHPExcel_Writer_Excel5($objExcel);     // 用于其他版本格式   
	
		//设置当前的sheet索引，用于后续的内容操作。   
	$objExcel->setActiveSheetIndex(0);   
	$objActSheet = $objExcel->getActiveSheet();   
	
	$objActSheet->setTitle('sheet1');   

	//modify yes123 2015-01-19 如果是后台导出保单，需要导出支付方式和使用的余额
		
	$abc = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O',
				 'P','Q','R','S','T','U','V','W','X','Y','Z',
				 'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO',
				 'AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
				 'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM'
	);
		/*设置特别表格的宽度*/  
   	$objExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(10);//处理标记
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(15); //被保险人编号 从1 开始
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(15); //投保人姓名
    
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(15);  //与投保人关系 
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(15);   //被保险人姓名
    
    
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('K')->setWidth(10);  //被保险人性别
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('L')->setWidth(15);  //被保险人出生日期
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('M')->setWidth(10);  //被保险人证件类别
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('N')->setWidth(30);  //被保险人证件号码
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('AT')->setWidth(10);  //投保单ID
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('AU')->setWidth(15);  //投保人手机号
    $objExcel->setActiveSheetIndex(0)->getColumnDimension('BI')->setWidth(15);  //保险起期 20150505
		

    //设置内容居中
	
	$policy_array= array();
	$policy_array[0]=$title;
	if(is_array($policy_list))    //add
	{ 
		$i=1;
		foreach ($policy_list as $key => $value ) 
		{
			
				$policy=array();
				//取被保险人
			    $sql = "SELECT policy_subject_id FROM t_insurance_policy_subject WHERE policy_id='$value[policy_id]'";	
        		$policy_subject_id = $GLOBALS['db']->getOne($sql);
				if($policy_subject_id){
					$sql = " SELECT uid  FROM t_insurance_policy_subject_insurant_user WHERE policy_subject_id='$policy_subject_id'" ;
					
					$uid = $GLOBALS['db']->getOne($sql);
					if($uid)
					{
						$sql = "SELECT * FROM t_user_info WHERE uid='$uid'";	
						$recognizee = $GLOBALS['db']->getRow($sql); //被保险人
						
						
						$policy[6]= $recognizee['fullname']; //被保险人姓名
						$policy[10]= $recognizee['gender']; //被保险人性别
				
						if($recognizee['birthday'])
						{	//被保险人生日
							$policy[11]= date("Ymd",strtotime($recognizee['birthday'])); 
						}
				
						$policy[12]= "I"; //被保险人证件类别
						$policy[13]= $recognizee['certificates_code']." "; //被保险人证件号码
						
					}
		        }
		        
				//modify yes123 2015-01-19 如果是后台导出保单，需要导出支付方式和使用的余额
				$policy[0]= "K";
				$policy[1]=$i;//被保险人编号，从1开始
				$policy[2]=$value['applicant_username']; //投保人姓名
				
				if($value['relationship_with_insured']) //与投保人关系
				{
					$policy[5]=$attr_relationship_with_insured_chinalife[$value['relationship_with_insured']];//与投保人关系
				}
				
				//付款帐号也就是保单ID
				$policy[45]= $value['policy_id'];
				$policy[46]= $value['mobiletelephone']; //投保人手机号码
				$policy[60]= date("Ymd",strtotime($value['start_date']));//保险起期
				
				$policy_array[]=$policy;
				
				$i++;
		}
	}
	
	foreach($policy_array as $k => $v)
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
				    //$objFontA1 = $objStyleA1->getFont();  
				    //$objFontA1->setName('黑体');  
				    //$objFontA1->setSize(12);  
				    //$objFontA1->getColor()->setARGB($title_fontcolor);  
				    
				    //$objStyleA1->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);  
    				//$objStyleA1->getFill()->getStartColor()->setARGB($title_bgcolor); 
				}
			}
	}
	$outputFileName = date('Y-m-d H:i',time()).".xls";
	header("Content-Type:application/octet-stream;charset=utf-8");
	header('Content-Disposition: attachment; filename=' . $outputFileName); 
	$objWriter->save('php://output');
	
}



//add yes123 2015-04-15 获取交强险信息
function get_compulsory_policy_info($policy_id)
{
	$sql = "SELECT * FROM t_insurance_policy_car_sun_compulsory WHERE policy_id='$policy_id'";
	$policy_car_sun_compulsory = $GLOBALS['db']->getRow($sql);			
	return $policy_car_sun_compulsory;
}


//add yes123 2015-04-15 获取商业险信息
function get_commercial_policy_info($policy_id)
{
	$sql = "SELECT * FROM t_insurance_policy_car_sun_commercial WHERE policy_id='$policy_id'";
	$policy_car_sun_commercial = $GLOBALS['db']->getAll($sql);			
	return $policy_car_sun_commercial;
}

//add yes123 2015-04-15 获取车信息
function get_policy_car_info($policy_id){
	$sql = " SELECT * FROM t_insurance_policy_car_vehicleinfo WHERE policy_id = '$policy_id'" ;
	$car = $GLOBALS['db']->getRow($sql);
	
	//通过城市编码获取城市名称
	$sql = " SELECT cityName FROM bx_city WHERE cityID = '$car[carCity]'" ;
	$cityName = $GLOBALS['db']->getOne($sql);
	if(!$cityName)
	{
		$sql = " SELECT provinceName FROM bx_province WHERE cityID = '$car[carProvince]'" ;
		$cityName = $GLOBALS['db']->getOne($sql);	
	}
	$car['cityName'] =$cityName;
	
	return $car;
}


function get_insurer_code_by_role()
{
	ss_log(__FUNCTION__." action_list:".$_SESSION['action_list']);
	if($_SESSION['action_list']=='proposal_list'){
			//1.获取角色ID
			$role_sql = "SELECT role_id FROM " . $GLOBALS['ecs']->table('admin_user') . " WHERE user_id=".$_SESSION['admin_id'];
			ss_log("获取角色ID:".$role_sql);
			$role_id = $GLOBALS['db']->getOne($role_sql);
			
			if($role_id){
				//2.获取角色名称
				$role_name_sql = "SELECT role_name FROM " . $GLOBALS['ecs']->table('role') . " WHERE role_id=".$role_id;
				ss_log("2.获取角色名称:".$role_name_sql);
				$role_name = trim($GLOBALS['db']->getOne($role_name_sql));
				//3.通过岗位名称查询保险公司code
				if($role_name)
				{
					//add by yes123 2014-11-12 获取当前用户所属角色->获取保险公司->保险公司编码
					$sql = "SELECT insurer_code FROM bx_role_factor WHERE role_name='$role_name'";
					ss_log("3.获取保险公司编码:".$sql);
					$insurer_code = $GLOBALS['db']->getOne($sql);
					return $insurer_code;
				}
					
			}
			
	}

}


//add yes123 2015-5-07 更新 国寿保单号
function update_chinalife_policy($strs)
{
	$res = array();
	$res['code']=1;//1为正常投保
	$temp_policy_status = trim($strs['0']);
	if($temp_policy_status!='Y')
	{
		$res['code']=4;
		$res['msg']='被保险人编号'.$strs[1].'处理标记不是Y，不能修改保单为已投保';
		return $res;
	}
	//获取保单ID
	$policy_id = trim($strs[45]);
	//获取保单号
	$policy_on = trim($strs[7]);
	//获取保险起期
	$start_date = trim($strs[60]);
	if($policy_id)
	{
		$sql = "SELECT order_id FROM t_insurance_policy WHERE policy_id='$policy_id'";
		ss_log(__FUNCTION__."$sql");
		$order_id = $GLOBALS['db']->getOne($sql);
		//判断订单是否付款
		$sql = "SELECT * FROM bx_order_info WHERE order_id='$order_id'";
		$order_info = $GLOBALS['db']->getRow($sql);
		if($order_info['order_status']==1 && $order_info['pay_status']==2) 
		{
			//如果已投保状态，就不用重复更新了		
			$sql = "SELECT policy_status FROM t_insurance_policy WHERE policy_id='$policy_id'";
			$policy_status = $GLOBALS['db']->getOne($sql);
			$policy_status = trim($policy_status);
			ss_log(__FUNCTION__.",policy_status:".$policy_status);
			if($policy_status=='insured')
			{
				$res['code']=4;
				$res['msg']='被保险人编号'.$strs[1].' 已投保，无需再更新';
				ss_log(__FUNCTION__.$res['msg']);
				return $res;	
			}
		
		
			$sql = "UPDATE t_insurance_policy SET policy_no='$policy_on',policy_status='insured' ,start_date='$start_date' WHERE policy_id='$policy_id'";
			ss_log(__FUNCTION__.'，sql：'.$sql);
			if($GLOBALS['db']->query($sql))
			{
				$sql="UPDATE " . $GLOBALS['ecs']->table('order_info') . " SET insured_policy_num=insured_policy_num+1 WHERE order_id=".$order_id;
				ss_log($sql);
				$GLOBALS['db']->query($sql);
				$res['msg']='ok';
				return $res;
			}
			else
			{
				$res['code']=4;
				$res['msg']='被保险人编号'.$strs[1].'执行sql异常：'.$sql;
				return $res;	
			}
		
		}
		else
		{
			$res['code']=4;
			$res['msg']='被保险人编号'.$strs[1].'订单未支付，不能修改保单为已投保';
			return $res;
		}

	}		
	else
	{
		$res['code']=4;
		$res['msg']='被保险人编号'.$strs[1].'付款帐号(保单ID)为空，不能修改保单为已投保';
		return $res;
	}
	
}

//add yes123 2015-5-07 更新 国寿 保单，已退保
function withdraw_chinalife_policy($strs)
{
	$res = array();
	$res['code']=1;//1为正常注销
	
	//获取保单ID
	$policy_id = trim($strs[45]);
	//判断是否有client_id 有并且client_id不等于agent_uid 那么不能正常退保
	if($policy_id)
	{
		$sql = "SELECT * FROM t_insurance_policy WHERE policy_id='$policy_id'";
		ss_log(__FUNCTION__."$sql");
		$policy = $GLOBALS['db']->getRow($sql);
		if($policy)
		{
			if($policy['client_id'])
			{
			/*	$res['code']=4;
				$res['msg']='被保险人编号'.$strs[1].'是c端用户微信支付，无法退款，只能退服务费';
				return $res;*/
/*					
					//获取openid 来判断是不是同一个人
					$sql = "SELECT * FROM bx_users WHERE user_id='$policy[agent_uid]'";
					ss_log(__FUNCTION__." 获取代理人".$sql);
					$user = $GLOBALS['db']->getRow($sql);
					
					$sql = "SELECT * FROM bx_users_c WHERE user_id='$policy[agent_uid]'";
					ss_log(__FUNCTION__." 获取C端用户".$sql);
					$users_c = $GLOBALS['db']->getRow($sql);
					
					if($user['openid_id']!=$users_c['c_openid'])
					{
				
					}*/

			}
			
			$policy['policy_status'] = trim($policy['policy_status']);
			if($policy['policy_status']=='insured')
			{
				require_once (ROOT_PATH . 'includes/lib_order.php');
				//注销
				withdraw_order($policy_id);
				
				$sql = "UPDATE t_insurance_policy SET policy_no='$policy_on',policy_status='canceled',pay_status=0 WHERE policy_id='$policy_id'";
				ss_log(__FUNCTION__.'，policy_status=saved sql：'.$sql);
				$GLOBALS['db']->query($sql);
				
				return $res;
			}
			else
			{
				$res['code']=4;
				$res['msg']='被保险人编号'.$strs[1].",非投保状态，无法退保";
				return $res;
			}
			
			
		}
		else
		{
			$res['code']=4;
			$res['msg']='被保险人编号'.$strs[1].",通过付款帐号(保单ID)查询不到保单，无法退保";
			
			return $res;
		}
	}
	
}

function policy_operation_logs()
{
	
	$user_id = !empty ($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$admin_ip = !empty ($_REQUEST['ip']) ? trim($_REQUEST['ip']) : '';
	$s_log_time = !empty ($_REQUEST['start_time']) ? strtotime($_REQUEST['start_time']) : '';
	$e_log_time = !empty ($_REQUEST['end_time']) ? strtotime($_REQUEST['end_time']) : '';

	$filter = array ();
	$filter['sort_by'] = empty ($_REQUEST['sort_by']) ? 'al.log_id' : trim($_REQUEST['sort_by']);
	$filter['sort_order'] = empty ($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
	$filter['user_name'] = empty ($_REQUEST['user_name']) ? '' : trim($_REQUEST['user_name']);
	
	//保单ID
	$filter['policy_id'] = empty ($_REQUEST['policy_id']) ? '' : trim($_REQUEST['policy_id']);
	//保单号
	$filter['policy_no'] = empty ($_REQUEST['policy_no']) ? '' : trim($_REQUEST['policy_no']);
	
	$filter['start_time'] = empty ($_REQUEST['start_time']) ? '' : trim($_REQUEST['start_time']);
	$filter['end_time'] = empty ($_REQUEST['end_time']) ? '' : trim($_REQUEST['end_time']);
	$filter['ip'] = empty ($_REQUEST['ip']) ? '' : trim($_REQUEST['ip']);
	$filter['action_note'] = empty ($_REQUEST['action_note']) ? '' : trim($_REQUEST['action_note']);
	
	//add yes123 2014-12-09 按照日志类型查询
	$filter['operation_type'] = empty ($_REQUEST['operation_type']) ? '' : trim($_REQUEST['operation_type']);

	//查询条件
	$where = " WHERE 1 ";
	if (!empty ($user_id)) {
		$where .= " AND al.user_id = '$user_id' ";
	}
	elseif (!empty ($admin_ip)) {
		$where .= " AND al.ip_address = '$admin_ip' ";
	}

	if ($s_log_time != '') {
		$where .= " AND al.log_time >= '$s_log_time' ";
	}

	if ($e_log_time != '') {
		$where .= " AND al.log_time <= '$e_log_time' ";
	}

	if ($filter['action_note'] != '') {
		$where .= " AND al.action_note LIKE '%" . $filter['action_note'] . "%'";
	}
	//add yes123 2014-12-09 按照日志类型查询
	if ($filter['operation_type'] != '') {
		$where .= " AND al.operation_type ='" . $filter['operation_type'] . "'";
	}
	
	if ($filter['policy_id'] != '') {
		$where .= " AND al.policy_id ='" . $filter['policy_id'] . "'";
	}
	
	if ($filter['policy_no'] != '') {
		$where .= " AND al.policy_no ='" . $filter['policy_no'] . "'";
	}
	
	if ($filter['user_name'] != '') {
		$user_id_sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('admin_user') ." WHERE user_name='".$filter['user_name']."'";
		$operate_user_id = $GLOBALS['db']->getOne($user_id_sql);
		if($operate_user_id){
			$where .= " AND al.user_id = '$operate_user_id' ";
		}
		else
		{
			$where .= " AND al.user_id = 'no' ";
		}		
	}

	

	/* 获得总记录数据 */
	$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('policy_operation_log') . ' AS al ' . $where;
	$filter['record_count'] = $GLOBALS['db']->getOne($sql);

	$filter = page_and_size($filter);

	/* 获取管理员日志记录 */
	$list = array ();
	$sql = 'SELECT al.*, u.user_name FROM ' . $GLOBALS['ecs']->table('policy_operation_log') . ' AS al ' .
	'LEFT JOIN ' . $GLOBALS['ecs']->table('admin_user') . ' AS u ON u.user_id = al.user_id ' .
	$where . ' ORDER by ' . $filter['sort_by'] . ' ' . $filter['sort_order'];
	
	$res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);

	while ($rows = $GLOBALS['db']->fetchRow($res)) {
		$rows['log_time'] = local_date('Y-m-d H:i', $rows['log_time']);

		$list[] = $rows;
	}

	return array (
		'list' => $list,
		'filter' => $filter,
		'page_count' => $filter['page_count'],
		'record_count' => $filter['record_count']
	);
	
	
}

?>