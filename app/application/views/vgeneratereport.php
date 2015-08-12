
<div class="subcontainer" style="width:70%;top:3%;">
<div class="panel panel-primary">
<div class="panel-heading centerHead">Report Generator</div>
<div class="panel-body">

<?php
/*
if($this->session->userdata('client_type')==3)
{
	echo '<form class="upload_form" target="_blank" action='.site_url('schoolreports/output').' method="POST" enctype="multipart/form-data">';
}
else
{
	echo '<form class="upload_form" target="_blank" action='.site_url('reports/output').' method="POST" enctype="multipart/form-data">';
}
*/
?>

<form class="upload_form" target="_blank" action="<?php echo site_url('reports/output') ?>" method="POST" enctype="multipart/form-data">

<input type="hidden" value="<?php echo $reportID;?>" name="reportid" />

<div id="info" class="alert alert-info" role="alert" style="width:90%;margin-left: auto;margin-right: auto;">
<?php
if($reportID==1)
{
echo 'The Pass Percentage report displays the percentage of students 
who have passed in all subjects.';
}
if($reportID==2)
{
echo 'The Overall Topper report displays the students who have the highest total percentage score.';
}
if($reportID==3)
{
echo 'The Student Rank List report displays the list of students and their total percentage ranked by the highest score.';
}
if($reportID==4)
{
echo 'The Subject Rank List report displays the list of subjects and their pass percentage ranked by the highest percentage.';
}
if($reportID==5)
{
echo 'The Student Mark List report displays the list of students and their marks in individual subjects.';
}
if($reportID==6)
{
echo 'The Subject Topper report displays the students who have have the highest total percentage score for each subject.';
}
?>
</div>

<br>
<p class="upload_header">Exam Selector</p>

<br>
<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><label>Select Exam Name</label></td>
<td class="uploadTableInput"><select class="exam_select" name="examid" ><?php echo $examlist ?></select></td>    
</tr>
</table>




<br><br>
<p class="upload_header">Level Selector</p>

<br>
<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><label>Select Level</label></td>
<td class="uploadTableInput">
<?php	
if($reportID==5||$reportID==6)
{
echo '<select disabled id="levelID" class="exam_select" name="levelid" onchange="myFunctionlevel()">';
}
else
{
echo '<select id="levelID" class="exam_select" name="levelid" onchange="myFunctionlevel()">';
}
?>

<?php
if($this->session->userdata('client_type')==1)
{
	echo '
	<option selected value="1">College Level</option>
	<option value="2">Department Level</option>
	<option value="3">Year Level</option>
	<option value="5">Department and Year Level</option>
	<option value="4">Class Level</option>
	';
}
else
{
	echo '
	<option selected value="1">School Level</option>
	<option value="2">Standard Level</option>
	<option value="4">Class Level</option>
	';
}
?>
</select>
</td>    
</tr>
</table>






<br>

<div id="level" class="alert alert-warning" role="alert" style="width:90%;margin-left: auto;margin-right: auto;">
<?php	
if($reportID==5||$reportID==6)
{
	echo 'This report is independant on levels. Please use filters if needed.';
}
else
{	
	if($this->session->userdata('client_type')==3)
	{
		echo 'School level report will be 
		generated for the results of students of all standards.';
	}
	else
	{
		echo 'College level report will be 
		generated for the results of students of all departments.';
	}
}
?>


</div>

<?php
if($this->session->userdata('client_type')==1)
{
	echo
	'
	<script>
	
	function myFunctionlevel() {
		var y = document.getElementById("levelID").value;
		document.getElementById("deptIcon").className = "glyphicon glyphicon-remove icon-size icon-red";
		document.getElementById("yearIcon").className = "glyphicon glyphicon-remove icon-size icon-red";
		document.getElementById("sectionIcon").className = "glyphicon glyphicon-remove icon-size icon-red";
		switch(y)
		{
			case "1":
			document.getElementById("level").innerHTML = "College level report will be generated for the results of students of all departments.";
			break;
			
			case "2":
			document.getElementById("level").innerHTML = "Department level report will be generated for the results of students of each department in college.";
			document.getElementById("deptIcon").className = "glyphicon glyphicon-ok icon-size icon-green";
			break;
			
			case "3":
			document.getElementById("level").innerHTML = "Year level report will be generated for the results of students of each year in college.";
			document.getElementById("yearIcon").className = "glyphicon glyphicon-ok icon-size icon-green";
			break;
			
			case "4":
			document.getElementById("level").innerHTML = "Class level report will be generated for the results of students of each class in college.";
			document.getElementById("deptIcon").className = "glyphicon glyphicon-ok icon-size icon-green";
			document.getElementById("yearIcon").className = "glyphicon glyphicon-ok icon-size icon-green";
			document.getElementById("sectionIcon").className = "glyphicon glyphicon-ok icon-size icon-green";
			break;
			
			case "5":
			document.getElementById("level").innerHTML = "Department and Year level report will be generated for the results of students of each department and year in college.";
			document.getElementById("deptIcon").className = "glyphicon glyphicon-ok icon-size icon-green";
			document.getElementById("yearIcon").className = "glyphicon glyphicon-ok icon-size icon-green";
		
			break;
			
		}
	}
	
	</script>
	';
}
else
{
	echo
	'
	<script>
	
	function myFunctionlevel() {
		var y = document.getElementById("levelID").value;
		document.getElementById("deptIcon").className = "glyphicon glyphicon-remove icon-size icon-red";
		//document.getElementById("yearIcon").className = "glyphicon glyphicon-remove icon-size icon-red";
		document.getElementById("sectionIcon").className = "glyphicon glyphicon-remove icon-size icon-red";
		switch(y)
		{
			case "1":
			document.getElementById("level").innerHTML = "School level report will be generated for the results of students of all standards.";
			break;
			
			case "2":
			document.getElementById("level").innerHTML = "Standard level report will be generated for the results of students of each standard in school.";
			document.getElementById("deptIcon").className = "glyphicon glyphicon-ok icon-size icon-green";
			break;
						
			case "4":
			document.getElementById("level").innerHTML = "Class level report will be generated for the results of students of each class in standard in school.";
			document.getElementById("deptIcon").className = "glyphicon glyphicon-ok icon-size icon-green";
			//document.getElementById("yearIcon").className = "glyphicon glyphicon-ok icon-size icon-green";
			document.getElementById("sectionIcon").className = "glyphicon glyphicon-ok icon-size icon-green";
			break;
			
		}
	}
	
	</script>
	';
}

