/*（手机端）华安学平险*/
var temp_html = {}; 
var age_min_temp_date = 0;
var age_max_temp_date = 0;
var currdate_now_Temp = '';
var opt={},opt_1={},opt4={},opt4_1={},opt2={},opt3={},opt3_1={};
var beneficiary_certificates_type_datavalue = [];
$(document).ready(function(){
	  beneficiary_certificates_type_datavalue = $('#text_beneficiary_certificates_type_01_P').data('value');
	  //加载被保险人年龄范围
	  age_min_temp_date = parseInt($('#age_min').val());
	  age_max_temp_date = parseInt($('#age_max').val());
	  
	  var app_useridTemp = $.cookie("app_userid");
	  var app_platformIdTemp = $.cookie("app_platformId");
	  if(app_useridTemp!="" && app_platformIdTemp!="" ){ 
	  	$('#head_top').hide();
		$('#userid_app').val(app_useridTemp);
		$('#platformId').val(app_platformIdTemp); 
	  }
	  if($('#step').val()==1){
		  temp_html = {'jigoucontent':$('#applicant_jigou').html(),'gerencontent':$('#applicant_geren').html()};
		  $('#applicant_jigou').html('');
		  initSet();
	  }
});


//初始化
function initSet(){
  
	$('#app_checked input').on('ifClicked', function(event){
  		var ch_f = $('#applicant_cardValidExps').prop("checked");
		if(!ch_f){
			$('#applicant_cardExpDate').val("9999-12-31");	
			$('#applicant_cardExpDate').prop("disabled",true);		
		}else{
			$('#applicant_cardExpDate').val("");	
			$('#applicant_cardExpDate').prop("disabled",false);		
		}
	});
	 
	$('#assured_checked input').on('ifClicked', function(event){
  		var ch_f = $('#assured_cardValidExps').prop("checked");
		if(!ch_f){
			$('#assured_cardExpDate').val("9999-12-31");	
			$('#assured_cardExpDate').prop("disabled",true);		
		}else{
			$('#assured_cardExpDate').val("");
			$('#assured_cardExpDate').prop("disabled",false);		
		}
	});
	 //加载投保咨询单选按钮
	$('#app_checked input').iCheck({
		checkboxClass: 'icheckbox_square-red',
		increaseArea: '20%' // optional
		 
	});
	
	$('#assured_checked input').iCheck({
		checkboxClass: 'icheckbox_square-red',
		increaseArea: '20%' // optional
		 
	});
	if($('#payPeriod').val()!=0){
		$('#bank_checked_2 input').iCheck({
			checkboxClass: 'icheckbox_square-red',
			increaseArea: '20%' // optional
			 
		});
	}
	
	 
	//加载投保日期
	var currdate = serverNowDate();
	currdate_now_Temp = currdate;
	var currdateNow = serverNowDate();
	 
	
	opt.date = {preset : 'date'};
	opt.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
		lang:'zh',
		startYear:currdate.getFullYear()-100, //开始年份
		startMonth:(currdate.getMonth()),
		startDay:currdate.getDate(),
		endYear:currdate.getFullYear(), //结束年份
		endMonth:(currdate.getMonth()),
		endDay:currdate.getDate()-1,
		onSelect: function(dateText, inst) { 
			if(!$('#applicant_cardValidExps').prop('checked')){
				var project_start_temp = dateText.split('-') ; 
				opt_1['default']['startYear'] = project_start_temp[0];
				opt_1['default']['startMonth'] = (parseInt(project_start_temp[1])-1);
				opt_1['default']['startDay'] = (parseInt(project_start_temp[2])+1);
				opt_1['default']['endYear'] = (parseInt(project_start_temp[0])+100);
				opt_1['default']['endMonth'] = (parseInt(project_start_temp[1])-1);
				opt_1['default']['endDay'] = (parseInt(project_start_temp[2])-1);
				$("#applicant_cardExpDate").val('').scroller('destroy').scroller($.extend(opt_1['date'], opt_1['default'])); 
			}
	   } 
	};
	
	opt_1.date = {preset : 'date'};
	opt_1.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
		lang:'zh'
	};
	
	opt2.date = {preset : 'date'};
	opt2.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
		lang:'zh',
		startYear:currdateNow.getFullYear()-98,//开始年份
		startMonth:(currdateNow.getMonth()),
		startDay:currdateNow.getDate(),
		endYear:currdateNow.getFullYear()-18, //结束年份
		endMonth:(currdateNow.getMonth()),
		endDay:currdateNow.getDate(),
		onSelect: function(dateText, inst) { 
		    
	   } 
	};
	
	opt3.date = {preset : 'date'};
	opt3.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
		lang:'zh',
		startYear:currdateNow.getFullYear()-(age_max_temp_date+1),//开始年份
		startMonth:(currdateNow.getMonth()),
		startDay:currdateNow.getDate()+1,
		endYear:currdateNow.getFullYear()-age_min_temp_date, //结束年份
		endMonth:(currdateNow.getMonth()),
		endDay:currdateNow.getDate(),
		onSelect: function(dateText, inst) { 
		    
	   } 
	};
	
	opt3_1.date = {preset : 'date'};
	opt3_1.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
		lang:'zh',
		startYear:currdateNow.getFullYear()-100,//开始年份
		startMonth:(currdateNow.getMonth()),
		startDay:currdateNow.getDate()+1,
		endYear:currdateNow.getFullYear(), //结束年份
		endMonth:(currdateNow.getMonth()),
		endDay:currdateNow.getDate(),
		onSelect: function(dateText, inst) { 
		    
	   } 
	};
	opt4.date = {preset : 'date'};
	opt4.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
		lang:'zh',
		startYear:currdate.getFullYear()-100, //开始年份
		startMonth:(currdate.getMonth()),
		startDay:currdate.getDate(),
		endYear:currdate.getFullYear(), //结束年份
		endMonth:(currdate.getMonth()),
		endDay:currdate.getDate()-1,
		onSelect: function(dateText, inst) { 
			if(!$('#assured_cardValidExps').prop('checked')){
				var project_start_temp = dateText.split('-') ; 
				opt4_1['default']['startYear'] = project_start_temp[0];
				opt4_1['default']['startMonth'] = (parseInt(project_start_temp[1])-1);
				opt4_1['default']['startDay'] = (parseInt(project_start_temp[2])+1);
				opt4_1['default']['endYear'] = (parseInt(project_start_temp[0])+100);
				opt4_1['default']['endMonth'] = (parseInt(project_start_temp[1])-1);
				opt4_1['default']['endDay'] = (parseInt(project_start_temp[2])-1);
				$("#assured_cardExpDate").val('').scroller('destroy').scroller($.extend(opt4_1['date'], opt4_1['default'])); 
			}
	   } 
	};
	
	opt4_1.date = {preset : 'date'};
	opt4_1.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
		lang:'zh'
	};
	$("#applicant_cardValidDate").val('').scroller('destroy').scroller($.extend(opt['date'], opt['default']));
	$("#assured_cardValidDate").val('').scroller('destroy').scroller($.extend(opt4['date'], opt4['default']));
	$("#applicant_birthday").val('').scroller('destroy').scroller($.extend(opt2['date'], opt2['default']));
	//$("#assured_birthday").val('').scroller('destroy').scroller($.extend(opt3['date'], opt3['default']));
	
	setBenefic_date(1);
	provice_city_Ajax('text_select_province_P','province');
	provice_city_Ajax('text_select_assured_province_P','province',2);
	get_industry_info('text_select_applicant_business_type_P','industry');
	get_organization('text_select_applicant_p_organization_P','P'); 
	get_bank_info();
}

