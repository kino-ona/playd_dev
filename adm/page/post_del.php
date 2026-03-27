<?php
include_once('./_common.php');

$post = get_write($_POST['seq']);

if (!$post['B_SEQ']) {
    $msg .= '게시물이 존재하지 않습니다.';
} else {
    // 게시글 삭제
    post_delete($post['B_SEQ']);
    
     //로그 기록
     insert_mgr_log('삭제','게시판 관리 > '.get_code_str($b_code), "");


    alert("삭제되었습니다.", "./post.php?bc_code=".$b_code.$qstr);
}

if ($msg)
    alert($msg);
?>