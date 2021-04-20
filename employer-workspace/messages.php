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
$BD->set_charset("utf8");

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
	
	
/* Принятие GET запроса с id  для определения id работы */
$id = htmlspecialchars(trim( isset($_GET['id']) ? (int) $_GET['id'] : 0));





?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js" charset="utf-8"></script> <!-- именно эта версия, потому что другие не поддерживают функции для уничтожения тегов в поле див -->
<html>
<head>
<link href="/mysite/styles_index.css" rel="stylesheet" type="text/css" />
</head>

<body>
<header  class="FW_GlobalHeader">

<nav class="FW_top_nav">
<ul  class="FW_top_nav_ul">
<li class="FW_top_nav_ul_current_index">
<a href="index.php" class="FW_top_nav_href">Главная</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="post-job.php" class="FW_top_nav_href">Опубликовать проект</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="my-jobs.php" class="FW_top_nav_href">Мои проекты</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="freelance.php" class="FW_top_nav_href">Фриланс раздел</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="finance.php" class="FW_top_nav_href">Финансовый раздел</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="messages.php" class="FW_top_nav_href">Общение</a>
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

<nav class="FM_middle_nav">
<h1 class=""> Контакты </h1>
<ul class="">

<?php
if($Freelancers_Information = $BD->query("SELECT * FROM `Contracts`, `Jobs` WHERE Contracts.JobId=Jobs.id AND Jobs.EmployerId='$User_Id' group by Contracts.FreelancerId ")){
while($Freelancers_Information_Data = $Freelancers_Information->fetch_assoc()) {

$FreelancersId = $Freelancers_Information_Data['FreelancerId'];
if($Freelancer_Information = $BD->query("SELECT * FROM `users` WHERE `id`='$FreelancersId' ") ){
$Freelancer_Information_Data = $Freelancer_Information->fetch_assoc();
$FreelancerFirstName = $Freelancer_Information_Data['FirstName'];
$FreelancerLastName = $Freelancer_Information_Data['LastName'];
$Freelancer_Information->close(); 
}
?>
<li>
<a class="" href="?id=<?php echo $FreelancersId; ?>"><?php echo "$FreelancerFirstName $FreelancerLastName"; ?></a>
</li>
<?php
}
$Freelancers_Information->close(); 
}
?>
</ul>
</nav>



<div class="FM_middle_center">

 <header>
 <h2> <?php echo $id; ?> </h2>
 <br>
 </header>
 
 
<!-- РАЗДЕЛ ЧАТА С ФРИЛАНСЕРОМ -->

<!-- Место для сообщенй -->
<div class="Message_box_text" id="Message_box_text"  class style=" margin: 10px 0px 215px;">
<table  cellspacing="0" cellpadding="0">
<tbody id="Message_place" class="the-return">

</tbody>
</table>
</div>


<!-- Место для ввода сообщений -->
<div class="Message_box_input">
<div class="Message-box" id="Message-box" contenteditable="true" style="height: 150px; overflow-y: auto;">
</div> 
<input type="submit" class="" id="submitMessage" value="Отправить" onclick="NewMessage()" class style="margin-left: 23%;">
</div>




</div>
</section>

