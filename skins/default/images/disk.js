var topParent = 0;
$(document).ready(function() {
	// --- 显示 TIPS ---
	$('#topRightNavSpaceBoxProgressBar').on('mouseenter', function(e) {
		showTips(e, this, '已用'+$(this).attr('tip')+'('+$(this).attr('per')+'%)');
		var per = $(this).attr('per') < 6 ? 5 : $(this).attr('per');
		var maxPer = per + 10;
		$('#topRightNavSpaceBoxProgressBar').stop().animate({width:maxPer+'%'},{step:function(){$(this).css('overflow','visible');},complete:function(){
			$('#topRightNavSpaceBoxProgressBar').stop().animate({width:per+'%'},{step:function(){$(this).css('overflow','visible');},duration:200});
		},duration:200});
	});
	// --- TOP UI ---
	$('.topNavItem').on('mouseenter', function() {
		$(this).addClass('topNavItemHover');
	}).on('mouseleave', function() {
		$(this).removeClass('topNavItemHover');
	});
	// --- 上面的NAV ---
	$('.topNavItem').on('click', function() {
		if($(this).index()!=4) {
			$('.topNavItemSelected').removeClass('topNavItemSelected');
			$(this).addClass('topNavItemSelected');
			$('#contentLeft').children('div:visible').hide();
			$('#contentLeft').children('div').eq($(this).index()-1).show();
			document.title = $(this).text() + ' - ' + title + ' - Powered by Filiant';
			$('.page').hide();
			$('.page').eq($(this).index()-1).show();
			resizeUI();
		} else {
			alertX('您确定要退出登录么', '确定', function(w, m) {
				windowMaskShow(w[0]);
				// --- 删除所选 ---
				$.ajax({
					type:'GET',
					cache:false,
					url:'ajax.php?ac=logout',
					success: function() {
						window.location.href = './';
					}
				});
			}, '取消');
		}
	});
	// --- 左边的 Nav ---
	$('#contentLeft').find('a').on('click', function() {
		if($(this).siblings('.selected').size() > 0) {
			$(this).siblings('.selected').removeClass('selected');
			$(this).addClass('selected');
		}
	});
	// --- 文件列表 ---
	$('.filesTable').on('mouseenter', 'tr', function() {
		$(this).find('td').stop().animate({'background-color':'#F0F1F2'}, 200);
	}).on('mouseleave', 'tr', function() {
		$(this).find('td').stop().animate({'background-color':'#FFF'}, 200);
	});
	$('.filesTable').on('click', 'tr[type=folder]', function(e) {
		if(!$(e.target).is(':checkbox'))
			listGet($(this).attr('fid'));
	});
	// --- 全选 ---
	$('#checkbox_f1').on('click', function() {
		$('[name^=checkbox]:not(:disabled)').prop('checked', $(this).prop('checked'));
	});
	// --- 绑定 ---
	// --- 新建文件夹 ---
	$('#addFolderBtn').on('click', function() {
		$('#addFolderTitleTxt').val('');
		windowShow('#addFolderWin');
	});
	$('#addFolderOkBtn').on('click', function() {
		windowMaskShow('#addFolderWin');
		$.ajax({
			type:'POST',
			cache:false,
			data:{title:$('#addFolderTitleTxt').val(),parent:topParent},
			url:'ajax.php?ac=addFolder',
			success: function(j) {
				j = $.parseJSON(j);
				if(j.result != '0') {
					windowHide('#addFolderWin');
					windowMaskHide('#addFolderWin');
					listGet(topParent);
				} else {
					alertX(j.msg, '确定', function(w, m) {
						windowMaskHide('#addFolderWin');
						alertXHide(w, m);
					});
				}
			}
		});
	});
	// --- 删除 ---
	$('#removeBtn').on('click', function() {
		//windowShow('#removeWin');
		if($('[name^="checkbox[f"]:checked').size() > 0) {
			alertX('您确定要删除勾选的文件夹以及文件么？<br />文件夹的所有子文件和文件夹都会被删除，这些是不可恢复的！', '确定', function(w, m) {
				windowMaskShow(w[0]);
				// --- 删除所选 ---
				$.ajax({
					type:'POST',
					cache:false,
					data:$('[name^="checkbox[f"]').serialize(),
					url:'ajax.php?ac=remove',
					success: function(j) {
						windowMaskHide(w[0]);
						alertXHide(w, m);
						j = $.parseJSON(j);
						alertX('成功删除 '+j.folders+' 个文件夹，'+j.files+' 个文件。', '确定');
						listGet(topParent);
						//*/
					}
				});
			}, '取消');
		} else {
			alertX('您未选择任何文件夹或文件', '确定');
		}
	});
	// --- 重命名 ---
	$('#renameBtn').on('click', function() {
		if($('[name^="checkbox[f"]:checked').size() == 1) {
			var title = $('[name^="checkbox[f"]:checked').parents('tr:eq(0)').children('td:eq(1)').find('span').text();
			$('#renameTitleTxt').val(title);
			windowShow('#renameWin');
		} else {
			alertX('只有您选择一个项目的时候才可以重命名', '确定');
		}
	});
	$('#renameOkBtn').on('click', function() {
		windowMaskShow('#renameWin');
		var type = ($('[name^="checkbox[f"]:checked').attr('name').indexOf('file') == -1) ? 'folder' : 'file';
		var id = $('[name^="checkbox[f"]:checked').val();
		$.ajax({
			type:'POST',
			cache:false,
			data:{name:$('#renameTitleTxt').val(),type:type,id:id},
			url:'ajax.php?ac=rename',
			success: function(j) {
				j = $.parseJSON(j);
				if(j.result == '1') {
					windowMaskHide('#renameWin');
					windowHide('#renameWin');
					listGet(topParent);
				} else {
					alertX(j.msg, '确定', function(w, m) {
						windowMaskHide('#renameWin');
						alertXHide(w, m);
					});
				}
				//*/
			}
		});
	});
	// --- 上传 ---
	$('#uploadBtn').uploadify({
		height: 41,
		swf: 'exts/uploadify/uploadify.swf',
		buttonImage: 'skins/default/images/button-bg-gray.png',
		uploader: 'ajax.php?ac=upload',
		onSelect: function(file) {
			var j=Math.round(file.size/1024);var o="KB";if(j>1000){j=Math.round(j/1000);o="MB";}var l=j.toString().split(".");j=l[0];if(l.length>1){j+="."+l[1].substr(0,2);}j+=o;
			$('#uploadWinTBody').append('<tr id="'+file.id+'C"><td>'+file.name+'</td><td width="80" align="center">'+j+'</td><td width="30" align="center">0%</td></tr>');
		},
		onUploadStart: function(file) {
			uploadOverTime = Math.round(new Date().getTime()/1000);
			uploadOverData = 0;
			$('#uploadBtn').uploadify('settings', 'formData', {
				'id': topParent,
				'sessionId': session_id
			});
		},
		onDialogClose: function(queueData) {
			if(queueData.filesQueued > 0) {
				windowShow('#uploadWin');
				$('#topRightNavUploading').fadeIn(200);
			}
		},
		onUploadSuccess: function(file, data) {
			var j = $.parseJSON(data);
			if(j.result == '0') {
				alertX(j.msg, '确定');
			} else if(j.result == '-1') {
				alertX('文件大小超过服务器限制，请检查 PHP.INI 的设置', '确定');
			}
			$('#'+file.id+'C').fadeOut(200, function() {
				$(this).remove();
			});
		},
		onQueueComplete: function() {
			windowHide('#uploadWin');
			listGet(topParent);
			$('#topRightNavUploading').fadeOut(200);
		},
		onUploadProgress: function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
			$('#'+file.id+'C').children('td:eq(2)').text(Math.round(bytesUploaded/bytesTotal*100)+'%');
			// --- 速度 ---
			uploadNowTime = Math.round(new Date().getTime()/1000);
			uploadNowData = bytesUploaded;
			var timeScale = uploadNowTime - uploadOverTime;
			if(timeScale >= 1) {
				var dataScale = uploadNowData - uploadOverData;
				uSpeed = dataScale/timeScale;
				var j=Math.round(uSpeed/1024);var o='KB';if(j>1000){j=Math.round(j/1000);o='MB';}var l=j.toString().split('.');j=l[0];if(l.length>1){j+='.'+l[1].substr(0,2);}j+=' '+o;
				$('#uploadWinSpeedBox').html(j+'/s');
				uploadOverTime = uploadNowTime;
				uploadOverData = uploadNowData;
			}
		},
		width: 97
    });
	$('#uploadBtn').addClass('button').find('.uploadify-button').before('<a id="uploadBtn" href="javascript:void(0);" onclick="return false;">上传文件<i></i></a>').remove();
	$('#uploadBtn').on('mouseenter', function() {
		$(this).addClass('buttonHover');
	}).on('mouseleave', function() {
		$(this).removeClass('buttonHover buttonActive');
	}).on('mousedown', function() {
		$(this).addClass('buttonActive');
	}).on('mouseup', function() {
		$(this).removeClass('buttonActive');
	})
	$('#topRightNavUploading').on('click', function() {
		windowShow('#uploadWin');
	});
	// --- 分享 ---
	$('#shareBtn').on('click', function() {
		if($('[name^="checkbox[f"]:checked').size() >= 1) {
			alertX('您确定要将勾选的文件和文件夹全部共享么？', '确定', function(w, m) {
				windowMaskShow(w[0]);
				// --- 分享所选 ---
				$.ajax({
					type:'POST',
					cache:false,
					data:$('[name^="checkbox[f"]').serialize(),
					url:'ajax.php?ac=share',
					success: function(j) {
						windowMaskHide(w[0]);
						alertXHide(w, m);
						j = $.parseJSON(j);
						alertX(j.msg, '确定');
						listGet(topParent);
						//*/
					}
				});
				//*/
			}, '取消');
		} else {
			alertX('只有您选择至少一个项目的时候才能分享', '确定');
		}
	});
	// --- 取消分享 ---
	$('#cancelShareBtn').on('click', function() {
		if($('[name^="checkbox[f"]:checked').size() >= 1) {
			alertX('确定全部取消共享？', '确定', function(w, m) {
				windowMaskShow(w[0]);
				$.ajax({
					type:'POST',
					cache:false,
					data:$('[name^="checkbox[f"]').serialize(),
					url:'ajax.php?ac=cancelshare',
					success: function(j) {
						windowMaskHide(w[0]);
						alertXHide(w, m);
						listGet(topParent);
					}
				});
			}, '取消');
		} else {
			alertX('取消共享需要您至少选择一个项目', '确定');
		}
	});
	// --- 移动 ---
	$('#moveWin>.windowMask').css({'opacity':'0'});
	$('#moveBtn').on('click', function() {
		if($('[name^="checkbox[f"]:checked').size() >= 1) {
			windowShow('#moveWin');
			$('.moveWinItem[fid="0"]').trigger('click');
		} else {
			alertX('只有您选择至少一个项目的时候才可以移动', '确定');
		}
	});
	$('#moveOkBtn').on('click', function() {
		// --- 移动 ---
		$('#moveWin>.windowMask').show();
		$.ajax({
			type:'POST',
			cache:false,
			data:$('[name^="checkbox[f"]').serialize()+'&toFid='+$('#moveWinSelected').attr('fid'),
			url:'ajax.php?ac=move',
			success: function(j) {
				$('#moveWin>.windowMask').hide();
				windowHide('#moveWin');
				j = $.parseJSON(j);
				alertX(j.msg, '确定');
				if(j.result=='1') {
					listGet(topParent);
				}
				//*/
			}
		});
	});
	$('#moveWin').on('mouseenter', '.moveWinItem', function() {
		$(this).not('.moveWinItemSelected').stop().animate({'background-color':'#F0F1F2'}, 200);
	}).on('mouseleave', '.moveWinItem', function() {
		$(this).not('.moveWinItemSelected').stop().animate({'background-color':'#FFF'}, 200);
	}).on('click', '.moveWinItem', function() {
		if($(this).attr('fid')=='0') {
			$('.moveWinItem[fid!=0]').remove();
			$(this).attr('opend', '0');
		}
		if($(this).attr('opend')=='0') {
			$('#moveWin>.windowMask').show();
			$.ajax({
				type:'POST',
				cache:false,
				data: {parent:$(this).attr('fid'),onlyShowFolders:'1'},
				url:'ajax.php?ac=list',
				success: function(j) {
					j = $.parseJSON(j);
					$('.moveWinItem[fid="'+j.parent+'"]').attr('opend', '1');
					for(var k in j.folders) {
						$afterdom = ($('.moveWinItem[parentid="'+j.parent+'"]').size()>0) ? $('.moveWinItem[parentid="'+j.parent+'"]').last() : $('.moveWinItem[fid="'+j.parent+'"]');
						$afterdom.after('<div class="moveWinItem" fid="'+j.folders[k].id+'" paths="'+j.folders[k].paths+'" parentid="'+j.parent+'" opend="0" style="padding-left:'+(j.folders[k].depth*21+26)+'px;background-position:'+(5+j.folders[k].depth*21)+'px center;">'+j.folders[k].name+'</div>');
					}
					$('#moveWin>.windowMask').hide();
				}
			});
		}
		$('.moveWinItemSelected').removeClass('moveWinItemSelected').stop().animate({'background-color':'#FFF'},200);
		$(this).addClass('moveWinItemSelected').stop().animate({'background-color':'#CFD1D8'},200);
		$('#moveWinSelected').text($(this).attr('paths')).attr('fid', $(this).attr('fid'));
	});
	// --- AJAX ---
	listGet(0);
	shareListGet('all');
	resizeUI();
});
$(window).resize(function() {
	resizeUI();
});

