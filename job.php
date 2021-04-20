<?php
session_start();

include("bd.php");

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$resJob=mysql_query("SELECT * FROM `jobs` WHERE `id`='$id' AND `Search`='1' ");
    $job_data=mysql_fetch_array($resJob);
	$TitleJob=$job_data['Title'];
	$DescriptionJob=$job_data['Description'];
	$DataJob=$job_data['DataCreation'];
	
	if(empty($job_data)){
	header("location: errorjob.php");
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



<!-- НАЧАЛО ПРОВЕРКИ ЧТО ЭТО ФРИЛАНСЕР или РАБОТОДАТЕЛЬ        -->
<?php
// проверка на фрилансера
	if(isset($_SESSION['SessionId'])) {
	$GUID=$_SESSION['SessionId'];
	$res1=mysql_query("SELECT * FROM `users` WHERE `SessionGUID`='$GUID' ");	
	$user_data1=mysql_fetch_array($res1);
	$group=$user_data1['GroupId'];
	if ($group == 1) {
	

	$FirstNameAcc=$user_data1['FirstName'];
	$LastNameAcc=$user_data1['LastName'];
	$id_user=$user_data1['id'];
	
		// раздел объявления об доступе к заданиям
	$activationemail=$user_data1['Activation'];
	
	$activlang=mysql_query("SELECT * FROM `user_tests` WHERE `userid`='$id_user' AND `testid`='1'");
	$activlang_data=mysql_fetch_array($activlang);
	$activationlang=$activlang_data['testid'];
	
	//раздел добавлении информации о сохранении работы
	
	if(isset($_POST['TextSave']))  {
	$FreelancerId=$id_user;
	$JobId=$id;
	$Note=htmlspecialchars($_POST['TextSave']);
	$RegisterSave="INSERT INTO `SavedJobs`(`FreelancerId`,`JobId`,`Note`) VALUES ('$FreelancerId','$JobId','$Note')";
	$ResultSave=mysql_query($RegisterSave);
	if($ResultSave == true)	{
		} else {
	echo "Error! ---->". mysql_error();
			}
	}
		//раздел удаления информации о сохранении работы
		
	if(isset($_POST['DeleteSaveHidden']))  {
	$DeleteSave=htmlspecialchars($_POST['DeleteSaveHidden']);	
	if ($DeleteSave == 1) {
	$DelSaveBd="DELETE FROM `SavedJobs` WHERE `FreelancerId`='$id_user' AND `JobId`='$id'";
	$DelSaveRes=mysql_query($DelSaveBd);
		}
	}
		
	// ---
	
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
if ($group == 2) {
$FirstNameAcc=$user_data1['FirstName'];
	$LastNameAcc=$user_data1['LastName'];


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
}
?>
<!--  ОКОНЧАНИЕ ПРОВЕРКИ ЧТО ЭТО ФРИЛАНСЕР    или РАБОТОДАТЕЛЬ     -->
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


<div class="FM_middle_center">

<!-- Дополнительные элементы для незарегистрированного пользователя -->
<?php
if(!isset($_SESSION['SessionId'])) {
?>

<div class="">
<div class="">
Кнопка "зарегистрироваться и сделать такое же задание"
</div>
<div class="">
Кнопка "зарегистрироваться и приступить к выполнению"
</div>
</div>
<?php
}
?>


<!-- Дополнительные элементы для фрилансера и работодателя -->
   <div class="">

<?php
// проверка на фрилансера
	if(isset($_SESSION['SessionId'])) {
	$GUID=$_SESSION['SessionId'];
	$rescheckfree=mysql_query("SELECT * FROM `users` WHERE `SessionGUID`='$GUID' ");	
	$user_datacheckfree=mysql_fetch_array($rescheckfree);
	$group=$user_datacheckfree['GroupId'];
	if ($group == 1) {
	
	// проверка на уже принятое к выполнению задание 
	
	$resapplyjob=mysql_query("SELECT * FROM `ResponseNewJob` WHERE `FreelancerId`='$id_user' AND `JobId`='$id' ");
	$user_datarespapplyjob=mysql_fetch_array($resapplyjob);
	$idapplyjob=$user_datarespapplyjob['id'];
	
	if(empty($idapplyjob)) {
	?>
<div class="">
<a href="freelance-workspace/apply-job.php?id=<?php echo $id; ?>">Перейти к принятию задания</a> 
</div>
	<?php
	}else {
?>
<div class="">
<p>Заявка на выполнения задания отправлена </p>
</div>	
<?php	
	}
?>



<!-- Раздел проверки на сохраненность работы в бд и подстановка нужных кнопок и текстов -->
<?php
 $resSave=mysql_query("SELECT * FROM `SavedJobs` WHERE `FreelancerId`='$id_user' AND `JobId`='$id' ");  
 $ResSave_data=mysql_fetch_array($resSave);
	$id_save=$ResSave_data['id'];
	$savenote=$ResSave_data['Note'];
 if(empty($id_save)){
 
 
 // вывод div для сохранения работы
 ?>
<div id="savediv1" class="">
Кнопка сохранить задание
<a id="showsave" href="#" class="">Сохранить задание</a>
</div>
<div id="savediv2" class="" class style="display: none">
Введите заметку о сохранении(по желанию)
<form name="SaveJob" id="SaveJob" class="" method="post">
<textarea id="TextSave" name="TextSave"></textarea>
<input type="submit" id="SaveSubmit" name="SaveSubmit" value="Сохранить">
</form>

<a id="hidesave" href="#" class="">отмена</a>
</div>

<?php


 // вывод див что работа сохранена
 } else {
 
 ?>
 <div class="">
Сохраненное задание
</div>
 <div class="">
<?php
echo "$savenote";
?>
</div>
<div class="">
<form name="DeleteSaveJob" id="DeleteSaveJob" class="" method="post">
<input type="hidden" id="DeleteSaveHidden" name="DeleteSaveHidden" value="1">
<input type="submit" id="DeleteSaveSubmit" name="DeleteSaveSubmit" value="Удалить сохраненное">
</form>
</div>

<?php
  }
 
?>

<?php 
   }
   
   // раздел проверки на работодателя
   	if ($group == 2) {
?>
	<div class="">
	<div class="">
	Кнопка сделать такое же задание
	</div>
	</div>	
<?php
}
}
?>
<!-------------------------------------------------->

<div class="">
<?php
echo $TitleJob;
?>
</div>

<div class="">
<?php
echo $DescriptionJob;
?>
</div>

<div class="">
<?php
echo $DataJob;
?>
</div>


</div>

</section>

<script type="text/javascript" src="jquery.min.js" charset="utf-8"></script>

<script>
$('#showsave').bind('click', function(){
  $('#savediv1').hide(); <!--скрываем div с кнопкой создать тест -->
  $('#savediv2').show(); <!--показывает div с формой создания теста -->
});
$('#hidesave').bind('click', function(){
  $('#savediv1').show(); <!--скрываем div с кнопкой создать тест -->
  $('#savediv2').hide(); <!--показывает div с формой создания теста -->
});
</script>

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