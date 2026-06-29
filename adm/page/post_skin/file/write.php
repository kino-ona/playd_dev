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
                <th>파일 첨부</th>
                <td  colspan="3">
                  <label for="list_img"><input type="file" id="b_attach4" name="b_attach4"></label>
                  <div class="di" id="fileBox1">
                            
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

    var json = JSON.parse($('#filelist1').val());
    if(json.length == 0){
            alert('파일을 등록해 주세요.');
            return false;
     }

    if(confirm("<?=$html_title?> 하시겠습니까?")){
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
   initFilepickerIMGPDF();
});



function initFilepickerIMGPDF()
{
    var masterFileExt = 'jpg,jpeg,png,gif,pdf,xls,xlsx,ppt,pptx,csv';
   if(document.getElementById("b_attach4") != null){
        document.getElementById("b_attach4").addEventListener("change", function(event) {
            var files = event.target.files;
            var html = '';
            for (var i=0; i<files.length; i++) {
                    var fileSize = files[i].size;
                    var ext = getExtensionOfFilename(files[i].name);
                    if(masterFileExt.indexOf(ext)==-1){
                        alert('허용된 파일만 등록 가능합니다.');
                        $('#b_attach4').val('');
                        break;
                    }
                    
                    var fileCount = appendFileCount('1');
                    if(fileCount>=1){
                        alert('파일첨부는 1개까지 등록 가능합니다.');
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