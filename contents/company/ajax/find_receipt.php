<?php
    include "../../../Libs/dbcon.php";

    $receipt_no = $_POST[receipt_no];
    $chk_passwd = $_POST[chk_passwd];

    $sql = "SELECT COUNT(*) FROM T_REPORT WHERE A_RECEIPT_NUM = '$receipt_no' AND A_PASSWD = '$chk_passwd'";
    $result = mysql_query($sql);
    $arr = mysql_fetch_array($result);

    $val = $arr[0];

    if($val != 0) {
        $sql = "SELECT A_SEQ, A_CONT, A_ANSWER FROM T_REPORT WHERE A_RECEIPT_NUM = '$receipt_no'";
        $result = mysql_query($sql);
        $arr = mysql_fetch_array($result);
    }

    echo json_encode(array("val" => $val, "seq" => $arr[A_SEQ], "cont" => nl2br($arr[A_CONT]), "answer" => nl2br($arr[A_ANSWER])));
?>
