
<div class="subcontainer" style="width:80%;top:4%;">
<div class="panel panel-primary">
<div class="panel-heading">How to Create CSV File</div>
<div class="panel-body">

<p class="inst">

<br>

This tutorial is only applicable for users who have installed Microsoft Excel (Any Version) in their systems.

<br><br>

Please follow the steps below :

<br><br>

1) Create an excel file exactly like the file found <a href="<?php echo base_url(); ?>css/sampledownload.php" style="text-decoration:underline">here</a>. (Click to download the sample)

<br><br>

2) The column headers are explained below

<span style="color:#8B008B">

<br><br>

<?php
if($this->session->userdata('client_type')==1)
{
    echo 'STUDENT_ID: Enter the student id. (eg) SR8976, VT7654';
}
else
{
    echo 'STUDENT_ID: Enter the student roll number. (eg) 28, 17';
}
?>


<br><br>
STUDENT_NAME: Enter the student name. (eg) RAVI, ASHOK 

<br><br>
MARKS_OBTAINED: Enter the marks obtained by the student in the subject (eg) 37, 47, 86 etc 

</span>

<br><br>

<span style="color:red";>
3) IMPORTANT : If any student is absent or his/her result not available for the particular subject please include the record with 0 (zero) Marks.
If the record is missed for any student then it causes problem in the application during calculation.
</span>

<br><br>

4) When the excel file is ready, CSV version can be created very easily. Follow the below commands.

<br><br>

In the excel application click FILE > SAVE AS.

<br><br>

In the dialog box that opens you need to select the save as type as CSV (Comma delimited) (*.csv) and click SAVE button. Do not select any other CSV formats.
You can choose any file name. For more info see image below.

<br><br>
<img style="width:80%;margin-left:10%;" src='<?php echo base_url(); ?>css/save-excel-csv.png'/>

</p>


</div>
</div>
</div>

