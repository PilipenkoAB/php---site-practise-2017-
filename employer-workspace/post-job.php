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


/* Задание переменных об ошибках заполнения данных при создании работы */	

	$Error = 0; // если 0 то нет ошибок, если 1 - то ошибки в заполнении полей
	
	$ErrorLanguageFrom=0;	// Переменная ошибки "С какого языка переводить"
	$ErrorLanguageTo=0;		// Переменная ошибки "На какой язык переводить"
	$ErrorStyle=0;			// Переменная ошибки "Стиля"
	$ErrorTitle=0;			// Переменная ошибки "Названия задания"
	$ErrorDescription=0;	// Переменная ошибки "Описания задания"
	$ErrorType=0;			// Переменная ошибки "Типа"
	$ErrorValue=0;			// Переменная ошибки "Объема"
	$ErrorDuration=0;		// Переменная ошибки "Сроков"
	$ErrorSearch=0;			// Переменная ошибки "Вида поиска фрилансеров"
	$ErrorPrice=0;			// Переменная ошибки "Цены"
	$ErrorPrepayment=0;		// Переменная ошибки "Предоплаты"
	$ErrorTestJob=0;		// Переменная ошибки "Тестового задания"

	
/* Задание переменной об виде страницы : "Формирование задания" или "Предпросмотр" */	
	
	$Page_Type = 0; // Если 0 - то тип страницы - "Формирование задания", если 1 - тип страницы "Предпросмотр"
	
/* Задание всех возможных переменных для формирования задания равных NULL */		
	$Title=NUll;				// Переменная "Названия задания"
	$Description=NUll;			// Переменная "Описания задания"
	$Style=NUll;				// Переменная "Стиля"
	$Type=NUll;					// Переменная "Типа"
	$Search=NUll;				// Переменная "Вида поиска фрилансеров"
	$Private=NUll;				// Переменная "Приватность"
	$Duration=NUll;				// Переменная "Сроков"
	$Prepayment=NUll;			// Переменная "Предоплаты"
	$Price=NUll;				// Переменная "Цены"
	$MinPrice=NUll;				// Переменная "Цены"
	$MaxPrice=NUll;				// Переменная "Цены"
	$TestText=Null;				// Переменная "Текста тестового задания"
	$TestDescription=Null;		// Переменная "Описание тестового задания"
	$LanguageFrom1=0;
	$LanguageTo1=0;
	$LanguageFrom2=0;	
	$LanguageTo2=0;
	$LanguageFrom3=0;	
	$LanguageTo3=0;
	$LanguageFrom4=0;	
	$LanguageTo4=0;
	$LanguageFrom5=0;	
	$LanguageTo5=0;	
	
	$File=Null;
	
	



			
			
