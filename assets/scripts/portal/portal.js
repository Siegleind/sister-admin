 $(document).ready(function(){
    $("#loginForm").submit(function(event){
        // cancels the form submission
        event.preventDefault();
        
        if ($.trim($("#inputName").val()) === "" || $.trim($("#inputPassword").val()) === "") {
            return false;
        }else{
            var me = $(this);
            var forms = $("#loginForm");
            loginForm(me, forms);
        }
    });

     $('#menuToggle').on('click', function(event) {
         $('body').toggleClass('open');
     });

     $('.search-trigger').on('click', function(event) {
         event.preventDefault();
         event.stopPropagation();
         $('.search-trigger').parent('.header-left').addClass('open');
     });

     $('.search-close').on('click', function(event) {
         event.preventDefault();
         event.stopPropagation();
         $('.search-trigger').parent('.header-left').removeClass('open');
     });
});

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
        url: "./login/do",
		dataType: "json",
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
                if(login_portal() == 1){
                    location.reload();
                }else{
                    console.log('Unknown Error');
                }

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
    return false;
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
function login_portal(){
    var forms = $("#loginForm");
    $.ajax({
        type: "POST",
        url: "http://portal.sushitei.co.id/sister/login_portal.html",
        dataType: "json",
        xhrFields: {
          withCredentials: true
        },
        data: forms.serialize(),
        success: function(resp_data){
            if(resp_data.success == 1){
                return 1;
            } else if(resp_data.success == 0) {
                return resp_data.message;
            } else {
                return 'Unknown Error: 3';
            }
        },
    });

}