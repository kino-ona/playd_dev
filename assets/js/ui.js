
if($('.back_head-title').length > 0) {
	var lastSt = 0;
	$(window).scroll(function (e) {
		var st = $(this).scrollTop();
		winW = $(window).width();
		
		if (st > 1) {
			$('.header.back_head-title').addClass('fixed');
			$('.container').css({
				'margin-top': '70px',
			})
		} 
		if (st > winW) {
			$('.header.back_head-title').addClass('show');
		}
		if (st < (winW * 1.5)) {
			$('.header.back_head-title').stop().removeClass('show')
		}
		if (st < 200) {
			$('.header.back_head-title').removeClass('fixed')
			$('.container').css({
				'margin-top': '0',
			})
		}
		lastSt = st;
	});
	if($('.brand_wrap, .detail_view, .comparison_wrap, .myfavorite_modi, .login_wrap').length > 0) {
		$('.header.back_head-title').find('.btn_pback').addClass('bk');
	}
}

if($('.banner_sec').length > 0){
	$('.banner_sec').each(function(i) {
		var num = i + 1;
		var bnrId = $(this).find('.swiper-container').attr('id', 'bannerswipe' + (i + 1));
		
		var bannerswipe = new Swiper(bnrId, {
			loop: true,
			parallax: true,
			observer: true,
			observeParents: true,
			observer: true,
			observeParents: true,
			speed: 800,
			autoplay: {
				delay:3100,
				disableOnInteraction: false,
			},
		});
		$('.banner_sec .swiper-slide').on('mouseover', function(){
			bannerswipe.autoplay.stop();
		});
		$('.banner_sec .swiper-slide').on('mouseout', function(){
			bannerswipe.autoplay.start();
		});
	
		var cur = bannerswipe.realIndex + 1,
				total = bannerswipe.slides.length - 2;
		var cur = (cur < 10) ? '' + cur : cur;
		var total = (total < 10) ? '' + total : total;
		bnrId.find('.swiper-counter').append('<span class=cur>' + cur + '</span><span class=total> / ' + total + '</span>')
		bannerswipe.on('slideChange', function () {
			var cur = bannerswipe.realIndex + 1,
					cur = (cur < 10) ? '' + cur : cur;
					bnrId.find('.swiper-counter .cur').html(cur)
		});
		$(window).resize(function (){
			bannerswipe.update();
		})
	});
}
if($('#recentnewsswipe').length > 0){ 
	var brandswipe = new Swiper('#recentnewsswipe', {
		slidesPerView: 2.2,
		spaceBetween: 15,
		allowTouchMove: true,
		breakpoints: {
			// when window width is >= 960px
			960: {
				slidesPerView: 5,
				spaceBetween: 20,
			},
		},
	});
}
if($('#detailswipe').length > 0){ 
	var brandswipe = new Swiper('#detailswipe', {
		slidesPerView: 1,
		spaceBetween: 15,
		allowTouchMove: true,
		pagination: {
			el: '.detail_swipe .swiper-pagination',
			type: 'progressbar',
		},
	});
}

var fixedMenuon = function(nm){
	if($('.fixed_bar').length > 0){
		$('.fixed_bar').find('.menu').removeClass('on');
		$('.fixed_bar').find('.menu').eq(nm).addClass('on');
	} 
}

/*** accordion fn  ***/
$('.accord_wrap').each(function () { // default
	if (!$(this).hasClass('manualfn')) {
		if ($(this).hasClass('multiple')) {
			defaultAcc = new Accordion($(this), {
				allowMultiple: true,
				transition: 'height .0s',
				transitionSupport: true,
				setFocus: 'first'
			});
		} else {
			defaultAcc = new Accordion($(this), {
				allowMultiple: false,
				transition: 'height .0s',
				transitionSupport: true,
				setFocus: 'first'
			});
		}
	}
});
function accoSet(setId, multiTF, setFocus) {
	if (!setId) {
		setId = '.accord_wrap';
	} else {
		var setId = $('#' + setId);
	}
	if (!multiTF) multiTF = false;
	if (!setFocus) setFocus = 'acco_panel';

	defaultAcc = new Accordion(setId, {
		allowMultiple: multiTF,
		setFocus: setFocus
	});
}
// tab control
function actvTabList(tabid, actNum){
	var basicTabs = new Tabs('#' + tabid);
	if(!actNum) actNum = 0;

	basicTabs.activate(actNum);
}
$('.tab_wrap').each(function(){  // default
	if(!$(this).hasClass('manualfn')){
		var basicTabs = new Tabs($(this));
	}
});

// fixed pop open/close
var scrollHeight = 0;
var fixpOpen = function(set) {
	var $this = $('.' + set);

	scrollHeight = $(window).scrollTop();
	$('html, body').addClass('noscroll');
	$('.wrap').css({
		'width': '100%',
	});
	$('.wrap').css('top', - scrollHeight);
	if(!$this.hasClass('open')){
		$this.addClass('open');

		// if($('.fixed_bar').length > 0){
		// 	$this.css({
		// 		'margin-bottom': '30px'
		// 	})
		// }
		$('.dim').addClass('show')
	}
}

