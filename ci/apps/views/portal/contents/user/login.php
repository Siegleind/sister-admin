<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="language" content="en" />

    <!-- blueprint CSS framework -->
    
    <title>Portal - Login Page</title>
    
    
    <!-- Bootstrap core CSS -->
    <!-- Latest compiled and minified CSS -->
    <?=link_tag('assets/modules/bootstrap-4.1.3/css/bootstrap.min.css')?>
    <?=link_tag('assets/modules/font-awesome/css/font-awesome.min.css')?>
    <?=link_tag('assets/css/login.style.css')?>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?=link_tag('assets/images/Ico/favicon.png', 'shortcut icon', 'image/ico')?>
    <style>
    .loader {
         border-top: 16px solid blue;
         border-right: 16px solid green;
         border-bottom: 16px solid red;
         border-left: 16px solid pink;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .brand{
        border-radius: 0;
    }
    </style>
</head>
<body class="my-login-page">
    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-md-center h-100">
                <div class="card-wrapper">

                    <div class="card fat">
                        <div class="card-body">
                            <h4 class="card-title text-center">Portal</h4>
                            <div id='feedBack' class='bg-white border d-none'>
                                <ul>
                                </ul>
                            </div>
                            <div class="row">
                                <div class="brand col-lg-4">
                                    <?=img('assets/images/logo.png')?>
                                </div>
                                <div class="col-lg-8">
                                    <?=form_open('user/login/do', array('id'=>'loginForm'))?>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-user"></i></div>
                                                </div>
                                                <input id="inputName" type="email" class="form-control" name="ticket_email" value="" required autofocus autocomplete="login-user">
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <label for="password" class="">Password
                                                <a class="float-right d-none" style="cursor: pointer;" data-target="#pwdModal" data-toggle="modal">
                                                    Forgot Password?
                                                </a>
                                            </label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                                </div>
                                                <input id="inputPassword" type="password" class="form-control" name="ticket_password" required autocomplete="login-password">
                                                <div class="input-group-append" id="passwordToggle">
                                                    <div class="input-group-text"><i class="fa fa-eye-slash" id="eyeToggle" aria-hidden="true" style="cursor: pointer;"></i></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" name="remember" id="remember" class="custom-control-input">
                                                <label for="remember" class="custom-control-label">Remember Me</label>
                                            </div>
                                        </div>
                                        <div class="form-group <?=!empty($session['attempt']) && $session['attempt'] > 5 ? ''  : 'd-none'?>" id="recaptchaDiv">
                                        </div>
                                        <div class="form-group m-0">
                                            <input type="submit" name="login" class="btn btn-primary btn-block"<?=!empty($session['attempt']) && $session['attempt'] > 5 ? ' disabled' : ''?> value="Login">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        Copyright &copy; 2020 &mdash; ANM
                    </div>
                </div>
            </div>
        </div>
        <div class="loader d-none"></div>
    </section>
    <div id="pwdModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center">Forgot Password</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                              <p>Please fill your email address in the form below. We will immediately send the password reset url to your email address.</p>
                                <div class="panel-body">
                                    <fieldset>
                                        <div class="form-group">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-envelope"></i></div>
                                                </div>
                                                <input class="form-control input-md" placeholder="E-mail Address" name="email" type="email">
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-success btn-block">Send My Password</button>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
          </div>
      </div>
    </div>
    <script src="<?=base_url()?>assets/modules/jquery/jquery-1.12.3.min.js"></script>
    <!-- <script src='https://www.google.com/recaptcha/api.js'></script> -->
    <script src="<?=base_url()?>assets/modules/bootstrap-4.1.3/js/bootstrap.min.js"></script>
    <?=(isset($session['attempt']) && $session['attempt'] > 5) ? '<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>' : ''?>
    <script src="<?=base_url()?>assets/scripts/portal/login.js"></script>
    <script>
        var loaded = 0;
        var onloadCallback = function() {
            grecaptcha.render('recaptchaDiv', {
                'sitekey': '6LfvL5YUAAAAAO57VGvJ00CtQ3N3ypuNzPSBS3iM',
                'callback' : verifyCallback
            });
            loaded = 1;
        };
        var verifyCallback = function (response) {
            if (response.length == 0) { 
            
            }//reCaptcha not verified
            else {
                $("#loginForm input[name='login']").removeProp('disabled');
            }
        };
        
    </script>
</body>

</html>