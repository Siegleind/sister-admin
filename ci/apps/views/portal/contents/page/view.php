
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
        <div class="content p-2 m-1 row">
            <div class="card">
                <div class="card-body">
                    <?=$body['page_content']?>
                    <br/>
                    
                    <iframe class="col-12 height-75" src="https://docs.google.com/forms/d/e/1FAIpQLScoasJLMOxRISkYYRZPsbsf8-Q0bYs8bdebQB1GB-v5vYAYqA/viewform?"></iframe>
                </div>
            </div>
            
        </div>