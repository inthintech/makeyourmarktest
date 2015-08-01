<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Rptoveralltopper extends CI_Model
{

function getReport($examid,$levelid,$client_name){
 
	   $this->load->model('user','',TRUE);
	   $this->load->model('analysis','',TRUE);
    
	   $result = $this->user->getExamName($examid);
	   foreach($result as $row)
	   {
		   $exam_name= $row->exam_name;
	   }
	    
	   $filterQry = '';
	   
	   //swap parameters for school and college
	   
	   $var1 = '';
	   $var2 = '';
	   $var3 = '';
	   
	   if($this->session->userdata('client_type')==3)
	   {
		   $var1 = 'School';
		   $var2 = 'Standard';
		   $var3 = 'Standard';
	   }
	   else
	   {
		   $var1 = 'College';
		   $var2 = 'Department Code';
		   $var3 = 'Department';
	   }
	   
	   /* College Level Report*/
		
		if($levelid==1)
		{
			$filterQry = "where 1=1 and rank=1";
			
			if($this->session->userdata('client_type')==3)
		    {
				$output = $this->analysis->overallTopperReportSchool($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
			}
			else
			{
				$output = $this->analysis->overallTopperReportCollege($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
			}
			if($output)
			{
			$table_headers = "<th style=\"width:40%;\">".$var1." Name</th>
			<th>Student ID</th><th>Student Name</th><th>Class</th>
			<th>Percentage</th><th>All Cleared</th>";

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
					if($row->allpass==1)
					{
						$stat = "<td style='background:green;color:white;'>Yes</td>";
					}
					else
					{
						$stat = "<td style='background:#DC143C;color:white;'>No</td>";
					}
					
					if($this->session->userdata('client_type')==3)
					{
						
						
						$opt_data = $opt_data."<tr><td>".$client_name."</td><td>".$row->student_id."</td>
						<td>".$row->student_name."</td>
						<td>".$row->dept_code." ".$row->section."</td>
						<td>".$row->percentage."</td>".$stat."
						</tr>";
					}
					else
					{
						$opt_data = $opt_data."<tr><td>".$client_name."</td><td>".$row->student_id."</td>
						<td>".$row->student_name."</td>
						<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
						<td>".$row->percentage."</td>
						".$stat."
						</tr>";
					}

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

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Overall Topper Report','level' => $var1.' Level','chart' => $chart);
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:0;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}
		

/* Dept Level Report*/
		
		if($levelid==2)
		{
			$filterQry = "where 1=1 and rank=1";
			
			if($this->input->post('deptfilter')<>99)
			{
				$filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
			}

			if($this->session->userdata('client_type')==3)
		    {
				$output = $this->analysis->overallTopperReportSchool($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
			}
			else
			{
				$output = $this->analysis->overallTopperReportCollege($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
			}
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">".$var2."</th>
			<th>Student ID</th><th>Student Name</th><th>Class</th>
			<th>Percentage</th><th>All Cleared</th>";

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
					
					if($row->allpass==1)
					{
						$stat = "<td style='background:green;color:white;'>Yes</td>";
					}
					else
					{
						$stat = "<td style='background:#DC143C;color:white;'>No</td>";
					}
					
					if($this->session->userdata('client_type')==3)
					{
						$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->student_id."</td>
						<td>".$row->student_name."</td>
						<td>".$row->dept_code." ".$row->section."</td>
						<td>".$row->percentage."</td>".$stat."
						</tr>";
					}
					else
					{
						$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->student_id."</td>
						<td>".$row->student_name."</td>
						<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
						<td>".$row->percentage."</td>".$stat."
						</tr>";
					}

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

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Overall Topper Report','level' => $var3.' Level','chart' => $chart);
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:0;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}

/* Year Level Report*/
		
		if($levelid==3)
		{
			$filterQry = "where 1=1 and rank=1";
			
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
			$output = $this->analysis->topperReportYear($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">Year</th>
			<th>Student ID</th><th>Student Name</th><th>Class</th>
			<th>Percentage</th><th>All Cleared</th>";

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
					
					if($row->allpass==1)
					{
						$stat = "<td style='background:green;color:white;'>Yes</td>";
					}
					else
					{
						$stat = "<td style='background:#DC143C;color:white;'>No</td>";
					}
					
					$opt_data = $opt_data."<tr><td>".$row->year."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>".$stat."
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

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Overall Topper Report','level' => 'Year Level','chart' => $chart);
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:0;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}


/* Department Year Level Report*/
		
		if($levelid==5)
		{
			$filterQry = "where 1=1 and rank=1";

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
			$output = $this->analysis->topperReportDeptYear($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">Dept</th><th style=\"width:10%;\">Year</th>
			<th>Student ID</th><th>Student Name</th><th>Class</th>
			<th>Percentage</th><th>All Cleared</th>";

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
					
					if($row->allpass==1)
					{
						$stat = "<td style='background:green;color:white;'>Yes</td>";
					}
					else
					{
						$stat = "<td style='background:#DC143C;color:white;'>No</td>";
					}
					
					$opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->year."</td><td>".$row->student_id."</td>
					<td>".$row->student_name."</td>
					<td>".$row->dept_code." ".$row->year." ".$row->section."</td>
					<td>".$row->percentage."</td>".$stat."
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

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Overall Topper Report','level' => 'Department and Year Level','chart' => $chart);
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:0;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}

/* Class Level Report*/
		
		if($levelid==4)
		{
			$filterQry = "where 1=1 and rank=1";

			if($this->input->post('deptfilter')<>99)
			{
				$filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
			}

			if($this->session->userdata('client_type')!=3)
		    {
				if($this->input->post('yearfilter')<>99)
				{
					$filterQry = $filterQry." and year=".$this->input->post('yearfilter');
				}
			}
			
			
			if($this->input->post('sectionfilter')<>99)
			{
				$filterQry = $filterQry." and section='".$this->input->post('sectionfilter')."'";
			}

			if($this->session->userdata('client_type')==3)
		    {
				$output = $this->analysis->overallTopperReportSchool($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
			}
			else
			{
				$output = $this->analysis->overallTopperReportCollege($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
			}
			
			if($output)
			{
			$table_headers = "<th style=\"width:15%;\">Class</th>
			<th>Student ID</th><th>Student Name</th>
			<th>Percentage</th><th>All Cleared</th>";

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
					if($row->allpass==1)
					{
						$stat = "<td style='background:green;color:white;'>Yes</td>";
					}
					else
					{
						$stat = "<td style='background:#DC143C;color:white;'>No</td>";
					}
					
					if($this->session->userdata('client_type')==3)
					{
						$opt_data = $opt_data."<tr><td>".$row->dept_code." ".$row->section."</td><td>".$row->student_id."</td>
						<td>".$row->student_name."</td>
						<td>".$row->percentage."</td>".$stat."
						</tr>";
					}
					else
					{
						$opt_data = $opt_data."<tr><td>".$row->dept_code." ".$row->year." ".$row->section."</td><td>".$row->student_id."</td>
						<td>".$row->student_name."</td>
						<td>".$row->percentage."</td>".$stat."
						</tr>";
					}

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

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Overall Topper Report','level' => 'Class Level','chart' => $chart);
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:0;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		}

	   
	   
	   
}

}
?>