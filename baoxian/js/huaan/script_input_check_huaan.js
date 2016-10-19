 var  $j = jQuery.noConflict();

 $j(document).ready(function(){
	 $('#applicant_certificates_type option[value="120011"]').remove();
	 //机构和个人投保的切换
	$('#applicant_type_single input[name="applicant_type"]').bind("click",function(){
		var app_type_val = $('#applicant_type_single input[name="applicant_type"]:checked').val();
		if(app_type_val==1){
			$('#app_list_1').show();
			$('#app_list_1').html('<th width="80" height="40" align="right" class="pad_R10"><span class="a_x">*</span>姓名</th><td width="380"><input value="" maxlength="50" name="applicant_fullname" id="applicant_fullname" title="投保人姓名" class="i_f pa_ui_element_normal pad_TB3" type="text" onfocus="javascript:showwarn(\'applicant_fullname_warn\');" onblur="javascript:check_applicant_name();"><span id="applicant_fullname_warn" style="float left;" class="warn_strong"></span></td><th width="80" align="right" class="pad_R10"><span class="a_x">*</span>性别</th><td width="380"><span id="clientSexInput"><input class="pa_ui_element_normal pad_TB3" name="applicant_sex" value="0" id="applicant_sex_man" title="投保人称谓-先生" type="radio" checked="checked" ><label for="man" class="ch">先生</label>&nbsp;&nbsp;&nbsp;<input class="pa_ui_element_normal pad_TB3" name="applicant_sex" value="1" id="applicant_sex_woman" title="投保人称谓-女士" type="radio"><label for="woman" class="ch">女士</label></span><span id="applicant_sex_warn" style="float left;" class="warn_strong"></span></td>');

			$('#applicant_certificates_type option[value="120011"]').remove();
			if($('#applicant_certificates_type option[value="120001"]').length==0){
				$('#applicant_certificates_type').prepend('<option value="120001">身份证</option>');
				$('#applicant_certificates_type').val('120001');
			}
            $('#applicant_certificates_code_warn').html('');
		}else if(app_type_val==2){
			$('#app_list_1').html('<th width="80" height="40" align="right" class="pad_R10"><span class="a_x">*</span>机构名称</th><td width="380"><input value="" maxlength="50" name="applicant_fullname" id="applicant_fullname" title="机构名称" class="i_f pa_ui_element_normal pad_TB3" type="text" onblur="javascript:checkgroupname();"><span id="applicant_fullname_warn" style="float left;" class="warn_strong"></span></td><th width="80" align="right" class="pad_R10"><span class="a_x">*</span>固定电话</th><td width="380"><input value="" maxlength="50" name="telephone" id="telephone" title="固定电话" class="i_f pa_ui_element_normal pad_TB3" type="text" onblur="javascript:checktelephone(\'telephone\');"><span id="telephone_warn" style="float left;" > 格式：010-12345678</span></td>');

			$('#applicant_certificates_type option[value="120001"]').remove();
			if($('#applicant_certificates_type option[value="120011"]').length==0){
				$('#applicant_certificates_type').prepend('<option value="120011">组织机构代码证</option>');
				$('#applicant_certificates_type').val('120011');
			}
            $('#applicant_certificates_code_warn').html('格式为xxxxxxxx-x 或 xxxxxxxxx ');
			 
		}
	}); 
	$('.app_type_radio').bind("click",function(){
		$(this).prev("input").prop("checked",true);
		$(this).prev("input").trigger("click");
	}); 
	
	provice_city_Ajax('province'); //省份加载
	provice_city_Ajax('city');//城市加载
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
    var date = $j("#server_time").val();
    date = parseInt(date);
    //alert("server time: "+date);
    //date = new Date(date);
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
				 	
					var startdate_old = $j("#startDate").val();
					$j("#endDate").val();
					$j("#startDate").val(startdate_old+" 00:00:00");
					$j("#endDate").datepicker("option",'minDate',dateText);
					var end_t = timeStamp2String(dateText,parseInt($j('#time_temp_pr').val())*30);
					$j("#endDate").datepicker("option",'maxDate',end_t.substring(0,10)); 
					$j("#project_start_date").val('');
					$j("#project_end_date").val('');
					
			 } 
        };
		
		var pickerOpts_end = {
							 changeMonth: true,
							 changeYear: true,
							 dateFormat:'yy-mm-dd',
							 onSelect: function(dateText, inst) { 
							  	if($j("#startDate").val()!=""){
									var startdate_old = $j("#endDate").val();
									$j("#endDate").val(startdate_old+" 23:59:59");
									$j("#project_start_date").val('');
									$j("#project_end_date").val('');
									var start_baoxian = $j('#startDate').val();
									$j("#project_start_date").datepicker("option",'minDate',start_baoxian.substring(0,10)); 
									$j("#project_start_date").datepicker("option",'maxDate',timeStamp2String(start_baoxian,29).substring(0,10));
									 
								}else{
									$j("#endDate").val('');
									layer.alert('保险起期没有填写',9,'温馨提示');	
									$('.xubox_layer').css('top',"200px");
								}
							 } 
					};
					
					
		var project_pickerOpts_start = {
							 changeMonth: true,
							 changeYear: true,
							 dateFormat:'yy-mm-dd',
							 onSelect: function(dateText, inst) {
								if($j("#startDate").val()!=""&&$j("#endDate").val()!=""){
									var startdate_old = $j("#project_start_date").val();
									$j("#project_start_date").val(startdate_old+" 00:00:00");
									$j("#project_end_date").val('');
									$j("#project_end_date").datepicker("option",'minDate',startdate_old); 
									$j("#project_end_date").datepicker("option",'maxDate',$j("#endDate").val().substring(0,10));
								}else{
									$j("#project_start_date").val('');
									layer.alert('保险起期或者止期没有填写',9,'温馨提示');
									$('.xubox_layer').css('top',"200px");
								}
							 } 
					};
					
		var project_pickerOpts_end = {
							 changeMonth: true,
							 changeYear: true,
							 dateFormat:'yy-mm-dd',
							 onSelect: function(dateText, inst) { 
							    if($j("#startDate").val()!=""&&$j("#endDate").val()!=""){
									if($j("#project_start_date").val()!=""){
										var startdate_old = $j("#project_end_date").val();
										$j("#project_end_date").val(startdate_old+" 23:59:59");
									}else{
										$j("#project_end_date").val('');
										layer.alert('工程起期没有填写',9,'温馨提示');	
										$('.xubox_layer').css('top',"200px");
									}
								}else{
									$j("#project_end_date").val('');
									layer.alert('保险起期或者止期没有填写',9,'温馨提示');
								}
							  	
							 } 
					};
				 
        //��������ѡ����
     
	 $j("#startDate").datepicker(pickerOpts); 
	 $j("#endDate").datepicker(pickerOpts_end); 
	 $j("#project_start_date").datepicker(project_pickerOpts_start); 
	 $j("#project_end_date").datepicker(project_pickerOpts_end); 
	 $('#beneficiary_text').val('法定');
	 $('#beneficiary').change(function(){
		if($(this).val()==2){
			$('#beneficiary_text').val('');
			$('#beneficiary_span').show();
		}else{
			$('#beneficiary_span').hide();
			$('#beneficiary_text').val('法定');	
		}	 
	 });
	 ///////////////////////////////////////////////////////////////
 
	 $j("a#btnNext").click(function(){
		 var res = check_buy_submit_huaanYMain();
		 
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
 
 //华安提交验证
 function check_buy_submit_huaanYMain(){
	  		var applicant_type_value = $('#applicant_type_single input[name="applicant_type"]:checked').val();
			if(!set_prodcutMarkPrice($('#q_prodcutMarkPrice').val())){
				layer.alert("保费填写有误",9,'温馨提示');
				$('.xubox_layer').css('top',"200px");
				window.setTimeout("document.getElementById('q_prodcutMarkPrice').focus();", 0)  ; 
				return false;	
			} 
		 	
			if(applicant_type_value==2){
				if(!checkgroupname()){
					layer.alert("请填写投保人机构名称",9,'温馨提示');	
					$('.xubox_layer').css('top',"200px");
					window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
					return false;
				}
                if(!checktelephone('telephone')){
                    layer.alert("机构固定电话格式正确",9,'温馨提示');
					$('.xubox_layer').css('top',"200px");
                    window.setTimeout("document.getElementById('telephone').focus();", 0)  ;
                    return false;
                }
			}else if(applicant_type_value==1){
				 
				 if(!check_applicant_name()){
					layer.alert("请填写投保人信息姓名",9,'温馨提示');	
					$('.xubox_layer').css('top',"200px");
					window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
					return false;
				}
				 
			}
			 
			if (!$j("#startDate").val()){
	            layer.alert("保险起期没填写！",9,'温馨提示');
				$('.xubox_layer').css('top',"200px");
	            window.setTimeout("document.getElementById('startDate').focus();", 0)  ; 
	            return false;
	        }else if (!$j("#endDate").val())
	        {
	            layer.alert("请填写保险止期！",9,'温馨提示');
				$('.xubox_layer').css('top',"200px");
	            window.setTimeout("document.getElementById('endDate').focus();", 0)  ; 
	            return false;
	        } else if(!check_applicant_email()){
				 layer.alert("请填写投保人电子邮箱",9,'温馨提示');	
				 $('.xubox_layer').css('top',"200px");
				window.setTimeout("document.getElementById('applicant_email').focus();", 0)  ;	
				return false;
			}else if(!check_applicant_card()){
				 layer.alert("请填写投保人证件号码",9,'温馨提示');	
				 $('.xubox_layer').css('top',"200px");
				window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0)  ;	
				return false;
			}else if(!check_applicant_mobilephone()){
				 layer.alert("请填写投保人手机号码",9,'温馨提示');	
				 $('.xubox_layer').css('top',"200px");
				window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0)  ;	
				return false;
			}
			//最后的人员修改整体验证 
			else if(checked_assuredAll()==false){  //验证上传文件

                layer.alert('验证失败,失败原因：<br>1.名称,手机号,证件号码不能为空;<br>2.证件号码：身份证15,18位;<br>机构代码格式：88829888-0<br>3.手机,证件号码有误;<br>4."是否被保险人"此列必须有一个是被保险人<br>修改完成再次检验!',9,'温馨提示');
				$('.xubox_layer').css('top',"200px");
				window.setTimeout("document.getElementById('list_assured_list').focus();", 0)  ;	
				
				return false;	
			}else if(!check_applicant_null('project_name')){
				 layer.alert("工程名称有误，请核实后提交！",9,'温馨提示');	
				 $('.xubox_layer').css('top',"200px");
				window.setTimeout("document.getElementById('project_name').focus();", 0)  ;	
				return false;
			}else if(!check_applicant_null('project_price')){
				
				 layer.alert("工程造价不在投保范围之内，请核实后提交！",9,'温馨提示');
				 $('.xubox_layer').css('top',"200px");	
				window.setTimeout("document.getElementById('project_price').focus();", 0)  ;	
				return false;
			}else if(!$j("#project_start_date").val()){
	            alert("请填写施工起期！",9,'温馨提示');
				$('.xubox_layer').css('top',"200px");
	            window.setTimeout("document.getElementById('project_start_date').focus();", 0)  ; 
	            return false;
	        }else if (!$j("#project_end_date").val())
	        {
	            layer.alert("请填写施工止期！",9,'温馨提示');
				$('.xubox_layer').css('top',"200px");
	            window.setTimeout("document.getElementById('project_end_date').focus();", 0)  ; 
	            return false;
	        }else if(!check_applicant_null('project_zipcode')){
				 layer.alert("请填写邮政编码",9,'温馨提示');	
				 $('.xubox_layer').css('top',"200px");
				window.setTimeout("document.getElementById('project_zipcode').focus();", 0)  ;	
				return false;
			}else if(!check_applicant_null('project_content')){
				 layer.alert("请填写施工内容",9,'温馨提示');	
				 $('.xubox_layer').css('top',"200px");
				window.setTimeout("document.getElementById('project_content').focus();", 0)  ;	
				return false;
			}else if(!check_applicant_null('project_location')){
				 layer.alert("请填写施工地址",9,'温馨提示');	
				 $('.xubox_layer').css('top',"200px");
				window.setTimeout("document.getElementById('project_location').focus();", 0)  ;	
				return false;
			}else if($('#beneficiary').val()==2){
				if(!str_length_br('beneficiary_text',100)){
					layer.alert("指定受益人字数100字之内",9,'温馨提示');	
					$('.xubox_layer').css('top',"200px");
					window.setTimeout("document.getElementById('beneficiary_text').focus();", 0)  ;	
					return false;	
				}
			}
			return true;
 }
 
 
 
 
