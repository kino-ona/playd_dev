var isLoading = false;
var appMode = false;

if( /Android/i.test(navigator.userAgent)) {
    appMode = true;
}else if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
    appMode = true;
}else {
    appMode = false;
}

$( document ).ready(function() {

});


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


function get_ajax_token()
{
    var token = "";

    $.ajax({
        type: "POST",
        url: "/adm/page/ajax.token.php",
        cache: false,
        async: false,
        dataType: "json",
        success: function(data) {
            if(data.error) {
                alert(data.error);
                if(data.url)
                    document.location.href = data.url;

                return false;
            }

            token = data.token;
        }
    });

    return token;
}


function getExtensionOfFilename(filename) { 
    var _fileLen = filename.length; 
    var _lastDot = filename.lastIndexOf('.'); 
    var _fileExt = filename.substring(_lastDot+1, _fileLen).toLowerCase(); 
    return _fileExt;
 }
 function bytesToSize(bytes) {
    var sizes = ['bytes', 'kb', 'mb', 'gb', 'tb'];
    if (bytes == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

function getYyyymmddmiss()
{
    var date =  new Date()
    var nowYear = date.getFullYear();
    var nowMonth = date.getMonth()+1;
    var nowDay = date.getDate();

    var nowHour = date.getHours();
    var nowMt = date.getMinutes();
    var nowSec = date.getSeconds();


    if(nowMonth<10) nowMonth = "0" + nowMonth;
    if(nowDay<10) nowDay = "0" + nowDay;
    if(nowHour<10) nowHour = "0" + nowHour;
    if(nowMt<10) nowMt = "0" + nowMt;
    if(nowSec<10) nowSec = "0" + nowSec;
    
    return nowYear + "-" + nowMonth + "-" + nowDay + " " + nowHour + ":" + nowMt + ":" + nowSec;

}

function getPrettyDate(time){

    var date = new Date((time || "").replace(/-/g,"/").replace(/[TZ]/g," ")),
    diff = (((new Date()).getTime() - date.getTime()) / 1000);

    let day_diff = Math.floor(diff / 86400);
    if (isNaN(day_diff) || day_diff < 0) 
        return "";

return day_diff == 0 && (
    diff < 60 && "방금 전" ||
    diff < 120 && "1분 전" ||
    diff < 3600 && Math.floor(diff / 60) + "분 전" ||
    diff < 7200 && "1시간 전" ||
    diff < 86400 && Math.floor(diff / 3600) + "시간 전") ||
    day_diff == 1 && "어제" ||
    day_diff < 7 && day_diff + "일 전" ||
    day_diff < 31 && Math.floor(day_diff / 7) + "주 전" ||
    day_diff < 360 && Math.floor(day_diff / 30) + "개월 전" ||
    day_diff >= 360 && (Math.floor(day_diff / 360) == 0 ? 1 : Math.floor(day_diff / 360)) + "년 전"

    
}

function getAmPmDate(time){

    var a = time.split(/[^0-9]/);
   
    var date = new Date(a[0], a[1]-1, a[2], a[3],a[4],a[5]),
    diff = (((new Date()).getTime() - date.getTime()) / 1000);
  
    var day_diff = Math.floor(diff / 86400);
    if (isNaN(day_diff) || day_diff < 0) 
        day_diff = 0 //return ""

    var output = '';

     if(day_diff>0){
        var nowYear = date.getFullYear();
        var nowMonth = date.getMonth()+1;
        var nowDay = date.getDate();
        if(nowMonth<0)nowMonth = "0" + nowMonth;
        if(nowDay<10)nowDay = "0" + nowDay;
        output = nowYear + "-" + nowMonth + "-" + nowDay;

     } else {
        var nowHour = date.getHours();
        var nowMt = date.getMinutes();
        if (  nowHour >= 12  &&  nowHour  <= 24  ) {
          output = '오후 ' + nowHour + ':' + nowMt;
        } else  {
          output = '오전 ' + nowHour + ':' + nowMt;
        }
     }
     
     return output;

}



function getAmPmDateOnly(time){

    var a = time.split(/[^0-9]/);
    var date = new Date(a[0], a[1]-1, a[2], a[3],a[4],a[5]),
    diff = (((new Date()).getTime() - date.getTime()) / 1000);
   
    var output = '';

    var nowHour = date.getHours();
    var nowMt = date.getMinutes();

    
    if (  nowHour >= 12  &&  nowHour  <= 24  ) {

        if(nowHour<0)nowHour = "0" + nowHour;
        if(nowMt<10)nowMt = "0" + nowMt;
    

      output = '오후 ' + nowHour + ':' + nowMt;
    } else  {

        if(nowHour<0)nowHour = "0" + nowHour;
        if(nowMt<10)nowMt = "0" + nowMt;

      output = '오전 ' + nowHour + ':' + nowMt;
    }
     
     return output;

}

function getWeekDate(time){

    var output = '';

    var a = time.split(/[^0-9]/);
   
    var date = new Date(a[0], a[1]-1, a[2], a[3],a[4],a[5]);
    var week = new Array('일', '월', '화', '수', '목', '금', '토');
    var today = date.getDay();
    var todayLabel = week[today];

    output = a[0] + '-' + a[1] + '-' + a[2] + '(' + todayLabel + ')';

    return output;

}

function cutByLen(str, maxByte) {
    for(b=i=0;c=str.charCodeAt(i);) {
            b+=c>>7?2:1;
            if (b > maxByte)
            break;
            i++;
    }
    return str.substring(0,i);
}



function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

//이메일 체크하기 
function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

//비밀번호 체크하기 
function validatePwd(mm_pwd){
    var pwReg = /^(?=.*[a-zA-Z])(?=.*[0-9]).{8,16}$/;
    return pwReg.test(mm_pwd);
}

//숫자만 가능하게 체크하기
function validateNumber(email) {
    const re = /[^0-9]/g;
    return re.test(String(email).toLowerCase());
}

function nvl(val){
    var output = '';
    if(val == undefined || val == null || val == 'undefined') return '';

    output = val;
    return output;
}


Date.isLeapYear = function (year) { 
    return (((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0)); 
};

Date.getDaysInMonth = function (year, month) {
    return [31, (Date.isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
};

Date.prototype.isLeapYear = function () { 
    return Date.isLeapYear(this.getFullYear()); 
};

Date.prototype.getDaysInMonth = function () { 
    return Date.getDaysInMonth(this.getFullYear(), this.getMonth());
};


Date.prototype.addMonths = function (value) {
    var n = this.getDate();
    this.setDate(1);
    this.setMonth(this.getMonth() + value);
    this.setDate(Math.min(n, this.getDaysInMonth()));
    return this;
};






String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g,""); }