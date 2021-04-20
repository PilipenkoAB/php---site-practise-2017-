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






	
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	
	
	
	
	// раздел объявления об доступе к заданиям
	//$activationemail=$user_data['Activation'];
	//
	//$activlang=mysql_query("SELECT * FROM `user_tests` WHERE `userid`='$User_Id' AND `testid`='1'");
	//$activlang_data=mysql_fetch_array($activlang);
	//$activationlang=$activlang_data['testid'];
	
	
	
	
	
	// раздел об выводе заданий которые ожидают подтверждения или отзыва
	
	
if ($Contracts_Information = $BD->query("SELECT * FROM `Jobs` WHERE `FreelancerId`='$User_Id' AND `id`='$id'")) { 
$Contracts_Information_Data = $Contracts_Information->fetch_assoc();
	

	$EmptyContract = 0;  // если нет контракта - то 1, и тогда просто надпись о том, что нет контракта, если 0 - то вывод контракта и работы с ним

If (empty($Contracts_Information_Data)) {
	$EmptyContract = 1;
} else {

$IdJob=$Contracts_Information_Data['id'];
$IdFreelancer=$Contracts_Information_Data['FreelancerId'];
$IdEmployer=$Contracts_Information_Data['EmployerId'];

$EmployerAgree=$Contracts_Information_Data['EmployerAgree'];
$FreelancerAgree=$Contracts_Information_Data['FreelancerAgree'];

$FullPrice=$Contracts_Information_Data['FullPrice'];
$Prepayment=$Contracts_Information_Data['Prepayment'];
$Commission=$Contracts_Information_Data['Commission'];
$Deadline=$Contracts_Information_Data['Deadline'];
$Price=$Contracts_Information_Data['Price'];
$ReservedPrice=$Contracts_Information_Data['ReservedMoney'];
$IdPriceParts=$Contracts_Information_Data['IdPriceParts'];

$Active=$Contracts_Information_Data['Active'];
	

		
	

// подтверждение контракта

if(isset($_POST['AgreeContract'])) {

$AgreeContract=htmlspecialchars(trim($_POST['AgreeContract']));

if($FullPrice == $ReservedPrice) {


if(!$BD->query("UPDATE `Jobs` SET  `FreelancerAgree`='1', `Active`='1' WHERE `id`='$id'   ")){
 printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
}else{    // если операция прошла успешно

// Вычисление размера оплаты сервису и предоплаты и конечной суммы оплаты фрилансеру без предоплат и оплаты сервису

$Commission=bcmul($ReservedPrice,0.1, 2);

$ResCom=bcsub($ReservedPrice,$Commission,2);

if($Prepayment == 0) {
$PrepaymentPaid = 0;
}
if($Prepayment == 1) {
$PrepaymentPaid = bcmul($ResCom, 0.25,2);
}
if($Prepayment == 2) {
$PrepaymentPaid=bcmul($ResCom,0.5,2);
}
if($Prepayment == 3) {
$PrepaymentPaid=bcmul($ResCom,0.75,2);
}
if($Prepayment == 4) {
$PrepaymentPaid=bcmul($ResCom,1,2);
}

$PricePaid=bcsub($ResCom,$PrepaymentPaid,2);

$OpenData=date('Y-m-d H:i:s');

if(!$BD->query("UPDATE `Jobs` SET  `Commission`='$Commission', `PrepaymentPaid`='1', `Price`='$PricePaid', `OpenData`='$OpenData' WHERE `id`='$id'  ")){
 printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
}else{    // если операция прошла успешно

 //При этом, если есть предоплата, то из ContractReservedMoney вычитывается сумма,указанная в предоплате, в пунке выплаченно предоплате ставится 1
// и сумма предоплаты переносится в ReservedMoney в `users` фрилансера


if ($Freelacer_Information = $BD->query("SELECT * FROM `users` WHERE `id`='$IdFreelancer'")) { 
$Freelacer_Information_Data = $Freelacer_Information->fetch_assoc();
$FreelancerMoney=$Freelacer_Information_Data['Money'];

$FreelancerPrepaymentPrice=bcadd($FreelancerMoney, $PrepaymentPaid,2);

$Freelacer_Information->close(); 
}

if(!$BD->query("UPDATE `users` SET  `Money`='$FreelancerPrepaymentPrice' WHERE `id`='$IdFreelancer'  ")){
 printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
}else{    // если операция прошла успешно
	

if ($Employer_Information = $BD->query("SELECT * FROM `users` WHERE `id`='$IdFreelancer'")) { 
$Employer_Information_Data = $Employer_Information->fetch_assoc();
$EmployerReservedMoney=$Employer_Information_Data['ReservedMoney'];

$EmployerNewReservedMoney=bcsub( $PrepaymentPaid,$EmployerReservedMoney,2);

$Employer_Information->close(); 
}	
	
if(!$BD->query("UPDATE `users` SET  `ReservedMoney`='$EmployerNewReservedMoney' WHERE `id`='$IdEmployer'  ")){
 printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
}else{    // если операция прошла успешно
header("location: contract.php?id=$id");

    
} // третье else
	} // второе else
		}	// первое else
			}
}    //закрытие  if($FullPrice == $ReservedPrice)
	
		} // if(isset($_POST['AgreeContract'])) {
		
		
		
		
		

// отмена подтверждения контракта
if(isset($_POST['DisagreeContract'])) {
$DisagreeContract=htmlspecialchars(trim($_POST['DisagreeContract']));

if(!$BD->query("UPDATE `Jobs` SET `FreelancerAgree`='2' WHERE `id`='$id' ")){
 printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
}else{
header("location: contract.php?id=$id");			// если операция прошла успешно
	 }	
}

	
	
	} // закрытие else от EmptyContract
$Contracts_Information->close(); 
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
<a class="FW_home_icon" href="index.php" rel="home"></a>
</li>
<li class="FW_top_nav_ul_last">
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
<a href="/mysite/jobs.php" class="FW_top_nav_href">Найти работу</a>
</li>
<li class="FW_top_subnav_ul">
<a href="saved-jobs.php" class="FW_top_nav_href">Сохраненные работы</a>
</li>
<li class="FW_top_subnav_ul">
<a href="accept-request.php" class="FW_top_nav_href">Заявки на работу</a>
</li>
<li class="FW_top_subnav_ul">
<a href="accept-contracts.php" class="FW_top_nav_href">Формирование контрактов</a>
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
<h1 class=""> Контрактов </h1>
<ul class="">
<li>
<a class="" href="noname.php">______</a>
</li>
<li>
<a class="" href="noname.php">_____</a>
</li>
</ul>
</nav>
<div class="FM_middle_center">

 <header>
 <h2> Информация о контракте </h2>
 <br>
 </header>

  <?php
 if($EmptyContract == 1) {
 ?>

 <p> Контракт не сформирован </p>
 
<?php
}
?>
 
 <?php
 if($EmptyContract == 0) {
 ?>
 
<section class="FW_middle_change_profile">

 <div class="">
 <header>
 <h2> Создание контракта </h2>
 </header>



<div class="FW_middle_Read_only">
<label class="Label">Ссылка на работу</label>
<div class="">
<?php
echo $IdJob;
?>
</div>
</div>

<div class="FW_middle_Read_only">
<label class="Label">Фрилансер</label>
<div class="">
<?php
echo $IdFreelancer;
?>
</div>
</div>



<div class="FW_middle_Read_only">
<label class="Label">Цена  -  (Коммисиия)</label>
<div class="">
<?php
echo $FullPrice;
$CommissionPrice=$FullPrice*0.1;
echo " - ( $CommissionPrice - коммиссия)"
?>
</div>


</div>

<div class="FW_middle_Read_only">
<label class="Label">Дата сдачи</label>
<div class="">
<?php
echo $Deadline;
?>
</div>


</div>

<div class="FW_middle_Read_only">
<label class="Label">Предоплата</label>
<div class="">
<?php
if($Prepayment == 0) {
echo "Нет Предоплаты";
} 
if($Prepayment == 1) {
echo "25%";
} 
if($Prepayment == 2) {
echo "50%";
} 
if($Prepayment == 3) {
echo "75%";
} 
if($Prepayment == 4) {
echo "100%";
} 
?>
</div>


</div>


<div class="FW_middle_Read_only">
<label class="Label">Разбиение задание на части</label>
<div class="">
<?php
echo $IdPriceParts;
?>
</div>


</div>


<div class="FW_middle_Read_only">
<label class="Label">Ответ фрилансера</label>
<div class="">
<?php
echo $FreelancerAgree;
?>
</div>
</div>

<?php
if($EmployerAgree == 0) {
?>
<br>
<p> Заказчик не подписал (не оформил контракт) </p>
<br>
<?php
}
?>

<!-- Принятие и отклонение правильности контракта -->

<?php
if($EmployerAgree == 1 && $FreelancerAgree == 0 && $Active == 0) {
?>
<form action="contract.php?id=<?php echo $id ?>" name="ChangeContract" id="ChangeContract" class="" method="post" >
<input type="hidden" name="AgreeContract" id="AgreeContract" value="0" >
<input type="submit" class="" id="submitAgree" value="Принять заявку и сформировать контракт">
</form>
<?php
}
?>


<?php
if($EmployerAgree == 1 && $FreelancerAgree == 0 && $Active == 0) {
?>
<form action="contract.php?id=<?php echo $id ?>" name="ChangeContract" id="ChangeContract" class="" method="post" >
<input type="hidden" name="DisagreeContract" id="DisagreeContract" value="0" >
<input type="submit" class="" id="submitDisagree" value="Отменить (изменить) заявку">
</form>
<?php
}
?>

<?php
if($EmployerAgree == 1 && $FreelancerAgree == 1 && $Active == 1) {
?>
Контракт сформирован. Перейти к выполнению задания
<?php
}
?>

<?php
if($EmployerAgree == 1 && $FreelancerAgree == 2 && $Active == 0) {
?>
Контракт отправлен на доработку (отклонен)
<?php
}
?>


</div>
 </section>



<?php
}
?>
</div>
</section>



<script type="text/javascript" src="jquery-1.5.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="jquery.min.js" charset="utf-8"></script>




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