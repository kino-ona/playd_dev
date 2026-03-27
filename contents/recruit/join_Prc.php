<meta charset="UTF-8">
<?php
    include "../../Libs/dbcon.php";
    include "../../Libs/db_handle.php";

    $func = new db_handle();


	if(!$seq) {
		$func->popup_msg_js("지원 오류\\n관리자에게 문의해주시기 바랍니다.");
	} 

	if ($type=='pool') $folder='playd_pool_application';
	else $folder='playd_application';

    $resume = $_FILES[resume];
    $resume_name = date("YmdHis").".".array_pop(explode(".", $resume[name]));
    $dir_path = "../../../../upfile/$folder/".$seq."/";
    $file_path = "../../../../upfile/$folder/".$seq."/".$resume_name;
    $email = trim($email[0])."@".trim($email[1]);
    $birth = $birth[0]."-".$birth[1]."-".$birth[2];
    $resume_name = "../upfile/$folder/".$seq."/".$resume_name;

    if($type == "pool") $table_nm = "T_POOL_VIEW";
    else $table_nm = "T_SUPPORT_VIEW";

    if(!is_dir($dir_path)) {
        mkdir($dir_path, 0777);
        chmod($dir_path, 0777);
    }

    if(move_uploaded_file($resume[tmp_name], $file_path)) {
        $sql = "INSERT INTO $table_nm SET ";
        $sql .= "SV_CODE = '$seq', ";
        $sql .= "SV_NAME = '$name', ";
        $sql .= "SV_STATE = '$people', ";
        $sql .= "SV_MAIL = '$email', ";
        $sql .= "SV_FILE1 = '$resume_name', ";
        $sql .= "SV_DATE = NOW(), ";
        $sql .= "SV_SYSFILE1 = '$resume_name', ";
        $sql .= "SV_BIRTH = '$birth'";
        $result = mysql_query($sql);

        if($result) {
            $func->popup_msg_js("지원이 완료되었습니다.", "join.html");
        } else {
            @unlink($file_path);
            $func->popup_msg_js("지원 실패\\n관리자에게 문의해주시기 바랍니다.");
        }
    } else {
        $func->popup_msg_js("파일 업로드 실패\\n관리자에게 문의해주시기 바랍니다.");
    }
?>
