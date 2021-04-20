<?php
/* Старт Сессии */ 
session_start();

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

	if ($User_Information = $BD->query("SELECT * FROM `users` WHERE `SessionGUID`='$GUID' ")) { 

    /* Выбираем результаты запроса по $User_Information : */ 
$User_Information_Data = $User_Information->fetch_assoc();

// Переменная "Группы" текущего пользователя
    $Group_Id=$User_Information_Data['GroupId'];
	
	// Если по GUID оказывается, что на страницу пытается зайти пользователь, который относится к одной из групп, отправлять его на его главную страницу
		if ($Group_Id == 1) {
		header("location: freelance-workspace/index.php");
		}elseif ($Group_Id ==2) {
	 	header("location: employer-workspace/index.php");
		}
/* Освобождаем память $User_Information */ 
$User_Information->close(); 
}	

/* Закрываем соединение */ 
$BD->close(); 	

}
?>

<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>

<head>

<!--название страницы-->
<title>index</title>
<!-- -->

<!-- стили оформления страницы-->
<link href="styles_index.css" rel="stylesheet" type="text/css" />
<!-- -->

</head>



<body class="Main_Body">
 <div  id="Main_Section">
 
<!-- header - верхн¤¤ часть - маленький банер с ссылками входа\регистрации и навигацией -->
<header class="GlobalHeader">


<div class="header_1">

<div class style="  width: 960px;
  position: relative;
  margin: 0 auto;">
<a class="FW_logo" href="" rel="home"></a>
</div>

<div class="header_1_columns">
<nav class="GH_links">
<ul class="site_links">
<li>
<a href="freelancers.php" class="GH_sl_link">Поиск фрилансеров</a>
</li>
<li>
<a href="nopage.php" class="GH_sl_link">Поиск работы</a>
</li>
<li>
<a href="nopage.php" class="GH_sl_link">Ссылка на что то 3</a>
</li>
</ul>
<ul class="user_links">
<li>
<a href="login.php" class="GH_h1_login">Вход</a>
</li>
<li>
<a href="signup-type.php" class="GH_h1_signup">Регистрация</a>
</li>
</ul>
</nav>
</div>
</div>
</header>

<!-- section 1 (up) - секци¤ 1 в которой на фоне картинки (?) 2 кнопки, надписи и полупрозрачна¤ полоска снизу -->
<section class="IndexUp">

<!-- div слоган каждый вложенный div определяет более точное положение текста. Данный div определяет положение общего элемента -->
<div class="IndexUp_first">
<!-- этот div обозначает откуда будет начинаться блок с текстом -->
<div class="IU_f-columns">
<!-- Этот div определяет откуда будет начинаться текст -->
<div class="IU_f-text">
<h1 class="IU_f-text_1"> Freelance Translate</h1>
<br>
<h2 class="IU_f-text_2"> Фриланс-сервис для переводов </h2>
</div>
</div>

</div>
<!-- div приветственная подпись -->
<div class="IndexUp_second">
<div class="IU_s-columns">
<div class="IU_s-text">
Добро пожаловать на рабочую площадку, где занимаюстя переводами
</div>
</div>
</div>
<!-- div 2 кнопки - Присоедениться \ Как это работает -->
<div class="IndexUp_third">
<div class="IU_t-columns">
<div class="IU_t-bottom">
<a href="nopage.php" class="IU_t-bottom_1">Присоединяйтесь</a>
<a href="nopage.php" class="IU_t-bottom_2">О проекте</a>
</div>
</div>
</div>
</section>

<hr>
<!-- section 2 (up) - секци¤ 2 -->
<section class="IndexDown">

<!--  -->
<div class="">


</div>
<!-- div  -->
<div class="IndexUp_second">
<div class="IU_s-columns">
<div class="IU_s-text">
Дополнительный текст снизу
</div>
</div>
</div>
<!-- div 2 кнопки - Присоедениться \ Как это работает -->
<div class="IndexUp_third">
<div class="IU_t-columns">
<div class="IU_t-bottom">
<a href="nopage.php" class="IU_t-bottom_1">доп кнопка 1</a>
<a href="nopage.php" class="IU_t-bottom_2">доп кнопка 2</a>
</div>
</div>
</div>

</section>
</div>


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
<a href="/freelancers.php">Поиск Фрилансеров</a>
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

</body>
</html>