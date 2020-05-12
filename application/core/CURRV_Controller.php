<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class CURRV_Controller extends CI_Controller {
	public $data = [];
	public function __construct() {
		parent::__construct();
    	$this->load->helper('date');

	}


	public function checkAuthSession($status=""){
		if($this->session->userdata('userSessionLogin')){
		   return true;
		}else{
		    redirect(base_url().'auth/login'); 
		}

	}



	public function firstLoadData()
	{
		$this->loadModel();
		$this->fetchAndDeleteCacheData();
	}


	public function loadModel()
	{
		$this->load->model('room_list');
		$this->load->model('sched_day');
		$this->load->model('subjects');
		$this->load->model('user');
		$this->load->model('user_type');
		$this->load->model('plotted_schedule');
		$this->load->model('subj_sched_day');
	}
	public function deleteExistingCache()
	{
		
	}
	public function fetchAndDeleteCacheData()
	{
		$user = new User();
		$user_type = new User_type();
		$subjects = new Subjects();
		$sched_day = new Sched_day();
		$room_list = new Room_list();
		$plotted_schedule = new Plotted_schedule();
		$subj_sched_day = new Subj_sched_day();
		
		$user->deleteExistingCache();
		$user_type->deleteExistingCache();
		$subjects->deleteExistingCache();
		$sched_day->deleteExistingCache();
		$room_list->deleteExistingCache();
		$plotted_schedule->deleteExistingCache();
		$subj_sched_day->deleteExistingCache();

		$user->fetchData();
		$user_type->fetchData();
		$subjects->fetchData();
		$sched_day->fetchData();
		$room_list->fetchData();
		$plotted_schedule->fetchData();
		$subj_sched_day->fetchData();
	}


	public function encrypt_data($password)
	{
		return openssl_encrypt($password,"AES-128-ECB","CURRV");
	}

	public function decrypt_data($password)
	{
		return openssl_decrypt($password,"AES-128-ECB","CURRV");
	}



	  public function allSemester()
    {
    	$this->load->model('periodicsem');
        $periodicSemesters = $this->periodicsem->periodicSemeser();
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

    public function storePeriod()
    {
    	 $this->load->model('schoolsemester');
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

            $sy = new SchoolSemester();
            $syResult = $sy->search(['school_year'=>$school_year, 'semester'=>$semester]);

            if (empty($syResult)) {
                $schoolSemester = new SchoolSemester();
                $schoolSemester->school_year = $data['school_year'];
                $schoolSemester->semester = $data['semester'];
                $schoolSemester->date_start = $semesterDateStart;
                $schoolSemester->date_end = $semesterDateEnd;
                $schoolSemester->save();

                $schoolSemesterId = $schoolSemester->db->insert_id();
            }
            else{
                foreach ($syResult as $result){
                    $schoolSemesterId= $result->ss_id;
                }
            }

            $periodicSemester = new PeriodicSem();
            $periodicSemester->ss_id = $schoolSemesterId;
            $periodicSemester->periodic_id = $data['period_id'];
            $periodicSemester->date_start = $periodDateStart;
            $periodicSemester->date_end = $periodDateEnd;
            $periodicSemester->save();

        }

    }
}