<?php

// if (empty($_SESSION['CSRFToken2'])) {
//     $_SESSION['CSRFToken2'] = bin2hex(random_bytes(32));
// }

// $CSRFToken2 = $_SESSION['CSRFToken2'];
?>
<!-- 20220617 s -->
<div class="layer__body popup newsLetter" id="newsLetter" aria-hidden="true" role="dialog">
  <div class="layer__wrap layer__wrap--full">
		<div class="layer__close">
			<button class="close" onclick="javascript:clearLetter();"><span>close</span></button>
		</div>
		<div class="layer__inner">
			<div class="layer__head">
				<p class="content__title">PlayD 뉴스레터 신청</p>
			</div>
			<div class="layer__content">
				<div class="content__text">
					<p class="content__description">광고·마케팅과 관련한 최근 미디어 이슈부터, 트렌드, 인사이트 리포트까지 뉴스레터로 손쉽게 받아보세요.</p>
					
					<input type="hidden" name="CSRFToken2" id="CSRFToken2" value="<?php echo $CSRFToken2; ?>" />
						
						<legend>플레이디 뉴스레터 신청폼</legend>
						<div class="input-box input-box--info">
							<div class="form-field">
								<label for="user-name" class="input-box--info__label input-box--info__label--necessary">이름</label>
								<input type="text" id="user-name"  class="input-box--info__input" name="" maxlength="52" placeholder="이름을 입력해 주세요.">
								<p class="form-warning"></p>
							</div>
							<div class="form-field">
								<label for="user-profession" class="input-box--info__label input-box--info__label--necessary">직업</label>
								<input type="text" id="user-profession" class="input-box--info__input" name="" maxlength="52" placeholder="직업을 입력해 주세요.">
								<p class="form-warning"></p>
							</div>
							<div class="form-field">
								<label for="user-mail" class="input-box--info__label input-box--info__label--necessary">이메일 주소</label>
								<input type="text" id="user-mail"  class="input-box--info__input" name="" maxlength="320" placeholder="이메일 주소를 입력해 주세요.">
								<p class="form-warning"></p>
							</div>
							<div class="form-field">
								<label for="user-position" class="input-box--info__label">직급</label>
								<input type="text" id="user-position" class="input-box--info__input" name="" maxlength="52" placeholder="직급을 입력해 주세요.">
							</div>
							<div class="form-field">
								<label for="user-company" class="input-box--info__label">회사명(소속)</label>
								<input type="text" id="user-company" class="input-box--info__input" name="" maxlength="52" placeholder="회사명(소속)을 입력해 주세요.">
							</div>
							<div class="form-field">
								<label for="user-team" class="input-box--info__label">부서(팀명)</label>
								<input type="text" id="user-team" class="input-box--info__input" name="" maxlength="52" placeholder="부서(팀명)을 입력해 주세요.">
							</div>
							<ol class="sub-checkboxs">
                <li class="sub-checkbox__list">
                  <label for="sub-checkbox--personal" class="sub-checkbox__label">
                    <input type="checkbox" id="sub-checkbox--personal" name="" class="sub-checkbox__input" onclick="checkboxClick();">
                    <span><i></i>개인 정보 수집 및 이용에 동의합니다.</span>
                  </label>
                </li>
								<li>
									<p class="form-description">
										입력하신 이름, 직업, 이메일 주소, 직급, 회사명
										(소속), 부서(팀명)은 뉴스레터 발송을 위해 정보주체로부터 개인 정보를 수집 시에 동의 받은 개인 정보 
										보유, 이용 기간 동안 보관 및 이용됩니다.
									</p>
								</li>
              </ol>
						</div>
					
				</div>
				<div class="button__wrap">
					<input type="button" class="button layer__button form-submit" value="확인" onclick="letterWriteProc();"/>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="layer__body popup form-popup letterFormSubmitted" id="letterFormSubmitted" aria-hidden="true" role="dialog">
  <div class="layer__wrap">
    <div class="layer__inner">
      <div class="layer__content">
        <p class="message__title"><span class="icon icon__check"><i></i></span>뉴스레터 신청이 완료되었습니다.</p>
        <p class="message__text">플레이디 뉴스레터를 구독해주셔서 감사합니다.</p>
        <button type="button" class="button layer__button close" onclick="letterOk();">확인</button>
      </div>
    </div>
  </div>
