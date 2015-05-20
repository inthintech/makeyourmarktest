<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exams extends CI_Controller {

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
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
    {
      	parent::__construct();
        // Your own constructor code
        $this->load->model('user','',TRUE);
        if(!$this->session->userdata('client_id'))
        {
        	redirect('login');
        }
    }

	public function index()
	{

				$result = $this->user->getClientName($this->session->userdata('client_id'));
				foreach($result as $row)
     			{
       
       			$client_name= $row->client_name;
      			} 
				$headerdata = array('client_name' => $client_name ,'title' => 'Welcome to Make Your Mark','container_height' => 150 );
				$this->load->view('header',$headerdata);
				$result = $this->user->getSubscriptionDetails($this->session->userdata('client_id'));
				foreach($result as $row)
     			{
       
       			$subscriptiondata = array('subscription_info' => '<tr><td>Name</td><td>'.$row->client_name.'</td></tr><tr><td>Active From</td>
       					<td>'.$row->subscription_start_date.'</td></tr>
						<tr><td>Subscription Ends On</td><td>'.$row->subscription_end_date.'</td></tr><tr><td>Package Name</td>
						<td>'.$row->package_name.'</td></tr>
						<tr><td>Package Description</td><td>'.$row->package_desc.'</td></tr>');
      			} 
				$this->load->view('vsubscription',$subscriptiondata);
				$this->load->view('footer');
		
	}

	public function newexam()
	{

				$result = $this->user->getClientName($this->session->userdata('client_id'));
				foreach($result as $row)
     			{
       
       			$client_name= $row->client_name;
      			} 
				$headerdata = array('client_name' => $client_name ,'title' => 'Add new exam','container_height' => 150 );
				$this->load->view('header',$headerdata);
				$this->load->helper(array('form'));
				
				$statusdata = array('success' => '');
			    $this->load->view('vnewexam',$statusdata);
				$this->load->view('footer');			 
		
	}

	public function newexamstatus()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ename', 'Exam Name', 'trim|required|xss_clean|max_length[250]|callback_enameRegex');
		$this->form_validation->set_error_delimiters('<p class="errorMsg">', '</p>');
		if($this->form_validation->run() == FALSE)
	   	{
		     
			$result = $this->user->getClientName($this->session->userdata('client_id'));
			foreach($result as $row)
			{
				$client_name= $row->client_name;
			} 
			$headerdata = array('client_name' => $client_name ,'title' => 'Add new exam','container_height' => 150 );
			$this->load->view('header',$headerdata);
			$statusdata = array('success' => '');
		    $this->load->view('vnewexam',$statusdata);
			$this->load->view('footer');			 

	   }
	   else
	   {
			

			$result = $this->user->getClientName($this->session->userdata('client_id'));
			foreach($result as $row)
			{
				$client_name= $row->client_name;
			} 
			$headerdata = array('client_name' => $client_name ,'title' => 'Add new exam','container_height' => 150 );
			$this->load->view('header',$headerdata);
			if($this->user->newExamEntry($this->session->userdata('client_id'),$this->input->post('ename')))
			{
				$statusdata = array('success' => '<p class="statusMsg">Exam Uploaded Successfully</p>');
			}
			else
			{
				$statusdata = array('success' => '<p class="errorMsg">Unknown Error. Please try again.</p>');
			}
		   	
		    $this->load->view('vnewexam',$statusdata);
			$this->load->view('footer');			 

	   }

	}


	public function enameRegex()
	{
		$ename = $this->input->post('ename');
		if(preg_match('/^[a-zA-Z0-9 ]+$/', $ename))
		//check if only alphanumeric,numbers and spaces are present	
		{
			return TRUE;
		}
		else
		{		
			$this->form_validation->set_message('enameRegex', 'Please enter only alphabets, numbers and spaces');
     		return FALSE;
		}		
	}


	public function viewexam()
	{
				$result = $this->user->getClientName($this->session->userdata('client_id'));
				foreach($result as $row)
     			{
       
       			$client_name= $row->client_name;
      			} 
				$headerdata = array('client_name' => $client_name ,'title' => 'Exam Status','container_height' => 150 );
				$this->load->view('header',$headerdata);
				$this->load->helper(array('form'));
				$statusdata = array('examstatus' => '');
		    	$this->load->view('vnewexam',$statusdata);
			    $this->load->view('vdispexam');
				$this->load->view('footer');			 
		
	}


	public function viewexamstatus()
	{

				$result = $this->user->getClientName($this->session->userdata('client_id'));
				foreach($result as $row)
     			{
       
       			$client_name= $row->client_name;
      			} 
				$headerdata = array('client_name' => $client_name ,'title' => 'Exam Status','container_height' => 150 );
				$this->load->view('header',$headerdata);
				$this->load->helper(array('form'));
			    $this->load->view('vdispexam');
				$this->load->view('footer');		
		
	}





}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */