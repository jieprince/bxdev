
//验证投保人姓名
function check_applicant_name()
{	
	var tname;
	if(document.getElementsByName("applicant_fullname")){
		tname = document.getElementsByName("applicant_fullname")[0].value.replace(/\ /g,"");
	}
	
	if( tname == ""){
		document.getElementById("applicant_fullname_warn").className = "wrong_strong";
		if($('#attribute_type').val()=='Y502'){
			document.getElementById("applicant_fullname_warn").innerHTML = "请填写联系人姓名";
		}else{
			document.getElementById("applicant_fullname_warn").innerHTML = "请填写投保人姓名";	
		}
		
		//window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ; 
		return false;
	}
	else
	{
		

	    var applicant_fullname = tname;//$j("input#applicant_fullname").val();
	   	if(strlen(applicant_fullname)>24||strlen(applicant_fullname)<3)
	    {
	    	//alert("投保人姓名不合规，应该大于3个且少于24个字符!");
			document.getElementById("applicant_fullname_warn").className = "wrong_strong";
			if($('#attribute_type').val()=='Y502'){
				document.getElementById("applicant_fullname_warn").innerHTML = "联系人填写有误!";
			}else{
				document.getElementById("applicant_fullname_warn").innerHTML = "投保人姓名不合规，应该大于3个且少于24个字符!";
			}
			
	    	//window.setTimeout("document.getElementById('applicant_fullname').focus();", 0)  ;  
	    	return false;
	    }
		
		if(document.getElementById("applicant_fullname2"))
		{
			document.getElementById("applicant_fullname2").value = tname;
		}
		
		document.getElementById("applicant_fullname_warn").className = "right_strong";
		document.getElementById("applicant_fullname_warn").innerHTML= "";
	}
	if($('#insurer_code').val()=='PAC04'){
		$('#ass_nameTempssq').text(tname);
	}
	
	return true;
}


//检查投保人证件号
function check_applicant_card(code_int)
{
	var tcardtype = document.getElementById("applicant_certificates_type").value;
	var str_temp = 'applicant_certificates_code_warn';
	var str_temptype = 'applicant_certificates_type_warn';
	if(code_int==6){
		tcardtype = 6;
	}
    if(tcardtype == "")
    {
		document.getElementById(str_temptype).className = "wrong_strong";
		document.getElementById(str_temptype).innerHTML = "选择填写证件类型";
		//window.setTimeout("document.getElementById('certType').focus();", 0)  ; 
		 
		return false;
	}	
	
	//if(document.getElementById("certCode"))
	//	tcardcode = document.getElementById("certCode").value;//投保人证件号
	
	var idcard = document.getElementById("applicant_certificates_code").value;
	if($('input[name="applicant_type"]:checked').val()==2&&code_int==6){  //验证不是Y502以外的团购证件验证 
		 idcard = document.getElementById("applicant_group_certificates_code").value;
		 str_temp = 'applicant_group_certificates_code_warn';
	}
	 
	if(idcard == "")
	{
		document.getElementById(str_temp).className="wrong_strong";
		document.getElementById(str_temp).innerHTML = "请填写证件号码";
		 
		//window.setTimeout("document.getElementById('certType').focus();", 0)  ; 
		return false;
	}
	else
	{
		var retStr = "";
		if(tcardtype == "01" || tcardtype == "1")
		{//证件类型是身份证
			var errorNo = isIdCardNo(idcard);
			if(errorNo == 0)//ok
			{
				//$("#tcard_warn").show();
				document.getElementById("applicant_certificates_code_warn").className="right_strong";
				document.getElementById("applicant_certificates_code_warn").innerHTML = "";
				//window.setTimeout("document.getElementById('certCode').focus();", 0)  ; 
				
				//alert("sdfsdf");
				return true;
			}
			else
			{
				retStr = errorIdcardMessage(errorNo);
				//$("#applicant_certificates_code_warn").show();
				document.getElementById("applicant_certificates_code_warn").className="wrong_strong";
				document.getElementById("applicant_certificates_code_warn").innerHTML = retStr;
				//window.setTimeout("document.getElementById('certCode').focus();", 0)  ; 
				return false;
			}
			
		}else{
			
			document.getElementById(str_temp).className="right_strong";
			document.getElementById(str_temp).innerHTML = "";	
		}
		
		
	}
	
	return true;
}


