<?php

include_once(S_ROOT.'./source/function_baoxian.php');

$product_additional_attr_period_uint = array("year"=>"年","month"=>"月","day"=>"天");

//根据产品属性找到产品列表
function admin_get_product_from_attributeid($attribute_id)
{
	global $_SGLOBAL;
	/////////////////////////////////////////////////////////////
	$ordersql = " ORDER BY view_order";//dateline_update
	$wheresql = "attribute_id='$attribute_id'";
	$sql = "SELECT * FROM ".tname('insurance_product_base')." WHERE $wheresql $ordersql LIMIT 0,10";

	//ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);

	$list = array();

	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		//$value['interface_flag'] = $interface_atrr[$value['interface_flag']];
		//$value['allow_sell'] = $allow_sell_atrr[$value['allow_sell']];

		$list[] =  $value;
	}

	return $list;
}


//////////////提交保险类型
function admin_insurance_type_post($POST,
		$olds=array()
)
{
	global $_SGLOBAL, $_SC, $space;
	///////////////////////////////////////////////////////////////////
	if(     empty($POST['code'])||
			empty($POST['name']) ||
			empty($POST['abbr_name']))
	{
		showmessage("必填项没填写！");

		return;
	}

	/////////////////////////////////////
	$isself = 1;

	if(!empty($olds['uid']) && $olds['uid'] != $_SGLOBAL['supe_uid'])
	{
		$isself = 0;

		if( $_SGLOBAL['supe_username']=='admin')//add by wangcya, 20121226, for bug[]
		{

		}
		else
		{
				
			$__SGLOBAL = $_SGLOBAL;
			$_SGLOBAL['supe_uid'] = $olds['uid'];
			$_SGLOBAL['supe_username'] = addslashes($olds['username']);
		}
	}

	//$POST['subject'] = getstr(trim($POST['subject']), 80, 1, 1, 1);
	$parent_id = intval($POST['parent_id']);
	if($parent_id)
	{
		$sql = "SELECT * FROM ".tname('insurance_type')." WHERE id='$parent_id' LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$parent = $_SGLOBAL['db']->fetch_array($query);

		$parent_name = $parent['name'];
	}

	$blogarr = array(
			'code' =>intval($POST['code']),
			'name'=>$POST['name'],
			'abbr_name'=>$POST['abbr_name'],
			'brief'=>$POST['brief'],
			'note'=>$POST['note'],
			'parent_id' =>$parent_id,
			'parent_name'=>$parent_name//add by wangcya ,20120931
	);


	if($olds['id'])
	{

		$id = $olds['id'];
		$blogarr['dateline_update'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];

		$blogarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_type', $blogarr, array('id'=>$id));

		$blogarr['uid'] = $olds['uid'];
		$blogarr['username'] = $olds['username'];
	}
	else
	{

		$blogarr['uid'] =  empty($POST['uid'])?$_SGLOBAL['supe_uid']:$POST['uid'];
		$blogarr['username'] =  empty($POST['username'])?$_SGLOBAL['supe_username']:$POST['username'];
		$blogarr['dateline_update'] = $blogarr['dateline_create'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];

		$setarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		$id = inserttable('insurance_type', $setarr, 1);
	}

	$blogarr['id'] = $id;

	if(!empty($__SGLOBAL))
		$_SGLOBAL = $__SGLOBAL;

	return $blogarr;
}


/**
 * 为某险种生成唯一的货号
 * @param   int     $goods_id   商品编号
 * @return  string  唯一的货号
 */
function admin_generate_attribute_code_sn($attribute_id)
{
	global $_SGLOBAL;
	////////////////////////////////////////////////////////

	$attribute_code = "BYTPA_" . str_repeat('0', 6 - strlen($attribute_id)) . $attribute_id;

	if(1)
	{
		ss_log("in admin_generate_attribute_code_sn , attribute_code: ".$attribute_code);

		$sql = "SELECT attribute_code FROM t_insurance_product_attribute " .
				" WHERE attribute_code LIKE '" . mysql_like_quote_my($attribute_code) . "%' AND attribute_id <> '$attribute_id' " .
				" ORDER BY LENGTH(attribute_code) DESC";

		ss_log($sql);

		$sn_list = $_SGLOBAL['db']->getCol($sql);

		//ss_log("array: ".);
		//echo "attribute_code: ".$attribute_code."</br>";
		//print_r($sn_list);

		if (in_array($attribute_code, $sn_list))
		{
			$max = pow(10, strlen($sn_list[0]) - strlen($attribute_code) + 1) - 1;
			ss_log("pow max: ".$max);
				
			$new_sn = $attribute_code . mt_rand(0, $max);
			ss_log("attribute_code: ".$attribute_code);
			ss_log("new_sn: ".$new_sn);
				
			while (in_array($new_sn, $sn_list))
			{
				$new_sn = $attribute_code . mt_rand(0, $max);
			}
			$attribute_code = $new_sn;
		}
	}

	ss_log("return attribute_code: ".$attribute_code);
	return $attribute_code;
}

function admin_insurance_type_delete($ids)
{

	global $_SGLOBAL;
	/////////////////////////////////////////////////////////////

	/*
	 $allowmanage = checkperm('manageblog');
	$managebatch = checkperm('managebatch');
	*/
	$blogs = $newids = array();
	$allowmanage = 1;
	$managebatch = 1;

	$delnum = 0;
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('insurance_type')." WHERE id IN (".simplode($ids).")");
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		if($allowmanage || $value['uid'] == $_SGLOBAL['supe_uid'])
		{
			$blogs[] = $value;
			if(!$managebatch && $value['uid'] != $_SGLOBAL['supe_uid'])
			{
				$delnum++;
			}
		}
	}

	if(empty($blogs) || (!$managebatch && $delnum > 1))
		return array();


	foreach($blogs as $key => $value)
	{
		$newids[] = $value['id'];
	}

	//数据删除
	$_SGLOBAL['db']->query("DELETE FROM ".tname('insurance_type')." WHERE id IN (".simplode($newids).")");

	return $blogs;

}


//得到产品的影响因素列表
function admin_get_company_insure_type_list( $insurer_id	)
{
	global $_SGLOBAL;

	$company_insure_type_list = array();
	////////////////////////////////////////////////////////////////////////
	//////////////得到保险责任////////////////////////////////
	$wheresql = "pt.insurer_id='$insurer_id'";

	$countsql = "SELECT COUNT(*) FROM ".tname('insurance_company_insure_type')." pt WHERE $wheresql";
	//echo $countsql;
	//ss_log($countsql);
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
	if($count)
	{

		$ordersql = "ORDER BY pt.view_order";
		$sql = "SELECT * FROM ".tname('insurance_company_insure_type')." pt WHERE $wheresql $ordersql LIMIT 0,20";
		ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
		while ($valued = $_SGLOBAL['db']->fetch_array($query))
		{
			$company_insure_type_list[] =  $valued;
		}
	}

	return $company_insure_type_list;
}


/////////删除保险公司
function admin_insurance_company_delete($ids)
{

	global $_SGLOBAL;
	/////////////////////////////////////////////////////////////

	/*
	 $allowmanage = checkperm('manageblog');
	$managebatch = checkperm('managebatch');
	*/
	$blogs = $newids = array();
	$allowmanage = 1;
	$managebatch = 1;

	$delnum = 0;
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('insurance_company')." WHERE insurer_id IN (".simplode($ids).")");
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		if($allowmanage || $value['uid'] == $_SGLOBAL['supe_uid'])
		{
			$blogs[] = $value;
			if(!$managebatch && $value['uid'] != $_SGLOBAL['supe_uid'])
			{
				$delnum++;
			}
		}
	}

	if(empty($blogs) || (!$managebatch && $delnum > 1))
		return array();


	foreach($blogs as $key => $value)
	{
		$newids[] = $value['insurer_id'];
	}

	//数据删除
	$_SGLOBAL['db']->query("DELETE FROM ".tname('insurance_company')." WHERE insurer_id IN (".simplode($newids).")");

	return $blogs;

}


///////////////////删除保险责任//////////////////
function admin_insurance_duty_delete($ids)
{

	global $_SGLOBAL;
	/////////////////////////////////////////////////////////////

	/*
	 $allowmanage = checkperm('manageblog');
	$managebatch = checkperm('managebatch');
	*/
	$blogs = $newids = array();
	$allowmanage = 1;
	$managebatch = 1;

	$delnum = 0;
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('insurance_duty')." WHERE id IN (".simplode($ids).")");
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		if($allowmanage || $value['uid'] == $_SGLOBAL['supe_uid'])
		{
			$blogs[] = $value;
			if(!$managebatch && $value['uid'] != $_SGLOBAL['supe_uid'])
			{
				$delnum++;
			}
		}
	}

	if(empty($blogs) || (!$managebatch && $delnum > 1))
		return array();


	foreach($blogs as $key => $value)
	{
		$newids[] = $value['duty_id'];
	}

	//数据删除
	$_SGLOBAL['db']->query("DELETE FROM ".tname('insurance_duty')." WHERE duty_id IN (".simplode($newids).")");

	return $blogs;

}

function admin_insurance_product_delete($ids)
{

	global $_SGLOBAL;
	/////////////////////////////////////////////////////////////

	/*
	 $allowmanage = checkperm('manageblog');
	$managebatch = checkperm('managebatch');
	*/
	$blogs = $newids = array();
	$allowmanage = 1;
	$managebatch = 1;

	$delnum = 0;
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('insurance_product_base')." WHERE product_id IN (".simplode($ids).")");
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		if($allowmanage || $value['uid'] == $_SGLOBAL['supe_uid'])
		{
			$blogs[] = $value;
			if(!$managebatch && $value['uid'] != $_SGLOBAL['supe_uid'])
			{
				$delnum++;
			}
		}
	}

	if(empty($blogs) || (!$managebatch && $delnum > 1))
		return array();


	foreach($blogs as $key => $value)
	{
		$newids[] = $value['product_id'];
	}

	//数据删除
	//
	$_SGLOBAL['db']->query("DELETE FROM ".tname('insurance_product_additional')." WHERE product_id IN (".simplode($newids).")");

	$_SGLOBAL['db']->query("DELETE FROM ".tname('insurance_product_base')." WHERE product_id IN (".simplode($newids).")");

	//外键将不能删除
	return $blogs;

}

//删除属性
function admin_insurance_product_attribute_delete($ids)
{

	global $_SGLOBAL;
	/////////////////////////////////////////////////////////////

	/*
	 $allowmanage = checkperm('manageblog');
	$managebatch = checkperm('managebatch');
	*/
	$blogs = $newids = array();
	$allowmanage = 1;
	$managebatch = 1;

	$delnum = 0;
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('insurance_product_attribute')." WHERE attribute_id IN (".simplode($ids).")");
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		if($allowmanage || $value['uid'] == $_SGLOBAL['supe_uid'])
		{
			$blogs[] = $value;
			if(!$managebatch && $value['uid'] != $_SGLOBAL['supe_uid'])
			{
				$delnum++;
			}
		}
	}

	if(empty($blogs) || (!$managebatch && $delnum > 1))
		return array();


	foreach($blogs as $key => $value)
	{
		$newids[] = $value['attribute_id'];
	}

	$sql = "DELETE FROM ".tname('insurance_product_attribute')." WHERE attribute_id IN (".simplode($newids).")";

	ss_log($sql);
	//数据删除
	$_SGLOBAL['db']->query($sql);

	return $blogs;

}

function admin_insurance_product_influencingfactor_delete($ids)
{

	global $_SGLOBAL;
	/////////////////////////////////////////////////////////////
	ss_log("into function admin_insurance_product_influencingfactor_delete");
	/*
	 $allowmanage = checkperm('manageblog');
	$managebatch = checkperm('managebatch');
	*/
	$blogs = $newids = array();
	$allowmanage = 1;
	$managebatch = 1;

	$delnum = 0;

	$sql = "SELECT * FROM ".tname('insurance_product_influencingfactor')." WHERE product_influencingfactor_id IN (".simplode($ids).")";
	ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		if($allowmanage || $value['uid'] == $_SGLOBAL['supe_uid'])
		{
			$blogs[] = $value;
			if(!$managebatch && $value['uid'] != $_SGLOBAL['supe_uid'])
			{
				$delnum++;
			}
		}
	}

	if(empty($blogs) || (!$managebatch && $delnum > 1))
	{
		ss_log(" null return!");
		return array();
	}


	foreach($blogs as $key => $value)
	{
		$newids[] = $value['product_influencingfactor_id'];
	}


	//数据删除
	$sql = "DELETE FROM ".tname('insurance_product_influencingfactor')." WHERE product_influencingfactor_id IN (".simplode($newids).")";
	ss_log($sql);
	$_SGLOBAL['db']->query($sql);

	return $blogs;

}



function admin_insurance_company_insure_type_delete($ids)
{

	global $_SGLOBAL;
	/////////////////////////////////////////////////////////////
	ss_log("into function admin_insurance_company_insure_type_delete");
	/*
	 $allowmanage = checkperm('manageblog');
	$managebatch = checkperm('managebatch');
	*/
	$blogs = $newids = array();
	$allowmanage = 1;
	$managebatch = 1;

	$delnum = 0;

	$sql = "SELECT * FROM ".tname('insurance_company_insure_type')." WHERE insurance_company_insure_type_id IN (".simplode($ids).")";
	ss_log($sql);
	$query = $_SGLOBAL['db']->query($sql);
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		if($allowmanage || $value['uid'] == $_SGLOBAL['supe_uid'])
		{
			$blogs[] = $value;
			if(!$managebatch && $value['uid'] != $_SGLOBAL['supe_uid'])
			{
				$delnum++;
			}
		}
	}

	if(empty($blogs) || (!$managebatch && $delnum > 1))
	{
		ss_log(" null return!");
		return array();
	}


	foreach($blogs as $key => $value)
	{
		$newids[] = $value['insurance_company_insure_type_id'];
	}


	//数据删除
	$sql = "DELETE FROM ".tname('insurance_company_insure_type')." WHERE insurance_company_insure_type_id IN (".simplode($newids).")";
	ss_log($sql);
	$_SGLOBAL['db']->query($sql);

	return $blogs;

}


function admin_insurance_product_duty_delete($ids)
{

	global $_SGLOBAL;
	/////////////////////////////////////////////////////////////

	/*
	 $allowmanage = checkperm('manageblog');
	$managebatch = checkperm('managebatch');
	*/
	$blogs = $newids = array();
	$allowmanage = 1;
	$managebatch = 1;

	$delnum = 0;

	$sql = "SELECT * FROM ".tname('insurance_product_duty')." WHERE product_duty_id IN (".simplode($ids).")";

	$query = $_SGLOBAL['db']->query($sql);
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		if($allowmanage || $value['uid'] == $_SGLOBAL['supe_uid'])
		{
			$blogs[] = $value;
			if(!$managebatch && $value['uid'] != $_SGLOBAL['supe_uid'])
			{
				$delnum++;
			}
		}
	}

	if(empty($blogs) || (!$managebatch && $delnum > 1))
		return array();


	foreach($blogs as $key => $value)
	{
		$newids[] = $value['product_duty_id'];
	}

	///////////////////先删除所有的该责任对应的价格。
	$sql = "DELETE FROM ".tname('insurance_product_duty_price')." WHERE product_duty_id IN (".simplode($newids).")";
	$_SGLOBAL['db']->query($sql);

	//数据删除
	$sql = "DELETE FROM ".tname('insurance_product_duty')." WHERE product_duty_id IN (".simplode($newids).")";
	//ss_log($sql);
	$_SGLOBAL['db']->query($sql);

	return $blogs;

}


function admin_insurance_product_duty_price_delete($ids)
{

	global $_SGLOBAL;
	/////////////////////////////////////////////////////////////

	/*
	 $allowmanage = checkperm('manageblog');
	$managebatch = checkperm('managebatch');
	*/
	$blogs = $newids = array();
	$allowmanage = 1;
	$managebatch = 1;

	$delnum = 0;

	$sql = "SELECT * FROM ".tname('insurance_product_duty_price')." WHERE product_duty_price_id IN (".simplode($ids).")";

	$query = $_SGLOBAL['db']->query($sql);
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
		if($allowmanage || $value['uid'] == $_SGLOBAL['supe_uid'])
		{
			$blogs[] = $value;
			if(!$managebatch && $value['uid'] != $_SGLOBAL['supe_uid'])
			{
				$delnum++;
			}
		}
	}

	if(empty($blogs) || (!$managebatch && $delnum > 1))
		return array();


	foreach($blogs as $key => $value)
	{
		$newids[] = $value['product_duty_price_id'];
	}

	///////////////////
	$sql = "DELETE FROM ".tname('insurance_product_duty_price')." WHERE product_duty_price_id IN (".simplode($newids).")";
	$_SGLOBAL['db']->query($sql);

	return $blogs;

}

