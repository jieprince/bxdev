// JavaScript Document

$(document).ready(function(){
	
     $('#tab_design .tab_menu ul li a').off("click").on("click",function(){
		var this_index = $(this).parent().index();	
		$('#tab_design .tab_menu ul li.active').removeClass("active");
		$(this).parent().addClass("active");
		$('#tab_design .tab_contents ul li').hide();
		$('#tab_design .tab_contents ul li').eq(this_index).show(); 
	 });

});