
<div class="subcontainer" style="width:90%;padding-bottom:5%;margin-top:5%;">
<div class="panel panel-default">
<div class="panel-heading">


Exam Name :
<span class="report_panel_name" style="margin-left:2.6%;";><?php echo $exam_name ?></span>
<br><br>
Report Name :
<span class="report_panel_name" style="margin-left:1.8%;";><?php echo $report_name ?></span>
<br><br>
Level :
<span class="report_panel_name" style="margin-left:7.1%;";><?php echo $level ?></span>


</div>
<div class="panel-body">

<?php echo $chart ?>

<table class="table-bordered report_table" style="width:95%">	
<tr>
<?php echo $table_headers ?>
</tr>
<?php echo $data ?>	
</table>
</div>
</div>
</div>





