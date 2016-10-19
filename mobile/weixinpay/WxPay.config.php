<?php
/**
* 
*/

define("APPID" , $_POST['appid']);  //appid
define("APPKEY" ,$_POST['APPKEY']); //paysign key
define("SIGNTYPE", $_POST['SIGNTYPE']); //method
define("PARTNERKEY",$_POST['PARTNERKEY']);//财付通商户密钥 
define("APPSERCERT", $_POST['appsecret']);
//var_dump(APPID.'--'.APPKEY.'--'.SIGNTYPE.'--'.PARTNERKEY.'--'.APPSERCERT);
?>