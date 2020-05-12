<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Course_sched extends CURRV_Controller {

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
	    $this->load->model('subjects');
	    $this->load->model('sched_time');
	    $this->load->model('program_list');
	    $this->load->model('room_list');
	    $this->load->model('plotted_time');
	    $this->load->model('plotted_schedule');
	    $this->load->model('sched_day');
	    $this->load->model('block_section');
	    $this->load->model('subj_sched_day');
	    $this->load->model('curr_codelist');
	    $this->load->model('sched_subj');
	    $this->load->model('block_section_subject');

	}

	public function index()
	{

		$data  = array('title'   => 'Course Schedule',
					   'pageID' => 'course_sched',
				   	   'heading' => 'Course Schedule',
				   	   'message' => '');
		$data['userInfo'] = (array) $this->session->userdata('userSessionLogin');
		$data['time'] = (object)  $this->sched_time->loadLastInput();
        if(!$data['time']){
        	$data['plotted'] = $this->plotted_time->where(["st_id"=>$data['time']->st_id])->get_all();
        }
     	$data['section_school_year'] = $this->block_section->schoolYear();
     	$data['sched_day'] =  $this->sched_day->fetchData();

   		$data['subjectList'] =  $this->block_section_subject->get_all_subjects_edit($data['section_school_year']['sy'], $data['section_school_year']['semister']);	

		$this->load->view('includes/header',$data);
		$this->load->view('includes/menu',$data);
		$this->load->view('course/schedule/index',$data);
		$this->load->view('includes/footer');
		$this->load->view('course/schedule/js');

	}
	public function subjectSchedule()
	{
	          $request = $this->input->get('data');
	          $data = [];
	          $sched = new Sched_subj();
	          $results = $this->sched_subj->schedule([
	              'sy' => $request['sy'],
	              'semester' => $request['semester'],
	//              'year_level' => $request['year_level'],
	              'subj_id' => $request['subj_id'],
	              'subject_name' => $request['subject_name'],
	          ]);
	          if (!empty($results)) {
	              foreach ($results as $result) {
	                  $data[] = [
	                      'section'=>strtoupper($result->sec_code).'<span class="hide">'.$result->ss_id.'</span>',
	                      'subject'=>ucwords($result->subj_name),
	                      'room'=>ucwords($result->room_code),
	                      'day'=>strtoupper($result->day)
	                      .' '.date('h:i', strtotime($result->time_start))
	                      .' - '.date('h:i A', strtotime($result->time_end)),
	                      'ss_id'=>$result->ss_id
	                  ];
	              }
	          }
	          echo json_encode(['data'=>$data]);
	}

	public function get_block_subject() 
	{	
		$type = $this->input->get('type');
		$schedule = $this->session->userdata('schedule');
		$data = array();
		if($this->input->get('modalType') == 'edit'){

		     $subjects = $this->block_section_subject->get_all_subjects(array('type' => $type, 'sy'=> $this->input->get('sy'), 'semester' => $this->input->get('semester'), 'section_code' => $this->input->get('section_code')));
		}else{	
			 $subjects = $this->block_section_subject->get_subject(array('type' => $type, 'bs_id' => $schedule['bs_id']));
			  
		}
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
	public function loadEditRenderingEvents() 
	{

	    $bs_id = $this->input->get("bs_id");
	    $sched = $this->sched_subj->getRoomSchedSubjByBlockSection($bs_id);
	    var_export($sched);
	    // $sched = new Sched_subj;
	    // $sched->toJoin = array("Room_list" => "Sched_subj", "Sched_day" => "Sched_subj", "Subject" => "Sched_subj");
	    // $sched->db->where("bs_id != {$bs_id}");
	    // $list = $sched->get();

	    $rendered = array();
	    foreach ($list as $key => $value) {
	      $rendered[] = array(
	        "room" => $value->room_code,
	        "start" => date("Y-m-d {$value->time_start}", strtotime("{$value->composition} this week")),
	        "end" => date("Y-m-d {$value->time_end}", strtotime("{$value->composition} this week")),
	        "rendering" => 'background',
	        "backgroundColor" => '#6ba5c1'//Blue
	      );
	    }
	    echo json_encode($rendered);
  	}
	public function get_schedule_time() 
	{

	    $data = array();

	    if ($this->session->has_userdata('schedule_time')) {

	      //plotted_time is a helper function
	      $time = plotted_time();
	      $start = $time['start'];
	      $end = $time['end'];

	      $request = $this->input->get('time');
	      if (!empty($request)) {
	        $start = date('H:i', strtotime($request));
	      }

	      $times = $this->plotted_time->get_by_sched(array($start, $end));
	      if (!empty($times)) {
	        foreach ($times as $k => $time) {
	           
	           	$data[] = array(
	            	'time' => date('h:i a', strtotime($time['time']))
	          	);
	        }
	      }

	    }else{
	    	 $times = $this->plotted_time->get_by_sched(array('7:30', '21:30'));
		     if (!empty($times)) {
		        foreach ($times as $k => $time) {
		           
		           	$data[] = array(
		            	'time' => date('h:i a', strtotime($time['time']))
		          	);
		        }
		     }
	    }
	    echo json_encode($data);
	}
	public function undo_schedule() 
	{
	    $schedule = $this->session->userdata('schedule');
	    $bs_id = $schedule['bs_id'];
	    unset($_SESSION['schedule']);
	    $results = $this->block_section_subject->where(array('bs_id' => $bs_id, 'status' => 0))->get_all();

	    if (!empty($results)) {
	    	$this->block_section->delete($bs_id);
	    }

	}
	public function deleteSchedSubject()
	{
		$ss_id = $this->input->get('ss_id');
		$this->sched_subj->delete($ss_id);
		if($this->db->affected_rows() > 0 ){
        	echo json_encode(['result' => true]);
        }else{
        	echo json_encode(['result' => false]);
        }
	}
    public function cancelScheduling(){
        $bs_id = $this->input->get("bs_id");
        $this->db->query("DELETE from sched_subj WHERE ss_id IN (SELECT ss_id FROM block_section_sched where bs_id = {$bs_id})");
        $this->block_section->delete($bs_id);                                                              
        if($this->db->affected_rows() > 0 ){
        	echo json_encode(1);
        }else{
        	echo json_encode(0);
        }
    }
	public function getSectionSchedules()
	{
        $bs_id = $this->input->get("bs_id");
        
        $sec_sched->toJoin = ["Sched_subj"=>"Block_section_sched","Subject"=>"Sched_subj","Sched_day"=>"Sched_subj","Room_list"=>"Sched_subj"];
        $sched = $this->block_section_sched->where("block_section_sched.bs_id",$bs_id)->get_all();

        $list = [];
        if(!empty($sched)){

            $type = ["lec"=>"Lecture","lab"=>"Laboratory"];

            foreach ($sched as $key => $value) {

                if(!array_key_exists($value->subj_id.$value->sched_type, $list)){
                    $list[$value->subj_id.$value->sched_type][] = array(
                                $value->subj_code,
                                $value->subj_name,
                                $value->composition,
                                date("h:i A", strtotime($value->time_start))."-".date("h:i A", strtotime($value->time_end)),
                                $value->room_code,
                                $type[$value->sched_type],
                                "<button><i class='fa fa-times'></i></button>"
                                );
                }
                else{
                    $list[$value->subj_id.$value->sched_type][] = array(
                                "",
                                "",
                                $value->composition,
                                date("h:i A", strtotime($value->time_start))."-".date("h:i A", strtotime($value->time_end)),
                                $value->room_code,
                                $type[$value->sched_type],
                                ""
                                );
                }
                
            }
            $newArr = [];
            foreach ($list as $key => $value) {
                $newArr[] = $value;
            }
        } 
        echo json_encode(["data"=>$list]);
    }
	public function getMoveRooms() 
	{

	    $type = $this->input->get("type");
	    $except = $this->input->get("except");

	  
	    $query = $this->db->query("SELECT * FROM room_list where type = '{$type}' AND rl_id != '{$except}'");
	    $list = $query->result();

	    if (!empty($list)) {
	      echo json_encode($list);
	    }
	}

	 public function edit_block_section()
	 {
	 		
	  	$data = ['status' => '1', 'data'=> (object) $this->block_section->where('bs_id',$this->input->get('bs_id'))->get_all()];
	    echo(json_encode($data));
	  	
	 }
	 public function getSubjectEditList()
	 {
	 	var_export($this->input->get());
	 }
	 public function delete_blockSection()
	 {

	 	$this->block_section->delete($this->input->get('bs_id'));
	    if($this->db->affected_rows() > 0 ){
        	echo 1;
        }else{
        	echo 0;
        }

	 }
	 public function create_schedule() 
	 {

	    $this->remove_unsuccessful_schedule();
	    $subjects = $data = $data_array = $sched = array();
	    $type = $this->input->get('type');
	    $request = $this->input->get('sched');

	    $scheduleData = array(
	    	'schedule_time' => $request['schedule']
	    );
	    $this->session->set_userdata($scheduleData);
	    $time = plotted_time();
	    if ($type == "curriculum") {
	      $data_array = array('program_id' => $request['program'], 'year_level' => $request['curryearlvl'], 'revision_no' => $request['revision_no'], 'semester' => $request['currsemister'], 'sy' => $request['currsy']);
	      $subjects = $this->getCurriculumSubject($data_array);
	    } else if ($type == 'subject') {
	      $subjects = $request['subjects'];
	    }

	    $this->db->trans_begin();

	    if (!empty($subjects)) {
	      $bs_id  = '';
	      $sectionCodeList =  $this->block_section->where('sec_code',$request['section_code'])->get_all();
	      // if(!empty($sectionCodeList)){
	      // 	  $bs_id = $sectionCodeList['bs_id'];
	      // }else{
	      	 $dataBlockSection = array(
		      	'sec_code' => $request['section_code'],
		      	'activation' => 'active',
		      	'year_lvl' => $request['year'],
		      	'semister' => $request['semister'],
		      	'sy' => $request['sy'],
		      	'pl_id' => $type == 'curriculum' ? $request['program'] : 0,

		      );

		      $this->block_section->insert($dataBlockSection);
		      $bs_id = $this->db->insert_id();
	      // }

	     

	     
	      $this->session->set_userdata(
	        array(
	          'schedule' => array(
	            'bs_id' => $bs_id,
	            'sy' => $request['sy'],
	            'semester' => $request['semister'],
	            'year' => $request['year'],
	            'code' => $request['section_code'],
	            'program_name' => $p = isset($request['prog_name']) ? $request['prog_name'] : '',
	            // 'major' => $request['major']
	          )
	        )
	      );

	      $subject_data = array();

	      foreach ($subjects as $subject) {

	        $block_section_subject_data = array('bs_id' => $bs_id, 'subj_id' => $subject['subj_id'], 'type' => 'lec');
	        $sched_subj_data = array('year_level' => $request['year'], 'sy' => $request['sy'], 'sem' => $request['semister'], 'subj_id' => $subject['subj_id'], 'bs_id' => $bs_id, 'type' => 'lec');


	        $this->save_block_section_subject($block_section_subject_data);
	        // $this->save_sched_subj($sched_subj_data);

	        $lab_unit = $subject['lab_unit'];
	        if ($lab_unit > 0 && $subject['lec_unit'] != 0 ) {

	          $block_section_subject_data['type'] = 'lab';
	          $sched_subj_data['type'] = 'lab';
	          // $this->save_sched_subj($sched_subj_data);
	          $this->save_block_section_subject($block_section_subject_data);

	        }
	      }

	    }else{
	    	 $dataBlockSection = array(
		      	'sec_code' => $request['section_code'],
		      	'activation' => 'active',
		      	'year_lvl' => $request['year'],
		      	'semister' => $request['semister'],
		      	'sy' => $request['sy'],
		      	'pl_id' => $type == 'curriculum' ? $request['program'] : 0,

		      );

		      $this->block_section->insert($dataBlockSection);

		      $bs_id = $this->db->insert_id();


		       $this->session->set_userdata(
		        array(
		          'schedule' => array(
		            'bs_id' => $bs_id,
		            'sy' => $request['sy'],
		            'semester' => $request['semister'],
		            'year' => $request['year'],
		            'code' => $request['section_code'],
		            'program_name' => $p = isset($request['prog_name']) ? $request['prog_name'] : '',
		            'major' => $request['major']
		          ),
		          'bs_id' => $bs_id,
		        )
		      );
	    }

	    if ($this->db->trans_status() === FALSE) {
	      $this->db->trans_rollback();
	      echo false;
	    } else {

	      $this->db->trans_commit();
	      echo true;
	    }
	}
	protected function get_room_id($code = false) 
	{
	    $room_id = '';
	    $room_result = $this->room_list->where('room_code',$code)->get_all();
	    if (!empty($room_result)) {
	      foreach ($room_result as $room) {
	        $room_id = $room['rl_id'];
	      }
	    }
	    return $room_id;
	}
	protected function get_subject_hour($data = [])
	{

	    // $s = new Subject();
	    // $result = array();

	    if ($data['type'] == 'lab') {

	      $subject = $this->subjects->where('subj_id',$data['subj_id'])->get_all();
	      foreach ($subject as $temp) {
	        $result = array('hour' => $temp['lab_hour'], 'type' => 'lab');
	      }
	    } else {
	      $subject = $this->subjects->where('subj_id',$data['subj_id'])->get_all();
	      foreach ($subject as $temp) {
	        $result = array('hour' => $temp['lec_hour'], 'type' => 'lec');
	      }

	    }

	    return $result;
	}
	protected function get_time_end($data = [])
	{
	    $minutes = $data['hour'] * 60;
	    $split = count($data['day']);
	    $hour = $minutes / $split;
	    $hour = date('G:i', mktime(0, $hour));
	    $hour = explode(':', $hour);
	    $time_end = $hour[1] == 0 ? date('H:i', strtotime('+' . $hour[0] . 'hour', strtotime($data['start']))) : date('H:i', strtotime('+' . $hour[0] . 'hour +' . $hour[1] . ' minutes', strtotime($data['start'])));
	    return $time_end;
	 }

	public function save_schedule()
	{
	    if ($this->input->method() == 'get' && array_key_exists('event', $_GET)) {
	    	
	        $event = $this->input->get('event');
	        $user = (array) $this->session->userdata('userSessionLogin');
	        $start = date("Y-m-d H:i:s", strtotime($event['start']));
	        $room_id = $this->get_room_id($event['room']);
	        $bb_sid =  $this->session->userdata('bs_id');
	        $subject_hour = $this->get_subject_hour(['type' => $event['type'], 'subj_id' => $event['sub_id']]);
	        $schedule = $this->session->userdata('schedule');
	        // $time_end = $this->get_time_end(['hour' => $subject_hour['hour'], 'day' => $event['selected_days'], 'start' => $start]);
	        $time_end = date("Y-m-d H:i:s", strtotime($event['end']));
	        $start = date("H:i", strtotime($event['start']));
	        $this->db->trans_begin();
	        //find the subject id  and block section id on sched_subj table
	      $schedSubj = $this->sched_subj->where(['subj_id'=>$event['sub_id'], 'bs_id'=>$bb_sid])->get();

	      //if subject id and block section id not exist save to sched_subj
	      //otherwise disregard.
	      $ss_id = null;
	      if(empty($schedSubj)){
	      	  $schedSubjData = array(
	      	  	  'year_lvl' => $schedule['year'],
	              'sy' => $schedule['sy'],
	              'subj_id' => $event['sub_id'],
	              'sem' => $schedule['semester'],
	              'avs_status' => 'active',
	              'bs_id' => $schedule['bs_id']
	      	  );
	      	var_export($event);
	      	$this->sched_subj->insert($schedSubjData);
	        $ss_id = $this->db->insert_id();
	      }
	      else{
	          $ss_id = $schedSubj['ss_id'];
	      }	

	      foreach ($event['selected_days'] as $day_id) {
	        $data = array('time_start' => $start, 'time_end' => $time_end, 'room' => $room_id, 'day' => $day_id, 'sem'=>$schedule['semester']);
	        // if (empty($this->getTimeVacant($data))) {
	        	$ssData = array(
	        		'time_start'=>$start,
	        		'time_end'=>$time_end,
	        		'sd_id'=>$day_id,
	        		'ss_id'=>$ss_id,
	        		'type'=>$event['type'],
	        		'rl_id'=>$room_id,
	        		'user_id'=>$user['user_id']
	        );
	          $this->subj_sched_day->insert($ssData);
	          $blockSectionSubject = $this->block_section_subject->where(['bs_id' => $bb_sid, 'type' => $event['type'], 'subj_id' => $event['sub_id']])->get();
	          if (!empty($blockSectionSubject)) {
	          	$this->block_section_subject->update(array('status'=>1),$blockSectionSubject['bss_id']);
	          }
	      // }
	      }

	      if ($this->db->trans_status() === FALSE) {
	        // $this->db->trans_rollback();
	        $this->db->trans_rollback();
	      } else {
	        // $this->db->trans_commit();
	        $this->db->trans_commit();
	        echo true;
	      }

	    }
	 }
	 public function check_plotted()
	 {

	        $bs_id = $this->input->get('bs_id');

	        $subjects = $this->block_section_subject->check_plotted($bs_id);
	        $result = '';


	        $data =array();
	        if(!empty($subjects)){
	            
	            foreach ($subjects as $subject) {

	                if($subject['status'] == 0){
	                    $result = $subject['status'];
	                    break;
	                }
	                else{
	                    $result = $subject['status'];
	                }
	            }


	        }
	        echo $result;     
	 }
	protected function save_block_section_subject($data = [])
	{


		$dataBlockSectionSubject  = array(
			'bs_id'=> $data['bs_id'],
			'subj_id'=> $data['subj_id'],
			'type'=> $data['type'],
			'status'=> 0
		);
		$this->block_section_subject->insert($dataBlockSectionSubject);
	    // $block_section_subject = new Block_section_subjects();
	    // $block_section_subject->bs_id = $data['bs_id'];
	    // $block_section_subject->subj_id = $data['subj_id'];
	    // $block_section_subject->type = $data['type'];
	    // $block_section_subject->status = 0;
	    // $block_section_subject->save();
	}
	protected function remove_unsuccessful_schedule() 
	{
	    if (array_key_exists('schedule', $_SESSION)) {

	      $schedule = $this->session->userdata('schedule');
	      $bs_id = $schedule['bs_id'];

	      $result = $this->sched_subj->where(array('bs_id' => $bs_id))->get_all();

	      if (empty($result)) {
	        $result = $this->block_section->where('bs_id',$bs_id)->get_all();
	        if (!empty($result)) {
	           $this->block_section->delete($bs_id);
	        }


	      }

	    }
	}
	 public function subject_scheduling(){

    	$type = $this->input->get('type');
        $data = $this->input->get('sched');
    	// $type = "curriculum";
    	// $data = ['program'=>2,"curryearlvl"=>"1st", "currsemister"=>"1st Semester", "currsy"=>"2016-2017"];
        $bs_id = 0;

        $this->db->trans_begin();

        $secExists = $this->isSectionExist($data);

        if(!$secExists){

        	$dataBS = array(
        		'sec_code' => $data['section'],
        		'activation' => 'active',
        		'year_lvl' => $data['year'],
        		'semister' => $data['semister'],
        		'sy' => $data['sy'],
        		'pl_id' => $data['program']
        	);


        	$this->block_section->insert($dataBS);
        	
            // $bs = new Block_section;

            // $bs->sec_code   = $data["section"];
            // $bs->activation = "active";
            // $bs->year_lvl   = $data["year"];
            // $bs->semister   = $data["semister"];
            // $bs->sy         = $data["sy"];
            // $bs->pl_id      = $data['program'];
            // $bs->save();

            $bs_id = $bs->db->insert_id();
        }

        if($type == "curriculum"){
            $subjects = $this->getCurriculumSubject($data['program'], $data['curryearlvl'], $data['currsemister'], $data['currsy']);
        }
        elseif($type == "subject"){
            $subjects = $data['subjects'];
        }

        $lectSubj = [];
        $labSubj  = [];
        $bss = [];

        if(!empty($subjects)){
        	foreach ($subjects as $key => $value) {
        		if($value["lec_unit"] != "" || $value["lec_unit"] != 0){
                    $bss[] = array(
                                "bs_id"          => $bs_id,
                                "subj_id"        => $value["subj_id"],
                                "type"           => "lec",
                                "remaining_hour" => $value["lec_hour"]
                                );
        		}
        		if($value["lab_unit"] != "" || $value["lab_unit"] != 0){
                    $bss[] = array(
                                "bs_id"          => $bs_id,
                                "subj_id"        => $value["subj_id"],
                                "type"           => "lab",
                                "remaining_hour" => $value["lab_hour"]
                                );

				}		
        	}

            if(!$secExists){
                $this->db->insert_batch("block_section_subjects", $bss);
            }
        }

        if($this->db->trans_status() == true){
             echo json_encode(["result"=>true,"lec"=>$this->getSectionSubjects($bs_id, "lec"), "lab"=>$this->getSectionSubjects($bs_id, "lab"), "bs_id"=>$bs_id, "isExist"=>$secExists]);
            $this->db->trans_commit();
        }
        else{
            echo json_encode(["result"=>false]);
            $this->db->trans_rollback();
        }
       
    }
	public function generateProgramList()
	{
		$data['userInfo'] = (array) $this->session->userdata('userSessionLogin');
		echo json_encode($this->program_list->where('dep_id',$data['userInfo']['dep_id'])->get_all());
	}
	public function generateSubjectList()
	{	
		echo json_encode($this->subjects->fetchData());
	}
	public function get_plotted_room() 
	{
	    $user = (array) $this->session->userdata('userSessionLogin');
	    $request = $this->input->get();
	    // $request['sy'] = '2019-2020';
	    $days = array("Monday" => 1, "Tuesday"=>2, "Wednesday"=>3, "Thursday"=>4, "Friday"=>5, "Saturday"=>6, "Sunday"=>7);
	    $results = $this->subj_sched_day->get_schedule($request['room_code'],$request['sy'], $request['semester']);
	    $data = array();
	    if (!empty($results)) {
	      foreach ($results as $result) {
	        $data[] = array(
	          'title' => ucwords($result->subj_name),
	          'start' => date('H:i', strtotime($result->time_start)),
	          'end' => date('H:i', strtotime($result->time_end)),
	          'dow' => array($days[$result->composition]),
	          'backgroundColor' => $color = $result->user_id == $user['user_id'] ?  '#6ba5c1' : '',
	          'textColor' => $color = $result->user_id == $user['user_id'] ?  '#000' : '#eee',
	          'ss_id'=>$result->ss_id,
	          'type'=>$result->type,
	          'rl_id'=>$result->rl_id,
	        );

	      }
	    }
	    echo json_encode($data);
	 }
	public function get_room()
	{
	    $type = trim($this->input->get('type'));
	    // $rooms = $this->room_list->where('type',$type)->get_all();
	    $rooms = $this->room_list->where('type',$type)->get_all();
	    $data = array();

	    if (!empty($rooms)) {
	      foreach ($rooms as $room) {
	        $data[] = array(
	          'room_code' => strtoupper($room['room_code']),
	          'room' => ucwords(strtolower($room['room_name']))
	        );
	      }
	    }

	    echo json_encode($data);
	}
	public function loadPlottedEvents() 
	{
	    $user_data = (array) $this->session->userdata('userSessionLogin');
	    $list = $this->plotted_schedule->get_by_user($user_data['user_id']);
	    $rendered = array();

	    foreach ($list as $key => $value) {
	      $rendered[] = array(
	        "room" => $value->room_code,
	        "start" => date("Y-m-d {$value->time_start}", strtotime("{$value->composition} this week")),
	        "end" => date("Y-m-d {$value->time_end}", strtotime("{$value->composition} this week")),
	        "rendering" => 'background',
	        "backgroundColor" => "#6ba5c1" //Blue
	      );
	    }
	    echo json_encode($rendered);
	}
	public function get_revision() 
	{
	    $pl_id = trim($this->input->get('pl_id'));

	    $result = $this->curr_codelist->get_curriculum($pl_id);
	    $data = array();

	    if (!empty($result)) {
	      foreach ($result as $temp) {
	        $data[] = array(
	          'sy' => $temp['eff_sy'],
	          'revision_no' => $temp['revision_no']
	        );
	      }
	    }
	    echo json_encode($data);
	}
	public function getRooms($type) 
	{
	    $list = $this->room_list->where('type',$type)->get_all();
	    if (!empty($list)) {
	      return $list;
	    }
	}
	public function getRoom()
    {
      $data = [];
      $type = $this->input->get('type');
      $rooms = $this->getRooms($type);
      if (!empty($rooms)){
          foreach ($rooms as $room){
              $html = ' <div id="container_'.$room['room_code'].'" class="panel panel-info ">';
              $html .= ' <div class="panel-heading clearfix">';
              $html .= '<span class="panel-title">'.strtoupper($room['room_code']).'</span>';
              $html .= '<small class="pull-right">Available Time Percentage: 27%</small>';
              $html .= '</div>';
              $html .= '<div class="panel-body p-t-10 p-l-10 p-r-10 p-b-10">';
              $html .= '<div class="roomCalendar" id="'.strtoupper($room['room_code']).'"></div>';
              $html .= '</div>';
              $html .= '<div class="panel-footer clearfix">';
              $html .= '<small class="pull-right">Total Unit Plotted: <span id="counter_'.$room['room_code'].'">27</span></small>';
              $html .= '</div>';
              $html .= '</div>';
              $data[] = ['code'=>$html];
          }
      }
      echo json_encode($data);
 	}

	public function getSectionList() 
	{
	    $query = $this->db->query("
	                  SELECT block_section.*, sis_main_db.program_list.prog_name,prog_code, sis_main_db.program_list.prog_abv
					  FROM block_section
					  LEFT JOIN sis_main_db.program_list 
	                  ON program_list.pl_id = block_section.pl_id ORDER BY created_at DESC");
	    $list = $query->result();
	    echo json_encode(array('data' => $list));
	}
	public function generateSectionCode($length = 5)
    {
        $alphabets = range('A', 'Z');
        $numbers = range('0', '9');

        $final_array = array_merge($alphabets, $numbers);
        $password = '';

        while ($length--) {
            $key = array_rand($final_array);
            $password .= $final_array[$key];
        }
        echo $password;
    }
    private function getCurriculumSubject($data = [])
	{
	    $year = array("1st" => "First Year", "2nd" => "Second Year", "3rd" => "Third Year", "4th" => "Fourth Year", "5th" => "Fifth Year");
	    $semester = array("1st Semester" => "First Semester", "2nd Semester" => "Second Semester");
	    $array = array();

	    $query = "SELECT * FROM cur_subject
	              INNER JOIN `subject` ON cur_subject.subj_id = `subject`.subj_id
	              INNER JOIN year_sem ON cur_subject.ys_id = year_sem.ys_id
	              INNER JOIN curr_codelist ON year_sem.cur_id = curr_codelist.cur_id
	              WHERE curr_codelist.pl_id = {$data['program_id']}
	              AND curr_codelist.revision_no = {$data['revision_no']}
	              AND curr_codelist.eff_sy = '{$data['sy']}'
	              AND year_sem.semister = '{$semester[$data['semester']]}'
	              AND year_sem.`year` = '{$year[$data['year_level']]}'
	              ";

	    $query = $this->db->query($query);
	    $results = $query->result();

	    if (!empty($results)) {
	      foreach ($results as $result) {
	        $array[] = array(
	          "subj_id" => $result->subj_id,
	          "subj_code" => $result->subj_code,
	          "lec_unit" => $result->lec_unit,
	          "lab_unit" => $result->lab_unit,
	          "subj_name" => $result->subj_name,
	          "lab_hour" => $result->lab_hour,
	          "lec_hour" => $result->lec_hour,
	          "split" => $result->split,
	          "cur_id" => $result->cur_id
	        );
	      }
	    }

	    return $array;
	}
    public function setSchedule() 
	{
		/*get the current user information who doing transactions */
		$user_data = (array) $this->session->userdata('userSessionLogin');
		//container variable: subjects as array , time and sched as array, and  days as array;  
		$subjects = array();
		$sched = array();

		/*container: $time
			contains : 
					"morning" => array("start" => "07:30", "end" => "12:00"),
			      	"afternoon" => array("start" => "12:00", "end" => "18:00"),
			      	"evening" => array("start" => "18:00", "end" => "22:00")
		*/
	    $time = array(
	      "morning" => array("start" => "07:30", "end" => "12:00"),
	      "afternoon" => array("start" => "12:00", "end" => "18:00"),
	      "evening" => array("start" => "18:00", "end" => "22:00")
	    );

	    /*container: $days
			contains : "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"
	    */
	    $days = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");


	    /*type and data came from XHR request*/
	    $type = $this->input->get('type');
	    $data = $this->input->get('sched');

	    /*variable : x equals 0*/
	    $x = 0;
	    


	    //if type is = "curriculum" then .
	    if ($type == "curriculum") {
	      $data_array = array('program_id' => $data['program'], 'year_level' => $data['curryearlvl'], 'semester' => $data['currsemister'], 'sy' => $data['currsy']);
	      $subjects = $this->getCurriculumSubject($data_array);
	     

	    } elseif ($type == "subject") {
	    	
		      if(isset($data['subjects'])){
		     	$subjects = $data['subjects'];
		      }else{
		      	exit(json_encode('no_subjects'));
		      }	
	    }



	    //container startTime
	  	$startTime = $time[$data['schedule']]['start'];


	    foreach ($subjects as $key => $subvalue) {

	    	//background color
	  		// $color = $this->random_color();
	  		$color = '#6ba5c1';
	  		// var_export($color);
	  		if($subvalue['lab_hour'] != 0){
	  		      $rooms = $this->getRooms("Laboratory");

	  		      //interval as subValue (Lab Hour :)
			      $interval = ($subvalue['lab_hour']);
			      $time1 = $this->convertTime($interval);
			      $time1 = explode(":", $time1);
			     
			      for ($z = 0; $z < $subvalue['split']; $z++) {
			         if (strtotime($startTime) > strtotime($time[$data['schedule']]["end"])) {
			            $x++;
			            $startTime = $time[$data['schedule']]["start"];
				     }
				     $time2 = date('H:i', strtotime($startTime . '+' . $time1[0] . ' hour +' . $time1[1] . ' minute'));
				     foreach ($rooms  as  $room) {
				     	 // $isVacantTime = $this->isTimeVacant(
				     	 // 										[ 
				     	 // 									 		"room" 		 => $room['rl_id'],
				     	 // 										    "time_start" => $startTime,
				     	 // 										    "time_end"   => $time2,
				     	 // 										    "day"        => $days[$x],
				     	 // 										    "sem"        => $data['semister']
				     	 // 										]
				     	 // 									);
				     	 // // var_export($isVacantTime);
				     	 // $isNotPlotted = $this->isNotPlotted(
				     	 // 										[
				     	 // 									 		"room" 		 => $room['rl_id'],
				     	 // 										    "time_start" => $startTime,
				     	 // 										    "time_end"   => $time2,
				     	 // 										    "day"        => $days[$x],
				     	 // 										]
				     	 // 									);
				     	 // var_export(['is_vacant_time'=>$isVacantTime,'count'=> $x]);
				     	 // var_export(['is_not_plotted'=>$isNotPlotted,'count'=>$x]);
				     	 // if((!$isVacantTime) && (!$isNotPlotted)){
				     	// var_export($room);
				     	 		$sched[] = array(
							                "ss_id" => "",
							                "id" => $room['room_code'] . $subvalue['subj_id'] . $user_data['user_id'],
							                "composition" => $days[$x],
							                "key" => $room['room_code'] . $subvalue['subj_id'] . $user_data['user_id'],
							                "year_lvl" => $data['year'],
							                "sy" => $data['sy'],
							                "sem" => $data['semister'],
							                "subj_id" => $subvalue['subj_id'],
							                "sd_id" => $this->getDayDetails("composition", $days[$x], "sd_id"),
							                "rl_id" => $room['rl_id'],
							                "time_start" => $startTime,
							                "time_end" => $time2,
							                "room" => $room['room_code'],
							                "title" => $subvalue['subj_name'] . " - Lab",
							                "start" => date("Y-m-d {$startTime}", strtotime("{$days[$x]} this week")),
							                "end" => date("Y-m-d {$time2}", strtotime("{$days[$x]} this week")),
							                "allDay" => false,
							                "color" => "#" . $color,
							                "textColor" => "#000",
							                "type" => "Laboratory",
							                "bs_id" => "");
				     	 		var_export($sched);
							              // break;
				     	 // }else{

				     	 // }

				     }
			      }


	  		}else if ($subvalue['lec_hour']) {
	  			
	  		}


	    //   if ($subvalue['lab_hour'] != 0) {

	  


	    //     for ($z = 0; $z < $subvalue['split']; $z++) {


	    //       
	    //       foreach ($rooms as $room) {

	    //         if (
	    //           !$this->isTimeVacant(
	    //             array(
	    //               "room" => $room['rl_id'],
	    //               "time_start" => $startTime,
	    //               "time_end" => $time2,
	    //               "day" => $days[$x],
	    //               "sem" => $data['semister'])
	    //           )
	    //           &&
	    //           !$this->isNotPlotted(
	    //             array(
	    //               "room" => $room['rl_id'],
	    //               "time_start" => $startTime,
	    //               "time_end" => $time2,
	    //               "day" => $days[$x])
	    //           )
	    //         ) {


	    //           $sched[] = array(
	    //             "ss_id" => "",
	    //             "id" => $room->room_code . $subvalue['subj_id'] . $user_data['user_id'],
	    //             "composition" => $days[$x],
	    //             "key" => $room->room_code . $subvalue['subj_id'] . $user_data['user_id'],
	    //             "year_lvl" => $data['year'],
	    //             "sy" => $data['sy'],
	    //             "sem" => $data['semister'],
	    //             "subj_id" => $subvalue['subj_id'],
	    //             "sd_id" => $this->getDayDetails("composition", $sched_days[$x], "sd_id"),
	    //             "rl_id" => $room->rl_id,
	    //             "time_start" => $startTime,
	    //             "time_end" => $time2,
	    //             "room" => $room->room_code,
	    //             "title" => $subvalue['subj_name'] . " - Lab",
	    //             "start" => date("Y-m-d {$startTime}", strtotime("{$days[$x]} this week")),
	    //             "end" => date("Y-m-d {$time2}", strtotime("{$days[$x]} this week")),
	    //             "allDay" => false,
	    //             "color" => "#" . $color,
	    //             "textColor" => "#000",
	    //             "type" => "Laboratory",
	    //             "bs_id" => ""
	    //           );
	    //           break;
	    //         }
	    //       }
	    //       $startTime = $time2;
	    //     }
	    //   }

	    //   if ($subvalue['lec_hour'] != 0) {

	    //     $rooms = $this->getRooms("Lecture");

	    //     $interval = ($subvalue['lec_hour']);

	    //     $time1 = $this->convertTime($interval);
	    //     $time1 = explode(":", $time1);


	    //     for ($z = 0; $z < $subvalue['split']; $z++) {


	    //       if (strtotime($startTime) > strtotime($time[$data['schedule']]["end"])) {
	    //         $x++;
	    //         $startTime = $time[$data['schedule']]["start"];
	    //       }

	    //       $time2 = date('H:i', strtotime($startTime . '+' . $time1[0] . ' hour +' . $time1[1] . ' minute'));

	    //       foreach ($rooms as $room) {

	    //         if (
	    //           !$this->isTimeVacant(
	    //             array(
	    //               "room" => $room['rl_id'],
	    //               "time_start" => $startTime,
	    //               "time_end" => $time2,
	    //               "day" => $days[$x],
	    //               "sem" => $data['semister'])
	    //        		)
	    //          	 &&
	    //          	!$this->isNotPlotted(
	    //             array(
	    //               "room" => $room['rl_id'],
	    //               "time_start" => $startTime,
	    //               "time_end" => $time2,
	    //               "day" => $days[$x]))
	    //         	){

	    //           $sched[] = array(
	    //             "ss_id" => "",
	    //             "id" => $room['room_code'] . $subvalue['subj_id'] .  $user_data['user_id'],
	    //             "composition" => $days[$x],
	    //             "key" => $room['room_code'] . $subvalue['subj_id'] .  $user_data['user_id'],
	    //             "year_lvl" => $data['year'],
	    //             // "sy" => $data['sy'],
	    //             "sem" => $data['semister'],
	    //             "subj_id" => $subvalue['subj_id'],
	    //             "sd_id" => $this->getDayDetails("composition", $days[$x], "sd_id"),
	    //             "rl_id" => $room['rl_id'],
	    //             "time_start" => $startTime,
	    //             "time_end" => $time2,
	    //             "room" => $room['room_code'],
	    //             "title" => $subvalue['subj_name'] . " - Lec",
	    //             "start" => date("Y-m-d {$startTime}", strtotime("{$days[$x]} this week")),
	    //             "end" => date("Y-m-d {$time2}", strtotime("{$days[$x]} this week")),
	    //             "allDay" => false,
	    //             "color" => "#" . $color,
	    //             "textColor" => "#000",
	    //             "type" => "Laboratory",
	    //             "bs_id" => ""
	    //           );
	    //           break;
	    //         }
	    //       }
	    //       $startTime = $time2;

	    //     }
	    //   }
	    }
	    $this->savePlottedSched($sched);

	    echo json_encode($sched);
	}
	private function savePlottedSched($sched) 
	{
        $user_data = (array) $this->session->userdata('userSessionLogin');
	    $data = array();
	    foreach ($sched as $key => $value) {
	      $data[] = array(
	        "user_id" => $user_data['user_id'],
	        "subj_id" => $value['subj_id'],
	        "sd_id" => $value['sd_id'],
	        "rl_id" => $value['rl_id'],
	        "time_start" => $value['time_start'],
	        "time_end" => $value['time_end'],
	        "key" => $value['key']
	      );
	    }

	    $this->db->insert_batch("plotted_schedule", $data);
	}

	public function checkTimeEachDay($schedDayQueryString = '',$event)
	{

		$queryString = "SELECT * FROM subj_sched_day
	                    INNER JOIN sched_subj ON subj_sched_day.ss_id = sched_subj.ss_id
	                    INNER JOIN sched_day ON subj_sched_day.sd_id = sched_day.sd_id
	                    WHERE subj_sched_day.rl_id  =  {$event['room']}
	                    AND sched_subj.sem = '{$event['sem']} ' AND
	                    ".$schedDayQueryString."
	                    AND (
	                        subj_sched_day.time_start < '{$event['end']}'
	                        AND subj_sched_day.time_end > '{$event['start']}'
	                    )";
	    $query = $this->db->query($queryString);
	    return empty($query->result()) ? true : false;
	}
	public function checkTimeVacant()
	{	
		$isTimeVacant = false;
		$tempSchedDay = '';
		$event = $this->input->get('event');
		// AND sched_day.sd_id = {$this->sched_day->getIDByDay($event['day'])}
		// foreach ($event['selected_days'] as $value) {

			 
		// }
		for ($i=0; $i < count($event['selected_days']) ; $i++) { 
			$tempSchedDay = 'sched_day.sd_id = '.$event['selected_days'][$i].' ';
			$isTimeVacant = $this->checkTimeEachDay($tempSchedDay,$event);
			if($isTimeVacant == false){
				echo true;
			}
		}

		echo $isTimeVacant;
	}
	private function getTimeVacant($data = array()){
		$query = $this->db->query("
						SELECT * FROM subj_sched_day
	                    INNER JOIN sched_subj ON subj_sched_day.ss_id = sched_subj.ss_id
	                    INNER JOIN sched_day ON subj_sched_day.sd_id = sched_day.sd_id
	                    WHERE subj_sched_day.rl_id  =  {$data['room']}
	                    AND sched_subj.sem = '{$data['sem']}'
	                    AND sched_day.sd_id = '{$data['day']}'
	                    AND (
	                        subj_sched_day.time_start < '{$data['time_end']}'
	                        AND subj_sched_day.time_end > '{$data['time_start']}'
	                    )");
		return $query->result();
	}
	private function isNotPlotted($data) {
	    $query = $this->db->query("SELECT
	                                        *
	                                    FROM
	                                        plotted_schedule,
	                                        sched_day
	                                    WHERE (plotted_schedule.time_start < '" . $data['time_end'] . "' AND plotted_schedule.time_end > '" . $data['time_start'] . "')
	                                    AND sched_day.composition = '" . $data['day'] . "'
	                                    AND plotted_schedule.sd_id = sched_day.sd_id");
	   
	    return  $result = !empty($query->result()) ? true : false;
	}
	private function convertTime($dec) 
	{
	    $seconds = ($dec * 3600);
	    $hours = floor($dec);
	    $seconds -= $hours * 3600;
	    $minutes = floor($seconds / 60);
	    $seconds -= $minutes * 60;
	    return $this->lz($hours) . ":" . $this->lz($minutes) . ":" . $this->lz($seconds);
	}
	private function lz($num) 
	{
	    return (strlen($num) < 2) ? "0{$num}" : $num;
	}

    private function getDayDetails($field, $par, $return) 
	{	
	    $result = $this->sched_day->where($field,$par)->get_all();
	   
	    foreach ($result as $key => $value) {
	      return $value[$return];
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