///////设置产品的影响因素。
function admin_insurance_product_influencingfactor_post(
		$POST,
		$olds=array()
)
{
	global $_SGLOBAL, $_SC, $space;
	///////////////////////////////////////////////////////////////////

	if(!empty($olds['product_influencingfactor_id']))//old
	{
		$product_id = $olds['product_id'];
	}
	else
	{
		$product_id = $POST['product_id'];
	}

	//empty($POST['range_note'])
	if( empty($product_id))
	{
		showmessage("必填项没填写！");

		return;
	}

	/////////////////////////////////////
	$isself = 1;

	if(!empty($olds['uid']) && $olds['uid'] != $_SGLOBAL['supe_uid'])
	{
		$isself = 0;

		if( $_SGLOBAL['supe_username']=='admin')//add by wangcya, 20121226, for bug[]
		{

		}
		else
		{

			$__SGLOBAL = $_SGLOBAL;
			$_SGLOBAL['supe_uid'] = $olds['uid'];
			$_SGLOBAL['supe_username'] = addslashes($olds['username']);
		}
	}

	/////////////////////////////////////////////////////////////////////////////


	if($olds['product_influencingfactor_id'])//old
	{

		$product_influencingfactor_type = trim($POST['product_influencingfactor_type']);
		$factor_name = trim($POST['factor_name']);
		$factor_code = trim($POST['factor_code']);//add by wangcya,20141126
		$factor_price = trim($POST['factor_price']);
		$period_min = trim($POST['period_min']);
		$period_max = trim($POST['period_max']);
		$additional_code = trim($POST['additional_code']);
		$factor_id =0;

		$blogarr = array(
				'product_id'=>$product_id,
				'product_influencingfactor_type'=>$product_influencingfactor_type,
				'factor_id'=>$factor_id,
				'factor_name'=>$factor_name,
				'factor_code'=>$factor_code,//add by wangcya,20141126
				'factor_price'=>$factor_price,
				'period_min' =>$period_min,
				'period_max' =>$period_max,
				'additional_code' =>$additional_code,
				
		);
		//////////////////////////////////////////////////////////////////////////////////
		$product_influencingfactor_id = $olds['product_influencingfactor_id'];
		$blogarr['dateline_update'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];

		$blogarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_product_influencingfactor', $blogarr, array('product_influencingfactor_id'=>$product_influencingfactor_id));

		$blogarr['uid'] = $olds['uid'];
		$blogarr['username'] = $olds['username'];
	}
	else
	{
		////////////////////////增加的时候是增加多个的//////////////////////
		$list_influencingfactor  =  $POST['influencingfactor'];
		foreach ($list_influencingfactor as $key=>$value)
		{

			$product_influencingfactor_type = $value['product_influencingfactor_type'];
			$factor_name  = trim($value['factor_name']);
			$factor_code  = trim($value['factor_code']);//add by wangcya,20141126
			$factor_price = trim($value['factor_price']);
			$period_min = trim($value['period_min']);
			$period_max = trim($value['period_max']);
			$additional_code = trim($value['additional_code']);

			$factor_id = 0;
				
			//ss_log("factor_name: ".$factor_name." factor_price: ".$factor_price);
				
			if(!empty($factor_name))
			{
				$blogarr = array(
						'product_id'=>$product_id,
						'product_influencingfactor_type'=>$product_influencingfactor_type,
						'factor_id'=>$factor_id,
						'factor_name'=>$factor_name,
						'factor_code'=>$factor_code,//add by wangcya,20141126
						'factor_price'=>$factor_price,
						'period_min' =>$period_min,
						'period_max' =>$period_max,
						'additional_code'=>$additional_code,
				);

				////////////////////////////////////////////////////////////////
				$sql = "SELECT * FROM ".tname('insurance_product_influencingfactor')." WHERE
				product_id='$product_id' AND factor_name='$factor_name' LIMIT 1";
				$query = $_SGLOBAL['db']->query($sql);
				$product_influencingfactor = $_SGLOBAL['db']->fetch_array($query);
				if(!empty($product_influencingfactor))
				{
					ss_log("this product influencingfactor already exisit!");
					//showmessage("已经存在！");
					continue;
				}
				else
				{
					//ss_log("will insert new product_influencingfactor！");
				}
				////////////////////////////////////////////////////////////////////

				$blogarr['uid'] 		=  empty($_SGLOBAL['supe_uid'])?1:$_SGLOBAL['supe_uid'];
				$blogarr['username'] 	=  $_SGLOBAL['supe_username'];
				$blogarr['dateline_create'] = $blogarr['dateline_update'] = empty($POST['dateline_update'])?$_SGLOBAL['timestamp']:$POST['dateline'];

				$blogarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
				$product_influencingfactor_id = inserttable('insurance_product_influencingfactor', $blogarr, 1);

				usleep(1);
			}
		}//foreach
	}

	$blogarr['product_influencingfactor_id'] = $product_influencingfactor_id;
	//////////////////////////////////////////////////////////////////////////////


	//////////////////////////////////////////////////////////////////////////////

	if(!empty($__SGLOBAL))
		$_SGLOBAL = $__SGLOBAL;

	return $blogarr;
}



///////设置产品的影响因素。
function admin_insurance_company_insure_type_post(
		$POST,
		$olds=array()
)
{
	global $_SGLOBAL, $_SC, $space;
	///////////////////////////////////////////////////////////////////

	if(!empty($olds['insurer_id']))//old
	{
		$insurer_id = $olds['insurer_id'];
	}
	else
	{
		$insurer_id = $POST['insurer_id'];
	}

	//empty($POST['range_note'])
	if( empty($insurer_id))
	{
		showmessage("保险公司id没提供！");

		return;
	}

	/////////////////////////////////////
	$isself = 1;

	if(!empty($olds['uid']) && $olds['uid'] != $_SGLOBAL['supe_uid'])
	{
		$isself = 0;

		if( $_SGLOBAL['supe_username']=='admin')//add by wangcya, 20121226, for bug[]
		{

		}
		else
		{

			$__SGLOBAL = $_SGLOBAL;
			$_SGLOBAL['supe_uid'] = $olds['uid'];
			$_SGLOBAL['supe_username'] = addslashes($olds['username']);
		}
	}

	/////////////////////////////////////////////////////////////////////////////


	if($olds['insurance_company_insure_type_id'])//old
	{
		$attribute_type = trim($POST['attribute_type']);
		$note 			= trim($POST['note']);//add by wangcya,20141126

		$blogarr = array(
				'insurer_id'=>$insurer_id,
				'attribute_type'=>$attribute_type,
				'note'=>$note,
		);
		//////////////////////////////////////////////////////////////////////////////////
		$insurance_company_insure_type_id = $olds['insurance_company_insure_type_id'];

		$blogarr['dateline_update'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];

		$blogarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_company_insure_type',
		$blogarr,
		array('insurance_company_insure_type_id'=>$insurance_company_insure_type_id));

		$blogarr['uid'] = $olds['uid'];
		$blogarr['username'] = $olds['username'];
	}
	else
	{
		////////////////////////增加的时候是增加多个的//////////////////////
		$list_companyinsuretype  =  $POST['companyinsuretype'];
		foreach ($list_companyinsuretype as $key=>$value)
		{

			$attribute_type  = trim($value['attribute_type']);
			$note  			 = trim($value['note']);//add by wangcya,20141126

			//ss_log("factor_name: ".$factor_name." factor_price: ".$factor_price);

			if(!empty($attribute_type))
			{
				$blogarr = array(
						'insurer_id'=>$insurer_id,
						'attribute_type'=>$attribute_type,
						'note'=>$note,
				);

				////////////////////////////////////////////////////////////////
				$sql = "SELECT * FROM ".tname('insurance_company_insure_type')." WHERE
				insurer_id='$insurer_id' AND attribute_type='$attribute_type' LIMIT 1";
				$query = $_SGLOBAL['db']->query($sql);
				$product_influencingfactor = $_SGLOBAL['db']->fetch_array($query);
				if(!empty($product_influencingfactor))
				{
					ss_log("this product influencingfactor already exisit!");
					//showmessage("已经存在！");
					continue;
				}
				else
				{
					//ss_log("will insert new product_influencingfactor！");
				}
				////////////////////////////////////////////////////////////////////////////////////////////////////////

				$blogarr['uid'] 		=  empty($_SGLOBAL['supe_uid'])?1:$_SGLOBAL['supe_uid'];
				$blogarr['username'] 	=  $_SGLOBAL['supe_username'];
				$blogarr['dateline_create'] = $blogarr['dateline_update'] = empty($POST['dateline_update'])?$_SGLOBAL['timestamp']:$POST['dateline'];

				$blogarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
				$insurance_company_insure_type_id = inserttable('insurance_company_insure_type', $blogarr, 1);

				usleep(1);
			}
		}//foreach
	}

	$blogarr['insurance_company_insure_type_id'] = $insurance_company_insure_type_id;
	//////////////////////////////////////////////////////////////////////////////


	//////////////////////////////////////////////////////////////////////////////

	if(!empty($__SGLOBAL))
		$_SGLOBAL = $__SGLOBAL;

	return $blogarr;
}