function provice_city_Ajax(val_type){
	var action_val = 'get_'+val_type+'_list';
	var datajson = {'action':action_val};
	if(val_type=='city'){
		datajson['province_name']=$('#province_name').val();
	}
	$.ajax({
		 type: 'POST',
		 url: '../oop/business/get_SINO_Data.php' ,
		 async:false,
		 data: datajson ,
		 dataType: "JSON",
		 success: function(data){
			var html_pro_city = '';
			$(data).each(function(i, n){
				 html_pro_city += '<option value="'+n+'">'+n+'</option>';
			});
			$('#'+val_type+'_name').html(html_pro_city);
		 }
	   
	
	});	 
}


 
function set_prodcutMarkPrice(val_str){
	var int_val = parseInt(val_str);
	var max_price_temp = parseInt($('#cost_limit').val().split(',')[1])/10*10000;
	var min_price_temp = parseInt($('#premium').val());
	if(int_val!=val_str){
		$('#q_prodcutMarkPrice_warn').attr("class","wrong_strong");
		$('#q_prodcutMarkPrice_warn').text('保费不是有效整数');
		return false;
	}else{ 
		if(min_price_temp<=int_val && int_val<=max_price_temp){
			$('#totalModalPremium_single').val(int_val);
			$('#totalModalPremium').val(int_val);
			$('#showprice').text(int_val);    
			$('#q_prodcutMarkPrice_warn').attr("class","right_strong");
			$('#q_prodcutMarkPrice_warn').text('');	
			return true;	
		}else{
			$('#q_prodcutMarkPrice_warn').attr("class","wrong_strong");
			$('#q_prodcutMarkPrice_warn').text('保费范围：'+min_price_temp+'<=保费<='+max_price_temp);
			return false;
		}
	}
}


function str_length_br(domid,num){
	if($.trim($('#'+domid).val().length)>num || $.trim($('#'+domid).val().length)<1){
		$('#'+domid+'_warn').attr("class","wrong_strong");
		$('#'+domid+'_warn').text('指定受益人字数'+num+'字之内');
		return false;
	}else{
		$('#'+domid+'_warn').attr("class","right_strong");
		$('#'+domid+'_warn').text('');
		return true;
	}
}


 
 