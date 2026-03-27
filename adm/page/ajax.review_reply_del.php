<?php
include_once('./_common.php');

$cmnt_no = trim($_POST['cmnt_no']);

$reply = get_review_reply($cmnt_no);

// 로그인중이고 자신의 글이라면 또는 관리자인 경우만 패스
if (!($member['user_no'] && $reply['user_no'] === $member['user_no'] || $is_admin)) {
    echo "404";
    exit;
}

if (!$reply['cmnt_no']) {
    echo "404";
    exit;
} else {
    // 리뷰 댓글 삭제
    review_reply_delete($cmnt_no);
}
?>
