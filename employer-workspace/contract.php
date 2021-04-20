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
$BD->set_charset("utf8");

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
	$ReservedMoneyEmployer=$User_Information_Data['ReservedMoney'];	
	
// Переменная "Активация Почтового Ящика" текущего пользователя
	$Activation_Email=$User_Information_Data['Activation'];
	
/* Освобождаем память $User_Information */ 
$User_Information->close(); 
}	
	
	
/* Принятие GET запроса с id  для определения id работы */
$id = htmlspecialchars(trim( isset($_GET['id']) ? (int) $_GET['id'] : 0));




// Проверка, является ли данный контракт частью работы данного работодателя
// Узнаём id работы (Job) данного контракта
if($CheckContract = $BD -> query ("SELECT * FROM `Jobs` WHERE `id` = '$id' ")) {
$CheckContract_Data = $CheckContract->fetch_assoc();
$JobId=$CheckContract_Data['id'];
$FreelancerId=$CheckContract_Data['FreelancerId'];
$Active=$CheckContract_Data['Active'];
$Close=$CheckContract_Data['Closed'];
$Ban=$CheckContract_Data['Ban'];
//Информация, кто принял, а кто не принял контракт (Employer\Freelance Agree)
$EmployerAgree=$CheckContract_Data['EmployerAgree'];
$FreelancerAgree=$CheckContract_Data['FreelancerAgree'];
//Информация для формирования контракта
$Deadline=$CheckContract_Data['Deadline'];
$Prepayment=$CheckContract_Data['Prepayment'];
$Parts=$CheckContract_Data['Parts'];
$FullPrice=$CheckContract_Data['FullPrice'];
$ReservedMoneyInfo=$CheckContract_Data['ReservedMoney'];
$FreelancerTextBack=$CheckContract_Data['FreelancerTextBack'];


$Commission=$CheckContract_Data['Commission'];
$Price=$CheckContract_Data['Price'];

// Узнаем кто работодатей у найденной работы
$EmployerId=$CheckContract_Data['EmployerId'];

//Данные для информации о ценах, предоплате, сроках
$StartMinPrice=$CheckContract_Data['StartMinPrice'];
$StartMaxPrice=$CheckContract_Data['StartMaxPrice'];
$StartPrice=$CheckContract_Data['FullPrice'];
$StartPrepayment=$CheckContract_Data['Prepayment'];
$Duration=$CheckContract_Data['Duration'];

$CheckContract->close();
}

/* эта информация теперь узнается выше (бывший контракт)
// Узнаем кто работодатей у найденной работы
if($CheckJob = $BD -> query ("SELECT * FROM `Jobs` WHERE `id` = '$JobId' ")) {
$CheckJob_Data = $CheckJob->fetch_assoc();
$EmployerId=$CheckJob_Data['EmployerId'];
//Данные для информации о ценах, предоплате, сроках
$StartPrice=$CheckJob_Data['StartPrice'];
$StartPrepayment=$CheckJob_Data['Prepayment'];
$Duration=$CheckJob_Data['Duration'];

$CheckJob->close();
}
*/

// Сверяем текущего работодателя и найденного в работе работодателя (Если всё нормально, то работаем дальше, иначе ошибка)
if( $User_Id != $EmployerId){
header("location: errorcheckemployercontract.php"); // Переход на страницу ошибки
}


