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
	$EmployerId=$user_data['id'];
	if ($group !=2) {
		header("location: erroronlyemployer.php");
	}
	$FirstName=$user_data['FirstName'];
	$LastName=$user_data['LastName'];	
	
	
	// раздел объявления об доступе к работе
	$activationemail=$user_data['Activation'];
	
	// раздел GET запрос принятие id и сверка его по правильности для данного пользователя
	
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	
	// нахождение данной работы
$ThatJobs=mysql_query("SELECT * FROM `jobs` WHERE `id`='$id' AND `EmployerId`='$EmployerId'	");	
$ThatJob_Data=mysql_fetch_array($ThatJobs);
If (empty($ThatJob_Data)) { 
		header("location: errorchoicejobemployer.php");   // если такой работы нет - ошибка
}

   // переменные с информацией о работе
$Jobid=$ThatJob_Data['id'];
$LanguageId=$ThatJob_Data['Language'];
$StyleId=$ThatJob_Data['Style'];
$Title=$ThatJob_Data['Title'];
$Description=$ThatJob_Data['Description'];
$TypeId=$ThatJob_Data['Type'];
$SubTypeId=$ThatJob_Data['SubType'];
$ValueId=$ThatJob_Data['Value'];
$SubValue=$ThatJob_Data['SubValue'];
$SearchId=$ThatJob_Data['Search'];
$TestJobId=$ThatJob_Data['TestJob'];
$DurationId=$ThatJob_Data['Duration'];
$Private=$ThatJob_Data['Private'];
$DataCreation=$ThatJob_Data['DataCreation'];
$Prepayment=$ThatJob_Data['Prepayment'];
$Price=$ThatJob_Data['Price'];

// вывод информации о работе по языку

$LanguageBd=mysql_query("SELECT * FROM `Language` WHERE `id`='$LanguageId'");	
$LanguageBd_data=mysql_fetch_array($LanguageBd);
$Language=$LanguageBd_data['Russian'];

// вывод информации о работе по продолжительности выполнения

$DurationBd=mysql_query("SELECT * FROM `DurationTranslation` WHERE `id`='$DurationId'");	
$DurationBd_data=mysql_fetch_array($DurationBd);
$Duration=$DurationBd_data['Russian'];
	
// вывод информации о работе по стилю

$StyleBd=mysql_query("SELECT * FROM `StyleTranslation` WHERE `id`='$StyleId'");	
$StyleBd_data=mysql_fetch_array($StyleBd);
$Style=$StyleBd_data['Russian'];	

// вывод информации о работе по типу

$TypeBd=mysql_query("SELECT * FROM `TypeTranslation` WHERE `id`='$TypeId'");	
$TypeBd_data=mysql_fetch_array($TypeBd);
$Type=$TypeBd_data['Russian'];
	
// вывод информации о работе по под-типу

$SubTypeBd=mysql_query("SELECT * FROM `SubTypeTranslation` WHERE `id`='$SubTypeId'");	
$SubTypeBd_data=mysql_fetch_array($SubTypeBd);
$SubType=$SubTypeBd_data['Russian'];

// вывод информации о работе по объему

$ValueBd=mysql_query("SELECT * FROM `ValueTranslation` WHERE `id`='$ValueId'");	
$ValueBd_data=mysql_fetch_array($ValueBd);
$Value=$ValueBd_data['Russian'];

// вывод информации о работе по виду поиска фрилансеров

$SearchBd=mysql_query("SELECT * FROM `SearchTranslation` WHERE `id`='$SearchId'");	
$SearchBd_data=mysql_fetch_array($SearchBd);
$Search=$SearchBd_data['Russian'];

// вывод информации о работе по виду поиска фрилансеров

$TestJobBd=mysql_query("SELECT * FROM `TestJob` WHERE `id`='$TestJobId'");	
$TestJobBd_data=mysql_fetch_array($TestJobBd);
$TestJob=$TestJobBd_data['Text'];
$TestJobDescription=$TestJobBd_data['Description'];
 }
?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<html>
<head>
<link href="/mysite/styles_index.css" rel="stylesheet" type="text/css" />
</head>

<body>
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
<a href="post-job.php" class="FW_top_nav_href">Сделать работу(?)</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="my-jobs.php" class="FW_top_nav_href">Контроль работ(?)</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="noname.php" class="FW_top_nav_href">Фриланс раздел(?)</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="finance.php" class="FW_top_nav_href">Финансовый раздел(?)</a>
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
<a href="my-jobs.php" class="FW_top_nav_href">Мои работы</a>
</li>
<li class="FW_top_subnav_ul">
<a href="noname.php" class="FW_top_nav_href">_____</a>
</li>
<li class="FW_top_subnav_ul">
<a href="noname.php" class="FW_top_nav_href">Архив работ</a>
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
<a class="" href="response.php?id=<?php echo $Jobid ?>">Заявки фрилансеров</a>
</li>
<li>
<a class="" href="contract.php?id=<?php echo $Jobid ?>">Конракт</a>
</li>
<li>
<a class="" href="parts.php?id=<?php echo $Jobid ?>">Прогресс выполнения работы</a>
</li>
<li>
<a class="" href="noname.php">______</a>
</li>
</ul>
</nav>

<div class="FM_middle_center">

 <header>
 <h2> Прогресс выполнения работы </h2>
 <br>
 </header>


<div class="">
<br>
<p>Раздел краткой информации о работе </p>
<br>

<div class="FW_middle_Read_only">
<label class="Label">Название задания:</label>
<div class="">
<?php
echo $Title;
?>
</div>
</div>


<div class="FW_middle_Read_only">
<label class="Label">Дата создания:</label>
<div class="">
<?php
echo $DataCreation;
?>
</div>
</div>

<div class="FW_middle_Read_only">
<label class="Label">Язык перевода:</label>
<div class="">
<?php
echo $Language;
?>
</div>
</div>

<div class="FW_middle_Read_only">
<label class="Label">Тип перевода:</label>
<div class="">
<?php
echo "$Type  ($SubType)"; 
?>
</div>
</div>

</div>


<div class="">
Части работы
Общий статус выполненных работ (число выполненных частей)
Цена каждой части: ___;


<div class="">
Вывод кол-ва частей в виде |1|2|3|...|10|
</div>

каждый див через если пхп можно открыть часть как вкладку. По умолчанию открыта первая часть

</div>

</div>





</section>



<script type="text/javascript" src="jquery-1.5.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="jquery.min.js" charset="utf-8"></script>





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