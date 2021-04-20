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


?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js" charset="utf-8"></script> <!-- Библиотека для jqiery -->
<html>
<head>
<link href="/mysite/styles_index.css" rel="stylesheet" type="text/css" />

</head>
<body>
<header  class="FW_GlobalHeader">

<nav class="FW_top_nav">
<ul  class="FW_top_nav_ul">
<li class="FW_top_nav_ul_current_index">
<a href="index.php" class="FW_top_nav_href">Главная</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="post-job.php" class="FW_top_nav_href">Опубликовать проект</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="my-jobs.php" class="FW_top_nav_href">Мои проекты</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="freelance.php" class="FW_top_nav_href">Фриланс раздел</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="finance.php" class="FW_top_nav_href">Финансовый раздел</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="messages.php" class="FW_top_nav_href">Общение</a>
</li>
</ul>
<div class="FW_top_nav_username">
<div class="FW_top_user_name">
<?php
echo "$First_Name ";
echo " $Last_Name";
?>
</div>
<div class="FW_top_user_logout">
<a href="/mysite/logout.php" class="FW_top_nav_href">Выход</a>
</div>
</div>
</nav>

<div class="Header_line">
</div>



<div class="header_3">
<nav class="FW_top_subnav">
<ul class="FW_top_subnav_ul">
<li class="FW_top_subnav_ul_current">
<a href="my-jobs.php" class="FW_top_nav_href">Все проекты</a>
</li>
<li class="FW_top_subnav_ul">
<a href="hired.php" class="FW_top_nav_href">Нанятые фрилансеры</a>
</li>
<li class="FW_top_subnav_ul">
<a href="noname.php" class="FW_top_nav_href">Архив</a>
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

<div class="">
Нанятыые фрилансеры
</div>



<!-- Таблица работ -->
<table class="Table_Hired">

<tbody class="">

<?php
if($Freelancers_Information = $BD->query("SELECT * FROM `Contracts`, `Jobs` WHERE Contracts.JobId=Jobs.id AND Jobs.EmployerId='$User_Id' group by Contracts.FreelancerId ")){


while($Freelancers_Information_Data = $Freelancers_Information->fetch_assoc()) {

$FreelancersId = $Freelancers_Information_Data['FreelancerId'];

if($Freelancer_Information = $BD->query("SELECT * FROM `users` WHERE `id`='$FreelancersId' ") ){
$Freelancer_Information_Data = $Freelancer_Information->fetch_assoc();
$FreelancerFirstName = $Freelancer_Information_Data['FirstName'];
$FreelancerLastName = $Freelancer_Information_Data['LastName'];
$FreelancerAvatar = $Freelancer_Information_Data['Avatar'];
$Freelancer_Information->close(); 
}


?>
<tr class="Hired_tr">
<td class="Hired_td"> <img class="Hired_avatar" alt="<?php echo $FreelancerFirstName ?>" src="<?php echo $FreelancerAvatar ?>"> </td>

<td>
<div class="Hired_td_div">
<a class="Hired_Name"   href="/mysite/user.php?id=<?php echo $FreelancersId ?>"> <?php echo "$FreelancerFirstName $FreelancerLastName"; ?> </a>
 <div class="">Принимает участие в _N_ колличестве контрактов&nbsp;</div>
 <a href="#Show_Info_<?php echo $FreelancersId;?>" class="Hired_Show_Job_a" id="Show_Info_<?php echo $FreelancersId;?>">Показать информацию о работе</a>
 </div>
 </td>
 
<td><a href="/mysite/user.php?id=<?php echo $FreelancersId ?>">Просмотр</a></td> 



<tr class="" id="Job_Info<?php echo $FreelancersId;?>"  class style="display: none">
<td class="">
первый участок (под аватаром)
</td>
<td class="">
номера контрактов:
<?php // вывод информации о контрактах, связанных с текущим фрилансером
if($Job_Information = $BD->query("SELECT * FROM `Contracts` WHERE `FreelancerId`='$FreelancersId' ") ){
While($Job_Information_Data = $Job_Information->fetch_assoc()){
$JobId = $Job_Information_Data['id'];


echo " - $JobId";

}
$Job_Information->close(); 
}
?>
</td>
<td class="">
Последний участок - под приглашением в новую работу
</td>
</tr>
<script>
$('#Show_Info_<?php echo $FreelancersId;?>').bind('click', function(){
 $("#Job_Info<?php echo $FreelancersId;?>").toggle();
});
</script>



</tr>

<?php 
		} 
$Freelancers_Information->close(); 
}
?>
</tbody>
</table>




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