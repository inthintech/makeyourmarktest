
<div class="subcontainer" style="width:70%;top:8%;">
<div class="panel panel-primary">
<div class="panel-heading centerHead">User Selector</div>
<div class="panel-body">

<form class="exam_form" action="<?php echo site_url($formaction);?>" method="POST" enctype="multipart/form-data">

<label>Select a user to delete from the System</label>
<br><br>
<select name="userid" ><?php echo $userlist ?></select>
<br>
<br><br>
<button type="submit" name="submit" class="btn btn-danger">Submit</button>

</form>

</div>
</div>
</div>





