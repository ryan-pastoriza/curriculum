<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_load extends CURRV_Controller {

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
	    $this->load->model('curr_codelist');
	    $this->load->model('instructor');
	    $this->load->model('subjects');
	    $this->load->model('sched_subj');
	    $this->load->model('subj_sched_day');
	    $this->load->model('sched_day');
        $this->load->model('block_section');
        $this->load->model('room_list');
	}

	public function index()
	{
		$data['userInfo'] = (array) $this->session->userdata('userSessionLogin');
		$data  = array('title'   => 'Instructor Course Loading',
					   'pageID' => 'course_load',
				   	   'heading' => 'Instructor Course Loading',
				   	   'message' => '');
		$data['userInfo'] = (array) $this->session->userdata('userSessionLogin');
        $data['section_school_year'] = $this->block_section->schoolYear();  
		// $data['curriculum'] = $this->curr_codelist->active_curriculum();

		$this->load->view('includes/header',$data);
		$this->load->view('includes/menu',$data);
		$this->load->view('course/load/index');
		$this->load->view('includes/footer');
		$this->load->view('course/load/js');
	}
	public function assign_schedule()
	{	
		$data = $this->sched_subj->as_array()->where('ss_id',$this->input->post('ssid'))->get();
		$data['employee_id'] = $this->input->post('emp_id');
		unset($data['ss_id']);
		unset($data['created_at']);
        $this->sched_subj->update($data,$this->input->post('ssid'));
	}
	public function getInstructorList()
	{
		echo json_encode($this->instructor->get_list());
	}
	public function preview_schedule(){
       
        $ss = $this->sched_subj->as_object()->where(['ss_id'=>$this->input->post('ssid'),'sy'=>$this->input->post('sy'),'sem'=>$this->input->post('sem')])->get();
         // var_export($this->input->post());
          // var_export($ss);
        $active = $ss->employee_id == "" ? "" : "disabled";


        $scheds = $this->subj_sched_day->getScheduleWithRoom($ss->ss_id);


         echo "<div class=\"col-sm-9\"> ";

        foreach ($scheds as $value) {
        	$sched_day = $this->sched_day->where('sd_id',$value->sd_id)->get_all();
            // $sched_day = $value->hasMany('Sched_day');

            $daysStr = "";
            $count = 0;
            // var_dump($sched_day);
            foreach ($sched_day as $v) {
                $count++;
                if($count == count($sched_day)){
                    $daysStr .= $v['abbreviation']."";

                }
                else{
                    $daysStr .= $v['abbreviation'].",";
                }
            }
            	
                 echo "<b for=\"\" class=\"text-info\">{$value->room_code} : {$daysStr} - ".date('h:i a', strtotime($value->time_start))." - ".date('h:i a',strtotime($value->time_end))."</b><br>";
        }
        $val =  'NO SCHEDULE OR CONFLICT';
        if (isset($value->ss_id)){
            $val = $value->ss_id;
        }
              echo "  </div>";
                echo "<div class=\"col-sm-3\">
                  <button assign-sched='{$val}'  class=\"btn btn-app btn-info {$active}\">Assign <span class=\"fa fa-caret-right\"></span></button>
                </div> ";
    }
    public function checkConflictSubject()
    {
    	$isConflict =  $this->subj_sched_day->checkConflict($this->input->get());
    	if(empty($isConflict)){
    		echo json_encode("1");
    	}else{
    		echo json_encode("2");
    	}
    }
	public function printCalendar()
	{

        $ins_id = $this->input->get("ins");
        $sem = $this->input->get("sem");
        $sy = $this->input->get("sy");
       
  
        $sched = $this->instructor->db->query("
                    SELECT
                    *
                    FROM subj_sched_day
                    INNER JOIN sched_day ON subj_sched_day.sd_id = sched_day.sd_id
                    INNER JOIN room_list ON subj_sched_day.rl_id = room_list.rl_id
                    INNER JOIN sched_subj ON subj_sched_day.ss_id = sched_subj.ss_id
                    INNER JOIN `subject` ON sched_subj.subj_id = `subject`.subj_id
                    WHERE
                    sched_subj.employee_id = '{$ins_id}'
                    AND sched_subj.sem = '{$sem}'
                    AND sched_subj.sy = '{$sy}'
                    AND sched_subj.avs_status = 'active'
                    -- 
                                        ");
        $list = $sched->result();
        $sub = array();
        foreach ($list as $key => $value) {
            $sub[] = array(
                "bs_id"=>$value->bs_id,
                "subj_id"=>$value->subj_id,
                "title" => "{$value->subj_code} - {$value->room_code}",
                "start" => date("Y-m-d {$value->time_start}", strtotime("{$value->composition} this week")),
                "end" => date("Y-m-d {$value->time_end}", strtotime("{$value->composition} this week")),
                "allDay" => false,
                "color" => "rgb(0, 133, 178)",
                "textColor" => "#FFF",
                "time_start" => date_format(date_create($value->time_start), 'h:i A'),
                "time_end" => date_format(date_create($value->time_end), 'h:i A'),
                "composition" => $value->composition,
                "subject" => $value->subj_name,
                "room" => $value->room_code
            );
        }
        $data['sched'] = $sub;
        $this->load->view("course/load/print", $data);
    }
	public function get_instuctor_sched()
    {
        $sem = $this->input->get('sem');
        $sy = $this->input->get('sy');
        $ins_id = $this->input->get('ins_id');


            $sched = $this->instructor->db->query("
                SELECT 
                * 
                FROM subj_sched_day
                INNER JOIN sched_subj ON subj_sched_day.ss_id = sched_subj.ss_id
                INNER JOIN room_list ON subj_sched_day.rl_id = room_list.rl_id
                INNER JOIN sched_day ON subj_sched_day.sd_id = sched_day.sd_id
                INNER JOIN `subject` ON sched_subj.subj_id = `subject`.subj_id
                WHERE sched_subj.employee_id = '{$ins_id}'
                AND sched_subj.sem = '{$sem}'
                AND sched_subj.sy = '{$sy}'
                ");
            $result = $sched->result();

        $sub = array();
        $unit = 0;

        foreach ($result as $key => $value) {
            $unit += $value->lab_unit + $value->lec_unit;
            $sub[] = array(
                "bs_id"=>$value->bs_id,
                "subj_id"=>$value->subj_id,
                "title" => "{$value->subj_code} - {$value->room_code}",
                "start" => date("Y-m-d {$value->time_start}", strtotime("{$value->composition} this week")),
                "end" => date("Y-m-d {$value->time_end}", strtotime("{$value->composition} this week")),
                "allDay" => false,
                "color" => "rgb(0, 133, 178)",
                "textColor" => "#FFF",
                "time_start" => date_format(date_create($value->time_start), 'h:i A'),
                "time_end" => date_format(date_create($value->time_end), 'h:i A'),
                "composition" => $value->composition,
                "subject" => $value->subj_name,
                "room" => $value->room_code,
                'unit'=>$unit
            );
        }
        echo json_encode($sub);
    }
	public function getAllSubjects()
	{
        $list = $this->sched_subj->get_list($this->input->post('sy'),$this->input->post('sem'));
        $data = [];
        foreach ($list as $key => $value) {

           if($value->employee_id != ""){
                $value->status = "<i class='fa fa-square fa-2x pull-right'></i><input type='hidden' value='{$value->ss_id}' ss-id >";
           }
           else{
                $value->status = "<i class='fa fa-square-o fa-2x pull-right'></i><input type='hidden' value='{$value->ss_id}' ss-id >";
           }
           $data['data'][] = [$value->subj_code,$value->subj_name,$value->lec_unit,$value->status];
        }
        echo json_encode($data);
    }
    public function undo_schedule() 
    {
        $schedule = $this->session->userdata('schedule');
        $bs_id = $schedule['bs_id'];


        $result = $this->block_section_subjects->where(['bs_id'=>$bs_id,'status' => 0])->get_all();
        
        if (!empty($results)) {
            $this->block_section->delete($bs_id);
        }
    }
    public function get_block_subject() 
    {

        $type = $this->input->get('type');
        $schedule = $this->session->userdata('schedule');

        $bss = new Block_section_subjects();
        $subjects = $bss->get_subject(array('type' => $type, 'bs_id' => $schedule['bs_id']));
        $data = array();

          if (!empty($subjects)) {
              foreach ($subjects as $subject) {
            $data[] = array(
              'subj_id' => $subject->subj_id,
              'name' => strtoupper($subject->subj_name),
              'code' => strtoupper($subject->subj_code),
              'bs_id' => $subject->bs_id,
                'section'=>$subject->sec_code,
                'semester'=>$subject->semister,
                'year_level'=>$subject->year_lvl,
                'sy'=>$subject->sy,
            );
          }
        }

        echo json_encode(array('data' => $data));
    }
    public function viewSectionSchedule() 
    {
          $data = [];
          $subj_id = null;
          $schedules = $this->subj_sched_day->getScheduleWithSubjectSched($this->input->get('bs_id'));


          foreach ( $this->block_section->program( $this->input->get('bs_id') ) as $section ) {
              $section_details = [
                  "sy" => $section->sy,
                  "major" => $section->major,
                  "year" => $section->year_lvl,
                  "section" => $section->sec_code,
                  "semister" => $section->semister,
                  "prog_name" => $section->prog_name
              ];
           }

        // var_export($this->block_section->program( $this->input->get('bs_id') ));
        // END SECTION DETAILS //

        foreach ($schedules as $schedule) {
         
          if ($subj_id != $schedule->subj_id) {
            $color = $this->random_color();
          }
          $subj_id = $schedule->subj_id;

          $composition = $this->getDayDetails("sd_id", $schedule->sd_id, "composition");
          $room_code = $this->getRoomDetails("rl_id", $schedule->rl_id, "room_code");
          $room_type = $this->getRoomDetails("rl_id", $schedule->rl_id, "type");

          $data[] = array(
            "ss_id" => $schedule->ss_id,
            "id" => $room_code . $schedule->ss_id,
            "composition" => $composition,
            "key" => $room_code . $schedule->ss_id,
            "year_lvl" => $schedule->year_lvl,
            "sy" => $schedule->sy,
            "sem" => $schedule->sem,
            "subj_id" => $schedule->subj_id,
            "sd_id" => $schedule->sd_id,
            "rl_id" => $schedule->rl_id,
            "time_start" => $schedule->time_start,
            "time_end" => $schedule->time_end,
            "room" => $room_code,
            "title" => $this->getSubjCode($schedule->subj_id) . " - " . substr($room_type, 0, 3),
            "start" => date("Y-m-d {$schedule->time_start}", strtotime("{$composition} this week")),
            "end" => date("Y-m-d {$schedule->time_end}", strtotime("{$composition} this week")),
            "allDay" => false,
            "color" => $color,
            "textColor" => "#FFF",
            "type" => $room_type,
            "bs_id" => $schedule->bs_id
          );
        }
         echo json_encode(array($data, $section_details));
    }
    
    private function getDayDetails($field, $par, $return) 
    {
       
        $result = $this->sched_day->where($field,$par)->get_all();
        foreach ($result as $key => $value) {
          return $value[$return];
        }
    }
    public function removeSubjectFromInstructor(){
        $subj_id    = $this->input->get("subj_id");
        $bs_id      = $this->input->get("bs_id");

        $data = $this->sched_subj->where(['subj_id' => $subj_id,
                        'bs_id' => $bs_id])->get();
        $ss_id = $data['ss_id'];
        unset($data['ss_id']);
		unset($data['created_at']);
		
        $data['employee_id'] = "";
        $this->sched_subj->update($data,$ss_id);
     
        echo json_encode(array("result"=>true,'sched' => $ss_id));
    }
    private function getRoomDetails($search, $param, $return) 
    {
        $list = $this->room_list->where($search, $param)->get_all();
        if (!empty($list)) {
          foreach ($list as $key => $value) {
            return $value[$return];
          }
        }
    }
    private function getSubjCode($subj_id) 
    {
        $list = $this->subjects->where('subj_id',$subj_id)->get_all();
        // var_dump($list);
        // return $list[$subj_id]->subj_name;

        foreach ($list as  $value) {
            if($value['subj_id'] === $subj_id){
                return $value['subj_name'];
            }
        }

    }
    private function random_color() 
    {
        return '#' . $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }

    private function random_color_part() 
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }
}
