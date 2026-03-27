<?php
include_once('./_common.php');

$start    = trim($_POST['start']);
$list     = trim($_POST['list']);

// 정렬 타입
$sort_txt = ($_POST['sort_txt']) ? trim($_POST['sort_txt']) : "가까운 거리 순";

// 현재 위치 위,경도
$now_lat = ($_POST['now_lat']) ? trim($_POST['now_lat']) : "35.832415";
$now_lng = ($_POST['now_lng']) ? trim($_POST['now_lng']) : "128.556782";

// 현재 위치 정보
$now_loc_data  = coord2address($now_lat, $now_lng);
$now_addr      = $now_loc_data->documents[0]->address->address_name;
$now_road_addr = $now_loc_data->documents[0]->road_address->address_name;

$sql_common = " from {$y1['store_info_table']} ";

$sql_search = " where (1) ";

// 메인 검색어
if ($search_txt) {
    // LIKE 보다 INSTR 속도가 빠름
    // 매장명
    if (preg_match("/[a-zA-Z]/", $search_txt))
        $sql_instr_nm = " and INSTR(LOWER(store_disp_nm), LOWER('{$search_txt}')) ";
    else
        $sql_instr_nm = " and INSTR(store_disp_nm, '{$search_txt}') ";
    
    // 해시태그
    if (preg_match("/[a-zA-Z]/", $search_txt))
        $sql_instr_rc = " and INSTR(LOWER(rec_word), LOWER('{$search_txt}')) ";
    else
        $sql_instr_rc = " and INSTR(rec_word, '{$search_txt}') ";
    
    // 매장명 OR 해시태그
    $sql_search .= " {$sql_instr_nm}
                     or store_no
                     in (
                          select store_no
                            from {$y1['store_recom_table']}
                           where (1) {$sql_instr_rc} 
                        ) ";
}

// 추천검색어
if($com_search_txt) {
    if (preg_match("/[a-zA-Z]/", $com_search_txt))
        $sql_instr_com_txt = " and INSTR(LOWER(rec_word), LOWER('{$com_search_txt}')) ";
    else
        $sql_instr_com_txt = " and INSTR(rec_word, '{$com_search_txt}') ";
    
    $sql_search .= " and store_no
                      in (
                           select store_no
                             from {$y1['store_recom_table']}
                            where store_no = store_info.store_no
                                  {$sql_instr_com_txt}
                         ) ";
}

// 카테고리 (음식종류)
if($store_cate) {
    $sql_search .= " and store_cate = '{$store_cate}' ";
}

