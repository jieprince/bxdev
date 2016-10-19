<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if($_APP['seo']['keywords']!=''){ ?><meta name="keywords" content="<?php echo $_APP['seo']['keywords'] ?>" /><?php } ?>
<?php if($_APP['seo']['description']!=''){ ?><meta name="description" content="<?php echo $_APP['seo']['description'] ?>" /><?php } ?>
<meta name="generator" content="Filiant" />
<meta name="author" content="Filiant Team" />
<meta name="copyright" content="2007-2014 Maiyun.NET" />
<title><?php echo $_SLEX['site']['title'] ?> - Powered by Filiant</title>
<link rel="stylesheet" type="text/css" href="skins/default/images/main.css" />
<link rel="stylesheet" type="text/css" href="skins/default/images/index.css" />
<script type="text/javascript" src="skins/common/images/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="skins/default/images/index.js"></script>
</head>
<body>
<div id="top">
  <div id="topContent">
    <a id="logo" href="/"></a>
    <div id="topRightNav"><a href="register.php">注册账号</a></div>
  </div>
</div>
<div id="content">
  <div id="shows">
    <div style="background-image:url(skins/default/images/banner.jpg);" class="selected"></div>
    <div style="background-image:url(skins/default/images/banner-1.jpg);"></div>
  </div>
  <div id="loginBox">
<?php
if(xUserMgr::$curuser->isGuest()) {
?>
    <span class="textbox"><input type="text" name="usernameTxt" id="usernameTxt" /></span>　<span class="textbox"><input type="password" name="passwordTxt" id="passwordTxt" /></span>　<span class="button"><a id="loginBtn" href="javascript:void(0);" onclick="return false;">登录</a><i></i></span>
<?php
} else {
?>
    <span class="button"><a href="disk.php" onclick="$(this).css({'opacity':'.5'});">进入<?php echo xUserMgr::$curuser->username ?>的存储空间</a><i></i></span>
<?php
}
?>
  </div>
</div>
<div id="bottom">
  <a href="/">网站首页</a> - <a href="javascript:void(0);">联系我们</a> - <a href="admin.php" target="_blank">后台管理</a><?php echo ($_ENV->site->icp=='')?'':' - <a href="http://www.miibeian.gov.cn/" target="_blank">'.$_ENV->site->icp.'</a>' ?><?php echo $_ENV->site->statcode ?><br />
  此网站由 <a href="http://www.maiyun.net" target="_blank">迈云网络</a> <a href="http://www.maiyun.net/products/filiant/" target="_blank">Filiant</a> 提供技术支持
</div>
</body>
</html>