//获取服务器时间并且加上间隔时间
function serverDate(){
	var start_day = $("#start_day").val();
	if(start_day==0){
		start_day = 6;
	}
	var date = $("#server_time").val();
	date = parseInt(date);
	var bombay = date + (3600 * 24)*start_day;
	var time_s = new Date(bombay*1000);
	 
	return time_s;
}


//获取服务器时间不加间隔时间
function serverNowDate(){
	 
	var date = $("#server_time").val();
	date = parseInt(date);
	 
	var time_s = new Date(date*1000);
	 
	return time_s;
}

//遍历购买份数
function showSelect_applyNum(domid,domPid,layertype){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(layertype){ //判定 存在 不弹出选择框
		initflag = false;
	}
	showSelect(initflag,domid,'applynum_name',str_data,showSelect_applyNum_callback);
}

function showSelect_applyNum_callback(){
	setPrices();
}


//遍历是个人还是机构
function showSelect_applicant_type(domid,domPid,layertype){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(layertype){ //判定 存在 不弹出选择框
		initflag = false;
	}
	showSelect(initflag,domid,'applicant_type_name',str_data,showSelect_applicant_type_callback);
}
 

//遍历是个人还是机构的回调函数
function showSelect_applicant_type_callback(){
	var applicant_type_str = $('#text_select_applicant_type').data("applicant_type_id"); 
	if(applicant_type_str==1){
		$('#applicant_geren').html(temp_html.gerencontent);
		$('#applicant_jigou').hide();
		$('#applicant_jigou').html('');
		$('#applicant_geren').show();
		
	}else if(applicant_type_str==2){
		$('#applicant_jigou').html(temp_html.jigoucontent);
		$('#applicant_geren').hide();	
		$('#applicant_geren').html('');	
		$('#applicant_jigou').show();
		
	}
	
}


//身份类型   type =1 投保人  2 被保险人  3 受益人
function showSelect_certificates_type(domid,domPid,type,layertype,str_num){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(layertype){ //判定 存在 不弹出选择框
		initflag = false;
	}
	if(type==1){
		showSelect(initflag,domid,'applicant_certificates_type_name',str_data,showSelect_appcertificates_type_callback);	
	}else if(type==2){
		showSelect(initflag,domid,'assured_certificates_type_name',str_data);	
	}else if(type==3){
		$('#beneficiary_certificates_type_numsq').val(str_num);
		showSelect(initflag,domid,'beneficiary_certificates_type_name',str_data,showSelect_Bcertificates_type_callback);	
	}
}

//投保人身份类型返回绑定事件
function showSelect_appcertificates_type_callback(){
	var strstcodetype = $('#text_applicant_certificates_type').data('applicant_certificates_type_id');
 
	$('#applicant_certificates_type').val(strstcodetype);
	$('#applicant_certificates_code').val('');
	$('#text_select_toubaoren_sex').text('');
	$('#text_select_toubaoren_sex').data('sex_id','');
	$('#text_select_toubaoren_sex').data('sex_name','');
	$('#applicant_birthday').val('');
	if(strstcodetype==1){
		$('#applicant_certificates_code').unbind("blur");	
		$('#applicant_certificates_code').bind("blur",function(){
			checked_codebirsex_test('applicant_certificates_code','applicant_birthday','text_select_toubaoren_sex','applicant_sex','app',true,1,'text_select_relationshipWithInsured');
		});	
		$('#text_select_toubaoren_sex_P').unbind("click");
		$("#applicant_birthday").val('').scroller('destroy');
	
	}else{
		$('#applicant_certificates_code').unbind("blur");
		$('#text_select_toubaoren_sex_P').unbind("blur");
		$('#text_select_toubaoren_sex_P').bind("click",function(){
			showSelect_SexBase('text_select_toubaoren_sex','text_select_toubaoren_sex_P','sex')	
			
		});
		$("#applicant_birthday").val('').scroller('destroy').scroller($.extend(opt2['date'], opt2['default']));
	}
}

