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
	
	
	// выборка для раздела profile	
	$FirstName=$User_Information_Data['FirstName'];
	$LastName=$User_Information_Data['LastName'];	
	$Email=$User_Information_Data['Email'];	
	$Username=$User_Information_Data['Username'];
	
// выборка для раздела locations
 	$Country=$User_Information_Data['Country'];
	$City=$User_Information_Data['City'];		
	$Address=$User_Information_Data['Address'];
	$Zip_code=$User_Information_Data['Zip-code'];	
	$Phone=$User_Information_Data['Phone'];		
	$Time_zone=$User_Information_Data['Time-zone'];	


	
// Переменная "Активация Почтового Ящика" текущего пользователя
	$Activation_Email=$User_Information_Data['Activation'];
	
/* Освобождаем память $User_Information */ 
$User_Information->close(); 
}	













//-------------------------НЕ ОТРЕДАКТИРОВАННО!!!!!!!!!!!!------------------------------------


// Обновление данных о профиле
if(isset($_POST['FirstNameNew']) && isset($_POST['LastNameNew']) && isset($_POST['EmailNew'])) {	

$FirstNameNew=htmlspecialchars(trim($_POST['FirstNameNew']));
$LastNameNew=htmlspecialchars(trim($_POST['LastNameNew']));
$EmailNew=htmlspecialchars(trim($_POST['EmailNew']));

// проверка на совпадения почты с бд кроме уже заданной
 $resCheckEmail=mysql_query("SELECT `Email` FROM `users` WHERE `Email`='$EmailNew'");
  $dataCheckEmail=mysql_fetch_array($resCheckEmail);       
   if(!empty($dataCheckEmail['Email']))	{
	if ($dataCheckEmail['Email'] == $Email) { 
	  } else {
		die("ОШИБКА РЕГИСТРАЦИИ ПОЧТА ЗАНЯТА!");
}
	} 

$Register="UPDATE `users` SET `FirstName`='$FirstNameNew', `LastName`='$LastNameNew',`Email`='$EmailNew' WHERE `SessionGUID`='$GUID' ";
$result=mysql_query($Register);

if($result==true)
{
	header("location: profile.php");
}
else
{
echo "Error! ---->". mysql_error();
}	
}

// Обновление данных о месторасположении
if(isset($_POST['CountryNew']) && isset($_POST['CityNew']) && isset($_POST['AddressNew']) && isset($_POST['Zip-codeNew']) && isset($_POST['PhoneNew']) && isset($_POST['Time-zoneNew'])) {

$CountryNew=htmlspecialchars(trim($_POST['CountryNew']));
$CityNew=htmlspecialchars(trim($_POST['CityNew']));
$AddressNew=htmlspecialchars(trim($_POST['AddressNew']));
$Zip_codeNew=htmlspecialchars(trim($_POST['Zip-codeNew']));
$PhoneNew=htmlspecialchars(trim($_POST['PhoneNew']));
$Time_zoneNew=htmlspecialchars(trim($_POST['Time-zoneNew']));

$RegisterLocation="UPDATE `users` SET `Country`='$CountryNew', `City`='$CityNew',`Address`='$AddressNew',`Zip-code`='$Zip_codeNew',`Phone`='$PhoneNew',`Time-zone`='$Time_zoneNew' WHERE `SessionGUID`='$GUID' ";
$resultLocation=mysql_query($RegisterLocation);

if($resultLocation==true)
{
	header("location: profile.php");

}
else
{
echo "Error! ---->". mysql_error();
}
}


	// раздел объявления об доступе к заданиям
	//$activationemail=$user_data['Activation'];
	//
	//$activlang=mysql_query("SELECT * FROM `user_tests` WHERE `userid`='$id_user' AND `testid`='1'");
	//$activlang_data=mysql_fetch_array($activlang);
	//$activationlang=$activlang_data['testid'];
	

	//-----------------------------------------------------------------------
	
	
	
	
?>

<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<html>
<head>
<link href="/mysite/styles_index.css" rel="stylesheet" type="text/css" />



<script>
function swap(profile_show, prodile_edit) {
    document.getElementById(profile_show).style.display = 'block';
    document.getElementById(profile_edit).style.display = 'none';
}

function swap(location_show, location_edit) {
    document.getElementById(location_show).style.display = 'block';
    document.getElementById(location_edit).style.display = 'none';
}
 </script>
 
 
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
<a href="noname.php" class="FW_top_nav_2_href">_____</a>
</li>
<li class="FW_top_subnav_ul_current">
<a href="noname.php" class="FW_top_nav_2_href">______</a>
</li>
<li class="FW_top_subnav_ul">
<a href="noname.php" class="FW_top_nav_2_href">_______</a>
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
<h1 class=""> Меню </h1>
<ul class="">
<li>
<a class="" href="profile.php">Личные данные</a>
</li>
<li>
<a class="" href="language.php">Знание языка</a>
</li>
<li>
<a class="" href="noname">Noname</a>
</li>
</ul>
</nav>

<div class="FM_middle_center">
<section class="FW_middle_change_profile">

 <div id="profile_show">
 <header>
 <h2> Личные данные </h2>
 <button onClick="swap('profile_edit','profile_show')">Редактировать</button>
 </header>

