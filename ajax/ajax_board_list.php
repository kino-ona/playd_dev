<?php
    include_once($_SERVER["DOCUMENT_ROOT"].'/include/_common.php');

    $year = nvl($_POST['year'],2);
    $type = nvl($_POST['type']);
    $order = nvl($_POST['order']);
    $keyword = nvl($_POST['keyword']);
    $b_code = nvl($_POST['code']);
    $page = $_POST['page']?$_POST['page']:1;
    $listsize = $_POST['listsize']?$_POST['listsize']:10; 
    if ( !$_POST['page']){
        exit;
    }
    if ($page < 1) $page = 1;
    $start = ($page - 1) * $listsize;

    $where = '';
    if($order == 'new'){
        $orderby = ' order by b_seq desc ';
    } else if($order == 'hits') {
        $orderby = ' order by b_hits desc , b_seq desc ';
        
    } else {
        $orderby = ' order by b_seq desc ';
    }
    

    if($year){
        $where .= " and a.B_YEAR = '".$year."' ";
    }
    if($type){
        $where .= " and a.B_TYPE = '".$type."' ";
    }
    if($keyword){
        $where .= " and ( a.B_TITLE like '%".$keyword."%' OR a.B_CONT like '%".$keyword."%' ) ";
    }

    if ($b_code == 'playdportfolio') {
        $sql = "  select a.*, (select k.FI_SEQ from T_BOARD_FILES k where a.B_SEQ=k.B_SEQ limit 1 )as FI_SEQ from T_BOARD a, T_POST_CATEGORY b
            where a.B_TYPE = b.C_NAME and b.C_USE = 'Y' and a.B_CODE='".$b_code."' and a.B_NOTI_YN in('Y','L') 
            {$where}
            order by STR_TO_DATE(B_SEND_DT, '%Y-%m-%d') desc, B_SEQ desc
            limit {$start}, {$listsize} ";
    } else {
        $sql = "  select a.*, (select k.FI_SEQ from T_BOARD_FILES k where a.B_SEQ=k.B_SEQ limit 1 )as FI_SEQ from T_BOARD a
            where a.B_CODE='".$b_code."' and a.B_NOTI_YN ='Y' 
            {$where}
            {$orderby}
            limit {$start}, {$listsize} ";
    }
    
   
    $result_array = array();
    $result = sql_query($sql);

    if ($b_code == 'playdportfolio') {
        $sql = " select count(*) as count from T_BOARD a, T_POST_CATEGORY b where a.B_TYPE = b.C_NAME and b.C_USE = 'Y' and a.B_CODE='".$b_code."' and a.B_NOTI_YN in('Y','L')  {$where}  ";
        $totalResult = sql_fetch($sql);
        $totalCount = (int)$totalResult['count'];
    } else {
        $sql = " select count(*) as count from T_BOARD a where B_CODE='".$b_code."' and B_NOTI_YN ='Y' {$where}  ";
    $totalResult = sql_fetch($sql);
    $totalCount = (int)$totalResult['count'];
    }
    

    while($row = mysqli_fetch_assoc($result)){  

        $result_array[] = $row; 
    }

    $postData = array('list' => $result_array, 'count' => $totalCount);
    echo json_encode($postData);

?>
