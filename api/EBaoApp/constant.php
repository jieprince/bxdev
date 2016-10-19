<?php
//定义app客户端常量

define('SERVERURL', 'http://182.92.156.137:82');//环境

/* del by wangcya, 20150119
define('ANDROIDPLATFORM', '103');//android平台id
define('IPHONEPLATFORM', '123');//iPhone平台id
define('WEIXINPLATFORM', '133');//微信平台id
*/

//add by wangcya, 20150119
define('ANDROIDPLATFORM', 'android');//android平台id
define('IPHONEPLATFORM', 'iPhone');//iPhone平台id
define('WEIXINPLATFORM', 'weixin');//微信平台id
define('PLATFORM_PC', 'pc');//add by wangcya, 20150119

//software
define('PLATFORMID', 'platformId');
define('EDITIONID', 'editionId');
define('DEVICETOKEN', 'token');
define('ACCESSTOKEN', 'accessToken');

//信鸽推送通知
define('XG_PN_ANDROID_ACCESSID', '2100059360');//android的推送id
define('XG_PN_ANDROID_ACCESSKEY', '0342192cc3aa55e92cfab9139274a90c');//android的推送密钥
define('XG_PN_IPHONE_ACCESSID', '2200059361');//iPhone的推送id
define('XG_PN_IPHONE_ACCESSKEY', 'b4093cd9fdd76fcc986a40f66a7a304b');//iPhone的推送密钥

//会员审核推送通知消息
define('XG_PN_MSG_TITLE', '易保险');
define('XG_PN_CHECKUSER_SUCCESSMSG_CONTENT', '您的账号已通过审核，已成为易保险的正式会员。');
define('XG_PN_CHECKUSER_FAILMSG_CONTENT', '您的账号审核不通过，请完善您的个人真实信息。');

//software
define('APP_PLATFORM_ANDROID', 'Android');
define('APP_PLATFORM_IPHONE', 'iPhone');
define('APP_PLATFORM_WINDOWSPHONE', 'windowsphone');
define('APP_PLATFORM_PCWEB','pcweb');
define('APP_PROTOCOL_VERSION', '1.0');//协议版本号

//登录
define('APP_COMMAND_LOCATION', 'LocationCollect');//响应命令 登录
define('APP_LOCATIONSUCCESS_CONTENT', '位置信息获取成功成功。');
define('APP_LOCATIONFAIL_CONTENT', '位置信息获取失败，请稍后重试或检查网络连接。');

//登录
define('APP_COMMAND_LOGIN', 'login');//响应命令 登录
define('APP_ALERT_TITLE', '提示');
define('APP_LOGINSUCCESS_CONTENT', '登录成功。');
define('APP_LOGINFAIL_CONTENT', '您输入的用户名和密码不匹配，请重新输入。');
define('APP_LOGINNOUSER_CONTENT', '您输入的用户名不存在，请核对后重新输入。');

//注册
define('APP_COMMAND_REGISTER', 'register');
define('APP_REGISTERSUCCESS_CONTENT', '恭喜您，注册成功，您已成为我们的会员。');
define('APP_REGISTERUSEREXIST', '啊哦，用户名已存在，注册不了。');
define('APP_REGISTERFAIL_CONTENT', '注册失败，请稍后重试或检查网络连接。');

//短信验证码
define('APP_COMMAND_SMS', 'sms');
define('APP_SMSSUCCESS_CONTENT', '发送短信验证码成功。');
define('APP_SMSFAIL_CONTENT', '发送短信验证码失败，请稍后重试。');

//找回密码
define('APP_COMMAND_RETRIEVEPW', 'retrievepassword');
define('APP_RETRIEVEPWSUCCESS_CONTENT', '新密码已发送您的手机%s%上，请注意查收。');
define('APP_RETRIEVEPWFAIL_CONTENT', '用户名不存在。');
define('APP_RETRIEVEPWNOMATCH_CONTENT', '用户名和邮箱地址不匹配，请重新输入。');

