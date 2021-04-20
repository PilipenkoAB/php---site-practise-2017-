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

/* Принятие GET запроса sl с вариантом сортировки заявок */
$Selection =htmlspecialchars(trim( isset($_GET['sl']) ? (int) $_GET['sl'] : 0));

/* Поиск Работы, которая определяется id из GET запроса */
if($Job_Information = $BD->query("SELECT * FROM `Jobs` WHERE `id`='$id' AND `EmployerId`='$User_Id'")){
$Job_Information_Data = $Job_Information->fetch_assoc();

If (empty($Job_Information_Data)) { 
		header("location: errorchoicejobemployer.php");   // если такой работы нет - ошибка
}




   // переменные с информацией о работе   (Id в добавлении к переменным - это для дополнительной выборки из бд для названий
 $Jobid=$Job_Information_Data['id'];  
 $Title=$Job_Information_Data['Title'];  
 $DurationId=$Job_Information_Data['Duration'];  
 $JobFreelancerId=$Job_Information_Data['FreelancerId'];  
   
$StartMinPrice=$Job_Information_Data['StartMinPrice'];
$StartMaxPrice=$Job_Information_Data['StartMaxPrice'];   
   
$LanguageFrom1=$Job_Information_Data['LanguageFrom1'];
$LanguageTo1=$Job_Information_Data['LanguageTo1'];
$LanguageFrom2=$Job_Information_Data['LanguageFrom2'];
$LanguageTo2=$Job_Information_Data['LanguageTo2'];
$LanguageFrom3=$Job_Information_Data['LanguageFrom3'];
$LanguageTo3=$Job_Information_Data['LanguageTo3'];
$LanguageFrom4=$Job_Information_Data['LanguageFrom4'];
$LanguageTo4=$Job_Information_Data['LanguageTo4'];
$LanguageFrom5=$Job_Information_Data['LanguageFrom5'];
$LanguageTo5=$Job_Information_Data['LanguageTo5'];


$StyleId=$Job_Information_Data['Style'];
$Description=$Job_Information_Data['Description'];
$TypeId=$Job_Information_Data['Type'];
$ValueId=$Job_Information_Data['Value'];
$SearchId=$Job_Information_Data['Search'];




$Private=$Job_Information_Data['Private'];
$DataCreation=$Job_Information_Data['DataCreation'];
$Prepayment=$Job_Information_Data['Prepayment'];


$ContractId=$Job_Information_Data['ContractId'];
$ContractId_2=$Job_Information_Data['ContractId_2'];
$ContractId_3=$Job_Information_Data['ContractId_3'];
$ContractId_4=$Job_Information_Data['ContractId_4'];
$ContractId_5=$Job_Information_Data['ContractId_5'];

// вывод информации о работе по виду поиска фрилансеров

$TestJobText=$Job_Information_Data['TestJobText'];
$TestJobDescription=$Job_Information_Data['TestJobDescription'];

/* Освобождаем память $Job_Information */ 
$Job_Information->close(); 
}



/* вывод информации о работе по продолжительности выполнения */
if($Duration_Information = $BD->query("SELECT * FROM `DurationTranslation` WHERE `id`='$DurationId'")){
$Duration_Information_Data = $Duration_Information->fetch_assoc();
$Duration=$Duration_Information_Data['Russian'];
$Duration_Information->close(); 
}

	
/* вывод информации о работе по стилю */
if($Style_Information = $BD->query("SELECT * FROM `StyleTranslation` WHERE `id`='$StyleId'")){
$Style_Information_Data = $Style_Information->fetch_assoc();
$Style=$Style_Information_Data['Russian'];
$Style_Information->close(); 
}

/* вывод информации о работе по типу */

if($Type_Information = $BD->query("SELECT * FROM `TypeTranslation` WHERE `id`='$TypeId'")){
$Type_Information_Data = $Type_Information->fetch_assoc();
$Type=$Type_Information_Data['Russian'];
$Type_Information->close(); 
}


/* вывод информации о работе по виду поиска фрилансеров */
if($Search_Information = $BD->query("SELECT * FROM `SearchTranslation` WHERE `id`='$SearchId'")){
$Search_Information_Data = $Search_Information->fetch_assoc();
$Search=$Search_Information_Data['Russian'];
$Search_Information->close(); 
}






// раздел вывода информации об отзывах фрилансеров

//раздел вывода по N заявок на странице

// количество записей, выводимых на странице 
$per_page=5;
// получаем номер страницы
if (isset($_GET['page'])) $page=($_GET['page']-1); else $page=0;
// вычисляем первый оператор для LIMIT
$start=abs($page*$per_page);


