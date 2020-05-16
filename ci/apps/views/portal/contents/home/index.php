<?php
$site = '';
$i = 0;
$il = 1;
$l = 4;
if(count($session['menu']) > 0){
    while($i < count($session['menu'])){
        if($il == 1){
            $site .= '<div class="card-deck-wrapper">
                <div class="card-deck mb-4">';
        }
        $il++;
        $site .= "
                    <div class=\"card {$session['menu'][$i]['site_color']} text-white\">
                        <div class=\"card-header text-uppercase font-weight-bold\">{$session['menu'][$i]['site_name']}</div>
                        <div class=\"card-body \">
                            <a href=\"".base_url().$session['menu'][$i]['permission_url']."\" target=\"_blank\" class=\"card-block stretched-link text-decoration-none\">
                                <div class='col-sm-6'>
                                    <i class=\"fa {$session['menu'][$i]['site_icon']} fa-2x text-white\"></i>
                                </div>
                                <div class='col-sm-6'>
                                    <p class=\"card-text float-left text-white\"> {$session['menu'][$i]['permission_name']}</p>
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
            
            
            <div class="col-12 col-md-12">
            <?=$site?>
            </div>
        </div>