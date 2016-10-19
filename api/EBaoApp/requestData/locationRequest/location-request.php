<?php
// echo __FILE__;
define ( 'IN_ECS', true );
define ( 'ROOT_PATH_NOSESSION', str_replace ( 'api/EBaoApp/requestData/locationRequest/location-request.php', '', str_replace ( '\\', '/', __FILE__ ) ) );
// echo ROOT_PATH;
include_once (ROOT_PATH_NOSESSION . '/includes/init.php');
global $GLOBALS;
$uid = isset ( $_REQUEST ['uid'] ) ? isset ( $_REQUEST ['uid'] ) : '';
$longitude = isset ( $_REQUEST ['longitude'] ) ? $_REQUEST ['longitude'] : '';
$latitude = isset ( $_REQUEST ['latitude'] ) ? $_REQUEST ['latitude'] : '';

// echo '$longitude' . $longitude;
// echo '$latitude' . $latitude;

$sql = "INSERT INTO " . $GLOBALS ['ecs']->table ( 'app_location_info' ) . "(uid," . "Longitude," . "Latitude)" . "VALUES('" . $uid . "','" . $longitude . "','" . $latitude . "')";

$GLOBALS ['db']->query ( $sql );
?>