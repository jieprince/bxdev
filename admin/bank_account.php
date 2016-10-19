<?php
/**
 * 2014/8/12
 * bhz
 * 银行账户管理
 */
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

/* 账户列表 */
if($_REQUEST['act'] == 'account_list'){
   
   if (empty($_SESSION['admin_id']))
   {
     header("Location:privilege.php?act=logout");
   }
   $sql = "SELECT * FROM ". $ecs->table('bank') ."WHERE uid = $_SESSION[admin_id]";
   
   $bank_account = $db->getAll($sql);
   
   $smarty->assign('bank', $bank_account);
   $smarty->display('bank_account.htm');
}
/* 添加新账户 */
elseif($_POST['act'] == 'add'){
    
	if (empty($_SESSION['admin_id']))
    {
      header("Location:privilege.php?act=logout");
    }
	$bank_code  = $_POST['bank_code'];
	$sql = "SELECT * FROM " . $ecs->table('bank'). " WHERE bank_code = '$bank_code'";
	if(count($db->getAll($sql)) > 0){
		$links = array(
	      array('href'=>'bank_account.php?act=account_add','text'=>'')  
	    );
	  sys_msg('账户已存在,请检测...',0,$links);
	}else{
		 /* 获得数据 */
		$bank_name = isset($_POST['bank_name']) ? $_POST['bank_name'] : '';
		if(empty($_POST['rests']))
		{
		   $b_account = $_POST['b_account'];
		}
		else
		{
		   $b_account = $_POST['rests'];
		}
		$sub_branch = $_POST['sub_branch'];
		$userID = $_SESSION['admin_id'];
		if($bank_name != null && $b_account != null && $bank_code != null && $sub_branch != null){
		   $sql="INSERT INTO ".$ecs->table('bank')." (uid, bank_name, b_account, sub_branch, bank_code) VALUES ($userID, '$bank_name', '$b_account', '$sub_branch', '$bank_code')";
		   $db->query($sql);
		   header("Location:bank_account.php?act=account_list");
		}else{
		  $links = array(
	        array('href'=>'bank_account.php?act=account_add','text'=>'')  
	      );
	      sys_msg('以上信息均不可为空！',0,$links);
		}
	}
}
/* 添加账户界面 */
elseif($_REQUEST['act'] == 'account_add'){
  
   $smarty->display('bank_add.htm');
   
}
/* 修改界面 */
elseif($_REQUEST['act'] == 'edit'){
	$bid = $_REQUEST['bid'];
	$sql = "SELECT * FROM " . $ecs->table('bank') . "WHERE bid = '$bid'";
	$bank_array = $db->getRow($sql);
	$smarty->assign('bank_account', $bank_array);
	$smarty->display('edit_account.htm');
}
/* 修改账户信息 */
elseif($_REQUEST['act'] == 'update'){

	$bid = $_POST['bid'];
    $bank_name = isset($_POST['bank_name']) ? $_POST['bank_name'] : '';
    $b_account = isset($_POST['b_account']) ? $_POST['b_account'] : '';
    $sub_branch = isset($_POST['sub_branch']) ? $_POST['sub_branch'] : '';
    $bank_code = isset($_POST['bank_code']) ? $_POST['bank_code'] : '';
	$sql = "UPDATE " . $ecs->table('bank') . " SET bank_name = '$bank_name', b_account = '$b_account', sub_branch = '$sub_branch', bank_code = '$bank_code' WHERE bid = '$bid'";
	if($db->query($sql))
	{
	  $links = array(
	     array('href'=>'bank_account.php?act=account_list','text'=>'')  
	  );
	  sys_msg('修改成功！',0,$links);
	 
	}else{
	  $links = array(
	     array('href'=>'bank_account.php?act=edit&bid=$bid','text'=>'')  
	  );
	  sys_msg('修改失败！请重新修改...',0,$links);
	}
}
/* 删除账户 */
elseif($_REQUEST['act'] == 'drop'){
   $bid = $_REQUEST['bid'];
   if($bid){
      $sql = "DELETE FROM " . $ecs->table('bank') . " WHERE bid = $bid";
	  if($db->query($sql))
	  {
		$links = array(
	       array('href'=>'bank_account.php?act=account_list','text'=>'')  
	     );
	    sys_msg('账户删除成功！',0,$links);
		
	  }
	  else{
	    $links = array(
	       array('href'=>'bank_account.php?act=account_list','text'=>'')  
	     );
	    sys_msg('删除账户失败！',0,$links);
	  }
   }
}
?>