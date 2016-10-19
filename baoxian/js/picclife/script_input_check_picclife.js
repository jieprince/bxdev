var  $j = jQuery.noConflict();
var cp_name_tempTTl = '';
$j(document).ready(function(){
	 
	 cp_name_tempTTl = $('#cp_name').val();
	 
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
			if(val_this==5)
			{
				$j("#div_assured").hide();
			}
			else
			{
				$j("#div_assured").show();
			}
	 });
	
	  $j("select#applicant_certificates_type").val("1");
	  $j("select#assured_certificates_code").val("1");
	  
	  
	  if(cp_name_tempTTl=='picclife_ttl'){
		  $j("select#relationshipWithInsured").val("2");
	  }else{
		  $j("select#relationshipWithInsured").val("5"); 
	  }
	
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
				sex = 1;
			//��
			} 
			else
			{
				sex = 2;
			//Ů
			} 
			
			//sex = "F";
			//alert(sex);
			
			if(sex == 1)
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
	/*
	$j("select#insureareaprov_code").change(function(){
    	   
    	 var provine_code = $j(this).val();
    	 
    	 var provine_name = $j(this).find("option:selected").text();
    	  
    	 $j("input#insureareaprov").val(provine_name);
    	 
		 $j.getJSON("cp.php", 
			 {
				 'ac': "product_buy_process_ajax_req",
				 op:"huatai_city",
				 provine_code:provine_code,
				 random: Math.random() 
			  }, 
			 function (data){

				  sl = $j("select#insureareacity_code");
				  sl.empty();
				  //sl.show();
				  
				  //	<option selected="selected" value="">请选择</option>
				  sl.append("<option selected=\"selected\" value='"+name+"'>"+"请选择城市"+"</option>");   //为Select追加一个Option(下拉项)
				
				  /////////////////////////////////////////////////////////////////
				  data = $j(data);
				  data.each(function () {
				  //var symptom = $j(this);
						var city = this;
						var id = city.BD_ID;
						var code = city.BD_Code;
						var name = city.BD_Name;
						//var code = this.BD_Code;
						
						if(id==1)
						{
							sl.append("<option value='"+code+"'>"+name+"</option>");   //为Select追加一个Option(下拉项)
						}
						//////////////////////////////////////////////////
					});//data.each

			  }
		 );//function (data)
   		  //});// getJSON
	
	});
	*/
	
	$j("input#assured_certificates_code").change(function(){
			
			var UUserCard = $j(this).val(); 
			
			var type = $j("select#assured_certificates_type").val();
			if(type !="01" || !UUserCard)
			{
				 return;
			}
			
			
			
			if (parseInt(UUserCard.substr(16, 1)) % 2 == 1) 
			{
				sex = 1;
			//��
			} 
			else
			{
				sex = 2;
			//Ů
			} 
			
			//sex = "F";
			//alert(sex);
			
			if(sex == 1)
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
		  //特殊处理
		   
		  if(cp_name_tempTTl=='picclife_ttl'){
			  $('#baoxianjine').text(totalpremium);  
		  }
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
	 
	 $('#beneficiary').change(function(){
		$('#beneficiary_info_value').val("");
		if($(this).val()==1){
			$('#beneficiary_list').hide();
			 
		}else if($(this).val()==0){
			 
			$('#beneficiary_list').show();
			 
		}
	}); 
	 
	 /////////////////////////////////////////////////////
	 $j("a#btnNext").click(function(){
		  
		 var res = '';
		if(cp_name_tempTTl=='picclife_ttl'){
			res = check_buy_submit_ttl();
		}else{
			res = check_buy_submit();
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
	   
	   $('#orderForm input:disabled').removeAttr("disabled");
	   $j("input#submit1").click();

	 });
	 
	 
 });// ready
 
//标准产品
function check_buy_submit(){
	//购买信息检查
	if (!$j("input#startDate").val())
	{
		 
		layer.alert("保险起期没填写！",9,'温馨提示');
		window.setTimeout("document.getElementById('startDate').focus();", 0)  ; 
		return false;
	}
	
	if (!$j("input#endDate").val())
	{
		layer.alert("保险止期没填写！",9,'温馨提示');
		window.setTimeout("document.getElementById('endDate').focus();", 0)  ; 
		return false;
	}
	if($('#bat_post_policy').val()==0){
			if (!check_cn_name('applicant_fullname',1))
			{
				 layer.alert("投保人姓名有误！",9,'温馨提示');
				 window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
				 return false;
			}
					
			else if (!$j("select#applicant_certificates_type").val())
			{
				 
				layer.alert("投保人证件类型没填写！",9,'温馨提示');
				window.setTimeout("document.getElementById('applicant_certificates_type').focus();", 0)  ; 
				return false;
			}
			else if (!check_All_card('applicant_certificates_type','applicant_certificates_code',1,true,'applicant_birthday','applicant_sex'))
			{
				 
				layer.alert("投保人证件号码有误！",9,'温馨提示');
				window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0)  ; 
				return false;
			}
			else if (!check_birthdays('applicant_birthday',1))
			{
				 
				layer.alert("投保人生日有误！",9,'温馨提示');
				window.setTimeout("document.getElementById('applicant_birthday').focus();", 0)  ; 
				return false;
			}
			else if ($('#orderForm input[name="applicant_sex"]:checked').val()=="")
			{ 
				layer.alert("投保人性别没有选择！",9,'温馨提示');
				window.setTimeout("document.getElementById('applicant_sex_man').focus();", 0)  ; 
				return false;
			}
			else if (!check_mobilephones('applicant_mobilephone'))
			{
				 
				layer.alert("投保人手机号有误！",9,'温馨提示');
				window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0)  ; 
				return false;
			}
			else if (!check_emails('applicant_email'))
			{
				layer.alert("投保人email有误！",9,'温馨提示');
				window.setTimeout("document.getElementById('applicant_email').focus();", 0)  ; 
				return false;
			}else if(!checkAll_Null('applicant_address')){
				layer.alert("投保人地址没填写！",9,'温馨提示');
				window.setTimeout("document.getElementById('applicant_address').focus();", 0)  ; 
				return false;
			}else if(!check_zipcode('applicant_zipcode')){
				layer.alert("投保人邮政编码有误！",9,'温馨提示');
				window.setTimeout("document.getElementById('applicant_zipcode').focus();", 0)  ; 
				return false;
			}
		
	  //  alert($j("select#relationshipWithInsured").val());
		//var age_min = $j("input#age_min").val()+"y";
		//var age_max = $j("input#age_max").val()+"y";
		   
		   if($j("select#relationshipWithInsured").val()==5)//本人
		   {
		
			   var birthday_applicant = document.getElementById("applicant_birthday").value;
			   document.getElementById("assured_birthday").value = birthday_applicant;
			   
			   if (!check_birthdays('assured_birthday',2))
				{
					 
					layer.alert("被保险人年龄不在投保范围内！",9,'温馨提示');
					window.setTimeout("document.getElementById('applicant_birthday').focus();", 0)  ; 
					return false;
				}
				
			  
		   }else{
			   
			   if (!check_cn_name('assured_fullname',2))
				{
				  
				   layer.alert("被保险人姓名有误！",9,'温馨提示');
					window.setTimeout("document.getElementById('assured_fullname').focus();", 0)  ; 
					return false;
				}
			
				else if (!$j("select#assured_certificates_type").val())
				{
					 
					layer.alert("被保险人证件证件类型没选择！",9,'温馨提示');
					window.setTimeout("document.getElementById('assured_certificates_type').focus();", 0)  ; 
					return false;
				}
				else if (!check_All_card('assured_certificates_type','assured_certificates_code',2,true,'assured_birthday','assured[0][assured_sex]'))
				{
					  
					layer.alert("被保险人证件号码有误！",9,'温馨提示');
					window.setTimeout("document.getElementById('assured_certificates_code').focus();", 0)  ; 
					return false;
				}
				
				else if (!check_birthdays('assured_birthday',2))
				{ 
					layer.alert("被保险人年龄不在投保范围内！",9,'温馨提示');
					window.setTimeout("document.getElementById('assured_birthday').focus();", 0)  ; 
					return false;
				}
				else if ($('#orderForm input[name="assured_sex"]:checked').val()=="")
				{
					 
					layer.alert("被保险人性别没选择！",9,'温馨提示');
					window.setTimeout("document.getElementById('assured_sex_man').focus();", 0)  ; 
					return false;
				}
				else if (!check_mobilephones('assured_mobilephone'))
				{
					 
					layer.alert("被保险人手机号有误！",9,'温馨提示');
					window.setTimeout("document.getElementById('assured_mobilephone').focus();", 0)  ; 
					return false;
				}	
				else if (!check_emails('assured_email'))
				{
					layer.alert("被保险人email有误！",9,'温馨提示');
					window.setTimeout("document.getElementById('assured_email').focus();", 0)  ; 
					return false;
				}else if(!checkAll_Null('assured_address')){
				layer.alert("被保险人地址没填写！",9,'温馨提示');
				window.setTimeout("document.getElementById('assured_address').focus();", 0)  ; 
				return false;
				}else if(!check_zipcode('assured_zipcode')){
					layer.alert("被保险人邮政编码有误！",9,'温馨提示');
					window.setTimeout("document.getElementById('assured_zipcode').focus();", 0)  ; 
					return false;
				}	
		   }
	}else if($('input[name="assured_input_type"]:checked').val()==2 && $('#bat_post_policy').val()==1){
	 
		if(checkedAll_New()==false){  //验证上传文件
			var sAge = parseInt($('#age_min').val());//被保险人最小年龄
			var eAge = parseInt($('#age_max').val());//被保险人最大年龄
			layer.alert('验证失败可能原因如下：<br>1.投保人类型、身份类型、身份证件号码无效,<br>2.投保人年龄不在投保范围之内(18 至 65岁)<br>3.被保险人员数小于1人或者大于3000人<br>4.被保险人名单中重复的证件号码、手机号、邮箱<br>5.被保险人身份证件号码无效<br>6.被保险人年龄不在投保范围之内('+sAge+' 至 '+eAge+'岁)<br><br>请核实后再次提交!');
			return false;
		}

    }
	 
	return  true;
}


