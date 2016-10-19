<?php
class wxUtils {
	private $appId;
	private $appSecret;

	public function __construct($appId, $appSecret) {
		$this->appId = $appId;
		$this->appSecret = $appSecret;
	}

	private function getAccessToken() {
		// access_token 应该全局存储与更新，以下代码以写入到文件中做示例
		
		if(file_exists(ROOT_PATH."mobile/access_token.json"))
		{
			$data = json_decode(file_get_contents("access_token.json"));
		}
		
		if ($data->expire_time < time()) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
			$res = json_decode($this->httpGet($url));
			$access_token = $res->access_token;
			if ($access_token) {
				$data->expire_time = time() + 7000;
				$data->access_token = $access_token;
				$fp = fopen("access_token.json", "w");
				fwrite($fp, json_encode($data));
				fclose($fp);
			}
		} else {
			$access_token = $data->access_token;
		}
		return $access_token;
	}

	private function httpGet($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_URL, $url);

		$res = curl_exec($curl);
		curl_close($curl);

		return $res;
	}

	public function getMediaById($media_id,$file_path='') {
		
		$res = array();
		if(!file_exists($file_path))
		{
			if (!make_dir($file_path))
		    {
		        /* 创建目录失败 */
		        $res['code']=1;
		        $res['msg']='创建目录失败';
		        return $res;
		    }
		}
		
		$access_token = $this->getAccessToken();

		$url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=" . $access_token . "&media_id=$media_id";
		ss_log(__FUNCTION__.",url:".$url);
		$fileInfo = $this->downloadWeixinFile($url);
		
		if($fileInfo['header']['http_code']!='200')
		{
			ss_log("http_code:".$fileInfo['header']['http_code']);
			ss_log("fileInfo:".print_r($fileInfo,true));
		
		}
		
		$filename = com_random_filename().".jpg";

		$this->saveWeixinFile($file_path."/".$filename, $fileInfo['body']);
		
		$this->filename = $filename;
		ss_log(__FUNCTION__.",filename==".$filename);
		return $filename;

	}

	private function downloadWeixinFile($url) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_NOBODY, 0); //只取头部

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); //只取头部
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); //只取头部
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //直接返回结果字符串
		$package = curl_exec($ch);
		$httpinfo = curl_getinfo($ch);
		curl_close($ch);

		return array_merge(array (
			'header' => $httpinfo
		), array (
			'body' => $package
		));

	}

	private function saveWeixinFile($filename, $filecontent) {
		$load_file = fopen($filename, 'w');

		if (false !== $load_file) {
			if (false !== fwrite($load_file, $filecontent)) {

				fclose($load_file);
			}

		}

	}
}
?>