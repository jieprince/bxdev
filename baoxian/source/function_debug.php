<?php

define('S_ROOT_LOG', dirname(__FILE__).DIRECTORY_SEPARATOR);

$ss_log_filename = S_ROOT_LOG.'../log/debug.log';//CONNECT_ROOT
$ss_log_filename_task = S_ROOT_LOG.'../log/debug_task.log';//CONNECT_ROOT

$ss_log_path = S_ROOT_LOG.'../log/';
//echo "dfsfsdfsd: ".$ss_log_filename;
//$ss_log_filename = 'baoxian/log/debug.log';//CONNECT_ROOT
//$ss_log_filename = "./log/debug.log";

function strexists_x($haystack, $needle) 
{
	return !(strpos($haystack, $needle) === FALSE);
}


//时式
function sgmdate_x($dateformat, $timestamp='', $format=0) {
	global $_SCONFIG, $_SGLOBAL;
	if(empty($timestamp)) {
		$timestamp = $_SGLOBAL['timestamp'];
	}
	$timeoffset = strlen($_SGLOBAL['member']['timeoffset'])>0?intval($_SGLOBAL['member']['timeoffset']):intval($_SCONFIG['timeoffset']);
	$result = '';
	if($format) {
		$time = $_SGLOBAL['timestamp'] - $timestamp;
		if($time > 24*3600) {
			$result = gmdate($dateformat, $timestamp + $timeoffset * 3600);
		} elseif ($time > 3600) {
			$result = intval($time/3600).lang('hour').lang('before');
		} elseif ($time > 60) {
			$result = intval($time/60).lang('minute').lang('before');
		} elseif ($time > 0) {
			$result = $time.lang('second').lang('before');
		} else {
			$result = lang('now');
		}
	} else {
		$result = gmdate($dateformat, $timestamp + $timeoffset * 3600);
	}
	return $result;
}


function ss_log_system($msg)
{
	global $ss_log_path;

	$ss_log_filename_1 = S_ROOT_LOG.'../log/system.log';//CONNECT_ROOT
	//echo $ss_log_filename."</br>";

	//echo $msg."</br>";
	///////////////////////////////////////////////////
	if(1)
	{

		$logFile = $ss_log_filename_1;
		$msgPre = '';

		/*
		 if (!file_exists($logFile))
		 {
		$msgPre = "\r\n<?php  die('access deny!'); ?> \r\n\r\n";
		}
		*/


		/////////////start add by wangcya , 20140827
		if(PHP_VERSION > '5.1') {

			//$timeoffset = intval(settings['timeoffset'] / 3600);
			//@date_default_timezone_set('Etc/GMT'.($timeoffset > 0 ? '-' : '+').(abs($timeoffset)));
			date_default_timezone_set('PRC');
		}
		/////////////end add by wangcya , 20140827////////////////////////////////////

		$msg = $msgPre. sprintf("%s\t%s\r\n",date("Y-m-d H:i:s"),$msg);
		//$msg = $msgPre. sprintf("%s\r\n",$msg);

		//////////////////////////////////////////////////////////////////////////
		//$mtime = explode(' ', microtime());
		//$timestamp = $mtime[1];

		$file ="system";
		//$yearmonth = sgmdate_x('Ym', timestamp );//$_SGLOBAL['timestamp']);
		$yearmonth = date("Y-m");

		$logdir = $ss_log_path;

		if(!is_dir($logdir))
			mkdir($logdir, 0777);

		$create_flag = false;//add by wangcya, 20150126

		//echo $yearmonth;
		$logfile = $ss_log_filename_1;//$logdir.$yearmonth.'_'.$file.'.log';

		/*
		if(@filesize($logfile) > 2048000) //
		{
			$dir = opendir($logdir);
			$length = strlen($file);
			$maxid = $id = 0;
			while($entry = readdir($dir))
			{
				if(strexists_x($entry, $yearmonth.'_'.$file))
				{
					$id = intval(substr($entry, $length + 8, -4));
					$id > $maxid && $maxid = $id;
				}
			}
				
			closedir($dir);
			$logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($maxid + 1).'.log';
			@rename($logfile, $logfilebak);
				
			$create_flag = true;//add by wangcya, 20150126
		}
		*/
		
		
		//////////////////////////////////////////////////////////////////////////
		$mode = 'ab';
		$fp = @fopen($logfile, $mode);
		if( $fp )
		{
			@flock($fp, LOCK_EX);
			$len = @fwrite($fp, $msg);
			@flock($fp, LOCK_UN);
			@fclose($fp);
						
			return $len;
		}

	}

}


