//验证投保人姓名
function check_applicant_name()
{	
	var tname;
	if(document.getElementsByName("applicant_fullname"))
	{
		tname = document.getElementsByName("applicant_fullname")[0].value.replace(/\ /g,"");
	}
	
	if( tname == "") 
	{
		document.getElementById("applicant_fullname_warn").className = "wrong_strong";
		document.getElementById("applicant_fullname_warn").innerHTML = "请填写投保人姓名";
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
			document.getElementById("applicant_fullname_warn").innerHTML = "投保人姓名不合规，应该大于3个且少于24个字符!";
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
	
	return true;
}


//检查投保人证件号  增加组织机构代码的验证  增加机构验证
function check_applicant_card(code_int,str_grp)
{
	var str_grp_temp = '';
	if(str_grp){
		str_grp_temp = str_grp;
	}
	var tcardtype = document.getElementById("applicant"+str_grp_temp+"_certificates_type").value;
	
	if(code_int=='120011'){
		tcardtype = '120011';
	}
    if(tcardtype == "")
    {
		 
		document.getElementById("applicant"+str_grp_temp+"_certificates_type_warn").className = "wrong_strong";
		document.getElementById("applicant"+str_grp_temp+"_certificates_type_warn").innerHTML = "选择填写证件类型";
		//window.setTimeout("document.getElementById('certType').focus();", 0)  ; 
		
		return false;
	}	
	
	//if(document.getElementById("certCode"))
	//	tcardcode = document.getElementById("certCode").value;//投保人证件号
	
	var idcard = document.getElementById("applicant"+str_grp_temp+"_certificates_code").value;
	
	if(idcard == "")
	{
		document.getElementById("applicant"+str_grp_temp+"_certificates_code_warn").className="wrong_strong";
		document.getElementById("applicant"+str_grp_temp+"_certificates_code_warn").innerHTML = "请填写证件号码";
		//window.setTimeout("document.getElementById('certType').focus();", 0)  ; 
		return false;
	}
	else
	{
		var retStr = "";
		if(tcardtype == "120001" || tcardtype == "01" || tcardtype == "1")
		{//证件类型是身份证
			var errorNo = isIdCardNo(idcard);
			if(errorNo == 0)//ok
			{
				//$("#tcard_warn").show();
				document.getElementById("applicant"+str_grp_temp+"_certificates_code_warn").className="right_strong";
				document.getElementById("applicant"+str_grp_temp+"_certificates_code_warn").innerHTML = "";
				//window.setTimeout("document.getElementById('certCode').focus();", 0)  ; 
				
				//alert("sdfsdf");
				return true;
			}
			else
			{
				retStr = errorIdcardMessage(errorNo);
				//$("#applicant_certificates_code_warn").show();
				document.getElementById("applicant"+str_grp_temp+"_certificates_code_warn").className="wrong_strong";
				document.getElementById("applicant"+str_grp_temp+"_certificates_code_warn").innerHTML = retStr;
				//window.setTimeout("document.getElementById('certCode').focus();", 0)  ; 
				return false;
			}
			
		}else if(tcardtype == "120011"){
            var errorNo_or = isOrganizationCodeNo(idcard);
            if(errorNo_or){
                document.getElementById("applicant"+str_grp_temp+"_certificates_code_warn").className="right_strong";
                document.getElementById("applicant"+str_grp_temp+"_certificates_code_warn").innerHTML = "";
                return true;

            }else{
                document.getElementById("applicant"+str_grp_temp+"_certificates_code_warn").className="wrong_strong";
                document.getElementById("applicant"+str_grp_temp+"_certificates_code_warn").innerHTML = "格式为xxxxxxxx-x 或 xxxxxxxxx";
                return false;
            }
        }
		
		document.getElementById("applicant"+str_grp_temp+"_certificates_code_warn").className="right_strong";
		document.getElementById("applicant"+str_grp_temp+"_certificates_code_warn").innerHTML= "";
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
			document.getElementById("applicant_birthday_warn").className = "right_strong";
			document.getElementById("applicant_birthday_warn").innerHTML= "";
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


//检查投保人手机号
function check_applicant_mobilephone(str_id)
{
	var str_cssidT = 'applicant_mobilephone';
	if(str_id){
		str_cssidT = str_id;
	}
	if(isNull(document.getElementsByName(str_cssidT)[0].value)) 
	{
		document.getElementById(str_cssidT+"_warn").className = "wrong_strong";
		document.getElementById(str_cssidT+"_warn").innerHTML = "请填写手机号码"; 
		//window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0);
		
		return false;
	}
	else if(!isMobile(document.getElementsByName(str_cssidT)[0].value,true)) 
	{
		document.getElementById(str_cssidT+"_warn").className = "wrong_strong";
		document.getElementById(str_cssidT+"_warn").innerHTML = "请填写正确的手机号";
		//window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0);
		return false;
	}
	else
	{
		document.getElementById(str_cssidT+"_warn").className = "right_strong";
		document.getElementById(str_cssidT+"_warn").innerHTML= "";
	}

	return true;
}

//检查投保人电子邮箱
function check_applicant_email(str_cssid)
{
	var str_cssid_T = 'applicant_email';
	if(str_cssid){
		str_cssid_T = str_cssid;
	}
	if(document.getElementsByName(str_cssid_T)[0].value == "") 
	{
		document.getElementById(str_cssid_T+"_warn").className = "wrong_strong";
		document.getElementById(str_cssid_T+"_warn").innerHTML = "请填写电子邮箱";
		//window.setTimeout("document.getElementById('applicant_email').focus();", 0);
		return false;
	}
	else if(!isEmail(document.getElementsByName(str_cssid_T)[0].value, true)) 
	{
		document.getElementById(str_cssid_T+"_warn").className = "wrong_strong";
		document.getElementById(str_cssid_T+"_warn").innerHTML = "请填写正确的电子邮箱";
		//window.setTimeout("document.getElementById('applicant_email').focus();", 0);
		return false;
	}
	else
	{
		document.getElementById(str_cssid_T+"_warn").className = "right_strong";
		document.getElementById(str_cssid_T+"_warn").innerHTML= "";
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
		document.getElementById("assured_fullname_warn").innerHTML = "请填写被保险人姓名";
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
			document.getElementById("assured_fullname_warn").innerHTML = "被保险人姓名不合规，应该大于3个且少于24个字符!";
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
		if(bcardtype == "01" || bcardtype=="120001")
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
	 
	if(bbirthday == "" )
	{
		document.getElementById("assured_birthday_warn").className = "wrong_strong";
		document.getElementById("assured_birthday_warn").innerHTML = "请填写出生日期";
		//window.setTimeout("document.getElementById('assured_birthday').focus();", 0)  ;
		return false;
	}
	else
	{
		//alert("before getAgeByBirthday");
		var bage = getAgeByBirthday(bbirthday,'assured_birthday');
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
			
    		if($('#cp_name').val()=='huaan_xueping'){
				if(eAge==18 || eAge==6){
					eAge = eAge-1;
				}
			}
    		if(bage < sAge || bage>eAge)
    		{ 
				if($('#cp_name').val()=='huaan_xueping'){
					if(eAge==17 || eAge==5){
						eAge = eAge+1;
						
						document.getElementById("assured_birthday_warn").className = "wrong_strong";
						document.getElementById("assured_birthday_warn").innerHTML = "被保人年龄必须在"+sAge+"(含)-"+eAge+"岁之间";
					}else if(eAge==30){
						document.getElementById("assured_birthday_warn").className = "wrong_strong";
						document.getElementById("assured_birthday_warn").innerHTML = "被保人年龄必须在"+sAge+"(含)-"+eAge+"(含)岁之间";
					}
				}else{
					document.getElementById("assured_birthday_warn").className = "wrong_strong";
					document.getElementById("assured_birthday_warn").innerHTML = "被保人年龄必须在"+sAge+"-"+eAge+"岁之间";
				}
    			//$("#assured_birthday_warn").show();
				
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

//验证不能为空
function check_applicant_null(strT){
	var temp_value = $.trim($('#'+strT).val());
	if(temp_value==""){
		document.getElementById(strT+"_warn").className = "wrong_strong";
		document.getElementById(strT+"_warn").innerHTML = "不能为空";	
		return false;
	}else{
		if(strT=='project_price'){
			var zaojia_p  = $('#cost_limit').val();
			 var re = /^(([1-9][0-9]*\.[0-9][0-9]*)|([0]\.[0-9][0-9]*)|([1-9][0-9]*)|([0]{1}))$/;   
			if(zaojia_p!=""&&re.test(temp_value)){
				var zaojia_pTemp = zaojia_p.split(',');
				 
				if((parseInt(zaojia_pTemp[0])*10000)>parseFloat(temp_value)||(parseInt(zaojia_pTemp[1])*10000)<=parseFloat(temp_value)){
					document.getElementById(strT+"_warn").className = "wrong_strong";
					document.getElementById(strT+"_warn").innerHTML = "范围："+zaojia_pTemp[0]+'万元<=X<'+zaojia_pTemp[1]+'万元';	
					return false;	
				}else{
					document.getElementById(strT+"_warn").innerHTML = "";
					return true;
				}
			}else{
				document.getElementById(strT+"_warn").className = "wrong_strong";
				document.getElementById(strT+"_warn").innerHTML = "填写数字不正确";	
				return false;	
			}
 
		}else{
			document.getElementById(strT+"_warn").innerHTML = "";
			return true;
		}
		
	}
	
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

//验证机构名称  必须加载jquery  此方法是小建工执行
function checkgroupname(){
	if(!$j.trim($j("#applicant_fullname").val())){
		
	    $j('#applicant_fullname_warn').show().html('投保机构名称没填写！');
		//window.setTimeout("document.getElementById('applicant_group_name').focus();", 0)  ;  
        return false;
	}else{
		$j('#applicant_fullname_warn').html('').hide();	
		return true;
	}
}

//验证机构名称  必须加载jquery
function checkgroupname_02(){
	if(!$j.trim($j("#applicant_group_name").val())){
		
	    $j('#applicant_group_name_warn').show().html('投保机构名称没填写！');
		//window.setTimeout("document.getElementById('applicant_group_name').focus();", 0)  ;  
        return false;
	}else{
		$j('#applicant_group_name_warn').html('').hide();	
		return true;
	}
}
  //验证固定电话（只有机构才有）
  function checktelephone(telID){
    var str_val = $('#'+telID).val();
    var reg_text = /^((0\d{2,3})-)(\d{7,8})$/;
    if(reg_text.test(str_val)){
        $('#'+telID+'_warn').html('').hide();
        return true;
    }else{
        $('#'+telID+'_warn').addClass("wrong_strong");
        $('#'+telID+'_warn').html(' 格式：010-12345678').show();
        return false;
    }
  }

//增加施工方人员信息行  每次一行
function add_assured_info(){
	var html_assured_html = '';
	html_assured_html = $('#assured_info_demo tr').clone();	
	if($('#list_assured_list tr').length<=3){
		$('#list_assured_list').append(html_assured_html);

	}else{
		layer.alert('关系方最多填写三条！',9,'温馨提示');	
		$('.xubox_layer').css('top',"200px");
	} 

}

//删除施工方行信息
function deletecelpan(ef){
	$(ef).parent().parent().remove();
}


function checked_assuredAllPan(){
	var flg = checked_assuredAll();
	if(flg){
		layer.alert('验证通过!',9,'温馨提示');
		$('.xubox_layer').css('top',"200px");
	}else{
		layer.alert('验证失败,失败原因：<br>1.名称,手机号,证件号码不能为空;<br>2.证件号码：身份证15,18位;<br>机构代码格式：88829888-0或者12345678A<br>3.手机,证件号码有误;<br>4."是否被保险人"此列必须有一个是被保险人<br>修改完成再次检验!',9,'温馨提示');
		$('.xubox_layer').css('top',"200px");
	}
}

//验证施工方填写信息
function checked_assuredAll(){
	var flag = true;
	var json_temp = [];
	var as_ed = 0;
	if($('#list_assured_list tr.list_info').length>0){
		$('#list_assured_list tr.list_info').each(function(i_s,n_s) {
			var json_celspan = {};
			$(n_s).children().each(function(i_td, n_td) {
				var data_title = $(n_td).data('title');
				 
				if(data_title){
					json_celspan[data_title] = $(n_td).children().val();
					if(data_title=='fullname'){
						var value_tempT = $(n_td).children().val();
						if($.trim(value_tempT)==""){
							flag = false;
						}
						
					}else if(data_title=='assured_mobilephone'){
						var assured_mobilephone_tempT = $(n_td).children().val();
						if(isNull(assured_mobilephone_tempT) || !isMobile(assured_mobilephone_tempT)){
							flag = false;
						}
						 
					
					}else if(data_title=='certificates_type'){
						var certificates_code = $(n_td).next("td").children().val();
						if($(n_td).children().val()=='120001'){
							if(isIdCardNo(certificates_code)!=0){
								flag = false;
							}
						}else if($(n_td).children().val()=='120011'){
                            if(!isOrganizationCodeNo(certificates_code)){
                                flag = false;
                            }
                        }else{
							if($.trim(certificates_code)==""){
								flag = false;
							}	
						}
					}else if(data_title=='be_assured'){
						if($(n_td).children().val()==1)	{
							as_ed = 1;
						}
					}
						
				}
			});
			json_temp.push(json_celspan); 
		});	
	}else{
		flag = false;
	}
	if(as_ed==0){
		flag = false;	
	}
	$('#assured_info').val(JSON.stringify(json_temp));
	return flag;
	
}



