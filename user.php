<?php
session_start();
 include("bd.php");
 $groupid=1;
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$res=mysql_query("SELECT * FROM `users` WHERE `id`='$id' AND `GroupId`='$groupid' ");
    $user_data=mysql_fetch_array($res);
	
	$FirstName=$user_data['FirstName'];
	$LastName=$user_data['LastName'];	
	$regdata=$user_data['RegData'];
	$username=$user_data['Username'];
	$countryid=$user_data['Country'];
	$rescountry=mysql_query("SELECT * FROM `Country` WHERE `id`='$countryid' ");
	$country_data=mysql_fetch_array($rescountry);
	
	$country=$country_data['Russian'];
	$city=$user_data['City'];
	$localtime=$user_data['Time-zone'];
	

?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<html>
 
 <head>
<link href="/mysite/styles_index.css" rel="stylesheet" type="text/css" />

</head>
 
<body>


<!-- НАЧАЛО ПРОВЕРКИ ЧТО ЭТО ФРИЛАНСЕР или РАБОТОДАТЕЛЬ        -->
<?php
// проверка на фрилансера
	if(isset($_SESSION['SessionId'])) {
	$GUID=$_SESSION['SessionId'];
	$res1=mysql_query("SELECT * FROM `users` WHERE `SessionGUID`='$GUID' ");	
		$user_data1=mysql_fetch_array($res1);
	$group=$user_data1['GroupId'];
	if ($group == 1) {
	

		$FirstNameAcc=$user_data1['FirstName'];
	$LastNameAcc=$user_data1['LastName'];
	$id_user=$user_data1['id'];
	
		// раздел объявления об доступе к заданиям
	$activationemail=$user_data1['Activation'];
	
	$activlang=mysql_query("SELECT * FROM `user_tests` WHERE `userid`='$id_user' AND `testid`='1'");
	$activlang_data=mysql_fetch_array($activlang);
	$activationlang=$activlang_data['testid'];
	
?>
<header  class="FW_GlobalHeader">
<div class="header_1">
<div class="header_1_columns">
logo 
</div>
<div class="FW_top_user">
<div class="">
<a href="index.php" class="">Главная</a>
</div>
<div class="FW_top_user_name">
<?php
echo $FirstNameAcc;
echo "_";
echo $LastNameAcc;
?>
</div>
<div class="FW_top_user_logout">
<a href="/mysite/logout.php" class="FW_top_nav_href">Выход</a>
</div>
</div>
</div>

<nav class="FW_top_nav">
<ul  class="FW_top_nav_ul">
<li class="FW_top_nav_ul_current">
<a href="freelance-workspace/profile.php" class="FW_top_nav_href">Профиль</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="/mysite/jobs.php" class="FW_top_nav_href">Поиск работы</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="freelance-workspace/my-jobs.php" class="FW_top_nav_href">Мои работы</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="freelance-workspace/inbox-messages.php" class="FW_top_nav_href">Общение</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="freelancers.php" class="GH_sl_link"> [ Поиск фрилансеров ]</a>
</li>
</ul>
</nav>

</header>

<!-- Объявление об активации учетной записи и начале работы -->
<?php
if( $activationemail == 0 || $activationlang != 1) {
?>
<div id="ActivationAd" class="ActivationAd">
    <p>Для начала работы с сервисом необходимо Активировать Учетную запись:</p><br>
<?php
if( $activationemail == 0) {
?>
   <p>- Подтвердите почтовый адресс</p>
   <?php } ?>
  
<?php
if( $activationlang != 1) {
?>
   <p>- Подтвердите знания языка и пройдите общий тест</p>
   <?php } ?>
</div>
<?php } ?>
<!-- Окончания объявления об активации -->

<?php 
}
// НАЧАЛО ПРОВЕРКИ НА РАБОТОДАТЕЛЯ
if ($group == 2) {
echo "область работодателя";
} 
}
?>
<!--  ОКОНЧАНИЕ ПРОВЕРКИ ЧТО ЭТО ФРИЛАНСЕР    или РАБОТОДАТЕЛЬ     -->

