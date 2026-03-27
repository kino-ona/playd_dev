<?php
include_once('./_common.php');

check_admin_token();

$m_id      = trim($member['M_ID']);        # 관리자아이디
$s_field   = trim($_POST['s_field']);      # 모집분야
$s_obj     = trim($_POST['s_obj']);        # 대상
$s_job     = trim($_POST['s_job']);        # 직군
$s_cont    = trim($_POST['s_cont']);       # 내용
$s_st_date = trim($_POST['s_st_date']);    # 모집기간 시작일자
$s_et_date = trim($_POST['s_et_date']);    # 모집기간 종료일자
$s_exps_yn = trim($_POST['s_exps_yn']);    # 상단고정
$s_noti_yn = trim($_POST['s_noti_yn']);    # 노출여부

$sql_in = " s_field = '{$s_field}',
            s_obj = '{$s_obj}',
            s_job = '{$s_job}',
            s_cont = '{$s_cont}',
            s_st_date = '{$s_st_date}',
            s_et_date = '{$s_et_date}',
            s_exps_yn = '{$s_exps_yn}',
            s_noti_yn = '{$s_noti_yn}' ";

if ($m == '')
{
    sql_query(" insert into {$p1['mable_t_support_table']} 
                        set s_date = '".P1_TIME_YMDHIS."',
                            s_writer = '{$m_id}',
                            {$sql_in} ");
                            
    $s_seq = sql_insert_id();
    
    alert("등록되었습니다.", "./m_incident_edit.php?seq=".$s_seq.$qstr."&amp;m=u");
}
else if ($m == 'u')
{
    $s_seq = trim($_POST['seq']);
    
    $in = get_m_incident($s_seq);
    if (!$in['S_SEQ'])
        alert('존재하지 않는 정보입니다.');

    sql_query(" update {$p1['mable_t_support_table']}
                   set s_uwriter = '{$m_id}',
                       {$sql_in}
                 where s_seq = '{$s_seq}' ");
                 
    alert("수정되었습니다.", "./m_incident_edit.php?seq=".$s_seq.$qstr."&amp;m=u");
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');