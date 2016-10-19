<?PHP 
//该demo的功能是查余额
//我们所有的Demo都是在GB2312环境下测试的，如果您的系统是UTF-8。请转成GB2312后，再提交。
//参考代码：iconv( "UTF-8", "gb2312//IGNORE" ,"你好，测试短信")
function get_balance(){
		$flag = 0; 
				//要post的数据 
		$argv = array( 
				 'sn'=>'SDK-GHD-010-00014', //替换成您自己的序列号
				 'pwd'=>'014355',//替换成您自己的密码
				
				 ); 
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
				$fp = fsockopen("sdk2.zucp.net",8060,$errno,$errstr,10) or exit($errstr."--->".$errno); 
				 //构造post请求的头 
				 $header = "POST /webservice.asmx/GetBalance HTTP/1.1\r\n"; 
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
					if(count($result)>1)
					return array('code'=>1,'num'=>$line);
					else
					return  array('code'=>0,'num'=>$line);

}
?>
