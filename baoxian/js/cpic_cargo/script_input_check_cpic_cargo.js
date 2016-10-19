var $j = jQuery.noConflict();
$(document).ready(function () {

	 
    //与被保险人关系
    $("select#relationshipWithInsured").change(function () {
        var val_this = $(this).val();
        if(val_this==1){
			$("#div_assured").hide();
		}else{
			$("#div_assured").show();
		}
    });
 
    $("select#relationshipWithInsured").val("6");
	 $("#businessType").val("2");
	$("#div_assured").show();
	
	getitemcode('big_itemcode','small');
 	getmain_clause();
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
     
	$('#startDate').val(time+' 00:00:00'); 
	
    ///////////////////////////////////////////////////
    
    var pickerOpts1 = {
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: 0,
            onSelect: function (dateText, inst) {
				 $('#effectdate').val(dateText);
            }
        };
		
		 
	 
    $j("#saildate").datepicker(pickerOpts1);
     
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
	if (!checked_str_length('group_name',100)){
		layer.alert("投保人名称有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('group_name').focus();", 0)  ; 
		return false;
	}else if (!checked_str_length('assured_fullname',100)){
		layer.alert("被保险人名称有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('assured_fullname').focus();", 0)  ; 
		return false;
	}else if(!checked_str_length('mark',500)){
		layer.alert("标记/发票号码/运单号有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('mark').focus();", 0)  ; 
		return false;
	}else if(!checked_str_length('item',500)){
		layer.alert("货物名称有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('item').focus();", 0)  ; 
		return false;
	}else if(!checked_str_length('quantity',500)){
		layer.alert("包装及数量有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('quantity').focus();", 0)  ; 
		return false;
	}else if(!checked_str_length('kindname',30)){
		layer.alert("运输工具有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('kindname').focus();", 0)  ; 
		return false;
	}else if(!checked_str_length('voyno',30)){
		layer.alert("航次/车牌号有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('voyno').focus();", 0)  ; 
		return false;
	}else if(!checked_str_length('startport',80)){
		layer.alert("运输路线开始地有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('startport').focus();", 0)  ; 
		return false;
	}else if(!checked_str_length('endport',80)){
		layer.alert("运输路线目的地有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('endport').focus();", 0)  ; 
		return false;	
	
	}else if(!$('#saildate').val()){
		layer.alert("起运日期不能为空！",9,'温馨提示');
		window.setTimeout("document.getElementById('saildate').focus();", 0)  ; 
		return false;
	}else if($('#mainitemcode').val()=="" || $('#mainitemcode').val()==null ){
		layer.alert("主要条款没有选择！",9,'温馨提示');
		window.setTimeout("document.getElementById('mainitemcode').focus();", 0)  ; 
		return false;
	}else if(!isCheckedNull('amount')){
		if($('#product_attribute_code').val()=='ZH'){
			if($('#amount').val()>1000000){
				layer.alert("保险金额小于等于1000000元！",9,'温馨提示');
				window.setTimeout("document.getElementById('amount').focus();", 0)  ; 
				return false;	
			}	
		}else{
			layer.alert("保险金额只能为数字！",9,'温馨提示');
			window.setTimeout("document.getElementById('amount').focus();", 0)  ; 
			return false;	
		}
			
	}else if(!isCheckedNull('rate')){
		layer.alert("费率有误！",9,'温馨提示');
		window.setTimeout("document.getElementById('rate').focus();", 0)  ; 
		return false;	
	}else if(!$('#premium').val() || $('#premium').val()<=0){
		layer.alert("保费必须大于0！",9,'温馨提示');
		window.setTimeout("document.getElementById('premium').focus();", 0)  ; 
		return false;	
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

 
function getmain_clause(){
	 
    $.ajax({
        type : "post",
        url : "../oop/business/get_CPIC_CARGO_Data.php",
        data : {'op':'get_cargo_internal_main_clause'},
        success : function(html){
            var html_option = '<option value="">请选择条款</option>'; 
			  
			if($('#product_attribute_code').val()=='ZH'){
				var tempitemcontent = '';
				$($.parseJSON(html)).each(function(i, n){
					if($('#product_attribute_code').val()==n.code){
						html_option ='<option value="'+n.code+'" data-value="'+n.content+'">'+n.name+'</option>';
						tempitemcontent = n.content;
					}
				});
				
				$('#itemcontent').val(tempitemcontent);
				if($('#product_attribute_code').val()=='C090026M'){
					$('#c90_gonglu').hide();
				}else{
					$('#c90_gonglu').hide();
				}
				
			
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

function change_mainitemcode(){
	var value_data = $('#mainitemcode option:selected').data('value');
	if(value_data){
		$('#itemcontent').val(value_data);
	}
	if($('#mainitemcode').val()=='C090026M'){
		$('#c90_gonglu').show();	
	}else{
		$('#c90_gonglu').hide();	
	}
}

function premium_change(){
	var amount_T = $('#amount').val();	
	var rate_T = $('#rate').val();	
	var pr_zong = 0;
	if(!isNaN(amount_T) && !isNaN(rate_T)){
		 
		pr_zong = accMul(amount_T,rate_T);
		pr_zong = parseFloat(pr_zong).toFixed(2);
		 
	}else{
		pr_zong = 0;
	}
	$('#premium').val(pr_zong);
	$('#totalModalPremium').val(pr_zong);
	$('#showprice').text(pr_zong);	
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
			if(domid=='amount'){
				if($('#product_attribute_code').val()=='ZH'){
					if(domid_value<=1000000){
						$('#'+domid+'_warn').attr("class",'right_strong');
						$('#'+domid+'_warn').html('');
						return true;
					}else{
						$('#'+domid+'_warn').attr("class",'wrong_strong');
						$('#'+domid+'_warn').html('保险金额小于等于1000000');
						
						return false; 
					}
				}else{
					$('#'+domid+'_warn').attr("class",'right_strong');
					$('#'+domid+'_warn').html('');
					return true; 
				}
			}else{
				$('#'+domid+'_warn').attr("class",'right_strong');
				$('#'+domid+'_warn').html('');
				
				return true; 
			}
			
		}else{
			$('#'+domid+'_warn').attr("class",'wrong_strong');
		    $('#'+domid+'_warn').html('填写内容必须大于0！');
			 
		    return false;
		}
		
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