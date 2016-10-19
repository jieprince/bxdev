<?php if(!defined('IN_UCHOME')) exit('Access Denied');?><?php subtplcheck('template//cp_admin_insurance_company|template//header|template//space_admin_sidebar|template//footer', '1417498657', 'template//cp_admin_insurance_company');?><!DOCTYPE html>

<html>

<head>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<meta name="viewport" content="width=device-width, initial-scale=1.0,   target-densitydpi=medium-dpi,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" />

<meta name="description" content="保险代理人服务平台" />
 
<meta name="keywords" content="保险代理人服务平添" />

<!--for qq login, comment by wangcya ,20130322-->

<meta property="qc:admins" content="450736211763167116375" />
<!-–[if lte IE 8]>
<meta http-equiv=”x-ua-compatible” content="ie=7" />
<noscript>
 <style>.html5-wrappers{display:none!important;}</style>
     <div class="ie-noscript-warning">您的浏览器禁用了脚本，请
 <a href="">查看这里</a>来启用脚本!或者<a href="/?noscript=1">继续访问</a>
     </div>
</noscript> 

<![endif]-–>
 

<!–-[if IE 9]>
<script> 
   (function() {
     //if (!0) return;
     var e = "abbr, article, aside, audio, canvas, datalist, details, dialog, eventsource, figure, footer, header, hgroup, mark, menu, meter, nav, output, progress, section, time, video".split(', ');
     var i= e.length;
     while (i--)
 {
         document.createElement(e[i])
     } 
})() ;
</script>
 <![endif]-–>
 

<title>首页 - 保险平台！</title>

<link href="style/css.css" rel="stylesheet" type="text/css" />
<link href="style/style.css" rel="stylesheet" type="text/css" />

<script src="source/jquery.min.js"></script>
<link href="source/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="source/jquery-ui.min.js"></script>


<script src="source/script_function_common.js" language="javascript" type="text/javascript"></script>



<style type="text/css">

@import url(template/default/global.css);

@import url(template/default/base.css);

<?php if($_TPL['css'] == 'admin_product') { ?>


@import url(template/default/default.css);
@import url(template/default/accordion.css);
@import url(template/component/dtree/dtree.css);

@import url(template/default/style.css);


<?php } elseif($_TPL['css'] == 'client_product') { ?>

@import url(template/default/pingan/base.css);
@import url(template/default/pingan/global.css);

@import url(template/default/pingan/public.css);

@import url(template/default/insurance_product.css);
@import url(template/default/pingan/buy_pro_det.css);
@import url(template/default/pingan/simple_header.css);
@import url(template/default/pingan/newflat.css);
@import url(template/default/pingan/newflat3.css);
@import url(template/default/pingan/common.css);


@import url(template/default/products.css);


<?php } elseif($_TPL['css'] == 'cp_product_buy_taipingyang') { ?>
@import url(template/default/taipingyang.css);


<?php } ?>

</style>
<!-- 




</head>
 

<body>

<div id="header">  </div>

<div id="mainarea">


