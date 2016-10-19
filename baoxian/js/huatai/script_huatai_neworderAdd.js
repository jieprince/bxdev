var jobCheck;
var insAgeCheck;


//常用被保人带入
function addAppnt(appntId){
	$.ajax({
		url: CONTEXT+"/fg/user/commonlyAppntSearch.do?metType=edit&appntId="+appntId,
		cache: false,
		async:false,
		success: function(data){
			var appnt = data.split(",");
			if(document.getElementsByName("appName")[0]){
				document.getElementsByName("appName")[0].value=appnt[0];
				document.getElementById("appName_warn").className="right_strong";
				document.getElementById("appName_warn").innerHTML= "";
				document.getElementById("appName_warn").style.display="";
			}
			if(document.getElementsByName("einsureName")[0]){
				document.getElementsByName("einsureName")[0].value=appnt[1];
			}
			if(document.getElementsByName("certType")[0]){
				document.getElementsByName("certType")[0].value=appnt[2];
			}
			if(document.getElementsByName("certCode")[0]){
				document.getElementsByName("certCode")[0].value=appnt[3];
				document.getElementById("tcard_warn").className="right_strong";
				document.getElementById("tcard_warn").innerHTML= "";
				document.getElementById("tcard_warn").style.display="";
			}
			if(document.getElementsByName("appSex")[0]&&document.getElementsByName("appSex")[1]){
				if(appnt[4]=="1"){
					 document.getElementsByName("appSex")[0].checked=true;
				  	 document.getElementsByName("appSex")[1].disabled=false;
				  	 document.getElementsByName("appSex")[0].disabled=false;
				  	 document.getElementById("appSex_warn").className="right_strong";
					 document.getElementById("appSex_warn").innerHTML= "";
					 document.getElementById("appSex_warn").style.display="";
				}else if(appnt[4]=="0"){
					 document.getElementsByName("appSex")[1].checked=true;
				  	 document.getElementsByName("appSex")[0].disabled=false;
				  	 document.getElementsByName("appSex")[1].disabled=false;
				  	 document.getElementById("appSex_warn").className="right_strong";
					 document.getElementById("appSex_warn").innerHTML= "";
					 document.getElementById("appSex_warn").style.display="";
				}
			}
			if(document.getElementsByName("appBirthday")[0]){
				document.getElementsByName("appBirthday")[0].value=appnt[5];
				var appAge = getAgeByBirthday(appnt[5]);
				if(document.getElementsByName("appAge")[0]){
					document.getElementsByName("appAge")[0].value = appAge;
				}
				document.getElementById("appBirthday_warn").className="right_strong";
				document.getElementById("appBirthday_warn").innerHTML= "";
				document.getElementById("appBirthday_warn").style.display="";
			}
			if(document.getElementsByName("appCell")[0]){
				document.getElementsByName("appCell")[0].value=appnt[6];
				document.getElementById("appCell_warn").className="right_strong";
				document.getElementById("appCell_warn").innerHTML= "";
				document.getElementById("appCell_warn").style.display="";
			}
			if(document.getElementsByName("appEmail")[0]){
				document.getElementsByName("appEmail")[0].value=appnt[7];
				document.getElementById("appEmail_warn").className="right_strong";
				document.getElementById("appEmail_warn").innerHTML= "";
				document.getElementById("appEmail_warn").style.display="";
			}
			
			
			
			document.getElementById("appntAdd").style.display = "none";
			document.getElementById("appntEdit").style.display = "";
			document.getElementById("appntId").value=appntId;
			document.getElementById("appntType").value = "update";
			checkappName();
		}
	});

}

//选择保存或修改常用投保人
function checkAppnt(appntType)
{
	if(appntType.checked)
	{
		document.getElementById("ischecked").value = "true";
	}
	else
	{
		document.getElementById("ischecked").value = "false";
	}
}

//清空投保人填写的信息
function clearAppnt()
{
			if(document.getElementsByName("appName")[0])
	{
		document.getElementsByName("appName")[0].value="";
	}
	
	if(document.getElementsByName("einsureName")[0]){
		document.getElementsByName("einsureName")[0].value="";
	}
	
	
	if(document.getElementsByName("certType")[0]){
		document.getElementsByName("certType")[0].value="";
	}
	if(document.getElementsByName("certCode")[0]){
		document.getElementsByName("certCode")[0].value="";
	}
	if(document.getElementsByName("appSex")[0]&&document.getElementsByName("appSex")[1]){
			 document.getElementsByName("appSex")[1].disabled=false;
		  	 document.getElementsByName("appSex")[0].disabled=false;
		  	  document.getElementsByName("appSex")[0].checked=false;
		  	   document.getElementsByName("appSex")[1].checked=false;
	}
	if(document.getElementsByName("appBirthday")[0]){
		document.getElementsByName("appBirthday")[0].value="";
		if(document.getElementsByName("appAge")[0]){
			document.getElementsByName("appAge")[0].value = "";
		}
	}
	if(document.getElementsByName("appCell")[0]){
		document.getElementsByName("appCell")[0].value="";
	}
	if(document.getElementsByName("appEmail")[0]){
		document.getElementsByName("appEmail")[0].value="";
	}
	document.getElementById("appntAdd").style.display = "";
	document.getElementById("appntEdit").style.display = "none";
	document.getElementById("appntId").value="";;
	document.getElementById("appntType").checked = false;
	document.getElementById("appntType").value = "save";
	document.getElementById("appName2").value = "";
	var x=document.getElementsByName("appntId");  //获取所有name=brand的元素
	for(var i=0;i<x.length;i++){ //对所有结果进行遍历，如果状态是被选中的，则将其选择取消
		if (x[i].checked)
		{
			x[i].checked=false;
		}
	}
}
//***************************分割线*****************************************

/*---------1.公用验证的方法------------------ */

/*
//显示提示信息
function showwarn(warnid,op){
	document.getElementById(warnid).style.display="";
}
*/

//根据身份证得到相应的人的生日、年龄、性别
function getBirthDay(obj,cardtype,pbirthday,psex,page,bstartage,bendage)
{
  var testSex="13579";//基数为男性
	var cardType=document.getElementsByName(cardtype)[0].value;
	var idNumber=obj.value;
	
	//alert("cardtype: "+cardtype);
	
  if(cardType!="" && "1"==cardType || "3"==cardType)
  {
	  
    if(idNumber.length==18 || "1"!=cardType)
    {
    	/*计算出生年月日*/
    	var date=""; //19790301
    	var year="";
    	var moth="";
    	var day="";
    	
    	if("1"==cardType)//身份证
    	{
    		date=idNumber.substring(6,14); //19790301
        	year=date.substring(0,4);
        	moth=date.substring(4,6);
        	day=date.substring(6,date.length);
        	var birthday=year + "-" + moth + "-" + day;
        	document.getElementsByName(pbirthday)[0].value=birthday; //出生年月日
    	}
    	else
    	{
    		date = idNumber;
    		year=date.substring(0,4);
        	moth=date.substring(5,7);
        	day=date.substring(8,date.length);
    	}
    	
    	if("1"==cardType)
    	{
	    	/*计算性别*/
		    if(testSex.indexOf(idNumber.substring(idNumber.length-2,idNumber.length-1))!=-1)
		    {
		    	if(document.getElementsByName(psex)[0])
		    	{
			    	document.getElementsByName(psex)[0].disabled=false;
			    	document.getElementsByName(psex)[0].checked=true;
			    	document.getElementsByName(psex)[1].disabled=true;
		    	}
		  	}
		    else
		    {
		  		if(document.getElementsByName(psex)[0])
		  		{
			  		document.getElementsByName(psex)[1].disabled=false;
			  		document.getElementsByName(psex)[1].checked=true;
			  		document.getElementsByName(psex)[0].disabled=true;
		  		}
		  	}
	    }
	    /*计算年龄*/
	    var nowdate = new Date();// 取得当前日期
	    var nowyear = nowdate.getFullYear();// 取得当前年份
	    var nowmonth = nowdate.getMonth()+1;// 取得当前月份
	    var nowday = nowdate.getDate();
	    var nowage;
	    if (nowmonth >= parseInt(moth))
	    { //判断当前月分与编码中的月份大小
	    	if(nowday<day)
	    	{
	    		nowage = nowyear - parseInt(year) - 1;
		    	document.getElementsByName(page)[0].value = nowyear - parseInt(year) - 1;
	    	}
	    	else
	    	{
	    		nowage = nowyear - parseInt(year);
	    		document.getElementsByName(page)[0].value = nowyear - parseInt(year);
	    	}
	    
	    }
	    else
	    {
	    	nowage = nowyear - parseInt(year) - 1;
	    	document.getElementsByName(page)[0].value = nowyear - parseInt(year) - 1;
	    }
	    
	    if(pbirthday == 'appBirthday')
	    {
			if(nowage < 18)
			{
				document.getElementById("appBirthday_warn").className = "wrong_strong";
				document.getElementById("appBirthday_warn").innerHTML = "投保人必须大于18岁";
				return false
			}
			else
			{
				document.getElementById("appBirthday_warn").className = "right_strong";
				document.getElementById("appBirthday_warn").innerHTML= "";
			}    	
			
			var ageDate = new Date(year,moth,day);
	    	var age_d = dateDiff('D',new Date(),ageDate);
	    	var sAge = bstartage.substr(0,bstartage.length-1);
	    	var eAge = bendage.substr(0,bendage.length-1);
	    	if(bstartage.indexOf('d') > 0)
	    	{
	    		if(age_d<sAge)
	    		{
	    			if("1"==cardType)
	    			{
		    			if(document.getElementById("certCode")){
		    				document.getElementById("tcard_warn").className = "wrong_strong";
		    				document.getElementById("tcard_warn").innerHTML = "投保人年龄必须大于"+sAge+"天";
		    				//document.getElementById("certCode").value="";
		    			}
	    			}
	    			if(document.getElementById("appBirthday"))
	    			{
	    				document.getElementById("appBirthday_warn").className = "wrong_strong";
	    				document.getElementById("appBirthday_warn").innerHTML = "投保人年龄必须大于"+sAge+"天";
	    				document.getElementById("appBirthday").value="";
	    			}
					return false;
	    		}
	    		else if(nowage>eAge)
	    		{
	    			if(eAge>=18)
	    			{
		    			if("1"==cardType)
		    			{
			    			if(document.getElementById("certCode"))
			    			{
			    				document.getElementById("tcard_warn").className = "wrong_strong";
			    				document.getElementById("tcard_warn").innerHTML = "投保人年龄必须小于或等于"+eAge+"周岁";
			    				//document.getElementById("certCode").value="";
			    			}
		    			}
		    			
		    			if(document.getElementById("appBirthday"))
		    			{
		    				document.getElementById("appBirthday_warn").className = "wrong_strong";
		    				document.getElementById("appBirthday_warn").innerHTML = "投保人年龄必须小于或等于"+eAge+"周岁";
		    				document.getElementById("appBirthday").value="";
		    			}
						return false;
	    			}
	    		}
	    	}
	    	else if(bstartage.indexOf('y') > 0)
	    	{
	    		
	    		if(nowage < sAge || nowage>eAge)
	    		{
	    			if(document.getElementById("insBirthday"))
	    			{
						document.getElementById("insBirthday_warn").className = "wrong_strong";
						document.getElementById("insBirthday_warn").innerHTML = "被保人年龄必须在"+sAge+"-"+eAge+"岁之间";
						//document.getElementById("insBirthday").value="";
						window.setTimeout("document.getElementById('insBirthday').focus();", 0)  ; 
	    			}
					if(document.getElementById("appBirthday"))
					{
						document.getElementById("appBirthday_warn").className = "wrong_strong";
						document.getElementById("appBirthday_warn").innerHTML = "投保人年龄必须在"+sAge+"-"+eAge+"岁之间";
					}
					if("1"==cardType)
					{
						if(document.getElementById("certCode"))
						{
							document.getElementById("tcard_warn").className = "wrong_strong";
							document.getElementById("tcard_warn").innerHTML = "投保人年龄必须在"+sAge+"-"+eAge+"岁之间";
						}
					}
					return false;
				}
	    		else
	    		{
					if("1"==cardType)
					{
						if(document.getElementById("certCode"))
						{
							document.getElementById("tcard_warn").innerHTML = "";
						}
					}
					
					if(document.getElementById("appBirthday"))
					{
						document.getElementById("appBirthday_warn").innerHTML = "";
					}
				}
	    	}
	    	else
	    	{
	    		if(document.getElementById("insBirthday_warn"))
	    		{
					document.getElementById("insBirthday_warn").className = "right_span";
					document.getElementById("insBirthday_warn").innerHTML= "";
	    		}
			}
			
	    }
	    
	    if(pbirthday == 'insBirthday')
	    {
	    	
	    	//alert("bstartage: "+bstartage);
	    	//alert("bendage: "+bendage);
	    	
	    	var ageDate = new Date(year,moth,day);
	    	var age_d = dateDiff('D',new Date(),ageDate);
	    	var sAge = bstartage.substr(0,bstartage.length-1);
	    	var eAge = bendage.substr(0,bendage.length-1);
	    	
	    	
	    	//alert("sAge: "+sAge);
	    	//alert("eAge: "+eAge);
	    		    	
	    	
	    	
	    	if(bstartage.indexOf('d') > 0)
	    	{
	    		//alert("age_d: "+age_d);
	    		if(age_d<sAge)
	    		{
	    			if(document.getElementById("insBirthday_warn"))
	    			{
	    				document.getElementById("insBirthday_warn").className = "wrong_strong";
	    				document.getElementById("insBirthday_warn").innerHTML = "被保人年龄必须大于"+sAge+"天";
	    				document.getElementById("insBirthday").value="";
	    			}
	    			if(document.getElementById("insAge_warn"))
	    			{
	    				document.getElementById("insAge_warn").className = "wrong_strong";
	    				document.getElementById("insAge_warn").innerHTML = "被保人年龄必须大于"+sAge+"天";
	    				document.getElementById("insBirthday").value="";
	    			}
	    			
					return false;
	    		}
	    		else if(nowage>eAge)
	    		{
	    			if(document.getElementById("insBirthday_warn"))
	    			{
	    				document.getElementById("insBirthday_warn").className = "wrong_strong";
	    				document.getElementById("insBirthday_warn").innerHTML = "被保人年龄必须小于或等于"+eAge+"周岁";
	    				document.getElementById("insBirthday").value="";
	    			}
	    			if(document.getElementById("insAge_warn"))
	    			{
	    				document.getElementById("insAge_warn").className = "wrong_strong";
	    				document.getElementById("insAge_warn").innerHTML = "被保人年龄必须小于或等于"+eAge+"周岁";
	    				document.getElementById("insBirthday").value="";
	    			}
					return false;
	    		}
	    	}
	    	else if(bstartage.indexOf('y') > 0)
	    	{
	    		//alert("--nowage: "+nowage);
	    		//alert("--sAge: "+sAge);
	    		//alert("--eAge: "+eAge);
	    		if(nowage < sAge || nowage>eAge)
	    		{
	    			//alert("被保险人的年龄不在承包范围内！");
					document.getElementById("insBirthday_warn").className = "wrong_strong";
					document.getElementById("insBirthday_warn").innerHTML = "被保人年龄必须在"+sAge+"-"+eAge+"岁之间";
					$("#insBirthday_warn").show();
					//document.getElementById("insBirthday_warn").style.display="";
					//document.getElementById("insBirthday").value="";
					window.setTimeout("document.getElementById('insBirthday').focus();", 0)  ; 
					return false;
				}
	    		else
	    		{
	    			$("#insBirthday_warn").hide();	
	    		}
	    		
	    	}
	    	else
	    	{
				document.getElementById("insBirthday_warn").className = "right_span";
				document.getElementById("insBirthday_warn").innerHTML= "";
			}
	    }

	    
	}
    else if(idNumber.length==15) 
    {
	
    	/*计算出生年月日*/
    	var date=""; //19790301
    	var year="";
    	var moth="";
    	var day="";
    	
    	if("1"==cardType)
    	{
    		date=idNumber.substring(6,12); //19790301
        	year="19"+date.substring(0,2);
        	moth=date.substring(2,4);
        	day=date.substring(4,date.length);
        	var birthday=year + "-" + moth + "-" + day;
        	document.getElementsByName(pbirthday)[0].value=birthday; //出生年月日
    	}
    	else
    	{
    		date = idNumber;
    		year=date.substring(0,4);
        	moth=date.substring(5,7);
        	day=date.substring(8,date.length);
    	}
    	
    	
    	/*计算性别*/
    	if("1"==cardType)
    	{
	    	if(testSex.indexOf(idNumber.substring(idNumber.length-1,idNumber.length))!=-1)
	    	{
		  	 document.getElementsByName(psex)[0].checked=true;
		  	 document.getElementsByName(psex)[1].disabled=true;
		  	 document.getElementsByName(psex)[0].disabled=false;
		  	}
	    	else
	    	{
		  	 document.getElementsByName(psex)[1].checked=true;
		  	 document.getElementsByName(psex)[0].disabled=true;
		  	 document.getElementsByName(psex)[1].disabled=false;
		  	}
    	}
    	
    	/*计算年龄 */
    	var nowdate = new Date();// 取得当前日期
    	   var nowyear = nowdate.getFullYear();// 取得当前年份
    	   var nowmonth = nowdate.getMonth();// 取得当前月份
    	   var nowage;
    	   if (nowmonth >= parseInt(moth))
    	   {// 判断当前月分与编码中的月份大小
    		   nowage = nowyear - parseInt(year);
    	   }
    	   else
    	   {
    		   nowage = nowyear - parseInt(year) - 1;
    	   }
    	   
	   	   if(nowage < 18)
	   	   {
				document.getElementById("tcard_warn").className = "wrong_strong";
				document.getElementById("tcard_warn").innerHTML = "投保人必须大于18岁";
				return false
		   }
	}
   
  }
  
  return true;
}


