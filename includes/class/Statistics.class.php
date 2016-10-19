<?php

/**
 * yes123 2014-12-10  数据统计类
 */
include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
include_once(ROOT_PATH . '/baoxian/source/function_debug.php');
require_once(ROOT_PATH . '/languages/zh_cn/admin/account_log.php');
class Statistics {
	
	public $page_size=15;
	public $limit="";
	function Statistics() {
	}

	public function getDataList() {
		 /* 初始化分页参数 */
		$filter = $this->initFilter();
		
		if($filter['sort']) 
		{
			//按照总推荐数统计
			if($filter['sort']=='recommend_total')
			{
				$res = $this->byRecommendStatistics($filter);
				//echo "<pre>";print_r($res);
			}
			
			//按照总收入数统计
			if($filter['sort']=='income_total')
			{
				$res = $this->byIncomeStatistics($filter);
			}
			
			//按照消费总额数统计
			if($filter['sort']=='xiaofei_total')
			{
				$res = $this->byConsumptionStatistics($filter);
					
			}
			
			//按照账户余额统计
			if($filter['sort']=='user_money')
			{
				$res = $this->byUserMoneyStatistics($filter);
				
			}
		}
		
		//如果通过用户信息查询列表为空， 那么再查一次
		if(!$res['data'])
		{
			if($filter['user_name'] || $filter['real_name'])
			{
				$res = $this->getDataByUser($filter);
				
			}
		}
		
		//计算page_count
		$filter['page_count']     = $res['record_count'] > 0 ? ceil($res['record_count'] / $filter['page_size']) : 1;
		$res['page_count'] = $filter['page_count'] ;
		return $res;
	}
	
	
	public function statisticList()
	{
		global $_LANG;
		 
		$filter = array(
	        's_change_time' =>$_REQUEST['s_change_time'],
	        'e_change_time' =>$_REQUEST['e_change_time'],
	        'user_name' =>trim($_REQUEST['user_name']),
	        'real_name' =>trim($_REQUEST['real_name']),
	        'sort' =>$_REQUEST['sort'],
	        'order' =>$_REQUEST['order'],
	        'user_ids' =>isset($_REQUEST['user_ids'])?$_REQUEST['user_ids']:0
	    );
		
		$page_size= isset($_REQUEST['page_size'])?intval($_REQUEST['page_size']):15;
		ss_log(__FUNCTION__.',page_size:'.$page_size);
		if($page_size>100)
		{
			$_REQUEST['page_size']=100;
			
			
		}
		//最大只能查50条记录
		if($_COOKIE['ECSCP']['page_size']>100)
		{
			$_COOKIE['ECSCP']['page_size']=100;
		}
		
		$user_where = " WHERE check_status='".CHECKED_CHECK_STATUS."' AND user_name<>'ebaoins_user' ";
		$recommend_where="";
		$income_where="";
		$xiaofei_where ="";
		
		if(empty($filter['sort']) && empty($filter['order']) )
		{
			$filter['sort']="income_total";
			$filter['order']="DESC";
			
		}
		
		
		if($filter['s_change_time'])
		{
			$recommend_where.="AND reg_time>='".strtotime($filter['s_change_time'])."'";
			$income_where.="AND change_time>='".strtotime($filter['s_change_time'])."'";
			$xiaofei_where.="AND dateline>='".strtotime($filter['s_change_time'])."'";
			
		}
		
		if($filter['e_change_time'])
		{
			$recommend_where.="AND reg_time<='".strtotime($filter['e_change_time'])."'";
			$income_where.="AND change_time<='".strtotime($filter['e_change_time'])."'";
			$xiaofei_where.="AND dateline<='".strtotime($filter['e_change_time'])."'";
		}
		
		
		if($filter['user_name'])
		{
			$user_where.=" AND user_name LIKE '%$filter[user_name]%' ";
		}
		if($filter['real_name'])
		{
			$user_where.=" AND real_name LIKE '%$filter[real_name]%' ";
		}
		
		if($filter['user_ids'])
		{
			$user_where.=" AND user_id IN($filter[user_ids]) ";
		}
		
		$filter['record_count'] = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('users') . $user_where);
	    $filter = page_and_size($filter);
		
		
		$sql  = " SELECT
		  u.user_id,
		  u.check_status,
		  u.institution_id,
		  u.user_name,
		  u.real_name,
		  u.reg_time,
		  u.user_money,
		  (SELECT COUNT(user_id) FROM bx_users WHERE parent_id=u.user_id $recommend_where ) AS recommend_total,
		  (SELECT COUNT(user_id) FROM bx_users WHERE parent_id=u.user_id AND check_status='".CHECKED_CHECK_STATUS."' $recommend_where ) AS checked_recommend_total,
		  (SELECT SUM(total_premium)  FROM t_insurance_policy  WHERE agent_uid IN(SELECT user_id FROM bx_users WHERE parent_id=u.user_id) AND policy_status='insured' $xiaofei_where) AS child_xiaofei_total, 
		  (SELECT SUM(user_money) FROM bx_account_log WHERE user_id=u.user_id $income_where AND incoming_type='$_LANG[ge_ren]') AS income_geren_total,
		  (SELECT SUM(user_money) FROM bx_account_log WHERE user_id=u.user_id $income_where AND incoming_type='$_LANG[tui_jian]') AS income_tuijian_total,
		  (SELECT SUM(user_money) FROM bx_account_log WHERE user_id=u.user_id $income_where AND change_desc LIKE '%新用户审核通过%') AS income_tuijian_award_total,
		  (SELECT SUM(user_money) FROM bx_account_log WHERE user_id=u.user_id $income_where AND change_desc LIKE '%渠道%') AS income_ji_gou_total,
		  (SELECT SUM(user_money) FROM bx_account_log WHERE user_id=u.user_id $income_where AND ((incoming_type<>'' AND incoming_type IS NOT NULL)  OR change_desc LIKE '%新用户审核通过%') ) AS income_total, 
		  (SELECT SUM(user_money) FROM bx_account_log WHERE user_id=u.user_id $income_where AND change_type IN(0,2) AND change_desc LIKE '%充值%' ) AS recharge_total, 
		  (SELECT SUM(user_money) FROM bx_account_log WHERE user_id=u.user_id $income_where  AND change_desc LIKE '%提现%' AND change_type=".ACT_DRAWING." ) AS withdraw_total, 
		  (SELECT SUM(total_premium) FROM t_insurance_policy WHERE agent_uid=u.user_id  AND policy_status='insured' $xiaofei_where) AS xiaofei_total 
		  FROM bx_users u $user_where  ORDER BY $filter[sort] $filter[order]
		  LIMIT $filter[start] , $filter[page_size]";
		  
		  
		  ss_log("statisticList:".$sql);
		 
