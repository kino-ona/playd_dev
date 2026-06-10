function get_ajax_token()
{
    var token = "";

    $.ajax({
        type: "POST",
        url: "./ajax.token.php",
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

$(function() {
    $(document).on("click", "form input:submit", function() {
        var f = this.form;
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

        return true;
    });
    
    // class로 readonly 구현
    $(".readonly").on("keydown paste", function(e) {
        e.preventDefault();
    });
});