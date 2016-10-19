<?php if(!defined('IN_UCHOME')) exit('Access Denied');?><?php subtplcheck('template//cp_product_buy_view|template//header_cshop|template//cp_product_buy_pingan_lvyou_view|template//cp_product_buy_pingan_y502_view|template//cp_product_buy_pingan_yiwai_view|template//cp_product_buy_taipingyang_yiwai_view|template//cp_product_buy_huatai_chuguo_yiwai_view|template//footer_cshop|template//cp_product_buy_taipingyang_yiwai_hangkong_additional_view', '1419400866', 'template//cp_product_buy_view');?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

<input type="hidden" name="lottery" value='' />


<div id="contain">

<div class="insure">
<div class="insure_main">

<div class="insure_step"><img src="../images/insure11.jpg" width="386" height="47"><img src="../images/insure22.jpg" width="392" height="47"><img src="../images/insure03.jpg" width="332" height="47"></div>
<div class="insure_title"><?=$product_arr['product_name']?>&nbsp;&nbsp;<span style="color:#09c;margin:0;padding:0;"></span></div>

<form id="fgNewOrderSearchForm" name="fgNewOrderSearchForm" method="POST" action="cp.php?ac=product_buy&product_id=<?=$product['product_id']?>&policy_id=<?=$policy_id?>&gid=<?=$gid?>&step=2">
<div class="insure_cen">

<input type="hidden" name="orderbuysubmit" id="orderbuysubmit" value="true" />

<input type="hidden" name="formhash" value="<?php echo formhash(); ?>" />

