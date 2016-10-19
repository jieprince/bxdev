/*（手机端）中国人寿学平险*/
 
$(document).ready( function(){
	  var app_useridTemp = $.cookie("app_userid");
	  var app_platformIdTemp = $.cookie("app_platformId");
	  if(app_useridTemp!="" && app_platformIdTemp!="" ){ 
	  	$('#head_top').hide();
		$('#userid_app').val(app_useridTemp);
		$('#platformId').val(app_platformIdTemp); 
	  }
	  if($('#step').val()==1){
	  	initSet();
	  }
	   
});


//初始化
function initSet(){
	var currdate = serverDate();
	$("#startDate").val(currdate+" 00:00:00");
	var end_t = timeStamp2String(currdate,$('#period').val()); 
	$("#endDate").val(end_t); 
	provice_city_Ajax('text_select_city_P','city_by_province',2);
}

//获取服务器时间并且加上间隔时间
function serverDate(){
	var start_day = $("#start_day").val();
	if(start_day==0){
		start_day = 10;
	}
	var date = $("#server_time").val();
	date = parseInt(date);
	var bombay = date + (3600 * 24)*start_day;
	var time_s = new Date(bombay*1000);
	var cur_month = (time_s.getMonth()+1)>9?(time_s.getMonth()+1):'0'+(time_s.getMonth()+1);
	var cur_day = (time_s.getDate())>9?(time_s.getDate()):'0'+(time_s.getDate());
	var dateText_temp = time_s.getFullYear()+"-"+cur_month+"-"+cur_day;  
	return dateText_temp;
}


//获取服务器时间不加间隔时间
function serverNowDate(){
	 
	var date = $("#server_time").val();
	date = parseInt(date);
	 
	var time_s = new Date(date*1000);
	 
	return time_s;
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
	var applicant_type_str = $('#text_select_applicant_type').data('applicant_type_id');
	 
	if(applicant_type_str==1){
		$('#applicant_geren').html(geren_applicant_type_html);
		$('#applicant_jigou').hide();
		$('#applicant_jigou').html('');
		$('#applicant_geren').show();
		
	}else if(applicant_type_str==2){
		$('#applicant_jigou').html(jigou_applicant_type_html);
		$('#applicant_geren').hide();	
		$('#applicant_geren').html('');	
		$('#applicant_jigou').show();
		
	}
	
}

 

//投保填写页面验证
function submit_checked(){
	 var flag_submit = true;
	 /**
	 if(!$('#startDate').val()){
				 
		showMessages('保险起期不能为空','startDate'); 
		flag_submit = false;
		return false;
	 }else if(!$('#endDate').val()){
		 showMessages('保险止期不能为空','startDate'); 
		 flag_submit = false;
		 return false;
	 }
	 **/
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
			  
			  if(!checked_datavalueAll('text_select_toubaoren_sex','sex_id').flag){
				  showMessages('投保人性别不能为空!','text_select_toubaoren_sex');
				  flag_submit = false;
				  return false;
			  }
			  $('#applicant_sex').val($('#text_select_toubaoren_sex').data('sex_id'));  
			  
		 }else if(applicant_type_id==2){
			 //机构
			 if(!check_applicant_null('applicant_fullname','app')){
				  showMessages('机构名称不能为空!','applicant_fullname');
				  flag_submit = false;
				  return false;	 
			 }
			 
			 if(!checktelephone('telephone','app')){
				 showMessages('机构固定电话不能为空<br>格式：010-12345678!','applicant_fullname');
				 flag_submit = false;
				 return false;	 
			 } 
		 }
		  
		 
		 if(!check_applicant_card('applicant_certificates_code','applicant_certificates_type','app')){
			 showMessages('投保人证件号码为空或者证件号码无效!','applicant_certificates_code');
			 flag_submit = false;
			 return false; 
		 }else if(!check_applicant_mobilephone('applicant_mobilephone','app')){
			 showMessages('投保人手机号码不正确或者无效!','applicant_mobilephone');
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
		  }else if(!check_applicant_email('applicant_email','app')){
			 showMessages('投保人Email为空或者格式不正确!','applicant_email');
			 flag_submit = false;
			 return false; 
		 }
		 
		 if(!$('#text_select_relationshipWithInsured').data('relationshipWithInsured_id')){
			 showMessages('与投保人关系没有选择!','text_select_relationshipWithInsured_P');
			 flag_submit = false;
			 return false; 
		 }
		 $('#relationshipWithInsured').val($('#text_select_relationshipWithInsured').data('relationshipWithInsured_id'));
		 
		 //被保险人验证
		  
		  if(!check_applicant_name('assured_fullname','bbxr','app')){
			  showMessages('被保险人姓名3-24个字符!','assured_fullname');
			  flag_submit = false;
			  return false;
		  }
		  
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
		 }
		 var bbxr_bir = getAgeByBirthday($.trim($('#assured_birthday').val()));
		 var min_ageTemp = parseInt($('#age_min').val());
		 var max_ageTemp = parseInt($('#age_max').val());
		  
		 if(bbxr_bir<min_ageTemp || bbxr_bir>max_ageTemp){
			 showMessages('被保险人年龄不在投保范围之内!','assured_birthday');
			 flag_submit = false;
			 return false; 
		 }
		 if(!$('#text_select_city').data('city_id')){
			 showMessages('被保险人所在城市没有选择!','text_select_city_P');
			 flag_submit = false;
			 return false; 
		 }
		 $('#city_name').val($('#text_select_city').text());
		 if(!$('#text_select_county').data('county_id')){
			 showMessages('被保险人所在地区没有选择!','text_select_county_P');
			 flag_submit = false;
			 return false; 
		 }
		 $('#county_name').val($('#text_select_county').text()); 
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
function showSelectcity(domid,domPid,val_str){
	var str_data = $('#'+domPid).data('value');
	 
	showSelect(true,domid,val_str+'_name',str_data,showSelectcity_callback);
	
}

function showSelectcity_callback(){
	provice_city_Ajax('text_select_county_P','county_by_city',2);	
}

//获取城市列
function provice_city_Ajax(domid,val_type,type){
	var action_val = 'get_'+val_type;
	var datajson = {'op':'get_address_info','action':action_val};
	var tempname = 'province';
	if(val_type=='city_by_province'){
		datajson['province_code']=220000;
		tempname = 'city';
	}else if(val_type=='county_by_city'){
		tempname = 'county';
		datajson['city_code']=$('#text_select_city').data('city_id');
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
				 //html_pro_city += '<option value="'+n[tempname+'_code']+'">'+n[tempname+'_name']+'</option>';
				 var test_json = {};
				 test_json[tempname+'_name'] = n[tempname+'_name'];
				 test_json[tempname+'_id'] = n[tempname+'_code'];
				 /* 取消吉林部分地区的限制
				 if(val_type=='county_by_city'){
					 if(n.county_code!=220112 &&n.county_code!=220122 &&n.county_code!=220181 && n.county_code!=220182  &&n.county_code!=220183){
						 html_pro_city.push(test_json);
					 }
				 	
				 }else{
					 html_pro_city.push(test_json);
				 }
				 */
				 html_pro_city.push(test_json);
			});
			$('#'+domid).data('value',html_pro_city);
			 
		 }
	});	 
}

 

