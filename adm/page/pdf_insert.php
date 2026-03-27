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
$sql_file ='';

$m_id        = trim($member['M_ID']);          # 관리자아이디
$s_seq       = trim($_POST['seq']);            # 게시물번호
$s_type      = trim($_POST['s_type']);         # 구분
$s_title     = trim(addslashes($_POST['s_title']));        # 제목
$s_cont      = trim(addslashes($_POST['s_cont']));         # 내용
$s_exps_yn   = trim($_POST['s_exps_yn']);      # 상단고정
$s_noti_yn   = trim($_POST['s_noti_yn']);      # 노출여부
$s_brief     = trim($_POST['s_brief']);        # 요약내용

$upload      = trim($_POST['upload']);         # 파일업로드이름
$img_file    = $_FILES['s_attach2'];           # 리스트 이미지 첨부파일
$pdf_file    = $_FILES['s_attach3'];           # 리스트 이미지 첨부파일

// param
$sql_post .= " s_type = '{$s_type}',
				s_title = '{$s_title}',
               s_cont = '{$s_cont}',
			   s_brief = '{$s_brief}',
			   s_noti_yn = '{$s_noti_yn}',
			   s_exps_yn = '{$s_exps_yn}' 
			   ";

//리스트 이미지
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

//pds 첨부파일
if ($pdf_file) {
    $pname  = $pdf_file['name'];     # 파일 이름 + 확장자
    $perror = $pdf_file['error'];    # 파일 에러

    // 서버에 설정된 값보다 큰파일을 업로드 한다면
    if ($pname) {
        if ($perror == 1) {
            alert("file size error");
        } else if ($perror != 0) {
            alert("file error ".$perror);
        }
    }
}
   
//리스트 이미지	
if (is_uploaded_file($img_file['tmp_name'])) {
    $year = date('Y').date('m');
    
    $data_sv_dir = '/'.P1_NSM_DIR.'/'.$upload.'/'.$year;
    $data_dir    = P1_NSM_PATH.'/'.$upload.'/'.$year;
    
    @mkdir($data_dir, P1_DIR_PERMISSION, true);
    @chmod($data_dir, P1_DIR_PERMISSION);
    
    $fname_tmp   = $img_file['tmp_name'];                       # 파일 tmp 경로 + 이름 + 확장자
    $fname       = $img_file['name'];                           # 파일 이름 + 확장자
    
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
    $sql_file .= " s_file1    = '{$img_url}', ";
}

//pdf첨부파일
if (is_uploaded_file($pdf_file['tmp_name'])) {
    $year = date('Y').date('m');
    
    $data_sv_dir = '/'.P1_NSM_DIR.'/'.$upload.'/'.$year;
    $data_dir    = P1_NSM_PATH.'/'.$upload.'/'.$year;
    
    @mkdir($data_dir, P1_DIR_PERMISSION, true);
    @chmod($data_dir, P1_DIR_PERMISSION);
    
    $fname_tmp   = $pdf_file['tmp_name'];                       # 파일 tmp 경로 + 이름 + 확장자
    $fname       = $pdf_file['name'];                           # 파일 이름 + 확장자
    
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
    $pdf_url = $data_sv_dir.'/'.$fname;
}
if ($pdf_url) {
    $sql_file .= " s_file2    = '{$pdf_url}', ";
	$sql_file .= " s_file2_nm    = '{$pdf_file['name']}', ";
}

if ($m == '')
{   
    // 글 insert
	sql_query(" insert into {$p1['t_pds_table']}
                        set s_hits = '0',
							  s_date = '".P1_TIME_YMDHIS."',                            
                              s_writer = '{$m_id}',
                             {$sql_file}
                             {$sql_post} ");
                            
    $b_seq = sql_insert_id();
    
    $msg .= "등록되었습니다.";
    //$act .= "./post.php?bc_code=".$b_code."&b_seq=".$b_seq.$qstr."&amp;m=u";
	$act .= "./pdf_list.php?bc_code=".$b_code.$qstr;

}
else if ($m == 'u')
{
    $post = get_pdf($seq);
    if (!$post['S_SEQ'])
        alert("글이 존재하지 않습니다.");
        
    // 파일유무
    if ($img_url) {
        // 파일 수정시 기존 파일 삭제
        unlink(P1_PATH.$post['S_FILE1']);
    }
	if ($pdf_url) {
        // 파일 수정시 기존 파일 삭제
        unlink(P1_PATH.$post['S_FILE2']);
    }

    // 글 update
    sql_query(" update {$p1['t_pds_table']}
                   set s_udate = '".P1_TIME_YMDHIS."',
                         s_uwriter = '{$m_id}',
                        {$sql_file}
                        {$sql_post}
                 where s_seq = '{$s_seq}' ");
    $msg .= "수정되었습니다.";
	$act .= "./pdf_list.php?".$qstr;

}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');


if ($msg) {
    alert($msg, $act);
}
?>