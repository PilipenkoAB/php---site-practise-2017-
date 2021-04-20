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
	
	// раздел о выводе списка пользователей, с которыми идет какое то взаимодействие чтобы можно было отправлять им сообщения
	
	$responsebd=mysql_query("SELECT * FROM `ResponseNewJob` WHERE `FreelancerId`='$id_user' AND `Deleted`='0' AND `Result`='2' ");

	// раздел отправки сообщения:
	$error = 0;
	if(isset($_POST['MessageFor']) && ($_POST['TitleMessage']) && ($_POST['TextMessage'])) {
	
	$MessageFor=htmlspecialchars($_POST['MessageFor']);
	$TitleMessage=htmlspecialchars($_POST['TitleMessage']);
	$TextMessage=htmlspecialchars($_POST['TextMessage']);
	
	if($MessageFor == 0) {
	$error = 1;
	}
	
		// если пустой заголовок и описание - ошибка
	$TitleMessageCheck=trim($TitleMessage); // проверка на пробелы (чтобы нельзя было прислать просто пробелы)
	$TextMessageCheck=trim($TextMessage); // проверка на пробелы (чтобы нельзя было прислать просто пробелы)
	if(empty($TitleMessageCheck) || empty($TextMessageCheck)) { // если ничего не пришло 
	$error = 1; 
		}
	

     
	 if($error !== 1) {

$IdFrom=$id_user;
$Time=date('Y-m-d H:i:s');
	 
	 
$RegisterMessage="INSERT INTO `Messages`(`IdFrom`,`IdTo`,`Title`,`Message`,`Time`) VALUES ('$IdFrom', '$MessageFor', '$TitleMessage', '$TextMessage', '$Time')";
$ResultRegister=mysql_query($RegisterMessage);

if($ResultRegister==true)
	{
	header("location: complete-messages.php");
}
else
	{
echo "Error! ---->". mysql_error();
}
	 }
	
	}
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
<a href="new-messages.php" class="FW_top_nav_href">Новое сообщение</a>
</li>
<li class="FW_top_subnav_ul">
<a href="inbox-messages.php" class="FW_top_nav_href">Входящие сообщения</a>
</li>
<li class="FW_top_subnav_ul">
<a href="send-messages.php" class="FW_top_nav_href">Исходящие сообщения</a>
</li>
<li class="FW_top_subnav_ul">
<a href="archive-messages.php" class="FW_top_nav_href">Архив сообщений</a>
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

<!-- Объявление об ошибке  на проверке сервера -->
<?php
if($error == 1) {
?>
<div id="ActivationAd" class="ActivationAd"> 
    <p>ОШИБКА В ЗАПОЛНЕНИИ ДАННЫХ</p><br>
</div>
<?php } ?>
<!-- Окончания объявления об ошибке -->


 <header>
 <h2>Новое сообщение</h2>

 </header>
 
 <div class="">

<form method="post" name="MessageForm" id="MessageForm" >
 
<div class="">
 <div class="">
Кому отправить
</div>
 <div class="">
  <select name="MessageFor" class="" id="MessageFor">
 <option value="0" label="Выберите кому отправлять" >Выберите кому отправлять</option>
<?php
$TheArray=array();
while( $response_data=mysql_fetch_array($responsebd)) {
$job_id = $response_data['JobId'];

$emplbd=mysql_query("SELECT * FROM `jobs` WHERE `id`='$job_id'   ");
 $emplbd_data=mysql_fetch_array($emplbd);
$empl_id = $emplbd_data['EmployerId'];

if (in_array($empl_id,$TheArray)) {
 echo "naideno";
} else {
 echo "ne naideno";
  $TheArray[] = $empl_id;

?>

<option value="<?php echo $empl_id; ?>" label="<?php echo $empl_id; ?>" ><?php echo $empl_id; ?></option>
					
<?php 
	}
 } ?>

</select>
</div>
</div>
 
<div class="" >
<div class="">
Тема сообщения
</div>
<div class="">
<input type="text" name="TitleMessage" id="TitleMessage" value maxlength="50" class="" placeholder="Заголовок сообщения"></textarea>
</div>
</div>

<div class="" >
<div class="">
Введите сообщение
</div>
<div class="">
<textarea name="TextMessage" id="TextMessage" value maxlength="5000" rows="20" cols="60" class="" placeholder="Текст сообщения"></textarea>
</div>
</div>


<button type="submit"  id="send" class="Signup_submit">Отправить сообщение</button>

</form>

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