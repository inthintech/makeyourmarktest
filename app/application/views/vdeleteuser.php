
<div class="subcontainer" style="width:90%;">
<div class="panel panel-default">
<div class="panel-heading">User Selector</div>
<div class="panel-body">

<form class="exam_form" action="<?php echo site_url('exams/viewexamstatus');?>" method="POST" enctype="multipart/form-data">
<fieldset>
<label>Select a user to delete from the System</label>
<br><br>
<select class="exam_select" name="userid" ><?php echo $userlist ?></select>
</fieldset>  
<br><br>
<button type="submit" name="submit" class="btn btn-primary">Submit</button>

</form>

</div>
</div>
</div>





