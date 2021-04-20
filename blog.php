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
<img src="logo.png" width="125" height="35">
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


  <div class="News_Page_Title">
    <h1 class="News_Page_Under_Title">Что нового на сайте</h1>
  </div>
  
<!-- Новостная панель -->
<div class="News_Content">
<main class="News_Main" role="main">

<?php
$News_Information=$BD->query("SELECT * FROM `News` "); // новости
While($News_data=$News_Information->fetch_assoc()){

$News_Title = $News_data['Title'];
$News_Author = $News_data['Author'];
$News_Data = $News_data['Data'];
$News_Image = $News_data['Image_Large'];
$News_Text = $News_data['Text'];
$News_Link = $News_data['Link'];
?>

<article class="News_article" itemscope itemtype="http://schema.org/Article">
	<header>
    
    <a class="News_wrapper" href="/mysite/blog/<?php echo $News_Link; ?>.php">
      <h1 class="News_title" itemprop="name"><?php echo $News_Title; ?></h1>
      <img class="News_image" src="/mysite/img/news/<?php echo $News_Image; ?>" alt="" /> 
    </a>
    <div class="News_meta">
      <span class="News_date"><?php echo $News_Data; ?></span>
      <span class="News_author"> Автор - <a href="ссылка на автора" itemprop="author"><?php echo $News_Author; ?></a></span>
    </div>
	</header>

	<div class="News_content">
    <p><?php echo $News_Text; ?><a href="/mysite/blog/<?php echo $News_Link; ?>.php" class="News_read-more">Прочитать всю новость</a></p>
	</div>
</article>

<?php 
} 
$News_Information->close(); 
?>

</main>


<!-- Правая панель  НЕ ЗАКОНЧЕНА -->
<aside class="News_aside" role="complementary">
<div class="News_aside_wrapper">
  <div class="row">
    <div class="medium-12 columns get-started">
      <a href="/">Впервые на сайте? <em>Узнайте больше</em></a>
    </div>
  </div>

  <ul class="row">
    <li id="blog_subscription-4" class="widget jetpack_subscription_widget"><h2 class="widgettitle"><label for="subscribe-field">Подпишитесь для получения &amp; новостей</label></h2>

		<form action="#" method="post" accept-charset="utf-8" id="subscribe-blog-blog_subscription-4">
			<div id="subscribe-text"></div>
			<p id="subscribe-email">
				<label id="jetpack-subscribe-label" for="subscribe-field">
					Email 				</label>
				<input type="email" name="email" value="" id="subscribe-field" placeholder="Email Address" />
			</p>

			<p id="subscribe-submit">
				<input type="hidden" name="action" value="subscribe" />
				<input type="hidden" name="source" value="" />
				<input type="hidden" name="sub-type" value="widget" />
				<input type="hidden" name="redirect_fragment" value="blog_subscription-4" />
								<input type="submit" value="Subscribe" name="jetpack_subscriptions_widget" />
			</p>
		</form>

		<script>
			( function( d ) {
				if ( ( 'placeholder' in d.createElement( 'input' ) ) ) {
					var label = d.getElementById( 'jetpack-subscribe-label' );
 					label.style.clip 	 = 'rect(1px, 1px, 1px, 1px)';
 					label.style.position = 'absolute';
 					label.style.height   = '1px';
 					label.style.width    = '1px';
 					label.style.overflow = 'hidden';
				}
			} ) ( document );
		</script>

		
</li>
<li id="categories-2" class="widget widget_categories"><h2 class="widgettitle">Категории</h2>
		<ul>
	<li class="cat-item cat-item-42"><a href="" title="The official corporate blog - home of announcements, events, contests and oConomy trends.">Все категории</a>
</li>
	<li class="cat-item cat-item-2471"><a href="" >Первая </a>
</li>
	<li class="cat-item cat-item-87"><a href="" >Вторая</a>
</li>
	<li class="cat-item cat-item-2465"><a href="" >Третья</a>
</li>
	<li class="cat-item cat-item-2466"><a href="" >Четвёртая</a>
</li>
	<li class="cat-item cat-item-916"><a href="" title="Resources for small businesses - tips, advice and guidance on doing more with less.">Пятая(над чем идёт работа)</a>
</li>
		</ul>
</li>
<li id="tag_cloud-4" class="widget widget_tag_cloud"><h2 class="widgettitle">Тэги</h2>
<div class="tagcloud"><a href='' class='tag-link-2467' title='32 topics' style='font-size: 100%;'>Тэг</a>
<a href='' class='tag-link-132' title='153 topics' style='font-size: 100%;'>Тэг</a>
<a href='' class='tag-link-2412' title='81 topics' style='font-size: 100%;'>Тэг</a>
<a href='' class='tag-link-2457' title='43 topics' style='font-size: 100%;'>Тэг</a>
<a href='' class='tag-link-1280' title='122 topics' style='font-size: 100%;'>Тэг</a>
<a href='' class='tag-link-92' title='474 topics' style='font-size: 100%;'>Тэг</a>
<a href='' class='tag-link-2499' title='4 topics' style='font-size: 100%;'>Тэг</a>
<a href='' class='tag-link-2453' title='136 topics' style='font-size: 100%;'>тэг</a></div>
</li>
  </ul>
</div>
</aside>

</div>
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
<p class="FooterLegal">© 2014 ServiceName.</p>
</div>
</div>
</div>
</footer>
</html>
<?php
/* Закрываем соединение */ 
$BD->close();	
?>