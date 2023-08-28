<?php 

include 'funzioni.php';

ini_set('display_errors', 1); 
error_reporting(E_ALL);

if(isset($_POST['img']) && isset($_POST['id'])){

    $id = $_POST['id'];

    $query = "UPDATE post set immagine = NULL where id_post = '$id' limit 1";
    $result = mysqli_query($con, $query);


    if(file_exists($_POST['img'])){
        unlink($_POST['img']);
    }

    $_SESSION['post']['immagine'] = NULL;
}
?>