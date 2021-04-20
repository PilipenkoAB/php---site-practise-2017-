<?php
/* Старт Сессии */ 
session_start();

/* Проверка на отсутствие Сессии у текущего пользователя */ 
if(!isset($_SESSION['SessionId'])) {
	header("location: errorlogin.php");
}

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

/* Задание переменной для определения GUID из сессии */
    $GUID=$_SESSION['SessionId'];
	
/* Посылаем запрос серверу на выборку информации о текущем пользователе */	
if ($User_Information = $BD->query("SELECT * FROM `users` WHERE `SessionGUID`='$GUID' ")) { 

    /* Выбираем результаты запроса по $User_Information : */ 
$User_Information_Data = $User_Information->fetch_assoc();

// Переменная "Группы" текущего пользователя
    $Group_Id=$User_Information_Data['GroupId'];
	
// Если Пользователь не входит в группу "Заказчиков", то переход на страницу ошибки
	if ($Group_Id !=2) {
		header("location: erroronlyemployer.php");
	}
	
// Переменные "id", "Имя", "Фамилия"  текущего пользователя
	$User_Id=$User_Information_Data['id'];
	$First_Name=$User_Information_Data['FirstName'];
	$Last_Name=$User_Information_Data['LastName'];	

	
// Переменная "Активация Почтового Ящика" текущего пользователя
	$Activation_Email=$User_Information_Data['Activation'];
	
/* Освобождаем память $User_Information */ 
$User_Information->close(); 
}	


	
/* Принятие GET запроса с id  для определения id работы */
$id = htmlspecialchars(trim( isset($_GET['id']) ? (int) $_GET['id'] : 0));


/* Поиск Работы, которая определяется id из GET запроса */
if($Job_Information = $BD->query("SELECT * FROM `Jobs` WHERE `id`='$id' AND `EmployerId`='$User_Id'")){
$Job_Information_Data = $Job_Information->fetch_assoc();

If (empty($Job_Information_Data)) { 
		header("location: errorchoicejobemployer.php");   // если такой работы нет - ошибка
}
   // переменные с информацией о работе
$Jobid=$Job_Information_Data['id'];
$Title=$Job_Information_Data['Title'];
/* Освобождаем память $Job_Information */ 
$Job_Information->close(); 
}









// раздел вывода информации нанятых фрилансерах


if($ResponceJobShow_Information=$BD->query("SELECT * FROM `ResponseNewJob` WHERE `JobId`='$id' ")){ // это для вывода надписи (нет заявок)
$ResponceJobShow_Information_Data = $ResponceJobShow_Information->fetch_assoc();
$FreelancerId=$ResponceJobShow_Information_Data['FreelancerId'];
$ResponceJobShow_Information->close(); 
}


// Раздел для вывода информации об уже принятой заявки

$ResponceJobAccept=$BD->query("SELECT * FROM `ResponseNewJob` WHERE `JobId`='$id' AND `Result`='2' "); // это для вывода выборанной заявки
$ResponceJobAccept_data=$ResponceJobAccept->fetch_assoc();
$FreelancerAcceptId=$ResponceJobAccept_data['FreelancerId'];
$IdJobAccept=$ResponceJobAccept_data['id'];

$FreelancerAcceptBd=$BD->query("SELECT * FROM `users` WHERE `id`='$FreelancerAcceptId' ");
$FreelancerAcceptBd_data=$FreelancerAcceptBd->fetch_assoc();
$FreelancerAcceptFirstName=$FreelancerAcceptBd_data['FirstName'];
$FreelancerAcceptLastName=$FreelancerAcceptBd_data['LastName'];
$FreelancerAcceptAvatar=$FreelancerAcceptBd_data['Avatar'];
$FreelancerAcceptRating=$FreelancerAcceptBd_data['Rating'];

// проверка на то, подписан ли контракт или нет
$ContractCheck=$BD->query("SELECT * FROM `Jobs` WHERE `id`='$id' AND `Active`='1'");
$ContractCheck_data=$ContractCheck->fetch_assoc();





?>

<script type="text/javascript" src="jquery-1.5.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="jquery.min.js" charset="utf-8"></script>


<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<html>
<head>
<link href="/mysite/styles_index.css" rel="stylesheet" type="text/css" />
</head>

<body class="Main_Body">
<div id="Main_Section">



<!-- Главная навигационная панель -->
<header  class="FW_GlobalHeader">

<div class style="  width: 960px;  position: relative;  margin: 0 auto;">
<a class="FW_logo" href="" rel="home"></a>
</div>

