<?php
define('IN_ECS', true);

require (dirname(__FILE__) . '/includes/init.php');
require_once (ROOT_PATH . 'includes/lib_main.php');
require_once (ROOT_PATH . 'includes/lib_policy.php');
require_once (ROOT_PATH . 'includes/lib_order.php');

require_once (ROOT_PATH . '/baoxian/common.php');
require_once (ROOT_PATH . '/baoxian/source/function_baoxian.php');
require_once (ROOT_PATH . '/baoxian/source/function_debug.php');//add by wangcya, 20141010
///////////////////////////////////////////////////////////////////////////
$policy_id = isset ($_GET['policy_id']) ? intval($_GET['policy_id']) : 0;

//////////////////////////////////////////////////////////////////////////////////////

$action = $_REQUEST['act'];
ss_log("act:".$action);
//echo "action: ".$action;

if ($_REQUEST['act'] == 'list') 
{
	///start add by wangcya , 20141106,得到保险公司的列表//////////////
	$sql = "SELECT insurer_code,insurer_name FROM t_insurance_company WHERE 1";
	//ss_log($sql);
	$list_insurance_company = $GLOBALS['db']->getAll($sql);
	$smarty->assign('list_insurance_company', $list_insurance_company);
	///end add by wangcya , 20141106/////////////////////////////////////
	/* 检查权限 */
	admin_priv('admin_insurance_policy');

	$smarty->assign('ur_here', $_LANG['admin_insurance_policy']);
	$smarty->assign('action_link', array (
		'href' => 'policy.php?act=info',
		'text' => $_LANG['admin_insurance_policy']
	));
	$smarty->assign('full_page', 1);

	$policy_list = get_alluser_policy_list("insured");

	
	$smarty->assign('policy_list', $policy_list['policys']);
	$smarty->assign('attr_status', $policy_list['attr_status']);
	$smarty->assign('filter', $policy_list['filter']);
	$smarty->assign('record_count', $policy_list['record_count']);
	$smarty->assign('page_count', $policy_list['page_count']);
	
	$smarty->assign('s_totalpremium', $policy_list['s_totalpremium']);//add by wangcya , 20141107
	
	
	
	$sql = "select *from  bx_distributor";
	$smarty->assign('organ_list', $GLOBALS['db']->getAll($sql));
	
	ss_log("policy_list s_totalpremium: ".$policy_list['s_totalpremium']);
	//echo "sdfsdf";
	$smarty->display('policy_list.htm');

}
//start add by wangcya, 20141114,投保单列表，为了专门显示给平安的三款儿童产品给平安后台管理员
elseif ($_REQUEST['act'] == 'proposal_list')
{
	///start add by wangcya , 20141106,得到保险公司的列表//////////////
	$sql = "SELECT insurer_code,insurer_name FROM t_insurance_company WHERE 1";
	//ss_log($sql);
	$list_insurance_company = $GLOBALS['db']->getAll($sql);
	$smarty->assign('list_insurance_company', $list_insurance_company);
	///end add by wangcya , 20141106/////////////////////////////////////
	
	ss_log("将要进入函数 get_alluser_policy_list");
	$policy_list = get_alluser_policy_list("all",'cast_policy_list');//add by wangcya, 20150206,要用相同的函数。
	//$policy_list = get_pingan_ertong_proposal_list();//del by wangcya, 20150206
		
	$smarty->assign('full_page', 1);
	$smarty->assign('policy_list', $policy_list['policys']);
	$smarty->assign('filter', $policy_list['filter']);
	$smarty->assign('attr_status', $policy_list['attr_status']);
	$smarty->assign('record_count', $policy_list['record_count']);
	$smarty->assign('page_count', $policy_list['page_count']);
	$smarty->assign('s_totalpremium', $policy_list['s_totalpremium']);//add by wangcya , 20141107
	
	$smarty->display('proposal_list.htm');	
}

