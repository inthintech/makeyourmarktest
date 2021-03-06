<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {


	public function __construct()
    {
      	parent::__construct();
        // Your own constructor code
        $this->load->model('user','',TRUE);
        $this->load->helper(array('form'));
    }

	public function index()
	{
		
		if($this->session->userdata('client_id'))
			{
				redirect('adminstatic');
			}
		else
			{
				$this->load->view('vlogin');
			}
		
	}

  public function logout()

  {

    $this->session->sess_destroy();
    redirect('login');

  }

	public function verify()
 	{
   //This method will have the credentials validation
   $this->load->library('form_validation');

   $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
   $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');
   $this->form_validation->set_error_delimiters('<p class="invalid">', '</p>');

   if($this->form_validation->run() == FALSE)
   {
     //Field validation failed.  User redirected to login page
     $this->load->view('vlogin');
   }
   else
   {
     //Go to private area
     redirect('adminstatic');
   }

 	}

 	public function check_database()
 	{
   //Field validation succeeded.  Validate against database
   $username = $this->input->post('username');
   $password = $this->input->post('password');

   //query the database
   $result = $this->user->login($username, $password);

   if($result)
   {
     
     foreach($result as $row)
     {
       
       $client_id = $row->client_id;
       $flag = $row->is_active;
	   $client_type = $row->client_type; 
       $usertype = $row->user_type; 
       $userid = $row->user_id;
	   $username = $row->username;
      } 

      if($flag=='Y')
      {

        $this->session->set_userdata('client_id', $client_id);
        $this->session->set_userdata('user_type', $usertype);
        $this->session->set_userdata('user_id', $userid);
		$this->session->set_userdata('user_name', $username);
		$this->session->set_userdata('client_type', $client_type);
        return TRUE;
      }
      else
      {
        $this->form_validation->set_message('check_database', 'Your license is expired. Please contact Customer Support.');
        return FALSE;
      }
    }
   else
   {
     $this->form_validation->set_message('check_database', 'Invalid username or password');
     return FALSE;
   }
 	}
	
}

