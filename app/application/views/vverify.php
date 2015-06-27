
<div class="subcontainer" style="width:90%;">
<div class="panel panel-default">
<div class="panel-heading">Verify Uploaded Results</div>
<div class="panel-body">

<form class="exam_form" action="<?php echo site_url($formaction);?>" method="POST" enctype="multipart/form-data">

<label>Select an exam to verify the results</label>
<br><br>
<select class="exam_select" name="examid" ><?php echo $examlist ?></select>
<br>
<br><br>
<button type="submit" name="submit" class="btn btn-primary">Submit</button>

</form>

</div>
</div>
</div>





