var $j = jQuery.noConflict();
$(document).ready(function () {
 
	
	//getitemcode('big_itemcode','small');
 	//getmain_clause();
    //////////////////////////////////////////////////////////////////////
    //http://api.jqueryui.com/datepicker/
    $j.datepicker.regional["zh-CN"] = {closeText: "关闭", prevText: "&#x3c;上月", nextText: "下月&#x3e;", currentText: "今天", monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"], monthNamesShort: ["一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二"], dayNames: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"], dayNamesShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"], dayNamesMin: ["日", "一", "二", "三", "四", "五", "六"], weekHeader: "周", dateFormat: "yy-mm-dd", firstDay: 1, isRTL: !1, showMonthAfterYear: !0, yearSuffix: "年"};

    $j.datepicker.setDefaults($j.datepicker.regional["zh-CN"]);
    
    var start_day = $("input#start_day").val();
    
    //alert(start_day);
	if(start_day==0)
	{
		start_day = 1;
	}
    
    //start add by wangcya , 20141121,服务器时间
    //var date = new Date($.ajax({async: false}).getResponseHeader("Date"));
    var date = $("#server_time").val();
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
    
    //alert(start_day);
     
	 
    ///////////////////////////////////////////////////
    var pickerOpts = {
         changeMonth: true,
         changeYear: true,
         dateFormat:'yy-mm-dd',
		 minDate: time,//start_day
		 onSelect: function(dateText, inst) { 
						    var period = $j("input#period").val();
						    var t = 0;
							if($('#product_sum_insured').val()==0){
								$j("#endDate").datepicker("option",'minDate',timeStamp2String(dateText,2,'day').substring(0,10));
								 
								t = timeStamp2String(dateText,2,'month');	
								$j("#endDate").val(t);
							}else{
								t = timeStamp2String(dateText,1,'year');	
								$j("#endDate").val(t);
							}
							$j("#startDate").val(dateText+" 00:00:00");
		  } 
    };
    var pickerOpts1 = {
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: 0,
            onSelect: function (dateText, inst) {
				 $('#effectdate').val(dateText);
            }
        };
		var pickerOpts2 = {
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: 0,
            onSelect: function (dateText, inst) {
				 $('#endDate').val(dateText+' 23:59:59');
            }
        };
		
		 
	$j("#startDate").datepicker(pickerOpts); 
    $j("#saildate").datepicker(pickerOpts1);
    if($('#product_sum_insured').val()==0){
		$j("#endDate").datepicker(pickerOpts2);
	} 
    ////////////////////////////////////////////////////////
    $("a#btnNext").click(function () {
         
        var res = check_buy_submit();
		 
    	if(!res)
    	{
    		return false;
    	}
    	
		///////////////////////////////////////////////////
	 	var val=$('input:radio[name="radiobutton"]:checked').val();
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
        $("input#submit1").click();
    });
});

 