<?php if(0) { ?>

<div id="shortcut-2013">
<div class="top">
<ul class="fl lh">
<li class="fore1 ld"><b></b><a href="javascript:addToFavorite()" rel="nofollow">收藏</a></li>
</ul>
<ul class="fr lh">

<?php if($_SGLOBAL ['supe_uid'] ) { ?>

<li class="fore1" id="loginbar">

<?=$_SGLOBAL['supe_username']?>！欢迎到来！

<span>
<a href="cp.php?ac=common&op=logout&uhash=<?=$_SGLOBAL['uhash']?>">[注销]</a>
<span>

</li>

<?php } else { ?>

<li class="fore1" id="loginbar">

<span>
<a href="do.php?ac=login">[登录]</a>

<a href="do.php?ac=register" class="link-regist">[免费注册]</a>
</span>
</li>
<?php } ?>

<li class="fore2 ld">
<s></s>
<a href="http://jd2008.jd.com/JdHome/OrderList.aspx" rel="nofollow">我的订单</a>
</li>
            <li class="fore2-1 ld" id="jd-vip">
                <s></s><img src="http://misc.360buyimg.com/lib/skin/2013/i/vip-new-ico.png" height="11" width="24">
                <a target="_blank" rel="nofollow" href="http://vip.jd.com">会员俱乐部</a>
            </li>
            <li class="fore3 ld menu" id="app-jd" data-widget="dropdown"><s></s><i></i><span class="outline"></span><span class="blank"></span><a href="http://app.jd.com/" target="_blank">手机端</a><b></b><div class="dd lh"><div class="qr-item qr-jd-app"><div class="qr-img"><img id="app-qrcode-img" data-img="1" trigger-lazy-img="http://img11.360buyimg.com/da/g14/M05/1D/01/rBEhVVNNDBcIAAAAAABl7mCid5IAAL__wNCQTIAAGYG408.png" class="err-product" alt="手机端" src="http://misc.360buyimg.com/lib/img/e/blank.gif" height="76" width="76"></div><div class="qr-ext"><strong>手机客户端</strong>
            <a clstag="homepage|keycount|home2013|01d01" href="http://itunes.apple.com/cn/app/id414245413" target="_blank" class="btn-app-apple"></a><a clstag="homepage|keycount|home2013|01d02" href="http://app.jd.com/download/android/360buy.apk" target="_blank" class="btn-app-android"></a></div></div><div class="qr-item qr-jd-jr"><div class="qr-img"><img id="app-qrcode-img" data-img="1" trigger-lazy-img="http://img13.360buyimg.com/da/g13/M00/09/1A/rBEhUlNfWZwIAAAAAAEASBZlCNAAAMoggAwdV8AAQBg657.png" class="err-product" alt="网银钱包客户端" src="http://misc.360buyimg.com/lib/img/e/blank.gif" height="76" width="76"></div><div class="qr-ext"><strong>网银钱包客户端</strong><a clstag="homepage|keycount|home2013|01d03" href="https://itunes.apple.com/cn/app/wang-yin-qian-bao/id832444218?mt=8" target="_blank" class="btn-app-apple"></a><a clstag="homepage|keycount|home2013|01d04" href="http://m.wangyin.com/basic/download/android/jddt-wangyin.apk " target="_blank" class="btn-app-android"></a></div></div></div></li>
<li class="fore4 ld menu" id="biz-service" data-widget="dropdown">
<s></s>
<span class="outline"></span>
<span class="blank"></span>
<a href="space.php?do=products_list">前端业务 </a>
<b></b>
<div class="dd">
<div><a href="http://help.jd.com/index.html" target="_blank">帮助中心</a></div>
<div><a href="http://myjd.jd.com/repair/orderlist.action" target="_blank" rel="nofollow">售后服务</a></div>
<div><a href="http://chat.jd.com/jdchat/custom.action" target="_blank" rel="nofollow">在线客服</a></div>
<div><a href="http://myjd.jd.com/opinion/list.action" target="_blank" rel="nofollow">投诉中心</a></div>
<div><a href="http://www.jd.com/contact/service.html" target="_blank">客服邮箱</a></div>
</div>
</li>
<li class="fore5 ld menu" id="site-nav" data-widget="dropdown">
<s></s>
<span class="outline"></span>
<span class="blank"></span>
<a href="space.php?do=admin_insurance_type">后台管理 </a>
<b></b>
<div class="dd lh">
<dl class="item fore1">
<dt>特色栏目</dt>
<dd>
<div><a target="_blank" href="http://my.jd.com/personal/guess.html">为我推荐</a></div>
<div><a target="_blank" href="http://shipingou.jd.com/">视频购物</a></div>
<div><a target="_blank" href="http://club.jd.com/">京东社区</a></div>
<div><a target="_blank" href="http://xiaoyuan.jd.com/">校园频道</a></div>
<div><a target="_blank" href="http://read.jd.com/">在线读书</a></div>
<div><a target="_blank" href="http://diy.jd.com/">装机大师</a></div>
<div><a target="_blank" href="http://market.jd.com/giftcard/">京东卡</a></div>
                            <div><a href="http://channel.jd.com/jiazhuang.html" target="_blank">家装城</a></div>
                            <div><a href="http://dapeigou.jd.com/" target="_blank">搭配购</a></div>
                            <div><a href="http://xihuan.jd.com/" target="_blank">我喜欢</a></div>
                        </dd>
</dl>
<dl class="item fore2">
<dt>企业服务</dt>
<dd>
<div><a target="_blank" href="http://giftcard.jd.com/company/index">企业客户</a></div>
<div><a target="_blank" href="http://sale.jd.com/p10997.html">办公直通车</a></div>
</dd>
</dl>
<dl class="item fore3">
<dt>旗下网站</dt>
<dd>
<div><a target="_blank" href="http://www.360top.com/">360TOP</a></div>
<div><a target="_blank" href="http://en.jd.com/">English Site</a></div>
</dd>
</dl>
</div>
</li>
</ul>
<span class="clr"></span>
</div>
</div>
 shortcut-2013
 --> 
 <?php } ?>
