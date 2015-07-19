<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminaction extends CI_Controller {

	private $containerHeight;
	
	public function __construct()
    {
      	parent::__construct();
        // Your own constructor code
        if(!$this->session->userdata('client_id'))
        {
        	redirect('login');
        }
		$this->load->model('user','',TRUE);
		$this->containerHeight = 100;
    }
	
	public function headerSetup($title,$height)
	{
		$client_name = '';
		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
		{
			$client_name= $row->client_name;
		}
		$headerdata = array(
		'client_name' => $client_name ,
		'title' => $title,
		'container_height' => $height,
		'user_name' => $this->session->userdata('user_name'));
		$this->load->view('header',$headerdata);
	}

	public function alphanumericVal($inp,$name)
	{
		
		if(preg_match('/^[a-zA-Z0-9 ]+$/', $inp))
		//check if only alphanumeric,numbers and spaces are present	
		{
			return TRUE;
		}
		else
		{		
			$this->form_validation->set_message('alphanumericVal', 'Please enter only alphabets, numbers and spaces for '.$name.' field');
     		return FALSE;
		}		
	}
	
	
	public function alphanumericValnoSpc($inp,$name)
	{
		
		if(preg_match('/^[a-zA-Z0-9]+$/', $inp))
		//check if only alphanumeric,numbers are present	
		{
			return TRUE;
		}
		else
		{		
			$this->form_validation->set_message('alphanumericValnoSpc', 'Please enter only alphabets and numbers for '.$name.' field');
     		return FALSE;
		}		
	}
	
	public function checkUname($inp)
	{
		
		if($this->user->checkUsername($inp))
		{
			
			$this->form_validation->set_message('checkUname', 'Username already exists');
     		return FALSE;			
		}
		else
		{	
			return TRUE;	
		}		
	}
	
	public function index()
	{
		redirect('adminstatic');	
	}
	
	public function addexam()
	{

		$this->headerSetup('Add new exam',$this->containerHeight);
		
		if(!isset($_POST['submit']))
		{
			
			$this->load->helper(array('form'));
			$data = array('formaction' => 'adminaction/addexam');
			$this->load->view('vnewexam',$data);
					
		}
		else
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('ename', 'Exam Name', 'trim|required|xss_clean|max_length[250]|callback_alphanumericVal[Exam Name]');
			$this->form_validation->set_error_delimiters('<br><br><p class="errorMsg">', '</p>');
			if($this->form_validation->run() == FALSE)
		   	{
				$data = array('formaction' => 'adminaction/addexam');
				$this->load->view('vnewexam',$data);
			}
			else
			{

				if($this->user->newExamEntry($this->session->userdata('client_id'),$this->input->post('ename')))
				{
					$data = array('message' => '<div class="alert alert-warning" role="alert">Success : New exam record has been created.</div>');
				}
				else
				{
					$data = array('message' => '<div class="alert alert-danger" role="alert">Error : Connection failed. Please try again.</div>');
				}
			   	
			    $this->load->view('vmessage',$data);
			}
		}
		
		$this->load->view('footer');
		
	}

	
	
	public function adduser()

	{
		
		$this->headerSetup('Add new user',$this->containerHeight);
		
		if(!isset($_POST['submit']))
		{
		$this->load->helper(array('form'));
		$data = array('formaction' => 'adminaction/adduser');
		$this->load->view('vadduser',$data);
		}
		else
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('uname', 'User Name', 'trim|required|xss_clean|max_length[15]|callback_alphanumericValnoSpc[User Name]|callback_checkUname');
			$this->form_validation->set_rules('pass', 'Password', 'trim|required|xss_clean|max_length[15]');
			$this->form_validation->set_error_delimiters('<p class="errorMsg">', '</p>');

			if($this->form_validation->run() == FALSE)
			{
				$data = array('formaction' => 'adminaction/adduser');
				$this->load->view('vadduser',$data);		 
			}
			else
			{
				if($this->user->newUserEntry($this->session->userdata('client_id'),$this->input->post('uname'),$this->input->post('pass')))
				{
	
					$data = array('message' => '<div class="alert alert-warning" role="alert">Success : New user has been created.</div>');
				}
				else
				{
					$data = array('message' => '<div class="alert alert-danger" role="alert">Error : Connection failed. Please try again.</div>');
	
				}
				$this->load->view('vmessage',$data);

			}
		}
		
		$this->load->view('footer');
		
	}
	
	public function deleteuser()

	{
		
		$this->headerSetup('Delete user',$this->containerHeight);
		
		if(!isset($_POST['submit']))
		{
		$this->load->helper(array('form'));
		$result = $this->user->getUserList($this->session->userdata('client_id'));
			if($result)
			{
				$userlist = '';
				foreach($result as $row)
	 			{
	   			$userlist = "<option value=".$row->user_id.">".$row->username."</option>".$userlist;
	   			
	  			} 
	  			$data = array('formaction' => 'adminaction/deleteuser','userlist' => $userlist);
				$this->load->view('vdeleteuser',$data);
			}
			else
			{
				$data = array('message' => '<div class="alert alert-danger" role="alert">Error : No user has been created. Please create a user.</div>');
				$this->load->view('vmessage',$data);
			}

		}
		else
		{
			
			if($this->user->removeUser($this->session->userdata('client_id'),$this->input->post('userid')))
			{
				$data = array('message' => '<div class="alert alert-warning" role="alert">Success : User is deleted from system.</div>');			
			}
			else
			{
				$data = array('message' => '<div class="alert alert-danger" role="alert">Error : Connection failed. Please try again.</div>');	
			}
			$this->load->view('vmessage',$data);	
		}
		
		$this->load->view('footer');
		
	}
	
	public function changepassword()

	{
		
		$this->headerSetup('Change Password',$this->containerHeight);
		
		if(!isset($_POST['submit']))
		{
			$this->load->helper(array('form'));
			$data = array('formaction' => 'adminaction/changepassword');
			$this->load->view('vchangepass',$data);
		}
		else
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('oldpass', 'Current Password', 'trim|required|xss_clean|max_length[15]|callback_checkOldPass');
			$this->form_validation->set_rules('newpass', 'Password', 'trim|required|xss_clean|max_length[15]');
			$this->form_validation->set_error_delimiters('<p class="errorMsg">', '</p>');
	
			if($this->form_validation->run() == FALSE)
			{
				$data = array('formaction' => 'adminaction/changepassword');
				$this->load->view('vchangepass',$data);		 
			}
			else
	
			{
				if($this->user->changePassword($this->session->userdata('user_id'),$this->input->post('newpass')))
				{
					$data = array('message' => '<div class="alert alert-warning" role="alert">Success : Password is changed.</div>');
					
				}
				else
				{
					$data = array('message' => '<div class="alert alert-danger" role="alert">Error : Connection failed. Please try again.</div>');
				}
				
				$this->load->view('vmessage',$data);
			}
		}
		
		$this->load->view('footer');
		
	}
	
	

}

