<?php


function livesearch_duty_json($q,$insurer_id)
{
	global $_SGLOBAL;

	//http://127.0.0.1/ucenterhome/upload/publics.php?ac=livesearch&q=閾氭娲撻惀锟絪id=121
	/////////////将来要从主治疗表中获取到名称和类型/////////////////////////////
	$wheresql = "insurer_id='$insurer_id' AND duty_name LIKE '%$q%'";
	$sql = "SELECT * FROM " . tname ( 'insurance_duty' ) . " WHERE $wheresql";
	
	ss_log($sql);
	
	$query = $_SGLOBAL ['db']->query ( $sql );
	$list_duty = array();
	while ( $value = $_SGLOBAL ['db']->fetch_array ( $query ) )
	{
		$list_duty[]  = $value;

	}//while

	$arr_disease = array();
	foreach($list_duty as $key => $value)
	{
		$arr_disease[$key]["BD_ID"]  = $value[duty_id];
		$arr_disease[$key]["BD_Name"] = $value[duty_name];
		$arr_disease[$key]["BD_Code"] = $value[duty_code];
	}

	$jarr=json_encode($arr_disease);
	echo $jarr;

	return;

}
////////////////////////////////////////////////////
//get the q parameter from URL
$op= $_GET["op"];

$q = $_GET["q"];
$q = urldecode($_GET["q"]);

//和前面的前后顺序不要颠倒了。
$q = stripsearchkey($q);//add by wangcya , 20131224, for bug[898] 	代码要进行全面的安全检查

$hint ="";
//lookup all links from the xml file if length of q>0
if (strlen($q) > 0)
{
	if($op == 'duty')
	{
		$insurer_id = intval($_GET["insurer_id"]);
		livesearch_duty_json($q,$insurer_id);
	}
	
}