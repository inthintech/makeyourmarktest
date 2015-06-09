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
				$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Generate Report','container_height' => 130 );
				$this->load->view('header',$headerdata);
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
		

		switch($reportId)
		{
			case 1:
			$this->passPercentageReport($this->input->post('examid'),$this->input->post('levelid'),$client_name);
			break;
			case 2:
			$this->topperReport($this->input->post('examid'),$this->input->post('levelid'),$client_name);
			break;
		}

		$this->load->view('footer');
	}

	private function passPercentageReport($examid,$levelid,$client_name)

	{

		$result = $this->user->getExamName($examid);
		foreach($result as $row)
		{
			$exam_name= $row->exam_name;
		} 
		
		$filterQry = "where 1=1";

		/* College Level Report*/
		
		if($levelid==1)
		{
			
			/*
			if($this->input->post('deptfilter')<>99)
			{
				$filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
			}
			if($this->input->post('yearfilter')<>99)
			{
				$filterQry = $filterQry." and year=".$this->input->post('yearfilter');
			}
			if($this->input->post('sectionfilter')<>99)
			{
				$filterQry = $filterQry." and section='".$this->input->post('sectionfilter')."'";
			}
			if($this->input->post('subjectfilter')<>99)
			{
				$filterQry = $filterQry." and subject_code='".$this->input->post('subjectfilter')."'";
			}
			*/
			$output = $this->analysis->passPercentageReportCollege($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:40%;\">College Name</th>
			<th style=\"white-space: nowrap;\">No of Students Attempted</th>
			<th>No of Students Passed</th><th>Overall Pass Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$client_name."</td><td>".$row->student_cnt."</td>
					<td>".$row->student_pass_cnt."</td><td>".$row->pass_percentage."</td></tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Pass Percentage Report','level' => 'College Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<div class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or there are any filters applied. Please remove any unneccessary filter and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}
		
		/* Dept Level Report*/
		
		if($levelid==2)
		{
			
			
			if($this->input->post('deptfilter')<>99)
			{
				$filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
			}
			/*
			if($this->input->post('yearfilter')<>99)
			{
				$filterQry = $filterQry." and year=".$this->input->post('yearfilter');
			}
			if($this->input->post('sectionfilter')<>99)
			{
				$filterQry = $filterQry." and section='".$this->input->post('sectionfilter')."'";
			}
			if($this->input->post('subjectfilter')<>99)
			{
				$filterQry = $filterQry." and subject_code='".$this->input->post('subjectfilter')."'";
			}
			*/
			$output = $this->analysis->passPercentageReportDept($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:20%;\">Department Code</th>
			<th style=\"white-space: nowrap;\">No of Students Attempted</th>
			<th>No of Students Passed</th><th>Overall Pass Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->student_cnt."</td>
					<td>".$row->student_pass_cnt."</td><td>".$row->pass_percentage."</td></tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Pass Percentage Report','level' => 'Dept Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<div class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or there are any filters applied. Please remove any unneccessary filter and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}


		/* Year Level Report*/
		
		if($levelid==3)
		{
			
			
			if($this->input->post('yearfilter')<>99)
			{
				$filterQry = $filterQry." and year=".$this->input->post('yearfilter');
			}

			/*
			if($this->input->post('deptfilter')<>99)
			{
				$filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
			}
			
			
			if($this->input->post('sectionfilter')<>99)
			{
				$filterQry = $filterQry." and section='".$this->input->post('sectionfilter')."'";
			}
			if($this->input->post('subjectfilter')<>99)
			{
				$filterQry = $filterQry." and subject_code='".$this->input->post('subjectfilter')."'";
			}
			*/
			$output = $this->analysis->passPercentageReportYear($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:20%;\">Year</th>
			<th style=\"white-space: nowrap;\">No of Students Attempted</th>
			<th>No of Students Passed</th><th>Overall Pass Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->year."</td><td>".$row->student_cnt."</td>
					<td>".$row->student_pass_cnt."</td><td>".$row->pass_percentage."</td></tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Pass Percentage Report','level' => 'Year Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<div class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or there are any filters applied. Please remove any unneccessary filter and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}

		/* Department Year Level Report*/
		
		if($levelid==5)
		{
			
			
			if($this->input->post('deptfilter')<>99)
			{
				$filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
			}

			if($this->input->post('yearfilter')<>99)
			{
				$filterQry = $filterQry." and year=".$this->input->post('yearfilter');
			}

			/*
			
			
			
			if($this->input->post('sectionfilter')<>99)
			{
				$filterQry = $filterQry." and section='".$this->input->post('sectionfilter')."'";
			}
			if($this->input->post('subjectfilter')<>99)
			{
				$filterQry = $filterQry." and subject_code='".$this->input->post('subjectfilter')."'";
			}
			*/
			$output = $this->analysis->passPercentageReportDeptYear($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:20%;\">Dept</th><th style=\"width:20%;\">Year</th>
			<th style=\"white-space: nowrap;\">No of Students Attempted</th>
			<th>No of Students Passed</th><th>Overall Pass Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->year."</td><td>".$row->student_cnt."</td>
					<td>".$row->student_pass_cnt."</td><td>".$row->pass_percentage."</td></tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Pass Percentage Report','level' => 'Department and Year Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<div class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or there are any filters applied. Please remove any unneccessary filter and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}

		/* Class Level Report*/
		
		if($levelid==4)
		{
			
			
			if($this->input->post('deptfilter')<>99)
			{
				$filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
			}

			if($this->input->post('yearfilter')<>99)
			{
				$filterQry = $filterQry." and year=".$this->input->post('yearfilter');
			}

			if($this->input->post('sectionfilter')<>99)
			{
				$filterQry = $filterQry." and section='".$this->input->post('sectionfilter')."'";
			}

			/*
			
			
			
			if($this->input->post('sectionfilter')<>99)
			{
				$filterQry = $filterQry." and section='".$this->input->post('sectionfilter')."'";
			}
			if($this->input->post('subjectfilter')<>99)
			{
				$filterQry = $filterQry." and subject_code='".$this->input->post('subjectfilter')."'";
			}
			*/
			$output = $this->analysis->passPercentageReportClass($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">Dept</th><th style=\"width:10%;\">Year</th><th style=\"width:10%;\">Section</th>
			<th style=\"white-space: nowrap;\">No of Students Attempted</th>
			<th>No of Students Passed</th><th>Overall Pass Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->year."</td><td>".$row->section."</td><td>".$row->student_cnt."</td>
					<td>".$row->student_pass_cnt."</td><td>".$row->pass_percentage."</td></tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Pass Percentage Report','level' => 'Class Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<div class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or there are any filters applied. Please remove any unneccessary filter and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}
		
	}

/*--------------------------------- TOPPER REPORT ---------------------------------*/

	private function topperReport($examid,$levelid,$client_name)

	{

		$result = $this->user->getExamName($examid);
		foreach($result as $row)
		{
			$exam_name= $row->exam_name;
		} 
		
		$filterQry = "where 1=1 and rank=1";

		/* College Level Report*/
		
		if($levelid==1)
		{
			
			/*
			if($this->input->post('deptfilter')<>99)
			{
				$filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
			}
			if($this->input->post('yearfilter')<>99)
			{
				$filterQry = $filterQry." and year=".$this->input->post('yearfilter');
			}
			if($this->input->post('sectionfilter')<>99)
			{
				$filterQry = $filterQry." and section='".$this->input->post('sectionfilter')."'";
			}
			if($this->input->post('subjectfilter')<>99)
			{
				$filterQry = $filterQry." and subject_code='".$this->input->post('subjectfilter')."'";
			}
			*/
			$output = $this->analysis->topperReportCollege($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:40%;\">College Name</th>
			<th>Student ID</th><th>Student Name</th><th>Class</th>
			<th>Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$client_name."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Topper Report','level' => 'College Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<div class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or there are any filters applied. Please remove any unneccessary filter and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}
		

/* Dept Level Report*/
		
		if($levelid==2)
		{
			
			if($this->input->post('deptfilter')<>99)
			{
				$filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
			}

			/*
			
			if($this->input->post('yearfilter')<>99)
			{
				$filterQry = $filterQry." and year=".$this->input->post('yearfilter');
			}
			if($this->input->post('sectionfilter')<>99)
			{
				$filterQry = $filterQry." and section='".$this->input->post('sectionfilter')."'";
			}
			if($this->input->post('subjectfilter')<>99)
			{
				$filterQry = $filterQry." and subject_code='".$this->input->post('subjectfilter')."'";
			}
			*/
			$output = $this->analysis->topperReportDept($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">Dept</th>
			<th>Student ID</th><th>Student Name</th><th>Class</th>
			<th>Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Topper Report','level' => 'Department Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<div class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or there are any filters applied. Please remove any unneccessary filter and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}

/* Year Level Report*/
		
		if($levelid==3)
		{
			
			if($this->input->post('yearfilter')<>99)
			{
				$filterQry = $filterQry." and year=".$this->input->post('yearfilter');
			}

			/*

			if($this->input->post('deptfilter')<>99)
			{
				$filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
			}
			
			
			if($this->input->post('sectionfilter')<>99)
			{
				$filterQry = $filterQry." and section='".$this->input->post('sectionfilter')."'";
			}
			if($this->input->post('subjectfilter')<>99)
			{
				$filterQry = $filterQry." and subject_code='".$this->input->post('subjectfilter')."'";
			}
			*/
			$output = $this->analysis->topperReportYear($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">Year</th>
			<th>Student ID</th><th>Student Name</th><th>Class</th>
			<th>Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->year."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Topper Report','level' => 'Year Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<div class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or there are any filters applied. Please remove any unneccessary filter and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}


/* Department Year Level Report*/
		
		if($levelid==5)
		{
			

			if($this->input->post('deptfilter')<>99)
			{
				$filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
			}

			if($this->input->post('yearfilter')<>99)
			{
				$filterQry = $filterQry." and year=".$this->input->post('yearfilter');
			}
			
			/*
			
			if($this->input->post('sectionfilter')<>99)
			{
				$filterQry = $filterQry." and section='".$this->input->post('sectionfilter')."'";
			}
			if($this->input->post('subjectfilter')<>99)
			{
				$filterQry = $filterQry." and subject_code='".$this->input->post('subjectfilter')."'";
			}
			*/
			$output = $this->analysis->topperReportDeptYear($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">Dept</th><th style=\"width:10%;\">Year</th>
			<th>Student ID</th><th>Student Name</th><th>Class</th>
			<th>Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->year."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Topper Report','level' => 'Department and Year Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<div class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or there are any filters applied. Please remove any unneccessary filter and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}

/* Class Level Report*/
		
		if($levelid==4)
		{
			

			if($this->input->post('deptfilter')<>99)
			{
				$filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
			}

			if($this->input->post('yearfilter')<>99)
			{
				$filterQry = $filterQry." and year=".$this->input->post('yearfilter');
			}
			
			
			
			if($this->input->post('sectionfilter')<>99)
			{
				$filterQry = $filterQry." and section='".$this->input->post('sectionfilter')."'";
			}
			/*
			if($this->input->post('subjectfilter')<>99)
			{
				$filterQry = $filterQry." and subject_code='".$this->input->post('subjectfilter')."'";
			}
			*/
			$output = $this->analysis->topperReportClass($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:15%;\">Class</th>
			<th>Student ID</th><th>Student Name</th>
			<th>Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code." ".$row->year." ".$row->section."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->percentage."</td>
					</tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Topper Report','level' => 'Class Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<div class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or there are any filters applied. Please remove any unneccessary filter and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}

	
	}

	
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */