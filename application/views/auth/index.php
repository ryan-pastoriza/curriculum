<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<!-- Mirrored from seantheme.com/color-admin-v1.9/admin/html/login_v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 15 Apr 2016 04:05:16 GMT -->
<head>
	<meta charset="utf-8" />
	<title>Curriculum | Login</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />

    <link rel="shortcut icon" href="<?php echo base_url('assets/img/web/sms-logo-sm.fw.png') ?>">
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<!-- <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet"> -->
    <link href="<?php echo base_url('assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/plugins/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/css/animate.min.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/css/style.min.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/css/style-responsive.min.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/css/theme/default.css') ?>" rel="stylesheet" id="theme" />
	<!-- ================== END BASE CSS STYLE ================== -->
	<style type="text/css">
        .login-v2{
                margin-top: 100px;
                box-shadow: #d6d6d6 1px 1px 1px 1px;
            }
        @media screen and (min-width: 480px) {
            .login-v2{
                width:400px;
            }
        }
    </style>
	<!-- ================== BEGIN BASE JS ================== -->
    <script src="<?php echo base_url('assets/plugins/pace/pace.min.js') ?>"></script>
	<!-- ================== END BASE JS ================== -->
</head>
<body class="pace-top" style="overflow:hidden">
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->

	<div class="login-cover">
	    <div class="login-cover-image"></div>
	    <div class="login-cover-bg" style="background: #fff"></div>
	</div>
	<!-- begin #page-container -->
	<div id="page-container" class="fade">
	    <!-- begin login -->
        <div class="login login-v2" data-pageload-addclass="animated fadeIn" style="background: #ddd !important;">

            <div class="login-content" style="width:400px;padding-bottom: 15px">
                <img class="img img-responsive" src="<?php echo base_url('assets/img/web/sms-logo.fw.png') ?>">
                <hr style="border-color:rgba(210, 172, 133, 0.64);" />
                <div class="clearfix">

                    <img class="img pull-right m-b-10" style="width:60%" src="<?php echo base_url('assets/img/web/login-logo.fw.png') ?>">
                </div>
                <?php if (!empty(validation_errors())): ?>
                    <?php echo validation_errors('<div class="danger text-center" style="color: #761c19;margin-bottom: 2%">', '</div>'); ?>
                <?php endif; ?>
                <?php echo form_open(base_url().'auth/verifyLogin', 'class="form"'); ?>
                <span style="color:#761c19; margin-bottom: 2%;">
                    <?php
                        if(isset($error)){
                            echo $error;
                        }
                    ?>

                </span>
                 <div class="form-group m-b-5 has-error">
                        <div class="input-group">
                            <span class="input-group-addon" style="background:#154360;color:#fff">
                                <i class="fa fa-user"></i>
                            </span>
                            <input autofocus value="<?php echo set_value('username'); ?>" name="username" type="text" style="color:#333" class="form-control bg-white" placeholder="Username" autocomplete="off" />
                        </div>
                    </div>
                    <div class="form-group m-b-5">
                        <div class="input-group">
                            <span class="input-group-addon" style="background:#154360;color:#fff">
                                <i class="fa fa-lock"></i>
                            </span>
                            <input autocomplete="off"  name="password" type="password" style="color:#333" class="form-control bg-white" placeholder="Password"/>
                        </div>
                    </div>

                    <div class="login-buttons clearfix">
                        <button style="background:#154360;border-color:#154360;width:100px" type="submit" class="btn btn-success pull-right m-t-15">Login</button>
                    </div>

                    <center class="m-t-20"><small>Copyright &copy; <?php echo date('Y') ?> EngTech Global Solutions Inc.</small></center>
                <!-- </form> -->
                <?php echo form_close(); ?>
            </div>
        </div>
        <!-- end login -->

	</div>
	<!-- end page container -->

	<!-- ================== BEGIN BASE JS ================== -->
    <script src="<?php echo base_url('assets/plugins/jquery/jquery-1.9.1.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery/jquery-migrate-1.1.0.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
    <!--[if lt IE 9]>
        <script src="assets/crossbrowserjs/html5shiv.js"></script>
        <script src="assets/crossbrowserjs/respond.min.js"></script>
        <script src="assets/crossbrowserjs/excanvas.min.js"></script>
    <![endif]-->
    <script src="<?php echo base_url('assets/plugins/slimscroll/jquery.slimscroll.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/plugins/jquery-cookie/jquery.cookie.js') ?>"></script>
    <!-- ================== END BASE JS ================== -->

    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="<?php echo base_url('assets/js/apps.min.js') ?>"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->

    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <!-- <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','../../../../www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-53034621-1', 'auto');
      ga('send', 'pageview');
    </script> -->
</body>

<!-- Mirrored from seantheme.com/color-admin-v1.9/admin/html/login_v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 15 Apr 2016 04:07:27 GMT -->
</html>