//修改密码
define('APP_COMMAND_MODIFYPW', 'modifypassword');
define('APP_MODIFYPWSUCCESS_CONTENT', '密码修改成功，请用新密码登录。');
define('APP_MODIFYPWFAIL_CONTENT', '密码修改失败，请稍后重试。');

//获取用户信息
define('APP_COMMAND_PROFILE', 'profile');
define('APP_PROFILESUCCESS_CONTENT', '获取用户信息成功。');
define('APP_PROFILEFAIL_CONTENT', '获取用户信息失败，请稍后重试。');

//更新用户信息
define('APP_COMMAND_UPDATEPROFILE', 'updateprofile');
define('APP_UPDATEPROFILESUCCESS_CONTENT', '更新用户信息成功。');
define('APP_UPDATEPROFILEFAIL_CONTENT', '更新用户信息失败，请稍后重试。');
define('APP_UPDATEPROFILEFAIL_EAMILEXIST_CONTENT', '更新用户信息失败，邮件地址已存在。');
define('APP_UPDATEPROFILEFAIL_IDEXIST_CONTENT', '更新用户信息失败，证件号码已存在。');
define('APP_UPDATEPROFILEFAIL_NUMEXIST_CONTENT', '更新用户信息失败，资格证书已存在。');

//获取会员动态
define('APP_COMMAND_MEMBERNEWS', 'membernews');
define('APP_MEMBERNEWSSUCCESS_CONTENT', '获取会员动态成功。');
define('APP_MEMBERNEWSFAIL_CONTENT', '获取会员动态失败，请稍后重试。');

//获取资讯
define('APP_COMMAND_ARTICLEINFO', 'aticleinfo');
define('APP_ARTICLEINFOSUCCESS_CONTENT', '获取资讯成功。');
define('APP_ARTICLEINFOFAIL_CONTENT', '获取资讯失败，请稍后重试。');

//轮播图
define('APP_COMMAND_CYCLENEWS', 'CycleNews');
define('APP_CYCLENEWSSUCCESS_CONTENT', '获取新闻图片成功。');
define('APP_CYCLENEWSFAIL_CONTENT', '获取新闻图片失败，请稍后重试。');

//热销产品
define('APP_COMMAND_HOTPRODUCT', 'hotproduct');
define('APP_HOTPRODUCTSUCCESS_CONTENT', '获取热销产品成功。');
define('APP_HOTPRODUCTFAIL_CONTENT', '获取热销产品失败，请稍后重试。');

//快捷功能数据
define('APP_COMMAND_SHORTSHOW', 'ShortShow');
define('APP_SHORTSHOWSUCCESS_CONTENT', '数据获取成功。');
define('APP_SHORTSHOWFAIL_CONTENT', '数据获取失败，请稍后重试。');

//产品-险种大类
define('APP_COMMAND_INSURANCEKIND', 'InsuranceKind');
define('APP_INSURANCEKINDSUCCESS_CONTENT', '获取险种大类成功。');
define('APP_INSURANCEKINDFAIL_CONTENT', '获取险种大类失败，请稍后重试。');

//险种及其产品列表
define('APP_COMMAND_INSURANCE', 'insurance');
define('APP_INSURANCESUCCESS_CONTENT', '获取产品成功。');
define('APP_INSURANCENoData_CONTENT', '没有更多数据。');
define('APP_INSURANCEFAIL_CONTENT', '获取产品失败，请稍后重试。');

//投保单
define('APP_COMMAND_TEMPPOLICY', 'temppolicy');
define('APP_TEMPPOLICYSUCCESS_CONTENT', '投保单提交成功。');
define('APP_TEMPPOLICYFAIL_CONTENT', '投保单提交失败，请稍后重试。');

//添加订单
define('APP_COMMAND_ORDERSUBMIT', 'ordersubmit');
define('APP_ORDERSUBMITSUCCESS_CONTENT', '订单提交成功。');
define('APP_ORDERSUBMITFAIL_CONTENT', '订单提交失败，请稍后重试。');
define('APP_ORDERSUBMITEXIST_CONTENT', '提交失败，订单已存在。');