//验证投保人出生日期
function check_applicant_birthday()
{
	var tbirthday = document.getElementById("applicant_birthday").value;
	if(tbirthday == "")
	{
		document.getElementById("applicant_birthday_warn").className = "wrong_strong";
		document.getElementById("applicant_birthday_warn").innerHTML = "请填写出生日期";
		//window.setTimeout("document.getElementById('applicant_birthday').focus();", 0)  ; 
		return false;
	}
	else
	{
		//alert("before getAgeByBirthday");
		var ttage = getAgeByBirthday(tbirthday);
	
		//document.getElementsByName('appAge')[0].value = tage;
		
		if(ttage==-1)
		{
			document.getElementById("applicant_birthday_warn").className = "wrong_strong";
			document.getElementById("applicant_birthday_warn").innerHTML = "投保人生日格式错误,例子(2010-01-02)";
			//window.setTimeout("document.getElementById('applicant_birthday').focus();", 0);
			
			return false;
		}
		else if(ttage < 18)
		{
			document.getElementById("applicant_birthday_warn").className = "wrong_strong";
			document.getElementById("applicant_birthday_warn").innerHTML = "投保人必须大于18岁";
			//window.setTimeout("document.getElementById('applicant_birthday').focus();", 0);
			return false
		}
		else
		{
			if($('#cp_name').val()=='pingan_lvyou' || $('#cp_name').val() == 'pingan_lvyoujingwai'){
				if(ttage >= 80){
					document.getElementById("applicant_birthday_warn").className = "wrong_strong";
					document.getElementById("applicant_birthday_warn").innerHTML = "投保人必须小于80岁";
					//window.setTimeout("document.getElementById('applicant_birthday').focus();", 0);
					return false
				}	
			}else{
				document.getElementById("applicant_birthday_warn").className = "right_strong";
				document.getElementById("applicant_birthday_warn").innerHTML= "";
			}
			
		}
		
		//alert("after getAgeByBirthday, ttage: "+ttage);
	}
	
	return true;
}


function check_applicant_sex()
{
	
	if(document.getElementsByName("applicant_sex")[0])
	{
		tsex1 = document.getElementsByName("applicant_sex")[0].checked;//投保人性别
	}
	
	if(document.getElementsByName("applicant_sex")[1])
		tsex2 = document.getElementsByName("applicant_sex")[1].checked;//投保人性别
    
    if(tsex1==false && tsex2==false)
    {
		//window.setTimeout("document.getElementsByName('appSex')[0].focus();", 0)  ;  
		document.getElementById("applicant_sex_warn").className = "wrong_strong";
		document.getElementById("applicant_sex_warn").innerHTML = "请选择";
		return false;
	}
    
    return true;
    	
}


//检查投保人手机号  val_str 不为空是团体
function check_applicant_mobilephone(val_str)
{
	var str_temp = 'applicant_mobilephone';
	if(val_str){
		str_temp = val_str;
	}
	var warn_temp = str_temp+"_warn";
 
	if(isNull(document.getElementsByName(str_temp)[0].value)) 
	{
		
		document.getElementById(warn_temp).className = "wrong_strong";
		document.getElementById(warn_temp).innerHTML = "请填写手机号码"; 
		//window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0);
		
		return false;
	}
	else if(!isMobile(document.getElementsByName(str_temp)[0].value,true)) 
	{
		document.getElementById(warn_temp).className = "wrong_strong";
		document.getElementById(warn_temp).innerHTML = "请填写正确的手机号";
		//window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0);
		return false;
	}
	else
	{
		document.getElementById(warn_temp).className = "right_strong";
		document.getElementById(warn_temp).innerHTML= "";
	}

	return true;
}

