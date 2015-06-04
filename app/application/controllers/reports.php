<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {


	public function __construct()
    {
      	parent::__construct();
        // Your own constructor code
		$this->load->model('user','',TRUE);
        $this->load->model('analysis','',TRUE);
        if(!$this->session->userdata('client_id'))
        {
        	redirect('login');
        }
    }

/*----------------------------------------------  Default  ----------------------------------------------*/

	public function index()
	{
		redirect('exams');
	}

/*----------------------------------------------  College Level  ----------------------------------------------*/

	public function college()
	{

	
		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
			{

			$client_name= $row->client_name;
			} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Add new exam','container_height' => 170 );
		$this->load->view('header',$headerdata);
		$this->load->helper(array('form'));
		$this->load->view('vcollegelevel');
		$this->load->view('footer');		
					
						
	}

	public function generate()
	{

	
		$result = $this->user->getClientName($this->session->userdata('client_id'));
		foreach($result as $row)
			{

			$client_name= $row->client_name;
			} 
		$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Generate Report','container_height' => 270 );
		$this->load->view('header',$headerdata);
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
				$this->load->view('vgeneratereport',$examdata);
			}
			else
			{
				$statusdata = array('message' => '<div class="alert alert-danger" role="alert">Error : There are no exams with results uploaded.</div>');
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
		$filterQry = "where 1=1";
		if($this->input->post('deptfilter')<>1)
		{
			$filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
		}
		if($this->input->post('yearfilter')<>1)
		{
			$filterQry = $filterQry." and year=".$this->input->post('yearfilter');
		}
		if($this->input->post('sectionfilter')<>1)
		{
			$filterQry = $filterQry." and section='".$this->input->post('sectionfilter')."'";
		}
		if($this->input->post('subjectfilter')<>1)
		{
			$filterQry = $filterQry." and subject_code='".$this->input->post('subjectfilter')."'";
		}
		switch($reportId)
		{
			case 1:
			$this->passPercentageReport($this->input->post('examid'),$this->input->post('levelid'),$filterQry);
			break;
			case 2:
			break;
		}

		$this->load->view('footer');
	}

	private function passPercentageReport($examid,$levelid,$filterQry)

	{

		$result = $this->user->getExamName($examid);
		foreach($result as $row)
		{
			$exam_name= $row->exam_name;
		} 
		if($levelid==1)
		{
			
			$output = $this->analysis->passPercentageReportCollege($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:40%;\">College Name</th>
			<th style=\"white-space: nowrap;\">No of Students Attempted</th>
			<th>No of Students Passed</th><th>Overall Pass Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->client_name."</td><td>".$row->student_cnt."</td>
					<td>".$row->student_pass_cnt."</td><td>".$row->pass_percentage."</td></tr>";
				
				} 
			}
			else
			{
				
			}

		}
		$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data);
		$this->load->view('vpasspercentrpt',$rptdata);
	}


	
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */