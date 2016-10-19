<?php

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

include_once(ROOT_PATH . 'baoxian/source/function_debug.php');

/**
 * 校验校验赠送剩余数量
 *
 * @access  public
 * @param   int      $uid      代理人ID
 * @param   int      $gid      goods_id
 * @return array
 */
function check_agent_num($uid,$gid,$activity_id){
	//获取此代理人，此产品赠送的总数
	 $sql = "SELECT give_count FROM " . $GLOBALS['ecs']->table('give_product_cfg') ." WHERE user_id='$uid' AND goods_id='$gid' AND activity_id='$activity_id'";
     $give_count =  $GLOBALS['db']->getOne($sql);

     if($give_count){
     	$sql = "SELECT count(order_id) FROM ".$GLOBALS['ecs']->table('order_info')." WHERE user_id='$uid' AND client_id !=0";
     	$order_total =  $GLOBALS['db']->getOne($sql);
	     if($order_total==$give_count){
	     	$res = array ('code' => 1,'msg' => '很抱歉，投保商量已使用完！');
	     }else{
	     	$res = array ('code' => 0,'msg' => 'ok');
	     }
     }else{
     	//如果没查询到，表示此代理人还没有C端用户投保
     	$res = array ('code' => 0,'msg' => 'ok');
     }
     
     return $res;
	
}
function send_phonecode_zhuanti($mobile_phone,$type,$activity_id=0,$goods_id=0){
	ss_log("send_phonecode function type:".$type.",mobile_phone:".$mobile_phone.",activity_id:".$activity_id.",goods_id:".$goods_id);
	global $_CFG;
	$num = rand(100000, 999999);
	switch ( $type ) {
		case 'check_c_phone': //给C端发校验手机号码短信
			$user_id= $_SESSION['user_id'];
			
		
			$content = "验证投保的手机验证码为：".$num;
			//1.查询是否已验证通过，如果验证通过bx_users_c表中会有此用户
			$sql = "SELECT user_id,openid,c_openid FROM " . $GLOBALS['ecs']->table('users_c') ." WHERE user_name='$mobile_phone'";
			ss_log('发送验证码时查询此用户是否已存在：'.$sql);
     		$user_c =  $GLOBALS['db']->getRow($sql);
			if($user_c['user_id']){
				ss_log('发送验证码时查询此用户是“存在” ...');

     			{
     				
     				//验证手机号码后，再验证openid
     				$c_openid = isset($_SESSION['c_openid'])?$_SESSION['c_openid']:"no";
     				$sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users_c') ." WHERE c_openid='$c_openid'";
     				$user_id =  $GLOBALS['db']->getOne($sql);
     				
//     				if($user_id){
//     					$sql = "SELECT order_id FROM " . $GLOBALS['ecs']->table('order_info') ." WHERE activity_id='$activity_id' AND client_id='$user_id' AND pay_status=2 ";
//	     				ss_log("通过openid判断用户是否投保过sql:".$sql);
//	     				$order_id =  $GLOBALS['db']->getOne($sql);
//	     				if($order_id){
//	     					ss_log('很抱歉，每个微信号只能投保一次！'.$sql);
//	     					$datas = json_encode(array('code'=>11,'msg'=>'很抱歉，每人只能投保一次！'));
//	     					return $datas;
//	     				}
//	     				
//     				}
     				
     				
     				ss_log('此号码已经验证通过，无需再验证，可以直接投保！');
     				ss_log("client_reg openid:".$user_c['openid']);
    				ss_log("client_reg c_openid:".$user_c['c_openid']);
    				if(!$user_c['openid'] ||!$user_c['c_openid'] ){
    					$b_openid = isset($_SESSION['b_openid']) ? $_SESSION['b_openid'] : '';
						$c_openid = isset($_SESSION['c_openid']) ? $_SESSION['c_openid'] : '';
						ss_log("client_reg b_openid:".$b_openid);
			    		ss_log("client_reg c_openid:".$c_openid);
    					if($b_openid && $c_openid){
    						$sql = "UPDATE " . $GLOBALS['ecs']->table('users_c') ." SET openid='$b_openid',c_openid='$c_openid'   WHERE user_id =".$user_c['user_id'];
			    			ss_log("client_reg  update openid:".$sql);
			     			$GLOBALS['db']->query($sql);
    					}
    				}
    				
     				
     				$datas = json_encode(array('code'=>10,'msg'=>'该手机号已通过验证！')); 
     			}
				
			}
			else
			{
				ss_log('发送验证码时查询此用户是“不存在” ...');
				$result = send_msg($mobile_phone,$content,80);
				$datas = json_encode(array('code'=>$result,'num'=>md5($num)));
				
				if($result===0){ //如果短信发送成功，那么把手机号码和验证码缓存，C端用户提交的时候验证
					$client = array('mobile_phone'=>$mobile_phone,'code'=>$num);
					$_SESSION['client'] = $client;
				}
			}
			break;
		case 'reset_password': 
			$sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users_c') ." WHERE user_name='$mobile_phone'";
			ss_log('get check phone reset_password：'.$sql);
			$user_id =  $GLOBALS['db']->getOne($sql);
			if($user_id){
				$content = "您在本网站重置密码的验证码为：".$num;
				$result = send_msg($mobile_phone,$content,80);
				if($result==-4)
				{
				 	writeLog();
				}
				
				if($result===0){ //如果短信发送成功，那么把手机号码和验证码缓存，C端用户提交的时候验证
					$client = array('mobile_phone'=>$mobile_phone,'code'=>$num);
					$_SESSION['client'] = $client;
				}
				
				$datas = json_encode(array('code'=>$result,'num'=>md5($num)));
			}else{
				$datas = json_encode(array('code'=>11,'num'=>md5($num),'msg'=>'手机号码不存在，请检查！'));
			}
			die($datas);
			break;
		default:
			break;
	}
	
	return $datas;
	
}


