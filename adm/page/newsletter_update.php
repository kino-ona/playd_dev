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
    $nl = get_newsletter($_POST['seq'][$k]);
    
    if (!$nl['NS_SEQ']) {
        alert("자료가 존재하지 않습니다.");
    } else {
        // 뉴스레터 신청자 삭제
        newsletter_delete($nl['NS_SEQ']);
    }
}

insert_mgr_log('삭제','신청자 관리 > 뉴스레터', "");



alert("삭제되었습니다.", "./newsletter.php?".$qstr);
?>
