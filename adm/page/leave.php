<?php
include_once('./_common.php');

if ($is_guest) {
    alert("로그인 후 이용하세요.", "./login");
}

// 회원탈퇴일을 저장
$sql = " update {$y1['user_master_table']}
            set login_key = null,
                login_key_old = '{$member['login_key']}',
                user_state = '3',
                out_dttm = '".Y1_TIME_YMDHIS."'
          where user_no = '{$member['user_no']}' ";
sql_query($sql);

// 3.09 수정 (로그아웃)
unset($_SESSION['ss_login_key']);

if (!$url)
    $url = Y1_PAGE_URL.'/store_list';

alert($member['nickname'].'님께서는 '. date("Y년 m월 d일") .'에 회원 탈퇴 하셨습니다.', $url);
?>
