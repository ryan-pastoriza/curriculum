<div id="setCurriculumModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?php echo base_url('curriculum/setNewCUrriculum') ?>" class="form-horizontal" method="post" id="addNewCurriculumForm">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <br>
        
        
          <small>Set new curriculum</small>
          <hr class="m-t-5 m-b-5">
          <div class="form-group">
            <label class="col-md-3 control-label">Program</label>
            <div class="col-md-9">
              <select required onchange="getProgramMajor($(this).val())" name="pl_id" class="form-control input-sm"></select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Major</label>
            <div class="col-md-9">
              <input type="text" class="form-control input-sm" readonly id="txtMajor">
            </div>
          </div>
          <small>Effectivity</small>
          <hr class="m-t-5 m-b-5">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-4 control-label">School year</label>
                <div class="col-md-8">
                  <select required class="form-control input-sm" name="eff_sy">
                    <option value="" selected class="hide">Select School Year...</option>
                  <?php
                    for($x=date('Y');$x>=2000;$x--){
                  ?>
                    <option value="<?php echo $x."-".($x+1); ?>"><?php echo $x."-".($x+1); ?></option>
                  <?php
                    }
                  ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="col-md-3 control-label">Semester</label>
                <div class="col-md-9">
                  <select required class="form-control input-sm" name="eff_sem">
                    <option value="" selected class="hide">Select Semester...</option>
                    <option value="1st Semester">1st Semester</option>
                    <option value="2nd Semester">2nd Semester</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="col-md-12">
                  <label>Curriculum Code</label>
                  <input required type="text" class="form-control input-sm" name="c_code">
                </div>
              </div>
            </div>
          </div>
        
      </div>
      <div class="modal-footer">
        <button class="hide" type="reset" ></button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Set</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div id="setCurrInfo" class="modal modal-xs fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="padding-left:20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <form  class="form-horizontal" method="post" id="saveCurrInfo">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <br>
           <input type="text" id="curr_id_field" value="" class="form-control input-sm hidden" name="curr_id">
            <small>Update Curriculum Information</small>
            <hr class="m-t-5 m-b-5">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-12">
                    <label>Revision Number</label>
                    <input type="text" class="form-control input-sm" name="revNum">
                  </div>
                </div>
              </div>
               <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-12">
                    <label>Date Issued</label>
                    <input type="date" class="form-control input-sm" name="dateIssue">
                  </div>
                </div>
              </div>
               <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-12">
                    <label>Issued Number</label>
                    <input type="text" class="form-control input-sm" name="issueNum">
                  </div>
                </div>
              </div>
               <div class="col-md-6">
                <div class="form-group">
                  <div class="col-md-12">
                    <label>Document Code</label>
                    <input type="text" class="form-control input-sm" name="documentCode">
                  </div>
                </div>
              </div>
            </div>
          
        </div>
      <div class="modal-footer">
        <button class="hide" type="reset" ></button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->