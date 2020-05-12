<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Curr_codelist extends CURRV_Model{
		public $table = 'curr_codelist';
		public $primary_key  = 'cur_id';
		public $fillable = array('c_code','pl_id','eff_sy','revision_no', 'date_issued', 'issued_no', 'document_code','eff_sem','status');
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
			$this->as_object()->set_cache('get_all_curr_codelist_list')->get_all();
		}

		public function deleteExistingCache()
		{
			$this->delete_cache('*');
		}
		
		public function fetchData()
		{
			return $this->get_all();
		}
		public function active_curriculum(){
		 
			$query = $this->db->query("SELECT curr_codelist.pl_id, curr_codelist.eff_sy, curr_codelist.`status`, curr_codelist.eff_sem, curr_codelist.c_code, sis_main_db.program_list.prog_code, sis_main_db.program_list.prog_abv, sis_main_db.program_list.prog_name, sis_main_db.program_list.prog_desc, sis_main_db.program_list.prog_type, sis_main_db.program_list.`level`, sis_main_db.program_list.major, sis_main_db.program_list.senior_high_track, sis_main_db.program_list.dep_id, sis_main_db.program_list.created_at, sis_main_db.program_list.updated_at FROM curr_codelist INNER JOIN sis_main_db.program_list ON curr_codelist.pl_id = sis_main_db.program_list.pl_id GROUP BY curr_codelist.eff_sy ORDER BY curr_codelist.cur_id DESC");
		    return (array) $query->result();
		}
		public function get_curriculum($pl_id = false)
		{
		    $this->db->where('pl_id', $pl_id);
		    $this->db->order_by('eff_sy','DESC');

		    return $this->get_all();

		}
}


?>