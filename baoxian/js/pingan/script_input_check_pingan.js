var  $j = jQuery.noConflict();

$j(document).ready(function(){
	 
	 var periodtemp = $('#period').val();
	 var careertemp = $('#career').val();
	 $('#period_temp').html(periodtemp+'个月');
	 if(careertemp==1){
		$('#career_temp').html('1~3类');	 
	 }else if(careertemp==4){
		$('#career_temp').html('4类');	 
	 }
	 
	 
	 //��ݱ����յĺ�Ͷ����֮ǰ�Ĺ�ϵ���õ����֤����
	$j("select#relationshipWithInsured").change(function(){
			
			var val_this = $j(this).val();
			if(val_this==1)
			{
				$j("#div_assured").hide();
			}
			else
			{
				$j("#div_assured").show();
			}
			
		
	 });
	
	 $j("select#applicant_certificates_type").val("01");
	  $j("select#assured_certificates_code").val("01");
	  $j("select#relationshipWithInsured").val("1");
	  
	
	//������֤�����ȡ���Ա������
	$j("input#applicant_certificates_code").change(function(){
			
			var UUserCard = $j(this).val(); 
			
			
			var type = $j("select#applicant_certificates_type").val();
			//alert(type);
			
			if(type !="01" || !UUserCard)
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
			if(type !="01" || !UUserCard)
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
	 function check_buy_submit_pingan()
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
	        	 window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
		         return false;
	        }
	        		
	        else if (!$j("select#applicant_certificates_type").val())
	        {
	            alert("投保人证件类型没填写！");
	            window.setTimeout("document.getElementById('applicant_certificates_type').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_card())
	        {
	            //alert("投保人证件号码没填写！");
	            window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_birthday())
	        {
	            //alert("投保人生日没填写！");
	            window.setTimeout("document.getElementById('applicant_birthday').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_sex())
	        {
	            //alert("投保人生日没填写！");
	            window.setTimeout("document.getElementById('applicant_sex').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_mobilephone())
	        {
	            //alert("投保人手机号没填写！");
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
			   
		   if($j("select#relationshipWithInsured").val()==1)//本人
		   {
			 
			   
			   var birthday_applicant = document.getElementById("applicant_birthday").value;
			   document.getElementById("assured_birthday").value = birthday_applicant;
			   
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
		           // alert("被保险人姓名没填写！");
		            window.setTimeout("document.getElementById('assured_fullname').focus();", 0)  ; 
		            return false;
		        }
			
		        else if (!$j("select#assured_certificates_type").val())
		        {
		            //alert("被保险人证件证件类型没填写！");
		            window.setTimeout("document.getElementById('assured_certificates_type').focus();", 0)  ; 
		            return false;
		        }
		        else if (!check_assured_card())
		        {
		            //alert("被保险人证件号码没填写！");
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
		            //alert("被保险人生日没填写！");
		            window.setTimeout("document.getElementById('assured_sex').focus();", 0)  ; 
		            return false;
		        }
		        else if (!check_assured_mobile_phone())
		        {
		            //alert("被保险人生日没填写！");
		            window.setTimeout("document.getElementById('assured_mobilephone').focus();", 0)  ; 
		            return false;
		        }	
		        else if (!check_assured_email())
		        {
		            //alert("被保险人生日没填写！");
		            window.setTimeout("document.getElementById('assured_email').focus();", 0)  ; 
		            return false;
		        }	
		   }
		   
		   if($j("input#attribute_type").val()=="jingwailvyou" || $j("input#attribute_type").val()=="jingwailvyouhuodong" )
		   {
			   //出行目的
			   /*if(!checkAllEditTag_Null('outgoingPurpose'))
			   {
		             
		            window.setTimeout("document.getElementById('outgoingPurpose').focus();", 0)  ; 
		            return false;
			   }*/
			   
			   
			   if(!checkAllEditTag_Null('destinationCountry'))
			   {
		            
		            window.setTimeout("document.getElementById('destinationCountry').focus();", 0)  ; 
		            return false;
			   }else if($('#destinationCountry').val()=='其他'){
                   if($.trim($('#select_other_cou').val())==""){
                       alert("请填写目的地国家！");
                       window.setTimeout("document.getElementById('select_other_cou').focus();", 0)  ;
                       return false;
                   }

               }

		   }
		  
		   
		
			return  true;
	 }
	 
	 /////////////////////////////////////////////////////
	 $j("a#btnNext").click(function(){
		  
		 var res = '';
		//if($('#businessType').val()==2)
		if($('#bat_post_policy').val()==2)//团单方式投保
		{//Y502
			res = check_buy_submit_pinganYMain();
		}
		else if($('#bat_post_policy').val()==1)//批量投保
		{
			res = check_buy_submit_pingan_group();
		}
		else
		{
			res = check_buy_submit_pingan();
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
function check_buy_submit_pingan_group()
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
	            window.setTimeout("document.getElementById('endDate').focus();", 0)  ; 
	            return false;
	}
	
	//alert("applicant_type: "+applicant_type);
	if(applicant_type ==2)
	{//group
		if(!checkgroupname())
		{
					 layer.alert("请填写投保人机构名称",9,'温馨提示');	
					window.setTimeout("document.getElementById('applicant_group_name').focus();", 0)  ; 
					return false;
		}
		else if(!check_applicant_card(6))
		{
					 layer.alert("请填写投保人证件号码",9,'温馨提示');	
					window.setTimeout("document.getElementById('applicant_group_certificates_code').focus();", 0)  ;	
					return false;
		}
		else if(!check_applicant_mobilephone('applicant_group_mobilephone'))
		{
					 layer.alert("请填写投保人手机号码",9,'温馨提示');	
					window.setTimeout("document.getElementById('applicant_group_mobilephone').focus();", 0)  ;	
					return false;
		}
		else if(!check_applicant_email('applicant_group_email'))
		{
			 layer.alert("请填写投保人电子邮箱",9,'温馨提示');	
			window.setTimeout("document.getElementById('applicant_group_email').focus();", 0)  ;	
			return false;
		}
	}
	else if(applicant_type ==1)
	{//single

            if (!check_applicant_name())
	        {
	        	 window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
		         return false;
	        }
	        		
	        else if (!$j("select#applicant_certificates_type").val())
	        {
	            alert("投保人证件类型没填写！");
	            window.setTimeout("document.getElementById('applicant_certificates_type').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_card())
	        {
	            //alert("投保人证件号码没填写！");
	            window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_birthday())
	        {
	            //alert("投保人生日没填写！");
	            window.setTimeout("document.getElementById('applicant_birthday').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_sex())
	        {
	            //alert("投保人生日没填写！");
	            window.setTimeout("document.getElementById('applicant_sex').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_mobilephone())
	        {
	            //alert("投保人手机号没填写！");
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
	}//end single
		
	//最后的人员修改整体验证 

	if(checkedAll()==false)
	{  //验证上传文件
		
		 layer.alert('验证失败可能原因如下：<br>1.没有上传excel文件<br/>2.上传人员数小于5人<br>3.上传人员名单中重复的证件号码、手机号、邮箱<br>4.身份证件号码无效<br>5.被保人年龄不在投保范围之内(18 至 65岁)<br><br>请核实后再次提交!',9,'温馨提示');
		return false;	
	}
	else
	{
		return true;
	}
	
}
 //平安的Y502团购提交验证
 function check_buy_submit_pinganYMain(){
	 	 
		 if (!$j("#startDate").val()){
	            alert("保险起期没填写！",9,'温馨提示');
	            window.setTimeout("document.getElementById('startDate').focus();", 0)  ; 
	            return false;
	        }else if (!$j("#endDate").val())
	        {
	            layer.alert("请填写保险止期！",9,'温馨提示');
	            window.setTimeout("document.getElementById('endDate').focus();", 0)  ; 
	            return false;
	        }else if(!checkgroupname()){
				 layer.alert("请填写投保人机构名称",9,'温馨提示');	
				window.setTimeout("document.getElementById('applicant_group_name').focus();", 0)  ; 
				return false;
			}else if(!check_applicant_card(6)){
				 layer.alert("请填写投保人证件号码",9,'温馨提示');	
				window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0)  ;	
				return false;
			}else if(!check_applicant_name()){
				layer.alert("请审查联系人姓名是否有误",9,'温馨提示');
				window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
				return false;
			}else if(!check_applicant_mobilephone()){
				 layer.alert("请填写投保人手机号码",9,'温馨提示');	
				window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0)  ;	
				return false;
			}else if(!check_applicant_email()){
				 layer.alert("请填写投保人电子邮箱",9,'温馨提示');	
				window.setTimeout("document.getElementById('applicant_email').focus();", 0)  ;	
				return false;
			}
			//最后的人员修改整体验证 
			else if(checkedAll()==false){  //验证上传文件
				
				 layer.alert('验证失败可能原因如下：<br>1.没有上传excel文件<br/>2.上传人员数小于5人<br>3.上传人员名单中重复的证件号码、手机号、邮箱<br>4.身份证件号码无效<br>5.被保人年龄不在投保范围之内(18 至 65岁)<br><br>请核实后再次提交!',9,'温馨提示');
				return false;	
			}else{
				return true;
			}
		 
 }
 
 
 



 
 