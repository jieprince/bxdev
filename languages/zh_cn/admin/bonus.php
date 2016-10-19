<?php

/**
 * ECSHOP 代金券类型/代金券管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: bonus.php 17217 2011-01-19 06:29:08Z liubo $
*/
/* 代金券类型字段信息 */
$_LANG['bonus_type'] = '代金券类型';
$_LANG['bonus_list'] = '代金券列表';
$_LANG['type_name'] = '类型名称';
$_LANG['type_money'] = '代金券金额';
$_LANG['min_goods_amount'] = '最小订单金额';
$_LANG['notice_min_goods_amount'] = '只有商品总金额达到这个数的订单才能使用这种代金券';
$_LANG['min_amount'] = '订单下限';
$_LANG['max_amount'] = '订单上限';
$_LANG['send_startdate'] = '发放起始日期';
$_LANG['send_enddate'] = '发放结束日期';

$_LANG['use_startdate'] = '使用起始日期';
$_LANG['use_enddate'] = '使用结束日期';
$_LANG['send_count'] = '发放数量';
$_LANG['use_count'] = '使用数量';
$_LANG['send_method'] = '如何发放此类型代金券';
$_LANG['send_type'] = '发放类型';
$_LANG['param'] = '参数';
$_LANG['no_use'] = '未使用';
$_LANG['yuan'] = '元';
$_LANG['user_list'] = '会员列表';
$_LANG['type_name_empty'] = '代金券类型名称不能为空！';
$_LANG['type_money_empty'] = '代金券金额不能为空！';
$_LANG['min_amount_empty'] = '代金券类型的订单下限不能为空！';
$_LANG['max_amount_empty'] = '代金券类型的订单上限不能为空！';
$_LANG['send_count_empty'] = '代金券类型的发放数量不能为空！';

$_LANG['send_by'][SEND_BY_USER] = '按用户发放';
$_LANG['send_by'][SEND_BY_GOODS] = '按商品发放';
$_LANG['send_by'][SEND_BY_ORDER] = '按订单金额发放';
$_LANG['send_by'][SEND_BY_PRINT] = '线下发放的代金券';
$_LANG['send_by'][SEND_BY_REG] = '按注册新用户发放';
$_LANG['send_by'][SEND_BY_PARENT] = '新用户审核通过给推荐人发放';
$_LANG['report_form'] = '报表';
$_LANG['send'] = '发放';
$_LANG['bonus_excel_file'] = '线下代金券信息列表';

$_LANG['goods_cat'] = '选择商品分类';
$_LANG['goods_brand'] = '商品品牌';
$_LANG['goods_key'] = '商品关键字';
$_LANG['all_goods'] = '可选商品';
$_LANG['send_bouns_goods'] = '发放此类型代金券的商品';
$_LANG['remove_bouns'] = '移除代金券';
$_LANG['all_remove_bouns'] = '全部移除';
$_LANG['goods_already_bouns'] = '该商品已经发放过其它类型的代金券了!';
$_LANG['send_user_empty'] = '您没有选择需要发放代金券的会员，请返回!';
$_LANG['batch_drop_success'] = '成功删除了 %d 个用户代金券';
$_LANG['sendbonus_count'] = '共发送了 %d 个代金券。';
$_LANG['send_bouns_error'] = '发送会员代金券出错, 请返回重试！';
$_LANG['no_select_bonus'] = '您没有选择需要删除的用户代金券';
$_LANG['bonustype_edit'] = '编辑代金券类型';
$_LANG['bonustype_view'] = '查看详情';
$_LANG['drop_bonus'] = '删除代金券';
$_LANG['send_bonus'] = '发放代金券';
$_LANG['continus_add'] = '继续添加代金券类型';
$_LANG['back_list'] = '返回代金券类型列表';
$_LANG['continue_add'] = '继续添加代金券';
$_LANG['back_bonus_list'] = '返回代金券列表';
$_LANG['validated_email'] = '只给通过邮件验证的用户发放代金券';

