<?php
include_once('./_common.php');

$post = get_pdf($_GET['seq']);
$gubun = $_GET['gubun'];

if (!$post['S_SEQ']) {
    $msg .= "게시물이 존재하지 않습니다..";
} else {
	if($gubun =="1"){
		$del_file = P1_PATH2.$post['S_FILE1'];
		$del_sql = " s_file1 = null ";
	}	else if($gubun =="2"){
		$del_file = P1_PATH2.$post['S_FILE2'];
		$del_sql = " s_file2 = null ";
	}

	 if (!file_exists($del_file)) {
		$msg .= '첨부파일이 존재하지 않습니다.'.$del_file;
	} else {
		// 파일삭제
		//@unlink($del_file);
		
		$sql = " update {$p1['t_pds_table']}
					set ".$del_sql."
				  where s_seq = '{$post['S_SEQ']}' ";
		sql_query($sql);
		echo $sql;
		$msg .= "삭제되었습니다.";
	}
}
if ($msg)
    alert($msg, "./pdf_edit.php?seq=".$post['S_SEQ'].$qstr."&amp;m=u");
?>