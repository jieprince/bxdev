<?php if(!defined('IN_UCHOME')) exit('Access Denied');?><?php subtplcheck('template//cp_product_buy_taipingyang_yiwai|template//header_cshop|template//cp_product_buy_header|template//cp_product_buy_taipingyang_yiwai_additional|template//cp_product_buy_footer|template//footer_cshop', '1419326016', 'template//cp_product_buy_taipingyang_yiwai');?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
     <input name="gid" value="<?=$gid?>" type="hidden">	
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

<script src="../js/jquery.min.js"></script>
<script src="../static/js/ajaxfileupload.js"></script>
                        <script src="../static/js/jquery.json.min.js"></script>
                        <script src="../static/js/json2.js"></script>
                        
                        <link href="../layer/skin/layer.css" type="text/css" rel="stylesheet"/>
                        <link href="../layer/skin/layer.ext.css" type="text/css" rel="stylesheet"/>
                        <script src="../layer/layer.min.js"></script>
                        <script src="../layer/extend/layer.ext.js"></script>

<div class="x_p_a_c c"><h2 class="l">购买信息</h2></div>
<div class="c_p_a_d">
<table class="c_t_a c_m_a" cellpadding="0" cellspacing="0" border="0" width="45%">
<thead>
</thead>
<tbody>
 	<tr class="">
            <th width="30%">年龄范围</th>
            <td><span> <?=$product['age_min']?></span> 至 <span> <?=$product['age_max']?></span>岁</td>

        </tr>
<tr class="">
<th width="30%">购买份数</th>
<td>
<div class="c_m_r_a">
<select title="购买份数" name="applyNum" id="applyNum" backval="" class="i_e">
<option value="1" selected>1</option>
<option value="2" >2</option>
<option value="3">3</option>
</select>
<span class="c_m_c">份</span>
</div>
</td>
</tr>
<tr class="">
<th width="30%">金额</th>
<td>
<span id="span_totalpremium" class="c_a_a">
<?=$product['premium']?>                                            </span>
<span>元（</span><span id="cardPriceSpan"><?=$product['premium']?></span><span>元/份）&nbsp;</span>
</td>
</tr>
<tr class="">
<th width="30%">
<span class="a_x">*</span>保险起期</th>
<td>
<input value="" maxlength="50" name="startDate" id="startDate" title="购保险起期" class="i_f pa_ui_element_normal" type="text" readonly="readonly">
<span id="clientNameSpan" style="display:none;"></span>
</td>
</tr>
<tr class="">
<th width="30%">
<span class="a_x">*</span>保险止期</th>
<td>
<input value="" maxlength="50" name="endDate" id="endDate" title="购保险起期" class="i_f pa_ui_element_normal" type="text" readonly="readonly">
<span id="clientNameSpan" style="display:none;"></span>
</td>
</tr>
</tbody>
</table>
</div>
<!--投保人开始-->
<div class="x_p_a_c c">
<h2 class="l">投保人信息</h2>
<div class=""></div>
</div>


<div class="c_p_a_d">
<table class="c_t_a c_m_a" cellpadding="0" cellspacing="0" border="0" width="45%">
<tbody>


<tr class="">
<th><span class="a_x">*</span>投保人类型</th>
<td>
<span id="clientSexInput">
<input class="pa_ui_element_normal" name="applicant_type" value="1" id="applicant_type_single" title="个人" type="radio" checked="checked" >
<label for="man" class="ch">个人</label>&nbsp;&nbsp;&nbsp;

<input class="pa_ui_element_normal" name="applicant_type" value="2" id="applicant_type_group" title="团体" type="radio">
<label for="woman" class="ch">团体</label>

</span>
<span id="clientSexSpan" style="display:none;"></span>
</td>
<td><div id="m2"><div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>
</tbody>
</table>
</div>		


<div class="c_p_a_d"  id="applicant_info_single">
<table class="c_t_a c_m_a" cellpadding="0" cellspacing="0" border="0" width="45%">
<tbody>


<tr class="">
<th width="30%"><span class="a_x">*</span>姓名</th>
<td width="30%">
<input value="" maxlength="50" name="applicant_fullname" id="applicant_fullname" title="投保人姓名" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="display:none;"></span>
  </td>
