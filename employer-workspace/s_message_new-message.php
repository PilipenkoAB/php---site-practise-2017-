<?php
header('Content-Type: application/json; charset=utf-8');
/* Старт Сессии */ 
session_start();




//----------------------------
// РАЗДЕЛ РАБОТЫ С ЗАПРОСОМ JSON
//------------------------

//Function to check if the request is an AJAX request

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){ // Проверка на то, ajax ли запрос это
 test_function(); 			//  И ЭТОГО когда action = test, то переходим к функции test_function. break - прекратить перебор
 }

// Функция, в который все что находится в конструкции $return["__"] = "__"; - будет принято обратно в js
function test_function(){

// Если нет сессии, то несанкционированная попытка доступа к скрипту и ошибка
if(!isset($_SESSION['SessionId'])) {
  die();
}

// Если сессия есть, то определяет от кого пришел запрос и работа с бд
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
		die();
	}
	
	// Переменные "id"  текущего пользователя
	$User_Id=$User_Information_Data['id'];
	
/* Освобождаем память $User_Information */ 
$User_Information->close(); 
}else{
die();
}	
	
	
// Принятие данных о контракте, через который находиятся данные о фрилансере в которой идет переписка.	
if (isset($_POST["contact"])){
  $ContactId=htmlspecialchars($_POST["contact"]);	
  
}else{
	die();
}
	
if (isset($_POST["message"])){
  $NewMessage=htmlspecialchars($_POST["message"]);	
	
$Time=date('Y-m-d H:i:s');

if(!$BD->query("INSERT INTO `Messages` (`IdFrom`,`IdTo`,`Type`,`Message`,`Time`) VALUES ('$User_Id', '$ContactId','0','$NewMessage','$Time')")){

	printf("Ошибка 3: %s\n", $BD->sqlstate); 	// если не получилось занести в бд
			}else{


	   // Определение того, что будет отправленно под return
  $return = $_POST; // НЕ ПОНЯТНО НАЗНАЧЕНИЕ ЭТОЙ СТРОКИ, это все значения, которые приняты через $_POST ???
	
  //Do what you need to do with the info. The following are some examples.

  $return["status"] = "ok";

 echo json_encode($return); // перекодировка в функции
 
 }	
	}
  $BD->close();
		}
}
?>