<input type="hidden" id="goumaifangshi" name="goumaifangshi"  value="">

  	<?php if($insurer_code == "PAC") { ?>
            <?php if($product['attribute_type']=='lvyou') { ?> 
            		
<div class="x_p_a_c c"><h2>投保人信息</h2></div>
<!--保险计划-->
<!--投保人信息-->
 <table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">
<tbody><tr>

<td width="150" class="check_td_left">投保人姓名：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['fullname']?></td>


<td width="150" class="check_td_left">英文名：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['fullname_english']?></td>


</tr>

<tr><td width="150" class="check_td_left">证件类型：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['certificates_type']?></td>


<td width="150" class="check_td_left">证件号：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['certificates_code']?></td>


</tr>

<tr><td width="150" class="check_td_left">性别：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['gender']?></td>


<td width="150" class="check_td_left">出生日期：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['birthday']?></td>


</tr>

<tr><td width="150" class="check_td_left">手机号：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['mobiletelephone']?></td>


<td width="150" class="check_td_left">电子邮箱：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['email']?></td>


</tr>

<tr>
<td width="150" class="check_td_left">省：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['province']?></td>


<td width="150" class="check_td_left">市：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['city']?></td>


</tr>

<tr>
<td width="150" class="check_td_left">通讯地址：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['address']?></td>


<td width="150" class="check_td_left">邮政编码：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['zipcode']?></td>


</tr>



</tbody>
</table>


<div class="x_p_a_c c">
                  			<h2>被保人信息</h2>
                  		</div>
                  		
                  <table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">
<tbody><tr>

<td width="150" class="check_td_left">被保人是投保人：</td>
<td width="190" class="check_td_right"><?=$relationship_with_insured_str?></td>


<td width="150" class="check_td_left">被保人姓名：</td>
<td width="190" class="check_td_right"><?=$insurant_info['fullname']?></td>


</tr>

<tr><td width="150" class="check_td_left">证件类型：</td>
<td width="190" class="check_td_right"><?=$insurant_info['certificates_type']?></td>


<td width="150" class="check_td_left">证件号：</td>
<td width="190" class="check_td_right"><?=$insurant_info['certificates_code']?></td>


</tr>

<tr><td width="150" class="check_td_left">性别：</td>
<td width="190" class="check_td_right"><?=$insurant_info['gender']?></td>


<td width="150" class="check_td_left">出生日期：</td>
<td width="190" class="check_td_right"><?=$insurant_info['birthday']?></td>


</tr>

<tr>
<td width="150" class="check_td_left">省：</td>
<td width="190" class="check_td_right"><?=$insurant_info['province']?></td>


<td width="150" class="check_td_left">市：</td>
<td width="190" class="check_td_right"><?=$insurant_info['city']?></td>


</tr>


</tbody>
</table>





<div class="x_p_a_c c">
                  			<h2>身故受益人信息</h2></div>
                  			<table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">
<tbody><tr>
                             <td width="150" class="check_td_left">法定</td>
                             <td width="617" class="check_td_right"></td>
                       		  </tr>
                         </tbody>
 </table>
 
 


<!--附加信息-->
<!--
<div class="x_p_a_c c"><h2>其它信息</h2></div>
<table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">

<tbody>

<tr>
<td width="150" class="check_td_left">出行目的</td>
<td width="617" class="check_td_right">商务活动</td>
</tr>

<tr>
<td width="150" class="check_td_left">出行目的地</td>
<td width="617" class="check_td_right">美国</td>
</tr>

<tr>
<td width="150" class="check_td_left">签证办理城市</td>
<td width="617" class="check_td_right">北京</td>
</tr>


</tbody></table>
-->
<!--健康告知-->

<div class="x_p_a_c c"><h2>保单信息</h2></div>
<table cellpadding="0" cellspacing="0" width="700">

<tbody>

<tr>
<td width="176" height="45" align="center" class="insure_cen_table_td insure_cen_table_das">保险期限：
</td>

<td width="605" class="check_td_right" style="font-weight:bolder">

<?=$policy_arr['start_date']?>&nbsp;&nbsp;至&nbsp;&nbsp;<?=$policy_arr['end_date']?>

</td>
</tr>

</tbody>
</table>	



<div class="x_p_a_c c"><h2>保障内容：</h2></div>
        <div align="left">
<table cellpadding="0" cellspacing="0" width="700" >	
   <tbody>		

<tr>
<td style="text-align: center; width:200px; font-weight:bolder; font-size:14px;"><strong>保险责任</strong></td>
<td style="text-align: center;font-weight:bolder;font-size:14px;"><strong>保险金额</strong></td>
</tr>

<?php if(is_array($product_duty_list)) { foreach($product_duty_list as $key => $value) { ?>
        <tr>
            <td class="check_td_left"><?=$value['duty_name']?></td>
            <td class="check_td_right"><?=$value['amount']?></td>
        </tr>
<?php } } ?>

     
    </tbody>
</table>
</div>


            <?php } elseif($product['attribute_type']=='Y502') { ?> 
            		
<div class="x_p_a_c c"><h2 class="l">保障内容</h2></div>
<div>
   <table align="center" class="border_table_05 " width="910"  >
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
                
                <div class="x_p_a_c c"><h2>投保人信息</h2></div>
<!--保险计划-->
<!--投保人信息-->
 <table  cellpadding="0" cellspacing="0" cellpadding="0" align="center" width="910" >
<tbody><tr>

<td width="100" class="check_td_left">机构名称：</td>
<td width="200" class="check_td_right"><?=$user_info_applicant['group_name']?></td>
<td width="100" class="check_td_left">机构类型：</td>
<td width="200" class="check_td_right"><?=$user_info_applicant['company_attribute']?></td>
<td width="100" class="check_td_left">证件类型：</td>
<td width="200" class="check_td_right">组织机构代码证</td>
<td width="100" class="check_td_left">证件号码：</td>
<td width="200" class="check_td_right"><?=$user_info_applicant['group_certificates_code']?></td>
</tr>

<tr>
                        <td width="100" class="check_td_left">手机号码：</td>
<td width="200" class="check_td_right"><?=$user_info_applicant['mobiletelephone']?></td>
                        <td width="100" class="check_td_left">电子邮箱：</td>
<td  class="check_td_right" colspan="3"><?=$user_info_applicant['email']?></td>
 
</tr>
 
</tbody>
</table>
 
<div class="x_p_a_c c">
                  			<h2>被保人信息</h2>
                  		</div>
<style>
.border_table_05 {}
.border_table_05 td {border-bottom: 1px solid #dddddd;}
.border_table_05 th { border-bottom: 1px solid #dddddd;border-top: 1px solid #dddddd;}
.tac{text-align:center;}
</style>
                    <table width="90%" cellspacing="0" cellpadding="0" border="1" class="border_table_05" style="margin-top:10px;margin-bottom:10px;" >
                    <tr><th class="fwb tac">姓名</th><th class="fwb tac">机构类型</th><th class="fwb tac">证件类型</th><th class="fwb tac">证件号码</th><th class="fwb tac">职业代码</th><th class="fwb tac">性别</th><th class="fwb tac">生日</th><th class="fwb tac">邮箱</th><th class="fwb tac">手机号</th></tr>
                    <?php if(is_array($list_subject_insurant)) { foreach($list_subject_insurant as $index => $value) { ?>
                    
                    <tr><td data-value="fullname" class=" tac"><?=$value['fullname']?></td><td data-value="certificates_type" class=" tac"><?=$value['certificates_type']?></td><td data-value="certificates_code" class=" tac"><?=$value['certificates_code']?></td><td data-value="career_type" class=" tac"><?=$value['occupationClassCode']?></td><td data-value="gender" class=" tac"><?=$value['gender']?></td><td data-value="birthday" class=" tac"><?=$value['birthday']?></td><td data-value="email" class=" tac"><?=$value['email']?></td><td data-value="mobiletelephone" class=" tac"><?=$value['mobiletelephone']?></td></tr>
                   <?php } } ?> 
                   </table>

<div class="x_p_a_c c">
                  			<h2>身故受益人信息</h2></div>
                  			<table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">
<tbody><tr>
                             <td width="150" class="check_td_left">法定</td>
                             <td width="617" class="check_td_right"></td>
                       		  </tr>
                         </tbody>
 </table>

<div class="x_p_a_c c"><h2>保单信息</h2></div>
<table cellpadding="0" cellspacing="0" width="700">

<tbody>

<tr>
<td width="176" height="45" align="center" class="insure_cen_table_td insure_cen_table_das">保险期限：
</td>

<td width="605" class="check_td_right" style="font-weight:bolder">

<?=$policy_arr['start_date']?>&nbsp;&nbsp;至&nbsp;&nbsp;<?=$policy_arr['end_date']?>

</td>
</tr>

</tbody>
</table>	
            <?php } else { ?>
            		
<div class="x_p_a_c c"><h2>投保人信息</h2></div>
<!--保险计划-->
<!--投保人信息-->
 <table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">
<tbody><tr>

<td width="150" class="check_td_left">投保人姓名：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['fullname']?></td>


<td width="150" class="check_td_left">英文名：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['fullname_english']?></td>


</tr>

<tr><td width="150" class="check_td_left">证件类型：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['certificates_type']?></td>


<td width="150" class="check_td_left">证件号：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['certificates_code']?></td>


</tr>

<tr><td width="150" class="check_td_left">性别：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['gender']?></td>


<td width="150" class="check_td_left">出生日期：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['birthday']?></td>


</tr>

<tr><td width="150" class="check_td_left">手机号：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['mobiletelephone']?></td>


<td width="150" class="check_td_left">电子邮箱：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['email']?></td>


</tr>

<tr>
<td width="150" class="check_td_left">省：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['province']?></td>


<td width="150" class="check_td_left">市：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['city']?></td>


</tr>

<tr>
<td width="150" class="check_td_left">通讯地址：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['address']?></td>


<td width="150" class="check_td_left">邮政编码：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['zipcode']?></td>


</tr>



</tbody>
</table>


<div class="x_p_a_c c">
                  			<h2>被保人信息</h2>
                  		</div>
                  		
                  <table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">
<tbody><tr>

<td width="150" class="check_td_left">被保人是投保人：</td>
<td width="190" class="check_td_right"><?=$relationship_with_insured_str?></td>


<td width="150" class="check_td_left">被保人姓名：</td>
<td width="190" class="check_td_right"><?=$insurant_info['fullname']?></td>


</tr>

<tr><td width="150" class="check_td_left">证件类型：</td>
<td width="190" class="check_td_right"><?=$insurant_info['certificates_type']?></td>


<td width="150" class="check_td_left">证件号：</td>
<td width="190" class="check_td_right"><?=$insurant_info['certificates_code']?></td>


</tr>

<tr><td width="150" class="check_td_left">性别：</td>
<td width="190" class="check_td_right"><?=$insurant_info['gender']?></td>


<td width="150" class="check_td_left">出生日期：</td>
<td width="190" class="check_td_right"><?=$insurant_info['birthday']?></td>


</tr>

<tr>
<td width="150" class="check_td_left">省：</td>
<td width="190" class="check_td_right"><?=$insurant_info['province']?></td>


<td width="150" class="check_td_left">市：</td>
<td width="190" class="check_td_right"><?=$insurant_info['city']?></td>


</tr>


</tbody>
</table>





<div class="x_p_a_c c">
                  			<h2>身故受益人信息</h2></div>
                  			<table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">
<tbody><tr>
                             <td width="150" class="check_td_left">法定</td>
                             <td width="617" class="check_td_right"></td>
                       		  </tr>
                         </tbody>
 </table>
 
 


<!--附加信息-->
<!--
<div class="x_p_a_c c"><h2>其它信息</h2></div>
<table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">

<tbody>

<tr>
<td width="150" class="check_td_left">出行目的</td>
<td width="617" class="check_td_right">商务活动</td>
</tr>

<tr>
<td width="150" class="check_td_left">出行目的地</td>
<td width="617" class="check_td_right">美国</td>
</tr>

<tr>
<td width="150" class="check_td_left">签证办理城市</td>
<td width="617" class="check_td_right">北京</td>
</tr>


</tbody></table>
-->
<!--健康告知-->

<div class="x_p_a_c c"><h2>保单信息</h2></div>
<table cellpadding="0" cellspacing="0" width="700">

<tbody>

<tr>
<td width="176" height="45" align="center" class="insure_cen_table_td insure_cen_table_das">保险期限：
</td>

<td width="605" class="check_td_right" style="font-weight:bolder">

<?=$policy_arr['start_date']?>&nbsp;&nbsp;至&nbsp;&nbsp;<?=$policy_arr['end_date']?>

</td>
</tr>

</tbody>
</table>	



<div class="x_p_a_c c"><h2>保障内容：</h2></div>
        <div align="left">
<table cellpadding="0" cellspacing="0" width="700" >	
   <tbody>		

<tr>
<td style="text-align: center; width:200px; font-weight:bolder; font-size:14px;"><strong>保险责任</strong></td>
<td style="text-align: center;font-weight:bolder;font-size:14px;"><strong>保险金额</strong></td>
</tr>

<?php if(is_array($product_duty_list)) { foreach($product_duty_list as $key => $value) { ?>
        <tr>
            <td class="check_td_left"><?=$value['duty_name']?></td>
            <td class="check_td_right"><?=$value['amount']?></td>
        </tr>
<?php } } ?>

     
    </tbody>
</table>
</div>


            <?php } ?>
   
<?php } elseif($insurer_code == "TBC01") { ?>   

   	
<div class="x_p_a_c c"><h2>投保人信息</h2></div>
<!--保险计划-->
<!--投保人信息-->
 <table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">
<tbody>

<?php if($policy_arr['business_type']==1) { ?>
<tr>

<td width="150" class="check_td_left">投保人姓名：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['fullname']?></td>


<td width="150" class="check_td_left">英文名：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['fullname_english']?></td>


</tr>

<tr><td width="150" class="check_td_left">证件类型：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['certificates_type']?></td>


<td width="150" class="check_td_left">证件号：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['certificates_code']?></td>


</tr>

<tr><td width="150" class="check_td_left">性别：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['gender']?></td>


<td width="150" class="check_td_left">出生日期：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['birthday']?></td>


</tr>

<tr><td width="150" class="check_td_left">手机号：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['mobiletelephone']?></td>


<td width="150" class="check_td_left">电子邮箱：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['email']?></td>


</tr>

<?php } elseif($policy_arr['business_type']==2) { ?>
<tr>

<td width="150" class="check_td_left">机构名称：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['group_name']?></td>

<td width="150" class="check_td_left"></td>
<td width="190" class="check_td_right">
</td>

</tr>

<tr><td width="150" class="check_td_left">证件类型：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['group_certificates_type']?></td>


<td width="150" class="check_td_left">证件号码：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['group_certificates_code']?></td>


</tr>
<tr><td width="150" class="check_td_left">手机号：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['mobiletelephone']?></td>


<td width="150" class="check_td_left">电子邮箱：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['email']?></td>


</tr>
<?php } ?>

<tr>
<td width="150" class="check_td_left">省：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['province']?></td>


<td width="150" class="check_td_left">市：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['city']?></td>


</tr>

<tr>
<td width="150" class="check_td_left">通讯地址：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['address']?></td>


<td width="150" class="check_td_left">邮政编码：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['zipcode']?></td>


</tr>



</tbody>
</table>


<div class="x_p_a_c c">
                  			<h2>被保人信息</h2>
                  		</div>
                  		
                  <table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">
<tbody><tr>

<td width="150" class="check_td_left">被保人是投保人：</td>
<td width="190" class="check_td_right"><?=$relationship_with_insured_str?></td>


<td width="150" class="check_td_left">被保人姓名：</td>
<td width="190" class="check_td_right"><?=$insurant_info['fullname']?></td>


</tr>

<tr><td width="150" class="check_td_left">证件类型：</td>
<td width="190" class="check_td_right"><?=$insurant_info['certificates_type']?></td>


<td width="150" class="check_td_left">证件号：</td>
<td width="190" class="check_td_right"><?=$insurant_info['certificates_code']?></td>


</tr>

<tr><td width="150" class="check_td_left">性别：</td>
<td width="190" class="check_td_right"><?=$insurant_info['gender']?></td>


<td width="150" class="check_td_left">出生日期：</td>
<td width="190" class="check_td_right"><?=$insurant_info['birthday']?></td>


</tr>

</tr>

<tr><td width="150" class="check_td_left">手机号：</td>
<td width="190" class="check_td_right"><?=$insurant_info['mobiletelephone']?></td>


<td width="150" class="check_td_left">电子邮箱：</td>
<td width="190" class="check_td_right"><?=$insurant_info['email']?></td>


</tr>	


<tr>
<td width="150" class="check_td_left">省：</td>
<td width="190" class="check_td_right"><?=$insurant_info['province']?></td>


<td width="150" class="check_td_left">市：</td>
<td width="190" class="check_td_right"><?=$insurant_info['city']?></td>


</tr>

<tr>
<td width="150" class="check_td_left">通讯地址：</td>
<td width="190" class="check_td_right"><?=$insurant_info['address']?></td>


<td width="150" class="check_td_left">邮政编码：</td>
<td width="190" class="check_td_right"><?=$insurant_info['zipcode']?></td>


</tr>	



</tbody>
</table>





<div class="x_p_a_c c">
                  			<h2>身故受益人信息</h2></div>
                  			<table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0;">
<tbody><tr>
                             <td width="150" class="check_td_left">法定</td>
                             <td width="617" class="check_td_right"></td>
                       		  </tr>
                         </tbody>
 </table>









<!--健康告知-->

<div class="insure_cen_title"><h1>其它信息</h1></div>

<?php if($product['product_code']=="2101700000000009") { ?>

<table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">

 <tbody>
<tr>
<td width="150" class="check_td_left">订票日期：</td>
<td width="190" class="check_td_right"><?=$policy_arr['orderDate']?></td>


<td width="150" class="check_td_left">航班号或火车班次 ：</td>
<td width="190" class="check_td_right"><?=$policy_arr['flightNo']?></td>


</tr>	

<tr>
<td width="150" class="check_td_left">出发地：</td>
<td width="190" class="check_td_right"><?=$policy_arr['flightFrom']?></td>


<td width="150" class="check_td_left">目标地：</td>
<td width="190" class="check_td_right"><?=$policy_arr['flightTo']?></td>


</tr>	


<tr>
<td width="150" class="check_td_left">出发时间：</td>
<td width="190" class="check_td_right"><?=$policy_arr['takeoffDate']?></td>


<td width="150" class="check_td_left">到达时间：</td>
<td width="190" class="check_td_right"><?=$policy_arr['landDate']?></td>


</tr>	


<tr>
<td width="150" class="check_td_left">电子票号：</td>
<td width="190" class="check_td_right"><?=$policy_arr['eTicketNo']?></td>


<td width="150" class="check_td_left">PNR码：</td>
<td width="190" class="check_td_right"><?=$policy_arr['pnrCode']?></td>


</tr>	


<tr>
<td width="150" class="check_td_left">机票金额：</td>
<td width="190" class="check_td_right"><?=$policy_arr['ticketAmount']?></td>


<td width="150" class="check_td_left">签约旅行社：</td>
<td width="190" class="check_td_right"><?=$policy_arr['travelAgency']?></td>


</tr>	



</tbody>
</table>

<?php } ?>


<div class="x_p_a_c c"><h2>保单信息</h2></div>
<table cellpadding="0" cellspacing="0" >


<tbody>
<tr>
<td width="176" class="insure_cen_table_td insure_cen_table_das">保险期限：</td>
<td width="605" class="check_td_right" style="font-weight:bolder">

<?=$policy_arr['start_date']?>&nbsp;&nbsp;至&nbsp;&nbsp;<?=$policy_arr['end_date']?>

</td>
</tr>
    </tbody>
</table>			

<div class="x_p_a_c c"><h2>保障内容</h2></div>
<table cellpadding="0" cellspacing="0" width="700">			

<tbody>
<tr>
<td style="text-align: center; width:200px; font-weight:bolder; font-size:14px;"><strong>保险责任</strong></td>
<td style="text-align: center;font-weight:bolder;font-size:14px;"><strong>保险金额</strong></td>
</tr>

<?php if(is_array($product_duty_list)) { foreach($product_duty_list as $key => $value) { ?>
<tr>
<td class="check_td_left"><?=$value['duty_name']?></td>
            <td class="check_td_right"><?=$value['amount']?></td>
</tr>
<?php } } ?>

     
    </tbody>
</table>


<?php } elseif($insurer_code == "HTS") { ?>   

   			
<div class="x_p_a_c c"><h2>投保人信息</h2></div>
<!--保险计划-->
<!--投保人信息-->
 <table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">
<tbody><tr>

<td width="150" class="check_td_left">投保人姓名：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['fullname']?></td>


<td width="150" class="check_td_left">英文名：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['fullname_english']?></td>


</tr>

<tr><td width="150" class="check_td_left">证件类型：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['certificates_type']?></td>


<td width="150" class="check_td_left">证件号：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['certificates_code']?></td>


</tr>

<tr><td width="150" class="check_td_left">性别：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['gender']?></td>


<td width="150" class="check_td_left">出生日期：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['birthday']?></td>


</tr>

<tr><td width="150" class="check_td_left">手机号：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['mobiletelephone']?></td>


<td width="150" class="check_td_left">电子邮箱：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['email']?></td>


</tr>

<tr><td width="150" class="check_td_left">通讯地址：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['address']?></td>


<td width="150" class="check_td_left">邮政编码：</td>
<td width="190" class="check_td_right"><?=$user_info_applicant['zipcode']?></td>


</tr>

</tbody></table>
<div class="x_p_a_c c">
                  			<h2>被保人信息</h2>
                  		</div>
                  		
                  <table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">
<tbody><tr>

<td width="150" class="check_td_left">被保人是投保人：</td>
<td width="190" class="check_td_right"><?=$relationship_with_insured_str?></td>


<td width="150" class="check_td_left">被保人姓名：</td>
<td width="190" class="check_td_right"><?=$insurant_info['fullname']?></td>


</tr>

<tr><td width="150" class="check_td_left">证件类型：</td>
<td width="190" class="check_td_right"><?=$insurant_info['certificates_type']?></td>


<td width="150" class="check_td_left">证件号：</td>
<td width="190" class="check_td_right"><?=$insurant_info['certificates_code']?></td>


</tr>

<tr><td width="150" class="check_td_left">性别：</td>
<td width="190" class="check_td_right"><?=$insurant_info['gender']?></td>


<td width="150" class="check_td_left">出生日期：</td>
<td width="190" class="check_td_right"><?=$insurant_info['birthday']?></td>


</tr>



<tr><td width="150" class="check_td_left">手机号：</td>
<td width="190" class="check_td_right"><?=$insurant_info['mobiletelephone']?></td>


<td width="150" class="check_td_left">电子邮箱：</td>
<td width="190" class="check_td_right"><?=$insurant_info['email']?></td>


</tr>

</tbody></table>




<div class="x_p_a_c c">
                  			<h2>身故受益人信息</h2></div>
                  			<table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">
<tbody><tr>
                             <td width="150" class="check_td_left">法定</td>
                             <td width="617" class="check_td_right"></td>
                       		  </tr>
                         </tbody></table>









<!--健康告知-->



<!--附加信息-->

<div class="x_p_a_c c"><h2>其它信息</h2></div>
<table width="785" cellpadding="0" cellspacing="1" style="padding:15px 0">

<tbody><tr>
<td width="150" class="check_td_left">出行目的</td>
<td width="617" class="check_td_right"><?=$policy_arr['purpose']?></td>
</tr>

<tr>
<td width="150" class="check_td_left">出行目的地</td>
<td width="617" class="check_td_right"><?=$policy_arr['destination_country']?></td>
</tr>

<tr>
<td width="150" class="check_td_left">签证办理城市</td>
<td width="617" class="check_td_right"><?=$policy_arr['visacity']?></td>
</tr>

</tbody></table>

<!--健康告知-->


<div class="x_p_a_c c"><h2>保单信息</h2></div>
<table cellpadding="0" cellspacing="0">

<tbody>
<tr>
<td width="176" height="45" align="center" class="insure_cen_table_td insure_cen_table_das">保险期限：</td>

<td width="605" class="check_td_right" style="font-weight:bolder">

<?=$policy_arr['start_date']?>&nbsp;&nbsp;至&nbsp;&nbsp;<?=$policy_arr['end_date']?>

</td>
</tr>
</tbody>

</table>	

<div class="x_p_a_c c"><h2>保障内容</h2></div>
<table cellpadding="0" cellspacing="0" width="700">

<tbody>		
        <tr>
            <td style="text-align: center; width:200px; font-weight:bolder; font-size:14px;"><strong>保险责任</strong></td>
            <td style="text-align: center;font-weight:bolder;font-size:14px;"><strong>保险金额</strong></td>
        </tr>

<?php if(is_array($product_duty_list)) { foreach($product_duty_list as $key => $value) { ?>
        <tr>
            <td class="check_td_left"><?=$value['duty_name']?></td>
            <td class="check_td_right"><?=$value['amount']?></td>
        </tr>
<?php } } ?>

     
    </tbody>
</table>




   
    <?php } ?>

<div class="x_p_a_c c"><h2>保险费用</h2></div>
<table>
<tbody><tr width="785">
<td>
<div class="order_cost" style="color:#C33;">
<div id="newstat" class="cost_num_wap2">


<p>￥
<span style="font-size:20px;">

<!--保费-->
<strong>
<?=$policy_arr['total_premium']?>
</strong>元
</span>
<!--
<span class="sub">
（您本次可以获得<font color="#3399FF">10.0</font>积分）
</span>
-->
</p>
<input id="reprice" type="hidden" value="200.00">
</div>
</div></td><td>

</td></tr>
</tbody>
</table>



<div class="x_p_a_c c"><h2>投保声明</h2></div>

<div style=" padding:25px 0px 0px 25px; text-align:left;">
 <?=$product_attribute_arr['insurance_declare']?>
</div>

<div class="state_list" style="border:1px solid #ddd;padding-left:10px;">

<label>
<input type="checkbox" id="readerId" name="reader" value="true">&nbsp;我已认真阅读并同意以上投保声明
</label>



<!--重要通知-->
<div class="order_state">
<div class="order_btn" align="left">


<div class="order_btn" align="center">
<div id="sub1">
<img onclick="orderCheckSubmit(this,2);" src="../images/icon15.jpg" style="cursor:pointer;">

</div>
<div id="sub2" style="display:none;">
<div><img src="../images/big_load.gif" style="cursor:pointer;" width="181" height="47"></div>
<div><font color="#CC3333">正在与保险公司通讯中，请稍等(请不要刷新浏览器)</font></div>
</div>
<!-- 立即购买 -->
</div>
</div>
</div>
</div>

</div>

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