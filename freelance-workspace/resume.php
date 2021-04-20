<?php
session_start();
if(!isset($_SESSION['SessionId'])) {
	header("location: errorlogin.php");
}

if(isset($_SESSION['SessionId'])) {
    include("bd.php");
    $GUID=$_SESSION['SessionId'];
    $res=mysql_query("SELECT * FROM `users` WHERE `SessionGUID`='$GUID' ");
    $user_data=mysql_fetch_array($res);
    $group=$user_data['GroupId'];
	$id_user=$user_data['id'];
	if ($group !=1) {
	echo $group;
		header("location: erroronlyfreelancer.php");
	}
	$FirstName=$user_data['FirstName'];
	$LastName=$user_data['LastName'];	

	// раздел объявления об доступе к заданиям
	$activationemail=$user_data['Activation'];
	
	$activlang=mysql_query("SELECT * FROM `user_tests` WHERE `userid`='$id_user' AND `testid`='1'");
	$activlang_data=mysql_fetch_array($activlang);
	$activationlang=$activlang_data['testid'];
	
	// раздел вывода под фото информации
	
	$regdata=$user_data['RegData'];
	$username=$user_data['Username'];
	
	// раздел вывода правой части вверху

	$countryid=$user_data['Country'];
	$rescountry=mysql_query("SELECT * FROM `Country` WHERE `id`='$countryid' ");
	$country_data=mysql_fetch_array($rescountry);
	
	$country=$country_data['Russian'];
	$city=$user_data['City'];
	$localtime=$user_data['Time-zone'];
		
}
?>

<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<html>
<head>
<link href="/mysite/styles_index.css" rel="stylesheet" type="text/css" />

</head>
<body class="Static Index">
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
echo $FirstName;
echo "_";
echo $LastName;
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
<a href="profile.php" class="FW_top_nav_href">Профиль</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="/mysite/jobs.php" class="FW_top_nav_href">Поиск работы</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="my-jobs.php" class="FW_top_nav_href">Мои контракты</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="inbox-messages.php" class="FW_top_nav_href">Общение</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="/mysite/freelancers.php" class="GH_sl_link"> [ Поиск фрилансеров ] </a>
</li>
</ul>
</nav>

<div class="header_3">
<nav class="FW_top_subnav">
<ul class="FW_top_subnav_ul">
<li class="FW_top_subnav_ul_current">
<a href="profile.php" class="FW_top_nav_href">Личный раздел</a>
</li>
<li class="FW_top_subnav_ul">
<a href="resume.php" class="FW_top_nav_href">Резюме</a>
</li>
<li class="FW_top_subnav_ul">
<a href="finance.php" class="FW_top_nav_href">Финансовые данные</a>
</li>
<li class="FW_top_subnav_ul">
<a href="tests.php" class="FW_top_nav_href">Тесты</a>
</li>
<li class="FW_top_subnav_ul">
<a href="security.php" class="FW_top_nav_href">Безопастность</a>
</li>
</ul>
</nav>
</div>

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

<div class="R_r_f_botton">
редактировать
</div>

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
<a href="/mysite/freelancers.php">Поиск Фрилансеров</a>
</li>
<li>
<a href="/mysite/jobs.php">Поиск Работы</a>
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
