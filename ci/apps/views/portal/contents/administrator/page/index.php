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
            
            
            <div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" id="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Delete User</h4>
                        </div>
                        <div class="modal-body">
                            <p class="content-padding bg-danger">Are you sure?</p>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                <a href=""><button id="deleteconfirm" type="button" class="btn btn-danger">Ya</button></a>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" tabindex="-1" role="dialog" id="modalForm">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Create User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id='feedBack'></div>
                            <?=form_open('email/send', array('id'=> 'inputForm'))?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="formTitle">Title</label>
                                        <input id="formMode" type="hidden" name="mode" value="create" required>
                                        <input id="formID" type="hidden" name="form_id" required>
                                        <input id="formTitle" name="title" type="text" class="form-control" maxlength="50" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="formGroup">Group</label>
                                            <select id="formGroup" name="group" class="form-control">
                                                <?php foreach($site_list as $s): ?>
                                                <option value="<?=$s['site_id']?>"><?=$s['site_name']?></option>
                                                
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="formType">Type</label>
                                            <select id="formType" name="type" class="form-control">
                                                <option value="1">Dynamic</option>
                                                <option value="2">IFrame</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="formContentHTML">Content</label>
                                        <textarea id="formContentHTML" name="content" type="text" class="form-control"></textarea>
                                        <input type="text" id="formContentEmbed" name="content" class="d-none form-control" disabled>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label for="formStatus">Status</label>
                                            <select id="formStatus" name="status" class="form-control">
                                                <option value="1" selected>Published</option>
                                                <option value="2">Unpublished</option>
                                            </select>
                                        </div>
                                    </div>
                                    
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