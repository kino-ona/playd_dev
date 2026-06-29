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

    $in = get_m_incident($_POST['seq'][$k]);

    if (!$in['S_SEQ']) {
        alert("자료가 존재하지 않습니다.");
    } else {
        // 입사지원글 삭제
        m_incident_delete($in['S_SEQ']);
    }
}

alert("삭제되었습니다.", "./m_incident.php?".$qstr);
?>
