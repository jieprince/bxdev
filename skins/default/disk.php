<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>主页 - <?php echo $_SLEX['site']['title'] ?> - Powered by Filiant</title>
<link rel="stylesheet" type="text/css" href="skins/default/images/main.css" />
<link rel="stylesheet" type="text/css" href="skins/default/images/disk.css" />
<script type="text/javascript" src="skins/common/images/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="skins/common/images/jquery-ui.min.js"></script>
<script type="text/javascript" src="exts/uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript" src="skins/default/images/disk.js"></script>
<script type="text/javascript">
var session_id = '<?php echo session_id() ?>';
var title = '<?php echo $_SLEX['site']['title'] ?>';
</script>
</head>
<body>
<div id="top">
  <a id="logo" href="/"></a><a href="javascript:void(0);" class="topNavItem topNavItemSelected" onclick="return false;">主页<i></i></a><a href="javascript:void(0);" class="topNavItem" onclick="return false;">存储<i></i></a><a href="javascript:void(0);" class="topNavItem" onclick="return false;">备忘录<i></i></a><a href="javascript:void(0);" class="topNavItem" onclick="return false;">退出登录<i></i></a>
  <div id="topRightNav"><span id="topRightNavUploading">上传中　</span><span>容量：</span><span id="topRightNavSpaceBox"><i></i><div id="topRightNavSpaceBoxProgressBar" tip="<?php echo xFile::toStrSize(xUserMgr::$curuser->disk_usage) ?>" per="<?php echo USAGE_PER ?>" style="width:<?php echo USAGE_PER<6?'5':USAGE_PER ?>%;"><b></b></div></span><span>　<?php echo xFile::toStrSize(xUserMgr::$curuser->disk_usage) ?> / <?php echo xFile::toStrSize((double)xUserMgr::$curuser->disk_max) ?></span></div>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="240" valign="top">
      <div id="contentLeft">
        <div style="display:block;">
          <a href="javascript:void(0);" class="selected" onclick="shareListGet('all')">全部分享</a><a href="javascript:void(0);" onclick="shareListGet('forMe')">向我分享</a><a href="javascript:void(0);" onclick="shareListGet('byMe')">我的分享</a>
        </div>
        <div>
          <a href="javascript:void(0);" class="selected" onclick="listGet('0', '*')">全部文件</a><a href="javascript:void(0);" onclick="listGet('0', 'picture')">我的图片</a><a href="javascript:void(0);" onclick="listGet('0', 'document')">我的文档</a><a href="javascript:void(0);" onclick="listGet('0', 'video')">我的视频</a><a href="javascript:void(0);" onclick="listGet('0', 'torrent')">我的种子</a><a href="javascript:void(0);" onclick="listGet('0', 'music')">我的音乐</a><!--<a href="javascript:void(0);">回收站</a>-->
        </div>
        <div>
          <a href="javascript:void(0);" class="selected">新建记事</a>
        </div>
      </div>
    </td>
    <td valign="top">
      <div id="contentRight">
        <!-- PAGE 1 -->
        <div id="page1" class="page" style="display:block;">
          <div class="page1Loading"></div>
        </div>
        <!-- PAGE 2 -->
        <div id="page2" class="page">
          <div class="box">
            <!-- CONTROL -->
            <div class="page2Nav">
              <span class="button"><a id="uploadBtn" href="javascript:void(0);" onclick="return false;">上传文件<i></i></a></span>　<span class="button buttonBlue"><a id="addFolderBtn" href="javascript:void(0);" onclick="return false;">新建文件夹<i></i></a></span>　<span class="button buttonBlue"><a id="shareBtn" href="javascript:void(0);" onclick="return false;">发布分享<i></i></a></span>　<span class="button buttonBlue"><a id="cancelShareBtn" href="javascript:void(0);" onclick="return false;">取消分享<i></i></a></span>　<span class="button buttonBlue"><a id="renameBtn" href="javascript:void(0);" onclick="return false;">重命名<i></i></a></span><!--　<span class="button buttonBlue"><a href="javascript:void(0);" onclick="return false;">复制<i></i></a></span>-->　<span class="button buttonBlue"><a id="moveBtn" href="javascript:void(0);" onclick="return false;">移动<i></i></a></span>　<span class="button buttonBlue"><a id="removeBtn" href="javascript:void(0);" onclick="return false;">删除<i></i></a></span>
            </div>
            <div id="page2Path">
              <input id="page2PathTxt" name="" type="text" value="/" path="/" readonly="readonly" />
            </div>
            <table id="filesTHeadTable" width="100%" border="0" cellspacing="0" cellpadding="0" class="filesTable">
              <tr>
                <th width="20"><input name="checkbox[-1]" id="checkbox_f1" type="checkbox" value="-1" /></th>
                <th>名称</th>
                <th width="200">大小</th>
                <th width="200">时间</th>
              </tr>
            </table>
            <div id="filesBox">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="filesTable">
                <tbody id="listTBody">
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- PAGE 3 -->
        <div id="page3" class="page">
          <div class="page1Bottom">开发中</div>
        </div>
      </div>
    </td>
  </tr>
