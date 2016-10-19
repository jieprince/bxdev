$(document).ready(function(){
	
	 //产品份数计算价格
	 $("select#applyNum").change(function(){											
	     var applyNum = $(this).val();
		 var premium =  $("#premium").val();
		 var totalpremium = accMul(premium,applyNum);
		 if($('#orderForm input[name="assured_input_type"]:checked').val()==2){
			 var length_posion_table = $('#ps_list table tr').length-1;
			 var poidTemp = $('#bat_post_policy').val();
			 if(poidTemp!=2){
				 length_posion_table = parseInt(length_posion_table/2);
			 }
			 totalpremium = accMul(length_posion_table,totalpremium);
	     }
		 $("#showprice").html(totalpremium);
		 $("#totalModalPremium").val(totalpremium);													   
	 });
	 
	 $('#applicant_certificates_type').off("change").on("change",function(){
		$('#applicant_birthday').attr("disabled",false); 
		$('#orderForm input[name="applicant_sex"]').attr("disabled",false);	
		$('#applicant_birthday_warn').attr("class","mes_right");
		$('#applicant_birthday_warn .mes_text').html("");
		if($(this).val()==1){
			$('#applicant_birthday').off("blur");
			
		}else{
			$('#applicant_birthday').off("blur").on("blur",function(){
				check_birthdays('applicant_birthday',1);	
			});	
		}
	 });
	 
	 $('#assured_certificates_type').off("change").on("change",function(){
		$('#assured_birthday').attr("disabled",false); 
		$('#orderForm input[name="assured[0][assured_sex]"]').attr("disabled",false); 
		$('#assured_birthday_warn').attr("class","mes_right");
		$('#assured_birthday_warn .mes_text').html("");
		if($(this).val()==1){
			$('#assured_birthday').off("blur");
		}else{
			$('#assured_birthday').off("blur").on("blur",function(){
				check_birthdays('assured_birthday',2);	
			});	
		}
	 });

     $('#relationshipWithInsured').val(1); 
     $('#relationshipWithInsured').off("change").on("change",function(){
		var relationshipWithInsured_value = $(this).val();	
		if(relationshipWithInsured_value==1){
			$("#div_assured").hide();	
		}else{
			$("#div_assured").show();
		} 
	 });	 
	 var start_dayTemp = $('#start_day').val();
	 if(start_dayTemp==0){
		start_dayTemp = 6;	 
	 }
	 laydate({
		elem: '#startDate',
		format: 'YYYY-MM-DD hh:mm:ss', // 分隔符可以任意定义，该例子表示只显示年月
		festival: true, //显示节日
		min: laydate.now(+start_dayTemp),
		start: laydate.now(+start_dayTemp),
		choose: function(datas){ //选择日期完毕的回调
			var endate_time = timeStamp2String(datas.substring(0,10),$('#period').val());
			$('#endDate').val(endate_time);
		}
	});
	 
});



