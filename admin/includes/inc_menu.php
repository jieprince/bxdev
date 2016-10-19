<?php

/**
 * ECSHOP 管理中心菜单数组
 * ============================================================================
 * * 版权所�?2005-2012 上海商派网络科技有限公司，并保留所有权利�?
 * 网站地址: http://www.ecshop.com�?
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改�?
 * 使用；不允许对程序代码以任何形式任何目的的再发布�?
 * ============================================================================
 * $Author: liubo $
 * $Id: inc_menu.php 17217 2011-01-19 06:29:08Z liubo $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}
$distributor_id=$_SESSION['admin_id'];

//===============会员管理start======================
//$modules['01_members']['01_users_add']              = 'users.php?act=add';
$modules['01_members']['03_users_list']             = 'users.php?act=list';  
//$modules['01_members']['02_users_check']            = 'users.php?act=list&check_status=other';
$modules['01_members']['05_user_rank_list']         = 'user_rank.php?act=list'; //会员等级
$modules['01_members']['06_reservation']            = 'reservation.php?act=list_all'; //咨询预约

//子账户管理$modules['01_members']['09_child_account']            = 'user_msg.php?act=list_all';
//===============会员管理end======================

//================保险产品start===================
//保险公司
$modules['02_insurance_product_manage']['admin_insurance_company']        = '../baoxian/space.php?do=admin_insurance_company'; //保险公司
//险种
$modules['02_insurance_product_manage']['admin_insurance_product_attribute']        = '../baoxian/space.php?do=admin_insurance_product_attribute';
//保险产品
$modules['02_insurance_product_manage']['admin_insurance_product']        = '../baoxian/space.php?do=admin_insurance_product';
//$modules['02_insurance_product_manage']['admin_insurance_interface']        = '../baoxian/space.php?do=admin_insurance_user';
//$modules['02_insurance_product_manage']['admin_insurance_type']        = '../baoxian/space.php?do=admin_insurance_type';
//保险责任
$modules['02_insurance_product_manage']['admin_insurance_duty']        = '../baoxian/space.php?do=admin_insurance_duty';
//职业分类
$modules['02_insurance_product_manage']['admin_career_category']        = '../baoxian/space.php?do=admin_career_category';
//$modules['02_insurance_product_manage']['admin_insurance_policy']        = 'policy.php?act=list';
//$modules['02_insurance_product_manage']['admin_insurance_proposal_list']        = 'policy.php?act=proposal_list'; //add by yes123 , 20141114 投保单列表

//================保险产品end====================

//================商品管理start==================
$modules['03_cat_and_goods']['01_goods_list']       = 'goods.php?act=list';         // 商品列表
//$modules['04_cat_and_goods']['02_goods_add']        = 'goods.php?act=add';          // 添加商品
$modules['03_cat_and_goods']['03_category_list']    = 'category.php?act=list';
$modules['03_cat_and_goods']['06_goods_brand_list'] = 'brand.php?act=list';
//================商品管理end====================

//================订单管理start==================
$modules['04_order']['admin_insurance_proposal_list']        = 'policy.php?act=proposal_list'; //add by yes123 , 20141114 投保单列表
$modules['04_order']['01_policy_list']        = 'policy.php?act=list';
$modules['04_order']['02_order_list']               = 'order.php?act=list';
//$modules['05_order']['03_cancel_policy_list']               = 'order.php?act=list';
/*$modules['04_order']['03_order_query']              = 'order.php?act=order_query';
$modules['04_order']['04_merge_order']              = 'order.php?act=merge';
$modules['04_order']['05_edit_order_print']         = 'order.php?act=templates';
$modules['04_order']['06_undispose_booking']        = 'goods_booking.php?act=list_all';
//$modules['04_order']['07_repay_application']        = 'repay.php?act=list_all';
$modules['04_order']['08_add_order']                = 'order.php?act=add';
$modules['04_order']['09_delivery_order']           = 'order.php?act=delivery_list';
$modules['04_order']['10_back_order']               = 'order.php?act=back_list';*/
//发票列表 2014-10-24 yes123
$modules['04_order']['11_invoice_list']               = 'order.php?act=invoice_list&receipt_assigned=waiting_process';  //modify yes123 2014-12-04 默认显示发票待受理

