<?php
include_once('./_common.php');

if (!$board['BC_CODE']) {
   alert('존재하지 않는 게시판입니다.', P1_URL);
}

// 목록, 삭제 링크
$list_href = $delete_href = '';
$list_href = P1_PAGE_URL.'/qna.php';
$del_href  = P1_PAGE_URL.'/qna_del.php';

if ($m == "") {
    $html_title = "등록";

    if ($a_seq) {
        alert('글쓰기에는 \$a_seq 값을 사용하지 않습니다.', P1_PAGE_URL.'/qna.php?bc_code='.$bc_code);
    }  
} else if ($m == "u") {
    $html_title = "수정";
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

set_session('ss_bc_code', $bc_code);
set_session('ss_a_seq', $a_seq);

$content = get_text($write['A_CONT'], 0);

$p1['title']    = $board['BC_NAME'];
$p1['subtitle'] = $board['BC_GROUP'];
include_once('./_head.php');


//권한체크
$auth = get_check_admin_auth($member); //alert('권한이 없습니다.', P1_URL);
if($auth['read'] != 'Y') {
    alert('권한이 없습니다.', '/adm/page/login.php');
    exit;
}

// add_javascript(P1_SMART_EDIT_JS, 0);    // SMART_EDIT js

include_once(P1_POST_SKIN_PATH.'/'.$board['BC_SKIN'].'/write.php');

include_once('./_tail.php');
?>