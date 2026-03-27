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
    sql_query(" insert into  T_POST_CATEGORY  
                        set {$sql_in} ");
                            
    $s_seq = sql_insert_id();
    
    //alert("등록되었습니다.", "./pl_incident_edit.php?seq=".$s_seq.$qstr."&amp;m=u");
	alert("등록되었습니다.", "./post_category_list.php?".$qstr);
}
else if ($m == 'u')
{
	$c_seq     = trim($_POST['seq']);       
    
    //$in = get_pl_incident($s_seq);
    //if (!$in['S_SEQ'])
    //    alert('존재하지 않는 정보입니다.');

    $sql = "select * from  T_POST_CATEGORY  where C_SEQ='{$c_seq}' ";
	$in = sql_fetch($sql);
    $oldName = $in['C_NAME'];



    sql_query(" update  T_POST_CATEGORY 
                   set {$sql_in}
                 where c_seq = '{$c_seq}' ");

    // 기존에 사용하고 있던 게시판도 업데이트 
    sql_query(" update T_BOARD 
                   set B_TYPE = '{$c_name}'
                 where B_CODE='playdportfolio' and B_TYPE = '{$oldName}' ");
                 
    //alert("수정되었습니다.", "./pl_incident_edit.php?seq=".$s_seq.$qstr."&amp;m=u");
	alert("수정되었습니다.", "./post_category_list.php?".$qstr);
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');