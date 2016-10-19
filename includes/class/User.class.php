<?php


/**
 * add yes123 2014-12-31
 * 用户中心类
 * 
 */
require_once (ROOT_PATH . 'baoxian/source/function_debug.php');
include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
include_once (ROOT_PATH . 'baoxian/source/my_const.php');
include_once(ROOT_PATH . 'includes/class/distrbutor.class.php');
class User {

	public function User() {
	}

	//获取index页面基本数据
	private function getIndexBaseData() {

	}

	//获取我的推荐
	public function getAffiliate() {
		global $_LANG;
		$user_id = $_SESSION['user_id'];
		$condition = array ();
		$condition['user_name'] = isset ($_REQUEST['user_name']) ? $_REQUEST['user_name'] : "";
		$condition['rank_id'] = isset ($_REQUEST['rank_id']) ? $_REQUEST['rank_id'] : 0;
		$condition['check_status'] = isset ($_REQUEST['check_status']) ? $_REQUEST['check_status'] : '';
		$condition['real_name'] = isset ($_REQUEST['real_name']) ? $_REQUEST['real_name'] : "";
		$condition['start_time'] = isset ($_REQUEST['start_time']) ? $_REQUEST['start_time'] : "";
		$condition['end_time'] = isset ($_REQUEST['end_time']) ? $_REQUEST['end_time'] : "";
		$condition['act']='affiliate';
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('users') . " WHERE parent_id= '" . $_SESSION['user_id'] . "' ";
		$record_count_sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('users') . " WHERE parent_id= '" . $user_id . "' ";
		$where = "";
		$order_by = " ORDER BY reg_time DESC";
		$page_sql = "";

		if ($condition['user_name']) {
			$where .= " AND  user_name LIKE '%" . $condition['user_name'] . "%' ";
		}
		if ($condition['real_name']) {
			$where .= " AND  real_name LIKE '%" . $condition['real_name'] . "%' ";
		}

		if ($condition['start_time']) {
			$where .= " AND  reg_time >= '" . strtotime($condition['start_time']) . "' ";
		}

		if ($condition['end_time']) {
			$where .= " AND  reg_time <= '" . strtotime($condition['end_time']) . "' ";
		}

		if ($condition['rank_id']) {
			$where .= " AND user_rank='$condition[rank_id]' ";
		}
		
		if ($condition['check_status']) {
			$where .= " AND check_status='$condition[check_status]' ";
		}
	
		//获取总数
		$record_count = $GLOBALS['db']->getOne($record_count_sql . $where);
		$page = isset ($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$pager = get_pager('user.php', $condition, $record_count, $page);

		if ($page > 1) {
			$page = ($page -1) * $pager['size'];
			$page_sql .= " LIMIT " . $page . "," . $pager['size'];
		} else {
			$page_sql .= " LIMIT 0 ," . $pager['size'];
		}
		
		ss_log(__FUNCTION__.",where:".$where);
		
		$user_list = $GLOBALS['db']->getAll($sql . $where . $order_by . $page_sql);
		
		//获取总收益
		$sql = " SELECT SUM(user_money) FROM bx_account_log WHERE user_id IN(
				 SELECT user_id FROM bx_users WHERE parent_id='$user_id')  AND incoming_type='$_LANG[tui_jian]'";
		ss_log("获取总收益：".$sql);
		$earnings_total =  $GLOBALS['db']->getOne($sql);
		if(!$earnings_total){
			$earnings_total=0;
		}
		
		$res = array (
			'user_list' => $user_list,
			'condition' => $condition,
			'earnings_total'=>$earnings_total,
			'pager' => $pager
		);
		return $res;
	}