$modules['04_order']['20_policy_operation_log']        = 'policy.php?act=policy_operation_log'; //add by yes123 , 20141114 投保单列表
//================订单管理end==================



//===============权限管理start======================
$modules['10_priv_admin']['admin_logs']             = 'admin_logs.php?act=list';
$modules['10_priv_admin']['admin_list']             = 'privilege.php?act=list';
$modules['10_priv_admin']['admin_role']             = 'role.php?act=list';
//$modules['10_priv_admin']['agency_list']            = 'agency.php?act=list';
//$modules['10_priv_admin']['suppliers_list']         = 'suppliers.php?act=list'; // 供货�?


//===============权限管理end==================


//===============系统设置start======================
$modules['05_system']['01_shop_config']             = 'shop_config.php?act=list_edit';//系统设置
$modules['05_system']['02_admin_logs']             = 'admin_logs.php?act=list'; //系统日志
$modules['05_system']['03_admin_list']             = 'privilege.php?act=list';//操作员列表
$modules['05_system']['04_admin_role']             = 'role.php?act=list';//角色管理
$modules['05_system']['05_sms_send_history']        = 'sms.php?act=send_history_list';
$modules['05_system']['weixin'] 					= 'shop_config.php?act=weixin';//微信开发者 cuikai14-9-26 上午10:41
$modules['05_system']['05_captcha_manage']             = 'captcha_manage.php?act=main';//验证码管理
//$modules['05_system']['06_db_optimize']             = 'database.php?act=optimize';
$modules['05_system']['04_mail_settings']           = 'shop_config.php?act=mail_settings';
$modules['05_system']['navigator']                  = 'navigator.php?act=list&position=shop';
$modules['05_system']['flashplay']                  = 'flashplay.php?act=list';
$modules['05_system']['08_friendlink_list']         = 'friend_link.php?act=list';
$modules['05_system']['08_partner_list']         = 'partner_link.php?act=list';
$modules['05_system']['09_user_bg_img']         = 'userbg_img.php?act=list';
$modules['05_system']['10_payment_list']            = 'payment.php?act=list';
$modules['05_system']['mail_template_manage']     = 'mail_template.php?act=list';

//===============系统设置end======================











//================财务管理start==================
$modules['06_bank_account']['account_list'] = "bank_account.php?act=account_list";
//$modules['06_bank_account']['account_add'] = "bank_account.php?act=account_add";
//$modules['19_bank_account']['admin_web_account']        = 'account_log.php?act=web_account_list&user_id=web_account';
$modules['06_bank_account']['revenue_ranking']        = 'account_log.php?act=revenue_ranking'; //modify yes123 2014-12-12
    //modify yes123 2014-12-19 充值和提现分开
$modules['06_bank_account']['09_user_account']           = 'user_account.php?act=list&process_type=0&is_paid=0';//充值申请
$modules['06_bank_account']['09_user_account2']           = 'user_account.php?act=list&process_type=1&is_paid=0';//提现申请


//$modules['06_bank_account']['10_user_account_manage']    = 'user_account_manage.php?act=list'; //资金管理
$modules['06_bank_account']['02_payment_list']            = 'payment.php?act=list';

//================财务管理end==================


//================推荐管理start==================
//$modules['07_rec']['affiliate']                     = 'affiliate.php?act=list';
//$modules['07_rec']['affiliate_ck']                  = 'affiliate_ck.php?act=list';
//$modules['07_rec']['affiliate_search']                  = 'users.php?act=list&check_status='.CHECKED_CHECK_STATUS.'&function_name='.$_LANG['affiliate_search'] ;

//================推荐管理end==================

