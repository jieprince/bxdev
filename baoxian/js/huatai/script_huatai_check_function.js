/**
 * Created by liuhui on 2015/1/20.
 */
function clearinsBirthday(op){

    if(op.value!=""){
        $("#insBirthday_warn").html("");
    }
}
function clearinsName(op){
    if($.trim(op.value)==""){
        $("#insName_warn").html("请填写被保人姓名");
    }else{
        $("#insName_warn").html("");
    }
}
function clearEnglishName(op){
    if($.trim(op.value)==""){
        $("#appEnglishName_warn").html("如：张三，填写拼音ZHANG SAN");
    }else{
        $("#appEnglishName_warn").html(".");
    }

}
function clearappSex(){
    $("#appSex_warn").html("");
}
function showins2(){
    $("#fists").html("<b>第1个</b>");
    var sUrl = "/fg/order/insuredTemplate2.jsp?productId=efyi21&insCount=2&date="+new Date();
    $("[name=insCount]").val("2");
    sprict();
    show(sUrl,"insShow2",false);
}
var insCount = 2;
function addIns(){
    insCount = insCount + 1;
    var sUrl = "/fg/order/insuredTemplate2.jsp?productId=efyi21&insCount="+insCount;
    $("[name=insCount]").val(insCount);
    sprict();
    show(sUrl,"insShow2",true);
}
function deleteins(){
    var insdiv = $("#ins"+insCount);
    insdiv.html("");
    insdiv.attr("id","");
    insCount = insCount - 1;
    $("[name=insCount]").val(insCount);
    sprict();
}
function clearIns(){
    $("#insShow2").html("");
    $("#insShow3").html("");
    $("fists").html("");
    insCount = 2;
    $("[name=insCount]").val(insCount);
    clearSprict();
}
function sprict(){
    var yp = 200.00;
    var cp = yp * insCount;
    $("#showprice").html(cp);
}
function clearSprict(){
    $("#showprice").html("200.00");
}
function clearinsEnglishName(op){
    if($.trim(op.value)==""){
        $("#insEnglishName_warn").html("请填写英文名");
    }else{
        $("#insEnglishName_warn").html("");
    }

}