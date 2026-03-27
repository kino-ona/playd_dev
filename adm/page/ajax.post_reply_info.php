<?php
include_once('./_common.php');

$cmnt_no = trim($_POST['cmnt_no']);

$reply = get_post_reply($cmnt_no);

if (!$reply['cmnt_no']) {
    echo "404";
    exit;
} else {
    echo json_encode($reply, JSON_UNESCAPED_UNICODE);
}
?>