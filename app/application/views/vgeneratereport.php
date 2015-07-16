
<div class="subcontainer" style="width:70%;top:3%;">
<div class="panel panel-primary">
<div class="panel-heading centerHead">Report Generator</div>
<div class="panel-body">

<form class="upload_form" target="_blank" action="<?php echo site_url('reports/output');?>" method="POST" enctype="multipart/form-data">

<input type="hidden" value="<?php echo $reportID;?>" name="reportid" />

<div id="info" class="alert alert-info" role="alert" style="width:90%;margin-left: auto;margin-right: auto;">The Pass Percentage report displays the percentage of students 
who have passed in all subjects. </div>

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
</td>    
</tr>
</table>






<br>
<div id="level" class="alert alert-warning" role="alert" style="width:90%;margin-left: auto;margin-right: auto;">College level report will be 
generated for the results of students of all departments.</div>

<script>

function myFunctionlevel() {
    var y = document.getElementById("levelID").value;
    switch(y)
	{
		case '1':
		document.getElementById("level").innerHTML = "College level report will be generated for the results of students of all departments.";
		break;
		
		case '2':
		document.getElementById("level").innerHTML = "Department level report will be generated for the results of students of each department in college.";
		document.getElementById("deptIcon").className = "glyphicon glyphicon-ok icon-size icon-green";
		break;
		
		case '3':
		document.getElementById("level").innerHTML = "Year level report will be generated for the results of students of each year in college.";
		break;
		
		case '4':
		document.getElementById("level").innerHTML = "Class level report will be generated for the results of students of each class in college.";
		break;
		
		case '5':
		document.getElementById("level").innerHTML = "Department and Year level report will be generated for the results of students of each department and year in college.";
		break;
		
	}
}

</script>



<br><br>
<p class="upload_header">Filter Selector</p>

<br><br>
<div class="alert alert-warning" role="alert" style="width:90%;margin-left: auto;margin-right: auto;">The X mark before the filter indicate
that the filter has no impact for the input. If it changes to a tick mark then the filter can be applied for the input.</div>

<br>
<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><span id="deptIcon" class="glyphicon glyphicon-remove icon-size icon-red"></span><label class="reportLabel">Dept Filter</label></td>
<td class="uploadTableInput">
<select id="deptfilter" class="exam_select" name="deptfilter">
<option selected value="99">All</option>
<option value="CSE">CSE</option>
<option value="EEE">EEE</option>
<option value="EIE">EIE</option>
</select>
</td>    
</tr>
</table>

<br>
<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><span class="glyphicon glyphicon-remove icon-size icon-red"></span><label class="reportLabel">Year Filter</label></td>
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

<br>
<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><span class="glyphicon glyphicon-remove icon-size icon-red"></span><label class="reportLabel">Section Filter</label></td>
<td class="uploadTableInput">
<select id="sectionfilter" class="exam_select" name="sectionfilter">
<option selected value="99">All</option>
<option value="A">A</option>
<option value="B">B</option>
<option value="B">C</option>
<option value="B">D</option>
<option value="B">E</option>
<option value="B">F</option>
</select>
</td>    
</tr>
</table>

<?php

if($reportID==5)
{
echo
'
<br>
<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><span class="glyphicon glyphicon-remove icon-size icon-red"></span><label class="reportLabel">Subject Filter</label></td>
<td class="uploadTableInput">
<input type="text" id="subjectfilter" class="exam_select" name="subjectfilter" size="50"/>
</td>    
</tr>
</table>
';
}
?>

<?php

if($reportID==5)
{
echo
'
<br>
<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><span class="glyphicon glyphicon-remove icon-size icon-red"></span><label class="reportLabel">Student Filter</label></td>
<td class="uploadTableInput">
<input type="text" id="studentfilter" class="exam_select" name="studentfilter" size="50"/>
</td>    
</tr>
</table>
';
}
?>

<?php

if($reportID==5)
{
echo

'
<br>
<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><span class="glyphicon glyphicon-remove icon-size icon-red"></span><label class="reportLabel">Result Filter</label></td>
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

