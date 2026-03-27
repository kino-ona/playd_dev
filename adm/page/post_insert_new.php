<?php
// if  ($_SERVER["REMOTE_ADDR"] == "49.254.187.248" ) {
// sql_escape_string 함수 점검 - 한줄이 8000자 이상이면서 두번째 패턴 조건에서 2개만 빼면 정상 동작하디만 현재 상태에서는 첫번째 조건만으로도 공백만 리턴됨
//	echo "debug:";
//	$str = preg_replace('/(and|or).*(union|select|insert|update|delete|from|where|limit|create|drop).*/i', '', $_POST['b_cont']);
//	print_r($str); 
//	exit; 
// }

include_once('./_common.php');

check_admin_token();

$msg = '';
$act = '';

$m_id        = trim($member['M_ID']);          # 관리자아이디
$b_seq       = trim($_POST['seq']);            # 게시물번호
$b_code      = trim($_POST['b_code']);         # 게시판코드
$b_site      = trim($_POST['b_site']);         # 사이트구분
$b_type      = trim($_POST['b_type']);         # 구분
$b_title     = trim(addslashes($_POST['b_title']));        # 제목
$b_cont      = trim(addslashes($_POST['b_cont']));         # 내용
$b_brief     = trim(addslashes($_POST['b_brief']));        # 요약내용

$b_exps_yn   = trim($_POST['b_exps_yn']);      # 상단고정
$b_noti_yn   = trim($_POST['b_noti_yn']);      # 노출여부

$date_y      = trim($_POST['date_y']);         # 년
$date_m      = trim($_POST['date_m']);         # 월

$b_corp_name = trim($_POST['b_corp_name']);    # 회사명
$b_name      = trim($_POST['b_name']);         # 대표자

$upload      = trim($_POST['upload']);         # 파일업로드이름
$img_file1    = $_FILES['b_attach1'];           # 리스트 이미지 첨부파일
$img_file2    = $_FILES['b_attach2'];
$img_file3   = $_FILES['b_attach3'];
$img_file4   = $_FILES['b_attach4'];
$img_file5   = $_FILES['b_attach5'];
$img_file6   = $_FILES['b_attach6'];

$b_share_seq = trim($_POST['b_share_seq']);    # 게시글 공유
$b_sns_del_yn = trim($_POST['b_sns_del_yn']);  # SNS 수집글 삭제 여부
$b_linkurl	= trim($_POST['b_linkurl']);

$b_send_dt      = trim($_POST['b_send_dt']); 

$b_ext1      = trim($_POST['b_ext1']);  
$b_ext2      = trim($_POST['b_ext2']);  
$b_ext3      = trim($_POST['b_ext3']);  
$b_ext4      = trim($_POST['b_ext4']);  
$b_ext5      = trim($_POST['b_ext5']);  
$b_ext6      = trim($_POST['b_ext6']);
$b_ext7      = trim($_POST['b_ext7']); 
$b_file4   = trim($_POST['b_file4']);

$b_title1      = trim($_POST['b_title1']); 
$b_cont1      = trim($_POST['b_cont1']); 

$filelist1        = trim($_POST['filelist1']);
$filelist2        = trim($_POST['filelist2']);


if ($b_site)      $sql_post .= " b_site = '{$b_site}', ";
if ($b_type)      $sql_post .= " b_type = '{$b_type}', ";
if ($b_exps_yn)   $sql_post .= " b_exps_yn = '{$b_exps_yn}', ";
if ($b_noti_yn)   $sql_post .= " b_noti_yn = '{$b_noti_yn}', ";
if ($date_y)    $sql_post .= " b_year = '{$date_y}', ";
if ($date_m)    $sql_post .= " b_month = '{$date_m}', ";
if ($b_corp_name) $sql_post .= " b_corp_name = '{$b_corp_name}', ";
if ($b_name)      $sql_post .= " b_name = '{$b_name}', ";
if ($b_share_seq) $sql_post .= " b_share_seq = '{$b_share_seq}', ";
if ($b_sns_del_yn) $sql_post .= " b_sns_del_yn = '{$b_sns_del_yn}', ";
if ($b_linkurl) $sql_post .= " b_linkurl = '{$b_linkurl}', ";

if ($b_ext1) $sql_post .= " b_ext1 = '{$b_ext1}', ";
if ($b_ext2) $sql_post .= " b_ext2 = '{$b_ext2}', ";
if ($b_ext3) $sql_post .= " b_ext3 = '{$b_ext3}', ";
if ($b_ext4) $sql_post .= " b_ext4 = '{$b_ext4}', ";
if ($b_ext5) $sql_post .= " b_ext5 = '{$b_ext5}', ";
if ($b_ext6) $sql_post .= " b_ext6 = '{$b_ext6}', ";
if ($b_ext7) $sql_post .= " b_ext7 = '{$b_ext7}', ";
if ($b_file4) $sql_post .= " b_file4 = '{$b_file4}', ";

if ($b_send_dt) $sql_post .= " b_send_dt = '{$b_send_dt}', ";

if ($b_title1) $sql_post .= " b_title1 = '{$b_title1}', ";
if ($b_cont1) $sql_post .= " b_cont1 = '{$b_cont1}', ";

$sql_post .= " b_title = '{$b_title}',
               b_cont = '{$b_cont}',
               b_brief = '{$b_brief}' ";

if ($img_file) {
    $fname  = $img_file['name'];     # 파일 이름 + 확장자
    $ferror = $img_file['error'];    # 파일 에러

    // 서버에 설정된 값보다 큰파일을 업로드 한다면
    if ($fname) {
        if ($ferror == 1) {
            alert("file size error");
        } else if ($ferror != 0) {
            alert("file error ".$ferror);
        }
    }
}

