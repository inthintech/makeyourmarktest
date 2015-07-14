
<div class="subcontainer" style="width:70%;top:8%;">
<div class="panel panel-primary">
<div class="panel-heading centerHead">Verify Uploaded Results</div>
<div class="panel-body">

<form class="exam_form" action="<?php echo site_url($formaction);?>" method="POST" enctype="multipart/form-data">

<label>Select an exam to verify the results</label>
<br><br>
<select class="exam_select" name="examid" ><?php echo $examlist ?></select>
<br>
<br><br>
<button type="submit" name="submit" class="btn btn-danger">Submit</button>

</form>

</div>
</div>
</div>





