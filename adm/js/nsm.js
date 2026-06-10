/*****************
디자인 셀렉트박스
*****************/
jQuery(function($){
	
	// Common
	var select_root = $('div.select');
	var select_value = $('.my_value, .ctrl');
	var select_a = $('div.select>ul>li>a');
	var select_input = $('div.select>ul>li>input[type=radio]');
	var select_label = $('div.select>ul>li>label');
	var select_ui = $('.a_list');
	
	// Radio Default Value
	$('div.my_value').each(function(){
		var default_value = $(this).next('.i_list').find('input[checked]').next('label').text();
		$(this).append(default_value);
	});
	
	// Line
	select_value.bind('focusin',function(){$(this).addClass('outLine')});
	select_value.bind('focusout',function(){$(this).removeClass('outLine')});
	select_input.bind('focusin',function(){$(this).parents('div.select').children('div.my_value').addClass('outLine')});
	select_input.bind('focusout',function(){$(this).parents('div.select').children('div.my_value').removeClass('outLine')});
	
	// Show
	function show_option(){
		$(this).parents('div.select:first').toggleClass('open');
	}
	
	// Hover
	function i_hover(){
		$(this).parents('ul:first').children('li').removeClass('hover');
		$(this).parents('li:first').toggleClass('hover');
	}
	
	// Hide
	function hide_option(){
		var t = $(this);
		setTimeout(function(){
			t.parents('div.select:first').removeClass('open');
		}, 1);
	}
	
	// Set Input
	function set_label(){
		var v = $(this).next('label').text();
		$(this).parents('ul:first').prev('.my_value').text('').append(v);
		$(this).parents('ul:first').prev('.my_value').addClass('selected');
	}
	
	// Set Anchor
	function set_anchor(){
		var v = $(this).text();
		$(this).parents('ul:first').prev('.my_value').text('').append(v);
		$(this).parents('ul:first').prev('.my_value').addClass('selected');
	}

	/*// Anchor Focus Out
	$('*:not("div.select a")').focus(function(){
		$('.a_list').parent('.select').removeClass('open');
	});
	*/
	
	select_value.click(show_option);
	//select_root.removeClass('open');
	select_root.mouseleave(function(){$(this).removeClass('open')});
	select_a.click(set_anchor).click(hide_option).focus(i_hover).hover(i_hover);
	select_input.change(set_label).focus(set_label);
	select_label.hover(i_hover).click(hide_option);
	
});

function imgOn(elem, on, off) {
	return elem.attr('src').replace(off,on);
}
function imgOff(elem, on, off ) {
	return elem.attr('src').replace(on,off);
}

/******************
링크 복사하기
******************/
function copy_trackback(trb) {
	var IE=(document.all)?true:false;
	if (IE) {
	if(confirm("이 글의 트랙백 주소를 클립보드에 복사하시겠습니까?"))
		window.clipboardData.setData("Text", location.href);
	} else {
		temp = prompt("이 글의 트랙백 주소입니다. Ctrl+C를 눌러 클립보드로 복사하세요", location.href);
	}
}

/*****************************
*스크롤 다운시 리스트 로드 함수*
*****************************/
var loadList = false;

