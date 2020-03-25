 $(document).ready(function(){
    var pass = 0;
    $("#loginForm").submit(function(event){
        // cancels the form submission
        event.preventDefault();
        
        if ($.trim($("#inputName").val()) === "" || $.trim($("#inputPassword").val()) === "") {
            return false;
        }else{
            var me = $(this);
            var forms = $("#loginForm");
            var login = loginForm(me, forms);
            //var portal = login_portal();

            //if(login == 1 && portal == 1){
            if(login == 1){
                window.location.replace("http://portal.sushitei.co.id/");
            }
        }
    });
    
    $("#passwordToggle").on('click', function(event){
        event.preventDefault();
        if(pass == 0){
            $("#inputPassword").prop('type', 'text');
            $("#eyeToggle").removeClass('fa-eye-slash');
            $("#eyeToggle").addClass('fa-eye');
            pass = 1;
        }else{
            $("#inputPassword").prop('type', 'password');
            $("#eyeToggle").removeClass('fa-eye');
            $("#eyeToggle").addClass('fa-eye-slash');
            pass = 0;
        }
        $("#inputPassword").focus();
    });
});

function loginForm(me, forms)
{
    var result = 0;
    if ( me.data('requestRunning') ) {
        return;
    }
    var retry = 0;
    me.data('requestRunning', true);
    $("#loginForm button[name='login']").attr("disabled", "disabled");
    $("#loader").toggleClass("d-none");
    
    $.ajax({
        type: "POST",
        url: "./login/do",
		dataType: "json",
        async: false,
        timeout: 3000,
        data: forms.serialize(),
        success: function(resp_data){
            $("input[name='"+resp_data.response.csrfName+"']").val(resp_data.response.csrfHash);
            if(resp_data.success == 1){
                //if(resp_data.last_url == null || resp_data.last_url == 'login.html' || resp_data.last_url == 'logout.html'){
                    //window.location.assign(resp_data.last_url);
                //}else{
                    //window.location.assign("index.html");
                //}
                $('#feedback').addClass('border-success');
                $('#feedback').removeClass('d-none');
                $('#feedback').html('Success');
                $('#session-list').html(resp_data.session);
                result = 1;
            } else if(resp_data.success == 0) {
                $("#userName").addClass("has-error");
                $("#loginLabel").html(resp_data.message);
                $(".main").addClass('border');
                $(".main").removeClass('border-info');
                $(".main").addClass('border-danger');
                retry = resp_data.attempt;
                if(retry <= 5){
                    showError(resp_data.error);
                }else{
                    $("#loginForm button[name='login']").prop("disabled", "disabled");
                    showError(resp_data.error);
                    
                    if(loaded == 1){
                        grecaptcha.reset();
                    }else{
                        $.getScript( "https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit", function( data, textStatus, jqxhr ) {
                            console.log( data ); // Data returned
                            console.log( textStatus ); // Success
                            console.log( jqxhr.status ); // 200
                            console.log( "Load was performed." );
                        });
                        var hide_status = $( "#recaptchaDiv" ).hasClass( "d-none" );
                        if(hide_status == 1){
                            $("#recaptchaDiv").removeClass('d-none');
                        }
                        
                    }
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
    return result;
}

function showError(jsondata){
    $('#feedBack ul').html('');
    $.each(jsondata, function(i, item) {
        $('#feedBack ul').append('<li>'+item+'</li>');
        $('#loginForm input[name="'+item+'"]').addClass("border border-warning");
    });
    $('#feedBack').removeClass('bg-white');
    $('#feedBack').removeClass('d-none');
    $('#feedBack').addClass('border-danger');
    $('#feedBack').addClass('bg-danger');
    $('#feedBack').addClass('text-white');
    $('#inputName').addClass('has-error');
    $('#inputName').focus();
}
//function login_portal(){
//    var forms = $("#loginForm");
//    var result = 0;
//    $.ajax({
//        type: "POST",
//        url: "http://portal.sushitei.co.id/sister/login_portal.html",
//        dataType: "json",
//        xhrFields: {
//          withCredentials: true
//        },
//        async: false,
//        timeout: 3000,
//        data: forms.serialize(),
//        success: function(resp_data){
//            if(resp_data.success == 1){
//                result = 1;
//            }
//        },
//    });

//    return result;
//}