		  $data = $GLOBALS['db']->getAll($sql);
		 
		 $arr = array('data' => $data, 'filter' => $filter,
         'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
         
    	 return $arr;
		 
		
		
	}
	

	//按照收入统计 
	public function byIncomeStatistics($filter) 
	{
		$where = $this->whereLogCondition($filter);
		$where.="AND incoming_type IS NOT NULL AND incoming_type <> '' ";
		$orderby = " ORDER BY income_total ".$filter['order'];
		$select_sql = "SELECT SUM(a.user_money) AS income_total, b.user_name,b.real_name,b.user_money,b.user_id FROM " .
		$GLOBALS['ecs']->table('account_log')." a ," . $GLOBALS['ecs']->table('users')." b WHERE  a.user_id=b.user_id  ".$where." GROUP BY a.user_id " .$orderby;		
	
	
		$record_count = count($GLOBALS['db']->getAll($select_sql));
		
		$data_list =$GLOBALS['db']->getAll($select_sql.$this->limit);
		$user_ids = CommonUtils :: arrToStr($data_list, 'user_id');
		if($user_ids)
		{
			//推荐总计
			$data_list = $this->getRecommendList($data_list,$user_ids);
			
			//消费总额
			$data_list = $this->getXiaoFeiList($data_list,$user_ids);
			
			//被推荐消费总计
			$data_list = $this->getChildXiaoFeiList($data_list);
			
			//推荐和订单服务费
			$data_list = $this->getIncomeList($data_list,$user_ids);
		}
		
		return array('data'=>$data_list,'record_count'=>$record_count,'filter'=>$filter);
		
	}

