var $j = jQuery.noConflict();
//////////////////////////////////////////////////
function importXLS_taipingyang(file,product_id)
{
	alert(file);
 
	var url = "cp.php?ac=product_buy";
	var data_obj = {
				op:'uploadfile_insured_xls',
				formhash:formhash,
				inajax:1
	            };
	
   	//alert();
	$j.ajax({
		type:'post',//可选get
		url:url,//这里是接收数据的PHP程序
		data:data_obj,//传给PHP的数据，多个参数用&连接
		dataType:'',//服务器返回的数据类型 可选XML ,Json jsonp script html text等          //这样会有问题，application/json; charset=utf-8
		success:function(msg){
	   
				var objret = $j.parseJSON(msg); 
				
				var return_code = objret.return_code;
				var retid = objret.BD_id;
				var retname = objret.BD_Name;
				if(return_code==1)
				{
					objthis.parents("span.qs-tag-editor-edit-item").remove();
				}
				else
				{
					alert(retname);
				}
			},//sccuess
			error:function(){
				// alert("error");
				}
									   
		});//ajax post 提交到服务器进行增加	
}

///////////////////////////////////////////////////
$j(document).ready(function () {

	
	
	//var url = "cp.php?ac=product_buy";
	var product_id = $j("input#product_id").val();
	
	/*
	$j("#inputExcel").fileupload({
	    url:"cp.php?ac=product_buy&op=uploadfile_insured_xls",//文件上传地址，当然也可以直接写在input的data-url属性内
	    maxFileSize: 5000000,
	    dataType: 'json',
	    formData:{product_id:product_id},//如果需要额外添加参数可以在这里添加
	    progressall: function (e, data) {
	        var progress = parseInt(data.loaded / data.total * 100, 10);
	        $j('#progress.bar').css('width',progress + '%');
	    },
	    done:function(e,data){
	        //done方法就是上传完毕的回调函数，其他回调函数可以自行查看api
	        //注意result要和jquery的ajax的data参数区分，这个对象包含了整个请求信息
	        //返回的数据在result.result中，假设我们服务器返回了一个json对象
	        //alert(JSON.stringify(result.result));
	   
	    	
	    }
	    
	});
	*/

	//删除<tr/>
	 var deltr =function(index)
	{
	        var _len = 10;//$("#tab tr").length;
	        $("tr[id='"+index+"']").remove();//删除当前行
	        for(var i=index+1,j=_len;i<j;i++)
	        {
	            var nextTxtVal = $("#desc"+i).val();
	            $("tr[id=\'"+i+"\']")
	                .replaceWith("<tr id="+(i-1)+" align='center'>"
	                                +"<td>"+(i-1)+"</td>"
	                                +"<td>Dynamic TR"+(i-1)+"</td>"
	                                +"<td><input type='text' name='desc"+(i-1)+"' value='"+nextTxtVal+"' id='desc"+(i-1)+"'/></td>"
	                                +"<td><a href=\'#\' onclick=\'deltr("+(i-1)+")\'>删除</a></td>"
	                            +"</tr>");
	        }    
	        
	}
	
	//投保份数更改后则要更改总保费
	$j("select#applyNum").change(function () {

        var price_single = $j("input#totalModalPremium_single").val();
        var applyNum = $j(this).val();
        
        var total_price = accMul(applyNum,price_single);
		 
		var temp_prsion_sum = ($('#ps_list table tr').length-1)/2;
		
		var sum_prices = total_price;
		var assured_input_type_checked = $('input[name="assured_input_type"]:checked').val();
		if(assured_input_type_checked==2){
			if(parseInt(temp_prsion_sum)>1){
				sum_prices = accMul(total_price,temp_prsion_sum);
			}
		}
         
        $j("span#span_totalpremium").text(total_price);  //这是单价*份数
		
		//以下这是总价=单价*份数*人数(只有批量投保的时候才能够乘以人数)
		$j("input#totalModalPremium").val(sum_prices);  
        $j("span#showprice").text(sum_prices);          
        
        
        //var test1 = $j("span#span_totalpremium").text();
        //alert(test1);
        
     
    });
	
	/*
	$j.fn.selectReadOnly=function(){
	    var tem=$j(this).children('option').index($j("option:selected"));
	    $j(this).change(function(){
	          $j(this).children('option').eq(tem).attr("selected",true); 
	    });
	 }
	*/
	
	
	///////////////////////////////////////////////////////////////////////////
    
    $j("input#applicant_certificates_code").change(function () {

        var UUserCard = $j(this).val();
        var type = $j("select#applicant_certificates_type").val();
        //alert(type);
        
        
        var applicant_certificates_type = $j("select#applicant_certificates_type").val();
        var applicant_certificates_code = $j("input#applicant_certificates_code").val();
	    if(applicant_certificates_type ==1&&
	       isIdCardNo(applicant_certificates_code)!=0
	       )
	    {
	    	alert("投保人证件号码输入有误，请重新输入!");
	    	return;
	    }
        

        if (type != "1" || !UUserCard)
        {
            return;
        }

        if (parseInt(UUserCard.substr(16, 1)) % 2 == 1)
        {
            sex = "1";
            //��
        }
        else
        {
            sex = "2";

        }

        //sex = "F";
        //alert(sex);

        if (sex == "1")
        {
            $j("input#applicant_sex_man").attr("checked", true);
            //$j("input#applicant_sex_man").removeAttr("checked");
        }
        else
        {
            $j("input#applicant_sex_woman").attr("checked", true);
            //$j("input#applicant_sex_woman").removeAttr("checked");
        }

        var birthday = UUserCard.substring(6, 10) + "-" + UUserCard.substring(10, 12) + "-" + UUserCard.substring(12, 14);
        //alert(birthday);
        $j("input#applicant_birthday").val(birthday);
    });

    $j("input#applicant_mobilephone").change(function () {

        var applicant_mobilephone = $j(this).val();
       	if(!isMobile(applicant_mobilephone))
	    {
	    	alert("投保人手机号码输入有误，请重新输入!");
	    	return;
	    }
       	
       	//alert("mobile ok");
        
    });
    
    $j("input#applicant_email").change(function () {

        var applicant_email = $j(this).val();
       	if(!isEmail(applicant_email))
	    {
	    	alert("投保人eamil输入有误，请重新输入!");
	    	return;
	    }
     	//alert("email ok");
    });

    
	  //选择省的时候则得到对应的名字
    $j("select#applicant_province_code").change(function () {

        var text = $j(this).find("option:selected").text();
        //alert(text);
        
        $j("#applicant_province").val(text);
     
    });
     
    
	  //选择市的时候则得到对应的名字
    $j("select#applicant_city_code").change(function () {

        var text = $j(this).find("option:selected").text();
        //alert(text);
        
        $j("#applicant_city").val(text);
     
    });
    

    /////////////////////被保险人///////////////////////////////////////

    //与被保险人关系
    $j("select#relationshipWithInsured").change(function () {
        var val_this = $j(this).val();
        if(val_this!="")
        {
            var applicant_type_val = $('input[name="applicant_type"]:checked').val();
            if(applicant_type_val==1){
                if(val_this==1){
                    $j("#div_assured").hide();
                }else{
                    $j("#div_assured").show();
                }
            }else if(applicant_type_val==2){
               if(val_this!=6){
                   layer.alert('投保人是机构，只能选择其他!');
                   $(this).val(6);
               }else{
                   $j("#div_assured").show();
               }
            }

        }
        else
        {
            $j("#div_assured").hide();
        }
    });

    $j("select#applicant_certificates_type").val("01");
    $j("select#assured_certificates_code").val("01");
    $j("select#relationshipWithInsured").val("1");


    
    //////////////////////////////////////////////////////////////
    $j("input#assured_certificates_code").change(function () {

        var UUserCard = $j(this).val();
        var type = $j("select#assured_certificates_type").val();
        if (type != "1" || !UUserCard)
        {
            return;
        }
        
        
        var assured_certificates_type = $j("select#assured_certificates_type").val();
        var assured_certificates_code = $j("input#assured_certificates_code").val();
	    if(assured_certificates_type ==1&&
	       isIdCardNo(assured_certificates_code)!=0
	       )
	    {
	    	alert("被保险人证件号码输入有误，请重新输入!");
	    	return;
	    }
        

        if (parseInt(UUserCard.substr(16, 1)) % 2 == 1)
        {
            sex = "1";

        }
        else
        {
            sex = "2";

        }

        if (sex == "1")
        {
            $j("input#assured_sex_man").attr("checked", true);

        }
        else
        {
            $j("input#assured_sex_woman").attr("checked", true);

        }

        var birthday = UUserCard.substring(6, 10) + "-" + UUserCard.substring(10, 12) + "-" + UUserCard.substring(12, 14);
        //alert(birthday);
        $j("input#assured_birthday").val(birthday);
    });

    //选择省的时候则得到对应的名字
    $j("select#insured_province_code").change(function () {

        var text = $j(this).find("option:selected").text();
        //alert(text);
        
        $j("#insured_province").val(text);
     
    });
    
    //被保险人的选择市的时候则得到对应的名字
    $j("select#insured_city_code").change(function () {

        var text = $j(this).find("option:selected").text();
        //alert(text);
        
        $j("#insured_city").val(text);
     
    });
    
    ////////////////////////////////////////////////////////////////////
    $j("input#assured_mobilephone").change(function () {

        var applicant_mobilephone = $j(this).val();
       	if(!isMobile(applicant_mobilephone))
	    {
	    	alert("被保险人手机号码输入有误，请重新输入!");
	    	return;
	    }
       	
       	//alert("mobile ok");
        
    });
    
    $j("input#assured_email").change(function () {

        var applicant_email = $j(this).val();
       	if(!isEmail(applicant_email))
	    {
	    	alert("被保险人eamil输入有误，请重新输入!");
	    	return;
	    }
     	//alert("email ok");
    });

    
    //////////////////////////////////////////////////////////////////////
    //http://api.jqueryui.com/datepicker/
    $j.datepicker.regional["zh-CN"] = {closeText: "关闭", prevText: "&#x3c;上月", nextText: "下月&#x3e;", currentText: "今天", monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"], monthNamesShort: ["一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二"], dayNames: ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"], dayNamesShort: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"], dayNamesMin: ["日", "一", "二", "三", "四", "五", "六"], weekHeader: "周", dateFormat: "yy-mm-dd", firstDay: 1, isRTL: !1, showMonthAfterYear: !0, yearSuffix: "年"};

    $j.datepicker.setDefaults($j.datepicker.regional["zh-CN"]);
    
    var start_day = $j("input#start_day").val();
    
    //alert(start_day);
	if($('#cp_name').val()!='taipingyang_hangkong'){
		if(start_day==0)
		{
			start_day = 5;
		}
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
    
    //alert(start_day);
    var pickerOpts = {
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        minDate: time,//start_day
        onSelect: function (dateText, inst) {
            var period = $j("input#period").val();
			 
            var t = timeStamp2String(dateText, period);
             
            $j("#endDate").val(t);
            
            var startdate_old = $j("#startDate").val();
            $j("#startDate").val(startdate_old+" 00:00:00");
        }
    };

    $j("#startDate").datepicker(pickerOpts);

    ///////////////////////////////////////////////////
    
    var pickerOpts1 = {
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            minDate: start_day,
            onSelect: function (dateText, inst) {
               // var period = $j("input#period").val();
                //var t = timeStamp2String(dateText, period);
                //alert(t);
                //$j("#endDate").val(t);
                
               // var startdate_old = $j("#startDate").val();
               // $j("#startDate").val(startdate_old+" 00:00:00");
            }
        };
	 
    $j("#orderDate").datepicker(pickerOpts1);
   // $j("#takeoffDate").datepicker(pickerOpts2);
    $j("#landDate").datepicker(pickerOpts1);
	//带时分秒的弹出框  使用的是datetimepicker  并且必须加载ui.min.js
	if($('#cp_name').val()=='taipingyang_hangkong'){  //太平洋航空
		$j("#takeoffDate").datetimepicker({
				 
				showSecond: false,
				timeFormat: 'hh:mm',
				stepHour: 1,
				stepMinute: 1,
				stepSecond: 0,
				minDate: start_day,
				onClose:function(dateText){
					var date_timesTemp = $j("#takeoffDate").val();
					$j('#startDate').val(date_timesTemp+':00'); 
					var end_time = timeStamp2String(dateText,2);
					$j('#endDate').val(end_time);
					 
				}
				 
			});
	}
     
    ///////////////////////////////////////////////////
    function check_buy_submit_taipingyang() {
        var bat_post_policy = $j("input#bat_post_policy").val();//add by wangcya, 20150106

        var businessType = $j("input#businessType").val();
		if($('#cp_name').val()=='taipingyang_hangkong'){
			if (!$j("input#takeoffDate").val()) {
				alert("乘机时间不能为空！");
				window.setTimeout("document.getElementById('takeoffDate').focus();", 0);
	
				return false;
			} 
		}else{
			if (!$j("input#startDate").val()) {
				alert("保险起期没填写！");
				window.setTimeout("document.getElementById('startDate').focus();", 0);
	
				return false;
			}
	
			if (!$j("input#endDate").val()) {
				alert("保险止期没填写！");
				window.setTimeout("document.getElementById('endDate').focus();", 0);
	
				return false;
			}
		}
        

        if(bat_post_policy==0){
            //购买信息检查

            if (businessType == 1)//single
            {

                //投保人信息检查
                if (!$j("input#applicant_fullname").val()) {
                    alert("投保人姓名没填写！");
                    window.setTimeout("document.getElementById('applicant_fullname').focus();", 0);

                    return false;
                }
                else if (!$j("select#applicant_certificates_type").val()) {
                    alert("投保人证件类型没填写！");
                    window.setTimeout("document.getElementById('applicant_certificates_type').focus();", 0);

                    return false;
                }
                else if (!$j("input#applicant_certificates_code").val()) {
                    alert("投保人证件号码没填写！");
                    window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0);

                    return false;
                }
                else if (!$j("input#applicant_birthday").val()) {
                    alert("投保人生日没填写！");
                    window.setTimeout("document.getElementById('applicant_birthday').focus();", 0);

                    return false;
                }
                else if (!$j("input#applicant_mobilephone").val()) {
                    alert("投保人手机没填写！");
                    window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0);

                    return false;
                }
                else if (!$j("input#applicant_email").val()) {
                    alert("投保人email没填写！");
                    window.setTimeout("document.getElementById('applicant_email').focus();", 0);

                    return false;
                }
                /////////////////////////////////////////////////////////////////

                var applicant_certificates_type = $j("select#applicant_certificates_type").val();
                var applicant_certificates_code = $j("input#applicant_certificates_code").val();
                if (applicant_certificates_type == 1 &&
                    isIdCardNo(applicant_certificates_code) != 0
                ) {
                    alert("投保人证件号码输入有误，请重新输入!");
                    window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0);

                    return false;
                }
                /////////////////////////////////////////////////////////////////
                var applicant_birthday = $j("input#applicant_birthday").val();
                var applicant_age = getAgeByBirthday(applicant_birthday);
                if (applicant_age>65 || applicant_age <18) {
                    alert("投保人的年龄必须在18-65之间！");
                    window.setTimeout("document.getElementById('applicant_birthday').focus();", 0);

                    return false;
                }
                /////////////////////////////////////////////////////////////////
                var applicant_mobilephone = $j("input#applicant_mobilephone").val();
                if (!isMobile(applicant_mobilephone)) {
                    alert("投保人手机号码输入有误，请重新输入!");
                    window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0);

                    return false;
                }

                var applicant_email = $j("input#applicant_email").val();
                if (!isEmail(applicant_email)) {
                    alert("投保人email输入有误，请重新输入!");
                    window.setTimeout("document.getElementById('applicant_email').focus();", 0);

                    return false;
                }

            }
            else {//投保人是团体
                if (!$j("input#applicant_group_name").val()) {
                    alert("投保机构名称没填写！");
                    window.setTimeout("document.getElementById('applicant_group_name').focus();", 0);

                    return false;
                }
                else if (!$j("select#applicant_group_certificates_type").val()) {
                    alert("投保机构证件类型没填写！");
                    window.setTimeout("document.getElementById('applicant_group_certificates_type').focus();", 0);

                    return false;
                }
                else if (!$j("input#applicant_group_certificates_code").val()) {
                    alert("投保机构证件号码没填写！");
                    window.setTimeout("document.getElementById('applicant_group_certificates_code').focus();", 0);

                    return false;
                }
                else if (!$j("input#applicant_group_mobilephone").val()) {
                    alert("投保机构的联系手机号码没填写！");
                    window.setTimeout("document.getElementById('applicant_group_mobilephone').focus();", 0);

                    return false;
                }
                else if (!isMobile($j("input#applicant_group_mobilephone").val())) {
                    alert("投保机构的联系手机号码格式错误！");
                    window.setTimeout("document.getElementById('applicant_group_mobilephone').focus();", 0);

                    return false;
                }
                else if (!$j("input#applicant_group_email").val()) {
                    alert("投保机构的联系email没填写！");

                    window.setTimeout("document.getElementById('applicant_group_email').focus();", 0);

                    return false;
                }
                else if (!isEmail($j("input#applicant_group_email").val())) {
                    alert("投保机构的联系email格式错误！");
                    window.setTimeout("document.getElementById('applicant_group_email').focus();", 0);

                    return false;
                }
            }
            //被保险人数据检查

            var relationshipWithInsured = $j("select#relationshipWithInsured").val();
            //alert("relationshipWithInsured: "+relationshipWithInsured);
			var assured_birthday = $j("input#assured_birthday").val();
            var assured_age = getAgeByBirthday(assured_birthday);
            if (relationshipWithInsured != 1)//非本人才判断被保险人
            {
				
                //if(businessType==1)//add by wangcya , 20141224, single
                if (bat_post_policy == 0)//add by wangcya, 20150106,单个投保的方式
                {
                    if (!$j("input#assured_fullname").val()) {
                        alert("被保险人姓名没填写！");
                        window.setTimeout("document.getElementById('assured_fullname').focus();", 0);

                        return false;
                    }
                    else if (!$j("select#assured_certificates_type").val()) {
                        alert("被保险人证件证件类型没填写！");

                        return false;
                    }
                    else if (!$j("input#assured_certificates_code").val()) {
                        alert("被保险人证件号码没填写！");
                        window.setTimeout("document.getElementById('assured_certificates_code').focus();", 0);

                        return false;
                    }
                    else if (!$j("input#assured_birthday").val()) {
                        alert("被保险人生日没填写！");
                        window.setTimeout("document.getElementById('assured_birthday').focus();", 0);

                        return false;
                    }
                    else if (!$j("input#assured_mobilephone").val()) {
                        alert("被保险人手机没填写！");
                        window.setTimeout("document.getElementById('assured_mobilephone').focus();", 0);

                        return false;
                    }
                    else if (!$j("input#assured_email").val()) {
                        alert("被保险人email没填写！");
                        window.setTimeout("document.getElementById('assured_email').focus();", 0);

                        return false;
                    }

                    ////////////////////////////////////////////////////////////
                    var assured_certificates_type = $j("select#assured_certificates_type").val();
                    var assured_certificates_code = $j("input#assured_certificates_code").val();
                    if (assured_certificates_type == 1 &&//身份证
                        isIdCardNo(assured_certificates_code) != 0
                    ) {
                        alert("被保险人证件号码输入有误，请重新输入!");
                        window.setTimeout("document.getElementById('assured_certificates_code').focus();", 0);

                        return false;
                    }

                    ///////////////////////////////////////////////////////////////////
                    var assured_birthday = $j("input#assured_birthday").val();
                    var assured_age = getAgeByBirthday(assured_birthday);

                    var age_min = $j("input#age_min").val();
                    var age_max = $j("input#age_max").val();
                    if (assured_age < age_min || assured_age > age_max) {
                        alert("被保险人的当前年龄：" + assured_age + "岁，不在投保年龄范围内！");

                        return false;
                    }
                    ///////////////////////////////////////////////////////
                    var assured_mobilephone = $j("input#assured_mobilephone").val();
                    if (!isMobile(assured_mobilephone)) {
                        alert("被保险人手机号码输入有误，请重新输入!");
                        window.setTimeout("document.getElementById('assured_mobilephone').focus();", 0);

                        return false;
                    }

                    var assured_email = $j("input#assured_email").val();
                    if (!isEmail(assured_email)) {
                        alert("被保险人email输入有误，请重新输入!");
                        window.setTimeout("document.getElementById('assured_email').focus();", 0);

                        return false;
                    }

                    //alert(text);
                    //被保险的的省份
                    var text = $j("select#insured_province_code").find("option:selected").text();
                    $j("input#insured_province").val(text);
                    //被保险的的市
                    var text = $j("select#insured_city_code").find("option:selected").text();
                    $j("input#insured_city").val(text);


                }//bat_post_policy==0

            }
            else//本人
            {
                var assured_birthday = $j("input#applicant_birthday").val();
            }
        }
		//对于批量上传内容的处理
        else if($('input[name="assured_input_type"]:checked').val()==2 && bat_post_policy==1)
        {

            if(checkedAll_New()==false)
            {  //验证上传文件
                var sAge = parseInt($('#age_min').val());//被保险人最小年龄
                var eAge = parseInt($('#age_max').val());//被保险人最大年龄
                layer.alert('验证失败可能原因如下：<br>1.投保人类型、身份类型、身份证件号码无效,<br>2.投保人年龄不在投保范围之内(18 至 65岁)<br>3.被保险人员数小于1人或者大于3000人<br>4.被保险人名单中重复的证件号码、手机号、邮箱<br>5.被保险人身份证件号码无效<br>6.被保险人年龄不在投保范围之内('+sAge+' 至 '+eAge+'岁)<br><br>请核实后再次提交!');
                return false;
            }

        }
        /*
        if (!$j("input#applicant_sex").val())
        {
            alert("投保人性别没填写！");
            return false;
        }
        */

        //////////////////////////////////////////////////////////////////////
        var text = $j("select#applicant_province_code").find("option:selected").text();
        $j("input#applicant_province").val(text);

        //选择市的是偶，则得到对应的市的名称。
        var text = $j("select#applicant_city_code").find("option:selected").text();
        $j("input#applicant_city").val(text);
        

    
        
        return true;
    }
    
    ////////////////////////////////////////////////////////
    $j("a#btnNext").click(function () {
        var bat_post_policy_temp = $('#bat_post_policy').val();
        var res = check_buy_submit_taipingyang();
		 
    	if(!res)
    	{
    		return false;
    	}
    	
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
});