<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {


	public function __construct()
    {
      	parent::__construct();
        // Your own constructor code
		$this->load->model('user','',TRUE);
        $this->load->model('analysis','',TRUE);
        $this->load->model('common','',TRUE);
        if(!$this->session->userdata('client_id'))
        {
        	redirect('login');
        }
		$this->containerHeight = 160;
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

    private function alphanumericVal($inp)
	{
		
		if(preg_match('/^[a-zA-Z0-9 ]+$/', $inp))
		//check if only alphanumeric,numbers and spaces are present	
		{
			return TRUE;
		}
		else
		{		
     		return FALSE;
		}		
	}

/*----------------------------------------------  Default  ----------------------------------------------*/

	public function index()
	{
		redirect('exams');
	}
	
	public function passpercentage()
	{
		$this->headerSetup('Generate Report',$this->containerHeight+120);
		$result = $this->user->getExamListWithData($this->session->userdata('client_id'));
		if($result)
		{
			$examlist = '';
			foreach($result as $row)
			{
			$examlist = "<option selected value=".$row->exam_id.">".$row->exam_name."</option>".$examlist;
			} 

			$data = array('examlist' => $examlist,'reportID' => '1','deptHtml' => $this->common->getDeptNames());
			$this->load->view('vgeneratereport',$data);
		}
		else
		{

			$data = array('message' => '<div style="margin-top:0;" class="alert alert-danger" role="alert">Error : There are no exams with results uploaded.</div>
			<script>$(".containerdiv").height(\''.$this->containerHeight.'%\');</script>');
			$this->load->view('vmessage',$data);
		}
		$this->load->view('footer');	
		
	}
	
	public function overalltopper()
	{
		$this->headerSetup('Generate Report',$this->containerHeight+120);
		$result = $this->user->getExamListWithData($this->session->userdata('client_id'));
		if($result)
		{
			$examlist = '';
			foreach($result as $row)
			{
			$examlist = "<option selected value=".$row->exam_id.">".$row->exam_name."</option>".$examlist;
			} 

			$data = array('examlist' => $examlist,'reportID' => '2','deptHtml' => $this->common->getDeptNames());
			$this->load->view('vgeneratereport',$data);
		}
		else
		{

			$data = array('message' => '<div style="margin-top:0;" class="alert alert-danger" role="alert">Error : There are no exams with results uploaded.</div>
			<script>$(".containerdiv").height(\''.$this->containerHeight.'%\');</script>');
			$this->load->view('vmessage',$data);
		}
		$this->load->view('footer');	
		
	}
	public function studentranklist()
	{
		$this->headerSetup('Generate Report',$this->containerHeight+120);
		$result = $this->user->getExamListWithData($this->session->userdata('client_id'));
		if($result)
		{
			$examlist = '';
			foreach($result as $row)
			{
			$examlist = "<option selected value=".$row->exam_id.">".$row->exam_name."</option>".$examlist;
			} 

			$data = array('examlist' => $examlist,'reportID' => '3','deptHtml' => $this->common->getDeptNames());
			$this->load->view('vgeneratereport',$data);
		}
		else
		{

			$data = array('message' => '<div style="margin-top:0;" class="alert alert-danger" role="alert">Error : There are no exams with results uploaded.</div>
			<script>$(".containerdiv").height(\''.$this->containerHeight.'%\');</script>');
			$this->load->view('vmessage',$data);
		}
		$this->load->view('footer');	
		
	}
	public function subjectranklist()
	{
		$this->headerSetup('Generate Report',$this->containerHeight+120);
		$result = $this->user->getExamListWithData($this->session->userdata('client_id'));
		if($result)
		{
			$examlist = '';
			foreach($result as $row)
			{
			$examlist = "<option selected value=".$row->exam_id.">".$row->exam_name."</option>".$examlist;
			} 

			$data = array('examlist' => $examlist,'reportID' => '4','deptHtml' => $this->common->getDeptNames());
			$this->load->view('vgeneratereport',$data);
		}
		else
		{

			$data = array('message' => '<div style="margin-top:0;" class="alert alert-danger" role="alert">Error : There are no exams with results uploaded.</div>
			<script>$(".containerdiv").height(\''.$this->containerHeight.'%\');</script>');
			$this->load->view('vmessage',$data);
		}
		$this->load->view('footer');	
		
	}
	public function studentmarklist()
	{
		$this->headerSetup('Generate Report',$this->containerHeight+150);
		$result = $this->user->getExamListWithData($this->session->userdata('client_id'));
		if($result)
		{
			$examlist = '';
			foreach($result as $row)
			{
			$examlist = "<option selected value=".$row->exam_id.">".$row->exam_name."</option>".$examlist;
			} 

			$data = array('examlist' => $examlist,'reportID' => '5','deptHtml' => $this->common->getDeptNames());
			$this->load->view('vgeneratereport',$data);
		}
		else
		{

			$data = array('message' => '<div style="margin-top:0;" class="alert alert-danger" role="alert">Error : There are no exams with results uploaded.</div>
			<script>$(".containerdiv").height(\''.$this->containerHeight.'%\');</script>');
			$this->load->view('vmessage',$data);
		}
		$this->load->view('footer');	
		
	}
	public function subjecttopper()
	{
		$this->headerSetup('Generate Report',$this->containerHeight+120);
		$result = $this->user->getExamListWithData($this->session->userdata('client_id'));
		if($result)
		{
			$examlist = '';
			foreach($result as $row)
			{
			$examlist = "<option selected value=".$row->exam_id.">".$row->exam_name."</option>".$examlist;
			} 

			$data = array('examlist' => $examlist,'reportID' => '6','deptHtml' => $this->common->getDeptNames());
			$this->load->view('vgeneratereport',$data);
		}
		else
		{

			$data = array('message' => '<div style="margin-top:0;" class="alert alert-danger" role="alert">Error : There are no exams with results uploaded.</div>
			<script>$(".containerdiv").height(\''.$this->containerHeight.'%\');</script>');
			$this->load->view('vmessage',$data);
		}
		$this->load->view('footer');	
		
	}

	public function generate()
	{

	
		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
			{

			$client_name= $row->client_name;
			} 
		
		$this->load->helper(array('form'));
		$result = $this->user->getExamListWithData($this->session->userdata('client_id'));
			if($result)
			{
				$examlist = '';
				foreach($result as $row)
	 			{
	   			$examlist = "<option selected value=".$row->exam_id.">".$row->exam_name."</option>".$examlist;
	  			} 

	  			$examdata = array('examlist' => $examlist);
	  			$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Generate Report','container_height' => 280 );
				$this->load->view('header',$headerdata);
				$this->load->view('vgeneratereport',$examdata);
			}
			else
			{
				$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Generate Report','container_height' => 100,'user_name' => $this->session->userdata('user_name') );
				$this->load->view('header',$headerdata);
				$statusdata = array('message' => '<div style="margin-top:0;" class="alert alert-danger" role="alert">Error : There are no exams with results uploaded.</div>');
				$this->load->view('vmessage',$statusdata);
			}
		$this->load->view('footer');		
					
						
	}

	public function output()
	{

		if(!isset($_POST['submit']))
		{
			redirect('reports/generate');
		}
		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
			{

			$client_name= $row->client_name;
			} 
		$headerdata = array('client_name' => $client_name ,'title' => 'Report Output');
		$this->load->view('rptheader',$headerdata);
		$reportId = $this->input->post('reportid');
		

		switch($reportId)
		{
			case 1:
			$this->load->model('rptpasspercentage','',TRUE);
			$this->rptpasspercentage->getReport($this->input->post('examid'),$this->input->post('levelid'),$client_name);
			break;
			case 2:
			$this->load->model('rptoveralltopper','',TRUE);
			$this->rptoveralltopper->getReport($this->input->post('examid'),$this->input->post('levelid'),$client_name);
			break;
			case 3:
			$this->load->model('rptstudentranklist','',TRUE);
			$this->rptstudentranklist->getReport($this->input->post('examid'),$this->input->post('levelid'),$client_name);
			break;
			case 4:
			$this->load->model('rptsubjectranklist','',TRUE);
			$this->rptsubjectranklist->getReport($this->input->post('examid'),$this->input->post('levelid'),$client_name);
			break;
			case 5:
			$this->load->model('rptstudentmarklist','',TRUE);
			$this->rptstudentmarklist->getReport($this->input->post('examid'),$client_name);
			break;
			case 6:
			$this->load->model('rptsubjecttopper','',TRUE);
			$this->rptsubjecttopper->getReport($this->input->post('examid'),$client_name);
			break;
		}

		$this->load->view('footer');
	}

}
