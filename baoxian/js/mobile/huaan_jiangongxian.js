/*（手机端）华安商场商铺室内装修建工一切险投保确认页面*/
 
var jigou_applicant_type_html = $('#applicant_jigou').html();
var geren_applicant_type_html = $('#applicant_geren').html();
$(document).ready( function() {
	  var app_useridTemp = $.cookie("app_userid");
	  var app_platformIdTemp = $.cookie("app_platformId");
	  if(app_useridTemp!="" && app_platformIdTemp!="" ){ 
	  	$('#head_top').hide();
		$('#userid_app').val(app_useridTemp);
		$('#platformId').val(app_platformIdTemp); 
	  }
	  if($('#step').val()==1){
		  initSet();
		  jigou_applicant_type_html = $('#applicant_jigou').html();
		  geren_applicant_type_html = $('#applicant_geren').html();
		  $('#applicant_jigou').html('');
		  provice_city_Ajax('province'); //省份加载
	  }
   
  
});


//初始化
function initSet(){
	var duty_beizhu = $('#xiane_price').data('amount').split(',');
	var duty_beizhu_html = '&nbsp;&nbsp;每次事故赔偿限额：'+duty_beizhu[1]+'万元<br>&nbsp;&nbsp;每次事故每人赔偿限额：'+duty_beizhu[2]+'万元<br>&nbsp;&nbsp;累计赔偿限额：'+duty_beizhu[0]+'万元';
	$('#beizhu_huaan').html(duty_beizhu_html);
	/* 
	 * 日期插件
	 * 滑动选取日期（年，月，日）
	 * V1.2@liuhui
	 *options操作项
	 *Ycallback确认返回方法
	 *Ncallback取消返回方法
	 *nowdate_type true false (true 现在时间=使用服务器时间+设置的天数 
	 *Checkedtype true false  (true=执行callback方法
	 *Checkedcallback callback方法
	 */ 
	 //(options,Ycallback,Ncallback,nowdate_type,Checkedtype,Checkedcallback)  
	
	var currdate = serverDate();	
	var opt={},opt2={},opt3={},opt4={};
	opt4.date = {preset : 'date'}; 
	opt4.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
		lang:'zh',
		startYear:currdate.getFullYear(), //开始年份
		startMonth:(currdate.getMonth()+1),
		startDay:currdate.getDate(),
		endYear:2016, //结束年份
		endMonth:3,
		endDay:22,
		onSelect: function(dateText, inst) { 
			 $("#project_end_date").val(dateText+" 23:59:59");		
	   } 
	};
	
	
	opt3.date = {preset : 'date'}; 
	opt3.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
		lang:'zh',
		startYear:currdate.getFullYear(), //开始年份
		startMonth:(currdate.getMonth()+1),
		startDay:currdate.getDate(),
		endYear:2016, //结束年份
		endMonth:3,
		endDay:22,
		onSelect: function(dateText, inst) { 
			var str_start_Y = $("#startDate").val().substring(0,10);
			var str_end_Y = $("#endDate").val().substring(0,10);			
			var start_f_g = date_Diff_day(str_start_Y,dateText);
			var end_f_g = date_Diff_day(dateText,str_end_Y); 
			if(start_f_g==1 && end_f_g==1){
			 	$("#project_start_date").val(dateText+" 00:00:00");
				$("#project_end_date").val('');
				var project_start_temp = dateText.split('-') ;
				var project_end_temp = ($("#endDate").val().substring(0,10)).split('-');
				opt4['default']['startYear'] = project_start_temp[0];
				opt4['default']['startMonth'] = (parseInt(project_start_temp[1])-1);
				opt4['default']['startDay'] = parseInt(project_start_temp[2]);
				opt4['default']['endYear'] = project_end_temp[0];
				opt4['default']['endMonth'] = (parseInt(project_end_temp[1])-1);
				opt4['default']['endDay'] = parseInt(project_end_temp[2]);
				$("#project_end_date").val('').scroller('destroy').scroller($.extend(opt4['date'], opt4['default']));  
			}else{
				$("#project_start_date").val('');
				$("#project_end_date").val(''); 
				layer.open({
					title:'温馨提示',
					content: '施工起期不在保险起止期范围之内！',
					btn: ['确定']
				});
				 
				
			}
	   } 
	};
	
	
	opt2.date = {preset : 'date'}; 
	opt2.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
		lang:'zh',
		startYear:currdate.getFullYear(), //开始年份
		startMonth:(currdate.getMonth()+1),
		startDay:currdate.getDate(),
		endYear:2016, //结束年份
		endMonth:3,
		endDay:22,
		onSelect: function(dateText, inst) { 
			
			$("#endDate").val(dateText+" 23:59:59");
			$("#project_start_date").val('');
			$("#project_end_date").val('');
			var start_baoxian = ($('#startDate').val()).substring(0,10);
			var start_baoxian_temp = start_baoxian.split('-') ;
			var end_baoxian_temp = (timeStamp2String(start_baoxian,29,'day').substring(0,10)).split('-');
			opt3['default']['startYear'] = start_baoxian_temp[0];
			opt3['default']['startMonth'] = (parseInt(start_baoxian_temp[1])-1);
			opt3['default']['startDay'] = parseInt(start_baoxian_temp[2]);
			opt3['default']['endYear'] = end_baoxian_temp[0];
			opt3['default']['endMonth'] = (parseInt(end_baoxian_temp[1])-1);
			opt3['default']['endDay'] = parseInt(end_baoxian_temp[2]);
			$("#project_start_date").val('').scroller('destroy').scroller($.extend(opt3['date'], opt3['default']));  
			 
	   } 
	};
	
	
	opt.date = {preset : 'date'};
	opt.default = {
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
			$("#endDate").val('');
			$("#project_start_date").val('');
			$("#project_end_date").val('');	
			//dateText
			var startdate_json = dateText.split('-');
			var end_t = timeStamp2String(dateText,parseInt($('#time_temp_pr').val())*30,'day'); 
			var end_t_josn = end_t.substring(0,10).split('-'); 
			opt2['default']['startYear'] = startdate_json[0];
			opt2['default']['startMonth'] = (parseInt(startdate_json[1])-1);
			opt2['default']['startDay'] = parseInt(startdate_json[2]);
			opt2['default']['endYear'] = end_t_josn[0];
			opt2['default']['endMonth'] = (parseInt(end_t_josn[1])-1);
			opt2['default']['endDay'] = parseInt(end_t_josn[2]);
			$("#endDate").val('').scroller('destroy').scroller($.extend(opt2['date'], opt2['default'])); 	
			 
	   } 
	};

	$("#startDate").val('').scroller('destroy').scroller($.extend(opt['date'], opt['default']));
	
}

