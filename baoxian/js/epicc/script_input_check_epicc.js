var  $j = jQuery.noConflict();

$j(document).ready(function(){
	 
	 var periodtemp = $('#period').val();
	 var careertemp = $('#career').val();
	 $('#period_temp').html(periodtemp+'个月');
	 if(careertemp==1){
		$('#career_temp').html('1~3类');	 
	 }else if(careertemp==4){
		$('#career_temp').html('4类');	 
	 }
	 if($('#cp_name').val()=='epicc_lvyou' || $('#cp_name').val()=='epicc_jingwailvyou'){
		var period_minTemp = $('#period_min').val();
		var period_maxTemp = $('#period_max').val();
		var period_min_maxTemp = '';
		for(var i = period_minTemp; i<=period_maxTemp ;i++){
			period_min_maxTemp +='<option value="'+i+'">'+i+'天</option>';
		}
		$('#period_select').html(period_min_maxTemp);
	 }
	 
	 $j("input[name=applicant_type]").change(function () {

             var $selectedvalue = $j("input[name=applicant_type]:checked").val();

             //alert($selectedvalue);
             if ($selectedvalue == 1) //single
             {
                 $j("input#businessType").val(1);
				 $('#div_assured_title').hide();
				 $j("div#applicant_info_group").hide();
                 $j("div#applicant_info_single").show();
				 $('#as_other').show();
                 $('#more_upload_list').hide();
		 		 $("#as_temps").hide();
                 $j("select#relationshipWithInsured").val("5");
                 //$j("select#relationshipWithInsured").attr("readonly",false);
				 $('#commit_type_id').val(''); 
				 
             }
             else//group
             {
				 if($('#cp_name').val()=='epicc_jingwailvyou'){
					$('#commit_type_id').val('jingwai_tuandan');
					  
				 }else{
					$('#commit_type_id').val('tuandan');	
					   
				 }
                 $j("input#businessType").val(2);
				 $j("div#applicant_info_single").hide();
				 $('#div_assured_title').show();
                 $j("div#applicant_info_group").show();
                 $j("#as_temps").hide();
				 $('#as_other').show();
				 $('#more_upload_list_title').hide();
				 $('#more_upload_list').show();
                 $j("select#relationshipWithInsured").val("6");//其他
				 
                 //$j("select#relationshipWithInsured").attr("disable",true);
                 //$j("select#relationshipWithInsured").selectReadOnly();
                  
             }
         });
	 
	 //��ݱ����յĺ�Ͷ����֮ǰ�Ĺ�ϵ���õ����֤����
	$j("select#relationshipWithInsured").change(function(){
			 
			var val_this = $j(this).val();
			if(val_this==5){
				$('#div_assured_title').hide();
				 
				$j("#as_temps").hide();
			}else{
				$('#div_assured_title').show();
				
				$j("#as_temps").show();
			}
			
		
	 });
	
	  $j("select#applicant_certificates_type").val("1");
	  $j("select#assured_certificates_code").val("1");
	  $j("select#relationshipWithInsured").val("5");
	  
	 
	//���Ͷ������õ��ܷ���
	$j("select#applyNum").change(function(){
		 											
	     var applyNum = $j(this).val();
		 
		 var premium =  $j("input#premium").val();
		 
		 var totalpremium = accMul(premium,applyNum);
		 if($('input[name="assured_input_type"]:checked').val()==2){
			 var length_posion_table = $('#ps_list table tr').length-1;
			 var poidTemp = $('#bat_post_policy').val();
			 if(poidTemp!=2){ //平安 除Y502产品以外的批量计算
				 length_posion_table = parseInt(length_posion_table/2);
			 }
			 totalpremium = accMul(length_posion_table,totalpremium);
	     }
		 $j("span#showprice").html(totalpremium);
		  
		  $j("input#totalModalPremium").val(totalpremium);
		 
		 //alert(totalpremium);
																   
	 });	


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
						 var period = $j("#period").val();
						 if($('#cp_name').val()=='epicc_lvyou' || $('#cp_name').val()=='epicc_jingwailvyou'){
							period = $j("#period_select").val();	 
						 }
						var t = 0;
						 
						if($('#product_code').val()=='Y502'){
							 
							t = monthStamp2String(dateText, period);
						}else{
							t = timeStamp2String(dateText, period);	
						}
						//alert(t);
						var startdate_old = $j("#startDate").val();
						$j("#startDate").val(startdate_old+" 00:00:00"); 
						 
						if($('#cp_name').val()=='epicc_lvyou' || $('#cp_name').val()=='epicc_jingwailvyou' ){
							$j("#endDate").datepicker("option",'minDate',timeStamp2String(dateText, parseInt($('#period_min').val())).substring(0,10)); 
							$j("#endDate").datepicker("option",'maxDate',timeStamp2String(dateText, parseInt($('#period_max').val())).substring(0,10)); 
						
						}else{
							$j("#endDate").val(t.substring(0,10)+" 00:00:00"); 	
						}
				} 
        };
	var pickerOpts2 = {
         changeMonth: true,
         changeYear: true,
         dateFormat:'yy-mm-dd',
		 
		 onSelect: function(dateText, inst) { 
						 
			var endDate_old = $j("#endDate").val();
			$j("#endDate").val(endDate_old+" 23:59:59");
		} 
     };
        //��������ѡ����
     
	 $j("#startDate").datepicker(pickerOpts); 
	 if($('#cp_name').val()=='epicc_lvyou' || $('#cp_name').val()=='epicc_jingwailvyou'){
	 	$j("#endDate").datepicker(pickerOpts2); 
		if($('#cp_name').val()=='epicc_jingwailvyou'){
			cityajax(0);
			cityajax(1);
		}
	 }
	 /////////////////////////////////////////////////////
	 $j("a#btnNext").click(function(){
		  
		 var res = '';
		if($('#bat_post_policy').val()==1){//批量投保
			res = check_buy_submit_group();
		}else{  
			var str_applicant_type = $('#applicant_type_s_g input[name="applicant_type"]:checked').val(); 
			res = check_buy_submit();
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
	   $('#orderForm input:disabled').removeAttr("disabled");
	   $j("input#submit1").click();

	 });
	 
	 
 });// ready
 
 