<td width="1"><div id="m1"><div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>
<tr class="">
<th width="30%"><span class="a_x">*</span>证件类型</th>
<td width="30%">
<select title="身份证类别" name="applicant_certificates_type" id="applicant_certificates_type" class="i_e">
<option value="1" selected>身份证</option>
<option value="2" >护照</option>
<option value="3" >军官证</option>
<option value="4" >驾照</option>
<option value="5" >其他</option>
   
</select>
<span id="clientNameSpan" style="display:none;"></span>
  </td>
<td><div id="m1"><div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>
<tr class="">
<th width="30%"><span class="a_x">*</span>证件号码</th>
<td width="30%">
<input value="" maxlength="18" name="applicant_certificates_code" id="applicant_certificates_code" title="投保人姓名" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="display:none;"></span>
  </td>
<td><div id="m1"><div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>

<tr class="">
<th width="30%">
 注意：投保人年龄
</th>
<td width="30%">
 须大于18周岁
</td>

</tr>

<tr class="">
<th width="30%">
<span class="a_x">*</span>
生日                                            </th>
<td width="60%">
<input value="" maxlength="20" name="applicant_birthday" id="applicant_birthday" title="投保人生日" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="color:red">格式为： 2010-01-02</span>
  </td>
<td>
<div id="m1">
<div style="display: none;" class="pa_ui_valid_tip">
</div>
</div>
</td>
</tr>


<tr class="">
<th><span class="a_x">*</span>性别</th>
<td>
<span id="clientSexInput">
<input class="pa_ui_element_normal" name="applicant_sex" value="1" id="applicant_sex_man" title="投保人性别-男" type="radio" checked="checked" >
<label for="man" class="ch">男</label>&nbsp;&nbsp;&nbsp;
<input class="pa_ui_element_normal" name="applicant_sex" value="2" id="applicant_sex_woman" title="投保人性别-女" type="radio"><label for="woman" class="ch">女</label>
</span>
<span id="clientSexSpan" style="display:none;"></span>
</td>
<td><div id="m2"><div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>

<!--	
  <tr class="">
<th>职业类别</th>
<td>

 <select id="applicant_occupationClassCode" name="applicant_occupationClassCode">
<option value="110100">北京市</option>
</select>	
 </td>
  </tr>		 
-->	

   <tr class="">
<th> <span class="a_x">*</span>手机</th>
<td>
<span id="clientSexInput">
   <input name="applicant_mobilephone" id="applicant_mobilephone" value="" maxlength="11" title="投保人手机" class="i_f pa_ui_element_normal" type="text">
   
</span>
 
</td>
</tr>


<tr class="">
<th> <span class="a_x">*</span>email</th>
<td>
<span id="clientSexInput">
<input name="applicant_email" type="text" class="i_f pa_ui_element_normal" id="applicant_email" title="投保人email" value="" maxlength="50">
   
</span>
 
</td>
   
  </tr>




<tr class="" width="30%">
<th>地址信息</th>
<td colspan="2">
<select id="applicant_province_code" name="applicant_province_code" onchange="change_applicant_province()"  >
<option value="110000">北京市</option>
<option value="120000">天津市</option>
<option value="130000">河北省</option>
<option value="140000">山西省</option>
<option value="150000">内蒙古自治区</option>
<option value="210000">辽宁省</option>
<option value="220000">吉林省</option>
<option value="230000">黑龙江省</option>
<option value="310000">上海市</option>
<option value="320000">江苏省</option>
<option value="330000">浙江省</option>
<option value="340000">安徽省</option>
<option value="350000">福建省</option>
<option value="360000">江西省</option>
<option value="370000">山东省</option>
<option value="410000">河南省</option>
<option value="420000">湖北省</option>
<option value="430000">湖南省</option>
<option value="440000">广东省</option>
<option value="450000">广西壮族自治区</option>
<option value="460000">海南省</option>
<option value="500000">重庆市</option>
<option value="510000">四川省</option>
<option value="520000">贵州省</option>
<option value="530000">云南省</option>
<option value="610000">陕西省</option>
<option value="620000">甘肃省</option>
<option value="630000">青海省</option>
<option value="640000">宁夏回族自治区</option>
<option value="650000">新疆维吾尔自治区</option>
<option value="830000">深圳市</option>
</select>&nbsp;

<input type="hidden" name="applicant_province" id="applicant_province" value="" />
 