//================内容管理start==================
$modules['08_content']['03_article_list']           = 'article.php?act=list';
$modules['08_content']['02_articlecat_list']        = 'articlecat.php?act=list';
//$modules['07_content']['vote_list']                 = 'vote.php?act=list';
//$modules['07_content']['article_auto']              = 'article_auto.php?act=list';
//$modules['07_content']['shop_help']                 = 'shophelp.php?act=list_cat';
//$modules['07_content']['shop_info']                 = 'shopinfo.php?act=list';
//================内容管理end==================



//================报表管理start==================
//$modules['06_stats']['flow_stats']                  = 'flow_stats.php?act=view';
//$modules['09_stats']['report_guest']                = 'guest_stats.php?act=list'; //客户统计
//$modules['09_stats']['report_order']                = 'order_stats.php?act=list'; //订单统计
//$modules['09_stats']['sale_list']                   = 'sale_list.php?act=list';
/*$modules['06_stats']['searchengine_stats']          = 'searchengine_stats.php?act=view';
$modules['06_stats']['z_clicks_stats']              = 'adsense.php?act=list';
$modules['06_stats']['report_sell']                 = 'sale_general.php?act=list';
$modules['06_stats']['sell_stats']                  = 'sale_order.php?act=goods_num';
$modules['06_stats']['report_users']                = 'users_order.php?act=order_num';
$modules['06_stats']['visit_buy_per']               = 'visit_sold.php?act=list';*/
//================报表管理end==================



//================渠道管理start==================
$modules['18_organization_manage']['organization_list']        = 'distributor.php?act=organization_list';
//================渠道管理end==================


/*$modules['02_cat_and_goods']['08_goods_type']       = 'goods_type.php?act=manage';
$modules['02_cat_and_goods']['05_comment_manage']   = 'comment_manage.php?act=list';
$modules['02_cat_and_goods']['11_goods_trash']      = 'goods.php?act=trash';        // 商品回收�?
$modules['02_cat_and_goods']['12_batch_pic']        = 'picture_batch.php';
$modules['02_cat_and_goods']['13_batch_add']        = 'goods_batch.php?act=add';    // 商品批量上传
$modules['02_cat_and_goods']['14_goods_export']     = 'goods_export.php?act=goods_export';
$modules['02_cat_and_goods']['15_batch_edit']       = 'goods_batch.php?act=select'; // 商品批量修改
$modules['02_cat_and_goods']['16_goods_script']     = 'gen_goods_script.php?act=setup';
$modules['02_cat_and_goods']['17_tag_manage']       = 'tag_manage.php?act=list';
$modules['02_cat_and_goods']['50_virtual_card_list']   = 'goods.php?act=list&extension_code=virtual_card';
$modules['02_cat_and_goods']['51_virtual_card_add']    = 'goods.php?act=add&extension_code=virtual_card';
$modules['02_cat_and_goods']['52_virtual_card_change'] = 'virtual_card.php?act=change';
$modules['02_cat_and_goods']['goods_auto']             = 'goods_auto.php?act=list';
*/


//==========================企业站管理=========================//
$modules['21_en_site_manage']['01_index_menu']        = 'navigator.php?act=list&position=en'; //主页菜单
$modules['21_en_site_manage']['02_flashplay']              = 'flashplay.php?act=list&position=en_index'; //首页轮播图
$modules['21_en_site_manage']['02_ins_services']           = 'ins_service.php?act=list&position=en'; //文章列表
$modules['21_en_site_manage']['03_articlecat_list']        = 'articlecat.php?act=list&position=en';//文章分类
$modules['21_en_site_manage']['04_article_list']           = 'article.php?act=list&position=en'; //文章列表


//==========================微信站管理=========================//
$modules['22_weixin_manage']['01_index_menu']        = 'navigator.php?act=list&position=weixin'; //主页菜单
$modules['22_weixin_manage']['02_flashplay']              = 'flashplay.php?act=list&position=weixin'; //首页轮播图
$modules['22_weixin_manage']['03_articlecat_list']        = 'articlecat.php?act=list&position=weixin';
$modules['22_weixin_manage']['04_article_list']           = 'article.php?act=list&position=weixin'; 








?>
