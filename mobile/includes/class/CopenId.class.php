<?php

/**
 * 获取openid
 * @author rongqingsong 2014-08-20
 * @updater cuikai 14-8-21 下午12:41 
 */
include_once(ROOT_PATH . 'baoxian/source/function_debug.php');
class CopenId {
    // 公众号开发者id
    private $appid = "wxea0c670f3b2e1a22"; //E保险
    //公众号开发者密钥
    private $appsecret = "2d240f57f3a28527929f6d0f4104db97";//E保险
    
    public function __construct($appid=null, $appsecret=null) {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {        
            if (empty($_SESSION['user_id']) || !isset($_SESSION['user_id']) || $_SESSION['user_name'] == '')
            {
                //Log::write("Not get user_name and user_id", 'WEIXIN');
                $this->appid     = empty($appid)     ? $this->$appid     : $appid;
                $this->appsecret = empty($appsecret) ? $this->$appsecret : $appsecret;
                
                // 开始登陆
                $this->wx_login_start($this->appid, $this->appsecret);
                
            }else{
                //Log::write("Got user_id[{$_SESSION['user_id']}] user_name[".$_SESSION['user_name']."]", 'WEIXIN');
            }
        }
    }
    /**
     * 微信登陆开始
     * @param type $appid
     * @param type $appsecret
     */
    private function wx_login_start($appid, $appsecret) {
        //判断 request中是否存在 code        
        if (!array_key_exists('code', $_GET)) {
             ss_log("get c_openid not code  go get code..");
             $this->get_wx_code($appid, "snsapi_userinfo");
            
        }else{
            //request中获取code
            $code = $_GET['code'];

            //根据code获取用户access_token和用户 openid
            $respond = $this->getopenidAndaccess_tokenBycode($appid, $appsecret, $code);

            if (array_key_exists('openid', $respond) && array_key_exists('access_token', $respond)) {
                //从结果中取出openid
                $openid = $respond['openid'];
	            $_SESSION['c_openid'] = $openid;
                ss_log($this->appid.'-> get c_openid:'.$openid);
//				$db_user = $this->getDbUserInfo($openid);
//			
//				if (!empty($db_user)) {
//					ss_log('user not null... start set session..');					
//					ss_log('2 exist:'.$db_user['user_name']);
//					$_SESSION['user_id'] = $db_user['user_id'];
//					$_SESSION['user_name'] = $db_user['user_name'];    
//					$_SESSION['real_name'] = $db_user['real_name']; 
//					update_user_info();
//					//重新计算购物车中的商品价格
//					recalculate_price();
//                }
//                
                

                //start add by wangcya, 2015-01-31 , for bug[238],增加会议记录
               $activity_id =   isset ($_REQUEST['activity_id']) ? $_REQUEST['activity_id'] : 0;
               // $activity_id = intval($_POST['activity_id']);//add by wangcya, 2015-01-31 , for bug[238]
                if($activity_id)
                {
                	$_SESSION['activity_id'] = $activity_id;
                	$uid = $_SESSION['user_id'];
                		
                	include_once(ROOT_PATH . 'includes/class/User.class.php');
                	$user_obj = new User;
                	$user_obj->add_user_activity($activity_id,$uid);
                		
                }
                //end add by wangcya, 2015-01-31 , for bug[238],增加会议记录
				
            }

        }
    }

    /**
     * 获取code临时令牌
     * @param type $appid
     * @param type $type
     */
    private function get_wx_code($appid, $type) {
         
        //获取当前访问页面的url
        $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		ss_log("get_wx_code redirect_uri :".$url);
        //对url 进行 urlencode 编码
        $url = urlencode($url);
        //拼接 请求地址  snsapi_userinfo  授权方式    

        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$url&response_type=code&scope=$type&state=123432#wechat_redirect";
        
        //输出 html 头部  标记使用 302 状态   （貌似没有什么用处 不写也可以）;
    
        header('HTTP/1.1 302 Moved Permanently');
       
        //使用header 跳转
        header("Location: $url");
        
    }

    /**
     * curl请求方法
     * @param type $curl
     * @return type
     */
    private function curl_get($curl) {
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
        return $result;
    }

    /**
     * 根据code获取用户access_token和用户 openid
     * @param type $appid
     * @param type $appsecret
     * @param type $code
     * @return type
     */
    private function getopenidAndaccess_tokenBycode($appid, $appsecret, $code) {
        //拼接请求url
        $requesturl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
        $request = $this->curl_get($requesturl);
        return $request;
    }
	
	    /**
     * 根据数据库获取用户的基本信息
     * @param type $openid
     * @return type
    */
    private function getDbUserInfo($openid) {
        $sql = "SELECT * FROM" . $GLOBALS['ecs']->table('users_c') .
                " WHERE openid = '" . $openid . "'";
        return $GLOBALS['db']->getRow($sql);
    }

}