function resizeUI() {
	$('#contentLeft').height($(window).height()-$('#top').outerHeight());
	$('#page1').height($(window).height()-$('#top').outerHeight()-20);
	$('#page2PathTxt').width($(window).width()-240-62);
	$('.mask').height($(window).height());
	$('#filesBox').height($(window).height() - $('#filesBox').offset().top - 20);
}

// --- TIPS 效果 ---
function showTips(e, d, text) {
	$tip = $('<div class="tips"></div>').appendTo('body').css({'left':e.pageX+13+'px',top:e.pageY+23+'px',opacity:'0'}).text(text).animate({fontSize:'12px',opacity:.75,paddingBottom:'5px',paddingTop:'5px',paddingLeft:'5px',paddingRight:'5px'}, 150);
	$(d).data('tip', $tip);
	$(d).on('mouseleave.tips', function(e) {
		$(this).data('tip').stop().animate({fontSize:'1px',opacity:0,paddingBottom:'0',paddingTop:'0',paddingLeft:'0',paddingRight:'0'}, 150, function() {
			$(this).remove();
		});
		$(this).off('mousemove.tips').off('mouseleave.tips');
	}).on('mousemove.tips', function(e) {
		$(this).data('tip').css({'left':e.pageX+13+'px','top':e.pageY+23+'px'}, 50).text($tip.text());
	});
}

