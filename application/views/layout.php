<!DOCTYPE html>
<head>
      <title><?php echo $this->config->item('title');?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="1North">
        <meta name="keywords" content="1North">
        <meta name="author" content="1North">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Oswald:700,400" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,800,900" rel="stylesheet">
        <!--/ END FONT STYLES -->
        <link href="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url()?>assets/assets/global/plugins/bower_components/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
        <link href="<?php echo base_url()?>assets/assets/admin/css/pages/table-advanced.css" rel="stylesheet">
        <link href="<?php echo base_url()?>assets/assets/global/plugins/bower_components/datatables/css/dataTables.bootstrap.css" rel="stylesheet">
               <!--/ END GLOBAL MANDATORY STYLES -->

        <!-- START @PAGE LEVEL STYLES -->
        <link href="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/fontawesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/animate.css/animate.min.css" rel="stylesheet">
        
        <!-- HERE 4 -->
        <link href="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/dropzone/downloads/css/dropzone.css" rel="stylesheet">
       <!-- <link href="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/jquery.gritter/css/jquery.gritter.css" rel="stylesheet">-->
        <link href="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/bootstrap-tour/build/css/bootstrap-tour.min.css" rel="stylesheet">
        <!--/ END PAGE LEVEL STYLES -->

        <!-- START @THEME STYLES -->
        <link href="<?php echo base_url()?>/assets/assets/admin/css/reset.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/assets/admin/css/layout.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/assets/admin/css/components.css" rel="stylesheet">
        
        <link href="<?php echo base_url()?>/assets/assets/admin/css/themes/blue-gray.theme.css" rel="stylesheet" id="theme">
        <link href="<?php echo base_url()?>/assets/assets/admin/css/custom.css" rel="stylesheet">
        <link href="<?php echo base_url()?>/assets/assets/admin/css/responsive.css" rel="stylesheet">
        <!-- <link href="<?php echo base_url()?>/assets/assets/css/selectstyle.css" rel="stylesheet"> -->
        <!--/ END THEME STYLES -->
        <link rel="stylesheet" href="<?php echo base_url() ?>/assets/web-fonts/web-fonts.css" type="text/css" media="screen" />
        <link rel="icon" href="<?php echo base_url()?>/assets/images/favicon.png"/>
        <script src="<?php echo base_url('assets/new_assets/js/jquery.min.js')?>"></script>
        <script src="<?php echo base_url('assets/new_assets/js/jquery-ui-latest.js')?>"></script>
        <script src="<?php echo base_url('assets/new_assets/js/jquery-latest.js')?>"></script>
        <script src="<?php echo base_url()?>/assets/js/custom.js"></script>
        <script src="<?php echo base_url()?>/assets/js/newcustom.js"></script>

<script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/jquery-cookie/jquery.cookie.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/bootstrap/dist/js/bootstrap.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/typehead.js/dist/handlebars.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/typehead.js/dist/typeahead.bundle.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/jquery-nicescroll/jquery.nicescroll.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/jquery.sparkline.min/index.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/jquery-easing-original/jquery.easing.1.3.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/ionsound/js/ion.sound.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/bootbox/bootbox.js"></script>
        <!--/ END CORE PLUGINS -->
