

/*===============无缝滚动====================*/
$(document).ready(function(e) {
	var timer;	
	$(".clone-imgs").append($(".cont").clone());
	
	function scrollImg(){
		timer = setInterval(function(){
			var ml = parseInt($(".scroll").css("margin-left"));
			
			$(".scroll").css({"margin-left":--ml});
			
			if(ml<= -980){
				$(".scroll").css({ "margin-left":0})  ;	
			}
			
		},10);
	};
	
	scrollImg();
	
	$(".scroll").mouseover(function(){
		clearInterval( timer );
	});
	$(".scroll").mouseout(function(){
		scrollImg();
	});
});

$(document).ready(function(e) {
	var timer;
	
	$(".clone-pic").append($(".cont2").clone());
	
	function scrollImg(){
		timer = setInterval(function(){
			var ml = parseInt($(".scroll2").css("margin-left"));
			
			$(".scroll2").css({"margin-left":--ml});
			
			if(ml<= -1170){
				$(".scroll2").css({"margin-left":8+"px"})  ;	
			}			
		},20);
	};
	scrollImg();
	
	$(".scroll2").mouseover(function(){
		clearInterval(timer);
	});
	$(".scroll2").mouseout(function(){
		scrollImg();
	});
});