//获取服务器时间并且加上间隔时间
function serverDate(){
	var start_day = $("#start_day").val();
	if(start_day==0)
	{
		start_day = 6;
	}
	var date = $("#server_time").val();
	date = parseInt(date);
	var bombay = date + (3600 * 24)*start_day;
	var time_s = new Date(bombay*1000);
	 
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

//显示工程方关系列表
function add_assured(ef){
	var s_value = $(ef).data('value');	
	$("#lists_"+s_value).show();
}


//组合工程方关系数据并且验证
function set_checked_assured(){
	var be_assured_fg = 0; //是否被保险人
	var json_temp = [];
	var flag_checked = true;
	var wrong_textJSON = [];
	$('#gongcheng_list').children('div').each(function(i,n) {
    	var dis_block = $(this).css("display"); 
		var dis_datavalue = $(this).data("value");
		var flag_temp_child = true;
		if(dis_block!='none'){
			var wrong_textTemp = '';
			var wrong_num = 0;
			//fullname_  certificates_type_   certificates_code_ assured_mobilephone_  be_assured
			var text_type = $('#lists_'+dis_datavalue+' .assured_type').text();
			var fullname_temp = $('#fullname_'+dis_datavalue).val();
			var certificates_type_temp = $('#certificates_type_'+dis_datavalue).data("applicant_certificates_type_id");
			var certificates_code_temp = $('#certificates_code_'+dis_datavalue).val();
			var assured_mobilephone_temp = $('#assured_mobilephone_'+dis_datavalue).val();
			var be_assured_temp = $('#be_assured_'+dis_datavalue).data("be_ased_id");
			
			if($.trim(fullname_temp)==""){
				flag_temp_child = false;
				wrong_num++;
				wrong_textTemp += '名称不能为空 , ';
			}else if(!checked_datavalueAll('certificates_type_'+dis_datavalue,'applicant_certificates_type_id').flag){
				flag_temp_child = false;
				wrong_num++;
				wrong_textTemp += '身份证类型没有选择 , ';
					
			}
			if($.trim(certificates_code_temp)!=""){
				if(certificates_type_temp=='120001'){
					if(isIdCardNo(certificates_code_temp)!=0){
						flag_temp_child = false;
						wrong_num++;
						wrong_textTemp += '证件号码无效 , ';
					}
				}else if(certificates_type_temp=='120011'){
					if(!isOrganizationCodeNo(certificates_code_temp)){
						flag_temp_child = false;
						wrong_num++;
						wrong_textTemp += '证件号码无效 , ';
					}
				}else{
					if($.trim(certificates_code_temp)==""){
						flag_temp_child = false;
						wrong_num++;
						wrong_textTemp += '证件号码为空 , ';
					}	
				}
				
				
			}else{
				flag_temp_child = false;
				wrong_num++;
				wrong_textTemp += '证件号码为空 , ';
			}
			
			
			if(isNull(assured_mobilephone_temp) || !isMobile(assured_mobilephone_temp)){
				flag_temp_child = false;
				wrong_num++;
				wrong_textTemp += '手机号为空或者无效 , ';
			}
			
			if(!checked_datavalueAll('be_assured_'+dis_datavalue,'be_ased_id').flag){
				flag_temp_child = false;
				wrong_num++;
				wrong_textTemp += '是否被保险人没有选择 , ';
					
			}else{
				if(be_assured_temp==1 && flag_temp_child==true){
					be_assured_fg ++;
				}	
				if(be_assured_fg==0){
					wrong_textTemp += '"是否被保险人"--最少要选择一个是被保险人 , ';
				}
			}
			
			//验证 something
			 
			//组合数据
			var wrong_textJSON_child = {'name':text_type,'wrong_text':wrong_textTemp};
			wrong_textJSON.push(wrong_textJSON_child); //错误信息
			if(flag_temp_child){
				json_temp.push({'assured_type':dis_datavalue,'fullname':fullname_temp,'certificates_type':certificates_type_temp,'certificates_code':certificates_code_temp,'assured_mobilephone':assured_mobilephone_temp,'be_assured':be_assured_temp});
			}
		}
    });
	if(be_assured_fg>=1 && json_temp!=[] && json_temp!=""){
		$('#assured_info').val(JSON.stringify(json_temp));
	}else{
		 
		flag_checked =false;	
	}
	var re_textlist = {'flag':flag_checked,'text_wrong':wrong_textJSON};
	return re_textlist;
	
}

//投保填写页面验证
function submit_checked(){
	 var flag_submit = true;
	 
	 if(!set_prodcutMarkPrice($('#q_prodcutMarkPrice').val())){
		var max_price_temps = parseInt($('#cost_limit').val().split(',')[1])/10*10000;
		var min_price_temps = parseInt($('#premium').val());		 
		showMessages('保费只能是整数并且在 '+min_price_temps+'元--'+max_price_temps+'元范围之内','q_prodcutMarkPrice'); 
		flag_submit = false;
		return false;
	 }
	 if(!$('#startDate').val()){
				 
		showMessages('保险起期不能为空','startDate'); 
		flag_submit = false;
		return false;
	 }else if(!$('#endDate').val()){
		 showMessages('保险止期不能为空','startDate'); 
		 flag_submit = false;
		 return false;
	 }
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
		  
		 if(!checked_datavalueAll('text_applicant_certificates_type','applicant_certificates_type_id').flag){
			 showMessages('投保人证件类型没有选择!','text_applicant_certificates_type');
			 flag_submit = false;
			 return false;
		 }
		 $('#applicant_certificates_type').val($('#text_applicant_certificates_type').data('applicant_certificates_type_id'));  	
		  
		 if(!check_applicant_card('applicant_certificates_code','applicant_certificates_type','app')){
			 showMessages('投保人证件号码为空或者证件号码无效!','applicant_certificates_code');
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
		 if(!checked_datavalueAll('text_nation_code','nation_code_id').flag){
			  showMessages('投保人国籍没有选择!','text_nation_code');
			  flag_submit = false;
			  return false;
		  }
		  $('#nation_code').val($('#text_nation_code').data('nation_code_id'));  
		  
		  var checked_assured_all = set_checked_assured();
		  if(!checked_assured_all.flag){
			  var text_wrong_liststr = '';
			  $.each(checked_assured_all.text_wrong,function(i,n){
				  text_wrong_liststr += n.name +' : '+n.wrong_text+'<br>';
			  });
			 showMessages(text_wrong_liststr,'gongcheng_list');
			 flag_submit = false;
			 return false;   
		  }
		  
		  if(!check_applicant_null('project_name','app')){
			  showMessages('工程名称不能为空!','project_name');
			  flag_submit = false;
			  return false;
		  }
		  
		  if(!checked_datavalueAll('text_project_type','project_type_id').flag){
			  showMessages('工程类型没有选择!','text_project_type');
			  flag_submit = false;
			  return false;
		  }
		  $('#project_type').val($('#text_project_type').data('project_type_id')); 
		  
		  if($('#project_price').val()!=""){
				 var temp_pricevalue = $('#project_price').val();
				 var zaojia_p  = $('#cost_limit').val();
				 var re = /^(([1-9][0-9]*\.[0-9][0-9]*)|([0]\.[0-9][0-9]*)|([1-9][0-9]*)|([0]{1}))$/;   
				if(zaojia_p!=""&&re.test(temp_pricevalue)){
					var zaojia_pTemp = zaojia_p.split(','); 
					if((parseInt(zaojia_pTemp[0])*10000)>parseFloat(temp_pricevalue)||(parseInt(zaojia_pTemp[1])*10000)<=parseFloat(temp_pricevalue)){ 
						showMessages('工程造价范围：'+zaojia_pTemp[0]+'万元<=工程价格<'+zaojia_pTemp[1]+'万元','project_price');
						flag_submit = false;
						return false;	
					}
				}else{
					showMessages('工程造价填写数字不正确!','project_price');
					flag_submit = false;
					return false;	
				}  
		  }else{
				 showMessages('工程造价没有填写!','project_price');
				 flag_submit = false;
				 return false; 
		  }
		  
		  if(!check_applicant_null('project_zipcode','app')){
			  showMessages('邮政编码不能为空!','project_zipcode');
			  flag_submit = false;
			  return false;
		  }
		  
		  if(!check_applicant_null('project_start_date','app')){
			  showMessages('施工起期不能为空!','project_start_date');
			  flag_submit = false;
			  return false;
		  }
		  
		  if(!check_applicant_null('project_end_date','app')){
			  showMessages('施工止期不能为空!','project_end_date');
			  flag_submit = false;
			  return false;
		  }
		  
		  if(!checked_datavalueAll('text_project_content','project_content_name').flag){
			  showMessages('施工内容没有选择!','text_project_content');
			  flag_submit = false;
			  return false;
		  }
		  $('#project_content').val($('#text_project_content').data('project_content_name'));  
		  if(!checked_datavalueAll('text_select_province','province_name').flag){
			  showMessages('施工省份没有选择!','text_select_province');
			  flag_submit = false;
			  return false;
		  }
		  $('#province_name').val($('#text_select_province').data('province_name'));  
		  
		  if(!checked_datavalueAll('text_select_city','city_name').flag){
			  showMessages('施工城市没有选择!','text_select_city');
			  flag_submit = false;
			  return false;
		  }
		  $('#city_name').val($('#text_select_city').data('city_name'));  
		  
		  if(!check_applicant_null('project_location','app')){
			  showMessages('施工地址不能为空!','project_location');
			  flag_submit = false;
			  return false;
		  }
		  if($('#text_beneficiary').data('beneficiary_id')==2 && !str_length_br('beneficiary_text',100)){
			  showMessages('指定受益人信息字数100字之内!','beneficiary_text');
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



//省份选择
function showSelectprovince(domid,domPid,layertype){
	var str_data = $.parseJSON($('#'+domPid).data('value'));
	var initflag = true;
	 
	showSelect(initflag,domid,'province_name',str_data,showSelectprovince_callback);
}

//省份选择回调函数
function showSelectprovince_callback(){
	provice_city_Ajax('city');
}
//省份城市ajax
function provice_city_Ajax(val_type){
	var action_val = 'get_'+val_type+'_list';
	var datajson = {'action':action_val,'type':'mobile'};
	if(val_type=='city'){
		datajson['province_name']=$.trim($('#text_select_province').text());
	}
	$.ajax({
		 type: 'POST',
		 url: '../oop/business/get_SINO_Data.php' ,
		 async:false,
		 data: datajson ,
		 dataType: "JSON",
		 success: function(data){
			var html_pro_city = [];
			
			$(data).each(function(i, n){
				if(val_type=='province'){
					html_pro_city.push({'province_name':n});
				}else if(val_type=='city'){
					html_pro_city.push({'city_name':n});
				} 
			});
			$('#text_select_'+val_type+'_P').data('value',JSON.stringify(html_pro_city));
		 }
	});	 
}



//受益人
function showSelect_base_beneficiary(domid,domPid,val_str,layertype){
	var str_data = $('#'+domPid).data('value');
	if(typeof(str_data)=="string"){
		str_data = $.parseJSON(str_data);
	}
	var initflag = true;
	if(layertype){ //判定 存在 不弹出选择框
		initflag = false;
	}
	showSelect(initflag,domid,val_str+'_name',str_data,callback_showSelect_base_beneficiary);
}

function callback_showSelect_base_beneficiary(){
	var temp_bfry = $('#text_beneficiary').data('beneficiary_id');	
	if(temp_bfry==1){
		$('#beneficiary_LI').hide();	
		$('#beneficiary_text').val('法定');
	}else if(temp_bfry==2){
		$('#beneficiary_text').val('');
		$('#beneficiary_LI').show();
	}
}



function set_prodcutMarkPrice(val_str){
	var int_val = parseInt(val_str);
	var max_price_temp = parseInt($('#cost_limit').val().split(',')[1])/10*10000;
	var min_price_temp = parseInt($('#premium').val());
	if(int_val!=val_str){ 
		return false;
	}else{ 
		if(min_price_temp<=int_val && int_val<=max_price_temp){
			$('#totalModalPremium_single').val(int_val);
			$('#totalModalPremium').val(int_val);
			return true;	
		}else{
			return false;
		}
	}
}


function str_length_br(domid,num){
	if($.trim($('#'+domid).val().length)>num || $.trim($('#'+domid).val().length)<1){
		return false;
	}else{
		return true;
	}
}




