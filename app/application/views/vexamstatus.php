
<div class="subcontainer" style="width:80%;top:6%;height:80%;">
<div class="panel panel-primary" style="height:100%;">
<div class="panel-heading">Exam Status</div>
<div class="panel-body" style="height:80%;overflow-y: scroll;">


<table class="data_table" style="width:75%;">	
<tr>
<th>S.No</th>
<th>Exam Name</th>
<th>Created Date</th>
<th>Status</th>
</tr>

<?php echo $examstatus ?>
	
</table>


</div>
</div>
</div>

<!--
<script type="text/javascript">

var ch = $(".containerdiv").height();
var sh = $(".subcontainer").height();
if (sh>ch){
    //code

var newHeight = 100+((ch/sh)*100);
$(".containerdiv").height(newHeight+'%');
}
</script>

-->