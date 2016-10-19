<?php
class GoodsList {

	public $where_sql;

	public $order_sql;
	function GoodsList() {
	}

	public function getGoodsList() {
		$user_id = $_SESSION['user_id'] == null ? "0" : $_SESSION['user_id'];
		$condition = $this->whereCondition();

		//获取保险公司
		$brand_list = $this->getBrands();
		//获取分类树
		$categorie = get_categories_tree(0);
		
		//初始化分页
		$page = isset ($_REQUEST['page']) && intval($_REQUEST['page']) > 0 ? intval($_REQUEST['page']) : 1;
		$size = isset ($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0 ? intval($_REQUEST['page_size']) : 5;

		//板当前位置
		//$new_themes_ur_here = "<a href='category.php?act=hot_goods_list'>热销产品</a>";
		
		//获取总数
		$count = $this->getCount();
		
		//mod by zhangxi, 20150608, 增加险种类型字段输出
		//modify yes123 2015-01-23 产品销量数据重新设计
		$sql = 'SELECT ipa.insurer_code,ipa.attribute_type,ipa.is_show_in_app,ipa.is_show_in_appwebview, ipa.start_day, ipa.product_characteristic,ipa.insurer_id,ipa.rate_myself,' .
			   ' ipa.description AS in_description , ipa.attribute_name AS in_attribute_name ,ipa.attribute_id ,ipa.apply_scope,'.
		       ' g.goods_id, g.goods_name, g.shop_price AS org_price,g.goods_type,'.
		       ' g.goods_thumb , g.goods_img ,g.detail_url,cg.rec_id AS is_collect,g.cat_id,g.goods_sales_volume '.
	           ' FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
		       ' LEFT JOIN ' . $GLOBALS['ecs']->table('collect_goods') . ' AS cg ON g.goods_id = cg.goods_id AND  cg.user_id= ' . $user_id . //add yes123 是否收藏
		       ' INNER JOIN t_insurance_product_attribute AS ipa ON ipa.attribute_id = g.tid  ' .
		       ' WHERE  '.$this->where_sql.'  ORDER BY '.$condition['sort'] .' '.$condition['order'];
		
		//ss_log('获取热门产品列表：'.$sql);
		$res = $GLOBALS['db']->selectLimit($sql, $size, ($page -1) * $size);
		$arr = array ();

		while ($row = $GLOBALS['db']->fetchRow($res)) {

			$arr[$row['goods_id']]['goods_id'] = $row['goods_id'];

			$arr[$row['goods_id']]['goods_name'] = $row['goods_name'];

			//start add by wangcya , 20140802
			$row['goods_name'] = $row['in_attribute_name']; //add by wangcya , 20140802
			
			//added by zhangxi, 20150608
			$arr[$row['goods_id']]['attribute_type'] = $row['attribute_type'];

			$arr[$row['goods_id']]['goods_name'] = $row['in_attribute_name'];
			$arr[$row['goods_id']]['apply_scope'] = $row['apply_scope']; //add by yes123 2014-11-11
			$arr[$row['goods_id']]['goods_brief'] = $row['in_description'];
			$arr[$row['goods_id']]['start_day'] = $row['start_day']; //add by dingchaoyang 2014-11-21
			$arr[$row['goods_id']]['is_show_in_app'] = $row['is_show_in_app']; //add by dingchaoyang 2014-12-8
			$arr[$row['goods_id']]['insurer_code']      = $row['insurer_code'];//add by dingchaoyang 2015-1-10
			$arr[$row['goods_id']]['cat_id'] = $row['cat_id']; //add by dingchaoyang 2014-12-10
			$arr[$row['goods_id']]['is_show_in_appwebview'] = $row['is_show_in_appwebview']; //add by dingchaoyang 2015-3-19
			$arr[$row['goods_id']]['product_characteristic'] = $row['product_characteristic']; //add by wangcya , 20140913

			//add yes123 2014-12-11  添加销量
			$arr[$row['goods_id']]['og_total'] =$row['goods_sales_volume']; //add by wangcya , 20140913
			///////得到该产品属性下面的产品列表
			$arr[$row['goods_id']]['ins_product_list'] = get_ins_product_list_by_attribute($row['attribute_id']);

			//end add by wangcya , 20140802

			$arr[$row['goods_id']]['ins_product_list_select'] = $arr[$row['goods_id']]['ins_product_list'][0]; //add by wangcya , 20140914

			$arr[$row['goods_id']]['name'] = $row['goods_name'];
			
			$arr[$row['goods_id']]['shop_price'] = price_format($row['org_price']);
			
			//add start  2014-10-04 yes123  热门商品显示的必要属性
			$insurance_company = $this->insurer_name_logoByinsurer_Id($row['insurer_id']);
			$arr[$row['goods_id']]['insurer_name'] = $insurance_company['insurer_name'];
			$arr[$row['goods_id']]['logo'] = $insurance_company['logo'];
			//end  

			//add by yes123 2014-11-18 去掉小数后面多余的0      
			include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
			$arr[$row['goods_id']]['rate_myself'] = CommonUtils :: decimal_zero_suppression($row['rate_myself']);

			//add yes123 是否收藏
			$arr[$row['goods_id']]['is_collect'] = $row['is_collect'];

			$arr[$row['goods_id']]['detail_url'] = $row['detail_url'];
			$arr[$row['goods_id']]['type'] = $row['goods_type'];
			$arr[$row['goods_id']]['goods_thumb'] = get_image_path($row['goods_id'], $row['goods_thumb'], true);
			$arr[$row['goods_id']]['goods_img'] = get_image_path($row['goods_id'], $row['goods_img']);
			$arr[$row['goods_id']]['url'] = build_uri('goods', array ('gid' => $row['goods_id']), $row['goods_name']);

		}
		
		
		if($condition['cat_id'])
		{
			//获取分类信息
			$sql = "SELECT cat_id,cat_name,cat_desc FROM ".$GLOBALS['ecs']->table('category') ." WHERE cat_id=$condition[cat_id]";
			$category = $GLOBALS['db']->getRow($sql);
		}
		
		
		return array (
			'goods_list' => $arr,
			'condition' => $condition,
			'brand_list' => $brand_list,
			'categorie' => $categorie,
			'current_category'=>$category,
			'count' => $count,
			'page' => $page,
			'size' => $size
		);

	}

	public function whereCondition() {
		//{$og_total_order}
		$condition['act'] = isset ($_REQUEST['act']) ?trim($_REQUEST['act']): ''  ;
		//排序 	
		$condition['sort'] = isset ($_REQUEST['sort']) ?trim($_REQUEST['sort']): 'g.sort_order'  ; 
		$condition['order'] = isset ($_REQUEST['order']) ? trim($_REQUEST['order']):'asc' ;
		
		//add by dingchaoyang 2015-1-23
		if ($condition['sort'] == 'og_total'){
			$condition['sort']='goods_sales_volume';
		}
		if (isset ($_REQUEST['id']) && !empty($_REQUEST['id'])){
			$_REQUEST['cat_id'] = $_REQUEST['id'];
		}
		//end
		
		if($condition['sort']=='add_time')
		{
			$condition['sort'] =' g.add_time ';
			$condition['add_time_order'] = $condition['order'];
		}elseif($condition['sort']=='goods_sales_volume'){
			$condition['sales_volume_order'] = $condition['order'];
		}
		
		//品牌
		$condition['brand_id'] = isset ($_REQUEST['brand_id']) ? intval($_REQUEST['brand_id']) : 0;
		//分类ID
		$condition['cat_id'] = isset ($_REQUEST['cat_id']) ? intval($_REQUEST['cat_id']) : 0;
		//商品名称
		$condition['goods_name'] = isset ($_REQUEST['goods_name']) ? $_REQUEST['goods_name'] : "";
		
		//$this->where_sql .= " g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND ipa.interface_flag=1 ";//del by wangcya, 20150117
		$this->where_sql .= " g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 ";//add by wangcya, 20150117
		
		if ($condition['brand_id']) {
			$this->where_sql .= " AND brand_id=" . $condition['brand_id'];
		}
		if ($condition['cat_id']) {
			$this->where_sql .= " AND cat_id=" . $condition['cat_id'];
		}
		if ($condition['goods_name']) {
			$this->where_sql .= " AND ipa.attribute_name LIKE '%" . $condition['goods_name'] . "%'";
		}
		
		//goodid
		//add by dingchaoyang 2014-12-10
		if (isset($_REQUEST['good_id'])){
			$good_id_ = intval($_REQUEST['good_id']);//add by dingchaoyang 2014-12-10
			if ($good_id_ ){
				$this->where_sql .= " and g.goods_id = " . $good_id_ . " ";
			}
		}
		//end by dingchaoyang 2014-12-10
		
		$is_weixin = isset ($_REQUEST['is_weixin']) ? intval($_REQUEST['is_weixin']) : 0;
		if($is_weixin){
			$this->where_sql .= " AND ipa.is_show_in_weixin=1 ";
		}
		
		
		//add yes123 2015-05-15判断，如果是独占渠道，那么只只展示渠道下的产品 end
		$distributor_cfg = get_organ_config();
		if($distributor_cfg)
		{
			$institution_id = $distributor_cfg['d_uid'];
			$sql = "SELECT d_institution_type FROM "  . $GLOBALS['ecs']->table('distributor') ." WHERE d_uid ='$institution_id' ";
			//ss_log(__FUNCTIOIN__." 获取d_institution_type信息:".$sql);
			$d_institution_type = $GLOBALS['db']->getOne($sql);
			if($d_institution_type=='private')
			{
				$sql = " SELECT attribute_id FROM bx_organ_ipa_rate_config WHERE institution_id='$institution_id'";
				//ss_log(__FUNCTIOIN__." 获取attribute_id 列表:".$sql);
				$attribute_id_list = $GLOBALS['db']->getAll($sql);
				if($attribute_id_list)
				{
					include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
					$attribute_ids_str = CommonUtils :: arrToStr($attribute_id_list, "attribute_id");
					$this->where_sql .= " AND ipa.attribute_id IN($attribute_ids_str) ";
				}
				else
				{
					$this->where_sql .= " AND ipa.attribute_id IN(0) ";
					
				}
				
			}
		}
		//add yes123 2015-05-15判断，如果是独占渠道，那么只只展示渠道下的产品 end
		
		return $condition;

	}

	public function getCount() {
	    $sql = 'SELECT COUNT(*)'.
	            'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
	            " INNER JOIN t_insurance_product_attribute AS ipa ON ipa.attribute_id = g.tid  " . 
	            " WHERE " .$this->where_sql;
		$count = $GLOBALS['db']->getOne($sql);
		return $count;
	}

	/**
	 * 取得品牌列表
	 * @return array 品牌列表 id => name
	 */
	public function getBrands() {
		$sql = 'SELECT brand_id, brand_name FROM ' . $GLOBALS['ecs']->table('brand') . ' WHERE is_show=1 ORDER BY sort_order  ';
		$res = $GLOBALS['db']->getAll($sql);
		return $res;
	}
	
	//通过品牌ID获取品牌
	public function getBrandNameById($brand_id){
		$sql = 'SELECT brand_id, brand_name FROM ' . $GLOBALS['ecs']->table('brand') . ' WHERE brand_id='.$brand_id;
		$res = $GLOBALS['db']->getRow($sql);
		return $res;
	}
	
	//通过产品分类ID获取分类
	public function getCategory($cat_id){
		$sql = 'SELECT cat_id, cat_name FROM ' . $GLOBALS['ecs']->table('category') . ' WHERE cat_id='.$cat_id;
		$res = $GLOBALS['db']->getRow($sql);
		return $res;
	}		
	
	/**
	 * add  2014-10-04
	 * yes123
	 * 通过保险公司ID获取，保险公司名称和logo
	 */
	function insurer_name_logoByinsurer_Id($insurer_id){
		$sql="SELECT insurer_name,substring(logo,4) logo FROM t_insurance_company WHERE insurer_id = ".$insurer_id;
		return  $GLOBALS['db']->getRow($sql);
		
	}
	
}
?>