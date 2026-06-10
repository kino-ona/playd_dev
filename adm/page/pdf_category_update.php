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

	$sql = "select * from {$p1['t_pds_category_table']} where C_SEQ='".$_POST['seq'][$k]."' ";
	$in = sql_fetch($sql);

    if (!$in['C_SEQ']) {
        alert("자료가 존재하지 않습니다.");
    } else {
        // 인재풀글 삭제
        $sql = "delete from {$p1['t_pds_category_table']} where C_SEQ='".$_POST['seq'][$k]."' ";
		//echo $sql;
		sql_query($sql);
    }
}
alert("삭제되었습니다.", "./pdf_category_list.php?".$qstr);
?>
