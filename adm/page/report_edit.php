<?php
include_once('./_common.php');

//권한체크
$auth = get_check_admin_auth($member); //alert('권한이 없습니다.', P1_URL);
if($auth['read'] != 'Y') {
    alert('권한이 없습니다.', '/adm/page/login.php');
    exit;
}
if($_SESSION['ss_m_id'] != 'admin_et')
{
    echo "<script>alert('해당 메뉴의 접근권한이 없습니다.'); history.back(); </script>";
    exit;
}


if ($m == "") {
    $html_title = "등록";
} else if ($m == "u") {
    $re = get_report($seq);

    if (!$re['A_SEQ'])
        alert('존재하지 않는 정보입니다.');

    $html_title = "수정";

    $re['A_SEQ']    = get_text($re['A_SEQ']);                     # 게시판번호
    $re['A_NAME']   = get_text($re['A_NAME']);                    # 이름
    $re['A_DATE']   = date("Y-m-d", strtotime($re['A_DATE']));    # 문의일
    $re['A_TEL']    = get_text($re['A_TEL']);                     # 전화번호
    $re['A_MAIL']   = get_text($re['A_MAIL']);                    # 이메일
    $re['A_CONT']   = get_text($re['A_CONT']);                    # 제보내용
    $re['A_RE_YN']  = get_text($re['A_RE_YN']);                   # 확인여부
    $re['A_RDATE']  = get_text($re['A_RDATE']);                   # 확인일
    $re['A_ANSWER'] = get_text($re['A_ANSWER']);                  # 답변

    /* 댓글 정보 */
    $sql_co = " select *
                  from {$p1['t_report_table']} a,
                       {$p1['t_comment_table']} b
                 where a.a_seq = '{$re['A_SEQ']}'
                   and a.a_seq = b.report_no order by b.wdate asc ";
    $res_co = sql_query($sql_co);
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

$p1['title']    = '윤리경영제보';
$p1['subtitle'] = '문의관리';
include_once('./_head.php');

$colspan = 4;


$sql = " select * from T_BOARD_FILES where B_SEQ = ".$re['A_SEQ']." AND FI_INDEX = '99' order by FI_SORT asc ";
$file_res1 = sql_query($sql);


?>
<link href="/adm/css/print.css" rel="stylesheet" type="text/css" media="print" />
<div id="printJS-form">
<form name="frm" method="post" action="<?php echo P1_PAGE_URL ?>/report_insert.php" onsubmit="return update();" enctype="multipart/form-data">
<input type="hidden" name="m" value="<?php echo $m ?>">
<?php if ($re['A_SEQ']) { ?>
<input type="hidden" name="seq" value="<?=$re['A_SEQ']?>">
<?php } ?>
    <fieldset>
    <legend class="hid1" id="skip_6"><?=$p1['title']?> 등록 및 수정</legend>
        <table class="colTable">
            <colgroup>
                <col width="139" />
                <col width="206" />
                <col width="139" />
                <col width="207" />
            </colgroup>
            <tr>
                <th>제보자 성명</th>
                <td><?=Decrypt($re['A_NAME'])?></td>
                <th>문의일</th>
                <td><?=$re['A_DATE']?></td>
            </tr>
            <tr>
                <th>연락처</th>
                <td><?=Decrypt($re['A_TEL'])?></td>
                <th>이메일</th>
                <td><?=Decrypt($re['A_MAIL'])?><input type="hidden" name="a_mail_check" value="<?=$re['A_MAIL']?>"></td>
            </tr>
            <tr>
                <th>제목</th>
                <td><?=$re['A_TITLE']?></td>
                <th>발생장소</th>
                <td><?=$re['A_PLACE']?></td>
            </tr>
            <tr>
                <th>발생시기</th>
                <td><?=$re['A_PLACE_DT']?></td>
                <th>차단여부</th>
                <td>
                    <label for="fail1">차단  <input type="radio" id="fail1" name="a_fail_yn" value="Y" <?if($re['A_PASSWD_FAIL'] >= 5){?>checked <?}?> > </label>
                    <label for="fail2">해제  <input type="radio" id="fail2" name="a_fail_yn" value="N" <?if($re['A_PASSWD_FAIL'] < 5){?>checked <?}?> >  </label>
                </td>
            </tr>
            <tr>
                <th>제보대상 및 <br/>제보대상자와의 관계</th>
                <td colspan="3"><?=$re['A_RELATION']?></td>
            </tr>
            <tr>
                <th>제보 내용</th>
                <td colspan="3"><?=nl2br($re['A_CONT'])?></td>
            </tr>

            <tr>
                <th>파일 첨부</th>
                <td colspan="3">
                <?
                $nFile = 0;
                    for ($i=0; $row=sql_fetch_array($file_res1); $i++) {
                ?>
                    <p style="margin:0;"><a href="<?=$row['FI_NAME']?>" target="_blank"><?=($i+1)?>.<?=$row['FI_ORG']?></a></p>
                <?php
                $nFile = $nFile + 1;
            }
                if($nFile == 0) {
                ?>
                첨부파일 없음
                <?php } ?>
                </td>
            </tr>



            <!-- <tr>
                <th>파일 첨부</th>
                <?php if ($re['A_FILE1']) { ?>
                <td colspan="3"><a href="<?=$re['A_FILE1']?>" target="_blank"><?=$re['A_SYSFILE1']?></a></td>
                <?php } else { ?>
                <td colspan="3">첨부파일 없음</td>
                <?php } ?>
            </tr> -->
            <tr>
                <th>추가 내용 01</th>
                <td colspan="3"><?=nl2br($re['A_CONT_ADD1'])?></td>
            </tr>
            <tr>
                <th>추가 내용 02</th>
                <td colspan="3"><?=nl2br($re['A_CONT_ADD2'])?></td>
            </tr>
            <tr>
                <th>추가 내용 03</th>
                <td colspan="3"><?=nl2br($re['A_CONT_ADD3'])?></td>
            </tr>
            <tr>
                <th>확인여부</th>
                <td colspan="3">
                    <?php
                    echo radio_selected("Y", $re['A_RE_YN'], "확인", "a_re_yn");
                    echo radio_selected("N", $re['A_RE_YN'], "미확인", "a_re_yn");
                    ?>
                </td>
            </tr>
            <?php if ($re['A_RE_YN'] == "Y") { ?>
            <tr>
                <th>확인일</th>
                <td colspan="3"><?=$re['A_RDATE']?></td>
            </tr>
            <?php } ?>
            <tr>
                <th>답변</th>
                <td colspan="3"><textarea style="width:600px;" rows="5" cols="500" id="a_answer" name="a_answer"><?=$re['A_ANSWER']?></textarea></td>
            </tr>
        </table>
        <!-- both button -->
        <div class="bothButton">
            <div class="fl">
                <button id="skip_1" class="btn btn-inverse" type="button" onclick="fnGoView('<?php echo P1_PAGE_URL ?>/report.php');">목록</button>
            </div>
            <div class="fr">
                <?if($auth['write'] == 'Y') {?>
                    <input id="skip_2"  type="submit" class="btn btn-primary" value="확인">
                <?}?>

                <?if($auth['read'] == 'Y') {?>
                    <button id="skip_3"  class="btn btn-inverse" type="button" onclick="prints();">프린트</button>

                <?}?>
                <?if($auth['del'] == 'Y') {?>
                <button id="skip_4"  class="btn btn-inverse" type="button" onclick="deletes('<?php echo P1_PAGE_URL ?>/report_del.php');">삭제</button>
                <?}?>
            </div>
        </div>
    </fieldset>
</form>

<!-- 댓글 -->
<form name="frm_re" method="post" action="<?php echo P1_PAGE_URL ?>/reply_insert.php" onsubmit="return replyUpdate();" enctype="multipart/form-data">
<?php if ($re['A_SEQ']) { ?>
<input type="hidden" name="seq" value="<?=$re['A_SEQ']?>">
<?php } ?>
<input type="hidden" name="cmd" id="cmd" value="">
<input type="hidden" name="no" id="no" value="">

    <fieldset>
        <p style="font-size:13px; font-weight:bold; color:#00f; margin:10px 0;">※ 댓글 </p>
        <table class="colTable">
            <thead>
                <colgroup>
                    <col width="33%" />
                    <col width="33%" />
                    <col width="33%" />
                </colgroup>
            </thead>
            <tbody>
                <tr>
                    <th scope="col" class="post_writer">작성자</th>
                    <th scope="col" class="post_title">내용</th>
                    <th scope="col" class="post_wdate">등록일</th>
                    <th scope="col" class="post_wdate"></th>
                </tr>
                <?php
                for ($i=0; $row=sql_fetch_array($res_co); $i++) {
                ?>
                    <tr>
                        <td class="post_title"><?=Decrypt($row['writer'])?></td>
                        <td class="post_writer"><?=$row['comment']?></td>
                        <td class="post_wdate"><?=$row['wdate']?></td>
                        <td class="post_wdate">
                        <?if($auth['write'] == 'Y') {?>
                            <button id="skip_7" class="btn btn-default" type="button" onclick="javascript:commentEdit('<?=$row['no']?>');">수정</button>
                        <?}?>
                        <?if($auth['del'] == 'Y') {?>
                            <button id="skip_8"  class="btn btn-default" type="button" onclick="javascript:commentDel('<?=$row['no']?>');"">삭제</button>
                        <?}?>
                        </td>
                    </tr>
                <?php
                }
                if ($i == 0)
                    echo "<tr><td colspan=\"".$colspan."\">자료가 없습니다.</td></tr>";
                ?>
                <tr id="skip_9">
                    <th>댓글 내용</th>
                    <td colspan="3"><textarea id="comment" name="comment" style="width:600px;" rows="5" cols="500"></textarea></td>
                </tr>
            </tbody>
        </table>
        <!-- both button -->
        <div class="bothButton">
            <div class="fr">
            <?if($auth['write'] == 'Y') {?>
                <input id="skip_5" type="submit" class="btn btn-primary" value="댓글등록">
                <?}?>
            </div>
        </div>
    </fieldset>
</form>
</div>
<div id="printarea"></div>

<!-- 목록으로 돌아가기 -->
<form name="golist" action="" method="post">
    <input type="hidden" name="page" value="<?=$page?>">
    <input type="hidden" name="bc_code" value="<?=$bc_code?>">
    <input type="hidden" name="search_type" value="<?=$search_type?>">
    <input type="hidden" name="search_txt" value="<?=$search_txt?>">
    <input type="hidden" name="re_yn" value="<?=$re_yn?>">
</form>

<link rel="stylesheet" href="/static/css/print.min.css">
<script src="/static/js/print.min.js"></script>

<script type="text/javascript">
function update() {
    if($(':radio[name="a_re_yn"]:checked').val() == undefined){
        alert("확인 여부를 선택하세요.");
        return false;
    }

    // if($("#a_answer").val() == null || $("#a_answer").val() == ""){
    //     alert("답변을 입력해주세요.");
    //     return false;
    // }

    return;
}

function replyUpdate() {
    if($("#comment").val() == null || $("#comment").val() == ""){
        alert("댓글 답변을 입력해주세요.");
        return false;
    }

    return;
}

var token = '';

 $(document).ready(function(){
    token = get_ajax_token();
 });

function commentEdit(val){

    $.ajax({
                          type: "POST",
                          url: 'report_comment_proc.php',
                          data: {
                              'seq':val,
                              'cmd':'list',
                              'token':token,
                          },
                          dataType: 'json',
                          timeout: 60000,
                          cache: false,
                          crossDomain: false,
                          error: function (request, error) {
                              console.log(error + '');
                          },
                          success: function (json) {
                              if(json.success){
                                   $('#comment').val(json.comment);
                                   $('#cmd').val('update');
                                   $('#no').val(val);
                              }
                          }
            });

}
function commentDel(val){

    $.ajax({
                          type: "POST",
                          url: 'report_comment_proc.php',
                          data: {
                              'seq':val,
                              'cmd':'delete',
                              'token':token,
                          },
                          dataType: 'json',
                          timeout: 60000,
                          cache: false,
                          crossDomain: false,
                          error: function (request, error) {
                              console.log(error + '');
                          },
                          success: function (json) {
                              if(json.success){
                                     alert('삭제되었습니다.');
                                    document.location.reload();
                              }
                          }
            });

}


// function prints() {

//     printJS({
//         printable: 'printJS-form',
//         type: 'html',
//         ignoreElements: ['skip_1','skip_2','skip_3','skip_4','skip_5','skip_6','skip_7','skip_8','skip_9']
//     })

// }

function prints(){
    var initBody;
    window.onbeforeprint = function(){
        initBody = document.body.innerHTML;
        document.getElementById('printarea').innerHTML = document.getElementById('printJS-form').innerHTML;
    };
    window.onafterprint = function(){
        document.body.innerHTML = initBody;
    };

    window.print();
    return false;
}

</script>
<?php
include_once('./_tail.php');
?>