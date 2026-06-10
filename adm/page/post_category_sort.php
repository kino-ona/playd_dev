<?php
include_once('./_common.php');


$data = $_POST['data'];
$jsondata=json_decode($data, true);

foreach ($jsondata as $row) {
	$sql = "update T_POST_CATEGORY set C_SORT = ".$row['nCnt']." where C_SEQ='".$row['c_seq']."'  ";
	$in = sql_fetch($sql);
}
echo "ok"
?>