//受益人身份类型返回绑定事件
function showSelect_Bcertificates_type_callback(){
	var str_numsq = parseInt($('#beneficiary_certificates_type_numsq').val());
	var strstcodetype = $('#text_beneficiary_certificates_type_0'+str_numsq).data('beneficiary_certificates_type_id');
	$('#beneficiary_certificates_type_0'+str_numsq).val(strstcodetype);
	$('#beneficiary_certificates_code_0'+str_numsq).val('');
	$('#text_select_shouyiren_sex_0'+str_numsq).text('');
	$('#text_select_shouyiren_sex_0'+str_numsq).data('sex_id','');
	$('#text_select_shouyiren_sex_0'+str_numsq).data('sex_name','');
	$('#beneficiary_birthday_0'+str_numsq).val('');
	 
	if(strstcodetype==1){
		$('#beneficiary_certificates_code_0'+str_numsq).unbind("blur");	
		$('#beneficiary_certificates_code_0'+str_numsq).bind("blur",function(){
			checked_codebirsex_test('beneficiary_certificates_code_0'+str_numsq,'beneficiary_birthday_0'+str_numsq,'text_select_shouyiren_sex_0'+str_numsq,'','app',true,3,'text_select_beneficiary_benefitRelation_0'+str_numsq);
		});	
		$('#text_select_shouyiren_sex_0'+str_numsq+'_P').unbind("click");
		$("#beneficiary_birthday_0"+str_numsq).val('').scroller('destroy');
	}else{
		$('#beneficiary_certificates_code_0'+str_numsq).unbind("blur");
		$('#text_select_shouyiren_sex_0'+str_numsq+'_P').unbind("blur");
		$('#text_select_shouyiren_sex_0'+str_numsq+'_P').bind("click",function(){
			showSelect_SexBase('text_select_shouyiren_sex_0'+str_numsq,'text_select_shouyiren_sex_0'+str_numsq+'_P','sex',false,3);
		});
		$("#beneficiary_birthday_0"+str_numsq).val('').scroller('destroy').scroller($.extend(opt3_1['date'], opt3_1['default']));
	}
}


//投保人和受益人性别    sq_type 等于3是受益人  其他为投保人
function showSelect_SexBase(domid,domPid,val_str,layertype,sq_type){
	var str_data = $('#'+domPid).data('value');
	if(typeof(str_data)=="string"){
		str_data = $.parseJSON(str_data);
	}
	var initflag = true;
	if(layertype){ //判定 存在 不弹出选择框
		initflag = false;
	}
	if(sq_type==3){
		showSelect(initflag,domid,val_str+'_name',str_data,showSelect_SexBase_beneficiary_callback);
	}else{
		showSelect(initflag,domid,val_str+'_name',str_data,showSelect_SexBase_callback);
	}
	
}
//投保人性别回调
function showSelect_SexBase_callback(){
	var tbr_valueTemp = $('#text_select_toubaoren_sex').data('sex_id');
	 
	gender_ass_appli(tbr_valueTemp,'text_select_relationshipWithInsured');
}

//受益人性别回调
function showSelect_SexBase_beneficiary_callback(){
	var str_numsq = parseInt($('#beneficiary_certificates_type_numsq').val());
	var tbr_valueTemp = $('#text_select_shouyiren_sex_0'+str_numsq).data('sex_id');
	gender_ass_beneficiary(tbr_valueTemp,'text_select_beneficiary_benefitRelation_0'+str_numsq);
}

//投保人和被保险人关系赋值
function gender_ass_appli(f_str,relationshipWithInsured_dom){
	var s_str = $('#gender_temp').val();
	var num_tempQ = 21;
	var tempQ_name = '父子';
	if(f_str=='M' && s_str=='F'){
		num_tempQ = 31;
		tempQ_name = '父女';
	}else if(f_str=='F' && s_str=='F'){
		num_tempQ = 32; 
		tempQ_name = '母女';
	}else if(f_str=='F' && s_str=='M'){
		num_tempQ = 22; 
		tempQ_name = '母子';
	}else if(f_str=='M' && s_str=='M'){
		num_tempQ = 21; 
		tempQ_name = '父子';
	}	
	$('#'+relationshipWithInsured_dom).data('relationship_id',num_tempQ); 
	$('#'+relationshipWithInsured_dom).data('relationship_name',tempQ_name); 
	$('#'+relationshipWithInsured_dom).text(tempQ_name); 
}
//受益人和被保险人关系赋值
function gender_ass_beneficiary(f_str,beneficiary_dom){
	var s_str = $('#gender_temp').val();
	var num_tempQ = '01';
	var tempQ_name = '父子';
	if(f_str=='M' && s_str=='F'){
		num_tempQ = '02';
		tempQ_name = '父女';
	}else if(f_str=='F' && s_str=='F'){
		num_tempQ = '04'; 
		tempQ_name = '母女';
	}else if(f_str=='F' && s_str=='M'){
		num_tempQ = '03'; 
		tempQ_name = '母子';
	}else if(f_str=='M' && s_str=='M'){
		num_tempQ = '01'; 
		tempQ_name = '父子';
	}
	$('#'+beneficiary_dom).data('relationship_id',num_tempQ); 
	$('#'+beneficiary_dom).data('relationship_name',tempQ_name); 
	$('#'+beneficiary_dom).text(tempQ_name);
}

 
//获取城市列表
function provice_city_Ajax(domid,val_type,type){
	var action_val = 'get_'+val_type;
	var datajson = {'op':'get_address_info','action':action_val};
	var tempname = 'province';
	 
	if(val_type=='city_by_province'){
		if(type==1){
			datajson['province_code']=$('#text_select_province').data('province_id');
		}else if(type==2){
			datajson['province_code']=$('#text_select_assured_province').data('province_id');
			
		}
		tempname = 'city';
	}else if(val_type=='county_by_city'){
		tempname = 'county';
		if(type==1){
			datajson['city_code']=$('#text_select_city').data('city_id');
		}else if(type==2){
			datajson['city_code']=$('#text_select_assured_city').data('city_id');
		}
	}
	$.ajax({
		 type: 'POST',
		 url: '../oop/business/get_NCI_Data.php' ,
		 async:false,
		 data: datajson ,
		 dataType: "JSON",
		 success: function(data){
			var html_pro_city = [];
			//console.log(JSON.stringify(data));
			$(data).each(function(i, n){
				if(val_type=='city_by_province'){
					html_pro_city.push({"city_id":n[tempname+'_code'],'city_name':n[tempname+'_name']}); 
				}else if(val_type=='county_by_city'){
					html_pro_city.push({"county_id":n[tempname+'_code'],'county_name':n[tempname+'_name']}); 
				}else{
					html_pro_city.push({"province_id":n[tempname+'_code'],'province_name':n[tempname+'_name']}); 
				}
			});
			if(val_type=='county_by_city' && html_pro_city==""){ //三级缺省填充二级内容到三级
				var temp_ids = '';
				var temp_names = '';
				 if(type==2){
					temp_ids = $('#text_select_assured_city').data('city_id'); 
					temp_names = $('#text_select_assured_city').data('city_name');
					
				}else{
					temp_ids = $('#text_select_city').data('city_id');
					temp_names = $('#text_select_city').data('city_name');	
					 
				}
				html_pro_city.push({"county_id":temp_ids,'county_name':temp_names});
			}
			$('#'+domid).data('value',html_pro_city);
			 
		 }
	});	 
}

