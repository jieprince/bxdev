<?php if(!defined('IN_UCHOME')) exit('Access Denied');?><?php subtplcheck('template//space_products_list|template//header|template//footer', '1416535988', 'template//space_products_list');?><!DOCTYPE html>

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






<div id="navigation" class="center">	

<div class="ft_l">
    <h2><img src="image/menu.png"></h2>
    <ul class="tit">
      <li class="mod_cate">
        <h4><a href="#" target="_blank">汽车保险</a></h4>
        <div style="display: none;" class="mod_subcate">
          <div class="mod_subcate_main">
            <dl>
              <dt><a href="#" target="_blank">栏目名称</a></dt>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
            </dl>
          </div>
        </div>
      </li>
      <li class="mod_cate">
        <h4><a href="#" target="_blank">旅游保险</a></h4>
        <div style="display: none;" class="mod_subcate">
          <div class="mod_subcate_main">
            <dl>
              <dt><a href="#" target="_blank">栏目名称</a></dt>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
            </dl>
          </div>
        </div>
      </li>
      <li class="mod_cate">
        <h4><a href="#" target="_blank">意外保险</a></h4>
        <div style="display: none;" class="mod_subcate">
          <div class="mod_subcate_main">
            <dl>
              <dt><a href="#" target="_blank">栏目名称</a></dt>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
            </dl>
          </div>
        </div>
      </li>
      <li class="mod_cate">
        <h4><a href="#" target="_blank">健康保险</a></h4>
        <div style="display: none;" class="mod_subcate">
          <div class="mod_subcate_main">
            <dl>
              <dt><a href="#" target="_blank">栏目名称</a></dt>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
            </dl>
          </div>
        </div>
      </li>
      <li class="mod_cate">
        <h4><a href="#" target="_blank">财产保险</a></h4>
        <div style="display: none;" class="mod_subcate">
          <div class="mod_subcate_main">
            <dl>
              <dt><a href="#" target="_blank">栏目名称</a></dt>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
            </dl>
          </div>
        </div>
      </li>
      <li class="mod_cate">
        <h4><a href="#" target="_blank">少儿女性</a></h4>
        <div class="mod_subcate">
          <div class="mod_subcate_main">
            <dl>
              <dt><a href="#" target="_blank">栏目名称</a></dt>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
              <dd><a class="org" href="#" title="">二级分类</a></dd>
            </dl>
          </div>
        </div>
      </li>
    </ul>



<div class="r_box" style="margin-top:1opx;">
        <h3><img src="image/menu01.jpg" height="20" width="145"></h3>
        <p><a href="#"><img src="#" height="66px" width="186px"></a></p>
        <ul>
          <li><a href="#" title="成亚芳湖北武汉平安保险">成亚芳湖北武汉平安保险</a></li>
          <li><a href="#" title="成亚芳湖北武汉平安保险">成亚芳湖北武汉平安保险</a></li>
          <li><a href="#" title="成亚芳湖北武汉平安保险">成亚芳湖北武汉平安保险</a></li>
          <li><a href="#" title="成亚芳湖北武汉平安保险">成亚芳湖北武汉平安保险</a></li>
          <li><a href="#" title="成亚芳湖北武汉平安保险">成亚芳湖北武汉平安保险</a></li>
          <li><a href="#" title="成亚芳湖北武汉平安保险">成亚芳湖北武汉平安保险</a></li>
          <li><a href="#" title="成亚芳湖北武汉平安保险">成亚芳湖北武汉平安保险</a></li>
          <li><a href="#" title="成亚芳湖北武汉平安保险">成亚芳湖北武汉平安保险</a></li>
          <li><a href="#" title="成亚芳湖北武汉平安保险">成亚芳湖北武汉平安保险</a></li>
          <li><a href="#" title="成亚芳湖北武汉平安保险">成亚芳湖北武汉平安保险</a></li>
        </ul>
      </div>
  <!--r_box-->


  </div>
  
  <!--ft_l-->
  
  
  
  
  	<div class="ft_r">
<div class="">
<div class="m" id="sift">
<div class="title"> <strong>您已选择：</strong>
                            
                                                            <a id="category_selected" href="javascript:">
                                    <span>意外保险</span>
                                    <s id="category_selected_cancle"></s>
                                </a>
                            
                            
