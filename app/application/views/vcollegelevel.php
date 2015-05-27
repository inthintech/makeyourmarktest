
<div class="subcontainer" style="width:90%;">
<div class="panel panel-default">
<div class="panel-heading">Select the Report to View at College Level</div>
<div class="panel-body">

<form class="upload_form" action="<?php echo site_url('exams/uploadstatus');?>" method="POST" enctype="multipart/form-data">

<fieldset>


<p class="report_header">Exam Selector</p>
<br><br>
<label>Select the exam to report</label>
<br><br>
<select class="exam_select" name="examid" ><?php echo $examlist ?></select>

<br><br><br><br>
<p class="report_header">Report Selector</p>

<br><br>
<label>Select the report</label>
<br><br>
<select id="reportID" class="exam_select" name="reportid" onchange="myFunction()">
<option selected value="1">Overall Pass Percentage</option>
<option value="2">Department Pass Percentage</option>
<option value="3">Overall College Topper</option>
<option value="4">Department College Toppers</option>
<option value="5">Student Rank</option>
</select>

<br><br><br><br>
<p class="report_header">Report Information</p>

<br><br>
<div id="info" class="alert alert-success" role="alert">The Overall Pass Percentage report displays the percentage of students 
who have passed in all subjects. </div>

</fieldset>  
<br><br>
<button type="submit" name="submit" class="btn btn-primary">Submit</button><br><br>
<?php echo validation_errors(); ?>
</form>


<script>
function myFunction() {
    var x = document.getElementById("reportID").value;
    switch(x)
	{
		case '1':
		document.getElementById("info").innerHTML = "The Overall Pass Percentage report displays the percentage of students who have passed in all subjects.";
		break;
		case '2':
		document.getElementById("info").innerHTML = "The Department Pass Percentage report displays the percentage of students who have passed in all subjects for each department.";
		break;
		case '3':
		document.getElementById("info").innerHTML = "The Overall College Topper report displays the student who has the highest percentage.";
		break;
		case '4':
		document.getElementById("info").innerHTML = "The Department Pass Percentage report provides the percentage of students who have passed in all subjects for each department of college.";
		break;
		case '5':
		document.getElementById("info").innerHTML = "The Student Rank report provides the percentage of students who have passed in all subjects for each department of college.";
		break;
	}
	
}
</script>

</div>
</div>
</div>