//遍历省份城市地区 type 1 投保人  2 被保险人
function showSelectprovincecity(domid,domPid,str_name,type){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(type==1){
		if(str_name=='province'){
			showSelect(initflag,domid,str_name+'_name',str_data,showSelectprovince_callback);
		}else if(str_name=='city'){
			showSelect(initflag,domid,str_name+'_name',str_data,showSelectcity_callback);
		}
		
	}else if(type==2){
		if(str_name=='province'){
			showSelect(initflag,domid,str_name+'_name',str_data,showSelectass_province_callback);
		}else if(str_name=='city'){
			showSelect(initflag,domid,str_name+'_name',str_data,showSelectass_city_callback);
		}
	}
}
//投保人省份回调函数
function showSelectprovince_callback(){
	$('#text_select_city').data('city_id','');
	$('#text_select_city').data('city_name','');
	$('#text_select_city').text('');
	$('#text_select_county').data('county_id','');
	$('#text_select_county').data('county_name','');
	$('#text_select_county').text('');
	provice_city_Ajax('text_select_city_P','city_by_province',1);
	
}
//投保人城市回调函数
function showSelectcity_callback(){
	$('#text_select_county').data('county_id','');
	$('#text_select_county').data('county_name','');
	$('#text_select_county').text('');
	provice_city_Ajax('text_select_county_P','county_by_city',1);
	
}
//被保险人省份回调函数
function showSelectass_province_callback(){
	$('#text_select_assured_city').data('city_id','');
	$('#text_select_assured_city').data('city_name','');
	$('#text_select_assured_city').text('');
	$('#text_select_assured_county').data('county_id','');
	$('#text_select_assured_county').data('county_name','');
	$('#text_select_assured_county').text('');
	provice_city_Ajax('text_select_assured_city_P','city_by_province',2);
	
}
//被保险人城市回调函数
function showSelectass_city_callback(){
	$('#text_select_assured_county').data('county_id','');
	$('#text_select_assured_county').data('county_name','');
	$('#text_select_assured_county').text('');
	provice_city_Ajax('text_select_assured_county_P','county_by_city',2);
}


//获取机构代码
function get_organization(domid,val_type){
	 
	var datajson = {'op':'get_organization'};
	if(val_type=='P'){
		datajson['parent_code']=86;
	}else if(val_type=='C'){
		datajson['parent_code']=$('#text_select_applicant_p_organization').data('organization_id');
	}
	$.ajax({
		 type: 'POST',
		 url: '../oop/business/get_NCI_Data.php' ,
		 async:false,
		 data: datajson ,
		 dataType: "JSON",
		 success: function(data){
			var html_pro_city = [];
			//console.log(JSON.stringify(data));
			$(data).each(function(i, n){
				if(val_type=='P'){
					html_pro_city.push({'organization_id':n.org_code,'organization_name':n.org_name});
				}else if(val_type=='C'){
					html_pro_city.push({'serviceorgcode_id':n.org_code,'serviceorgcode_name':n.org_name});
				}
			});
			$('#'+domid).data('value',html_pro_city);
			 
		 }
	});	 
}
//服务机构
function showSelect_applicant_p_organization(domid,domPid,str_name){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	showSelect(initflag,domid,str_name+'_name',str_data,showSelect_applicant_p_organization_callback);
}
//服务机构的回调函数
function showSelect_applicant_p_organization_callback(){
	$('#text_select_serviceOrgCode').data('serviceorgcode_id','');
	$('#text_select_serviceOrgCode').data('serviceorgcode_name','');
	$('#text_select_serviceOrgCode').text('');
	 
	get_organization('text_select_serviceOrgCode_P','C');
	
}




//获取职业代码
function get_industry_info(domid,val_type,type){
	 
	var datajson = {'op':'get_industry_info','action':'get_'+val_type};
	if(val_type=='career'){
		if(type==2){
			datajson['industry_code']=$('#text_select_assured_business_type').data('business_type_id');
		}else{
			datajson['industry_code']=$('#text_select_applicant_business_type').data('business_type_id');
		}
		
	}
	$.ajax({
		 type: 'POST',
		 url: '../oop/business/get_NCI_Data.php' ,
		 async:false,
		 data: datajson ,
		 dataType: "JSON",
		 success: function(data){
			var html_pro_city = [];
			//console.log(JSON.stringify(data));
			$(data).each(function(i, n){
				if(val_type=='industry'){
					html_pro_city.push({'business_type_id':n[val_type+'_code'],'business_type_name':n[val_type+'_name']});
				}else if(val_type=='career'){
				 	html_pro_city.push({'occupationclasscode_id':n[val_type+'_code'],'occupationclasscode_name':n[val_type+'_name']});
				}
			});
			$('#'+domid).data('value',html_pro_city);
			 
		 }
	});	 
}
//获取职业代码选择
function showSelect_applicant_business_type(domid,domPid,str_name,type){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(type==1){
		showSelect(initflag,domid,str_name+'_name',str_data,showSelect_applicant_business_type_callback);
	}else if(type==2){
		showSelect(initflag,domid,str_name+'_name',str_data,showSelect_ass_business_type_callback);
	}
}
//获取职业代码选择回调函数
function showSelect_applicant_business_type_callback(){
	 
	$('#text_select_applicant_occupationClassCode').data('occupationclasscode_id','');
	$('#text_select_applicant_occupationClassCode').data('occupationclasscode_name','');
	$('#text_select_applicant_occupationClassCode').text('');
	get_industry_info('text_select_applicant_occupationClassCode_P','career');
	
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
			var html_pro_city = [];
			//console.log(JSON.stringify(data));
			$(data).each(function(i, n){
				 html_pro_city.push({'bank_id':n.bank_code,'bank_name':n.bank_name});
			});
			$('#text_select_bank_P').data("value",html_pro_city);
		 }
	});	 
}

