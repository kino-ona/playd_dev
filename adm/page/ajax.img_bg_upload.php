<?php
include_once('./_common.php');
include_once(Y1_LIB_PATH.'/thumbnail.lib.php');

if ($is_guest) {
    echo "login";
    exit;
}

$img_file = $_FILES['img_file'];         # 이미지파일
$store_no = trim($_POST['store_no']);    # 매장번호
$user_no  = trim($member['user_no']);    # 사용자번호

$store = get_store($store_no);
if (!$store['store_no']) {
    echo "존재하지 않는 매장입니다.";
    exit;
}

// 로그인중이고 자신의 매장이라면 패스
if (!($member['user_no'] && $store['user_no'] === $member['user_no'])) {
    echo "권한이 없습니다.";
    exit;
}

if ($img_file) {
    $fname  = $img_file['name'];     # 파일 이름 + 확장자
    $ferror = $img_file['error'];    # 파일 에러

    // 서버에 설정된 값보다 큰파일을 업로드 한다면
    if ($fname) {
        if ($ferror == 1) {
            echo "file size error";
            exit;
        } else if ($ferror != 0) {
            echo "file error ".$ferror;
            exit;
        }
    }
}
    
if (is_uploaded_file($img_file['tmp_name'])) {
    $ym = date('ym');
    
    $data_sv_dir = '/'.Y1_DATAFILES_DIR.'/store_bg/'.$ym;
    $data_dir    = Y1_DATAFILES_PATH.'/store_bg/'.$ym;
   
    @mkdir($data_dir, Y1_DIR_PERMISSION, true);
    @chmod($data_dir, Y1_DIR_PERMISSION);

    $fname_tmp   = $img_file['tmp_name'];                       # 파일 tmp 경로 + 이름 + 확장자
    $fname       = $img_file['name'];                           # 파일 이름 + 확장자
    
    $t_file_name = pathinfo($fname_tmp, PATHINFO_FILENAME);     # 파일 tmp 이름
    $t_file_ext  = pathinfo($fname_tmp, PATHINFO_EXTENSION);    # 파일 tmp 확장자

    $r_file_name = pathinfo($fname, PATHINFO_FILENAME);         # 파일 이름
    $r_file_ext  = pathinfo($fname, PATHINFO_EXTENSION);        # 파일 확장자
    
    // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
    $fname = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $fname);
    
    // 파일명 shuffle
    $chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
    shuffle($chars_array);
    $shuffle = implode('', $chars_array);
    
    // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다.
    $fname = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($fname);
    
    // $thumb_360x190 = thumbnail($fname, $fname_tmp, $data_dir, "360", "190", true, true);
    
    // if (!$thumb_360x190) {
        // echo "file upload error 360";
        // exit;
    // }

    // 썸네일 이미지 360x190
    // $img_url = $data_sv_dir.'/'.$thumb_360x190;
    
    // 업로드가 안된다면 에러메세지 출력하고 die
    if(!move_uploaded_file($fname_tmp, $data_dir.'/'.$fname)) {
        // alert("file upload error 360");
        echo "file upload error 360";
        exit;
    }

    // 이미지 comp
    $img_url = $data_sv_dir.'/'.$fname;
}

// 파일유무
if ($img_url) {
    $sql_file = " bg_img_url = '{$img_url}', ";
    
    // 파일 수정시 기존 파일 삭제
    unlink(Y1_PATH.$store['bg_img_url']);
}

$sql = " update {$y1['store_info_table']}
            set {$sql_file}
                upd_dttm = '".Y1_TIME_YMDHIS."'
          where store_no = '{$store_no}' ";
sql_query($sql);

echo "<src>".$img_url."</src>";
?>