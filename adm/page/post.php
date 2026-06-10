<?php
include_once('./_common.php');


if (!$board['BC_CODE']) {
   alert('존재하지 않는 게시판입니다.', P1_URL);
}

if (!isset($page) || (isset($page) && $page == 0)) $page = 1;

$p1['title']    = $board['BC_NAME'];
$p1['subtitle'] = $board['BC_GROUP'];
include_once('./_head.php');

// 게시물 아이디가 있다면 게시물 수정을 INCLUDE
if (isset($b_seq) && $b_seq) {
    include_once(P1_PAGE_PATH.'/post_write.php');
}

// b_seq 값이 없다면 목록을 보임
if (empty($b_seq)) {
    include_once(P1_PAGE_PATH.'/post_list.php');
}

include_once('./_tail.php');
?>