<meta charset="UTF-8">
<?php
    include "../Libs/dbcon.php";
    include "../Libs/db_handle.php";

    $func = new db_handle();

    if(!$newsletter_agreed) $func->popup_msg_js("개인정보 수집에 동의해주시기 바랍니다.");
    if(!$news_email[0] || !$news_email[1]) $func->popup_msg_js("이메일을 입력해주시기 바랍니다.");

    //$email = trim($news_email[0])."@".trim($news_email[1]);

    //$sql = "INSERT INTO T_NEWSLETTER (NS_MAIL, NS_REGDATE) VALUES ('$email', NOW())";
	$sql = "INSERT INTO T_NEWSLETTER (NS_MAIL, NS_REGDATE) VALUES ('$news_email', NOW())";
	$result = mysql_query($sql);

    if($result) {
?>
<!-- 전환페이지 설정 -->
<script type="text/javascript" src="//wcs.naver.net/wcslog.js"></script> 
<script type="text/javascript"> 
var _nasa={};
_nasa["cnv"] = wcs.cnv("4","1"); // 전환유형, 전환가치 설정해야함. 설치매뉴얼 참고
</script> 

<!-- 공통 적용 스크립트 , 모든 페이지에 노출되도록 설치. 단 전환페이지 설정값보다 항상 하단에 위치해야함 --> 
<script type="text/javascript" src="//wcs.naver.net/wcslog.js"> </script> 
<script type="text/javascript"> 
if (!wcs_add) var wcs_add={};
wcs_add["wa"] = "s_68190443255";
if (!_nasa) var _nasa={};
wcs.inflow();
wcs_do(_nasa);
</script>

<?	
	$func->popup_msg_js("구독 신청이 완료되었습니다.");
	}else $func->popup_msg_js("구독 신청 실패\\n관리자에게 문의해주시기 바랍니다.");
?>
