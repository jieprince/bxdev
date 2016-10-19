<?php
$ROOT_PATH_= str_replace ( 'baoxian/source/cp_product_buy_epicc.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_. 'baoxian/source/function_baoxian_epicc.php');
/*人保寿险*/
function cp_buy_access_check_epicc($product,$user_info)
{
	//return true;
	////////////////////////////////////////////////////////
	$product_code 	= $product['product_code'];
	
	
	return true;
}

/*标准产品处理*/
function process_product_epicc_simple($product,$POST)
{
	//end
	global $_SGLOBAL;
	ss_log(__FUNCTION__.", GET IN");
	$ret_policy_input  = input_check_epicc($product, $POST);
	
	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数 ,生成保单
	
	if($product['attribute_type'] == 'jingwailvyou')
	{
		$global_info = $ret_policy_input['global_info'];
		$policy_arr = $ret_policy_output['policy_arr'];
		$policy_id  = $policy_arr['policy_id'];
		ss_log(__FUNCTION__.", 准备插入epicc境境外旅游的附加信息到数据库，policy_id: ".$policy_id);
		
		$attr_other_info = array(
				"policy_id"=>$policy_id,
				"destinationCountry"=>$global_info['destinationCountry'],//出行目的地
		        );
		
		inserttable("insurance_policy_epicc_otherinfo_lvyou", $attr_other_info);
		///////////////////////////////////////////////////////////////////////////////////
		$wheresql = "policy_id='$policy_id'";
		$sql = "SELECT * FROM ".tname('insurance_policy_epicc_otherinfo_lvyou')." WHERE $wheresql LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$policy_otherinfo = $_SGLOBAL['db']->fetch_array($query);
		
		if($policy_otherinfo)
		{
			$ret_policy_output['policy_arr']["destinationCountry"] = $policy_otherinfo['destinationCountry'];//出行目的
		}
		ss_log(__FUNCTION__.", GET OUT");
	}
	
	return $ret_policy_output;
}


function process_product_epicc_plan($product,$POST)
{
	$ret_policy_input  = input_check_epicc($product, $POST);
	
	$ret_policy_output = process_gen_policy($ret_policy_input,$product);//公共函数
		
	return $ret_policy_output;
}



function process_product_epicc($product,$POST)
{
	ss_log(__FUNCTION__.", GET IN");
	$attribute_type = $product['attribute_type'];
	$attribute_type = empty($attribute_type)?"product":$attribute_type;

	$insurer_code 	= $product['insurer_code'];
	if($attribute_type == "product"
	||$attribute_type == "lvyou"
	||$attribute_type == "jingwailvyou")//最简单的产品，下面组合参数
	{
		$ret_policy_output = process_product_epicc_simple($product,$POST);
	
	}
	else
	{
		$ret_policy_output = process_product_epicc_simple($product,$POST);
	}
	
	return $ret_policy_output;
}


//end add by wangcya, for bug[193],能够支持多人批量投保，


///////////////////////////////////////////////////////////////////////////////
function gen_cp_buy_view_info_epicc($product,$POST)
{
	
	$product_arr= $product;
	
	global $attr_epicc_RelationRoleCode;//与投保人关系
	global $attr_epicc_GovtIDTC;//证件类型
	//////////////////////////////////////////////////////
	$_TPL['css'] = 'cp_product_buy_epicc';
	
	$attribute_type = $product['attribute_type'];
	
	$attribute_type = empty($attribute_type)?"product":$attribute_type;
	
	ss_log(__FUNCTION__.", product_code: ".$product['product_code']);
	ss_log(__FUNCTION__.", attribute_type: ".$attribute_type);
	///////////////////////////////////////////////////////
	$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
	
	//mod by zhangxi, 20150312, 活动相关代码
	$uid = $_REQUEST['uid'];
	$gid = $_GET['gid'];

	//如果保险公司有险种代码的情况，最好还是按照保险公司的险种代码来定义，
	if( $attribute_type == 'product')//标准产品，例如价格不会变
	{
		ss_log(__FUNCTION__."process attribute_type: product");
		
		$businessType = 1; //1 为个人，2为机构
		$bat_post_policy = 0;//add by wangcya, 20150106, 被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		$prodcutMarkPrice = $product['premium'];
		$period_day = 365;//一年的
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品价格等于单价
	
		include_once template("cp_product_buy_epicc_product");
		ss_log(__FUNCTION__."template, cp_product_buy_epicc_product");	
	}
	else if( $attribute_type == 'lvyou'
	|| $attribute_type == 'jingwailvyou')//标准产品，例如价格不会变
	{
		ss_log(__FUNCTION__."process attribute_type: ".$attribute_type);
		
		$businessType = 1;
		$bat_post_policy = 0;//
		$prodcutMarkPrice = isset($_POST['prodcutMarkPrice'])?$_POST['prodcutMarkPrice']:0;

		$period_min = isset($_POST['period_min'])? intval($_POST['period_min']): 0;
		$period_max = isset($_POST['period_max'])? intval($_POST['period_max']): 0;
		$product_influencingfactor_id = isset($_POST['product_influencingfactor_id'])? intval($_POST['product_influencingfactor_id']): 0;
		
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品价格等于单价
	
		include_once template("cp_product_buy_epicc_lvyou");
		ss_log(__FUNCTION__."template, cp_product_buy_epicc_lvyou");	
	}
	else
	{  
		ss_log("process attribute_type: other");
		//这个分支是为了保证兼容性，不影响原有的没有可虑到的可能流程
		
		$businessType = 1;
		$bat_post_policy = 0;//被保险人投保方式： 0 单个投保，1批量投保，2，团单投保
		
		$prodcutMarkPrice = $product['premium'];
		$period_day = 365;//一年的
		
		$totalModalPremium_single = $prodcutMarkPrice;//add by wangcya, 20150116,产品单价
		include_once template("cp_product_buy_epicc_product");
	}
	
}