	//按照消费统计 
	public function byConsumptionStatistics($filter) 
	{
		$where = $this->whereLogCondition($filter);
		$where.="AND a.user_money<0 ";
		$orderby=" ORDER BY xiaofei_total ".$filter['order'];
		
		$select_sql = "SELECT SUM(a.user_money) AS xiaofei_total, b.user_name,b.real_name,b.user_money,b.user_id FROM " .
		$GLOBALS['ecs']->table('account_log')." a ," . $GLOBALS['ecs']->table('users')." b WHERE  a.user_id=b.user_id  ".$where." GROUP BY user_id ".$orderby;		
	
		$record_count = count($GLOBALS['db']->getAll($select_sql));
		$data_list =$GLOBALS['db']->getAll($select_sql.$this->limit);
		$user_ids = CommonUtils :: arrToStr($data_list, 'user_id');
		if($user_ids)
		{	//推荐总计
			$data_list = $this->getRecommendList($data_list,$user_ids);
			//收入总计
			$data_list = $this->getIncomeList($data_list,$user_ids);
			
			//被推荐消费总计
			$data_list = $this->getChildXiaoFeiList($data_list);
			
			//推荐和订单服务费
			$data_list = $this->getIncomeList($data_list,$user_ids);
		}
		
		return array('data'=>$data_list,'record_count'=>$record_count,'filter'=>$filter);
	}


	//按照余额统计
	public function byUserMoneyStatistics($filter) 
	{
		$where=" user_money>0 ";
		//处理时间条件
		if ($filter['s_change_time'] != '') {
			$where .= " AND reg_time >='" . strtotime($filter['s_change_time']) . "'";
		}
		if ($filter['e_change_time'] != '') {
			$where .= " AND reg_time <='" . strtotime($filter['e_change_time']) . "'";
		}

		//用户名
		if ($filter['user_name']) {
			$user_id_sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_name LIKE '%" . trim($_REQUEST['user_name']) . "%' ";
			$user_id = $GLOBALS['db']->getAll($user_id_sql);
			if ($user_id) {
				$user_ids = CommonUtils :: arrToStr($user_id, 'user_id');
				$where .= " AND user_id IN(".$user_ids.")";
			} else {
				$where .= " AND user_id =0 ";
			}
		}
		//真名
		if ($filter['real_name']) {
			$user_id_sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users') . " WHERE real_name LIKE '%" . $filter['real_name'] . "%' ";
			$user_id = $GLOBALS['db']->getAll($user_id_sql);
			if ($user_id) {
				$user_ids = CommonUtils :: arrToStr($user_id, 'user_id');
				$where .= " AND user_id IN(".$user_ids.")";
			} else {
				$where .= " AND user_id =0 ";
			}
			
		}
		
		
		
		$orderby=" ORDER BY user_money ".$filter['order'];
		
		$select_sql = "SELECT * FROM bx_users WHERE " . $where .$orderby;
		
		$record_count = count($GLOBALS['db']->getAll($select_sql));
		$data_list =$GLOBALS['db']->getAll($select_sql.$this->limit);
		$user_ids = CommonUtils :: arrToStr($data_list, 'user_id');
		if($user_ids)
		{	//推荐总计
			$data_list = $this->getRecommendList($data_list,$user_ids);
			//收入总计
			$data_list = $this->getIncomeList($data_list,$user_ids);
			//消费统计
			$data_list = $this->getXiaoFeiList($data_list,$user_ids);
			
			//被推荐消费总计
			$data_list = $this->getChildXiaoFeiList($data_list);
			
			//推荐和订单服务费
			$data_list = $this->getIncomeList($data_list,$user_ids);
		}
		
		return array('data'=>$data_list,'record_count'=>$record_count,'filter'=>$filter);
	}

