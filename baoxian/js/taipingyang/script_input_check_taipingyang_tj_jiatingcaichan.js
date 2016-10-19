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
	
	//与被保险人关系
    $j("select#relationshipWithInsured").change(function () {
        var val_this = $j(this).val();
        if(val_this==0){
             $('#assured_fullname').val($('#applicant_fullname').val());
			 $('#assured_certificates_type').val($('#applicant_certificates_type').val());
			 $('#assured_certificates_code').val($('#applicant_certificates_code').val());
			 $('#assured_mobilephone').val($('#applicant_mobilephone').val());
			 $('#assured_email').val($('#applicant_email').val());
			 
        }else{
			$('#assured_fullname').val('');
			$('#assured_certificates_type').val('');
			$('#assured_certificates_code').val('');
			$('#assured_mobilephone').val('');
			$('#assured_email').val('');
		}
         
    }); 
	$j("select#relationshipWithInsured").val('4');
	 
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
            $j("#startDate").val(dateText.replace(/-/g, ""));
			var t = timeStamp2String(dateText, period);
			t = timeStamp2String(t.substring(0,10),2,'day'); 
			$j("#endDate").val(t.substring(0,10).replace(/-/g, ""));
        }
    };
	 

    $j("#startDate").datepicker(pickerOpts);
	 
    ///////////////////////////////////////////////////
    
	 
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
				alert("保险起期填写有误！");
				window.setTimeout("document.getElementById('startDate').focus();", 0);
	
				return false;
			}
	
			if (!$j("input#endDate").val()) {
				alert("保险止期填写有误！");
				window.setTimeout("document.getElementById('endDate').focus();", 0);
	
				return false;
			}
		}
        

        if(bat_post_policy==0){
            //购买信息检查
			var str_applicant_type = $('#applicant_type_s_g input[name="applicant_type"]:checked').val(); 
            if (str_applicant_type == 1)//single
            {
                //投保人信息检查
                if (!check_applicant_name()) {
                    alert("投保人姓名填写有误！");
                    window.setTimeout("document.getElementById('applicant_fullname').focus();", 0);
                    return false;
                }
                else if (!$j("select#applicant_certificates_type").val()) {
                    alert("投保人证件类型填写有误！");
                    window.setTimeout("document.getElementById('applicant_certificates_type').focus();", 0);

                    return false;
                }
                else if (!check_applicant_card()) {
                    alert("投保人证件号码填写有误！");
                    window.setTimeout("document.getElementById('applicant_certificates_code').focus();", 0);

                    return false;
                }
                
                else if (!check_applicant_mobilephone()) {
                    alert("投保人手机输入有误！");
                    window.setTimeout("document.getElementById('applicant_mobilephone').focus();", 0);

                    return false;
                }
                else if (!check_applicant_email()) {
                    alert("投保人email输入有误！");
                    window.setTimeout("document.getElementById('applicant_email').focus();", 0);

                    return false;
                }
                /////////////////////////////////////////////////////////////////
 
				
				var relationshipWithInsured = $j("select#relationshipWithInsured").val();
				//alert("relationshipWithInsured: "+relationshipWithInsured);
				 
				if (relationshipWithInsured != 0)//非本人才判断被保险人
				{
					
					//if(businessType==1)//add by wangcya , 20141224, single
					if (bat_post_policy == 0)//add by wangcya, 20150106,单个投保的方式
					{
						if (!check_assured_name()) {
							alert("被保险人姓名填写有误！");
							window.setTimeout("document.getElementById('assured_fullname').focus();", 0);
	
							return false;
						}
						else if (!$j("select#assured_certificates_type").val()) {
							alert("被保险人证件证件类型没选择！");
	
							return false;
						}
						else if (!check_assured_card()) {
							alert("被保险人证件号码填写有误！");
							window.setTimeout("document.getElementById('assured_certificates_code').focus();", 0);
	
							return false;
						}
						 
						else if (!check_assured_mobile_phone()) {
							alert("被保险人手机填写有误！");
							window.setTimeout("document.getElementById('assured_mobilephone').focus();", 0);
	
							return false;
						}
						else if (!check_assured_email()) {
							alert("被保险人email填写有误！");
							window.setTimeout("document.getElementById('assured_email').focus();", 0);
	
							return false;
						}else if(!checkAllEditTag_Null('insured_address')){
							alert("被保险人详细地址不能为空！");
							window.setTimeout("document.getElementById('insured_address').focus();", 0);
	
							return false;
						}else if(!check_zipcode('insured_zipcode')){
							alert("被保险人邮政编码有误！");
							window.setTimeout("document.getElementById('insured_zipcode').focus();", 0);
	
							return false;
						}
					}//bat_post_policy==0
	
				}
				 
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
    	$('#insured_address').val('天津市 '+$('#insured_address').val())
        //////////////////////////////////////
        $j("input#submit1").click();
    });
});


 