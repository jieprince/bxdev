/*（手机端）公共验证方法*/
//根据生日计算年龄  dom_id只有华安学平险目前使用  dom_id  是为了被保险人年龄的计算  以保险起期来开始投保的
function getAgeByBirthday(bir,dom_id){
	if(trim(bir)==""){
		return "";
	}else if(/^(\d{4})(-)(\d{1,2})(-)(\d{1,2})$/.test(bir)){
		var birthday= new Date(bir.replace(/-/g, "\/")); 
		var d=new Date(); 
		if($('#xuepingxian').val()=='y' && dom_id=="assured_birthday"){
			d=new Date($('#startDate').val().substring(0,10).replace(/-/g, "\/")); 
		}
		
		var age = d.getFullYear()-birthday.getFullYear()-((d.getMonth()<birthday.getMonth()|| d.getMonth()==birthday.getMonth() && d.getDate()<birthday.getDate())?1:0);
		
		if(typeof(age)=="undefined"){
			//alert("getAgeByBirthday，变量无效");
			age = -1;
		}
		else if(isNaN(age)){
			//alert("getAgeByBirthday，年龄是非数字");
			age = -1;	
		}
		else{
			//alert("getAgeByBirthday: "+age);
		}
	}else{
		age = -1;
	}
	return age;
}

/*机构代码验证(验证格式为:88829888-0)  注：目前以华安提供的机构代码来验证(目前使用于华安的产品) */

