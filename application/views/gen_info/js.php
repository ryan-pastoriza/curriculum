<script type="text/javascript">
	var pl_id = 0;
	var tabOpen;
	var tblRoom;
	var tblTime;
	var tblProgram;
	var roomOpt = 'new';
	var ds = [0,0,0,0,0,0,0];
	var selectedInstructor;
 	var semister;
  	var sy;



	// RATING
	var countRate = 1;
	var rateObj = {};
	var countObj;
	var previous;
	var percent = 100;
	var addedRate = {};

	getRate();

	$(document).ready(function(){
		
		initTbl();
		tabOpen = $('#room-inputs');
		loadRoomList();
		initValidationRoom();
		$.fn.dataTable.ext.errMode = 'none';
		$('input[name="date_semester"]#date-semester').daterangepicker();
        $('input[name="date_period"]#date-period').daterangepicker();


		$('input#shsCheckbox').iCheck({
	      checkboxClass: 'icheckbox_square-blue',
	      radioClass: 'iradio_square-blue',
	      increaseArea: '20%' // optional
	    }).on('ifChanged', function (e) {
	      // Get the field name
	      var isChecked = e.currentTarget.checked;
	      if (isChecked == true) {
	        $("#btnSetRate").removeClass('hide');
	        $('div#category-wrapper').removeClass('hide');
	      }
	      else {
	        $("#btnSetRate").addClass('hide');
	        $('div#category-wrapper').addClass('hide');
	      }
	    });
	     $('input#abbvCheckbox').iCheck({
	      checkboxClass: 'icheckbox_square-blue',
	      radioClass: 'iradio_square-blue',
	      increaseArea: '20%' // optional
	    }).on('ifChanged', function (e) {
	      // Get the field name
	      var isChecked = e.currentTarget.checked;
	      if (isChecked == true) {
	        $("#abbvTextbox").attr("readonly", false);
	      }
	      else {
	        $("#abbvTextbox").attr("readonly", true);
	      }
	    });
	    $('input#progcodeCheckbox').iCheck({
	      checkboxClass: 'icheckbox_square-blue',
	      radioClass: 'iradio_square-blue',
	      increaseArea: '20%' // optional
	    }).on('ifChanged', function (e) {
	      // Get the field name
	      var isChecked = e.currentTarget.checked;
	      if (isChecked == true) {
	        $("#progcodeTextbox").attr("readonly", false);
	      }
	      else {
	        $("#progcodeTextbox").attr("readonly", true);
	      }
	    });
	    $(document).on('click','.btn-sort',function(){
	    	$('.btn-sort').removeClass('btn-primary');
	    	$(this).addClass('btn-primary');
	    	console.log(tblProgram.DataTable().search($(this).attr('val') ).draw());
	    	$('[aria-controls="tblProgram"]').val('');
	    });
	    $("form#formAddDepartment").on("submit", function (e) {
	      e.preventDefault();
	      $.ajax({
	        url: "<?php echo base_url('gen_info/addDepartment') ?>",
	        data: $(this).serialize(),
	        type: "POST",
	        dataType: "JSON",
	        success: function (data) {
	          if (data.result == true) {
	            $("form#formAddDepartment button[type=reset]").trigger('click');
	            $("select#selectDepartment").append("<option value='" + data['last'][0] + "'>" + data['last'][1] + "</option>");
	            showMessage('Success', 'Department has been saved.', 'success');
	          }
	          else if (data.result == false) {
	            showMessage('Error', 'Unable to save department. Please try again.', 'error');
	          }
	          else {
	            showMessage('Validation Error', data.result, 'error');
	            ''
	          }
	        },
	        error: function () {
	          showMessage('Error', 'Function error.', 'error');
	        }
	      });
	    });
	    $("#formAddRate").on("submit", function (e) {
	      e.preventDefault();


	      if (getRemainingPercent() > 0) {
	        if (countRate <= countObj) {
	          var con = $('#rate' + (countRate - 1) + ' select').val();
	          delete rateObj[con];

	          if (countRate == 1) {
	            percent -= 0;
	          }
	          else if (countRate > 1) {
	            percent -= $('#rate' + (countRate - 1) + ' input.txtpercent').val();
	          }

	          $('#rate' + (countRate - 1) + ' select').attr("disabled", true);
	          $('#rate' + (countRate - 1) + ' input.txtpercent').attr("disabled", true);

	          var display = "<div class=\"panel m-b-0\" id='rate" + countRate + "'>\
							        	<div class=\"panel-body p-t-0 p-b-5\">\
							        		<div class=\"row\">\
									        	<div class=\"col-md-8\">\
									        		Rate Name\
									        		<select class=\"form-control input-sm\">";

	          $.each(rateObj, function (key, value) {
	            display += "<option ratename='" + value.name + "' value='" + value.id + "'>" + value.name + "</option>";
	          });

	          display += "</select>\
									        	</div>\
									        	<div class=\"col-md-4\">\
									        		Percentage\
									        		<div class=\"input-group input-group-sm\">\
									        			<input data-parsley-type=\"number\" min=1 max=" + percent + " data-parsley-required=\"true\" type=\"text\" class=\"form-control input-sm txtpercent\">\
									        			<span class=\"input-group-addon\">%</span>\
									        		</div>\
									        	</div>\
									        </div>\
							        	</div>\
							        </div>";

	          $("#modalRate .modal-body").append(display);
	          countRate++;
	        }
	      }
	    });
	    $('#tblServiceList tbody').on('click', 'tr', function () {
	      console.log('a')
	      semister = $(this).closest('tr').attr("sem");
	      sy = $(this).closest('tr').attr("sy");

	      $("table#tblServiceList tbody tr td").css({"background": "none", "color": "#777"});
	      $("table#tblServiceList tbody tr").removeClass("activeRow");

	      $(this).closest('tr').addClass("activeRow");
	      $("#tblServiceList tbody tr.activeRow td").css({"background": "rgb(179, 213, 224)", "color": "#FFF"});

	    });
	    $("form#formAddOtherSched").on("submit", function (e) {
	      e.preventDefault();
	      $.ajax({
	        url: "<?php echo base_url('gen_info/otherSave') ?>",
	        data: $(this).serialize(),
	        type: "post",
	        dataType: "json",
	        success: function (data) {
	          if (data.result == true) {
	          	$('#hddnOs').val('');
	            loadOther();
	            $("form#formAddOtherSched button[type=reset]").trigger('click');

	            if (data.type == 'new') {
	              showMessage('Success', 'Schedule has been saved successfully.', 'success');
	            }
	            else if (data.type == 'update') {
	              showMessage('Success', 'Schedule has been updated successfully.', 'success');
	            }
	          }
	          else {
	            showMessage('Error', 'Transaction error.', 'error');
	          }
	        },
	        error: function () {
	          showMessage('Error', 'Function error.', 'error');
	        }
	      });
	    });
		$(document).on('click','.btn-options',function(e){
			e.preventDefault();
			var option = $(this).attr('val');
			switch(option){
				case "tab-room":
					tabChange($('#room-inputs'));
				break;
				case "tab-day":
					tabChange($('#day-inputs'));
					if(ds[0] === 0)
					{	
						loadDayList();
						ds[0] = 1;
					}
				break;
				case "tab-time":
					tabChange($('#time-inputs'));
					if(ds[1] === 0)
					{	

						loadTimeList();
						ds[1] = 1;
					}
				break;
				case "tab-course":
					tabChange($('#course-inputs'));
					if(ds[2] === 0)
					{	
						loadSubjectList();
						ds[2] = 1;
					}
				break;
				case "tab-instructor":
					tabOpen.addClass('hide');
					if(ds[3] === 0)
					{	

						loadInstructorList();
						ds[3] = 1;
					}
				break;
				case "tab-program":
					tabOpen.addClass('hide');
					if(ds[4] === 0)
					{	
						loadProgramList();
						ds[4] = 1;
					}
				break;
				case "tab-others":
					tabChange($('#others-inputs'));
					if(ds[5] === 0)
					{	
						loadOther();
						ds[5] = 1;
					}
				break;
				case "tab-semester-setup":
					tabChange($('#semester-setup-inputs'));
					if(ds[6] === 0)
					{	
						loadSemesterSetup();
						ds[6] = 1;
					}
				break;
				
			}
		})

		$("form#formAddSubject").on("submit", function (e) {
		      e.preventDefault();

		      if ($("input#shsCheckbox[type=checkbox]").is(':checked')) {

		        if (Object.keys(addedRate).length == 0) {
		          showMessage('Error', 'Please add set subject rate basis.', 'error');
		        }
		        else {
		          var subjtype = "Senior High";
		          $.ajax({
		            url: "<?= base_url('gen_info/subjectSave') ?>",
		            data: $(this).serialize() + "&" + $.param({'rate': addedRate, 'subj_type': subjtype}),
		            type: "post",
		            dataType: "json",
		            success: function (data) {
		              if (data.result == true) {
		                loadSubjectList();
		                $("form#formAddSubject button[type=reset]").trigger('click');

		                if (data.type == 'new') {
		                  showMessage('Success', 'Subject has been saved successfully.', 'success');
		                  resetRate();
		                }
		                else if (data.type == 'update') {
		                  showMessage('Success', 'Subject has been updated successfully.', 'success');
		                  resetRate();
		                }
		              }
		              else if (data.result == false) {
		                showMessage('Error', 'Transaction error.', 'error');
		              }
		              else if (data.result == "validateError") {
		                $(".error_message").html(data.errors);
		              }
		            },
		            error: function () {
		              showMessage('Error', 'Function error.', 'error');
		            }
		          });
		        }
		      }
		      else {
		        var subjtype = "College";
		        $.ajax({
		          url: "<?= base_url('gen_info/subjectSave');?>",
		          data: $(this).serialize() + "&" + $.param({'rate': addedRate, 'subj_type': subjtype}),
		          type: "post",
		          dataType: "json",
		          success: function (data) {
		            if (data.result == true) {
		              loadSubjectList();
		              $("form#formAddSubject button[type=reset]").trigger('click');

		              if (data.type == 'new') {
		                showMessage('Success', 'Subject has been saved successfully.', 'success');
		                resetRate();
		              }
		              else if (data.type == 'update') {
		                showMessage('Success', 'Subject has been updated successfully.', 'success');
		                resetRate();
		              }
		            }
		            else if (data.result == false) {
		              showMessage('Error', 'Transaction error.', 'error');
		            }
		            else if (data.result == "validateError") {
		              $(".error_message").html(data.errors);
		            }
		          },
		          error: function () {
		            showMessage('Error', 'Function error.', 'error');
		          }
		        });
		      }

		    });
		$("form#formAddDay").on("submit", function (e) {
		    e.preventDefault();
			      $.ajax({
			        url: "<?= base_url('gen_info/daySave') ?>",
			        data: $(this).serialize(),
			        type: "post",
			        dataType: "json",
			        success: function (data) {
			          if (data.result == true) {
			            loadDayList();
			            $("form#formAddDay button[type=reset]").trigger('click');
			            if (data.type == 'new') {
			              showMessage('Success', 'Day has been saved successfully.', 'success');
			            }
			            else if (data.type == 'update') {
			              showMessage('Success', 'Day has been updated successfully.', 'success');
			            }
			          }
			          else if (data.result == "validateError") {
			            $(".error_message_day").html(data.errors);
			          }
			          else {
			            showMessage('Error', 'Transaction error.', 'error');
			          }
			        },
			        error: function () {
			          showMessage('Error', 'Function error.', 'error');
			        }
			      });
			      $('[name="sd_id"]').val("");
			     
			});

		$('#tblInstructorList tbody').on('click', 'tr', function () {
		      var tr = $(this).closest('tr').attr("id");
		      selectedInstructor = tr;

		      $("table#tblInstructorList tbody tr td").css({"background": "none", "color": "#777"});
		      $("table#tblInstructorList tbody tr").removeClass("activeRow");

		      $(this).closest('tr').addClass("activeRow");
		      $("#tblInstructorList tbody tr.activeRow td").css({"background": "rgb(179, 213, 224)", "color": "#FFF"});

		      if (semister != "" && sy != "") {
		        showInstructorSched();
		      }

		});
		$("form#formAddTime").on("submit", function (e) {
	      e.preventDefault();

	      $.ajax({
	        url: "<?php echo base_url('gen_info/timeSave') ?>",
	        data: $(this).serialize(),
	        type: "post",
	        dataType: "json",
	        success: function (data) {
	          if (data.result == true) {
	            $("form#formAddTime button[type=reset]").trigger('click');
	            tblTime.fnClearTable();

	            if (data.type == 'new') {
	              showMessage('Success', 'Time has been saved successfully.', 'success');
	              loadTimeList();
	            }
	            else if (data.type == 'update') {
	              showMessage('Success', 'Time has been updated successfully.', 'success');
	              loadTimeList();
	            }
	          }
	          else {
	            showMessage('Error', 'Transaction error.', 'error');
	          }
	        },
	        error: function () {
	          showMessage('Error', 'Function error.', 'error');
	        }
	      });


	    });
	    $("#frmAddProgram").on("submit", function (e) {
	      e.preventDefault();
	      if (pl_id == 0) {
	        $.ajax({
	          url: "<?php echo base_url('gen_info/addProgram') ?>",
	          data: $(this).serialize(),
	          type: "POST",
	          dataType: "JSON",
	          success: function (data) {
	            if (data.result == true) {
	              pl_id = 0;
	             
	              location.href = '#tab-program';

	              $("form#frmAddProgram button[type=reset]").trigger('click');
	             
	              getProgramList();
	              showMessage('Success', 'Program has been saved.', 'success');
	            }
	            else if (data.result == false) {
	              showMessage('Error', 'Unable to save program. Please try again.', 'error');
	            }
	            else {
	              showMessage('Validation Error', data.result, 'error');
	            }
	          },
	          error: function () {
	            showMessage('Error', 'Function error.', 'error');
	          }
	        });
	      }
	      else {
	        // UPDATE
	        $.ajax({
	          url: "<?php echo base_url('gen_info/updateProgram') ?>",
	          data: $(this).serialize() + "&pl_id=" + pl_id,
	          type: "POST",
	          dataType: "JSON",
	          success: function (data) {
	            
	            if (data.result == true) {
	              pl_id = 0;
	              location.href = '#tab-program';

	              $("form#frmAddProgram button[type=reset]").trigger('click');
	              getProgramList();
	              showMessage('Success', 'Program has been updated.', 'success');
	            }
	            else if (data.result == false) {
	              showMessage('Error', 'Unable to update program. Please try again.', 'error');
	            }
	            else {
	              showMessage('Validation Error', data.result, 'error');
	            }
	          },
	          error: function () {
	            showMessage('Error', 'Function error.', 'error');
	          }
	        });

	      }
	      $("#frmAddProgram")[0].reset();
	    });
	    $("#frmAddProgram input[name=prog_name]").on("keyup", function () {
	    	if($(this).val().substr(-1) === ' '){
	    		$.ajax({
		        url: "<?php echo base_url('gen_info/programAcronym') ?>",
		        data: {prog_name: $(this).val()},
		        type: "POST",
		        dataType: "HTML",
		        success: function (data) {
		          $('input#abbvTextbox').val(data);
		          $('input#progcodeTextbox').val(data);
		        },
		        error: function () {

		        }
		      });
	    	}
	    });
		$('#tblServiceList tbody').on('click', 'tr', function () {

		      semister = $(this).closest('tr').attr("sem");
		      sy = $(this).closest('tr').attr("sy");

		      $("table#tblServiceList tbody tr td").css({"background": "none", "color": "#777"});
		      $("table#tblServiceList tbody tr").removeClass("activeRow");

		      $(this).closest('tr').addClass("activeRow");
		      $("#tblServiceList tbody tr.activeRow td").css({"background": "rgb(179, 213, 224)", "color": "#FFF"});

		      if (selectedInstructor != "") {
		        showInstructorSched();
		      }

		    });
	    });

	function getProgramList() {
	    $.ajax({
	      url: "<?php echo base_url('gen_info/programList') ?>",
	      dataType: "JSON",
	      success: function (data) {
	        tblProgram.fnClearTable();
	        $.each(data, function (key, value) {
	          var btnStatus = "";
	          if (value.status == "active") {
	            btnStatus = "<button onclick=\"changeStatusProgram('inactive'," + value.pl_id + ")\" class=\"btn btn-danger btn-xs\">Deactivate</button>";
	          }
	          else {
	            btnStatus = "<button onclick=\"changeStatusProgram('active'," + value.pl_id + ")\" class=\"btn btn-info btn-xs\">Activate</button>";
	          } 

	          tblProgram.fnAddData([
	            "<h5 class=\"m-b-0 m-t-0\" style=\"font-weight:bold\">" + value.prog_name + "</h5 class=\"text-bold\">\
	            				<div>\
	            					<small>Major: </small><small class=\"text-primary\">" + value.major + "</small>&nbsp;&nbsp;\
	            					<small>Type: </small><small class=\"text-primary\">" + value.prog_type + "</small>&nbsp;&nbsp;\
	            					<small>Level: </small><small class=\"text-primary\">" + value.level + "</small>\
	            				</div>\
	            				<div class=\"bg-primary p-2 m-t-2\">[" + value.prog_code + "] - <small>"+ value.prog_desc +"</small> </div>",
	            "<a href='javascript:;' onclick=\"location.href='#tab-program?edit=" + value.pl_id + "';editProgram()\" class=\"btn btn-warning btn-xs\">Modify</a> " + btnStatus
	          ]);
	        });
	      },
	      error: function () {

	      }
	    });
	}

	function initTbl()
	{
		tblRoom = $('#tblRoom').dataTable({
			"bSort" : false,
			"bLength" : false,
			"bInfo" :  false,
			pageLength : 20
		});
	}
    function createSemesterSetup() {
      $('form#frm-period').submit(function(e) {
        e.preventDefault();

        $.ajax({
          url:'<?php echo base_url('gen_info/storeperiod'); ?>',
          data:  $(this).serialize(),
          type:'post',
          dataType:'html'
        }).done(function(data) {
          var semesterList = $('table#tbl-semester-setup').DataTable();
              semesterList.ajax.reload(null, false);
        });
      });
    }
    function showInstructorSched() {
	    var str = "gen_info/get_instuctor_sched?sem=" + semister + "&sy=" + sy + "&ins_id=" + selectedInstructor;
	    var url = "<?php echo base_url('"+str+"') ?>";
	    $("#calendar").fullCalendar('removeEvents');
	    $("#calendar").fullCalendar('addEventSource', url);
	}

	function loadInstructorList()
	{
		 tblInstructor = $("#tblInstructorList").dataTable({
			  "bSort": false,
			  "bLengthChange": false,
			  // "bInfo"   : false,
			  "bFilter": true,
			  pagingType: 'simple',
			  destroy: true,
			  processing: true,
			  pageLength: 20,
			  "oLanguage": {
			    "sSearch": "<i class='fa fa-search'></i> ",
			    "oPaginate": {
			      "sNext": '<i class="fa fa-chevron-right"></i>',
			      "sPrevious": '<i class="fa fa-chevron-left"></i>',
			      "sFirst": '<i class="fa fa-angle-double-left"></i>',
			      "sLast": '<i class="fa fa-angle-double-right"></i>'
			    }
			  }
			});

		 $.ajax({
		      url:  "<?= base_url()?>gen_info/instructorList",
		      dataType: "json",
		      success: function (data) {
		        tblInstructor.fnClearTable();
		        $.each(data, function (key, value) {

		          var ext = value['employee_ext'] != null ? value['employee_ext'] :'';
		          var newRow = tblInstructor.fnAddData([
		            value['employee_fname'] + " " + value['employee_mname'] + " " + value['employee_lname'] + " " + ext,
		            value['department_name']
		          ]);

		          var oSettings = tblInstructor.fnSettings();
		          var nTr = oSettings.aoData[newRow[0]].nTr;
		          $(nTr).attr("id", value['employment_id']);
		        });
		      }
		    });
			var $calendar = $("#calendar");
		        $calendar.fullCalendar({
		          // defaultDate: moment(),
		          defaultView: 'agendaWeek',
		          header: {
		            left: '',
		            right: ''
		          },
		          minTime: "07:00:00",
		          maxTime: "21:30:00",
		          columnFormat: {
		            week: 'ddd'
		          },
		          events: "<?php echo base_url('gen_info/get_instructor_sched') ?>",
		          slotMinutes: 30,
		          allDaySlot: false,
		          editable: false,
		          droppable: false,
		          firstDay: 1,
		          height: 600,
		          eventMouseover: function (data, event, view) {
		            tooltip = '<div class="tooltiptopicevent" style="color:#0085B2;border-radius: 5px;width:auto;height:auto;background:#CCC;position:absolute;z-index:10001;padding:10px 10px 10px 10px ;  line-height: 200%;">' + 'Subject: ' + ': ' + data.subject + '</br>' + 'Start: ' + ': ' + data.time_start + '</br>End: ' + data.time_end + '</br>Day: ' + data.composition + '</br>Room: ' + data.room + '</div>';
		            $("body").append(tooltip);
		            $(this).mouseover(function (e) {
		              $(this).css('z-index', 10000);
		              $('.tooltiptopicevent').fadeIn('500');
		              $('.tooltiptopicevent').fadeTo('10', 1.9);
		            }).mousemove(function (e) {
		              $('.tooltiptopicevent').css('top', e.pageY + 10);
		              $('.tooltiptopicevent').css('left', e.pageX + 20);
		            });
		          },
		          eventMouseout: function (data, event, view) {
		            $(this).css('z-index', 8);

		            $('.tooltiptopicevent').remove();

		        }
		});
	}
	function loadOther()
	{
		  tblOthers = $("#tblOthers").dataTable({
		      "bSort": false,
		      "bLength": false,
		      "bInfo": false
		  });
		   $.ajax({
		      url: "<?= base_url()?>gen_info/otherList",
		      dataType: "json",
		      success: function (data) {
		        tblOthers.fnClearTable();
		        $.each(data, function (key, value) {
		          var newRow = tblOthers.fnAddData([
		            value['work_name'],
		            value['time_span'] + " " + value['time_unit'],
		            value['description'],
		            "<span class='pull-right'><button onclick=\"editOthers(" + value['os_id'] + ")\" class=\"btn btn-xs btn-info no-radius\"><i class=\"fa fa-pencil\"></i></button> <button onclick='confirmDelete(\"other\"," + value['os_id'] + ")' class=\"btn btn-xs btn-info no-radius\"><i class=\"fa fa-trash\"></i></button></span>"
		          ]);

		          var oSettings = tblOthers.fnSettings();
		          var nTr = oSettings.aoData[newRow[0]].nTr;
		          $(nTr).attr("id", value['os_id']);
		        });
		      },
		      error: function () {

		      }
		    });
	}
	function showPreviewTimeSplit() {

	    var start = $('input[name=time_start]').val();
	    var end = $('input[name=time_end]').val();
	    var interval = $('input[name=interval]').val();
	    var unit = $('select[name=unit]').val();

	    // var data1 = [start, end, interval, unit];
	    // console.log(data1);
	    $.ajax({
	      url: "<?php echo base_url('gen_info/previewInterval'); ?>",
	      data: {start: start, end: end, interval: interval, unit: unit},
	      type: "GET",
	      dataType: "json",
	      success: function (data) {
	        tblTime.fnClearTable();
	        $.each(data, function (key, value) {
	          tblTime.fnAddData([
	            value,
	            '',
	            '',
	            '',
	            '',
	            ''
	          ]);
	        })
	      },
	      error: function () {

	      }
	    });
	}
	function editOthers(rl_id) {
	    $.ajax({
	      url: "<?php echo base_url('gen_info/otherEdit'); ?>",
	      data: {os_id: rl_id},
	      type: "GET",
	      dataType: "json",
	      success: function (data) {
	        $.each(data, function (key, value) {
	          $.each(value, function (key1, value1) {
	            $("form#formAddOtherSched input[name=" + key1 + "]").val(value1);
	          });
	          $("form#formAddOtherSched select[name=time_unit]").val(value['time_unit']);
	          $("form#formAddOtherSched textarea[name=description]").val(value['description']);
	        });
	      },
	      error: function () {

	      }
	    });
	}
	function loadProgramList()
	{

		tblProgram = $('#tblProgram').dataTable({
			"bSort" : false,
			"bLength" : false,
			"bInfo" :  false,
			pageLength : 8
		});

		$.ajax({
		      url: "<?= base_url()?>gen_info/programList",
		      dataType: "JSON",
		      success: function (data) {
		        tblProgram.fnClearTable();
		        $.each(data, function (key, value) {
		          var btnStatus = "";
		          if (value.status == "active") {
		            btnStatus = "<button onclick=\"changeStatusProgram('inactive'," + value.pl_id + ")\" class=\"btn btn-danger btn-xs\">Deactivate</button>";
		          }
		          else {
		            btnStatus = "<button onclick=\"changeStatusProgram('active'," + value.pl_id + ")\" class=\"btn btn-info btn-xs\">Activate</button>";
		          } 

		          tblProgram.fnAddData([
		            "<h5 class=\"m-b-0 m-t-0\" style=\"font-weight:bold\">" + value.prog_name + "</h5 class=\"text-bold\">\
		            				<div>\
		            					<small>Major: </small><small class=\"text-primary\">" + value.major + "</small>&nbsp;&nbsp;\
		            					<small>Type: </small><small class=\"text-primary\">" + value.prog_type + "</small>&nbsp;&nbsp;\
		            					<small>Level: </small><small class=\"text-primary\">" + value.level + "</small>\
		            				</div>\
		            				<div class=\"bg-primary p-2 m-t-2\">[" + value.prog_code + "] - <small>"+ value.prog_desc +"</small> </div>",
		            "<a href='javascript:;' onclick=\"location.href='#tab-program?edit=" + value.pl_id + "';editProgram()\" class=\"btn btn-warning btn-xs\">Modify</a> " + btnStatus
		          ]);
		        });
		      },
		      error: function () {

		      }
	    });
	}
	function editProgram() {
	    pl_id = 0;
	    var hash = $(location).attr("hash");
	    var tab = hash.split("?");
	    var arr = hash.split("=");

	    $("div[data-toggle=tab][href=" + tab[0] + "]").tab("show");

	    if (arr.length > 1) {
	      pl_id = arr[1];

	      location.href = tab[0] + "?edit=" + pl_id;

	      $.ajax({
	        url: "<?php echo base_url('gen_info/modifyProgram') ?>",
	        data: {pl_id: pl_id},
	        type: "GET",
	        dataType: "JSON",
	        success: function (data) {

	          $.each(data, function (key, value) {
	            $.each(value, function (key1, value1) {
	              $("[name=" + key1 + "]").val(value1);
	            });
	          });
	        },
	        error: function () {

	        }
	      });
	    }
	}
	function loadTimeList()
	{
		$('#conTime').hide();
		tblTime = $("#tblTime").dataTable({
	      "bSort": false,
	      "bLength": false,
	      "bInfo": false,
	      pageLength: 15
	    });



	    var start = $('input[name=time_start]').val();
	    var end = $('input[name=time_end]').val();
	    var interval = $('input[name=interval]').val();
	    var unit = $('select[name=unit]').val();

	    // var data1 = [start, end, interval, unit];
	    // console.log(data1);
	    $.ajax({
	      url: "<?= base_url('gen_info/timeList'); ?>",
	      data: {start: start, end: end, interval: interval, unit: unit},
	      type: "GET",
	      dataType: "json",
	      success: function (data) {

	        tblTime.fnClearTable();
	        $.each(data, function (key, value) {
	          tblTime.fnAddData([
	            value.interval+" min's",
	            value.time_start,
	            value.time_end,
	          ]);
	         
	          $('#conTime').show();
	   		  $('#time_loading').hide();
	        })
	      },
	      error: function () {

	      }
	    });
	}
	function loadSubjectList()
	{

		$('#conCourse').hide();
		tblCourse = $("#tblCourse").dataTable({
	      "bSort": false,
	      "bLength": false,
	      "bInfo": false,
	      pageLength: 20
	    });

		$.ajax({
	      url: "<?= base_url()?>gen_info/subjectList",
	      dataType: "json",
	      success: function (data) {
	        tblCourse.fnClearTable();
	        $.each(data, function (key, value) {
	          var newRow = tblCourse.fnAddData([
	            value['subj_code'],
	            value['subj_name'],
	            value['lab_unit'],
	            value['lec_unit'],
	            value['lec_hour'],
	            value['lab_hour'],
	            value['split'],
	            // value['type'],
	            "<span class='pull-right'><button onclick='editCourse(" + value['subj_id'] + ")' class=\"btn btn-xs btn-info no-radius\"><i class=\"fa fa-pencil\"></i></button> <button onclick='confirmDelete(\"course\"," + value['subj_id'] + ")' class=\"btn btn-xs btn-info no-radius\"><i class=\"fa fa-trash\"></i></button></span>"
	          ]);

	          var oSettings = tblCourse.fnSettings();
	          var nTr = oSettings.aoData[newRow[0]].nTr;
	          $(nTr).attr("id", value['subj_id']);
	          $('#conCourse').show();
	   		  $('#course_loading').hide();
	        });
	      },
	      error: function () {

	      }
	    });
	    
	}
	function loadSemesterSetup()
		{
			$('table#tbl-semester-setup').DataTable({
	        ajax:'<?php echo base_url('gen_info/allsemester')?>',
	        columns: [
	          {'data': 0},
	          {'data': 1},
	          {'data': 2},
	          {'data': 3},
	          {'data': 4},
	          {'data': 5},
	          {'data': 6},
	        ]
	      });
	}
	function loadDayList() {

		$('#conDay').hide();
		tblDay = $("#tblDay").dataTable({
	      "bSort": false,
	      "bLength": false,
	      "bInfo": false,
	      pageLength: 20
	    });

	    $.ajax({
	      url: "<?= base_url()?>gen_info/dayList",
	      dataType: "json",
	      success: function (data) {
	        tblDay.fnClearTable();
	        $.each(data, function (key, value) {
	          var newRow = tblDay.fnAddData([
	            value['abbreviation'],
	            value['composition'],
	            "<span class='pull-right'><button onclick='editDay(" + value['sd_id'] + ")' class=\"btn btn-xs btn-info no-radius\"><i class=\"fa fa-pencil\"></i></button> <button onclick='confirmDelete(\"day\"," + value['sd_id'] + ")' class=\"btn btn-xs btn-info no-radius\"><i class=\"fa fa-trash\"></i></button></span>"
	          ]);

	          var oSettings = tblDay.fnSettings();
	          var nTr = oSettings.aoData[newRow[0]].nTr;
	          $(nTr).attr("id", value['sd_id']);
	          $('#conDay').show();
	   		  $('#day_loading').hide();
	        });
	      },
	      error: function () {

	      }
	    });
	}
	function loadRoomList()
	{	
		$('#conRoom').hide();
		 $.ajax({
		      url: "<?= base_url()?>gen_info/roomList",
		      dataType: "json",
		      success: function (data) {
		        tblRoom.fnClearTable();
		        $.each(data, function (key, value) {
		          var newRow = tblRoom.fnAddData([
		            value['room_code'],
		            value['room_name'],
		            value['capacity'],
		            value['type'],
		            value['desc'],
		            "<span class='pull-right'><button onclick='editRoom(" + value['rl_id'] + ")' class=\"btn btn-xs btn-info no-radius\"><i class=\"fa fa-pencil\"></i></button> <button onclick='confirmDelete(\"room\"," + value['rl_id'] + ")' class=\"btn btn-xs btn-info no-radius\"><i class=\"fa fa-trash\"></i></button></span>"
		          ]);

		          var oSettings = tblRoom.fnSettings();
		          var nTr = oSettings.aoData[newRow[0]].nTr;
		          $(nTr).attr("id", value['rl_id']);
		          $('#conRoom').show();
	   		  	  $('#room_loading').hide();
		        });
		      },
		      error: function () {

		      }
		    });
	}
	function tabChange(el)
	{
		tabOpen.addClass('hide');
		tabOpen = el.removeClass('hide');
	}
	function confirmDelete(opt='', id='') {

	    bootbox.confirm("Are you sure you want to delete "+opt+"?", function (result) {
	      if (result == true) {
	      	switch(opt){
	      		case "room":
					deleteRoom(id);
				break;
				case "day":
					deleteDay(id);
				break;
				case "time":
					deleteTime(id);
				break;
				case "course":
					deleteCourse(id);
				break;
				case "instructor":
					deleteInstructor(id);
				break;
				case "semester-setup":
					deleteSemesterSetup(id);
				case "other":
					deleteOther(id);
				break;
	      	}

	      }
	    });
	};
	function deleteOther()
	{
		 $.ajax({
		      url: "<?php echo base_url('gen_info/otherDelete'); ?>",
		      data: {os_id: rl_id},
		      type: "GET",
		      dataType: "json",
		      success: function (data) {
		        if (data['result'] == true) {
		          $("#tblOthers tr#" + rl_id).fadeOut();
		          showMessage('Success', 'Schedule has been deleted successfully.', 'success');
		        }
		        else if (data['result'] == false) {
		          showMessage('Error', 'Unable to delete schedule. Please try again', 'error');
		        }
		      },
		      error: function () {
		        showMessage('Error', 'Function error.', 'error');
		      }
	    });
	}
	function deleteRoom(id='')
	{
	  $.ajax({
	      url: "<?= base_url('gen_info/roomDelete') ?>",
	      data: {rl_id: id},
	      type: "GET",
	      dataType: "json",
	      success: function (data) {
	        if (data['result'] == true) {
	          $("#tblRoom tr#" + id).fadeOut();
	          showMessage('Success', 'Room has been deleted successfully.', 'success');
	        }
	        else if (data['result'] == false) {
	          showMessage('Error', 'Unable to delete room. Please try again', 'error');
	        }
	      },
	      error: function () {
	        showMessage('Error', 'Function error.', 'error');
	      }
	   });
	}
	function deleteTime(id='')
	{

	}
	function deleteCourse(id='')
	{
		$.ajax({
	      url: "<?php echo base_url('gen_info/subjectDelete'); ?>",
	      data: {subj_id: id},
	      type: "GET",
	      dataType: "json",
	      success: function (data) {
	        if (data['result'] == true) {
	          $("#tblCourse tr#" + id).fadeOut();
	          showMessage('Success', 'Course has been deleted successfully.', 'success');
	        }
	        else if (data['result'] == false) {
	          showMessage('Error', 'Unable to delete course. Please try again', 'error');
	        }
	      },
	      error: function () {
	        showMessage('Error', 'Function error.', 'error');
	      }
	    });
	}
	function deleteInstructor(id='')
	{}
	function deleteDay(id='')
	{
		$.ajax({
	      url: "<?= base_url('gen_info/dayDelete') ?>",
	      data: {sd_id: id},
	      type: "GET",
	      dataType: "json",
	      success: function (data) {
	        if (data['result'] == true) {
	          $("#tblDay tr#" + id).fadeOut();
	          showMessage('Success', 'Day has been deleted successfully.', 'success');
	        }
	        else if (data['result'] == false) {
	          showMessage('Error', 'Unable to delete day. Please try again', 'error');
	        }
	      },
	      error: function () {
	        showMessage('Error', 'Function error.', 'error');
	      }
	    });
	}
	function deleteSemesterSetup(id='')
	{}





	function editRoom(rl_id)
	{
		 $.ajax({
		      url: "<?= base_url('gen_info/roomEdit')?>",
		      data: {rl_id: rl_id},
		      type: "GET",
		      dataType: "json",
		      success: function (data) {
		      	$.each(data, function(key,value){
		      		
		      		$("form#formAddRoom input[name=" + key + "]").val(value)
		      		
		      	});
		      	$("form#formAddRoom select[name=type]").val(data['type']);
		        $("form#formAddRoom textarea[name=desc]").val(data['desc']);
		      	$('#roomOpt').val('edit');
		     
		      },
		      error: function () {

		      }
	    });


	}
	function editTime()
	{}
	function editDay(sd_id)
	{
		$.ajax({
	      url: "<?= base_url('gen_info/dayEdit')?>",
	      data: {sd_id: sd_id},
	      type: "GET",
	      dataType: "json",
	      success: function (data) {
	        $.each(data, function (key, value) {
	            $("form#formAddDay input[name=" + key + "]").val(value);
	        });
	      },
	      error: function () {

	      }
	    });
	}
	function editCourse(subj_id)
	{
		 $.ajax({
	      url: "<?= base_url('gen_info/subjectEdit'); ?>",
	      data: {subj_id: subj_id},
	      type: "GET",
	      dataType: "json",
	      success: function (data) {
	        $.each(data['subjData'], function (key, value) {
	         
	          $("form#formAddSubject input[name=" + key + "]").val(value);
	         
	          if(key === 'subj_desc')
	          {
	          	$("form#formAddSubject textarea[name=subj_desc]").val(value);
	          }
	          if(key === 'subj_type')
	          {
	          	if (value == "Senior High") {
		            $("#shsCheckbox").iCheck('check');
		        }else {
		            $("#shsCheckbox").iCheck('uncheck');
		        }
	          }

	          // $("#shsCheckbox").iCheck('uncheck');

	        });

	        $.each(data['rate'], function (key, value) {

	          var display = "<div class=\"panel m-b-0\" id='rate" + countRate + "'>\
							        	<div class=\"panel-body p-t-0 p-b-5\">\
							        		<div class=\"row\">\
									        	<div class=\"col-md-8\">\
									        		Rate Name\
									        		<select class=\"form-control input-sm\">";
	          display += "<option class='hide' selected ratename='" + rateObj[value.rn_id]['name'] + "' value='" + value.rn_id + "'>" + rateObj[value.rn_id]['name'] + "</option>";
	          $.each(rateObj, function (key, value) {
	            display += "<option ratename='" + value.name + "' value='" + value.id + "'>" + value.name + "</option>";
	          });
	          display += "</select>\
									        	</div>\
									        	<div class=\"col-md-4\">\
									        		Percentage\
									        		<div class=\"input-group input-group-sm\">\
									        			<input value='" + value.rate_num + "' data-parsley-type=\"number\" min=1 max=" + percent + " data-parsley-required=\"true\" type=\"text\" class=\"form-control input-sm txtpercent\">\
									        			<span class=\"input-group-addon\">%</span>\
									        		</div>\
									        	</div>\
									        </div>\
							        	</div>\
							        </div>";

	          $("#modalRate .modal-body").append(display);
	          percent -= parseInt(value.rate_num);

	          countRate++;
	          delete rateObj[value.rn_id];
	        });

	      },
	      error: function () {

	      }
	    });
	}
	function editInstructor()
	{}
	function editSemesterSetup()
	{}



	function initValidationRoom() 
	{

	    $('form#formAddRoom').formValidation({
	      message: 'This value is not valid',
	      //live: 'disabled',
	      icon: {
	       // valid: 'glyphicon glyphicon-ok',
	       // invalid: 'glyphicon glyphicon-remove',
	        validating: 'fa fa-refresh fa-spin'
	      },
	      fields: {
	        room_code: {
	          validators: {
	            notEmpty: {
	              message: 'The room code is required and cannot be empty'
	            },
	            stringLength: {
	              min: 1,
	              message: 'The room code  must be more than 1 characters long'
	            },
	            regexp: {
	              regexp: /^[a-zA-Z0-9\-]+$/,
	              message: 'The room code  can only consist of charaacter, number and dashes.'
	            },
	            remote: {
	              url: '<?= base_url('gen_info/roomOptions/checkRoomCode')?>',
	              type: 'post',
	              data: function(){
	              		return {
	              			option:$('#roomOpt').val(),
	              		}

	              },
	              message: 'The room code is not available',
	              delay: 1000
	            }
	          }
	        },
	        room_name: {
	          validators: {
	            notEmpty: {
	              message: 'The room name is required and cannot be empty'
	            },
	            stringLength: {
	              max: 60,
	              message: 'The room name must be more than 3 characters long'
	            }
	          }
	        },
	        capacity: {
	          validators: {
	             notEmpty: {
	              message: 'The capacity is required and cannot be empty'
	            },
	            stringLength: {
	              max: 3,
	              message: 'The capacity must be more than 1 characters long'
	            },
	            regexp: {
	              regexp: /^[0-9]+$/,
	              message: 'The capacity can only consist of number'
	            }
	          }
	        },
	        type: {
	          validators: {
	             notEmpty: {
	              message: 'The type is required and cannot be empty'
	            },
	            stringLength: {
	              min: 3,
	              message: 'The type must be more than 1 characters long'
	            }
	          }
	        },
	        location: {
	          validators: {
	            stringLength: {
	              min: 6,
	              message: 'The location must be more than 1 characters long'
	            },
	            regexp: {
	              regexp: /^[a-zA-Z0-9]+$/,
	              message: 'The capacity can only consist of character and number'
	            }
	          }
	        },
	        desc: {
	          validators: {
	            stringLength: {
	              min: 3,
	              message: 'The description must be more than 1 character long'
	            },
	            regexp: {
	              regexp: /^[a-zA-Z0-9]+$/,
	              message: 'The description can only consist of character and number'
	            }
	          }
	        },
	      }/*end of fields*/
	    }).on('success.form.fv', function (e) {

	      e.preventDefault();
	      var $form = $(e.target);

	      $.ajax({
	        url: $(this).attr('action'),
	        data: new FormData(this),
	        type: $(this).attr('method'),
	        contentType: false,
	        cache: false,
	        processData: false,
	        dataType: 'html',
	        success: function (data) {

	          if (data == 1) {

	            new PNotify({
	              type: "success",
	              text: "Room Successfully Created."
	            });
	            loadRoomList();
	            $form
	              .formValidation('disableSubmitButtons', false)  // Enable the submit buttons
	              .formValidation('resetForm', true);
	          }
	          if (data == 2) {
	            new PNotify({
	              type: "success",
	              text: "Room Successfully Updated."
	            });
	          }
	          if (data == 3) {
	            new PNotify({
	              type: "error",
	              text: "Room Failed to  Update."
	            });
	          }
	          if (data == 0) {
	            new PNotify({
	              type: "error",
	              text: "Failed to Create new Room."
	            });
	          }

	        }

	      });
	    }).on('err.form.fv', function(e) {

	      // Active the panel element containing the first invalid element
	      var $form         = $(e.target),
	        validator     = $form.data('formValidation'),
	        $invalidField = validator.getInvalidFields().eq(0),
	        $collapse     = $invalidField.parents('.collapse');

	      $collapse.collapse('show');
	    });


  	}
  	function initValidationDay()
	{}
	function initValidationTime()
	{}
	function initValidationCourse()
	{}
	function initValidationInstructor()
	{}
	function initValidationSemesterSetup()
	{}
	
	//rate : 

	function resetRate() {
	    countRate = 1;
	    rateObj = {};
	    countObj;
	    percent = 100;
	    addedRate = {};
	    $("#modalRate .modal-body").html("");
	    getRate();
	}

	function getRate() {

	    $.ajax({
	      url: "<?= base_url('gen_info/rate') ?>",
	      dataType: "json",
	      success: function (data) {
	        $.each(data, function (key, value) {
	          rateObj[value.rn_id] = {id: value.rn_id, name: value.rate_name};
	        });

	        // countObj = rateObj.length;
	        countObj = (Object.keys(rateObj).length);
	      },
	      error: function () {

	      }
	    });
	}
	function deleteRate() {

	    if (countRate > 2) {
	      var id = $('#rate' + (countRate - 2) + " select").val();
	      var name = $('#rate' + (countRate - 2) + " select option:selected").attr('ratename');

	      rateObj[id] = {id: id, name: name};

	      $('#rate' + (countRate - 1)).remove();
	      countRate--;

	      $('#rate' + (countRate - 1) + ' select').attr("disabled", false);
	      $('#rate' + (countRate - 1) + ' input.txtpercent').attr("disabled", false);

	      percent = parseInt(percent) + parseInt($('#rate' + (countRate - 1) + ' input.txtpercent').val());

	     
	    }
	}
	function resetFormProgram() {
	    pl_id = 0;
	    location.href = '#tab-program';
	}
	function setRate() {
	    addedRate = {};
	    var totalPercent = 0;
	    for (var x = 1; x < countRate; x++) {

	      var rate = $('#rate' + (x) + " select").val();
	      var percent = $('#rate' + (x) + " input.txtpercent").val();

	      var p = parseInt($('#rate' + (x) + " input.txtpercent").val());

	      if ($('#rate' + (x) + " input.txtpercent").val() == "") {
	        p = 0;
	      }
	      totalPercent += p;
	      addedRate[x] = {rn_id: rate, rate_num: percent};
	    }

	    if (totalPercent < 100) {
	      var remain = 100 - parseInt(totalPercent);
	      showMessage('Information', 'The total rating percentage must be 100%. There is still have ' + remain + '% remaining.', 'error');
	    }
	    else if (totalPercent > 100) {
	      showMessage('Information', 'The total rating percentage must be 100%.', 'error');
	    }
	    else {
	      $("#modalRate").modal("hide");
	    }
	 }

	function cancelAddRate() {
	    bootbox.confirm("Are you sure you want to cancel?", function (result) {
	      if (result == true) {
	        countRate = 1;
	        rateObj = {};
	        countObj;
	        percent = 100;
	        addedRate = {};
	        $("#modalRate .modal-body").html("");
	        $("#modalRate").modal("hide");
	        getRate();
	      }
	    });
	  }

	function changeStatusProgram(stat, pl_id) {
	    $.ajax({
	      url: "<?php echo base_url('gen_info/changeProgStatus') ?>",
	      data: {status: stat, pl_id: pl_id},
	      type: "GET",
	      dataType: "JSON",
	      success: function (data) {
	        if (data.result == true) {
	          getProgramList();

	          showMessage('Success', 'Program status has been updated.', 'success');

	        }
	        else {
	          showMessage('Error', 'Unable to update program status.', 'error');
	        }
	      },
	      error: function () {

	      }
	    });
	}

	function getRemainingPercent() {
	    var totalPercent = 0;
	    for (var x = 1; x < countRate; x++) {
	      var percent = $('#rate' + (x) + " input.txtpercent").val();
	      if ($('#rate' + (x) + " input.txtpercent").val() == "") {
	        percent = 0;
	      }
	      totalPercent += parseInt(percent);
	    }

	    var remain = 100 - parseInt(totalPercent);
	    return remain;
 	}
  	function showMessage(title, msg, type){
		new PNotify({
		    title: title,
		    text: msg,
		    type: type,
		});
	}
</script>