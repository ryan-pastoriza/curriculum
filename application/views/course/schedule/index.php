<style type="text/css">
  .content{
    padding: 50px 15px!important;
  }
  #modalSubjectScheduling .modal-dialog.modal-lg{
    width:1300px!important;
  }
  .fc-toolbar {
    margin-bottom: 0px!important;
  }
  .select2-container.select2-container-multi.form-control {
    
     width: 100%!important; 
  }
  .tbl_subject td{
    border-top:none!important;
  }
  .tbl_subject tr{
    cursor: pointer
  }
</style>
<style type="text/css">
   ul.nav.nav-pills li a{
      padding:5px!important;
   }
   ul.nav.nav-pills li.active a{
      background: #0085b2;
   }
   .fc-widget-header {
       padding: 0px 1px !important;
   }
</style>


<div id="content" class="content" style="">
  <section class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-inline" id="frm-schedule" method="get" action="#">
                        <div class="form-group">
                            <label for="school_year">School Year</label>
                            <select name="school_year" id="sy" class="form-control input-sm">
                                <option disabled>Choose School Year</option>
                             
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
                        <div class="form-group" style="margin-left:10px;">
                          <label class="radio-inline"><input id="first-semester" type="radio" name="semester" <?php echo   $active = semester('first') ? 'checked':''; ?> value="first semester"> First Semester</label>
                        </div>
                          <div class="form-group" style="margin-left:10px;">
                          <label class="radio-inline"><input id="second-semester" type="radio" name="semester" <?php echo   $active = semester('second') ? 'checked':''; ?> value="second semester"> Second Semester</label>
                        </div>
                        <div class="form-group" style="margin-left:10px;">
                            <button type="submit" class="btn btn-success btn-sm" >display schedule</button>
                        </div>
                      
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-12">
                        <div class="col-md-2 pull-right">
                          <button onclick="loadScheduleList();$('#modalScheduleList').modal('show')" class="btn btn-info btn-sm pull-right">
                            Schedule List
                        </button>
                        </div>
                        <div class="col-md-2 pull-right">
                          <button onclick="$('#setSchedModal').modal('show');" class="btn btn-sm btn-success pull-right">Set Schedule</button>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>
    </section>
   <div class="col-md-12">
      <!-- LEFT -->
      <div class="col-lg-6">
         <div class="row p-t-5 p-b-5" style="background: #154360;color:#FFF;border-radius:5px">
            <div class="col-md-12 clearfix">Lecture Room <small class="pull-right">Total Rooms: <span id="roomCountLec"></span></small></div>
         </div>
         <div class="row m-t-5">
            <div id="lec-room-container" class="col-md-12 p-l-0 p-r-5">
               
            </div>
         </div>
      </div>
      <!-- RIGHT -->
     <div class="col-lg-6">
         <div class="row p-t-5 p-b-5" style="background: #154360;color:#FFF;border-radius:5px">
            <div class="col-md-12 clearfix">Laboratory Room <small class="pull-right">Total Rooms: <span id="roomCountLab"></span></small></div>
         </div>
         <div class="row m-t-5">
            <div id="lab-room-container" class="col-md-12 p-l-5 p-r-0">
            
            </div>
         </div>
      </div>
   </div>
</div>

<!-- MODAL -->
<?php //include("addSchedModal.php") ?>   

<!-- CONTEXT MENU EVENT -->
<div id="moveEventModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Move to room</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
         Select Room
         <select name="rooms" class="form-control input-sm">
         </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button onclick="moveConfirm()" type="button" class="btn btn-primary">OK</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<style type="text/css">
  .list-group-item {
      background-color: #333;
      color:#fff!important;
      font-size:12px;
      border:none!important;
      padding-top: 7px!important;
      padding-bottom: 7px!important;
  }
  .list-group-item:hover{
      background-color: #00698C !important;
      color:#FFF !important;
  }
  .list-group-item:hover{
   background-color: #00698C !important;
      color:#FFF !important;
  }
</style>

<div class="list-group" id="contextMenu" style="display:none;z-index:1000;width:150px">
  <!-- <a href="#" onclick="duplicateEvent()" class="list-group-item".><i class="fa fa-copy"></i> Duplicate</a> -->
  <a href="#" onclick="moveEvent()" class="list-group-item"><i class="fa fa-mail-forward"></i> Move</a>
  <!-- <a href="#" onclick="" class="list-group-item"><i class="fa fa-times"></i> Remove</a> -->