// варианты сортировки
if ($Selection != 2 && $Selection != 3) {
$ResponceJob=$BD->query("SELECT * FROM `ResponseNewJob` WHERE `JobId`='$id' AND `Result`='0' ORDER BY `DataResponse` LIMIT $start,$per_page ");	
} elseif ($Selection == 2) {
$ResponceJob=$BD->query("SELECT `ResponseNewJob`.`FreelancerId`,`ResponseNewJob`.`JobId`,`ResponseNewJob`.`DataResponse`,`ResponseNewJob`.`Response`,`ResponseNewJob`.`Price`,`ResponseNewJob`.`Duration`,`ResponseNewJob`.`TestJobAnswer`,`ResponseNewJob`.`Result`,`ResponseNewJob`.`Archive`,`ResponseNewJob`.`Deleted`, `users`.`id`,`users`.`Rating` FROM `ResponseNewJob`, `users` WHERE `ResponseNewJob`.`FreelancerId`=`users`.`id` AND `ResponseNewJob`.`JobId`='$id' AND `ResponseNewJob`.`Result`='0'  ORDER BY `users`.`Rating` DESC  LIMIT $start,$per_page"); // выбраны только нужные строки из таблиц сортировка по рейтингу	
} elseif ($Selection == 3) {
$ResponceJob=$BD->query("SELECT * FROM `ResponseNewJob` WHERE `JobId`='$id' AND `Result`='0' ORDER BY `Price` DESC  LIMIT $start,$per_page"); 
}
// ---- ----- окончание раздела сортировки ---- ---- ----

if($ResponceJobShow_Information=$BD->query("SELECT * FROM `ResponseNewJob` WHERE `JobId`='$id' ")){ // это для вывода надписи (нет заявок)
$ResponceJobShow_Information_Data = $ResponceJobShow_Information->fetch_assoc();
$FreelancerId=$ResponceJobShow_Information_Data['FreelancerId'];
$ResponceJobShow_Information->close(); 
}


// Раздел для вывода информации об уже принятой заявки

$ResponceJobAccept=$BD->query("SELECT * FROM `ResponseNewJob` WHERE `JobId`='$id' AND `Result`='2' "); // это для вывода выборанной заявки
$ResponceJobAccept_data=$ResponceJobAccept->fetch_assoc();












/*  // изменение из contracts в jobs структуры (перенос заявки на данном этапе)

// изменение статуса заявки (Если заявка принимается, то начинается формирование контракта)
if(isset($_POST['HiddenAgree'])) {

$HiddenAgree = htmlspecialchars(trim($_POST['HiddenAgree']));

$HiddenAgreeBd=$BD->query("SELECT * FROM `ResponseNewJob` WHERE `id`='$HiddenAgree' "); // Поиск фрилансера, курирующего данную работу
$HiddenAgreeBd_data=$HiddenAgreeBd->fetch_assoc();
$HiddenCheckFreelancer=$HiddenAgreeBd_data['FreelancerId'];  

// Проверить, если таких работ не больше 5 и если нет такого же фрилансера на выполнении такой же работы, то можно принять заявку
$CountContracts = 0; // Счетчик для подсчета контрактов(формирующихся\активных), которые есть у данной работы
$CheckContracts = 0; // Счетчик для контроля ошибки на существование текущего фрилансера среди контрактов ( 1 - существует, 0 - нет)

$CountContractsBd=$BD->query("SELECT * FROM `Contracts` WHERE `JobId`='$id' "); // Поиск в БД в контрактах созданных контрактов по данной работе
While($CountContractsBd_data=$CountContractsBd->fetch_assoc()){ // В цикле при каждом найденном результате
$CountContracts = $CountContracts + 1;								// Прибавлять на счётчик +1
$CheckFreelancer=$CountContractsBd_data['FreelancerId'];   // Проверяем переменную Id Фрилансера
if(	$CheckFreelancer == $HiddenCheckFreelancer ) {								// Если переменная найденного фрилансера совпадает с фрилансером, которому принимается заявка, то ошибка = 1
$CheckContracts = 1;
}
	}
	
if ($CountContracts < 1 && $CheckContracts == 0) {   // Если существует меньше 1 контрактов и нет среди них текущего фрилансера, то 

// ОБНОВЛЯЕМ ДАННЫЕ В RESPONSE NEW JOB 	
	if(!$BD->query("UPDATE `ResponseNewJob` SET `Result`='2' WHERE `id`='$HiddenAgree' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
		}else{  // если операция прошла успешно
// ОБНОВЛЯЕМ ДАННЫЕ ДЛЯ CONTRACTS
	if(!$BD->query("INSERT INTO `Contracts` (`JobId`,`FreelancerId`) VALUES ('$id','$HiddenCheckFreelancer') ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
		}else{  // если операция прошла успешно
		
// Узнаем id только что созданного контракта
$NewContractsIdBd=$BD->query("SELECT * FROM `Contracts` WHERE `JobId`='$id' AND `FreelancerId`='$HiddenCheckFreelancer' ");
$NewContractsIdBd_data=$NewContractsIdBd->fetch_assoc();
$NewContractId=$NewContractsIdBd_data['id'];

// ОБНОВЛЯЕМ ДАННЫЕ ДЛЯ JOBS

// Проверка в какой именно ContractId внести номер контракт в JOBS

if($ContractId == 0){
	if(!$BD->query("UPDATE `Jobs` SET `ContractId`='$NewContractId' WHERE `id`='$id' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
		}else{  // если операция прошла успешно
		header('Refresh: 0;URL=');
}	}			
		}		
				}
}elseif($CountContracts > 1){  // ошибка, что больше 5 контрактов пытаемся сделать
	echo ("Всё плохо - максимум контрактов"); 		
	}elseif($CheckContracts == 1){ // ошибка, что такой фрилансер уже подписал контракт на данную работу
	echo ("Всё плохо - контракт с таким фрилансером уже есть"); 
		}
		
}


*/


