<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Sched_subj extends CURRV_Model{
		public $table = 'sched_subj';
		public $primary_key  = 'ss_id';
		public $fillable = array('year_lvl','sy','sem','subj_id','avs_status','employee_id','bs_id','temp_id','key');
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

			//$this->has_one['...'] allows establishing ONE TO ONE or more ONE TO ONE relationship(s) between models/tables
			// $this->has_one['user_type'] = array('user_type','user_type_id','user_type_id');
			// $this->has_many[''...] allows establishing ONE TO MANY or more ONE TO MANY relationship(s) between models/tables
			// $this->has_many['subj_sched_day'] = array('subj_sched_day','user_id','user_id');
			// $this->has_many['plotted_schedule'] = array('plotted_schedule','user_id','user_id');
			 // $this->has_one['user_type'] = 'user_type';
			 // $this->has_many['subj_sched_day'] =  'subj_sched_day';

		
			// you can also use caching. If you want to use the set_cache('...') method, but you want to change the way the caching is made you can use the following properties:

			$this->cache_driver = 'file';
			//By default, MY_Model uses the files (CodeIgniter's file driver) to cache result. If you want to change the way it stores the cache, you can change the $cache_driver property to whatever CodeIgniter cache driver you want to use.

			$this->cache_prefix = 'currv';
			//With $cache_prefix, you can prefix the name of the caches. By default any cache made by MY_Model starts with 'mm' + _ + "name chosen for cache"

			parent::__construct();
		}

		public function fetchCacheData()
		{
			$this->as_object()->set_cache('get_all_subject_sched')->get_all();
		}

		public function deleteExistingCache()
		{
			$this->delete_cache('*');
		}

		public function fetchData()
		{
			return $this->get_all();
		}


		public function saveData($data = array())
		{
			if($this->insert($data)){
				return array("result"=>true, "type"=>"new");
			}else{
				return false;
			}
		}
		public function updateData($data = array())
		{

			$id = $data['sd_id'];
			unset($data['sd_id']);
			$this->update($data,$id);
			return array("result"=>true, "type"=>"update");
		}
		public function remove($id)
		{
			if($this->force_delete($id)){
				return true;
			}else{
				return false;
			}
		}


		public function schedule($data = [])
        {
            $this->db->select('
                block_section.sec_code,
                sched_subj.sem,
                sched_subj.sy,
                sched_subj.year_lvl,
                subject.subj_name,
                subj_sched_day.type,
                subj_sched_day.time_start,
                subj_sched_day.time_end,
                room_list.room_code,
                room_list.room_name,
                sched_subj.ss_id,
                GROUP_CONCAT(sched_day.abbreviation SEPARATOR "") AS day
            ')->from('sched_subj')
            ->join('subject', 'subject.subj_id = sched_subj.subj_id')
            ->join('block_section', 'block_section.bs_id = sched_subj.bs_id')
            ->join('subj_sched_day', 'subj_sched_day.ss_id = sched_subj.ss_id')
            ->join('room_list', 'room_list.rl_id = subj_sched_day.rl_id')
            ->join('sched_day', 'sched_day.sd_id = subj_sched_day.sd_id')
            ->where(['sched_subj.sy'=>$data['sy']])
            ->where(['sched_subj.sem'=>$data['semester']])
            // ->where(['sched_subj.year_lvl'=>$data['year_level']])
            ->where(['sched_subj.subj_id'=>$data['subj_id']])
            ->group_by(['sched_subj.ss_id','block_section.sec_code','subj_sched_day.type','subj_sched_day.type'])
            ->order_by('block_section.sec_code');

            $query = $this->db->get();

            return $query->result();
        }
		public function get_list($sy,$sem)
		{

			$query = $this->db->query('SELECT sched_subj.ss_id, sched_subj.year_lvl, sched_subj.sy, sched_subj.sem, sched_subj.subj_id, sched_subj.avs_status, sched_subj.employee_id, sched_subj.bs_id, sched_subj.temp_id, sched_subj.`key`, sched_subj.created_at, `subject`.subj_code, `subject`.subj_name, `subject`.subj_desc, `subject`.lec_hour, `subject`.lec_unit, `subject`.lab_hour, `subject`.lab_unit, block_section.sec_code, block_section.activation, block_section.description, block_section.year_lvl, block_section.semister, block_section.sy, block_section.pl_id, block_section.cur_id FROM sched_subj INNER JOIN `subject` ON sched_subj.subj_id = `subject`.subj_id INNER JOIN block_section ON sched_subj.bs_id = block_section.bs_id WHERE sched_subj.sy = "'.$sy.'" AND sched_subj.sem = "'.$sem.'" GROUP BY  sched_subj.bs_id, sched_subj.subj_id');

			return (array) $query->result();
		}
		public function getSubjectFromSched($bs_id = ''){
			$query = $this->db->query('SELECT sched_subj.*, `subject`.subj_code, `subject`.subj_name, `subject`.subj_desc, `subject`.lec_hour, `subject`.lec_unit, `subject`.lab_hour, `subject`.lab_unit, `subject`.split, `subject`.subj_type, `subject`.lab_type_id FROM sched_subj INNER JOIN `subject` ON sched_subj.subj_id = `subject`.subj_id WHERE sched_subj.bs_id = '.$bs_id.' GROUP BY sched_subj.subj_id');
			return (array) $query->result();
		}
		public function getRoomSchedSubj($bs_id='', $subj_id=''){
			$query = $this->db->query('SELECT sched_subj.*, sched_day.abbreviation, sched_day.composition, subj_sched_day.ssd_id, subj_sched_day.time_start, subj_sched_day.time_end, subj_sched_day.sd_id, subj_sched_day.ss_id, subj_sched_day.type, subj_sched_day.rl_id, subj_sched_day.user_id, subj_sched_day.created_at, room_list.rl_id, room_list.room_code, room_list.room_name, room_list.capacity, room_list.location, room_list.type, room_list.`desc`, room_list.lab_type_id FROM sched_subj INNER JOIN subj_sched_day ON subj_sched_day.ss_id = sched_subj.ss_id INNER JOIN room_list ON subj_sched_day.rl_id = room_list.rl_id INNER JOIN sched_day ON subj_sched_day.sd_id = sched_day.sd_id WHERE sched_subj.bs_id = '.$bs_id.' AND sched_subj.subj_id  = '.$subj_id.' ');
			return (array) $query->result();
		}

		public function getRoomSchedSubjByBlockSection($bs_id=''){
			$query = $this->db->query('SELECT sched_subj.*, sched_day.abbreviation, sched_day.composition, subj_sched_day.ssd_id, subj_sched_day.time_start, subj_sched_day.time_end, subj_sched_day.sd_id, subj_sched_day.ss_id, subj_sched_day.type, subj_sched_day.rl_id, subj_sched_day.user_id, subj_sched_day.created_at, room_list.rl_id, room_list.room_code, room_list.room_name, room_list.capacity, room_list.location, room_list.type, room_list.`desc`, room_list.lab_type_id FROM sched_subj INNER JOIN subj_sched_day ON subj_sched_day.ss_id = sched_subj.ss_id INNER JOIN room_list ON subj_sched_day.rl_id = room_list.rl_id INNER JOIN sched_day ON subj_sched_day.sd_id = sched_day.sd_id WHERE sched_subj.bs_id = '.$bs_id.'');
			return (array) $query->result();
		}
		
		
}


?>