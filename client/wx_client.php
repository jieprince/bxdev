<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "ebao_client");
$wechatObj = new wechatCallbackapiTest();

if(!isset($_GET['echostr'])){
$wechatObj->responseMsg();
}else{
$wechatObj->valid();
}


class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){

              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
				$type = trim($postObj->Event);
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
				
				if($type == "subscribe")
				{	
					
					$textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[news]]></MsgType>
						<ArticleCount>1</ArticleCount>
						<Articles>
						<item>
						<Title><![CDATA[E保险]]></Title>
						<Description><![CDATA[您在E保险已投保成功，请点击此处查看保单]]></Description>
						<PicUrl><![CDATA[http://cczrb.img43.wal8.com/img43/509471_20150208141205/14233759829.jpg]]></PicUrl>
						<Url><![CDATA[http://www.ebaoins.cn/client/user.php]]></Url>
						</item>
						</Articles>
						</xml>				
						";
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
					echo $resultStr;
					
				}
							
				           
				if(!empty( $keyword ))
                {
					$msgType = "text";
					if(mb_substr($keyword,0,2,'utf-8') == "天气")
					{
						$cityname=mb_substr($keyword,2,5,'utf-8');
						$url="http://v.juhe.cn/weather/index?format=2&cityname=".$cityname."&key=3c0361d18fa13a6268d79b4436b473ba";
						$str=file_get_contents($url);
						$de_json = json_decode($str,TRUE);
						
						$contentStr="城市：".$de_json['result']['today']['city']."\n日期：".$de_json['result']['today']['date_y'].$de_json['result']['today']['week']."当前温度:".$de_json['result']['sk']['temp']."度\n当前天气：". $de_json['result']['sk']['wind_strength'].$de_json['result']['sk']['wind_direction']."\n今日温度：".$de_json['result']['today']['temperature']."\n今日天气：".$de_json['result']['today']['weather']."";
						
					}else{
                		$contentStr = "欢迎关注E保险!";
					}
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>