</table>
<div id="mask" class="mask"></div>
<div id="addFolderWin" class="window">
  <div class="windowContent">
    <table border="0" cellspacing="0" cellpadding="0" class="sTable">
      <tr>
        <td style="padding-top:0;">请输入文件夹名称：</td>
      </tr>
      <tr>
        <td><input id="addFolderTitleTxt" type="text" class="textbox" /></td>
      </tr>
    </table>
  </div>
  <div class="windowBottom"><span class="button"><a id="addFolderOkBtn" href="javascript:void(0);" onclick="return false;">确定<i></i></a></span>　<span class="button buttonBlue"><a href="javascript:void(0);" onclick="windowHide('#addFolderWin');return false;">取消<i></i></a></span></div>
  <div class="windowMask"></div>
</div>
<!-- 新建文件夹 -->
<div id="addFolderWin" class="window">
  <div class="windowContent">
    <table border="0" cellspacing="0" cellpadding="0" class="sTable">
      <tr>
        <td style="padding-top:0;">请输入文件夹名称：</td>
      </tr>
      <tr>
        <td><input id="addFolderTitleTxt" type="text" class="textbox" /></td>
      </tr>
    </table>
  </div>
  <div class="windowBottom"><span class="button"><a id="addFolderOkBtn" href="javascript:void(0);" onclick="return false;">确定<i></i></a></span>　<span class="button buttonBlue"><a href="javascript:void(0);" onclick="windowHide('#addFolderWin');return false;">取消<i></i></a></span></div>
  <div class="windowMask"></div>
</div>
<!-- 重命名文件夹或文件 -->
<div id="renameWin" class="window">
  <div class="windowContent">
    <table border="0" cellspacing="0" cellpadding="0" class="sTable">
      <tr>
        <td style="padding-top:0;">请输入新的名称：</td>
      </tr>
      <tr>
        <td><input id="renameTitleTxt" type="text" class="textbox" /></td>
      </tr>
    </table>
  </div>
  <div class="windowBottom"><span class="button"><a id="renameOkBtn" href="javascript:void(0);" onclick="return false;">确定<i></i></a></span>　<span class="button buttonBlue"><a href="javascript:void(0);" onclick="windowHide('#renameWin');return false;">取消<i></i></a></span></div>
  <div class="windowMask"></div>
</div>
<!-- 上传窗口 -->
<div id="uploadWin" class="window">
  <div class="windowContent" style="padding:0;">
    <div id="uploadWinTitle">文件上传</div>
    <table width="720" border="0" cellspacing="0" cellpadding="0" class="filesTable">
      <tr>
        <th>名称</th>
        <th width="80">大小</th>
        <th width="30" style="padding-right:30px;">进度</th>
      </tr>
    </table>
    <div id="uploadWinFilesBox" style="_width:715px;">
      <table width="702" border="0" cellspacing="0" cellpadding="0" class="filesTable">
        <tbody id="uploadWinTBody">
        </tbody>
      </table>
    </div>
  </div>
  <div class="windowBottom"><span class="button buttonBlue"><a href="javascript:void(0);" onclick="windowHide('#uploadWin');return false;">隐藏<i></i></a></span><div id="uploadWinSpeedBox">0K/S</div></div>
</div>
<!-- 移动窗口 -->
<div id="moveWin" class="window">
  <div class="windowContent">
    选择要移动到的文件夹：
    <div id="moveWinFilesBox">
      <div class="moveWinItem moveWinItemSelected" fid="0" paths="/" opend="0">我的存储</div>
    </div><br />
    当前选择：<span id="moveWinSelected" fid="0">/</span>
  </div>
  <div class="windowBottom"><span class="button"><a id="moveOkBtn" href="javascript:void(0);" onclick="return false;">确定<i></i></a></span>　<span class="button buttonBlue"><a href="javascript:void(0);" onclick="windowHide('#moveWin');return false;">取消<i></i></a></span></div>
  <div class="windowMask" style="cursor:progress;"></div>
</div>
</body>
</html>