<?php


/////////////////////////////////////////////
function update_type_table()
{
	global $_SGLOBAL, $space,$_SN,$_SC;
	/////////////////////////////////////////////////////////////////
	$wheresql = "1";
	$sql = "SELECT * FROM ".tname('insurance_type')." WHERE $wheresql";

	//ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);

	$list = array();
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		$list[] =  $value;
	}

	/////////////////////////////////////////////////

	foreach ($list AS $key=>$value)
	{
		updatetable('insurance_type', array('id'=>($key+1) ), array('tempid'=>$value['tempid']));
		usleep(1);
	}

}

function update_product_table()
{

	global $_SGLOBAL, $space,$_SN,$_SC;
	/////////////////////////////////////////////////////////////////
	$wheresql = "1";
	$sql = "SELECT * FROM ".tname('insurance_type')." WHERE $wheresql";

	//ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);

	$list = array();
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		$list[] =  $value;
	}

	foreach ($list AS $key=>$value)
	{
		updatetable('insurance_product', array('ins_type_id1'=>$value['id'], 'ins_type_name'=>$value['name'] ), array('ins_type_id'=>$value['tempid']));
		usleep(1);
	}
}


//�����Լ��ĸ���
function update_type_table_parent()
{

	global $_SGLOBAL, $space,$_SN,$_SC;
	/////////////////////////////////////////////////////////////////
	$wheresql = "1";
	$sql = "SELECT * FROM ".tname('insurance_type')." WHERE $wheresql";

	//ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);

	$list = array();
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		$list[] =  $value;
	}

	foreach ($list AS $key=>$value)
	{
		updatetable('insurance_type', array('pid'=>$value['id'], 'pname'=>$value['name'] ), array('parent_id'=>$value['tempid']));
		usleep(1);
	}
}

//���¹�˾���id
function update_insurer_table_index()
{
	global $_SGLOBAL, $space,$_SN,$_SC;
	/////////////////////////////////////////////////////////////////
	$wheresql = "1";
	$sql = "SELECT * FROM ".tname('insurer')." WHERE $wheresql";

	//ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);

	$list = array();
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		$list[] =  $value;
	}

	/////////////////////////////////////////////////

	foreach ($list AS $key=>$value)
	{
		updatetable('insurer', array('sid'=>($key+1) ), array('id'=>$value['id']));
		usleep(1);
	}

}

//���²�Ʒ�еı��չ�˾��id
function update_product_table_ins()
{

	global $_SGLOBAL, $space,$_SN,$_SC;
	/////////////////////////////////////////////////////////////////
	$wheresql = "1";
	$sql = "SELECT * FROM ".tname('insurer')." WHERE $wheresql";

	//ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);

	$list = array();
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		$list[] =  $value;
	}

	foreach ($list AS $key=>$value)
	{
		updatetable('insurance_product', array('insurer_id1'=>$value['sid'], 'insurer_name'=>$value['abbr_name'] ), array('insurer_id'=>$value['id']));
		usleep(1);
	}
}



//���²�Ʒ���id
function update_product_table_index()
{
	global $_SGLOBAL, $space,$_SN,$_SC;
	/////////////////////////////////////////////////////////////////
	$wheresql = "1";
	$sql = "SELECT * FROM ".tname('insurance_product')." WHERE $wheresql";

	//ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);

	$list = array();
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		$list[] =  $value;
	}

	/////////////////////////////////////////////////

	foreach ($list AS $key=>$value)
	{
		updatetable('insurance_product', array('idp'=>($key+1) ), array('id'=>$value['id']));
		usleep(1);
	}

}


//�����Լ��ĸ���
function update_product_table_parent()
{

	global $_SGLOBAL, $space,$_SN,$_SC;
	/////////////////////////////////////////////////////////////////
	$wheresql = "1";
	$sql = "SELECT * FROM ".tname('insurance_product')." WHERE $wheresql";

	//ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);

	$list = array();
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		$list[] =  $value;
	}

	foreach ($list AS $key=>$value)
	{
		updatetable('insurance_product', array('parent_id1'=>$value['idp'], 'parent_name'=>$value['name'] ), array('parent_id'=>$value['id']));
		usleep(1);
	}
}

////////////////////////////////////////////////////////////////////////////
function one_add_blog_to_db($path)
{


	echo  "start: "."<br/>";
	////////////////////////////////////////////////////////
	echo $path."<br/>";

	$ch = curl_init();

	add_blog_to_db($path, $ch);

	curl_close($ch);

	echo  "finish: "."<br/>";

}

//����39.net����ݿ�
function loop_add_blog_to_db($dir)
{
	echo  "start: "."<br/>";
	////////////////////////////////////////////////////////
	$count = 0;

	$filelist = getfileName($dir);

	$ch = curl_init();
	////////////////////////////////////////////////////////////////
	foreach ($filelist AS $key=>$value)
	{
		$path = $dir.$value;


		add_blog_to_db($path, $ch);

		$count ++;

		//if($count>100)
		//	break;
	}
	//////////////////////////////////////////////////////
	curl_close($ch);

	echo  "finish: ".$count."<br/>";

}

