<?php
session_start();
include("bd.php");

// НАЧАЛО ПРОВЕРКИ ЧТО ЭТО ФРИЛАНСЕР или РАБОТОДАТЕЛЬ    

	if(isset($_SESSION['SessionId'])) {
	$GUID=$_SESSION['SessionId'];
	$res1=mysql_query("SELECT * FROM `users` WHERE `SessionGUID`='$GUID' ");	
	$user_data1=mysql_fetch_array($res1);
	$group=$user_data1['GroupId'];
	
	$FirstName=$user_data1['FirstName'];
	$LastName=$user_data1['LastName'];
	$id_user=$user_data1['id'];
	
		// раздел объявления об доступе к заданиям
	$activationemail=$user_data1['Activation'];

	
// вывод всех фрилансеров
$resfree=mysql_query("SELECT * FROM `users` WHERE `GroupId`='1' ORDER BY `Rating` DESC");
?>


<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<html>
<head>
<link href="/mysite/styles_index.css" rel="stylesheet" type="text/css" />
</head>
 
<body>


<!-- ОБЛАСТЬ ФРИЛАНСЕРА -->
<?php
// проверка на фрилансера
	if ($group == 1) {
	
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
?>
<!--  ОКОНЧАНИЕ ОБЛАСТИ ФРИЛАНСЕРА    -->





<?php
// НАЧАЛО ПРОВЕРКИ НА РАБОТОДАТЕЛЯ
if ($group == 2) {
?>

<header  class="FW_GlobalHeader">

<nav class="FW_top_nav">
<ul  class="FW_top_nav_ul">
<li class="FW_top_nav_ul_current_index">
<a href="employer-workspace/index.php" class="FW_top_nav_href">Главная</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="employer-workspace/post-job.php" class="FW_top_nav_href">Опубликовать проект</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="employer-workspace/my-jobs.php" class="FW_top_nav_href">Мои проекты</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="employer-workspace/noname.php" class="FW_top_nav_href">Фриланс раздел</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="employer-workspace/finance.php" class="FW_top_nav_href">Финансовый раздел</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="employer-workspace/input-messages.php" class="FW_top_nav_href">Общение</a>
</li>
</ul>
<div class="FW_top_nav_username">
<div class="FW_top_user_name">
<?php
echo "$FirstName ";
echo " $LastName";
?>
</div>
<div class="FW_top_user_logout">
<a href="/mysite/logout.php" class="FW_top_nav_href">Выход</a>
</div>
</div>
</nav>

<div class="Header_line">
</div>

</header>

<!-- Объявление об активации учетной записи и начале работы -->
<?php
if( $activationemail == 0 ) {
?>
<div id="ActivationAd" class="ActivationAd">
    <p>Для начала работы с сервисом необходимо Активировать Учетную запись:</p><br>
<?php
if( $activationemail == 0) {
?>
   <p>- Подтвердите почтовый адресс</p>
   <?php } ?>
  
</div>
<?php } ?>
<!-- Окончания объявления об активации -->


<?php
		} 
}
?>



<!--  ОКОНЧАНИЕ ОБЛАСТИ РАБОТОДАТЕЛЯ     -->
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
<a href="jobs.php" class="GH_sl_link">Поиск работы</a>
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


<section class="FW_middle">
<div class="поиск">
Критерии поиска 
</div>
<div class="результаты поиска">

Результаты поиска

<div class="">
<?php
while( $user_data=mysql_fetch_array($resfree)) {
$Freelancer_id = $user_data['id'];
$text = $user_data['Username'];
$rating = $user_data['Rating'];
?>
<div id="<?php echo $Freelancer_id; ?>">
    <p><?php echo $text; ?></p>
	<p><?php echo $rating; ?></p>
    <button type="button" class="button">Show</button>
	<br>
	
	
<!-- Раздел разных кнопок для разных видов фрилансера или работодателя (??? или нанять) -->	
<?php
if($group == 1) {
?>
неизвестная кнопка для фрилансера ещё
<?php
} elseif($group ==2) {
?>	
<a href="employer-workspace/invite.php?id=<?php echo $Freelancer_id;?>">Нанять</a>
<?php
}
?>	
	
</div>
<?php } ?>
</div>

</div>
</section>


</body>

<footer class="GlobalFooter">
<div class="GlobalFooterUp">
<div class="GlobalFooter_col">
<ul>
<li>
<a href="/mysite/about.php">О сервисе</a>
</li>
<li>
<a href="/mysite/news.php">Новости</a>
</li>
</ul>
</div>
<div class="GlobalFooter_col">
<ul>
<li>
<a href="/mysite/freelancers.php">Поиск Фрилансеров</a>
</li>
<li>
<a href="/mysite/noname.php">Поиск Работы</a>
</li>
</ul>
</div>
<div class="GlobalFooter_col">
<ul>
<li>
<a href="/mysite/noname.php">Помощь</a>
</li>
<li>
<a href="/mysite/noname.php">Правила сервиса</a>
</li>
<li>
<a href="/mysite/contacts.php">Контакты</a>
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