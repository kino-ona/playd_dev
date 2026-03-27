<?php
include_once('./_common.php');

// 권한검사
$auth = get_check_admin_auth($member); //alert('권한이 없습니다.', P1_URL);
if($auth['read'] != 'Y') {
    alert('권한이 없습니다.', '/adm/page/login.php');
    exit;
}

if ($m == "") {
    $html_title = "등록";

    $required_mb_id = 'required';
    $required_mb_password = 'required';
} else if ($m == "u") {
    $user = get_user($seq);

    if (!$user['M_SEQ'])
        alert('존재하지 않는 정보입니다.');

    $html_title = "수정";

    $required_mb_id = 'readonly';
    $required_mb_password = '';

    $user['M_SEQ']       = get_text($user['M_SEQ']);          # 유저번호
    $user['M_ID']        = get_text($user['M_ID']);           # 사번
    $user['M_MAIL']      = get_text($user['M_MAIL']);         # 이메일
    $user['M_USE_YN']    = get_text($user['M_USE_YN']);       # 상태
    $user['M_AUTH_TP']   = get_text($user['M_AUTH_TP']);      # 관리자권한설정

    $user['M_DEP1_MENU'] = get_text($user['M_DEP1_MENU']);    # 일반 권한 대분류
    $user['M_DEP2_MENU'] = get_text($user['M_DEP2_MENU']);    # 일반 권한 소분류
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}


//그룹 리스트 가져오기
$sql = " select * from T_GROUP order by G_SEQ desc ";
$result2 = sql_query($sql);


//활동 이력 
$sql = " select count(*) as cnt from T_MGR_LOG where M_ID = '".$user['M_ID']."' ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 레코드 번호 매김
$bnum = $total_count - ($rows * ($page - 1)); # 역순 (10 ~ 1)
// $bnum = ($page - 1) * $rows + 1; # 순번 (1 ~ 10)

$sql = " select * from T_MGR_LOG where M_ID = '".$user['M_ID']."' order by ML_IDX desc  limit {$from_record}, {$rows} ";
$result3 = sql_query($sql);



$p1['title']    = '';
$p1['subtitle'] = '관리자 관리';
include_once('./_head.php');
?>
<script type="text/javascript">
$(document).ready(function(){
    <?php if ($user['M_AUTH_TP'] == "1") { ?>
    $('#setPermissions').hide();
    <?php } else { ?>
    $('#setPermissions').show();
    <?php } ?>

    <?php if (!$user['M_SEQ']) { ?>
    $("#m_auth_tp1").attr("checked", true);
    $("#setPermissions").hide();
    $("input:checkbox").attr("checked", true);
    <?php } ?>

    // $("#m_auth_tp1").click(function() {
    //     $("#setPermissions").hide();
    //     $("input:checkbox").attr("checked", true);
    // });

    // $("#m_auth_tp2").click(function() {
    //     $("#setPermissions").show();
    // });

    // var list = "<?=$user['M_DEP1_MENU']?>,<?=$user['M_DEP2_MENU']?>";
    // list = list.split(",");

    // for(var i=0; i<list.length; i++) {
    //     $("input[name=" + list[i] + "]").attr("checked", true);
    // }
});

// 하위 메뉴 전체 클릭시 상위 자동 checked
function Auto1Depthchecked(cd) {
    var depth2AllNotChecked = true;

    $("input[id=mb" + cd + "]").each(function (i) {
        if ($(this).is(":checked") == true) {
            depth2AllNotChecked = false;
            return;
        }
    });

    if (depth2AllNotChecked == true) {
        $("input[id=mm" + cd + "]").attr("checked", false);
    } else {
        if ($("input[id=mm" + cd + "]").is(":checked") == false) {
            $("input[id=mm" + cd + "]").attr("checked", true);
        }
    }
}

// 1depth 메뉴 클릭시 하위 2depth 자동 checked
function Auto2Depthchecked(cd) {
    if ($("input[id=mm" + cd + "]").is(":checked") == true) {
        $("input[id=mb" + cd + "]").attr("checked", true);
    } else {
        $("input[id=mb" + cd + "]").attr("checked", false);
    }
}
</script>

