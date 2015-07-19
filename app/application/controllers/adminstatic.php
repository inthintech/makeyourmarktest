<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminstatic extends CI_Controller {

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
		$user_name = '';
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

	public function index()
	{


		$this->headerSetup('Welcome to Make Your Mark',$this->containerHeight);
		$result = $this->user->getSubscriptionDetails($this->session->userdata('client_id'));
		foreach($result as $row)
			{

			$data = array('client_name' => $row->client_name, 'ssd' => $row->subscription_start_date,
			'sed' => $row->subscription_end_date, 'pkg_name' => $row->package_name, 'pkg_desc' => $row->package_desc);
			} 
		$this->load->view('vsubscription',$data);
		$this->load->view('footer');
		
	}
	
	public function help()

	{

		$this->headerSetup('Contact Support',$this->containerHeight);
		$data = array('message' => '<p class="help">If you need any technical support or if you have any feedback about our product, please contact us at 
		<span style="color:blue;">keyrelations@gmail.com</span> <br><br>Note : Please include your Institution name and contact number in the mail.</p>');
		$this->load->view('vhelp');
		$this->load->view('footer');

	}
	
	public function createcsv()

	{

		$this->headerSetup('How to create CSV',$this->containerHeight+90);
		$this->load->view('vcreatecsv');
		$this->load->view('footer');

	}
	
	public function examstatus()
	{
		$this->headerSetup('Exam Status',$this->containerHeight+30);
		
		
		$result = $this->user->getExamStatus($this->session->userdata('client_id'));

		if($result)
			{
				$examsts = '';
				$sno = 0;
				foreach($result as $row)
					{
					$sno++;
					$examsts = $examsts."<tr><td>".$sno."</td><td>".$row->exam_name."</td><td>".$row->cdate."</td><td>".$row->status_msg."</td></tr>";
					} 
				
				$data = array('examstatus' => $examsts);	
				$this->load->view('vexamstatus',$data);
			}
		else
			{
				$data = array('message' => '<div class="alert alert-danger" role="alert">Error : No exam has been created. Please create an exam.</div>');
				$this->load->view('vmessage',$data);
			}

		$this->load->view('footer');			 
		
	}

}