/*保单操作日志*/
elseif ($_REQUEST['act'] == 'policy_operation_log')
{
		/* 权限的判断 */
	admin_priv('logs_manage');

	$user_id = !empty ($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
	$admin_ip = !empty ($_REQUEST['ip']) ? $_REQUEST['ip'] : '';
	$log_date = !empty ($_REQUEST['log_date']) ? $_REQUEST['log_date'] : '';

	/* 查询IP地址列表 */
	$ip_list = array ();
	$res = $db->query("SELECT DISTINCT ip_address FROM " . $ecs->table('admin_log'));
	while ($row = $db->FetchRow($res)) {
		$ip_list[$row['ip_address']] = $row['ip_address'];
	}

	$smarty->assign('ur_here', $_LANG['admin_logs']);
	$smarty->assign('ip_list', $ip_list);
	$smarty->assign('full_page', 1);

	$log_list = policy_operation_logs();

	$smarty->assign('log_list', $log_list['list']);
	$smarty->assign('filter', $log_list['filter']);
	$smarty->assign('record_count', $log_list['record_count']);
	$smarty->assign('page_count', $log_list['page_count']);

	$sort_flag = sort_flag($log_list['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);

	assign_query_info();
	$smarty->display('policy_operation_logs.htm');
	
	
}
elseif ($_REQUEST['act'] == 'policy_pdf_upload')
{
	ss_log("policy_pdf_upload");
	//////////////////////////////////////////////////////////
	$FILE = $_FILES['input_policy_pdf'];
	$policy_id =  $_POST['policy_id'];
	$policy_no =  $_POST['policy_no'];
	
	if(empty($policy_id))
	{
		$url = "policy.php?act=info&policy_id=$policy_id";
		showmessage("保单id为空",$url);
	}
	
	if(empty($policy_no))
	{
		$url = "policy.php?act=info&policy_id=$policy_id";
		showmessage("保单号不能为空",$url);
	}
	
	if(empty($FILE))
	{
		$url = "policy.php?act=info&policy_id=$policy_id";
		showmessage("电子保单文件不能为空",$url);
	}
	
	ss_log("policy_id: ".$policy_id." policy_no: ".$policy_no);
	
	$allowfiletype = array('pdf');
	$ret_attr = create_temp_upload_file($FILE,$allowfiletype);
	$result = $ret_attr['ret_code'];
	//echo $result;
	if($result) 
	{
		ss_log("ret_msg: ".$ret_attr['ret_msg']);
		$msg = $ret_attr['ret_msg'];
		showmessage($msg);
		return $ret_attr;
	}
	else//如果上传文件成功，就执行导入excel操作
	{
		$uploadfile = $ret_attr['ret_msg'];
	}
	
	ss_log("policy_pdf_upload， file path: ".$uploadfile);
	//////////////////////////////////////////////////////////////
	$order_sn = update_policy_pdf_no($policy_id, $policy_no, $uploadfile);
	ss_log("order_sn: ".$order_sn);
	//comment by zhangxi, 20150319, 是否应该给代理人发送短信呢?
	//通过policy_id获取agent_uid,
	//再获取用户的信息
	$sql="select agent_uid from t_insurance_policy  WHERE policy_id=".$policy_id;
	ss_log(__FUNCTION__.", ".$sql);
	$policy_info = $GLOBALS['db']->getRow($sql);
	if($policy_info['agent_uid'])
	{
		$sql = "select * from bx_users  WHERE user_id=".$policy_info['agent_uid'];
		$user = $GLOBALS['db']->getRow($sql);
		if($user)
		{
			$content = "订单号：".$order_sn."，投保单:".$policy_id."已经投保成功，可下载电子保单";
			ss_log(__FUNCTION__.", content=".$content);
			send_msg($user['mobile_phone'],$content);	
		}
			
	}
	
	
	//////////////////////////////////////////////////////////////
	$url = "policy.php?act=info&policy_id=$policy_id";
	showmessage("success",$url);
}
//end add by wangcya, 20141114,头保单列表，为了专门显示给平安的三款儿童产品给平安后台管理员
/*------------------------------------------------------ */
//--导出保单列表 2014-11-7 yes123 
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'export_policy_list')
{
	$insurer_code = get_insurer_code_by_role();
	if($insurer_code=='CHINALIFE')
	{
		$title = array ('处理标记','被保险人编号','投保人姓名','投保人证件类别','投保人证件号码',
					'与投保人关系','被保险人姓名','主被保人编号','与被保险人关系','被保险人类型','性别','出生日期',
					'证件类别','证件号码','职业代码','组织层次代码','工作地点','工号','在职标志',
					'医保标识','医保代码','医保编号','异常告知',
					'险种1代码','险种1保额','险种1保费','险种1属组',
					'险种2代码','险种2保额','险种2保费','险种2属组',
					'险种3代码','险种3保额','险种3保费','险种3属组',
					'险种4代码','险种4保额','险种4保费','险种4属组',
					'险种5代码','险种5保额','险种5保费','险种5属组',
					'开户名','理赔开户银行','付款帐号','联系电话','电子邮件地址',
					'受益人姓名','受益人受益顺序','受益人受益份额','受益人性别','受益人出生日期','受益人证件类别',
					'受益人证件号码','受益人是被保险人的','被保险人签名','分理行名称','账号类型','分理行号','指定生效日',
					'同业公司人身保险保额合计');
		export_chinalife_cast_policy_list($title,'FFFFFF','000000');
	}
	else
	{
		//modify yes123 2015-01-08 导出提出去了
		$title = array ('订单号','保单号','代理人','保险公司','险种','保费','支付方式','支付金额','使用余额','出单日期','保险起期','保险止期','投保人','被保险人','投保状态','保单ID','用户ID','用户名');
		export_policy_list($title,'FF365D6A','FFFFFFFF','admin');
		exit;
	}
	
	

}
//add yes123 
elseif ($_REQUEST['act'] == 'import_excel')
{
	include_once(ROOT_PATH . 'oop/lib/Excel/PHPExcel.php');
	//获取上传的文件名
    $inputFileName = $_FILES["excelfile"]["name"];
    $suffix = explode('.',$inputFileName); 
    //转换为小写
    $suffix = strtolower($suffix[1]);
    ss_log("suffix:".$suffix); 
    if($suffix!='xlsx' && $suffix!='xls')
    {
    	$datas = json_encode(array('code'=>4,'msg'=>"请上传excel格式的文件！"));
 		die($datas);
    }
    
    $inputFileType='Excel2007';
    if ( $_FILES["excelfile"]["type"] == "application/vnd.ms-excel" ){
            $inputFileType = 'Excel5';
    }
    
    
    ss_log("inputFileType:".$inputFileType);
    
    $new_filename = "tempUploadFile/" . $_SESSION['admin_id']."_".time().".".$suffix;
 	ss_log(__FUNCTION__." tempUploadFile:".$new_filename);
    $result = move_uploaded_file($_FILES["excelfile"]["tmp_name"],  $new_filename);
    
    //如果上传成功，就解析
  	if($result) 
  	{
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);/*Excel5 for 2003 excel2007 for 2007*/  
		$objPHPExcel = $objReader->load($new_filename); //Excel 路径  
        $objWorksheet = $objPHPExcel->getActiveSheet();          
		$highestRow = $objWorksheet->getHighestRow();   // 取得总行数       
		$highestColumn = $objWorksheet->getHighestColumn();          
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数  
		  
		ss_log("highestRow:".$highestRow);
        ss_log("highestColumn:".$highestColumn);  
        ss_log("highestColumnIndex:".$highestColumnIndex);  
		
		//投保失败 或者注销失败的
		$import_fail_array = array();
		
		//相应给前端的字符串
		$warn_str = "";
		
		$excel_array = array();
		for ($row = 1;$row <= $highestRow;$row++) 
		{  
		    $strs=array();  
		    //注意highestColumnIndex的列数索引从0开始  
		    for ($col = 0;$col < $highestColumnIndex;$col++)            {  
		        $strs[$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();  
		    }  
		    
		    $temp_policy_status  = trim($strs[0]);
		    //标记为Y的，更改投保单的状态为“已投保”
		    if($temp_policy_status=='Y')
		    {
		    	$res = update_chinalife_policy($strs);
		    	if($res['code']==4)
		    	{
		    		$import_fail_array[]=$res['msg'];
		    	}
		    	else
		    	{
		    		//投保成功
		    	}
		    } 
		    
		    //处理标记为N时，需要进行与保单注销对应的结算操作：
		    if($temp_policy_status=='N')
		    {	$policy_id = trim($strs[45]);
		    	if($policy_id)
		    	{
			    	$res = withdraw_chinalife_policy($strs);
			    	if($res['code']==4)
			    	{
			    		$import_fail_array[]=$res['msg'];
			    	}
		    	}
		    } 
		    
		   $excel_array[]=$strs;
		}  
		
				    
	   //$var = print_r($excel_array , true);
	   //ss_log("strs--------------".$var);
		
		
		//拼接警告信息
		if(!empty($import_fail_array))
		{
			$i=1;
			foreach ($import_fail_array as $key => $value ) {
       			$warn_str.="<br/>".$i.":".$value;
       			$i++;
			}
			
		}

		$datas = json_encode(array('code'=>0,'msg'=>"处理完毕！",'warn_str'=>$warn_str));
 		die($datas);
 	}
 	else
 	{
 		$datas = json_encode(array('code'=>4,'msg'=>"保存失败，请联系管理员!"));
 		die($datas);
 	}
}

elseif ($_REQUEST['act'] == 'manual_refund') 
{
	$policy_id = $_GET['policy_id'];
	$sql = "UPDATE t_insurance_policy SET manual_refund='processed' WHERE policy_id='$policy_id'";
	ss_log('，manual_refund：'.$sql);
	$GLOBALS['db']->query($sql);	
	
	$content="保单ID为:".$policy_id." 手工退款";
    //记录管理员操作 
    admin_log2($content,'warn');
	
	
	$datas = json_encode(array('code'=>1,'msg'=>"ok"));
 	die($datas);
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query') 
{
	/* 检查权限 */
	admin_priv('admin_insurance_policy');

	$policy_list = get_alluser_policy_list("insured");

	$smarty->assign('policy_list', $policy_list['policys']);
	$smarty->assign('filter', $policy_list['filter']);
	$smarty->assign('record_count', $policy_list['record_count']);
	$smarty->assign('page_count', $policy_list['page_count']);
	
	$smarty->assign('s_totalpremium', $policy_list['s_totalpremium']);//add by wangcya , 20141107
	

	$sort_flag = sort_flag($policy_list['filter']);
	
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);

	ss_log("query s_totalpremium: ".$policy_list['s_totalpremium']);
	
	make_json_result($smarty->fetch('policy_list.htm'), '', array (
		'filter' => $policy_list['filter'],
		'page_count' => $policy_list['page_count']
	));
}


elseif ($_REQUEST['act'] == 'proposal_query')
{
	ss_log("into elseif proposal_query ");
	
	$policy_list = get_alluser_policy_list("all",'cast_policy_list');
	$smarty->assign('policy_list', $policy_list['policys']);
	$smarty->assign('filter', $policy_list['filter']);
	$smarty->assign('record_count', $policy_list['record_count']);
	$smarty->assign('page_count', $policy_list['page_count']);

	$smarty->assign('s_totalpremium', $policy_list['s_totalpremium']);//add by wangcya , 20141107


	$sort_flag = sort_flag($policy_list['filter']);

	$smarty->assign($sort_flag['tag'], $sort_flag['img']);

	ss_log("query s_totalpremium: ".$policy_list['s_totalpremium']);

	make_json_result($smarty->fetch('proposal_list.htm'), '', array (
	'filter' => $policy_list['filter'],
	'page_count' => $policy_list['page_count']
	));
}

/*------------------------------------------------------ */
//-- 订单详情页面
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'info') 
{
	//include_once(ROOT_PATH . 'includes/lib_transaction.php');
	if ($policy_id) 
	{
		$ret = get_policy_info_view($policy_id, $smarty);

		$policy = $ret['policy_arr'];
		$policy['total_premium'] = floatval($policy['total_premium']);
		$policy['rate_myself'] = floatval($policy['rate_myself']);
		$policy['rate_myself'] = floatval($policy['rate_myself']);
		$policy['rate_recommend'] = floatval($policy['rate_recommend']);
		$policy['rate_organization'] = floatval($policy['rate_organization']);
		
		//add yes123 2015-04-14 如果是阳光的产品，数据另外获取
		if($policy['insurer_code']=='sinosig')
		{
			//1.获取车辆信息
			$car = get_policy_car_info($policy_id);
			//2.投保险种及费用
			if($policy['attribute_type']=='compulsory') //交强险
			{ 
				$policy_car_sun_compulsory = get_compulsory_policy_info($policy_id);
				$smarty->assign('policy_car_sun_compulsory', $policy_car_sun_compulsory);
			}
			else if($policy['attribute_type']=='commercial')
			{
				$policy_car_sun_commercial = get_commercial_policy_info($policy_id);
				$smarty->assign('policy_car_sun_commercial', $policy_car_sun_commercial);
			}
			
			$policy['user_info_applicant'] = $ret['user_info_applicant'];
			$policy['user_info_applicant']['gender']=substr($policy['user_info_applicant']['certificates_code'], (strlen($policy['user_info_applicant']['certificates_code'])==15 ? -2 : -1), 1) % 2 ? '男' : '女'; 
			$smarty->assign('policy', $policy);
			$smarty->assign('car', $car);
			$smarty->display('sinosig_policy_info.htm');
			exit;
		}
		
		
		
		$user_info_applicant = $ret['user_info_applicant'];
		$list_subject = $ret['list_subject']; //多层级一个链表
		
		$order_id = $policy['order_id'];
		$order = get_order_info($order_id);
		
		$smarty->assign('order', $order);
		$smarty->assign('user_info_applicant', $user_info_applicant);
		$smarty->assign('list_subject', $list_subject);
		
		if($policy['insurer_code'] == 'SINO')
	    {
	    	//added by zhangxi, 20150107,  华安工程信息获取
	    	$product_info = $list_subject[0]['list_subject_product'][0];
	    	huaan_assign_project_info($smarty, $product_info['product_id'],$policy_id);
	    	$smarty->assign('product_code',$product_info['product_code']);
	    	
	    }
	    
	    
	    //如果保单状态为注销，判断是否是春秋商旅积分支付的
	    if($policy['policy_status']=='canceled' || $policy['policy_status']=='surrender')
	    {
	    	include_once(ROOT_PATH . 'includes/lib_payment.php');
	    	$sql = " SELECT pay_id FROM ".$GLOBALS['ecs']->table('order_info')." WHERE order_id='$policy[order_id]'";
	    	$pay_id = $GLOBALS['db']->getOne($sql);
	    	$payment_info = get_payment_byid($pay_id);
	    	if($payment_info['pay_code']=='springpass')
	    	{
	    		$refund_file_html="";
	    		
	    		$sql = " SELECT file FROM ".$GLOBALS['ecs']->table('springpass_refund')." WHERE policy_id='$policy[policy_id]' ";
	    		$springpass_refund_file_list= $GLOBALS['db']->getAll($sql);
	    		$i=1;
	    		foreach ( $springpass_refund_file_list as $key => $springpass_refund) 
	    		{
       				//退款文件
       				$refund_file_html.=" <a href=../$springpass_refund[file]>退积分文件_$i</a><br>";
       				$i++;
       					
				}
	    		$policy['refund_file_html'] = $refund_file_html;
	    		
	    	}
	    }
	    
	    
	    $smarty->assign('policy', $policy);
	    
	    $smarty->assign('insurer_code',$policy['insurer_code']);

	}

	$smarty->display('policy_info.htm');
}
//start add by wangcya , 20140826,单独处理，为了解决session的问题。
elseif ($_REQUEST['act'] == 'insure') 
{//投保

	//各种保险公司的投保都在这里进行
	$result_attr = post_policy($policy_id);

	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];

	if ($result == 0) 
	{
		$url = 'policy.php?act=info&policy_id=' . $policy_id;
		showmessage('do_success', $url, 0);
	} 
	else 
	{
		showmessage("投保失败！原因：" . $retmsg);
	}
}
elseif ($_REQUEST['act'] == 'reinsure') 
{//add by wangcya , 20141012, for bug[158],修正性性投保，重新投保，用户输入投保信息错误，或者我们错误，但是投保成功，需要先注销，然后再次投保的功能。 

	ss_log("后台管理员手工进行重新投保reinsure, policy_id: ".$policy_id);
	//各种保险公司的投保都在这里进行

	$result_attr = re_insure($policy_id);
	/////////////////////////////////////////////////////////////////////////
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];

	/*
	if ($result == 0)
	{
		$url = 'policy.php?act=info&policy_id=' . $policy_id;
		showmessage('do_success', $url, 0);
	}
	else
	{
		showmessage("重新投保失败！原因：" . $retmsg);
	}
	*/
	
	echo json_encode($result_attr);
	
	exit(0);
}
elseif ($_REQUEST['act'] == 'getpolicyfile') 
{
	$result_attr = get_policy_file($policy_id,"server_op", true);

	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];
	if($result!=0)
	{
		showmessage("获取电子保单失败，原因：" . $retmsg);
	}


}

