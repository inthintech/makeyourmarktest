<div class="subcontainer" style="width:90%;">
<div class="panel panel-default">
<div class="panel-heading">Exam Status</div>
<div class="panel-body">

<form class="exam_form" action="<?php echo site_url('exams/viewexamstatus');?>" method="POST" enctype="multipart/form-data">
<fieldset>
<label>Select an exam to see the status</label>
<br>
<select class="exam_select" name="examid" ><?php echo $examlist ?></select>
</fieldset>  
<br><br>
<button type="submit" name="submit" class="btn btn-primary">Submit</button>

</form>

<table class="data_table" style="width:95%">	
<tr>
<th>S.No</th>
<th>Exam Name</th>
<th>Created Date</th>
<th>Status</th>
</tr>

<?php echo $examstatus ?>
	
</table>


</div>
</div>
</div>



