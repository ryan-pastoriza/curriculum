<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Block_section_subject extends CURRV_Model{
		public $table = 'block_section_subjects';
		public $primary_key  = 'bss_id';
		public $fillable = array('bss_id','bs_id','subj_id','type','remaining_hour','status');
		public $protected = array('');
		public $delete_cache_on_save = TRUE;

		public function __construct()
		{
			// you can set the database connection that you want to use for this particular model, by passing the group connection name or a config array. By default will use the default connection
			//$this->_database_connection  = 'sis_main_db_connection';

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
			//$this->has_many['subject'] =  array('sched_subj','bs_id','bs_id');

		
			// you can also use caching. If you want to use the set_cache('...') method, but you want to change the way the caching is made you can use the following properties:

			$this->cache_driver = 'file';
			//By default, MY_Model uses the files (CodeIgniter's file driver) to cache result. If you want to change the way it stores the cache, you can change the $cache_driver property to whatever CodeIgniter cache driver you want to use.

			$this->cache_prefix = 'currv';
			//With $cache_prefix, you can prefix the name of the caches. By default any cache made by MY_Model starts with 'mm' + _ + "name chosen for cache"

			parent::__construct();
		}

		public function fetchCacheData()
		{
			$this->as_object()->set_cache('block_section_subject_list')->get_all();
		}

		public function deleteExistingCache()
		{
			$this->delete_cache('*');
		}
		
		public function fetchData()
		{
			return $this->get_all();
		}
		public function check_plotted($bs_id = false){
	        $this->db->where('bs_id', $bs_id);
	        return $this->get_all();
	    }
	    public function get_all_subjects($data = array())
	    {
	    	$query  = $this->db->query('SELECT block_section_subjects.bss_id, block_section_subjects.bs_id, block_section_subjects.subj_id, block_section.sec_code, CONCAT("[",block_section.sec_code,"] = ",`subject`.subj_name, "") as subj_name,`subject`.subj_code, block_section.year_lvl, block_section.semister, block_section.sy FROM block_section_subjects INNER JOIN block_section ON block_section_subjects.bs_id = block_section.bs_id INNER JOIN `subject` ON block_section_subjects.subj_id = `subject`.subj_id WHERE block_section_subjects.type = "'.$data['type'].'" AND block_section.sec_code = "'.$data['section_code'].'" AND block_section.sy = "'.$data['sy'].'" AND block_section.semister = "'.$data['semester'].'"');
	    	
	        return (array) $query->result();
	    }
	    public function get_all_subjects_edit($yearLvl,$semester)
	    {

	    	$query  = $this->db->query('SELECT  CONCAT( `subject`.subj_id,"=",subject.subj_code," = ",`subject`.subj_name, "") as subj_name FROM subject');
	        return (array) $query->result();
	    }
		public function get_subject($data =array())
	    {
	    	$query  = $this->db->query('SELECT * FROM block_section_subjects INNER JOIN block_section ON block_section_subjects.bs_id = block_section.bs_id INNER JOIN `subject` ON block_section_subjects.subj_id = `subject`.subj_id WHERE block_section_subjects.type = "'.$data['type'].'" AND block_section_subjects.bs_id = "'.$data['bs_id'].'"');
	    	 // $query  = $this->db->query('SELECT * FROM block_section_subjects INNER JOIN block_section ON block_section_subjects.bs_id = block_section.bs_id INNER JOIN `subject` ON block_section_subjects.subj_id = `subject`.subj_id WHERE block_section_subjects.type = "'.$data['type'].'"');
	        return (array) $query->result();
	    }

}


?>