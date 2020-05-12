<!-- begin scroll to top btn -->
<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
<!-- end scroll to top btn -->
<footer class="footer">
  <div class="container-fluid clearfix text-center">
    <small class="text-white">Copyright © 2019 EngTech Global Solutions Inc.</small>
    <img class="pull-right img img-responsive" style="height:30px" src="<?= base_url('assets/img/web/ama-logo-sm.fw.png')?>">
  </div>
</footer>
</div>

<div class="modal fade" id="modalChangePic" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-picture-o"></i> Change Profile Picture</h4>
      </div>
      <form method="post" id="formChangeImage" action="<?php echo base_url('settings/changeUserImage') ?>" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div id="imageUploadError">

              </div>
           
              <input type="file" id="inputfile" class="hide" name="user_image">
              <button id="btnbrowseimage" class="btn btn-sm btn-info m-t-10" type="button" style="width:100%">Browse Photo</button>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end page container -->

<!-- ================== BEGIN BASE JS ================== -->

<script src="<?php echo base_url('assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/moment/moment.min.js'); ?>"></script>
<!--[if lt IE 9]>
<script src="assets/crossbrowserjs/html5shiv.js"></script>
<script src="assets/crossbrowserjs/respond.min.js"></script>
<script src="assets/crossbrowserjs/excanvas.min.js"></script>
<![endif]-->
<script src="<?php echo base_url('assets/plugins/slimscroll/jquery.slimscroll.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/jquery-cookie/jquery.cookie.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/icheck/icheck.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/pnotify/pnotify.custom.min.js'); ?>"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="<?php echo base_url('assets/plugins/DataTables/media/js/jquery.dataTables.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/fullcalendar2/fullcalendar.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-form-validation/js/formValidation.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-form-validation/js/framework/bootstrap.min.js'); ?>"></script>

<script src="<?php echo base_url('assets/plugins/mockjax/jquery.mockjax.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/moment/moment.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootbox.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/fullcalendar-rightclick.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/parsley/dist/parsley.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-wizard/js/bwizard.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-daterangepicker/moment.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-daterangepicker/daterangepicker.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/apps.min.js'); ?>"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
  function dd(data) {
    console.log(data);
  }
  $(document).ready(function () {
    App.init();
    // FormEditable.init();
  });
</script>

<script type="text/javascript">

  $(function () {


    // DISPLAY REAL TIME
    $(document).ready(function(){
      function clickClock () {

         var time = moment(new Date()).format('hh:mm:ss A');
         var date = moment(new Date()).format('MMMM DD, YYYY');
         var day = moment(new Date()).format('dddd')+",";
         
         $("nav.navbar .day").html(day);
         $("nav.navbar .date").html(date);
         $("nav.navbar .time").html(time);
         setTimeout(clickClock,1000);
      }
      clickClock();
    });

  });
</script>
</body>
</html>