/**
 * 目前停用
 * 
 * */
elseif ($_REQUEST['act'] == 'withdraw') 
{
	//add 2014-10-09   yes123 判断是否已到保险起期 BUG编号:#129
	$result_attr = withdraw_policy($policy_id);

	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];

	if ($result == 0) //success
	{
		ss_log("将要注销订单的处理");
		$url = 'policy.php?act=info&policy_id='.$policy_id;
		//2014-09-23 yes123 撤销保单的一系列关联，1.修改订单状态，2.退佣金 
		withdraw_order($policy_id);
		showmessage('do_success', $url, 0);
	} 
	else 
	{
		showmessage("注销电子保单失败，原因：" . $retmsg);
	}

}
//added by zhangxi,20150616, 查询电子保单的情况
elseif($_REQUEST['act'] =='querypolicyfile')
{
	$result_attr = query_policy_all($policy_id);
	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];

	if ($result == 0) //success
	{
		ss_log(__FILE__.", 管理端订单查询,policy_id=".$policy_id);
		$url = 'policy.php?act=info&policy_id='.$policy_id;
		showmessage('do_success', $url, 0);
	} 
	else 
	{
		showmessage("查询失败，原因：" . $retmsg);
	}
	
}
elseif ($_REQUEST['act'] == 'sendtorescue')
{//发送到救援公司
	
	$result_attr = rescue_policy($policy_id);

	$result = $result_attr['retcode'];
	$retmsg = $result_attr['retmsg'];

	if ($result == 0) //success
	{
		$url = 'policy.php?act=info&policy_id='.$policy_id;
		//2014-09-23 yes123 撤销保单的一系列关联，1.修改订单状态，2.退佣金
		showmessage('do_success', $url, 0);
	}
	else
	{
		showmessage("发送投保信息到救援公司失败，原因：" . $retmsg);
	}

}

