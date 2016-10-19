<?php if(!defined('IN_UCHOME')) exit('Access Denied');?><?php subtplcheck('template//cp_admin_insurance_product_attribute|template//header|template//space_admin_sidebar|template//footer', '1418885078', 'template//cp_admin_insurance_product_attribute');?><!DOCTYPE html>

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


<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <script type="text/javascript" charset="utf-8" src="ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="ueditor/lang/zh-cn/zh-cn.js"></script>
<link href="style/tabs.css" rel="stylesheet" type="text/css" />

</head>




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


<div id="content-body">
   <div id="toolbar">

</div>
   <div id="error">
</div>
   <div id="navigator">

    <div class="content">
        当前位置：
        
            
             <a href="space.php?do=admin_insurance_product_attribute">产品属性</a>
 <?php if(!$blog['attribute_id']) { ?>
            <label class="separator">&gt;</label>     
                新建
 <?php } else { ?>
  <label class="separator">&gt;</label> 
  修改
   <label class="separator">&gt;</label> 
   <a href="space.php?do=admin_insurance_product_attribute&attribute_id=<?=$blog['attribute_id']?>"><?=$blog['attribute_name']?></a>
 <?php } ?> 	
            
      </div>

</div>
                                <div id="main-body">

<script type="text/javascript">

    function onDeleteAttachment(parentId, id, uploadFileId){
        deleteAttachment('/uploadFileAction.do?method=delete&time=1400741141051', parentId, id, uploadFileId);
    }

</script>



<form name="insuranceKindForm" method="POST" action="cp.php?ac=admin_insurance_product_attribute&attribute_id=<?=$blog['attribute_id']?>" method="post">


<input type="hidden" name="blogsubmit" id="blogsubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo formhash(); ?>" />
 
<input name="attribute_id" value="<?=$attribute_id?>" type="hidden">

   
<table class="editable-table">
    <tbody>

<tr>
        <td colspan="4" class="editable-header">基本信息</td>
    </tr>
   
    <tr>
        <td class="requiredField" width="80">保险公司：</td>
        <td class="tdEditArea">


<select id="insurer_id" name="insurer_id" onchange="change_insurer_type(this.value)">
<option value="0">&nbsp;</option>

<?php if(is_array($list_company)) { foreach($list_company as $key => $value) { ?>
<option value="<?=$value['insurer_id']?>" <?=$company_arr_selected[$value['insurer_id']]?>><?=$value['insurer_name']?></option>
<?php } } ?>

</select>

            
        </td>

<td class="requiredField">险种类别：</td>
        <td class="tdEditArea">


<select id="ins_type_id" name="ins_type_id">
<option value="0">&nbsp;</option>

<?php if(is_array($list_type)) { foreach($list_type as $key => $value) { ?>
<option value="<?=$value['id']?>" <?=$ins_type_arr_selected[$value['id']]?>><?=$value['name']?></option>
<?php } } ?>

</select>
         </td>
    </tr>

<tr>
    <td class="requiredField">产品类型：</td>
        <td class="tdEditArea">
    <select name="attribute_type" id="attribute_type">
   
</select>	
</td>

<td class="requiredField">险种编号：</td>
        <td class="tdEditArea">
<input name="attribute_code" id="attribute_code" value="<?=$attribute_code?>" type="text" readonly="readonly">
</td>

</tr>

<tr>	

    <td class="requiredField">接口属性：</td>
        <td class="tdEditArea">
    <select name="interface_flag">
   <option value="1" <?=$interface_flag_select['1']?>>已对接</option>
               <option value="2" <?=$interface_flag_select['2']?>>未对接</option>
</select>
</td>

    <td class="requiredField">允许售卖：</td>
        <td class="tdEditArea">
    <select name="allow_sell">
   <option value="0" <?=$allow_sell_select['1']?>>允许</option>
               <option value="1" <?=$allow_sell_select['2']?>>禁止</option>
</select>
</td>

</tr>


    <tr>