// --- 窗体 ---
function windowShow(sel) {
	$('#mask').show().css({'opacity':'0'}).animate({'opacity':'.2'},300);
	$(sel).show().css({'opacity':'0'});
	var top = $(window).height()/2-$(sel).outerHeight()/2;
	$(sel).css({'left':$(window).width()/2-$(sel).outerWidth()/2+'px','top':top-60+'px'}).animate({'opacity':'1','top':top+'px'},300);
	$('#mask').show().css({'opacity':'0'}).animate({'opacity':'.2'},300);
	$(sel).children('.windowMask').css({'opacity':'0','height':$(sel).height()+'px','width':$(sel).width()+'px'});
}
function windowHide(sel) {
	$('#mask').animate({'opacity':'0'}, 300, function() {
		$(this).hide();
	});
	$(sel).animate({'opacity':'0','top':'-=60px'},300,function() {
		$(this).hide();
	});
}
function windowMaskShow(win) {
	$(win).children('.windowMask').show().animate({opacity:'.2'}, 300);
}
function windowMaskHide(win) {
	$(win).children('.windowMask').animate({opacity:'0'}, 300, function() {
		$(this).hide();
	});
}

// --- 获得list ---
function listGet(parent, filter) {
	filter = filter || '*';
	$('#mask').show().css({'opacity':'0','cursor':'progress'});
	$.ajax({
		type:'POST',
		cache:false,
		data: {parent:parent, filter:filter, onlyShowFiles: (filter=='*')?'0':'1'},
		url:'ajax.php?ac=list',
		success: function(j) {
			$('#listTBody').html('');
			$('#mask').css({'cursor':'default'}).hide();
			j = $.parseJSON(j);
			topParent = j.parent;
			if(j.parent!=0)
				$('#listTBody').append('<tr type="folder" fid="'+j.doubleParent+'"><td align="center" width="20"><input type="checkbox" disabled="disabled" /></td><td><img src="skins/common/images/icon/sys-folder.gif" /> <span>返回上级目录</span></td><td width="200">-</td><td width="200">-</td></tr>');
			for(var k in j.folders) {
				$('#listTBody').append('<tr type="folder" fid="'+j.folders[k].id+'"><td align="center" width="20"><input name="checkbox[folder]['+j.folders[k].id+']" type="checkbox" value="'+j.folders[k].id+'" /></td><td><div style="position:relative;"><img src="skins/common/images/icon/sys-folder.gif" /> <span>'+j.folders[k].name+'</span>'+(j.folders[k].FILEM_SHARED?'<div class="shareIcon'+j.folders[k].FILEM_SHARED_SUB+'"></div>':'')+'</div></td><td width="200">'+j.folders[k].size+'</td><td width="200">'+j.folders[k].added+'</td></tr>');
			}
			for(var k in j.files) {
				$('#listTBody').append('<tr type="file" fid="'+j.files[k].id+'"><td align="center" width="20"><input name="checkbox[file]['+j.files[k].id+']" type="checkbox" value="'+j.files[k].id+'" /></td><td><div style="position:relative;"><img src="skins/common/images/icon/sys-file.gif" /> <a href="view.php?key='+j.files[k].key+'" target="_blank"><span>'+j.files[k].name+'</span></a>'+(j.files[k].FILEM_SHARED?'<div class="shareIcon'+j.files[k].FILEM_SHARED_SUB+'"></div>':'')+'</div></td><td width="200">'+j.files[k].size+'</td><td width="200">'+j.files[k].added+'</td></tr>');
			}
			$('#page2PathTxt').val(j.path).attr('path', j.path);
			if(j.filter=='*') {
				if(!$('#contentLeft>div:eq(1)>a:eq(0)').hasClass('selected')) {
					$('#contentLeft>div:eq(1)>.selected').removeClass('selected');
					$('#contentLeft>div:eq(1)>a:eq(0)').addClass('selected');
				}
			}
		}
	});
}
// --- 获取分享 LIST ---
function shareListGet(type, start) {
	start = start || '0';
	if(start == '0') $('#page1>div').remove();
	$('#page1').append('<div class="page1Loading"></div>');
	$.ajax({
		type:'POST',
		cache:false,
		data:{type:type,start:start},
		url:'ajax.php?ac=shareList',
		success: function(j) {
			j = $.parseJSON(j);
			$('#page1>.page1Loading').remove();
			for(var k in j.list) {
				if(j.list[k].type == 'file') {
					$('#page1').append('<div class="box"><div class="boxBody"><img src="skins/common/images/icon/sys-file.gif" /> <a href="view.php?key='+j.list[k].filekey+'" target="_blank">'+j.list[k].filename+'</a><span> ['+j.list[k].filesize+']</span></div><div class="boxBottom"><img src="skins/common/images/tx-32.jpg" style="border:1px solid #D3D5DD;" />　'+j.list[k].username+'<div class="boxBottomRight">'+j.list[k].date+'</div></div></div>');
				} else {
					$('#page1').append('<div class="box"><div class="boxBody"><img src="skins/common/images/icon/sys-folder.gif" /> <a href="javascript:void(0);">'+j.list[k].foldername+'</a><span> ['+j.list[k].foldersize+']</span></div><div class="boxBottom"><img src="skins/common/images/tx-32.jpg" style="border:1px solid #D3D5DD;" />　'+j.list[k].username+'<div class="boxBottomRight">'+j.list[k].date+'</div></div></div>');
				}
			}
			$('#page1').append('<div class="page1Bottom">无更多动态</div>');
			$('#page1>div:eq(0)').css({'margin-top':'0'});
		}
	});
}