</div>

<div id="modalScheduleList" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Schedule List</h4>
      </div>
      <div class="modal-body">
        <table id="tblSchedule" class="table table-bordered table-hover table-striped">
         <thead>
            <tr>
               <th width="10%">Section Code</th>
               <th>Program</th>
               <th>Year</th>
               <th>Semester</th>
               <th>SY</th>
               <th>Status</th>
               <th></th>
            </tr>
         </thead>
         <tbody>
          
         </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modalSubjectScheduling" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <div class="row">
                    <div class="col-md-2">
                        <small>Section Code</small>
                        <div style="font-size:15px;color:#337ab7" id="section-detail"></div>
                    </div>
                    <div class="col-md-1">
                        <small>Year</small>
                        <div style="font-size:15px;color:#337ab7" id="year-detail"></div>
                    </div>
                    <div class="col-md-4">
                        <small>Curriculum</small>
                        <div style="font-size:15px;color:#337ab7" id="program-detail"></div>
                        <div style="font-size:15px;color:#337ab7" id="major-detail"> </div>
                    </div>
                    <div class="col-md-4">
                        <small>Semester / School Year</small>
                        <div style="font-size:15px;color:#337ab7"><span id="semester-detail"></span> - <span
                                    id="sy-detail"></span></div>
                    </div>
                </div>
            </div>
            <div class="modal-body">

                <div  style="overflow:auto; height:500px;">
                    <!-- <div class="col-md-3">
                      <label><b>Set Schedule</b></label>
                      <div class="form-group">
                        Time Start
                        <input type="text" class="form-control input-sm">
                      </div>
                      <div class="form-group">
                        Time End
                        <input type="text" class="form-control input-sm">
                      </div>
                      <div class="form-group">
                        Room Suggestions
                        <select class="form-control input-sm"></select>
                      </div>
                      <div class="form-group">
                        <button style="width:100px" class="btn btn-success pull-right">Save</button>
                      </div>
                    </div> -->
                    <div class="col-md-4" >
                        <div class="row">
                            <div class="col-md-12" >
                                <!-- <h4>Subject List</h4> -->
                                <!-- <label><b>Subject List</b></label><br> -->

                                <div>
                                    <ul class="nav nav nav-pills" role="tablist" id="presentation-tab">
                                        <li tab-val="lecture" role="presentation" class="active"><a href="#home" aria-controls="home"
                                                                                  role="tab"
                                                                                  data-toggle="tab">Lecture</a></li>
                                        <li tab-val="laboratory" role="presentation"><a href="#profile" aria-controls="profile" role="tab"
                                                                   data-toggle="tab">Laboratory</a></li>       
                                    </ul>
                                    <div class="tab-content" style="overflow:scroll; height:400px;">
                                        <div role="tabpanel" class="tab-pane fade in active" id="home">
                                            <table class="table tbl_subject table-striped table-hover" id="table-subject-lecture">
                                                <thead class="hide">
                                                <tr>
                                                    <th>#</th>
                                                    <th class="hidden"></th>
                                                    <th class="hidden"></th>
                                                    <th class="hidden"></th>
                                                    <th class="hidden"></th>
                                                    <th class="hidden"></th>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>#</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="profile">
                                            <table class="table table-hover table-striped tbl_subject" id="table-subject-lab">
                                                <thead class="hide">
                                                <tr>
                                                    <th>#</th>
                                                    <th class="hidden"></th>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>#</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                            </div>
                           
                            
                        </div>
                    </div>
                    <div class="col-md-8">
                          <div class="row">
                              <div class="col-md-4">
                                     <section class="panel panel-info">
                                          <div class="panel-heading">
                                              <h3 class="panel-title">Schedule</h3>
                                          </div>
                                          <div class="panel-body">
                                              <div class="form-group">
                                                  Room Suggestions
                                                  <select class="form-control input-sm" id="select-room" name="room"></select>
                                              </div>
                                              <div class="form-group">
                                                  Day
                                                  <select id="selected-days" class="form-control select2 input-sm"
                                                          multiple="multiple" data-placeholder="Select Day Schedule">
                                                      <?php if (!empty($sched_day)): ?>
                                                          <?php foreach ($sched_day as $day): ?>
                                                              <option data-composition="<?php echo $day['composition']; ?>" value="<?php echo $day['sd_id']; ?>"><?php echo ucwords(strtolower($day['composition'])) ?>
                                                                  - <?php echo strtoupper($day['abbreviation']); ?></option>
                                                          <?php endforeach; ?>
                                                      <?php endif; ?>
                                                  </select>
                                              </div>
                                              <div class="form-group">
                                                  Time Start
                                                  <select id="select-time-start" class="form-control input-sm"></select>
                                              </div>
                                               <div class="form-group">
                                                  Time End
                                                  <select id="select-time-end" class="form-control input-sm"></select>
                                              </div>
                                              <div class="form-group">
                                                  <button style="width:100px" class="btn btn-success pull-right"
                                                          id="btn-save">Add
                                                  </button>
                                              </div>
                                          </div>
                                      </section>
                                      <div class="col-md-12">
                                         <h4>Add Subject</h4>
                                         <select id="subject-edit-list" multiple="multiple" name="subject-edit-list" class="form-control input-sm" data-placeholder="Select Subject">
                                                    <?php if (!empty($subjectList)): ?>
                                                        <?php foreach ($subjectList as $subject): ?>
                                                            <option><?= strtoupper($subject->subj_name)?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>       
                                          </select>
                                      </div>
                              </div>  
                              <div class="col-md-8">
                                  <div class="panel panel-info">
                                      <div class="panel-heading clearfix">
                                          <label class="text-white"><strong id="room-display-name">No Room
                                                  Selected</strong></label>
                                      </div>
                                      <div class="panel-body p-t-0 p-l-10 p-r-10 p-b-10" style="border:1px solid #CCC">
                                          <div class="m-t-0" id="subject-schedule-calendar"></div>
                                      </div>
                                  </div>
                              </div>
                          </div>  
                      </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="confirm()" >Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-schedule fade" id="modal-schedule" data-backdrop="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Sections List</h4>
      </div>
      <div class="modal-body">
                <table class="table" id="sections-list">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Section/Subject</th>
                        <th>Room</th>
                        <th>Schedule</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
      </div>
    </div>
  </div>