<select id="applicant_city_code" name="applicant_city_code">
<option value="110100">北京市</option>
</select>


<input type="hidden" name="applicant_city" id="applicant_city" value="" />
  </td>
</tr>




<tr class="">
<th><span class="a_x"></span>住址</th>
<td>
<span id="clientAddressInput">
   <input name="applicant_address" id="applicant_address" value="" maxlength="150" title="投保人住址" class="i_f pa_ui_element_normal" type="text">
   
</span>
 
</td>
</tr>


<tr class="">
<th><span class="a_x"></span>邮编</th>
<td>
<span id="clientAddressInput">
   <input name="applicant_zipcode" id="applicant_zipcode" value="" maxlength="6" title="投保人邮编" class="i_f pa_ui_element_normal" type="text">
   
</span>
 
</td>
</tr>								


</tbody>
</table>
</div>
<!--个人投保人结束-->

<div class="c_p_a_d"  id="applicant_info_group" style="display:none;">
<table class="c_t_a c_m_a" cellpadding="0" cellspacing="0" border="0" width="45%">
<tbody>

  <tr class="">
<th width="30%"><span class="a_x">*</span>机构名称</th>
<td width="30%">
<input value="" maxlength="50" name="applicant_group_name" id="applicant_group_name" title="投保人姓名" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="display:none;"></span>
  </td>
<td width="1"><div id="m1"><div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>
<tr class="">
<th width="30%"><span class="a_x">*</span>机构证件类型</th>
<td width="50%">
<select title="机构证件类型" name="applicant_group_certificates_type" id="applicant_group_certificates_type" class="i_e">
   <option value="6" >组织机构代码</option>
</select>
<span id="clientNameSpan" style="display:none;"></span>
  </td>
<td><div id="m1"><div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>
<tr class="">
<th width="30%"><span class="a_x">*</span>证件号码</th>
<td width="30%">
<input value="" maxlength="50" name="applicant_group_certificates_code" id="applicant_group_certificates_code" title="投保人姓名" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="display:none;"></span>
  </td>
<td><div id="m1"><div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>


   <tr class="">
<th> <span class="a_x">*</span>手机</th>
<td>
<span id="clientSexInput">
   <input name="applicant_group_mobilephone" id="applicant_group_mobilephone" value="" maxlength="11" title="投保人手机" class="i_f pa_ui_element_normal" type="text">
   
</span>
 
</td>
</tr>

  <tr class="">
<th> <span class="a_x">*</span>邮箱</th>
<td>
<span id="clientSexInput">
   <input name="applicant_group_email" id="applicant_group_email" value="" maxlength="50" title="投保人邮箱" class="i_f pa_ui_element_normal" type="text">
   
</span>
 
</td>
</tr>

</tbody>
</table>
</div>		
<!--团体投保人结束-->


<!--被保险人开始-->
<div class="x_p_a_c c">
<h2 class="l">被保险人信息</h2>
<div class="">（注：与投保人信息将会在支付成功后的激活流程中填写）</div>
</div>



<table class="c_t_a c_m_a" cellpadding="0" cellspacing="0" border="0" width="45%">
   
<tbody>
   <tr class="">
<th width="30%"><span class="a_x">*</span>与投保人关系</th>
<td width="30%">
<select readonly="readonly" title="与投保人关系" name="relationshipWithInsured" id="relationshipWithInsured" class="i_e">
<option value="">
请选择
</option>
<option value="1" >本人</option>
<option value="2" >配偶</option>
<option value="3" >子女</option>
<option value="4">父母</option>
<option value="5" >女儿</option>
<option value="6" >其他</option>
<option value="7" >儿子</option>
</select>
<span id="clientNameSpan" style="display:none;"></span>
</td>
<td>
<div id="m1"><div style="display: none;" class="pa_ui_valid_tip"></div></div></td>


</tr>
</tbody>
</table>		





<div class="c_p_a_d" id="div_assured" style="display:none;">


<!--导入被保险人方式-->
<div class="x_p_a_c c">
<h2 class="l">被保险人信息输入方式</h2>
<div class="">

<table class="c_t_a c_m_a" cellpadding="0" cellspacing="0" border="0" width="45%">
   
<tbody>
<tr> 
<td>
 <label for="man" class="ch">手工输入</label>
  <input class="pa_ui_element_normal" name="assured_input_type" value="1" id="assured_input_type_manual" title="手工输入" type="radio" checked="checked" >
