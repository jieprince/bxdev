<?php
/***
 * ���Ͷ��� 
 */
class sent_sms{
  
   /***
	 * �����ֻ���֤��
	 * @access  public
	 * @param   url �ӿڵ�ַ
	 * @param   fields ��������
	 */
    function execPostRequest($url,$fields){
		if(empty($url)){ return false;}		
		//$fields_string =http_build_query($post_array);
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string,'&');
		
		$ch = curl_init();

		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		$result = curl_exec($ch);

		curl_close($ch);

		return $result;

	}
	function post_sms($uid,$passwd,$telphone,$message){
	    //�������� $message
       // $message = '�����ա������ڻ�ȡע�ᶯ̬��֤��,��������֤��'.$num.'���ע�ᡣ';
	   //	���������ױ������ֵ���֤��Ϊ
		$fields = array(
				'CorpID'=>urlencode($uid),
				'Pwd'=>urlencode($passwd),
				'Mobile'=>urlencode($telphone),
				'Content'=>urlencode($message),
				'Cell'=>'',
				'SendTime'=>''
			   );


		$url = "http://qxt.tongxun3g.cn/ws/Send.aspx";
		$result = self::execPostRequest($url,$fields);
		
		return $result;
	}
}

?>