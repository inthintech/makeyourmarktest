
<div class="subcontainer" style="width:90%;position:relative;top:2%;">
<div class="panel panel-default">
<div class="panel-heading">Report Generator</div>
<div class="panel-body">

<form class="upload_form" target="_blank" action="<?php echo site_url('reports/output');?>" method="POST" enctype="multipart/form-data">

<fieldset>

<p class="report_header">Exam Selector</p>

<br><br>


<label>Select exam</label>
<br><br>
<select class="exam_select" name="examid" ><?php echo $examlist ?></select>

<br><br><br><br>
<p class="report_header">Report Selector</p>

<br><br>
<label>Select Report</label>
<br><br>
<select id="reportID" class="exam_select" name="reportid" onchange="myFunction()">
<option selected value="1">Pass Percentage</option>
<option value="2">Topper</option>
<option value="3">Student Rank List</option>
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
		document.getElementById("levelID").disabled = false;
		document.getElementById("subjectfilter").disabled = true;
		document.getElementById("studentfilter").disabled = true;
		document.getElementById("resultfilter").disabled = true;
		break;
		
		case '2':
		document.getElementById("info").innerHTML = "The Topper report displays the students who have passed in all subjects and have the highest total percentage score.";
		document.getElementById("levelID").disabled = false;
		document.getElementById("subjectfilter").disabled = true;
		document.getElementById("studentfilter").disabled = true;
		document.getElementById("resultfilter").disabled = true;
		break;
		
		case '3':
		document.getElementById("info").innerHTML = "The Student Rank List report displays the list of students and their total percentage ranked by the highest score.";
		document.getElementById("levelID").disabled = false;
		document.getElementById("subjectfilter").disabled = true;
		document.getElementById("studentfilter").disabled = false;
		document.getElementById("resultfilter").disabled = true;
		break;
		
		case '5':
		document.getElementById("info").innerHTML = "The Subject Rank List report displays the list of subjects and their pass percentage ranked by the highest percentage.";
		document.getElementById("levelID").disabled = false;
		document.getElementById("subjectfilter").disabled = false;
		document.getElementById("studentfilter").disabled = true;
		document.getElementById("resultfilter").disabled = true;
		break;
		
		case '6':
		document.getElementById("info").innerHTML = "The Student Mark List report displays the list of students and their marks in individual subjects. It is independant of levels.";
		document.getElementById("levelID").disabled = true;
		document.getElementById("subjectfilter").disabled = false;
		document.getElementById("studentfilter").disabled = false;
		document.getElementById("resultfilter").disabled = false;
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
<label>Select Level</label>
<br><br>
<select id="levelID" class="exam_select" name="levelid" onchange="myFunctionlevel()">
<option selected value="1">College Level</option>
<option value="2">Department Level</option>
<option value="3">Year Level</option>
<option value="5">Department and Year Level</option>
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
		document.getElementById("deptfilter").disabled = true;
		document.getElementById("yearfilter").disabled = true;
		document.getElementById("sectionfilter").disabled = true;
		break;
		
		case '2':
		document.getElementById("level").innerHTML = "Department level report will be generated for the results of students of each department in college.";
		document.getElementById("deptfilter").disabled = false;
		document.getElementById("yearfilter").disabled = true;
		document.getElementById("sectionfilter").disabled = true;
		break;
		
		case '3':
		document.getElementById("level").innerHTML = "Year level report will be generated for the results of students of each year in college.";
		document.getElementById("deptfilter").disabled = true;
		document.getElementById("yearfilter").disabled = false;
		document.getElementById("sectionfilter").disabled = true;
		break;
		
		case '4':
		document.getElementById("level").innerHTML = "Class level report will be generated for the results of students of each class in college.";
		document.getElementById("deptfilter").disabled = false;
		document.getElementById("yearfilter").disabled = false;
		document.getElementById("sectionfilter").disabled = false;
		break;
		
		case '5':
		document.getElementById("level").innerHTML = "Department and Year level report will be generated for the results of students of each department and year in college.";
		document.getElementById("deptfilter").disabled = false;
		document.getElementById("yearfilter").disabled = false;
		document.getElementById("sectionfilter").disabled = true;
		break;
		
	}
	
}
</script>



<br><br><br>
<div id="level" class="alert alert-success" role="alert">College level report will be 
generated for the results of students of all departments.</div>


<br><br>
<p class="report_header">Filter Selector (Enables based on report type)</p>

<br><br>
<label>Dept Filter</label>
<br><br>
<select disabled id="deptfilter" class="exam_select" name="deptfilter">
<option selected value="99">All</option>
<option value="CSE">CSE</option>
<option value="EEE">EEE</option>
<option value="EIE">EIE</option>
</select>

<br><br>
<label>Year Filter</label>
<br><br>
<select disabled id="yearfilter" class="exam_select" name="yearfilter">
<option selected value="99">All</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
</select>

<br><br>
<label>Section Filter</label>
<br><br>
<select disabled id="sectionfilter" class="exam_select" name="sectionfilter">
<option selected value="99">All</option>
<option value="A">A</option>
<option value="B">B</option>
</select>

<br><br>
<label>Subject Filter (Please type full or partial Subject Name as entered in system)</label>
<br><br>
<input type="text" disabled id="subjectfilter" class="exam_select" name="subjectfilter" size="50"/>


<br><br>
<label>Student Filter (Please type full or partial Student Name as entered in system)</label>
<br><br>
<input type="text" disabled id="studentfilter" class="exam_select" name="studentfilter" size="50"/>

<br><br>
<label>Result Filter</label>
<br><br>
<select disabled id="resultfilter" class="exam_select" name="resultfilter">
<option selected value="99">All</option>
<option value="1">Pass Only</option>
<option value="0">Fail Only</option>
</select>

<br><br><br>
<div class="alert alert-danger" role="alert">Warning : Filters can be used to 
restrict the results based on the filters applied. Please use wherever necessary.</div>


</fieldset>  
<br><br>
<button type="submit" name="submit" class="btn btn-primary">Submit</button><br><br>

</form>

</div>
</div>
</div>

