<?php


/**
 * 综合工具类
 * 2014-11-18 
 * yes123
 */

class CommonUtils {
	
	//add by yes123    2014-11-18  去掉小数后面多余的0  
	public static function decimal_zero_suppression($s) {
		$s = trim(strval($s));
		if (preg_match('#^-?\d+?\.0+$#', $s)) {
			return preg_replace('#^(-?\d+?)\.0+$#', '$1', $s);
		}
		if (preg_match('#^-?\d+?\.[0-9]+?0+$#', $s)) {
			return preg_replace('#^(-?\d+\.[0-9]+?)0+$#', '$1', $s);
		}
		return $s;
	}

	//add by yes123    2014-11-20 字符串中所有空格
	public static function trimAll($str) //删除空格
	{
		$qian = array (
			" ",
			"　",
			"\t",
			"\n",
			"\r"
		);
		$hou = array (
			"",
			"",
			"",
			"",
			""
		);
		return str_replace($qian, $hou, $str);
	}

	/**
	 * 数组转以逗号分割的字符串
	 * add by yes123 2014-11-24 
	 * $arr 数组
	 * $key 是数组的键
	 */
	public static function arrToStr($arr, $key = '') {
		$strs = "";
		foreach ($arr as $value) {

			if ($key) {
				$strs .= trim($value[$key]) . ",";
			} else {
				$strs .= $value . ",";
			}

		}

		if (strstr($strs, ',')) {
			$strs = rtrim($strs, ',');
		}

		return $strs;
	}

	//add yes123 2014-12-01 实现部分用户名用星号代替功能 仿淘宝评论购买记录隐藏部分用户名  
	public static function cutStr($string, $sublen, $start = 0, $code = 'UTF-8') {
		if ($code == 'UTF-8') {
			$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
			preg_match_all($pa, $string, $t_string);

			if (count($t_string[0]) - $start > $sublen)
				return join('', array_slice($t_string[0], $start, $sublen));
			return join('', array_slice($t_string[0], $start, $sublen));
		} else {
			$start = $start * 2;
			$sublen = $sublen * 2;
			$strlen = strlen($string);
			$tmpstr = '';

			for ($i = 0; $i < $strlen; $i++) {
				if ($i >= $start && $i < ($start + $sublen)) {
					if (ord(substr($string, $i, 1)) > 129) {
						$tmpstr .= substr($string, $i, 2);
					} else {
						$tmpstr .= substr($string, $i, 1);
					}
				}
				if (ord(substr($string, $i, 1)) > 129)
					$i++;
			}
			//if(strlen($tmpstr)< $strlen ) $tmpstr.= "...";
			return $tmpstr;
		}
	}

	//add yes123 2014-12-01  判断是不是手机号码
	public static function isPhoneNumber($str) {
		if (preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/", $str)) {
			//验证通过    
			return true;

		} else {
			//手机号码格式不对    
			return false;
		}
	}

	public static function make_password($length = 8) {
		// 密码字符集，可任意添加你需要的字符
		$chars = array (
			'a',
			'b',
			'c',
			'd',
			'e',
			'f',
			'g',
			'h',
			'i',
			'j',
			'k',
			'l',
			'm',
			'n',
			'o',
			'p',
			'q',
			'r',
			's',
			't',
			'u',
			'v',
			'w',
			'x',
			'y',
			'z',
			'0',
			'1',
			'2',
			'3',
			'4',
			'5',
			'6',
			'7',
			'8',
			'9'
		);
		// 在 $chars 中随机取 $length 个数组元素键名
		$keys = array_rand($chars, $length);
		$password = '';
		for ($i = 0; $i < $length; $i++) {
			// 将 $length 个数组元素连接成字符串
			$password .= $chars[$keys[$i]];
		}
		return $password;
	}

	//add yes123 2014-12-05 返回所有A标签的href的值和名称
	public static function getAllATag($str) {
		$pat = '/<a(.*?)href="(.*?)"(.*?)>(.*?)<\/a>/i';
		preg_match_all($pat, $str, $m);

		$url_list = $m[2];
		$title_list = $m[4];

		$data = array ();

		for ($i = 0; $i < count($url_list); $i++) {
			$temp = array ();
			$temp['url'] = $url_list[$i];
			$temp['title'] = $title_list[$i];
			$data[] = $temp;
		}

		return $data;
	}

	//start add yes 123 2014-10-05 解析XML的方法 
	public static function xml_to_array($xml) {
		$array = (array) (simplexml_load_string($xml,null, LIBXML_NOCDATA));
		foreach ($array as $key => $item) {
			$array[$key] = CommonUtils :: struct_to_array((array) $item);
		}
		return $array;
	}
	private static function struct_to_array($item) {
		if (!is_string($item)) {
			$item = (array) $item;
			foreach ($item as $key => $val) {
				$item[$key] = CommonUtils :: struct_to_array($val);
			}
		}
		return $item;
	}
	//end add yes 123 2014-10-05 解析XML的方法 

	public static function uncdata($xml) {

		// States:

		//

		//     'out'

		//     '<'

		//     '<!'

		//     '<!['

		//     '<![C'

		//     '<![CD'

		//     '<![CDAT'

		//     '<![CDATA'

		//     'in'

		//     ']'

		//     ']]'

		//

		// (Yes, thestates a represented by strings.)

		//

		$state = 'out';

		$a = str_split($xml);

		$new_xml = '';

		foreach ($a AS $k => $v) {

			// Dealwith "state".

			switch ($state) {

				case 'out' :

					if ('<' == $v) {

						$state = $v;

					} else {

						$new_xml .= $v;

					}

					break;

				case '<' :

					if ('!' == $v) {

						$state = $state . $v;

					} else {

						$new_xml .= $state . $v;

						$state = 'out';

					}

					break;

				case '<!' :

					if ('[' == $v) {

						$state = $state . $v;

					} else {

						$new_xml .= $state . $v;

						$state = 'out';

					}

					break;

				case '<![' :

					if ('C' == $v) {

						$state = $state . $v;

					} else {

						$new_xml .= $state . $v;

						$state = 'out';

					}

					break;

				case '<![C' :

					if ('D' == $v) {

						$state = $state . $v;

					} else {

						$new_xml .= $state . $v;

						$state = 'out';

					}

					break;

				case '<![CD' :

					if ('A' == $v) {

						$state = $state . $v;

					} else {

						$new_xml .= $state . $v;

						$state = 'out';

					}

					break;

				case '<![CDA' :

					if ('T' == $v) {

						$state = $state . $v;

					} else {

						$new_xml .= $state . $v;

						$state = 'out';

					}

					break;

				case '<![CDAT' :

					if ('A' == $v) {

						$state = $state . $v;

					} else {

						$new_xml .= $state . $v;

						$state = 'out';

					}

					break;

				case '<![CDATA' :

					if ('[' == $v) {

						$cdata = '';

						$state = 'in';

					} else {

						$new_xml .= $state . $v;

						$state = 'out';

					}

					break;

				case 'in' :

					if (']' == $v) {

						$state = $v;

					} else {

						$cdata .= $v;

					}

					break;

				case ']' :

					if (']' == $v) {

						$state = $state . $v;

					} else {

						$cdata .= $state . $v;

						$state = 'in';

					}

					break;

				case ']]' :

					if ('>' == $v) {

						$new_xml .= str_replace('>', '&gt;', str_replace('>', '&lt;', str_replace('"', '&quot;', str_replace('&', '&amp;', $cdata))));

						$state = 'out';

					} else {

						$cdata .= $state . $v;

						$state = 'in';

					}

					break;

			} // switch

		}

		return $new_xml;

	}

}
?>
