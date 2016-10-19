<?php
session_start();
define('IN_ECS', true);
define('ECS_ADMIN', true);
require(dirname(__FILE__) . '/includes/init.php');
$smarty->display("insurance.html");
?>