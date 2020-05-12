<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <title>School System-Curriculum | <?php echo $title; ?></title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
  <!--   <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet"> -->
    <link href="<?php echo base_url('assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css');?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css');?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/plugins/font-awesome/css/font-awesome.min.css');?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/css/animate.min.css');?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/css/style.min.css');?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/css/style-responsive.min.css');?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/css/theme/default.css');?>" rel="stylesheet" id="theme" />
    <script src="<?php echo base_url('assets/plugins/pace/pace.min.js');?>"></script>
</head>
<body class="pace-top">
<div id="page-loader" class="fade in"><span class="spinner"></span></div>
<div id="page-container" class="fade">
    <div class="error">
        <div class="error-code m-b-10">403 <i class="fa fa-warning"></i></div>
        <div class="error-content">
            <div class="error-message"><?= $heading?></div>
            <div class="error-desc m-b-20">
               <?= $message?>
            </div>
            <div>
                <a href="<?php echo base_url('login');?>" class="btn btn-success">Go Back to Home Page</a>
            </div>
        </div>
    </div>
    <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>

</div>
<script src="<?php echo base_url('assets/plugins/jquery/jquery-1.9.1.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/jquery/jquery-migrate-1.1.0.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/apps.min.js');?>"></script>
<script>
  $(document).ready(function() {
    App.init();
  });
</script>
</body>
</html>