/**
 * 取消投保单，当有部分保单投保失败的时候，可以用此功能。退换此保单的保费，和相应服务费
 * 2015-08-03 yes123
 */
elseif ($_REQUEST['act'] == 'cancel_policy') 
{
	$res = cancel_policy($policy_id);
	if($res['flag'])
	{
		$sql = " UPDATE t_insurance_policy SET policy_status='saved' WHERE policy_id='$policy_id'";
		ss_log('修改保单状态：'.$sql);
		$GLOBALS['db']->query($sql);
		die(json_encode(array('code'=>0,'msg'=>'取消成功')));
		
	}
	else
	{
		die(json_encode(array('code'=>0,'msg'=>$res['msg'])));
	}
	
	
}

/* 下载附件*/
elseif ($_REQUEST['act'] == 'dowload_policy_attachment') 
{
	//保单号
	$policy_id = $_REQUEST['policy_id'];
	//要下载的文件
	$attachment_ids = trim($_REQUEST['attachment_ids']);
	
	
	if($attachment_ids)
	{
		$sql = "SELECT * FROM t_attachment WHERE id IN($attachment_ids)";
		$attachment_list = $GLOBALS['db']->getAll($sql);
		
		$file_list = array();
		
		$file_count = 1;
		
		foreach ( $attachment_list as $key => $attachment ) 
		{
			if($attachment['value'])
			{
				$attachment_temp = array();
				
				if (strstr($attachment['value'], ',')) {
					$child_list = explode(',',$attachment['value']);
					foreach ( $child_list as $key2 => $child ) {
						$aryStr = explode(".", $child);
       					$attachment_temp['field_desc'] = $attachment['field_desc']."_".$file_count.".".$aryStr[1];
       					
       					$attachment_temp['value']=ROOT_PATH.$child;
       					$file_list[] = $attachment_temp;
       					$file_count++;
       					
					}
					
				}
				else
				{
					$aryStr = explode(".", $attachment['value']);
					$attachment_temp['field_desc'] = $attachment['field_desc']."_".$file_count.".".$aryStr[1];
					
					$attachment_temp['value'] =ROOT_PATH.$attachment['value'] ;
					$file_list[] = $attachment_temp;
					$file_count++;
				}
				
			
			}
		}
		
		
		$zip = new ZipArchive();
		$zip_name = $_SESSION['admin_id']."_".time().".zip";
		if ($zip->open($zip_name, ZIPARCHIVE::OVERWRITE) === TRUE) 
		{
			$file_list_str = print_r($file_list,true);
			ss_log("file_list_str:".$file_list_str);
			
			
			foreach ( $file_list as $key3 => $file ) 
			{
       			//$zip->addFile($file['value'],$file['field_desc']);
       			 $filesss = iconv('UTF-8','gb2312',$file['field_desc']);
       			 ss_log("文件全路径:".$file['value']);
       			$zip->addFile($file['value'],$filesss);
       			
			}


		}
		$zip->close();
		
		if (file_exists($zip_name)) {
			ini_set("memory_limit","-1");
			//打开文件
			$file = fopen($zip_name, "r");
			//返回的文件类型
			Header("Content-type: application/octet-stream");
			//按照字节大小返回
			Header("Accept-Ranges: bytes");
			
			$file_size = filesize($zip_name);
			//返回文件的大小
			header('Content-Length: ' . $file_size); //告诉浏览器，文件大小   
	
			//这里对客户端的弹出对话框，对应的文件名
			Header("Content-Disposition: attachment; filename=" . $zip_name);
			//修改之前，一次性将数据传输给客户端
			echo fread($file, filesize($zip_name));
			//修改之后，一次只传输1024个字节的数据给客户端
			//向客户端回送数据
			$buffer = 1024; //
			$file_count=0; //读取的总字节数  
			//判断文件是否读完
			while (!feof($file)) {
				//将文件读入内存
				$file_data = fread($file, $buffer);
				$file_count+=$buffer; 
				//每次向客户端回送1024个字节的数据
				echo $file_data;
			}
	
			fclose($file);
			//下载完成后删除压缩包，临时文件夹  
			if($file_count >= $file_size)  
			{}
				//
		
			
			unlink($zip_name); //删除文件
			
		} else {
			echo "<script>alert('对不起,您要下载的文件不存在');</script>";
		}
	
}



}