//检查投保人电子邮箱   val_str 不为空是团体
function check_applicant_email(val_str)
{
	var str_temp = 'applicant_email';
	if(val_str){
		str_temp = val_str;
	}
	if(document.getElementsByName(str_temp)[0].value == "") 
	{
		document.getElementById(str_temp+"_warn").className = "wrong_strong";
		document.getElementById(str_temp+"_warn").innerHTML = "请填写电子邮箱";
		//window.setTimeout("document.getElementById('applicant_email').focus();", 0);
		return false;
	}
	else if(!isEmail(document.getElementsByName(str_temp)[0].value, true)) 
	{
		document.getElementById(str_temp+"_warn").className = "wrong_strong";
		document.getElementById(str_temp+"_warn").innerHTML = "请填写正确的电子邮箱";
		//window.setTimeout("document.getElementById('applicant_email').focus();", 0);
		return false;
	}
	else
	{
		document.getElementById(str_temp+"_warn").className = "right_strong";
		document.getElementById(str_temp+"_warn").innerHTML= "";
	}


	return true;

}
///////////////下面是检查被保险人信息/////////////////////////////////////////////////////////////////////////


// 选择被保人与投保人的关系
function chooserelation(obj,sAge,eAge){
	var r = obj.value;
	checkappName();
	var tage = document.getElementsByName("appAge")[0].value;
	if(tage < 18){
		document.getElementById("appBirthday_warn").style.display = "";
		document.getElementById("appBirthday_warn").className = "wrong_strong";
		document.getElementById("appBirthday_warn").innerHTML = "投保人必须大于18岁";
	}else{
		document.getElementById("appBirthday_warn").className = "right_strong";
		document.getElementById("appBirthday_warn").innerHTML= "";
	}
	if(r == "5"){//本人
		if(document.getElementById("tb1")){
			document.getElementById("tb1").style.display="none"
		}
		if(document.getElementById("tb2")){
			document.getElementById("tb2").style.display="none"
		}
	}else{
		if(document.getElementById("tb1")){
			document.getElementById("tb1").style.display="";
		}	
		if(document.getElementById("tb2")){
			document.getElementById("tb2").style.display="";
		}
	}
}

/////////////////////////////////////////////////////////////////////////
//验证被保人姓名
function check_assured_name()
{
	var tname;
	if(document.getElementsByName("assured[0][assured_fullname]"))
	{
		tname = document.getElementsByName("assured[0][assured_fullname]")[0].value.replace(/\ /g,"");
	}
	
	if( tname == "") 
	{
		document.getElementById("assured_fullname_warn").className = "wrong_strong";
		document.getElementById("assured_fullname_warn").innerHTML = "请填写投保人姓名";
		//window.setTimeout("document.getElementById('assured_fullname').focus();", 0)  ; 
		return false;
	}
	else
	{
		//var assured_fullname = tname;//$j("input#assured_fullname").val();
	   	if(strlen(tname)>24||strlen(tname)<3)
	    {
	    	//alert("投保人姓名不合规，应该大于3个且少于24个字符!");
			document.getElementById("assured_fullname_warn").className = "wrong_strong";
			document.getElementById("assured_fullname_warn").innerHTML = "投保人姓名不合规，应该大于3个且少于24个字符!";
	    	//window.setTimeout("document.getElementById('assured_fullname').focus();", 0)  ;  
	    	return false;
	    }
		
		if(document.getElementById("assured_fullname2"))
		{
			document.getElementById("assured_fullname2").value = tname;
		}
		
		document.getElementById("assured_fullname_warn").className = "right_strong";
		document.getElementById("assured_fullname_warn").innerHTML= "";
	}
	
	return true;
}

