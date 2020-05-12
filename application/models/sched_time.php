<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Sched_time extends CURRV_Model{
		public $table = 'sched_time';
		public $primary_key  = 'st_id';
		public $fillable = array('interval','time_start','time_end');
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
			$this->as_object()->set_cache('get_all_sched_time_list')->order_by('st_id','DESC')->get_all();
		}

		public function deleteExistingCache()
		{
			$this->delete_cache('*');
		}

		public function saveData($data = array())
		{
			if($this->insert($data)){
				echo "1";
			}else{
				echo "0";
			}
		}
		public function remove($id)
		{
			if($this->force_delete($id)){
				return true;
			}else{
				return false;
			}
		}

		public function loadLastInput()
		{
			$data = $this->order_by('st_id','DESC')->limit(1)->get();

			if(!empty($data)){
				return $data;	
			}else{
				return $this->defaultSched();
			}
			// return $this->get_all();
			


		}
		public function updateData($data = array())
		{
			$id = $data['st_id'];
			unset($data['st_id']);
			if($this->update($data,$id))
			{
				echo "1";
			}else{
				echo "2";
			}
		}

		public function fetchData()
		{
			return $this->get_all();
		}


		public function defaultSched()
		{
			return  ['interval'   => 15, 
					  'time_start' => '6:00AM',
					  'time_end'   => '10:00PM'
					 ];
		}

}


?>