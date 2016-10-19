<?php

//解密
function partner_decrypt($data)
{
	require_once(ROOT_PATH . 'includes/class/RSA.php');
	//对渠道代码和用户ID解密
	$pubfile = ROOT_PATH."includes/rsa_key/rsa_public_key.pem"; 
	$prifile = ROOT_PATH."includes/rsa_key/rsa_private_key.pem"; 
	$m = new RSA($pubfile, $prifile); 
	
	
	$cooperator = $m->decrypt($data['cooperator']); 
	$buyerId = $m->decrypt($data['buyerId']); 
	
	
	return array('cooperator'=>$cooperator,'buyerId'=>$buyerId);
	
}


function partner_login($cooperator,$buyerId)
{
	$sql = " SELECT * FROM ".$GLOBALS['ecs']->table('distributor')." WHERE d_identificationcode='$cooperator' ";
	$distributor = $GLOBALS['db']->getRow($sql);
	if(empty($distributor))
	{
		ss_log("查询不到响应渠道。。。非法访问");
		return false;
	}
	
	$user_id = 0;
	$sql = " SELECT * FROM ".$GLOBALS['ecs']->table('users')." WHERE thirdparty_uid='$buyerId' AND institution_id='$distributor[d_uid]' ";
	ss_log(__FUNCTION__.",查询用户：".$sql);
	$users = $GLOBALS['db']->getRow($sql);
	
	if(!empty($users))
	{
		ss_log(__FUNCTION__.",查询到用户去登录：".$users['user_id']);
		$_SESSION['user_id'] = $users['user_id'];
		update_user_info();
		set_config();
	}
	else
	{
		$reg_data = array(
			'user_name'=>generate_username(8),
			'password'=>md5(create_password(6)),
			'reg_time'=>time(),
			'last_update_time'=>time(),
			'institution_id'=>$distributor['d_uid'],
			'thirdparty_uid'=>$buyerId
		);
		
		
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('users'), $reg_data, 'INSERT');
		$user_id = $GLOBALS['db']->insert_id(); 
		
		ss_log(__FUNCTION__.",没有查询到用户，新增user_id：".$GLOBALS);
		$_SESSION['user_id'] = $user_id;
		update_user_info();
		set_config();	
	}
	
	return true;
}

//自动为用户随机密码(长度6-13)

function create_password($pw_length = 6){

	$randpwd = '';
	
	for ($i = 0; $i < $pw_length; $i++){
	
	$randpwd .= chr(mt_rand(33, 126));
	
	}
	
	return $randpwd;

}

function generate_username( $length = 8 ) {

	// 用户名字符集
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_';
	$username = '';
	for ( $i = 0; $i < $length; $i++ )
	{
		$username .= $chars[ mt_rand(0, strlen($chars) - 1) ];
	}
	
	//查询用户名是否重复
	$sql = " SELECT count(user_id) FROM ".$GLOBALS['ecs']->table('users')." WHERE user_name='$username' ";
	$count = $GLOBALS['db']->getOne($sql);
	if($count>0)
	{
		generate_username($length);
	}
	else
	{
		return $username;
	}

}


/*苹果手机 Safari浏览器单独处理*/
function safari_handle(){
	require_once(ROOT_PATH . 'baoxian/source/function_debug.php');
	$_SESSION['is_iframe']=1;
	
	ss_log(__FUNCTION__.",set is_iframe=1");
	$http_user_agent= $_SERVER["HTTP_USER_AGENT"];
	if(strpos($http_user_agent,"Safari"))
	{
		ss_log("来访浏览器为Safari，is_iframe为1");
		$_SESSION['is_iframe']=1;
		
	}
}

?>
