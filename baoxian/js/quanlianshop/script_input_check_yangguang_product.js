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
	 
	 $('#applicant_certificates_type').val(10);
	 $('#applicant_certificates_type').off("change").on("change",function(){
		$('#applicant_birthday').attr("disabled",false); 
		$('#orderForm input[name="applicant_sex"]').attr("disabled",false);	
		$('#applicant_birthday_warn').attr("class","mes_right");
		$('#applicant_birthday_warn .mes_text').html("");
		if($(this).val()==10){
			$('#applicant_birthday').off("blur");
			
		}else{
			$('#applicant_birthday').off("blur").on("blur",function(){
				check_birthdays('applicant_birthday',1);	
			});	
		}
	 });
	 
	 $('#assured_certificates_type_01').val(10);
	 $('#assured_certificates_type_01').off("change").on("change",function(){
		$('#assured_birthday_01').attr("disabled",false); 
		$('#orderForm input[name="assured_sex_01"]').attr("disabled",false); 
		$('#assured_birthday_01_warn').attr("class","mes_right");
		$('#assured_birthday_01_warn .mes_text').html("");
		if($(this).val()==10){
			$('#assured_birthday_01').off("blur");
		}else{
			$('#assured_birthday_01').off("blur").on("blur",function(){
				check_birthdays('assured_birthday_01',2);	
			});	
		}
	 });

     $('#relationshipWithInsured_01').val(61); 
     $('#relationshipWithInsured_01').off("change").on("change",function(){
		var relationshipWithInsured_value = $(this).val();	
		if(relationshipWithInsured_value==10){
			app_copy_2_ass(1,1);
		}else{
			app_copy_2_ass(0,1);
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
	}else if(!$('#startDate').val()){
		layer.alert('保险起期没有选择');
		window.setTimeout("document.getElementById('startDate').focus();", 0)  ; 
		return false;
	}else if(!$('#endDate').val()){
		layer.alert('保险止期没有选择');
		window.setTimeout("document.getElementById('endDate').focus();", 0)  ; 
		return false;
	}
	var check_ssured_res = check_ssured_All();
	 
	if(!check_ssured_res.flag){
		layer.alert(check_ssured_res.wrong_text,{'title':'温馨提示'});
		return false;
	}
	
	if(!$('#radiobutton_yes').prop("checked")){
		layer.alert('同意条款没有选择');
		return false;		
	}
	$('#orderForm input:disabled').attr("disabled",false);
	return true;	
}



//增加被保险人
function add_assured(){
		
	var last_value = $('#assured_list').children().last().data('value');
	var new_last_value = parseInt(last_value)+1;
	var persion_index_value = parseInt($('#assured_list').children().last().find(".persion_index").text())+1;
	var relationshipWithInsured_html = $('#relationshipWithInsured_01').html();
	var assured_certificates_type_html = $('#assured_certificates_type_01').html();
	var assured_html = '<div class="f-mt20" id="assured_child_0'+new_last_value+'" data-value="0'+new_last_value+'"><table cellpadding="0" cellspacing="0" border="0" class="table_buy_01 s-bsc_solid_d2d2d2"><tr class="s-bscB_dashed_d2d2d2"><td width="150"><div class="f-pl10"><h4>被保险人(<span class="persion_index">'+persion_index_value+'</span>)</h4></div></td><td width="348" colspan="3"><div align="right" class="f-pr10"><a class="btn btn-danger" href="javascript:void(0)" onclick="delete_assured('+new_last_value+')">删除</a></div></td></tr><tr><th width="150">与投保人关系：</th><td><div class="f-pl10"><select id="relationshipWithInsured_0'+new_last_value+'" class="s-select-1">'+relationshipWithInsured_html+'</select></div></td><th width="150">姓 名：</th><td width="348"><div class="f-pl10"><input type="text" class="g-h35 g-w210 f-pl5 f-pr5" placeholder="姓名" id="assured_fullname_0'+new_last_value+'" onblur="check_applicant_name(\'assured_fullname_0'+new_last_value+'\',\'bbxr\',\'pc\')" ><span class="mes_right" id="assured_fullname_0'+new_last_value+'_warn"><span class="dec"><s class="dec1">◆</s><s class="dec2">◆</s></span><span class="mes_text"></span></span></div></td></tr><tr><th>证件类型：</th><td><div class="f-pl10"><select id="assured_certificates_type_0'+new_last_value+'" class="s-select-1">'+assured_certificates_type_html+'</select><span class="mes_right" id="assured_certificates_type_0'+new_last_value+'_warn"><span class="dec"><s class="dec1">◆</s><s class="dec2">◆</s></span><span class="mes_text"></span></span></div></td><th>证件号码：</th><td><div class="f-pl10"><input type="text" class="g-h35 g-w210 f-pl5 f-pr5" placeholder="证件号码"  id="assured_certificates_code_0'+new_last_value+'" onblur="check_All_card(\'assured_certificates_type_0'+new_last_value+'\',\'assured_certificates_code_0'+new_last_value+'\',2,true,\'assured_birthday_0'+new_last_value+'\',\'assured_sex_0'+new_last_value+'\')"><span class="mes_right" id="assured_certificates_code_0'+new_last_value+'_warn"><span class="dec"><s class="dec1">◆</s><s class="dec2">◆</s></span><span class="mes_text"></span></span></div></td></tr><tr><th>生日：</th><td><div class="f-pl10"><input type="text" class="g-h35 g-w210 f-pl5 f-pr5" id="assured_birthday_0'+new_last_value+'"><span class="mes_right" id="assured_birthday_0'+new_last_value+'_warn"><span class="dec"><s class="dec1">◆</s><s class="dec2">◆</s></span><span class="mes_text"></span></span></div></td><th>性 别：</th><td><div class="f-pl10" id="assured_sex_s_0'+new_last_value+'"><input type="radio" name="assured_sex_0'+new_last_value+'" value="1" checked > 男 <input type="radio" name="assured_sex_0'+new_last_value+'" value="2"> 女 <span class="mes_right" id="assured_sex_0'+new_last_value+'_warn"><span class="dec"><s class="dec1">◆</s><s class="dec2">◆</s></span><span class="mes_text"></span></span></div></td></tr><tr><th>E-mail：</th><td><div class="f-pl10"><input type="text" class="g-h35 g-w210 f-pl5 f-pr5" id="assured_email_0'+new_last_value+'" onblur="check_applicant_email(\'assured_email_0'+new_last_value+'\',\'pc\')"><span class="mes_right" id="assured_email_0'+new_last_value+'_warn"><span class="dec"><s class="dec1">◆</s><s class="dec2">◆</s></span><span class="mes_text"></span></span></div></td><th width="150">手机号码：</th><td width="348"><div class="f-pl10"><input type="text" class="g-h35 g-w210 f-pl5 f-pr5" placeholder="手机号码" id="assured_mobilephone_0'+new_last_value+'" onblur="check_applicant_mobilephone(\'assured_mobilephone_0'+new_last_value+'\',\'pc\')"><span class="mes_right" id="assured_mobilephone_0'+new_last_value+'_warn"><span class="dec"><s class="dec1">◆</s><s class="dec2">◆</s></span><span class="mes_text"></span></span></div></td></tr></table></div>';
	$('#assured_list').append(assured_html);	
	
	$('#assured_certificates_type_0'+new_last_value).val(10);
	$('#assured_certificates_type_0'+new_last_value).off("change").on("change",function(){
		$('#assured_birthday_0'+new_last_value).attr("disabled",false); 
		$('#orderForm input[name="assured_sex_0'+new_last_value+'"]').attr("disabled",false); 
		$('#assured_birthday_0'+new_last_value+'_warn').attr("class","mes_right");
		$('#assured_birthday_0'+new_last_value+'_warn .mes_text').html("");
		if($(this).val()==10){
			$('#assured_birthday_0'+new_last_value).off("blur");
		}else{
			$('#assured_birthday_0'+new_last_value).off("blur").on("blur",function(){
				check_birthdays('assured_birthday_0'+new_last_value,2);	
			});	
		}
	 });

     $('#relationshipWithInsured_0'+new_last_value).val(61); 
     $('#relationshipWithInsured_0'+new_last_value).off("change").on("change",function(){
		var relationshipWithInsured_value = $(this).val();	
		if(relationshipWithInsured_value==10){
			app_copy_2_ass(1,new_last_value);
		}else{
			app_copy_2_ass(0,new_last_value);
		} 
	 }); 
}

function app_copy_2_ass(str_flag,res_value){
	var applicant_fullname_value = '';
	var applicant_mobilephone_value = '';
	var applicant_certificates_type_value = 10;
	var applicant_birthday_value = '';
	var applicant_certificates_code_value = '';
	var applicant_sex_value = '';
	if(str_flag==1){
		applicant_fullname_value = $('#applicant_fullname').val();
		applicant_mobilephone_value = $('#applicant_mobilephone').val();
		applicant_certificates_type_value = $('#applicant_certificates_type').val();
		applicant_birthday_value = $('#applicant_birthday').val();
		applicant_certificates_code_value = $('#applicant_certificates_code').val();
		applicant_sex_value = $('#applicant_sex_s input[name="applicant_sex"]:checked').val();
	}
	 
	$('#assured_fullname_0'+res_value).val(applicant_fullname_value);
	$('#assured_certificates_type_0'+res_value).val(applicant_certificates_type_value);
	$('#assured_certificates_code_0'+res_value).val(applicant_certificates_code_value);
	$('#assured_birthday_0'+res_value).val(applicant_birthday_value);
	if(str_flag==1){
		$('#assured_sex_s_0'+res_value+' input[name="assured_sex_0'+res_value+'"][value="'+applicant_sex_value+'"]').prop("checked",true); 
	}else{
		$('#assured_sex_s_0'+res_value+' input[name="assured_sex_0'+res_value+'"]').prop("checked",false); 	
	}
	
	$('#assured_mobilephone_0'+res_value).val(applicant_mobilephone_value);
}

//删除被保险人
function delete_assured(num_assured){
	$('#assured_child_0'+num_assured).remove(); 
	$('#assured_list').children().each(function(i, n) {
         $(this).find(".persion_index").text($(this).index()+1);
    });
}


//验证被保险人
function check_ssured_All(){
	var wrong_text_all = ''; 
	var flag_As = true;
	var ssured_json =[]; 
	$('#assured_list').children().each(function(i,n) {
		
		var i_AS = 1;
        var assured_child_value = $(this).data("value");
		var persion_index_text = $(this).find(".persion_index").text();
		wrong_text_all += '被保险人('+persion_index_text+')的错误信息：<br>';
		//if(!$('#relationshipWithInsured_'+assured_child_value)){
				
		//}
		if(!check_applicant_name('assured_fullname_'+assured_child_value,'bbxr','pc')){
		 	wrong_text_all += '('+i_AS+')被保险人姓名有误，2个以上汉字;<br>';
			i_AS += 1;
			flag_As = false;
		}
		if(!$('#assured_certificates_type_'+assured_child_value).val()){
			 wrong_text_all += '('+i_AS+')被保险人证件类型没有选择<br>';
			 i_AS += 1;
			 flag_As = false;
		}
		if(!check_All_card('assured_certificates_type_'+assured_child_value,'assured_certificates_code_'+assured_child_value,2,true,'assured_birthday_'+assured_child_value,'assured_sex_'+assured_child_value)){
			 wrong_text_all += '('+i_AS+')被保险人证件号码有误<br>';
			 i_AS += 1;
			 flag_As = false;
		}
		if(!check_birthdays('assured_birthday_'+assured_child_value,2)){
			 wrong_text_all += '('+i_AS+')被保险人出生日期有误<br>';
			 i_AS += 1;
			 flag_As = false;
		}
		if(!$('#orderForm input[name="assured_sex_'+assured_child_value+'"]:checked').val()){
			 wrong_text_all += '('+i_AS+')被保险人性别没有选择<br>';
			 i_AS += 1;
			 flag_As = false;
		}
		if(!check_applicant_email('assured_email_'+assured_child_value,'pc')){
			 wrong_text_all += '('+i_AS+')被保险人邮箱格式有误<br>';
			 i_AS += 1;
			 flag_As = false;
		}
		if(!check_applicant_mobilephone('assured_mobilephone_'+assured_child_value,'pc')){
			 wrong_text_all += '('+i_AS+')被保险人手机号码有误<br>';
			 i_AS += 1;
			 flag_As = false;
		}
		var relationshipWithInsured_value = $('#relationshipWithInsured_'+assured_child_value).val();
		var assured_fullname_value = $('#assured_fullname_'+assured_child_value).val();
		var assured_certificates_type_value = $('#assured_certificates_type_'+assured_child_value).val();
		var assured_certificates_code_value = $('#assured_certificates_code_'+assured_child_value).val();
		var assured_birthday_value = $('#assured_birthday_'+assured_child_value).val();
		var assured_sex_value = $('#assured_sex_s_'+assured_child_value+' input[name="assured_sex_'+assured_child_value+'"]:checked').val();
		var assured_email_value = $('#assured_email_'+assured_child_value).val();
		var assured_mobilephone_value = $('#assured_mobilephone_'+assured_child_value).val();
		var res_json = {'assured_relationship':relationshipWithInsured_value,'fullname':assured_fullname_value,'certificates_type':assured_certificates_type_value,'certificates_code':assured_certificates_code_value,'birthday':assured_birthday_value,'gender':assured_sex_value,'email':assured_email_value,'mobiletelephone':assured_mobilephone_value};
	 
		ssured_json.push(res_json);
		
    });
	
	if(flag_As){
		$('#assured_info').val(JSON.stringify(ssured_json));
	}
	return {'wrong_text':wrong_text_all,'flag':flag_As};
	
	
}
 


 
 