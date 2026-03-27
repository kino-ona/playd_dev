<?
    include_once($_SERVER["DOCUMENT_ROOT"].'/include/_common.php');

    $s_seq = nvl($_POST['seq'],2);
    $sql = "  select a.* from T_SUPPORT a where a.S_SEQ = ".$s_seq;   
    $totalResult = sql_fetch($sql);

    $postData = array('item' => $totalResult);
    echo json_encode($postData);

?>