	//推荐总计
	public function  getRecommendList($data_list,$user_ids)
	{
		$select_sql = "SELECT parent_id,COUNT(parent_id) AS recommend_total FROM bx_users WHERE parent_id IN(".$user_ids.") GROUP BY parent_id " ;
		$recommend_total = $GLOBALS['db']->getAll($select_sql);
		
		foreach ($data_list as $key => $user) {
			foreach ($recommend_total as $value) {
				if ($user['user_id'] == $value['parent_id']) {
					$data_list[$key]['recommend_total'] = $value['recommend_total'];
					break;
				}
			}
		}
		
		// 获取正式会员总数
		$checked_select_sql = "SELECT parent_id,COUNT(parent_id) AS checked_recommend_total FROM bx_users WHERE check_status='".CHECKED_CHECK_STATUS."' AND  parent_id IN(".$user_ids.") GROUP BY parent_id " ;
		$checked_recommend_total = $GLOBALS['db']->getAll($checked_select_sql);
		
		foreach ($data_list as $key => $user) {
			foreach ($checked_recommend_total as $value) {
				if ($user['user_id'] == $value['parent_id']) {
					$data_list[$key]['checked_recommend_total'] = $value['checked_recommend_total'];
					break;
				}
			}
		}
		
		return $data_list;
	}
	
	//消费总计
	public function getXiaoFeiList($data_list,$user_ids)
	{
		$sql = "SELECT user_id,SUM(user_money) AS xiaofei_total FROM bx_account_log  WHERE user_id IN(" . $user_ids . ") AND user_money<0 GROUP BY user_id";
		$user_xiaofei_total = $GLOBALS['db']->getAll($sql);

		foreach ($data_list as $key => $user) {
			foreach ($user_xiaofei_total as $value) {
				if ($user['user_id'] == $value['user_id']) {
					$data_list[$key]['xiaofei_total'] = $value['xiaofei_total'];
					break;
				}
			}
		}
		
		return $data_list;
	}
	
	
	//被推荐人消费总计
	public function getChildXiaoFeiList($data_list)
	{
		//遍历所有parent_id 获取它的子类消费总计
		foreach ($data_list as $key => $value ) {
			$sql = "SELECT SUM(total_premium)  FROM t_insurance_policy  " .
				   "WHERE agent_uid IN(SELECT user_id FROM bx_users WHERE parent_id=$value[user_id]) AND policy_status='insured'";
			
			
			$data_list[$key]['child_xiaofei_total'] =$GLOBALS['db']->getOne($sql);
		}
		return $data_list;
	}
	
	
	//收入总计
	public function getIncomeList($data_list,$user_ids)
	{
		
		global $_LANG;
		
		$sql = "SELECT user_id,SUM(user_money) AS shouru_total FROM bx_account_log  WHERE user_id IN(" . $user_ids . ") " .
		" AND user_money>0 AND incoming_type IS NOT NULL AND incoming_type <>'' GROUP BY user_id ";
		$user_shouru_list = $GLOBALS['db']->getAll($sql);
		
		$sql = "SELECT user_id,SUM(user_money) AS shouru_total FROM bx_account_log  WHERE user_id IN(" . $user_ids . ") " .
		" AND user_money>0 AND incoming_type IS NOT NULL AND incoming_type = '$_LANG[tui_jian]' GROUP BY user_id ";
		$user_tui_jian_list = $GLOBALS['db']->getAll($sql);
		
	
		$sql = "SELECT user_id,SUM(user_money) AS shouru_total FROM bx_account_log  WHERE user_id IN(" . $user_ids . ") " .
		" AND user_money>0 AND incoming_type IS NOT NULL AND incoming_type = '$_LANG[ge_ren]' GROUP BY user_id ";
		
		
		$user_ge_ren_list = $GLOBALS['db']->getAll($sql);
		
		foreach ($data_list as $key => $user) {
			foreach ($user_shouru_list as  $value) {
				if ($user['user_id'] == $value['user_id']) {
					$data_list[$key]['income_total'] = $value['shouru_total'];
					break;
				}
			}
			foreach ($user_tui_jian_list as  $value) {
				if ($user['user_id'] == $value['user_id']) {
					$data_list[$key]['income_tuijian_total'] = $value['shouru_total'];
					break;
				}
			}
			foreach ($user_ge_ren_list as  $value) {
				if ($user['user_id'] == $value['user_id']) {
					$data_list[$key]['income_geren_total'] = $value['shouru_total'];
					break;
				}
			}
		}
		
		
		return $data_list;
	}
	
