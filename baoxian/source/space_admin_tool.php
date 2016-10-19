<?php

//http://127.0.0.1:84/baoxian/space.php?do=admin_tool&op=update_policy_info
include_once(S_ROOT.'./source/function_common.php');
include_once(S_ROOT.'./source/function_baoxian.php');
include_once(S_ROOT.'./source/function_tool.php');
include_once(S_ROOT.'./source/function_baoxian_admin.php');

include_once(S_ROOT.'./source/function_task.php');
///////////////////////////////////////////////////////////////

define('IN_ECS', true);

include_once('../includes/init.php');

include_once('../includes/lib_order.php');
include_once('../includes/inc_constant.php');

ss_log("into admin_tool!");

/*
if(($_SESSION['admin_name'])!="admin")
{
	//ss_log("in space,admin, session is null, so logout!");
	showmessage("您当前不是管理员，无法进行此操作!");
}
*/

ss_log("into admin_tool!.after session.");
////////////////////////////////////////////////////////////////
//下面这段代码是用来调整原先丢不了的价格，以及补充佣金的。
$op = empty($_GET['op'])?'':$_GET['op'];

ss_log("op: ".$op);

if($op == 'update_diubuliao')
{//更新丢不了的产品的价格等信息
	if(submitcheck('toolsubmit'))
	{
		$order_sn = trim($_POST['order_sn']);
		if($order_sn)
		{
			
			//$sql = "SELECT order_id FROM bx_order_info WHERE order_sn='$order_sn'";
			//$order_id = $GLOBALS['db']->getOne($sql);
			$sql="select goods_amount,order_id from ". $GLOBALS['ecs']->table('order_info') ."  WHERE order_sn=".$order_sn;
			$order_info = $GLOBALS['db']->getRow($sql);
		
			////////////////////////////////////////////////////////////////////////
			$order_id = $order_info['order_id'];//总价格
			
			$price = $order_info['goods_amount'];//更新一下总价格
			if($price == 190.00)
			{
				$sql = "UPDATE bx_order_info SET goods_amount='699.00' WHERE order_id=".$order_id;
				$GLOBALS['db']->query($sql);
			
				ss_log("change 190.00 to 699.00!");
			}
			elseif($price == 599.00)
			{
				ss_log("price is 599, so return!");
				return;
			}
			
			
			//没有产品则插入一个产品
			$sql = "SELECT rec_id FROM bx_order_goods WHERE order_id='$order_id'";
			$rec_id = $GLOBALS['db']->getOne($sql);
			if(empty($rec_id))
			{
				$product_id = 0;
				$goods_price = 699.00;
				$goods_attr = "";
				
				$attribute_id = 11;
				
				$sql = "INSERT INTO " . $ecs->table('order_goods') . "( " .
						"order_id, goods_id, goods_name, goods_sn, product_id, goods_number, market_price, ".
						"goods_price, goods_attr, is_real, extension_code, parent_id, is_gift) ".
						" SELECT '$order_id', goods_id, goods_name, goods_sn, '$product_id','1', market_price, ".
						"'$goods_price', '$goods_attr', is_real, extension_code, 0, 0 ".
						" FROM bx_goods WHERE tid = '$attribute_id'";
				$db->query($sql);
				
			}
			else
			{
				//$sql = "UPDATE bx_order_goods SET order_id='$new_order_id' WHERE order_id=".$order['policy_id'];
				//$GLOBALS['db']->query($sql);
			}
			
	
			///////////////////////////////////////////////////////////////////////////////
			ss_log("from admin tool, into assign_commision_and_post_policy");
			//mod by zhangxi, 20150305, 修改函数名,分配佣金和投保
			//yongJin2($order_sn,0);
			assign_commision_and_post_policy($order_sn,0);
		}
	
	}
	
	
}//更新丢不了产品的信息
////////////////////////////////////////////////////////////////////////////
elseif($op == 'update_order_status')
{//处理：支付宝已经支付成功，但是却没更新订单状态，并没投保的情况。
	ss_log("before update_order_status!");
	
	if(submitcheck('toolsubmit'))
	{
		$order_sn = trim($_POST['order_sn']);
		//$order_sn = "2014092264691";
		if($order_sn)
		{
	
			//$sql = "SELECT order_id FROM bx_order_info WHERE order_sn='$order_sn'";
			//$order_id = $GLOBALS['db']->getOne($sql);
			$sql="select order_id,fee_assigned from ". $GLOBALS['ecs']->table('order_info') ."  WHERE order_sn='$order_sn'";
			ss_log($sql);
			
			$order_info = $GLOBALS['db']->getRow($sql);
			$order_id = $order_info['order_id'];
			$fee_assigned = $order_info['fee_assigned'];
			
			////////////////////////////////////////////////////////////////////////
			if($order_id&&$fee_assigned!=1)
			{
				//PS_PAYED , order_status
				$sql = "UPDATE bx_order_info SET order_status='1',pay_status='2' WHERE order_id='$order_id'";
				$GLOBALS['db']->query($sql);
		
				ss_log($sql);
			
		
				///////////////////////////////////////////////////////////////////////////////
				ss_log("from admin tool, into assign_commision_and_post_policy");
				//mod by zhangxi, 20150305, 修改函数名,分配佣金和投保
				//yongJin2($order_sn,0);
				assign_commision_and_post_policy($order_sn,0);
			}
			else
			{
				echo "没找到该订单，或者订单已经分配佣金,佣金标志： ".$fee_assigned;
			}
		}
	
	}
	
	ss_log("after update_order_status!");
	
}//end 处理：支付宝已经支付成功，但是却没更新订单状态，并没投保的情况。
elseif($op == 'upload_taipingyang_excel')
{//start 处理提交太平洋的职业类别的excel的。
  //http://127.0.0.1/insure/baoxian/space.php?do=admin_tool&op=upload_taipingyang_excel
	
	ss_log("before upload_taipingyang_excel!");
	
	if(submitcheck('toolsubmit'))
	{

		if($_POST['leadExcel'] == "true")
		{
			//$filename = $_FILES['inputExcel']['name'];
			//$tmp_name = $_FILES['inputExcel']['tmp_name'];
			$FILE = $_FILES['inputExcel'];
			$msg = process_file_xls_taipingyang_occupationClassCode($FILE);
			echo $msg;
		}
	}
	
	ss_log("after upload_taipingyang_excel!");

}////end 处理提交太平洋的职业类别的excel的。
elseif($op == 'upload_hatai_destination')
{
	array('亚洲'=>array(
			"孟加拉共和国",
			"不丹王国",
			"文莱",
			"缅甸",
			"柬埔寨",
			"中国",
			"印度",
			"印度尼西亚",
			"日本",
			"朝鲜",
			"韩国",
			"老挝",
			"马来西亚",
			"马尔代夫群岛",
			"蒙古",
			"尼泊尔",
			"巴基斯坦",
			"菲律宾",
			"新加坡",
			"斯里兰卡",
			"泰国",
			"越南",
			"中国香港",
			"中国澳门",
			),
			""=>array(
					
					)
			);
	
}
elseif($op == 'check_hatai_post_return_xml')
{//127.0.0.1/insure/baoxian/space.php?do=admin_tool&op=check_hatai_post_return_xml
	
	$path = S_ROOT."xml/checkinsurance_rep.xml";
	//$path = "C:\wamp\www\insure\baoxian\xm\checkinsurance_rep.xml";
	echo $path."<br />"; ;
	
	//$return_data = file_get_contents($path);
	//echo $return_data;

	$xml = new DOMDocument("1.0","GBK");
	//$ret = $xml->loadXML($return_data);
	$ret = $xml->load($path);
	if($ret)
	{
		echo "load xml ok!"."<br />";
	}
	else
	{
		echo "load xml fail!"."<br />";
	}
	//echo "dom: ".$dom;
	$root = $xml->documentElement;//使用DOM对象的documentElement属性可以访问XML文档的根元素
	$BaseInfos = $root->getElementsByTagName("BaseInfo");
	//$TradeType = $BaseInfos->item(1)->nodeValue;
	//echo "------".$TradeType."<br />";
	
	foreach($BaseInfos as $BaseInfo)
	{
		//echo "1111dsfsfsdf"."<br />"; ;
		$TradeTypes = $BaseInfo->getElementsByTagName("TradeType");
        $TradeType = $TradeTypes->item(0)->nodeValue;      //nodeValue 书写是要注意！
       
		//ss_log($TradeType);
		echo "------".$TradeType."<br />"; 
		
		$TradeTypes = $BaseInfo->getElementsByTagName("TradeCode");
		$TradeCode = $TradeTypes->item(0)->nodeValue;      //nodeValue 书写是要注意！
		    
		echo "------".$TradeCode."<br />";
		
	}
	

	/*
	$nodes = $root->getElementsByTagName("OutputData");
	if($nodes)
	{
		$TradeSeq = $nodes->getElementsByTagName("TradeSeq");
		ss_log("OutputData TradeSeq:".$TradeSeq);
	}
	*/
		
}
elseif($op == 'check_mobilephone')
{
	//http://127.0.0.1/insure/baoxian/space.php?do=admin_tool&op=check_mobilephone
	$mobilephone = "13611260420";
	$res = checkMobileValidity($mobilephone);
	if(!$res)
	{
		echo "mobile is not Validity";
		exit(0);
	}
	
	$zone = checkMobilePlace($mobilephone);
	if(empty($res))
	{
		echo "mobile is null";
		exit(0);
	}
	else
	{
		$province = $zone["province"];
		$catName  = $zone["catName"];
		
		ss_log("province: ".$province);
		ss_log("catName: ".$catName);
		
		
		echo "province: ".$province;
		echo "catName: ".$catName;
		exit(1);
	}
}
elseif($op == 'update_policy_info')
{//把以前的保单信息发给救援公司。
	//echo "finish!";
	//exit("finish");
	
	loop_update_policy_info();
	echo "finish!";
	exit("finish");

}
elseif($op == 'set_attribute_code_sn')
{//循环设置险种的代码，每个险种对应一个代码

	tool_generate_attribute_code_sn_loop();
	echo "finish!";
	exit("finish");
}
elseif($op == 'send_message_to_jiouyuan')
{//把以前的保单信息发给救援公司。
	$task = new TaskActive;
	$task->check_policy_taipingyang_jiuyuan();

	echo "finish!";
	exit("finish");

}
elseif($op == 'sum_insured_to_order')
{//把所有的订单下已经投保成功的保单的个数汇总到定的那上
//http://www.ebaoins.cn/baoxian/space.php?do=admin_tool&op=sum_insured_to_order
	$count_arr = tool_sum_insured_to_order();
	$order_count = $count_arr['order_count'];
	$policy_count = $count_arr['policy_count'];
	
	echo "tool_sum_insured_to_order, finish!, order_count: ".$order_count." insured policy_count: ".$policy_count;
	exit(" finish");

}

$_TPL['css'] = "admin_product";
include_once template("space_admin_tool");


