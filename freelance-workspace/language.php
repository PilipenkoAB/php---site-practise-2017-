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
	$id_user=$user_data['id'];
    $group=$user_data['GroupId'];
	if ($group !=1) {
	echo $group;
		header("location: erroronlyfreelancer.php");
	}
	$FirstName=$user_data['FirstName'];
	$LastName=$user_data['LastName'];	
// Тут выборка на показание знания языка 
    $lang=mysql_query("SELECT * FROM `user_tests` WHERE `userid`='$id_user' AND `testid`='1'");
	$lang_data=mysql_fetch_array($lang);
	$point=$lang_data['point'];
	
	// раздел объявления об доступе к заданиям
	$activationemail=$user_data['Activation'];
	
	$activlang=mysql_query("SELECT * FROM `user_tests` WHERE `userid`='$id_user' AND `testid`='1'");
	$activlang_data=mysql_fetch_array($activlang);
	$activationlang=$activlang_data['testid'];
	
	
}
?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<html>
<head>
<link href="/mysite/styles_index.css" rel="stylesheet" type="text/css" />

</head>
<body>
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

<section class="FW_middle">

<nav class="FM_middle_nav">
<h1 class=""> Меню </h1>
<ul class="">
<li>
<a class="" href="profile.php">Личные данные</a>
</li>
<li>
<a class="" href="language.php">Знание языка</a>
</li>
<li>
<a class="" href="noname">Noname</a>
</li>
</ul>
</nav>

<div class="FM_middle_center">


<section class="FW_middle_change_languages">

<div class=""> <!-- Див один на секцию, потому что изменения внутри будут  -->

 <header>
 <h2> Данные о языках</h2>
 </header>
 
 
 <form>
 <div class="">
 <div class="">
 Выбор языка(из списка) 
 </div>
 <div class="">
 <select name="languages" id="languages">
 <option value="English" label="English">English</option>
 </select>
 </div>
</div>

<div class="">
<div class="">
<?php
echo "$point";
?>
 - Показывается уровень знания языка из общего теста как echo 
</div>

</div>

<div class="">
<?php
echo "$point";
?>
 - тут echo с результатом теста и местом в общем рейтинге
</div>

<div class="">
<!--<button type="submit"  id="submit" class="Signup_submit">Перейти к общему тесту</button> -->
<a class="" href="tests/1.php">Перейти к тесту</a>
</div>


</form>


<div class="">
Дополнительно пройденные тесты
информация для чего нужны дополнительные тесты (кратко)
кнопка перехода к тестам
надпись "мои пройденные тесты или результаты"
пройденный тест 1
пройденный тест N
</div>

</div>

</section> 
</div>

</section>

<script type="text/javascript" src="jquery-1.5.1.min.js" charset="utf-8"></script>

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