	//按照推荐人数统计 
	public function byRecommendStatistics($filter) {
		$where .= " parent_id <>0 ";
		$where .= $this->whereUserCondition($filter);
	
		$orderby=" ORDER BY recommend_total ".$filter['order'];
		
		$select_sql = "SELECT parent_id,COUNT(parent_id) AS recommend_total FROM bx_users WHERE " . $where . " GROUP BY parent_id ".$orderby;
      	
      	ss_log('tuijian order by：'.$select_sql);
        //获取总数
        $record_count = count($GLOBALS['db']->getAll($select_sql));
        
		$data_list =$GLOBALS['db']->getAll($select_sql.$this->limit);
		
	
		
		if($data_list)
		{
			// start modify yes123 2014-12-28 推荐人排序修正
			foreach ( $data_list as $key => $value ) {
       			$sql = "SELECT user_id,user_name,real_name,user_money FROM bx_users WHERE user_id =". $value['parent_id'];
				$user = $GLOBALS['db']->getRow($sql);
				$data_list[$key]['user_id'] =$user['user_id'];
				$data_list[$key]['user_name'] =$user['user_name'];
				$data_list[$key]['real_name'] =$user['real_name'];
				$data_list[$key]['user_money'] =$user['user_money'];
			}
			
			
			$user_ids = CommonUtils :: arrToStr($data_list, 'parent_id');
			if ($user_ids) {
				//2.获取消费金额
				$data_list = $this->getXiaoFeiList($data_list,$user_ids);
	
				//3.获取收入
				$data_list = $this->getIncomeList($data_list,$user_ids);
				
				//4.获取被推荐人收入总计
				$data_list = $this->getChildXiaoFeiList($data_list,$user_ids);
				
				//5.推荐和订单服务费
				$data_list = $this->getIncomeList($data_list,$user_ids);
				
				//6.获取被推荐已审核总计
				$checked_select_sql = "SELECT parent_id,COUNT(parent_id) AS checked_recommend_total FROM bx_users WHERE check_status='".CHECKED_CHECK_STATUS."' AND parent_id IN(".$user_ids.") GROUP BY parent_id " ;
				$checked_recommend_total = $GLOBALS['db']->getAll($checked_select_sql);
				foreach ($data_list as $key => $user) {
					foreach ($checked_recommend_total as $value) {
						if ($user['user_id'] == $value['parent_id']) {
							$data_list[$key]['checked_recommend_total'] = $value['checked_recommend_total'];
							break;
						}
					}
				}
				
				
				return array('data'=>$data_list,'record_count'=>$record_count,'filter'=>$filter);
			}
			
			// end modify yes123 2014-12-28 推荐人排序修正
		}
		else
		{
			return array('data'=>array(),'record_count'=>0,'filter'=>$filter);
		}

		
	}
	
