(function () {
	function initKeyVisual() {
		var slides = document.querySelectorAll('.visual__image .image');
		if (!slides.length) return;
		var i = 0;
		function show(n) {
			for (var k = 0; k < slides.length; k++) {
				slides[k].classList.toggle('active', k === n);
			}
		}
		var found = -1;
		for (var j = 0; j < slides.length; j++) {
			if (slides[j].classList.contains('active')) {
				found = j;
				break;
			}
		}
		if (found < 0) {
			found = 0;
			show(0);
		}
		i = found;
		setInterval(function () {
			i = (i + 1) % slides.length;
			show(i);
		}, 4500);
	}

	function initSwiper() {
		if (typeof Swiper === 'undefined') return;
		var el = document.querySelector('.sectionSwiper');
		if (!el) return;
		new Swiper(el, {
			slidesPerView: 1.12,
			spaceBetween: 12,
			grabCursor: true,
		});
	}
	function init() {
		initKeyVisual();
		initSwiper();
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
