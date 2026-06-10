<?php
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가
?>
<form name="frm" method="post" action="<?php echo P1_PAGE_URL ?>/qna_insert.php" onsubmit="return update();" enctype="multipart/form-data">
<input type="hidden" name="m" value="<?php echo $m ?>">
<input type="hidden" id="a_code" name="a_code" value="<?=($m == "u") ? $write['A_CODE'] : $bc_code?>">
<?php if ($write['A_SEQ']) { ?>
<input type="hidden" name="seq" value="<?=$write['A_SEQ']?>">
<?php } ?>
    <fieldset>
    <legend class="hid1"><?=$p1['title']?> 등록 및 수정</legend>
        <table class="colTable">
            <colgroup>
                <col width="139" />
                <col width="206" />
                <col width="139" />
                <col width="207" />
            </colgroup>
			<tr>
                <th>업체명</th>
                <td><?=$write['A_CORP_NAME']?></td>
                <th>문의일</th>
                <td><?=$write['A_DATE']?></td>
            </tr>
            <tr>
                <th>이름</th>
                <td><?=$write['A_NAME']?></td>
                <th>연락처</th>
                <td><?=$write['A_TEL']?></td>
            </tr>
            <tr>
                <th>이메일</th>
                <td><?=$write['A_MAIL']?></td>
                <th>홈페이지 주소</th>
                <td><?=$write['A_URL']?></td>
            </tr>
            <tr>
                <th>예산</th>
                <td><?=$write['A_TYPE_GUBUN1']?></td>
                <th>광고목적</th>
                <td><?=$write['A_TYPE_GUBUN2']?></td>
            </tr>
			<tr>
                <th>광고매체</th>
                <td><?=$write['A_TYPE_GUBUN3']?></td>
                <th>문의제목</th>
                <td><?=($write['A_TITLE']) ? $write['A_TITLE'] : $p1['title']?></td>
            </tr>
			<!--
            <tr>
                <th>관심상품</th>
                <td colspan="3"><?=$write['A_TYPE']?></td>
            </tr>
			<tr>
                <th>문의제목</th>
                <td colspan="3"><?=($write['A_TITLE']) ? $write['A_TITLE'] : $p1['title']?></td>
            </tr>
			-->
            <tr>
                <th>문의내용</th>
                <td colspan="3"><?=($write['A_CONT']) ? nl2br($write['A_CONT']) : $p1['title']?></td>
            </tr>
            <tr>
                <th>상담내용</th>
                <td colspan="3">
                    <textarea rows="4" cols="50" style="width:600px; height:200px" id="rcontents" name="a_rcont"><?=$write['A_RCONT']?></textarea>
                </td>
            </tr>
            <tr>
                <th>담당자</th>
                <td id="rwriter" colspan="3"><?=($write['A_RWRITER']) ? $write['A_RWRITER'] : $member['M_ID']?></td>
            </tr>
            <tr>
                <th>확인여부</th>
                <td colspan="3">
                    <?php
                    echo radio_selected("Y", $write['A_RE_YN'], "확인", "a_re_yn");
                    echo radio_selected("N", $write['A_RE_YN'], "미확인", "a_re_yn");
                    ?>
                </td>
            </tr>
            <?php if ($write['A_RE_YN'] == "Y") { ?>
            <tr>
                <th>확인일</th>
                <td colspan="3"><?=$write['A_RDATE']?></td>
            </tr>
            <?php } ?>
        </table>
        <!-- both button -->
        <div class="bothButton">
            <div class="fl">
                <button class="btn btn-inverse" type="button" onclick="fnGoView('<?php echo $list_href ?>');">목록</button>
            </div>
            <div class="fr">
                <input type="submit" class="btn btn-primary" value="확인">
                <button class="btn btn-inverse" type="button" onclick="deletes('<?php echo $del_href ?>');">삭제</button>
            </div>
        </div>
    </fieldset>
</form>
<!-- 목록으로 돌아가기 -->
<form name="golist" action="" method="post">
    <input type="hidden" name="page" value="<?=$page?>">
    <input type="hidden" name="bc_code" value="<?=$bc_code?>">
    <input type="hidden" name="search_type" value="<?=$search_type?>">
    <input type="hidden" name="search_txt" value="<?=$search_txt?>">
    <input type="hidden" name="re_yn" value="<?=$re_yn?>">
</form>
<script type="text/javascript">
function update() {
    if($(':radio[name="a_re_yn"]:checked').val() == undefined){
        alert("확인 여부를 선택하세요.");
        return false;
    }
    if($("#rcontents").val() == null || $("#rcontents").val() == ""){
        alert("상담내용을 입력해주세요");
        return false;
    }
    
    /* if($(':radio[name="re_yn"]:checked').val() == 'Y'){
        if(confirm("답변 메일을 발송하시겠습니까?")){
            document.f.action = "/manager/advertising/ask_update.do";
            document.f.submit();
            return;
        }
    }else if($(':radio[name="re_yn"]:checked').val() == 'N'){
        if(confirm("메일 발송없이 저장하시겠습니까?")){
            document.f.action = "/manager/advertising/ask_update.do";
            document.f.submit();
            return;	
        }
    } */
    
    return;
}
</script>