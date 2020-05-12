<script type="text/javascript">
    var row_id =  counter = block_section_id = year_level = semester = sy = room_code = subj_id = sub_name = bs_id = type = lab_count = lec_count = null;
    var schedule_data = [];
    var room_type_value = null;
	var lab_room_index = {strt:0, end:2};
	var lec_room_index = {strt:0, end:2};
	var activeTab = "subject";
    var schedObj;
	var subject = {};
	var lecture_room_id = {};
    var lab_room_id = {};
    var eventsToSave  = {};
    var _current_year_level = $('#yearlvl').val();
    var _current_semester = $('#semister').val();
    var _current_sy = $('#schoolyear').val();
    var _program_id = $('#program').val();
    var tblSchedule;
    var modalType;
    var subject_table;
    var lab_table;
    var tblSubjectList;
    var sec;
    var pubSY;
    var pub_bs_id = 0;
    var is_save = false;
    var is_edit = false;


    var tblSectionsSubjects;
    var _curriculum_year_level = $('#curryearlvl').val();
    var _curriculum_year_semester = $('#currsemister').val();
	var _curriculum_revision = $('#currsy').val();
    var _section_code = $('#sectioncode').val();


	var sy = $('#sy').val();
	var semester = $('#first-semester').is(':checked') ? 'first semester' : 'second semester';
	$(document).ready(function(e){

		$(window).scroll(function() {
		    if($(window).scrollTop() == $(document).height() - $(window).height()) {
		       	roomSchedule('div#lec-room-container', 'lecture', lec_room_index);
      			roomSchedule('div#lab-room-container', 'laboratory', lab_room_index);
      			lec_room_index.strt+=2;
				lec_room_index.end+=2;
				lab_room_index.strt+=2;
				lab_room_index.end+=2;
		    }else{
		    	
		    }
		});
		FormWizardValidation.init();
		$(".select2").select2();
        $('#subject-edit-list').select2({});
     	loadRooms();
     	loadProgramList();
		generateSubjectList();
		generateSectionCode();
		 $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
        tblSectionsSubjects = $("table#tblSectionsSubjects").DataTable();
        $(document).on('submit','form#frm-schedule',function (e) {
          e.preventDefault();
           if ($('#first-semester').is(':checked')) {
           semester = $('#first-semester').val();
            }
           if ($('#second-semester').is(':checked')) {
                 semester = $('#second-semester').val();
               }
               $('#lec-room-container').html('');
               $('#lab-room-container').html('');
               lab_room_index = {strt:0, end:2};
               lec_room_index = {strt:0, end:2};
               loadRooms();
        });
        $(document).on('change','#subject-edit-list',function(e){
            var subjectData = $('#subject-edit-list').val()+"".replace("'","");
            var dataSplitter = subjectData.split('=');

            var container = '<tr role="row" class="odd" subj_id="'+dataSplitter[0]+'">'+
                                '<td class="text-success">'+dataSplitter[1]+'</td>'+
                                '<td class="text-transformation:uppercase;">'+dataSplitter[2]+'</td>'+
                                '<td><button class="btn btn-sm btn-primary btn-schedule-list" data-target="#modal-schedule" data-toggle="modal"><i class="fa fa-search"></i></button>'+
                                '</td>'
                            '</tr>';
            +$('#subject-edit-list').val('');
            $('#table-subject-lecture').append(container);
            $('.select2-search-choice').remove();    
        });
        $('div.modal#modal-schedule').on('shown.bs.modal', function () {
            sectionToOtherRoom();
            counter = 0;
        });
        $('#modalScheduleList').on('hidden.bs.modal', function () {
            is_edit = false;
        });
        $(document).on('change','#schoolyear',function (e) {
            e.preventDefault();
            sy = $("#schoolyear option:selected").val();
        });
        $(document).on('change', '#select-room', function () {
          room_code = $(this).val();
          sy = $('#sy-detail').text();
          $('#room-display-name').html('Room '+room_code);
          room_calendar(room_code);
        });
        
        //reset all fields after the modal will close
        $('#modalSubjectScheduling').on('shown.bs.modal', function () {
            load_start_time();
            lecture_room();     
            laboratory_room();
            initialize_calendar();
            load_rooms('lecture');
            set_room();
            $("#subjectSchedulingCalendar").fullCalendar({
                defaultView: 'agendaWeek',
                header: {
                     left: '',
                     right: ''
                },
                minTime: "<?php echo $time->time_start; ?>",
                maxTime: "<?php echo $time->time_end; ?>",  
                columnFormat: {
                     week: 'ddd'
                },
                slotDuration: "0:<?php echo $time->interval; ?>",
                snapDuration: "0:<?php echo $time->interval; ?>",
                allDaySlot: false,
                editable: false,
                droppable: false,
                firstDay: 1,
                eventDrop: function( event, delta, revertFunc, jsEvent, ui, view ){},
                eventRightclick: function(event, jsEvent, view) {}
            });
            // alert("test");
        });
        $("#cancelSectionScheduling").on("click", function(){
            var bs_id = $("#modalSubjectScheduling input#bs_id").val();
            cancelScheduling(bs_id);
        });
        $(document).on('change','#currsy',function(e){
            e.preventDefault();
            $(this).attr('revision',$('option:selected', this).attr('revision'));

        })
        $("#formSubjectScheduling").on("submit", function(e){

            e.preventDefault();

            var section = sec;
            var yrlvl = schedObj.year;
            var sem = schedObj.semister;
            var sy = schedObj.sy;
            var pl_id = schedObj.program;
            var type = $("input[name=subj_id][type=radio]:checked").attr("data-type");
            var totalHour = parseFloat($("input[name=subj_id][type=radio]:checked").attr("totalHour"));
            var bss_id = parseFloat($("input[name=subj_id][type=radio]:checked").attr("bss_id"));

            $.ajax({
                url: "<?php echo base_url('Course_sched/saveSchedule')?>",
                data: $(this).serialize()+"&section="+section+"&year="+yrlvl+"&sem="+sem+"&sy="+sy+"&pl_id="+pl_id+"&type="+type+"&hours="+totalHour+"&bss_id="+bss_id,
                type: "POST",
                dataType: "JSON",
                success: function(data){

                    if(data.result == true){

                        // SUBTRACT SUBJECT TIME
                        var diff = parseFloat(totalHour - data.hours);

                        $("input[name=subj_id][type=radio]:checked").attr("totalHour", diff);

                        if(diff == 0){
                            $("input[name=subj_id][type=radio]:checked").attr("disabled", true);
                            $("input[name=subj_id][type=radio]:checked").closest("tr").css("cursor", "not-allowed");
                            $("input[name=subj_id][type=radio]:checked").closest("tr").css("background", "#f3f3f3");
                            $("input[name=subj_id][type=radio]:checked").attr("checked", false);
                        }

                        pub_bs_id = data.bs_id;
                        loadSectionSchedules(pub_bs_id);
                        scheduleRoom("#selectRoom");

                        showMessage('Success', "Schedule has been saved.", 'success');
                    }
                    else if(data.result == false){

                    }
                    else if(data.result == "invalid time"){
                        showMessage('Invalid Time', "Time end must be greater than the time start.", 'error');
                    }
                    else if(data.result == "hour exceeds"){
                        showMessage('Invalid Time', "Time exceeds to the subject's total hours per week. It should have only "+totalHour+" hour/s per week.", 'error');
                    }
                    else{
                        showMessage('Conflict', data.result, 'error');
                    }
                },
                error: function(){

                }
            });
        });
        $('#setSchedModal').on('shown.bs.modal', function () {
            $('#currsy').html('<option selected disabled >Select school year</option>');
            generate_section_code();
            load_revision();
        });
       $("li a[data-toggle=tab]").on("shown.bs.tab", function (e) {
            activeTab = $(this).attr("tosave");
        });
		
        $('#modalSubjectScheduling').on('hidden.bs.modal', function () {
            if(is_save === false && is_edit === false){
                 $.ajax({
                      url: '<?php echo base_url('course_sched/undo_schedule') ?>',
                      success: function (data) {
                         location.reload();
                      }
                });
            }

        });
        $('.set_section_select').on('change',function(){
             if($(this).attr('name') === 'year_lvl'){
                   year_level = $(this).val();
             }else if($(this).attr('name') === 'sem'){
                  semester = $(this).val();
             }else if($(this).attr('name') === 'sy'){
                  sy = $(this).val();
             }
        }); 
        $('#presentation-tab li').on('click',function(){
            var type =  $(this).attr('tab-val');
            load_rooms(type);
        });
        // loadRenderedEvents();
        loadPlottedEvents();
	});
	function load_revision() 
	{

        $('#program').change(function () {
            $('#currsy').html('');
            $.ajax({
                url: '<?php echo base_url('course_sched/get_revision') ?>',
                data: {pl_id: $(this).val()},
                dataType: 'json',
                success: function (data) {
                    $.each(data, function (index, data) {
                        if(index == 0){
                            $('#currsy').attr('revision',data.revision_no);
                        }
                        $('#currsy').append('<option value="' + data.sy + '" revision="'+data.revision_no+'">' + data.sy + '- Revision No : '+data.revision_no+'</option>');
                    });
                }

            });
        });
    }
     function confirm() {
       
            $.ajax({
              url: '<?php echo base_url('Course_sched/check_plotted') ?>',
              data: {bs_id: bs_id},
              async:false,
              success: function (data) {
               
                if(data == 0){

                    (new PNotify({
                        title: 'Confirmation Needed',
                        text: 'Transaction undone. Are you sure you want to continue ?',
                        icon: 'glyphicon glyphicon-question-sign',
                        hide: false,
                        confirm: {
                            confirm: true
                        },
                        buttons: {
                            closer: false,
                            sticker: false
                        },
                        history: {
                            history: false
                        }
                    })).get().on('pnotify.confirm', function() {
                        is_save = true;
                        $('.modal').modal('hide');
                        window.location.reload();
                    }).on('pnotify.cancel', function() {

                    });
                    
                }
                else{
                      $('.modal').modal('hide');
                }       
              }
    
            });
    }
    
    function room_calendar(room_code) {
        $.ajax({
            url: '<?php echo base_url('Course_sched/get_plotted_room')?>',
            data: {room_code: room_code, sy: sy, semester :semester},
            dataType: 'json',
            success: function (data) {
                $("#subject-schedule-calendar").fullCalendar('removeEvents');
                $("#subject-schedule-calendar").fullCalendar('addEventSource', data);
                $("#subject-schedule-calendar").fullCalendar('refetchEvents');
            }
        });
    }
    function laboratory_room() {

        lab_table = subjectTableLab = $('#table-subject-lab').DataTable({
            ajax: {
                url: '<?php echo base_url('Course_sched/get_block_subject'); ?>',
                data: {type: 'lab', modalType: modalType, sy:sy, semester:semester}
            },
            filter: false,
            processing: true,
            deferRender: true,
            paginate: false,
            destroy: true,
            sort: false,
            length: false,
            info: false,
            pagingType: 'simple',
            language: {
                'loadingRecords': 'retrieving subjects...'
            },
            columns: [
                {
                    'visible': false,
                    'data': 'subj_id'
                },
                 {
                    'visible': false,
                    'data': 'bs_id'
                },
                {'data': 'code'},
                {'data': 'name'},
                {'data': 'subj_id',
                  render: function(id){
                  return '<button class="btn btn-sm btn-primary btn-schedule-list" data-toggle="modal" data-target="#modal-schedule">select to other section</button>';
                  }
                }
            ]
        });

        $('#table-subject-lab tbody').on('click', 'tr', function () {
            if ($(this).hasClass('success')) {
                $(this).removeClass('success');
            }
            else {
                lab_table.$('tr.success').removeClass('success');
                $(this).addClass('success');
            }
            subj_id = lab_table.row(this).data()['subj_id'];               
            bs_id = lab_table.row(this).data()['bs_id'];
            sub_name = lab_table.row(this).data()['name'];
            type = 'lab';

            load_rooms('laboratory');
        });
    }
    function select_day(day) {
        var num = '';
        switch (day) {
            case 'Sunday':
                num = 0;
                break;
            case 'Monday':
                num = 1;
                break;
            case 'Tuesday':
                num = 2;
                break;
            case 'Wednesday':
                num = 3;
                break;
            case 'Thursday':
                num = 4;
                break;
            case 'Friday':
                num = 5;
                break;
            case 'Saturday':
                num = 6;
                break;
        }
        return num;
    }
    function lecture_room() {
         subject_table = $('#table-subject-lecture').DataTable({
          ajax: {
                url: '<?php echo base_url('Course_sched/get_block_subject'); ?>',
                data: {type: 'lec', modalType: modalType, sy:sy, semester:semester,section_code: $('#section-detail').text() }
          },
          pagingType: 'simple',
          deferRender: true,
          processing: true,
          paginate: false,
          length: false,
          destroy: true,
          filter: false,
          sort: false,
          info: false,
            language: {
                'loadingRecords': 'retrieving subjects...'
            },
            columns: [
                {
                    'visible': false,
                    'data': 'subj_id'
                },
                 {
                    'visible': false,
                    'data': 'bs_id'
                },
              {
                'visible': false,
                'data': 'semester'
              },
              {
                'visible': false,
                'data': 'sy'
              },
              {
                'visible': false,
                'data': 'year_level'
              },
                {'data': 'code'},
                {'data': 'name'},
                {'data': 'subj_id',
                render: function(id){
                  return '<button class="btn btn-sm btn-primary btn-schedule-list" data-target="#modal-schedule" data-toggle="modal" ><i class="fa fa-search"></i></button>';
                }
              }
            ],
            initComplete: function(settings, json) {
              bs_id = json.data[0].bs_id;
              
             }
        });

        /*retrieve room from lecture table*/
        $('#table-subject-lecture tbody').on('click', 'tr', function () {
          row_id = subject_table.row(this).id();
            if ($(this).hasClass('success')) {
                $(this).removeClass('success');
            }
            else {
                subject_table.$('tr.success').removeClass('success');
                $(this).addClass('success');
            }

           if(is_edit === true){
                subj_id = $(this).attr('subj_id');
                semester = $('#semester-detail').text();
                sy = $('#sy-detail').text();
                block_section_id = bs_id;
                year_level = $('#year-detail').text();
           }else{
                 subj_id = subject_table.row(this).data()['subj_id'];

                block_section_id =  bs_id = subject_table.row(this).data()['bs_id'];
               
                sub_name = subject_table.row(this).data()['name'];

                semester = $('#semester-detail').text();

                sy = $('#sy-detail').text();

                year_level = subject_table.row(this).data()['year_level'];

           }
            type = 'lec';

            load_rooms('lecture');

        });
    }
    function load_start_time() {
        $('#select-time-start,#select-time-end').html('<option selected disabled> Select Time </option>');
        $.ajax({
            url: '<?php echo base_url('Course_sched/get_schedule_time'); ?>',
            dataType: 'JSON',
            success: function (data) {
                $.each(data, function (index, data) {
                    $('#select-time-start,#select-time-end').append('<option value="' + data.time + '">' + data.time + '</option>');
                });
            }
        });
    }
    function load_rooms(type) {

        $.ajax({
            url: '<?php echo site_url('Course_sched/get_room'); ?>',
            data: {type: type},
            dataType: 'JSON',
            success: function (data) {
                $('#select-room').html('');
                $('#select-room').append('<option disabled selected>Select Room</option>');
                $.each(data, function (index, data) {
                    $('#select-room').append('<option data-name="' + data.room + '" value="' + data.room_code + '">' + data.room +' '+data.room_code+ '</option>');
                });
            }
        });
    }
    function initialize_calendar() {
        $('#subject-schedule-calendar').fullCalendar({
            cache: false,
            defaultView: 'agendaWeek',   
            header: {
                left: '',
                right: ''
            },
            minTime: "<?php echo date('H:i', strtotime($time->time_start)); ?>",
            maxTime: "<?php echo date('H:i', strtotime($time->time_end)); ?>",
            columnFormat: {
                week: 'ddd'
            },
            slotDuration: "0:<?php echo $time->interval; ?>",
            snapDuration: "0w<?php echo $time->interval; ?>",
            allDaySlot: false,
            editable: false,
            droppable: false,
            firstDay: 1,
            eventRightclick: function (event, jsEvent, view) {
                
                console.log(event.ss_id);
                // ShowMenu('contextMenus', jsEvent);
                // contextMenuEventSelected = event;
                 (new PNotify({
                        title: 'Confirmation Needed',
                        text: 'Are you sure you want to delete '+event.title+'? ',
                        icon: 'glyphicon glyphicon-question-sign',
                        hide: false,
                        confirm: { 
                            confirm: true
                        },
                        buttons: {
                            closer: false,
                            sticker: false
                        },
                        history: {
                            history: false
                        }
                    })).get().on('pnotify.confirm', function() {
                         $.ajax({
                            url: "<?php echo base_url('Course_sched/deleteSchedSubject') ?>",
                            data: {ss_id:event.ss_id},
                            dataType: "json",
                            success: function (response) {
                                if (response.result === true){
                                     new PNotify({
                                        type:'success',
                                        text:'Successful Delete'
                                    });
                                    room_calendar(room_code);
                                }
                                else{
                                    
                                }

                            },
                            error: function () {

                            }
                        });


                    }).on('pnotify.cancel', function() {

                    });
                return false;
            }
        });
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
    function cancelScheduling(bs_id){
        bootbox.confirm("Are you sure you want to cancel scheduling? The schedules that have been saved will be deleted.", function(result) {
            if(result == true){
                $.ajax({
                    url: "<?php echo base_url('Course_sched/cancelScheduling') ?>",
                    data: {bs_id : bs_id},
                    type: "GET",
                    dataType: "JSON",
                    success: function(data){
                        if(data.result == true){
                            showMessage('Success', "Scheduling has been cancelled.", 'success');
                            $("#modalSubjectScheduling").modal("hide");
                        }
                        else{

                        }
                    },
                    error: function(){

                    }
                }); 
            }
        });
    }
    function loadProgramList()
    {
    	$.ajax({
    		url: "<?php echo base_url('course_sched/generateProgramList'); ?>",
    		dataType : "JSON",
    		success: function(data){
    			var temp = '';
    			$.each(data, function(k,v){
    				temp += "<option progname=\""+v.prog_name+"\""+
                             "value=\""+v.pl_id+"\" major=\""+v.major.toUpperCase()+"\">"+v.prog_name.toUpperCase()+" - "+v.major.toUpperCase()+"</option>";
    			});
    			$('#program').append(temp);
    		}
    	});
    }
    function loadSectionSchedules(bs_id){
        tblSectionsSubjects.ajax.url("<?php echo base_url('Course_sched/getSectionSchedules?bs_id="+bs_id+"') ?>").load();
    }
	function generate_section_code() 
	{
        $.ajax({
            url: "<?php echo base_url('course_sched/generateSectionCode'); ?>",
            success: function (data) {
                if (typeof data != 'undefined') {
                    $("#setSchedModal span#sectioncode").html(data);
                }
                else {
                    $("#setSchedModal span#sectioncode").html('cannot generate code');
                }

            }
        });
    }
    
        
    function sectionToOtherRoom()
    {
      var groupColumn = 0;
        var section_list_table = $('table#sections-list').DataTable({
          destroy:true,
          "bSort": false,
          "bInfo": false,
          length: false,
          searching:false,
          deferRender:true,
          lengthChange: false,
          order : [[ groupColumn, 'asc' ]],

          columns:[
            {'data':'section'},
            {'data':'subject'},
            {'data':'room'},
            {'data':'day'},
            {'data':'ss_id','visible':false}
          ],

          "columnDefs": [
            { "visible": false, "targets": groupColumn }
          ],

          "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;

            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
              if ( last !== group ) {
                $(rows).eq( i ).before('<tr class="group active"><td colspan="5"><strong>'+group+'</strong></td></tr>');
                last = group;
              }
            } );
          },

          ajax:{
            url:'<?php echo base_url('Course_sched/subjectSchedule');?>',
            data: {'data': {'year_level': year_level, 'semester':semester, 'sy':sy, 'subj_id':subj_id, 'bs_id':bs_id, 'subject_name':sub_name}}
          }

        });

      // Order by the grouping
      $('table#sections-list tbody').on( 'click', 'tr.group', function () {
        var ss_id = $(this).closest('tr').find('span').text();
        if(counter == 0){
          $.ajax({
            url:'<?php echo base_url('Course_sched/saveOtherSection'); ?>',
            type: 'post',
            data:{'data':{'ss_id':ss_id, 'bs_id':block_section_id}},
            success: function(data) {
              if(data){
                $('div.modal#modal-schedule').modal('hide');
              }
            }
          });
        }
        //counter is used to make sure that when row is selected it only send 1 data request to the server
        // this is a bug and need to be fix. about click event of the tr that has a class of group element
        counter++;
      });

      $('table#sections-list tbody')
         .on('mouseover', 'tr.group', function() {
            $(this).removeClass('active').addClass('success');
      }).
      on('mouseout', 'tr.group', function() {
        $(this).removeClass('success').addClass('active');
          });
    }

    function set_room() {
           $(document).on('click','#btn-save',function(e){
            var selected_days = $('#selected-days').val();
            var start = $('#select-time-start').val();
            var end = $('#select-time-end').val();
            var room = $('#select-room').val();

            var event = {'sem':semester,'type':type,'bs_id':bs_id,'sub_id': subj_id, 'start': start, 'end': end, 'room':room, 'selected_days':selected_days};

             // $.ajax({
             //    url:'<?php echo base_url('Course_sched/checkTimeVacant'); ?>',
             //    data:{event:event},
             //    success:function (data) {
             //        console.log(data);
             //       if(JSON.parse(data) === 1){
                      $.ajax({
                            url:'<?php echo base_url('Course_sched/save_schedule'); ?>',
                            data:{event:event},
                            success:function (data) {

                                if(data == 1){
                                    
                                     room_calendar(room_code);

                                    new PNotify({
                                        type:'success',
                                        text:'Subject added to '+$('#section-detail').text()+' section.'
                                    });
                                }

                                if (data == 0) {
                                    new PNotify({
                                        type:'error',
                                        text:'Schedule already taken. Please another Roon or Time'
                                    });
                                }
                                
                              
                            },
                            error:function (first, second) {
                                console.log('error : '+ second);
                            }
                        });
        //            }else{
        //                 alert('CONFLICT')
        //            }
        //         },
        //         error:function (first, second) {
        //             console.log('error : '+ second);
        //         }
        //     });
                 
              
        });
    }
    function isVacantTime(event){
        
    }
	function generateSubjectList()
	{
		var data = '';
		$.ajax({
			url : "<?= base_url('course_sched/generateSubjectList'); ?>",
			dataType: "JSON",
			success :function(data)
			{
				$.each(data,function(k,v){
					var tmp = "<tr><td class=\"col-md-2\">"+
							  "<input type=\"checkbox\" name=\"subj_id\" subjname=\""+v.subj_name+"\" labunit=\""+v.lab_unit+"\" split=\""+v.split+"\" lecunit=\""+v.lec_unit+"\" lechour=\""+v.lec_hour+"\" labhour=\""+v.lab_hour+"\" subjcode=\""+v.subj_code+"\" value=\""+v.subj_id+"\"> "+v.subj_code+
					 		   "</td>"+
                          	   "<td> "+v.subj_name+"</td></tr>";
                    data += tmp;
				});
				$('#subjectListTbody').html(data);
			},
			error :function(data)
			{

			}
		})
	}
	function generateSectionCode()
	{
		$.ajax({
			url: "<?= base_url('course_sched/generateSectionCode'); ?>",
			dataType: "HTML",
			success: function(data){
				$("#setSchedModal span#sectioncode").html(data);
			},
			error: function(){

			}
		});
	}
    function select(el){
        var dis = $(el).children('td').children('input[type=radio]').attr("disabled");

        if(dis != "disabled"){
            $(el).children('td').children('input[type=radio]').attr("checked", true);
        }
    }
	function setCurriculumSched(){
        if($("#setSchedModal input#ownsectioncode").val() == ""){
            sec = $("#setSchedModal span#sectioncode").html();
        }
        else{
            sec = $("#setSchedModal input#ownsectioncode").val();
        }

        if(activeTab == 'subject'){
            $.each($("input[type='checkbox'][name='subj_id']:checked"), function(){         
                subject[$(this).val()] = {subj_id : $(this).val(), subj_code : $(this).attr('subjcode'), subj_name : $(this).attr('subjname'), lec_unit : $(this).attr('lecunit'), lab_unit : $(this).attr('labunit'), split : $(this).attr('split'), lab_hour : $(this).attr('labhour'), lec_hour : $(this).attr('lechour') };
            });

            schedObj  = {
                        program : '...',
                        prog_name : '...',
                        major: '...',
                        year: $("#setSchedModal select#yearlvl").val(),
                        semister: $("#setSchedModal select#semister").val(),
                        sy: $("#setSchedModal select#schoolyear").val(),
                        section: sec,
                        curryearlvl: $("#setSchedModal select#curryearlvl").val(),
                        currsemister: $("#setSchedModal select#currsemister").val(),
                        currsy: $("#setSchedModal select#currsy").val(),
                        schedule: $("#setSchedModal input[type=radio][name=schedule]:checked").val(),
                        subjects: subject,
                    };
        }
        else if(activeTab != "subject"){
            schedObj  = {
                        program : $("#setSchedModal select#program").val(),
                        prog_name : $("#setSchedModal select#program option:selected").attr("progname"),
                        major: $("#setSchedModal input#major").val(),
                        year: $("#setSchedModal select#yearlvl").val(),
                        semister: $("#setSchedModal select#semister").val(),
                        sy: $("#setSchedModal select#schoolyear").val(),
                        section: sec,
                        curryearlvl: $("#setSchedModal select#curryearlvl").val(),
                        currsemister: $("#setSchedModal select#currsemister").val(),
                        currsy: $("#setSchedModal select#currsy").val(),
                        schedule: $("#setSchedModal input[type=radio][name=schedule]:checked").val(),
                        subjects: subject,
                    };
        }

        $.ajax({
            url: "<?php echo base_url('Course_sched/subject_scheduling') ?>",
            data: {sched : schedObj, type : activeTab},
            type: "GET",
            dataType: "json",
            success: function(data){
                
                if(data.result == true){
                    if(data.isExist == true){
                        showMessage('Error', 'Section already exists.', 'error');
                    }
                    else{

                        pubSem = schedObj.semister;
                        pubSY = schedObj.sy;

                        setDetails(schedObj);
                        
                        if(!jQuery.isEmptyObject(data)){
                            var lec = "";
                            var lab = "";

                            $("#setSchedModal").modal("hide");
                            
                            if(!jQuery.isEmptyObject(data.lec)){
                                $.each(data.lec, function(key, value){
                                    lec += "<tr onclick=\"select(this)\">\
                                                <td><input id=\"lec_"+value.subj_id+"\" required data-type=\"lec\" bss_id=\""+value.bss_id+"\" totalHour=\""+value.lec_hour+"\" type=\"radio\" value=\"lec-"+value.subj_id+"\" name=\"subj_id\"></td>\
                                                <td>"+value.subj_code+"</td>\
                                                <td>"+value.subj_name+"</td>\
                                                <td>";
                                            if(value.countSched > 0){
                                                lec += "<button type='button' class='btn btn-xs btn-danger'>"+value.countSched+" schedule/s</button>";
                                            }
                                    lec +=  "</td>\
                                            </tr>";
                                });
                            }
                            else{
                                lec = "<tr><td colspan='3'>No subject available</td></tr>";
                            }

                            if(!jQuery.isEmptyObject(data.lab)){
                                $.each(data.lab, function(key, value){
                                    lab += "<tr onclick=\"select(this)\">\
                                                <td><input id=\"lab_"+value.subj_id+"\" required data-type=\"lab\" bss_id=\""+value.bss_id+"\" totalHour=\""+value.lab_hour+"\" type=\"radio\" value=\"lab-"+value.subj_id+"\" name=\"subj_id\"></td>\
                                                <td>"+value.subj_code+"</td>\
                                                <td>"+value.subj_name+"</td>\
                                                <td>";
                                                if(value.countSched > 0){
                                                    lab += "<button type='button' class='btn btn-xs btn-danger'>"+value.countSched+" schedule/s</button>";
                                                }
                                    lab +=  "</td>\
                                            </tr>";
                                });
                            }
                            else{
                                lab = "<tr><td colspan='3'>No subject available</td></tr>";
                            }
                            
                            $("#modalSubjectScheduling .modal-header div.section").html(schedObj.section);
                            $("#modalSubjectScheduling .modal-header div.year").html(schedObj.year);
                            $("#modalSubjectScheduling .modal-header div.course").html(schedObj.prog_name);
                            $("#modalSubjectScheduling .modal-header small.major").html(schedObj.major);
                            $("#modalSubjectScheduling .modal-header div.ysem").html(schedObj.semister+" - "+schedObj.sy);
                            $("#modalSubjectScheduling #tblLecture").html(lec);
                            $("#modalSubjectScheduling #tblLaboratory").html(lab);
                            $("#modalSubjectScheduling input#bs_id").val(data.bs_id);
                            $("#modalSubjectScheduling").modal("show");
                        }
                    }
                }
                else{
                    showMessage('Error', 'Error in creating a section function.', 'error');
                }
                
            },
            error: function(){
                showMessage('Error', 'Function error.', 'error');
            }
        });
    }
	function loadScheduleList() {

        var tblSchedule = $('#tblSchedule').DataTable({
            "bSort": false,
            "bLength": false,
            "bInfo": false,
            destroy: true,
            processing: true,
            ajax: '<?= base_url('course_sched/getSectionList');?>',
            columns: [
                {'data': 'sec_code'},
                {'data': 'prog_abv'},
                {'data': 'year_lvl'},
                {'data': 'semister'},
                {'data': 'sy'},
                {'data': 'activation'},
                {
                    'data': 'bs_id', render: function (id) {
                      var button = '<button onclick="viewScheduleSection(' + id + ')" style="margin-left:2px;" class="btn btn-info btn-xs pull-right"><span class="fa fa-folder-open-o"></span> view</button> <button onclick="editScheduleSection(' + id + ')" style="margin-left:2px;" class="btn btn-xs btn-success pull-right"><span class="fa fa-pencil"></span> Edit</button> <button onclick="deleteScheduleSection(' + id + ')" style="margin-left:2px;" class="btn btn-danger btn-xs pull-right"><span class="fa fa-trash-o"></span> Delete</button>';
                    return button;
                }
                }
            ]
        });

    }
    function loadEditRenderedEvents(bs_id) {
        $.ajax({
          url: "<?php echo base_url('course_sched/loadEditRenderingEventsloadEditRenderingEvents') ?>",
          data: {bs_id: bs_id},
          type: "GET",
          dataType: "json",
          success: function (data) {
            // $(".roomCalendar").fullCalendar('removeEvents');
            for (var i = 0; i < data.length; i++) {
              $("#" + data[i]['room']).fullCalendar('addEventSource', [data[i]]);
            }
          },
          error: function () {

          }
        });
    }
    function editScheduleSection(bs_id) {

         modalType = 'edit';
         is_edit = true;

  
         $.ajax({
            url: "<?php echo base_url('course_sched/edit_block_section') ?>",
            data: {'bs_id' : bs_id},
            dataType: "json",
            success: function (response) {
                 console.log(response.data[0]);
                 if (response.status == 1){
                            //$('#setSchedModal').modal('hide');
                            $('#modalSubjectScheduling').modal('show');
                            $('#section-detail').html(response.data[0].sec_code);
                            $('#year-detail').html(response.data[0].year_lvl);
                            $('#program-detail').html(response.data[0].sec_code);
                            // $('#major-detail').html(schedObj.major.toUpperCase());
                            $('#semester-detail').html(response.data[0].semister);
                            $('#sy-detail').html(response.data[0].sy);

                            sy = response.data[0].sy;
                            semester = response.data[0].semister;

                        }
                        else{
                            new PNotify({
                                type:'error',
                                text:'Setting Curriculum/Subject detected error. Press F5 to Refresh and  Try again.'
                            });
                  }

            }
         });
        
    }
    function deleteScheduleSection(bs_id,block_section_code) {
         (new PNotify({
                title: 'Confirmation Needed',
                text: 'Are you sure you want to delete '+block_section_code+'? ',
                icon: 'glyphicon glyphicon-question-sign',
                hide: false,
                confirm: { 
                    confirm: true
                },
                buttons: {
                    closer: false,
                    sticker: false
                },
                history: {
                    history: false
                }
            })).get().on('pnotify.confirm', function() {
                 $.ajax({
                    url: "<?php echo base_url('Course_sched/delete_blockSection') ?>",
                    data: {bs_id:bs_id},
                    dataType: "json",
                    success: function (response) {
                        if (response === 1){
                             new PNotify({
                                type:'success',
                                text:'Successful Delete'
                            });
                            loadScheduleList();
                        }
                        else{
                            
                        }

                    },
                    error: function () {

                    }   
                });


            }).on('pnotify.cancel', function() {

            });
      
    }
    function viewScheduleSection(bs_id) {
	    secToEdit = bs_id;
	    $.ajax({
	      url: "<?php echo base_url('course_load/viewSectionSchedule') ?>",
	      data: {bs_id: bs_id},
	      type: "GET",
	      dataType: "json",
	      success: function (data) {
	        $(".roomCalendar").fullCalendar('removeEvents');
	        loadEditRenderedEvents(bs_id);
	        for (var i = 0; i < data[0].length; i++) {
	          $("#" + data[0][i]['room']).fullCalendar('addEventSource', [data[0][i]]);

	          var dataToSave = {
	            year_lvl: data[0][i].year_lvl,
	            sy: data[0][i].sy,
	            sem: data[0][i].sem,
	            subj_id: data[0][i].subj_id,
	            composition: data[0][i].composition,
	            rl_id: data[0][i].rl_id,
	            time_start: data[0][i].time_start,
	            time_end: data[0][i].time_end,
	            ss_id: data[0][i].ss_id,
	            bs_id: data[0][i].bs_id
	          };

	          var key = data[0][i].key;
	          eventsToSave[key] = dataToSave;
	        }
	        setDetails(data[1]);
	        $("#buttonContainer").html("<button onclick=\"cancelEdit()\" class=\"btn btn-sm btn-success pull-right m-l-5\">Cancel</button> <button onclick=\"updateSchedule()\" class=\"btn btn-sm btn-info pull-right m-l-5\">Update</button>");
	      },
	      error: function () {

	      }
	    });
	}
	function searchSubject(key) {
	    $('table#subjectListTable').DataTable().search(key).draw();
	}
	var handleBootstrapWizardsValidation = function () {

        "use strict";

        $("#wizard").bwizard({
            validating: function (e, t) {
                if (t.index == 0) {
                    if (false === $('form[name="form-wizard"]').parsley().validate("wizard-step-1")) {
                        return false
                    }
                } else if (t.index == 1) {
                    if (false === $('form[name="form-wizard"]').parsley().validate("wizard-step-2")) {
                        return false
                    }
                } else if (t.index == 2) {
                    if (false === $('form[name="form-wizard"]').parsley().validate("wizard-step-3")) {
                        return false
                    }
                }
            }
        })
    };

    var FormWizardValidation = function () {
        // "use strict";
        return {
            init: function () {
                handleBootstrapWizardsValidation()
                  }
        }
    }();
    function loadPlottedEvents() {
        $.ajax({
              url: "<?= base_url('course_sched/loadPlottedEvents')?>",
              dataType: "json",
              success: function (data) {
                $(".roomCalendar").fullCalendar('rerenderEvents');

                for (var i = 0; i < data.length; i++) {
                  $("#" + data[i]['room']).fullCalendar('addEventSource', [data[i]]);

                }
              },
              error: function () {
                    
              }
    	});
	}

	function loadRooms() {
	   roomByType('lecture');
	   roomByType('laboratory');
       roomSchedule('div#lec-room-container', 'lecture', lec_room_index);
       roomSchedule('div#lab-room-container', 'laboratory', lab_room_index);
    }
    function scheduleRoom(el){
        var rl_id = $(el).val();
        var r_name = $("option:selected", el).html();

        var str = "Course_sched/roomSched?rl_id="+rl_id+"&sem="+pubSem+"&sy="+pubSY;
        var url = "<?php echo base_url('"+str+"') ?>";
        
        $("#subjectSchedulingCalendar").fullCalendar('removeEvents');
        $("#subjectSchedulingCalendar").fullCalendar('addEventSource', url);

        $("#roomLabelName").html("<b>Room "+r_name+" Schedules</b>");
    }
    function roomSchedule(selector, type, counter) {
        var total_unit = 0;
        $.ajax({
            url:'<?php echo base_url('course_sched/getRoom'); ?>',
            data: {type: type},
            dataType:'json',
            success:function (data) {
        		var counter_view_lec = 0;
        		var counter_view_lab = 0;
                $.each(data, function (index, room) {
                	if(type === 'lecture'){
                	
                		if (lec_room_index.strt <= counter_view_lec &&  counter_view_lec < lec_room_index.end){
            				$(selector).append(room.code);
            				loadPlottedRooms(index,type);
                		}
            		}else{
            		
                		if (lab_room_index.strt <= counter_view_lab &&  counter_view_lab < lab_room_index.end){
                			$(selector).append(room.code);
            				loadPlottedRooms(index,type);

                		}
            		}
        			counter_view_lec++;
        			counter_view_lab++;

            		total_unit++;
                });
                	if(type == 'lecture'){
                		$('#roomCountLec').text(total_unit);
                	}else{
                		$('#roomCountLab').text(total_unit);
                	}
            }
        });
    }
    function roomByType(type) {
            $.ajax({
                url:'<?= base_url('course_sched/get_room'); ?>',
                data: {type: type},
                dataType: 'json',
                async:false,
                success:function (data) {
                    (type == 'lecture' ? lecture_room_id = data : lab_room_id = data);
                }
            });
    }
    function loadPlottedRooms(ind, type)
    {
    	if(type === 'lecture'){
    		  $.each(lecture_room_id, function (index, room) {
    		  		if(ind === index){
    		  			displayPlottedRoom(room.room_code,sy,semester)
    		  		}

    		  });

    	}else{
    		  $.each(lab_room_id, function (index, room) {
    		  		if(ind === index){
    		  			displayPlottedRoom(room.room_code,sy,semester)
    		  		}
    		 });

    	}
    }
    function displayPlottedRoom(room_code,sy,semester){
    	 $.ajax({
            url: '<?php echo base_url('course_sched/get_plotted_room')?>',
            data: {room_code: room_code, sy: sy, semester: semester},
            dataType:'json',
            success:function (data) {
            	 $('#counter_'+room_code).text(data.length);
            	 $('#'+room_code).fullCalendar({
			                defaultView: 'agendaWeek',
			                header: {left: '', right: ''},
			                minTime: "<?php echo date('H:i', strtotime($time->time_start)); ?>",
			                maxTime: "<?php echo date('H:i', strtotime($time->time_end)); ?>",
			                columnFormat: {week: 'ddd'},
			                slotDuration: "0:<?php echo $time->interval; ?>",
			                snapDuration: "0:<?php echo $time->interval; ?>",
			                allDaySlot: false,
			                editable: true,
			                droppable: true,
			                firstDay: 1,
			                eventOverlap: function (stillEvent, movingEvent) {
			                    return stillEvent.allDay && movingEvent.allDay;
			                },
			                eventDrop: function (event, delta, revertFunc, jsEvent, ui, view) {


			                    eventsToSave[event.key].composition = event.start.format("dddd");
			                    eventsToSave[event.key].time_start = event.start.format("HH:mm:ss");
			                    eventsToSave[event.key].time_end = event.end.format("HH:mm:ss");

			                    sched = {
			                        key: event.key,
			                        time_start: event.start.format("HH:mm"),
			                        time_end: event.end.format("HH:mm"),
			                        composition: event.start.format("dddd"),
			                        rl_id: event.rl_id
			                    };

			                    updatePlottedSched(sched);

			                    
			                },
			                eventRightclick: function (event, jsEvent, view) {
			                    ShowMenu('contextMenu', jsEvent);
			                    contextMenuEventSelected = event;

			                    return false;
			                }
			            });
            		$('#'+room_code).fullCalendar('removeEvents');
                    $('#'+room_code).fullCalendar('addEventSource', data);
                    $('#'+room_code).fullCalendar('refetchEvents');
            	
            }
        });

    }
    function ShowMenu(control, e) {
        var posx = e.clientX + window.pageXOffset + 'px'; //Left Position of Mouse Pointer
        var posy = e.clientY + window.pageYOffset + 'px'; //Top Position of Mouse Pointer
        document.getElementById(control).style.position = 'absolute';
        document.getElementById(control).style.display = 'inline';
        document.getElementById(control).style.left = posx;
        document.getElementById(control).style.top = posy;
        $('#'+control).attr('display','inline');
        // content_menu_selected_id = id;
        // alert(posx+" - "+posy);
    }
    function moveEvent() {

        $("#moveEventModal").modal('show');

        loadMoveRooms();

        HideMenu('contextMenu');
    }
    function moveConfirm() {
        var e = contextMenuEventSelected;
        var selectRoom = $("#moveEventModal select[name=rooms]").val();
        var rl_id = $('#moveEventModal select[name=rooms] option:selected').attr('rl_id');

        bootbox.confirm("Are you sure you want to move subject?", function (result) {
          if (result == true) {

            // delete eventsToSave[e.ss_id];
            $("#" + e.room).fullCalendar('removeEvents', e.id);

            var eventData = {
              composition: e.start.format("dddd"),
              key: e.key,
              id: e.id,
              year_lvl: e.year_lvl,
              sy: e.sy,
              sem: e.sem,
              subj_id: e.subj_id,
              sd_id: e.sd_id,
              rl_id: rl_id,
              time_start: e.start.format("HH:mm:ss"),
              time_end: e.end.format("HH:mm:ss"),
              room: selectRoom,
              title: e.title,
              start: e.start.format("YYYY-MM-DD HH:mm:ss"),
              end: e.end.format("YYYY-MM-DD HH:mm:ss"),
              allDay: e.allDay,
              color: e.color,
              textColor: e.textColor,
              type: e.type,
              ss_id: e.ss_id,
              bs_id: e.bs_id
            };

            var dataToSave = {
              year_lvl: eventData.year_lvl,
              sy: eventData.sy,
              sem: eventData.sem,
              subj_id: eventData.subj_id,
              composition: eventData.composition,
              rl_id: eventData.rl_id,
              time_start: eventData.time_start,
              time_end: eventData.time_end,
              ss_id: eventData.ss_id,
              bs_id: eventData.bs_id
            };

            eventsToSave[eventData.key] = dataToSave;

            $("#" + selectRoom).fullCalendar('addEventSource', [eventData]);
            $("#moveEventModal").modal('hide');
          }
        });
    }
    function loadMoveRooms() {

        var e = contextMenuEventSelected;

        $.ajax({
          url: "<?php echo base_url('course_sched/getMoveRooms') ?>",
          data: {type: e.type, except: e.rl_id},
          type: "GET",
          dataType: "JSON",
          success: function (data) {
            $("#moveEventModal select[name=rooms]").html("");
            $.each(data, function (key, value) {
              $("#moveEventModal select[name=rooms]").append('<option rl_id="'+value.rl_id+'" value="'+value.room_code+'">'+value.room_name + " ("+ value.room_code+")"+'</option>');
            });
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

    function clickOnMenu(event) {
        
        event.stopPropagation();
    }
    function lectureRoom() {
        roomByType('lecture');
        var counter_view_lec = 0;
        $.each(lecture_room_id, function (index, room) {
        	if (lec_room_index.strt <= counter_view_lec &&  counter_view_lec < lec_room_index.end){
        		  var room_code = room.room_code;
		            $.ajax({
		                url: '<?php echo base_url('course_sched/get_plotted_room')?>',
		                data: {room_code: room_code, sy: sy, semester: semester},
		                dataType:'json',
		                success:function (data) {
		                	 $('#counter_'+room_code).text(data.length);
		                	 $('#'+room_code).fullCalendar({
						                defaultView: 'agendaWeek',
						                header: {left: '', right: ''},
						                minTime: "<?php echo date('H:i', strtotime($time->time_start)); ?>",
						                maxTime: "<?php echo date('H:i', strtotime($time->time_end)); ?>",
						                columnFormat: {week: 'ddd'},
						                slotDuration: "0:<?php echo $time->interval; ?>",
						                snapDuration: "0:<?php echo $time->interval; ?>",
						                allDaySlot: false,
						                editable: true,
						                droppable: true,
						                firstDay: 1,
						                eventOverlap: function (stillEvent, movingEvent) {
						                    return stillEvent.allDay && movingEvent.allDay;
						                },
                                        
						                eventDrop: function (event, delta, revertFunc, jsEvent, ui, view) {


						                    eventsToSave[event.key].composition = event.start.format("dddd");
						                    eventsToSave[event.key].time_start = event.start.format("HH:mm:ss");
						                    eventsToSave[event.key].time_end = event.end.format("HH:mm:ss");

						                    sched = {
						                        key: event.key,
						                        time_start: event.start.format("HH:mm"),
						                        time_end: event.end.format("HH:mm"),
						                        composition: event.start.format("dddd"),
						                        rl_id: event.rl_id
						                    };

						                    updatePlottedSched(sched);

						                 
						                },
						                eventRightclick: function (event, jsEvent, view) {
						                    ShowMenu('contextMenu', jsEvent);
						                    contextMenuEventSelected = event;

						                    return false;
						                },
                                        eventResize: function(info) {
                                           alert('asdkasd')
                                        }
						            });
		                		$('#'+room_code).fullCalendar('removeEvents');
			                    $('#'+room_code).fullCalendar('addEventSource', data);
			                    $('#'+room_code).fullCalendar('refetchEvents');
		                	
		                }
		            });
        	}
          

          

        });
    }
    function setDetails(data) {
    	
	    $("#setScheduleContent span.schedDetailsProgram").html(data.prog_name);
	    $("#setScheduleContent span.schedDetailsMajor").html(data.major);
	    $("#setScheduleContent span.schedDetailsYear").html(data.year);
	    $("#setScheduleContent span.schedDetailsSemister").html(data.semister);
	    $("#setScheduleContent span.schedDetailsSY").html(data.sy);
	    $("#setScheduleContent span.schedDetailsSection").html(data.section);
	}

	function set_curriculum() {

        var section_code_val = $('#setSchedModal input#ownsectioncode').val();
        var sec = section_code_val == '' ? $("#setSchedModal span#sectioncode").html() : $('#setSchedModal input#ownsectioncode').val();
        var program = $("#setSchedModal select#program").val();
        var major = $("#setSchedModal input#major").val();
        var year = $("#setSchedModal select#yearlvl").val();
        // var semester = semester;
        // var school_year = sy;
        var curriculum_year_level = $("#setSchedModal select#curryearlvl").val();
        var curriculum_semester = $("#setSchedModal select#currsemister").val();
        var curriculum_school_year = $("#setSchedModal select#currsy").val();
        var schedule = $("#setSchedModal input[type=radio][name=schedule]:checked").val();
        var prog_name = $("#setSchedModal select#program option:selected").attr("progname");
        var revision_no = $("#currsy").attr('revision');
        is_edit = false;
        if (activeTab == 'subject') {

            $.each($("input[type='checkbox'][name='subj_id']:checked"), function () {

                subject[$(this).val()] = {
                    subj_id: $(this).val(),
                    subj_code: $(this).attr('subjcode'),
                    subj_name: $(this).attr('subjname'),
                    lec_unit: $(this).attr('lecunit'),
                    lab_unit: $(this).attr('labunit'),
                    split: $(this).attr('split'),
                    lab_hour: $(this).attr('labhour'),
                    lec_hour: $(this).attr('lechour')
                };

            });
        }


        schedObj = {
            program: program,
            prog_name: prog_name,
            major: major,
            year: year,
            semister: semester,
            sy: sy,
            section_code: sec,
            curryearlvl: curriculum_year_level,
            currsemister: curriculum_semester,
            currsy: curriculum_school_year,
            schedule: schedule,
            subjects: subject,
            revision_no: revision_no
        };

        setDetails(schedObj);

        $.ajax({
            url: "<?php echo base_url('Course_sched/create_schedule') ?>",
            data: {sched: schedObj, type: activeTab},
            dataType: "json",
            success: function (response) {
                if (response == 1){
                    $('#setSchedModal').modal('hide');
                    $('#modalSubjectScheduling').modal('show');

                    $('#section-detail').html(schedObj.section_code.toUpperCase());
                    $('#year-detail').html(schedObj.year.toUpperCase());
                    $('#program-detail').html(schedObj.prog_name);
                    // $('#major-detail').html(schedObj.major.toUpperCase());
                    $('#semester-detail').html(schedObj.semister.toUpperCase());
                    $('#sy-detail').html(schedObj.sy.toUpperCase());
                }
                else{
                    new PNotify({
                        type:'error',
                        text:'Setting Curriculum/Subject detected error. Press F5 to Refresh and  Try again.'
                    });
                }

            },
            error: function () {

            }
        });
    }
    function laboratoryRoom() {
        roomByType('laboratory');
        var counter_view_lab = 0;
        $.each(lab_room_id, function (index, room) {

        	//lab_scr_index === 0 ||index === lab_scr_index &&
        	if (lab_room_index.strt <= counter_view_lab &&  counter_view_lab < lab_room_index.end){
	            var room_code = room.room_code;
	            $.ajax({
	                url: '<?php echo base_url('course_sched/get_plotted_room')?>',
	                data: {room_code: room_code, sy: sy, semester: semester},
	                dataType:'json',
	                success:function (data) {
	                	     $('#counter_'+room_code).text('');
	                		 $('#'+room_code).fullCalendar({
					                defaultView: 'agendaWeek',
					                header: {left: '', right: ''},
					                minTime: "<?php echo date('H:i', strtotime($time->time_start)); ?>",
					                maxTime: "<?php echo date('H:i', strtotime($time->time_end)); ?>",
					                columnFormat: {week: 'ddd'},
					                slotDuration: "0:<?php echo $time->interval; ?>",
					                snapDuration: "0:<?php echo $time->interval; ?>",
					                allDaySlot: false,
					                editable: true,
					                droppable: true,
					                firstDay: 1,
					                eventOverlap: function (stillEvent, movingEvent) {
					                    return stillEvent.allDay && movingEvent.allDay;
					                },
					                eventDrop: function (event, delta, revertFunc, jsEvent, ui, view) {


					                    eventsToSave[event.key].composition = event.start.format("dddd");
					                    eventsToSave[event.key].time_start = event.start.format("HH:mm:ss");
					                    eventsToSave[event.key].time_end = event.end.format("HH:mm:ss");

					                    sched = {
					                        key: event.key,
					                        time_start: event.start.format("HH:mm"),
					                        time_end: event.end.format("HH:mm"),
					                        composition: event.start.format("dddd"),
					                        rl_id: event.rl_id
					                    };

					                    updatePlottedSched(sched);

					                 
					                },
					                eventRightclick: function (event, jsEvent, view) {
					                    ShowMenu('contextMenu', jsEvent);
					                    contextMenuEventSelected = event;

					                    return false;
					                }
					            });
	                	
	                
	                		$('#'+room_code).fullCalendar('removeEvents');
		                    $('#'+room_code).fullCalendar('addEventSource', data);
		                    $('#'+room_code).fullCalendar('refetchEvents');
	                 }  
	              
	            });
	        }
	       
        });
    }

/*
    name _ {
    lib {
        database {
            create_database(),
            if_database_exist(),

            create_table(),
            if_table_exist(),
        },

    },
    Structures {
        Flow {
            //structures
            create database[db] {
              //list  
            },
            create modules [mdl] {
                //list
            },
            create functions [fn] {
                //list
            }
        },

        View {
            init header[hdr] {
                title,
                metadata,
                stylesheet,
            },
            init body[bdy] {
                container_body,
            },
            init footer[ftr] {
                container_footer,
                scripts,
            },


        }

    }

}
*/

</script>