//查询投保结果
define('APP_COMMAND_POLICYRESULT', 'policyresult');
define('APP_POLICYRESULTSUCCESS_CONTENT', '投保结果查询成功。');
define('APP_POLICYRESULTFAIL_CONTENT', '投保结果查询失败，请稍后重试。');

//用户退出
define('APP_COMMAND_LOGOUT', 'logout');
define('APP_LOGOUTSUCCESS_CONTENT', '退出操作成功。');
define('APP_LOGOUTFAIL_CONTENT', '退出操作失败，请稍后重试。');

//订单列表
define('APP_COMMAND_ORDERLIST', 'orderlist');
define('APP_ORDERLISTSUCCESS_CONTENT', '订单查询成功。');
define('APP_ORDERLISTNODATA_CONTENT', '没有更多数据。');
define('APP_ORDERLISTFAIL_CONTENT', '订单查询失败，请稍后重试。');

//取消订单
define('APP_COMMAND_CANCELORDER', 'cancelorder');
define('APP_CANCELORDERSUCCESS_CONTENT', '订单取消成功。');
define('APP_CANCELORDERFAIL_CONTENT', '订单取消失败，请稍后重试。');

//保单列表
define('APP_COMMAND_POLICYLIST', 'policylist');
define('APP_POLICYLISTSUCCESS_CONTENT', '保单查询成功。');
define('APP_POLICYLISTNODATA_CONTENT', '没有更多数据。');
define('APP_POLICYLISTFAIL_CONTENT', '保单查询失败，请稍后重试。');

//保单详情
define('APP_COMMAND_POLICYDETAIL', 'policydetail');
define('APP_POLICYDETAILSUCCESS_CONTENT', '保单详情查询成功。');
define('APP_POLICYDETAILFAIL_CONTENT', '保单详情查询失败，请稍后重试。');

//注销保单
define('APP_COMMAND_CANCELPOLICY', 'cancelpolicy');
define('APP_CANCELPOLICYSUCCESS_CONTENT', '保单注销成功。');
define('APP_CANCELPOLICYFAIL_CONTENT', '保单注销失败，请稍后重试。');

//下载电子保单
define('APP_COMMAND_DOWNEPOLICY', 'downepolicy');
define('APP_DOWNEPOLICYSUCCESS_CONTENT', '电子保单下载成功。');
define('APP_DOWNEPOLICYFAIL_CONTENT', '电子保单下载失败，请稍后重试。');

//账单明细列表
define('APP_COMMAND_BILLLIST', 'billlist');
define('APP_BILLLISTSUCCESS_CONTENT', '账户明细查询成功。');
define('APP_BILLLISTNODATA_CONTENT', '没有更多数据。');
define('APP_BILLLISTFAIL_CONTENT', '账户明细查询失败，请稍后重试。');

//充值列表
define('APP_COMMAND_RECHARGELIST', 'rechargelist');
define('APP_RECHARGELISTSUCCESS_CONTENT', '记录查询成功。');
define('APP_RECHARGELISTNODATA_CONTENT', '没有更多数据。');
define('APP_RECHARGELISTFAIL_CONTENT', '记录查询失败，请稍后重试。');

//充值申请
define('APP_COMMAND_RECHARGE', 'recharge');
define('APP_RECHARGESUCCESS_CONTENT', '充值申请成功。');
define('APP_RECHARGEFAIL_CONTENT', '充值申请失败，请稍后重试。');

//提现申请
define('APP_COMMAND_WITHDRAWCASH', 'withdrawcash');
define('APP_WITHDRAWCASHSUCCESS_CONTENT', '提现申请成功。');
define('APP_WITHDRAWCASHFAIL_CONTENT', '提现申请失败，请稍后重试。');

//取消充值或提现申请
define('APP_COMMAND_CANCELRECHARGE', 'cancelrecharge');
define('APP_CANCELRECHARGESUCCESS_CONTENT', '取消申请成功。');
define('APP_CANCELRECHARGEFAIL_CONTENT', '取消申请失败，请稍后重试。');