<form>

<div class="FW_middle_Read_only">
<label class="Label">Username (Логин)</label>
<div class="">
<?php
echo $Username;
?>
</div>
</div>

<div class="FW_middle_Read_only">
<label class="Label">First Name (Имя)</label>
<div class="">
<?php
echo $FirstName;
?>
</div>
</div>

<div class="FW_middle_Read_only">
<label class="Label">Last Name (Фамилия)</label>
<div class="">
<?php
echo $LastName;
?>
</div>
</div>

<div class="FW_middle_Read_only">
<label class="Label">Photo (Фото)</label>
<div class="">
<?php
echo "тут фото";
?>
</div>
</div>

<div class="FW_middle_Read_only">
<label class="Label">Email (Почтовый адрес)</label>
<div class="">
<?php
echo $Email;
?>
</div>
</div>

</form>

</div>

 <div id="profile_edit" style="display:none">

<form method="post" name="profile" id="profile" novalidate="novalidate">

<div class="" >
<h2>
Личные данные 
</h2>
</div>

<div class="" >
логин - 
<?php
echo "username";
?>
</div>

<div class="" >
<div class="">
Имя
</div>
<div class="">
<input type="text" name="FirstNameNew" id="FirstNameNew" Value="<?php echo $FirstName;  ?>"  class="">
</div>
</div>

<div class="" >
<div class="">
Фамилия
</div>
<div class="">
<input type="text" name="LastNameNew" id="LastNameNew" Value="<?php echo $LastName; ?>" class="">
</div>
</div>

<div class="" >
фото портер 
</div>

<div class="" >
<div class="">
Почта
</div>
<div class="">
<input type="text" name="EmailNew" id="EmailNew" Value="<?php echo $Email; ?>" class="">
</div>
</div>

<div class="">
<button type="submit"  id="send" class="Signup_submit">Save change</button>
  <!-- эта кнопка должна быть в двух - "применить изменения" и "отменить" -->
    <button onClick="swap('profile_show','profile_edit')">Отмена</button>
</div>
 
 </form>
 </div>

 </section>
 
<section class="FW_middle_change_location">

<div id="location_show">

 <header>
 <h2> Данные о местаросположении </h2>
<button onClick="swap('location_edit','location_show')">Редактировать</button>
 </header>

 <form>

<div class="FW_middle_Read_only">
<label class="Label">Country (Страна)</label>
<div class="">
<?php
echo $Country;
?>
</div>
</div>

<div class="FW_middle_Read_only">
<label class="Label">City (Город)</label>
<div class="">
<?php
echo $City;
?>
</div>
</div>

<div class="FW_middle_Read_only">
<label class="Label">Address (адрес)</label>
<div class="">
<?php
echo $Address;
?>
</div>
</div>

<div class="FW_middle_Read_only">
<label class="Label">Zip-code (почтовый индекс)</label>
<div class="">
<?php
echo $Zip_code;
?>
</div>
</div>

<div class="FW_middle_Read_only">
<label class="Label">Phone (телефонный номер)</label>
<div class="">
<?php
echo $Phone;
?>
</div>
</div>

<div class="FW_middle_Read_only">
<label class="Label">Time zone (Временная зона)</label>
<div class="">
<?php
echo $Time_zone;
?>
</div>
</div>

</form>

</div>

<div id="location_edit" style="display:none">

<form method="post" name="location" id="location" novalidate="novalidate">
<div class="" >
<h2>
Location
</h2>
</div>

<div class="" >
<div class="">
Страна
</div>
<div class="">
<input type="text" name="CountryNew" id="CountryNew" Value="<?php echo $Country;  ?>"  class="">
</div>
</div>

<div class="" >
<div class="">
Город
</div>
<div class="">
<input type="text" name="CityNew" id="CityNew" Value="<?php echo $City;  ?>"  class="">
</div>
</div>

<div class="" >
<div class="">
Адрес
</div>
<div class="">
<input type="text" name="AddressNew" id="AddressNew" Value="<?php echo $Address  ?>"  class="">
</div>
</div>

<div class="" >
<div class="">
Zip-code
</div>
<div class="">
<input type="text" name="Zip-codeNew" id="Zip-codeNew" Value="<?php echo $Zip_code;  ?>"  class="">
</div>
</div>

<div class="" >
<div class="">
Phont number
</div>
<div class="">
<input type="text" name="PhoneNew" id="PhoneNew" Value="<?php echo $Phone;  ?>"  class="">
</div>
</div>

<div class="" >
<div class="">
Time-zone
</div>
<div class="">
<input type="text" name="Time-zoneNew" id="Time-zoneNew" Value="<?php echo $Time_zone;  ?>"  class="">
</div>
</div>

<div class="">
<button type="submit"  id="sendloc" class="Signup_submit">Save change</button>
  <!-- эта кнопка должна быть в двух - "применить изменения" и "отменить" -->
 <button onClick="swap('location_show','location_edit')">Отмена</button> 
</div>

 </form>

</div>

</section>
</div>
</section>



<script type="text/javascript" src="jquery-1.5.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="valid_f_profile.js" charset="utf-8"></script>




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