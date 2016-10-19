<?php
/**
 * 太平洋货运险
*/

 class CPIC_CARGO_Insurance 
 {
   public function get_main_cargo_list()
    {
    	global $_SGLOBAL;

    	$sql="SELECT DISTINCT name_one FROM t_insurance_cpic_cargo_type";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_list = array();
    	while ($row = $_SGLOBAL['db']->fetch_array($query))
    	{
    		$arr_list[] = $row;
    	}
    	return $arr_list;
    }
    //
    public function get_cargo_type_by_code($code)
    {
    	global $_SGLOBAL;

    	$sql="SELECT  name_two FROM t_insurance_cpic_cargo_type where code='$code'";
		$query = $_SGLOBAL['db']->query($sql);
		$name_two = '';
    	$row = $_SGLOBAL['db']->fetch_array($query);
    	ss_log(__FUNCTION__.", ".var_export($row,true));
    	$name_two = $row['name_two'];
    	
    	return $name_two;
    }
     public function get_small_type_cargo_by_main($name)
    {
    	global $_SGLOBAL;

    	$sql="SELECT name_two, code FROM t_insurance_cpic_cargo_type where name_one='$name'";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_list = array();
    	while ($row = $_SGLOBAL['db']->fetch_array($query))
    	{
    		$arr_list[] = $row;
    	}
    	return $arr_list;
    }
	public function get_internal_cargo_main_clause()
	{
		global $_SGLOBAL;

    	$sql="SELECT code, name, content FROM t_insurance_cpic_cargo_internal_main_clause";
		$query = $_SGLOBAL['db']->query($sql);
		$arr_list = array();
    	while ($row = $_SGLOBAL['db']->fetch_array($query))
    	{
    		$arr_list[] = $row;
    	}
    	return $arr_list;
	}
  
 }