else if($_REQUEST['act']=='operation_policy')
{
	require_once (ROOT_PATH . '/baoxian/source/function_baoxian.php');	
	
	
	$smarty->assign('ur_here',      "操作保单");
	
	
	$policy_id = isset($_REQUEST['policy_id'])?trim($_REQUEST['policy_id']):'';
	$sql = "SELECT *FROM t_insurance_policy WHERE policy_id='$policy_id'";
	$policy = $db->getRow($sql);
	
	$policy_status_list = $attr_status;
	
	if($policy['policy_status']=='insured')
	{
		unset($policy_status_list['insured']);
		unset($policy_status_list['payed']);
		unset($policy_status_list['saved']);
	}
	
	if($policy['policy_status']=='saved' )
	{
		$str_err = "保单状态为已保存，无法操作";
		$links[] = array('text' => $str_err , 'href'=>"policy.php?act=info&policy_id=$policy_id");
		sys_msg($str_err, 1, $links);
	}
	
	if($policy['policy_status']=='surrender' || $policy['policy_status']=='canceled' )
	{
		$str_err = "此保单已注销或者已退保，无法操作保单！";
		$links[] = array('text' => $str_err , 'href'=>"policy.php?act=info&policy_id=$policy_id");
		sys_msg($str_err, 1, $links);
		
	}
	
	if($policy['policy_status']=='payed' )
	{
		unset($policy_status_list['saved']);
		unset($policy_status_list['payed']);
		unset($policy_status_list['canceled']);
		unset($policy_status_list['surrender']);
		
		$policy_status_list['cancel_policy'] = "取消投保单";
	}
	
	if(time()> strtotime($policy['end_date']))
	{
		$str_err = "已过保，无法操作";
		$links[] = array('text' => $str_err , 'href'=>"policy.php?act=info&policy_id=$policy_id");
		sys_msg($str_err, 1, $links);
		
	}
	
	
	foreach ( $policy_status_list as $key => $status ) 
	{
      $policy_status_list[$key] = str_replace("已","",$status);
      if($key=='insured'){
      	 $policy_status_list[$key] = "补充保单号";
      	
      }
      
	}
	
	
	//echo "<pre>";print_r($policy);
	
	$policy['policy_status_str'] = $attr_status[$policy['policy_status']];
	$policy['total_premium'] = floatval($policy['total_premium']);
	
/*	if($withdraw_type=='cancel')
	{
		$policy['operation_type'] = "注销（保单未生效的时候可以注销）";
	}
	else if($withdraw_type=='surrender')
	{
		$policy['operation_type'] = "退保（保单已生效的时候可以退保）";
	}*/
	
	$smarty->assign('policy', $policy);
	$smarty->assign('policy_status_list', $policy_status_list);
	$smarty->display('operation_policy.htm');
	
}