//检查手机号码
function checkPhone(data){
	 var name = data.name;
	 if(isNull(document.getElementsByName(name)[0].value)) 
	 {
		 document.getElementById(name+"_warn").className = "wrong_strong";
		 document.getElementById(name+"_warn").innerHTML = "请填写手机号码";
		 return false;
	 }
	 else if(!isMobile(document.getElementsByName(name)[0].value,true)) 
	 {
		 document.getElementById(name+"_warn").className = "wrong_strong";
		 document.getElementById(name+"_warn").innerHTML = "请填写正确的手机号";
		 return false;
	 }
	 else
	 {
		 document.getElementById(name+"_warn").className = "right_strong";
		 document.getElementById(name+"_warn").innerHTML= "";
	 }
	 
	
	 return true;
} 

/*---------2.投保人输入项验证-------------*/

//验证投保人姓名
function checkappName()
{
	var tname;
	if(document.getElementsByName("appName"))
	{
		tname = document.getElementsByName("appName")[0].value.replace(/\ /g,"");
	}
	
	if( tname == "") 
	{
		document.getElementById("appName_warn").className = "wrong_strong";
		document.getElementById("appName_warn").innerHTML = "请填写投保人姓名";
		//window.setTimeout("document.getElementById('appName').focus();", 0)  ; 
		return false;
	}
	else
	{
		

	    var appName = $j("input#appName").val();
	   	if(strlen(appName)>24||strlen(appName)<3)
	    {
	    	//alert("投保人姓名不合规，应该大于3个且少于24个字符!");
			document.getElementById("appName_warn").className = "wrong_strong";
			document.getElementById("appName_warn").innerHTML = "投保人姓名不合规，应该大于3个且少于24个字符!";
	    	//window.setTimeout("document.getElementById('appName').focus();", 0)  ;  
	    	return false;
	    }
		
		if(document.getElementById("appName2"))
		{
			document.getElementById("appName2").value = tname;
		}
		document.getElementById("appName_warn").className = "right_strong";
		document.getElementById("appName_warn").innerHTML= "";
	}
	
	return true;
}


function checkappEnglishName()
{
	if(document.getElementsByName("appEnglishName")[0])
	{
		appEnglishName = document.getElementsByName("appEnglishName")[0].value.replace(/\ /g,"");//投保人姓名
	}

	
	if(document.getElementById("appEnglishName"))
	{
		if(document.getElementById("appEnglishName").value == "")
		{
			document.getElementById("appEnglishName_warn").className = "wrong_strong";
			document.getElementById("appEnglishName_warn").innerHTML = "请填写英文名称";
			//window.setTimeout("document.getElementById('appEnglishName').focus();", 0)  ;  
			return false;
		}
	}
	
    var appEnglishName = $j("input#appEnglishName").val();
   	if(strlen(appEnglishName)<3||strlen(appEnglishName)>24)
    {
    	//alert("投保人英文姓名不要超过30个字符!");
		document.getElementById("appEnglishName_warn").className = "wrong_strong";
		document.getElementById("appEnglishName_warn").innerHTML = "投保人英文姓名应在3-24个字符之间!";
    	//window.setTimeout("document.getElementById('appEnglishName').focus();", 0)  ;  
    	return false;
    }	
	else
	{
		document.getElementById("appEnglishName_warn").className = "right_strong";
		document.getElementById("appEnglishName_warn").innerHTML= "";
	}
   	
   	return true;
}

function checkaddrdetail()
{
	return true;
}

// 选择投保人证件类型 
function choosetcardtype(obj)
{
	var tcardtype = obj.value;
	document.getElementById("certCode").value = "";
	
	if(tcardtype == "")
	{
		document.getElementById("certCode").disabled=true;
		document.getElementById("tcard_warn").className="wrong_strong";
		document.getElementById("tcard_warn").innerHTML = "请选择证件类型";
	  	//window.setTimeout("document.getElementById('certCode').focus();", 0)  ;  

	}
	else
	{
		if(tcardtype == "1")
		{ 
			document.getElementsByName("appSex")[0].disabled=true;
			document.getElementsByName("appSex")[1].disabled=true;
			document.getElementsByName("appSex")[0].checked=false;
			document.getElementsByName("appSex")[1].checked=false;
		}
		else
		{
			document.getElementsByName("appSex")[0].disabled=false;
			document.getElementsByName("appSex")[1].disabled=false;
			document.getElementsByName("appSex")[0].checked=false;
			document.getElementsByName("appSex")[1].checked=false;
		}
	}
	
	return true;
}


//检查投保人证件号
function checktcard()
{
	var tcardtype = document.getElementById("certType").value;
    if(tcardtype == "")
    {
		 
		document.getElementById("certType_warn").className = "wrong_strong";
		document.getElementById("certType_warn").innerHTML = "请填写证件号";
		//window.setTimeout("document.getElementById('certType').focus();", 0)  ; 
		
		return false;
	}	
	
	//if(document.getElementById("certCode"))
	//	tcardcode = document.getElementById("certCode").value;//投保人证件号
	
	var idcard = document.getElementById("certCode").value;
	if(idcard == "")
	{
		document.getElementById("tcard_warn").className="wrong_strong";
		document.getElementById("tcard_warn").innerHTML = "请填写证件号码";
		//window.setTimeout("document.getElementById('certType').focus();", 0)  ; 
		return false;
	}
	else
	{
		var retStr = "";
		if(tcardtype == "1")
		{//证件类型是身份证
			var errorNo = isIdCardNo(idcard);
			if(errorNo == 0)//ok
			{
				//$("#tcard_warn").show();
				document.getElementById("tcard_warn").className="right_strong";
				document.getElementById("tcard_warn").innerHTML= "";
				//window.setTimeout("document.getElementById('certCode').focus();", 0)  ; 
				
				//alert("sdfsdf");
				return true;
			}
			else
			{
				retStr = errorIdcardMessage(errorNo);
				$("#tcard_warn").show();
				document.getElementById("tcard_warn").className="wrong_strong";
				document.getElementById("tcard_warn").innerHTML = retStr;
				//window.setTimeout("document.getElementById('certCode').focus();", 0)  ; 
				return false;
			}
			
		}
		
		//document.getElementById("tcard_warn").className="right_strong";
		//document.getElementById("tcard_warn").innerHTML= "";
	}
	
	return true;
}


function checktsex()
{
	
	if(document.getElementsByName("appSex")[0])
	{
		tsex1 = document.getElementsByName("appSex")[0].checked;//投保人性别
	}
	
	if(document.getElementsByName("appSex")[1])
		tsex2 = document.getElementsByName("appSex")[1].checked;//投保人性别
    
    if(tsex1==false && tsex2==false)
    {
		//window.setTimeout("document.getElementsByName('appSex')[0].focus();", 0)  ;  
		document.getElementById("appSex_warn").className = "wrong_strong";
		document.getElementById("appSex_warn").innerHTML = "请选择";
		return false;
	}
    
    return true;
    	
}


//验证投保人出生日期
function checktbirthday()
{
	var tbirthday = document.getElementById("appBirthday").value;
	if(tbirthday == "")
	{
		document.getElementById("appBirthday_warn").className = "wrong_strong";
		document.getElementById("appBirthday_warn").innerHTML = "请填写出生日期";
		//window.setTimeout("document.getElementById('appBirthday').focus();", 0)  ; 
		return false;
	}
	else
	{
		//alert("before getAgeByBirthday");
		var ttage = getAgeByBirthday(tbirthday);
	
		//document.getElementsByName('appAge')[0].value = tage;
		
		if(ttage==-1)
		{
			document.getElementById("appBirthday").className = "wrong_strong";
			document.getElementById("appBirthday_warn").innerHTML = "投保人生日格式错误,例子(2010-01-02)";
			//window.setTimeout("document.getElementById('appBirthday').focus();", 0);
			
			return false;
		}
		else if(ttage < 18)
		{
			document.getElementById("appBirthday_warn").className = "wrong_strong";
			document.getElementById("appBirthday_warn").innerHTML = "投保人必须大于18岁";
			//window.setTimeout("document.getElementById('appBirthday').focus();", 0);
			return false
		}
		else
		{
			document.getElementById("appBirthday_warn").className = "right_strong";
			document.getElementById("appBirthday_warn").innerHTML= "";
		}
		
		//alert("after getAgeByBirthday, ttage: "+ttage);
	}
	
	return true;
}

