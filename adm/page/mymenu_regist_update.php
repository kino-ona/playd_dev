<?php
include_once('./_common.php');
include_once(Y1_LIB_PATH."/register.lib.php");
include_once(Y1_LIB_PATH.'/thumbnail.lib.php');

check_token();

if ($is_guest) {
    alert("로그인 후 이용하세요.", "./login");
}

$user_no   = trim($member['user_no']);     # 사용자번호
$login_key = trim($_POST['login_key']);    # 로그인키
$nickname  = trim($_POST['nickname']);     # 닉네임
$age_group = trim($_POST['age_group']);    # 연령대[110]
$job       = trim($_POST['job']);          # 직업[120]
$job_name  = trim($_POST['job_name']);     # 직업명
$food      = trim($_POST['food']);         # 선호음식[130]
$food_name = trim($_POST['food_name']);    # 선호음식명
$area      = trim($_POST['area']);         # 자주가는지역[140]
$area_name = trim($_POST['area_name']);    # 자주가는지역명
$img_file  = $_FILES['img_file'];          # 사진 첨부파일

if ($msg = empty_nickname($nickname))                alert($msg, "", true, true);
if ($msg = valid_nickname($nickname))                alert($msg, "", true, true);
if ($msg = count_nickname($nickname))                alert($msg, "", true, true);
if ($msg = exist_nickname($nickname, $login_key))    alert($msg, "", true, true);

// 닉네임에 utf-8 이외의 문자가 포함됐다면 오류
// 서버환경에 따라 정상적으로 체크되지 않을 수 있음.
$tmp_nickname = iconv('UTF-8', 'UTF-8//IGNORE', $nickname);
if($tmp_nickname != $nickname) {
    alert('닉네임을 올바르게 입력해 주십시오.');
}

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
    
    if (is_uploaded_file($img_file['tmp_name'][$i])) {
        $ym = date('ym');
        
        $data_sv_dir = '/'.Y1_DATAFILES_DIR.'/profile/'.$ym;
        $data_dir    = Y1_DATAFILES_PATH.'/profile/'.$ym;
       
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
        
        // $thumb_96_96 = thumbnail($fname, $fname_tmp, $data_dir, "96", "96", true, true);
        
        // if (!$thumb_96_96) {
            // alert("file upload error 96");
        // }

        // 썸네일 이미지 96x96
        // $thumbnail_url = $data_sv_dir.'/'.$thumb_96_96;
        
        // 업로드가 안된다면 에러메세지 출력하고 die
        if(!move_uploaded_file($fname_tmp, $data_dir.'/'.$fname)) {
            alert("file upload error 96");
        }
        
        // 이미지 96x96
        $thumbnail_url = $data_sv_dir.'/'.$fname;
    }
}

// 파일유무
if ($thumbnail_url) {
    $sql_file = " thumbnail_url = '{$thumbnail_url}', ";
    
    // 파일 수정시 기존 파일 삭제
    unlink(Y1_PATH.$member['thumbnail_url']);
}

// 회원 정보 update
$sql = " update {$y1['user_master_table']}
            set {$sql_file}
                nickname = '{$nickname}',
                upd_dttm = '".Y1_TIME_YMDHIS."',
                age_group = '{$age_group}',
                job = '{$job}',
                job_name = '{$job_name}'
          where user_no = '{$user_no}' ";
sql_query($sql);

// 선호음식 : 복수선택 처리
if ($food) {
    $sql_del = " delete
                   from {$y1['user_food_table']}
                  where user_no = '{$user_no}' ";
    sql_query($sql_del);
    
    $food_arr = explode(",", $food);
    foreach($food_arr as $food_cd) {
        if ($food_cd == "99") {
            $food_nm = " ,food_nm = '{$food_name}' ";
        }

        $sql = " insert into {$y1['user_food_table']}
                         set user_no = '{$user_no}',
                             food_cd = '{$food_cd}'
                             {$food_nm} ";
        sql_query($sql);
    }
}

// 자주가는지역 : 복수선택 처리
if ($area) {
    $sql_del = " delete
                   from {$y1['user_region_table']}
                  where user_no = '{$user_no}' ";
    sql_query($sql_del);
    
    $area_arr = explode(",", $area);
    foreach($area_arr as $region_cd) {
        if ($region_cd == "99") {
            $region_nm = " ,region_nm = '{$area_name}' ";
        }
        
        $sql = " insert into {$y1['user_region_table']}
                         set user_no = '{$user_no}',
                             region_cd = '{$region_cd}'
                             {$region_nm} ";
        sql_query($sql);
    }
}

alert("수정되었습니다.", Y1_PAGE_URL.'/mymenu_regist');
?>