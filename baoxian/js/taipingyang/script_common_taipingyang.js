function change_applicant_province (){
 
    $("#applicant_city_code").show();
    $("#applicant_city_code").empty(); 

    jQuery.ajax({
        type : "post",
        url : "../oop/business/queryAreaList.php",
        data : "region_code="+$("#applicant_province_code").val(),
        success : function(html){
            $("#applicant_city_code").empty(); //清空市区选项
            var areaInfos=html.split(";");
            for(var i=0;i<areaInfos.length-1;i++){
                var info=areaInfos[i].split(":");
                $("<option value='"+info[0]+"'>"+info[1]+"</option>").appendTo("#applicant_city_code");
            }
        },
        error : function(data){
        // alert("网络传输异常，无法获取地区信息");
        }
    });
}

function change_insured_province (){
 
    $("#insured_city_code").show();
    $("#insured_city_code").empty(); 

    jQuery.ajax({
        type : "post",
        url : "../oop/business/queryAreaList.php",
        data : "region_code="+$("#insured_province_code").val(),
        success : function(html){
            $("#insured_city_code").empty(); //清空市区选项
            var areaInfos=html.split(";");
            for(var i=0;i<areaInfos.length-1;i++){
                var info=areaInfos[i].split(":");
                $("<option value='"+info[0]+"'>"+info[1]+"</option>").appendTo("#insured_city_code");
            }
        },
        error : function(data){
        // alert("网络传输异常，无法获取地区信息");
        }
    });
}

