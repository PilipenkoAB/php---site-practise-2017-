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
	if ($Group_Id !=1) {
		header("location: erroronlyfreelancer.php");
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
	
	
	
	
	
	
	// узнаем какая от какой работы пришли 
	$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	
if ($Job_Information = $BD->query("SELECT * FROM `jobs` WHERE `id`='$id' ")) { 

$Job_Information_Data = $Job_Information->fetch_assoc();	
$idjob=$Job_Information_Data['id'];
$titlejob=$Job_Information_Data['Title'];
$descriptionjob=$Job_Information_Data['Description'];
$testjob=$Job_Information_Data['TestJob'];
if(empty($idjob)){
header("location: erroronapplyjob.php");
}
$Job_Information->close(); 
}

	// берем информацию о задании и проверяем на наличие вообще такого задания, если его нет - то ошибка
	//$infojob=mysql_query("SELECT * FROM `jobs` WHERE `id`='$id' ");
    //$infojob_data=mysql_fetch_array($infojob);
   // $idjob=$infojob_data['id'];
	//$titlejob=$infojob_data['Title'];
	//$descriptionjob=$infojob_data['Description'];
	//$testjob=$infojob_data['TestJob'];
	//if(empty($idjob)){
	//header("location: erroronapplyjob.php");
	//}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	// информация о тесте, есть ли он и если да, то показываем его
	
	$testjobid=0;
	$Readytest=0;
	
	if($testjob != 0) {
	$infotestjob=mysql_query("SELECT * FROM `TestJob` WHERE `id`='$testjob' ");
    $infotestjob_data=mysql_fetch_array($infotestjob);
    $descriptiontestjob=$infotestjob_data['Description'];
	$texttestjob=$infotestjob_data['Text'];
	$testjobid=$infotestjob_data['id'];
	}
	
	
	//Валидация Заполнения и выполнения принятия задания
	$error = 0;
	
	
	
	
	
	if(isset($_POST['Answerjob']) && isset($_POST['Pricejob']) && isset($_POST['Durationjob'])) {
	
	$Answerjob=htmlspecialchars($_POST['Answerjob']);
	$Pricejob=htmlspecialchars($_POST['Pricejob']);	
	$Durationjob=htmlspecialchars($_POST['Durationjob']);
	
	
	/* Задание Времени прихода данных (Для указания времени регистрации задания) */	
	$DataCreation=date('Y-m-d H:i:s');
	
	$AnswerJobCheck=trim($Answerjob); // проверка на пробелы (чтобы нельзя было прислать просто пробелы)
	if(empty($AnswerJobCheck)) { // если ничего не пришло 
	$error = 1; 
	echo "AnswerError,";
		}
	
	if($Durationjob != 1 && $Durationjob != 2 && $Durationjob != 3 && $Durationjob != 4 && $Durationjob != 5 && $Durationjob != 6) {
	$error = 1;
	echo "errorDuration,";
	} 
	
	if($Pricejob < 1) {
	$error = 1;
	echo "errorPrice,";
	}
	
	
	if(isset($_POST['Readytest']) && $testjob != 0) {
	$Readytest=htmlspecialchars($_POST['Readytest']);
	
	$ReadytestCheck=trim($Readytest); // проверка на пробелы (чтобы нельзя было прислать просто пробелы)
	if(empty($ReadytestCheck)) { // если ничего не пришло 
	$error = 1; 
	echo "ReadytestError,";
		}
	}
	// если нет ошибок заносим отзыв в бд - если есть - то пропускаем занесение в бд и выводим окно об ошибках
	if($error != 1) {
		echo "ну тип прошло йопта";
		
		
		/* Заносим в БД новую работу */		
if(!$BD->query("INSERT INTO `ResponseNewJob`(`FreelancerId`,`JobId`,`Response`,`Price`,`Duration`,`TestJobAnswer`,`DataResponse`) VALUES ('$User_Id','$id','$Answerjob','$Pricejob','$Durationjob','$Readytest','$DataCreation')")){
 printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
}else{
header("location: index.php");			// если операция прошла успешно
	 }
		
	//$RegisterApplyJob="INSERT INTO `ResponseNewJob`(`FreelancerId`,`JobId`,`Response`,`Price`,`Duration`,`TestJobId`,`TestJobAnswer`) VALUES ('$id_user','$id','$Answerjob','$Pricejob','$Durationjob','$testjobid','$Readytest')";
	//$ResultApplyJob=mysql_query($RegisterApplyJob);
	//if($ResultApplyJob == true)
	//{
	//	header("location: noname.php");
	//} else {
//echo "Error! ---->". mysql_error();
//}



		}
	}

?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<html>
<head>
<link href="/mysite/styles_index.css" rel="stylesheet" type="text/css" />

</head>



<body class="Main_Body">
<div id="Main_Section">



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
<a href="saved-jobs.php" class="FW_top_nav_2_href">Сохраненные работы</a>
</li>
<li class="FW_top_subnav_ul">
<a href="accept-request.php" class="FW_top_nav_2_href">Заявки на работу</a>
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

<div class="FM_middle_center">


<section class="FW_middle_tests">

<div class=""> 

 <header>
 <h2> Принять задание</h2>
 </header>
 
<!-- Раздел вывода информации о работе -->
<div class="">
<?php
echo $titlejob;
echo "--";
echo $descriptionjob;
?>
</div>
<!-- Раздел ответа на задание ( ответ \ цена \ сроки) -->
<div class="">
<form  name="ApplyJob" id="Applyjob" class="" method="post">
<div class="">
заметка к заданию
<textarea id="Answerjob" name ="Answerjob"></textarea>
</div>

<div class="">
введите свою цену
<input type="text" name="Pricejob" id="Pricejob">
</div>

<div class="">
ваши сроки на выполнения
<select name="Durationjob" class="" id="Durationjob">
<option  value="" label="Выберите">Выберите</option>
<option  value="1" label="Больше 6 месяцев">Больше 6 месяцев</option>
<option  value="2" label="3-6 месяцев">3-6 месяцев</option>
<option  value="3" label="1-3 месяца">1-3 месяца</option>
<option  value="4" label="Меньше месяца">Меньше месяца</option>
<option  value="5" label="Меньше недели">Меньше недели</option>
<option  value="6" label="1 день">1 день</option>
</select>
</div>


<!-- Раздел выполнения тестового задания внутри формы, если оно есть -->
<?php 
	if($testjob != 0) {
?>

<!--Описание задания тестового -->

<div class=""> 
<div class="">
<?php
echo $descriptiontestjob;
?>
</div>
 
<div class="">
<?php
echo $texttestjob;
?> 
</div>
 
</div>


<!--Выполнение задания тестового -->

<div class="">
<textarea name="Readytest" id="Readytest" class="" maxlength="1800"></textarea>
</div>

<?php
 }
?>

<div class="">
<input type="submit" class="" id="submit" value="Создать задание"/>
</div>

</div>
</form>
<!-- -->
</div>

</section> 
</div>

</section>

<script type="text/javascript" src="jquery-1.5.1.min.js" charset="utf-8"></script>



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
<?php
/* Закрываем соединение */ 
$BD->close();	
}