// изменение статуса заявки (Если заявка принимается, то начинается формирование контракта)
if(isset($_POST['HiddenAgree'])) {

$HiddenAgree = htmlspecialchars(trim($_POST['HiddenAgree']));

$HiddenAgreeBd=$BD->query("SELECT * FROM `ResponseNewJob` WHERE `id`='$HiddenAgree' "); // Поиск фрилансера, курирующего данную работу
$HiddenAgreeBd_data=$HiddenAgreeBd->fetch_assoc();
$HiddenCheckFreelancer=$HiddenAgreeBd_data['FreelancerId'];  


if(	$JobFreelancerId == 0 ) {								// Если переменная фрилансера = 0, т.е. нет принятой заявки фрилансера, то записываем туда фрилансера
// ОБНОВЛЯЕМ ДАННЫЕ В RESPONSE NEW JOB 	
	if(!$BD->query("UPDATE `ResponseNewJob` SET `Result`='2' WHERE `id`='$HiddenAgree' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
		}else{  // если операция прошла успешно
		
	if(!$BD->query("UPDATE `Jobs` SET `FreelancerId`='$HiddenCheckFreelancer' WHERE `id`='$id' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
		}else{  // если операция прошла успешно
		header('Refresh: 0;URL=');
			}
		}
		
	}
	
}







// УДАЛЕНИЕ ЗАЯВКИ И КОНТРАКТА И ИЗ РАБОТЫ ТОЖЕ

if(isset($_POST['HiddenDisagree'])) {
$HiddenDisagree = htmlspecialchars(trim($_POST['HiddenDisagree']));

$DisagreeResponseBd=$BD->query("SELECT * FROM `ResponseNewJob` WHERE `id`='$HiddenDisagree' ");
$DisagreeResponseBd_data=$DisagreeResponseBd->fetch_assoc();
$DisagreeJobId=$DisagreeResponseBd_data['JobId'];
$DisagreeFreelancerId=$DisagreeResponseBd_data['FreelancerId'];

$DisagreeJobBd=$BD->query("SELECT * FROM `Jobs` WHERE `id`='$DisagreeJobId'");
$DisagreeJobBd_data=$DisagreeJobBd->fetch_assoc();
$DisagreeJobFreelancerId=$DisagreeJobBd_data['FreelancerId'];


if($DisagreeFreelancerId == $DisagreeJobFreelancerId) {

if(!$BD->query("UPDATE `Jobs` SET `FreelancerId`='0' WHERE `id`='$DisagreeJobId' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
//Удаление из Response
if(!$BD->query("UPDATE `ResponseNewJob` SET `Result`='0' WHERE `id`='$HiddenDisagree' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
header('Refresh: 0;URL=');		// если операция прошла успешно
			}	
		}
		
	}
}

/* // старая версия для contracts
// УДАЛЕНИЕ ЗАЯВКИ И КОНТРАКТА И ИЗ РАБОТЫ ТОЖЕ

if(isset($_POST['HiddenDisagree'])) {
$HiddenDisagree = htmlspecialchars(trim($_POST['HiddenDisagree']));

//Удаление из Jobs
$DisagreeResponseBd=$BD->query("SELECT * FROM `ResponseNewJob` WHERE `id`='$HiddenDisagree' ");
$DisagreeResponseBd_data=$DisagreeResponseBd->fetch_assoc();
$DisagreeJobId=$DisagreeResponseBd_data['JobId'];
$DisagreeFreelancerId=$DisagreeResponseBd_data['FreelancerId'];

$DisagreeContractBd=$BD->query("SELECT * FROM `Contracts` WHERE `JobId`='$DisagreeJobId' AND `FreelancerId`='$DisagreeFreelancerId'");
$DisagreeContractBd_data=$DisagreeContractBd->fetch_assoc();
$DisagreeContractId=$DisagreeContractBd_data['id'];

$DisagreeJobBd=$BD->query("SELECT * FROM `Jobs` WHERE `id`='$DisagreeJobId'");
$DisagreeJobBd_data=$DisagreeJobBd->fetch_assoc();
$DisagreeJobContractId=$DisagreeJobBd_data['ContractId'];
$DisagreeJobContractId_2=$DisagreeJobBd_data['ContractId_2'];
$DisagreeJobContractId_3=$DisagreeJobBd_data['ContractId_3'];
$DisagreeJobContractId_4=$DisagreeJobBd_data['ContractId_4'];
$DisagreeJobContractId_5=$DisagreeJobBd_data['ContractId_5'];

if($DisagreeJobContractId == $DisagreeContractId) {
	//Удаление из Jobs
if(!$BD->query("UPDATE `Jobs` SET `ContractId`='0' WHERE `id`='$DisagreeJobId' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
	//Удаление из Contracts
if(!$BD->query("DELETE FROM `Contracts`  WHERE `id`='$DisagreeContractId' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
//Удаление из Response
if(!$BD->query("UPDATE `ResponseNewJob` SET `Result`='0' WHERE `id`='$HiddenDisagree' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
header('Refresh: 0;URL=');		// если операция прошла успешно
}	
	}
		}
}elseif($DisagreeJobContractId_2 == $DisagreeContractId){
	//Удаление из Jobs
if(!$BD->query("UPDATE `Jobs` SET `ContractId_2`='0' WHERE `id`='$DisagreeJobId' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
	//Удаление из Contracts
if(!$BD->query("DELETE FROM `Contracts`  WHERE `id`='$DisagreeContractId' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
//Удаление из Response
if(!$BD->query("UPDATE `ResponseNewJob` SET `Result`='0' WHERE `id`='$HiddenDisagree' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
header('Refresh: 0;URL=');		// если операция прошла успешно
}	
	}
		}
}elseif($DisagreeJobContractId_3 == $DisagreeContractId){
	//Удаление из Jobs
if(!$BD->query("UPDATE `Jobs` SET `ContractId_3`='0' WHERE `id`='$DisagreeJobId' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
	//Удаление из Contracts
if(!$BD->query("DELETE FROM `Contracts`  WHERE `id`='$DisagreeContractId' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
//Удаление из Response
if(!$BD->query("UPDATE `ResponseNewJob` SET `Result`='0' WHERE `id`='$HiddenDisagree' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
header('Refresh: 0;URL=');		// если операция прошла успешно
}	
	}
		}
}elseif($DisagreeJobContractId_4 == $DisagreeContractId){
	//Удаление из Jobs
if(!$BD->query("UPDATE `Jobs` SET `ContractId_4`='0' WHERE `id`='$DisagreeJobId' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
	//Удаление из Contracts
if(!$BD->query("DELETE FROM `Contracts`  WHERE `id`='$DisagreeContractId' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
//Удаление из Response
if(!$BD->query("UPDATE `ResponseNewJob` SET `Result`='0' WHERE `id`='$HiddenDisagree' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
header('Refresh: 0;URL=');			// если операция прошла успешно
}	
	}
		}
}elseif($DisagreeJobContractId_5 == $DisagreeContractId){
	//Удаление из Jobs
if(!$BD->query("UPDATE `Jobs` SET `ContractId_5`='0' WHERE `id`='$DisagreeJobId' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
	//Удаление из Contracts
if(!$BD->query("DELETE FROM `Contracts`  WHERE `id`='$DisagreeContractId' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
//Удаление из Response
if(!$BD->query("UPDATE `ResponseNewJob` SET `Result`='0' WHERE `id`='$HiddenDisagree' ")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
header('Refresh: 0;URL=');		// если операция прошла успешно
}	
	}
		}
			}
}
*/



?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<script type="text/javascript" src="jquery-1.5.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="jquery.min.js" charset="utf-8"></script>

<html>
<head>
<link href="/mysite/styles_index.css" rel="stylesheet" type="text/css" />
</head>




<body class="Main_Body">
<div id="Main_Section">

<header  class="FW_GlobalHeader">

<div class style="  width: 960px;
  position: relative;
  margin: 0 auto;">
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
<a href="my-jobs.php" class="FW_top_nav_2_href_current">Мои проекты</a>
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


<h2 class="FW_middle_right_up_profile_title"> Меню  </h2>
<ul class="FW_middle_left_ul">
<li class="FW_middle_left_li">
<a class="FW_middle_left_a" href="job.php?id=<?php echo $Jobid ?>">Описание работы</a>
</li>
<li class="FW_middle_left_li">
<a class="FW_middle_left_a" href="team.php?id=<?php echo $Jobid ?>">Команда</a>
</li>
<li class="FW_middle_left_li">
<a class="FW_middle_left_a" href="noname.php">______</a>
</li>
</ul>
</nav>

 
 
 
 
 
<!-- Форма предпросмотра работы -->
<div class="FM_middle_center">

<div class="Job_Preview_Title">
<h1>
<div class="Job_Preview_Title_First">
<?php echo $Title; ?>
</div>
</h1>

<h2>
 <div class="Job_Preview_Title_Second">
 Опубликовано меньше минуты назад  ||

 Бюджет : 
 <?php
echo "$StartMinPrice - $StartMaxPrice USD";
 ?> 
 </div>
</h2>
</div>

<div class="Job_Preview_Detail">
<div class="Job_Preview_Detail_Main">
<div class="Job_Preview_Detail_First">

<?php
if($Language_From_Information = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageFrom1'")){
$Language_From_Information_Data = $Language_From_Information->fetch_assoc();
$LanguageFromImg=$Language_From_Information_Data['Flag'];
$LanguageFromLang=$Language_From_Information_Data['Russian'];
$Language_From_Information->close(); 
}
if($Language_To_Information = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageTo1'")){
$Language_To_Information_Data = $Language_To_Information->fetch_assoc();
$LanguageToImg=$Language_To_Information_Data['Flag'];
$LanguageToLang=$Language_To_Information_Data['Russian'];
$Language_To_Information->close(); 
}
?>

<ul style="padding-left: 200px;">
<li class="Job_Preview_Detail_Li_1">
<span class="Job_Preview_Detail_Span">
 <img src="<?php echo $LanguageFromImg; ?>" alt>  <?php echo $LanguageFromLang; ?>  -> <img src="<?php echo $LanguageToImg; ?>" alt>  <?php echo $LanguageToLang; ?> 
</span>
</li>

<?php
if ( $LanguageFrom2 != 0 && $LanguageTo2 != 0 ){

if($Language_From_Information_2 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageFrom2'")){
$Language_From_Information_Data_2 = $Language_From_Information_2->fetch_assoc();
$LanguageFromImg_2=$Language_From_Information_Data_2['Flag'];
$LanguageFromLang_2=$Language_From_Information_Data_2['Russian'];
$Language_From_Information_2->close(); 
}
if($Language_To_Information_2 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageTo2'")){
$Language_To_Information_Data_2 = $Language_To_Information_2->fetch_assoc();
$LanguageToImg_2=$Language_To_Information_Data_2['Flag'];
$LanguageToLang_2=$Language_To_Information_Data_2['Russian'];
$Language_To_Information_2->close(); 
}
?>
<li class="Job_Preview_Detail_Li_1">
<span class="Job_Preview_Detail_Span">
 <img src="<?php echo $LanguageFromImg_2; ?>" alt>  <?php echo $LanguageFromLang_2; ?>  -> <img src="<?php echo $LanguageToImg_2; ?>" alt>  <?php echo $LanguageToLang_2; ?> 
</span>
</li>
<?php
}
?>

<?php
if ( $LanguageFrom3 != 0 && $LanguageTo3 != 0 ){

if($Language_From_Information_3 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageFrom3'")){
$Language_From_Information_Data_3 = $Language_From_Information_3->fetch_assoc();
$LanguageFromImg_3=$Language_From_Information_Data_3['Flag'];
$LanguageFromLang_3=$Language_From_Information_Data_3['Russian'];
$Language_From_Information_3->close(); 
}
if($Language_To_Information_3 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageTo3'")){
$Language_To_Information_Data_3 = $Language_To_Information_3->fetch_assoc();
$LanguageToImg_3=$Language_To_Information_Data_3['Flag'];
$LanguageToLang_3=$Language_To_Information_Data_3['Russian'];
$Language_To_Information_3->close(); 
}
?>
<li class="Job_Preview_Detail_Li_1">
<span class="Job_Preview_Detail_Span">
 <img src="<?php echo $LanguageFromImg_3; ?>" alt>  <?php echo $LanguageFromLang_3; ?>  -> <img src="<?php echo $LanguageToImg_3; ?>" alt>  <?php echo $LanguageToLang_3; ?> 
</span>
</li>
<?php
}
?>

<?php
if ( $LanguageFrom4 != 0 && $LanguageTo4 != 0 ){

if($Language_From_Information_4 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageFrom4'")){
$Language_From_Information_Data_4 = $Language_From_Information_4->fetch_assoc();
$LanguageFromImg_4=$Language_From_Information_Data_4['Flag'];
$LanguageFromLang_4=$Language_From_Information_Data_4['Russian'];
$Language_From_Information_4->close(); 
}
if($Language_To_Information_4 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageTo4'")){
$Language_To_Information_Data_4 = $Language_To_Information_4->fetch_assoc();
$LanguageToImg_4=$Language_To_Information_Data_4['Flag'];
$LanguageToLang_4=$Language_To_Information_Data_4['Russian'];
$Language_To_Information_4->close(); 
}
?>
<li class="Job_Preview_Detail_Li_1">
<span class="Job_Preview_Detail_Span">
 <img src="<?php echo $LanguageFromImg_4; ?>" alt>  <?php echo $LanguageFromLang_4; ?>  -> <img src="<?php echo $LanguageToImg_4; ?>" alt>  <?php echo $LanguageToLang_4; ?> 
</span>
</li>
<?php
}
?>

<?php
if ( $LanguageFrom5 != 0 && $LanguageTo5 != 0 ){

if($Language_From_Information_5 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageFrom5'")){
$Language_From_Information_Data_5 = $Language_From_Information_5->fetch_assoc();
$LanguageFromImg_5=$Language_From_Information_Data_5['Flag'];
$LanguageFromLang_5=$Language_From_Information_Data_5['Russian'];
$Language_From_Information_5->close(); 
}
if($Language_To_Information_5 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageTo5'")){
$Language_To_Information_Data_5 = $Language_To_Information_5->fetch_assoc();
$LanguageToImg_5=$Language_To_Information_Data_5['Flag'];
$LanguageToLang_5=$Language_To_Information_Data_5['Russian'];
$Language_To_Information_5->close(); 
}
?>
<li class="Job_Preview_Detail_Li_1">
<span class="Job_Preview_Detail_Span">
 <img src="<?php echo $LanguageFromImg_5; ?>" alt>  <?php echo $LanguageFromLang_5; ?>  -> <img src="<?php echo $LanguageToImg_5; ?>" alt>  <?php echo $LanguageToLang_5; ?> 
</span>
</li>
<?php
}
?>

</ul>

<ul class="Job_Preview_Detail_Ul">
<li class="Job_Preview_Detail_Li_2">
<span class="Job_Preview_Detail_Span">
Стиль :   <?php echo $Style;  ?>
 </span>
</li>

<li class="Job_Preview_Detail_Li_2">
<span class="Job_Preview_Detail_Span">
Сроки выполнения : <?php echo $Duration;  ?>
</span>
</li>


<li class="Job_Preview_Detail_Li_2">
<span class="Job_Preview_Detail_Span">
Приватность :   

 <?php If ($Private == 0) { ?>
Нет
  
<?php } elseif ($Private == 1 ) { ?>
Да

<?php }  ?> 
</span>
</li>
</ul>

<ul class="Job_Preview_Detail_Ul">
<li class="Job_Preview_Detail_Li_2">
<span class="Job_Preview_Detail_Span">
Тип :  <?php echo $Type;  ?>
</span>
</li>


<li class="Job_Preview_Detail_Li_2">
<span class="Job_Preview_Detail_Span">
Предоплата :  

 <?php If ($Prepayment == 1) { ?>
Нет

<?php } elseif ($Prepayment == 2 ) { ?>
25%

<?php } elseif ($Prepayment == 3 ) { ?>
50%

<?php } elseif ($Prepayment == 4 ) { ?>
75%

<?php } elseif ($Prepayment == 5 ) { ?>
100%

<?php }  ?> 

</span>
</li>


<li class="Job_Preview_Detail_Li_2">
<span class="Job_Preview_Detail_Span">
Тестовая работа :  

 <?php If ($TestHidden == 0) { ?>
Нет

<?php } elseif ($TestHidden == 1 ) { ?>
Да

<?php }  ?> 
</span>
</li>
</ul>
</div>

</div>



<div class="Job_Preview_Detail_Desc_Main">

<h2 class="Job_Preview_Detail_Desc_Tittle"> Описание </h2>
<p class="Job_Preview_Detail_Description">
 <?php echo "$Description"; ?> 
</p>


 <?php If ($FileNamen != Null) { ?>
<div>
<span>
Прикрепленный файл : <?php echo "$FileName"; ?> 
</span>
</div>
<?php }  ?> 


 <?php If ($TestHidden == 1) { ?>
<div>
<hr>
<label class="Post_Job_Label">Тестовая работа  </label>
<span> Название тестовой работы : <?php echo "$TestJobDescription" ?></span>
<br>
<span> Текст работы : <?php echo "$TestJobText" ?></span>

</div>
<?php }  ?> 

</div>

<div class="Job_Preview_Detail_Desc_Main">
<< Нижняя панель с некоторыми элементами (Разработка!) >>
</div>

</div>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 <!-- Раздел заявок -->


<!-- Раздел выбранной заявки -->

<?php
$ResponceAcceptCount = 0; // Счетчик кол-ва принятых заявок для вывода кнопки принять заявку или предупреждении, что взято максимальное кол-во заявок
if(!empty($ResponceJobAccept_data)){
?>
<hr>
<b>Принятая заявка </b>


<?php
$ResponceJobAccept=$BD->query("SELECT * FROM `ResponseNewJob` WHERE `JobId`='$id' AND `Result`='2' "); // это для вывода выборанной заявки

While($ResponceJobAccept_data=$ResponceJobAccept->fetch_assoc()){

$ResponceAcceptCount = $ResponceAcceptCount + 1;

$FreelancerAcceptId=$ResponceJobAccept_data['FreelancerId'];
$IdJobAccept=$ResponceJobAccept_data['id'];

$FreelancerAcceptBd=$BD->query("SELECT * FROM `users` WHERE `id`='$FreelancerAcceptId' ");
$FreelancerAcceptBd_data=$FreelancerAcceptBd->fetch_assoc();
$FreelancerAcceptFirstName=$FreelancerAcceptBd_data['FirstName'];
$FreelancerAcceptLastName=$FreelancerAcceptBd_data['LastName'];
$FreelancerAcceptAvatar=$FreelancerAcceptBd_data['Avatar'];
$FreelancerAcceptRating=$FreelancerAcceptBd_data['Rating'];

// проверка на то, подписан ли контракт или нет
$Job_Information_To_Contract=$BD->query("SELECT * FROM `Jobs` WHERE `id`='$id' ");
$Job_Information_To_Contract_Data=$Job_Information_To_Contract->fetch_assoc();
$Job_Contract_Id=$Job_Information_To_Contract_Data['ContractId'];

$ContractCheck=$BD->query("SELECT * FROM `Contracts` WHERE `id`='$Job_Contract_Id' AND `Active`='1' ");
$ContractCheck_data=$ContractCheck->fetch_assoc();

?>

<div class="EW-response-whilediv">
<header>
<h2 class="EW-response-whileh2">
<p> <?php echo "$FreelancerAcceptFirstName $FreelancerAcceptLastName ( $FreelancerAcceptRating )"; ?> </p>
<img src="<?php echo $FreelancerAcceptAvatar; ?>" alt="Avatar">
</h2>
</header>

<!-- возможность отмены заявки если не подписан ещё контракт -->
<?php
if(empty($ContractCheck_data)){
?>
<form name="DisagreeJob" id="DisagreeJob" class="" method="post">
<input type="hidden" name='HiddenDisagree' id='HiddenDisagree' value='<?php echo $IdJobAccept ?>' >
<input type="submit" class="" id="submitDisagree" value="Отменить заявку"/>
</form>
<?php
}else{
?>
Контракт подписан
<?php
}
?>
</div>
<?php
}	}
?>




<!-- Раздел сортировки заявок -->

<div  class="" style="margin-top: 10px;">
Сортировка: <a href="?id=<?php echo $id;?>&sl=1">Под дате</a> | <a href="?id=<?php echo $id;?>&sl=2">По рейтингу фрилансеров</a> | <a href="?id=<?php echo $id;?>&sl=3">По Цене Фрилансеров</a>
</div>

<!-- РАЗДЕЛ СТАНДАРТНОЙ ОСНОВЫ -->

<?php
if($SearchId == 1) {
?>
<div class="" style="padding-left: 25px;    width: 740px;">
<?php
if(empty($ResponceJobShow_Information_Data)) {
?>
<hr>
<br>
<br>
<p> Нет заявок от фрилансеров </p>
<?php
	} else {
?>

<?php
$ii=0;
while( $ResponceJob_data=$ResponceJob->fetch_assoc()) {
$ii=$ii+1;
// переменную $start используем, как нумератор записей. - вывод n заявок на странице
//echo ++$start.". ".$ResponceJob_data['DataResponse']."<br>\n"; // не важный элемент
//
$Responce_idR = $ResponceJob_data['id'];
$FreelancerIdR= $ResponceJob_data['FreelancerId'];
$ResponseR = $ResponceJob_data['Response'];
$PriceR = $ResponceJob_data['Price'];
$DurationR = $ResponceJob_data['Duration'];
$TestJobAnswerR = $ResponceJob_data['TestJobAnswer'];
$ResultR = $ResponceJob_data['Result'];
$DataResponseR = $ResponceJob_data['DataResponse'];

$DurationWhile=$BD->query("SELECT * FROM `DurationTranslation` WHERE `id`='$DurationR' ");
$DurationWhile_Data=$DurationWhile->fetch_assoc();
$Duration=$DurationWhile_Data['Russian'];

$FreelancerBd=$BD->query("SELECT * FROM `users` WHERE `id`='$FreelancerIdR' ");
$FreelancerBd_data=$FreelancerBd->fetch_assoc();
$FreelancerId=$FreelancerBd_data['id'];
$FreelancerFirstName=$FreelancerBd_data['FirstName'];
$FreelancerLastName=$FreelancerBd_data['LastName'];
$FreelancerAvatar=$FreelancerBd_data['Avatar'];
$FreelancerRating=$FreelancerBd_data['Rating'];

?>

<hr>

<li class="EW-response-li">
<article class="EW-response-whilediv">


<!-- ЛЕВАЯ ЧАСТЬ (аватар+репутация) -->
<div class="EW-response-left-part">

<!-- АВАТАР -->
<div class="EW-response-avatar">
<img src="<?php echo $FreelancerAvatar; ?>" alt="Avatar">
</div>

<!-- РЕПУТАЦИЯ -->
<div class="EW-response-freelancerinfo">
reputation : <?php echo $FreelancerRating; ?> .
</div>

</div>


<!-- ПРАВАЯ ЧАСТЬ -->

<!-- ИМЯ ФАМИЛИЯ -->
<div class="EW-response-information">
<header>
<h2 class="EW-response-whileh2">
<a href="/mysite/freelancers.php?id=<? echo $FreelancerId; ?>"> <?php echo "$FreelancerFirstName $FreelancerLastName"; ?> </a>
</h2>

<!-- ПРИНЯТЬ ЗАЯВКУ ИЛИ УЖЕ ПРИНЯТА -->
<div class="">
<?php 
if($ResponceAcceptCount < 1) {  // Если нет принятых 5 заявок, то можно принимать заявку ( 0 1 2 3 4 = 5)
?>	
<form name="AgreeJob" id="AgreeJobJob" class="" method="post">
<input type="hidden" name='HiddenAgree' id='HiddenAgree' value='<?php echo $Responce_idR ?>' >
<input type="submit" class="" id="submitAgree" value="Принять заявку"/>
</form>
<?php
} else {
?>
 <p> Принято максимальное кол-во заявок  </p> 
<?php 
}
?>
</div>
</header>

<!-- ИНФОРМАЦИЯ ПОДАННОЙ ЗАЯВКИ - ПЕРЕМЕННЫЕ -->
<div >
<div class="EW-response-whilefirst">
<p><?php echo "Дата ответа: $DataResponseR ; Цена: $PriceR ; Сроки: $Duration ."; ?></p>
</div>

<!-- ИНФОРМАЦИЯ ПОДАННОЙ ЗАЯВКИ - ОТВЕТ ФРИЛАНСЕРА -->
<div class="EW-response-whilesecond">
<p><?php echo "Ответ: $ResponseR"; ?></p>
</div>

<!-- КНОПКА ПОКАЗАТЬ ТЕСТОВОЕ ЗАДАНИЕ, ЕСЛИ ТАКОВОЕ БЫЛО -->
<?php
if($TestJobText != 0 && $TestJobDescription != 0 ) {
?>
<div class="EW-response-testjob">
<input type="button" id="showtest_<?php echo $ii; ?>" value="показать тестовое задание">
<input type="button" id="hidetest_<?php echo $ii; ?>" value="скрыть тестовое задание" class style="display: none">
</div>

<!-- две закрывающие ранее еденицы -->
</div>
</div>

<!-- БЛОК РАЗВЕРНУТОГО ТЕКСТОВОГО ЗАДАНИЯ -->
<div id="testdiv_<?php echo $ii; ?>" class="EW-response-testjob" class style="display: none">
Название тестового задания:
	<p><?php echo $TestJobText; ?></p>
Текст тестового задания:
	<p><?php echo $TestJobDescription; ?></p>
<br>
Ответ на тестовое задание:
	<p><?php echo $TestJobAnswerR; ?></p>
</div>

<br>

<!-- Скрипт на открытие и закрытие тестового задания -->
<script>
$('#showtest_<?php echo $ii; ?>').bind('click', function(){
  $('#testdiv_<?php echo $ii; ?>').show(); <!--скрывает div с формой создания теста -->
  $('#showtest_<?php echo $ii; ?>').hide(); <!--скрывает div с формой создания теста -->
  $('#hidetest_<?php echo $ii; ?>').show(); <!--скрывает div с формой создания теста -->
});

$('#hidetest_<?php echo $ii; ?>').bind('click', function(){
  $('#testdiv_<?php echo $ii; ?>').hide(); <!--скрывает div с формой создания теста -->
  $('#showtest_<?php echo $ii; ?>').show(); <!--скрывает div с формой создания теста -->
  $('#hidetest_<?php echo $ii; ?>').hide(); <!--скрывает div с формой создания теста -->
});
</script>

<?php 
}else{
?>
<div class="EW-response-testjob">
Тестового задания нет
</div>

<!-- две закрывающие ранее еденицы -->
</div> 
<?php
}
?>

</article>
</li>

<?php 
	} 
?>
<?php 
	} 
?>
</div>
<?php
}
?>


<!-- Вывод ссылок на страницы для N заявок на странице -->
<?php
// дальше выводим ссылки на страницы:
$q=$BD->query("SELECT count(*) FROM `ResponseNewJob` WHERE `JobId`='$id' ");
$row=$q->fetch_row();
$total_rows=$row[0];

$num_pages=ceil($total_rows/$per_page);

for($i=1;$i<=$num_pages;$i++) {
  if ($i-1 == $page) {
    echo $i." ";
  } else {
    echo '<a href="'.$_SERVER['PHP_SELF'].'?id='.$id.'&sl='.$Selection.'&page='.$i.'">'.$i."</a> ";
  }
}

?>


<!-- РАЗДЕЛ ВЫБОРОЧНОЙ ОСНОВЫ -->

<?php
if($SearchId == 2) {
?>

<div class="">

раздел выборочной основы

</div>

<?php
}
?>





</div>
</section>





<script>

function SwapDescription(HideDescription,ShowDescription){

document.getElementById(HideDescription).style.display='none';
document.getElementById(ShowDescription).style.display='block';
}

function SwapTest(HideTest,ShowTest){

document.getElementById(HideTest).style.display='none';
document.getElementById(ShowTest).style.display='block';
}

</script>


</div>
</body>

<footer class="GlobalFooter Footer_Big ">
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