//start add by wangcya, 20141127
function admin_insert_or_update_duty( $product_id,
		$POST
)
{
	global $_SGLOBAL, $_SC, $space;

	///////////////////////////////////////////////////////////////////

	$duty_id   = $POST['duty_id'];
	$duty_name = $POST['duty_name'];
	$duty_code = $POST['duty_code'];
	$duty_note = $POST['duty_note'];
	$amount    = $POST['amount'];


	ss_log("in function admin_insert_or_update_duty ");
	ss_log("duty_id ".$duty_id);
	ss_log("duty_code ".$duty_code);
	ss_log("duty_name ".$duty_name);

	if(!empty($duty_id))
	{
		$sql = "SELECT * FROM ".tname('insurance_duty')." WHERE duty_id='$duty_id' LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);

		$product_duty = $blog = $_SGLOBAL['db']->fetch_array($query);
		if(!empty($product_duty))
		{
			$duty_name = empty($duty_name)?$product_duty['duty_name']:$duty_name;

			$dutyarr = array(	'duty_name'=>$duty_name,
					'duty_code'=>$duty_code,
					'duty_note'=>$duty_note,
			);

			$dutyarr = saddslashes($dutyarr);//add by wangcya , 20141211,for sql Injection
			updatetable('insurance_duty', $dutyarr, array('duty_id'=>$duty_id));

		}

	}
	else//如果没找到，则插入一条到责任表中
	{

		$sql = "SELECT * FROM ".tname('insurance_product_base')." pb LEFT JOIN
		".tname('insurance_product_attribute')." pa ON pb.attribute_id=pa.attribute_id
		WHERE pb.product_id='$product_id'";

		$query = $_SGLOBAL['db']->query($sql);

		$product = $_SGLOBAL['db']->fetch_array($query);
		$insurer_id = $product['insurer_id'];//保险公司的id

		$POST['insurer_id'] = $insurer_id;

		$insurance_duty_arr = admin_insurance_duty_post($POST,array(),true);//增加责任

		$duty_id = $insurance_duty_arr['duty_id'];
		$duty_name = $insurance_duty_arr['duty_name'];

	}

	$ret = array("duty_id"=>$duty_id,
			"duty_name"=>$duty_name
	);
	/////////////////////////////////////////////////////////////////////////////

	return $ret;
}
//end add by wangcya, 20141127

///////设置产品的责任
function admin_insurance_product_duty_post(
		$POST,
		$olds=array()
)
{
	
	global $_SGLOBAL, $_SC, $space;

	///////////////////////////////////////////////////////////////////
	if(!empty($olds['product_duty_id']))//old
	{
		$product_id = $olds['product_id'];
	}
	else
	{
		$product_id = $POST['product_id'];
	}

	ss_log("admin_insurance_product_duty_post, product_id: ".$product_id);

	//empty($POST['range_note'])
	if( empty($product_id))
	{

		showmessage("没有产品id！");

		return;
	}

	/////////////////////////////////////
	$isself = 1;

	if(!empty($olds['uid']) && $olds['uid'] != $_SGLOBAL['supe_uid'])
	{
		$isself = 0;

		if( $_SGLOBAL['supe_username']=='admin')//add by wangcya, 20121226, for bug[]
		{

		}
		else
		{

			$__SGLOBAL = $_SGLOBAL;
			$_SGLOBAL['supe_uid'] = $olds['uid'];
			$_SGLOBAL['supe_username'] = addslashes($olds['username']);
		}
	}

	//$POST['subject'] = getstr(trim($POST['subject']), 80, 1, 1, 1);

	/////////////////////////////////////////////////////////////////////////////




	/*
	 if($product['product_duty_price_type']=="single")//直接设置价格的方式，则需要在另外一个表中进行增加一条记录。
	{

	}
	*/
	

	if($olds['product_duty_id'])
	{

		$duty_id   = trim($POST['duty_id']);
		$duty_name = trim($POST['duty_name']);
		$duty_code = trim($POST['duty_code']);
		$duty_note = trim($POST['duty_note']);
		$amount    = trim($POST['amount']);
		
		
		///////////////////////////////////////////////////////////
		$ret = admin_insert_or_update_duty($product_id, $POST );
		
		$duty_id   = $ret['duty_id'];
		$duty_name = $ret['duty_name'];
		///////////////////////////////////////////////////////////

		$blogarr = array(
				'product_id'=>$product_id,
				'duty_id'=>$duty_id,
				'duty_name'=>$duty_name,
				'duty_note'=>$duty_note, 
				'amount'=>$amount
		);
	
		$product_duty_id = $olds['product_duty_id'];
		$blogarr['dateline_update'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];

		$blogarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_product_duty', $blogarr, array('product_duty_id'=>$product_duty_id));

		$blogarr['uid'] = $olds['uid'];
		$blogarr['username'] = $olds['username'];
	}
	else
	{


		////////////////////////增加的时候是增加多个的//////////////////////
		$list_productduty =  $POST['productduty'];
		foreach ($list_productduty as $key=>$value)
		{
			/////////////////////////////////////////////////////////////////////////////
			$duty_id   = trim($value['duty_id']);
			$duty_name = trim($value['duty_name']);
			$duty_code = trim($value['duty_code']);
			$duty_note = trim($value['duty_note']);
			$amount    = trim($value['amount']);

			///////////////////////////////////////////////////////////

			///////////////////////////////////////////////////////////
			//ss_log("factor_name: ".$factor_name." factor_price: ".$factor_price);

			if(!empty($duty_name))
			{
				$ret = admin_insert_or_update_duty($product_id, $value );
				$duty_id   = $ret['duty_id'];
				$duty_name = $ret['duty_name'];

				$blogarr = array(
						'product_id'=>$product_id,
						'duty_id'=>$duty_id,
						'duty_name'=>$duty_name,
						'duty_note'=>$duty_note,
						'amount'=>$amount
				);

				////////////////////////////////////////////////////////////////
				$sql = "SELECT * FROM ".tname('insurance_product_duty')." WHERE
				product_id='$product_id' AND duty_name='$duty_name' LIMIT 1";
				$query = $_SGLOBAL['db']->query($sql);
				$product_duty = $_SGLOBAL['db']->fetch_array($query);
				if(!empty($product_duty))
				{
					ss_log("this product duty already exisit!");
					//showmessage("已经存在！");
					continue;
				}
				else
				{
					//ss_log("will insert new product_influencingfactor！");
				}
				////////////////////////////////////////////////////////////////////

				$blogarr['uid'] 		=  empty($_SGLOBAL['supe_uid'])?1:$_SGLOBAL['supe_uid'];
				$blogarr['username'] 	=  $_SGLOBAL['supe_username'];
				$blogarr['dateline_create'] = $blogarr['dateline_update'] = empty($POST['dateline_update'])?$_SGLOBAL['timestamp']:$POST['dateline'];

				$blogarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
				$product_duty_id = inserttable('insurance_product_duty', $blogarr, 1);

				usleep(1);
			}
		}//foreach
	}

	$blogarr['product_duty_id'] = $product_duty_id;
	//////////////////////////////////////////////////////////////////////////////

	//////////////////////////////////////////////////////////////////////////////

	if(!empty($__SGLOBAL))
		$_SGLOBAL = $__SGLOBAL;

	return $blogarr;
}