/**
 * 发送短信获取验证码，通用，具体分支用switch
 *
 * @access  public
 * @param   string      $mobile_phone     用户名
 * @param   string      	$type      要执行的路径
 * @return array
 */
function send_phonecode($mobile_phone,$type,$activity_id=0,$goods_id=0){
	ss_log("send_phonecode function type:".$type.",mobile_phone:".$mobile_phone.",activity_id:".$activity_id.",goods_id:".$goods_id);
	global $_CFG;
	$num = rand(100000, 999999);
	switch ( $type ) {
		case 'check_c_phone': //给C端发校验手机号码短信
			$user_id= $_SESSION['user_id'];
			$give_product_cfg = get_give_product_cfg($user_id,$goods_id,$activity_id);
			if(($give_product_cfg['give_count']-$give_product_cfg['order_num'])<=0){
				$datas = json_encode(array('code'=>11,'msg'=>'很抱歉，以全部售完！','give_count'=>$give_product_cfg['give_count'],'order_num'=>$give_product_cfg['order_num']));
				return $datas;
			}
			
/*			$legitimate_phone = array('15801033007','13910080241','13611260420','15810239380','13121162106','13516811205','13240484152');
			
			if (!in_array($mobile_phone,$legitimate_phone)){
				$datas = json_encode(array('code'=>11,'msg'=>'活动时间:2015年2月10日至3月10日'));
				return $datas;
			}*/
		
		
			$content = "验证投保的手机验证码为：".$num;
			//1.查询是否已验证通过，如果验证通过bx_users_c表中会有此用户
			$sql = "SELECT user_id,openid,c_openid FROM " . $GLOBALS['ecs']->table('users_c') ." WHERE user_name='$mobile_phone'";
			ss_log('发送验证码时查询此用户是否已存在：'.$sql);
     		$user_c =  $GLOBALS['db']->getRow($sql);
			if($user_c['user_id']){
				ss_log('发送验证码时查询此用户是“存在” ...');
				//如果存在，那么校验此用户是否投保过
				$sql = "SELECT order_id FROM " . $GLOBALS['ecs']->table('order_info') ." WHERE activity_id='$activity_id' AND client_id='$user_c[user_id]' AND pay_status=2 ";
     			ss_log("判断用户是否投保过sql:".$sql);
     			$order_id =  $GLOBALS['db']->getOne($sql);
     			if($order_id){
     				ss_log('很抱歉，每个手机号码只能投保一次！'.$sql);
     				$datas = json_encode(array('code'=>11,'msg'=>'很抱歉，每人只能投保一次！'));
     			}else{
     				
     				//验证手机号码后，再验证openid
     				$c_openid = isset($_SESSION['c_openid'])?$_SESSION['c_openid']:"no";
     				$sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users_c') ." WHERE c_openid='$c_openid'";
     				$user_id =  $GLOBALS['db']->getOne($sql);
     				if($user_id){
     					$sql = "SELECT order_id FROM " . $GLOBALS['ecs']->table('order_info') ." WHERE activity_id='$activity_id' AND client_id='$user_id' AND pay_status=2 ";
	     				ss_log("通过openid判断用户是否投保过sql:".$sql);
	     				$order_id =  $GLOBALS['db']->getOne($sql);
	     				if($order_id){
	     					ss_log('很抱歉，每个微信号只能投保一次！'.$sql);
	     					$datas = json_encode(array('code'=>11,'msg'=>'很抱歉，每人只能投保一次！'));
	     					return $datas;
	     				}
	     				
     				}
     				
     				
     				ss_log('此号码已经验证通过，无需再验证，可以直接投保！');
     				ss_log("client_reg openid:".$user_c['openid']);
    				ss_log("client_reg c_openid:".$user_c['c_openid']);
    				if(!$user_c['openid'] ||!$user_c['c_openid'] ){
    					$b_openid = isset($_SESSION['b_openid']) ? $_SESSION['b_openid'] : '';
						$c_openid = isset($_SESSION['c_openid']) ? $_SESSION['c_openid'] : '';
						ss_log("client_reg b_openid:".$b_openid);
			    		ss_log("client_reg c_openid:".$c_openid);
    					if($b_openid && $c_openid){
    						$sql = "UPDATE " . $GLOBALS['ecs']->table('users_c') ." SET openid='$b_openid',c_openid='$c_openid'   WHERE user_id =".$user_c['user_id'];
			    			ss_log("client_reg  update openid:".$sql);
			     			$GLOBALS['db']->query($sql);
    					}
    				}
    				
     				
     				$datas = json_encode(array('code'=>10,'msg'=>'该手机号已通过验证！')); 
     			}
				
			}
			else
			{
				ss_log('发送验证码时查询此用户是“不存在” ...');
				$result = send_msg($mobile_phone,$content,80);
				$datas = json_encode(array('code'=>$result,'num'=>md5($num)));
				
				if($result===0){ //如果短信发送成功，那么把手机号码和验证码缓存，C端用户提交的时候验证
					$client = array('mobile_phone'=>$mobile_phone,'code'=>$num);
					$_SESSION['client'] = $client;
				}
			}
			break;
		case 'reset_password': 
			$sql = "SELECT user_id FROM " . $GLOBALS['ecs']->table('users_c') ." WHERE user_name='$mobile_phone'";
			ss_log('get check phone reset_password：'.$sql);
			$user_id =  $GLOBALS['db']->getOne($sql);
			if($user_id){
				$content = "您在本网站重置密码的验证码为：".$num;
				$result = send_msg($mobile_phone,$content,80);
				if($result==-4)
				{
				 	writeLog();
				}
				
				if($result===0){ //如果短信发送成功，那么把手机号码和验证码缓存，C端用户提交的时候验证
					$client = array('mobile_phone'=>$mobile_phone,'code'=>$num);
					$_SESSION['client'] = $client;
				}
				
				$datas = json_encode(array('code'=>$result,'num'=>md5($num)));
			}else{
				$datas = json_encode(array('code'=>11,'num'=>md5($num),'msg'=>'手机号码不存在，请检查！'));
			}
			die($datas);
			break;
		default:
			break;
	}
	
	return $datas;
	
}

