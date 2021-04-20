<?php


if(isset($_GET['EmailNew'])){
 
	include("bd.php");
    session_start;

    $email = htmlspecialchars($_GET['EmailNew']);
	$mymail=$_SESSION['SessionId'];


	 $resCheckEmail=mysql_query("SELECT `Email` FROM `users` WHERE `Email`='$email'");
  $dataCheckEmail=mysql_fetch_array($resCheckEmail);       
   if(!empty($dataCheckEmail['Email']))	{
//	if ($dataCheckEmail['Email'] == $Email) { 
//	echo "0";
//	  } else {
	  echo "1";
//}
	} else {
	echo "0";
	}
	


	 }


?>