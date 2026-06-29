<?php
define('_NAV_', true);

include_once('./_common.php');

if ($is_guest) {
    alert("로그인 후 이용하세요.", "./login");
}

// 찜 업소 리스트
$sql = " select *
           from {$y1['user_pick_store_table']}
          where user_no = '{$member['user_no']}' ";
$res = sql_query($sql);

$y1['title'] = '마이메뉴';
$y1['div_cls'] = 'nav_wrap';
$y1['div_id'] = 'navMymenu';
include_once('./_head.php');
?>
<article class="mymenu detail">
    <section class="_top_">
        <a href="<?php echo Y1_PAGE_URL ?>/mymenu" class="go_list">마이메뉴</a>
        <p class="title">내가 찜한 업소</p>
    </section>
    <section class="list_store">
        <ul>
            <?php
            while($row=sql_fetch_array($res)) {
                // 매장 정보
                $store = get_store($row['store_no']);
                
                // 매장 프로필 이미지
                $icon_img_url = ($store['icon_img_url']) ? $store['icon_img_url'] : Y1_URL.Y1_NOIMG_STORE;
                
                // 해시태그 정보
                $sql_hash = " select *
                                from {$y1['store_recom_table']}
                               where store_no = '{$row['store_no']}' ";
                $res_hash = sql_query($sql_hash);
            ?>
            <li onclick="location.href='<?php echo Y1_PAGE_URL ?>/store_view?store_no=<?=$store['store_no']?>&url=<?php echo Y1_PAGE_URL ?>/mymenu_pick_store'" style="cursor:pointer;">
                    <div class="img">
                        <img src="<?=$icon_img_url?>">
                    </div>
                    <div class="txt">
                        <!--<p class="likeit"></p>-->
                        <p class="name"><?=$store['store_disp_nm']?></p>
                        <p class="address">주<span class="spacing"></span>소<span><?=$store['addr1']?> <?=$store['addr2']?></span></p>
                        <p class="phone">전화번호<span><?=$store['tel']?></span></p>
                    </div>
                    <div class="tag">
                        <?php
                        while($hash=sql_fetch_array($res_hash)) {
                            echo '<p>'.$hash['rec_word'].'</p>';
                        }
                        ?>
                    </div>
            </li>
            <?php
            }
            ?>
        </ul>
    </section>
</article>
<?php
include_once('./_tail.php');
?>