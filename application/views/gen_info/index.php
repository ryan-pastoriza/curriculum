
<div id="page-container" class="page-container fade page-without-sidebar page-header-fixed in">

   <style type="text/css">
      .btn-options{
      border-radius:5px;
      width:100%;background:#f3f3f3;height:75px;
      color:#428CA0;
      text-align:center;
      padding:5px;
      cursor:pointer;
      }
      .btn-options.active{
      color:#B3DBE8;
      background:#0085B2;
      }
      .btn-options:hover{
      color:#FFF;
      background:#B7DDE9;
      }
      #content-geninfo{
      padding:0px!important;
      background:none!important;
      }
      table.tblContent{
      border:none!important;
      }
      table.tblContent tbody tr td{
      padding:1px!important;
      padding-left:7px!important;
      }
      table.tblContent thead tr th{
      color: #0085B2!important;
      padding:5px!important;
      text-align: center;
      border: none!important;
      }
      table.tblContent thead tr{
      border: none!important;
      }
      table#tblInstructorList>tbody>tr>td{
      border: none!important;
      padding:5px;
      color: #0085B2!important;
      }
      table#tblServiceList>tbody>tr>td{
      border: none!important;
      padding:5px;
      color: #0085B2!important;
      }
      .fc-widget-header {
      padding: 0px 1px !important;
      }
   </style>
   <div id="content" class="content">
      <div class="row">
         <div class="col-md-2">
            <div class="row">
               <div class="col-md-12 ui-sortable">
                  <div class="panel panel-primary">
                     <div class="panel-heading">
                        <h4 class="panel-title">Options</h4>
                     </div>
                     <div class="panel-body">
                        <div class="row" role="tablist">
                           <div class="col-sm-6">
                              <div href="#tab-room" aria-controls="tab-room" data-toggle="tab" val="tab-room" class="btn-options active">
                                 <i class="fa fa-sign-in fa-4x"></i>
                                 <div>Room / Lab</div>
                              </div>
                           </div>
                           <div class="col-sm-6">
                              <div href="#tab-day" aria-controls="tab-day" data-toggle="tab" val="tab-day" class="btn-options">
                                 <i class="fa fa-calendar fa-4x"></i>
                                 <div>Day</div>
                              </div>
                           </div>
                        </div>
                        <div class="row m-t-10">
                           <div class="col-sm-6">
                              <div href="#tab-time" aria-controls="tab-time" data-toggle="tab" val="tab-time" class="btn-options">
                                 <i class="fa fa-clock-o fa-4x"></i>
                                 <div>Time</div>
                              </div>
                           </div>
                           <div class="col-sm-6">
                              <div href="#tab-course" aria-controls="tab-course" data-toggle="tab" val="tab-course" class="btn-options">
                                 <i class="fa fa-book fa-4x"></i>
                                 <div>Course</div>
                              </div>
                           </div>
                        </div>
                        <div class="row m-t-10">
                           <div class="col-sm-6">
                              <div href="#tab-instructor" aria-controls="tab-instructor" data-toggle="tab" val="tab-instructor" class="btn-options">
                                 <i class="fa fa-user-secret fa-4x"></i>
                                 <div>Instructor</div>
                              </div>
                           </div>
                           <div class="col-sm-6">
                              <div href="#tab-program" aria-controls="tab-program" data-toggle="tab" val="tab-program" class="btn-options">
                                 <i class="fa fa-graduation-cap fa-4x"></i>
                                 <div>Program</div>
                              </div>
                           </div>
                        </div>
                        <div class="row m-t-10">
                           <div class="col-sm-6">
                              <div href="#tab-others" aria-controls="tab-others" data-toggle="tab" val="tab-others" class="btn-options">
                                 <i class="fa fa-random fa-4x"></i>
                                 <div>Others</div>
                              </div>
                           </div>
                           <div class="col-sm-6">
                              <div href="#tab-semester-setup" aria-controls="tab-semester-setup" data-toggle="tab" val="tab-semester-setup" class="btn-options">
                                 <i class="fa fa-calendar-o fa-4x"></i>
                                 <div>Semester Setup</div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="panel panel-primary tab-inputs" id="room-inputs" tab-toggle="#tab-room">
                     <div class="panel-heading">
                        <h4 class="panel-title">Room Inputs</h4>
                     </div>
                     <div class="panel-body" style="padding:5px">
                          <!-- <form method="post" id="formAddRoom" action="<?php echo base_url('gen_info/roomSave') ?>"> -->
                                   <div class="error_message_room text-danger"><?php echo validation_errors(); ?></div>
                                  <?php echo form_open('gen_info/roomSave', 'id="formAddRoom"'); ?>
                                      <input type="hidden" name="rl_id">
                                      <input type="hidden" value="new" id="roomOpt">
                                      <div class="form-group m-b-5">
                                        Code
                                        <input data-parsley-required="true" autocomplete="off" name="room_code" type="text" class="form-control input-sm">
                                      </div>
                                      <div class="form-group m-b-5">
                                        Name
                                        <input data-parsley-required="true" autocomplete="off" name="room_name" type="text" class="form-control input-sm">
                                      </div>
                                      <div class="form-group m-b-5">
                                        Capacity
                                        <input data-parsley-required="true" data-parsley-type="digits" min="1" autocomplete="off" name="capacity" type="text" class="form-control input-sm">
                                      </div>
                                      <div class="form-group m-b-5">
                                        Type
                                        <!-- <input autocomplete="off" name="type" type="text" class="form-control input-sm"> -->
                                        <select data-parsley-required="true" class="form-control input-sm" name="type">
                                          <option value='' selected class="hide">Select type</option>
                                          <option value="Lecture">Lecture</option>
                                          <option value="Laboratory">Laboratory</option>
                                        </select>
                                      </div>
                                      <div class="form-group m-b-5">
                                        Location
                                        <input autocomplete="off" name="location" type="text" class="form-control input-sm">
                                      </div>
                                      <div class="form-group">
                                        Description
                                        <textarea autocomplete="off" name="desc" class="form-control"></textarea>
                                      </div>
                                      <div class="form-group clearfix">
                                        <button type="reset" class="hide">reset</button>
                                        <button style="width:100px" class="btn btn-sm btn-success pull-right">Save</button>
                                      </div>
                              </form>
                     </div>
                  </div>
                      <div class="panel panel-primary tab-inputs hide" id="time-inputs" tab-toggle="#tab-time">
                          <div class="panel-heading">
                            <h4 class="panel-title">Time Inputs</h4>
                          </div>
                          <div class="panel-body" style="padding:5px">
                            <form id="formAddTime" method="post" action="gen_info/timeSave">
                              <input type="hidden" name="st_id">
                              <table style="width:100%">
                                <thead class="bg-dark-blue">
                                <tr>
                                  <td class="p-5 col-md-6">Interval</td>
                                  <td class="p-5 col-md-6">Unit</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                  <td><input name="interval" type="text" class="form-control input-sm no-radius"></td>
                                  <td>
                                    <select name="unit" class="form-control input-sm no-radius border-l-none">
                                      <option>minutes</option>
                                      <option>hours</option>
                                    </select>
                                  </td>
                                </tr>
                                </tbody>
                              </table>
                              <div class="clearfix">
                                <span class="pull-right"><small>General Time Scale</small></span>
                              </div>
                              <div class="form-group m-b-5">
                                Time start
                                <input name="time_start" type="time" class="form-control input-sm">
                              </div>
                              <div class="form-group m-b-5">
                                Time end
                                <input name="time_end" type="time" class="form-control input-sm">
                              </div>
                              <div class="form-group">
                                <button onclick="showPreviewTimeSplit()" type="button" class="btn btn-xs btn-info">Set</button>
                              </div>
                              <button type="reset" class="hide"></button>
                              <div class="form-group clearfix">
                                <button style="width:100px" class="btn btn-sm btn-success pull-right">Save</button>
                              </div>
                            </form>
                          </div>
                        </div>


                         <div class="panel panel-primary tab-inputs hide" id="others-inputs" tab-toggle="#tab-others">
                            <div class="panel-heading">
                              <h4 class="panel-title">Others Inputs</h4>
                            </div>
                            <div class="panel-body" style="padding:5px">
                              <form method="post" id="formAddOtherSched" action="<?php echo base_url('gen_info/otherSave') ?>">
                                <input id="hddnOs" type="hidden" name="os_id">
                                <div class="form-group m-b-5">
                                  <label>Work</label>
                                  <input autocomplete="off" name="work_name" type="text" class="form-control input-sm">
                                </div>
                                <div class="form-group m-b-5">
                                  <label>Time Span</label>
                                  <div class="row">
                                    <div class="col-md-5">
                                      <input autocomplete="off" name="time_span" type="text" class="form-control input-sm">
                                    </div>
                                    <div class="col-md-7">
                                      <select class="form-control input-sm" name="time_unit">
                                        <option value="hour">hour</option>
                                        <option value="minute">minute</option>
                                        <option value="second">second</option>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label>Description</label>
                                  <textarea autocomplete="off" name="description" class="form-control"></textarea>
                                </div>
                                <div class="form-group clearfix">
                                  <button style="width:100px" class="btn btn-sm btn-success pull-right">Save</button>
                                  <button class="hide" type="reset">reset</button>
                                </div>
                              </form>
                            </div>
                          </div>

                          <div class="panel panel-primary tab-inputs hide" id="day-inputs" tab-toggle="#tab-day">
                            <div class="panel-heading">
                              <h4 class="panel-title">Day Inputs</h4>
                            </div>
                            <div class="panel-body" style="padding:5px">
                              <!-- <form id="formAddDay" method="post" action="gen_info/daySave"> -->
                              <div class="error_message_day text-danger"><?= validation_errors() ?></div>
                              <?= form_open('gen_info/daySave', 'data-parsley-validate="true" id="formAddDay"') ?>
                              <input type="hidden" name="sd_id">
                              <div class="form-group m-b-5">
                                <label>Abbreviation</label>
                                <input autocomplete="off" data-parsley-required="true" name="abbreviation" type="text" class="form-control input-sm">
                              </div>
                              <div class="form-group">
                                <label>Composition</label>
                                <input autocomplete="off" data-parsley-required="true" name="composition" type="text" class="form-control input-sm">
                              </div>
                              <div class="form-group clearfix">
                                <button style="width:100px" class="btn btn-sm btn-success pull-right">Save</button>
                              </div>
                              <button class="hide" type="reset">reset</button>
                              </form>
                            </div>
                          </div>

                          <!-- <div class="panel panel-primary tab-inputs hide" id="program-inputs" tab-toggle="#tab-program">
                            PROGRAMS
                          </div> -->

                          <div class="panel panel-primary tab-inputs hide" id="course-inputs" tab-toggle="#tab-course">
                            <div class="panel-heading">
                              <span class="panel-title">Course Inputs</span>
                              <span class="pull-right" style="line-height: 0px;"><input type="checkbox" id="shsCheckbox" style=""> <font ">SHS</font></span>
                            </div>
                            <div class="panel-body" style="padding:5px">
                              <div class="error_message text-danger"><?php echo validation_errors(); ?></div>
                              <?php echo form_open('gen_info/subjectSave', 'class="email" data-parsley-validate="true" id="formAddSubject"'); ?>

                              <input type="hidden" name="subj_id">
                                <div class="form-group m-b-5 hide" id="category-wrapper">
                                    <label for="category">Subject Category</label>
                                    <select name="sc_id" id="category" class="form-control">
                                        <option selected disabled >Select Category</option>
                                        <?php if (!empty($categories)):?>
                                            <?php foreach ($categories as $category):?>
                                                <option value="<?php echo $category->sc_id?>"><?php echo ucwords($category->category_name); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                              <div class="form-group m-b-5">
                                <label>Code</label>
                                <input autofocus data-parsley-required="false" name="subj_code" id="subj_code" type="text" class="form-control input-sm" autocomplete="off">
                              </div>
                              <div class="form-group m-b-5">
                                <label>Name</label>
                                <input id="subject-name" data-parsley-required="false" name="subj_name" list="subject-list" " class="form-control input-sm" autocomplete="off">
                                <datalist id="subject-list"></datalist>
                              </div>
                              <div class="form-group m-b-5">
                                <label>Lab. Unit</label>
                                <input data-parsley-type="number" min=0 data-parsley-required="true" name="lab_unit" class="form-control input-sm" autocomplete="off">
                              </div>
                              <div class="form-group m-b-5">
                                <label>Laboratory no. of hour per week</label>
                                <input data-parsley-type="number" min=0 data-parsley-required="true" name="lab_hour" type="text" class="form-control input-sm" autocomplete="off">
                              </div>
                              <div class="form-group m-b-5">
                                <label>Lec. Unit</label>
                                <input data-parsley-type="number" min=0 data-parsley-required="true" name="lec_unit" type="text" class="form-control input-sm" autocomplete="off">
                              </div>
                              <div class="form-group m-b-5">
                                <label>Lecture no. of hour per week</label>
                                <input data-parsley-type="number" min=0 data-parsley-required="true" name="lec_hour" type="text" class="form-control input-sm" autocomplete="off">
                              </div>
                              <div class="form-group m-b-5">
                                <label>Split No.</label>
                                <input data-parsley-type="number" min=1 data-parsley-required="true" name="split" type="text" class="form-control input-sm" autocomplete="off">
                              </div>
                              <div class="form-group">
                                <label>Description</label>
                                <textarea name="subj_desc" class="form-control" autocomplete="off"></textarea>
                              </div>
                              <div class="form-group clearfix">
                                <button class="hide" type="reset"></button>
                                <button type="button" id="btnSetRate" onclick="$('#modalRate').modal('show');$('#btnAddRating').trigger('click');" style="width:100px"
                                        class="btn btn-success btn-sm hide">Set Rate
                                </button>
                                <button type="submit" id="btnSaveSubject" style="width:100px" class="btn btn-sm btn-success pull-right">Save</button>
                              </div>
                              </form>
                            </div>
                          </div>

                          <section class="panel panel-primary tab-inputs hide" id="semester-setup-inputs" tab-toggle="#tab-semester-setup">
                              <div class="panel-heading">
                                  <span class="panel-title">Semester Inputs</span>
                              </div>
                              <div class="panel-body">
                                  <div class="error_message text-danger"><?php echo validation_errors(); ?></div>
                                  <?php echo form_open('gen_info/storeperiod', 'id="frm-period" class="email" data-parsley-validate="true" id="form-setup"'); ?>
                                  <fieldset>
                                      <legend><h5>Semester Information</h5></legend>
                                    <div class="form-group">
                                      <label for="sy">School Year</label>
                                      <select name="school_year" id="" class="form-control input-sm">
                                          <?php for ($i = date('Y') + 1; $i >= 2005; $i--): ?>
                                              <option value="<?php echo $i.'-'.($i + 1);?>" <?php echo $retVal = (date('Y') == $i) ? 'selected': '';?> ><?php echo $i.'-'.($i + 1);?></option>
                                          <?php endfor;?>

                                      </select>
                                  </div>
                                  <div class="form-group">
                                      <label for="semester">Semester</label>
                                      <select name="semester" id="semester" class="form-control input-sm">
                                          <option selected disabled>-Select Semester-</option>
                                          <option value="first semester">First Semester</option>
                                          <option value="second semester">Second Semester</option>
                                      </select>
                                  </div>
                                  <div class="form-group">
                                      <label for="date">Date</label>
                                      <input type="text" name="date_semester" class="form-control input-sm" id="date-semester">
                                  </div>
                                  </fieldset>
                                  <fieldset>
                                      <legend><h5>Period Information</h5></legend>
                                      <div class="form-group">
                                          <select name="period_id" id="period" class="form-control input-sm">
                                              <option selected disabled >-Select Period-</option>
                                              <?php if (!empty($periods)):?>
                                                <?php foreach ($periods as $period):?>
                                                      <option value="<?php echo $period['periodic_id']?>"><?php echo ucwords($period['period']);?></option>
                                                <?php endforeach; ?>
                                              <?php endif;?>
                                          </select>
                                      </div>
                                      <div class="form-group">
                                          <label for="date-period">Date</label>
                                          <input name="date_period" type="text" id="date-period" class="form-control input-sm">
                                      </div>
                                  </fieldset>
                                  <div class="form-group">
                                      <button class="btn btn-sm btn-success pull-right" type="submit">Save</button>
                                  </div>
                                  <?php echo form_close();?>

                              </div>
                          </section>
                  </div>
            </div>
          
         </div>
         <div class="col-md-10">
            <div class="tab-content" id="content-geninfo">
               <div role="tabpanel" id="tab-room" class="panel panel-primary tab-pane active">
                  <div class="panel-heading">
                     <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                     </div>
                     <h4 class="panel-title">Room / Lab List</h4>
                  </div>
                  <div class="panel-body">
                     <div id="conRoom">
                         <table id="tblRoom" class="table table-hover table-striped table-bordered tblContent" style="margin-top:0px!important">
                             <thead>
                                <tr>
                                  <th>Code</th>
                                  <th>Name</th>
                                  <th>Capacity</th>
                                  <th>Type</th>
                                  <th>Description</th>
                                  <th class="col-md-1"></th>
                                </tr>
                             </thead>
                              <tbody></tbody>
                        </table>
                     </div>
                     <div id="room_loading" class="col-md-12">
                         <center>
                            <i class="fa fa-spinner fa-pulse fa-5x text-success "></i>
                         </center>
                     </div>
                  </div>
               </div>
               <div role="tabpanel" id="tab-day" class="panel panel-primary tab-pane">
                  <div class="panel-heading">
                     <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                     </div>
                     <h4 class="panel-title">Day</h4>
                  </div>
                  <div class="panel-body">
                     <div id="conDay">
                          <table id="tblDay" class="table table-hover table-striped table-bordered tblContent" style="margin-top:0px!important">
                             <thead>
                                <tr>
                                  <th width="45%">Abbreviation</th>
                                  <th width="45%">Composition</th>
                                  <th width="10%">Action</th>
                                </tr>
                             </thead>
                             <tbody></tbody>
                        </table>
                     </div>
                     <div id="day_loading" class="col-md-12">
                         <center>
                            <i class="fa fa-spinner fa-pulse fa-5x text-success"></i>
                         </center>
                     </div>
                  </div>
               </div>
               <div role="tabpanel" id="tab-program" class="tab-pane">
                 <div class="col-md-4">
                    <div class="panel panel-primary">
                      <div class="panel-heading">
                        <h4 class="panel-title">Set-up</h4>
                      </div>
                      <div class="panel-body">
                        <!-- <form method="post" id="frmAddProgram" action="<?php echo base_url('gen_info/addProgram') ?>"> -->
                        <?php echo form_open("gen_info/updateProgram", ["id" => "frmAddProgram"]) ?>
                        <div class="form-group">
                          <label>Program</label>
                          <input name="prog_name" type="text" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                          <label>Abbreviation</label>
                          <div class="input-group">
                            <input name="prog_abv" id="abbvTextbox" readonly type="text" class="form-control">
                            <span class="input-group-addon">
                                          <input type="checkbox" id="abbvCheckbox"> <small>Define own abbreviation</small> 
                                        </span>
                          </div>
                        </div>
                        <div class="form-group">
                          <label>Program Code</label>
                          <div class="input-group">
                            <input name="prog_code" id="progcodeTextbox" readonly type="text" class="form-control">
                            <span class="input-group-addon">
                                          <input type="checkbox" id="progcodeCheckbox"> <small>Define own code</small> 
                                        </span>
                          </div>
                        </div>
                        <div class="form-group">
                          <label>Major</label>
                          <input name="major" type="text" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                          <label>Department</label>
                          <div class="input-group input-group-sm">
                            <select id="selectDepartment" name="dep_id" class="form-control input-sm">
                              <option value="" selected class="hide">Select Department</option>
                              <?php foreach ($dep as $key => $value): ?>
                                <option value="<?php echo $value['dep_id'] ?>"><?php echo $value['dep_name'] ?></option>
                              <?php endforeach ?>
                            </select>
                            <span class="input-group-btn">
                                          <button onclick="$('#modalAddDepartment').modal('show')" type="button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></button>
                                        </span>
                          </div>
                        </div>
                        <div class="form-group">
                          <label>Type</label>
                          <input name="prog_type" type="text" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                          <label>Level</label>
                          <input name="level" type="text" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                          <label>Description</label>
                          <textarea name="prog_desc" class="form-control input-sm" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                          <button onclick="resetFormProgram()" type="reset" class="btn btn-sm">Cancel</button>
                          <button type="submit" class="btn btn-sm btn-info pull-right">Save</button>
                        </div>
                        <?php echo form_close(); ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8 ui-sortable">
                     <div class="panel panel-primary" style="min-height:730px">
                        <div class="panel-heading" style="padding-bottom:20px">
                           <div class=" form-group pull-right">
                              <button type="button" val=""class="btn-sort btn btn-sm btn-primary">All</button>
                              <button type="button" val="college" class="btn-sort btn btn-sm btn-default">College</button>
                              <button type="button" val="Senior High" class="btn-sort btn btn-sm btn-default">Senior High</button>
                           </div>
                           <h4 class="panel-title">Program List</h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover table-striped" id="tblProgram">
                                 <thead class="hide">
                                    <tr>
                                      <td width="95%"></td>
                                      <td width="5%"></td>
                                    </tr>
                                 </thead>
                                 <tbody></tbody>
                            </table>
                        </div>
                     </div>
                  </div>
               </div>
               <div role="tabpanel" id="tab-time" class="panel panel-primary tab-pane">
                  <div class="panel-heading">
                     <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                     </div>
                     <h4 class="panel-title">Time</h4>
                  </div>
                  <div class="panel-body">
                      <div id="conTime">
                        <table id="tblTime" class="table table-hover table-striped table-bordered tblContent" style="margin-top:0px!important">
                            <thead>
                            <tr>
                              <th>Interval</th>
                              <th>Time Start</th>
                              <th>Time End</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                          </table>
                       </div>
                       <div id="time_loading" class="col-md-12">
                         <center>
                            <i class="fa fa-spinner fa-pulse fa-5x text-success"></i>
                         </center>
                       </div>
                  </div>
               </div>
               <div role="tabpanel" id="tab-course" class="panel panel-primary tab-pane">
                  <div class="panel-heading">
                     <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                     </div>
                     <h4 class="panel-title">Course</h4>
                  </div>
                  <div class="panel-body">
                     <div id="conCourse">
                           <table id="tblCourse" class="table table-hover table-striped table-bordered tblContent" style="margin-top:0px!important">
                             <thead>
                                   <tr>
                                     <th width="10%">Code</th>
                                     <th width="30%">Name</th>
                                     <th width="10%">Lab Unit</th>
                                     <th width="10%">Lec Unit</th>
                                     <th width="5%">Lec Hour</th>
                                     <th width="5%">Lab Hour</th>
                                     <th width="5%">Subject Type</th>
                                     <th width="5%">Action</th>
                                   </tr>
                               </thead>
                             <tbody></tbody>
                           </table>
                     </div>
                     <div id="course_loading" class="col-md-12">
                         <center>
                            <i class="fa fa-spinner fa-pulse fa-5x text-success"></i>
                         </center>
                     </div>
                  </div>
               </div>
               <div role="tabpanel" id="tab-instructor" class="panel panel-primary tab-pane">
                  <div class="panel-heading">
                     <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                     </div>
                     <h4 class="panel-title">Instructor</h4>
                  </div>
                  <div class="panel-body">
                     <div class="row">
                        <div class="col-md-4">
                          <div>
                            <h5 class="m-t-0">Instructors List</h5>
                            <!-- <div class="form-group pull-right">
                              <input type="text" class="form-control input-sm">
                            </div> -->
                          </div>
                          <hr class="m-t-0 m-b-5"/>
                          <table id="tblInstructorList" class="table m-t-0" style="margin-top:0px!important;cursor:pointer">
                            <thead class="hide">
                            <tr>
                              <th>Name</th>
                              <th>Department</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                          </table>
                        </div>
                        <div class="col-md-3">
                          <h5 class="m-t-0">Service List</h5>
                          <hr class="m-t-0 m-b-5"/>
                          <table id="tblServiceList" class="table m-t-0" style="margin-top:0px!important;cursor:pointer">
                            <thead class="hide">
                            <tr>
                              <th>Sem</th>
                              <th>Year</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php for ($i = date('Y'); $i >= 2010; $i--) { ?>
                              <tr sem="1st Semester" sy="<?php echo $i . '-' . ($i + 1) ?>" id="<?php echo '1' . $i ?>">
                                <td>1st Semester</td>
                                <td><?php echo $i . "-" . ($i + 1) ?></td>
                              </tr>
                              <tr sem="2nd Semester" sy="<?php echo $i . '-' . ($i + 1) ?>" id="<?php echo '2' . $i ?>">
                                <td>2nd Semester</td>
                                <td><?php echo $i . "-" . ($i + 1) ?></td>
                              </tr>
                            <?php } ?>
                            </tbody>
                          </table>
                        </div>
              <div class="col-md-5">
                <div id="calendar"></div>
              </div>
            </div>
                  </div>
               </div>
               <div role="tabpanel" id="tab-others" class="panel panel-primary tab-pane">
                  <div class="panel-heading">
                     <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                     </div>
                     <h4 class="panel-title">Others</h4>
                  </div>
                  <div class="panel-body">
                        <table id="tblOthers" class="table table-hover table-striped table-bordered tblContent" style="margin-top:0px!important">
                             <thead>
                             <tr>
                               <th width="30%">Work</th>
                               <th width="30%">Time Span</th>
                               <th width="30%">Description</th>
                               <th></th>
                             </tr>
                             </thead>
                             <tbody></tbody>
                        </table>
                  </div>
               </div>
               <section role="tabpanel" id="tab-semester-setup" class="panel panel-primary tab-pane">
                  <div class="panel-heading">
                     <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                     </div>
                     <h4 class="panel-title">Semester Setup</h4>
                  </div>
                  <div class="panel-body">
                     <table id="tbl-semester-setup" class="table table-hover table-striped table-bordered tblContent" style="margin-top:0px!important">
                      <thead>
                      <tr>
                          <th>School Year</th>
                          <th>Semester</th>
                          <th>Semester Start</th>
                          <th>Semester End</th>
                          <th>Period</th>
                          <th>Period Start</th>
                          <th>Period End</th>
                          <th></th>
                      </tr>
                      </thead>
                      <tbody></tbody>
                  </table>
                  </div>
               </section>
            </div>
         </div>
      </div>
   </div>
   <!-- MODAL SET RATE -->
   <div id="modalRate" data-backdrop="static" data-keyboard="false" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <form id="formAddRate" data-parsley-validate="true" novalidate="">
               <div class="modal-header">
                  <button onclick="cancelAddRate()" type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
                  <h4 class="modal-title">SET SUBJECT RATE</h4>
               </div>
               <div class="modal-body"></div>
               <div class="modal-footer">
                  <button type="button" onclick="deleteRate()" class="btn btn-danger"><i class="fa fa-times"></i></button>
                  <button type="submit" id="btnAddRating" class="btn btn-success"><i class="fa fa-plus"></i></button>
                  <button type="button" onclick="setRate()" class="btn btn-info">Set</button>
               </div>
            </form>
         </div>
         <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
   </div>
   <!-- /.modal -->
   <!-- MODAL ADD DEPARTMENT -->
   <div id="modalAddDepartment" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-sm" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
               <h4 class="modal-title">Add New Department</h4>
            </div>
            <form id="formAddDepartment" method="post" action="http://localhost/currv2/gen_info/addDepartment">
               <div class="modal-body">
                  <div class="form-group">
                     <label>Department</label>
                     <input name="dep_name" type="text" class="form-control input-sm">
                  </div>
                  <div class="form-group">
                     <label>Description</label>
                     <textarea name="dep_desc" type="text" rows="5" class="form-control input-sm"></textarea>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="reset" class="hide">reset</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save</button>
               </div>
            </form>
         </div>
         <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
   </div>
   <!-- /.modal -->
   <!-- begin scroll to top btn -->
   <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
   <!-- end scroll to top btn -->
  
</div>