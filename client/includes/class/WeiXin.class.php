<?php

class WeiXin {
	public $appid="wxea0c670f3b2e1a22";
	public $appsecret="2d240f57f3a28527929f6d0f4104db97";
    function WeiXin() {
    	
    }
    
    //上传图片
	public function upload_img($img_path=""){

	    $request = $this->get_access_token($this->appid,$this->appsecret);
	    $access_token = $request['access_token'];
	    ss_log("upload_img function img_path :".$img_path);
	    
		ss_log("upload_img function access_token :".$access_token);
		
		//开始上传图片
		$type="image";
		$filedate = array("media" => "@".$img_path); //必须加“@”
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";
		$result = $this->https_request($url,$filedate);
		print_r($result);
		
	}
	
	
	//上传图文消息素材
	public function update_text_img($data){
		$request = $this->get_access_token($this->appid,$this->appsecret);
	    $access_token = $request['access_token'];
		$url = "https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=$access_token";
		ss_log("update_text_img url:".$url);
		$data = json_encode($data); //必须为json字符串，再传
		ss_log("update_text_img data:".$data);
		$result = $this->https_request($url,$data);
		//$request = json_decode($request, true);
		echo "<pre>";
		
		print_r($result);
	}
	
	
	public function send_policy_by_openid(){
		
		 if (!array_key_exists('code', $_GET)) {
		 	$redirect_uri="";
			$redirect_uri = "http://" . $_SERVER['HTTP_HOST'] . "/client/route.php?act=send_policy_by_openid";
			$this->get_wx_code($this->appid,"snsapi_userinfo",$redirect_uri);
        }else{
			$code = $_GET['code'];
        	$respond = $this->getopenidAndaccess_tokenBycode($this->appid, $this->appsecret, $code);
        	$openid = isset($respond['openid'])?$respond['openid']:"";
        	if($openid){
        		//通过openid获取用户id，然后拿保单等信息
        		
        		
        	}
        }
		

		
		
	}
	
	public function get_wx_code($appid, $type,$redirect_uri) {
	    //获取当前访问页面的url
	    ss_log("WeiXin redirect_uri:".$redirect_uri);
	    //对url 进行 urlencode 编码
	    $redirect_uri = urlencode($redirect_uri);
	    //拼接 请求地址  snsapi_userinfo  授权方式    
	
	    $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=$type&state=123432#wechat_redirect";
	    
	    //输出 html 头部  标记使用 302 状态   （貌似没有什么用处 不写也可以）;
	
	    header('HTTP/1.1 302 Moved Permanently');
	   
	    //使用header 跳转
	    header("Location: $url");
	        
	}
	
	//获取code
	public function getopenidAndaccess_tokenBycode($appid, $appsecret, $code) {
	    //拼接请求url
	    $requesturl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
	    $request = $this->curl_get($requesturl);
	    return $request;
	}
	//获取openid
	public function curl_get($curl) {
		$weixin =  file_get_contents($curl);//通过code换取网页授权access_token
		$jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
		$array = get_object_vars($jsondecode);//转换成数组
	    return $array;
	}
	
	function https_request($url, $data = null)
	{
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    if (!empty($data)){
	        curl_setopt($curl, CURLOPT_POST, 1);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    }
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $output = curl_exec($curl);
	    curl_close($curl);
	    return $output;
	}
	
	public function get_access_token($appid,$appsecret){
	    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
	    $request = $this->https_request($url);
	    $request = json_decode($request, true);
	    return $request;
	}

}
?>