//add yes123 2015-01-24 通过手机号码查询保单个数
function check_client_policy($mobile_phone){
	$sql = "SELECT user_id,openid FROM " . $GLOBALS['ecs']->table('users_c') ." WHERE user_name='$mobile_phone'";
	$user_c =  $GLOBALS['db']->getRow($sql);
	if($user_c['user_id']){
		//如果存在，那么校验此用户是否投保过
		$sql = "SELECT policy_id FROM t_insurance_policy WHERE client_id='$user_c[user_id]'";
		$policy_id =  $GLOBALS['db']->getOne($sql);
		return $policy_id;
	}else{
		return 0;
	}
				
}
/*C 端用户注册*/
function client_reg($client,$mobile_phone,$code){
	//校验是否存在此用户
	$sql = "SELECT user_id,openid,c_openid FROM " . $GLOBALS['ecs']->table('users_c') ." WHERE user_name='$mobile_phone'";
    ss_log("start into client_reg function:".$sql);
    $user =  $GLOBALS['db']->getRow($sql);
    if($user['user_id']){
    	$datas = json_encode(array('code'=>10,'msg'=>'用户已存在！'));
    	//如果没有openid，则更新
    	ss_log("client_reg openid:".$user['openid']);
    	ss_log("client_reg c_openid:".$user['c_openid']);
    	if(!$user['openid'] ||!$user['c_openid'] ){
    		$b_openid = isset($_SESSION['b_openid']) ? $_SESSION['b_openid'] : '';
			$c_openid = isset($_SESSION['c_openid']) ? $_SESSION['c_openid'] : '';
			ss_log("client_reg b_openid:".$b_openid);
    		ss_log("client_reg c_openid:".$c_openid);
    		
			//如果session里有openid 则更新
    		if($b_openid && $c_openid){
    			$sql = "UPDATE " . $GLOBALS['ecs']->table('users_c') ." SET openid='$b_openid',c_openid='$c_openid'   WHERE user_id = '$user_id'";
    			ss_log("client_reg  update openid:".$sql);
     			$GLOBALS['db']->query($sql);
    		}
    	}
    }else{
    	//验证用户的验证码是否正确
		if($mobile_phone==$client['mobile_phone']){
			if($code== $client['code']){
				$password = md5($client['code']);
				$reg_time = time();
				$c_openid = isset($_SESSION['c_openid'])?$_SESSION['c_openid']:"0";
				$b_openid = isset($_SESSION['b_openid'])?$_SESSION['b_openid']:"0";
				$sql = "INSERT INTO " . $GLOBALS['ecs']->table('users_c') . "(openid,user_name, mobile_phone, password,reg_time,c_openid)" .
					" VALUES ('$b_openid','$mobile_phone','$mobile_phone','$password','$reg_time','$c_openid')";
				ss_log('client_reg sql ：'.$sql);
				$GLOBALS['db']->query($sql);
				$datas = json_encode(array('code'=>0,'msg'=>'该手机号已通过验证！'));
			}else{
				$datas = json_encode(array('code'=>10,'msg'=>'验证码不正确，请检查！'));
			}
	
		}else{
			$datas = json_encode(array('code'=>10,'msg'=>'手机号码不对，请检查！'));
		}
    }
    
	return $datas;
	
}
//通过活动id找到产品的相关信息
function get_product_attribute_info_by_activity_id($activity_id)
{
	if(!$activity_id)
	{
		return false;
	}
	$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('favourable_activity') ." WHERE act_range='3' AND act_id='$activity_id'";
    $favourable_activity =  $GLOBALS['db']->getRow($sql);
    $active_ext = $favourable_activity['act_range_ext'];
    
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('goods') ." WHERE goods_id='$active_ext'";
    $goods_info =  $GLOBALS['db']->getRow($sql);
    $attribute_id = $goods_info['tid'];
    $goods_id = $goods_info['goods_id'];//add by wangcya, 20150130
    
    $sql = "SELECT * FROM t_insurance_product_base AS ipb INNER JOIN t_insurance_product_attribute AS ipa 
           ON ipb.attribute_id=ipa.attribute_id " .
           " INNER JOIN t_insurance_product_additional AS ipba ON ipba.product_id=ipb.product_id ".
    		" WHERE ipb.attribute_id='$attribute_id' ";
    ss_log($sql);
    $product_info =  $GLOBALS['db']->getRow($sql);
    $product_info['goods_id'] = $goods_id;
    
    return $product_info;
    
}


