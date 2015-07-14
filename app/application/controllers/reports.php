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
				$statusdata = array('message' => '<div style="margin-top:5%;" class="alert alert-danger" role="alert">Error : There are no exams with results uploaded.</div>');
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
			$total_student_count = 0;
			$total_pass_count = 0;
			$total_fail_count = 0;
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$client_name."</td><td>".$row->student_cnt."</td>
					<td>".$row->student_pass_cnt."</td><td>".$row->pass_percentage."</td></tr>";
					$total_student_count = $row->student_cnt;
					$total_pass_count = $row->student_pass_cnt;
					$total_fail_count = $total_student_count - $total_pass_count;
				} 

			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="300" height="300"/></div>';
			$chart = $chart.'<script>

		var Data = [
				{
					value: '.$total_fail_count.',
					color:"#8B0000",
					highlight: "#FF5A5E",
					label: "Failed"
				},
				{
					value: '.$total_pass_count.',
					color: "#006400",
					highlight: "#6E8B3D",
					label: "Passed"
				}
			];

			window.onload = function(){
				var ctx = document.getElementById("chart-area").getContext("2d");
				window.myChart = new Chart(ctx).Pie(Data);
			};
			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>
			<div style="background:#006400;"></div>
			<span style="margin-left:5%;">Students Passed</span>
			</li>
			<li>
			<div style="background:#8B0000;"></div>
			<span style="margin-left:5%;">Students Failed</span>
			</li>
			</ul>
			</div>';
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Pass Percentage Report','level' => 'College Level','chart' => $chart);
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
			$labels = '';
			$total_cnt_data = '';
			$pass_cnt_data = '';
			$cnt = 0;
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->student_cnt."</td>
					<td>".$row->student_pass_cnt."</td><td>".$row->pass_percentage."</td></tr>";
					if($cnt==0)
					{
						$labels = '"'.$row->dept_code.'"';
						$total_cnt_data = $row->student_cnt;
						$pass_cnt_data = $row->student_pass_cnt;

					}
					else
					{
						$labels = $labels.',"'.$row->dept_code.'"';
						$total_cnt_data = $total_cnt_data.','.$row->student_cnt;
						$pass_cnt_data = $pass_cnt_data.','.$row->student_pass_cnt;

					}
					$cnt=1;
					
				} 

			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ['.$labels.'],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(220,220,220,0.8)",
				highlightFill: "rgba(220,220,220,0.75)",
				highlightStroke: "rgba(220,220,220,1)",
				data : ['.$total_cnt_data.']
			},
			{
				fillColor : "#006400",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$pass_cnt_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Department</li>
			<li>Y axis : Students</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">Students Attempted</span>
			</li>
			<li>
			<div style="background:#006400;"></div>
			<span style="margin-left:5%;">Students Passed</span>
			</li>
			</ul>
			</div>';
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Pass Percentage Report','level' => 'Dept Level','chart' => $chart);
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
			$labels = '';
			$total_cnt_data = '';
			$pass_cnt_data = '';
			$cnt = 0;

			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->year."</td><td>".$row->student_cnt."</td>
					<td>".$row->student_pass_cnt."</td><td>".$row->pass_percentage."</td></tr>";

					if($cnt==0)
					{
						$labels = '"'.$row->year.'"';
						$total_cnt_data = $row->student_cnt;
						$pass_cnt_data = $row->student_pass_cnt;

					}
					else
					{
						$labels = $labels.',"'.$row->year.'"';
						$total_cnt_data = $total_cnt_data.','.$row->student_cnt;
						$pass_cnt_data = $pass_cnt_data.','.$row->student_pass_cnt;

					}
					$cnt=1;
				
				} 

			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ['.$labels.'],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(220,220,220,0.8)",
				highlightFill: "rgba(220,220,220,0.75)",
				highlightStroke: "rgba(220,220,220,1)",
				data : ['.$total_cnt_data.']
			},
			{
				fillColor : "#006400",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$pass_cnt_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Year</li>
			<li>Y axis : Students</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">Students Attempted</span>
			</li>
			<li>
			<div style="background:#006400;"></div>
			<span style="margin-left:5%;">Students Passed</span>
			</li>
			</ul>
			</div>';
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Pass Percentage Report','level' => 'Year Level','chart' => $chart);
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
			$labels = '';
			$total_cnt_data = '';
			$pass_cnt_data = '';
			$cnt = 0;

			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->year."</td><td>".$row->student_cnt."</td>
					<td>".$row->student_pass_cnt."</td><td>".$row->pass_percentage."</td></tr>";

					if($cnt==0)
					{
						$labels = '"'.$row->dept_code.' '.$row->year.'"';
						$total_cnt_data = $row->student_cnt;
						$pass_cnt_data = $row->student_pass_cnt;

					}
					else
					{
						$labels = $labels.',"'.$row->dept_code.' '.$row->year.'"';
						$total_cnt_data = $total_cnt_data.','.$row->student_cnt;
						$pass_cnt_data = $pass_cnt_data.','.$row->student_pass_cnt;

					}
					$cnt=1;
				
				} 

			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ['.$labels.'],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(220,220,220,0.8)",
				highlightFill: "rgba(220,220,220,0.75)",
				highlightStroke: "rgba(220,220,220,1)",
				data : ['.$total_cnt_data.']
			},
			{
				fillColor : "#006400",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$pass_cnt_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Dept and Year</li>
			<li>Y axis : Students</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">Students Attempted</span>
			</li>
			<li>
			<div style="background:#006400;"></div>
			<span style="margin-left:5%;">Students Passed</span>
			</li>
			</ul>
			</div>';
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Pass Percentage Report','level' => 'Department and Year Level','chart' => $chart);
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
			$labels = '';
			$total_cnt_data = '';
			$pass_cnt_data = '';
			$cnt = 0;

			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->year."</td><td>".$row->section."</td><td>".$row->student_cnt."</td>
					<td>".$row->student_pass_cnt."</td><td>".$row->pass_percentage."</td></tr>";

				if($cnt==0)
					{
						$labels = '"'.$row->dept_code.' '.$row->year.' '.$row->section.'"';
						$total_cnt_data = $row->student_cnt;
						$pass_cnt_data = $row->student_pass_cnt;

					}
					else
					{
						$labels = $labels.',"'.$row->dept_code.' '.$row->year.' '.$row->section.'"';
						$total_cnt_data = $total_cnt_data.','.$row->student_cnt;
						$pass_cnt_data = $pass_cnt_data.','.$row->student_pass_cnt;

					}
					$cnt=1;
				
				} 

			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ['.$labels.'],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(220,220,220,0.8)",
				highlightFill: "rgba(220,220,220,0.75)",
				highlightStroke: "rgba(220,220,220,1)",
				data : ['.$total_cnt_data.']
			},
			{
				fillColor : "#006400",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$pass_cnt_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Dept and Year</li>
			<li>Y axis : Students</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">Students Attempted</span>
			</li>
			<li>
			<div style="background:#006400;"></div>
			<span style="margin-left:5%;">Students Passed</span>
			</li>
			</ul>
			</div>';
			
			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Pass Percentage Report','level' => 'Class Level','chart' => $chart);
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
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;

			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$client_name."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";

					if($row->percentage>=0&&$row->percentage<=10)
					{
						$set1++;

					}
					if($row->percentage>=11&&$row->percentage<=20)
					{
						$set2++;
					}
					if($row->percentage>=21&&$row->percentage<=30)
					{
						$set3++;
					}
					if($row->percentage>=31&&$row->percentage<=40)
					{
						$set4++;
					}
					if($row->percentage>=41&&$row->percentage<=50)
					{
						$set5++;
					}
					if($row->percentage>=51&&$row->percentage<=60)
					{
						$set6++;
					}
					if($row->percentage>=61&&$row->percentage<=70)
					{
						$set7++;
					}
					if($row->percentage>=71&&$row->percentage<=80)
					{
						$set8++;
					}
					if($row->percentage>=81&&$row->percentage<=90)
					{
						$set9++;
					}
					if($row->percentage>=91&&$row->percentage<=100)
					{
						$set10++;
					}
					
				
				} 

			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Percentage</li>
			<li>Y axis : Students</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Students</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Topper Report','level' => 'College Level','chart' => $chart);
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
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;

			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";

					if($row->percentage>=0&&$row->percentage<=10)
					{
						$set1++;

					}
					if($row->percentage>=11&&$row->percentage<=20)
					{
						$set2++;
					}
					if($row->percentage>=21&&$row->percentage<=30)
					{
						$set3++;
					}
					if($row->percentage>=31&&$row->percentage<=40)
					{
						$set4++;
					}
					if($row->percentage>=41&&$row->percentage<=50)
					{
						$set5++;
					}
					if($row->percentage>=51&&$row->percentage<=60)
					{
						$set6++;
					}
					if($row->percentage>=61&&$row->percentage<=70)
					{
						$set7++;
					}
					if($row->percentage>=71&&$row->percentage<=80)
					{
						$set8++;
					}
					if($row->percentage>=81&&$row->percentage<=90)
					{
						$set9++;
					}
					if($row->percentage>=91&&$row->percentage<=100)
					{
						$set10++;
					}
				
				} 
			
			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Percentage</li>
			<li>Y axis : Students</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Students</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Topper Report','level' => 'Department Level','chart' => $chart);
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
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->year."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";

					if($row->percentage>=0&&$row->percentage<=10)
					{
						$set1++;

					}
					if($row->percentage>=11&&$row->percentage<=20)
					{
						$set2++;
					}
					if($row->percentage>=21&&$row->percentage<=30)
					{
						$set3++;
					}
					if($row->percentage>=31&&$row->percentage<=40)
					{
						$set4++;
					}
					if($row->percentage>=41&&$row->percentage<=50)
					{
						$set5++;
					}
					if($row->percentage>=51&&$row->percentage<=60)
					{
						$set6++;
					}
					if($row->percentage>=61&&$row->percentage<=70)
					{
						$set7++;
					}
					if($row->percentage>=71&&$row->percentage<=80)
					{
						$set8++;
					}
					if($row->percentage>=81&&$row->percentage<=90)
					{
						$set9++;
					}
					if($row->percentage>=91&&$row->percentage<=100)
					{
						$set10++;
					}

				
				} 
			
			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Percentage</li>
			<li>Y axis : Students</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Students</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Topper Report','level' => 'Year Level','chart' => $chart);
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
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;

			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->year."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";

					if($row->percentage>=0&&$row->percentage<=10)
					{
						$set1++;

					}
					if($row->percentage>=11&&$row->percentage<=20)
					{
						$set2++;
					}
					if($row->percentage>=21&&$row->percentage<=30)
					{
						$set3++;
					}
					if($row->percentage>=31&&$row->percentage<=40)
					{
						$set4++;
					}
					if($row->percentage>=41&&$row->percentage<=50)
					{
						$set5++;
					}
					if($row->percentage>=51&&$row->percentage<=60)
					{
						$set6++;
					}
					if($row->percentage>=61&&$row->percentage<=70)
					{
						$set7++;
					}
					if($row->percentage>=71&&$row->percentage<=80)
					{
						$set8++;
					}
					if($row->percentage>=81&&$row->percentage<=90)
					{
						$set9++;
					}
					if($row->percentage>=91&&$row->percentage<=100)
					{
						$set10++;
					}
				
				} 
			
			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Percentage</li>
			<li>Y axis : Students</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Students</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Topper Report','level' => 'Department and Year Level','chart' => $chart);
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
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code." ".$row->year." ".$row->section."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->percentage."</td>
					</tr>";

					if($row->percentage>=0&&$row->percentage<=10)
					{
						$set1++;

					}
					if($row->percentage>=11&&$row->percentage<=20)
					{
						$set2++;
					}
					if($row->percentage>=21&&$row->percentage<=30)
					{
						$set3++;
					}
					if($row->percentage>=31&&$row->percentage<=40)
					{
						$set4++;
					}
					if($row->percentage>=41&&$row->percentage<=50)
					{
						$set5++;
					}
					if($row->percentage>=51&&$row->percentage<=60)
					{
						$set6++;
					}
					if($row->percentage>=61&&$row->percentage<=70)
					{
						$set7++;
					}
					if($row->percentage>=71&&$row->percentage<=80)
					{
						$set8++;
					}
					if($row->percentage>=81&&$row->percentage<=90)
					{
						$set9++;
					}
					if($row->percentage>=91&&$row->percentage<=100)
					{
						$set10++;
					}
				
				} 
			
			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Percentage</li>
			<li>Y axis : Students</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Students</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Topper Report','level' => 'Class Level','chart' => $chart);
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
			
			
			if($this->input->post('studentfilter'))
			{
				if($this->alphanumericVal($this->input->post('studentfilter')))
				{
					$filterQry = $filterQry." and student_name like '%".$this->input->post('studentfilter')."%'";
				}
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
			$output = $this->analysis->studentRankListReportCollege($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:20%;\">Rank</th>
			<th>Student ID</th><th>Student Name</th><th>Class</th>
			<th>Percentage</th>";

			$opt_data = '';
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->rank."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";

					if($row->percentage>=0&&$row->percentage<=10)
					{
						$set1++;

					}
					if($row->percentage>=11&&$row->percentage<=20)
					{
						$set2++;
					}
					if($row->percentage>=21&&$row->percentage<=30)
					{
						$set3++;
					}
					if($row->percentage>=31&&$row->percentage<=40)
					{
						$set4++;
					}
					if($row->percentage>=41&&$row->percentage<=50)
					{
						$set5++;
					}
					if($row->percentage>=51&&$row->percentage<=60)
					{
						$set6++;
					}
					if($row->percentage>=61&&$row->percentage<=70)
					{
						$set7++;
					}
					if($row->percentage>=71&&$row->percentage<=80)
					{
						$set8++;
					}
					if($row->percentage>=81&&$row->percentage<=90)
					{
						$set9++;
					}
					if($row->percentage>=91&&$row->percentage<=100)
					{
						$set10++;
					}
				
				} 
				
			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Percentage</li>
			<li>Y axis : Students</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Students</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Student Rank List Report','level' => 'College Level','chart' => $chart);
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

			if($this->input->post('studentfilter'))
			{
				if($this->alphanumericVal($this->input->post('studentfilter')))
				{
					$filterQry = $filterQry." and student_name like '%".$this->input->post('studentfilter')."%'";
				}
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
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;
			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->rank."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";

					if($row->percentage>=0&&$row->percentage<=10)
					{
						$set1++;

					}
					if($row->percentage>=11&&$row->percentage<=20)
					{
						$set2++;
					}
					if($row->percentage>=21&&$row->percentage<=30)
					{
						$set3++;
					}
					if($row->percentage>=31&&$row->percentage<=40)
					{
						$set4++;
					}
					if($row->percentage>=41&&$row->percentage<=50)
					{
						$set5++;
					}
					if($row->percentage>=51&&$row->percentage<=60)
					{
						$set6++;
					}
					if($row->percentage>=61&&$row->percentage<=70)
					{
						$set7++;
					}
					if($row->percentage>=71&&$row->percentage<=80)
					{
						$set8++;
					}
					if($row->percentage>=81&&$row->percentage<=90)
					{
						$set9++;
					}
					if($row->percentage>=91&&$row->percentage<=100)
					{
						$set10++;
					}
				
				} 
			
			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Percentage</li>
			<li>Y axis : Students</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Students</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Student Rank List Report','level' => 'Department Level','chart' => $chart);
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

			if($this->input->post('studentfilter'))
			{
				if($this->alphanumericVal($this->input->post('studentfilter')))
				{
					$filterQry = $filterQry." and student_name like '%".$this->input->post('studentfilter')."%'";
				}
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
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;

			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->year."</td><td>".$row->rank."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";

					if($row->percentage>=0&&$row->percentage<=10)
					{
						$set1++;

					}
					if($row->percentage>=11&&$row->percentage<=20)
					{
						$set2++;
					}
					if($row->percentage>=21&&$row->percentage<=30)
					{
						$set3++;
					}
					if($row->percentage>=31&&$row->percentage<=40)
					{
						$set4++;
					}
					if($row->percentage>=41&&$row->percentage<=50)
					{
						$set5++;
					}
					if($row->percentage>=51&&$row->percentage<=60)
					{
						$set6++;
					}
					if($row->percentage>=61&&$row->percentage<=70)
					{
						$set7++;
					}
					if($row->percentage>=71&&$row->percentage<=80)
					{
						$set8++;
					}
					if($row->percentage>=81&&$row->percentage<=90)
					{
						$set9++;
					}
					if($row->percentage>=91&&$row->percentage<=100)
					{
						$set10++;
					}
				
				} 
			

			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Percentage</li>
			<li>Y axis : Students</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Students</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Student Rank List Report','level' => 'Year Level','chart' => $chart);
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
			
			if($this->input->post('studentfilter'))
			{
				if($this->alphanumericVal($this->input->post('studentfilter')))
				{
					$filterQry = $filterQry." and student_name like '%".$this->input->post('studentfilter')."%'";
				}
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
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;

			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->year."</td><td>".$row->rank."</td>
					<td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";

					if($row->percentage>=0&&$row->percentage<=10)
					{
						$set1++;

					}
					if($row->percentage>=11&&$row->percentage<=20)
					{
						$set2++;
					}
					if($row->percentage>=21&&$row->percentage<=30)
					{
						$set3++;
					}
					if($row->percentage>=31&&$row->percentage<=40)
					{
						$set4++;
					}
					if($row->percentage>=41&&$row->percentage<=50)
					{
						$set5++;
					}
					if($row->percentage>=51&&$row->percentage<=60)
					{
						$set6++;
					}
					if($row->percentage>=61&&$row->percentage<=70)
					{
						$set7++;
					}
					if($row->percentage>=71&&$row->percentage<=80)
					{
						$set8++;
					}
					if($row->percentage>=81&&$row->percentage<=90)
					{
						$set9++;
					}
					if($row->percentage>=91&&$row->percentage<=100)
					{
						$set10++;
					}
				
				} 
			
			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Percentage</li>
			<li>Y axis : Students</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Students</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Student Rank List Report','level' => 'Department and Year Level','chart' => $chart);
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

			if($this->input->post('studentfilter'))
			{
				if($this->alphanumericVal($this->input->post('studentfilter')))
				{
					$filterQry = $filterQry." and student_name like '%".$this->input->post('studentfilter')."%'";
				}
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
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;

			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->rank."</td>
					<td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->percentage."</td>
					</tr>";

					if($row->percentage>=0&&$row->percentage<=10)
					{
						$set1++;

					}
					if($row->percentage>=11&&$row->percentage<=20)
					{
						$set2++;
					}
					if($row->percentage>=21&&$row->percentage<=30)
					{
						$set3++;
					}
					if($row->percentage>=31&&$row->percentage<=40)
					{
						$set4++;
					}
					if($row->percentage>=41&&$row->percentage<=50)
					{
						$set5++;
					}
					if($row->percentage>=51&&$row->percentage<=60)
					{
						$set6++;
					}
					if($row->percentage>=61&&$row->percentage<=70)
					{
						$set7++;
					}
					if($row->percentage>=71&&$row->percentage<=80)
					{
						$set8++;
					}
					if($row->percentage>=81&&$row->percentage<=90)
					{
						$set9++;
					}
					if($row->percentage>=91&&$row->percentage<=100)
					{
						$set10++;
					}
				
				} 
			
			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Percentage</li>
			<li>Y axis : Students</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Students</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Student Rank List Report','level' => 'Class Level','chart' => $chart);
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

			if($this->input->post('subjectfilter'))
			{
				if($this->alphanumericVal($this->input->post('subjectfilter')))
				{
					$filterQry = $filterQry." and subject_name like '%".$this->input->post('subjectfilter')."%'";
				}
			}
			
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
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;

			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->rank."</td><td>".$row->subject_code."</td>
					<td>".$row->subject_name."</td><td>".$row->staff_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";


			if($row->percentage>=0&&$row->percentage<=10)
					{
						$set1++;

					}
					if($row->percentage>=11&&$row->percentage<=20)
					{
						$set2++;
					}
					if($row->percentage>=21&&$row->percentage<=30)
					{
						$set3++;
					}
					if($row->percentage>=31&&$row->percentage<=40)
					{
						$set4++;
					}
					if($row->percentage>=41&&$row->percentage<=50)
					{
						$set5++;
					}
					if($row->percentage>=51&&$row->percentage<=60)
					{
						$set6++;
					}
					if($row->percentage>=61&&$row->percentage<=70)
					{
						$set7++;
					}
					if($row->percentage>=71&&$row->percentage<=80)
					{
						$set8++;
					}
					if($row->percentage>=81&&$row->percentage<=90)
					{
						$set9++;
					}
					if($row->percentage>=91&&$row->percentage<=100)
					{
						$set10++;
					}
				
				} 
			
			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Percentage</li>
			<li>Y axis : Subjects</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Subjects</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Subject Rank List Report','level' => 'College Level','chart' => $chart);
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

			if($this->input->post('subjectfilter'))
			{
				if($this->alphanumericVal($this->input->post('subjectfilter')))
				{
					$filterQry = $filterQry." and subject_name like '%".$this->input->post('subjectfilter')."%'";
				}
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
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;

			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->rank."</td><td>".$row->subject_code."</td>
					<td>".$row->subject_name."</td><td>".$row->staff_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";

					if($row->percentage>=0&&$row->percentage<=10)
					{
						$set1++;

					}
					if($row->percentage>=11&&$row->percentage<=20)
					{
						$set2++;
					}
					if($row->percentage>=21&&$row->percentage<=30)
					{
						$set3++;
					}
					if($row->percentage>=31&&$row->percentage<=40)
					{
						$set4++;
					}
					if($row->percentage>=41&&$row->percentage<=50)
					{
						$set5++;
					}
					if($row->percentage>=51&&$row->percentage<=60)
					{
						$set6++;
					}
					if($row->percentage>=61&&$row->percentage<=70)
					{
						$set7++;
					}
					if($row->percentage>=71&&$row->percentage<=80)
					{
						$set8++;
					}
					if($row->percentage>=81&&$row->percentage<=90)
					{
						$set9++;
					}
					if($row->percentage>=91&&$row->percentage<=100)
					{
						$set10++;
					}
				
				} 
			
			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Percentage</li>
			<li>Y axis : Subjects</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Subjects</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Subject Rank List Report','level' => 'Department Level','chart' => $chart);
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

			if($this->input->post('subjectfilter'))
			{
				if($this->alphanumericVal($this->input->post('subjectfilter')))
				{
					$filterQry = $filterQry." and subject_name like '%".$this->input->post('subjectfilter')."%'";
				}
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
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;

			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->year."</td><td>".$row->rank."</td><td>".$row->subject_code."</td>
					<td>".$row->subject_name."</td><td>".$row->staff_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";

					if($row->percentage>=0&&$row->percentage<=10)
					{
						$set1++;

					}
					if($row->percentage>=11&&$row->percentage<=20)
					{
						$set2++;
					}
					if($row->percentage>=21&&$row->percentage<=30)
					{
						$set3++;
					}
					if($row->percentage>=31&&$row->percentage<=40)
					{
						$set4++;
					}
					if($row->percentage>=41&&$row->percentage<=50)
					{
						$set5++;
					}
					if($row->percentage>=51&&$row->percentage<=60)
					{
						$set6++;
					}
					if($row->percentage>=61&&$row->percentage<=70)
					{
						$set7++;
					}
					if($row->percentage>=71&&$row->percentage<=80)
					{
						$set8++;
					}
					if($row->percentage>=81&&$row->percentage<=90)
					{
						$set9++;
					}
					if($row->percentage>=91&&$row->percentage<=100)
					{
						$set10++;
					}
				
				} 
			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Percentage</li>
			<li>Y axis : Subjects</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Subjects</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Subject Rank List Report','level' => 'Year Level','chart' => $chart);
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

			if($this->input->post('subjectfilter'))
			{
				if($this->alphanumericVal($this->input->post('subjectfilter')))
				{
					$filterQry = $filterQry." and subject_name like '%".$this->input->post('subjectfilter')."%'";
				}
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
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;

			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->year."</td>
					<td>".$row->rank."</td><td>".$row->subject_code."</td>
					<td>".$row->subject_name."</td><td>".$row->staff_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>
					</tr>";

					if($row->percentage>=0&&$row->percentage<=10)
					{
						$set1++;

					}
					if($row->percentage>=11&&$row->percentage<=20)
					{
						$set2++;
					}
					if($row->percentage>=21&&$row->percentage<=30)
					{
						$set3++;
					}
					if($row->percentage>=31&&$row->percentage<=40)
					{
						$set4++;
					}
					if($row->percentage>=41&&$row->percentage<=50)
					{
						$set5++;
					}
					if($row->percentage>=51&&$row->percentage<=60)
					{
						$set6++;
					}
					if($row->percentage>=61&&$row->percentage<=70)
					{
						$set7++;
					}
					if($row->percentage>=71&&$row->percentage<=80)
					{
						$set8++;
					}
					if($row->percentage>=81&&$row->percentage<=90)
					{
						$set9++;
					}
					if($row->percentage>=91&&$row->percentage<=100)
					{
						$set10++;
					}

				
				} 
			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Percentage</li>
			<li>Y axis : Subjects</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Subjects</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Subject Rank List Report','level' => 'Department and Year Level','chart' => $chart);
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

			if($this->input->post('subjectfilter'))
			{
				if($this->alphanumericVal($this->input->post('subjectfilter')))
				{
					$filterQry = $filterQry." and subject_name like '%".$this->input->post('subjectfilter')."%'";
				}
			}
			
			$output = $this->analysis->subjectRankListReportClass($this->session->userdata('client_id'),$examid,$filterQry);
			
			if($output)
			{
			$table_headers = "<th style=\"width:15%;\">Class</th>
			<th style=\"width:10%;\">Rank</th>
			<th>Subject Code</th><th>Subject Name</th><th>Staff Name</th>
			<th>Pass Percentage</th>";

			$opt_data = '';
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;

			foreach($output as $row)
				{
				
					$opt_data = $opt_data."<tr><td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->rank."</td><td>".$row->subject_code."</td>
					<td>".$row->subject_name."</td><td>".$row->staff_name."</td>
					<td>".$row->percentage."</td>
					</tr>";

					if($row->percentage>=0&&$row->percentage<=10)
					{
						$set1++;

					}
					if($row->percentage>=11&&$row->percentage<=20)
					{
						$set2++;
					}
					if($row->percentage>=21&&$row->percentage<=30)
					{
						$set3++;
					}
					if($row->percentage>=31&&$row->percentage<=40)
					{
						$set4++;
					}
					if($row->percentage>=41&&$row->percentage<=50)
					{
						$set5++;
					}
					if($row->percentage>=51&&$row->percentage<=60)
					{
						$set6++;
					}
					if($row->percentage>=61&&$row->percentage<=70)
					{
						$set7++;
					}
					if($row->percentage>=71&&$row->percentage<=80)
					{
						$set8++;
					}
					if($row->percentage>=81&&$row->percentage<=90)
					{
						$set9++;
					}
					if($row->percentage>=91&&$row->percentage<=100)
					{
						$set10++;
					}

				
				} 
			
			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Percentage</li>
			<li>Y axis : Subjects</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Subjects</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Subject Rank List Report','level' => 'Class Level','chart' => $chart);
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

		if($this->input->post('studentfilter'))
			{
				if($this->alphanumericVal($this->input->post('studentfilter')))
				{
					$filterQry = $filterQry." and student_name like '%".$this->input->post('studentfilter')."%'";
				}
			}

		if($this->input->post('subjectfilter'))
			{
				if($this->alphanumericVal($this->input->post('subjectfilter')))
				{
					$filterQry = $filterQry." and subject_name like '%".$this->input->post('subjectfilter')."%'";
				}
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
			$chart_data = '';
			$set1 = 0;
			$set2 = 0;
			$set3 = 0;
			$set4 = 0;
			$set5 = 0;
			$set6 = 0;
			$set7 = 0;
			$set8 = 0;
			$set9 = 0;
			$set10 = 0;
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

					if($row->marks_obtained>=0&&$row->marks_obtained<=10)
					{
						$set1++;

					}
					if($row->marks_obtained>=11&&$row->marks_obtained<=20)
					{
						$set2++;
					}
					if($row->marks_obtained>=21&&$row->marks_obtained<=30)
					{
						$set3++;
					}
					if($row->marks_obtained>=31&&$row->marks_obtained<=40)
					{
						$set4++;
					}
					if($row->marks_obtained>=41&&$row->marks_obtained<=50)
					{
						$set5++;
					}
					if($row->marks_obtained>=51&&$row->marks_obtained<=60)
					{
						$set6++;
					}
					if($row->marks_obtained>=61&&$row->marks_obtained<=70)
					{
						$set7++;
					}
					if($row->marks_obtained>=71&&$row->marks_obtained<=80)
					{
						$set8++;
					}
					if($row->marks_obtained>=81&&$row->marks_obtained<=90)
					{
						$set9++;
					}
					if($row->marks_obtained>=91&&$row->marks_obtained<=100)
					{
						$set10++;
					}

				
				} 
			$chart_data = $set1.','.$set2.','.$set3.','.$set4.','.$set5.','.$set6.','.$set7.','.$set8.','.$set9.','.$set10;
			$chart = '<div id="canvas-holder" style="text-align:center;margin-top:3%;"><canvas id="chart-area" width="450" height="300"/></div>';
			$chart = $chart.'<script>
		var Data = {
		labels : ["0-10","11-20","21-30","31-40","41-50","51-60","61-70","71-80","81-90","91-100"],
		datasets : [
			{
				fillColor : "#EEC900",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : ['.$chart_data.']
			}
			]
			}

			window.onload = function(){
			var ctx = document.getElementById("chart-area").getContext("2d");
			var myChart = new Chart(ctx).Bar(Data, {
				scaleShowHorizontalLines: false,
				scaleShowVerticalLines: false,
				scaleFontColor: "#000"
				    

			});
			
			}

			</script>';

			$chart = $chart.'<div class="chart_legend">
			<ul class="chart_legend_list">
			<li>X axis : Total Marks in Subject</li>
			<li>Y axis : Students</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Students</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Student Mark List Report','level' => 'N/A','chart' => $chart);
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