</td>
 

<td>
  <label for="man" class="ch">xls批量导入</label>
  <input class="pa_ui_element_normal" name="assured_input_type" value="2" id="assured_input_type_xls" title="xls批量导入" type="radio" >
</td>



</tr>
 </tbody>
 </table>	
 
</div>
</div>


<table class="c_t_a c_m_a" cellpadding="0" cellspacing="0" border="0" width="45%" style="display:none;" id="table_input_type_xls">
<thead>
</thead>
<tbody>
<tr> 
<td align="right">
<div>
  <span class="btn btn-success fileinput-button">
  <i class="glyphicon glyphicon-plus"></i>
(3000 >= EXCEL人员数 >= 5)               
 <input type="file" id="inputExcel" name="inputExcel" accept="xls,xlsx"/>

  <input type="button" id="b" style="background:#0C3; border:0px; border-radius:3px; height:30px; width:100px; cursor:pointer; color:#FFF" value="导 入" onclick="if(inputExcel.value=='') alert('请选择xls文件'); else importXLs('inputExcel',<?=$product['product_id']?>,1);" /> 
 
  </span>
  &nbsp;&nbsp;&nbsp;&nbsp;
<a href="ziliao/pingan/Y502/Y502_demo.xlsx" class="f2" target="_blank">人员清单导入模板</a>&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="ziliao/pingan/Y502/zhiyefenlei(2009).xls" class="f2" target="_blank">平安职业分类表</a>
  
  <input type="hidden" id="excel_check" value="0" />
  <input type="hidden" id="excel_content" name="data_user_info" value="" />
 </div>
                                       
   <style type="text/css">
  .bar {
  height: 18px;
  background: green;
  }
  </style>
  
  <div id="progress">
<div class="bar" style="width: 0%;"></div>
   </div>
  <div id="excel_wrong" class="warn_strong"></div>
                                      
</td>
</tr>
 </tbody>
 </table>	 
 
                              <div id="ps_list">
                             	
                             </div>
 				
  <table id="tab" border="1" width="100%" align="center" style="margin-top:20px;display:none;">
<tr>
<td>姓名</td>
<td>证件类型</td>
<td>证件号码</td>
<td>生日</td>
<td>手机</td>
<td>email</td>
<td>省</td>
<td>区</td>
<td>住址</td>
<td>邮编</td>
</tr>	
 </table>

<table class="c_t_a c_m_a" cellpadding="0" cellspacing="0" border="0" width="45%" id="table_input_type_manual">
<thead>
</thead>
<tbody>


<tr class="" id="tr_assured_fullname">
<th width="30%"><span class="a_x">*</span>姓名</th>
<td width="30%">
<input value="" maxlength="50" name="assured[0][assured_fullname]" id="assured_fullname" title="姓名" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="display:none;"></span>
</td>
<td><div id="m1"><div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>



<tr class="" id="tr_assured_certificates_type">
<th width="30%"><span class="a_x">*</span>证件类型</th>
<td width="30%">
<select title="被保险人身份证类别" name="assured[0][assured_certificates_type]" id="assured_certificates_type" class="i_e">
<option value="1" selected>身份证</option>
<option value="2" >护照</option>
<option value="3" >军官证</option>
<option value="4" >驾照</option>
<option value="5" >其他</option>
<option value="6" >组织机构代码</option>
</select>
<span id="clientNameSpan" style="display:none;"></span>
</td>
<td>
<div id="m1">
<div style="display: none;" class="pa_ui_valid_tip">
</div>
</div>
</td>
</tr>
<tr class="" id="tr_assured_certificates_code">
<th width="30%"><span class="a_x">*</span>证件号码</th>
<td width="30%">
<input value="" maxlength="50" name="assured[0][assured_certificates_code]" id="assured_certificates_code" title="被保险人证件号码" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="display:none;"></span>
</td>
<td><div id="m1"><div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>
<tr class="" id="tr_assured_sex">
<th><span class="a_x">*</span>性别</th>
<td>
<span id="clientSexInput">
<input class="pa_ui_element_normal" name="assured[0][assured_sex]" value="1" id="assured_sex_man" title="被保险人性别-男" type="radio" checked="checked" >
<label for="man" class="ch">男</label>&nbsp;&nbsp;&nbsp;
<input class="pa_ui_element_normal" name="assured[0][assured_sex]" value="2" id="assured_sex_woman" title="被保险人性别-女" type="radio">
<label for="woman" class="ch">女</label>
</span>
<span id="clientSexSpan" style="display:none;"></span>
</td>
<td><div id="m2"><div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>


  <tr class="">
