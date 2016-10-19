<!----------------------------------------------以下是最新的验证方法(20150403) 统一的验证方法------------------------------------------------>
/*验证不为空  适用于所有的不为空验证
 *@tag_id 标签id 
*/
function checkAll_Null(tag_id){
	var tag_type = $('#'+tag_id).attr("type");
	var tagName = $('#'+tag_id).get(0).tagName;
	var tag_value = $.trim($('#'+tag_id).val());
	if(tag_value==""){
	    $j('#'+tag_id+'_warn').show().html('内容不能为空！');
        return false;
	}else{
		$j('#'+tag_id+'_warn').html('').hide();	
		return true;
	}
}

//验证英文姓名
function check_en_name(str_cid){
	var reg_s = /^([A-Za-z]+\s?)*[A-Za-z]$/;
	var str_value = $('#'+str_cid).val();
	if(reg_s.test(str_value)){
		$('#'+str_cid+'_warn').attr("class","right_strong");
		$('#'+str_cid+'_warn').html("");
		return true;	
	}else{
		$('#'+str_cid+'_warn').attr("class","wrong_strong");
		$('#'+str_cid+'_warn').html("英文姓名：Tom Jst");
		return false;	
	}
}

/*验证中文姓名
 *dom_id dom节点id
 *type 1 投保人  2被保险人  3  受益人
*/
function check_cn_name(dom_id,type){	
	var text_str = '';
	if(type==1){
		text_str = '投保人';
	}else if(type==2){
		text_str = '被保险人';
	}else if(type==3){
		text_str = '受益人';
	}
	var tname = $.trim($('#'+dom_id).val());
	if( tname == ""){
		$('#'+dom_id+'_warn').attr("class","wrong_strong");
		$('#'+dom_id+'_warn').html("请填写"+text_str+"姓名");
		 
		return false;
	}else{
	    var applicant_fullname = tname;//$j("input#applicant_fullname").val();
	   	if(strlen(applicant_fullname)>24||strlen(applicant_fullname)<3){ 
			$('#'+dom_id+'_warn').attr("class","wrong_strong");
			$('#'+dom_id+'_warn').html(text_str+"姓名不合规，应该大于3个且少于24个字符!");
	    	//window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ;  
	    	return false;
	    }else{
			$('#'+dom_id+'_warn').attr("class","right_strong");
			$('#'+dom_id+'_warn').html("");
			if(type==1 && dom_id=='applicant_fullname'){
				$('#bank_username').val(applicant_fullname);
			}
			return true;
		}
	}
}

//验证是数字
function checked_isNaN(dom_id){
	var tname = $.trim($('#'+dom_id).val());
	if(tname==""){
		$('#'+dom_id+'_warn').attr("class","wrong_strong");
		$('#'+dom_id+'_warn').html("年收入不能为空");
		 
		return false;
	}else{
		if(!isNaN(tname)){
			if(parseFloat(tname)>0){
				$('#'+dom_id+'_warn').attr("class","right_strong");
				$('#'+dom_id+'_warn').html("");
				return true;
				
			}else{
				$('#'+dom_id+'_warn').attr("class","wrong_strong");
				$('#'+dom_id+'_warn').html("年收入必须大于0");
				return false;
				
			}
		}else{
			$('#'+dom_id+'_warn').attr("class","wrong_strong");
			$('#'+dom_id+'_warn').html("年收入为数字");
			return false;
			
		}
	}
}

