        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="fa fa-user"></i>  Permission Management</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url()?>administrator/home">Administrator</a></li>
                            <li class="active">Permission Management</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content mt-3">
            <div class="card">
              
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="siteData" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Site Name</th>
                                    <th>Action</th>
                                    <th>Action</th>
                                    <th>Action</th>
                                    <th>Action</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div> 
              
            </div>
        </div>
    <script>
        var table_data_src = '<?=base_url()?>restful/site/list_paged';
        var submit_url = '<?=base_url()?>restful/site/form';
        var info_url = '<?=base_url()?>restful/site/detail/';
    </script>