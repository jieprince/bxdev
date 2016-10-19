/* 
	字符串操作
	 1、是否是整数				isInt(str,bEmpty)		bEmpty是否为空
	 2、是否是浮点数			isFloat(str,bEmpty)		
	 3、是否所有字符为数字类型	isNumber(str,bEmpty)		bEmpty是否为空
	 4、是否为空				isNull(str)
	 5、是否是日期 xxxx-xx-xx	xxxx/xx/xx     年月日
	 							isDate(str,bEmpty)
	 6、是否是EMAIL				isEmail(str,bEmpty)
	 7、是否是电话号码			isTelNo(str,bEmpty)
	 		是否是手机号码			isMobile(str,bEmpty)
	 	
	 8、是否包含特殊字符		isASCII(str,bEmpty)
	 9、裁减字符串				trim(str,flag)
	 10、计算字符串长度			strlen(str)
	11、将三个字符串连日期格式	concatDate(year,month,day)
	12、比较两个字符串是否相等	equals(str1,str2,length)
	13、比较两个数值的大小,str1大于str2返回-1， 等于返回0， 小于返回1
								compareFloat(str1,str2)
	14、是否包含特殊字符(~`!@#$%^&*()-+=|{}[]:";'<>,./?)
	        					hasSpecSymbol(str,bEmpty) 
	
	控件操作
	 1、是否是回车键			isEnterKey()
	 2、聚焦到下一个控件		keyEnter(keyCode,nextControl) 
	 3、是否是整数				isIntCtrl(ctrl, bEmpty)
	 4、是否是浮点数			isFloatCtrl(ctrl,bEmpty)
	 5、是否为空				isNullCtrl(ctrl,bEmpty)
	 6、是否包含特殊字符		isASCIICtrl(ctrl,bEmpty)
	 7、是否是日期格式			isDateCtrl(ctrl,bEmpty)
	 8、是否是EMAIL				isEmailCtrl(ctrl,bEmpty)
	 9、是否是电话号码			isTelNoCtrl(ctrl,bEmpty)
	10、两控件内容是否相等		equalsCtrl(ctrl1,ctrl2,bEmpty,length)
	11、取得textarea控件的高度  getTextareaHigh(textarea)	
	12、covertDate(strDate) 将日期格式为"2003-08-21"的字符串变为日期对象Date		
	13、compareDate(strStartDate, strEndDate) 判断是否起始日期小于等于结束日期	
	14、getLocation(lc) 在给定连接上面加入时间戳参数	
	15、是否包含特殊字符(~`!@#$%^&*()-+=|{}[]:";'<>,./?)
								hasSpecSymbolCtrl(ctrl,bEmpty)
	16、人民币金额除小数外不能以0开头  IsZeroStart(num)
	
	2008-12-22新加
	//是否是邮编
	function isChinaCode
	是否是汉字 checkIsChinese(str)   
*/
function checkIsChinese(str){
	//如果值为空，通过校验
	if (str == "") 
		return true; 
	var pattern = /^([\u4E00-\u9FA5]|[\uFE30-\uFFA0])*$/gi; 
	if (pattern.test(str)) 
		return true; 
	else 
		return false; 
}


function isInt(str,bEmpty){
	if(str == null || trim(str) == ""){
		return bEmpty;
	}
	var regExe = /^[1-9][0-9]*$/;
	if(!regExe.test(str)){
		return false;
	}
	return true;
}

function isFloat(checkstr, bEmpty, tcount){
	if(checkstr == null || trim(checkstr) == ""){
		return bEmpty;
	}
	var regExe = /^\d\.?\d*$/;
	if(!regExe.test(checkstr)){
		return false;
	}
	return true;
}

//是否是邮编
function isChinaCode(str,bEmpty,checkLength){
	if(str == null || trim(str) == ""){
		return bEmpty;
	}
	if(checkLength == null || checkLength == ""){
		checkLength = 6;
	}
	var strLength = str.length;
	var regExe = /^[0-9]+$/;
	if(strLength != checkLength || !regExe.test(str)){
		return false;
	}
	return true;
}

function isNumber(str,bEmpty){
	if(str == null || trim(str) == ""){
		return bEmpty;
	}
	var regExe = /^\d+$/;
	if (!regExe.test(str)){
		return false;
	}
	return true;
}

