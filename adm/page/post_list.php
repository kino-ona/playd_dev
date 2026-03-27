<?php
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가

//권한체크
$auth = get_check_admin_auth($member); //alert('권한이 없습니다.', P1_URL);
if($auth['read'] != 'Y') {
    alert('권한이 없습니다.', '/adm/page/login.php');
    exit;
}


$sql_common = " from {$p1['t_board_table']} ";

$sql_search = " where (1)
                  and b_code = '{$board['BC_CODE']}' ";
                  
// 검색어
if ($search_txt) {    

    if($search_type == 'file'){
        $sql_search .= " and b_seq in ( select k.b_seq from T_BOARD_FILES k WHERE k.FI_ORG like '%".$search_txt."%' ) ";
      
    } else {
         // LIKE 보다 INSTR 속도가 빠름
        if (preg_match("/[a-zA-Z]/", $search_txt)) 
            $sql_instr = " and INSTR(LOWER(b_{$search_type}), LOWER('{$search_txt}')) ";
        else
            $sql_instr = " and INSTR(b_{$search_type}, '{$search_txt}') ";
        
        switch($search_type) {
            case "all":    # 제목 + 내용
                $sql_search .= " and (b_title like '%{$search_txt}%' || b_cont like '%{$search_txt}%') ";
                break;
            default:
                $sql_search .= $sql_instr;
                break;
        }
    }
   
}

if ($date_y) {
    $sql_search .= " and b_year = '".$date_y."' ";
}

if ($date_m) {
    $sql_search .= " and b_month = '".$date_m."' ";
}

// 사이트선택
if ($site) {
    $sql_search .= " and b_site = '{$site}' ";
}

// 상단고정선택
if ($exps_yn) {
    $sql_search .= " and b_exps_yn = '{$exps_yn}' ";
}

// 구분
if($_POST['type']){
    $type = $_POST['type'];
}
if ($type) {
    $sql_search .= " and b_type = '{$type}' ";
}

// 노출여부
if ($noti_yn) {
    $sql_search .= " and b_noti_yn = '{$noti_yn}' ";
}



$sql = " select count(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$page_rows = $board['BC_ROWS'];
$list_page_rows = $board['BC_PAGES_ROWS'];

if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
$from_record = ($page - 1) * $page_rows; // 시작 열을 구함

$sql_order = " order by b_regdate desc ";
if ($sst) {
    $sql_order = " order by {$sst} {$sod} ";
}

if(!$sod){
    $sod = 'asc';
}

if($bc_code == 'playdportfolio') {
     $sql_order = " order by B_SEND_DT desc ";
}

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$page_rows} ";
$res = sql_query($sql);

$k = 0;
$i = 0;
$list = array();
while ($row=sql_fetch_array($res)) {
    $list[$i] = $row;
    $list_num = $total_count - ($page - 1) * $list_page_rows;
    $list[$i]['num'] = $list_num - $k;
    $list[$i]['href'] = P1_PAGE_URL.'/post.php';
    //$list[$i]['B_REGDATE'] = passing_time($list[$i]['B_REGDATE']);
    // $list[$i]['href'] = P1_PAGE_URL.'/post.php?bc_code='.$board['BC_CODE'].'&amp;b_seq='.$list[$i]['B_SEQ'].$qstr;

    $i++;
    $k++;
}

$pages = get_paging($page_rows, $page, $total_page, './post.php?bc_code='.$bc_code.$qstr.'&amp;page='.$page.'&site='.$site.'&exps_yn='.$exps_yn.'&type='.$type.'&noti_yn='.$noti_yn);

$write_href = '';
$write_href = P1_PAGE_URL.'/post_write.php?bc_code='.$bc_code;

include_once(P1_POST_SKIN_PATH.'/'.$board['BC_SKIN'].'/list.php');
?>