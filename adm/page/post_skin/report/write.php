<?php
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가

$send_dt = substr($write['B_SEND_DT'],0,10);
$send_hour = substr($write['B_SEND_DT'],11,2);
$send_minute = substr($write['B_SEND_DT'],14,2);

//file list
if($write['B_SEQ']){
    $sql = " select * from T_BOARD_FILES where B_SEQ = ".$write['B_SEQ']." AND FI_INDEX = '1' order by FI_SORT asc ";
    $file_res1 = sql_query($sql);

    $sql = " select * from T_BOARD_FILES where B_SEQ = ".$write['B_SEQ']." AND FI_INDEX = '2' order by FI_SORT asc ";
    $file_res2 = sql_query($sql);

}

?>
<form id="frm" name="frm" method="post" action="<?php echo P1_PAGE_URL ?>/post_insert_new.php" onsubmit="return send();" enctype="multipart/form-data">
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
<input type="hidden" name="filelist1" id="filelist1" value="">
<input type="hidden" name="filelist2" id="filelist2" value="">

    <fieldset>
    <legend class="hid1"><?=$p1['title']?> 등록 및 수정</legend>
        <table class="colTable">
           
           
            <tr>
                <th>*분류</th>
                <td>
                    
                    <select name="b_type" id="b_type">
                        <option value="">선택</option>
                        <option value="Trend Delivery" <?=$write['B_TYPE']=='Trend Delivery'?'selected':''?>>Trend Delivery</option>
                        <option value="VOICE Trend" <?=$write['B_TYPE']=='VOICE Trend'?'selected':''?>>VOICE Trend</option>
                        <option value="Trend Overview" <?=$write['B_TYPE']=='Trend Overview'?'selected':''?>>Trend Overview</option>
                        
                    </select>

                </td>

                <th>*발행일</th>
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
                <th>*제목</th>
                <td colspan="3">
                    <label for="b_title"><input type="text" id="b_title" name="b_title" value="<?=$write['B_TITLE']?>" style="width:600px"></label>
                </td>
            </tr>
            <tr>
                <th>*리포트 소개</th>
                <td  colspan="3">
                    <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
                    <!-- <textarea name="b_brief" id="b_brief" style="height:100px; width:100%" rows="20" cols="100"><?=$write['B_BRIEF']?></textarea> -->
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
                <th>*썸네일 이미지(PC)</th>
                <td >
                <label for="list_img"><input type="file" id="b_attach3" name="b_attach3"></label>
                   <?php
                        if ($write['B_FILE3']) {
                            echo $write['B_FILE3'];
                        ?>
                        <button class="btn-small btn-inverse" type="button" onclick="location.href='./post_file_delete.php?seq=<?=$write['B_SEQ']?>'">X</button>
                        <?php
                        }
                    ?>
                </td>
                <th>Alt값(PC)</th>
                <td >
                    <input type="text" id="b_ext3" name="b_ext3" value="<?=$write['B_EXT3']?>">
                </td>
            </tr>

            <tr>
                <th>*썸네일 이미지(MO)</th>
                <td >
                <label for="list_img"><input type="file" id="b_attach4" name="b_attach4"></label>
                   <?php
                        if ($write['B_FILE4']) {
                            echo $write['B_FILE4'];
                        ?>
                        <button class="btn-small btn-inverse" type="button" onclick="location.href='./post_file_delete.php?seq=<?=$write['B_SEQ']?>'">X</button>
                        <?php
                        }
                    ?>
                </td>
                <th>Alt값(MO)</th>
                <td >
                    <input type="text" id="b_ext4" name="b_ext4" value="<?=$write['B_EXT4']?>">
                </td>
            </tr>


            <tr>
                <th>리포트 파일첨부*<br/>(PDF 만 등록가능)</th>
                <td colspan="3">
                    
                   <div>
                    <input type="radio" name="b_site" id="b_site1" value="FILE"  onclick="javascript:siteChange('FILE');" <?php if (!$write['B_SEQ']) { ?>  checked <?}?>  <?if($write['B_SITE']=='FILE'){?> checked <?}?>/>
                    <label for="b_site1">
                    직접등록
                    </lable>
                   
                    <label for="b_site2">
                    <input type="radio" name="b_site" id="b_site2" value="URL"  onclick="javascript:siteChange('URL');" <?if($write['B_SITE']=='URL'){?> checked <?}?>/>
                    URL등록
                    </label>
                    </div>
                    
                    <div id="file_box">
                        <label for="list_img"><input type="file" id="b_attach5" name="b_attach5"></label>
                        <div class="di" id="fileBox2">
                            
                        </div>
                    </div>

                    <div id="url_box" style="display:none;">
                        <input type="text" id="b_linkurl"  style="width:500px;" name="b_linkurl" value="<?=$write['B_LINKURL']?>" placeholder="URL을 넣어주세요 (PDF파일 URL만 가능합니다)">
                    </div>

                </td>
            </tr>


            <!-- <tr>
                <th>컨텐츠 내용*<br/>(이미지 슬라이드)</th>
                <td colspan="3">
                    
                    <div id="file_box">
                        <label for="list_img"><input type="file" id="b_attach6" name="b_attach6"></label> * 슬라이드 이미지 10개까지 등록 가능합니다.
                       
                    </div>

                    <div class="di" id="fileBox1">
                                
                    </div>

                </td>
            </tr> -->


         
            <tr>
                <th>게시여부</th>
                <td colspan="3">

                <div>
                    <input type="radio" name="b_noti_yn" id="b_noti_yn1" value="Y" <?php if (!$write['B_SEQ']) { ?>  checked <?}?> <?if($write['B_NOTI_YN']=='Y'){?> checked <?}?> />
                    <label for="b_noti_yn1">
                    노출
                    </lable>
                   
                    <label for="b_noti_yn2">
                    <input type="radio" name="b_noti_yn" id="b_noti_yn2" value="N"  <?if($write['B_NOTI_YN']=='N'){?> checked <?}?> />
                    비노출
                    </label>
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

    $('#filelist1').val(appendFileListJson('1'));
    $('#filelist2').val(appendFileListJson('2'));
    
    var send_dt = $('#send_dt').val();
        var send_hour = $('#send_hour').val();
        var send_minute = $('#send_minute').val();

        $('#b_send_dt').val(send_dt + ' ' + send_hour + ':' + send_minute + ':00');
        
        
    if(confirm("<?=$html_title?> 하시겠습니까?")){
        
        <?php echo $editor_js;?> // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   
         oEditors.getById["b_cont"].exec("UPDATE_CONTENTS_FIELD",[]);      


        var b_site = $("input[name='b_site']:checked").val();
       
        if($("#b_type").val() == null || $("#b_type").val() == ""){
            alert("분류를 입력해주세요");
            return false;
        }
     
        if($("#send_dt").val() == null || $("#send_dt").val() == ""){
            alert("발행일을 입력해주세요");
            return false;
        }

        if($("#b_title").val() == null || $("#b_title").val() == ""){
            alert("제목을 입력해주세요");
            return false;
        }

        if($("#b_cont").val() == null || $("#b_cont").val() == ""){
            alert("리포트 소개를 입력해주세요");
            return false;
        }
        <? if (!$write['B_FILE5']) { ?>
            // if(b_site=='FILE'){
            //     if($("#b_attach5").val() == null || $("#b_attach5").val() == ""){
            //         alert("PDF 파일만 첨부 가능하며 1건만 등록가능");
            //         return false;
            //     }
            // }

        <?}?>
        
        
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
 $(document).ready(function(){

    $('#filelist2').val(appendFileListJson('2'));

    $('#send_dt').datepicker({
            dateFormat: "yy-mm-dd"
    });
    <?
        if($write['B_SITE']){
    ?>
            siteChange('<?=$write['B_SITE']?>');
    <? 
    }
    ?> 


<?
        for ($i=0; $row=sql_fetch_array($file_res1); $i++) {
    ?>
        appendFileList('1', '<?=$row['FI_NAME']?>',  '<?=$row['FI_ORG']?>');
    <?}?>


<?
        for ($i=0; $row=sql_fetch_array($file_res2); $i++) {
    ?>
        appendFileList('2', '<?=$row['FI_NAME']?>',  '<?=$row['FI_ORG']?>');
    <?}?>


initFilepickerPDF();
initFilepickerIMG();
initFilepickerIMG6();

});

 function siteChange(val){
     if(val=='FILE'){
        $('#file_box').show();
        $('#url_box').hide();
        
     } else {
        $('#file_box').hide();
        $('#url_box').show();
     }
 }


 function initFilepickerPDF()
{
    var masterFileExt = 'pdf';
        document.getElementById("b_attach5").addEventListener("change", function(event) {
            var files = event.target.files;

            var html = '';
            for (var i=0; i<files.length; i++) {
                    var fileSize = files[i].size;
                    var ext = getExtensionOfFilename(files[i].name);
                    if(masterFileExt.indexOf(ext)==-1){
                        alert('PDF 파일만 첨부 가능하며 1건만 등록가능 합니다.');
                        $('#b_attach5').val('');
                        break;
                    }


                    var fileCount = appendFileCount('2');
                    if(fileCount>=1){
                        alert('파일첨부는 1개까지 등록 가능합니다.');
                        $('#b_attach5').val('');
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
                                    console.log("upload" + output);
                                    appendFileList('2', output.filename, output.orgname);
                                    $('#b_attach5').val('');
                                }
                        });


            };

        }, false);

    }