else if($_REQUEST['act']=='act_operation_policy')
{
	$type = trim($_REQUEST['type']);
	$policy_id = $_REQUEST['policy_id'];

	
	if(!$policy_id)
	{
		die("非法操作，保单号为空！");
		
	}	
	
	$url = "policy.php?act=info&policy_id=$policy_id";
	
	
	if(!$type)
	{
		$str_err = "请选择操作类型";
		$links[] = array('text' => $str_err , 'href'=>'policy.php?act=operation_policy&policy_id='.$policy_id);
		sys_msg($str_err, 1, $links);
	}
	
	
	$sql = "SELECT *FROM t_insurance_policy WHERE policy_id='$policy_id'";
	$policy = $db->getRow($sql);
	
	$policy_operation_log = array();
	$policy_operation_log['action_note'] = trim($_REQUEST['action_note']);
	$policy_operation_log['policy_id'] = $policy_id;
	$policy_operation_log['policy_no'] = $policy['policy_no'];
	$policy_operation_log['operation_type'] = $type;
	$policy_operation_log['log_time'] = time();
	$policy_operation_log['user_id'] = $_SESSION['admin_id'];
	$policy_operation_log['ip_address'] = real_ip();
	
	
	// 投保
	if($type=='insured')
	{
		$policy_no = isset($_REQUEST['policy_no'])?trim($_REQUEST['policy_no']):'';
		
		//检查操作是否合法 start
		if(!$policy_no)
		{
			$str_err = "请输入保单号";
			$links[] = array('text' => $str_err , 'href'=>$url);
			sys_msg($str_err, 1, $links);
			
		}
		
		if($policy['policy_status']=='insured')
		{
			$str_err = "已为投保状态，请勿重复操作！";
			$links[] = array('text' => $str_err , 'href'=>$url);
			sys_msg($str_err, 1, $links);
		}
		
		if($policy['policy_status']!='payed')
		{
			$str_err = "保单状态为未付款，无法投保！";
			$links[] = array('text' => $str_err , 'href'=>$url);
			sys_msg($str_err, 1, $links);
		}
		//检查操作是否合法 end
		
		if($policy['policy_status']=='payed')
		{
			$policy_data = array();
			$policy_data['policy_status'] = $type;
			$policy_data['policy_no'] = $policy_no;
			$r = $GLOBALS['db']->autoExecute("t_insurance_policy", $policy_data, "UPDATE","policy_id='$policy_id'"); 
			
			if($r)
			{
				
				$policy_operation_log['policy_no'] = $policy_no;
				$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('policy_operation_log'), $policy_operation_log, "INSERT"); 
				
				if($r)
				{
					$str_msg = "操作成功！";
					$links[] = array('text' => "返回保单详情" , 'href'=>$url);
					sys_msg($str_msg, 0, $links);
					
				}
			}
			
		}
		
		
	}
	
	elseif($type=='cancel_policy') //取消投保单，因某些原因无法投保成功，可以用此操作
	{
		if($policy['policy_status']!='payed')
		{
			$str_err = "当前保单状态不是待出单，无法取消！";
			
			ss_log("policy_id:$policy_id,".$str_err);
			
			$links[] = array('text' => $str_err , 'href'=>$url);
			sys_msg($str_err, 1, $links);
		}
		else
		{
			
			$res = cancel_policy($policy_id);
			if($res['flag'])
			{
				$sql = " UPDATE t_insurance_policy SET policy_status='saved' WHERE policy_id='$policy_id'";
				ss_log('修改保单状态：'.$sql);
				$GLOBALS['db']->query($sql);
				$str_msg = "取消成功";
				
				$policy_operation_log['user_money'] = $policy['total_premium'];
				$r = $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('policy_operation_log'), $policy_operation_log, "INSERT"); 
				
			}
			else
			{
				$str_msg = $res['msg'];

			}
			
			
			$links[] = array('text' => $str_msg , 'href'=>"policy.php?act=info&policy_id=$policy_id");
			sys_msg($str_msg, 0, $links);
			
		}
		
		
	}
	
	
	elseif($type=='canceled' || $type=='surrender') //注销或者退保
	{
				
		if($policy['policy_status']!='insured')
		{
			$str_err = "当前保单状态不是已投保，无法注销/退保！";
			$links[] = array('text' => $str_err , 'href'=>$url);
			sys_msg($str_err, 1, $links);
		}
		else
		{
			
			$other_parm = array();
			//1 为全额退款，2为部分退款
			$other_parm['withdraw_money_type'] = isset($_REQUEST['withdraw_money_type'])?intval($_REQUEST['withdraw_money_type']):0;			
			$other_parm['withdraw_money'] = isset($_REQUEST['withdraw_money'])?floatval($_REQUEST['withdraw_money']):0;			
			$other_parm['type'] = $type;
			
			ss_log("将要注销/退保的处理");
			$result_attr = withdraw_policy($policy_id);
			$result = $result_attr['retcode'];
			$retmsg = $result_attr['retmsg'];
		
			if ($result == 0) //success
			{
				withdraw_order($policy_id,$other_parm);
				
				//记录退还的保费
				if($other_parm['withdraw_money_type']==1)
				{
					$policy_operation_log['user_money'] = $policy['total_premium'];
				}
				else
				{
					$policy_operation_log['user_money'] = $other_parm['withdraw_money'];
				}				
				
				$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('policy_operation_log'), $policy_operation_log, "INSERT"); 
				
				
				$url = 'policy.php?act=info&policy_id='.$policy_id;
				$str_msg = "操作成功！";
				$links[] = array('text' => $str_msg , 'href'=>$url);
				sys_msg($str_msg, 0, $links);
			} 
			else 
			{
				ss_log("policy_id:$policy_id,".$retmsg);
				$url = 'policy.php?act=info&policy_id='.$policy_id;
				$str_msg = $retmsg;
				$links[] = array('text' => "保单详情" , 'href'=>$url);
				sys_msg($str_msg, 1, $links);
			}
			
		}
		
	}

	
}

