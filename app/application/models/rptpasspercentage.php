<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Rptpasspercentage extends CI_Model
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
		   $filterQry = "where 1=1";
		   
		   if($this->session->userdata('client_type')==3)
		   {	
			   $output = $this->analysis->passPercentageReportSchool($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
		   }
		   else
		   {
			   $output = $this->analysis->passPercentageReportCollege($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
		   }
		   
		   if($output)
		   {
		   
			   $table_headers = "<th style=\"width:40%;\">".$var1." Name</th>
			   <th style=\"white-space: nowrap;\">No of Students Attempted</th>
			   <th>No of Students Passed in all Subjects</th><th>Overall Pass Percentage</th>";
		   
	  
	  
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
		   
		   $rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Pass Percentage Report','level' => $var1.' Level','chart' => $chart);
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
	 $filterQry = "where 1=1";

	 
	 if($this->input->post('deptfilter')<>99)
	 {
		 $filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
	 }
	
	 if($this->session->userdata('client_type')==3)
	 {	
		 $output = $this->analysis->passPercentageReportSchool($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
	 }
	 else
	 {
		 $output = $this->analysis->passPercentageReportCollege($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
	 }

	 if($output)
	 {
	 
		 $table_headers = "<th style=\"width:20%;\">".$var2."</th>
		 <th style=\"white-space: nowrap;\">No of Students Attempted</th>
		 <th>No of Students Passed in all Subjects</th><th>Overall Pass Percentage</th>";
	 
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
	 <ul class="chart_legend_list">'.
	 '<li>X axis : '.$var3.'</li>'.
	 '<li>Y axis : Students</li>
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
	 
	 $rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Pass Percentage Report','level' => $var3.' Level','chart' => $chart);	
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
	 $filterQry = "where 1=1";
	 
	 if($this->input->post('yearfilter')<>99)
	 {
		 $filterQry = $filterQry." and year=".$this->input->post('yearfilter');
	 }
	 
	 $output = $this->analysis->passPercentageReportCollege($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
	 
	 
	 if($output)
	 {
	 $table_headers = "<th style=\"width:20%;\">Year</th>
	 <th style=\"white-space: nowrap;\">No of Students Attempted</th>
	 <th>No of Students Passed in all Subjects</th><th>Overall Pass Percentage</th>";

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
		 $statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:0;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
		 $this->load->view('vmessage',$statusdata);
	 }

 }

 /* Department Year Level Report*/
 
 if($levelid==5)
 {
	 $filterQry = "where 1=1";
	 
	 if($this->input->post('deptfilter')<>99)
	 {
		 $filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
	 }

	 if($this->input->post('yearfilter')<>99)
	 {
		 $filterQry = $filterQry." and year=".$this->input->post('yearfilter');
	 }

	 $output = $this->analysis->passPercentageReportCollege($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
 
	 if($output)
	 {
	 $table_headers = "<th style=\"width:20%;\">Dept</th><th style=\"width:20%;\">Year</th>
	 <th style=\"white-space: nowrap;\">No of Students Attempted</th>
	 <th>No of Students Passed in all Subjects</th><th>Overall Pass Percentage</th>";

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
		 $statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:0;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
		 $this->load->view('vmessage',$statusdata);
	 }

 }

 /* Class Level Report*/
 
 if($levelid==4)
 {
	 
	 $filterQry = "where 1=1";
	 
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
		 $output = $this->analysis->passPercentageReportSchool($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
	 }
	 else
	 {
		 $output = $this->analysis->passPercentageReportCollege($this->session->userdata('client_id'),$examid,$filterQry,$levelid);
	 }
	 
	 if($output)
	 {
	 
	 if($this->session->userdata('client_type')==3)
	 {	
		$table_headers = "<th style=\"width:10%;\">Standard</th><th style=\"width:10%;\">Section</th>
		<th style=\"white-space: nowrap;\">No of Students Attempted</th>
		<th>No of Students Passed in all Subjects</th><th>Overall Pass Percentage</th>";
	 }
	 else
	 {
		$table_headers = "<th style=\"width:10%;\">Dept</th><th style=\"width:10%;\">Year</th><th style=\"width:10%;\">Section</th>
	   <th style=\"white-space: nowrap;\">No of Students Attempted</th>
	   <th>No of Students Passed in all Subjects</th><th>Overall Pass Percentage</th>";
	 }
	 
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
			 
			 if($this->session->userdata('client_type')==3)
			 {	
			 $opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->section."</td><td>".$row->student_cnt."</td>
			 <td>".$row->student_pass_cnt."</td><td>".$row->pass_percentage."</td></tr>";
			 }
			 else
			 {
			  $opt_data = $opt_data."<tr><td>".$row->dept_code."</td><td>".$row->year."</td><td>".$row->section."</td><td>".$row->student_cnt."</td>
			 <td>".$row->student_pass_cnt."</td><td>".$row->pass_percentage."</td></tr>";
			 }

			 if($row->pass_percentage>=0&&$row->pass_percentage<=10)
					{
						$set1++;

					}
					if($row->pass_percentage>=11&&$row->pass_percentage<=20)
					{
						$set2++;
					}
					if($row->pass_percentage>=21&&$row->pass_percentage<=30)
					{
						$set3++;
					}
					if($row->pass_percentage>=31&&$row->pass_percentage<=40)
					{
						$set4++;
					}
					if($row->pass_percentage>=41&&$row->pass_percentage<=50)
					{
						$set5++;
					}
					if($row->pass_percentage>=51&&$row->pass_percentage<=60)
					{
						$set6++;
					}
					if($row->pass_percentage>=61&&$row->pass_percentage<=70)
					{
						$set7++;
					}
					if($row->pass_percentage>=71&&$row->pass_percentage<=80)
					{
						$set8++;
					}
					if($row->pass_percentage>=81&&$row->pass_percentage<=90)
					{
						$set9++;
					}
					if($row->pass_percentage>=91&&$row->pass_percentage<=100)
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
			<li>Y axis : Class</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Class</span>
			</li>
			</ul>
			</div>';
	 
	 $rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Pass Percentage Report','level' => 'Class Level','chart' => $chart);
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