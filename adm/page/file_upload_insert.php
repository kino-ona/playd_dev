<?php
include_once('./_common.php');

// check_admin_token();

$m_id      = trim($member['M_ID']);        # 관리자아이디
if(!is_array($_POST[$key])){ $value = trim($value);  $$key  = $value;   }

$img_file    = $_FILES['attach'];           # 리스트 이미지 첨부파일

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


if (is_uploaded_file($img_file['tmp_name'])) {
	$year = date('Y');

	$data_sv_dir = '/'.P1_NSM_DIR.'/news/'.$folder;
	$data_dir    = P1_NSM_PATH.'/news/'.$folder;

	@mkdir($data_dir, 0777, true);
	@chmod($data_dir, 0777);

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
	if(!move_uploaded_file($fname_tmp, $data_dir.'/'.$org_fname)) {
		alert("file upload error");
	}
	@chmod($data_dir.'/'.$org_fname, 0666);
	// 이미지
	$img_url = $data_sv_dir.'/'.$org_fname;
}

$sql_in = " I_DEL_YN = 'Y' ";


$m = trim($_POST['m']);

if ($m == '')
{
	sql_query(" insert into T_IMAGE 
					set I_FILE = '{$img_url}',
					I_FOLDER = '{$folder}',
					I_WRITER = '{$m_id}',
					I_UWRITER = '{$m_id}',
					I_REGDATE = now(),
					I_UREGDATE=now(),
					I_DEL_YN = 'N'


	");

    alert("등록되었습니다.", "/adm/page/file_upload.php?folder=$folder");
}
else if ($m == 'u')
{
    $s_seq = trim($_POST['seq']);
    
    if (!$s_seq) {
		alert('존재하지 않는 정보입니다.');
		exit; 
	}

    sql_query(" update T_IMAGE
                   set I_UWRITER = '{$m_id}',
                       {$sql_in}
                 where I_SEQ = '{$s_seq}' ");
    alert("수정되었습니다.", "/adm/page/file_upload.php?folder=$folder");
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');