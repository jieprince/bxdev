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
 	  
	//加载投保日期
	var currdate = serverDate();
	var currdateNow = serverNowDate();
	  
	opt.date = {preset : 'date'};
	opt.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
		lang:'zh',
		startYear:currdateNow.getFullYear(),//开始年份
		startMonth:(currdateNow.getMonth()),
		startDay:currdateNow.getDate(),
		endYear:currdateNow.getFullYear()+20, //结束年份
		endMonth:(currdateNow.getMonth()),
		endDay:currdateNow.getDate(),
		onSelect: function(dateText, inst) { 
		    
	   } 
	};
	
	opt2.date = {preset : 'date'};
	opt2.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
		lang:'zh',
		startYear:currdate.getFullYear(), //开始年份
		startMonth:(currdate.getMonth()),
		startDay:currdate.getDate(),
		endYear:currdate.getFullYear()+20, //结束年份
		endMonth:(currdate.getMonth()),
		endDay:currdate.getDate(),
		onSelect: function(dateText, inst) { 
		    $("#startDate").val(dateText+" 00:00:00"); 
			var t = 0;
			if($('#product_sum_insured').val()==0){
				var project_start_temp = dateText.split('-') ;
				 
				opt3['default']['startYear'] = project_start_temp[0];
				opt3['default']['startMonth'] = (parseInt(project_start_temp[1])-1);
				opt3['default']['startDay'] = (parseInt(project_start_temp[2])+1);
				opt3['default']['endYear'] = (parseInt(project_start_temp[0])+20);
				opt3['default']['endMonth'] = (parseInt(project_start_temp[1])-1);
				opt3['default']['endDay'] = parseInt(project_start_temp[2]); 
				t = timeStamp2String(dateText,2,'month');	
				$("#endDate").val(t).scroller('destroy').scroller($.extend(opt3['date'], opt3['default'])); 
				
			}else{
				t = timeStamp2String(dateText,1,'year');	
				$("#endDate").val(t);
			}
			
			 
	   } 
	};
	
	
	opt3.date = {preset : 'date'};
	opt3.default = {
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
		    $("#endDate").val(dateText+' 23:59:59');
	   } 
	}; 
	
	$("#startDate").val('').scroller('destroy').scroller($.extend(opt2['date'], opt2['default']));
	if($('#product_sum_insured').val()==0){
		$("#sailDate").val('').scroller('destroy').scroller($.extend(opt['date'], opt['default']));
	}
}



 