<!-- 
<div class="top">
  <div class="center"><a href="#" title="">手机版本</a><span>|</span><a href="#" title="">微信公众平台</a><em>&nbsp;</em>
  
  			
<?php if($_SGLOBAL ['supe_uid'] ) { ?>
<?=$_SGLOBAL['supe_username']?>！欢迎到来！

<a href="cp.php?ac=common&op=logout&uhash=<?=$_SGLOBAL['uhash']?>">[注销]</a><span>|</span>

   <?php } else { ?>	
     <a href="do.php?ac=login">[登录]</a><span>|</span>
  	 <a href="do.php?ac=register" class="link-regist">[免费注册]</a><span>|</span>
    <?php } ?>			
  

  <a href="#" title="">我的订单</a><span>|</span>
  <a href="#" title="">用户中心</a><span>|</span>
  <a href="#" title="">网站地图</a><span>|</span>
  <a href="space.php?do=products_list">前端业务 </a><span>|</span>
  <a href="space.php?do=admin_insurance_type">后台管理 </a>
  
  </div>
</div>


  

<div class="logo">
  <div class="center">
    <div class="ft_l"><a href="#"><img src="image/logo.png" height="73" width="232"></a></div>
    <div class="ft_r">
      <form>
        <table cellpadding="0" cellspacing="0" border="0" width="200">
          <tbody><tr>
            <td width="144"><input class="inputbox" name="" type="text"></td>
            <td width="56"><input name="" src="image/menu05.png" type="image"></td>
          </tr>
        </tbody></table>
      </form>
    </div>
    <div class="clearft"></div>
  </div>
</div>
 end logo




<div class="nav">
  <div class="center">
    <ul>
  <li class="linkbg"><a href="#" title="" style="padding:0;"><img src="image/liebie.jpg" height="35" width="189"></a></li>
      <li><a href="space.php?do=home" title="首页">首页</a></li>
      <li><a href="space.php?do=products_list" title="保险产品">保险产品</a></li>
      <li><a href="space.php?do=agent" title="找代理人">找代理人</a></li>
      <li><a href="space.php?do=video" title="保险视频">保险视频</a></li>
      <li><a href="space.php?do=paymentclaims" title="理赔服务">理赔服务</a></li>
      <li><a href="space.php?do=introduce" title="平台介绍">平台介绍</a></li>
    </ul>
  </div>
</div> -->
<!--end nav-->




<div id="main-content">

<!-- <div id="sidebar">

<div class="top"></div>
<div class="content">
    

<script type="text/javascript">
    $(document).ready(function(){
        $('#accordion').accordion({
            active: false,
            header: 'a.head',
            navigation: true,
            event: 'dblclick',
            clearStyle: true,
            animated: 'easeslide',
            heightStyle: 'fill'
        });
    });
</script>

<div class="ui-accordion" id="accordion">
    <a href="/productAction.do?method=list" class="selected">保险产品</a>
    
    <div class="dtree">
    
<script type="text/javaScript">
    var menuTree = new dTree('menuTree');
