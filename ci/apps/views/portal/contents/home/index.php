<?php
$site = '';
$i = 0;
$il = 1;
$l = 3;
while($i < count($dashboard)){
    if($il == 1){
        $site .= '<div class="card-deck-wrapper">
            <div class="card-deck mb-4">';
    }
    $il++;
    $site .= "
                <div class=\"card text-white {$dashboard[$i]['site_color']}\">
                    <div class=\"card-header text-uppercase font-weight-bold\">{$dashboard[$i]['site_name']}</div>
                    <div class=\"card-body \">
                        <a href=\"".base_url().$dashboard[$i]['permission_url']."\" target=\"_blank\" class=\"card-block stretched-link text-decoration-none\">
                            <div class='col-sm-6'>
                                <i class=\"fa {$dashboard[$i]['site_icon']} fa-2x text-white\"></i>
                            </div>
                            <div class='col-sm-6'>
                                <p class=\"card-text float-left text-white\"> Total {$dashboard[$i]['data_value']} Tickets.</p>
                            </div>
                        </a>
                    </div>
                </div>
            ";
    if($il == $l){
        $site .= '</div>
        </div>';
        $il = 1;
    }
    $i++;
}

?>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="fas fa-home"></i>  Dashboard</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url()?>">Portal</a></li>
                            <li class="active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content p-2 m-1 row">
            <div class="col-lg-4 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="mx-auto d-block">
                            <img class="rounded-circle mx-auto d-block" src="./assets/images/profiles/unknown.png" alt="Card image cap">
                            <h5 class="text-sm-center mt-2 mb-1"><?=$session['user_name']?></h5>
                            <div class="location text-sm-center"><i class="fa fa-key"></i> <?=$session['group']?></div>
                        </div>
                        <hr>
                        <div class="card-text text-sm-center">
                            <a href="#"><i class="fab fa-facebook pr-1"></i></a>
                            <a href="#"><i class="fab fa-twitter pr-1"></i></a>
                            <a href="#"><i class="fab fa-linkedin pr-1"></i></a>
                            <a href="#"><i class="fab fa-pinterest pr-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8">
            <?=$site?>
            </div>
        </div>