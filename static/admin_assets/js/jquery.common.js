var isLoading = false;

function getExtensionOfFilename(filename) { 
    var _fileLen = filename.length; 
    var _lastDot = filename.lastIndexOf('.'); 
    var _fileExt = filename.substring(_lastDot+1, _fileLen).toLowerCase(); 
    return _fileExt;
}
Date.prototype.addHours= function(h){
    this.setHours(this.getHours()+h);
    return this;
}

function getUrlParams() {
    var params = {};
    window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(str, key, value) { params[key] = value; });
    return params;
}

function getMonday(d, week) {
    if(week == 'prev'){
        d = new Date(d);
        var day = d.getDay()+7,
            diff = d.getDate() - day + (day == 0 ? -6:1); // adjust when day is sunday
        return new Date(d.setDate(diff));
    } else {
        d = new Date(d);
        var day = d.getDay(),
            diff = d.getDate() - day + (day == 0 ? -6:1); // adjust when day is sunday
        return new Date(d.setDate(diff));
    }
    
  }


$(document).ready(function(){
        var paramsObj = getUrlParams();
       
       
});  


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
