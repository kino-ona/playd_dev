<?php
include_once('./_common.php');

$cmnt_no = trim($_POST['cmnt_no']);
$type    = trim($_POST['type']);

$reply = get_post_reply($cmnt_no);

// 로그인중이고 자신의 글이라면 또는 관리자인 경우만 패스
if (!($member['user_no'] && $reply['user_no'] === $member['user_no'] || $is_admin)) {
    echo "404";
    exit;
}

if (!$reply['cmnt_no']) {
    echo "404";
    exit;
} else {
    // 게시글 댓글 삭제
    post_reply_delete($reply['cmnt_no'], $reply['post_no'], $reply['parent_no'], $type);
}
?>