</div>
<!--// 20220617 e -->



<!-- 20220629 s -->
<div class="layer__body popup reportDownload" id="report" aria-hidden="true" role="dialog">
  <div class="layer__wrap layer__wrap--full">
		<div class="layer__close">
			<button class="close" onclick="javascript:clearReport();"><span>close</span></button>
		</div>
		<div class="layer__inner">
			<div class="layer__head">
				<p class="content__title">PlayD 트렌드 리포트 다운로드</p>
			</div>
			<div class="layer__content">
				<div class="content__text">
					<p class="content__description">간단한 정보를 입력하시면 플레이디 리포트를 빠르게 받아보실 수 있습니다.</p>
					
					<input type="hidden" name="CSRFToken2" id="CSRFToken2" value="<?php echo $CSRFToken2; ?>" />
						
							<legend>플레이디 리포트 다운로드 신청폼</legend>
						<div class="input-box input-box--info">
							<div class="form-field">
								<label for="user-name" class="input-box--info__label input-box--info__label--necessary">이름</label>
								<input type="text" id="user-name"  class="input-box--info__input" name="" maxlength="52" placeholder="이름을 입력해 주세요.">
								<p class="form-warning"></p>
							</div>
							<div class="form-field">
								<label for="user-profession" class="input-box--info__label">직업</label>
								<input type="text" id="user-profession" class="input-box--info__input" name="" maxlength="52" placeholder="직업을 입력해 주세요.">
								<p class="form-warning"></p>
							</div>
							<div class="form-field">
								<label for="user-mail" class="input-box--info__label input-box--info__label--necessary">이메일 주소</label>
								<input type="text" id="user-mail"  class="input-box--info__input" name="" maxlength="320" placeholder="이메일 주소를 입력해 주세요.">
								<p class="form-warning"></p>
							</div>
							<div class="form-field">
								<label for="user-position" class="input-box--info__label">직급</label>
								<input type="text" id="user-position" class="input-box--info__input" name="" placeholder="직급을 입력해 주세요.">
							</div>
							<div class="form-field">
								<label for="user-company" class="input-box--info__label input-box--info__label--necessary">회사명(소속)</label>
								<input type="text" id="user-company" class="input-box--info__input" name="" maxlength="52" placeholder="회사명(소속)을 입력해 주세요.">
							</div>
							<div class="form-field">
								<label for="user-team" class="input-box--info__label">부서(팀명)</label>
								<input type="text" id="user-team" class="input-box--info__input" name="" maxlength="52" placeholder="부서(팀명)을 입력해 주세요.">
							</div>
							<ol class="sub-checkboxs">
                <li class="sub-checkbox__list">
                  <label for="sub-checkbox--personal2" class="sub-checkbox__label">
                    <input type="checkbox" id="sub-checkbox--personal2" name="" class="sub-checkbox__input" onclick="personal2check(this.checked);">
                    <span><i></i>[필수] 개인정보 수집 및 이용에 동의합니다.</span>
										<a href="#" onclick="layerOpen('personalPopup');" class="button button__sub-checkbox open__personal">전문보기</a>
                  </label>
                </li>
              </ol>
							<ol class="sub-checkboxs">
                <li class="sub-checkbox__list">
                  <label for="sub-checkbox--marketing" class="sub-checkbox__label">
                    <input type="checkbox" id="sub-checkbox--marketing" name="" class="sub-checkbox__input">
                    <span><i></i>[선택] 광고성 정보 수집 및 이용에 동의합니다.</span>
										<a href="#" onclick="layerOpen('marketingPopup');" class="button button__sub-checkbox open__marketing">전문보기</a>
                  </label>
                </li>
              </ol>
						</div>
				</div>
				<div class="button__wrap">
					<input type="button" class="button layer__button form-submit" id="personal2" value="리포트 다운로드" onclick="reportWriteProc(); " />
				</div>
			</div>
		</div>
	</div>
</div>
<!--// 20220629 e -->

<!-- 2024.05.02 -->
<div class="layer__body popup personalPopup" id="personalPopup" aria-hidden="true" role="dialog">
  <div class="layer__wrap layer__wrap--wide">
    <div class="layer__close">
      <button onclick="layerClose('personalPopup');" class="closePopup">close</button>
    </div>
    <div class="layer__inner">
      <div class="layer__head">
        <p class="content__title">개인정보 수집 및 이용동의</p>
      </div>
      <div class="layer__content">
        <div class="content__text">
					<p>
            <span>1.</span>
            <span>
              처리하는 개인정보의 항목<br>
              - 이름, 이메일 주소, 회사명, 직업, 직급, 부서명
            </span>
					</p>
          <p>
            <span>2.</span>
            <span>
              개인정보의 수집·이용 목적<br>
							- PlayD는 자체 발간 트렌드 리포트 제공을 목적으로 개인정보를 수집하여 처리하고 있습니다.
            </span>
          </p>
          <p>
            <span>3.</span>
            <span>
              개인정보의 보유 및 이용기간<br>
							- 수집된 개인정보는 1년 간 보유하며, 1년 이후에는 지체없이 파기합니다.<br>
							(단, 정보주체의 파기 요청이 있는 경우 즉시 파기합니다.)
            </span>
          </p>
        </div>
        <div class="button__wrap">
          <button onclick="layerClose('personalPopup');" class="button layer__button closePopup">확인</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- 2024.05.02 -->

<!-- 2024.05.02 -->
<div class="layer__body popup marketingPopup" id="marketingPopup" aria-hidden="true" role="dialog">
  <div class="layer__wrap layer__wrap--wide">
    <div class="layer__close">
      <button onclick="layerClose('marketingPopup');" class="closePopup">close</button>
    </div>
    <div class="layer__inner">
      <div class="layer__head">
        <p class="content__title">광고성 정보 수신 동의</p>
      </div>
      <div class="layer__content">
        <div class="content__text">
					<p>
            <span>1.</span>
            <span>
              수집하는 항목<br>
              - 이름, 이메일 주소, 회사명
            </span>
					</p>
          <p>
            <span>2.</span>
            <span>
              수집·이용 목적<br>
							- 플레이디 뉴스레터, 무료 컨설팅, 마케팅 정보 등 광고·마케팅과 관련된 전자적 전송 매체를 발송하는데 개인정보를 활용합니다.
            </span>
          </p>
          <p>
            <span>3.</span>
            <span>
              보유 및 이용기간<br>
              - 수집된 정보는 2년 간 보유하며, 2년마다 수신동의 여부를 확인합니다.<br>
              - 별도의 수신거부 의사 표현이 있는 경우 보유한 정보를 파기합니다.
            </span>
          </p>
        </div>
        <div class="button__wrap">
          <button onclick="layerClose('marketingPopup');" class="button layer__button closePopup">확인</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- 2024.05.02 -->

<!-- footer -->
<section class="banner banner__footer">
	<div class="banner__content">
		<p class="banner__title">플레이디와 함께 멈추지 않는 성장을 경험하세요.</p>
		<div class="button__wrap">
			<a href="contact.html" class="button button__radius button__lined-white">프로젝트 문의</a>
			<a href="../assets/files/PLAYD-CompanyIntroduction.pdf" target="_blank" class="button button__radius button__filled-white" onclick="_LA.EVT('6128')">회사 소개서 다운로드</a>
		</div>
	</div>
</section>
<footer class="footer">
	<div class="footer-top">
		<div class="footer-top__inner">
			<a href="/" class="logo logo__footer"><span class="a11y">플레이디</span></a>
			<ul class="footer__nav">
				<li><a href="ethical.html">윤리경영 신고</a></li>
				<li><a href="letter.html">뉴스레터</a></li>
				<li><a href="contact.html#playd">오시는 길</a></li>
				<li><a href="https://sensen.techhub.co.kr/apply/diagnosis?la_gc=TR9161705801&la_src=cmpn&la_cnfg=525955" target="_blank">무료 광고 진단</a></li>
				<li><a href="personal.html">개인정보 처리방침</a></li>
			</ul>
			<nav class="footer__nav--right">
				<a href="https://blog.naver.com/playd_log" target="_blank" class="sns-link"><img src="../assets/images/w/common/icons/footer_sns-naverblog.png" /><span class="a11y">네이버</span></a>
				<a href="https://www.instagram.com/playd.official/" target="_blank" class="sns-link"><img src="../assets/images/w/common/icons/footer_sns-instagram.png" /><span class="a11y">인스타그램</span></a>
				<!-- <a href="https://www.youtube.com/c/PLAYD" target="_blank" class="sns-link"><img src="../assets/images/w/common/icons/footer_sns-youtube.png" /><span class="a11y">유투브</span></a>
				<a href="https://www.facebook.com/playd.inc" target="_blank" class="sns-link"><img src="../assets/images/w/common/icons/footer_sns-facebook.png" /><span class="a11y">페이스북</span></a> -->
				<a href="https://pletter.stibee.com/" target="_blank" class="sns-link sns-link--platter"><img src="../assets/images/w/common/icons/footer_sns-platter.png" /><span class="a11y">플레터</span></a><!-- 20240126 -->
			</nav>
		</div>
	</div>
	<div class="footer-bottom">
		<ul class="footer__util">
			<li>(주)플레이디</li>
			<li>대표자 : 조명진 | 사업자등록번호 : 129-86-43885</li>
			<li>주소 : 경기도 성남시 분당구 황새울로 359번길 11, 5~6층</li>
			<li>이메일 : <a href="mailto:master@playd.com">master@playd.com</a></li>
			<li class="copyright">Copyright &copy; PlayD.,Ltd, All rights reserved.</li>
		</ul>
		<nav class="footer__util--right">
			<p class="information"><span class="information__title">대표전화</span>1566-3265</p>
			<ul class="information__desc">
				<li>평일 10:00~17:00</li>
			</ul>
			<div class="information__download">
				<p>Company Profile Download</p>
				<div class="download_link">
					<a href="../assets/files/PLAYD-CompanyIntroduction.pdf" target="_blank">KOREAN <i><span class="a11y">회사소개서 다운로드</span></i></a> 
					<a href="../assets/files/PLAYD-Company_Introduction(en).pdf" target="_blank">ENGLISH <i><span class="a11y">회사소개서 다운로드</span></i></a> 
				</div>
			</div>
		</nav>
	</div>
</footer>
<div class="top-button">
	<a href="/"><span class="a11y">위로가기</span></a>
</div>