<td class="requiredField">投保人类型：</td>
        <td class="tdEditArea">
    <select name="business_type">
   <option value="individual" <?=$business_type_selected['individual']?>>个人</option>
   <option value="group" <?=$business_type_selected['group']?>>团体</option>
               <option value="all" <?=$business_type_selected['all']?>>团体或个人</option>
   </select>	
</td>

        <td class="tdTitle">产品名称：</td>
        <td class="tdEditArea">
<input name="attribute_name" value="<?=$blog['attribute_name']?>" type="text">
</td>
     
    </tr>

<tr>

        <td class="requiredField">个人服务费率：</td>
        <td class="tdEditArea">
<input name="rate_myself" value="<?=$blog['rate_myself']?>" type="text">
<span>(%)</span>
</td>


<td class="requiredField">机构服务费率：</td>
        <td class="tdEditArea">
<input name="rate_organization" value="<?=$blog['rate_organization']?>" type="text">
<span>(%)</span>
</td>


    </tr>

<tr>


        <td class="requiredField">推荐服务费率：</td>
        <td class="tdEditArea">
<input name="rate_recommend" value="<?=$blog['rate_recommend']?>" type="text">
<span>(%)</span>
</td>

<!-- yes123 2014-11-11 添加适用范围字段 -->
<td class="tdTitle">适用范围：</td>
        <td class="tdEditArea">
<input name="apply_scope" value="<?=$blog['apply_scope']?>" type="text">
</td>	

    </tr>

<tr>
<td class="requiredField">合作伙伴代码：</td>
        <td class="tdEditArea">
<input name="partner_code" value="<?=$blog['partner_code']?>" type="text">
</td>

      	<td class="requiredField">生效延迟期：</td>
        <td class="tdEditArea">
<input name="start_day" value="<?=$blog['start_day']?>" type="text">
<span>（天）</span>
</td>

    </tr>

<tr>
<td class="requiredField">共保</td>
        <td class="tdEditArea">
<input type="checkbox" name="electronic_policy" value="1" <?=$electronic_policy_checked?>> 
</td>
 </tr>
    

<tr>
        <td class="tdTitle">显示图标：</td>
        <td class="attachment" colspan="3">
            <a href="javascript: void(0)" onclick="javascript: upload('/uploadFileAction.do?method=prepare&amp;action=upload&amp;ownerType=1', 'attachmentList')">添加附件</a>
        </td>
    </tr>


    <tr>
        <td></td>
        <td class="tdEditArea" colspan="3">
            <div id="attachmentList" class="attachmentWrapper"></div>
        </td>
    </tr>
    
<?php if(0) { ?>
<tr>
        <td class="tdTitle">显示序号：</td>
        <td class="tdEditArea" colspan="3">
<input name="showOrderString" value="19" class="long" type="text"></td>
    </tr>
<?php } ?>

</tbody></table>
<section class="radiotabs">
<input type="radio" name="tab" id="tab1" class="tabs" checked="checked"><label for="tab1">产品特色</label>
<input type="radio" name="tab" id="tab2" class="tabs"><label for="tab2"> 产品简介 </label> 
<input type="radio" name="tab" id="tab3" class="tabs"> <label for="tab3"> 特别约定</label>
<input type="radio" name="tab" id="tab4" class="tabs"> <label for="tab4"> 投保须知 </label> 
<input type="radio" name="tab" id="tab5" class="tabs"> <label for="tab5"> 理赔指南 </label>
<input type="radio" name="tab" id="tab6" class="tabs"> <label for="tab6"> 保险条款 </label>
<input type="radio" name="tab" id="tab7" class="tabs"> <label for="tab7"> 投保声明 </label>
<input type="radio" name="tab" id="tab8" class="tabs"> <label for="tab8"> 常见问题</label> 


<section id="view1" class="tabcontent">
    <script id="editor_product_characteristic" type="text/plain" name="product_characteristic" style="width:1024px;height:500px;">
<?=$blog['product_characteristic']?>
</script>
</section>

