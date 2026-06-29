<?php
if (!defined('_YOLU_')) exit; // 개별 페이지 접근 불가

$tab2 = ($tab2) ? $tab2 : "img";

$sql = " select *
           from {$y1['store_info_table']}
          where user_no = '{$member['user_no']}'
            and service_state = '3' ";
$store = sql_fetch($sql);

$store['store_no']   = get_text($store['store_no']);      # 번호
$store['pr_img_url'] = get_text($store['pr_img_url']);    # 홍보이미지경로

// 게시중인 짤
$sql_zz_on = " select *
                 from {$y1['zzal_appl_table']}
                where store_no = '{$store['store_no']}'
                  and appl_state = '3'
             order by upd_dttm desc ";
$row_zz_on = sql_fetch($sql_zz_on);

$zz_on_img_url = ($row_zz_on['zzal_url']) ? '<img src="'.get_text($row_zz_on['zzal_url']).'">' : "<span>게시중인 짤 없음</span>";    # 짤영상위치

// 신청한 짤
$sql_zz = " select *
              from {$y1['zzal_appl_table']}
             where store_no = '{$store['store_no']}'
          order by reg_dttm desc";
$res_zz = sql_query($sql_zz);
?>
<section class="manager _photo_">
    <div class="tab inner_tab">
        <div class="tab_btn col2">
            <a href="./manager_store?tab=intro&tab2=img" class="btn btnL<?=($tab2 == "img") ? " on" : ""?>">이미지 올리기</a>
            <a href="./manager_store?tab=intro&tab2=zzal_img" class="btn btnR<?=($tab2 == "zzal_img") ? " on" : ""?>">짤 올리기(유료)</a>
        </div>
    </div>
    <?php
    include_once('./manager_store_intro_'.$tab2.'.php');
    ?>
</section>