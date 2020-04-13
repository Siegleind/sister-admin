
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="fas fa-home"></i>  <?=$body['page_title']?></h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url()?>">Portal</a></li>
                            <li class="active"><?=$body['page_title']?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content p-2 m-1">
            <div class="card">
                <div class="card-body card-block">
                    <?php
                        if($body['page_type'] == 1){
                            echo base64_decode($body['page_content']);
                        }else{
                            echo "<div class='embed-responsive embed-responsive-16by9'><iframe class='embed-responsive-item' src='".base64_decode($body['page_content'])."' allowfullscreen></iframe>";
                        }
                    ?>
                    
                </div>
            </div>
            
        </div>