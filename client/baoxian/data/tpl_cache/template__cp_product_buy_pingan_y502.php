<?php if(!defined('IN_UCHOME')) exit('Access Denied');?><?php subtplcheck('template//cp_product_buy_pingan_y502|template//header_cshop|template//cp_product_buy_header|template//cp_product_buy_footer|template//footer_cshop', '1419400848', 'template//cp_product_buy_pingan_y502');?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
                         		
                        		<input id="career" name="career" value="<?=$career?>" type="hidden">
                                <input id="posion_price" name="posion_price" value="<?=$prodcutMarkPrice?>" type="hidden">
                                <input id="additional_ids" name="duty_price_ids" value="<?=$duty_price_ids?>" type="hidden">
                                <div class="x_p_a_c c"><h2 class="l">选中产品</h2></div>
                                <div class="c_p_a_d" style="padding:20px;">
                                	 <table align="center" class="border_table_05 " cellpadding="0" cellspacing="0" width="870">
                                     <tr><td class="tac fwb">类型</td><td class="tac fwb">产品名称</td><td class="tac fwb">责任</td><td class="tac fwb">保额/保费(元)</td></tr>
                                        <?php if(is_array($list_product_duty_price)) { foreach($list_product_duty_price as $index => $value1) { ?>
                                        	
                                             <tr>
                                             	<?php if($value1['product_type']=='main' ) { ?>
                                                <td width="80" class="tac">主险</td>
                                                <td width="300" class="tac"><?=$value1['product_name']?></td>
                                                <td width="240" class="tac"><?=$value1['duty_name']?></td>
                                                <td width="130" class="tac"><?=$value1['amount']?>/<?=$value1['price']?></td>
                                            	<?php } ?>
                                              </tr>
                                              
                                        <?php } } ?>
                                        <?php if(is_array($list_product_duty_price)) { foreach($list_product_duty_price as $index => $value1) { ?>
                                             <tr>
                                             
                                             	<?php if($value1['product_type']=='additional' ) { ?>
                                                
                                                <td class="tac">附加险</td>
                                                
                                                <td class="tac"><?=$value1['product_name']?></td>
                                                <td class="tac"><?=$value1['duty_name']?></td>
                                                <td class="tac"><?=$value1['amount']?>/<?=$value1['price']?></td>
                                            	<?php } ?> 
                                              </tr>
                                              
                                        <?php } } ?>
                                     </table>
                                </div>
<div class="x_p_a_c c"><h2 class="l">购买信息</h2></div>
<div class="c_p_a_d">
<table  cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top:10px;margin-bottom:10px;"  >	 
                            <tbody>
                                <tr class="">
                                    <th width="80">保险期限</th>
                                    <td width="270"><span id="period_temp"> </span> </td>
                                
                                 
                                    <th width="80">职业类别</th>
                                    <td width="150"><span id="career_temp"></span></td>
                                    <th width="80">年龄范围</th>
                                    <td width="250"><span> <?=$product['age_min']?></span> 至 <span> <?=$product['age_max']?></span>岁</td>
                                 </tr>
                                 <tr class="">
                                  <th >购买份数</th>
                                    <td>
                                        <div class="c_m_r_a">
                                            <select title="购买份数" name="applyNum" id="applyNum" backval="" class="i_e">
                                            <option value="1" selected>1</option>
                                            
                                            </select>
                                            <span class="c_m_c">份/人</span>
                                        </div>
                                    </td>
                                           
<th >
<span class="a_x">*</span>保险起期</th>
<td>
<input value="" maxlength="50" name="startDate" id="startDate" title="购保险起期" class="i_f pa_ui_element_normal" type="text" readonly="readonly">
</td>
 
<th>
<span class="a_x">*</span>保险止期</th>

<td>
<input value="" maxlength="50" name="endDate" id="endDate" title="保险止期" class="i_f pa_ui_element_normal" type="text" readonly="readonly">
</td>

</tr>

</tbody>
</table>
</div>

<div class="x_p_a_c c">
<h2 class="l">投保人信息</h2>
<div class=""></div>
</div>

<div class="c_p_a_d">
<table cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top:10px;margin-bottom:10px;" >
<tbody>
  <tr class="">