//add yes123 2015-01-28 审核通过后，分配赠送数量
function huodong_recommend_operation($user_id){
	//查询所有活动
	$current_time = time();
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('favourable_activity') ." WHERE start_time<'$current_time' AND '$current_time'<end_time";
    $favourable_activity_list =  $GLOBALS['db']->getAll($sql);
    ss_log('获取当前时间段的所有活动：'.$sql);
    
    $sql = "SELECT parent_id FROM " . $GLOBALS['ecs']->table('users') ." WHERE user_id='$user_id'";
 	$parent_id =  $GLOBALS['db']->getOne($sql);
 			
    foreach ($favourable_activity_list as $key => $favourable_activity ) {
    	$default_num = $favourable_activity['default_num']; //默认可用额
    	$proxy_num = $favourable_activity['proxy_num']; //推荐人
    	$recommended_num = $favourable_activity['recommended_num']; //被推荐人
    	$act_range_ext = $favourable_activity['act_range_ext']; //参与活动的商品
 		//通过商品ID查询商品
 		if($act_range_ext){
	 		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('goods') ." WHERE goods_id IN('$act_range_ext') ";
 			$goods_list =  $GLOBALS['db']->getAll($sql);
 			foreach ( $goods_list as $key => $goods ) {
 				
 				if($parent_id){
 					//1.给推荐人赠送10个赠送额
	       			$give_product_cfg = get_give_product_cfg($parent_id,$goods['goods_id'],$favourable_activity['act_id']);
	       			if($give_product_cfg){
	       				
	       				if($proxy_num){
	       					$sql = "UPDATE ". $GLOBALS['ecs']->table('give_product_cfg') ." SET send_sms_status=0,give_count = give_count+$proxy_num " .
	       							" WHERE id = $give_product_cfg[id]";
		       				ss_log("update 推荐人:".$sql);
							$GLOBALS['db']->query($sql);
	       				}

	       			}else{
	       				//插入条新数据
	       				if($default_num || $proxy_num){
	       					
	       					$sql = "INSERT INTO " . $GLOBALS['ecs']->table('give_product_cfg') . " (user_id, goods_id, give_count, add_time, update_time,activity_id) " .
								"VALUES ('$parent_id', '$goods[goods_id]', $default_num+$proxy_num, '$current_time', '$current_time','$favourable_activity[act_id]')";
							ss_log("insert 推荐人:".$sql);
							$GLOBALS['db']->query($sql);
	       					
	       				}

	       			}
 					
 				}
				
       			
       			if($default_num || $recommended_num)
       			{
	       			$give_product_cfg = get_give_product_cfg($user_id,$goods['goods_id'],$favourable_activity['act_id']);
	       			
       				if($give_product_cfg){
       					$sql = "UPDATE ". $GLOBALS['ecs']->table('give_product_cfg') ." SET send_sms_status=0, give_count = give_count+$recommended_num " .
       							" WHERE id=$give_product_cfg[id]";
		       			ss_log("update 被推荐人:".$sql);
						$GLOBALS['db']->query($sql);
       					
       				}else{
	       			    //2.给被推荐人赠送
		 				$sql = "INSERT INTO " . $GLOBALS['ecs']->table('give_product_cfg') . " (user_id, goods_id, give_count, add_time, update_time,activity_id) " .
							   "VALUES ('$user_id', '$goods[goods_id]', $default_num+$recommended_num, '$current_time', '$current_time','$favourable_activity[act_id]')";
						ss_log("insert 被推荐人:".$sql);
					    $GLOBALS['db']->query($sql);
       					
       				}
       				
       			}

			}
 			
 		}
       
	}
}