/* Если пришли все данные на Формирование задания */		
	if(isset($_POST['Title']) && isset($_POST['Description']) && isset($_POST['Pairs']) && isset($_POST['Style']) && isset($_POST['Type']) && isset($_POST['Search']) && isset($_POST['TestHidden']) && isset($_POST['Duration']) && isset($_POST['Prepayment']) && isset($_POST['Price'])  ) {

/* Задание переменных из пришедших значений для формирования задания БЕЗ специальных символов */	
	$LanguagePairs=htmlspecialchars($_POST['Pairs']); // Переменная "Всех выбранных пар"
	$Style=htmlspecialchars($_POST['Style']);	 			// Переменная "Стиля"
	$Title=htmlspecialchars($_POST['Title']);		 		// Переменная "Названия задания"
	$Description=htmlspecialchars($_POST['Description']);	// Переменная "Описания задания"
	$Type=htmlspecialchars($_POST['Type']);					// Переменная "Типа"
	$Search=htmlspecialchars($_POST['Search']);				// Переменная "Вида поиска фрилансера"
	$TestHidden=htmlspecialchars($_POST['TestHidden']);		// Переменная "Существования тестового задания или его отсутствия"
	$Duration=htmlspecialchars($_POST['Duration']);			// Переменная "Длительности"
	$Price=htmlspecialchars($_POST['Price']);				// Переменная "Цены"
	$Prepayment=htmlspecialchars($_POST['Prepayment']);		// Переменная "Предоплаты"
	

/* Задание Времени прихода данных (Для указания времени регистрации задания) */	
	$DataCreation=date('Y-m-d H:i:s');
	
	
/* Задание пустых перменных для Тестового задания */	
	$TestDescription=0; 	// Переменная "Описание тестового задания"
	$TestText=0;			// Переменная "Текст тестового задания"
	


/* Раздел работы с выбором пар языка и валидацией языковых пар */	
	
if(empty($LanguagePairs)){
	$error = 1;
	$ErrorLanguageFrom=1;
	$ErrorLanguageTo=1;
}else{
	$LengthPairs=strlen($LanguagePairs); // Сколько символов в строке


	// Если определенное кол-во символов, ты выделять те символы, которые обозначают языки
if( $LengthPairs == 3) {
	$LanguageFrom1=$LanguagePairs{0};
	$LanguageTo1=$LanguagePairs{2};
	
	/* Валидация */
	if($LanguageFrom1 == 0) { // Если не выбрано "С какого языка" переводить - ошибка
	$Error = 1;
	$ErrorLanguageFrom=1;
		}
	if($LanguageTo1 == 0) { // Если не выбрано "На какой язык" переводить - ошибка
	$Error = 1;
	$ErrorLanguageTo=1;
		}
	if($LanguageFrom1 == $LanguageTo1){ // Если совпадает "С какого языка" - "На какой язык" переводить - ошибка
	$Error = 1;
	$ErrorLanguageFrom=1;
	$ErrorLanguageTo=1;
	}	
	
}elseif ($LengthPairs == 7) {
	$LanguageFrom1=$LanguagePairs{0};	
	$LanguageTo1=$LanguagePairs{2};
	$LanguageFrom2=$LanguagePairs{4};	
	$LanguageTo2=$LanguagePairs{6};
	
		/* Валидация */
	if($LanguageFrom1 == 0 || $LanguageFrom2 == 0) { // Если не выбрано "С какого языка" переводить - ошибка
	$Error = 1;
	$ErrorLanguageFrom=1;
		}
	if($LanguageTo1 == 0 || $LanguageTo2 == 0) { // Если не выбрано "На какой язык" переводить - ошибка
	$Error = 1;
	$ErrorLanguageTo=1;
		}
	if(($LanguageFrom1 == $LanguageTo1) || ($LanguageFrom2 == $LanguageTo2)){ // Если совпадает "С какого языка" - "На какой язык" переводить - ошибка
	$Error = 1;
	$ErrorLanguageFrom=1;
	$ErrorLanguageTo=1;
	}	
}elseif ($LengthPairs == 10) {
	$LanguageFrom1=$LanguagePairs{0};
	$LanguageTo1=$LanguagePairs{2};
	$LanguageFrom2=$LanguagePairs{4};	
	$LanguageTo2=$LanguagePairs{6};
	$LanguageFrom3=$LanguagePairs{8};	
	$LanguageTo3=$LanguagePairs{10};
	
		/* Валидация */
	if($LanguageFrom1 == 0 || $LanguageFrom2 == 0 || $LanguageFrom3 == 0) { // Если не выбрано "С какого языка" переводить - ошибка
	$Error = 1;
	$ErrorLanguageFrom=1;
		}
	if($LanguageTo1 == 0 || $LanguageTo2 == 0 || $LanguageTo3 == 0) { // Если не выбрано "На какой язык" переводить - ошибка
	$Error = 1;
	$ErrorLanguageTo=1;
		}
	if(($LanguageFrom1 == $LanguageTo1) || ($LanguageFrom2 == $LanguageTo2) || ($LanguageFrom3 == $LanguageTo3)){ // Если совпадает "С какого языка" - "На какой язык" переводить - ошибка
	$Error = 1;
	$ErrorLanguageFrom=1;
	$ErrorLanguageTo=1;
	}		
}elseif ($LengthPairs == 14) {
	$LanguageFrom1=$LanguagePairs{0};
	$LanguageTo1=$LanguagePairs{2};
	$LanguageFrom2=$LanguagePairs{4};	
	$LanguageTo2=$LanguagePairs{6};
	$LanguageFrom3=$LanguagePairs{8};	
	$LanguageTo3=$LanguagePairs{10};
	$LanguageFrom4=$LanguagePairs{12};	
	$LanguageTo4=$LanguagePairs{14};
	
		/* Валидация */
	if($LanguageFrom1 == 0 || $LanguageFrom2 == 0 || $LanguageFrom3 == 0 || $LanguageFrom4 == 0) { // Если не выбрано "С какого языка" переводить - ошибка
	$Error = 1;
	$ErrorLanguageFrom=1;
		}
	if($LanguageTo1 == 0 || $LanguageTo2 == 0 || $LanguageTo3 == 0 || $LanguageTo4 == 0) { // Если не выбрано "На какой язык" переводить - ошибка
	$Error = 1;
	$ErrorLanguageTo=1;
		}
	if(($LanguageFrom1 == $LanguageTo1) || ($LanguageFrom2 == $LanguageTo2) || ($LanguageFrom3 == $LanguageTo3) || ($LanguageFrom4 == $LanguageTo4)){ // Если совпадает "С какого языка" - "На какой язык" переводить - ошибка
	$Error = 1;
	$ErrorLanguageFrom=1;
	$ErrorLanguageTo=1;
	}		
}elseif ($LengthPairs == 18) {
	$LanguageFrom1=$LanguagePairs{0};
	$LanguageTo1=$LanguagePairs{2};
	$LanguageFrom2=$LanguagePairs{4};	
	$LanguageTo2=$LanguagePairs{6};
	$LanguageFrom3=$LanguagePairs{8};	
	$LanguageTo3=$LanguagePairs{10};
	$LanguageFrom4=$LanguagePairs{12};	
	$LanguageTo4=$LanguagePairs{14};
	$LanguageFrom5=$LanguagePairs{16};	
	$LanguageTo5=$LanguagePairs{18};
	
		/* Валидация */
	if($LanguageFrom1 == 0 || $LanguageFrom2 == 0 || $LanguageFrom3 == 0 || $LanguageFrom4 == 0 || $LanguageFrom5 == 0) { // Если не выбрано "С какого языка" переводить - ошибка
	$Error = 1;
	$ErrorLanguageFrom=1;
		}
	if($LanguageTo1 == 0 || $LanguageTo2 == 0 || $LanguageTo3 == 0 || $LanguageTo4 == 0 || $LanguageTo5 == 0) { // Если не выбрано "На какой язык" переводить - ошибка
	$Error = 1;
	$ErrorLanguageTo=1;
		}
	if(($LanguageFrom1 == $LanguageTo1) || ($LanguageFrom2 == $LanguageTo2) || ($LanguageFrom3 == $LanguageTo3) || ($LanguageFrom4 == $LanguageTo4) || ($LanguageFrom5 == $LanguageTo5)){ // Если совпадает "С какого языка" - "На какой язык" переводить - ошибка
	$Error = 1;
	$ErrorLanguageFrom=1;
	$ErrorLanguageTo=1;
		}
	}
}
	
	
/* ----------- Валидация Пришедших Данных ---------- */		

		
/* Валидация выбора "Стиля" */		
	if($Style != 1 && $Style != 2 && $Style != 3 && $Style != 4 && $Style != 5 && $Style != 6 && $Style != 7) { // Если не выбран "Стиль" - ошибка
	$Error = 1;
	$ErrorStyle=1;
		}
		
/* Валидация "Названия задания" и "Описания задания" */
	$TitleCheck=trim($Title); 				// Проверка "Названия задания" на пробелы (чтобы нельзя было прислать просто пробелы)
	$DescriptionCheck=trim($Description); 	// Проверка "Описания задания" на пробелы (чтобы нельзя было прислать просто пробелы)
		if(empty($TitleCheck)){			 	// Если "Название задания" пустое - ошибка
	$Error = 1; 
	$ErrorTitle=1;
	}	
	if(empty($DescriptionCheck)) { 			// Если "Описание задания" пустое - ошибка 
	$Error = 1; 
	$ErrorDescription=1;
		}
		
/* Валидация "Типа" */
	if($Type != 1 && $Type != 2 && $Type != 3 && $Type != 4 && $Type != 5) {  // Если не выбран "Тип" - ошибка
	$Error = 1;
	$ErrorType=1;
	} 
	
/* Валидация "Типа поиска фрилансеров" */
	if($Search != 1 && $Search != 2) {	 // Если не выбран "Тип поиска фрилансеров" - ошибка
	$Error = 1;
	$ErrorSearch=1;
	} 
	
	
/* --- Валидация "Тестового задания" ---*/
	if($TestHidden != 0 && $TestHidden != 1) {	// Если "TestHidden" принимает значение не 1 или не 0 - ошибка
	$Error = 1;
	} 
	if($TestHidden == 1 && isset($_POST['TestDescription']) && isset($_POST['TestText'])) { // Если "TestHidden" принимает значение 1 и пришли данные о тестовом задании
	$TestDescription=htmlspecialchars($_POST['TestDescription']);							// Переменная "Описания тестового задания"
	$TestText=htmlspecialchars($_POST['TestText']);											// Переменная "Текста тестового задания" 
    $TestDescriptionCheck=trim($TestDescription); 						// Проверка "Описание тестового задания" на пробелы (чтобы нельзя было прислать просто пробелы)
	$TestTextCheck=trim($TestText); 									// Проверка "Текст тестового задания" на пробелы (чтобы нельзя было прислать просто пробелы)
	if(empty($TestDescriptionCheck) || empty($TestTextCheck)) { 	// Если "Описание тестового задания" или "Текст тестового задания" пустое - ошибка 
	$Error = 1; 
	$ErrorTestJob = 1;
		}
	} 

	
/* Валидация "Сроков" */
	if($Duration != 1 && $Duration != 2 && $Duration != 3 && $Duration != 4 && $Duration != 5 && $Duration != 6) { // Если не выбраны "Сроки" - ошибка
	$Error = 1;
	$ErrorDuration=1;
	} 
	
/* Валидация "Приватности" */
	 If (isset($_POST['Private'])) { 	// Если пришли данные о "Приватности"
	$Private=1;							// "Приватность" равна 1 (т.е. Задание является приватным)
	} else {							// иначе
	$Private=0;							// "Приватность" равна 0 (т.е. Задание не является приватным)
		}
	if ($Private != 0 && $Private != 1) { // Если "Приватность" не имеет значения 0 или 1 - ошибка
	$Error = 1;
	}

/* Валидация "Предоплаты" */
	if($Prepayment != 1 && $Prepayment != 2 && $Prepayment != 3 && $Prepayment != 4 && $Prepayment != 0 ) {	// Если не выбрана "Предоплата" - ошибка
	$Error = 1;
	} 
	

	
	
	
	
	// РАЗДЕЛ ВВОДА ФАЙЛА ??
	
	if(isset($_FILES['UploadFile']))	{	


//$TmpFile =($_FILES['UploadFile']['tmp_name']); 	

// Загрузка файла
if(isset($_FILES['UploadFile']))	{	
	move_uploaded_file($_FILES['UploadFile']['tmp_name'],"files/{$_FILES['UploadFile']['name']}");
	
	$FileName =($_FILES['UploadFile']['name']); 
if($_FILES['UploadFile']['error']>0)
{
switch ($_FILES['UploadFile']['error'])
{
case 1: //echo 'Файл превышает максимальный серверный размер для пересылки';
break;
case 2: //echo 'Файл превышает максимальный размер файла';
break;
case 3: //echo 'Файл загрузился только частично';
break;
case 4: //echo 'Никакой файл не загрузился';
break;
}
}
else
{
$RandNameFile = rand(1, 10000);
$File =($_FILES['UploadFile']['name']); 
$File = "$User_Id"."_"."$RandNameFile"."_"."$File";
rename("files/{$_FILES['UploadFile']['name']}", "files/$User_Id"."_"."$RandNameFile"."_"."{$_FILES['UploadFile']['name']}");	  // переименование файла - вопрос с форматом только...
}

}
}

// После предпросмотра (занос из скрытых полей предпросмотра)
if(isset($_POST['File'])) {
	$File=($_POST['File']);	
}
	//


	
	
	
	// РАЗДЕЛ ФОРМИРОВАНИЯ ЦЕНЫ  
	
	/* Валидация "Цены" */
	if($Price != 1 && $Price != 2 && $Price != 3 && $Price != 4 && $Price != 5 && $Price != 6 && $Price != 7 && $Price != 8  ) {	// Если не выбрана "Цена" - ошибка
	$Error = 1;
	} 

	if($Price == 1) {
	$MinPrice=10;
	$MaxPrice=30;
	} elseif ($Price == 2) {
	$MinPrice=30;
	$MaxPrice=250;
	} elseif ($Price == 3) {
	$MinPrice=250;
	$MaxPrice=750;
	} elseif ($Price == 4) {
	$MinPrice=750;
	$MaxPrice=1500;
	} elseif ($Price == 5) {
	$MinPrice=1500;
	$MaxPrice=3000;	
	} elseif ($Price == 6) {
	$MinPrice=3000;
	$MaxPrice=5000;
	} elseif ($Price == 7) {
	$MinPrice=5000;
	$MaxPrice=100000;	
	} elseif ($Price == 8) {
		$MinPrice=3333;
	$MaxPrice=333333;
 if(isset($_POST['MinPrice']) && isset($_POST['MaxPrice'])) {
	
	$MinPrice=htmlspecialchars($_POST['MinPrice']);	// Переменная "Минимальная цена"	
	$MaxPrice=htmlspecialchars($_POST['MaxPrice']);	// Переменная "Максимальная цена"		
	//цена не может быть ниже подсчитанной минимальной цены
		if($MaxPrice < $MinPrice) {
	$Error = 1;
	$ErrorPrice=1;
		}	
		if($MaxPrice < 10 ) {
	$Error = 1;
}		
		if($MinPrice < 10 ) {
	$Error = 1;
}		

			}else {
		$Error = 1;
	} 
	}

	

	//ОКОНЧАНИЕ РАЗДЕЛА ФОРМИРОВАНИЯ ЦЕНЫ


/* Окончание Валидации, проверка на наличие ошибок в валидации. Если Ошибок нет (Error == 0) то переходим дальше, иначе вывод сообщения об ошибках */
	if($Error !== 1) {
	
	
	/* если Тип страницы - "Формирование Задания" (Page_Type = 0) то задаем Тип страницы - "Предпросмотр" (Page_Type = 1) */
	if($Page_Type == 0) {
	$Page_Type = 1;
	}



	
	
/* Данный раздел - "Предпросмотр". Три варианта: Публикация, Отмена, Сохранения шаблона */
	/* Публикация */
		if(isset($_POST['Post_Job'])) {  	// Если была нажата кнопка Создания задания\


		
/* Заносим в БД новую работу */		
if(!$BD->query("INSERT INTO `Jobs` (`EmployerId`,`LanguageFrom1`,`LanguageTo1`,`LanguageFrom2`,`LanguageTo2`,`LanguageFrom3`,`LanguageTo3`,`LanguageFrom4`,`LanguageTo4`,`LanguageFrom5`,`LanguageTo5`,`Style`,`Title`,`Description`,`Type`,`Search`,`Private`,`Prepayment`,`Duration`,`StartMinPrice`,`StartMaxPrice`,`DataCreation`,`TestJobDescription`,`TestJobText`,`File`) VALUES ('$User_Id', '$LanguageFrom1','$LanguageTo1','$LanguageFrom2','$LanguageTo2','$LanguageFrom3','$LanguageTo3','$LanguageFrom4','$LanguageTo4','$LanguageFrom5','$LanguageTo5','$Style', '$Title', '$Description', '$Type', '$Search','$Private','$Prepayment','$Duration','$MinPrice','$MaxPrice','$DataCreation','$TestDescription','$TestText','$File')")){
 printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
}else{
header("location: my-jobs.php");			// если операция прошла успешно
	 }		
}

	/* Отмена (Возврат назад) */
if(isset($_POST['Back_Job'])) {		// Если была нажата кнопка Отмена
$Page_Type = 0 ;		// задаем значение $Page_Type = 0 (Возвращаемся назад)
}
		
	/* Сохранение шаблона */		
		if(isset($_POST['Save_Job'])) {		// Если была нажата кнопка Сохранить шаблон
/* Заносим в БД новый шаблон */	
	if(!$BD->query("INSERT INTO `TemplateJob` (`EmployerId`,`LanguageFrom1`,`LanguageTo1`,`LanguageFrom2`,`LanguageTo2`,`LanguageFrom3`,`LanguageTo3`,`LanguageFrom4`,`LanguageTo4`,`LanguageFrom5`,`LanguageTo5`,`Style`,`Title`,`Description`,`Type`,`Value`,`Search`,`Private`,`Prepayment`,`Duration`,`StartPrice`,`TestJobDescription`,`TestJobText`) VALUES ('$User_Id', '$LanguageFrom1','$LanguageTo1','$LanguageFrom2','$LanguageTo2','$LanguageFrom3','$LanguageTo3','$LanguageFrom4','$LanguageTo4','$LanguageFrom5','$LanguageTo5', '$Style', '$Title', '$Description', '$Type', '$Value','$Search','$Private','$Prepayment','$Duration','$Price','$TestDescription','$TestText')")){
	printf("Ошибка: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
	}else{
	header("location: my-jobs.php");			// если операция прошла успешно
		}	
	}
}	
	
}		
	
	
	
/* Если пришел запрос GET "Template" для выбора шаблона */
	 if( isset($_GET['Template'])){
	 
	$TemplateTitleGET=htmlspecialchars($_GET['Template']);	// Переменная названия шаблона
	
/* Посылаем запрос серверу на выборку информации о текущем шаблоне */	
if ($Template_Check_Information = $BD->query("SELECT * FROM `TemplateJob` WHERE `EmployerId`='$User_Id' AND `Title`='$TemplateTitleGET' ")) { 

/* Выбираем результаты запроса по $Template_Check_Information : */ 
$Template_Check_Information_Data = $Template_Check_Information->fetch_assoc();	

/* Если данный шаблон существует, то произвести ввод переменных по шаблону */ 	
	if(!empty($Template_Check_Information_Data)){
	$LanguageFrom1=$Template_Check_Information_Data['LanguageFrom1'];
	$LanguageTo1=$Template_Check_Information_Data['LanguageTo1'];
	$LanguageFrom2=$Template_Check_Information_Data['LanguageFrom2'];
	$LanguageTo2=$Template_Check_Information_Data['LanguageTo2'];
	$LanguageFrom3=$Template_Check_Information_Data['LanguageFrom3'];
	$LanguageTo3=$Template_Check_Information_Data['LanguageTo3'];
	$LanguageFrom4=$Template_Check_Information_Data['LanguageFrom4'];
	$LanguageTo4=$Template_Check_Information_Data['LanguageTo4'];
	$LanguageFrom5=$Template_Check_Information_Data['LanguageFrom5'];
	$LanguageTo5=$Template_Check_Information_Data['LanguageTo5'];
	$Title=$Template_Check_Information_Data['Title'];
	$Style=$Template_Check_Information_Data['Style'];
	$Description=$Template_Check_Information_Data['Description'];
	$Type=$Template_Check_Information_Data['Type'];
	$Value=$Template_Check_Information_Data['Value'];
	$Search=$Template_Check_Information_Data['Search'];
	$Duration=$Template_Check_Information_Data['Duration'];
	$Private=$Template_Check_Information_Data['Private'];
	$Prepayment=$Template_Check_Information_Data['Prepayment'];
	$Price=$Template_Check_Information_Data['StartPrice'];

		} else {
		echo "net takogo shablone";
		}
/* Освобождаем память $Template_Check_Information */ 
$Template_Check_Information->close(); 	
	}
}
	
?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js" charset="utf-8"></script> <!-- именно эта версия, потому что другие не поддерживают функции для уничтожения тегов в поле див -->
<html>
<head>
<link href="/mysite/styles_index.css" rel="stylesheet" type="text/css" />

</head>



<body class="Main_Body">
<div id="Main_Section">




<!-- Главная навигационная панель -->
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
<a href="post-job.php" class="FW_top_nav_2_href_current">Опубликовать проект</a>
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


<!-- Раздел section, которые отвечает за страницу с Preview = 0 -->

<?php
if($Page_Type == 0) {
?>

<section class="FW_middle">

<!-- Панель, пока неопределенная, которую можно закрыть (является в какой то мере необязательной) -->
<div class="Post_Job_Up_Panel">
Данная панель предназначена для показа последовательности заполнения формы работы в интерактивном виде. Возможно в виде картинке - диаграммы. Или совмещением картинок и текста. В верхнем правом углу можно закрыть, нажав на крестик. Открыть только перезагрузкой страницы.
</div>

<!-- Заголовок "Публикация Проекта" -->
<header class="">
<h1>
<span>Публикация Проекта</span> 
</h1>
</header>

<!-- Объявление об ошибке  на проверке сервера -->
<?php
if($Error == 1) {
?>
<div id="ActivationAd" class="ActivationAd"> 
    <p>ОШИБКА В ЗАПОЛНЕНИИ ДАННЫХ</p><br>
</div>
<?php } ?>
<!-- Окончания объявления об ошибке -->



<hr>


<!-- Форма заполнения данных о работе -->

<form enctype="multipart/form-data" name="NewJob" id="NewJob"  method="post" class="FormPostJob" >
<input type="hidden" name="MAX_FILE_SIZE" value="30000" />


<!-- Ввод Названия задания перевода -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Название проекта</label>
<input type="text" value maxlength="50" name="Title" id="Title"  class="Post_Job_Form_Elements " placeholder="" size="75" style="width: 575px;">

<?php
if($ErrorTitle == 1) {
echo "[!] Ошибка";
}
?>

<aside class="PostJobHelpHidden" id="HelpTitle"> <!-- class="PostJobHelpHidden" - если хочу чтобы появлялось -->
<b>Название</b> - первое, что увидят фрилансеры, поэтому Вы должны четко описать, что Вам нужно, сделав это как можно короче. 
</aside>

</div>




<!-- Ввод Описания задания перевода -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Подробно опишите задание</label>
<textarea  value maxlength="5000" name="Description" id="Description" rows="10" cols="80"  class="Post_Job_Form_Elements " placeholder="" style="width: 575px;"></textarea>

<?php
if($ErrorDescription == 1) {
echo "[!] Ошибка";
}
?>

<aside class="PostJobHelpHidden" id="HelpDescription"> <!-- class="PostJobHelpHidden" - если хочу чтобы появлялось -->
<b>Опишите</b> задание как можно подробней. Укажите наибольшее колличество деталей, таких как объем, стилистику, жанр и т.д. Чем больше будет информации, тем выше шанс найти лучшего исполнителя.
</aside>

</div>








<!-- Добавить файл -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Приложение</label>

<div class="Post_Job_File">
<input type="file" id="file" name="UploadFile" class="AddFile">
<br><br>
<b><ins>Добавьте файл с необходимой информацией для перевода</ins></b>

</div>

</div>


<br>
<hr>
<br>






<!-- Выбор языка перевода -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Выберите пары языков для перевода</label>

<div class="Post_Job_Choice_Language">
<!-- Выбор с какого языка переводить -->


<div class="Post_Job_Choice_Language_From">

<b>С какого языка:</b>

<select name="LanguageFrom" class="Post_Job_Form_Elements " id="LanguageFrom">
<option  value="0" label="Выберите языки">Выберите языки</option>
<option  value="1" label="Русский">Русский</option>
<option  value="2" label="Английский">Английский</option>
<option  value="3" label="Французский">Французский</option>
</select>
<?php
if($ErrorLanguageFrom == 1) {
echo "[!] Ошибка";
}
?>

<aside class="PostJobHelpHidden" id="ErrorLanguageFrom">
[!]
</aside>
</div>

<!-- Выбор на какой язык переводить -->
<div class="Post_Job_Choice_Language_To">

<b>На какой язык:</b>

<select name="LanguageTo" class="Post_Job_Form_Elements " id="LanguageTo">

<option  value="0" label="Выберите языки">Выберите языки</option>
<option  value="1" label="Русский">Русский</option>
<option  value="2" label="Английский">Английский</option>
<option  value="3" label="Французский">Французский</option>
</select>

<?php
if($ErrorLanguageTo == 1) {
echo "[!] Ошибка";
}
?>

<aside class="PostJobHelpHidden" id="ErrorLanguageTo">
[!]
</aside>
</div>

<!-- Кнопка добавить пару -->
<input type="button" name="PairLanguage" id="PairLanguage" value="Добавить пару" class="Post_Job_Language_Button">

<!-- Вывод добавленных пар -->
<select id="chosen_pairs" name="chosen_pairs" multiple="multiple" size="6" class="Post_Job_Pair_Languages" ></select>

<input type="hidden" name="Pairs" id="Pairs" value="">

<!-- Кнопка удалить выбранную пару -->
<br>
<input type="button" name="DeleteLanguage" id="DeleteLanguage" value="Удалить выбранную пару" class="Post_Job_Language_Button" style="  margin-left: 150px;">

</div>

<aside class="Post_Job_Help_Language"> 
<b>Выберите</b> одну или несколько пар языков для перевода. 
<br>
 Для этого вначале выберите "с какого языка" будет осуществляться перевод, а затем "на какой язык" будет осуществляться перевод. После чего нажмите кнопку "добавить пару". Для удаления уже выбранной пары следует нажать на требуемую пару после чего нажать кнопку "удалить выбранную пару".
 <br> 
 Максимальное кол-во пар языков - 5.
</aside>
</div>


<hr>


<!-- Выбор стиля перевода (технический\художественный\итд) -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Выберите стиль</label>

<div class="Post_Job_Style">
<div class="Post_Job_Style_col_1">
<label class="Post_Job_Style_Label">
<input type="radio" name="Style" id="Style_1" value="1" class="">
Общая тематика
</label>
<label class="Post_Job_Style_Label">
<input type="radio" name="Style" id="Style_2" value="2" class="">
Художественный
</label>
<label class="Post_Job_Style_Label">
<input type="radio" name="Style" id="Style_3" value="3" class="">
Технический
</label>
<label class="Post_Job_Style_Label">
<input type="radio" name="Style" id="Style_4" value="4" class="">
Экономический
</label>
</div>
<div class="Post_Job_Style_col_2">
<label class="Post_Job_Style_Label">
<input type="radio" name="Style" id="Style_5" value="5" class="">
Политический
</label>
<label class="Post_Job_Style_Label">
<input type="radio" name="Style" id="Style_6" value="6" class="">
Медицинский
</label>
<label class="Post_Job_Style_Label">
<input type="radio" name="Style" id="Style_7" value="7" class="">
Реклама
</label>
</div>
</div>

<?php
if($ErrorStyle == 1) {
echo "[!] Ошибка";
}
?>
</div>






<!-- Выбор Типа перевода -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Выберите форму перевода</label>

<div class="Post_Job_Choice_Panel">
<div class="Post_Job_Type_Box" id="Post_Job_Type_Box_Form_1">
<label class="Post_Job_Type_Label_1">
<input type="radio" id="Type_1" name="Type" value="1" class="Post_Job_Type_Radio">
<div> Текст </div>
</label>
</div>
<div class="Post_Job_Type_Box" id="Post_Job_Type_Box_Form_2">
<label class="Post_Job_Type_Label_2">
<input type="radio" id="Type_2" name="Type" value="2" class="Post_Job_Type_Radio">
<div> Видео </div>
</label>
</div>
<div class="Post_Job_Type_Box" id="Post_Job_Type_Box_Form_3">
<label class="Post_Job_Type_Label_3">
<input type="radio" id="Type_3" name="Type" value="3" class="Post_Job_Type_Radio">
<div> Аудио </div>
</label>
</div>
<div class="Post_Job_Type_Box" id="Post_Job_Type_Box_Form_4">
<label class="Post_Job_Type_Label_4">
<input type="radio" id="Type_4" name="Type" value="4" class="Post_Job_Type_Radio">
<div> Локализация </div>
</label>
</div>
</div>

<?php
if($ErrorType == 1) {
echo "[!] Ошибка";
}
?>
</div>





<!-- Время выполнение работы приблизительно -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Выберите срок, который дается на выполнение проекта</label>

<div class="Post_Job_Choice_Panel">
<div class="Post_Job_Duration_Box" id="Post_Job_Duration_Box_Form_1">
<label class="Post_Job_Duration_Label">
<input type="radio" id="Duration_1" name="Duration" value="1" class="Post_Job_Type_Radio">
<div> больше 6 месяцев </div>
</label>
</div>
<div class="Post_Job_Duration_Box" id="Post_Job_Duration_Box_Form_2">
<label class="Post_Job_Duration_Label">
<input type="radio" id="Duration_2" name="Duration" value="2" class="Post_Job_Type_Radio">
<div> 3 - 6 месяцев </div>
</label>
</div>
<div class="Post_Job_Duration_Box" id="Post_Job_Duration_Box_Form_3">
<label class="Post_Job_Duration_Label">
<input type="radio" id="Duration_3" name="Duration" value="3" class="Post_Job_Type_Radio">
<div> 1 - 3  месяца </div>
</label>
</div>
<div class="Post_Job_Duration_Box" id="Post_Job_Duration_Box_Form_4">
<label class="Post_Job_Duration_Label">
<input type="radio" id="Duration_4" name="Duration" value="4" class="Post_Job_Type_Radio">
<div> меньше месяца </div>
</label>
</div>
<div class="Post_Job_Duration_Box" id="Post_Job_Duration_Box_Form_5">
<label class="Post_Job_Duration_Label">
<input type="radio" id="Duration_5" name="Duration" value="5" class="Post_Job_Type_Radio">
<div> меньше недели </div>
</label>
</div>
<div class="Post_Job_Duration_Box" id="Post_Job_Duration_Box_Form_6">
<label class="Post_Job_Duration_Label">
<input type="radio" id="Duration_6" name="Duration" value="6" class="Post_Job_Type_Radio">
<div> 1 день </div>
</label>
</div>
</div>

<?php
if($ErrorDuration == 1) {
echo "[!] Ошибка";
}
?>
</div>



<hr>


<!-- Вариации ПОИСКА ФРИЛАНСЕРА  -->

<div class="Post_Job_Form">
<label class="Post_Job_Label">Выберите, как будет осуществляться поиск фрилансеров</label>

<span class="Post_Job_Search_Icon"></span>
<div class="Post_Job_Choice_Search">

<div class="Post_Job_Choice_Search_1">
<input type="radio" id="Search1" name="Search" value="1" class="Post_Job_Choice_Search_Radio" checked>
Стандартная форма поиска фрилансера
<br>
<small>Фрилансеры смогут оставлять заявки на ваш проект и вы сможете сами приглашать фрилансеров.</small>
</div>

<div class="Post_Job_Choice_Search_2">
<input type="radio" id="Search2" name="Search" value="2" class="Post_Job_Choice_Search_Radio">
Выборочная форма поиска фрилансера
<br>
<small>Ваш проект не отобращается в списке проектов для заявок фрилансеров. Вы сами ищете и приглашаете фрилансера.</small>
</div>
</div>

<?php
if($ErrorSearch == 1) {
echo "[!] Ошибка";
}
?>
</div>


<hr>






<!-- Приватность задания -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Задать проект как приватный?</label>

<span class="Post_Job_Private_Icon"></span>
<div class="Post_Job_Private">
<input  type="checkbox" name="Private" id="Private" value="1"> - Да, подписывая контракт на этот проект, фрилансер дает согласие о неразглашении информации о проекте (так же информация о проекте в профиле фрилансера будет скрыта для других пользователей).<Br>
</div>
</div>





<!-- Предоплата задания -->
<div class="Post_Job_Form">
<label class="Post_Job_Label" style="padding-bottom: 0px!important;">Укажите максимально возможный размер предоплаты за проект </label>
<small>(Окончательно размер предоплаты формируется при подписании контракта с фриласнером) </small>


<div class="Post_Job_Choice_Panel" style="padding-top: 20px;">
<div class="Post_Job_Prepayment_Box" id="Post_Job_Prepayment_Box_Form_1">
<label class="Post_Job_Prepayment_Label">
<input type="radio" id="Prepayment_1" name="Prepayment" value="1" class="Post_Job_Type_Radio">
<div>нет </div>
</label>
</div>
<div class="Post_Job_Prepayment_Box" id="Post_Job_Prepayment_Box_Form_2">
<label class="Post_Job_Prepayment_Label">
<input type="radio" id="Prepayment_2" name="Prepayment" value="2" class="Post_Job_Type_Radio">
<div> 25% </div>
</label>
</div>
<div class="Post_Job_Prepayment_Box" id="Post_Job_Prepayment_Box_Form_3">
<label class="Post_Job_Prepayment_Label">
<input type="radio" id="Prepayment_3" name="Prepayment" value="3" class="Post_Job_Type_Radio">
<div> 50% </div>
</label>
</div>
<div class="Post_Job_Prepayment_Box" id="Post_Job_Prepayment_Box_Form_4">
<label class="Post_Job_Prepayment_Label">
<input type="radio" id="Prepayment_4" name="Prepayment" value="4" class="Post_Job_Type_Radio">
<div> 75% </div>
</label>
</div>
<div class="Post_Job_Prepayment_Box" id="Post_Job_Prepayment_Box_Form_5">
<label class="Post_Job_Prepayment_Label">
<input type="radio" id="Prepayment_5" name="Prepayment" value="5" class="Post_Job_Type_Radio">
<div> 100% </div>
</label>
</div>
</div>



<hr>

















<!-- Раздел формирования цены -->
<div class="">


<div class="Post_Job_Form">
<label class="Post_Job_Label">Бюджет проекта </label>

На какой бюджет вы рассчитываете
<select name="Price" class="Post_Job_Form_Elements " id="Price" onChange="Selected(this)">

<option  value="0" id="Price_1" name="Price" label="Выберите бюджет">Выберите бюджет</option>
<option  value="1" id="Price_2" name="Price" label="Микро (10-30 USD)">Микро (10-30 USD)</option>
<option  value="2" id="Price_3" name="Price" label="Простой (30-250 USD)">Простой (30-250 USD)</option>
<option  value="3" id="Price_4" name="Price" label="Очень маленький (250-750 USD)">Очень маленький (250-750 USD)</option>
<option  value="4" id="Price_5" name="Price" label="Маленький (750-1500 USD)">Маленький (750-1500 USD)</option>
<option  value="5" id="Price_6" name="Price" label="Средний (1500-3000 USD)">Средний (1500-3000 USD)</option>
<option  value="6" id="Price_7" name="Price" label="Большой (3000-5000 USD)">Большой (3000-5000 USD)</option>
<option  value="7" id="Price_8" name="Price" label="Крупный (>5000 USD)">Крупный (>5000 USD)</option>
<option  value="8" id="Price_9" name="Price" label="Настройка бюджета">Настройка бюджета</option>
</select>

<div class="Post_Job_Price_Option" id="Price_Option" style="display: none;">
<b>Минимальный бюджет</b><b>(USD)</b>
<input  type="number" size="7" name="MinPrice" id="MinPrice" min="10" max="9999999" value="10" class="Post_Job_Form_Elements ">
<br>

<b>Максимальный бюджет</b><b>(USD)</b>
<input  type="number"  size="7" name="MaxPrice" id="MaxPrice" min="10" max="9999999" value="10" class="Post_Job_Form_Elements ">
</div>

</div>


<?php
if($ErrorPrice == 1) {
echo "[!] Ошибка";
}
?>
</div>



<hr>




<!-- Тестовое задание -->
<div class="Post_Job_Form">
<label class="Post_Job_Label">Тест. задание:</label>
<input type="hidden" name="TestHidden" id="TestHidden" value="0">
<!--Раздел тестового задания при не нажатой кнопке создать тестовое задание -->
<div id="testdiv1" class="">
<input type="button" id="showtest" value="Добавить тестовое задание">
- Создать тестовое задание для проверки знаний фрилансера
</div>
</div>

<!--Раздел тестового задания при нажатии кнопки создать тестовое задание -->
<div id="testdiv2" class="" class style="display: none">

<input type="button" id="hidetest" value="удалить тестовое задание">

<div class="">
Введите описание тестового задания
</div>

<div class="">
<textarea name="TestDescription" id="TestDescription" rows="6" cols="60" class="Post_Job_Form_Elements " maxlength="100"></textarea>
</div>

<div class="">
Введите тестовое задание (текст для перевода не более 1800 символов)
</div>

<div class="">
<textarea name="TestText" id="TestText" rows="24" cols="80" class="Post_Job_Form_Elements " maxlength="1800"></textarea>
</div>

</div>
</div>

<br>
<hr>
<br>






<!-- кнопка создать работу -->
<div class="Post_Job_Form">
<input type="submit" class="" id="submit" value="Перейти к предпросмотру"/>
</div>

</form>

</section>




<!--Скрипт на открытие разных типов разных div  -->


<script>

// ПРИ НАЖАТИИ НА КНОПКУ ДОБАВИТЬ ЯЗЫКОВУЮ ПАРУ


var PairLanguage = document.getElementById('PairLanguage');
var Pairs = document.getElementById('Pairs');

i = 0;

PairLanguage.onclick = function() {


if(i <= 4) {  // максимум пар ( 0 1 2 3 4 ) - 5

// если нет одинаковых пар и ни одна из пар не является не выбранной, то добавляем в список и в скрытое поле
if((LanguageFrom.options[LanguageFrom.selectedIndex].label != LanguageTo.options[LanguageTo.selectedIndex].label) && ( LanguageFrom.options[LanguageFrom.selectedIndex].value != 0 ) && ( LanguageTo.options[LanguageTo.selectedIndex].value != 0 ) ) {

// добавление в селект текста языков
var objSel = document.getElementById("chosen_pairs");
objSel.options[i] = new Option(LanguageFrom.options[LanguageFrom.selectedIndex].label+" -> "+LanguageTo.options[LanguageTo.selectedIndex].label, i);


// проверка на то, что такая пара уже выбрана (перебор на каждый вариант)
if( i == 1){
if(objSel.options[i].text == objSel.options[0].text ){
// удаление созданного элемента, если такой же есть в списке и возврат значения на предыдущее для i
objSel.options[i] = null;
alert('выбранная пара уже добавлена1');
i = i-1;
} else {
// добавление в скрытое поле
Pairs.value =Pairs.value +","+ document.getElementById('LanguageFrom').value + "-"+ document.getElementById('LanguageTo').value;
} }else if( i == 2) {
if(objSel.options[i].text == objSel.options[0].text || objSel.options[i].text == objSel.options[1].text  ){
objSel.options[i] = null;
alert('выбранная пара уже добавлена2');
i = i-1;
} else {
// добавление в скрытое поле
Pairs.value =Pairs.value +","+ document.getElementById('LanguageFrom').value + "-"+ document.getElementById('LanguageTo').value;
} }else if( i == 3) {
if(objSel.options[i].text == objSel.options[0].text || objSel.options[i].text == objSel.options[1].text || objSel.options[i].text == objSel.options[2].text ){
objSel.options[i] = null;
alert('выбранная пара уже добавлена3');
i = i-1;
} else {
// добавление в скрытое поле
Pairs.value =Pairs.value +","+ document.getElementById('LanguageFrom').value + "-"+ document.getElementById('LanguageTo').value;
} }else if( i == 4) {
if(objSel.options[i].text == objSel.options[0].text || objSel.options[i].text == objSel.options[1].text || objSel.options[i].text == objSel.options[2].text || objSel.options[i].text == objSel.options[3].text ){
objSel.options[i] = null;
alert('выбранная пара уже добавлена4');
i = i-1;
} else {
// добавление в скрытое поле
Pairs.value =Pairs.value +","+ document.getElementById('LanguageFrom').value + "-"+ document.getElementById('LanguageTo').value;
} }

if(i == 0){
Pairs.value =document.getElementById('LanguageFrom').value + "-"+ document.getElementById('LanguageTo').value;
}

// прибавляем i
i=i+1;

return false;

}else {
alert('ошибка выбора пар (пары не выбраны или выбраны одинаковые пары');
}
}else{
alert('максимальное колличество пар языков');
	}
}


// удаление выбранной пары

DeleteLanguage.onclick = function() {
var objSel = document.getElementById("chosen_pairs"); // переменная текущих пар
var Pairs =  document.getElementById("Pairs");


if ( objSel.selectedIndex != -1) {// если что то выделено

// удаляет из скрытого поля первый элемент
if ( objSel.selectedIndex == 0) {
Pairs.value =  Pairs.value.slice(4);
}
// удаляет из скрытого поля второй элемент
if ( objSel.selectedIndex == 1) {
Pairs.value = Pairs.value.slice(0,3) + Pairs.value.slice(7);
}
// удаляет из скрытого поля третий элемент
if ( objSel.selectedIndex == 2) {
Pairs.value = Pairs.value.slice(0,7) + Pairs.value.slice(11);
}
// удаляет из скрытого поля !!! элемент
if ( objSel.selectedIndex == 3) {
Pairs.value = Pairs.value.slice(0,11) + Pairs.value.slice(15);
}
// удаляет из скрытого поля !!! элемент
if ( objSel.selectedIndex == 4) {
Pairs.value = Pairs.value.slice(0,15) + Pairs.value.slice(19);
}

//удалить текущий элемент и уменьшить значение i на 1 
  objSel.options[objSel.selectedIndex] = null;

  i = i-1;
  
  return false;
}
}



// ВЫВОД ДИНАМИЧЕСКИХ ПОДСКАЗОК

/*
// ---------------------   ДИНАМИЧЕСКАЯ ПРОВЕРКА LANGUAGE   ------------------------------
var HelpLanguageFrom = document.getElementById('LanguageFrom');
var HelpLanguageTo = document.getElementById('LanguageTo');

 // для подсказки
var HelpLanguageForm = document.getElementById('HelpLanguage');
 // для ошибки
var LanguageErrorForm = document.getElementById('ErrorLanguageTo');

 

// при окончании фокуса с какого языка выбирать
HelpLanguageFrom.onblur = function() {
  HelpLanguageForm.className = 'PostJobHelpHidden';
    // для ошибки нахождение value 
    LanguageFromValue = document.getElementById("LanguageFrom").value;
    LanguageToValue = document.getElementById("LanguageTo").value;
  // проверка на ошибку не выбора языка
  if (LanguageFromValue != 1 && LanguageFromValue != 2 ) {  // если не выбран ни один из вариантов
    LanguageErrorForm.className = 'PostJobError';  // то показывается ошибка
 } else {											// иначе
    LanguageErrorForm.className = 'PostJobHelpHidden'; // не показывается ошибка
  }
  // проверка на совпадение языков
    if (LanguageFromValue == LanguageToValue ) {  // если совпали языки
    LanguageErrorForm.className = 'PostJobError';  // то показывается ошибка
  } else {											// иначе
    LanguageErrorForm.className = 'PostJobHelpHidden'; // не показывается ошибка
  }
  }
  
  
  // при окончании фокуса на какой языка выбирать
HelpLanguageTo.onblur = function() {
  HelpLanguageForm.className = 'PostJobHelpHidden';

    // для ошибки нахождение value 
    LanguageFromValue = document.getElementById("LanguageFrom").value;
  LanguageToValue = document.getElementById("LanguageTo").value;
  // проверка на ошибку не выбора языка
  if (LanguageToValue != 1 && LanguageToValue !=2 ) {  // если не выбран ни один из вариантов
    LanguageErrorForm.className = 'PostJobError';  // то показывается ошибка
  } else {											// иначе
    LanguageErrorForm.className = 'PostJobHelpHidden'; // не показывается ошибка
  }
  // проверка на совпадение языков
    if (LanguageToValue == LanguageFromValue ) {  // если совпали языки
    LanguageErrorForm.className = 'PostJobError';  // то показывается ошибка
  } else {											// иначе
    LanguageErrorForm.className = 'PostJobHelpHidden'; // не показывается ошибка
  }
  }

*/
// ---------------------   ДИНАМИЧЕСКАЯ ПРОВЕРКА ------------------------------

var HelpTitle = document.getElementById('Title');
var HelpTitleForm = document.getElementById('HelpTitle');

HelpTitle.onfocus = function() {
  HelpTitleForm.className = 'Post_Job_Help_Name';
}
HelpTitle.onblur = function() {
  HelpTitleForm.className = 'PostJobHelpHidden';
}

var HelpDescription = document.getElementById('Description');
var HelpDescriptionForm = document.getElementById('HelpDescription');

HelpDescription.onfocus = function() {
  HelpDescriptionForm.className = 'Post_Job_Help_Description';
}
HelpDescription.onblur = function() {
  HelpDescriptionForm.className = 'PostJobHelpHidden';
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








// ------------------------------------------------------------------------------ //
// Изменение стиля при переключении radio у Type //

var Post_Job_Type_Box_Form_1 = document.getElementById('Post_Job_Type_Box_Form_1');
var Post_Job_Type_Box_Form_2 = document.getElementById('Post_Job_Type_Box_Form_2');
var Post_Job_Type_Box_Form_3 = document.getElementById('Post_Job_Type_Box_Form_3');
var Post_Job_Type_Box_Form_4 = document.getElementById('Post_Job_Type_Box_Form_4');

Post_Job_Type_Box_Form_1.onmouseover=Post_Job_Type_Box_Form_1.className = 'Post_Job_Type_Box_Checked';
Post_Job_Type_Box_Form_1.onmouseout=Post_Job_Type_Box_Form_1.className = 'Post_Job_Type_Box';


var TypeRadio_1 = document.querySelector('#Type_1');
TypeRadio_1.onclick = function() {
 if (TypeRadio_1.checked) {
    Post_Job_Type_Box_Form_1.className = 'Post_Job_Type_Box_Checked';
    Post_Job_Type_Box_Form_2.className = 'Post_Job_Type_Box';
    Post_Job_Type_Box_Form_3.className = 'Post_Job_Type_Box';
    Post_Job_Type_Box_Form_4.className = 'Post_Job_Type_Box';	
 } 
}

var TypeRadio_2 = document.querySelector('#Type_2');
TypeRadio_2.onclick = function() {
 if (TypeRadio_2.checked) {
    Post_Job_Type_Box_Form_1.className = 'Post_Job_Type_Box';
    Post_Job_Type_Box_Form_2.className = 'Post_Job_Type_Box_Checked';
    Post_Job_Type_Box_Form_3.className = 'Post_Job_Type_Box';
    Post_Job_Type_Box_Form_4.className = 'Post_Job_Type_Box';
 } 
}


var TypeRadio_3 = document.querySelector('#Type_3');
TypeRadio_3.onclick = function() {
 if (TypeRadio_3.checked) {
    Post_Job_Type_Box_Form_1.className = 'Post_Job_Type_Box';
    Post_Job_Type_Box_Form_2.className = 'Post_Job_Type_Box';
    Post_Job_Type_Box_Form_3.className = 'Post_Job_Type_Box_Checked';
    Post_Job_Type_Box_Form_4.className = 'Post_Job_Type_Box';
 } 
}

var TypeRadio_4 = document.querySelector('#Type_4');
TypeRadio_4.onclick = function() {
 if (TypeRadio_4.checked) {
    Post_Job_Type_Box_Form_1.className = 'Post_Job_Type_Box';
    Post_Job_Type_Box_Form_2.className = 'Post_Job_Type_Box';
    Post_Job_Type_Box_Form_3.className = 'Post_Job_Type_Box';
    Post_Job_Type_Box_Form_4.className = 'Post_Job_Type_Box_Checked';
 } 
}



// ------------------------------------------------------------------------------ //
// Изменение стиля при переключении radio у Duration //

var Post_Job_Duration_Box_Form_1 = document.getElementById('Post_Job_Duration_Box_Form_1');
var Post_Job_Duration_Box_Form_2 = document.getElementById('Post_Job_Duration_Box_Form_2');
var Post_Job_Duration_Box_Form_3 = document.getElementById('Post_Job_Duration_Box_Form_3');
var Post_Job_Duration_Box_Form_4 = document.getElementById('Post_Job_Duration_Box_Form_4');
var Post_Job_Duration_Box_Form_5 = document.getElementById('Post_Job_Duration_Box_Form_5');
var Post_Job_Duration_Box_Form_6 = document.getElementById('Post_Job_Duration_Box_Form_6');

Post_Job_Duration_Box_Form_1.onmouseover=Post_Job_Duration_Box_Form_1.className = 'Post_Job_Duration_Box_Checked';
Post_Job_Duration_Box_Form_1.onmouseout=Post_Job_Duration_Box_Form_1.className = 'Post_Job_Duration_Box';


var DurationRadio_1 = document.querySelector('#Duration_1');
DurationRadio_1.onclick = function() {
 if (DurationRadio_1.checked) {
    Post_Job_Duration_Box_Form_1.className = 'Post_Job_Duration_Box_Checked';
    Post_Job_Duration_Box_Form_2.className = 'Post_Job_Duration_Box';
    Post_Job_Duration_Box_Form_3.className = 'Post_Job_Duration_Box';
    Post_Job_Duration_Box_Form_4.className = 'Post_Job_Duration_Box';	
	Post_Job_Duration_Box_Form_5.className = 'Post_Job_Duration_Box';	
	Post_Job_Duration_Box_Form_6.className = 'Post_Job_Duration_Box';	
 } 
}

var DurationRadio_2 = document.querySelector('#Duration_2');
DurationRadio_2.onclick = function() {
 if (DurationRadio_2.checked) {
    Post_Job_Duration_Box_Form_1.className = 'Post_Job_Duration_Box';
    Post_Job_Duration_Box_Form_2.className = 'Post_Job_Duration_Box_Checked';
    Post_Job_Duration_Box_Form_3.className = 'Post_Job_Duration_Box';
    Post_Job_Duration_Box_Form_4.className = 'Post_Job_Duration_Box';	
	Post_Job_Duration_Box_Form_5.className = 'Post_Job_Duration_Box';	
	Post_Job_Duration_Box_Form_6.className = 'Post_Job_Duration_Box';	
 } 
}


var DurationRadio_3 = document.querySelector('#Duration_3');
DurationRadio_3.onclick = function() {
 if (DurationRadio_3.checked) {
    Post_Job_Duration_Box_Form_1.className = 'Post_Job_Duration_Box';
    Post_Job_Duration_Box_Form_2.className = 'Post_Job_Duration_Box';	
    Post_Job_Duration_Box_Form_3.className = 'Post_Job_Duration_Box_Checked';
    Post_Job_Duration_Box_Form_4.className = 'Post_Job_Duration_Box';	
	Post_Job_Duration_Box_Form_5.className = 'Post_Job_Duration_Box';	
	Post_Job_Duration_Box_Form_6.className = 'Post_Job_Duration_Box';	
 } 
}

var DurationRadio_4 = document.querySelector('#Duration_4');
DurationRadio_4.onclick = function() {
 if (DurationRadio_4.checked) {
    Post_Job_Duration_Box_Form_1.className = 'Post_Job_Duration_Box';
    Post_Job_Duration_Box_Form_2.className = 'Post_Job_Duration_Box';	
    Post_Job_Duration_Box_Form_3.className = 'Post_Job_Duration_Box';	
    Post_Job_Duration_Box_Form_4.className = 'Post_Job_Duration_Box_Checked';
	Post_Job_Duration_Box_Form_5.className = 'Post_Job_Duration_Box';	
	Post_Job_Duration_Box_Form_6.className = 'Post_Job_Duration_Box';	
 } 
}

var DurationRadio_5 = document.querySelector('#Duration_5');
DurationRadio_5.onclick = function() {
 if (DurationRadio_5.checked) {
    Post_Job_Duration_Box_Form_1.className = 'Post_Job_Duration_Box';
    Post_Job_Duration_Box_Form_2.className = 'Post_Job_Duration_Box';	
    Post_Job_Duration_Box_Form_3.className = 'Post_Job_Duration_Box';	
    Post_Job_Duration_Box_Form_4.className = 'Post_Job_Duration_Box';	
	Post_Job_Duration_Box_Form_5.className = 'Post_Job_Duration_Box_Checked';
	Post_Job_Duration_Box_Form_6.className = 'Post_Job_Duration_Box';	
 } 
}

var DurationRadio_6 = document.querySelector('#Duration_6');
DurationRadio_6.onclick = function() {
 if (DurationRadio_6.checked) {
    Post_Job_Duration_Box_Form_1.className = 'Post_Job_Duration_Box';
    Post_Job_Duration_Box_Form_2.className = 'Post_Job_Duration_Box';	
    Post_Job_Duration_Box_Form_3.className = 'Post_Job_Duration_Box';	
    Post_Job_Duration_Box_Form_4.className = 'Post_Job_Duration_Box';	
	Post_Job_Duration_Box_Form_5.className = 'Post_Job_Duration_Box';	
	Post_Job_Duration_Box_Form_6.className = 'Post_Job_Duration_Box_Checked';
 } 
}


// ------------------------------------------------------------------------------ //
// Изменение стиля при переключении radio у Prepayment //

var Post_Job_Prepayment_Box_Form_1 = document.getElementById('Post_Job_Prepayment_Box_Form_1');
var Post_Job_Prepayment_Box_Form_2 = document.getElementById('Post_Job_Prepayment_Box_Form_2');
var Post_Job_Prepayment_Box_Form_3 = document.getElementById('Post_Job_Prepayment_Box_Form_3');
var Post_Job_Prepayment_Box_Form_4 = document.getElementById('Post_Job_Prepayment_Box_Form_4');
var Post_Job_Prepayment_Box_Form_5 = document.getElementById('Post_Job_Prepayment_Box_Form_5');

Post_Job_Prepayment_Box_Form_1.onmouseover=Post_Job_Prepayment_Box_Form_1.className = 'Post_Job_Prepayment_Box_Checked';
Post_Job_Prepayment_Box_Form_1.onmouseout=Post_Job_Prepayment_Box_Form_1.className = 'Post_Job_Prepayment_Box';


var PrepaymentRadio_1 = document.querySelector('#Prepayment_1');
PrepaymentRadio_1.onclick = function() {
 if (PrepaymentRadio_1.checked) {
    Post_Job_Prepayment_Box_Form_1.className = 'Post_Job_Prepayment_Box_Checked';
    Post_Job_Prepayment_Box_Form_2.className = 'Post_Job_Prepayment_Box';
    Post_Job_Prepayment_Box_Form_3.className = 'Post_Job_Prepayment_Box';
    Post_Job_Prepayment_Box_Form_4.className = 'Post_Job_Prepayment_Box';
	Post_Job_Prepayment_Box_Form_5.className = 'Post_Job_Prepayment_Box';
 } 
}

var PrepaymentRadio_2 = document.querySelector('#Prepayment_2');
PrepaymentRadio_2.onclick = function() {
 if (PrepaymentRadio_2.checked) {
    Post_Job_Prepayment_Box_Form_1.className = 'Post_Job_Prepayment_Box';
    Post_Job_Prepayment_Box_Form_2.className = 'Post_Job_Prepayment_Box_Checked';
    Post_Job_Prepayment_Box_Form_3.className = 'Post_Job_Prepayment_Box';
    Post_Job_Prepayment_Box_Form_4.className = 'Post_Job_Prepayment_Box';
	Post_Job_Prepayment_Box_Form_5.className = 'Post_Job_Prepayment_Box';
 } 
}


var PrepaymentRadio_3 = document.querySelector('#Prepayment_3');
PrepaymentRadio_3.onclick = function() {
 if (PrepaymentRadio_3.checked) {
    Post_Job_Prepayment_Box_Form_1.className = 'Post_Job_Prepayment_Box';
    Post_Job_Prepayment_Box_Form_2.className = 'Post_Job_Prepayment_Box';
    Post_Job_Prepayment_Box_Form_3.className = 'Post_Job_Prepayment_Box_Checked';
    Post_Job_Prepayment_Box_Form_4.className = 'Post_Job_Prepayment_Box';
	Post_Job_Prepayment_Box_Form_5.className = 'Post_Job_Prepayment_Box';
 } 
}

var PrepaymentRadio_4 = document.querySelector('#Prepayment_4');
PrepaymentRadio_4.onclick = function() {
 if (PrepaymentRadio_4.checked) {
    Post_Job_Prepayment_Box_Form_1.className = 'Post_Job_Prepayment_Box';
    Post_Job_Prepayment_Box_Form_2.className = 'Post_Job_Prepayment_Box';
    Post_Job_Prepayment_Box_Form_3.className = 'Post_Job_Prepayment_Box';
    Post_Job_Prepayment_Box_Form_4.className = 'Post_Job_Prepayment_Box_Checked';
	Post_Job_Prepayment_Box_Form_5.className = 'Post_Job_Prepayment_Box';
 } 
}

var PrepaymentRadio_5 = document.querySelector('#Prepayment_5');
PrepaymentRadio_5.onclick = function() {
 if (PrepaymentRadio_5.checked) {
    Post_Job_Prepayment_Box_Form_1.className = 'Post_Job_Prepayment_Box';
    Post_Job_Prepayment_Box_Form_2.className = 'Post_Job_Prepayment_Box';
    Post_Job_Prepayment_Box_Form_3.className = 'Post_Job_Prepayment_Box';
    Post_Job_Prepayment_Box_Form_4.className = 'Post_Job_Prepayment_Box';	
	Post_Job_Prepayment_Box_Form_5.className = 'Post_Job_Prepayment_Box_Checked';
 } 
}

// ------------------------------------------------------------------------------ //
// Открытие настройки Price при выборе пункта "Настройка бюджета" из селектора //
function Selected(a) {
        var label = a.value;
        if (label==8) {
            document.getElementById("Price_Option").style.display='block';      
        } else {
            document.getElementById("Price_Option").style.display='none';
        }
         
}


</script>


<?php
}
?>
<!-- окончание раздела с preview = 0 -->



<!-- начало раздела с preview = 1 -->
<?php
if($Page_Type == 1){
?>






<section class="FW_middle">





<h1>Предпросмотр работы</h1>

<!-- Форма предпросмотра работы -->
<div class="FM_middle_center">

<div class="Job_Preview_Title">
<h1>
<div class="Job_Preview_Title_First">
<?php echo $Title; ?>
</div>
</h1>

<h2>
 <div class="Job_Preview_Title_Second">
 Опубликовано меньше минуты назад  ||

 Бюджет :
 <?php If ($Price == 1) { ?>
до 30 USD  
  
<?php } elseif ($Price == 2 ) { ?>
до 250 USD

<?php } elseif ($Price == 3 ) { ?>
до 750 USD

<?php } elseif ($Price == 4 ) { ?>
до 1500 USD

<?php } elseif ($Price == 5 ) { ?>
до 3000 USD

<?php } elseif ($Price == 6 ) { ?>
до 5000 USD

<?php } elseif ($Price == 7 ) { ?>
неограничен

<?php } elseif ($Price == 8 ) { 
echo "от $MinPrice до $MaxPrice";
 } ?> 
 </div>
</h2>
</div>

<div class="Job_Preview_Detail">
<div class="Job_Preview_Detail_Main">
<div class="Job_Preview_Detail_First">

<?php
if($Language_From_Information = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageFrom1'")){
$Language_From_Information_Data = $Language_From_Information->fetch_assoc();
$LanguageFromImg=$Language_From_Information_Data['Flag'];
$LanguageFromLang=$Language_From_Information_Data['Russian'];
$Language_From_Information->close(); 
}
if($Language_To_Information = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageTo1'")){
$Language_To_Information_Data = $Language_To_Information->fetch_assoc();
$LanguageToImg=$Language_To_Information_Data['Flag'];
$LanguageToLang=$Language_To_Information_Data['Russian'];
$Language_To_Information->close(); 
}
?>

<ul style="padding-left: 200px;">
<li class="Job_Preview_Detail_Li_1">
<span class="Job_Preview_Detail_Span">
 <img src="<?php echo $LanguageFromImg; ?>" alt>  <?php echo $LanguageFromLang; ?>  -> <img src="<?php echo $LanguageToImg; ?>" alt>  <?php echo $LanguageToLang; ?> 
</span>
</li>

<?php
if ( $LanguageFrom2 != 0 && $LanguageTo2 != 0 ){

if($Language_From_Information_2 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageFrom2'")){
$Language_From_Information_Data_2 = $Language_From_Information_2->fetch_assoc();
$LanguageFromImg_2=$Language_From_Information_Data_2['Flag'];
$LanguageFromLang_2=$Language_From_Information_Data_2['Russian'];
$Language_From_Information_2->close(); 
}
if($Language_To_Information_2 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageTo2'")){
$Language_To_Information_Data_2 = $Language_To_Information_2->fetch_assoc();
$LanguageToImg_2=$Language_To_Information_Data_2['Flag'];
$LanguageToLang_2=$Language_To_Information_Data_2['Russian'];
$Language_To_Information_2->close(); 
}
?>
<li class="Job_Preview_Detail_Li_1">
<span class="Job_Preview_Detail_Span">
 <img src="<?php echo $LanguageFromImg_2; ?>" alt>  <?php echo $LanguageFromLang_2; ?>  -> <img src="<?php echo $LanguageToImg_2; ?>" alt>  <?php echo $LanguageToLang_2; ?> 
</span>
</li>
<?php
}
?>

<?php
if ( $LanguageFrom3 != 0 && $LanguageTo3 != 0 ){

if($Language_From_Information_3 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageFrom3'")){
$Language_From_Information_Data_3 = $Language_From_Information_3->fetch_assoc();
$LanguageFromImg_3=$Language_From_Information_Data_3['Flag'];
$LanguageFromLang_3=$Language_From_Information_Data_3['Russian'];
$Language_From_Information_3->close(); 
}
if($Language_To_Information_3 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageTo3'")){
$Language_To_Information_Data_3 = $Language_To_Information_3->fetch_assoc();
$LanguageToImg_3=$Language_To_Information_Data_3['Flag'];
$LanguageToLang_3=$Language_To_Information_Data_3['Russian'];
$Language_To_Information_3->close(); 
}
?>
<li class="Job_Preview_Detail_Li_1">
<span class="Job_Preview_Detail_Span">
 <img src="<?php echo $LanguageFromImg_3; ?>" alt>  <?php echo $LanguageFromLang_3; ?>  -> <img src="<?php echo $LanguageToImg_3; ?>" alt>  <?php echo $LanguageToLang_3; ?> 
</span>
</li>
<?php
}
?>

<?php
if ( $LanguageFrom4 != 0 && $LanguageTo4 != 0 ){

if($Language_From_Information_4 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageFrom4'")){
$Language_From_Information_Data_4 = $Language_From_Information_4->fetch_assoc();
$LanguageFromImg_4=$Language_From_Information_Data_4['Flag'];
$LanguageFromLang_4=$Language_From_Information_Data_4['Russian'];
$Language_From_Information_4->close(); 
}
if($Language_To_Information_4 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageTo4'")){
$Language_To_Information_Data_4 = $Language_To_Information_4->fetch_assoc();
$LanguageToImg_4=$Language_To_Information_Data_4['Flag'];
$LanguageToLang_4=$Language_To_Information_Data_4['Russian'];
$Language_To_Information_4->close(); 
}
?>
<li class="Job_Preview_Detail_Li_1">
<span class="Job_Preview_Detail_Span">
 <img src="<?php echo $LanguageFromImg_4; ?>" alt>  <?php echo $LanguageFromLang_4; ?>  -> <img src="<?php echo $LanguageToImg_4; ?>" alt>  <?php echo $LanguageToLang_4; ?> 
</span>
</li>
<?php
}
?>

<?php
if ( $LanguageFrom5 != 0 && $LanguageTo5 != 0 ){

if($Language_From_Information_5 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageFrom5'")){
$Language_From_Information_Data_5 = $Language_From_Information_5->fetch_assoc();
$LanguageFromImg_5=$Language_From_Information_Data_5['Flag'];
$LanguageFromLang_5=$Language_From_Information_Data_5['Russian'];
$Language_From_Information_5->close(); 
}
if($Language_To_Information_5 = $BD->query("SELECT * FROM `Language` WHERE `id`='$LanguageTo5'")){
$Language_To_Information_Data_5 = $Language_To_Information_5->fetch_assoc();
$LanguageToImg_5=$Language_To_Information_Data_5['Flag'];
$LanguageToLang_5=$Language_To_Information_Data_5['Russian'];
$Language_To_Information_5->close(); 
}
?>
<li class="Job_Preview_Detail_Li_1">
<span class="Job_Preview_Detail_Span">
 <img src="<?php echo $LanguageFromImg_5; ?>" alt>  <?php echo $LanguageFromLang_5; ?>  -> <img src="<?php echo $LanguageToImg_5; ?>" alt>  <?php echo $LanguageToLang_5; ?> 
</span>
</li>
<?php
}
?>

</ul>

<ul class="Job_Preview_Detail_Ul">
<li class="Job_Preview_Detail_Li_2">
<span class="Job_Preview_Detail_Span">
Стиль :  

 <?php If ($Style == 1) { ?>
Общая тематика
  
<?php } elseif ($Style == 2 ) { ?>
Художественный

<?php } elseif ($Style == 3 ) { ?>
Технический

<?php } elseif ($Style == 4 ) { ?>
Экономический

<?php } elseif ($Style == 5 ) { ?>
Политический

<?php } elseif ($Style == 6 ) { ?>
Медицинский

<?php } elseif ($Style == 7 ) { ?>
Реклама

<?php } ?> 

</span>
</li>

<li class="Job_Preview_Detail_Li_2">
<span class="Job_Preview_Detail_Span">
Сроки выполнения : 

 <?php If ($Duration == 1) { ?>
Больше 6 месяцев
  
<?php } elseif ($Duration == 2 ) { ?>
3 - 6 месяцев

<?php } elseif ($Duration == 3 ) { ?>
1 - 3 месяца

<?php } elseif ($Duration == 4 ) { ?>
Меньше месяца

<?php } elseif ($Duration == 5 ) { ?>
Меньше недели

<?php } elseif ($Duration == 6 ) { ?>
Один день

<?php }  ?> 

</span>
</li>


<li class="Job_Preview_Detail_Li_2">
<span class="Job_Preview_Detail_Span">
Приватность :   

 <?php If ($Private == 0) { ?>
Нет
  
<?php } elseif ($Private == 1 ) { ?>
Да

<?php }  ?> 
</span>
</li>
</ul>

<ul class="Job_Preview_Detail_Ul">
<li class="Job_Preview_Detail_Li_2">
<span class="Job_Preview_Detail_Span">
Тип :  

 <?php If ($Type == 1) { ?>
Текст

<?php } elseif ($Type == 2 ) { ?>
Видео

<?php } elseif ($Type == 3 ) { ?>
Аудио

<?php } elseif ($Type == 4 ) { ?>
Локализация
<?php }  ?> 
</span>
</li>


<li class="Job_Preview_Detail_Li_2">
<span class="Job_Preview_Detail_Span">
Предоплата :  

 <?php If ($Prepayment == 1) { ?>
Нет

<?php } elseif ($Prepayment == 2 ) { ?>
25%

<?php } elseif ($Prepayment == 3 ) { ?>
50%

<?php } elseif ($Prepayment == 4 ) { ?>
75%

<?php } elseif ($Prepayment == 5 ) { ?>
100%

<?php }  ?> 

</span>
</li>


<li class="Job_Preview_Detail_Li_2">
<span class="Job_Preview_Detail_Span">
Тестовая работа :  

 <?php If ($TestHidden == 0) { ?>
Нет

<?php } elseif ($TestHidden == 1 ) { ?>
Да

<?php }  ?> 
</span>
</li>
</ul>
</div>

</div>



<div class="Job_Preview_Detail_Desc_Main">

<h2 class="Job_Preview_Detail_Desc_Tittle"> Описание </h2>
<p class="Job_Preview_Detail_Description">
 <?php echo "$Description"; ?> 
</p>


 <?php If ($FileNamen != Null) { ?>
<div>
<span>
Прикрепленный файл : <?php echo "$FileName"; ?> 
</span>
</div>
<?php }  ?> 


 <?php If ($TestHidden == 1) { ?>
<div>
<hr>
<label class="Post_Job_Label">Тестовая работа (если есть) </label>
<span> Название тестовой работы : <?php echo "$TestDescription" ?></span>
<br>
<span> Текст работы : <?php echo "$TestText" ?></span>
</div>
<?php }  ?> 



<div class="footer for it">

</div>

</div>





<!--
<div class="Post_Job_Form">
<label class="Post_Job_Label">Язык </label>
 <?php echo "$LanguageFrom1 - $LanguageTo1,$LanguageFrom2 - $LanguageTo2,$LanguageFrom3 - $LanguageTo3,$LanguageFrom4 - $LanguageTo4,$LanguageFrom5 - $LanguageTo5 "; ?> 
 </div>
 <div class="Post_Job_Form">
<label class="Post_Job_Label">Стиль </label>
 <?php echo "$Style"; ?> 
 </div>
 <div class="Post_Job_Form">
<label class="Post_Job_Label">Название </label>
 <?php echo "$Title"; ?> 
 </div>
 <div class="Post_Job_Form">
<label class="Post_Job_Label">Описание </label>
 <?php echo "$Description"; ?> 
 </div>
 <div class="Post_Job_Form">
<label class="Post_Job_Label">Тип </label>
 <?php echo "$Type"; ?> 
 </div>
 <div class="Post_Job_Form">
  <label class="Post_Job_Label">Вид поиска</label>
 <?php echo "$Search"; ?> 
  </div>
 <div class="Post_Job_Form">
  <label class="Post_Job_Label">Приватность </label>
 <?php echo "$Private"; ?> 
  </div>
 <div class="Post_Job_Form">
  <label class="Post_Job_Label">Сроки</label>
 <?php echo "$Duration"; ?> 
  </div>
 <div class="Post_Job_Form">
  <label class="Post_Job_Label">Предоплата</label>
 <?php echo "$Prepayment"; ?> 
  </div>
  <div class="Post_Job_Form">
  <label class="Post_Job_Label">Загруженнный файл</label>
 <?php echo "$FileName"; ?> 
 </div>
  
  
 <div class="Post_Job_Form">
  <label class="Post_Job_Label">Цена</label>
 <?php echo "$MinPrice - $MaxPrice" ?> 
 </div>
 
  <div class="Post_Job_Form">
  <label class="Post_Job_Label">!!!!</label>
 <?php echo  "'$User_Id', '$LanguageFrom1','$LanguageTo1','$LanguageFrom2','$LanguageTo2','$LanguageFrom3','$LanguageTo3','$LanguageFrom4','$LanguageTo4','$LanguageFrom5','$LanguageTo5','$Style', '$Title', '$Description', '$Type', '$Search','$Private','$Prepayment','$Duration','$MinPrice','$MaxPrice','$DataCreation','$TestDescription','$TestText','$File'"; ?>
 </div>
 
 -->
 
 
 
 
 
<form name="NewJob" id="NewJob" class="" method="post" >


<!-- Данные, которые занесутся в бд после предпросмотра -->
<input type="hidden" name="Pairs" value="<?php echo "$LanguagePairs" ?>" />
<input type="hidden" name="Style" value="<?php echo "$Style" ?>" />
<input type="hidden" name="Title" value="<?php echo "$Title" ?>" />
<input type="hidden" name="Description" value="<?php echo "$Description" ?>" />
<input type="hidden" name="Type" value="<?php echo "$Type" ?>" />
<input type="hidden" name="Search" value="<?php echo "$Search" ?>" />
<input type="hidden" name="Private" value="<?php echo "$Private" ?>" />
<input type="hidden" name="TestHidden" value="<?php echo "$TestHidden" ?>" />
<input type="hidden" name="Duration" value="<?php echo "$Duration" ?>" />
<input type="hidden" name="Prepayment" value="<?php echo "$Prepayment" ?>" />
<input type="hidden" name="Price" value="<?php echo "$Price" ?>" />
<input type="hidden" name="MinPrice" value="<?php echo "$MinPrice" ?>" />
<input type="hidden" name="MaxPrice" value="<?php echo "$MaxPrice" ?>" />
<input type="hidden" name="TestDescription" value="<?php echo "$TestDescription" ?>" />
<input type="hidden" name="TestText" value="<?php echo "$TestText" ?>" />
<input type="hidden" name="File" value="<?php echo "$File" ?>" />

<div class="Post_Job_Form">
<input type="submit" name="Post_Job" class="" id="Post_Job" value="Создать задание"/>
</div>

<div class="Post_Job_Form">
<input type="submit" name="Back_Job" class="" id="Back_Job" value="Вернуться к редактированию"/>
</div>

<div class="Post_Job_Form">
<input type="submit" name="Save_Job" class="" id="Save_Job" value="Отменить и Сохранить как шаблон"/>
</div>
</form>


</section>

</div>

<?php
}
?>
<!-- окончание раздела с preview = 1 -->


<!-- раздел перехода к ошибке, если preview не равно ни 1 ни 0 -->
<?php
if($Page_Type !=1 && $Page_Type !=0){
?>
<section class="FW_middle">
ошибка формирования страницы
</section>
<?php
}
?>
<!-- ------------------------------------------- -->


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