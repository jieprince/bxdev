<?php
/***
 * 发送短信 
 */
class sent_sms{
  
   /***
	 * 发送手机验证码
	 * @access  public
	 * @param   url 接口地址
	 * @param   fields 参数数组
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
	    //短信内容 $message
       // $message = '【保险】您正在获取注册动态验证码,请输入验证码'.$num.'完成注册。';
	   //	您本次在易保网提现的验证码为
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