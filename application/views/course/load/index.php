<style type="text/css">
  .numLeft{
    position:absolute;
    background:#154360;
    color:#FFF;
    padding-top:1px;
    font-size:40px;
    width:30px;
    height:60px;
    text-align:center;
    border-top-left-radius: 5px;
    border-bottom-left-radius:5px;
  }

  table#tblInstructorList>tbody>tr>td, table#tbl-subjects>tbody>tr>td{
    border: none!important;
    padding:5px;
    color: #0085B2!important;
    cursor: pointer;
  }
  table#tblCurriculum>tbody>tr>td{
    border: none!important;
    padding:5px!important;
    color: #0085B2!important;
    cursor: pointer;
  }
  table#tblCourse>tbody>tr>td{
    border: none!important;
    padding:5px!important;
    color: #0085B2!important;
    cursor: pointer;
    font-size:11px;
  }
  table#tblAllCourse>tbody>tr>td{
    border: none!important;
    padding:5px!important;
    color: #0085B2!important;
    cursor: pointer;
    font-size:11px;
  }
  table#tblSectionList>tbody>tr>td{
    border: none!important;
    padding:5px!important;
    color: #0085B2!important;
    cursor: pointer;
    font-size:11px;
    padding-left:0px;
  }
  table#tblOffSectionList>tbody>tr>td{
    border: none!important;
    padding:5px!important;
    color: #0085B2!important;
    cursor: pointer;
    font-size:11px;
    padding-left:0px;
  }
  .schedule-list{
    padding:0px;
    list-style: none;
    font-size:11px;
    color: #0085B2!important;
    font-weight:bold;
  }
  /*<ul class="nav nav-pills">
                <li class="active"><a href="#nav-pills-tab-1" data-toggle="tab">Block Section</a></li>
                <li><a href="#nav-pills-tab-2" data-toggle="tab">Off Sem Section</a></li>
              </ul>*/
  ul.nav.nav-pills li a{
    padding:5px!important;
  }
  ul.nav.nav-pills li.active a{
    background: #0085b2;
  }
  
  /*CALENDAR*/
  /*td.fc-today{
    background:none!important;
  }
  .fc-agenda-divider.fc-widget-header{
    display:none!important;
  }
  .fc-agenda-gutter.fc-widget-header.fc-last{
    display:none!important;
  }
  .fc-agenda-gutter.fc-widget-content.fc-last{
    display:none!important;
  }
  .fc-event{
    font-size:11px!important;
    
  }
  .fc-event .fc-event-time{
    font-size:10px!important;
  }*/
  #tbl-subjects tr.active{
    background: #e8ecf1!important;
  }
  .fc-widget-header {
      padding: 0px 1px !important;
  }

  #tblInstructorList_filter{
    display: none;
  }
</style>
<div id="content" class="content">
  <div class="row p-t-10 p-b-10 m-b-10" style="background: #154360;">
    <div class="col-lg-3 text-white">
      <span class="f-s-20">School Year:</span>
      <select id="selectSY" class="f-s-20" style="background:none;outline: none;border:none;color:#348fe2">
              <?php if (!empty($section_school_year)): ?>
                    <?php foreach ($section_school_year as $key => $section): ?>
                      <?php if($key == 'sy'){?>
                           <option value="<?= $section; ?>"><?= $section?></option>
                      <?php }?>
                  <?php endforeach; ?>
              <?php else: ?>
                  <option value="<?=  (date('Y').'-'.(date('Y') + 1));?>"> <?=  (date('Y').'-'.(date('Y') + 1));?></option>
                 
              <?php endif ?>
      </select>
    </div>
     <div class="col-lg-3 text-white">
      <span class="f-s-20">Semester</span>
      <select id="selectSem" class="f-s-20" style="background:none;outline: none;border:none;color:#348fe2">
        <option value="First Semester">First Semester</option>
        <option value="Second Semester">Second Semester</option>
      </select>
    </div>
  </div>
   <div class="row">
    <div class="col-md-3 p-l-0" style="position:relative;">
      <div class="numLeft">1</div>
      <div class="panel panel-info m-l-30">
        <div class="panel-heading no-radius">
          <div class="row">
            <div class="col-md-3">
              <h4 class="panel-title">Instructor List</h4>
            </div>
            <div class="col-md-9">
              <div class="input-group input-group-sm">
                        <span class="input-group-addon">
                  <i class="fa fa-search"></i>
                </span>
                <input onkeyup="searchInstructor($(this).val())" type="text" class="form-control" placeholder="Search instructor">
              </div>
            </div>
          </div>
        </div>
        <div class="panel-body p-t-10 p-l-10 p-r-10 p-b-10">
          <table id="tblInstructorList" class="table table-hover m-t-0" style="margin-top:0px!important">
            <thead class="hide">
            <tr>
              <th>Name</th>
              <th>Department</th>
            </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-4 p-l-3 p-r-3" style="vertical-align:bottom">

      <div class="row">
        <div class="col-md-12" style="position:relative;">
          <div class="numLeft">2</div>
          <div class="panel panel-info m-b-10 m-l-30">
            <div class="panel-heading no-radius">
              <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
              </div>
              <h4 class="panel-title">Subjects</h4>
            </div>
            <div class="panel-body p-l-5 p-t-5 p-r-5 p-b-5 table-responsive" style="display: block">
              <table id="tbl-subjects" class="table table-hover" style="margin-top:0px!important">
                <thead class="hide">
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12" style="position:relative;">
          <div class="numLeft">3</div>
          <div class="panel panel-info m-b-10 m-l-30">
            <div class="panel-heading no-radius">
              <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
              </div>
              <h4 class="panel-title">Course Schedule Preview</h4>
            </div>
            <div class="panel-body p-l-5 p-t-5 p-r-5 p-b-5 table-responsive" style="display: block;min-height: 100px" sched-preview>
                <h2 class='title'>SELECT A SUBJECT TO PREVIEW</h2>

            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-5 p-r-0">
      <div class="panel panel-info">
        <div class="panel-heading clearfix">
          <button
            onclick="window.open('<?php echo base_url("course_load/printCalendar?sem='+pubSem+'&sy='+pubSY+'&ins='+pubInsID+'") ?>','','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,width=1000,height=600')"
            class="btn btn-primary btn-sm pull-right"><i class="fa fa-print"></i> Print
          </button>
          <h4 class="panel-title">Instructors Schedule Preview</h4>
        </div>
        <div class="panel-body">
          <div id="calendar"></div>
        </div>
        <div class="panel-footer clearfix">
          <small class="pull-right">Total Unit Plotted: <span id="unit-plotted" class="badge badge-success">0</span></small>
        </div>
      </div>
    </div>
  </div>
  <div class="list-group" id="contextMenu" style="display:none;z-index:1000;width:150px">
     <a href="#" onclick="removeSubjectFromInstructor()" class="list-group-item"><i class="fa fa-copy"></i> Remove subject</a>
  </div>

</div>
