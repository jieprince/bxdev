<?php
define('APPLICATION_PATH', realpath(dirname(__FILE__). '/../../') . '/');

require_once APPLICATION_PATH . 'oop/core/init.php';
$region_code = $_REQUEST['region_code'];
$TBCRegion = new TBCRegion();
$regionStr = $TBCRegion->getRegionList($region_code);
echo $regionStr;
