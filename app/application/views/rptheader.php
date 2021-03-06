<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title ?></title>
<!-- Bootstrap -->
<link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
<!-- jQuery (necessary for Bootstraps JavaScript plugins) -->
<script src="<?php echo base_url();?>js/jquery-1.11.3.min.js"></script>
<link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet">
<script src="<?php echo base_url();?>js/Chart.js"></script>
<style = "text/css">
@font-face {
        font-family: "Play";
        src: url('<?php echo base_url(); ?>css/Play-Regular.ttf');
        font-weight: normal;
        font-style: normal;
    }
html,body
{

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
.clientname
{
text-align: right;
padding-right: 17%;
margin: 0;
padding-top: 2%;
font-family: 'Play', sans-serif;


}
.containerdiv
{
width: 75%;

background: #fff;
margin-left: auto;
margin-right: auto;
border-left: 3px solid #D6D6D6;
border-right: 3px solid #D6D6D6;
}
.leftnav
{
height: 100%;
width: 25%;
border-right : 3px solid #D6D6D6;
float : left;
}

.rightcontainer
{
width: 100%;
height: 100%;
overflow: hidden;
/*background: green;*/
}

.menuitem
{

background: #3AC0CC;
font-size: 1em;
font-style: italic;
color:#333333;
}

.leftnav ul li
{
border-bottom: 1px #D6D6D6;
display: block;
width: 100%;
/*background: blue;*/
padding-top: 6%;
padding-bottom: 6%;
border-bottom: 1px solid #D6D6D6;
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
margin-left: 12%;
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
<h4 class="clientname"><?php echo $client_name ?></h4>
</div>
<div class="containerdiv">
<div class="rightcontainer">