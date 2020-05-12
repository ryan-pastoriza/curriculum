<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Plotted_schedule extends CURRV_Model{
		public $table = 'subj_sched_day';
		public $primary_key  = 'ps_id';
		public $fillable = array('user_id','subj_id','sd_id','key','rl_id','time_start','time_end');
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
			// $this->has_one['room_list'] = array('room_list','rl_id','rl_id');
			// $this->has_one['Sched_day'] = array('sched_day','sd_id','sd_id');
			// $this->has_one['subject'] = array('subjects','subj_id','subj_id');

		
			// you can also use caching. If you want to use the set_cache('...') method, but you want to change the way the caching is made you can use the following properties:

			$this->cache_driver = 'file';
			//By default, MY_Model uses the files (CodeIgniter's file driver) to cache result. If you want to change the way it stores the cache, you can change the $cache_driver property to whatever CodeIgniter cache driver you want to use.

			$this->cache_prefix = 'currv';
			//With $cache_prefix, you can prefix the name of the caches. By default any cache made by MY_Model starts with 'mm' + _ + "name chosen for cache"

			parent::__construct();
		}

		public function fetchCacheData()
		{
			$this->as_object()->set_cache('get_all_plotted_schedule')->get_all();
		}

		public function deleteExistingCache()
		{
			$this->delete_cache('*');
		}

		public function fetchData()
		{
			return $this->get_all();
		}

		public function get_by_user($user_id)
		{
		    $query = $this->db->query('SELECT * FROM plotted_schedule INNER JOIN room_list ON plotted_schedule.rl_id = room_list.rl_id INNER JOIN sched_day ON plotted_schedule.sd_id = sched_day.sd_id INNER JOIN `subject` ON plotted_schedule.subj_id = `subject`.subj_id WHERE plotted_schedule.user_id != "$user_id" ');
		    // var_dump($this->with_room_list()->with_sched_day()->with_subject()->where('plotted_schedule.user_id != ',$user_id)->get_all());
		    return (array) $query->result();
		}
	}

?>