<?php
include_once('./_common.php');

if ($is_guest) {
    // alert("로그인 후 이용하세요.", "./login");
    echo "로그인 후 이용하세요.";
    exit;
}

$store_no = trim($_POST['store_no']);    # 매장번호

$store = get_store($store_no);
if (!$store['store_no']) {
    // alert("존재하지 않는 매장입니다.");
    echo "존재하지 않는 매장입니다.";
    exit;
}

// 로그인중이고 자신의 매장이라면 패스
if (!($member['user_no'] && $store['user_no'] === $member['user_no'])) {
    // alert("권한이 없습니다.");
    echo "권한이 없습니다.";
    exit;
}
    
if ($m == '')
{   
    $video_file = $_FILES['video_file'];       # 동영상 파일
    $user_no    = trim($member['user_no']);    # 사용자번호
    $start_dt   = trim($_POST['start_dt']);    # 유효시작일
    $end_dt     = trim($_POST['end_dt']);      # 유효종료일
    
    // 첨부파일이 있다면
    for($i=0; $i<count($video_file['name']); $i++) {
        $fname  = $video_file['name'][$i];     # 파일 이름 + 확장자
        $ferror = $video_file['error'][$i];    # 파일 에러
        
        // 서버에 설정된 값보다 큰파일을 업로드 한다면
        if ($fname) {
            if ($ferror == 1) {
                alert("file size error");
            } else if ($ferror != 0) {
                alert("file error ".$ferror);
            }
        }

        // 첨부파일이 있다면
        if(is_uploaded_file($video_file['tmp_name'][$i])) {
            $ym = date('ym');

            // print_r($video_file);
            // exit;
            
            $data_sv_dir = '/'.Y1_DATAFILES_DIR.'/store_zzal_video/'.$ym;
            $data_dir    = Y1_DATAFILES_PATH.'/store_zzal_video/'.$ym;

            @mkdir($data_dir, Y1_DIR_PERMISSION, true);
            @chmod($data_dir, Y1_DIR_PERMISSION);

            $fname_tmp   = $video_file['tmp_name'][$i];                 # 파일 tmp 경로 + 이름 + 확장자
            $fname       = $video_file['name'][$i];                     # 파일 이름 + 확장자
            
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

            // 파일 헤더 업는 경우 강제로 처리
            if($video_file['type'][$i] == "application/octet-stream") {
                $fname .= ".mp4";
            }

            // 업로드가 안된다면 에러메세지 출력하고 die
            if(!move_uploaded_file($fname_tmp, $data_dir.'/'.$fname)) {
                // alert("video file upload error");
                echo "video file upload error";
                exit;
            }
            
            $zzal_url_orgin = $data_sv_dir.'/'.$fname;
        }
    }

    // 파일유무
    if ($zzal_url_orgin) {
        $sql_file = " zzal_url_orgin = '{$zzal_url_orgin}', ";
    }
    
    // 짤 영상 신청
    $sql = " insert into {$y1['zzal_appl_table']}
                     set {$sql_file}
                         appl_user_no = '{$user_no}',
                         appl_state = '1',
                         start_dt = '{$start_dt}',
                         end_dt = '{$end_dt}',
                         reg_dttm = '".Y1_TIME_YMDHIS."',
                         store_no = '{$store_no}'  ";
    sql_query($sql);

    // alert("짤 영상 신청이 완료되었습니다.", Y1_PAGE_URL.'/manager_store?tab=intro&tab2=zzal_img'.$qstr);
    echo "짤 영상 신청이 완료되었습니다.";
    exit;
}
else if ($m == 'u')
{
    $appl_no = trim($_POST['appl_no']);     # 신청번호
    $user_no = trim($member['user_no']);    # 사용자번호
    
    $sql = " select *
               from {$y1['zzal_appl_table']}
              where appl_no = '{$appl_no}'
                and appl_state = '1' ";
    $row = sql_fetch($sql);
    if (!$row['appl_no']) {
        // alert("존재하지 않는 정보입니다.");
        echo "존재하지 않는 정보입니다.";
        exit;
    }
    
    // 신청취소
    $sql = " update {$y1['zzal_appl_table']}
                set appl_state = '5',
                    upd_dttm = '".Y1_TIME_YMDHIS."'
              where appl_no = '{$appl_no}' ";
    sql_query($sql);
    
    // alert("신청이 취소되었습니다.", Y1_PAGE_URL.'/manager_store?tab=intro&tab2=zzal_img'.$qstr);
    echo "신청이 취소되었습니다.";
    exit;
}
else
    echo '제대로 된 값이 넘어오지 않았습니다.';
    // alert('제대로 된 값이 넘어오지 않았습니다.');
?>