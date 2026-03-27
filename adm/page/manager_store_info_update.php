<?php
include_once('./_common.php');

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

$user_no       = trim($member['user_no']);         # 사용자번호
$store_no      = trim($_POST['store_no']);         # 매장번호
$store_nm      = trim($_POST['store_nm']);         # 사업자명
$store_id      = trim($_POST['store_id']);         # 사업자등록번호
$store_disp_nm = trim($_POST['store_disp_nm']);    # 매장명
$ceo_nm        = trim($_POST['ceo_nm']);           # 대표자성명
$tel           = trim($_POST['tel']);              # 대표전화
$link_url      = trim($_POST['link_url']);         # 가맹점 홈페이지
$store_intro   = trim($_POST['store_intro']);      # 가맹점 소개
$store_cate    = trim($_POST['store_cate']);       # 요식업분류[900]
$parking       = trim($_POST['parking']);          # 주차가능여부
$zipcode       = trim($_POST['zipcode']);          # 우편번호
$addr1         = trim($_POST['addr1']);            # 주소
$addr2         = trim($_POST['addr2']);            # 상세주소
$img_file      = $_FILES['img_file'];              # 사업자등록증 첨부파일

// 주소 -> 좌표 변환
$data = search_address($addr1);
$lat = $data->documents[0]->road_address->y;
$lng = $data->documents[0]->road_address->x;

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
    
    if (is_uploaded_file($img_file['tmp_name'][$i])) {
        $ym = date('ym');
        
        $data_sv_dir = '/'.Y1_DATAFILES_DIR.'/store_license/'.$ym;
        $data_dir    = Y1_DATAFILES_PATH.'/store_license/'.$ym;
       
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

        // 업로드가 안된다면 에러메세지 출력하고 die
        if(!move_uploaded_file($fname_tmp, $data_dir.'/'.$fname)) {
            alert("file upload error");
        }
        
        // 이미지
        $license_img_url = $data_sv_dir.'/'.$fname;
    }
}

// 파일유무
if ($license_img_url) {
    $sql_file = " license_img_url = '{$license_img_url}', ";
}

// 매장 정보 update
$sql = " update {$y1['store_info_table']}
            set {$sql_file}
                store_nm = '{$store_nm}',
                store_id = '{$store_id}',
                zipcode = '{$zipcode}',
                addr1 = '{$addr1}',
                addr2 = '{$addr2}',
                store_disp_nm = '{$store_disp_nm}',
                tel = '{$tel}',
                ceo_nm = '{$ceo_nm}',
                store_cate = '{$store_cate}',
                upd_dttm = '".Y1_TIME_YMDHIS."',
                parking = '{$parking}',
                lat = '{$lat}',
                lng = '{$lng}',
                link_url = '{$link_url}',
                store_intro = '{$store_intro}'
          where store_no = '{$store_no}' ";
sql_query($sql);

// 검색 키워드 update
if(count($_POST['store_recom'] > 0)) {
    // 해당 매장 검색 키워드 전체 삭제
    $sql_del = " delete
                   from {$y1['store_recom_table']}
                  where store_no = '{$store_no}' ";
    sql_query($sql_del);

    // 검색 키워드 insert
    for ($i=0; $i<count($_POST['store_recom']); $i++) {
        $rec_word = $_POST['store_recom'][$i];
        
        $sql_rec = " insert into {$y1['store_recom_table']}
                             set rec_word = '{$rec_word}',
                                 store_no = '{$store_no}',
                                 user_no = '{$user_no}',
                                 reg_dttm = '".Y1_TIME_YMDHIS."' ";
        sql_query($sql_rec);
    }
}

// 요일별 영업시간 update
for($i=0; $i<=6; $i++) {
    $open_time = ${"open_time".$i};    # 오픈시간
    $end_time  = ${"end_time".$i};     # 종료시간
    $day_off   = ${"day_off".$i};      # 휴무여부
    
    $sql_week = " update {$y1['store_weekly_time_table']}
                     set open_time = '{$open_time}',
                         end_time = '{$end_time}',
                         day_off = '{$day_off}',
                         upd_dttm = '".Y1_TIME_YMDHIS."'
                   where store_no = '{$store_no}'
                     and day_code = '{$i}' ";
    sql_query($sql_week);
}

alert("수정되었습니다.", Y1_PAGE_URL.'/manager_store');
?>