for($i=1;$i<=6;$i++){
    $img_url = '';

    if (is_uploaded_file(${'img_file'.$i}['tmp_name'])) {
        
        $year = date('Y');
        
        $data_sv_dir = '/'.P1_NSM_DIR.'/'.$upload.'/'.$year;
        $data_dir    = P1_NSM_PATH.'/'.$upload.'/'.$year;
        
        @mkdir($data_dir, P1_DIR_PERMISSION, true);
        @chmod($data_dir, P1_DIR_PERMISSION);
        
        $fname_tmp   = ${'img_file'.$i}['tmp_name'];                       # 파일 tmp 경로 + 이름 + 확장자
        $fname       = ${'img_file'.$i}['name'];                           # 파일 이름 + 확장자
        
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

    // 파일유무
    if ($img_url) {
        if($i==1){
            $sql_file .= " b_file1    = '{$img_url}' , ";
        } else if($i==2){
            $sql_file .= " b_file2    = '{$img_url}', ";
        } else if($i==3){
            $sql_file .= " b_file3    = '{$img_url}', ";
        } else if($i==4){
            $sql_file .= " b_file4    = '{$img_url}', ";
        } else if($i==5){
            $sql_file .= " b_file5    = '{$img_url}', ";
        } else if($i==6){
            $sql_file .= " b_file6    = '{$img_url}', ";
        } 
    }
}


if ($m == '')
{   
    // 글 insert
    sql_query(" insert into {$p1['t_board_table']}
                        set b_regdate = '".P1_TIME_YMDHIS."',
                            b_hits = '0',
                            b_writer = '{$m_id}',
                            b_code = '{$b_code}',
                            {$sql_file}
                            {$sql_post} ");
                            
    $b_seq = sql_insert_id();
    

    $msg .= "등록되었습니다.";
    //$act .= "./post.php?bc_code=".$b_code."&b_seq=".$b_seq.$qstr."&amp;m=u";
	$act .= "./post.php?bc_code=".$b_code.$qstr;

    //로그 기록
    if($b_code == 'files'){
        insert_mgr_log('등록','파일 업로드', "/adm/page/post.php?bc_code=files");
    } else {
        insert_mgr_log('등록','게시판 관리 > '.get_code_str($b_code), "/adm/page/post.php?m=u&seq=".$b_seq."&bc_code=".$b_code.$qstr);
    }

}
else if ($m == 'u')
{
    $post = get_write($b_seq);
    if (!$post['B_SEQ'])
        alert("글이 존재하지 않습니다.");
    
    if (get_session('ss_bc_code') != $b_code || get_session('ss_b_seq') != $b_seq) {
        alert('올바른 방법으로 수정하여 주십시오.', P1_PAGE_URL.'/post.php?bc_code='.$b_code);
    }
    
    // 파일유무
    if ($img_url) {
        // 파일 수정시 기존 파일 삭제
        unlink(P1_PATH.$post['B_FILE2']);
    }
    
    // 글 update
    sql_query(" update {$p1['t_board_table']}
                   set b_uregdate = '".P1_TIME_YMDHIS."',
                       b_uwriter = '{$m_id}',
                       {$sql_file}
                       {$sql_post}
                 where b_seq = '{$b_seq}' ");
    $msg .= "수정되었습니다.";
    //$act .= "./post.php?bc_code=".$b_code."&b_seq=".$b_seq.$qstr."&amp;m=u";
	$act .= "./post.php?bc_code=".$b_code.$qstr;

    //로그 기록
    insert_mgr_log('수정','게시판 관리 > '.get_code_str($b_code), "/adm/page/post.php?m=u&seq=".$b_seq."&bc_code=".$b_code.$qstr);


}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');


if($m=='u'){
    sql_query(" delete from T_BOARD_FILES where B_SEQ = ".$b_seq);
}


    if($filelist1){
        $filelist1 = json_decode($filelist1, true);
        $nCnt = 0;
        foreach ($filelist1 as $row) {
            sql_query(" insert into T_BOARD_FILES (FI_NAME,FI_ORG, B_SEQ, FI_SORT, FI_INDEX, FI_REGDATE) values ('".$row['filename']."', '".$row['orgname']."', '".$b_seq."', '".$nCnt."', '1',  now()) ");
            $nCnt++;
        }
    }
    if($filelist2){
        $filelist2 = json_decode($filelist2, true);
        $nCnt = 0;
        foreach ($filelist2 as $row) {
            sql_query(" insert into T_BOARD_FILES (FI_NAME,FI_ORG, B_SEQ, FI_SORT, FI_INDEX, FI_REGDATE) values ('".$row['filename']."', '".$row['orgname']."', '".$b_seq."', '".$nCnt."', '2',  now()) ");
            $nCnt++;
        }
    }
    


// 해시태그 update
if(count($_POST['br_word'] > 0)) {
    // 해당 게시글 해시태그 전체 삭제
    $sql_del = " delete
                   from {$p1['t_board_recom_table']}
                  where br_b_seq = '{$b_seq}' ";
    sql_query($sql_del);

    // 검색 키워드 insert
    for ($i=0; $i<count($_POST['br_word']); $i++) {
        $rec_word = $_POST['br_word'][$i];
        
        $sql_rec = " insert into {$p1['t_board_recom_table']}
                             set br_word = '{$rec_word}',
                                 br_b_seq = '{$b_seq}',
                                 br_reg_dttm = '".P1_TIME_YMDHIS."' ";
        sql_query($sql_rec);
    }
}

if ($msg) {
    alert($msg, $act);
}
?>