// 카테고리 (가격대)
if($price) {
    $sql_price = " and store_no
                        in (
                             select store_no
                               from {$y1['menu_table']}
                              where store_no = store_info.store_no
                                and use_yn = '1' ";
    switch($price) {
        case "1":
            // 2만원 이하
            $sql_search .= $sql_price." and price <= '20000' ) ";
            break;
        case "2":
            // 2만원 이상 ~ 4만원 이하
            $sql_search .= $sql_price." and (price >= '20000' and price <= '40000') ) ";
            break;
        case "3":
            // 4만원 이상
            $sql_search .= $sql_price." and price >= '40000' ) ";
            break;
    }
}

// 카테고리 (주차여부)
if($parking) {
    $sql_search .= " and parking = '{$parking}' ";
}

// 카테고리 (쿠폰발행여부)
if($coupon) {
    $sql_search .= " and store_no
                      in (
                           select store_no
                             from {$y1['cupon_appl_table']}
                            where store_no = store_info.store_no
                              and appl_state = '2'
                              and '".Y1_TIME_YMDHIS."' between start_dt and end_dt
                         order by start_dt
                         ) ";
}

// 운영상태[200] 영업중 상태
$sql_search .= " and service_state = '3' ";

$sql_order = " order by loc ";
if ($sst) {
    $sql_order = " order by {$sst} {$sod} ";
}

$sql = " select count(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 매장 정보
$sql = " select *,
                get_distance({$now_lat}, {$now_lng}, lat, lng) as loc,
                (
                    select count(*)
                      from {$y1['user_pick_store_table']}
                     where store_no = {$y1['store_info_table']}.store_no
                ) as pick_cnt,
                (
                    select sum(review_cnt)
                      from {$y1['menu_table']}
                     where store_no = {$y1['store_info_table']}.store_no
                       and use_yn = '1'
                ) as review_cnt
                {$sql_common}
                {$sql_search}
                {$sql_order}
          limit {$start}, {$list} ";
$res = sql_query($sql);

$max_rand  = ($total_count >= $list) ? $list-1 : $total_count-1;
$randomNum = mt_rand(0, $max_rand);

for ($i=0; $row=sql_fetch_array($res); $i++) {
    // 짤이미지 여부
    $sql_zzal = " select *
                    from {$y1['zzal_appl_table']}
                   where store_no = '{$row['store_no']}'
                     and appl_state = '3'
                     and '".Y1_TIME_YMD."' between start_dt and end_dt
                order by reg_dttm desc ";
    $row_zzal = sql_fetch($sql_zzal);
    
    if($row_zzal['zzal_url']) {
        $img_url = '<img src="'.$row_zzal['zzal_url'].'">';
    } else if($row['pr_img_url']) {
        $img_url = '<img src="'.$row['pr_img_url'].'">';
    } else {
        $img_url = '<img src="'.Y1_NOIMG_TILE.'">';
    }
    
    // 해시태그 정보
    $sql_hash = " select *
                    from {$y1['store_recom_table']}
                   where store_no = '{$row['store_no']}'
                order by reg_dttm ";
    $res_hash = sql_query($sql_hash);
    
    // likeit 정보
    $sql_like = " select *
                    from {$y1['user_pick_store_table']}
                   where store_no = '{$row['store_no']}'
                     and user_no = '{$member['user_no']}' ";
    $row_like = sql_fetch($sql_like);
    $likeit_cls = ($row_like) ? " on" : "";
    
    // 유효기간 안에 승인된 쿠폰이 있을경우
    // 쿠폰 이벤트 중으로 처리
    $sql_cup = " select *
                   from {$y1['cupon_appl_table']}
                  where store_no = '{$row['store_no']}'
                    and appl_state = '2'
                    and '".Y1_TIME_YMDHIS."' between start_dt and end_dt
               order by start_dt, end_dt
                  limit 1 ";
    $res_cup   = sql_query($sql_cup);
    $cnt_cup   = sql_num_rows($res_cup);
    $cupon_cls = ($cnt_cup > 0) ? " on" : "";
?>
<div class="item" onclick="store_item_view(this);";>
    <div class="inner">
        <div class="coupon<?=$cupon_cls?>">coupon</div>
        <div class="likeit<?=$likeit_cls?>" onclick="store_likeit(this, '<?=$row['store_no']?>');">likeit</div>
        <a href="./store_view?store_no=<?=$row['store_no']?>" class="focus">
            <div class="info">
                <p class="tit"><?=$row['store_disp_nm']?></p>
                <p class="hashtag">
                <?php
                while($hash=sql_fetch_array($res_hash)) {
                    echo '<span>#'.$hash['rec_word'].'</span>';
                }
                ?>
                </p>
            </div>
            <p class="loc">
                <span class="ico"></span>
                <span class="txt"><?=$row['loc']?>m</span>
            </p>
        </a>
        <?=$img_url?>
    </div>
</div>
<?php
    if ($i == $randomNum) {
        // 광고 배너 리스트
        $sql_bn = " select *
                      from (
                             select a.*,
                                    @rownum := @rownum + 1 rowno
                               from {$y1['banner_info_table']} a,
                                    ( select @rownum := 0 ) tmp
                              where post_state = '2'
                           order by banner_no
                           ) a
                     where rowno = ( select mod(now(), ( select count(*) from {$y1['banner_info_table']} where post_state = '2' ) ) + 1 ) ";
        $res_bn = sql_query($sql_bn);
        for ($j=0; $row_bn=sql_fetch_array($res_bn); $j++) {
            if ($row_bn['img_url']) {
                $bn_img_url = '<img src="'.$row_bn['img_url'].'">';
            } else {
                $bn_img_url = '<img src="'.Y1_NOIMG_TILE.'">';
            }
?>
        <div class="item" onclick="store_item_view(this);">
            <div class="inner">
                <div class="ad">AD</div>
                <a href="#" class="focus" onclick="javascript:window.open('<?=$row_bn['link_url']?>')">
                    <div class="info">
                        <p class="tit"><?=$row_bn['banner_title']?></p>
                    </div>
                </a>
                <?=$bn_img_url?>
            </div>
        </div>
<?php
        }
    }
}
?>