//检查投保人手机号
function checktphone()
{
	if(isNull(document.getElementsByName("appCell")[0].value)) 
	{
		document.getElementById("appCell_warn").className = "wrong_strong";
		document.getElementById("appCell_warn").innerHTML = "请填写手机号码"; 
		//window.setTimeout("document.getElementById('appCell').focus();", 0);
		
		return false;
	}
	else if(!isMobile(document.getElementsByName("appCell")[0].value,true)) 
	{
		document.getElementById("appCell_warn").className = "wrong_strong";
		document.getElementById("appCell_warn").innerHTML = "请填写正确的手机号";
		//window.setTimeout("document.getElementById('appCell').focus();", 0);
		return false;
	}
	else
	{
		document.getElementById("appCell_warn").className = "right_strong";
		document.getElementById("appCell_warn").innerHTML= "";
	}

	return true;
}

//检查投保人电子邮箱
function checktemail()
{
	
	if(document.getElementsByName("appEmail")[0].value == "") 
	{
		document.getElementById("appEmail_warn").className = "wrong_strong";
		document.getElementById("appEmail_warn").innerHTML = "请填写电子邮箱";
		//window.setTimeout("document.getElementById('appEmail').focus();", 0);
		return false;
	}
	else if(!isEmail(document.getElementsByName("appEmail")[0].value, true)) 
	{
		document.getElementById("appEmail_warn").className = "wrong_strong";
		document.getElementById("appEmail_warn").innerHTML = "请填写正确的电子邮箱";
		//window.setTimeout("document.getElementById('appEmail').focus();", 0);
		return false;
	}
	else
	{
		document.getElementById("appEmail_warn").className = "right_strong";
		document.getElementById("appEmail_warn").innerHTML= "";
	}


	return true;

}


function check_t_region()
{
	
	//alert("sdfsfsdf");
	var tprov = document.getElementsByName("insureareaprov_code")[0].value;
	var tcity = document.getElementsByName("insureareacity_code")[0].value;
	if(tprov =="" || tcity =="" )
	{
		document.getElementById("insureareaprov_code_warn").className = "wrong_strong";
		document.getElementById("insureareaprov_code_warn").innerHTML = "请选择完整的通讯地址";
		//window.setTimeout("document.getElementsByName('insureareaprov_code')[0].focus();", 0)  ; 
	}
	else
	{
		document.getElementById("insureareaprov_code_warn").className = "right_strong";
		document.getElementById("insureareaprov_code_warn").innerHTML= ""; 
	}
	
	return true;
}


function checka_t_ddrdetail()
{
	var addrdetail = document.getElementsByName("appAddress")[0].value;
	if(addrdetail == "")
	{
		document.getElementById("appAddress_warn").className="wrong_strong";
		document.getElementById("appAddress_warn").innerHTML = "请填写详细地址";
		//window.setTimeout("document.getElementsByName('appAddress')[0].focus();", 0)  ;  
		return false;
	}
	else
	{
		document.getElementById("appAddress_warn").className="right_strong";
		document.getElementById("appAddress_warn").innerHTML= "";
	}
	
	return true;
}


function check_t_postcode()
{    
   
    if(document.getElementsByName("appPostcode")[0] && document.getElementsByName("appPostcode")[0].value == "") 
    {
		document.getElementById("appPostcode_warn").className = "wrong_strong";
		document.getElementById("appPostcode_warn").innerHTML = "请填写邮政编码";
		//window.setTimeout("document.getElementsByName('appPostcode')[0].focus();", 1)  ;  
		return false;
	}
    
    if(document.getElementsByName("appPostcode")[0] && !isChinaCode(document.getElementsByName("appPostcode")[0].value, true,6)) 
    {
		document.getElementById("appPostcode_warn").className = "wrong_strong";
		document.getElementById("appPostcode_warn").innerHTML = "请填写正确的邮政编码";
		//window.setTimeout("document.getElementsByName('appPostcode')[0].focus();", 1)  ;  
		return false;
	}
    
    return true;
    
}


function checkJobCode()
{
	var job1 = document.getElementsByName("job_cate_code")[0].value;
	var job2 = document.getElementsByName("job_subcate_code")[0].value;
	var job3 = document.getElementsByName("job_code")[0].value;
	if(job1!="" && job2!="" && job3!=""){
		document.getElementById("jnsJobCode").className = "right_strong";
		document.getElementById("jnsJobCode").innerHTML= "";
	}else{
		document.getElementById("jnsJobCode").className = "wrong_strong";
		document.getElementById("jnsJobCode").innerHTML = "请选择完整的职业";
	}
}


function check_t_job()
{
	   
    ///////////////////////////////////////////////////////////////////////////////
	if(document.getElementsByName("job_cate_code"))
		jobs1 = document.getElementsByName("job_cate_code");
	
	if(document.getElementsByName("job_subcate_code"))
		jobs2 = document.getElementsByName("job_subcate_code");
	
	if(document.getElementsByName("job_code"))
		jobs3 = document.getElementsByName("job_code");

	
	if(jobs1.length>0)
	{
		job1 = document.getElementsByName("job_cate_code")[0].value;
	}
	
	if(jobs2.length>0)
	{
		job2 = document.getElementsByName("job_subcate_code")[0].value;
	}
	
	if(jobs3.length>0)
	{
		job3 = document.getElementsByName("job_code")[0].value;
	}
	
	return true;
}

function Checke_Purpose()
{
   	
    var Purpose = $j("select#Purpose").val();
   	if(!Purpose)
    {
    	alert("请输入出行目的!");
    	window.setTimeout("document.getElementById('Purpose').focus();", 0)  ;  
    	return false;
    }
   	
	
    var destination_country = $j("select#destination_country").val();
   	if(!destination_country)
    {
    	alert("请输入出行目的地国家!");
    	window.setTimeout("document.getElementById('destination_country').focus();", 0)  ;  
    	return false;
    }	
   	
   	return true;
}
////////////////////////////////////////////////////////////////////////////////////


//检查投保人邮政编码
function checktpostcode(){
	if(document.getElementsByName("appPostcode")[0] && document.getElementsByName("appPostcode")[0].value == "") {
		document.getElementById("appPostcode_warn").className = "wrong_strong";
		document.getElementById("appPostcode_warn").innerHTML = "请填写邮政编码";
		return false;
	}else if(!isChinaCode(document.getElementsByName("appPostcode")[0].value, true,6)) {
		document.getElementById("appPostcode_warn").className = "wrong_strong";
		document.getElementById("appPostcode_warn").innerHTML = "请填写正确的邮政编码";
		return false;
	}else{
		document.getElementById("appPostcode_warn").className = "right_strong";
		document.getElementById("appPostcode_warn").innerHTML= "";
	}
}

/*---------3.被保人输入项验证-------------*/

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
function checkinsName()
{
	if(document.getElementsByName("insName"))
	{
		tname = document.getElementsByName("insName")[0].value.replace(/\ /g,"");
	}
	
	if( tname == "") 
	{
		document.getElementById("insName_warn").className = "wrong_strong";
		document.getElementById("insName_warn").innerHTML = "请填写被保人姓名";
		//window.setTimeout("document.getElementById('insName').focus();", 0)  ;  
		return false;
	}
    //var insName = $j("input#insName").val();
	else if(strlen(tname)>24||strlen(tname)<3)
    {
    	//alert("被保险人姓名不合规，应该大于3个且少于24个字符!");
    	document.getElementById("insName_warn").className = "wrong_strong";
		document.getElementById("insName_warn").innerHTML = "被保险人姓名不合规，应该大于3个且少于24个字符!";
    	//window.setTimeout("document.getElementById('insName').focus();", 0)  ;  
    	return false;
    }
	else
	{
		document.getElementById("insName_warn").className = "right_strong";
		document.getElementById("insName_warn").innerHTML= "";
	}
	
	
  	///////////////////////////////////////////////////////////////////////

   	
   	return true;
 
}


//验证被保人姓名
function checkinsEnglishName()
{
	var bEnglishname;
	if(document.getElementsByName("insEnglishName"))
	{
		bEnglishname = document.getElementsByName("insEnglishName")[0].value.replace(/\ /g,"");
	}
	
	if(bEnglishname == "" &&document.getElementById("insEnglishName_warn"))
	{
		//alert("sdfsdf");
		document.getElementById("insEnglishName_warn").className = "wrong_strong";
		document.getElementById("insEnglishName_warn").innerHTML = "请填写被保人姓名";
		
		return false;
	}
	//var appEnglishName = $j("input#appEnglishName").val();
	else if(strlen(bEnglishname)<3||strlen(bEnglishname)>24)
	{
		//alert("投保人英文姓名不要超过30个字符!");
		document.getElementById("insEnglishName_warn").className = "wrong_strong";
		document.getElementById("insEnglishName_warn").innerHTML = "被保人英文姓名应在3-24字符之间!";
		//window.setTimeout("document.getElementById('insEnglishName').focus();", 0)  ;  
		return false;
	}	
	else
	{
		document.getElementById("insEnglishName_warn").className = "right_strong";
		document.getElementById("insEnglishName_warn").innerHTML= "";
	}
		
	return true;
}


/*检查被保人证件号*/
function checkbcard()
{
	
	var bcardtype = document.getElementById("insCertType").value;
    if(bcardtype == "" && document.getElementById("insCertCpde_warn"))
	{
		//window.setTimeout("document.getElementById('insCertType').focus();", 0)  ;  
		document.getElementById("insCertCpde_warn").className = "wrong_strong";
		document.getElementById("insCertCpde_warn").innerHTML = "请选择证件类型";
		//window.setTimeout("document.getElementById('insCertType').focus();", 0)  ;  
		return false;
	}
	
	
	
	var idcard = document.getElementById("insCertCpde").value;
	if(idcard == "")
	{
		if(document.getElementById("bcard_warn")){
			document.getElementById("bcard_warn").className="wrong_strong";
			document.getElementById("bcard_warn").innerHTML = "请填写证件号码";
			//window.setTimeout("document.getElementById('insCertCpde').focus();", 0)  ;  
		}
		return false;
	}
	else
	{
		var retStr = "";
		if(bcardtype == "1")
		{//证件类型是身份证
			var errorNo = isIdCardNo(idcard);
			if(errorNo == 0)
			{
				document.getElementById("insCertCpde_warn").className="right_strong";
				document.getElementById("insCertCpde_warn").innerHTML= "";
				
				return true;
			}
			else
			{
				retStr = errorIdcardMessage(errorNo);
				document.getElementById("insCertCpde_warn").className="wrong_strong";
				document.getElementById("insCertCpde_warn").innerHTML = retStr;
				//window.setTimeout("document.getElementById('insCertCpde').focus();", 0)  ;  
				return false;
			}
			
			return false;
		}
		
		document.getElementById("insCertCpde_warn").className="right_strong";
		document.getElementById("insCertCpde_warn").innerHTML= "";
		
	}
	
	return true;
	
}


function checkbsex()
{	
	if(document.getElementsByName("insSex")[0])
	{
		tsex1 = document.getElementsByName("insSex")[0].checked;//投保人性别
	}
	
	if(document.getElementsByName("insSex")[1])
		tsex2 = document.getElementsByName("insSex")[1].checked;//投保人性别
    
    if(tsex1==false && tsex2==false)
    {
		//window.setTimeout("document.getElementsByName('insSex')[0].focus();", 0)  ;  
		document.getElementById("insSex_warn").className = "wrong_strong";
		document.getElementById("insSex_warn").innerHTML = "请选择";
		return false;
	}
    
    return true;
    	
}