//////////////设置产品责任的价格/////////////////////////////////

///////设置产品的责任的价格，单一对应的
function admin_insurance_product_duty_price_single_post(
		$POST,
		$olds=array()
		)
{
	global $_SGLOBAL, $_SC, $space;
	///////////////////////////////////////////////////////////////////
	if(empty($olds))//new
	{
		$product_duty_id = $POST['product_duty_id'];
	}
	else
	{
		$product_duty_id = $olds['product_duty_id'];
	}

	//empty($POST['range_note'])
	if( empty($product_duty_id) ||
			empty($POST['amount'])
	)
	{
		showmessage("必填项没填写！");

		return;
	}

	/////////////////////////////////////
	$isself = 1;

	if(!empty($olds['uid']) && $olds['uid'] != $_SGLOBAL['supe_uid'])
	{
		$isself = 0;

		if( $_SGLOBAL['supe_username']=='admin')//add by wangcya, 20121226, for bug[]
		{

		}
		else
		{

			$__SGLOBAL = $_SGLOBAL;
			$_SGLOBAL['supe_uid'] = $olds['uid'];
			$_SGLOBAL['supe_username'] = addslashes($olds['username']);
		}
	}

	//$POST['subject'] = getstr(trim($POST['subject']), 80, 1, 1, 1);


	$blogarr = array(
			'product_duty_id'=>$product_duty_id,
			'product_career_id'=>$POST['product_career_id'],
			'product_period_id'=>$POST['product_period_id'],
			'product_age_id'=>$POST['product_age_id'],
			'amount'=>$POST['amount'],//modify by wangcya , 20140913, 要更改为字符串
			'premium'=>$POST['premium'],
	);


	if($olds['product_duty_price_id'])
	{

		$product_duty_price_id = $olds['product_duty_price_id'];
		$blogarr['dateline_update'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];


		$blogarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_product_duty_price', $blogarr, array('product_duty_id'=>$product_duty_id));

		$blogarr['uid'] = $olds['uid'];
		$blogarr['username'] = $olds['username'];
	}
	else
	{

		$blogarr['uid'] =  empty($POST['uid'])?$_SGLOBAL['supe_uid']:$POST['uid'];
		$blogarr['username'] =  empty($POST['username'])?$_SGLOBAL['supe_username']:$POST['username'];
		$blogarr['dateline_create'] =  $blogarr['dateline_update']  = empty($POST['dateline_update'])?$_SGLOBAL['timestamp']:$POST['dateline'];

		$blogarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		$product_duty_price_id = inserttable('insurance_product_duty_price', $blogarr, 1);
	}

	$blogarr['product_duty_price_id'] = $product_duty_price_id;
	//////////////////////////////////////////////////////////////////////////////

	if(!empty($__SGLOBAL))
		$_SGLOBAL = $__SGLOBAL;

	return $blogarr;
}



//定义保险责任
function admin_insurance_duty_post($POST,
		$olds=array(),
		$retflag = false//是否返回
)
{
	global $_SGLOBAL, $_SC, $space;
	///////////////////////////////////////////////////////////////////

	$insurer_id = intval($POST['insurer_id']);
	$duty_code  = $POST['duty_code'];
	$duty_name  = $POST['duty_name'];

	ss_log("in function ".__FUNCTION__);
	ss_log("insurer_id ".$insurer_id);
	ss_log("duty_code ".$duty_code);
	ss_log("duty_name ".$duty_name);

	if(     /*empty($POST['duty_code'])||*/
			empty($POST['duty_name']) ||
			empty($POST['insurer_id'])
	)
	{

		if(!$retflag)
		{
			showmessage("必填项没填写！");
		}

		return;
	}

	/////////////////////////////////////
	$isself = 1;

	if(!empty($olds['uid']) && $olds['uid'] != $_SGLOBAL['supe_uid'])
	{
		$isself = 0;

		if( $_SGLOBAL['supe_username']=='admin')//add by wangcya, 20121226, for bug[]
		{

		}
		else
		{

			$__SGLOBAL = $_SGLOBAL;
			$_SGLOBAL['supe_uid'] = $olds['uid'];
			$_SGLOBAL['supe_username'] = addslashes($olds['username']);
		}
	}

	//$POST['subject'] = getstr(trim($POST['subject']), 80, 1, 1, 1);

	/////////////////得到保险公司的信息////////////////////////////////////////////


	if($insurer_id)
	{
		$sql = "SELECT * FROM ".tname('insurance_company')." WHERE insurer_id='$insurer_id' LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$parent = $_SGLOBAL['db']->fetch_array($query);

		$insurer_name = $parent['insurer_name'];
	}


	/////////////////////////////////////////////////////////////
	$blogarr = array(
			'duty_code' =>$duty_code,
			'duty_name'=>$duty_name,
			'insurer_id' =>$insurer_id,//保险公司
			'insurer_name'=>$insurer_name,
	);


	if($olds['duty_id'])
	{

		$duty_id = $olds['duty_id'];

		$blogarr['dateline_update'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];

		$blogarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_duty', $blogarr, array('duty_id'=>$duty_id));

		$blogarr['uid'] = $olds['uid'];
		$blogarr['username'] = $olds['username'];
	}
	else
	{
		////////////先查找是否存在////////////////////////////////////////
		$duty_code = $POST['duty_code'];
		$duty_name = $POST['duty_name'];

/*		$sql = "SELECT * FROM ".tname('insurance_duty')." WHERE insurer_id='$insurer_id' AND duty_name='$duty_name' AND duty_code='$duty_code' LIMIT 1";
		//ss_log($sql);
		$query = $_SGLOBAL['db']->query($sql);
		$duty_attr = $_SGLOBAL['db']->fetch_array($query);
		if($duty_attr['duty_id'])
		{
			if(!$retflag)
			{
				showmessage("该责任已经存在！".$retflag);
			}
			else
			{
				$blogarr['duty_id'] = $duty_attr['duty_id'];
				return $blogarr;
			}
				
		}*/
		///////////////////////////////////////////////////////////////////////////

		$blogarr['uid'] =  empty($POST['uid'])?$_SGLOBAL['supe_uid']:$POST['uid'];
		$blogarr['username'] =  empty($POST['username'])?$_SGLOBAL['supe_username']:$POST['username'];

		$blogarr['dateline_create'] = $blogarr['dateline_update'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];

		$setarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		$duty_id = inserttable('insurance_duty', $setarr, 1);
	}

	$blogarr['duty_id'] = $duty_id;

	if(!empty($__SGLOBAL))
		$_SGLOBAL = $__SGLOBAL;

	return $blogarr;
}

