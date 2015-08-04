<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Rptstudentmarklist extends CI_Model
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

		if($this->input->post('studentfilter'))
			{
				if($this->alphanumericVal($this->input->post('studentfilter')))
				{
					$filterQry = $filterQry." and student_name like '%".$this->input->post('studentfilter')."%'";
				}
			}
		
		if($this->input->post('studentidfilter'))
			{
				if($this->alphanumericVal($this->input->post('studentidfilter')))
				{
					$filterQry = $filterQry." and student_id like '%".$this->input->post('studentidfilter')."%'";
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
			
			if($this->session->userdata('client_type')==3)
			{
				$output = $this->analysis->studentMarkListReportSchool($this->session->userdata('client_id'),$examid,$filterQry);
			}
			else
			{
				$output = $this->analysis->studentMarkListReportCollege($this->session->userdata('client_id'),$examid,$filterQry);
			}
			
			
			if($output)
			{
			$table_headers = "<th style=\"width:10%;\">S.No</th><th style=\"width:10%;\">Class</th>
			<th style=\"width:10%;\">Student ID</th>
			<th style=\"width:15%;\">Student Name</th>
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
					if($this->session->userdata('client_type')==3)
					{
						if($row->result==1)
						{
						$opt_data = $opt_data."<tr><td>".$sno."</td><td>".$row->dept_code." ".$row->section."</td>
						<td>".$row->student_id."</td><td>".$row->student_name."</td>
						<td>".$row->subject_name."</td>
						<td>".$row->total_marks."</td><td>".$row->marks_obtained."</td><td style='background:green;color:white;'>Passed</td>
						</tr>";
						}
						else
						{
						$opt_data = $opt_data."<tr><td>".$sno."</td><td>".$row->dept_code." ".$row->section."</td>
						<td>".$row->student_id."</td><td>".$row->student_name."</td>
						<td>".$row->subject_name."</td>
						<td>".$row->total_marks."</td><td>".$row->marks_obtained."</td><td style='background:#DC143C;color:white;'>Failed</td>
						</tr>";
						}
					}
					else
					{
						if($row->result==1)
						{
						$opt_data = $opt_data."<tr><td>".$sno."</td><td>".$row->dept_code." ".$row->year." ".$row->section."</td>
						<td>".$row->student_id."</td><td>".$row->student_name."</td>
						<td>".$row->subject_name."</td>
						<td>".$row->total_marks."</td><td>".$row->marks_obtained."</td><td style='background:green;color:white;'>Pass</td>
						</tr>";
						}
						else
						{
						$opt_data = $opt_data."<tr><td>".$sno."</td><td>".$row->dept_code." ".$row->year." ".$row->section."</td>
						<td>".$row->student_id."</td><td>".$row->student_name."</td>
						<td>".$row->subject_name."</td>
						<td>".$row->total_marks."</td><td>".$row->marks_obtained."</td><td style='background:#DC143C;color:white;'>Failed</td>
						</tr>";
						}
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
				$statusdata = array('message' => '<style>.containerdiv {height:70%;}</style><div style="margin-top:0;" class="alert alert-danger" role="alert">No data was returned. Either there is no data for the input or some invalid filters are applied. Please remove any unneccessary filters and try again.</div>');
				$this->load->view('vmessage',$statusdata);
			}
	   
	   
}

}
?>