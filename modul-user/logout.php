<?php
   session_start(); 
   session_destroy(); 


   $logout = "index.php";         

   header("Location: $logout"); 
   exit(); 


?>