function check_buy_submit(){
	var minage = $('#age_min').val();
	var maxage = $('#age_max').val();
	//购买信息检查
	if (!$j("input#startDate").val())
	{ 
		layer.alert("保险起期没填写！",9,'温馨提示');
		window.setTimeout("document.getElementById('startDate').focus();", 0)  ; 
		return false;
	}
	
	if (!$j("input#endDate").val())
	{
		layer.alert("保险止期没填写！",9,'温馨提示');
		window.setTimeout("document.getElementById('endDate').focus();", 0)  ; 
		return false;
	}
	 
	if($(' input[name="applicant_type"]:checked').val()==1){
		if (!check_cn_name('applicant_fullname',1))
		{
			 layer.alert("投保人姓名有误！",9,'温馨提示');
			 window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
			 return false;
		}
				
		else if (!$j("select#applicant_certificates_type").val())
		{
			 
			layer.alert("投保人证件类型没填写！",9,'温馨提示');
			window.setTimeout("document.getElementById('applicant_certificates_type').focus();", 0)  ; 
			return false;
		}
		else if (!check_All_card('applicant_certificates_type','applicant_certificates_code',1,true,'applicant_birthday','applicant_gender'))
		{
			 
			layer.alert("投保人证件号码有误！",9,'温馨提示');
			window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0)  ; 
			return false;
		}
		else if (!check_birthdays('applicant_birthday',1))
		{
			layer.alert("投保人生日有误！",9,'温馨提示');
			window.setTimeout("document.getElementById('applicant_birthday').focus();", 0)  ; 
			return false;
		}
		else if ($('#orderForm input[name="applicant_gender"]:checked').val()=="")
		{ 
			layer.alert("投保人性别没有选择！",9,'温馨提示');
			window.setTimeout("document.getElementById('applicant_birthday').focus();", 0)  ; 
			return false;
		}else if (!check_mobilephones('applicant_mobilephone')){
		 
			layer.alert("投保人手机号有误！",9,'温馨提示');
			window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0)  ; 
			return false;
		}else if (!check_emails('applicant_email')){
			layer.alert("投保人email有误！",9,'温馨提示');
			window.setTimeout("document.getElementById('applicant_email').focus();", 0)  ; 
			return false;
		}
		if($j("select#relationshipWithInsured").val()==5){//本人
			var birthday_applicant = document.getElementById("applicant_birthday").value;
	   document.getElementById("assured_birthday").value = birthday_applicant;
	   
	   if (!check_birthdays('assured_birthday',2)){
			 
			layer.alert("被保险人年龄不在投保范围内！",9,'温馨提示');
			window.setTimeout("document.getElementById('applicant_birthday').focus();", 0)  ; 
			return false;
		}
	    }else{
		   if (!check_cn_name('assured_fullname',2))
			{
			  
			   layer.alert("被保险人姓名有误！",9,'温馨提示');
				window.setTimeout("document.getElementById('assured_fullname').focus();", 0)  ; 
				return false;
			}
		
			else if (!$j("select#assured_certificates_type").val())
			{
				 
				layer.alert("被保险人证件证件类型没选择！",9,'温馨提示');
				window.setTimeout("document.getElementById('assured_certificates_type').focus();", 0)  ; 
				return false;
			}
			else if (!check_All_card('assured_certificates_type','assured_certificates_code',2,true,'assured_birthday','assured[0][assured_gender]',false))
			{
				  
				layer.alert("被保险人证件号码有误！",9,'温馨提示');
				window.setTimeout("document.getElementById('assured_certificates_code').focus();", 0)  ; 
				return false;
			}
			
			else if (!check_birthdays('assured_birthday',2))
			{ 
				layer.alert("被保险人年龄不在投保范围内！",9,'温馨提示');
				window.setTimeout("document.getElementById('assured_birthday').focus();", 0)  ; 
				return false;
			}
			else if ($('#orderForm input[name="assured_gender"]:checked').val()=="")
			{
				 
				layer.alert("被保险人生日没选择！",9,'温馨提示');
				window.setTimeout("document.getElementById('assured_gender_man').focus();", 0)  ; 
				return false;
			}
			else if (!check_mobilephones('assured_mobilephone'))
			{
				 
				layer.alert("被保险人手机号有误！",9,'温馨提示');
				window.setTimeout("document.getElementById('assured_mobilephone').focus();", 0)  ; 
				return false;
			}	
			else if (!check_emails('assured_email'))
			{
				layer.alert("被保险人email有误！",9,'温馨提示');
				window.setTimeout("document.getElementById('assured_email').focus();", 0)  ; 
				return false;
			}
	    }
		
		
	}else{
		if(!checkAll_Null('applicant_group_name')){
			layer.alert("投保机构名称不能为空！",9,'温馨提示');
			window.setTimeout("document.getElementById('applicant_group_name').focus();", 0)  ; 
			return false;
		}else if(!check_All_card('applicant_group_certificates_type','applicant_group_certificates_code',1,false,'','',false)){
			layer.alert("投保机构证件号码不能为空！",9,'温馨提示');
			window.setTimeout("document.getElementById('applicant_group_certificates_code').focus();", 0)  ; 
			return false;
		}else if (!check_mobilephones('applicant_group_mobilephone')){
		 
			layer.alert("投保人手机号有误！",9,'温馨提示');
			window.setTimeout("document.getElementById('applicant_group_mobilephone').focus();", 0)  ; 
			return false;
		}else if (!check_emails('applicant_group_email')){
			layer.alert("投保人email有误！",9,'温馨提示');
			window.setTimeout("document.getElementById('applicant_group_email').focus();", 0)  ; 
			return false;
		}else if(checkedAll()==false){  //验证上传文件
			 if($('input[name="applicant_type"]:checked').val() ==2 && $('#bat_post_policy').val()!=1){
				 layer.alert('验证失败可能原因如下：<br>1.没有上传excel文件<br/>2.上传人员数大于5小于3000<br>3.上传人员名单中证件类型、证件号码、生日、性别、电话、邮箱无效<br>4.投保人大于18岁,<br>5.被保人年龄不在投保范围之内('+minage+' 至 '+maxage+'岁)<br><br>请核实后再次提交!',9,'温馨提示');
			}else{
				 
				layer.alert('验证失败可能原因如下：<br>1.投保人类型、身份类型、身份证件号码无效,<br>2.投保人年龄不在投保范围之内(18 至65岁)<br>3.被保险人员数小于1人或者大于3000人<br>4.被保险人名单中重复的证件号码、手机号、邮箱<br>5.被保险人身份证件号码无效<br>6.被保险人年龄不在投保范围之内('+minage+' 至 '+maxage+'岁)<br>7.被保险人职业类别不能为空<br>请核实后再次提交!',9,'温馨提示');
			}
			window.setTimeout("document.getElementById('applicant_info_group').focus();", 0)  ; 
			return false;	
		}
	}
	if($('#citySelect').val()=="" && $('#cp_name').val()=='epicc_jingwailvyou'){
		layer.alert("出行目的地没有选择！",9,'温馨提示');
		window.setTimeout("document.getElementById('tab_lvyou_sq').focus();", 0)  ; 
		return false;
	}
	
	
	
  //  alert($j("select#relationshipWithInsured").val());
	//var age_min = $j("input#age_min").val()+"y";
	//var age_max = $j("input#age_max").val()+"y";
	 
	return  true;
}
  
  
 
 
 //检查批量投保，也可以允许个人投保
