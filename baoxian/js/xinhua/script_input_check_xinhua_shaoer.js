var  $j = jQuery.noConflict();
var pickerOpts ={};
var pickerOpts2 ={};
somehtml_relation = '';
$j(document).ready(function(){
	somehtml_relation = $('#beneficiary_benefitRelation_01').html();
	$('#businessType').val(1);
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
	  
    var bombay = date - (3600 * 24)*1;
	 var bombay2 = date;
	 
    //alert("bombay: "+bombay);
	
     var time = new Date(bombay*1000);
	 var time2 = new Date(bombay2*1000);
    //alert("time: "+time);
	
    time = time.getFullYear()+"-"+(time.getMonth()+1)+"-"+time.getDate();
	time2 = time2.getFullYear()+"-"+(time2.getMonth()+1)+"-"+time2.getDate();
    //alert("after time: "+time);
    //end add by wangcya , 20141121,服务器时间
    
	 pickerOpts = {
         changeMonth: true,
         changeYear: true,
         dateFormat:'yy-mm-dd',
		 maxDate: time,//start_day
		 onSelect: function(dateText, inst) { 
		 	
		 }
        };
		 
	pickerOpts2 = {
         changeMonth: true,
         changeYear: true,
         dateFormat:'yy-mm-dd',
		 minDate: time2,//start_day
		 onSelect: function(dateText, inst) { 
		 	
		 }
        };
	 
	 
	 
	 $j("#applicant_cardValidDate").datepicker(pickerOpts); 
	 $j("#applicant_cardExpDate").datepicker(pickerOpts2); 
	 $j("#assured_cardValidDate").datepicker(pickerOpts); 
	 $j("#assured_cardExpDate").datepicker(pickerOpts2); 
	 ///////////////////////////////////////////////////////////////
 
	
	$('#applicant_cardValidExps').click(function(){
		var ch_f = $(this).attr("checked");
		if(ch_f){
			$('#applicant_cardExpDate').val("9999-12-31");	
			$('#applicant_cardExpDate').attr("disabled",true);		
		}else{
			$('#applicant_cardExpDate').val("");	
			$('#applicant_cardExpDate').attr("disabled",false);		
		}
	}); 
	
	$('#assured_cardValidExps').click(function(){
		var ch_f = $(this).attr("checked");
		if(ch_f){
			$('#assured_cardExpDate').val("9999-12-31");	
			$('#assured_cardExpDate').attr("disabled",true);		
		}else{
			$('#assured_cardExpDate').val("");
			$('#assured_cardExpDate').attr("disabled",false);		
		}
	}); 
	
	$('#beneficiary').change(function(){
		$('#beneficiary_info_value').val("");
		if($(this).val()==1){
			$('#beneficiary_list').hide();
			$('#beneficiary_info').show();
		}else if($(this).val()==0){
			$('#beneficiary_info').hide();
			$('#beneficiary_list').show();
			$j("#beneficiary_cardValidDate_01").datepicker(pickerOpts); 
			$j("#beneficiary_cardExpDate_01").datepicker(pickerOpts2); 
			$('#beneficiary_cardValidExps_01').click(function(){
				var ch_f = $(this).attr("checked");
				if(ch_f){
					$("#beneficiary_cardExpDate_01").val("9999-12-31");	
					$("#beneficiary_cardExpDate_01").attr("disabled",true);		
				}else{
					$("#beneficiary_cardExpDate_01").val("");	
					$("#beneficiary_cardExpDate_01").attr("disabled",false);		
				}
			}); 	
		}
	});
	if($('#payPeriod').val()==0){
		$('#bank_infos').hide();	
	}else{
		$('#bank_infos').show();
		get_bank_info();
	}
	 
	provice_city_Ajax('applicant_province_code','province');
	provice_city_Ajax('assured_province_code','province',2);
	
	get_industry_info('applicant_business_type','industry');
	get_organization('applicant_p_organization','P');
	//get_industry_info('assured_occupationClassCode','career',2);
	
	
	
	 /////////////////////////////////////////////////////
	 $j("a#btnNext").click(function(){
		  
		 var res = '';
		//if($('#businessType').val()==2)
		if($('#bat_post_policy').val()==0){ 
		 
			res = check_buy_submit_xinhuashaoer();
		}else{
			res = '';
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
	   $('#orderForm input:disabled').attr('disabled',false);
	   $('#orderForm select:disabled').attr('disabled',false);
	   $j("input#submit1").click();

	 });
	 
	 
	 /*
	 
	    $j("form#inputForm").submit(function() {
                alert("submit");
                  return true;
            });
       */
     	 
	 
 });// ready
 
function gender_ass_appli(f_str,relationshipWithInsured_dom){
	var s_str = $('#gender_temp').val();
	var num_tempQ = 21;
	if(f_str=='M' && s_str=='F'){
		num_tempQ = 31;
	}else if(f_str=='F' && s_str=='F'){
		num_tempQ = 32; 
	}else if(f_str=='F' && s_str=='M'){
		num_tempQ = 22; 
	}else if(f_str=='M' && s_str=='M'){
		num_tempQ = 21; 
	}	
	
	$('#'+relationshipWithInsured_dom).val(num_tempQ); 
}


function gender_ass_beneficiary(f_str,beneficiary_dom){
 
	var s_str = $('#gender_temp').val();
	var num_tempQ = '01';
	if(f_str=='M' && s_str=='F'){
		num_tempQ = '02';
	}else if(f_str=='F' && s_str=='F'){
		num_tempQ = '04'; 
	}else if(f_str=='F' && s_str=='M'){
		num_tempQ = '03'; 
	}else if(f_str=='M' && s_str=='M'){
		num_tempQ = '01'; 
	}	
	
	$('#'+beneficiary_dom).val(num_tempQ); 
}
 
 
 ///////////////////////////////////////////////////
function check_buy_submit_xinhuashaoer(){
	//购买信息检查
	if (!check_cn_name('applicant_fullname',1)){
		layer.alert("投保人姓名有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
		return false;
	}else if($('#applicant_certificates_type').val()==""){
		layer.alert("投保人证件类型没有选择！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_certificates_type').focus();", 0)  ; 
		return false;
	}else if(!check_All_card('applicant_certificates_type','applicant_certificates_code',1,true,'applicant_birthday','applicant_gender',false)){
		layer.alert("投保人证件号码有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0)  ; 
		return false;
	}else if($.trim($('#applicant_cardValidDate').val())==""){
		layer.alert("投保人证件号码有限期不能为空！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_cardValidDate').focus();", 0)  ; 
		return false;
	}else if($.trim($('#applicant_cardExpDate').val())=="" && $('#applicant_cardValidExps').attr("checked")==false){
		layer.alert("投保人证件号码有限期不能为空！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_cardExpDate').focus();", 0)  ; 
		return false;
	}else if(!check_birthdays('applicant_birthday',1)){
		layer.alert("投保人生日填写有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_birthday').focus();", 0)  ; 
		return false;
	}else if(!$('#applicant_info_single input[name="applicant_gender"]:checked').val()){
		layer.alert("投保人性别没有选择！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_info_single').focus();", 0) ; 
		return false;
	}else if($('#applicant_nation_code').val()==""){
		layer.alert("投保人国籍没有选择！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_nation_code').focus();", 0)  ; 
		return false;
	}else if(!checkAll_Null('applicant_address') || $('#applicant_province_code').val()=="" || $('#applicant_city_code').val()==""  || $('#applicant_county_code').val()==""  || $('#applicant_province_code').val()==null || $('#applicant_city_code').val()==null  || $('#applicant_county_code').val()==null){
		layer.alert("投保人通信地址没有选择或者填写！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_address').focus();", 0)  ; 
		return false;
	}else if($('#applicant_p_organization').val()=="" || $('#applicant_child_organization').val()=="" ){
		layer.alert("投保人服务机构没有选择！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_p_organization').focus();", 0)  ; 
		return false;
	}else if(!check_zipcode('applicant_zipcode')){
		layer.alert("投保人邮政编码有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_zipcode').focus();", 0)  ; 
		return false;
	}else if(!check_mobilephones('applicant_mobiletelephone')){
		layer.alert("投保人移动电话有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_mobiletelephone').focus();", 0)  ; 
		return false;
	}else if(!check_emails('applicant_email')){
		layer.alert("投保人电子邮箱有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_email').focus();", 0)  ; 
		return false;
	}else if($('#applicant_business_type').val()=="" || $('#applicant_occupationClassCode').val()==""  ){
		layer.alert("投保人职业没有选择！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_business_type').focus();", 0)  ; 
		return false;
	}else if(!check_cn_name('assured_fullname',2)){
		layer.alert("被保险人姓名有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_fullname').focus();", 0)  ; 
		return false;
	}else if($('#assured_certificates_type').val()==""){
		layer.alert("被保险人证件类型没有选择！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_certificates_type').focus();", 0)  ; 
		return false;
	}else if(!check_All_card('assured_certificates_type','assured_certificates_code',2,true,'assured_birthday','assured[0][assured_gender]',true)){
		layer.alert("被保险人证件号码有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_certificates_code').focus();", 0)  ; 
		return false;
	}else if($.trim($('#assured_cardValidDate').val())==""){
		layer.alert("被保险人证件号码有限期不能为空！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_cardValidDate').focus();", 0)  ; 
		return false;
	}else if($.trim($('#assured_cardExpDate').val())=="" && $('#assured_cardValidExps').attr("checked")==false){
		layer.alert("被保险人证件号码有限期不能为空！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_cardExpDate').focus();", 0)  ; 
		return false;
	}else if($('#gender_temp').val()!=$('input[name="assured[0][assured_gender]"]:checked').val()){
		layer.alert("被保险人性别填写有误！",9,'温馨提示');
		window.setTimeout("document.getElementsByName('assured[0][assured_gender]').focus();", 0)  ; 
		return false;
	}else if(!check_birthdays('assured_birthday',2)){
		layer.alert("被保险人生日填写有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_birthday').focus();", 0)  ; 
		return false;
	}else if($('#assured_birthdayTemp').val()!=$('#assured_birthday').val()){
		layer.alert("被保险人生日和产品详情页面选择的生日不一致！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_birthday').focus();", 0)  ; 
		return false;
	}else if($('#assured__nation_code').val()==""){
		layer.alert("被保险人国籍没有选择！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured__nation_code').focus();", 0)  ; 
		return false;
	}else if(!checkAll_Null('assured_address') || $('#assured_province_code').val()==null || $('#assured_city_code').val()==null  || $('#assured_county_code').val()==null || $('#assured_province_code').val()=="" || $('#assured_city_code').val()==""  || $('#assured_county_code').val()==""){
		layer.alert("被保险人通信地址没有选择或者填写！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_address').focus();", 0)  ; 
		return false;
	}else if($('#assured_business_type').val()=="" || $('#assured_occupationClassCode').val()==""  ){
		layer.alert("被保险人职业没有选择！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_business_type').focus();", 0)  ; 
		return false;
	}else if(!check_mobilephones('assured_mobilephone')){
		layer.alert("被保险人移动电话有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_mobilephone').focus();", 0)  ; 
		return false;
	}
	
	if($('#beneficiary').val()==0){
		var checked_save_res = checked_save_beneficiary();
		if(checked_save_res.flag==false){
			layer.alert(checked_save_res.wrong_text,9,'温馨提示');
			var container = $('div'),scrollTo = $('#'+checked_save_res.domid);
			container.scrollTop(scrollTo.offset().top - container.offset().top + container.scrollTop());
			return false;
		}
	}
	
	if($('#payPeriod').val()!=0){
		if($('#bank_name').val()==""){
			layer.alert("续期交费银行没有选择！",9,'温馨提示');
			window.setTimeout("document.getElementById('bank_name').focus();", 0)  ; 
			return false;
		}else if($.trim($('#bank_username').val())==""){
			layer.alert("续期交费银行账户姓名没有填写！",9,'温馨提示');
			window.setTimeout("document.getElementById('bank_username').focus();", 0)  ; 
			return false;
		}else if($.trim($('#bank_cardnumber').val())==""){
			layer.alert("续期交费银行账户卡号没有填写！",9,'温馨提示');
			window.setTimeout("document.getElementById('bank_cardnumber').focus();", 0)  ; 
			return false;
		}else if(!$('#bank_checkbox').attr("checked")){
			layer.alert("没有勾选《同意保险费自动转账授权声明》！",9,'温馨提示');
			window.setTimeout("document.getElementById('bank_checkbox').focus();", 0)  ; 
			return false;
		}
	}
 
	return  true;
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
            var childjsons = {'beneficiary_benefitSort':1};
			
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
			
			if($('#beneficiary_cardValidDate_0'+mode_datavalue).val()!=""){
				childjsons['beneficiary_cardValidDate'] = $('#beneficiary_cardValidDate_0'+mode_datavalue).val(); 
			}else{
				flag_ben = false;
				wrong_textchild += '<br>证件有效期:起期不能为空';
			}
			
			if($('#beneficiary_cardValidExps_0'+mode_datavalue).attr("checked")){
				childjsons['beneficiary_cardExpDate'] = $('#beneficiary_cardExpDate_0'+mode_datavalue).val(); 
			}else{
				if($('#beneficiary_cardExpDate_0'+mode_datavalue).val()!=""){
					childjsons['beneficiary_cardExpDate'] = $('#beneficiary_cardExpDate_0'+mode_datavalue).val(); 
				}else{
					flag_ben = false;
					wrong_textchild += '<br>证件有效期:止期不能为空';
				}
			}
			 
			if($('#beneficiary_info_0'+mode_datavalue+' input[name="beneficiary_sex_0'+mode_datavalue+'"]:checked').val() && $('#beneficiary_info_0'+mode_datavalue+' input[name="beneficiary_sex_0'+mode_datavalue+'"]:checked').val()!=""){
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
			
			if($('#beneficiary_nativePlace_0'+mode_datavalue).val()!=""){
				childjsons['beneficiary_nativePlace'] = $('#beneficiary_nativePlace_0'+mode_datavalue).val(); 
			}else{
				flag_ben = false;
				wrong_textchild += '<br>国籍:不能为空';
			}
			 
			if(check_numberAFloat('beneficiary_benefitScale_0'+mode_datavalue)){
				
				str_Bai = accAdd(str_Bai,$('#beneficiary_benefitScale_0'+mode_datavalue).val());
				 
				if(i== (strlength-1) && parseFloat(str_Bai)!=100){
					flag_ben = false;	
					wrong_textchild = '<br>受益比例:受益相加不等于100';
				}else{
					childjsons['beneficiary_benefitScale'] = accDiv($('#beneficiary_benefitScale_0'+mode_datavalue).val(),100); 
				}
			}else{
				flag_ben = false;
				wrong_textchild += '<br>受益比例:只能是数字和小数点';
			}
			
			if(flag_ben==false){
				wrong_text += wrong_textchild;	
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

//删除受益人填写列表
function del_beneficiary(var_str){
	$('#beneficiary_cardValidExps_0'+var_str).unbind("click");
	$('#beneficiary_info_0'+var_str).remove();
}
//增加受益人列表
function add_beneficiary(){
	var data_value_len = $('#beneficiary_list_mode').children("div:last").data('value');
	var sum_len = $('#beneficiary_list_mode').children().length;
	
	var html_certificates_type = $('#assured_certificates_type').html();
	var str_small2bignum = '一';
	if(sum_len<=2){
		var st_len = 1;	
		if(data_value_len){
			st_len = parseInt(data_value_len)+1;
		}
		if(sum_len==1){
			str_small2bignum = '二';	
		}else if(sum_len==2){
			str_small2bignum = '三';	
		}
		var html_beneficiary = '<div id="beneficiary_info_0'+st_len+'" data-value="'+st_len+'" style="padding-top:20px;"><div class="benegiciary_title"><span style="float:left">受益人'+str_small2bignum+'</span><span style="float:right"><a href="javascript:void(0)" onclick="del_beneficiary('+st_len+')" class="delebtn">删 除</a></span><span style=" clear:both"></span></div><table class="c_t_a c_m_a" cellpadding="0" cellspacing="0" border="0" width="90%" style=" border: dashed #ccc 1px;" ><tr class=""><th ><span class="a_x">*</span>姓名</th><td data-title="beneficiary_name"><input value="" maxlength="50" class="i_f pa_ui_element_normal" id="beneficiary_name_0'+st_len+'" type="text" onblur="check_cn_name(\'beneficiary_name_0'+st_len+'\',3)"><span id="beneficiary_name_0'+st_len+'_warn" style="float left;" class="warn_strong"></span></td></tr><tr class=""><th ><span class="a_x">*</span>证件类型</th><td data-title="beneficiary_certificates_type"><select id="beneficiary_certificates_type_0'+st_len+'" class="i_e">'+html_certificates_type+'</select><span id="beneficiary_certificates_type_0'+st_len+'_warn" style="float left;" class="warn_strong"></span></td></tr><tr class=""><th><span class="a_x">*</span>证件号码</th><td data-title="beneficiary_certificates_code"><input value="" maxlength="50" id="beneficiary_certificates_code_0'+st_len+'" class="i_f pa_ui_element_normal" type="text" onblur="check_All_card(\'beneficiary_certificates_type_0'+st_len+'\',\'beneficiary_certificates_code_0'+st_len+'\',3,true,\'beneficiary_birthday_0'+st_len+'\',\'beneficiary_sex_0'+st_len+'\',false)"><span id="beneficiary_certificates_code_0'+st_len+'_warn" style="float left;" class="warn_strong"></span></td></tr><tr class=""><th><span class="a_x">*</span>证件有效期</th><td data-title="beneficiary_cardValidDate"><input id="beneficiary_cardValidDate_0'+st_len+'" class="i_f pa_ui_element_normal" type="text" > 至 <input id="beneficiary_cardExpDate_0'+st_len+'" class="i_f pa_ui_element_normal" type="text" ><input type="checkbox" id="beneficiary_cardValidExps_0'+st_len+'" /> 长期有效 <span id="beneficiary_cardValidDate_0'+st_len+'_warn beneficiary_cardExpDate_0'+st_len+'_warn" style="float left;" class="warn_strong"></span></td></tr><tr class="" id="tr_assured_sex"><th><span class="a_x">*</span>性别</th><td data-title="beneficiary_sex"><input class="pa_ui_element_normal"  value="M" name="beneficiary_sex_0'+st_len+'" type="radio" onclick="gender_ass_beneficiary(this.value,\'beneficiary_benefitRelation_0'+st_len+'\');"><label for="man" class="ch">男</label>&nbsp;&nbsp;&nbsp;<input class="pa_ui_element_normal" value="F" name="beneficiary_sex_0'+st_len+'" type="radio" onclick="gender_ass_beneficiary(this.value,\'beneficiary_benefitRelation_0'+st_len+'\');"><label for="woman" class="ch">女</label></td></tr><tr class="" id="tr_assured_birthday"><th>出生日期</th><td data-title="beneficiary_birthday"><input id="beneficiary_birthday_0'+st_len+'" class="i_f pa_ui_element_normal" type="text" onblur="check_birthdays(\'beneficiary_birthday_0'+st_len+'\',3)"><span id="beneficiary_birthday_0'+st_len+'_warn" style="float left;" class="warn_strong"></span></td></tr><tr class=""><th width="30%">与被保险人关系</th><td width="70%" data-title="beneficiary_benefitRelation"><select class="i_e" id="beneficiary_benefitRelation_0'+st_len+'"  disabled="disabled" >'+somehtml_relation+'</select></td></tr><tr class=""><th><span class="a_x">*</span>国籍</th><td data-title="beneficiary_nativePlace"><select id="beneficiary_nativePlace_0'+st_len+'" class="i_e"><option value="37">中国</option></select></td></tr><tr class=""><th><span class="a_x">*</span>收益比例</th><td data-title="beneficiary_benefitScale"><input type="text" class="i_f pa_ui_element_normal" id="beneficiary_benefitScale_0'+st_len+'" maxlength=5 onblur="checkAll_Null(\'beneficiary_benefitScale_0'+st_len+'\')" />%<span id="beneficiary_benefitScale_0'+st_len+'_warn" style="float left;" class="warn_strong"></span></td></tr></table></div>';
		
		$('#beneficiary_list_mode').append(html_beneficiary); 
		$j("#beneficiary_cardValidDate_0"+st_len).datepicker(pickerOpts); 
		$j("#beneficiary_cardExpDate_0"+st_len).datepicker(pickerOpts2); 
		$('#beneficiary_cardValidExps_0'+st_len).click(function(){
			var ch_f = $(this).attr("checked");
			if(ch_f){
				$("#beneficiary_cardExpDate_0"+st_len).val("9999-12-31");	
				$("#beneficiary_cardExpDate_0"+st_len).attr("disabled",true);		
			}else{
				$("#beneficiary_cardExpDate_0"+st_len).val("");	
				$("#beneficiary_cardExpDate_0"+st_len).attr("disabled",false);		
			}
		}); 
		
	}else{
		layer.alert('最多只能添加三个受益人！',9,'温馨提示');	
	}
}

<!-----------------------------------------以下是新华少儿产品的ajax调用（省市区、服务机构、职业代码、银行）------------------------------------>

 //获取城市列表
function provice_city_Ajax(domid,val_type,type){
	var action_val = 'get_'+val_type;
	var datajson = {'op':'get_address_info','action':action_val};
	var tempname = 'province';
	var sts_temp = domid.split('_')[0];
	if(val_type=='city_by_province'){
		datajson['province_code']=$('#'+sts_temp+'_province_code').val();
		tempname = 'city';
	}else if(val_type=='county_by_city'){
		tempname = 'county';
		datajson['city_code']=$('#'+sts_temp+'_city_code').val();
	}
	$.ajax({
		 type: 'POST',
		 url: '../oop/business/get_NCI_Data.php' ,
		 async:false,
		 data: datajson ,
		 dataType: "JSON",
		 success: function(data){
			var html_pro_city = '';
			//console.log(JSON.stringify(data));
			$(data).each(function(i, n){
				 html_pro_city += '<option value="'+n[tempname+'_code']+'">'+n[tempname+'_name']+'</option>';
			});
			if(val_type=='county_by_city' && html_pro_city==""){ //三级缺省填充二级内容到三级
				var temp_ids = '';
				var temp_names = '';
				 if(type==2){
					temp_ids = $('#assured_city_code').val();
					temp_names = $('#assured_city_code option:selected').text();
				}else{
					temp_ids = $('#applicant_city_code').val();
					temp_names = $('#applicant_city_code option:selected').text();	
				}
				html_pro_city += '<option value="'+temp_ids+'">'+temp_names+'</option>';
			}
			$('#'+domid).html(html_pro_city);
			 
			if(val_type=='province'){
				if(type==2){
					provice_city_Ajax('assured_city_code','city_by_province',2);
				}else{
					provice_city_Ajax('applicant_city_code','city_by_province');
						
				}
				
			}else if(val_type=='city_by_province'){
				if(type==2){
					provice_city_Ajax('assured_county_code','county_by_city',2);
				}else{
					provice_city_Ajax('applicant_county_code','county_by_city');
					 
				}
				
			}
		 }
	});	 
}


//获取机构代码
function get_organization(domid,val_type){
	 
	var datajson = {'op':'get_organization'};
	if(val_type=='P'){
		datajson['parent_code']=86;
	}else if(val_type=='C'){
		datajson['parent_code']=$('#applicant_p_organization').val();
	}
	$.ajax({
		 type: 'POST',
		 url: '../oop/business/get_NCI_Data.php' ,
		 async:false,
		 data: datajson ,
		 dataType: "JSON",
		 success: function(data){
			var html_pro_city = '';
			//console.log(JSON.stringify(data));
			$(data).each(function(i, n){
				 html_pro_city += '<option value="'+n.org_code+'">'+n.org_name+'</option>';
			});
			$('#'+domid).html(html_pro_city);
			if(val_type=='P'){
				get_organization('applicant_child_organization','C');
			}
		 }
	});	 
}



//获取职业代码
function get_industry_info(domid,val_type,type){
	 
	var datajson = {'op':'get_industry_info','action':'get_'+val_type};
	if(val_type=='career'){
		if(type==2){
			datajson['industry_code']=$('#assured_business_type').val();	
		}else{
			datajson['industry_code']=$('#applicant_business_type').val();
		}
		
	}
	$.ajax({
		 type: 'POST',
		 url: '../oop/business/get_NCI_Data.php' ,
		 async:false,
		 data: datajson ,
		 dataType: "JSON",
		 success: function(data){
			var html_pro_city = '';
			//console.log(JSON.stringify(data));
			$(data).each(function(i, n){
				 html_pro_city += '<option value="'+n[val_type+'_code']+'">'+n[val_type+'_name']+'</option>';
			});
			$('#'+domid).html(html_pro_city);
			if(val_type=='industry'){
				if(!type){
					get_industry_info('applicant_occupationClassCode','career')	
				}
		
			}
		 }
	});	 
}



//获取银行代码
function get_bank_info(){
	 
	var datajson = {'op':'get_bank_info','action':'get_bank_list'};
	 
	$.ajax({
		 type: 'POST',
		 url: '../oop/business/get_NCI_Data.php' ,
		 async:false,
		 data: datajson ,
		 dataType: "JSON",
		 success: function(data){
			var html_pro_city = '';
			//console.log(JSON.stringify(data));
			$(data).each(function(i, n){
				 html_pro_city += '<option value="'+n.bank_code+'">'+n.bank_name+'</option>';
			});
			$('#bank_name').html(html_pro_city);
		 }
	});	 
}

//浮点数相加
function accAdd(arg1,arg2){
	var r1,r2,m,mz=0;
	 
	try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0};
	try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0};
	m=Math.pow(10,Math.max(r1,r2));
	if(r1>r2){
		mz = r1;
	}else{
		mz = r2;
	}
	return parseFloat(parseInt((arg1*m+arg2*m))/m).toFixed(mz);
} 
 
//除法
function accDiv(arg1,arg2){
	var t1=0,t2=0,r1,r2;
	try{t1=arg1.toString().split(".")[1].length}catch(e){}
	try{t2=arg2.toString().split(".")[1].length}catch(e){}
	with(Math){
		r1=Number(arg1.toString().replace(".",""))
		r2=Number(arg2.toString().replace(".",""))
		return (r1/r2)*pow(10,t2-t1);
	}
} 


 
 