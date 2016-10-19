<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>验证 - <?php echo $_SLEX['site']['title'] ?> - Powered by Filiant</title>
<link rel="stylesheet" type="text/css" href="/skins/default/images/main.css" />
<link rel="stylesheet" type="text/css" href="/skins/default/images/index.css" />
<link rel="stylesheet" type="text/css" href="/skins/default/images/verify.css" />
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
<?php
if(KEY_ISVALID) {
?>
      <div class="errorTxt">邮箱已经成功验证</div>
      <div class="errorTip">您的账号已经激活，您可以尽情的享用了！<br /><br /><a href="./">点我返回首页</a></div>
<?php
} else {
?>
      <div class="errorTxt">无效的验证代码</div>
      <div class="errorTip">这是个无效的验证码，请检查后再试<br /><br /><a href="./">点我返回首页</a></div>
<?php
}
?>
    </div>
  </div>
</div>
<div id="bottom">
  <a href="/">网站首页</a> - <a href="javascript:void(0);">联系我们</a> - <a href="admin.php">后台管理</a><?php echo ($_ENV->site->icp=='')?'':' - <a href="http://www.miibeian.gov.cn/" target="_blank">'.$_ENV->site->icp.'</a>' ?><?php echo $_ENV->site->statcode ?><br />
  此网站由 <a href="http://www.maiyun.net" target="_blank">迈云网络</a> <a href="http://www.maiyun.net/products/filiant/" target="_blank">Filiant</a> 提供技术支持
</div>
</body>
</html>