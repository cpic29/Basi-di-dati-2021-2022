<?php

include 'funzioni.php';

if(isset($_POST['val'])){
    
    $id_autore = $_POST['val'];

    $query = "SELECT * FROM utente WHERE id_utente = ? LIMIT 1";
    $result = mysqli_prepare($con, $query);

    if($result){

        mysqli_stmt_bind_param($result,'i', $id_autore);
  
        mysqli_stmt_execute($result);

        $stm = mysqli_stmt_get_result($result);

        if(mysqli_num_rows($stm) > 0){

        $post_data = mysqli_fetch_assoc($stm);

        $_SESSION['utente'] = $post_data;
        }
    }

}

?>