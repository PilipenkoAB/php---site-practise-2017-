<?php
/* Старт Сессии */ 
session_start();

/* Проверка на наличие Сессии у текущего пользователя */ 
if(isset($_SESSION['SessionId'])) {

/* --------------- ОСНОВНЫЕ ОПЕРАЦИЯ ДЛЯ ПОДКЛЮЧЕНИЯ К СЕРВЕРУ --------------- */

/* Подключение к серверу MySQL */ 
$BD = new mysqli('localhost', 'root', '', 'mysite'); 

if (mysqli_connect_errno()) { 
   printf("Подключение к серверу MySQL невозможно. Код ошибки: %s\n", mysqli_connect_error()); 
   exit; 
} 

/* изменение набора символов на utf8 */
mysqli_set_charset($BD, "utf8");

/* --------------- --------------------------------------------- --------------- */

?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<html>




<head>
<link href="/mysite/styles_index.css" rel="stylesheet" type="text/css" />
</head>
 
 
 
 
 
<body class="Main_Body">
<div id="Main_Section">





<!-- НАЧАЛО ПРОВЕРКИ ЧТО ЭТО ФРИЛАНСЕР или РАБОТОДАТЕЛЬ        -->
<?php


/* Задание переменной для определения GUID из сессии */
    $GUID=$_SESSION['SessionId'];
	
/* Посылаем запрос серверу на выборку информации о текущем пользователе */	
if ($User_Information = $BD->query("SELECT * FROM `users` WHERE `SessionGUID`='$GUID' ")) { 

    /* Выбираем результаты запроса по $User_Information : */ 
$User_Information_Data = $User_Information->fetch_assoc();

// Переменная "Группы" текущего пользователя
    $Group_Id=$User_Information_Data['GroupId'];
	
	
	
	
	
	
	
// Если Пользователь Фрилансер
	if ($Group_Id ==1) {
	
	
	
// Переменные "id", "Имя", "Фамилия"  текущего пользователя
	$User_Id=$User_Information_Data['id'];
	$First_Name=$User_Information_Data['FirstName'];
	$Last_Name=$User_Information_Data['LastName'];	

	
// Переменная "Активация Почтового Ящика" текущего пользователя
	$Activation_Email=$User_Information_Data['Activation'];
		

	

		

	
//	$activlang=mysql_query("SELECT * FROM `user_tests` WHERE `userid`='$id_user' AND `testid`='1'");
//	$activlang_data=mysql_fetch_array($activlang);
//	$activationlang=$activlang_data['testid'];
	
?>


<header  class="FW_GlobalHeader">

<div class style="  width: 960px;  position: relative;  margin: 0 auto;">
<a class="FW_logo" href="" rel="home"></a>
</div>

<nav class="FW_top_nav">
<ul  class="FW_top_nav_ul">
<li class="FW_top_nav_ul_last">
<!--<a href="index.php" class="FW_top_nav_href_current">Главная</a> -->
<a class="FW_home_icon" href="freelance-workspace/index.php" rel="home"></a>
</li>
<li class="FW_top_nav_ul_last">
<a href="freelance-workspace/profile.php" class="FW_top_nav_href">Профиль</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="/mysite/jobs.php" class="FW_top_nav_href">Поиск работы</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="freelance-workspace/my-jobs.php" class="FW_top_nav_href">Мои контракты</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="freelance-workspace/inbox-messages.php" class="FW_top_nav_href">Общение</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="/mysite/freelancers.php" class="FW_top_nav_href">[ Поиск фрилансеров ]</a>
</li>

</ul>
<div class="FW_top_nav_username">
<div class="FW_top_user_name">
<?php
echo "$First_Name ";
echo " $Last_Name";
?>
</div>
<div class="FW_top_user_top">
<!-- <a href="/mysite/logout.php" class="FW_top_nav_href">Выход</a> -->
<a class="FW_config_icon" href="freelance-workspace/index.php" rel="config"></a>
<a class="FW_help_icon" href="freelance-workspace/index.php" rel="help"></a>
<a class="FW_exit_icon" href="/mysite/logout.php" rel="logout"></a>
</div>

<div class="FW_top_user_bot">
<a href="/mysite/logout.php" class="FW_top_nav_href">Нижняя панелька какая то</a>

</div>
</div>
</nav>



<!-- Панель дополнительных ссылок. второй ряд -->
<div class="header_3">
<nav class="FW_top_subnav">
<ul class="FW_top_subnav_ul">
<li class="FW_top_subnav_ul_current">
<a href="/mysite/jobs.php" class="FW_top_nav_2_href">Найти работу</a>
</li>
<li class="FW_top_subnav_ul_current">
<a href="freelance-workspace/saved-jobs.php" class="FW_top_nav_2_href">Сохраненные работы</a>
</li>
<li class="FW_top_subnav_ul">
<a href="freelance-workspace/accept-request.php" class="FW_top_nav_2_href">Заявки на работу</a>
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

<?php 
}
// НАЧАЛО ПРОВЕРКИ НА РАБОТОДАТЕЛЯ
	if ($Group_Id ==2) {
	
// Переменные "id", "Имя", "Фамилия"  текущего пользователя
	$User_Id=$User_Information_Data['id'];
	$First_Name=$User_Information_Data['FirstName'];
	$Last_Name=$User_Information_Data['LastName'];	

	
// Переменная "Активация Почтового Ящика" текущего пользователя
	$Activation_Email=$User_Information_Data['Activation'];
	
?>
<header  class="FW_GlobalHeader">
<div class="header_1">
<div class="header_1_columns">
logo 
</div>
<div class="FW_top_user">
<div class="">
<a href="employer-workspace/index.php" class="">Главная</a>
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
<a href="employer-workspace/post-job.php" class="FW_top_nav_href">Сделать работу(?)</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="employer-workspace/my-jobs.php" class="FW_top_nav_href">Контроль работ(?)</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="employer-workspace/noname.php" class="FW_top_nav_href">Фриланс раздел(?)</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="employer-workspace/noname.php" class="FW_top_nav_href">Финансовый раздел(?)</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="employer-workspace/message.php" class="FW_top_nav_href">Общение (?)</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="/mysite/freelancers.php" class="GH_sl_link"> [ Поиск фрилансеров ] </a>
</li>
</ul>
</nav>

</header>
<?php
} 


/* Освобождаем память $User_Information */ 
$User_Information->close(); 
}	

?>
<!--  ОКОНЧАНИЕ ПРОВЕРКИ ЧТО ЭТО ФРИЛАНСЕР    или РАБОТОДАТЕЛЬ     -->
<!-- начало для незарегистрированного -->


<?php
/* Проверка на отсутствие Сессии у текущего пользователя */ 
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


/* Посылаем запрос серверу на выборку информации о Jobs */	
if ($Jobs_Information = $BD->query("SELECT * FROM `Jobs` WHERE `Search`='1' ORDER BY `DataCreation` DESC")) { 

while($Jobs_Information_Data = $Jobs_Information->fetch_assoc()) {

$post_id = $Jobs_Information_Data['id'];
$text = $Jobs_Information_Data['EmployerId']; 
$title = $Jobs_Information_Data['Title'];  
$data = $Jobs_Information_Data['DataCreation'];  
?>
<div id="<?php echo $post_id; ?>">
    <p><?php echo $text; ?></p>
	<p><?php echo $title; ?></p>
	<p><?php echo $data; ?></p>
	<a href="job.php?id=<?php echo $post_id; ?>">Перейти</a> 
	<br><br><br>
</div>
<?php
 }
/* Освобождаем память $Jobs_Information */ 
$Jobs_Information->close(); 
} ?>
</div>

</div>
</section>







</div>
</body>






<footer class="GlobalFooter Footer_Big">
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
<?php
/* Закрываем соединение */ 
$BD->close();	
}