/*
 * 내용 체크
 */
function contCheck(elem, str){
	if(str == ''){
		alert('내용을 입력하여야 합니다.');
		elem.focus();
		return false;
	}
	
	return true;
}
function econtCheck(elem, str){
	if(str == ''){
		alert('Please enter message.');
		elem.focus();
		return false;
	}
	
	return true;
}
/*
 * 이름 체크
 */
function nameCheck(elem, str){
	var reg_name=/^[가-힣A-Za-z]{2,5}$/;
	if(str == ''){
		alert('이름을 입력하여야 합니다.');
		elem.focus();
		return false;
	} /*else if(!reg_name.test(str)){
		alert('이름을 정확히 입력해주세요.');
		elem.focus();
		return false;
	}*/
	
	if(str.replace(/[^가-힣]/g,'').length < 2 && str.replace(/[^A-Za-z]/g,'').length < 4) {
		alert("이름을 올바르게 입력하여야 합니다.");
		elem.focus();
		return false;
	}
	
	return true;
}
function enameCheck(elem, str){
	var reg_name=/^[가-힣A-Za-z]{2,5}$/;
	if(str == ''){
		alert('Please enter your name.');
		elem.focus();
		return false;
	}
	
	return true;
}

/*
 * 휴대폰 번호 체크
 */
function telCheck(elem, num, number){
var middle = /[0-9]{3,4}/,
	last = /[0-9]{4}/,
	all = /[0-9]{7}/;
	
	if(number == 'middle') {
		number = middle;
	} else if(number == 'last') {
		number = last;
	} else {
		number = all;
	}
	
	if(num == ''){
		alert("전화번호를 입력하여야 합니다.");
		elem.focus();
		return false;
	} else if(!number.test(num)){
		alert("전화번호를 확인하시기 바랍니다.");
		elem.focus();
		return false;
	}
	return true;
}

function etelCheck(elem, num, number){
	var middle = /[0-9]{3,4}/,
		last = /[0-9]{4}/,
		all = /[0-9]{7}/;
		
		if(number == 'middle') {
			number = middle;
		} else if(number == 'last') {
			number = last;
		} else {
			number = all;
		}
		
		if(num == ''){
			alert("Please enter your phone number.");
			elem.focus();
			return false;
		} else if(!number.test(num)){
			alert("Please check your phone number");
			elem.focus();
			return false;
		}
		return true;
	}


/*
* 숫자만 입력
*/
function onlyNumber(event) {
    var key = window.event ? event.keyCode : event.which;    

    if ((event.shiftKey == false) && ((key  > 47 && key  < 58) || (key  > 95 && key  < 106)
    || key  == 35 || key  == 36 || key  == 37 || key  == 39  // 방향키 좌우,home,end  
    || key  == 8  || key  == 46 ) // del, back space
    ) {
        return true;
    }else {
        return false;
    }    
};

/*
 * 이메일 아이디 체크
 */
function mailIdCheck(elem, str) {
	/*var idReg = /^[0-9a-zA-Z][_0-9a-zA-Z-]{3,19}$/;*/
	var idReg = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z]){3,19}$/;
	
	if(str == '') {
		alert("이메일 아이디를 입력하여야 합니다.");
		elem.focus();
		return false;
	} else if(!idReg.test(str)){
		alert("이메일 아이디를 확인하시기 바랍니다.");
		elem.focus();
		return false;
	}
	return true;
}

function emailIdCheck(elem, str) {
	var idReg = /^[0-9a-zA-Z][_0-9a-zA-Z-]{3,19}$/;
	
	if(str == '') {
		alert("Please enter your email address.");
		elem.focus();
		return false;
	} else if(!idReg.test(str)){
		alert("Please check your email address.");
		elem.focus();
		return false;
	}
	return true;
}


/*
 * 이메일 도메인 체크
 */
function mailAddress(elem, str) {
	var mailAddr = /^((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
	
	if(str == '') {
		alert("이메일주소를 입력하여야 합니다.");
		elem.focus();
		return false;
	} else if(!mailAddr.test(str)){
		alert("이메일 주소를 확인하시기 바랍니다.");
		elem.focus();
		return false;
	}
	return true;
}

function emailAddress(elem, str) {
	var mailAddr = /^((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
	
	if(str == '') {
		alert("Please enter your email address.");
		elem.focus();
		return false;
	} else if(!mailAddr.test(str)){
		alert("Please check your email address.");
		elem.focus();
		return false;
	}
	return true;
}

/*
* 홈페이지 url 체크
*/
function isValidUrl(elem, urls) {
	var chkExp = /http:\/\/([\w\-]+\.)+/g;
	if(urls == '') {
		alert("홈페이지주소를 입력하여야 합니다.");
		elem.focus();
		return false;
	} 
	if (!chkExp.test(urls)) {
		alert ("홈페이지주소를 확인하시기 바랍니다.");
		elem.focus();
		return false;
	}
	return true;
}

function eisValidUrl(elem, urls) {
	var chkExp = /http:\/\/([\w\-]+\.)+/g;
	if(urls == '') {
		alert("Please enter your website URL.");
		elem.focus();
		return false;
	} 
	if (!chkExp.test(urls)) {
		alert ("Please check your website URL.");
		elem.focus();
		return false;
	}
	return true;
}