//定义产品属性
function admin_insurance_product_attribute_post($POST,
		$olds=array()
)
{
	global $_SGLOBAL, $_SC, $space;
	///////////////////////////////////////////////////////////////////
	if(     empty($POST['attribute_name'])||
			empty($POST['attribute_type']) ||
			empty($POST['ins_type_id'])||
			empty($POST['insurer_id'])||
			empty($POST['business_type'])||
			empty($POST['attribute_code'])//start add by wangcya, 20141213,险种代码
			/*
			 empty($POST['rate_myself'])||
	empty($POST['rate_organization'])||
	empty($POST['rate_recommend'])*/
	)
	{
		showmessage("必填项没填写！");

		return;
	}


	$attribute_code = trim($POST['attribute_code']);
	/////////////////////////////////////////////////////////
	$rate_myself = intval($POST['rate_myself']);
	$rate_organization = intval($POST['rate_organization']);
	$rate_recommend = intval($POST['rate_recommend']);
	$rate_total = intval($POST['rate_total']); //add yes123 2015-03-18  总服务费率

	if($rate_total>100)
	{
		showmessage("您设置的总服务费率大于100！");

		return;
	}

	if(($rate_myself+$rate_organization+$rate_recommend)>$rate_total)
	{
		showmessage("您设置的费率之总和大于总服务费率！");

		return;
	}
	/////////////////////////////////////
	$isself = 1;

	if(!empty($olds['uid']) && $olds['uid'] != $_SGLOBAL['supe_uid'])
	{
		$isself = 0;

		if( $_SGLOBAL['supe_username']=='admin')//add by wangcya, 20121226, for bug[]
		{

		}
		else
		{

			$__SGLOBAL = $_SGLOBAL;
			$_SGLOBAL['supe_uid'] = $olds['uid'];
			$_SGLOBAL['supe_username'] = addslashes($olds['username']);
		}
	}

	//$POST['subject'] = getstr(trim($POST['subject']), 80, 1, 1, 1);

	/////////////////得到保险公司的信息////////////////////////////////////////////
	$insurer_id = intval($POST['insurer_id']);
	if($insurer_id)
	{
		$sql = "SELECT * FROM ".tname('insurance_company')." WHERE insurer_id='$insurer_id' LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$parent = $_SGLOBAL['db']->fetch_array($query);

		$insurer_name = $parent['insurer_name'];
		$insurer_code = $parent['insurer_code'];
	}

	/////////////////得到保险类型信息////////////////////////////////////////////
	$ins_type_id = intval($POST['ins_type_id']);
	if($ins_type_id)
	{
		$sql = "SELECT * FROM ".tname('insurance_type')." WHERE id='$ins_type_id' LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$parent = $_SGLOBAL['db']->fetch_array($query);

		$ins_type_name = $parent['name'];
	}
	/////////////////////////////////////////////////////////////

	$start_day = empty($POST['start_day'])?6:intval($POST['start_day']);

	if(empty($POST['electronic_policy']))
	{
		$electronic_policy = 0;
	}
	else
	{
		$electronic_policy = intval($POST['electronic_policy']);
	}

	ss_log("electronic_policy: ".$electronic_policy);

	$blogarr = array(
			'attribute_name' =>$POST['attribute_name'],
			'attribute_type'=>$POST['attribute_type'],
			'business_type'=>$POST['business_type'],//add by wangcya , 20140807 ,投保人类型，个人或者团体
			'interface_flag' =>intval($POST['interface_flag']),//产品是否对接标示
			'allow_sell' =>intval($POST['allow_sell']),//add by wangcya , 20141127
			'insurer_id' =>$insurer_id,//保险公司
			'insurer_name'=>$insurer_name,
			'insurer_code'=>$insurer_code,
			'ins_type_id' =>$ins_type_id,//险种类别
			'ins_type_name'=>$ins_type_name,
			'rate_total'=>($POST['rate_total']),//add yes123 2015-03-18 总服务费率
			'rate_myself'=>($POST['rate_myself']),//个人服务费率
			'rate_organization'=>($POST['rate_organization']),//机构服务费率
			'rate_recommend'=>($POST['rate_recommend']),//推荐服务费率
			'apply_scope'=>($POST['apply_scope']),//添加适用范围  2014-11-11 yes123
			'description'=>$POST['description'],//产品简介
			'limit_note'=>$POST['limit_note'],//特别约定
			'cover_note'=>($POST['cover_note']),//投保须知
			'claims_guide'=>($POST['claims_guide']),//理赔指南
			'insurance_clauses'=>($POST['insurance_clauses']),//保险条款
			'product_characteristic'=>$POST['product_characteristic'],//产品特色
			'insurance_declare'=>$POST['insurance_declare'],//投保声明
			'insurance_faq'=>$POST['insurance_faq'],//add yes123 2014-12-15 常见问题
			'uid'=>($_SGLOBAL['supe_uid']),//个人服务费率
			'username'=>($_SGLOBAL['supe_username']),//个人服务费率
			'partner_code'=>trim($POST['partner_code']),
			'electronic_policy'=>$electronic_policy,//add by wangcya, 20141124
			'start_day'=>$POST['start_day'],//保险延迟起期
			'is_show_in_app'=>isset ($POST['is_show_in_app']) ? intval($POST['is_show_in_app']) : 0,//add yes123 2015-01-20 是否在app上显示
			'is_show_in_weixin'=>isset ($POST['is_show_in_weixin']) ? intval($POST['is_show_in_weixin']) : 0,//add yes123 2015-01-20 是否在weixin上显示
			'is_show_in_appwebview'=>isset ($POST['is_show_in_appwebview']) ? intval($POST['is_show_in_appwebview']) : 0,//add yes123 2015-01-20 是否在weixin上显示
			'attribute_code'=>$attribute_code//added by zhangxi, 20150507, 增加险种的修改
			);


	if($olds['attribute_id'])
	{

		$attribute_id = $olds['attribute_id'];


		////start add by wangcya, 20141213,险种代码//////////////////////////////
		$wheresql = "attribute_id!='$attribute_id' AND attribute_code = '$attribute_code'";//不等于自己的其他
		$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_attribute')." WHERE $wheresql";
		$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
		if ($count > 0)
		{
			showmessage("险种代码已经存在！");
		}
		elseif(empty($olds['attribute_code']))
		{
			$blogarr['attribute_code'] = $attribute_code;//start add by wangcya, 20141213,险种代码
		}
		////end add by wangcya, 20141213,险种代码//////////////////////////////


		$blogarr['dateline_update'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];

		//add yes123 2014-12-09 判断是否修改过服务费率,如果需改,那么往admin_log表里插入一条记录  start
		$sql = "SELECT rate_myself,rate_organization,rate_recommend FROM t_insurance_product_attribute WHERE attribute_id =".$attribute_id;
		$query = $_SGLOBAL['db']->query($sql);
		$product_attribute  = $_SGLOBAL['db']->fetch_array($query);
		$content="";
		if($blogarr['rate_myself']!=$product_attribute['rate_myself'])
		{
			$content.="个人服务费率".$product_attribute['rate_myself']."修改为:".$blogarr['rate_myself'].";";
		}

		if($blogarr['rate_organization']!=$product_attribute['rate_organization'])
		{
			$content.="机构服务费率".$product_attribute['rate_organization']."修改为:".$blogarr['rate_organization'].";";
		}

		if($blogarr['rate_recommend']!=$product_attribute['rate_recommend'])
		{
			$content.="推荐服务费率".$product_attribute['rate_recommend']."修改为:".$blogarr['rate_recommend'].";";
		}

		if($content)
		{
			//只能获取到管理员名称,拿不到ID,所以只能查询一次
			$sql = "SELECT user_id FROM bx_admin_user WHERE user_name='".$_SESSION['admin_name']."'";
			$query = $_SGLOBAL['db']->query($sql);
			$admin_id = $_SGLOBAL['db']->fetch_array($query);

			$insertsqlarr= array();
			$insertsqlarr['log_time'] = time();
			$insertsqlarr['user_id'] = $admin_id['user_id'];
			$insertsqlarr['log_info'] = '编辑险种:'.$blogarr['attribute_name']."$content";
			$insertsqlarr['ip_address'] = $_SERVER["REMOTE_ADDR"];
			$insertsqlarr['log_level'] = 'warn';
				
			$setarr = saddslashes($insertsqlarr);//add by wangcya , 20141211,for sql Injection
			$log_id = bx_inserttable('admin_log',$setarr , 0);
		}

		//add yes123 2014-12-09 判断是否修改过服务费率,如果需改,那么往admin_log表里插入一条记录  end


		$blogarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_product_attribute', $blogarr, array('attribute_id'=>$attribute_id));

		$blogarr['uid'] = $olds['uid'];
		$blogarr['username'] = $olds['username'];
	}
	else
	{
		////start add by wangcya, 20141213,险种代码//////////////////////////////
		$wheresql = "attribute_code = '$attribute_code'";//不等于自己的其他
		$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_attribute')." WHERE $wheresql";
		$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
		if ($count > 0)
		{
			showmessage("险种代码已经存在！");
		}
		////end add by wangcya, 20141213,险种代码//////////////////////////////

		$blogarr['attribute_code'] = $attribute_code;//start add by wangcya, 20141213,险种代码

		$blogarr['uid'] =  empty($POST['uid'])?$_SGLOBAL['supe_uid']:$POST['uid'];
		$blogarr['username'] =  empty($POST['username'])?$_SGLOBAL['supe_username']:$POST['username'];

		$blogarr['dateline_create'] = $blogarr['dateline_update'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];

		$setarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		$attribute_id = inserttable('insurance_product_attribute', $setarr, 1);
		add_goods($attribute_id,trim($blogarr['attribute_name']));
	}

	$blogarr['attribute_id'] = $attribute_id;

	if(!empty($__SGLOBAL))
		$_SGLOBAL = $__SGLOBAL;

	return $blogarr;
}



