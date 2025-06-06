<!DOCTYPE html>
    <!-- START @HEAD -->
    <head>
        <!-- START @META SECTION -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title><?php echo $title; ?></title>
        <!--/ END META SECTION -->
        
        <!-- START @CORE PLUGINS -->
        <script src="<?php echo base_url()?>assets/assets/global/plugins/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url()?>assets/assets/global/plugins/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url()?>assets/assets/global/plugins/bower_components/jquery-validation/dist/jquery.validate.min.js"></script>
        <!--/ END CORE PLUGINS -->
        
        <!-- START @FONT STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700" rel="stylesheet">
        <!--/ END FONT STYLES -->

        <!-- START @GLOBAL MANDATORY STYLES -->
        <link href="<?php echo base_url()?>assets/assets/global/plugins/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url()?>assets/assets/global/plugins/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!--/ END GLOBAL MANDATORY STYLES -->

        <!-- START @PAGE LEVEL STYLES -->
        <link href="<?php echo base_url()?>assets/assets/global/plugins/bower_components/fontawesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo base_url()?>assets/assets/global/plugins/bower_components/animate.css/animate.min.css" rel="stylesheet">
        <!--/ END PAGE LEVEL STYLES -->

        <!-- START @THEME STYLES -->
        <link href="<?php echo base_url()?>assets/assets/admin/css/reset.css" rel="stylesheet">
        <link href="<?php echo base_url()?>assets/assets/admin/css/layout.css" rel="stylesheet">
        <link href="<?php echo base_url()?>assets/assets/admin/css/components.css" rel="stylesheet">
        <link href="<?php echo base_url()?>assets/assets/admin/css/plugins.css" rel="stylesheet">
        <link href="<?php echo base_url()?>assets/assets/admin/css/themes/blue-gray.theme.css" rel="stylesheet" id="theme">
        <link href="<?php echo base_url()?>assets/assets/admin/css/pages/sign.css" rel="stylesheet">
        <link href="<?php echo base_url()?>assets/assets/admin/css/custom.css" rel="stylesheet">
        <!--/ END THEME STYLES -->      
    </head>
    <!--/ END HEAD -->
    <body class="page-sound">
        <!-- START @SIGN WRAPPER -->
        <div id="sign-wrapper" >
        <?php $success_message = $this->session->flashdata('success_message');
        if(isset($success_message) && !empty($success_message)){?><div class="alert alert-success alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><strong><?php echo $success_message;?></strong></div><?php } ?>
            <!-- Brand -->
            <div class="brand">
               <img class="domain_logo" src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo"/> 
            </div>
            <!--/ Brand -->
            <?php $this->load->view($content_view);?>
        </div><!-- /#sign-wrapper -->
        <!--/ END SIGN WRAPPER -->


    </body>
    <!-- END BODY -->
</html>