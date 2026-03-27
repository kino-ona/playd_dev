<?php
    include "../../../Libs/dbcon.php";

    $report_no = $_POST[report_no];
    $comment = $_POST[comment];

    $sql = "INSERT INTO T_COMMENT SET ";
    $sql .= "report_no = '$report_no', ";
    $sql .= "writer = (SELECT A_NAME FROM T_REPORT WHERE A_SEQ = '$report_no'), ";
    $sql .= "comment = '$comment', ";
    $sql .= "wdate = NOW(), ";
    $sql .= "ip = '$_SERVER[REMOTE_ADDR]'";
    $result = mysql_query($sql);

    if($result) echo json_encode(1);
    else echo json_encode(0);
?>