	/* 会员充值和提现申请记录 ,微信和PC端公用*/
	public function getAccountLog() {
		$condition['end_time'] = isset ($_REQUEST['end_time']) ? $_REQUEST['end_time'] : "";

		$user_id = $_SESSION['user_id'];
		$action = isset ($_REQUEST['act']) ? trim($_REQUEST['act']) : 'default';
		$page = isset ($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$where = "";
		$condition = array ();
		$condition['process_type'] = isset ($_REQUEST['process_type']) ? $_REQUEST['process_type'] : '';
		$condition['start_time'] = isset ($_REQUEST['start_time']) ? $_REQUEST['start_time'] : "";
		$condition['end_time'] = isset ($_REQUEST['end_time']) ? $_REQUEST['end_time'] : "";
		$condition['is_paid'] = isset ($_REQUEST['is_paid']) ? $_REQUEST['is_paid'] : ""; //是否已支付
		$condition['payment'] = isset ($_REQUEST['payment']) ? $_REQUEST['payment'] : ""; //支付方式

		$condition['act'] = $action;

		if ($condition['process_type'] === '0') {
			//add by dingchaoyang 2014-12-5 如果是app端访问，不显示未完成的充值记录
			include_once (ROOT_PATH . 'api/EBaoApp/platformEnvironment.class.php');
			if ((PlatformEnvironment :: isMobilePlatform())) {
				$where .= " AND (is_paid=1) "; //OR (is_paid = 0 AND pay_id =2)) ";
			}
			//end by dingchaoyang 2014-12-5
			$where .= " AND process_type=0 ";
		}
		elseif ($condition['process_type'] == 1) {
			$where .= " AND process_type=1 ";
		}
		if ($condition['start_time']) {
			$where .= " AND  add_time >= '" . strtotime($condition['start_time']) . "' ";
		}

		if ($condition['end_time']) {
			$where .= " AND  add_time <= '" . strtotime($condition['end_time']) . "' ";
		}

		if ($condition['payment']) {
			$where .= " AND  pay_id =" . $condition['payment'];
		}

		if ($condition['is_paid']) {
			if ($condition['is_paid'] == 2) {
				$where .= " AND  is_paid =0";
			} else {
				$where .= " AND  is_paid =1";
			}
		}

		//获取总记录数
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('user_account') .
		" WHERE user_id = '$user_id' " . $where;
		$record_count = $GLOBALS['db']->getOne($sql);

		//分页函数
		$pager = get_pager('user.php', $condition, $record_count, $page);

		$account_log = get_account_log($user_id, $pager['size'], $pager['start'], $where);

		return array (
			'account_log' => $account_log,
			'pager' => $pager,
			'condition' => $condition
		);

	}
	
	//add yes123 2015-01-26 提醒管理员尽快处理提现申请
	public function reminderWithdraw(){
		$id = isset ($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
		if($id){
			$sql = "UPDATE " . $GLOBALS['ecs']->table('user_account') . " SET add_time = '" . time() . "' WHERE id=".$id;
			$GLOBALS['db']->query($sql);
			die(json_encode(array (
					'code' => 0,
					'msg' => '催办申请已提交，请耐心等待！'
			)));
		}
		
	}

	/* 会员账目明细 */
	public function getAccountDetail() {
		$user_id = $_SESSION['user_id'];
		$condition = array ();
		$condition['act'] = "account_detail";
		$condition['order_sn'] = isset ($_REQUEST['order_sn']) ? trim($_REQUEST['order_sn']) : '';
		$condition['s_add_time'] = empty ($_REQUEST['s_add_time']) ? '' : $_REQUEST['s_add_time'];
		$condition['e_add_time'] = empty ($_REQUEST['e_add_time']) ? '' : $_REQUEST['e_add_time'];
		$condition['time_quantum'] = isset ($_REQUEST['time_quantum']) ? $_REQUEST['time_quantum'] : "all";

		//处理最近时间
		$sdTime = sdtimeByZjTime();
		if (count($sdTime) > 0) {
			$condition['s_add_time'] = $sdTime['s_add_time'];
			$condition['e_add_time'] = $sdTime['e_add_time'];
		}

		$where_sql = " WHERE user_id =$user_id ";

		if ($condition['order_sn'] != '') {
			$where_sql .= " AND order_sn=" . $condition['order_sn'];
		}

		if ($condition['s_add_time'] != '') {
			$where_sql .= " AND change_time >=" . strtotime($condition['s_add_time']);
		}
		if ($condition['e_add_time'] != '') {
			$where_sql .= " AND change_time <=" . strtotime($condition['e_add_time']);
		}

		$page = isset ($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;

		/* 获取记录条数 */
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('account_log') . $where_sql .
		" AND user_money <> 0 ";
		$record_count = $GLOBALS['db']->getOne($sql);
		//分页函数
		$pager = get_pager('user.php', $condition, $record_count, $page);

		//获取余额记录
		$account_log = array ();
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('account_log') .
		$where_sql .
		" AND user_money <> 0 " .
		" ORDER BY log_id DESC";
		$res = $GLOBALS['db']->selectLimit($sql, $pager['size'], $pager['start']);
		global $_CFG, $_LANG;
		require_once (ROOT_PATH . 'languages/' . $_CFG['lang'] . '/user.php');
		while ($row = $GLOBALS['db']->fetchRow($res)) {
			$row['change_time'] =date('Y-m-d H:i:s',$row['change_time']);
			$row['type'] = $row['user_money'] > 0 ? $_LANG['account_inc'] : $_LANG['account_dec'];
			$row['user_money'] = price_format(abs($row['user_money']), false);
			$row['frozen_money'] = price_format(abs($row['frozen_money']), false);
			$row['rank_points'] = abs($row['rank_points']);
			$row['pay_points'] = abs($row['pay_points']);
			$row['short_change_desc'] = sub_str($row['change_desc'], 60);
			$row['amount'] = $row['user_money'];
			$account_log[] = $row;
		}

		return array (
			'account_log' => $account_log,
			'pager' => $pager,
			'condition' => $condition
		);

	}

	//start 个人资料
	public function getProfile() {
		$arr = array ();
		$user_id = $_SESSION['user_id'];
		//获取基本信息
		$user_info = get_profile($user_id);
		$arr['user_info'] = $user_info;
		/* 取出注册扩展字段 */
		$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('reg_fields') . ' WHERE type < 2 AND display = 1 ORDER BY dis_order, id';
		$extend_info_list = $GLOBALS['db']->getAll($sql);

		$sql = 'SELECT reg_field_id, content ' .
		'FROM ' . $GLOBALS['ecs']->table('reg_extend_info') .
		" WHERE user_id = $user_id";
		$extend_info_arr = $GLOBALS['db']->getAll($sql);
		$temp_arr = array ();
		foreach ($extend_info_arr AS $val) {
			$temp_arr[$val['reg_field_id']] = $val['content'];
		}

		foreach ($extend_info_list AS $key => $val) {
			switch ($val['id']) {
				case 1 :
					$extend_info_list[$key]['content'] = $user_info['msn'];
					break;
				case 2 :
					$extend_info_list[$key]['content'] = $user_info['qq'];
					break;
				case 3 :
					$extend_info_list[$key]['content'] = $user_info['office_phone'];
					break;
				case 4 :
					$extend_info_list[$key]['content'] = $user_info['home_phone'];
					break;
				case 5 :
					$extend_info_list[$key]['content'] = $user_info['mobile_phone'];
					break;
				default :
					$extend_info_list[$key]['content'] = empty ($temp_arr[$val['id']]) ? '' : $temp_arr[$val['id']];
			}
		}

		$arr['extend_info_list'] = $extend_info_list;

		//start add by wangcya,20141117
		if ($user_info['check_status'] == CHECKED_CHECK_STATUS) {
			//如果已经审核通过，通过ID获取地区名称显示
			$Province_my_id = intval($user_info['Province']);

			$sql = "SELECT region_name FROM " . $GLOBALS['ecs']->table('region') . " WHERE parent_id = 1 AND region_id = '$Province_my_id'"; //china
			$province_name_my = $GLOBALS['db']->getOne($sql);
			$arr['province_name_my'] = $province_name_my;

			$city_my_id = intval($user_info['city']);
			$sql = "SELECT region_name FROM " . $GLOBALS['ecs']->table('region') . " WHERE parent_id = '$user_info[Province]' AND region_id = '$city_my_id'"; //china
			$city_name_my = $GLOBALS['db']->getOne($sql);
			$arr['city_name_my'] = $city_name_my;
			
			if($user_info['district'])
			{
				$sql = "SELECT region_name FROM " . $GLOBALS['ecs']->table('region') . " WHERE region_id = '$user_info[district]'"; //china
				$district_name_my = $GLOBALS['db']->getOne($sql);
				$arr['district_name_my'] = $district_name_my;
				
			}

		}
		//end add by wangcya,20141117
		else {}
		
		$region_list = get_region_list($user_info['Province'],$user_info['city']);
		if(isset($region_list['city_list']))
		{
			$arr['city_list'] = $region_list['city_list'];
		}
		
		if(isset($region_list['district_list']))
		{
			$arr['district_list'] = $region_list['district_list'];
		}
		$arr['province_list'] = $region_list['province_list'];

		 //end

		return $arr;

	}

	private $is_weixin;
	public function editProfile() {
		$this->is_weixin = isset ($_POST['is_weixin']) ? trim($_POST['is_weixin']) : '';
		$user_id = $_SESSION['user_id'];
		$user_data['real_name'] = $real_name = empty ($_POST['real_name']) ? '' : $_POST['real_name'];
		$user_data['email'] = $email = isset ($_POST['email']) ? trim($_POST['email']) : '';
		$user_data['sex'] = isset ($_POST['sex']) ? trim($_POST['sex']) : '';
		$user_data['birthday'] = isset ($_POST['birthday']) ? trim($_POST['birthday']) : '';
		$user_data['last_update_time'] = time();
		
		if (!$user_data['birthday']) {
			$user_data['birthday'] = $_REQUEST['birthdayYear'] . '-' . $_REQUEST['birthdayMonth'] . '-' . $_REQUEST['birthdayDay'];
		}
		
		$user_data['mobile_phone'] = isset ($_POST['extend_field5']) ? trim($_POST['extend_field5']) : '';
		if(!$user_data['mobile_phone'])
		{
			$user_data['mobile_phone']  = isset ($_POST['mobile_phone']) ? trim($_POST['mobile_phone']) : '';
		}
		
		
		//user_info表的信息	
		//证件类型
		$user_info['CertificatesType'] = isset ($_POST['CertificatesType']) ? trim($_POST['CertificatesType']) : 1;
		$user_info['CardId'] = isset ($_POST['CardId']) ? trim($_POST['CardId']) : '';

		//证书类型
		$user_info['Category'] = isset ($_POST['Category']) ? trim($_POST['Category']) : '';
		//证书编号
		$user_info['CertificateNumber'] = isset ($_POST['CertificateNumber']) ? trim($_POST['CertificateNumber']) : '';

		//证书有效期 
		$user_info['certificate_expiration_date'] = isset ($_POST['certificate_expiration_date']) ? trim($_POST['certificate_expiration_date']) : '';
		//add yes12 2014-12-31 对永久有效另外处理
		$certificate_expiration_date_no = isset ($_POST['certificate_expiration_date_no']) ? trim($_POST['certificate_expiration_date_no']) : '';
		if ($certificate_expiration_date_no) {
			$user_info['certificate_expiration_date'] = $certificate_expiration_date_no;
		}

		//modify yes123 2014-12-16 为了验证地区，把这部分代码移动到上面来了
		//所在省份
		$user_info['Province'] = isset ($_POST['province']) ? trim($_POST['province']) : '';
		//所在市/区
		$user_info['city'] = isset ($_POST['city']) ? trim($_POST['city']) : '';
		$user_info['district'] = isset ($_POST['district']) ? trim($_POST['district']) : '';
		//家庭住址
		$user_info['address'] = isset ($_POST['address']) ? trim($_POST['address']) : '';
		//邮政编码
		$user_info['ZoneCode'] = isset ($_POST['ZoneCode']) ? trim($_POST['ZoneCode']) : '';
		
		$user_info['check_status'] = isset ($_POST['check_status']) ? trim($_POST['check_status']) : '';

		//modify yes123 2014-12-01没有通过审核的用户才需要验证 
		if ($_SESSION['check_status']!=CHECKED_CHECK_STATUS) {
			//校验数据格式是否正确
			global $_LANG;
/*			if (!is_email($user_data['email'])) {
				if ($this->is_weixin) {
					die(json_encode(array (
						'code' => 1,
						'msg' => $_LANG['msg_email_format']
					)));
				} //add yes123 2015-01-06 微信端提示
				show_message($_LANG['msg_email_format']);
			} else {
				$sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('users') . " WHERE email = '$email' AND user_id <> '$user_id'";
				$r = $GLOBALS['db']->getOne($sql);
				if ($r) {
					include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
					EbaAdapter :: responseData('UpdateProfileEmailExist');
					if ($this->is_weixin) {
						die(json_encode(array (
							'code' => 1,
							'msg' => '邮件地址已存在！'
						)));
					} //add yes123 2015-01-06 微信端提示
					show_message('邮件地址已存在！', '', 'user.php?act=profile');
				}

			}*/
/*
			if (!empty ($user_data['mobile_phone']) && !preg_match('/^[\d-\s]+$/', $user_data['mobile_phone'])) {
				if ($this->is_weixin) {
					die(json_encode(array (
						'code' => 1,
						'msg' => $_LANG['passport_js']['mobile_phone_invalid']
					)));
				} //add yes123 2015-01-06 微信端提示
				show_message($_LANG['passport_js']['mobile_phone_invalid']);
			}*/

/*			if (empty ($user_data['mobile_phone'])) {

				if ($this->is_weixin) {
					die(json_encode(array (
						'code' => 1,
						'msg' => '手机为必填项！'
					)));
				} //add yes123 2015-01-06 微信端提示	

				show_message('手机为必填项！', '', 'user.php?act=profile');
				exit;
			} else {
				$sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('users') . " WHERE (mobile_phone = '$user_data[mobile_phone]' OR user_name='$user_data[mobile_phone]') AND user_id <> '$user_id'";
				ss_log("检测手机号码是否存在：".$sql);
				$r = $GLOBALS['db']->getOne($sql);
				if ($r) {
					if ($this->is_weixin) {
						die(json_encode(array (
							'code' => 1,
							'msg' => '手机号码已存在！'
						)));
					} //add yes123 2015-01-06 微信端提示
					show_message('手机号码已存在！', '', 'user.php?act=profile');
				}
			}*/

			if (!$user_data['real_name']) {
				if ($this->is_weixin) {
					die(json_encode(array (
						'code' => 1,
						'msg' => '真实姓名为必填项！'
					)));
				} //add yes123 2015-01-06 微信端提示
				show_message('真实姓名为必填项！', '', 'user.php?act=profile');
			}

			//证件编号
			if (empty ($user_info['CardId'])) {
				if ($this->is_weixin) {
					die(json_encode(array (
						'code' => 1,
						'msg' => '身份证号码不能为空！'
					)));
				} //add yes123 2015-01-06 微信端提示
				show_message('证件号码不能为空！', '', 'user.php?act=profile');
			} else {
				$sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('user_info') .
						"  WHERE CardId = '$user_info[CardId]' AND CertificatesType = '$user_info[CertificatesType]' AND uid <> '$user_id' AND uid!=0 ";
				$r = $GLOBALS['db']->getOne($sql);
				if ($r > 0) {
					//add by dingchaoyang 2014-11-10
					//响应json数据到客户端
					include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
					EbaAdapter :: responseData('UpdateProfileIDExist');
					//end add by dingchaoyang 2014-11-10
					if ($this->is_weixin) {
						die(json_encode(array (
							'code' => 1,
							'msg' => '身份证号码已存在！'
						)));
					} //add yes123 2015-01-06 微信端提示
					show_message('身份证号码已存在！', '', 'user.php?act=profile');
				}

			}
/*           del yes123 2015-03-25 前台不提交，后台不校验
			if (empty ($user_info['CertificateNumber'])) {
				if ($this->is_weixin) {
					die(json_encode(array (
						'code' => 1,
						'msg' => '资格证书编号不能为空！'
					)));
				} //add yes123 2015-01-06 微信端提示
				show_message('资格证书编号不能为空！', '', 'user.php?act=profile');
			}
			elseif (strlen($user_info['certificate_expiration_date']) < 4) {
				if ($this->is_weixin) {
					die(json_encode(array (
						'code' => 1,
						'msg' => '资格证格式不对！'
					)));
				} //add yes123 2015-01-06 微信端提示
				
				//add by dingchaoyang 2015-1-31
				//如果来自移动端，不判断。当前版本没这个字段
				include_once (ROOT_PATH . 'api/EBaoApp/platformEnvironment.class.php');
				if (!(PlatformEnvironment::isMobilePlatform())){
					show_message('资格证格式不对！', '', 'user.php?act=profile');
				}
			} else {
				$sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('user_info') .
				 " WHERE CertificateNumber = '$user_info[CertificateNumber]' AND Category='$user_info[Category]' AND uid <> '$user_id' AND uid!=0";
				$r = $GLOBALS['db']->getOne($sql);
				if ($r > 0) {
					//add by dingchaoyang 2014-11-10
					//响应json数据到客户端
					include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
					EbaAdapter :: responseData('UpdateProfileNumExist');
					//end add by dingchaoyang 2014-11-10
					if ($this->is_weixin) {
						die(json_encode(array (
							'code' => 1,
							'msg' => '资格证书编号已存在！'
						)));
					} //add yes123 2015-01-06 微信端提示
					show_message('资格证书编号已存在！', '', 'user.php?act=profile');
				}
			}*/

/*	
			if (!$user_info['Province']) {
				if ($this->is_weixin) {
					die(json_encode(array (
						'code' => 1,
						'msg' => '省份不能为空！'
					)));
				} //add yes123 2015-01-06 微信端提示
				show_message('省份不能为空！', '', 'user.php?act=profile');
			}

			if (!$user_info['city']) {
				if ($this->is_weixin) {
					die(json_encode(array (
						'code' => 1,
						'msg' => '城市不能为空！'
					)));
				} //add yes123 2015-01-06 微信端提示
				show_message('城市不能为空！', '', 'user.php?act=profile');
			}

			if (!$user_info['address']) {
				if ($this->is_weixin) {
					die(json_encode(array (
						'code' => 1,
						'msg' => '详细地址不能为空！'
					)));
				} //add yes123 2015-01-06 微信端提示
				show_message('详细地址不能为空！', '', 'user.php?act=profile');
			}*/

		}
		
		if($user_data['mobile_phone'])
		{
			$user_data['user_name'] = $user_data['mobile_phone'];
		}
		
		
		//更新bx_users表
		$this->editUsersTable($user_id,$user_data);
		//保存照片
		$this->saveImgs($user_id);
		//申请审核
		if ($user_info['check_status']==PENDING_CHECK_STATUS) {
			$this->applyCheck($user_id);
		}
		//更新user_info表
		$r = $this->editUserInfoTable($user_id, $user_info);
		return $r;

	}

	//保存照片
	private function saveImgs($user_id) {
		$avatar = isset ($_POST['avatar']) ? trim($_POST['avatar']) : '';
		$avatar = strstr($avatar, 'images'); //add by dingchaoyang 适配app.返回给app时加了‘/’
		$img_type1 = isset ($_POST['img_type1']) ? trim($_POST['img_type1']) : '';
		$img_type1 = strstr($img_type1, 'images'); //add by dingchaoyang 适配app.返回给app时加了‘/’
		$img_type2 = isset ($_POST['img_type2']) ? trim($_POST['img_type2']) : '';
		$img_type2 = strstr($img_type2, 'images'); //add by dingchaoyang 适配app.返回给app时加了‘/’
		$img_type3 = isset ($_POST['img_type3']) ? trim($_POST['img_type3']) : '';
		$img_type3 = strstr($img_type3, 'images'); //add by dingchaoyang 适配app.返回给app时加了‘/’
		if ($avatar) {
			$sql = "UPDATE " . $GLOBALS['ecs']->table('user_info') . "SET avatar = '" . $avatar . "' WHERE uid= '" . $user_id . "'";
			$GLOBALS['db']->query($sql);
			ss_log($sql);
		}

		if ($img_type1) {
			$sql = "UPDATE " . $GLOBALS['ecs']->table('user_info') . "SET card_img1 = '" . $img_type1 . "' WHERE uid= '" . $user_id . "'";
			$GLOBALS['db']->query($sql);
			ss_log($sql);
		}

		if ($img_type2) {
			$sql = "UPDATE " . $GLOBALS['ecs']->table('user_info') . "SET card_img2 = '" . $img_type2 . "' WHERE uid= '" . $user_id . "'";
			$GLOBALS['db']->query($sql);
			ss_log($sql);
		}

		if ($img_type3) {
			$sql = "UPDATE " . $GLOBALS['ecs']->table('user_info') . "SET certificate_img = '" . $img_type3 . "' WHERE uid= '" . $user_id . "'";
			$GLOBALS['db']->query($sql);
			ss_log($sql);
		}

	}
	//编辑扩展字段
	public function editUserInfoTable($user_id, $user_info) {
		
		$sql = "SELECT uid FROM " . $GLOBALS['ecs']->table('user_info') . "WHERE uid=" . $user_id;
		
		//如果没有记录则插入  存在则更新
		if ($GLOBALS['db']->getOne($sql)) 
		{
			$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_info'), $user_info, "UPDATE", "uid = $user_id");  
		} 
		else 
		{
			$user_info['uid']=$user_id;
			$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_info'), $user_info, "INSERT");  
		}
		
		return $r;
	}

	//更新bx_users表
	private function editUsersTable($user_id,$user_data) {
		global $_LANG;
		
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_id = '$user_id'";
		$user = $GLOBALS['db']->getRow($sql);
		
		//add yes123 2015-03-26  判断如果邮箱地址有变，则修改为未验证邮箱
		if($user['email']!=$user['email'])
		{
			$user_data['is_validated']=0;
		}
		
		$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('users'), $user_data, "UPDATE", "user_id = $user_id");  
		
		
		
		
		if ($r) {
			return $r;
		} else {
			//add by dingchaoyang 2014-11-10
			//响应json数据到客户端
			include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
			EbaAdapter :: responseData('UpdateProfileFail');
			//end add by dingchaoyang 2014-11-10
			if ($this->is_weixin) {
				die(json_encode(array (
					'code' => 1,
					'msg' => $_LANG['edit_profile_failed']
				)));
			} //add yes123 2015-01-06 微信端提示
			$msg = $_LANG['edit_profile_failed'];
			show_message($msg, '', '', 'info');
		}
	}


	//标志申请审核
	private function applyCheck($user_id) {
		$sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET check_status = '".PENDING_CHECK_STATUS."'  WHERE user_id=" . $user_id;
		$r = $GLOBALS['db']->query($sql);
		
		//add yes123 2015-01-29 提交审核后，给管理员发送短信
		//include_once(ROOT_PATH.'includes/hongdong_function.php');
		//notification_admin($user_id);
		return $r;
	}

	//end 编辑个人资料
	
	
	public function editPassword(){
		$user_id = $_SESSION['user_id'];
		global $_LANG,$_CFG,$user;
		$is_weixin = isset($_POST['is_weixin']) ? trim($_POST['is_weixin']) : 0;
		$is_client = isset($_POST['is_client']) ? trim($_POST['is_client']) : 0;
		
	    $old_password = isset($_POST['old_password']) ? trim($_POST['old_password']) : null;
	    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
	    $user_id      = isset($_POST['uid'])  ? intval($_POST['uid']) : $user_id;
	    $code         = isset($_POST['code']) ? trim($_POST['code'])  : '';
		
		if (strlen($new_password) < 6)
	    {
	    	$msg = $_LANG['passport_js']['password_shorter'];
	    	//add yes123 2015-01-21 微信提示
	    	if($is_weixin){die( json_encode(array('code'=>1,'msg'=>$msg)));}
	        show_message($_LANG['passport_js']['password_shorter']);
	    }
	     
	     if($is_client)
	     {
	     	$user->user_table="users_c";
	     }
	     	
	     $user_info = $user->get_profile_by_id($user_id);
	     if (($user_info && (!empty($code) && md5($user_info['user_id'] . $_CFG['hash_code'] . $user_info['reg_time']) == $code)) || ($_SESSION['user_id']>0 && $_SESSION['user_id'] == $user_id && $user->check_user($_SESSION['user_name'], $old_password)))
		 {		
		 		
					
		        if ($user->edit_user(array('username'=> (empty($code) ? $_SESSION['user_name'] : $user_info['user_name']), 'old_password'=>$old_password, 'password'=>$new_password), empty($code) ? 0 : 1))
		        {
					//$sql="UPDATE ".$GLOBALS['ecs']->table($user->user_table). "SET `ec_salt`='0' WHERE user_id= '".$user_id."'";
					//modify yes123 2015-03-20 修改密码后，user_source置0
					$sql="UPDATE ".$GLOBALS['ecs']->table($user->user_table). "SET ec_salt=0,user_source=0 WHERE user_id= '".$user_id."'";
					$GLOBALS['db']->query($sql);
		            $user->logout();
		            //add by dingchaoyang 2014-12-2
		            //响应json数据到客户端
		            include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
		            EbaAdapter::responseData('Eba_ModifyPasswordSuccess');
		            //end by dingchaoyang 2014-12-2
		            
		            //add yes123 2015-01-21 微信提示
		            if($is_weixin){
		            	//微信修改密码，必须把openid清空，让用户重新登录
		            	$sql = 'UPDATE ' . $GLOBALS['ecs']->table($user->user_table) . ' SET openid=0 WHERE user_id='.$user_id;   
						$GLOBALS['db']->query($sql);
		            	die( json_encode(array('code'=>0,'msg'=>$_LANG['edit_password_success'])));
		            }
		            show_message($_LANG['edit_password_success'], $_LANG['relogin_lnk'], 'user.php?act=login', 'info');
		        }
		        else
		        {
		        	//add by dingchaoyang 2014-12-2
		        	//响应json数据到客户端
		        	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
		        	EbaAdapter::responseData('Eba_ModifyPasswordFail');
		        	//end by dingchaoyang 2014-12-2
		        	
		        	//add yes123 2015-01-21 微信提示
		        	if($is_weixin){die( json_encode(array('code'=>1,'msg'=>$_LANG['edit_password_failure'])));}
		        	show_message($_LANG['edit_password_failure'], $_LANG['back_page_up'], '', 'info');
		        }
		  }
		  else
		  {
		    	//add by dingchaoyang 2014-12-2
		    	//响应json数据到客户端
		    	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
		    	EbaAdapter::responseData('Eba_ModifyPasswordFail');
		    	//end by dingchaoyang 2014-12-2
		    	//add yes123 2015-01-21 微信提示
		    	if($is_weixin){die( json_encode(array('code'=>1,'msg'=>$_LANG['edit_password_failure'])));}
		    	show_message($_LANG['edit_password_failure'], $_LANG['back_page_up'], '', 'info');
		  }
			
		
	}
	//短信动态验证码 提现, 转账
	public function withdrawals() {
		$num = rand(1000, 9999);
		$user_id = $_SESSION['user_id'];
		$judge = $_REQUEST['judge'];
		$money = $_REQUEST['money'];
		$send_type=VERIFICATION_CODE_SMS;
		//获取手机号码，发短信用
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_id = " . $user_id;
		$user = $GLOBALS['db']->getRow($sql);
		$mobile = $user['mobile_phone'];

		//缓存手机号码和验证码用于提交时对比验证
		$phone_code['username'] = $mobile;
		$phone_code['code'] = $num;
		$_SESSION['phone_code'] = $phone_code;

		global $_CFG;
		$sdk_sn='SDK-GHD-010-00014';
		$sdk_pwd='014355';
		if ($judge == 2) {
		    //检测是否有为处理的提现记录，如果有，不让用户重复发
		    $sql = "SELECT id FROM " .$GLOBALS['ecs']->table('user_account')." WHERE user_id=".$_SESSION['user_id'] ." AND is_paid=0 AND process_type=1 ";
		    ss_log('查询是否有为完成的提申请：'.$sql);
			$user_account_id = $GLOBALS['db']->getOne($sql);
			if($user_account_id){
				$datas = json_encode(array (
					'data' => 11,
					'msg' => '还有未处理的提现申请，请勿重复提交!'
				));
				die($datas);
			}

			$content = "您本次提现的动态验证码为" . $num;
			$send_type = WITHDRAWALS_VERIFICATION_CODE_SMS;
		}
		elseif ($judge == 3) {
			$payee_name = isset ($_REQUEST['payee_name']) ? $_REQUEST['payee_name'] : "";

			//检查收款人是否存在
			$sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_name='" . $payee_name . "'";
			$target_user = $GLOBALS['db']->getOne($sql);
			if (!$target_user) {
				$datas = json_encode(array (
					'data' => 10,
					'msg' => '收款人不存在，请检查！'
				));
				die($datas);
			}

			//检查收款人是否为自己
			if ($user_id == $target_user) {
				$datas = json_encode(array (
					'data' => 10,
					'msg' => '不能给自己转账！'
				));
				die($datas);
			}

			//add yes123 2014-12-19   验证
			if ($money > $user['user_money']) {
				$datas = json_encode(array (
					'data' => 10,
					'msg' => '转账金额不能大于余额！'
				));
				die($datas);
			}

			$content = "您本次 转账的动态验证码为" . $num;
			$send_type = TRANSFER_VERIFICATION_CODE_SMS;
		}

		//$result = gxmt_post($sdk_sn, $sdk_pwd, $mobile, $content);
		$result = send_msg($mobile,$content,1,$send_type,$num);
		//add yes123 2014-12-15 如果为-4欠费
		if ($result == -4) {
			writeLog();
		}
		ss_log('result:' . $result . ",num:" . $result);
		return array (
			'result' => $result,
			'num' => $num
		);
	}

	//提现
	public function withdrawMoney($surplus){
	  	
	  $weixin = isset($_POST['weixin']) ? $_POST['weixin'] : 0;	
	  $bid = isset($_POST['bid']) ? $_POST['bid'] : 0;
	  $user_id = $_SESSION['user_id'];
      $amount = $surplus['amount'];
      $checkCode = isset($_POST['checkCode']) ? $_POST['checkCode'] : 0;	
      $username = isset($_POST['username']) ? $_POST['username'] : 0;
      
      
      global $_LANG;
      
      //add yes123 2014-12-04 如果银行ID为空,提示用户
       if(!$bid){
       	 //add yes123 2015-01-05 微信的提现
       	   if($weixin){
       	   	 die( json_encode(array('code'=>1,'msg'=>'请选择提现银行！')));
       	   }
	       show_message('请选择提现银行！');
       }
		
		//add by dingchaoyang 2014-12-19,
		include_once (ROOT_PATH . 'api/EBaoApp/platformEnvironment.class.php');
		if (!(PlatformEnvironment::isMobilePlatform())){
			//add  yes123 2014-11-28 验证用户注册信息 start
			$phone_code = isset($_SESSION['phone_code'])?$_SESSION['phone_code']:0;
			if($phone_code)
			{
				//验证用户填写的验证码和服务端发送的是否一致
				if($phone_code['code']!=$checkCode)
				{
					 //add yes123 2015-01-05 微信的提现
					 if($weixin){
					 	die( json_encode(array('code'=>1,'msg'=>'短信动态验证码有误！')));
					 }
					show_message('短信动态验证码有误！', '', 'user.php?act=account_raply');
					//用完后清空
					$_SESSION['phone_code']=null;
					exit;
				}
				
			}
			else
			{
				ss_log("error: withdraw deposit  SESSION[phone_code] is null ");
				 //add yes123 2015-01-05 微信的提现
				if($weixin){
					die( json_encode(array('code'=>1,'msg'=>'抱歉,出现未知错误,请重新获取验证码 !')));
				}		
				show_message('抱歉,出现未知错误,请重新获取验证码 !', '', 'user.php?act=account_raply');exit;
			}
			
			
			
		   //提现校验
		   $res = $this->withdrawMoneyCheck($surplus,$user_id);
		   if($res['code']==1)
		   {
		   		if($weixin){
					die( json_encode(array('code'=>1,'msg'=>$res['msg'])));
				}
	            show_message($res['msg'], $_LANG['back_page_up'], '', 'info');
		   }
	
		}
		
		
		$res = $this->countWithdrawRate($surplus);
		
        //插入会员账目明细
        $amount = '-'.$res['actual_money'];
        $surplus['payment'] = '';
        $surplus['rec_id']  = insert_user_account($surplus,$amount);
        
		
		//扣除实际提现金额
		$id=0;
		if($res['actual_money']>0)
		{
			$amount = "-".$res['actual_money'];
			$id = log_account_change($user_id, $amount,
                                0, 0, 0, $_LANG['surplus_type_1'],
                                ACT_DRAWING,'','','',0,
                                $surplus['money_type'],$surplus['money_type']);
		}
		
		if($id)
		{
			$admin_note = '';
			//扣除手续费
			if($res['withdraw_poundage']>0)
			{
				$amount = "-".$res['withdraw_poundage'];
				log_account_change($user_id, $amount,
				                0, 0, 0, $_LANG['surplus_type_1'].",扣除手续费",
				                ACT_DRAWING,'','','',0,
				                'withdraw_poundage',$surplus['money_type']);
				
				$admin_note.=' 手续费：'.$res['withdraw_poundage']."\n";
			}
			
			//扣除个人所得税
			if($res['income_tax']>0)
			{
				$amount = "-".$res['income_tax'];
				log_account_change($user_id, $amount,
		                        0, 0, 0, $_LANG['surplus_type_1'].",扣除个人所得税",
		                        ACT_DRAWING,'','','',0,
		                        'income_tax',$surplus['money_type']);
		        
				$admin_note.=' 个人所得税：'.$res['income_tax']."\n";;
			}
			
			
			//
			if($admin_note && $surplus['rec_id'])
			{
				$admin_note.=' 实际应到账金额：'.$res['actual_money'];
				
				$sql = " UPDATE bx_user_account SET admin_note='$admin_note' WHERE id='$surplus[rec_id]'";
				ss_log('更新提现说明：'.$sql);
				$GLOBALS['db']->query($sql);
			}
		
		
		}
		
		ss_log(__FUNCTION__.',amount:'.$amount);


		
		
        /* 如果成功提交 */
        if ($surplus['rec_id'] > 0)
        {
        	//add by dingchaoyang 2014-12-19
        	//响应json数据到客户端
        	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
        	EbaAdapter::responseData('Eba_WithdrawCashSuccess');
        	//end add by dingchaoyang 2014-12-19
        	$content = $_LANG['surplus_appl_submit'];
        	 //add yes123 2015-01-05 微信的提现
        	if($weixin){
				die( json_encode(array('code'=>0,'msg'=>$content)));
			}
            show_message($content, $_LANG['back_account_log'], 'user.php?act=account_log&process_type=1', 'info');
        }
        else
        {
        	//add by dingchaoyang 2014-12-19
        	//响应json数据到客户端
        	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
        	EbaAdapter::responseData('Eba_WithdrawCashFail');
        	//end add by dingchaoyang 2014-12-19
        	$content = $_LANG['process_false'];
        	 //add yes123 2015-01-05 微信的提现
        	if($weixin){
				die( json_encode(array('code'=>1,'msg'=>$content)));
			}
            show_message($content, $_LANG['back_page_up'], '', 'info');
        }
    
	}
	
	
	private function withdrawMoneyCheck($surplus,$user_id){
		global $money_type_arr;
		
		$money_type = $surplus['money_type'];
		$res = array();
		$res['code']=0;
		

		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('withdraw_rule_cfg')." WHERE money_type='$money_type'";
		ss_log(__FUNCTION__.",get withdraw_rule_cfg sql:".$sql);
		$withdraw_rule_cfg = $GLOBALS['db']->getRow($sql);
		
		      
      //0.检测是否有为处理的提现记录，如果有，不让用户重复发
		$sql = "SELECT id FROM " .$GLOBALS['ecs']->table('user_account')." WHERE user_id='$user_id' AND is_paid=0 AND process_type=1 ";
		ss_log('查询是否有为完成的提申请：'.$sql);
		$user_account_id = $GLOBALS['db']->getOne($sql);
		if($user_account_id){
			$res['code']=1;
			$res['msg']='还有未处理的提现申请，请勿重复提交！';
			return $res;	
		}
		
		
		//1.检测可提现金额是否足够
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('users')." WHERE user_id='$user_id'";
		 ss_log(__FUNCTION__.",查询账户余额 sql:".$sql);
		$user = $GLOBALS['db']->getRow($sql);
		if($user['check_status']!=CHECKED_CHECK_STATUS)
		{
			$res['code']=1;
			$res['msg']="非正式会员，不能提现！";
			return $res;	
			
		}
		
		
		//检测提现金额是否在可提现范围内
		if($withdraw_rule_cfg['min_withdraw_money']>$surplus['amount'])
		{
			$res['code']=1;
			$res['msg']="最小提现金额为:".$withdraw_rule_cfg['min_withdraw_money'];
			return $res;	
		}
		//检测提现金额是否在可提现范围内
		if($withdraw_rule_cfg['max_withdraw_money']<$surplus['amount'])
		{
			$res['code']=1;
			$res['msg']="最大提现金额为:".$withdraw_rule_cfg['max_withdraw_money'];
			return $res;	
		}
		
		
		//判断服务费账户和奖励账户是否有负数,如果有负数，那么和充值账户相加再判断是否有足够余额提现
		if($money_type=='user_money')
		{
			if($user['service_money']<0)
			{
				$user['user_money']+=$user['service_money'];
			}
			if($user['award_money']<0)
			{
				$user['user_money']+=$user['award_money'];
			}
		}

		
		if($user[$money_type]<$surplus['amount'])
		{
			$res['code']=1;
			$res['msg']=$money_type_arr[$withdraw_rule_cfg['money_type']]." 可提现金额不足，请检查！";
			return $res;
		}
		
		
		//2，确认是否可以提现
		if($withdraw_rule_cfg['is_permit_withdraw']=='n')
		{
			$res['code']=1;
			$res['msg']=$money_type_arr[$withdraw_rule_cfg['money_type']]."账户不允许提现！";
			return $res;
		}
		
		//3,查询提现次数是否已满
		$end_time = time();//截至日期
		$start_time = date("Y-m",$end_time)."-01"; //开始日期
		$start_time = strtotime($start_time); 
		$sql = "SELECT COUNT(id) FROM " . $GLOBALS['ecs']->table('user_account')." WHERE user_id='$user_id' AND amount<0  " .
        	   " AND amount_type ='$money_type' AND add_time BETWEEN '$start_time' and '$end_time'";
        ss_log(__FUNCTION__.",查询当月已提现次数 sql:".$sql);
		$withdraw_num = $GLOBALS['db']->getOne($sql);
		if($withdraw_rule_cfg['month_withdraw_num']<=$withdraw_num)
		{
			$res['code']=1;
			$res['msg']=$money_type_arr[$withdraw_rule_cfg['money_type']]."提现次数已满，请次月操作！！";
			return $res;
			
		}
		
		return $res;

		
	}
		
	//充值
	public function rechargeMoney($surplus){
		$weixin = isset($_POST['weixin']) ? $_POST['weixin'] : 0;	
		global $_LANG;
		$amount = $surplus['amount'];
    	//add yes123 2014-12-12 通过支code获取支付id
    	if($surplus['pay_code'])
    	{
			$sql = "SELECT pay_id FROM " . $GLOBALS['ecs']->table('payment') .
			" WHERE pay_code = '" .$surplus['pay_code'] . "'";
			$pay_id = $GLOBALS['db']->getOne($sql);    	
			$surplus['payment_id']=$pay_id;
    	}
   
    	 //add yes123 2014-12-12 检查是否上传支付凭证
    	if($surplus['pay_code']=='bank')
    	{
	    	if(!$surplus['pay_document'])
	        {
	        	$content = $_LANG['upload_pay_doc'];
	        	if($weixin){
					die( json_encode(array('code'=>1,'msg'=>$content)));
				}
	            show_message($content);
	        }
    	}

    	
        if (!$surplus['pay_code'])
        {
        	$content = $_LANG['select_payment_pls'];
	        if($weixin){
				die( json_encode(array('code'=>1,'msg'=>$content)));
			}
            show_message($content);
        }

        include_once(ROOT_PATH .'includes/lib_payment.php');

        //获取支付方式名称
        $payment_info = array();
        $payment_info = payment_info($surplus['payment_id']);
        $surplus['payment'] = $payment_info['pay_name'];

        if ($surplus['rec_id'] > 0)
        {
            //更新会员账目明细
            $surplus['rec_id'] = update_user_account($surplus);
        }
        else
        {
            //插入会员账目明细
            $surplus['rec_id'] = insert_user_account($surplus, $amount);
        }

        //取得支付信息，生成支付代码
        $payment = unserialize_config($payment_info['pay_config']);

        //生成伪订单号, 不足的时候补0
        $order = array();
        $order['order_sn']       = $surplus['rec_id'];
        $order['user_name']      = $_SESSION['user_name'];
        $order['surplus_amount'] = $amount;

        //计算支付手续费用
        $payment_info['pay_fee'] = pay_fee($surplus['payment_id'], $order['surplus_amount'], 0);

        //计算此次预付款需要支付的总金额
        $order['order_amount']   = $amount + $payment_info['pay_fee'];

        //记录支付log
        $order['log_id'] = insert_pay_log($surplus['rec_id'], $order['order_amount'], $type=PAY_SURPLUS, 0);
        
        /* 调用相应的支付方式文件 */
        include_once(ROOT_PATH . 'includes/modules/payment/' . $payment_info['pay_code'] . '.php');

        /* 取得在线支付方式的支付按钮 */
        $pay_obj = new $payment_info['pay_code'];
        $payment_info['pay_button'] = $pay_obj->get_code($order, $payment);
		return array('order'=>$order,'payment'=>$payment_info,'amount'=>$amount);
	}
	
	//转账处理
	public function transferAccountsUpdate(){
		$weixin = isset($_POST['weixin']) ? $_POST['weixin'] : 0;
		$checkCode = $_POST['checkCode'];
		$phone_code = $_SESSION['phone_code'];
		if($phone_code)
		{
			//验证用户填写的验证码和服务端发送的是否一致
			if($phone_code['code']!=$checkCode)
			{
				$content='短信动态验证码有误！';
				if($weixin){
					die( json_encode(array('code'=>1,'msg'=>$content)));
				}
				show_message($content, '', 'user.php?act=account_raply');
				//用完后清空
				$_SESSION['phone_code']=null;
				exit;
			}
				
		}
		else
		{
			ss_log("error: transfer_accounts_update  SESSION[phone_code] is null ");
			$content='抱歉,出现未知错误,请重新获取验证码 !';
			if($weixin){
				die( json_encode(array('code'=>1,'msg'=>$content)));
			}		
			show_message($content, '', 'user.php?act=transfer_accounts');exit;
		}
		
		//获取当前用户可用余额
		$sql = "SELECT  user_name,user_id,user_money FROM " .$GLOBALS['ecs']->table('users')." WHERE user_id=".$_SESSION['user_id'];
		$current_user = $GLOBALS['db']->getRow($sql);
		
		//获取用户输入的转账金额
		$transfer_money=trim($_REQUEST['transfer_money']);
		
		//判断源和目标账户不能相同
		if($current_user['user_name']==trim($_REQUEST['payee_name'])){
			
			if($weixin){
				die( json_encode(array('code'=>1,'msg'=>'不能给自己转账!')));
			}
			
			echo "<script>alert('不能给自己转账！');history.go(-1); </script>";
			exit;
		}
		
		if($transfer_money>0){
			if($current_user['user_money']>=$transfer_money){
				//给要充值的账户增加余额
				$sql = "SELECT user_name,user_id,user_money FROM " .$GLOBALS['ecs']->table('users')." WHERE user_name='".trim($_REQUEST['payee_name'])."'";
				$target_user = $GLOBALS['db']->getRow($sql);
				if($target_user!=''){
					global $_LANG;
					$desc=trim($_REQUEST['desc'])!=''?",附加说明:".trim($_REQUEST['desc']):'';
					log_account_change($target_user['user_id'],$transfer_money,0,0,0,$_LANG['transfer_accounts'].",来源用户：".$current_user['user_name'].$desc);	
					log_account_change($current_user['user_id'],"-".$transfer_money,0,0,0,$_LANG['transfer_accounts'].",目标用户：".$target_user['user_name'].$desc);	
					
					if($weixin){
						die( json_encode(array('code'=>0,'msg'=>'转账成功!')));
					}	
					echo "<script>alert('转账成功');window.location.href='user.php?act=account_detail'; </script>";
				}else{
					if($weixin){
						die( json_encode(array('code'=>1,'msg'=>'用户名不存在，请更换')));
					}	
					echo "<script>alert('用户名不存在，请更换');history.go(-1); </script>";
				}
				
			}

	   }	
		
	}
	
	// 收益明细
	public function getAccountIncome(){
		
		global $income_type_list;
		$user_id = $_SESSION['user_id'];
		$condition=array();
		$condition['act']="account_income";
		$condition['user_name'] = isset ($_REQUEST['user_name']) ? trim($_REQUEST['user_name']) : "";
		$condition['order_sn'] = isset ($_REQUEST['order_sn']) ? trim($_REQUEST['order_sn']) : "";
		$condition['incoming_type'] = isset ($_REQUEST['incoming_type']) ? trim($_REQUEST['incoming_type']) : "";
		$condition['closed_account'] = isset ($_REQUEST['closed_account']) ? intval($_REQUEST['closed_account']) : -1;
		$condition['s_change_time'] = isset ($_REQUEST['s_change_time']) ? $_REQUEST['s_change_time'] : "";
		$condition['e_change_time'] = isset ($_REQUEST['e_change_time']) ? $_REQUEST['e_change_time'] : "";
		
		$where=" WHERE user_money<>0 AND user_id = '".$user_id."' AND 
				(incoming_type='".ORDER_SERVICE_MONEY."' OR incoming_type = '".ORGAN_SERVICE_MONEY."' OR incoming_type='".RECOMMEND_SERVICE_MONEY."') ";
		
		if($condition['user_name']!=''){
	    	$sql="SELECT user_id FROM " . $GLOBALS['ecs']->table('users') ."  WHERE user_name LIKE '%".$condition['user_name']."'";
	    	$user_id_list = $GLOBALS['db']->getAll($sql);
	    	if($user_id_list)
	    	{
	    		include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
	    		$user_id_str = CommonUtils::arrToStr($user_id_list,"user_id");
	    		$where .= " AND cname IN ('".$user_id_str."')";
	    	}
	    	
	    }
	    
	    if($condition['order_sn']!=''){
    			$where.=" AND order_sn='".$condition['order_sn']."'";
	    }
	    
	    if($condition['incoming_type']){
	    	
	    	if($condition['incoming_type']==-1)
	    	{
	    		$where.=" AND user_money <0 ";
	    		
	    	}
	    	else
	    	{
	    		$where.=" AND incoming_type='".$condition['incoming_type']."'";
	    	}
	    }
	    
	    if($condition['s_change_time']){
	    		$where.=" AND change_time >='".strtotime($condition['s_change_time'])."'";
	    }
	    if($condition['e_change_time']){
	    		$where.=" AND change_time <='".strtotime($condition['e_change_time'])."'";
	    }

	    if($condition['closed_account']!='-1')
	    {
	    	$where.=" AND closed_account=$condition[closed_account] ";
	 
	    }
	    
	    $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
	    
	    //总记录数
	   	$record_count_sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('account_log').$where;

	    //总收入       
	    $shouru_money_total_sql = "SELECT sum(user_money) FROM " .$GLOBALS['ecs']->table('account_log'). $where;
	    //获取余额
	   	$surplus_amount_sql = "SELECT user_money FROM ".$GLOBALS['ecs']->table('users')." WHERE user_id=".$user_id;
	    
	    $surplus_amount=$GLOBALS['db']->getOne($surplus_amount_sql);
	    $record_count = $GLOBALS['db']->getOne($record_count_sql);
    	$shouru_money_total = $GLOBALS['db']->getOne($shouru_money_total_sql);
	    
	    if (empty($surplus_amount))
	    {
	        $surplus_amount = 0;
	    }
		    
	    //分页函数
    	$pager = get_pager('user.php', $condition, $record_count, $page);
	    
	    //获取余额记录
	    $account_log = array();
	    
	    //数据列表       
	    $res_sql = "SELECT * FROM " . $GLOBALS['ecs']->table('account_log') .$where." ORDER BY log_id DESC";
	    ss_log("资金明细列表：".$res_sql);
	    $res = $GLOBALS['db']->selectLimit($res_sql, $pager['size'], $pager['start']);
	    global $_CFG,$_LANG;
	    while ($row = $GLOBALS['db']->fetchRow($res))
	    {
	    	//通过cname上的ID获取用户名或者真名
	    	$sql = "SELECT user_name,real_name FROM ".$GLOBALS['ecs']->table('users')." WHERE user_id='$row[cname]'";
	    	$user = $GLOBALS['db']->getRow($sql);
	    	
	    	if(count($user)>0)
	    	{
	    		if($user['real_name'])
	    		{
	    			$row['cname']=$user['real_name'];
	    		}else{
	    			$row['cname']=$user['user_name'];
	    		}
	    	}
	        $row['change_time'] = date('Y-m-d H:i:s', $row['change_time']); ;
	        //add yes123 2015-01-17 如果金额小于0,属于退保
	        if($row['user_money']<0)
	        {
	        	$row['amount'] = $row['user_money'];
	        	$row['cname'] = sub_str($row['change_desc'], 60);
	        }
	        else
	        {
	        	$row['amount'] = price_format(abs($row['user_money']), false);
	        	$row['short_change_desc'] = sub_str($row['change_desc'], 60);
	        }
	        
	        
	        $row['incoming_type'] = $income_type_list[$row['incoming_type']];
	        
	        
	        $account_log[] = $row;
	    }
		
		
		return array(
				'account_log'=>$account_log,
				'pager'=>$pager,
				'surplus_amount'=>$surplus_amount,
				'record_count'=>$record_count,
				'shouru_money_total'=>$shouru_money_total,
				'condition'=>$condition,
		);
		
	}
	
	//获取订单列表
	public function getOrderList($is_organ=0){
		$user_id = $_SESSION['user_id'];
		//$condition['end_time'] = isset ($_REQUEST['end_time']) ? $_REQUEST['end_time'] : "";
		$condition=array();
		$condition['act']="order_list";
		$condition['order_sn']=empty($_REQUEST['order_sn']) ? '' : trim($_REQUEST['order_sn']);
		$condition['goods_name']=empty($_REQUEST['goods_name']) ? '' : trim($_REQUEST['goods_name']);
		
		$condition['s_add_time'] = isset ($_REQUEST['s_add_time']) ? $_REQUEST['s_add_time'] : "";
		$condition['e_add_time'] = isset ($_REQUEST['e_add_time']) ? $_REQUEST['e_add_time'] : "";
		
		$condition['order_status'] = isset ($_REQUEST['order_status']) ? $_REQUEST['order_status'] : "";
		$condition['order_by_money'] = isset ($_REQUEST['order_by_money']) ? $_REQUEST['order_by_money'] : "";
		$condition['invoice_id'] = isset ($_REQUEST['invoice_id']) ? $_REQUEST['invoice_id'] : "";
		$condition['pay_id'] = isset ($_REQUEST['pay_id']) ? $_REQUEST['pay_id'] : "";
		
		$condition['need_receipt'] = isset ($_REQUEST['need_receipt']) ? $_REQUEST['need_receipt'] : "";
		$condition['applicant_username'] = isset ($_REQUEST['applicant_username']) ? $_REQUEST['applicant_username'] : "";
		
		//add yes123 2015-01-29 查询活动订单条件
		$condition['activity_id'] = isset ($_REQUEST['activity_id']) ? $_REQUEST['activity_id'] : 0;
		$condition['client_id'] = isset ($_REQUEST['client_id']) ? $_REQUEST['client_id'] : 0;
		
		//add yes123 2015-03-10  渠道需要的查询条件
		$condition['user_name'] = isset ($_REQUEST['user_name']) ? trim($_REQUEST['user_name']) : "";
		$condition['real_name'] = isset ($_REQUEST['real_name']) ? trim($_REQUEST['real_name']) : "";
		
		$condition['search'] = isset ($_REQUEST['search']) ? trim($_REQUEST['search']) : "";
		
		
		
		$size = isset ($_REQUEST['size']) ? $_REQUEST['size'] : 10;
		//如果有client_id，说明是查询C端自己的单子
		if($condition['client_id']){
			$where_sql=" WHERE oi.client_id =$condition[client_id] ";
			if($condition['activity_id']){
				$where_sql.=" AND oi.activity_id =".$condition['activity_id'];
			}
		}else{
			//渠道
			if($is_organ==1){
				$condition['act']="order_manage_list";
				$where_sql=" WHERE oi.organ_user_id =$user_id AND oi.pay_status =2 AND oi.order_status=1 AND shipping_status=0 ";
			}
			else
			{
				$where_sql=" WHERE oi.user_id =$user_id ";
			}
			//$where_sql.=" AND oi.activity_id =".$condition['activity_id'];
		}
		
		
		
			//处理最近时间
	    $sdTime = sdtimeByZjTime();
		if(count($sdTime)>0){
			$condition['s_add_time']=$sdTime['s_add_time'];
			$condition['e_add_time']=$sdTime['e_add_time'];
		}
		
		//add 是否开发票标识 by dingchaoyang 2014-12-7 
		if($condition['need_receipt']){
			$where_sql.=" AND oi.need_receipt=".$condition['need_receipt'];
		}
		
		if($condition['order_sn']){
			$where_sql.=" AND oi.order_sn='".$condition['order_sn']."'";
		}
		
		if($condition['invoice_id']!=''){
			include_once (ROOT_PATH . 'includes/class/invoice.class.php');
			$order_ids = Invoice::getPolicyIdsByInvoiceId($condition['invoice_id']);
			$ids="";
			foreach ( $order_ids as $value ) {
	       		$ids .= $value['order_id'].",";
			}
			if(strstr($ids,',')){
				$ids= rtrim($ids, ','); 
			}
			$where_sql.=" AND oi.order_id in (".$ids.")"; 
		}
		
		if($condition['applicant_username'])
		{
			$where_sql.=" AND ip.applicant_username LIKE '%".$condition['applicant_username']."%'";
		}
		
		if($condition['s_add_time']!=''){
			$where_sql.=" AND oi.add_time >=".strtotime($condition['s_add_time']);
		}
		if($condition['e_add_time']!=''){
			$where_sql.=" AND oi.add_time <=".strtotime($condition['e_add_time']);
		}
		
		if($condition['pay_id']){
			$where_sql.=" AND oi.pay_id =".$condition['pay_id'];
		}
		
		if($condition['order_status']!=''){
			if($condition['order_status']==1){
				$where_sql.=" AND oi.pay_status =2 AND oi.order_status=1 AND shipping_status=0 " ;
			}elseif($condition['order_status']==0){
				$where_sql.=" AND oi.pay_status =0 AND oi.order_status=0 AND shipping_status=0 " ;
			}elseif($condition['order_status']==2){
				$where_sql.=" AND oi.pay_status =0 AND oi.order_status=2 AND shipping_status=0 " ;
			}
		}	
		
		if ($condition['goods_name'])
	    {
	        $where_sql.=" AND ip.attribute_name LIKE '%".$condition['goods_name']."%'";
	    }
	    
	    //add yes123 2015-03-10  渠道需要的查询条件
	    if ($condition['user_name'])
	    {
	        $where_sql.=" AND u.user_name LIKE '%".$condition['user_name']."%'";
	    }
	    if ($condition['real_name'])
	    {
	        $where_sql.=" AND u.real_name LIKE '%".$condition['real_name']."%'";
	    }
	    if ($condition['search'])
	    {
	        $where_sql.=" AND (oi.order_sn='$condition[search]' OR oi.applicant_username_str LIKE '%$condition[search]%') ";
	    }
	    
	    
	    
	    //modify  2015-02-05 yes123 把LEFT 修改为INNER
	    //获取总数
	    //$sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('order_info')." AS oi LEFT JOIN t_insurance_policy AS ip ON oi.order_id = ip.order_id ".$where_sql;//add by wangcya, for bug[193],能够支持多人批量投保
	/*    $sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('order_info')." AS oi
	    		LEFT JOIN t_insurance_policy AS ip ON oi.order_id = ip.order_id 
	    		LEFT JOIN bx_users u ON oi.user_id = u.user_id ".$where_sql;//add by wangcya, for bug[193],能够支持多人批量投保
	  	*/
	  	$sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('order_info')." AS oi
	    		LEFT JOIN bx_users u ON oi.user_id = u.user_id ".$where_sql;
	    
	    $record_count = $GLOBALS['db']->getOne($sql);
	    
	    $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$pager  = get_pager('user.php', $condition, $record_count, $page);
		
		//获取订单列表
		$orders = get_user_orders($user_id, $pager['size'], $pager['start'],$where_sql);
		
		//modify  2015-02-05 yes123 把LEFT 修改为INNER
		//获取订单金额总数
		//$sql="SELECT SUM(goods_amount) tatol FROM " . $GLOBALS['ecs']->table('order_info') ." AS oi LEFT JOIN t_insurance_policy AS ip ON oi.order_id = ip.order_id ". $where_sql;
		$sql="SELECT SUM(goods_amount) tatol FROM " . $GLOBALS['ecs']->table('order_info') ." AS oi LEFT JOIN t_insurance_policy AS ip ON oi.order_id = ip.order_id LEFT JOIN bx_users u ON oi.user_id = u.user_id ". $where_sql;
    	$order_tatol_amount = $GLOBALS['db']->getOne($sql);
		
		//获取支付列表
		$sql="SELECT pay_id,pay_code,pay_name FROM " . $GLOBALS['ecs']->table('payment') ;
    	$payment = $GLOBALS['db']->getAll($sql);
		
		return array(
			'payment'=>$payment,
 			'orders'=>$orders,
			'record_count'=>$record_count,
			'pager'=>$pager,
			'condition'=>$condition,
			'order_tatol_amount'=>$order_tatol_amount
			);
	}
	
	//获取保单列表
	public function getPolicyList($is_organ=""){
		include_once (ROOT_PATH . 'includes/lib_policy.php'); 
		$user_id = $_SESSION['user_id'];
		//保单条件 br:yes123,20140906 
		$condition=array();
		$condition['act']="warranty_list";
		$from = isset ($_REQUEST['from']) ? trim($_REQUEST['from']) : "";
		$condition['policy_no'] = isset ($_REQUEST['policy_no']) ? trim($_REQUEST['policy_no']) : "";
		$condition['attribute_name'] = isset ($_REQUEST['attribute_name']) ? trim($_REQUEST['attribute_name']) : "";
		$condition['applicant_username'] = isset ($_REQUEST['applicant_username']) ? trim($_REQUEST['applicant_username']) : "";
		$condition['s_add_time'] = isset ($_REQUEST['s_add_time']) ? $_REQUEST['s_add_time'] : "";
		$condition['e_add_time'] = isset ($_REQUEST['e_add_time']) ? $_REQUEST['e_add_time'] : "";
		$condition['policy_status'] = isset ($_REQUEST['policy_status']) ? $_REQUEST['policy_status'] : "";
		$condition['warranty_money'] = isset ($_REQUEST['warranty_money']) ? trim($_REQUEST['warranty_money']) : "";
		$condition['assured_fullname'] = isset ($_REQUEST['assured_fullname']) ? trim($_REQUEST['assured_fullname']) : "";
		$condition['assured_certificates_code'] = isset ($_REQUEST['assured_certificates_code']) ? trim($_REQUEST['assured_certificates_code']) : "";
		$condition['applicant_uid'] = isset ($_REQUEST['applicant_uid']) ? $_REQUEST['applicant_uid'] : "";//add by dingchaoyang 2014-12-13
		$condition['order_id'] = isset ($_REQUEST['order_id']) ? $_REQUEST['order_id'] : 0;
		$condition['invoice_id'] = isset ($_REQUEST['invoice_id']) ? $_REQUEST['invoice_id'] : "";
		$condition['brand_id'] = isset ($_REQUEST['brand_id']) ? $_REQUEST['brand_id'] :0;
		
		//add yes123 2015-03-10 渠道查询条件
		$condition['user_name'] = isset ($_REQUEST['user_name']) ? trim($_REQUEST['user_name']) : "";
		$condition['real_name'] = isset ($_REQUEST['real_name']) ? trim($_REQUEST['real_name']) : "";	
			
		//add yes123 2015-01-29 活动ID
		$condition['activity_id'] = isset ($_REQUEST['activity_id']) ? $_REQUEST['activity_id'] : 0;
		$condition['client_id'] = isset ($_REQUEST['client_id']) ? $_REQUEST['client_id'] : 0;
		
		$size = isset ($_REQUEST['size']) ? $_REQUEST['size'] : 10;
		
		//add yes123 2015-02-06  页大小最大1000
		if($size>500){
			$size=500;
		}
		
		
		$where_sql=" WHERE 1";
		//如果有client_id，说明是查询C端自己的单子
		if($condition['client_id']){
			$where_sql.=" p.client_id =$condition[client_id] ";
			if($condition['activity_id']){
				$where_sql.=" AND p.activity_id =".$condition['activity_id'];
			}
		}else{
			//渠道
			if($is_organ=="policy_manage_list"){
				$condition['act']="policy_manage_list";
				$where_sql .=" AND (p.organ_user_id =$user_id OR p.agent_uid ='$user_id') AND policy_status='insured' ";
				//$where_sql=" WHERE p.agent_uid =$user_id AND policy_status='insured' ";
			}
			else //如果来自微信端的订单详情，就不用限制为已投保成功的
			{
				$where_sql.=" AND p.agent_uid ='$user_id'";
			}
			
			//$where_sql.=" AND p.activity_id =".$condition['activity_id'];
		}
		
		
		//处理最近时间
		
	    $sdTime = sdtimeByZjTime();
	    
		if(count($sdTime)>0){
			$condition['s_add_time']=$sdTime['s_add_time'];
			$condition['e_add_time']=$sdTime['e_add_time'];
		}
		
		if($condition['user_name']!=''){
				
			$where_sql.=" AND bxu.user_name LIKE '%".$condition['user_name']."%'";
		}
		
		if($condition['real_name']!=''){
				
			$where_sql.=" AND bxu.real_name LIKE '%".$condition['real_name']."%'";
		}
		
		if($condition['policy_no']!=''){
				
			$where_sql.=" AND p.policy_no='".$condition['policy_no']."'";
		}
		
		
		if($condition['applicant_uid']!=''){
			$where_sql.=" AND p.applicant_uid='".$condition['applicant_uid']."'";
		}
		
		
		if($condition['attribute_name']!=''){
			$where_sql.=" AND p.attribute_name LIKE '%".$condition['attribute_name']."%'";
		}
		
		if($condition['applicant_username']!=''){
			$where_sql.=" AND p.applicant_username LIKE '%".$condition['applicant_username']."%'";
		}
		
		if($condition['s_add_time']!=''){
			$where_sql.=" AND p.dateline >=".strtotime($condition['s_add_time']);
		}
		if($condition['e_add_time']!=''){
			$where_sql.=" AND p.dateline <=".strtotime($condition['e_add_time']);
		}
		if($condition['order_id']){
			$where_sql.=" AND p.order_id =".$condition['order_id'];
		}
		
		if($condition['invoice_id']){
			
			include_once (ROOT_PATH . 'includes/class/invoice.class.php');
			$policy_ids = Invoice::getPolicyIdsByInvoiceId($condition['invoice_id']);
			$ids = CommonUtils :: arrToStr($policy_ids, "policy_id");
			
			$where_sql.=" AND p.policy_id IN (".$ids.")"; 
		}
		
		
		//start add by wangcya, 20141119 , for 被保险人
		if ($condition['assured_fullname'] || $condition['assured_certificates_code'] )
		{
			$temp_where=" ";
			if($condition['assured_fullname'])
			{
				$temp_where.=" AND fullname='".$condition['assured_fullname']."'";
			}
			 
			if($condition['assured_certificates_code'])
			{
				$temp_where.=" AND certificates_code='".$condition['assured_certificates_code']."'";
			}
			 
			 
			$uid_by_uname_sql="SELECT uid FROM t_user_info WHERE 1=1 ".$temp_where;
			//ss_log($uid_by_uname_sql);
			$uids= $GLOBALS['db']->getAll($uid_by_uname_sql);
			$uids = CommonUtils::arrToStr($uids,"uid");
				
			if($uids)
			{
				$sql ="SELECT policy_subject_id FROM t_insurance_policy_subject_insurant_user WHERE uid IN($uids)";
				$policy_subject_id_list= $GLOBALS['db']->getAll($sql);
				$policy_subject_id_str = CommonUtils::arrToStr($policy_subject_id_list,"policy_subject_id");
				
				$sql="SELECT policy_id FROM t_insurance_policy_subject WHERE policy_subject_id IN($policy_subject_id_str)";
				$policy_ids= $GLOBALS['db']->getAll($sql);
				
				
				$policy_ids = CommonUtils::arrToStr($policy_ids,"policy_id");
				
				$where_sql .= " AND p.policy_id in(".$policy_ids.")";
			}
			else
			{
				$where_sql .= " AND p.policy_id =0 ";
			}
		
		}
		//end add by wangcya, 20141119 , for 被保险人
		
		
		if($condition['brand_id'])
		{
			$sql = " SELECT tid FROM bx_goods WHERE brand_id='$condition[brand_id]' ";
			$tid_list = $GLOBALS['db']->getAll($sql);
			$tid_str = CommonUtils::arrToStr($tid_list,"tid");
			$where_sql .= " AND p.attribute_id IN ($tid_str) ";
			
		}
		
				
		
		//add yes123 2015-05-25 购物车商品列表
		$condition['list_type'] = isset ($_REQUEST['list_type']) ? trim($_REQUEST['list_type']) : "";		
		if($condition['list_type']=='cart_list')
		{
			$where_sql.=" AND p.policy_status='cart' AND p.order_id=0 ";
		}
		elseif($condition['policy_status']){
			$where_sql.=" AND p.policy_status='".$condition['policy_status']."' ";
		}
		elseif($from!='order_info')
		{
			$where_sql.="  AND p.policy_status='insured' ";
		}
		
		
		
		$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		
		$sql = "SELECT COUNT(p.policy_id) FROM t_insurance_policy p ".
	       " LEFT JOIN bx_order_info o ON p.order_id=o.order_id 
	         LEFT JOIN bx_users bxu ON bxu.user_id=p.agent_uid $where_sql";
		$record_count = $GLOBALS['db']->getOne($sql);
		ss_log('get policy_list record_count ' .$record_count.":".$sql);
		
		//add yes123 2015-03-10获取总保费
		$sql = "SELECT SUM(p.total_premium) FROM t_insurance_policy p ".
	       " LEFT JOIN bx_order_info o ON p.order_id=o.order_id 
	         LEFT JOIN bx_users bxu ON bxu.user_id=p.agent_uid $where_sql";
        ss_log("获取总保费：".$sql);
		$total_premium = $GLOBALS['db']->getOne($sql);
		
		$pager  = get_pager('user.php', $condition, $record_count, $page,$size);
		$warranty = get_user_policy($user_id,$pager['size'], $pager['start'],$where_sql);
		
		return array('policy_list'=>$warranty,'record_count'=>$record_count,'pager'=>$pager,'condition'=>$condition,'total_premium'=>$total_premium);
	}
	
	
	public function removePolicy($policy_ids,$user_id){
		$res = array();
		$res['code']=0;
		
		
		if(!$policy_ids)
		{
			$res['msg']= "policy_id为空，无法删除！";
			$res['code']=1;
			return $res;
		}
		
		
		//先判断这些保单是否属于废弃状态，例如购物车。
		include_once(ROOT_PATH . 'includes/class/Order.class.php');
		$order_obj = new Order;
		$res = $order_obj->checkPolicyStatus($policy_ids);
		if($res['code']==1)
		{
			$res['msg']= $res['msg']."已绑定其他订单，无法删除！";
			return $res;
		}
		
		
		//1.查询所有t_insurance_policy_subject  获取所有policy_subject_id
		$sql="SELECT policy_subject_id FROM t_insurance_policy_subject WHERE policy_id IN($policy_ids)";
		ss_log(__FUNCTION__."获取所有通过保单IDt_insurance_policy_subject:".$sql);
		$policy_subject_id_list= $GLOBALS['db']->getAll($sql);
		$policy_subject_id_str = CommonUtils::arrToStr($policy_subject_id_list,"policy_subject_id");
		
		
		$sql ="DELETE FROM t_insurance_policy_subject_insurant_user WHERE policy_subject_id IN($policy_subject_id_str)";
		ss_log(__FUNCTION__."删除 t_insurance_policy_subject_insurant_user:".$sql);
		$GLOBALS['db']->query($sql);
		
		$sql ="DELETE FROM t_insurance_policy_subject_product_duty_prices WHERE policy_subject_id IN($policy_subject_id_str)";
		ss_log(__FUNCTION__."删除 t_insurance_policy_subject_product_duty_prices:".$sql);
		$GLOBALS['db']->query($sql);
		
		$sql ="DELETE FROM t_insurance_policy_subject_products WHERE policy_subject_id IN($policy_subject_id_str)";
		ss_log(__FUNCTION__."删除 t_insurance_policy_subject_products:".$sql);
		$GLOBALS['db']->query($sql);
		
		
		$sql ="DELETE FROM t_insurance_policy_subject WHERE policy_id IN($policy_ids)";
		ss_log(__FUNCTION__."删除 t_insurance_policy_subject:".$sql);
		$GLOBALS['db']->query($sql);
		
		
		
		
		$sql = "DELETE FROM t_insurance_policy WHERE policy_id IN($policy_ids)  AND agent_uid='$user_id' ";
		ss_log(__FUNCTION__."删除 t_insurance_policy:".$sql);
		if($GLOBALS['db']->query($sql))
		{
			$res['msg']="删除成功!";
		}
		else
		{
			$res['code']=1;
			$res['msg']="删除失败!";
		}
		
		return $res;
	}
	
	
	//comment by zhangxi,20150417, 用户注册函数, pc端，微信端都会走
	public function userRegister(){
		global $_CFG,$_LANG,$_CFG,$smarty,$err,$standard_addr;
		
		$checkCode = trim($_POST['checkCode']);
		$username = trim($_POST['username']);

		/* 增加是否关闭注册 */
		if ($_CFG['shop_reg_closed'])
		{
		    $smarty->assign('action',     'register');
		    $smarty->assign('shop_reg_closed', $_CFG['shop_reg_closed']);
		    $smarty->display('user_passport.dwt');
		}
		else
		{   //开始注册
		
		    include_once(ROOT_PATH . 'includes/lib_passport.php');
			$is_weixin = isset($_POST['is_weixin']) ? trim($_POST['is_weixin']) : '';
		    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
		    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
		    $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
		    
		    
		    $other['msn'] = isset($_POST['extend_field1']) ? $_POST['extend_field1'] : '';
		    $other['qq'] = isset($_POST['extend_field2']) ? $_POST['extend_field2'] : '';
		    $other['office_phone'] = isset($_POST['extend_field3']) ? $_POST['extend_field3'] : '';
		    $other['home_phone'] = isset($_POST['extend_field4']) ? $_POST['extend_field4'] : '';
		    $other['mobile_phone'] = isset($_POST['extend_field5']) ? $_POST['extend_field5'] : '';
			$other['is_institution'] = isset($_POST['is_institution']) ? $_POST['is_institution'] : 1;
			//added by zhangxi, 20150426, 增加活动id的处理 ,更新到用户数据库中
			$other['active_id'] = isset($_GET['activity']) ? $_GET['activity'] : 0;
			
			//默认是普通会员
			$user_rank = get_user_rank_info(0,COMMON_USER);
			$other['user_rank'] = $user_rank['rank_id'];
			
		    $sel_question = empty($_POST['sel_question']) ? '' : compile_str($_POST['sel_question']);
		    $passwd_answer = isset($_POST['passwd_answer']) ? compile_str(trim($_POST['passwd_answer'])) : '';
		
		    $back_act = isset($_POST['back_act']) ? trim($_POST['back_act']) : '';
			
			
			if (strlen($username) < 3)
			{
				show_message($_LANG['passport_js']['username_shorter']);
			}
		        
			if (strlen($password) < 6)
			{
			 	show_message($_LANG['passport_js']['password_shorter']);
			}
		        
		    if (strpos($password, ' ') > 0)
		    {
		    	show_message($_LANG['passwd_balnk']);
		    }
		    
		    
		    //add by dingchaoyang 2014-12-8,
		    include_once (ROOT_PATH . 'api/EBaoApp/platformEnvironment.class.php');
		    if (!(PlatformEnvironment::isMobilePlatform()))
		    {   
		    	//查询保存的验证码
		    	$check_res = check_code($username,$checkCode,REG_VERIFICATION_CODE_SMS);
		    	if($check_res['code']!=CHECK_CODE_OK)
		    	{
		    		show_message($check_res['msg'], '', 'user.php?act=register');
		    		
		    	}
		    
		    }
			//获取平台
			include_once (ROOT_PATH . 'api/EBaoApp/platformEnvironment.class.php');
			$platform_id = PlatformEnvironment::getPlatformID ();
			if ($platform_id) {
				$order ['platform_id'] = $platform_id;
			}
			
			
			
			if(!$is_weixin)
			{
				/* 验证码检查 */
		        if ((intval($_CFG['captcha']) & CAPTCHA_REGISTER) && gd_version() > 0)
		        {
		            if (empty($_POST['captcha']))
		            {
		                show_message($_LANG['invalid_captcha'], $_LANG['sign_up'], 'user.php?act=register', 'error');
		            }
		
		            /* 检查验证码 */
		            include_once('includes/cls_captcha.php');
		
		            $validator = new captcha();
		            if (!$validator->check_word($_POST['captcha']))
		            {
		                show_message($_LANG['invalid_captcha'], $_LANG['sign_up'], 'user.php?act=register', 'error');
		            }
		        }
			}
			
		    //注册的函数
		    if (register($username, $password, $email, $other) !== false) 
		    {
		    	//add yes123 2015-01-06 保存手机号码和最后更新时间,上面保存不成功
		        include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
				if(CommonUtils::isPhoneNumber($username))
				{   
					$last_time = date('Y-m-d H:i:s',time());                    
					$sql = " UPDATE " . $GLOBALS['ecs']->table('users') . " SET mobile_phone='$username',last_time='$last_time' WHERE user_name='$username'";  
					$GLOBALS['db']->query($sql);
				}
		    	
		        /*把新注册用户的扩展信息插入数据库*/
		        $sql = 'SELECT id FROM ' . $GLOBALS['ecs']->table('reg_fields') . ' WHERE type = 0 AND display = 1 ORDER BY dis_order, id';   //读出所有自定义扩展字段的id
		        $fields_arr = $GLOBALS['db']->getAll($sql);
		
		        $extend_field_str = '';    //生成扩展字段的内容字符串
		        foreach ($fields_arr AS $val)
		        {
		            $extend_field_index = 'extend_field' . $val['id'];
		            if(!empty($_POST[$extend_field_index]))
		            {
		                $temp_field_content = strlen($_POST[$extend_field_index]) > 100 ? mb_substr($_POST[$extend_field_index], 0, 99) : $_POST[$extend_field_index];
		                $extend_field_str .= " ('" . $_SESSION['user_id'] . "', '" . $val['id'] . "', '" . compile_str($temp_field_content) . "'),";
		            }
		        }
		        $extend_field_str = substr($extend_field_str, 0, -1);
		
		        if ($extend_field_str)      //插入注册扩展数据
		        {
		            $sql = 'INSERT INTO '. $GLOBALS['ecs']->table('reg_extend_info') . ' (`user_id`, `reg_field_id`, `content`) VALUES' . $extend_field_str;
		            $GLOBALS['db']->query($sql);
		        }
		        
		    	
		    	//赠送代金券
		    	$user_info = get_user_info($_SESSION['user_id']);
		    	$this->sendBonus($user_info['user_id'],SEND_BY_REG,REG_BONUS,$user_info['user_id']);
		        
		        //start add yes123 2015-04-26 判断parent_id 是不是机构，如果是更新此会员机构ID
				$this->updateInstitutionId($_SESSION['user_id']);
				
				
				//add yes123  2015-06-23 判断是如果是二级域名的话，获取渠道ID，更新次用户
				
				$distributor_cfg = get_organ_config();
				if($distributor_cfg)
				{
					$d_uid = $distributor_cfg['d_uid'];
					$sql = " UPDATE " . $GLOBALS['ecs']->table('users') . " SET institution_id='$d_uid' WHERE user_name='$username'";  
					$GLOBALS['db']->query($sql);
					
				}
		        
		        
		        //如果是手机注册的，跳转到另外一个页面
		        if($is_weixin)
		        {
		        	ss_log("微信用户，跳转到个人资料页面");
		        	header("location: index.php");
		        	//header("location: http://mp.weixin.qq.com/s?__biz=MzA3OTYwMDQxMQ==&mid=204924241&idx=1&sn=fa45b48a31a18b80b776a39c133ad10a&key=1936e2bc22c2ceb5c364dc0fffb4c8c2e8139d1b56967eb1b787240cba48150a95988d49530797db3db3acaae1431304&ascene=1&uin=MTI2ODc3NjE0MA%3D%3D&devicetype=Windows+7&version=61000721&pass_ticket=eHf0ZumdzaNnu1S%2BVcWTL7uY0v2gYT4kGOp%2BvWPr7aYRO%2BtvcU7TwgnlkJNYi4wE");
		        	
		        	
		        	exit; //modify yes123 2015-01-27 注册完毕后，跳转到会员中心修改资料
		        }
		        
				if($_SESSION['rank_code'] == COMMON_USER)
				{
					//add by dingchaoyang 2014-11-5
					//响应json数据到客户端
					include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
					EbaAdapter::responseData('RegisterResponseSuccess');
					//end add by dingchaoyang 2014-11-5
					
		            //modify yes123 2015-01-27注册成功直接跳转到个人资料页面  
		            $ucdata = empty($user->ucdata)? "" : $user->ucdata;
		            show_message(sprintf($_LANG['register_success'], $username . $ucdata), array($_LANG['back_up_page'], $_LANG['profile_lnk']), array($back_act, 'index.php'), 'info');
		            //show_message(sprintf($_LANG['register_success'], $username . $ucdata), '','user.php?act=profile');
				}
				elseif($_SESSION['rank_code'] == ORGANIZATION_USER)
				{
					 $ucdata = empty($user->ucdata)? "" : $user->ucdata;
				  	 show_message(sprintf($_LANG['register_success'], $username . $ucdata), array($_LANG['back_up_page'], $_LANG['profile_lnk']), 'user.php?act=organ_info');
				 	//show_message(sprintf($_LANG['register_success'], $username . $ucdata), '','user.php?act=organ_info'); 	
				}
				
		    }
		    else
		    {
		    	ss_log("rigister user fail!");
		    	//add by dingchaoyang 2014-11-5
		    	//响应json数据到客户端
		    	include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
		    	EbaAdapter::responseData(RegisterResponseFail);
		    	//end add by dingchaoyang 2014-11-5
		        $err->show($_LANG['sign_up'], 'user.php?act=register');
		    }
		}
			
	}
		    
	
	function registerMeeting()
	{
		global $_CFG,$_LANG,$_CFG;
		
		$checkCode = $_POST['checkCode'];
		$username = $_POST['username'];
	
		ss_log("into act_register_meeting");
		/* 增加是否关闭注册 */
		if ($_CFG['shop_reg_closed'])
		{
			$smarty->assign('action',     'register');
			$smarty->assign('shop_reg_closed', $_CFG['shop_reg_closed']);
			$smarty->display('user_passport.dwt');
		}
		else
		{//开始注册
			include_once(ROOT_PATH . 'includes/lib_passport.php');
			$is_weixin = isset($_POST['is_weixin']) ? trim($_POST['is_weixin']) : '';
			$username = isset($_POST['username']) ? trim($_POST['username']) : '';
			$password = isset($_POST['password']) ? trim($_POST['password']) : '';
			$email    = isset($_POST['email']) ? trim($_POST['email']) : '';
			$other['msn'] = isset($_POST['extend_field1']) ? $_POST['extend_field1'] : '';
			$other['qq'] = isset($_POST['extend_field2']) ? $_POST['extend_field2'] : '';
			$other['office_phone'] = isset($_POST['extend_field3']) ? $_POST['extend_field3'] : '';
			$other['home_phone'] = isset($_POST['extend_field4']) ? $_POST['extend_field4'] : '';
			$other['mobile_phone'] = isset($_POST['extend_field5']) ? $_POST['extend_field5'] : '';
			$other['is_institution'] = isset($_POST['is_institution']) ? $_POST['is_institution'] : 1;
			$sel_question = empty($_POST['sel_question']) ? '' : compile_str($_POST['sel_question']);
			$passwd_answer = isset($_POST['passwd_answer']) ? compile_str(trim($_POST['passwd_answer'])) : '';
	
			$back_act = isset($_POST['back_act']) ? trim($_POST['back_act']) : '';
	
			$activity_id = isset($_POST['activity_id']) ? intval($_POST['activity_id']) : 0;//活动的id,通过活动页面注册的，就需要即可，老人登录，也需要携带。
			$_SESSION['activity_id'] = $activity_id;//add by wangcya, 2015-01-31 , for bug[238]
			////////////////////////////////////////////////////////////////////////////
			if(1)//暂时测试用
			{
				if (strlen($username) < 3)
				{
					show_message($_LANG['passport_js']['username_shorter']);
				}
				 
				if (strlen($password) < 6)
				{
					show_message($_LANG['passport_js']['password_shorter']);
				}
				 
				if (strpos($password, ' ') > 0)
				{
					show_message($_LANG['passwd_balnk']);
				}
				 
				//add by dingchaoyang 2014-12-8,
				include_once (ROOT_PATH . 'api/EBaoApp/platformEnvironment.class.php');
				if (!(PlatformEnvironment::isMobilePlatform()))
				{
					//add yes123 2014-11-28 验证用户注册信息 start
					$phone_code = $_SESSION['phone_code'];
					if($phone_code)
					{
						//验证用户填写的验证码和服务端发送的是否一致
						if($phone_code['code']!=$checkCode)
						{
							show_message('短信动态验证码有误！', '', 'user.php?act=register');
							//用完后清空
							$_SESSION['phone_code']=null;
							exit;
						}
	
						//验证用户填写的手机号码和服务端发送的是否一致
						if($phone_code['username']!=$username)
						{
							show_message('手机号码有误！', '', 'user.php?act=register');
							//用完后清空
							$_SESSION['phone_code']=null;
							exit;
						}
	
	
					}
					else
					{
						ss_log("error:user register  SESSION[phone_code] is null ");
						show_message('抱歉,出现未知错误,请重新获取验证码 ', '', 'user.php?act=register');exit;
					}
	
					//add yes123 2014-11-28 验证用户注册信息 end
						
					if(empty($_POST['agreement']))
					{
						show_message($_LANG['passport_js']['agreement']);
					}
	
					//add by dingchaoyang 2014-11-27,手机端不上传验证码
					if ((intval($_CFG['captcha']) & CAPTCHA_REGISTER) && gd_version() > 0)
					{
						if (empty($_POST['captcha']))
						{
							show_message($_LANG['invalid_captcha'], $_LANG['sign_up'], 'user.php?act=register', 'error');
						}
	
						/* 检查验证码 */
						include_once('includes/cls_captcha.php');
	
						$validator = new captcha();
						if (!$validator->check_word($_POST['captcha']))
						{
							show_message($_LANG['invalid_captcha'], $_LANG['sign_up'], 'user.php?act=register', 'error');
						}
					}
				}
			}//暂时测试用
	
	
			//注册的函数
			if (register($username, $password, $email, $other) !== false)
			{
				 
				//start add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能,可以在这个地方做跳转
				$user_type = trim($_POST['user_type']);//add by wangcya, 20150129,for bug[238], 增加一个会议活动注册的功能,可以在这个地方做跳转
				if($user_type=="meeting")//活动注册的，则要更新该字段
				{
	
					$sql = " UPDATE " . $GLOBALS['ecs']->table('users') . " SET type='$user_type' WHERE user_name='$username'";
					ss_log($sql);
					$GLOBALS['db']->query($sql);
					
					///////////////如果会议注册，则插入一条活动记录////////////////////////////////////////
					$uid = $_SESSION['user_id'];
					$this->add_user_activity($activity_id,$uid);
					
				}
				 
				//end add by wangcya, 2015029, for bug[238], 增加一个会议活动注册的功能,可以在这个地方做跳转
				 
				//add yes123 2015-01-06 保存手机号码和最后更新时间,上面保存不成功
				include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
				if(CommonUtils::isPhoneNumber($username))
				{
					$last_time = date('Y-m-d H:i:s',time());
					$sql = " UPDATE " . $GLOBALS['ecs']->table('users') . " SET mobile_phone='$username',last_time='$last_time' WHERE user_name='$username'";
					ss_log($sql);
					$GLOBALS['db']->query($sql);
				}
				 
				/*把新注册用户的扩展信息插入数据库*/
				$sql = 'SELECT id FROM ' . $GLOBALS['ecs']->table('reg_fields') . ' WHERE type = 0 AND display = 1 ORDER BY dis_order, id';   //读出所有自定义扩展字段的id
				$fields_arr = $GLOBALS['db']->getAll($sql);
	
				$extend_field_str = '';    //生成扩展字段的内容字符串
				foreach ($fields_arr AS $val)
				{
					$extend_field_index = 'extend_field' . $val['id'];
					if(!empty($_POST[$extend_field_index]))
					{
						$temp_field_content = strlen($_POST[$extend_field_index]) > 100 ? mb_substr($_POST[$extend_field_index], 0, 99) : $_POST[$extend_field_index];
						$extend_field_str .= " ('" . $_SESSION['user_id'] . "', '" . $val['id'] . "', '" . compile_str($temp_field_content) . "'),";
					}
				}
				$extend_field_str = substr($extend_field_str, 0, -1);
	
				if ($extend_field_str)      //插入注册扩展数据
				{
					$sql = 'INSERT INTO '. $GLOBALS['ecs']->table('reg_extend_info') . ' (`user_id`, `reg_field_id`, `content`) VALUES' . $extend_field_str;
					$GLOBALS['db']->query($sql);
				}
	
				/* 写入密码提示问题和答案 */
				/*if (!empty($passwd_answer) && !empty($sel_question))
				 {
				$sql = 'UPDATE ' . $GLOBALS['ecs']->table('users') . " SET `passwd_question`='$sel_question', `passwd_answer`='$passwd_answer'  WHERE `user_id`='" . $_SESSION['user_id'] . "'";
				$GLOBALS['db']->query($sql);
				}*/
				/* 判断是否需要自动发送注册邮件 */
				if ($GLOBALS['_CFG']['member_email_validate'] && $GLOBALS['_CFG']['send_verify_email'])
				{
					send_regiter_hash($_SESSION['user_id']);
				}
				send_regiter_hash($_SESSION['user_id']);
				$ucdata = empty($user->ucdata)? "" : $user->ucdata;
	
				//如果是手机注册的，跳转到另外一个页面
				if($is_weixin)
				{
					ss_log("微信用户，跳转到个人资料页面");
					 
					$user_id = $_SESSION['user_id'];
					//$_SESSION['user_name'] = $user_name;
					//header("location: user.php?act=profile_meeting");
					header("location: user.php");
					exit; //modify yes123 2015-01-27 注册完毕后，跳转到会员中心修改资料
				}
	
				if($_SESSION['is_institution'] == 1)
				{
					//add by dingchaoyang 2014-11-5
					//响应json数据到客户端
					include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
					EbaAdapter::responseData('RegisterResponseSuccess');
					//end add by dingchaoyang 2014-11-5
	
					//modify yes123 2015-01-27注册成功直接跳转到个人资料页面
					//show_message(sprintf($_LANG['register_success'], $username . $ucdata), array($_LANG['back_up_page'], $_LANG['profile_lnk']), array($back_act, 'user.php?act=profile'), 'info');
					show_message(sprintf($_LANG['register_success'], $username . $ucdata), '','user.php?act=profile');
				}
				elseif($_SESSION['is_institution'] == 2)
				{
					show_message(sprintf($_LANG['register_success'], $username . $ucdata), array($_LANG['back_up_page'], $_LANG['profile_lnk']), 'user.php?act=organ_info');
	
				}
			}
			else
			{
				
				ss_log("注册用户失败了");
				$err->show($_LANG['sign_up'], 'user.php?act=register');
				
				/*
				ss_log("rigister user fail!");
				//add by dingchaoyang 2014-11-5
				//响应json数据到客户端
				include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
				EbaAdapter::responseData(RegisterResponseFail);
				//end add by dingchaoyang 2014-11-5
				$err->show($_LANG['sign_up'], 'user.php?act=register');
				*/
			}
		}
		
	}
	
	public function add_user_activity($activity_id,$uid)
	{
		if($activity_id)
		{
			$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('favourable_activity') . " WHERE act_id = '$activity_id'";
			ss_log($sql);
			$attr_activity = $GLOBALS['db']->getRow($sql);
			if($attr_activity)
			{
				////////////////////////////////////////////////////////////////
				
				$act_id = $attr_activity['act_id'];//活动id
				//////////////////////////////////////////////////////////////
				$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('meeting_register') . " WHERE uid='$uid' AND act_id = '$activity_id'";
				ss_log($sql);
				$count_my_activity = $GLOBALS['db']->getOne($sql);
				if($count_my_activity>0)
				{
					ss_log("已经参与过该活动了");
				}
				else
				{
					include_once(ROOT_PATH . 'includes/hongdong_function.php');
					$product_info = get_product_attribute_info_by_activity_id($act_id);
					
					$goods_id = $product_info['goods_id'];
					$price = $premium = $product_info['premium'];
					$attribute_id = $product_info['attribute_id'];
					$attribute_name = $product_info['attribute_name'];
						
					ss_log("goods_id: ".$goods_id);
					ss_log("act_id: ".$act_id);
					ss_log("attribute_id: ".$attribute_id);
					ss_log("attribute_name: ".$attribute_name);
					ss_log("premium: ".$premium);
					
					///////////////////////////////////////////////////////////
		
					$act_name = $attr_activity['act_name'];//活动名
					$need_pay = 1;//需要支付
					$registered = 0;//还没注册
					$dateline_reg = time();
					$dateline_pay =  time();
					$sql = "INSERT INTO " .$GLOBALS['ecs']->table('meeting_register'). " (uid, act_id, act_name,need_pay,price,registered,dateline_reg,dateline_pay)" .
							"VALUES ('$uid', '$act_id', '$act_name','$need_pay','$price','$registered','$dateline_reg','$dateline_pay')";
		
					ss_log($sql);
					$GLOBALS['db']->query($sql);
		
		
				}
			}
		}
	}
	
	public function editPayment(){
		
		 /* 检查支付方式 */
	    $pay_id = intval($_POST['pay_id']);
	    if ($pay_id <= 0)
	    {
	        ecs_header("Location: ./\n");
	        exit;
	    }
	
	    include_once(ROOT_PATH . 'includes/lib_order.php');
	    $payment_info = payment_info($pay_id);
	    if (empty($payment_info))
	    {
	        ecs_header("Location: ./\n");
	        exit;
	    }
	
	    /* 检查订单号 */
	    $order_id = intval($_POST['order_id']);
	    if ($order_id <= 0)
	    {
	        ecs_header("Location: ./\n");
	        exit;
	    }
	
	    /* 取得订单 */
	    $order = order_info($order_id);
	    if (empty($order))
	    {
	        ecs_header("Location: ./\n");
	        exit;
	    }
	
	    /* 检查订单用户跟当前用户是否一致 */
	    if ($_SESSION['user_id'] != $order['user_id'])
	    {
	        ecs_header("Location: ./\n");
	        exit;
	    }
	    
	    /* 检查订单是否未付款和未发货 以及订单金额是否为0 和支付id是否为改变*/
	    if ($order['pay_status'] != PS_UNPAYED || $order['shipping_status'] != SS_UNSHIPPED || $order['goods_amount'] <= 0 || $order['pay_id'] == $pay_id)
	    {
	    	ecs_header("Location: user.php?act=order_detail&order_id=$order_id\n");
	        exit;
	    }
		
		$order_amount = $order['order_amount'] - $order['pay_fee'];
	    $pay_fee = pay_fee($pay_id, $order_amount);
	    $order_amount += $pay_fee;
	
	    $sql = "UPDATE " . $GLOBALS['ecs']->table('order_info') .
	           " SET pay_id='$pay_id', pay_name='$payment_info[pay_name]', pay_fee='$pay_fee', order_amount='$order_amount'".
	           " WHERE order_id = '$order_id'";
	    return $GLOBALS['db']->query($sql);
	    
	    
	}
	
	
	////////////////////////////////////渠道///////////////////////////////////////////////
		
	//add yes123 2015-03-05 渠道信息
	public function organInfo($user_id=0)
	{
		if(!$user_id)
		{
			$user_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
		}
		$sql = "SELECT d.*,u.*,cfg.* FROM bx_users u " .
				" LEFT JOIN " . $GLOBALS['ecs']->table('distributor') ." d ON u.user_id=d.d_uid " .
				" LEFT JOIN " . $GLOBALS['ecs']->table('distributor_cfg') ." cfg  ON u.user_id=cfg.d_uid " .
				" WHERE u.user_id = '$user_id'";
		ss_log("get organInfo:".$sql);
		$distributor_info = $GLOBALS['db']->getRow($sql);
		
		$distributor_info['cat_ids_array'] = explode(",",$distributor_info['cat_ids']);
		
		$addr_code = explode(",", $distributor_info['d_zone']);
		$distributor_info['province']=$addr_code[0];
		$distributor_info['city']=$addr_code[1];
		$distributor_info['district']=$addr_code[2];
		
		if(!$distributor_info['d_mobilePhone'])
		{
			$sql = "SELECT mobile_phone FROM " . $GLOBALS['ecs']->table('users') .
			" WHERE user_id = '$user_id'";
			$distributor_info['d_mobilePhone'] = $GLOBALS['db']->getOne($sql);
			$sql = 'UPDATE ' . $GLOBALS['ecs']->table('distributor') . " SET d_mobilePhone = '$distributor_info[d_mobilePhone]' WHERE  d_uid = '$user_id'";
			ss_log("更新渠道电话号码:".$sql);
			$GLOBALS['db']->query($sql);
		}
		
		return array('distributor_info'=>$distributor_info);
		
	}
	
	//add yes123 2015-03-05 更新渠道信息
	public function editOrganInfo($user_id=0)
	{
		if(!$user_id)
		{
			$user_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
		}
		
		$save_type = isset ($_POST['save_type']) ? trim($_POST['save_type']) : '';
		
		$name = isset ($_POST['name']) ? trim($_POST['name']) : '';
		$type = isset ($_POST['type']) ? trim($_POST['type']) : '';
		$contacts = isset ($_POST['contacts']) ? trim($_POST['contacts']) : '';
		$mobilePhone = isset ($_POST['mobilePhone']) ? trim($_POST['mobilePhone']) : '';
		$email = isset ($_POST['email']) ? trim($_POST['email']) : '';
		$province = isset ($_POST['province']) ? trim($_POST['province']) : '';
		$city = isset ($_POST['city']) ? trim($_POST['city']) : '';
		$district = isset ($_POST['district']) ? trim($_POST['district']) : '';
		$d_zone = $province.','.$city.','.$district;
		$address = isset ($_POST['address']) ? trim($_POST['address']) : '';
		$check_status = isset ($_POST['check_status']) ? trim($_POST['check_status']) : '';
		
		$code=1;

		if(empty($contacts)){
		   $msg='联系人不能为空！';
		}
		if(empty($mobilePhone)){
		  $msg='移动电话不能为空！';
		}

		if(empty($email)){
		  $msg='Email地址不能为空！';
		}
		
		if($msg)
		{
			return array('code'=>$code,'msg'=>$msg);
		}
		
		
		$user_info = array();
		
		$user_info['Province'] = $province;
		$user_info['city'] = $city;
		$user_info['district'] = $district;
		$user_info['address'] =  $address;

		
		$distributor_data = array();
		$distributor_data['d_name'] = $name;
		$distributor_data['d_type'] = $type;
		$distributor_data['d_contacts'] = $contacts;
		$distributor_data['d_mobilePhone'] = $mobilePhone;
		$distributor_data['d_email'] = $email;
		$distributor_data['d_zone'] = $d_zone;
		$distributor_data['d_address'] = $address;
		
		$user_data = array();
		$user_data['user_name'] = $mobilePhone;
		$user_data['mobile_phone'] = $mobilePhone;
		$user_data['email'] = $email;
		$user_data['check_status'] = $check_status;
		$user_rank = get_user_rank_info(0,ORGANIZATION_USER);
		
		$user_data['user_rank'] = $user_rank['rank_id'];

		$validate_msg = $this->validate_data($user_id);
		if($validate_msg){
		  	$msg = $validate_msg;
		}else{
			//编辑
			if($save_type=='edit_organ_info'||$save_type=='edit_child_organ_info')
			{
				
				$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('users'), $user_data, 'UPDATE', 'user_id = ' .$user_id);
				
				$distributor_data['d_uid'] = $user_info['uid'] = $user_id;
				
				$sql="SELECT uid FROM bx_user_info  WHERE  uid=".$user_id;
				if($GLOBALS['db']->getOne($sql))
				{
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_info'), $user_info, 'UPDATE', 'uid = ' .$user_id);
				}
				else
				{
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_info'), $user_info, 'INSERT');	
				}
				
				
				
				
				
				$sql="SELECT d_uid FROM bx_distributor  WHERE  d_uid=".$user_id;
				if($GLOBALS['db']->getRow($sql))
				{
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('distributor'), $distributor_data, 'UPDATE', 'd_uid = ' .$user_id);
				}
				else
				{
					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('distributor'), $distributor_data, 'INSERT');	
					
				}
				
				//更新session里的名称
				if($user_id==$_SESSION['user_id'])
				{
					$_SESSION['real_name'] = $name;
				}
				
				$code=0;
				if($check_status){
					$msg='提交成功，请等待管理员审核！';
				}else{
					$msg='修改成功!';
				
				}
					
					
			}
			//新增
			else if($save_type=='add_child_organ')
			{
				$sql = "SELECT * FROM ". $GLOBALS['ecs']->table('distributor') ." WHERE d_uid = $user_id";
				$distributor = $GLOBALS['db']->getRow($sql);
				
				$password = isset ($_POST['password']) ? trim($_POST['password']) : '';
				$password = md5($password);
				
				$user_data['institution_id'] = $user_id;
				$user_data['password'] = $password;
				
				$user_data['reg_time'] = time();
				$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('users'), $user_data, 'INSERT');	
				$new_user_id = $GLOBALS['db']->insert_id();
				
				
				$distributor_data['d_uid'] = $user_info['uid'] =  $new_user_id;
				
				//新渠道应用子渠道的类型（共有还是私有）
				$distributor_data['d_institution_type'] = $distributor['d_institution_type'];
				
				$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('distributor'), $distributor_data, 'INSERT');	
				$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('user_info'), $user_info, 'INSERT');
				$code=0;
				$msg='添加成功！';
				
				//获取父渠道配置
				$sql = "SELECT * FROM bx_organ_ipa_rate_config WHERE institution_id='$user_id'";
				$organ_ipa_rate_config_list = $GLOBALS['db']->getAll($sql);
				foreach ( $organ_ipa_rate_config_list as $key => $organ_ipa_rate_config ) 
				{
					//先移除ID
					unset($organ_ipa_rate_config['id']);
					$temp_str = print_r($organ_ipa_rate_config,true);
					ss_log("temp_str:".$temp_str);
       				$organ_ipa_rate_config['institution_id'] = $new_user_id;
       				$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('organ_ipa_rate_config'), $organ_ipa_rate_config, 'INSERT');
				}
				
			}

		}
		
		return array('code'=>$code,'msg'=>$msg);
		
	}
	
	/* 渠道信息验证 */
	public function validate_data($user_id=0){
		
		$msg="";
		$where="";
		$users_where="";
		if($user_id){
			$where=" AND d_uid <> " . $user_id;
			$users_where=" AND user_id <> " . $user_id;
		}
		
		//验证邮箱是否存在
		$sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('distributor') .
		" WHERE d_email = '$_REQUEST[email]' $where";
		ss_log(__FUNCTION__."从distributor表里判断email是否重复:".$sql);
		$r = $GLOBALS['db']->getOne($sql);
		if($r){
			return '邮件地址已被使用,请更换!';
		}	
		
		if(!$r){
			$sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('users') .
			" WHERE email = '$_REQUEST[email]' $users_where";
			ss_log(__FUNCTION__."从users表里判断email是否重复:".$sql);
			$r = $GLOBALS['db']->getOne($sql);
			if($r)
			{
				return '邮件地址已被使用,请更换!';
			}
		}
		
		//验证手机是否存在
		$sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('distributor') .
		" WHERE d_mobilePhone = '$_REQUEST[mobilePhone]'  $where";
		ss_log(__FUNCTION__."从distributor表里判断手机是否重复:".$sql);
		$r = $GLOBALS['db']->getOne($sql);
		if(!$r){
			$sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('users') .
			" WHERE (mobile_phone = '$_REQUEST[mobilePhone]' OR user_name = '$_REQUEST[mobilePhone]') $users_where";
			ss_log(__FUNCTION__."从users表里判断手机是否重复:".$sql);
			$r = $GLOBALS['db']->getOne($sql);
			if($r)
			{
				return '手机号码已被使用,请更换!';
			}
		
		}
		else
		{
			return '手机号码已被使用,请更换!';
		}

		return $msg;
	}
	
	
	//渠道下的会员列表 可通过代理人姓名，手机号查找到代理人。
	public function salesmanList($user_id=0)
	{
		include_once(ROOT_PATH . 'languages/zh_cn/admin/account_log.php');
		global $_LANG,$check_status_list; 
		
		if(!$user_id)
		{
			$user_id = $_SESSION['user_id'];
		}
		//保单条件 br:yes123,20140906 
		$condition=array();
		$condition['act']="salesman_list";
		$condition['user_name'] = isset ($_REQUEST['user_name']) ? trim($_REQUEST['user_name']) : "";
		$condition['real_name'] = isset ($_REQUEST['real_name']) ? trim($_REQUEST['real_name']) : "";
		$condition['start_time'] = isset ($_REQUEST['start_time']) ? trim($_REQUEST['start_time']) : "";
		$condition['end_time'] = isset ($_REQUEST['end_time']) ? trim($_REQUEST['end_time']) : "";
		$condition['check_status'] = isset ($_REQUEST['check_status']) ? trim($_REQUEST['check_status']) : "";
		$condition['is_disable'] = isset ($_REQUEST['is_disable']) ? trim($_REQUEST['is_disable']) : 0;
		$condition['department_id'] = isset ($_REQUEST['department_id']) ? trim($_REQUEST['department_id']) : 0;
		$condition['rank_id'] = isset ($_REQUEST['rank_id']) ? trim($_REQUEST['rank_id']) : 0;
		$condition['sort'] = isset ($_REQUEST['sort']) ? trim($_REQUEST['sort']) : "order_num";
		$condition['order'] = isset ($_REQUEST['order']) ? trim($_REQUEST['order']) : "DESC";
		$size = isset ($_REQUEST['size']) ? $_REQUEST['size'] : 10;
		
		
		$user_rank = get_user_rank_info(0,ORGANIZATION_USER);
		$where_sql = " WHERE u.institution_id='$user_id' AND user_rank!='".$user_rank['rank_id']."' ";
		$sort=" ORDER BY u.reg_time ";
		$policy_where=" WHERE policy_status='insured' "; //只查询投保成功的
		$account_log_where=" WHERE incoming_type='$_LANG[ji_gou]' ";
		
		if($condition['sort']=='total_premium')
		{
			$sort=" ORDER BY total_premium_user.total_premium ";
		}
		
		if($condition['sort']=='ogran_money_total')
		{
			$sort=" ORDER BY ogran_income_total.ogran_money_total ";
		}
		
		if($condition['sort']=='policy_num')
		{
			$sort=" ORDER BY total_premium_user.policy_num ";
		}
		
		
		if($condition['user_name']!=''){
				
			$where_sql.=" AND u.user_name LIKE '%$condition[user_name]%'";
		}
		
		if($condition['real_name']!=''){
				
			$where_sql.=" AND u.real_name LIKE '%$condition[real_name]%'";
		}
		
		if($condition['start_time']){
			$policy_where.=" AND dateline>".strtotime($condition['start_time']);
			$account_log_where.=" AND change_time>".strtotime($condition['start_time']);
		}
		
		if($condition['end_time']){
			$policy_where.=" AND dateline<".strtotime($condition['end_time']);
			$account_log_where.=" AND change_time<".strtotime($condition['end_time']);
		}
		
		
		if($condition['check_status']){
			$where_sql.=" AND u.check_status='$condition[check_status]' ";
			
		}
		
		if($condition['is_disable']){
			if($condition['is_disable']==2){
				$where_sql.=" AND u.is_disable=0 ";
			}
			else
			{
				$where_sql.=" AND u.is_disable='$condition[is_disable]'";
			}	
		}
		
		if($condition['department_id'])
		{
			$where_sql.=" AND u.department_id='$condition[department_id]'";
		}
		if($condition['rank_id'])
		{
			$where_sql.=" AND u.user_rank='$condition[rank_id]'";
		}
		
		
		$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		
		$record_count_sql = "SELECT count(u.user_id) FROM " .$GLOBALS['ecs']->table('users'). "AS u " .
				" LEFT JOIN ".$GLOBALS['ecs']->table('user_info')."AS ui ON u.user_id=ui.uid ";
		
		$record_count = $GLOBALS['db']->getOne($record_count_sql.$where_sql);
        $sql  = "SELECT
			  u.user_id,
			  u.user_name,
			  u.real_name, 
			  u.is_disable,
			  u.user_rank,
			  u.check_status,
			  u.reg_time,
			  u.parent_id,
			  u.department_id,
			  total_premium_user.total_premium,
			  total_premium_user.policy_num,
			  ogran_income_total.ogran_money_total
			FROM bx_users u
			  LEFT JOIN (SELECT agent_uid,COUNT(policy_id) AS policy_num,SUM(total_premium) AS total_premium
			             FROM t_insurance_policy $policy_where GROUP BY agent_uid) AS total_premium_user
			    ON total_premium_user.agent_uid = u.user_id 
			  LEFT JOIN (SELECT
				       cname,
			               SUM(user_money)   AS ogran_money_total
			             FROM bx_account_log $account_log_where
			             GROUP BY user_id) AS ogran_income_total
			    ON ogran_income_total.cname = u.user_id
			$where_sql $sort $condition[order]";
		
		ss_log('会员列表：'.$sql);
		$pager  = get_pager('user.php', $condition, $record_count, $page,$size);
		$res = $GLOBALS['db']->selectLimit($sql, $pager['size'], $pager['start']);
		
		$user_list=array();
		while ($row = $GLOBALS['db']->fetchRow($res)) 
		{

			$row['reg_time'] = date('Y-m-d H:i',$row['reg_time']);
			$row['check_status_str'] =  $check_status_list[$row['check_status']];
			//获取推荐人
			if($row['parent_id'])
			{
				if($row['parent_id']==$_SESSION['user_id'])
				{
					$row['parent_name']=$_SESSION['real_name'];
				}
				else
				{
					$sql="SELECT user_name,real_name FROM bx_users WHERE user_id='$row[parent_id]'";
					$temp_parent = $GLOBALS['db']->getRow($sql);
					if($temp_parent)
					{
						if($temp_parent['real_name'])
						{
							$row['parent_name']=$temp_parent['real_name'];
						}
						else
						{
							$row['parent_name']=$temp_parent['user_name'];
						}
					}
				}
			}
			
			//获取不通过的原因
			if($row['check_status']==NO_PASS_CHECK_STATUS)
			{
				$sql = " SELECT check_result FROM bx_user_info WHERE uid='$row[user_id]'";
				$row['check_result'] = $GLOBALS['db']->getOne($sql);
			}
			
			
			//获取部门
			if($row['department_id'])
			{	
				$sql="SELECT department_name FROM bx_organ_department WHERE department_id='$row[department_id]'";
				$row['department_name'] = $GLOBALS['db']->getOne($sql);
			}
			
			//获取分类
			if($row['user_rank'])
			{	
				$sql="SELECT rank_name FROM bx_user_rank WHERE rank_id='$row[user_rank]'";
				$row['rank_name'] = $GLOBALS['db']->getOne($sql);
			}
			
			   
			$user_list[] = $row;
		}
		
		
		//获取总计
		$sql = "SELECT COUNT(policy_id) AS total_policy_num,SUM(total_premium) AS total_total_premium 
				FROM t_insurance_policy $policy_where AND agent_uid IN 
				(SELECT user_id FROM bx_users u  $where_sql)";
		$total_data = $GLOBALS['db']->getRow($sql);
		
		//获取部门列表
		$sql="SELECT * FROM bx_organ_department WHERE organ_id='$_SESSION[user_id]'";
		$organ_department_list = $GLOBALS['db']->getAll($sql);
		
		return array('user_list'=>$user_list,'record_count'=>$record_count,'pager'=>$pager,'condition'=>$condition,'organ_department_list'=>$organ_department_list,'total_data'=>$total_data);
		
	}
	//add yes123 2015-05-13渠道下的子渠道列表 可通过代理人姓名，手机号查找到代理人。
	public function childOrganList($user_id=0)
	{
		include_once(ROOT_PATH . 'languages/zh_cn/admin/account_log.php');
		global $_LANG,$check_status_list; 
		
		if(!$user_id)
		{
			$user_id = $_SESSION['user_id'];
		}
		//保单条件 br:yes123,20140906 
		$condition=array();
		$condition['act']="salesman_list";
		$condition['d_name'] = isset ($_REQUEST['d_name']) ? trim($_REQUEST['d_name']) : "";
		$condition['d_contacts'] = isset ($_REQUEST['d_contacts']) ? trim($_REQUEST['d_contacts']) : "";
		$condition['is_disable'] = isset ($_REQUEST['is_disable']) ? intval($_REQUEST['is_disable']) :0;
		$condition['start_time'] = isset ($_REQUEST['start_time']) ? trim($_REQUEST['start_time']) : "";
		$condition['end_time'] = isset ($_REQUEST['end_time']) ? trim($_REQUEST['end_time']) : "";
		$condition['check_status'] = isset ($_REQUEST['check_status']) ? trim($_REQUEST['check_status']) : "";
		$condition['sort'] = isset ($_REQUEST['sort']) ? trim($_REQUEST['sort']) : "order_num";
		$condition['order'] = isset ($_REQUEST['order']) ? trim($_REQUEST['order']) : "DESC";
		$size = isset ($_REQUEST['size']) ? $_REQUEST['size'] : 10;
		
		$user_rank = get_user_rank_info(0,ORGANIZATION_USER);
		
		
		$where_sql = " WHERE u.institution_id='$user_id' AND u.user_rank='".$user_rank['rank_id']."'";
		$sort=" ORDER BY u.reg_time ";

		
		if($condition['sort']=='total_premium')
		{
			$sort=" ORDER BY total_premium_user.total_premium ";
		}
		
		if($condition['sort']=='ogran_money_total')
		{
			$sort=" ORDER BY ogran_income_total.ogran_money_total ";
		}
		
		if($condition['sort']=='policy_num')
		{
			$sort=" ORDER BY total_premium_user.policy_num ";
		}
		
		
		if($condition['user_name']!=''){
				
			$where_sql.=" AND u.user_name LIKE '%$condition[user_name]%'";
		}
		
		if($condition['real_name']!=''){
				
			$where_sql.=" AND u.real_name LIKE '%$condition[real_name]%'";
		}
		
		if($condition['start_time']){
			$policy_where.=" AND dateline>".strtotime($condition['start_time']);
			$account_log_where.=" AND change_time>".strtotime($condition['start_time']);
		}
		
		if($condition['end_time']){
			$policy_where.=" AND dateline<".strtotime($condition['end_time']);
			$account_log_where.=" AND change_time<".strtotime($condition['end_time']);
		}
		
		
		if($condition['check_status']!=''){
			$where_sql.=" AND u.check_status='$condition[check_status]'";
		}
		
		if($condition['is_disable']){
			if($condition['is_disable']==2){
				$where_sql.=" AND u.is_disable=0 ";
			}
			else
			{
				$where_sql.=" AND u.is_disable='$condition[is_disable]'";
			}	
		}
		
		$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		
		$record_count_sql = " SELECT count(u.user_id) FROM bx_users u LEFT JOIN bx_distributor d ON u.user_id=d.d_uid ".$where_sql;
		$record_count = $GLOBALS['db']->getOne($record_count_sql);
		
        $sql  = "SELECT
			  u.*,
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
			$where_sql $sort $condition[order]";

	
		$pager  = get_pager('user.php', $condition, $record_count, $page,$size);
		$res = $GLOBALS['db']->selectLimit($sql, $pager['size'], $pager['start']);
		
		$user_list=array();
		while ($row = $GLOBALS['db']->fetchRow($res)) 
		{
		   $row['reg_time'] = date('Y-m-d H:i',$row['reg_time']);
			
		   //上级渠道
		   if($row['institution_id'])
	       {
	       		$sql = "SELECT d_name FROM bx_distributor WHERE d_uid = '$row[institution_id]'";
	        	$row['parent_d_name'] = $GLOBALS['db']->getOne($sql);
	       }
		   
		   
		   $distrbutor_obj = new Distrbutor;
		   $data = $distrbutor_obj->get_accumulate_data_by_organ($row['user_id']);
		   $row['user_num'] = empty($data['user_num'])?0:$data['user_num'];
		   $row['policy_num'] = empty($data['policy_num'])?0:$data['policy_num'];
		   $row['total_premium'] = empty($data['total_premium'])?0:$data['total_premium'];
		   $row['child_organ_num'] = empty($data['organ_num'])?0:$data['organ_num']; 
		   
		   if(!$row['ogran_money_total'])
		   {
		   		$row['ogran_money_total']= 0;	
		   }
		   
		   $row['check_status_str'] = $check_status_list[$row['check_status']];
		   
		   			//获取不通过的原因
			if($row['check_status']==NO_PASS_CHECK_STATUS)
			{
				$sql = " SELECT check_result FROM bx_user_info WHERE uid='$row[user_id]'";
				$row['check_result'] = $GLOBALS['db']->getOne($sql);
			}
		   
		   
		   $user_list[] = $row;
/*		   下面的层级渠道了
		   $sql = " SELECT user_id FROM bx_users WHERE institution_id=$row[user_id] AND user_type='".ORGANIZATION_USER."'";
		   $temp_ids = $GLOBALS['db']->getAll($sql);
		   if($temp_ids)
		   {
		   		$temp_res = $this->childOrganList($row['user_id']);
		   		if($temp_res['user_list'])
		   		{
		   			foreach ( $temp_res['user_list'] as $key => $organ ) 
		   			{
       					$user_list[] = $organ;
					}
		   		}		
		   	
		   }*/
		
		}

		
				
		ss_log("获取子渠道record_count:".$record_count);
		return array('user_list'=>$user_list,'record_count'=>$record_count,'pager'=>$pager,'condition'=>$condition);
		
	}
	
	
	//添加新的会员
	public function addSalesman()
	{
		global $_LANG;
		$last_time = date('Y-m-d H:i:s', time());
		//新增，还是编辑
		$type = isset($_POST['type'])?$_POST['type']:'edit';
		
		//会员ID
		$salesman_id = isset($_POST['salesman_id'])?$_POST['salesman_id']:0;
		//所属部门
		$department_id = empty ($_POST['department_id']) ? 0 : $_POST['department_id'];
		
		$real_name = empty ($_POST['real_name']) ? '' : $_POST['real_name'];
		$email = isset ($_POST['email']) ? trim($_POST['email']) : '';
		$mobile_phone = isset ($_POST['mobile_phone']) ? trim($_POST['mobile_phone']) : '';
		$password = isset ($_POST['password']) ? trim($_POST['password']) : '';
		$birthday = isset ($_POST['birthday']) ? trim($_POST['birthday']) : '';
		$sex = isset ($_POST['sex']) ? trim($_POST['sex']) : '';
		//user_info表的信息	
		//证件类型
		$user_info['CertificatesType'] = isset ($_POST['CertificatesType']) ? trim($_POST['CertificatesType']) : '';
		$user_info['CardId'] = isset ($_POST['CardId']) ? trim($_POST['CardId']) : '';

		//证书类型
		$user_info['Category'] = isset ($_POST['Category']) ? trim($_POST['Category']) : '';
		//证书编号
		$user_info['CertificateNumber'] = isset ($_POST['CertificateNumber']) ? trim($_POST['CertificateNumber']) : '';

		//证书有效期 
		$user_info['certificate_expiration_date'] = isset ($_POST['certificate_expiration_date']) ? trim($_POST['certificate_expiration_date']) : '';
		//add yes12 2014-12-31 对永久有效另外处理
		$certificate_expiration_date_no = isset ($_POST['certificate_expiration_date_no']) ? trim($_POST['certificate_expiration_date_no']) : '';
		if ($certificate_expiration_date_no) {
			$user_info['certificate_expiration_date'] = $certificate_expiration_date_no;
		}

		//modify yes123 2014-12-16 为了验证地区，把这部分代码移动到上面来了
		//所在省份
		$user_info['Province'] = isset ($_POST['province']) ? trim($_POST['province']) : '';
		//所在市/区
		$user_info['city'] = isset ($_POST['city']) ? trim($_POST['city']) : '';
		//县
		$user_info['district'] = isset ($_POST['district']) ? trim($_POST['district']) : '';
		//家庭住址
		$user_info['address'] = isset ($_POST['address']) ? trim($_POST['address']) : '';
		//邮政编码
		$user_info['ZoneCode'] = isset ($_POST['ZoneCode']) ? trim($_POST['ZoneCode']) : '';
		
		//校验
		$user['user_id']=$salesman_id;
		$user['user_name']=$mobile_phone;
		$user['real_name']=$real_name;
		$user['mobile_phone']=$mobile_phone;
		$user['email']=$email;
		$user['user_info'] = $user_info;
		$res = $this->checkUserInfo($user,$type);
		if($res)
		{
			if($res['code']==1)
			{
				return $res;
			}
		}
		

		
		//保存
		if($type=='add')
		{
			//校验密码
			if($password)
			{
				if(strlen($password)<6)
				{
					return array('code'=>1,'msg'=>'密码长度必须大于6位！');
				}
			}
			else
			{
				return array('code'=>1,'msg'=>'请输入初始密码！');
			}
			
			
			//基本表
			$md5_password=md5($password);
			$reg_time = time();
			$sql = 'INSERT INTO ' . $GLOBALS['ecs']->table('users') . " (user_name,real_name,password, email, mobile_phone,parent_id,institution_id,birthday,sex,reg_time,user_source,department_id) " .
					" VALUES ('$mobile_phone','$real_name','$md5_password','$email','$mobile_phone','$_SESSION[user_id]','$_SESSION[user_id]','$$birthday','$sex','$reg_time',1,$department_id)";
			ss_log('添加会员 users：'.$sql);
			$GLOBALS['db']->query($sql);
			$new_salesman_id = $GLOBALS['db']->insert_id(); //发票ID
			//user_info表
			$sql = 'INSERT INTO ' . $GLOBALS['ecs']->table('user_info') . " (`uid`, `CertificatesType`, `CardId`, `Category`, `CertificateNumber`, `Province`, `city`, `district`,`address`, `ZoneCode`,`certificate_expiration_date`) " .
				"VALUES (" . $new_salesman_id . ", '$user_info[CertificatesType]','$user_info[CardId]','$user_info[Category]','$user_info[CertificateNumber]'," .
				"'$user_info[Province]','$user_info[city]','$user_info[district]','$user_info[address]','$user_info[ZoneCode]','$user_info[certificate_expiration_date]')";
			ss_log('添加会员user_info：'.$sql);
			if($GLOBALS['db']->query($sql))
			{
				return array('code'=>0,'msg'=>'添加成功！');
			}		
		}
		else if($type=='edit')
		{
					
			//申请审核
			if ($_POST['check_status'] == PENDING_CHECK_STATUS) {
				$this->applyCheck($salesman_id);
			}
			
			$sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET real_name='$real_name'," .
					" email='$email',birthday = '$birthday' , " .
		            " sex='$sex' ,last_time='$last_time',department_id='$department_id' WHERE user_id=" . $salesman_id;
			ss_log('更新会员users :' . $sql);
			$r = $GLOBALS['db']->query($sql);
			if($r)
			{
				$r = $this->editUserInfoTable($salesman_id,$user_info);
				if($r)
				{
					if($_POST['check_status']==PENDING_CHECK_STATUS)
					{
						return array('code'=>0,'msg'=>$_LANG['submit_check_success']);
					}
					else
					{
						return array('code'=>0,'msg'=>'更新成功！');
					}
				}
				else
				{
					return array('code'=>1,'msg'=>'更新失败！');
				}
			}
			else
			{
				return array('code'=>1,'msg'=>'更新失败！');
			}
	
		}
			
		
	}
	
	//添加子渠道
	public function addChildOrgan()
	{
		$name = isset ($_POST['name']) ? trim($_POST['name']) : '';
		//$identificationcode = isset ($_POST['identificationcode']) ? trim($_POST['identificationcode']) : '';
		$type = isset ($_POST['type']) ? trim($_POST['type']) : '';
		//$qualificationNumber = isset ($_POST['qualificationNumber']) ? trim($_POST['qualificationNumber']) : '';
		$contacts = isset ($_POST['contacts']) ? trim($_POST['contacts']) : '';
		$mobilePhone = isset ($_POST['mobilePhone']) ? trim($_POST['mobilePhone']) : '';
		$email = isset ($_POST['email']) ? trim($_POST['email']) : '';
		//$qq = isset ($_POST['qq']) ? trim($_POST['qq']) : '';
		$province = isset ($_POST['province']) ? trim($_POST['province']) : '';
		$city = isset ($_POST['city']) ? trim($_POST['city']) : '';
		$district = isset ($_POST['district']) ? trim($_POST['district']) : '';
		$d_zone = $province.','.$city.','.$district;
		$address = isset ($_POST['address']) ? trim($_POST['address']) : '';
		$password = isset ($_POST['password']) ? trim($_POST['password']) : '';
		
/*		if(empty($identificationcode)){
		   $msg='企业组织代码不能为空！';
		}
		if(empty($qualificationNumber)){
		   $msg='从业资格证书不能为空！';
		}
		
		if(empty($qq)){
		  $msg='QQ不能为空！';
		}*/
		if(empty($contacts)){
		   $msg='联系人不能为空！';
		}
		if(empty($mobilePhone)){
		  $msg='移动电话不能为空！';
		}

		if(empty($email)){
		  $msg='Email地址不能为空！';
		}
		
		if($msg)
		{
			return array('code'=>1,'msg'=>$msg);
		}

		$validate_msg = $this->validate_data();
		if($validate_msg){
		  	$msg = $validate_msg;
		}else{
			//基本表
			$md5_password=md5($password);
			$reg_time = time();
			$sql = 'INSERT INTO ' . $GLOBALS['ecs']->table('users') . " (user_name,password, email, mobile_phone,parent_id,institution_id,birthday,sex,reg_time,user_source) " .
					" VALUES ('$mobile_phone','$md5_password','$email','$mobile_phone','$_SESSION[user_id]','$_SESSION[user_id]','$$birthday','$sex','$reg_time',1)";
			ss_log('添加会员 users：'.$sql);
			$GLOBALS['db']->query($sql);
			$new_salesman_id = $GLOBALS['db']->insert_id(); //发票ID
			//user_info表
			$sql = 'INSERT INTO ' . $GLOBALS['ecs']->table('user_info') . " (`uid`, `CertificatesType`, `CardId`, `Category`, `CertificateNumber`, `Province`, `city`, `district`,`address`, `ZoneCode`,`certificate_expiration_date`) " .
				   "VALUES (" . $new_salesman_id . ", '$user_info[card_type]','$user_info[card_id]','$user_info[Category]','$user_info[CertificateNumber]'," .
				   "'$user_info[province]','$user_info[city]','$user_info[district]','$user_info[address]','$user_info[ZoneCode]','$user_info[certificate_expiration_date]')";
			ss_log('添加会员user_info：'.$sql);
			
			
			//modify yes123 2015-03-27 取消企业组织代码等
			$sql = "INSERT INTO ". $GLOBALS['ecs']->table('distributor') ." (d_uid, d_name, d_type, d_contacts, d_mobilePhone, d_email, d_zone, d_address) VALUES ('$user_id', '$name',  '$type','$contacts', '$mobilePhone', '$email','$d_zone', '$address')";
			ss_log(__FUNCTION__.",INSERT distributor:".$sql);
			$GLOBALS['db']->query($sql);
	
			
			$code=0;
			
			
			if($check_status){
				$msg='提交成功，请等待管理员审核！';
			}else{
				$msg='修改成功!';
			
			}
		}
		
		
		//保存
		if($type=='add')
		{
			//校验密码
			if($password)
			{
				if(strlen($password)<6)
				{
					return array('code'=>1,$msg='密码长度必须大于6位！');
				}
			}
			else
			{
				return array('code'=>1,$msg='请输入初始密码！');
			}
			
			

			
			
			
			
			if($GLOBALS['db']->query($sql))
			{
				return array('code'=>0,'msg'=>'添加成功！');
			}		
		}
			
		
	}
	
	
	//移除渠道和会员的关系
	public function removeSalesman()
	{
		$user_id = $_SESSION['user_id'];
		$salesman_id = isset($_REQUEST['salesman_id'])?$_REQUEST['salesman_id']:0;	
		
		$sql = "SELECT institution_id FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_id=".$salesman_id ;
		
		$institution_id = $GLOBALS['db']->getOne($sql);		
		if($institution_id==$user_id)
		{
			$sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET institution_id =0 WHERE user_id=".$salesman_id ;
			ss_log("removeSalesman:".$sql);
			return $GLOBALS['db']->query($sql);	
		}
		else
		{
			
			ss_log('removeSalesman 非法操作！sql:'.$sql.",user_id=$user_id,institution_id=$institution_id");
		}
	
	}
	
	
	//禁用或者启用会员
	public function disableOrEnabledSalesman($status)
	{
		$user_id = $_SESSION['user_id'];
		$salesman_id = isset($_REQUEST['salesman_id'])?$_REQUEST['salesman_id']:0;	
		
		$sql = "SELECT institution_id FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_id=".$salesman_id ;
		$institution_id = $GLOBALS['db']->getOne($sql);		
		if($institution_id==$user_id || $_SESSION['admin_id'])
		{
			$sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET is_disable =$status WHERE user_id=".$salesman_id ;
			ss_log("disableOrEnabledSalesman:".$sql);
			if($GLOBALS['db']->query($sql))
			{
				//禁用后把下面的会员都禁用，启用把下面的会员都启用
				$sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET is_disable =$status WHERE institution_id=".$salesman_id ;
				ss_log("禁用或者启用渠道下的会员".$sql);
				return $GLOBALS['db']->query($sql);
			}	
		}
		else
		{
			
			ss_log('disableOrEnabledSalesman 非法操作！institution_id:'.$institution_id.",user_id:".$user_id);
		}
	
	}

	
	
	//查看会员明细
	public function salesmanDetail()
	{
		$salesman_id = isset($_GET['salesman_id'])?$_GET['salesman_id']:0;
		$user_info = get_profile($salesman_id);
		$arr['user_info'] =$user_info;
		$region_list = get_region_list($user_info['Province'],$user_info['city']);
		$arr['city_list'] = $region_list['city_list'];
		$arr['district_list'] = $region_list['district_list'];
		$arr['province_list'] = $region_list['province_list'];
		
		//获取部门列表
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('organ_department') . " WHERE organ_id=$_SESSION[user_id]";
		$arr['organ_department_list'] = $GLOBALS['db']->getAll($sql);
		
		return $arr;
	}
	//查看子渠道明细
	public function childOrganDetail()
	{
		$salesman_id = isset($_GET['salesman_id'])?$_GET['salesman_id']:0;
		$user_info = get_profile($salesman_id);
		$arr['user_info'] =$user_info;
		//start add by wangcya,20141117
		if ($user_info['check_status'] == CHECKED_CHECK_STATUS) {
			//如果已经审核通过，通过ID获取地区名称显示
			$Province_my_id = intval($user_info['Province']);

			$sql = "SELECT region_name FROM " . $GLOBALS['ecs']->table('region') . " WHERE parent_id = 1 AND region_id = '$Province_my_id'"; //china
			$province_name_my = $GLOBALS['db']->getOne($sql);
			$arr['province_name_my'] = $province_name_my;

			$city_my_id = intval($user_info['city']);
			$sql = "SELECT region_name FROM " . $GLOBALS['ecs']->table('region') . " WHERE parent_id = '$user_info[Province]' AND region_id = '$city_my_id'"; //china
			$city_name_my = $GLOBALS['db']->getOne($sql);
			$arr['city_name_my'] = $city_name_my;
			
			
			$district_my_id = intval($user_info['district']);
			$sql = "SELECT region_name FROM " . $GLOBALS['ecs']->table('region') . " WHERE parent_id = '$user_info[city]' AND region_id = '$district_my_id'"; //china
			$district_name_my = $GLOBALS['db']->getOne($sql);
			$arr['district_name_my'] = $district_name_my;
			
		}
		//end add by wangcya,20141117
		else {
			$province_html = array ();
			$city_html = array ();
			$sql = "SELECT region_id, region_name FROM " . $GLOBALS['ecs']->table('region') . " WHERE parent_id = 1"; //china
			$provinces = $GLOBALS['db']->getAll($sql);

			// start modify yes123 2014-12-16 重做地区选择
			if ($user_info['Province']) {
				$sql = "SELECT region_id, region_name FROM " . $GLOBALS['ecs']->table('region') . " WHERE parent_id = $user_info[Province]";
				$citys = $GLOBALS['db']->getAll($sql);

				$arr['citys'] = $citys;
			}

			if ($user_info['city']) {
				$sql = "SELECT region_id, region_name FROM " . $GLOBALS['ecs']->table('region') . " WHERE parent_id = $user_info[city]";
				$districts = $GLOBALS['db']->getAll($sql);

				$arr['districts'] = $districts;
			}
			$arr['provinces'] = $provinces;
			// end modify yes123 2014-12-16 重做地区选择

		} //end
		
		return $arr;
	}
	
	
	
	function organGoodsList($user_id)
	{
		//保单条件 br:yes123,20140906 
		$condition=array();
		$condition['act']="salesman_list";
		$condition['attribute_name'] = isset ($_REQUEST['attribute_name']) ? trim($_REQUEST['attribute_name']) : "";
		$size = isset ($_REQUEST['size']) ? $_REQUEST['size'] : 10;

		$where_sql = " WHERE oirc.institution_id='$user_id' ";
		

		
		
		if($condition['attribute_name']!=''){
				
			$where_sql.=" AND ipa.attribute_name LIKE '%$condition[attribute_name]%'";
		}
		
		$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		
		$record_count_sql = "SELECT count(oirc.id) FROM bx_organ_ipa_rate_config oirc 
			LEFT JOIN (SELECT u.user_id,u.user_name,d.d_name,d.d_identificationcode FROM bx_users u LEFT JOIN bx_distributor d ON u.user_id=d.d_uid WHERE is_institution=2) AS organization ON oirc.institution_id=organization.user_id
			LEFT JOIN t_insurance_product_attribute ipa ON ipa.attribute_id=oirc.attribute_id  ";
		
		$record_count = $GLOBALS['db']->getOne($record_count_sql.$where_sql);
		
		$sql="SELECT organization.*,oirc.*,ipa.attribute_name,ipa.insurer_name, ipa.rate_total,ipa.rate_myself,ipa.rate_recommend,ipa.rate_organization AS rate_organization_old  FROM bx_organ_ipa_rate_config oirc 
			  LEFT JOIN (SELECT u.user_id,u.user_name,d.d_name,d.d_identificationcode FROM bx_users u LEFT JOIN bx_distributor d ON u.user_id=d.d_uid WHERE is_institution=2) AS organization ON oirc.institution_id=organization.user_id
			  LEFT JOIN t_insurance_product_attribute ipa ON ipa.attribute_id=oirc.attribute_id $where_sql";
		
		ss_log('可购买产品列表：'.$sql);
		$pager  = get_pager('user.php', $condition, $record_count, $page,$size);
		$res = $GLOBALS['db']->selectLimit($sql, $pager['size'], $pager['start']);
		
		$attribute_list=array();
		while ($row = $GLOBALS['db']->fetchRow($res)) 
		{
			//获取goods_id
			$sql = "SELECT goods_id FROM bx_goods WHERE tid='$row[attribute_id]'";
			$goods_id = $GLOBALS['db']->getOne($sql);
			$row['goods_id'] = $goods_id;
			$rate_organization = doubleval($row['rate_organization']);
			$rate_myself = doubleval($row['rate_myself']);
			$row['rate_organization'] = $rate_organization."%";
			$row['rate_myself'] = $rate_myself."%";
			$attribute_list[] = $row;
		}
		
		return array('attribute_list'=>$attribute_list,'record_count'=>$record_count,'pager'=>$pager,'condition'=>$condition);
		
		
	}
	
	//校验是否重复
	private function checkRepetitionByUsers($column_name,$data,$user_id)
	{
		$sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('users') . " WHERE $column_name = '$data' AND user_id <> '$user_id'";
		return $GLOBALS['db']->getOne($sql);
		 
	}
	
	//校验是否重复
	private function checkRepetitionByUserInfo($column_name,$data,$user_id)
	{
		$sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('user_info') . " WHERE $column_name = '$data' AND uid <> '$user_id'";
		return $GLOBALS['db']->getOne($sql);
		 
	}
	
	
	public function checkUserInfo($user,$type)
	{
		$user_info = $user['user_info'];
		if($type=='add')
		{
			//2.手机号码
			if(!empty($user['mobile_phone']))
			{
				$res_mobile_phone = $this->checkRepetitionByUsers('mobile_phone',$user['mobile_phone'],$user['user_id']);
				if($res_mobile_phone)
				{
					return array('code'=>1,'msg'=>'手机号码已存在，请更换！');
				}
				else
				{
					$res_mobile_phone = $this->checkRepetitionByUsers('user_name',$user['mobile_phone'],$user['user_id']);
					if($res_mobile_phone)
					{
						return array('code'=>1,'msg'=>'手机号码已存在，请更换！');
					}
					
				}
			}
			else
			{
				return array('code'=>1,'msg'=>'手机号码不能为空！');
			}
		
		}
		//3.邮箱
/*		if(!empty($user['email']))
		{
			$res_mobile_phone = $this->checkRepetitionByUsers('email',$user['email'],$user['user_id']);
			if($res_mobile_phone)
			{
				return array('code'=>1,'msg'=>'邮件地址已存在，请更换！');
			}
		}
		else
		{
			return array('code'=>1,'msg'=>'邮件地址不能为空！');
		}*/
		
		//4.身份证号码
/*		if(!empty($user_info['CardId']))
		{
			$res_mobile_phone = $this->checkRepetitionByUserInfo('CardId',$user_info['CardId'],$user['user_id']);
			if($res_mobile_phone)
			{
				return array('code'=>1,'msg'=>'身份证号码已存在，请更换！');
			}
		}
		else
		{
			return array('code'=>1,'msg'=>'身份证号码不能为空！');
		}*/
		
		//5.真名
/*		if(empty($user['real_name']))
		{
			return array('code'=>1,'msg'=>'姓名不能为空！');
		}*/
		
		//6.校验地址
	/*	if(empty($user_info['Province']))
		{
			return array('code'=>1,'msg'=>'省份不能为空！');
		}
		
		if(empty($user_info['city']))
		{
			return array('code'=>1,'msg'=>'城市不能为空！');
		}

		if(empty($user_info['address']))
		{
			return array('code'=>1,'msg'=>'详细地址不能为空！');
		}*/
		
	}
	
	//更新机构ID
	public function updateInstitutionId($reg_user_id=0)
	{
		ss_log("into updateInstitutionId");
		if($reg_user_id)
		{
			//微信端注册时设置parent_id
			$parent_uid = isset($_GET['u']) ? trim($_GET['u']) : 0;
			ss_log("updateInstitutionId parent_uid:".$parent_uid);
			if($parent_uid)
			{	$sql = " UPDATE " . $GLOBALS['ecs']->table('users') . " SET parent_id='$parent_uid' WHERE user_id='$reg_user_id'";  
				ss_log("updateInstitutionId 注册设置parent_id  :".$sql);
				$GLOBALS['db']->query($sql);
			}
			
			
			$sql = "SELECT parent_id FROM bx_users WHERE user_id='$reg_user_id'";
			ss_log("updateInstitutionId get parent_id :".$sql);
			$parent_id = $GLOBALS['db']->getOne($sql);
			
			if($parent_id)
			{
				$parent_info = user_info_by_userid($parent_id);
				
				$parent_info = $GLOBALS['db']->getRow($sql);
				if($parent_info['rank_code']==ORGANIZATION_USER)
				{
					$sql = " UPDATE " . $GLOBALS['ecs']->table('users') . " SET institution_id='$parent_id' WHERE user_id='$reg_user_id'";  
		            ss_log("updateInstitutionId update :".$sql);
					$GLOBALS['db']->query($sql);	
				}
				else if($parent_info['institution_id'])//拿当前用户推荐用户的渠道id
				{
					$sql = " UPDATE " . $GLOBALS['ecs']->table('users') . " SET institution_id='$parent_info[institution_id]' WHERE user_id='$reg_user_id'";  
		            ss_log("update InstitutionId by parent_institution_id update :".$sql);
					$GLOBALS['db']->query($sql);	
				}
			}
			
		}
	}
	
	
	//检测能否提现,并且返回提现必要数据
	public function checkAccountWithdraw($user_id){
		global $money_type_arr;
		$res = array();
		$res['code']=0;
		
		$user = user_info_by_userid($user_id);
		
		$is_withdraw_cfg = get_rank_cfg($user['user_rank'],IS_WITHDRAW_RANK);
		
		if(empty($is_withdraw_cfg))
		{
			$res['code']=1;
			$res['msg'] = "抱歉,当前用户类型不允许提现！";
			return $res;
			
		}

		$withdraw_rule_cfg_list = array();
		$user_withdraw_rule_cfg = $this->getWithdrawCfg('user_money',$user);
		$service_withdraw_rule_cfg = $this->getWithdrawCfg('service_money',$user);
		
		//如果服务费账户为负数，那么加到充值账户里
		if($service_withdraw_rule_cfg['usable_money']<0)
		{
			$user_withdraw_rule_cfg['usable_money']+=$service_withdraw_rule_cfg['usable_money'];
		}
		
		

		$withdraw_rule_cfg_list[]=$user_withdraw_rule_cfg;
		$withdraw_rule_cfg_list[]=$service_withdraw_rule_cfg;
		
		
		$res['withdraw_rule_cfg']=$withdraw_rule_cfg_list;
		
		//3.获取银行列表
		$sql = "SELECT * FROM " .$GLOBALS['ecs']->table('bank'). "WHERE uid = $user_id";
		$bank_list =  $GLOBALS['db']->getAll($sql);
		$res['bank_list'] = $bank_list;
		
		
		//获取手机号码
		//comment by wangcya , 20140929, 如果用户名是手机号码
		if(!preg_match("/^1[1-9][0-9]\d{8}$/",$user['mobile_phone']))
		{
			if($user['is_institution']!=1)
			{
				$sql = "SELECT d_mobilePhone FROM " . $GLOBALS['ecs']->table('distributor') ." WHERE d_uid = '$_SESSION[user_id]'";
				$mobilePhone =  $GLOBALS['db']->getOne($sql);
				$user['mobile_phone'] = $mobilePhone;
			}
			
		}		
		
		$res['user']=$user;
	
		return $res;
		
	}
	
	private function getWithdrawCfg($money_type,$user)
	{
		global $money_type_arr;
		$user_id = $user['user_id'];
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('withdraw_rule_cfg')." WHERE money_type='$money_type'";
		$withdraw_rule_cfg = $GLOBALS['db']->getRow($sql);
		if($withdraw_rule_cfg)
		{
			$end_time = time();//截至日期
			$start_time = date("Y-m",$end_time)."-01"; //开始日期
			$start_time = strtotime($start_time); 
			
			$sql = "SELECT COUNT(id) FROM " . $GLOBALS['ecs']->table('user_account').
	               " WHERE user_id='$user_id' AND amount<0 AND amount_type ='$money_type' AND add_time BETWEEN '$start_time' and '$end_time'";
	        ss_log(__FUNCTION__.",".$money_type."获取已提现次数:".$sql);
			$withdraw_num = $GLOBALS['db']->getOne($sql);
			ss_log(__FUNCTION__.",withdraw_num:".$withdraw_num);
			ss_log(__FUNCTION__.",month_withdraw_num:".$withdraw_rule_cfg['month_withdraw_num']);
			//标记是否可以提现
			if($withdraw_rule_cfg['month_withdraw_num']<=$withdraw_num)
			{
				$withdraw_rule_cfg['is_withdraw'] ='n';
			}
			else
			{
				$withdraw_rule_cfg['is_withdraw'] ='y';
			}
			
			//可提现金额
			$withdraw_rule_cfg['usable_money'] =$user[$money_type];
			
			$withdraw_rule_cfg['money_type_str'] = $money_type_arr[$money_type];
			
			return $withdraw_rule_cfg;
		}
		else
		{
			$sql = "INSERT INTO " . $GLOBALS['ecs']->table('withdraw_rule_cfg') . 
				   " (money_type, month_withdraw_num, min_withdraw_money, max_withdraw_money,income_tax,withdraw_poundage,is_permit_withdraw)
					VALUES ('$money_type',10,50,5000,0,0,'y')";
			$r = $GLOBALS['db']->query($sql);
			if($r)
			{
				return $this->getWithdrawCfg($money_type,$user);
				 
			}
			
		}
		
		
	}
	
	
	
	//计算提现各种费率
	public function countWithdrawRate($surplus){
		$money = $surplus['amount'];
		$money_type = $surplus['money_type'];
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('withdraw_rule_cfg')." WHERE money_type='$money_type'";
		ss_log(__FUNCTION__.",get withdraw_rule_cfg sql:".$sql);
		$withdraw_rule_cfg = $GLOBALS['db']->getRow($sql);

		if($withdraw_rule_cfg)
		{
			$income_tax = $money*$withdraw_rule_cfg['income_tax']/100; //个人所得税
			$withdraw_poundage = $money*$withdraw_rule_cfg['withdraw_poundage']/100;//手续费
			$actual_money = $money-$income_tax-$withdraw_poundage; //实际应到账金额
			
			ss_log(__FUNCTION__.",提现总金额：".$money);
			ss_log(__FUNCTION__.",个人所得税：".$income_tax);
			ss_log(__FUNCTION__.",手续费：".$withdraw_poundage);
			ss_log(__FUNCTION__.",实际应到账金额：".$actual_money);
			return array('income_tax'=>$income_tax,
	 					 'withdraw_poundage'=>$withdraw_poundage,
						 'actual_money'=>$actual_money,
						 'money'=>$money
						 );
		}
		else
		{
			ss_log(__FUNCTION__.',没有查询到withdraw_rule_cfg配置');
			$income_tax = 0; //个人所得税
			$withdraw_poundage = 0;//手续费
			$actual_money = $money; //实际应到账金额
			
			ss_log(__FUNCTION__.",提现总金额：".$money);
			ss_log(__FUNCTION__.",个人所得税：".$income_tax);
			ss_log(__FUNCTION__.",手续费：".$withdraw_poundage);
			ss_log(__FUNCTION__.",实际应到账金额：".$actual_money);
			return array('income_tax'=>$income_tax,
	 					 'withdraw_poundage'=>$withdraw_poundage,
						 'actual_money'=>$actual_money,
						 'money'=>$money
						 );
			
		}
	}
	
	/**
	 *  清除指定后缀的模板缓存或编译文件
	 *
	 * @access  public
	 * @param  int     $user_id          赠送的用户ID
	 * @param  int     $bonus_type       需要删除的文件名，不包含后缀
	 * @param  int     $source_type      代金券来源，例如推荐、注册、订单等等
	 * @param  int     $source_id        来源的ID
	 */
	public function sendBonus($user_id,$bonus_type,$source_type,$source_id)
	{
		$time = time();
		//获取代金券类型 
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('bonus_type').
            "WHERE send_type = '" . $bonus_type . "' " .
            "AND send_start_date <= '$time' " .
            "AND send_end_date >= '$time' ";
		ss_log(__FUNCTION__.",get 代金券类型 sql:".$sql);
		$bonus_type_list = $GLOBALS['db']->getAll($sql);
		
		if($bonus_type_list)
		{
			foreach ( $bonus_type_list as $key => $bonus_type ) {
				/* 向会员代金券表录入数据 */
		        $sql = "INSERT INTO " . $GLOBALS['ecs']->table('user_bonus') .
		                "(bonus_type_id, user_id,source_type,source_id) " .
		                "VALUES ('$bonus_type[type_id]','$user_id',".$source_type.",'$source_id')";
		        ss_log(__FUNCTION__."赠送代金券:".$sql);
		        $GLOBALS['db']->query($sql);
		        
			}
			
		}
		

		
	}
	

}
?>