<th width="80" align="right"><span class="a_x">*</span>机构名称</th>
<td width="205">
<input value="" maxlength="50" name="applicant_group_name" id="applicant_group_name" title="机构名称" class="i_f pa_ui_element_normal" type="text" onblur="checkgroupname();">
<span id="applicant_groupname_warn" style="display:none;" class="warn_strong"></span>
  </td>
<th width="88" align="right"><span class="a_x">*</span>机构类型</th>
<td width="140">
<select title="机构类型" name="company_attribute" id="company_attribute" class="i_e_jigou" style="width:60px;">
   <option value="01">国有</option>
   <option value="02">集体</option>
   <option value="03">私营</option>
   <option value="33">个体</option>
   <option value="04">中外合资</option>
   <option value="05">外商独资</option>
   <option value="07">股份</option>
   <option value="08">机关事业</option>
   <option value="13">社团</option>
   <option value="39">中外合作</option>
   <option value="9">其他</option>
</select>
<span id="applicant_certificates_type_warn" style="display:none;"></span>
  </td>
<th width="80" align="right"><span class="a_x">*</span>证件类型</th>
<td width="200">
<select title="机构证件类型" name="applicant_certificates_type" id="applicant_certificates_type" class="i_e" style="width:130px;">
   <option value="01" >组织机构代码证</option>
</select>
<span id="applicant_certificates_type_warn" style="display:none;"></span>
  </td>
 
            <th width="80" align="right"><span class="a_x">*</span>证件号码</th>
<td width="250" >
<input value="" maxlength="50" name="applicant_certificates_code" id="applicant_certificates_code" title="证件号码" class="i_f pa_ui_element_normal" type="text" onblur="check_applicant_card(6);">
<span id="applicant_certificates_code_warn" ></span>
  </td>
</tr>
<tr class="">

 
<th align="right"> <span class="a_x">*</span>手机</th>
<td>
<span id="clientSexInput">
   <input name="applicant_mobilephone" id="applicant_mobilephone" value="" maxlength="11" title="投保人手机" class="i_f pa_ui_element_normal" type="text" onblur="check_applicant_mobilephone();">
   
</span>
                <span id="applicant_mobilephone_warn" class="warn_strong"></span>
 
</td>
            <th align="right"> <span class="a_x">*</span>邮箱</th>
<td colspan="3" align="left">
<span id="clientSexInput">
   <input name="applicant_email" id="applicant_email" value="" maxlength="50" title="投保人邮箱" class="i_f pa_ui_element_normal" type="text" onblur="check_applicant_email();">
   
</span>
 <span id="applicant_email_warn" class="warn_strong"></span>
</td>
</tr>

  

</tbody>
</table>
</div>
 
<div class="x_p_a_c c">
<h2 class="l">被保险人信息</h2>
</div>
<div class="c_p_a_d">

 
                    			<table class="c_t_a c_m_a" cellpadding="0" cellspacing="0" border="0" id="table_input_type_xls" width="910" style="margin-top:10px;">
                                      
                                      <tr> 
                                      <td align="right">
                                      <div>
                                      <span class="btn btn-success fileinput-button">
                                                      <i class="glyphicon glyphicon-plus"></i>
                                                        (3000 >= EXCEL人员数 >= 5)               
                                                     <input type="file" id="inputExcel" name="inputExcel" accept="xls,xlsx"/>
                                                
                                      <input type="button" id="b" style="background:#0C3; border:0px; border-radius:3px; height:30px; width:100px; cursor:pointer; color:#FFF" value="导 入" onclick="if(inputExcel.value=='')alert('请选择xls文件');else importXLs('inputExcel',<?=$product['product_id']?>,<?=$career?>);" /> 
                                     
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
                                      
                                   </table>	 
                                   	
 </div>
                             <div id="ps_list">
                             	
                             </div>
                             <!--div_assured-->


 <table class="c_t_a c_m_a" cellpadding="0" cellspacing="0" border="0" width="90%" style="margin-top:20px;">
                                   
                                    <tbody>
   <tr class="">
                                            <th width="50%" height="40" >
<span class="a_x">*</span>受益人</th>
                                            <td width="50%">
                                               		
<select title="受益人" name="beneficiary" id="beneficiary" backval="" class="i_e">
<option value="1" selected="1">法定</option>
</select> </td>

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