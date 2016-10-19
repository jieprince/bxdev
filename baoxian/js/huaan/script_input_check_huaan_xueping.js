var  $j = jQuery.noConflict();

$j(document).ready(function(){
	 var temp_html = {};
	 var periodtemp = $('#period').val();
	 var careertemp = $('#career').val();
	 $('#period_temp').html(periodtemp+'个月');
	 if(careertemp==1){
		$('#career_temp').html('1~3类');	 
	 }else if(careertemp==4){
		$('#career_temp').html('4类');	 
	 }
	 temp_html = {'id':2,'content':$('#applicant_info_group').html()};
	 $('#applicant_info_group').html('');
	 $j("input[name=applicant_type]").change(function () {
			  
             var $selectedvalue = $j("input[name=applicant_type]:checked").val();
			 
             //alert($selectedvalue);
             if ($selectedvalue == 1) //single
             {
				
				 if(temp_html.id==1){
					 $j("#applicant_info_single").html(temp_html.content);
				 } 
                 $j("input#businessType").val(1);
				 $j("#applicant_info_group").hide();
                 $j("#applicant_info_single").show();
                 $('#more_upload_list').hide();
		 		 $("#as_temps").show();
                 $j("select#relationshipWithInsured").val("601019");
				 
				 var temp_html_c = $('#applicant_info_group').html();
				 if(temp_html_c!=""){
					temp_html = {'id':2,'content':$('#applicant_info_group').html()};
					$('#applicant_info_group').html('');
				 }
				 
                 //$j("select#relationshipWithInsured").attr("readonly",false);
                 $('#commit_type_id').val(''); 
				 
             }
             else//group
             {
				 if(temp_html.id==2){
					 $j("#applicant_info_group").html(temp_html.content);
				 } 
				 $('#commit_type_id').val('tuandan');
                 $j("input#businessType").val(2);
				 $j("#applicant_info_single").hide();
                 $j("#applicant_info_group").show();
                 
                 $j("select#relationshipWithInsured").val("601019");//其他
                 //$j("select#relationshipWithInsured").attr("disable",true);
                 //$j("select#relationshipWithInsured").selectReadOnly();
                 $j("#as_temps").hide();
				 $('#more_upload_list_title').hide();
				 $('#more_upload_list').show();
				 var temp_html_cl = $('#applicant_info_single').html();
				 if(temp_html_cl!=""){
					temp_html = {'id':1,'content':$('#applicant_info_single').html()};
					$('#applicant_info_single').html('');
				 }
             }
			 //��ݱ����յĺ�Ͷ����֮ǰ�Ĺ�ϵ���õ����֤����
			$j("select#relationshipWithInsured").change(function(){
					
					var val_this = $j(this).val();
					if(val_this=='601005')
					{
						
						$j("#div_assured").hide();
					}
					else
					{
						$j("#div_assured").show();
					}
					
				
			 });
			
			 $j("select#applicant_certificates_type").val("120001");
			  $j("select#assured_certificates_code").val("120001");
			  $j("select#relationshipWithInsured").val("601019");
         });
	 
	    //��ݱ����յĺ�Ͷ����֮ǰ�Ĺ�ϵ���õ����֤����
	$j("select#relationshipWithInsured").change(function(){
			
			var val_this = $j(this).val();
			if(val_this=='601005')
			{
				$j("#div_assured").hide();
			}
			else
			{
				$j("#div_assured").show();
			}
			
		
	 });
	
	 $j("select#applicant_certificates_type").val("120001");
	  $j("select#assured_certificates_code").val("120001");
	  $j("select#relationshipWithInsured").val("601019");
	 
	  $('#applicant_fullname').change(function(){
			$('#temp_name_tbr').text($(this).val());  
		  
	 });
	
	//������֤�����ȡ���Ա������
	$j("input#applicant_certificates_code").change(function(){
			
			var UUserCard = $j(this).val(); 
			
			
			var type = $j("select#applicant_certificates_type").val();
			$('#temp_cart_type_tbr').text($j("select#applicant_certificates_type option:selected").text());
			$('#temp_cart_num_tbr').text(UUserCard);
			//alert(type);
			
			if(type !="120001" || !UUserCard)
			{
				 return;
			}
			
			if (parseInt(UUserCard.substr(16, 1)) % 2 == 1) 
			{
				sex = "M";
			//��
			} 
			else
			{
				sex = "F";
			//Ů
			} 
			
			//sex = "F";
			//alert(sex);
			
			if(sex == "M")
			{
				$j("input#applicant_sex_man").attr("checked",true);
				//$j("input#applicant_sex_man").removeAttr("checked");
			}
			else
			{
				$j("input#applicant_sex_woman").attr("checked",true);
				//$j("input#applicant_sex_woman").removeAttr("checked");
			}
			
			
			var birthday = UUserCard.substring(6, 10) + "-" + UUserCard.substring(10, 12) + "-" + UUserCard.substring(12, 14); 
			
			//alert(birthday);
			$j("input#applicant_birthday").val(birthday);
 	});
	
	
	$j("input#assured_certificates_code").change(function(){
			
			var UUserCard = $j(this).val(); 
			
			var type = $j("select#assured_certificates_type").val();
			if(type !="120001" || !UUserCard)
			{
				 return;
			}
			
			
			
			if (parseInt(UUserCard.substr(16, 1)) % 2 == 1) 
			{
				sex = "M";
			//��
			} 
			else
			{
				sex = "F";
			//Ů
			} 
			
			//sex = "F";
			//alert(sex);
			
			if(sex == "M")
			{
				$j("input#assured_sex_man").attr("checked",true);
				//$j("input#applicant_sex_man").removeAttr("checked");
			}
			else
			{
				$j("input#assured_sex_woman").attr("checked",true);
				//$j("input#applicant_sex_woman").removeAttr("checked");
			}
			
			
			var birthday = UUserCard.substring(6, 10) + "-" + UUserCard.substring(10, 12) + "-" + UUserCard.substring(12, 14); 
			
			//alert(birthday);
			$j("input#assured_birthday").val(birthday);
 	});
	
	
	//���Ͷ������õ��ܷ���
	$j("select#applyNum").change(function(){
		 											
	     var applyNum = $j(this).val();
		 
		 var premium =  $j("input#premium").val();
		 
		 var totalpremium = accMul(premium,applyNum);
		 if($('input[name="assured_input_type"]:checked').val()==2){
			 var length_posion_table = $('#ps_list table tr').length-1;
			 var poidTemp = $('#bat_post_policy').val();
			 if(poidTemp!=2){ //平安 除Y502产品以外的批量计算
				 length_posion_table = parseInt(length_posion_table/2);
			 }
			 totalpremium = accMul(length_posion_table,totalpremium);
	     }
		 $j("span#showprice").html(totalpremium);
		 $j("input#totalModalPremium").val(totalpremium);
		 
		 //alert(totalpremium);
																   
	 });	


    //http://api.jqueryui.com/datepicker/
    $j.datepicker.regional["zh-CN"] = {closeText: "关闭", prevText: "&#x3c;上月", nextText: "下月&#x3e;", currentText: "今天", monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"], monthNamesShort: ["一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二"], dayNames: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"], dayNamesShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"], dayNamesMin: ["日", "一", "二", "三", "四", "五", "六"], weekHeader: "周", dateFormat: "yy-mm-dd", firstDay: 1, isRTL: !1, showMonthAfterYear: !0, yearSuffix: "年"}
    $j.datepicker.setDefaults($j.datepicker.regional["zh-CN"]);
    var start_day = $j("input#start_day").val();
    if(start_day==0)
    {
    	start_day = 6;
    }
     
    //start add by wangcya , 20141121,服务器时间
    //var date = new Date($.ajax({async: false}).getResponseHeader("Date"));
    var date = $("#server_time").val();
	  
    date = parseInt(date);
	  
    var bombay = date + (3600 * 24)*start_day;
    //alert("bombay: "+bombay);
	
     var time = new Date(bombay*1000);
    //alert("time: "+time);
	
    time = time.getFullYear()+"-"+(time.getMonth()+1)+"-"+time.getDate();
    //alert("after time: "+time);
    //end add by wangcya , 20141121,服务器时间
    
	 var pickerOpts = {
         changeMonth: true,
         changeYear: true,
         dateFormat:'yy-mm-dd',
		 minDate: time,//start_day
		 onSelect: function(dateText, inst) { 
			var period = $j("input#period").val();
			var t = 0; 
			if($('#product_code').val()=='Y502'){	 
				t = monthStamp2String(dateText, period);
			}else{
				t = timeStamp2String(dateText, period);	
			}
			//alert(t);
			$j("#endDate").val(t);
			var startdate_old = $j("#startDate").val();
			$j("#startDate").val(startdate_old+" 00:00:00");
		} 
   };
	
        //��������ѡ����
     
	 $j("#startDate").datepicker(pickerOpts); 
	 
	 ///////////////////////////////////////////////////////////////


	 ///////////////////////////////////////////////////
	 function check_buy_submit_huaan_xueping()
	 {		
		    //购买信息检查
	        if (!$j("input#startDate").val())
	        {
	            alert("保险起期没填写！");
	            window.setTimeout("document.getElementById('startDate').focus();", 0)  ; 
	            return false;
	        }
			
	        if (!$j("input#endDate").val())
	        {
	            alert("保险止期没填写！");
	            window.setTimeout("document.getElementById('endDate').focus();", 0)  ; 
	            return false;
	        }
	        //投保人信息检查
	        /*
	        else if (!$j("input#applicant_fullname").val())
	        {
	            alert("投保人姓名没填写！");
	            window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
	            return false;
	        }
	        */
	        else if (!check_applicant_name())
	        {
				alert("投保人姓名没填写！");
	        	 window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
		         return false;
	        }
	        		
	        else if (!$j("select#applicant_certificates_type").val())
	        {
	            alert("投保人证件类型没选择！");
	            window.setTimeout("document.getElementById('applicant_certificates_type').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_card())
	        {
	            alert("投保人证件号码没填写！");
	            window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_birthday())
	        {
	            alert("投保人生日没填写！");
	            window.setTimeout("document.getElementById('applicant_birthday').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_sex())
	        {
	            alert("投保人性别没选择！");
	            window.setTimeout("document.getElementById('applicant_sex').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_mobilephone())
	        {
	            alert("投保人手机号没填写！");
	            window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_email())
	        {
	            //alert("投保人email没填写！");
	            window.setTimeout("document.getElementById('applicant_email').focus();", 0)  ; 
	            return false;
	        }
	         
	      //  alert($j("select#relationshipWithInsured").val());
	        var age_min = $j("input#age_min").val()+"y";
	        var age_max = $j("input#age_max").val()+"y";
			   
		   if($j("select#relationshipWithInsured").val()=='601005')//本人
		   { 
			   var birthday_applicant = document.getElementById("applicant_birthday").value;
			   document.getElementById("assured_birthday").value = birthday_applicant;
			   $('#assured_fullname').val($('#applicant_fullname').val());
			   $('#assured_certificates_type').val($('#applicant_certificates_type').val());
			   $('#assured_certificates_code').val($('#applicant_certificates_code').val());
			   $('#as_temps input[name="assured[0][assured_sex]"][value="'+$('#applicant_info_single input[name="applicant_sex"]:checked').val()+'"]').attr('checked',true);
			   
		       if (!check_assured_birthday(age_min,age_max))
		        {
		            alert("被保险人年龄不在投保范围内！");
		            window.setTimeout("document.getElementById('applicant_birthday').focus();", 0)  ; 
		            return false;
		        }
		        
		      
		   }
		   else
		   {
			   if (!check_assured_name())
		        {
		            alert("被保险人姓名没填写！");
		            window.setTimeout("document.getElementById('assured_fullname').focus();", 0)  ; 
		            return false;
		        }
			
		        else if (!$j("select#assured_certificates_type").val())
		        {
		            alert("被保险人证件证件类型没有选择！");
		            window.setTimeout("document.getElementById('assured_certificates_type').focus();", 0)  ; 
		            return false;
		        }
		        else if (!check_assured_card())
		        {
		            alert("被保险人证件号码没填写！");
		            window.setTimeout("document.getElementById('assured_certificates_code').focus();", 0)  ; 
		            return false;
		        }
				
		        else if (!check_assured_birthday(age_min,age_max))
		        {
		            alert("被保险人年龄不在投保范围内！");
		            window.setTimeout("document.getElementById('assured_birthday').focus();", 0)  ; 
		            return false;
		        }
		        else if (!check_assured_sex())
		        {
		            alert("被保险人性别没选择！");
		            window.setTimeout("document.getElementById('assured_sex').focus();", 0)  ; 
		            return false;
		        }
		        
		   }
		   if(!check_applicant_null('school_name')){
			    alert("被保险人学校没选择！");
				window.setTimeout("document.getElementById('school_name').focus();", 0)  ; 
				return false;
		   }
		   var city_temp = $('#city_temp option:selected').text();
		   var county_temp = $('#county_temp option:selected').text();
		   var school_nametemp = $('#school_name').val();
		   $('#assured_school').val(city_temp+county_temp+school_nametemp);
		   
			return  true;
	 }
	 
	 /////////////////////////////////////////////////////
	 $j("a#btnNext").click(function(){
		  
		 var res = '';
		//if($('#businessType').val()==2)
		if($('#wenxun_checjbox input[name="wenxun_2_checjbox"]:checked').val()!=0){
			layer.alert('投保问询中被保险人有疾病不可以投保!');
			$('.xubox_layer').css('top',"200px");
			return false;
		}
		if($('#bat_post_policy').val()==1)//批量投保
		{
			res = check_buy_submit_huaan_xueping_group();
		}
		else
		{  
			var str_applicant_type = $('#applicant_type_s_g input[name="applicant_type"]:checked').val(); 
			if(str_applicant_type==1){  //个人
				res = check_buy_submit_huaan_xueping();
			}else{  //机构
				res = check_buy_submit_huaan_xueping_group();	
			}
			
		}
		
		if(!res||res ==null||res == "")
		{
			return false;
		}
		
		///////////////////////////////////////////////////
	 	var val=$j('input:radio[name="radiobutton"]:checked').val();
		if(val==null)
		{
			alert("什么也没选中!");
			return false;
		}
		else if(val!="yes")
		{
			alert("您还没选择同意声明！");
			return false;
		}
	   //////////////////////////////////////
	   $j("input#submit1").click();

	 });
	 
	 /*
	 
	    $j("form#inputForm").submit(function() {
                alert("submit");
                  return true;
            });
       */
     	 
	 
 });// ready
 
 //检查批量投保，也可以允许个人投保
function check_buy_submit_huaan_xueping_group()
{
	var applicant_type = $("input[name=applicant_type]:checked").val();
	
	if (!$j("#startDate").val())
	{
	            alert("保险起期没填写！",9,'温馨提示');
	            window.setTimeout("document.getElementById('startDate').focus();", 0)  ; 
	            return false;
	}
	else if (!$j("#endDate").val())
	{
	            layer.alert("请填写保险止期！",9,'温馨提示');
				$('.xubox_layer').css('top',"200px");
	            window.setTimeout("document.getElementById('endDate').focus();", 0)  ; 
	            return false;
	}
	var minage = $('#age_min').val();
	var maxage = $('#age_max').val();
	//alert("applicant_type: "+applicant_type);
	if(applicant_type ==2 && $('#bat_post_policy').val()!=1)  //机构
	{//group
		if(!checkgroupname())
		{
					 layer.alert("请填写投保人机构名称",9,'温馨提示');	
					 $('.xubox_layer').css('top',"200px");
					window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
					return false;
		}
		else if(!check_applicant_card())
		{
					 layer.alert("请填写投保人证件号码",9,'温馨提示');
					 $('.xubox_layer').css('top',"200px");	
					window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0)  ;	
					return false;
		}
		else if(!check_applicant_mobilephone('applicant_mobilephone'))
		{
					 layer.alert("请填写投保人手机号码",9,'温馨提示');	
					 $('.xubox_layer').css('top',"200px");
					window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0)  ;	
					return false;
		}
		else if(!check_applicant_email('applicant_email'))
		{
			 layer.alert("请填写投保人电子邮箱",9,'温馨提示');	
			 $('.xubox_layer').css('top',"200px");
			window.setTimeout("document.getElementById('applicant_email').focus();", 0)  ;	
			return false;
		} 
		
		//最后的人员修改整体验证 
		 
		if(checkedAll()==false)
		{  //验证上传文件
			 if(applicant_type ==2 && $('#bat_post_policy').val()!=1){
				 layer.alert('验证失败可能原因如下：<br>1.没有上传excel文件<br/>2.上传人员数大于1小于3000<br>3.上传人员名单中证件类型、证件号码无效<br>4.投保人大于18岁,<br>5.被保人年龄不在投保范围之内('+minage+' 至 '+maxage+'岁)<br><br>请核实后再次提交!',9,'温馨提示');
			}else{
				 
				layer.alert('验证失败可能原因如下：<br>1.投保人类型、身份类型、身份证件号码无效,<br>2.投保人年龄不在投保范围之内(18 至65岁)<br>3.被保险人员数小于1人或者大于3000人<br>4.被保险人名单中重复的证件号码、手机号、邮箱<br>5.被保险人身份证件号码无效<br>6.被保险人年龄不在投保范围之内('+minage+' 至 '+maxage+'岁)<br>7.被保险人学校名称没有填写<br>请核实后再次提交!',9,'温馨提示');
			}
			$('.xubox_layer').css('top',"200px");
			return false;	
		}
		else
		{
			return true;
		}
	}else if($('#bat_post_policy').val()==1){
		//最后的人员修改整体验证 

		if(checkedAll_New()==false)
		{  //验证上传文件
			 if(applicant_type ==2 && $('#bat_post_policy').val()!=1){
				 layer.alert('验证失败可能原因如下：<br>1.没有上传excel文件<br/>2.上传人员数大于1小于3000<br>3.上传人员名单中证件类型、证件号码无效<br>4.投保人年龄不在投保范围之内(18 至65岁),<br>5.被保人年龄不在投保范围之内('+minage+' 至 '+maxage+'岁)<br><br>请核实后再次提交!',9,'温馨提示');
			}else{
				 
				layer.alert('验证失败可能原因如下：<br>1.投保人类型、身份类型、身份证件号码无效,<br>2.投保人年龄不在投保范围之内(18 至65岁)<br>3.被保险人员数小于1人或者大于3000人<br>4.被保险人名单中重复的证件号码、手机号、邮箱<br>5.被保险人身份证件号码无效<br>6.被保险人年龄不在投保范围之内('+minage+' 至 '+maxage+'岁)<br>7.被保险人学校名称没有填写<br>请核实后再次提交!',9,'温馨提示');
				
			}
			$('.xubox_layer').css('top',"200px");
			return false;	
		}
		else
		{
			return true;
		}
		
	}
	 
		
	
	
}
 
 
 
 