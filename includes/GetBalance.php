<?PHP 
//��demo�Ĺ����ǲ����
//�������е�Demo������GB2312�����²��Եģ��������ϵͳ��UTF-8����ת��GB2312�����ύ��
//�ο����룺iconv( "UTF-8", "gb2312//IGNORE" ,"��ã����Զ���")
function get_balance(){
		$flag = 0; 
				//Ҫpost������ 
		$argv = array( 
				 'sn'=>'SDK-GHD-010-00014', //�滻�����Լ������к�
				 'pwd'=>'014355',//�滻�����Լ�������
				
				 ); 
		//����Ҫpost���ַ��� 
		foreach ($argv as $key=>$value) { 
				  if ($flag!=0) { 
								 $params .= "&"; 
								 $flag = 1; 
				  } 
				 $params.= $key."="; $params.= urlencode($value); 
				 $flag = 1; 
				  } 
				 $length = strlen($params); 
						 //����socket���� 
				$fp = fsockopen("sdk2.zucp.net",8060,$errno,$errstr,10) or exit($errstr."--->".$errno); 
				 //����post�����ͷ 
				 $header = "POST /webservice.asmx/GetBalance HTTP/1.1\r\n"; 
				 $header .= "Host:sdk2.zucp.net\r\n"; 
				 $header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
				 $header .= "Content-Length: ".$length."\r\n"; 
				 $header .= "Connection: Close\r\n\r\n"; 
				 //���post���ַ��� 
				 $header .= $params."\r\n"; 
				 //����post������ 
				 fputs($fp,$header); 
				 $inheader = 1; 
				  while (!feof($fp)) { 
								 $line = fgets($fp,1024); //ȥ���������ͷֻ��ʾҳ��ķ������� 
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
