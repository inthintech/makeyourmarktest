<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title ?></title>
<!-- Bootstrap -->
<link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
<link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet">
<!-- jQuery (necessary for Bootstraps JavaScript plugins) -->
<script src="<?php echo base_url();?>js/jquery-1.11.3.min.js"></script>

<style = "text/css">
@font-face {
        font-family: "Play";
        src: url('<?php echo base_url(); ?>css/Play-Regular.ttf');
        font-weight: normal;
        font-style: normal;
    }
html,body
{
      /*background: #B0E0E6;*/
      /*background: #EED5B7;*/
      background: #84A749;
      
      
      height: 100%; 
      width: 100%; 
}
.topbar
{
width:100%;
height: 8%;
background: #333333;
}
.headerdiv
{
width:80%;
height: 80%;
margin-left: auto;
margin-right: auto;
position: relative;
top: 6%;
}
.headertxt
{
margin: 0;
padding: 0;
color: #66CDAA;
color:#EEAD0E;
font-family: 'Play', sans-serif;

position: relative;
top: 36%;
font-size: 1.2em;
float: left;
}

.welcometxt
{
margin: 0;
padding: 0;
color: white;
margin-left: 27%;
font-family: 'Play', sans-serif;


position: relative;
top: 30%;
text-align: center;
font-size: 1.2em;
float: left;
}
#logout
{
float:right;
position: relative;
top:15%;
}
.topbar2
{
width:100%;
height: 12%;
border-bottom: 3px solid #D6D6D6;
background: white;
}
.nameHeader
{
        margin: 0;
        padding-top: 2%;
}
.clientname
{
margin-left:50%;
font-family: 'Play', sans-serif;
}
.loginname
{

margin-left:10%;
font-family: 'Play', sans-serif;
}
.containerdiv
{
width: 100%;
height: <?php echo $container_height ?>%;

background: #fff;
margin-left: auto;
margin-right: auto;
border-left: 3px solid #D6D6D6;
border-right: 3px solid #D6D6D6;

}
.leftnav
{
height: 100%;
width: 23%;
border-right : 3px solid #D6D6D6;
float : left;
background: #7D9EC0;
overflow-y:scroll;
}

.rightcontainer
{
width: 77%;
height: 100%;
background: #B3CB65;
/*margin-left: 30%;*/

float: left;

}

.menuitem
{

/*background: #3AC0CC;*/
background: #3B3B3B;
font-size: 1em;
font-style: italic;
color:white;
}

.leftnav ul li
{
/*border-bottom: 1px #D6D6D6;*/
display: block;
width: 100%;
/*background: blue;*/
padding-top: 6%;
padding-bottom: 6%;
/*border-bottom: 1px solid #D6D6D6;*/
/*border-bottom: 1px solid #696969;*/

}
.leftnav ul
{
list-style-type: none;

padding: 0;
}
.leftnav ul li span
{
margin-left: 8%;

}
.leftnav ul li span a
{
margin-left: 5%;
color: black;

}
.leftnav ul li span a:hover
{
text-decoration: none;
color: red;
}
.footer
{
width: 100%;
height: 25%;
background: #5E5E5E;
}
.footer h5
{

margin: 0;
margin-left: 13%;
padding-top: 1.5%;
color :#DEDEDE;
font-size: 0.9em;
}
.icon-size
{
font-size:1em;
}
</style>
</head>
<body>
<div class="topbar">
<div class="headerdiv">
<h4 class="headertxt">Make Your Mark</h4>
<p class="welcometxt">Welcome to Application Control Panel</p>
<a id="logout" href="<?php echo site_url('login/logout'); ?>" class="btn btn-danger">Logout</a>
</div>
</div>
<div class="topbar2">
<h4 class="nameHeader">
        
<span  class="loginname">Welcome <?php echo $user_name ?>,</span>
<span class="clientname"><?php echo $client_name ?></span>

</h4>
</div>
<div class="containerdiv">
<div class="leftnav">
<ul> 

<li class="menuitem"><span>Administration</span></li>
<li><span><span class="glyphicon glyphicon-home icon-size"></span><a href="<?php echo site_url('adminstatic'); ?>">Home</a></span></li>
<?php
if($usertype==1)
{
echo '<li><span><span class="glyphicon glyphicon-plus icon-size"></span><a href="'.site_url('adminaction/addexam').'">New Exam</a></span></li>';
echo '<li><span><span class="glyphicon glyphicon-th-list icon-size"></span><a href="'.site_url('adminstatic/examstatus').'">Exam Status</a></span></li>';
}
?>
<li><span><span class="glyphicon glyphicon-file icon-size"></span><a href="<?php echo site_url('adminstatic/createcsv'); ?>">How to Create CSV File</a></span></li>
<li><span><span class="glyphicon glyphicon-upload icon-size"></span><a href="<?php echo site_url('upload/form'); ?>">Upload Results</a></span></li>
<li><span><span class="glyphicon glyphicon-search icon-size"></span><a href="<?php echo site_url('verify/exam'); ?>">Verify Results</a></span></li>
<?php
if($usertype==1)
{
echo '<li><span><span class="glyphicon glyphicon-user icon-size"></span><a href="'.site_url('adminaction/adduser').'">Add User</a></span></li>';
echo '<li><span><span class="glyphicon glyphicon-trash icon-size"></span><a href="'.site_url('adminaction/deleteuser').'">Delete User</a></span></li>';
}
?>
<li><span><span class="glyphicon glyphicon-cog icon-size"></span><a href="<?php echo site_url('adminaction/changepassword'); ?>">Change Password</a></span></li>

<li><span><span class="glyphicon glyphicon-globe icon-size"></span><a href="<?php echo site_url('adminstatic/help'); ?>">Feedback / Help</a></span></li>
<li class="menuitem"><span>Generate Reports</span></li>
<li><span><span class="glyphicon glyphicon-stats icon-size"></span><a href="<?php echo site_url('reports/passpercentage'); ?>">Pass Percentage</a></span></li>
<li><span><span class="glyphicon glyphicon-stats icon-size"></span><a href="<?php echo site_url('reports/generate'); ?>">Topper</a></span></li>
<li><span><span class="glyphicon glyphicon-stats icon-size"></span><a href="<?php echo site_url('reports/generate'); ?>">Student Rank List</a></span></li>
<li><span><span class="glyphicon glyphicon-stats icon-size"></span><a href="<?php echo site_url('reports/generate'); ?>">Subject Rank List</a></span></li>
<li><span><span class="glyphicon glyphicon-stats icon-size"></span><a href="<?php echo site_url('reports/generate'); ?>">Student Mark List</a></span></li>

<br>
<br>
</ul>
</div>
<div class="rightcontainer">