<?php $this->load->view('hrms/admin/components/header'); ?>
<body>
    <?php $this->load->view('hrms/admin/components/user_profile'); ?>
    <div class="wrapper row-offcanvas row-offcanvas-left">	            
        <?php $this->load->view('hrms/admin/components/navigation'); ?>	
        <!-- Right side column. Contains the navbar and content of the page -->

        <div class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">

                <ol class="breadcrumb">
                    <?php echo $this->breadcrumbs->build_breadcrumbs(); ?>
                </ol>
            </section>

            <br/>
            <div class="container-fluid">
                <?php echo $subview ?>
            </div>

            <br />
            <footer>

                <hr />
                <div class="col-sm-4">&nbsp;</div>                    
                <p>&copy; Designed and Developed By <a target="_blank" href="#">Akhmad Rosaddy</a></p>

            </footer>

        </div><!-- /.right-side -->

    </div>

    <?php $this->load->view('hrms/admin/components/footer'); ?>     
