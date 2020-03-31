<?php
    $l_site= '';
    $i = 0;
    $o = 0;
    $menu = '';
    #exit(print_r($session));
    if(!empty($session['menu'])){
        while($i < count($session['menu'])){
            if($l_site != $session['menu'][$i]['permission_site']){
                if($o == 1){
                    $menu .= '
                        </ul>
                    </li>';
                    $o = 0;
                }
                if(isset($session['menu'][$i+1]) && $session['menu'][$i+1]['permission_site'] == $session['menu'][$i]['permission_site']){
                    $menu .= "
                    <li class='menu-item-has-children dropdown'>
                        <a href='#' class='dropdown-toggle' data-toggle='dropdown'> <i class='menu-icon fa {$session['menu'][$i]['site_icon']}'></i>{$session['menu'][$i]['site_name']}</a>
                        <ul class='sub-menu children dropdown-menu'>";
                    $o = 1;
                }else{
                    $menu .= "
                    <li><a href='".base_url().$session['menu'][$i]['permission_url']."'><i class=\"menu-icon fa {$session['menu'][$i]['site_icon']}\" target='_blank'></i>{$session['menu'][$i]['permission_name']}</a></li>";
                }
                $l_site = $session['menu'][$i]['permission_site'];
            }else{
                $menu .= "
                            <li><i class='menu-icon fa {$session['menu'][$i]['site_icon']}'></i><a href='".base_url().$session['menu'][$i]['permission_url']."'> {$session['menu'][$i]['permission_name']}</a></li>";
                if(!isset($session['menu'][$i+1]) && $o ==1){
                    $menu .= '
                        </ul>
                    </li>';
                    $o = 0;
                }
            }
            $i++;
        }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Portal BintangDelapan<?=(!empty($page) ? " - {$page}" : '')?></title>
    <meta name="description" content="Portal">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?=base_url();?>assets/images/favicon.jpg">


    <link rel="stylesheet" href="<?=base_url();?>assets/modules/sufee/assets/css/normalize.css">
    <link rel="stylesheet" href="<?=base_url();?>assets/modules/bootstrap-4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url();?>assets/modules/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=base_url();?>assets/modules/sufee/assets/css/themify-icons.css">
    <link rel="stylesheet" href="<?=base_url();?>assets/modules/sufee/assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?=base_url();?>assets/modules/sufee/assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link rel="stylesheet" href="assets/css/bootstrap-select.less"> -->
    <link rel="stylesheet" href="<?=base_url();?>assets/modules/sufee/assets/scss/style.css">
    <link href="<?=base_url();?>assets/modules/sufee/assets/css/lib/vector-map/jqvmap.min.css" rel="stylesheet">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <?php
        if(isset($option['stylesheet']) && count($option['stylesheet']) > 0){
            foreach($option['stylesheet'] as $css){
                echo "  <link rel='stylesheet' type='text/css' href='{$css}'>
        ";
            }
        }
    ?>
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
    <script src="<?=base_url();?>assets/modules/jquery/jquery-3.3.1.min.js"></script>
    <script src="<?=base_url();?>assets/modules/popper.js/dist/umd/popper.min.js"></script>
    <script src="<?=base_url();?>assets/modules/bootstrap-4.3.1/js/bootstrap.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <?php
    if(isset($option['jstop']) && count($option['jstop']) > 0){
        foreach($option['jstop'] as $js){
            echo "<script src='{$js}'></script>
    ";
        }
    }
?>
</head>
<body>


        <!-- Left Panel -->

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand brand-logo" href="#">Portal</a>
                <a class="navbar-brand hidden" href="#">PDB</a>
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav"><?=$menu?>

                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->

    <!-- Left Panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <header id="header" class="header" style="background:272c33; background-color:rgb(39, 44, 51);">

            <div class="header-menu">

                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa-th-list"></i></a>
                </div>
                <div class="user-area dropdown float-right">
                    <a href="#" class="dropdown-toggle nav-link text-white" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?=$session['user_name']?> <i class="fa fa-caret-down"></i>
                    </a>
                    <div class="user-menu dropdown-menu">
                        <a class="nav-link" href="#"><i class="fa fa-user"></i>&nbsp;&nbsp; Profile</a>
                        <a class="nav-link" href="<?=base_url()?>user/logout"><i class="fa fa-power-off"></i>&nbsp;&nbsp; Logout</a>
                    </div>
                </div>
                
            </div>

        </header><!-- /header -->
        <!-- Header-->
        <?=$content?>
        
        
    </div><!-- /#right-panel -->

    <!-- Right Panel -->

    <script src="<?=base_url();?>assets/modules/sufee/assets/js/plugins.js"></script>

<?php
    if(isset($option['js']) && count($option['js']) > 0){
        foreach($option['js'] as $js){
            echo "<script src='{$js}'></script>
    ";
        }
    }
?>
</body>
</html>
