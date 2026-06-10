<?php
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가
?>
<form name="frm" method="post" action="<?php echo P1_PAGE_URL ?>/post_insert.php" onsubmit="return send();" enctype="multipart/form-data">
<input type="hidden" name="m" value="<?php echo $m ?>">
<input type="hidden" id="b_code" name="b_code" value="<?=($m == "u") ? $write['B_CODE'] : $bc_code?>">
<?php if ($board['BC_SHARE_USE_YN'] == 1) { ?>
<input type="hidden" id="bc_code" name="bc_code" value="<?=($m == "u") ? $write['B_CODE'] : $bc_code?>">
<?php } ?>
<input type="hidden" id="upload" name="upload" value="<?=$board['BC_UPLOAD_NM']?>">
<?php if ($write['B_SEQ']) { ?>
<input type="hidden" name="seq" value="<?=$write['B_SEQ']?>">
<?php } ?>
    <fieldset>
    <legend class="hid1"><?=$p1['title']?> 등록 및 수정</legend>
        <table class="colTable">
            <?php if ($board['BC_SHARE_USE_YN'] == 1) { ?>
            <tr>
                <th>성공사례 불러오기</th>
                <td>
                    <select name="b_share_seq" id="b_share_seq" style="width:100%">
                        <option value="">성공사례 선택</option>
                        <?php
                        for ($i=0; $i<count($share); $i++) {
                            echo option_selected($share[$i]['b_seq'], ($b_share_seq) ? $b_share_seq : $write['B_SHARE_SEQ'], $share[$i]['title']);
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>해시태그</th>
                <td>
                    <div class="input_wrap keyword_wrap">
                        <input type="text" id="hash_tag" name="hash_tag" class="keyword in_btn" maxlength="15" placeholder="해시태그 입력 후 엔터">
                        <button type="button" class="btn btn_regist type3 search" onclick="return hash_tag_push();">등록</button>
                    </div>
                    <div class="hash_tag_list">
                        <?php
                        $sql_rec = " select *
                                       from {$p1['t_board_recom_table']}
                                      where br_b_seq = '{$write['B_SEQ']}' ";
                        $res_rec = sql_query($sql_rec);
                        while($row_rec=sql_fetch_array($res_rec)) {
                        ?>
                        <p><?=$row_rec['BR_WORD']?><input type="hidden" name="br_word[]" value="<?=$row_rec['BR_WORD']?>"><span class="btn_delete" onclick="recom_del(this);">삭제</span></p>
                        <?php
                        }
                        ?>
                    </div>
                    <p class="info_txt">※ 해시태그는 최대 5개까지 등록 가능합니다.</p>
                    <script>
                    // 해시태그 삭제
                    function recom_del(that) {
                        jQuery(that).parent().remove();
                    }
                    
                    // 해시태그 등록
                    function hash_tag_push() {
                        if ($("#hash_tag").val().length < 2) {
                            alert("2글자 이상 입력해주세요.");
                        } else if ($("#hash_tag").val().length > 10) {
                            alert("최대 15글자 까지 입력가능합니다.");
                        } else if ($(".hash_tag_list > p").length >= 5) {
                            alert("해시태그는 최대 5개까지 등록 가능합니다.");
                        } else {                            
                            $(".hash_tag_list").append('<p>' + $("#hash_tag").val() + '<input type="hidden" name="br_word[]" value="' + $("#hash_tag").val() + '"><span class="btn_delete" onclick="recom_del(this);">삭제</span></p>');
                            $("#hash_tag").val("");
                        }
                        return false;
                    }
                    
                    $("#hash_tag").keydown(function(key) {
                        if (key.keyCode == 13) {
                            if ($(this).val().length < 2) {
                                alert("2글자 이상 입력해주세요.");
                            } else if ($(this).val().length > 10) {
                                alert("최대 15글자 까지 입력가능합니다.");
                            } else if ($(".hash_tag_list > p").length >= 5) {
                                alert("해시태그는 최대 5개까지 등록 가능합니다.");
                            } else {                            
                                $(".hash_tag_list").append('<p>' + $(this).val() + '<input type="hidden" name="br_word[]" value="' + $(this).val() + '"><span class="btn_delete" onclick="recom_del(this);">삭제</span></p>');
                                $(this).val("");
                            }
                            return false;
                        }
                    });
                    </script>
                </td>
            </tr>
            <?php } ?>
            <?php if ($board['BC_CORP_NAME_USE_YN'] == 1) { ?>
            <tr>
                <th>회사명</th>
                <td>
                    <label for="b_corp_name"><input type="text" id="b_corp_name" name="b_corp_name" value="<?=$write['B_CORP_NAME']?>"></label>
                </td>
            </tr>
            <?php } ?>
            <?php if ($board['BC_NAME_USE_YN'] == 1) { ?>
            <tr>
                <th>대표자</th>
                <td>
                    <label for="b_name"><input type="text" id="b_name" name="b_name" value="<?=$write['B_NAME']?>"></label>
                </td>
            </tr>
            <?php } ?>
            <?php if ($board['BC_SITE_USE_YN'] == 1) { ?>
            <tr>
                <th>사이트 구분</th>
                <td>
                    <select name="b_site" id="b_site">
                        <option value="">전체사이트</option>
                        <?php
                        echo option_selected("플레이디", $write['B_SITE'], "플레이디");
                        echo option_selected("메이블", $write['B_SITE'], "메이블");
                        echo option_selected("인스타그램", $write['B_SITE'], "인스타그램");
                        echo option_selected("페이스북", $write['B_SITE'], "페이스북");
                        echo option_selected("네이버블로그", $write['B_SITE'], "네이버블로그");
						?>
                    </select>
                </td>
            </tr>
			<tr>
				<th>수집시 삭제 여부</th>
				<td>
					<select name="b_sns_del_yn">
						<option value="Y" <?php if($write['B_SNS_DEL_YN']=="Y" || $write['B_SNS_DEL_YN']=="") echo " selected";?>>삭제</option>
						<option value="N" <?php if($write['B_SNS_DEL_YN']=="N") echo " selected";?>>삭제안함</option>
					</select>
				</td>
			</tr>
            <?php } ?>
            <?php if ($board['BC_TYPE_USE_YN'] == 1) { ?>
            <tr>
                <th>구분</th>
                <td>
                    <select name="b_type" id="b_type" required>
                        <option value="">구분선택</option>
                        <?php
                        switch($bc_code) {
                            case "nsmexp":
                                $type_arr = array("광고 트렌드", "광고전략", "광고운영TIP", "시스템활용", "Trend Research");
                                break;
                            default:
                                $type_arr = array("보도기사", "공지", "안내");
                                break;
                        }
                        
                        foreach($type_arr as $k=>$v) {
                            echo option_selected($v, $write['B_TYPE'], $v);
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <th>제목 (최대 100자)</th>
                <td>
                    <label for="b_title"><input type="text" id="b_title" name="b_title" value="<?=$write['B_TITLE']?>" maxlength="100" style="width:600px"></label>
                </td>
            </tr>
			<?php if ($write['B_SITE']=="네이버블로그" || $write['B_SITE']=="페이스북" || $write['B_SITE']=="인스타그램") { ?>
			<tr>
				<th>게시물 바로가기</th>
                <td>
                    <label for="list_img"></label>
                    <div class="di" id="b_linkurl">
						<a href="<?php echo $write[B_LINKURL]; ?>" target="_blank">바로가기</a>
						<input type="text" name="b_linkurl" value="<?php echo $write[B_LINKURL]; ?>" style="width:90%;" />
					</div>
				</td>
			</tr>
			<?php } ?>
            <tr>
                <th>리스트 이미지</th>
                <td>
                    <label for="list_img"><input type="file" id="b_attach2" name="b_attach2"></label>
                    <div class="di" id="di2">
                        <?php
                        if ($write['B_FILE2']) {
                            echo $write['B_FILE2'];
                        ?>
                        <button class="btn-small btn-inverse" type="button" onclick="location.href='./post_file_delete.php?seq=<?=$write['B_SEQ']?>'">X</button>
                        <?php
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <?php if ($board['BC_DATE_USE_YN'] == 1) { ?>
            <tr>
                <th>년/월</th>
                <td>
                    <?php
                    echo date_ym_select(conv_date_format("Y-m", ($m == "u") ? $write['B_YEAR']."-".$write['B_MONTH'] : "0000"), "date");
                    ?>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <th>내용</th>
                <td>
                    <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
                </td>
            </tr>
            <tr>
                <th>요약 내용<br/>(최대 200자)</th>
                <td>
                    <textarea name="b_brief" id="b_brief" style="height:100px; width:100%" rows="20" cols="100" maxlength="200"><?=$write['B_BRIEF']?></textarea>
                </td>
            </tr>
            <?php if ($board['BC_EXPS_USE_YN'] == 1) { ?>
            <tr>
                <th>상단고정</th>
                <td>
                    <?php
                    echo radio_selected("Y", $write['B_EXPS_YN'], "고정", "b_exps_yn");
                    echo radio_selected("N", $write['B_EXPS_YN'], "비고정", "b_exps_yn");
                    ?>
                </td>
            </tr>
            <?php } ?>
            <?php if ($board['BC_NOTI_USE_YN'] == 1) { ?>
            <tr>
                <th>노출여부</th>
                <td>
                    <?php
                    echo radio_selected("Y", $write['B_NOTI_YN'], "노출", "b_noti_yn");
                    echo radio_selected("N", $write['B_NOTI_YN'], "비노출", "b_noti_yn");
                    ?>
                </td>
            </tr>
            <?php } ?>
        </table>
        <!-- both button -->
        <div class="bothButton">
            <div class="fl">
                <button class="btn btn-inverse" type="button" onclick="fnGoView('<?php echo $list_href ?>');">목록</button>
            </div>
            <div class="fr">
                <?php if ($m == "u") { ?>
                <input type="submit" class="btn btn-primary" value="수정">
                <button class="btn btn-inverse" type="button" onclick="deletes('<?php echo $del_href ?>');">삭제</button>
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
    <input type="hidden" name="bc_code" value="<?=$bc_code?>">
    <input type="hidden" name="search_type" value="<?=$search_type?>">
    <input type="hidden" name="search_txt" value="<?=$search_txt?>">
    <input type="hidden" name="site" value="<?=$site?>">
    <input type="hidden" name="exps_yn" value="<?=$exps_yn?>">
    <input type="hidden" name="type" value="<?=$type?>">
    <input type="hidden" name="noti_yn" value="<?=$noti_yn?>">
</form>
<script type="text/javascript">
//등록 & 수정
function send() {
    if(confirm("<?=$html_title?> 하시겠습니까?")){
        <?php echo $editor_js;?> // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   
   		oEditors.getById["b_cont"].exec("UPDATE_CONTENTS_FIELD",[]);        

        <?php if ($board['BC_TYPE_USE_YN'] == 1) { ?>
        if($("#b_corp_name").val() == ""){
            alert("회사명을 입력해주세요");
            return false;
        }
        <?php } ?>
        
        <?php if ($board['BC_TYPE_USE_YN'] == 1) { ?>
        if($("#b_type").val() == null || $("#b_type").val() == ""){
            alert("구분을 입력해주세요");
            return false;
        }
        <?php } ?>
        
        if($("#b_title").val() == null || $("#b_title").val() == ""){
            alert("제목을 입력해주세요");
            return false;
        }
        return true;
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

<?php if ($board['BC_SHARE_USE_YN'] == 1) { ?>
$("#b_share_seq").change(function(){
    var frm = $("form[name='frm'");
    frm.attr("action", "post_write.php");
    frm.attr("onsubmit", "");
    frm.submit();
});
<?php } ?>
</script>