<th width="30%">
投保年龄范围
</th>
<td width="60%" >
   <span > 	<?=$product['age_min']?></span> 至
<span > <?=$product['age_max']?></span>岁
</td>

</tr>




<tr class="" id="tr_assured_birthday">
<th width="30%">
<span class="a_x">*</span>
生日
</th>
<td width="60%">
<input value="" maxlength="20" name="assured[0][assured_birthday]" id="assured_birthday" title="被保险人生日" class="i_f pa_ui_element_normal" type="text">

<span id="clientNameSpan" style="color:red">格式为： 2010-01-02</span>
</td>
<td>
<div id="m1">
<div style="display: none;" class="pa_ui_valid_tip">
</div>
</div>
</td>
</tr>


<!--	
  <tr class="">
<th>职业类别</th>
<td>

 <select id="assured_occupationClassCode" name="assured[0][assured_occupationClassCode]">
<option value="110100">北京市</option>
</select>	
 </td>
  </tr>	
  
  -->


   <tr class="">
<th> <span class="a_x">*</span>手机</th>
<td>
<span id="clientSexInput">
   <input name="assured[0][assured_mobilephone]" id="assured_mobilephone" value="" maxlength="11" title="被保险人手机" class="i_f pa_ui_element_normal" type="text">
   
</span>
 
</td>
</tr>


<tr class="">
<th> <span class="a_x">*</span>email:</th>
<td>
<span id="clientSexInput">
<input name="assured[0][assured_email]" type="text" class="i_f pa_ui_element_normal" id="assured_email" title="被保险人email" value="" maxlength="50">
   
</span>
 
</td>
   
  </tr>


<tr class="" width="600px">
<th>地址信息</th>
<td width="550px" colspan="2">
<select id="insured_province_code"  name="assured[0][insured_province_code]" onchange="change_insured_province()">
<option value="110000">北京市</option>
<option value="120000">天津市</option>
<option value="130000">河北省</option>
<option value="140000">山西省</option>
<option value="150000">内蒙古自治区</option>
<option value="210000">辽宁省</option>
<option value="220000">吉林省</option>
<option value="230000">黑龙江省</option>
<option value="310000">上海市</option>
<option value="320000">江苏省</option>
<option value="330000">浙江省</option>
<option value="340000">安徽省</option>
<option value="350000">福建省</option>
<option value="360000">江西省</option>
<option value="370000">山东省</option>
<option value="410000">河南省</option>
<option value="420000">湖北省</option>
<option value="430000">湖南省</option>
<option value="440000">广东省</option>
<option value="450000">广西壮族自治区</option>
<option value="460000">海南省</option>
<option value="500000">重庆市</option>
<option value="510000">四川省</option>
<option value="520000">贵州省</option>
<option value="530000">云南省</option>
<option value="610000">陕西省</option>
<option value="620000">甘肃省</option>
<option value="630000">青海省</option>
<option value="640000">宁夏回族自治区</option>
<option value="650000">新疆维吾尔自治区</option>
<option value="830000">深圳市</option>
</select>&nbsp;

<input type="hidden" name="assured[0][insured_province]" id="insured_province" value="" />


<select name="assured[0][insured_city_code]" id="insured_city_code">
<option value="110100">北京市</option>
</select>  

<input type="hidden" name="assured[0][insured_city]" id="insured_city" value="" />

</td>
</tr>



<tr class="">
<th><span class="a_x"></span>住址</th>
<td>
<span id="insuredAddressInput">
   <input name="assured[0][insured_address]" id="insured_address" value="" maxlength="150" title="投保人住址" class="i_f pa_ui_element_normal" type="text">
   
</span>
 
</td>
</tr>


<tr class="">
<th><span class="a_x"></span>邮编</th>
<td>
<span id="insuredzipcodeInput">
   <input name="assured[0][insured_zipcode]" id="insured_zipcode" value="" maxlength="6" title="投保人邮编" class="i_f pa_ui_element_normal" type="text">
   
</span>
 
</td>
</tr>				


