<?php
include_once('./_common.php');

$last_idx    = trim($_POST['last_idx']);
$menu_no     = trim($_POST['menu_no']);
$show_review = trim($_POST['show_review']);

$sql_review = " select *
                  from {$y1['menu_review_table']}
                 where review_state = '2'
                   and menu_no = '{$menu_no}'
              order by reg_dttm desc
                 limit {$last_idx}, {$show_review}";
$res_review = sql_query($sql_review);
while($row_review=sql_fetch_array($res_review)) {
    $thumb_url = ($row_review['thumb_url']) ? $row_review['thumb_url'] : Y1_NOIMG_GALLERY;
?>
<a href="./review_view?review_no=<?=$row_review['review_no']?>" class="swiper-slide"><img src="<?=$thumb_url?>"></a>
<?php
}
?>