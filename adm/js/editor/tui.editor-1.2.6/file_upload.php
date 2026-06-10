<?php
//--------------------------------------------
// 파일명 치환
//--------------------------------------------
function replace_filename($name) {
    @session_start();
    $ss_id = session_id();
    $usec = get_microtime();
    $file_path = pathinfo($name);
    $ext = $file_path['extension'];
    $return_filename = sha1($ss_id.$_SERVER['REMOTE_ADDR'].$usec); 
    if( $ext )
        $return_filename .= '.'.$ext;

    return $return_filename;
}

//--------------------------------------------
// 마이크로 타임을 얻어 계산 형식으로 만듦
//--------------------------------------------
function get_microtime() {
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

$ym = date('ym');

$data_dir = $_SERVER['DOCUMENT_ROOT'].'/dataFiles/editor/'.$ym.'/';
$data_url = 'http://'.$_SERVER['HTTP_HOST'].'/dataFiles/editor/'.$ym.'/';

@mkdir($data_dir, 0707);
@chmod($data_dir, 0707);

$fname = $_POST['fname']; # 파일 이름
$fdata = $_POST['data'];  # 파일 데이터

// data:image/jpeg;base64, /9j/4AAQS~~~~~~~~
// 쉼표 기준으로 뒤쪽 데이터만 끊어줌
$data = substr($fdata, strpos($fdata, ",") + 1);
$decodeData = base64_decode($data);

// 파일명 shuffle
$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
shuffle($chars_array);
$shuffle = implode('', $chars_array);

// 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다.
$fname = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($fname);

// 파일 쓰기.. 경로는 스마트에디터랑 동일하게
$fp = fopen($data_dir.$fname, 'wb');
fwrite($fp, $decodeData);
fclose($fp);

// 파일 경로 callback 던져줌
echo $data_url.$fname;
?>