/*验证证件号码
 *type_domid 证件类型的id
 *dom_id dom节点id
 *type 1 投保人  2被保险人  3  受益人
 *falg true 联动填充生日和性别  验证年龄  false 不联动不验证
 *birthday_domid  生日的dom节点
 *sex_name   性别的dom节点
 *code_int是需要验证(特殊情况) 可以为空 
 *bir_type给出生日和性别去反查匹配
*/
function check_All_card(type_domid,dom_id,type,falg,birthday_domid,sex_name,bir_type,code_int){
	
	var tcardtype = $('#'+type_domid).val();
	var min_age = 18;
	var max_age = 65;
	var sex_M = 1;
	var sex_F = 2;
	if(code_int==6){
		tcardtype = 6;
	}
	if(type==1){
		min_age = 18;
		max_age = 80;
		if($('#cp_name').val()=='picclife_bwsj'){
			min_age = 18;
			max_age = 60;
		}
		$('#'+birthday_domid).attr("disabled",false);
		$('input[name="'+sex_name+'"]').attr("disabled",false);
	}else if(type==2){
		min_age = $('#age_min').val();
		max_age = $('#age_max').val();
		if(bir_type){
			$('#'+birthday_domid).attr("disabled",true);
			$('input[name="'+sex_name+'"]').attr("disabled",true);
		}else{
			$('#'+birthday_domid).attr("disabled",false);
			$('input[name="'+sex_name+'"]').attr("disabled",false);
		}
	}else if(type==3){
		min_age = 0;
		max_age = 300;
		$('#'+birthday_domid).attr("disabled",false);
		$('input[name="'+sex_name+'"]').attr("disabled",false);
	}
    if(tcardtype == ""){
		$('#'+type_domid+'_warn').attr("class","wrong_strong");
		$('#'+type_domid+'_warn').html("选择填写证件类型");
		return false;
	}	
	 
	var idcard = $.trim($('#'+dom_id).val());
	 
	if(idcard == ""){
		$('#'+dom_id+'_warn').attr("class","wrong_strong");
		$('#'+dom_id+'_warn').html("填写证件号码");
		 
		return false;
	}else{
		var retStr = "";
		if(tcardtype == "01" || tcardtype == "1" || tcardtype == "身份证" || tcardtype == "120001"){//证件类型是身份证
			var errorNo = isIdCardNo(idcard);
			if(errorNo == 0){//ok
				
				if(falg){
					$('#'+birthday_domid).attr("disabled",true);
					$('input[name="'+sex_name+'"]').attr("disabled",true);
					var json_sex_bir = code2bir_sex(idcard);
					var ttage = getAgeByBirthday(json_sex_bir.bir);
					var stsSex = (json_sex_bir.sex%2 ==0) ? sex_F : sex_M;
					if(bir_type){
						if(json_sex_bir.bir!=$('#'+birthday_domid).val() || $('input[name="'+sex_name+'"]:checked').val()!=stsSex){
							$('#'+dom_id+'_warn').attr("class","wrong_strong");
							$('#'+dom_id+'_warn').html("身份证和生日或者性别不一致!");
							return false;
						}else{
							if(min_age>ttage || ttage>max_age){
								$('#'+dom_id+'_warn').attr("class","wrong_strong");
								$('#'+dom_id+'_warn').html("身份证号码不在投保范围之内!");
								return false;	
							}else{
								$('#'+dom_id+'_warn').attr("class","right_strong");
								$('#'+dom_id+'_warn').html("");
								return true;	
							}	
						}
					}else{
						$('#'+birthday_domid).val(json_sex_bir.bir);
						$('input[name="'+sex_name+'"][value="'+stsSex+'"]').attr("checked",true);	
						if(min_age>ttage || ttage>max_age){
							$('#'+dom_id+'_warn').attr("class","wrong_strong");
							$('#'+dom_id+'_warn').html("身份证号码不在投保范围之内!");
							return false;	
						}else{
							$('#'+dom_id+'_warn').attr("class","right_strong");
							$('#'+dom_id+'_warn').html("");
							return true;	
						}	
					}	
				}else{
					
					$('#'+dom_id+'_warn').attr("class","right_strong");
					$('#'+dom_id+'_warn').html("");
					return true;
				}
				
			}else{
				retStr = errorIdcardMessage(errorNo);
				//$("#applicant_certificates_code_warn").show();
				$('#'+dom_id+'_warn').attr("class","wrong_strong");
				$('#'+dom_id+'_warn').html(retStr);
				return false;
			}
			
		}else if(tcardtype == "120011"){ //组织机构代码 目前只有华安的使用
			
            var errorNo_or = isOrganizationCodeNo(idcard);
            if(errorNo_or){
				$('#'+dom_id+'_warn').attr("class","right_strong");
				$('#'+dom_id+'_warn').html(""); 
                return true;
            }else{ 
				$('#'+dom_id+'_warn').attr("class","wrong_strong");
				$('#'+dom_id+'_warn').html("格式为xxxxxxxx-x 或 xxxxxxxxx");
                return false;
            }
        }
		 
		$('#'+dom_id+'_warn').attr("class","right_strong");
		$('#'+dom_id+'_warn').html("");
		
	}
	return true;
}

//身份证转化出生日和性别

function code2bir_sex(certificates_code){
	var sex_tempint = 0;
	var br_Temp = '';
	if(certificates_code.length==15){
		sex_tempint = certificates_code.substring(14,15);  //M F
		br_Temp = certificates_code.substring(6,8)+'-'+certificates_code.substring(8,10)+'-'+certificates_code.substring(10,12); 
	}else if(certificates_code.length==18){
		sex_tempint = certificates_code.substring(16,17);
		br_Temp = certificates_code.substring(6,10)+'-'+certificates_code.substring(10,12)+'-'+certificates_code.substring(12,14); 
	}
	
	return {'bir':br_Temp,'sex':sex_tempint};
}

