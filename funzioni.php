<?php

require 'db_connessione.php';

error_reporting(E_ALL);
ini_set( 'display_errors','1'); 


function check_login()
{
    if(empty($_SESSION['info'])){

        header("Location: login.php");
        die;
    }
}

?> 