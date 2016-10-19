//src('script_huatai_neworderAdd.js');

var  $j = jQuery.noConflict();
$j(document).ready(function(){

   //$("#applicant_city_code").show();
   //$("#applicant_city_code").empty(); 

    $j("select#destination_area").change(function(){
    	   
    	 var area = $j(this).val();
    	
		 $j.getJSON("cp.php", 
					 {
						 'ac': "product_buy_process_ajax_req",
			             op:"huatai_destination",
			             area:area,
			             random: Math.random() 
			          }, 
		             function (data){

			           	  sl = $j("select#destination_country");
			           	  sl.empty();
			           	  //sl.show();
			           	  
			           //	<option selected="selected" value="">请选择</option>
			            sl.append("<option selected=\"selected\" value='"+name+"'>"+"请选择"+"</option>");   //为Select追加一个Option(下拉项)
						
			    		  /////////////////////////////////////////////////////////////////
		            	  data = $j(data);
		            	  data.each(function () {
				          //var symptom = $j(this);
		            	        var id = this.BD_ID;
				                var name = this.BD_Name;
				                //var code = this.BD_Code;
				                
				                if(id==1)
				                {
				                	sl.append("<option value='"+name+"'>"+name+"</option>");   //为Select追加一个Option(下拉项)
				                }
				                //////////////////////////////////////////////////
		            	  	});//data.each
	
			          });//function (data)
   		     //});// getJSON
	
	});//change
    
    //////////////////////////////////////////////////////////
    $j("select#insureareacity_code").change(function(){
    	
    	 //var city = $j(this).val();
    	 
    	 var city_name = $j(this).find("option:selected").text();
    	  
    	 $j("input#insureareacity").val(city_name);
    });//change
    
    $j("select#insureareaprov_code").change(function(){
    	   
    	 var provine_code = $j(this).val();
    	 
    	 var provine_name = $j(this).find("option:selected").text();
    	  
    	 $j("input#insureareaprov").val(provine_name);
    	 
		 $j.getJSON("cp.php", 
					 {
						 'ac': "product_buy_process_ajax_req",
			             op:"huatai_city",
			             provine_code:provine_code,
			             random: Math.random() 
			          }, 
		             function (data){

			           	  sl = $j("select#insureareacity_code");
			           	  sl.empty();
			           	  //sl.show();
			           	  
			              //	<option selected="selected" value="">请选择</option>
			              sl.append("<option selected=\"selected\" value='"+name+"'>"+"请选择城市"+"</option>");   //为Select追加一个Option(下拉项)
						
			    		  /////////////////////////////////////////////////////////////////
		            	  data = $j(data);
		            	  data.each(function () {
				          //var symptom = $j(this);
		            		    var city = this;
		            	        var id = city.BD_ID;
		            	        var code = city.BD_Code;
		            	        var name = city.BD_Name;
				                //var code = this.BD_Code;
				                
				                if(id==1)
				                {
				                	sl.append("<option value='"+code+"'>"+name+"</option>");   //为Select追加一个Option(下拉项)
				                }
				                //////////////////////////////////////////////////
		            	  	});//data.each
	
			          }
		 );//function (data)
   		  //});// getJSON
	
	});//change
    //////////////////////////////////////////////////////////////////////////


    //把时间进行转换
    function timeStamp2String_huatai(dateText,period_day)
    {
    	//alert(dateText);
       //var datetime = new Date();
	   	var datetime = new Date(Date.parse(dateText.replace(/-/g, "/")));
    	//var datetime = new Date(Date.parse(dateText));
    	
	   	var t = datetime.getTime();//毫秒，起始时间
		 
	   	if(period_day!=365){
			t = t + period_day*24*3600*1000-1000;//减少一秒
		}else{
			t = t-1000;//减少一秒	
		}
		
	   	
	   	datetime = new Date(t);
	   	
	   	//alert("sdfsdf");
	   	
	   //datetime.setTime(time);
	   var year = datetime.getFullYear();
	   if(period_day==365){
			year = 	parseInt(year)+1;
	   }
	   //var month = datetime.getMonth() + 1 < 10 ? "0" + (datetime.getMonth() + 1) : datetime.getMonth() + 1;
	   var month = datetime.getMonth() + 1 < 10 ? "0" + (datetime.getMonth() + 1) : datetime.getMonth() + 1;//����12���¡�
	
	   var date = datetime.getDate() < 10 ? "0" + datetime.getDate() : datetime.getDate();
	   var hour = datetime.getHours()< 10 ? "0" + datetime.getHours() : datetime.getHours();
	   var minute = datetime.getMinutes()< 10 ? "0" + datetime.getMinutes() : datetime.getMinutes();
	   var second = datetime.getSeconds()< 10 ? "0" + datetime.getSeconds() : datetime.getSeconds();
	   //return year + "-" + month + "-" + date+" "+hour+":"+minute+":"+second;
	   var strtime = year + "" + month + "" + date;
	   //var strtime = year + month+ date;
	   return strtime;
    }
    
    /////////////////////////////////////////////////////////////////////////////////
	//http://api.jqueryui.com/datepicker/
    $j.datepicker.regional["zh-CN"] = {closeText: "关闭", prevText: "&#x3c;上月", nextText: "下月&#x3e;", currentText: "今天", monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"], monthNamesShort: ["一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二"], dayNames: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"], dayNamesShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"], dayNamesMin: ["日", "一", "二", "三", "四", "五", "六"], weekHeader: "周", dateFormat: "yy-mm-dd", firstDay: 1, isRTL: !1, showMonthAfterYear: !0, yearSuffix: "年"}
    $j.datepicker.setDefaults($j.datepicker.regional["zh-CN"]);
    var start_day = $j("input#start_day").val();
    if(start_day==0)
    {//第二天
    	start_day = 1;
    }
    
    //start add by wangcya , 20141121,服务器时间
    //var date = new Date($.ajax({async: false}).getResponseHeader("Date"));
    var date = $j("#server_time").val();
    date = parseInt(date);
    //alert("server time: "+date);
    //date = new Date(date);
    var bombay = date + (3600 * 24)*start_day;
    //alert("bombay: "+bombay);
     var time = new Date(bombay*1000);
    //alert("time: "+time);
    time = time.getFullYear()+"-"+(time.getMonth()+1)+"-"+time.getDate();
    //alert("after time: "+time);
    //end add by wangcya , 20141121,服务器时间
    
	 var pickerOpts = {
         changeMonth: true,
         changeYear: true,
         dateFormat:'yy-mm-dd',
		 minDate: time,//start_day
		 onSelect: function(dateText, inst) { 
			 var period = $j("input#period").val();
			 
			   var t = timeStamp2String_huatai(dateText, period);
	           // alert(t);
	            $j("#endDate").val(t);
	            
	            var startdate_old = $j("#startDate").val();
	            startdate_old = startdate_old.replace(/-/g, "");
	            //$j("#startDate").val(startdate_old+" 00:00:00");
	            $j("#startDate").val(startdate_old);

         }
        };
	
	 /*
	 $.datepicker.setDefaults($.datepicker.regional['zh-CN']);
	 $("#startDate").datepicker($.datepicker.regional['zh-CN']);
	 $('#startDate').datepicker('option', $.datepicker.regional['zh-CN']);
     */
	 //  $j.datepicker.setDefaults($.datepicker.regional['zh-CN']);
	 // $j.datepicker.regional["zh-CN"] = { closeText: "关闭", prevText: "&#x3c;上月", nextText: "下月&#x3e;", currentText: "今天", monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"], monthNamesShort: ["一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二"], dayNames: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"], dayNamesShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"], dayNamesMin: ["日", "一", "二", "三", "四", "五", "六"], weekHeader: "周", dateFormat: "yy-mm-dd", firstDay: 1, isRTL: !1, showMonthAfterYear: !0, yearSuffix: "年" }
    //   $j.datepicker.setDefaults($.datepicker.regional["zh-CN"]);
	 //��������ѡ����
     
	 $j("#startDate").datepicker(pickerOpts); 
	 
	 ///////////////////////////////////////////////////////
	     	 
	 
	////////////////////////////////////////////////////////
	$j("a#btnNext").click(function () {
	
        var startDate = $j("input#startDate").val();
       	if(!startDate)
	    {
	    	alert("请输入保险起期!");
	    	window.setTimeout("document.getElementById('startDate').focus();", 0)  ;  
	    	return;
	    }
       
        var endDate = $j("input#endDate").val();
       	if(!endDate)
	    {
	    	alert("请输入保险止期!");
	    	window.setTimeout("document.getElementById('endDate').focus();", 0)  ;  
	    	return;
	    }
       	////////////////////////////////////////////////////////////////////////////
             	
       	////////////////////////////////////////////////////////////////////////////
        var bat_post_policy_val = $('#bat_post_policy').val();
        var res = "";
        if(bat_post_policy_val==0){
            res = orderSubmit();
        }else if(bat_post_policy_val==1){
            res = checkedAll_New();
        }

		if(!res || res=="")
		{
			return;
		}
       	////////////////////////////////////////////////////////////////////////////
	
	
		///////////////////////////////////////////////////
	 	var val=$j('input:radio[name="radiobutton"]:checked').val();
		if(val==null)
		{
			alert("什么也没选中!");
			return false;
		}
		else if(val!="yes")
		{
			alert("您还没选择同意声明！");
			return false;
		}
		
		
	    //////////////////////////////////////
	    $j("input#submit1").click();

	});
	 
	 
	 
 });// ready



