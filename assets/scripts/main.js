function loginForm(me, forms)
{
    if ( me.data('requestRunning') ) {
        return;
    }
    var retry = 0;
    me.data('requestRunning', true);
    $("#loginForm button[name='login']").attr("disabled", "disabled");
    $("#loader").toggleClass("d-none");
    
    $.ajax({
        type: "POST",
        url: "ajax_login.html",
		dataType: "json",
        data: forms.serialize(),
        success: function(resp_data){
            if(resp_data.success == 1){
                //if(resp_data.last_url == null || resp_data.last_url == 'login.html' || resp_data.last_url == 'logout.html'){
                    //window.location.assign(resp_data.last_url);
                //}else{
                    //window.location.assign("index.html");
                //}
                alert('Successfully Logged In');
                window.location.assign("index.html");
            } else if(resp_data.success == 0) {
                $("#userName").addClass("has-error");
                $("#loginLabel").html(resp_data.message);
                $(".main").addClass('border');
                $(".main").removeClass('border-info');
                $(".main").addClass('border-danger');
                retry = resp_data.login_attempt;
                if(retry <= 5){
                    alert(resp_data.message);
                }else{
                    var hide_status = $( "#recaptchaDiv" ).hasClass( "d-none" );
                    if(hide_status == 1){
                        $("#recaptchaDiv").removeClass('d.none');
                    }
                    grecaptcha.reset();
                    $("#loginForm button[name='login']").attr("disabled", "disabled");
                }
            } else {
                alert('Unknown error.');
            }
        },
        complete: function() {
            if(retry <= 5){
                $("#loginForm button[name='login']").removeAttr("disabled");
            }
            $("#loader").toggleClass("d-none");
            me.data('requestRunning', false);
        },
    });
    return false;
}