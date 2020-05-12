<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cpanel extends CURRV_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
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
	    $this->load->model('user_type');
	    $this->load->model('curr_codelist');
	    $this->load->model('user');
	    $this->load->model('stud_load_cap_set');
	    $this->load->model('sched_subj');
	    $this->load->model("subject_declaration");
	    $this->load->model('block_section');

	}

	public function index()
	{
		$data  = array('title'   => 'C-panel',
					   'pageID'  => 'cpanel',
				   	   'heading' => 'C-panel',
				   	   'message' => '',
				   	   'curriculum' => $this->curr_codelist->active_curriculum(),
				   	   'user_type' => $this->user_type->fetchData(),
				   	   'department' => $this->department());
		$data['userInfo'] = (array) $this->session->userdata('userSessionLogin');
			

		$this->load->view('includes/header',$data);
		$this->load->view('includes/menu',$data);
		$this->load->view('cpanel/index');
		$this->load->view('includes/footer');
		$this->load->view('cpanel/js');
	}
	public function department(){
	    $query = $this->db->query("SELECT * FROM sis_main_db.department");
	    $result = $query->result();

	    if (!empty($result)) {
	      return $result;
	    } else {
	      return array();
	    }
	}
	public function courseStatusLoadAllSection()
	{

		$data['userInfo'] = (array) $this->session->userdata('userSessionLogin');
	    $type = $this->input->get('type');
	    $sy = $this->input->get('sy');
	    $sem = $this->input->get('sem');

	    $str = "SELECT
	                    block_section.*, sis_main_db.program_list.prog_name,
	                    prog_code,prog_abv,
	                    major
	                FROM
	                    block_section
	                LEFT JOIN sis_main_db.program_list ON sis_main_db.program_list.pl_id = block_section.pl_id
	                WHERE
	                    block_section.semister = '{$sem}'
	                AND block_section.sy = '{$sy}'";

	    if ($type == "#block") {
	      $str .= " AND block_section.pl_id != 0 AND sis_main_db.program_list.dep_id = {$data['userInfo']['dep_id']}";
	    } elseif ($type == "#off") {
	      $str .= " AND block_section.pl_id = 0";
	    }

	    $query = $this->db->query($str);
	    $list = $query->result();

	    if (!empty($list)) {
	      echo json_encode($list);
	    } else {
	      echo json_encode(array());
	    }
	}
	public function loadSLCS()
	{
	    $list = $this->stud_load_cap_set->fetchData();
	    echo json_encode($list);
	}
	public function userList()
	{
	    $list = $this->user->fetchData();
	    // $list = $user->data_table();
	    $data = array();


		if (!empty($list)) {
		    foreach ($list as $temp) {
		        $data[] = array(
		          'name' => ucwords($temp['user_fname']) . ' ' . ucwords($temp['user_lname']),
		          'username' => $temp['username'],
		          'department' => ucwords($temp['user_department']),
		          'position' => ucwords($temp['user_position']),
		          'id' => $temp['user_id']

		        );
		    }
		}
	    echo json_encode(array('data'=>$data));
	}
	public function saveSLCS()
	{

	    $this->form_validation->set_rules('student_type', 'Student type', 'required|is_unique[Stud_load_cap_set.student_type]',
	      array('required' => 'You must provide a %s.', 'is_unique' => '%s already exist.'));
	    $this->form_validation->set_rules('unit_capacity', 'No of units', 'required|is_natural_no_zero',
	      array('required' => 'You must provide a %s.', 'is_unique' => '%s already exist.'));


	    if ($this->form_validation->run() == FALSE) {
	      $errors = validation_errors();
	      echo json_encode(["result" => "validateError", "errors" => $errors]);
	    } else {
	      // $slcs = new Stud_load_cap_set;
	      $data = $this->input->post();
	      if (!empty($data)) {

	        if ($data['slcs_id'] != "") {
	          $slcs_id = $data['slcs_id'];
	          unset($data['slcs_id']);
	          $this->stud_load_cap_set->update($data,$slcs_id);
	          $type = "updated";
	        }else{
	        	$this->stud_load_cap_set->insert($data);
	        	$type = "saved";
	        }

	        if ($this->db->affected_rows() > 0 || $this->db->affected_rows() == 0) {
	          echo json_encode(array("result" => true, "type" => $type));
	        } elseif ($slcs->db->affected_rows() == 0) {
	          echo json_encode(array("result" => false, "type" => $type));
	        }
	      }
		}
	}
	public function deleteSLCS()
	{
	   
	    $this->stud_load_cap_set->delete($this->input->get('slcs_id'));
	    if ($this->db->affected_rows() > 0) {
	      echo json_encode(array("result" => true));
	    } else {
	      echo json_encode(array("result" => false));
	    }
	}
	public function courseStatusLoadSubject()
	{
	    $status = "";

	    $bs_id = $this->input->get("bs_id");
	    $list = $this->sched_subj->getSubjectFromSched($bs_id);

	    
	    foreach ($list as $key => $value) {
	      $value->enrolled = $this->courseStatusCountEnrolled($value->ss_id);
	      $value->class_schedule = $this->courseStatusGetSubjectSchedule($value->subj_id, $value->bs_id);
	      $value->declaration = $this->getDeclarationStatus($value->ss_id);
	    }


	    
	    echo json_encode($list);

	}
	public function editSLCS()
	{
	    $slcs_id = $this->input->get('slcs_id');
	    $info = $this->stud_load_cap_set->where("slcs_id",$slcs_id)->get_all();
	    echo json_encode($info);
	 }

	public function saveUser()
	{	
		$inputData = $this->input->post();
		
		$data = [	'username' => $inputData['username'],
					'password' => $inputData['password'],
					'user_fname' => $inputData['user_fname'],
					'user_lname' => $inputData['user_lname'],
					'user_mname' => $inputData['user_mname'],
					'user_department' => '',
					'user_position' => $inputData['user_position'],
					'user_status' => 'active',
					'user_image' => 'default.png',
					'dep_id' => $inputData['dep_id'],
					'user_type_id' => $inputData['user_type_id']];

		$this->user->insert($data);
	    if ($this->db->affected_rows() > 0) {
	        echo json_encode(["result" => true]);
	    } else {
	        echo json_encode(["result" => false]);
	    }
	}
	public function loadSubject()
	{
	    $status = "";

	    $bs_id = $this->input->get("bs_id");

	    $list = $this->sched_subj->getSubjectFromSched($bs_id);


	    foreach ($list as $key => $value) {
	      if ($value->employee_id != "") {
	        $value->status = "taken";
	      } else {
	        $value->status = "vacant";
	      }
	    }
	    echo json_encode($list);
	}
	public function changeSubjectStatus()
	{
	    $ss_id = $this->input->get("ss_id");
	    $status = $this->input->get("status");

	    // CHECK IF SS_ID EXIST
	    $declaration = $this->subject_declaration->where("ss_id",$ss_id)->get_all();
	    if (empty($declaration)) {
	      // SAVE DECLARATION
	      $this->subject_declaration->insert(['ss_id'=>$ss_id,'declaration'=>$status]);

	      if ($this->db->affected_rows() > 0) {
	        echo json_encode(["result" => true]);
	      } else {
	        echo json_encode(["result" => false]);
	      }
	    } else {
	      // UPDATE DECLARATION
	      foreach ($declaration as $key => $value) {

	      	$this->subject_declaration->update(['ss_id'=>$ss_id,'declaration'=>$status],$value['sd_id']);

	        if ($this->db->affected_rows() >= 0) {
	          echo json_encode(["result" => true]);
	        } else {
	          echo json_encode(["result" => false]);
	        }
	      }
	    }
	}
	private function courseStatusGetSubjectSchedule($subj_id, $bs_id)
	{

	    

	    $sched['Lecture'] = array();
	    $sched['Laboratory'] = array();

	    $list = $this->sched_subj->getRoomSchedSubj($bs_id,$subj_id);

	    foreach ($list as $key => $value) {
	      if ($value->type == "Lecture") {

	        $sched['Lecture'][] = array(
	          "ss_id" => $value->ss_id,
	          "rl_id" => $value->rl_id,
	          "sd_id" => $value->sd_id,
	          "start" => $value->time_start,
	          "end" => $value->time_end,
	          "room" => $value->room_code,
	          "sched" => $value->abbreviation,
	          "time_start" => date_format(date_create($value->time_start), "h:i A"),
	          "time_end" => date_format(date_create($value->time_end), "h:i A"),
	        );
	      } elseif ($value->type == "Laboratory") {

	        $sched['Laboratory'][] = array(
	          "ss_id" => $value->ss_id,
	          "rl_id" => $value->rl_id,
	          "sd_id" => $value->sd_id,
	          "start" => $value->time_start,
	          "end" => $value->time_end,
	          "room" => $value->room_code,
	          "sched" => $value->abbreviation,
	          "time_start" => date_format(date_create($value->time_start), "h:i A"),
	          "time_end" => date_format(date_create($value->time_end), "h:i A"),
	        );
	      }
	    }
	    return $sched;
	}
	private function courseStatusCountEnrolled($ss_id)
	{
	    $query = $this->db->query("SELECT * FROM sis_main_db.subject_enrolled where ss_id = {$ss_id}");
	    $list = $query->result();

	    $count = 0;
	    if (!empty($list)) {
	      $count = count($list);
	    }
	    return $count;
	}
	public function activeCurriculum()
	{
		$data['userInfo'] = (array) $this->session->userdata('userSessionLogin');
	    $sy = $this->input->get("sy");
	    $sem = $this->input->get("sem");

	    $query = $this->db->query("SELECT
	                                    curr_codelist.*, sis_main_db.program_list.prog_name,major,
	                                    prog_abv
	                                FROM
	                                    curr_codelist,
	                                    sis_main_db.program_list
	                                WHERE
	                                    curr_codelist.status = 'active' AND
	                                    curr_codelist.eff_sy = '$sy' AND
	                                    curr_codelist.eff_sem = '$sem'
	                                AND sis_main_db.program_list.dep_id = {$data['userInfo']['dep_id']}
	                                AND curr_codelist.pl_id = sis_main_db.program_list.pl_id");

	    $list = $query->result();

	    $tblData = array();

	    foreach ($list as $key => $value) {

	      $tblData[] = array($value->cur_id, "<div class=\"col-md-12\"><span class=\"f-s-20\">" . strtoupper($value->prog_abv) . " - </span>" . ucwords($value->major) . "
	                          <ul class=\"m-b-0\">
	                            <li><small>" . $value->eff_sem . " SY: " . $value->eff_sy . "</small></li>
	                          </ul></div>", $value->pl_id, $value->eff_sem, $value->eff_sy);
	    }

	    echo json_encode($tblData);
	}
	public function loadBlockSection()
	{

	    $semister = array("1st Semester" => "First Semester", "2nd Semester" => "Second Semester");

	    $pl_id = $this->input->get('pl_id');
	    $sem = $this->input->get('sem');
	    $sy = $this->input->get('sy');

	    $list = $this->block_section->where("pl_id",$pl_id)->where("semister",$semister[$sem])->where("sy",$sy)->get();
	    if (!empty($list)) {
	      echo json_encode($list);
	    } else {
	      echo json_encode(array());
	    }
	}
	public function loadOffSection()
	{
	    $semister = array("1st Semester" => "First Semester", "2nd Semester" => "Second Semester");
	    $sy = $this->input->get("sy");
	    $sem = $this->input->get("sem");

	    $list = $this->block_section->where("pl_id",0)->where("sy",$sy)->where("semister",$semister[$sem])->get_all();

	    // var_export($this->db->last_query());

	    if (!empty($list)) {
	      echo json_encode($list);
	    } else {
	      echo json_encode(array());
	    }
	}
	private function getDeclarationStatus($ss_id)
	{
	    
	    $declaration = $this->subject_declaration->where("ss_id",$ss_id)->get_all();

	    if (!empty($declaration)) {
	      foreach ($declaration as $key => $value) {

	        $color = array("tutorial" => "label-warning", "bridge" => "label-info", "dissolve" => "label-danger");
	        return "<span class='label " . $color[$value['declaration']] . "'>" . ucwords($value['declaration']) . "</span>";
	      }
	    } else {
	      return "";
	    }
	}

	public function editUser()
	{
	    $list = $this->user->where("user_id",$this->input->get('user_id'))->get();
	   	$list['password'] = '';
	    echo json_encode($list);
	}
}
