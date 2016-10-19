<?php
   /**
    * sdk 发送短信
	* @Parma sn   SDK序列号
	* @param pwd  密码 $sn + $pwd
	* @param mobile 手机号码
	* @param content 内容(编码:gb2312)
	*/
function gxmt_post($sn,$pwd,$mobile,$content){
    
    $flag = 0; 
        //要post的数据 
	$argv = array( 
		 'sn'=>$sn, //提供的账号
		 'pwd'=>strtoupper(md5($sn.$pwd)), //此处密码需要加密 加密方式为 md5(sn+password) 32位大写
		 'mobile'=>$mobile,//手机号 多个用英文的逗号隔开 一次小于1000个手机号
		 'content'=>urlencode($content.'【中天】'),//多个内容分别urlencode编码然后逗号隔开 //注意:[中天] 两字是是快速通道签名。不能随便修改
		 'ext'=>'',//子号(可以空 ,可以是1个 可以是多个,多个的需要和内容和手机号一一对应)
		 'stime'=>'',//定时时间 格式为2011-6-29 11:09:21
		 'rrid'=>'',//默认空 如果空返回系统生成的标识串 如果传值保证值唯一 成功则返回传入的值
		 'sign'=>''
		 ); 
	$params = '';
	//构造要post的字符串 
	foreach ($argv as $key=>$value) { 
	    if ($flag!=0) { 
		    $params .= "&"; 
		    $flag = 1; 
	    } 
	    $params.= $key."="; $params.=urlencode($value); 
	    $flag = 1; 
	} 
	 $length = strlen($params); 
	  
			 //创建socket连接 
	 $fp = fsockopen("sdk2.zucp.net",8060,$errno,$errstr,10) or exit($errstr."--->".$errno); 
	 
	 //构造post请求的头 
	 $header = "POST /webservice.asmx/gxmt HTTP/1.1\r\n"; 
	 $header .= "Host:sdk2.zucp.net\r\n"; 
	 $header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
	 $header .= "Content-Length: ".$length."\r\n"; 
	 $header .= "Connection: Close\r\n\r\n"; 
	 //添加post的字符串 
	 $header .= $params."\r\n"; 
	 //发送post的数据 
	 fputs($fp,$header); 
	 $inheader = 1; 
	  while (!feof($fp)) { 
		 $line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据 
		 if ($inheader && ($line == "\n" || $line == "\r\n")) { 
				 $inheader = 0; 
		  } 
		  if ($inheader == 0) { 
				// echo $line; 
		  } 
	  } 
	  //<string xmlns="http://tempuri.org/">-5</string>
	   $line=str_replace("<string xmlns=\"http://tempuri.org/\">","",$line);
	   $line=str_replace("</string>","",$line);
	   $result=explode("-",$line);
	   if(count($result)>1){
	       return $line;
	   }else{
	       $line = 0;
	       return $line;
	   }	   
   }

function post_mt($sn,$pwd,$mobile,$content)
{
//改demo的功能是群发短信和发单条短信。（传一个手机号就是发单条，多个手机号既是群发）

//您把序列号和密码还有手机号，填上，直接运行就可以了

//如果您的系统是utf-8,请转成GB2312 后，再提交、
//请参考 'content'=>iconv( "UTF-8", "gb2312//IGNORE" ,'您好测试短信[XXX公司]'),//短信内容

$flag = 0; 
        //要post的数据 
$argv = array( 
         'sn'=>$sn, ////替换成您自己的序列号
		 'pwd'=>strtoupper(md5($sn.$pwd)), //此处密码需要加密 加密方式为 md5(sn+password) 32位大写
		 'mobile'=>$mobile,//手机号 多个用英文的逗号隔开 一次小于1000个手机号
		 'content'=>$content.'【中天】',//多个内容分别urlencode编码然后逗号隔开 //注意:[中天] 两字是是快速通道签名。不能随便修改
		 'ext'=>'',		
		 'stime'=>'',//定时时间 格式为2011-6-29 11:09:21
		 'rrid'=>''
		 ); 
	ss_log("argv[content]:".$argv['content']);
//构造要post的字符串 
foreach ($argv as $key=>$value) { 
          if ($flag!=0) { 
                         $params .= "&"; 
                         $flag = 1; 
          } 
         $params.= $key."="; $params.= urlencode($value); 
         $flag = 1; 
          } 
         $length = strlen($params); 
                 //创建socket连接 
        $fp = fsockopen("sdk2.entinfo.cn",8060,$errno,$errstr,10) or exit($errstr."--->".$errno); 
         //构造post请求的头 
         $header = "POST /webservice.asmx/mt HTTP/1.1\r\n"; 
         $header .= "Host:sdk2.entinfo.cn\r\n"; 
         $header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
         $header .= "Content-Length: ".$length."\r\n"; 
         $header .= "Connection: Close\r\n\r\n"; 
         //添加post的字符串 
         $header .= $params."\r\n"; 
         //发送post的数据 
         fputs($fp,$header); 
         $inheader = 1; 
          while (!feof($fp)) { 
                         $line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据 
                         if ($inheader && ($line == "\n" || $line == "\r\n")) { 
                                 $inheader = 0; 
                          } 
                          if ($inheader == 0) { 
                                // echo $line; 
                          } 
          } 
		  //<string xmlns="http://tempuri.org/">-5</string>
	       $line=str_replace("<string xmlns=\"http://tempuri.org/\">","",$line);
	       $line=str_replace("</string>","",$line);
		   $result=explode("-",$line);
		   ss_log('post_mt result:'.$result[0]);
		  // echo $line."-------------";
		   if(count($result)>1)
			return $line;
			else
			$line=0;
			return $line;

}
?>