//echo ($data);
function add_blog_to_db($path, $ch)
{

	//$path = S_ROOT.'message/2014032010072_ap.xml';
	//$path = S_ROOT.'message/2014050710003_ap.xml';

	$str = end(explode("/",$path));	//ȥ��'/'
	//echo $str."</br>";			//����ļ�ȫ��
	$rf =  substr($str,0,strrpos($str, '.'));//������չ����ļ���

	$af_path = S_ROOT."out_af/".$rf."_af.xml";
	//echo $af_path;
	//exit(0);
	/*
	 //echo  $path."<br />";
	$pathinfo = pathinfo($path);

	echo "Ŀ¼��ƣ�$pathinfo[dirname]<br>";
	echo "�ļ���ƣ�$pathinfo[basename]<br>";
	echo "��չ��$pathinfo[extension]";
	*/

	echo "path: ".$path."<br/>";
	echo "af_path: ".$af_path."<br/>";

	$content = file_get_contents($path);
	if(empty($content))
	{
		echo "content is null!";
		return;
	}
	else
	{
		//echo $content;
		//exit(0);
	}


	$flagpost= 1;
	$url= 'https://222.68.184.181:8107';
	//$flagpost=0;
	//$url= 'https://www.kangyq.com';
	$port =  8107;
	$data = curlPost($ch,$flagpost,$url, $port, $content, 10, 0);

	if(!empty($data))
	{
		file_put_contents($af_path,$data);
	}


	return ;

}


//更新太平洋的产品的历史数据到救援公司那边
function loop_update_policy_info()
{
	global $_SGLOBAL;
	////////////////////////////////////////////////////////
	$sql = "SELECT * FROM t_insurance_policy WHERE order_id>0 AND ((order_sn is null) OR order_sn='' OR LENGTH(order_sn)<=0)";
	ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);

	$policy_list = array();
	while ($row = $_SGLOBAL['db']->fetch_array($query))
	{
		$policy_list[] = $row;
	}


	foreach($policy_list AS $key=>$value)
	{
		$policy_id = $value['policy_id'];
		$order_id = $value['order_id'];

		$sql = "SELECT order_sn FROM bx_order_info WHERE order_id='$order_id' LIMIT 1";
		ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
		$row = $_SGLOBAL['db']->fetch_array($query);
		$order_sn = $row['order_sn'];

		/*
		 $sql = "UPDATE t_insurance_policy SET order_sn='$order_sn' WHERE policy_id='$policy_id' AND order_sn='0'";//modify by wangcya, for bug[193],能够支持多人批量投保，
		ss_log($sql);
		$db->query($sql);
		*/
		ss_log("loop_update_policy_info, order_sn: ".$order_sn);
		ss_log("loop_update_policy_info, policy_id: ".$policy_id);
		updatetable("insurance_policy", array('order_sn'=>$order_sn), array('policy_id'=>$policy_id));
	}
}

//更新太平洋的产品的历史数据到救援公司那边
function tool_sum_insured_to_order()
{
	global $_SGLOBAL;
	
	ss_log("into function ".__FUNCTION__);
	////////////////////////////////////////////////////////
	$sql = "select order_id, order_sn from bx_order_info where 1";
    ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);

	$count = 0;
	$order_list = array();
	while ($row = $_SGLOBAL['db']->fetch_array($query))
	{
		$order_list[] = $row;
		$count++;
	}

	ss_log("order count: ".$count);
	
	$count_policy = 0;
	foreach($order_list AS $key=>$value)
	{
		$countsql = "select count(*) from t_insurance_policy where order_id='$value[order_id]' AND policy_status='insured'";
		//ss_log($countsql);
		$insured_policy_num = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
		
		if($insured_policy_num)
		{
			$sql = "UPDATE bx_order_info SET insured_policy_num = ".$insured_policy_num." WHERE order_sn='$value[order_sn]'";
			ss_log($sql);
			$_SGLOBAL['db']->query($sql, 'SILENT');
			
			$count_policy++;
		}
		
	}
	
	ss_log("insured count_policy: ".$count_policy);
	
	return 	array("order_count"=>$count,
			      "policy_count"=>$count_policy
			     );
}


//更新所有的险种代码，每个险种对应一个唯一代码
function tool_generate_attribute_code_sn_loop()
{
	global $_SGLOBAL;
	////////////////////////////////////////////////////////
	$sql = "SELECT * FROM t_insurance_product_attribute WHERE 1";
	//ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);

	$attribute_list = array();
	while ($row = $_SGLOBAL['db']->fetch_array($query))
	{
		$attribute_list[] = $row;
	}

	foreach($attribute_list AS $key=>$value)
	{
		$attribute_id   = $value['attribute_id'];
		$attribute_code = $value['attribute_code'];

		$attribute_code_sn = "BYTPA_" . str_repeat('0', 6 - strlen($attribute_id)) . $attribute_id;

		updatetable("insurance_product_attribute",
		array('attribute_code'=>$attribute_code_sn),
		array('attribute_id'=>$attribute_id)
		);

	}
}