<!-- начало для незарегистрированного -->
<?php
if(!isset($_SESSION['SessionId'])) {
?>
<header class="GlobalHeader">
<div class="header_1">
<div class="header_1_columns">
<div class="logo">
<a href="/">
<img src="logo.png" width="125" height="35">
</a>
</div>
<nav class="GH_links">
<ul class="site_links">
<li>
<a href="freelancers.php" class="GH_sl_link">Поиск фрилансеров</a>
</li>
<li>
<a href="nopage.php" class="GH_sl_link">Ссылка 2</a>
</li>
<li>
<a href="nopage.php" class="GH_sl_link">Ссылка на что то 3</a>
</li>
<li>
<a href="nopage.php" class="GH_sl_link">Ссылка на что то 4</a>
</li>
</ul>
<ul class="user_links">
<li>
<a href="login.php" class="GH_h1_login">log in</a>
</li>
<li>
<a href="signup-type.php" class="GH_h1_signup">sign up</a>
</li>
</ul>
</nav>
</div>
</div>
</header>
<?php 
}
?>
<!-- ОКОНЧАНИЕ для незарегистрированного -->

<Section class="FW_middle">

<div class="R_left">
<div class="R_l_photo">
<img id="ProfileImage" class="ProfileImage" alt="<?php echo "$FirstName $LastName" ?>" src="" style="border: solid 1px #CCC; max-width:160px; max-height:160px; height:expression(this.height >159 ? 160:true); width:expression(this.width >159 ? 160:true);">
</div>

<div class="R_l_info">
<h5 class="">Идентификация</h5>
<div class="">
username - <?php echo "$username" ?>
</div>
<div class="">
Зарегистрирован - <?php echo "$regdata" ?>
</div>
<div class="">
Вертификация
</div>
</div>
</div>

<div class="R_right">
<div class="R_r_first">
<div class="R_r_f_nametittle">
<div class="R_r_f_name">
<h1 class="" > <?php echo "$FirstName $LastName" ?> </h1>
</div>
<h5 class="">титл описания себя</h5>
</div>

<!-- тут разметка видимости кнопки для разных видов зарегистрированных -->
<?php

	if(isset($_SESSION['SessionId'])) {
	$GUID=$_SESSION['SessionId'];
	$res1=mysql_query("SELECT * FROM `users` WHERE `SessionGUID`='$GUID' ");
	$user_data1=mysql_fetch_array($res1);
	$group=$user_data1['GroupId'];


if ($group == 1) {
?>
<div class="R_r_f_botton">
отправить сообщение (кнопка)
</div>
<?php }
if ($group == 2) {
?>
<div class="R_r_f_botton">
нанять (кнопка) 
</div>
<?php }

	}
?>
<!-- окончание видимости кнопки -->

<div class="R_r_f_info">
<div class="R_r_f_info_in">
<?php echo "$country" ?> |
</div>
<div class="R_r_f_info_in">
<?php echo "$city" ?> |
</div>
<div class="R_r_f_info_in">
<?php echo "$localtime" ?>  - локальное время
</div>
</div>
</div>
<div class="R_r_second">
<div class="R_r_s_overview">
<h4 class=""> OverView</h4>
<br>
<p class=""> тут описание себя </p>
</div>
<div class="R_r_s_tests">
<div class="генеральный тест">
тут результаты генерального теста
</div>
<div class="дополнительные тесты">
тут результаты дополнительных тестов
</div>
</div>
</div>

</div>

</section>

</body>

<footer class="GlobalFooter">
<div class="GlobalFooterUp">
<div class="GlobalFooter_col">
<ul>
<li>
<a href="/noname.php">О сервисе</a>
</li>
<li>
<a href="/noname.php">Новости</a>
</li>
</ul>
</div>
<div class="GlobalFooter_col">
<ul>
<li>
<a href="/noname.php">Поиск Фрилансеров</a>
</li>
<li>
<a href="/noname.php">Поиск Работы</a>
</li>
</ul>
</div>
<div class="GlobalFooter_col">
<ul>
<li>
<a href="/noname.php">Помощь</a>
</li>
<li>
<a href="/noname.php">Правила сервиса</a>
</li>
<li>
<a href="/noname.php">Контакты</a>
</li>
</ul>
</div>
</div>
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

 
</html>