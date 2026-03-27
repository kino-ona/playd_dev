<?php
include_once('./_common.php');

$qna = get_qna($_POST['seq']);

if (!$qna['A_SEQ']) {
    $msg .= '게시물이 존재하지 않습니다.';
} else {
    // 게시글 삭제
    qna_delete($qna['A_SEQ']);
    

    //로그 기록
    insert_mgr_log('삭제','문의 관리 > '.get_code_str($a_code),'');

    alert("삭제되었습니다.", "./qna.php?bc_code=".$a_code.$qstr);
}

if ($msg)
    alert($msg);
?>