function check_buy_submit_group()
{
	var applicant_type = $("input[name=applicant_type]:checked").val();
	
	if (!$j("#startDate").val())
	{
	            alert("保险起期没填写！",9,'温馨提示');
	            window.setTimeout("document.getElementById('startDate').focus();", 0)  ; 
	            return false;
	}
	else if (!$j("#endDate").val())
	{
	            layer.alert("请填写保险止期！",9,'温馨提示');
	            window.setTimeout("document.getElementById('endDate').focus();", 0)  ; 
	            return false;
	}
	var minage = $('#age_min').val();
	var maxage = $('#age_max').val();
	if($('#bat_post_policy').val()==1){
		//最后的人员修改整体验证 

		if(checkedAll_New()==false)
		{  //验证上传文件
			 if(applicant_type ==2 && $('#bat_post_policy').val()!=1){
				 layer.alert('验证失败可能原因如下：<br>1.没有上传excel文件<br/>2.上传人员数大于5小于3000<br>3.上传人员名单中证件类型、证件号码无效<br>4.投保人大于18岁,<br>5.被保人年龄不在投保范围之内(3 至 18岁)<br><br>请核实后再次提交!',9,'温馨提示');
			}else{
				 
				layer.alert('验证失败可能原因如下：<br>1.投保人类型、身份类型、身份证件号码无效,<br>2.投保人年龄不在投保范围之内(18 至65岁)<br>3.被保险人员数小于1人或者大于3000人<br>4.被保险人名单中重复的证件号码、手机号、邮箱<br>5.被保险人身份证件号码无效<br>6.被保险人年龄不在投保范围之内(3至18岁)<br>7.被保险人职业类别不能为空<br>请核实后再次提交!',9,'温馨提示');
			}
			return false;	
		}
		else
		{
			return true;
		}
		
	} 
	 
		
	
	
}