menuTree.add(1100, -1, '险种类别22', 'space.php?do=admin_insurance_type', '险种类别22', '_self', '', '');
menuTree.add(1200, -1, '保险公司', 'space.php?do=admin_insurance_company', '保险公司', '_self', '', '');
menuTree.add(1300, -1, '保险产品', 'space.php?do=admin_insurance_products', '保险产品', '_self', '', '');
menuTree.add(1400, -1, '附加险', '/insuranceSortAction.do?method=list', '附加险', '_self', '', '');
menuTree.add(1500, -1, '保险责任', '/insuranceDutyAction.do?method=list', '保险责任', '_self', '', '');
menuTree.add(1600, -1, '职业分类', '/careerCategoryAction.do?method=list', '职业分类', '_self', '', '');
menuTree.add(1700, -1, '参数管理', '/insurancePropertyAction.do?method=list', '参数管理', '_self', '', '');
menuTree.add(1800, -1, '参数关联', '/insurancePropertyRelationAction.do?method=list', '参数关联', '_self', '', '');
menuTree.add(1900, -1, '报价人', '/insurerBidderAction.do?method=list', '报价人', '_self', '', '');
document.write(menuTree);
</script>

<div class="dtree">
<div class="dTreeNode">


<div class="dTreeNode">
<a id="smenuTree1" class="<?=$activetree['insurance_company']?>" href="space.php?do=admin_insurance_user" title="用户列表" target="_self" onclick="javascript: menuTree.s(1);">用户列表</a></div>

<a id="smenuTree0" class="<?=$activetree['insurance_type']?>" href="space.php?do=admin_insurance_type" title="险种类别" target="_self" onclick="javascript: menuTree.s(0);">险种类别</a>
</div>

<div class="dTreeNode">
<a id="smenuTree1" class="<?=$activetree['insurance_company']?>" href="space.php?do=admin_insurance_company" title="保险公司" target="_self" onclick="javascript: menuTree.s(1);">保险公司</a></div>

<div class="dTreeNode">
<a id="smenuTree6" class="<?=$activetree['insurance_duty']?>" href="space.php?do=admin_insurance_duty" title="保险责任" target="_self" onclick="javascript: menuTree.s(4);">保险责任</a>
</div>

<div class="dTreeNode">
<a id="smenuTree7" class="<?=$activetree['career_category']?>" href="space.php?do=admin_career_category" title="职业分类" target="_self" onclick="javascript: menuTree.s(5);">职业分类</a>
</div>


<div class="dTreeNode">
<a id="smenuTree2" class="<?=$activetree['insurance_product_attribute']?>" href="space.php?do=admin_insurance_product_attribute" title="保险公司" target="_self" onclick="javascript: menuTree.s(1);">产品属性</a></div>


<div class="dTreeNode">
<a id="smenuTree3" class="<?=$activetree['insurance_product']?>" href="space.php?do=admin_insurance_product" title="保险产品" target="_self" onclick="javascript: menuTree.s(2);">保险产品</a>
</div>

<div class="dTreeNode">
<a id="smenuTree4" class="<?=$activetree['insurance_additional']?>" href="space.php?do=admin_insurance_additional" title="附加险" target="_self" onclick="javascript: menuTree.s(3);">附加险</a>
</div>

<div class="dTreeNode">
<a id="smenuTree5" class="<?=$activetree['insurance_order']?>" href="space.php?do=admin_insurance_order" title="订单管理" target="_self" onclick="javascript: menuTree.s(3);">订单管理</a>
</div>



<div class="dTreeNode">
<a id="smenuTree5" class="<?=$activetree['insurance_policy']?>" href="space.php?do=admin_insurance_policy" title="保单管理" target="_self" onclick="javascript: menuTree.s(3);">保单管理</a>
</div>

</div>

</div>
<a href="/applicantIndividualAction.do?method=list" class="head">客户管理</a>

<div style="height: 0px; display: none;"></div>
<a href="/salesChannelAction.do?method=list" class="head">营销机构</a>
<div style="height: 0px; display: none;"></div><a href="/bankAction.do?method=list" class="head">银行档案</a><div style="height: 0px; display: none;"></div><a href="/contactAction.do?method=list" class="head">客户关系</a><div style="height: 0px; display: none;"></div><a href="/configCodeManagerAction.do?method=list" class="head">系统设置</a><div style="height: 0px; display: none;"></div>
</div>

</div>
<div class="bottom"></div>
</div> -->
<script type="text/javascript" src="../js/jquery-1.8.3.min.js"></script>
<!-- <script type="text/javascript" src="../js/jquery.form.js"></script> -->
<div id="content-body">
                                <div id="toolbar">






</div>
                                <div id="error">