function ss_log($msg)
{
	global $ss_log_filename,$ss_log_path;


	//echo $ss_log_filename."</br>";

	//echo $msg."</br>";
	///////////////////////////////////////////////////
	if(1)
	{

		$logFile = $ss_log_filename;
		$msgPre = '';

		/*
		 if (!file_exists($logFile))
		 {
		$msgPre = "\r\n<?php  die('access deny!'); ?> \r\n\r\n";
		}
		*/


		/////////////start add by wangcya , 20140827
		if(PHP_VERSION > '5.1') {

			//$timeoffset = intval(settings['timeoffset'] / 3600);
			//@date_default_timezone_set('Etc/GMT'.($timeoffset > 0 ? '-' : '+').(abs($timeoffset)));
			date_default_timezone_set('PRC');
		}
		/////////////end add by wangcya , 20140827////////////////////////////////////

		$msg = $msgPre. sprintf("%s\t%s\r\n",date("Y-m-d H:i:s"),$msg);
		//$msg = $msgPre. sprintf("%s\r\n",$msg);
		
		//////////////////////////////////////////////////////////////////////////
		//$mtime = explode(' ', microtime());
		//$timestamp = $mtime[1];
		
		$file ="running";
		//$yearmonth = sgmdate_x('Ym', timestamp );//$_SGLOBAL['timestamp']);
		$yearmonth = date("Y-m");
		
		$logdir = $ss_log_path;
		
		if(!is_dir($logdir)) 
			mkdir($logdir, 0777);
		
		$create_flag = false;//add by wangcya, 20150126
		
		//echo $yearmonth;
		$logfile = $logdir.$yearmonth.'_'.$file.'.log';
		
		//ss_log_system("before filesize logfile: ".$logfile);
		
		$len_spit = strlen($yearmonth)+1+strlen($file)+1;//多两个 _
		//ss_log_system("间隔长度 len_spit: ".$len_spit);
		
		if(@filesize($logfile) > 2048000) //10
		{
			ss_log_system("超过2M, beyond 2M, file name: ".$logfile);
			
			$dir = opendir($logdir);
			$length = strlen($file);
			$maxid = $id = 0;
			while($entry = readdir($dir)) 
			{
				//ss_log_system("entry: ".$entry);
				
				if(strexists_x($entry, $yearmonth.'_'.$file)) 
				{
					//ss_log_system("in entry: ".$entry);//2015-01_running.log, 2015-01_running_1.log
					
					$len1 = $length + 8+1;
					//ss_log_system("另外一个， 间隔长度 len1: ".$len1);
					
					$len2 =  strlen(".log");
					//ss_log_system("另外一个，len2: ".$len2);
					
					//$len_spit = 8+1
					$str_id = substr($entry, $length + 8+1, -4);
					//ss_log_system("str_id: ".$str_id);
					
					$id = intval($str_id);
					
					//ss_log_system("id: ".$id);
					
					$id > $maxid && $maxid = $id;
					
					//ss_log_system("maxid： ".$maxid);
				}
			}//while
			
			
			closedir($dir);
						
			$logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($maxid + 1).'.log';
			ss_log_system("before while, logfilebak: ".$logfilebak);
	
			//如果这个文件存在，则应该继续增加id
			$new_maxid = $maxid+1; 
			while(file_exists($logfilebak)) 
			{	
				//ss_log_system("logfilebak存在，循环下一个: ".$logfilebak);
				
				$logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($new_maxid + 1).'.log';
				
				//ss_log_system("循环得到后文件名: ".$logfilebak);
				
				$new_maxid++;
			}
			
			
			///////////////////////////////////////////////////
			ss_log_system("will rename: ");
			ss_log_system("logfile: ".$logfile);
			ss_log_system("logfilebak: ".$logfilebak);
					
			$ret = rename($logfile, $logfilebak);//如果执行成功了，但是名称不对，可能覆盖原先的
			if($ret)
			{
				ss_log_system("rename ret true ");
			}
			else
			{
				ss_log_system("rename ret false ");
			}
			
			$create_flag = true;//add by wangcya, 20150126
		}
		
		//////////////////////////////////////////////////////////////////////////
		$mode = 'ab';
		
		//ss_log_system("before open , logfile: ".$logfile);
		
		$fp = @fopen($logfile, $mode);
		if( $fp )
		{
			@flock($fp, LOCK_EX);
			$len = @fwrite($fp, $msg);
			@flock($fp, LOCK_UN);
			@fclose($fp);
			
			//start add by wangcya, 20150126,权限问题
			if($create_flag)
			{ 
				ss_log_system("will chown, chgrp");
				
				chown($logfile, "apache"); 
            	chgrp($logfile, "apache"); 
			}
			//end add by wangcya, 20150126
			
			return $len;
		}

	}

}



