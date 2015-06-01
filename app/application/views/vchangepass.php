
<div class="subcontainer" style="width:90%;">
<div class="panel panel-default">
<div class="panel-heading">Change Your Password</div>
<div class="panel-body">

<form class="exam_form" action="<?php echo site_url('exams/changepasswordstatus');?>" method="POST" enctype="multipart/form-data">

<fieldset>

<label>Enter your old password</label>
<br>
<input type="password" name="oldpass" value="<?php echo set_value('oldpass'); ?>" size="250" class="form-control">


<br><br>
<label>Enter the new password</label>
<br>
<input type="text" name="newpass" value="<?php echo set_value('newpass'); ?>" size="250" class="form-control">


</fieldset>  
<br><br>
<button type="submit" name="submit" class="btn btn-primary">Submit</button><br><br>
<?php echo validation_errors(); ?>
</form>


</div>
</div>
</div>