$(window).resize(function() {
	var winW = window.innerWidth || document.body.clientWidth;
	if(winW >= 768) {
		loadList=false;
	} else {
		loadList=true;
	}
});

 //elem : 로드된 아이템을 넣을 장소 ex)  '.column_list > ul'
 //type : list / square
 //bool : 모바일에서만 로드 -> true , 전체에서 로드 -> false
 function scrollLoad(elem, type, bool, initnum) {
	var working = false,
		href = $('#next > a').attr('href'); //불러올 리스트의 링크
		num = initnum,
		//가져온 href의 뒤에 넘버값
		hrefparam = href + num;
	$(window).scroll(function() {
		// bool이 참일 때는 모바일에서만 리스트가 로드
		if(bool){ 
			if(loadList == false) { return false; }
		}
		var docH = $(document).height(), //도큐먼트 높이
				delta = docH - document.body.clientHeight - 400;

		//현재 리스트가 로드중일땐 실행 중단
		if(working){ return false; }

		//스크롤의 높이 값이 delta값보다 높을 시 리스트 로드
		if ($(window).scrollTop() > delta) {

			//연속 로드 방지
			working = true;

			//ajax가 실행될때 로딩이미지 노출
			$('.loading').stop(true, true).fadeIn();
			$.ajax({
				url: hrefparam,
				success: function(html) {

					//로드될 페이지
					var list = html;

					//로딩이미지가 사라지고 리스트 생성
					$('.loading').stop(true, true).fadeOut(function() {
						working = false;

						//불러오는 페이지안에 내용이 없을 경우 리스트 로드 중단
						if(list == '') {
							working = true;
						}
						
						//불러온 요소를 넣을 곳
						$(elem).append(list);
						
						if(type == 'list') {
							$('.normal:even').css({ backgroundColor: '#f2f4f5' });
							//넘버값 5씩 증가
							num += 5;
						} else if(type == 'square') {
							$('li.normal:nth-of-type(2n+0)').addClass('first3');
							$('.newletter_list li:nth-child(3n)').css({marginRight: 0});
							//넘버값 6씩 증가(회사소식 박스형)
							num += 6;
						}


						
						//다음 로딩때 넘버값 5씩 증가
						hrefparam = href + num;
					});

				}
			});
		}
	}); 
};

$(function() {
	/***********************
	서브페이지 상단이동 버튼
	***********************/
	(function() {
		$('.top_btn').click(function() {
			$(window).scrollTop(0);
		});
	})();



	/********************************************
	GNB 마우스 오버 함수 & 페이지 메뉴 활성화 함수
	********************************************/
	(function() {
		var title = $('.sectitle h2'), // 서브페이지 타이틀
				gnb = $('.gnb'), // GNB
				his = $('.history'), // 헤더, 푸터 히스토리 네비게이션
				depth1 = $('.depth1 > li > div > a'),
				depth2 = $('.depth2 > li > a'),
				depth3 = $('.depth3 > li > a'),
				docTop,
				timer;
		
		//768아래 해상도에서는 3뎁스 숨김/노출 스크립트 제거
		$(window).resize(function() {
			var winW = window.innerWidth || document.body.clientWidth;
			clearTimeout(timer);
			timer = setTimeout(function() {
				if(winW >= 768) {
					$('#header').removeClass('on');
					depth1.bind({
						mouseenter : function() {
							depth1.removeClass('on');
							depth2.removeClass('on');
							$('.gnb .depth3').css('display', 'none');
							$(this).addClass('on');
						}
					});

					depth2.bind({
						mouseenter : function() {
							$('.sub_bar').css('display','block');
							depth1.removeClass('on');
							$(this).parent().parent().siblings('a').addClass('on');
							$('.gnb .depth3').css('display', 'none');
							$(this).siblings('.depth3').css('display', 'block');
							depth2.removeClass('on');
							$(this).addClass('on');
						}
					});
					
					depth3.bind({
						mouseenter : function() {
							$(this).addClass('hover');
						},
						mouseleave : function() {
							depth3.removeClass('hover');
						}
					});

					$('#header').bind({
						mouseleave : function() {
							depth1.removeClass('on');
							depth2.removeClass('on');
							$('.gnb .depth3').removeAttr('style');
							
							if($('#wrap').attr('class') == 'main'){
								$('.sub_bar').css('display','none');
							} else {
								activeClass();
							}
						}
					});
				} else {
					depth1.unbind('mouseenter');
					depth2.unbind('mouseenter');
					depth3.unbind('mouseenter');
					depth3.unbind('mouseleave');
					$('#header').unbind('mouseleave');
					
				}
			}, 500);
		}).trigger('resize');
		
		//메인페이지에서는 메뉴활성화 실행중지
		if($('#wrap').attr('class') == 'main') {
			return false;
		} else {
			activeClass();
			activeHistory();
		}
		
		//현재 페이지 활성화 함수
		function activeClass() {
				// gnb안에서 서브페이지 타이틀과 같은 텍스트를 찾습니다.
				gnb.find('a').filter(function() {
					return $(this).text() == title.text();
				}).addClass('on');
//				gnb.find('a:contains("'+title.text()+'")').addClass('on');
				//찾은 텍스트의 부모 ul에 on클래스 추가 후 형제태그 a에도 on클래스를 추가합니다.
				gnb.find('a.on').closest('ul').addClass('on').siblings('a').addClass('on'); //
				gnb.find('a.on').closest('ul').parent().parent().siblings('a').addClass('on'); //
		}

		//현재 페이지 위치 로케이션에 추가
		function activeHistory() {
			//현재 페이지에서 활성화 될 depth3 메뉴가 없다면
			//history에서 depth3 태그를 삭제합니다.
			if(gnb.find('a:contains("'+title.text()+'")').text() == '') {
				his.find('.depth1').append('<a href="'+location.href+'" class="etc">'+title.text()+'</a>');
				his.find('.depth2').remove();
				his.find('.depth3').remove();
			} else {
				if(!$('.gnb .depth3 > li > a').hasClass('on')){
				his.find('.depth3').remove();
				//depth3 메뉴가 있다면 depth3 메뉴명을 history에 append 시킵니다.
				} else {
					his.find('.depth3').append($('.gnb .depth3 > li > a').filter('.on').clone());
				}
				//depth2 메뉴명을 history에 append 시킵니다.
				his.find('.depth2').append($('.gnb .depth2 > li > a').filter('.on').clone());
				//depth1 메뉴명을 history에 append 시킵니다.
				his.find('.depth1').append($('.gnb .depth2 > li > a').filter('.on').closest('ul').siblings('a').clone());
				
				$('#footer .history .depth3 > a').text($('#footer .history .depth3 > a').text().toLowerCase());
				$('#footer .history .depth2 > a').text($('#footer .history .depth2 > a').text().toLowerCase());
				$('#footer .history .depth1 > a').text($('#footer .history .depth1 > a').text().toLowerCase());
			}
		}
		
	})();
	
	/**********************
	스크롤시 헤더 축소 함수
	**********************/
	(function() {
		//스크롤 다운시 헤더 미니모드로 변환
		$(window).scroll(function() {
			var winW = window.innerWidth || document.body.clientWidth;
			if(winW >= 768) {
				var mini = $('.logo img').attr('src').replace('logo.jpg', 'logo_mini.png'),
						big = $('.logo img').attr('src').replace('logo_mini.png', 'logo.jpg');
				
				//메인페이지일때 실행 중지
				if($('#wrap').attr('class') == 'main') { return false; }
				if($(window).scrollTop() > 118) {
					if($('#header').hasClass('mini')){
						return false;
					} else {
						//스크롤높이가 67이상일때 헤더에 mini클래스 추가
						$('#header').addClass('mini').css({
							height: '53px'
						});
						//로고이미지 png파일로 변경
						$('.logo img').attr("src", mini);
					}
				} else {
					//스크롤높이가 67이하일때 헤더에 mini클래스 삭제 후 스타일 삭제
					$('#header').removeClass('mini').removeAttr('style');
					$('.logo img').attr("src", big);
				}
			}
		});
	})();

	/****************
	메인 인디케이터
	***************/
	(function() {
		//메인페이지 푸터 메뉴 클릭시 해당하는메뉴로 이동
		$('.section_scroll > div').bind('click', function() {
			var inDex = $(this).index() + 1, // 클릭한 메뉴의 인덱스값+1
				head = $('#header').height(),
				target = $('.section').eq(inDex),
				offset = target.offset(), // 클릭한 메뉴에 해당하는  메인컨텐츠 위치값
				top = offset.top; // 해당하는 메인컨텐츠의 높이값

			//$('.section_scroll > div').removeClass('on');
			//$(this).addClass('on'); //클릭한 메뉴 활성화

			//가져온 메인컨텐츠의 높이값만큼 스크롤 이동
			$('html, body').stop(true, false).animate({
				scrollTop: top - head
			});
		});
		
		//인디케이터 활성화
		$(window).scroll(function() {
			var currentPosition = $(this).scrollTop()+250;
			$('.section:not(.m_logo)').each(function() {
				var top = $(this).offset().top,
					bottom = top + $(this).height();
				if(currentPosition >= top && currentPosition <= bottom) {
					var inDex = $(this).index() -1;
					
					$('.section_scroll > div').removeClass('on');
					$('.section_scroll > div').eq(inDex).addClass('on');
				}
			});
		});


	})();

/**************
모바일 메뉴
**************/
	(function() {

			var y, moveY, scrollVal, gnbTop, moveTop, gnbH, maxH, timer;
			
			$(window).resize(function() {
				var winW = window.innerWidth || document.body.clientWidth;
				if(winW <768) {
					$('.depth1').css('top', 0);
					if($('#header').hasClass('mini')){
						$('#header').removeClass('mini').removeAttr('style');
					}
				} else {
					if(!$('#header').is(':visible')){
						$('#header').removeAttr('style');
					}
				}
			});

			$('.request .menu, .gnb .gnb_close, #header .mask').click(function() {
				if($('#header').is(':visible')){
					$('#header').css('display','none');
					$('.depth1').unbind('touchstart', initTouch);
					$('.mask').unbind('touchmove');
					$('.depth1').css('top', 0);
				} else {
					$('#header').css({
						display: 'block'
					});
					//아이폰에서 fixed메뉴 주소표시줄에 가려짐현상 방지
					$('html, body').animate({
						scrollTop: '+='+1
					});
					$('.depth1').css('top', 0);
					$('.depth1').bind('touchstart', initTouch);
					$('.mask').bind('touchmove', function(e) {
						e.preventDefault();
					})
				}
				return false;
			});
			
			//터치이벤트
			function initTouch(e) {
				var event = e.originalEvent;
				
				y = event.touches[0].screenY,
				scrollVal = $(window).scrollTop(),
				gnbTop = parseInt($('.depth1').css('top')),
				gnbH = $('.gnb').height(),
				maxH = $('.depth1').innerHeight()+$('.request_wrap').height() - gnbH;

				//alert(maxH);
				$('.depth1').bind('touchmove', moveTouch);
				$('.depth1').bind('touchend', endTouch);
			}
			
			//터치무브 이벤트
			function moveTouch(e) {
				moveY = event.touches[0].screenY;
				e.preventDefault();

				if( moveY > y) {
					moveTop = gnbTop + (moveY - y);
					//위로 이동
					$('.depth1').css({
						top : moveTop
					});
				} else if(moveY < y) {
					moveTop = gnbTop - (y - moveY);
					//아래로 이동
					$('.depth1').css({
						top : moveTop
					});
				}

			}
			
			//터치를 끝냈을때
			function endTouch() {
				if(moveTop > 0) {
					$('.depth1').stop(true, false).animate({
						top: 0
					});
				}
				
				if(moveTop < -maxH) {
					$('.depth1').stop(true, false).animate({
						top: -maxH
					});
				}
				$('.depth1').unbind('touchmove');
				$('.depth1').unbind('touchend');
			}

	})();
	/***********
	탭 컨텐츠
	***********/
	(function() {
		$('.tab_title').click(function() {
			$('.tab_title').removeClass('on');
			$('.tab_cont').removeClass('on');
			$(this).addClass('on');
			$(this).next().addClass('on');
			return false;
		});
	})();

	/**********************
	간편광고문의 호출 함수
	**********************/
	(function() {
		var btn = $('.request_wrap > .btn'), //간편광고 버튼
				close = $('.request_box .close'), //간평광고 닫기 버튼
				checkBtn = $('.privacy_check'),
				viewBtn = $('.privacy_view .view_btn'),
				okBtn = $('.right_btn .ok'),
				cancelBtn = $('.right_btn .cancel'),
				simpleBox = $('.request_box'),
				y, scrollVal, Top, boxH, maxH, moveY, moveTop;
		
		//간편광고문의 폼 노출
		btn.click(function() {
			mobileCheck();
			if(!$('.request_box').is(':visible')){
				$(this).addClass('on');
				$('.request_box').stop(true, false).slideDown(function() {
					$('#name').focus();
				});
			} else {
				$(this).removeClass('on');
				$('.request_box').stop(true, false).slideUp(function() {
					simpleBox.removeAttr('style');
					simpleBox.children('div').removeAttr('style');
					$(document).unbind('touchstart');
				});
				$('.layer_close').trigger('click');
			}
			return false;
		});
		
		//간편광고문의 폼 숨김
		close.bind({
			click : function() {
			//클래스 on 삭제
				btn.removeClass('on');
				//슬라이드업
				btn.trigger('click');
				$('.layer_close').trigger('click');
				return false;
			}
		});
		
		//간편광고문의 체크박스
		checkBtn.click(function() {
			if(!$(this).hasClass('check')){
				$(this).addClass('check');
				$(this).find('img').attr('src', imgOn($(this).find('img'), '_on.jpg', '_off.jpg'));
			} else {
				$(this).removeClass('check');
				$(this).find('img').attr('src', imgOff($(this).find('img'), '_on.jpg', '_off.jpg'));
			}
		});
		
		//개인정보취급방침전문보기
		$('.privacy_view > .view_btn').click(function() {
			$('.privacy_layer').addClass('on');
			return false;
		});
		$('.layer_close').click(function() {
			$('.privacy_layer').removeClass('on');
			return false;
		});

		function mobileCheck(resize) {
			var winW = window.innerWidth || document.body.clientWidth,
					winH = window.innerHeight || document.body.clientHeight,
					btnHeight = $('.request').innerHeight(),
					boxHeight = resize || $('.request_box').innerHeight() + btnHeight ;
			//alert(winH);
			//브라우저 사이즈가 768보다 작을 때
			if(winW < 768) {
				//브라우저의 높이가 문의광고 입력폼의 높이보다 작을 때
				if(winH < boxHeight){
					simpleBox.css({
						height: winH - btnHeight,
						overflow: 'hidden'
					});
				//	alert(winH - btnHeight);
					$(document).bind('touchstart', initTouch);
				} else {
					simpleBox.css({
							height: 'auto'
					});
				}
				simpleBox.children('div').css('top',0);
			}
		}
		

		//터치이벤트
		function initTouch(e) {

			var event = e.originalEvent;
			
			y = event.touches[0].screenY,
			scrollVal = $(window).scrollTop(),
			Top = parseInt($('.request_box > div').css('top')),
			boxH = $('.request_box').height(),
			maxH = ($('.request_box > div').innerHeight()) - $('.request_box').height() ;
			
			$(document).bind('touchmove', moveTouch);
			$(document).bind('touchend', endTouch);
		}
		
		//터치무브 이벤트
		function moveTouch(e) {

			moveY = event.touches[0].screenY;
			if( moveY > y) {
				moveTop = Top + (moveY - y);
				//위로 이동
				$('.request_box > div').css({
					top : moveTop
				});
			} else if(moveY < y) {
				moveTop = Top - (y - moveY);
				//아래로 이동
				$('.request_box > div').css({
					top : moveTop
				});
			}
			e.preventDefault();
		}
		
		//터치를 끝냈을때
		function endTouch() {
			if(moveTop > 0) {
				$('.request_box > div').stop(true, false).animate({
					top: 0
				});
			}
			
			if(moveTop < -maxH) {
				$('.request_box > div').stop(true, false).animate({
					top: -maxH
				});
			}
			$(document).unbind('touchmove');
			$(document).unbind('touchend');
		}
		$(window).resize(function() {
			var winW = window.innerWidth || document.body.clientWidth;
			if(winW < 768) {
				var btnHeight = $('.request').innerHeight(),
						boxHeight = $('.request_box > div').innerHeight() + btnHeight
				if($('.request_box').is(':visible')){
					mobileCheck(boxHeight);
				}
			} else {
				simpleBox.css({
					height: 'auto'
				});			
			}
		});
	})();
	
	/****************
	RECRUIT | 채용문의
	****************/
	(function() {
		var li = $('.qna_list ul li');
		$('.qna_title').click(function() {
			if($(this).closest('li').hasClass('on')){
				$(this).closest('li').removeClass('on');
			} else {
				li.removeClass('on');
				$(this).closest('li').addClass('on');
			}
			return false;
		});
	})();
});