<nav class="FW_top_nav">
<ul  class="FW_top_nav_ul">
<li class="FW_top_nav_ul_last">
<!--<a href="index.php" class="FW_top_nav_href_current">Главная</a> -->
<a class="FW_home_icon" href="index.php" rel="home"></a>
</li>
<li class="FW_top_nav_ul_last">
<a href="my-jobs.php" class="FW_top_nav_href">Проекты</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="freelance.php" class="FW_top_nav_href">Фрилансеры</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="messages.php" class="FW_top_nav_href">Сообщения</a>
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
<a class="FW_config_icon" href="index.php" rel="config"></a>
<a class="FW_help_icon" href="index.php" rel="help"></a>
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
<a href="post-job.php" class="FW_top_nav_2_href">Опубликовать проект</a>
</li>
<li class="FW_top_subnav_ul_current">
<a href="my-jobs.php" class="FW_top_nav_2_href">Мои проекты</a>
</li>
<li class="FW_top_subnav_ul">
<a href="archive-jobs.php" class="FW_top_nav_2_href">Архив проектов</a>
</li>
</ul>
</nav>
</div>


</header>

<!-- Объявление об активации учетной записи и начале работы -->
<?php
if( $Activation_Email == 0 ) {
?>
<div id="ActivationAd" class="ActivationAd">
    <p>Для начала работы с сервисом необходимо Активировать Учетную запись:</p><br>
   <p>- Подтвердите почтовый адресс</p>
  
</div>
<?php } ?>
<!-- Окончания объявления об активации -->

<section class="FW_middle">

<nav class="FM_middle_nav">
<h1 class=""> Меню работы </h1>
<ul class="">
<li>
<a class="" href="job.php?id=<?php echo $Jobid ?>">Описание работы</a>
</li>
<li>
<a class="" href="team.php?id=<?php echo $Jobid ?>">Команда</a>
</li>
<li>
<a class="" href="noname.php">______</a>
</li>
</ul>
</nav>

<div class="FM_middle_center">

 <header>
 <h2> Команда Фрилансеров, выполняющих работу </h2>
 <br>
 </header>



<!-- Раздел Нанятые фрилансеры -->

Нанятые Фрилансеры

<!-- Таблица нанятых фрилансеров -->
<table class="Form_My-Jobs">
<thead>
<tr>
<th>Name</th>
<th>Link</th>
</tr>
</thead>
<tbody class="">

<?php
$Job_Active = $BD->query("SELECT * FROM `Jobs` WHERE `id`='$id' AND `Active`='1'");

while( $Job_Active_data=$Job_Active->fetch_assoc()) {

$FreelancerId_Active= $Job_Active_data['FreelancerId'];

$FreelancerBd=$BD->query("SELECT * FROM `users` WHERE `id`='$FreelancerId_Active' ");
$FreelancerBd_data=$FreelancerBd->fetch_assoc();
$FreelancerFirstName_Active=$FreelancerBd_data['FirstName'];
$FreelancerLastName_Active=$FreelancerBd_data['LastName'];

?>
<tr>

<td> <?php echo "$FreelancerFirstName_Active $FreelancerLastName_Active"; ?> </td>

<td><a href="contract.php?id=<?php echo "$id"; ?>">Просмотр Прогресса выполнения</a></td> 

</tr>
<?php } ?>
</tbody>
</table>

<!-- ------ ------------ --------- --------- --->





<!-- Раздел Заявки - Формирование контрактов фрилансеров -->

Формирование контрактов Фрилансеров

<!-- Таблица Заявки - Формирование контрактов фрилансеров -->


<table class="Form_My-Jobs">
<thead>
<tr>
<th>Name</th>
<th>Link</th>
</tr>
</thead>
<tbody class="">

<?php
$Job_Unactive = $BD->query("SELECT * FROM `Jobs` WHERE `id`='$id' AND `Active`='0'");

while( $Job_Unactive_data=$Job_Unactive->fetch_assoc()) {

$FreelancerId_Unactive= $Job_Unactive_data['FreelancerId'];


$FreelancerBd=$BD->query("SELECT * FROM `users` WHERE `id`='$FreelancerId_Unactive' ");
$FreelancerBd_data=$FreelancerBd->fetch_assoc();
$FreelancerFirstName_Unactive=$FreelancerBd_data['FirstName'];
$FreelancerLastName_Unactive=$FreelancerBd_data['LastName'];

?>
<tr>

<td> <?php echo "$FreelancerFirstName_Unactive $FreelancerLastName_Unactive"; ?> </td>

<td><a href="contract.php?id=<?php echo "$id"; ?>">Перейти к формированию контракта</a></td> 

</tr>
<?php } ?>
</tbody>
</table>

<!-- ------ ------------ --------- --------- --->


</div>
</section>




</div>
</body>

<!-- Футер -->
<footer class="GlobalFooter Footer_Big">
<div class="GlobalFooterUp">
<div class="GlobalFooter_col">
<ul>
<li>
<a href="/mysite/about.php">О сервисе</a>
</li>
<li>
<a href="/mysite/howitworks.php">Как это работает</a>
</li>
<li>
<a href="/mysite/blog.php">Новости</a>
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
<?php
/* Закрываем соединение */ 
$BD->close();	
}
?>