function cityajax(str_num){
	var str_op = 'get_shengen_destination_list';
	if(str_num==1){
		str_op = 'get_destination_list';
	}
	$.ajax({
		type: 'POST',
		url: '../oop/business/get_EPICC_Data.php',
		data: {'op':str_op} ,
		dataType: 'JSON',
		async:false,
		success:function(data){
			 
			var st_htmls = '';
			$(data).each(function(i,n) {
				if(str_num==1){
					 
                	st_htmls += '<span><input type="checkbox" name="guojia20'+i+'" data-name="'+n.name+'" /> '+n.name+'</span>';
				}else{
					 
					st_htmls += '<span><input type="checkbox" name="guojia10'+i+'" data-name="'+n.name+'" /> '+n.name+'</span>';
				}
            });
			st_htmls += '<em style="clear:both"></em>';
			
			$('#tab_lvyou_sq .tabCon').children().eq(str_num).html(st_htmls);
		} 
		
	});	
	
}

function showcity(ef){
	
	var htmls = '<div id="tab_lvyou">'+$('#tab_lvyou_sq').html()+'</div><div><a href="javascript:void(0)" id="city_btn" class="btn_link001" style=" position:absolute; bottom:10px; right:150px;">确 定</a></div>';
	var isq = layer.tips(htmls, ef, {
		 
		isGuide: false,
		maxWidth:'350px',
		 
		closeBtn:[0, true],
		style:['background-color:#f5f5f5;color:#333','#f5f5f5']
		 
	});
	var json_inputtext = $('#citySelect').data("value"); 
	if(json_inputtext && json_inputtext!=""){
		var json_data_sel = json_inputtext.split(',');	
		$(json_data_sel).each(function(i,n) {
            $('#tab_lvyou input[name="'+n+'"]').attr("checked",true);
        });
	}
	$('#city_btn').unbind("click");
	$('#city_btn').bind("click",function(){
		var st_id_st = [];
		var jsons_city = [];
		$('#tab_lvyou input[type="checkbox"]:checked').each(function(index, element) {
            jsons_city.push($(this).data('name'));
			st_id_st.push($(this).attr("name"));
        });	
		if(jsons_city!=[]){
			$('#citySelect').val(jsons_city.join(','));
			$('#citySelect').data("value",st_id_st.join(','));
			layer.close(isq);
		}
	});
	//tabload();
}


function showef(str_num){
	$('#tab_lvyou .tabList .cur').removeClass('cur');
	$('#tab_lvyou .tabList ul').children().eq(str_num).addClass("cur");
	$('#tab_lvyou .tabCon .cur').hide();
	$('#tab_lvyou .tabCon .cur').removeClass('cur');
	$('#tab_lvyou .tabCon').children().eq(str_num).addClass("cur");
	$('#tab_lvyou .tabCon .cur').show();
	
}
 
function tabload(){
    var oDiv = document.getElementById("tab_lvyou");
    var oLi = oDiv.getElementsByTagName("div")[0].getElementsByTagName("li");
    var aCon = oDiv.getElementsByTagName("div")[1].getElementsByTagName("div");
    var timer = null;
    for (var i = 0; i < oLi.length; i++) {
        oLi[i].index = i;
        oLi[i].onclick = function() {
            show(this.index);
        }
    }
    function show(a) {
        index = a;
        var alpha = 0;
        for (var j = 0; j < oLi.length; j++) {
            oLi[j].className = "";
            aCon[j].className = "";
            aCon[j].style.opacity = 0;
            aCon[j].style.filter = "alpha(opacity=0)";
        }
		$('.tabCon .cur')
        oLi[index].className = "cur";
        clearInterval(timer);
        timer = setInterval(function() {
            alpha += 2;
            alpha > 100 && (alpha = 100);
            aCon[index].style.opacity = alpha / 100;
            aCon[index].style.filter = "alpha(opacity=" + alpha + ")";
            alpha == 100 && clearInterval(timer);
        },
        5)
    }
}
 


 
 