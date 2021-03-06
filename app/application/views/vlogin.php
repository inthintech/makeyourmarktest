<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Please log in</title>
<!-- Bootstrap -->
<link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">

<style type="text/css">
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
.logindiv
{
width: 35%;

background: #fff;
margin-left: auto;
margin-right: auto;
margin-top: 8%;
border-radius: 5px;
}

.welcome {

margin-left: 3%;
padding-top: 6%;
font-family: Georgia, serif;
font-weight: bold;
font-size: 1.8em;

}

.lform 
{
margin-top: 8%;
padding-bottom: 10%;
}

.lform fieldset
{
margin-left: 4%;
padding-bottom: 8%;
}

.lform fieldset label
{
font-size: 1.1em;
float: left;
width: 37%;
}
.lform fieldset input
{

float: left;
width: 55%;
font-size: 0.9em;
background: #F0F0F0;
}
.lform button
{
width:20%;
margin-left: 72%;
}

.invalid
{
margin-top: 5%;
color: red;
font-size: 0.9em;
text-align: center;
}
.logout
{
margin-top: 5%;
color: black;
font-size: 0.9em;
text-align: center;
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
color: #EEAD0E;

font-family: 'Play', sans-serif;
position: relative;
top: 34%;
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

</style>

</head>
<body>
<div class="topbar">
<div class="headerdiv">
<h5 class="headertxt">Make Your Mark</h5>

<p class="welcometxt">Welcome to Application Control Panel</p>
</div>
</div>
<div class="logindiv">
<h2 class="welcome">Please log in</h2>
<form class="lform" action="<?php echo site_url('login/verify');?>" method="POST">
<fieldset>
<label>Username</label>
<input type="text" name="username" value="<?php echo set_value('username'); ?>" class="form-control">
</fieldset>  
<fieldset>
<label>Password</label>
<input type="password" name="password" value="<?php echo set_value('password'); ?>" class="form-control">
</fieldset>  
<button type="submit" name="submit" class="btn btn-danger">Sign In</button>
<?php echo validation_errors(); ?>
</form>

</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo base_url();?>js/jquery-1.11.3.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
</script>
<script>

var elem = document.createElement( "canvas" );
 
if ( elem.getContext && elem.getContext( "2d" ) ) {
    
} else {
    $(".logindiv").empty();
    $(".logindiv").html('<p style="font-size:1.2em;padding:5%;">You are using an unsupported browser.<br><br>Please use a HTML5 supported browser.<br><br><a href="http://www.w3schools.com/html/html5_canvas.asp">Click here</a> for list of HTML5 supported browsers.</p>');

    
}  

</script>


</body>
</html>