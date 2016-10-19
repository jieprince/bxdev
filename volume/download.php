<?php
include_once '../slex.php';
xCore::start();

$vol = new xVolume(1);
xGbl::$mysql->exec('DELETE FROM `'.TB_PREFIX.'volume_token` WHERE `date` < "'.(time()-86400).'";');
if($tokenRes = xGbl::$mysql->exec('SELECT * FROM `'.TB_PREFIX.'volume_token` WHERE `token` = "'.xGbl::$mysql->escape($_GET['token']).'" AND `vid` = "'.xGbl::$mysql->escape($_GET['id']).'";')) {
	if($token = $tokenRes->fetch_assoc()) {
		// --- 常量 ---
		define('IS_HTTPRANGE',		isset($_SERVER['HTTP_RANGE'])&&($_SERVER['HTTP_RANGE']!='') ? true : false);
		// --- 实体文件表 ---
		$vfileRes = xGbl::$mysql->exec('SELECT * FROM `'.TB_PREFIX.'volume_vfiles` WHERE `id` = '.$token['vid'].';');
		$vfile = $vfileRes->fetch_assoc();
		$vfileRes->free();
		// --- 开始输出 ---
		@set_time_limit(0);
		header('Content-type: application/octet-stream');
		header('Accept-Ranges: bytes');
		header('Pragma: no-cache');
		header('Cache-Control: max-age=0');
		header('Expires: -1');
		if($vol->stype == '0') $fh = fopen(__ROOT__.'volume/'.$vfile['path'],'r');
		// --- 设置文件名 ---
		$filenameEncoded = str_replace('+', '%20', urlencode($token['filename']));
		$_SERVER['HTTP_USER_AGENT'] = strtolower($_SERVER['HTTP_USER_AGENT']);
		if(preg_match('/msie/', $_SERVER['HTTP_USER_AGENT']) || preg_match("/trident\/7.0/", $_SERVER['HTTP_USER_AGENT']))
			header('Content-Disposition: attachment; filename="' . $filenameEncoded . '"');
		else if (preg_match('/firefox/', $_SERVER['HTTP_USER_AGENT'])) {
			header('Content-Disposition: attachment; filename*="utf8\'\'' . $token['filename'] . '"');
		} else
			header('Content-Disposition: attachment; filename="'.$token['filename'].'"');
		// --- 检测断点 ---
		if(IS_HTTPRANGE) {
			// --- 是断点 ---
			list($httpRangeMin, $httpRangeMax) = explode('-', str_replace('bytes=','',trim(strtolower($_SERVER['HTTP_RANGE']))));
			if($httpRangeMin == 0) {
				// --- 从 0 开始的断点就不是断点 ---
			} else {
				if($vol->stype == '0') fseek($fh, $httpRangeMin);
				header('Content-Length: '.($vfile['size']-1-$httpRangeMin));
				header('Content-Range: bytes '.$httpRangeMin.'-'.($vfile['size']-1).'/'.$vfile['size']);
				header('HTTP/1.1 206 Partial Content');
			}
		} else {
			// --- 没断点 ---
			header('Content-Range: bytes 0-'.($vfile['size']-1).'/'.$vfile['size']);
			header('Content-Length: '.$vfile['size']);
		}
		// --- clear token ---
		xGbl::$mysql->exec('DELETE FROM `'.TB_PREFIX.'volume_token` WHERE `token` = "'.$token['token'].'";');
		// --- Start Download ---
		if($vol->stype == '0') {
			while(!feof($fh)) {
				echo fread($fh,32768);
				ob_flush();
				flush();
			}
			fclose($file);
		} else if($vol->stype == '1') {
			header('X-Accel-Redirect: '.__ROOT__.'volume/'.$vfile['path']);
		} else {
			header('X-Sendfile: '.__ROOT__.'volume/'.$vfile['path']);
		}
	} else {
		header('Location: /');
	}
	$tokenRes->free();
} else {
	header('Location: /');
}

