<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Curriculum extends CURRV_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct()
    {
        parent::__construct();
        $this->checkAuthSession();
        $this->load->model('curr_codelist');
        $this->load->model('subjects');
        $this->load->model('year_sem');
        $this->load->model('curr_subject');
        $this->load->model('subjects');
        $this->load->model('pre_requisite');
        $this->load->model('curr_codelist');
    }

    public function index()
    {
        $data  = array('title'   => 'Curriculum',
                       'pageID' => 'curriculum',
                       'heading' => 'Curriculum',
                       'message' => '');
        $data['userInfo'] = (array) $this->session->userdata('userSessionLogin');

        
        $this->load->view('includes/header',$data);
        $this->load->view('includes/menu',$data);
        $this->load->view('curriculum/index');
        $this->load->view('includes/footer');
        $this->load->view('curriculum/js');
    }

    public function add_sem_year()
    {   
        $shsGradeList = ['Grade 11 - First Semester',
                         'Grade 11 - Second Semester',
                         'Grade 12 - First Semester',
                         'Grade 12 - Second Semester',
                         'Grade 13 - First Semester',
                         'Grade 13 - Second Semester',
                         'Grade 14 - First Semester',
                         'Grade 14 - Second Semester',
                         'Grade 15 - First Semester',
                         'Grade 15 - Second Semester'];
        $data['userInfo'] = (array) $this->session->userdata('userSessionLogin');
        $display = "";
        $subj_name_list = "";
        $subj_code_list = "";
        $sy = $this->input->get("ys");
        $tr = $this->input->get("tr");
        $query = $this->db->query("SELECT * FROM subject");
        $sub = $query->result();
        foreach ($sub as $key => $value) {
            $subj_name_list .= "<option value='" . $value->subj_id . "'>" . $value->subj_name . "</option>";
            $subj_code_list .= "<option value='" . $value->subj_id . "'>" . $value->subj_code . "</option>";
        }
        //(($data['userInfo']['dep_id']) == 4) ? $shsGradeList[$this->input->get('yns')] : ''

        $display .= "<div id='ys_" . str_replace(' ', '', $sy) . "' class=\"curr-preview-body\">
                        <center>
                            <input type='hidden' name='txt_ys[]' value='" . $sy . "'>
                            <h6 class=\"m-t-30 m-b-0\">" . (($data['userInfo']['dep_id'] == 4) ?  $shsGradeList[$this->input->get('yns')]  : $sy). "</h6>
                            <table class=\"table table-curr\">
                                <thead>
                                    <tr>
                                        <td>Course</td>
                                        <td>Title</td>
                                        <td>Lec</td>
                                        <td>Lab</td>
                                        <td>Unit</td>
                                        <td>Pre-requisites</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>";

        $display .= "<tr id='" . $tr . "'>
                                                    <td>...</td>
                                                    <td>
                                                        <select onchange=\"setNameSelect2($(this).closest('tr').find('td select.js-example-basic-multiple').attr('name', 'subj_'+$(this).val()+'[]'))\" name='ys_" . str_replace(' ',
                '', $sy) . "_sub_id[]' required class=\"preview-select-title\">
                                                            <option value='' selected class='hide'>Select subject ...</option>";
        
        // foreach ($sub as $key2 => $value2) {
        //     $display .= "<option value='" . $value2->subj_id . "'>" . $value2->subj_name . "</option>";
        // }
        $display .= $subj_name_list;
        $display .= "</select>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        <select name='' placeholder='Select Pre-requisites' style='outline:none;border:none' class=\"form-control js-example-basic-multiple\" multiple=\"multiple\">";
        // $query = $this->db->query("SELECT * FROM subject");
        // $sub = $query->result();

        // foreach ($sub as $key1 => $value1) {
        //     $display .= "<option value='" . $value1->subj_id . "'>" . $value1->subj_code . "</option>";
        // }
        $display .= $subj_code_list;
        $display .= "</select>
                                                    </td>
                                                    <td><a onclick=\"remove_subject($(this).attr('con'),$(this).attr('tr'))\" con='ys_" . str_replace(' ',
                '', $sy) . "' tr='" . $tr . "' title='remove' href=\"javascript:;\"><i class='fa fa-times'></i></a></td>
                                                </tr>";

        $display .= "</tbody>
                                <tfooter>
                                    <tr>
                                        <td colspan=\"5\"><button onclick=\"add_subject($(this).attr('con'))\" con='ys_" . str_replace(' ',
                '', $sy) . "' class='btn btn-xs btn-default btn-add-subject' type='button'>Add subject</button></td>
                                        <td colspan=\"2\">Total Unit: </td>
                                    </tr>
                                </tfooter>
                            </table>
                        </center>
                     </div>";

        echo $display;
    }


    

    public function getCurriculumByUser()
    {
        $userData = (array) $this->session->userdata('userSessionLogin');
        $query = $this->db->query("SELECT sis_main_db.program_list.pl_id, sis_main_db.program_list.prog_code, sis_main_db.program_list.prog_abv, sis_main_db.program_list.prog_name, sis_main_db.program_list.prog_desc, sis_main_db.program_list.prog_type, sis_main_db.program_list.`level`, sis_main_db.program_list.major, sis_main_db.program_list.senior_high_track, sis_main_db.program_list.created_at, sis_main_db.program_list.updated_at, sis_main_db.department.dep_name, sis_main_db.department.dep_desc, sis_main_db.department.dep_id FROM sis_main_db.program_list INNER JOIN sis_main_db.department ON program_list.dep_id = department.dep_id WHERE department.dep_id = {$userData['dep_id']}");

        $programs = $query->result();
        $data = [];
        $display = '';

        if (!empty($programs[0])){
            foreach ($programs as $program){
                $display = '<div class="curr-list-container">';
                $display .= '    <div class="list-head clearfix">';
                $display .= '        <div class="list-counter text-center pull-left">';
                $display .= '            <h3 class="m-t-0 m-b-0">' . $this->count_revices($program->pl_id) . '</h3>';
                $display .= '            <span class="text-white">revises</span>';
                $display .= '        </div>';
                $display .= '    <div class="pull-left p-l-10">';
                $display .= '        <h5 class="m-t-0 m-b-0">' . strtoupper($program->prog_name) . '</h5>';
                $display .= '            <h6 class="m-t-0 m-b-0">' . ucwords($program->major) . '</h6>';
                $display .= '            <small class="m-t-0 m-b-0">' . ucwords($program->dep_name) . '</small>';
                $display .= '     </div>';
                $display .= '   </div>';
                $display .= '   <div class="list-content p-t-10" style="min-height:100px;max-height:300px;overflow:auto;">';
                $display .= '     <ul id="listProg_' . $program->pl_id . '" class="curr-list"></ul>';
                $display .= '</div>';

                $data[] = [
                    'program'=> $display,
                    'program_id'=>$program->pl_id
                ];
            }
        }
        echo json_encode(['data'=>$data]);
    }
    public function setActiveInactive()
    {
        $cur_id = $this->input->get("cur_id");
        // GET CURRENT STATUS //
        $curInfo = $this->curr_codelist->where("cur_id",$cur_id)->get();
        $curStatus = $curInfo['status'];
        // UPDATE STATUS
        $str = "";
        $str2 = "";
        if ($curStatus == "active") {
            $curStatus = "inactive";
            $str = "Activate";
        } elseif ($curStatus == "inactive") {
            $curStatus = "active";
            $str = "Deactivate";
        }
        $queryResult = '';
        $this->db->simple_query("UPDATE curr_codelist SET status = '{$curStatus}' WHERE cur_id = {$cur_id}");

        if ($this->db->affected_rows()) {
            $queryResult = true;
            $str2 = $str;
        }
        echo json_encode(array("result" => $queryResult, "str" => $str2));
    }

    public function curriculumList()
    {
        $data['userInfo'] = (array) $this->session->userdata('userSessionLogin');
        $query = $this->db->query("SELECT * from sis_main_db.program_list where dep_id = {$data['userInfo']['dep_id']}");
        $programs = $query->result();
        $display = "";
        $array = array();

        foreach ($programs as $key => $value) {

            $display .= '<div class="curr-list-container">';
            $display .= '    <div class="list-head clearfix">';
            $display .= '        <div class="list-counter text-center pull-left">';
            $display .= '            <h3 class="m-t-0 m-b-0">' . $this->count_revices($value->pl_id) . '</h3>';
            $display .= '            <span class="text-white">revises</span>';
            $display .= '        </div>';   
            $display .= '    <div class="pull-left p-l-10">';
            $display .= '        <h5 class="m-t-0 m-b-0">' . strtoupper($value->prog_name) . '</h5>';
            $display .= '            <h6 class="m-t-0 m-b-0">' . $value->major . '</h6>';
            $display .= '     </div>';
            $display .= '   </div>';
            $display .= '   <div class="list-content p-t-10" style="min-height:100px;max-height:300px;overflow:auto;">';
            $display .= '     <ul id="listProg_' . $value->pl_id . '" class="curr-list"></ul>';
            $display .= '     <button onclick="showMoreCurr(' . $value->pl_id . ')" class="btn btn-xs btn-default">Show more</button>';
            $display .= '  </div>';
            $display .= '</div>';

            $array[$value->pl_id] = $display;
            $display = "";
        }
        echo json_encode($array);
    }

    public function showCurrPerProgram()
    {
        $pl_id = $this->input->get("pl_id");
        $query = $this->curr_codelist->db->query("SELECT * FROM curr_codelist WHERE pl_id = {$pl_id} ORDER BY eff_sy DESC ");
        $curriculum = $query->result();
        echo json_encode($curriculum);
    }
    public function getSubjectLoadTags()
    {   
        
        $query = $this->subjects->db->query("SELECT * FROM subject ORDER BY subj_name ASC");
        $list = $query->result();
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $array[] = array("id" => "$value->subj_id", "text" => "$value->subj_code");
            }
        }
        echo json_encode($array);
    }
    public function showMoreCurr()
    {
        $limit = $this->input->get("limit");
        $pl_id = $this->input->get("pl_id");

        $query = $this->db->query("SELECT * FROM curr_codelist WHERE pl_id = {$pl_id} ORDER BY eff_sy DESC LIMIT {$limit},5");
        $curriculum = $query->result();
        echo json_encode($curriculum);
    }
    public function setNewCUrriculum()
    {
        $data = $this->input->post();
        $data['status'] = 'active';
        $this->curr_codelist->insert($data);
        if($this->db->affected_rows()){
            $result = true;
        }else{
            $result = false;
        }
        echo json_encode(array("result" => $result));
    }
    public function getProgramMajor()
    {
        $pl_id = $this->input->get('pl_id');
        $query = $this->db->query("SELECT * from sis_main_db.program_list where pl_id = {$pl_id}");
        $programs = $query->result();
        foreach ($programs as $key => $value) {
            echo json_encode(array("major" => $value->major));
        }
    }
    public function program_list()
    {       
        
        $data['userInfo'] = (array) $this->session->userdata('userSessionLogin');

        $query = $this->db->query("SELECT * from sis_main_db.program_list where dep_id = {$data['userInfo']['dep_id']}");
        $programs = $query->result();
       
        echo json_encode($programs);
    }

    public function save_revision()
    {
        $cur_id = $this->input->post("cur_id");
        $ys = $this->input->post("txt_ys");

        //DELETE PREVIOUS SUBJECTS //
        $ysList = $this->year_sem->where("cur_id",$cur_id)->get_all();
        if(!empty($ysList)){
            foreach ($ysList as $k1 => $v1) {
                $deleteResult = $this->db->simple_query("DELETE FROM cur_subject WHERE ys_id = {$v1['ys_id']}");
            }
        }

        foreach ($ys as $key => $value) {
            $s = explode(" - ", $value);
            // -------------------------- SAVE YEAR ------------------------- //
            // CHECK IF YEAR AND SEMISTER EXIST IN CURRICULUM//
            $currCheck = $this->year_sem->where(array("cur_id" => $cur_id, "year" => $s[0], "semister" => $s[1]))->get_all();
            if (!empty($currCheck)) {
                // GET THE YS ID
                $ys_id = 0;
                foreach ($currCheck as $key1 => $value1) {
                    $ys_id = $value1['ys_id'];
                }
                // SAVE SUBJECT //
                $subj_id = $this->input->post("ys_" . str_replace(' ', '', $value) . "_sub_id");
                if ($deleteResult) {
                    if (!empty($subj_id)) {
                        foreach ($subj_id as $key2 => $value2) {
                            // SAVE SUBJECT
                            $curSubArray = array(
                                'subj_id' => $value2,
                                'ys_id' => $ys_id,
                            );
                            $this->curr_subject->insert($curSubArray);

                            $preq = $this->input->post("subj_" . $value2);
                            if ($this->db->affected_rows() > 0) {
                                // SAVE PRE-REQUISITE //
                                $cs_id = $this->db->insert_id();
                                if (!empty($preq)) {
                                    foreach ($preq as $kpreq => $vpreq) {

                                        // GET PREREQUISITES //
                                        $prqData = array(
                                            'cs_id' => $cs_id,
                                            'subj_id' => $vpreq
                                        );
                                        $this->pre_requisite->insert($prqData);
                                    }
                                }
                            }
                        }
                    }

                }
            } // iF NOT EXIST YEAR AND SEMISTER IN CURRICULUM
            else {
                // SAVE YEAR AND SEMISTER //
                $yearSemData = array(
                    'year'=>$s[0],
                    'semister' => $s[1],
                    'cur_id' => $cur_id
                );
                $this->year_sem->insert($yearSemData);

                // IF YEAR AND SEM SAVED
                if ($this->db->affected_rows() > 0) {
                    $ys_id = $this->db->insert_id();
                    // SAVE SUBJECT
                    $subj_id = $this->input->post("ys_" . str_replace(' ', '', $value) . "_sub_id");
                    foreach ($subj_id as $key2 => $value2) {
                        // SAVE SUBJECT
                        $curSubArray = array(
                            'subj_id' => $value2,
                            'ys_id' => $ys_id,
                        );
                        $this->curr_subject->insert($curSubArray);

                        $preq = $this->input->post("subj_" . $value2);
                        if ($this->db->affected_rows() > 0) {
                            // SAVE PRE-REQUISITE //
                            $cs_id = $this->db->insert_id();
                            if (!empty($preq)) {
                                foreach ($preq as $kpreq => $vpreq) {
                                    // GET PREREQUISITES //
                                    $prqData = array(
                                        'cs_id' => $cs_id,
                                        'subj_id' => $vpreq
                                    );

                                    $this->pre_requisite->insert($prqData);

                                }
                            }
                        }
                    }

                }
            }
        }
        echo json_encode(array("result" => true));
    }
    private function count_revices($pl_id)
    {
        $currList = $this->curr_codelist->where('pl_id',$pl_id)->get_all();
        return $result = !empty($currList) ? count($currList) : false;

    }
    public function getRrevisionNumber($cur_id)
    {   
        $result = $this->curr_codelist->where('cur_id',$cur_id)->get();
        return $result = !empty( $result['revision_no']) ?  $result['revision_no'] : false;
    }
    public function getDocumentCode($cur_id)
    {   
        $result = $this->curr_codelist->where('cur_id',$cur_id)->get();
        return $result = !empty( $result['document_code']) ?  $result['document_code'] : false;
    }
    public function getIssuedNumber($cur_id)
    {   
        $result = $this->curr_codelist->where('cur_id',$cur_id)->get();
        return $result = !empty( $result['issued_no']) ?  $result['issued_no'] : false;
    }
     public function getDateIssued($cur_id)
    {   
        $result = $this->curr_codelist->where('cur_id',$cur_id)->get();
        return $result = !empty( $result['date_issued']) ?  (new DateTime($result['date_issued']))->format('F d, Y') : false;
    }
    public function saveCurrInfo()
    {
       $data = ['revision_no'=> $this->input->post('revNum'),
                                      'date_issued'=> $this->input->post('dateIssue'),
                                      'issued_no'=> $this->input->post('issueNum'),
                                      'document_code'=> $this->input->post('documentCode')
                                      ];
        //array ( 'curr_id' => '79', 'revNum' => 'asd', 'dateIssue' => '2020-01-23', 'issueNum' => 'asdas', 'documentCode' => 'dasd', )
        $result = $this->curr_codelist->update($data,$this->input->post('curr_id'));
        echo $result;
    }
    public function curriculumPreview()
    {
        $cur_id = $this->input->get('cur_id');
        $revision_template = "";
        $currInfo = $this->curr_codelist->where('cur_id',$cur_id)->get();

        $pl_id = $currInfo['pl_id'];
        $currStatus = $currInfo['status'];

        if ($currStatus == "active") {
            $currStatus = "Deactivate";
        } elseif ($currStatus == "inactive") {
            $currStatus = "Activate";
        }

        if($currInfo['revision_no'] == '' && $currInfo['date_issued'] == '0000-00-00' && $currInfo['issued_no'] == '' && $currInfo['document_code'] == ''){

        }else{
            $revision_template = "<div><small>Revision No. <span style=\"color:#00698C;\">".$this->getRrevisionNumber($cur_id)."</span> / Document No. <span style=\"color:#00698C;\">".$this->getDocumentCode($cur_id)."</span> /Date Issued. <span style=\"color:#00698C;\">".$this->getDateIssued($cur_id)."</span> /Issued No. <span style=\"color:#00698C;\">".$this->getIssuedNumber($cur_id). "</span></small></div>";
        }

        $query = $this->curr_codelist->db->query("SELECT * from sis_main_db.program_list where pl_id = {$pl_id}");
        $result = $query->result();

        $program = "";
        $major = "";
        $display = "";

        foreach ($result as $key => $value) {
            $program = $value->prog_name;
            $major = $value->major;
        }
        $user =  $this->session->userdata('userSessionLogin');
         
        if($user->dep_id != 4 || $user->dep_id == 4) {
            $display .= '<form id="formSaveRevisionCurriculum" action=" ' . base_url('curriculum/save_revision') . '" method="post">';
            $display .= '<div class="curr-container">';
            $display .= '<input type="hidden" name="cur_id" value="' . $cur_id . '">';
            $display .= '<div class="curr-preview-header">';
            $display .= '<center>';
            $display .= '<h4 class="m-b-0"> ' . strtoupper($program) . '</h4>';
            $display .= '<h6 class="m-t-0 m-b-0">' . ucwords($major) . '</h6>';
            $display .= '<small>';
            $display .= 'Revised Curriculum Effectivity: Semester';
            $display .= '<select class="preview-select-sem">';
            $display .= '<option selected class="hide">' . $currInfo['eff_sem'] . '</option>';
            $display .= '<option>1st Semester</option>';
            $display .= '<option>2nd Semester</option>';
            $display .= '</select>';
            $display .= 'School Year';
            $display .= '<select class="preview-select-sy">';
            $display .= '<option selected class="hide">' . $currInfo['eff_sy'] . '</option>';

            for ($x = date('Y'); $x >= 2000; $x--) {
                $display .= "<option>" . $x . "-" . ($x + 1) . "</option>";
            }
            $display .= "</select></small>
                            ".$revision_template."
                            </center>
                        </div>
                        <div id=\"existing_ys_container\"></div>
                        <div id='year_sem_container'></div>
                        <button onclick=\"addYearAndSemister()\" type=\"button\" class=\"btn btn-xs btn-primary m-t-5\">Next Year / Semester</button>
                        <button onclick=\"removePreviousYS()\" type='button' class='btn btn-xs btn-danger m-t-5'>Remove Previous Year / Semester</button>
                    </div>
                    <div class=\"curr-preview-footer\">
                        <button type='submit' class=\"btn btn-success btn-sm\">Save</button>
                        <button onclick=\"cancelSave()\" type='button' class=\"btn btn-danger btn-sm\">Cancel</button>
                        <button id=\"btnSetActiveInactiveCurriculum\" onclick=\"setActiveInactiveCurriculum()\" type='button' class=\"btn btn-inverse btn-sm pull-right m-l-5\">" . ucfirst($currStatus) . " Curriculum</button>
                    </div></form>";
        }
        else{
            // var_export($display);
        }
        
        echo $display;

    }
    public function getYearSem()
    {

        $shsGradeList = ['First Year - First Semester' => 'Grade 11 - First Semester',
                         'First Year - Second Semester' => 'Grade 11 - Second Semester',
                         'Second Year - First Semester' => 'Grade 12 - First Semester',
                         'Second Year - Second Semester' => 'Grade 12 - Second Semester',
                         'Third Year - First Semester' => 'Grade 13 - First Semester',
                         'Third Year - Second Semester' => 'Grade 13 - Second Semester',
                         'Fourth Year - First Semester' => 'Grade 14 - First Semester',
                         'Fourth Year - Second Semester' => 'Grade 14 - Second Semester',
                         'Fifth Year - First Semester' => 'Grade 15 - First Semester',
                         'Fifth Year - Second Semester' => 'Grade 15 - Second Semester'];
        $data['userInfo'] = (array) $this->session->userdata('userSessionLogin');
        $cur_id = $this->input->get('cur_id');
        $this->curr_subject->db->order_by("ys_id");
        $list = $this->year_sem->where('cur_id',$cur_id)->get_all();

        $query = $this->subjects->db->query("SELECT * FROM subject ORDER BY subject.subj_name ASC");
        $sub = $query->result();

        $opt_con_title = '';
        $opt_con_pre = '';
        foreach ($sub as $key2 => $value2) {
            $opt_con_title .= "<option value='" . $value2->subj_id . "'><b>" .$value2->subj_code.'</b> - '. $value2->subj_name . "</option>";
            $opt_con_pre .= "<option value='" . $value2->subj_id . "'><b>" .$value2->subj_code."</option>";
        }

        $array = array();
        $display = "";
        $totalUnit = 0;
        foreach ($list as $key => $value) {
            // CHECK IF YS NOT EMPTY   .  .
            $countSub = $this->curr_subject->where('ys_id',$value)->get_all();
            if (!empty($countSub)) {
                $display .= "<div id='ys_" . str_replace(' ', '', $value['year'] . "-" . $value['semister']) . "' class=\"curr-preview-body\">
                        <center>
                            <input type='hidden' name='txt_ys[]' value='" . $value['year'] . " - " . $value['semister'] . "'>
                            <h6 class=\"m-t-30 m-b-0\">"  . (($data['userInfo']['dep_id'] == 4) ?  $shsGradeList[ $value['year'] . " - " . $value['semister']]  : $value['year'] . " - " . $value['semister']).  "</h6>
                            <table class=\"table table-curr\">
                                <thead>
                                    <tr>
                                        <td >Course</td>
                                        <td >Title</td>
                                        <td >Lec</td>
                                        <td >Lab</td>
                                        <td >Unit</td>
                                        <td  class='col-md-1'>Pre-requisites</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>";
                $query = $this->year_sem->db->query("SELECT * FROM subject,cur_subject WHERE cur_subject.ys_id = {$value['ys_id']} AND cur_subject.subj_id = subject.subj_id ");
                $result = $query->result();
                foreach ($result as $key1 => $value1) {
                    $display .= "<tr id='" . $value1->subj_code . "'>
                                                    <td>" . $value1->subj_code . "</td>
                                                    <td>
                                                        <select onchange=\"setNameSelect2($(this).closest('tr').find('td select.js-example-basic-multiple').attr('name', 'subj_'+$(this).val()+'[]'))\" name='ys_" . str_replace(' ',
                            '', $value['year'] . "-" . $value['semister']) . "_sub_id[]' required class=\"preview-select-title\">
                                                            <option class='hide' selected value='" . $value1->subj_id . "'>" .$value1->subj_name . "</option>";
                    // $query = $this->subjects->db->query("SELECT * FROM subject ORDER BY subject.subj_name ASC");
                    // $sub = $query->result();
                    // foreach ($sub as $key2 => $value2) {
                    //     $display .= "<option value='" . $value2->subj_id . "'>" .$value2->subj_code.' - '. $value2->subj_name . "</option>";
                    // }
                    $display .= $opt_con_title;
                    $display .= "</select>
                                        </td>
                                        <td>" . $value1->lec_unit . "</td>
                                        <td>" . $value1->lab_unit . "</td>
                                        <td>" . ($value1->lec_unit + $value1->lab_unit) . "</td>
                                        <td>
                                            <select name='subj_" . $value1->subj_id . "[]' placeholder='Select Pre-requisites' style='outline:none;border:none' class=\"form-control js-example-basic-multiple\" multiple=\"multiple\">";
                   
                    // GET PREREQUISITES //

                    // $preqSub = $this->pre_requisite->with_subjects()->where("cs_id",$value1->cs_id)->get_all();
                    $query = $this->db->query("SELECT pre_requisite.*, `subject`.* FROM pre_requisite INNER JOIN `subject` ON pre_requisite.subj_id = `subject`.subj_id WHERE pre_requisite.cs_id = '{$value1->cs_id}' ");
                    $result =  $query->result();
                    // var_export($result);

                    if(!empty($result)){
                         foreach ($result as $ky => $val) {
                            $display .= "<option selected value='"    . $val->subj_id . "'>" . $val->subj_code . "</option>";
                        }
                    }
                    // if (!empty($result['subjects'])) {
                    //     foreach ($prqSub['subjects'] as $ky => $val) {
                    //         $display .= "<option selected value='"    . $val->subj_id . "'>" . $val->subj_code . "</option>";
                    //     }
                    // }

                    //END GET PREREQUISITES 
                    // foreach ($sub as $key2 => $value2) {
                    //     $display .= "<option value='" . $value2->subj_id . "'>" . $value2->subj_code . "</option>";
                    // }
                    $display .= $opt_con_pre;
                    $display .= "</select>
                                                    </td>
                                                    <td><a onclick=\"remove_subject($(this).attr('con'),$(this).attr('tr'))\" con='ys_" . str_replace(' ',
                            '', $value['year'] . "-" . $value['semister']) . "' tr='" . $value1->subj_code . "' title='remove' href=\"javascript:;\"><i class='fa fa-times'></i></a></td>
                                                </tr>";
                    $totalUnit += ($value1->lec_unit + $value1->lab_unit);
                }
                $display .= "</tbody>
                                <tfooter>
                                    <tr>
                                        <td colspan=\"5\"><button onclick=\"add_subject($(this).attr('con'))\" con='ys_" . str_replace(' ',
                        '', $value['year'] . "-" . $value['semister']) . "' class='btn btn-xs btn-default btn-add-subject' type='button'>Add subject</button></td>
                                        <td colspan=\"2\">Total Unit: " . $totalUnit . "</td>
                                    </tr>
                                </tfooter>
                            </table>
                        </center>
                     </div>";
                $totalUnit = 0;
                $array[] = $display;
                $display = "";
            }
        }
        echo json_encode($array);
    }


    
    public function add_subject()
    {
        $tr = $this->input->get("tr");
        $con = $this->input->get("con");
        $display = "";
        $display .= "<tr id='" . $tr . "'>
                        <td>...</td>
                        <td>
                            <select onchange=\"setNameSelect2($(this).closest('tr').find('td select.js-example-basic-multiple').attr('name', 'subj_'+$(this).val()+'[]'))\" name='" . str_replace(' ',
                '', $con) . "_sub_id[]' required class=\"preview-select-title\">
                                <option value='' selected class='hide'>Select subject ...</option>";
        $query = $this->db->query("SELECT * FROM subject ORDER BY subj_name");
        $sub = $query->result();
        foreach ($sub as $key2 => $value2) {
            $display .= "<option value='" . $value2->subj_id . "'>".$value2->subj_code.' - '. $value2->subj_name . "</option>";
        }
        $display .= "</select>       
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <select name='' placeholder='Select Pre-requisites' style='outline:none;border:none' class=\"form-control js-example-basic-multiple\" multiple=\"multiple\">";
        $query = $this->db->query("SELECT * FROM subject");
        $sub = $query->result();
        foreach ($sub as $key1 => $value1) {
            $display .= "<option value='" . $value1->subj_id . "'>" . $value1->subj_code . "</option>";
        }
        $display .= "</select>
                        </td>
                        <td><a onclick=\"remove_subject($(this).attr('con'),$(this).attr('tr'))\" con='" . str_replace(' ', '', $con) . "' tr='" . $tr . "' title='remove' href=\"javascript:;\"><i class='fa fa-times'></i></a></td>
                    </tr>";

        echo $display;
    }


      
}
