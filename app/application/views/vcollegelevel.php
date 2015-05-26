
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
<option selected value="1">CSE</option>
<option value="2">EEE</option>
</select>

<br><br><br><br>
<p class="report_header">Report Information</p>

<br><br>
<div id="info" class="alert alert-success" role="alert">Well done! You successfully read this important alert message. </div>

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
		document.getElementById("info").innerHTML = "The vrepoio efofof frfmoremorf frmfoermforemvorv vrkmvrmvr mninvirenvirv  vkrnvirnvir vrnviernvir vnrvnrev. fherififnif kferfnernfifremffeofrf. kfneifeiwfn";
		break;
		case '2':
		document.getElementById("info").innerHTML = "yes";
		break;
	}
	
}
</script>

</div>
</div>
</div>