</div>
<div class="o-item">
<div id="company_select" class="item fore">
<div class="tit">保险公司：</div>

<div class="fr">
<ul>

 <?php if(is_array($list_insurer)) { foreach($list_insurer as $key => $value) { ?>
   <li>
                                       <a href="/list_13000_3___1_1_.html" id="Company_13000"><?=$value['abbr_name']?></a>
                                       </li>
 <?php } } ?>
                                          
                                             
                                        									
                                        </ul>
</div>
</div>
<div style="display: none;" id="category_select" class="item">
<div class="tit">分类：</div>
<div class="fr">
<ul>
                                                                                                                            <li>
                                                <a href="/list__2___1_1_.html" id="Category_2">少儿女性</a>
                                            </li>
                                                                                    <li>
                                                <a href="/list__3___1_1_.html" id="Category_3">意外保险</a>
                                            </li>
                                                                                    <li>
                                                <a href="/list__4___1_1_.html" id="Category_4">健康医疗</a>
                                            </li>
                                                                                    <li>
                                                <a href="/list__5___1_1_.html" id="Category_5">旅游保险</a>
                                            </li>
                                                                                    <li>
                                                <a href="/list__6___1_1_.html" id="Category_6">财产保险</a>
                                            </li>
                                        									</ul>
</div>
</div>
<div id="ageGroup_select" class="item">
<div class="tit">年龄：</div>
<div class="fr">
<ul>
<li>
<a href="/list__3_0_17_1_1_.html" id="AgeGroup_0|17">0-17周岁</a>
</li>
<li>
<a href="/list__3_18_45_1_1_.html" id="AgeGroup_18|45">18-45周岁</a>
</li>
<li>
<a href="/list__3_45_55_1_1_.html" id="AgeGroup_45|55">45-55周岁</a>
</li>
<li>
<a href="/list__3_56_70_1_1_.html" id="AgeGroup_56|70">56-70周岁</a>
</li>
<li>
<a href="/list__3_70__1_1_.html" id="AgeGroup_70|">70周岁以上</a>
</li>
</ul>
</div>
</div>
</div>
</div>
                    					<div class="m" id="list">
                       						<div class="mt">
<div class="sift">
险种排序：
<a id="1-1" href="javascript:" class="curr">
价格
<s></s>
</a>
<a id="2-2" href="javascript:" class="">
销量
<s></s>
</a>
</div>
<div class="extra">
<strong class="gre"> 意外保险 </strong>
共找到
<strong class="org">8</strong>
件符合条件的产品
</div>
</div>
<div class="mc">
<div class="o-item">
                                	
                                 <?php if(is_array($list_products_attribute)) { foreach($list_products_attribute as $key => $value) { ?>
                                 							
                                	<div class="item">
<div class="p-price">
<span>
价格：￥<strong><?=$value['premium']?></strong>元
</span>
<a href="<?=$theurl?>&attribute_id=<?=$value['attribute_id']?>" rel="nofollow" class="btn-detail">查看详情</a>
</div>
<div class="p-name">
<a href="<?=$theurl?>&attribute_id=<?=$value['attribute_id']?>"><?=$value['attribute_name']?></a>
</div>
<div class="p-limit">
<strong>承保年龄：</strong>
<span><?=$value['age_min']?>周岁-<?=$value['age_max']?>周岁</span>
<strong>保险期间：</strong>
<span><?=$value['period']?>个月</span>
</div>
<div class="p-info">
                                                                                            </ul>
                                   </div>
                                   
                                    <?php if($value['description']) { ?>
<div class="p-spec">
<span class="org">简介：</span>
                                      <?=$value['description']?>
</div>
  <?php } ?>

</div> <!-- item -->

                               <?php } } ?>	
                              
                               </div> <!-- o-item -->
                                							
<div class="clearfix">

     <div class="page">
    <?=$multi?>
 </div>
 
</div>

</div>
                       					</div>
</div>


</div> 

<!-- end ft_r--> 
  
  </div>
  <!--navigation-->


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
 Copyright@2006-2014 e保险网版权所有</address>
  </div>
</div>



</div>  mainarea

</body>
</html> --><?php ob_out();?>