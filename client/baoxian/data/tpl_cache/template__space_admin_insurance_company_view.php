<?php if(!defined('IN_UCHOME')) exit('Access Denied');?><?php subtplcheck('template//space_admin_insurance_company_view|template//header|template//footer', '1417748564', 'template//space_admin_insurance_company_view');?><!DOCTYPE html>

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


<div id="main-body">

<a href="space.php?do=admin_insurance_company"> 返回类别列表 </a>


    <div id="view">
        <div id="viewData">
            <div id="viewContent">
<div style="float: left; margin-left: 17px; ">
<img alt="" src='<?=$value['logo']?>' >
</div><br/>
<table class="view-table">
    <tbody><tr>
        <td colspan="4" class="view-header">基本信息</td>
    </tr>
    <tr>
        <td class="view-first-title">编码</td>
        <td class="view-first-text"><?=$value['insurer_code']?></td>
        <td class="view-first-title">名称</td>
        <td class="view-top-right-text"><?=$value['insurer_name']?></td>
    </tr>
    <tr>
        <td class="view-title">简称</td>
        <td class="view-text"><?=$value['insurer_abbrname']?></td>
        <td class="view-title">上级</td>
        <td class="view-right-text"><?=$value['parent_name']?></td>
    </tr>
    <tr>
        <td class="view-title">类别</td>
        <td class="view-text">财产保险公司</td>
        <td class="view-title">所在地区</td>
        <td class="view-right-text">北京市</td>
    </tr>
    <tr>
        <td class="view-title">简介</td>
        <td class="view-right-text" colspan="3"><?=$value['brief']?></td>
    </tr>


</tbody>

</table>

<a href="cp.php?ac=admin_insurance_company_insure_type&insurer_id=<?=$insurer_id?>" target="_self">
<img src="template/images/updateButton.gif" alt="增加不同险种代码围">增加不同产品类型</a>


<?php if($list_company_insure_type) { ?>
<form name="insuranceKindForm" method="POST" action="cp.php?ac=admin_insurance_company_insure_type&insurer_id=<?=$insurer_id?>">


<input type="hidden" name="company_insure_order_submit" id="company_insure_order_submit" value="true" />
<input type="hidden" name="formhash" value="<?php echo formhash(); ?>" />

<table class="view-table">
    <tbody>
<tr>
        <td colspan="6" class="view-header">产品类型（每家保险公司产品产品类型不同，例如旅游险，Y502）</td>
    </tr>
    <tr class="listTitle">
        <td class="list-first-title" width="5%">显示顺序</td>
        <td class="list-title" width="10%">保险公司id</td>
 <td class="list-title" width="10%">产品类型</td>
<td class="list-title" width="10%">注释</td>
 <td class="list-title" width="10%">修改</td>
  <td class="list-title" width="10%">删除</td>
    </tr>
    
<?php if(is_array($list_company_insure_type)) { foreach($list_company_insure_type as $key => $valued) { ?>
    
    <tr class="tdContent">

<td class="list-content">	
<input name="view_order_ids[<?=$valued['insurance_company_insure_type_id']?>]" id="view_order_<?=$key?>" value="<?=$valued['view_order']?>" type="text">		
</td>

    <td class="list-content"><?=$valued['insurer_id']?></td>
        <td class="list-first-content">
<input name="attribute_type_ids[<?=$valued['insurance_company_insure_type_id']?>]" id="attribute_type_<?=$key?>" value="<?=$valued['attribute_type']?>" type="text">		
    </td>

  <td class="list-content">
  <input name="note_ids[<?=$valued['insurance_company_insure_type_id']?>]" id="note_<?=$key?>" value="<?=$valued['note']?>" type="text">		
  </td>

      
  <td class="list-content">
  <a href="cp.php?ac=admin_insurance_company_insure_type&insurance_company_insure_type_id=<?=$valued['insurance_company_insure_type_id']?>">修改</a>
  </td>
  
  <td class="list-content">
  <a href="cp.php?ac=admin_insurance_company_insure_type&insurance_company_insure_type_id=<?=$valued['insurance_company_insure_type_id']?>&op=delete">删除</a>
  </td>
  
    </tr>
    
    
    <?php } } ?>
     
    
<tr>
    <td class="buttons">
                <input value="保存" class="webButton" type="submit">
              
            </td>
</tr>   

</tbody>
</table>
</form>

<?php } ?>


                <table class="view-table">
                    <tbody><tr>
                        <td colspan="4" class="view-header">元数据信息</td>
                    </tr>
                    <tr>
                        <td class="view-first-title">状态标志</td>
                        <td class="view-first-text">
                            正常
                            
                            
                        </td>
                        <td class="view-first-title">操作人</td>
                        <td class="view-top-right-text">超级用户</td>
                    </tr>
                    <tr>
                        <td class="view-title">创建时间</td>
                        <td class="view-text">2014-01-22</td>
                        <td class="view-title">更新时间</td>
                        <td class="view-top-right-text">2014-03-24</td>
                    </tr>
                    <tr>
                        <td class="view-title">显示序号</td>
                        <td class="view-text">1001</td>
                        <td class="view-title">备注信息</td>
                        <td class="view-right-text"></td>
                    </tr>
                </tbody></table>
            </div>
        </div>
    </div>

</div>

</div> <!--id="main-content"-->
 
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