//提交验证
function check_buy_submit(){
	if(!$('#startDate').val()){
		layer.alert("保险起期没有填写！",9,'温馨提示');
		window.setTimeout("document.getElementById('startDate').focus();", 0)  ; 
		return false;	
	}else if(!$('#endDate').val()){
		layer.alert("保险止期没有填写！",9,'温馨提示');
		window.setTimeout("document.getElementById('endDate').focus();", 0)  ; 
		return false;	
	}else if (!checked_str_length('assured_fullname',100)){
		layer.alert("被保险人名称有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_fullname').focus();", 0)  ; 
		return false;
	}else if (!checked_str_length('assured_certificate',200)){
		layer.alert("被保险人身份证号码(组织机构代码)有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_certificate').focus();", 0)  ; 
		return false;
	}else if(!check_zipcode('assured_zipcode')){
		layer.alert("被保险人邮政编码有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_zipcode').focus();", 0)  ; 
		return false;
		
	}else if(!checked_str_length('assured_address',200)){
		layer.alert("被保险人联系地址有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_address').focus();", 0)  ; 
		return false;
		
	}else if(!checked_str_length('applicant_fullname',100)){
		layer.alert("联系人有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
		return false;
		
	}else if(!check_mobilephones('applicant_mobiletelephone')){
		layer.alert("有机号码有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_mobiletelephone').focus();", 0)  ; 
		return false;
		
	}else if(!check_emails('applicant_email')){
		layer.alert("Email有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('applicant_email').focus();", 0)  ; 
		return false;
		
	}else if(!checked_str_length('roadLicenseNumber',200)){
		layer.alert("道路运输许可证号码有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('roadLicenseNumber').focus();", 0)  ; 
		return false;
		
	}else if(!checked_str_length('carNumber',50)){
		layer.alert("运输车辆号码有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('carNumber').focus();", 0)  ; 
		return false;
		
	}else if(!checked_str_length('goodsName',200)){
		layer.alert("运输货物名称有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('goodsName').focus();", 0)  ; 
		return false;
		
	}else if(!checked_str_length('vehicleOwners',200)){
		layer.alert("行驶证车主有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('vehicleOwners').focus();", 0)  ; 
		return false;
		
	}else if(!isCheckedNull('transportVehiclesApprovedTotal')){
		layer.alert("运输车辆核定载质量有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('transportVehiclesApprovedTotal').focus();", 0)  ; 
		return false;
		
	}else if(!checked_str_length('frameNumber',200)){
		layer.alert("运输车辆车架号/识别代码有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('frameNumber').focus();", 0)  ; 
		return false;
		
	}else if(!checked_str_length('engineNo',200)){
		layer.alert("发动机号码有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('engineNo').focus();", 0)  ; 
		return false;
		
	}
	
	if($('#product_sum_insured').val()==0){
		if(!checked_str_length('deliverorderNo',200)){
			layer.alert("运单号有误！",9,'温馨提示');
			window.setTimeout("document.getElementById('deliverorderNo').focus();", 0)  ; 
			return false;	
		}else if(!$('#saildate').val()){
			layer.alert("起运日期不能为空！",9,'温馨提示');
			window.setTimeout("document.getElementById('saildate').focus();", 0)  ; 
			return false;	
		}else if(!checked_str_length('startPlace',100)){
			layer.alert("起运地不能为空！",9,'温馨提示');
			window.setTimeout("document.getElementById('startPlace').focus();", 0)  ; 
			return false;	
		}else if(!checked_str_length('endPlace',100)){
			layer.alert("目的地不能为空！",9,'温馨提示');
			window.setTimeout("document.getElementById('endPlace').focus();", 0)  ; 
			return false;	
		}else if(!isCheckedNull('amount')){
			layer.alert("保险金额有误！",9,'温馨提示');
			window.setTimeout("document.getElementById('amount').focus();", 0)  ; 
			return false;	
		}else if(!isCheckedNull('rate')){
			layer.alert("费率有误！",9,'温馨提示');
			window.setTimeout("document.getElementById('rate').focus();", 0)  ; 
			return false;	
		}else if(!$('#premium').val() || $('#premium').val()<=0){
			layer.alert("保费必须大于0！",9,'温馨提示');
			window.setTimeout("document.getElementById('premium').focus();", 0)  ; 
			return false;	
		}
	}
	
	
	 
	//if($('#mainitemcode').val()=='C090026M'){
		
	//}
	return true;
}

function changeName(val_checked){
	if(val_checked){
		$('#assured_fullname').val($('#group_name').val());
		$('#assured_fullname_warn').attr('class','right_strong');
		$('#assured_fullname_warn').html('');
	}else{
		$('#assured_fullname').val('');
		$('#assured_fullname_warn').attr('class','wrong_strong');
		$('#assured_fullname_warn').html('内容不能为空');
	}
}

//货物类型
function getitemcode(dom_id,str_num){
	var name_main = '-1';
    if(str_num!="small"){
		name_main = str_num;
	}
    $.ajax({
        type : "post",
        url : "../oop/business/get_CPIC_CARGO_Data.php",
        data : {'op':'get_cargo_type','name_main':name_main},
        success : function(html){
            var html_option = ''; 
			 
			$($.parseJSON(html)).each(function(i, n) {
				if(str_num=="small"){
					html_option+='<option value="'+n.name_one+'">'+n.name_one+'</option>';
				}else{
					html_option+='<option value="'+n.code+'">'+n.name_two+'</option>';
				}
            });
			
            $('#'+dom_id).html(html_option);
			if(str_num=="small"){
				getitemcode('itemcode',$('#big_itemcode').val());
			}
        },
        error : function(data){
        // alert("网络传输异常，无法获取地区信息");
        }
    });
}


//货物类型
function getmain_clause(){
	 
    $.ajax({
        type : "post",
        url : "../oop/business/get_CPIC_CARGO_Data.php",
        data : {'op':'get_cargo_internal_main_clause'},
        success : function(html){
            var html_option = '<option value="">请选择条款</option>'; 
			  
			if($('#product_attribute_code').val()=='C090026M'){
				html_option = '<option value="C090026M" data-value="中国太平洋财产保险股份有限公司国内公路运输综合保险条款">国内公路运输综合保险条款</option>';
				$('#itemcontent').val('中国太平洋财产保险股份有限公司国内公路运输综合保险条款');
				$('#c90_gonglu').show();
			}else{
				$($.parseJSON(html)).each(function(i, n){
					html_option+='<option value="'+n.code+'" data-value="'+n.content+'">'+n.name+'</option>';
				});
			}
            $('#mainitemcode').html(html_option);
			 
        },
        error : function(data){
        // alert("网络传输异常，无法获取地区信息");
        }
    });
}

 

function premium_change(){
	if($('#product_sum_insured').val()=="" || $('#product_sum_insured').val()==0){
		var amount_T = $('#amount').val();	
		$('#timeAmount').val(amount_T);
		var rate_T = $('#rate').val();	
		var pr_zong = 0;
		if(!isNaN(amount_T) && !isNaN(rate_T)){
			 
			pr_zong = accMul(amount_T,rate_T);
			pr_zong = parseFloat(pr_zong).toFixed(2);
			 
			if(amount_T==50000 || amount_T==100000){
				$('#eachAccidentFranchise_cs').html('<option value="人民币3000元或损失的10%，以高者为准" selected="selected">人民币3000元或损失的10%，以高者为准</option>');
			}else if(amount_T==150000 || amount_T==200000){
				$('#eachAccidentFranchise_cs').html('<option value="人民币4000元或损失的15%，以高者为准" selected="selected">人民币4000元或损失的15%，以高者为准</option>');
			}
			
		}else{
			pr_zong = 0;
		}
		$('#premium').val(pr_zong);
		$('#totalModalPremium').val(pr_zong);
		$('#showprice').text(pr_zong);	
	}
}



//验证填写内容不能大于length_num的字符数
function checked_str_length(domid,length_num){
	  var str = $('#'+domid).val();
	  var length_Temp = 0;
	  
	  if(domid=='startport' || domid=='transport1' || domid=='endport'){
		  domid = 'startport';
	  }
	  if (str == null || $.trim(str)=="") {
		length_Temp = 0;
		$('#'+domid+'_warn').attr("class",'wrong_strong');
	    $('#'+domid+'_warn').html('填写内容不能为空！');
	    return false;
	  }else{
		  if (typeof str != "string"){
			str += "";
		  }
		  length_Temp = str.replace(/[^x00-xff]/g,"01").length;
	  }
	  
	  if(length_num<=length_Temp){
		  $('#'+domid+'_warn').attr("class",'wrong_strong');
		  
		  $('#'+domid+'_warn').html('填写内容小于'+length_num+'字符！');
		  
		  return false;
	  }else{
		   $('#'+domid+'_warn').attr("class",'right_strong');
		   $('#'+domid+'_warn').html('');
		   if(domid=='group_name'){
		  		$('#title_fp').val(str);
	  	   }
		   return true; 
	  }
	  
}
//验证为空  只能是数字
function isCheckedNull(domid){
	var domid_value = $('#'+domid).val();
	premium_change();
	if($.trim(domid_value)=="" || $.trim(domid_value)==null || isNaN($.trim(domid_value))){
	    
	   $('#'+domid+'_warn').attr("class",'wrong_strong');
	   $('#'+domid+'_warn').html('填写内容只能是数字！');
	    
	   return false;
	}else{
		if($.trim(domid_value)>0){
			$('#'+domid+'_warn').attr("class",'right_strong');
			$('#'+domid+'_warn').html('');
			
			return true; 
		}else{
			$('#'+domid+'_warn').attr("class",'wrong_strong');
		    $('#'+domid+'_warn').html('填写内容必须大于0！');
			 
		    return false;
		}
		
	}
	
}

//检查手机号
function check_mobilephones(domid){
	var str_text = $.trim($('#'+domid).val());
    if(isNull(str_text)){
		$('#'+domid+'_warn').attr("class","wrong_strong");
		$('#'+domid+'_warn').html("请填写手机号码");
        return false;
    }
    else if(!isMobile(str_text,true)){
		$('#'+domid+'_warn').attr("class","wrong_strong");
		$('#'+domid+'_warn').html("请填写正确的手机号");
        return false;
    }
    else{
		$('#'+domid+'_warn').attr("class","right_strong");
		$('#'+domid+'_warn').html("");
        return true;
    }
    
}

//检查电子邮箱
function check_emails(domid){
	var str_text = $.trim($('#'+domid).val());
	if(str_text == ""){
		$('#'+domid+'_warn').attr("class","wrong_strong");
		$('#'+domid+'_warn').html("请填写电子邮箱");
		return false;
	}else if(!isEmail(str_text, true)) {
		$('#'+domid+'_warn').attr("class","wrong_strong");
		$('#'+domid+'_warn').html("请填写正确的电子邮箱"); 
		return false;
	}else{
		$('#'+domid+'_warn').attr("class","right_strong");
		$('#'+domid+'_warn').html("");
		return true;
	}
}


//邮政编码验证
function check_zipcode(domid){
	var zipcode=$.trim($('#'+domid).val());
	var re= /^[1-9][0-9]{5}$/
	if(re.test(zipcode)){
	  $('#'+domid+'_warn').attr("class","right_strong");
	  $('#'+domid+'_warn').html("");
	  return true;
	}else{
	  $('#'+domid+'_warn').attr("class","wrong_strong");
	  $('#'+domid+'_warn').html("邮政编码不正确"); 
	  return false;
	}	
}

//浮点数乘法
function accMul(arg1,arg2){
	var m=0,ms=0,mz=0,s1=arg1.toString(),s2=arg2.toString();
	try{m=s1.split(".")[1].length}catch(e){};
	try{ms=s2.split(".")[1].length}catch(e){};
	if(m>ms){
		mz = m;
	}else{
		mz = ms;
	}
	return parseFloat(parseInt(Number(s1.replace(".",""))*Number(s2.replace(".","")))/Math.pow(10,(m+ms))).toFixed(mz);
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