//受益人弹出  
function showSelect_beneficiary(domid,domPid,val_str,layertype){
	var str_data = $('#'+domPid).data('value');
	if(typeof(str_data)=="string"){
		str_data = $.parseJSON(str_data);
	}
	var initflag = true;
	if(layertype){ //判定 存在 不弹出选择框
		initflag = false;
	}
	 
	showSelect(initflag,domid,val_str+'_name',str_data,showSelect_beneficiary_callback);
}
//受益人弹出回调
function showSelect_beneficiary_callback(){
	var beneficiary_idTemp = $('#text_select_beneficiary').data('beneficiary_id');
	if(beneficiary_idTemp==1){
		$('#zhiding_beneficiary').hide();
		$('#fading_beneficiary').show();
	}else if(beneficiary_idTemp==0){
		$('#fading_beneficiary').hide();
		$('#zhiding_beneficiary').show();
	}
}

//投保填写页面验证
function submit_checked(){
	 var flag_submit = true;
	  
	 var applicant_type_id = $('#text_select_applicant_type').data("applicant_type_id");
	 if(applicant_type_id==1 || applicant_type_id==2){
		 $('#applicant_type').val(applicant_type_id);
		 if(applicant_type_id==1){
			  //个人
			  if(!check_applicant_name('applicant_fullname','tbr','app')){
				  showMessages('投保人姓名3-24个字符!','applicant_fullname');
				  flag_submit = false;
				  return false;
			  }
			  if(!$('#text_applicant_certificates_type').data('applicant_certificates_type_id') || $('#text_applicant_certificates_type').data('applicant_certificates_type_id')==""){
				 showMessages('投保人证件类型没有选择!','text_applicant_certificates_type');
				 flag_submit = false;
				 return false; 
			  }
			  $('#applicant_certificates_type').val($('#text_applicant_certificates_type').data('applicant_certificates_type_id')); 
			  if(!checked_datavalueAll('text_select_toubaoren_sex','sex_id').flag){
				  showMessages('投保人性别不能为空!','text_select_toubaoren_sex');
				  flag_submit = false;
				  return false;
			  }
			  $('#applicant_sex').val($('#text_select_toubaoren_sex').data('sex_id'));  
			  if(!check_applicant_card('applicant_certificates_code','applicant_certificates_type','app')){
			 	 showMessages('投保人证件号码为空或者证件号码无效!','applicant_certificates_code');
				 flag_submit = false;
				 return false; 
			 }else if($('#applicant_cardValidDate').val()==""){
				 showMessages('投保人证件起期不能为空!','applicant_cardValidDate');
				 flag_submit = false;
				 return false;
			 }else if($('#applicant_cardExpDate').val()==""){
				 showMessages('投保人证件止期不能为空!','applicant_cardExpDate');
				 flag_submit = false;
				 return false;
			 }else if($.trim($('#applicant_birthday').val())==""){
				 showMessages('投保人生日不能为空!','applicant_birthday');
				 flag_submit = false;
				 return false; 
			 }
			 var bbxr_birTs = getAgeByBirthday($.trim($('#applicant_birthday').val()));
				  
			 if(bbxr_birTs<18){
				 showMessages('投保人必须大于18周岁!','applicant_birthday');
				 flag_submit = false;
				 return false; 
			 }else if(!check_applicant_mobilephone('applicant_mobilephone','app')){
				 showMessages('投保人手机号码不正确或者无效!','applicant_mobilephone');
				 flag_submit = false;
				 return false; 
			 }else if(!check_applicant_email('applicant_email','app')){
				 showMessages('投保人Email为空或者格式不正确!','applicant_email');
				 flag_submit = false;
				 return false; 
			 }else if(!checkzipcode('applicant_zipcode','app')){
				 showMessages('投保人邮政编码格式不正确! 如：100197','applicant_zipcode');
				 flag_submit = false;
				 return false;  
			 }else if($.trim($('#applicant_address').val())==""){
				 showMessages('投保人详细地址不能为空!','applicant_address');
				 flag_submit = false;
				 return false;  
			 }
			 if(!$('#text_select_province').data('province_id')){
				 showMessages('投保人省份没有选择!','text_select_province_P');
				 flag_submit = false;
				 return false;  
			 }
			 $('#applicant_province_code').val($('#text_select_province').data('province_id'));
			 if(!$('#text_select_city').data('city_id')){
				 showMessages('投保人城市没有选择!','text_select_city_P');
				 flag_submit = false;
				 return false;  
			 }
			 $('#applicant_city_code').val($('#text_select_city').data('city_id'));
			 
			 if(!$('#text_select_county').data('county_id')){
				 showMessages('投保人地区没有选择!','text_select_county_P');
				 flag_submit = false;
				 return false;
			 }
			 $('#applicant_county_code').val($('#text_select_county').data('county_id'));
			 
			 if(!$('#text_select_applicant_p_organization').data('organization_id')){
				 showMessages('投保人服务机构(总)没有选择!','text_select_applicant_p_organization_P');
				 flag_submit = false;
				 return false;
			 }
			 $('#applicant_p_organization').val($('#text_select_applicant_p_organization').data('organization_id'));
			 
			 if(!$('#text_select_serviceOrgCode').data('serviceorgcode_id')){
				 showMessages('投保人服务机构(分)没有选择!','text_select_serviceOrgCode_P');
				 flag_submit = false;
				 return false;
			 }
			 $('#serviceOrgCode').val($('#text_select_serviceOrgCode').data('serviceorgcode_id'));
			 
			 
			 if(!$('#text_select_applicant_business_type').data('business_type_id')){
				 showMessages('投保人职业(一级)没有选择!','text_select_applicant_business_type_P');
				 flag_submit = false;
				 return false;
			 }
			 $('#applicant_business_type').val($('#text_select_applicant_business_type').data('business_type_id'));
			 
			 if(!$('#text_select_applicant_occupationClassCode').data('occupationclasscode_id')){
				 showMessages('投保人职业(二级)没有选择!','text_select_applicant_occupationClassCode_P');
				 flag_submit = false;
				 return false;
			 }
			 $('#applicant_occupationClassCode').val($('#text_select_applicant_occupationClassCode').data('occupationclasscode_id'));
			 
			 
		 }
		  
		//被保险人验证 
		 
		 var text_select_relationshipWithInsured = $('#text_select_relationshipWithInsured').data('relationship_id');
		 if(!text_select_relationshipWithInsured){
			 showMessages('与投保人关系没有选择!','text_select_relationshipWithInsured');
			 flag_submit = false;
			 return false; 
		 }
		 $('#relationshipWithInsured').val(text_select_relationshipWithInsured);
		
		 if(!check_applicant_name('assured_fullname','bbxr','app')){
			  showMessages('被保险人姓名3-24个字符!','assured_fullname');
			  flag_submit = false;
			  return false;
		  }
		  if(!$('#text_select_assured_certificates_type').data('assured_certificates_type_id') || $('#text_select_assured_certificates_type').data('assured_certificates_type_id')==""){
			 showMessages('被保险人件类型没有选择!','text_select_assured_certificates_type_P');
			 flag_submit = false;
			 return false; 
		 }
		 $('#assured_certificates_type').val($('#text_select_assured_certificates_type').data('assured_certificates_type_id')); 
		  
		 if(!check_applicant_card('assured_certificates_code','assured_certificates_type','app')){
			 showMessages('被保险人证件号码为空或者证件号码无效!','assured_certificates_code');
			 flag_submit = false;
			 return false; 
		 }else if(!$('#assured_cardValidDate').val()){
			 showMessages('被保险人证件起期没有填写!','assured_cardValidDate');
			 flag_submit = false;
			 return false; 
		 }else if(!$('#assured_cardExpDate').val()){
			 showMessages('被保险人证件止期没有填写!','assured_cardExpDate');
			 flag_submit = false;
			 return false; 
		 }else if($.trim($('#assured_birthday').val())==""){
			 showMessages('被保险人生日不能为空!','assured_birthday');
			 flag_submit = false;
			 return false; 
		 }else if(!check_applicant_mobilephone('assured_mobilephone','app')){
			 showMessages('被保险人手机号码不正确或者无效!','assured_mobilephone');
			 flag_submit = false;
			 return false; 
		 }else if($.trim($('#assured_address').val())==""){
			 showMessages('被保险人详细地址不能为空!','assured_address');
			 flag_submit = false;
			 return false;  
		 }
		 
		 if($('#text_select_assured_certificates_type').data('assured_certificates_type_id')==1){
			if(!checked_codebirsex('assured_certificates_code','assured_birthday','text_select_assured_sex','app',false)){
				 showMessages('被保险人省份证、生日和性别不一致!','assured_certificates_code');
				 flag_submit = false;
				 return false; 
			 } 
		 }
		 
		 
		 if(!$('#text_select_assured_province').data('province_id')){
			 showMessages('被保险人省份没有选择!','text_select_assured_province_P');
			 flag_submit = false;
			 return false;  
		 }
		 $('#assured_province_code').val($('#text_select_assured_province').data('province_id'));
		 if(!$('#text_select_assured_city').data('city_id')){
			 showMessages('被保险人城市没有选择!','text_select_assured_city_P');
			 flag_submit = false;
			 return false;  
		 }
		 $('#assured_city_code').val($('#text_select_assured_city').data('city_id'));
		 
		 if(!$('#text_select_assured_county').data('county_id')){
			 showMessages('被保险人地区没有选择!','text_select_assured_county_P');
			 flag_submit = false;
			 return false;
		 }
		 $('#assured_county_code').val($('#text_select_assured_county').data('county_id'));
		  
		  
		 if(!$('#text_select_assured_occupationClassCode').data('occupationclasscode_id')){
			 showMessages('被保险人职业(二级)没有选择!','text_select_assured_occupationClassCode_P');
			 flag_submit = false;
			 return false;
		 }
		 $('#assured_occupationClassCode').val($('#text_select_assured_occupationClassCode').data('occupationclasscode_id'));
	 	 
		 var beneficiary_idSave = $('#text_select_beneficiary').data('beneficiary_id'); 
		 if(beneficiary_idSave!=1 && beneficiary_idSave!=0){
			 showMessages('受益人没有选择!','text_select_beneficiary_P');
			 flag_submit = false;
			 return false;
		 }
		 $('#beneficiary').val(beneficiary_idSave);
		 if(beneficiary_idSave==0){
			var checked_save_res = checked_save_beneficiary();
			if(checked_save_res.flag==false){
				showMessages(checked_save_res.wrong_text,checked_save_res.domid);
				flag_submit = false;
			 	return false;
			}
		 }
		 
		 if($('#payPeriod').val()!=0){
			if(!$('#text_select_bank').data('bank_id')){
				 
				 showMessages('续期交费银行没有选择!','text_select_bank_P');
				 flag_submit = false;
				 return false;
			}
			$('#partPayBankCode').val($('#text_select_bank').data('bank_id'));
			if($.trim($('#bank_username').val())==""){
				 
				 showMessages('续期交费银行账户姓名没有填写!','bank_username');
				 flag_submit = false;
				 return false;
			}else if($.trim($('#bank_cardnumber').val())==""){
				 
				 showMessages('续期交费银行账户卡号没有填写!','bank_cardnumber');
				 flag_submit = false;
				 return false;
			}else if(!$('#bank_checked_ok').prop("checked")){
				 
				 showMessages('没有勾选《同意保险费自动转账授权声明》!','bank_checked');
				 flag_submit = false;
				 return false;
				 
			}
		}
		  
		 if(!ydtychecked()){
			  showMessages('保险条款、投保须知、投保声明 三者有没有选中的项!','bxtk_01');
			  flag_submit = false;
			  return false;
		 }
		  
	 }else{
		 showMessages('投保人类型为空,请重新刷新页面!','text_select_applicant_type'); 
		 flag_submit = false;
		 return false;	 
	 }
	 
	 if(flag_submit){
		$('#orderForm input:disabled').prop("disabled",false); 
		$('#submit_link').click();	 
	 }
}

