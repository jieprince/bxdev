<?php

/**
 * ECSHOP 加密解密类
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: lib_code.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

//added by zhangxi, 20150129,加密函数
function ebaoins_encrypt($str_need_crypted, $key =AUTH_KEY)
{
	$str_encrypted = encrypt($str_need_crypted,$key);
	$data = $str_need_crypted.','.$str_encrypted;
	return $data;
}

//added by zhangxi, 20150129, 校验加密后的参数，校验正确，返回1，校验错误，返回0
function ebaoins_encrypt_check($str_encrypted, $key = AUTH_KEY)
{
	$decode_data = explode(',' ,$str_encrypted);
	$uid = $decode_data[0];
	$encrypted_data = $decode_data[1];
	$decrypted_uid = decrypt($encrypted_data, $key);
	ss_log(__FUNCTION__."str_encrypted=".$str_encrypted."encrypted_data=".$encrypted_data.",decrypted_uid=".$decrypted_uid.",uid=".$uid);
	if($decrypted_uid == $uid)
	{
		return 1;
	}
	else
	{
		return 0;
	}
}


/**
 * 加密函数
 * @param   string  $str    加密前的字符串
 * @param   string  $key    密钥
 * @return  string  加密后的字符串
 */
function encrypt($str, $key = AUTH_KEY)
{
    $coded = '';
    $keylength = strlen($key);

    for ($i = 0, $count = strlen($str); $i < $count; $i += $keylength)
    {
        $coded .= substr($str, $i, $keylength) ^ $key;
    }

    return str_replace('=', '', base64_encode($coded));
}

/**
 * 解密函数
 * @param   string  $str    加密后的字符串
 * @param   string  $key    密钥
 * @return  string  加密前的字符串
 */
function decrypt($str, $key = AUTH_KEY)
{
    $coded = '';
    $keylength = strlen($key);
    $str = base64_decode($str);

    for ($i = 0, $count = strlen($str); $i < $count; $i += $keylength)
    {
        $coded .= substr($str, $i, $keylength) ^ $key;
    }

    return $coded;
}

?>