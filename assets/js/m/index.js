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
			slidesPerView: "auto",
			grabCursor: true,
		});
	}

  function initPopup() {
		const startDate = new Date("2026/09/01 10:00:00");
		const endDate = new Date("2026/09/30 23:59:59");

		const today = Date.now();

		if (today >= startDate && today <= endDate) {
			const setCookie = function (name, value, exp) {
				var date = new Date();
				date.setTime(date.getTime() + exp * 24 * 60 * 60 * 1000);
				document.cookie =
					name + "=" + value + ";expires=" + date.toUTCString() + ";path=/";
			};

			const getCookie = function (name) {
				var value = document.cookie.match("(^|;) ?" + name + "=([^;]*)(;|$)");

				return value ? value[2] : null;
			};

			if (!getCookie("main-popup--checked")) {
				layerOpen("main-popup");
			}

			document
				.querySelector(".main-popup .close")
				.addEventListener("click", () => {
					if (
						document.querySelector(".main-popup .sub-checkbox__input:checked")
					) {
						setCookie("main-popup--checked", "true", 1);
						layerClose("main-popup");
					} else {
						layerClose("main-popup");
					}
				});

			document
				.querySelector(".main-popup .layer__footer button")
				.addEventListener("click", () => {
					if (
						document.querySelector(".main-popup .sub-checkbox__input:checked")
					) {
						setCookie("main-popup--checked", "true", 1);
						layerClose("main-popup");
					} else {
						layerClose("main-popup");
					}
				});
		}
	}

	function init() {
		initKeyVisual();
		initSwiper();
		initPopup();
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
