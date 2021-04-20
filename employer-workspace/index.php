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
	
// Переменные текущего пользователя
	$User_Id=$User_Information_Data['id']; 					// Переменная "id"
	$First_Name=$User_Information_Data['FirstName']; 		// Переменная "Имя"
	$Last_Name=$User_Information_Data['LastName'];			// Переменная "Фамилия"
	$Username=$User_Information_Data['Username']; 			// Переменная "Логин"
	$Email=$User_Information_Data['Email'];					 // Переменная "Почта"
	$Rating=$User_Information_Data['Rating'];				// Переменная "Рейтинг"
	$Money=$User_Information_Data['Money']; 				// Переменная "Кол-во денег"
	$Reserved_Money=$User_Information_Data['ReservedMoney']; // Переменная "Кол-во денег в резерве"
	
// Переменные о блоке или бане аккаунта текущего пользователя

	$User_Block=$User_Information_Data['Block'];
	$User_Ban=$User_Information_Data['Ban'];	
	
// Переменная "Активация Почтового Ящика" текущего пользователя
	$Activation_Email=$User_Information_Data['Activation'];
	
/* Освобождаем память $User_Information */ 
$User_Information->close(); 
}

				/* Раздел новостной ленты */
/* Посылаем запрос серверу на выборку информации о новостях */	

if ($News_Information = $BD->query("SELECT * FROM `News` ORDER BY `Data` DESC ")) { 

    /* Выбираем результаты запроса по $User_Information : */ 
$News_Information_Data = $News_Information->fetch_assoc();

$News_Title = $News_Information_Data['Title'];
$News_Author = $News_Information_Data['Author'];
$News_Data = $News_Information_Data['Data'];
$News_Image = $News_Information_Data['Image_Large'];
$News_Text = $News_Information_Data['Text'];
$News_Link = $News_Information_Data['Link'];

/* Освобождаем память $User_Information */ 
$News_Information->close(); 
	}
	
	
	
	
	
				/* Раздел Выбора показа помощи или статистики */
/* Посылаем запрос серверу на выборку информации о работах для проверки есть проекты или нет*/	
	
if($Job_Information = $BD->query("SELECT * FROM `Jobs` Where `EmployerId`='$User_Id' ")) { 

    /* Выбираем результаты запроса : */ 
$Job_Information_Data = $Job_Information->fetch_assoc();

$Job_id = $Job_Information_Data['id'];

/* Освобождаем память  */ 
$Job_Information->close(); 
}
?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js" charset="utf-8"></script> <!-- Библиотека для jqiery -->
<title>Home Page - <?php echo "$First_Name $Last_Name";?> </title>
<html>


<head>
<link href="/mysite/styles_index.css" rel="stylesheet" type="text/css" />		<!-- css -->
<link href="/mysite/module_css/tips.css" rel="stylesheet" type="text/css" />		<!-- css  подсказок как модуль -->
</head>



<body class="Main_Body">
<div id="Main_Section">





<!-- Главная навигационная панель -->
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
<a href="my-jobs.php" class="FW_top_nav_2_href">Мои проекты</a>
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

<div class="FM_middle_center">

<!-- Новостная панель -->

<article class="News_Index_article" itemscope itemtype="http://schema.org/Article">
	<header class="News_Index_Header">
   <p class="News_Index_Category">Последняя новость</p>
    <a class="News_Index_wrapper" href="/mysite/blog/<?php echo $News_Link; ?>.php">
      <h1 class="News_Index_title" itemprop="name"><?php echo $News_Title; ?></h1>
      <img class="News_Index_image" src="/mysite/img/news/<?php echo $News_Image; ?>" alt="" /> 
    </a>
    <div class="News_Index_meta">
      <span class="News_Index_date"><?php echo $News_Data; ?></span>
      <span class="News_Index_author"> Автор - <a href="ссылка на автора" itemprop="author"><?php echo $News_Author; ?></a></span>
    </div>
	</header>

	<div class="News_Index_content">
    <p><?php echo $News_Text; ?><a href="/mysite/blog/<?php echo $News_Link; ?>.php" class="News_Index_read-more">Прочитать всю новость</a></p>
	</div>
</article>



<!-- Состояние 1 - Как начать работать -->
<?php
if(empty($Job_id)){
?>

<div class="News_Index_content_howto">


<img src="/mysite/img/post_job.png" class="Index_Post_Job_Img" />

<a class="EW_index_bottom" href="post-job.php">Создать проект</a>
или
<a class="EW_index_bottom" href="/mysite/freelancers.php">Найти фрилансера</a> 


<br>
<a class="" href="/mysite/howitworks.php">Как это работает</a>
<br>

<p> Начните работу с сервисом FT, опубликуйте бесплатно свою работу, и наймите фрилансера для её выполнения. 
Только когда работа будет успешно завершена, будет снято 10% коммисии.  </p>

</div>
<?php
}
?>





<!-- Состояние 2 - статистика -->
<?php
if(!empty($Job_id)){
?>
<div class="News_Index_content_howto">
<h1>
Начните работу
</h1>
<!-- Таблица работ -->
<table class="Form_My-Jobs">
<thead>
<tr>
<th>Краткие сведения о текущем статусе работ</th>
</tr>
</thead>
<tbody class="">

<?php
$i_jobs = 0;
$i_contracts=0;
if($Jobs_Information = $BD->query("SELECT * FROM `Jobs` Where `EmployerId`='$User_Id' AND `Closed`='0' ")){ 
while($Jobs_Information_Data = $Jobs_Information->fetch_assoc()) {
$i_jobs = $i_jobs + 1;
$JobId_count=$Jobs_Information_Data['id'];

if($Contracts_Information = $BD->query("SELECT * FROM `Contracts` Where `JobId`='$JobId_count'")){ 
while($Contracts_Information_Data = $Contracts_Information->fetch_assoc()) {
$i_contracts = $i_contracts + 1;
			}
$Contracts_Information->close(); 
		}

	}
$Jobs_Information->close(); 
}
?>

<tr>
<td> <?php echo " Кол-во активных проектов:"; ?> </td>

<td> <?php echo "$i_jobs";  ?> </td>
</tr>

<tr>
<td> <?php echo " Кол-во активных контрактов:"; ?> </td>

<td> <?php echo "$i_contracts";  ?> </td>
</tr>

</tbody>

</table>




<a class="EW_index_bottom_second" href="my-jobs.php">Go to Jobs</a>


<br>
<br>
<br>


<a class="EW_index_bottom" href="post-job.php">Post Job</a>
or
<a class="EW_index_bottom" href="/mysite/freelancers.php">Find Freelancer</a> 

<br>
<a class="" href="/mysite/howitworks.php">Как это работает</a>
<br>

<p> Начните работу с сервисом FT, опубликуйте бесплатно свою работу, и наймите фрилансера для её выполнения. 
Только когда работа будет успешно завершена, будет снято 10% коммисии.  </p>

</div>

<?php
}
?>

</div>

<div class="FW_middle_right">

<div class="FW_middle_right_up">

<div class="FW_middle_right_up_profile_title">
<h2>Мой профиль</h2>
</div>

<div class="FW_middle_right_up_profile">

<!-- В этом разделе определяется, какой статус имеет аккаунт - активен \ не активирован \ заблокирован \ заморожен и пояснения через (?) к каждому -->


<?php
if ($User_Ban == 1){
 ?>
 <div class="FW_middle_right_up_profile_status"> 
<label>Статус:</label> 
 
<div class="FW_middle_right_up_profile_status_current_unactive">Заблокирован&nbsp;
<!-- начало модуля подсказок -->
	<div class="bs-example tooltip-demo"  class style="float: right;">
		<div class="bs-example-tooltips">
			<a type="button" class="btn btn-default" data-toggle="tooltip" data-placement="right" title="Аккаунт заблокирован. Обратитесь к администрации">(?)</a>
		</div>
	</div>
<!-- окончание модуля подсказок -->
</div>    
  
  
 <?php
  }elseif($User_Block == 1){
 ?>
 <div class="FW_middle_right_up_profile_status_block"> 
<label>Статус:</label> 
 
 <div class="FW_middle_right_up_profile_status_current_unactive">Заморожен&nbsp;  
<!-- начало модуля подсказок -->
	<div class="bs-example tooltip-demo"  class style="float: right;">
		<div class="bs-example-tooltips">
			<a type="button" class="btn btn-default" data-toggle="tooltip" data-placement="right" title="Аккаунт заморожен">(?)</a>
		</div>
	</div>
<!-- окончание модуля подсказок -->
</div>

  <?php
 }elseif($Activation_Email == 0){
  ?>
 <div class="FW_middle_right_up_profile_status"> 
<label>Статус:</label> 
 
 <div class="FW_middle_right_up_profile_status_current_unactive">Не активирован&nbsp;
<!-- начало модуля подсказок -->
	<div class="bs-example tooltip-demo"  class style="float: right;">
		<div class="bs-example-tooltips">
			<a type="button" class="btn btn-default" data-toggle="tooltip" data-placement="right" title="Требуется активация аккаунта (подтверждение активации по почте)">(?)</a>
		</div>
	</div>
<!-- окончание модуля подсказок -->
</div>  

  <?php
 }elseif($Activation_Email == 1){
 ?>
 <div class="FW_middle_right_up_profile_status_active"> 
<label>Статус:</label> 
 
 <div class="FW_middle_right_up_profile_status_current_active">Активен&nbsp; 
<!-- начало модуля подсказок -->
	<div class="bs-example tooltip-demo"  class style="float: right;">
		<div class="bs-example-tooltips">
            <a type="button" class="btn btn-default" data-toggle="tooltip" data-placement="right" title="Аккаунт активен">(?)</a>   
		</div>
	</div>
<!-- окончание модуля подсказок -->
</div> 
 <?php
 }
 ?>
</div>


<!-- В этом разделе показываются данные: Логин \ почта \ репутация \ тип аккаунта и кнопка "редактировать", которая ведет к profile.php -->
<div class="FW_middle_right_up_profile_content"> 
<label>Логин:</label>
<div class="FW_middle_right_up_profile_content_div">
<?php
echo $Username;
?>
</div>
</div>

<div class="FW_middle_right_up_profile_content">
<label>Почта:</label>
<div  class="FW_middle_right_up_profile_content_div">
<?php
echo $Email;
?>
</div>
</div>

<div class="FW_middle_right_up_profile_content">
<label>Баланс:
</label>
<div  class="FW_middle_right_up_profile_content_div">
<?php
echo "$Money | $Reserved_Money &nbsp; ";
?>
<!-- начало модуля подсказок -->
	<div class="bs-example tooltip-demo" class style="float: right;" >
		<div class="bs-example-tooltips">
            <a type="button" class="btn btn-default" data-toggle="tooltip" data-placement="right" title="Свободно | Зарезервированно в проектах">(?)</a>   
		</div>
	</div>
</div> 
<!-- окончание модуля подсказок -->
</div>


<div class="FW_middle_right_up_profile_content">
<label>Репутация:</label>
<div  class="FW_middle_right_up_profile_content_star" class style="margin-left: 23px;">
<div  class="FW_middle_right_up_profile_content_star_count" class style="width: <?php echo $Rating ?>%;">
</div>
</div>
</div>

<div class="FW_middle_right_up_profile_content">
<label>Тип аккаунта:</label>
<div  class="FW_middle_right_up_profile_content_div">
<?php
echo "Базовый";
?>
</div>
</div>

<div class style="padding: 5% 0% 0 17%;">

<a href="profile.php" class="FW_top_nav_href">Редактировать</a>

</div>

</div>

</div>










<div class="FW_middle_right_up_profile">
<div class="">
        <section>
            <div class="">
                         <ul class="">
                                    <li class="FW_index_under_profile_li">
							<img class="FW_index_under_profile_img" src="/mysite/img/hiw_2_e.png">
                                                <div class="FW_index_under_profile_div">
                                <a class=""  href=""> Как начать?     </a>
                                <div class="">Узнайте всё о найме фрилансеров!</div>
                            </div>
                        </li>
                                        <li class="FW_index_under_profile_li" >
			<img class="FW_index_under_profile_img" src="/mysite/img/hiw_2_e.png">
                                           <div class="FW_index_under_profile_div">
								  <a class=""  href="">Оплата</a>   
							<div class="">Выберите самый удобный для Вас способ оплаты!</div>
                        </div>
                    </li>
					                      <li class="FW_index_under_profile_li" >
							    <img class="FW_index_under_profile_img" src="/mysite/img/hiw_2_e.png">
                            <span class=""></span>
                            <div class="FW_index_under_profile_div">
                                 <a class=""  href="">Типы аккаунтов</a>   
                                <div class="">Выберите подходящий именно Вам!</div>
                            </div>
                        </li>
                                            <li class="FW_index_under_profile_li" >
								<img class="FW_index_under_profile_img" src="/mysite/img/hiw_2_e.png">
                            
                            <div class="FW_index_under_profile_div">
                                <span class="">Нужна помощь?</span>
                                <div class="">mail@mail.mail</div>
                            </div>
                        </li>
                                    </ul>
            </div>
        </section>
    </div>

</div>





</div>


</section>

</div>
</body>

<script type="text/javascript" src="/mysite/module_scripts/tips.js" charset="utf-8"></script> <!-- модуль подскахок -->

<!-- Футер -->
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