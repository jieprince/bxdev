<?php
/**
 * 人保财险相关
*/

 class EPICC_Insurance 
 {
   public function get_destination_List()
    {
    	global $_SGLOBAL;

    	$sql="SELECT name, english_name FROM t_insurance_epicc_travelling_destination";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_list_destination = array();
    	while ($row = $_SGLOBAL['db']->fetch_array($query))
    	{
    		$arr_list_destination[] = $row;
    	}
    	return $arr_list_destination;
    }
    //申根协议国家列表
     public function get_shengen_destination_List()
    {
    	global $_SGLOBAL;

    	$sql="SELECT name, english_name FROM t_insurance_epicc_travelling_destination where type='1'";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_list_destination = array();
    	while ($row = $_SGLOBAL['db']->fetch_array($query))
    	{
    		$arr_list_destination[] = $row;
    	}
    	return $arr_list_destination;
    }
   
  
 }