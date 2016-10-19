$(document).ready(function(){
	$('#upload_file_list_other_file_02').hide();
    $('#upload_file_list_other_file_03').hide();
    $('#upload_file_list_other_file_08').hide();
    $('#upload_file_list_other_file_09').hide();
    $('#down_list_ssbq .f-filename').val('No file selected...');
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
	 $('#applicant_type').val(1);
	 $('#applicant_type').off("change").on("change",function(){
			if($(this).val()==1){
				$('#text_applicant_fullname').text('姓 名');	
				$('#s_001').hide();
				$('#s_002').hide();
				$('#s_003').show();
                $('#upload_file_list_other_file_02').hide();
                $('#upload_file_list_other_file_03').hide();
                $('#upload_file_list_other_file_08').hide();
                $('#upload_file_list_other_file_09').hide();
                $('#upload_file_list_other_file_10').show();
                $('#upload_file_list_other_file_11').show();
                $('#upload_file_list_other_file_12').show();

			}else{
				$('#text_applicant_fullname').text('单位(公司)名称');		
				$('#s_001').show();
				$('#s_002').show();
				$('#s_003').hide();
                $('#upload_file_list_other_file_10').hide();
                $('#upload_file_list_other_file_11').hide();
                $('#upload_file_list_other_file_12').hide();
                $('#upload_file_list_other_file_02').show();
                $('#upload_file_list_other_file_03').show();
                $('#upload_file_list_other_file_08').show();
                $('#upload_file_list_other_file_09').show();

			}
		 
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
	 $('#startDate').val(laydate.now(+start_dayTemp)+' 00:00:00');
	 
	 var endate_time = timeStamp2String($('#startDate').val().substring(0,10),1,'year');
	 $('#endDate').val(endate_time);
	 
	 /**
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
	**/
	//baoxiangongsi_sel();
	
	//$('#baoxiangongsi_sel').bind("change",function(){
	//	baoxiangongsi_sel();
	//});
	
	gerenqiye_sel();
	
	$('#gerenqiye_sel').bind("change",function(){
		gerenqiye_sel();
	});
	
	$("input[type=file]").change(function(){$(this).parents(".f-uploader").find(".f-filename").val($(this).val());});
	$("input[type=file]").each(function(){
		if($(this).val()==""){$(this).parents(".f-uploader").find(".f-filename").val("No file selected...");}
	}); 
});



function checkedAll(){
	if(!check_applicant_null('applicant_fullname','pc')){
		window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
		return false;	
	}else if(!check_applicant_mobilephone('applicant_mobilephone','pc')){
		window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0)  ; 
		return false;
	}
	if($('#applicant_type').val()==1){
		 
		if(!$('#applicant_certificates_type').val()){
			 
			layer.alert('投保人证件类型没有选择');
			window.setTimeout("document.getElementById('applicant_certificates_type').focus();", 0)  ; 
			return false;
		}else if(!check_All_card('applicant_certificates_type','applicant_certificates_code',1,false,'applicant_birthday','applicant_sex')){
			window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0)  ; 
			return false;
		}
	}else{
		if(!check_applicant_null('applicant_fullname2','pc')){
			window.setTimeout("document.getElementById('applicant_fullname2').focus();", 0)  ; 
			return false;	
		}	
		
		
	}
	
	if(!check_applicant_email('applicant_email','pc')){
		 
		window.setTimeout("document.getElementById('applicant_email').focus();", 0)  ; 
		return false;
	}else if(!check_applicant_null('applicant_shiwusuo','pc')){
		window.setTimeout("document.getElementById('applicant_shiwusuo').focus();", 0)  ; 
		return false;	
		
	}else if(!check_applicant_null('applicant_address','pc')){
		window.setTimeout("document.getElementById('applicant_address').focus();", 0)  ; 
		return false;	
		
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
	
	if($('#vital_info_1_0 input[name="attachment"]').val()=="" || $('#vital_info_2_0 input[name="attachment"]').val()=="" || $('#vital_info_3_0 input[name="attachment"]').val()=="" || $('#vital_info_4_0 input[name="attachment"]').val()=="" ){
		layer.alert('保险公司核保报价必须提供资料:上传不完整,请核实！');
        window.setTimeout("document.getElementById('upload_file_list_01').focus();", 0)  ;
        return false;
	}
    $('#down_list_ssbq table tr td').children().each(function(i,n){
        var dls_data_keyTemp = $(this).data("key");
        var dls_data_valueTemp = $(this).data("value");
        var  percent_baienbi = $('#percent_'+dls_data_keyTemp+'_'+dls_data_valueTemp).text();
        if(percent_baienbi!=""){
            if(percent_baienbi!="0%" && percent_baienbi!="100%"){
                layer.alert('保险公司核保报价必须提供资料:有部分内容正在上传,上传完成再次点击提交！');
                window.setTimeout("document.getElementById('upload_file_list_01').focus();", 0)  ;
                return false;
            }
        }


    });

	$('#orderForm input:disabled').attr("disabled",false);
	return true;	
}


function baoxiangongsi_sel(){
	var bxgs_value = $('#baoxiangongsi_sel').val();
	var khlx_value2 = $('#gerenqiye_sel').val();
	if(bxgs_value=='01'){
		
		$('#upload_file_list_01').hide();
		$('#upload_file_list_03').hide();
		$('#upload_file_list_06').hide();
		$('#upload_file_list_07').hide();
		$('#upload_file_list_08').hide();
		$('#upload_file_list_09').hide();
		$('#upload_file_list_11').hide();
		$('#upload_file_list_02').show();
		$('#upload_file_list_04').show();
		$('#upload_file_list_05').show();
		$('#upload_file_list_10').show();
		$('#upload_file_list_12').show();
		
	}else if(bxgs_value=='02'){
		$('#upload_file_list_04').hide();
		$('#upload_file_list_05').hide();
		$('#upload_file_list_10').hide();
		$('#upload_file_list_12').hide();
		if(khlx_value2=='01'){
			$('#upload_file_list_02').hide();
			$('#upload_file_list_01').show();	
		}else if(khlx_value2=='02'){
			$('#upload_file_list_01').hide();
			$('#upload_file_list_02').show();
		}	
		$('#upload_file_list_03').show();
		$('#upload_file_list_06').show();
		$('#upload_file_list_07').show();
		$('#upload_file_list_08').show();
		$('#upload_file_list_09').show();
		$('#upload_file_list_11').show();
		
	}else if(bxgs_value=='03'){
		$('#upload_file_list_04').hide();
		$('#upload_file_list_05').hide();
		$('#upload_file_list_08').hide();
		$('#upload_file_list_10').hide();
		$('#upload_file_list_11').hide();
		$('#upload_file_list_12').hide();
		if(khlx_value2=='01'){
			$('#upload_file_list_02').hide();
			$('#upload_file_list_01').show();	
		}else if(khlx_value2=='02'){
			$('#upload_file_list_01').hide();
			$('#upload_file_list_02').show();
		}	
		$('#upload_file_list_03').show();
		$('#upload_file_list_06').show();
		$('#upload_file_list_07').show();
		$('#upload_file_list_09').show();
		

	}
}
 
 
function gerenqiye_sel(){
	var khlx_value = $('#gerenqiye_sel').val();
	var bxgs_value2 = $('#baoxiangongsi_sel').val();
	 
	if(bxgs_value2=='01'){
		layer.alert('选择平安保险公司客户类型只能是个人');
		$('#gerenqiye_sel').val('01');
	}else{
		if(khlx_value=='01'){
			$('#upload_file_list_01').hide();
			$('#upload_file_list_02').show();	
		}else if(khlx_value=='02'){
			$('#upload_file_list_02').hide();
			$('#upload_file_list_01').show();
		}	
	}
	
}
 


function addinput(ef){
	var name_temps_value = $(ef).parent().parent().parent().children().last().data("value");	
	var name_temps_key = $(ef).parent().parent().parent().children().last().data("key");
	
	var html_opp = '<div class="f-pl10" data-value="'+(parseInt(name_temps_value)+1)+'" data-key="'+name_temps_key+'">'+
	   '<form id='+name_temps_key+'_'+(parseInt(name_temps_value)+1)+' action="../user.php?act=upload_file&path_type=1" method="post" enctype="multipart/form-data">'+
		'<div class="f-uploader f-white f-ib f-vam">'+
		'<input type="text" class="f-filename" readonly="readonly"/>'+
		'<input type="button" name="file" class="f-button" value="文件上传..." >'+
		'<input type="file" size="30" name="attachment" onchange="upload_file(\''+name_temps_key+'_'+(parseInt(name_temps_value)+1)+'\',\''+name_temps_key+'\');" /></div>'+
		'<div class=" f-ib f-vam f-ml10">'+
		'<a href="javascript:void(0)" class="btn btn-danger" onclick="deleinput(this)">删除</a>'+
        '&#12288;<span id="progress_'+name_temps_key+'_'+(parseInt(name_temps_value)+1)+'"><span id="bar_'+name_temps_key+'_'+(parseInt(name_temps_value)+1)+'"></span>'+
        '<span id="percent_'+name_temps_key+'_'+(parseInt(name_temps_value)+1)+'">0%</span ></span>'+ 
	'</div></div>';
	
	$(ef).parent().parent().parent().append(html_opp);
	
	$("input[type=file]").change(function(){$(this).parents(".f-uploader").find(".f-filename").val($(this).val());});
	$("input[type=file]").each(function(){
		if($(this).val()==""){$(this).parents(".f-uploader").find(".f-filename").val("No file selected...");}
	}); 
}

function deleinput(ef){
	$(ef).parent().parent().parent().remove();
}
 