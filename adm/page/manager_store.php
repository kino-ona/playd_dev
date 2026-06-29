<?php
include_once('./_common.php');

if ($is_guest) {
    alert("로그인 후 이용하세요.", "./login");
}

$sql = " select count(*) as cnt
           from {$y1['store_info_table']}
          where user_no = '{$member['user_no']}'
            and service_state in (1, 2) ";
$row = sql_fetch($sql);
if ($row['cnt'] > 0) {
    alert("가맹점회원 심의 중입니다.");
}

$tab = ($tab) ? $tab : "info";

$y1['title'] = '마이메뉴';

switch($tab) {
    case "info":
        $y1['art_cls'] = ' detail';
        $y1['div_cls'] = '_bg';
        break;
    case "intro":
        $y1['art_cls'] = ' write';
        $y1['div_cls'] = '_bg';
        break;
    case "detail":
        $y1['div_cls'] = '';
        $y1['art_cls'] = ' detail write';
        break;
}

$y1['div_id'] = 'navMymenu';
include_once('./_head.php');
?>
<article class="mymenu<?=$y1['art_cls']?>">
    <section class="_top_">
        <a href="<?php echo Y1_PAGE_URL ?>/manager" class="go_list">매장관리자 앱</a>
        <p class="title">매장정보</p>
    </section>
    <section class="tab">
        <div class="tab_btn type2 col3">
            <a href="./manager_store?tab=info" class="btn<?=($tab == "info") ? " on" : ""?>">기본정보</a>
            <a href="./manager_store?tab=intro" class="btn<?=($tab == "intro") ? " on" : ""?>">메인소개</a>
            <a href="./manager_store?tab=detail" class="btn<?=($tab == "detail") ? " on" : ""?>">업체소개</a>
        </div>
    </section>
    <?php
    include_once('./manager_store_'.$tab.'.php');
    ?>
</article>
<?php
include_once('./_tail.php');
?>