</div>
<!-- SET SECTION MODAL -->
<style type="text/css">
  #subjectListTable_filter{
    display: none;
  }
</style>
<div id="setSchedModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body" style="padding-bottom:0px">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <br>

                <form class="form-horizontal">
                    <fieldset>
                        <legend class="pull-left width-full">Set Section</legend>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-md-5 control-label">Year lvl.</label>
                                    <div class="col-md-7">
                                        <select id="yearlvl"  class="form-control input-sm set_section_select" name="year_lvl">
                                            <option value='' selected="selected" class="hide">Select year level</option>
                                            <optgroup label="College">
                                            <option value="First Year">1st</option>
                                            <option value="Second Year">2nd</option>
                                            <option value="Third Year">3rd</option>
                                            <option value="Fourth Year">4th</option>
                                            <option value="Fifth Year">5th</option>
                                            </optgroup>
                                            <optgroup label="Senior High">
                                                <option value="grade 11">Grade 11</option>
                                                <option value="grade 12">Grade 12</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Sem</label>
                                    <div class="col-md-9">
                                        <select id="semister" required class="form-control input-sm set_section_select" name="sem">
                                            <option value='' selected="selected" class="hide">Select Semester</option>
                                            <option value="First Semester">1st Semester</option>
                                            <option value="Second Semester">2nd Semester</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">S.Y.</label>
                                    <div class="col-md-9">
                                        <select id="schoolyear" required class="form-control input-sm set_section_select" name="sy">
                                            <option value='' selected="selected" class="hide">Select school year
                                            </option>
                    
                                           <?php for ($i = date('Y'); $i >= 2000; $i--): ?>
                                                 <option value="<?php echo $i.'-'.($i+1)?>"><?php echo $i.'-'.($i+1)?></option>
                                           <?php endfor; ?>

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend class="pull-left width-full">Set Course</legend>
                        <ul class="nav nav-pills">
                            <li class="active"><a href="#nav-pills-tab-1" tosave="subject" data-toggle="tab">Subject
                        
                            <li><a href="#nav-pills-tab-2" tosave="curriculum" data-toggle="tab">Curriculum</a></li>
                        </ul>
                        <div class="tab-content" style="margin-bottom:0px;padding:0px">

                            <!-- SUBJECT LIST -->
                            <div class="tab-pane fade active in" id="nav-pills-tab-1">
                                <div class="row">
                                    <div class="col-md-4 col-md-offset-8">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                      <i class="fa fa-search"></i>
                                                    </span>
                                                    <input onkeyup="searchSubject($(this).val())" type="text" placeholder="Search subject" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="overflow:auto;overflow-x:hidden;min-height:200px;max-height:300px;">
                                    <table id="example" class="table table-striped table-hover">
                                        <thead class="hide">
                                        <tr>
                                            <th></th>
                                            <th>Subject</th>
                                        </tr>
                                        </thead>
                                        <tbody id="subjectListTbody">
                   
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-pills-tab-2">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Program</label>
                                    <div class="col-md-9">
                                        <select id="program" required class="form-control input-sm">
                                            <option class="hide" selected value=''>Select program</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Major</label>
                                    <div class="col-md-9">
                                        <input value="no major" required id="major-content"
                                               class="form-control input-sm text-primary" readonly
                                               type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">Year lvl.</label>
                                            <div class="col-md-7">
                                                <select id="curryearlvl" required class="form-control input-sm"
                                                        name="year_lvl">
                                                    <option value='' selected="selected" class="hide">Select year level
                                                    </option>
                                                    <option value="1st">1st Year</option>
                                                    <option value="2nd">2nd Year</option>
                                                    <option value="3rd">3rd Year</option>
                                                    <option value="4th">4th Year</option>
                                                    <option value="5th">5th Year</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Semester</label>
                                            <div class="col-md-9">
                                                <select id="currsemister" required class="form-control input-sm"
                                                        name="sem">
                                                    <option value='' selected="selected" class="hide">Select Semester
                                                    </option>
                                                    <option value="1st Semester">1st Semester</option>
                                                    <option value="2nd Semester">2nd Semester</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Revision</label>
                                            <div class="col-md-9">
                                                <select id="currsy" class="form-control input-sm"
                                                        name="sy"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- MODAL CONTENT -->

                                <!-- MODAL CONTENT -->

                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend class="pull-left width-full">Section Code</legend>
                        <div class="row">
                            <div class="col-md-3" style="border-right:1px solid #348fe2">
                                <span id="sectioncode" class="text-primary text-bold"
                                      style="font-size:24px">CS001</span>
                                <button onclick="generate_section_code()" type="button"
                                        class="btn btn-xs btn-default pull-right">generate
                                </button>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input id="ownsectioncode" type="text"
                                               class="form-control input-sm text-primary"
                                               placeholder="Set your own code">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Scheduled for:</label>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="radio" checked value="morning" name="schedule"> Morning |
                                        <input value="afternoon" type="radio" name="schedule"> Afternoon |
                                        <input value="evening" type="radio" name="schedule"> Evening
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="btn-set-sched" type="button" onclick="set_curriculum()" class="btn btn-primary">Set</button>
            </div>
        </div>
    </div>
</div>
<div class="list-group" id="contextMenus" style="display:none;z-index:1000;width:150px">
     <a href="#" onclick="removeSubjectFromInstructor()" class="list-group-item"><i class="fa fa-copy"></i> Remove subject</a>
  </div>
<div id="modalScheduleList" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Schedule List</h4>
      </div>
      <div class="modal-body">
        <table id="tblSchedule" class="table table-bordered table-hover table-striped">
          <thead>
            <tr>
              <th>Section Code</th>
              <th>Program</th>
              <th>Year</th>
              <th>Semester</th>
              <th>SY</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modalSubjects" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Subject Schedules</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->         
<style type="text/css">
    table tr.group.active:hover, table tr.group.success:hover{
        cursor: pointer;
    }
    select#selected-days {
        width: 100% !important;
    }
    div#table-subject > label {
        display: none;
    }
</style>