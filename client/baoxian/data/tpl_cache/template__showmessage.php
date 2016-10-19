<?php if(!defined('IN_UCHOME')) exit('Access Denied');?><?php subtplcheck('template//showmessage|template//header_cshop|template//footer_cshop', '1419778198', 'template//showmessage');?><?php $_TPL['nosidebar']=1; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="" />
<meta name="Description" content="<?=$product['product_name']?>" />
<title><?=$product['product_name']?>_保险平台</title>
<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" href="animated_favicon.gif" type="../image/gif" />
<!--
修改时间2014/7/18
修改者：鲍洪?
内容：加载CSS、js文件
-->
<link type="text/css" rel="stylesheet" href="../static/css/reset.css">
<link type="text/css" rel="stylesheet" href="../static/css/default.css">
<link type="text/css" rel="stylesheet" href="../static/css/bxxq.css">
<link type="text/css" rel="stylesheet" href="../static/css/global.css">
<link href="../static/css/ebaoins.css" rel="stylesheet" type="text/css" />
<script src="../static/js/jquery-1.5.2.min.js"></script>
<script src="../static/js/global.js"></script>
<link href="../themes/inserance/style_coffee.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/common.js"></script>

<script src="js/jquery.min.js"></script>
<link href="js/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-ui.min.js"></script>
<style type="text/css">


@import url(template/default/products.css);
@import url(template/default/insurance_product.css);
@import url(template/default/pingan/newflat3.css);

@import url(template/default/huatai/myreset.css);
@import url(template/default/huatai/public.css);


<?php if($_TPL['css'] == 'cp_product_buy_pingan') { ?>

@import url(template/default/pingan/base.css);
@import url(template/default/pingan/global.css);
@import url(template/default/pingan/public.css);
@import url(template/default/pingan/buy_pro_det.css);
@import url(template/default/pingan/simple_header.css);
@import url(template/default/pingan/newflat.css);

@import url(template/default/pingan/common.css);
<?php } elseif($_TPL['css'] == 'cp_product_buy_taipingyang') { ?>
@import url(template/default/taipingyang/base.css);
@import url(template/default/taipingyang/global.css);
@import url(template/default/taipingyang/public.css);
@import url(template/default/taipingyang/buy_pro_det.css);
@import url(template/default/taipingyang/simple_header.css);
@import url(template/default/taipingyang/newflat.css);
@import url(template/default/pingan/common.css);
<?php } elseif($_TPL['css'] == 'cp_product_buy_huatai') { ?>
@import url(template/default/huatai/myreset.css);
@import url(template/default/huatai/public.css);


<?php } ?>
</style>

<script src="js/script_pub.js" language="javascript" type="text/javascript"></script>
<script src="js/script_common_check.js" language="javascript" type="text/javascript"></script>
<script src="js/script_function_common.js" language="javascript" type="text/javascript"></script>

<?php if($_TPL['css'] == 'cp_product_buy_pingan') { ?>

<script src="js/pingan/script_pingan_check_function.js" language="javascript" type="text/javascript"></script>
<script src="js/pingan/script_input_check_pingan.js" language="javascript" type="text/javascript"></script>
<script src="js/script_bat_import.js" language="javascript" type="text/javascript"></script>
<?php } elseif($_TPL['css'] == 'cp_product_buy_huaan') { ?>
 <script src="js/huaan/script_huaan_check_function.js" language="javascript" type="text/javascript"></script>
<script src="js/huaan/script_input_check_huaan.js" language="javascript" type="text/javascript"></script>
<?php } elseif($_TPL['css'] == 'cp_product_buy_huatai') { ?>

<script src="js/huatai/script_huatai_WdatePicker.js" language="javascript" type="text/javascript"></script>
<script src="js/huatai/script_huatai_ajax.js" language="javascript" type="text/javascript"></script>
<script src="js/huatai/script_huatai_neworderAdd.js" language="javascript" type="text/javascript"></script>

<script src="js/huatai/script_input_check_huaitai.js" language="javascript" type="text/javascript"></script>


<?php } elseif($_TPL['css'] == 'cp_product_buy_taipingyang') { ?>
<!--

<script src="../js/jquery-1.9.1.js"></script>
<script src="../js/jQuery-File-Upload-9.8.0/js/vendor/jquery.ui.widget.js"></script>
<script src="../js/jQuery-File-Upload-9.8.0/js/jquery.iframe-transport.js"></script>
<script src="../js/jQuery-File-Upload-9.8.0/js/jquery.fileupload.js"></script>
-->
<script src="js/taipingyang/script_common_taipingyang.js" language="javascript" type="text/javascript"></script>
<script src="js/taipingyang/script_input_check_taipingyang.js" language="javascript" type="text/javascript"></script>
<script src="js/script_bat_import.js" language="javascript" type="text/javascript"></script>
<?php } ?>