//下单完毕后，需要执行的后续操作
function done_finish_operation($order_id=0){
	$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('order_info') .
			   " WHERE order_id=$order_id";
	ss_log('done_finish_operation  通过order_id 获取订单：'.$sql);
	$order =  $GLOBALS['db']->getRow($sql);
	if($order){
		$current_time = time();
		//查询此活动
		$favourable_activity = get_activity_by_id($order['activity_id']);
		if(empty($favourable_activity)){
			ss_log('此订单没有活动id：'.$sql);
			return;
		}
		
		//拿产品ID
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('order_goods') .
			   " WHERE order_id=$order_id";
		$order_goods =  $GLOBALS['db']->getRow($sql);
		$give_product_cfg = get_give_product_cfg($order['user_id'],$order_goods['goods_id'],$order['activity_id']);
		
		if($give_product_cfg){
			$sql = "UPDATE ". $GLOBALS['ecs']->table('give_product_cfg') ." SET order_num=order_num+1,update_time='$current_time' " .
       				" WHERE id='$give_product_cfg[id]'";
       		ss_log("update 下单更新:".$sql);		
       		$GLOBALS['db']->query($sql);
		}else{
			$sql = "INSERT INTO " . $GLOBALS['ecs']->table('give_product_cfg') . " (user_id, goods_id, give_count, add_time, update_time,order_num,activity_id) " .
				   "VALUES ('$order[user_id]', '$order_goods[goods_id]', '$favourable_activity[default_num]', '$current_time', '$current_time',1,'$favourable_activity[act_id]')";
			ss_log("insert 下单更新:".$sql);
			$GLOBALS['db']->query($sql);

		}
		
		//更新此活动的销售总数
		$sql = "UPDATE ". $GLOBALS['ecs']->table('favourable_activity') ." SET total_sold_num=total_sold_num+1 " .
       				" WHERE act_id='$favourable_activity[act_id]'";
       	ss_log("更新此活动的销售总数:".$sql);		
       	$GLOBALS['db']->query($sql);
		
	}else{
		ss_log("done_finish_operation 订单为空:".$sql);
	}
}

