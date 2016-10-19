<?php if(!defined('IN_UCHOME')) exit('Access Denied');?><?php subtplcheck('template//cp_product_buy_huatai_chuguo_yiwai|template//header_cshop|template//cp_product_buy_header|template//cp_product_buy_footer|template//footer_cshop', '1418787391', 'template//cp_product_buy_huatai_chuguo_yiwai');?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
                    			 <?php if($_SGLOBAL['real_name']) { ?>
                    			  	<?=$_SGLOBAL['real_name']?>
                    			 <?php } else { ?> 
                    			  	<?=$_SGLOBAL['supe_username']?>
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

 


<div id="simple_wrapper">
<div id="contain">
<div class="insure">
<div class="insure_main">
            <form id="orderForm" name="orderForm" method="POST" action="cp.php?ac=product_buy&product_id=<?=$product['product_id']?>">
            
            <input type="hidden" name="buysubmit" id="buysubmit" value="true" />
                                                
            <input type="hidden" name="formhash" value="<?php echo formhash(); ?>" />
            
           <input type="hidden" name="step" id="step" value="1" />
            
            
           <input type="hidden" name="start_day" id="start_day" value="<?=$product['start_day']?>" />
           
            <input type="hidden" name="server_time" id="server_time" value="<?=$product['server_time']?>" />		
                
                
            <input name="product_id" id="product_id" value="<?=$product['product_id']?>" type="hidden">		
                    
            <input type="hidden" name="businessType" id="businessType" value="<?=$businessType?>" />	
            
            <input id="totalModalPremium_single" name="totalModalPremium_single" value="<?=$product['premium']?>" type="hidden">		
            
            <input id="totalModalPremium" name="totalModalPremium" value="<?=$prodcutMarkPrice?>" type="hidden">
                                                    
            <input id="period" name="period" value="<?=$period_day?>" type="hidden">
            
         <input id="age_min" name="age_min" value="<?=$product['age_min']?>" type="hidden">
                
         <input id="age_max" name="age_max" value="<?=$product['age_max']?>" type="hidden">
          <input id="product_code" name="product_code" value="<?=$product['product_code']?>" type="hidden">		
 
<div class="insure_step" style="padding-top:20px;">
<img src="../images/insure01.jpg" width="387" height="47"><img src="../images/insure02.jpg" width="394" height="47"><img src="../images/insure03.jpg" width="332" height="47"></a>
                </div>
                <div class="insure_title">
                 <a href="../goods.php?id=<?=$gid?>" title="<?=$product_name?>" target="_blank" style="cursor:hand">
<?=$product['attribute_name']?><span style="color:#09c;margin:0;padding:0;"></span></a>
                &nbsp;>&nbsp;
                <a href="../goods.php?ins_product_id=<?=$product['product_id']?>" title="<?=$product_name?>" target="_blank" style="cursor:hand">
<?=$product['product_name']?><span style="color:#09c;margin:0;padding:0;"></span></a>
</div>
<div class="insure_cen">

<div class="ndt_cart_lc ndt_cart_lcT1"></div>


<input id="applyNum" name="applyNum" value="1" type="hidden">	
<input id="period_code" name="period_code" value="<?=$period_code?>" type="hidden">	
 
<input type="hidden" name="metType" value="ordercheck">
<input type="hidden" name="bean.shipType" value="">
<input type="hidden" name="bean.virtualPay" value="">
<input type="hidden" name="curChannelId" value="index">



<div class="x_p_a_c c"><h2 class="l">保险信息</h2></div>


<table cellpadding="0" cellspacing="0" style="width:600px; margin-left:118px;">


<tbody>
 
<tr class="">
<th width="25%" align="right" class="pad_R21">
<span class="a_x">*</span>年龄范围</th>

<td align="left">
<span> <?=$product['age_min']?></span> 至 <span> <?=$product['age_max']?></span>岁

</td>

<th width="25%" align="right" class="pad_R21">
<span class="a_x">*</span>保险期限</th>

<td align="left">
<span><?=$prodcutPeriod?></span>

</td>
</tr>			
<tr class="">
<th width="25%" align="right" class="pad_R21">
<span class="a_x">*</span>保险起期</th>

<td align="left">
<input value="" maxlength="50" name="startDate" id="startDate" title="购保险起期" class="i_f pa_ui_element_normal prod_txt" type="text" readonly="readonly">
<span id="clientNameSpan" style="display:none;"></span>
</td>


<th width="25%" align="right" class="pad_R21">
<span class="a_x">*</span>保险止期</th>

<td align="left">
<input value="" maxlength="50" name="endDate" id="endDate" title="购保险起期" class="i_f pa_ui_element_normal prod_txt" type="text" readonly="readonly">
<span id="clientNameSpan" style="display:none;"></span>
</td>

</tr>


  </tbody>
</table>
  
  
<!-- 投保人、被保人、受益人Div -->


<!-- 投保人信息 -->
<div class="x_p_a_c c"><h2 class="l">投保人信息</h2></div>
 
<input type="hidden" name="appntId" id="appntId" value="">
<input type="hidden" name="ischecked" id="ischecked" value="false">

<!-- 常用投保人table -->

<!-- 投保人录入项table -->
<table cellpadding="0" cellspacing="0" style="width:800px; margin-left:110px;">


<tbody>



<tr>
<td width="96" align="right" class="pad_R21">
<span class="a_x">*</span>投保人姓名</td>
 
<td colspan="3" align="left">
<input name="appName" type="text" class="prod_txt" id="appName" onfocus="javascript:showwarn('appName_warn');" onblur="javascript:checkappName();" value="" maxlength="24">
<strong style="display: none;color:red" id="appName_warn" class="warn_strong">请输入名称</strong>
</td>
</tr>

<tr>
 <td align="right" class="pad_R21">
<span class="a_x">*</span>英文名
</td>
 
<td colspan="3" align="left">
<input name="appEnglishName" type="text" class="prod_txt" id="appEnglishName" onfocus="javascript:showwarn('appEnglishName_warn');" onblur="checkappEnglishName();" value="" maxlength="30">
<strong style="display: none;color:red" id="appEnglishName_warn" class="warn_strong">如：张三，填写拼音ZHANG SAN</strong>
<script>
function clearEnglishName(op){
if($.trim(op.value)==""){
$("#appEnglishName_warn").html("如：张三，填写拼音ZHANG SAN");
}else{
$("#appEnglishName_warn").html(".");
}

}
</script>
</td>
</tr>


<tr>
<td align="right" width="96" class="pad_R21"><span class="a_x">*</span>证件类型</td>
 
<td align="left" width="203">
<select id="certType" name="certType" class="ind_txt2" onchange="javascript:choosetcardtype(this)">

<option value="1">身份证</option>
<option value="3">护照</option>

</select> 
</td>

<td width="103" align="right" class="pad_R21"><span class="a_x">*</span>证件号</td>
 
<td width="460" align="left">
<input type="text" id="certCode" name="certCode" class="prod_txt" maxlength="18" value="" onfocus="javascript:showwarn('tcard_warn');" onchange="javascript:getBirthDay(this,'certType','appBirthday','appSex','appAge','','');" onblur="javascript:checktcard(); getBirthDay(this,'certType','appBirthday','appSex','appAge','','');">

<strong style="display: none;color:red" id="tcard_warn" class="warn_strong"> </strong>
</td>


</tr>



<tr>
 <td align="right" class="pad_R21">
<span class="a_x">*</span>性别
</td>
 
<td colspan="3" align="left">


<label>
<input type="radio" id="checktsex_M" name="appSex" value="M" onclick="clearappSex();">男
</label>

<label>
<input type="radio" id="checktsex_F" name="appSex" value="F" onclick="clearappSex();">女
</label>


<span id="appSex_warn" style="color:red"> </span>
<script>
function clearappSex(){
$("#appSex_warn").html("");
}
</script>
</td>
</tr>


<tr>
<td width="96" align="right" class="pad_R21">
<span class="a_x">*</span>出生日期
</td>
 
<td colspan="3" align="left">

<input name="appBirthday" class="prod_txt" id="appBirthday" onblur="getBirthDay(this,'certType','appBirthday','appSex','appAge','','');" onchange="javascript:checktbirthday();getBirthDay(this,'certType','appBirthday','appSex','appAge','','');" onclick="WdatePicker();" value="" maxlength="15">
<span id="appBirthday_warn" style="color:red">格式为： 2010-01-02</span>

</td>

</tr>


<tr>
<td align="right" class="pad_R21">
<span class="a_x">*</span>手机号
</td>
 
<td colspan="3" align="left">
<input class="prod_txt" type="text" id="appCell" name="appCell" value="" maxlength="11" onfocus="javascript:showwarn('appCell_warn');" onblur="javascript:checktphone();">
<strong style="display: none;color:red" id="appCell_warn" class="warn_strong"> </strong>
</td>
</tr>


<tr>
<td align="right" class="pad_R21">
<span class="a_x">*</span>电子邮箱
</td>
 
<td colspan="3" align="left">
<input name="appEmail" type="text" class="prod_txt" id="appEmail" onfocus="javascript:showwarn('appEmail_warn');" onblur="javascript:checktemail();" value="" maxlength="50">
<strong style="display: none;color:red;" id="appEmail_warn" class="warn_strong"> </strong>
</td>
</tr>



<tr height="50px">
<td width="96" align="right" class="pad_R21">
<span class="a_x">*</span>通讯地址
                                         </td>
 
 <td colspan="3" align="left">
 	<div>
 		<span>
<select class="ind_txt2" name="insureareaprov_code" id="insureareaprov_code">
<option value="">
请选择省份
</option>

<!--
<option value="860100">北京</option>
<option value="860200">上海</option>
<option value="860500">广东</option>
<option value="860600">江苏</option>
<option value="860700">浙江</option>
<option value="860800">山东</option>
<option value="860900">福建</option>
<option value="861200">四川</option>
<option value="861500">湖南</option>
<option value="861600">湖北</option>
<option value="861700">江西</option>
<option value="862100">河南</option>
<option value="862300">内蒙古</option>
<option value="861100">河北</option>
-->
<option value="101">北京</option>
<option value="102">浙江</option>
<option value="103">四川</option>
<option value="104">江苏</option>
<option value="105">上海</option>
<option value="106">山东</option>
<option value="107">河南</option>
<option value="108">福建</option>
<option value="109">湖南</option>
<option value="110">广东</option>
<option value="111">江西</option>
<option value="112">内蒙古</option>
<option value="113">湖北</option>
<option value="114">河北</option>

</select>

<input type="hidden" id="insureareaprov" name="insureareaprov" value="" >
</span>
&nbsp;&nbsp;
<span id="orderareadiv">

<select name="insureareacity_code" id="insureareacity_code" class="ind_txt2" onchange="check_t_region();">												
</select>	

<input type="hidden" id="insureareacity" name="insureareacity" value="" >					
</span>

<span style="color:red" id="insureareaprov_code_warn">

</span>
</div>
</td>

</tr>

<tr>
 <td align="right" class="pad_R21">
                                         	<span class="a_x">*</span>详细地址
                                         </td>
 
<td colspan="3" align="left">
<input name="appAddress" type="text" class="prod_txt" id="appAddress" onblur="checkaddrdetail()" value="" maxlength="100">

<span id="appAddress_warn" style="color:red"></span>
</td>
</tr>




<tr>
<td align="right" class="pad_R21">
                                         	<span class="a_x">*</span>邮政编码
</td>
 
<td colspan="3" align="left">
<input type="text" id="appPostcode" name="appPostcode" value="" class="prod_txt" maxlength="6" onblur="javascript:checktpostcode();">
<span id="appPostcode_warn" style="color:red"></span>
</td>

</tr>


<input type="hidden" name="appAge" value="">


<!-- 常用投保人 -->
 
</tbody></table>


<!--  被保人信息start-->
<input type="hidden" name="insCount">
<script>

function showins2(){
$("#fists").html("<b>第1个</b>");
var sUrl = "/fg/order/insuredTemplate2.jsp?productId=efyi21&insCount=2&date="+new Date();
$("[name=insCount]").val("2");
sprict();
show(sUrl,"insShow2",false);
}
var insCount = 2;
function addIns(){
insCount = insCount + 1;
var sUrl = "/fg/order/insuredTemplate2.jsp?productId=efyi21&insCount="+insCount;
$("[name=insCount]").val(insCount);
sprict();
show(sUrl,"insShow2",true);
}
function deleteins(){
var insdiv = $("#ins"+insCount);
insdiv.html("");
insdiv.attr("id","");
insCount = insCount - 1;
$("[name=insCount]").val(insCount);
sprict();
}
function clearIns(){
$("#insShow2").html("");
$("#insShow3").html("");
$("fists").html("");
insCount = 2;
$("[name=insCount]").val(insCount);
clearSprict();
}
function sprict(){
var yp = 200.00;
var cp = yp * insCount;
$("#showprice").html(cp);
}
function clearSprict(){
$("#showprice").html("200.00");
}
</script>

<div class="x_p_a_c c"><h2 class="l">被保人信息</h2></div>


<div id="fists" style="padding-top:10px;"> 
<span style="color:#CC9900;margin-left:50px;">同一被保险人在同一保险期间不能拥有两张有效的华泰人寿环球无忧出国险保单，投保时候请确认是否发生重复投保的问题。
</span>
</div>

<div id="insShow1" class="c_p_a_d">


<table cellpadding="0" cellspacing="0" style="width:800px; margin-left:110px;">
 
<tbody><tr>
<td align="right" width="96" class="pad_R21">
<span class="a_x">*</span>关系
</td>	
 
<td colspan="3" align="left">
<span style="float:left" id="tb2">
被保人是投保人
<input name="appName2" type="text" class="prod_txt" id="appName2" onclick="checkappName()" value="" maxlength="24" readonly="readonly">
的
</span>

  		
<select style="margin-top:3px" name="relationShipApp" class="ind_txt2" id="guanxi" value="" onchange="javascript:chooserelation(this,'18','75y');">
<option value="">请选择</option>
<!--
<option value="a">本人</option>	
<option value="b">妻子</option>	
<option value="c">丈夫</option>	
<option value="d">父亲</option>	
<option value="e">母亲</option>	
<option value="f" selected>儿子</option>	
<option value="g">女儿</option>	
-->

<?php if($product['product_code']=="60041331"||  $product['product_code']=="60041341") { ?>
<option value="2" selected>子女</option>
<?php } else { ?>
<option value="1">配偶</option>
<option value="2" selected>子女</option>
<option value="3">父母</option>
<option value="5">本人</option>
<?php } ?>

<!--
<option value="4">亲属</option>
<option value="6">其它</option>
<option value="0">无关或不确定</option>	
-->

</select>
<strong style="display: none;color:red" id="tb_warn" name="tx_show_one">请选择被保人关系</strong>
</td>
</tr>
              </tbody>
              <tbody id="tb1">
 
<tr>
<td align="right" width="96" class="pad_R21">
<span class="a_x">*</span>被保人姓名
</td>	
 
<td colspan="3" align="left">
<input name="insName" type="text" class="prod_txt" id="insName" onfocus="javascript:showwarn('insName_warn',this);" onblur="clearinsName(this);" value="" maxlength="24">
<span style="display: none;color:red" id="insName_warn" name="tx_show_one">请填写被保人姓名</span>
</td>
</tr>
<script>
function clearinsName(op){
if($.trim(op.value)==""){
$("#insName_warn").html("请填写被保人姓名");
}else{
$("#insName_warn").html("");
}
}
</script>



<tr>
<td align="right" width="96" class="pad_R21">
<span class="a_x">*</span>英文名
</td>	
 
<td colspan="3" align="left">

<input name="insEnglishName" type="text" class="prod_txt" id="insEnglishName" onblur="clearinsEnglishName(this);" onchange="javascript:checkinsEnglishName();" value="" maxlength="30">

<span id="insEnglishName_warn" name="tx_show_one" style="display:none;color:red">请填写英文名</span>
</td>
</tr>
<script>
function clearinsEnglishName(op){
if($.trim(op.value)==""){
$("#insEnglishName_warn").html("请填写英文名");
}else{
$("#insEnglishName_warn").html("");
}

}
</script>



<tr>
<td align="right" width="96" class="pad_R21">
<span class="a_x">*</span>证件类型
</td>	
 
<td  align="left" width="203">
<span class="span_left"> 
<select id="insCertType" name="insCertType"  value="" class="select_middle ind_txt2" onchange="javascript:choosebcardtype(this);" >

<option value="1">身份证</option>
<option value="2">军官证</option>
<option value="3">护照</option>
<option value="4">驾驶执照</option>
<option value="5">返乡证</option>
<option value="6">其他</option>


</select> 
</span>

</td>
                    <td align="right" width="103" class="pad_R21">
<span class="a_x">*</span>证件号
                    </td>
 
<td width="460">
<input type="text" id="insCertCpde" name="insCertCpde" class="prod_txt" maxlength="18" value="" onfocus="javascript:showwarn('insCertCpde_warn',this);" onchange="javascript:getBirthDay(this,'insCertType','insBirthday','insSex','insAge','<?=$product['age_min']?>y','<?=$product['age_max']?>y');" onblur="javascript:checkbcard();getBirthDay(this,'insCertType','insBirthday','insSex','insAge','<?=$product['age_min']?>y','<?=$product['age_max']?>y');">
<span style="display: none;color:red" id="insCertCpde_warn" name="tx_show_one" class="warn_strong">请填写证件号码</span>
</td>
</tr>





<tr>
<td align="right" width="96" class="pad_R21">
<span class="a_x">*</span>性别
</td>	
 
<td colspan="3" align="left">


<label>
<input type="radio" id="insSex_M" name="insSex" value="M">男
</label>

<label>
<input type="radio" id="insSex_F" name="insSex" value="F">女
</label>


<span id="insSex_warn" style="color:red;display:none;" name="tx_show_one">请选择性别</span>
</td>
</tr>
<tr class="">
                    <td align="right" width="96" class="pad_R21">
                        投保年龄范围
                    </td>
                    <td  colspan="3" align="left" >
                       <span > 	<?=$product['age_min']?></span> 至
                        <span > <?=$product['age_max']?></span>岁
                    </td>

</tr>


<tr>
<td align="right" width="96" class="pad_R21">
<span class="a_x">*</span>出生日期
</td>
 
<td colspan="3" align="left">
<input name="insBirthday" class="prod_txt" id="insBirthday" onchange="checkbbirthday(this.value,('<?=$product['age_min']?>y','<?=$product['age_max']?>y');clearinsBirthday(this);" onclick="WdatePicker();" value="" maxlength="15" style="float:left;">
<span id="insBirthday_warn" style="color:red;display:block;" name="tx_show_one">格式为：2010-01-02</span>


<script>
function clearinsBirthday(op){

if(op.value!=""){
$("#insBirthday_warn").html("");
}
}
</script>


</td>
</tr>


<tr>
<td align="right" width="96" class="pad_R21">
<span class="a_x">*</span>手机号
</td>
 
<td colspan="3" align="left">
<input class="prod_txt" type="text" id="insCell" name="insCell" value="" maxlength="11" onfocus="javascript:showwarn('insCell_warn');" onblur="javascript:checktphone_ins();">
<strong style="display: none;color:red" id="insCell_warn" class="warn_strong"> </strong>
</td>
</tr>


<tr>
<td align="right" width="96" class="pad_R21">
<span class="a_x">*</span>电子邮箱
</td>
 
<td colspan="3" align="left">
<input name="insEmail" type="text" class="prod_txt" id="insEmail" onfocus="javascript:showwarn('insEmail_warn');" onblur="javascript:checktemail_ins();" value="" maxlength="50">
<strong style="display: none;color:red;" id="insEmail_warn" class="warn_strong"> </strong>
</td>
</tr>


<input type="hidden" name="insAge" value="">

</tbody>

</table>	

</div>
<div id="insShow2"></div>
<div id="insShow3"></div>
<!--  被保人信息end-->

<!--  受益人信息-->
<div class="x_p_a_c c">
                  	<h2>身故受益人信息</h2>
                  </div>

<table cellpadding="0" cellspacing="0" style="width:800px; margin-left:88px;">
<tbody><tr>
<td align="right" class="pad_R21" width="96">
                                         	<span class="a_x">*</span>受益人
</td>
 
<td width="19" align="center">
<input checked="checked" id="ben" type="radio" name="fading" value="1" class="no_line">
</td>
<td width="658" align="left">
<span style="margin:0 0 0 3px">法定继承人</span>
</td>
</tr>
</tbody></table>

<!-- 其它信息 -->
 







<div class="x_p_a_c c">
  <h2>其它信息</h2>
</div>
     <table cellpadding="0" cellspacing="0" style="width:800px; margin-left:88px;">
     
<tbody><tr>
<td align="right" class="pad_R21" width="96">
              <span class="a_x">*</span>出行目的
</td>
 
<td align="left">
<input id="Purposehide" type="hidden" name="otherInfoName">
<select id="Purpose" title="出行目的" name="Purpose" class="ind_txt2">
<option value="">请选择</option>
<option value="1">商务活动</option>
<option value="2">留学</option>
<option value="3">培训</option>
<option value="4">探亲</option>
<option value="5">旅游</option>
<option value="6">访友</option>
</select>
</td>
</tr>


<tr>
<td align="right" class="pad_R21" width="96">
              <span class="a_x">*</span>出行目的地
</td>
 
<td align="left">



  <div style="float:left;">
   	<select id="destination_area" name="destination_area" title="出行目的地" class="ind_txt2">
   		<option selected="selected" value="">请选择</option>
   		<option value="Asia">亚洲</option>
   		<option value="M-East">中东</option>
   		<option value="E-EuropeAsia">欧亚</option>
   		<option value="W-Europe">西欧</option>
   		<option value="E-Europe">东欧</option>
   		<option value="N-America">北美洲</option>
   		<option value="S-America">南美洲</option>
   		<option value="Oceania">大洋洲</option>
   		<option value="Africa">非洲</option>
   	</select>
 		</div>

<div style="float:left; margin-left:20px;" id="guo">

   	<select id="destination_country" name="destination_country" title="出行目的地" class="ind_txt2">
   		
   		
   	</select>
 		</div>
        <div style="clear:both"></div>
</td>
</tr>


<tr>
<td align="right" class="pad_R21" width="96">
              <span class="a_x">*</span>签证办理城市
</td>
 
<td align="left">
<input id="VisaCityhide" type="hidden" name="otherInfoName">
<input checked="checked" id="VisaCity" type="text" title="签证办理城市" name="VisaCity" value="" class="prod_txt">
<span style="color:#C8C8C8;">请填您填写签办城市</span>
</td>
</tr>


</tbody>

</table>

<div class="x_p_a_c c"><h2 class="l">保费</h2></div>

<table>	
  <tbody>
  
  <tr>
                           		 <td width="72">共计保险费：</td>
                           		
                   				 <td height="56" align="center" class="product_red"><sup><font size="+1">￥</font></sup>
 <span id="showprice"><?=$prodcutMarkPrice?></span>元
 </td>

 
        				</tr>

        		</tbody>
        		</table>

<div class="x_p_a_c c"><h2 class="l">投保须知</h2></div>		
<table style="margin-top:10px; text-align:left;">	
<tbody>
<tr>
<td width="996" style="padding:10px;">
<?=$product['cover_note']?>
</td>
</tr>

<tr style="text-align:center;font-size:16px; color:#666; font-weight:bold; ">	
<td align="center">  
<div class="c_m_b">
                    <span class="vam">对以上声明：</span>
                    <input name="radiobutton" id="radiobutton_yes" value="yes" title="我同意" type="radio"><label for="radiobutton_yes" class="ch vam">我同意</label>
<span class="c_m_c"><input name="radiobutton" id="radiobutton_no" value="no" title="我不同意" type="radio" checked="checked"><label for="radiobutton_no" class="ch vam">我不同意</label></span>
   </div>	
</td>								  

</tr>
  
  <tr style="text-align:center; padding-top:30px;">
  
 				
 <td width="180">
<a id="btnNext">
<img src="../images/icon15.jpg"></a>
    </td>	 	 
  </tr>

</tbody>
</table>			  
<input value="保存" id="submit1" type="submit"  style="display:none;" />	
 
</form>
</div>
</div>

</div> <!--contain-->


</div> <!-- simple_wrapper -->


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