// Проверка, является ли контракт активированным или только в процессе формирования
if( $Active == '0') {			// Если контракт не сформирован
echo "active = 0";

// Формирование контракта
if(isset($_POST['NewPrice']) && isset($_POST['NewDeadline']) && isset($_POST['NewPrepayment']) && isset($_POST['NewParts'])) {
echo "данные пришли";

// Пришедшие переменные
$NewPrice=htmlspecialchars(trim($_POST['NewPrice']));
$NewDeadline=htmlspecialchars(trim($_POST['NewDeadline']));
$NewPrepayment=htmlspecialchars(trim($_POST['NewPrepayment']));
$NewParts=htmlspecialchars(trim($_POST['NewParts']));

// Валидация пришедших данных

// !!
// !! 
// !!

// Проверяется наличие нужной суммы у работодателя для резервации денег
if($CheckMoney = $BD -> query ("SELECT * FROM `users` WHERE `id` = '$User_Id' ")) {
$CheckMoney_Data = $CheckMoney->fetch_assoc();
$EmployerMoney=$CheckMoney_Data['Money'];
$EmployerReservedMoney=$CheckMoney_Data['ReservedMoney'];
$CheckMoney->close();
}

if($EmployerMoney >= $NewPrice) {  // Если денег у заказчика больше или равно заданной сумме, то отправить контракт фрилансеру

// Резервирование денег на задание у ЗАКАЗЧИКА
$NewMoneyEmployer=bcsub($EmployerMoney, $NewPrice,2);
$NewReservedMoneyEmployer=bcadd($EmployerReservedMoney, $NewPrice,2);
if(!$BD->query("UPDATE `users` SET  `Money`='$NewMoneyEmployer' , `ReservedMoney`='$NewReservedMoneyEmployer' WHERE  `id`='$User_Id'  ")){
	printf("Ошибка 2: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
		}else{
// Занесение денег в контракт
if(!$BD->query("UPDATE `Jobs` SET  `ReservedMoney`='$NewPrice', `FullPrice`='$NewPrice', `Deadline`='$NewDeadline',`Prepayment`='$NewPrepayment',`Parts`='$NewParts',`EmployerAgree`='1' WHERE `id`='$JobId' ")){
	printf("Ошибка 3: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
	header("location: contract.php?id=$id");				// если операция прошла успешно			
				}
			}
}else{ // Если денег не достаточно, выдать ошибку
echo "Денег НЕ хватает";
	}
}






// Формирование после того, как фрилансер отказался
if(isset($_POST['ChangedPrice']) && isset($_POST['ChangedDeadline']) && isset($_POST['ChangedPrepayment']) && isset($_POST['ChangedParts'])) {
echo "данные пришли изменённые";

// Пришедшие переменные
$ChangedPrice=htmlspecialchars(trim($_POST['ChangedPrice']));
$ChangedDeadline=htmlspecialchars(trim($_POST['ChangedDeadline']));
$ChangedPrepayment=htmlspecialchars(trim($_POST['ChangedPrepayment']));
$ChangedParts=htmlspecialchars(trim($_POST['ChangedParts']));

// Валидация пришедших данных

// !!
// !! 
// !!

// Проверяется наличие нужной суммы у работодателя для резервации денег
if($CheckMoney = $BD -> query ("SELECT * FROM `users` WHERE `id` = '$User_Id' ")) {
$CheckMoney_Data = $CheckMoney->fetch_assoc();
$EmployerMoney=$CheckMoney_Data['Money'];
$EmployerReservedMoney=$CheckMoney_Data['ReservedMoney'];
$CheckMoney->close();
}
// Изменение суммы для работа ($EmployerMoneyUpgrade(( $EmployerMoney + $ReservedMoneyInfo)) >= $NewPrice)
$EmployerMoneyUpgrade=bcadd($EmployerMoney, $ReservedMoneyInfo,2);
$EmployerReservedMoneyUpgrade=bcsub($EmployerReservedMoney, $ReservedMoneyInfo,2);

if($EmployerMoneyUpgrade >= $ChangedPrice) {  // Если денег у заказчика больше или равно заданной сумме, то отправить контракт фрилансеру
// Резервирование денег на задание у ЗАКАЗЧИКА
$NewMoneyEmployer=bcsub($EmployerMoneyUpgrade, $ChangedPrice,2);
$NewReservedMoneyEmployer=bcadd($EmployerReservedMoneyUpgrade, $ChangedPrice,2);
if(!$BD->query("UPDATE `users` SET  `Money`='$NewMoneyEmployer' , `ReservedMoney`='$NewReservedMoneyEmployer' WHERE  `id`='$User_Id'  ")){
	printf("Ошибка 2: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
// Занесение денег в контракт
if(!$BD->query("UPDATE `Jobs` SET  `ReservedMoney`='$ChangedPrice', `FullPrice`='$ChangedPrice', `Deadline`='$ChangedDeadline',`Prepayment`='$ChangedPrepayment',`Parts`='$ChangedParts',`FreelancerAgree`='0' WHERE `id`='$JobId' ")){
	printf("Ошибка 3: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
			}else{
	header("location: contract.php?id=$id");			// если операция прошла успешно			
				}
		}
	}else{ // Если денег не достаточно, выдать ошибку
echo "Денег НЕ хватает";
	}
}
} elseif ($Active == '1') {		// Если контракт  сформирован

echo "active = 1";




// Если написано новое сообщение
//if(isset($_POST['Message'])) {
//$NewMessage=htmlspecialchars($_POST['Message']);
//$Time=date('Y-m-d H:i:s');

//if(!$BD->query("INSERT INTO `Messages` (`IdFrom`,`IdTo`,`Type`,`Message`,`Time`) VALUES ('$User_Id', '$FreelancerId','$id','$NewMessage','$Time')")){
//
//	printf("Ошибка 3: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
//			}else{
//	header("location: contract.php?id=$id");			// если операция прошла успешно			
//}
//}






//---------------------------------------------------------
//------------- НЕ АКТИВНО!!!!!!!!!!!!!!!!!-----------------------
//----------------------------------------------------------	
/*
// отмена подтверждения контракта
if(isset($_POST['DisagreeCon'])) {
$DisagreeContract=htmlspecialchars(trim($_POST['DisagreeContract']));

$RegisterDisagreeUpdate="UPDATE `Jobs` SET `EmployerAgree`='0' WHERE `id`='$id' ";
$resultUpdateDisagree=mysql_query($RegisterDisagreeUpdate);
if($resultUpdateDisagree==true)
{

// возврат денег из резерва обратно заказчику

$CheckMoney=mysql_query("SELECT * FROM `users` WHERE `id`='$EmployerId' ");
$CheckMoney_data=mysql_fetch_array($CheckMoney);
$EmployerMoney=$CheckMoney_data['Money'];
$EmployerReservedMoney=$CheckMoney_data['ReservedMoney'];
$NewReservedMoneyEmployer=bcsub($EmployerReservedMoney, $FullPrice,2);
$NewMoneyEmployer=bcadd($EmployerMoney, $FullPrice,2);

$RegisterBackUpdate="UPDATE `Jobs` SET `ReservedPrice`='0' WHERE `id`='$id' ";
$resultBackUpdate=mysql_query($RegisterBackUpdate);

if($resultBackUpdate==true)
{
$EmployerMoneyBackUpdate="UPDATE `users` SET  `Money`='$NewMoneyEmployer' , `ReservedMoney`='$NewReservedMoneyEmployer' WHERE  `id`='$EmployerId'  ";
$resultEmployerMoneyBackUpdate=mysql_query($EmployerMoneyBackUpdate);

if($resultEmployerMoneyBackUpdate==true)
{
	header("location: contract.php?id=$id");

	}else
{
echo "Error! ---->". mysql_error();
	}
	
	
	}else
{
echo "Error! ---->". mysql_error();
	}
	
	
	
	}	else
{
echo "Error! ---->". mysql_error();
	}

			}
*/
	//----------------------------------------------------------			
	//----------------------------------------------------------			
		//----------------------------------------------------------		
			
			
			
			
			
//---------------------------------------------------------
//------------- ЗАКРЫТИЕ КОНТРАКТА-----------------------
//----------------------------------------------------------			
			
if(isset($_POST['HiddenSubmitClose'])){


$SubmitClose=htmlspecialchars(trim($_POST['HiddenSubmitClose']));

$CommisionUser = 1; // id commision = 1. 
// Информация о деньгах коммиссии
if($CheckMoneyCommission_Information = $BD -> query ("SELECT * FROM `users` WHERE `id` = '$CommisionUser' ")) {
$CheckMoneyCommission_Data = $CheckMoneyCommission_Information ->fetch_assoc();
$CheckMoneyCommission =$CheckMoneyCommission_Data['Money'];
$CheckMoneyCommission_Information->close();
}

// Информация о деньгах фрилансера
if($CheckMoneyFreelancer_Information = $BD -> query ("SELECT * FROM `users` WHERE `id` = '$FreelancerId' ")) {
$CheckMoneyFreelancer_Data = $CheckMoneyFreelancer_Information ->fetch_assoc();
$CheckMoneyFreelancer =$CheckMoneyFreelancer_Data['Money'];
$CheckMoneyFreelancer_Information->close();
}

// Изменение суммы для работа ($EmployerMoneyUpgrade(( $EmployerMoney + $ReservedMoneyInfo)) >= $NewPrice)
$ClosePrice=bcadd($CheckMoneyFreelancer, $Price,2);
$CloseCommission=bcadd($CheckMoneyCommission, $Commission,2);

$CloseReserverMoney=bcsub($ReservedMoneyEmployer,$Price,2);


$CloseData=date('Y-m-d H:i:s');

$CloseComment = 0;

$CloseReputation= 0;

// Передача денег Фрилансеру
if(!$BD->query("UPDATE `users` SET  `Money`='$ClosePrice'  WHERE  `id`='$FreelancerId'  ")){
printf("Ошибка 1: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
	
	// Передача  Коммисии
if(!$BD->query("UPDATE `users` SET  `Money`='$CloseCommission'  WHERE  `id`='$CommisionUser'  ")){
printf("Ошибка 2: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
	
	
		// Фиксация в журнале коммисии о принятии коммисии
if(!$BD->query("INSERT INTO `Commission` SET  `Money`='$Commission', `Data`='$CloseData', `Job`='$JobId'  ")){
printf("Ошибка 3: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{

		// Снятие резерва с работодателя
if(!$BD->query("UPDATE `users` SET  `ReservedMoney`='$CloseReserverMoney'  WHERE  `id`='$User_Id'  ")){
printf("Ошибка 4: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
	
// Закрытие контракта
if(!$BD->query("UPDATE `Jobs` SET  `Closed`='1', `CommissionPaid`='1', `CloseData`='$CloseData',`Comment`='$CloseComment',`Reputation`='$CloseReputation' WHERE `id`='$JobId' ")){
	printf("Ошибка 5: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
			}else{
	header("location: contract.php?id=$id");			// если операция прошла успешно			
					}
				}
			}
		}	
	}
}
			
//---------------------------------------------------------
//------------- БАН КОНТРАКТА   -----------------------
//----------------------------------------------------------		


if(isset($_POST['HiddenSubmitBan'])){


$SubmitClose=htmlspecialchars(trim($_POST['HiddenSubmitBan']));
			
	if(!$BD->query("UPDATE `Jobs` SET  `Ban`='1' WHERE `id`='$JobId'    ")){
	printf("Ошибка 1: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
			}else{
	header("location: contract.php?id=$id");			// если операция прошла успешно					
			}
}





//закрытие active=1
}
?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js" charset="utf-8"></script> <!-- именно эта версия, потому что другие не поддерживают функции для уничтожения тегов в поле див -->
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
<a class="" href="job.php?id=<?php echo $JobId ?>">Описание работы</a>
</li>
<li>
<a class="" href="team.php?id=<?php echo $JobId ?>">Команда</a>
</li>
<li>
<a class="" href="noname.php">______</a>
</li>
</ul>
</nav>
















<div class="FM_middle_center">

 <header>
 <h2> Информация о контракте </h2>
 <br>
 </header>

 
 
 
 <!-- Начала списка формирования вида контрактов -->
 
 
  <?php
 if($Active == 0  AND $Ban == 0) {  // Если контракт не сформирован
 ?>

 <!-- ОБЩАЯ ИНФОРМАЦИЯ -->
 <p> Контракт не сформирован </p>
 
<label class="Label">Цена  -  (Коммисиия) (указанная при создании работы)</label>
<div class="">
<?php
echo $StartPrice;
$CommissionStartPrice=$StartPrice*0.1;
echo " - ( $CommissionStartPrice - коммиссия)";
?>
</div>
  
  
<label class="Label">Предоплата (указанная при создании работы):</label>
<div class="">
<?php
if($StartPrepayment == 0) {
echo "Нет Предоплаты";
} 
if($StartPrepayment == 1) {
echo "25%";
} 
if($StartPrepayment == 2) {
echo "50%";
} 
if($StartPrepayment == 3) {
echo "75%";
} 
if($StartPrepayment == 4) {
echo "100%";
} 
?>
</div>

<label class="Label">Сроки(указанные при создании работы):</label>
<div class="">
<?php
if($Duration == 1) {
echo "Больше 6 месяцев";
}elseif($Duration == 2) {
echo "3 - 6 месяцев";
}elseif($Duration == 3) {
echo "1 - 3 месяца";
} elseif($Duration == 4) {
echo "Меньше месяца%";
} elseif($Duration == 5) {
echo "Меньше недели";
} elseif($Duration == 6) {
echo "1 день";
} 
?>
</div>

 <br>
 Указанные значения для контракта будут уникальными для каждого созданного контракта и никак не изменят значения, указанные в работе (Это нужно для того, чтобы каждый контракт был сформирован для каждого фрилансера индивидуально)
 <br>
 <p> Будьте внимательны, при формировании контракта, после того, как фрилансер подтверждает ВАШ контракт, вы не сможете его переделать</p>
 
 
 <!-- ЕСЛИ ЗАКАЗЧИИК ЕЩЁ НЕ СФОРМИРОВАЛ КОНТРАКТ -->
  <?php
 if ($EmployerAgree == 0 && $FreelancerAgree != 2){
 ?>
 
 <br>
 <br>
 Формирование контракта:
 <br>
 
 <form action="contract.php?id=<?php echo $id ?>" name="CreateContract" id="CreateContract" class="" method="post">
 
 <div class="">
Назначить окончательную цену(Price) :
<input type="text" name="NewPrice" id="NewPrice" Value="">
</div>
 
 <div class="">
Назначить окончательные сроки (Deadline) :
<input type="date" name="NewDeadline" id="NewDeadline" Value="">
</div>

<div class="">
Назначить окончательную предоплату (Prepayment) :
<select name="NewPrepayment" class="" id="NewPrepayment" Value="">
<option  value="<?php echo $Prepayment; ?>" label="Текущее значение">Текущее значение</option>
<option  value="0" label="Нет предоплаты">Нет предоплаты</option>
<option  value="1" label="25%">25%</option>
<option  value="2" label="50%">50%</option>
<option  value="3" label="75%">75%</option>
<option  value="4" label="100%">100%</option>
</select>
</div>
 
 <div class="">
Назначить окончательное кол-во частей(Parts) :
<select name="NewParts" class="" id="NewParts" Value="<?php echo $Parts; ?>">
<option  value="<?php echo $Parts; ?>" label="Текущее значение">Текущее значение</option>
<option  value="1" label="1 часть">1 часть</option>
<option  value="2" label="2 части">2 части</option>
<option  value="3" label="3 части">3 части</option>
<option  value="4" label="4 части">4 части</option>
<option  value="5" label="5 частей">5 частей</option>
<option  value="6" label="6 частей">6 частей</option>
<option  value="7" label="7 частей">7 частей</option>
<option  value="8" label="8 частей">8 частей</option>
<option  value="9" label="9 частей">9 частей</option>
<option  value="10" label="10 частей">10 частей</option>
</select>
</div>


<input type="submit" class="" id="submitAgree" value="Сформировать контракт">
  </form>
 <?php }  ?>
 
 
 <!-- ЕСЛИ ЗАКАЗЧИИК ПРИНЯЛ КОНТРАКТ, А ФРИЛАНСЕР  - НЕТ  -->
 <?php
 if ($EmployerAgree == 1 && $FreelancerAgree != 2){
 
 echo "Ожидания принятия заявки фралинсером. Можно отменить заявку";
 
 }
 ?>
  
    
<!-- ЕСЛИ ЗАКАЗЧИИК ПРИНЯЛ КОНТРАКТ, А ФРИЛАНСЕР  - ВЕРНУЛ НА ДОРАБОТКУ -->
 <?php
 if ($EmployerAgree == 1 && $FreelancerAgree == 2){
 ?>
 
 <br>
 <br>
 Контракт возвращён фрилансером на доработку контракта:
 <br>
 
  <div class="">
Ответ от фрилансера:
<?php
echo "$FreelancerTextBack";
?>
</div>
 
 
 
 
 <form action="contract.php?id=<?php echo $id ?>" name="CreateContract" id="CreateContract" class="" method="post">
 
 <div class="">
Назначить окончательную цену(Price)Предыдущее значение: <?php echo "$FullPrice"; ?>:
<input type="text" name="ChangedPrice" id="ChangedPrice" Value="">
</div>
 
 <div class="">
Назначить окончательные сроки (Deadline)Предыдущее значение:<?php echo "$Deadline"; ?>:
<input type="date" name="ChangedDeadline" id="ChangedDeadline" Value="">
</div>

<div class="">
Назначить окончательную предоплату (Prepayment)Предыдущее значение:<?php echo "$Prepayment"; ?>:
<select name="ChangedPrepayment" class="" id="ChangedPrepayment" Value="">
<option  value="<?php echo $Prepayment; ?>" label="Текущее значение">Текущее значение</option>
<option  value="0" label="Нет предоплаты">Нет предоплаты</option>
<option  value="1" label="25%">25%</option>
<option  value="2" label="50%">50%</option>
<option  value="3" label="75%">75%</option>
<option  value="4" label="100%">100%</option>
</select>
</div>
 
 <div class="">
Назначить окончательное кол-во частей(Parts) Предыдущее значение:<?php echo "$Parts"; ?>:
<select name="ChangedParts" class="" id="ChangedParts" Value="<?php echo $Parts; ?>">
<option  value="<?php echo $Parts; ?>" label="Текущее значение">Текущее значение</option>
<option  value="1" label="1 часть">1 часть</option>
<option  value="2" label="2 части">2 части</option>
<option  value="3" label="3 части">3 части</option>
<option  value="4" label="4 части">4 части</option>
<option  value="5" label="5 частей">5 частей</option>
<option  value="6" label="6 частей">6 частей</option>
<option  value="7" label="7 частей">7 частей</option>
<option  value="8" label="8 частей">8 частей</option>
<option  value="9" label="9 частей">9 частей</option>
<option  value="10" label="10 частей">10 частей</option>
</select>
</div>


<input type="submit" class="" id="submitAgree" value="Сформировать контракт">
  </form>
 <?php }  ?> 
 
 
 <?php
} // закрытие if (active = 0)
?>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 <?php
 if($Active == 1 AND $Close == 0  AND $Ban == 0) {
 ?>
 <section class="FW_middle_change_profile">


 <!-- Если нажата кнопка закрыть контракт, будет другое поле принятое с GET -->
 <?php
 /* Принятие GET запроса с close и ban  для определения закрытия\ban контракта */
$Close_Contract = htmlspecialchars(trim( isset($_GET['close'])));
$Ban_Contract = htmlspecialchars(trim( isset($_GET['ban'])));
  if(empty($Close_Contract) && empty($Ban_Contract)){
 ?>

 
 <a href="contract.php?id=<?php echo $id; ?>&close" class="FW_top_nav_2_href">Перейти к закрытию контракта</a>
 <a href="contract.php?id=<?php echo $id; ?>&ban" class="FW_top_nav_2_href">Заморозить (бан) контракт</a>



 <!-- Если нажата кнопка закрыть контракт -->
 <?php
 }elseif(!empty($Close_Contract)) {
 

$CommisionInfo=$CheckContract_Data['Commission'];
$PriceInfo=$CheckContract_Data['Price'];
$PrepaymentInfo=$FullPrice - $CommisionInfo - $PriceInfo;
 ?>
 
Закрытие контракта:

 <?php echo " вся цена - $FullPrice , резерв - $ReservedMoneyInfo, оплата фрилансеру - $PriceInfo (включая предоплату - $PrepaymentInfo),  коммисия - $CommisionInfo";  ?>
<br>
информация о времени выполнения работы

+ 

Отзыв о работе + начисление репутации

==

с резерва деньги  к фрилансеру 
с резерва коммисии к коммисии
отзыв+репутация -- к работе
контракт переносится в архив


  <form action="" name="CloseContract" id="CloseContract" class="" method="post">
<input type="hidden" class="" name="HiddenSubmitClose" id="HiddenSubmitClose" value="1">
<input type="submit" class="" id="SubmitClose" value="Перейти к закрытию контракта">

</form>

 <!-- Если нажата кнопка заморозить контракт -->
 <?php
 }elseif(!empty($Ban_Contract)) {
 ?>

 <form action="" name="BanContract" id="BanContract" class="" method="post">
BAN
<input type="submit" class="" id="HiddenSubmitBan" name="HiddenSubmitBan" value="Заморозить контракт (Бан)">
</form>
 
 
  <?php
 }
 ?>
 
 
 
  </section>
 <?php
}
?>

<!---------------------------------------------------------------------------------->
<!-------------------------  ЗАКРЫТЫЙ КОНТРАКТ  ------------------------------------->
<!---------------------------------------------------------------------------------->
 <?php
 if($Active == 1 AND $Close == 1 AND $Ban == 0) {
 ?>
 <section class="FW_middle_change_profile">
 
 
 Работа закрыта (контракт закрыт)
 
   </section>
  <?php
}
?>


<!------------------------------------------------------------------------------------------>
<!-------------------------  ЗАБЛОКИРОВАННЫЙ КОНТРАКТ  ------------------------------------->
<!------------------------------------------------------------------------------------------>

  <?php
 if($Active == 1 AND $Close == 0 AND $Ban == 1) {
 ?>
 <section class="FW_middle_change_profile">
 
 
Заблокированный контракт (до выяснение обстоятельств с администрацией)
 
   </section>
  <?php
}
?>

<!------------------------- ------------------------------------->
<!---------- Раздел, отделяющий рабочую часть от чата ----------->
<!------------------------- ------------------------------------->
<br>
---------------
---------------
<br> 
ЧАТ С ФРИЛАНСЕРОМ
<br>
<br>

<!-- РАЗДЕЛ ЧАТА С ФРИЛАНСЕРОМ -->

<table  cellspacing="0" cellpadding="0">
<tbody id="Message_place" class="the-return">


</tbody>
</table>






<!--
<div class="the-return">
  [HTML is replaced when successful.]
</div>


<div id ="message-box1">
31
</div> 


<form action="contract.php?id=<?php echo $id ?>" name="NewMessage" id="NewMessage" class="" method="post">
<div class="Message-box" id="testtest" contenteditable="true" style="height: 150px; overflow-y: auto;">
</div> 
<input type="text" name="Message" id="Message" Value="">
<input type="submit" class="" id="submitMessage" value="Отправить" onclick="NewMessage()">
</form>
-->

<div class="Message-box" id="Message-box" contenteditable="true" style="height: 150px; overflow-y: auto;">
</div> 
<input type="submit" class="" id="submitMessage" value="Отправить" onclick="NewMessage()">


</div>
</section>


<!--<script type="text/javascript" src="jquery-1.5.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="jquery.min.js" charset="utf-8"></script>
-->

<script>
	$(function(){
	
	

	function update(){
	
	
    var data = {
      "action": "test",
	  "contract": "<?php echo $id?>"
	      };
    data = $(this).serialize() + "&" + $.param(data);
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "s_response.php", //Relative or absolute path to response.php file
      data: data,
      success: function(data) { // если данные успешно пришли
	 
	  $('#Message_place').empty();  // очищает все сообщения

// в цикле от первого до последнего сообщения, пришедшего из запроса выводим текст по очереди	 	  
	   i = 1; // счетчик
	  while( i < data["count"]){   // пока данные не стали равны кол-ву сообщений
	 
//	 alert (data["count"]+i);
	  
$('<tr><td class="Message_author"><div class="chatComm" id="'+i+'">"'  + data["Who "+i] + '"</div></td><td class="Message_text"><div class="chatComm" id="'+i+'">"'  + data["text "+i] + '"</div></td><td  class="Message_data"><div class="chatComm" id="'+i+'">"'  + data["time "+i] + '"</div></td></tr>').appendTo('#Message_place'); // создаем новый элемент в id=message_place

  //	 $(".the-return").html( 
//	  "<div id="+i+"><br />Gender: " + data["text "+i] + "<br />JSON: " + data["json"] + "</div>"
//	  ); 
	  
     i++; // прибавляем 1 к счётчику
	}

   //    alert("Form submitted successfully.\nReturned json: " + data["json"]);
      }
    });
  

                    // обновим таймер 
                    update_timer();
                }
	
	update();

    // что бы окно чата обновлялось раз в 10 секунд, прицепим таймер
	
    var timer;
    function update_timer() {
        if (timer) // если таймер уже был, сбрасываем
            clearTimeout(timer);
        timer = setTimeout(function () {
            update();
        }, 5000); // 1000 - 1 sec
    }
    update_timer();
	
			

			});
	
	
		// При нажатии кнопки "отправить" - отправка данных на скрипт и обновление таймера при усмешном завершении
	function NewMessage() {
	
	var texttext = $('#Message-box').html();
	
	 var data = {
	  "contract": "<?php echo $id?>",
	  "message": texttext
	      };
    data = $(this).serialize() + "&" + $.param(data);
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "s_new-message.php", //Relative or absolute path to response.php file
      data: data,
      success: function(data) { // если данные успешно пришли
		$('#Message-box').empty();  
			}
	});
	
	}
	

	
// чтобы в поле вносилось без тегов, без кода и тд. Чистый текст
$('[contenteditable]').on('paste',function(e) {
    e.preventDefault();
    var text = (e.originalEvent || e).clipboardData.getData('text/plain') || prompt('Paste something..');
    window.document.execCommand('insertText', false, text);
});

</script>




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