<script src="../assets/js/w/swiper.min.js"></script>
<script src="../assets/js/w/ui.js?v=0706"></script>
<?php
$__playd_uri = (string) (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
if ($__playd_uri === '/w/index.html') {
    echo '<script src="/assets/js/w/index.js"></script>'."\n";
}
unset($__playd_uri);
?>
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script src="../assets/js/jquery-ui-1.13.1.js"></script>
<script src="../assets/js/datepicker.js"></script>

<script>

$(document).ready(function(){
	Kakao.init('7b1167e36a73b18de0fe0588ace1cdf8'); 


});

function letterWriteProc(){
	
	$('#newsLetter').find('.form-field').each(function(){
		$(this).removeClass('warning');
	});

	if($('#newsLetter').find('#user-name').val()=='') {
		alert('이름을 입력해 주세요.');
		$('#newsLetter').find('#user-name').focus();
		$('#newsLetter').find('#user-name').closest('.form-field').addClass('warning');
		
		return;
	}
	// if($('#newsLetter').find('#user-profession').val()==''){
	// 	alert('직업을 입력해 주세요.');
	// 	$('#newsLetter').find('#user-profession').focus();
	// 	$('#newsLetter').find('#user-profession').closest('.form-field').addClass('warning');
	// 	return;
	// }
	if($('#newsLetter').find('#user-mail').val()==''){
		alert('이메일을 입력해 주세요.');
		$('#newsLetter').find('#user-mail').focus();
		$('#newsLetter').find('#user-mail').closest('.form-field').addClass('warning');
		return;
	}
	if(!validateEmail($('#newsLetter').find('#user-mail').val())){
		alert('이메일을 다시 입력해 주세요.');
		$('#newsLetter').find('#user-mail').focus();
		$('#newsLetter').find('#user-mail').closest('.form-field').addClass('warning');
		return;
	}
	// if($('#user-position').val()==''){
	// 	alert('직급을 입력해 주세요.');
	// 	return;
	// }
	if($('#user-company').val()==''){
		alert('회사명(소속)을 입력해 주세요.');
		$('#newsLetter').find('#user-mail').focus();
		$('#newsLetter').find('#user-mail').closest('.form-field').addClass('warning');

		return;
	}
	// if($('#user-team').val()==''){
	// 	alert('부서(팀명)을 입력해 주세요.');
	// 	return;
	// }
	if(!$('#newsLetter').find("input:checkbox[id='sub-checkbox--personal']").is(":checked")){
		alert('개인 정보 수집 및 이용에 동의해주세요.');
		return;
	}

	if(isLoading) return;
	isLoading = true;
	
	$.ajax({
                    type: "POST",
                    url: '/ajax/ajax_board_proc.php',
                    data: {
                        'user_name':$('#newsLetter').find('#user-name').val(),
						'user_profession':$('#newsLetter').find('#user-profession').val(),
						'user_mail':$('#newsLetter').find('#user-mail').val(),
						'user_position':$('#newsLetter').find('#user-position').val(),
						'user_company':$('#newsLetter').find('#user-company').val(),
						'user_team':$('#newsLetter').find('#user-team').val(),
						'CSRFToken2':'<?php echo $CSRFToken2; ?>',
						'cmd':'letter_write',
                    },
                    dataType: 'json',
                    timeout: 60000,
                    cache: false,
                    crossDomain: false,
                    error: function (request, error) {
                        console.log(error + '');
                    },
                    success: function (json) {
						isLoading = false;
						if(json['success']){
							layerOpen('letterFormSubmitted');
							$('#newsLetter').find('#user-name').val('');
							$('#newsLetter').find('#user-profession').val('');
							$('#newsLetter').find('#user-mail').val('');
							$('#newsLetter').find('#user-position').val('');
							$('#newsLetter').find('#user-company').val('');
							$('#newsLetter').find('#user-team').val('');
						} else {
							alert('실패하였습니다. 토큰 문제로 관리자에게 문의해 주세요.');
						}
                    }
    });

}


function letterOk(){
	document.location.reload();
}



function reportWriteProc(){
	
	$('#report').find('.form-field').each(function(){
		$(this).removeClass('warning');
	});


	if($('#report').find('#user-name').val()=='') {
		alert('이름을 입력해 주세요.');
		$('#report').find('#user-name').focus();
		$('#report').find('#user-name').closest('.form-field').addClass('warning');
		return;
	}
	// if($('#report').find('#user-profession').val()==''){
	// 	alert('직업을 입력해 주세요.');
	// 	$('#report').find('#user-profession').focus();
	// 	$('#report').find('#user-profession').closest('.form-field').addClass('warning');
	// 	return;
	// }
	if($('#report').find('#user-mail').val()==''){
		alert('이메일을 입력해 주세요.');
		$('#report').find('#user-mail').focus();
		$('#report').find('#user-mail').closest('.form-field').addClass('warning');
		return;
	}
	if(!validateEmail($('#report').find('#user-mail').val())){
		alert('이메일을 다시 입력해 주세요.');
		$('#report').find('#user-mail').focus();
		$('#report').find('#user-mail').closest('.form-field').addClass('warning');
		return;
	}
	// if($('#user-position').val()==''){
	// 	alert('직급을 입력해 주세요.');
	// 	return;
	// }
	if($('#report').find('#user-company').val()==''){
		alert('회사명(소속)을 입력해 주세요.');
		$('#report').find('#ser-company').focus();
		$('#report').find('#ser-company').closest('.form-field').addClass('warning');

		return;
	}
	// if($('#user-team').val()==''){
	// 	alert('부서(팀명)을 입력해 주세요.');
	// 	return;
	// }
	if(!$('#report').find("input:checkbox[id='sub-checkbox--personal2']").is(":checked")){
		alert('개인정보 수집 및 이용에 동의해주세요.');
		return;
	}

	var marketing_yn = 'N';
	if($('#report').find("input:checkbox[id='sub-checkbox--marketing']").is(":checked")){
		marketing_yn = 'Y';
	}
	

	if(isLoading) return;
	isLoading = true;
	
	$.ajax({
                    type: "POST",
                    url: '/ajax/ajax_board_proc.php',
                    data: {
                        'user_name':$('#report').find('#user-name').val(),
						'user_profession':$('#report').find('#user-profession').val(),
						'user_mail':$('#report').find('#user-mail').val(),
						'user_position':$('#report').find('#user-position').val(),
						'user_company':$('#report').find('#user-company').val(),
						'user_team':$('#report').find('#user-team').val(),
						'user_marketing':marketing_yn,
						'user_seq':g_seq,
						'CSRFToken2':'<?php echo $CSRFToken2; ?>',
						'cmd':'newsreport',
                    },
                    dataType: 'json',
                    timeout: 60000,
                    cache: false,
                    crossDomain: false,
                    error: function (request, error) {
                        console.log(error + '');
                    },
                    success: function (json) {
						isLoading = false;
						if(json['success']){
							//layerOpen('letterFormSubmitted');

							if(g_url=='null') g_url = '';
							if(g_url != ''){
								window.open(g_url);
							} else {
								document.location.href = '/ajax/ajax_file_down.php?seq=' + g_val;
							}

							$('#report').find('#user-name').val('');
							$('#report').find('#user-profession').val('');
							$('#report').find('#user-mail').val('');
							$('#report').find('#user-position').val('');
							$('#report').find('#user-company').val('');
							$('#report').find('#user-team').val('');
							layerClose('report');
						} else {
							alert('실패하였습니다. 토큰 문제로 관리자에게 문의해 주세요.');
						}
                    }
    });

}




function kakaoShare(title, desc, imageUrl){

	Kakao.Link.sendDefault({
		objectType: "feed",
		content: {
			title: title, // 공유될 제목
			description: desc, // 공유될 설명
			imageUrl: imageUrl, // 공유될 이미지 url
			link: {
				mobileWebUrl: "<?= getFullUrl() ?>", // 공유될 모바일 URL
				webUrl: "<?= getFullUrl() ?>", // 공유될 웹 URL
			},
		},
	});

	//window.open('https://sharer.kakao.com/talk/friends/picker/shortlink/LYK%2560uGY%252FjraGo%2560qPv%252F_F%255EiYiVFaJooVoobE9s%255DydF94%255Bqs%25407z9zS0yz%253B%253DOyEqdYQ
	
}
//https://www.facebook.com/dialog/share?app_id=441801156302191&href=https%3A%2F%2Fn.news.naver.com%2Fmnews%2Farticle%2F001%2F0013268235%3Flfrom%3Dfacebook&display=popup&enc=utf-8

function facebookShare(){
	window.open('https://www.facebook.com/sharer/sharer.php?u=<?= getFullUrl() ?>');
}


function twitterShare(){
	window.open('https://twitter.com/intent/tweet?url=<?= getFullUrl() ?>');
}


function linkShare(){
	var tempElem = document.createElement('textarea');
	tempElem.value = '<?= getFullUrl() ?>';  
	document.body.appendChild(tempElem);

	tempElem.select();
	document.execCommand("copy");
	document.body.removeChild(tempElem);
	alert('복사하였습니다.');
}

function checkboxClick(){
	if(!$("input:checkbox[id='sub-checkbox--personal']").is(":checked")){
		$('.form-submit').removeClass('submit--active');
	} else {
		$('.form-submit').addClass('submit--active');
	}
}

function personal2check(chk){
	if(chk){
		$('#personal2').addClass('submit--active');
	} else {
		$('#personal2').removeClass('submit--active');
	}
}

function clearLetter(){
	$('#newsLetter').find('#user-name').val('');
							$('#newsLetter').find('#user-profession').val('');
							$('#newsLetter').find('#user-mail').val('');
							$('#newsLetter').find('#user-position').val('');
							$('#newsLetter').find('#user-company').val('');
							$('#newsLetter').find('#user-team').val('');
}

function clearReport(){
	$('#report').find('#user-name').val('');
							$('#report').find('#user-profession').val('');
							$('#report').find('#user-mail').val('');
							$('#report').find('#user-position').val('');
							$('#report').find('#user-company').val('');
							$('#report').find('#user-team').val('');
}

</script>




<!-- Footer Logscript  Start -------------------------------------------------------------------------------------------------------->	
<!--------- 삭제 금지 : PlayD TERA Log Script v1.2 -->
<script>
var _nSA=(function(_g,_s,_p,_d,_i,_h){ 
 if(_i.wgc!=_g){_i.wgc=_g;_i.wrd=(new Date().getTime());
 var _sc=_d.createElement('script');_sc.src=_p+'//sas.techhub.co.kr/'+_s+'gc='+_g+'&rd='+_i.wrd;
 var _sm=_d.getElementsByTagName('script')[0];_sm.parentNode.insertBefore(_sc, _sm);} return _i;
})('TR9947105674','sa-w.js?',location.protocol,document,window._nSA||{},location.hostname);
</script>
<!--------- 삭제 금지 : PlayD TERA Log Script v1.2 -->
<!-- 채널톡 -->
<script>
  (function(){var w=window;if(w.ChannelIO){return w.console.error("ChannelIO script included twice.");}var ch=function(){ch.c(arguments);};ch.q=[];ch.c=function(args){ch.q.push(args);};w.ChannelIO=ch;function l(){if(w.ChannelIOInitialized){return;}w.ChannelIOInitialized=true;var s=document.createElement("script");s.type="text/javascript";s.async=true;s.src="https://cdn.channel.io/plugin/ch-plugin-web.js";var x=document.getElementsByTagName("script")[0];if(x.parentNode){x.parentNode.insertBefore(s,x);}}if(document.readyState==="complete"){l();}else{w.addEventListener("DOMContentLoaded",l);w.addEventListener("load",l);}})();

  ChannelIO('boot', {
    "pluginKey": "3c1abb65-3b7e-4562-a85e-6ca7f57d0c15"
  });
</script>
<!-- 프리미엄 : 250210 -->
<script type="text/javascript" src="//wcs.naver.net/wcslog.js"> </script> 
<script type="text/javascript"> 
if (!wcs_add) var wcs_add={};
wcs_add["wa"] = "s_68190443255";
if (!_nasa) var _nasa={};
if(window.wcs){
wcs.inflow();
wcs_do();
}
</script>

<!-- Footer Logscript  END -------------------------------------------------------------------------------------------------------->

</body>
</html>
