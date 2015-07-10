$(function(){

var sl_base_height = $('.sl').height()
	$('.sl').bind('mouseenter', function(){
		$(this).height(sl_base_height * $('.sl a').length)
		$('.sl-ar').addClass('a')
	})
	$('.sl').bind('mouseleave', function(){
		$(this).height(sl_base_height)
		$('.sl-ar').removeClass('a')
	})
	$('.sl a').bind('click', function(){
		var a = $(this)
		if(a.index() != 0){
			setTimeout(function(){
				a.insertBefore($('.sl a').eq(0))
			}, 200)
		}
		$('.sl').trigger('mouseleave')
		var name = $('.sl').attr('name')
		var val = a.attr('value')
		$('input[name="' + name + '"]').attr('value', val)
	}).eq(0).trigger('click')

	init()
	$(window).resize(function(){
		init()
	})
	$('.box').each(function(i, e){
		var w = $(window).width()
		var h = $(window).height()
		var boxw = $(e).width()
		var boxh = $(e).height()
		var padding = parseInt($(e).css('padding-left').replace('px', ''))
		$(e).css('left', (w - boxw - padding)/2)
		$(e).css('top', (h - boxh - padding)/2)

	})
	$(".box-background").click(function(){
		$(this).hide();
		$(".box").hide();
		$("video").trigger("pause");
	})
	// 出版物点击事件
	$('.pub-l a').bind('click', function(){
		$('.pub-l a').removeClass('a')
		$(this).addClass('a')
		var index = $(this).index()
		$('.pub-r').height($('.pub-r').height())
		$('.pub-rc').eq(0).stop().animate({marginTop:-index*538}, 500)
	}).eq(0).trigger('click')

	$('.js_login').bind('click', function(){
		box_show(1)
	})

	$('.js_reg').bind('click', function(){
		box_show(2)
	})
	$('.js_cancelbox').bind('click', function(){
		box_show(0)
	})
	$('.fqa-b a').bind('click', function(){
		$('.fqa-b').removeClass('a').find('h2').height(0)
		var h = $(this).parent().find('p').height() + 45
		$(this).parent().addClass('a').find('h2').height(h)
	}).eq(0).trigger('click')
	$(".box-bg").click(function(){
		box_hide();
	})
	
	$('.pub-l a').bind('click', function(){
		$('.pub-l a').removeClass('a')
		$(this).addClass('a')
		var index = $(this).index()
		$('.pub-r').height($('.pub-r').height())
		$('.pub-rc').eq(0).stop().animate({marginTop:-index*538}, 500)
	}).eq(0).trigger('click')

	// 菜单收缩
	$('.h-navm-2').bind('click', function(){
		if($('.h-navmc').css('display') == 'none'){
			$('.h-navmc').show()
		}else{
			$('.h-navmc').hide()
		}
	})

	$('.h-navm-1').bind('click', function(){
		if($('.h-navmcr').css('display') == 'none'){
			$('.h-navmcr').show()
		}else{
			$('.h-navmcr').hide()
		}
	})

	$('.review-mb a').bind('click', function(){
		var index = $(this).index()
		$('.review-mb a').removeClass('a')
		$(this).addClass('a')
		var left = $('.review-m ul li').width() * index
		$('.review-m ul').stop().animate({marginLeft:-left}, 500)
	})
	//智库列表页瀑布流示例
	//方便与ajax结合使用，请将返回的数据打包成datalist参数相同的格式
	//完成后请删除示例相关代码
})
function init() {
	if($('.bn-c').length > 0){
		$('.bn-c').css('left', ($('.bn').width() - $('.bn-c').width())/2 - $('.bn-c').css('padding-left').replace('px', ''))
	}
	if($(window).width() >= 1150){
		$('.z-schk').attr('value', '')
	}else{
		$('.z-schk').attr('value', '关键词')
		$('.z-wt').html('<div class="l z-wtn"></div><div class="l z-wtn"></div><div class="b"></div>')
	}
}
var hasLoad = new Array();
function waterfall_example( datalist) {
	var datalist = datalist;
	waterfall(datalist, '.z-wtn', function(){
		$(".loading").hide();
		stype = 1;
		// alert('callback:一页数据加载完成')
	})
}

