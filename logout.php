<?php 

require "funzioni.php";

unset($_SESSION['info']);
session_destroy();

//redirect to login
header("Location: login.php");
die;

?>