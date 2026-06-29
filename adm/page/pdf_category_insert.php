<?php
include_once('./_common.php');

check_admin_token();

$m_id        = trim($member['M_ID']);          # 관리자아이디
$m   = trim($_POST['m']);      
$c_name      = trim($_POST['c_name']);        
$c_use     = trim($_POST['c_use']);        

$sql_in = " c_name = '{$c_name}',
            c_use = '{$c_use}' ";

if ($m == '')
{
    sql_query(" insert into {$p1['t_pds_category_table']} 
                        set {$sql_in} ");
                            
    $s_seq = sql_insert_id();
    
    //alert("등록되었습니다.", "./pl_incident_edit.php?seq=".$s_seq.$qstr."&amp;m=u");
	alert("등록되었습니다.", "./pdf_category_list.php?".$qstr);
}
else if ($m == 'u')
{
	$c_seq     = trim($_POST['seq']);       
    
    //$in = get_pl_incident($s_seq);
    //if (!$in['S_SEQ'])
    //    alert('존재하지 않는 정보입니다.');

    sql_query(" update {$p1['t_pds_category_table']}
                   set {$sql_in}
                 where c_seq = '{$c_seq}' ");
                 
    //alert("수정되었습니다.", "./pl_incident_edit.php?seq=".$s_seq.$qstr."&amp;m=u");
	alert("수정되었습니다.", "./pdf_category_list.php?".$qstr);
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');