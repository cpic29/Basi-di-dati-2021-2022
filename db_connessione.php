<?php

session_start();

$dbhost="localhost";
$dbuser="root";
$dbpassword="";
$dbname="sitoweb";

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpassword,$dbname))
{

    die("La connessione al database non è riuscita!");
}

?>
 