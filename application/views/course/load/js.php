<script type="text/javascript">
	var pubSY;
	var pubSem;
	var pubInsID = 0;
	var pubBsID = 0;
	var pubSubjID = 0;
	var pubSubStat;

	var sy,sem;
	var date = new Date();
   	var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var $calendar = $("#calendar");
    var contextMenuEventSelected;

	$(document).ready(function(){
		loadTables();
		loadTableInstructor();
		loadAllSubject();
		loadCalendar();
		pubSY = $('#selectSY').val();
	    pubSem = $('#selectSem').val();
		$('#tblInstructorList tbody').on('click', 'tr', function () {

	      var employee_id = $(this).closest('tr').attr("id");
	      pubInsID = employee_id;

	      $("table#tblInstructorList tbody tr td").css({"background": "none", "color": "#777"});
	      $("table#tblInstructorList tbody tr").removeClass("activeRow");

	      $(this).closest('tr').addClass("activeRow");
	      $("#tblInstructorList tbody tr.activeRow td").css({"background": "rgb(179, 213, 224)", "color": "#FFF"});

	      showInstructorSched(employee_id);
	    });
		$("#selectSY").on("change", function () {
	      pubSY = $(this).val();
	      sy = $(this).val();
	      loadAllSubject();
	    });

	    $("#selectSem").on("change", function () {
	      pubSem = $(this).val();
	      sem = $(this).val();
	      loadAllSubject();
	    });
	    $(document).on('click','#tbl-subjects tr', function(){
		    var ssid = $(this).find('[ss-id]').val();
		    if($(this).hasClass('active')){
		      $('#tbl-subjects tr.active').removeClass('active');
		    }else{
		      schedPrev(ssid);
		      $(this).addClass('active');
		    }
		  });
		  $(document).on('click','[assign-sched]', function(e){
		    if($(this).hasClass('disabled')){
		      e.preventDefault();
		    }
		    else{
		      var ssid = $(this).attr('assign-sched');
		      assignSched(ssid);
		      $(this).addClass('disabled')
		    }
		});

	});
	function assignSched(ssid){
	    var selectedEmp = $('#tblInstructorList tr.activeRow').attr('id');
	    if(typeof selectedEmp == 'undefined'){
	      alert("Select Instructor");
	    }else{
	    	checkConflict(ssid,selectedEmp);
	    }
	}
    function checkConflict (ss_id,selectedEmp)
	{	
		
		$.ajax({
	          url: "<?= base_url('course_load/checkConflictSubject'); ?>",
	          data: {ssid: ss_id, employee_id : pubInsID,},
	          type: "GET",
	          dataType: "JSON",
	          success: function (data) {
	           	 if(data === '1'){
	           	 	 $.post("<?= base_url('course_load/assign_schedule') ?>",{'ssid':ss_id,'emp_id': selectedEmp}, function(r){
				        $('[ss-id][value='+ss_id+']').parent().find("i").removeClass("fa-square-o").addClass("fa-square");
				        showInstructorSched(selectedEmp)
				     });	
	           	 }else{	
	           	 	alert('Conflict Subject Schedule');
	           	 }
	          },
	          error: function () {
               
	          }
	        });
	}
	function HideMenu(control) {
	    document.getElementById(control).style.display = 'none';
	}

	function clickOnBody(event) {
	    HideMenu('contextMenu');
	}
	function removeSubjectFromInstructor() {

	    HideMenu('contextMenu');

	    var subj_id = contextMenuEventSelected.subj_id;
	    var bs_id = contextMenuEventSelected.bs_id;

	    bootbox.confirm("Are you sure you want to remove subject?", function (result) {
	      if (result == true) {
	        $.ajax({
	          url: "<?php echo base_url('course_load/removeSubjectFromInstructor') ?>",
	          data: {subj_id: subj_id, bs_id: bs_id},
	          type: "GET",
	          dataType: "JSON",
	          success: function (data) {
	            if (data.result == true) {
	              $('[ss-id][value='+data.ss_id+']').parent().find('i').removeClass('fa-square').addClass('fa-square-o');

	              showInstructorSched(pubInsID);
	              // showMessage("Success", "The subject has been removed successfully.", "success");
	                $('#tblInstructorList tbody').trigger('click');
	            }
	            else if (data.result == false) {
	              // showMessage("Error", "Cannot remove subject. Please try again.", "error");
	            }
	          },
	          error: function () {

	          }
	        });
	      }

	    });
	}
	function schedPrev(ssid){
	    $.post("<?= base_url('course_load/preview_schedule') ?>",{'ssid':ssid,'sy':$('#selectSY').val(),'sem':$('#selectSem').val()}, function(r){
	      $('[sched-preview]').html(r);
	    })
	}
	function ShowMenu(control, e) {
	    var posx = e.clientX + window.pageXOffset + 'px'; //Left Position of Mouse Pointer
	    var posy = e.clientY + window.pageYOffset + 'px'; //Top Position of Mouse Pointer
	    document.getElementById(control).style.position = 'absolute';
	    document.getElementById(control).style.display = 'inline';
	    document.getElementById(control).style.left = posx;
	    document.getElementById(control).style.top = posy;
	    // content_menu_selected_id = id;
	    // alert(posx+" - "+posy);
	}
	function showInstructorSched(insID) {


	    var str = "course_load/get_instuctor_sched?sem=" + pubSem + "&sy=" + pubSY + "&ins_id=" + insID;
	    var url = "<?= base_url('"+str+"') ?>";
	    $("#calendar").fullCalendar('removeEvents');
	    $("#calendar").fullCalendar('addEventSource', url);

	    $.ajax({
	      url: url,
	      dataType:'json',
	    }).done(function(data) {
	      $('#unit-plotted').html(0);
		     $.each(data, function(value) {
		       $('#unit-plotted').html(value.unit);
		     });
	    });
	  }


	function loadCalendar()
	{
    $calendar.fullCalendar({
      // defaultDate: moment(),
      

      defaultView: 'agendaWeek',
      header: {
        left: '',
        right: ''
      },
      minTime: "07:00:00",
      maxTime: "22:00:00",
      columnFormat: {
        week: 'ddd'
      },
      slotMinutes: 15,
      allDaySlot: false,
      editable: false,
      droppable: false,
      firstDay: 1,
      eventRightclick: function (event, jsEvent, view) {
        ShowMenu('contextMenu', jsEvent);
        contextMenuEventSelected = event;
        return false;
      }
    });
	}
	function loadTableInstructor()
	{
		 $.ajax({
		      url: "<?=  base_url('course_load/getInstructorList')?>",
		      dataType: "json",
		      success: function (data) {
		        tblInstructor.fnClearTable();
		        $.each(data, function (key, value) {
		            var ext = value['employee_ext'] != null ? value['employee_ext'] : '';

		          var newRow = tblInstructor.fnAddData([
		            value['employee_fname'] + " " + value['employee_mname'] + " " + value['employee_lname'] + " " + ext,
		            value['department_name']
		          ]);

		          var oSettings = tblInstructor.fnSettings();
		          var nTr = oSettings.aoData[newRow[0]].nTr;
		          $(nTr).attr("id", value['employment_id']);
		        });
		      },
		      error: function () {

		      }
		    });
	}

	function searchInstructor(val) {
	    tblInstructor.fnFilter(val);
	}
	function loadAllSubject() {

	    sy = $('#selectSY').val();
	    sem = $('#selectSem').val();
	    $('#tbl-subjects').DataTable().destroy();
   		subjectsTbl =  $('#tbl-subjects').DataTable(
	    {
	          ajax: {
	                  url: "<?php echo base_url('course_load/getAllSubjects') ?>",
	                  // dataSrc : "",
	                  data: {
	                    'sy': sy,
	                    'sem' : sem
	                  },
	                  type: 'post',
	                },
	          "pageLength": 10,
	          "pagingType": "simple",
	          "bSort": false,
	          "bLengthChange": false,
	          "bFilter": true,
	          "oLanguage": {
	            "sSearch": "<i class='fa fa-search'></i> ",
	            "oPaginate": {
	              "sNext": '<i class="fa fa-chevron-right"></i>',
	              "sPrevious": '<i class="fa fa-chevron-left"></i>',
	              "sFirst": '<i class="fa fa-angle-double-left"></i>',
	              "sLast": '<i class="fa fa-angle-double-right"></i>'
	            }
	      }
	        }
	      );
	 
	 }
	function loadTables()
	{
		 tblInstructor = $("#tblInstructorList").dataTable({
	      "pageLength": 20,
	      "pagingType": "simple",
	      "bSort": false,
	      "bLengthChange": false,
	      // "bInfo": false,
	      "bFilter": true,
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
	}

</script>