function isOrganizationCodeNo(codestr){
    if(codestr.length==9 || codestr.length==10){
        var reg_text = /^(([0-9A-Za-z]{8})-)([0-9A-Za-z]{1})$/;
        var reg_text2 = /^([0-9A-Za-z]{8})([0-9A-Za-z]{1})$/;
        if(reg_text.test(codestr) || reg_text2.test(codestr)){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

//2.1.日期型转换为格式化的字符串  YYYYMMDD
function convDate(sDate, sSep) {
    var pos = 0;
    var str = sDate;
    var len = str.length;
    if ( (len < 8) || (len > 10)) {
  return str;
}
else if (str.indexOf(sSep) == 4) {
  pos = str.indexOf(sSep, 5);
  if (pos == 6) {
    if (len == 8) {
      return str.substring(0, 4) + "0" + str.substring(5, 6) + "0" +
          str.substring(7, 8);
    }
    else {
      return str.substring(0, 4) + "0" + str.substring(5, 6) +
          str.substring(7, 9);
    }
  }
  else if (pos == 7) {
    if (len == 9) {
      return str.substring(0, 4) + str.substring(5, 7) + "0" +
          str.substring(8, 9);
    }
    else {
      return str.substring(0, 4) + str.substring(5, 7) + str.substring(8, 10);
    }
  }
  else {
    return str;
  }
}
else {
  return str;
}
}
//3.判断是否为闰年
function checkLeapYear(year) {
    if ( ( (year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0)) {
	  return true;
	}
	return false;
}
//6.检查是否为数字型元素
function checkNumber(str) {
    var i;
    var len = str.length;
    var chkStr = "1234567890";
    if (len == 1) {
  if (chkStr.indexOf(str.charAt(i)) < 0) {
    return false;
  }
}
else {

  if ( (chkStr.indexOf(str.charAt(0)) < 0)) {
    return false;
  }
  for (i = 1; i < len; i++) {
    if (chkStr.indexOf(str.charAt(i)) < 0) {
      return false;
    }
  }
}
return true;
}
//7.检查是否为浮点型元素
    function checkFloat(str) {
    var i;
    var len = str.length;
    var chkStr = "1234567890.";
    if (len == 1) {
  if (chkStr.indexOf(str.charAt(i)) < 0) {
    return false;
  }
}
else {
  //if ((chkStr.indexOf(str.charAt(0)) < 0) || (str.charAt(0) == "0")) {
  if ( (chkStr.indexOf(str.charAt(0)) < 0)) {
    return false;
  }
  for (i = 1; i < len; i++) {
    if (chkStr.indexOf(str.charAt(i)) < 0) {
      return false;
    }
  }
}
return true;
}
//8.检查是否为日期
    function checkDate(str) {
    str = convDate(str, "/");
    if ( (str.length != 8) || !checkNumber(str)) {
  return false;
}
var year = str.substring(0, 4);
    var month = str.substring(4, 6);
    var day = str.substring(6, 8);
    dayOfMonth = new Array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    if ( (month < 1) || (month > 12)) {
  return false;
}
if ( (day < 1) || (day > dayOfMonth[month - 1])) {
  return false;
}
if (!checkLeapYear(year) && (month == 2) && (day == 29)) {
  return false;
}
return true;
}

//8.检查是否为日期(6位)
function checkDate6(str) {
    str = convDate(str, "/");
    if ( (str.length != 6) || !checkNumber(str)) {
      return false;
    }
    var year = str.substring(0, 2);
    var month = str.substring(2, 4);
    var day = str.substring(4, 6);
    dayOfMonth = new Array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    if ( (month < 1) || (month > 12)) {
      return false;
    }
	if ( (day < 1) || (day > dayOfMonth[month - 1])) {
	  return false;
	}
//	if (!checkLeapYear(year) && (month == 2) && (day == 29)) {
//	  return false;
//	}
	return true;
}

/*************************************************身份证号码验证-支持新的带x身份证***********************************************************/
//--身份证号码验证-支持新的带x身份证
function isIdCardNo(idcardno){
	var factorArr = new Array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2,1);
	var error;
	var errorNo = 0;
	var isRight = true;
	var varArray = new Array();
	var intValue;
	var lngProduct = 0;
	var intCheckDigit;
	var intStrLen = idcardno.length;
	var idNumber = idcardno;
	//initialize
	if((intStrLen != 15) && (intStrLen != 18) && isRight){
		error = "身份证长度不正确！";
		errorNo = 1;
		isRight = false;
	}
	//checkandsetvalue
	for(var i = 0;i < intStrLen;i++){
		varArray[i] = idNumber.charAt(i);
		if((varArray[i] < '0'||varArray[i] > '9') && (i != 17) && isRight){
			error = "错误的身份证号码！";
			errorNo = 2;
			isRight = false;
		}else if(i < 17 && isRight){
			varArray[i] = varArray[i] * factorArr[i];
		}
	}
	if(intStrLen == 18 && isRight){
		//checkdate
		var date8 = idNumber.substring(6,14);
		if(checkDate(date8) == false && isRight){
			error = "身份证中日期信息不正确！";
			errorNo = 3;
			isRight = false;
		}
		//calculatethesumoftheproducts
		for(i = 0;i < 17;i++){
			lngProduct = lngProduct + varArray[i];
		}
		//calculatethecheckdigit
		intCheckDigit = 12 - lngProduct % 11;
		switch(intCheckDigit){
			case 10:
				intCheckDigit = 'X';
				break;
			case 11:
				intCheckDigit = 0;
				break;
			case 12:
				intCheckDigit = 1;
				break;
		}
		//checklastdigit
		if(varArray[17].toUpperCase() != intCheckDigit && isRight){
			error = "身份证效验位错误!...正确为："+intCheckDigit+".";
			errorNo = 4;
			isRight = false;
		}
	}else{//lengthis15
		//checkdate
		var date6=idNumber.substring(6,12);
		if(checkDate6(date6) == false && isRight){
			error = "身份证日期信息有误！";
			errorNo = 5;
			isRight = false;
		}
	}
	
	return errorNo;
}
function errorIdcardMessage(errorNo){
	var errorMessage = "";
	switch(errorNo){
		case 1:
			errorMessage = "身份证长度不正确！";
			break;
		case 2:
			errorMessage = "错误的身份证号码！";
			break;
		case 3:
			errorMessage = "身份证中日期信息不正确！";
			break;
		case 4:
			errorMessage = "身份证效验位错误！";
			break;
		case 5:
			errorMessage = "身份证日期信息有误！";
			break;
	}
	return errorMessage;
}

//对输入框的值格式化
function clearNotNum(obj){
	//先把非数字的都替换掉，除了数字和.
	obj.value = obj.value.replace(/[^\d.]/g,"");
	//必须保证第一个为数字而不是.
	obj.value = obj.value.replace(/^\./g,"");
	//保证只有出现一个.而没有多个.
	obj.value = obj.value.replace(/\.{2,}/g,".");
	//保证.只出现一次，而不能出现两次以上
	obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
 }

//判断中文和英文的长度////////////////////////////////////////////
function strlen(str) {
    var len = 0;
    for (var i = 0; i < str.length; i++) {
        var c = str.charCodeAt(i);
        //单字节加1 
        if ((c >= 0x0001 && c <= 0x007e) || (0xff60 <= c && c <= 0xff9f)) {
            len++;
        }else {
            len += 2;
        }
    }
    return len;
}

//两个时间的比较
function date_Diff_day(date1,date2){
	var qssj = date1.split('-');  
    var jssj = date2.split('-');
	if(qssj[1].substring(0,1)==0){
		qssj[1] = qssj[1].substring(1,2);
	}
	if(jssj[1].substring(0,1)==0){
		jssj[1] = jssj[1].substring(1,2);
	}
	var d1 = new Date(qssj[0], qssj[1], qssj[2]);
    var d2 = new Date(jssj[0], jssj[1], jssj[2]);
	 
	if(d1>d2){
		return 0;
	} else{
		return 1;
	}
}


//把时间进行转换
 //把时间进行转换
 function timeStamp2String(dateText,day,datetype){
    var period_uint = $('#period_uint').val();
	var datetime = new Date(Date.parse(dateText.replace(/-/g, "/")));
	if(datetype){
		period_uint = datetype;
	} 
	if(period_uint=="year"){ //按年计算
		datetime.setYear(datetime.getFullYear()+parseInt(day));
	}else if(period_uint=="month"){//按月计算
		datetime.setMonth(datetime.getMonth()+parseInt(day));
	}else if(period_uint=="day"){
		datetime.setDate(datetime.getDate()+parseInt(day)); 
	}
	var t = datetime.getTime();//毫秒，起始时间
	if(period_uint!="year" && period_uint!="month" && period_uint!="day"){
		if(day==365){
			t = t-1000;//减少一秒	
		}else{
			t = t + day*24*3600*1000-1000;//减少一秒
			
		}
	}else{
		t = t-1000;//减少一秒	
	}
	
	datetime = new Date(t);
	
	//alert("sdfsdf");
	
    //datetime.setTime(time);
    var year = datetime.getFullYear();
	if(period_uint!="year" && period_uint!="month" && period_uint!="day"){
		if(day==365){
			year = 	parseInt(year)+1;
		}
	}
    //var month = datetime.getMonth() + 1 < 10 ? "0" + (datetime.getMonth() + 1) : datetime.getMonth() + 1;
	var month = datetime.getMonth() + 1 < 10 ? "0" + (datetime.getMonth() + 1) : datetime.getMonth() + 1;//����12���¡�
	
    var date = datetime.getDate() < 10 ? "0" + datetime.getDate() : datetime.getDate();
    var hour = datetime.getHours()< 10 ? "0" + datetime.getHours() : datetime.getHours();
    var minute = datetime.getMinutes()< 10 ? "0" + datetime.getMinutes() : datetime.getMinutes();
    var second = datetime.getSeconds()< 10 ? "0" + datetime.getSeconds() : datetime.getSeconds();
    //return year + "-" + month + "-" + date+" "+hour+":"+minute+":"+second;
	if(period_uint!="year" && period_uint!="month" && period_uint!="day"){
		if(day==365){
			if(year%4!=0&&month=='02'&&parseInt(date)>28){
				date = 28;
			}else if(year%4==0&&month=='02'&&parseInt(date)==28){
				date = 29;
			}
		} 
	}
	return year + "-" + month + "-" + date + " "+ hour+":"+minute+":"+second;
}


//把时间增加月份进行转换
function monthStamp2String(dateText,monthT){
    //var datetime = new Date();
	var datetime = new Date(Date.parse(dateText.replace(/-/g, "/")));  
	datetime.setMonth(datetime.getMonth()+parseInt(monthT));
	var t = datetime.getTime();//毫秒，起始时间
	t = t -1000;
	datetime = new Date(t);
	 
    var year = datetime.getFullYear();
    var month = datetime.getMonth() + 1 < 10 ? "0" + (datetime.getMonth() + 1) : datetime.getMonth() + 1;
	 
    var date = datetime.getDate() < 10 ? "0" + datetime.getDate() : datetime.getDate();
    var hour = datetime.getHours()< 10 ? "0" + datetime.getHours() : datetime.getHours();
    var minute = datetime.getMinutes()< 10 ? "0" + datetime.getMinutes() : datetime.getMinutes();
    var second = datetime.getSeconds()< 10 ? "0" + datetime.getSeconds() : datetime.getSeconds();
    //return year + "-" + month + "-" + date+" "+hour+":"+minute+":"+second;
	return year + "-" + month + "-" + date + " "+ hour+":"+minute+":"+second;
}



//弹出不联动的  公用  
function showSelect_base(domid,domPid,val_str,layertype){
	var str_data = $('#'+domPid).data('value');
	if(typeof(str_data)=="string"){
		str_data = $.parseJSON(str_data);
	}
	var initflag = true;
	if(layertype){ //判定 存在 不弹出选择框
		initflag = false;
	}
	showSelect(initflag,domid,val_str+'_name',str_data);
}

//投保人证件类型选择  带回调函数(没有使用)
function showSelect_app_certificates_type(domid,domPid,val_str,layertype){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(layertype){ //判定 存在 不弹出选择框
		initflag = false;
	}
	showSelect(initflag,domid,val_str+'_name',str_data,callback_app_certificates_type);
}

//获取data-*的数据 并且判定不为空
function checked_datavalueAll(domid,data_str){
	var str_datavalueTemp = $('#'+domid).data(data_str);
	var return_data_flag = true;
	
	if(str_datavalueTemp!=0 && (str_datavalueTemp=="" || str_datavalueTemp==undefined || str_datavalueTemp==null)){
		return_data_flag = false;
		 
	}else{
		return_data_flag = true;	
	}
	var json_data = {'flag':return_data_flag,'value':str_datavalueTemp};
	return json_data;
	
}


//阅读并同意 取消和确定
function updatechecked(ef){
	var flag_c = $(ef).data("value");
	if(flag_c=='Y'){
		
		$(ef).attr("class","fa fa-square-o s-c-ed462f f-mr10");	
		$(ef).data("value","N");
	}else{
		$(ef).attr("class","fa fa-check-square s-c-ed462f f-mr10");	
		$(ef).data("value","Y");
	}
}


//阅读并同意 验证
function ydtychecked(){
	var ydtyflag = false;    
	var bxtk_fg = $('#bxtk_01').data("value");
	var tbxz_fg = $('#tbxz_01').data("value");
	var tbsm_fg = $('#tbsm_01').data("value");
	if(bxtk_fg=='Y' && tbxz_fg=='Y' && tbsm_fg=='Y'){
		ydtyflag = true;
	}
	return ydtyflag;
}


//提示框内容提示 验证信息  
function showMessages(message_str,redirect_id){
	layer.open({
		content: '<div style="padding:10px; color:#696969;">'+message_str+'</div>',
		style:"width:100%;",
		btn: ['确定'],
		yes: function(index){
			if(redirect_id){
				$("html,body").animate({scrollTop:$("#"+redirect_id).offset().top-100},1000);
			}
			layer.close(index);
		}
	});	
	
}


/*------------------------------------------------以下是验证方法（适用于移动端和pc端，目前使用在移动端）---------------------------------------*/


//验证字符串长度
function checked_str_length(domid,length_num){
	  var str = $('#'+domid).val();
	  var length_Temp = 0;
	  if(domid=='startport' || domid=='transport1' || domid=='endport'){
		  domid = 'startport';
	  }
	  if (str == null || $.trim(str)=="") {
		length_Temp = 0;
	    return false;
	  }else{
		  if (typeof str != "string"){
			str += "";
		  }
		  length_Temp = str.replace(/[^x00-xff]/g,"01").length;
	  }
	  if(length_num<=length_Temp){ 
		  return false;
	  }else{
		   return true; 
	  }
	  
}
//验证为空  只能是数字  并且小于1000000
function isCheckedNull(domid){
	var domid_value = $('#'+domid).val();
	
	if($.trim(domid_value)=="" || $.trim(domid_value)==null || isNaN($.trim(domid_value))){
	   return false;
	}else{
		if($.trim(domid_value)>0){
			if(domid=='amount'){
				if($('#product_attribute_code').val()=='ZH'){
					if(domid_value<=1000000){
						return true;
					}else{
						return false; 
					}
				}else{ 
					return true; 
				}
			}else{	 
				return true; 
			}
			
		}else{
			 
		    return false;
		}
		
	}
	
}


/*中文姓名验证 (投保人和被保险人) 3-24个字符
	domid 验证的dom节点 
	applicant_type是tbr 投保人  bbxr =被保险人
	type是选择版本  app=移动端  pc=PC端 
*/

function check_applicant_name(domid,applicant_type,type){	
	var tname = $.trim($('#'+domid).val());
	var str_temp = '投保人';
	if(applicant_type=='tbr'){
		str_temp = '投保人';
	}else if(applicant_type=='bbxr'){
		str_temp = '被保险人';
	}else if(applicant_type=='syr'){
		str_temp = '受益人';
	}
	if( tname == ""){
		if(type=='pc'){
			$('#'+domid+'_warn').attr("class","wrong_strong");
			$('#'+domid+'_warn').html("请填写"+str_temp+"姓名");
		}
		return false;
	}else{
	    //var applicant_fullname = tname;//$j("input#applicant_fullname").val();
	   	if(strlen(tname)>24||strlen(tname)<3)
	    {
	    	//alert("投保人姓名不合规，应该大于3个且少于24个字符!");
			if(type=='pc'){
				$('#'+domid+'_warn').attr("class","wrong_strong");
				$('#'+domid+'_warn').html(str_temp+"姓名不合规，应该大于3个且少于24个字符!");
			}
	    	return false;
	    }else{
			if(type=='pc'){
				$('#'+domid+'_warn').attr("class","right_strong");
				$('#'+domid+'_warn').html("");
			}
			return true;
		}
		 
	}
}

//验证英文姓名
function check_en_name(str_cid,applicant_type,type){
	var reg_s = /^([A-Za-z]+\s?)*[A-Za-z]$/;
	var str_value = $('#'+str_cid).val();
	if(reg_s.test(str_value)){
		if(type=='pc'){
			$('#'+str_cid+'_warn').attr("class","right_strong");
			$('#'+str_cid+'_warn').html("");
		}
		return true;	
	}else{
		if(type=='pc'){
			$('#'+str_cid+'_warn').attr("class","wrong_strong");
			$('#'+str_cid+'_warn').html("英文姓名：Tom Jst");
		}
		return false;	
	}
}


/*检查身份证证件号 和组织机构代码验证（组织机构代码只适合华安）
	domid 验证的dom节点 
	type_domid 证件类型的id
	type是选择版本  app=移动端  pc=PC端
	code_int是需要验证(特殊情况) 可以为空 
	注意 ：
		身份证代码  120001 ,01 ,1  已验证 
		组织机构代码证  120011  已验证
		其他都是验证的不为空
*/


function check_applicant_card(domid,type_domid,type,code_int)
{
	 
	var tcardtype = $('#'+type_domid).val();
	
	if(code_int=='120011'){
		tcardtype = code_int;
	}
    if(tcardtype == ""){
		if(type=='pc'){
			$('#'+type_domid+'_warn').attr("class","wrong_strong");
			$('#'+type_domid+'_warn').html("选择填写证件类型");
		}
		return false;
	}	
	 
	var idcard = $('#'+domid).val();
	
	if(idcard == ""){
		if(type=='pc'){
			$('#'+domid+'_warn').attr("class","wrong_strong");
			$('#'+domid+'_warn').html("请填写证件号码");
		} 
		return false;
	}else{
		var retStr = "";
		if(tcardtype == "120001" || tcardtype == "01" || tcardtype == "1")
		{//证件类型是身份证
			var errorNo = isIdCardNo(idcard);
			if(errorNo == 0)//ok
			{
				//$("#tcard_warn").show();
				if(type=='pc'){
					$('#'+domid+'_warn').attr("class","right_strong");
					$('#'+domid+'_warn').html("");
				} 
				 
				return true;
			}
			else
			{
				retStr = errorIdcardMessage(errorNo);
				 
				if(type=='pc'){
					$('#'+domid+'_warn').attr("class","wrong_strong");
					$('#'+domid+'_warn').html(retStr);
				} 
				 
				return false;
			}
			
		}else if(tcardtype == "120011"){
            var errorNo_or = isOrganizationCodeNo(idcard);
            if(errorNo_or){
				if(type=='pc'){
					$('#'+domid+'_warn').attr("class","right_strong");
					$('#'+domid+'_warn').html("");
				} 
                return true;

            }else{
				if(type=='pc'){
					$('#'+domid+'_warn').attr("class","wrong_strong");
					$('#'+domid+'_warn').html("格式为xxxxxxxx-x 或 xxxxxxxxx");
				}
                return false;
            }
        }
		
		if(type=='pc'){
			$('#'+domid+'_warn').attr("class","right_strong");
			$('#'+domid+'_warn').html("");
		} 
	}
	return true;
}

/*验证出生日期  
	domid 验证的dom节点 
	applicant_type是tbr 投保人  bbxr =被保险人
	type是选择版本  app=移动端  pc=PC端
*/
function check_applicant_birthday(domid,applicant_type,type){
	var tbirthday = $('#'+domid).val();
	var str_temp = '投保人';
	var str_min_age = 18;
	var str_max_age = 65;
	if(applicant_type=='tbr'){
		str_temp = '投保人';
	}else if(applicant_type=='bbxr'){
		str_min_age = $('#age_min').val();
		str_max_age = $('#age_max').val();
		str_temp = '被保险人';
	} 
	if(tbirthday == ""){
		if(type=='pc'){
			$('#'+domid+'_warn').attr("class","wrong_strong");
			$('#'+domid+'_warn').html("请填写出生日期");
		}
		 
		return false;
	}else{
		 
		var ttage = getAgeByBirthday(tbirthday);
		if(ttage==-1){
			if(type=='pc'){
				$('#'+domid+'_warn').attr("class","wrong_strong");
				$('#'+domid+'_warn').html(str_temp+"生日格式错误,例子(2010-01-02)");
			}
			return false;
		}else{ 
			if(ttage < 18 || ttage > 65){
				if(type=='pc'){
					$('#'+domid+'_warn').attr("class","wrong_strong");
					$('#'+domid+'_warn').html(str_temp+"生日必须18-65之间");
				}
				 
				return false
			}else{
				if(type=='pc'){
					$('#'+domid+'_warn').attr("class","right_strong");
					$('#'+domid+'_warn').html("");
				}
			}
		}
	}
	
	return true;
}

/*检查手机号  引用了script_common_check.js
	domid 验证的dom节点 
	type是选择版本  app=移动端  pc=PC端
*/
function check_applicant_mobilephone(domid,type){
	var tel_temp = $('#'+domid).val(); 
	if(isNull(tel_temp)) {
		if(type=='pc'){
			$('#'+domid+'_warn').attr("class","wrong_strong");
			$('#'+domid+'_warn').html("请填写手机号码");
		}
		 
		return false;
	}else if(!isMobile(tel_temp,true)) {
		if(type=='pc'){
			$('#'+domid+'_warn').attr("class","wrong_strong");
			$('#'+domid+'_warn').html("手机号码有误");
		}
		return false;
	}else{
		if(type=='pc'){
			$('#'+domid+'_warn').attr("class","right_strong");
			$('#'+domid+'_warn').html("");
		}
	}

	return true;
}

/*检查电子邮箱  引用了script_common_check.js
	domid 验证的dom节点 
	type是选择版本  app=移动端  pc=PC端
*/
function check_applicant_email(domid,type){
	 
	var strvalue_temp = $('#'+domid).val();
	if($.trim(strvalue_temp) == "") {
		if(type=='pc'){
			$('#'+domid+'_warn').attr("class","wrong_strong");
			$('#'+domid+'_warn').html("请填写电子邮箱");
		}
		return false;
	}else if(!isEmail(strvalue_temp, true)) {
		if(type=='pc'){
			$('#'+domid+'_warn').attr("class","wrong_strong");
			$('#'+domid+'_warn').html("电子邮箱有误");
		}
		return false;
	}else{
		if(type=='pc'){
			$('#'+domid+'_warn').attr("class","right_strong");
			$('#'+domid+'_warn').html("");
		}
	}
	return true;
}


/*验证不为空 
	domid 验证的dom节点 
	type是选择版本  app=移动端  pc=PC端
*/
function check_applicant_null(domid,type){
	var temp_value = $.trim($('#'+domid).val());
	if(temp_value==""){
		if(type=='pc'){
			$('#'+domid+'_warn').attr("class","wrong_strong");
			$('#'+domid+'_warn').html("不能为空");
		}
		return false;
	}else{
		if(domid=='project_price'){
			var zaojia_p  = $('#cost_limit').val();
			 var re = /^(([1-9][0-9]*\.[0-9][0-9]*)|([0]\.[0-9][0-9]*)|([1-9][0-9]*)|([0]{1}))$/;   
			if(zaojia_p!=""&&re.test(temp_value)){
				var zaojia_pTemp = zaojia_p.split(',');
				 
				if((parseInt(zaojia_pTemp[0])*10000)>parseFloat(temp_value)||(parseInt(zaojia_pTemp[1])*10000)<=parseFloat(temp_value)){
					if(type=='pc'){
						$('#'+domid+'_warn').attr("class","wrong_strong");
						$('#'+domid+'_warn').html("范围："+zaojia_pTemp[0]+'万元<=X<'+zaojia_pTemp[1]+'万元');
					}
					 
					return false;	
				}else{
					if(type=='pc'){
						$('#'+domid+'_warn').attr("class","right_strong");
						$('#'+domid+'_warn').html("");
					}
					return true;
				}
			}else{
				if(type=='pc'){
					$('#'+domid+'_warn').attr("class","wrong_strong");
					$('#'+domid+'_warn').html("填写数字不正确");
				}
				return false;	
			}
 
		}else{
			if(type=='pc'){
				$('#'+domid+'_warn').attr("class","right_strong");
				$('#'+domid+'_warn').html("");
			}
			return true;
		}
	}
}


 
/*验证固定电话（只有机构才有） （目前华安产品使用）
	domid 验证的dom节点 
	type是选择版本  app=移动端  pc=PC端
*/
function checktelephone(domid,type){
	var str_val = $('#'+domid).val();
	var reg_text = /^((0\d{2,3})-)(\d{7,8})$/;
	if(reg_text.test(str_val)){
		if(type=="pc"){
			$('#'+domid+'_warn').html('').hide();	
		}
		return true;
	}else{
		if(type=="pc"){
			$('#'+domid+'_warn').addClass("wrong_strong");
			$('#'+domid+'_warn').html(' 格式：010-12345678').show();
		}
		return false;
	}
}


/*验证邮政编码
	domid 验证的dom节点 
	type是选择版本  app=移动端  pc=PC端
	
*/
function checkzipcode(domid,type){
	var str_val = $('#'+domid).val();
	var reg_text = /^[1-9][0-9]{5}$/;
	if(reg_text.test(str_val)){
		if(type=="pc"){
			$('#'+domid+'_warn').html('').hide();	
		}
		return true;
	}else{
		if(type=="pc"){
			$('#'+domid+'_warn').addClass("wrong_strong");
			$('#'+domid+'_warn').html(' 格式：100197').show();
		}
		return false;
	}
}

 
/*数字和小数点  不能大于100  受益比例
	domid 验证的dom节点 
	type是选择版本  app=移动端  pc=PC端
	
*/
function check_numberAFloat(domid,type){
	if((/^(\d+(\.\d+)?)$/).test($('#'+domid).val())){
		if(parseFloat($('#'+domid).val())<=100){
			if(type=="pc"){
				$('#'+domid+'_warn').attr("class","right_strong");
				$('#'+domid+'_warn').html("");
			}
			return true;
		}else{
			if(type=="pc"){
				$('#'+domid+'_warn').attr("class","wrong_strong");
				$('#'+domid+'_warn').html("不能大于100");
			}
			return false;
		}
		
	}else{
		$('#'+domid+'_warn').attr("class","wrong_strong");
	  	$('#'+domid+'_warn').html("只能是数字和小数点");
		return false;
	}	
}

/*验证身份证和生日和性别三者一致性
	codedomid 省份证验证的dom节点 
	birdomid 生日验证的dom节点 
	sexdomid 性别验证的dom节点 M F  1 0 
 	type是选择版本  app=移动端  pc=PC端
	showtype 联动生日和性别
*/

function checked_codebirsex(codedomid,birdomid,sexdomid,type,showtype){
	 
	var code_str = $('#'+codedomid).val();
	var bir_str = $('#'+birdomid).val();
	var sex_str = $('#'+sexdomid).data('sex_id');
	
	var man_temp = 'M';
	var fMan_temp = 'F';
	if($('#applicat_sex_type').val()==1){
		man_temp = 0;	
		fMan_temp = 1;
	}else if($('#applicat_sex_type').val()==3){
		man_temp = 1;	
		fMan_temp = 2;
	}
	
	var excels_flag = true;
	var falg_code = isIdCardNo(code_str);
	if(falg_code==0){
		var br_Temp = '';
		var sex_tempint = '';
		var sex_temp = '';
		if(code_str.length==15){
			sex_tempint = code_str.substring(14,15);  //M F
			sex_temp = (((parseInt(sex_tempint))%2) ==0)?fMan_temp:man_temp;
			br_Temp = code_str.substring(6,8)+'-'+code_str.substring(8,10)+'-'+code_str.substring(10,12);
			if(!showtype){
				if(bir_str.substring(2,9)!=br_Temp){
					//省份证  生日   不匹配
					excels_flag = false;
				}
				if(sex_temp!=sex_str){
					excels_flag = false;
				}
			}else{
				excels_flag = true;
			}
		}else if(code_str.length==18){
			sex_tempint = code_str.substring(16,17);
			sex_temp = (((parseInt(sex_tempint))%2) ==0) ?fMan_temp:man_temp;
			br_Temp = code_str.substring(6,10)+'-'+code_str.substring(10,12)+'-'+code_str.substring(12,14);
			if(!showtype){
				if(br_Temp!=bir_str){
					//省份证  生日  性别 不匹配
					excels_flag = false;
					 
				}
				if(sex_temp!=sex_str){
					excels_flag = false;
				}
			}else{
				excels_flag = true;
			}
		}
		if(showtype){
			$('#'+birdomid).val(br_Temp);
			$('#'+sexdomid).data('sex_id',sex_temp);
			if(sex_temp==man_temp){
				$('#'+sexdomid).data('sex_name','男');
				$('#'+sexdomid).text('男');
			}else if(sex_temp==fMan_temp){
				$('#'+sexdomid).data('sex_name','女');
				$('#'+sexdomid).text('女');
			}
			excels_flag = true;
		}
	}else{
		excels_flag = false;	
	}
	
	return excels_flag;
}
 

 
function save_dialog(){
	 
	var cart_insure_id = $("#cart_insure_id").val();
	 $.ajax({
		   type: "GET",
		   url: "cp.php",
		   data: "ac=product_buy&op=save_to_cart&cart_insure_id="+cart_insure_id,
		   success: function(data){
			   data = $.parseJSON(data);
			   if(data['code']==200){
				    layer.open({
						content: '保存成功！您可以到"用户中心->购物车"里批量支付',
						btn: ['去支付', '继续投保'],
						shadeClose: false,
						yes: function(){
							 var url = '../mobile/user.php?act=cart_list';
					         window.location.href=url; 
						}, no: function(){
							var url = '../mobile/goods_list.php?act=hot_goods_list';
					        window.location.href=url;
						}
					});
					 
			   }
		   
		   }
	 });

 }
