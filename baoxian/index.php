<?php

/*

	$Id: index.php 13003 2009-08-05 06:46:06Z  $

*/


include_once('./common.php');

//ss_log("before index:  ".$_SGLOBAL['mobile_type']);

/*
if(is_numeric($_SERVER['QUERY_STRING'])) {

	showmessage('enter_the_space', "space.php?uid=$_SERVER[QUERY_STRING]", 0);

}
*/


/*

//��������

if(!isset($_GET['do']) && $_SCONFIG['allowdomain']) {

	$hostarr = explode('.', $_SERVER['HTTP_HOST']);

	$domainrootarr = explode('.', $_SCONFIG['domainroot']);

	if(count($hostarr) > 2 && count($hostarr) > count($domainrootarr) && $hostarr[0] != 'www' && !isholddomain($hostarr[0])) {

		showmessage('enter_the_space', $_SCONFIG['siteallurl'].'space.php?domain='.$hostarr[0], 0);

	}

}*/



if($_SGLOBAL['supe_uid']) 
{
	//�ѵ�¼��ֱ����ת������ҳ
	showmessage('enter_the_space', 'space.php?do=products_list', 0);
}

include_once(S_ROOT.'./source/space_products_list.php');


?>