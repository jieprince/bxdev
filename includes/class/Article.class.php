<?php
/*
 * 文章相关类
 * 
 * */
 
include_once (ROOT_PATH . 'includes/class/commonUtils.class.php'); 
class Article {

    function Article() {
    }

	public function getArticleList($cat_name='') {
	
		global $_LANG,$article_type_list;
		$condition = array ();
		$condition['act']=$_REQUEST['act'];
		$condition['cat_id']=$_REQUEST['cat_id'];
		$condition['article_type']=isset($_REQUEST['article_type'])?trim($_REQUEST['article_type']):'';
		
		
		$sort_order = " ORDER BY sort_order ASC,article_id DESC ";
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('article');
		$record_count_sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('article') ;
		$where = " WHERE is_open=1 ";
		
		
		
		
		$parent_cat_id = 0;
		if($cat_name)
		{
			$article_cat_sql="SELECT cat_id FROM ". $GLOBALS['ecs']->table('article_cat') . " WHERE cat_name='$cat_name'";
			$parent_cat_id = $GLOBALS['db']->getOne($article_cat_sql);
			if($parent_cat_id)
			{
				$child_article_cat_list = $this->getChildCat($parent_cat_id,$cat_name);
				if(!empty($child_article_cat_list))
				{
					$cat_ids_str = CommonUtils::arrToStr($child_article_cat_list,"cat_id");
					if($cat_ids_str)
					{
						$where.= " AND cat_id IN($cat_ids_str) ";
					}
					
				}
				
			}
			
		}
		
		
		$article_cat = array();
		if($condition['cat_id'])
		{
			$article_cat = $this->getArticleCat($condition['cat_id']);
						
			$where.= " AND cat_id =$condition[cat_id] ";
		}
		
		//获取总数
		$record_count = $GLOBALS['db']->getOne($record_count_sql . $where);
		$page = isset ($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$pager = get_pager('category.php', $condition, $record_count, $page);

		if ($page > 1) {
			$page = ($page -1) * $pager['size'];
			$page_sql .= " LIMIT " . $page . "," . $pager['size'];
		} else {
			$page_sql .= " LIMIT 0 ," . $pager['size'];
		}
	
	
		$article_list = $GLOBALS['db']->getAll($sql . $where .$sort_order.$page_sql);
		
		
		
		//获取合并后的文章 start
		if($condition['cat_id'])
		{
			$sql = " SELECT *FROM ". $GLOBALS['ecs']->table('merging_display_article') . 
					" WHERE cat_ids LIKE '$condition[cat_id],%' OR cat_ids LIKE '%,$condition[cat_id],%' OR cat_ids LIKE '%,$condition[cat_id]' OR cat_ids='$condition[cat_id]' ";
			//echo $sql."<br>";
			$merging_display_article_list = $GLOBALS['db']->getAll($sql);
			
			$article_ids = '';
			$article_ids_arr = array();

			foreach ( $merging_display_article_list as $key_a => $merging_display_article ) 
			{
				$article_ids .= $merging_display_article['article_ids'].",";
				$merging_display_article['is_merging'] = 1;
				$merging_display_article['title'] = $merging_display_article['new_title'];
       			$article_list[] = $merging_display_article;
			}
			
			// 所有文章ID字符串转数组 start
			if (strstr($article_ids, ',')) {
				$article_ids = rtrim($article_ids, ',');
			}
			
			if($article_ids)
			{
				$article_ids_arr = explode(',',$article_ids);
				
				$article_ids_arr=array_unique($article_ids_arr);
			}
			// 所有文章ID字符串转数组 end
			
			
			foreach ( $article_list as $key_2 => $value ) 
			{
				if(in_array($value['article_id'],$article_ids_arr))
				{
					unset($article_list[$key_2]);
					
				}
				else
				{
		       		$article_list[$key_2]['formated_add_time'] = date('Y-m-d',$value['add_time']);
					
				}
	       		
			}
			
			
		}
		
		//获取合并后的文章 end
		
		//echo "<pre>";print_r($article_list);

		//exit;
		
		$res = array (
			'article_list' => $article_list,
			'condition' => $condition,
			'pager' => $pager,
			'article_cat' => $article_cat,
			'child_article_cat_list'=>$child_article_cat_list,
			'is_top_article_list'=>$this->getIsTopArticle($cat_name,1,5)
		);
		return $res;
	}
	
	
	public function getChildCat($parent_cat_id=0,$cat_name='')
	{
		if(!$parent_cat_id)
		{	
			$article_cat_sql="SELECT cat_id FROM ". $GLOBALS['ecs']->table('article_cat') . " WHERE cat_name='$cat_name'";
			$parent_cat_id = $GLOBALS['db']->getOne($article_cat_sql);
			
		}
		//获取子分类
		$child_article_cat_sql="SELECT * FROM ". $GLOBALS['ecs']->table('article_cat') . " WHERE parent_id='$parent_cat_id'";
		$child_article_cat_list = $GLOBALS['db']->getAll($child_article_cat_sql);
		
		return 	$child_article_cat_list;	
		
	}
	
	
	public function getArticleCat($cat_id)
	{
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('article_cat') . " WHERE cat_id = '$cat_id'" ;
		$article_cat = $GLOBALS['db']->getRow($sql);
		return $article_cat;
	}
	
	
	public function getIsTopArticle($cat_name='',$article_type,$page_size)
	{
		$sort_order = " ORDER BY sort_order ASC,article_id DESC ";
		$where = "WHERE article_type='$article_type' ";
		if($cat_name)
		{
			$sql="SELECT cat_id FROM ".$GLOBALS['ecs']->table('article_cat')." WHERE parent_id IN (SELECT cat_id FROM ".$GLOBALS['ecs']->table('article_cat')." WHERE cat_name='$cat_name' ) ";
			$child_cat_list = $GLOBALS['db']->getAll($sql);
			$cat_ids_str = CommonUtils::arrToStr($child_cat_list,"cat_id");
			
			if($cat_ids_str)
			{
				$where.=" AND cat_id IN($cat_ids_str)";
			}
			
		}
		
		
		$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('article')." $where $sort_order LIMIT $page_size";
		$is_top_article_list = $GLOBALS['db']->getAll($sql);
		return $is_top_article_list;
	}
	
	
	public function getIndexFAQ($cat_name,$page_size=10)
	{
		//获取常见问题的子分类列表
		$sql = "SELECT cat_id FROM " . $GLOBALS['ecs']->table('article_cat')." WHERE cat_name='$cat_name'";
		
		$sql = "SELECT cat_name,cat_id FROM " . $GLOBALS['ecs']->table('article_cat')." WHERE parent_id=($sql) ORDER BY sort_order ASC,cat_id DESC LIMIT $page_size ";
		$article_cat_list = $GLOBALS['db']->getAll($sql);
		
		if($article_cat_list[0]['cat_id'])
		{
			$res = $this->getIndexFAQByCatId($article_cat_list[0]['cat_id']);
			$res['article_cat_list']=$article_cat_list;
			return $res;
		}
		
	}
	
	public function getIndexFAQByCatId($cat_id)
	{	
		//获取所有文章
		$sql ="SELECT article_id,title,content,article_img,cat_id,description FROM ".$GLOBALS['ecs']->table('article'). " WHERE cat_id='$cat_id' ORDER BY article_id DESC LIMIT 11";
		ss_log(__FUNCTION__.",article_list:".$sql);
    	$article_list = $GLOBALS['db']->getAll($sql);
		
		//获取第一篇文章
		if($article_list[0]['article_id'])
		{
			$first_article_id = $article_list[0]['article_id'];
			$sql ="SELECT article_id,title,content,article_img,cat_id,description FROM ".$GLOBALS['ecs']->table('article'). " WHERE article_id='$first_article_id' ";
			ss_log($sql);
	    	$first_article = $GLOBALS['db']->getRow($sql);
		}
		
		return array('article_list'=>$article_list,'first_article'=>$first_article);
	}
	
	
	//常见问题
	public function getFaq()
	{
		$sql = "SELECT title,article_id FROM " . $GLOBALS['ecs']->table('article') . " WHERE cat_id=(" .
    		" SELECT cat_id FROM ". $GLOBALS['ecs']->table('article_cat')." WHERE cat_name= '常见问题' ) LIMIT 0,7";
    	$faq = $GLOBALS['db']->getAll($sql);
    	return $faq;
	}
	
	
	//通过分类获取文章
	function getArticleListByCat($cat_name='',$cat_id=0,$article_num=10)
	{
		
		$where=" WHERE 1 ";
		if($cat_name)
		{
			$where.=" AND cat_name='$cat_name' ";
		}
		elseif($cat_id)
		{
			$where.=" AND cat_id='$cat_id' ";
		}
		//获取当前分类
		$sql ="SELECT * FROM ".$GLOBALS['ecs']->table('article_cat'). $where;
		$article_cat = $GLOBALS['db']->getRow($sql);
		
		if($article_cat)
		{
			$sql ="SELECT * FROM ".$GLOBALS['ecs']->table('article'). " WHERE cat_id='$article_cat[cat_id]' ORDER BY article_id DESC LIMIT $article_num";
			$article_list = $GLOBALS['db']->getAll($sql);
			
			foreach ( $article_list as $key => $article ) 
			{
				$article_list[$key]['formated_add_time'] = date("Y-m-d",$article['add_time']);
       	
			}
			
			
			return array("article_cat"=>$article_cat,'article_list'=>$article_list);
		}
		
	}
}
?>