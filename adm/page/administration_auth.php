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
$sql = " select * from T_GROUP order by G_NAME asc ";
$result2 = sql_query($sql);

//그룹 메뉴 리스트 가져오기 
if($seq){
    $sql = " select * from T_GROUP_AUTH  where G_SEQ = '".$seq."' ";
    $result3 = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result3); $i++) {
        $result_array[] = $row; 
    }
}

$p1['title']    = '';
$p1['subtitle'] = '권한 관리';
include_once('./_head.php');
?>

    <fieldset>
    <legend class="hid1">관리자 등록 및 수정</legend>
        <table class="colTable" style="border-top:none;">
            <tr>
                <td style="width:35%;  vertical-align:top;">
                    <h4>관리자 그룹명</h4>
                    <table class="colTable">
                    <?php
                    for ($i=0; $row=sql_fetch_array($result2); $i++) {
                    ?>
                        <tr id="group_tr_<?=$row['G_SEQ']?>" onclick="groupClick('<?=$row['G_SEQ']?>');" <?if($seq == $row['G_SEQ']){?> style="background:#eeeeee;" <?}?> >
                            <td style="height:40px; font-weight:bold; cursor:pointer;">
                               <?=$row['G_NAME']?>
                            </td>
                        </tr>
                    <?}?>
                    </table>
                    
                    <div style="width:100%; margin-top:10px; text-align:right;">
                        <?if($auth['write']=='Y'){?>
                        <a href="javascript:groupAppend();" class="btn btn-primary">추가</a>
                        <?}?>
                        <?if($auth['del']=='Y'){?>
                        <a href="javascript:groupDelete();" class="btn btn-default">삭제</a>
                        <?}?>
                    </div>
                    

                </td>
                <td  style="width:40px;"></td>
                <td  style="width:65%; vertical-align:top;" >
                <h4>메뉴 권한 상세</h4>
                    <?if($seq){?>
                    <table class="colTable">
                    <tr>
                        <td style="text-align:center;">메뉴</td>
                        <td style="text-align:center;">접근/읽기</td>
                        <td style="text-align:center;">등록/수정</td>
                        <td style="text-align:center;">삭제</td>
                    </tr>
                    <tr>
                        <td ><span class="fa fa-plus"></span> 관리자 관리</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_a"  value="Y" onclick="javascript:allCheck(this, 'read','a');"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_a"  value="Y" onclick="javascript:allCheck(this, 'write','a');"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_a"  value="Y" onclick="javascript:allCheck(this, 'del','a');"></td>
                    </tr>
                    <tr>
                        <td>└ 관리자 계정 관리</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_a1" value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_a1" value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_a1" value="Y"></td>
                    </tr>
                    <tr>
                        <td>└ 권한 관리</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_a2" value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_a2" value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_a2" value="Y"></td>
    
                    </tr>
                    <tr>
                        <td>└ IP 관리</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_a3" value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_a3" value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_a3" value="Y"></td>
    
                    </tr>
                    <tr>
                        <td >게시판 설정 관리</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_b"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_b"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_b"  value="Y"></td>
                    </tr>


                    <tr>
                        <td ><span class="fa fa-plus"></span> 포트폴리오 관리</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_h"  value="Y" onclick="javascript:allCheck(this, 'read','h');"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_h"  value="Y" onclick="javascript:allCheck(this, 'write','h');"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_h"  value="Y" onclick="javascript:allCheck(this, 'del','h');"></td>
                    </tr>
                    <tr>
                        <td >└ 카테고리 관리</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_h1"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_h1"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_h1"  value="Y"></td>
                    </tr>
                    <tr>
                        <td >└ 포트폴리오 게시판 관리</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_h2"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_h2"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_h2"  value="Y"></td>
                    </tr>


                    <tr>
                        <td ><span class="fa fa-plus"></span> 게시판 관리</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_c"  value="Y" onclick="javascript:allCheck(this, 'read','c');"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_c"  value="Y" onclick="javascript:allCheck(this, 'write','c');"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_c"  value="Y" onclick="javascript:allCheck(this, 'del','c');"></td>
                    </tr>
                    <tr>
                        <td >└ 뉴스렌터</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_c1"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_c1"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_c1"  value="Y"></td>
                    </tr>
                    <tr>
                        <td >└ 리포트</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_c2"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_c2"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_c2"  value="Y"></td>
                    </tr>
                    <tr>
                        <td >└ 광고컬럼</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_c3"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_c3"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_c3"  value="Y"></td>
                    </tr>

                    <!-- <tr>
                        <td >└ 포트폴리오</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_c4"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_c4"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_c4"  value="Y"></td>
                    </tr> -->

                    <tr>
                        <td >└ 개인정보처리방침</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_c5"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_c5"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_c5"  value="Y"></td>
                    </tr>

                    <tr>
                        <td >└ 전자공고</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_c6"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_c6"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_c6"  value="Y"></td>
                    </tr>

                    <tr>
                        <td >└ 공시정보</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_c7"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_c7"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_c7"  value="Y"></td>
                    </tr>


                    <tr>
                        <td ><span class="fa fa-plus"></span> 문의 관리</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_d"  value="Y" onclick="javascript:allCheck(this, 'read','d');"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_d"  value="Y" onclick="javascript:allCheck(this, 'write','d');"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_d"  value="Y" onclick="javascript:allCheck(this, 'del','d');"></td>
                    </tr>

                    <tr>
                        <td >└ 광고문의</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_d1"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_d1"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_d1"  value="Y"></td>
                    </tr>
                    <tr>
                        <td >└ 윤리경영제보</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_d2"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_d2"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_d2"  value="Y"></td>
                    </tr>


                    <tr>
                        <td ><span class="fa fa-plus"></span> 채용공고 관리</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_e"  value="Y" onclick="javascript:allCheck(this, 'read','e');"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_e"  value="Y" onclick="javascript:allCheck(this, 'write','e');"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_e"  value="Y" onclick="javascript:allCheck(this, 'del','e');"></td>
                    </tr>
                    <tr>
                        <td >└ 채용공고</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_e1"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_e1"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_e1"  value="Y"></td>
                    </tr>
                    <tr>
                        <td >└ 상시채용</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_e2"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_e2"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_e2"  value="Y"></td>
                    </tr>

                    <tr>
                        <td ><span class="fa fa-plus"></span> 신청자 관리</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_f"  value="Y" onclick="javascript:allCheck(this, 'read','f');"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_f"  value="Y" onclick="javascript:allCheck(this, 'write','f');"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_f"  value="Y" onclick="javascript:allCheck(this, 'del','f');"></td>
                    </tr>

                    <tr>
                        <td >└ 뉴스레터</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_f1"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_f1"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_f1"  value="Y"></td>
                    </tr>
                    <tr>
                        <td >└ 리포트</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_f2"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_f2"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_f2"  value="Y"></td>
                    </tr>

                    <tr>
                        <td >파일 업로드</td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_read_g"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_write_g"  value="Y"></td>
                        <td style="text-align:center;"><input type="checkbox" id="auth_del_g"  value="Y"></td>
                    </tr>

                    </table>
                    <?}?>

                </td>
            </tr>
        </table>
        <!-- both button -->
        <?if($auth['write']=='Y'){?>
        <div class="bothButton">
            <div class="fr">
                <button class="btn btn-primary" type="button" onclick="javascript:authSave();">저장</button>
            </div>
        </div>
        <?}?>
    </fieldset>

