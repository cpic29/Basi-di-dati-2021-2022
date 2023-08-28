
<?php

require 'funzioni.php';
error_reporting(E_ALL);
ini_set( 'display_errors','1'); 


if(isset($_POST['azione']) && isset($_POST['us']) && isset($_POST['post'])){
    
    $azione = $_POST['azione'];
    $id_user = $_POST['us'];
    $id_post = $_POST['post'];

    //se l'azione è like
    if($azione == "L"){
        $query = "INSERT into likes (id_user, post_id) values ('$id_user','$id_post')";
        $result = mysqli_query($con, $query);
        

    //l'azione è unlike
    } else {

        $query = "DELETE from likes where id_user = '$id_user' AND post_id = '$id_post'";
        $result = mysqli_query($con, $query);
        
    }
}

?>