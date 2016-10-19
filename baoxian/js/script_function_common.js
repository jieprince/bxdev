
$().ready(function() {
	
	if($j('#step').val()==1){
		var op_html = '';
		var temp_nums = $('#applyMum_sums').val();
		if(isNaN(temp_nums)){
			temp_nums=1;	
		}else{
			if(parseInt(temp_nums)<=1){
				temp_nums=1;
			}	
		}
		for (var iqs = 1; iqs <=parseInt(temp_nums) ; iqs++) {
			if (iqs == 1) {
				op_html += '<option value="'+iqs+'" selected>' + iqs + '</option>';
			} else {
				op_html += '<option value="'+iqs+'">' + iqs + '</option>';
			}
		}
		if($('#applyNum').get(0) && ($('#applyNum').get(0).tagName=='SELECT' || $('#applyNum').get(0).tagName=='select')){
			$('#applyNum').html(op_html);
		}
	}
});

function showwarn(warnid,op){
	document.getElementById(warnid).style.display="";
}

//根据生日计算年龄  //dom_id  选填参数  只有华安学平险使用
function getAgeByBirthday(bir,dom_id)
{
	if(trim(bir)==""){
		return "";
	}else if(/^(\d{4})(-)(\d{1,2})(-)(\d{1,2})$/.test(bir)){
		var birthday= new Date(bir.replace(/-/g, "\/")); 
		var d=new Date(); 
		if($('#xuepingxian').val()=='y' && dom_id=='assured_birthday'){
			if($('#startDate').val()){
				d=new Date($('#startDate').val().substring(0,10).replace(/-/g, "\/")); 
			}else{
				d=new Date();
			}
		}
		var age = d.getFullYear()-birthday.getFullYear()-((d.getMonth()<birthday.getMonth()|| d.getMonth()==birthday.getMonth() && d.getDate()<birthday.getDate())?1:0);
		
		if(typeof(age)=="undefined")
		{
			//alert("getAgeByBirthday，变量无效");
			age = -1;
		}
		else if(isNaN(age))
		{
			//alert("getAgeByBirthday，年龄是非数字");
			age = -1;	
		}
		else
		{
			//alert("getAgeByBirthday: "+age);
		}
	}else{
		age = -1;
	}
	
	return age;
}


//判断中文和英文的长度////////////////////////////////////////////
function strlen(str) 
{
    var len = 0;
    for (var i = 0; i < str.length; i++) {
        var c = str.charCodeAt(i);
        //单字节加1 
        if ((c >= 0x0001 && c <= 0x007e) || (0xff60 <= c && c <= 0xff9f)) {
            len++;
        }
        else {
            len += 2;
        }
    }
    return len;
}

/*
//通过js导入excel文件
function importXLS(fileName)
{
	alert(fileName);
	
    objCon = new ActiveXObject("ADODB.Connection");
    objCon.Provider = "Microsoft.Jet.OLEDB.4.0";
    objCon.ConnectionString = "Data Source=" + fileName + ";Extended Properties=Excel 8.0;";
    objCon.CursorLocation = 1;
    //objCon.Open =1;
    var strQuery;
    //Get the SheetName
    var strSheetName = "Sheet1$";
    var rsTemp =  new ActiveXObject("ADODB.Recordset");
    rsTemp = objCon.OpenSchema(20);
    if(!rsTemp.EOF)
    strSheetName = rsTemp.Fields("Table_Name").Value;
    rsTemp = null
    rsExcel =  new ActiveXObject("ADODB.Recordset");
    strQuery = "SELECT * FROM [" + strSheetName + "]";
    rsExcel.ActiveConnection = objCon;
    rsExcel.Open(strQuery);
    while(!rsExcel.EOF)
    {
    for(i = 0;i<rsExcel.Fields.Count;++i)
    {
    alert(rsExcel.Fields(i).value);
    }
    rsExcel.MoveNext;
    }
    // Close the connection and dispose the file
    objCon.Close;
    objCon =null;
    rsExcel = null;
}
*/
/////////////////////////////////////////////////
var  $j = jQuery.noConflict();

 $j(document).ready(function(){
							
	//找到产品责任搜索返回
	$j("input#duty_name").keyup(function(){
		
		//alert("dddddddd");
		
		 var objthis =  $j(this);
		 
		 var value = objthis.val();
		 if(value.length<=0)
		 {
			 return;
		 }
		 
		 var insurer_id = $j("input#insurer_id").val();
		 
		 //var url = "space.php?do=livesearch&op=duty&insurer_id="+insurer_id+"&searchkey="+encodeURI(value);//		encodeURI	,escape
		//alert();
		 
		
		 $j.getJSON("space.php", 
					 {
						 'do': "livesearch",
			             op:"duty",
			             insurer_id:insurer_id,
			             q:value,
			             random: Math.random() 
			          }, 
		             function (data){

			           	  ul = $j("ul#duty_name_list");
			    		  ul.empty();
			    		  ul.show();
			    		  /////////////////////////////////////////////////////////////////
		            	  data = $j(data);
		            	  data.each(function () {
				          //var symptom = $j(this);
		            	       	var id = this.BD_ID;
				                var name = this.BD_Name;
				                var code = this.BD_Code;
				                
				                var li = $j("<li>").append($j("<a>").text(name));
				 		        ul.append(li);
				 			   
				       
				 		       li.click(function () {
				 		        
				 		    	  $j("input#duty_name").val(name);
				 		    	  $j("input#duty_id").val(id);
				 		    	  $j("input#duty_code").val(code);
				 		    	 
				 		    	  ul.hide();
				 		       });//li.click
						//////////////////////////////////////////////////
			       });//data.each
	
			  });//function (data)
	 });// getJSON
	

	 
 });//ready 			
 
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
 function monthStamp2String(dateText,monthT)
 {
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
 

 function sclckimg()
 {
		$("#sub1").hide();
		$("#sub2").show();
}
 
//核对完成-订单提交(i=1--加入购物车；i=2--直接购买)
function orderCheckSubmit(obj,i) 
{
	 if (document.getElementById('readerId').checked == false)
	 {
	 	alert("请详细阅读《投保声明》。")
	 	document.getElementById('readerId').focus();
	 	return false;
	 }	 		
	
	 
	 var ctimes=obj.getAttribute("ctimes");
	 
	 if(ctimes<1)
	 {
	 	var lottery = document.getElementsByName('lottery')[0].value;
	 	var msg = "确认提交订单?";
	 
		if(confirm(msg)) 
		{
			sclckimg();
			document.getElementById("goumaifangshi").value="";
			if(i==1)
			{
				document.getElementById("goumaifangshi").value="buycart";
			}
			else
			{
				document.getElementById("goumaifangshi").value="";
			}
			document.getElementById('fgNewOrderSearchForm').submit();
			return  true;
		}
		else
		{
			obj.setAttribute("ctimes",0);
			return false;
		}	
	 }
	 
	 return true;
}
