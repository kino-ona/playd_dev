<?php
include_once('./_common.php');
include_once(Y1_LIB_PATH.'/thumbnail.lib.php');

check_token();

if ($is_guest) {
    alert("로그인 후 이용하세요.", "./login");
}

$sql = " select count(*) as cnt
           from {$y1['store_info_table']}
          where user_no = '{$member['user_no']}'
            and service_state in (1, 2) ";
$row = sql_fetch($sql);
if ($row['cnt'] > 0) {
    alert("가맹점회원 심의 중입니다.");
}

$user_no  = trim($member['user_no']);    # 사용자번호
$store_no = trim($_POST['store_no']);    # 매장번호
$title    = trim($_POST['title']);       # 쿠폰타이틀
$start_dt = trim($_POST['start_dt']);    # 유효시작일
$end_dt   = trim($_POST['end_dt']);      # 유효종료일
$img_file = $_FILES['img_file'];         # 사진 첨부파일

// 날짜 역순 체크
if ($start_dt >= $end_dt)
    alert("적용기간이 역순입니다.");

// 매장 정보
$store = get_store($store_no);

// 로그인중이고 자신의 매장이라면 패스
if (!($member['user_no'] && $store['user_no'] === $member['user_no']))
    alert('권한이 없습니다.');

// 동일시점 중복체크
// $sql_cu = " select count(*) as cnt
              // from {$y1['cupon_appl_table']}
             // where store_no = '{$store['store_no']}'
               // and appl_state not in (3,4)
               // and ( '{$start_dt}' between start_dt and end_dt or '{$end_dt}' between start_dt and end_dt ) ";
// $row_cu = sql_fetch($sql_cu);
// if ($row_cu['cnt'] > 0)
    // alert("동일한 시점에 이미 신청한 쿠폰이 있습니다.");

// 첨부파일이 있다면
for($i=0; $i<count($img_file['name']); $i++) {
    $fname  = $img_file['name'][$i];     # 파일 이름 + 확장자
    $ferror = $img_file['error'][$i];    # 파일 에러
    
    // 서버에 설정된 값보다 큰파일을 업로드 한다면
    if ($fname) {
        if ($ferror == 1) {
            alert("file size error");
        } else if ($ferror != 0) {
            alert("file error ".$ferror);
        }
    }
    
    // 첨부파일이 있다면
    if(is_uploaded_file($img_file['tmp_name'][$i])) {
        $ym = date('ym');
        
        $data_sv_dir = '/'.Y1_DATAFILES_DIR.'/coupon/'.$ym;
        $data_dir    = Y1_DATAFILES_PATH.'/coupon/'.$ym;

        @mkdir($data_dir, Y1_DIR_PERMISSION, true);
        @chmod($data_dir, Y1_DIR_PERMISSION);

        $fname_tmp   = $img_file['tmp_name'][$i];                   # 파일 tmp 경로 + 이름 + 확장자
        $fname       = $img_file['name'][$i];                       # 파일 이름 + 확장자
        
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

        // $thumb_64x64 = thumbnail($fname, $fname_tmp, $data_dir, "64", "64", true, true);
        
        // if (!$thumb_64x64) {
            // alert("file upload error 64");
        // }

        // 썸네일 이미지 64x64
        // $img_url = $data_sv_dir.'/'.$thumb_64x64;
        
        // 업로드가 안된다면 에러메세지 출력하고 die
        if(!move_uploaded_file($fname_tmp, $data_dir.'/'.$fname)) {
            alert("file upload error 64");
        }
        
        // 이미지 64x64
        $img_url = $data_sv_dir.'/'.$fname;
    }
}

// 파일유무
if ($img_url) {
    $sql_file = " img_url = '{$img_url}', ";
}

// 전체 미사용 처리
$sql_up = " update {$y1['cupon_appl_table']}
               set appl_state = '3',
                   upd_dttm = '".Y1_TIME_YMDHIS."' ";
sql_query($sql_up);

$sql = " insert into {$y1['cupon_appl_table']}
                 set {$sql_file}
                     appl_user_no = '{$user_no}',
                     store_no = '{$store_no}',
                     title = '{$title}',
                     appl_state = '2',
                     start_dt = '{$start_dt}',
                     end_dt = '{$end_dt}',
                     reg_dttm = '".Y1_TIME_YMDHIS."'  ";
sql_query($sql);

alert("등록되었습니다.", Y1_PAGE_URL.'/manager_coupon?'.$qstr);
?>