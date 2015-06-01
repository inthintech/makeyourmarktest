
<div class="subcontainer" style="width:90%;position:relative;top:2%;">
<div class="panel panel-default">
<div class="panel-heading">Report Generator</div>
<div class="panel-body">

<form class="upload_form" action="<?php echo site_url('exams/uploadstatus');?>" method="POST" enctype="multipart/form-data">

<fieldset>

<p class="report_header">Exam Selector</p>

<br><br>


<label>Select exam</label>
<br><br>
<select class="exam_select" name="examid" ><?php echo $examlist ?></select>

<br><br><br><br>
<p class="report_header">Report Selector</p>

<br><br>
<label>Select report</label>
<br><br>
<select id="reportID" class="exam_select" name="reportid" onchange="myFunction()">
<option selected value="1">Pass Percentage</option>
<option value="2">Topper</option>
<option value="3">Student Rank List</option>
<option value="4">Total Marks</option>
<option value="5">Subject Rank List</option>
<option value="6">Student Mark List</option>
</select>

<script>
function myFunction() {
    var x = document.getElementById("reportID").value;
    switch(x)
	{
		case '1':
		document.getElementById("info").innerHTML = "The Pass Percentage report displays the percentage of students who have passed in all subjects.";
		break;
		case '2':
		document.getElementById("info").innerHTML = "The Topper report displays the students who have passed in all subjects and have the highest total percentage score.";
		break;
		case '3':
		document.getElementById("info").innerHTML = "The Student Rank List report displays the list of students and their total percentage ranked by the highest score.";
		break;
		case '4':
		document.getElementById("info").innerHTML = "The Total Marks report displays the total marks of students in all subjects considering both pass and fail category.";
		break;
		case '5':
		document.getElementById("info").innerHTML = "The Subject Rank List report displays the list of subjects and their pass percentage ranked by the highest percentage.";
		break;
		case '6':
		document.getElementById("info").innerHTML = "The Student Mark List report displays the list of students and their marks in individual subjects. It is independant of levels.";
		break;
	}
	
}
</script>


<br><br><br>
<div id="info" class="alert alert-success" role="alert">The Pass Percentage report displays the percentage of students 
who have passed in all subjects. </div>

<br><br>
<p class="report_header">Level Selector</p>

<br><br>
<label>Select level</label>
<br><br>
<select id="levelID" class="exam_select" name="levelid" onchange="myFunctionlevel()">
<option selected value="1">College Level</option>
<option value="2">Department Level</option>
<option value="3">Year Level</option>
<option value="4">Class Level</option>
<!--
<option value="5">Subject Level</option>
<option value="6">Student Level</option>
-->
</select>

<script>
function myFunctionlevel() {
    var x = document.getElementById("levelID").value;
    switch(x)
	{
		case '1':
		document.getElementById("level").innerHTML = "College level report will be generated for the results of students of all departments.";
		break;
		case '2':
		document.getElementById("level").innerHTML = "Department level report will be generated for the results of students of each department in college.";
		break;
		case '3':
		document.getElementById("level").innerHTML = "Year level report will be generated for the results of students of each year in college.";
		break;
		case '4':
		document.getElementById("level").innerHTML = "Class level report will be generated for the results of students of each class in college.";
		break;
		/*
		case '5':
		document.getElementById("level").innerHTML = "Subject level report will be generated for the results of students in each subject of department.";
		break;
		case '6':
		document.getElementById("level").innerHTML = "Student level report will be generated for the result of each students in college.";
		break;
		*/
	}
	
}
</script>



<br><br><br>
<div id="level" class="alert alert-success" role="alert">College level report will be 
generated for the results of students of all departments.</div>


<br><br>
<p class="report_header">Filter Selector</p>

<br><br>
<label>Dept Filter</label>
<br><br>
<select class="exam_select" name="deptid">
<option selected value="1">All</option>
</select>

<br><br>
<label>Year Filter</label>
<br><br>
<select class="exam_select" name="yearid">
<option selected value="1">All</option>
</select>

<br><br>
<label>Section Filter</label>
<br><br>
<select class="exam_select" name="sectionid">
<option selected value="1">All</option>
</select>

<br><br>
<label>Subject Filter</label>
<br><br>
<select class="exam_select" name="subjectid">
<option selected value="1">All</option>
</select>

<br><br>
<label>Student Filter</label>
<br><br>
<select class="exam_select" name="studentid">
<option selected value="1">All</option>
</select>

<br><br><br>
<div class="alert alert-danger" role="alert">Warning : Filters can be used to 
restrict the results based on the filters applied. Usage of unnecessary filters 
may return incorrect result. Please use wherever necessary.</div>


</fieldset>  
<br><br>
<button type="submit" name="submit" class="btn btn-primary">Submit</button><br><br>

</form>

</div>
</div>
</div>

