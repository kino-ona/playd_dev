<?php
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가

$send_dt = substr($write['B_SEND_DT'],0,10);
$send_hour = substr($write['B_SEND_DT'],11,2);
$send_minute = substr($write['B_SEND_DT'],14,2);


$b_ext5_start = '';
$b_ext5_end = '';

if($write['B_EXT5']){
    $arr = explode('~', $write['B_EXT5']);
    $b_ext5_start = trim($arr[0]);
    $b_ext5_end = trim($arr[1]);
} else {
    //$b_ext5_start = date("Y.m", P1_SERVER_TIME);
    //$b_ext5_end = date("Y.m", P1_SERVER_TIME);
}

$sql = "select * from  T_POST_CATEGORY  order by C_SORT asc ";
$cates = sql_query($sql);



//file list
if($write['B_SEQ']){
    $sql = " select * from T_BOARD_FILES where B_SEQ = ".$write['B_SEQ']." AND FI_INDEX = '1' order by FI_SORT asc ";
    $file_res1 = sql_query($sql);


}


?>
<form name="frm" method="post" action="<?php echo P1_PAGE_URL ?>/post_insert_new.php" onsubmit="return send();" enctype="multipart/form-data">
<input type="hidden" name="m" value="<?php echo $m ?>">
<input type="hidden" id="b_code" name="b_code" value="<?=($m == "u") ? $write['B_CODE'] : $bc_code?>">
<?php if ($board['BC_SHARE_USE_YN'] == 1) { ?>
<input type="hidden" id="bc_code" name="bc_code" value="<?=($m == "u") ? $write['B_CODE'] : $bc_code?>">
<?php } ?>
<input type="hidden" id="upload" name="upload" value="<?=$board['BC_UPLOAD_NM']?>">
<?php if ($write['B_SEQ']) { ?>
<input type="hidden" name="seq" value="<?=$write['B_SEQ']?>">
<?php } ?>

<input type="hidden" name="b_send_dt" id="b_send_dt" value="">


<input type="hidden" name="b_ext5" id="b_ext5" value="">
<input type="hidden" name="b_ext6" id="b_ext6" value="">

