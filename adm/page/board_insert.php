<?php
include_once('./_common.php');

check_admin_token();

// 권한검사
if ($member['M_AUTH_TP'] != "1") alert('권한이 없습니다.', P1_URL);

$bc_code             = trim($_POST['bc_code']);                # 게시판코드
$bc_name             = trim($_POST['bc_name']);                # 게시판이름
$bc_upload_nm        = trim($_POST['bc_upload_nm']);           # 파일업로드이름
$bc_group            = trim($_POST['bc_group']);               # 게시판그룹
$bc_type             = trim($_POST['bc_type']);                # 게시판종류
$bc_skin             = trim($_POST['bc_skin']);                # 게시판스킨
$bc_editor           = trim($_POST['bc_editor']);              # 게시판에디터
$bc_rows             = trim($_POST['bc_rows']);                # 리스트 row
$bc_pages_rows       = trim($_POST['bc_pages_rows']);          # 페이지 row
$bc_site_use_yn      = trim($_POST['bc_site_use_yn']);         # 사이트 사용여부
$bc_exps_use_yn      = trim($_POST['bc_exps_use_yn']);         # 상단고정 사용여부
$bc_type_use_yn      = trim($_POST['bc_type_use_yn']);         # 구분 사용여부
$bc_noti_use_yn      = trim($_POST['bc_noti_use_yn']);         # 노출 사용여부
$bc_date_use_yn      = trim($_POST['bc_date_use_yn']);         # 년/월 사용여부
$bc_corp_name_use_yn = trim($_POST['bc_corp_name_use_yn']);    # 회사명 사용여부
$bc_name_use_yn      = trim($_POST['bc_name_use_yn']);         # 대표자 사용여부
$bc_share_use_yn     = trim($_POST['bc_share_use_yn']);        # 게시글불러오기 사용여부

$sql_bo = " bc_name = '{$bc_name}',
            bc_upload_nm = '{$bc_upload_nm}',
            bc_group = '{$bc_group}',
            bc_type = '{$bc_type}',
            bc_skin = '{$bc_skin}',
            bc_editor = '{$bc_editor}',
            bc_rows = '{$bc_rows}',
            bc_pages_rows = '{$bc_pages_rows}',
            bc_site_use_yn = '{$bc_site_use_yn}',
            bc_exps_use_yn = '{$bc_exps_use_yn}',
            bc_type_use_yn = '{$bc_type_use_yn}',
            bc_noti_use_yn = '{$bc_noti_use_yn}',
            bc_date_use_yn = '{$bc_date_use_yn}',
            bc_corp_name_use_yn = '{$bc_corp_name_use_yn}',
            bc_name_use_yn = '{$bc_name_use_yn}',
            bc_share_use_yn = '{$bc_share_use_yn}' ";

if ($m == '')
{
    sql_query(" insert into {$p1['t_board_config_table']} 
                        set bc_code = '{$bc_code}',
                            bc_reg_dttm = '".P1_TIME_YMDHIS."',
                            {$sql_bo} ");
                            
    $bc_seq = sql_insert_id();
    
    alert("등록되었습니다.", "./board_edit.php?seq=".$bc_seq.$qstr."&amp;m=u");
}
else if ($m == 'u')
{
    $bc_seq = trim($_POST['seq']);    # 유저번호
    
    $bo = get_board($bc_seq);
    if (!$bo['BC_SEQ'])
        alert('존재하지 않는 정보입니다.');

    sql_query(" update {$p1['t_board_config_table']}
                   set bc_upd_dttm = '".P1_TIME_YMDHIS."',
                       {$sql_bo}
                 where bc_seq = '{$bc_seq}' ");
                 
    alert("수정되었습니다.", "./board_edit.php?seq=".$bc_seq.$qstr."&amp;m=u");
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');