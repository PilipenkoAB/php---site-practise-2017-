<?php



if(isset($_GET['Username'])){

	include("bd.php");

     $username = htmlspecialchars($_GET['Username']);
	 
	
	$bd_username=mysql_query("SELECT Username FROM `users` WHERE `Username`='$username' ");
	$data_username=mysql_fetch_array($bd_username);
	
     if($username == $data_username['Username']){
         echo "1";
     }else{
          echo "0";
     }
}

if(isset($_GET['Email'])){

	include("bd.php");

     $email = htmlspecialchars($_GET['Email']);
	 
	
	$bd_email=mysql_query("SELECT Email FROM `users` WHERE `Email`='$email' ");
	$data_email=mysql_fetch_array($bd_email);
	
     if($email == $data_email['Email']){
         echo "1";
     }else{
          echo "0";
     }
}
?>