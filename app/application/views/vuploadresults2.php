
<div class="subcontainer" style="width:90%;">
<div class="panel panel-default">
<div class="panel-heading">Step 2 - Upload the CSV file with Student Results</div>
<div class="panel-body">

<form class="upload_form" action="<?php echo site_url($formaction);?>" method="POST" enctype="multipart/form-data">

<br>
<p class="upload_header">Upload Results File</p>

<br><br>
<label>Upload the Exam Results CSV File (Refer to How to create CSV section for help)</label>
<br><br>
<input type="file" name="fileToUpload" id="fileToUpload">

<input type="hidden" name="batchIdvalue" value="<?php echo $batch ?>" size="250" class="form-control">

<br><br>
<button type="submit" name="submit" class="btn btn-primary">Submit</button><br><br>
<?php echo validation_errors(); ?>
</form>

</div>
</div>
</div>

