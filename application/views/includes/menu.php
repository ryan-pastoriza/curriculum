<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">
        <img class="img img-responsive center-block" src="<?php echo base_url('assets/img/web/sms-logo-sm.fw.png') ?>">
      </a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li id="menu_gen_info" class="page_menu <?= ($pageID == 'gen_info')? 'active' : '' ?>">
          <a href="<?php echo base_url('gen_info') ?>">
            <center><i class="fa fa-edit fa-2x"></i></center>
            <small>Gen. Information</small>
          </a>
        </li>
        <li id="menu_curriculum" class="page_menu <?= ($pageID == 'curriculum')? 'active' : '' ?> ">
          <a href="<?php echo base_url('curriculum') ?>">
            <center><i class="fa fa-search fa-2x"></i></center>
            <small>Curriculum</small>
          </a>
        </li>
        <li id="menu_course_sched" class="page_menu <?= ($pageID == 'course_sched')? 'active' : '' ?> ">
          <a href="<?php echo base_url('course_sched') ?>">
            <center><i class="fa fa-book fa-2x"></i></center>
            <small>Course Schedule</small>
          </a>
        </li>
        <li id="menu_course_load" class="page_menu <?= ($pageID == 'course_load')? 'active' : '' ?> ">
          <a href="<?php echo base_url('course_load') ?>">
            <center><i class="fa fa-user fa-2x"></i></center>
            <small>Inst. Course Loading</small>
          </a>
        </li>
        <li id="menu_cpanel" class="page_menu <?= ($pageID == 'cpanel')? 'active' : '' ?> ">
          <a href="<?php echo base_url('cpanel') ?>">
            <center><i class="fa fa-gears fa-2x"></i></center>
            <small>C-Panel</small>
          </a>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right hidden-xs">
        <li class="hidden-sm hidden-md hidden-xs">
          <a style="padding-top:25px">
            <span class="day">Tuesday,</span> <span class="date">July 09, 2019</span> <span class="time">01:35:55 PM</span>
          </a>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <div class="pull-left p-r-10" style="border-right:1px solid #FFF">
              <p style="margin-bottom:0px">
                <small>Welcome:</small> <?= $userInfo['user_fname']. ' ' .$userInfo['user_lname'] ?></p>
              <center><?= $userInfo['user_position'] ?></center>
            </div>
            <img src="assets/img/profile_image/<?=$userInfo['user_image']?>" class="img img-responsive pull-right m-l-10"
                 style="height:40px;width:40px">
          </a>
          <ul class="dropdown-menu">
            <li><a href="settings"><i class="fa fa-gear"></i> Account Settings</a></li>
            <li><a href="<?= base_url('logout')?>"><i class="fa fa-sign-out"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>