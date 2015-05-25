
<div class="subcontainer" style="width:90%;position:relative;top:2%;">
<div class="panel panel-default">
<div class="panel-heading">Upload Results for the Exam</div>
<div class="panel-body">

<form class="upload_form" action="<?php echo site_url('exams/upload');?>" method="POST" enctype="multipart/form-data">

<fieldset>

<p class="upload_header">Staff information</p>

<br><br>
<label>Enter the Staff Name</label>
<br>
<input type="text" name="staffname" value="<?php echo set_value('staffname'); ?>" size="250" class="form-control">

<br><br>
<label>Enter the Staff Employee Id</label>
<br>
<input type="text" name="staffid" value="<?php echo set_value('staffid'); ?>" size="250" class="form-control">

<br><br>
<p class="upload_header">Subject information</p>

<br><br>
<label>Enter the Subject Name</label>
<br>
<input type="text" name="subname" value="<?php echo set_value('subname'); ?>" size="250" class="form-control">

<br><br>
<label>Enter the Subject Code</label>
<br>
<input type="text" name="subcode" value="<?php echo set_value('subcode'); ?>" size="250" class="form-control">

<br><br>
<label>Enter the Maximum Mark for the Subject in the Exam (50,100 etc.)</label>
<br>
<input type="text" name="maxmark" value="<?php echo set_value('maxmark'); ?>" size="250" class="form-control">

<br><br>
<label>Enter the Minimum Mark required to Pass this Subject (35,40 etc.)</label>
<br>
<input type="text" name="minmark" value="<?php echo set_value('minmark'); ?>" size="250" class="form-control">


<br><br>
<p class="upload_header">Class information</p>

<br><br>
<label>Select the DEPT code of the Class</label>
<br><br>
<select class="exam_select" name="dept_code" >
<option selected value="CSE">CSE</option>
</select>

<br><br><br>
<label>Select Year of the Class</label>
<br><br>
<select class="exam_select" name="year" >
<option selected value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
</select>

<br><br><br>
<label>Select the Section of the Class (if no section then select 'A')</label>
<br><br>
<select class="exam_select" name="section" >
<option selected value="A">A</option>
<option value="B">B</option>
<option value="C">C</option>
<option value="D">D</option>
<option value="E">E</option>
<option value="F">F</option>
</select>


<br><br><br>
<p class="upload_header">Exam Information</p>
<br><br>
<label>Select an Exam to Upload Results</label>
<br><br>
<select class="exam_select" name="examid" ><?php echo $examlist ?></select>

<br><br><br>
<p class="upload_header">Upload Results File</p>

<br><br>
<label>Upload the Exam Results CSV File (Refer to How to create CSV section for help)</label>
<br><br>
<input type="file" name="fileToUpload" id="fileToUpload">

<!--
<label>Select an Exam to Upload Results</label>
<br><br>
<select class="exam_select" name="examid" ><?php echo $examlist ?></select>


<br><br><br>
<label>Select the DEPT code</label>
<br><br>
<select class="exam_select" name="dept_code" >
<option selected value="CSE">CSE</option>
</select>

<br><br><br>
<label>Select Year</label>
<br><br>
<select class="exam_select" name="year" >
<option selected value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
</select>

<br><br><br>
<label>Select Section (if no section then select 'A')</label>
<br><br>
<select class="exam_select" name="section" >
<option selected value="A">A</option>
<option value="B">B</option>
<option value="C">C</option>
<option value="D">D</option>
<option value="E">E</option>
<option value="F">F</option>

</select>
-->

</fieldset>  
<br><br>
<button type="submit" name="submit" class="btn btn-primary" style="">Submit</button><br><br>
<?php echo validation_errors(); ?>
</form>

</div>
</div>
</div>

