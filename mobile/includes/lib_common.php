<?php
/**
 * mobile 公共函数库
 */

//add yes123 2015-01-12 微信分页
function mobilePagebar($record_count, $url,$condition='') {
	
	if($condition){
		foreach ($condition AS $key => $value)
		{
			if($key!='act'){
				$url.="&".$key."=".$value;
			}
		}
	}
		
	$page_num = '10';
	$page = !empty ($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
	$pages = ceil($record_count / $page_num);
	if ($page <= 0) {
		$page = 1;
	}
	if ($pages == 0) {
		$pages = 1;
	}
	if ($page > $pages) {
		$page = $pages;
	}
	$pagebar = get_wap_pager($record_count, $page_num, $page, $url, 'page');
	return $pagebar;
}


function handler_get_wx_code($appid, $type,$redirect_uri) {
    //获取当前访问页面的url
	ss_log("get_wx_code redirect_uri :".$redirect_uri);
    //对url 进行 urlencode 编码
    $redirect_uri = urlencode($redirect_uri);
    //拼接 请求地址  snsapi_userinfo  授权方式    

    $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=$type&state=123432#wechat_redirect";
    ss_log("handler_get_wx_code,redirect_uri: ".$redirect_uri);
    //输出 html 头部  标记使用 302 状态   （貌似没有什么用处 不写也可以）;

    header('HTTP/1.1 302 Moved Permanently');
   
    //使用header 跳转，给微信浏览器重定向,跑到微信服务器上去
    header("Location: $url");
        
}

//获取code
function get_openid_and_access_token_by_code($appid, $appsecret, $code) {
    //拼接请求url
    $requesturl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
    $request = curl_get($requesturl);
    return $request;
}
//获取openid
function curl_get($curl) {
/*
    if($curl)
	{//add by wangcya, 20150210
		$weixin =  file_get_contents($curl);//通过code换取网页授权access_token
		if($weixin)
		{//add by wangcya, 20150210
			$jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
			if($jsondecode)
			{//add by wangcya, 20150210
				$array = get_object_vars($jsondecode);//转换成数组
			}
		}
		
		return $array;
	}
*/	
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $curl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //直接返回结果字符串
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查  
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在 住：如果1报错就用2 
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器  
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转  
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer  
    // 3. 执行一个cURL会话并且获取相关回复
    $response = curl_exec($ch);
    $result = json_decode($response, true);
    curl_close($ch);
    ss_log("into curl_get function:".$curl);
    return $result;
}

function get_client_ip()
{
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
	  $cip = $_SERVER["HTTP_CLIENT_IP"];
	 }
	 elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
	  $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	 }
	 elseif(!empty($_SERVER["REMOTE_ADDR"])){
	  $cip = $_SERVER["REMOTE_ADDR"];
	 }
	 else{
	  $cip = "无法获取！";
	 }
	return $cip;
}

?>
