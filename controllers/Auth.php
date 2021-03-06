<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session','form_validation'));
		$this->load->helper('form');
		$this->load->model('Authmodel');
	}

	public function index()
	{
		// set validation rules
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		
		if ($this->form_validation->run() == false) 
		{
			$this->blade->view('template-auth/header');
		    $this->blade->view('auth/index');
			$this->blade->view('template-auth/footer');
		} 
		else 
		{
			$user = $this->get_login($this->input->post('username'), $this->input->post('password'));
			if(is_array($user))
			{
				$this->session->set_userdata("back_email", $user[0]['email']);
				$this->session->set_userdata("back_name", $user[0]['name']);
				$this->session->set_userdata("back_userid", $user[0]['id']);
				$this->session->set_userdata("kk_level", $user[0]['id_level']);
				
				$akses = $this->session->userdata('kk_level');

				if ($akses == 1) {
					redirect("Admin/dashboard");
				}else if ($akses == 2) {
					echo 'Ini Level User';
				}

			 }else{
			 	redirect('auth','refresh');
			 }
			
		}
			
	}

	public function get_login($email = null, $password = null)
	{

		
		$res = "";
        $dataLogin = Authmodel::get_password($email);
		if ($dataLogin) 
		{
			switch ($dataLogin) 
			{
			    case password_verify($password, $dataLogin[0]['password']) && $dataLogin[0]['status'] == "active" :
		            $res = $dataLogin;
			        break;
			    case password_verify($password, $dataLogin[0]['password']) && $dataLogin[0]['status'] == "non-active":
		            $res = 'Account is not active';
			        break;
			    case password_verify($password, $dataLogin[0]['password']) && $dataLogin[0]['status'] == "deleted":
				    $res =  'Account was deleted.';
			        break;
			    default:
			    	$res = 'Invalid Password';
			    	break;
			}
		}
		else
		{
            $res = 'User / Email Not Found';
		}
	
		return $res;
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('auth','refresh');
	}

}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */
