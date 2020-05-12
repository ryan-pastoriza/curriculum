<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CURRV_Controller {

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
	    $this->load->model("user");

	    if($this->session->userdata('userSessionLogin')){
		  redirect(base_url().'gen_info'); 
		}
	}
	
	public function index()
	{
		$this->login();
	}

	public function verifyLogin()
	{
		$this->formValidate();
		if ($this->form_validation->run() == false) {
			$this->login();
		}else{
            $username = $this->xssClean($this->input->post('username'));
            $password = $this->xssClean($this->input->post('password'));
            $user = new User();
			$message = $user->checkUser($username, $password);

			$data['error'] = $message;


			$this->load->view('Auth/index',$data);
 
		}
		
	}

	public function xssClean($input = "")
	{
		return $this->security->xss_clean($input);
	}
	public function formValidate()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
	}
	public function login()
	{
		$this->load->view('Auth/index');
	}

	public function logout()
	{	
		$this->session->unset_userdata('userSessionLogin');
        $this->session->sess_destroy();
		redirect(base_url()."login", "refresh");
		
	}
}
