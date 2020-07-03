        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="fa fa-user"></i>  Page Content Management</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url()?>administrator/home">Administrator</a></li>
                            <li class="active">Manage Page</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content mt-3">
            <div class="card">
              
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="userData" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Page Title</th>
                                    <th>Group</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div> 
            </div>
            
            
            <div class="modal fade" tabindex="-1" role="dialog" id="modalForm">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">{{title}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id='formBuilder'></div>
                            <?=form_open('email/send', array('id'=> 'inputForm'))?>
                                <div class="col-md-12">
                                    
                                    
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="submitForm">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    <script>
        var table_data_src = '<?=base_url()?>restful/page/get';
        var submit_url = '<?=base_url()?>restful/page/form';
        var info_url = '<?=base_url()?>restful/page/detail/';
    </script>