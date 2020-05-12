<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Subj_sched_day extends CURRV_Model{
		public $table = 'subj_sched_day';
		public $primary_key  = 'ssd_id';
		public $fillable = array('time_start','time_end','sd_id','ss_id','type','rl_id','user_id');
		public $protected = array('');
		public $delete_cache_on_save = TRUE;

		public function __construct()
		{
			// you can set the database connection that you want to use for this particular model, by passing the group connection name or a config array. By default will use the default connection
			// $this->_database_connection  = 'special_connection';

			// you can disable the use of timestamps. This way, MY_Model won't try to set a created_at and updated_at value on create methods. Also, if you pass it an array as calue, it tells MY_Model, that the first element is a created_at field type, the second element is a updated_at field type (and the third element is a deleted_at field type if $this->soft_deletes is set to TRUE)
			$this->timestamps = FALSE;

			// you can enable (TRUE) or disable (FALSE) the "soft delete" on records. Default is FALSE, which means that when you delete a row, that one is gone forever
	        $this->soft_deletes = FALSE;

	        // you can set how the model returns you the result: as 'array' or as 'object'. the default value is 'object'
			$this->return_as = 'object' | 'array';


			// you can set relationships between tables
			// $this->has_many['user'] = array('user','user_type_id','user_type_id');
			//$this->has_one['schedule'] = array('sched_subj','ss_id','ss_id');

		
			// you can also use caching. If you want to use the set_cache('...') method, but you want to change the way the caching is made you can use the following properties:

			$this->cache_driver = 'file';
			//By default, MY_Model uses the files (CodeIgniter's file driver) to cache result. If you want to change the way it stores the cache, you can change the $cache_driver property to whatever CodeIgniter cache driver you want to use.

			$this->cache_prefix = 'currv';
			//With $cache_prefix, you can prefix the name of the caches. By default any cache made by MY_Model starts with 'mm' + _ + "name chosen for cache"

			parent::__construct();
		}
		public function schedule()
	    {
	        $this->toJoin = ['sched_subj'=>'subj_sched_day'];
	        return $this;
	    }
		public function fetchCacheData()
		{
			$this->as_object()->set_cache('get_all_subject_sched_day')->get_all();
		}

		public function deleteExistingCache()
		{
			$this->delete_cache('*');
		}

		public function fetchData()
		{
			return $this->get_all();
		}
		public function checkConflict($get)
		{
			$data = $this->where('ss_id',$get['ssid'])->get();

			$query = $this->db->query("SELECT subj_sched_day.ssd_id, subj_sched_day.time_start, subj_sched_day.time_end, subj_sched_day.sd_id, subj_sched_day.ss_id, subj_sched_day.type, subj_sched_day.rl_id, subj_sched_day.user_id, subj_sched_day.created_at, sched_day.abbreviation, sched_day.composition, sched_subj.employee_id, `subject`.subj_name FROM subj_sched_day INNER JOIN sched_day ON subj_sched_day.sd_id = sched_day.sd_id INNER JOIN sched_subj ON subj_sched_day.ss_id = sched_subj.ss_id INNER JOIN `subject` ON sched_subj.subj_id = `subject`.subj_id WHERE sched_subj.employee_id = '".$get['employee_id']."' AND ('".$data['time_end']."' > subj_sched_day.time_start AND '".$data['time_start']."' < subj_sched_day.time_end) AND  subj_sched_day.sd_id = ".$data['sd_id']." AND subj_sched_day.ss_id <> ". $get['ssid']);
			
		
			return (array) $query->result();

		}
		public function getScheduleWithRoom($id)
		{	
			$query = $this->db->query("SELECT subj_sched_day.ssd_id, subj_sched_day.time_start, subj_sched_day.time_end, subj_sched_day.sd_id, subj_sched_day.ss_id, subj_sched_day.type, subj_sched_day.rl_id, subj_sched_day.user_id, subj_sched_day.created_at, room_list.room_code, room_list.room_name, room_list.location, room_list.capacity, room_list.type, room_list.`desc`, room_list.lab_type_id FROM subj_sched_day INNER JOIN room_list ON subj_sched_day.rl_id = room_list.rl_id WHERE subj_sched_day.ss_id = '".$id."'");
			return (array) $query->result();
		}
		public function get_schedule($room_code = null, $sy = null, $semester = null){
	    	$query = $this->db->query("SELECT subj_sched_day.rl_id, subj_sched_day.ssd_id, subj_sched_day.time_start, subj_sched_day.time_end, subj_sched_day.type, subj_sched_day.user_id, subj_sched_day.created_at, room_list.room_code, room_list.room_name, room_list.capacity, room_list.location, room_list.type, room_list.`desc`, sched_subj.year_lvl, sched_subj.sy, sched_subj.sem, sched_subj.avs_status, `subject`.subj_id, sched_subj.employee_id, block_section.sec_code, block_section.activation, block_section.description, block_section.year_lvl, block_section.semister, block_section.sy, block_section.pl_id, block_section.cur_id, `subject`.subj_code, `subject`.subj_name, sched_subj.temp_id, sched_day.abbreviation, sched_day.composition, sched_subj.ss_id, sched_day.sd_id, `subject`.subj_desc, `subject`.lec_hour, `subject`.lec_unit, `subject`.lab_unit, `subject`.lab_hour, `subject`.split, `subject`.subj_type, block_section.bs_id FROM subj_sched_day INNER JOIN room_list ON subj_sched_day.rl_id = room_list.rl_id INNER JOIN sched_subj ON subj_sched_day.ss_id = sched_subj.ss_id INNER JOIN sched_day ON subj_sched_day.sd_id = sched_day.sd_id INNER JOIN `subject` ON sched_subj.subj_id = `subject`.subj_id INNER JOIN block_section ON sched_subj.bs_id = block_section.bs_id WHERE sched_subj.sy = '$sy' AND sched_subj.sem = '$semester' AND room_list.room_code = '$room_code' GROUP BY subj_sched_day.time_start, subj_sched_day.time_end, subj_sched_day.sd_id");
	    	
			return (array) $query->result();
        }
        public function getScheduleWithSubjectSched($bsid)
        {
        	$query = $this->db->query("SELECT subj_sched_day.ssd_id, subj_sched_day.time_start, subj_sched_day.time_end, subj_sched_day.sd_id, subj_sched_day.type, subj_sched_day.rl_id, subj_sched_day.user_id, subj_sched_day.created_at, sched_subj.ss_id, sched_subj.year_lvl, sched_subj.sy, sched_subj.sem, sched_subj.subj_id, sched_subj.avs_status, sched_subj.employee_id, sched_subj.bs_id, sched_subj.temp_id, sched_subj.`key`, sched_subj.created_at FROM subj_sched_day INNER JOIN sched_subj ON subj_sched_day.ss_id = sched_subj.ss_id WHERE sched_subj.bs_id = '".$bsid."'");
        	return (array) $query->result();
        }
		
		
	}

?>