///////////////增加保险公司
function admin_insurance_company_post($POST,
		$olds=array()
)
{
	global $_SGLOBAL, $_SC, $space;
	///////////////////////////////////////////////////////////////////
	if(     empty($POST['insurer_code'])||
			empty($POST['insurer_name']) ||
			empty($POST['insurer_abbrname']))
	{
		showmessage("必填项没填写！");

		return;
	}

	/////////////////////////////////////
	$isself = 1;

	if(!empty($olds['uid']) && $olds['uid'] != $_SGLOBAL['supe_uid'])
	{
		$isself = 0;

		if( $_SGLOBAL['supe_username']=='admin')//add by wangcya, 20121226, for bug[]
		{

		}
		else
		{

			$__SGLOBAL = $_SGLOBAL;
			$_SGLOBAL['supe_uid'] = $olds['uid'];
			$_SGLOBAL['supe_username'] = addslashes($olds['username']);
		}
	}

	//$POST['subject'] = getstr(trim($POST['subject']), 80, 1, 1, 1);
	$parent_id = intval($POST['parent_id']);
	if($parent_id)
	{
		$sql = "SELECT * FROM ".tname('insurance_company')." WHERE insurer_id='$parent_id' LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$parent = $_SGLOBAL['db']->fetch_array($query);

		$parent_name = $parent['name'];
	}


	/////////////////////////////////////////////////////////////
	$blogarr = array(
			'insurer_code' =>$POST['insurer_code'],
			'insurer_name'=>$POST['insurer_name'],
			'insurer_abbrname'=>$POST['insurer_abbrname'],
			'logo'=>$POST['logo'],
			'brief'=>$POST['brief'],
			'note'=>$POST['note'],
			'parent_id' =>$parent_id,
			'parent_name'=>$parent_name,
			'partnerName'=>trim($POST['partnerName']),//add by wangcya,20150506
			'BANK_CODE'=>trim($POST['BANK_CODE']),
			'BRNO'=>trim($POST['BRNO']),
			'area_id' =>intval($POST['area_id']),
			'area_name'=>$POST['area_name']
	);


	if($olds['insurer_id'])
	{

		$insurer_id = $olds['insurer_id'];
		$blogarr['dateline_update'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];

		$blogarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_company', $blogarr, array('insurer_id'=>$insurer_id));

		$blogarr['uid'] = $olds['uid'];
		$blogarr['username'] = $olds['username'];
	}
	else
	{

		$blogarr['uid'] =  empty($POST['uid'])?$_SGLOBAL['supe_uid']:$POST['uid'];
		$blogarr['username'] =  empty($POST['username'])?$_SGLOBAL['supe_username']:$POST['username'];
		$blogarr['dateline_update'] = $blogarr['dateline_create'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];
		$setarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		$insurer_id = inserttable('insurance_company', $setarr, 1);
	}

	$blogarr['insurer_id'] = $insurer_id;

	if(!empty($__SGLOBAL))
		$_SGLOBAL = $__SGLOBAL;

	return $blogarr;
}


////////////修改订单，但是不能增加


//定义产品
function admin_insurance_product_post($POST,
		$olds=array()
)
{
	global $_SGLOBAL, $_SC, $space;
	///////////////////////////////////////////////////////////////////
	if(     !isset($POST['product_code'])||
			empty($POST['product_name']) ||
			empty($POST['product_type']) )
	{
		showmessage("必填项没填写！");

		return;
	}

	/////////////////////////////////////
	$isself = 1;

	if(!empty($olds['uid']) && $olds['uid'] != $_SGLOBAL['supe_uid'])
	{
		$isself = 0;

		if( $_SGLOBAL['supe_username']=='admin')//add by wangcya, 20121226, for bug[]
		{

		}
		else
		{

			$__SGLOBAL = $_SGLOBAL;
			$_SGLOBAL['supe_uid'] = $olds['uid'];
			$_SGLOBAL['supe_username'] = addslashes($olds['username']);
		}
	}

	//$POST['subject'] = getstr(trim($POST['subject']), 80, 1, 1, 1);


	/////////////////得到保险产品的属性信息////////////////////////////////////////////
	$attribute_id = intval($POST['attribute_id']);
	if($attribute_id)
	{
		$sql = "SELECT * FROM ".tname('insurance_product_attribute')." WHERE attribute_id='$attribute_id' LIMIT 1";
		$query = $_SGLOBAL['db']->query($sql);
		$product_attribute = $_SGLOBAL['db']->fetch_array($query);

		$attribute_name = $product_attribute['attribute_name'];

		$attribute_type = $product_attribute['attribute_type'];//add by wangcya , 20140913,

	}


	/////////////////////////////////////////////////////////////
	$blogarr = array(
			'product_code' =>$POST['product_code'],
			'product_name'=>$POST['product_name'],
			'product_abbrname'=>$POST['product_abbrname'],
			'attribute_id' =>$POST['attribute_id'],//
			'attribute_name' =>$attribute_name,//
			'product_type'=>$POST['product_type'],//产品类型，产品类还是方案类
			'product_duty_price_type'=> trim($POST['product_duty_price_type']),//产品类型，产品类还是方案类
			'parent_id'=>($POST['parent_id']),//
			'plan_code'=>($POST['plan_code']),//
			'plan_name'=>($POST['plan_name']),//

	);

	if($olds['product_id'])
	{

		$product_id = $olds['product_id'];
		$blogarr['dateline_update'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];

		$blogarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_product_base', $blogarr, array('product_id'=>$product_id));

		$blogarr['uid'] = $olds['uid'];
		$blogarr['username'] = $olds['username'];
	}
	else
	{
		////////////先查找是否存在////////////////////////////////////////
		$product_code = trim($POST['product_code']);

		//start add  by wangcya , 20140913,
		//if($attribute_type =="product")
		if(1)
		{
			$sql = "SELECT * FROM ".tname('insurance_product_base')." WHERE product_code='$product_code' LIMIT 1";
			$query = $_SGLOBAL['db']->query($sql);
			$product_attr = $_SGLOBAL['db']->fetch_array($query);
			if(!empty($product_attr))
			{
				showmessage("该产品代码已经存在！");
				return;
			}
		}
		//end start add  by wangcya , 20140913,

		///////////////////////////////////////////////////////
		$blogarr['uid'] =  empty($POST['uid'])?$_SGLOBAL['supe_uid']:$POST['uid'];
		$blogarr['username'] =  empty($POST['username'])?$_SGLOBAL['supe_username']:$POST['username'];
		$blogarr['dateline_update'] = $blogarr['dateline_create'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];

		$setarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		$id = inserttable('insurance_product_base', $setarr, 1);
	}

	$blogarr['product_id'] = $product_id;

	//////////////产品附加表//////////////////////////////////////////////
	if(1)//$blogarr['product_duty_price_type']=='single')
	{

		$blogarr_additional = array(
				'age_min' =>intval($POST['age_min']),
				'age_max'=>intval($POST['age_max']),
				'premium'=>$POST['premium'],
				'sum_insured'=>$POST['sum_insured'], //add yes123 2015-04-24 总保额
				'number' =>intval($POST['number']),
				'period' =>intval($POST['period']),
				'period_uint'=>trim($POST['period_uint']),
				'rate'=>trim($POST['rate'])//added by zhangxi, 20150616, 增加费率，太平洋货运险配置
		);


		//ss_log("dsfsdfsd: ".$olds['product_id']);
		///////////////////////////////////////////////////////////

		$sql = "SELECT * FROM ".tname('insurance_product_additional')." WHERE product_id='$product_id'";
		$query = $_SGLOBAL['db']->query($sql);
		$product_additional = $_SGLOBAL['db']->fetch_array($query);
		if(empty($product_additional))//new
		{
			$blogarr_additional['product_id'] = $product_id;
				
			$blogarr_additional = saddslashes($blogarr_additional);//add by wangcya , 20141211,for sql Injection
			inserttable('insurance_product_additional', $blogarr_additional);
		}
		else
		{
			$blogarr_additional = saddslashes($blogarr_additional);//add by wangcya , 20141211,for sql Injection
			updatetable('insurance_product_additional', $blogarr_additional, array('product_id'=>$product_id));
		}

	}

	////////////////////////////////////////////////////////////
	if(!empty($__SGLOBAL))
		$_SGLOBAL = $__SGLOBAL;

	return $blogarr;
}


