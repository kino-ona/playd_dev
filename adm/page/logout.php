<?php
include_once('./_common.php');

session_unset(); // 모든 세션변수를 언레지스터 시켜줌
session_destroy(); // 세션해제함

if ($url) {
    if ( substr($url, 0, 2) == '//' )
        $url = 'http:' . $url;

    $p = @parse_url(urldecode($url));
    /*
        // OpenRediect 취약점관련, PHP 5.3 이하버전에서는 parse_url 버그가 있음 ( Safflower 님 제보 ) 아래 url 예제
        // http://localhost/bbs/logout.php?url=http://sir.kr%23@/
    */
    if (preg_match('/^https?:\/\//i', $url) || $p['scheme'] || $p['host']) {
        alert('url에 도메인을 지정할 수 없습니다.', P1_URL);
    }

    $link = $url;
} else {
    $link = P1_PAGE_URL.'/login.php';
}

goto_url($link);
?>
