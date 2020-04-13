<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="fa fa-user"></i>  Site Management</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li><a href="<?=base_url()?>administrator/home">Administrator</a></li>
                    <li class="active">Site Management</li>
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
                            <th>Site ID</th>
                            <th>Site Name</th>
                            <th>Site Color</th>
                            <th>Site Icon</th>
                            <th>Site Status</th>
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
                    <h5 class="modal-title">Create New Site</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id='feedBack' class='bg-white border'>
                        <ul>
                        </ul>
                    </div>
                    <?=form_open('email/send', array('id'=> 'siteAction'))?>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="newSiteName">Site Name</label>
                                <input id="newSiteAction" type="hidden" name="action_mode" value="create" required>
                                <input id="newSiteID" name="site_id" type="hidden" maxlength="3">
                                <input id="newSiteName" name="site_name" type="email" class="form-control" maxlength="50" required>
                            </div>
                            <div class="form-group">
                                <label for="newSiteColor">Site Color</label>
                                <input id="newSiteColor" name="site_color" type="text" class="form-control" maxlength="15" required>
                            </div>
                            <div class="form-group">
                                <label for="newSiteIcon">Site Icon</label>
                                <input id="newSiteIcon" name="site_icon" type="text" class="form-control" maxlength="30" required>
                            </div>
                            <div class="form-group">
                                <label for="newSiteStatus">Site Status</label>
                                <select id="newSiteStatus" name="site_status" class="form-control">
                                    <option value='1'>Enabled</option>
                                    <option value='0'>Disabled</option>
                                </select>
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
                        <i id='modalIcon' class="fa fa-check icon-3x"></i>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <h4 id='modalTitle'>Great!</h4>
                    <p id="modalMessage">Site successfully created.</p>
                    <button class="btn btn-success" data-dismiss="modal"><i class="fa fa-play"></i> Continue</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var table_data_src = '<?=base_url()?>restful/site/list_paged';
var submit_url = '<?=base_url()?>restful/site/form';
var info_url = '<?=base_url()?>restful/site/detail/';
</script>