<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gen_info extends CURRV_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->checkAuthSession();
	    $this->load->model('periodic');
	    $this->load->model('room_list');
	    $this->load->model('sched_day');
	    $this->load->model("program_list");
	    $this->load->model('other_schedule');
	    $this->load->model('subjects');
	    $this->load->model('sched_time');
	    $this->load->model('split_no_per_week');
	    $this->load->model("rate_source");
	    $this->load->model("rate_percentage_subj");
	    $this->load->model("periodic_sem");
	    $this->load->model("instructor");
	    $this->load->model('school_semesters');
	    $this->load->model('plotted_time');
	    $this->load->model('department');
	    $this->load->model('usage_status');

	}
	public function index()
	{
		$data  = array('title'   => 'General Information',
					   'pageID' => 'gen_info',
					   'heading' => 'Gen Information',
					   'message' => '');
		$data['periods'] = $this->periodic->fetchData();
		$data['userInfo'] = (array) $this->session->userdata('userSessionLogin');
		$data['dep'] = $this->department->fetchData();


		$this->load->view('includes/header',$data);
		$this->load->view('includes/menu',$data);
		$this->load->view('gen_info/index');
		$this->load->view('includes/footer');
		$this->load->view('gen_info/js');
	}
	/*module : 
		ROOM LIST : 
			structure : 
					function : roomList() ==> view 
							   roomSave() ==> create
							   roomEdit() ==> edit
							   roomDelete() ==> delete
					end function : 
			end structure
	  end module
	*/
	public function roomList()
	{
		echo json_encode($this->room_list->fetchData());
	}
	public function allSemester()
    {

        $periodicSemesters = $this->periodic_sem->periodicSemeser();
        $data = [];

        if (!empty($periodicSemesters)){
            foreach ($periodicSemesters as $period){
                $data[] = [
                    $period->school_year,
                    ucwords($period->semester),
                    date('M d, Y', strtotime($period->semester_start)),
                    date('M d, Y', strtotime($period->semester_end)),
                    ucwords($period->period),
                    date('M d, Y', strtotime($period->period_start)),
                    date('M d, Y', strtotime($period->period_end)),
                ];
            }
        }

        echo json_encode(['data'=>$data]);
    }
    public function modifyProgram(){
	    $pl_id = $this->input->get("pl_id");

	    $data = $this->program_list->where("pl_id",$pl_id)->get_all();

	    foreach ($data as $key => $value) {

	      $this->prevProg_name = $value['prog_name'];
	      $this->prevProg_abv = $value['prog_abv'];
	      $this->prevProg_code = $value['prog_code'];
	      $this->prevpl_id = $value['pl_id'];

	    }
	    echo json_encode($data);
	}
	public function updateProgram(){

		if(!empty($this->input->post('pl_id'))){
			$pl_id = $this->input->post('pl_id');
			unset($_POST['pl_id']);


			$this->program_list->update($this->input->post(),$pl_id);

			if ($this->db->affected_rows()) {
		      echo json_encode(["result" => true]);
		    } else {
		      echo json_encode(["result" => false]);
		    }
		}


	 

	}
    public function timeSave(){
	    $data = $this->input->post();
	    $result = array();

	    if ($this->input->post('st_id') == "") {
	    	$data['time_start'] = date("h:iA",strtotime($data['time_start']));
	    	$data['time_end'] = date("h:iA",strtotime($data['time_end']));
	    	unset($data['st_id']);
	    	unset($data['unit']);
	     	$this->sched_time->insert($data);
	     	if($this->db->affected_rows()){
	     		$st_id = $this->db->insert_id();
	     		$this->saveInterval( $this->input->post('time_start'),  $this->input->post('time_end'),  $this->input->post('interval'), $this->input->post('unit'), $st_id);
	     		$result['result'] = true;
	     		$result['type'] = 'new';
	     	}
	    } else {
	      $result = $sched_time->update($data,$data['st_id']);
	     	if($this->db->affected_rows()){
	     		$result['result'] = true;
	     		$result['type'] = 'update';
	     	}
	    }
	    echo json_encode(array("result" => $result['result'], "type" => $result['type']));

	}
	public function addDepartment()
	{
	      $data = $this->input->post();
	      if (!empty($data)) {
	        $this->department->insert($this->input->post());

	        if ($this->db->affected_rows()) {
	          echo json_encode(["result" => true, "last" => $this->loadDepartmentLastInput()]);
	        } else {
	          echo json_encode(["result" => false]);
	        }
	      }
	}
	private function loadDepartmentLastInput()
	{
		$list = $this->department->order_by('dep_id','DESC')->fetchData();
	    if (!empty($list)) {
	      foreach ($list as $key => $value) {
	        return [$value['dep_id'], $value['dep_name']];
	      }
	    }
	}
	public function addProgram()
	{
	    
	    $data = $this->input->post();
	    $data['created_at'] = date('Y-m-d H:i:s');
	    $data['updated_at'] = date('Y-m-d H:i:s');

	    
	    $this->program_list->insert($data);
	    $pl_id = $this->db->insert_id();

	    // SAVE PROGRAM STATUS
	 	$this->usage_status->insert(['status'=>'active','pl_id'=>$pl_id]);

	    if ($this->db->affected_rows()) {
	       echo json_encode(["result" => true]);
	    } else {
	       echo json_encode(["result" => false]);
	    }
	}
	public function saveInterval($start, $end, $interval, $unit, $st_id){

	    $startTime = strtotime($start);
	    $endTime = strtotime($end);
	    $interval = $interval;
	    $time = $startTime;

	    while ($time <= $endTime) {
	      $data = array(
	        "time"  => date('H:i', $time),
	        "st_id" => $st_id
	      );

	      $this->plotted_time->insert($data);

	      $time = strtotime('+' . $interval . ' ' . $unit, $time);
	    }
	}
    public function previewInterval(){
	    $startTime = strtotime($this->input->get('start'));
	    $endTime = strtotime($this->input->get('end'));
	    $interval = $this->input->get('interval');
	    $unit = $this->input->get('unit');
	    $time = $startTime;
	    $data = array();

	    while ($time <= $endTime) {
	      $data[] = date('h:i A', $time);
	      $time = strtotime('+' . $interval . ' ' . $unit, $time);
	    }
	    echo json_encode($data);
	}
    public function storePeriod()
    {
        if ( $this->input->server('REQUEST_METHOD') == 'POST'){
            $data = $this->input->post();
            $semesterDate = explode('-',$data['date_semester']);
            $semesterDateStart = date('Y-m-d',strtotime($semesterDate[0]));
            $semesterDateEnd = date('Y-m-d',strtotime($semesterDate[1]));

            $periodDate = explode('-', $data['date_period']);
            $periodDateStart = date('Y-m-d', strtotime($periodDate[0]));
            $periodDateEnd= date('Y-m-d', strtotime($periodDate[1]));

            $school_year = $data['school_year'];
            $semester = $data['semester'];

            $syResult = $this->school_semesters->where(['school_year' => $school_year, 'semester' => $semester])->get_all();

            if ($syResult) {
            	foreach ($syResult as $result){
                    $schoolSemesterId = $result['ss_id'];
                }
            }else{
            	$data = array('school_year'=>$school_year,'semester'=>$semester,'date_start'=>$semesterDateStart,'date_end'=>$semesterDateEnd);
                $this->school_semesters->insert($data);
            	$schoolSemesterId = $this->db->insert_id();
            }
            $data = array('ss_id'=> $schoolSemesterId, 'periodic_id'=> $this->input->post('period_id'), 'date_start' => $periodDateStart, 'date_end'=>$periodDateEnd);
            $this->periodic_sem->insert($data);

        }
    }
   
	public function roomSave()
	{
		if(empty($this->input->post('rl_id'))){
			unset($_POST['rl_id']);
			echo $this->room_list->saveData($this->input->post());
		}else{
			echo $this->room_list->updateData($this->input->post());
		}
		
	}
	public function roomEdit()
	{
		echo json_encode((array) $this->room_list->where($this->input->get('rl_id'))->get());
	}
	public function roomDelete()
	{
		echo json_encode(array("result" => $this->room_list->remove($this->input->get('rl_id'))));
	}

	public function roomOptions($opt='')
	{

		switch ($opt) {
			case 'checkRoomCode':
						echo $this->room_list->checkRoomCode($this->input->post('room_code'),$this->input->post('option'),$this->input->post('oldCode'));
				break;

			default:
				# code...
				break;
		}
		
		
	}

    //----------------------------------------------------------------------------------------------------------------------------------



	/*module : 
		DAY LIST : 
			structure : 
					function : dayList() ==> view 
							   daySave() ==> create
							   dayEdit() ==> edit
							   dayDelete() ==> delete
					end function : 
			end structure
	  end module
	*/
	public function dayList()
	{
		echo json_encode($this->sched_day->fetchData());
	}
	public function daySave()
	{
		$result = array();
		if(empty($this->input->post('sd_id'))){
			unset($_POST['sd_id']);
		}
    	if(empty($this->input->post('sd_id')))
    	{
    		$result = $this->sched_day->saveData($this->input->post());
    	}else{
    		$result = $this->sched_day->updateData($this->input->post());
    	}
    	echo json_encode(array("result" => $result['result'], "type" => $result['type']));
	
	}
	public function dayDelete()
	{
		echo json_encode(array("result" => $this->sched_day->remove($this->input->get('sd_id'))));
	}
	public function dayEdit()
	{
		echo json_encode((array) $this->sched_day->where($this->input->get('sd_id'))->get());
	}


	 //----------------------------------------------------------------------------------------------------------------------------------

	/*module : 
		PROGRAM LIST : 
			structure : 
					function : programList() ==> view 
							   programSave() ==> create
							   programEdit() ==> edit
							   programDelete() ==> delete
					end function : 
			end structure
	  end module
	*/
	public function programList()
	{	
		$data = $this->program_list->fetchData();

		foreach ($data as $key => $value) {
			$usage_status = $this->usage_status->where('pl_id', $value['pl_id'])->get();
			$data[$key]['status'] = $usage_status['status'];
		}
	    echo json_encode($data);
	}

    public function getProgramListBy()
	{	
		$data = $this->program_list->fetchDataBy($level);

		foreach ($data as $key => $value) {
			$usage_status = $this->usage_status->where('pl_id', $value['pl_id'])->get();
			$data[$key]['status'] = $usage_status['status'];
		}
	    echo json_encode($data);
	}


	//----------------------------------------------------------------------------------------------------------------------------------

	/*module : 
		OTHER LIST : 
			structure : 
					function : otherList() ==> view 
							   otherSave() ==> create
							   otherEdit() ==> edit
							   otherDelete() ==> delete
					end function : 
			end structure
	  end module
	*/
	public function otherList()
	{
		echo json_encode($this->other_schedule->fetchData());
	}
	public function otherEdit()
	{
	    echo json_encode($this->other_schedule->where('os_id',$this->input->get('os_id'))->get_all());
	}
	public function otherSave()
	{
		$result = array();
	    $data = $this->input->post();
	    $result = "";

	    if ($this->input->post('os_id') == "") {
	      unset($data['os_id']);
	      $result = $this->other_schedule->insert($data);
	      if($this->db->affected_rows() > 0){
             $result =  array("result"=>true, "type"=>"new");
          }else{
             $result =  false;
          }
	    } else {
    	  $os_id = $data['os_id'];
    	  unset($data['os_id']);
	      $result = $this->other_schedule->update($data,$os_id);
	      if($this->db->affected_rows() > 0){
             $result =  array("result"=>true, "type"=>"update");
          }else{
             $result =  false;
          }
	    }
	    echo json_encode($result);
	}
	public function otherDelete()
	{
		$result = array();
	    $this->other_schedule->delete($this->input->get('os_id'));
	    if($this->db->affected_rows() > 0){
            $result = array("result" => true);
        }
        else{
            $result = array("result" => false);
        }
	    echo json_encode($result);
	}



	 //----------------------------------------------------------------------------------------------------------------------------------


	/*module : 
		INSTRUCTION LIST : 
			structure : 
					function : instructionList() ==> view 
							   instructionSave() ==> create
							   instructionEdit() ==> edit
							   instructionDelete() ==> delete
					end function : 
			end structure
	  end module
	*/
	public function instructorList()
	{
		echo json_encode($this->instructor->get_instructor());
	}
	public function get_instuctor_sched()
	{
	    $sem = $this->input->get('sem');

	    $sy = $this->input->get('sy');

	    $ins_id = $this->input->get('ins_id');


	    $semister = array("1st Semester" => "First Semester", "2nd Semester" => "Second Semester");

	    $sched = $this->db->query("
	        SELECT 
	        * 
	        FROM subj_sched_day
	        INNER JOIN sched_subj ON subj_sched_day.ss_id = sched_subj.ss_id
	        INNER JOIN room_list ON subj_sched_day.rl_id = room_list.rl_id
	        INNER JOIN sched_day ON subj_sched_day.sd_id = sched_day.sd_id
	        INNER JOIN `subject` ON sched_subj.subj_id = `subject`.subj_id
	        WHERE sched_subj.employee_id = '{$ins_id}'
	        AND sched_subj.sem = '{$semister[$sem]}'
	        AND sched_subj.sy = '{$sy}'
	      ");

	    $list = $sched->result();

	    $data = array();

	    if(!empty($list)) {
	      foreach ($list as $key => $value) {
	        $data[] = array(
	          "title"       => "{$value->subj_code}",
	          "start"       => date("Y-m-d {$value->time_start}", strtotime("{$value->composition} this week")),
	          "end"         => date("Y-m-d {$value->time_end}", strtotime("{$value->composition} this week")),
	          "allDay"      => false,
	          "color"       => "rgb(0, 133, 178)",
	          "textColor"   => "#FFF",
	          "time_start"  => date_format(date_create($value->time_start), 'h:i A'),
	          "time_end"    => date_format(date_create($value->time_end), 'h:i A'),
	          "composition" => $value->composition,
	          "subject"     => $value->subj_name,
	          "room"        => $value->room_code
	        );
	      }
	    }
	    echo json_encode($data);
	 }

	//----------------------------------------------------------------------------------------------------------------------------------











	 //----------------------------------------------------------------------------------------------------------------------------------


	/*module : 
		INSTRUCTION LIST : 
			structure : 
					function : timeList() ==> view 
							   timeSave() ==> create
							   timeEdit() ==> edit
							   timeDelete() ==> delete
					end function : 
			end structure
	  end module
	*/
	public function timeList(){
	    echo json_encode($this->sched_time->fetchData());
	}



	//----------------------------------------------------------------------------------------------------------------------------------











	/*module : 
		SUBJECT LIST : 
			structure : 
					function : subjectList() ==> view 
							   subjectSave() ==> create
							   subjectEdit() ==> edit
							   subjectDelete() ==> delete
					end function : 
			end structure
	  end module
	*/





	public function subjectList()
	{
		$data = array();
		$subjects = (object) $this->subjects->fetchData();

		if($subjects)
		{
			foreach ($subjects as $subject) {
				$lab_unit = '';
		        if (!empty($subject['lab_unit'])) {
		          $lab_unit = $subject['lab_unit'];
		        }
		        $data[] = array(
		          'subj_id'=>$subject['subj_id'],
		          'subj_code'=>$subject['subj_code'],
		          'subj_name'=>$subject['subj_name'],
		          'subj_desc'=>$subject['subj_desc'],
		          'lec_unit'=>$subject['lec_unit'],
		          'lab_unit'=>$lab_unit,
		          'lec_hour'=>$subject['lec_hour'],
		          'lab_hour'=>$subject['lab_hour'],
		          'split' =>$subject['split'],
		          'subj_type' =>$subject['subj_type'],
		            'type'=> $subject['subj_type']
		        );
			}		
		}
		
		echo json_encode($data);
	}

	public function subjectSave()
	{
		$result = array();
		if(empty($this->input->post('subj_id'))){
			$rate = $this->input->post('rate');
			unset($_POST['rate']);
			unset($_POST['subj_id']);


			$this->subjects->insert($this->input->post());
			if($this->db->affected_rows()){
				$rateArr = array();

				if(!empty($rate)){
				
					foreach ($rate as $value) {
						$rateArr[] = array(
							"rate_num" => $value['rate_num'],
			                "subj_id"  => $this->db->insert_id(),
			                "rn_id"    => $value['rn_id']
						);
						
					}
					$this->db->insert_batch('rate_percentage_subj', $rateArr);
				}
				$result['result'] = true;
	       		$result['type'] = 'new';
			}
			
		}else{
			
			$subj_id = $this->input->post('subj_id');
			unset($_POST['subj_id']);
			unset($_POST['rate']);
			$this->subjects->update($this->input->post(),$subj_id);
	        if($this->db->affected_rows()){
	        	$result['result'] = true;
	        	$result['type'] = 'update';
	        }
		}
		echo json_encode(array("result" => $result['result'], "type" => $result['type']));
	}

	public function programAcronym(){
	    $phrase = $this->input->post("prog_name");
	    echo $this->set_acronym($phrase);
	}
	public function subjectEdit()
	{
		echo json_encode(["subjData" => (array) $this->subjects->where($this->input->get('subj_id'))->get(), 
	    				  "rate" => $this->rate_percentage_subj->where('subj_id', $this->input->get('subj_id'))->get_all()]);
	}

	public function subjectDelete()
	{
		echo json_encode(array("result" => $this->subjects->remove($this->input->get('subj_id'))));
	}

	public function semesterSetupList()
	{
		
	}

	public function rate()
	{
	    echo json_encode($this->rate_source->fetchData());
	}

	public function changeProgStatus(){

	    $status = $this->input->get("status");
	    $pl_id = $this->input->get("pl_id");	


	    $this->usage_status->where('pl_id',$this->input->get('pl_id'))->update(['status'=>$this->input->get('status')]);

	    if ($this->db->affected_rows()) {
	      echo json_encode(["result" => true]);
	    } else {
	      echo json_encode(["result" => false]);
	    }
	}
	public function set_acronym($phrase)
	{
	  $words = explode(" ", trim($phrase, " "));
	  $acronym = "";
	  foreach ($words as $w) {
	  	if( strlen($w) >= 3){
	    	$acronym .= $w[0];
	  	}
	  }
	  return strtoupper($acronym);
	}

	
}
