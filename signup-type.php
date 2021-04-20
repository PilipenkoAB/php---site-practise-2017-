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

<!--название страницы-->
<title>Signup-type</title>
<!-- -->

<!-- стили оформления страницы-->
<link href="styles_index.css" rel="stylesheet" type="text/css" />
<!-- -->

</head>

<body class="Main_Body">
<div  id="Main_Section">


<header  class="GlobalHeader">

<div class style="  width: 960px;
  position: relative;
  margin: 0 auto;">
<a class="FW_logo" href="index.php" rel=""></a>
</div>

<div class="header_1">
<div style="float: left;
padding-left: 25rem; color: #EAEEF4;">
Страница выбора типа регистрации
</div>
</div>
</header>

<section class="Signup-type">

<div class="SUS_main">
<div class="SUS_submain">
<div class="SUS_submain_1">
<h1>
Create account 
</h1>
<h3>
Welcome
</h3>
</div>

<div class="SUS_submain_2">
<div class="SUS_s2_w1">
<h3>i am freelancer</h3>
<p> я фрилансер и ищу работу </p>
<a class="SUS_s2_link" href="signup-freelancer.php">Sign Up </a>
</div>
<div class="SUS_s2_w2">
<h3>i am employer</h3>
<p> я работодатель и ищу фрилансеров </p>
<a class="SUS_s2_link" href="signup-employer.php">Sign Up </a>
</div>
</div>

<div class="SUS_submain_3">
<p>Or <a href="login.php">Log In</a> if you already have an account </p>
</div>
</div>
</div>

</section>
</div>


<footer class="Footer_Mini GlobalFooter">

<div class="GlobalFooterBot">

<p class="FooterLegal">© 2016 Freelance Translate.</p>

</div>

</footer>

</body>

</html>