	//bx_account_log的条件
	public function whereLogCondition($filter) 
	{
		$where = "";
				//处理时间条件
		if ($filter['s_change_time'] != '') {
			$where .= " AND a.change_time >='" . strtotime($filter['s_change_time']) . "'";
		}
		if ($filter['e_change_time'] != '') {
			$where .= " AND a.change_time <='" . strtotime($filter['e_change_time']) . "'";
		}

		//用户名
		if ($filter['user_name']) {
			$user_id_sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_name LIKE '%" . trim($_REQUEST['user_name']) . "%' ";
			$user_id = $GLOBALS['db']->getAll($user_id_sql);
			if ($user_id) {
				$user_ids = CommonUtils :: arrToStr($user_id, 'user_id');
				$where .= " AND a.user_id IN(".$user_ids.")";
			} else {
				$where .= " AND a.user_id =0 ";
			}
		}
		//真名
		if ($filter['real_name']) {
			$user_id_sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users') . " WHERE real_name LIKE '%" . $filter['real_name'] . "%' ";
			$user_id = $GLOBALS['db']->getAll($user_id_sql);
			if ($user_id) {
				$user_ids = CommonUtils :: arrToStr($user_id, 'user_id');
				$where .= " AND a.user_id IN(".$user_ids.")";
			} else {
				$where .= " AND a.user_id =0 ";
			}
		}
		
		if ($filter['incoming_type']) {
			$where .= " AND a.incoming_type ='" . $filter['incoming_type'] . "'";
		}
		
		
		return $where;
	}
	
	
	//users表的条件
	public function whereUserCondition($filter) 
	{
		$where=" ";
		//处理时间条件
		if ($filter['s_change_time'] != '') {
			$where .= " AND reg_time >='" . strtotime($filter['s_change_time']) . "'";
		}
		if ($filter['e_change_time'] != '') {
			$where .= " AND reg_time <='" . strtotime($filter['e_change_time']) . "'";
		}

		//用户名
		if ($filter['user_name']) {
			$user_id_sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_name LIKE '%" . trim($_REQUEST['user_name']) . "%' ";
			$user_id = $GLOBALS['db']->getAll($user_id_sql);
			if ($user_id) {
				$user_ids = CommonUtils :: arrToStr($user_id, 'user_id');
				$where .= " AND parent_id IN(".$user_ids.")";
			} else {
				$where .= " AND parent_id =0 ";
			}
		}
		//真名
		if ($filter['real_name']) {
			$user_id_sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users') . " WHERE real_name LIKE '%" . $filter['real_name'] . "%' ";
			$user_id = $GLOBALS['db']->getAll($user_id_sql);
			if ($user_id) {
				$user_ids = CommonUtils :: arrToStr($user_id, 'user_id');
				$where .= " AND parent_id IN(".$user_ids.")";
			} else {
				$where .= " AND parent_id =0 ";
			}
			
		}
		
		return $where;
	}
	
	
	//add yes123 2014-12-22通过用户信息查询
	public function getDataByUser($filter)
	{
		$where = " 1=1 ";
		if($filter['user_name'])
		{
			$where.=" AND user_name LIKE '%$filter[user_name]%'";
		}
		
		if($filter['real_name'])
		{
			$where.=" AND real_name LIKE '%$filter[real_name]%'";
		}
		
		
		
		$sql = "SELECT * FROM bx_users WHERE ".$where;
		$record_count = count($GLOBALS['db']->getAll($sql));
		
		$data_list = $GLOBALS['db']->getAll($sql.$this->limit);
		$user_ids = CommonUtils :: arrToStr($data_list, 'user_id');	
		if($user_ids)
		{	//推荐总计
			$data_list = $this->getRecommendList($data_list,$user_ids);
			//收入总计
			$data_list = $this->getIncomeList($data_list,$user_ids);
			//消费统计
			$data_list = $this->getXiaoFeiList($data_list,$user_ids);
			
			//被推荐消费总计
			$data_list = $this->getChildXiaoFeiList($data_list);
			
			//推荐和订单服务费
			$data_list = $this->getIncomeList($data_list,$user_ids);
		}
		
		return array('data'=>$data_list,'record_count'=>$record_count,'filter'=>$filter);
			
		
	}
	