<script src="/js/jquery.min.js"></script>
<script src="/js/mark.js"></script>
<script src="/js/roll.js"></script>
<script type="text/javascript" src="/js/lanrenzhijia.js"></script>
<script type="text/javascript">

/*function <?=$id?>(element) {
  return document.getElementById(element);
}*/
//切屏--是按钮，_v是内容平台，_h是内容库
function reg(str){
  var bt=<?=$id?>(str+"_b").getElementsByTagName("h2");
  for(var i=0;i<bt.length;i++){
    bt[i].subj=str;
    bt[i].pai=i;
    bt[i].style.cursor="pointer";
    bt[i].onclick=function(){
      <?=$id?>(this.subj+"_v").innerHTML=<?=$id?>(this.subj+"_h").getElementsByTagName("blockquote")[this.pai].innerHTML;
      for(var j=0;j<<?=$id?>(this.subj+"_b").getElementsByTagName("h2").length;j++){
        var _bt=<?=$id?>(this.subj+"_b").getElementsByTagName("h2")[j];
        var ison=j==this.pai;
        _bt.className=(ison?"":"h2bg");
      }
    }
  }
  <?=$id?>(str+"_h").className="none";
  <?=$id?>(str+"_v").innerHTML=<?=$id?>(str+"_h").getElementsByTagName("blockquote")[0].innerHTML;
}
</script>
<!--
修改时间?014/7/21
修改者：鲍洪?
内容：整个页面调?
-->
</head>
<body>
<!--
修改时间?014/7/16
修改者：鲍洪?
内容：首页头?
-->
<div class="container">
<div id="container">
<!--头部开始-->
<div class="header">
   	  <div class="header_02">
        	<div class="logo"><a href="index.php"><img src="../themes/inserance_02/images/logo.png" width="376" height="80" /></a></div>
<div class="header_01">
        		<div class="info">
            		<ul>
                		<li>咨询热线 <span>4006-900-618</span></li>
                		
                    		<!-- <?php if($smarty['session'].user_id) { ?>-->
                            <li>
                    			  <a href="../user.php">
                    			 <?php if($_SESSION['real_name']) { ?> 
                    			  	<?=$_SESSION['real_name']?>
                    			<?php } else { ?>
                    			  	<?=$_SESSION['user_name']?>
                    			 <?php } ?>
                    			  </a> 
                            </li>
                            <li>
                    			<a href="../user.php?act=logout">&nbsp;注销</a>
                            </li>
                    			 <!-- <?php } else { ?> -->
                                 <li>
                    			<a href="">登录</a>
                                </li>
                                <li>
                    			<a href="../user.php?act=register">注册</a>
                                </li>
                    		<!-- <?php } ?>-->
                    	
                    	<li><img src="image/a2.png" width="18" height="18" align="absmiddle" /><a href="../user.php?act=order_list">我的订单</a>&nbsp;&nbsp;</li>
                    	<!-- <?php if($smarty['session'].user_id) { ?>-->
                    	<li><img src="image/a3.png" width="18" height="18" align="absmiddle" /><a href="../user.php">用户中心</a></li>
                    	<!-- <?php } ?>-->
                	</ul>
            	</div>
<div class="headerNav"><!--导航开始 -->
<ul class="title-list">
<li class="on"><a href="../index.php">首页</a></li>
<li><a href="../category.php?act=hot_goods_list">热销产品</a></li>
<li><a href="../category.php?act=special">e保专题 </a></li>
<li><a href="../category.php?act=introduce">平台介绍</a></li>
<li><a href="../category.php?act=about">关于我们</a></li>
<p><b></b></p>
</ul>
            	</div><!--导航结束-->
            	<div class="clear"></div>
            </div>
        </div>
    </div>
</div><!--头部结束-->
</div>

 


