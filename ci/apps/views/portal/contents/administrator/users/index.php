        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="fa fa-user"></i>  User Management</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url()?>administrator/home">Administrator</a></li>
                            <li class="active">User Management</li>
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
                                    <th>Email Address</th>
                                    <th width="25%">Display Name</th>
                                    <th width="20%">User Group</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
            
            <div class="modal fade" tabindex="-1" role="dialog" id="modalAction">
                <div class="modal-dialog modal-dialog-lg" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Create User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id='feedBack'></div>
                            <?=form_open('email/send', array('id'=> 'userAction'))?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="newEmail">Email</label>
                                        <input id="newUserMode" type="hidden" name="action_mode" value="create" required>
                                        <input id="newUserID" type="hidden" name="user_id" required>
                                        <input id="newEmail" name="email" type="email" class="form-control" maxlength="50" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="newDName">Display Name</label>
                                        <input id="newDName" name="display_name" type="text" class="form-control" maxlength="50" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="newPassword">Password</label>
                                            <input id="newPassword" name="new_password" type="password" value="" class="form-control" maxlength="50">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="newConfirm">Confirm Password</label>
                                            <input id="newConfirm" name="confirm_password" type="password" value="" class="form-control" maxlength="50">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label for="newGroup">User Type</label>
                                            <select id="newGroup" name="group" class="form-control">
                                                <?php foreach($role as $r):?>
                                                <option value='<?=$r['role_id']?>'><?=$r['role_name']?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="newStore">Store</label>
                                            <select id="newStore" name="outlet" class="form-control">
                                                <?php foreach($store as $s):?>
                                                <option value='<?=$s['store_id']?>'><?=$s['store_name']?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label for="newStatus">Status</label>
                                            <select id="newStatus" name="status" class="form-control">
                                                <option value="0">Disabled</option>
                                                <option value="1" selected>Active</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="submitNew">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div id="modalSuccess" class="modal fade">
                <div class="modal-dialog modal-confirm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="icon-box mt-0 mx-auto">
                                <i class="fa fa-check icon-3x"></i>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body text-center">
                            <h4>Great!</h4>
                            <p>User account successfully created.</p>
                            <button class="btn btn-success" data-dismiss="modal"><i class="fa fa-play"></i> Continue</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script>
        var table_data_src = '<?=base_url()?>restful/user/list_paged';
        var submit_url = '<?=base_url()?>restful/user/form';
        var info_url = '<?=base_url()?>restful/user/detail/';
    </script>