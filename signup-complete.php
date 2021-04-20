<?php 
include("bd.php");
session_start();

// проверка как фрилансера
if(isset($_POST['FirstName']) && isset($_POST['LastName']) && isset($_POST['Username']) && isset($_POST['Email']) && isset($_POST['Country']) && isset($_POST['Password']) && isset($_POST['Type']) && isset($_POST['Password_check'])) {

//быборка по базе на проверку с совпадением usermane и email с базой 
$Username=htmlspecialchars(trim($_POST['Username']));
$Email=htmlspecialchars(trim($_POST['Email']));
$GroupId=htmlspecialchars(trim($_POST['Type']));

 $resCheckLogin=mysql_query("SELECT `Username` FROM `users` WHERE `Username`='$Username' ");
    $dataCheckLogin=mysql_fetch_array($resCheckLogin);
	 if(empty($dataCheckLogin['Username'])) {
    $resCheckEmail=mysql_query("SELECT `Email` FROM `users` WHERE `Email`='$Email' ");
    $dataCheckEmail=mysql_fetch_array($resCheckEmail);       
 	   if(!empty($dataCheckEmail['Email']))	{
		die("ОШИБКА РЕГИСТРАЦИИ ПОЧТА!");
		} 
	 }
   	if(!empty($dataCheckLogin['Username'])) {
		die("ОШИБКА РЕГИСТРАЦИИ ЛОГИН!");
	}

	
// проверка на правильность вводов одинаковых паролей
if ($_POST['Password'] == $_POST['Password_check']) {

$FirstName=htmlspecialchars(trim($_POST['FirstName']));
$LastName=htmlspecialchars(trim($_POST['LastName']));
$Country=htmlspecialchars(trim($_POST['Country']));
$Password=htmlspecialchars(trim($_POST['Password']));
$Activation=0;

// генерация активационного кода для подтверждения по email
$Activation_code=mt_rand(1000000000, 9999999999);

// генерация соли и шифрование пароля
$salt = '$2a$10$'.substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(),mt_rand()))), 0, 22) . '$';
$hashed_password = crypt($Password, $salt);
$DataCreation=date('Y-m-d H:i:s');


$Register="INSERT INTO `users`(`FirstName`,`LastName`,`Username`,`Email`,`Country`,`Password`,`GroupId`,`Activation`,`Activation_code`,`RegData`) VALUES ('$FirstName', '$LastName', '$Username', '$Email', '$Country', '$hashed_password', '$GroupId','$Activation','$Activation_code','$DataCreation')";
$result=mysql_query($Register);

if($result==true)
{
	header("location: login.php");
}
else
{
echo "Error! ---->". mysql_error();
}
}
else
{
echo "ошибка в заполнении данных";
}
}

// проверка как индивидуального и компании
if(isset($_POST['FirstName']) && isset($_POST['LastName']) && isset($_POST['Email']) && isset($_POST['Password']) && isset($_POST['Type']) && isset($_POST['Company'])) {

if ($_POST['Password'] == $_POST['Password_check']) {

$FirstName=htmlspecialchars(trim($_POST['FirstName']));
$LastName=htmlspecialchars(trim($_POST['LastName']));
$Email=htmlspecialchars(trim($_POST['Email']));
$Password=htmlspecialchars(trim($_POST['Password']));
$GroupId=htmlspecialchars(trim($_POST['Type']));
$Company=htmlspecialchars(trim($_POST['Company']));
$Activation=0;
$Activation_code=mt_rand(1000000000, 9999999999);
$DataCreation=date('Y-m-d H:i:s');

$salt = '$2a$10$'.substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(),mt_rand()))), 0, 22) . '$';
$hashed_password = crypt($Password, $salt);

$Register="INSERT INTO `users`(`FirstName`,`LastName`,`Email`,`Password`,`GroupId`,`Activation`,`Activation_code`,`Company`,`RegData`) VALUES ('$FirstName', '$LastName', '$Email', '$hashed_password', '$GroupId','$Activation','$Activation_code','$Company','$DataCreation')";
$result=mysql_query($Register);


if($result==true)
{
	header("location: login.php");
}
else
{
echo "Error! ---->". mysql_error();
}
}
else
{
echo "ошибка в заполнении данных";
}

}

?>