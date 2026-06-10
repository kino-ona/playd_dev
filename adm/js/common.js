// 날짜 비교 함수
function diffDays() {
    var start = $('#from').handleDtpicker('getDate');
    var end   = $('#to').handleDtpicker('getDate');
    var diff = new Date(end - start);
    if(!start || !end)
        return;
    var days = diff/1000/60/60/24;
    return days;
}

function is_checked(elements_name)
{
    var checked = false;
    var chk = document.getElementsByName(elements_name);
    for (var i=0; i<chk.length; i++) {
        if (chk[i].checked) {
            checked = true;
        }
    }
    return checked;
}

function autoSelect(id, val){
	var obj = document.getElementById(id);
	
	for(i=0; i < obj.length; i++){
		if(obj.item(i).value == val){
			obj.item(i).selected = true;
		}else{
			obj.item(i).selected = false;	
		}
	}
}

//검색기능
function search(){

	var f = document.f;
	if(f.search_txt.value ==''){
		alert('검색어를 입력해 주세요.');
		return;
	}
	//f.pageNo.value = 1;

	f.submit();
}

//페이징에서 리스트 버튼 클릭시 이동
function fnGoList(pageNo){

	var f = document.f;
	f.pageNo.value=pageNo;

	f.submit();
}

//게시판 글 보기
function view(seq,url){
	var f = document.view;
	f.action = url;
	f.seq.value=seq;
	f.submit();
}

//게시판 목록으로 이동
function fnGoView(url){
	var f = document.golist;
	f.action = url;
	f.submit();
}

//게시판 관리 글 삭제
function deletes(url){
    try {
    	if(confirm("삭제 하시겠습니까?")){	
    		document.frm.action = url;
	        document.frm.submit();
    	}
    	
    } catch(e) {}
}

//게시판 목록 선택삭제
function multi_del(){
    if (!is_checked("chk[]")) {
        alert("삭제 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

	if($("#sns_block").val()=="sns_block") {
		if(!confirm("선택한 SNS 자료를 삭제방지 하시겠습니까?")) {
			return false;
		}

	} else {
		if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
			return false;
		}
	}
	return true;
}

function checkemail() {
	if(!checkEmail($("#m_mail").val())) {
		alert("이메일 주소가 정확하지 않습니다.\n\n확인 후 다시 입력해 주세요");
		return false;
	}
	return true;
}

// 이메일 유효성 체크
function checkEmail(str){
	var reg = /^((\w|[\-\.])+)@((\w|[\-\.])+)\.([A-Za-z]+)$/;
	if (str.search(reg) != -1) {
		return true;
	}
	return false;
}

$(function() {
	//textarea maxlength
    $("textarea[maxlength]").bind('input propertychange', function() {  
        var maxLength = $(this).attr('maxlength');  
        if ($(this).val().length > maxLength) {  
            $(this).val($(this).val().substring(0, maxLength));  
        }  
    });
    
	// 체크박스 전체선택
	$("#allCheck").on("click", function(){
		if($("#allCheck").prop("checked")) {
			$("input[type=checkbox]").prop("checked",true);
		}else{
			$("input[type=checkbox]").prop("checked",false);
		}
	});
});