//投保确认页面验证提交
function steptwo_queck(){
	if(!ydtychecked()){
	   showMessages('保险条款、投保须知、投保声明 三者有没有选中的项!','bxtk_01');
	   return false;
    }
	layer.open({
		content: '你是想确认提交订单吗？',
		btn: ['确认', '取消'],
		shadeClose: false,
		yes: function(index){
			layer.close(index);
			return true;
		}, no: function(index){
			layer.close(index);
			return false;
		}
	});
}


function checked_codebirsex_test(code_str,br_str,str_sex,sex_dominput,type,showflag,a_a_type,sts_dom_relationshi){
	var res_str = checked_codebirsex(code_str,br_str,str_sex,type,showflag);
	if(res_str){
		if(sex_dominput!=""){
			$('#'+sex_dominput).val($('#'+str_sex).data('sex_id'));
		}
		if(a_a_type==1){
			gender_ass_appli($('#'+str_sex).data('sex_id'),sts_dom_relationshi);
		}else if(a_a_type==3){
			gender_ass_beneficiary($('#'+str_sex).data('sex_id'),sts_dom_relationshi);
		}
	}else{
		showMessages('身份证号码不正确!',code_str);	
		$('#'+sex_dominput).val('');
		$('#'+str_sex).text('');
		$('#'+br_str).val('');
	}
}