/*验证出生日期
 *domid dom节点id
 *type 1 投保人  2被保险人  3  受益人
*/
function check_birthdays(domid,type){
	var min_age = 18;
	var max_age = 65;
	if($('#cp_name').val()=='picclife_ijiankang'){
		max_age = 80;
	}
	
	var tbirthday = $.trim($('#'+domid).val());
	if(tbirthday == ""){
		$('#'+domid+'_warn').attr("class","wrong_strong");
		$('#'+domid+'_warn').html("请填写出生日期");
		return false;
	}
	else
	{
		//alert("before getAgeByBirthday");
		var ttage = getAgeByBirthday(tbirthday);
	
		//document.getElementsByName('appAge')[0].value = tage;
		
		if(ttage==-1)
		{
			$('#'+domid+'_warn').attr("class","wrong_strong");
			$('#'+domid+'_warn').html("生日格式错误,如(2010-01-02)");
			 
			return false;
		}
		else{
			if(type==1){
				min_age = 18;
				max_age = 80;
				if($('#cp_name').val()=='picclife_bwsj'){
					max_age = 60;
				}
				if(ttage<min_age || ttage>max_age){
					$('#'+domid+'_warn').attr("class","wrong_strong");
					$('#'+domid+'_warn').html("投保人 "+min_age+"--"+max_age+'岁之间');	
					return false
				}
			}else if(type==2){
				min_age = $('#age_min').val();
				max_age = $('#age_max').val();
				if(ttage<min_age || ttage>max_age){
					$('#'+domid+'_warn').attr("class","wrong_strong");
					if($('#cp_name').val()=='picclife_ttl'){
						$('#'+domid+'_warn').html("被保险人 "+min_age+"--"+max_age+'岁之间');	
					}else{
						$('#'+domid+'_warn').html("被保险人 28天--"+max_age+'岁之间');	
					}
					return false
				}
				if($('#cp_name').val()=='picclife_ttl'){
					if(ttage==0){
						 
						var dateBrthirday = $("#server_time").val();
						dateBrthirday = parseInt(dateBrthirday);
						var dateBrthirdaytime = new Date(dateBrthirday*1000);
						dateBrthirdaytime = dateBrthirdaytime.getFullYear()+"-"+(dateBrthirdaytime.getMonth()+1)+"-"+dateBrthirdaytime.getDate();
						
						var diff_Bday = DateDiff(dateBrthirdaytime,tbirthday);
						 
						if(diff_Bday<28){
							$('#'+domid+'_warn').attr("class","wrong_strong");
							$('#'+domid+'_warn').html("被保险人 28天--"+max_age+'岁之间');	
							return false
						}
					}	
				}
			}else if(type==3){
				min_age = 0;
				max_age = 300;
				if(ttage<min_age || ttage>max_age){
					$('#'+domid+'_warn').attr("class","wrong_strong");
					$('#'+domid+'_warn').html("受益人生日大于0岁");	
					return false
				}
			}
		} 
	}
	$('#'+domid+'_warn').attr("class","right_strong");
	$('#'+domid+'_warn').html("");
	return true;
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
//数字和小数点  不能大于100
function check_numberAFloat(domid){
	if((/^(\d+(\.\d+)?)$/).test($('#'+domid).val())){
		if(parseFloat($('#'+domid).val())<=100){
			$('#'+domid+'_warn').attr("class","right_strong");
			$('#'+domid+'_warn').html("");
			return true;
		}else{
			$('#'+domid+'_warn').attr("class","wrong_strong");
			$('#'+domid+'_warn').html("不能大于100");
			return false;
		}
		
	}else{
		$('#'+domid+'_warn').attr("class","wrong_strong");
	  	$('#'+domid+'_warn').html("只能是数字和小数点");
		return false;
	}	
}

//通过身份类型屏蔽生日和性别
function dis_certificates_type(domid,bir_str,sex_name){
	var text_sel = $('#'+domid+' option:selected').text();
	if(text_sel!='身份证'){
		$('#'+bir_str).removeAttr("disabled");
		$('#orderForm input[name="'+sex_name+'"]').removeAttr("disabled");
	}else{
		$('#'+bir_str).attr("disabled",'disabled');
		$('#orderForm input[name="'+sex_name+'"]').attr("disabled",'disabled');
	}
}


//两个日期之间的天数
function  DateDiff(sDate1,sDate2){    //sDate1和sDate2是2006-12-18格式  
   var oDate1,  oDate2,  iDays  ;
   oDate1  =  new  Date(sDate1.replace(/-/g, "/"))  ;  //转换为12-18-2006格式  
   oDate2  =  new  Date(sDate2.replace(/-/g, "/")) ; 
   iDays  =  parseInt(Math.abs(oDate1  -  oDate2)/(1000 * 60 * 60 * 24))  ;  //把相差的毫秒数转换为天数  
   return  (iDays+1);
}  
