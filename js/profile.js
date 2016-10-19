/**
 * 用户编辑资料校验
 * 
 * 
 */

// add yes123 2015-01-07 验证真实姓名
function real_name(val, tag) {
	var msg = '真实姓名必填！\n';
	if (val.length < 2) {
		if (tag) {
			return msg;
		}
		alert(msg);
	} else {
		return "";
	}
}

// 校验资格证有效期
function certificate_expiration_date(card, tag) {
	var certificate_expiration_date = $.trim($("#certificate_expiration_date")
			.val());
	if (certificate_expiration_date.length < 4) {
		if ($("#yjyx").attr("checked") == 'checked') {
			return '';
		}
		var msg = "请填写资格证有效期";
		if (tag) {
			return msg;
		}
		alert(msg);
	} else {
		return "";
	}
}

// 校验身份证号码
function isCardNo(card, tag) {
	// 身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X
	var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
	if (reg.test(card) === false) {
		var msg = "身份证输入不合法";
		if (tag) {
			return msg;
		}
		alert(msg);
	} else {
		return "";
	}
}

// 校验手机号码
function phone_validate(val, tag) {
	if (!/^1[0-9]{10}$/.test(val)) {
		var msg = '手机号码不正确!\n手机号码必填!\n';
		if (tag) {
			return msg;
		}
		alert(msg);
	} else {
		return '';
	}
}

//校验输入的两次密码是否一致
function check_conform_password(password1,password2,tag) {
	var msg ="";
	password1 = $.trim(password1);
	password2 = $.trim(password2);
	
	var patrn=/^(\w){6,20}$/;  
	if (!patrn.exec(password1)) {
		msg="密码格式不正确!\n只能输入6-20个字母、数字、下划线！\n"
		if (tag) {
			return msg;
		}
		return msg
	}
	if (password1!=password2) {
		var msg = '密码和确认密码不一致，请检查！\n';
		if (tag) {
			return msg;
		}
		alert(msg);
	} 
	
	return "";
}

//校验输入的两次密码是否一致
function password_validate(password,tag) {
	var msg ="";
	
	var patrn=/^(\w){6,20}$/;  
	if (!patrn.exec(password)) {
		msg="密码格式不正确!\n只能输入6-20个字母、数字、下划线！\n"
		if (tag) {
			return msg;
		}
		return msg
	}
	return "";
}


//校验邮箱地址
function email_validate(val, tag) {
	if (!/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/
			.test(val)) {
		var msg = '邮件格式不支持！支持格式：xxxx@xxx.xxx!\n邮件地址必填!\n';
		if (tag) {
			return msg;
		}
		alert(msg);
	} else {
		return "";
	}
}

// 校验邮政编码
function Zone_Code(val, tag) {
	if (!/^[0-9]{6}$/.test(val)) {
		var msg = '邮政编码不正确!\n邮政编码必填!\n';
		if (tag) {
			return msg;
		}
		alert(msg);
	} else {
		return "";
	}
}

// start add yes123 2014-12-29 通过身份证 号码获取并设置生日 ,性别
function setBirthday() {
	var cardId = $.trim($("#CardId").val());
	isCardNo(cardId);
	var birthday = getBirthdatByIdNo(cardId);

	$("#birthday").val(birthday);
	$("input[name='sex'][value=" + getSexById(cardId) + "]").attr("checked",
			true);
}

// 获取生日
function getBirthdatByIdNo(iIdNo) {
	var tmpStr = "";
	var strReturn = "";
	if (iIdNo.length == 15) {
		tmpStr = iIdNo.substring(6, 12);
		tmpStr = "19" + tmpStr;
		tmpStr = tmpStr.substring(0, 4) + "-" + tmpStr.substring(4, 6) + "-"
				+ tmpStr.substring(6)
		return tmpStr;
	} else {
		tmpStr = iIdNo.substring(6, 14);
		tmpStr = tmpStr.substring(0, 4) + "-" + tmpStr.substring(4, 6) + "-"
				+ tmpStr.substring(6)
		return tmpStr;
	}
}

// 获取性别
function getSexById(cardId) {
	if (cardId.length == 15) {
		var sex = cardId.substr(14, 1) % 2 ? 1 : 2;
	} else if (cardId.length == 18) {
		var sex = cardId.substr(16, 1) % 2 ? 1 : 2;
	}
	return sex;

}
// end add yes123 2014-12-29 通过身份证 号码获取并设置生日

// 证书过期时间和‘永久有效’只能选择其一
function clearOtherDate(type) {
	if (type == 1) {
		$("#certificate_expiration_date").val('');
	} else {
		$("#yjyx").attr("checked", false);
	}

}

//将form表单元素的值序列化成对象
function serializeObject(form) {
    var o = {};
    $.each(form.serializeArray(), function(index) {
        if (o[this['name']]) {
            o[this['name']] = o[this['name']] + "," + this['value'];
        } else {
            o[this['name']] = this['value'];
        }
    });
    return o;
};

//校验手机号码
function mobile_phone_validate(mobile_phone, tag) {
	
	if (/^1[0-9]{10}$/.test(mobile_phone)) {
		return true
	} else {
		var msg = '手机号码不正确!\n手机号码必填!\n';
		if (tag) {
			alert(msg) ;
		}
		return false;
	}
}

function is_exist_by_value(field_name,field_value,user_id){
	
	 var flag = false;
	 $.ajax({
		   url: "user.php",
		   type: "GET",
		   async: false,
		   data: "act=is_exist_by_value&field_name="+field_name+"&field_value="+field_value+"&user_id="+user_id,
		   success: function(res){
			   if(res=='true'){
				   flag=true; 
			   }else if(res=='false'){
				   flag=false; 
			   }
			   
		   }
		 });
	  return flag;
}