function ss_log_task($msg)
{
	global $ss_log_filename_task,$ss_log_path;


	//echo $ss_log_filename."</br>";

	//echo $msg."</br>";
	///////////////////////////////////////////////////
	if(1)
	{

		$logFile = $ss_log_filename_task;
		$msgPre = '';

		/*
		 if (!file_exists($logFile))
		 {
		$msgPre = "\r\n<?php  die('access deny!'); ?> \r\n\r\n";
		}
		*/


		/////////////start add by wangcya , 20140827
		if(PHP_VERSION > '5.1') {

			//$timeoffset = intval(settings['timeoffset'] / 3600);
			//@date_default_timezone_set('Etc/GMT'.($timeoffset > 0 ? '-' : '+').(abs($timeoffset)));
			date_default_timezone_set('PRC');
		}
		/////////////end add by wangcya , 20140827////////////////////////////////////

		$msg = $msgPre. sprintf("%s\t%s\r\n",date("Y-m-d H:i:s"),$msg);
		//$msg = $msgPre. sprintf("%s\r\n",$msg);

		//////////////////////////////////////////////////////////////////////////
		//$mtime = explode(' ', microtime());
		//$timestamp = $mtime[1];

		$file ="runninghour_task";//add by wangcya, 20150114
		//$yearmonth = sgmdate_x('Ym', timestamp );//$_SGLOBAL['timestamp']);
		$yearmonth = date("Y-m");

		$logdir = $ss_log_path;

		if(!is_dir($logdir))
			mkdir($logdir, 0777);

		//echo $yearmonth;
		$logfile = $logdir.$yearmonth.'_'.$file.'.log';
		if(@filesize($logfile) > 2048000) //
		{
			$dir = opendir($logdir);
			$length = strlen($file);
			$maxid = $id = 0;
			while($entry = readdir($dir))
			{
				if(strexists_x($entry, $yearmonth.'_'.$file))
				{
					$id = intval(substr($entry, $length + 8, -4));
					$id > $maxid && $maxid = $id;
				}
			}
				
			closedir($dir);
			$logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($maxid + 1).'.log';
			@rename($logfile, $logfilebak);
		}

		//////////////////////////////////////////////////////////////////////////
		$mode = 'ab';
		$fp = @fopen($logfile, $mode);
		if( $fp )
		{
			@flock($fp, LOCK_EX);
			$len = @fwrite($fp, $msg);
			@flock($fp, LOCK_UN);
			@fclose($fp);
			return $len;
		}

	}

}


