/*新华少儿*/
var server_time = '';
$().ready(function(){
	setApp_Cookie();
	 
	server_time = parseInt($('#server_time').val())*1000; 
	$('#birthdate').val('');
	var s_date_old = DateAdd('y',-17,new Date(server_time)); 
	var s_date_old_2D = DateAdd('d',2,s_date_old); 
	var s_date_now = DateAdd('d',-30,new Date(server_time)); 
	var opt = {};
	opt.date = {preset : 'date'};
	opt.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
		lang:'zh',
		startYear:s_date_old_2D.getFullYear(), //开始年份
		startMonth:(s_date_old_2D.getMonth()),
		startDay:s_date_old_2D.getDate(),
		endYear:s_date_now.getFullYear(), //结束年份
		endMonth:(s_date_now.getMonth()),
		endDay:s_date_now.getDate(),
		onSelect: function(dateText, inst) { 
		    
			var start_date_temps = time2string(DateAdd('d',1,new Date(server_time)),'-'); 
			$('#startdateText').text(start_date_temps);  
			var old_date = setENDtime(server_time,dateText);
			$('#enddateText').text(old_date);  
			$('#birthdate_text').show();
			$('#startDate').val(start_date_temps+' 00:00:00');
			$('#endDate').val(old_date+' 23:59:59'); 
			setDis_N_Y(time2string(DateAdd('d',-1,new Date(dateText.replace(/-/g,"/"))),'-'));
			setPrices(); 
			 
	   } 
	};

	$("#birthdate").val('').scroller('destroy').scroller($.extend(opt['date'], opt['default']));
	$('#period_list_select_P_temp').data('value',$('#period_list_select_P').data('value'));
	
	$('#submit_btn').click(function (){
	 	return setPrices(1);
	});
	
});

 

 

function showSelect_factor_list(domid,domPid,type){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(typeof str_data!='object'){
		str_data = $.parseJSON(str_data);
	}
	if(type){
		initflag = false;
	}
	showSelect(initflag,domid,'factor_name',str_data,showSelect_factor_dates_callback);
}

function showSelect_factor_dates_callback(){
	setPrices();
}
 




function setDis_N_Y(data_str){
	
	var age_temp = getAgeByBirthday(data_str);
	$.ajax({
			url: '../oop/business/get_NCI_Data.php' ,
			type: 'POST',
			async:false,
			data: {'op':'check_period','product_id':$('#product_idTemp').val(),'age_factor_code':age_temp} ,
			dataType: "json",
			success: function(data){
				var tempperiod_list = [];
				var tempperiod_list_2 = $('#period_list_select_P_temp').data('value');
				if(typeof tempperiod_list_2 != 'object'){
					tempperiod_list_2 = $.parseJSON(tempperiod_list_2);
				}
				$(tempperiod_list_2).each(function(i, n) {
					 var s_i = 0; 
					 var s_ntext = n.factor_id.split(':')[1];
                     $(data).each(function(ii, nn) {
                       	 if(s_ntext == nn){
							tempperiod_list.push(n);
						 } 
                     });
					  
					 
                });
				$('#period_list_select_P').data('value',JSON.stringify(tempperiod_list));
				$('#period_list_select').text('');
				$('#period_list_select').data('factor_id','');
				$('#period_list_select').data('factor_name','');
				//$('#fitem_value_period a[data-data_v="4:'++'"]').hide();
			}
			
		
		});	 
	
}