function bank_name(str){
	$('#bank_username').val(str);
}


//增加受益人
function add_beneficiary(){
	var data_value_len = $('#zhiding_beneficiary_list').children("div:last").data('value');
	var sum_len = $('#zhiding_beneficiary_list').children().length;
	 
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
		var html_beneficiary = '<div class="panel panel-default" id="zhiding_beneficiary_0'+st_len+'" data-value="'+st_len+'"><div class="panel-body f-csp"  onClick="show_hide(this,true)">受益人('+str_small2bignum+')<span class="pull-right" style="height:20px; width:20px; display:block"><img src="../mobile/dist/images/drop_down_card_btn_open.png" class="img-responsive" ></span><span class="f-fs2 pull-right"><a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="delete_beneficiary('+st_len+')">删除</a></span></div><ul class="list-group" id="zhiding_beneficiary_0'+st_len+'"><li class="list-group-item" ><span class="s-c-aaaaaa">姓名</span><span class="f-fs2 pull-right"><input type="text" id="beneficiary_name_0'+st_len+'" placeholder="名字长度至少2的汉字" class="input_b text-right"><i class=" f-ml10 fa fa-user s-c-aaaaaa"></i></span></li><a class="list-group-item" href="javascript:void(0)" id="text_beneficiary_certificates_type_0'+st_len+'_P" onClick="showSelect_certificates_type(\'text_beneficiary_certificates_type_0'+st_len+'\',\'text_beneficiary_certificates_type_0'+st_len+'_P\',3,false,'+st_len+')"><span class="s-c-aaaaaa">证件类型</span><span class="pull-right"><span id="text_beneficiary_certificates_type_0'+st_len+'"></span><i class="fa fa-angle-right s-c-aaaaaa f-fs6 f-ml10"></i></span></a><li class="list-group-item" ><span class="s-c-aaaaaa">证件号码</span><span class="f-fs2 pull-right"><input type="text"   id="beneficiary_certificates_code_0'+st_len+'" class="input_b text-right g-w150"><i class=" f-ml10 fa fa-credit-card s-c-aaaaaa"></i></span></li><li class="list-group-item" ><span class="s-c-aaaaaa">证件起期</span><span class="f-fs2 pull-right f-pr"><input type="text"   id="beneficiary_cardValidDate_0'+st_len+'" placeholder="选择证件起期" class="input_b text-right g-w100" readonly="readonly"><i class=" f-ml10 fa fa-calendar s-c-aaaaaa"></i></span></li><li class="list-group-item" ><span class="s-c-aaaaaa">证件止期</span><span class="f-fs2 pull-right"><input type="text" id="beneficiary_cardExpDate_0'+st_len+'" placeholder="选择证件止期" class="input_b text-right g-w100" readonly="readonly"><span id="beneficiary_checked_0'+st_len+'" class="s-c-aaaaaa"><input type="checkbox" id="beneficiary_cardValidExps_0'+st_len+'" /> 长期</span><i class="fa fa-calendar s-c-aaaaaa"></i></span></li><a class="list-group-item" href="javascript:void(0)" id="text_select_shouyiren_sex_0'+st_len+'_P"  data-value=\'[{"sex_id":"M","sex_name":"男"},{"sex_id":"F","sex_name":"女"}]\'><span class="s-c-aaaaaa">性别</span><span class="pull-right"><span id="text_select_shouyiren_sex_0'+st_len+'"></span><i class="fa fa-angle-right s-c-aaaaaa f-fs6 f-ml10"></i></span></a><li class="list-group-item" ><span class="s-c-aaaaaa">出生日期</span><span class="f-fs2 pull-right"><input type="text" id="beneficiary_birthday_0'+st_len+'" class="input_b text-right" readonly></span></li><li class="list-group-item" ><span class="s-c-aaaaaa">与被保险人关系</span><span class="f-fs2 pull-right" id="text_select_beneficiary_benefitRelation_0'+st_len+'"></span></li><li class="list-group-item" ><span class="s-c-aaaaaa">受益比例</span><span class="f-fs2 pull-right"><input type="text" id="beneficiary_benefitScale_0'+st_len+'" class="input_b text-right" placeholder="请填写受益比例"> %</span></li></ul></div>';
		
		$('#zhiding_beneficiary_list').append(html_beneficiary); 
		setBenefic_date(st_len);
	
	}else{
		showMessages('最多只能添加三个受益人！');	
	}
}


function setBenefic_date(st_len){
	$('#text_beneficiary_certificates_type_0'+st_len+'_P').data("value",beneficiary_certificates_type_datavalue);
	var opt5 = {},opt5_1 = {};
		opt5.date = {preset : 'date'};
		opt5.default = {
			theme: 'android-ics light', //皮肤样式
			display: 'modal', //显示方式 
			mode: 'scroller', //日期选择模式
			lang:'zh',
			startYear:currdate_now_Temp.getFullYear()-100, //开始年份
			startMonth:(currdate_now_Temp.getMonth()),
			startDay:currdate_now_Temp.getDate(),
			endYear:currdate_now_Temp.getFullYear(), //结束年份
			endMonth:(currdate_now_Temp.getMonth()),
			endDay:currdate_now_Temp.getDate()-1,
			onSelect: function(dateText, inst) { 
				if(!$('#beneficiary_cardValidExps_0'+st_len).prop('checked')){
					var project_start_temp = dateText.split('-') ; 
					opt5_1['default']['startYear'] = project_start_temp[0];
					opt5_1['default']['startMonth'] = (parseInt(project_start_temp[1])-1);
					opt5_1['default']['startDay'] = (parseInt(project_start_temp[2])+1);
					opt5_1['default']['endYear'] = (parseInt(project_start_temp[0])+100);
					opt5_1['default']['endMonth'] = (parseInt(project_start_temp[1])-1);
					opt5_1['default']['endDay'] = (parseInt(project_start_temp[2])-1);
					$("#beneficiary_cardExpDate_0"+st_len).val('').scroller('destroy').scroller($.extend(opt5_1['date'], opt5_1['default'])); 
				}
		   } 
		};
		
		opt5_1.date = {preset : 'date'};
		opt5_1.default = {
			theme: 'android-ics light', //皮肤样式
			display: 'modal', //显示方式 
			mode: 'scroller', //日期选择模式
			lang:'zh'
		};
		$("#beneficiary_cardValidDate_0"+st_len).val('').scroller('destroy').scroller($.extend(opt5['date'], opt5['default'])); 
		$('#beneficiary_checked_0'+st_len+' input').off("ifClicked").on('ifClicked', function(event){
			var ch_f = $('#beneficiary_cardValidExps_0'+st_len).prop("checked");
			if(!ch_f){
				$("#beneficiary_cardExpDate_0"+st_len).val("9999-12-31");	
				$("#beneficiary_cardExpDate_0"+st_len).prop("disabled",true);		
			}else{
				$("#beneficiary_cardExpDate_0"+st_len).val("");	
				$("#beneficiary_cardExpDate_0"+st_len).prop("disabled",false);		
			}
		});
		$('#beneficiary_checked_0'+st_len+' input').iCheck({
			checkboxClass: 'icheckbox_square-red',
			increaseArea: '20%' // optional
			 
		});	
}

