<?php
//Запускаем сессию для работы с куками
session_start();
//Так как пользователь хотел выйти,
//удаляем ему логин и id из кукисов
unset($_SESSION['SessionId']);

session_destroy();  
//Переадресовываем на главную
header("location: index.php");
?>