//计算保费价格
function setPrices(type){
	var flag_Y = 0;
	var datajson = {'op':'get_duty_list','attribute_id':$('#attribute_id').val()};
	var birthdate_str = $('#birthdate').val();
	
	if(birthdate_str){
		
		datajson['age_factor_code'] = getAgeByBirthday(time2string(DateAdd('d',-1,new Date(birthdate_str.replace(/-/g,"/"))),'-'));
		flag_Y++;
	}
	
	 
	$('#list_value .active').each(function(i, n) {
        var data_vTemp = $(this).data('factor_id');
		 
		if(data_vTemp){
			data_vJSON = data_vTemp.split(':');
			if(data_vJSON[0]==1){ //性别
				$('#gender').val(data_vJSON[1]);
				datajson['gender_factor_code']= data_vJSON[1];
				flag_Y++;
			}
			if(data_vJSON[0]==3){//保险金额
				$('#applyNum').val(data_vJSON[1]);
				//datajson['applyNum']= data_vJSON[1];
				flag_Y++;	
			}
			if(data_vJSON[0]==4){//缴费期间
				$('#payPeriod').val(data_vJSON[1]);
				if(data_vJSON[1]==0){
					$('#payPeriodUnit').val(1);
					$('#p_waylist').text('一次性付清');
					$('#p_waylist').data('factor_id','5:0');
				}else if(data_vJSON[1]==18){
					$('#payPeriodUnit').val(3);
					$('#p_waylist').text('年交');
					$('#p_waylist').data('factor_id','5:12');
				}else{
					$('#payPeriodUnit').val(2);
					$('#p_waylist').text('年交');
					$('#p_waylist').data('factor_id','5:12');
				}
				
				datajson['period_factor_code'] = data_vJSON[1];
				flag_Y++;	
			}
			if(data_vJSON[0]==5){//缴费方式
				//datajson['p_way']= data_vJSON[1]; 
				if(datajson['period_factor_code']!=0 && data_vJSON[1]==12){
					flag_Y++;	
				}else if(datajson['period_factor_code']==0 && data_vJSON[1]==0){
					flag_Y++;	
				}
					
			}
		}
    });
	
	if(flag_Y==5){
		
		 $.ajax({
			url: '../oop/business/get_NCI_Data.php' ,
			type: 'POST',
			async:false,
			data: datajson ,
			dataType: "json",
			success: function(data){
				var price_temps = 0;
				var json_duty_price_ids = [];
				$(data).each(function(i, n) {
                    $(n.product_duty_list).each(function(i_1, n_1) {
                    	$(n_1.duty_price_list).each(function(i_2, n_2) {
                    		price_temps = accAdd(price_temps,n_2.premium);
							json_duty_price_ids.push(n_2.product_duty_price_id);
							
                		});
                	});
                });
				
				var pre_price = accMul($('#applyNum').val(),price_temps);
				var sum_prices = pre_price;
				if(datajson['period_factor_code']!=0){
					if(datajson['age_factor_code']==18){
						sum_prices = accMul((18-datajson['age_factor_code']),pre_price);	
					}else{
						sum_prices = accMul(datajson['period_factor_code'],pre_price);	
					}
					
				}
 
				$('#duty_price_ids').val(json_duty_price_ids.join(','));
				
				var price_html = '(共交费'+sum_prices+'元)';
				
				if(datajson['period_factor_code']==0){
					$('#price').val(sum_prices);
					$('#productPrem_price').html(sum_prices);
					price_html = '(共交费'+sum_prices+'元)';
				}else{
					$('#price').val(pre_price);	
					$('#productPrem_price').html(pre_price);
				}
				$('#price_sum_temp_content').html(price_html);
			}
			
		
		});	
	}else{
		$('#price').val(0);
		$('#duty_price_ids').val('');
		$('#productPrem_price').html('0');	
		$('#price_sum_temp_content').html('');
		if(type){
			
			layer.open({
				title:'温馨提示',
				content: '选项没有选择完全,请选择完全点击投保!',
				btn: ['确认']
			});	
			return false;
		}
	}
	
}

//计算保险结束时间
function setENDtime(now_time,sel_val){
	var sel_valTemp = sel_val.split('-');
	var now_timeTemp = new Date(parseInt(now_time)+24*60*60*1000);
	
	var now_timeTemp02 = new Date(parseInt(now_time));
	var sel_valTemp_year = parseInt(sel_valTemp[0]);
	var sel_valTemp_month = parseInt(sel_valTemp[1]);
	var sel_valTemp_date = parseInt(sel_valTemp[2]); 
	var now_timeTemp_year = parseInt(now_timeTemp.getFullYear());
	var now_timeTemp_month = parseInt(now_timeTemp.getMonth()+1);
	var now_timeTemp_date = parseInt(now_timeTemp.getDate());
	var now_timeTemp_month02 = parseInt(now_timeTemp02.getMonth()+1);
	var now_timeTemp_date02 = parseInt(now_timeTemp02.getDate());
	var end_yearTemp = sel_valTemp_year;
	if(sel_valTemp_month>now_timeTemp_month){
		end_yearTemp+=26;
	}else if(sel_valTemp_month==now_timeTemp_month){
		if(sel_valTemp_date>now_timeTemp_date){
			end_yearTemp+=26;	
		}else{
			end_yearTemp+=25;	
		}
		
	}else{
		end_yearTemp+=25;	
	}
	 
	if(now_timeTemp_month==2 && now_timeTemp_date>=28){
		var str_st = end_yearTemp%4;
		if(str_st!=0){
			now_timeTemp_date = 28;
		}
	}
	if(now_timeTemp_month02<10){
	 	now_timeTemp_month02 = '0'+now_timeTemp_month02;
	}
	if(now_timeTemp_date02<10){
		now_timeTemp_date02 = '0'+now_timeTemp_date02;
	}
	
	return end_yearTemp+'-'+now_timeTemp_month02+'-'+now_timeTemp_date02;
}