<form name="golist" action="" method="post">
    <input type="hidden" name="page" value="<?=$page?>">
    <input type="hidden" name="search_type" value="<?=$search_type?>">
    <input type="hidden" name="search_txt" value="<?=$search_txt?>">
    <input type="hidden" name="auth_tp" value="<?=$auth_tp?>">
</form>

<div id="group_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <form name="frm" id="frm" method="post" action="administration_auth_insert.php" >
    <input type="hidden" name="cmd" id="cmd" value="insert">
    <input type="hidden" name="seq" id="seq"  value="">
    <input type="hidden" name="token" id="token"  value="">
    <input type="hidden" name="auth_array" id="auth_array"  value="">
    

            <div class="modal-dialog modal-md" >
                    <div class="modal-content">
                        <div class="modal-header">
                           <h4 class="modal-title">관리자 그룹 신규 생성 </h4>
                        </div>
                          
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12" >
                                    <input type="text" class="form-control" id="g_name" name="g_name" placeholder="관리자 그룹명을 입력해 주세요."  value="">
                                                          

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger waves-effect" onclick="javascript:groupInsert();">등록</button>
                                               
                             <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">취소</button>
                        </div>
                    </div>
             </div>
                </form>
</div>

<script type="text/javascript">

var g_group_seq = '<?=$seq?>';
var g_auth_array = [];