</tbody>
</table>

<!--被保险人结束-->

</div> <!--div_assured-->


<?php if($product['product_code']=="2101700000000009") { ?>
<input type="hidden" name="product_code" id="product_code" value="<?=$product['product_code']?>"/>

<div class="x_p_a_c c">
<h2 class="l">航班信息信息</h2>
<div class=""></div>
</div>


<div class="c_p_a_d"  id="applicant_info_single">
<table class="c_t_a c_m_a" cellpadding="0" cellspacing="0" border="0" width="45%">
<tbody>
 
<tr class="">
<th width="30%"><span class="a_x">*</span>订票日期</th>
<td width="30%">
<input value="" maxlength="50" name="orderDate" id="orderDate" title="订票日期" class="i_f pa_ui_element_normal" type="text">
  <span id="clientNameSpan" style="display:none;"></span>
  </td>
<td width="1"><div id="m1">
<div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>
   

   <tr class="">
<th width="30%"><span class="a_x">*</span>航班号或火车班次</th>
<td width="30%">
<input value="" maxlength="50" name="flightNo" id="flightNo" title="订票日期" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="display:none;"></span>
  </td>
<td width="1"><div id="m1">
<div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>


   <tr class="">
<th width="30%"><span class="a_x">*</span>出发地</th>
<td width="30%">
<input value="" maxlength="50" name="flightFrom" id="flightFrom" title="订票日期" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="display:none;"></span>
  </td>
<td width="1"><div id="m1">
<div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>


   <tr class="">
<th width="30%"><span class="a_x">*</span>目标地</th>
<td width="30%">
<input value="" maxlength="50" name="flightTo" id="flightTo" title="订票日期" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="display:none;"></span>
  </td>
<td width="1"><div id="m1">
<div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>


   <tr class="">
<th width="30%"><span class="a_x">*</span>出发时间</th>
<td width="30%">
<input value="" maxlength="50" name="takeoffDate" id="takeoffDate" title="订票日期" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="display:none;"></span>
  </td>
<td width="1"><div id="m1">
<div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>


   <tr class="">
<th width="30%"><span class="a_x">*</span>到达时间</th>
<td width="30%">
<input value="" maxlength="50" name="landDate" id="landDate" title="到达时间" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="display:none;"></span>
  </td>
<td width="1"><div id="m1">
<div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>

 <tr class="">
<th width="30%"><span class="a_x">*</span>电子票号</th>
<td width="30%">
<input value="" maxlength="50" name="eTicketNo" id="eTicketNo" title="电子票号" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="display:none;"></span>
  </td>
<td width="1"><div id="m1">
<div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>


 <tr class="">
<th width="30%"><span class="a_x">*</span>PNR码</th>
<td width="30%">
<input value="" maxlength="50" name="pnrCode" id="pnrCode" title="PNR码" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="display:none;"></span>
  </td>
<td width="1"><div id="m1">
<div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>
 <tr class="">
<th width="30%"><span class="a_x">*</span>机票金额</th>
<td width="30%">
<input value="" maxlength="50" name="ticketAmount" id="ticketAmount" title="机票金额" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="display:none;"></span>
  </td>
<td width="1"><div id="m1">
<div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>

 <tr class="">
<th width="30%"><span class="a_x">*</span>签约旅行社</th>
<td width="30%">
<input value="春秋" maxlength="50" name="travelAgency" id="travelAgency" title="机票金额" class="i_f pa_ui_element_normal" type="text">
<span id="clientNameSpan" style="display:none;"></span>
  </td>
<td width="1"><div id="m1">
<div style="display: none;" class="pa_ui_valid_tip"></div></div></td>
</tr>

</tbody>
</table>
</div>



<?php } ?>


<div class="x_p_a_c c">
<h2 class="l">受益人</h2>
<div class=""></div>
</div>

<table class="c_t_a c_m_a" cellpadding="0" cellspacing="0" border="0" width="45%">
   
<tbody>
   <tr class="">
<th width="30%">
<span class="a_x">*</span>受益人</th>
<td width="30%">
 <select title="受益人" name="beneficiary" id="beneficiary" backval="" class="i_e">
<option value="1" selected="1">法定</option>
</select>
</td>
</td>
   
<td	>
<div id="m1">
<div style="display: none;" class="pa_ui_valid_tip"></div></div>
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