<script>
   
	$(function(){
	

$("#Message_box_text").scrollTop(10000); // это рабочий скрипт для того, чтобы сместить скроллер вниз, но т.к. информация обновляется с нуля, то и опустить единожды не получается

	

	function update(){
			
    var data = {
      "action": "test",
	  "contact": "<?php echo $id?>"
	      };
    data = $(this).serialize() + "&" + $.param(data);
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "s_message_response.php", //Relative or absolute path to response.php file
      data: data,
      success: function(data) { // если данные успешно пришли
	 
	  $('#Message_place').empty();  // очищает все сообщения

// в цикле от первого до последнего сообщения, пришедшего из запроса выводим текст по очереди	 	  
	   i = 1; // счетчик
	  while( i < data["count"]){   // пока данные не стали равны кол-ву сообщений
	 
//	 alert (data["Who "+i]);
//	 alert (data["count"]+i);
	  if( data["Who "+i] == <?php echo $User_Id; ?>){
// $('<tr><td class="Message_author"><div class="ChatMessageTo" id="'+i+'">"'  + data["Who "+i] + '"</div></td><td class="Message_text"><div class="ChatMessageTo" id="'+i+'">"'  + data["text "+i] + '"</div></td><td  class="Message_data"><div class="ChatMessageTo" id="'+i+'">"'  + data["time "+i] + '"</div></td></tr>').appendTo('#Message_place'); // создаем новый элемент в id=message_place если сообщение от пользователя	  

$('<tr><td class="ChatMessageReadCheck"><div class=""></div></td><td colspan=2  class="ChatMessageContentTo"><div class="ChatMessageText" id="'+i+'">'  + data["text "+i] + '</div><div class="ChatMessageTime" id="'+i+'">'  + data["time "+i] + '</div></td></tr>').appendTo('#Message_place'); // создаем новый элемент в id=message_place если сообщение от пользователя	 

	  }else {
// $('<tr><td class="Message_author"><div class="ChatMessageFrom" id="'+i+'">"'  + data["Who "+i] + '"</div></td><td class="Message_text"><div class="ChatMessageFrom" id="'+i+'">"'  + data["text "+i] + '"</div></td><td  class="Message_data"><div class="ChatMessageFrom" id="'+i+'">"'  + data["time "+i] + '"</div></td></tr>').appendTo('#Message_place'); // создаем новый элемент в id=message_place если сообщение от собеседника	  

$('<tr><td colspan=2 class="ChatMessageContentFrom"><div class="ChatMessageContentFrom" id="'+i+'">'  + data["text "+i] + '</div><div class="ChatMessageTime" id="'+i+'">'  + data["time "+i] + '</div></td><td class="ChatMessageReadCheck"><div class=""></div></td></tr>').appendTo('#Message_place'); // создаем новый элемент в id=message_place если сообщение от собеседника

	  }
	  

$("#Message_box_text").scrollTop(10000);
  //	 $(".the-return").html( 
//	  "<div id="+i+"><br />Gender: " + data["text "+i] + "<br />JSON: " + data["json"] + "</div>"
//	  ); 
	  
     i++; // прибавляем 1 к счётчику
	}

   //    alert("Form submitted successfully.\nReturned json: " + data["json"]);
      }
    });
  
				
                    // обновим таймер 
                    update_timer();
					
					
                }
	
	update();

    // что бы окно чата обновлялось раз в 10 секунд, прицепим таймер

    var timer;
    function update_timer() {
	
        if (timer) // если таймер уже был, сбрасываем
            clearTimeout(timer);
        timer = setTimeout(function () {
            update();
        }, 5000); // 1000 - 1 sec
    }
    update_timer();
	
	

			});
	
	
		// При нажатии кнопки "отправить" - отправка данных на скрипт и обновление таймера при усмешном завершении
	function NewMessage() {
	
	var texttext = $('#Message-box').html();
	
	 var data = {
	  "contact": "<?php echo $id?>",
	  "message": texttext
	      };
    data = $(this).serialize() + "&" + $.param(data);
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "s_message_new-message.php", //Relative or absolute path to response.php file
      data: data,
      success: function(data) { // если данные успешно пришли
		$('#Message-box').empty();  
			}
	});
	
	}
	

	
// чтобы в поле вносилось без тегов, без кода и тд. Чистый текст
$('[contenteditable]').on('paste',function(e) {
    e.preventDefault();
    var text = (e.originalEvent || e).clipboardData.getData('text/plain') || prompt('Paste something..');
    window.document.execCommand('insertText', false, text);
});

</script>


</body>



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
}
?>