	private function initFilter()
	{
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
		
		if(!$filter['sort'])
		{
			$filter['sort']='income_total';
		}
		
				//分页部分
		$filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);
	    if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0)
	    {
	      $filter['page_size'] = intval($_REQUEST['page_size']);
	    }
	    elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0)
	    {
	      $filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
	    }
	    else
	    {
	      $filter['page_size'] = 15;
	    }
	
		if(!$filter['order'])
		{
			$filter['order']='DESC';
		}
				
		$this->limit = " LIMIT ".(($filter['page']-1)*$filter['page_size']).",".$filter['page_size'];
		
		return $filter;
		
	}
	
	
	//add yes123 2015-01-08 导出保单功能
	/**
	 *  通过订单sn取得订单ID
	 *  @param  array  $title   标题数组
	 *  @param  string    $title_bgcolor    标题颜色
	 *  @param  string    $title_fontcolor    标题字体颜色
	 *  @param  string    $type    表示前台还是后台，默认是前台
	 */
	function exportStatisticsList($user_ids){
		include_once(ROOT_PATH . 'oop/lib/Excel/PHPExcel.php');
		$title=array('用户ID','会员帐号','姓名','推荐人总数','正式会员总数','被推荐人消费总计','订单服务费','推荐服务费','推荐奖励','总收益','消费总额','充值总计','提现总计','账户余额');
		
		$res = $this->statisticList($user_ids);
		
		$user_list = $res['data'];
		
		$objExcel = new PHPExcel();
		
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);     // 用于其他版本格式   
		
			//设置当前的sheet索引，用于后续的内容操作。   
		$objExcel->setActiveSheetIndex(0);   
		$objActSheet = $objExcel->getActiveSheet();   
		
		$objActSheet->setTitle('sheet1');   
	
		//modify yes123 2015-01-19 如果是后台导出保单，需要导出支付方式和使用的余额
		
		$title=array('用户ID','会员帐号','姓名','推荐人总数','正式会员总数','被推荐人消费总计',
					'订单服务费','推荐服务费','推荐奖励','渠道管理费','总收益','消费总额','充值总计','提现总计','账户余额');
			
		$abc = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O');
		/*设置特别表格的宽度*/  
		$objExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(10); //用户ID
	   	$objExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(20); //用户名
	   	$objExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(20); //真是姓名
	   	$objExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(12); //推荐人总数
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(12);  //正式会员总数
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(12);  //被推荐人消费总计
	    
	    
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(12);  //订单服务费
	   	$objExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(12); //推荐服务费
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(12);  //推荐奖励
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('J')->setWidth(12);  //渠道管理费
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('K')->setWidth(12);  //总收益
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('L')->setWidth(12);  //消费总额
	   	$objExcel->setActiveSheetIndex(0)->getColumnDimension('M')->setWidth(12); //充值总计
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('N')->setWidth(12);  //提现总计
	    $objExcel->setActiveSheetIndex(0)->getColumnDimension('O')->setWidth(12);  //账户余额
	
	
	    //设置内容居中
		
		$user_array= array();
		$user_array[0]=$title;
		
		if(is_array($user_list))    //add
		{ 
			foreach ($user_list as $key => $value ) 
			{
				$user[0]= $value['user_id']." ";
				$user[1]= $value['user_name']." ";
				$user[2]= $value['real_name']." ";
				$user[3]= $value['recommend_total'];
				$user[4]= $value['checked_recommend_total']." ";
				$user[5]= $value['child_xiaofei_total']." ";
				$user[6]= $value['income_geren_total']." ";
				$user[7]= $value['income_tuijian_total']." ";
				$user[8]= $value['income_tuijian_award_total']." ";
				$user[9]= $value['income_ji_gou_total']." ";
				$user[10]= $value['income_total']." ";
				$user[11]= $value['xiaofei_total']." ";
				$user[12]= $value['recharge_total']." ";
				$user[13]= $value['withdraw_total']." ";
				$user[14]= $value['user_money']." ";
				
		 		$user_array[]=$user;
			}
		}
		
		$title_fontcolor="FF365D6A";
		$title_bgcolor="FFFFFFFF";
		
	
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
	
	
}
?>