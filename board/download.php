<?php

	include "../Libs/dbcon.php";
	include "../Libs/conf.php";
	include "../Libs/db_handle.php";
	
	$sql = "select S_FILE2 from T_PDS where S_SEQ='".$_GET['seq']."' ";
	$result = mysql_query($sql);
	$data = mysql_fetch_array($result);

	$target_Dir = $_SERVER['DOCUMENT_ROOT'].$data['S_FILE2'];
	//echo $target_Dir;
	$filesize = filesize($target_Dir);
	//echo "<br>".$filesize;
	if($_GET['type'] == "pdf"){
		$filename = "DownPDF_PLAYD.pdf";
	} else {
		$filenm = explode("/",$data['S_FILE2']);
		//print_r($filenm);
		$filename = $filenm[4];
	}
	
	if(file_exists($target_Dir)){
		
		header("Pragma: public");
		header("Expires: 0");
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=".$filename." ");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".$filesize." ");

		ob_clean();
		flush();
		readfile($target_Dir);
	} else{
	?><script>alert("존재하지 않는 파일입니다.")</script><?
	}
?>