<section id="view2" class="tabcontent">
<script id="editor_description" type="text/plain" name="description" style="width:1024px;height:500px;">
<?=$blog['description']?>
</script>
</section>
<section id="view3" class="tabcontent">		
<script id="editor_limit_note" type="text/plain" name="limit_note" style="width:1024px;height:500px;">
<?=$blog['limit_note']?>
</script>
</section>
<section id="view4" class="tabcontent">		
<script id="editor_cover_note" type="text/plain" name="cover_note" style="width:1024px;height:500px;">
<?=$blog['cover_note']?>
</script>
</section>
<section id="view5" class="tabcontent">		
<script id="editor_claims_guide" type="text/plain" name="claims_guide" style="width:1024px;height:500px;">
<?=$blog['claims_guide']?>
</script>
</section>
<section id="view6" class="tabcontent">		
<script id="editor_insurance_clauses" type="text/plain" name="insurance_clauses" style="width:1024px;height:200px;">
<?=$blog['insurance_clauses']?>
</script>
</section>
<section id="view7" class="tabcontent">	
<script id="editor_insurance_declare" type="text/plain" name="insurance_declare" style="width:1024px;height:200px;">
<?=$blog['insurance_declare']?>
</script>
</section>
<!-- add yes123 2014-12-15 常见问题 -->
<section id="view8" class="tabcontent">	
<script id="editor_insurance_faq" type="text/plain" name="insurance_faq" style="width:1024px;height:500px;">
<?=$blog['insurance_faq']?>
</script>
</section>
</section>


    
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
    </tbody>
</table>
    
</form>
<script type="text/javascript" language="JavaScript">
  <!--
  var focusControl = document.forms["insuranceKindForm"].elements["insurerName"];

  if (focusControl.type != "hidden" && !focusControl.disabled) {
     focusControl.focus();
  }
  // -->
</script>
<script type="text/javascript">
window.onload = function(){
var cpid = document.getElementById('insurer_id').value;
if(cpid!=0&&cpid!=""){
change_insurer_type(cpid);
}
};
    //实例化编辑器
var ue = UE.getEditor('editor_product_characteristic');
    var ue = UE.getEditor('editor_description');
    var ue = UE.getEditor('editor_limit_note');
    var ue = UE.getEditor('editor_cover_note');
    var ue = UE.getEditor('editor_claims_guide');
    var ue = UE.getEditor('editor_insurance_clauses');
    var ue = UE.getEditor('editor_insurance_declare');
    var ue = UE.getEditor('editor_insurance_faq');
var st_list = []; 
<?php if(is_array($all_list_company_insure_type)) { foreach($all_list_company_insure_type as $key => $value) { ?>
var st_first = {'pid':<?=$key?>,'list':[]};
 
<?php if(is_array($value)) { foreach($value as $keys => $values) { ?>
 st_first['list'].push({'name':'<?=$values['attribute_type']?>'}); 
<?php } } ?>
st_list.push(st_first);
<?php } } ?>
 
//var st_list = [{'pid':4,'name':'保险公司','list':[{'id':1,'name':'product'},{'id':2,'name':'mproduct'},{'id':3,'name':'plan'},{'id':4,'name':'Y502'}]},{'pid':5,'name':'保险公司2','list':[{'id':1,'name':'product'},{'id':2,'name':'mproduct'},{'id':3,'name':'plan'},{'id':4,'name':'Y502'}]},{'pid':6,'name':'保险公司3','list':[{'id':1,'name':'product'},{'id':2,'name':'mproduct'},{'id':3,'name':'plan'},{'id':4,'name':'Y502'}]}];

function change_insurer_type(cpid){
var st_html = '';
for(var i=0;i<st_list.length;i++){
if(st_list[i].pid==cpid){
for(var j=0;j<st_list[i].list.length;j++){
if(st_list[i].list[j].name=="<?=$product_attribute['attribute_type']?>"){
st_html += '<option value="'+st_list[i].list[j].name+'" selected="selected">'+st_list[i].list[j].name+'</option>'; 
}else{
st_html += '<option value="'+st_list[i].list[j].name+'">'+st_list[i].list[j].name+'</option>'; 
}

} 
}
} 
document.getElementById('attribute_type').innerHTML = st_html;
 
}


</script>
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