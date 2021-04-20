<?php
header('Content-Type: application/json; charset=utf-8');
/* Старт Сессии */ 
session_start();




//----------------------------
// РАЗДЕЛ РАБОТЫ С ЗАПРОСОМ JSON
//------------------------

//Function to check if the request is an AJAX request

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){ // Проверка на то, ajax ли запрос это
  if (isset($_POST["action"]) && !empty($_POST["action"]) ) { //Checks if action value exists
  $action = $_POST["action"];
  switch($action) { //Switch case for value of action      НЕПОНЯТНО НАЗНАЧЕНИЕ ЭТОГО вроде как прогоняем все значения action
  case "test": test_function(); break;				//  И ЭТОГО когда action = test, то переходим к функции test_function. break - прекратить перебор
    }
  }
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
if (isset($_POST["contract"])){
  $ContractId = $_POST["contract"];	
  
 if ($Contract_Information = $BD->query("SELECT * FROM `Contracts` WHERE `id`='$ContractId' ")) { 

    /* Выбираем результаты запроса по $Contract_Information : */ 
$Contract_Information_Data = $Contract_Information->fetch_assoc();

	$Freelancer_Id=$Contract_Information_Data['FreelancerId'];
	
/* Освобождаем память $Contract_Information */ 
$Contract_Information->close(); 
}	 
}else{
	die();
}
	

	
	
	
	   // Определение того, что будет отправленно под return
  $return = $_POST; // НЕ ПОНЯТНО НАЗНАЧЕНИЕ ЭТОЙ СТРОКИ, это все значения, которые приняты через $_POST ???

	
	//ВЫБОРКА ИЗ БД СООБЩЕНИЙ, ГДЕ ОТПРАВИТЕЛЬ ЗАКАЗЧИК, А ПРИНИМАЮЩИЙ - ФРИЛАНСЕР и ОТПРАВИТЕЛЬ - ФРИЛАНСЕР, А ПРИНИМАЮЩИЙ - ЗАКАЗЧИК

		/* Посылаем запрос серверу на выборку информации о всех возможных сообщениях по данному контракту */	
if($Message_Information = $BD->query("SELECT * FROM `Messages` WHERE `Type`='$ContractId' ORDER BY `Time`  ")) { 

    /*В Цикле Выбираем результаты запроса по $Message_Information : */ 
	$i=1; //Счётчик кол-ва сообщений

While($Message_Information_Data = $Message_Information->fetch_assoc()){

	$Message_text=$Message_Information_Data['Message'];
	$Message_time=$Message_Information_Data['Time'];
	$Message_from=$Message_Information_Data['IdFrom'];
	
	$return["number $i"]=$i;
	$return["time $i"]=$Message_time;
	$return["text $i"]=$Message_text;
	$return["Who $i"]=$Message_from;
	
		$i=$i+1;
}
/* Освобождаем память $Message_Information */ 
$Message_Information->close(); 
}else{
die();
}


  $oneone = $User_Id;

  

  //Do what you need to do with the info. The following are some examples.

  
    $return["id"] = "123";
	$return["one"] = "1";
	$return["oneone"] = $oneone;
	$return["text"]=$Message_text;
  $return["count"]=$i; // сообщение под номером i

 
  $return["json"] = json_encode_cyr($return);  // вся инфа, которая есть - будет под json именем

 echo json_encode_cyr($return); // перекодировка в функции
 
  $BD->close();
}



}


// функция перекодировки в русские буквы из кракозябр
function json_encode_cyr($return) {
    $trans = array(
    '\u0410'=>'А', '\u0411'=>'Б', '\u0412'=>'В', '\u0413'=>'Г', '\u0414'=>'Д', '\u0415'=>'Е',
    '\u0401'=>'Ё', '\u0416'=>'Ж', '\u0417'=>'З', '\u0418'=>'И', '\u0419'=>'Й', '\u041a'=>'К',
    '\u041b'=>'Л', '\u041c'=>'М', '\u041d'=>'Н', '\u041e'=>'О', '\u041f'=>'П', '\u0420'=>'Р',
    '\u0421'=>'С', '\u0422'=>'Т', '\u0423'=>'У', '\u0424'=>'Ф', '\u0425'=>'Х', '\u0426'=>'Ц',
    '\u0427'=>'Ч', '\u0428'=>'Ш', '\u0429'=>'Щ', '\u042a'=>'Ъ', '\u042b'=>'Ы', '\u042c'=>'Ь',
    '\u042d'=>'Э', '\u042e'=>'Ю', '\u042f'=>'Я',
    '\u0430'=>'а', '\u0431'=>'б', '\u0432'=>'в', '\u0433'=>'г', '\u0434'=>'д', '\u0435'=>'е',
    '\u0451'=>'ё', '\u0436'=>'ж', '\u0437'=>'з', '\u0438'=>'и', '\u0439'=>'й', '\u043a'=>'к',
    '\u043b'=>'л', '\u043c'=>'м', '\u043d'=>'н', '\u043e'=>'о', '\u043f'=>'п', '\u0440'=>'р',
    '\u0441'=>'с', '\u0442'=>'т', '\u0443'=>'у', '\u0444'=>'ф', '\u0445'=>'х', '\u0446'=>'ц',
    '\u0447'=>'ч', '\u0448'=>'ш', '\u0449'=>'щ', '\u044a'=>'ъ', '\u044b'=>'ы', '\u044c'=>'ь',
    '\u044d'=>'э', '\u044e'=>'ю', '\u044f'=>'я');
    return strtr(json_encode($return),$trans);
}
 


?>