<meta charset="UTF-8">
<?php
    include "../Libs/dbcon.php";
    include "../Libs/db_handle.php";

    $func = new db_handle();


	// 구글캡챠 2020-12-10 이츠앰 김형호
	if(isset($_POST['g-recaptcha-response'])){ 
		$captcha=$_POST['g-recaptcha-response']; 
	} 
	if(!$captcha) { 
		$func->popup_msg_js("등록폼에 리캡챠를 확인하세요.");
	} 
	$secretKey = "6Ld5UAAaAAAAAE4HRe-i4PVusxMhnrTxqp7ayIiB"; 
	$ip = $_SERVER['REMOTE_ADDR']; 
	$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip); 
	$responseKeys = json_decode($response,true); 
	if(intval($responseKeys["success"]) !== 1) { 
		$func->popup_msg_js("검증을 통과하지 못했습니다.");
	}

    if(!$newsletter_agreed) $func->popup_msg_js("개인정보 수집에 동의해주시기 바랍니다.","/contents/etc/prc_complete.html");
    if(!$news_email[0] || !$news_email[1]) $func->popup_msg_js("이메일을 입력해주시기 바랍니다.");

    //$email = trim($news_email[0])."@".trim($news_email[1]);
    //$sql = "INSERT INTO T_NEWSLETTER (NS_MAIL, NS_REGDATE) VALUES ('$email', NOW())";

    $write_arr =
    array("yahoo.com","YAHOO.COM","AOL.COM","aol.com","PLASTICOIL.COM","ssgen.com","COMCAST.NET","PREFERREDCONTRACT.COM","WESBANCO.COM","GS-CONSTRUCTION.COM","monroecounty.gov","CHARTER.NET","mojogcm.com","lyft.com","cttempcontrols.com","energyprint.com","ssemarketing.net","ICLOUD.COM","joujouintlinc.com","homesteadproperties-inc.com","SDAINC.NET","PEOPLEINC-FR.ORG","susd.org","EAFENCE.NET","ormutual.com","mac.com","RELEVANTCOMMUNITY.ORG","michiganbread.com","AZPLEA.COM","am.jll.com","FORDLUMBERSUPPLY.COM","thelaseragent.com","GLOBALES-LLC.COM","SCHERRCONTRACTING.COM","SUBHANLAW.COM","ACCENTCARE.COM","PRESTIGECONSTRUCTIONNE.COM","BELLFENCE.COM","SEQUOYAH.COM","ABENAKITIMBER.COM","KW.COM","CARESABQ.COM","verizon.net","rossercapitalpartners.com","CSWGRAPHICS.COM","susancurtisfoundation.org","peopleincfr.org","tampabay.rr.com","co.allen.in.us","NATIONALTURFINC.COM","uticak12.org","NCFG.COM");

    $aaa = explode("@", $news_email);

    if(in_array($aaa[1], $write_arr)) {
        //------- 등록제한 -------//
    } else {
        $sql = "INSERT INTO T_NEWSLETTER (NS_MAIL, NS_REGDATE) VALUES ('$news_email', NOW())";
        $result = mysql_query($sql);
    }

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
