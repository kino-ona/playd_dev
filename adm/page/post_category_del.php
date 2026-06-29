<?php
include_once('./_common.php');

if(!$_POST['seq']){
    exit;
}

$sql = "select * from  T_POST_CATEGORY  where C_SEQ='".$_POST['seq']."' ";
$in = sql_fetch($sql);

if (!$in['C_SEQ']) {
    alert("자료가 존재하지 않습니다.");
} else {
    
    $sql2 = " select count(*) as cnt from T_BOARD where B_CODE='playdportfolio' and  B_TYPE = '".$in['C_NAME']."' ";
    $row2 = sql_fetch($sql2);
    $total_count2 = $row2['cnt'];
    if($total_count2 > 0) {
        alert("사용하고 있는 카테고리는 삭제가 불가능 합니다.");
        exit;
    }

    // 인재풀글 삭제
    $sql = "delete from  T_POST_CATEGORY  where C_SEQ='".$_POST['seq']."' ";
	sql_query($sql);
}
alert("삭제되었습니다.", "./post_category_list.php?".$qstr);
?>
