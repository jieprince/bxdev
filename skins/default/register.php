<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>注册 - <?php echo $_SLEX['site']['title'] ?> - Powered by Filiant</title>
<link rel="stylesheet" type="text/css" href="/skins/default/images/main.css" />
<link rel="stylesheet" type="text/css" href="/skins/default/images/index.css" />
<link rel="stylesheet" type="text/css" href="/skins/default/images/register.css" />
<script type="text/javascript" src="skins/common/images/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="skins/common/images/jquery-ui.min.js"></script>
<script type="text/javascript" src="skins/default/images/register.js"></script>
</head>
<body>
<div id="top">
  <div id="topContent">
    <a id="logo" href="/"></a>
    <div id="topRightNav"><a href="./">返回首页</a></div>
  </div>
</div>
<div id="content">
  <div id="contentBody">
    <div id="contentBodyBox">
      <div id="regBox">
        <h2 align="center">用户注册</h2>
<?php
if($_APP['register']['open']) {
?>
        <div class="textboxEx"><input type="text" id="usernameTxt" /><span>用户名</span></div>
        <div class="textboxEx"><input type="password" id="passwordTxt" /><span>密码</span></div>
        <div class="textboxEx"><input type="text" id="emailTxt" /><span>邮箱</span></div>
        <div class="textboxEx"><input type="text" id="verTxt" /><span>验证码</span></div>
        <div class="textboxEx" style="padding:25px 0;"><span style="font-size:12px;top:-11px;color:#286FBF;padding-left:5px;padding-right:5px;">验证码图片</span><div style="text-align:center;"><img id="verifyImg" src="exts/php_verify/verify.php" onclick="$(this).attr('src', 'exts/php_verify/verify.php?'+Math.random())" style="cursor:pointer;" /></div></div>
        <div style="text-align:center;">
          <span class="button"><a id="regBtn" href="javascript:void(0);" onclick="return false;">注册</a><i></i></span>
        </div>
<?php
} else {
?>
        <div class="closeinfoBox">站方关闭了注册功能<br /><?php echo $_APP['register']['closeinfo'] ?></div>
<?php
}
?>
      </div>
    </div>
  </div>
</div>
<div id="bottom">
  <a href="/">网站首页</a> - <a href="javascript:void(0);">联系我们</a> - <a href="admin.php">后台管理</a><?php echo ($_ENV->site->icp=='')?'':' - <a href="http://www.miibeian.gov.cn/" target="_blank">'.$_ENV->site->icp.'</a>' ?><?php echo $_ENV->site->statcode ?><br />
  此网站由 <a href="http://www.maiyun.net" target="_blank">迈云网络</a> <a href="http://www.maiyun.net/products/filiant/" target="_blank">Filiant</a> 提供技术支持
</div>
</body>
</html>