</div>
                                <div id="navigator">





    <div class="content">
        当前位置：
        
            
            
                
            
            
             <a href="space.php?do=admin_insurance_company">   保险公司 </a>
            
        
            <label class="separator">&gt;</label>
            
                
            
            
                新建
            
        
    </div>

</div>
                                <div id="main-body">



<form name="productForm" method="POST" action="cp.php?ac=admin_insurance_company&insurer_id=<?=$blog['insurer_id']?>">

<input type="hidden" name="blogsubmit" id="blogsubmit" value="true" />
<input type="hidden" name="logo" id="logo" value="" />
<input type="hidden" name="formhash" value="<?php echo formhash(); ?>" />
 
     <input name="id" value="<?=$id?>" type="hidden">

    <table class="list">
        <tbody><tr valign="top">
            <td class="listData">


<table class="editable-table">


    <tbody>

    
       <tr> <td colspan="4" class="editable-header"><span class="tdTitle">保险公司</span>基本信息</td>
        
    </tr>
    <tr>

        <td class="tdTitle">
   
        上级：</td>
        <td class="tdEditArea">
<?php if(0) { ?>

            <input name="parent_id" value="<?=$blog['parent_id']?>" type="hidden">
            <input name="parent_name" value="<?=$blog['parent_name']?>" readonly="readonly" class="ref" type="text">
            <a href="javascript: void(0)" onclick="javascript: openReferenceDialog('/productAction.do?method=list&amp;type=select', 'parent', 'false')"><img src="/pages/images/select.gif">
<?php } ?>


<select id="parent_id" name="parent_id">
<option value="0">&nbsp;</option>

<?php if(is_array($list_parent)) { foreach($list_parent as $key => $value) { ?>
<option value="<?=$value['insurer_id']?>" <?=$parent_arr[$value['insurer_id']]?>><?=$value['insurer_name']?></option>
<?php } } ?>

</select>


</a>
            
        </td>

 <td class="tdTitle">地区：</td>
        <td class="tdEditArea">
            <input name="area_id" value="<?=$blog['area_id']?>" type="hidden">
            <input name="area_name" value="<?=$blog['area_name']?>" readonly="readonly" class="ref" type="text">
            <a href="javascript: void(0)" onclick="javascript: openReferenceDialog('/productAction.do?method=list&amp;type=select', 'parent', 'false')"><img src="/pages/images/select.gif">
</a>
            
        </td>

<?php if(0) { ?>
      
    <td class="requiredField">类别：</td>
        <td class="tdEditArea">
            <input name="type_id" value="<?=$blog['type_id']?>" type="hidden">
            <input name="type_name" value="<?=$blog['type_name']?>" readonly="readonly" class="ref" type="text">
            <a href="javascript: void(0)" onclick="javascript: openReferenceDialog('/baseDataAction.do?method=list&amp;type=select&amp;baseType=productType', 'productType', 'false')"><img src="/pages/images/select.gif"></a>
            
        </td>
<?php } ?>
    </tr>
    <tr>
        <td class="requiredField">编码：</td>
        <td class="tdEditArea">
<input name="insurer_code" value="<?=$blog['insurer_code']?>" type="text"></td>
        <td class="requiredField">名称：</td>
        <td class="tdEditArea">
<input name="insurer_name" value="<?=$blog['insurer_name']?>" type="text">
</td>
    </tr>
    <tr>
    	<td>
    	     Logo:<input id="fileupload" type="file" name="logo">
        <div class="progress">
    		<span class="bar"></span><span class="percent">0%</span >
</div>
     <div class="files"></div>
        <div id="showimg">
        	<img alt="" src='<?=$blog['logo']?>'>
        </div>
    	</td>
    </tr>
    <tr>
        <td class="requiredField">
  
        简称：</td>
        <td class="tdEditArea" colspan="3">
<input name="insurer_abbrname" value="<?=$blog['insurer_abbrname']?>" class="long" type="text"></td>
    </tr>
    <tr>
        <td class="tdTitle">描述：</td>
        <td class="tdEditArea" colspan="3">

<textarea name="brief" class="editor"><?=$blog['brief']?></textarea>
</td>

    </tr>
    
    <tr>
        <td class="tdTitle">备注信息：</td>
        <td class="tdEditArea" colspan="3">
<textarea name="note" class="note"><?=$blog['note']?></textarea></td>
    </tr>
