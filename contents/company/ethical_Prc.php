<meta charset="UTF-8">
<?php
    include "../../Libs/dbcon.php";
    include "../../Libs/db_handle.php";

    $func = new db_handle();

    $email = $email[0]."@".$email[1];

    if($upload[name]) {
        $upload = $_FILES[upload];
        $file_name = date("YmdHis").".".array_pop(explode(".", $upload[name]));
        $dir_path = "../../upload/ethical/";
        $file_path = "../../upload/ethical/".$file_name;
        $file_name = "ethical/".$file_name;

        if(!move_uploaded_file($upload[tmp_name], $file_path)) $func->popup_msg_js("파일 업로드 실패\\n관리자에게 문의해주시기 바랍니다.");
    }

	if($name=="") {
		$func->popup_msg_js("이름을 입력해 주세요.");
	}

	if($content=="") {
		$func->popup_msg_js("내용을 입력해 주세요.");
	}

    $sql = "INSERT INTO T_REPORT SET ";

    if($upload[name]) $sql .= "A_FILE1 = '$file_name', ";

    $sql .= "A_PASSWD = '$passwd', ";
    $sql .= "A_NAME = '$name', ";
    $sql .= "A_TEL = '$phone', ";
    $sql .= "A_MAIL = '$email', ";
    $sql .= "A_CONT = '$content', ";
    $sql .= "A_DATE = NOW(), ";
    $sql .= "A_CORP_NAME = '$company'";
    $result = mysql_query($sql);

    if($result) {
        $seq = $func->queryR("SELECT MAX(A_SEQ) FROM T_REPORT");
        $receipt_no = date("Ymd")."-".$seq;

        mysql_query("UPDATE T_REPORT SET A_RECEIPT_NUM = '$receipt_no' WHERE A_SEQ = '$seq'");

        $func->popup_msg_js("제보 신청이 완료되었습니다.\\n접수번호는 ".$receipt_no." 입니다.", "ethical.html");
    } else {
        $func->popup_msg_js("제보 실패\\n관리자에게 문의해주시기 바랍니다.");
    }
?>