//银行卡列表
define('APP_COMMAND_UNIONPAY', 'unionpay');
define('APP_UNIONPAYSUCCESS_CONTENT', '银行卡获取成功。');
define('APP_UNIONPAYNODATA_CONTENT', '没有银行卡数据。');
define('APP_UNIONPAYFAIL_CONTENT', '银行卡获取失败，请稍后重试。');

//更新银行卡
define('APP_COMMAND_UPDATEUNIONPAY', 'updateunionpay');
define('APP_UPDATEUNIONPAYSUCCESS_CONTENT', '更新银行卡成功。');
define('APP_UPDATEUNIONPAYFAIL_CONTENT', '更新银行卡失败，请稍后重试。');

//删除银行卡
define('APP_COMMAND_DELETEUNIONPAY', 'deleteunionpay');
define('APP_DELETEUNIONPAYSUCCESS_CONTENT', '删除银行卡成功。');
define('APP_DELETEUNIONPAYFAIL_CONTENT', '删除银行卡失败，请稍后重试。');

//删除银行卡
define('APP_COMMAND_ADDUNIONPAY', 'addunionpay');
define('APP_ADDUNIONPAYSUCCESS_CONTENT', '添加银行卡成功。');
define('APP_ADDUNIONPAYEXIST_CONTENT', '银行卡已存在。');
define('APP_ADDUNIONPAYFAIL_CONTENT', '添加银行卡失败，请稍后重试。');

//收益列表
define('APP_COMMAND_IMCOMELIST', 'incomelist');
define('APP_IMCOMELISTSUCCESS_CONTENT', '收益记录获取成功。');
define('APP_IMCOMELISTNODATA_CONTENT', '没有更多数据。');
define('APP_IMCOMELISTFAIL_CONTENT', '收益记录获取失败，请稍后重试。');

//发票列表
define('APP_COMMAND_INVOICELIST', 'invoicelist');
define('APP_INVOICELISTSUCCESS_CONTENT', '发票记录获取成功。');
define('APP_INVOICELISTNODATA_CONTENT', '没有更多数据。');
define('APP_INVOICELISTFAIL_CONTENT', '发票记录获取失败，请稍后重试。');

//申请开票
define('APP_COMMAND_APPLYINVOICE', 'applyinvoice');
define('APP_APPLYINVOICESUCCESS_CONTENT', '发票记录获取成功。');
define('APP_APPLYINVOICEFAIL_CONTENT', '发票记录获取失败，请稍后重试。');

//推荐列表
define('APP_COMMAND_RECOMMENDLIST', 'recommendlist');
define('APP_RECOMMENDLISTSUCCESS_CONTENT', '我的推荐获取成功。');
define('APP_RECOMMENDLISTNODATA_CONTENT', '没有更多数据。');
define('APP_RECOMMENDLISTFAIL_CONTENT', '我的推荐获取失败，请稍后重试。');

//上传图片
define('APP_COMMAND_UPLOADIMAGE', 'uploadimage');
define('APP_UPLOADIMAGESUCCESS_CONTENT', '图片上传成功。');
define('APP_UPLOADIMAGENOMATCHTYPE_CONTENT', '图片格式不正确，支持的格式为jpg,png,bmp。');
define('APP_UPLOADIMAGEOVERMAX_CONTENT', '您上传的图片超过最大限制5M。');
define('APP_UPLOADIMAGEFAIL_CONTENT', '图片上传失败，请稍后重试。');

//公司收款账号
define('APP_COMMAND_RECACCOUNT', 'CompanyRecAccount');
define('APP_RECACCOUNTSUCCESS_CONTENT', '公司收款账号获取成功。');
define('APP_RECACCOUNTFAIL_CONTENT', '公司收款账号获取失败，请稍后重试。');

//保险公司
define('APP_COMMAND_INSURERCOMPANY', 'InsurerCompany');
define('APP_INSURERCOMPANYSUCCESS_CONTENT', '保险公司获取成功。');
define('APP_INSURERCOMPANYFAIL_CONTENT', '保险公司获取失败，请稍后重试。');

