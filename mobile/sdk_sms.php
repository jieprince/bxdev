<?php
   /**
    * sdk ���Ͷ���
	* @Parma sn   SDK���к�
	* @param pwd  ���� $sn + $pwd
	* @param mobile �ֻ�����
	* @param content ����(����:gb2312)
	*/
function gxmt_post($sn,$pwd,$mobile,$content){
    
    $flag = 0; 
        //Ҫpost������ 
	$argv = array( 
		 'sn'=>$sn, //�ṩ���˺�
		 'pwd'=>strtoupper(md5($sn.$pwd)), //�˴�������Ҫ���� ���ܷ�ʽΪ md5(sn+password) 32λ��д
		 'mobile'=>$mobile,//�ֻ��� �����Ӣ�ĵĶ��Ÿ��� һ��С��1000���ֻ���
		 'content'=>urlencode($content.'�����졿'),//������ݷֱ�urlencode����Ȼ�󶺺Ÿ��� //ע��:[����] �������ǿ���ͨ��ǩ������������޸�
		 'ext'=>'',//�Ӻ�(���Կ� ,������1�� �����Ƕ��,�������Ҫ�����ݺ��ֻ���һһ��Ӧ)
		 'stime'=>'',//��ʱʱ�� ��ʽΪ2011-6-29 11:09:21
		 'rrid'=>'',//Ĭ�Ͽ� ����շ���ϵͳ���ɵı�ʶ�� �����ֵ��ֵ֤Ψһ �ɹ��򷵻ش����ֵ
		 'sign'=>''
		 ); 
	$params = '';
	//����Ҫpost���ַ��� 
	foreach ($argv as $key=>$value) { 
	    if ($flag!=0) { 
		    $params .= "&"; 
		    $flag = 1; 
	    } 
	    $params.= $key."="; $params.=urlencode($value); 
	    $flag = 1; 
	} 
	 $length = strlen($params); 
	  
			 //����socket���� 
	 $fp = fsockopen("sdk2.zucp.net",8060,$errno,$errstr,10) or exit($errstr."--->".$errno); 
	 
	 //����post�����ͷ 
	 $header = "POST /webservice.asmx/gxmt HTTP/1.1\r\n"; 
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
	   if(count($result)>1){
	       return $line;
	   }else{
	       $line = 0;
	       return $line;
	   }	   
   }

function post_mt($sn,$pwd,$mobile,$content)
{
//��demo�Ĺ�����Ⱥ�����źͷ��������š�����һ���ֻ��ž��Ƿ�����������ֻ��ż���Ⱥ����

//�������кź����뻹���ֻ��ţ����ϣ�ֱ�����оͿ�����

//�������ϵͳ��utf-8,��ת��GB2312 �����ύ��
//��ο� 'content'=>iconv( "UTF-8", "gb2312//IGNORE" ,'���ò��Զ���[XXX��˾]'),//��������

$flag = 0; 
        //Ҫpost������ 
$argv = array( 
         'sn'=>$sn, ////�滻�����Լ������к�
		 'pwd'=>strtoupper(md5($sn.$pwd)), //�˴�������Ҫ���� ���ܷ�ʽΪ md5(sn+password) 32λ��д
		 'mobile'=>$mobile,//�ֻ��� �����Ӣ�ĵĶ��Ÿ��� һ��С��1000���ֻ���
		 'content'=>$content.'�����졿',//������ݷֱ�urlencode����Ȼ�󶺺Ÿ��� //ע��:[����] �������ǿ���ͨ��ǩ������������޸�
		 'ext'=>'',		
		 'stime'=>'',//��ʱʱ�� ��ʽΪ2011-6-29 11:09:21
		 'rrid'=>''
		 ); 
	ss_log("argv[content]:".$argv['content']);
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
        $fp = fsockopen("sdk2.entinfo.cn",8060,$errno,$errstr,10) or exit($errstr."--->".$errno); 
         //����post�����ͷ 
         $header = "POST /webservice.asmx/mt HTTP/1.1\r\n"; 
         $header .= "Host:sdk2.entinfo.cn\r\n"; 
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
		   ss_log('post_mt result:'.$result[0]);
		  // echo $line."-------------";
		   if(count($result)>1)
			return $line;
			else
			$line=0;
			return $line;

}
?>