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



/* раздел вывода информации об заданиях в цикле с сортировкой */
	
$Selection = 1;
$Selection = htmlspecialchars(trim( isset($_GET['sl']) ? (int) $_GET['sl'] : 0));	// GET запрос принятие для сортировки
	
	// Виды сортировки работы
if($Selection == 1){
	$Jobs_Information = $BD->query("SELECT * FROM `Jobs` WHERE `EmployerId`='$User_Id' AND `Closed`='0' ORDER BY `Title` ");				// Сортировка по Названию
}elseif ($Selection == 2){
	$Jobs_Information = $BD->query("SELECT * FROM `Jobs` WHERE `EmployerId`='$User_Id' AND `Closed`='0' ORDER BY `DataCreation` DESC");		// Сортировка по времени создания
}else{
	$Jobs_Information = $BD->query("SELECT * FROM `Jobs` WHERE `EmployerId`='$User_Id' AND `Closed`='0' ORDER BY `Title`");				// Сортировка по Названию
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








<div class="">
<h1>
Опубликованные работы
</h1>
</div>





<!-- Варианты сортировки -->

<div  class="EW_MyJobs_sort">

Сортировать: 
<select onchange="location.href = '?sl='+this.value;">
<option <?php if($Selection == 1){ ?> selected="selected"  <?php } ?> value="1" label="По названию">По названию</option>
<option <?php if($Selection == 2){ ?> selected="selected"  <?php } ?> value="2" label="По дате публикации">По дате публикации</option>
</select>

<input type="text" Class="Search_Input" placeholder="Поиск по названию проекта">

</div>











<!-- Таблица работ   cellspacing="0" border="0" cellpadding="1"-->
<table class="EW_MyJobs_Table" >
<thead>
<tr>
<th>Языки</th>
<th>Дата публикации</th>
<th>Название</th>
<th>Статус</th>
</tr>
</thead>
<tbody>

<?php
while($Jobs_Information_Data = $Jobs_Information->fetch_assoc()) {

$post_id = $Jobs_Information_Data['id'];
$TitleJob = $Jobs_Information_Data['Title'];
$DataCreation=$Jobs_Information_Data['DataCreation'];
// вывод информации 
$LanguageFrom1=$Jobs_Information_Data['LanguageFrom1'];
$LanguageTo1=$Jobs_Information_Data['LanguageTo1'];
$LanguageFrom2=$Jobs_Information_Data['LanguageFrom2'];
$LanguageTo2=$Jobs_Information_Data['LanguageTo2'];
$LanguageFrom3=$Jobs_Information_Data['LanguageFrom3'];
$LanguageTo3=$Jobs_Information_Data['LanguageTo3'];
$LanguageFrom4=$Jobs_Information_Data['LanguageFrom4'];
$LanguageTo4=$Jobs_Information_Data['LanguageTo4'];
$LanguageFrom5=$Jobs_Information_Data['LanguageFrom5'];
$LanguageTo5=$Jobs_Information_Data['LanguageTo5'];

// вывод информации о языках для определения url картинки
if($Language_From_Information = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageFrom1'")){
$Language_From_Information_Data = $Language_From_Information->fetch_assoc();
$LanguageFromImg=$Language_From_Information_Data['Flag'];
$Language_From_Information->close(); 
}
if($Language_To_Information = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageTo1'")){
$Language_To_Information_Data = $Language_To_Information->fetch_assoc();
$LanguageToImg=$Language_To_Information_Data['Flag'];
$Language_To_Information->close(); 
}



// вывод информации о заявках

if($About_Response_Information = $BD->query("SELECT * FROM `ResponseNewJob` WHERE `JobId`='$post_id' AND `Result`='2' ")){
$About_Response_Information_Data = $About_Response_Information->fetch_assoc();
$ResponseResult=$About_Response_Information_Data['FreelancerId'];
}

//подсчет кол-ва заявок
if($Response_Count_Information= $BD->query("SELECT * FROM `ResponseNewJob` WHERE `JobId`='$post_id' ")){
	//определение общее кол-во заявок -->
$count = 0;
while( $Response_Count_Information_Data=$Response_Count_Information->fetch_assoc()) {
$count = $count + 1;
		}
}

// вывод информации о контрактах - о наличии формировании контракта

if($About_Contract_Information = $BD->query("SELECT * FROM `Contracts` WHERE `JobId`='$post_id' ")){
$Contract_Result_Data = $About_Contract_Information->fetch_assoc();
$ContractResultId=$Contract_Result_Data['id'];
$ContractBan=$Contract_Result_Data['Ban'];
}

// вывод информации о контрактах - о наличии активного контракта

if($Active_Contract_Information = $BD->query("SELECT * FROM `Contracts` WHERE `JobId`='$post_id' AND `Active`='1' ")){
$Active_Contract_Result_Data = $Active_Contract_Information->fetch_assoc();
}

?>



<!--         НАЧАЛО ФОРМИРОВАНИЯ СТРОКИ          -->

<!-- строка как ссылка -->
<tr onclick="window.location.href='job.php?id=<?php echo $post_id ?>'; return false" class="EW_MyJobs_tr" >

<td class="EW_MyJobs_td_0">
 <img src="<?php echo $LanguageFromImg; ?>" alt> -> <img src="<?php echo $LanguageToImg; ?>" alt>
 
 
<?php
// Раздел, где по if наличия языка генерируются строки флагов
if($LanguageFrom2 != 0 && $LanguageTo2 != 0) {
if($Language_From_Information2 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageFrom2'")){
$Language_From_Information_Data2 = $Language_From_Information2->fetch_assoc();
$LanguageFromImg2=$Language_From_Information_Data2['Flag'];
$Language_From_Information2->close(); 
}
if($Language_To_Information2 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageTo2'")){
$Language_To_Information_Data2 = $Language_To_Information2->fetch_assoc();
$LanguageToImg2=$Language_To_Information_Data2['Flag'];
$Language_To_Information2->close(); 
}
?>
<br>
<img src="<?php echo $LanguageFromImg2; ?>" alt> -> <img src="<?php echo $LanguageToImg2; ?>" alt>
<?php
}
?>

<?php
// Раздел, где по if наличия языка генерируются строки флагов
if($LanguageFrom3 != 0 && $LanguageTo3 != 0) {
if($Language_From_Information3 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageFrom3'")){
$Language_From_Information_Data3 = $Language_From_Information3->fetch_assoc();
$LanguageFromImg3=$Language_From_Information_Data3['Flag'];
$Language_From_Information3->close(); 
}
if($Language_To_Information3 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageTo3'")){
$Language_To_Information_Data3 = $Language_To_Information3->fetch_assoc();
$LanguageToImg3=$Language_To_Information_Data1['Flag'];
$Language_To_Information3->close(); 
}
?>
<br>
<img src="<?php echo $LanguageFromImg3; ?>" alt> -> <img src="<?php echo $LanguageToImg3; ?>" alt>
<?php
}
?>

<?php
// Раздел, где по if наличия языка генерируются строки флагов
if($LanguageFrom4 != 0 && $LanguageTo4 != 0) {
if($Language_From_Information4 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageFrom4'")){
$Language_From_Information_Data4 = $Language_From_Information4->fetch_assoc();
$LanguageFromImg4=$Language_From_Information_Data4['Flag'];
$Language_From_Information4->close(); 
}
if($Language_To_Information4 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageTo4'")){
$Language_To_Information_Data4 = $Language_To_Information4->fetch_assoc();
$LanguageToImg4=$Language_To_Information_Data4['Flag'];
$Language_To_Information4->close(); 
}
?>
<br>
<img src="<?php echo $LanguageFromImg4; ?>" alt> -> <img src="<?php echo $LanguageToImg4; ?>" alt>
<?php
}
?>

<?php
// Раздел, где по if наличия языка генерируются строки флагов 4
if($LanguageFrom5 != 0 && $LanguageTo5 != 0) { 
if($Language_From_Information5 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageFrom5'")){
$Language_From_Information_Data5 = $Language_From_Information5->fetch_assoc();
$LanguageFromImg5=$Language_From_Information_Data5['Flag'];
$Language_From_Information5->close(); 
}
if($Language_To_Information5 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageTo5'")){
$Language_To_Information_Data5 = $Language_To_Information5->fetch_assoc();
$LanguageToImg5=$Language_To_Information_Data5['Flag'];
$Language_To_Information5->close(); 
}
?>
<br>
<img src="<?php echo $LanguageFromImg5; ?>" alt> -> <img src="<?php echo $LanguageToImg5; ?>" alt>
<?php
}
?>


</td>


<td> <?php echo $DataCreation; ?>  </td>


<td> <?php echo $TitleJob; ?> </td>


<td class="EW_MyJobs_td_1">
	<p>Всего заявок: <?php echo $count ?> </p>
<?php

// 1) Если нет контракта и не подписана заявка указать что заявка не выбрана

if(empty($About_Response_Information_Data)) {
?>
   
	<p>Нет выбранной заявки</p>
<?php
} else {
?>
	<p>Принята заявка фрилансера: <a href="/mysite/freelancers.php?id=<?php echo $ResponseResult; ?>">(Профиль фрилансера)</a>  </p>
<?php
}

if(!empty($ResponseResult) && empty($Contract_Result_Data)) {
?>
	<p>Контракт не создан </p>
<?php
}elseif(!empty($Active_Contract_Result_Data)) {
?>
	<p>Контракт подписан: <a href="contract.php?id=<?php echo $ContractResultId; ?>">(Перейти)</a> </p>
<?php
}  elseif(!empty($Contract_Result_Data)) {
?>
	<p>Контракт формируется <a href="contract.php?id=<?php echo $ContractResultId; ?>">(Перейти)</a> </p>
<?php


} 
// вывод информации о том что контракт заблокирован ban = 1
if( $ContractBan == 1) {
?>

	<p>Работа заблокированна</p>
	
<?php 
}

?> </td>

</tr>

<?php } ?>
</tbody>

</table>


</section>





<!--  -================================================================================================ -->
<!--  -================================================================================================ -->
<!--  -================================================================================================ -->
<!--  -====================    вариации таблицы  ====================================================== -->
<!--  -================================================================================================ -->
<!--  -================================================================================================ -->

<section class="FW_middle">








<div class="">
<h1>
Опубликованные работы
</h1>
</div>





<!-- Варианты сортировки -->

<div  class="EW_MyJobs_sort">

Сортировать: 
<select onchange="location.href = '?sl='+this.value;">
<option <?php if($Selection == 1){ ?> selected="selected"  <?php } ?> value="1" label="По названию">По названию</option>
<option <?php if($Selection == 2){ ?> selected="selected"  <?php } ?> value="2" label="По дате публикации">По дате публикации</option>
</select>

<input type="text" Class="Search_Input" placeholder="Поиск по названию проекта">

</div>
<!-- Таблица работ   cellspacing="0" border="0" cellpadding="1"-->
<table class="EW_MyJobs_Table" Style="border-collapse: collapse; /* Убираем двойные линии между ячейками */">
<thead>
<tr>
<th>Дата публикации</th>
<th>Название</th>
<th>Статус</th>

</tr>
</thead>
<tbody>
<!--         НАЧАЛО ФОРМИРОВАНИЯ СТРОКИ          -->

<!-- строка как ссылка -->
<tr onclick="window.location.href='job.php?id=<?php echo $post_id ?>'; return false" class="EW_MyJobs_tr" style="background: url(/mysite/img/flagss.png) repeat-y; width: 100%; border-spacing: 0; width: 230px;
    height: 51px;
    padding: 0;
    top: 5px;
    /* left: -15px; */
    text-indent: 100%;
    white-space: nowrap;
    overflow: hidden;
    z-index: 1;
    zoom: 0.75;" >


<td> <?php echo "123"; ?>  </td>


<td> <?php echo "123"; ?> </td>


<td> <?php echo "123"; ?> </td>


</tr>


</tbody>

</table>


</section>




<!--  -================================================================================================ -->
<!--  -================================================================================================ -->
<!--  -================================================================================================ -->
<!--  -====================    вариации таблицы  ====================================================== -->
<!--  -================================================================================================ -->
<!--  -================================================================================================ -->














<!--  -================================================================================================ -->
<!--  -================================================================================================ -->
<!--  -================================================================================================ -->
<!--  -====================    вариации таблицы  ====================================================== -->
<!--  -================================================================================================ -->
<!--  -================================================================================================ -->





</div>
</body>


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