/*----------------------4.订单提交验证-----------------------*/

//提交订单
function orderSubmit(){
	
	var tname = "";//投保人姓名
	var bname = "";//被保人姓名
	var bEnglishname ="";//被保险人英文名
	var tsex1 = "";//投保人性别
	var tsex2 = "";//投保人性别
	var bsex1 = "";//被保人性别
	var bsex2 = "";//被保人性别
	var tcardtype1 = "";//投保人证件类型
	var bcardtype1 = "";//被保人证件类型
	var tcardcode = "";//投保人证件号
	var bcardcode = "";//被保人证件号
	var tprov = "";
	var tcity = "";
	var jobs1 = "";
	var jobs2 = "";
	var jobs3 = "";
	var job1 = null;
	var job2 = null;
	var job3 = null;
	

	///////////////////////////////////////////////////////////////////////////////////
	if(!checkappName())
	{
		window.setTimeout("document.getElementById('appName').focus();", 1)  ;  
		return false;
		
	}
   	
    //////////////////////////////////////////////////////////////////////////
	if(!checkappEnglishName())
	{
		window.setTimeout("document.getElementById('appEnglishName').focus();", 1)  ;  
		return false;
		
	}
	

    ////////////////////////////////////////////////////////////////////////
    if(!checktcard())
    {
    	window.setTimeout("document.getElementById('certCode').focus();", 1)  ;  
	   	return false;
    	
    }
    
  
	////////////////////////////////////////////////////////////////////
    
    if(!checktsex())
    {
    	window.setTimeout("document.getElementById('checktsex_M').focus();", 1)  ;  
    	return false;
    }
    
   	///////////////////////////////////////////////////////////////////////
    //alert("before checktbirthday");
    
    if(!checktbirthday())
    {
    	window.setTimeout("document.getElementById('appBirthday').focus();", 1)  ;  
    	return false;
    }
    
  
    //alert("before checktphone");
    
    if(!checktphone())
    {
    	window.setTimeout("document.getElementById('appCell').focus();", 1)  ;  
    	return false;
    }
    
    //alert("before check_t_address");
    
    if(!checktemail())
    {
    	window.setTimeout("document.getElementById('appEmail').focus();", 1)  ;  
    	return false;
    }
    
    if(!check_t_region())
    {
    	window.setTimeout("document.getElementById('insureareaprov_code').focus();", 1)  ;  
    	return false;
    }
    
    if(!checka_t_ddrdetail())
    {
    	window.setTimeout("document.getElementById('appAddress').focus();", 1)  ;  
    	return false;
    }
    
    if(!check_t_postcode())
    {
    	window.setTimeout("document.getElementById('appPostcode').focus();", 1)  ;  
    	return false;
    }
    
    //alert("check_t_address");
    
    /*
    if(!check_t_job())
    {
    	return false;
    }
    */
   	////////end add by wangcya , 20141105////////////////////////
	var tbr = document.getElementsByName("relationShipApp")[0]?document.getElementsByName("relationShipApp")[0].value:"";
	
	if(tbr == "" && document.getElementById("tb_warn")) 
	{
		document.getElementById("tb_warn").className = "wrong_strong";
		document.getElementById("tb_warn").innerHTML = "请选择投保人与被保人的关系";
		document.getElementById("tb_warn").style.display = "";
		window.setTimeout("document.getElementsByName('relationShipApp')[0].focus();", 0)  ;  
		return false;
	}
	

	//alert("relationShipApp: "+tbr);
	///////////////////////////////////////////////////
	var age_min = document.getElementsByName("age_min")[0]?document.getElementsByName("age_min")[0].value:"";
	var age_max = document.getElementsByName("age_max")[0]?document.getElementsByName("age_max")[0].value:"";

	/////////////////////////////////////////////////////
	
	if(tbr == '5')//本人
	{//modify by wangcya, 20141016 , from a to 5
		//bbage = ttage;
		
    	var bbirthday = document.getElementById("appBirthday").value;
    	
    	if(!checkbbirthday(bbirthday,age_min+'y',age_max+'y'))
	    {
	    	//alert("被保险人年龄不在承保范围内！");
	    	document.getElementById("appBirthday_warn").className = "wrong_strong";
			document.getElementById("appBirthday_warn").innerHTML = "被保险人年龄不在承保范围内！";
			window.setTimeout("document.getElementsByName('appBirthday')[0].focus();", 0)  ;  
	
	    	return false;
	    }
	}
	else
	{
		//alert("before checkinsName");
	    if(!checkinsName())
	    {
	    	window.setTimeout("document.getElementById('insName').focus();", 1)  ;  
	    	return false;
	    }
	    
	    //alert("before checkinsEnglishName");
	    if(!checkinsEnglishName())
	    {
	    	window.setTimeout("document.getElementById('insEnglishName').focus();", 1)  ;  
	    	return false;
	    }
		    
	    //alert("before checkbcard");
		if(!checkbcard())
		{
			window.setTimeout("document.getElementById('insCertCpde').focus();", 1)  ;  
			return false;
			
		}
			
		////////////////////////////////////////////////////////////////
	
    	var bbirthday = document.getElementById("insBirthday").value;
    	
    	//alert("before checkbbirthday, bbirthday: "+bbirthday);
    	if(!checkbbirthday(bbirthday,age_min+'y',age_max+'y'))
	    {
	    	//alert("被保险人年龄不在承保范围内！");
	   		window.setTimeout("document.getElementsByName('insBirthday')[0].focus();", 0)  ;  
	
	    	return false;
	    }
	    
	
	    
	    /*
		
		if(!checkbbirthday('18y','75y'))
		{
			return false;
			
		}
		*/
    	//alert("before checkbsex");
		if(!checkbsex())
		{
			window.setTimeout("document.getElementById('insSex_M').focus();", 1)  ;  
			return false;
			
		}
    	
    	
    	//alert("before checktphone_ins");
		if(!checktphone_ins())
		{
			window.setTimeout("document.getElementById('insCell').focus();", 1)  ;  
			return false;
			
		}
		
		//alert("before checktemail_ins");
		if(!checktemail_ins())
		{
			window.setTimeout("document.getElementById('insEmail').focus();", 1)  ;  
			return false;
			
		}
		
		/*
		if(!checktaddress_ins())
		{
			return false;
			
		}
		*/
		
		//////////////////////////////////////////////////////////////////////////////////

	}

    //alert("before otherChecked");
	if(!Checke_Purpose())
	{
		return false;
	}
	
	//alert("before checkedNullChar");
    if(!checkedNullChar())
    {
    	return false;
    }
    
    //alert("before otherChecked");
	if(!otherChecked()){
		return false;
	}
	
	//alert("sdfdsfsdf");
	//document.getElementById('orderForm').submit();//del by wangcya, 20140916
	
	return true;
}//end orderSubmit