<script src="<?php echo base_url()?>assets/assets/global/plugins/bower_components/jasny-bootstrap-fileinput/js/jasny-bootstrap.fileinput.min.js"></script>
       <!-- export custom table data -->
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/custom_table_export/tableExport.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/custom_table_export/jquery.base64.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/custom_table_export/html2canvas.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/custom_table_export/sprintf.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/custom_table_export/jspdf.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/custom_table_export/base64.js"></script>
        <!-- START @PAGE LEVEL PLUGINS -->
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/flot/jquery.flot.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/flot/jquery.flot.spline.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/flot/jquery.flot.categories.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/flot/jquery.flot.tooltip.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/flot/jquery.flot.resize.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/flot/jquery.flot.pie.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/dropzone/downloads/dropzone.min.js"></script>
                <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/skycons-html5/skycons.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/waypoints/lib/jquery.waypoints.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/counter-up/jquery.counterup.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/bootstrap-tour/build/js/bootstrap-tour.min.js"></script>
                <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/datatables/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/datatables/extentions/dataTables.select.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/datatables/extentions/dataTables.buttons.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/datatables/extentions/jszip.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/datatables/extentions/pdfmake.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/datatables/extentions/vfs_fonts.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/datatables/extentions/buttons.html5.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/datatables/extentions/buttons.print.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/datatables/extentions/full_numbers_no_ellipses.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/datatables/extentions/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/datatables/extentions/datatables.responsive.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/jquery-mockjax/jquery.mockjax.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/jquery.gritter/js/jquery.gritter.min.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/global/plugins/bower_components/bootstrap-datepicker-vitalets/js/bootstrap-datepicker.js"></script>
        
      <!--   <script type="text/javascript" src="https://www.dropbox.com/static/api/1/dropins.js" id="dropboxjs" data-app-key="<?php echo $this->config->item('dropbox_api_key');?>"></script> -->
       <link href="<?php echo base_url();?>assets/assets/css/main.css" rel="stylesheet">
       <!-- <link href="<?php echo base_url();?>assets/assets/admin/css/bootstrap-grid.css" rel="stylesheet"> -->    


        <script src="<?php echo base_url();?>assets/assets/admin/js/pages/blankon.mail.js"></script>
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/retina.js/dist/retina.min.js"></script>
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-wysihtml5/lib/js/wysihtml5-0.3.0.min.js"></script>
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-wysihtml5/src/bootstrap-wysihtml5.js"></script>
<script src="<?php echo base_url();?>assets/assets/global/plugins/bower_components/bootstrap-fileupload/js/bootstrap-fileupload.min.js"></script>

<script>
        var base_url = "<?php echo base_url();?>";
</script>
</head>
<body class="page-session page-header-fixed page-footer-fixed page-sidebar-fixed demo-dashboard-session">
    <!-- START @WRAPPER -->
    <section id="wrapper">
   <?php
   $this->load->view('header');
   $this->load->view('leftmenu');
   $this->load->view('footer');
   ?>
        <link href="<?php echo base_url()?>/assets/new_assets/css/plugins.css" rel="stylesheet">
        <script src="<?php echo base_url()?>/assets/new_assets/js/apps.js"></script>
        <script src="<?php echo base_url()?>/assets/new_assets/js/select2.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/admin/js/pages/blankon.table.advanced.js"></script>
        <script src="<?php echo base_url()?>/assets/assets/admin/js/demo.js"></script>
        <script>
              setTimeout(function(){
                $( ".alert" ).fadeOut(3000);
            },3000);
        </script>
        <link rel="stylesheet" href="<?php echo base_url();?>/assets/css/jquery-ui.css">
        <script src="<?php echo base_url();?>/assets/css/jquery-ui.js?>"></script>
        <style type="text/css">
             tr.highlight td{
                    background-color: #d4edda !important;  /* Soft green */
                    animation: fadeOutHighlight 3s ease forwards;
             }
        </style>

        <script type="text/javascript">            
            const publicVapidKey = 'YOUR_PUBLIC_VAPID_KEY';

            if ('serviceWorker' in navigator && 'PushManager' in window) {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        return registration.pushManager.getSubscription()
                            .then(async subscription => {
                                if (subscription) return subscription;

                                return await registration.pushManager.subscribe({
                                    userVisibleOnly: true,
                                    applicationServerKey: urlBase64ToUint8Array(publicVapidKey)
                                });
                            });
                    }).then(subscription => {
                        fetch('/subscription/save', {
                            method: 'POST',
                            body: JSON.stringify(subscription),
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        });
                    });
            }

            function urlBase64ToUint8Array(base64String) {
                const padding = '='.repeat((4 - base64String.length % 4) % 4);
                const base64 = (base64String + padding)
                    .replace(/\-/g, '+')
                    .replace(/_/g, '/');

                const rawData = atob(base64);
                return new Uint8Array([...rawData].map(char => char.charCodeAt(0)));
            }

        </script>
<body>
</html>