/*检查被保人证件号*/
function check_assured_card()
{
	
	var bcardtype = document.getElementById("assured_certificates_type").value;
    if(bcardtype == "" && document.getElementById("insCertCpde_warn"))
	{
		//window.setTimeout("document.getElementById('insCertType').focus();", 0)  ;  
		document.getElementById("assured_certificates_type_warn").className = "wrong_strong";
		document.getElementById("assured_certificates_type_warn").innerHTML = "请选择证件类型";
		//window.setTimeout("document.getElementById('insCertType').focus();", 0)  ;  
		return false;
	}
	
	
	
	var idcard = document.getElementById("assured_certificates_code").value;
	if(idcard == "")
	{
		if(document.getElementById("assured_certificates_code_warn")){
			document.getElementById("assured_certificates_code_warn").className="wrong_strong";
			document.getElementById("assured_certificates_code_warn").innerHTML = "请填写证件号码";
			//window.setTimeout("document.getElementById('insCertCpde').focus();", 0)  ;  
		}
		return false;
	}
	else
	{
		var retStr = "";
		if(bcardtype == "01" || bcardtype == "1")
		{//证件类型是身份证
			var errorNo = isIdCardNo(idcard);
			if(errorNo == 0)
			{
				document.getElementById("assured_certificates_code_warn").className="right_strong";
				document.getElementById("assured_certificates_code_warn").innerHTML= "";
				
				return true;
			}
			else
			{
				retStr = errorIdcardMessage(errorNo);
				document.getElementById("assured_certificates_code_warn").className="wrong_strong";
				document.getElementById("assured_certificates_code_warn").innerHTML = retStr;
				//window.setTimeout("document.getElementById('insCertCpde').focus();", 0)  ;  
				return false;
			}
			
			return false;
		}
		
		document.getElementById("assured_certificates_code_warn").className="right_strong";
		document.getElementById("assured_certificates_code_warn").innerHTML= "";
		
	}
	
	return true;
	
}

//验证被保人出生日期
function check_assured_birthday(bstartage,bendage){
	
	//alert("bstartage: "+bstartage);
	//alert("bendage: "+bendage);
	
	//var bbirthday = document.getElementById("insBirthday").value;
	var bbirthday = document.getElementById("assured_birthday").value;
	if(bbirthday == "")
	{
		document.getElementById("assured_birthday_warn").className = "wrong_strong";
		document.getElementById("assured_birthday_warn").innerHTML = "请填写出生日期";
		//window.setTimeout("document.getElementById('assured_birthday').focus();", 0)  ;
		return false;
	}
	else
	{
		//alert("before getAgeByBirthday");
		var bage = getAgeByBirthday(bbirthday);
		//alert("after getAgeByBirthday: "+bage);
		if(bage == -1)
		{
			document.getElementById("assured_birthday_warn").className = "wrong_strong";
			document.getElementById("assured_birthday_warn").innerHTML = "被保险人生日格式错误,例子(2010-01-02)";
			//window.setTimeout("document.getElementById('assured_birthday').focus();", 0);
			
			return false;
		}
		
		//alert("before getAgeByBirthday");
		
		var year = bbirthday.substr(0,4);
		var moth = bbirthday.substr(5,2);
		var day = bbirthday.substr(8,2);
		var ageDate = new Date(year,moth-1,day);
    	var age_d = dateDiff('D',new Date(),ageDate);
    	
    	
    	var sAge = bstartage.substr(0,bstartage.length-1);
    	var eAge = bendage.substr(0,bendage.length-1);
    	
    	//alert("sAge:"+sAge);
    	//alert("eAge:"+eAge);
    	
    	if(bstartage.indexOf('d') > 0)
    	{
    		alert("after d");
    		
    		if(age_d<sAge)
    		{
    			//alert("before 3333");
    			if(document.getElementById("assured_birthday_warn"))
    			{
    				//$("#assured_birthday_warn").show();
    				document.getElementById("assured_birthday_warn").className = "wrong_strong";
    				document.getElementById("assured_birthday_warn").innerHTML = "被保人年龄必须大于"+sAge+"天";
    				//document.getElementById("assured_birthday").value="";
    				//window.setTimeout("document.getElementById('assured_birthday').focus();", 0)  ;
    			}
    			if(document.getElementById("assured_birthday_warn"))
    			{
    				//$("#insAge_warn").show();
    				document.getElementById("assured_birthday_warn").className = "wrong_strong";
    				document.getElementById("assured_birthday_warn").innerHTML = "被保人年龄必须大于"+sAge+"天";
    				//document.getElementById("assured_birthday").value="";
    				//window.setTimeout("document.getElementById('assured_birthday').focus();", 0)  ;
    			}
    			
				return false;
    		}
    		else if(bage>eAge)
    		{
    			//alert("before 4444");
    			if(document.getElementById("assured_birthday_warn"))
    			{
    				//$("#assured_birthday_warn").show();
    				document.getElementById("assured_birthday_warn").className = "wrong_strong";
    				document.getElementById("assured_birthday_warn").innerHTML = "被保人年龄必须小于或等于"+eAge+"周岁";
    				//document.getElementById("assured_birthday").value="";
    				//window.setTimeout("document.getElementById('assured_birthday').focus();", 0)  ;
    			}
    			
    			//alert("before 5555");
    			if(document.getElementById("assured_birthday_warn"))
    			{
    				//$("#insAge_warn").show();
    				document.getElementById("assured_birthday_warn").className = "wrong_strong";
    				document.getElementById("assured_birthday_warn").innerHTML = "被保人年龄必须小于或等于"+eAge+"周岁";
    				//document.getElementById("assured_birthday").value="";
    				//window.setTimeout("document.getElementById('assured_birthday').focus();", 0)  ;
    			}
    			
				return false;
    		}
    	}//end d
    	else if(bstartage.indexOf('y') > 0)
    	{
    		//alert("before Y,sAge: "+sAge);
    		//alert("before Y,eAge: "+eAge);
    		
    		//alert("before Y,bage : "+bage);
    		
    		
    		if(bage < sAge || bage>eAge)
    		{
    			//$("#assured_birthday_warn").show();
				document.getElementById("assured_birthday_warn").className = "wrong_strong";
				document.getElementById("assured_birthday_warn").innerHTML = "被保人年龄必须在"+sAge+"-"+eAge+"岁之间";
				//document.getElementById("assured_birthday").value="";
				//window.setTimeout("document.getElementById('assured_birthday').focus();", 0)  ; 
				return false;
			}
    	}
    	else
    	{
    		//alert("other");
    		
    		if(document.getElementsByName("assured_age"))
    		{
    			document.getElementsByName("assured_age")[0].value = bage;
    		}
    		
    		//$("#assured_birthday_warn").show();
			document.getElementById("assured_birthday_warn").className = "right_strong";
			document.getElementById("assured_birthday_warn").innerHTML= "";
		}
	}
	
	if(document.getElementsByName("assured_age"))
	{
		document.getElementsByName("assured_age")[0].value = bage;
	}
	
	//$("#assured_birthday_warn").show();
	document.getElementById("assured_birthday_warn").className = "right_strong";
	document.getElementById("assured_birthday_warn").innerHTML= "";
	
	
	return true;
}


