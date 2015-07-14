
<div class="subcontainer" style="width:70%;top:3%;">
<div class="panel panel-primary">
<div class="panel-heading centerHead">Upload Results for Examination</div>
<div class="panel-body">

<form class="upload_form" action="<?php echo site_url($formaction);?>" method="POST" enctype="multipart/form-data">

<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><label>Select Exam Name</label></td>
<td class="uploadTableInput"><select name="examid" ><?php echo $examlist ?></select></td>    
</tr>
</table>

<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><label>Enter Staff Name</label></td>
<td class="uploadTableInput">
<input type="text" name="staffname" value="<?php echo set_value('staffname'); ?>" size="250" class="form-control">
</td>    
</tr>
</table>
<?php echo form_error('staffname'); ?>

<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><label>Enter Staff Employee ID</label></td>
<td class="uploadTableInput">
<input type="text" name="staffid" value="<?php echo set_value('staffid'); ?>" size="250" class="form-control">
</td>    
</tr>    
</table>
<?php echo form_error('staffname'); ?>

<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><label>Enter Subject Name</label></td>
<td class="uploadTableInput">
<input type="text" name="subname" value="<?php echo set_value('subname'); ?>" size="250" class="form-control">
</td>    
</tr>    
</table>
<?php echo form_error('subname'); ?>

<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><label>Enter Subject Code</label></td>
<td class="uploadTableInput">
<input type="text" name="subcode" value="<?php echo set_value('subcode'); ?>" size="250" class="form-control">
</td>    
</tr>    
</table>
<?php echo form_error('subcode'); ?>

<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><label>Enter the Maximum Mark for the Subject in the Exam (50,100 etc.)</label></td>
<td class="uploadTableInput">
<input type="text" name="maxmark" value="<?php echo set_value('maxmark'); ?>" size="250" class="form-control">
</td>    
</tr>    
</table>
<?php echo form_error('maxmark'); ?>

<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><label>Enter the Minimum Mark required to Pass this Subject (35,40 etc.)</label></td>
<td class="uploadTableInput">
<input type="text" name="minmark" value="<?php echo set_value('minmark'); ?>" size="250" class="form-control">
</td>    
</tr>    
</table>
<?php echo form_error('minmark'); ?>

<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><label>Select the DEPT code of the Class</label></td>
<td class="uploadTableInput">
<select class="exam_select" name="dept_code" >
<option selected value="CSE">CSE</option>
<option value="IT">IT</option>
<option value="EEE">EEE</option>
<option value="EIE">EIE</option>
<option value="ECE">ECE</option>
<option value="MECH">MECH</option>
<option value="ICE">ICE</option>
</select>
</td>    
</tr>    
</table>

<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><label>Select the Year of the Class</label></td>
<td class="uploadTableInput">
<select class="exam_select" name="year" >
<option selected value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
</select>
</td>    
</tr>    
</table>

<table class="uploadTable">
<tr>
<td class="uploadTableLabel"><label>Select the Section of the Class (if no section then select 'A')</label></td>
<td class="uploadTableInput">
<select class="exam_select" name="section" >
<option selected value="A">A</option>
<option value="B">B</option>
<option value="C">C</option>
<option value="D">D</option>
<option value="E">E</option>
<option value="F">F</option>
</select>
</td>    
</tr>    
</table>




<br><br><br>
<p class="upload_header">Upload Results File</p>

<br><br>
<label style="margin-left:15%;">Upload the Exam Results CSV File (Refer to How to create CSV section for help)</label>
<br><br>
<input type="file" name="fileToUpload" id="fileToUpload">
<br>
<?php echo form_error('fileToUpload'); ?>


<br><br>
<button type="submit" name="submit" class="btn btn-danger">Submit</button><br><br>

</form>

</div>
</div>
</div>