//获取服务器时间并且加上间隔时间
function serverDate(){
	var start_day = $("#start_day").val();
	if(start_day==0){
		start_day = 1;
	}
	var date = $("#server_time").val();
	date = parseInt(date);
	var bombay = date + (3600 * 24)*1;
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

//遍历保额
function showSelect_amount(domid,domPid,layertype){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(layertype){ //判定 存在 不弹出选择框
		initflag = false;
	}
	showSelect(initflag,domid,'amount_name',str_data,showSelect_amountcallback);
}
function showSelect_amountcallback(){
	var pre_price_bf = $('#text_select_amount').data('amount_id');
	$('#amount').val(pre_price_bf);
	$('#timeAmount').val(pre_price_bf);
	if(pre_price_bf==50000 || pre_price_bf==100000){
		$('#eachAccidentFranchise').val('人民币3000元或损失的10%，以高者为准');
		$('#eachAccidentFranchise_text').text('人民币3000元或损失的10%，以高者为准');
	}else if(pre_price_bf==150000 || pre_price_bf==200000){
		$('#eachAccidentFranchise').val('人民币4000元或损失的15%，以高者为准');
		$('#eachAccidentFranchise_text').text('人民币4000元或损失的15%，以高者为准');
	}
	setPrices();
}

 

//计算价格
function setPrices(){
	var amount_T = $('#amount').val();	
	var rate_T = $('#rate').val();	
	 
	var pr_zong = 0;
	if(!isNaN(amount_T) && !isNaN(rate_T)){
		 
		pr_zong = accMul(amount_T,rate_T);
		pr_zong = parseFloat(pr_zong).toFixed(2);
		 
	}else{
		pr_zong = 0;
	} 
	//计算价格
	
	$('#premium').val(pr_zong);
	$('#test_price_Temp').text(pr_zong);
	$('#totalModalPremium').val(pr_zong);
 
}
 

//投保填写页面验证
function submit_checked(){
	 var flag_submit = true;
	 if(!$('#startDate').val()){
				 
		showMessages('保险起期不能为空','startDate'); 
		flag_submit = false;
		return false;
	 }else if(!$('#endDate').val()){
		 showMessages('保险止期不能为空','startDate'); 
		 flag_submit = false;
		 return false;
	 }else if(!checked_str_length('assured_fullname',100)){		 
		showMessages('被保险人名称100字符之内','assured_fullname'); 
		flag_submit = false;
		return false;
	 }else if(!checked_str_length('assured_certificate',200)){		 
		showMessages('被保险人身份证号码(组织机构代码)200字符之内','assured_certificate'); 
		flag_submit = false;
		return false;
	 }if(!checkzipcode('assured_zipcode','app')){
		 showMessages('被保险人邮政编码有误!','assured_zipcode');
		 flag_submit = false;
		 return false;
	 }else if(!checked_str_length('assured_address',200)){		 
		showMessages('被保险人联系地址200字符之内','assured_address'); 
		flag_submit = false;
		return false;
	 }else if(!checked_str_length('applicant_fullname',100)){		 
		showMessages('联系人200字符之内','applicant_fullname'); 
		flag_submit = false;
		return false;
	 }else if(!check_applicant_mobilephone('applicant_mobiletelephone','app')){
		 showMessages('投保人手机号码不正确或者无效!','applicant_mobiletelephone');
		 flag_submit = false;
		 return false; 
	 }else if(!check_applicant_email('applicant_email','app')){
		 showMessages('投保人Email为空或者格式不正确!','applicant_email');
		 flag_submit = false;
		 return false; 
	 }else if(!checked_str_length('roadLicenseNumber',200)){		 
		showMessages('道路运输许可证号码200字符之内','roadLicenseNumber'); 
		flag_submit = false;
		return false;
	 }else if(!checked_str_length('carNumber',50)){		 
		showMessages('运输车辆号码50字符之内','carNumber'); 
		flag_submit = false;
		return false;
	 }else if(!checked_str_length('goodsName',26)){		 
		showMessages('运输货物名称12汉字之内','goodsName'); 
		flag_submit = false;
		return false;
	 }else if(!checked_str_length('vehicleOwners',200)){		 
		showMessages('行驶证车主200字符之内','vehicleOwners'); 
		flag_submit = false;
		return false;
	 }else if(!isCheckedNull('transportVehiclesApprovedTotal')){		 
		showMessages('运输车辆核定载质量只能为数字','transportVehiclesApprovedTotal'); 
		flag_submit = false;
		return false;
	 }else if(!checked_str_length('frameNumber',200)){		 
		showMessages('运输车辆车架号/识别代码200字符之内','frameNumber'); 
		flag_submit = false;
		return false;
	 }else if(!checked_str_length('engineNo',200)){		 
		showMessages('发动机号码200字符之内','engineNo'); 
		flag_submit = false;
		return false;
	 }
	 if($('#product_sum_insured').val()==0){
		if(!checked_str_length('deliverorderNo',200)){		 
			showMessages('运单号200字符之内','deliverorderNo'); 
			flag_submit = false;
			return false;
		 }else if(!$('#sailDate').val()){		 
			showMessages('起运日期没有选择','sailDate'); 
			flag_submit = false;
			return false;
		 }else if(!checked_str_length('startPlace',50)){		 
			showMessages('起运地50字符之内','startPlace'); 
			flag_submit = false;
			return false;
		 }else if(!checked_str_length('endPlace',50)){		 
			showMessages('目的地50字符之内','endPlace'); 
			flag_submit = false;
			return false;
		 }
		 if(!$('#text_select_amount').data('amount_id') || $('#text_select_amount').data('amount_id')==""){
			 
			showMessages('累计责任限额没有选择！','text_select_amount_P'); 
			flag_submit = false;
			return false;
		}
		$('#amount').val($('#text_select_amount').data('amount_id')); 
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