//简单的瀑布流 by wanderwind
function waterfall(datalist, target, callback) {
	if($(target).length == 0){
		return
	}
	var tpl = function(data) {
		if (hasLoad[data['courseid']] == 1){
			return
		}
		imgPath = '/statics/css/top100/img/';
		if(data['courseid'].length < 1){
			data['url'] = 'javascript:void(0);'
		} else {
			data['url'] = '/index.php?m=api&c=think&a=show&cs='+data['courseid'];
		}
		hasLoad[data['courseid']] = 1;
		var html = ''
		html += '<a href="'+data['url']+'" target="_blank" class="z-wtc">'
		html += '<h2>'+data['title']+'</h2>'
		html += '<div class="z-wtc-m">'
		if (!data['courseLecturer']) {
			lecturers = data['lecturer'];
		} else {
			lecturers = data['courseLecturer'];
		}
		console.log(data)
		if (lecturers) {
			var lecturerNum = lecturers.length;
			for(var i=0; i < lecturerNum; i++) {
				var lecturer = lecturers[i]['lecturer'];
				if (lecturer['thumbs']) {
					var thumb = JSON.parse(lecturer['thumbs']);
				} else {
					thumb = '';
				}
				if (thumb[0]['thumbnailUrl'] != undefined) 
				{
					var thumb = thumb[0]['thumbnailUrl'];
				} else if (thumb[0]['fileUrl']) {
					var thumb = thumb[0]['fileUrl'];
				}
				html += '<img src="'+thumb+'" class="l" />';

				html += '<div class="r" style="width:120px;"><p>'+lecturer['name']+'</p><p>'+lecturer['company']+'&nbsp;&nbsp;'+lecturer['position']+'</p></div>'
				html += '<div class="b"></div>';
			}
		}

		// html += '<h1>'+data['title']+'</h1>'
		if (!data['desc'] && data['content']) {
			html += '<p class="course_desc">'+data['content']+'</p>'
		} else if (data['desc']) {
			html += '<p class="course_desc">'+data['desc']+'</p>'
		} 
		var comments = Math.floor(Math.random() * 1000)+parseInt(data['comments']);
		var praises = Math.floor(Math.random() * 1000)+parseInt(data['praises']);
		html += '</div>'
		html += '<div class="z-wtc-b">'
		html += '<span><img src="'+imgPath+'z-icon1.png" />'+comments+'</span>'
		html += '<span><img src="'+imgPath+'z-icon2.png" />'+praises+'</span>'
		if (data['auditionvideo'].length > 0) {
			html += '<span><img src="'+imgPath+'z-icon3.png" /></span>'
		}
		html += '</div>'
		html += '</a>'
		return html
	}
	var minh = function(t) {
		var minElem = $(t).eq(0)
		var min = minElem.height()
		$(t).each(function(i, e){
			var h = $(e).height()
			if(h < min) {
				min = h
				minElem = $(e)
			}
		})
		return minElem
	}
	var run = function() {

		if(datalist != undefined && datalist.length == 0){
			if(callback){
				callback()
			}
			return
		}

		var data = datalist.shift()
		minh(target).append(tpl(data)).children().last().css('opacity', 0).stop().animate({opacity:1}, 500)
		setTimeout(function(){
			run()
		}, 100)
	}
	run()
}

// 弹出框显示
var box_show_status = 0;
function box_show(n) {
	if(n == 0 || n == undefined) {
		n = '';
	}
	var bg = $('.box-bg');
	var box = $('.box'+n);
	var speed = 500;
	if(n == ''){
		bg.stop().animate({opacity:0},speed, function(){
			$(this).hide();
		});
		box.stop().animate({opacity:0},speed, function(){
			$(this).hide();
			box_show_status = 0;
		});
	}else{
		var a = function(){
			box.css('opacity', 0).show().stop().animate({opacity:1}, speed, function(){
				box_show_status = n;
			});
		};
		if(box_show_status != 0){
			$('.box' + box_show_status).stop().animate({opacity:0},speed, function(){
				$(this).hide();
				a();
			});
		}else{
			bg.css('opacity', 0).show().stop().animate({opacity:0.6}, speed);
			a();
		}
	}
	
}

function box_hide() {
	box_show(0);
}