function isNull(str){
	if (str == null || trim(str) == ""){
		return true;
	}
	return false;
}

function isDate(str,bEmpty){
	if (str == null){
		return bEmpty;
	}
	var regExe = /^(197[0-9]|19[89][0-9]|[2-9][0-9]{3})[-\/]{1}(0[1-9]|1[0-2])[-\/]{1}(0[1-9]|[12][0-9]|3[01])$/;
	if (!regExe.test(str)){
		return false;
	}
	return true;
}

function isEmail(str,bEmpty){
	if (str == null || trim(str) == ""){
		return bEmpty;
	}
	//校验类似于hxu@sitechasia.com的邮件
	pattern1=/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
	//校验类似于huanxu@yahoo.com.cn的邮件
	//pattern2="^[a-zA-Z0-9_.\-]+[@]{1}[a-zA-Z0-9_\-]+[.]{1,5}[a-zA-Z0-9_\-]+[.]{1,5}[a-zA-Z0-9_\-]";
	if(pattern1.test(str)){
		return true;
	}else{
		return false;
	}
	 
}

function isTelNo(str,bEmpty){
	if(str==null||trim(str)=="")
		return bEmpty;
	//var re=/^(\d{3,4}-)?\d{7,8}$/g;
	var pattern = /^(\d{3,4}-)?(\d{7,8})(-(\d{1,5}))?$/;
	var tel=tel;
	if(!pattern.test(str)){
		return false;
	}
	return true;
}

function isMobile(str,bEmpty){
	if(str==null||trim(str)=="")
		return bEmpty;
	var myphone=/^(1)(\d){10}$/; 
	if(myphone.test(str))
		return true;
	return false;
}


function isASCII(str, bEmpty){
	if(str==null||trim(str)=="")
		return bEmpty;
	var str= trim(str);
	for(var i = 0; i < str.length; i++){
		var ch = str.charAt(i);
		if (!((ch >= "A" && ch <= "z" ) || (ch >="0" && ch <="9"))){
			return false;
		}
	}
	return true;
}

//Function trim a string
function trim(Str , Flag){
	Str= ""+Str;
	if( Flag == "l" || Flag == "L" ){
		RegularExp= /^\s+/gi;
		return Str.replace( RegularExp,"" );
	}else if( Flag == "r" || Flag == "R" ){
		RegularExp= /\s+$/gi;
		return Str.replace( RegularExp,"" );
	}else{
		RegularExp= /^\s+|\s+$/gi;
		return Str.replace( RegularExp,"" );
	}
}

function strlen(str){
	return str.length;
}


function concatDate(year,month,day){
	if(year == null || trim(year) == "") 
		return false;
	if(month == null || trim(month) == "") 
		return false;
	if(day == null || trim(day) == "") 
		return false;
	
	var y = trim(year);
	var m = trim(month);
	var d = trim(day);
	
	var yearNum = parseInt(y);
	var monthNum = parseInt(m);
	var dayNum = parseInt(d);
	var str = "" + yearNum;
	
	if (monthNum < 10)
		str = "0" + monthNum;
	else 
		str = "" + monthNum;
	if (dayNum < 10)
		str = "0" + dayNum;
	else 
		str = "" + dayNum;

	return str;
}

function equals(str1,str2){
	if (str1 == null && str2 == null){
		return true;
	}
	if (str1 == str2){
		return true;
	}
	return false;
}

function equals(str1,str2,minlength,maxlength){
	if(isNull(str1) || str1.length < minlength || str1.length > maxlength){
		return false
	}
	if(isNull(str2) || str2.length < minlength || str2.length > maxlength){
		return false
	}
	if (str1 != str2){
		return false;
	}
	return true;
}

/**
*比较两个数值的大小,str1大于str2返回-1， 等于返回0， 小于返回1
*/
function compareFloat(str1, str2){
	str1 = trim(str1);
	str2 = trim(str2);
	var float1 = parseFloat(str1);
	var float2 = parseFloat(str2);
	if(float1 < float2) return 1;
	else if(float1 > float2) return -1;
	else if(float1 == float1) return 0;
}