function check_assured_sex()
{	
	if(document.getElementsByName("assured[0][assured_sex]")[0])
	{
		tsex1 = document.getElementsByName("assured[0][assured_sex]")[0].checked;//投保人性别
	}
	
	if(document.getElementsByName("assured[0][assured_sex]")[1])
	{
		tsex2 = document.getElementsByName("assured[0][assured_sex]")[1].checked;//投保人性别
	}
    
    if(tsex1==false && tsex2==false)
    {
		//window.setTimeout("document.getElementsByName('insSex')[0].focus();", 0)  ;  
		document.getElementById("assured_sex_warn").className = "wrong_strong";
		document.getElementById("assured_sex_warn").innerHTML = "请选择性别";
		return false;
	}
    
    return true;
    	
}



//检查被保险人手机号
function check_assured_mobile_phone()
{

	if(isNull(document.getElementsByName("assured[0][assured_mobilephone]")[0].value)) 
	{
		document.getElementById("assured_mobilephone_warn").className = "wrong_strong";
		document.getElementById("assured_mobilephone_warn").innerHTML = "请填写手机号码"; 
		//window.setTimeout("document.getElementsByName('assured_mobilephone')[0].focus();", 1)  ;  
		return false;
	}
	else if(!isMobile(document.getElementsByName("assured[0][assured_mobilephone]")[0].value,true)) 
	{
		document.getElementById("assured_mobilephone_warn").className = "wrong_strong";
		document.getElementById("assured_mobilephone_warn").innerHTML = "请填写正确的手机号";
		//window.setTimeout("document.getElementsByName('assured_mobilephone')[0].focus();", 1)  ;  
		return false;
	}
	else
	{
		document.getElementById("assured_mobilephone_warn").className = "right_strong";
		document.getElementById("assured_mobilephone_warn").innerHTML= "";
	}
	
	return true;
}


