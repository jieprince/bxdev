
/**
 * 提现专用js
 * 
 */

var platform_type;

function verification_code(type){
	
	platform_type=type;
	var res = withdraw_check();
	
	if(!res){
	 return;
	}
	
	//弹出计算确认框，让用户确认是否提现
	if(type=='mobile'){
		mobile_withdraw_confirm_dailog(res);
	}else if(type=='pc'){
		withdraw_confirm_dailog(res);
	}

  
}
 
function checkNum(value){  
   //add yes123 2014-11-30 md5加密后再和服务器返回过来的比较
   value = $.md5(value);
   if(code != '' && value != ''){
	   if(code == value){
	   	 	document.getElementById("div").innerHTML='<img src="./images/yes.gif"/>';
	    	$("#tr").hide();
	     }else{
	   	 	document.getElementById("div").innerHTML='<img src="./images/no.gif"/>';
	     }
     }
}

    
function withdraw_check()
{
	
	 var res = {};
   	  //校验必选项
   	  //银行帐号
   	  var bid = $('#formSurplus input[name="bid"]:checked ').val();//PC端
   	  if(!bid){
   		  bid = $('#bid').val(); //微信端
   		  if(!bid){
   	   		  alert("请选择提现银行！");
   	   		  return false;  
   		  }
   	  }
   	  
   	 res['bid']=bid;
   	   
   	  //提现目标账户
   	  var money_type = $('#formSurplus input[name="money_type"]:checked ').val(); //PC端
	   
   	  if(!money_type){
   		  money_type = $('#money_type').val(); //微信端
   		if(!money_type){
 		  alert("请选择提现账户！");
   		  return false;	
   		}
   	  }	 
   	 
   	 res['money_type']=money_type;
   		 
   	  //add yes123 2014-12-19 获取用户要提现或者转账的金额	  校验金额 
   	  var money = $('#withdraw_money').val();
   	  var flag=ismoney(money);
   	  if(!flag){
   		  return false;
   	  }
  	  money = parseFloat(money);
   	  if(money<=0){
   		  alert("提现金额不能小于等于0");
   		  return false;
   	  }
   	  
   	  res['money']=money;
      
   	  var min_withdraw_money = $("#"+money_type+"_min_withdraw_money").val();
   	      min_withdraw_money = parseFloat(min_withdraw_money);
   	  var max_withdraw_money = $("#"+money_type+"_max_withdraw_money").val();
   	      max_withdraw_money = parseFloat(max_withdraw_money);
   	  if(money<min_withdraw_money)
   	  {
   		  alert("提现金额不能小于"+min_withdraw_money);
   		  return false; 
   		  
   	  }
   	  
   	  if(money>max_withdraw_money)
   	  {
   		  alert("提现金额不能大于"+max_withdraw_money);
   		  return false; 
   		  
   	  }
   	  
   	  
      var usable_money = $("#"+money_type).val();
      usable_money = parseFloat(usable_money);

   	  if(money>usable_money){
   		  alert("输入的金额"+money+"不能大于可提现金额"+usable_money+"！");
   		  return false;
   	  }
      
   	  
	return res;
	
}

//PC端
function withdraw_confirm_dailog(data){
	var load_dialog = layer.load('加载中');
	var dialog;
	var money_type = data.money_type;
	var money = data.money;
	$.ajax({
		   type: "POST",
		   url: "user.php",
		   async: false,
		   dataType:"json",
		   data: "act=withdraw_confirm_info&money_type="+money_type+"&withdraw_money="+money,
		   success: function(res){
			    console.info(res);
			   
	 			var html_str = "<table><tr><td width='100px' >&#12288;提现金额：</td><td >￥"+res.money+"元</td></tr>"+
					       "<tr><td>&#12288;&#12288;手续费：</td><td>￥"+res.withdraw_poundage+"元</td></tr>"+
						   "<tr><td>个人所得税：</td><td >￥"+res.income_tax+"元</td></tr>"+
						   "<tr><td>&#12288;实际到账：</td><td >￥"+res.actual_money+"元</td></tr></table>";
						   
				dialog = $.layer({
				    shade: [0],
				    area: ['auto','auto'],
				    dialog: {
				        msg: html_str+'<span style="color: red">您确定要提现吗？</span>',
				        btns: 2,                    
				        type: 4,
				        btn: ['确定','取消'],
				        yes: function(){
				        	layer.close(dialog);
				        	layer.close(load_dialog);
				        	send_sms(res,'user.php');
				        }, no: function(){
				        }
				    }
				});	 
				
		   }
		});
	
	
}

