$(document).ready(function() {
});
if($('#kvswipe').length > 0){
	var mainswipe = new Swiper('#kvswipe', {
		loop: true,
		parallax: true,
		observer: true,
		observeParents: true,
		pagination: {
			el: '.kvpage .swiper-pagination',
			type: 'progressbar',
		},
		observer: true,
		observeParents: true,
    speed: 800,
		autoplay: {
			delay:3100,
			disableOnInteraction: false,
		},
	});
	$('#kvswipe').on('mouseenter', function(e){
		mainswipe.autoplay.stop();
  })
  $('#kvswipe').on('mouseleave', function(e){
		mainswipe.autoplay.start();
  })

	var cur = mainswipe.realIndex + 1,
			total = mainswipe.slides.length - 2;
	var cur = (cur < 10) ? '' + cur : cur;
	var total = (total < 10) ? '' + total : total;
	$('#kvswipe').find('.swiper-counter').append('<span class=cur>' + cur + '</span><span class=total> / ' + total + '</span>')
	mainswipe.on('slideChange', function () {
		var cur = mainswipe.realIndex + 1,
				cur = (cur < 10) ? '' + cur : cur;
		$('#kvswipe').find('.swiper-counter .cur').html(cur)
	});
	$(window).resize(function (){
		mainswipe.update();
	})
}

if($('#fieldswipe').length > 0){ 
	var fieldswipe = new Swiper('#fieldswipe', {
		allowTouchMove: true,
		pagination: {
			el: '.field_sec .swiper-pagination',
			type: 'progressbar'
		},
		breakpoints: {
			// when window width is >= 960px
			960: {
				slidesPerView: 3,
				spaceBetween: 20
			},
		},
	});
}

if($('#brandswipe').length > 0){ 
	var brandswipes = new Swiper('#brandswipe', {
		slidesPerView: 1.1,
		spaceBetween: 15,
		allowTouchMove: true,
		pagination: {
			el: '#brandswipe .swiper-pagination',
			type: 'progressbar',
		},
		breakpoints: {
			// when window width is >= 960px
			960: {
				slidesPerView: 3,
				spaceBetween: 20
			},
		},
	});
}
if($('#jangswipe').length > 0){ 
	var brandswipes = new Swiper('#jangswipe', {
		slidesPerView: 1.1,
		spaceBetween: 15,
		allowTouchMove: true,
		pagination: {
			el: '#jangswipe .swiper-pagination',
			type: 'progressbar',
		},
		breakpoints: {
			// when window width is >= 960px
			960: {
				slidesPerView: 3,
				spaceBetween: 20
			},
		},
	});
}

if($('#interestswipe').length > 0){ 
	var interestswipe = new Swiper('#interestswipe', {
		slidesPerView: 1.1,
		spaceBetween: 15,
		allowTouchMove: true,
		pagination: {
			el: '.interest_sec .swiper-pagination',
			type: 'progressbar',
		},
		breakpoints: {
			// when window width is >= 960px
			960: {
				slidesPerView: 3,
				spaceBetween: 20
			},
		},
	});
}

// if($('#recentswipe').length > 0){ 
// 	var interestswipe = new Swiper('#recentswipe', {
// 		allowTouchMove: true,
// 		pagination: {
// 			el: '.recent_sec .swiper-pagination',
// 			type: 'progressbar',
// 		},
// 		breakpoints: {
// 			// when window width is >= 960px
// 			960: {
// 				slidesPerView: 3,
// 				spaceBetween: 20
// 			},
// 		},
// 	});
// }

if($('#recommswipe').length > 0){ 
	var recommswipe = new Swiper('#recommswipe', {
		slidesPerView: 1.1,
		spaceBetween: 15,
		allowTouchMove: true,
		pagination: {
			el: '.recomm_sec .swiper-pagination',
			type: 'progressbar',
		},
		breakpoints: {
			// when window width is >= 960px
			960: {
				slidesPerView: 3,
				spaceBetween: 20
			},
		},
	});
}

if($('#popularswipe').length > 0){ 
	var popularswipe = new Swiper('#popularswipe', {
		slidesPerView: 2.2,
		slidesPerColumn: 2,
		spaceBetween: 15,
		allowTouchMove: true,
		pagination: {
			el: '.popular_sec .swiper-pagination',
			type: 'progressbar',
		},
		breakpoints: {
			// when window width is >= 960px
			960: {
				slidesPerView: 3,
				spaceBetween: 20,
				slidesPerColumn: 1,
			},
		},
	});
}

if($('#recentswipe').length > 0){ 
	$('.recent_sec .tab-pagination').find('p').each(function(index) {
		$(this).on('click', function(){
			var timers = setTimeout(function() {

				if($('#recentswipe').find('.nodata').length > 0) {
					$('#recentswipe').find('.prog_page').css({
						'opacity': 0
					})
					$('#recentswipe').find('.swiper-wrapper').attr('style', '')
				}else{
					$('#recentswipe').find('.prog_page').css({
						'opacity': 1
					})
				}

				clearTimeout(timers);
			}, 200);
		})
	});
}

if($('#brandswipe').length > 0){ 
	$('.brand_sec .tab-pagination').find('p').each(function(index) {
		$(this).on('click', function(){
			var timers = setTimeout(function() {
				if($('#brandswipe').find('.nodata').length > 0) {
					$('#brandswipe').find('.prog_page').css({
						'opacity': 0
					})
					$('#brandswipe').find('.swiper-wrapper').attr('style', '')
				}else{
					$('#brandswipe').find('.prog_page').css({
						'opacity': 1
					})
				}

				clearTimeout(timers);
			}, 200);
		})
	});
}

if($('#jangswipe').length > 0){ 
	$('.brand_sec .tab-pagination').find('p').each(function(index) {
		$(this).on('click', function(){
			var timers = setTimeout(function() {
				if ($('#jangswipe #div_contents_jang').is(':empty')){
					$('#jangswipe').find('.prog_page').css({
						'opacity': 0
					})
					$('#jangswipe').find('.swiper-wrapper').attr('style', '')
				}else{
					$('#jangswipe').find('.prog_page').css({
						'opacity': 1
					})
				}

				if($('#jangswipe').find('.nodata').length > 0) {
					$('#jangswipe').find('.prog_page').css({
						'opacity': 0
					})
					$('#jangswipe').find('.swiper-wrapper').attr('style', '')
				}else{
					$('#jangswipe').find('.prog_page').css({
						'opacity': 1
					})
				}

				clearTimeout(timers);
			}, 200);
		})
	});
}