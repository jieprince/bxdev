/*（手机端）华安学平险*/
var temp_html = {}; 
var age_min_temp_date = 0;
var age_max_temp_date = 0;
var opt={},opt2={},opt3={};
$(document).ready( function(){
	  
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
 
	//加载投保份数
	if(!$('#activity_id').val()){
		var applyMum_sums_Temp = $('#applyMum_sums').val();
		if(applyMum_sums_Temp){
			var temp_json_sums = [];
			for(var iQs=1;iQs<=applyMum_sums_Temp;iQs++){
				temp_json_sums.push({"applynum_id":iQs,"applynum_name":iQs+"份"});	
			}	
			$('#text_select_applyNum_P').data('value',JSON.stringify(temp_json_sums));
		}
	}
	
	//加载投保日期
	var currdate = serverDate();
	var currdateNow = serverNowDate();
	 
	
	opt.date = {preset : 'datetime'};
	opt.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
		lang:'zh',
		startYear:currdateNow.getFullYear(), //开始年份
		startMonth:(currdateNow.getMonth()),
		startDay:currdateNow.getDate(),
		endYear:currdateNow.getFullYear()+20, //结束年份
		endMonth:(currdateNow.getMonth()),
		endDay:currdateNow.getDate(),
		onSelect: function(dateText, inst) { 
		    $('#startDate').val(dateText+':00'); 
			var end_t = timeStamp2String(dateText+':00',2,'day'); 
			$("#endDate").val(end_t);
	   } 
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
	
	$("#takeoffDate").val('').scroller('destroy').scroller($.extend(opt['date'], opt['default']));
	$("#applicant_birthday").val('').scroller('destroy').scroller($.extend(opt2['date'], opt2['default']));
	$("#assured_birthday").val('').scroller('destroy').scroller($.extend(opt3['date'], opt3['default']));
	 
}

//获取服务器时间并且加上间隔时间
function serverDate(){
	var start_day = $("#start_day").val();
	if(start_day==0){
		start_day = 5;
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
	$('#applicant_type').val(applicant_type_str);
	$('#beibaoxianren_list').show();
	if(applicant_type_str==1){
		$('#businessType').val(1);
		$('#applicant_geren').html(temp_html.gerencontent);
		$('#applicant_jigou').hide();
		$('#applicant_jigou').html('');
		$('#applicant_geren').show();
		$('#text_select_relationshipWithInsured').data('relationshipWithInsured_id','');
		$('#text_select_relationshipWithInsured').data('relationshipWithInsured_name','');
		$('#text_select_relationshipWithInsured').text('');
		$('#relationshipWithInsured').val('');
		
	}else if(applicant_type_str==2){
		$('#businessType').val(2);
		$('#applicant_jigou').html(temp_html.jigoucontent);
		$('#applicant_geren').hide();	
		$('#applicant_geren').html('');	
		$('#applicant_jigou').show();
		$('#text_select_relationshipWithInsured').data('relationshipWithInsured_id',6);
		$('#text_select_relationshipWithInsured').data('relationshipWithInsured_name','其他');
		$('#text_select_relationshipWithInsured').text('其他');
		$('#relationshipWithInsured').val(6);
		
	}
	
}


//身份类型   type =1 投保人  2 被保险人  3 受益人  4机构
function showSelect_certificates_type(domid,domPid,type,layertype){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(layertype){ //判定 存在 不弹出选择框
		initflag = false;
	}
	if(type==1){
		showSelect(initflag,domid,'applicant_certificates_type_name',str_data,showSelect_appcertificates_type_callback);	
	}else if(type==2){
		showSelect(initflag,domid,'assured_certificates_type_name',str_data,showSelect_asscertificates_type_callback);	
	}else if(type==4){
		showSelect(initflag,domid,'applicant_certificates_type_name',str_data);	
	}
}

//身份类型返回绑定事件
function showSelect_appcertificates_type_callback(){
	var strstcodetype = $('#text_applicant_certificates_type').data('applicant_certificates_type_id');
	 
	
	$('#applicant_certificates_code').val('');
	$('#text_select_toubaoren_sex').text('');
	$('#text_select_toubaoren_sex').data('sex_id','');
	$('#text_select_toubaoren_sex').data('sex_name','');
	$('#applicant_birthday').val('');
	if(strstcodetype==1){
		$('#applicant_certificates_code').unbind("blur");	
		$('#applicant_certificates_code').bind("blur",function(){
			checked_codebirsex_test('applicant_certificates_code','applicant_birthday','text_select_toubaoren_sex','applicant_sex','app',true)
		});	
		$('#text_select_toubaoren_sex_P').unbind("click");
		$("#applicant_birthday").val('').scroller('destroy');
	
	}else{
		$('#applicant_certificates_code').unbind("blur");
		$('#text_select_toubaoren_sex_P').unbind("blur");
		$('#text_select_toubaoren_sex_P').bind("click",function(){
			showSelect_base('text_select_toubaoren_sex','text_select_toubaoren_sex_P','sex')	
			
		});
		$("#applicant_birthday").val('').scroller('destroy').scroller($.extend(opt2['date'], opt2['default']));
	}
}

//身份类型返回绑定事件
function showSelect_asscertificates_type_callback(){
	var strstcodetype = $('#text_select_assured_certificates_type').data('assured_certificates_type_id');
	$('#assured_certificates_type').val(strstcodetype);
	$('#assured_certificates_code').val('');
	$('#text_select_assured_sex').text('');
	$('#text_select_assured_sex').data('sex_id','');
	$('#text_select_assured_sex').data('sex_name','');
	$('#assured_birthday').val('');
	 
	if(strstcodetype==1){
		$('#assured_certificates_code').unbind("blur");	
		$('#assured_certificates_code').bind("blur",function(){
			checked_codebirsex_test('assured_certificates_code','assured_birthday','text_select_assured_sex','assured_sex','app',true);
		});	
		$('#text_select_assured_sex_P').unbind("click");
		$("#assured_birthday").val('').scroller('destroy');
	}else{
		$('#assured_certificates_code').unbind("blur");
		$('#text_select_assured_sex_P').unbind("blur");
		$('#text_select_assured_sex_P').bind("click",function(){
			showSelect_base('text_select_assured_sex','text_select_assured_sex_P','sex');
		});
		$("#assured_birthday").val('').scroller('destroy').scroller($.extend(opt3['date'], opt3['default']));
	}
}

//与被保险人关系
function showSelect_relationshipWithInsured(domid,domPid,layertype){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(layertype){ //判定 存在 不弹出选择框
		initflag = false;
	}
	showSelect(initflag,domid,'relationshipWithInsured_name',str_data,showSelect_relationshipWithInsured_callback);
}
//与被保险人关系返回
function showSelect_relationshipWithInsured_callback(){
	var relationshipWithInsured_id = $('#text_select_relationshipWithInsured').data('relationshipWithInsured_id');
	$('#relationshipWithInsured').val(relationshipWithInsured_id);
 
	var text_select_applicant_type_temp = $('#text_select_applicant_type').data('applicant_type_id');
	if(text_select_applicant_type_temp==1){
		if(relationshipWithInsured_id==1){
			$('#beibaoxianren_list').hide();
		}else{
			$('#beibaoxianren_list').show();
		}
	}else if(text_select_applicant_type_temp==2){
		 
		if(relationshipWithInsured_id!=6){
			showMessages('"投保人类型"是机构的情况下"与被保险人关系"只能选择"其他"','text_select_relationshipWithInsured_P'); 
			$('#text_select_relationshipWithInsured').data('relationshipWithInsured_id',"6");
			$('#text_select_relationshipWithInsured').data('relationshipWithInsured_name',"其他");
			$('#text_select_relationshipWithInsured').text("其他");
		}
	}
}

//省份
function showSelect_province(domid,domPid,type,layertype){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(layertype){ //判定 存在 不弹出选择框
		initflag = false;
	}
	if(type==1){
		showSelect(initflag,domid,'province_name',str_data,showSelect_province_callback);
	}else if(type==2){
		showSelect(initflag,domid,'province_name',str_data,showSelect_province_ass_callback);
	}
	
	
}



//投保人省份回调函数
function showSelect_province_callback(){
	$('#text_select_insureareaprov_code').data('insureareaprov_id','');
	$('#text_select_insureareaprov_code').data('insureareaprov_name','');
	$('#text_select_insureareaprov_code').text('');
	$.ajax({
		 type: 'GET',
		 url: '../oop/business/queryAreaList.php' ,
		 async:false,
		 data: {'region_code':$('#text_select_province').data('province_id')} ,
		 dataType: "JSON",
		 success: function(data){
			var html_pro_city = [];
			var areaInfos=data.split(";");
			$(areaInfos).each(function(i, n){
				 var info=n.split(":");
				html_pro_city.push({'insureareaprov_id':info[0],'insureareaprov_name':info[1]});
			});
			$('#text_select_insureareaprov_code_P').data('value',JSON.stringify(html_pro_city));
		 }
	});	 
	
}


//被保险人省份回调函数
function showSelect_province_ass_callback(){
	$('#text_select_insureareaprov_ass_code').data('insureareaprov_id','');
	$('#text_select_insureareaprov_ass_code').data('insureareaprov_name','');
	$('#text_select_insureareaprov_ass_code').text('');
	$.ajax({
		 type: 'GET',
		 url: '../oop/business/queryAreaList.php' ,
		 async:false,
		 data: {'region_code':$('#text_select_province_ass').data('province_id')} ,
		 dataType: "JSON",
		 success: function(data){
			var html_pro_city = [];
			var areaInfos=data.split(";");
			$(areaInfos).each(function(i, n){
				 var info=n.split(":");
				html_pro_city.push({'insureareaprov_id':info[0],'insureareaprov_name':info[1]});
			});
			$('#text_select_insureareaprov_ass_code_P').data('value',JSON.stringify(html_pro_city));
		 }
	});	 
	
}

//计算价格
function setPrices(){
	var select_applyNum = $('#text_select_applyNum').data("applynum_id");	
	
	if(parseInt(select_applyNum)>0){
		//计算价格
		var premium_temp = $('#premium').val();
		$('#applyNum').val(select_applyNum);
		var sums_price_Z = accMul(premium_temp,select_applyNum);
		$('#test_price_Temp').text(sums_price_Z);
		$('#totalModalPremium').val(sums_price_Z);
	}
 
}
 

//投保填写页面验证
function submit_checked(){
	 var flag_submit = true;
	 /*
	 if(!$('#startDate').val()){
				 
		showMessages('保险起期不能为空','startDate'); 
		flag_submit = false;
		return false;
	 }else if(!$('#endDate').val()){
		 showMessages('保险止期不能为空','startDate'); 
		 flag_submit = false;
		 return false;
	 }
	 */
	  
	 
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
			 }
			 
			  
		 }else if(applicant_type_id==2){
			 if(!check_applicant_name('applicant_group_name','tbr','app')){
				 showMessages('机构名称不能为空!','applicant_group_name');
				 flag_submit = false;
				 return false; 
			 }
			 if($('#text_applicant_group_certificates_type').data('applicant_certificates_type_id')!=6){
				 showMessages('机构证件类型没有选择!','text_applicant_group_certificates_type');
				 flag_submit = false;
				 return false; 
			 }
			 $('#applicant_group_certificates_type').val($('#text_applicant_group_certificates_type').data('applicant_certificates_type_id')); 
			 if($.trim($('#applicant_group_certificates_code').val())==""){
				 showMessages('机构证件号码为空或者证件号码无效!','applicant_group_certificates_code');
				 flag_submit = false;
				 return false; 
			 }else if(!check_applicant_mobilephone('applicant_group_mobilephone','app')){
				 showMessages('机构手机号码不正确或者无效!','applicant_group_mobilephone');
				 flag_submit = false;
				 return false; 
			 }else if(!check_applicant_email('applicant_group_email','app')){
				 showMessages('机构Email为空或者格式不正确!','applicant_group_email');
				 flag_submit = false;
				 return false; 
			 }
		 }
		  
		 
		 
		 var text_select_relationshipWithInsured = $('#text_select_relationshipWithInsured').data('relationshipWithInsured_id');
		 if(!text_select_relationshipWithInsured){
			 showMessages('与投保人关系没有选择!','text_select_relationshipWithInsured_P');
			 flag_submit = false;
			 return false; 
		 }
		 $('#relationshipWithInsured').val(text_select_relationshipWithInsured);
				 //被保险人验证
		 if(text_select_relationshipWithInsured!='1'){
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
				  if(!checked_datavalueAll('text_select_assured_sex','sex_id').flag){
					  showMessages('被保险人性别不能为空!','text_select_assured_sex');
					  flag_submit = false;
					  return false;
				  }
				  $('#assured_sex').val($('#text_select_assured_sex').data('sex_id'));  
				 
				 if(!check_applicant_card('assured_certificates_code','assured_certificates_type','app')){
					 showMessages('被保险人证件号码为空或者证件号码无效!','assured_certificates_code');
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
				 }else if(!check_applicant_email('assured_email','app')){
					 showMessages('被保险人Email为空或者格式不正确!','assured_email');
					 flag_submit = false;
					 return false; 
				 }
				 
				 
				 var bbxr_bir = getAgeByBirthday($.trim($('#assured_birthday').val()));
				  
				 if(bbxr_bir<age_min_temp_date || bbxr_bir>age_max_temp_date){
					 showMessages('被保险人年龄不在投保范围之内!','assured_birthday');
					 flag_submit = false;
					 return false; 
				 }
				 
				 
		  }else{
			   
			  var bbxr_bir = getAgeByBirthday($.trim($('#applicant_birthday').val()));
				  
			  if(bbxr_bir<age_min_temp_date || bbxr_bir>age_max_temp_date){
				 showMessages('被保险人年龄不在投保范围之内!','applicant_birthday');
				 flag_submit = false;
				 return false; 
			  }
		  }
		  
		 if(!$('#flightNo').val() || $.trim($('#flightNo').val())==""){
			 showMessages('被保险人航班号不能为空!','flightNo');
			 flag_submit = false;
			 return false; 
			 
		 } 
		 if(!$('#takeoffDate').val() || $.trim($('#takeoffDate').val())==""){
			 showMessages('被保险人出发时间没有选择!','takeoffDate');
			 flag_submit = false;
			 return false; 
			 
		 }
		 if(!$('#flightFrom').val() || $.trim($('#flightFrom').val())==""){
			 showMessages('被保险人出发地没有填写!','flightFrom');
			 flag_submit = false;
			 return false; 
			 
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


function checked_codebirsex_test(code_str,br_str,str_sex,sex_dominput,type,showflag){
	var res_str = checked_codebirsex(code_str,br_str,str_sex,type,showflag);
	if(res_str){
		$('#'+sex_dominput).val($('#'+str_sex).data('sex_id'));
	}else{
		showMessages('身份证号码不正确!',code_str);	
		$('#'+sex_dominput).val('');
		$('#'+str_sex).text('');
		$('#'+br_str).val('');
	}
}
 
 
 

