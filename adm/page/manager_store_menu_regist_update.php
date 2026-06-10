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

$user_no  = trim($member['user_no']);    # 사용자번호
$store_no = trim($_POST['store_no']);    # 매장번호
$menu_nm  = trim($_POST['menu_nm']);     # 메뉴이름
$price    = trim($_POST['price']);       # 가격
$disp_ord = trim($_POST['disp_ord']);    # 표시순서
$img_file = $_FILES['img_file'];         # 사진 첨부파일

// 메뉴
$sql_menu = " menu_nm = '{$menu_nm}',
              price = '{$price}',
              use_yn = '1',
              disp_ord = '{$disp_ord}' ";

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
        
        $data_sv_dir = '/'.Y1_DATAFILES_DIR.'/menu/'.$ym;
        $data_dir    = Y1_DATAFILES_PATH.'/menu/'.$ym;
        
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
        
        // $thumb_64_64 = thumbnail($fname, $fname_tmp, $data_dir, "64", "64", true, true);
        
        // if (!$thumb_64_64) {
            // alert("file upload error 64");
        // }
        
        // 업로드가 안된다면 에러메세지 출력하고 die
        if(!move_uploaded_file($fname_tmp, $data_dir.'/'.$fname)) {
            alert("file upload error 64");
        }
        
        // 메뉴 이미지 64x64
        $img_url = $data_sv_dir.'/'.$fname;
    }
}

// 파일유무
if ($img_url) {
    $sql_file = " img_url = '{$img_url}', ";
}

if ($m == '')
{
    // 메뉴정보 insert
    sql_query(" insert into {$y1['menu_table']}
                        set store_no = '{$store_no}',
                            review_cnt = '0',
                            reg_user_no = '{$user_no}',
                            reg_dttm = '".Y1_TIME_YMDHIS."',
                            {$sql_file}
                            {$sql_menu} ");
    
    $menu_no = sql_insert_id();
    
    $msg = "등록";
}
else if ($m == 'u')
{
    $menu_no = trim($_POST['menu_no']);    # 메뉴번호
    
    $menu = get_menu($menu_no);
    if (!$menu['menu_no'])
        alert('존재하지 않는 메뉴입니다.');
    
    // 파일유무
    if ($img_url) {
        // 파일 수정시 기존 파일 삭제
        unlink(Y1_PATH.$menu['img_url']);
    }
  
    // 메뉴정보 update
    sql_query(" update {$y1['menu_table']}
                   set upd_dttm = '".Y1_TIME_YMDHIS."',
                       {$sql_file}
                       {$sql_menu}
                 where menu_no = '{$menu['menu_no']}'
                   and store_no = '{$store_no}' ");

    $msg = "수정";
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');


// 추천단어 update
if(count($_POST['menu_recom'] > 0)) {
    // 해당 메뉴 추천단어(관리자전용) 전체 삭제
    $sql_del = " delete
                   from {$y1['review_recom_table']}
                  where menu_no = '{$menu_no}'
                    and store_yn = '1' ";
    sql_query($sql_del);

    // 검색 키워드 insert
    for ($i=0; $i<count($_POST['menu_recom']); $i++) {
        $review_word = $_POST['menu_recom'][$i];
        
        $sql_rec = " insert into {$y1['review_recom_table']}
                             set menu_no = '{$menu_no}',
                                 review_word = '{$review_word}',
                                 store_yn = '1',
                                 reg_dttm = '".Y1_TIME_YMDHIS."' ";
        sql_query($sql_rec);
    }
}

alert($msg."되었습니다.", Y1_PAGE_URL.'/manager_store?tab=detail'.$qstr);
?>