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

	$ressaved=mysql_query("SELECT * From `SavedJobs` WHERE `FreelancerId`='$id_user'");
	
	
	// раздел объявления об доступе к заданиям
	$activationemail=$user_data['Activation'];
	
	$activlang=mysql_query("SELECT * FROM `user_tests` WHERE `userid`='$id_user' AND `testid`='1'");
	$activlang_data=mysql_fetch_array($activlang);
	$activationlang=$activlang_data['testid'];
	
	// раздел об выводе заданий которые ожидают подтверждения или отзыва
	
	$responsebd=mysql_query("SELECT * FROM `ResponseNewJob` WHERE `FreelancerId`='$id_user' AND `Result`='1' AND `Deleted`='0' AND `Archive`='0'");

	
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
<a href="/mysite/jobs.php" class="FW_top_nav_href">Найти работу</a>
</li>
<li class="FW_top_subnav_ul">
<a href="saved-jobs.php" class="FW_top_nav_href">Сохраненные работы</a>
</li>
<li class="FW_top_subnav_ul">
<a href="accept-request.php" class="FW_top_nav_href">Заявки на работу</a>
</li>
<li class="FW_top_subnav_ul">
<a href="noname.php" class="FW_top_nav_href">----</a>
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
<h1 class=""> Меню ответов на заявки </h1>
<ul class="">
<li>
<a class="" href="accept-request.php">Принятые</a>
</li>
<li>
<a class="" href="waiting-request.php">Ожидающие ответа</a>
</li>
<li>
<a class="" href="reject-request.php">Отклоненные</a>
</li>
<li>
<a class="" href="archive-request.php">Архив</a>
</li>
</ul>
</nav>

<div class="FM_middle_center">


<section class="FW_middle_tests">



 <header>
 <h2>Отклоненные заявки </h2>
 </header>
 
<div class="">
<?php
while( $response_data=mysql_fetch_array($responsebd)) {
$job_id = $response_data['JobId'];
$text = $response_data['Response'];
?>
<div id="<?php echo $job_id; ?>">
    <p><?php echo $job_id; ?></p>
	<p><?php echo $text; ?></p>
	<a href="/mysite/job.php?id=<?php echo $job_id; ?>">Перейти</a> 
	<p>Отправить в архив</p>
	<p>Удалить</p>
</div>
<?php } ?>

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