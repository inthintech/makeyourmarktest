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
    }

	public function index()
	{
		if($this->session->userdata('client_id'))
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
		else
			{
				
				redirect('login');
			}
		
	}

	public function newexam()
	{
		if($this->session->userdata('client_id'))
			{
				
				$result = $this->user->getClientName($this->session->userdata('client_id'));
				foreach($result as $row)
     			{
       
       			$client_name= $row->client_name;
      			} 
				$headerdata = array('client_name' => $client_name ,'title' => 'Welcome to Make Your Mark','container_height' => 150 );
				$this->load->view('header',$headerdata);
				$this->load->helper(array('form'));
				$this->load->view('vnewexam');
				$this->load->view('footer');

			}
		else
			{
				
				redirect('login');
			}
		
	}







}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */