<div class="subcontainer" style="width:70%;top:8%;">
<div class="panel panel-primary">
<div class="panel-heading centerHead">Add a New Exam Entry</div>
<div class="panel-body">

<form class="exam_form" action="<?php echo site_url($formaction);?>" method="POST" enctype="multipart/form-data">

<label>Enter the exam name (Only alphabets,numbers and spaces allowed)</label>
<br>
<input type="text" name="ename" value="<?php echo set_value('ename'); ?>" size="250" class="form-control">
 
<br><br>
<button type="submit" name="submit" class="btn btn-danger">Submit</button>
<?php echo validation_errors(); ?>
</form>


</div>
</div>
</div>



