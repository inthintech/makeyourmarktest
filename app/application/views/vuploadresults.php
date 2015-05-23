
<div class="subcontainer" style="width:90%;">
<div class="panel panel-default">
<div class="panel-heading">Upload Results for the Exam</div>
<div class="panel-body">

<form class="exam_form" action="<?php echo site_url('exams/viewexamstatus');?>" method="POST" enctype="multipart/form-data">
<fieldset>
<label>Select an exam to upload results</label>
<br><br>
<select class="exam_select" name="examid" ><?php echo $examlist ?></select>
</fieldset>  
<br><br>
<button type="submit" name="submit" class="btn btn-primary">Submit</button>

</form>

</div>
</div>
</div>