/* 提示信息 */
$_LANG['attradd_succed'] = '操作成功!';
$_LANG['js_languages']['type_name_empty'] = '请输入代金券类型名称!';
$_LANG['js_languages']['type_money_empty'] = '请输入代金券类型价格!';
$_LANG['js_languages']['order_money_empty'] = '请输入订单金额!';
$_LANG['js_languages']['type_money_isnumber'] = '类型金额必须为数字格式!';
$_LANG['js_languages']['useful_life_isnumber'] = '有效期必须为数字格式!';
$_LANG['js_languages']['order_money_isnumber'] = '订单金额必须为数字格式!';
$_LANG['js_languages']['bonus_sn_empty'] = '请输入代金券的序列号!';
$_LANG['js_languages']['bonus_sn_number'] = '代金券的序列号必须是数字!';
$_LANG['send_count_error'] = '代金券的发放数量必须是一个整数!';
$_LANG['js_languages']['bonus_sum_empty'] = '请输入您要发放的代金券数量!';
$_LANG['js_languages']['bonus_sum_number'] = '代金券的发放数量必须是一个整数!';
$_LANG['js_languages']['bonus_type_empty'] = '请选择代金券的类型金额!';
$_LANG['js_languages']['user_rank_empty'] = '您没有指定会员等级!';
$_LANG['js_languages']['user_name_empty'] = '您至少需要选择一个会员!';
$_LANG['js_languages']['invalid_min_amount'] = '请输入订单下限（大于0的数字）';
$_LANG['js_languages']['send_start_lt_end'] = '代金券发放开始日期不能大于结束日期';
$_LANG['js_languages']['use_start_lt_end'] = '代金券使用开始日期不能大于结束日期';

$_LANG['order_money_notic'] = '只要订单金额达到该数值，就会发放代金券给用户';
$_LANG['type_money_notic'] = '此类型的代金券可以抵销的金额';
$_LANG['send_startdate_notic'] = '只有当前时间介于起始日期和截止日期之间时，此类型的代金券才可以发放';
$_LANG['use_startdate_notic'] = '只有当前时间介于起始日期和截止日期之间时，此类型的代金券才可以使用';
$_LANG['type_name_exist'] = '此类型的名称已经存在!';
$_LANG['type_money_error'] = '金额必须是数字并且不能小于 0 !';
$_LANG['bonus_sn_notic'] = '提示:代金券序列号由六位序列号种子加上四位随机数字组成';
$_LANG['creat_bonus'] = '生成了 ';
$_LANG['creat_bonus_num'] = ' 个代金券序列号';
$_LANG['bonus_sn_error'] = '代金券序列号必须是数字!';
$_LANG['send_user_notice'] = '给指定的用户发放代金券时,请在此输入用户名, 多个用户之间请用逗号(,)分隔开<br />如:liry, wjz, zwj';

/* 代金券信息字段 */
$_LANG['bonus_id'] = '编号';
$_LANG['bonus_type_id'] = '类型金额';
$_LANG['send_bonus_count'] = '代金券数量';
$_LANG['start_bonus_sn'] = '起始序列号';
$_LANG['bonus_sn'] = '代金券序列号';
$_LANG['user_id'] = '使用会员';
$_LANG['used_time'] = '使用时间';
$_LANG['order_id'] = '订单号';
$_LANG['send_mail'] = '发邮件';
$_LANG['emailed'] = '邮件通知';
$_LANG['mail_status'][BONUS_NOT_MAIL] = '未发';
$_LANG['mail_status'][BONUS_MAIL_FAIL] = '已发失败';
$_LANG['mail_status'][BONUS_MAIL_SUCCEED] = '已发成功';

$_LANG['sendtouser'] = '给指定用户发放代金券';
$_LANG['senduserrank'] = '按用户等级发放代金券';
$_LANG['userrank'] = '用户等级';
$_LANG['select_rank'] = '选择会员等级...';
$_LANG['keywords'] = '关键字：';
$_LANG['userlist'] = '会员列表：';
$_LANG['send_to_user'] = '给下列用户发放代金券';
$_LANG['search_users'] = '搜索会员';
$_LANG['confirm_send_bonus'] = '确定发送代金券';
$_LANG['bonus_not_exist'] = '该代金券不存在';
$_LANG['success_send_mail'] = '%d 封邮件已被加入邮件列表';
$_LANG['send_continue'] = '继续发放代金券';
?>