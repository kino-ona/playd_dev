<?php
include_once('./_common.php');

if ($is_guest) {
    alert("로그인 후 이용하세요.", "./login");
}

$sql = " select *
           from {$y1['store_info_table']}
          where user_no = '{$member['user_no']}'
            and service_state = '3' ";
$store = sql_fetch($sql);

// 로그인중이고 자신의 매장이라면 패스
if (!($member['user_no'] && $store['user_no'] === $member['user_no']))
    alert('권한이 없습니다.');

$sql = " select *
           from {$y1['cupon_appl_table']}
          where store_no = '{$store['store_no']}'
       order by reg_dttm desc ";
$res = sql_query($sql);

$y1['title'] = '마이메뉴';
$y1['div_cls'] = '_bg';
$y1['div_id'] = 'navMymenu';
include_once('./_head.php');
?>
<article class="mymenu list">
    <section class="_top_">
        <a href="<?php echo Y1_PAGE_URL ?>/manager" class="close">닫기</a>
        <p class="title">쿠폰등록</p>
        <a href="<?php echo Y1_PAGE_URL ?>/manager_coupon_regist" class="btn_apply">등록</a>
    </section>
    
    <form name="fwrite" id="fwrite" action="./manager_coupon_update" method="post">
    <input type="hidden" name="appl_no" id="appl_no">
    <input type="hidden" name="appl_state" id="appl_state">
    <input type="hidden" name="store_no" id="store_no" value="<?=$store['store_no']?>">
    
    <section class="list_coupon">
        <ul>
            <?php
            while($row=sql_fetch_array($res)) {
                $img_url = ($row['img_url']) ? $row['img_url'] : Y1_NOIMG_STORE;
                
                // 신청상태
                $sql_co = " select cd_nm
                              from {$y1['code_master_table']}
                             where cd_cls = '420'
                               and cd = '{$row['appl_state']}' ";
                $row_co = sql_fetch($sql_co);
            ?>
            <li>
                <div class="coupon_wrap">
                    <div class="img">
                        <img src="<?=$img_url?>">
                    </div>
                    <div class="txt">
                        <div class="coupon on">coupon</div>
                        <p class="name"><?=$store['store_disp_nm']?></p>
                        <p class="info"><?=$row['title']?></p>
                    </div>
                </div>
                <div class="status">
                    <p><span>유효기간</span><?=$row['start_dt']?> ~ <?=$row['end_dt']?></p>
                    <p><span>신청상태</span><?=$row_co['cd_nm']?></p>
                    <?php if($row['appl_state'] == "2") { ?>
                    <button type="submit" class="btn cancel" onclick="return fwrite_submit('<?=$row['appl_no']?>', '3');">쿠폰미사용</button>
                    <?php } else { ?>
                    <button type="submit" class="btn available" onclick="return fwrite_submit('<?=$row['appl_no']?>', '2');">쿠폰사용</button>
                    <?php } ?>
                </div>
            </li>
            <?php
            }
            ?>
        </ul>
    </section>
    
    </form>

    <script>
    function fwrite_submit(appl_no, appl_state) {
        if(!confirm("쿠폰을 수정 하시겠습니까?")) {
            return false;
        } else {
            $("#appl_no").val(appl_no);
            $("#appl_state").val(appl_state);
        }
        
        return true;
    }
    </script>
</article>
<?php
include_once('./_tail.php');
?>