$(document).ready(function(e) {
    // --- TABLE UI ---
	$('table tr').on('mouseenter', function() {
		$(this).addClass('hover');
	}).on('mouseleave', function() {
		$(this).removeClass('hover');
	});
	// --- DROPDOWN ---
	$('body').append('<div id="dropdownMenu"></div>');
	$('.dropdown').each(function(index, element) {
		var name = $(this).find('select').attr('name');
		var id = $(this).find('select').attr('id');
		var value = $(this).find('select').val();
        var list = {};
		$(this).find('option').each(function(index, element) {
            list[$(this).attr('value')] = $(this).text();
        });
		$(this).html('<span>'+$(this).find('option[value="'+value+'"]').text()+'</span>').append('<input id="'+id+'" name="'+name+'" type="hidden" value="'+value+'" />');
		$(this).on('click', function() {
			var sm = .9;
			var needWidth = $(this).outerWidth()-1;
			var smWidth = needWidth * sm;
			var smLeft = needWidth/2-smWidth/2;
			var smFontSize = 12*sm;
			$('#dropdownMenu').css({'left':$(this).offset().left-1+smLeft+'px','top':$(this).offset().top-1+10+'px',width:smWidth+'px','font-size':smFontSize+'px','opacity':'0'}).show().animate({'left':$(this).offset().left-1+'px','top':$(this).offset().top-1+'px',width:needWidth+'px','font-size':'12px','opacity':'1'}, 200).html('');
			for(var v in list) {
				$('#dropdownMenu').append('<a value="'+v+'" href="javascript:void(0);">'+list[v]+'</a>');
			}
			$('#dropdownMenu').children('a').each(function(index, element) {
                $(this).css({'padding':10*sm+'px '+15*sm+'px'}).animate({paddingTop:'10px',paddingRight:'15px',paddingBottom:'10px',paddingLeft:'15px'}, 200);
            });
			// --- 事件 ---
			var $dropdown = $(this);
			$('#dropdownMenu a').on('click', function() {
				$dropdown.children('span').text($(this).text());
				$dropdown.find('[type="hidden"]').val($(this).attr('value'));
				$('#dropdownMenu').hide();
			});
		});
    });
	$('#dropdownMenu').on('mouseleave', function() {
		$(this).stop().hide();
	});
});