else if($_REQUEST['act']=='set_rate')
{
	$res = array();
	$res['code']=0;
	$policy_id = isset($_REQUEST['policy_id'])?intval($_REQUEST['policy_id']):0;
		
	$sql = " select *from t_insurance_policy where policy_id='$policy_id'";
	$policy = $GLOBALS['db']->getRow($sql);
	
	if($policy['pay_status']==2)
	{
		$res['code']=1;
		$res['msg']="已付款，无法修改";
		die(json_encode($res));
	}	
	
		
		
	$data['total_premium'] = isset($_REQUEST['total_premium'])?floatval($_REQUEST['total_premium']):0;
	$data['rate_myself'] = isset($_REQUEST['rate_myself'])?floatval($_REQUEST['rate_myself']):0;
	$data['rate_recommend'] = isset($_REQUEST['rate_recommend'])?floatval($_REQUEST['rate_recommend']):0;
	$data['rate_organization'] = isset($_REQUEST['rate_organization'])?floatval($_REQUEST['rate_organization']):0;
	
	
	$r = $GLOBALS['db']->autoExecute("t_insurance_policy", $data, "UPDATE", "policy_id = $policy_id");
	
	if(!$r)
	{
		$res['code']=1;
		$res['msg']="更新失败";
		die(json_encode($res));
	}
	else
	{
		//更新订单金额
		$sql = "select SUM(total_premium) AS total_amount from t_insurance_policy where order_id='$policy[order_id]'";
		$total_amount = $GLOBALS['db']->getOne($sql);
		
		$sql= " UPDATE ".$GLOBALS['ecs']->table('order_info')." SET order_amount='$total_amount',goods_amount='$total_amount' WHERE order_id='$policy[order_id]'";
		$GLOBALS['db']->query($sql);
		
		
		//更新pay_log 金额
		$sql= " UPDATE ".$GLOBALS['ecs']->table('pay_log')." SET order_amount='$total_amount' WHERE order_id='$policy[order_id]' AND is_paid=0 ";
		$GLOBALS['db']->query($sql);
		
		
		
		
		//操作保单日志
		$policy_operation_log = array();
		$policy_operation_log['action_note'] = 
		"设置保费$data[total_premium]，订单服务费为：$data[rate_myself]，推荐服务费为：$data[rate_recommend]，渠道服务费为：$data[rate_organization]";
		$policy_operation_log['policy_id'] = $policy_id;
		$policy_operation_log['operation_type'] = "设置保单费用费率";
		$policy_operation_log['log_time'] = time();
		$policy_operation_log['user_id'] = $_SESSION['admin_id'];
		$policy_operation_log['ip_address'] = real_ip();
		
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('policy_operation_log'), $policy_operation_log, "INSERT");  
	}
	die(json_encode($res));
}