//检查注册手机号(活动使用)
function check_applicant_mobilephone_temp()
{

    if(isNull(document.getElementById("applicant_mobilephone_temps").value))
    {
        document.getElementById("applicant_mobilephone_temps_warn").className = "wrong_strong";
        document.getElementById("applicant_mobilephone_temps_warn").innerHTML = "请填写手机号码";
        //window.setTimeout("document.getElementsByName('assured_mobilephone')[0].focus();", 1)  ;
        return false;
    }
    else if(!isMobile(document.getElementById("applicant_mobilephone_temps").value,true))
    {
        document.getElementById("applicant_mobilephone_temps_warn").className = "wrong_strong";
        document.getElementById("applicant_mobilephone_temps_warn").innerHTML = "请填写正确的手机号";
        //window.setTimeout("document.getElementsByName('assured_mobilephone')[0].focus();", 1)  ;
        return false;
    }
    else
    {
        document.getElementById("applicant_mobilephone_temps_warn").className = "right_strong";
        document.getElementById("applicant_mobilephone_temps_warn").innerHTML= "";
    }

    return true;
}

//检查被保险人电子邮箱
function check_assured_email()
{

	if(document.getElementsByName("assured[0][assured_email]")[0].value == "") 
	{
		document.getElementById("assured_email_warn").className = "wrong_strong";
		document.getElementById("assured_email_warn").innerHTML = "请填写电子邮箱";
		//window.setTimeout("document.getElementsByName('insEmail')[0].focus();", 1)  ;  
		return false;
	}
	else if(!isEmail(document.getElementsByName("assured[0][assured_email]")[0].value, true)) 
	{
		document.getElementById("assured_email_warn").className = "wrong_strong";
		document.getElementById("assured_email_warn").innerHTML = "请填写正确的电子邮箱";
		//window.setTimeout("document.getElementsByName('insEmail')[0].focus();", 1)  ;  
		return false;
	}
	else
	{
		document.getElementById("assured_email_warn").className = "right_strong";
		document.getElementById("assured_email_warn").innerHTML= "";
	}
	
	return true;
}

//验证机构名称  必须加载jquery
function checkgroupname(){
	if(!$j.trim($j("#applicant_group_name").val())){
		
	    $j('#applicant_groupname_warn').show().html('投保机构名称没填写！');
		//window.setTimeout("document.getElementById('applicant_group_name').focus();", 0)  ;  
        return false;
	}else{
		$j('#applicant_groupname_warn').html('').hide();	
		return true;
	}
}


/*验证不为空  适用于所有的不为空验证   新增@liuhui add 20150109
 *@tag_id 标签id 
*/
function checkAllEditTag_Null(tag_id){
	var tag_type = $('#'+tag_id).attr("type");
	var tagName = $('#'+tag_id).get(0).tagName;
	var tag_value = $.trim($('#'+tag_id).val());
	if(tag_value==""){
	    $j('#'+tag_id+'_warn').show().html('请填写正确的内容！');
		 
        return false;
	}else{
		$j('#'+tag_id+'_warn').html('').hide();	
		return true;
	}
}

//验证英文姓名
function english_name(str_cid){
	var reg_s = /^([A-Za-z]+\s?)*[A-Za-z]$/;
	var str_value = $('#'+str_cid).val();
	if(reg_s.test(str_value)){
		document.getElementById(str_cid+"_warn").className = "right_strong";
		document.getElementById(str_cid+"_warn").innerHTML= "";
		return true;	
		
	}else{
		document.getElementById(str_cid+"_warn").className = "wrong_strong";
		document.getElementById(str_cid+"_warn").innerHTML= "英文姓名：Tom Jst";
		return false;	
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
