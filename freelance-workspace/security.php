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
	$password=$user_data['Password'];
	$id_user=$user_data['id'];
	if ($group !=1) {
	echo $group;
		header("location: erroronlyfreelancer.php");
	}
	$FirstName=$user_data['FirstName'];
	$LastName=$user_data['LastName'];	

	$activationemail=$user_data['Activation'];
	$activlang=mysql_query("SELECT * FROM `user_tests` WHERE `userid`='$id_user' AND `testid`='1'");
	$activlang_data=mysql_fetch_array($activlang);
	$activationlang=$activlang_data['testid'];	

	
	if(isset($_POST['PasswordOld']) && isset($_POST['PasswordNew']) && isset($_POST['PasswordNewCheck'])) {	
	$salt = '$2a$10$'.substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(),mt_rand()))), 0, 22) . '$';
	$PasswordOld=htmlspecialchars(trim($_POST['PasswordOld']));
	$PasswordNew=htmlspecialchars(trim($_POST['PasswordNew']));
	$PasswordNewCheck=htmlspecialchars(trim($_POST['PasswordNewCheck']));
	$hashed_passwordOld = crypt($PasswordOld, $salt);
	if ($hashed_passwordOld == $password) {
	if ($PasswordNew == $PasswordNewCheck ) {
		$hashed_passworNew = crypt($PasswordNew, $salt);
$Register="UPDATE `users` SET `Password`='$hashed_passworNew' WHERE `SessionGUID`='$GUID' ";
$result=mysql_query($Register);

if($result==true)
{
	header("location: security.php");
}
else
{
echo "Error! ---->". mysql_error();
}
	
	
	} else {
	echo "новые пароли не сошлись";
	}
	} else { 
	echo "неверный старый пароль";
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
<a href="profile.php" class="FW_top_nav_href">Личный раздел</a>
</li>
<li class="FW_top_subnav_ul">
<a href="resume.php" class="FW_top_nav_href">Резюме</a>
</li>
<li class="FW_top_subnav_ul">
<a href="finance.php" class="FW_top_nav_href">Финансовые данные</a>
</li>
<li class="FW_top_subnav_ul">
<a href="tests.php" class="FW_top_nav_href">Тесты</a>
</li>
<li class="FW_top_subnav_ul">
<a href="security.php" class="FW_top_nav_href">Безопастность</a>
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



<Section class="FW_middle">
<nav class="FM_middle_nav">
<h1 class=""> Меню </h1>
<ul class="">
<li>
<a class="" href="security.php">Общее</a>
</li>
<li>
<a class="" href="change-password.php">Смена пароля</a>
</li>
<li>
<a class="" href="noname">Секретный вопрос</a>
</li>
<li>
<a class="" href="noname">Блокировать аккаунт</a>
</li>
<li>
<a class="" href="noname">noname</a>
</li>
</ul>
</nav>

<div class="FM_middle_center">
В данной части вашего личного кабинета вы можете изменить пароль, поставить секретный вопрос для восстановления пароля,
а так же заблокировать Ваш аккаунт, если вы им не будете пользоваться.
</div>
</section>

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