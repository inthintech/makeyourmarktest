<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VerifyLogin extends CI_Controller {

 function __construct()
 {
   parent::__construct();
   $this->load->model('user','',TRUE);
 }

 function index()
 {
   //This method will have the credentials validation
   $this->load->library('form_validation');

   $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
   $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');
   $this->form_validation->set_error_delimiters('<span class="invalid">', '</span>');

   if($this->form_validation->run() == FALSE)
   {
     //Field validation failed.  User redirected to login page
     $this->load->view('vlogin');
   }
   else
   {
     //Go to private area
     redirect('exams');
   }

 }

 function check_database($password)
 {
   //Field validation succeeded.  Validate against database
   $username = $this->input->post('username');

   //query the database
   $result = $this->user->login($username, $password);

   if($result)
   {
     
     foreach($result as $row)
     {
       
       $client_id = $row->client_id;
       $flag = $row->is_active;
      
      } 

      if($flag='Y')
      {

        $this->session->set_userdata('client_id', $client_id);
        return TRUE;
      }
      else
      {
        $this->form_validation->set_message('check_database', 'Your license is expired. Please contact Customer Support.');
        return false;
      }
    }
   else
   {
     $this->form_validation->set_message('check_database', 'Invalid username or password');
     return false;
   }
 }

}

?>