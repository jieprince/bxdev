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

	 //与被保人关系选择，选择本人隐藏，选择其余的关系都显示（注：投保人类型是机构选择的时候，本人不可能选择）
	$j("select#relationshipWithInsured").change(function(){

			var val_this = $j(this).val();
			if(val_this==1){
                var applicant_type_val = $('input[name="applicant_type"]:checked').val();
                if(applicant_type_val==1){
                    $j("#div_assured").hide();
                }else{
                    layer.alert('投保人是机构，不能够选择本人!');
                    $(this).val(6);
                }
			}else{
				$j("#div_assured").show();
			}
	 });
	
	  $j("select#applicant_certificates_type").val("01");
	  $j("select#assured_certificates_code").val("01");
	  $j("select#relationshipWithInsured").val("1");
	  
	
	//������֤�����ȡ���Ա������
	$j("input#applicant_certificates_code").change(function(){
			
			var UUserCard = $j(this).val(); 
			
			
			var type = $j("select#applicant_certificates_type").val();
			//alert(type);
			
			if(type !="01" || !UUserCard)
			{
				 return;
			}
			
			if (parseInt(UUserCard.substr(16, 1)) % 2 == 1) 
			{
				sex = "M";
			//��
			} 
			else
			{
				sex = "F";
			//Ů
			} 
			
			//sex = "F";
			//alert(sex);
			
			if(sex == "M")
			{
				$j("input#applicant_sex_man").attr("checked",true);
				//$j("input#applicant_sex_man").removeAttr("checked");
			}
			else
			{
				$j("input#applicant_sex_woman").attr("checked",true);
				//$j("input#applicant_sex_woman").removeAttr("checked");
			}
			
			
			var birthday = UUserCard.substring(6, 10) + "-" + UUserCard.substring(10, 12) + "-" + UUserCard.substring(12, 14); 
			
			//alert(birthday);
			$j("input#applicant_birthday").val(birthday);
 	});
	
	
	$j("input#assured_certificates_code").change(function(){
			
			var UUserCard = $j(this).val(); 
			
			var type = $j("select#assured_certificates_type").val();
			if(type !="01" || !UUserCard)
			{
				 return;
			}
			
			
			
			if (parseInt(UUserCard.substr(16, 1)) % 2 == 1) 
			{
				sex = "M";
			//��
			} 
			else
			{
				sex = "F";
			//Ů
			} 
			
			//sex = "F";
			//alert(sex);
			
			if(sex == "M")
			{
				$j("input#assured_sex_man").attr("checked",true);
				//$j("input#applicant_sex_man").removeAttr("checked");
			}
			else
			{
				$j("input#assured_sex_woman").attr("checked",true);
				//$j("input#applicant_sex_woman").removeAttr("checked");
			}
			
			
			var birthday = UUserCard.substring(6, 10) + "-" + UUserCard.substring(10, 12) + "-" + UUserCard.substring(12, 14); 
			
			//alert(birthday);
			$j("input#assured_birthday").val(birthday);
 	});
	
	
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
						 var period = $j("input#period").val();
						    var t = 0;
							 
						 	if($('#product_code').val()=='Y502'){
								 
				            	t = monthStamp2String(dateText, period);
							}else{
								t = timeStamp2String(dateText, period);	
							}
							//alert(t);
				            $j("#endDate").val(t);
				            
				            var startdate_old = $j("#startDate").val();
				            $j("#startDate").val(startdate_old+" 00:00:00");
											   } 
        };
	
        //��������ѡ����
     
	 $j("#startDate").datepicker(pickerOpts); 
	 provice_city_Ajax('applicant_province_code','province');
	  
	
	 ///////////////////////////////////////////////////////////////

	 $('#cs_checked').click(function(){
		
		var cs_check_flg = $(this).attr("checked");	 
		  
		if(cs_check_flg){
			$('#assured_fullname').val($('#applicant_fullname').val());
			$('#assured_certificates_type').val($('#applicant_certificates_type').val());
			$('#assured_certificates_code').val($('#applicant_certificates_code').val());
			$('#assured_birthday').val($('#applicant_birthday').val());
			var sex_temp_ap = $('#applicant_info_single input[name="applicant_sex"]:checked').val();
			$('#table_input_type_manual input[name="assured[0][assured_sex]"][value="'+sex_temp_ap+'"]').attr("checked",true);
			$('#assured_mobilephone').val($('#applicant_mobilephone').val());
		 
		}else{
			$('#assured_fullname').val('');
			$('#assured_certificates_type').val('01');
			$('#assured_certificates_code').val('');
			$('#assured_birthday').val('');
			 
			$('#table_input_type_manual input[name="assured[0][assured_sex]"][value="M"]').attr("checked",true);
			$('#assured_mobilephone').val('');
		}
	 });
	 
	 /////////////////////////////////////////////////////
	 $j("a#btnNext").click(function(){
		  
		 var res = '';
		 res = check_buy_submit_pinganYMain();
		
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
	   $j("input#submit1").click();

	 });
	 
	 /*
	 
	    $j("form#inputForm").submit(function() {
                alert("submit");
                  return true;
            });
       */
     	 
	 
 });// ready
 
 function check_buy_submit_pinganYMain(){
	 	 
		 	//购买信息检查
	        if (!$j("input#startDate").val())
	        {
	            alert("保险起期没填写！");
	            window.setTimeout("document.getElementById('startDate').focus();", 0)  ; 
	            return false;
	        }
			
	        if (!$j("input#endDate").val())
	        {
	            alert("保险止期没填写！");
	            window.setTimeout("document.getElementById('endDate').focus();", 0)  ; 
	            return false;
	        }
	        //投保人信息检查
	        /*
	        else if (!$j("input#applicant_fullname").val())
	        {
	            alert("投保人姓名没填写！");
	            window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
	            return false;
	        }
	        */
	        else if (!check_applicant_name())
	        {
	        	 window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
		         return false;
	        }
	        		
	        else if (!$j("select#applicant_certificates_type").val())
	        {
	            alert("投保人证件类型没填写！");
	            window.setTimeout("document.getElementById('applicant_certificates_type').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_card())
	        {
	            //alert("投保人证件号码没填写！");
	            window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_birthday())
	        {
	            //alert("投保人生日没填写！");
	            window.setTimeout("document.getElementById('applicant_birthday').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_sex())
	        {
	            //alert("投保人生日没填写！");
	            window.setTimeout("document.getElementById('applicant_sex').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_mobilephone())
	        {
	            //alert("投保人手机号没填写！");
	            window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0)  ; 
	            return false;
	        }
	        else if (!check_applicant_email())
	        {
	            //alert("投保人email没填写！");
	            window.setTimeout("document.getElementById('applicant_email').focus();", 0)  ; 
	            return false;
	        }
			$('#province_temp').val($('#applicant_province_code option:selected').text());
			$('#city_temp').val($('#applicant_city_code option:selected').text());
			$('#county_temp').val($('#applicant_county_code option:selected').text());
			 
				if (!check_assured_name())
		        {
		           // alert("被保险人姓名没填写！");
		            window.setTimeout("document.getElementById('assured_fullname').focus();", 0)  ; 
		            return false;
		        }
			
		        else if (!$j("select#assured_certificates_type").val())
		        {
		            //alert("被保险人证件证件类型没填写！");
		            window.setTimeout("document.getElementById('assured_certificates_type').focus();", 0)  ; 
		            return false;
		        }
		        else if (!check_assured_card())
		        {
		            //alert("被保险人证件号码没填写！");
		            window.setTimeout("document.getElementById('assured_certificates_code').focus();", 0)  ; 
		            return false;
		        }
				
		        else if (!check_assured_birthday('18y','80y'))
		        {
		            alert("被保险人年龄不在投保范围内！");
		            window.setTimeout("document.getElementById('assured_birthday').focus();", 0)  ; 
		            return false;
		        }
		        else if (!check_assured_sex())
		        {
		            //alert("被保险人生日没填写！");
		            window.setTimeout("document.getElementById('assured_sex').focus();", 0)  ; 
		            return false;
		        }
		        else if (!check_assured_mobile_phone())
		        {
		            //alert("被保险人生日没填写！");
		            window.setTimeout("document.getElementById('assured_mobilephone').focus();", 0)  ; 
		            return false;
		        }
			var type_str_temp = 0;
			if($('#cs_checked').attr("checked")){
				type_str_temp = 2;
			}else{
				type_str_temp = 0;
			}
			var fullname_Ts = $('#assured_fullname').val();
			var certificates_type_Ts = $('#assured_certificates_type').val();
			var certificates_type_Ts_str = $('#assured_certificates_type option:selected').text();
			var certificates_code_Ts = $('#assured_certificates_code').val();
			var birthday_Ts = $('#assured_birthday').val();
			var gender_Ts = $('#table_input_type_manual input[name="assured[0][assured_sex]"]:checked').val();
			var gender_Ts_str = gender_Ts=='M'?'男':'女';
			var mobiletelephone_Ts = $('#assured_mobilephone').val();
			var str_jsondata = [{'type':type_str_temp,'fullname':fullname_Ts,'certificates_type':certificates_type_Ts,'certificates_code':certificates_code_Ts,'birthday':birthday_Ts,'gender':gender_Ts,'mobiletelephone':mobiletelephone_Ts,'gender_str':gender_Ts_str,'certificates_type_Ts':certificates_type_Ts_str}];
			$('#data_userInfo').val(JSON.stringify(str_jsondata));
			//最后的人员修改整体验证 
			return true;
			
			//[{'fullname':'','relation':}]
		 
 }
 
 
 
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


function checkedset_M(){
	var str_M = $('#personal_protection').val(); 
	var str_M_price = 10;
	var s_01Temp = $('#s_01_01').attr("checked");
	var s_02Temp = $('#s_01_02').attr("checked");
	var s_03Temp = $('#s_01_03').attr("checked");
	if(s_01Temp){
		$('#s_02_01').attr("disabled",false);
	}else{
		$('#s_02_01').attr("disabled",true);
	}
	if(s_02Temp){
		$('#s_02_02').attr("disabled",false);
	}else{
		$('#s_02_02').attr("disabled",true);
	}
	if(s_03Temp){
		$('#s_02_03').attr("disabled",false);
	}else{
		$('#s_02_03').attr("disabled",true);
	} 
	if(str_M=='A'){
		if(s_01Temp==true && s_02Temp==true && s_03Temp==true){
			$('#s_03_00').text(4);
			$('#s_03_01').text(4);
			$('#s_03_02').text(1);
			$('#s_03_03').text(1);
			 
			$('#s_04_00').val(0.1);
			$('#s_04_01').val(0.1);
			$('#s_04_02').val(0.025);
			$('#s_04_03').val(0.025);
		}else if(s_01Temp==true && s_02Temp==false && s_03Temp==true){
			$('#s_03_00').text(4);
			$('#s_03_01').text(4);
			$('#s_03_02').text(0);
			$('#s_03_03').text(2);
			$('#s_04_00').val(0.1);
			$('#s_04_01').val(0.1);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0.05);
		}else if(s_01Temp==true && s_02Temp==true && s_03Temp==false){
			$('#s_03_00').text(4);
			$('#s_03_01').text(4);
			$('#s_03_02').text(2);
			$('#s_03_03').text(0);
			$('#s_04_00').val(0.1);
			$('#s_04_01').val(0.1);
			$('#s_04_02').val(0.05);
			$('#s_04_03').val(0);
		}else if(s_01Temp==true && s_02Temp==false && s_03Temp==false){
			$('#s_03_00').text(5);
			$('#s_03_01').text(5);
			$('#s_03_02').text(0);
			$('#s_03_03').text(0);
			$('#s_04_00').val(0.125);
			$('#s_04_01').val(0.125);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0);
		}else if(s_01Temp==false && s_02Temp==true && s_03Temp==true){
			$('#s_03_00').text(6);
			$('#s_03_01').text(0);
			$('#s_03_02').text(2);
			$('#s_03_03').text(2);
			$('#s_04_00').val(0.15);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0.05);
			$('#s_04_03').val(0.05);
		}else if(s_01Temp==false && s_02Temp==false && s_03Temp==true){
			$('#s_03_00').text(8);
			$('#s_03_01').text(0);
			$('#s_03_02').text(0);
			$('#s_03_03').text(2);
			$('#s_04_00').val(0.2);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0.05);
		}else if(s_01Temp==false && s_02Temp==true && s_03Temp==false){
			$('#s_03_00').text(8);
			$('#s_03_01').text(0);
			$('#s_03_02').text(2);
			$('#s_03_03').text(0);
			$('#s_04_00').val(0.2);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0.05);
			$('#s_04_03').val(0);
		}else if(s_01Temp==false && s_02Temp==false && s_03Temp==false){
			$('#s_03_00').text(10);
			$('#s_03_01').text(0);
			$('#s_03_02').text(0);
			$('#s_03_03').text(0);
			$('#s_04_00').val(0.25);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0);
		}
	}else if(str_M=='B'){
		if(s_01Temp==true && s_02Temp==true && s_03Temp==true){
			$('#s_03_00').text(8);
			$('#s_03_01').text(8);
			$('#s_03_02').text(2);
			$('#s_03_03').text(2);
			$('#s_04_00').val(0.2);
			$('#s_04_01').val(0.2);
			$('#s_04_02').val(0.05);
			$('#s_04_03').val(0.05);
		}else if(s_01Temp==true && s_02Temp==false && s_03Temp==true){
			$('#s_03_00').text(8);
			$('#s_03_01').text(8);
			$('#s_03_02').text(0);
			$('#s_03_03').text(4);
			$('#s_04_00').val(0.2);
			$('#s_04_01').val(0.2);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0.1);
		}else if(s_01Temp==true && s_02Temp==true && s_03Temp==false){
			$('#s_03_00').text(8);
			$('#s_03_01').text(8);
			$('#s_03_02').text(4);
			$('#s_03_03').text(0);
			
			$('#s_04_00').val(0.2);
			$('#s_04_01').val(0.2);
			$('#s_04_02').val(0.1);
			$('#s_04_03').val(0);
		}else if(s_01Temp==true && s_02Temp==false && s_03Temp==false){
			$('#s_03_00').text(10);
			$('#s_03_01').text(10);
			$('#s_03_02').text(0);
			$('#s_03_03').text(0);
			$('#s_04_00').val(0.25);
			$('#s_04_01').val(0.25);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0);
		}else if(s_01Temp==false && s_02Temp==true && s_03Temp==true){
			$('#s_03_00').text(16);
			$('#s_03_01').text(0);
			$('#s_03_02').text(2);
			$('#s_03_03').text(2);
			$('#s_04_00').val(0.4);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0.05);
			$('#s_04_03').val(0.05);
		}else if(s_01Temp==false && s_02Temp==false && s_03Temp==true){
			$('#s_03_00').text(16);
			$('#s_03_01').text(0);
			$('#s_03_02').text(0);
			$('#s_03_03').text(4);
			$('#s_04_00').val(0.4);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0.1);
		}else if(s_01Temp==false && s_02Temp==true && s_03Temp==false){
			$('#s_03_00').text(16);
			$('#s_03_01').text(0);
			$('#s_03_02').text(4);
			$('#s_03_03').text(0);
			$('#s_04_00').val(0.4);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0.1);
			$('#s_04_03').val(0);
		}else if(s_01Temp==false && s_02Temp==false && s_03Temp==false){
			$('#s_03_00').text(20);
			$('#s_03_01').text(0);
			$('#s_03_02').text(0);
			$('#s_03_03').text(0);
			$('#s_04_00').val(0.5);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0);
		}
	}else if(str_M=='C'){
		if(s_01Temp==true && s_02Temp==true && s_03Temp==true){
			$('#s_03_00').text(10);
			$('#s_03_01').text(10);
			$('#s_03_02').text(5);
			$('#s_03_03').text(5);
			
			$('#s_04_00').val(0.17);
			$('#s_04_01').val(0.17);
			$('#s_04_02').val(0.08);
			$('#s_04_03').val(0.08);
		}else if(s_01Temp==true && s_02Temp==false && s_03Temp==true){
			$('#s_03_00').text(12.5);
			$('#s_03_01').text(12.5);
			$('#s_03_02').text(0);
			$('#s_03_03').text(5);
			$('#s_04_00').val(0.21);
			$('#s_04_01').val(0.21);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0.08);
		}else if(s_01Temp==true && s_02Temp==true && s_03Temp==false){
			$('#s_03_00').text(12.5);
			$('#s_03_01').text(12.5);
			$('#s_03_02').text(5);
			$('#s_03_03').text(0);
			$('#s_04_00').val(0.21);
			$('#s_04_01').val(0.21);
			$('#s_04_02').val(0.08);
			$('#s_04_03').val(0);
		}else if(s_01Temp==true && s_02Temp==false && s_03Temp==false){
			$('#s_03_00').text(15);
			$('#s_03_01').text(15);
			$('#s_03_02').text(0);
			$('#s_03_03').text(0);
			$('#s_04_00').val(0.25);
			$('#s_04_01').val(0.25);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0);
		}else if(s_01Temp==false && s_02Temp==true && s_03Temp==true){
			$('#s_03_00').text(24);
			$('#s_03_01').text(0);
			$('#s_03_02').text(3);
			$('#s_03_03').text(3);   
			$('#s_04_00').val(0.22);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0.028);
			$('#s_04_03').val(0.028);
		}else if(s_01Temp==false && s_02Temp==false && s_03Temp==true){
			$('#s_03_00').text(20);
			$('#s_03_01').text(0);
			$('#s_03_02').text(0);
			$('#s_03_03').text(10);
			$('#s_04_00').val(0.33);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0.17);
		}else if(s_01Temp==false && s_02Temp==true && s_03Temp==false){
			$('#s_03_00').text(20);
			$('#s_03_01').text(0);
			$('#s_03_02').text(10);
			$('#s_03_03').text(0);
			$('#s_04_00').val(0.33);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0.17);
			$('#s_04_03').val(0);
		}else if(s_01Temp==false && s_02Temp==false && s_03Temp==false){
			$('#s_03_00').text(30);
			$('#s_03_01').text(0);
			$('#s_03_02').text(0);
			$('#s_03_03').text(0);
			$('#s_04_00').val(0.5);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0);
		}
	}else if(str_M=='D'){
		if(s_01Temp==true && s_02Temp==true && s_03Temp==true){
			$('#s_03_00').text(20);
			$('#s_03_01').text(20);
			$('#s_03_02').text(5);
			$('#s_03_03').text(5);
			
			$('#s_04_00').val(0.4);
			$('#s_04_01').val(0.4);
			$('#s_04_02').val(0.1);
			$('#s_04_03').val(0.1);
		}else if(s_01Temp==true && s_02Temp==false && s_03Temp==true){
			$('#s_03_00').text(20);
			$('#s_03_01').text(20);
			$('#s_03_02').text(0);
			$('#s_03_03').text(10);
			$('#s_04_00').val(0.4);
			$('#s_04_01').val(0.4);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0.2);
		}else if(s_01Temp==true && s_02Temp==true && s_03Temp==false){
			$('#s_03_00').text(20);
			$('#s_03_01').text(20);
			$('#s_03_02').text(10);
			$('#s_03_03').text(0);
			$('#s_04_00').val(0.4);
			$('#s_04_01').val(0.4);
			$('#s_04_02').val(0.2);
			$('#s_04_03').val(0);
		}else if(s_01Temp==true && s_02Temp==false && s_03Temp==false){
			$('#s_03_00').text(25);
			$('#s_03_01').text(25);
			$('#s_03_02').text(0);
			$('#s_03_03').text(0);
			$('#s_04_00').val(0.5);
			$('#s_04_01').val(0.5);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0);
		}else if(s_01Temp==false && s_02Temp==true && s_03Temp==true){
			$('#s_03_00').text(30);
			$('#s_03_01').text(0);
			$('#s_03_02').text(10);
			$('#s_03_03').text(10);
			$('#s_04_00').val(0.6);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0.2);
			$('#s_04_03').val(0.2);
		}else if(s_01Temp==false && s_02Temp==false && s_03Temp==true){
			$('#s_03_00').text(40);
			$('#s_03_01').text(0);
			$('#s_03_02').text(0);
			$('#s_03_03').text(10);
			$('#s_04_00').val(0.8);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0.2);
		}else if(s_01Temp==false && s_02Temp==true && s_03Temp==false){
			$('#s_03_00').text(40);
			$('#s_03_01').text(0);
			$('#s_03_02').text(10);
			$('#s_03_03').text(0);
			$('#s_04_00').val(0.8);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0.2);
			$('#s_04_03').val(0);
		}else if(s_01Temp==false && s_02Temp==false && s_03Temp==false){
			$('#s_03_00').text(50);
			$('#s_03_01').text(0);
			$('#s_03_02').text(0);
			$('#s_03_03').text(0);
			$('#s_04_00').val(1);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0);
		}
	}if(str_M==1){
		if(s_01Temp==true && s_02Temp==true && s_03Temp==true){
			$('#s_03_00').text(15);
			$('#s_03_01').text(15);
			$('#s_03_02').text(10);
			$('#s_03_03').text(10);
			 
			$('#s_04_00').val(0.6);
			$('#s_04_01').val(0.6);
			$('#s_04_02').val(0.4);
			$('#s_04_03').val(0.4);
		}else if(s_01Temp==true && s_02Temp==false && s_03Temp==true){
			$('#s_03_00').text(20);
			$('#s_03_01').text(20);
			$('#s_03_02').text(0);
			$('#s_03_03').text(10);
			$('#s_04_00').val(0.8);
			$('#s_04_01').val(0.8);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0.4);
		}else if(s_01Temp==true && s_02Temp==true && s_03Temp==false){
			$('#s_03_00').text(20);
			$('#s_03_01').text(20);
			$('#s_03_02').text(10);
			$('#s_03_03').text(0);
			$('#s_04_00').val(0.8);
			$('#s_04_01').val(0.8);
			$('#s_04_02').val(0.4);
			$('#s_04_03').val(0);
		}else if(s_01Temp==true && s_02Temp==false && s_03Temp==false){
			$('#s_03_00').text(25);
			$('#s_03_01').text(25);
			$('#s_03_02').text(0);
			$('#s_03_03').text(0);
			$('#s_04_00').val(1);
			$('#s_04_01').val(1);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0);
		}else if(s_01Temp==false && s_02Temp==true && s_03Temp==true){
			$('#s_03_00').text(30);
			$('#s_03_01').text(0);
			$('#s_03_02').text(10);
			$('#s_03_03').text(10);
			$('#s_04_00').val(1.2);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0.4);
			$('#s_04_03').val(0.4);
		}else if(s_01Temp==false && s_02Temp==false && s_03Temp==true){
			$('#s_03_00').text(40);
			$('#s_03_01').text(0);
			$('#s_03_02').text(0);
			$('#s_03_03').text(10);
			$('#s_04_00').val(1.6);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0.4);
		}else if(s_01Temp==false && s_02Temp==true && s_03Temp==false){
			$('#s_03_00').text(40);
			$('#s_03_01').text(0);
			$('#s_03_02').text(10);
			$('#s_03_03').text(0);
			$('#s_04_00').val(1.6);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0.4);
			$('#s_04_03').val(0);
		}else if(s_01Temp==false && s_02Temp==false && s_03Temp==false){
			$('#s_03_00').text(50);
			$('#s_03_01').text(0);
			$('#s_03_02').text(0);
			$('#s_03_03').text(0);
			$('#s_04_00').val(2);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0);
		}
	}if(str_M==2){
		if(s_01Temp==true && s_02Temp==true && s_03Temp==true){
			$('#s_03_00').text(30);
			$('#s_03_01').text(30);
			$('#s_03_02').text(10);
			$('#s_03_03').text(10);
			 5
			$('#s_04_00').val(1.875);
			$('#s_04_01').val(1.875);
			$('#s_04_02').val(0.625);
			$('#s_04_03').val(0.625);
		}else if(s_01Temp==true && s_02Temp==false && s_03Temp==true){
			$('#s_03_00').text(35);
			$('#s_03_01').text(35);
			$('#s_03_02').text(0);
			$('#s_03_03').text(10);
			$('#s_04_00').val(2.1875);
			$('#s_04_01').val(2.1875);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0.625);
		}else if(s_01Temp==true && s_02Temp==true && s_03Temp==false){
			$('#s_03_00').text(35);
			$('#s_03_01').text(35);
			$('#s_03_02').text(10);
			$('#s_03_03').text(0);
			$('#s_04_00').val(2.1875);
			$('#s_04_01').val(2.1875);
			$('#s_04_02').val(0.625);
			$('#s_04_03').val(0);
		}else if(s_01Temp==true && s_02Temp==false && s_03Temp==false){
			$('#s_03_00').text(40);
			$('#s_03_01').text(40);
			$('#s_03_02').text(0);
			$('#s_03_03').text(0);
			$('#s_04_00').val(2.5);
			$('#s_04_01').val(2.5);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0);
		}else if(s_01Temp==false && s_02Temp==true && s_03Temp==true){
			$('#s_03_00').text(60);
			$('#s_03_01').text(0);
			$('#s_03_02').text(10);
			$('#s_03_03').text(10);
			$('#s_04_00').val(3.75);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0.625);
			$('#s_04_03').val(0.625);
		}else if(s_01Temp==false && s_02Temp==false && s_03Temp==true){
			$('#s_03_00').text(70);
			$('#s_03_01').text(0);
			$('#s_03_02').text(0);
			$('#s_03_03').text(10);
			$('#s_04_00').val(4.375);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0.625);
		}else if(s_01Temp==false && s_02Temp==true && s_03Temp==false){
			$('#s_03_00').text(70);
			$('#s_03_01').text(0);
			$('#s_03_02').text(10);
			$('#s_03_03').text(0);
			$('#s_04_00').val(4.275);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0.625);
			$('#s_04_03').val(0);
		}else if(s_01Temp==false && s_02Temp==false && s_03Temp==false){
			$('#s_03_00').text(80);
			$('#s_03_01').text(0);
			$('#s_03_02').text(0);
			$('#s_03_03').text(0);
			$('#s_04_00').val(5);
			$('#s_04_01').val(0);
			$('#s_04_02').val(0);
			$('#s_04_03').val(0);
		}
	}
		
}


 
 