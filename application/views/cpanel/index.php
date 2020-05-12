
<style type="text/css">
  .nav>li>a {
      color: #FFF;
  }
  .nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover {
      background: #3B617A;
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

  table#tblCurriculum>tbody>tr>td{
    border: none!important;
    padding:5px!important;
    color: #0085B2!important;
    cursor: pointer;
  }

  table#tblSection>tbody>tr>td{
    border: none!important;
    padding:5px!important;
    color: #0085B2!important;
    cursor: pointer;
  }
  table#tblSection2>tbody>tr>td{
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
  table#tblCoursePreview>tbody>tr>td{
    border: none!important;
    padding:5px!important;
    color: #0085B2!important;
    cursor: pointer;
    font-size:11px;
  }

  .schedule-list{
    padding:0px;
    list-style: none;
    font-size:11px;
    color: #0085B2!important;
    font-weight:bold;
  }
</style>
<div class="content" id="content" style="margin-top: 30px;">

  <div class="row p-t-10 p-b-10 m-b-10" style="background: #154360;border-radius: 5px;color:#FFF!important">
    <div class="col-lg-12">
      <ul class="nav nav-pills m-b-0">
        <li class="active"><a href="#nav-pills-tab-1" data-toggle="tab" aria-expanded="true">User Account</a></li>
        <li class=""><a href="#nav-pills-tab-2" data-toggle="tab" aria-expanded="false">Student Load Capacity Settings</a></li>
        <li class=""><a href="#nav-pills-tab-3" data-toggle="tab" aria-expanded="false">Section Settings</a></li>
        <li class=""><a href="#nav-pills-tab-4" data-toggle="tab" aria-expanded="false">Course Status</a></li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 p-l-0 p-r-0">

      <div class="tab-content p-t-0 p-r-0 p-l-0" style="background: none">

        <div class="tab-pane fade active in" id="nav-pills-tab-1">
            <div class="row">
            <div class="col-lg-3 col-lg-offset-1">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Input User Data</h3>
                </div>
                <div class="panel-body">
                  <!-- <form method="post" action="cpanel/saveUser"> -->
                  <div class="error_message_user text-danger"><?php echo validation_errors(); ?></div>
                  <?php echo form_open('cpanel/saveUser', 'data-parsley-validate="true" id="formAddUser"'); ?>
                  <input type="hidden" name="user_id">
                  <div class="form-group m-b-5">
                    <label>First name</label>
                    <input data-parsley-required="true" autocomplete="off" type="text" class="form-control input-sm" name="user_fname">
                  </div>
                  <div class="form-group m-b-5">
                    <label>Last name</label>
                    <input data-parsley-required="true" autocomplete="off" type="text" class="form-control input-sm" name="user_lname">
                  </div>
                  <div class="form-group m-b-5">
                    <label>Middle name</label>
                    <input autocomplete="off" type="text" class="form-control input-sm" name="user_mname">
                  </div>
                  <div class="form-group m-b-5">
                    <label>Department</label>
                    <select data-parsley-required="true" class="form-control input-sm" name="dep_id">
                      <option value="" selected class="hide">Select Department</option>
                      <?php foreach ($department as $key => $value): ?>
                        <option value="<?php echo $value->dep_id; ?>"><?php echo $value->dep_name ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Position</label>
                    <input data-parsley-required="true" autocomplete="off" type="text" class="form-control input-sm" name="user_position">
                  </div>
                  <hr/>
                  <div class="form-group m-b-5">
                    <label>Username</label>
                    <input data-parsley-required="true" data-parsley-minlength="5" autocomplete="off" type="text" class="form-control input-sm" name="username">
                  </div>
                  <div class="form-group">
                    <label>Password</label>
                    <input data-parsley-required="true" data-parsley-minlength="5" autocomplete="off" type="password" class="form-control input-sm" name="password">
                  </div>
                  <div class="form-group">
                    <label>User type</label>
                    <select data-parsley-required="true" name="user_type_id" class="form-control input-sm">
                      <?php foreach ($user_type as $key => $value): ?>
                        <option value="<?php echo $value['user_type_id'] ?>"><?php echo $value['user_type'] ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                  <div class="form-group clearfix">
                    <button class="btn btn-sm btn-danger" type="reset">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success pull-right" style="width:100px">Save</button>
                  </div>
                  <?php echo form_close(); ?>
                  <!-- </form> -->
                </div>
              </div>
            </div>
            <div class="col-lg-7">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">List</h3>
                </div>
                <div class="panel-body">
                  <table id="tblUser" class="table table-hover table-striped table-bordered tblContent" style="margin-top:0px!important">
                    <thead>
                    <tr>
                      <th>Name</th>
                      <th>Department</th>
                      <th>Position</th>
                      <th>Username</th>
                      <th class="col-xs-1"></th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="nav-pills-tab-2">
          <div class="row">
            <div class="col-lg-3 col-lg-offset-1">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Set-up SLCS</h3>
                </div>
                <div class="panel-body">
                  <!-- <form method="post" action="cpanel/saveUser"> -->
                  <div class="error_message_slcs text-danger"><?php echo validation_errors(); ?></div>
                  <?php echo form_open('cpanel/saveSLCS', 'data-parsley-validate="true" id="formSLCS"'); ?>
                  <input type="hidden" name="slcs_id">
                  <div class="form-group m-b-5">
                    <label>Student type</label>
                    <input data-parsley-required="true" autocomplete="off" type="text" class="form-control input-sm" name="student_type">
                  </div>
                  <div class="form-group">
                    <label>Total Unit Capacity</label>
                    <input data-parsley-required="true" data-parsley-type="digits" min="1" autocomplete="off" type="text" class="form-control input-sm" name="unit_capacity">
                  </div>
                  <div class="form-group clearfix">
                    <button class="btn btn-sm btn-danger" type="reset">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success pull-right" style="width:100px">Save</button>
                  </div>
                  <?php echo form_close(); ?>
                  <!-- </form> -->
                </div>
              </div>
            </div>
            <div class="col-lg-7">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">SLCS List</h3>
                </div>
                <div class="panel-body">
                  <table id="tblSLCS" class="table table-hover table-striped table-bordered tblContent" style="margin-top:0px!important">
                    <thead>
                    <tr>
                      <th>Student type</th>
                      <th>Total unit capacity</th>
                      <th></th>
                      <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td class="text-right">
                        <button class="btn btn-xs btn-info no-radius"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-xs btn-info no-radius"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td class="text-right">
                        <button class="btn btn-xs btn-info no-radius"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-xs btn-info no-radius"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td class="text-right">
                        <button class="btn btn-xs btn-info no-radius"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-xs btn-info no-radius"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td class="text-right">
                        <button class="btn btn-xs btn-info no-radius"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-xs btn-info no-radius"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td class="text-right">
                        <button class="btn btn-xs btn-info no-radius"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-xs btn-info no-radius"><i class="fa fa-trash"></i></button>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="nav-pills-tab-3">
         <div class="row">
            <div class="col-lg-4">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon" style="background:#3a92ab;color:#FFF">
                    SY
                  </span>
                  <select id="selectSY" class="form-control">
                    
                    <?php for ($i = date('Y'); $i >= 2000; $i--): ?>
                      <option value="<?php echo $i.'-'.($i+1)?>"><?php echo $i.'-'.($i+1)?></option>
                    <?php endfor; ?>

                  </select>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-lg-offset-4">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon" style="background:#3a92ab;color:#FFF">
                    Semester
                  </span>
                  <select id="selectSemister" class="form-control">
                    <option value="1st Semester">1st Semester</option>
                    <option value="2nd Semester">2nd Semester</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
           <div class="row">
            <div class="col-lg-4">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Active Curriculum</h3>
                </div>
                <div class="panel-body p-l-5 p-t-5 p-r-5 p-b-5">
                  <table id="tblCurriculum" class="table table-hover" style="margin-top:0px!important">
                    <thead class="hide">
                    <tr>
                      <td></td>
                    </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>

              
            </div>
            <div class="col-lg-4">
              <div class="panel panel-info">
                <div class="panel-heading clearfix">
                  <span class="panel-title">Section List</span>
                  <div class="pull-right">
                    <button id="btnBlockSection" class="btn btn-sm btn-primary"> Block Section</button>
                    <button id="btnOffSetSection" class="btn btn-sm btn-default">Off Sem Section</button>
                  </div>
                </div>
                <div class="panel-body p-l-5 p-t-5 p-r-5 p-b-5">
                  <table id="tblSection" class="table table-hover" style="margin-top:0px!important">
                    <thead class="hide">
                    <td></td>
                    <td></td>
                    </thead>
                    <tbody>
                    
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Course Preview</h3>
                </div>
                <div class="panel-body p-l-5 p-t-5 p-r-5 p-b-5">
                  <table id="tblCourse" class="table table-hover" style="margin-top:0px!important">
                    <thead class="hide">
                    <tr>
                      <td></td>
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
        </div>
        <div class="tab-pane fade" id="nav-pills-tab-4">
             <div class="row">
            <div class="col-lg-4">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Settings</h3>
                </div>
                <div class="panel-body p-l-5 p-t-5 p-r-5 p-b-5">
                  <div class="row">
                    <div class="col-md-7">
                      <div class="col-md-12">
                          <table style="color:#0085B2!important">
                            <tr>
                              <td class="p-b-5">School Year</td>
                              <td class="p-l-20 p-b-5">:
                                <select id="csSelectSY" class="m-l-5" style="border:none;outline: none">
                                  <?php foreach ($curriculum as $temp): ?> 
                                      <option value="<?php echo $temp->eff_sy; ?>"><?php echo $temp->eff_sy; ?></option>
                                  <?php endforeach; ?> 
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Semester</td>
                              <td class="p-l-20">:
                                <select id="csSelectSemester" class="m-l-5" style="border:none;outline: none">
                                  <option value="First Semester">1st Semester</option>
                                  <option value="Second Semester">2nd Semester</option>
                                </select>
                              </td>
                            </tr>
                          </table>
                      </div>
                    </div>
                    <div class="col-md-5 clearfix">
                      <button id="csButtonSetSettings" class="btn btn-primary pull-right">Set settings</button>
                    </div>
                  </div>
                </div>
              </div>

              <style type="text/css">
                button.tabActive {
                  color: #fff;
                  background: #00acac;
                  border-color: #00acac;
                }

              </style>

              <div class="panel panel-info">
                <div class="panel-heading clearfix">
                  <span class="panel-title">Section List</span>
                  <div class="pull-right">
                    <button href="#block" aria-controls="block" role="tab" data-toggle="tab" class="btn btn-sm btnTabSectionList tabActive">Block Sections</button>
                    <button href="#off" aria-controls="off" role="tab" data-toggle="tab" class="btn btn-sm btnTabSectionList">Off Sem Sections</button>
                    <button href="#all" aria-controls="all" role="tab" data-toggle="tab" class="btn btn-sm btnTabSectionList">All Sections</button>
                  </div>
                </div>
                <div class="panel-body p-l-5 p-t-5 p-r-5 p-b-5">

                  <table id="tblSection2" class="table table-hover" style="margin-top:0px!important">
                    <thead class="hide">
                    <td></td>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-lg-8">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 id="coursePreviewHead" class="panel-title">Section: <span>...</span></h3>
                </div>
                <div class="panel-body p-l-5 p-t-5 p-r-5 p-b-5">
                  <table id="tblCoursePreview" class="table table-hover table-striped" style="margin-top:0px!important">
                    <thead class="hide">
                    <tr>
                      <td class=" col-md-4"></td>
                      <td class=" col-md-4"></td>
                      <td class=" col-md-4"></td>
                    </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>