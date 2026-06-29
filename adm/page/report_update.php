<?php
include_once('./_common.php');

if (!count($_POST['chk'])) {
    alert("선택삭제 하실 항목을 하나 이상 체크하세요.");
}

check_admin_token();

for ($i=0; $i<count($_POST['chk']); $i++)
{
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];

    $re = get_report($_POST['seq'][$k]);

    if (!$re['A_SEQ']) {
        alert("자료가 존재하지 않습니다.");
    } else {
        // 윤리경영제보 삭제
        report_delete($re['A_SEQ']);
    }
}

 //로그 기록
 insert_mgr_log('삭제','문의 관리 > 윤리경영제보', "");


alert("삭제되었습니다.", "./report.php?".$qstr);
?>
