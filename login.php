<?php
/* Старт Сессии */ 
session_start();

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

 
 
 
//Если пришли данные на обработку
if(isset($_POST['Username']) && isset($_POST['Password']))
{

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
 
 
 // задаем пустые значения для email системы - костыль короче.
 $dataEmail=0;
 $GroupEmailId=0;
//Записываем все в переменные
    $usernameoremail=htmlspecialchars(trim($_POST['Username']));
    $password=htmlspecialchars(trim($_POST['Password']));
    
	$salt = '$2a$10$'.substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(),mt_rand()))), 0, 22) . '$';
    $hashed_password = crypt($password, $salt);
	
	
//Достаем из таблицы инфу о пользователе по логину по пришедшим данным
    $resLogin=$BD->query("SELECT `Username`, `Password`, `GroupId`, `id` FROM `users` WHERE `Username`='$usernameoremail' ");
    $dataLogin=$resLogin->fetch_assoc();
			$LoginPass=$dataLogin['Password'];
 
//Если такого нет, то проверяем пришедшие данные по почте
    if(empty($dataLogin['Username']))
    {
    $resEmail=$BD->query("SELECT `Email`, `Password`, `GroupId`, `id` FROM `users` WHERE `Email`='$usernameoremail' ");
    $dataEmail=$resEmail->fetch_assoc();  
		$EmailPass=$dataEmail['Password'];
	   
	   if(empty($dataEmail['Email']))
    {
	   die("Введенные данные неверны!123");
	   }
    }
	

/* Сравнение введенного пароля с паролем в базе (правильный метод сравнение через crypt) */
    if(crypt($hashed_password,$LoginPass)== $LoginPass)   {          // Сравнение пароля по логину
           if(crypt($hashed_password,$EmailPass)== $LoginPass) {	// Сравнение пароля по email
	   die("Введенный пароль неверен!123");
		   }
    }
	

	
	$GroupLoginId=$dataLogin['GroupId'];
	$GroupEmailId=$dataEmail['GroupId'];
//Запускаем пользователю сессию

 // Генерация GUID
function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}
 
//проверяем группу пользователя

 if($GroupLoginId==1) {

 $GUID = getGUID();

if($BD->query("UPDATE `users` SET `SessionGUID`='$GUID' WHERE `Username`='$usernameoremail'")){
    $_SESSION['SessionId']=$GUID;
//Переадресовываем на главную для работодателя
		     header("location: freelance-workspace/index.php");
}else
{
echo "Error! ---->";
}
 }
 
 if($GroupEmailId==1) {
$GUID = getGUID();

if($BD->query("UPDATE `users` SET `SessionGUID`='$GUID' WHERE `Email`='$usernameoremail'")){
    $_SESSION['SessionId']=$GUID;
//Переадресовываем на главную для работодателя
		     header("location: freelance-workspace/index.php");
}
else
{
echo "Error! ---->";
}
 }
 
 
 
  if($GroupLoginId==2) {
$GUID = getGUID();

if($BD->query("UPDATE `users` SET `SessionGUID`='$GUID' WHERE `Username`='$usernameoremail'")){

    $_SESSION['SessionId']=$GUID;
//Переадресовываем на главную для работодателя
		     header("location: employer-workspace/index.php");
}
else
{
echo "Error! ---->";
}
 }
 
 if($GroupEmailId==2) {
$GUID = getGUID();

if($BD->query("UPDATE `users` SET `SessionGUID`='$GUID' WHERE `Email`='$usernameoremail'")){
    $_SESSION['SessionId']=$GUID;
//Переадресовываем на главную для работодателя
		     header("location: employer-workspace/index.php");
}
else
{
echo "Error! ---->";
}

 
 }

/* Закрываем соединение */ 
$BD->close(); 	
}
 
?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<html>

<head>

<link href="styles_index.css" rel="stylesheet" type="text/css" />

</head>

<body class="Main_Body">
<div id="Main_Section">


<header  class="GlobalHeader">
<div class="header_1">
<div class="header_1_columns">
logo and ccылка - Зарегистрироваться
</div>
</div>
</header>



<section class="Login-type"  >
<div class="Login_main">
<div class="Login_submain">
<div class="Login_submain_1">
<h1>
Login
</h1>
<h3>
Welcome
</h3>
</div>
<form action="login.php" name="login" id="login" class="Login_form" method="post">
<input type="text" name="Username" id="Username" value maxlength="1000" class="Login_form_username_or_email" placeholder="Username или Email">
<input type="password" name="Password" id="Password" class="Login_form_password" placeholder="Password">
<input type="button" id="send" value="Log in" class="Login_form_submit">
</form>
<h3>
Забыли пароль?
</h3>
</div>
</div>
</section>
</div>


<footer class="GlobalFooter Footer_Mini">
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


<script type="text/javascript" src="jquery-1.5.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="login.js" charset="utf-8"></script>

</body>

</html>
