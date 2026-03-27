<?php
include_once('./_common.php');

//check_admin_token();

$msg = '';
$act = '';

$img_file    = $_FILES['file0'];
$img_url = '';
$org_name = '';

 if (is_uploaded_file($img_file['tmp_name'])) {
        
        $year = date('Y');
        
        $data_sv_dir = '/'.P1_NSM_DIR.'/'.$year;
        $data_dir    = P1_NSM_PATH.'/'.$year;
        
        @mkdir($data_dir, P1_DIR_PERMISSION, true);
        @chmod($data_dir, P1_DIR_PERMISSION);
        
        $fname_tmp   = $img_file['tmp_name'];                       # 파일 tmp 경로 + 이름 + 확장자
        $fname       = $img_file['name'];                           # 파일 이름 + 확장자
        $org_name    = $img_file['name'];

        $t_file_name = pathinfo($fname_tmp, PATHINFO_FILENAME);     # 파일 tmp 이름
        $t_file_ext  = pathinfo($fname_tmp, PATHINFO_EXTENSION);    # 파일 tmp 확장자

        $r_file_name = pathinfo($fname, PATHINFO_FILENAME);         # 파일 이름
        $r_file_ext  = pathinfo($fname, PATHINFO_EXTENSION);        # 파일 확장자
        
        // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
        $fname = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $fname);
        
        $org_fname = $fname;
        
        // 파일명 shuffle
        $chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
        shuffle($chars_array);
        $shuffle = implode('', $chars_array);
        
        // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다.
        $fname = P1_TIME_YMDHIS_ORG.'_'.substr($shuffle,0,8).'.'.$r_file_ext;
        // $fname = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($fname);

        // 업로드가 안된다면 에러메세지 출력하고 die
        if(!move_uploaded_file($fname_tmp, $data_dir.'/'.$fname)) {
            alert("file upload error");
        }
        
        // 이미지
        $img_url = $data_sv_dir.'/'.$fname;
    }
 
$postData = array('filename' => $img_url, 'orgname' => $org_name);
echo json_encode($postData);

?>