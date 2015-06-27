
<div class="subcontainer" style="width:90%;">
<div class="panel panel-default">
<div class="panel-heading">Add a New User</div>
<div class="panel-body">

<form class="exam_form" action="<?php echo site_url($formaction);?>" method="POST" enctype="multipart/form-data">



<label>Enter the User Name</label>
<br>
<input type="text" name="uname" value="<?php echo set_value('uname'); ?>" size="250" class="form-control">


<br><br>
<label>Enter the Password</label>
<br>
<input type="password" name="pass" value="<?php echo set_value('pass'); ?>" size="250" class="form-control">

 
<br><br>
<button type="submit" name="submit" class="btn btn-primary">Submit</button><br><br>
<?php echo validation_errors(); ?>
</form>

</div>
</div>
</div>