//获取配置
function get_give_product_cfg($user_id=0,$goods_id=0,$activity_id=0){
	$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('give_product_cfg') .
	" WHERE user_id='$user_id' AND goods_id='$goods_id' AND activity_id=$activity_id";
	ss_log("get_give_product_cfg:".$sql);
	$give_product_cfg =  $GLOBALS['db']->getRow($sql);
	
	//如果为空
	if(empty($give_product_cfg)){
		ss_log("get_give_product_cfg  is null ");
		$give_product_cfg = insert_give_product_cfg($user_id,$goods_id,$activity_id);
	}
	return $give_product_cfg;
}


//插入新的配置，并返回
function insert_give_product_cfg($user_id=0,$goods_id=0,$activity_id=0){
	ss_log("into insert_give_product_cfg function user_id:".$user_id.",goods_id:".$goods_id.",activity_id:".$activity_id);
	if($activity_id==0){
		return;
	}
	$favourable_activity = get_activity_by_id($activity_id);
	$sql = "INSERT INTO " . $GLOBALS['ecs']->table('give_product_cfg') . " (user_id, goods_id, give_count, add_time, update_time,order_num,activity_id) " .
			   " VALUES (".$user_id.",".$goods_id.",".$favourable_activity['default_num'].",". time().",".time().",0,".$favourable_activity['act_id'].")";
	ss_log("insert_give_product_cfg:".$sql);
	$GLOBALS['db']->query($sql);
	$give_product_cfg_id = $GLOBALS['db']->insert_id(); //发票ID
	
	$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('give_product_cfg') ." WHERE id = '$give_product_cfg_id'";
    return $GLOBALS['db']->getRow($sql);
	
}


//判断是不是活动时间
function is_activity_time(){
	$current_time = time();
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('favourable_activity') ." WHERE start_time<'$current_time' AND '$current_time'<end_time";
    return $GLOBALS['db']->getAll($sql);
}

//提交审核后，给管理员发送短信
function notification_admin($user_id=0){
	//判断是不是活动期间
	$favourable_activity_list = is_activity_time();
	if(empty($favourable_activity_list) || $user_id==0){
		return;
	}
	include_once(ROOT_PATH.'sdk_sms.php');
	//start add yes123 2015-01-29 发短信通知管理员有新会员资料要审核
	//获取手机号码
	$sql = "SELECT mobile_phone FROM " . $GLOBALS['ecs']->table('admin_user') . " WHERE user_name='admin'"; //china
	$mobile_phone = $GLOBALS['db']->getOne($sql);
	$mobile_phone = trim($mobile_phone);
	//获取用户名
	$sql = "SELECT user_name FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_id=$user_id"; //china
	$user_name = $GLOBALS['db']->getOne($sql);
	$content = "有会员提交审核,用户ID:".$user_id.",用户名:".$user_name;
	send_msg($mobile_phone,$content,1);
	//end add yes123 2015-01-29 发短信通知管理员有新会员资料要审核
}

//查询是否有剩余赠送个数，如果没有，发送短信给代理人，并把剩余个数返回
function insufficient_notification($user_id=0,$goods_id=0,$activity_id=0){
	$give_product_cfg = get_give_product_cfg($user_id,$goods_id,$activity_id);
	if($give_product_cfg){
		$surplus_num = $give_product_cfg['give_count']-$give_product_cfg['order_num'];
		if($surplus_num<=0){ //如果数量不足，通知代理人
			//判断是否发过短信
			if(!$give_product_cfg['send_sms_status']){ 
				//发送短信	
				include_once(ROOT_PATH.'sdk_sms.php');
				$mobile_phone = get_mobile_phone($user_id);
				
				if($give_product_cfg['activity_id']){
					$favourable_activity = get_activity_by_id($give_product_cfg['activity_id']);
					$content = "[".$favourable_activity['act_name']."]"."活动赠送数额已用完，可以推荐会员以获取更多赠送额";
					send_msg($mobile_phone,$content,1);
					//更新发送短信状态
					update_send_sms_status($give_product_cfg['id'],1);
				}
			
			}else{//send_sms为1则已通知过代理人，无需再发短信
				ss_log('insufficient_notification function 已经通知过代理人，无需再通知activity_id：'.$activity_id);
			}
			
		}
		
		return $surplus_num;
		
	}else{ //如果个数配置表里没查询到，说明是第一单
		$give_product_cfg = insert_give_product_cfg($user_id,$goods_id,$activity_id);
		return  $give_product_cfg['give_count']-$give_product_cfg['order_num'];
	}

}