if($(window).width() > 960) {
	$('.btn_filter').click(function () {
		var posTop = $(this).offset().top; 
		var posLeft = $(this).offset().left; 
	
		$('.fixed_pop-filter').css({
			'padding-top': posTop + 50,
			'padding-left': posLeft - 375,
		})
	});
}
var fixpClose = function(set) {
	var $this = $('.' + set);

	$this.removeClass('open');
	$('html, body').removeClass('noscroll');
	$('.wrap').css('top', 0);
	$('.wrap').css('position', 'relative');

	$('html, body').scrollTop(scrollHeight);

	$('.dim').removeClass('show');
	$this.css({
		'margin-bottom': '0'
	})
}

$(document).ready(function() {
	if($('.tab-pagination .row p').length > 0) tabposSet();
});
var tabposSet = function(){
	$('.tab-pagination').each(function() {
		var $tablist = $(this).find('.row');
		
		$(this).find('.row p').each(function() {
			$(this).on('click', function(e){
				if(!$(this).hasClass('active')){
					var $element = $(this);
					$tablist.find('p').removeClass('active');
					$element.addClass('active');

					var hashOffset = $element.offset().left;
					var hashWidth = $element.outerWidth(true);
					var menuScrollLeft = $tablist.scrollLeft();
					var menuWidth = $tablist.width();

					var myScrollPos = hashOffset + (hashWidth / 2) + menuScrollLeft - (menuWidth / 2);
					$tablist.stop().animate({
						scrollLeft: myScrollPos - (menuWidth / 9)
					}, 300);
				}
		
			});
		});
	});
}

if($('.reply_area').length > 0) {
	var lastSt = 0;
	$(window).scroll(function (e) {
		var st = $(this).scrollTop();
		winW = $(window).width();
		winH = $(window).height();
		docH = $(document).height();
		if(winW < 1024) {
			if (st > 100) {
				$('.reply_area').addClass('fixed')
			} else {
				$('.reply_area').removeClass('fixed')
			}
		}

		lastSt = st;
	});
}

if($('.brnd_detail-head .btn_morevw').length > 0){ 
	$('.brnd_detail-head .btn_morevw').on('click', function(e){
		if($(this).hasClass('more')) {
			$(this).removeClass('more');
			$(this).parents('.desc').removeClass('more');
		}else{
			$(this).addClass('more');
			$(this).parents('.desc').addClass('more');
		}
	});
}

var isVisible = false;
$(window).on('scroll',function() {
	$('.view_conts p').each(function(t){
		var pconts = $(this);
		if (checkVisible(pconts)&&!isVisible) {
			pconts.addClass('animation');
			// isVisible=true;
			return;
		} else {
			pconts.removeClass('animation');
		}
		if(document.documentElement.scrollTop == 0){
			pconts.removeClass('animation');
		}
	});
});
var checkVisible = function(elm, eval){
	eval = eval || "object visible";
	var viewportHeight = $(window).height(), // Viewport Height
			scrolltop = $(window).scrollTop(), // Scroll Top
			y = $(elm).offset().top + 160,
			elementHeight = $(elm).height();

	if (eval == "object visible") return ((y < (viewportHeight + scrolltop)) && (y > (scrolltop - elementHeight)));
	if (eval == "above") return ((y < (viewportHeight + scrolltop)));
}
$('.view_conts p').each(function(t){
	var pconts = $(this);
	if (checkVisible(pconts)&&!isVisible) {
		pconts.addClass('animation');
		return;
	}else {
		pconts.removeClass('animation');
	}
});

if($('.view_conts .editors').length > 0){ 
	$('.view_conts .editors').find('.__se_link').each(function(){
		$(this).attr('href', '#');
	});
}

if($('.comparison_detail').length > 0){ 
	var timers = setTimeout(function() {
		if($('.comparison_detail').find('#left_price').length > 0){ 
			if($(".comparison_detail #left_price p:contains('만원만원')")) {
				$(".comparison_detail #left_price p:contains('만원만원')").text($(".comparison_detail #left_price p:contains('만원만원')").text().replace('만원만원', '만원'));
			}
		}
		if($('.comparison_detail').find('#right_price').length > 0){ 
			if($(".comparison_detail #right_price p:contains('만원만원')")) {
				$(".comparison_detail #right_price p:contains('만원만원')").text($(".comparison_detail #right_price p:contains('만원만원')").text().replace('만원만원', '만원'));
			}
		}

		clearTimeout(timers);
	}, 150);

	$('.fixed_pop-comparison').find('.btn').on('click', function(e){
		var timers = setTimeout(function() {
			if($('.comparison_detail').find('#right_price').length > 0){ 
				if($(".comparison_detail #right_price p:contains('만원만원')")) {
					$(".comparison_detail #right_price p:contains('만원만원')").text($(".comparison_detail #right_price p:contains('만원만원')").text().replace('만원만원', '만원'));
				}
			}
	
			clearTimeout(timers);
		}, 150);
	});
}
