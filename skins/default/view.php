<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo FILE_NAME ?> - <?php echo $_SLEX['site']['title'] ?> - Powered by Filiant</title>
<link rel="stylesheet" type="text/css" href="skins/default/images/main.css" />
<link rel="stylesheet" type="text/css" href="skins/default/images/view.css" />
<script type="text/javascript" src="skins/common/images/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="skins/default/images/view.js"></script>
</head>
<body>
<div id="top">
  <div id="topContent">
    <a id="logo" href="/"></a>
    <div id="topRightNav"><a href="disk.php">返回存储空间</a></div>
  </div>
</div>
<div id="content">
  <div id="contentBody">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
          <div id="fileContent">
            <strong><?php echo FILE_NAME ?></strong><br /><br />
            文件大小：<?php echo FILE_SIZE ?><br />
            分享时间：<?php echo FILE_SHARETIME ?><br />
            下载次数：<?php echo FILE_DOWNLOADS ?> 次
          </div>
          <div id="fileDownloadBox">
            <span class="button"><a id="downloadBtn" href="javascript:void(0);" onclick="downurlGet(<?php echo FILE_ID ?>);return false;">立即下载<i></i></a></span><!--　<span class="button buttonBlue"><a id="saveBtn" href="javascript:void(0);" onclick="return false;">保存到存储空间<i></i></a></span>-->
          </div>
          <div id="fileTipsBox">本站严禁上传包括反动、色情、暴力、违法及侵权内容的文件。</div>
        </td>
        <td width="250" valign="top">
          <a href="http://www.maiyun.net/products/filiant/" target="_blank"><img src="skins/default/images/show.jpg" /></a>
        </td>
      </tr>
    </table>
  </div>
</div>
<div id="bottom">
  <a href="/">网站首页</a> - <a href="javascript:void(0);">联系我们</a> - <a href="admin.php" target="_blank">后台管理</a><?php echo ($_ENV->site->icp=='')?'':' - <a href="http://www.miibeian.gov.cn/" target="_blank">'.$_ENV->site->icp.'</a>' ?><?php echo $_ENV->site->statcode ?><br />
  此网站由 <a href="http://www.maiyun.net" target="_blank">迈云网络</a> <a href="http://www.maiyun.net/products/filiant/" target="_blank">Filiant</a> 提供技术支持
</div>
<div id="downurlGetMask">正在获取文件</div>
</body>
</html>