//获取会员手机号码
function get_mobile_phone($user_id=0){
	$sql = "SELECT mobile_phone FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_id='$user_id'"; 
	ss_log("get_mobile_phone:".$sql);
	$mobile_phone = $GLOBALS['db']->getOne($sql);
	return $mobile_phone;
}

//更新发送短信状态
function update_send_sms_status($give_product_cfg_id=0,$send_sms_status){
	$sql = "UPDATE ".$GLOBALS['ecs']->table('give_product_cfg')." SET ".
           " send_sms_status =$send_sms_status".
           " WHERE id=".$give_product_cfg_id;
    $GLOBALS['db']->query($sql);
}

function get_activity_by_id($activity_id=0){
	$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('favourable_activity') . " WHERE act_id='$activity_id'"; 
	ss_log("into get_activity_by_id:".$sql);
	$favourable_activity = $GLOBALS['db']->getRow($sql);
	return $favourable_activity;
}

//通过商品ID获取当前活动
function get_current_activity_by_goods_id($goods_id=0){
	$current_time = time();
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('favourable_activity') ." WHERE act_range_ext='$goods_id' AND start_time < '$current_time' AND '$current_time' < end_time ";
    $favourable_activity = $GLOBALS['db']->getRow($sql);
    ss_log("get_current_activity_by_goods_id :".$sql);
    return $favourable_activity;
}

function get_current_activity_by_id($activity_id=0){
    $sql = "SELECT * FROM " . $GLOBALS['ecs']->table('favourable_activity') ." WHERE act_id=".$activity_id." AND start_time < ".time()." AND ".time()." < end_time ";
    ss_log("get_current_activity_by_id:".$sql);
    $favourable_activity = $GLOBALS['db']->getRow($sql);
    return $favourable_activity;
}


//校验是否可以分享，购买
function check_legal($user_id,$goods_id){
	ss_log("check_legal function==user_id:".$user_id.",goods_id:".$goods_id);
	$data = array();
	if($goods_id){
		$msg="";
		$sql="SELECT activity_id FROM ".$GLOBALS['ecs']->table('goods')." WHERE goods_id=$goods_id";
		ss_log("check_legal get activity_id by goods_id :".$sql);
		$activity_id = $GLOBALS['db']->getOne($sql);
		if($activity_id){
			ss_log("check_legal function 是活动产品 activity_id：".$activity_id);
			//1.判断是不是活动期间
			$favourable_activity = get_current_activity_by_id($activity_id);
			if(empty($favourable_activity)){
				$msg="很抱歉，非活动期间!";
				ss_log("check_legal function：".$msg);
				$data = array (
						'code' => 10,
						'msg' => $msg);
				
			}else{
				//2.判断是否有剩余数量
				if(($favourable_activity['total_limit_num']-$favourable_activity['total_sold_num'])<=0){
					$msg="很抱歉，已售完！";
					ss_log("check_legal function 没有余量 total_limit_num：".$favourable_activity['total_limit_num']."total_sold_num:".$favourable_activity['total_sold_num']);
					$data=array (
							'code' => 10,
							'msg' => $msg);
				}
				
			}
			
			
			//3.判断是否代理人是否有剩余量
			if($user_id){
				$surplus_num = insufficient_notification($user_id,$goods_id,$activity_id);
				if($surplus_num<=0){
					$msg="很抱歉，已售完，请联系保险顾问88555";
					ss_log("check_legal function ".$msg.",".$surplus_num);
					$data=array (
							'code' => 10,
							'msg' => $msg);
				}
			}
			
			
			if(!empty($data)){
				
				return $data;
			}

		}else{
			$msg="非活动产品或者不在活动期间";
			ss_log("check_legal function ".$msg);
			$data=array (
					'code' => 11,
					'msg' => $msg);
			return $data;
		}
		
	}
	$data=array (
			'code' => 0,
			'msg' => "ok");
	return $data;
}
?>