function initFilepickerIMG6()
{
    var masterFileExt = 'jpg,jpeg,png,gif';
    if(document.getElementById("b_attach6") != null){
        document.getElementById("b_attach6").addEventListener("change", function(event) {
            var files = event.target.files;
            var html = '';
            for (var i=0; i<files.length; i++) {
                    var fileSize = files[i].size;
                    var ext = getExtensionOfFilename(files[i].name);
                    if(masterFileExt.indexOf(ext)==-1){
                        alert('이미지 파일만 등록 가능합니다.');
                        $('#b_attach6').val('');
                        break;
                    }
                    
                    var fileCount = appendFileCount('1');
                    if(fileCount>=10){
                        alert('슬라이드 이미지 10개까지 등록 가능합니다.');
                        $('#b_attach6').val('');
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
                                    $('#b_attach6').val('');
                                }
                        });

            };

        }, false);
    }
}



 function initFilepickerIMG()
{
    var masterFileExt = 'jpg,jpeg,png,gif';
    
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


        document.getElementById("b_attach3").addEventListener("change", function(event) {
            var files = event.target.files;

            var html = '';
            for (var i=0; i<files.length; i++) {
                    var fileSize = files[i].size;
                    var ext = getExtensionOfFilename(files[i].name);
                    if(masterFileExt.indexOf(ext)==-1){
                        alert('이미지 파일만 등록 가능합니다.');
                        $('#b_attach3').val('');
                        break;
                    }
            };

        }, false);


        document.getElementById("b_attach4").addEventListener("change", function(event) {
            var files = event.target.files;

            var html = '';
            for (var i=0; i<files.length; i++) {
                    var fileSize = files[i].size;
                    var ext = getExtensionOfFilename(files[i].name);
                    if(masterFileExt.indexOf(ext)==-1){
                        alert('이미지 파일만 등록 가능합니다.');
                        $('#b_attach4').val('');
                        break;
                    }
            };

        }, false);


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