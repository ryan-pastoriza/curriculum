<script type="text/javascript">
    var tr = 1;
    var json,
        display,
        yns = 0,
        limit = 5,
        active_pl = 0,
        tblListCurr = $('table#table-list-curr'),
        publicCurID,
        year = [
          'First Year - First Semester',
          'First Year - Second Semester',
          'Second Year - First Semester',
          'Second Year - Second Semester',
          'Third Year - First Semester',
          'Third Year - Second Semester',
          'Fourth Year - First Semester',
          'Fourth Year - Second Semester',
          'Fifth Year - First Semester',
          'Fifth Year - Second Semester'
        ];
    var codeProgAbv = "";
    var codeSem = "";
    var codeSY = "";
    $(document).ready(function(){      
        tblListCurr = $('table#table-list-curr')
        var tblListCurr = $('table#table-list-curr').DataTable({
            ajax: '<?= base_url('curriculum/getCurriculumByUser'); ?>',
            pagingType: 'simple',
            lengthChange : false,
            destroy : true,
            columns : [{'data':'program'}],
          initComplete : function(setting, json) {
              $.each(json, function(index, data) {
                $.each(data, function(index, data) {
                  getCurriculumPerProg(data.program_id);

                });
              });
          }
          });
        $(document).on('click','.btncurrInfo',function(e){
            e.preventDefault();
            $("#curr_id_field").attr('value',$(this).attr('value'));
            $("input[name='revNum']").attr('value',($(this).attr('revNum') === "undefined") ? '' : $(this).attr('revNum'));
            $("input[name='dateIssue']").attr('value',($(this).attr('dateIssue') === "undefined") ? '' : $(this).attr('dateIssue'));
            $("input[name='issueNum']").attr('value',($(this).attr('issueNum') === "undefined") ? '' : $(this).attr('issueNum'));
            $("input[name='documentCode']").attr('value',($(this).attr('documentCode') === "undefined") ? '' : $(this).attr('documentCode'));
        });
        $('#setCurrInfo').on('hidden.bs.modal', function () {
             $('#saveCurrInfo')[0].reset();
        });
        $("#saveCurrInfo").on("submit", function (e) {
             e.preventDefault();
           
            $.ajax({
              url: "<?php echo base_url('curriculum/saveCurrInfo') ?>",
              data: $(this).serialize(),
              type: "POST",
              dataType: "json",
              success: function (data) {
                if (data === 1) {
                  showMessage('Success', 'New curriculum has been set successfully.', 'success');
                    $('#setCurrInfo').modal('hide');
                }
                else {
                  showMessage('Error', 'Cannot set new curriculum. Please try again', 'error');
                }
              },
              error: function () {

              }
            });
        });
        $("#addNewCurriculumForm").on("submit", function (e) {
            e.preventDefault();
            $.ajax({
              url: "<?php echo base_url('curriculum/setNewCurriculum') ?>",
              data: $(this).serialize(),
              type: "POST",
              dataType: "json",
              success: function (data) {
                $('table#table-list-curr').DataTable().ajax.reload();
                if (data['result'] == true) {
                   loadCurrList();
                   $("#addNewCurriculumForm")[0].reset();
                  showMessage('Success', 'New curriculum has been set successfully.', 'success');
                }
                else {
                  showMessage('Error', 'Cannot set new curriculum. Please try again', 'error');
                }
              },
              error: function () {

              }
            });
        });
        $('#setCurriculumModal').on('shown.bs.modal', function () {
            selectProgram();
        });
        $("#addNewCurriculumForm select[name=pl_id]").on('change', function () {
            codeProgAbv = $('option:selected', this).attr('abv');
            generateCurrCode();
        });
        $("#addNewCurriculumForm select[name=eff_sy]").on('change', function () {
            var val = $(this).val();
            codeSY = val.substr(2, 2) + val.substr(7, 2);
            generateCurrCode();
        });
        $("#addNewCurriculumForm select[name=eff_sem]").on('change', function () {
            var val = $(this).val();
            if (val == "1st Semester") {
                codeSem = "SEM1";
            }
            else if (val == "2nd Semester") {
                codeSem = "SEM2";
            }
            generateCurrCode();
        });
     });
    function searchCurriculum(key) {
        $('table#table-list-curr').DataTable().search(key).draw();
    }
    function cancelSave() {
        $("#currPreviewContainer").html("<p>Please select curriculum to view . . .</p>");
    }
    function generateCurrCode() {
        var code = codeProgAbv + "-" + codeSY + "-" + codeSem;
        $("#addNewCurriculumForm input[name=c_code]").val(code);
    }
    function showMessage(title, msg, type){
        new PNotify({
            title: title,
            text: msg,
            type: type,
        });
    }
    function addYearAndSemister() {
        if (yns <= 9) {
            var yearsem = year[yns];
            $.ajax({
                url: "<?php echo base_url('curriculum/add_sem_year') ?>",
                data: {ys: yearsem, tr: tr, yns : yns},
                type: "GET",
                dataType: "html",
                success: function (data) {
                    $('#year_sem_container').append(data);
                    yns++;
                    tr++;
                    $("select.js-example-basic-multiple").select2("destroy");
                    $("select.js-example-basic-multiple").select2();
                },
                error: function () {

                }
            });
        }
    }
        function remove_subject(con, tr) {

        bootbox.confirm("Are you sure you want to remove subject?", function (result) {
            if (result) {
                var element = "#" + con + " table.table-curr tbody tr#" + tr;
                $(element).remove(); 
            }
        });
    }
    function setNameSelect2(data){
        console.log(data);

    }

    function removePreviousYS() {
        bootbox.confirm("Are you sure you want to remove Year and Semester?", function (result) {
            console.log(year[yns]);
            if (result == true) {
                yns--;
                var str = "#ys_" + year[yns];
                str.replace(/\s/g, '');
                $(str.replace(/\s/g, '')).remove();
            }
        });
    }
    function loadCurrList() {
        $.ajax({
            url: "<?php echo base_url('curriculum/curriculumList') ?>",
            dataType: "json",
            success: function (data) {
                tblListCurr.fnClearTable();
                $.each(data, function (key, value) {
                    tblListCurr.fnAddData([value]);
                    getCurriculumPerProg(key);
                });
            },
            error: function () {

            }
        });
    }
    function setActiveInactiveCurriculum() {
        $.ajax({
            url: "<?php echo base_url('curriculum/setActiveInactive') ?>",
            data: {cur_id: publicCurID},
            type: "GET",
            dataType: "json",
            success: function (data) {
                if (data.result == true) {
                    $("button#btnSetActiveInactiveCurriculum").html(data.str + " Curriculum");
                    showMessage('Success', 'Curriculum status changed successfully.', 'success');
                }
                else {
                    showMessage('Error', 'Curriculum unable to change status successfully.', 'error');
                }
            },
            error: function () {

            }
        });
    }
    
    
    function getProgramMajor(pl_id) {
        $.ajax({
            url: "<?php echo base_url('curriculum/getProgramMajor') ?>",
            data: {pl_id: pl_id},
            type: "GET",
            dataType: "json",
            success: function (data) {
                $("form#addNewCurriculumForm input#txtMajor").val(data['major']);
            },
            error: function () {

            }
        });
    }

    function selectProgram() {
        $("form#addNewCurriculumForm select[name=pl_id]").html("");
        $.ajax({
            url: "<?php echo base_url('Curriculum/program_list') ?>",
            dataType: "json",
            success: function (data) {
                $("form#addNewCurriculumForm select[name=pl_id]").append("<option selected class='hide'>Select program ...</option>");
                $.each(data, function (key, value) {
                    $("form#addNewCurriculumForm select[name=pl_id]").append("<option abv='" + value['prog_abv'] + "' value='" + value['pl_id'] + "'>" + value['prog_name'].toUpperCase() +'-'+value['prog_abv']+ "</option>");
                });
            },
            error: function () {

            }
        });
    }
    function showMoreCurr(pl_id) {

        if (active_pl == pl_id) {
            limit += 5;
        }
        else {
            limit = 5;
        }

        $.ajax({
            url: "<?php echo base_url('curriculum/showMoreCurr') ?>",
            data: {pl_id: pl_id, limit: limit},
            type: "GET",
            dataType: "json",
            success: function (data) {
                $.each(data, function (key, value) {
                    display = "<li onclick=\"previewCurriculum(" + value['cur_id'] + ")\">\
                                        <span class=\"fa fa-chevron-right\"></span> <a href=\"javascript:;\">" + value['cur_id'] + " Revised Curriculum Effectivity <span class=\"lbl-sem\">" + value['eff_sem'] + "</span> SY: <span class=\"lbl-sy\">" + value['eff_sy'] + "</span></a>\
                                    </li>";

                    $(".curr-list-container .curr-list#listProg_" + pl_id).append(display);
                });

            },
            error: function () {

            }
        });
        // console.log(pl_id);
        // console.log(limit);
        active_pl = pl_id;
    }
    function getCurriculumPerProg(pl_id) {
        var btn = "";
        
        $.ajax({
          url: "<?= base_url('curriculum/showCurrPerProgram')?>",
          data: {pl_id: pl_id},
          type: "GET",
          dataType: "json",
          success: function (data) {
            // var display = "";
            $.each(data, function (index, value) {
            // if(value.revision_no === null && value.date_issued === null && value.issued_no === null && value.document_code === null){
               btn = "<button class=\"btn btn-xs btn-primary btncurrInfo\" value=\""+value.cur_id+"\"revNum=\""+value.revision_no+"\" dateIssue=\""+value.date_issued+"\" issueNum=\""+value.issued_no+"\" documentCode=\""+value.document_code+"\" style=\"margin-left:10px;\" onclick=\"$('#setCurrInfo').modal('show')\"> Add Information </button>";
            // }else{
            //     btn = "";
            // }
              display = "<li>\
              <span class=\"fa fa-chevron-right\"></span>\
              <a href=\"javascript:;\" onclick=\"previewCurriculum(" + value.cur_id + ")\">Revised Curriculum Effectivity\
              <span class=\"lbl-sem\">" + value.eff_sem + "</span> " +
                  "SY: <span class=\"lbl-sy\">  " + value.eff_sy + "</span>" +
                  "</a>"+btn+
                  "</li>";

                        $(".curr-list-container .curr-list#listProg_" + pl_id).append(display);
                    });
                },  
            error: function () {

            }
        });
    }

    function addShSubject(con) {
      $.ajax({
        url: "<?php echo base_url('curriculum/add_subject') ?>",
        data: {tr: tr, con: con},
        type: "GET",
        dataType: "html",
        success: function (data) {
          $("#" + con + " table.table-curr").append(data);
          tr++;
          $("select.js-example-basic-multiple").select2('destroy');
          $("select.js-example-basic-multiple").select2();
        },
        error: function () {

        }
      });
    }
    function add_subject(con) {
        $.ajax({
            url: "<?php echo base_url('curriculum/add_subject') ?>",
            data: {tr: tr, con: con},
            type: "GET",
            dataType: "html",
            success: function (data) {
                $("#" + con + " table.table-curr").append(data);
                tr++;
                $("select.js-example-basic-multiple").select2('destroy');
                $("select.js-example-basic-multiple").select2();
            },
            error: function () {

            }
        });
    }
    function previewCurriculum(cur_id) {
        publicCurID = cur_id;
        $.ajax({
            url: "<?= base_url('curriculum/curriculumPreview') ?>",
            data: {cur_id: cur_id},
            type: "GET",
            dataType: "html",
            success: function (data) {
                yns = 0;
                $("#currPreviewContainer").html(data);
                retrieveSySem(cur_id);

                $("#formSaveRevisionCurriculum").on("submit", function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: "<?php echo base_url('curriculum/save_revision') ?>",
                        data: $(this).serialize(),
                        type: "POST",
                        dataType: "json",
                        success: function (data) {
                            if (data['result'] == true) {
                                previewCurriculum(publicCurID);
                                showMessage('Success', 'New curriculum has been set successfully.', 'success');
                            }
                            else {
                                showMessage('Error', 'Cannot set new curriculum. Please try again', 'error');
                            }
                        },
                        error: function () {
                        }
                    });
                });
            },
            error: function () {

            }
        });
    }
    function retrieveSySem(cur_id) {
        getSubjectTags();
        $.ajax({
            url: "<?= base_url('curriculum/getYearSem') ?>",
            data: {cur_id: cur_id},
            type: "GET",
            dataType: "json",
            success: function (data) {
                var display = "";
                $.each(data, function (key, value) {
                    display += value;
                    yns++;
                });
                $("#existing_ys_container").html(display);
                $("select.js-example-basic-multiple").select2();
            },
            error: function () {

            }
        });

    }
    function getSubjectTags() {
        $.ajax({
            url: "<?= base_url('curriculum/getSubjectLoadTags') ?>",
            dataType: "json"
        }).done(function(data) {
          json = data;
        });
    }
    function cancelScheduling(bs_id){
        bootbox.confirm("Are you sure you want to cancel scheduling? The schedules that have been saved will be deleted.", function(result) {
            if(result == true){
                $.ajax({
                    url: "<?php echo base_url('course_schedule/cancelScheduling') ?>",
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



</script>