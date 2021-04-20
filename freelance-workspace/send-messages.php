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
	
	// раздел о выводе новых сообщений или вообще входящих
	
	$inboxbd=mysql_query("SELECT * FROM `Messages` WHERE `IdFrom`='$id_user' AND `Deleted`='0' AND `Archive`='0'");

	
	// раздел добавления в архив
	
	$ArchiveError = 0;
	if(isset($_POST['HiddenArchive']) && isset($_POST['IdArchive'])){
	$ToArchive=htmlspecialchars(trim($_POST['HiddenArchive']));
	$IdArchive=htmlspecialchars(trim($_POST['IdArchive']));
	if($ToArchive == 1) {
	
$RegisterArchive="UPDATE `Messages` SET `Archive`='1' WHERE `id`='$IdArchive' ";
$resultArchive=mysql_query($RegisterArchive);
if($resultArchive==true){
	header("location: send-messages.php");
}
else
{
echo "Error! ---->". mysql_error();
	$ArchiveError = 1;
}	
	
					} else {
	$ArchiveError = 1;
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



 <header>
 <h2>Входящие сообщения</h2>
 </header>
 
<div class="">
<?php
while( $inbox_data=mysql_fetch_array($inboxbd)) {
$inbid = $inbox_data['id'];
$IdFrom = $inbox_data['IdFrom'];
$IdTo = $inbox_data['IdTo'];
$Title = $inbox_data['Title'];
$Time = $inbox_data['Time'];
$Message = $inbox_data['Message'];
?>
<div id="<?php echo $inbid; ?>">
    <p><?php echo $IdFrom; ?></p>
	<p><?php echo $IdTo; ?></p>
	<p><?php echo $Time; ?></p>
	<p><?php echo $Message; ?></p>
	<p><?php echo $Title; ?></p>
	<a href="read-message.php?id=<?php echo $inbid; ?>">Перейти к сообщению</a> 
	
<form method="post" name="ArchiveForm" id="ArchiveForm"> 
<input type="hidden" name="HiddenArchive" id="HiddenArchive" Value= "1">
<input type="hidden" name="IdArchive" id="IdArchive" Value= "<?php echo $inbid; ?>">
<input type="submit" name="SubmitArchive" id="SubmitArchive">
</form>	
	
	<p>Удалить (Отправить в архив)</p>
	
<?php //прочитано - не прочитано
$Readed =  $inbox_data['Readed'];
if($Readed == 0) {
?>
	<p>Получатель не прочитал</p>
<?php 
} else {
?>
	<p>Получатель прочитал</p>
<?php 
} // окончание прочитано - не прочитано
?>	
	
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