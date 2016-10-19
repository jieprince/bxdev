<?php

/**
 * 发票处理类
 * yes123 
 * 2014-11-20
 */
include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
include_once (ROOT_PATH . 'baoxian/source/function_debug.php');

class Invoice {

	const INSURER_UNLIKENESS = 1; //保险公司不一致
	const APPLICANT_UNLIKENESS = 2; //投保人不一致

	public $invoice_id;
	public $fp_title;
	public $username; //收件人
	public $tel;
	public $phone;
	public $address;
	public $zonecode;
	public $postscript;
	public $policy_ids;

	//查询条件
	public $user_id;
	public $start_time;
	public $end_time;
	public $insurer_code; //保险公司code
	public $policy_no; //保单号
	public $product_name; //保险产品
	public $user_name; //会员帐号
	public $real_name; //会员姓名
	public $applicant_username; //投保人
	public $assured_fullname; //投保人
	public $date_type; //投保人
	public $receipt_assigned; //受理状态
	
	public $express_company; //快递公司
	public $mail_sn; //邮寄单号
	
	
	public $start;
	public $page_size;
	public $limit;
	public $condition;
	public $where = "";

	function Invoice() {
	}
	//add by yes123    2014-11-20  保存或者编辑发票
	public function saveOrEditInvoice() {
		if (!$this->invoice_id) {
			if (!$this->policy_ids) {
				show_message("异常,请联系管理员!", '', 'user.php?act=warranty_list', 'error', false);
				exit;
			}
	
			$insurance_company = $this->isEqual($this->policy_ids);
			
			if(empty($insurance_company))
			{
				show_message("申请发票异常，请联系管理员！", '', 'user.php?act=warranty_list', 'error', false);
				exit;
			}
			
			if ($insurance_company['code']) {
				if ($insurance_company['code'] == self :: INSURER_UNLIKENESS) {
					show_message("抱歉!所选择的订单产品所属的保险公司不一致,请重新选择!", '', 'user.php?act=warranty_list', 'error', false);
					exit;
				}
	/*
				if ($insurance_company['code'] == self :: APPLICANT_UNLIKENESS) {
					show_message("抱歉!所选择的订单投保人不一致,请重新选择!", '', 'user.php?act=warranty_list', 'error', false);
					exit;
				}*/
			}
			
			
			
			//add yes123 2014-12-08 添加金额总计
			$sql = "SELECT SUM(total_premium) FROM t_insurance_policy  WHERE policy_id IN (".$this->policy_ids.") " ; 
			$order_amount = $GLOBALS['db']->getOne($sql);
			
			//add platformid by dingchaoyang 2014-12-19 则给platform_id赋值
			$ROOT_PATH__= str_replace ( 'includes/class/invoice.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
			include_once ($ROOT_PATH__ . 'api/EBaoApp/platformEnvironment.class.php');
			$platform_id = PlatformEnvironment::getPlatformID();
			//end
			
			//modify yes123 2014-12-08 添加金额总计
			//add by dingchaoyang 2015-1-14
			$insureId = empty($insurance_company[0]['insurer_id'])?0:$insurance_company[0]['insurer_id'];
			//end
			
			//add yes123 2015-05-20 判断是否已经申请过，申请过的退出
			$policy_ids = $this->policy_ids;
			$sql = "SELECT id FROM " . $GLOBALS['ecs']->table('invoice_order_policy') . " WHERE policy_id IN($policy_ids)";
			$ids = $GLOBALS['db']->getAll($sql);
			if($ids)
			{	
				ss_log("重复申请或者操作频繁，请稍后sql:".$sql);
				show_message("重复申请或者操作频繁，请稍后", '', 'user.php?act=warranty_list', 'error', false);	
			}
			
			
			$sql = "INSERT INTO " . $GLOBALS['ecs']->table('receipt') . "(fp_title, username, phone, tel,address,zonecode, postscript,receipt_add_time,user_id,insurer_id,insurer_name,insurer_code,total_premium,platform_id)" .
			" VALUES ('" . $this->fp_title . "','" . $this->username . "','" . $this->phone . "','" .
			$this->tel . "','" . $this->address . "','" . $this->zonecode . "','" .
			$this->postscript . "'," . time() . ", " . $_SESSION['user_id'] . "," . $insureId . ",'" .
			$insurance_company[0]['insurer_name'] . "','" . $insurance_company[0]['insurer_code'] . "','".$order_amount."','".$platform_id."')";
			
			ss_log("save receipt info sql:".$sql);
			$GLOBALS['db']->query($sql);
			$receipt_id = $GLOBALS['db']->insert_id(); //发票ID
			if ($receipt_id) {
				//插入发票对应
				//modify yes123 2014-12-08 添加返回值
				$r = $this->batchInsertInvoiceOrderPolicy($receipt_id, $this->policy_ids);
				
				
				//add yes123 2015-05-13 为了安全，查询是否关联保单了
				$sql = "SELECT id FROM " . $GLOBALS['ecs']->table('invoice_order_policy') . " WHERE invoice_id ='$receipt_id'";
				$ids = $GLOBALS['db']->getAll($sql);
				if(empty($ids))
				{
					$this->deleteInvoice($receipt_id);
					ss_log("insert receipt fail 发票关联保单异常");
					show_message("抱歉!保存发票失败,请联系管理员!", '', 'user.php?act=warranty_list', 'error', false);
					
				}
				
				return $r;
			} else {
				$this->deleteInvoice($receipt_id);
				ss_log("insert receipt fail");
				show_message("抱歉!保存发票失败,请联系管理员!", '', 'user.php?act=warranty_list', 'error', false);
			}
			
			
			

		} else {  //修改
			//为了安全,首先确认下此发票管理员是否已经受理
			$sql = "SELECT receipt_assigned FROM " . $GLOBALS['ecs']->table('receipt') . " WHERE id = " . $this->invoice_id;
			$receipt_assigned = $GLOBALS['db']->getOne($sql);
			if ($receipt_assigned) {
				//说明已经受理过,不能继续;
				show_message("抱歉!此发票已经受理,无法修改!", '', 'user.php?act=warranty_list', 'error', false);
				exit;
			}

			$sql = "UPDATE " . $GLOBALS['ecs']->table('receipt') .
			" SET fp_title='" . $this->fp_title . "',username='" . $this->username . "', phone='" . $this->phone . "'" .
			", tel='" . $this->tel . "', address='" . $this->address . "',zonecode='" . $this->zonecode . "',postscript='" . $this->postscript . "' WHERE id=" . $this->invoice_id;
			$GLOBALS['db']->query($sql);
		}

		return $r;
	}

	//获取发票列表
	public function getInvoiceList() {
		
		//modify yes123 2014-12-06 添加查询受理人
		//$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('receipt') . " WHERE 1=1  " . $this->where . " ORDER BY id DESC ";
		$sql = "SELECT r.*,u.user_name AS handlers FROM " . $GLOBALS['ecs']->table('receipt') .
		 " AS r LEFT JOIN". $GLOBALS['ecs']->table('admin_user') ." AS u ON r.handlers_id = u.user_id " .
		 " LEFT JOIN". $GLOBALS['ecs']->table('invoice_send_info') ." AS isi ON isi.invoice_id = r.id " .
		 " WHERE 1=1  " . $this->where . " ORDER BY r.id DESC ";
		//modify add yes123 2014-12-08 修改查询方式
		$invoice_list =$GLOBALS['db']->SelectLimit($sql, $this->page_size, $this->start);
		//$user_list = $GLOBALS['db']->SelectLimit($sql, $this->page_size, $this->start);
		
		ss_log("invoice_list:".$sql);
		$arr = array ();
		while ($invoice = $GLOBALS['db']->fetchRow($invoice_list)) {
			$arr[] = $invoice;
		}

		$invoice_list = $arr;
		//取保险公司信息
		foreach ($invoice_list as $key => $invoice) {

			//保单ID
			$sql = "SELECT policy_id FROM bx_invoice_order_policy WHERE invoice_id=" . $invoice['id'];
			$policy_id = $GLOBALS['db']->getOne($sql);
			if (!$policy_id) { // 老的发票处理
				$sql = "SELECT order_id,policy_id FROM bx_order_info WHERE receipt_id =" . $invoice['id'];
				
				$order_info = $GLOBALS['db']->getRow($sql);
				
				if($order_info['policy_id'])
				{
					$policy_id = $order_info['policy_id'];
				}
				else
				{
					$sql = "SELECT order_id FROM bx_order_info WHERE receipt_id =" . $invoice['id'];
					
					$order_id = $GLOBALS['db']->getOne($sql);
					if($order_id)
					{
						$sql = "SELECT policy_id FROM t_insurance_policy WHERE order_id =" . $order_id;
						$policy_id = $GLOBALS['db']->getOne($sql);
					}					
			
					
					
				}
				
			}

			//保单
			if($policy_id)
			{
							
				$sql = " SELECT ip.policy_id,ip.attribute_id,ip.applicant_uid,ip.applicant_username," .
						" u.user_name,u.real_name," .
						" ipa.insurer_name,ipa.insurer_id " .
						" FROM t_insurance_policy ip " .
						" LEFT JOIN bx_users u ON ip.agent_uid=u.user_id  " .
						" LEFT JOIN t_insurance_product_attribute ipa ON ipa.attribute_id = ip.attribute_id WHERE policy_id=" . $policy_id;
				
				$insurance_policy = $GLOBALS['db']->getRow($sql);
				//$sql = "SELECT insurer_id,insurer_name FROM t_insurance_product_attribute WHERE attribute_id = ( " . $insurance_policy['attribute_id'] . ")";
				//$insurer = $GLOBALS['db']->getRow($sql);
				$invoice_list[$key]['insurer_id'] = $insurance_policy['insurer_id'];
				$invoice_list[$key]['insurer_name'] = $insurance_policy['insurer_name'];
				
				//add yes123 2014-12-04 查询用户姓名
				$invoice_list[$key]['user_name'] = $insurance_policy['user_name'];
				$invoice_list[$key]['real_name'] = $insurance_policy['real_name'];
				
				$invoice_list[$key]['policy_id'] = $insurance_policy['policy_id'];
				$invoice_list[$key]['applicant_uid'] = $insurance_policy['applicant_uid'];
				$invoice_list[$key]['applicant_username'] = $insurance_policy['applicant_username'];
				
			}
			
			if($invoice['receipt_add_time'])
			{
				$invoice_list[$key]['receipt_add_time'] = date('Y-m-d H:i', $invoice['receipt_add_time']);
			}
			if($invoice['assigned_time']){
				$invoice_list[$key]['assigned_time'] = date('Y-m-d H:i', $invoice['assigned_time']);
			}
			
			//add yes123 2014-12-07 显示订单总记录数和总金额 start
			$sql = "SELECT COUNT(*) FROM bx_invoice_order_policy WHERE invoice_id=" . $invoice['id'];
			$order_count = $GLOBALS['db']->getOne($sql);
			$invoice_list[$key]['order_count'] = $order_count;
		
			$invoice_list[$key]['invoice_total'] = $invoice['total_premium'];
			
			
			//add yes123 2014-12-07 显示订单总记录数和总金额 end

		}

		return $invoice_list;

	}

	//获取发票总数
	public function getInvoiceCount() {
		$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('receipt') . " AS r " .
			   " LEFT JOIN". $GLOBALS['ecs']->table('invoice_send_info') ." AS isi ON isi.invoice_id = r.id  " .
			   " WHERE 1=1 " . $this->where;
		return $GLOBALS['db']->getOne($sql);
	}

	//条件拼接
	public function whereCondition() {
		$this->condition = array ();
		if ($this->fp_title) {
			$this->where .= " AND fp_title LIKE '%" . $this->fp_title . "%'";
			$this->condition['fp_title'] = $this->fp_title;
		}

		if ($this->tel) {
			$this->where .= " AND r.tel= '" . $this->tel . "'";
			$this->condition['tel'] = $this->tel;
		}

		if ($this->phone) {
			$this->where .= " AND r.phone= '" . $this->phone . "'";
			$this->condition['phone'] = $this->phone;
		}
		if ($this->address) {
			$this->where .= " AND r.address LIKE '%" . $this->address . "%'";
			$this->condition['address'] = $this->address;
		}
		if ($this->zonecode) {
			$this->where .= " AND r.zonecode= '" . $this->zonecode . "'";
			$this->condition['zonecode'] = $this->zonecode;
		}

		if ($this->postscript) {
			$this->where .= " AND r.postscript LIKE '%" . $this->postscript . "%'";
			$this->condition['postscript'] = $this->postscript;
		}

		if ($this->user_id) {
			$this->where .= " AND r.user_id= '" . $this->user_id . "'";
			$this->condition['user_id'] = $this->user_id;
		}

		/*	if($this->start_time){
				$this->where.=" AND receipt_add_time > '".strtotime($this->start_time)."'";
				$this->condition['start_time'] = $this->start_time;
			}
			
			if($this->end_time){
				$this->where.=" AND receipt_add_time < '".strtotime($this->end_time)."'";
				$this->condition['end_time'] = $this->end_time;
			}*/

		if ($this->insurer_code) {
			$this->where .= " AND r.insurer_code ='" . $this->insurer_code . "'";
			$this->condition['insurer_code'] = $this->insurer_code;
		}

		
		//add yes123 2014-12-16  快递信息		
		if ($this->express_company) {
			$this->where .= " AND isi.express_company LIKE '%" . $this->express_company . "%'";
			$this->condition['express_company'] = $this->express_company;
		}
		
		if ($this->mail_sn) {
			$this->where .= " AND isi.mail_sn LIKE '%" . $this->mail_sn . "%'";
			$this->condition['mail_sn'] = $this->mail_sn;
		}

		if ($this->order_sn) {
			$sql = "SELECT invoice_id  FROM " . $GLOBALS['ecs']->table('invoice_order_policy') . " WHERE order_sn = '" . $this->order_sn . "'";
			$invoice_id = $GLOBALS['db']->getOne($sql);
			if ($invoice_id) {
				$this->where .= " AND id= " . $invoice_id;
			} else {
				$this->where .= " AND id= 0";
			}

			$this->condition['order_sn'] = $this->order_sn;
		}

		if ($this->policy_no) {

			$sql = "SELECT invoice_id  FROM " . $GLOBALS['ecs']->table('invoice_order_policy') . " WHERE policy_no = '" . $this->policy_no . "'";
			$invoice_id = $GLOBALS['db']->getOne($sql);

			if ($invoice_id) {
				$this->where .= " AND r.id ='" . $invoice_id . "'";
			} else {
				$this->where .= " AND r.id =0";
			}

		}

		//保险产品
		if ($this->product_name) {

			$sql = "SELECT policy_id  FROM t_insurance_policy WHERE attribute_name = '" . $this->product_name . "'";
			$policy_ids = $GLOBALS['db']->getAll($sql);

			$policy_ids_str = CommonUtils :: arrToStr($policy_ids, "policy_id");

			if ($policy_ids_str) {

				$sql = "SELECT invoice_id  FROM " . $GLOBALS['ecs']->table('invoice_order_policy') . " WHERE policy_id IN (" . $policy_ids_str . ")";
				$invoice_id_arr = $GLOBALS['db']->getAll($sql);
				$invoice_ids = CommonUtils :: arrToStr($invoice_id_arr, "invoice_id");

				if ($invoice_ids) {
					$this->where .= " AND id IN(" . $invoice_ids . ")";
					$this->condition['product_name'] = $this->product_name;
				} else {
					$this->where .= " AND r.id =0";
				}

			} else {
				$this->where .= " AND r.id =0";
			}

		}

		//代理人帐号
		if ($this->user_name) {

			$sql = "SELECT user_id  FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_name = '" . $this->user_name . "'";
			$user_id = $GLOBALS['db']->getOne($sql);

			if ($user_id) {
				$this->where .= " AND r.user_id= " . $user_id;
				$this->condition['user_name'] = $this->user_name;
			} else {
				$this->where .= " AND r.user_id= 0";
			}

		}

		//代理人姓名
		if ($this->real_name) {

			$sql = "SELECT user_id  FROM " . $GLOBALS['ecs']->table('users') . " WHERE real_name = '" . $this->real_name . "'";
			
			$user_id_arr = $GLOBALS['db']->getAll($sql);
			if ($user_id_arr) {
				$user_ids = CommonUtils :: arrToStr($user_id_arr, "user_id");

				if ($user_ids) {
					$this->where .= " AND r.user_id IN(" . $user_ids . ") ";
					$this->condition['real_name'] = $this->real_name;
				} else {
					$this->where .= " AND r.user_id= 0";
				}

			} else {
				$this->where .= " AND r.user_id= 0";
			}

		}

		//投保人
		if ($this->applicant_username) {

			$sql = "SELECT policy_id  FROM t_insurance_policy WHERE applicant_username = '" . $this->applicant_username . "'";
			$policy_ids = $GLOBALS['db']->getAll($sql);
			$policy_ids = CommonUtils :: arrToStr($policy_ids, "policy_id");
			if ($policy_ids) {
				$sql = "SELECT invoice_id  FROM " . $GLOBALS['ecs']->table('invoice_order_policy') . " WHERE policy_id IN(" . $policy_ids . ")";
				$invoice_ids = $GLOBALS['db']->getAll($sql);
				$invoice_ids = CommonUtils :: arrToStr($invoice_ids, "invoice_id");
				if ($invoice_ids) {
					$this->where .= " AND r.id IN(" . $invoice_ids . ")";
					$this->condition['applicant_username'] = $this->applicant_username;
				} else {
					$this->where .= " AND r.id =0 ";
				}

			} else {
				$this->where .= " AND r.id =0 ";
			}

		}

		//被保险人
		if ($this->assured_fullname) {
			$temp_where = " ";
			$temp_where .= " AND fullname='" . $this->assured_fullname . "'";

			$uid_by_uname_sql = "SELECT uid FROM t_user_info WHERE 1=1 " . $temp_where;
			$uids = $GLOBALS['db']->getAll($uid_by_uname_sql);

			$uids = CommonUtils :: arrToStr($uids, "uid");

			if ($uids) {
				$sql = "SELECT policy_id FROM t_insurance_policy_subject WHERE policy_subject_id in(" .
				"SELECT policy_subject_id FROM t_insurance_policy_subject_insurant_user WHERE uid in(" .
				$uids . "))";
				$policy_ids = $GLOBALS['db']->getAll($sql);

				$policy_ids = CommonUtils :: arrToStr($policy_ids, "policy_id");

				if ($policy_ids) {
					$sql = "SELECT invoice_id  FROM " . $GLOBALS['ecs']->table('invoice_order_policy') . " WHERE policy_id IN(" . $policy_ids . ")";
					$invoice_ids = $GLOBALS['db']->getAll($sql);
					$invoice_ids = CommonUtils :: arrToStr($invoice_ids, "invoice_id");
					if ($invoice_ids) {
						$this->where .= " AND id IN(" . $invoice_ids . ")";
						$this->condition['applicant_username'] = $this->applicant_username;
					} else {
						$this->where .= " AND r.id =0 ";
					}
				} else {
					$this->where .= " AND r.id =0 ";
				}

			} else {
				$this->where .= " AND r.id =0 ";
			}

		}

		//处理日期

		if ($this->date_type) {
			$start_time_timestamp = "";
			$end_time_timestamp = "";
			if ($this->start_time || $this->end_time) {
				if ($this->start_time) {
					$start_time_timestamp = strtotime($this->start_time);
				}
				if ($this->end_time) {
					$end_time_timestamp = strtotime($this->end_time);
				}

				//订单日期
				if ($this->date_type == 'order_date') {
					$sql = "SELECT order_id FROM bx_order_info WHERE 1=1 ";
					$temp_where = "";

					if ($start_time_timestamp) {
						$temp_where .= " AND add_time >='" . $start_time_timestamp . "' ";
					}
					if ($end_time_timestamp) {
						$temp_where .= " AND add_time <='" . $end_time_timestamp . "' ";
					}

					$order_ids = $GLOBALS['db']->getAll($sql . " " . $temp_where);
					$order_ids = CommonUtils :: arrToStr($order_ids, "order_id");

					if ($order_ids) {
						$sql = "SELECT invoice_id  FROM " . $GLOBALS['ecs']->table('invoice_order_policy') . " WHERE order_id IN(" . $order_ids . ")";
						$invoice_ids = $GLOBALS['db']->getAll($sql);
						$invoice_ids = CommonUtils :: arrToStr($invoice_ids, "invoice_id");
						if ($invoice_ids) {
							$this->where .= " AND r.id IN(" . $invoice_ids . ")";
							$this->condition['date_type'] = $this->date_type;
							$this->condition['start_time'] = $this->start_time;
							$this->condition['end_time'] = $this->end_time;

						} else {
							$this->where .= " AND r.id =0 ";
						}

					} else {
						$this->where .= " AND r.id =0 ";
					}

				}

				//保险起期
				if ($this->date_type == 'policy_date') {
					$sql = "SELECT policy_id FROM t_insurance_policy WHERE 1=1 ";
					$temp_where = "";
					if ($start_time_timestamp) {
						$temp_where .= " AND start_date >='" . $this->start_time . "'";
					}
					if ($end_time_timestamp) {
						$temp_where .= " AND start_date <='" . $this->end_time . "'";
					}

					$policy_ids = $GLOBALS['db']->getAll($sql . " " . $temp_where);
					$policy_ids = CommonUtils :: arrToStr($policy_ids, "policy_id");

					if ($policy_ids) {
						$sql = "SELECT invoice_id  FROM " . $GLOBALS['ecs']->table('invoice_order_policy') . " WHERE policy_id IN(" . $policy_ids . ")";
						$invoice_ids = $GLOBALS['db']->getAll($sql);
						$invoice_ids = CommonUtils :: arrToStr($invoice_ids, "invoice_id");
						if ($invoice_ids) {
							$this->where .= " AND r.id IN(" . $invoice_ids . ")";
							$this->condition['date_type'] = $this->date_type;
							$this->condition['start_time'] = $this->start_time;
							$this->condition['end_time'] = $this->end_time;

						} else {
							$this->where .= " AND r.id =0 ";
						}

					} else {
						$this->where .= " AND r.id =0 ";
					}

				}

				//申请发票日期
				if ($this->date_type == 'receipt_date') {
					if ($start_time_timestamp) {
						$this->where .= " AND r.receipt_add_time  >='" . $start_time_timestamp . "' ";
					}
					if ($end_time_timestamp) {
						$this->where .= " AND r.receipt_add_time  <='" . $end_time_timestamp . "' ";
					}

					$this->condition['date_type'] = $this->date_type;
					$this->condition['start_time'] = $this->start_time;
					$this->condition['end_time'] = $this->end_time;

				}

			}

		}

		//代理人姓名
		if ($this->receipt_assigned) {
			if ($this->receipt_assigned == 'processed') {
				$this->where .= " AND r.receipt_assigned =1 ";
			}
			if ($this->receipt_assigned == 'waiting_process') {
				$this->where .= " AND r.receipt_assigned =0 ";
			}
			
			$this->condition['receipt_assigned'] = $this->receipt_assigned;

		}
		else
		{
			$this->condition['receipt_assigned'] = 'all';
		}
		return $this->condition;
	}

	//批量往bx_invoice_order_policy插入数据
	public function batchInsertInvoiceOrderPolicy($receipt_id, $policy_ids) {
		try {
			//首先去查找判断此订单是否已经属于其他发票  start
			$policy_ids_arr = explode(",", $policy_ids);
			$new_order_sn = "";
			foreach ($policy_ids_arr as $value) {
				$sql = "SELECT invoice_id  FROM " . $GLOBALS['ecs']->table('invoice_order_policy') . " WHERE policy_id = '" . $value . "'";
				$invoice_id = $GLOBALS['db']->getOne($sql);
				if (!$invoice_id) //如果结果为空,说明此订单没有被其他发票占用
					{
					//$new_order_sn.=$value.",";
					//为了安全,判断此保单是否已经投保成功
					$sql = "SELECT policy_status  FROM t_insurance_policy WHERE policy_id ='$value'";
					$policy_status = $GLOBALS['db']->getOne($sql);
					if ($policy_status == 'insured') {
						$new_order_sn .= $value . ",";
					} else {
						show_message("抱歉!保单ID为:" . $value . "投保失败,无法申请发票!", '', 'user.php?act=warranty_list', 'error', false);
						$this->deleteInvoice($receipt_id);
						exit;

					}
				} else {
					show_message("抱歉!保单ID为:" . $value . "已经申请过,不能重复申请!", '', 'user.php?act=warranty_list', 'error', false);
					$this->deleteInvoice($receipt_id);
					exit;
				}

			}

			if (strstr($new_order_sn, ',')) {
				$policy_ids = rtrim($new_order_sn, ',');
			}

			//首先去查找判断此订单是否已经属于其他发票  end

			if ($policy_ids) {
				//获取保单列表
				$sql=" SELECT order_id,policy_id,policy_no FROM t_insurance_policy WHERE policy_id IN ($policy_ids) ";
				ss_log(__FUNCTION__.",获取保单列表:".$sql);
				$policy_list = $GLOBALS['db']->getAll($sql);
				
				//获取订单
				$order_id = $policy_list[0]['order_id'];
				$sql=" SELECT order_id,order_sn FROM t_insurance_policy WHERE order_id = $order_id";
				ss_log(__FUNCTION__.",获取订单:".$sql);
				$order_info = $GLOBALS['db']->getRow($sql);
				
				$order_id = $order_info['order_id'];
				$order_sn = $order_info['order_sn'];
				
				if ($policy_list) {
					foreach ($policy_list as $value) {
						$sql = "INSERT INTO " . $GLOBALS['ecs']->table('invoice_order_policy') . "(invoice_id, order_id, order_sn, policy_id,policy_no ) VALUES " .
						"('" . $receipt_id . "','" . $order_id . "','" . $order_sn . "','" . $value['policy_id'] . "','" . $value['policy_no'] . "')";
						$r = $GLOBALS['db']->query($sql);
					}
				}
				
				return $r; //add yes123 2014-12-08 添加返回值
				
			} else {
				$this->deleteInvoice($receipt_id, $policy_ids);
				exit;
			}
		} catch (Exception $e) {
			$this->deleteInvoice($receipt_id);
			show_message("抱歉!申请出现异常,请联系管理员!", '', 'user.php?act=warranty_list', 'error', false);
		}
	}

	//通过发票ID查询订单ID
	public static function getPolicyIdsByInvoiceId($invoice_id) {
		$sql = " SELECT policy_id FROM " . $GLOBALS['ecs']->table('invoice_order_policy') . "WHERE invoice_id =" . $invoice_id;
		return $GLOBALS['db']->getAll($sql);

	}

	//通过发票ID查询发票
	public static function getInvoiceById($invoice_id) {
		$sql = "SELECT r.*,isi.*,u.user_name,u.real_name FROM " . $GLOBALS['ecs']->table('receipt') . " AS r " .
				" LEFT JOIN". $GLOBALS['ecs']->table('invoice_send_info') ." AS isi ON r.id = isi.invoice_id " .
				" LEFT JOIN". $GLOBALS['ecs']->table('users') ." AS u ON r.user_id = u.user_id " .
			    " WHERE id=" . $invoice_id;
		$r = $GLOBALS['db']->getRow($sql);
		
		if($r['assigned_time']){
			$r['assigned_time'] = date("Y-m-d H:i",$r['assigned_time']);
		}
		$r['total_premium'] = price_format($r['total_premium']);
		//查询发票的其他信息
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('invoice_order_policy') . " WHERE invoice_id=" . $invoice_id;
		$iop_list = $GLOBALS['db']->getAll($sql);
		
		if($iop_list)
		{
			//(invoice_id, order_id, order_sn, policy_id,policy_no ) VALUES " .
			$order_id = CommonUtils :: arrToStr($iop_list, "order_id");
			$order_sn = CommonUtils :: arrToStr($iop_list, "order_sn");
			$policy_id = CommonUtils :: arrToStr($iop_list, "policy_id");
			$policy_no = CommonUtils :: arrToStr($iop_list, "policy_no");
			
			$r['order_id'] = $order_id;
			$r['order_sn'] = $order_sn;
			$r['policy_id'] = $policy_id;
			$r['policy_no'] = $policy_no;
				
		}
		else
		{// 老的发票处理
			$sql = "SELECT policy_id,order_sn FROM bx_order_info WHERE receipt_id =" . $invoice_id;
			$order_info = $GLOBALS['db']->getRow($sql);	
			
			$order_sn = $order_info['order_sn'];	
			$policy_id = $order_info['policy_id'];	
		}


		if($r['receipt_add_time'])
		{
			$r['receipt_add_time'] = date('Y-m-d H:i', $r['receipt_add_time']);
		}
		

		if ($policy_id) {
			$sql = "SELECT p.* FROM t_insurance_policy AS p WHERE p.policy_id IN(" . $policy_id . ") ";
			
			$policy_list = $GLOBALS['db']->getAll($sql);

			foreach ($policy_list AS $key => $policy) {
				$policy_list[$key]['total_premium'] = price_format($policy['total_premium']);
				
				//modify yes123 2015-01-27 取被投保人,有多个被保险人的时候，不赋值
				$sql = "SELECT policy_subject_id FROM t_insurance_policy_subject WHERE policy_id=$policy[policy_id]";	
        		$policy_subject_id_list = $GLOBALS['db']->getAll($sql);
			    $policy_subject_ids = commonUtils::arrToStr($policy_subject_id_list,"policy_subject_id");
				if($policy_subject_ids){
					$sql = " SELECT uid  FROM t_insurance_policy_subject_insurant_user WHERE policy_subject_id IN ($policy_subject_ids)" ;
					$uid_list = $GLOBALS['db']->getAll($sql);
					if(count($uid_list)==1)  //只有一个保险人的时候再赋值
					{
						$sql = "SELECT fullname FROM t_user_info WHERE uid=".$uid_list[0]['uid'];	
						$policy_list[$key]['assured_fullname'] = $GLOBALS['db']->getOne($sql);
					}
		        }   		 
				
			}

			//总额
			if(strstr($policy_id,","))
			{
				$sql = "SELECT SUM(total_premium) FROM t_insurance_policy WHERE policy_id IN(" . $policy_id . " )";
				$total = $GLOBALS['db']->getOne($sql);
			}
			else
			{
				$sql = "SELECT total_premium FROM t_insurance_policy WHERE policy_id = '". $policy_id . "'";
				$total = $GLOBALS['db']->getOne($sql);
				
			}

			
			$r['policy_list'] = $policy_list;
			$r['total'] = price_format($total, $change_price = true);
			
		}
		
		return $r;

	}

	//通过订单号查询保险公司,判断保单是不是同一个保险公司
	public  function isEqual($policy_ids) {
		$code = 0;
		//modify yes123 2015-04-03
		$sql = " SELECT DISTINCT insurer_name,insurer_code FROM t_insurance_policy WHERE policy_id IN ($policy_ids) ";
		ss_log("保单是不是同一个保险公司 ：".$sql);
		$insurance_company = $GLOBALS['db']->getAll($sql);
		
		
		//如果查询出来的保险公司数量大于1,说明有不同的公司,提示用户..
		if (count($insurance_company) > 1) {
			$code = self :: INSURER_UNLIKENESS;

		}

		//add yes123 2015-04-03  判断是不是同一个投保人
/*		$sql = "SELECT DISTINCT applicant_uid FROM t_insurance_policy WHERE policy_id IN ($policy_ids) ";
		ss_log("判断是不是同一个投保人：".$sql);
		$user_id_arr = $GLOBALS['db']->getAll($sql);
		if (count($user_id_arr) > 1) {
			$code = self :: APPLICANT_UNLIKENESS;
		}*/

		$insurance_company['code'] = $code;
		return $insurance_company;

	}

	public static function invoiceAssigned($invoice_ids) {
		$express_company = trim($_REQUEST['express_company']);//快递公司
		$mail_sn = trim($_REQUEST['mail_sn']);//快递公司
		$mail_time = trim($_REQUEST['mail_time']);//邮递时间
		$send_those = trim($_REQUEST['send_those']);//寄送人
		$send_those_phone = trim($_REQUEST['send_those_phone']);//寄送人电话
		$send_those_email = trim($_REQUEST['send_those_email']);//寄送人电话
		
		if(strstr($invoice_ids,",")){
			$invoice_ids = explode(",", $invoice_ids);
		}
		
		if ($invoice_ids) {
			$assigned_time = time();
			if(is_array($invoice_ids)){
				foreach ($invoice_ids as $value) {
					$sql = "INSERT INTO " . $GLOBALS['ecs']->table('invoice_send_info') . " (express_company, mail_sn, mail_time, send_those,send_those_phone,send_those_email,invoice_id) VALUES " .
							"('$express_company','$mail_sn','$mail_time','$send_those','$send_those_phone','$send_those_email','$value')";
					$r = $GLOBALS['db']->query($sql);
					
					//修改发票为已受理
					//modify yes123 20150130 添加显示受理人
					$sql = " UPDATE " . $GLOBALS['ecs']->table('receipt') . " SET receipt_assigned= 1,assigned_time='$assigned_time',handlers_id='$_SESSION[admin_id]'  WHERE id = '$value'";	
       				$GLOBALS['db']->query($sql);
				}
			}else{
					$sql = "INSERT INTO " . $GLOBALS['ecs']->table('invoice_send_info') . " (express_company, mail_sn, mail_time, send_those,send_those_phone,send_those_email,invoice_id) VALUES " .
							"('$express_company','$mail_sn','$mail_time','$send_those','$send_those_phone','$send_those_email','$invoice_ids')";
					$r = $GLOBALS['db']->query($sql);
					
					//修改发票为已受理
					//modify yes123 20150130 添加显示受理人
					$sql = " UPDATE " . $GLOBALS['ecs']->table('receipt') . " SET receipt_assigned=1,assigned_time='$assigned_time' ,handlers_id='$_SESSION[admin_id]' WHERE id = '$invoice_ids'";	
       				$GLOBALS['db']->query($sql);
			}

		}
		return $r;
	}
	// 删除发票
	public function deleteInvoice($receipt_id, $order_sn) {
		//删除发票
		$sql = "DELETE FROM " . $GLOBALS['ecs']->table('receipt') . " WHERE id=" . $receipt_id;
		$GLOBALS['db']->query($sql);

		$sql = "DELETE FROM " . $GLOBALS['ecs']->table('invoice_order_policy') . " WHERE invoice_id=" . $receipt_id;
		$GLOBALS['db']->query($sql);

	}

	
	public function updateReceiptInsurerCode ()
	{
		$sql = "SELECT receipt_id,add_time,policy_id FROM bx_order_info WHERE receipt_id IS NOT NULL AND receipt_id<>0";
		$policy_receipt_id = $GLOBALS['db']->getAll($sql);
		
		foreach ( $policy_receipt_id as $key => $value ) {
			
       		$sql = " UPDATE " . $GLOBALS['ecs']->table('receipt') . " SET receipt_add_time= '".$value['add_time']. "' WHERE id = ".$value['receipt_id'];	
       		$GLOBALS['db']->query($sql);
		}
		
		
	}
	
	public function get_insurance_company_by_order_sn ($order_sn){
		$sql = " SELECT DISTINCT insurer_name,insurer_code FROM t_insurance_policy WHERE order_id IN" .
				" (SELECT order_id FROM bx_order_info WHERE order_sn IN ($order_sn)) ";
		ss_log("申请发票通过订单号获取订单id,再查询保险公司 sql：".$sql);
		$insurance_company = $GLOBALS['db']->getAll($sql);
		return $insurance_company;
	
	}
	
	
}
?>