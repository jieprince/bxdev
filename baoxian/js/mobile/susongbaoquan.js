function checkAll(){
   var flag_submit = true;
   var applicant_type =  $('#applicant_type').val();
    if(applicant_type==1){
        if(!check_applicant_name('applicant_fullname','bbxr','app')){
            showMessages('被保险人姓名3-24个字符!','applicant_fullname');
            flag_submit = false;
            return false;
        }else if($('#applicant_certificates_type').val()==""){
            showMessages('被保险人证件类型没有选择!','applicant_certificates_type');
            flag_submit = false;
            return false;
        }else if(!check_applicant_card('applicant_certificates_code','applicant_certificates_type','app')){
            showMessages('被保险人证件号码为空或者证件号码无效!','applicant_certificates_code');
            flag_submit = false;
            return false;
        }
    }else{
        if(!check_applicant_name('applicant_fullname','bbxr','app')){
            showMessages('公司/单位名称3-24个字符!','applicant_fullname');
            flag_submit = false;
            return false;
        }else if(!$('#applicant_fullname2').val()){
            showMessages('联系人不能为空!','applicant_fullname2');
            flag_submit = false;
            return false;
        }
    }
    if(!check_applicant_mobilephone('applicant_mobilephone','app')){
        showMessages('被保险人手机号码不正确或者无效!','applicant_mobilephone');
        flag_submit = false;
        return false;
    }else if(!check_applicant_email('applicant_email','app')){
        showMessages('被保险人Email为空或者格式不正确!','applicant_email');
        flag_submit = false;
        return false;
    }else if($.trim($('#applicant_shiwusuo').val())==""){
        showMessages('被保险人事务所不能为空!','applicant_email');
        flag_submit = false;
        return false;
    }else if($.trim($('#applicant_address').val())==""){
        showMessages('被保险人联系地址不能为空!','applicant_email');
        flag_submit = false;
        return false;
    }
    if(flag_submit){
        $('#form_submit_001').trigger("click");
    }
}