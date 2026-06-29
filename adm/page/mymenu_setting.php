<?php
define('_NAV_', true);

include_once('./_common.php');

if ($is_guest) {
    alert("로그인 후 이용하세요.", "./login");
}

$y1['title'] = '마이메뉴';
$y1['div_cls'] = 'nav_wrap';
$y1['div_id'] = 'navMymenu';
include_once('./_head.php');
?>
<article class="mymenu detail">
    <section class="_top_">
        <a href="<?php echo Y1_PAGE_URL ?>/mymenu" class="go_list">마이메뉴</a>
        <p class="title">환경설정</p>
    </section>
    <section class="list_setting">
        <ul>
            <li>
                <span>알림수신</span>
                <ul>
                    <li>
                        <div class="onoff_wrap">
                            <span>이벤트알림</span>
                            <button type="button" class="btn_onoff<?=($member['noti_event'] == "1") ? " on" : ""?>" onclick="noti_select(this, 'noti_event');"><?=($member['noti_event'] == "1") ? " ON" : "OFF"?></button>
                        </div>
                    </li>
                    <li>
                        <div class="onoff_wrap">
                            <span>업소알림</span>
                            <button type="button" class="btn_onoff<?=($member['noti_store'] == "1") ? " on" : ""?>" onclick="noti_select(this, 'noti_store');"><?=($member['noti_store'] == "1") ? " ON" : "OFF"?></button>
                        </div>
                    </li>
                    <li>
                        <div class="onoff_wrap">
                            <span>신규오픈알림</span>
                            <button type="button" class="btn_onoff<?=($member['noti_open'] == "1") ? " on" : ""?>" onclick="noti_select(this, 'noti_open');"><?=($member['noti_open'] == "1") ? " ON" : "OFF"?></button>
                        </div>
                    </li>
                </ul>
            </li>
            <li>
                <span>앱 버전</span>
                <ul>
                    <li>현재버전<span class="ver" id="now_ver"></span></li>
                </ul>
            </li>
        </ul>
    </section>
</article>
<script>
if(window.hasOwnProperty('android')) {
    var device_info = jQuery.parseJSON(window.android.getdeviceInfo());
    app_now_ver(device_info);
} else {
    window.location = "jscall://getdeviceInfo?app_now_ver";
}
</script>
<?php
include_once('./_tail.php');
?>