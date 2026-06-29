<?php
include_once('./_common.php');

$in = get_pdf($_POST['seq']);

if (!$in['S_SEQ']) {
    $msg .= '정보가 존재하지 않습니다.';
} else {
    pdf_delete($in['S_SEQ']);
    
    alert("삭제되었습니다.", "./pdf_list.php?".$qstr);
}

if ($msg)
    alert($msg);
?>