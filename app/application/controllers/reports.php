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

	  			$studentlist ='';
	  			//get student list
	  			$result = $this->user->getStudentsList($this->session->userdata('client_id'));
	  			foreach($result as $row)
	 			{
	   			$studentlist = "<option value=".$row->student_id.">".$row->student_name." (".$row->student_id.")</option>".$studentlist;
	  			} 

	  			$subjectlist ='';
	  			//get subject list
	  			$result = $this->user->getSubjectsList($this->session->userdata('client_id'));
	  			foreach($result as $row)
	 			{
	   			$subjectlist = "<option value=".$row->subject_code.">".$row->subject_name." (".$row->subject_code.")</option>".$subjectlist;
	  			}

	  			$examdata = array('examlist' => $examlist,'studentlist' => $studentlist,'subjectlist' => $subjectlist);
	  			$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Generate Report','container_height' => 280 );
				$this->load->view('header',$headerdata);
				$this->load->view('vgeneratereport',$examdata);
			}
			else
			{
				$headerdata = array('usertype' => $this->session->userdata('user_type'), 'client_name' => $client_name ,'title' => 'Generate Report','container_height' => 130 );
				$this->load->view('header',$headerdata);
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">Error : There are no exams with results uploaded.</div>');
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
			case 3:
			$this->studentRankListReport($this->input->post('examid'),$this->input->post('levelid'),$client_name);
			break;
			case 5:
			$this->subjectRankListReport($this->input->post('examid'),$this->input->post('levelid'),$client_name);
			break;
			case 6:
			$this->studentMarkListReport($this->input->post('examid'),$this->input->post('levelid'),$client_name);
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
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}

	
	}



