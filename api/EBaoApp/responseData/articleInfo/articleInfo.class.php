<?php
/**
 * 平台咨询
 * $Author: dingchaoyang $
 * 2014-11-11 $
 */
$ROOT_PATH_= str_replace ( 'api/EBaoApp/responseData/articleInfo/articleInfo.class.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;

include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');

class ArticleInfoSuccess extends BaseResponse implements IResponse{
	private $infos;
	public function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_ARTICLEINFOSUCCESS_CONTENT;
		$this->status = new ResStatus('0', $message);
		$this->command = APP_COMMAND_ARTICLEINFO;
	}
	
	public function setArticle($info){
		foreach ($info as $outKey=>$outValue){
			foreach ($outValue  as $key=>$value){
				if ($key == 'article_id'){
					$this->infos[$outKey]['article_id']=$value;
				}
				if ($key == 'title'){
					$this->infos[$outKey]['title'] = $value;
				}
				if ($key == 'link'){
					$this->infos[$outKey]['link'] = /*SERVERURL.*/'/article.php?act=index_article_by_id&id='.$this->infos[$outKey]['article_id'];
				}
			}
			
		}
	}
	
	public function responseResult(){
		
		$ar = array('articleInfo'=>$this->infos);
		$result = array_merge(parent::responseResult(),$ar);
		return $result;
	}
}

class ArticleInfoFail extends BaseResponse implements IResponse{
	private $infos;
	public function __construct(){
		parent::__construct();
		$message = new ResMessage();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_ARTICLEINFOFAIL_CONTENT;
		$this->status = new ResStatus('1', $message);
		$this->command = APP_COMMAND_ARTICLEINFO;
	}

	public function responseResult(){

		$result = parent::responseResult();
		return $result;
	}
}
?>