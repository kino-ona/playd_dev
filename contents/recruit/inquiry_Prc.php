<meta charset="UTF-8">
<?php
    include "../../Libs/dbcon.php";
    include "../../Libs/db_handle.php";

    $func = new db_handle();

    $email = trim($email[0])."@".trim($email[1]);

    $sql = "INSERT INTO T_ASK (A_CODE, A_NAME, A_MAIL, A_TITLE, A_CONT, A_DATE) VALUES ('$code', '$name', '$email', '$title', '$content', NOW())";
    $result = mysql_query($sql);

    if($result) $func->popup_msg_js("문의가 완료되었습니다.", "inquiry.html");
    else $func->popup_msg_js("문의 실패\\n관리자에게 문의해주시기 바랍니다.");
?>