function ss_log_policy($msg)
{
	global $ss_log_filename,$ss_log_path;


	//echo $ss_log_filename."</br>";

	//echo $msg."</br>";
	///////////////////////////////////////////////////
	if(1)
	{

		$logFile = $ss_log_filename;
		$msgPre = '';

		/*
		 if (!file_exists($logFile))
		 {
		$msgPre = "\r\n<?php  die('access deny!'); ?> \r\n\r\n";
		}
		*/


		/////////////start add by wangcya , 20140827
		if(PHP_VERSION > '5.1') {

			//$timeoffset = intval(settings['timeoffset'] / 3600);
			//@date_default_timezone_set('Etc/GMT'.($timeoffset > 0 ? '-' : '+').(abs($timeoffset)));
			date_default_timezone_set('PRC');
		}
		/////////////end add by wangcya , 20140827////////////////////////////////////

		$msg = $msgPre. sprintf("%s\t%s\r\n",date("Y-m-d H:i:s"),$msg);
		//$msg = $msgPre. sprintf("%s\r\n",$msg);
		
		//////////////////////////////////////////////////////////////////////////
		//$mtime = explode(' ', microtime());
		//$timestamp = $mtime[1];
		
		$file ="policy";
		//$yearmonth = sgmdate_x('Ym', timestamp );//$_SGLOBAL['timestamp']);
		$yearmonth = date("Y-m");
		
		$logdir = $ss_log_path;
		
		if(!is_dir($logdir)) 
			mkdir($logdir, 0777);
		
		$create_flag = false;//add by wangcya, 20150126
		
		//echo $yearmonth;
		$logfile = $logdir.$yearmonth.'_'.$file.'.log';
		
		//ss_log_system("before filesize logfile: ".$logfile);
		
		$len_spit = strlen($yearmonth)+1+strlen($file)+1;//多两个 _
		ss_log_system("间隔长度 len_spit: ".$len_spit);
		
		if(@filesize($logfile) > 2048000) //10
		{
			$dir = opendir($logdir);
			$length = strlen($file);
			$maxid = $id = 0;
			while($entry = readdir($dir)) 
			{
				ss_log_system("entry: ".$entry);
				
				if(strexists_x($entry, $yearmonth.'_'.$file)) 
				{
					ss_log_system("in entry: ".$entry);//2015-01_running.log, 2015-01_running_1.log
					
					$len1 = $length + 8+1;
					ss_log_system("另外一个， 间隔长度 len1: ".$len1);
					
					$len2 =  strlen(".log");
					ss_log_system("另外一个，len2: ".$len2);
					
					//$len_spit = 8+1
					$str_id = substr($entry, $length + 8+1, -4);
					ss_log_system("str_id: ".$str_id);
					
					$id = intval($str_id);
					
					ss_log_system("id: ".$id);
					
					$id > $maxid && $maxid = $id;
					
					ss_log_system("maxid： ".$maxid);
				}
			}//while
			
			closedir($dir);
			
			
			$logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($maxid + 1).'.log';
			ss_log_system("before while, logfilebak: ".$logfilebak);
	
			//如果这个文件存在，则应该继续增加id
			$new_maxid = maxid+1; 
			while(file_exists($logfilebak)) 
			{	
				ss_log_system("logfilebak存在，循环下一个: ".$logfilebak);
				
				$logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($new_maxid + 1).'.log';
				
				ss_log_system("循环得到后文件名: ".$logfilebak);
				
				$new_maxid++;
			}
			
			
			///////////////////////////////////////////////////
			ss_log_system("will rename: ");
			ss_log_system("logfile: ".$logfile);
			ss_log_system("logfilebak: ".$logfilebak);
					
			$ret = rename($logfile, $logfilebak);//如果执行成功了，但是名称不对，可能覆盖原先的
			if($ret)
			{
				ss_log_system("rename ret true ");
			}
			else
			{
				ss_log_system("rename ret false ");
			}
			
			$create_flag = true;//add by wangcya, 20150126
		}
		
		//////////////////////////////////////////////////////////////////////////
		$mode = 'ab';
		
		//ss_log_system("before open , logfile: ".$logfile);
		
		$fp = @fopen($logfile, $mode);
		if( $fp )
		{
			@flock($fp, LOCK_EX);
			$len = @fwrite($fp, $msg);
			@flock($fp, LOCK_UN);
			@fclose($fp);
			
			//start add by wangcya, 20150126,权限问题
			if($create_flag)
			{ 
				ss_log_system("will chown, chgrp");
				
				chown($logfile, "apache"); 
            	chgrp($logfile, "apache"); 
			}
			//end add by wangcya, 20150126
			
			return $len;
		}

	}

}

//娓呯┖鏃ュ織
function ss_log_reset()
{
	global $ss_log_filename;
	@unlink($ss_log_filename);
}
////////end add by wangcya, 20121218 for bug[]

function echo_to_app($msg)
{
	echo $msg;
	//ss_log($msg);
}
/////////////////////////////////////////////////////////