function admin_get_insurance_product_influencingfactor($product_id,$type)
{
	global $_SGLOBAL;
	///////////////////////////////////////////////////////////////////////////
	$wheresql = "product_influencingfactor_type='$type' AND product_id='$product_id'";//不等于自己的其他
	$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_influencingfactor')." WHERE $wheresql";
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
	{

		$ordersql = " ORDER BY dateline_create";
		$sql = "SELECT * FROM ".tname('insurance_product_influencingfactor')." WHERE $wheresql $ordersql";
		$query = $_SGLOBAL['db']->query($sql);

		$list_product_influencingfactor_period = array();
		while ($value = $_SGLOBAL['db']->fetch_array($query))
		{
			$list_product_influencingfactor_period[] =  $value;
		}
	}

	return $list_product_influencingfactor_period;

}

/////////////////////给产品责任设置价格////////////////////////////

///////设置产品的影响因素。
function admin_insurance_product_duty_price_multiple_post(
															$POST,
															$olds=array()
															)
{
	global $_SGLOBAL, $_SC, $space;
	///////////////////////////////////////////////////////////////////

	//comment by zhangxi, 20150708, 编辑的情况
	if(!empty($olds['product_duty_price_id']))//old
	{
		$product_duty_id = $olds['product_duty_id'];
	}
	else//comment by zhangxi, 20150708, 新增的情况
	{
		$product_duty_id = $POST['product_duty_id'];
	}

	//empty($POST['range_note'])
	if( empty($product_duty_id))
	{
		showmessage("必填项没填写！");

		return;
	}

	/////////////////////////////////////
	$isself = 1;

	if(!empty($olds['uid']) && $olds['uid'] != $_SGLOBAL['supe_uid'])
	{
		$isself = 0;

		if( $_SGLOBAL['supe_username']=='admin')//add by wangcya, 20121226, for bug[]
		{

		}
		else
		{

			$__SGLOBAL = $_SGLOBAL;
			$_SGLOBAL['supe_uid'] = $olds['uid'];
			$_SGLOBAL['supe_username'] = addslashes($olds['username']);
		}
	}

	/*
	 //////////////////找到这个产品的影响因素列表//////////////////////////////////////////////////////
	$wheresql = "product_duty_id='$product_duty_id'";//不等于自己的其他
	$sql = "SELECT * FROM ".tname('insurance_product_duty')." WHERE $wheresql LIMIT 1";
	$product_duty = $_SGLOBAL['db']->query($sql);
	$product_id = $product_duty['product_id'];

	$wheresql = "product_id='$product_id'";//不等于自己的其他
	$countsql = "SELECT COUNT(*) FROM ".tname('insurance_product_influencingfactor')." WHERE $wheresql";
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($countsql), 0);
	{

	$ordersql = " ORDER BY dateline_create";
	$sql = "SELECT * FROM ".tname('insurance_product_influencingfactor')." WHERE $wheresql $ordersql";
	$query = $_SGLOBAL['db']->query($sql);

	$list_product_influencingfactor = array();
	while ($value = $_SGLOBAL['db']->fetch_array($query))
	{
	$list_product_influencingfactor[$value['product_influencingfactor_id']] =  $value;
	}
	}
	*/
	/////////////////////////////////////////////////////////////////////////////

	if($olds['product_duty_price_id'])//old，这个是修改的处理分之?
	{

		$product_period_id = intval($POST['period']);
		$product_career_id = intval($POST['career']);
		$product_age_id = intval($POST['age']);

		/*
		 $product_period_name = $list_product_influencingfactor[$product_period_id][factor_name];
		$product_career_name = $list_product_influencingfactor[$product_career_id][factor_name];
		$product_age_name = $list_product_influencingfactor[$product_age_id][factor_name];
		*/



		$amount = $POST['amount']; //modify by wangcya , 20140913, 要更改为字符串
		$premium = $POST['premium'];
		$limitAmount = $POST['limitAmount'];
		/////////////////////////////////////////////////
		if( empty($amount))		
		{
			showmessage("保额不能为空");

			return;
		}
		//////////////////////////////////////////////////////////

		$blogarr = array(
				'product_duty_id'=>$product_duty_id,
				'product_period_id'=>$product_period_id,
				'product_career_id'=>$product_career_id,
				'product_age_id'=>$product_age_id,
				//'product_period_name'=>$product_period_name,
				//'product_career_name'=>$product_career_name,
				//'product_age_name'=>$product_age_name,
				'limitAmount'=>$limitAmount,
				'amount'=>$amount,
				'premium'=>$premium,
		);

		//////////////////////////////////////////////////////////////////////////////////
		$product_duty_price_id = $olds['product_duty_price_id'];
		$blogarr['dateline_update'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];

		$blogarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
		updatetable('insurance_product_duty_price', $blogarr, array('product_duty_price_id'=>$product_duty_price_id));

		$blogarr['uid'] = $olds['uid'];
		$blogarr['username'] = $olds['username'];
	}
	else//comment by zhangxi, 20150708, 增加是走这里
	{
		////////////////////////增加的时候是增加多个的//////////////////////
		$list_product_price_input  =  $POST['product_price_input'];
		foreach ($list_product_price_input as $key=>$value)
		{

			$product_period_id = intval($value['period']);
			$product_career_id = intval($value['career']);
			$product_age_id = intval($value['age']);

			/*
			 $product_period_name = $list_product_influencingfactor[$product_period_id][factor_name];
			$product_career_name = $list_product_influencingfactor[$product_career_id][factor_name];
			$product_age_name = $list_product_influencingfactor[$product_age_id][factor_name];
			*/
			$limitAmount= $value['limitAmount'];
			$amount = $value['amount'];//modify by wangcya , 20140913, 要更改为字符串
			$premium = $value['premium'];
			//comment by zhangxi, 20150708, 保费和保额大于0才会执行
			if(!empty($amount))
			{
					
				$blogarr = array(
						'product_duty_id'=>$product_duty_id,
						'product_period_id'=>$product_period_id,
						'product_career_id'=>$product_career_id,
						'product_age_id'=>$product_age_id,
						/*'product_period_name'=>$product_period_name,
						 'product_career_name'=>$product_career_name,
						'product_age_name'=>$product_age_name,*/
						'limitAmount'=>$limitAmount,
						'amount'=>$amount,
						'premium'=>$premium,
				);

				////////////////////////////////////////////////////////////////
				/*
				 $sql = "SELECT * FROM ".tname('insurance_product_duty_price')." WHERE
				product_duty_id='$product_duty_id' AND factor_name='$factor_name' LIMIT 1";
				$query = $_SGLOBAL['db']->query($sql);
				$product_influencingfactor = $_SGLOBAL['db']->fetch_array($query);
				if(!empty($product_influencingfactor))
				{
				//showmessage("已经存在！");
				continue;
				}
				*/
				////////////////////////////////////////////////////////////////////

				$blogarr['uid'] =  empty($POST['uid'])?$_SGLOBAL['supe_uid']:$POST['uid'];
				$blogarr['username'] =  empty($POST['username'])?$_SGLOBAL['supe_username']:$POST['username'];
				$blogarr['dateline_create'] = $blogarr['dateline_update'] = empty($POST['dateline_update'])?$_SGLOBAL['timestamp']:$POST['dateline'];

				$blogarr = saddslashes($blogarr);//add by wangcya , 20141211,for sql Injection
				$product_duty_price_id = inserttable('insurance_product_duty_price', $blogarr, 1);

				usleep(1);
			}//if
		}//foreach
	}

	$blogarr['product_duty_price_id'] = $product_duty_price_id;
	//////////////////////////////////////////////////////////////////////////////


	//////////////////////////////////////////////////////////////////////////////

	if(!empty($__SGLOBAL))
		$_SGLOBAL = $__SGLOBAL;

	return $blogarr;
}

