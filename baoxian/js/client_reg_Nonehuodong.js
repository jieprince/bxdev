$(function() {
	/*$("#btnNext").hide(); // 移除click
	$("#btnNext2").show(); // 移除click
	$("#btnNext2").bind("click",function() {
		window.setTimeout("document.getElementById('applicant_mobilephone').focus();",0);
		alert('请先验证投保人手机！');
	});*/
	// authenticated();
})

var checkCode = '';
function verification_code() {
	var activity_id = $("#activity_id").val();
	
	 
	var goods_id = $("#gid").val();
	// add yes123 2014-12-19 获取用户要提现或者转账的金额
	var applicant_mobilephone = $("#applicant_mobilephone_temps").val();
	// 校验手机号码
	if (!phone_validate(applicant_mobilephone)) {
		return;
	}
	$.post('../mobile/chinalife_zhuanti.php', {
		'act' : 'send_phonecode',
		'type' : 'check_c_phone',
		'activity_id' : activity_id,
		'goods_id':goods_id,
		'mobile_phone' : applicant_mobilephone
	}, function(obj) {
		checkCode = obj.num;
		if (obj.code == 0) {
			var step = 119;
			$('#btn').val('重新发送120');
			var flag = setInterval(function() {
				$("#btn").attr("disabled", true);// 设置disabled属性
				$('#btn').val('重新发送' + step);
				step -= 1;
				if (step <= 0) {
					$("#btn").removeAttr("disabled"); // 移除disabled属性
					$('#btn').val('免费获取验证码');
					code = '';
					clearInterval(flag);// 清除setInterval
				}
			}, 1000);
		} else if (obj.code == 3) {
			alert(obj.msg);
		} else if (obj.code == 10) {
			alert(obj.msg);

			authenticated(applicant_mobilephone);
		} else if (obj.code == 11) {
			alert(obj.msg);
		} else {
			alert("发送失败!");
		}
	}, 'json');
}
function checkNum(value) {
	value = $.md5(value);
	if (checkCode != '' && value != '') {
		if (checkCode == value) {
			document.getElementById("div").innerHTML = '<img src="../images/yes.gif"/>';
			$("#tr").hide();
		} else {
			document.getElementById("div").innerHTML = '<img src="../images/no.gif"/>';
		}
	}
}
function client_reg() {
	var applicant_mobilephone = $.trim($("#applicant_mobilephone_temps").val());
	var code = $.trim($("#checkCode").val());
    if(check_applicant_mobilephone('applicant_mobilephone_temps','pc') && code!=""){
        $.post('../mobile/chinalife_zhuanti.php', {
            'act' : 'client_reg',
            'code' : code,
            'mobile_phone' : applicant_mobilephone
        }, function(data) {
            if (data.code == 0) {
                alert(data.msg);
                authenticated(applicant_mobilephone);
            } else if (data.code == 10) {
                alert(data.msg);
            }
        }, 'json');
    }else{
        alert('手机号填写有误或者验证码为空!');
    }
}

function phone_validate(val) {
	if (!/^1[1-9][0-9]\d{8}$/.test(val)) {
		var msg = '手机号码不正确!\n手机号码必填!\n';
		alert(msg);
		return false;
	} else {
		return true;
	}
}

// 验证通过后，修改各种状态
function authenticated(phonum) {
    $('#applicant_mobilephone').val(phonum);
	$("#is_check_phone").val(1); // 是否已验证
	// 验证通过后，禁止编辑手机号
	$("#applicant_mobilephone").attr("readonly", "readonly");
	// 移除获取验证码操作
	$("#input_code_tr").remove();
	$("#get_code_tr").remove();
    $('#login_phone_checked').hide();
    $('#step_one_show').show();
    $('#login_checked_footer').show();


}

//触发获取openid
function get_c_openid() {
	$.ajax({
	   type: "GET",
	   url: "../mobile/chinalife_zhuanti.php",
	   data: "act=get_c_openid",
	   success: function(msg){
			
	   }
	});
}