<div class="clear"></div>
<style>
.message_bg{background:#f5f5f5;padding:10px 0px;}
.message_wid{width:1180px; margin:0 auto; background:#FFF; }
.message_title{font-size:20px; height:30px; text-align:center}
.padd_TB20{padding:20px 0px;}
.message_content_bg{ background:url(template/default/images/message_bg.png) no-repeat center; width:450px; height:250PX; margin-top:30px;}
.message_content{ font-size:14px; color:red; text-align:left; line-height:25px; width:180px; margin-left:215px; padding-top:80px; }
.message_line25{ line-height:25px;}
.message_btn_top{ text-align:right; margin-top:70px; margin-right:70px;}
.message_homebtn{ margin-left:100px; display:inline-block; width:150px; height:45px; line-height:45px; background:#cecece;color:#8b8b8b;border-radius:3px; text-decoration:none; font-size:16px;}
 
.message_homebtn:hover{background:#bbbbbb; color:#8b8b8b; text-decoration:none;font-size:16px;height:45px; line-height:45px;}

.message_prebtn{ display:inline-block; width:150px; height:45px; line-height:45px; background:#ff832d;color:#fff;font-size:14px;border-radius:3px; text-decoration:none; font-size:16px;}
.message_prebtn:hover{background:#ff700d; color:#FFF; text-decoration:none;height:45px; line-height:45px; }
</style>
<div class="showmessage message_bg">
<div class="message_wid">
    	 
    	<div class="padd_TB20">
        	 
            <div class="message_content_bg">
            	<!--<div class="message_title">信息提示</div>-->
            	<div class="message_content"><?=$message?></div>
                
            </div>
            <div align="center" style="margin-top:50px;">
            <?php if($url_forward) { ?>
                <a href="<?=$url_forward?>">页面跳转中...</a>
            <?php } else { ?>
                <a href="javascript:history.go(-1);" class="message_prebtn ">返回上一页</a>
                <a href="../index.php" class="message_homebtn ">返回首页</a>
            <?php } ?>
            </div>
        </div>
</div>
</div>
<div class="clear"></div>
<!--
修改时间：2014/7/16
修改者：鲍洪州
内容：首页底部
-->
<div class="clear"></div>
<div class="foot02">
<!--<div class="footer">
    	<dl>
        	<dt>新手指南</dt>
            <dd><a href="#">投保演示</a></dd>
            <dd><a href="#">使用说明</a></dd>
            <dd><a href="#">红包使用说明</a></dd>
            <dd><a href="#">产品问题大全</a></dd>
        </dl>
        <dl>
        	<dt>新手指南</dt>
            <dd><a href="#">投保演示</a></dd>
            <dd><a href="#">使用说明</a></dd>
            <dd><a href="#">红包使用说明</a></dd>
            <dd><a href="#">产品问题大全</a></dd>
        </dl>
        <dl>
        	<dt>新手指南</dt>
            <dd><a href="#">投保演示</a></dd>
            <dd><a href="#">使用说明</a></dd>
            <dd><a href="#">红包使用说明</a></dd>
            <dd><a href="#">产品问题大全</a></dd>
        </dl>
        <dl>
        	<dt>新手指南</dt>
            <dd><a href="#">投保演示</a></dd>
            <dd><a href="#">使用说明</a></dd>
            <dd><a href="#">红包使用说明</a></dd>
            <dd><a href="#">产品问题大全</a></dd>
        </dl>
        <dl>
        	<dt>新手指南</dt>
            <dd><a href="#">投保演示</a></dd>
            <dd><a href="#">使用说明</a></dd>
            <dd><a href="#">红包使用说明</a></dd>
            <dd><a href="#">产品问题大全</a></dd>
        </dl>
        <dl>
        	<dt>微信公众平台</dt>
        	
            <dd style="margin-top: 5px;">
            <img src="baoxian/image/ad02.jpg" width="80px" height="80px">
            </dd>
        </dl>
    </div>-->
<div class="footer1">
<ul class="insur02">
<li><a href="article.php?id=43">法律安全</a>&nbsp;|&nbsp;</li>
<li><a href="article.php?id=44">隐私政策</a>&nbsp;|&nbsp;</li>
<li><a href="article.php?id=45">法律声明</a>&nbsp;|&nbsp;</li>
<li><a href="article.php?id=46">关于我们</a></li>
</ul>
</div>
    <div class="clear"></div>
    <div class="footer2">
    	<p class="text">经营保险代理业务许可证：201974000000800  中天信合保险代理（北京）有限公司：110104011479456 ICP备案号：京ICP备12031115号</p>
        <p class="text">Copyright@2006-2014 <?=$shop_name?>版权所有</p>
    </div>
</div>

<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fb099f026faffdeb4cf3297bf7e6d0877' type='text/javascript'%3E%3C/script%3E"));
</script>


</body>

<!--
<script type="text/javascript">
var goods_id = 33;
var goodsattr_style = 1;
var gmt_end_time = 0;
var day = "天";
var hour = "小时";
var minute = "分钟";
var second = "秒";
var end = "结束";
var goodsId = 33;
var now_time = 1407319141;

onload = function(){
  changePrice();
  fixpng();
  try {onload_leftTime();}
  catch (e) {}
}


/**
 * 点选可选属性或改变数量时修改商品价格的函数
 */
function changePrice()
{
  var attr = getSelectedAttributes(document.forms['ECS_FORMBUY']);
  var qty = document.forms['ECS_FORMBUY'].elements['number'].value;
  Ajax.call('goods.php', 'act=price&id=' + goodsId + '&attr=' + attr + '&number=' + qty, changePriceResponse, 'GET', 'JSON');
}
/**
 * 接收返回的信息
 */
function changePriceResponse(res)
{
  if (res.err_msg.length > 0)
  {
alert(res.err_msg);
  }
  else
  {
document.forms['ECS_FORMBUY'].elements['number'].value = res.qty;
if (document.getElementById('ECS_GOODS_AMOUNT'))
  document.getElementById('ECS_GOODS_AMOUNT').innerHTML = res.result;
  }
}

</script>

-->

</html>

<?php ob_out();?>