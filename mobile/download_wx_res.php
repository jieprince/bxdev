<?php

define('IN_ECS', true);
require(str_replace('\\','/',dirname(__FILE__)) . '/includes/init.php');


require_once "includes/class/wxUtils.class.php";

$media_id = isset($_REQUEST['media_id'])?trim($_REQUEST['media_id']):'';
$media_id = "r4eA4hRRrgl_lFSpBCBIqb2bpH0kEPYfCRzmn2LD9u63_9ac-dmi44bLisNOQOgm";

if($media_id)
{
	$wxUtils = new wxUtils(APPID, APPSECRET);
	$wxUtils->getMediaById($media_id);
	

}
?>
