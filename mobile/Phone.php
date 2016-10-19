<?php

/**
 * ECSHOP 用户中心
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: user.php 16643 2009-09-08 07:02:13Z liubo $
*/

define('IN_ECS', true);
define('ECS_ADMIN', true);

require(dirname(__FILE__) . '/includes/init.php');
/* 载入语言文件 */
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/user.php');
/* 短信动态验证码 提现 充值 转账 baohongzhou 14-12-19 下午2:20 */

    include_once('sdk_sms.php');
     //$mobile = $_POST['username'];
     $num = rand(1000,9999);
     //$phone_code['username'] = $mobile;
     $phone_code['code'] = $num;
     $_SESSION['phone_code'] = $phone_code;

     if($_POST['judge'] == 1){

        $content = "您本次在本网站充值的动态验证码为".$num;

     }elseif($_POST['judge'] == 2){

       $content = "您本次提现的动态验证码为".$num;

     }elseif($_POST['judge'] == 3){

       $content = "您本次转账的动态验证码为".$num;

     }

     //modify yes123 2014-11-30 手机号码不应该从前台获取,从数据库里查询,
     $sql = "SELECT mobile_phone FROM " . $ecs->table('users') ." WHERE user_id = '$_SESSION[user_id]'";
     $mobile = $db->getOne($sql);

     //$result = gxmt_post($sdk_sn,$sdk_pwd,$mobile,$content);
     $result = send_msg($mobile,$content,1,VERIFICATION_CODE_SMS);
     //add yes123 2014-12-15 如果为-5欠费
     if($result==-5)
     {
            writeLog();

     }
     //add by dingchaoyang 2014-12-5
     //响应json数据到客户端
     include_once (ROOT_PATH . 'api/EBaoApp/eba_adapter.php');
     EbaAdapter::responseSmsCheckCode($num);
     //end add by dingchaoyang 2014-12-5
     //add yes123 2014-11-30 不能给前台返回明文的验证码
     $num = md5($num);
     $datas = json_encode(array('data'=>$result,'num'=>$num));
     echo $datas;
?>