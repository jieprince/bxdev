<?php
include_once (ROOT_PATH . 'includes/class/commonUtils.class.php');
class Index {

    function Index() {
    }
    
    
    //获取index页面基本数据
    public function getIndexBaseDate($position='shop'){
    	//$user_count = $this->getUserCount(); //获取用户总数
    	//$show_info = $this->getNewestUser(); //获取前5个新注册用户
    	//$show_info = $this->getNewestIncome($show_info); //获取前5个获得收益的用户
    	$article_information = $this->getInformation();//平台资讯
    	$ebao_hot_goods = $this->getHotGoods(); //获取热门产品
    	$links = $this->index_get_links();


    	$afficheimg = $this->getAfficheimg($position);
    	
    	
    	include_once(ROOT_PATH . 'includes/class/Article.class.php');
	    $article_obj = new Article;
	    
	    if($position=='shop')
		{
			$cat_name='商城常见问题';
		}
		$res = $article_obj->getIndexFAQ($cat_name,10);

    	
    	$base_data = array();
    	//$base_data['user_count'] = $user_count;
    	//$base_data['show_info'] = $show_info;
    	$base_data['article_information'] = $article_information;
    	$base_data['ebao_hot_goods'] = $ebao_hot_goods;
    	$base_data['links'] = $links;
    	$base_data['faq'] = $res;
    	$base_data['afficheimg'] = $afficheimg;
    	return $base_data;
    }
    
    
    //获取用户总数
    public function getUserCount(){
    	$user_count_sql="SELECT COUNT(*) FROM ". $GLOBALS['ecs']->table('users');
		$user_count = $GLOBALS['db']->getOne($user_count_sql);
    	return $user_count;
    }
    
    //获取轮播图
    public function getAfficheimg($position='shop'){
    	
    	$distributor_cfg = get_organ_config();
    	if($distributor_cfg)
    	{
    		$organ_id = $distributor_cfg['d_uid'];
    	}
    	else
    	{
    		$organ_id = 0;
    	}
    	
    	$where = "WHERE position='$position'";
    	$sql="SELECT * FROM ". $GLOBALS['ecs']->table('afficheimg')." $where ORDER BY img_sort ASC";
		if(empty($afficheimg_list))
		{
			$sql="SELECT * FROM ". $GLOBALS['ecs']->table('afficheimg')." $where ORDER BY img_sort ASC";
			
		}
		
		$afficheimg_list = $GLOBALS['db']->getAll($sql);
    	return $afficheimg_list;
    }
    
    //获取前5个新注册用户
    private function getNewestUser(){
    	$show_info= array();
    	
		$user_info_sql="SELECT user_name FROM ". $GLOBALS['ecs']->table('users')." ORDER BY  reg_time DESC LIMIT 0,5";
		$user_info_list = $GLOBALS['db']->getAll($user_info_sql);
		foreach ( $user_info_list as $value ) {
			//modify yes123 2014-12-01  修正截字符串乱码的问题
			if(CommonUtils::isPhoneNumber($value['user_name']))
			{
				$user_name_str =  substr($value['user_name'],0,3)."*****".substr($value['user_name'],8,3);
			}
			else
			{
				$user_name_str = CommonUtils::cutStr($value['user_name'],3,0)."****";
			}
			$show_info[]="新用户(".$user_name_str.")完成注册";
		
    	}
    	
    	return $show_info;
    }
    
    //获取前5个获得收益的用户
    private function getNewestIncome($show_info){
    	$account_log_sql="SELECT a.user_money,u.user_name FROM ". $GLOBALS['ecs']->table('account_log')." a,". $GLOBALS['ecs']->table('users') ." u WHERE u.user_id=a.user_id AND incoming_type='订单服务费' AND a.user_money>0 ORDER BY change_time DESC LIMIT 0,5";
		$account_log =$GLOBALS['db']->getAll($account_log_sql);

		foreach ($account_log as $value ) {
			//modify yes123 2014-12-01  修正截字符串乱码的问题
	     	if(CommonUtils::isPhoneNumber($value['user_name']))
			{
				$user_name_str =  substr($value['user_name'],0,3)."*****".substr($value['user_name'],8,3);
			}
			else
			{
				$user_name_str = CommonUtils::cutStr($value['user_name'],3,0)."****";
			}
			$show_info[]=$user_name_str."已获得收入".$value['user_money']."元";
		}
	
		return $show_info;
		
    }
    
    //start 平台咨询
    public function getInformation(){
    	$cat_id_sql="SELECT cat_id FROM ". $GLOBALS['ecs']->table('article_cat')." WHERE cat_name='平台资讯'";
		$cat_id = $GLOBALS['db']->getOne($cat_id_sql);
		$article_information = $this->get_article($cat_id,0,7);
		return $article_information;
    }
    private function get_article($cat_id,$page,$size)
	{
		$article_sql ="SELECT * FROM ". $GLOBALS['ecs']->table('article')." WHERE cat_id=".$cat_id." ORDER BY add_time desc LIMIT ".$page.",".$size;
		$article_information = $GLOBALS['db']->getAll($article_sql);
		return $article_information;
	}
	//end 平台咨询
	
	//获取热门商品
	public function getHotGoods(){
		$where_sql= " g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND g.is_hot=1 ";
		$is_weixin = isset ($_REQUEST['is_weixin']) ? intval($_REQUEST['is_weixin']) : 0;
		if($is_weixin){
			$where_sql .= " AND ipa.is_show_in_weixin=1 ";
		}
		
		
		//add yes123 2015-05-15判断，如果是独占渠道，那么只只展示渠道下的产品 start
		if($_SESSION['user_id'])
		{
			$user_info = user_info_by_userid($_SESSION['user_id']);
			$institution_id = $user_info['institution_id'];
			
			
			if($user_info['rank_code']==ORGANIZATION_USER)
			{
				$institution_id = $user_info['institution_id'];
			}
			
			if($institution_id)
			{
				$sql = "SELECT d_institution_type FROM "  . $GLOBALS['ecs']->table('distributor') ." WHERE d_uid ='$institution_id' ";
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
						$where_sql .= " AND ipa.attribute_id IN($attribute_ids_str) ";
					}
					
				}
			}
			
			
		}
		
		$hot_goods_sql = " SELECT g.goods_id,g.goods_name,g.shop_price,g.goods_brief,g.tid,g.goods_thumb,g.goods_img,g.detail_url,ipa.product_characteristic FROM ". $GLOBALS['ecs']->table('goods') ." AS g
				           INNER JOIN t_insurance_product_attribute AS ipa ON g.tid=ipa.attribute_id 
				           WHERE $where_sql ORDER BY g.sort_order ASC,g.goods_id DESC LIMIT 2 ";
					
		$ebao_hot_goods = $GLOBALS['db']->getAll($hot_goods_sql);
		//add yes123 2015-01-05 获取其他属性
/*		foreach ( $ebao_hot_goods as $key => $goods ) {
			 $ebao_hot_goods[$key]['ins_product_list'] = get_ins_product_list_by_attribute($goods['tid'],'','');
			 $ebao_hot_goods[$key]['ins_product_list_select'] = $ebao_hot_goods[$key]['ins_product_list'][0];
			 $ebao_hot_goods[$key]['rate_myself'] = doubleval($goods['rate_myself']);
		}*/

		return $ebao_hot_goods;
	}
	
	
	//友情链接
	public function index_get_links($table_name='friend_link')
	{
	    $sql = 'SELECT link_logo, link_name, link_url FROM ' . $GLOBALS['ecs']->table($table_name) . ' ORDER BY show_order';
	    $res = $GLOBALS['db']->getAll($sql);
	
	    $links['img'] = $links['txt'] = array();
	
	    foreach ($res AS $row)
	    {
	        if (!empty($row['link_logo']))
	        {
	            $links['img'][] = array('name' => $row['link_name'],
	                                    'url'  => $row['link_url'],
	                                    'logo' => $row['link_logo']);
	        }
	         $links['txt'][] = array('name' => $row['link_name'],
	                                    'url'  => $row['link_url']);
	    }
	
	    return $links;
	}
    
}
?>