<form name="frm" method="post" action="<?php echo P1_PAGE_URL ?>/administration_insert.php" onsubmit="return send();" enctype="multipart/form-data">
<input type="hidden" name="m" value="<?php echo $m ?>">
<?php if ($user['M_SEQ']) { ?>
<input type="hidden" name="seq" value="<?=$user['M_SEQ']?>">
<?php } ?>
<input type="hidden" name="m_auth_tp" value="9"/>

    <fieldset>
    <legend class="hid1">관리자 등록 및 수정</legend>
        <table class="colTable">
            <tr>
                <th><span>*</span>사용자ID</th>
                <td>
                    <label for="m_id"><input type="text" id="m_id" name="m_id" value="<?=$user['M_ID']?>" <?php echo $required_mb_id ?>></label>
                </td>
                <th><span>*</span>패스워드</th>
                <td>
                    <label for="m_pw"><input type="password" id="m_pw" name="m_pw" <?php echo $required_mb_password ?>></label>
                    <? if ($user['M_SEQ']) { ?> <br/>*변경 시 입력<?}?>
                </td>
            </tr>

            <tr>
                <th><span>*</span>사용자명</th>
                <td>
                    <label for="m_name"><input type="text" id="m_name" name="m_name" value="<?=$user['M_NAME']?>" ></label>
                </td>
                <th><span>*</span>이메일</th>
                <td>
                    <label for="m_mail"><input type="text" id="m_mail" name="m_mail" value="<?=$user['M_MAIL']?>" ></label>
                </td>
            </tr>


            <tr>
                <th>휴대폰번호</th>
                <td>
                    <label for="m_phone"><input type="text" id="m_phone" name="m_phone" value="<?=$user['M_PHONE']?>" ></label>
                </td>
                <th><span>*</span>그룹</th>
                <td>
                    <label for="g_seq">
                        <select name="g_seq" id="g_seq">
                            <option value="">선택</option>
                    <?php
    for ($i=0; $row=sql_fetch_array($result2); $i++) {
    ?>
        <option value="<?=$row['G_SEQ']?>" <?if($row['G_SEQ'] == $user['G_SEQ']){?>selected<?}?>><?=$row['G_NAME']?></option>

<?}?>
    </selecT>

                    </label>
                </td>
            </tr>

            <tr>
                <th>사용여부</th>
                <td colspan="4">
                    <input type="radio" id="m_use_yn1" name="m_use_yn"<?php if (!$user['M_SEQ']) { ?>checked <?}?> value="Y" <?php echo get_checked($user['M_USE_YN'], "Y"); ?> required><label for="m_use_yn1">사용</label>
                    <input type="radio" id="m_use_yn2" name="m_use_yn" value="N" <?php echo get_checked($user['M_USE_YN'], "N"); ?> required><label for="m_use_yn2">미사용</label>
                </td>
            </tr>

        </table>