//Move Control's Focus Through Put Down One Key
function keyEnter(keyCode,nextControl) 
{
var srcElement=window.event.srcElement;
var iKeyCode = window.event.keyCode;

if(iKeyCode != keyCode)
{
return true;
}

if(nextControl == null)
{
var i = 0;
while (srcElement!=srcElement.form.elements[i])
{
i++;
}
if(!srcElement.form.elements[i+1].disabled)
srcElement.form.elements[i+1].focus();
if(srcElement.form.elements[i+1].type == "text"||srcElement.form.elements[i+1].type == "textarea"||srcElement.form.elements[i+1].type == "checkbox")
srcElement.form.elements[i+1].select();
else
{
do
{
i++;
}while(srcElement.form.elements[i+1].disabled)
srcElement.form.elements[i+1].focus();
if(srcElement.form.elements[i+1].type == "text"||srcElement.form.elements[i+1].type == "textarea"||srcElement.form.elements[i+1].type == "checkbox")
srcElement.form.elements[i+1].select();
}
}
else
{
nextControl.focus();
if(nextControl.type == "text"||nextControl.type == "textarea"||nextControl.type == "checkbox")
nextControl.select();
}
return false;
}

function isIntCtrl(ctrl, bEmpty)
{
var flag = isInt(ctrl.value, bEmpty);
if (flag == false)
{
alert("请输入大于零的数字");
ctrl.focus();
return false;
}

return true;
}

function isFloatCtrl(ctrl,bEmpty,tcount)
{
if(tcount == null) tcount = '2' ;
var flag = isFloat(ctrl.value, bEmpty,tcount);
if (flag == false)
{
alert("输入小数位小于" + tcount + "的数字");
ctrl.focus();
return false;
}

return true;
}

function isNumberCtrl(ctrl, bEmpty)
{
var flag = isNumber(ctrl.value, bEmpty);
if (flag == false)
{
alert("请输入0-9之间的数字");
ctrl.focus();
return false;
}

return true;
}

function isNullCtrl(ctrl,bEmpty)
{
var flag = isNull(ctrl.value, bEmpty);
if (flag)
{
alert("输入字符不能为空");
ctrl.focus();
return false;
}

return true;
}

function isASCIICtrl(ctrl,bEmpty)
{
var flag = isASCII(ctrl.value, bEmpty);
if (flag == false)
{
alert("不能包含特殊字符");
ctrl.focus();
return false;
}

return true;
}


function hasSpecSymbolCtrl(ctrl,bEmpty){
var flag = hasSpecSymbol(ctrl.value, bEmpty);
if (flag == false)
{
alert("不能包含特殊字符");
ctrl.focus();
return false;
}

return true;

}

function isDateCtrl(ctrl,bEmpty)
{
var flag = isDate(ctrl.value, bEmpty);
if (flag == false)
{
alert("请输入正确的日期类型");
ctrl.focus();
return false;
}

return true;
}

function isEmailCtrl(ctrl,bEmpty)
{
var flag = isEmail(ctrl.value, bEmpty);

if (flag == false)
{
alert("请输入正确的邮件地址");
ctrl.focus();
return false;
}

return true;
}

function isTelNoCtrl(ctrl,bEmpty)
{
var flag = isTelNo(ctrl.value, bEmpty);
if (flag == false)
{
alert("请输入正确的电话号码, 包含0-9、-、+ 、(、)、/。");
ctrl.focus();
return false;
}

return true;
}

function test_name(str) {
var pattern = /^[_\-a-zA-Z0-9\@\.]{3,20}$/;
if(pattern.test(str)) return true;
return false;
}

function equalsCtrl(ctrl1, ctrl2, bEmpty, length)
{
if (ctrl1 ==null || ctrl1.value.length < length)
{
alert("长度不能小于"+ length +"!");
ctrl1.focus();
return false;
}

if (ctrl2 ==null || ctrl2.value.length < length)
{
alert("长度不能小于"+ length +"!");
ctrl2.focus();
return false;
}

if (!bEmpty) {
if (ctrl1 ==null || ctrl1.value.length < 1) {
alert("输入不能为空!");
ctrl1.focus();
return false;
}

if (ctrl2 ==null || ctrl2.value.length < 1) {
alert("输入不能为空!");
ctrl2.focus();
return false;
}
}

var flag = equals(ctrl1.value, ctrl2.value, length);

if (flag == false)
{
alert("请输入相等的内容");
ctrl2.focus();
return false;
}

return true;
}