/*--------------------------------- STUDENT RANK LIST REPORT ---------------------------------*/

	private function studentRankListReport($examid,$levelid,$client_name)

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
			$output = $this->analysis->studentRankListReportCollege($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:20%;\">Rank</th>
			<th>Student ID</th><th>Student Name</th><th>Class</th>
			<th>Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->rank."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Student Rank List Report','level' => 'College Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
			$output = $this->analysis->studentRankListReportDept($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">Dept</th><th style=\"width:10%;\">Rank</th>
			<th>Student ID</th><th>Student Name</th><th>Class</th>
			<th>Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->rank."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Student Rank List Report','level' => 'Department Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
			$output = $this->analysis->studentRankListReportYear($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">Year</th><th style=\"width:10%;\">Rank</th>
			<th>Student ID</th><th>Student Name</th><th>Class</th>
			<th>Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->year."</td><td>".$row->rank."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Student Rank List Report','level' => 'Year Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
			$output = $this->analysis->studentRankListReportDeptYear($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">Dept</th><th style=\"width:10%;\">Year</th><th style=\"width:10%;\">Rank</th>
			<th>Student ID</th><th>Student Name</th><th>Class</th>
			<th>Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->year."</td><td>".$row->rank."</td>
					<td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Student Rank List Report','level' => 'Department and Year Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
			$output = $this->analysis->studentRankListReportClass($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:15%;\">Class</th><th style=\"width:10%;\">Rank</th>
			<th>Student ID</th><th>Student Name</th>
			<th>Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->rank."</td>
					<td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->percentage."</td>
					</tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Student Rank List Report','level' => 'Class Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}

	
	}


/*--------------------------------- SUBJECT RANK LIST REPORT ---------------------------------*/

	private function subjectRankListReport($examid,$levelid,$client_name)

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
			$output = $this->analysis->subjectRankListReportCollege($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">Rank</th>
			<th>Subject Code</th><th>Subject Name</th><th>Staff Name</th><th>Class</th>
			<th>Pass Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->rank."</td><td>".$row->subject_code."</td>
					<td>".$row->subject_name."</td><td>".$row->staff_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Subject Rank List Report','level' => 'College Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
			$output = $this->analysis->subjectRankListReportDept($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">Dept</th><th style=\"width:10%;\">Rank</th>
			<th>Subject Code</th><th>Subject Name</th><th>Staff Name</th><th>Class</th>
			<th>Pass Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->rank."</td><td>".$row->subject_code."</td>
					<td>".$row->subject_name."</td><td>".$row->staff_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Subject Rank List Report','level' => 'Department Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
			$output = $this->analysis->subjectRankListReportYear($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">Year</th><th style=\"width:10%;\">Rank</th>
			<th>Subject Code</th><th>Subject Name</th><th>Staff Name</th><th>Class</th>
			<th>Pass Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->year."</td><td>".$row->rank."</td><td>".$row->subject_code."</td>
					<td>".$row->subject_name."</td><td>".$row->staff_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Subject Rank List Report','level' => 'Year Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
			$output = $this->analysis->subjectRankListReportDeptYear($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">Dept</th>
			<th style=\"width:10%;\">Year</th><th style=\"width:10%;\">Rank</th>
			<th>Subject Code</th><th>Subject Name</th><th>Staff Name</th><th>Class</th>
			<th>Pass Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->year."</td>
					<td>".$row->rank."</td><td>".$row->subject_code."</td>
					<td>".$row->subject_name."</td><td>".$row->staff_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Subject Rank List Report','level' => 'Department and Year Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
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
			$output = $this->analysis->subjectRankListReportClass($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:15%;\">Class</th>
			<th style=\"width:10%;\">Rank</th>
			<th>Subject Code</th><th>Subject Name</th><th>Staff Name</th>
			<th>Pass Percentage</th>";

			$opt_data = '';
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->rank."</td><td>".$row->subject_code."</td>
					<td>".$row->subject_name."</td><td>".$row->staff_name."</td>
					<td>".$row->percentage."</td>
					</tr>";
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Subject Rank List Report','level' => 'Class Level');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}

	
	}



/*--------------------------------- STUDENT MARK LIST REPORT ---------------------------------*/

	private function studentMarkListReport($examid,$levelid,$client_name)

	{

		$result = $this->user->getExamName($examid);
		foreach($result as $row)
		{
			$exam_name= $row->exam_name;
		} 
		
		$filterQry = "where 1=1";

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

		if($this->input->post('studentfilter')<>99)
		{
			$filterQry = $filterQry." and student_id='".$this->input->post('studentfilter')."'";
		}

		if($this->input->post('resultfilter')<>99)
		{
			$filterQry = $filterQry." and result='".$this->input->post('resultfilter')."'";
		}

			$output = $this->analysis->studentMarkListReport($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">S.No</th><th style=\"width:10%;\">Class</th>
			<th style=\"width:10%;\">Student ID</th>
			<th style=\"width:15%;\">Student Name</th><th style=\"width:10%;\">Subject Code</th>
			<th style=\"width:15%;\">Subject Name</th><th style=\"width:10%;\">Total Marks</th>
			<th style=\"width:10%;\">Marks Obtained</th><th style=\"width:15%;\">Result</th>";

			$opt_data = '';
			$sno = 0;
			foreach($output as $row)
				{
					$sno++;
					if($row->result==1)
					{
					$opt_data = $opt_data."<tr><td>".$sno."</td><td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->student_id."</td><td>".$row->student_name."</td>
					<td>".$row->subject_code."</td><td>".$row->subject_name."</td>
					<td>".$row->total_marks."</td><td>".$row->marks_obtained."</td><td style='background:green;color:white;'>Pass</td>
					</tr>";
					}
					else
					{
					$opt_data = $opt_data."<tr><td>".$sno."</td><td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->student_id."</td><td>".$row->student_name."</td>
					<td>".$row->subject_code."</td><td>".$row->subject_name."</td>
					<td>".$row->total_marks."</td><td>".$row->marks_obtained."</td><td style='background:#DC143C;color:white;'>Failed</td>
					</tr>";
					}
				
				} 
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Student Mark List Report','level' => 'N/A');
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:5%;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}


	
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */