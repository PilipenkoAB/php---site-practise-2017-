<?php
/* Старт Сессии */ 
session_start();

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

/* Проверка на наличие Сессии у текущего пользователя */ 
if(isset($_SESSION['SessionId'])) {

/* Задание переменной для определения GUID из сессии */
    $GUID=$_SESSION['SessionId'];
	
/* Посылаем запрос серверу на выборку информации о текущем пользователе */	
if ($User_Information = $BD->query("SELECT * FROM `users` WHERE `SessionGUID`='$GUID' ")) { 

    /* Выбираем результаты запроса по $User_Information : */ 
$User_Information_Data = $User_Information->fetch_assoc();

// Переменная "Группы" текущего пользователя
    $Group_Id=$User_Information_Data['GroupId'];
	
// Переменные "id", "Имя", "Фамилия"  текущего пользователя
	$User_Id=$User_Information_Data['id'];
	$First_Name=$User_Information_Data['FirstName'];
	$Last_Name=$User_Information_Data['LastName'];	

/* Освобождаем память $User_Information */ 
$User_Information->close(); 
		}
}	

?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js" charset="utf-8"></script> <!-- Библиотека для jqiery -->
<html>
<head>
<link href="/mysite/styles_index.css" rel="stylesheet" type="text/css" />		<!-- css -->
</head>
<body>         
<?php
if(isset($_SESSION['SessionId'])) {
?>
<!-- Панель возврата к рабочему кабинету -->
<header  class="OutHeader">
<nav class="OutTopNav">
<div class="logo">
<a href="">
<img src="img/logo.png" width="90" height="45">
</a>
</div>
<ul  class="FW_top_nav_ul">
<li class="FW_top_nav_ul_noncabinet_index">
<a href="index.php" class="FW_top_nav_href">Рабочий кабинет</a>
</li>
</ul>
<div class="FW_top_nav_username">
<div class="FW_top_user_name">
<?php
echo "$First_Name ";
echo " $Last_Name";
?>
</div>
<div class="FW_top_user_logout">
<a href="/mysite/logout.php" class="FW_top_nav_href">Выход</a>
</div>
</div>
</nav>
<div class="Header_line">
</div>
</header>
<?
}
// ------------------------ что наверху, если нет пользователя --------------------- //
if(!isset($_SESSION['SessionId'])) {	
?>
<header class="GlobalHeader">
<div class="header_1">
<div class="header_1_columns">
<div class="logo">
<a href="/">
<img src="img/logo.png" width="90" height="65">
</a>
</div>
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

<!-- САМ РАЗДЕЛ КАК ЭТО РАБОТАЕТ -->

<!-- верхняя панель с выбором как это работает для заказчика или работодателя -->
<header class="HIW_header">
<div class="HIW_container">
<h1> Как это работает для </h1>
</div>        

<ul class="HIW_ul">
<li class="HIW_li">
<a href="#Employer" id="showemployer" class="HIW_li_a">Заказчика</a>
</li>
<li class="HIW_li">
<a href="#Freelancer" id="showfreelance" class="HIW_li_a">Фрилансера</a>
</li>
</ul>
</header>
<div class="HIW_arrow ">
</div>



<section class="HIW_section">
<!-- под верхней панелью, если выбранно ЗАКАЗЧИК-->	
<div class="" id="employer_part">


<!-- элементы с иконкой слева\справа\слева-->	
 <div class="HIW_element_l">
		        <img src="img/hiw_1_e.png" class="HIW_element_img_l"/>
		        <div class="HIW_element_text">
                    <h3> Проект</h3>
		            <p>
Создавайте работу, подходящую вашим требованиям и приступайте к найму </p>
                </div>
 </div>

  <div class="HIW_element_r">
		        <img src="img/hiw_2_e.png" class="HIW_element_img_r"/>
		        <div class="HIW_element_text">
                    <h3> Найм</h3>
		            <p>
Просматривайте кандидатуры на вашу работу, знакомьтесь с резюме фрилансера, его рейтингом, результатами проверочных тестов и приступайте к найму
					</p>
                </div>
 </div>
 
  <div class="HIW_element_l">
		        <img src="img/hiw_3_e.png" class="HIW_element_img_l"/>
		        <div class="HIW_element_text">
                    <h3> Оплата</h3>
		            <p>
Резервируйте сумму проекта, но оплачивайте только выполненную работу. Коммисия от сервиса составляет 5% для заказчика и 5% дня фрилансера.
					</p>
                </div>
 </div>

   <div class="HIW_element_r">
		        <img src="img/hiw_4_e.png" class="HIW_element_img_r"/>
		        <div class="HIW_element_text">
                    <h3> Защита</h3>
		            <p>
Вы не потеряете свои деньги, если работа не была выполнена. А столкнувшись с попыткой обмана, на помощь придёт администрация
					</p>
                </div>
 </div>
 
 <!-- схематичная картинка-->	
 <div class="HIW_img_div">
 		        <img src="img/hiw.png" class="HIW_img" />
 </div>

  <!-- Раздел с вопросами-->	
 <div>
  <h1>Вопросы</h1>
 <ul>
<li>
<a href="#Q_1e" id="Show_Q_1e">Вопрос 1</a>
<div class="" id="question_1e" class style="display: none">
Ответ на вопрос 1 
</div>
</li>
<li>
<a href="#Q_2e" id="Show_Q_2e">Вопрос 2</a>
<div class="" id="question_2e"  class style="display: none">
Ответ на вопрос 2
</div>
</li>
<li>
<a href="#Q_3e" id="Show_Q_3e">Вопрос 3</a>
<div class="" id="question_3e"  class style="display: none">
Ответ на вопрос 3
</div>
</li>
<li>
<a href="#Q_4e" id="Show_Q_4e">Вопрос 4</a>
<div class="" id="question_4e"  class style="display: none">
Ответ на вопрос 4
</div>
</li>
<li>
<a href="#Q_5e" id="Show_Q_5e">Вопрос 5</a>
<div class="" id="question_5e"  class style="display: none">
Ответ на вопрос 5
</div>
</li>
</ul>
 </div>
 
 
</div>






<!-- под верхней панелью, если выбранно ФРИЛАНСЕР-->	
<div id="freelance_part" class="" class style="display: none">

<!-- элементы с иконкой слева\справа\слева-->	
 <div class="HIW_element_l">
		        <img src="img/hiw.png" class="HIW_element_img_l"/>
		        <div class="HIW_element_text">
                    <h3> ????</h3>
		            <p>
????????????</p>
                </div>
 </div>

  <div class="HIW_element_r">
		        <img src="img/hiw.png" class="HIW_element_img_r"/>
		        <div class="HIW_element_text">
                    <h3> ????</h3>
		            <p>
????????????????????
					</p>
                </div>
 </div>
 
  <div class="HIW_element_l">
		        <img src="img/hiw.png" class="HIW_element_img_l"/>
		        <div class="HIW_element_text">
                    <h3> ????</h3>
		            <p>
????????????????
					</p>
                </div>
 </div>

   <div class="HIW_element_r">
		        <img src="img/hiw.png" class="HIW_element_img_r"/>
		        <div class="HIW_element_text">
                    <h3> ????</h3>
		            <p>
????????????
					</p>
                </div>
 </div>
 
 <!-- схематичная картинка-->	
 <div class="HIW_img_div">
 		        <img src="img/hiw.png" class="HIW_img" />
 </div>

  <!-- Раздел с вопросами-->	
 <div>
  <h1>Вопросы</h1>
 <ul>
<li>
<a href="#Q_1f" id="Show_Q_1f">Вопрос 1</a>
<div class="" id="question_1f" class style="display: none">
Ответ на вопрос 1 
</div>
</li>
<li>
<a href="#Q_2f" id="Show_Q_2f">Вопрос 2</a>
<div class="" id="question_2f"  class style="display: none">
Ответ на вопрос 2
</div>
</li>
<li>
<a href="#Q_3f" id="Show_Q_3f">Вопрос 3</a>
<div class="" id="question_3f"  class style="display: none">
Ответ на вопрос 3
</div>
</li>
<li>
<a href="#Q_4f" id="Show_Q_4f">Вопрос 4</a>
<div class="" id="question_4f"  class style="display: none">
Ответ на вопрос 4
</div>
</li>
<li>
<a href="#Q_5f" id="Show_Q_5f">Вопрос 5</a>
<div class="" id="question_5f"  class style="display: none">
Ответ на вопрос 5
</div>
</li>
</ul>
 </div>
 
 
</div>
	
	
</section>		
	

<!-- Скрипты для работы с изменением показа для заказчика\фрилансера и открытии выпросов-->		
<script>
// ------------------------------------------------------------------------------ //

<!-- открыть - закрыть вопросы-->
$('#Show_Q_1e').bind('click', function(){
 $("#question_1e").toggle();
});
$('#Show_Q_1f').bind('click', function(){
 $("#question_1f").toggle();
});
$('#Show_Q_2e').bind('click', function(){
 $("#question_2e").toggle();
});
$('#Show_Q_2f').bind('click', function(){
 $("#question_2f").toggle();
});
$('#Show_Q_3e').bind('click', function(){
 $("#question_3e").toggle();
});
$('#Show_Q_3f').bind('click', function(){
 $("#question_3f").toggle();
});
$('#Show_Q_4e').bind('click', function(){
 $("#question_4e").toggle();
});
$('#Show_Q_4f').bind('click', function(){
 $("#question_4f").toggle();
});
$('#Show_Q_5e').bind('click', function(){
 $("#question_5e").toggle();
});
$('#Show_Q_5f').bind('click', function(){
 $("#question_5f").toggle();
});



<!-- изменить тип отображения - заказчик или заказчик-->
$('#showfreelance').bind('click', function(){
  $('#employer_part').hide(); <!--скрываем div с кнопкой создать тест -->
  $('#freelance_part').show(); <!--показывает div с формой создания теста -->
});
  $("#showfreelance").mouseover(function(){
      $(this).css('background-color','green');
    })
		$("#showfreelance").mouseout(function(){	
      $(this).css('background-color','blue');
    });
	
$('#showemployer').bind('click', function(){
  $('#freelance_part').hide(); <!--скрывает div с формой создания теста -->
  $('#employer_part').show(); <!--показывает div с кнопкой создать тест -->
});
  $("#showemployer").mouseover(function(){
      $(this).css('background-color','green');
    })
		$("#showemployer").mouseout(function(){	
      $(this).css('background-color','blue');
    });
  
 </script>
	
</body>		
<!-- Футер -->
<footer class="GlobalFooter">
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
<p class="FooterLegal">© 2015 ServiceName.</p>
</div>
</div>
</div>
</footer>
</html>
<?php
/* Закрываем соединение */ 
$BD->close();	
?>