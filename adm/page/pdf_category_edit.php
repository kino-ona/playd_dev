<?php
$board['BC_EDITOR'] = "smarteditor2";
include_once('./_common.php');
include_once(P1_EDITOR_LIB);

if ($m == "") {
    $html_title = "등록";
} else if ($m == "u") {
    //$in = get_incident($seq);
    
    /*if (!$in['S_SEQ'])
        alert('존재하지 않는 정보입니다.');
	*/
	$sql="select * from  {$p1['t_pds_category_table']} where C_SEQ='".$no."' ";
	$in = sql_fetch($sql);
    
    $html_title = "수정";
    
    $in['C_SEQ']     = get_text($in['C_SEQ']);                        # 게시판번호
    $in['C_NAME']   = get_text($in['C_NAME']);                      # 카테고리명
    $in['C_USE']     = get_text($in['C_USE']);                        # 노출여부
    $in['C_DATE']     = get_text($in['C_DATE']);                        # 등록일
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

$p1['title']    = 'PDF카테고리관리';
include_once('./_head.php');

?>

<form name="frm" method="post" action="<?php echo P1_PAGE_URL ?>/pdf_category_insert.php" onsubmit="return send();" enctype="multipart/form-data">
<input type="hidden" name="m" value="<?php echo $m ?>">
<?php if ($in['C_SEQ']) { ?>
<input type="hidden" name="seq" value="<?=$in['C_SEQ']?>">
<?php } ?>
    <fieldset>
    <legend class="hid1">PDS 게시판 카테고리 관리</legend>
        <table class="colTable">
            <tr>
                <th>카테고리명</th>
                <td>
                    <label for="s_field"><input type="text" id="c_name" name="c_name" value="<?=$in['C_NAME']?>" maxlength="100" style="width:600px" />
                </td>
            </tr>
            <tr>
                <th>노출여부</th>
                <td>
                    <?php
                    echo radio_selected("Y", $in['C_USE'], "노출", "c_use");
                    echo radio_selected("N", $in['C_USE'], "비노출", "c_use");
                    ?>
                </td>
            </tr>
        </table>
        <!-- both button -->
        <div class="bothButton">
            <div class="fl">
                <button class="btn btn-inverse" type="button" onclick="fnGoView('<?php echo P1_PAGE_URL ?>/pdf_category_list.php');">목록</button>
            </div>
            <div class="fr">
                <?php if ($m == "u") { ?>
                <input type="submit" class="btn btn-primary" value="수정">
                <button class="btn btn-inverse" type="button" onclick="deletes('<?php echo P1_PAGE_URL ?>/pdf_category_del.php');">삭제</button>
                <?php } ?>
                <?php if ($m == "") { ?>
                <input type="submit" class="btn btn-inverse" value="등록">
                <?php } ?>
            </div>
        </div>
    </fieldset>
</form>
<!-- 목록으로 돌아가기 -->
<form name="golist" action="" method="post">
    <input type="hidden" name="page" value="<?=$page?>">
    <input type="hidden" name="search_type" value="<?=$search_type?>">
    <input type="hidden" name="search_txt" value="<?=$search_txt?>">
    <input type="hidden" name="obj" value="<?=$obj?>">
    <input type="hidden" name="job" value="<?=$job?>">
</form>

<script type="text/javascript">
//등록 & 수정
function send() {
    if(confirm("<?=$html_title?> 하시겠습니까?")){
       
        if($("#c_name").val() == null || $("#c_name").val() == ""){
            alert("카테고리명을 입력해주세요");
            return false;
        }
        return;
    }
    return false;
}
	
</script>
<?php
include_once('./_tail.php');
?>