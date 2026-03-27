<?php
include_once('./_common.php');
include_once(P1_EDITOR_LIB);

if (!$board['BC_CODE']) {
   alert('존재하지 않는 게시판입니다.', P1_URL);
}

// 목록, 삭제 링크
$list_href = $delete_href = '';
$list_href = P1_PAGE_URL.'/post.php';
$del_href  = P1_PAGE_URL.'/post_del.php';

if ($m == "") {
    $html_title = "등록";

    if ($b_seq) {
        alert('글쓰기에는 \$b_seq 값을 사용하지 않습니다.', P1_PAGE_URL.'/post.php?bc_code='.$bc_code);
    }  
} else if ($m == "u") {
    $html_title = "수정";
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

set_session('ss_bc_code', $bc_code);
set_session('ss_b_seq', $b_seq);

$content = get_text($write['B_CONT'], 0);

/* 성공사례 리스트 */
if ($board['BC_SHARE_USE_YN'] == 1) {
    $sql_sh = " select b_seq, b_title, b_regdate from {$p1['t_board_table']} where b_code = 'nsmext' order by b_regdate desc ";
    $res_sh = sql_query($sql_sh);

    $k = 0;
    $i = 0;
    $share = array();
    while ($row_sh=sql_fetch_array($res_sh)) {
        $share[$i] = $row_sh;
        $share[$i]['title'] = "[".date("Y.m.d", strtotime($row_sh['b_regdate']))."] ".$row_sh['b_title'];
        
        $i++;
        $k++;
    }
    
    /* 성공사례 불러오기 */
    if ($b_share_seq) {
        $sh = get_write($b_share_seq);
        if (!$sh['B_SEQ'])
            alert("자료가 존재하지 않습니다.");
        
        $write['B_TITLE']   = $sh['B_TITLE'];
        $write['B_BRIEF']   = $sh['B_BRIEF'];
        $write['B_EXPS_YN'] = $sh['B_EXPS_YN'];
        $write['B_NOTI_YN'] = $sh['B_NOTI_YN'];
        $content = get_text($sh['B_CONT'], 0);
    }
}

$is_dhtml_editor = false;
if ($board['BC_EDITOR'])
    $is_dhtml_editor = true;

$editor_html = editor_html('b_cont', $content, $is_dhtml_editor);
$editor_js = '';
$editor_js .= get_editor_js('b_cont', $is_dhtml_editor);
$editor_js .= chk_editor_js('b_cont', $is_dhtml_editor);

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