// 日期处理
function DateAdd(interval,number,date) { 
/* 
  *---------------   DateAdd(interval,number,date)   ----------------- 
  *   DateAdd(interval,number,date)   
  *   功能:实现VBScript的DateAdd功能. 
  *   参数:interval,字符串表达式，表示要添加的时间间隔. 
  *   参数:number,数值表达式，表示要添加的时间间隔的个数. 
  *   参数:date,时间对象. 
  *   返回:新的时间对象. 
  *   var   now   =   new   Date(); 
  *   var   newDate   =   DateAdd( "d ",5,now); 
  *    
  *    
  *---------------   DateAdd(interval,number,date)   ----------------- 
  */     
        switch(interval){ 
                case "y" : { 
                        date.setFullYear(date.getFullYear()+number); 
                        return date; 
                        break; 
                } 
                case "q" : { 
                        date.setMonth(date.getMonth()+number*3); 
                        return date; 
                        break; 
                } 
                case "M" : { 
                        date.setMonth(date.getMonth()+number); 
                        return date; 
                        break; 
                } 
                case "w" : { 
                        date.setDate(date.getDate()+number*7); 
                        return date; 
                        break; 
                } 
                case "d" : { 
                        date.setDate(date.getDate()+number); 
                        return date; 
                        break; 
                } 
                case "h" : { 
                        date.setHours(date.getHours()+number); 
                        return date; 
                        break; 
                } 
                case "m" : { 
                        date.setMinutes(date.getMinutes()+number); 
                        return date; 
                        break; 
                } 
                case "s" : { 
                        date.setSeconds(date.getSeconds()+number); 
                        return date; 
                        break; 
                } 
                default : { 
                        date.setDate(d.getDate()+number); 
                        return   date; 
                        break; 
                } 
        } 
		
}

//时间转化成字符串
function time2string(date_times,str_val){
	var str_Month = (date_times.getMonth()+1)<10?'0'+(date_times.getMonth()+1):(date_times.getMonth()+1);
	var str_Date = (date_times.getDate())<10?'0'+(date_times.getDate()):(date_times.getDate());
	 
	var date_str = date_times.getFullYear()+str_val+str_Month+str_val+str_Date;
	return date_str;
}

//根据生日计算年龄
function getAgeByBirthday(bir){
	if($.trim(bir)==""){
		return "";
	}else if(/^(\d{4})(-)(\d{1,2})(-)(\d{1,2})$/.test(bir)){
		var birthday= new Date(bir.replace(/-/g, "\/")); 
		var d=new Date(); 
		var age = d.getFullYear()-birthday.getFullYear()-((d.getMonth()<birthday.getMonth()|| (d.getMonth()==birthday.getMonth() && d.getDate()<birthday.getDate()))?1:0);
		 
		if(typeof(age)=="undefined"){
			//alert("getAgeByBirthday，变量无效");
			age = -1;
		}
		else if(isNaN(age)){
			//alert("getAgeByBirthday，年龄是非数字");
			age = -1;	
		}
		else{
			//alert("getAgeByBirthday: "+age);
		}
	}else{
		age = -1;
	}
	return age;
}


//两个时间的比较
function date_Diff_day(date1,date2){
	var qssj = date1.split('-');  
    var jssj = date2.split('-');
	if(qssj[1].substring(0,1)==0){
		qssj[1] = qssj[1].substring(1,2);
	}
	if(jssj[1].substring(0,1)==0){
		jssj[1] = jssj[1].substring(1,2);
	}
	var d1 = new Date(qssj[0], qssj[1], qssj[2]);
    var d2 = new Date(jssj[0], jssj[1], jssj[2]);
	 
	if(d1>d2){
		return 0;
	} else{
		return 1;
	}
}





