//url中数据的提取
$().ready(function(){
    $('a').focus(function(){
        this.blur();
    });
});
function getUrlParam(name){
		var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if (r!=null) return unescape(r[2]); return null;	
}