// --- 显示询问框 ---
function alertX(html, btnname, func, btn2name, func2) {
	$winMask = $('<div class="mask"></div>').appendTo('body');
	$win = $('<div class="window"><div class="windowContent">'+html+'</div><div class="windowBottom"><span class="button okBtn"><a href="javascript:void(0);" onclick="return false;">'+btnname+'<i></i></a></span></div><div class="windowMask"></div></div>').appendTo('body');
	// --- 变量 ---
	func = func || function(){alertXHide($win, $winMask);};
	func2 = func2 || function(){alertXHide($win, $winMask);};
	// --- 事件 ---
	$win.find('.okBtn').on('click', function() {
		func($win, $winMask);
	});
	if(btn2name) {
		$win.children('.windowBottom').append('<span>　</span>');
		$('<span class="button buttonBlue"><a href="javascript:void(0);" onclick="return false;">'+btn2name+'<i></i></a></span>').appendTo($win.children('.windowBottom')).on('click', function() {
			func2($win, $winMask);
		});
	}
	// --- 显示 ---
	$win.show().css({'opacity':'0'});
	var top = $(window).height()/2-$win.outerHeight()/2;
	$win.css({'left':$(window).width()/2-$win.outerWidth()/2+'px','top':top-60+'px'}).animate({'opacity':'1','top':top+'px'},300);
	$winMask.show().css({'opacity':'0'}).animate({'opacity':'.2'},300);
	$win.children('.windowMask').css({'opacity':'0','height':$win.height()+'px','width':$win.width()+'px'});
	resizeUI();
}
function alertXHide(w, m) {
	m.animate({'opacity':'0'}, 300, function() {
		$(this).remove();
	});
	w.animate({'opacity':'0','top':'-=60px'},300,function() {
		$(this).remove();
	});
}