function getStrLen(str)
{
if(str==null) return 0;

//var len;
//len = document.TestApplet.getStrByteLen(str);

var size = 0;
for (var i = 0; i < str.length; i++) { 
var ch = str.charAt(i); 
if ( ch <= "\x7f" ){
size = size + 1;
}
if ("\x80"< ch){
size = size + 2;
}
}

return size;
}

function getTextareaHigh(textarea)
{ 
var textareaWidth = textarea.cols;
var str = textarea.value;
var totalHigh = 0;
var rnLoc = str.indexOf("\r\n");
var tempString =str ;

if(rnLoc!= -1){
while (rnLoc != -1)
{
var s = tempString.substring(0, rnLoc); 
totalHigh = totalHigh+ getLineNum(s, textareaWidth); 
tempString = tempString.substring(rnLoc+2, tempString.length);
rnLoc = tempString.indexOf("\r\n"); 
}
}

if (rnLoc== -1 && tempString.length > 0)
{
totalHigh = totalHigh + getLineNum(tempString, textareaWidth);
}

if (totalHigh == 0)
totalHigh = 1; 
return totalHigh; 
}

function getLineNum(str, lineLen)
{ 
if(str==null) return 0;

var cols = 1;
var size = 0;
var deltaSize = 1;
for (var i = 0; i < str.length; i++) { 
var ch = str.charAt(i); 
if ( ch <= "\x7f" ){
deltaSize = 1;
}
if ("\x80"< ch){
deltaSize = 2;
}
size = size + deltaSize; 

if(size > lineLen*cols){
//如果上个双字节字符使字符串换行，则使size多加一，以补充textarea自动换行多出的空位
if(deltaSize == 2) size++;
}

if(size == lineLen*cols && str.charAt(i+1)<= "\x7f" && str.charAt(i+1)!= ""){
var needPatch = "false";
for(var k = lineLen*(cols-1);k < (lineLen*cols -1); k++) {
if(str.charAt(k) > "\x80") {
needPatch = "true";
break; 
}
}

if(needPatch == "true"){
var pos = size;

//找到前面最近的双字节字符
for(var j = i;str.charAt(j-1)<= "\x7f" && pos>(lineLen*(cols-1)) ;j--) {
pos--; 
}
size = size + (lineLen*cols - pos) + 1;
}

}
cols = Math.ceil(size/lineLen);


}

return cols;
}




vartotalStr = ""; 
function printLengthCrtl(str,len)
{
var rnLoc = str.indexOf("\r\n");
if(rnLoc>-1)
{
if(rnLoc>len)
{
var str1 = str.substring(0,len);
totalStr +=str1+"\r\n";
str = str.substring(len);

printLengthCrtl(str,len);
}
else
{
totalStr += str.substring(0,rnLoc) + "\r\n";
str = str.substring(rnLoc+2);
alert(str);
printLengthCrtl(str,len); 
}
}else{
if(str.length>len)
{
var str1 = str.substring(0,len);
totalStr +=str1+"\r\n";
str = str.substring(len);
alert(str);
printLengthCrtl(str,len);
}else{
totalStr +=str;
}
}
return totalStr;
}

function printLengthCrtl(str,len)
{
var totalStr = "";
var rnLoc = str.indexOf("\r\n");
if(rnLoc>-1)
{
if(rnLoc>len)
{
var str1 = str.substring(0,len);
var cutpos = str1.lastIndexOf(" ");
if(cutpos>-1)
{
totalStr += str1.substring(0,cutpos)+"\r\n";
str = str.substring(cutpos+1);
}
else
{
totalStr +=str1+"\r\n";
str = str.substring(len);
}
printLengthCrtl(str,len);
}
else
{
totalStr +=str.substring(0,rnLoc+2);
str = str.substring(rnLoc+2);
printLengthCrtl(str,len); 
}
}else{
if(str.length>len)
{
var str1 = str.substring(0,len);
var cutpos = str1.lastIndexOf(" ");
if(cutpos>-1)
{
totalStr += str1.substring(0,cutpos)+"\r\n";
str = str.substring(cutpos+1);
}
else
{
totalStr +=str1+"\r\n";
str = str.substring(len);
}
printLengthCrtl(str,len);
}else{
totalStr +=str;
}
}
return totalStr;
}


