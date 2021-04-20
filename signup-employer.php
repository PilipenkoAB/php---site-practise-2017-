<?php
session_start();

if(isset($_SESSION['SessionId'])) {
    include("bd.php");
	$GUID=$_SESSION['SessionId'];
    $res=mysql_query("SELECT * FROM `users` WHERE `SessionGUID`='$GUID' ");
    $user_data=mysql_fetch_array($res);
    $group=$user_data['GroupId'];
	
	if ($group == 1) {
		header("location: freelance-workspace/index.php");
     } else {
	 	header("location: employer-workspace/index.php");
	 }
}
?>

<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<html>

<head>

<link href="styles_index.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
  $(function() {
    $( "#tabs" ).tabs();
  });
  </script>
</head>

<body>

<header  class="GlobalHeader">
<div class="header_1">
<div class="header_1_columns">
logo and Need help? contact us.
</div>
</div>
</header>

<section class="Signup-employer">
<div class="SUE_main">
<div class="SUE_submain">
<div class="SUE_tittle">
Create an account of employer
</div>

<div id="tabs">
  <ul>
    <li><a href="#tabs-1">1</a></li>
    <li><a href="#tabs-2">2</a></li>
  </ul>
   <div id="tabs-1">
<form action="signup-complete.php" name="signup" id="signup" class="SUE_form" method="post">
<div class="SUE_signforms">
<div class="Signup_first_name" id="FirstName">
<input type="text" name="FirstName" id="FirstName" placeholder="Имя" class="Signforms">
</div>
<div class="Signup_last_name" id="LastName">
<input type="text" name="LastName" id="LastName" placeholder="Фамилия" class="Signforms">
</div>
<div class="Signup_email" id="Email">
<input type="text" name="Email" id="Email" placeholder="Email" class="Signforms">
</div>
<div class="Signup_password" id="Password">
<input type="text" name="Password" id="Password" placeholder="Пароль" class="Signforms">
</div>
<div class="Signup_password" id="Password_check">
<input type="text" name="Password_check" id="Password_check" placeholder="Подтвердите пароль" class="Signforms">
</div>
<div class="Signup_captcha" id="Captcha">

</div>
<input type="hidden" name="Type" value="2"  > 
<input type="hidden" name="Company" value=""> 

<input type="submit" name="submit" id="submit" value="GET STARTER" class="Signup_submit">
</div>
</form>
</div>
 <div id="tabs-2">
 <form action="signup-complete.php" name="signup" id="signup" class="SUE_form" method="post">
<div class="SUE_signforms">
<div class="Signup_first_name" id="FirstName">
<input type="text" name="FirstName" id="FirstName" placeholder="Имя" class="Signforms">
</div>
<div class="Signup_last_name" id="LastName">
<input type="text" name="LastName" id="LastName" placeholder="Фамилия" class="Signforms">
</div>
<div class="Signup_company" id="Company">
<input type="text" name="Company" id="Company" placeholder="Company" class="Signforms">
</div>
<div class="Signup_email" id="Email">
<input type="text" name="Email" id="Email" placeholder="Email" class="Signforms">
</div>
<div class="Signup_password" id="Password">
<input type="text" name="Password" id="Password" placeholder="Пароль" class="Signforms">
</div>
<div class="Signup_password" id="Password_check">
<input type="text" name="Password_check" id="Password_check" placeholder="Подтвердите пароль" class="Signforms">
</div>
<div class="Signup_captcha" id="Captcha">

</div>
<input type="hidden" name="Type" value="3"> 


<input type="submit" name="submit" id="submit" value="GET STARTER" class="Signup_submit">
</div>
</form>
 
 </div>
 </div>
</div>
</div>
</section>

<footer class="GlobalFooter">
<div class="GlobalFooterBot">
<div class="GlovalFooterBot_row">
<div class="GlobalFooterBot-col">
<a class="FooterLogo" href="/">
<img src="" alt="SiteName" width="90" height="25">
</a>
<p class="FooterLegal">© 2014 ServiceName.</p>
</div>
</div>
</div>
</footer>
</body>

</html>