//会话失效
define('APP_COMMAND_SESSIONINVALID', 'sessioninvalid');
define('APP_SESSIONINVALID_CONTENT', '会话已失效，请重新登陆。');

//客户列表
define('APP_COMMAND_CUSTOMERLIST', 'customerlist');
define('APP_CUSTOMERLISTSUCCESS_CONTENT', '我的客户获取成功。');
define('APP_CUSTOMERLISTNODATA_CONTENT', '没有更多数据。');
define('APP_CUSTOMERLISTFAIL_CONTENT', '我的客户获取失败，请稍后重试。');

//新增、修改保存客户
define('APP_COMMAND_ADDCUSTOMER', 'savecustomer');
define('APP_ADDCUSTOMERSUCCESS_CONTENT', '客户更新成功。');
define('APP_ADDCUSTOMERFAIL_CONTENT', '客户更新失败，请稍后重试。');

//删除客户
define('APP_COMMAND_DELCUSTOMER', 'delcustomer');
define('APP_DELCUSTOMERSUCCESS_CONTENT', '客户删除成功。');
define('APP_DELCUSTOMERFAIL_CONTENT', '客户删除失败，请稍后重试。');

//客户证件类型
define('APP_COMMAND_CUSTOMERCER', 'customercerlist');
define('APP_CUSTOMERCERSUCCESS_CONTENT', '客户证件类型获取成功。');
define('APP_CUSTOMERCERFAIL_CONTENT', '客户证件类型获取失败，请稍后重试。');

//限制购买
define('APP_COMMAND_LIMITPURCHASE', 'limitpurchase');
define('APP_LIMITPURCHASE_PINGAN_CONTENT', '此产品仅限北京地区销售。');
define('APP_PURCHASEBLE_PINGAN_CONTENT', '可正常销售。');

//支付包网页支付 返回的支付url
define('APP_COMMAND_ALIPAYURL', 'paymenturl');
define('APP_ALIPAYURLSUCCESSCONTENT', '网页支付url返回成功。');
define('APP_ALIPAYURLFAILCONTENT', '网页支付url返回失败。');

//对未支付的订单，不管选择哪种支付方式，如果使用余额
define('APP_COMMAND_PAYUNPAIDORDER', 'payunpayorder');
define('APP_PAYUNPAIDORDERSUCCESS_CONTENT', '余额处理成功。');
define('APP_PAYUNPAIDORDERFAIL_CONTENT', '余额处理失败。');
define('APP_PAYUNPAIDORDERINSUREDSUCCESS_CONTENT', '投保成功。');
define('APP_PAYUNPAIDORDERINSUREDFAIL_CONTENT', '投保失败。');

//收藏产品
define('APP_COMMAND_FAVORITEINS', 'favoriteins');
define('APP_FAVORITEINSSUCCESS_CONTENT', '收藏产品成功。');
define('APP_FAVORITEINSFAIL_CONTENT', '该产品已收藏。');
define('APP_CANCELFAVORITEINSSUCCESS_CONTENT', '取消收藏产品成功。');

//活动
define('APP_COMMAND_PROMOTION', 'EbaPromotion');
define('APP_PROMOTIONSSUCCESS_CONTENT', '活动信息获取成功。');

//软件更新
define('APP_COMMAND_ONLINEUPDATE', 'OnlineUpdate');
define('APP_ONLINEUPDATECONTENT', '发现新版本');
define('APP_ONLINENOUPDATECONTENT', '没有新版本');

//反馈
define('APP_COMMAND_FEEDBACK', 'Feedback');
define('APP_FEEDBACKCONTENT', '您的反馈，我们已收到。');
// define('APP_ONLINENOUPDATECONTENT', '没有新版本');

//其他通用的错误提示
define('APP_COMMAND_OTHERERROR', 'othererror');
define('APP_OTHERERRORCONTENT', '请求数据失败。');
?>