<?php
define('_LOGIN_', true);
include_once './_common.php';

$p1['title'] = '로그인 검사';

$back_url = $url ? $url : './login';

// 이메일 로그인
$m_id = trim($_POST['m_id']);
$m_pw = trim($_POST['m_pw']);

if (!$m_id || !$m_pw) {
    alert('로그인 정보가 올바르지 않습니다.');
    exit;
}

//IP 검사 20220907부터 검사
// $sql = " select count(*) as cnt FROM T_IP where IP ='".$_SERVER['REMOTE_ADDR']."' ";
// $row2 = sql_fetch($sql);
// $total_count = $row2['cnt'];
// if($total_count == 0 && date("Ymd") >= 20220916) {
//     alert('로그인 정보가 올바르지 않습니다.');
//     exit;
// }

$m_id_sql = sql_escape_string($m_id);
// M_USE_YN: 일부 DB 덤프는 'Y', 다른 곳은 '1' 사용
$user = sql_fetch(" select * from {$p1['t_mgr_table']} where TRIM(M_ID) = TRIM('{$m_id_sql}') AND M_USE_YN IN ('Y', '1') LIMIT 1 ");

$m_uid = playd_row_col($user, 'M_ID');
if (!$m_uid) {
    // 계정 없음·컬럼 불일치 등은 동일 문구로 응답 (기존: 없는데도 '로그인 불가' 문구 사용)
    alert('로그인 정보가 올바르지 않습니다.');
    exit;
}

$m_upw = playd_row_col($user, 'M_PW');
if (!check_password($m_pw, $m_upw)) {

    //비번 틀리면 FAIL_COUNT 업데이트
    sql_query(" UPDATE T_MGR SET PASSWD_FAIL_CNT = IFNULL(PASSWD_FAIL_CNT,0) + 1 WHERE M_ID = '".$m_id_sql."' ");


    //FAIL COUNT 검사
    $sql = " select IFNULL(PASSWD_FAIL_CNT,0) as cnt FROM T_MGR where M_ID = '".$m_id_sql."' ";
    $row2 = sql_fetch($sql);
    $total_count = playd_row_col($row2, 'cnt');
    if ($total_count >= 5) {
//        sql_query(" UPDATE T_MGR SET M_USE_YN = 'N' WHERE M_ID = '".$m_id."' ");
        alert('현재 계정은 로그인이 불가능한 상태입니다. 관리자에게 문의하시기 바랍니다.');
        exit;
    }
    // 비밀번호 5번 틀리면 알럿
    // if($total_count >= 5){
    //     // set_session('passwd_id', $m_id);
    //     alert('현재 계정은 로그인이 불가능한 상태입니다. 관리자에게 문의하시기 바랍니다.');
    //     exit;
    // }

    alert('로그인 정보가 올바르지 않습니다.');
}

// 중지한 아이디인가?
if (playd_row_col($user, 'M_USE_YN') == '0') {
    alert('비활성화된 아이디이므로 접근하실 수 없습니다.');
    goto_url($back_url);
}

// 90일 체크
$sql = " select count(*) as cnt from T_MGR where M_ID = '".$m_id_sql."' AND DATEDIFF(PASSWD_CHG_DT, NOW()) <= 0 ";
$row2 = sql_fetch($sql);
$total_count = playd_row_col($row2, 'cnt');
if($total_count > 0){
    set_session('passwd_id', $m_id);
    alert('마지막 비밀번호 변경일로부터 90일이 경과했습니다. 비밀번호를 변경해주세요.','login_passwd.php');
    exit;
}


// 로그인키 세션 생성
set_session('ss_m_seq', playd_row_col($user, 'M_SEQ'));
set_session('ss_m_id', $m_uid);

//접속 로그 기록
insert_mgr_log('접속','','');

sql_query(" UPDATE T_MGR SET PASSWD_FAIL_CNT = 0 WHERE M_ID = '".$m_id_sql."' ");

// 최고관리자 아니면 권한이 있는 URL로 접근하기
if (playd_row_col($user, 'M_AUTH_TP') != 1) {
    $g_seq = (int) playd_row_col($user, 'G_SEQ');
    $sql = " select G_MENU from T_GROUP_AUTH where G_MENU IN ('a1','a2','a3','b','c1','c2','c3','c4','c5','c6','c7','d1','e1','f1','f2','g') ";
    $sql .= " AND G_SEQ=".$g_seq." AND G_AUTH_READ = 'Y' order by G_MENU ASC limit 1 ";
    $row2 = sql_fetch($sql);
    $menu_row = playd_row_col($row2, 'G_MENU');
    if($menu_row){
        foreach ($menu_status as $key => $value) {
            $str_value = '';
            $grp = explode('|', $value);
            if (sizeof($grp) == 2) {
                $str_value = $grp[0];
            } else {
                $str_value = $value;
            }
            if($menu_row == $str_value) {
                goto_url($key);
                exit;
            }
        }
    }
}

if ($url) {
    // url 체크
    check_url_host($url, '', P1_URL, true);

    $link = urldecode($url);
    // 2003-06-14 추가 (다른 변수들을 넘겨주기 위함)
    if (preg_match('/\?/', $link)) {
        $split = '&amp;';
    } else {
        $split = '?';
    }

    // $_POST 배열변수에서 아래의 이름을 가지지 않은 것만 넘김
    $post_check_keys = ['m_id', 'm_pw', 'x', 'y', 'url'];

    foreach ($_POST as $key => $value) {
        if ($key && !in_array($key, $post_check_keys)) {
            $link .= "$split$key=$value";
            $split = '&amp;';
        }
    }
} else {
    $link = P1_URL;
}

goto_url($link);
?>