//删除受益人
function delete_beneficiary(var_num){
	 
	$('#zhiding_beneficiary_0'+var_num).remove();
}



//验证受益人填写并且保存
function checked_save_beneficiary(){
	
	var strlength = $('#zhiding_beneficiary_list').children().length;
	var flag_ben = true;
	var wrong_text = "";
	var domidTemp = '';
	var parentjsons = [];
	var str_Bai = 0;
	var sqTemp = 0;
	var dom_idTemp = '';
	if(strlength>0){
		$('#zhiding_beneficiary_list').children().each(function(i, n) {
			sqTemp++;
			dom_idTemp = 'zhiding_beneficiary_0'+sqTemp;
            var childjsons = {'beneficiary_benefitSort':1};
			childjsons['beneficiary_nativePlace'] = 37; 
			var mode_datavalue = $(this).data('value');
			var wrong_textchild = '第'+mode_datavalue+'个受益人信息错误(如下)：';
			if($('#text_select_beneficiary_benefitRelation_0'+mode_datavalue).data('relationship_id')){
				childjsons['beneficiary_benefitRelation'] = $('#text_select_beneficiary_benefitRelation_0'+mode_datavalue).data('relationship_id'); 
			}else{
				flag_ben = false;
				wrong_textchild += '<br>与被保险人关系:性别没有选择';
			}
			
			if(check_applicant_name('beneficiary_name_0'+mode_datavalue,'syr','app')){
				childjsons['beneficiary_name'] = $('#beneficiary_name_0'+mode_datavalue).val(); 
			}else{
				flag_ben = false;
				wrong_textchild += '<br>姓名:受益人姓名格式不正确';
			}
			 
			if($('#text_beneficiary_certificates_type_0'+mode_datavalue).data('beneficiary_certificates_type_id')){
				childjsons['beneficiary_certificates_type'] = $('#text_beneficiary_certificates_type_0'+mode_datavalue).data('beneficiary_certificates_type_id'); 
			}else{
				flag_ben = false;
				wrong_textchild += '<br>证件类型:证件类型没有选择';
			}
			
			
			if(check_applicant_card('beneficiary_certificates_code_0'+mode_datavalue,'beneficiary_certificates_type_0'+mode_datavalue,'app')){
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
			
			if($('#beneficiary_cardExpDate_0'+mode_datavalue).val()!=""){
				childjsons['beneficiary_cardExpDate'] = $('#beneficiary_cardExpDate_0'+mode_datavalue).val(); 
			}else{
				flag_ben = false;
				wrong_textchild += '<br>证件有效期:止期不能为空';
			}
			 
			if($('#text_select_shouyiren_sex_0'+mode_datavalue).data('sex_id')){
				childjsons['beneficiary_sex'] = $('#text_select_shouyiren_sex_0'+mode_datavalue).data('sex_id');
			}else{
				flag_ben = false;
				wrong_textchild += '<br>性别:不能为空';
			}
			
			if($('#beneficiary_birthday_0'+mode_datavalue).val()){
				childjsons['beneficiary_birthday'] = $('#beneficiary_birthday_0'+mode_datavalue).val(); 
			}else{
				flag_ben = false;
				wrong_textchild += '<br>出生日期:不能为空或者格式不正确(如：2015-01-08)';
			}
			 
			if(check_numberAFloat('beneficiary_benefitScale_0'+mode_datavalue,'app')){
				
				str_Bai = accAdd(str_Bai,$('#beneficiary_benefitScale_0'+mode_datavalue).val());
				 
				if(i== (strlength-1) && parseFloat(str_Bai)!=100){
					flag_ben = false;	
					wrong_textchild = '<br>受益比例:受益相加不等于100';
				}else{
					childjsons['beneficiary_benefitScale'] = accDiv($('#beneficiary_benefitScale_0'+mode_datavalue).val(),100); 
				}
			}else{
				flag_ben = false;
				wrong_textchild += '<br>受益比例:只能是数字和小数点,并且小于100';
			}
			
			if(flag_ben==false){
				wrong_text += wrong_textchild;	
				return false;
			}
		 	parentjsons.push(childjsons);
				
        });
		 
	}else{
		$('#beneficiary_info_value').val('');
		dom_idTemp = 'zhiding_beneficiary_list';
		wrong_text = '没有受益人，请增加受益人！';
		flag_ben = false; 
	}
	
	if(flag_ben){
		$('#beneficiary_info_value').val(JSON.stringify(parentjsons));
		
	}
	
	return {'flag':flag_ben,'wrong_text':wrong_text,'domid':dom_idTemp};
	
}


//银行续费条款显示和隐藏
function show_xqlist(ef){
	var data_valTemp = $(ef).data("value");	
	if(data_valTemp==1){
		$(ef).data("value",0);
		$(ef).text("关闭详情");
		$('#xiangqing_list').show();
	}else{
		$(ef).data("value",1);
		$(ef).text("查看详情");
		$('#xiangqing_list').hide();
	}
} 
 