//微信端
function mobile_withdraw_confirm_dailog(data){
	
	var loading = layer.open({
	    type: 2,
	    content: '加载测试中'
	});
	
	var dialog;
	var money_type = data.money_type;
	var money = data.money;
	$.ajax({
		   type: "POST",
		   url: "account.php",
		   async: false,
		   dataType:"json",
		   data: "act=withdraw_confirm_info&money_type="+money_type+"&withdraw_money="+money,
		   success: function(res){
			    layer.close(loading);
	 	   		var html_str = "<table><tr><td width='100px' >&#12288;提现金额：</td><td >￥"+res.money+"元</td></tr>"+
						       "<tr><td>&#12288;&#12288;手续费：</td><td>￥"+res.withdraw_poundage+"元</td></tr>"+
							   "<tr><td>个人所得税：</td><td >￥"+res.income_tax+"元</td></tr>"+
							   "<tr><td>&#12288;实际到账：</td><td >￥"+res.actual_money+"元</td></tr></table>";
				html_str+='<span style=color:red>您确定要提现吗？</span>';
			  	dialog =  layer.open({
				    content: html_str,
				    btn: ['确认', '取消'],
				    shadeClose: false,
				    yes: function(){
				    	layer.close(dialog);
				    	send_sms(res,'account.php');
				    
				    }, no: function(){
				    	layer.close(dialog);
				    }
				});
			   
		   }
		}); 
}


function send_sms(res,url){
	//disable_select(true);//发送短信，禁止修改其他项
	var money = res.money;
	$.post(url,{
	   'act':'withdrawals',
	   'judge':$.trim($("[name=judge]").val()),
	   'money':money
	},function(datas){
		var obj = JSON.parse(datas);
		code = obj.num;
		if(obj.data == 0){
			$('#account').attr("readOnly",true); //发送短信成功后，金额禁止修改
			var step = 119;
			$('#btn').val('重新发送120');
			var flag = setInterval(function(){
			$("#btn").attr("disabled", true);//设置disabled属性
			$('#btn').val('重新发送'+step);
			step -= 1;
			
			if(step <= 0){
				 $("#btn").removeAttr("disabled"); //移除disabled属性
				 $('#btn').val('免费获取验证码');
				 code = '';
				 clearInterval(flag);//清除setInterval
			}
			},1000);
	  }else if(obj.data == -5){
	    alert('请稍后再试!');
	  }else if(obj.data==10){ //add yes123 2014-12-19  提示用户原因
	  	alert(obj.msg);
	  }else if(obj.data==11){
		alert(obj.msg);
		var go_url='';
		if(platform_type=='mobile'){
			go_url='account.php?act=account_log&process_type=1';
		}else if(platform_type=='pc'){
			go_url='user.php?act=account_log&process_type=1';
		}
		
		window.location.href=go_url;
		
	  }else{
	  	alert('发送失败!');
	  }
	 },'text');
	
}

function check_account(money_type){
	var flag = true;
	var is_withdraw = $("#"+money_type+"_is_withdraw").val();//校验提现次数
	var is_permit_withdraw = $("#"+money_type+"_is_permit_withdraw").val();//校验是否允许提现

	if(is_withdraw=='n')
	{
		var month_withdraw_num = $("#"+money_type+"_month_withdraw_num").val();
		alert('此账户月提现次数最大为:'+month_withdraw_num+",您的提现次数已满，请次月操作！");
		flag=false;
		
	}
	
	if(is_permit_withdraw=='n')
	{
		
		alert('抱歉，此账户不允许提现！');
		flag=false;
	}
	
	return flag;
}





    