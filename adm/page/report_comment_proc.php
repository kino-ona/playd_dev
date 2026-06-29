<?php
include_once('./_common.php');

check_admin_token();

$a_seq    = trim($_POST['seq']);         # 게시물번호
$cmd  = trim($_POST['cmd']);     # 확인여부
$comment  = trim(addslashes($_POST['comment']));     # 확인여부


if ($cmd == 'delete')
{
    sql_query(" delete from T_COMMENT where no = '{$a_seq}' ");
   
    $postData = array('success' => true);
    echo json_encode($postData);
    exit;

} else if ($cmd == 'update')
{
    sql_query(" update T_COMMENT set comment = '{$comment}' where no = '{$a_seq}' ");
   
    $postData = array('success' => true);
    echo json_encode($postData);
    exit;

} else if ($cmd == 'list')
{
    $sql = " select * from T_COMMENT where no = '{$a_seq}' "; 
    $row = sql_fetch($sql);

        
    $postData = array('comment' => $row['comment'], 'success' => true);
    echo json_encode($postData);
    exit;

}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');
?>