function checkedAll(){
	if(!check_applicant_name('applicant_fullname','tbr','pc')){
		window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
		return false;	
	}else if(!check_applicant_mobilephone('applicant_mobilephone','pc')){
		window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0)  ; 
		return false;
	}else if(!$('#applicant_certificates_type').val()){
		layer.alert('投保人证件类型没有选择');
		window.setTimeout("document.getElementById('applicant_certificates_type').focus();", 0)  ; 
		return false;
	}else if(!check_All_card('applicant_certificates_type','applicant_certificates_code',1,true,'applicant_birthday','applicant_sex')){
		window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0)  ; 
		return false;
	}else if(!check_birthdays('applicant_birthday',1)){
		window.setTimeout("document.getElementById('applicant_birthday').focus();", 0)  ; 
		return false;
	}else if(!$('#orderForm input[name="applicant_sex"]:checked').val()){
		layer.alert('投保人性别没有选择');
		window.setTimeout("document.getElementById('applicant_sex_s').focus();", 0)  ; 
		 
		return false;
	}else if(!check_applicant_email('applicant_email','pc')){
		 
		window.setTimeout("document.getElementById('applicant_email').focus();", 0)  ; 
		return false;
	}else if(!$('#startDate').val()){
		layer.alert('保险起期没有选择');
		window.setTimeout("document.getElementById('startDate').focus();", 0)  ; 
		return false;
	}else if(!$('#endDate').val()){
		layer.alert('保险止期没有选择');
		window.setTimeout("document.getElementById('endDate').focus();", 0)  ; 
		return false;
	}else if($('#relationshipWithInsured').val()==1){
		var age_min = $("input#age_min").val();
	    var age_max = $("input#age_max").val();
		var birthday_applicant = document.getElementById("applicant_birthday").value;
	    document.getElementById("assured_birthday").value = birthday_applicant;
	    var ttagesTemp = getAgeByBirthday(birthday_applicant);
	    if(ttagesTemp<age_min || ttagesTemp>age_max){
			layer.alert("选择本人情况下,被保险人年龄不在投保范围内！");
			window.setTimeout("document.getElementById('applicant_birthday').focus();", 0)  ; 
			return false;
		}
		 
	}else if($('#relationshipWithInsured').val()!=1){
		if(!check_applicant_name('assured_fullname','bbxr','pc')){
			window.setTimeout("document.getElementById('assured_fullname').focus();", 0)  ; 
			return false;	
		}else if(!check_applicant_mobilephone('assured_mobilephone','pc')){
			window.setTimeout("document.getElementById('assured_mobilephone').focus();", 0)  ; 
			return false;
		}else if(!$('#assured_certificates_type').val()){
			layer.alert('被保险人证件类型没有选择');
			window.setTimeout("document.getElementById('assured_certificates_type').focus();", 0)  ; 
			return false;
		}else if(!check_All_card('assured_certificates_type','assured_certificates_code',2,true,'assured_birthday','assured[0][assured_sex]')){
			window.setTimeout("document.getElementById('assured_certificates_code').focus();", 0)  ; 
			return false;
		}else if(!check_birthdays('assured_birthday',2)){
			window.setTimeout("document.getElementById('assured_birthday').focus();", 0)  ; 
			return false;
		}else if(!$('#orderForm input[name="assured[0][assured_sex]"]:checked').val()){
			layer.alert('被保险人性别没有选择');
			window.setTimeout("document.getElementById('assured_sex_s').focus();", 0)  ; 
			 
			return false;
		}else if(!check_applicant_email('assured_email','pc')){
			 
			window.setTimeout("document.getElementById('assured_email').focus();", 0)  ; 
			return false;
		}
	}
	
	if($('#cp_name').val()=='pingan_jingwailvyou'){
		/*if(!check_en_name('applicant_fullname_english','tbr','pc')){
			layer.alert('投保人英文姓名不能为空');
			window.setTimeout("document.getElementById('applicant_fullname_english').focus();", 0)  ;
			return false;
		}
		if($('#relationshipWithInsured').val()!=1){
			if(!check_en_name('assured_fullname_english','bbxr','pc')){
				layer.alert('被保险人英文姓名不能为空');
				window.setTimeout("document.getElementById('assured_fullname_english').focus();", 0)  ;
				return false;
			}
		}*/
		
		if(!check_applicant_null('destinationCountry','pc')){
				
				window.setTimeout("document.getElementById('destinationCountry').focus();", 0)  ; 
				return false;
		   }else if($('#destinationCountry').val()=='其他'){
			   if($.trim($('#select_other_cou').val())==""){
				   layer.alert('请填写目的地国家！');
				   window.setTimeout("document.getElementById('select_other_cou').focus();", 0)  ;
				   return false;
			   }

		   }
	}
	if(!$('#radiobutton_yes').prop("checked")){
		layer.alert('同意条款没有选择');
		return false;		
	}
	$('#orderForm input:disabled').attr("disabled",false);
	return true;	
}
 
 


 
 