</tbody>
</table>
</td>
        </tr>
    </tbody></table>
    
    <table class="buttonArea">
        <tbody><tr>
            <td class="promptText">
                请注意：红色标题所指示的数据项必须填写或选择。
            </td>
            <td class="buttons">
                <input value="保存" class="webButton" type="submit">
                <input value="清除" class="webButton" type="reset">
            </td>
        </tr>
    </tbody></table>
    
</form>


</div>
                            </div>


 </div> 
 
<script type="text/javascript">
$(function () {
var bar = $('.bar');
var percent = $('.percent');
var showimg = $('#showimg');
var progress = $(".progress");
var files = $(".files");
var btn = $(".btn span");
$("#fileupload").wrap("<form id='myupload' action='uploadImg.php' method='post' enctype='multipart/form-data'></form>");
    $("#fileupload").change(function(){
$("#myupload").ajaxSubmit({
dataType:  'json',
beforeSend: function() {
        		showimg.empty();
progress.show();
        		var percentVal = '0%';
        		bar.width(percentVal);
        		percent.html(percentVal);
btn.html("上传中...");
    		},
    		uploadProgress: function(event, position, total, percentComplete) {
        		var percentVal = percentComplete + '%';
        		bar.width(percentVal);
        		percent.html(percentVal);
    		},
success: function(data) {
files.html("<b>"+data.name+"("+data.size+"k)</b> <span class='delimg' rel='"+data.pic+"'>删除</span>");
var img = "../images/"+data.pic;
$("#logo").val(img);
showimg.html("<img src='"+img+"'>");
btn.html("添加附件");
},
error:function(xhr){
btn.html("上传失败");
bar.width('0')
files.html(xhr.responseText);
}
});
});

$(".delimg").live('click',function(){
var insurer_id = 0;
/* 	if(<?=$blog['insurer_id']?>){
insurer_id=<?=$blog['insurer_id']?>;
} */
var pic = $(this).attr("rel");
console.info("pic="+pic);
$.post("uploadImg.php?act=delimg",{imagename:pic,insurer_id:'<?=$blog['insurer_id']?>'},function(msg){
if(msg==1){
files.html("删除成功.");
$("#logo").val("");
showimg.empty();
progress.hide();
}else{
alert(msg);
}
});
});
});
</script>
 
 <!--id="main-content"-->
 
 <!-- <div class="footer">
  <div class="center">
  <dl>
    <dt>新手指南</dt>
<dd><a href="">使用说明</a></dd>
<dd><a href="">投保演示</a></dd>
<dd><a href="">红包使用说明</a></dd>
<dd><a href="">产品问题大全</a></dd>
  </dl>
  <dl>
    <dt>新手指南</dt>
<dd><a href="">使用说明</a></dd>
<dd><a href="">投保演示</a></dd>
<dd><a href="">红包使用说明</a></dd>
<dd><a href="">产品问题大全</a></dd>
  </dl>
  <dl>
    <dt>新手指南</dt>
<dd><a href="">使用说明</a></dd>
<dd><a href="">投保演示</a></dd>
<dd><a href="">红包使用说明</a></dd>
<dd><a href="">产品问题大全</a></dd>
  </dl>
  <dl>
    <dt>新手指南</dt>
<dd><a href="">使用说明</a></dd>
<dd><a href="">投保演示</a></dd>
<dd><a href="">红包使用说明</a></dd>
<dd><a href="">产品问题大全</a></dd>
  </dl>
  <dl>
    <dt>新手指南</dt>
<dd><a href="">使用说明</a></dd>
<dd><a href="">投保演示</a></dd>
<dd><a href="">红包使用说明</a></dd>
<dd><a href="">产品问题大全</a></dd>
  </dl>
  <dl>
    <dt>微信公共平台</dt>
<dd><img src="image/ad02.jpg" width="80px" height="80px"></dd>
  </dl>
  <div class="clearfl"></div>
  <address>保险业务经营许可证：201974000000800 中天信合保险代理（北京）有限公司：110104011479456 京ICP备12031115号-3<br>
 Copyright@2006-2014 <?=$shop_name?>版权所有</address>
  </div>
</div>



</div>  mainarea

</body>
</html> --><?php ob_out();?>