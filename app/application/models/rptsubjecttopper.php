<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Rptsubjecttopper extends CI_Model
{

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


function getReport($examid,$client_name){
 
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
	   
	   $filterQry = "where 1=1";
			
			if($this->input->post('deptfilter')<>99)
			{
				$filterQry = $filterQry." and dept_code='".$this->input->post('deptfilter')."'";
			}
			
			if($this->session->userdata('client_type')!=3)
			{
				if($this->input->post('yearfilter')<>99)
				{
					$filterQry = $filterQry." and year='".$this->input->post('yearfilter')."'";
				}
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
			
			if($this->session->userdata('client_type')==3)
			{
				$output = $this->analysis->subjectTopperReportSchool($this->session->userdata('client_id'),$examid,$filterQry);
			}
			else
			{
				$output = $this->analysis->subjectTopperReportCollege($this->session->userdata('client_id'),$examid,$filterQry);
				
			}
			
			if($output)
			{
			$table_headers = "
			<th>Class</th><th>Subject Name</th><th>Student ID</th><th>Student Name</th>
			<th>Marks Scored</th>";

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
						$opt_data = $opt_data."<tr><td>".$row->dept_code." ".$row->section."</td>
						<td>".$row->subject_name."</td>
						<td>".$row->student_id."</td>
						<td>".$row->student_name."</td>
						<td>".$row->marks_obtained."</td>
						</tr>";
					}
					else
					{
						$opt_data = $opt_data."<tr><td>".$row->dept_code." ".$row->year." ".$row->section."</td>
						<td>".$row->subject_name."</td>
						<td>".$row->student_id."</td>
						<td>".$row->student_name."</td>
						<td>".$row->marks_obtained."</td>
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
			<li>X axis : Marks Obtained</li>
			<li>Y axis : Subjects</li>
			<li>
			<div style="background:#EEC900;"></div>
			<span style="margin-left:5%;">No of Subjects</span>
			</li>
			</ul>
			</div>';

			$rptdata = array('exam_name' => $exam_name,'table_headers' => $table_headers,'data' => $opt_data,'report_name' => 'Subject Topper Report','level' => 'N/A','chart' => $chart);
			$this->load->view('vrptoutput',$rptdata);
			}
			else
			{
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:0;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}

		
		
	   
}

}
?>