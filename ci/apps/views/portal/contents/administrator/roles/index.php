<?php
$last= '';
$loop = 0;
$o = 0;
$c = 1;
$menu = '';
if(isset($list_access)){
    while($loop < count($list_access)){
        if($last != $list_access[$loop]['permission_site']){

            $menu .= "
                                    <tr class='table-info'>
                                        <th colspan=\"2\">
                                            <label class=\"form-check-label\" for=\"inlineFormCheck{$list_access[$loop]['site_id']}\">
                                                {$list_access[$loop]['site_name']}
                                            </label>
                                            <input name='permission_number[]' value='{$list_access[$loop]['permission_id']}' class=\"ml-2 float-right site-access-click\" type=\"checkbox\" id=\"inlineFormCheck{$list_access[$loop]['site_id']}\" data-id='{$list_access[$loop]['site_id']}'>
                                        </th>
                                    </tr>
                                    ";
            $o = 1;
            $last = $list_access[$loop]['permission_site'];
        }else{

            if ($c >= 2 && $o == 1) {
                $menu .= '
                                    <tr>';
                $c = 0;
            }
            $menu .= "
                                        <td" . (isset($list_access[$loop + 1]) && $list_access[$loop + 1]['permission_site'] != $list_access[$loop]['permission_site'] ? ' colspan="2"' : '') . ">
                                            <input name='permission_number[]' value='{$list_access[$loop]['permission_id']}' class=\"ml-2 site-access\" type=\"checkbox\" id=\"itemCheck{$list_access[$loop]['permission_id']}\" data-id='{$list_access[$loop]['site_id']}' disabled>
                                            <label class=\"form-check-label\" for=\"itemCheck{$list_access[$loop]['permission_id']}\">
                                                {$list_access[$loop]['permission_description']}
                                            </label>
                                        </td>";
            if (!isset($list_access[$loop + 1]) && $o == 1) {
                $menu .= '
                                    </tr>';
                $o = 0;
            }
        }
        $c++;
        $loop++;
    }
}
?>
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="fa fa-user"></i>  Role Management</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="<?=base_url()?>administrator/home">Administrator</a></li>
                            <li class="active">Role Management</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content mt-3">
            <div class="card">
              
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="roleData" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Role Name</th>
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
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Create New Role</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?=form_open('email/send', array('id'=> 'roleAction'))?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="newRoleName">Role Name</label>
                                        <input id="newRoleAction" type="hidden" name="action_mode" value="create" required>
                                        <input id="newRoleID" name="role_id" type="hidden" maxlength="3">
                                        <input id="newRoleName" name="role_name" type="email" class="form-control" maxlength="50" required>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered"><?=$menu?>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="submitData"><i class="fa fa-save"></i> Save changes</button>
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
        var table_data_src = '<?=base_url()?>restful/role/list_paged';
        var submit_url = '<?=base_url()?>restful/role/do';
        var info_url = '<?=base_url()?>restful/role/detail/';
    </script>