<br/>
<h4>활동 이력(<?=number_format($total_count)?>)</h4>
<table class="rowTable">
    <thead>
        <tr>
            <th>일시</th>
            <th>구분</th>
            <th>경로/위치</th>
            <th>대상</th>
        </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result3); $i++) {
        $ml_path = $row['ML_PATH'];
        if($row['ML_MODE'] == '접속'){
            $ml_path = $row['ML_IP'];
        }
    ?>
        <tr>
            <td width="15%"><?=$row['ML_REG']?></td>
            <td width="10%"><?=$row['ML_MODE']?></td>
            <td><?=$ml_path?></td>
            <?if($row['ML_LINK']){?>
                <?if($row['ML_MODE'] == '그룹추가' || $row['ML_MODE'] == '그룹변경'){
                    $split_txt = explode("id=", $row['ML_LINK']);
                  
                ?>
                    <td><a href="<?=$row['ML_LINK']?>"><u><?= $split_txt[1]?></u></a></td>
                <?}else{?>
                    <td><a href="<?=$row['ML_LINK']?>"><u>바로가기</u></a></td>
                <?}?>
            <?}else{?>
                <td><?=$row['ML_LINK']?></td>
            <?}?>
        </tr>
    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=4>자료가 없습니다.</td></tr>";
    ?>
    </tbody>
</table>

<!-- pagination -->
<?php echo get_paging($rows, $page, $total_page, '?seq='.$seq.'&m=u'); ?>




        <!-- both button -->
        <div class="bothButton">
            <div class="fl">
                <button class="btn btn-inverse" type="button" onclick="fnGoView('<?php echo P1_PAGE_URL ?>/administration.php');">목록</button>
            </div>
            <div class="fr">
                <?php if ($m == "u") { ?>
                <?if($auth['write'] == 'Y') {?>
                <input type="submit" class="btn btn-primary" value="수정">
                <?}?>
                <?if($auth['del'] == 'Y' && $user['M_ID'] != 'admin') {?>
                <button class="btn btn-inverse" type="button" onclick="deletes('<?php echo P1_PAGE_URL ?>/administration_del.php');">삭제</button>
                <?}?>
                <?php } ?>
                <?php if ($m == "") { ?>
                    <?if($auth['write'] == 'Y') {?>
                <input type="submit" class="btn btn-inverse" value="등록">
                <?}?>
                <?php } ?>
            </div>
        </div>
    </fieldset>
</form><!-- 목록으로 돌아가기 -->
<form name="golist" action="" method="post">
    <input type="hidden" name="page" value="<?=$pagelist?>">
    <input type="hidden" name="search_type" value="<?=$search_type?>">
    <input type="hidden" name="search_txt" value="<?=$search_txt?>">
    <input type="hidden" name="auth_tp" value="<?=$auth_tp?>">
</form>

<script type="text/javascript">
//등록 & 수정
function send() {
    if (confirm("<?=$html_title?> 하시겠습니까?")) {
        if ($("#m_id").val() == null || $("#m_id").val() == "") {
            alert("사용자ID를 입력해주세요");
            return false;
        }

        <?php if ($m == "") { ?>
        if ($("#m_pw").val() == null || $("#m_pw").val() == "") {
            alert("패스워드를 입력해주세요");
            return false;
        }

        var reg = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/;
        if(false === reg.test($("#m_pw").val()) || $("#m_pw").val().length > 16) {
            alert('비밀번호는 8~16자 이내의 영문 대/소문자,숫자,특수문자를 사용해 주세요.');
            return false;
        }

        <?php }else{ ?>


        if( $("#m_pw").val()!= ''){
            var reg = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/;
            if(false === reg.test($("#m_pw").val()) || $("#m_pw").val().length > 16) {
                alert('비밀번호는 8~16자 이내의 영문 대/소문자,숫자,특수문자를 사용해 주세요.');
                return false;
            }
        }

        <?}?>

        if($("#m_name").val() == null || $("#m_name").val() == "") {
            alert("사용자명을 입력해주세요");
            return false;
        }

        if($("#g_seq").val() == null || $("#g_seq").val() == "") {
            alert("그룹을 입력해주세요");
            return false;
        }


        if($("#m_mail").val() == null || $("#m_mail").val() == "") {
            alert("이메일을 입력해주세요");
            return false;
        }


        if (!checkemail()) return false;    // 메일 유효성 확인

        roles();

        return;
    } else {
        return false;
    }
}

//roles 조합
function roles() {
    var depth1 = "";
    var depth2 = "";
    var roles  = "";

    $("input:checkbox").each(function() {
        if(this.checked == true) {
            if (this.name.length == 1) {
                depth1  = (depth1 != "") ? depth1 + "," : depth1;
                depth1 += this.name;
            } else {
                depth2  = (depth2 != "") ? depth2 + "," : depth2;
                depth2 += this.name;
            }
            roles  = (roles != "") ? roles+"," : roles;
            roles += this.value;
        }
    });

    if ($("#m_auth_tp1").is(":checked")) {
        $("#roles").val("ROLE_ADMIN");
    } else {
        $("#roles").val(roles);
    }

    $("#dep1_menu").val(depth1);
    $("#dep2_menu").val(depth2);
}
</script>
<?php
include_once('./_tail.php');
?>