//ttl 淘淘乐少儿重疾保险

function check_buy_submit_ttl(){
	//购买信息检查
	if (!$j("input#startDate").val())
	{
		 
		layer.alert("保险起期没填写！",9,'温馨提示');
		window.setTimeout("document.getElementById('startDate').focus();", 0)  ; 
		return false;
	}
	
	 
	else if (!check_cn_name('applicant_fullname',1))
	{
		 layer.alert("投保人姓名有误！",9,'温馨提示');
		 window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
		 return false;
	}
			
	else if (!$j("select#applicant_certificates_type").val())
	{
		 
		layer.alert("投保人证件类型没填写！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_certificates_type').focus();", 0)  ; 
		return false;
	}
	else if (!check_All_card('applicant_certificates_type','applicant_certificates_code',1,true,'applicant_birthday','applicant_sex'))
	{
		 
		layer.alert("投保人证件号码有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0)  ; 
		return false;
	}
	else if (!check_birthdays('applicant_birthday',1))
	{
		 
		layer.alert("投保人生日有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_birthday').focus();", 0)  ; 
		return false;
	}
	else if ($('#orderForm input[name="applicant_sex"]:checked').val()=="")
	{ 
		layer.alert("投保人性别没有选择！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_sex_man').focus();", 0)  ; 
		return false;
	}
	else if (!check_mobilephones('applicant_mobilephone'))
	{
		 
		layer.alert("投保人手机号有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0)  ; 
		return false;
	}
	else if (!check_emails('applicant_email'))
	{
		layer.alert("投保人email有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_email').focus();", 0)  ; 
		return false;
	}else if(!checkAll_Null('applicant_address')){
		layer.alert("投保人地址没填写！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_address').focus();", 0)  ; 
		return false;
	}else if(!check_zipcode('applicant_zipcode')){
		layer.alert("投保人邮政编码有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_zipcode').focus();", 0)  ; 
		return false;
	}else if (!check_cn_name('assured_fullname',2)){
	  
	   layer.alert("被保险人姓名有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_fullname').focus();", 0)  ; 
		return false;
	}else if (!$j("select#assured_certificates_type").val()){
		 
		layer.alert("被保险人证件证件类型没选择！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_certificates_type').focus();", 0)  ; 
		return false;
	}else if (!check_All_card('assured_certificates_type','assured_certificates_code',2,true,'assured_birthday','assured[0][assured_sex]')){
		  
		layer.alert("被保险人证件号码有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_certificates_code').focus();", 0)  ; 
		return false;
	}else if (!check_birthdays('assured_birthday',2)){ 
		layer.alert("被保险人年龄不在投保范围内！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_birthday').focus();", 0)  ; 
		return false;
	}else if ($('#orderForm input[name="assured_sex"]:checked').val()==""){
		 
		layer.alert("被保险人性别没选择！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_sex_man').focus();", 0)  ; 
		return false;
	}else if($('#beneficiary').val()==0){
		var checked_save_res = checked_save_beneficiary();
		if(checked_save_res.flag==false){
			layer.alert(checked_save_res.wrong_text,9,'温馨提示');
			var container = $('div'),scrollTo = $('#'+checked_save_res.domid);
			container.scrollTop(scrollTo.offset().top - container.offset().top + container.scrollTop());
			return false;
		}
	}
    
	return  true;
}


//删除受益人填写列表
function del_beneficiary(var_str){
	$('#beneficiary_cardValidExps_0'+var_str).unbind("click");
	$('#beneficiary_info_0'+var_str).remove();
}
//增加受益人列表
function add_beneficiary(){
	var data_value_len = $('#beneficiary_list_mode').children("div:last").data('value');
	var sum_len = $('#beneficiary_list_mode').children().length;
	var html_relation = '<option value="3">父母</option>';
	var html_certificates_type = '<option value="1">身份证</option><option value="2">军官证</option><option value="3">护照</option> <option value="7">户口本</option>';
	var cp_name_Temp = $('#cp_name').val();
	if(cp_name_Temp=='picclife_klwylqx'){
		html_relation = '<option value="1">身份证</option><option value="2">军官证</option><option value="3">护照</option><option value="4">出生证</option><option value="5">异常身份证</option><option value="6">回乡证</option><option value="7">户口本</option><option value="8">警官证</option><option value="9">其他</option>';
		html_certificates_type = '<option value="1">配偶</option><option value="2">子女</option><option value="3">父母</option>';
	}
	var str_small2bignum = '一';
	if(sum_len<=4){
		var st_len = 1;	
		if(data_value_len){
			st_len = parseInt(data_value_len)+1;
		}
		if(sum_len==1){
			str_small2bignum = '二';	
		}else if(sum_len==2){
			str_small2bignum = '三';	
		}else if(sum_len==3){
			str_small2bignum = '四';	
		}else if(sum_len==4){
			str_small2bignum = '五';	
		}
		var html_beneficiary = '<div id="beneficiary_info_0'+st_len+'" data-value="'+st_len+'" style="padding-top:20px;"><div class="benegiciary_title"><span style="float:left">受益人'+str_small2bignum+'</span><span style="float:right"><a href="javascript:void(0)" onclick="del_beneficiary('+st_len+')" class="delebtn">删 除</a></span><span style=" clear:both"></span></div><table class="c_t_a c_m_a" cellpadding="0" cellspacing="0" border="0" width="90%" style=" border: dashed #ccc 1px;" ><tr class=""><th width="30%">与被保险人关系</th><td width="70%" data-title="beneficiary_benefitRelation"><select class="i_e" id="beneficiary_benefitRelation_0'+st_len+'">'+html_relation+'</select></td></tr><tr class=""><th ><span class="a_x">*</span>姓名</th><td data-title="beneficiary_name"><input value="" maxlength="50" class="i_f pa_ui_element_normal" id="beneficiary_name_0'+st_len+'" type="text" onblur="check_cn_name(\'beneficiary_name_0'+st_len+'\',3)"><span id="beneficiary_name_0'+st_len+'_warn" style="float left;" class="warn_strong"></span></td></tr><tr class=""><th ><span class="a_x">*</span>证件类型</th><td data-title="beneficiary_certificates_type"><select id="beneficiary_certificates_type_0'+st_len+'" class="i_e">'+html_certificates_type+'</select><span id="beneficiary_certificates_type_0'+st_len+'_warn" style="float left;" class="warn_strong"></span></td></tr><tr class=""><th><span class="a_x">*</span>证件号码</th><td data-title="beneficiary_certificates_code"><input value="" maxlength="50" id="beneficiary_certificates_code_0'+st_len+'" class="i_f pa_ui_element_normal" type="text" onblur="check_All_card(\'beneficiary_certificates_type_0'+st_len+'\',\'beneficiary_certificates_code_0'+st_len+'\',3,true,\'beneficiary_birthday_0'+st_len+'\',\'beneficiary_sex_0'+st_len+'\',false)"><span id="beneficiary_certificates_code_0'+st_len+'_warn" style="float left;" class="warn_strong"></span></td></tr><tr class="" id="tr_assured_sex"><th><span class="a_x">*</span>性别</th><td data-title="beneficiary_sex"><input class="pa_ui_element_normal"  value="1" name="beneficiary_sex_0'+st_len+'" type="radio" checked="checked" ><label for="man" class="ch">男</label>&nbsp;&nbsp;&nbsp;<input class="pa_ui_element_normal" value="2" name="beneficiary_sex_0'+st_len+'" type="radio" ><label for="woman" class="ch">女</label></td></tr><tr class="" id="tr_assured_birthday"><th>出生日期</th><td data-title="beneficiary_birthday"><input id="beneficiary_birthday_0'+st_len+'" class="i_f pa_ui_element_normal" type="text" onblur="check_birthdays(\'beneficiary_birthday_0'+st_len+'\',3)"><span id="beneficiary_birthday_0'+st_len+'_warn" style="float left;" class="warn_strong"></span></td></tr><tr class=""><th><span class="a_x">*</span>受益比例</th><td data-title="beneficiary_benefitScale"><input type="text" class="i_f pa_ui_element_normal" id="beneficiary_benefitScale_0'+st_len+'" maxlength=5 onblur="checkAll_Null(\'beneficiary_benefitScale_0'+st_len+'\')" />%<span id="beneficiary_benefitScale_0'+st_len+'_warn" style="float left;" class="warn_strong"></span></td></tr><tr class=""><th><span class="a_x">*</span>受益顺序</th><td data-title="beneficiary_benefitSort"><select id="beneficiary_benefitSort_0'+st_len+'" class="i_e"><option value="1">第一受益人</option><option value="2">第二受益人</option><option value="3">第三受益人</option></select></td></tr></table></div>';
		
		$('#beneficiary_list_mode').append(html_beneficiary); 
		 
	}else{
		layer.alert('最多只能添加五个受益人！',9,'温馨提示');	
	}
}
 
 

//验证受益人填写并且保存
function checked_save_beneficiary(){
	
	var strlength = $('#beneficiary_list_mode').children().length;
	var flag_ben = true;
	var wrong_text = "";
	var domidTemp = '';
	var parentjsons = [];
	var str_Bai = 0;
	if(strlength>0){
		$('#beneficiary_list_mode').children().each(function(i, n) {
			var wrong_textchild = '';
            var childjsons = {'beneficiary_benefitSort':$('#beneficiary_benefitSort_0'+mode_datavalue).val()};
			
			var mode_datavalue = $(this).data('value');
			if($('#beneficiary_benefitRelation_0'+mode_datavalue).val()!=""){
				childjsons['beneficiary_benefitRelation'] = $('#beneficiary_benefitRelation_0'+mode_datavalue).val(); 
			}else{
				flag_ben = false;
				wrong_textchild += '<br>与投保人关系:与投保人关系没有选择';
			}
			
			if(check_cn_name('beneficiary_name_0'+mode_datavalue,3)){
				childjsons['beneficiary_name'] = $('#beneficiary_name_0'+mode_datavalue).val(); 
			}else{
				flag_ben = false;
				wrong_textchild += '<br>姓名:受益人姓名格式不正确';
			}
			
			if($('#beneficiary_certificates_type_0'+mode_datavalue).val()!=""){
				childjsons['beneficiary_certificates_type'] = $('#beneficiary_certificates_type_0'+mode_datavalue).val(); 
			}else{
				flag_ben = false;
				wrong_textchild += '<br>证件类型:证件类型没有选择';
			}
			
			
			if(check_All_card('beneficiary_certificates_type_0'+mode_datavalue,'beneficiary_certificates_code_0'+mode_datavalue,3,true,'beneficiary_birthday_0'+mode_datavalue,'beneficiary_sex_0'+mode_datavalue,false)){
				childjsons['beneficiary_certificates_code'] = $('#beneficiary_certificates_code_0'+mode_datavalue).val(); 
			}else{
				flag_ben = false;
				wrong_textchild += '<br>证件号码:证件号码填写有误';
			}
			  
			if( $('#beneficiary_info_0'+mode_datavalue+' input[name="beneficiary_sex_0'+mode_datavalue+'"]:checked').val()!=""){
				childjsons['beneficiary_sex'] = $('#beneficiary_info_0'+mode_datavalue+' input[name="beneficiary_sex_0'+mode_datavalue+'"]:checked').val(); 
			}else{
				flag_ben = false;
				wrong_textchild += '<br>性别:不能为空';
			}
			
			if(check_birthdays('beneficiary_birthday_0'+mode_datavalue,3)){
				childjsons['beneficiary_birthday'] = $('#beneficiary_birthday_0'+mode_datavalue).val(); 
			}else{
				flag_ben = false;
				wrong_textchild += '<br>出生日期:不能为空或者格式不正确(如：2015-01-08)';
			}
			 
			 
			if(check_numberAFloat('beneficiary_benefitScale_0'+mode_datavalue)){
				
				str_Bai = accAdd(str_Bai,$('#beneficiary_benefitScale_0'+mode_datavalue).val());
				if(parseFloat(str_Bai)>100){
					flag_ben = false;	
					wrong_text = '<br>受益比例:受益相加大于100';
				}else{
					childjsons['beneficiary_benefitScale'] = accDiv($('#beneficiary_benefitScale_0'+mode_datavalue).val(),100); 
				}
			}else{
				flag_ben = false;
				wrong_textchild += '<br>受益比例:只能是数字和小数点';
			}
			
			if(flag_ben==false){
				wrong_text += '<br>受益人'+(i+1)+' : '+wrong_textchild;	
			}
		 	parentjsons.push(childjsons);	
        });
		
		
	}else{
		$('#beneficiary_info_value').val('');
		flag_ben = false; 
	}
	
	if(flag_ben){
		$('#beneficiary_info_value').val(JSON.stringify(parentjsons));
		
	}
	return {'flag':flag_ben,'wrong_text':wrong_text,'domid':'beneficiary_list_mode'};
	
}

 
 