$( document ).ready(function() {
    $( "#btn_register_submit" ).on( "click", function() {
        
        var data_string = $('#register-form').serialize(); // Collect data from form
        
        $.ajax({
            type: "POST",
            url: '/register_proc',
            data: data_string,
            dataType: 'json',
            timeout: 60000,
            cache: false,
            crossDomain: false,
            error: function (request, error) {
                console.log(error + '');
            },
            success: function (json) {
                if(json.success == true){
                    document.location.href = '/project';
                } else {
                    swal("", json.msg.ko, "error");
                }
            }
        });

    });
    $( "#btn_login_submit" ).on( "click", function() {
        if($('#userId').val() == '') {
            swal("", "\n아이디를 입력해 주세요!", "info");
            return;
        }
        if($('#userPwd').val() == '') {
            swal("", "\n비밀번호를 입력해 주세요!", "info");
            return;
        }
        var data_string = $('#login-form').serialize(); // Collect data from form
        
        $.ajax({
            type: "POST",
            url: $('#login-form').attr('action'),
            data: data_string,
            dataType: 'json',
            timeout: 60000,
            cache: false,
            crossDomain: false,
            error: function (request, error) {
                console.log(error + '');
            },
            success: function (json) {
                if(json.success == true){
                    document.location.href = '/project';
                } else {
                    swal("", "\n아이디 및 비밀번호가 틀렸습니다!", "info");
                }
                
                
            }
        });
    });
});