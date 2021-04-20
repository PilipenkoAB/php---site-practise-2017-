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
	$id_user=$user_data['id'];
    $group=$user_data['GroupId'];
	if ($group !=2) {
		header("location: erroronlyemployer.php");
	}
	$FirstName=$user_data['FirstName'];
	$LastName=$user_data['LastName'];	
	
	// раздел объявления об доступе к работе
	$activationemail=$user_data['Activation'];


	//  Раздел проверки действительно ли это фрилансер, и если да то показать страницу
	$id = htmlspecialchars(trim( isset($_GET['id']) ? (int) $_GET['id'] : 0));
	
	$InviteFreelancer=mysql_query("SELECT * FROM `users` WHERE `id`='$id' AND `GroupId`='1' ");
    $InviteFreelancer_data=mysql_fetch_array($InviteFreelancer);
	
	if(empty($InviteFreelancer_data)) {
			header("location: /mysite/freelancers.php");
	}
	
	// Раздел создания заявки для уже существующего задания
	
	$JobResponse=htmlspecialchars(trim( isset($_GET['jb']) ? (int) $_GET['jb'] : 0));
	
	$InviteFreelancerJobCheck = 0;
	
	if($JobResponse != 0){
	$InviteFreelancerJob=mysql_query("SELECT * FROM `Jobs` WHERE `id`='$jb' AND `Active`='0' AND `FreelancerId`='0' ");
    $InviteFreelancerJob_data=mysql_fetch_array($InviteFreelancerJob);
		
	if(!empty($InviteFreelancerJob_data)) {
	$InviteFreelancerJobCheck = 1;
	
	// данные о выбранной работе 
	$InviteLanguageFrom=$InviteFreelancerJob_data['LanguageFrom'];
	$InviteLanguageTo=$InviteFreelancerJob_data['LanguageTo'];	
	$InviteStyle=$InviteFreelancerJob_data['Style'];	
	$InviteTitle=$InviteFreelancerJob_data['Title'];	
	$InviteDescription=$InviteFreelancerJob_data['Description'];	
	$InviteType=$InviteFreelancerJob_data['Type'];
	$InviteValue=$InviteFreelancerJob_data['Value'];
	$InviteSearch=$InviteFreelancerJob_data['Search'];
	$InviteDuration=$InviteFreelancerJob_data['Duration'];
	$InvitePrivate=$InviteFreelancerJob_data['Private'];
	$InviteTestJobDescription=$InviteFreelancerJob_data['TestJobDescription'];
	$InviteTestJobText=$InviteFreelancerJob_data['TestJobText'];	
	$InviteStartPrice=$InviteFreelancerJob_data['StartPrice'];
	$InviteDataCreation=$InviteFreelancerJob_data['DataCreation'];
	$InvitePrepayment=$InviteFreelancerJob_data['Prepayment'];
	
		}else {
	header("location: errorinvitejob.php");	
		}
	}
	// раздел об ошибках
	$error = 0; // если 0 то нет ошибок, если 1 - то ошибки в заполнении полей
	
	$ErrorLanguageFrom=0;
	$ErrorLanguageTo=0;
	$ErrorStyle=0;
	$ErrorTitle=0;
	$ErrorDescription=0;
	$ErrorType=0;
	$ErrorValue=0;
	$ErrorDuration=0;
	$ErrorSearch=0;
	$ErrorPrice=0;
	//---------------------------------
	
	$preview = 0; // если 0 - то тип страницы - задание, если 1 - тип страницы предпросмотр
	
	// раздел приема данных после нажатия кнопки перейти к предпросмотру
	
	$LanguageFrom=NUll;
	$LanguageTo=NUll;
	$Style=NUll;
	$Title=NUll;
	$Description=NUll;
	$Type=NUll;
	$Value=NUll;
	$Search=NUll;
	$Private=NUll;
	$Duration=NUll;
	$Prepayment=NUll;
	$Price=NUll;
	$TestText=Null;
	$TestDescription=Null;
	
	if(isset($_POST['LanguageFrom']) && ($_POST['LanguageTo']) && isset($_POST['Style']) && isset($_POST['Title']) && isset($_POST['Description']) && isset($_POST['Type']) && isset($_POST['Value']) && isset($_POST['TestHidden']) && isset($_POST['Duration']) && isset($_POST['Prepayment']) && isset($_POST['Price']) && isset($_POST['DescriptionFreelancerJob'])  ) {
    
	// переменные из приходящей информации без специальных симоволов
	
	$LanguageFrom=htmlspecialchars($_POST['LanguageFrom']);
	$LanguageTo=htmlspecialchars($_POST['LanguageTo']);
	$Style=htmlspecialchars($_POST['Style']);	
	$Title=htmlspecialchars($_POST['Title']);
	$Description=htmlspecialchars($_POST['Description']);
	$Type=htmlspecialchars($_POST['Type']);
	$Value=htmlspecialchars($_POST['Value']);
	$TestHidden=htmlspecialchars($_POST['TestHidden']);
	$Duration=htmlspecialchars($_POST['Duration']);
	$Price=htmlspecialchars($_POST['Price']);
	$Prepayment=htmlspecialchars($_POST['Prepayment']);
	
	$DescriptionFreelancerJob=htmlspecialchars($_POST['DescriptionFreelancerJob']);
	
	$EmployerId=$user_data['id'];
	
	$DataCreation=date('Y-m-d H:i:s');
	$SubValue=0;
	$TestJob=0;
	$TestDescription=0;
	$TestText=0;
	//-------------
	// проверка на приход нужных данных ( не пустых, нужные символы и тд и тп) ВАЛИДАЦИЯ
	//-------------

	// Если не выбрал язык - ошибка
	if($LanguageFrom == 0) {
	$error = 1;
	$ErrorLanguageFrom=1;
		}
	if($LanguageTo == 0) {
	$error = 1;
	$ErrorLanguageTo=1;
		}
	// Если не выбрал стиль - ошибка
	if($Style != 1 && $Style != 2 && $Style != 3 && $Style != 4 && $Style != 5 && $Style != 6 && $Style != 7) { // если не пришло одно из значений 1 или 2 или 3 ... 7
	$error = 1;
	$ErrorStyle=1;
		}
	// если пустой заголовок и описание - ошибка
	$TitleCheck=trim($Title); // проверка на пробелы (чтобы нельзя было прислать просто пробелы)
	$DescriptionCheck=trim($Description); // проверка на пробелы (чтобы нельзя было прислать просто пробелы)
		if(empty($TitleCheck)){
	$error = 1; 
	$ErrorTitle=1;
	}	
	if(empty($DescriptionCheck)) { // если ничего не пришло 
	$error = 1; 
	$ErrorDescription=1;
		}

	// если не выбран тип
	if($Type != 1 && $Type != 2 && $Type != 3 && $Type != 4 && $Type != 5) {  // если не пришло одно из значений 1 или 2 или 3
	$error = 1;
	$ErrorType=1;
	} 
	
	
	// если не выбран объем
	if($Value != 1 && $Value != 2 && $Value != 3 && $Value != 4 ) {  // если не пришло одно из значений 1 или 2 или 3 или 4
	$error = 1;
	$ErrorValue=1;
	} 

	
	//РАЗДЕЛ ТЕСТОВОГО ЗАДАНИЯ
	// Если TestHidden принимает значение не 1 или не 0 - ошибка
	if($TestHidden != 0 && $TestHidden != 1) {
	$error = 1;
	echo "154,";
	} 
	
	if($TestHidden == 1 && isset($_POST['testdescription']) && isset($_POST['testtext'])) {
	$TestDescription=htmlspecialchars($_POST['testdescription']);
	$TestText=htmlspecialchars($_POST['testtext']);
    $TestDescriptionCheck=trim($TestDescription); // проверка на пробелы (чтобы нельзя было прислать просто пробелы)
	$TestTextCheck=trim($TestText); // проверка на пробелы (чтобы нельзя было прислать просто пробелы)
	if(empty($TestDescriptionCheck) || empty($TestTextCheck)) { // если ничего не пришло 
	$error = 1; 
	echo "160,";
		}
	} 
	//ОКОНЧАНИЕ РАЗДЕЛА ТЕСТОВОГО ЗАДАНИЯ
	
	//Проверка поля Duration
	if($Duration != 1 && $Duration != 2 && $Duration != 3 && $Duration != 4 && $Duration != 5 && $Duration != 6) {
	$error = 1;
	$ErrorDuration=1;
	} 
	
		// Проверка на поле Private 
	 If (isset($_POST['Private'])) {
	$Private=htmlspecialchars($_POST['Private']);
	} else {
	$Private=0;
		}
	if ($Private != 0 && $Private != 1) {
	$error = 1;
	echo "177,";
	}

	// Проверка на поле Prepayment		

	if($Prepayment != 1 && $Prepayment != 2 && $Prepayment != 3 && $Prepayment != 4 && $Prepayment != 0 ) {
	$error = 1;
	echo "186,";
	} 
	
	// РАЗДЕЛ ВВОДА ФАЙЛА
	
	
	// РАЗДЕЛ ФОРМИРОНИЯ ЦЕНЫ
	//формирование минимальной цены
	$MinPrice=1;
	
	//цена не может быть ниже подсчитанной минимальной цены
	if($Price < $MinPrice) {
	$error = 1;
	$ErrorPrice=1;
		}
	//ОКОНЧАНИЕ РАЗДЕЛА ФОРМИРОВАНИЯ ЦЕНЫ
	
	//---
	// Если нет ошибок заходим в if и продолжаем работу, иначе проходим вниз и открываем форму об ошибке
	//---
	if($error !== 1) {
	
	// если preview = 0 задаем значение preview = 1
	
	// РАЗДЕЛ ПРИНЯТИЯ , ОТМЕНЫ ИЛИ СОХРАНЕНИЯ КАК ШАБЛОНА

// Раздел валидации пришедших данных

	
// если с валидацией все нормально, то проверяем, что было нажато и делаем соответствующее действие	

// ПУБЛИКАЦИЯ РАБОТЫ

	// Заносим все в бд в таблицу работы 
$RegisterJob="INSERT INTO `Jobs`(`EmployerId`,`LanguageFrom`,`LanguageTo`,`Style`,`Title`,`Description`,`Type`,`Value`,`Search`,`Private`,`Prepayment`,`Duration`,`StartPrice`,`DataCreation`,`TestJobDescription`,`TestJobText`) VALUES ('$EmployerId', '$LanguageFrom','$LanguageTo','$Style', '$Title', '$Description', '$Type', '$Value','2','$Private','$Prepayment','$Duration','$Price','$DataCreation','$TestDescription','$TestText')";
$ResultJob=mysql_query($RegisterJob);

$ResultJobBd=mysql_query("SELECT * FROM `Jobs` WHERE `EmployerId`='$EmployerId'  AND  `LanguageFrom`='$LanguageFrom'  AND `LanguageTo`='$LanguageTo' AND `Style`='$Style' AND `Title`='$Title' AND `Description`='$Description' AND `Type`='$Type' AND `Value`='$Value' AND `Search`='2' AND `Private`='$Private' AND `Prepayment`='$Prepayment' AND `Duration`='$Duration' AND `StartPrice`='$Price' AND `DataCreation`='$DataCreation' AND `TestJobDescription`='$TestDescription' AND `TestJobText`='$TestText'");
$ResultJobBd_data=mysql_fetch_array($ResultJobBd);
$JobNewRegister=$ResultJobBd_data['id'];

if($ResultJob==true)
	{
	
	
	$RegisterResponse="INSERT INTO `ResponseNewJob`(`FreelancerId`,`JobId`,`Private`,`PrivateDescription`) VALUES ('$id', '$JobNewRegister','1','$DescriptionFreelancerJob')";
$ResultRegisterResponse=mysql_query($RegisterResponse);

if($ResultRegisterResponse==true)
	{
	header("location: my-jobs.php");
}
else
	{
echo "Error! ---->". mysql_error();
}
	}
else
	{
echo "Error! ---->". mysql_error();
}
		
		
		// ВОЗВРАТ НАЗАД ДЛЯ РЕДАКТИРОВАНИЯ
		
		
		// СОХРАНЕНИЕ ШАБЛОНА
	}

		

				}
	
	
	
	
	
	// раздел вывода списка доступных шаблонов
	
	 if( isset($_GET['title'])){
	$TemplateTitleGET=htmlspecialchars($_GET['title']);
	
	$TemplateCheckres=mysql_query("SELECT * FROM `TemplateJob` WHERE `EmployerId`='$id_user' AND `Title`='$TemplateTitleGET' ");
	$TemplateCheck_data=mysql_fetch_array($TemplateCheckres);
	if( !empty($TemplateCheck_data)){
	
	$LanguageFrom=$TemplateCheck_data['LanguageFrom'];
	$LanguageTo=$TemplateCheck_data['LanguageTo'];
	$Title=$TemplateCheck_data['Title'];
	$Style=$TemplateCheck_data['Style'];
	$Description=$TemplateCheck_data['Description'];
	$Type=$TemplateCheck_data['Type'];
	$Value=$TemplateCheck_data['Value'];
	$Search=$TemplateCheck_data['Search'];
	$Duration=$TemplateCheck_data['Duration'];
	$Private=$TemplateCheck_data['Private'];
	$Prepayment=$TemplateCheck_data['Prepayment'];
	$Price=$TemplateCheck_data['StartPrice'];
	
		} else {
		echo "net takogo shablone";
				}	
		}
		

		
		
	if(isset($_POST['DescriptionFreelancer'])) {
	$DescriptionFreelancer=htmlspecialchars($_POST['DescriptionFreelancer']);
	
// Заносим все в бд в таблицу заявок
$RegisterResponse="INSERT INTO `ResponseNewJob`(`FreelancerId`,`JobId`,`Private`,`PrivateDescription`) VALUES ('$id', '$JobResponse','1','$DescriptionFreelancer')";
$ResultRegisterResponse=mysql_query($RegisterResponse);

if($ResultRegisterResponse==true)
	{
	header("location: my-jobs.php");
}
else
	{
echo "Error! ---->". mysql_error();
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
<a href="noname.php" class="FW_top_nav_href">Фриланс раздел</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="finance.php" class="FW_top_nav_href">Финансовый раздел</a>
</li>
<li class="FW_top_nav_ul_last">
<a href="input-messages.php" class="FW_top_nav_href">Общение</a>
</li>
</ul>
<div class="FW_top_nav_username">
<div class="FW_top_user_name">
<?php
echo "$FirstName ";
echo " $LastName";
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
if( $activationemail == 0 ) {
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


<!-- Раздел section, которые отвечает за страницу с Preview = 0 -->

<?php
if($preview == 0) {
?>

<section class="FW_middle">



<!-- Раздел возможности отправить заявку от уже имеющейся работы, имея возможность добавить подпись для фрилансера -->
<div class="">

<div style="text-align: center;">
<h1>Приглашение в уже созданную работу(но без принятой заявки)</h1>
</div>

<?php
if($InviteFreelancerJobCheck == 0) {
?>
<div class="">
<?php
$PrivateJobs=mysql_query("SELECT * FROM `Jobs` WHERE `Private`='1' AND `Active`='0' AND `FreelancerId`='0' ");
While($PrivateJobs_data=mysql_fetch_array($PrivateJobs)){
$PrivateJobsId=$PrivateJobs_data['id'];
?>
<a href="?id=<?php echo $id;?>&jb=<?php echo $PrivateJobsId; ?>">Нанять к этой работе</a> 
<?php
}
?>
</div>

</div>

<!-- Информация о работе, если есть &jb=N -->
<?php
}
if($InviteFreelancerJobCheck == 1) {
?>

<div class="">

<a href="?id=<?php echo $id;?>">Назад</a> 

тут информация о работе + возможность оставить личное сообщение фрилансеру

<div class="Post_Job_Form">
<label class="Post_Job_Label">Язык </label>
 <?php echo "$InviteLanguageFrom - $InviteLanguageTo" ?> 
 </div>

<form name="NewJob" id="NewJob"  method="post" class="FormPostJob">
<div class="Post_Job_Form">
<label class="Post_Job_Label">Сообщение для фрилансера:</label>
<textarea  value maxlength="1000" name="DescriptionFreelancer" id="DescriptionFreelancer" rows="20" cols="80"  class="Post_Job_Form_Elements " placeholder="Сообзение фрилансеру"></textarea>
</div>
<div class="Post_Job_Form">
<input type="submit" class="" id="submit" value="Отправить заявку"/>
</div>
</form>

</div>

<?php
} else {
?>
<!-- Создание нового задания для этого пользователя -->

<div style="text-align: center;">
<h1>Создание работы</h1>
</div>

<div class="PostJobSteps">
<b>РЕДАКТИРОВАНИЕ</b> --> Предпросмотр --> Публикация
</div>

<!-- Объявление об ошибке  на проверке сервера -->
<?php
if($error == 1) {
?>
<div id="ActivationAd" class="ActivationAd"> 
    <p>ОШИБКА В ЗАПОЛНЕНИИ ДАННЫХ</p><br>
</div>
<?php } ?>
<!-- Окончания объявления об ошибке -->


<div class="TemplateForm">
<div id="TemplateDiv1" class="">
<input type="button" id="HideTemplate" value="скрыть">
Выбрать шаблон:
<br>
<?php
$i=0;
	 $Templatesres=mysql_query("SELECT * FROM `TemplateJob` WHERE `EmployerId`='$id_user' ");
     While($Template_data=mysql_fetch_array($Templatesres)) {
	 $TitleTemplate=$Template_data['Title'];
	 $i=$i+1;
	 ?>
	 <a href="?title=<? echo "$TitleTemplate" ?>" class="FW_top_nav_href"><? echo "$i) $TitleTemplate ;" ?></a>
	 <br>
<?php
	 }
?>
</div>
<div id="TemplateDiv2" class="" class style="display: none">
<input type="button" id="ShowTemplate" value="показать">
Шаблоны
</div>
</div>


<form enctype="multipart/form-data" name="NewJob" id="NewJob"  method="post" class="FormPostJob" >
<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
<!-- Выбор языка перевода -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Язык:</label>
<select name="LanguageFrom" class="Post_Job_Form_Elements " id="LanguageFrom">
<option  value="" label="Выберите языки">Выберите языки</option>
<option <?php if($LanguageFrom == 1) { ?> selected <?php }; ?> value="1" label="Русский">Русский</option>
<option <?php if($LanguageFrom == 2) { ?> selected <?php }; ?> value="2" label="Английский">Английский</option>
</select>
<?php
if($ErrorLanguageFrom == 1) {
echo "[!] Ошибка";
}
?>
<aside class="PostJobHelpHidden" id="HelpLanguage">
   Подсказка выбора языка
</aside>
<aside class="PostJobHelpHidden" id="ErrorLanguageFrom">
[!]
</aside>


<select name="LanguageTo" class="Post_Job_Form_Elements " id="LanguageTo">
<option  value="" label="Выберите языки">Выберите языки</option>
<option <?php if($LanguageTo == 1) { ?> selected <?php }; ?> value="1" label="Русский">Русский</option>
<option <?php if($LanguageTo == 2) { ?> selected <?php }; ?> value="2" label="Английский">Английский</option>
</select>
<?php
if($ErrorLanguageTo == 1) {
echo "[!] Ошибка";
}
?>
<aside class="PostJobHelpHidden" id="HelpLanguage">
   Подсказка выбора языка
</aside>
<aside class="PostJobHelpHidden" id="ErrorLanguageTo">
[!]
</aside>


</div>


<!-- Выбор стиля перевода (технический\художественный\итд) -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Стиль:</label>

<select name="Style" class="Post_Job_Form_Elements " id="Style">
<option value="" label="Выберите стиль перевода">Выберите стиль перевода</option>
<option <?php if($Style == 1) { ?> selected <?php }; ?> value="1" label="Общей тематики">Общей тематики</option>
<option <?php if($Style == 2) { ?> selected <?php }; ?> value="2" label="Художественный">Художественный</option>
<option <?php if($Style == 3) { ?> selected <?php }; ?> value="3" label="Технический">Технический</option>
<option <?php if($Style == 4) { ?> selected <?php }; ?> value="4" label="Экономический">Экономический</option>
<option <?php if($Style == 5) { ?> selected <?php }; ?> value="5" label="Политический">Политический</option>
<option <?php if($Style == 6) { ?> selected <?php }; ?> value="6" label="Медицинский">Медицинский</option>
<option <?php if($Style == 7) { ?> selected <?php }; ?> value="7" label="Реклама">Реклама</option>
</select>

<?php
if($ErrorStyle == 1) {
echo "[!] Ошибка";
}
?>

<aside class="PostJobHelpHidden" id="HelpStyle">
Подсказка выбора стиля
</aside>

</div>

<!-- Ввод Названия задания перевода -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Название:</label>
<input type="text" value="<?php  echo $Title;  ?>" value maxlength="50" name="Title" id="Title"  class="Post_Job_Form_Elements " placeholder="Название задания">

<?php
if($ErrorTitle == 1) {
echo "[!] Ошибка";
}
?>

<aside class="PostJobHelpHidden" id="HelpTitle">
Подсказка выбора названия
</aside>

</div>

<!-- Ввод Описания задания перевода -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Описание:</label>
<textarea  value maxlength="5000" name="Description" id="Description" rows="20" cols="80"  class="Post_Job_Form_Elements " placeholder="Описание задания"><?php echo $Description; ?></textarea>

<?php
if($ErrorDescription == 1) {
echo "[!] Ошибка";
}
?>

<aside class="PostJobHelpHidden" id="HelpDescription">
Подсказка выбора описания
</aside>

</div>

<!-- Добавить файл -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Дополнительно:</label>
<input type="file" id="file" name="filename">
</div>


<br>
<hr>
<br>

<!-- Выбор Типа перевода -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Тип:</label>
<select name="Type" class="Post_Job_Form_Elements " id="Type">
<option id="0" value="" label="Выберите тип">Выберите тип</option>
<option <?php if($Type == 1) { ?> selected <?php }; ?> id="type1" value="1" label="Перевод текста">Перевод текста</option>
<option <?php if($Type == 2) { ?> selected <?php }; ?> id="type2" value="2" label="Перевод видео">Перевод видео</option>
<option <?php if($Type == 3) { ?> selected <?php }; ?> id="type3" value="3" label="Перевод аудио">Перевод аудио</option>
<option <?php if($Type == 4) { ?> selected <?php }; ?> id="type4" value="4" label="Локализация">Локализация</option>
<option <?php if($Type == 5) { ?> selected <?php }; ?> id="type5" value="5" label="Другое">Другое</option>
</select>

<?php
if($ErrorType == 1) {
echo "[!] Ошибка";
}
?>

<aside class="PostJobHelpHidden" id="HelpType">
Подсказка выбора типа
</aside>

</div>


<div class="Post_Job_Form">
<label class="Post_Job_Label">Объем:</label>
<select name="Value" class="Post_Job_Form_Elements " id="Value" >
<option id="Value" value="" label="Выберите объем">Выберите объем</option>
<option <?php if($Value == 1) { ?> selected <?php }; ?>  id="Value1" value="1" label="Малый">Малый</option>
<option <?php if($Value == 2) { ?> selected <?php }; ?> id="Value2" value="2" label="Средний">Средний</option>
<option <?php if($Value == 3) { ?> selected <?php }; ?> id="Value3" value="3" label="Большой">Большой</option>
<option <?php if($Value == 4) { ?> selected <?php }; ?> id="Value4" value="4" label="Сверхбольшой">Сверхбольшой</option>
<option <?php if($Value == 5) { ?> selected <?php }; ?> id="Value5" value="5" label="Другое">Другое</option>
</select>
<?php
if($ErrorValue == 1) {
echo "[!] Ошибка";
}
?>
<aside class="PostJobHelpHidden" id="HelpValue">
Объем:
Малый - до 1800 знаков (1 страница) или до 10 минут видео или аудио
<br>
Средний - от 1 до 10 страниц или от 10 до 30 минут видео или аудио
<br>
Большой - от 10 до 100 страниц или от 30 до 60 минут видео или аудио
<br>
Сверхбольшой - от 100 страниц или свыше 60 минут видео или аудио
<br>
Другое - если Локализируется программа, где нет четкого размера 
</aside>
</div>




<br>
<hr>
<br>

<!-- Вариации ПОИСКА ФРИЛАНСЕРА  -->

<div class="Post_Job_Form">
<label class="Post_Job_Label">Как искать: Выборочно</label>
</div>

<br>
<hr>
<br>


<!-- Тестовое задание -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Тест. задание:</label>
<input type="hidden" name="TestHidden" id="TestHidden" value="0">
<!--Раздел тестового задания при не нажатой кнопке создать тестовое задание -->
<div id="testdiv1" class="">
<input type="button" id="showtest" value="Добавить тестовое задание">
Создать тестовое задание для проверки знаний фрилансера
</div>
</div>

<!--Раздел тестового задания при нажатии кнопки создать тестовое задание -->
<div id="testdiv2" class="" class style="display: none">

<input type="button" id="hidetest" value="удалить тестовое задание">

<div class="">
Введите описание тестового задания
</div>

<div class="">
<textarea name="testdescription" id="testdescription" rows="6" cols="60" class="Post_Job_Form_Elements " maxlength="100"></textarea>
</div>

<div class="">
Введите тестовое задание (текст для перевода не более 1800 символов)
</div>

<div class="">
<textarea name="testtext" id="testtext" rows="24" cols="80" class="Post_Job_Form_Elements " maxlength="1800"></textarea>
</div>

</div>


<br>
<hr>
<br>

<!-- Время выполнение работы приблизительно -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Сроки:</label>
<select name="Duration" class="Post_Job_Form_Elements " id="Duration">
<option  value="" label="Выберите">Выберите</option>
<option <?php if($Duration == 1) { ?> selected <?php }; ?>  value="1" label="Больше 6 месяцев">Больше 6 месяцев</option>
<option <?php if($Duration == 2) { ?> selected <?php }; ?> value="2" label="3-6 месяцев">3-6 месяцев</option>
<option <?php if($Duration == 3) { ?> selected <?php }; ?> value="3" label="1-3 месяца">1-3 месяца</option>
<option <?php if($Duration == 4) { ?> selected <?php }; ?> value="4" label="Меньше месяца">Меньше месяца</option>
<option <?php if($Duration == 5) { ?> selected <?php }; ?> value="5" label="Меньше недели">Меньше недели</option>
<option <?php if($Duration == 6) { ?> selected <?php }; ?> value="6" label="1 день">1 день</option>
</select>
<?php
if($ErrorDuration == 1) {
echo "[!] Ошибка";
}
?>
<aside class="PostJobHelpHidden" id="HelpDuration">
Подсказка выбора сроков
</aside>
</div>


<!-- Приватность задания -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Приватность:</label>
<input <?php if($Private == 1) { ?> checked <?php }; ?> type="checkbox" name="Private" id="Private" value="1">Сделать приватным<Br>
</div>

<!-- Предоплата задания -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Предоплата:</label>
<select name="Prepayment" class="Post_Job_Form_Elements " id="Prepayment">
<option <?php if($Prepayment == 0) { ?> selected <?php }; ?>  value="0" label="Нет предоплаты">Нет предоплаты</option>
<option <?php if($Prepayment == 1) { ?> selected <?php }; ?>  value="1" label="25%">25%</option>
<option <?php if($Prepayment == 2) { ?> selected <?php }; ?> value="2" label="50%">50%</option>
<option <?php if($Prepayment == 3) { ?> selected <?php }; ?> value="3" label="75%">75%</option>
<option <?php if($Prepayment == 4) { ?> selected <?php }; ?> value="4" label="100%">100%</option>
</select>

<aside class="PostJobHelpHidden" id="HelpPrepayment">
Подсказка выбора предоплаты
</aside>
</div>

<hr>

<!-- Раздел формирования цены -->


<div class="Post_Job_Form">
<label class="Post_Job_Label">Цена: </label>
<input  type="number" value="<?php echo $Price; ?>" value maxlength="10" name="Price" id="Price"  class="Post_Job_Form_Elements ">
<?php
if($ErrorPrice == 1) {
echo "[!] Ошибка";
}
?>
<aside class="PostJobHelpHidden" id="HelpPrice">
Подсказка выбора цены
</aside>
</div>

<hr>

<!-- Сообщение для фрилансера -->

<div class="Post_Job_Form">
<label class="Post_Job_Label">Сообщение для фрилансера:</label>
<textarea  value maxlength="1000" name="DescriptionFreelancerJob" id="DescriptionFreelancerJob" rows="20" cols="80"  class="Post_Job_Form_Elements " placeholder="Сообзение фрилансеру"></textarea>
</div>

<!-- кнопка создать работу -->
<div class="Post_Job_Form">
<input type="submit" class="" id="submit" value="Создать работу"/>
</div>

</form>

<?php
}  // закрытие скобки от <!-- Информация о работе, если есть &jb=N -->
?>
</section>
<!-- окончание раздела с preview = 0 -->
<?php 
}  
?>

<!-- начало раздела с preview = 1 -->
<?php
if($preview == 1){
?>
<section class="FW_middle">

<h1>Предпросмотр работы</h1>

<div class="Post_Job_Form">
<label class="Post_Job_Label">Язык </label>
 <?php echo "$LanguageFrom - $LanguageTo" ?> 
 </div>
 <div class="Post_Job_Form">
<label class="Post_Job_Label">Стиль </label>
 <?php echo "$Style" ?> 
 </div>
 <div class="Post_Job_Form">
<label class="Post_Job_Label">Название </label>
 <?php echo "$Title" ?> 
 </div>
 <div class="Post_Job_Form">
<label class="Post_Job_Label">Описание </label>
 <?php echo "$Description" ?> 
 </div>
 <div class="Post_Job_Form">
<label class="Post_Job_Label">Тип </label>
 <?php echo "$Type" ?> 
 </div>
 <div class="Post_Job_Form">
 <label class="Post_Job_Label">Объем </label>
 <?php echo "$Value" ?> 
  </div>
 <div class="Post_Job_Form">
  <label class="Post_Job_Label">Вид поиска</label>
 <?php echo "$Search" ?> 
  </div>
 <div class="Post_Job_Form">
  <label class="Post_Job_Label">Приватность </label>
 <?php echo "$Private" ?> 
  </div>
 <div class="Post_Job_Form">
  <label class="Post_Job_Label">Сроки</label>
 <?php echo "$Duration" ?> 
  </div>
 <div class="Post_Job_Form">
  <label class="Post_Job_Label">Предоплата</label>
 <?php echo "$Prepayment" ?> 
  </div>
 <div class="Post_Job_Form">
  <label class="Post_Job_Label">Цена</label>
 <?php echo "$Price" ?> 
 </div>

<form name="NewJob" id="NewJob" class="" method="post" >

<input type="hidden" name="LanguageFrom" value="<?php echo "$LanguageFrom" ?>" />
<input type="hidden" name="LanguageTo" value="<?php echo "$LanguageTo" ?>" />
<input type="hidden" name="Style" value="<?php echo "$Style" ?>" />
<input type="hidden" name="Title" value="<?php echo "$Title" ?>" />
<input type="hidden" name="Description" value="<?php echo "$Description" ?>" />
<input type="hidden" name="Type" value="<?php echo "$Type" ?>" />
<input type="hidden" name="SubType" value="<?php echo "$SubType" ?>" />
<input type="hidden" name="Value" value="<?php echo "$Value" ?>" />
<input type="hidden" name="SubValue" value="<?php echo "$SubValue" ?>" />
<input type="hidden" name="Search" value="<?php echo "$Search" ?>" />
<input type="hidden" name="Private" value="<?php echo "$Private" ?>" />
<input type="hidden" name="TestHidden" value="<?php echo "$TestHidden" ?>" />
<input type="hidden" name="Duration" value="<?php echo "$Duration" ?>" />
<input type="hidden" name="Prepayment" value="<?php echo "$Prepayment" ?>" />
<input type="hidden" name="Price" value="<?php echo "$Price" ?>" />
<input type="hidden" name="testdescription" value="<?php echo "$testdescription" ?>" />
<input type="hidden" name="testtext" value="<?php echo "$testtext" ?>" />


<div class="Post_Job_Form">
<input type="submit" name="Post_Job" class="" id="submit" value="Создать задание"/>
</div>

<div class="Post_Job_Form">
<input type="submit" name="Back_Job" class="" id="submit" value="Вернуться к редактированию"/>
</div>

<div class="Post_Job_Form">
<input type="submit" name="Save_Job" class="" id="submit" value="Отменить и Сохранить как шаблон"/>
</div>
</form>


</section>
<?php
}
?>
<!-- окончание раздела с preview = 1 -->

<!-- ОКОНЧАНИЕ Создание нового задания для этого пользователя -->


<!-- раздел перехода к ошибке, если preview не равно ни 1 ни 0 -->
<?php
if($preview !=1 && $preview !=0){
?>
<section class="FW_middle">
ошибка формирования страницы
</section>
<?php
}
?>
<!-- ------------------------------------------- -->


<script type="text/javascript" src="jquery-1.5.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="jquery.min.js" charset="utf-8"></script>



<!--Скрипт на открытие разных типов разных div  -->


<script>

//var divs = $( "div[class^='type']" ).hide();
//$("#Type").bind('change',function(){
//         divs.hide().filter("div."+this[ this.selectedIndex ].id ).show();
//});


// ВЫВОД ДИНАМИЧЕСКИХ ПОДСКАЗОК

// ---------------------   ДИНАМИЧЕСКАЯ ПРОВЕРКА LANGUAGE   ------------------------------
var HelpLanguage = document.getElementById('LanguageTo');
 // для подсказки
var HelpLanguageForm = document.getElementById('HelpLanguage');
 // для ошибки
var LanguageErrorForm = document.getElementById('ErrorLanguageTo');
// при фокусе
HelpLanguage.onfocus = function() {
  HelpLanguageForm.className = 'PostJobHelp';
}
// при окончании фокуса
HelpLanguage.onblur = function() {
  HelpLanguageForm.className = 'PostJobHelpHidden';
  // для ошибки нахождение value 
  LanguageValue = document.getElementById("LanguageTo").value;
  // проверка на ошибку
  if (LanguageValue != 1 && LanguageValue !=2 ) {  // если не выбран ни один из вариантов
    LanguageErrorForm.className = 'PostJobError';  // то показывается ошибка
  } else {											// иначе
    LanguageErrorForm.className = 'PostJobHelpHidden'; // не показывается ошибка
  }
  }


// ---------------------   ДИНАМИЧЕСКАЯ ПРОВЕРКА STYLE   ------------------------------
var HelpStyle = document.getElementById('Style');
var HelpStyleForm = document.getElementById('HelpStyle');

HelpStyle.onfocus = function() {
  HelpStyleForm.className = 'PostJobHelp';
}
HelpStyle.onblur = function() {
  HelpStyleForm.className = 'PostJobHelpHidden';
}

var HelpTitle = document.getElementById('Title');
var HelpTitleForm = document.getElementById('HelpTitle');

HelpTitle.onfocus = function() {
  HelpTitleForm.className = 'PostJobHelp';
}
HelpTitle.onblur = function() {
  HelpTitleForm.className = 'PostJobHelpHidden';
}

var HelpDescription = document.getElementById('Description');
var HelpDescriptionForm = document.getElementById('HelpDescription');

HelpDescription.onfocus = function() {
  HelpDescriptionForm.className = 'PostJobHelp';
}
HelpDescription.onblur = function() {
  HelpDescriptionForm.className = 'PostJobHelpHidden';
}

var HelpType = document.getElementById('Type');
var HelpTypeForm = document.getElementById('HelpType');

HelpType.onfocus = function() {
  HelpTypeForm.className = 'PostJobHelp';
}
HelpType.onblur = function() {
  HelpTypeForm.className = 'PostJobHelpHidden';
}

var HelpValue = document.getElementById('Value');
var HelpValueForm = document.getElementById('HelpValue');

HelpValue.onfocus = function() {
  HelpValueForm.className = 'PostJobHelp';
}
HelpValue.onblur = function() {
  HelpValueForm.className = 'PostJobHelpHidden';
}


var HelpDuration = document.getElementById('Duration');
var HelpDurationForm = document.getElementById('HelpDuration');

HelpDuration.onfocus = function() {
  HelpDurationForm.className = 'PostJobHelp';
}
HelpDuration.onblur = function() {
  HelpDurationForm.className = 'PostJobHelpHidden';
}

var HelpValue = document.getElementById('Value');
var HelpValueForm = document.getElementById('HelpValue');

HelpValue.onfocus = function() {
  HelpValueForm.className = 'PostJobHelp';
}
HelpValue.onblur = function() {
  HelpValueForm.className = 'PostJobHelpHidden';
}

var HelpPrepayment = document.getElementById('Prepayment');
var HelpPrepaymentForm = document.getElementById('HelpPrepayment');

HelpPrepayment.onfocus = function() {
  HelpPrepaymentForm.className = 'PostJobHelp';
}
HelpPrepayment.onblur = function() {
  HelpPrepaymentForm.className = 'PostJobHelpHidden';
}

var HelpPrice = document.getElementById('Price');
var HelpPriceForm = document.getElementById('HelpPrice');

HelpPrice.onfocus = function() {
  HelpPriceForm.className = 'PostJobHelp';
}
HelpPrice.onblur = function() {
  HelpPriceForm.className = 'PostJobHelpHidden';
}

// ------------------------------------------------------------------------------ //

<!-- при нажатии на добавить тестовое задание менять значение testhidden и при удалить тоже -->

$('#showtest').bind('click', function(){
  $('#testdiv1').hide(); <!--скрываем div с кнопкой создать тест -->
  $('#testdiv2').show(); <!--показывает div с формой создания теста -->
  $("#TestHidden").val("1");  <!--jQuery добавляем нужно значение в поле testhidden -->
});

$('#hidetest').bind('click', function(){
  $('#testdiv2').hide(); <!--скрывает div с формой создания теста -->
  $('#testdiv1').show(); <!--показывает div с кнопкой создать тест -->
  $("#TestHidden").val("0");  <!--jQuery добавляем нужно значение в поле testhidden -->
});


<!-- Сворачивание и разворачивание рамки с шаблонами -->
$('#HideTemplate').bind('click', function(){
  $('#TemplateDiv1').hide(); <!--скрывает div  -->
  $('#TemplateDiv2').show(); <!--показывает div  -->
});
$('#ShowTemplate').bind('click', function(){
  $('#TemplateDiv2').hide(); <!--скрывает div-->
  $('#TemplateDiv1').show(); <!--показывает div  -->
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
<a href="/mysite/news.php">Новости</a>
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