<input type="hidden" name="filelist1" id="filelist1" value="">
		<style>
			.colTable input[type=file] {border: 1px solid #ddd; padding: 5px 10px;margin-right: 10px;}

		</style>
    <fieldset>
    <legend class="hid1"><?=$p1['title']?> 등록 및 수정</legend>
        <table class="colTable">
           
                    
						<tr>
							<td colspan="4" style="font-weight: 700;font-size: 18px;color: #000;padding-top:15px;">메인(목록) 화면</td>
						</tr>
	
            <tr>
                <th style="width:200px;">*카테고리</th>
                <td>
                    
                    <select name="b_type" id="b_type">
                        <option value="">선택</option>

                        <?php
                       
                        for ($i=0; $row=sql_fetch_array($cates); $i++) {
                            $row = $row['C_NAME']
                        ?>
                         <option value="<?=$row?>" <?=$write['B_TYPE']==$row?'selected':''?>><?=$row?></option>
                        <?}?>
                    </select>

                </td>

                <th style="width:180px">*발행일</th>
                <td width="30%">
                    <input type="text" id="send_dt" name="send_dt" readonly style="min-width:110px !important;width:110px !important;" value="<?=$send_dt?>" >
                    <select name="send_hour" id="send_hour" style="min-width:70px !important;width:70px !important;">
                    <?for($i=0;$i<24;$i++){
                        $str = $i;
                        if($str<10) $str = "0".$i;
                        ?>
                        <option value="<?=$str?>" <?=$send_hour==$str?'selected':''?>><?=$str?></option>
                    <?}?>
                    </select>

                    <select name="send_minute" id="send_minute" style="min-width:70px !important;width:70px !important;">
                    <?for($i=0;$i<60;$i++){
                        $str = $i;
                        if($str<10) $str = "0".$i;
                        ?>
                        <option value="<?=$str?>"  <?=$send_minute==$str?'selected':''?>><?=$str?></option>
                    <?}?>
                    </select>

                </td>
            </tr>

           

            <tr>
                <th>*프로젝트명</th>
                <td >
                    <label for="b_title"><input type="text" id="b_title" name="b_title" value="<?=$write['B_TITLE']?>" style="width:400px;"></label>
                </td>
                <th>*광고주명</th>
                <td >
                    <label for="b_ext3"><input type="text" id="b_ext3" name="b_ext3" value="<?=$write['B_EXT3']?>" ></label>
                </td>
            </tr>


            <tr>
                <th>*빅배너 이미지(PC)</th>
                <td >
                <label for="list_img"><input type="file" id="b_attach1" name="b_attach1"></label>
                   <?php
                        if ($write['B_FILE1']) {
                            echo $write['B_FILE1'];
                        ?>
                        <button class="btn-small btn-inverse" type="button" onclick="location.href='./post_file_delete.php?seq=<?=$write['B_SEQ']?>'">X</button>
                        <?php
                        }
                    ?>
                </td>
                <th>Alt값(PC)</th>
                <td >
                    <input type="text" id="b_ext1" name="b_ext1" value="<?=$write['B_EXT1']?>">
                </td>
            </tr>


            <tr>
                <th>*빅배너 이미지(MO)</th>
                <td >
                <label for="list_img"><input type="file" id="b_attach2" name="b_attach2"></label>
                   <?php
                        if ($write['B_FILE2']) {
                            echo $write['B_FILE2'];
                        ?>
                        <button class="btn-small btn-inverse" type="button" onclick="location.href='./post_file_delete.php?seq=<?=$write['B_SEQ']?>'">X</button>
                        <?php
                        }
                    ?>
                </td>
                <th>Alt값(MO)</th>
                <td >
                    <input type="text" id="b_ext2" name="b_ext2" value="<?=$write['B_EXT2']?>">
                </td>
            </tr>

                    
						<tr>
							<td colspan="4" style="font-weight: 700;font-size: 18px;color: #000;padding-top:40px;">상세 화면</td>
						</tr>

            <tr>
                <th>*기간(연도)</th>
                <td colspan="3">

                <div>
                    <input type="radio" name="b_site" id="b_site1" value="MULTI"  onclick="javascript:siteChange('MULTI');" <?php if (!$write['B_SEQ']) { ?>  checked <?}?>  <?if($write['B_SITE']=='MULTI'){?> checked <?}?>/>
                    <label for="b_site1">
                    연속 연도 선택
                    </lable>
                   
                    <label for="b_site2">
                    <input type="radio" name="b_site" id="b_site2" value="ON"  onclick="javascript:siteChange('ON');" <?if($write['B_SITE']=='ON'){?> checked <?}?>/>
                    단일 연도 선택
                    </label>
                 </div>

                 <div id="multi_box">
                    <input type="text" id="b_ext5_start" name="b_ext5_start" value="<?=$b_ext5_start?>" placeholder="ex) 2024.09" maxlength="7">
                        ~
                    <input type="text" id="b_ext5_end" name="b_ext5_end" value="<?=$b_ext5_end?>" placeholder="ex) 2024.09" maxlength="7">

               </div>
                 <div id="one_box" style="display:none">
                        <input type="text" id="b_ext6_start" name="b_ext6_start" value="<?=$write['B_EXT6']?>" placeholder="ex) 2024.09" maxlength="7">
                       
                        <!-- <button class="btn btn-inverse" style="margin-top:10px;" type="button" onclick="yearsAdd('');">추가</button> -->
          
                </div>
                </td>
            </tr>


            <tr>
                <th>*타이틀</th>
                <td colspan="3">
                    <label for="b_ext4"><input type="text" id="b_ext4" name="b_ext4" value="<?=$write['B_EXT4']?>" style="width:600px;"></label>
                </td>
            </tr>

            <tr>
                <th>*서브 타이틀</th>
                <td colspan="3">
                    <label for="b_linkurl"><input type="text" id="b_linkurl" name="b_linkurl" value="<?=$write['B_LINKURL']?>" style="width:600px;"></label>
                </td>
            </tr>


            <tr>
                <th>*설명</th>
                <td colspan="3">
                    <textarea name="b_brief" id="b_brief" style="height:100px; width:100%" rows="20" cols="100"><?=$write['B_BRIEF']?></textarea>
                </td>
            </tr> 



            <tr>
                <th>게시여부</th>
                <td colspan="3">

                <div>
                    <input type="radio" name="b_noti_yn" id="b_noti_yn1" value="Y" <?php if (!$write['B_SEQ']) { ?>  checked <?}?> <?if($write['B_NOTI_YN']=='Y'){?> checked <?}?> />
                    <label for="b_noti_yn1">
                    전체공개(목록+상세)
                    </lable>

                    <label for="b_noti_yn3">
                        <input type="radio" name="b_noti_yn" id="b_noti_yn3" value="L"  <?if($write['B_NOTI_YN']=='L'){?> checked <?}?> />
                        일부공개(목록)
                    </label>
                   
                    <label for="b_noti_yn2">
                        <input type="radio" name="b_noti_yn" id="b_noti_yn2" value="N"  <?if($write['B_NOTI_YN']=='N'){?> checked <?}?> />
                        비공개
                    </label>
                    </div>
                </td>
            </tr>

            <tr>
                <th>동영상 업로드 또는<br/> 유튜브 링크 삽입<br/>(최소 한개)</th>
                <td colspan="3">
                <div>
                    <label for="b_title1_div3">
                        <input type="radio" name="b_title1" id="b_title1_div3" value="none"  <?if(!$write['B_SEQ'] || $write['B_TITLE1']=='none'){?> checked <?}?>  onclick="videoChange('none');"/>
                        영상등록 없음
                    </label>

                    <label for="b_title1_div1">
                        <input type="radio" name="b_title1" id="b_title1_div1" value="video"  <?if($write['B_TITLE1']=='video'){?> checked <?}?>  onclick="videoChange('video');"/>
                        동영상 첨부
                    </label>

                    <label for="b_title1_div2">
                        <input type="radio" name="b_title1" id="b_title1_div2" value="youtube"  <?if($write['B_TITLE1']=='youtube'){?> checked <?}?> onclick="videoChange('youtube');" />
                        유튜브 링크
                    </label>

                    <!-- <label for="b_title1_div3">
                        <input type="radio" name="b_title1" id="b_title1_div3" value="server"  <?if($write['B_TITLE1']=='server'){?> checked <?}?> onclick="videoChange('server');" />
                        서버경로입력
                    </label> -->

                    <div id="video_box"  <? if ($write['B_TITLE1'] == 'youtube' || $write['B_TITLE1'] == 'server' || $write['B_TITLE1'] == 'none') { ?> style="display:none" <?}?> <?if(!$write['B_SEQ'] || $write['B_TITLE1']=='video'){?> style="display:block" <?}?>>
                                    
                        <label for="list_img"><span style="display:block;padding: 10px 0;">동영상 (* mp4 파일을 업로드해주세요)</span><input type="file" accept="video/mp4" id="b_attach6" name="b_attach6" ></label>
                            <?php
                                    if ($write['B_FILE6']) {
                                        echo $write['B_FILE6'];
                                    ?>
                                    <button class="btn-small btn-inverse" type="button" onclick="location.href='./post_file_delete.php?seq=<?=$write['B_SEQ']?>&num=6'">X</button>
                                    <?php
                                    }
                           ?>
                            <div style="padding-top:15px;">
                                <label for="list_img"><span style="display:block;padding: 10px 0;">썸네일 (* 이미지 파일을 업로드해주세요)</span><input type="file"  accept="image/png, image/jpeg" id="b_attach5" name="b_attach5" ></label>
                                <?php
                                        if ($write['B_FILE5']) {
                                            echo $write['B_FILE5'];
                                        ?>
                                        <button class="btn-small btn-inverse" type="button" onclick="location.href='./post_file_delete.php?seq=<?=$write['B_SEQ']?>&num=5'">X</button>
                                        <?php
                                        }
                            ?>
                           </div>
                    </div>

                    <div id="youtube_box" <? if (!$write['B_SEQ'] || $write['B_TITLE1'] == 'video' || $write['B_TITLE1'] == 'server'  || $write['B_TITLE1'] == 'none') { ?> style="display:none" <?}?>  <?if($write['B_TITLE1']=='youtube'){?>  style="display:block" <?}?>>
                        <label for="list_img">
                            VideoID(code) <input style="min-width:400px;" type="text" id="b_file4" name="b_file4" value="<?=$write['B_FILE4']?>" placeholder="예시와 같이 유투브 url 의 빨간색 코드를 입력해주세요">
														<span>ex) https://www.youtube.com/watch?v=<span style="font-weight:700;color: red;">THOwN4qMxeI</span></span>
                         </label>
                    </div>
										
                    <!-- <div id="server_box" <? if (!$write['B_SEQ'] || $write['B_TITLE1'] == 'video' || $write['B_TITLE1'] == 'youtube') { ?> style="display:none" <?}?>  <?if($write['B_TITLE1']=='server'){?>  style="display:block" <?}?>>
                        <label for="list_img">
                            URL <input style="min-width:400px;" type="text" id="b_file4" name="b_file4" value="<?=$write['B_FILE4']?>" placeholder="서버 동영상 경로를 입력해주세요">
                         </label>
                    </div> -->

										<!-- <div style="border-top:1px solid #eee; padding-top: 10px;margin-top: 15px;line-height: 1.4;">
										[서버경로입력] 등록 방법 
										<br>
											- 등록되야 하는 영상파일을 업로드 담당자에게 전달해주세요<br>
											- 업로드 담당자로부터 받은 해당 영상파일의 위치를 입력란에 입력해주세요<br>
											- 컨텐츠 등록 전 반드시 영상파일이 서버에 위치해야 사용 가능합니다<br>
											- 해당 기능은 파일 용량이 100MB가 넘는 고용량 이거나 동영상첨부로 등록되지 않은 영상파일일 경우 선택하여 진행하시면 됩니다
										</div> -->


                </td>
            </tr>
            
            <tr>
                <th>* 이미지 업로드<br/>(최소 한개는 등록되어야 함)</th>
                <td colspan="3">
               
                 <label for="list_img"><input type="file" id="b_attach4" name="b_attach4"></label>
                  <div class="di" id="fileBox1">
                            
                  </div>

                  <br/><br/>
									<div style="border-top:1px solid #eee; padding-top: 10px;line-height: 1.4;">
										- 이미지는 최대 8개까지 등록 가능합니다<br/>
										- 이미지는 되도록 직사각형 비율의 이미지를 등록해주세요<br/>
										- 이미지용량은 최대 20MB 를 넘지 않도록 해주세요
									</div>
                </td>
            </tr>
            
            <tr>
                <th>해시태그</th>
                <td colspan="3">
                    <input style="min-width:400px;" type="text" id="b_cont1" name="b_cont1" value="<?=$write['B_CONT1']?>" placeholder="해시태그를 입력해주세요">
                    <br/><br/>
										<div style="border-top:1px solid #eee; padding-top: 10px;line-height: 1.4;">
											- 해시태그 입력 후 콤마(,)를 넣어주세요 <br/>
											- 해시태그는 최대 5개까지 등록 가능합니다<br/>
											- 해시태그에는 특수기호는 사용하지 말아주세요<br/>
										</div>

                </td>
            </tr>
            
            <?if($write['B_SEQ']){?>
            <tr>
                <th>최초 등록일</th>
                <td >
                    <?=$write['B_REGDATE']?>
                </td>
                <th>최초 등록자</th>
                <td >
                <?=$write['B_WRITER']?>
                </td>
            </tr>
            <tr>
                <th>최종 수정일</th>
                <td >
                <?=$write['B_UREGDATE']?>
                </td>
                <th>최종 수정자</th>
                <td >
                <?=$write['B_UWRITER']?>
                </td>
            </tr>


            <?}?>
        </table>
        <!-- both button -->
        <div class="bothButton">
            <div class="fl">
                <button class="btn btn-inverse" type="button" onclick="fnGoView('<?php echo $list_href ?>');">목록</button>
            </div>
            <div class="fr">
                <?php if ($m == "u") { ?>
                    <?if($auth['write']=='Y'){?>
                <input type="submit" class="btn btn-primary" value="수정">
                <?}?>
                <?if($auth['del']=='Y'){?>
                <button class="btn btn-inverse" type="button" onclick="deletes('<?php echo $del_href ?>');">삭제</button>
                <?}?>
                <?php } ?>
                <?php if ($m == "") { ?>
                    <?if($auth['write']=='Y'){?>
                <input type="submit" class="btn btn-inverse" value="등록">
                <?}?>
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
    if (window._playdPortfolioSubmitting) {
        return false;
    }

    $('#filelist1').val(appendFileListJson('1'));


    var send_dt = $('#send_dt').val();
    var send_hour = $('#send_hour').val();
    var send_minute = $('#send_minute').val();

    $('#b_send_dt').val(send_dt + ' ' + send_hour + ':' + send_minute + ':00');
        


    var b_ext5_start = $('#b_ext5_start').val();
    var b_ext5_end = $('#b_ext5_end').val();
  
    $('#b_ext5').val(b_ext5_start + '~' + b_ext5_end);
    
    var b_site = $("input[name='b_site']:checked").val();
       
    if(b_site=='ON'){
        var b_ext6 = '';
        // $("select[id^='b_ext6_start']").each(function (i, el) {
        //     b_ext6+= $(this).val() + ",";
        // }); 
        $('#b_ext6').val($('#b_ext6_start').val());
    }

    if(confirm("<?=$html_title?> 하시겠습니까?")){
        
       
        if($("#b_type").val() == null || $("#b_type").val() == ""){
            alert("분류를 입력해주세요");
            return false;
        }
     
        if($("#send_dt").val() == null || $("#send_dt").val() == ""){
            alert("발행일을 입력해주세요");
            return false;
        }

        if($("#b_title").val() == null || $("#b_title").val() == ""){
            alert("프로젝트명을 입력해주세요");
            return false;
        }

        if($("#b_ext3").val() == null || $("#b_ext3").val() == ""){
            alert("광고주명을 입력해주세요");
            return false;
        }


        if(b_site=='ON'){

            if($("#b_ext6_start").val() == null || $("#b_ext6_start").val() == ""){
                alert("연도를 입력해주세요");
                return false;
            }
        } else {

            if($("#b_ext5_start").val() == null || $("#b_ext5_start").val() == "" || $("#b_ext5_end").val() == null || $("#b_ext5_end").val() == ""){
                alert("연도를 입력해주세요");
                return false;
            }

        }
        
        

        if($("#b_ext4").val() == null || $("#b_ext4").val() == ""){
            alert("타이틀을 입력해주세요");
            return false;
        }

        if($("#b_linkurl").val() == null || $("#b_linkurl").val() == ""){
            alert("서브 타이틀을 입력해주세요");
            return false;
        }

        if($("#b_brief").val() == null || $("#b_brief").val() == ""){
            alert("설명을 입력해주세요");
            return false;
        }

        // 파일 검사 
        var fileCount = appendFileCount('1');
        if(fileCount == 0){
           alert('이미지는 최소 한개 이상 등록되어야 합니다.');
           return false;
        }

        if(fileCount > 8){
           alert('이미지는 최대 2개까지 등록 가능합니다.');
           return false;
        }

        var b_title1_chk = $("input[name='b_title1']:checked").val();
        if(b_title1_chk=='video' || b_title1_chk=='youtube') {
            var b_attach5_temp  = '<?=$write['B_FILE5']?>'; //썸네일
            var b_attach6_temp  = '<?=$write['B_FILE6']?>'; //영상첨부
						
						if (b_title1_chk === 'video') {
								if ($('#b_attach6').val() == '' && b_attach6_temp == '') {
										alert('동영상을 첨부해주세요');
										return false;
								}
								if(b_attach5_temp == '') {
									if (($('#b_attach6').val() !== '' || b_attach6_temp !== '') && $('#b_attach5').val() == '') {
											alert('동영상 첨부시 썸네일 이미지는 필수로 등록되어야 합니다.');
											return false;
									}
								}
						}

						if (b_title1_chk === 'youtube') {
								if ($('#b_file4').val() == '') {
										alert('유투브 링크를 등록해주세요');
										return false;
								}
						}

            // if($('#b_attach6').val() != '') {
						// 	if($('#b_attach5').val() == '') {
						// 		alert('동영상 첨부 또는 유투브 등록시 썸네일 이미지는 필수로 등록되어야 합니다.');
						// 		return false;
						// 	}
            // }
        }


        

      

  
        //해시태그 검사 
        if($("#b_cont1").val() != null ){ 
            var split_arr = $("#b_cont1").val().split(",");
            if (split_arr.length > 5) {
                alert('해시태그는 최대 5개까지 등록 가능합니다');
                return false;
            }
            for(var i=0; i< split_arr.length; i++) {
                var reg = /[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/g
                if(reg.test(split_arr[i])) {
                    alert('해시태그에 특수기호가 포함되어 있습니다.');
                    return false;
                }
            }
            // $("#b_cont1").val()
        }
        
      

        window._playdPortfolioSubmitting = true;
        $('form[name="frm"] input[type="submit"]').prop('disabled', true);

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



<script>
    var totalFileSize = 0;

 $(document).ready(function(){
    $('#send_dt').datepicker({
            dateFormat: "yy-mm-dd"
    });
    <? if($write['B_SITE']){?>
            siteChange('<?=$write['B_SITE']?>');
    <?  }  ?> 
    <? if($write['B_SITE'] == 'ON' && $write['B_EXT6']){  ?>
       $('#one_box_list').empty();

        <?
        $arr = explode(",", $write['B_EXT6']);
        foreach($arr as $v) {
            if($v){
        ?>
            yearsAdd('<?=$v?>');
        <?}}?>
    <?}?>


// initFilepickerPDF();
    initFilepickerIMG();


    <?
        for ($i=0; $row=sql_fetch_array($file_res1); $i++) {
    ?>
        appendFileList('1', '<?=$row['FI_NAME']?>',  '<?=$row['FI_ORG']?>');
    <?}?>


    
});

 function siteChange(val){
     if(val=='MULTI'){
        $('#multi_box').show();
        $('#one_box').hide();
        
     } else {
        $('#multi_box').hide();
        $('#one_box').show();
     }
 }


//  function initFilepickerPDF()
// {
//     var masterFileExt = 'pdf';
//         document.getElementById("b_attach5").addEventListener("change", function(event) {
//             var files = event.target.files;

//             var html = '';
//             for (var i=0; i<files.length; i++) {
//                     var fileSize = files[i].size;
//                     var ext = getExtensionOfFilename(files[i].name);
//                     if(masterFileExt.indexOf(ext)==-1){
//                         alert('PDF 파일만 첨부 가능하며 1건만 등록가능 합니다.');
//                         $('#b_attach5').val('');
//                         break;
//                     }
//             };

//         }, false);

//     }



 function initFilepickerIMG()
{
    var masterFileExt = 'jpg,jpeg,png,gif';
    var masterFileSize = 1024*1024*25;

    document.getElementById("b_attach1").addEventListener("change", function(event) {
    var files = event.target.files;

    var html = '';
    for (var i=0; i<files.length; i++) {
            var fileSize = files[i].size;
            var ext = getExtensionOfFilename(files[i].name);
            if(masterFileExt.indexOf(ext)==-1){
                alert('이미지 파일만 등록 가능합니다.');
                $('#b_attach1').val('');
                break;
            }
        };

    }, false);


    document.getElementById("b_attach2").addEventListener("change", function(event) {
        var files = event.target.files;

        var html = '';
        for (var i=0; i<files.length; i++) {
                var fileSize = files[i].size;
                var ext = getExtensionOfFilename(files[i].name);
                if(masterFileExt.indexOf(ext)==-1){
                    alert('이미지 파일만 등록 가능합니다.');
                    $('#b_attach2').val('');
                    break;
                }
        };

    }, false);

   if(document.getElementById("b_attach4") != null){
        document.getElementById("b_attach4").addEventListener("change", function(event) {
            var files = event.target.files;
            var html = '';
            for (var i=0; i<files.length; i++) {
                    var fileSize = files[i].size;
                    totalFileSize = totalFileSize + fileSize;
                    var ext = getExtensionOfFilename(files[i].name);
                    if(masterFileExt.indexOf(ext)==-1){
                        alert('이미지 파일만 등록 가능합니다.');
                        $('#b_attach4').val('');
                        break;
                    }

                    if(parseInt(masterFileSize) > 0){
                        if(fileSize > parseInt(masterFileSize)){
                            alert('전송 가능한 파일 크기를 초과하였습니다.');
                            break;
                        }
                    }
                    
                    var fileCount = appendFileCount('1');
                    if(fileCount>8){
                        alert('이미지 파일첨부는 8개까지 등록 가능합니다.');
                        $('#b_attach4').val('');
                        return;
                    }

                    //파일 업로드 
                    createToken();
                    var formData = new FormData($('#frm')[0]);
                        formData.append('file0', files[0]); 

                        jQuery.ajax({
                                type: "POST",
                                url: "post_file_insert.php",
                                data: formData,
                                dataType: 'json',
                                timeout: 600000,
                                contentType: false,
                                processData: false,
                                cache: false,
                                crossDomain: false,
                                error: function (request, error) {
                                        console.log(error + '');
                                },
                                success: function(output){
                                    appendFileList('1', output.filename, output.orgname);
                                    $('#b_attach4').val('');
                                }
                        });

            };

        }, false);
    }

}

    function yearsAdd(val){
        var nCnt = 0;
        $("select[id^='b_ext6_start']").each(function (i, el) {
            nCnt++;
        });
        nCnt++;

        var html = '<div id="b_ext_box_'+nCnt+'"><select id="b_ext6_start_'+nCnt+'">';
        html+="<option value=''>선택</option>";
        <?for($i=2000;$i<=date("Y", P1_SERVER_TIME);$i++){?>
            var selected = '';
            if(val == '<?=$i?>'){
                selected = 'selected';
            }
            html+='<option value="<?=$i?>"'+selected+'><?=$i?></option>';
        <?}?>
        html+='</select> <button type="button" class="btn" onclick="javascript:yearsDel(\''+nCnt+'\');">삭제</button></div>';

        $('#one_box_list').append(html);
    }

    function yearsDel(val){
        $('#b_ext_box_' + val).remove();
    }

    function videoChange(val) {
        if(val=='video') {
            $('#video_box').show();
            $('#youtube_box').hide();
            $('#server_box').hide();
        } else if(val=='server') {
            $('#server_box').show();
            $('#video_box').hide();
            $('#youtube_box').hide();
        } else if(val=='none') {
            $('#server_box').hide();
            $('#video_box').hide();
            $('#youtube_box').hide();
        } else {
            $('#video_box').hide();
            $('#youtube_box').show();
            $('#server_box').hide();
        }
    }

    function appendFileList(index, filename, orgname){
        var count = appendFileCount(index);
        count = count + 1;
        var html = '';
        html = '<div style="margin:5px;" id="appendFile'+index+'_'+count+'" data-filename="'+filename+'" data-orgname="'+orgname+'">'+count+'번 <a href="'+filename+'" target="_blank">'+orgname+'</a> <button onclick="javascript:appendFileDelete(\''+index+'\',\''+count+'\');">삭제</button></div>';
        console.log(html);
        $('#fileBox' + index).append(html);
    }

    function appendFileCount(index){
        var fileCount = 0;
        $("div[id^='appendFile"+index+"_']").each(function (i, el) {
            fileCount++;
        });
        return fileCount;
    }

    function appendFileListJson(index){
        var file_arr = [];
        $("div[id^='appendFile"+index+"_']").each(function (i, el) {
            file_arr.push({'filename':$(this).attr('data-filename'), 'orgname':$(this).attr('data-orgname')});
        });
        return JSON.stringify(file_arr);
    }


    function appendFileDelete(index,count){
        $("#appendFile"+index+"_"+count).remove();
    }


    function createToken(){
        //token
        var f = document.frm;
                var token = get_ajax_token();
                console.log(token);
                if(!token) {
                    alert("토큰 정보가 올바르지 않습니다.");
                    return false;
                }

                var $f = $(f);

                if(typeof f.token === "undefined")
                    $f.prepend('<input type="hidden" name="token" value="">');

        $f.find("input[name=token]").val(token);
    }

 </script>