<?if($result_array){?>
g_auth_array = '<?=json_encode($result_array)?>';
if(g_auth_array){
    g_auth_array = JSON.parse(g_auth_array);
}
<?}?>


//등록 & 수정
function send() {
    if (confirm("<?=$html_title?> 하시겠습니까?")) {	
        if ($("#m_id").val() == null || $("#m_id").val() == "") {
            alert("사번을 입력해주세요");
            return false;
        }
        
        <?php if ($m == "") { ?>
        if ($("#m_pw").val() == null || $("#m_pw").val() == "") {
            alert("패스워드를 입력해주세요");
            return false;
        }
        <?php } ?>
        
        if($("#m_mail").val() == null || $("#m_mail").val() == "") {
            alert("메일을 입력해주세요");
            return false;
        }
        
        if (!checkemail()) return false;    // 메일 유효성 확인
        
        roles();
        
        return;
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

function groupAppend(){
    $('#group_modal').modal({
                  show: 'true'
     });
}

function groupDelete(){
    if(g_group_seq == ''){
        alert('선택된 그룹명이 없습니다.');
        return;
    }

    if(confirm('정말 삭제하시겠습니까?')){
        $('#seq').val(g_group_seq);
        $('#cmd').val('delete');
        $('#frm').submit();
    }
}

function groupInsert(){
    if($('#g_name').val()==''){
        alert('관리자 그룹명을 입력해 주세요.');
        return;
    }
    $('#frm').submit();
}

function groupClick(val){

    location.href = 'administration_auth.php?seq=' + val;
   
}

function authSave(){
    var auth_array = [];
    if(g_group_seq == ''){
        alert('선택된 그룹명이 없습니다.');
        return;
    }
    
    $("input[id^='auth_read_']").each(function (i, el) {
        var arr = $(this).attr('id').split("_");
        var read = 'N';
        var write = 'N';
        var del = 'N';
        if($(this).is(':checked')){
            read = 'Y';
        }
        if($('#auth_write_' + arr[2]).is(':checked')){
            write = 'Y';
        }
        if($('#auth_del_' + arr[2]).is(':checked')){
            del = 'Y';
        }

        auth_array.push({'menu':arr[2], 'read':read, 'write':write, 'del':del});
    });
   
    $('#auth_array').val(JSON.stringify(auth_array));
    
    $('#seq').val(g_group_seq);
    $('#cmd').val('auth');
    $('#frm').submit();

}
</script>


<script type="text/javascript">
$(document).ready(function(){
   var token = get_ajax_token();
   $('#token').val(token);
   
   //menu 권한 세팅하기
  for(var i=0;i<g_auth_array.length;i++){
    $('#auth_read_' + g_auth_array[i]['G_MENU']).prop('checked', g_auth_array[i]['G_AUTH_READ'] == 'Y'?true:false);
    $('#auth_write_' + g_auth_array[i]['G_MENU']).prop('checked', g_auth_array[i]['G_AUTH_WRITE'] == 'Y'?true:false);
    $('#auth_del_' + g_auth_array[i]['G_MENU']).prop('checked', g_auth_array[i]['G_AUTH_DEL'] == 'Y'?true:false);
   };
  
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

function allCheck(that, id, code){
    if(that.checked){
        $("input[id^='auth_"+id+"_"+code+"']").each(function (i, el) {
            $(this).prop('checked', true);
        });
       that.checked = true;
    } else {
        $("input[id^='auth_"+id+"_"+code+"']").each(function (i, el) {
            $(this).prop('checked', false);
        });
        that.checked = false;
    }
}
</script>


<?php
include_once('./_tail.php');
?>