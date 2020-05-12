<script type="text/javascript">
  var tblUser,
      tblSLCS,
      tblCourse,
      tblSection,
      tblSection2,
      tblCUrriculum,
      tblCoursePreview;

  var csPubSY = $("#csSelectSY").val();
  var csPubSemester = $("#csSelectSemester").val();
  var csPubType = "#block";
  var csPubBSID = 0;

  csGetSection(csPubType);

	$(document).ready(function(){
		load_user();

		 $('.nav-pills a[href="#nav-pills-tab-1"]').on('shown.bs.tab', function (e) {
		      e.target
		      e.relatedTarget
		      load_user();
		});
	    $('.nav-pills a[href="#nav-pills-tab-2"]').on('shown.bs.tab', function (e) {
	      e.target
	      e.relatedTarget
	      loadSLCS();
	    });

	    //sections setting tab
	    $('.nav-pills a[href="#nav-pills-tab-3"]').on('shown.bs.tab', function (e) {
	      e.target
	      e.relatedTarget
	    });

	    //course status tab
	    $('.nav-pills a[href="#nav-pills-tab-4"]').on('shown.bs.tab', function (e) {
	      e.target
	      e.relatedTarget
	    });

	    $('button.btnTabSectionList[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	      $(".btnTabSectionList").removeClass("tabActive");
	      $(this).addClass("tabActive");

	      var type = $(this).attr('href');
	      csPubType = type;
	      csGetSection(type);
	    });
	    $("#csButtonSetSettings").on("click", function () {
	      csPubSY = $("#csSelectSY").val();
	      csPubSemester = $("#csSelectSemester").val();
	      csGetSection(csPubType);
	    });
	    $("#btnBlockSection").on('click', function () {
	      $(this).removeClass('btn-default');
	      $(this).addClass('btn-primary');

	      $("#btnOffSetSection").addClass('btn-default');
	      $("#btnOffSetSection").removeClass('btn-primary');

	      loadBlockSection(pubSem, pubPLID, pubSY);
	    });

	    $("#btnOffSetSection").on('click', function () {
	      $(this).removeClass('btn-default');
	      $(this).addClass('btn-primary');

	      $("#btnBlockSection").addClass('btn-default');
	      $("#btnBlockSection").removeClass('btn-primary');

	      loadOffSection();
	    });
	    $('#tblSection2 tbody').on('click', 'tr', function () {

	      var bs_id = $(this).closest('tr').attr("id");
	      var scode = $(this).closest('tr').attr("scode");
	      $("table#tblSection2 tbody tr td").css({"background": "none", "color": "#777"});
	      $("table#tblSection2 tbody tr").removeClass("activeRow");

	      $(this).closest('tr').addClass("activeRow");
	      $("#tblSection2 tbody tr.activeRow td").css({"background": "rgb(179, 213, 224)", "color": "#FFF"});

	      $("#coursePreviewHead span").html(scode);

	      csGetSubject(scode, bs_id);

	    });
	    $("#formSLCS").on("submit", function (e) {
	      e.preventDefault();
	      $.ajax({
	        url: "<?php echo base_url('cpanel/saveSLCS') ?>",
	        data: $(this).serialize(),
	        type: "POST",
	        dataType: "json",
	        success: function (data) {
	          if (data.result == true) {
	            loadSLCS();
	            $("#formSLCS button[type=reset]").trigger('click');
	            showMessage("Success", "SLCS " + data.type + " successfully.", "success");
	          }
	          else if (data.result == "validateError") {
	            $(".error_message_slcs").html(data.errors);
	          }
	          else {
	            var type = {saved: "saved", updated: "update"};
	            $("#formSLCS button[type=reset]").trigger('click');
	            showMessage("Success", "Error to " + type[data.type] + " SLCS. Please try again.", "success");
	          }
	        },
	        error: function () {

	        }
	      });
		});
		$("#formAddUser").on("submit", function (e) {
	      e.preventDefault();
	      $.ajax({
	        url: "<?php echo base_url('cpanel/saveUser'); ?>",
	        data: $(this).serialize(),
	        type: "POST",
	        dataType: "json",
	        success: function (data) {
	        	$("#formAddUser")[0].reset();
	        	load_user();
	        },
	        error: function () {

	        }
	      });
	    });
		$("#selectSY").on("change", function () {
	      pubSY = $(this).val();
	      pubSem = $("#selectSemister").val();
	      loadActiveCurriculum();
	    });

	    $("#selectSemister").on("change", function () {
	      pubSY = $("#selectSY").val();
	      pubSem = $(this).val();
	      loadActiveCurriculum();
	    });
	    tblSLCS = $("#tblSLCS").dataTable({
	      "bSort": false,
	      "bLength": false,
	      "bInfo": false
	    });

	    tblCurriculum = $("#tblCurriculum").dataTable({
	      "pageLength": 4,
	      "bSort": false,
	      "bLengthChange": false,
	      "bInfo": false,
	      "bFilter": false,
	      "pagingType": "simple",
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
	    tblSection = $("#tblSection").dataTable({
	      "pageLength": 4,
	      "bSort": false,
	      "bLengthChange": false,
	      "bInfo": false,
	      "bFilter": false,
	      "pagingType": "simple",
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
	    tblSection2 = $("#tblSection2").dataTable({
	      "pageLength": 5,
	      "bSort": false,
	      "bLengthChange": false,
	      "bInfo": false,
	      "bFilter": true,
	      "pagingType": "simple",
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
	    tblCourse = $("#tblCourse").dataTable({
	      "pageLength": 10,
	      "bSort": false,
	      "bLengthChange": false,
	      "bInfo": false,
	      "bFilter": false,
	      "pagingType": "simple",
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

	    tblCoursePreview = $("#tblCoursePreview").dataTable({
	      "pageLength": 10,
	      "bSort": false,
	      "bLengthChange": false,
	      "bInfo": true,
	      "bFilter": true,
	      // "pagingType" : "simple",
	      "oLanguage": {
	        "sSearch": "<i class='fa fa-search'></i> ",
	        "oPaginate": {
	          "sNext": '<i class="fa fa-chevron-right"></i>',
	          "sPrevious": '<i class="fa fa-chevron-left"></i>',
	          "sFirst": '<i class="fa fa-angle-double-left"></i>',
	          "sLast": '<i class="fa fa-angle-double-right"></i>'
	        }
	      },
	      "columnDefs": [
	        {className: "text-right", "targets": [2]}
	      ]
	    });
	});
	$('#tblCurriculum tbody').on('click', 'tr', function () {
	      var cur_id = $(this).closest('tr').attr("id");
	      var pl_id = $(this).closest('tr').attr("pl_id");
	      var sy = $(this).closest('tr').attr("sy");
	      var sem = $(this).closest('tr').attr("sem");
	      pubPLID = pl_id;

	      $("table#tblCurriculum tbody tr td").css({"background": "none", "color": "#777"});
	      $("table#tblCurriculum tbody tr").removeClass("activeRow");

	      $(this).closest('tr').addClass("activeRow");
	      $("#tblCurriculum tbody tr.activeRow td").css({"background": "rgb(179, 213, 224)", "color": "#FFF"});

	      loadBlockSection(sem, pl_id, sy);
    });
    $('#tblSection tbody').on('click', 'tr', function () {

      var bs_id = $(this).closest('tr').attr("id");
      // pubBsID = bs_id;

      $("table#tblSection tbody tr td").css({"background": "none", "color": "#777"});
      $("table#tblSection tbody tr").removeClass("activeRow");

      $(this).closest('tr').addClass("activeRow");
      $("#tblSection tbody tr.activeRow td").css({"background": "rgb(179, 213, 224)", "color": "#FFF"});

      loadSubject(bs_id);
    });
    function loadSubject(bs_id) {
	    $.ajax({
	      url: "<?php echo base_url('cpanel/loadSubject') ?>",
	      data: {bs_id: bs_id},
	      type: "GET",
	      dataType: "JSON",
	      success: function (data) {
	        tblCourse.fnClearTable();
	        $.each(data, function (key, value) {
	          var status = "";
	          if (value.status == "taken") {
	            status = "<i class='fa fa-check-square fa-2x pull-right'></i>";
	          }
	          else if (value.status == "vacant") {
	            status = "<i class='fa fa-square-o fa-2x pull-right'></i>";
	          }
	          var newRow = tblCourse.fnAddData([
	            value.subj_code,
	            value.subj_name,
	            value.lec_unit,
	            value.lab_unit,
	            status
	          ]);
	          var oSettings = tblCourse.fnSettings();
	          var nTr = oSettings.aoData[newRow[0]].nTr;
	          $(nTr).attr("id", value['subj_id']);
	          $(nTr).attr("status", value['status']);
	        });
	      },
	      error: function () {

	      }
	   });
	}
	function editSLCS(slcs_id) {
	    $.ajax({
	      url: "<?php echo base_url('cpanel/editSLCS') ?>",
	      data: {slcs_id: slcs_id},
	      type: "GET",
	      dataType: "json",
	      success: function (data) {
	      	console.log(data);
	        $.each(data[0], function (key, value) {
	            $("#formSLCS input[name=" + key + "]").val(value);
	        });
	      },
	      error: function () {

	      }
	    });
	}
    function loadOffSection() {
	    var status = {open: "close", close: "open"};
	    $.ajax({
	      url: "<?php echo base_url('cpanel/loadOffSection') ?>",
	      data: {sy: pubSY, sem: pubSem},
	      type: "GET",
	      dataType: "JSON",
	      success: function (data) {
	        tblSection.fnClearTable();
	        $.each(data, function (key, value) {

	          if (value.description == "") {
	            value.description = "No description available";
	          }
	          if(value.activation === 'open'){
	          	value.activation = 'close';
	          }else{
	          	value.activation = 'open';
	          }

	          var newRow = tblSection.fnAddData([
	            "<div class=\"col-md-12\"><h5 class=\"m-t-0 m-b-0\">Section: <span class=\"f-s-15\" style=\"color:#154360\"><b>" + value.sec_code + "<b></span></h5>\
						"+value.year_lvl+" <p><small class=\"f-s-10\">" + value.description + "</small></p>",
	            "<button id='btnChangeStatus_" + value.bs_id + "' onclick=\"changeStatus(" + value.bs_id + ", '" + value.activation + "')\" class=\"btn btn-sm btn-primary pull-right\" style=\"height:50px;width:50px\">" + value.activation + "</button></div>"
	          ]);
	          var oSettings = tblSection.fnSettings();
	          var nTr = oSettings.aoData[newRow[0]].nTr;
	          $(nTr).attr("id", value['bs_id']);
	        });
	      },
	      error: function () {

	      }
	    });
	 }
	function loadActiveCurriculum() {
    $.ajax({
	      url: "<?php echo base_url('cpanel/activeCurriculum') ?>",
	      data: {sy: pubSY, sem: pubSem},
	      type: "GET",
	      dataType: "json",
	      success: function (data) {
	        tblCurriculum.fnClearTable();

	        $.each(data, function (key, value) {

	          var newRow = tblCurriculum.fnAddData([
	            value[1]
	          ]);

	          var oSettings = tblCurriculum.fnSettings();
	          var nTr = oSettings.aoData[newRow[0]].nTr;

	          $(nTr).attr("id", value[0]);
	          $(nTr).attr("sy", value[4]);
	          $(nTr).attr("pl_id", value[2]);
	          $(nTr).attr("sem", value[3]);

	        });
	      },
	      error: function () {

	      }
	    });
	}
	function loadBlockSection(sem, pl_id, sy) {
	    var status = {open: "close", close: "open"};
	    $.ajax({
	      url: "<?php echo base_url('cpanel/loadBlockSection') ?>",
	      data: {pl_id: pl_id, sy: sy, sem: sem},
	      type: "GET",
	      dataType: "JSON",
	      success: function (data) {
	        tblSection.fnClearTable();
	        $.each(data, function (key, value) {

	          if (value.description == "") {
	            value.description = "No description available";
	          }
	          var newRow = tblSection.fnAddData([
	            "<h5 class=\"m-t-0 m-b-0\">-Section: " + value.sec_code + "</h5>\
						<p><small>" + value.description + "</small></p>",
	            "<button id='btnChangeStatus_" + value.bs_id + "' onclick=\"changeStatus(" + value['bs_id'] + ", '" + status[value['activation']] + "')\" class=\"btn btn-sm btn-primary pull-right\" style=\"height:50px;width:50px\">" + status[value['activation']] + "</button>"
	          ]);

	          var oSettings = tblSection.fnSettings();
	          var nTr = oSettings.aoData[newRow[0]].nTr;
	          $(nTr).attr("id", value['bs_id']);
	        });
	      },
	      error: function () {

	      }
	    });
	}
	function loadSLCS() {
	    $.ajax({
	      url: "<?php echo base_url('cpanel/loadSLCS') ?>",
	      dataType: "json",
	      success: function (data) {
	        tblSLCS.fnClearTable();
	        $.each(data, function (key, value) {
	          tblSLCS.fnAddData([
	            value.student_type,
	            value.unit_capacity,
	            "",
	            "<span class='pull-right'><button onclick=\"editSLCS(" + value['slcs_id'] + ")\" class=\"btn btn-xs btn-info no-radius\"><i class=\"fa fa-pencil\"></i></button> <button onclick='deleteSLCS(" + value['slcs_id'] + ")' class=\"btn btn-xs btn-info no-radius\"><i class=\"fa fa-trash\"></i></button></span>"
	          ]);
	        });
	      },
	      error: function () {

	      }
	    });
	}
	function csGetSection(type) {
	    $.ajax({
	      url: "<?php echo base_url('cpanel/courseStatusLoadAllSection') ?>",
	      data: {type: type, sy: csPubSY, sem: csPubSemester},
	      type: "GET",
	      dataType: "json",
	      success: function (data) {
	        tblSection2.fnClearTable();
	        $.each(data, function (key, value) {
	          
	          if(key == 0 ){
	          	csPubBSID = value['bs_id'];
	            csGetSubject(value.sec_code, csPubBSID);
	          }

	          if (value.pl_id == 0) {
	            value.prog_abv = "No specific program";
	            value.major = "";
	          }

	          var newRow = tblSection2.fnAddData([
	            "<div class=\"col-md-12\"><h5 class=\m-t-0 m-b-0\>-Section: <b style=\"color:#154360;\">" + value.sec_code + "</b></h5>\
							<h5 class=\m-t-0 m-b-0\>" + value.prog_abv + " - " + value.major + "</h5>\
							<ul style=\font-size:10px\>\
								<li><p class=\m-b-0\>Revised Curriculum Effectivity</p>\
								<p class=\m-b-0\>" + value.semister + " SY: " + value.sy + "</p>\
								</li>\
							</ul></div>"
	          ]);
	          var oSettings = tblSection2.fnSettings();
	          var nTr = oSettings.aoData[newRow[0]].nTr;
	          $(nTr).attr("id", value['bs_id']);
	          $(nTr).attr("scode", value.sec_code);
	        });
	      },
	      error: function () {

	      }
	    });
	 }
	function load_user() {
	    tblUser = $("#tblUser").DataTable({
	      destroy: true,
	      processing: true,
	      ajax: {
	        url: '<?php echo site_url("cpanel/userList"); ?>',
	      },
	      columns: [
	        {'data': 'name'},
	        {'data': 'department'},
	        {'data': 'position'},
	        {'data': 'username'},
	        {
	          'data': 'id', render: function (id) {
	          var btn_edit = '<span class="pull-left"><button type="button" onclick="editUser(' + id + ')" class="btn btn-info btn-xs no-radius"><span class=" fa fa-pencil"></span></button></span>';
	          var btn_delete = '<button style="margin-left:1%" type="button" onclick="deleteUser(' + id + ')" class="btn btn-info btn-xs no-radius"><span class=" fa fa-trash"></span></button>';
	          return btn_edit + ' ' + btn_delete;

	        },
	          sortable: false,
	          searchable: false
	        },
	      ]
	    });
	}
	function csGetSubject(code, bs_id) {
	    var shedSTR = "";

	    $.ajax({
	      url: "<?php echo base_url('cpanel/courseStatusLoadSubject') ?>",
	      data: {bs_id: bs_id},
	      type: "GET",
	      dataType: "JSON",
	      success: function (data) {
	        tblCoursePreview.fnClearTable();
	        $.each(data, function (key, value) {

	          $("#coursePreviewHead span").html(code);

	          if (!jQuery.isEmptyObject(value['class_schedule']['Lecture'])) {
	            shedSTR += "<h5 class=\"m-b-0\">Lecture</h5><ul class=\"schedule-list\">";
	            $.each(value['class_schedule']['Lecture'], function (key1, value1) {
	              shedSTR += "<li>Room: " + value1['room'] + " Schedule: " + value1['sched'] + ", " + value1['time_start'] + " - " + value1['time_end'] + "</li>";
	            });
	            shedSTR += "</ul>";
	          }
	          if (!jQuery.isEmptyObject(value['class_schedule']['Laboratory'])) {
	            shedSTR += "<h5 class=\"m-b-0\">Laboratory</h5><ul class=\"schedule-list\">";
	            $.each(value['class_schedule']['Laboratory'], function (key1, value1) {
	              shedSTR += "<li>Room: " + value1['room'] + " Schedule: " + value1['sched'] + ", " + value1['time_start'] + " - " + value1['time_end'] + "</li>";
	            });
	            shedSTR += "</ul>";
	          }

	          tblCoursePreview.fnAddData([
	            "<h4 class=\"m-t-0 m-b-0\"><b>" + value.subj_code + "</b></h4>\
							<h6 class=\"m-t-0 m-b-0\"><b>" + value.subj_name + "</b></h6>" + shedSTR + " " + value.declaration,
	            "<div class=\"text-center p-10 bg-primary\" style=\"border-radius:5px;\">\
	                <h6 class=\"m-t-0 m-b-0 text-white\">Enrolled</h6>\
	                <h2 class=\"m-t-0 m-b-0 text-white\">" + value.enrolled + "</h2>\
	    					</div>",
	            "<button onclick=\"changeSubjectStatus(" + value.ss_id + ", 'tutorial', " + value.bs_id + ", '" + code + "')\" class='btn btn-sm btn-warning'>Tutorial</button> <button onclick=\"changeSubjectStatus(" + value.ss_id + ", 'bridge', " + value.bs_id + ", '" + code + "')\" class='btn btn-sm btn-info'>Bridge</button> <button onclick=\"changeSubjectStatus(" + value.ss_id + ", 'dissolve', " + value.bs_id + ", '" + code + "')\" class='btn btn-sm btn-danger'>Dissolve</button>"
	          ]);

	          shedSTR = "";
	        });
	      },
	      error: function () {

	      }
	    });
	}
	function deleteSLCS(slcs_id) {
	    bootbox.confirm("Are you sure you want to delete SLCS?", function (result) {
	      if (result == true) {
	        $.ajax({
	          url: "<?php echo base_url('cpanel/deleteSLCS') ?>",
	          data: {slcs_id: slcs_id},
	          type: "GET",
	          dataType: "json",
	          success: function (data) {
	            if (data.result == true) {
	              loadSLCS();
	              $("#formSLCS button[type=reset]").trigger('click');
	              showMessage("Success", "SLCS deleted successfully.", "success");
	            }
	            else {
	              $("#formSLCS button[type=reset]").trigger('click');
	              showMessage("Error", "Unable to delete SLCS.", "error");
	            }
	          },
	          error: function () {

	          }
	        });
	      }
	    });
	}
	function changeSubjectStatus(ss_id, stat, bs_id, code) {
	    bootbox.confirm("Are you sure you want to update subject status?", function (result) {
	      if (result == true) {
	        $.ajax({
	          url: "<?php echo base_url('cpanel/changeSubjectStatus') ?>",
	          data: {ss_id: ss_id, status: stat},
	          type: "GET",
	          dataType: "JSON",
	          success: function (data) {
	            if (data.result == true) {
	              showMessage('Success', 'Subject has been updated.', 'success');
	              csGetSubject(code, bs_id);
	            }
	            else {
	              showMessage('Error', 'Subject is not updated.', 'error');
	            }
	          },
	          error: function () {
	            showMessage('Warning', 'Function Error.', 'warning');
	          }
	        });
	      }
	    });

	}
	function editUser(user_id) {

	    $.ajax({
	      url: "<?php echo base_url('Cpanel/editUser'); ?>",
	      data: {user_id: user_id},
	      type: "GET",
	      dataType: "JSON",
	      success: function (data) {
	        $.each(data, function (key, value) {
	            $("#formAddUser input[name=" + key + "]").val(value);
	        });
	        console.log(data);
	      },
	      error: function () {

	      }
	    });
	}
	function showMessage(title, msg, type)
	{	
		new PNotify({
			title: title,
			text : msg,
			type : type
		});
	}
</script>