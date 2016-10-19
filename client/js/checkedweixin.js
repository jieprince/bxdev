/**
 * Created by liuhui on 2015/1/21.
 */
// 对浏览器的UserAgent进行正则匹配，不含有微信独有标识的则为其他浏览器
/*var useragent = navigator.userAgent;
if (useragent.match(/MicroMessenger/i) != 'MicroMessenger') {
    var alert_html = '\n操作方法如下:\n' +
        '\n1.在微信中搜索公众号关注我们，公众号：ZTXHBXZB;' +
        '\n2.在微信中搜索“中天信合保险”关注我们;' +
        '\n3.扫描二维码直接关注 ' +
        '\n\n点击【确认】进入二维码页面';
    // 这里警告框会阻塞当前页面继续加载
    if(confirm('已禁止本次访问：您必须使用微信内置浏览器访问本页面！\n'+alert_html)){

        document.location.href="../mobile_page/images/gongzhonghao.jpg";
    }else{
        // 以下代码是用javascript强行关闭当前页面
        var opened = window.open('about:blank', '_self');

        opened.opener = null;
        //opened.close();
    }
}*/