//将日期格式为"2003-08-21"的字符串变为日期对象Date
function covertDate(strDate)
{
tempStr = strDate;
var i = tempStr.indexOf("-");
if(i > -1)
var strYear = tempStr.substring(0, i);
tempStr = tempStr.substring(i+1, tempStr.length);
i = tempStr.indexOf("-");
if(i > -1)
var strMonth = tempStr.substring(0, i);
strDay = tempStr.substring(i+1, tempStr.length);
tempStr = tempStr.substring(i+1, tempStr.length);
var date = new Date(strYear, strMonth, strDay);
return date;
}

//判断是否起始日期小于等于结束日期
function compareDate(strStartDate, strEndDate)
{
if(trim(strStartDate) == "" || trim(strEndDate) == "" )
return true;
var startDate = covertDate(strStartDate);
var endDate = covertDate(strEndDate);
if(startDate.getTime() > endDate.getTime())
return false;
else 
return true;
}


function getLocation(lc)
{
var date = new Date();
var s = lc + "";
var i = s.indexOf("?");
if(i > -1)
{
lc = lc + "&amp;time=" + date.getTime();
}
else
{
lc = lc + "?time=" + date.getTime();
}
return lc;
}

//打开HTML编辑器
var formID;
function openscriphtml(textareaname){
formID = textareaname;
if (navigator.appName!="Microsoft Internet Explorer")
alert("此功能 Netscape 用户不能使用！")
else { 
newwin=window.open('../../../EbizResourceSet/htmleditor/editor.html','','width=640,height=450,status=1');
newwin.focus();
//newwin.execScript("var formID='CompanyNewsMaint_FormCompanyNews.CompanyNewsMaint_CompanyNewsContent';");
}
}

//页面输出指定input对象中的长文本中的指定长度的字符
//input - 指定input对象
//showCharNumber - 指定长度
function printPartTextInLongText(input, showCharNumber){
var str = input.value;
var reg = /<[a-z]*[A-Z]*(\s[a-z]*[A-Z]*\=((\"(.*)\")*|(\#\w{6})*))*\/?>|<\/[a-z]*[A-Z]*>/g;
var regSpace = /\s/g;
var regSpace1 = /&nbsp;/g;
var str1 = str.replace(reg,"");
var moveSpace = str1.replace(regSpace,"");
var laststr = moveSpace.replace(regSpace1,"");
var str_Description = laststr.substr(0,showCharNumber) + "...";
document.write(str_Description);
}
function SetHome(){
var LocationStr = new String(window.location);
var NumStr = LocationStr.indexOf("\?");
//alert(NumStr);
var SetHomeStr;
if(NumStr == -1){
SetHomeStr = LocationStr;
}else{
SetHomeStr = LocationStr.substring(0,NumStr)}
document.getElementsByName('SetHomeA')[0].style.behavior="url(#default#homepage)";
document.getElementsByName('SetHomeA')[0].setHomePage(SetHomeStr);
return false;
}

//javascript4舍5入
function to2bits(flt) { 
if(parseFloat(flt) == flt){
return Math.round(flt * 100) / 100; 
}else{
return flt;
}
}

function hasSpecSymbol(str,bEmpty){
if(str==null||trim(str)=="")
return bEmpty;//alert(name+"项：您尚未填写。");

var str= trim(str);

for (var i = 0; i < str.length; i++) { 
var ch = str.charAt(i); 
if ((ch == "`" )|| (ch == "~" )||(ch == "!" )||(ch == "@" )||
(ch == "#" )||(ch == "%" )||(ch == "^" )||(ch == "&" )||
(ch == "*" )||(ch == "(" )||(ch == ")" )||(ch == "/" )||
(ch == "+" )||(ch == "=" )||(ch == "|" )||(ch == "{" )||
(ch == "}" )||(ch == "[" )||(ch == "]" )||(ch == ":" )||
(ch == ";" )||(ch == "'" )||(ch == '"' )||(ch == "<" )||
(ch == ">" )||(ch == "," )||(ch == "." )||(ch == "?" )) { 
return true; 
} 
} 
return false; 
}