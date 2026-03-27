<meta charset="UTF-8">
<?php
    include "../../Libs/dbcon.php";
    include "../../Libs/db_handle.php";

    $func = new db_handle();
	$url = "prc_complete.html";
	$a_type = "";

	if($product!="") {
		foreach($product as $key=>$val) {
			$a_type.="{$val},";
		}
		$a_type = substr($a_type, 0, -1);
	}

    if($homepage && strpos($homepage, "http://") === false) $homepage = "http://".$homepage;

    if($code == "nsmasso" && $upload[name]) {
        $upload = $_FILES[upload];
        $file_name = date("YmdHis").".".array_pop(explode(".", $upload[name]));
        $dir_path = "../../upload/partnership/";
        $file_path = "../../upload/partnership/".$file_name;
        $file_name = "partnership/".$file_name;

        if(!move_uploaded_file($upload[tmp_name], $file_path)) $func->popup_msg_js("파일 업로드 실패\\n관리자에게 문의해주시기 바랍니다.");
    }

    $sql = "INSERT INTO T_ASK SET ";
    $sql .= "A_CODE = '$code', ";
    $sql .= "A_NAME = '$name', ";
    $sql .= "A_TEL = '$phone', ";
    $sql .= "A_MAIL = '$email', ";
    $sql .= "A_URL = '$homepage', ";
    $sql .= "A_TYPE = '$a_type', ";

	$sql .= "A_CONT = '$content', ";

    if($code == "nsmasso") {
        $sql .= "A_FILE1 = '$file_name', ";
        $sql .= "A_SYSFILE1 = '$file_name', ";
        $sql .= "A_CORP_NAME = '$company', ";

        //$url = "partnership.html";
    } else {
        $sql .= "A_ID = '$ad_id', ";

        if($place) {
			if($_GET[return_url]=="1") {
				//$url = "../../eng/contents/main/";
			} else {
				//$url = "../main/";
			}

		} else {
			//$url = "ad_inquiry.html";

		}
    }

	if($_POST[spam_code]!="") {
		$sql .= "route_check = 'ftail', ";
	}
    $sql .= "A_DATE = NOW()";
    $result = mysql_query($sql);
    if($result){
		
		if($_GET[return_url]=="1") {
			$func->popup_msg_js("Your inquiry has been completed.", $url);
		} else { 
			$func->popup_msg_js("문의가 완료되었습니다.", $url);
		}
	}else{ $func->popup_msg_js("문의 실패\\n관리자에게 문의해주시기 바랍니다.");}
?>
