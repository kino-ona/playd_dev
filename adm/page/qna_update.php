<?php
include_once('./_common.php');

// 권한검사
// if (!auth_check_no("a", "1")) alert('권한이 없습니다.', '/');

if (!count($_POST['chk'])) {
    alert("선택삭제 하실 항목을 하나 이상 체크하세요.");
}

check_admin_token();

for ($i=0; $i<count($_POST['chk']); $i++)
{
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];

    $qna = get_qna($_POST['seq'][$k]);

    if (!$qna['A_SEQ']) {
        alert("게시물이 존재하지 않습니다.");
    } else {
        // 게시물 삭제
        qna_delete($qna['A_SEQ']);
    }
}


//로그 기록
insert_mgr_log('삭제','문의 관리 > '.get_code_str($bc_code),'');


alert("삭제되었습니다.", "./qna.php?bc_code=".$bc_code.$qstr);
?>
