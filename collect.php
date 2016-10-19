<?php
/* 添加收藏 */
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if($_REQUEST['act'] == 'collect'){
	$gid = $_REQUEST['gid'];
	$user_id = $_SESSION['user_id'];
	$sql = "SELECT count(*) FROM " . $ecs->table('collect_goods') ." WHERE goods_id = '$gid' AND user_id = '$user_id'";
	$r = $db->getOne($sql);
	if($r>0){
	   show_message('该保险已被收藏！', '', "goods.php?id=$gid");
	}else{
	    $time = time();
		$sql = "INSERT INTO " . $ecs->table('collect_goods') ." (rec_id,user_id,goods_id,add_time) VALUES (DEFAULT,'$user_id',$gid,$time)";
		if($db->query($sql)){
			show_message('收藏成功！', '', "goods.php?id=$gid");
		}
	}	
}