?>


<br><br>
<p class="upload_header">Filter Selector</p>

<br><br>
<div class="alert alert-warning" role="alert" style="width:90%;margin-left: auto;margin-right: auto;">The X mark before the filter indicate
that the filter has no impact for the input. If it changes to a tick mark then the filter can be applied for the input.</div>

<br>
<table class="uploadTable">
<tr>
<td class="uploadTableLabel">
<?php	
if($reportID==5||$reportID==6)
{
echo '<span id="deptIcon" class="glyphicon glyphicon-ok icon-size icon-green"></span>';
}
else
{
echo '<span id="deptIcon" class="glyphicon glyphicon-remove icon-size icon-red"></span>';
}
?>

<label class="reportLabel">
<?php
if($this->session->userdata('client_type')==1)
{
	echo 'Dept Filter';
}
else
{
	echo 'Standard Filter';
}
?>
</label></td>
<td class="uploadTableInput">
<select id="deptfilter" class="exam_select" name="deptfilter">
<?php echo $deptHtml?>
<option selected value="99">All</option>
</select>
</td>    
</tr>
</table>


<?php
if($this->session->userdata('client_type')==1)
{
	echo
	'
	<br>
	<table class="uploadTable">
	<tr>
	<td class="uploadTableLabel">
	';
	if($reportID==5||$reportID==6)
	{
	echo '<span id="yearIcon" class="glyphicon glyphicon-ok icon-size icon-green"></span>';
	}
	else
	{
	echo '<span id="yearIcon" class="glyphicon glyphicon-remove icon-size icon-red"></span>';
	}
	echo
	'
	<label class="reportLabel">Year Filter</label></td>
	<td class="uploadTableInput">
	<select id="yearfilter" class="exam_select" name="yearfilter">
	<option selected value="99">All</option>
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	</select>
	</td>    
	</tr>
	</table>
	';
}
?>

<br>
<table class="uploadTable">
<tr>
<td class="uploadTableLabel">
<?php	
if($reportID==5||$reportID==6)
{
echo '<span id="sectionIcon" class="glyphicon glyphicon-ok icon-size icon-green"></span>';
}
else
{
echo '<span id="sectionIcon" class="glyphicon glyphicon-remove icon-size icon-red"></span>';
}
?>
<label class="reportLabel">Section Filter</label></td>
<td class="uploadTableInput">
<select id="sectionfilter" class="exam_select" name="sectionfilter">
<option selected value="99">All</option>
<option value="A">A</option>
<option value="B">B</option>
<option value="C">C</option>
<option value="D">D</option>
<option value="E">E</option>
<option value="F">F</option>
</select>
</td>    
</tr>
</table>

<?php

if($reportID==5||$reportID==6||$reportID==4)
{
echo
'
<br>
<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><span class="glyphicon glyphicon-ok icon-size icon-green"></span><label class="reportLabel">Subject Filter</label></td>
<td class="uploadTableInput">
<input type="text" id="subjectfilter" class="exam_select" name="subjectfilter" size="50"/>
</td>    
</tr>
</table>
';
}
?>

<?php

if($reportID==5||$reportID==3)
{
echo
'
<br>
<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><span class="glyphicon glyphicon-ok icon-size icon-green"></span><label class="reportLabel">Student Name</label></td>
<td class="uploadTableInput">
<input type="text" id="studentfilter" class="exam_select" name="studentfilter" size="50"/>
</td>    
</tr>
</table>
<br>
<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><span class="glyphicon glyphicon-ok icon-size icon-green"></span><label class="reportLabel">Student Id</label></td>
<td class="uploadTableInput">
<input type="text" id="studentidfilter" class="exam_select" name="studentidfilter" size="50"/>
</td>    
</tr>
</table>
';
}
?>

<?php

if($reportID==5||$reportID==3)
{
echo

'
<br>
<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><span class="glyphicon glyphicon-ok icon-size icon-green"></span><label class="reportLabel">Result Filter</label></td>
<td class="uploadTableInput">
<select id="resultfilter" class="exam_select" name="resultfilter">
<option selected value="99">All</option>
<option value="1">Pass Only</option>
<option value="0">Fail Only</option>
</select>
</td>    
</tr>
</table>
';

}

?>

<br>
<div class="alert alert-danger" role="alert" style="width:90%;margin-left: auto;margin-right: auto;">Warning : Filters can be used to 
restrict the results based on the filters applied. Please use wherever necessary.</div>



<br><br>
<button type="submit" name="submit" class="btn btn-danger">Submit</button><br><br>

</form>

</div>
</div>
</div>