//验证被保人出生日期
function checkbbirthday(bbirthday,bstartage,bendage){
	
	//alert("bbirthday: "+bbirthday);
	//var bbirthday = document.getElementById("insBirthday").value;
	//var bbirthday = document.getElementById("insBirthday").value;
	if(bbirthday == "")
	{
		document.getElementById("insBirthday_warn").className = "wrong_strong";
		document.getElementById("insBirthday_warn").innerHTML = "请填写出生日期";
		//window.setTimeout("document.getElementById('insBirthday').focus();", 0)  ;
		return false;
	}
	else
	{
		//alert("before getAgeByBirthday");
		var bage = getAgeByBirthday(bbirthday);
		//alert("after getAgeByBirthday"+bage);
		if(bage == -1)
		{
			document.getElementById("insBirthday").className = "wrong_strong";
			document.getElementById("insBirthday_warn").innerHTML = "被保险人生日格式错误,例子(2010-01-02)";
			//window.setTimeout("document.getElementById('insBirthday').focus();", 0);
			
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
    	
    	//alert("before 1111");
    	if(bstartage.indexOf('d') > 0)
    	{
    		//alert("before 222");
    		if(age_d<sAge)
    		{
    			//alert("before 3333");
    			if(document.getElementById("insBirthday_warn"))
    			{
    				$("#insBirthday_warn").show();
    				document.getElementById("insBirthday_warn").className = "wrong_strong";
    				document.getElementById("insBirthday_warn").innerHTML = "被保人年龄必须大于"+sAge+"天";
    				//document.getElementById("insBirthday").value="";
    				//window.setTimeout("document.getElementById('insBirthday').focus();", 0)  ;
    			}
    			if(document.getElementById("insAge_warn"))
    			{
    				$("#insAge_warn").show();
    				document.getElementById("insAge_warn").className = "wrong_strong";
    				document.getElementById("insAge_warn").innerHTML = "被保人年龄必须大于"+sAge+"天";
    				//document.getElementById("insBirthday").value="";
    				//window.setTimeout("document.getElementById('insBirthday').focus();", 0)  ;
    			}
    			
				return false;
    		}
    		else if(bage>eAge)
    		{
    			//alert("before 4444");
    			if(document.getElementById("insBirthday_warn"))
    			{
    				//$("#insBirthday_warn").show();
    				document.getElementById("insBirthday_warn").className = "wrong_strong";
    				document.getElementById("insBirthday_warn").innerHTML = "被保人年龄必须小于或等于"+eAge+"周岁";
    				//document.getElementById("insBirthday").value="";
    				//window.setTimeout("document.getElementById('insBirthday').focus();", 0)  ;
    			}
    			
    			//alert("before 5555");
    			if(document.getElementById("insAge_warn"))
    			{
    				//$("#insAge_warn").show();
    				document.getElementById("insAge_warn").className = "wrong_strong";
    				document.getElementById("insAge_warn").innerHTML = "被保人年龄必须小于或等于"+eAge+"周岁";
    				//document.getElementById("insBirthday").value="";
    				//window.setTimeout("document.getElementById('insBirthday').focus();", 0)  ;
    			}
    			
				return false;
    		}
    	}
    	else if(bstartage.indexOf('y') > 0)
    	{
    		//alert("before Y");
    		
    		if(bage < sAge || bage>eAge)
    		{
    			//$("#insBirthday_warn").show();
				document.getElementById("insBirthday_warn").className = "wrong_strong";
				document.getElementById("insBirthday_warn").innerHTML = "被保人年龄必须在"+sAge+"-"+eAge+"岁之间";
				//document.getElementById("insBirthday").value="";
				//window.setTimeout("document.getElementById('insBirthday').focus();", 0)  ; 
				return false;
			}
    	}
    	else
    	{
    		//alert("other");
    		
    		if(document.getElementsByName("insAge"))
    		{
    			document.getElementsByName("insAge")[0].value = bage;
    		}
    		
    		//$("#insBirthday_warn").show();
			document.getElementById("insBirthday_warn").className = "right_strong";
			document.getElementById("insBirthday_warn").innerHTML= "";
		}
	}
	
	return true;
}

//检查被保险人手机号
function checktphone_ins()
{
	if(isNull(document.getElementsByName("insCell")[0].value)) 
	{
		document.getElementById("insCell_warn").className = "wrong_strong";
		document.getElementById("insCell_warn").innerHTML = "请填写手机号码"; 
		//window.setTimeout("document.getElementsByName('insCell')[0].focus();", 1)  ;  
		return false;
	}
	else if(!isMobile(document.getElementsByName("insCell")[0].value,true)) 
	{
		document.getElementById("insCell_warn").className = "wrong_strong";
		document.getElementById("insCell_warn").innerHTML = "请填写正确的手机号";
		//window.setTimeout("document.getElementsByName('insCell')[0].focus();", 1)  ;  
		return false;
	}
	else
	{
		document.getElementById("insCell_warn").className = "right_strong";
		document.getElementById("insCell_warn").innerHTML= "";
	}
	
	return true;
}

//检查被保险人电子邮箱
function checktemail_ins()
{
	if(document.getElementsByName("insEmail")[0].value == "") 
	{
		document.getElementById("insEmail_warn").className = "wrong_strong";
		document.getElementById("insEmail_warn").innerHTML = "请填写电子邮箱";
		//window.setTimeout("document.getElementsByName('insEmail')[0].focus();", 1)  ;  
		return false;
	}
	else if(!isEmail(document.getElementsByName("insEmail")[0].value, true)) 
	{
		document.getElementById("insEmail_warn").className = "wrong_strong";
		document.getElementById("insEmail_warn").innerHTML = "请填写正确的电子邮箱";
		//window.setTimeout("document.getElementsByName('insEmail')[0].focus();", 1)  ;  
		return false;
	}
	else
	{
		document.getElementById("insEmail_warn").className = "right_strong";
		document.getElementById("insEmail_warn").innerHTML= "";
	}
	
	return true;
}

/*
function checktaddress_ins()
{

	if(document.getElementsByName("insureareaprov_code")[0])
		tprov = document.getElementsByName("insureareaprov_code")[0].value;
	
	if(document.getElementsByName("insureareacity_code")[0])
		tcity = document.getElementsByName("insureareacity_code")[0].value;

	///////////////////////////////////////////////////////////
	
	//////////////////////////////////////////////////////////////////
	  
    if(tprov =="" || tcity =="" ) 
    {
    	if(document.getElementById("taddr"))
    	{
			document.getElementById("taddr").className = "wrong_strong";
			document.getElementById("taddr").innerHTML = "请选择完整的通讯地址";
			//window.setTimeout("document.getElementsByName('taddr')[0].focus();", 0)  ;  
			return false;
    	}
	}
    
    return true;
    
}
*/

function checktjob_ins()
{


	if(document.getElementById("jnsJobCode")&&job1!=null && job2!=null && job3!=null && (job1==""||job2==""||job3==""))
	{
		document.getElementById("jnsJobCode").className = "wrong_strong";
		document.getElementById("jnsJobCode").innerHTML = "请选择完整的职业";
		//window.setTimeout("document.getElementsByName('job_cate_code')[0].focus();", 0)  ;  
		return false;
	}

  	
    if(document.getElementById("jnsJobCode")&&job1!=null && job2!=null && job3!=null && (job1==""||job2==""||job3=="")){
		document.getElementById("jnsJobCode").className = "wrong_strong";
		document.getElementById("jnsJobCode").innerHTML = "请选择完整的职业";
		//window.setTimeout("document.getElementsByName('job_cate_code')[0].focus();", 0)  ; 
		return false;
	}
    	
	return true;	
}

function otherChecked(){
	var flag = true;
	$("[name=otherInfo]").each(function(){
		if(this.type=="text"&&trim(this.value)==""){
			alert("请填写"+this.title);
			flag = false;
			this.focus();
			return false;
		}else if(trim(this.value)==""){
			alert("请选择"+this.title);
			flag = false;
			this.focus();
			return false;
		}
		
		$("#"+this.id+"hide").val(this.title);
		
	});
	
	return flag;
}

function tsex_click(){
	document.getElementById("tsex_warn").className = "wrong_span";
	document.getElementById("tsex_warn").innerHTML = "";
}
function bsex_click(){
	document.getElementById("bsex_warn").className = "wrong_span";
	document.getElementById("bsex_warn").innerHTML = "";
}


/**
 * 选择被保人证件类型 
 */
function choosebcardtype(obj){
	var bcardtype = obj.value;
	document.getElementById("insCertCpde").value = "";
	if(bcardtype == ""){
		document.getElementById("insCertCpde").disabled=true;
		document.getElementById("insCertCpde_warn").className="wrong_strong";
		document.getElementById("insCertCpde_warn").innerHTML = "请选择证件类型";
	}else{
		document.getElementById("insCertCpde").disabled=false;
		document.getElementById("insCertCpde_warn").className="warn_strong";
		document.getElementById("insCertCpde_warn").innerHTML = "请填写证件号码";
		if(bcardtype == "1"){ 
			document.getElementsByName("insSex")[0].disabled=true;
			document.getElementsByName("insSex")[1].disabled=true;
			document.getElementsByName("insSex")[0].checked=false;
			document.getElementsByName("insSex")[1].checked=false;
		}else{
			document.getElementsByName("insSex")[0].disabled=false;
			document.getElementsByName("insSex")[1].disabled=false;
			document.getElementsByName("insSex")[0].checked=false;
			document.getElementsByName("insSex")[1].checked=false;
		}
	}
}




function checkform(){

	if(document.getElementsByName('address')[0].value == "") {
		alert("请填写投保人通讯地址");
		document.getElementsByName('address')[0].focus();
		return false;
	}
	
	if(document.getElementsByName('startTime')[0].value == "") {
		alert("请填写保险开始时间");
		document.getElementsByName('startTime')[0].focus();
		return false;
	}
	
}

//订单投保人居住省
function chageOrderArea(thisUrl){
	show(thisUrl,"orderareadiv",false);
}

//订单被保险人职业选择
function chageChoose(thisUrl,strdiv){
	if(!$("[name='job_subcate_code']").get(0)&&thisUrl.indexOf("jobSelect")!=-1){//如果只有主分类，就不需要联动职业菜单了。
	}else{
		show(thisUrl,strdiv,false);
	}
}

function changeElementValue(curElement,thisValue,hidedom) {
	document.getElementsByName(curElement)[0].value = thisValue;
	$("[name="+hidedom+"]").hide();
}

function chageSystemArea(customerInfoUrl,position){
	show(customerInfoUrl,"systemareadiv",false);
	var provincial = getSelectText(document.getElementsByName('orderConsigneeAddress.provincial')[0]);
	var city = getSelectText(document.getElementsByName('orderConsigneeAddress.city')[0]);
	var region = getSelectText(document.getElementsByName('orderConsigneeAddress.region')[0]);
	var address = provincial + city + region;
	check_t_region();
}

function getSelectText(obj) {
	if(obj == null) return '';
	if(!obj.length) {
		return obj.value;
	}
	for(i=0;i<obj.length; i++) {
		if(obj[i].value == obj.value) return obj[i].text;
	}
	
	return '';
}

function changeRegion() {
	var provincial = getSelectText(document.getElementsByName('orderConsigneeAddress.provincial')[0]);
	var city = getSelectText(document.getElementsByName('orderConsigneeAddress.city')[0]);
	var region = getSelectText(document.getElementsByName('orderConsigneeAddress.region')[0]);
	var address = provincial + city + region;
	//document.getElementsByName('bean.orderConsigneeAddress.address')[0].value = address;
	check_t_region()
}


function submitaddress(viewType,context) {

	if(document.getElementsByName('bean.orderConsigneeAddress.name')[0].value == "") { 
			alert("请填写收货人");
			document.getElementsByName('bean.orderConsigneeAddress.name')[0].focus();
			return false;
	}
	
	if(document.getElementsByName('orderConsigneeAddress.city')[0] != null && document.getElementsByName('orderConsigneeAddress.city')[0].value == "") {
		alert("请选择城市");
		document.getElementsByName('orderConsigneeAddress.city')[0].focus();
		return false;
	}
	
	if(document.getElementsByName('orderConsigneeAddress.region')[0] != null && document.getElementsByName('orderConsigneeAddress.region')[0].value == "") {
		alert("请选择地区");
		document.getElementsByName('orderConsigneeAddress.region')[0].focus();
		return false;
	}
	
	if(document.getElementsByName('bean.orderConsigneeAddress.address')[0].value == "") {
			alert("请填写地址");
			document.getElementsByName('bean.orderConsigneeAddress.address')[0].focus();
			return false;
	}
	
	if(document.getElementsByName('bean.orderConsigneeAddress.address')[0].value != "" && hasSpecSymbol(document.getElementsByName('bean.orderConsigneeAddress.address')[0].value,true)) {
			alert("地址中不能有特殊字符！");
			document.getElementsByName('bean.orderConsigneeAddress.address')[0].focus();
			return false;
	}
	
	if(!isChinaCode(document.getElementsByName('bean.orderConsigneeAddress.zipcode')[0].value, true,6)) {
			alert("请填写正确的邮编");
			document.getElementsByName('bean.orderConsigneeAddress.zipcode')[0].focus();
			return false;
	}	

	
	if(document.getElementsByName('bean.orderConsigneeAddress.mobileTelephone')[0].value == "" && document.getElementsByName('bean.orderConsigneeAddress.telephone')[0].value == "") {
			alert("请填写手机号码或电话号码。");
			return false;
	}
	
	if(!isTelNo(document.getElementsByName('bean.orderConsigneeAddress.telephone')[0].value, true)
		&& !isMobile(document.getElementsByName('bean.orderConsigneeAddress.telephone')[0].value,false)
		) {
			alert("请填写正确的电话号码或手机号码");
			document.getElementsByName('bean.orderConsigneeAddress.telephone')[0].focus();
			return false;
	}

	if(document.getElementsByName('bean.orderConsigneeAddress.emailAddress')[0].value != '' 
		&& !isEmail(document.getElementsByName('bean.orderConsigneeAddress.emailAddress')[0].value, true)) {
			alert("请填写正确的邮箱地址");
			document.getElementsByName('bean.orderConsigneeAddress.emailAddress')[0].focus();
			return false;
	}
	
	 var thisUrl=context+"/fg/order/orderAdd-shippay.jsp";
	 
	 if('view' == viewType) {
	 	 thisUrl=context+"/fg/order/orderAdd-address-view.jsp?"+new Date();
	 	 var appXml = getFormData();
		 sendFormData(gopayaddressview,"POST",thisUrl,true,appXml,true);
		 show(context+'/fg/order/orderAdd-ship-edit.jsp', 'shipview', false)

		 return;
	 } else {
		 thisUrl=context+"/fg/order/orderAdd-shippay.jsp?"+new Date();
	 	 var appXml = getFormData();
		 sendFormData(gopayship,"POST",thisUrl,true,appXml,true);
		 return ;
	 }
}


function getFormData(){		
	
	var name = document.getElementsByName('bean.orderConsigneeAddress.name')[0].value;
	var zipCode = document.getElementsByName('bean.orderConsigneeAddress.zipcode')[0].value;
	var address = document.getElementsByName('bean.orderConsigneeAddress.address')[0].value;
	var telephone = document.getElementsByName('bean.orderConsigneeAddress.telephone')[0].value;
	var mobileTelephone = document.getElementsByName('bean.orderConsigneeAddress.mobileTelephone')[0].value;
	var emailAddress = document.getElementsByName('bean.orderConsigneeAddress.emailAddress')[0].value;

	var provincial = document.getElementsByName('orderConsigneeAddress.provincial')[0].value;
	var city = "";
	if(document.getElementsByName('orderConsigneeAddress.city')[0] != null) {
		city = document.getElementsByName('orderConsigneeAddress.city')[0].value;
	}
	var region = "";
	if(document.getElementsByName('orderConsigneeAddress.region')[0] != null) {
		region = document.getElementsByName('orderConsigneeAddress.region')[0].value;
	}
	var sex = getRadioValue(document.getElementsByName('bean.orderConsigneeAddress.sex'));
	if(sex == null || sex=='' || sex == "") {
		sex = "1";
	}
	var retrunXml = "<save>";
	retrunXml = retrunXml + "<type>orderaddress</type>";
	retrunXml = retrunXml + "<orderaddress>";
	retrunXml = retrunXml + "<name>";
	retrunXml = retrunXml + escape(name);
	retrunXml = retrunXml + "</name>";
	retrunXml = retrunXml + "<zipCode>";
	retrunXml = retrunXml + escape(zipCode);
	retrunXml = retrunXml + "</zipCode>";
	retrunXml = retrunXml + "<address>";
	retrunXml = retrunXml + escape(address);
	retrunXml = retrunXml + "</address>";
	retrunXml = retrunXml + "<telephone>";
	retrunXml = retrunXml + escape(telephone);
	retrunXml = retrunXml + "</telephone>";
	retrunXml = retrunXml + "<mobileTelephone>";
	retrunXml = retrunXml + escape(mobileTelephone);
	retrunXml = retrunXml + "</mobileTelephone>";
	
	retrunXml = retrunXml + "<emailAddress>";
	retrunXml = retrunXml + escape(emailAddress);
	retrunXml = retrunXml + "</emailAddress>";
	
	retrunXml = retrunXml + "<provincial>";
	retrunXml = retrunXml + escape(provincial);
	retrunXml = retrunXml + "</provincial>";
	
	retrunXml = retrunXml + "<city>";
	retrunXml = retrunXml + escape(city);
	retrunXml = retrunXml + "</city>";

	retrunXml = retrunXml + "<region>";
	retrunXml = retrunXml + escape(region);
	retrunXml = retrunXml + "</region>";
	
	retrunXml = retrunXml + "<sex>";
	retrunXml = retrunXml + escape(sex);
	retrunXml = retrunXml + "</sex>";
	
	retrunXml = retrunXml + "</orderaddress>";
	retrunXml = retrunXml + "</save>";			
	return retrunXml;
}

function getRadioValue(obj) {
		if(obj == null) return '';
		if(obj.length == null && obj.checked) {
			return obj.value;
		}
		
		if(obj.length && obj.length > 0) {
			for(i=0;i<obj.length; i++) {
				if(obj[i].checked){
				 return obj[i].value;
				} 
			}
		} else {
			return obj.value;
		}
	return ''; 
}

function gopayship(){
 if (rq.readyState == 4){
  if (rq.status == 200){
    var returnText = rq.responseText;     
    document.getElementById('orderadd').innerHTML = returnText;
   }
 }
}

function gopayaddressview(){
		if (rq.readyState == 4){
    if (rq.status == 200){
      var returnText = rq.responseText;
      document.getElementById('addressview').innerHTML = returnText;
    }
  }
}

 var rq = false;	
 function sendFormData(methodName,httpMethod,thisUrl,isSynchronous,sendXml,isXML) {
		if(window.XMLHttpRequest) {
			rq = new XMLHttpRequest();
			if (rq.overrideMimeType){
				rq.overrideMimeType('text/xml');
			}
		}else if (window.ActiveXObject) {
			 try {
				 rq = new ActiveXObject("Msxml2.XMLHTTP");			
			  }catch(e){
				try {
					rq = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			}
		}
		if (!rq){
			window.alert("不能创建XMLHttpRequest对象实例.");
			return false;
		}
		rq.onreadystatechange = methodName;
		rq.open(httpMethod,thisUrl,isSynchronous);
		if(httpMethod=="POST" && isXML){
			rq.setRequestHeader("Content-Type","text/xml");
			rq.setRequestHeader("charset","GBK");			
		 }else if(httpMethod=="POST" && !isXML){
			rq.setRequestHeader("Content-Type","application/x-www-form-urlencoded");			
		}
		if (window.ActiveXObject){
		 rq.setRequestHeader("If-Modified-Since","0");
 		}
		rq.send(sendXml);
}




function showOrderAdd(pageUrl, targetObject, isadd) {
	var	showAjax = new ajax();
	showAjax.url = pageUrl;
	showAjax.callback = showResult;
	showAjax.cache = true;
	showAjax.send();
	function showResult(){
	  if (showAjax.req.readyState == 4) {
	    if (showAjax.req.status == 200) {
	      var returnText = showAjax.req.responseText;
					if(isadd) {
	      		obji(targetObject).innerHTML=obji(targetObject).innerHTML + returnText;
	      	} else {
	      		obji(targetObject).innerHTML= returnText;
	      	}
	      	
	      	showAjax = null;
	    }
	  }
	}
}


function openShow(act){
	 	if(act=='1'){
		  document.getElementById('ordergift').style.display='block';
		  document.getElementById('yhqfh').innerHTML = "(-)";
		}
		if(act=='2'){
			document.getElementById('ordergift2').style.display='block';
		  document.getElementById('fpfh').innerHTML = "(-)";
		}
  }
  
  function closeShow(act){
	 	if(act=='1'){
		  document.getElementById('ordergift').style.display='none';
		  document.getElementById('yhqfh').innerHTML = "(+)";
		}
		if(act=='2'){
			document.getElementById('ordergift2').style.display='none';
		  document.getElementById('fpfh').innerHTML = "(+)";
		}  	
  }
  
  
var mp_pop = null;
 var _HTML_ = "";

function buildTree(turl, treeName){
	if(mp_pop!=null)mp_pop.close();
	
	var	showAjax = new ajax();
	showAjax.url =turl;
	showAjax.callback = showResult;
	showAjax.cache = true;
	showAjax.send();
	
	function showResult(){
	  if (showAjax.req.readyState == 4) {
	    if (showAjax.req.status == 200) {
	      var returnText = showAjax.req.responseText;
					showAjax = null; 
					_HTML_=returnText;
					mp_pop=new Popup({contentType:2,isReloadOnClose:false,top:2000,width:460,height:185});
					mp_pop.setContent("title",treeName);
					mp_pop.setContent("contentHtml", _HTML_);
					mp_pop.build();
					mp_pop.show();
	    }else{
	     
	    }
	  }
  }

}

	function cleargiftCard() {
		var	showAjax = new ajax();

		var thisUrl = 'orderadd.do?metType=cleargiftcard';

		showAjax.url =thisUrl;
		
		showAjax.callback = showResult;
		showAjax.cache = true;
		showAjax.send();

		function showResult(){
		  if (showAjax.req.readyState == 4) {
		 
		    if (showAjax.req.status == 200) {
		 			var xmlDoc=showAjax.req.responseXML;
		      var amount = "";
		      if (window.XMLHttpRequest){
		      	if(xmlDoc.getElementsByTagName("amount") != null)
		      	amount = xmlDoc.getElementsByTagName("amount")[0].firstChild.nodeValue;
		      } else {
		      	if(xmlDoc.getElementsByTagName("amount") != null)
		      	amount = xmlDoc.getElementsByTagName("amount")[0].text;
		    	}
		      
		      
		      var rtext = showAjax.req.responseText;
		     	if(amount != null) {
						changeGiftAmountValue(amount);
					} 
	        
	        showAjax = null;
	    	}
		   }
	  }
	}
	 
function changeGiftAmountValue(gvalue) {
	document.getElementsByName('order_giftamount')[0].value = gvalue;
	if(parseFloat(gvalue)==0){
		document.getElementById('giftamount').innerHTML = '';
	  document.getElementById('giftId').innerHTML = "<a  onclick=\"buildTree('<%=CONTEXT%>/fg/order/orderAdd-giftcard.jsp?totalUserPrice="+document.getElementsByName('order_totalUserPrice')[0].value+"', '我要用优惠券');\"><span style=\"cursor:hand\">[我要用优惠券]</span></a>";
	}else{
		document.getElementById('giftamount').innerHTML =  to2bits(parseFloat(gvalue))
		document.getElementById('giftId').innerHTML = "<a  onclick=\"cleargiftCard()\"><span style=\"cursor:pointer\">[清除]</span></a>";
	}
	orderStat();
}

 function submitShipType() {
		var shipTypeId = getRadioValue(document.getElementsByName('bean.shipType'));
		if(shipTypeId == '' || shipTypeId == null) {
			alert("请选择配送方式。")
			return ;
		}else {
			var showUrl = "orderAdd-pay.jsp?shipTypeId=" + shipTypeId;
			show(showUrl, 'orderadd', false);
	  }
	}
	
	function submitShipTypeview() {
		var shipTypeId = getRadioValue(document.getElementsByName('bean.shipType'));
		if(shipTypeId == '' || shipTypeId == null) {
			alert("请选择配送方式。")
			return ;
		}else {
			var showUrl = "orderAdd-pay.jsp?shipTypeId=" + shipTypeId;
			show(showUrl, 'orderadd', false);
	  }
	}
	
  function submitPayType(act) {
		var payTypeId = getRadioValue(document.getElementsByName('bean.payType'));
		if(payTypeId == '' || payTypeId == null) {
			alert("请选择支付方式。");
		}else {
			var showUrl = "";
			if(act == "groupbuy"){
			  showUrl = "orderAdd-groupBuyView.jsp?payTypeId=" + payTypeId;	
			}else{
			  showUrl = "orderAdd-view.jsp?payTypeId=" + payTypeId;			
		  }
			show(showUrl, 'orderadd', false);
			$("#orderadd").show();
	  }
	}
	
	function submitPayTypeview() {
		var payTypeId = getRadioValue(document.getElementsByName('bean.payType'));
		if(payTypeId == '' || payTypeId == null) {
			alert("请选择支付方式。")
		}else {
			var showUrl = "orderAdd-view.jsp?payTypeId=" + payTypeId;
			show(showUrl, 'orderadd', false);
	  }
	}
 	function submitGiftCard(totalUserPrice) {
 		if(document.getElementsByName('gift_no')[0].value =='' || document.getElementsByName('gift_no')[0].value==""){
 				alert("请填写优惠券号。");	
 				return;
 		}
		var	showAjax = new ajax();
		var thisUrl = 'orderAdd-giftcard.jsp?check=true&gift_no=' + document.getElementsByName('gift_no')[0].value +"&totalUserPrice="+totalUserPrice;
		showAjax.url =thisUrl;
		showAjax.callback = showResult;
		showAjax.cache = true;
		showAjax.send();
	
		function showResult(){
		  if (showAjax.req.readyState == 4){
		 
		    if (showAjax.req.status == 200) {
		    	var xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
		      xmlDoc.loadXML(trim(showAjax.req.responseText));
		     	if(xmlDoc.getElementsByTagName("amount") != null 
						&& xmlDoc.getElementsByTagName("amount")[0] != null 
						&& xmlDoc.getElementsByTagName("amount")[0].text != null 
					) {
						var amountValue = xmlDoc.getElementsByTagName("amount")[0].text;
						show('orderAdd-giftcard.jsp?totalUserPrice=' + amountValue, 'giftCardId', false);
						$("#giftCardValueId").html(amountValue);
						var paid = $("#paid").html();
						$("#paid").html(parseFloat(paid) - parseFloat(amountValue));
						show('orderAdd-giftcard.jsp?', 'useOrderGift', false);
					} else if(xmlDoc.getElementsByTagName("total_product_cost_error") != null 
						&& xmlDoc.getElementsByTagName("total_product_cost_error")[0] != null 
						&& xmlDoc.getElementsByTagName("total_product_cost_error")[0].text != null 
						) {
							alert("您的购物金额必须大于" + xmlDoc.getElementsByTagName("total_product_cost_error")[0].text + "才可以使用本优惠券.");							
					} else {
						alert("您所输入的优惠券无效! 原因可能是：1、优惠券号错误 2、优惠券已被使用 3、优惠券过期。");	
					} 
	        
	        showAjax = null;
		    }
		  }
	  }
	}
	
	
	
 function submitInvoice() {
 	if(document.getElementsByName('bean.invoiceTitle')[0] && isNull(document.getElementsByName('bean.invoiceTitle')[0].value)){
 		alert("发票抬头不能为空.");	
 		return;
 	}
	var	showAjax = new ajax();
	var thisUrl = 'orderAdd-invoice.jsp?invoice=true&invoice_title=' + document.getElementsByName('bean.invoiceTitle')[0].value + '&invoice_content=' + document.getElementsByName('bean.invoiceContent')[0].value;
	showAjax.url =thisUrl;
	showAjax.callback = showResult;
	showAjax.cache = true;
	showAjax.send();
	
	function showResult(){
	  if (showAjax.req.readyState == 4) {
	 
	    if (showAjax.req.status == 200) {
	    	var xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
	      xmlDoc.loadXML(trim(showAjax.req.responseText));
	      
				if(xmlDoc.getElementsByTagName("invoice") != null 
					&& xmlDoc.getElementsByTagName("invoice")[0] != null 
					&& xmlDoc.getElementsByTagName("invoice")[0].text == 'true' 
				) {
					show('orderAdd-invoice.jsp', 'invoiceId', false);
				}
        
        showAjax = null;

	     }
	    }
	  }
	}

function submit1Form(currentTime) {
	if(document.getElementsByName('bean.sendTime')[0] && document.getElementsByName('bean.sendTime')[0].value != '' && getSelectText(document.getElementsByName('bean.sendTime')[0]) < currentTime ){
		alert("对不起，送货时间需要大于当前时间。");
		return;
	}
	if(confirm("确定该订单内容吗?")){
		document.fgorderForm.submit();
	}
}
function submitForm() {
	var shipType = getRadioValue(document.getElementsByName('bean.shipType'));
	var payType = getRadioValue(document.getElementsByName('bean.payType')); 
	if(shipType == '') {
		alert('请选择配送方式。')
		return;
	}
	
	if(payType == '') {
		alert('请选择付款方式。')
		return;
	}
	
	if(confirm("确定该订单内容吗?")){
			document.fgorderForm.submit();
	}
}


function checkAddressForm() {
	if($("[name=bean.orderConsigneeAddress.name]").val() == "") { 
		alert("请填写收货人姓名");
		$("[name=bean.orderConsigneeAddress.name]").focus();
		return false;
	}
	if($("[name=orderConsigneeAddress.provincial]").val() == "") {
		alert("请选择联系地址");
		$("[name=orderConsigneeAddress.provincial]").focus();
		return false;
	}
	if($("[name=orderConsigneeAddress.city]").val() == "") {
		alert("请选择联系地址城市");
		$("[name=orderConsigneeAddress.city]").focus();
		return false;
	}
	if($("[name=orderConsigneeAddress.region]").val() == "") {
		alert("请选择联系地址区县");
		$("[name=orderConsigneeAddress.region]").focus();
		return false;
	}
	if($("[name=bean.orderConsigneeAddress.address]").val() == "") {
			alert("请填写详细地址");
			$("[name=bean.orderConsigneeAddress.address]").focus();
			return false;
	}
	if($("[name=bean.orderConsigneeAddress.address]").val() != "" && hasSpecSymbol($("[name=bean.orderConsigneeAddress.address]").val(),true)) {
			alert("详细地址中不能有特殊字符！");
			$("[name=bean.orderConsigneeAddress.address]").focus();
			return false;
	}
	
//	if(getRadioValue(document.getElementsByName('bean.orderConsigneeAddress.sex')) == '') {
//			alert("请选择性别");
//			return false;
//	}

	if($("[name=bean.orderConsigneeAddress.zipCode]").val() == "") { 
		alert("请填写邮编。");
		$("[name=bean.orderConsigneeAddress.zipCode]").focus();
		return false;
	}
	

	if(!isChinaCode($("[name=bean.orderConsigneeAddress.zipCode]").val(), true,6)|| !isNumber($("[name=bean.orderConsigneeAddress.zipCode]").val(),true)) {
			alert("请填写正确的邮编编码");
			$("[name=bean.orderConsigneeAddress.zipCode]").focus();
			return false;
	}
	if($("[name=bean.orderConsigneeAddress.mobileTelephone]").val() == ""){
			alert("请填写手机号码。");
			return false;
	}
	if($("[name=bean.orderConsigneeAddress.mobileTelephone]").val() != ""&&!isMobile($("[name=bean.orderConsigneeAddress.mobileTelephone]").val(),true)){
			alert("请填写正确的手机号码。");
			$("[name=bean.orderConsigneeAddress.mobileTelephone]").focus();
			return false;
	}
	/*
	if($("[name=zoneDescription]").val()== ""	&& $("[name=bean.orderConsigneeAddress.mobileTelephone]").val()== "" ){
			alert("请填写手机号码或电话号码。");
			$("[name=zoneDescription]").focus();
			return false;
	}
	*/
	if($("[name=bean.orderConsigneeAddress.emailAddress]").val() != '' 
		&& !isEmail($("[name=bean.orderConsigneeAddress.emailAddress]").val(), true)) {
			alert("请填写正确的邮箱地址");
			$("[name=bean.orderConsigneeAddress.emailAddress]").focus();
			return false;
	}
	return true;
}

var adressOptions = {
	beforeSubmit:  function(){
		return checkAddressForm();
	}, 
	type:'post',
	success: function(html){
		$('#address').html(html)
			if($('#hasShipType').val() != 'true') {
	      show('orderadd.do?metType=shiptypeadd', 'shipType', false);
	    }
	    show('orderadd.do?metType=stat', 'stat', false);
    } 
  };
  
  var shipTypeOptions = { 
		beforeSubmit:  function(){
			var shipType = getRadioValue(document.getElementsByName('bean.shipType'));
			
			if(shipType == "") { 
				alert("请选择配送方式。");
				return false;
			}
			return true;
	}, 
	type:'post',
	success: function(html){
		$('#shipType').html(html)
    show('orderadd.do?metType=paytypeadd', 'payType', false);
    show('orderadd.do?metType=stat','stat', false);
    } 
  };
  
    var payTypeOptions = { 
		beforeSubmit:  function(){
			var payType = getRadioValue(document.getElementsByName('bean.payType'));
			
			if(payType == "") { 
				alert("请选择支付方式。");
				return false;
			}
			return true;
	}, 
	type:'post',
	success: function(html){
		$('#payType').html(html)
    } 
  };
 
var voucherOptions = { 
	beforeSubmit:  function(){
		var voucherNo = $("[name=voucher_no]").val();
		var price = parseFloat($("#price").val());
		var gathering = parseFloat($("#gathering").val());
		if(gathering<=price){
			var mes=confirm("你的抵用卷金额超过或等于订单应付金额，系统将为你直接支付？");
			if(!mes){
				return false;
			}
		}
		if(voucherNo == "") { 
			alert("请填写抵用券号码。");
			$("[name=voucher_no]").focus();
			return false;
		}
		document.getElementById("vtjbut").style.display="none";
		document.getElementById("vtjbuth").style.display="";
		document.getElementById("vtjbutts").style.display="none";
		document.getElementById("vtjbuthts").style.display="";
		return true;
	}, 
	type:'post',
	success: function(html){
		var cruTime = new Date().toLocaleTimeString();
		$('#voucher').html(html);
		$.get(CONTEXT+"/fg/insurance/order/newOrderAdd.do?metType=billing&dateTime=" + cruTime,
			function(billinghtml){
				$('#billing').html(billinghtml);
				var price = parseFloat($("#price").val(""));
				var gathering = parseFloat($("#gathering").val(""));
			}
		);
  	} 
}; 
  
var giftCardOptions = { 
	beforeSubmit:  function(){
		var giftCardNo = $('#gift_no').val();
		
		if(giftCardNo == "") { 
			alert("请填写优惠券号码。");
			$('#gift_no').focus();
			return false;
		}
		return true;
	}, 
	type:'post',
	success: function(html){
		$('#giftcard').html(html);
		show('orderadd.do?metType=stat','stat', false);
  } 
};


var invoiceOptions = { 
	beforeSubmit:  function(){
		var invoiceTitle = $("[name=bean.invoiceTitle]").val();
		return true;
	}, 
	type:'post',
	success: function(html){
		$('#invoice').html(html)
  } 
};

function setPayType(str){
	if($('input[name=bean.payType][checked]').val()!=""){
		document.getElementById('payNote').innerHTML = "<div class='datx_bezu'>"+str+"</div>";
	}else{
		document.getElementById('payNote').innerHTML = "";
	}
}


//核保问题-订单提交
function orderQuestionSubmit(productNo,questionIds) {
	var questionId = questionIds.split(",");
	if(productNo == '103798008' || productNo == '103798009' || productNo == '103798010' || productNo == '103798011' || productNo == '103798012' || productNo == '103798013' ){
		//中英旅游险7款第一个问题选"是"
		for(var i = 0;i<questionId.length;i++){
			if(i == 0){
				if($("[name=question"+i+"][@checked]").val()=="0"){
			  		alert("核保问题不通过，无法投保本产品");
			  		$("[name=question"+i+"]").focus();
			  		return false;
				}else if(!$("[name=question"+i+"][@checked]").val()){
						alert("核保问题未勾选完整，请确认。");
			  		$("[name=question"+i+"]").focus();
			  		return false;				
				}
			}else{ 
				if($("[name=question"+i+"][@checked]").val()=="1"){
			  		alert("核保问题不通过，无法投保本产品");
			  		$("[name=question"+i+"]").focus();
			  		return false;
				}else if(!$("[name=question"+i+"][@checked]").val()){
						alert("核保问题未勾选完整，请确认。");
			  		$("[name=question"+i+"]").focus();
			  		return false;				
				}
			}
		}
	}else if(productNo!='103798001'&&productNo!='103798002'&&productNo!='103798003'&&productNo!='103798004'){
		for(var i = 0;i<questionId.length;i++){
			if($("[name=question"+i+"][@checked]").val()=="1"){
		  		alert("核保问题不通过，无法投保本产品");
		  		$("[name=question"+i+"]").focus();
		  		return false;
			}else if(!$("[name=question"+i+"][@checked]").val()){
					alert("核保问题未勾选完整，请确认。");
		  		$("[name=question"+i+"]").focus();
		  		return false;				
			}
		}
	}else{
		for(var i = 0;i<questionId.length;i++){
			//103798001checkqa004为问题（4、您每年的收入是否低于4万元？）uuid
			if(questionId[i]!="103798001checkqa004" && questionId[i]!="103798002checkqa004" && questionId[i]!="103798003checkqa004" && questionId[i]!="103798004checkqa004"){
				if($("[name=question"+i+"][@checked]").val()=="1"){
			  		alert("核保问题不通过，无法投保本产品");
			  		$("[name=question"+i+"]").focus();
			  		return false;
				}else if(!$("[name=question"+i+"][@checked]").val()){
					alert("核保问题未勾选完整，请确认。");
			  		$("[name=question"+i+"]").focus();
			  		return false;
				}
			}
		}
	}
  document.getElementById('orderForm').submit();
}



//订单返回修改
function orderBackSubmit(type) {
  if(type=="计划信息"){
	  document.getElementsByName('metType')[1].value = "backaccountallocate";
  }else{
	  document.getElementsByName('metType')[1].value = "orderback";
  }
  document.getElementById('fgNewOrderSearchForm').submit();
}



//与被投保人关系选择
function choose(k,bstype){
	
	var selecttbName = "tbRelation" + k;
	//选择的文字
  var selecttbText = $('select[name='+selecttbName+']').val();	
  
	var selectsbName = "sbrelation" + k;	
	//选择的文字
  var selectsbText = $('select[name='+selectsbName+']').val();	
  
	//姓名的选择
	var tname = "tname" + k;
	//证件类型的选择
	var tcardtype = "tcardtype" + k;	
	//证件号码的选择
	var tcard = "tcard" + k;
	
	//被保人姓名的选择
	var bname = "bname" + k;
	//被保人证件类型的选择
	var bcardtype = "bcardtype" + k;	
	//被保人证件号码的选择
	var bcard = "bcard" + k;

	//受益人姓名的选择
	var sname = "sname" + k;
	//受益人证件类型的选择
	var scardtype = "scardtype" + k;	
	//受益人证件号码的选择
	var scard = "scard" + k;
	
	if("投保人与被保人关系"==bstype){
		//被保人关系选择本人的情况下，被保人姓名身份证号码不用填写
		if(selecttbText=="本人"){
			$('input[name=' + bname +']').val($('input[name=' + tname +']').val());
			$('select[name=' + bcardtype +']').val($('select[name=' + tcardtype +']').val());
			$('input[name=' + bcard +']').val($('input[name=' + tcard +']').val());
			$('input[name=' + bname +']').attr('disabled','false');
			$('select[name=' + bcardtype +']').attr('disabled','false');
			$('input[name=' + bcard +']').attr('disabled','false');
		}else{
			$('input[name=' + bname +']').val("");
			$('select[name=' + bcardtype +']').val("---请选择---");
			$('input[name=' + bcard +']').val("");
			$('input[name=' + bname +']').removeAttr('disabled');
			$('select[name=' + bcardtype +']').removeAttr('disabled');
			$('input[name=' + bcard +']').removeAttr('disabled');
		}		
		//被保人关系选择本人的情况下，受益人姓名身份证号码不用填写
		if(selectsbText=="本人"){
			$('input[name=' + sname +']').val($('input[name=' + bname +']').val());
			$('select[name=' + scardtype +']').val($('select[name=' + bcardtype +']').val());
			$('input[name=' + scard +']').val($('input[name=' + bcard +']').val());
			$('input[name=' + sname +']').attr('disabled','false');
			$('select[name=' + scardtype +']').attr('disabled','false');
			$('input[name=' + scard +']').attr('disabled','false');
		}	
	}else if("受益人与被保人关系"==bstype){		
		//被保人关系选择本人的情况下，受益人姓名身份证号码不用填写
		if(selectsbText=="法定"){
			//$('input[name=' + sname +']').val($('input[name=' + bname +']').val());
			//$('select[name=' + scardtype +']').val($('select[name=' + bcardtype +']').val());
			//$('input[name=' + scard +']').val($('input[name=' + bcard +']').val());
			$('input[name=' + sname +']').val("");
			$('select[name=' + scardtype +']').val("---请选择---");
			$('input[name=' + scard +']').val("");
			$('input[name=' + sname +']').attr('disabled','false');
			$('select[name=' + scardtype +']').attr('disabled','false');
			$('input[name=' + scard +']').attr('disabled','false');
			
			document.getElementById("sg"+sname).innerHTML="";
			document.getElementById("sg"+scardtype).innerHTML="";
			document.getElementById("sg"+scard).innerHTML="";
		}else{
			$('input[name=' + sname +']').val("");
			$('select[name=' + scardtype +']').val("---请选择---");
			$('input[name=' + scard +']').val("");
			$('input[name=' + sname +']').removeAttr('disabled');
			$('select[name=' + scardtype +']').removeAttr('disabled');
			$('input[name=' + scard +']').removeAttr('disabled');
			document.getElementById("sg"+sname).innerHTML="*";
			document.getElementById("sg"+scardtype).innerHTML="*";
			document.getElementById("sg"+scard).innerHTML="*";
		}				
	}
}

//与被投保人关系选择（姓名）
function choosetname(k){

	//选择框的确认
	var selecttbName = "tbRelation" + k;
	var selectsbName = "sbrelation" + k;
	//选择的文字
	var selecttbText = $('select[name='+selecttbName+']').val();
	var selectsbText = $('select[name='+selectsbName+']').val();
		
	//姓名的选择
	var tname = "tname" + k;
	//被保人姓名的选择
	var bname = "bname" + k;
	//受益人姓名的选择
	var sname = "sname" + k;	

	//被保人关系选择本人的情况下，被保人姓名身份证号码不用填写
	if(selecttbText=="本人"){
		$('input[name=' + bname +']').val($('input[name=' + tname +']').val());
	}
	if(selectsbText=="本人"){
		$('input[name=' + sname +']').val($('input[name=' + bname +']').val());
	}
}



//与被投保人关系选择（身份证）
function choosetcard(k){

	//选择框的确认
	var selecttbName = "tbRelation" + k;
	var selectsbName = "sbrelation" + k;
	//选择的文字
	var selecttbText = $('select[name='+selecttbName+']').val();
	var selectsbText = $('select[name='+selectsbName+']').val();
		
	//证件号码的选择
	var tcard = "tcard" + k;
	//被保人证件号码的选择
	var bcard = "bcard" + k;
	//受益人证件号码的选择
	var scard = "scard" + k;

	//被保人关系选择本人的情况下，被保人姓名身份证号码不用填写
	if(selecttbText=="本人"){
		$('input[name=' + bcard +']').val($('input[name=' + tcard +']').val());
	}
	if(selectsbText=="本人"){
		$('input[name=' + scard +']').val($('input[name=' + bcard +']').val());
	}
}


//与被投保人关系选择（姓名）
function choosetsname(k){

	//选择框的确认
	var selectsbName = "sbrelation" + k;
	//选择的文字
	var selectsbText = $('select[name='+selectsbName+']').val();
		
	//被保人姓名的选择
	var bname = "bname" + k;
	//受益人姓名的选择
	var sname = "sname" + k;	

	//被保人关系选择本人的情况下，被保人姓名身份证号码不用填写
	if(selectsbText=="本人"){
		$('input[name=' + sname +']').val($('input[name=' + bname +']').val());
	}
}

//与被投保人关系选择（证件类型）
function choosetscardtype(k){

	//选择框的确认
	var selectsbName = "sbrelation" + k;
	//选择的文字
	var selectsbText = $('select[name='+selectsbName+']').val();
	
	//被保人证件类型的选择
	var bcardtype = "bcardtype" + k;		
	//受益人证件类型的选择
	var scardtype = "scardtype" + k;	

	
	//被保人关系选择本人的情况下，被保人姓名身份证号码不用填写
	if(selectsbText=="本人"){
		$('select[name=' + scardtype +']').val($('select[name=' + bcardtype +']').val());
	}
}

//与被投保人关系选择（身份证）
function choosetscard(k){

	//选择框的确认
	var selectsbName = "sbrelation" + k;
	//选择的文字
	var selectsbText = $('select[name='+selectsbName+']').val();
		
	//被保人证件号码的选择
	var bcard = "bcard" + k;
	//受益人证件号码的选择
	var scard = "scard" + k;

	//被保人关系选择本人的情况下，被保人姓名身份证号码不用填写
	if(selectsbText=="本人"){
		$('input[name=' + scard +']').val($('input[name=' + bcard +']').val());
	}
}



function ChangeCalPremObjiect(propertyStr,propertyValue )    {
  //用生日计算年龄
	var nowDay = new Date();
	var year = nowDay.getFullYear();//获取当前年
	var month = nowDay.getMonth()+1;//获取当前月
	var nowdate = nowDay.getDate();//获取当前日
	//投保人
  if(propertyStr == "insurebirth" && $("[name=insureage]")){
  	if(propertyValue!=""){
      var dateStr = propertyValue.split("-"); //字符分割    
     
      var age = parseInt(year) - parseInt(dateStr[0],10) - 1;
      var month_mon = parseInt(month) - parseInt(dateStr[1],10);
      var nowdate_mon = parseInt(nowdate) - parseInt(dateStr[2],10);
      if(month_mon > 0){
      	age = age + 1;
      }
      if( month_mon == 0 && nowdate_mon >= 0){
      	age = age + 1;
      }
      
      
	    $("[name=insureage]").val(age);
	    $("[name=insureage]").attr('readonly','readonly');
    	$("[name=insureage]:first").attr("onclick","");	
			$("[name=insureage]").addClass("colors");
  	}else{
  		$("[name=insureage]").val("");
    }
  }
  
	//被保险人
  if(propertyStr == "birth" && $("[name=age]")){
  	if(propertyValue!=""){
      var dateStr = propertyValue.split("-"); //字符分割    
       var age = parseInt(year) - parseInt(dateStr[0],10) - 1;
      var month_mon = parseInt(month) - parseInt(dateStr[1],10);
      var nowdate_mon = parseInt(nowdate) - parseInt(dateStr[2],10);
      if(month_mon > 0){
      	age = age + 1;
      }
      if( month_mon == 0 && nowdate_mon >= 0){
      	age = age + 1;
      }
      
	    $("[name=age]").val(age);
  	}else{
  		$("[name=age]").val("");
    }
  }	  		
	
	//受益人
  if(propertyStr == "benbirth"){

  }
}

//根据身份证得到相应的人的生日（大模板复杂产品）
function checkcard(obj,cardtypeName,birthdayName,sexName){
	alert("5");
  if("insurecardno"==cardtypeName) {
		document.getElementsByName(cardtypeName)[0].value = "";
		$("[name="+sexName+"]").removeClass("colors");
		$("[name="+birthdayName+"]").removeClass("colors");
		$("[name=insureage]").removeClass("colors");
		
		$("[name="+sexName+"]").attr('readonly',false);
		$("[name=insureage]").attr('readonly',false);
		if(obj.value!=1) {
		$("[name="+birthdayName+"]").bind('click',function(){WdatePicker();}); 
	  }
		var num = $("[name="+sexName+"]");
	  		num.empty();
	  		var sel = $("<option>").text("请选择").val('');
	  		var female = $("<option>").text("女").val('0');
	  		var male = $("<option>").text("男").val('1');
	  		num.append(sel);
		  	num.append(female);
		  	num.append(male);
  } else {
	 var testSex="13579";//基数为男性
   var cardType=document.getElementsByName(cardtypeName)[0].value;
	 if(cardType!="" && obj.value!='' && "1"==cardType && "insurecardtype"==cardtypeName) {
	    var errorNo = isIdCardNo(obj.value);
		  if(!errorNo == 0){
		  	alert("请填写正确的身份证号码");
				obj.select();
			  return false;	
		  }
		}
	
		var idNumber=obj.value;
	  if(cardType!="" && "1"==cardType ){
	    if(idNumber.length==18){
	    	var date=idNumber.substring(6,14); //19790301
	    	var year=date.substring(0,4);
	    	var moth=date.substring(4,6);
	    	var day=date.substring(6,date.length);
	    	var birthday=year + "-" + moth + "-" + day;
	    	document.getElementsByName(birthdayName)[0].value=birthday;
	  		$("[name="+birthdayName+"]").attr('readonly','readonly');
	  		$("[name="+birthdayName+"]").unbind("click");
	    	$("[name="+birthdayName+"]:first").attr("onclick","");
				$("[name="+birthdayName+"]").addClass("colors");
	    	ChangeCalPremObjiect(birthdayName,birthday);
		    if(testSex.indexOf(idNumber.substring(idNumber.length-2,idNumber.length-1))!=-1){
		    	$("[name="+sexName+"] option[value='0']").removeAttr("selected");
	        $("[name="+sexName+"] option[value='1']").attr("selected","selected");
	        
	        var num = $("[name="+sexName+"]");
		  		num.empty();
		  		var option = $("<option>").text("男").val('1');
			  	num.append(option);
	      	//设置元素不可修改
	      	$("[name="+sexName+"]").attr('readonly','readonly');
	      	$("[name="+sexName+"]:first").attr("onclick","");
					$("[name="+sexName+"]").addClass("colors");
		  	}else{
		  		$("[name="+sexName+"] option[value='1']").removeAttr("selected");
		  	  $("[name="+sexName+"] option[value='0']").attr("selected","selected");
		  	  
		  	  var num = $("[name="+sexName+"]");
		  		num.empty();
		  		var option = $("<option>").text("女").val('0');
			  	num.append(option);
	      	//设置元素不可修改
	      	$("[name="+sexName+"]").attr('readonly','readonly');
	      	$("[name="+sexName+"]:first").attr("onclick","");
					$("[name="+sexName+"]").addClass("colors");
		  	}
	    }else if(idNumber.length==15) {
	    	var date=idNumber.substring(6,12); //790301
	    	var year="19"+date.substring(0,2);
	    	var moth=date.substring(2,4);
	    	var day=date.substring(4,date.length);
	    	var birthday=year + "-" + moth + "-" + day;
	    	document.getElementsByName(birthdayName)[0].value=birthday;
	    	$("[name="+birthdayName+"]").attr('readonly','readonly');
	    	$("[name="+birthdayName+"]").unbind("click");
	    	$("[name="+birthdayName+"]:first").attr("onclick","");
				$("[name="+birthdayName+"]").addClass("colors");
			ChangeCalPremObjiect(birthdayName,birthday);
	    	if(testSex.indexOf(idNumber.substring(idNumber.length-1,idNumber.length))!=-1){
		    	$("[name="+sexName+"] option[value='0']").removeAttr("selected");
	        $("[name="+sexName+"] option[value='1']").attr("selected","selected");
	        
	        var num = $("[name="+sexName+"]");
		  		num.empty();
		  		var option = $("<option>").text("男").val('1');
			  	num.append(option);
	      	//设置元素不可修改
	      	$("[name="+sexName+"]").attr('readonly','readonly');
	      	$("[name="+sexName+"]:first").attr("onclick","");
					$("[name="+sexName+"]").addClass("colors");
		  	}else{
		  		$("[name="+sexName+"] option[value='1']").removeAttr("selected");
		  	  $("[name="+sexName+"] option[value='0']").attr("selected","selected");
		  	  
		  	  var num = $("[name="+sexName+"]");
		  		num.empty();
		  		var option = $("<option>").text("女").val('0');
			  	num.append(option);
	      	//设置元素不可修改
	      	$("[name="+sexName+"]").attr('readonly','readonly');
	      	$("[name="+sexName+"]:first").attr("onclick","");
					$("[name="+sexName+"]").addClass("colors");
		  	}
	    }
	  }
  }
}


function checkbencard(obj,cardtypeName,birthdayName,num){
  if("bencardno"+num==cardtypeName) {
		$("[name="+cardtypeName+"]").val("");
		$("[name="+birthdayName+"]").removeClass("colors");
		if(obj.value!=1) {
		$("[name="+birthdayName+"]").bind('click',function(){WdatePicker();}); 
	  }
  } else {

		var testSex="13579";//基数为男性
	  var cardType=$("[name="+cardtypeName+"]").val();
	  if(cardType!="" && obj.value!='' && "1"==cardType && "bencardtype"+num==cardtypeName) {
	    var errorNo = isIdCardNo(obj.value);
		  if(!errorNo == 0){
		  	alert("请填写正确的身份证号码");
				obj.select();
			  return false;	
		  }
		}
		
		var idNumber=obj.value;
	  if(cardType!="" && "1"==cardType ){
	    if(idNumber.length==18){
	    	var date=idNumber.substring(6,14); //19790301
	    	var year=date.substring(0,4);
	    	var moth=date.substring(4,6);
	    	var day=date.substring(6,date.length);
	    	var birthday=year + "-" + moth + "-" + day;
	    	
	    	$("[name="+birthdayName+"]").val(birthday);
	  		$("[name="+birthdayName+"]").attr('readonly','readonly');
	  		$("[name="+birthdayName+"]").unbind("click"); 
	    	$("[name="+birthdayName+"]:first").attr("onclick","");
	    		
				$("[name="+birthdayName+"]").addClass("colors");
	    }else if(idNumber.length==15) {
	    	var date=idNumber.substring(6,12); //790301
	    	var year="19"+date.substring(0,2);
	    	var moth=date.substring(2,4);
	    	var day=date.substring(4,date.length);
	    	var birthday=year + "-" + moth + "-" + day;
	    	$("[name="+birthdayName+"]").val(birthday);
	    	$("[name="+birthdayName+"]").attr('readonly','readonly');
	    	$("[name="+birthdayName+"]").unbind("click");
	    	$("[name="+birthdayName+"]:first").attr("onclick","");
				$("[name="+birthdayName+"]").addClass("colors");
	 
	    }
	  }
  }
}
//监听回车事件
if (document.addEventListener){//如果是Firefox
	document.addEventListener("keypress", fireFoxHandler, true);
} else{
	document.attachEvent("onkeypress", ieHandler);
}
function fireFoxHandler(evt){
	//alert("firefox");
	if (evt.keyCode == 13){
		//orderSubmit();
	}
}
function ieHandler(evt){
	//alert("IE");
	if (evt.keyCode == 13){
		//orderSubmit();
	}
}

function clearNoNum(obj){   
 //先把非数字的都替换掉，除了数字和.   
 obj.value = obj.value.replace(/[^\d.]/g,"");   
  //必须保证第一个为数字而不是.   
 obj.value = obj.value.replace(/^\./g,"");   
  //保证只有出现一个.而没有多个.   
  obj.value = obj.value.replace(/\.{2,}/g,".");   
  //保证.只出现一次，而不能出现两次以上   
  obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");   
}   

function checkinsurecardCardNo(obj,cardType) {
	if("inserdcardno"==cardType && $("[name="+cardType+"]").val()!=1){
		 $("[name="+cardType+"]").val("");
	}
	if($("[name="+cardType+"]").val()==1 && !isNull(obj.value) && isIdCardNo(obj.value)!=0){
		alert("证件号码输入有误，请重新输入。");
		obj.select();
		return;
	}
	if ("1" == $("[name=cardtype]").val()) {
		  $("[name=drivinglicno]").val($("[name=inserdcardno]").val());
	}else{
		 $("[name=drivinglicno]").val("");
	}	
}

var sexflag = true;
var birthdayflag = true;
var ageflag = true;
//被保人身份证
function checkinserdcard(obj,cardtypeName,birthdayName,sexName){
  if("inserdcardno"==cardtypeName) {
  	document.getElementsByName(cardtypeName)[0].value = "";
  	if(sexflag){//性别是否可修改
  		$("[name="+sexName+"]").removeClass("colors");
  		$("[name="+sexName+"]").attr('readonly',false);
			var num = $("[name="+sexName+"]");
			num.empty();
			var sel = $("<option>").text("请选择").val('');
			var female = $("<option>").text("女").val('0');
			var male = $("<option>").text("男").val('1');
			num.append(sel);
	  	num.append(female);
	  	num.append(male);  		
  	}
  	if(birthdayflag){//生日是否可修改
  		$("[name="+birthdayName+"]").removeClass("colors");
  		$("[name="+birthdayName+"]")[0].value="";
			if(obj.value!=1) {
				$("[name="+birthdayName+"]").bind('click',function(){WdatePicker();}); 
		  }  		
  	}
  	if(ageflag){//年龄是否可修改
  		$("[name=age]").removeClass("colors");
  		$("[name=age]").attr('readonly',false);
  		$("[name=age]")[0].value="";
  	}  	
  } else {
  	var testSex="13579";//基数为男性
	  var cardType=document.getElementsByName(cardtypeName)[0].value;
	  
		var idNumber=obj.value;
	  if(cardType!="" && "1"==cardType ){
	    if(idNumber.length==18){
		  	if(birthdayflag){//生日是否可修改
		    	var date=idNumber.substring(6,14); //19790301
		    	var year=date.substring(0,4);
		    	var moth=date.substring(4,6);
		    	var day=date.substring(6,date.length);
		    	var birthday=year + "-" + moth + "-" + day;
		    	document.getElementsByName(birthdayName)[0].value=birthday;		  		
		  		$("[name="+birthdayName+"]").unbind("click");
		    	$("[name="+birthdayName+"]:first").attr("onclick","");
		    	$("[name="+birthdayName+"]").attr('readonly','readonly');
					$("[name="+birthdayName+"]").addClass("colors");
					if(ageflag){//年龄是否可修改
						ChangeCalinserdAge(birthdayName,birthday);	
					}
		  	}else{
		  		var date=idNumber.substring(6,14); //19790301
		    	var year=date.substring(0,4);
		    	var moth=date.substring(4,6);
		    	var day=date.substring(6,date.length);
		    	var birthday=year + "-" + moth + "-" + day;
		  		if(document.getElementsByName(birthdayName)[0].value!=birthday){
		  			alert("被保人身份证号码和被保人出生日期不匹配！请重新填写。");
		  			document.getElementsByName('inserdcardno')[0].value="";
						obj.select();
						return;  		  			
		  		}
		  		
		  		//用生日计算年龄
					var nowDay = new Date();
					var year = nowDay.getFullYear();//获取当前年
					var month = nowDay.getMonth()+1;//获取当前月
					var nowdate = nowDay.getDate();//获取当前日
		  		var dateStr = birthday.split("-"); //字符分割    
		      var age = parseInt(year) - parseInt(dateStr[0],10) - 1;
		      var month_mon = parseInt(month) - parseInt(dateStr[1],10);
		      var nowdate_mon = parseInt(nowdate) - parseInt(dateStr[2],10);
		      if(month_mon > 0){
		      	age = age + 1;
		      }
		      if( month_mon == 0 && nowdate_mon >= 0){
		      	age = age + 1;
		      }
		  		if(document.getElementsByName("age")[0].value!=age){
		  			alert("被保人身份证号码和被保人年龄不匹配！请重新填写。");
		  			document.getElementsByName('inserdcardno')[0].value="";
						obj.select();
						return;  		 	  	
		  		}		  		
		  	}		    
		  	if(sexflag){//性别是否可修改
			    if(testSex.indexOf(idNumber.substring(idNumber.length-2,idNumber.length-1))!=-1){
			    	$("[name="+sexName+"] option[value='0']").removeAttr("selected");
		        $("[name="+sexName+"] option[value='1']").attr("selected","selected");
		        
		        var num = $("[name="+sexName+"]");
			  		num.empty();
			  		var option = $("<option>").text("男").val('1');
				  	num.append(option);
		      	//设置元素不可修改
		      	$("[name="+sexName+"]:first").attr("onclick","");
		      	//$("[name="+sexName+"]").attr('readonly','readonly');
						$("[name="+sexName+"]").addClass("colors");
			  	}else{
			  		$("[name="+sexName+"] option[value='1']").removeAttr("selected");
			  	  $("[name="+sexName+"] option[value='0']").attr("selected","selected");
			  	  
			  	  var num = $("[name="+sexName+"]");
			  		num.empty();
			  		var option = $("<option>").text("女").val('0');
				  	num.append(option);
		      	//设置元素不可修改
		      	$("[name="+sexName+"]").attr('readonly','readonly');
		      	$("[name="+sexName+"]:first").attr("onclick","");
						$("[name="+sexName+"]").addClass("colors");
			  	}
		  	}else{
		  		var sexnum;
		  		if(testSex.indexOf(idNumber.substring(idNumber.length-2,idNumber.length-1))!=-1){
		  			sexnum = "1";
	  			}else{
	  				sexnum = "0";
	  			}
		  		if(document.getElementsByName(sexName)[0].value!=sexnum){
		  			alert("被保人身份证号码和被保人性别不匹配！请重新填写。");
		  			document.getElementsByName('inserdcardno')[0].value="";
						obj.select();
						return;
		  		}
		  	}	
	    }else if(idNumber.length==15) {
	    	if(birthdayflag){//生日是否可修改
		    	var date=idNumber.substring(6,12); //790301
		    	var year="19"+date.substring(0,2);
		    	var moth=date.substring(2,4);
		    	var day=date.substring(4,date.length);
		    	var birthday=year + "-" + moth + "-" + day;
		    	document.getElementsByName(birthdayName)[0].value=birthday;
		    	$("[name="+birthdayName+"]").attr('readonly','readonly');
		    	$("[name="+birthdayName+"]").unbind("click");
		    	$("[name="+birthdayName+"]:first").attr("onclick","");
					$("[name="+birthdayName+"]").addClass("colors");
					if(ageflag){//年龄是否可修改
						ChangeCalinserdAge(birthdayName,birthday);
					}					
				}else{
		  		var date=idNumber.substring(6,14); //19790301
		    	var year=date.substring(0,4);
		    	var moth=date.substring(4,6);
		    	var day=date.substring(6,date.length);
		    	var birthday=year + "-" + moth + "-" + day;
		  		if(document.getElementsByName(birthdayName)[0].value!=birthday){
		  			alert("被保人身份证号码和被保人出生日期不匹配！请重新填写。");
		  			document.getElementsByName('inserdcardno')[0].value="";
						obj.select();
						return;  		 		  			
		  		}
		  		
		  		//用生日计算年龄
					var nowDay = new Date();
					var year = nowDay.getFullYear();//获取当前年
					var month = nowDay.getMonth()+1;//获取当前月
					var nowdate = nowDay.getDate();//获取当前日
		  		var dateStr = birthday.split("-"); //字符分割    
		      var age = parseInt(year) - parseInt(dateStr[0],10) - 1;
		      var month_mon = parseInt(month) - parseInt(dateStr[1],10);
		      var nowdate_mon = parseInt(nowdate) - parseInt(dateStr[2],10);
		      if(month_mon > 0){
		      	age = age + 1;
		      }
		      if( month_mon == 0 && nowdate_mon >= 0){
		      	age = age + 1;
		      }
		  		if(document.getElementsByName("age")[0].value!=age){
		  			alert("被保人身份证号码和被保人年龄不匹配！请重新填写。");
		  			document.getElementsByName('inserdcardno')[0].value="";
						obj.select();
						return;  		 	  	
		  		}		  		
		  	}
				if(sexflag){//性别是否可修改
		    	if(testSex.indexOf(idNumber.substring(idNumber.length-1,idNumber.length))!=-1){
			    	$("[name="+sexName+"] option[value='0']").removeAttr("selected");
		        $("[name="+sexName+"] option[value='1']").attr("selected","selected");
		        
		        var num = $("[name="+sexName+"]");
			  		num.empty();
			  		var option = $("<option>").text("男").val('1');
				  	num.append(option);
		      	//设置元素不可修改
		      	$("[name="+sexName+"]").attr('readonly','readonly');
		      	$("[name="+sexName+"]:first").attr("onclick","");
						$("[name="+sexName+"]").addClass("colors");
			  	}else{
			  		$("[name="+sexName+"] option[value='1']").removeAttr("selected");
			  	  $("[name="+sexName+"] option[value='0']").attr("selected","selected");
			  	  
			  	  var num = $("[name="+sexName+"]");
			  		num.empty();
			  		var option = $("<option>").text("女").val('0');
				  	num.append(option);
		      	//设置元素不可修改
		      	$("[name="+sexName+"]").attr('readonly','readonly');
		      	$("[name="+sexName+"]:first").attr("onclick","");
						$("[name="+sexName+"]").addClass("colors");
			  	}
			  }else{
		  		var sexnum;
		  		if(testSex.indexOf(idNumber.substring(idNumber.length-2,idNumber.length-1))!=-1){
		  			sexnum = "1";
	  			}else{
	  				sexnum = "0";
	  			}
		  		if(document.getElementsByName(sexName)[0].value!=sexnum){
		  			alert("被保人身份证号码和被保人性别不匹配！请重新填写。");
		  			document.getElementsByName('inserdcardno')[0].value="";
						obj.select();
						return;
		  		}
		  	}
	    }
	  }
  }
  if ("1" == $("[name=cardtype]").val()) {
	  $("[name=drivinglicno]").val($("[name=inserdcardno]").val());
}else{
	 $("[name=drivinglicno]").val("");
}
}

function checkedNullChar (){
	var flag = true;
	$("[name=tx_show]").each(function (){
		var spanDom = this;
		var vDom = this.id.replace("_warn","");
		var insDom = $("#"+vDom);
		if(""==insDom.val()){
			this.style.display="";
			flag = false;
			alert(this.innerHTML);
			insDom[0].focus();
			return false;
		}else if(this.innerHTML.indexOf("请")>-1){
			this.style.display="none";
		}
		
	});
	if(flag){
		if(jobCheck){
			if(!jobCheck()){
				flag = false; 
			}
		}
	}
	return flag;
}
