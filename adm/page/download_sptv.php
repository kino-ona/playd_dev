<?php
include_once('./_common.php');

// clean the output buffer
ob_end_clean();

$seq = (int)$seq;

// 쿠키에 저장된 ID값과 넘어온 ID값을 비교하여 같지 않을 경우 오류 발생
// 다른곳에서 링크 거는것을 방지하기 위한 코드
if (!get_session('ss_inc_view_'.$seq))
    alert('잘못된 접근입니다.');

if ($mode == "p")  $sql_common = " {$p1['t_support_view_table']} ";
if ($mode == "m")  $sql_common = " {$p1['mable_t_support_view_table']} ";
if ($mode == "pl") $sql_common = " {$p1['t_pool_view_table']} ";

$sql = " select sv_sysfile1 from {$sql_common} where sv_seq = '$seq' ";
$file = sql_fetch($sql);
if (!$file['sv_sysfile1'])
    alert('파일 정보가 존재하지 않습니다.');

$original = urlencode($file['sv_sysfile1']);
$file_name = basename($file['sv_sysfile1']);

$filepath = '../../'.$file['sv_sysfile1'];
$filepath = addslashes($filepath);
if (!is_file($filepath) || !file_exists($filepath))
    alert('파일이 존재하지 않습니다.');

if(preg_match("/msie/i", $_SERVER['HTTP_USER_AGENT']) && preg_match("/5\.5/", $_SERVER['HTTP_USER_AGENT'])) {
    header("content-type: doesn/matter");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"".$file_name."\"");
    // header("content-disposition: attachment; filename=\"$original\"");
    header("content-transfer-encoding: binary");
} else if (preg_match("/Firefox/i", $_SERVER['HTTP_USER_AGENT'])){
    header("content-type: file/unknown");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"".$file_name."\"");
    header("content-description: php generated data");
} else {
    header("content-type: file/unknown");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"".$file_name."\"");
    // header("content-disposition: attachment; filename=\"$original\"");
    header("content-description: php generated data");
}
header("pragma: no-cache");
header("expires: 0");
flush();

$fp = fopen($filepath, 'rb');

$download_rate = 10;

while(!feof($fp)) {
    //echo fread($fp, 100*1024);
    /*
    echo fread($fp, 100*1024);
    flush();
    */

    print fread($fp, round($download_rate * 1024));
    flush();
    usleep(1000);
}
fclose ($fp);
flush();
?>