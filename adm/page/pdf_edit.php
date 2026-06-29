<?php
$board['BC_EDITOR'] = "smarteditor2";
include_once('./_common.php');
include_once(P1_EDITOR_LIB);

if ($m == "") {
    $html_title = "등록";
} else if ($m == "u") {
    $in = get_pdf($seq);
    
    if (!$in['S_SEQ'])
        alert('존재하지 않는 정보입니다.');
    
    $html_title = "수정";
    
    $in['S_SEQ']     = get_text($in['S_SEQ']);                        # 게시판번호
    $in['S_TYPE']   = get_text($in['S_TYPE']);                      # 카테고리
    $in['S_TITLE']     = get_text($in['S_TITLE']);                        # 대상
    $in['S_EXPS_YN'] = get_text($in['S_EXPS_YN']);                    # 상단고정
    $in['S_NOTI_YN'] = get_text($in['S_NOTI_YN']);                    # 노출여부
	$in['S_BRIEF'] = get_text($in['S_BRIEF']);						#요약내용

} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

$content = get_text($in['S_CONT'], 0);

$editor_html = editor_html('s_cont', $content, true); # 내용
$editor_js = '';
$editor_js .= get_editor_js('s_cont', true);
$editor_js .= chk_editor_js('s_cont', true);

// 카테고리
$sql1 = "select * from  {$p1['t_pds_category_table']} where c_use='Y' ";
$result1 = sql_query($sql1);


$p1['title']    = 'PDF게시판';
include_once('./_head.php');

?>
<form name="frm" method="post" action="<?php echo P1_PAGE_URL ?>/pdf_insert.php" onsubmit="return send();" enctype="multipart/form-data">
<input type="hidden" name="m" value="<?php echo $m ?>">
<input type="hidden" id="upload" name="upload" value="pdf">
<?php if ($in['S_SEQ']) { ?>
<input type="hidden" name="seq" value="<?=$in['S_SEQ']?>">
<?php } ?>
    <fieldset>
    <legend class="hid1">PDF게시판 관리</legend>
        <table class="colTable">
            <tr>
                <th>제목 (최대 100자)</th>
                <td>
                    <label for="s_field"><input type="text" id="s_title" name="s_title" value="<?=$in['S_TITLE']?>" maxlength="100" style="width:600px" />
                </td>
            </tr>
            <tr>
                <th>카테고리</th>
                <td>
                    <select name="s_type" id="s_type">
						<option value="">카테고리 선택</option>
                        <?php
						 for ($i=0; $row1=sql_fetch_array($result1); $i++) {
							 
							 if($in['S_TYPE'] == $row1['C_NAME']) $sel = "selected";
							 else $sel = "";

							echo "<option value='".$row1['C_NAME']."' ".$sel.">".$row1['C_NAME']."</option>";
						}
                        ?>
                    </select>
                </td>
            </tr>
			<tr>
                <th>리스트 이미지</th>
                <td>
                    <label for="list_img"><input type="file" id="s_attach2" name="s_attach2"></label>
                    <div class="di" id="di2">
                        <?php
                        if ($in['S_FILE1']) {
                            echo $in['S_FILE1'];
                        ?>
                        <button class="btn-small btn-inverse" type="button" onclick="location.href='./pdf_file_delete.php?seq=<?=$in['S_SEQ']?>&gubun=1'">X</button>
                        <?php
                        }
                        ?>
                    </div>
                </td>
            </tr>
			<tr>
                <th>첨부파일</th>
                <td>
                    <label for="list_img"><input type="file" id="s_attach3" name="s_attach3"></label>
                    <div class="di" id="di3">
                        <?php
                        if ($in['S_FILE2']) {
                            echo $in['S_FILE2'];
                        ?>
                        <button class="btn-small btn-inverse" type="button" onclick="location.href='./pdf_file_delete.php?seq=<?=$in['S_SEQ']?>&gubun=2'">X</button>
                        <?php
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>내용</th>
                <td>
                    <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
                </td>
            </tr>      
			<tr>
                <th>요약 내용<br/>(최대 200자)</th>
                <td>
                    <textarea name="s_brief" id="s_brief" style="height:100px; width:100%" rows="20" cols="100" maxlength="200"><?=$in['S_BRIEF']?></textarea>
                </td>
            </tr>
            <tr>
                <th>상단고정</th>
                <td>
                    <?php
                    echo radio_selected("Y", $in['S_EXPS_YN'], "고정", "s_exps_yn");
                    echo radio_selected("N", $in['S_EXPS_YN'], "비고정", "s_exps_yn");
                    ?>
                </td>
            </tr>
            <tr>
                <th>노출여부</th>
                <td>
                    <?php
                    echo radio_selected("Y", $in['S_NOTI_YN'], "노출", "s_noti_yn");
                    echo radio_selected("N", $in['S_NOTI_YN'], "비노출", "s_noti_yn");
                    ?>
                </td>
            </tr>
        </table>
        <!-- both button -->
        <div class="bothButton">
            <div class="fl">
                <button class="btn btn-inverse" type="button" onclick="fnGoView('<?php echo P1_PAGE_URL ?>/pdf_list.php');">목록</button>
            </div>
            <div class="fr">
                <?php if ($m == "u") { ?>
                <input type="submit" class="btn btn-primary" value="수정">
                <button class="btn btn-inverse" type="button" onclick="deletes('<?php echo P1_PAGE_URL ?>/pdf_del.php');">삭제</button>
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
        <?php echo $editor_js;?> // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   
        oEditors.getById["s_cont"].exec("UPDATE_CONTENTS_FIELD",[]);        

       
        if($("#s_title").val() == null || $("#s_title").val() == ""){
            alert("제목을 입력해주세요");
            return false;
        }
		
		if($("#s_type").val() == null || $("#s_type").val() == ""){
            alert("카테고리를 입력해주세요");
            return false;
        }
        
        
        return;
    }
    return false;
}
	
function delfile(filename,index){
    var params = "";
    params =  "index=${boardBean.seq}&file"+index+"="+filename;
    if(confirm("파일을 삭제 하시겠습니까?")){
        $.ajax({
            type: "POST",
            url: "/manager/forums/file_del.do",
            data: params,
            success: function(data){
                alert("삭제되었습니다.");
                $("#di"+index).text('');
                return;
            },
            error:function(request,status,error){
                alert("요청이 지연되었습니다. 잠시 후 다시 시도하세요.");
                return false;
            }
        });	
    }
    return false;
}
</script>
<?php
include_once('./_tail.php');
?>